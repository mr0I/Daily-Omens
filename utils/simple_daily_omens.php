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
    $result = shoudDailyOmenPublish();
    if (!$result) {
        wp_send_json_error([
            'msg' => 'Can not publish again!'
        ], 400);
        exit;
    }

    // Publish the post
    global $wpdb;
    $table = $wpdb->prefix . DOPL_SIMPLE_OMENS_TABLE;
    // Reset omens publish loop
    $resetOmens = function ($wpdb, $table) {
        $unusedOmens = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM $table WHERE selected=%s", array('0')));
        if (intval($unusedOmens) < 12) {
            $resetQuery = $wpdb->query($wpdb->prepare(
                "UPDATE $table SET selected=%s",
                array('0')
            ));
        }
        return true;
    };
    $resetOmens($wpdb, $table);

    $date = getCurrentShamsiDate('l j F Y', null);
    $postTitle = 'فال روزانه' . ' ' . $date;
    $postId = wp_insert_post(array(
        'post_title' => $postTitle,
        'post_name' => $postTitle,
        'post_type' => 'post',
        'post_status' => 'publish',
        'post_content' => '<p>[simple_horoscope]</p><p>&nbsp;</p>',
        'post_excerpt' => "فال روزانه $date: امروز چه اتفاقی برای شما می‌افتد؟ امروز ستاره‌ها چه چیزی را برای شما پیش بینی می‌کنند؟ فال روزانه ماه تولد خود را بخوانید و ببنید چه چیزی در انتظار شماست.",
    ));
    if (!$postId) {
        $now = current_datetime()->format('H:i:s');
        error_log("[ $now (local_time)] Simple_Daily_Horoscope Insert Post Error");
        return;
    }
    // Set custom author
    $post = get_post($postId);
    if (!$post->post_author) {
        wp_update_post(array(
            'ID' => $postId,
            'post_author' => '79'
        ));
    }
    // Set categories
    // wp_set_post_categories($postId, [$postCategory], false);
    wp_set_post_terms($postId, [1605], 'category');

    // Generate daily featured image
    require_once(DAILYOMENS_PLUGINS . 'feature_image_generator.php');
    $featureImage = new Feature_Image($postId, $postTitle);
    $attachmentId = $featureImage->run();
    if ($attachmentId) set_post_thumbnail($postId, intval($attachmentId));

    // Insert Unique Omens
    $remaindIdsQuery = $wpdb->get_results($wpdb->prepare(
        "SELECT id FROM $table WHERE selected=%s",
        array('0')
    ));
    $remaindIds = [];
    foreach ($remaindIdsQuery as $key => $value) {
        array_push($remaindIds, $value->id);
    }
    $randomMonthesKeys = generateRandomIds($remaindIds);
    $placeholder = bindINOperatorPlaceholder($randomMonthesKeys);
    $randomOmens = $wpdb->get_results($wpdb->prepare(
        "SELECT omen FROM $table WHERE id IN ($placeholder)",
        $randomMonthesKeys
    ));
    $update = $wpdb->query($wpdb->prepare(
        "UPDATE $table SET selected='1' WHERE id IN ($placeholder)",
        $randomMonthesKeys
    ));
    $uniqueOmensTbl = $wpdb->prefix . DOPL_UNIQUE_SIMPLE_OMENS_TABLE;
    $insert = $wpdb->insert($uniqueOmensTbl, array(
        'post_id' => $postId,
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


    wp_send_json_success([
        'msg' => $result,
        'post_published' => isset($postId) ? 'true' : 'false',
        'post_id' => $postId
    ], 200);
}

function fixed_daily_post_callback()
{
    //
}
