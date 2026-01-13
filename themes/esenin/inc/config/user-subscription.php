<?php

if (!defined('ABSPATH')) {
    exit;
}

function usp_create_tables() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_subscriptions';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        subscriber_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
		subscribe_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('after_switch_theme', 'usp_create_tables');

function usp_enqueue_scripts() {
    wp_enqueue_script('usp-script', get_template_directory_uri() . '/assets/static/js/user-subscription.js', array('jquery'), null, true);
    wp_localize_script('usp-script', 'usp_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'usp_enqueue_scripts');

function usp_subscribe() {
    if (!is_user_logged_in()) {
        wp_send_json_error('Вы должны быть авторизованы.');
    }

    $subscriber_id = get_current_user_id();
    $user_id = intval($_POST['user_id']);

    global $wpdb;
    $table_name = $wpdb->prefix . 'user_subscriptions';

    $existing = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE subscriber_id = %d AND user_id = %d", $subscriber_id, $user_id));

    if ($existing) {
        $wpdb->delete($table_name, array('subscriber_id' => $subscriber_id, 'user_id' => $user_id));
        notify_on_unsubscription($subscriber_id, $user_id); 
		$action = 'unsubscribe';
    } else {
        $wpdb->insert($table_name, array('subscriber_id' => $subscriber_id, 'user_id' => $user_id));
        notify_on_subscription($subscriber_id, $user_id); 
		$action = 'subscribe';
    }

    $subscribers_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE user_id = %d", $user_id));
    $subscriptions_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE subscriber_id = %d", $subscriber_id));

    wp_send_json_success(array(
        'action' => $action,
        'subscribers_count' => $subscribers_count,
        'subscriptions_count' => $subscriptions_count,
    ));
}
add_action('wp_ajax_usp_subscribe', 'usp_subscribe');

function usp_subscription_button($user_id) {
    if (!is_user_logged_in()) {
        return '<a href="' . wp_login_url() . '"><button class="user-subscribe-button">Подписаться</button></a>';
    }

    $subscriber_id = get_current_user_id();
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_subscriptions';

    $is_subscribed = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE subscriber_id = %d AND user_id = %d", $subscriber_id, $user_id));

    $button_text = $is_subscribed ? __('Отписаться', 'esenin') : __('Подписаться', 'esenin');
    $class = $is_subscribed ? 'unsubscribe' : 'subscribe';

    return '<button class="usp-button user-subscribe-button ' . $class . '" data-user-id="' . esc_attr($user_id) . '">' . esc_html($button_text) . '</button>';
}

function usp_add_subscription_button_shortcode($atts) {
    $atts = shortcode_atts(array('user_id' => 0), $atts);
    return usp_subscription_button($atts['user_id']);
}
add_shortcode('subscription_button', 'usp_add_subscription_button_shortcode');

function usp_subscribers_count($atts) {
    $atts = shortcode_atts(array('user_id' => 0), $atts);
    $user_id = intval($atts['user_id']);

    global $wpdb;
    $table_name = $wpdb->prefix . 'user_subscriptions';
    $subscribers_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE user_id = %d", $user_id));

    if ($subscribers_count == 0) {
        return '';
    }

    ob_start(); ?>

    <div class="user-subscribers-count">
        <span class="fw-bold"><?php echo $subscribers_count; ?></span>
        <?php echo num_decline($subscribers_count, 'подписчик,подписчика,подписчиков', 0); ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('subscribers_count', 'usp_subscribers_count');

function usp_subscriptions_count($atts) {
    $atts = shortcode_atts(array('user_id' => 0), $atts);
    $user_id = intval($atts['user_id']);

    global $wpdb;

    $table_name_users = $wpdb->prefix . 'user_subscriptions';
    $subscriptions_count_users = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name_users WHERE subscriber_id = %d", $user_id));

    $table_name_categories = $wpdb->prefix . 'category_subscriptions';
    $subscriptions_count_categories = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name_categories WHERE user_id = %d", $user_id));

    $total_subscriptions = $subscriptions_count_users + $subscriptions_count_categories;

    if ($total_subscriptions == 0) {
        return '';
    }

    ob_start(); ?>
    <div class="user-total-subscriptions-count">
        <span class="fw-bold"><?php echo $total_subscriptions; ?></span>
        <?php echo num_decline($total_subscriptions, 'подписка,подписки,подписок', 0); ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('subscriptions_count', 'usp_subscriptions_count');

function usp_cleanup_subscriptions() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_subscriptions';

    $invalid_subscriptions = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id = 0 OR NOT EXISTS (SELECT ID FROM {$wpdb->users} WHERE ID = subscriber_id)");

    foreach ($invalid_subscriptions as $subscription) {
        $wpdb->delete($table_name, array('id' => $subscription->id));
    }
}
add_action('init', 'usp_cleanup_subscriptions');


// Shortcode Modal Subscriptions List (Подписки)
function usp_display_subscriptions($atts) {
    $user_id = get_current_user_id();
    $author_id = get_queried_object_id();
    global $wpdb;

    $subscribed_users = $wpdb->get_results($wpdb->prepare(
        "SELECT user_id, subscribe_date FROM {$wpdb->prefix}user_subscriptions WHERE subscriber_id = %d ORDER BY subscribe_date DESC",
        $author_id
    ));

    $subscribed_categories = $wpdb->get_results($wpdb->prepare(
        "SELECT category_id, subscribe_date FROM {$wpdb->prefix}category_subscriptions WHERE user_id = %d ORDER BY subscribe_date DESC",
        $author_id
    ));

    if (empty($subscribed_users) && empty($subscribed_categories)) {
        return do_shortcode('[subscriptions_count user_id="' . $author_id . '"]');
    }

    $combined_list = [];

    foreach ($subscribed_categories as $cat) {
        $term = get_term($cat->category_id);
        if ($term) {
            $combined_list[] = [
                'type' => 'category',
                'link' => get_category_link($term->term_id),
                'name' => esc_html($term->name),
                'avatar' => wp_get_attachment_image(get_term_meta($term->term_id, 'esn_category_logo', true), 'thumbnail', false, ['class' => 'avatar-subs-modal']),
                'subscribe_date' => $cat->subscribe_date,
            ];
        }
    }

    foreach ($subscribed_users as $usr) {
        $author = get_userdata($usr->user_id);
        if ($author) {
            $combined_list[] = [
                'type' => 'author',
                'link' => get_author_posts_url($author->ID),
                'name' => esc_html($author->display_name),
                'avatar' => get_avatar($author->ID, 36, '', '', array('class'=>'avatar-subs-modal') ),
                'subscribe_date' => $usr->subscribe_date,
            ];
        }
    }

    usort($combined_list, function($a, $b) {
        return strtotime($b['subscribe_date']) - strtotime($a['subscribe_date']);
    });
    
    ob_start();
    ?>
    <div class="usp-subscriptions-popup">
        <div class="open-popup" id="subscriptions_count">
            <?php echo do_shortcode('[subscriptions_count user_id="' . $author_id . '"]'); ?>
        </div>
        <div class="popup-overlay-subscriptions" style="display:none;">                            
            <div class="popup-content">
                <div class="popup-header">
                    <span class="title-popup"><?php esc_html_e('Подписки', 'esenin'); ?></span>
                    <span class="close-popup">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"></path>
                        </svg>
                    </span>
                </div>

                <div class="subscription-list">
				  <?php if ( is_user_logged_in() ) : ?>
                    <ul>
                        <?php foreach ($combined_list as $item): ?>
                            <li>
                                <a href="<?php echo $item['link']; ?>">
                                    <?php echo $item['avatar']; ?>
                                    <?php echo $item['name']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
				   <?php else : ?>
                      <p><?php echo esc_html__( 'Чтобы просмотреть подписки пользователя, авторизуйтесь на сайте.', 'esenin' ); ?></p>
                   <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('subscriptions_list', 'usp_display_subscriptions');


// Shortcode Modal Subscribers List (Подписчики)
function usp_display_subscribers($atts) {
    $atts = shortcode_atts(array('user_id' => 0), $atts);
    $user_id = intval($atts['user_id']);
	$author_id = get_queried_object_id();

    global $wpdb;
    $table_name = $wpdb->prefix . 'user_subscriptions';

    $subscribers = $wpdb->get_results($wpdb->prepare(
        "SELECT subscriber_id, subscribe_date FROM $table_name WHERE user_id = %d ORDER BY subscribe_date DESC",
        $author_id
    ));
	
	if (empty($subscribers)) {
        return do_shortcode('[subscribers_count user_id="' . $author_id . '"]');
    }

    ob_start();
    ?>		
	<div class="usp-subscriptions-popup">
        <div class="open-popup" id="subscribers_count">
            <?php echo do_shortcode('[subscribers_count user_id="' . $author_id . '"]'); ?>
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
				  <?php if ( is_user_logged_in() ) : ?>
                    <ul>
                        <?php foreach ($subscribers as $subscriber): 
						 $subscriber_data = get_userdata($subscriber->subscriber_id); ?>
                <li>
                    <a href="<?php echo get_author_posts_url($subscriber->subscriber_id); ?>">
                        <?php echo get_avatar($subscriber->subscriber_id, 36, '', '', array('class'=>'avatar-subs-modal')); ?>
                        <?php echo esc_html($subscriber_data->display_name); ?>
                    </a>
                </li>
                 <?php endforeach; ?>
                    </ul>
				   <?php else : ?>
                      <p><?php echo esc_html__( 'Чтобы просмотреть подписчиков пользователя, авторизуйтесь на сайте.', 'esenin' ); ?></p>
                   <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('subscribers_list', 'usp_display_subscribers');