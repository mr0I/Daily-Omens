<?php defined('ABSPATH') or die('No script kiddies please!');

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

global $wpdb;
$coffeeOmenTbl = $wpdb->prefix . DOPL_COFFEE_OMENS_TABLE;
$createCoffeeOmenTbl =
    "
    CREATE TABLE IF NOT EXISTS `$coffeeOmenTbl` (
        `id` int unsigned NOT NULL AUTO_INCREMENT,
        `coffee_omen` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

dbDelta($createCoffeeOmenTbl);
