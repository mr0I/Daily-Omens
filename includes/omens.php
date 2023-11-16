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
        return 'SimpleOmen';
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
$prophetsOmen = new ProphetsOmen();
$prophetsOmen->addShortCode();
// wp_die(json_encode([
//     '1' => $simpleOmen->registerRestApi(),
//     '2' => $prophetsOmen->print()
// ], JSON_PRETTY_PRINT));
