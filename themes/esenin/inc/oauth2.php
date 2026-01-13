<?php
// Internal logic of authorization 
// integration through Yandex and Google services 
// for Esenin
defined('ABSPATH') or die('Доступ запрещён!');

// Settings page
add_action('admin_menu', 'oauth_settings_menu');
function oauth_settings_menu() {
    add_options_page(__('Вход через OAuth', 'esenin'), __('Вход через OAuth', 'esenin'), 'manage_options', 'oauth-settings', 'oauth_settings_page');
}

function oauth_settings_page() {
    ?>
    <div class="wrap esn-setting-oauth">
        <h1><?php _e('Настройка авторизации через сервисы (Esenin)', 'esenin'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('oauth-settings-group');
            do_settings_sections('oauth-settings-group');
            ?>

            <div class="oauth-settings-section oauth-settings-show">
                <h2><?php _e('Настройки отображения', 'esenin'); ?></h2>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Включить Google', 'esenin'); ?></th>
                        <td><input type="checkbox" name="enable_google" value="1" <?php checked(1, get_option('enable_google')); ?> /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Включить Яндекс', 'esenin'); ?></th>
                        <td><input type="checkbox" name="enable_yandex" value="1" <?php checked(1, get_option('enable_yandex')); ?> /></td>
                    </tr>
                </table>
            </div>

            <div class="oauth-settings-section">
                <h2><?php _e('Настройки Google', 'esenin'); ?></h2>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Client ID', 'esenin'); ?></th>
                        <td><input type="text" name="google_client_id" value="<?php echo esc_attr(get_option('google_client_id')); ?>" class="regular-text" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Client secret', 'esenin'); ?></th>
                        <td><input type="text" name="google_client_secret" value="<?php echo esc_attr(get_option('google_client_secret')); ?>" class="regular-text" /></td>
                    </tr>
                </table>
                <div class="oauth-settings-section-note">
                    <?php _e('Получить данные для настройки Google можно в', 'esenin'); ?> <a href="https://console.developers.google.com/" target="blank"><?php _e('консоли разработчика', 'esenin'); ?></a>.</br>
                    <?php _e('Ссылка для редиректа:', 'esenin'); ?> <strong>/wp-login.php?action=google_login</strong>
                </div>
            </div>

            <div class="oauth-settings-section">
                <h2><?php _e('Настройки Яндекс', 'esenin'); ?></h2>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('ClientID', 'esenin'); ?></th>
                        <td><input type="text" name="yandex_client_id" value="<?php echo esc_attr(get_option('yandex_client_id')); ?>" class="regular-text" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Client secret', 'esenin'); ?></th>
                        <td><input type="text" name="yandex_client_secret" value="<?php echo esc_attr(get_option('yandex_client_secret')); ?>" class="regular-text" /></td>
                    </tr>
                </table>
                <div class="oauth-settings-section-note">
                    <?php _e('Получить данные для настройки Яндекс можно в', 'esenin'); ?> <a href="https://oauth.yandex.ru/" target="blank"><?php _e('моих приложениях', 'esenin'); ?></a>.</br>
                    <?php _e('Ссылка для редиректа:', 'esenin'); ?> <strong>/wp-login.php?action=yandex_login</strong>
                </div>
            </div>
            <?php submit_button(); ?>
        </form>

        <style>
            .esn-setting-oauth h1 {
                margin: 20px 0 30px;
                font-size: 27px;
            }
            .oauth-settings-show {
                background-color: #fafafa !important;
                border: 3px solid #00ad64;
                box-shadow: 0 0 0 0 !important;
            }
            .oauth-settings-section {
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 20px;
                background-color: #fff;
                box-shadow: 0 0 #0000, 0 0 #0000, 0 0 #0000, 0 0 #0000, 0 1px 3px 0 #0000001a, 0 1px 2px -1px #0000001a !important;
            }
            .oauth-settings-section h2 {
                border-bottom: 1px solid #00ad64;
                padding: 20px;
                margin-left: -20px;
                margin-right: -20px;
                margin-top: -20px;
                font-size: 22px;
            }
            .form-table th {
                width: 250px;
            }
            .regular-text {
                width: 100%;
                max-width: 400px;
                box-sizing: border-box;
            }
        </style>
    </div>
    <?php
}

// Registering Settings
add_action('admin_init', 'register_oauth_settings');
function register_oauth_settings() {
    register_setting('oauth-settings-group', 'google_client_id');
    register_setting('oauth-settings-group', 'google_client_secret');
    register_setting('oauth-settings-group', 'yandex_client_id');
    register_setting('oauth-settings-group', 'yandex_client_secret');
    register_setting('oauth-settings-group', 'enable_google');
    register_setting('oauth-settings-group', 'enable_yandex');
}

// Shortcode for login
add_shortcode('esenin_social_login', 'render_oauth_login_buttons');
function render_oauth_login_buttons() {
    ob_start();

    $buttons = [];

     if (get_option('enable_yandex')) {
        $client_id = get_option('yandex_client_id');
        $redirect_uri = home_url('/wp-login.php?action=yandex_login');
        $url_yandex = 'https://oauth.yandex.ru/authorize?client_id=' . esc_attr($client_id) . '&response_type=code&redirect_uri=' . esc_url($redirect_uri);
        $buttons[] = '<a href="' . esc_url($url_yandex) . '" class="btn-social btn-auth-yandex">
                        <img src="' . esc_url(get_template_directory_uri() . '/assets/img/social/yandex.svg') . '" alt="' . esc_attr__('Продолжить с Яндекс', 'esenin') . '">
                         <div class="btn-auth-yandex--text">' . esc_attr__('Яндекс ID', 'esenin') . '</div>
                      </a>';
    }

    if (get_option('enable_google')) {
        $client_id = get_option('google_client_id');
        $redirect_uri = home_url('/wp-login.php?action=google_login');
        $url_google = 'https://accounts.google.com/o/oauth2/auth?client_id=' . esc_attr($client_id) . '&redirect_uri=' . esc_url($redirect_uri) . '&response_type=code&scope=email profile';
        $buttons[] = '<a href="' . esc_url($url_google) . '" class="btn-social btn-auth-google">
                        <img src="' . esc_url(get_template_directory_uri() . '/assets/img/social/google.svg') . '" alt="' . esc_attr__('Продолжить с Google', 'esenin') . '">
                          <div class="btn-auth-google--text">' . esc_attr__('Google', 'esenin') . '</div>
                      </a>';
    }

    if (!empty($buttons)) {
        echo '<div class="esenin-social-login">';
        echo '<span>' . esc_html__('или с помощью', 'esenin') . '</span>';
        echo '<div class="esenin-social-login-buttons">';
        echo implode('', $buttons);
        echo '</div>';
        echo '</div>';
    }

    return ob_get_clean();
}

add_action('init', 'handle_oauth_login');
function handle_oauth_login() {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'google_login':
                handle_google_login();
                break;
            case 'yandex_login':
                handle_yandex_login();
                break;
        }
    }
}

