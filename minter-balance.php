<?php
/**
 * Minter Balance
 *
 * @package     PluginPackage
 * @author      Constantine Avdeev
 * @copyright   2019 Constantine Avdeev
 * @license     MIT
 *
 * Plugin Name: Minter Balance
 * Description: Позволяет выводить баланс кошелька сети Minter в указанной монете
 * Plugin URI:  https://github.com/fussraider/minter-balance
 * Author URI:  https://fussraider.ru
 * Author:      Constantine Avdeev
 * Version:     0.2
 * License:     MIT
 * License URI: https://raw.githubusercontent.com/fussraider/minter-balance/master/LICENSE
 */

use Minter\MinterAPI;

require "vendor/autoload.php";

function minter_balance_value_shortcode($atts, $content, $tag){
    $result = 0;
    $atts = shortcode_atts([
        'address' => 'Mx8e6c210b6f310ce8e38024838becca67cb52a428',
        'coin' => 'BIP',
        'round' => -1
    ], $atts);

    $result =  minter_get_address_balance_single($atts['address'], $atts['coin']);

    if ($result > 0)
        $result = minter_round_result($result, $atts['round']);

    return $result;
}
add_shortcode('minter-balance-value', 'minter_balance_value_shortcode');


function minter_balance_shortcode($atts, $content, $tag){
    $val = minter_balance_value_shortcode($atts, $content, $tag);

    return $val . ' ' . $atts['coin'];
}
add_shortcode('minter-balance', 'minter_balance_shortcode');