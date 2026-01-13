<?php
/**
 * Template part user not logged
 *
 * @package Esenin
 */
?>

<!-- Кнопка --> 
<a class="login-modal-open es-button esn-modal-open" href="#login"><?php echo esc_html__( 'Войти', 'esenin' ); ?></a>
 
<!-- ВХОД -->    
<div id="login" class="modal">
  <div class="modal__window">
    <div class="modal__window_header">
	    <div class="modal__window_header-title">
	     <?php esc_html_e('Авторизация', 'esenin'); ?>
		</div>
		<a class="modal__close" onclick="closeModal()">
	    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/>
        </svg>
	    </a>
	</div>
      <div class="modal__window_body">
        <?php echo do_shortcode('[esenin_login]'); ?>
	  </div>
  </div>    
</div>


<!-- СБРОС ПАРОЛЯ -->    
<div id="passreset" class="modal">
  <div class="modal__window">
    <div class="modal__window_header">
	    <div class="modal__window_header-title">
	     <?php _e('Сброс пароля', 'esenin'); ?>
		</div>
		<a class="modal__close" onclick="closeModal()">
	    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/>
        </svg>
	    </a>
	</div>
      <div class="modal__window_body">  
        <?php echo do_shortcode('[esenin_passreset]'); ?>
	  </div>
    </div> 	
</div>




<!-- РЕГИСТРАЦИЯ -->    
<div id="register" class="modal">
  <div class="modal__window">
    <div class="modal__window_header">
	    <div class="modal__window_header-title">
	     <?php _e('Регистрация', 'esenin'); ?>
		</div>
		<a class="modal__close" onclick="closeModal()">
	    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/>
        </svg>
	    </a>
	</div>
      <div class="modal__window_body">       
		<?php echo do_shortcode('[register]'); ?>
      </div>
	</div>
</div>

<!-- НОВЫЙ ПАРОЛЬ (Пока не нужен. Отключён.) -->    
<div id="new-password" class="modal">
  <div class="modal__window">
    <div class="modal__window_header">
	    <div class="modal__window_header-title">
	     <?php esc_html_e('Новый пароль', 'esenin'); ?>
		</div>
		<a class="modal__close" onclick="closeModal()">
	    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/>
        </svg>
	    </a>
	</div>
      <div class="modal__window_body">	  
        <?php echo do_shortcode('[esenin_custom_passreset]'); ?>
	  </div>
    </div> 	
</div>

<!-- Предложение подтвердить аккаунт -->    
<div id="verify-profile" class="modal">
  <div class="modal__window">
    <div class="modal__window_header">
	    <div class="modal__window_header-title">
	     <?php esc_html_e('Подтвердите почту', 'esenin'); ?>
		</div>
		<a class="modal__close" onclick="closeModal()">
	    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/>
        </svg>
	    </a>
	</div>
      <div class="modal__window_body">
        <?php esc_html_e('Мы отправили вам письмо с подтверждением вашего адреса электронной почты. Пожалуйста, проверьте вашу входящую почту или спам.', 'esenin'); ?>
	  </div>
  </div>    
</div>

<!-- Истёкший токен -->    
<div id="email-token-error" class="modal">
  <div class="modal__window">
    <div class="modal__window_header">
	    <div class="modal__window_header-title">
	     <?php esc_html_e('Упс..', 'esenin'); ?>
		</div>
		<a class="modal__close" onclick="closeModal()">
	    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/>
        </svg>
	    </a>
	</div>
      <div class="modal__window_body">
        <?php esc_html_e('Ссылка подтверждения больше недействительна или уже была использована.', 'esenin'); ?>
	  </div>
  </div>    
</div>

<!-- ... -->    
<div id="hello-bot" class="modal">
  <div class="modal__window">
    <div class="modal__window_header">
	    <div class="modal__window_header-title">
	     <?php esc_html_e('Упс.. Бот..', 'esenin'); ?>
		</div>
		<a class="modal__close" onclick="closeModal()">
	    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/>
        </svg>
	    </a>
	</div>
      <div class="modal__window_body">
        <?php esc_html_e('Сайт не готов принять такую важную персону.', 'esenin'); ?>
	  </div>
  </div>    
</div>

<!-- ... -->    
<div id="verify-email-error" class="modal">
  <div class="modal__window">
    <div class="modal__window_header">
	    <div class="modal__window_header-title">
	     <?php esc_html_e('Нам жаль..', 'esenin'); ?>
		</div>
		<a class="modal__close" onclick="closeModal()">
	    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/>
        </svg>
	    </a>
	</div>
      <div class="modal__window_body">
        <?php esc_html_e('Не удалось отправить письмо для подтверждения. Пожалуйста, попробуйте позже.', 'esenin'); ?>
	  </div>
  </div>    
</div>