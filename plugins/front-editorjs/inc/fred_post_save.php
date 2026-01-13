<?php 

/**

 * Save post 

 */



if (!defined('ABSPATH')) exit;



function fred_prepare_post_for_db(){

    $post_data = array(   

        'post_modified' => current_time('mysql'), // Используем current_time для правильного таймзона

        'comment_status' => (isset($_POST['comments_disable']) && $_POST['comments_disable'] == '1') ? 'closed' : 'open',

    );



    // Определяем статус поста в зависимости от роли пользователя

    if (current_user_can('administrator') || current_user_can('editor') || current_user_can('contributor') || current_user_can('author')) {

        $post_data['post_status'] = 'publish'; // Публикуем пост для администраторов и редакторов

        $post_data['post_date'] = current_time('mysql'); // Устанавливаем дату создания поста

    } else {

        $post_data['post_status'] = 'pending'; // Публикуем на модерацию для авторов и подписчиков

        $post_data['post_date'] = current_time('mysql'); // Устанавливаем текущую дату для распоряжений

    }



    // Настройки ID поста

    if(isset($_POST['post_id']) && $_POST['post_id'] > 0){

        $post_data['ID'] = sanitize_key($_POST['post_id']);



        // Устанавливаем время изменения, если пост редактируется

        $post_data['post_modified'] = current_time('mysql');

        // Сохраняем оригинальную дату, если редактирование поста

        $post_data['post_date'] = get_the_date('Y-m-d H:i:s', $_POST['post_id']);

    }



    // Настройка автора поста

    if (isset($_POST['post_author'])){

        $post_data['post_author'] = sanitize_text_field($_POST['post_author']);

    }

    // Настройка заголовка поста

    if (isset($_POST['post_title'])){

        $post_data['post_title'] = sanitize_text_field($_POST['post_title']);

    }

    // Настройка категории поста

    if (isset($_POST['post_category'])){

        $post_data['post_category'] = array(sanitize_key($_POST['post_category']));

    }

// --- ГЛАВНАЯ ЗАЩИТА КОНТЕНТА (FIX XSS) ---
    if (isset($_POST['post_content'])) {
        // 1. Снимаем экранирование WordPress, чтобы получить чистый HTML
        $content_raw = wp_unslash($_POST['post_content']);

        // 2. Определяем БЕЛЫЙ СПИСОК тегов. Всё, чего тут нет — будет удалено.
        $allowed_html = array(
            'p'      => array('class' => true, 'style' => true),
            'a'      => array(
                'href'   => true, 
                'title'  => true, 
                'target' => true, 
                'rel'    => true,
                'class'  => true
            ),
            'b'      => array(),
            'strong' => array(),
            'i'      => array(),
            'em'     => array(),
            'u'      => array(),
            's'      => array(), // зачеркнутый
            'ul'     => array('class' => true),
            'ol'     => array('class' => true),
            'li'     => array('class' => true),
            'br'     => array(),
            'hr'     => array('class' => true),
            'h2'     => array('class' => true, 'id' => true),
            'h3'     => array('class' => true, 'id' => true),
            'h4'     => array('class' => true, 'id' => true),
            'h5'     => array('class' => true, 'id' => true),
            'h6'     => array('class' => true, 'id' => true),
            'blockquote' => array('class' => true, 'cite' => true),
            'figure' => array('class' => true),
            'figcaption' => array(),
            'div'    => array('class' => true, 'data-*' => true, 'style' => true), // style иногда нужен для выравнивания
            
            // КАРТИНКИ: Разрешаем только безопасные атрибуты. 
            // onerror здесь НЕТ, значит kses его вырежет.
            'img'    => array(
                'src'    => true, 
                'alt'    => true, 
                'class'  => true, 
                'width'  => true, 
                'height' => true,
                'title'  => true
            ),
            
            // IFRAME (если разрешаем Embeds)
            'iframe' => array(
                'src'             => true,
                'width'           => true,
                'height'          => true,
                'frameborder'     => true,
                'allowfullscreen' => true,
                'class'           => true,
                'id'              => true,
                'allow'           => true, // Для autoplay и прочего
                'style'           => true
            ),
        );

		error_log('RAW CONTENT: ' . print_r($content_raw, true));
		
        // 3. Жестко чистим через wp_kses
        $clean_content = wp_kses($content_raw, $allowed_html);

        // 4. (Опционально) Балансируем теги, если юзер сломал HTML
        $clean_content = force_balance_tags($clean_content);

        // 5. Возвращаем слэши для корректной записи в БД (wp_insert_post требует slashes)
       $post_data['post_content'] = wp_slash(wp_kses($content_raw, $allowed_html));
    }    
    // --- КОНЕЦ ЗАЩИТЫ ---

    // Настройка миниатюры поста

    if (isset($_POST['thumbnail_id']) && !empty($_POST['thumbnail_id'])) {

        $post_data['post_thumbnail'] = intval($_POST['thumbnail_id']);

    }    

    // Добавление NSFW

    if (isset($_POST['nsfw_content'])) {

        $post_data['_nsfw_content'] = '1';

    } else {

        $post_data['_nsfw_content'] = '0';

    }


// Добавление скрытого поста

    if (isset($_POST['hidden_post'])) {

        $post_data['_hidden_post'] = '1';

    } else {

        $post_data['_hidden_post'] = '0';

    }



    return $post_data;

}



