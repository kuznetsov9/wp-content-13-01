<?php
if (!defined('ABSPATH')) exit;

/**
 * 1. Ловим момент публикации и планируем "отложенный" репост через Cron
 */
add_action('transition_post_status', 'vk_exporter_schedule_cron', 10, 3);

function vk_exporter_schedule_cron($new_status, $old_status, $post) {
    if ($new_status === 'publish' && $old_status !== 'publish' && $post->post_type === 'post') {
        // Планируем экспорт через 120 секунд (хватит даже самому медленному парсеру)
        wp_schedule_single_event(time() + 60, 'vk_exporter_delayed_action', array($post->ID));
        error_log("--- VK EXPORT: Запланирован репост для ID {$post->ID} на через 2 минуты ---");
    }
}

/**
 * 2. Регистрируем само событие крона
 */
add_action('vk_exporter_delayed_action', 'vk_exporter_send_to_wall');

function vk_exporter_send_to_wall($post_id) {
    // Проверка на дубли (на всякий случай)
    if (get_post_meta($post_id, '_vk_exported', true)) return;

    // --- НАСТРОЙКИ (Твой токен Kate Mobile) ---
	$access_token = 'vk1.a.EaRZ0sMDCiID1MKtMoq6NmkYwnBvljSuaNeWbeTog9ah3pK9LQ8MhSqdzajesBAIY5VgdBUGpBEjBh7l3NBfHuWxmY0RBSjUhsS8JqgwAULHdTLRmoD3Sq2bJp6y7ip9wn4aY434DoZU7OOc-vFhLMPwHzZX_ybu7_wM_NEZxJ_o7BuVOlVIwwr9x-ftK46ZkvcS4glpkVBoRRdblvM03w';
    $owner_id     = '-235313377'; 
    $group_id     = '235313377'; 
    $api_v        = '5.199';
    // ------------------------------------------

    $post = get_post($post_id);
    if (!$post) return;

    error_log("--- VK EXPORT CRON: Обработка поста {$post_id} началась ---");

    // ВЕРСТКА
    $title = html_entity_decode($post->post_title, ENT_QUOTES, 'UTF-8');
    $content = preg_replace('/<\/(p|h[1-6]|div|figure|figcaption)>/i', "\n\n", $post->post_content);
    $content = strip_tags($content);
    $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
    $content = str_replace(array('&nbsp;', '&hellip;', '…'), array(' ', '', ''), $content);
    $content = trim(preg_replace("/\n{3,}/", "\n\n", $content)); 

    $link = urldecode(get_permalink($post_id));
    $link_readable = str_replace('xn--k1ahcq.xn--p1ai', 'рфпл.рф', $link);
    $message = $title . "\n\n" . $content . "\n\n" . "Комментарии: " . $link_readable;

    // ФОТО: Ищем картинку (теперь она точно должна быть в базе)
    $vk_attachments = [];
    $thumb_id = get_post_thumbnail_id($post_id);

    // Если миниатюры всё еще нет — пробуем выцепить из текста
    if (!$thumb_id) {
        if (preg_match('/<img.+?src=["\'](.+?)["\'].*?>/i', $post->post_content, $matches)) {
            error_log("VK Debug Cron: Миниатюры нет, беру первое фото из текста: " . $matches[1]);
            // (Логика загрузки через URL была в прошлых версиях, если надо — допишем, но обычно Featured Image уже на месте)
        }
    }

    if ($thumb_id) {
        $upload_path = get_attached_file($thumb_id);
        if ($upload_path && file_exists($upload_path)) {
            $server_res = wp_remote_get("https://api.vk.com/method/photos.getWallUploadServer?group_id=$group_id&access_token=$access_token&v=$api_v");
            $server_data = json_decode(wp_remote_retrieve_body($server_res), true);

            if (isset($server_data['response']['upload_url'])) {
                $ch = curl_init($server_data['response']['upload_url']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ['photo' => new CURLFile($upload_path)]);
                $upload_res = curl_exec($ch);
                curl_close($ch);

                $upload_data = json_decode($upload_res, true);
                if (!empty($upload_data['photo'])) {
                    $save_res = wp_remote_post("https://api.vk.com/method/photos.saveWallPhoto", [
                        'body' => [
                            'group_id' => $group_id,
                            'server' => $upload_data['server'],
                            'photo' => $upload_data['photo'],
                            'hash' => $upload_data['hash'],
                            'access_token' => $access_token,
                            'v' => $api_v
                        ]
                    ]);
                    $saved = json_decode(wp_remote_retrieve_body($save_res), true);
                    if (isset($saved['response'][0]['id'])) {
                        $vk_attachments[] = "photo" . $saved['response'][0]['owner_id'] . "_" . $saved['response'][0]['id'];
                        error_log("VK Debug Cron: Картинка успешно загружена.");
                    }
                }
            }
        }
    }

    // ПУБЛИКАЦИЯ
    $params = [
        'owner_id'     => $owner_id,
        'from_group'   => 1,
        'message'      => $message,
        'attachments'  => implode(',', $vk_attachments),
        'access_token' => $access_token,
        'v'            => $api_v,
    ];

    $response = wp_remote_post('https://api.vk.com/method/wall.post', ['body' => $params]);
    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['response']['post_id'])) {
        update_post_meta($post_id, '_vk_exported', time());
        error_log("VK Debug Cron: Пост в ВК опубликован! ID: " . $body['response']['post_id']);
    } else {
        error_log('VK Debug Cron: Ошибка wall.post: ' . json_encode($body, JSON_UNESCAPED_UNICODE));
    }
}