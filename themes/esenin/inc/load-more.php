<?php
/**
 * Load More Posts via WP REST API.
 * Фикс для страницы "Популярное": убираем дубли и включаем LazyLoad
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 1. Локализация скриптов темы
 */
function esn_load_more_js() {
    global $wp_query;

    $is_popular_page = is_page_template('pages/popular-posts.php');
    $current_query = $wp_query;

    if ( $is_popular_page ) {
        $timeframe = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'week';
        $current_query = get_popular_posts($timeframe, 1);
    }

    if ( $current_query->max_num_pages <= 1 ) return;

    $pagination_type = get_theme_mod( esn_get_archive_option( 'pagination_type' ), 'load-more' );

    $args = array(
        'root'           => esc_url_raw( rest_url() ),
        'nonce'          => wp_create_nonce( 'wp_rest' ),
        'type'           => 'ajax_restapi',
        'page'           => 2,
        'posts_per_page' => (int) get_option( 'posts_per_page' ) ?: 10,
        'is_popular'     => $is_popular_page,
        'timeframe'      => $is_popular_page ? (isset($_GET['sort']) ? $_GET['sort'] : 'week') : '',
        'query_data'     => array( 'query_vars' => $wp_query->query_vars ),
        'options'        => esn_get_archive_options(),
        'infinite_load'  => ( 'infinite' === $pagination_type ) ? 'true' : 'false',
        'translation'    => array(
            'load_more' => esc_html__( 'Показать еще', 'esenin' ),
            'loading'   => esc_html__( 'Загрузка...', 'esenin' ),
        ),
    );

    wp_localize_script( 'esn-scripts', 'esn_ajax_pagination', $args );
}
add_action( 'wp_enqueue_scripts', 'esn_load_more_js', 110 );

/**
 * 2. Регистрация маршрута
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'esn/v1', '/more-posts', array(
        'methods'             => 'POST',
        'callback'            => 'esn_rest_load_more_handler',
        'permission_callback' => '__return_true',
    ) );
} );

/**
 * 3. Обработчик REST API
 */
function esn_rest_load_more_handler( $request ) {
    $params = $request->get_json_params();
    
    $page = isset( $params['page'] ) ? absint( $params['page'] ) : 2;
    $is_popular = !empty($params['is_popular']);
    $timeframe = isset($params['timeframe']) ? sanitize_text_field($params['timeframe']) : 'week';
    $options = isset($params['options']) ? $params['options'] : array();

    if ( $is_popular && function_exists('get_popular_posts') ) {
        $the_query = get_popular_posts($timeframe, $page);
    } else {
        $query_data = $params['query_data'];
        $query_vars = array_merge( (array) $query_data['query_vars'], array(
            'post_status' => 'publish',
            'paged'       => $page,
        ));
        $the_query = new WP_Query( $query_vars );
    }

    $content = '';
    $posts_end = true;

    if ( $the_query->have_posts() ) {
        $user_id = get_current_user_id();
        $is_logged = is_user_logged_in();
        global $wpdb;
        
        $liked = $is_logged ? $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$wpdb->prefix}likes WHERE user_id = %d", $user_id)) : [];
        $bookmarked = $is_logged ? $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$wpdb->prefix}bookmarks WHERE user_id = %d", $user_id)) : [];

        ob_start();
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            set_query_var( 'is_user_logged_in', $is_logged );
            set_query_var( 'liked', in_array(get_the_ID(), $liked) );
            set_query_var( 'bookmarked', in_array(get_the_ID(), $bookmarked) );
            set_query_var( 'options', $options );

            $layout = $options['layout'] ?? 'standard';
            if ( 'full' === $layout ) get_template_part( 'template-parts/archive/content-full' );
            elseif ( 'overlay' === $layout ) get_template_part( 'template-parts/archive/entry-overlay' );
            else get_template_part( 'template-parts/archive/entry' );
        }
        $content = ob_get_clean();
        
        if ( $the_query->max_num_pages > $page ) {
            $posts_end = false;
        }
    }
    wp_reset_postdata();

    return array(
        'success' => true,
        'data'    => array(
            'content'   => $content,
            'posts_end' => $posts_end,
        ),
    );
}

/**
 * Вспомогательная функция для передачи данных в data-атрибут контейнера
 */
function esn_get_popular_archive_data($query, $timeframe) {
    // Принудительно ставим infinite_load = true, чтобы LazyLoad работал всегда на этой странице
    $args = array(
        'root'           => esc_url_raw( rest_url() ),
        'nonce'          => wp_create_nonce( 'wp_rest' ),
        'type'           => 'ajax_restapi',
        'is_popular'     => true,
        'timeframe'      => $timeframe,
        'posts_per_page' => (int) get_option( 'posts_per_page' ) ?: 10,
        'options'        => esn_get_archive_options(),
        'infinite_load'  => 'true', // ВСЕГДА TRUE для автоподгрузки
        'translation'    => array(
            'load_more' => __( 'Показать еще', 'esenin' ),
            'loading'   => __( 'Загрузка...', 'esenin' ),
        ),
    );
    return base64_encode(json_encode($args));
}