function fred_save_post(){

    $post_data = fred_prepare_post_for_db();

    $result['id'] = wp_insert_post($post_data, true);



    if (is_wp_error($result['id'])) {

        $result['error'] = $result['id']->get_error_message();

    } else {

        // Если есть миниатюра, устанавливаем ее

        if (isset($post_data['post_thumbnail'])) {

            set_post_thumbnail($result['id'], $post_data['post_thumbnail']);

        }



        // Устанавливаем мета данные

        $comments_disable = isset($_POST['comments_disable']) ? '1' : '0';

        update_post_meta($result['id'], '_comments_disable', $comments_disable);

        update_post_meta($result['id'], '_nsfw_content', isset($_POST['nsfw_content']) ? '1' : '0');

update_post_meta($result['id'], '_hidden_post', isset($_POST['hidden_post']) ? '1' : '0');



        // Модифицируем ответ для вывода сообщения на фронте

        if (current_user_can('administrator') || current_user_can('editor')) {

            $result['message'] = __('Пост размещён в ленте.', 'front-editorjs'); // Сообщение при публикации

        } else {

            $result['message'] = __('Пост отправлен на модерацию.', 'front-editorjs'); // Сообщение при модерации

        }

    }


// --- АВТОМАТИЧЕСКАЯ УСТАНОВКА THUMBNAIL START ---
        $post_id = $result['id']; 
        if ( ! has_post_thumbnail( $post_id ) ) {
            $content_to_parse = wp_unslash($_POST['post_content'] ?? '');
            if ( preg_match('/<img.+src=["\']([^"\']+)["\']/', $content_to_parse, $matches) ) {
                $first_img_url = $matches[1];
                // Ищем ID вложения
                global $wpdb;
                $path = parse_url($first_img_url, PHP_URL_PATH);
                $found_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid LIKE %s AND post_type='attachment' LIMIT 1", '%' . $path . '%'));
                
                if ( $found_id ) {
                    set_post_thumbnail( $post_id, $found_id );
                    $thumbnail_id = $found_id; // Передаем ID дальше мусорщику, чтобы он не удалил файл
                }
            }
        }
        // --- АВТОМАТИЧЕСКАЯ УСТАНОВКА THUMBNAIL END ---

// --- GARBAGE COLLECTION START ---

    // Если пост успешно обновлен/создан

    if ( ! is_wp_error( $result['id'] ) ) {

        $post_id = $result['id'];

        

        // 1. Парсим JSON контента, чтобы найти все активные URL картинок

        $content_raw = $_POST['post_content']; // Берем сырой JSON

        // Если content экранирован слешами, убираем их

        if ( is_string($content_raw) ) {

            $content_raw = stripslashes($content_raw);

        }

        $content_data = json_decode( $content_raw, true );

        

        $used_image_urls = array();

        

        if ( isset($content_data['blocks']) && is_array($content_data['blocks']) ) {

            foreach ( $content_data['blocks'] as $block ) {

                // Ищем блоки типа image

                if ( isset($block['type']) && $block['type'] === 'image' ) {

                    if ( isset($block['data']['file']['url']) ) {

                        $used_image_urls[] = $block['data']['file']['url'];

                    }

                }

                // Если есть другие блоки с картинками (например, gallery), добавь логику сюда

            }

        }



        // 2. Получаем все вложения (картинки), привязанные к этому посту

        $attachments = get_posts( array(

            'post_type'      => 'attachment',

            'posts_per_page' => -1,

            'post_parent'    => $post_id,

            'post_mime_type' => 'image', // Только картинки

            'fields'         => 'ids' // Нам нужны только ID

        ) );



        // 3. Получаем ID миниатюры поста, чтобы случайно не снести её

        $thumbnail_id = get_post_thumbnail_id( $post_id );



        // 4. Сравниваем и удаляем

        foreach ( $attachments as $attachment_id ) {

            // Не трогаем миниатюру поста (Featured Image)

            if ( $attachment_id == $thumbnail_id ) {

                continue;

            }



            $attachment_url = wp_get_attachment_url( $attachment_id );

            

            // Если URL вложения не найден в списке используемых в контенте -> удаляем

            // Используем strpos, так как http/https или домены могут иногда шалить, но обычно точного совпадения достаточно

            if ( ! in_array( $attachment_url, $used_image_urls ) ) {

                // FORCE DELETE: true (минаем корзину, удаляем сразу файл и запись)

                wp_delete_attachment( $attachment_id, true );

                

                // Дебаг лог, если надо

                if ( defined('FRED_DEBUG') && FRED_DEBUG ) {

                    error_log("Front EditorJS GC: Deleted orphan attachment ID $attachment_id for Post $post_id");

                }

            }

        }

    }

    // --- GARBAGE COLLECTION END ---

return $result;

}

/**
 * Получаем ID вложения по его URL (оптимизировано)
 */
function fred_get_attachment_id_by_url($url) {
    global $wpdb;
    // Отрезаем домен, чтобы искать только по пути, если guid в базе капризничает
    $path = parse_url($url, PHP_URL_PATH);
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid LIKE %s AND post_type='attachment';", '%' . $path . '%'));
    return !empty($attachment) ? $attachment[0] : false;
}

$fred_post_save_res = fred_save_post();