function handle_google_login() {
    if (!isset($_GET['code'])) {
        return;
    }
    oauth_callback('google');
}

function handle_yandex_login() {
    if (!isset($_GET['code'])) {
        return;
    }
    oauth_callback('yandex');
}

// General logic for authorization processing
function oauth_callback($service) {
    $client_id = get_option("{$service}_client_id");
    $client_secret = get_option("{$service}_client_secret");
    $redirect_uri = home_url("/wp-login.php?action={$service}_login");
    $code = sanitize_text_field($_GET['code']);

    $token_url = $service === 'google'
        ? 'https://oauth2.googleapis.com/token'
        : 'https://oauth.yandex.ru/token';

    $body = array(
        'code' => $code,
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'redirect_uri' => $redirect_uri,
        'grant_type' => 'authorization_code'
    );

    $response = wp_remote_post($token_url, array('body' => $body));

    if (is_wp_error($response)) {
        wp_die(__('Ошибка получения токена доступа от ', 'esenin') . ucfirst($service) . '.');
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (isset($body['error'])) {
        wp_die(__('Ошибка: ', 'esenin') . esc_html($body['error']));
    }

    $userinfo_url = $service === 'google'
        ? 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . esc_attr($body['access_token'])
        : 'https://login.yandex.ru/info?format=json&oauth_token=' . esc_attr($body['access_token']);

    $userinfo_response = wp_remote_get($userinfo_url);
    if (is_wp_error($userinfo_response)) {
        wp_die(__('Ошибка получения информации о пользователе от ', 'esenin') . ucfirst($service) . '.');
    }

    $userinfo = json_decode(wp_remote_retrieve_body($userinfo_response), true);
    handle_user_login($userinfo, $service);
}

// Data extraction
function handle_user_login($userinfo, $service) {
    $first_name = '';
    $last_name = '';
    $avatar = '';

    if ($service === 'google') {
        $first_name = $userinfo['given_name'] ?? '';
        $last_name = $userinfo['family_name'] ?? '';
        $avatar = $userinfo['picture'] ?? '';
    } elseif ($service === 'yandex') {
        $first_name = $userinfo['first_name'] ?? '';
        $last_name = $userinfo['last_name'] ?? '';

        if (isset($userinfo['default_avatar_id'])) {
            $avatar = 'https://avatars.yandex.net/get-yapic/' . $userinfo['default_avatar_id'] . '/islands-200';
        }
    }

    $email = $service === 'google' ? ($userinfo['email'] ?? '') : ($userinfo['default_email'] ?? '');
    $email = sanitize_email($email);

// Генерируем базовый логин по твоим правилам
$base_login = esenin_generate_base_login($first_name, $last_name, $email);

$login = esenin_ensure_unique_login($base_login);

$user = get_user_by('email', $email);

    if ($user) {

        $user_id = $user->ID;
        $update_data = array('ID' => $user_id);

        if (empty(get_user_meta($user_id, 'first_name', true))) {
            $update_data['first_name'] = sanitize_text_field($first_name);
        }
        if (empty(get_user_meta($user_id, 'last_name', true))) {
            $update_data['last_name'] = sanitize_text_field($last_name);
        }
        if (empty(get_user_meta($user_id, 'display_name', true))) {
            $update_data['display_name'] = trim("{$first_name} {$last_name}");
        }
		
        if (in_array($user->roles[0], ['pending'])) {
            $update_data['role'] = 'author';
        }

        wp_update_user($update_data);

        if ($avatar && !get_user_meta($user_id, 'esn_user_avatar', true)) {
            save_user_avatar($user_id, $avatar); 
        }
} else {
        $userdata = array(
            'user_login' => $login,
            'user_email' => $email,
            'user_pass'  => wp_generate_password( 16, false ), // ДОБАВЬ ЭТУ СТРОКУ
            'first_name' => sanitize_text_field($first_name),
            'last_name' => sanitize_text_field($last_name),
            'display_name' => trim("{$first_name} {$last_name}"),
            'role' => 'contributor',
        );

        $user_id = wp_insert_user($userdata);
        if (is_wp_error($user_id)) {
            wp_die(__('Ошибка создания пользователя: ', 'esenin') . $user_id->get_error_message());
        } else {
            save_user_avatar($user_id, $avatar);
        }
    }

    update_user_meta($user_id, 'login_method', ucfirst($service));
    update_location($user_id); 

    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : home_url();
    wp_redirect($redirect_to);
    exit;
}

function update_location($user_id) {
    $location = get_user_meta($user_id, 'esn_location', true);
    if (empty($location)) {
        $location = get_user_location();
        if ($location) {
            $translated_location = auto_translate_location($location);
            update_user_meta($user_id, 'esn_location', sanitize_text_field($translated_location));
        }
    }
}

// Save Avatar
function save_user_avatar($user_id, $avatar_url) {
    if (!filter_var($avatar_url, FILTER_VALIDATE_URL)) {
        return;
    }

    $response = wp_remote_get($avatar_url);
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return; 
    }

    $uploads = wp_upload_dir();
    $avatar_path = $uploads['basedir'] . '/users-avatars/';

    if (!file_exists($avatar_path)) {
        wp_mkdir_p($avatar_path);
    }

    $avatar_filename = uniqid('avatar_') . '.' . pathinfo($avatar_url, PATHINFO_EXTENSION);
    $local_avatar_path = $avatar_path . $avatar_filename;

    $file_data = file_get_contents($avatar_url);
    if ($file_data) {
        file_put_contents($local_avatar_path, $file_data);
        update_user_meta($user_id, 'esn_user_avatar', array('full' => $local_avatar_path));
    }
}

