<?php defined('ABSPATH') or die('No script kiddies please!');


interface DailyOmen
{
    public function registerRestApi();
    public function addShortCode();
}
interface ConstantOmen
{
    public function addShortCode();
}

class SimpleOmen implements DailyOmen
{
    public function registerRestApi(): void
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

    public function addShortCode(): void
    {
        add_action('init', function () {
            if (!shortcode_exists('simple_horoscope')) {
                add_shortcode('simple_horoscope', 'simpleHoroscopeRender');
            }
        });
    }
}

class HafezOmen implements DailyOmen
{
    public function registerRestApi(): void
    {
        add_action('rest_api_init', function () {
            register_rest_route(
                'plugins/v1',
                'daily_hafez_omen',
                array(
                    'methods' =>   WP_REST_SERVER::EDITABLE,
                    'callback' => "daily_hafez_omen_callback"
                ),
                false
            );
        });
    }

    public function addShortCode(): void
    {
        add_action('init', function () {
            if (!shortcode_exists('hafez_horoscope')) {
                add_shortcode('hafez_horoscope', 'hafezHoroscopeRender');
            }
        });
    }
}

class ProphetsOmen implements ConstantOmen
{
    public function addShortCode(): void
    {
        add_action('init', function () {
            if (!shortcode_exists('prophets_omen')) {
                add_shortcode('prophets_omen', 'prophetsOmenRender');
            }
        });
    }
}

class coffeeOmen implements ConstantOmen
{
    public function addShortCode(): void
    {
        add_action('init', function () {
            if (!shortcode_exists('coffee_omen')) {
                add_shortcode('coffee_omen', 'coffeeOmenRender');
            }
        });
    }
}

$simpleOmen = new SimpleOmen();
$simpleOmen->registerRestApi();
$simpleOmen->addShortCode();

$prophetsOmen = new ProphetsOmen();
$prophetsOmen->addShortCode();

$coffeeOmen = new CoffeeOmen();
$coffeeOmen->addShortCode();

$hafezOmen = new HafezOmen();
$hafezOmen->registerRestApi();
$hafezOmen->addShortCode();



// wp_die(json_encode([
//     '1' => $simpleOmen->registerRestApi(),
//     '2' => $prophetsOmen->print()
// ], JSON_PRETTY_PRINT));
