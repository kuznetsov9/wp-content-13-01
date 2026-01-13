<?php
/**
 * Narrow setting for theme
 *
 * @package Esenin
 */

/**
 * Declension RUSSIAN
 */
 
 /**
 * Склонение слов в зависимости от числа.
 *
 *     // Examples of invocation:
 *     num_decline( $num, 'книга,книги,книг' )
 *     num_decline( $num, 'book,books' )
 *     num_decline( $num, [ 'книга','книги','книг' ] )
 *     num_decline( $num, [ 'book','books' ] )
 */
function num_decline( $number, $titles, $show_number = true ){
	if( is_string( $titles ) ){
		$titles = preg_split( '/, */', $titles );
	}
	// когда указано 2 элемента
	if( empty( $titles[2] ) ){
		$titles[2] = $titles[1];
	}
	$cases = [ 2, 0, 1, 1, 1, 2 ];
	$intnum = abs( (int) strip_tags( $number ) );
	$title_index = ( $intnum % 100 > 4 && $intnum % 100 < 20 )
		? 2
		: $cases[ min( $intnum % 10, 5 ) ];
	return ( $show_number ? "$number " : '' ) . $titles[ $title_index ];
}


/**
 * Генерируем название сайта и почту при отправке письма с сайта на @
 * По умолчанию было WordPress..
 */
function change_name($name) {
	return get_bloginfo('name');
}
add_filter('wp_mail_from_name','change_name');

function change_email($email) {
	return get_bloginfo('admin_email');
}
add_filter('wp_mail_from','change_email');
 
 



// Запретить создание дополнительных размеров изображений
function disable_image_sizes( $sizes ) {
    // Отключить все размеры
    return array();
}
add_filter( 'intermediate_image_sizes_advanced', 'disable_image_sizes' );

// Удалить уже созданные размеры изображений при загрузке нового
function remove_image_sizes_on_upload( $metadata ) {
    $upload_dir = wp_upload_dir();
    $file_path = $upload_dir['basedir'] . '/' . $metadata['file'];

    // Удаляем все дополнительные размеры, если они существуют
    foreach ( $metadata['sizes'] as $size => $size_info ) {
        $file_size_path = $upload_dir['basedir'] . '/' . $size_info['file'];
        if ( file_exists( $file_size_path ) ) {
            unlink( $file_size_path ); 
        }
    }

    $metadata['sizes'] = array();

    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'remove_image_sizes_on_upload' );

function disable_image_sizes_in_media_library( $args ) {
    $args['thumbnail_size_w'] = 0;
    $args['thumbnail_size_h'] = 0;
    return $args;
}
add_filter( 'image_size_names_choose', 'disable_image_sizes_in_media_library' );

// Отключить неправильные размеры, которые могут быть добавлены другими плагинами
function disable_plugin_generated_sizes( $sizes ) {
    return array();
}
add_filter( 'intermediate_image_sizes_advanced', 'disable_plugin_generated_sizes' );


// Для закругления аватара пользователя в админке 
add_action('admin_head', 'round_avatar_admin');
function round_avatar_admin() {
    echo '<style>
        .avatar {
            border-radius: 50%;
        }
    </style>';
}
function custom_admin_avatar_style() {
    echo '<style>
        #wp-admin-bar-my-account .avatar {
            border-radius: 50%; /* Закругляем аватар */
        }
        #wp-admin-bar-user-info .avatar {
            border-radius: 50%; /* Закругляем аватар */
        }
    </style>';
}
add_action('admin_head', 'custom_admin_avatar_style');



function disable_jquery_migrate( &$scripts ) {
    if (!is_admin()) {
        if (isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];
            // Удаляем jQuery Migrate
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_filter('wp_default_scripts', 'disable_jquery_migrate');


// Post Footer Sticky
function enqueue_custom_scripts() {
    if (is_single()) { 
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                var postBlock = $('.entry-content');
                var footerSticky = $('.post-footer');

           
                function toggleFooterSticky() {
                    if ($(window).scrollTop() + $(window).height() > postBlock.offset().top + postBlock.outerHeight() - 200) {
                        footerSticky.removeClass('sticky');
                    } else {
                        footerSticky.addClass('sticky');
                    }
                }

                footerSticky.addClass('sticky');

                $(window).on('scroll', function() {
                    toggleFooterSticky();
                });

                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
                    footerSticky.removeClass('sticky');
                }

                $(window).on('load', function() {
                    if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
                        footerSticky.removeClass('sticky');
                    }
                });
            });			
        </script>
        <?php
    }
}
add_action('wp_footer', 'enqueue_custom_scripts');

// Button Delete Post (page My Posts)
function handle_post_deletion() {
    if (isset($_GET['delete_post']) && current_user_can('delete_post', intval($_GET['delete_post']))) {
        $post_id = intval($_GET['delete_post']);

        wp_delete_post($post_id);

        wp_redirect(remove_query_arg('delete_post'));
        exit; 
    }
}

