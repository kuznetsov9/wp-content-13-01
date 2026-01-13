<?php
add_action('widgets_init', function() {
    register_widget('ESN_Themes_Widget');
});

class ESN_Themes_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'esn_themes_widget',
            __('(ESENIN) Темы', 'esenin'),
            array('description' => __('Виджет со списком тем (категорий)', 'esenin'))
        );

        add_action('wp_ajax_load_more_categories', array($this, 'load_more_categories'));
        add_action('wp_ajax_nopriv_load_more_categories', array($this, 'load_more_categories'));
    }

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $classname = !empty($instance['classname']) ? $instance['classname'] : '';
        $widget_id = $this->id;

        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $categories = get_categories(array('number' => 8)); 

        $show_load_more_button = count($categories) >= 8;

        echo '<ul class="es-wid-themes ' . esc_attr($classname) . '" data-widget-id="' . esc_attr($widget_id) . '">';

        $current_category_id = is_category() ? get_queried_object_id() : 0;

        foreach ($categories as $category) {
            $this->render_category($category, $current_category_id);
        }
        echo '</ul>';

        if ($show_load_more_button && !empty($categories)) { ?>
            <div class="load-more-themes" data-offset="8" data-widget-id="<?php echo esc_attr($widget_id); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M18.71,8.21a1,1,0,0,0-1.42,0l-4.58,4.58a1,1,0,0,1-1.42,0L6.71,8.21a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l4.59,4.59a3,3,0,0,0,4.24,0l4.59-4.59A1,1,0,0,0,18.71,8.21Z"/>
                </svg>
                <span><?php _e('Показать ещё', 'esenin'); ?></span>                        
            </div>
        <?php }

        echo $args['after_widget'];

        add_action('wp_footer', array($this, 'enqueue_scripts'));
    }

    private function render_category($category, $current_category_id) {
        $esn_category_logo = get_term_meta($category->term_id, 'esn_category_logo', true);
        $current_category_class = ($current_category_id === $category->term_id) ? 'current-theme' : '';

        echo '<li class="es-wid-theme-item ' . esc_attr($current_category_class) . '">';
        echo '<a href="' . esc_url(get_term_link($category->term_id)) . '">';

        if ($esn_category_logo) {
            echo '<div class="es-wid-theme-item__logo flex-shrink-0">';
            esn_get_retina_image($esn_category_logo);
            echo '</div>';
        }

        echo '<span title="' . esc_html($category->name) . '">' . esc_html($category->name) . '</span>';
        echo '</a>';
        echo '</li>';
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $classname = !empty($instance['classname']) ? $instance['classname'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Заголовок:', 'esenin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('classname'); ?>"><?php _e('Доп. class CSS:', 'esenin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('classname'); ?>" name="<?php echo $this->get_field_name('classname'); ?>" type="text" value="<?php echo esc_attr($classname); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['classname'] = (!empty($new_instance['classname'])) ? strip_tags($new_instance['classname']) : '';
        return $instance;
    }

    public function enqueue_scripts() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.load-more-themes').click(function(e) {
                    e.preventDefault();
                    var button = $(this);
                    var offset = button.data('offset');
                    var widgetId = button.data('widget-id'); 

                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'load_more_categories',
                            offset: offset,
                            widget_id: widgetId 
                        },
                        success: function(response) {
                            if (response) {
                                $('.es-wid-themes[data-widget-id="'+ widgetId +'"]').append(response);
                                button.data('offset', offset + 5);

                                var currentUrl = window.location.href;
                                $('.es-wid-theme-item a').each(function() {
                                    if ($(this).attr('href') === currentUrl) {
                                        $(this).closest('li').addClass('current-theme');
                                    }
                                });

                                if (response.split('<li').length < 6) { 
                                    button.remove(); 
                                }
                            } else {
                                button.remove(); 
                            }
                        }
                    });
                });
            });
        </script>
        <?php
    }

    public function load_more_categories() {
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
        $widget_id = isset($_POST['widget_id']) ? sanitize_text_field($_POST['widget_id']) : '';
        $categories = get_categories(array('number' => 5, 'offset' => $offset)); 

        foreach ($categories as $category) {
            $this->render_category($category, 0); 
        }

        wp_die(); 
    }
}
