<?php defined('ABSPATH') or die('No script kiddies please!');
error_reporting(E_ALL & ~E_NOTICE); // Report all errors except E_NOTICE

global $post, $wpdb;
$table = $wpdb->prefix . DOPL_UNIQUE_HAFEZ_OMENS_TABLE;
$selectedOmens = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM $table WHERE post_id=%s",
    array($post->ID)
));
?>


<section class="hafez-horoscope">
    <div class="hafez-horoscope-content">
        <div class="hafez-horoscope-content__header">
            <div class="monthTable">
                <table class="monthTable-desktop">
                    <caption>مشاهده فال حافظ روزانه بر اساس ماه تولد 👇</caption>
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

                <table class="monthTable-mobile">
                    <caption>مشاهده فال شمع روزانه بر اساس ماه تولد 👇</caption>
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
            <figure>
                <img src="<?= DAILYOMENS_SITE_STATIC . 'images/daily_hafez_omen/img1.jpg' ?>" width="100" alt="فال حافظ روزانه">
            </figure>
        </div>

        <div class="hafez-horoscope-content__omen">
            <?php
            $id = 'm1';
            $monthName = 'فروردین';
            $omenObj = json_decode($selectedOmens[0]->farvardin, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm2';
            $monthName = 'اردیبهشت';
            $omenObj = json_decode($selectedOmens[0]->ordibehesht, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm3';
            $monthName = 'خرداد';
            $omenObj = json_decode($selectedOmens[0]->khordad, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm4';
            $monthName = 'تیر';
            $omenObj = json_decode($selectedOmens[0]->tir, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm5';
            $monthName = 'مرداد';
            $omenObj = json_decode($selectedOmens[0]->mordad, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm6';
            $monthName = 'شهریور';
            $omenObj = json_decode($selectedOmens[0]->shahrivar, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm7';
            $monthName = 'مهر';
            $omenObj = json_decode($selectedOmens[0]->mehr, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm8';
            $monthName = 'آبان';
            $omenObj = json_decode($selectedOmens[0]->aban, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm9';
            $monthName = 'آذر';
            $omenObj = json_decode($selectedOmens[0]->azar, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm10';
            $monthName = 'دی';
            $omenObj = json_decode($selectedOmens[0]->dey, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm11';
            $monthName = 'بهمن';
            $omenObj = json_decode($selectedOmens[0]->bahman, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');

            $id = 'm12';
            $monthName = 'اسفند';
            $omenObj = json_decode($selectedOmens[0]->esfand, true);
            include(DAILYOMENS_TEMPLATES . 'components/hafez_horoscope_content_item.php');
            ?>
        </div>
    </div>
</section>