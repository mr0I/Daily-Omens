<?php

/**
 * Plugin Name: Daily Omens
 * Plugin URI:  
 * Description: افزونه انتشار خودکار فال روزانه
 * Version: 1.0.0
 * Author: ZeroOne
 * Author URI: https://github.com/mr0I
 * Text Domain: daily_omens
 * Domain Path: /l10n
 */
defined('ABSPATH') or die('No script kiddies please!');
define('IS_DEV', true);
define('AES_METHOD', 'aes-256-cbc');

include(plugin_dir_path(__FILE__) . 'register_functions.php');
register_activation_hook(__FILE__, 'dailyomens_activate_function');
register_deactivation_hook(__FILE__, 'dailyomens_deactivate_function');

require(plugin_dir_path(__FILE__) . 'setup.php');
