<?php
/**
 * Plugin Name: Vakvriend AI Chatbot
 * Description: AI warmtepomp chatbot powered by Claude. Gespecialiseerd in Qvantum, Nibe en ISDE-subsidie.
 * Version: 1.0.5
 * Author: Vakvriend
 */
defined('ABSPATH') || exit;

// ── Admin instellingen ────────────────────────────────────────
add_action('admin_menu', function() {
    add_options_page('AI Chatbot', 'AI Chatbot', 'manage_options', 'vk-chatbot', 'vk_chatbot_admin');
});

function vk_chatbot_admin() {
    if (isset($_POST['vk_save']) && check_admin_referer('vk_chatbot')) {
        update_option('vk_api_key', sanitize_text_field($_POST['vk_api_key']));
        update_option('vk_chat_actief', isset($_POST['vk_chat_actief']) ? 1 : 0);
        update_option('vk_chat_tel', sanitize_text_field($_POST['vk_chat_tel']));
        update_option('vk_chat_wa', sanitize_text_field($_POST['vk_chat_wa']));
        echo '<div class="notice notice-success"><p>✅ Opgeslagen!</p></div>';
    }
    $key    = get_option('vk_api_key', '');
    $actief = get_option('vk_chat_actief', 0);
    $tel    = get_option('vk_chat_tel', '075 234 0001');
    $wa     = get_option('vk_chat_wa', '31752340001');
    ?>
    <div class="wrap">
    <h1>🤖 Vakvriend AI Chatbot</h1>
    <p style="max-width:700px">De chatbot gebruikt Claude AI (Anthropic) en is gespecialiseerd in warmtepompen, Qvantum QA/QE/QG, Nibe en ISDE-subsidie. Bij doorverbinden verschijnen de contactknoppen.</p>
    <hr>
    <form method="post">
    <?php wp_nonce_field('vk_chatbot'); ?>
    <table class="form-table">
        <tr>
            <th>🔑 Anthropic API Key</th>
            <td>
                <input type="password" name="vk_api_key" value="<?=esc_attr($key)?>" style="width:420px;font-family:monospace;padding:8px" placeholder="sk-ant-api03-...">
                <p class="description">Maak een gratis account op <a href="https://console.anthropic.com" target="_blank">console.anthropic.com</a> → <strong>API Keys</strong> → <strong>Create Key</strong>. Kopieer de sleutel en plak die hier.</p>
                <p class="description" style="color:#d97706">⚠️ Let op: API gebruik kost geld per bericht (~€0,002 per gesprek). Stel een budgetlimiet in op console.anthropic.com</p>
            </td>
        </tr>
        <tr>
            <th>Chatbot actief</th>
            <td><label><input type="checkbox" name="vk_chat_actief" <?=checked($actief,1,false)?>> Chatbot weergeven op de website</label></td>
        </tr>
        <tr>
            <th>📞 Telefoonnummer adviseur</th>
            <td><input type="text" name="vk_chat_tel" value="<?=esc_attr($tel)?>" style="width:200px"> <span class="description">Wordt getoond als de klant doorverbonden wil worden</span></td>
        </tr>
        <tr>
            <th>💬 WhatsApp nummer</th>
            <td><input type="text" name="vk_chat_wa" value="<?=esc_attr($wa)?>" style="width:200px"> <span class="description">Formaat: 31752340001 (zonder +)</span></td>
        </tr>
    </table>
    <p class="submit"><input type="submit" name="vk_save" class="button button-primary" value="Instellingen opslaan"></p>
    </form>

    <?php if ($actief && $key): ?>
    <hr>
    <h2>✅ Chatbot is actief</h2>
    <p>De AI chatbot verschijnt rechtsonder op uw website. Bezoekers kunnen direct vragen stellen over warmtepompen.</p>
    <?php elseif (!$key): ?>
    <hr>
    <div style="background:#fef3c7;padding:16px;border-radius:8px;border-left:4px solid #d97706">
        <strong>⚠️ Vul eerst uw API key in</strong><br>
        Zonder API key werkt de chatbot niet. Maak een account aan op <a href="https://console.anthropic.com" target="_blank">console.anthropic.com</a>.
    </div>
    <?php endif; ?>
    </div>
    <?php
}

// ── AJAX endpoint voor chatbot ────────────────────────────────
add_action('wp_ajax_vk_chat', 'vk_chat_handler');
add_action('wp_ajax_nopriv_vk_chat', 'vk_chat_handler');

