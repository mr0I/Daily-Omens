<?php defined('ABSPATH') or die('No script kiddies please!');


if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

$now = current_datetime()->format('H:i:s');
error_log("[$now - INFO -  daily_omens plugin uninstalled.");
