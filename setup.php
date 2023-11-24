<?php defined('ABSPATH') or die('No script kiddies please!');


class Setup
{
    private string $pluginVersion;

    public function __construct(string $plugin_version)
    {
        $this->pluginVersion = $plugin_version;
    }

    // public static function init(Setup $self)
    // {
    //     error_log();
    //     error_log('init</br>');
    //     $self->defineGlobals();
    // }
    public function init(): void
    {
        error_log('init</br>');
        $this->defineGlobals();
        $this->enqueueScripts();
        $this->setupPlugin();
    }

    private function defineGlobals(): void
    {
        define('DAILYOMENS_ROOTDIR', plugin_dir_path(__FILE__));
        define('DAILYOMENS_ROOTURL', plugin_dir_url(__FILE__));
        define('DAILYOMENS_INC', DAILYOMENS_ROOTDIR . 'includes/');
        define('DAILYOMENS_UTILS', DAILYOMENS_ROOTDIR . 'utils/');
        define('DAILYOMENS_TEMPLATES', DAILYOMENS_ROOTDIR . 'site/templates/');
        define('DAILYOMENS_ADMIN', DAILYOMENS_ROOTDIR . 'admin/');
        define('DAILYOMENS_SITE', DAILYOMENS_ROOTDIR . 'site/');
        define('DAILYOMENS_SITE_CSS', DAILYOMENS_ROOTURL . 'site/static/css/');
        define('OMENS_LOGGER_TABLE', 'omens_logger');
        define('PROPHETS_OMEN_TABLE', 'prophets_omen');
    }

    private function enqueueScripts(): void
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('daily_omens_main_styles', DAILYOMENS_SITE_CSS . 'styles.css', array(), $this->pluginVersion);

            // if (is_single() || is_page()) {
            //     global $post;
            //     if (has_shortcode($post->post_content, 'woopi_error_msg')) {
            //         wp_enqueue_style('woopi-shortcode-styles', WOOPI_SITE_CSS . 'shortcode_styles.css', array(), $pluginVersion);
            //     }
            // }
        });
    }

    private function setupPlugin(): void
    {
        add_action('plugins_loaded', function () {
            load_plugin_textdomain('daily_omens', false, basename(DAILYOMENS_ROOTDIR) . '/l10n/');
        });

        require_once(DAILYOMENS_INC . 'shortcodes.php');
        require_once(DAILYOMENS_UTILS . 'simple_daily_omens.php');
        require_once(DAILYOMENS_UTILS . 'helpers.php');
        require_once(DAILYOMENS_INC . 'omens.php');
        require_once(DAILYOMENS_SITE . 'wp_ajax.php');

        if (is_admin()) {
            error_log('is_admin</br>');
            // require_once(COFFEEOMEN_ADMIN . "admin_process.php");
            // require_once(COFFEEOMEN_ADMIN . 'admin_ajax.php');
        }
        error_log('setup</br>');
    }
}

$pluginVersion = boolval(DOMENS_IS_DEV) ? time() : (get_plugin_data(__FILE__, false))['Version'];

$dailyOmen = new Setup($pluginVersion);
$dailyOmen->init();
// Setup::init();
