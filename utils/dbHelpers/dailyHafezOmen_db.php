<?php defined('ABSPATH') or die('No script kiddies please!');


require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

global $wpdb;
$dailyHafezOmenTbl = $wpdb->prefix . DOPL_HAFEZ_OMENS_TABLE;
$uniqueHafezOmensTbl = $wpdb->prefix . DOPL_UNIQUE_HAFEZ_OMENS_TABLE;

$createDailyHafezOmenTable =
    "
    CREATE TABLE $dailyHafezOmenTbl (
        `id` int unsigned NOT NULL AUTO_INCREMENT,
        `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `omen` text COLLATE utf8mb4_unicode_ci,
        `audio_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `ghazal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `anchor_text` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `selected` tinyint DEFAULT '0',
        PRIMARY KEY (`id`),
        KEY `selected` (`selected`) USING BTREE
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
          ";

$createDailyHafezUniqueOmensTable =
    "
          CREATE TABLE IF NOT EXISTS $uniqueHafezOmensTbl (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `post_id` int(10) unsigned NOT NULL,
              `farvardin` json DEFAULT NULL,
              `ordibehesht` json DEFAULT NULL,
              `khordad` json DEFAULT NULL,
              `tir` json DEFAULT NULL,
              `mordad` json DEFAULT NULL,
              `shahrivar` json DEFAULT NULL,
              `mehr` json DEFAULT NULL,
              `aban` json DEFAULT NULL,
              `azar` json DEFAULT NULL,
              `dey` json DEFAULT NULL,
              `bahman` json DEFAULT NULL,
              `esfand` json DEFAULT NULL,
              `createdAt` datetime DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `post_id` (`post_id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
              ";


dbDelta($createDailyHafezOmenTable);
dbDelta($createDailyHafezUniqueOmensTable);
