<?php defined('ABSPATH') or die('No script kiddies please!');
require_once(DAILYOMENS_ROOTDIR . 'env.php');

/**
 * 
 */
function daily_simple_omen_callback()
{
    $token = $_GET['token'] ?? '';
    if (empty($token)) {
        exit;
    }

    if (!boolval(validateRestApiToken($token, getenv('AES_PASSWORD'), 'SimpleOmen'))) {
        wp_send_json_error([
            'msg' => 'Token is invalid :('
        ], 401);
        exit;
    }

    // Check if post should published yet or not
    global $wpdb;
    $omensLoggerTable = $wpdb->prefix . OMENS_LOGGER_TABLE;
    // $log = $wpdb->prepare($wpdb->get_var(""));
    // $result = shoudDailyOmenPublish();
    // if (!$result) {
    //     wp_send_json([
    //         'success' => false,
    //         'post_published' => 'nup'
    //     ], 400);
    //     exit;
    // }

    // Publish the post
    // $resetOmens = function ($wpdb, $table) {
    //     $unUsedOmens = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM $table WHERE selected=%s", array('0')));
    //     if (intval($unUsedOmens) < 12) {
    //         $resetQuery = $wpdb->query($wpdb->prepare(
    //             "UPDATE $table SET selected=%s",
    //             array('0')
    //         ));
    //     }
    //     return true;
    // };
    // $resetOmens($wpdb, $table);

    $date = getCurrentShamsiDate('l j F Y', null);
    $postTitle = 'فال روزانه' . ' ' . $date;




    wp_send_json_success([
        'msg' => $postTitle,
    ], 200);
}

function fixed_daily_post_callback()
{
    //
}