// Add a column to the admin panel (user list) to display the login method
add_filter('manage_users_columns', 'add_login_method_column');
function add_login_method_column($columns) {
    $columns['login_method'] = __('Способ входа', 'esenin');
    return $columns;
}

add_filter('manage_users_custom_column', 'show_login_method_column', 10, 3);
function show_login_method_column($value, $column_name, $user_id) {
    if ($column_name === 'login_method') {
        $method = esc_html(get_user_meta($user_id, 'login_method', true));
        switch (strtolower($method)) {
            case 'google':
                return __('Google', 'esenin');
            case 'yandex':
                return __('Яндекс', 'esenin');
            default:
                return $method;
        }
    }
    return $value;
}

// Deleting a user
add_action('delete_user', 'remove_user_data_on_delete');

function remove_user_data_on_delete($user_id) {
    $uploads = wp_upload_dir();
    $avatar_path = $uploads['basedir'] . '/users-avatars/';

    $user_avatar_meta = get_user_meta($user_id, 'esn_user_avatar', true);

    if (!empty($user_avatar_meta['full'])) {
        $avatar_file = $user_avatar_meta['full'];
        if (file_exists($avatar_file)) {
            unlink($avatar_file);
        }
    }

    delete_user_meta($user_id, 'esn_user_avatar');
    delete_user_meta($user_id, 'login_method');
    delete_user_meta($user_id, 'esn_location');
}

