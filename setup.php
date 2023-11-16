<?php defined('ABSPATH') or die('No script kiddies please!');


class Setup
{
    private $pluginVersion;

    public function __construct($plugin_version)
    {
        $this->pluginVersion = $plugin_version;
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
        $this->enqueueScripts();
    }

    private function defineGlobals()
    {
        define('DAILYOMENS_ROOTDIR', plugin_dir_path(__FILE__));
        define('DAILYOMENS_ROOTURL', plugin_dir_url(__FILE__));
        define('DAILYOMENS_INC', DAILYOMENS_ROOTDIR . 'includes/');
        define('DAILYOMENS_UTILS', DAILYOMENS_ROOTDIR . 'utils/');
        define('DAILYOMENS_TEMPLATES', DAILYOMENS_ROOTDIR . 'site/templates/');
        define('DAILYOMENS_SITE_CSS', DAILYOMENS_ROOTURL . 'site/static/css/');
    }

    private function enqueueScripts()
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('daily_omens_main_styles', DAILYOMENS_SITE_CSS . 'styles.css', array(), $this->pluginVersion);
        });
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
        require_once(DAILYOMENS_UTILS . 'simple_daily_omens.php');
        require_once(DAILYOMENS_UTILS . 'helpers.php');
        require_once(DAILYOMENS_INC . 'omens.php');

        if (is_admin()) {
            error_log('is_admin</br>');

            // require_once(COFFEEOMEN_ADMIN . "admin_process.php");
            // require_once(COFFEEOMEN_ADMIN . 'admin_ajax.php');
        }
        error_log('setup</br>');
    }
}

$pluginVersion = boolval(IS_DEV) ? time() : (get_plugin_data(__FILE__, false))['Version'];

$dailyOmen = new Setup($pluginVersion);
$dailyOmen->init();
// Setup::init();
