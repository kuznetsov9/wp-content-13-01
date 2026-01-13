<?php
/**
 * All core theme actions.
 *
 * Please do not modify this file directly.
 * You may remove actions in your child theme by using remove_action().
 *
 * Please see /inc/partials.php for the list of partials,
 * added to actions.
 *
 * @package Esenin
 */

/**
 * Body
 */

add_action( 'esn_site_before', 'esn_offcanvas' );
add_action( 'esn_main_content_before', 'esn_theme_breadcrumbs', 100 );

/**
 * Main
 */
add_action( 'esn_main_content_before', 'esn_page_header', 100 );

/**
 * Singular
 */
add_action( 'esn_entry_content_before', 'esn_singular_post_type_before', 10 );
add_action( 'esn_entry_content_after', 'esn_singular_post_type_after', 999 );


/**
 * Home Hero
 */
add_action( 'esn_main_content_before', 'esn_home_hero_standard', 110 );
add_action( 'esn_site_content_before', 'esn_home_hero_fullwidth', 10 );

/**
 * Entry Header
 */
add_action( 'esn_main_content_before', 'esn_entry_header', 110 );

/**
 * Entry Sections
 */
add_action( 'esn_entry_content_after', 'esn_page_pagination', 10 );
add_action( 'esn_entry_content_after', 'esn_entry_tags', 20 );
add_action( 'esn_entry_content_after', 'esn_entry_footer', 30 );
add_action( 'esn_entry_content_after', 'esn_entry_prev_next', 40 );
add_action( 'esn_entry_content_after', 'esn_entry_comments', 50 );
add_action( 'esn_footer_before', 'esn_entry_read_next', 20 );



/**
 * Entry Elements
 */
add_action( 'esn_entry_container_start', 'esn_entry_metabar', 10 );

/**
 * Home Post Categories
 */
add_action( 'esn_main_content_before', 'esn_post_categories', 120 );

/**
 * Subscribe
 */
add_action( 'esn_footer_before', 'esn_misc_subscribe', 30 );

/**
 * Обработка одобрения поста модератором (с фронтенда)
 */
add_action( 'admin_post_approve_post', function() {
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    
    // Проверка прав: должен уметь редактировать чужие посты
    if ( ! $post_id || ! current_user_can( 'edit_others_posts' ) ) {
        wp_die('У вас недостаточно прав для этого действия или ID поста неверный.');
    }
    
    // Проверка безопасности (нонс)
    check_admin_referer( 'approve_post_' . $post_id );

    // Обновляем статус на "Опубликовано"
    wp_update_post([
        'ID'          => $post_id,
        'post_status' => 'publish'
    ]);

    // Возвращаем модератора туда, откуда он пришел
    wp_redirect( wp_get_referer() ? wp_get_referer() : home_url() );
    exit;
});

/**
 * Обработка удаления (отклонения) поста в корзину
 */
add_action( 'admin_post_reject_post', function() {
    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    
    if ( ! $post_id || ! current_user_can( 'edit_others_posts' ) ) {
        wp_die('У вас недостаточно прав или ID поста неверный.');
    }
    
    check_admin_referer( 'reject_post_' . $post_id );

    // Отправляем в корзину (не удаляем насовсем, чтобы можно было восстановить если что)
    wp_trash_post( $post_id );

    wp_redirect( wp_get_referer() ? wp_get_referer() : home_url() );
    exit;
});

/**
 * Глобальная зачистка: сносим всё мясо, привязанное к посту (включая Thumbnail)
 */
add_action( 'before_delete_post', 'esenin_permanent_delete_post_assets', 10 );

function esenin_permanent_delete_post_assets( $post_id ) {
    // 1. Работаем только с обычными постами
    if ( get_post_type( $post_id ) !== 'post' ) {
        return;
    }

    $to_delete = array();

    // 2. Выцепляем ID миниатюры (Thumbnail)
    $thumb_id = get_post_thumbnail_id( $post_id );
    if ( $thumb_id ) {
        $to_delete[] = $thumb_id;
    }

    // 3. Ищем все вложения, где этот пост указан как родитель (стандартная медиатека)
    $attached_media = get_posts( array(
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
        'post_parent'    => $post_id,
        'fields'         => 'ids',
    ) );

    if ( ! empty( $attached_media ) ) {
        $to_delete = array_merge( $to_delete, $attached_media );
    }

    // 4. Чистим список от дублей (если миниатюра и так была "ребенком")
    $to_delete = array_unique( $to_delete );

    // 5. Понеслась зачистка
    foreach ( $to_delete as $attachment_id ) {
        // Проверяем на всякий случай, что это реально вложение
        if ( get_post_type( $attachment_id ) === 'attachment' ) {
            // true — удаляем файл с сервера навсегда
            wp_delete_attachment( $attachment_id, true );
        }
    }
}