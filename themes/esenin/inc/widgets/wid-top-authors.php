<?php
add_action('widgets_init', function() {
    register_widget('User_Karma_Widget');
});

class User_Karma_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'user_karma_widget',
            __('(ESENIN) Топ авторов', 'esenin'), 
            array('description' => __('Виджет с выводом рейтинга авторов на основе кармы', 'esenin'))
        );
    }

    public function widget($args, $instance) {
        global $wpdb;

        $title = !empty($instance['title']) ? $instance['title'] : __('Топ авторов', 'esenin');
        $number = !empty($instance['number']) ? absint($instance['number']) : 4;

        echo $args['before_widget'];
        echo $args['before_title'] . esc_html($title) . $args['after_title'];

        $table_name = $wpdb->prefix . 'user_karma';

        // Здесь мы добавляем условие для фильтрации пользователей с положительной кармой
        $users = $wpdb->get_results($wpdb->prepare(
            "SELECT user_id, karma FROM $table_name WHERE karma > 0 ORDER BY karma DESC LIMIT %d", $number
        ));
				
		 if ($users) {
            echo '<ol class="widget-top-authors">';
            foreach ($users as $user) {
                $user_info = get_userdata($user->user_id);
                if ($user_info) {
                    $avatar = get_avatar($user_info->ID, 40);
                    $profile_link = get_author_posts_url($user_info->ID);
                    echo '<li>';
					echo '<a href="' . esc_url($profile_link) . '">';
                    echo '<div class="d-flex align-items-center justify-content-between m-0">';
                    echo '<div class="d-flex align-items-center">';
                    echo '<div class="me-2 flex-shrink-0 wta-avatar">';
                    echo $avatar;
                    echo '</div>';
                    echo '<div class="flex-grow-1 wta-name">';
                    echo '<div class="wta-author-name">' . esc_html($user_info->display_name) . '</div>';
                    echo do_shortcode('[subscribers_count user_id="' . $user_info->ID . '"]');
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="d-flex align-items-center wta-karma">';
                    echo esc_html($user->karma);
                    echo '</div>';
                    echo '</div>';
					echo '</a>';
                    echo '</li>';
                }
            }
            echo '</ol>';
        } else {
            echo __('Популярных пока нет..', 'esenin');
        }
		
		

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Топ авторов', 'esenin');
        $number = !empty($instance['number']) ? absint($instance['number']) : 4;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Заголовок:', 'esenin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Количество:', 'esenin'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" value="<?php echo esc_attr($number); ?>" min="1" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 4;
        return $instance;
    }
}

function display_user_karma_widget($title = null, $number = null) {
    $title = $title ?: __('Топ авторов', 'esenin');
    $number = $number ?: 4;
    the_widget('User_Karma_Widget', array('title' => $title, 'number' => $number));
}
?>