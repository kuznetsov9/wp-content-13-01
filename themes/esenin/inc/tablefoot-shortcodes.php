<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_shortcode('foot_table', function($atts) {
    $atts = shortcode_atts(['id' => '63'], $atts);
    $lid = $atts['id'];

    if ( isset($_GET['reset']) && $_GET['reset'] == $lid ) { 
        delete_option('bz_foot_cache_' . $lid); 
    }

    $all = get_option('bz_foot_cache_' . $lid);
    if ( ! $all ) {
        $all = bz_fetch_league_data($lid);
    }

    if ( ! $all ) return '<p style="color:red;">Данные лиги '.$lid.' не получены.</p>';

    $teams = $all['data']['table']['all'] ?? [];
    $forms = $all['teamForm'] ?? [];
    $nexts = $all['nextOpponent'] ?? [];
    
    // Берем маппинг из третьего файла
    $bz_map = bz_get_football_mapping($lid);

    ob_start(); ?>
    <div class="bz-ultimate-wrap bz-league-<?php echo $lid; ?>">
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
                        <th class="bz-c">ОЧК</th>
						<th class="bz-c">+/-</th>
                        <th class="bz-c">Форма</th>
                        <th class="bz-c">Далее</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( (array)$teams as $team ) : 
                        $tid = (string)$team['id'];
                        $t_form = $forms[$tid] ?? null;
                        $t_next = $nexts[$tid] ?? null;
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
                            <td class="bz-c bz-pts"><?php echo $team['pts']; ?></td>							
                            <td class="bz-c bz-m"><?php echo $team['scoresStr']; ?></td>
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
            Обновлено: <?php echo get_option('bz_foot_ts_' . $lid); ?>
        </div>
        <style>
            /* Сюда перенеси все стили из своего кода, они универсальные */
            .bz-ultimate-wrap { background: var(--es-layout-background); color: var(--es-color-primary, #fff); border-radius: 12px;}
            .bz-scroll { overflow-x: auto; }
            .bz-main-table { width: 100%; border-collapse: collapse; font-size: 13px; }
            .bz-main-table th { color: #666; font-size: 11px; padding: 10px; text-transform: uppercase; border: none; }
            .bz-main-table td { padding: 14px 3px; vertical-align: middle; border: none; }
            .bz-rel { position: relative; }
            .bz-q-line { position: absolute; left: 0; top: 20%; bottom: 20%; width: 3px; border-radius: 0 2px 2px 0; }
            .bz-t-cell { align-items: center; gap: 10px; text-align: left !important; min-width: 170px; }
			.bz-t-cell img   {margin-right: 6px;}
            .bz-t-link { text-decoration: none; color: inherit; transition: opacity 0.2s; }
            .bz-t-link:hover { opacity: 0.7; }
            .bz-c { text-align: center; }
            .bz-m { color: #777; font-weight: bold;    min-width: 67px; }
            .bz-pts { font-weight: bold; font-size: 15px; }
            .bz-f-dots { display: flex; gap: 4px; justify-content: center; }
            .bz-dot { width: 20px; height: 20px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; color: #fff; }
            .bz-dot-w { background: #26aa58; }
            .bz-dot-d { background: #666; }
            .bz-dot-l { background: #e93e3e; }
            .bz-footer-info { text-align: right; font-size: 10px; color: #555; margin-top: 10px; }
        </style>
    </div>
    <?php
    return ob_get_clean();
});