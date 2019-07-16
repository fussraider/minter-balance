<?php

class MinterBalanceSingleWidget extends WP_Widget
{
    function __construct() {
        parent::__construct(
            'minter_balance_single_widget',
            'Minter Balance Single Widget',
            ['description' => 'Выводит баланс кошелька сети Minter (Одна монета)']
        );
    }

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $address = apply_filters('widget_address', $instance['address']);
        $coin = apply_filters('widget_address', $instance['coin']);
        $round = (int)apply_filters('widget_round_to', $instance['round_to']);

        echo $args['before_widget'];
        //if title is present
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];
        //output
        $balance = minter_balance_get_address_balance_single($address, $coin);
        ?>
            <ul class="minter-balance-single-block">
                <li class="minter-balance-single-row">
                    <span class="minter-balance-single-value"><?php echo minter_balance_round_result($balance, $round); ?></span>
                    <span class="minter-balance-single-ticker"><?php echo $coin; ?></span>
                </li>
            </ul>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : 'Minter BIP Balance';
        $address = isset($instance['address']) ? $instance['address'] : 'Mx8e6c210b6f310ce8e38024838becca67cb52a428';
        $coin = isset($instance['coin']) ? $instance['coin'] : 'BIP';
        $round = isset($instance['round_to']) ? (int)$instance['round_to'] : -1;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo esc_attr($address); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('coin'); ?>"><?php _e('Coin:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('coin'); ?>" name="<?php echo $this->get_field_name('coin'); ?>" type="text" value="<?php echo esc_attr($coin); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('round_to'); ?>"><?php _e('Round to:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('round_to'); ?>" name="<?php echo $this->get_field_name('round_to'); ?>" type="number" min="-1" max="18" value="<?php echo esc_attr($round); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = [];
        $instance['title'] = (!empty( $new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['address'] = (!empty( $new_instance['address'])) ? strip_tags($new_instance['address']) : 'Mx8e6c210b6f310ce8e38024838becca67cb52a428';
        $instance['coin'] = (!empty( $new_instance['coin'])) ? strip_tags($new_instance['coin']) : 'BIP';
        $instance['round_to'] = (!empty( $new_instance['round_to'])) ? (int)strip_tags($new_instance['round_to']) : -1;
        return $instance;
    }
}


function minter_balance_single_register_widget() {
    register_widget('MinterBalanceSingleWidget');
}
add_action('widgets_init', 'minter_balance_single_register_widget');