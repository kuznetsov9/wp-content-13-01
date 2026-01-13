<?php
/**
 * Plugin Name: User Avatars
 * Plugin URI:  https://t.me/khlimankov
 * Description: Интерактивная загрузка аватарок через клик по фото для темы Esenin.
 * Version:     1.1
 * Author:      A.Khlimankov & Gemini
 * Text Domain: esn-user-avatars
 */

class esn_user_avatars {
    private $user_id_being_edited;

    public function __construct() {
        add_action('admin_init', array($this, 'admin_init'));
        add_shortcode('esn-user-avatars', array($this, 'shortcode'));
        add_filter('get_avatar_data', array($this, 'get_avatar_data'), 10, 2);
        add_filter('get_avatar', array($this, 'get_avatar'), 10, 6);
        add_filter('avatar_defaults', array($this, 'avatar_defaults'));

        add_action('wp_ajax_esn_upload_avatar', array($this, 'ajax_upload_avatar'));
        add_action('wp_ajax_esn_delete_avatar', array($this, 'ajax_delete_avatar'));

        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts() {
        wp_register_script('ajax-upload-avatar', '', array('jquery'), null, true);
        wp_localize_script('ajax-upload-avatar', 'EsnAjax', array('ajaxurl' => admin_url('admin-ajax.php')));

        $script = "
            jQuery(document).ready(function($) {
                var cropper;
                var modal = $('#cropper-modal');
                var image = document.getElementById('image-to-crop');

                // Юзер выбрал файл
                $('#esn-local-avatar').on('change', function(e) {
                    var files = e.target.files;
                    if (files && files.length > 0) {
                        var reader = new FileReader();
                        reader.onload = function(event) {
                            image.src = event.target.result;
                            modal.css('display', 'flex');
                            if (cropper) cropper.destroy();
                            cropper = new Cropper(image, {
                                aspectRatio: 1,
                                viewMode: 1,
                                guides: false,
                            });
                        };
                        reader.readAsDataURL(files[0]);
                    }
                });

                // Кнопка 'Сохранить' в модалке
                $('#apply-crop').on('click', function() {
                    var canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
                    canvas.toBlob(function(blob) {
                        var formData = new FormData();
                        formData.append('file', blob, 'avatar.webp');
                        formData.append('action', 'esn_upload_avatar');
                        formData.append('security', '" . wp_create_nonce('esn_user_avatar_nonce') . "');

                        $.ajax({
                            url: EsnAjax.ajaxurl,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: function() { $('#apply-crop').text('Грузим...'); },
                            success: function(response) {
                                if (response.success) {
                                    location.reload();
                                } else {
                                    alert(response.data);
                                    $('#apply-crop').text('Сохранить');
                                }
                            }
                        });
                    }, 'image/webp');
                });

                $('#cancel-crop').on('click', function() {
                    modal.hide();
                    if (cropper) cropper.destroy();
                    $('#esn-local-avatar').val('');
                });
                
                // Удаление
                $('#esn-delete-avatar').on('click', function() {
                    if(!confirm('Точно сносим аву?')) return;
                    $.ajax({
                        url: EsnAjax.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'esn_delete_avatar',
                            security: '" . wp_create_nonce('esn_user_avatar_nonce') . "'
                        },
                        success: function(response) {
                            if (response.success) {
                                location.reload();
                            }
                        }
                    });
                });
            });
        ";

        wp_add_inline_script('ajax-upload-avatar', $script);
        wp_enqueue_script('ajax-upload-avatar');
    }

    public function admin_init() {
        register_setting('discussion', 'esn_user_avatars_caps', array($this, 'sanitize_options'));
    }

    public function sanitize_options($input) {
        return array('esn_user_avatars_caps' => empty($input) ? 0 : 1);
    }

    public function get_avatar_data($args, $id_or_email) {
        if (!empty($args['force_default'])) return $args;

        $user_id = $this->get_user_id($id_or_email);
        if (empty($user_id)) return $args;

        $local_avatars = get_user_meta($user_id, 'esn_user_avatar', true);
        if (empty($local_avatars) || empty($local_avatars['full'])) return $args;

        $upload_path = wp_upload_dir();
        $user_avatar_url = str_replace($upload_path['basedir'], $upload_path['baseurl'], $local_avatars['full']);
        if ($user_avatar_url) {
            $args['url'] = $user_avatar_url;
            $args['found_avatar'] = true;
        }
        return apply_filters('esn_user_avatar_data', $args);
    }

