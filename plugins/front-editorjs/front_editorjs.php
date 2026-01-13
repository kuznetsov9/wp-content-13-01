<?php
/**
 * Plugin Name: Front EditorJS
 * Description: Редактор Editor.js для шаблона Esenin
 * Plugin URI: https://devster.ru/
 * Version: 1.1
 * Author: Alexander Khlimankov
 * Author URI: https://t.me/khlimankov
 * Requires at least: 6.8
 * Tested up to: 6.8.1
 * Text Domain: front-editorjs
 * Domain Path: /languages/
 * **/


if ( ! defined( 'ABSPATH' ) ) {	
	exit;
}

if ( ! defined( 'FRONT_EDITORJS_PLUGIN_DIR' ) ) {
	define( 'FRONT_EDITORJS_PLUGIN_DIR',  dirname( __FILE__ )  );
}

if ( ! defined( 'FRONT_EDITORJS_PLUGIN_URL' ) ) {
    define( 'FRONT_EDITORJS_PLUGIN_URL',  plugins_url( '', __FILE__ )  );
}


if( !function_exists( 'fred_register_scripts' ) ) {

    function fred_register_scripts(){
		wp_enqueue_script( 'dompurify', 'https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.0.6/purify.min.js', array(), '3.0.6', true );
        wp_enqueue_script("global-setting", FRONT_EDITORJS_PLUGIN_URL.'/assets/js/vendor/global-setting.js', array());
        wp_enqueue_script("fred-script", FRONT_EDITORJS_PLUGIN_URL.'/assets/js/script.js', array());
		
        wp_localize_script('fred-script', 'siteData', array(
             'url' => site_url(),
             'ajaxUrl' => admin_url('admin-ajax.php'),
             'nonce' => wp_create_nonce('fred_ajax_nonce') 
        ));
       
        // Plugins Editorjs
        wp_enqueue_script( "fred_underline" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/underline.js");		
        wp_enqueue_script( "fred_delimiter" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/delimiter.js");
        wp_enqueue_script( "fred_embed" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/embed.js");
        wp_enqueue_script( "fred_header" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/header.js");
        wp_enqueue_script( "fred_image" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/image.js");
        wp_enqueue_script( "fred_link" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/link.js");
        wp_enqueue_script( "fred_list" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/list.js");
        wp_enqueue_script( "fred_marker" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/marker.js");
        wp_enqueue_script( "fred_paragraph" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/paragraph.js");
        wp_enqueue_script( "fred_quote" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/quote.js");
        wp_enqueue_script( "fred_button" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/link-button.js");
		wp_enqueue_script( "fred_telegram" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/embed-telegram.js");
		wp_enqueue_script( "fred_tiktok" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/embed-tiktok.js");
		wp_enqueue_script( "fred_alerts" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/alerts.js");
        
        wp_enqueue_script( "fred_editor" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/plugins/editor.js");
		
		// Renders
		wp_enqueue_script( "fred_htmltoeditjs" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/vendor/htmltoeditjs.js");
        wp_enqueue_script( "fred_edjsHTML" , FRONT_EDITORJS_PLUGIN_URL."/assets/js/vendor/edjsHTML.browser.js");		      
	}
}


function front_editorjs_activate() {
    $post_editor_page = array(
        'post_title'    =>  __( "Добавить пост" , 'front-editorjs' ),
        'post_name'     => 'editor',
        'post_content'  => '[fred_post_editor]',
        'post_status'   => 'publish',
        'post_type'     => 'page',
    );
    $post_editor_id = wp_insert_post( $post_editor_page );

    $my_posts_page = array(
        'post_title'    => __( "Мои посты" , 'front-editorjs' ),
        'post_name'     => 'my-posts',
        'post_content'  => '[fred_my_posts]',
        'post_status'   => 'publish',
        'post_type'     => 'page',
    );
    $my_posts_id = wp_insert_post( $my_posts_page );

    update_option( 'front_editorjs_post_editor_page_id', $post_editor_id );
    update_option( 'front_editorjs_my_posts_page_id', $my_posts_id );

    update_option( 'fred_page_shortcode', $post_editor_id );

    $role = get_role( 'author' );
    if ( ! empty( $role ) ) {
        $role->add_cap( 'edit_posts' );
        $role->add_cap( 'create_posts' );
        $role->add_cap( 'publish_posts' ); 
        $role->add_cap( 'edit_published_posts' ); 
    }
}
register_activation_hook( __FILE__, 'front_editorjs_activate' );

function front_editorjs_deactivate() {
    $post_editor_id = get_option( 'front_editorjs_post_editor_page_id' );
    $my_posts_id = get_option( 'front_editorjs_my_posts_page_id' );

    if ( $post_editor_id ) {
        wp_delete_post( $post_editor_id, true );
    }

    if ( $my_posts_id ) {
        wp_delete_post( $my_posts_id, true );
    }

    delete_option( 'front_editorjs_post_editor_page_id' );
    delete_option( 'front_editorjs_my_posts_page_id' );
    delete_option( 'fred_page_shortcode' );

    $role = get_role( 'author' );
    if ( ! empty( $role ) ) {
        $role->remove_cap( 'edit_posts' );
        $role->remove_cap( 'create_posts' );
        $role->remove_cap( 'publish_posts' );
        $role->remove_cap( 'edit_published_posts' );
    }

    global $wpdb;
    $prefix = $wpdb->prefix;

    $wpdb->query( "DELETE FROM {$prefix}postmeta WHERE meta_key LIKE 'fred_%'" );
    $wpdb->query( "DELETE FROM {$prefix}usermeta WHERE meta_key LIKE 'fred_%'" );

}
register_deactivation_hook( __FILE__, 'front_editorjs_deactivate' );

// LOGIC MY POSTS (PAGE)
require (FRONT_EDITORJS_PLUGIN_DIR.'/view/fred_my_posts.php');


// SHORTCODE FOR EDITOR REGISTER
add_shortcode('fred_post_editor', 'fred_post_editor_new_post');

if(!function_exists('fred_post_edit_link')){
    function fred_post_edit_link($link, $post_id, $context) {
        if ( is_admin() ) {
            return $link;
        }

        if ( current_user_can('administrator') ) {
            return $link;
        }

        // Use the correct URL for the editor page
        $editor_page_url = home_url( '/editor/?fred=edit&fred_id=' . $post_id );
        return $editor_page_url;
    }
}

add_filter('edit_post_link','fred_post_edit_link', 10, 3);


function fred_add_edit_links_to_admin_posts_list( $actions, $post ) {
    $edit_link = get_edit_post_link( $post->ID );
    $front_edit_link = home_url( '/editor/?fred=edit&fred_id=' . $post->ID );

    if ($post->post_type == 'post') {
        $actions['edit_front'] = '<a style="color: #00ad64;" target="blank" href="' . esc_url( $front_edit_link ) . '">' . __( 'Изменить (EditorJS)', 'front-editorjs' ) . '</a>';
    }
    return $actions;
}

add_filter( 'post_row_actions', 'fred_add_edit_links_to_admin_posts_list', 10, 2 );
add_filter( 'page_row_actions', 'fred_add_edit_links_to_admin_posts_list', 10, 2 );

///////////////////////////////

if( !function_exists( 'fred_post_editor_new_post' ) ) {
    function fred_post_editor_new_post(){
		ob_start();
        wp_enqueue_style('fred-editor-style', FRONT_EDITORJS_PLUGIN_URL.'/assets/css/editor.css');
        wp_enqueue_style('fred-style', FRONT_EDITORJS_PLUGIN_URL.'/assets/css/style.css');
        wp_enqueue_media ();
        fred_register_scripts();

		if ( !is_user_logged_in() ) {
            echo '<p>'.__( "Для добавления постов авторизуйтесь на сайте." , 'front-editorjs' ).'</p>';
            return ob_get_clean(); // Возвращаем то, что успели "наэхоить"
        }

        $user = wp_get_current_user();
        $current_user_id = $user->ID;
        $user_name = $user->display_name; // Получаем имя юзера (логин или имя)
        $is_edit_mode = (isset($_GET['fred']) && $_GET['fred'] == 'edit');

        // --- АНТИФЛУД С ДИНАМИЧЕСКИМ ИМЕНЕМ ---
        if ( ! $is_edit_mode && ! current_user_can('administrator') ) {
            global $wpdb;

            $last_post_date = $wpdb->get_var( $wpdb->prepare(
                "SELECT post_date 
                 FROM $wpdb->posts 
                 WHERE post_author = %d 
                 AND post_type = 'post' 
                 AND post_status IN ('publish', 'pending', 'draft', 'future')
                 ORDER BY post_date DESC 
                 LIMIT 1",
                $current_user_id
            ) );

            if ( $last_post_date && $last_post_date != '0000-00-00 00:00:00' ) {
                $last_time    = strtotime( $last_post_date );
                $current_time = current_time('timestamp');
                $wait_time    = 3 * 60; 
                $diff         = $current_time - $last_time;

                if ( $diff < $wait_time && $diff >= 0 ) {
                    $minutes_left = ceil( ($wait_time - $diff) / 60 );

                    echo '<div class="fred-flood-stop" style="text-align:center; padding: 20px;">';
                    echo '<span style="font-size: 50px;">🏟️</span>';
                    // Динамическое обращение
                    echo '<h3 style="margin-top:20px;">Таймаут, ' . esc_html($user_name) . '!</h3>';
                    echo '<p style="line-height:1.5;">У нас нельзя частить с постами. <br>Подожди ещё <b>' . $minutes_left . ' мин.</b> и возвращайся в игру.</p>';
                    
                    // Кнопка-обертка
                    echo '<button onclick="window.location.href=\'' . home_url() . '\'" style="display:inline-block; margin-top:15px; background:var(--es-color-button); color:var(--es-color-button-contrast); padding:0.5rem 0.8rem; border:none; border-radius:var(--es-button-border-radius); cursor:pointer; font-size:15px; font-weight:500;">';
                    echo 'На главную';
                    echo '</button>';
                    
                    echo '</div>';
                    
                    return ob_get_clean(); // Снова выход через буфер
                }
            }
        }
        // --- КОНЕЦ АНТИФЛУДА ---

if ( is_admin() ) {
    return; 
}

        // Логика сохранения
        if(isset($_POST['fred_post_save']) && $_POST['fred_post_save'] == 'post_save'){
            if(wp_verify_nonce($_POST['_wpnonce'])){
                require (FRONT_EDITORJS_PLUGIN_DIR.'/inc/fred_post_save.php');
                if (isset($fred_post_save_res['id']) && $fred_post_save_res['id'] > 0) {
					ob_end_clean();
                    echo '<script>window.location.href="' . esc_url(get_permalink($fred_post_save_res['id'])) . '";</script>';
                    exit;
                }
            }
        }

        $allowed_roles = array( 'administrator', 'editor', 'author', 'subscriber', 'contributor' );
        if ( array_intersect( $allowed_roles, $user->roles ) ) {
            require (FRONT_EDITORJS_PLUGIN_DIR.'/view/fred_new_post_editor.php');
        } else {
            echo '<p>'.__( "Вам не разрешено создавать посты." , 'front-editorjs' ).'</p>';
        }
		return ob_get_clean();
    }
}

add_action( 'plugins_loaded', 'fred_language_files_init' );
function fred_language_files_init(){
	load_plugin_textdomain( 'front-editorjs', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}


///////////////////////

function allow_iframe_in_wordpress() {
    global $allowedposttags;
    $allowedposttags['iframe'] = array(
        'src' => array(),
        'height' => array(),
        'width' => array(),
        'frameborder' => array(),
        'scrolling' => array(),
        'allowfullscreen' => array(),
        'title' => array(),
    );
}
add_action('init', 'allow_iframe_in_wordpress');

///////////////////

add_action('rest_api_init', function () {
    register_rest_route('edjs', 'link-info', array(
        'methods' => 'GET',
        'callback' => 'get_link_info',
		'permission_callback' => '__return_true', // КИЛЛЕР-ФИЧА: разрешает всем юзерам юзать этот эндпоинт
    ));
});

function get_link_info($request) {
    $url = esc_url($request->get_param('url'));
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        return new WP_Error('link_error', 'Ошибка получения ссылки', array('status' => 500));
    }

    $body = wp_remote_retrieve_body($response);
    $meta = extract_meta_data($body);

    return rest_ensure_response(array(
        'success' => 1,
        'link' => $url,
        'meta' => $meta
    ));
}

function extract_meta_data($html) {
    $meta = array();

    if (preg_match('/<title>(.*?)<\/title>/', $html, $matches)) {
        $meta['title'] = $matches[1];
    }

    if (preg_match('/<meta name="description" content="(.*?)"/', $html, $matches)) {
        $meta['description'] = $matches[1];
    }

    if (preg_match('/<meta property="og:image" content="(.*?)"/', $html, $matches)) {
        $meta['image'] = array('url' => $matches[1]);
    }

    return $meta;
}

/////////////////////////////

/**
 * Вспомогательная функция: ресайз до 920px + конвертация в WebP
 * Возвращает путь к новому файлу (или к старому при ошибке)
 */
function fred_process_image($file_path) {
    $editor = wp_get_image_editor($file_path);
    if (is_wp_error($editor)) {
        return $file_path; 
    }

    $size = $editor->get_size();

    // Ресайз по ширине до 920px
    if ($size['width'] > 920) {
        $editor->resize(920, null, false);
    }

    // Качество 90%
    $editor->set_quality(75);
    
    // Формируем новый путь с расширением .webp
    $path_parts = pathinfo($file_path);
    $new_path = $path_parts['dirname'] . '/' . $path_parts['filename'] . '.webp';

    // Сохраняем как image/webp
    $saved = $editor->save($new_path, 'image/webp');
    
    if (is_wp_error($saved)) {
        return $file_path; // При ошибке возвращаем старый путь
    }

    // Если успешно сохранили новый файл, удаляем исходный (png/jpg)
    if ($saved['path'] !== $file_path && file_exists($file_path)) {
        @unlink($file_path);
    }
    
    return $saved['path'];
}

// upload_image
function MimeTypeToExtension($mime_type) {
    $extensions = array(
        'image/jpeg'    => 'jpg',
        'image/png'     => 'png',
        'image/gif'     => 'gif',
        'image/webp'    => 'webp',
        'image/svg+xml' => 'svg',
        'image/bmp'     => 'bmp',
        'image/tiff'    => 'tiff',
        'image/x-icon'  => 'ico',
    );
    $mime_type = strtok($mime_type, ';');
    return isset($extensions[$mime_type]) ? $extensions[$mime_type] : '';
}

function create_attachment_and_response($file_path, $file_url, $mime_type) {
    $filename = basename($file_path);
    $attachment = array(
        'guid'           => $file_url, 
        'post_mime_type' => $mime_type,
        'post_title'     => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)), 
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $file_path);

    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);

    wp_update_attachment_metadata($attach_id, $attach_data);

    return array(
        'success' => 1, 
        'file'    => array(
            'url' => $file_url,
            'id'  => $attach_id 
        )
    );
}

function handle_image_upload() {
    header('Content-Type: application/json');
    $response = array('success' => 0, 'message' => 'Неизвестная ошибка.'); 
    $upload_dir_info = wp_upload_dir();
    $upload_path = $upload_dir_info['path'];
    $base_upload_url = $upload_dir_info['baseurl'] . $upload_dir_info['subdir'];

    if (!empty($_FILES['file'])) {
        $uploaded_file = $_FILES['file'];

        if ($uploaded_file['error'] !== UPLOAD_ERR_OK) {
            $response['message'] = 'Ошибка при загрузке файла: ' . $uploaded_file['error'];
            echo json_encode($response);
            wp_die();
        }

        $file_extension = pathinfo($uploaded_file['name'], PATHINFO_EXTENSION);
        if (empty($file_extension)) {
            $file_extension = MimeTypeToExtension($uploaded_file['type']);
            if (empty($file_extension)) {
                $file_extension = 'jpg';
            }
        }

        $base_filename = 'editorjs-img-' . time() . '-' . wp_rand(1000, 9999);
        $new_filename = $base_filename . '.' . $file_extension;
        $filename = wp_unique_filename($upload_path, $new_filename);
        $target_file = $upload_path . '/' . $filename;
       
        if (move_uploaded_file($uploaded_file['tmp_name'], $target_file)) {
            
            // 1. Конвертируем в WebP
            $final_file_path = fred_process_image($target_file);

            // 2. Обновляем URL и имя файла, так как расширение могло измениться
            $filename = basename($final_file_path);
            $file_url = $base_upload_url . '/' . $filename;
            
            // 3. Определяем новый MIME (скорее всего image/webp)
            $file_type = wp_check_filetype($filename, null);
            $final_mime = $file_type['type'];

            $response = create_attachment_and_response($final_file_path, $file_url, $final_mime);
        } else {
            $response['message'] = 'Ошибка перемещения загруженного файла.';
        }
    } else {
        $response['message'] = 'Файл не найден в запросе.';
    }

    echo json_encode($response);
    wp_die();
}

function handle_image_upload_by_url() {
    header('Content-Type: application/json');
    $response = array('success' => 0, 'message' => 'Неизвестная ошибка.');

    $image_url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';

    if ($image_url) {
        if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
            $response['message'] = 'Недействительный URL изображения.';
            echo json_encode($response);
            wp_die();
        }

        $remote_response = wp_remote_get($image_url, array('timeout' => 15));

        if (is_wp_error($remote_response)) {
            $response['message'] = 'Ошибка при получении изображения с URL: ' . $remote_response->get_error_message();
            echo json_encode($response);
            wp_die();
        }

        $status_code = wp_remote_retrieve_response_code($remote_response);
        if ($status_code !== 200) {
            $response['message'] = 'Не удалось загрузить изображение с URL. Код статуса: ' . $status_code;
            echo json_encode($response);
            wp_die();
        }

        $image_content = wp_remote_retrieve_body($remote_response);
        $content_type = wp_remote_retrieve_header($remote_response, 'content-type');

        $file_extension = MimeTypeToExtension($content_type);
        if (empty($file_extension)) {
            $path_parts = pathinfo(parse_url($image_url, PHP_URL_PATH));
            $file_extension = isset($path_parts['extension']) ? $path_parts['extension'] : 'jpg'; 
        }

        $base_filename = 'editorjs-url-img-' . time() . '-' . wp_rand(1000, 9999);
        $new_filename = $base_filename . '.' . $file_extension;

        $upload = wp_upload_bits($new_filename, null, $image_content);

        if ($upload['error']) {
            $response['message'] = 'Ошибка сохранения изображения с URL: ' . $upload['error'];
        } else {
            // 1. Конвертируем в WebP
            $final_file_path = fred_process_image($upload['file']);
            
            // 2. Обновляем данные (путь в upload_bits уже не актуален, если формат сменился)
            $filename = basename($final_file_path);
            
            // Получаем базовый URL загрузки (приходится пересчитывать, т.к. wp_upload_bits возвращает полный url)
            $upload_dir_info = wp_upload_dir();
            $base_upload_url = $upload_dir_info['baseurl'] . $upload_dir_info['subdir'];
            $file_url = $base_upload_url . '/' . $filename;
            
            // 3. MIME
            $file_type = wp_check_filetype($filename, null);
            $final_mime = $file_type['type'];

            $response = create_attachment_and_response($final_file_path, $file_url, $final_mime);
        }
    } else {
        $response['message'] = 'URL не найден в запросе.';
    }

    echo json_encode($response);
    wp_die();
}

add_action('wp_ajax_handle_image_upload', 'handle_image_upload');
add_action('wp_ajax_nopriv_handle_image_upload', 'handle_image_upload');

add_action('wp_ajax_handle_image_upload_by_url', 'handle_image_upload_by_url');
add_action('wp_ajax_nopriv_handle_image_upload_by_url', 'handle_image_upload_by_url');


/////////////////

// upload_thumb
function handle_thumb_upload() {
    // 1. Базовые проверки
    if (empty($_FILES['file'])) {
        echo json_encode(['success' => false, 'message' => 'Файл не передан']);
        wp_die();
    }

    $uploaded_file = $_FILES['file'];
    $upload_dir = wp_upload_dir();

    if ($uploaded_file['error'] !== 0) {
        echo json_encode(['success' => false, 'message' => 'Ошибка загрузки PHP: ' . $uploaded_file['error']]);
        wp_die();
    }

    // 2. Генерируем уникальное имя, чтобы не затереть ничего
    $filename = wp_unique_filename($upload_dir['path'], sanitize_file_name($uploaded_file['name']));
    $target_file = $upload_dir['path'] . '/' . $filename;

    // 3. Перемещаем исходник
    if (move_uploaded_file($uploaded_file['tmp_name'], $target_file)) {
        
        // --- НАЧАЛО МАГИИ ---
        // Прогоняем через твой конвертер (ресайз + webp)
        // Он вернет путь к новому файлу, если все ок, или к старому, если ошибка
        $final_file_path = fred_process_image($target_file);
        
        // Переопределяем имя файла и URL, так как расширение могло смениться на .webp
        $filename = basename($final_file_path);
        $file_url = $upload_dir['url'] . '/' . $filename;
        
        // Определяем реальный MIME-тип (теперь это image/webp)
        $file_type = wp_check_filetype($filename, null);
        $final_mime = $file_type['type'];
        // --- КОНЕЦ МАГИИ ---

        // 4. Регистрируем в WordPress
        $attachment = array(
            'guid'           => $file_url, 
            'post_mime_type' => $final_mime, // Важно: пишем реальный mime
            'post_title'     => sanitize_file_name($filename),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        $attach_id = wp_insert_attachment($attachment, $final_file_path);
        
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        
        // Генерируем метаданные (размеры и прочее)
        $attach_data = wp_generate_attachment_metadata($attach_id, $final_file_path);
        wp_update_attachment_metadata($attach_id, $attach_data);

        echo json_encode([
            'success' => true, 
            'fileUrl' => $file_url, 
            'attachId' => $attach_id
        ]);

    } else {
        echo json_encode(['success' => false, 'message' => 'Не удалось переместить файл в uploads']);
    }

    wp_die();
}

function remove_thumbnail() {
    $attach_id = intval($_POST['attachId']);

    if (wp_delete_attachment($attach_id, true)) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Ошибка удаления.'));
    }

    wp_die();
}

add_action('wp_ajax_handle_thumb_upload', 'handle_thumb_upload');
add_action('wp_ajax_remove_thumbnail', 'remove_thumbnail');


// Block Hidden Content

add_filter('the_content', 'custom_display_hidden_content');

function custom_display_hidden_content($content) {
    $current_user = wp_get_current_user();
    $post_id = get_the_ID();
    $is_hidden = get_post_meta($post_id, '_hidden_post', true);

    if ($is_hidden && !in_array('administrator', $current_user->roles) && !in_array('editor', $current_user->roles)) {
        $subscribed = false;

        global $wpdb;
        $table_name = $wpdb->prefix . 'user_subscriptions';

        $subscriptions = $wpdb->get_col($wpdb->prepare("SELECT subscriber_id FROM $table_name WHERE user_id = %d", get_post_field('post_author', $post_id)));

        if (in_array($current_user->ID, $subscriptions)) {
            $subscribed = true;
        }

    if (!$current_user->ID) {
        $paragraphs = explode("\n", $content);
        if (count($paragraphs) > 2) {
            $visible_content = implode("\n", array_slice($paragraphs, 0, 2));
            return $visible_content . '<div class="esn-hidden-block">' .
                   '<div class="esn-hidden-block-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="512" height="512"><g><path d="M405.333,179.712v-30.379C405.333,66.859,338.475,0,256,0S106.667,66.859,106.667,149.333v30.379   c-38.826,16.945-63.944,55.259-64,97.621v128C42.737,464.214,90.452,511.93,149.333,512h213.333   c58.881-0.07,106.596-47.786,106.667-106.667v-128C469.278,234.971,444.159,196.657,405.333,179.712z M277.333,362.667   c0,11.782-9.551,21.333-21.333,21.333c-11.782,0-21.333-9.551-21.333-21.333V320c0-11.782,9.551-21.333,21.333-21.333   c11.782,0,21.333,9.551,21.333,21.333V362.667z M362.667,170.667H149.333v-21.333c0-58.91,47.756-106.667,106.667-106.667   s106.667,47.756,106.667,106.667V170.667z"/></g></svg></div>' .
                   '<div class="esn-hidden-block-title">' . __('Скрытый контент', 'front-editorjs') . '</div>' .
                   '<div class="esn-hidden-block-text">' . sprintf(__('Для продолжения <a href="%s">авторизуйтесь</a> на сайте.', 'front-editorjs'), '#login') . '</div>' .
                   '</div>';
        }
    }

    if (!$subscribed) {
        $paragraphs = explode("\n", $content);
        if (count($paragraphs) > 2) {
            $visible_content = implode("\n", array_slice($paragraphs, 0, 2));
            return $visible_content . '<div class="esn-hidden-block">' .
                   '<div class="esn-hidden-block-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="512" height="512"><g><path d="M405.333,179.712v-30.379C405.333,66.859,338.475,0,256,0S106.667,66.859,106.667,149.333v30.379   c-38.826,16.945-63.944,55.259-64,97.621v128C42.737,464.214,90.452,511.93,149.333,512h213.333   c58.881-0.07,106.596-47.786,106.667-106.667v-128C469.278,234.971,444.159,196.657,405.333,179.712z M277.333,362.667   c0,11.782-9.551,21.333-21.333,21.333c-11.782,0-21.333-9.551-21.333-21.333V320c0-11.782,9.551-21.333,21.333-21.333   c11.782,0,21.333,9.551,21.333,21.333V362.667z M362.667,170.667H149.333v-21.333c0-58.91,47.756-106.667,106.667-106.667   s106.667,47.756,106.667,106.667V170.667z"/></g></svg></div>' .
                   '<div class="esn-hidden-block-title">' . __('Скрытый контент', 'front-editorjs') . '</div>' .
                   '<div class="esn-hidden-block-text">' . sprintf(__('Для продолжения подпишитесь на <a href="%s">автора</a> поста.', 'front-editorjs'), get_author_posts_url(get_post_field('post_author', $post_id))) . '</div>' .
                   '</div>';
        }
      }
    }
	
    return $content;
}

function delete_editorjs_image() {
    // 1. Проверяем Nonce (что запрос пришел с нашего сайта)
    check_ajax_referer('fred_ajax_nonce', 'security');
    
    // 2. Проверяем права на загрузку файлов
    if (!current_user_can('upload_files')) {
         wp_send_json_error('Permission denied.');
    }

    $image_id = isset($_POST['image_id']) ? intval($_POST['image_id']) : 0;
    $image_url = isset($_POST['image_url']) ? esc_url_raw($_POST['image_url']) : '';

    if (!$image_id && $image_url) {
        $image_id = attachment_url_to_postid($image_url);
    }

    if ($image_id) {
        // --- ЗАЩИТА ОТ IDOR ---
        $attachment = get_post($image_id);
        
        if (!$attachment) {
            wp_send_json_error('File not found.');
        }

        // Получаем ID текущего юзера
        $current_user_id = get_current_user_id();

        // Если это не Админ И это не автор файла — посылаем нафиг
        if ( !current_user_can('manage_options') && intval($attachment->post_author) !== $current_user_id ) {
             // Можно записать в лог попытку взлома
             error_log("User $current_user_id tried to delete attachment $image_id belonging to {$attachment->post_author}");
             wp_send_json_error('Вы не можете удалить чужой файл.');
        }
        // --- КОНЕЦ ЗАЩИТЫ ---

        // Теперь удаляем безопасно (мимо корзины, чтобы сразу место освободить)
        if (wp_delete_attachment($image_id, true)) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to delete attachment.');
        }
    } else {
        wp_send_json_error('Image not found.');
    }
}
add_action('wp_ajax_delete_editorjs_image', 'delete_editorjs_image');