<?php

class BalanceWidget extends WP_Widget
{
    function __construct() {
        parent::__construct(
            'minter_balance_widget',
            'Minter Balance Widget',
            ['description' => 'Позволяет выводить баланс кошелька сети Minter (Все монеты)']
        );
    }

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $address = apply_filters('widget_address', $instance['address']);
        $round = (int)apply_filters('widget_round_to', $instance['round_to']);

        echo $args['before_widget'];
        //if title is present
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];
        //output
        $balance = minter_get_address_balance($address);
        if($balance) {
            echo '<ul class="minter-balance-full-block">';
            foreach ($balance as $ticker => $value){ ?>
                <li class="minter-balance-full-row">
                    <span class="minter-balance-full-value"><?php echo minter_round_result($value, $round); ?></span>
                    <span class="minter-balance-full-ticker"><?php echo $ticker; ?></span>
                </li>
            <?php }
            echo '</ul>';
        }
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : 'Minter Balance';
        $address = isset($instance[ 'address' ]) ? $instance[ 'address' ] : 'Mx8e6c210b6f310ce8e38024838becca67cb52a428';
        $round = isset($instance[ 'round_to' ]) ? (int)$instance[ 'round_to' ] : -1;
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
            <label for="<?php echo $this->get_field_id('round_to'); ?>"><?php _e('Round to:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('round_to'); ?>" name="<?php echo $this->get_field_name('round_to'); ?>" type="number" min="-1" max="18" value="<?php echo esc_attr($round); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = [];
        $instance['title'] = (!empty( $new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['address'] = (!empty( $new_instance['address'])) ? strip_tags($new_instance['address']) : '';
        $instance['round_to'] = (!empty( $new_instance['round_to'])) ? (int)strip_tags($new_instance['round_to']) : -1;
        return $instance;
    }
}


function minter_balance_register_widget() {
    register_widget('BalanceWidget');
}
add_action('widgets_init', 'minter_balance_register_widget');