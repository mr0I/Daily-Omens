<?php defined('ABSPATH') or die('No script kiddies please!');


global $wpdb;
$omensLoggerTable = $wpdb->prefix . OMENS_LOGGER_TABLE;

$createOmensLoggerTbl = "
CREATE TABLE $omensLoggerTable (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `date` date NOT NULL,
    `logs` json NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_date` (`date`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
";

dbDelta($createOmensLoggerTbl);
