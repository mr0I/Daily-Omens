<?php defined('ABSPATH') or die('No script kiddies please!');

if (!function_exists('prophetsOmenRender')) {
    function prophetsOmenRender($atts = []): string
    {
        ob_start();
        include(DAILYOMENS_TEMPLATES . 'prophets_omen.php');
        $content = ob_get_clean();
        return $content;
    }
}

if (!function_exists('simpleHoroscopeRender')) {
    function simpleHoroscopeRender($atts = []): string
    {
        ob_start();
        include(DAILYOMENS_TEMPLATES . 'simple_horoscope.php');
        $content = ob_get_clean();
        return $content;
    }
}
