<?php
// comment-generator-functions.php
defined('ABSPATH') || exit;

/**
 * Санитайзеры - оставляем без изменений для совместимости
 */
function wpex_comment_generator_sanitize_number($input) {
    $number = absint($input);
    return ($number > 0) ? $number : 1;
}

function wpex_comment_generator_sanitize_sentences($input) {
    if (!is_array($input)) {
        $input = explode("\n", (string)$input);
    }
    return array_map('sanitize_text_field', array_filter($input));
}

function wpex_comment_generator_sanitize_authors($input) {
    if (is_array($input)) {
        $authors = $input;
    } else {
        $authors = explode("\n", (string)$input);
    }
    $sanitized = array();
    foreach ($authors as $author) {
        $parts = explode('|', $author);
        if (count($parts) === 2) {
            $name = sanitize_text_field($parts[0]);
            $email = sanitize_email($parts[1]);
            if ($name && $email) { $sanitized[] = "$name|$email"; }
        } elseif (count($parts) === 1) {
            $name = sanitize_text_field($parts[0]);
            if ($name) { $sanitized[] = $name; }
        }
    }
    return $sanitized;
}

/**
 * AJAX удаление истории
 */
function wpex_comment_generator_delete_commented_items() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
        return;
    }
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wpex_comment_generator_ajax_nonce')) {
        wp_send_json_error('Invalid nonce.');
    }
    if (get_option('wpex_comment_generator_commented_items')) {
        delete_option('wpex_comment_generator_commented_items');
        wp_send_json_success();
    } else {
        wp_send_json_error('Option not found.');
    }
}
add_action('wp_ajax_wpex_comment_generator_delete_commented_items', 'wpex_comment_generator_delete_commented_items');

/**
 * Инициация генерации
 */
function wpex_start_comment_generation() {
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions');
    }
    if (!isset($_POST['wpex_comment_generator_nonce']) || !wp_verify_nonce($_POST['wpex_comment_generator_nonce'], 'wpex_comment_generator_nonce')) {
        wp_die('Security check failed.');
    }
    return wpex_comment_generator_generate_comment();
}

add_action('admin_init', function () {
    if (isset($_POST['wpex_comment_generator_start'])) {
        $report = wpex_start_comment_generation();
        if ($report) {
            $msg = sprintf("Report: Created: %d, Posts: %d, Exists: %d", $report['cmnt_created'], $report['created'], $report['exist']);
            wp_add_inline_script('comment-generator-settings-scripts', 'alert("'.esc_js($msg).'");');
        }
    }
});

/**
 * БЛОК ОТРИСОВКИ АДМИНКИ - возвращаем все функции , чтобы окна были нормальными
 */
function wpex_comment_generator_settings_page() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Comment Generator Pro', 'comment-generator'); ?></h1>
        <form name="comment-generator-setting" id="comment-generator-setting" action="options.php" method="post">
            <?php settings_fields('wpex_comment_generator_settings_group'); ?>
            <?php wp_nonce_field('update_wpex_comment_generator_settings', 'wpex_comment_generator_settings_nonce'); ?>
            <?php do_settings_sections('wpex_comment_generator_settings_page'); ?>
            <?php submit_button(); ?>
        </form>
        <form name="wpex_comment_generator_start_form" id="comment-generator-start-form" method="post">
            <?php wp_nonce_field('wpex_comment_generator_nonce', 'wpex_comment_generator_nonce'); ?>
            <button type="submit" name="wpex_comment_generator_start" id="comment-generator-start-btn" class="button button-primary">
                <?php esc_html_e('Start Generating Comments', 'comment-generator'); ?>
            </button>
        </form>
    </div>
    <?php
}

// Стили и скрипты
function wpex_comment_generator_settings_script() {
    $screen = get_current_screen();
    if ($screen->base === 'toplevel_page_wpex_comment_generator_settings') {
        wp_enqueue_script('comment-generator-settings-scripts', WPEX_COMMENT_GENERATOR_PLUGIN_url . 'assets/js/admin-setting.js', array('jquery'), '1.0.0', true);
        wp_localize_script('comment-generator-settings-scripts', 'commentGeneratorSettings', array(
            'nonce' => wp_create_nonce('wpex_comment_generator_ajax_nonce'),
            'deleteSuccess' => __('Deleted successfully.', 'comment-generator'),
            'deleteFailed' => __('Failed.', 'comment-generator')
        ));
    }
}
add_action('admin_enqueue_scripts', 'wpex_comment_generator_settings_script');

