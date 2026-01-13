<?php
if (!defined('ABSPATH')) exit;

/**
 * 1. СОЗДАНИЕ И АПГРЕЙД ТАБЛИЦ (Посты + Комменты)
 */
function rpl_setup_optimization_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    // --- ТАБЛИЦА ПОСТОВ ---
    $table_posts = $wpdb->prefix . 'post_stats';
    $sql_posts = "CREATE TABLE $table_posts (
        post_id bigint(20) UNSIGNED NOT NULL,
        likes_count int(11) DEFAULT 0 NOT NULL,
        views_count int(11) DEFAULT 0 NOT NULL,
        comment_count int(11) DEFAULT 0 NOT NULL,
        post_date_ts int(11) UNSIGNED NOT NULL,
        pop_day float DEFAULT 0 NOT NULL,
        pop_week int DEFAULT 0 NOT NULL,
        pop_month int DEFAULT 0 NOT NULL,
        pop_year int DEFAULT 0 NOT NULL,
        pop_all int DEFAULT 0 NOT NULL,
        PRIMARY KEY  (post_id),
        KEY pop_day (pop_day),
        KEY pop_week (pop_week),
        KEY pop_month (pop_month),
        KEY pop_year (pop_year),
        KEY pop_all (pop_all),
        KEY post_date_ts (post_date_ts)
    ) $charset_collate;";
    dbDelta( $sql_posts );

    // --- ТАБЛИЦА КОММЕНТОВ ---
    $table_comments = $wpdb->prefix . 'comment_stats';
    $sql_comments = "CREATE TABLE $table_comments (
        comment_id bigint(20) UNSIGNED NOT NULL,
        karma int(11) DEFAULT 0 NOT NULL,
        PRIMARY KEY  (comment_id),
        KEY karma (karma)
    ) $charset_collate;";
    dbDelta( $sql_comments );

    // Принудительный допил колонок (если dbDelta затупила)
    $wpdb->query("ALTER TABLE $table_posts ADD COLUMN IF NOT EXISTS post_date_ts int(11) UNSIGNED NOT NULL AFTER comment_count");
    $wpdb->query("ALTER TABLE $table_posts ADD COLUMN IF NOT EXISTS pop_year int DEFAULT 0 NOT NULL AFTER pop_month");
    $wpdb->query("ALTER TABLE $table_posts ADD COLUMN IF NOT EXISTS pop_all int DEFAULT 0 NOT NULL AFTER pop_year");
}
add_action('after_switch_theme', 'rpl_setup_optimization_tables');

/**
 * 2. ФУНКЦИИ ОБНОВЛЕНИЯ (The Fast Track)
 */

// Обновить статы поста (Counts Only - Математику сделает Крон)
function rpl_update_post_stats_flat($post_id) {
    global $wpdb;
    $likes = (int) get_post_meta($post_id, '_likes_count', true);
    $views = (int) get_post_meta($post_id, 'post_views_count', true);
    $post = get_post($post_id);
    $comments = $post ? (int) $post->comment_count : 0;
    $ts = $post ? strtotime($post->post_date) : time();

    $wpdb->query($wpdb->prepare(
        "INSERT INTO {$wpdb->prefix}post_stats (post_id, likes_count, views_count, comment_count, post_date_ts) 
         VALUES (%d, %d, %d, %d, %d) 
         ON DUPLICATE KEY UPDATE likes_count = %d, views_count = %d, comment_count = %d",
        $post_id, $likes, $views, $comments, $ts, $likes, $views, $comments
    ));
}

// Обновить статы коммента
function rpl_update_comment_stats_flat($comment_id) {
    global $wpdb;
    $karma = (int) $wpdb->get_var($wpdb->prepare("SELECT comment_karma FROM {$wpdb->comments} WHERE comment_ID = %d", $comment_id));

    $wpdb->query($wpdb->prepare(
        "INSERT INTO {$wpdb->prefix}comment_stats (comment_id, karma) 
         VALUES (%d, %d) 
         ON DUPLICATE KEY UPDATE karma = %d",
        $comment_id, $karma, $karma
    ));
}

/**
 * 3. ХУКИ ДЛЯ НОВЫХ И УДАЛЯЕМЫХ ДАННЫХ
 */

// При публикации нового поста — создаем запись
add_action('publish_post', function($ID, $post) {
    // Вместо тупого insert вызываем твою умную функцию
    // Она сама разберется: вставить новую строку или обновить старую
    rpl_update_post_stats_flat($ID);
}, 10, 2);

// При удалении поста — чистим статы
add_action('deleted_post', function($post_id) {
    global $wpdb;
    $wpdb->delete($wpdb->prefix . 'post_stats', array('post_id' => $post_id));
});

// При удалении коммента — чистим статы
add_action('delete_comment', function($comment_id) {
    global $wpdb;
    $wpdb->delete($wpdb->prefix . 'comment_stats', array('comment_id' => $comment_id));
});

/**
 * 4. МИГРАЦИЯ ДАННЫХ (Запусти один раз: рфпл.рф/?migrate_all=go)
 */
add_action('init', function() {
    if (!isset($_GET['migrate_all']) || $_GET['migrate_all'] !== 'go') return;
    if (!current_user_can('manage_options')) wp_die('Только для админа');

    global $wpdb;
    $table_s = $wpdb->prefix . 'post_stats';
    $table_c = $wpdb->prefix . 'comment_stats';

    // Посты
    $wpdb->query("INSERT IGNORE INTO $table_s (post_id, post_date_ts) 
                  SELECT ID, UNIX_TIMESTAMP(post_date) FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish'");
    
    $wpdb->query("UPDATE $table_s s INNER JOIN {$wpdb->posts} p ON s.post_id = p.ID 
                  SET s.comment_count = p.comment_count, s.post_date_ts = UNIX_TIMESTAMP(p.post_date)");

    // Вытягиваем лайки и просмотры из мета
    $wpdb->query("UPDATE $table_s s SET s.likes_count = IFNULL((SELECT CAST(meta_value AS UNSIGNED) FROM {$wpdb->postmeta} WHERE post_id = s.post_id AND meta_key = '_likes_count' LIMIT 1), 0)");
    $wpdb->query("UPDATE $table_s s SET s.views_count = IFNULL((SELECT CAST(meta_value AS UNSIGNED) FROM {$wpdb->postmeta} WHERE post_id = s.post_id AND meta_key = 'post_views_count' LIMIT 1), 0)");

    // Комменты
    $wpdb->query("INSERT IGNORE INTO $table_c (comment_id, karma) 
                  SELECT comment_ID, comment_karma FROM {$wpdb->comments} WHERE comment_approved = '1'");

    echo "Костя, база готова к 3 млн показов! Всё перелито.";
    exit;
});
