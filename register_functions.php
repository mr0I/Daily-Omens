<?php defined('ABSPATH') or die('No script kiddies please!');


function dailyomens_activate_function(): void
{
    require(DAILYOMENS_UTILS . 'dbHelpers/prophetsOmen_db.php');

    //register_uninstall_hook(__FILE__, 'canldeHoroscopeUninstall');
    flush_rewrite_rules();
}

function dailyomens_deactivate_function()
{
    flush_rewrite_rules();
}
