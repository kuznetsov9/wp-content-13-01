<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 1. Интервал (оставляем как есть)
add_filter('cron_schedules', function($schedules) {
    $schedules['every_eleven_hours'] = ['interval' => 41122, 'display' => 'Раз в 11 часов'];
    return $schedules;
});

// 2. Улучшенная функция загрузки (копируем логику заголовков из team-core)
function bz_fetch_league_data($league_id) {
    $url = "https://www.fotmob.com/api/data/tltable?leagueId=" . $league_id;
    
    $args = [
        'timeout'    => 30,
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'headers'    => [
            'Accept'          => 'application/json',
            'Accept-Language' => 'ru-RU,ru;q=0.9',
            'Referer'         => 'https://www.fotmob.com/leagues/' . $league_id,
            'Origin'          => 'https://www.fotmob.com',
        ]
    ];

    $response = wp_remote_get($url, $args);
    if ( is_wp_error( $response ) ) return false;

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );
    
    // У FotMob структура может быть разной, берем корень или первый элемент
    $root = isset($data[0]) ? $data[0] : $data;

    if ( !empty($root) ) {
        update_option('bz_foot_cache_' . $league_id, $root);
        update_option('bz_foot_ts_' . $league_id, current_time('mysql'));
        return $root;
    }
    return false;
}

// 3. ПРАВИЛЬНАЯ РЕГИСТРАЦИЯ ОБРАБОТЧИКОВ (Глобально!)
$active_leagues = ['63', '338', '193']; 

foreach ($active_leagues as $id) {
    $hook = 'bz_cron_fetch_league_' . $id;
    
    // Привязываем функцию к хуку ГЛОБАЛЬНО, а не внутри add_action('wp')
add_action($hook, function() use ($id) {
        set_time_limit(120); // На всякий случай даем 2 минуты
        bz_fetch_league_data($id);
        // Небольшой сон, чтобы не частить запросами к API
        sleep(2); 
    });
}

// 4. Планирование задач (теперь только проверка расписания)
add_action('wp', function() {
    $active_leagues = ['63', '338', '193'];
    foreach ($active_leagues as $id) {
        $hook = 'bz_cron_fetch_league_' . $id;
        if ( ! wp_next_scheduled( $hook ) ) {
            wp_schedule_event( time(), 'every_eleven_hours', $hook );
        }
    }
});