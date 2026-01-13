<?php
/**
 *
 * Auth forms.
 *
 * @package Esenin
 */
function custom_redirect_after_login($redirect_to, $request, $user) {
    if (isset($user->ID)) {
        return wp_get_referer();
    }
    return $redirect_to;
}
add_filter('login_redirect', 'custom_redirect_after_login', 10, 3);

function handle_login_redirect() {
    if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'logout' || $_REQUEST['action'] == 'lostpassword')) {
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return;
    }

    wp_redirect(home_url('#login'));
    exit();
}
add_action('login_init', 'handle_login_redirect', 10);

add_filter('authenticate', 'custom_authenticate_redirect', 101, 3);

function custom_authenticate_redirect($user, $username, $password) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (is_wp_error($user)) {
            $error_codes = join(',', $user->get_error_codes());

            $login_url = home_url('/#login');
            $login_url = add_query_arg('errno', $error_codes, $login_url);

            wp_redirect($login_url);
            exit;
        }
    }

    return $user;
}


add_action('wp_logout', 'custom_logout_redirect', 5);
function custom_logout_redirect() {
    wp_safe_redirect(wp_get_referer());
    exit;
}

// New role 'pending'
add_action('init', function() {
    add_role('pending', __('Неподтверждённый', 'esenin'), array());
});

add_action('wp_authenticate_user', function($user) {
    if ($user->roles && in_array('pending', $user->roles)) {
        return new WP_Error('authentication_failed', __('Пока отказано. Почта не подтверждена.', 'esenin'));
    }
    return $user;
});

add_action('template_redirect', function() {
    if (is_author()) {
        $author = get_queried_object();
        if (user_can($author, 'pending') && !current_user_can('administrator')) {
            wp_redirect(home_url());
            exit;
        }
    }
});
?>
<?php
/*
 * ВХОД ===========  Добавляем шорткод, его можно использовать в содержимом любой статьи или страницы, вставив [esenin_login]
 */
add_shortcode( 'esenin_login', 'esenin_render_login' );
 