add_action('init', 'handle_post_deletion');


// Blur Bg Image (Posts)
function custom_image_blur_background() {
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const figures = document.querySelectorAll('figure.wp-block-image');
            figures.forEach(figure => {
                const img = figure.querySelector('img');
                const postBlock = figure.closest('.es-post') || figure.closest('.pc-excerpt');

                if (img && postBlock) {
                    const imgWidth = img.naturalWidth;
                    const postBlockWidth = postBlock.offsetWidth;

                    if (imgWidth < postBlockWidth) {
                        // Создаем новый элемент div с классом bg-blur
                        const bgBlurDiv = document.createElement('div');
                        bgBlurDiv.classList.add('bg-blur');
                        bgBlurDiv.style.backgroundImage = 'url(' + img.src + ')';
                       

                        figure.style.position = 'relative';

                        figure.insertBefore(bgBlurDiv, img);

                        figure.classList.add('es-image-blur');
                    }
                }
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'custom_image_blur_background');

// Blur Bg Image (Thumbnail)
function custom_image_blur_background_thumb() {
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const figures = document.querySelectorAll('.es-overlay-transparent');
            figures.forEach(figure => {
                const img = figure.querySelector('img');
                const postBlock = figure.closest('.post-card')|| figure.closest('.es-post');

                if (img && postBlock) {
                    const imgWidth = img.naturalWidth;
                    const postBlockWidth = postBlock.offsetWidth;

                    if (imgWidth < postBlockWidth) {
                        // Создаем новый элемент div с классом bg-blur
                        const bgBlurDiv = document.createElement('div');
                        bgBlurDiv.classList.add('bg-blur');
                        bgBlurDiv.style.backgroundImage = 'url(' + img.src + ')';
                       

                        figure.style.position = 'relative';

                        figure.insertBefore(bgBlurDiv, img);

                        figure.classList.add('es-image-blur');
                    }
                }
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'custom_image_blur_background_thumb');


function compact_pagination() {
    global $wp_query;
    $current_page = max(1, get_query_var('paged'));
    $total_pages = $wp_query->max_num_pages;

    if ($total_pages <= 1) {
        return;
    }
    echo '<div class="es-posts-area__pagination mt-0">';
    echo '<nav class="navigation pagination">';	
	echo '<div class="nav-links">';

    if ($current_page > 1) {
        echo '<a class="prev page-numbers current" href="' . get_pagenum_link($current_page - 1) . '">' . __( 'Previous', 'esenin' ) . '</a>';
    }

    echo '<span class="current-page page-numbers current">' . $current_page . ' ' . __( 'for ', 'esenin' ) . $total_pages . '</span>';

    if ($current_page < $total_pages) {
        echo '<a class="next page-numbers current" href="' . get_pagenum_link($current_page + 1) . '">' . __( 'Next', 'esenin' ) . '</a>';
    }

    echo '</div>';
    echo '</nav>';
	echo '</div>';
}

// Share custom text (post and commments)
function add_share_toolbar() {
    if (is_single() || have_comments()) {
        ?>
       <div id="share-toolbar" style="display: none;">
		<button type="button" onclick="shareVK()">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/social/vk.svg" alt="<?php esc_attr_e( 'Поделиться Вконтакте', 'esenin' ); ?>" />
        </button>
        <button type="button" onclick="shareTelegram()">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/social/telegram.svg" alt="<?php esc_attr_e( 'Разместить в Telegram', 'esenin' ); ?>" />
        </button>
       </div>        
        <?php
    }
}

add_action('wp_footer', 'add_share_toolbar');




// Добавляем колонку "Карма" в таблицу пользователей
add_filter('manage_users_columns', 'add_karma_column');
function add_karma_column($columns) {
    $columns['user_karma'] = __('Карма', 'esenin');
    return $columns;
}

add_filter('manage_users_custom_column', 'show_user_karma_column', 10, 3);
function show_user_karma_column($output, $column_name, $user_id) {
    if ('user_karma' === $column_name) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_karma';
        $karma = $wpdb->get_var($wpdb->prepare("SELECT karma FROM $table_name WHERE user_id = %d", $user_id));
        return $karma !== null ? intval($karma) : 0; // Выводим карму или 0, если нет записи
    }
    return $output;
}

add_action('show_user_profile', 'karma_user_profile_field');
add_action('edit_user_profile', 'karma_user_profile_field');
function karma_user_profile_field($user) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_karma';
    $karma = $wpdb->get_var($wpdb->prepare("SELECT karma FROM $table_name WHERE user_id = %d", $user->ID));
    $karma = $karma !== null ? intval($karma) : 0; // Если карма не существует, устанавливаем 0
    ?>
    <h3><?php _e('Карма пользователя', 'esenin'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="user_karma"><?php _e('Карма', 'esenin'); ?></label></th>
            <td>
                <input type="number" name="user_karma" id="user_karma" value="<?php echo esc_attr($karma); ?>" class="regular-text" />
                <p class="description"><?php _e('Введите значение кармы для этого пользователя', 'esenin'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

add_action('personal_options_update', 'save_user_karma');
add_action('edit_user_profile_update', 'save_user_karma');
function save_user_karma($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'user_karma';
    $karma = isset($_POST['user_karma']) ? intval($_POST['user_karma']) : 0;

    $current_karma = $wpdb->get_var($wpdb->prepare("SELECT karma FROM $table_name WHERE user_id = %d", $user_id));
    if ($current_karma === null) {
        $wpdb->insert($table_name, array('user_id' => $user_id, 'karma' => $karma));
    } else {
        $wpdb->update($table_name, array('karma' => $karma), array('user_id' => $user_id));
    }
}

add_action('wp_ajax_update_user_karma', 'ajax_update_user_karma');
function ajax_update_user_karma() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error();
    }

    global $wpdb;
    $user_id = intval($_POST['user_id']);
    $karma = intval($_POST['karma']);
    $table_name = $wpdb->prefix . 'user_karma';

    $result = $wpdb->update($table_name, array('karma' => $karma), array('user_id' => $user_id));

    if ($result !== false) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}



// Hiding wp-admin from regular users and guests
function restrict_admin_access() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();

        if (!in_array('administrator', $current_user->roles) && !in_array('editor', $current_user->roles)) {
            if (!defined('DOING_AJAX') || !DOING_AJAX) {
                wp_redirect(home_url('/author/' . $current_user->user_nicename));
                exit;
            }
        }
    } else {
        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            wp_redirect(wp_login_url());
            exit;
        }
    }
}
add_action('admin_init', 'restrict_admin_access');