// Getting user location
function get_user_location() {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $response = wp_remote_get("http://ip-api.com/json/{$ip_address}");

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($data['city']) && !empty($data['country'])) {
        return "{$data['city']}, {$data['country']}";
    }

    return false;
}

// Setting the language for automatic translation
function auto_translate_location($location) {
    $locale = get_locale();
    $default_language = 'en';
    $targetLang = $locale !== $default_language ? $locale : $default_language;

    if (empty($location)) {
        return $location;
    }

    // URL for translation API
    $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl={$targetLang}&dt=t&q=" . urlencode($location);

    $response = wp_remote_get($url);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
        return $location; 
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($data[0][0][0])) {
        return $data[0][0][0];
    }

    return $location;
}

/**
 * Генерирует базу для логина на основе приоритетов
 */
function esenin_generate_base_login($first, $last, $email) {
    $first = esenin_transliterate(trim($first));
    $last = esenin_transliterate(trim($last));

    if (!empty($first) && !empty($last)) {
        $login = $first . $last; // 1. ИмяФамилия
    } elseif (!empty($first)) {
        $login = $first;         // 2. Только имя
    } elseif (!empty($last)) {
        $login = $last;         // 2. Только фамилия
    } else {
        $email_part = strtok($email, '@'); // 3. Часть почты
        $login = esenin_transliterate($email_part);
    }

    // Очищаем от всего, кроме букв и цифр, приводим к нижнему регистру
    $login = sanitize_user($login, true);
    $login = str_replace(' ', '', $login);
    $login = mb_strtolower($login);

    return !empty($login) ? $login : 'user';
}

/**
 * Проверяет логин на уникальность и инкрементирует хвост, если нужно
 */
function esenin_ensure_unique_login($login) {
    $original_login = $login;
    $counter = 1;

    // 4. Если занято — добавляем цифру пока не станет свободно
    while (username_exists($login)) {
        $login = $original_login . $counter;
        $counter++;
    }

    return $login;
}

/**
 * Простейший транслит, чтобы не тащить тяжелые библиотеки
 */
function esenin_transliterate($text) {
    $rus = ['а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я'];
    $lat = ['a','b','v','g','d','e','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch','sh','sch','','y','','e','yu','ya'];
    
    $text = str_replace($rus, $lat, mb_strtolower($text));
    return preg_replace('/[^a-z0-9]/', '', $text); // На выходе только чистый латинский движ
}