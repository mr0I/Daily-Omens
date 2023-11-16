<?php defined('ABSPATH') or die('No script kiddies please!');


class Setup
{
    public function __construct()
    {
        // $this->var = $var;
    }

    // public static function init(Setup $self)
    // {
    //     error_log();
    //     error_log('init</br>');
    //     $self->defineGlobals();
    // }
    public function init()
    {
        error_log('init</br>');
        $this->defineGlobals();
        $this->setupPlugin();
    }

    private function defineGlobals()
    {
        define('DAILYOMENS_ROOTDIR', plugin_dir_path(__FILE__));
        define('DAILYOMENS_INC', DAILYOMENS_ROOTDIR . 'includes/');
        define('DAILYOMENS_TEMPLATES', DAILYOMENS_ROOTDIR . 'site/templates/');
    }

    private function enqueueScripts()
    {
        //
    }

    private function setupPlugin()
    {
        add_action('plugins_loaded', function () {
            load_plugin_textdomain('daily_omens', false, basename(DAILYOMENS_ROOTDIR) . '/l10n/');
        });

        /** Inits */
        include(DAILYOMENS_ROOTDIR . 'register_functions.php');
        register_activation_hook(__FILE__, 'dailyomens_activate_function');
        register_deactivation_hook(__FILE__, 'dailyomens_deactivate_function');

        require_once(DAILYOMENS_INC . 'shortcodes.php');
        require_once(DAILYOMENS_INC . 'omens.php');

        if (is_admin()) {
            error_log('is_admin</br>');

            // require_once(COFFEEOMEN_ADMIN . "admin_process.php");
            // require_once(COFFEEOMEN_ADMIN . 'admin_ajax.php');
        }
        error_log('setup</br>');
    }
}

$dailyOmen = new Setup();
$dailyOmen->init();
// Setup::init();
