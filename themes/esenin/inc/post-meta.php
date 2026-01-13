<?php
/**
 * Post Meta Helper Functions & Popularity System for РФПЛ.рф
 */

// --- 1. ОРИГИНАЛЬНЫЕ МЕТА-ХЕЛПЕРЫ ТЕМЫ (ESENIN) ---
// Оставляем их максимально близко к оригиналу, чтобы не посыпался дизайн

if ( ! function_exists( 'esn_get_post_meta' ) ) {
    function esn_get_post_meta( $meta, $output = true, $allowed = null, $settings = array() ) {
        if ( ! $meta ) return;
        $meta = (array) $meta;
        $settings = array_merge(array('category_type' => 'default', 'author_avatar' => false), $settings);

        if ( is_string( $allowed ) || true === $allowed ) {
            $option_default = null;
            $option_name = is_string( $allowed ) ? $allowed : esn_get_archive_option( 'post_meta' );
            if ( isset( ESN_Customizer::$fields[ $option_name ]['default'] ) ) {
                $option_default = ESN_Customizer::$fields[ $option_name ]['default'];
            }
            $allowed = get_theme_mod( $option_name, $option_default );
        }

        if ( ! is_array( $allowed ) && ! $allowed ) {
            $allowed = apply_filters( 'esn_post_meta', array( 'category', 'author', 'comments', 'date', 'reading_time' ) );
        }

        if ( is_array( $meta ) ) { $meta = array_intersect( $meta, $allowed ); }
        $markup = '';

        if ( is_array( $meta ) && $meta ) {
            foreach ( $meta as $type ) {
                $func = "esn_get_meta_$type";
                if (function_exists($func)) $markup .= call_user_func( $func, 'div', $settings );
            }
            $scheme = apply_filters( 'esn_post_meta_scheme', null, $settings );
            $markup = sprintf( '<div class="es-entry__post-meta" %s>%s</div>', $scheme, $markup );
        } elseif ( in_array( $meta, $allowed, true ) ) {
            $func = "esn_get_meta_$meta";
            if (function_exists($func)) $markup .= call_user_func( $func, 'div', $settings );
        }

        if ( $output ) { return printf( '%s', $markup ); }
        return $markup;
    }
}

// Стандартные функции вывода (категории, даты, авторы, комменты)
function esn_get_meta_category( $tag = 'div', $settings = array() ) {
    $class = ( 'line' === $settings['category_type'] ) ? 'es-entry__category' : 'es-meta-category';
    return '<' . esc_html( $tag ) . ' class="' . $class . '">' . get_the_category_list( '', '', get_the_ID() ) . '</' . esc_html( $tag ) . '>';
}

function esn_get_meta_date( $tag = 'div', $settings = array() ) {
    $output = '<' . esc_html( $tag ) . ' class="es-meta-date"><span class="es-meta-date-on">' . esc_html__( 'on', 'esenin' ) . '</span>';
    $time_string = get_the_date();
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) && !get_theme_mod( 'misc_published_date', true ) ) {
        $time_string = get_the_modified_date();
    }
    $output .= apply_filters( 'esn_post_meta_date_output', $time_string ) . '</' . esc_html( $tag ) . '>';
    return $output;
}

function esn_get_meta_author( $tag = 'div', $settings = array() ) {
    $id = get_the_author_meta( 'ID' );
    $avatar = ( is_single() && isset( $settings['author_avatar'] ) && $settings['author_avatar'] ) ? get_avatar( $id, 36 ) : null;
    $output = '<' . esc_attr( $tag ) . ' class="es-meta-author"><a class="es-meta-author-link url fn n" href="' . get_author_posts_url( $id ) . '">';
    if ( $avatar ) $output .= '<picture class="es-meta-author-avatar">' . $avatar . '</picture>';
    $output .= '<span class="es-meta-author-name">' . get_the_author_meta( 'display_name', $id ) . '</span></a></' . esc_html( $tag ) . '>';
    return $output;
}

function esn_get_meta_comments( $tag = 'div', $settings = array() ) {
    if ( ! comments_open( get_the_ID() ) ) return;
    $output = '<' . esc_html( $tag ) . ' class="es-meta-comments">';
    ob_start(); comments_popup_link( '0', '1', '%', 'comments-link', '' );
    $output .= ob_get_clean() . '</' . esc_html( $tag ) . '>';
    return $output;
}

// --- 2. СИСТЕМА ПРОСМОТРОВ ---

function getPostViews($postID) {
    global $wpdb;
    
    // 1. Пытаемся взять число из нашей новой быстрой таблицы
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT views_count FROM {$wpdb->prefix}post_stats WHERE post_id = %d", 
        $postID
    ));

    // 2. Если в новой таблице записи еще нет (бывает со старыми постами), 
    // берем из мета-данных как запасной вариант
    if ($count === null) {
        $count = (int) get_post_meta($postID, 'post_views_count', true);
    }

    $count = (int)$count;

    if ($count === 0) return "0";

    // 3. Форматируем число (К - для тысяч)
    if ($count >= 1000) {
        $formatted = number_format($count / 1000, 1, '.', '');
        return (substr($formatted, -2) == '.0' ? substr($formatted, 0, -2) : $formatted) . 'K';
    }

    return $count;
}

