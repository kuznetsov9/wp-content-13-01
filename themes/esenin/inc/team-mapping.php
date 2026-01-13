<?php
if (!defined('ABSPATH')) exit;

function bz_get_prepared_team_data($raw, $team_id) {
    if (empty($raw) || !is_array($raw)) return false;

    $id_str = (string)$team_id;
    $ov = $raw['overview'] ?? [];

    // 1. СТАДИОН
    $v = $ov['venue'] ?? $raw['venue'] ?? [];
    $stadium = ['name' => $v['widget']['name'] ?? 'Стадион', 'city' => $v['widget']['city'] ?? '', 'cap' => '—', 'year' => '—'];
    if (!empty($v['statPairs'])) {
        foreach ($v['statPairs'] as $pair) {
            if ($pair[0] === 'Capacity') $stadium['cap'] = number_format($pair[1], 0, '.', ' ');
            if ($pair[0] === 'Opened') $stadium['year'] = $pair[1];
        }
    }

    // 2. ФОРМА (ТВОЙ КОД: overview -> teamForm -> Прямой массив!)
    $form = [];
    $f_raw = $ov['teamForm'] ?? [];
    
    // Если вдруг FotMob решит вернуть объект с ID, проверяем
    if (isset($f_raw[$team_id])) {
        $f_raw = $f_raw[$team_id];
    }
    
    if (is_array($f_raw)) {
        foreach (array_slice($f_raw, -5) as $m) {
            preg_match('/teamlogo\/(\d+)/', $m['imageUrl'] ?? '', $matches);
            $form[] = [
                'res'    => $m['resultString'] ?? 'D',
                'score'  => $m['score'] ?? '0-0',
                'opp_id' => $matches[1] ?? '0'
            ];
        }
    }

    // 3. ТРЕНЕРЫ (root -> coachHistory)
    $coach_stats = [];
    // Пробуем взять из корня, если нет - из overview
    $c_hist = $raw['coachHistory'] ?? $ov['coachHistory'] ?? [];
    
    if (is_array($c_hist) && count($c_hist) > 0) {
        // Берем последние 6 записей
        $slice = array_slice($c_hist, -15); 
        foreach ($slice as $c) {
            $coach_stats[] = [
                'id'     => $c['id'],
                'name'   => $c['name'],
                'ppg'    => number_format($c['pointsPerGame'] ?? 0, 1),
                'win_p'  => round(($c['winPercentage'] ?? 0) * 100),
                'season' => $c['season'],
				'league' => $c['leagueName'] ?? 'Турнир', // Добавил лигу для тултипа
                'w'      => $c['win'] ?? 0, 
                'd'      => $c['draw'] ?? 0, 
                'l'      => $c['loss'] ?? 0
            ];
        }
    }
    // Текущий тренер - последний в массиве
    $current_coach = !empty($coach_stats) ? end($coach_stats) : null;

    // 4. БОМБАРДИРЫ (ТВОЙ КОД: stats -> players -> ищем 'goals')
    $scorers = [];
    $stats_groups = $raw['stats']['players'] ?? [];
    foreach ($stats_groups as $group) {
        if (isset($group['name']) && $group['name'] === 'goals' && !empty($group['topThree'])) {
            foreach ($group['topThree'] as $p) {
                $scorers[] = [
                    'id'    => $p['id'],
                    'name'  => $p['name'],
                    'goals' => $p['value']
                ];
            }
            break; // Нашли голы, выходим
        }
    }

    // 5. СОСТАВ (overview -> lastLineupStats)
    $lineup = [];
    $lineup_raw = $ov['lastLineupStats']['starters'] ?? [];
    foreach ($lineup_raw as $s) {
        $perf = $s['performance'] ?? [];
        $names = explode(' ', $s['name']);
        $lineup[] = [
            'id'      => $s['id'],
            'name'    => end($names),
            'rating'  => number_format($perf['seasonRating'] ?? 0, 1),
            'goals'   => $perf['seasonGoals'] ?? 0,
            'assists' => $perf['seasonAssists'] ?? 0,
            'x'       => $s['horizontalLayout']['x'] ?? 0,
            'y'       => $s['horizontalLayout']['y'] ?? 0
        ];
    }

    // 6. РАСПИСАНИЕ (fixtures -> allFixtures -> fixtures)
    $upcoming = [];
    // Пробуем взять самый полный список
    $raw_fixtures = $raw['fixtures']['allFixtures']['fixtures'] ?? $ov['overviewFixtures'] ?? [];
    $now = time();
    
    if (is_array($raw_fixtures)) {
        foreach ($raw_fixtures as $f) {
            $ts = strtotime($f['status']['utcTime'] ?? '');
            $is_cancelled = !empty($f['status']['cancelled']) || ($f['status']['reason']['longKey'] ?? '') === 'cancelled';
            
            // Фильтр: Будущее + Не отменен
            if ($ts > $now && !$is_cancelled) {
                
                // Определяем соперника (чтобы не показать "Краснодар vs Краснодар")
                $home_id = $f['home']['id'] ?? 0;
                $opp_id = ($home_id == $team_id) ? ($f['away']['id'] ?? 0) : $home_id;
                $opp_name = ($home_id == $team_id) ? ($f['away']['name'] ?? '') : ($f['home']['name'] ?? '');

                $upcoming[] = [
                    'opp'    => $opp_name,
                    'opp_id' => $opp_id,
                    'tour'   => $f['tournament']['name'] ?? '',
                    'ts'     => $ts,
                    'date'   => date('d.m H:i', $ts)
                ];
            }
        }
        usort($upcoming, function($a, $b) { return $a['ts'] <=> $b['ts']; });
    }

    return [
        'name'          => $raw['details']['name'] ?? 'Команда',
        'stadium'       => $stadium,
        'form'          => $form,
        'coach_history' => $coach_stats,
        'current_coach' => $current_coach,
        'scorers'       => $scorers,
        'lineup'        => $lineup,
        'upcoming'      => array_slice($upcoming, 0, 5)
    ];
}