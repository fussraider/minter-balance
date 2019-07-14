<?php
/**
 * Plugin Name: Minter Balance
 * Description: Позволяет выводить баланс кошелька сети Minter в указанной монете
 * Plugin URI:  https://github.com/fussraider/minter-balance
 * Author URI:  https://fussraider.ru
 * Author:      Constantine A
 * Version:     0.1
 *
 * License:     MIT
 * License URI: https://opensource.org/licenses/MIT
 *
 */

use Minter\MinterAPI;

require "vendor/autoload.php";

function minter_balance_value_shortcode($atts, $content, $tag){
    $minter_options = get_option('minter_balance'); // node_url AND cache_expire params
    $result = 0;

    if(!isset($minter_api) || !is_object($minter_api)){
        $minter_api = new Minter\MinterAPI($minter_options['node_url']);
    }

    $atts = shortcode_atts([
        'address' => 'Mx8e6c210b6f310ce8e38024838becca67cb52a428',
        'coin' => 'BIP',
        'round' => -1
    ], $atts);

    $atts['coin'] = mb_strtoupper($atts['coin']);

    $cache = get_option('minter_balance_' . $atts['address'] . '_' . $atts['coin']);

    if($cache && isset($cache['timestamp']) && isset($cache['value'])
        && $cache['timestamp'] > time() + (int) $minter_options['cache_expire']){
        $cache = maybe_unserialize($cache);
        return $cache['value'];
    }
    else {
        try {
            $balance = $minter_api->getBalance($atts['address']);
            $result = $balance->result->balance->{$atts['coin']};
            $result = \Minter\SDK\MinterConverter::convertValue($result, 'bip');
            if ($result > 0) {
                if ($atts['round'] >= 0) {
                    $result = round((float)$result, $atts['round']);
                }
            }

            update_option('minter_balance_' . $atts['address'] . '_' . $atts['coin'], maybe_serialize([
                'timestamp' => time(),
                'value' => $result
            ]));
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            //handle
        }
    }

    return $result;
}
add_shortcode('minter-balance-value', 'minter_balance_value_shortcode');


function minter_balance_shortcode($atts, $content, $tag){
    $val = minter_balance_value_shortcode($atts, $content, $tag);

    return $val . ' ' . $atts['coin'];
}
add_shortcode('minter-balance', 'minter_balance_shortcode');