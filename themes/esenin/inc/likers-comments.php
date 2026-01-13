<?php
if (!defined('ABSPATH')) exit;

/**
 * 1. Подключение скриптов
 */
function like_comment_system_enqueue_scripts() {
    wp_enqueue_script('like-comment-system-js', get_template_directory_uri() . '/assets/static/js/likers-comments.js', array('jquery'), null, true);
    wp_localize_script('like-comment-system-js', 'rfplDataComm', array(
        'root'  => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest')
    ));
}
add_action('wp_enqueue_scripts', 'like_comment_system_enqueue_scripts');

/**
 * 2. Регистрация эндпоинтов REST API
 */
add_action('rest_api_init', function () {
    register_rest_route('rfpl/v1', '/like-comment/(?P<id>\d+)', array(
        'methods'             => 'POST',
        'callback'            => 'rfpl_rest_handle_comment_like',
        'permission_callback' => function () { return is_user_logged_in(); },
    ));

    register_rest_route('rfpl/v1', '/comment-voters/(?P<id>\d+)', array(
        'methods'             => 'GET',
        'callback'            => 'rfpl_rest_get_comment_voters',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('rfpl/v1', '/comments/(?P<id>\d+)', array(
        'methods'             => 'GET',
        'callback'            => 'rfpl_rest_get_sorted_comments',
        'permission_callback' => '__return_true',
    ));
});

/**
 * 3. Перехват параметров запроса (Force Popular by Default)
 */
add_action('pre_get_comments', function($query) {
    if (is_admin()) return;

    // Если orderby не задан или стоит стандартный 'comment_date_gmt'
    // и при этом в URL нет явного указания на другой сорт
    if (empty($query->query_vars['orderby']) || $query->query_vars['orderby'] == 'comment_date_gmt') {
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'popular';
        
        if ($sort === 'popular') {
            // Ставим метку, которую поймает наш SQL-фильтр ниже
            $query->query_vars['orderby'] = 'comment_karma';
        }
    }
});

/**
 * 4. Хардкорный SQL-фильтр (Тот самый JOIN к Flat-таблице)
 */
add_filter('comments_clauses', 'esn_hardcore_sql_sort', 99999, 2);
function esn_hardcore_sql_sort($clauses, $query) {
    if (is_admin()) return $clauses;

    global $wpdb;
    $orderby = isset($query->query_vars['orderby']) ? $query->query_vars['orderby'] : '';

    // Если система или мы сами запросили 'comment_karma'
    if ($orderby === 'comment_karma') {
        $table_stats = $wpdb->prefix . 'comment_stats';
        
        // Клеим плоскую таблицу, если её еще нет в запросе
        if (strpos($clauses['join'], $table_stats) === false) {
            $clauses['join'] .= " LEFT JOIN $table_stats AS stats ON {$wpdb->comments}.comment_ID = stats.comment_id ";
        }
        
        // Сортируем по карме из плоской таблицы, а затем по дате
        $clauses['orderby'] = "stats.karma DESC, {$wpdb->comments}.comment_date DESC";
    }

    return $clauses;
}

/**
 * 5. Обработчик REST API для подгрузки комментов
 */
function rfpl_rest_get_sorted_comments($request) {
    $post_id  = $request['id'];
    $sort     = $request->get_param('sort') ?: 'popular';
    $page     = (int) $request->get_param('page') ?: 1;
    $per_page = (int) get_option('comments_per_page') ?: 40;

    $args = array(
        'post_id' => $post_id,
        'status'  => 'approve',
        'number'  => $per_page,
        'paged'   => $page,
    );

    switch ($sort) {
        case 'oldest':  $args['orderby'] = 'comment_date'; $args['order'] = 'ASC'; break;
        case 'newest':  $args['orderby'] = 'comment_date'; $args['order'] = 'DESC'; break;
        case 'popular': $args['orderby'] = 'comment_karma'; break; 
    }

    $comments_query = new WP_Comment_Query;
    $comments = $comments_query->query($args);
    $total_comments = get_comments_number($post_id);
    $max_pages = ceil($total_comments / $per_page);

    ob_start();
    if ($comments) {
        wp_list_comments(array(
            'callback'    => 'esn_comments_callback',
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 34
        ), $comments);
    } else {
        if ($page === 1) echo '<li class="es-no-comments">Комментариев нет</li>';
    }
    
    return array(
        'html'      => ob_get_clean(),
        'max_pages' => (int)$max_pages,
        'current'   => $page
    );
}

/**
 * 6. Логика лайка (Запись в Flat-таблицу)
 */
function rfpl_rest_handle_comment_like($request) {
    global $wpdb;
    $comment_id = $request['id'];
    $user_id = get_current_user_id();
    $comment = get_comment($comment_id);
    if (!$comment) return new WP_Error('err', 'Not found', array('status' => 404));

    $table_name = $wpdb->prefix . 'comment_likes';
    $existing = $wpdb->get_row($wpdb->prepare("SELECT id FROM $table_name WHERE comment_id = %d AND user_id = %d", $comment_id, $user_id));

    if ($existing) {
        $wpdb->delete($table_name, array('id' => $existing->id));
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->comments} SET comment_karma = comment_karma - 1 WHERE comment_ID = %d", $comment_id));
        if ($user_id != $comment->user_id) update_user_karma($comment->user_id, -1);
    } else {
        $wpdb->insert($table_name, array('comment_id' => $comment_id, 'user_id' => $user_id));
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->comments} SET comment_karma = comment_karma + 1 WHERE comment_ID = %d", $comment_id));
        if ($user_id != $comment->user_id) update_user_karma($comment->user_id, 1);
    }

    $new_karma = $wpdb->get_var($wpdb->prepare("SELECT comment_karma FROM {$wpdb->comments} WHERE comment_ID = %d", $comment_id));

    // Обновляем Flat-таблицу
    if (function_exists('rpl_update_comment_stats_flat')) {
        rpl_update_comment_stats_flat($comment_id);
    }    
    
    return array('success' => true, 'count' => (int)$new_karma);
}

