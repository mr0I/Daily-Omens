<?php defined('ABSPATH') or die('No script kiddies please!');


class Setup
{
    private string $pluginVersion;
    // private static int $count = 0; 


    public function __construct(string $plugin_version)
    {
        $this->pluginVersion = $plugin_version;
        // self::$count = 10;
    }

    // public static function init(Setup $self)
    // {
    //     $self->defineGlobals();
    // }
    public function init(): void
    {
        error_log('init...</br>');
        $this->defineGlobals();
        $this->enqueueScripts();
        $this->setupPlugin();
    }

    private function defineGlobals(): void
    {
        define('DAILYOMENS_PLUGIN_VERSION', $this->pluginVersion);
        define('DAILYOMENS_ROOTDIR', plugin_dir_path(__FILE__));
        define('DAILYOMENS_ROOTURL', plugin_dir_url(__FILE__));
        define('DAILYOMENS_INC', DAILYOMENS_ROOTDIR . 'includes/');
        define('DAILYOMENS_UTILS', DAILYOMENS_ROOTDIR . 'utils/');
        define('DAILYOMENS_PLUGINS', DAILYOMENS_ROOTDIR . 'plugins/');
        define('DAILYOMENS_TEMPLATES', DAILYOMENS_ROOTDIR . 'site/templates/');
        define('DAILYOMENS_ADMIN', DAILYOMENS_ROOTDIR . 'admin/');
        define('DAILYOMENS_SITE', DAILYOMENS_ROOTDIR . 'site/');
        define('DAILYOMENS_SITE_STATIC', DAILYOMENS_ROOTURL . 'site/static/');
        define('DAILYOMENS_SITE_CSS', DAILYOMENS_ROOTURL . 'site/static/css/');
        define('DAILYOMENS_SITE_JS', DAILYOMENS_ROOTURL . 'site/static/js/');
        define('DAILYOMENS_LOGGER_TABLE', 'omens_logger');
        define('DOPL_PROPHETS_OMENS_TABLE', 'prophets_omen_tbl');
        define('DOPL_SIMPLE_OMENS_TABLE', 'simple_omen_tbl');
        define('DOPL_UNIQUE_SIMPLE_OMENS_TABLE', 'unique_simple_omen_tbl');
        define('DOPL_HAFEZ_OMENS_TABLE', 'hafez_omen_tbl');
        define('DOPL_UNIQUE_HAFEZ_OMENS_TABLE', 'unique_hafez_omen_tbl');
        define('DOPL_COFFEE_OMENS_TABLE', 'coffee_omen_tbl');
    }

    private function enqueueScripts(): void
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('daily_omens_main_styles', DAILYOMENS_SITE_CSS . 'styles.css', array(), $this->pluginVersion);

            if (is_single() || is_page()) {
                global $post;
                if (has_shortcode($post->post_content, 'coffee_omen')) {
                    wp_enqueue_script('daily_omens_coffee_script', DAILYOMENS_SITE_JS . 'coffeeOmen_script.js', array(), $this->pluginVersion, true);
                    wp_localize_script('daily_omens_coffee_script', 'daily_omens_wp_ajax', array(
                        'AJAXURL' => admin_url('admin-ajax.php'),
                        'SECURITY' => wp_create_nonce('YDPagk5TQhIFlePOuPQY'),
                        'REQUEST_TIMEOUT' => 30000
                    ));
                }
            }
        });
    }

    private function setupPlugin(): void
    {
        add_action('plugins_loaded', function () {
            load_plugin_textdomain('daily_omens', false, basename(DAILYOMENS_ROOTDIR) . '/l10n/');
        });

        require_once(DAILYOMENS_INC . 'shortcodes.php');
        require_once(DAILYOMENS_UTILS . 'daily_omens.php');
        require_once(DAILYOMENS_UTILS . 'helpers.php');
        require_once(DAILYOMENS_INC . 'omens.php');
        require_once(DAILYOMENS_SITE . 'wp_ajax.php');

        if (is_admin()) {
            // require_once(COFFEEOMEN_ADMIN . "admin_process.php");
            // require_once(COFFEEOMEN_ADMIN . 'admin_ajax.php');
        }
    }
}

$pluginVersion = boolval(DOMENS_IS_DEV) ? time() : (get_plugin_data(__FILE__, false))['Version'];
$dailyOmen = new Setup($pluginVersion);
$dailyOmen->init();
// Setup::init();
