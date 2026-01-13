<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 1. Регистрация интервала 13 часов
 */
add_filter('cron_schedules', function($schedules) {
    $schedules['every_thirteen_hours'] = [
        'interval' => 46800, // 13 часов в секундах
        'display'  => 'Раз в 13 часов (Божественное вмешательство)'
    ];
    return $schedules;
});

/**
 * 2. Функция получения данных (Таймаут 60 сек на борту)
 */
function bz_fetch_team_data($team_id) {
    $team_id = sanitize_text_field($team_id);
    $url = "https://www.fotmob.com/api/data/teams?id=" . $team_id;
    
    $args = [
        'timeout'     => 60,
        'user-agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'headers'     => [
            'Accept'            => 'application/json, text/plain, */*',
            'Accept-Language'   => 'ru-RU,ru;q=0.9',
            'Referer'           => 'https://www.fotmob.com/ru/teams/' . $team_id . '/overview/',
            'Origin'            => 'https://www.fotmob.com',
            'Cache-Control'     => 'no-cache',
        ]
    ];

    $response = wp_remote_get($url, $args);

    if ( is_wp_error( $response ) ) return false;

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($data && is_array($data)) {
        update_option('bz_team_raw_' . $team_id, $data);
        update_option('bz_team_last_update_' . $team_id, current_time('mysql'));
        return $data;
    }
    return false;
}

/**
 * 3. Регистрация крона (Теперь на 13 часов)
 */
add_action('wp', function() {
    $hook = 'bz_cron_fetch_all_teams_event';
    if ( ! wp_next_scheduled( $hook ) ) {
        // Юзаем новый слаг интервала
        wp_schedule_event( time(), 'every_thirteen_hours', $hook );
    }
});

/**
 * 4. Воркер: обновляет список РПЛ
 */
add_action('bz_cron_fetch_all_teams_event', function() {
    set_time_limit(300); // Страховка на 5 минут работы PHP

    $teams = [
        '168719', '8698', '9760', '8710', '8643', '49694', 
        '8683', '8708', '1068364', '9763', '8705', '8709', 
        '1068353', '657508', '132286', '195601',
    ];

    foreach ($teams as $id) {
        bz_fetch_team_data($id);
        sleep(3); // Короткий перекур между запросами
    }
});