function esenin_render_login() {
 
	if ( is_user_logged_in() ) {
		 return sprintf(__('Вы уже авторизованы на сайте. <a href="%s">Выйти</a>.', 'esenin'), wp_logout_url());
	}
 
	$return = '<div class="auth-form-container">';
 
	if ( isset( $_REQUEST['errno'] ) ) {
		
		$error_codes = explode( ',', $_REQUEST['errno'] );
 
		foreach ($error_codes as $error_code) {
            switch ($error_code) {
                case 'empty_username':
                    $return .= '<div class="alert alert-danger rounded-3">' . __('Вы не забыли указать свой логин?', 'esenin') . '</div>';
                    break;
                case 'empty_password':
                    $return .= '<div class="alert alert-danger rounded-3">' . __('Пожалуйста, укажите пароль.', 'esenin') . '</div>';
                    break;
                case 'invalid_username':
                    $return .= '<div class="alert alert-danger rounded-3">' . __('Таких пользователей на сайте нет.', 'esenin') . '</div>';
                    break;
                case 'incorrect_password':
                    $return .= '<div class="alert alert-danger rounded-3">' . __('Неверный пароль.', 'esenin') . '</div>';
                    break;
                case 'confirm':
                    $return .= '<div class="alert alert-danger rounded-3">' . __('Инструкции по сбросу пароля отправлены на вашу почту', 'esenin') . '</div>';
                    break;
                case 'changed':
                    $return .= '<div class="alert alert-success rounded-3">' . __('Пароль изменён', 'esenin') . '</div>';
                    break;
                case 'expiesnkey':
                case 'invalidkey':
                    $return .= '<div class="alert alert-danger rounded-3">' . __('Недействительный ключ.', 'esenin') . '</div>';
                    break;
				case 'authentication_failed':
                    $return .= '<div class="alert alert-danger rounded-3">' . __('Пока отказано. Почта не подтверждена.', 'esenin') . '</div>';
                    break;
            }
		}
	}
	
	$return .= '

 <form class="form-signin" name="loginform" id="loginform" action="/wp-login.php" method="post" autocomplete="off" >       
      <input class="form-control" type="text" name="log" id="user_login_res" placeholder="' . __('Логин', 'esenin') . '" required="" autofocus=""/>	   
	  <div class="password-show-hide">
      <input class="form-control" type="password" name="pwd" id="user_pass" placeholder="' . __('Пароль', 'esenin') . '" required="" autocomplete="new-password" value="" />	
        <svg onclick="togglePass()" id="view" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		   <title>' . __('Показать пароль', 'esenin') . '</title>
		 <path d="M23.271,9.419C21.72,6.893,18.192,2.655,12,2.655S2.28,6.893.729,9.419a4.908,4.908,0,0,0,0,5.162C2.28,17.107,5.808,21.345,12,21.345s9.72-4.238,11.271-6.764A4.908,4.908,0,0,0,23.271,9.419Zm-1.705,4.115C20.234,15.7,17.219,19.345,12,19.345S3.766,15.7,2.434,13.534a2.918,2.918,0,0,1,0-3.068C3.766,8.3,6.781,4.655,12,4.655s8.234,3.641,9.566,5.811A2.918,2.918,0,0,1,21.566,13.534Z"/><path d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z"/>
		</svg>
        <svg onclick="togglePass()" hidden id="no_view" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		   <title>' . __('Скрыть пароль', 'esenin') . '</title>
		 <path d="m14.828,19.071c.576.576.266,1.559-.534,1.707-.008.001-.016.003-.024.004-.322.059-.656-.053-.888-.285L2.216,9.332c-.361-.361-.397-.932-.083-1.334.004-.005.008-.011.013-.016.371-.474,1.08-.514,1.505-.088l11.177,11.177ZM.528,10.473c-.143.272-.261.514-.35.708-.237.521-.237,1.118,0,1.64.041.091.094.199.147.308l5.915,5.915c.782.782,1.758,1.346,2.833,1.603.086.021.173.04.261.059.537.116.902-.524.514-.912L.528,10.473Zm23.179,11.82c.391.391.391,1.023,0,1.414-.195.195-.451.293-.707.293s-.512-.098-.707-.293L.293,1.707C-.098,1.316-.098.684.293.293S1.316-.098,1.707.293l4.268,4.268c1.838-1.036,3.862-1.561,6.025-1.561,6.192,0,9.72,4.238,11.271,6.764.978,1.592.978,3.57,0,5.162-.632,1.029-1.678,2.473-3.178,3.753l3.614,3.614ZM7.455,6.041l1.788,1.788c1.94-1.283,4.586-1.071,6.293.636,1.707,1.708,1.919,4.353.636,6.293l2.502,2.502c1.368-1.135,2.322-2.45,2.894-3.381.581-.946.581-2.122,0-3.068-1.333-2.17-4.349-5.811-9.567-5.811-1.619,0-3.143.35-4.545,1.041Zm7.252,7.252c.531-1.115.336-2.492-.585-3.414-.922-.922-2.3-1.116-3.414-.585l4,4Z"/>
		</svg>
      </div>
	
  <div class="row mb-2">
    <div class="col d-flex ms-1">
      <div class="form-check">
        <input class="form-check-input" name="rememberme" type="checkbox" id="rememberme" value="forever" checked />
		<label class="form-check-label" for="rememberme">' . __('Запомнить', 'esenin') . '</label>
      </div>
    </div>

    <div class="col">
       <a class="forgot-password esn-modal-open" href="#passreset">' . __('Забыли пароль?', 'esenin') . '</a>
    </div>
  </div>

  <input type="submit" name="wp-submit" id="wp-submit-log" class="btn btn-lg btn-primary btn-block" value="' . __('Войти', 'esenin') . '" /> 
  <input type="hidden" name="redirect_to" value="/" /> 

   '. do_shortcode("[esenin_social_login]") . ' 

  <div class="auth-text-bottom mt-4">
    ' . __('Нет аккаунта? ', 'esenin') . '<a class="esn-modal-open" href="#register">' . __('Регистрация', 'esenin') . '</a>    
  </div>
	
	</form>
';
    
	$return .= '</div>';

	return $return;
 
}
add_action('login_form', 'esenin_render_login');
?>
<?php
// Регистрация ============= шорткод [esenin_custom_registration]
add_filter('registration_redirect', 'my_redirect_home');
function my_redirect_home($registration_redirect) {
    return home_url('#register');
}

