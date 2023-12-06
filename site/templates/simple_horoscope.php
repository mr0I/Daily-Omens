<?php defined('ABSPATH') or die('No script kiddies please!');
error_reporting(E_ALL & ~E_NOTICE);

require_once(DAILYOMENS_UTILS . 'helpers.php');


global $wpdb;
$table = $wpdb->prefix . DOPL_UNIQUE_SIMPLE_OMENS_TABLE;
$storageFile = file_get_contents(DAILYOMENS_ROOTDIR . 'storage/simpleOmenMetadata.json', 'a');
$data = json_decode($storageFile, true);


$contentType = 'daily';
if (!empty($atts[0])) {
    $params = shortcode_atts(
        array(
            'post_id',
            'content_type'
        ),
        $atts,
        $tag
    );
    $paramsStr = str_replace('params=', '', $params[0]);
    $attribs = json_decode(stripslashes($paramsStr), true);
    if ($attribs['content_type'] == 'fix_post') {
        $contentType = 'fix_post';
        $postId = $attribs['post_id'];
        $post = get_post($postId);
    }
} else {
    global $post;
}

$postPusblishDate =  (new DateTime($post->post_date))->format('Y-m-d');
$postDate =  date('Y-m-d', strtotime($postPusblishDate . ' + 1 days'));
$shamsiPostDate = convertToShamsiDate('Y-m-d', null, $postDate);
$postDateTimestamp = strtotime($postDate);
$shmasiPostDateTimestamp = strtotime($shamsiPostDate);
$postDateMonth = convertToShamsiDate(null, 'month', $postDateTimestamp);
$moonPositionKey = '';
if (isset($data['moon_position'][$postDateMonth])) {
    foreach ($data['moon_position'][$postDateMonth] as $key => $value) {
        if (strtotime($key) == $shmasiPostDateTimestamp) $moonPositionKey = $key;
    }
}

$emojiesObj = $data['emojies'];
$emojies = [];
foreach ($emojiesObj as $key => $value) {
    array_push($emojies, $value);
}
shuffle($emojies);
$emojies = array_slice($emojies, 0, 12);
$numbersArray = range(0, 11);
shuffle($numbersArray);

$selectedOmens = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM $table WHERE post_id=%s",
    array($post->ID)
));

?>

<link rel="stylesheet" href="<?= plugin_dir_url(__FILE__) . '../static/css/styles.css' ?>">