function add_pending_posts_count_to_menu() {
    global $menu;

    $count_posts = wp_count_posts();
    $pending_count = $count_posts->pending;

    if ($pending_count > 0) {
        $menu[5][0] .= ' <span class="awaiting-mod" style="background: #00ad64; color: white; border-radius: 10px; padding: 0 5px; font-weight: 700; box-shadow: 0 1px 3px 0 rgb(0 0 0 / .1), 0 1px 2px -1px rgb(0 0 0 / .1);">' . $pending_count . '</span>';
    }
}
add_action('admin_menu', 'add_pending_posts_count_to_menu');


// Добавление логики для автоматической генерации мета-описаний и ключевых слов
add_action('save_post', 'set_meta_description_and_keywords');

function set_meta_description_and_keywords($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!current_user_can('edit_post', $post_id)) return;

    $post = get_post($post_id);

    if ($post->post_type == 'post' || $post->post_type == 'page') {
        $content = $post->post_content;

        $meta_description = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
        if (empty($meta_description)) {
            $meta_description = wp_trim_words($content, 50, '');
            if (mb_strlen($meta_description) > 180) {
                $meta_description = mb_substr($meta_description, 0, 177) . '...';
            } elseif (mb_strlen($meta_description) < 160) {
                $meta_description = mb_substr($meta_description, 0, 160);
            }
            update_post_meta($post_id, '_yoast_wpseo_metadesc', $meta_description);
        }

        $keywords = get_post_meta($post_id, '_yoast_wpseo_focuskw', true);
        if (empty($keywords)) {
            preg_match('/\b(главная ключевая фраза|фраза1, фраза2|фраза3)\b/i', $content, $matches);
            if (!empty($matches)) {
                $keywords = implode(',', array_map('trim', explode(',', $matches[0])));
            } else {
                $title = get_the_title($post_id);
                $keywords = $title;
            }
            update_post_meta($post_id, '_yoast_wpseo_focuskw', $keywords); 
        }

        set_search_image_meta($post_id, $content);
    }
}

function set_search_image_meta($post_id, $content) {
    $thumbnail_id = get_post_thumbnail_id($post_id);

    if (!$thumbnail_id) {
        preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $content, $matches);
        if (!empty($matches[1])) {
            $first_image = $matches[1][0];
            update_post_meta($post_id, '_yoast_wpseo_opengraph-image', esc_url($first_image));
            update_post_meta($post_id, '_yoast_wpseo_twitter-image', esc_url($first_image));
        }
    } else {
        $thumbnail_url = wp_get_attachment_url($thumbnail_id);
        update_post_meta($post_id, '_yoast_wpseo_opengraph-image', esc_url($thumbnail_url));
        update_post_meta($post_id, '_yoast_wpseo_twitter-image', esc_url($thumbnail_url));
    }
}