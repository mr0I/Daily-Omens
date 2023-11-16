<?php defined('ABSPATH') or die('No script kiddies please!');
require_once(DAILYOMENS_ROOTDIR . 'env.php');


function daily_simple_omen_callback()
{
    $token = $_GET['token'] ?? '';

    if (!empty($token)) {
        if (!boolval(validateRestApiToken($token, getenv('AES_PASSWORD'), 'SimpleOmen'))) {
            wp_send_json_error([
                'msg' => 'Token is invalid :('
            ], 401);
            exit;
        }

        wp_send_json_success([
            'msg' => 'OK'
        ], 200);
    }
}

function fixed_daily_post_callback()
{
    //
}