function red_registration_form($atts) {
    $atts = shortcode_atts(array(
        'role' => 'contributor',
    ), $atts, 'register');

    $role_number = $atts["role"];

    if (!is_user_logged_in()) {
        $registration_enabled = get_option('users_can_register');
        return $registration_enabled ? red_registration_fields() : __('<p>Регистрация отключена</p>', 'esenin');
    }
    return __('<p>У вас уже есть аккаунт на этом сайте, поэтому нет необходимости регистрироваться повторно.</p>', 'esenin');
}
add_shortcode('register', 'red_registration_form');

function red_registration_fields() {
    ob_start();
    ?>
    <div class="auth-form-container">
        <form id="red_registration_form" class="form-signin" action="" method="POST">
            <?php red_register_messages(); ?>
            
            <input class="form-control" name="red_user_login" id="red_user_login" 
                   placeholder="<?php _e('Логин', 'esenin'); ?>" 
                   type="text" required minlength="4" pattern="^[a-zA-Z0-9]+$"/>
            
            <div id="login-error-msg" style="color: #ff4d4d; font-size: 12px; display: none; margin-bottom: 10px;">
                <?php _e('Используй только английские буквы и цифры!', 'esenin'); ?>
            </div>

            <input name="red_user_email" id="red_user_email" class="form-control" placeholder="<?php _e('Почта', 'esenin'); ?>" type="email" required/>
            <input class="form-control red_user_hb" name="red_user_hb" id="red_user_hb" placeholder="<?php _e('Имя', 'esenin'); ?>"/>
            <input name="red_user_pass" id="password" class="form-control" placeholder="<?php _e('Пароль', 'esenin'); ?>" type="password" autocomplete="new-password" required/>
            <input name="red_user_pass_confirm" id="password_again" class="form-control" placeholder="<?php _e('Подтвердите пароль', 'esenin'); ?>" type="password" required/>
            <input type="hidden" name="red_csrf" value="<?php echo wp_create_nonce('red-csrf'); ?>"/>
            
            <div class="my-3">
                <input type="submit" id="wp-submit-reg" value="<?php _e('Зарегистрироваться', 'esenin'); ?>"/>
            </div>
            
            <script>
                // Простая проверка на лету
                document.getElementById('red_user_login').addEventListener('input', function (e) {
                    const regex = /^[a-zA-Z0-9]+$/;
                    const errorMsg = document.getElementById('login-error-msg');
                    if (this.value !== '' && !regex.test(this.value)) {
                        this.style.borderColor = '#ff4d4d';
                        errorMsg.style.display = 'block';
                    } else {
                        this.style.borderColor = '';
                        errorMsg.style.display = 'none';
                    }
                });
            </script>
            </form>
    </div>
    <?php
    return ob_get_clean();
}

function get_restricted_usernames() {
    return ['test', 'admin', 'user', 'moder', 'moderator', 'administrator', 'testuser', 'author', '1234', '1111'];
}

function is_valid_email_domain($email, $errors) {
    $valid_email_domains = [
        '@gmail.com', '@yahoo.com', '@outlook.com', '@gmx.com', '@mail.ru', '@yandex.ru',
        '@yandex.com', '@ya.ru', '@ya.com', '@yandex.by', '@inbox.ru', '@rambler.ru', 
        '@inbox.eu', '@icloud.com',
    ];

    foreach ($valid_email_domains as $d) {
        if (stripos($email, $d) !== false) return;
    }
    $errors->add('domain_whitelist_error', __('Почта не попадает под белый список', 'esenin'));
}

function is_valid_username($username, $errors) {
    // Если после sanitize_user осталось пусто или есть кириллица/спецсимволы
    if (empty($username) || !preg_match('/^[a-z0-9]+$/i', $username)) {
        $errors->add('username_invalid', __('Логин должен состоять только из латинских букв и цифр. Никакой кириллицы, это база.', 'esenin'));
    }
}

