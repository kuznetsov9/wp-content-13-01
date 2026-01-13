<?php
/**
 * We create system pages when activating the theme
 *
 * @package Esenin
 */

add_action('after_switch_theme', 'esn_create_pages');

function esn_create_pages() {
    $pages = [
       [
            'title' => __('Настройки профиля', 'esenin'),
            'slug' => 'edit-profile',
            'template' => 'pages/edit-profile.php'
        ],
        [
            'title' => __('Популярные статьи', 'esenin'),
            'slug' => 'popular',
            'template' => 'pages/popular-posts.php'
        ],
        [
            'title' => __('Пользователи', 'esenin'),
            'slug' => 'users',
            'template' => 'pages/all-users.php'
        ],
        [
            'title' => __('Темы сайта', 'esenin'),
            'slug' => 'themes',
            'template' => 'pages/themes.php'
        ],
        [
            'title' => __('Мои подписки', 'esenin'),
            'slug' => 'my-subscriptions',
            'template' => 'pages/my-subscriptions.php'
        ],
        [
            'title' => __('Моя лента', 'esenin'),
            'slug' => 'my',
            'template' => 'pages/my-feed.php'
        ],
        [
            'title' => __('Мои закладки', 'esenin'),
            'slug' => 'bookmarks',
            'template' => 'pages/my-bookmarks.php'
        ],
		[
            'title' => __('Мои уведомления', 'esenin'),
            'slug' => 'notifications',
            'template' => 'pages/notifications.php'
        ]
    ];

    foreach ($pages as $page) {
        if (!get_page_by_path($page['slug'], OBJECT, 'page')) {
            $new_page = array(
                'post_type' => 'page',
                'post_title' => $page['title'],
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_name' => $page['slug']
            );

            $new_page_id = wp_insert_post($new_page);
            if (!empty($page['template'])) {
                update_post_meta($new_page_id, '_wp_page_template', $page['template']);
            }
        }
    }
}


add_action('switch_theme', 'esn_delete_pages');

function esn_delete_pages() {
    $pages = [
        'edit-profile',
        'popular',
        'users',
        'themes',
        'my-subscriptions',
        'my',
        'bookmarks',
		'notifications'
    ];

    foreach ($pages as $slug) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if ($page) {
            wp_delete_post($page->ID, true); // true - удаляем навсегда
        }
    }
}