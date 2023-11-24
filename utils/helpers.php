<?php defined('ABSPATH') or die('No script kiddies please!');


if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}
if (!is_plugin_active('wp-parsidate/wp-parsidate.php') && !class_exists('bn_parsidate')) {
    include_once(DAILYOMENS_ROOTDIR . 'plugins/parsidate.php');
}

/**
 * @param string $token, string $password, string $type
 * @return boolean
 */
if (!function_exists('validateRestApiToken')) {
    function validateRestApiToken(string $token, string $password, string $type)
    {
        $iv_size    = openssl_cipher_iv_length(AES_METHOD);
        $data       = explode(":", $token);
        $iv         = hex2bin($data[0]);
        $cipherText = hex2bin($data[1]);
        $reqToken   = openssl_decrypt($cipherText, AES_METHOD, $password, OPENSSL_RAW_DATA, $iv);
        $explode = explode(';', $reqToken);

        if ($explode[1] !== $type || time() - $explode[0] > 60) {
            return 0;
        }
        return 1;
    }
}

/**
 * @param string $format, string $type
 * @return string
 */
if (!function_exists('getCurrentShamsiDate')) {
    function getCurrentShamsiDate($format, $type)
    {
        $format = $format ?? 'd-m-Y';
        $type = $type ?? 'full';

        $parsiDate = new bn_parsidate();
        switch ($type) {
            case 'full':
                $date = _convert_to_number($parsiDate->persian_date($format, 'tomorrow'));
                break;
            case 'month':
                $date = intval(_convert_to_number($parsiDate->persian_date('m', 'tomorrow')));
                break;
            default:
                break;
        }


        return $date;
    }
}