function is_valid_password($password, $password_confirm, $errors) {
    if (empty($password)) {
        $errors->add('password_empty', __('Пожалуйста, задайте пароль.', 'esenin'));
        return;
    }
    
    if (strlen($password) < 8) {
        $errors->add('password_short', __('Пароль слишком короткий. Минимум 8 символов, не экономь на безопасности.', 'esenin'));
    }

    // Проверка на совсем уж "мусорные" пароли (только цифры или только буквы)
    if (preg_match('/^[0-9]+$/', $password)) {
        $errors->add('password_weak_digits', __('Пароль только из цифр? Серьезно? Добавь букв.', 'esenin'));
    }
    
    if (preg_match('/^[a-z]+$/', $password)) {
        $errors->add('password_weak_letters', __('Добавь в пароль хотя бы одну цифру для приличия.', 'esenin'));
    }

    if ($password !== $password_confirm) {
        $errors->add('password_mismatch', __('Пароли не совпадают. Попробуй еще раз.', 'esenin'));
    }
}

function check_spam($ip, $username, $email) {
    $url = 'https://api.stopforumspam.org/api?ip=' . $ip . '&username=' . urlencode($username) . '&email=' . urlencode($email);
    $response = file_get_contents($url);
    $result = json_decode($response, true);

    return isset($result['success']) && $result['success'] === true && (
        isset($result['ip']['match']) && $result['ip']['match'] ||
        isset($result['username']['match']) && $result['username']['match'] ||
        isset($result['email']['match']) && $result['email']['match']
    );
}

function red_add_new_user() {
    if (isset($_POST["red_user_login"]) && wp_verify_nonce($_POST['red_csrf'], 'red-csrf')) {
        
        if (!empty($_POST['red_user_hb'])) {
            wp_redirect(home_url('#hello-bot'));
            exit;
        }
        
        $user_login   = sanitize_user($_POST["red_user_login"]);
        $user_email   = sanitize_email($_POST["red_user_email"]);
        $user_pass    = $_POST["red_user_pass"];
        $pass_confirm = $_POST["red_user_pass_confirm"];
        $user_ip      = $_SERVER['REMOTE_ADDR'];

        // 1. Проверки на СПАМ и существование
        if (check_spam($user_ip, $user_login, $user_email)) {
            red_errors()->add('spam_check_failed', __('СПАМ-фильтр: в регистрации отказано!', 'esenin'));
        }

        if (in_array(strtolower($user_login), get_restricted_usernames(), true)) {
            red_errors()->add('username_restricted', __('Выбранный логин недоступен', 'esenin'));
        }

        if (username_exists($user_login)) {
            red_errors()->add('username_unavailable', __('Логин уже занят', 'esenin'));
        }

        if (email_exists($user_email)) {
            red_errors()->add('email_used', __('Эта почта уже используется', 'esenin'));
        }

        // 2. Системная валидация логина и ПАРОЛЯ (теперь одинаково)
        is_valid_username($user_login, red_errors());
        is_valid_password($user_pass, $pass_confirm, red_errors()); // Валидация пароля тут!

        if (!is_email($user_email)) {
            red_errors()->add('email_invalid', __('Некорректная почта', 'esenin'));
        }

        is_valid_email_domain($user_email, red_errors());

        // Проверяем, есть ли ошибки на этом этапе
        $errors = red_errors()->get_error_messages();
        
        if (empty($errors)) {
            // Пытаемся создать юзера
            $new_user_id = wp_insert_user(array(
                'user_login'    => $user_login,
                'user_pass'     => $user_pass,
                'user_email'    => $user_email,
                'user_registered' => date('Y-m-d H:i:s'),
                'role'          => 'pending' 
            ));

            if (is_wp_error($new_user_id)) {
                // Если база данных не приняла юзера (редкий случай при наших проверках)
                red_errors()->add('registration_db_error', $new_user_id->get_error_message());
                return; 
            }

            // Юзер создан успешно, генерим токен и шлем письмо
            if ($new_user_id) {
                $verification_token = sha1($user_email . time());
                update_user_meta($new_user_id, 'email_verification_token', $verification_token);

                $verification_link = add_query_arg(array(
                    'action' => 'verify_email',
                    'token'  => $verification_token,
                    'user'   => $new_user_id
                ), home_url());

                $subject   = __('Подтверждение почты', 'esenin');
                $user_info = get_userdata($new_user_id);
                $user_name = !empty($user_info->display_name) ? $user_info->display_name : __('новый пользователь', 'esenin');
                $site_name = parse_url(get_option('home'), PHP_URL_HOST);

    $message = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: Roboto, sans-serif;
                font-size: 16px;
                line-height: 1.6;
                color: #212121;
                margin: 0;
                padding: 20px;
            }
            .header {
                font-size: 17px;
                margin-bottom: 10px;
            }
            .content {
                font-size: 16px;
                margin-bottom: 20px;
            }
            .button {
                padding: 0.625rem 1.125rem;
                background-color: #00ad64;
                color: #fff !important;
                text-decoration: none;
                border-radius: 10px;
                display: inline-block;
                font-size: 17px;
                font-weight: 500;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            }
			.button:hover { opacity: .91}
            .footer {
                font-size: 13px;
                color: #646464;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="header">
            ' . __('Приветствуем вас, ', 'esenin') . ' <strong>' . esc_html($user_name) . '</strong>!
        </div>
        <div class="content">
            ' . __('Чтобы завершить регистрацию на', 'esenin') . ' <strong>' . esc_html($site_name) . '</strong>, 
            ' . __('а также получить доступ ко всем нашим функциям, пожалуйста, подтвердите свой адрес электронной почты ниже:', 'esenin') . '
        </div>
        <a class="button" href="' . esc_url($verification_link) . '">' . __('Подтвердить', 'esenin') . '</a>
        <div class="footer">
            ' . __('Если это были не вы и кто-то по ошибке указал вашу почту, просто не обращайте внимания на это письмо. Неподтверждённый профиль будет удалён автоматически.', 'esenin') . '
        </div>
    </body>
    </html>
    ';

$headers = array('Content-Type: text/html; charset=UTF-8');

                if (wp_mail($user_email, $subject, $message, $headers)) {
                    wp_redirect(home_url('#verify-profile'));
                } else {
                    // Вот теперь это РЕАЛЬНАЯ ошибка отправки почты
                    wp_redirect(home_url('#verify-email-error'));
                }
                exit;
            }
        }
    }
}
add_action('init', 'red_add_new_user');

