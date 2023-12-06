<?php defined('ABSPATH') or die('No script kiddies please!');


function getProphetOmen_callback()
{
    if (
        !wp_verify_nonce($_POST['nonce'], 'horoscope-nonce')
        || !check_ajax_referer('Dnt3dUF8U4FRBNt3', 'SECURITY')
    ) {
        wp_send_json_error(['error_msg' => 'Forbidden!'], 403);
        exit;
    }

    global $wpdb;
    $tbl = $wpdb->prefix . DOPL_PROPHETS_OMENS_TABLE;
    $horoscopesIdsArray = [];
    $getIdsQuery = $wpdb->prepare("SELECT id FROM $tbl");
    $horoscopesIds = $wpdb->get_results($getIdsQuery);
    foreach ($horoscopesIds as $id) {
        $horoscopesIdsArray[] = intval($id->id);
    }
    $randomId = array_rand($horoscopesIdsArray, 1);
    $getOneHoroscopeQuery = $wpdb->prepare("SELECT * FROM $tbl WHERE id=%d", array($randomId));
    $selectedHoroscope = $wpdb->get_row($getOneHoroscopeQuery);

    if (!$selectedHoroscope) {
        wp_send_json_error(['error_msg' => 'خطای نامشخص!'], 400);
        exit;
    }

    $explode = explode("\n", $selectedHoroscope->h_dobeity);
    $selectedHoroscope->h_mesraOne = $explode[0];
    $selectedHoroscope->h_mesraTwo = $explode[1];
    $selectedHoroscope->h_separator = $explode[2];
    $selectedHoroscope->h_mesraThree = $explode[3];
    $selectedHoroscope->h_mesraFour = $explode[4];

    wp_send_json_success($selectedHoroscope, 200);
    exit;
}
add_action('wp_ajax_getProphetOmen', 'getProphetOmen_callback');
add_action('wp_ajax_nopriv_getProphetOmen', 'getProphetOmen_callback');

function showCoffeeOmen_callback()
{
    if (!wp_verify_nonce($_POST['nonce'], 'coffe-omen-nonce') || !check_ajax_referer('YDPagk5TQhIFlePOuPQY', 'SECURITY')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    global $wpdb;
    $tbl = $wpdb->prefix . DOPL_COFFEE_OMENS_TABLE;
    $horoscopesIdsArray = [];
    $horoscopesIds = $wpdb->get_results($wpdb->prepare("SELECT id FROM $tbl"));
    foreach ($horoscopesIds as $id) {
        $horoscopesIdsArray[] = intval($id->id);
    }

    $randomId = array_rand($horoscopesIdsArray, 1);
    $selectedHoroscope = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $tbl WHERE id=%d", array($randomId))
    );

    if (!$selectedHoroscope) {
        wp_send_json(['data' => null], 404);
        exit();
    }

    sleep(0.5);
    wp_send_json([
        'success' => true,
        'data' => [
            'name' => trim(sanitize_text_field($_POST['name'])),
            'omen' => $selectedHoroscope->coffee_omen
        ],
    ], 200);
    exit();
}
add_action('wp_ajax_showCoffeeOmen', 'showCoffeeOmen_callback');
add_action('wp_ajax_nopriv_showCoffeeOmen', 'showCoffeeOmen_callback');
