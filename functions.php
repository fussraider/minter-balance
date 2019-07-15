
<?php

global $minter_api;

/**
 * Создаем страницу настроек плагина
 */
add_action('admin_menu', 'add_plugin_page');
function add_plugin_page(){
    add_options_page( 'Настройки Minter Balance', 'Minter Balance', 'manage_options', 'minter_balance_options', 'minter_balance_options_page_output' );
}


function minter_balance_options_page_output(){
    ?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

        <form action="options.php" method="POST">
            <?php
            settings_fields( 'option_group' );     // скрытые защитные поля
            do_settings_sections( 'minter_balance_page' ); // секции с настройками (опциями)
            ?>
            <div><?php submit_button(); ?></div>
        </form>
    </div>
    <?php
}

/**
 * Регистрируем настройки.
 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
 */
add_action('admin_init', 'plugin_settings');
function plugin_settings(){
    // параметры: $option_group, $option_name, $sanitize_callback
    register_setting( 'option_group', 'minter_balance', 'sanitize_callback' );

    // параметры: $id, $title, $callback, $page
    add_settings_section( 'section_id', 'Основные настройки', '', 'minter_balance_page' );

    // параметры: $id, $title, $callback, $page, $section, $args
    add_settings_field('node_url', 'Node URL', 'fill_node_url_field', 'minter_balance_page', 'section_id' );
    add_settings_field('cache_expire', 'Кеширование (сек)', 'fill_cache_expire', 'minter_balance_page', 'section_id' );
}

function fill_node_url_field(){
    $val = get_option('minter_balance');
    $val = $val ? $val['node_url'] : null;
    ?>
    <input type="text" name="minter_balance[node_url]" value="<?php echo esc_attr($val) ?>" />
    <?php
}

function fill_cache_expire(){
    $val = get_option('minter_balance');
    $val = $val ? $val['cache_expire'] : 0;
    ?>
    <input type="number" name="minter_balance[cache_expire]" value="<?php echo esc_attr($val) ?>" />
    <?php
}

// Очистка данных
function sanitize_callback( $options ){
    foreach( $options as $name => & $val ){
        if( $name == 'node_url' )
            $val = strip_tags( $val );

        if( $name == 'cache_expire' )
            $val = intval( $val );
    }

    return $options;
}

function minter_round_result($val, $round_to){
    if ($round_to >= 0)
        return  round((float)$val, $round_to);
    else
        return $val;
}

function minter_get_plugin_options(){
    return get_option('minter_balance'); // node_url AND cache_expire params
}

function minter_node_url(){
    $options = minter_get_plugin_options();
    return $options['node_url'];
}

function minter_cache_expire(){
    $options = minter_get_plugin_options();
    return (int)$options['cache_expire'];
}

function minter_api(){
    global $minter_api;
    if(!isset($minter_api) || !is_object($minter_api)){
        $minter_api = new Minter\MinterAPI(minter_node_url());
    }

    return $minter_api;
}

function minter_get_address_balance($address){
    $cache_key = 'minter_balance_' . $address;
    $cache = get_option($cache_key);
    $result = [];

    if($cache && isset($cache['timestamp']) && isset($cache['value']) && $cache['timestamp'] > time() + minter_cache_expire()){
        $cache = maybe_unserialize($cache);
        $result = $cache['value'];
    } else {
        try {
            $balance = minter_api()->getBalance($address);
            $balance = $balance->result->balance;

            foreach($balance as $ticker => $value){
                $result[$ticker] = \Minter\SDK\MinterConverter::convertValue($value, 'bip');
            }

            update_option($cache_key, maybe_serialize([
                'timestamp' => time(),
                'value' => $result
            ]));
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($cache && isset($cache['value'])) {
                $result = $cache['value'];
            }
        }
    }

    return $result;
}

function minter_get_address_balance_single($address, $ticker){
    $ticker = mb_strtoupper($ticker);
    $cache_key = 'minter_balance_' . $address . '_' . $ticker;
    $cache = get_option($cache_key);
    $result = 0;

    if($cache && isset($cache['timestamp']) && isset($cache['value']) && $cache['timestamp'] > time() + minter_cache_expire()){
        $cache = maybe_unserialize($cache);
        $result = $cache['value'];
    } else {
        try {
            $balance = minter_api()->getBalance($address);
            $balance = $balance->result->balance->{$ticker};
            $balance = $balance ? $balance : 0;
            $result = \Minter\SDK\MinterConverter::convertValue($balance, 'bip');

            update_option($cache_key, maybe_serialize([
                'timestamp' => time(),
                'value' => $result
            ]));
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($cache && isset($cache['value'])) {
                $result = $cache['value'];
            }
        }
    }

    return $result;
}

function dump($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}