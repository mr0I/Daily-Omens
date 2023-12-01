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
        require_once(DAILYOMENS_ROOTDIR . 'env.php');

        $iv_size    = openssl_cipher_iv_length(AES_METHOD);
        $data       = explode(":", $token);
        $iv         = hex2bin($data[0]);
        $cipherText = hex2bin($data[1]);
        $reqToken   = openssl_decrypt($cipherText, AES_METHOD, $password, OPENSSL_RAW_DATA, $iv);
        $explode = explode(';', $reqToken);

        if ($explode[1] !== $type || time() - $explode[0] > getenv('TOKEN_EXPIRE_TIME')) {
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

if (!function_exists('convertToShamsiDate')) {
    function convertToShamsiDate($format, $type, $date)
    {
        $format = $format ?? 'd-m-Y';
        $type = $type ?? 'full';
        $date = $date ?? 'now';

        $parsiDate = new bn_parsidate();
        switch ($type) {
            case 'full':
                $convertedDate = _convert_to_number($parsiDate->persian_date($format, $date));
                break;
            case 'month':
                $convertedDate = intval(_convert_to_number($parsiDate->persian_date('m', $date)));
                break;
            default:
                break;
        }

        return $convertedDate;
    }
}

/**
 * @param
 */
if (!function_exists('shoudDailyOmenPublish')) {
    function shoudDailyOmenPublish()
    {
        global $wpdb;
        $omensLoggerTable = $wpdb->prefix . DAILYOMENS_LOGGER_TABLE;

        $tomorrowDate = strtotime(date('Ymd') . ' + 1 days');
        $currentDateRow = $wpdb->get_row($wpdb->prepare("SELECT * FROM $omensLoggerTable WHERE date=%s", array(
            $tomorrowDate
        )));

        if (!$currentDateRow) {
            $insert =  $wpdb->insert($omensLoggerTable, [
                'date' => $tomorrowDate,
                'created_at' => date('Y-m-d H:i:s'),
                'logs' => json_encode([
                    'simple_daily' => 1
                ])
            ]);
            if ($insert) return 1;
        }

        $existedLogs = json_decode($currentDateRow->logs, true);
        if (!$existedLogs) {
            $updatedAt = date('Y-m-d H:i:s');
            $update = $wpdb->query($wpdb->prepare(
                "UPDATE $omensLoggerTable 
                SET `logs` = JSON_OBJECT('simple_daily', 1), `updated_at`='$updatedAt'
                WHERE date=%s",
                array(
                    $tomorrowDate
                )
            ));
            if ($update) return 1;
        }

        $currentStatus = $existedLogs['simple_daily'];
        if (!boolval($currentStatus)) {
            $updatedAt = date('Y-m-d H:i:s');
            $update = $wpdb->query($wpdb->prepare("UPDATE $omensLoggerTable 
            SET `logs` = JSON_SET(`logs`, '$.simple_daily',1), `updated_at`='$updatedAt'
            WHERE date=%s", array(
                $tomorrowDate
            )));
            if ($update) return 1;
        }

        return 0;

        // return array(
        //     'cdr ' => $currentDateRow,
        //     'el ' => $existedLogs,
        //     'cs ' => $currentStatus
        // );
    }
}

/**
 * @param array $input_array
 * @return string
 */
if (!function_exists('bindINOperatorPlaceholder')) {
    function bindINOperatorPlaceholder($input_array)
    {
        $filter = array_map(function ($v) {
            return "'" . esc_sql($v) . "'";
        }, $input_array);
        return implode(',', array_fill(0, count($filter), '%s'));
    }
}

/**
 * @param array $ids
 * @return array 
 */
if (!function_exists('generateRandomIds')) {
    function generateRandomIds($ids)
    {
        $randomKeys = array_rand($ids, 12);
        $randomIds = [];
        foreach ($randomKeys as $key) {
            $randomIds[] = $ids[$key];
        }
        shuffle($randomIds);

        return $randomIds;
    }
}


/**
 * @param string $str
 * @return string 
 */
if (!function_exists('_convert_to_number')) {
    function _convert_to_number($str)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];
        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $str);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }
}
