<?php defined('ABSPATH') or die('No script kiddies please!');
require_once(DAILYOMENS_ROOTDIR . 'env.php');

/**
 * Publishes daily simple omen
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
    $result = shoudDailyOmenPublish('simple_daily');
    if (!$result) {
        wp_send_json_error([
            'msg' => 'Can not publish again!'
        ], 400);
        exit;
    }

    // Publish the post
    global $wpdb;
    $table = $wpdb->prefix . DOPL_SIMPLE_OMENS_TABLE;
    $uniqueOmensTable = $wpdb->prefix . DOPL_UNIQUE_SIMPLE_OMENS_TABLE;

    // Reset omens publish loop
    resetDailyOmens($wpdb, $table);

    $date = getCurrentShamsiDate('l j F Y', null);
    $postTitle = 'فال روزانه' . ' ' . $date;
    $postId = insertDailyOmenPost($postTitle, '<p>[simple_horoscope]</p><p>&nbsp;</p>', "فال روزانه $date: امروز چه اتفاقی برای شما می‌افتد؟ امروز ستاره‌ها چه چیزی را برای شما پیش بینی می‌کنند؟ فال روزانه ماه تولد خود را بخوانید و ببنید چه چیزی در انتظار شماست.");
    if (!$postId) {
        $now = current_datetime()->format('H:i:s');
        error_log("[$now - ERROR -  error on insert simple_daily_horoscope post");
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
    insertDailyUniqueOmens($wpdb, $table, $uniqueOmensTable, $postId, 'simple_omen');

    wp_send_json_success([
        'msg' => $result,
        'post_published' => isset($postId) ? 'true' : 'false',
        'post_id' => $postId
    ], 200);
}

/**
 * Publishes daily hafez omen
 */
function daily_hafez_omen_callback(WP_REST_Request $req)
{
    // $req->get_json_params(), --> get request body
    $headers = ($req->get_headers());
    $token = (explode('Bearer ', $headers['authorization'][0]))[1];
    if (empty($token)) {
        exit;
    }

    if (!boolval(validateRestApiToken($token, getenv('AES_PASSWORD'), 'HafezOmen'))) {
        wp_send_json_error([
            'msg' => 'Token is invalid :('
        ], 401);
        exit;
    }

    // Check if post should published yet or not
    $result = shoudDailyOmenPublish('hafez_daily');
    if (!$result) {
        wp_send_json_error([
            'msg' => 'Can not publish again!'
        ], 400);
        exit;
    }

    // Publish the post
    global $wpdb;
    $table = $wpdb->prefix . DOPL_HAFEZ_OMENS_TABLE;
    $uniqueOmensTable = $wpdb->prefix . DOPL_UNIQUE_HAFEZ_OMENS_TABLE;

    // Reset omens publish loop
    resetDailyOmens($wpdb, $table);

    $date = getCurrentShamsiDate('l j F Y', null);
    $postTitle = 'فال حافظ روزانه' . ' ' . $date . ' با معنی و تفسیر دقیق';
    $postSlug = 'فال حافظ روزانه' . ' ' . $date;
    $postId = insertDailyOmenPost($postTitle, '<p>[hafez_horoscope]</p><p>&nbsp;</p>', "فال حافظ روشی سرگرم کننده و جالب برای پیش بینی آینده است. نتایج این فال بر اساس طالع بینی و تفسیر اشعار حافظ، شاعر بزرگ ایرانی، انجام می‌شود. در این پست با فال حافظ روزانه $date بر اساس ماه تولد همراه ما باشید.", $postSlug);
    if (!$postId) {
        $now = current_datetime()->format('H:i:s');
        error_log("[$now - ERROR -  error on insert hafez_daily_horoscope post");
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
    wp_set_post_terms($postId, [5229], 'category');

    // Insert Unique Omens
    insertDailyUniqueOmens($wpdb, $table, $uniqueOmensTable, $postId, 'hafez_omen');

    wp_send_json_success([
        'msg' => $result,
        'post_published' => isset($postId) ? 'true' : 'false',
        'post_id' => $postId
    ], 200);
}
