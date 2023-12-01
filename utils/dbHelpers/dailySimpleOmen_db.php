<?php defined('ABSPATH') or die('No script kiddies please!');


require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

global $wpdb;
$dailyHoroscopeTbl = $wpdb->prefix . DOPL_SIMPLE_OMENS_TABLE;
$uniqueOmensTbl = $wpdb->prefix . DOPL_UNIQUE_SIMPLE_OMENS_TABLE;

$createDailyHoroscopeTable =
    "
        CREATE TABLE IF NOT EXISTS $dailyHoroscopeTbl (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `omen` text COLLATE utf8_unicode_ci NOT NULL,
        `selected` tinyint(4) DEFAULT 0,
        PRIMARY KEY (`id`),
        KEY `selected` (`selected`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
      ";

$createDailyUniqueOmensTable =
    "
    CREATE TABLE IF NOT EXISTS $uniqueOmensTbl (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `post_id` int(10) unsigned NOT NULL,
        `farvardin` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `ordibehesht` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `khordad` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `tir` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `mordad` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `shahrivar` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `mehr` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `aban` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `azar` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `dey` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `bahman` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `esfand` text COLLATE utf8_unicode_ci DEFAULT NULL,
        `createdAt` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `post_id` (`post_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
    ";


dbDelta($createDailyHoroscopeTable);
dbDelta($createDailyUniqueOmensTable);
