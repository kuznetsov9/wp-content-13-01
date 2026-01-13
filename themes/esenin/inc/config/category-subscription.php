<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Подключение скриптов
 */
function cs_enqueue_scripts() {
    wp_enqueue_script('cs-main-js', get_template_directory_uri() . '/assets/static/js/category-subscription.js', array('jquery'), null, true);
    wp_localize_script('cs-main-js', 'cs_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'cs_enqueue_scripts');

/**
 * Шорткод кнопки подписки
 */
function cs_subscription_shortcode($atts) {
    $atts = shortcode_atts(array('category_id' => '0'), $atts);
    ob_start();
    cs_render_subscription_button($atts['category_id']);
    return ob_get_clean();
}
add_shortcode('cat_subscribe', 'cs_subscription_shortcode');

/**
 * Шорткод счетчика подписчиков
 */
function cs_subscription_count_shortcode($atts) {
    $atts = shortcode_atts(array('category_id' => '0'), $atts);
    ob_start();
    cs_render_subscription_count($atts['category_id']);
    return ob_get_clean();
}
add_shortcode('cat_subscribe_count', 'cs_subscription_count_shortcode');

/**
 * Рендер кнопки
 */
function cs_render_subscription_button($category_id) {
    $user_id = get_current_user_id();
    $is_subscribed = cs_user_is_subscribed($category_id, $user_id);
    $class = $is_subscribed ? 'unsubscribe' : 'subscribe';
    
    if (is_user_logged_in()) { ?>
        <span class="cs-subscription-container" title="Подписаться/отписаться на категорию" data-category-id="<?php echo esc_attr($category_id); ?>">
            <button class="cs-subscribe-button <?php echo esc_attr($class); ?>">
                <?php echo $is_subscribed ? __('Отписаться', 'esenin') : __('Подписаться', 'esenin'); ?>
            </button>
        </span>
    <?php } else {
        echo '<a href="' . wp_login_url() . '"><button class="cs-subscribe-button">' . __('Подписаться', 'esenin') . '</button></a>';
    }
}

/**
 * Рендер счетчика
 */
function cs_render_subscription_count($category_id) {
    $subscribers_count = cs_get_subscribers_count($category_id);
    if ($subscribers_count > 0) { ?>
        <div class="cs-subscribers-count">
            <span><?php echo esc_html($subscribers_count); ?></span>
            <?php echo num_decline($subscribers_count, 'подписчик,подписчика,подписчиков', 0); ?>
        </div>
    <?php } 
}

/**
 * Получение кол-ва подписчиков
 */
function cs_get_subscribers_count($category_id) {
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}category_subscriptions WHERE category_id = %d", $category_id));
}

/**
 * Проверка подписки
 */
function cs_user_is_subscribed($category_id, $user_id) {
    if (!$user_id) return false;
    global $wpdb;
    return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}category_subscriptions WHERE category_id = %d AND user_id = %d", $category_id, $user_id)) > 0;
}

/**
 * ОБРАБОТКА AJAX (с защитой от спама)
 */
function cs_handle_subscription() {
    if (!is_user_logged_in()) {
        wp_send_json_error('Авторизуйтесь на сайте.');
    }

    $category_id = intval($_POST['category_id']);
    $user_id = get_current_user_id();
    global $wpdb;

    // Анти-спам защита (Transient замок на 2 секунды)
    $lock_key = 'cs_sub_lock_' . $user_id;
    if (get_transient($lock_key)) {
        wp_send_json_error('Слишком быстро! Подождите секунду.');
    }
    set_transient($lock_key, true, 2);

    $table_name = $wpdb->prefix . "category_subscriptions";

    if (cs_user_is_subscribed($category_id, $user_id)) {
        $wpdb->delete($table_name, array('category_id' => $category_id, 'user_id' => $user_id));
        $action = 'unsubscribed';
    } else {
        $wpdb->insert($table_name, array('category_id' => $category_id, 'user_id' => $user_id));
        $action = 'subscribed';
    }

    $subscribers_count = cs_get_subscribers_count($category_id);
    wp_send_json_success(array(
        'subscribers_count' => $subscribers_count, 
        'action' => $action
    ));
}
add_action('wp_ajax_cs_handle_subscription', 'cs_handle_subscription');

/**
 * Создание таблицы при смене темы
 */
function cs_create_subscription_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . "category_subscriptions";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        category_id mediumint(9) NOT NULL,
        user_id mediumint(9) NOT NULL,
        subscribe_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'cs_create_subscription_table');

/**
 * Хелперы для тем
 */
function subscribe_to_category($category_id, $user_id) {
    global $wpdb;
    if (!cs_user_is_subscribed($category_id, $user_id)) {
        $wpdb->insert($wpdb->prefix . "category_subscriptions", array('category_id' => $category_id, 'user_id' => $user_id));
        return true; 
    }
    return false; 
}

function get_subscribed_categories($user_id) {
    global $wpdb;
    return $wpdb->get_results($wpdb->prepare(
        "SELECT c.term_id, c.name FROM {$wpdb->prefix}category_subscriptions AS cs 
        JOIN {$wpdb->prefix}terms AS c ON cs.category_id = c.term_id 
        WHERE cs.user_id = %d", 
        $user_id
    ), ARRAY_A); 
}

/**
 * Шорткод списка подписчиков категории (модалка)
 */
function usp_display_category_subscribers($atts) {
    $atts = shortcode_atts(array('category_id' => 0), $atts);
    $category_id = intval($atts['category_id']);

    global $wpdb;
    $table_name = $wpdb->prefix . 'category_subscriptions';

    $subscribers = $wpdb->get_results($wpdb->prepare(
        "SELECT user_id FROM $table_name WHERE category_id = %d ORDER BY subscribe_date DESC",
        $category_id
    ));

    if (empty($subscribers)) {
        return ''; 
    }

    ob_start(); ?>
    <div class="usp-category-subscribers-popup">
        <div class="open-popup" id="subscribers_count">
            <?php echo do_shortcode('[cat_subscribe_count category_id="' . $category_id . '"]'); ?>
        </div>
        <div class="popup-overlay-subscribers" style="display:none;">
            <div class="popup-content">
                <div class="popup-header">
                    <span class="title-popup"><?php esc_html_e('Подписчики', 'esenin'); ?></span>
                    <span class="close-popup">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"></path>
                        </svg>
                    </span>
                </div>
                <div class="subscription-list">
                    <ul>
                        <?php foreach ($subscribers as $subscriber): 
                            $subscriber_data = get_userdata($subscriber->user_id);
                            if ($subscriber_data) : ?>
                            <li>
                                <a href="<?php echo get_author_posts_url($subscriber->user_id); ?>">
                                    <?php echo get_avatar($subscriber->user_id, 36, '', '', array('class'=>'avatar-subs-modal')); ?>
                                    <?php echo esc_html($subscriber_data->display_name); ?>
                                </a>
                            </li>
                            <?php endif;
                        endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('category_subscribers_list', 'usp_display_category_subscribers');