function red_errors() {
    static $wp_error;
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error());
}

function red_register_messages() {
    if ($codes = red_errors()->get_error_codes()) {
        echo '<div class="mb-2">';
        foreach ($codes as $code) {
            $message = red_errors()->get_error_message($code);
            echo '<div class="alert alert-danger rounded-3">' . $message . '</div>';
        }
        echo '</div>';
    }
}

function verify_email() {
    if (isset($_GET['action']) && $_GET['action'] === 'verify_email' && isset($_GET['token']) && isset($_GET['user'])) {
        $user_id = intval($_GET['user']);
        $token = sanitize_text_field($_GET['token']);


        $saved_token = get_user_meta($user_id, 'email_verification_token', true);

        if ($token === $saved_token) {
			$default_role = get_option('default_role', 'subscriber');
			
            wp_update_user(array(
                'ID' => $user_id,
                'role' => $default_role
            ));
            delete_user_meta($user_id, 'email_verification_token'); 

            wp_set_auth_cookie($user_id);
            wp_redirect(home_url('/author/' . get_userdata($user_id)->user_login . ''));
            exit;
        } else {
            wp_redirect(home_url('#email-token-error'));
			exit;
        }
    }
}
add_action('init', 'verify_email');

// Delete users pending
function delete_pending_users() {
    $args = array(
        'role' => 'pending',
        'orderby' => 'user_registered',
        'order' => 'ASC',
        'date_query' => array(
            array(
                'column' => 'user_registered',
                'before' => date('Y-m-d H:i:s', strtotime('-24 hours')),
            ),
        ),
    );
    $pending_users = get_users($args);
    
	foreach ($pending_users as $user) {
        wp_delete_user($user->ID);
    }
}
function register_pending_users_cleanup_schedule() {
    if (!wp_next_scheduled('delete_pending_users_event')) {
        wp_schedule_event(time(), 'daily', 'delete_pending_users_event');
    }
}
add_action('wp', 'register_pending_users_cleanup_schedule');
add_action('delete_pending_users_event', 'delete_pending_users');

function clear_pending_users_cleanup_schedule() {
    $timestamp = wp_next_scheduled('delete_pending_users_event');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'delete_pending_users_event');
    }
}
add_action('switch_theme', 'clear_pending_users_cleanup_schedule');
?>
<?php
// ВОССТАНОВЛЕНИЕ ПАРОЛЯ (ГЕНЕРАЦИЯ НОВОГО) ============= шорткод [esenin_passreset]
add_shortcode( 'esenin_passreset', 'esenin_passreset_function' );