    private function get_user_id($id_or_email) {
        if (is_numeric($id_or_email) && 0 < $id_or_email) return (int)$id_or_email;
        if (is_object($id_or_email) && isset($id_or_email->user_id)) return $id_or_email->user_id;
        if (is_string($id_or_email) && strpos($id_or_email, '@')) {
            $user = get_user_by('email', $id_or_email);
            return $user ? $user->ID : null;
        }
        return null;
    }

    public function get_avatar($avatar, $id_or_email, $size = 130, $default = '', $alt = false, $args = array()) {
        return apply_filters('esn_user_avatar', $avatar, $id_or_email);
    }

    public function ajax_upload_avatar() {
        check_ajax_referer('esn_user_avatar_nonce', 'security');
        if (!empty($_FILES['file']['name'])) {
            $user_id = get_current_user_id();
            $this->avatar_delete($user_id);
            $avatar_count = $this->get_avatar_count($user_id);
            $user_login = get_userdata($user_id)->user_login;
            $new_avatar_name = "{$user_login}_{$avatar_count}.webp";

            if (!function_exists('wp_handle_upload')) require_once ABSPATH . 'wp-admin/includes/file.php';

            $avatar = wp_handle_upload($_FILES['file'], array('test_form' => false));
            if (empty($avatar['file'])) wp_send_json_error('Ошибка загрузки.');

            $square_avatar_path = $this->create_square_avatar($avatar['file'], $new_avatar_name);
            update_user_meta($user_id, 'esn_user_avatar', array('full' => $square_avatar_path));
            wp_send_json_success();
        }
        wp_die();
    }

    public function ajax_delete_avatar() {
        check_ajax_referer('esn_user_avatar_nonce', 'security');
        $user_id = get_current_user_id();
        $this->avatar_delete($user_id);
        delete_user_meta($user_id, 'esn_user_avatar_count');
        wp_send_json_success();
        wp_die();
    }

    private function create_square_avatar($original_image_path, $new_avatar_name) {
        $image_editor = wp_get_image_editor($original_image_path);
        if (is_wp_error($image_editor)) return $original_image_path;
        
        $size = $image_editor->get_size();
        $new_size = min($size['width'], $size['height']);
        $x = ($size['width'] - $new_size) / 2;
        $y = ($size['height'] - $new_size) / 2;

        $image_editor->crop($x, $y, $new_size, $new_size);
        $path = $this->get_uploads_directory() . '/users-avatars/' . $new_avatar_name;
        $image_editor->save($path);
        @unlink($original_image_path);
        return $path;
    }

    private function get_uploads_directory() { return wp_upload_dir()['basedir']; }

    private function get_avatar_count($user_id) {
        $avatars = get_user_meta($user_id, 'esn_user_avatar_count', true) ?: 0;
        update_user_meta($user_id, 'esn_user_avatar_count', $avatars + 1);
        return $avatars + 1;
    }

    public function shortcode() {
        if (!is_user_logged_in()) return;
        $user_id = get_current_user_id();

        ob_start();
        ?>
        <style>

            
            /* Главная обертка авы */
            .esn-avatar-preview-wrapper {
                position: relative;
                width: 150px;
                height: 150px;
                border-radius: 50%;
                overflow: hidden;
                cursor: pointer;
                background: #000;
                margin: 0 auto;
            }

            .esn-avatar-preview-wrapper img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: all 0.3s ease;
                display: block;
            }

