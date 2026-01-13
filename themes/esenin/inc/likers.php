<?php
if (!defined('ABSPATH')) exit;

function like_system_enqueue_scripts() {
    wp_enqueue_script('like-system-js', get_template_directory_uri() . '/assets/static/js/likers.js', array('jquery'), null, true);
    wp_localize_script('like-system-js', 'rfplData', array(
        'root'  => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest')
    ));
}
add_action('wp_enqueue_scripts', 'like_system_enqueue_scripts');

add_action('rest_api_init', function () {
    register_rest_route('rfpl/v1', '/like/(?P<id>\d+)', array(
        'methods'             => 'POST',
        'callback'            => 'rfpl_rest_handle_like',
        'permission_callback' => function () { return is_user_logged_in(); },
    ));
    register_rest_route('rfpl/v1', '/voters/(?P<id>\d+)', array(
        'methods'             => 'GET',
        'callback'            => 'rfpl_rest_get_voters',
        'permission_callback' => '__return_true',
    ));
});

function rfpl_rest_handle_like($request) {
    global $wpdb;
    $post_id = $request['id'];
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'likes';
    $post = get_post($post_id);
    if (!$post) return new WP_Error('no_post', 'Post not found', array('status' => 404));

    $existing = $wpdb->get_row($wpdb->prepare("SELECT id FROM $table_name WHERE post_id = %d AND user_id = %d", $post_id, $user_id));
    $current_likes = (int)get_post_meta($post_id, '_likes_count', true);

    if ($existing) {
        $wpdb->delete($table_name, array('id' => $existing->id));
        if ($user_id != $post->post_author) update_user_karma($post->post_author, -1);
        $new_count = max(0, $current_likes - 1);
        update_post_meta($post_id, '_likes_count', $new_count);
        notify_on_like($post_id, $user_id, 'remove');
        $action = 'remove';
    } else {
        $wpdb->insert($table_name, array('post_id' => $post_id, 'user_id' => $user_id));
        if ($user_id != $post->post_author) update_user_karma($post->post_author, 1);
        $new_count = $current_likes + 1;
        update_post_meta($post_id, '_likes_count', $new_count);
        notify_on_like($post_id, $user_id, 'add');
        $action = 'add';
    }

    // --- ОБНОВЛЕНИЕ FLAT-ТАБЛИЦЫ (Масштабирование) ---
    // Вызываем функцию, которую мы прописали в db-optimization.php
    if (function_exists('rpl_update_post_stats_flat')) {
        rpl_update_post_stats_flat($post_id);
    }
    // ------------------------------------------------

    return array('success' => true, 'action' => $action, 'count' => $new_count);
}

function rfpl_rest_get_voters($request) {
    global $wpdb;
    $post_id = $request['id'];
    $voters = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->prefix}likes WHERE post_id = %d ORDER BY id DESC LIMIT 6", $post_id));

    if (empty($voters)) return array('votersHtml' => '<div class="likers-no-voters">Оценивших нет</div>');

    ob_start();
    echo '<div class="likers-avatar-group">';
    foreach ($voters as $voter) {
        $user_info = get_userdata($voter->user_id);
        if ($user_info) {
            printf('<div class="likers-avatar"><a href="%s" title="%s">%s</a></div>',
                esc_url(get_author_posts_url($voter->user_id)),
                esc_attr($user_info->display_name),
                get_avatar($voter->user_id, 24)
            );
        }
    }
    echo '</div>';
    return array('votersHtml' => ob_get_clean());
}

function like_system_like_button($atts) {
    $post_id = get_the_ID();
    $user_id = get_current_user_id();

    // --- ОПТИМИЗАЦИЯ: Берем лайки из FLAT-таблицы ---
    global $wpdb;
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT likes_count FROM {$wpdb->prefix}post_stats WHERE post_id = %d", 
        $post_id
    ));

    // Запасной вариант, если во flat-таблице еще нет записи
    if ($count === null) {
        $count = (int)get_post_meta($post_id, '_likes_count', true);
    }
    // -----------------------------------------------

    $liked = false;
    if ($user_id) {
        $liked = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}likes WHERE post_id = %d AND user_id = %d", $post_id, $user_id));
    }
    ob_start(); ?>
    <div class="like-button" data-post-id="<?php echo $post_id; ?>">
        <span class="pc-footer-button d-flex justify-content-center align-items-center">
            <span class="pc-footer-button_icon like-btn <?php echo $liked ? 'liked' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path stroke="none" d="M20.602,4.702c-1.218-1.136-2.812-1.752-4.385-1.686-1.423,.059-2.735,.672-3.695,1.727l-.521,.574-.521-.574c-.96-1.055-2.272-1.668-3.695-1.727-1.575-.068-3.168,.55-4.385,1.686-1.127,1.052-2.431,3.188-2.397,5.744,.033,2.582,1.355,5.049,3.93,7.331,.857,.76,1.87,1.465,3.095,2.157,1.223,.69,2.598,1.055,3.975,1.055s2.752-.365,3.976-1.055c1.224-.691,2.236-1.397,3.094-2.157,2.574-2.282,3.896-4.749,3.93-7.331,.033-2.556-1.271-4.692-2.397-5.744Zm-2.858,11.578c-.751,.666-1.651,1.291-2.751,1.912-1.848,1.043-4.138,1.042-5.983,0-1.101-.622-2.001-1.247-2.752-1.912-2.135-1.893-3.23-3.865-3.257-5.86-.026-2.036,1.097-3.633,1.764-4.256,.79-.737,1.8-1.152,2.793-1.152,.984,0,1.836,.411,2.441,1.077l1.262,1.388c.379,.417,1.102,.417,1.48,0l1.262-1.388c.605-.666,1.399-1.038,2.297-1.074,1.038-.042,2.109,.376,2.938,1.149,.667,.623,1.79,2.221,1.764,4.256-.026,1.996-1.122,3.967-3.257,5.86Z"/></svg>
            </span>
            <span class="dropdown-likers-show pc-footer-button_label" tabindex=0>
                <span class="like-count"><?php echo $count; ?></span>
                <span class="dropdown-content dropdown-menu"></span>
            </span>
        </span>
    </div>
    <?php return ob_get_clean();
}
add_shortcode('like_button', 'like_system_like_button');