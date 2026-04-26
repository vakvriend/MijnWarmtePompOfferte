<?php
defined('ABSPATH') || exit;

define('WC_GITHUB_OWNER', 'vakvriend');
define('WC_GITHUB_REPO', 'MijnWarmtePompOfferte');
define('WC_GITHUB_TRANSIENT', 'wc_github_theme_release');

function wc_github_theme_slug() {
    return get_stylesheet();
}

function wc_github_release_api_url() {
    return sprintf(
        'https://api.github.com/repos/%s/%s/releases/latest',
        rawurlencode(WC_GITHUB_OWNER),
        rawurlencode(WC_GITHUB_REPO)
    );
}

function wc_github_normalize_version($version) {
    return ltrim(trim((string) $version), "vV \t\n\r\0\x0B");
}

function wc_github_fetch_latest_release($force = false) {
    if (!$force) {
        $cached = get_site_transient(WC_GITHUB_TRANSIENT);
        if (is_array($cached)) {
            return $cached;
        }
    }

    $response = wp_remote_get(wc_github_release_api_url(), array(
        'timeout' => 10,
        'headers' => array(
            'Accept'     => 'application/vnd.github+json',
            'User-Agent' => 'Vakvriend-Warmtepomp-Campagne-Updater',
        ),
    ));

    if (is_wp_error($response)) {
        return null;
    }

    if ((int) wp_remote_retrieve_response_code($response) !== 200) {
        return null;
    }

    $release = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($release) || empty($release['tag_name'])) {
        return null;
    }

    $asset_url = '';
    if (!empty($release['assets']) && is_array($release['assets'])) {
        foreach ($release['assets'] as $asset) {
            if (!empty($asset['browser_download_url']) && preg_match('/\.zip$/i', $asset['name'] ?? '')) {
                $asset_url = $asset['browser_download_url'];
                break;
            }
        }
    }

    $download_url = $asset_url ?: ($release['zipball_url'] ?? '');
    if (!$download_url) {
        return null;
    }

    $data = array(
        'version'      => wc_github_normalize_version($release['tag_name']),
        'tag'          => $release['tag_name'],
        'download_url' => esc_url_raw($download_url),
        'details_url'  => esc_url_raw($release['html_url'] ?? 'https://github.com/' . WC_GITHUB_OWNER . '/' . WC_GITHUB_REPO),
        'body'         => wp_kses_post($release['body'] ?? ''),
    );

    set_site_transient(WC_GITHUB_TRANSIENT, $data, 6 * HOUR_IN_SECONDS);

    return $data;
}

function wc_github_check_for_update($transient) {
    if (empty($transient->checked) || !isset($transient->checked[wc_github_theme_slug()])) {
        return $transient;
    }

    $current_version = wc_github_normalize_version($transient->checked[wc_github_theme_slug()]);
    $release = wc_github_fetch_latest_release();

    if (!$release || empty($release['version']) || empty($release['download_url'])) {
        return $transient;
    }

    if (version_compare($release['version'], $current_version, '<=')) {
        return $transient;
    }

    $transient->response[wc_github_theme_slug()] = array(
        'theme'       => wc_github_theme_slug(),
        'new_version' => $release['version'],
        'url'         => $release['details_url'],
        'package'     => $release['download_url'],
    );

    return $transient;
}
add_filter('pre_set_site_transient_update_themes', 'wc_github_check_for_update');

function wc_github_theme_update_info($result, $action, $args) {
    if ($action !== 'theme_information' || empty($args->slug) || $args->slug !== wc_github_theme_slug()) {
        return $result;
    }

    $release = wc_github_fetch_latest_release(true);
    if (!$release) {
        return $result;
    }

    return (object) array(
        'name'          => wp_get_theme()->get('Name'),
        'slug'          => wc_github_theme_slug(),
        'version'       => $release['version'],
        'author'        => 'Vakvriend',
        'homepage'      => $release['details_url'],
        'download_link' => $release['download_url'],
        'sections'      => array(
            'description' => 'Vakvriend Warmtepomp Campagne theme.',
            'changelog'   => $release['body'] ?: 'Bekijk de GitHub release voor wijzigingen.',
        ),
    );
}
add_filter('themes_api', 'wc_github_theme_update_info', 10, 3);

function wc_github_clear_release_cache() {
    delete_site_transient(WC_GITHUB_TRANSIENT);
}
add_action('upgrader_process_complete', 'wc_github_clear_release_cache');
