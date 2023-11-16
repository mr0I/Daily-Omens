<?php defined('ABSPATH') or die('No script kiddies please!');


interface DailyOmen
{
    public function registerRestApi();
}
interface ConstantOmen
{
    public function addShortCode();
}

class SimpleOmen implements DailyOmen
{
    public function registerRestApi()
    {
        add_action('rest_api_init', function () {
            register_rest_route(
                'plugins/v1',
                'daily_simple_omen',
                array(
                    'methods' =>   WP_REST_SERVER::READABLE,
                    'callback' => "daily_simple_omen_callback"
                ),
                false
            );
            register_rest_route(
                'plugins/v1',
                'daily_simple_omen/fixed_post',
                array(
                    'methods' =>   WP_REST_SERVER::READABLE,
                    'callback' => "fixed_daily_post_callback"
                ),
                false
            );
        });
    }
}

class ProphetsOmen implements ConstantOmen
{
    public function addShortCode()
    {
        add_action('init', function () {
            if (!shortcode_exists('prophets_omen')) {
                add_shortcode('prophets_omen', 'prophetsOmenRender');
            }
        });
    }
}

$simpleOmen = new SimpleOmen();
$simpleOmen->registerRestApi();

$prophetsOmen = new ProphetsOmen();
$prophetsOmen->addShortCode();

// wp_die(json_encode([
//     '1' => $simpleOmen->registerRestApi(),
//     '2' => $prophetsOmen->print()
// ], JSON_PRETTY_PRINT));
