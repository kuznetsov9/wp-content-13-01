<?php
function custom_posts_widget_init() {
    register_widget('Custom_Posts_Widget');
}
add_action('widgets_init', 'custom_posts_widget_init');

class Custom_Posts_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'custom_posts_widget',
            __('(ESENIN) Посты', 'esenin'),
            ['description' => __('Виджет для отображения случайных, новых, популярных или просматриваемых постов.', 'esenin')]
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        $title = apply_filters('widget_title', $instance['title']);
        $display_type = $instance['display_type'];
        $number_of_posts = absint($instance['number_of_posts']); // Привести к целому положительному числу перед использованием

        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $query_args = $this->get_query_args($display_type, $number_of_posts);
        $custom_query = new WP_Query($query_args);

        if ($custom_query->have_posts()) {
            echo '<div class="custom-posts-widget">';
            while ($custom_query->have_posts()) {
                $custom_query->the_post();
                $this->display_post_item();
            }
            echo '</div>';
        }

        wp_reset_postdata();
        echo $args['after_widget'];
    }

    private function get_query_args($display_type, $number_of_posts) {
        $query_args = [
            'post_type' => 'post',
            'posts_per_page' => $number_of_posts,
            'ignore_sticky_posts' => true, // Игнорировать закреплённые посты, если нужно
        ];

        switch ($display_type) {
            case 'random':
                $query_args['orderby'] = 'rand';
                break;
            case 'new':
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'DESC'; // Сортировка по новой записи
                break;
            case 'popularity':
                $query_args['orderby'] = 'meta_value_num';
                $query_args['meta_key'] = 'popularity';
                break;
            case 'post_views_count':
                $query_args['orderby'] = 'meta_value_num';
                $query_args['meta_key'] = 'post_views_count';
                break;
        }

        return $query_args;
    }

    private function display_post_item() {
        $comments_number = get_comments_number();
        $views_count = getPostViews(get_the_ID());
        ?>
        <a href="<?php the_permalink(); ?>">
            <div class="widget-custom-post row align-items-start">
                <div class="widget-custom-meta col">
                    <a href="<?php the_permalink(); ?>" class="widget-custom-post-title"><?php the_title(); ?></a>                    
                    <div class="widget-custom-meta-footer d-flex align-items-center justify-content-start gap-2 mt-1">                     
                        <?php $this->display_comments($comments_number); ?>
                        <?php $this->display_views($views_count); ?>
                    </div>
                </div>                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="widget-custom-image col-auto">
                        <a href="<?php echo esc_url(get_permalink()); ?>">
                            <?php the_post_thumbnail('thumbnail', ['class' => 'img-fluid']); ?>
                        </a>
                    </div>
                <?php endif; ?>                              
            </div>
        </a>
        <?php
    }

    private function display_views($views_count) {
        ?>
        <div class="view-count wc-footer-button d-flex justify-content-center align-items-center">
            <span class="wc-footer-button_icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path stroke="none" d="M23.271,9.419C21.72,6.893,18.192,2.655,12,2.655S2.28,6.893.729,9.419a4.908,4.908,0,0,0,0,5.162C2.28,17.107,5.808,21.345,12,21.345s9.72-4.238,11.271-6.764A4.908,4.908,0,0,0,23.271,9.419Zm-1.705,4.115C20.234,15.7,17.219,19.345,12,19.345S3.766,15.7,2.434,13.534a2.918,2.918,0,0,1,0-3.068C3.766,8.3,6.781,4.655,12,4.655s8.234,3.641,9.566,5.811A2.918,2.918,0,0,1,21.566,13.534Z"/>
                    <path d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z" stroke="none"/>
                </svg>
            </span>
            <span class="wc-footer-button_label">
                <?php echo esc_html($views_count); ?>
            </span>
        </div>
        <?php
    }

    private function display_comments($comments_number) {
        ?>
        <?php if ($comments_number > 0) : ?>
            <div class="comment-count">
                <a href="<?php comments_link(); ?>">
                    <div class="wc-footer-button d-flex justify-content-center align-items-center">
                        <span class="wc-footer-button_icon">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">  
                                <path d="M10.8,19.2c1.462,0,8.4.152,9.6-2.4.607-1.291-3.47-1.292-2.4-3.6a9.714,9.714,0,0,0,1.2-3.6A9.6,9.6,0,1,0,0,9.6C0,14.9,4.3,19.2,10.8,19.2Z" transform="translate(2.4 2.4)" fill="none" stroke-miterlimit="10"/>
                            </svg>
                        </span>
                        <span class="wc-footer-button_label">
                            <?php echo $comments_number; ?>
                        </span>
                    </div>
                </a>
            </div>
        <?php endif; ?>
        <?php
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Интересное', 'esenin');
        $display_type = !empty($instance['display_type']) ? $instance['display_type'] : 'new';
        $number_of_posts = !empty($instance['number_of_posts']) ? $instance['number_of_posts'] : 3;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Заголовок:', 'esenin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('display_type'); ?>"><?php _e('Тип постов:'); ?></label>
            <select id="<?php echo $this->get_field_id('display_type'); ?>" name="<?php echo $this->get_field_name('display_type'); ?>">
                <option value="random" <?php selected($display_type, 'random'); ?>><?php _e('Случайные', 'esenin'); ?></option>
                <option value="new" <?php selected($display_type, 'new'); ?>><?php _e('Новые', 'esenin'); ?></option>
                <option value="popularity" <?php selected($display_type, 'popularity'); ?>><?php _e('Популярные', 'esenin'); ?></option>
                <option value="post_views_count" <?php selected($display_type, 'post_views_count'); ?>><?php _e('Просматриваемые', 'esenin'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_posts'); ?>"><?php _e('Количество:', 'esenin'); ?></label>
            <input id="<?php echo $this->get_field_id('number_of_posts'); ?>" name="<?php echo $this->get_field_name('number_of_posts'); ?>" type="number" value="<?php echo esc_attr($number_of_posts); ?>" min="1">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['display_type'] = (!empty($new_instance['display_type'])) ? $new_instance['display_type'] : 'new';
        $instance['number_of_posts'] = (!empty($new_instance['number_of_posts'])) ? absint($new_instance['number_of_posts']) : 3;

        return $instance;
    }
}