            /* Оверлей с твоим SVG */
            .esn-avatar-overlay {
                position: absolute;
                top: 0; left: 0; width: 100%; height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, 0);
                opacity: 0;
                transition: all 0.3s ease;
            }

            .esn-avatar-overlay svg {
                width: 42px;
                height: 42px;
                fill: #fff;
                transform: translateY(10px);
                transition: all 0.3s ease;
            }

            /* Ховер эффекты */
            .esn-avatar-preview-wrapper:hover img {
                filter: brightness(0.7);
            }

            .esn-avatar-preview-wrapper:hover .esn-avatar-overlay {
                opacity: 1;
            }

            .esn-avatar-preview-wrapper:hover .esn-avatar-overlay svg {
                transform: translateY(0);
            }

            /* Прячем инпут */
            #esn-local-avatar {
                display: none;
            }

            /* Модалка Cropper */
            #cropper-modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); z-index:99999; align-items:center; justify-content:center; padding: 20px; }
            .cropper-content { background: #1a1a1a; padding:25px; border-radius:15px; max-width:500px; width:100%; text-align:center; }
            .cropper-box { max-height:400px; margin-bottom:20px; overflow:hidden; border-radius:8px; background: #000; }
            #image-to-crop { max-width:100%; }
            .cropper-btns { display:flex; gap:10px; justify-content:center; }
            .cropper-btns button { padding: 10px 20px; border-radius: 8px; cursor: pointer; border: none; font-weight: 600; }
            #apply-crop { background: #fff; color: #000; }
            #cancel-crop { background: #333; color: #fff; }
        </style>

        <div class="esn-avatar-editor">
            <div class="esn-avatar-preview-wrapper" onclick="document.getElementById('esn-local-avatar').click();">
                <?php echo get_avatar($user_id, 150, '', '', array('class' => 'avatar-img')); ?>
                <div class="esn-avatar-overlay">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M24,12c0,6.617-5.383,12-12,12-.815,0-1.631-.083-2.426-.246-.541-.111-.89-.64-.778-1.181,.111-.541,.638-.89,1.181-.778,.662,.136,1.343,.205,2.023,.205,5.514,0,10-4.486,10-10S17.514,2,12,2c-.643,0-1.286,.061-1.912,.182-.546,.103-1.067-.25-1.171-.792-.104-.542,.25-1.066,.792-1.171,.75-.145,1.521-.218,2.291-.218,6.617,0,12,5.383,12,12Zm-12.013,5.997s.009,.003,.013,.003c.005,0,.009-.003,.014-.003,.564-.004,1.127-.219,1.558-.648l3.136-3.142c.39-.391,.39-1.024-.002-1.415-.391-.39-1.023-.389-1.414,.001l-2.292,2.297V7c0-.552-.448-1-1-1s-1,.448-1,1V15.09l-2.292-2.297c-.39-.391-1.023-.392-1.415-.001-.391,.39-.392,1.023-.001,1.415l3.137,3.143c.43,.43,.993,.645,1.558,.648Zm-7.288,.837c-.377-.404-1.01-.424-1.413-.046-.403,.377-.424,1.01-.046,1.413,.562,.6,1.188,1.144,1.859,1.617,.175,.123,.376,.182,.575,.182,.314,0,.624-.148,.819-.424,.318-.452,.209-1.076-.242-1.394-.56-.394-1.082-.848-1.551-1.348Zm-2.532-5.007c-.1-.543-.621-.904-1.165-.802-.543,.1-.902,.622-.802,1.165,.141,.762,.356,1.512,.641,2.229,.156,.393,.532,.632,.93,.632,.123,0,.247-.023,.368-.071,.514-.204,.765-.785,.561-1.298-.237-.597-.416-1.221-.533-1.855ZM5.099,2.182c-.671,.473-1.297,1.017-1.859,1.617-.378,.403-.357,1.036,.046,1.413,.193,.181,.438,.271,.684,.271,.267,0,.533-.106,.729-.316,.469-.5,.991-.954,1.551-1.348,.452-.318,.56-.942,.242-1.394-.317-.451-.94-.56-1.394-.242ZM2.139,7.02c-.516-.205-1.095,.047-1.298,.561-.285,.717-.5,1.467-.641,2.229-.1,.543,.259,1.065,.802,1.165,.062,.011,.123,.017,.183,.017,.473,0,.894-.337,.982-.818,.117-.634,.296-1.258,.533-1.855,.204-.513-.047-1.095-.561-1.298Z"/>
                    </svg>
                </div>
            </div>

            <input type="file" id="esn-local-avatar" accept="image/*" />
            
            <div style="text-align: center; margin-top: 15px;">
                <a href="javascript:void(0);" id="esn-delete-avatar" style="color: #666; font-size: 12px; text-decoration: none;">Удалить текущее фото</a>
            </div>
        </div>

        <div id="cropper-modal">
            <div class="cropper-content">
                <h3 style="color:#fff; margin-top:0;">Настройка аватара</h3>
                <div class="cropper-box">
                    <img id="image-to-crop" src="">
                </div>
                <div class="cropper-btns">
                    <button type="button" id="cancel-crop">Отмена</button>
                    <button type="button" id="apply-crop">Сохранить</button>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function avatar_defaults($avatar_defaults) {
        remove_action('get_avatar', array($this, 'get_avatar'));
        return $avatar_defaults;
    }

    public function avatar_delete($user_id) {
        $old_avatars = get_user_meta($user_id, 'esn_user_avatar', true);
        if (is_array($old_avatars) && !empty($old_avatars['full']) && file_exists($old_avatars['full'])) {
            @unlink($old_avatars['full']);
        }
        delete_user_meta($user_id, 'esn_user_avatar');
    }
}

$esn_user_avatars = new esn_user_avatars;

// Хелперы
define('DEFAULT_AVATAR_URL', get_template_directory_uri() . '/assets/img/no-avatar.png');

add_filter('get_avatar', function($avatar) {
    return preg_replace("/http.*?gravatar\.com[^\']*/", DEFAULT_AVATAR_URL, $avatar);
});