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

/**
 * @param string $format, string $type, date $date
 * @return string 
 */
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
 * @return bool 
 */
if (!function_exists('shoudDailyOmenPublish')) {
    function shoudDailyOmenPublish($omen_type)
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
                    $omen_type => 1
                ])
            ]);
            if ($insert) return 1;
        }

        $existedLogs = json_decode($currentDateRow->logs, true);
        // if logs is null
        if (!$existedLogs) {
            $updatedAt = date('Y-m-d H:i:s');
            $update = $wpdb->query($wpdb->prepare(
                "UPDATE $omensLoggerTable 
                SET `logs` = JSON_OBJECT('$omen_type', 1), `updated_at`='$updatedAt'
                WHERE date=%s",
                array(
                    $tomorrowDate
                )
            ));
            if ($update) return 1;
        }

        $currentStatus = $existedLogs[$omen_type];
        // if logs json is 0
        if (!boolval($currentStatus)) {
            $updatedAt = date('Y-m-d H:i:s');
            $update = $wpdb->query($wpdb->prepare("UPDATE $omensLoggerTable 
            SET `logs` = JSON_SET(`logs`, '$.$omen_type',1), `updated_at`='$updatedAt'
            WHERE date=%s", array(
                $tomorrowDate
            )));
            if ($update) return 1;
        }

        return 0;
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


if (!function_exists('resetDailyOmens')) {
    function resetDailyOmens($db, $table)
    {
        $unusedOmens = $db->get_var($db->prepare("SELECT COUNT(id) FROM $table WHERE selected=%s", array('0')));
        if (intval($unusedOmens) < 12) {
            $resetQuery = $db->query($db->prepare(
                "UPDATE $table SET selected=%s",
                array('0')
            ));
        }
        return true;
    };
}

if (!function_exists('insertDailyOmenPost')) {
    function insertDailyOmenPost($post_title, $post_content, $post_excerpt, $post_slug = null)
    {
        return wp_insert_post(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_name' => $post_slug ?? $post_title,
            'post_title' => $post_title,
            'post_name' => $post_title,
            'post_content' => $post_content,
            'post_excerpt' => $post_excerpt
        ));
    };
}

if (!function_exists('insertDailyUniqueOmens')) {
    function insertDailyUniqueOmens($db, $table, $unique_omens_table, $post_id, $omen_type): void
    {
        $remainedIdsQuery = $db->get_results($db->prepare(
            "SELECT id FROM $table WHERE selected=%s",
            array('0')
        ));
        $remainedIds = [];
        foreach ($remainedIdsQuery as $key => $value) {
            array_push($remainedIds, $value->id);
        }
        $randomMonthsKeys = generateRandomIds($remainedIds);
        $placeholder = bindINOperatorPlaceholder($randomMonthsKeys);
        $selector = $omen_type === 'simple_omen' ? 'omen' : '*';
        $randomOmens = $db->get_results($db->prepare(
            "SELECT $selector FROM $table WHERE id IN ($placeholder)",
            $randomMonthsKeys
        ));
        $update = $db->query($db->prepare(
            "UPDATE $table SET selected='1' WHERE id IN ($placeholder)",
            $randomMonthsKeys
        ));
        switch ($omen_type) {
            case 'simple_omen':
                $insert = $db->insert($unique_omens_table, array(
                    'post_id' => $post_id,
                    'farvardin' => $randomOmens[0]->omen,
                    'ordibehesht' => $randomOmens[1]->omen,
                    'khordad' => $randomOmens[2]->omen,
                    'tir' => $randomOmens[3]->omen,
                    'mordad' => $randomOmens[4]->omen,
                    'shahrivar' => $randomOmens[5]->omen,
                    'mehr' => $randomOmens[6]->omen,
                    'aban' => $randomOmens[7]->omen,
                    'azar' => $randomOmens[8]->omen,
                    'dey' => $randomOmens[9]->omen,
                    'bahman' => $randomOmens[10]->omen,
                    'esfand' => $randomOmens[11]->omen,
                    'createdAt' => date('Y-m-d'),
                ), array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
                break;
            case 'hafez_omen':
                $insert = $db->insert($unique_omens_table, array(
                    'post_id' => $post_id,
                    'farvardin' => json_encode(_jsonOmenGen($randomOmens, 0)),
                    'ordibehesht' => json_encode(_jsonOmenGen($randomOmens, 1)),
                    'khordad' => json_encode(_jsonOmenGen($randomOmens, 2)),
                    'tir' => json_encode(_jsonOmenGen($randomOmens, 3)),
                    'mordad' => json_encode(_jsonOmenGen($randomOmens, 4)),
                    'shahrivar' => json_encode(_jsonOmenGen($randomOmens, 5)),
                    'mehr' => json_encode(_jsonOmenGen($randomOmens, 6)),
                    'aban' => json_encode(_jsonOmenGen($randomOmens, 7)),
                    'azar' => json_encode(_jsonOmenGen($randomOmens, 8)),
                    'dey' => json_encode(_jsonOmenGen($randomOmens, 9)),
                    'bahman' => json_encode(_jsonOmenGen($randomOmens, 10)),
                    'esfand' => json_encode(_jsonOmenGen($randomOmens, 11)),
                    'createdAt' => date('Y-m-d H:i:s'),
                ), array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
                break;
        }
    };
}

if (!function_exists('_jsonOmenGen')) {
    function _jsonOmenGen($omensArray, $index)
    {
        return array(
            'url' => $omensArray[$index]->url,
            'omen' => $omensArray[$index]->omen,
            'audio_url' => $omensArray[$index]->audio_url,
            'ghazal' => $omensArray[$index]->ghazal,
            'anchor_text' => $omensArray[$index]->anchor_text
        );
    }
}