<section class="daily-horoscope">
    <div class="daily-horoscope-content">
        <div class="daily-horoscope-content__sun">
            <figure>
                <img src="<?= DAILYOMENS_SITE_STATIC . 'images/daily_simple_omen/sun.png' ?>" alt="<?= __('Daily Horsocope Sun Position', 'daily_horoscope') ?>">
            </figure>
            <p>
                <strong><?= $data['sun_position'][$postDateMonth ?? '---'] ?></strong>
            </p>
        </div>

        <?php if (isset($data['moon_position'][$postDateMonth][$moonPositionKey])) : ?>
            <div class="daily-horoscope-content__moon">
                <figure>
                    <img src="<?= DAILYOMENS_SITE_STATIC . 'images/daily_simple_omen/moon.png' ?>" alt="<?= __('Daily Horsocope Moon Position', 'daily_horoscope') ?>">
                </figure>
                <p>
                    <strong>
                        <?= $data['moon_position'][$postDateMonth][$moonPositionKey] ?? '---' ?>
                    </strong>
                </p>
            </div>
        <?php endif; ?>

        <div class="daily-horoscope-content__monthTable">
            <table class="desktop_tbl">
                <caption>مشاهده فال روزانه بر اساس ماه تولد 👇</caption>
                <tbody>
                    <tr>
                        <td style="border-top-right-radius: 8px;"><a href="#m1">فروردین</a></td>
                        <td><a href="#m2">اردیبهشت</a></td>
                        <td><a href="#m3">خرداد</a></td>
                        <td style="border-top-left-radius: 8px;"><a href="#m4">تیر</a></td>
                    </tr>
                    <tr>
                        <td><a href="#m5">مرداد</a></td>
                        <td><a href="#m6">شهریور</a></td>
                        <td><a href="#m7">مهر</a></td>
                        <td><a href="#m8">آبان</a></td>
                    </tr>
                    <tr>
                        <td style="border-bottom-right-radius: 8px;"><a href="#m9">آذر</a></td>
                        <td><a href="#m10">دی</a></td>
                        <td><a href="#m11">بهمن</a></td>
                        <td style="border-bottom-left-radius: 8px;"><a href="#m12">اسفند</a></td>
                    </tr>
                </tbody>
            </table>

            <table class="mobile_tbl">
                <caption>مشاهده فال روزانه بر اساس ماه تولد 👇</caption>
                <tbody>
                    <tr>
                        <td style="border-top-right-radius: 8px;"><a href="#m1">فروردین</a></td>
                        <td><a href="#m2">اردیبهشت</a></td>
                        <td style="border-top-left-radius: 8px;"><a href="#m3">خرداد</a></td>
                    </tr>
                    <tr>
                        <td><a href="#m4">تیر</a></td>
                        <td><a href="#m5">مرداد</a></td>
                        <td><a href="#m6">شهریور</a></td>
                    </tr>
                    <tr>
                        <td><a href="#m7">مهر</a></td>
                        <td><a href="#m8">آبان</a></td>
                        <td><a href="#m9">آذر</a></td>
                    </tr>
                    <tr>
                        <td style="border-bottom-right-radius: 8px;"><a href="#m10">دی</a></td>
                        <td><a href="#m11">بهمن</a></td>
                        <td style="border-bottom-left-radius: 8px;"><a href="#m12">اسفند</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php if ($contentType == 'daily') : ?>
            <div>
                <div class="daily-horoscope-content__description">
                    <p>
                        به عنوان یکی از روش‌های پیش‌بینی در فرهنگ‌های مختلف رواج دارد. این پیش‌بینی‌ها می‌توانند درباره مسائلی مانند موفقیت در کار، عشق و روابط، سلامتی و... باشند. برخی از افراد به فال روزانه به عنوان یک سنت یا روشی برای به دست آوردن راهنمایی در زندگی اعتقاد دارند و برخی دیگر نیز به آن صرفا به عنوان یک سرگرمی و تفریح نگاه می‌کنند. در هر صورت اگر شما نیز به فال و طالع بیینی علاقه‌مند هستید، با ما همراه باشید تا ببینید امروز چه چیزی پیش روی شما است.
                    </p>
                </div>
                <div class="daily-horoscope-content__rowButtons">
                    <a href="https://setare.com/fa/news/29249/%D9%81%D8%A7%D9%84-%D8%AD%D8%A7%D9%81%D8%B8-%D8%A8%D8%A7-%D9%85%D8%B9%D9%86%DB%8C-%D9%88-%D8%AA%D9%81%D8%B3%DB%8C%D8%B1-%DA%A9%D8%A7%D9%85%D9%84/" class="bg-orange">فال حافظ</a>
                    <a href="https://setare.com/fa/news/19806/%D8%A7%D8%B3%D8%AA%D8%AE%D8%A7%D8%B1%D9%87-%D8%A8%D8%A7-%D9%82%D8%B1%D8%A2%D9%86-%D8%A8%D8%A7-%D8%AC%D9%88%D8%A7%D8%A8-%D8%A7%D8%B3%D8%AA%D8%AE%D8%A7%D8%B1%D9%87-%D8%A2%D9%86%D9%84%D8%A7%DB%8C%D9%86-%D8%AE%D9%88%D8%A8-%D9%88-%D8%A8%D8%AF-%D9%81%D9%88%D8%B1%DB%8C/" class="bg-cyan">استخاره</a>
                    <a href="https://setare.com/fa/news/28246/%D8%B7%D8%A7%D9%84%D8%B9-%D8%A8%DB%8C%D9%86%DB%8C-%D8%AD%D8%B1%D9%88%D9%81-%D8%A7%D8%A8%D8%AC%D8%AF-%D8%A8%D8%B5%D9%88%D8%B1%D8%AA-%D8%A2%D9%86%D9%84%D8%A7%DB%8C%D9%86/" class="bg-purple">طالع بینی حروف ابجد</a>
                    <a href="https://setare.com/fa/news/430718/%D9%81%D8%A7%D9%84-%D8%A7%D9%86%D8%A8%DB%8C%D8%A7/" class="bg-green">فال انبیا</a>
                    <a href="https://setare.com/fa/news/25644/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D9%81%D8%A7%D9%84-%D9%88%D8%B1%D9%82-%D9%88-%D9%86%DA%A9%D8%A7%D8%AA-%D9%85%D8%B1%D8%A8%D9%88%D8%B7-%D8%A8%D9%87-%D8%A2%D9%86/" class="bg-pink">فال پاسور</a>
                    <a href="https://setare.com/fa/news/34643/%D9%81%D8%A7%D9%84-%D9%82%D9%87%D9%88%D9%87-%D8%A2%D9%86%D9%84%D8%A7%DB%8C%D9%86-%D9%88-%DA%86%DA%AF%D9%88%D9%86%DA%AF%DB%8C-%D8%AE%D9%88%D8%A7%D9%86%D8%AF%D9%86-%D8%A7%D8%B4%DA%A9%D8%A7%D9%84-%D8%A7%D8%B9%D8%AF%D8%A7%D8%AF-%D9%88-%D8%AD%DB%8C%D9%88%D8%A7%D9%86%D8%A7%D8%AA/" class="bg-blue">فال قهوه</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="daily-horoscope-content__omen">
            <?php
            $index = 0;
            $id = 'm1';
            $gifName = 'aries';
            $monthName = 'فروردین';
            $mainFeature = 'مدیر بودن';
            $planet = 'مریخ';
            $stone = 'الماس';
            $color = 'قرمز مایل به زرد';
            $strengths = 'باهوش، قاطع، ماجراجو';
            $label = 'فروردین ماهی عزیز...';
            $omen = $selectedOmens[0]->farvardin;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 1;
            $id = 'm2';
            $gifName = 'taurus';
            $monthName = 'اردیبهشت';
            $mainFeature = 'با اراده و مصمم';
            $planet = 'زمین';
            $stone = 'زمرد';
            $color = 'صورتی جیغ';
            $strengths = 'سخت کوش، شجاع، صادق';
            $label = 'اردیبهشت ماهی عزیز...';
            $omen = $selectedOmens[0]->ordibehesht;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 2;
            $id = 'm3';
            $gifName = 'gemini';
            $monthName = 'خرداد';
            $mainFeature = 'ارتباطات بالا';
            $planet = 'عطارد';
            $stone = 'عقیق';
            $color = 'زرد';
            $strengths = 'شوخ طبع، خوش صحبت، همه کاره';
            $label = 'خردادماهی عزیز...';
            $omen = $selectedOmens[0]->khordad;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 3;
            $id = 'm4';
            $gifName = 'cancer';
            $monthName = 'تیر';
            $mainFeature = 'احساسی';
            $planet = 'ماه';
            $stone = 'مروارید';
            $color = 'نقره ای';
            $strengths = 'خود آموز بودن، پرورش دادن';
            $label = 'تیرماهی عزیز...';
            $omen = $selectedOmens[0]->tir;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 4;
            $id = 'm5';
            $gifName = 'leo';
            $monthName = 'مرداد';
            $mainFeature = 'خلاق بودن';
            $planet = 'خورشید';
            $stone = 'یاقوت';
            $color = 'طلایی';
            $strengths = 'شجاعت، راستی، اعتماد به نفس بالا';
            $label = 'مردادماهی عزیز...';
            $omen = $selectedOmens[0]->mordad;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 5;
            $id = 'm6';
            $gifName = 'virgo';
            $monthName = 'شهریور';
            $mainFeature = 'ریزبین';
            $planet = 'عطارد';
            $stone = 'زبرجد';
            $color = 'آبی دریایی';
            $strengths = 'دقیق، منظم، کارآمد';
            $label = 'شهریورماهی عزیز...';
            $omen = $selectedOmens[0]->shahrivar;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 6;
            $id = 'm7';
            $gifName = 'libra';
            $monthName = 'مهر';
            $mainFeature = 'هماهنگ بودن';
            $planet = 'زهره';
            $stone = 'عقیق';
            $color = 'صورتی کم رنگ';
            $strengths = 'سیاست مدار، جذاب';
            $label = 'مهرماهی عزیز...';
            $omen = $selectedOmens[0]->mehr;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 7;
            $id = 'm8';
            $gifName = 'scorpio';
            $monthName = 'آبان';
            $mainFeature = 'با استقامت';
            $planet = 'پلوتون';
            $stone = 'یاقوت زرد';
            $color = 'سفید';
            $strengths = 'قدرتمند بودن، سازگاری';
            $label = 'آبان ماهی عزیز...';
            $omen = $selectedOmens[0]->aban;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 8;
            $id = 'm9';
            $gifName = 'sagittarius';
            $monthName = 'آذر';
            $mainFeature = 'مدبر';
            $planet = 'مریخ';
            $stone = 'الماس';
            $color = 'قرمز مایل به زرد';
            $strengths = 'باهوش، قاطع، ماجراجو';
            $label = 'آذرماهی عزیز...';
            $omen = $selectedOmens[0]->azar;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 9;
            $id = 'm10';
            $gifName = 'capricorn';
            $monthName = 'دی';
            $mainFeature = 'جاه طلبی';
            $planet = 'زحل';
            $stone = 'لعل';
            $color = 'خاکستری';
            $strengths = 'منظم بودن، شکیبایی';
            $label = 'دی ماهی عزیز...';
            $omen = $selectedOmens[0]->dey;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 10;
            $id = 'm11';
            $gifName = 'aquarius';
            $monthName = 'بهمن';
            $mainFeature = 'هنجارشکن';
            $planet = 'اورانوس';
            $stone = 'یاقوت ارغوانی';
            $color = 'آبی';
            $strengths = 'انسان دوست، مدرن، تحلیل کننده';
            $label = 'بهمن ماهی عزیز...';
            $omen = $selectedOmens[0]->bahman;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');

            $index = 11;
            $id = 'm12';
            $gifName = 'pisces';
            $monthName = 'اسفند';
            $mainFeature = 'دلسوز';
            $planet = 'نپتون';
            $stone = 'زمرد کبود';
            $color = 'بنفش';
            $strengths = 'آرمان گرایی، معنوی بودن';
            $label = 'اسفند ماهی عزیز...';
            $omen = $selectedOmens[0]->esfand;
            include(DAILYOMENS_TEMPLATES . 'components/simple_horoscope_content_item.php');
            ?>
        </div>
    </div>
</section>


<script src="<?= plugin_dir_url(__FILE__) . '../static/js_obfuscated/responsive_script.js' . '?ver=' . DAILYOMENS_PLUGIN_VERSION ?>" defer></script>