<?php defined('ABSPATH') or die('No script kiddies please!');


global $wpdb;
$omensLoggerTable = $wpdb->prefix . DAILYOMENS_LOGGER_TABLE;

$createOmensLoggerTbl = "
CREATE TABLE $omensLoggerTable (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `date` date NOT NULL,
    `logs` json NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `date` (`date`),
    KEY `idx_date` (`date`)
  ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
";

dbDelta($createOmensLoggerTbl);