function setPostViews($postID) {
    $count_key = "post_views_count";
    $count = get_post_meta($postID, $count_key, true);
    if ($count == "") {
        update_post_meta($postID, $count_key, "1");
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
    
    // ДОБАВЬ ЭТО:
    if (function_exists('rpl_update_post_stats_flat')) {
        rpl_update_post_stats_flat($postID);
    }
}

add_filter("manage_posts_columns", function($defaults) { $defaults["post_views"] = 'Просмотры'; return $defaults; });
add_action("manage_posts_custom_column", function($column_name, $id) {
    if($column_name === "post_views") echo getPostViews($id);
}, 5, 2);

// --- 3. КРОН И РАСЧЕТ ПОПУЛЯРНОСТИ (10 МИНУТ) ---

add_filter( 'cron_schedules', function( $schedules ) {
    $schedules['ten_minutes'] = array('interval' => 600, 'display' => 'Every 10 Minutes');
    return $schedules;
});

add_action('wp', function() {
    if( ! wp_next_scheduled( 'esn_ten_minutes_event' ) ) {
        wp_schedule_event( time(), 'ten_minutes', 'esn_ten_minutes_event');
    }
});

add_action('esn_ten_minutes_event', 'esn_recalculate_popularity_hardcore');
function esn_recalculate_popularity_hardcore() {
    global $wpdb;
    $table = $wpdb->prefix . 'post_stats';
    $posts = $wpdb->posts;
    $now = time();

    // 1. Синхронизируем комменты (база берет их из wp_posts одним ударом)
    $wpdb->query("UPDATE $table s INNER JOIN $posts p ON s.post_id = p.ID SET s.comment_count = p.comment_count");

    // 2. СУТКИ (Алгоритм с затуханием: (L+C) / SQRT(часы+12))
    // Обновляем только посты за последние 24 часа для экономии сил
    $wpdb->query("
    UPDATE $table 
    SET pop_day = (likes_count + comment_count) / SQRT(
        (GREATEST(0, CAST($now AS SIGNED) - CAST(post_date_ts AS SIGNED)) / 3600) + 12
    )
    WHERE post_date_ts > ($now - 86400)
");

    // 3. НЕДЕЛЯ, МЕСЯЦ, ГОД (Считаем по твоей логике: просто сумма)
    $wpdb->query("UPDATE $table SET pop_week = (likes_count + comment_count) WHERE post_date_ts > ($now - 604800)");
    $wpdb->query("UPDATE $table SET pop_month = (likes_count + comment_count) WHERE post_date_ts > ($now - 2678400)");
    $wpdb->query("UPDATE $table SET pop_year = (likes_count + comment_count) WHERE post_date_ts > ($now - 31536000)");

    // 4. ВЕЧНОСТЬ (Все посты, которые живут дольше суток)
    $wpdb->query("UPDATE $table SET pop_all = (likes_count + comment_count) WHERE post_date_ts < ($now - 86400)");
}

// Чтобы новые посты не пропадали из фильтров
add_action('publish_post', function($ID) {
    if (get_post_meta($ID, 'popularity', true) === '') update_post_meta($ID, 'popularity', 0);
});

// --- 4. ТА САМАЯ ФУНКЦИЯ ДЛЯ ВАШЕГО ШАБЛОНА popular-posts.php ---

function get_popular_posts($timeframe = 'week', $paged = 1) {
    global $wpdb;
    $per_page = (int) get_option('posts_per_page') ?: 10;
    $offset = ($paged - 1) * $per_page;
    
    // 1. Настройка таймфрейма и ЖЕСТКОЙ отсечки по дате
    $date_limit = "";
    switch ($timeframe) {
        case 'today':    
            $sort_col = 'pop_day'; 
            $date_limit = "AND p.post_date > NOW() - INTERVAL 24 HOUR";
            break;
        case 'week':     
            $sort_col = 'pop_week'; 
            $date_limit = "AND p.post_date > NOW() - INTERVAL 7 DAY";
            break;
        case 'month':    
            $sort_col = 'pop_month'; 
            $date_limit = "AND p.post_date > NOW() - INTERVAL 31 DAY";
            break;
        case 'year':     
            $sort_col = 'pop_year'; 
            $date_limit = "AND p.post_date > NOW() - INTERVAL 365 DAY";
            break;
        case 'all_time': 
            $sort_col = 'pop_all'; 
            $date_limit = "AND p.post_date < NOW() - INTERVAL 1 DAY"; // Твое условие: жизнь > 1 дня
            break;
        default:         
            $sort_col = 'pop_day';
            $date_limit = "AND p.post_date > NOW() - INTERVAL 24 HOUR";
    }

    // 2. SQL запрос с вторичной сортировкой по дате
    $sql = $wpdb->prepare("
        SELECT SQL_CALC_FOUND_ROWS p.ID 
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->prefix}post_stats s ON p.ID = s.post_id
        WHERE p.post_type = 'post' 
          AND p.post_status = 'publish'
          $date_limit
        ORDER BY s.{$sort_col} DESC, p.post_date DESC
        LIMIT %d, %d
    ", $offset, $per_page);

    $post_ids = $wpdb->get_col($sql);
    $total_posts = (int) $wpdb->get_var("SELECT FOUND_ROWS()");

    if (empty($post_ids)) {
        return new WP_Query(array('post__in' => array(0)));
    }

    $query = new WP_Query(array(
        'post__in'       => $post_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => $per_page,
        'no_found_rows'  => true,
    ));

    $query->found_posts = $total_posts;
    $query->max_num_pages = ceil($total_posts / $per_page);
    
    return $query;
}

// --- 5. СИСТЕМНЫЕ КОРРЕКТИРОВКИ ЗАПРОСОВ ---

add_action('pre_get_posts', 'esn_modify_query');
function esn_modify_query($query) {
    if (!is_admin() && $query->is_main_query() && (is_author() || is_category() || is_archive() || is_tag())) {
        $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : '';
        
        // Логика для кнопки "Свежее"
        if ($sort === 'new') {
            $query->set('orderby', 'date');
            $query->set('order', 'DESC');
            return; // Уходим, тут Flat-таблица не нужна
        }

        // Логика для популярных фильтров (используем нашу Flat-таблицу)
        if (in_array($sort, ['today', 'week', 'month', 'year', 'popular'])) {
            $query->set('orderby', 'rpl_flat_popularity'); 
            $query->set('order', 'DESC');
        }
    }
}

// Тот самый фильтр для JOIN-а плоской таблицы в категориях
add_filter('posts_clauses', function($clauses, $query) {
    if ($query->get('orderby') === 'rpl_flat_popularity') {
        global $wpdb;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'week';
        
        // Определяем колонку и дату отсечки
        $col = 'pop_week'; 
        $interval = "7 DAY";
        
        if ($sort == 'today')   { $col = 'pop_day';   $interval = "24 HOUR"; }
        if ($sort == 'month')   { $col = 'pop_month'; $interval = "31 DAY"; }
        if ($sort == 'year')    { $col = 'pop_year';  $interval = "365 DAY"; }
        if ($sort == 'popular') { $col = 'pop_all';   $interval = "99 YEAR"; }

        $table_s = $wpdb->prefix . 'post_stats';
        $clauses['join'] .= " INNER JOIN $table_s AS s ON {$wpdb->posts}.ID = s.post_id ";
        
        // Добавляем отсечку прямо в WHERE архива
        $clauses['where'] .= " AND {$wpdb->posts}.post_date > NOW() - INTERVAL $interval ";
        
        // Сортируем: Популярность -> Дата
        $clauses['orderby'] = " s.$col DESC, {$wpdb->posts}.post_date DESC ";
    }
    return $clauses;
}, 10, 2);

// --- 6. ЧТЕНИЕ И ВСПОМОГАТЕЛЬНЫЕ ШТУКИ ---

function get_months_ru($month_num) {
    $months = [1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'];
    return $months[$month_num] ?? null;
}

if ( ! function_exists( 'esn_get_meta_reading_time' ) ) {
    function esn_get_meta_reading_time( $tag = 'div', $compact = false ) {
        $reading_time = esn_get_post_reading_time(); 
        $output = '<' . esc_html( $tag ) . ' class="es-meta-reading-time">';
        if ( $compact ) { $output .= intval( $reading_time ) . ' мин';
        } else {
            $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" style="vertical-align:middle;margin-right:5px;"><path d="m22,12c0-3.265-1.573-6.169-4-7.995V1c0-.552-.448-1-1-1s-1,.448-1,1v1.836c-1.226-.537-2.578-.836-4-.836s-2.774.299-4,.836v-1.836c0-.552-.448-1-1-1s-1,.448-1,1v3.005c-2.427,1.826-4,4.73-4,7.995s1.573,6.169,4,7.995v3.005c0,.553.448,1,1,1s1-.447,1-1v-1.836c1.226.537,2.578.836,4,.836s2.774-.299,4-.836v1.836c0,.553.448,1,1,1s1-.447,1-1v-3.005c2.427-1.826,4-4.73,4-7.995Zm-18,0c0-4.411,3.589-8,8-8s8,3.589,8,8-3.589,8-8,8-8-3.589-8-8Zm10.707,1.293c.391.391.391,1.023,0,1.414-.195.195-.451.293-.707.293s-.512-.098-.707-.293l-2-2c-.188-.188-.293-.442-.293-.707v-4c0-.552.448-1,1-1s1,.448,1,1v3.586l1.707,1.707Z"/></svg>';
            $output .= $reading_time . ' мин. чтения';
        }
        return $output . '</' . esc_html( $tag ) . '>';
    }
}