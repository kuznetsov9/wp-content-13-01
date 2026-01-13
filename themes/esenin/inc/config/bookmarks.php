<?php
/**
 * Bookmarks Ajax.
 *
 * @package Esenin
 */

defined('ABSPATH') || exit;

add_action('after_switch_theme', 'esen_register_bookmarks');
add_action('wp_enqueue_scripts', 'esen_enqueue_scripts');
add_action('wp_ajax_nopriv_esen_add_remove_bookmark', 'esen_add_remove_bookmark'); // Добавляем обработку для незарегистрированных пользователей
add_action('wp_ajax_esen_add_remove_bookmark', 'esen_add_remove_bookmark');

add_shortcode('esen_bookmark_button', 'esen_bookmark_button_shortcode');
add_shortcode('esen_bookmarks_list', 'esen_bookmarks_list_shortcode');

function esen_register_bookmarks() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'bookmarks';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id mediumint(9) NOT NULL,
        post_id mediumint(9) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function esen_enqueue_scripts() {
    wp_enqueue_script('es-ajax-script', get_template_directory_uri() . '/assets/static/js/bookmark.js', array('jquery'), null, true);
    wp_localize_script('es-ajax-script', 'esen_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

function esen_add_remove_bookmark() {
    $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0; // Проверить наличие post_id
    $user_id = get_current_user_id();

    if ($post_id === 0) {
        wp_send_json_error(array('message' => 'invalid_post_id'));
    }

    // Если не авторизован, просто возвращаем ошибку без перенаправления
    if ($user_id == 0) {
        wp_send_json_error(array('message' => 'not_logged_in'));
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'bookmarks';

    $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND post_id = %d", $user_id, $post_id));

    if ($exists) {
        $wpdb->delete($table_name, array('user_id' => $user_id, 'post_id' => $post_id));
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path stroke="none" d="M23.836,8.794a3.179,3.179,0,0,0-3.067-2.226H16.4L15.073,2.432a3.227,3.227,0,0,0-6.146,0L7.6,6.568H3.231a3.227,3.227,0,0,0-1.9,5.832L4.887,15,3.535,19.187A3.178,3.178,0,0,0,4.719,22.8a3.177,3.177,0,0,0,3.8-.019L12,20.219l3.482,2.559a3.227,3.227,0,0,0,4.983-3.591L19.113,15l3.56-2.6A3.177,3.177,0,0,0,23.836,8.794Zm-2.343,1.991-4.144,3.029a1,1,0,0,0-.362,1.116L18.562,19.8a1.227,1.227,0,0,1-1.895,1.365l-4.075-3a1,1,0,0,0-1.184,0l-4.075,3a1.227,1.227,0,0,1-1.9-1.365L7.013,14.93a1,1,0,0,0-.362-1.116L2.507,10.785a1.227,1.227,0,0,1,.724-2.217h5.1a1,1,0,0,0,.952-.694l1.55-4.831a1.227,1.227,0,0,1,2.336,0l1.55,4.831a1,1,0,0,0,.952.694h5.1a1.227,1.227,0,0,1,.724,2.217Z"/></svg>';
        wp_send_json_success(array('icon' => $icon, 'action' => 'removed'));
    } else {
        $wpdb->insert($table_name, array('user_id' => $user_id, 'post_id' => $post_id));
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path fill="#f39c12" stroke="none" d="M1.327,12.4,4.887,15,3.535,19.187A3.178,3.178,0,0,0,4.719,22.8a3.177,3.177,0,0,0,3.8-.019L12,20.219l3.482,2.559a3.227,3.227,0,0,0,4.983-3.591L19.113,15l3.56-2.6a3.227,3.227,0,0,0-1.9-5.832H16.4L15.073,2.432a3.227,3.227,0,0,0-6.146,0L7.6,6.568H3.231a3.227,3.227,0,0,0-1.9,5.832Z"/></svg>';
        wp_send_json_success(array('icon' => $icon, 'action' => 'added'));
    }

    wp_die();
}

function esen_bookmark_button_shortcode($atts) {
    global $post;

    $user_id = get_current_user_id();
    $post_id = $post->ID;
    global $wpdb;
    $table_name = $wpdb->prefix . 'bookmarks';

    $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND post_id = %d", $user_id, $post_id));

    $icon_add = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path stroke="none" d="M23.836,8.794a3.179,3.179,0,0,0-3.067-2.226H16.4L15.073,2.432a3.227,3.227,0,0,0-6.146,0L7.6,6.568H3.231a3.227,3.227,0,0,0-1.9,5.832L4.887,15,3.535,19.187A3.178,3.178,0,0,0,4.719,22.8a3.177,3.177,0,0,0,3.8-.019L12,20.219l3.482,2.559a3.227,3.227,0,0,0,4.983-3.591L19.113,15l3.56-2.6A3.177,3.177,0,0,0,23.836,8.794Zm-2.343,1.991-4.144,3.029a1,1,0,0,0-.362,1.116L18.562,19.8a1.227,1.227,0,0,1-1.895,1.365l-4.075-3a1,1,0,0,0-1.184,0l-4.075,3a1.227,1.227,0,0,1-1.9-1.365L7.013,14.93a1,1,0,0,0-.362-1.116L2.507,10.785a1.227,1.227,0,0,1,.724-2.217h5.1a1,1,0,0,0,.952-.694l1.55-4.831a1.227,1.227,0,0,1,2.336,0l1.55,4.831a1,1,0,0,0,.952.694h5.1a1.227,1.227,0,0,1,.724,2.217Z"/></svg>';
    $icon_remove = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path fill="#f39c12" stroke="none" d="M1.327,12.4,4.887,15,3.535,19.187A3.178,3.178,0,0,0,4.719,22.8a3.177,3.177,0,0,0,3.8-.019L12,20.219l3.482,2.559a3.227,3.227,0,0,0,4.983-3.591L19.113,15l3.56-2.6a3.227,3.227,0,0,0-1.9-5.832H16.4L15.073,2.432a3.227,3.227,0,0,0-6.146,0L7.6,6.568H3.231a3.227,3.227,0,0,0-1.9,5.832Z"/></svg>';

    $button_text = $exists ? $icon_remove : $icon_add;
    $title_text = $exists ? __('Удалить из закладок', 'esenin') : __('Добавить в закладки', 'esenin');

    return '<div class="pc-footer-button d-flex justify-content-center align-items-center"><span class="pc-footer-button_icon"><div class="es-bookmark-button d-flex justify-content-center align-items-center" data-post-id="' . esc_attr($post_id) . '" title="' . esc_attr($title_text) . '">' . $button_text . '</div></span></div>';
}

function esen_bookmarks_list_shortcode($atts) {
    if (!is_user_logged_in()) {
        return '<p>' . esc_html_e('Чтобы просмотреть закладки, требуется авторизация.', 'esenin') . '</p>';
    }

    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'bookmarks';
    $page = max(1, get_query_var('paged', 1));
    $per_page = 10;
    $offset = ($page - 1) * $per_page;

    $bookmarks = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $table_name WHERE user_id = %d LIMIT %d OFFSET %d", $user_id, $per_page, $offset));

    if (!$bookmarks) {
        return '<p>' . esc_html_e('У вас нет закладок.', 'esenin') . '</p>';
    }

    $output = '<ul>';
    foreach ($bookmarks as $bookmark) {
        $post = get_post($bookmark->post_id);
        if ($post) {
            $output .= '<li><a href="' . get_permalink($post) . '">' . esc_html($post->post_title) . '</a></li>';
        }
    }
    $output .= '</ul>';

    $total_bookmarks = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE user_id = %d", $user_id));
    $total_pages = ceil($total_bookmarks / $per_page);
    $output .= paginate_links(array(
        'total' => $total_pages,
        'current' => $page,
    ));

    return $output;
}