function vk_chat_handler() {
    check_ajax_referer('vk_chat_nonce', 'nonce');

    $api_key  = get_option('vk_api_key', '');
    $messages = isset($_POST['messages']) ? json_decode(stripslashes($_POST['messages']), true) : [];

    if (!$api_key) {
        wp_send_json_error(['message' => 'API key niet ingesteld.']);
        return;
    }
    if (empty($messages) || !is_array($messages)) {
        wp_send_json_error(['message' => 'Geen berichten ontvangen.']);
        return;
    }

    // Saniteer berichten
    $clean_messages = [];
    foreach ($messages as $msg) {
        if (!isset($msg['role'], $msg['content'])) continue;
        if (!in_array($msg['role'], ['user', 'assistant'])) continue;
        $clean_messages[] = [
            'role'    => $msg['role'],
            'content' => substr(sanitize_textarea_field($msg['content']), 0, 1000),
        ];
    }
    if (empty($clean_messages)) {
        wp_send_json_error(['message' => 'Ongeldige berichten.']);
        return;
    }

    // Systeem prompt — warmtepomp specialist
    $system = "Je bent een vriendelijke en deskundige warmtepomp adviseur van Vakvriend Installatiebedrijf in Nederland.

OVER VAKVRIEND:
- Gecertificeerd installateur voor Qvantum en Nibe warmtepompen
- Actief door heel Nederland
- Meer dan 200 warmtepomp installaties
- 4,6/5 beoordeling
- Telefoon: 075 234 0001 | Website: vakvriend.nl

QVANTUM KENNIS:
- QA: lucht/water warmtepomp met QH-XL hydromodule. Geen aparte boiler nodig. Thermische batterij tot 90°C. COP 4,5. R290 koudemiddel (GWP=3). FlexReady voor slim energiebeheer.
- QE: ventilatie warmtepomp. Geen buitenunit. Haalt warmte uit afvoerventilatielucht. Thermische batterij. Met QS-accessoire ook WTW-ventilatie. Ideaal voor appartementen en goed geïsoleerde woningen.
- QG: water/water warmtepomp voor bodem/bronsystemen. Hoogste rendement.
- Alle Qvantum pompen hebben een geïntegreerde thermische batterij die als thuisbatterij werkt — laden bij lage stroomprijzen, gebruiken bij hoge prijzen.
- FlexReady: communiceren als slim netwerk, helpen bij netcongestie.

NIBE KENNIS:
- F2040/S2125: lucht/water warmtepomp, A+++ label
- S-serie: bodemwarmtepomp, hoogste COP
- F730: hybride warmtepomp (combineert met CV-ketel)
- 30+ jaar ervaring, Scandinavisch kwaliteitsmerk

SUBSIDIES (ISDE 2026):
- Lucht/water warmtepomp: startbedrag €1.025 + €225/kW + €200 bonus A+++ = bijv. 5kW A+++ geeft €2.125
- Bodemwarmtepomp: significant hoger, kan oplopen tot €8.000+
- Vakvriend regelt de volledige ISDE-aanvraag bij de RVO

GRONDBORINGEN:
- Vakvriend verzorgt boringen door heel Nederland
- Inclusief vergunningsaanvraag bij de gemeente

STIJL:
- Antwoord in het Nederlands
- Vriendelijk maar professioneel
- Geef concrete, eerlijke adviezen
- Houd antwoorden beknopt (max 3-4 zinnen)
- Als iemand een offerte wil of doorverbonden wil worden, zeg dan: 'IK_VERBIND_DOOR' aan het einde van je bericht
- Als iemand vraagt over prijzen, zeg dan dat een gratis offerte de exacte prijs geeft";

    // Roep Anthropic API aan
    $response = wp_remote_post('https://api.anthropic.com/v1/messages', [
        'timeout' => 30,
        'headers' => [
            'x-api-key'         => $api_key,
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ],
        'body' => json_encode([
            'model'      => 'claude-haiku-4-5-20251001',
            'max_tokens' => 300,
            'system'     => $system,
            'messages'   => $clean_messages,
        ]),
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error(['message' => 'Verbindingsfout. Probeer het opnieuw.']);
        return;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (!isset($body['content'][0]['text'])) {
        wp_send_json_error(['message' => 'Geen antwoord van de AI. Controleer uw API key.']);
        return;
    }

    $antwoord = $body['content'][0]['text'];
    $doorverbinden = strpos($antwoord, 'IK_VERBIND_DOOR') !== false;
    $antwoord = str_replace('IK_VERBIND_DOOR', '', $antwoord);
    $antwoord = trim($antwoord);

    wp_send_json_success([
        'message'       => $antwoord,
        'doorverbinden' => $doorverbinden,
        'tel'           => get_option('vk_chat_tel', '075 234 0001'),
        'wa'            => get_option('vk_chat_wa', '31752340001'),
    ]);
}

// ── Frontend: chatbot widget ──────────────────────────────────
add_action('wp_enqueue_scripts', function() {
    if (!get_option('vk_chat_actief', 0) || !get_option('vk_api_key', '')) return;
    wp_enqueue_style('vk-chat-widget', plugin_dir_url(__FILE__) . 'chatbot.css', [], '1.0.5');
    wp_enqueue_script('vk-chat-widget', plugin_dir_url(__FILE__) . 'chatbot.js', [], '1.0.5', true);
    wp_localize_script('vk-chat-widget', 'vkChat', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('vk_chat_nonce'),
        'tel'     => get_option('vk_chat_tel', '075 234 0001'),
        'wa'      => get_option('vk_chat_wa', '31752340001'),
    ]);
});

add_action('wp_footer', function() {
    if (!get_option('vk_chat_actief', 0) || !get_option('vk_api_key', '')) return;
    echo '<div id="vk-chat-widget"></div>';
});