function wpex_comment_generator_settings_styles() {
    $screen = get_current_screen();
    if ($screen->base === 'toplevel_page_wpex_comment_generator_settings') {
        wp_enqueue_style('comment-generator-settings-styles', WPEX_COMMENT_GENERATOR_PLUGIN_url . 'assets/css/admin-setting.css', array(), '1.0');
    }
}
add_action('admin_enqueue_scripts', 'wpex_comment_generator_settings_styles');

/**
 * ЯДРО ГЕНЕРАЦИИ С ФИКСОМ ПРОФИЛЕЙ
 */
function wpex_comment_generator_generate_comment() {
    if (!current_user_can('manage_options')) return false;
    global $wpdb;
    
    $report = array('success' => false, 'created' => 0, 'exist' => 0, 'cmnt_created' => 0);
    
    $post_type = get_option('wpex_comment_generator_post_type', 'post');
    $category = get_option('wpex_comment_generator_category', 0);
    $product_limit = get_option('wpex_comment_generator_product_limit', 1);
    $comment_count = get_option('wpex_comment_generator_comment_count', 1);
    $commented_items = (array) get_option('wpex_comment_generator_commented_items', array());
    $comment_status = get_option('wpex_comment_generator_comment_status', 0);
    $specific_id = get_option('wpex_comment_generator_specific_post_id', '');
    $comment_mode = get_option('wpex_comment_generator_comment_mode', 'single');
    $x_hours = get_option('wpex_comment_generator_comment_date', 3);

    // Тексты
    $general_comments = (array) get_option('wpex_comment_generator_general_sentences', array());
    $product_buyer_comments = (array) get_option('wpex_comment_generator_product_buyer_sentences', array());
    $product_non_buyer_comments = (array) get_option('wpex_comment_generator_product_non_buyer_sentences', array());
    
    // Авторы
    $authors_raw = (array) get_option('wpex_comment_generator_custom_authors', array());
    $comment_authors = array();
    foreach ($authors_raw as $line) {
        $p = explode('|', $line);
        $comment_authors[] = array('name' => trim($p[0]), 'email' => isset($p[1]) ? trim($p[1]) : '');
    }

    if (empty($comment_authors)) return $report;

    // Запрос
    $args = array('post_type' => $post_type, 'posts_per_page' => $product_limit);
    if ($comment_mode === 'single' && !empty($specific_id)) {
        $args['post__in'] = array((int)$specific_id);
    } elseif ($category > 0) {
        if ($post_type === 'product') {
            $args['tax_query'] = array(array('taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $category));
        } else {
            $args['cat'] = $category;
        }
    }

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $auth_idx = 0;
        $gen_idx = (int) get_option('wpex_comment_generator_general_index', 0);
        
        while ($query->have_posts()) {
            $query->the_post();
            $pid = get_the_ID();

            if (!in_array($pid, $commented_items) || $comment_mode === 'single') {
                for ($i = 0; $i < $comment_count; $i++) {
                    $selected_author = $comment_authors[$auth_idx % count($comment_authors)];
                    
                    // ПОИСК ЮЗЕРА
                    $user_id = 0;
                    if (!empty($selected_author['email'])) {
                        $user = get_user_by('email', $selected_author['email']);
                        if ($user) $user_id = $user->ID;
                    }

                    $ts = current_time('timestamp');
                    $c_date = gmdate('Y-m-d H:i:s', wp_rand($ts - ($x_hours * 3600), $ts));

                    $comment_data = array(
                        'comment_post_ID'      => $pid,
                        'comment_author'       => $selected_author['name'],
                        'comment_author_email' => $selected_author['email'],
                        'comment_approved'     => $comment_status,
                        'comment_date'         => $c_date,
                        'user_id'              => $user_id, // Привязка
                        'comment_content'      => $general_comments[$gen_idx % count($general_comments)] ?? 'Круто!'
                    );

                    // Если это товар WooCommerce
                    if ($post_type === 'product') {
                        $comment_data['comment_type'] = 'review';
                        $comment_data['comment_meta'] = array('rating' => wp_rand(4, 5), 'verified' => 1);
                    }

                    wp_insert_comment($comment_data);
                    $report['cmnt_created']++;
                    $auth_idx++;
                    $gen_idx++;
                }
                $report['created']++;
                $commented_items[] = $pid;
            } else {
                $report['exist']++;
            }
        }
        update_option('wpex_comment_generator_commented_items', $commented_items);
        update_option('wpex_comment_generator_general_index', $gen_idx);
        wp_reset_postdata();
    }
    return $report;
}