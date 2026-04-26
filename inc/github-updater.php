<?php
defined('ABSPATH') || exit;

define('WC_GITHUB_OWNER', 'vakvriend');
define('WC_GITHUB_REPO', 'MijnWarmtePompOfferte');
define('WC_GITHUB_TRANSIENT', 'wc_github_theme_release');
define('WC_GITHUB_PACKAGE_SCHEME', 'wc-github-theme://');

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

function wc_github_get_token() {
    return trim((string) get_option('wc_github_access_token', ''));
}

function wc_github_request_headers($accept = 'application/vnd.github+json') {
    $headers = array(
        'Accept'               => $accept,
        'User-Agent'           => 'Vakvriend-Warmtepomp-Campagne-Updater',
        'X-GitHub-Api-Version' => '2022-11-28',
    );

    $token = wc_github_get_token();
    if ($token) {
        $headers['Authorization'] = 'Bearer ' . $token;
    }

    return $headers;
}

function wc_github_package_url($tag) {
    return WC_GITHUB_PACKAGE_SCHEME . rawurlencode((string) $tag);
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
        'headers' => wc_github_request_headers(),
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
    $asset_api_url = '';
    $asset_name = '';
    if (!empty($release['assets']) && is_array($release['assets'])) {
        foreach ($release['assets'] as $asset) {
            if (!empty($asset['browser_download_url']) && preg_match('/\.zip$/i', $asset['name'] ?? '')) {
                $asset_url = $asset['browser_download_url'];
                $asset_api_url = $asset['url'] ?? '';
                $asset_name = $asset['name'] ?? '';
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
        'package'      => wc_github_package_url($release['tag_name']),
        'asset_api_url'=> esc_url_raw($asset_api_url),
        'asset_name'   => sanitize_file_name($asset_name ?: 'theme-' . $release['tag_name'] . '.zip'),
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
        'package'     => $release['package'],
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
        'download_link' => $release['package'],
        'sections'      => array(
            'description' => 'Vakvriend Warmtepomp Campagne theme.',
            'changelog'   => $release['body'] ?: 'Bekijk de GitHub release voor wijzigingen.',
        ),
    );
}
add_filter('themes_api', 'wc_github_theme_update_info', 10, 3);

function wc_github_download_package($reply, $package, $upgrader, $hook_extra) {
    if (!is_string($package) || strpos($package, WC_GITHUB_PACKAGE_SCHEME) !== 0) {
        return $reply;
    }

    if (!current_user_can('update_themes')) {
        return new WP_Error('wc_github_forbidden', 'U heeft geen rechten om thema-updates te downloaden.');
    }

    $release = wc_github_fetch_latest_release(true);
    if (!$release || (empty($release['asset_api_url']) && empty($release['download_url']))) {
        return new WP_Error('wc_github_no_package', 'Geen GitHub release zip gevonden.');
    }

    $download_url = !empty($release['asset_api_url']) ? $release['asset_api_url'] : $release['download_url'];
    $accept = !empty($release['asset_api_url']) ? 'application/octet-stream' : 'application/vnd.github+json';
    $tmp = wp_tempnam($release['asset_name'] ?? 'vakvriend-theme.zip');

    if (!$tmp) {
        return new WP_Error('wc_github_temp_file', 'Kan geen tijdelijk updatebestand maken.');
    }

    $response = wp_remote_get($download_url, array(
        'timeout'  => 60,
        'stream'   => true,
        'filename' => $tmp,
        'headers'  => wc_github_request_headers($accept),
    ));

    if (is_wp_error($response)) {
        @unlink($tmp);
        return $response;
    }

    $code = (int) wp_remote_retrieve_response_code($response);
    if ($code < 200 || $code >= 300) {
        @unlink($tmp);
        return new WP_Error('wc_github_download_failed', 'GitHub theme download mislukt met HTTP status ' . $code . '.');
    }

    return $tmp;
}
add_filter('upgrader_pre_download', 'wc_github_download_package', 10, 4);

function wc_github_settings_init() {
    register_setting('wc_github_updates', 'wc_github_access_token', array(
        'type'              => 'string',
        'sanitize_callback' => 'wc_github_sanitize_token',
        'default'           => '',
    ));

    add_settings_section(
        'wc_github_updates_main',
        'GitHub theme updates',
        '__return_false',
        'wc-github-updates'
    );

    add_settings_field(
        'wc_github_access_token',
        'GitHub token',
        'wc_github_token_field',
        'wc-github-updates',
        'wc_github_updates_main'
    );
}
add_action('admin_init', 'wc_github_settings_init');

function wc_github_sanitize_token($value) {
    wc_github_clear_release_cache();
    return trim((string) $value);
}

function wc_github_token_field() {
    $value = wc_github_get_token();
    echo '<input type="password" name="wc_github_access_token" value="' . esc_attr($value) . '" class="regular-text" autocomplete="off" placeholder="github_pat_...">';
    echo '<p class="description">Gebruik een fine-grained token met alleen toegang tot de repository ' . esc_html(WC_GITHUB_OWNER . '/' . WC_GITHUB_REPO) . ' en minimaal Contents: read. Nodig als de repository private is.</p>';
}

function wc_github_updates_menu() {
    add_options_page(
        'Theme updates',
        'Theme updates',
        'manage_options',
        'wc-github-updates',
        'wc_github_updates_page'
    );
}
add_action('admin_menu', 'wc_github_updates_menu');

function wc_github_updates_page() {
    ?>
    <div class="wrap">
        <h1>Theme updates</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wc_github_updates');
            do_settings_sections('wc-github-updates');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function wc_github_clear_release_cache() {
    delete_site_transient(WC_GITHUB_TRANSIENT);
}
add_action('upgrader_process_complete', 'wc_github_clear_release_cache');
