
<?php
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