/**
 * 7. Вывод вотерсов
 */
function rfpl_rest_get_comment_voters($request) {
    global $wpdb;
    $comment_id = $request['id'];
    $voters = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->prefix}comment_likes WHERE comment_id = %d ORDER BY id DESC LIMIT 6", $comment_id));
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

/**
 * 8. Кнопка лайка (Чтение из Flat-таблицы)
 */
function like_comment_system_like_button($atts) {
    $comment_id = intval($atts['id']);
    global $wpdb;
    $karma = $wpdb->get_var($wpdb->prepare("SELECT karma FROM {$wpdb->prefix}comment_stats WHERE comment_id = %d", $comment_id));
    if ($karma === null) {
        $comment = get_comment($comment_id);
        $karma = $comment ? $comment->comment_karma : 0;
    }
    $liked = false;
    if (is_user_logged_in()) {
        $liked = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}comment_likes WHERE comment_id = %d AND user_id = %d", $comment_id, get_current_user_id()));
    }
    ob_start(); ?>
    <div class="like-comment-button" data-comment-id="<?php echo $comment_id; ?>">
        <span class="comment-item-button d-flex justify-content-center align-items-center">
            <span class="comment-item-button_icon like-comm-btn <?php echo $liked ? 'liked' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path stroke="none" d="M20.602,4.702c-1.218-1.136-2.812-1.752-4.385-1.686-1.423,.059-2.735,.672-3.695,1.727l-.521,.574-.521-.574c-.96-1.055-2.272-1.668-3.695-1.727-1.575-.068-3.168,.55-4.385,1.686-1.127,1.052-2.431,3.188-2.397,5.744,.033,2.582,1.355,5.049,3.93,7.331,.857,.76,1.87,1.465,3.095,2.157,1.223,.69,2.598,1.055,3.975,1.055s2.752-.365,3.976-1.055c1.224-.691,2.236-1.397,3.094-2.157,2.574-2.282,3.896-4.749,3.93-7.331,.033-2.556-1.271-4.692-2.397-5.744Zm-2.858,11.578c-.751,.666-1.651,1.291-2.751,1.912-1.848,1.043-4.138,1.042-5.983,0-1.101-.622-2.001-1.247-2.752-1.912-2.135-1.893-3.23-3.865-3.257-5.86-.026-2.036,1.097-3.633,1.764-4.256,.79-.737,1.8-1.152,2.793-1.152,.984,0,1.836,.411,2.441,1.077l1.262,1.388c.379,.417,1.102,.417,1.48,0l1.262-1.388c.605-.666,1.399-1.038,2.297-1.074,1.038-.042,2.109,.376,2.938,1.149,.667,.623,1.79,2.221,1.764,4.256-.026,1.996-1.122,3.967-3.257,5.86Z"/></svg>
            </span>
            <span class="dropdown-likers-show comment-item-button_label" tabindex=0>
                <span class="like-count"><?php echo (int)$karma; ?></span>
                <span class="dropdown-content dropdown-menu"></span>
            </span>
        </span>
    </div>
    <?php return ob_get_clean();
}
add_shortcode('like_comment_button', 'like_comment_system_like_button');