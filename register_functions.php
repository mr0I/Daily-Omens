<?php defined('ABSPATH') or die('No script kiddies please!');


function dailyomens_activate_function()
{
    //require(DAILYOMENS_ROOTDIR . 'helpers/db.php');

    //register_uninstall_hook(__FILE__, 'canldeHoroscopeUninstall');
    flush_rewrite_rules();
}

function dailyomens_deactivate_function()
{
    flush_rewrite_rules();
}