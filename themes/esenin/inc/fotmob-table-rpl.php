<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 1. НАСТРОЙКА ОБНОВЛЕНИЯ (КРОН)
add_filter('cron_schedules', function($schedules) {
    $schedules['every_four_hours'] = [
        'interval' => 14400,
        'display'  => 'Раз в 4 часа'
    ];
    return $schedules;
});

if ( ! wp_next_scheduled( 'bz_rpl_cron_worker' ) ) {
    wp_schedule_event( time(), 'every_four_hours', 'bz_rpl_cron_worker' );
}

add_action( 'bz_rpl_cron_worker', 'bz_fetch_rpl_ultimate' );


// 2. ФУНКЦИЯ ЗАГРУЗКИ ДАННЫХ
function bz_fetch_rpl_ultimate() {
    $url = "https://www.fotmob.com/api/data/tltable?leagueId=63";
    $response = wp_remote_get($url, [
        'timeout'    => 30,
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ]);
    if ( is_wp_error( $response ) ) return false;
    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );
    $root = isset($data[0]) ? $data[0] : $data;

    if ( $root ) {
        update_option('bz_rpl_final_cache', $root);
        update_option('bz_rpl_final_ts', current_time('mysql'));
        return $root;
    }
    return false;
}


// 3. ШОРТКОД [rpl_table]
add_shortcode('rpl_table', function() {
    if ( isset($_GET['reset']) ) { delete_option('bz_rpl_final_cache'); }

    $all = get_option('bz_rpl_final_cache');
    if ( ! $all ) {
        $all = bz_fetch_rpl_ultimate();
    }

    if ( ! $all ) return '<p style="color:red;">Данные не получены. Проверь интернет на сервере.</p>';

    $teams = $all['data']['table']['all'] ?? [];
    $forms = $all['teamForm'] ?? [];
    $nexts = $all['nextOpponent'] ?? [];

    // МЭППИНГ: ID команды => [Имя на русском, Ссылка]
    $bz_map = [
        '168719' => ['n' => 'Краснодар', 'u' => '/краснодар'],
        '8698'   => ['n' => 'Зенит', 'u' => '/зенит'],
        '8710'   => ['n' => 'Локомотив', 'u' => '/локомотив'],
        '9760'   => ['n' => 'ЦСКА', 'u' => '/цска'],
        '49694'  => ['n' => 'Балтика', 'u' => '/балтика'],
        '8643'   => ['n' => 'Спартак', 'u' => '/спартак'],
        '8683'   => ['n' => 'Рубин', 'u' => '/рубин'],
        '8708'   => ['n' => 'Ахмат', 'u' => '/ахмат'],
        '1068364'=> ['n' => 'Акрон', 'u' => '/акрон'],
        '9763'   => ['n' => 'Динамо М', 'u' => '/динамо-москва'],
        '8705'   => ['n' => 'Ростов', 'u' => '/ростов'],
        '8709'   => ['n' => 'Крылья Советов', 'u' => '/крылья-советов'],
        '1068353'=> ['n' => 'Динамо Мх', 'u' => '/динамо-махачкала'],
        '657508' => ['n' => 'Пари НН', 'u' => '/пари-нн'],
        '132286' => ['n' => 'Оренбург', 'u' => '/оренбург'],
        '195601' => ['n' => 'Сочи', 'u' => '/сочи'],
    ];

    ob_start(); 
    
    if ( isset($_GET['debug']) ) {
        echo '<div style="background:#fff; color:#000; padding:10px; font-size:11px; margin-bottom:10px;">';
        echo 'Команд: ' . count($teams) . ' | Форм: ' . count($forms) . ' | Соперников: ' . count($nexts);
        echo '</div>';
    }
    ?>

    <div class="bz-ultimate-wrap">
        <div class="bz-scroll">
            <table class="bz-main-table">
                <thead>
                    <tr>
                        <th class="bz-c">#</th>
                        <th style="text-align:left;">Команда</th>
                        <th class="bz-c">И</th>
                        <th class="bz-c">В</th>
                        <th class="bz-c">Н</th>
                        <th class="bz-c">П</th>
                        <th class="bz-c">+/-</th>
                        <th class="bz-c">=</th>
                        <th class="bz-c">ОЧК</th>
                        <th class="bz-c">Форма</th>
                        <th class="bz-c">Далее</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( (array)$teams as $team ) : 
                        $tid = (string)$team['id'];
                        $t_form = $forms[$tid] ?? null;
                        $t_next = $nexts[$tid] ?? null;

                        // Локализация имени и ссылки
                        $rus_name = isset($bz_map[$tid]) ? $bz_map[$tid]['n'] : $team['name'];
                        $slug = isset($bz_map[$tid]) ? $bz_map[$tid]['u'] : '#';
                    ?>
                        <tr>
                            <td class="bz-c bz-rel">
                                <?php if (!empty($team['qualColor'])) : ?>
                                    <span class="bz-q-line" style="background:<?php echo $team['qualColor']; ?>;"></span>
                                <?php endif; ?>
                                <span class="bz-m"><?php echo $team['idx']; ?></span>
                            </td>
                            <td class="bz-t-cell">
                                <img src="https://images.fotmob.com/image_resources/logo/teamlogo/<?php echo $tid; ?>.png" width="24" height="24">
                                <a href="<?php echo $slug; ?>" class="bz-t-link">
                                    <span class="bz-t-name"><?php echo esc_html($rus_name); ?></span>
                                </a>
                            </td>
                            <td class="bz-c"><?php echo $team['played']; ?></td>
                            <td class="bz-c"><?php echo $team['wins']; ?></td>
                            <td class="bz-c"><?php echo $team['draws']; ?></td>
                            <td class="bz-c"><?php echo $team['losses']; ?></td>
                            <td class="bz-c bz-m"><?php echo $team['scoresStr']; ?></td>
                            <td class="bz-c bz-m"><?php 
                                $diff = $team['goalConDiff'] ?? 0;
                                echo ($diff > 0 ? '+' : '') . $diff; 
                            ?></td>
                            <td class="bz-c bz-pts"><?php echo $team['pts']; ?></td>
                            
                            <td class="bz-c">
                                <div class="bz-f-dots">
                                    <?php 
                                    if ( is_array($t_form) ) {
                                        $slice = array_slice($t_form, 0, 5);
                                        foreach ($slice as $m) {
                                            $res = strtolower($m['resultString'] ?? 'd');
                                            $label = ($res == 'w') ? 'В' : (($res == 'd') ? 'Н' : 'П');
                                            echo "<span class='bz-dot bz-dot-{$res}'>{$label}</span>";
                                        }
                                    } else { echo '<span class="bz-m">—</span>'; }
                                    ?>
                                </div>
                            </td>

                            <td class="bz-c">
                                <?php if ( isset($t_next[0]) ) : ?>
                                    <img src="https://images.fotmob.com/image_resources/logo/teamlogo/<?php echo $t_next[0]; ?>.png" width="22" style="opacity:0.8">
                                <?php else: echo '<span class="bz-m">—</span>'; endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="bz-footer-info">
            Последнее обновление данных: <?php echo get_option('bz_rpl_final_ts'); ?>
        </div>
        
        <style>
            .bz-ultimate-wrap { background: var(--es-layout-background); color: var(--es-color-primary); border-radius: 12px; }
            .bz-scroll { overflow-x: auto; }
            .bz-main-table { width: 100%; border-collapse: collapse; font-size: 13px; }
            .bz-main-table th { color: #666; font-size: 11px; padding: 10px; text-transform: uppercase; border: none; }
            .bz-main-table td { padding: 14px 5px; vertical-align: middle; border: none; }
            .bz-rel { position: relative; }
            .bz-q-line { position: absolute; left: 0; top: 20%; bottom: 20%; width: 3px; border-radius: 0 2px 2px 0; }
            .bz-t-cell { display: flex; align-items: center; gap: 10px; text-align: left !important; min-width: 170px; }
            .bz-t-name { font-weight: 500; white-space: nowrap; }
            .bz-t-link { text-decoration: none; color: inherit; transition: opacity 0.2s; }
            .bz-t-link:hover { opacity: 0.7; }
            .bz-c { text-align: center; }
            .bz-m { color: #777; font-weight: bold; }
            .bz-pts { font-weight: bold; font-size: 15px; color: #fff; }
            .bz-f-dots { display: flex; gap: 4px; justify-content: center; }
            .bz-dot { width: 20px; height: 20px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; color: #fff; }
            .bz-dot-w { background: #26aa58; }
            .bz-dot-d { background: #666; }
            .bz-dot-l { background: #e93e3e; }
            .bz-footer-info { text-align: right; font-size: 10px; color: #555; margin-top: 10px; font-family: sans-serif; }
            .bz-ultimate-wrap table th, .bz-ultimate-wrap .wp-block-table th, .bz-ultimate-wrap table td, .bz-ultimate-wrap .wp-block-table td { border: none !important; }
        </style>
    </div>

    <?php
    return ob_get_clean();
});