function esenin_passreset_function() { ?> 
<?php
	ob_start();
	?>
   <div class="auth-form-container">
	<?php if ( !is_user_logged_in() ) {

		global $getPasswordError, $getPasswordSuccess;

		 if ( !empty( $getPasswordError ) ) { ?>
			<div class="alert alert-danger rounded-3">
				<?php echo $getPasswordError; ?>
			</div>
		<?php } ?>

		<?php if ( !empty( $getPasswordSuccess ) ) { ?>
			<br/>
			<div class="alert alert-success">
				<?php echo $getPasswordSuccess; ?>
			</div>
		<?php } ?>
       
		<form method="post" class="form-signin">
			
				
					<?php $user_login = isset($_POST['user_login']) ? $_POST['user_login'] : ''; ?>
					<input class="form-control red_user_hb" name="red_user_hb_lost" id="red_user_hb_lost" placeholder="<?php _e('Имя', 'esenin'); ?>"/>
					<input class="form-control" placeholder="<?php _e('Ваша почта', 'esenin'); ?>" type="text" name="user_login" id="user_login_reg" value="<?php echo $user_login; ?>" />			        
					<?php
					ob_start();
					do_action( 'lostpassword_form' );
					echo ob_get_clean();
					?>
					
					<?php wp_nonce_field('userGetPassword', 'formType'); ?>	
                    <input class="form-control red_user_hb" name="red_user_hb_res" id="red_user_hb_res" placeholder="<?php _e('Имя', 'esenin'); ?>"/>					
					<input type="submit" id="wp-submit-res" value="<?php _e('Создать новый пароль', 'esenin'); ?>" class="btn btn-lg btn-primary btn-block mb-4 mt-2">		            
		  <div class="auth-text-bottom">           
                      <p>
                       <?php _e('Передумали?', 'esenin'); ?>
                           <a class="esn-modal-open" href="#login">
                             <?php _e('Вернуться назад', 'esenin'); ?>
                           </a>
                      </p>
          </div>
		</form>
		
		</div>
		<?php
	}

	$forgot_pwd_form = ob_get_clean();
	return $forgot_pwd_form;
}

add_action('wp', 'esenin_passreset_user');

function esenin_passreset_user() {
	if ( isset( $_POST['formType'] ) && wp_verify_nonce( $_POST['formType'], 'userGetPassword' ) ) {

		if (!empty($_POST['red_user_hb_lost'] || $_POST['red_user_hb_res'])) {
            wp_redirect(home_url('#hello-bot'));
             exit;
        }
		
		global $getPasswordError, $getPasswordSuccess;

		$email = trim( $_POST['user_login'] );

		if (empty($email)) {
            $getPasswordError = __('Укажите адрес электронной почты.', 'esenin');
        } else if (!is_email($email)) {
            $getPasswordError = __('Неверный адрес электронной почты.', 'esenin');
        } else if (!email_exists($email)) {
            $getPasswordError = __('Пользователей с указанной почтой нет..', 'esenin');
		} else {

            $random_password = wp_generate_password( 12, false );

            $user = get_user_by( 'email', $email );

			$update_user = wp_update_user( array(
				'ID' => $user->ID,
				'user_pass' => $random_password
			) );

            if ( $update_user ) {
				$to = $email;
				$subject = __('Сгенерированный пароль', 'esenin');
				$sender = get_bloginfo( 'name' );

				$message = __('Ваш новый пароль: ', 'esenin') . $random_password;

                  /* $headers[] = 'MIME-Version: 1.0' . "\r\n";
                    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers[] = "X-Mailer: PHP \r\n";
                    $headers[] = 'From: ' . $sender . ' < ' . $email . '>' . "\r\n"; */
                    $headers = array( 'Content-Type: text/html; charset=UTF-8' );

                    $mail = wp_mail( $to, $subject, $message, $headers );
                    if ( $mail ) {
                    	$getPasswordSuccess =  __('Отлично! Проверьте свою почту на наличие нового пароля.', 'esenin') ;
                    }
                } else {
                	$getPasswordError = '<strong>' . __('Упс!', 'esenin') . ' </strong>' . __('Что-то пошло не так..', 'esenin');
                }
            }
        }
    }