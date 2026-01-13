<?php
/**
 * Template name: Настройки профиля
 *
 * Page edit profile
 *
 * @package Esenin
 */
global $user_ID;
global $current_user, $wp_roles;
wp_get_current_user();
 
if( !$user_ID ) {
	header('location:' . site_url() . '/#login');
	exit;
} else {
	$userdata = get_user_by( 'id', $user_ID );
}

 get_header(); 
?>

<div id="primary" class="edit-profile es-content-area">
<div class="es-page-mw">
	<?php
	/**
	 * The esn_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_before' );
	?>



	
	<?php
if( isset($_GET['status']) ) :
	switch( $_GET['status'] ) :
		case 'ok':{
			echo '<div class="alert alert-success">'.esc_attr__( 'Ваш профиль обновлён.', 'esenin' ).'</div>';
			break;
		}
		case 'exist':{
			echo '<div class="alert alert-danger">'.esc_attr__( 'Пользователь с указанной почтой уже существует.', 'esenin' ).'</div>';
			break;
		}
		case 'short':{
			echo '<div class="alert alert-danger">'.esc_attr__( 'Пароль слишком короткий.', 'esenin' ).'</div>';
			break;
		}
		case 'wrongshift':{
			echo '<div class="alert alert-danger">'.esc_attr__( 'Пароли не совпадают.', 'esenin' ).'</div>';
			break;
		}
		case 'wrong':{
			echo '<div class="alert alert-danger">'.esc_attr__( 'Старый пароль неверен.', 'esenin' ).'</div>';
			break;
		}
		case 'required':{
			echo '<div class="alert alert-primary">'.esc_attr__( 'Пожалуйста, заполните все обязательные поля.', 'esenin' ).'</div>';
			break;
		}		
	endswitch;
endif;
?>
	
	


<ul class="nav nav-underline" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <div class="nav-link active" id="pills-general-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button" role="tab" aria-controls="pills-general" aria-selected="false"><?php echo esc_attr__( 'Основные', 'esenin' ); ?></div>
  </li>
   <li class="nav-item" role="presentation">
    <div class="nav-link" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password" type="button" role="tab" aria-controls="pills-password" aria-selected="false"><?php echo esc_attr__( 'Пароль', 'esenin' ); ?></div>
  </li>
  <li class="nav-item" role="presentation">
    <div class="nav-link" id="pills-social-tab" data-bs-toggle="pill" data-bs-target="#pills-social" type="button" role="tab" aria-controls="pills-social" aria-selected="false"><?php echo esc_attr__( 'Социальные ссылки', 'esenin' ); ?></div>
  </li>
  <li class="nav-item" role="presentation">
    <div class="nav-link" id="pills-avatar-tab" data-bs-toggle="pill" data-bs-target="#pills-avatar" type="button" role="tab" aria-controls="pills-avatar" aria-selected="false"><?php echo esc_attr__( 'Аватар', 'esenin' ); ?></div>
  </li>
</ul>


<div class="tab-body">
<form action="<?php echo get_template_directory_uri() ?>/inc/config/profile-update.php" method="POST" autocomplete="off">
<div class="tab-content" id="pills-tabContent">


  <div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab">
        <div class="mb-4">
		 <div class="fs-3 fw-bolder"><?php echo esc_attr__( 'Основные настройки', 'esenin' ); ?></div>
		 <div class="fs-6 text-secondary"><?php echo esc_attr__( 'Публичные данные, видимые в профиле.', 'esenin' ); ?></div>
		</div> 
		
		
		 <input class="form-control" type="text" name="first_name" placeholder="<?php echo esc_attr__( 'Имя', 'esenin' ); ?>" value="<?php echo $userdata->first_name ?>" />
	     <input class="form-control" type="text" name="last_name" placeholder="<?php echo esc_attr__( 'Фамилия', 'esenin' ); ?>" value="<?php echo $userdata->last_name ?>" />
	     
		  <!-- .form-display_name -->
        <select class="display_name" name="display_name" id="display_name">
		<?php
			$public_display = array();
			$public_display['display_nickname']  = $current_user->nickname;
			$public_display['display_username']  = $current_user->user_login;

			if ( !empty($current_user->first_name) )
				$public_display['display_firstname'] = $current_user->first_name;

			if ( !empty($current_user->last_name) )
				$public_display['display_lastname'] = $current_user->last_name;

			if ( !empty($current_user->first_name) && !empty($current_user->last_name) ) {
				$public_display['display_firstlast'] = $current_user->first_name . ' ' . $current_user->last_name;
				$public_display['display_lastfirst'] = $current_user->last_name . ' ' . $current_user->first_name;
			}

			if ( ! in_array( $current_user->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
				$public_display = array( 'display_displayname' => $current_user->display_name ) + $public_display;

			$public_display = array_map( 'trim', $public_display );
			$public_display = array_unique( $public_display );

			foreach ( $public_display as $id => $item ) {
		?>
			<option <?php selected( $current_user->display_name, $item ); ?>><?php echo $item; ?></option>
		<?php
			}
		?>
		</select>
	
		 <!-- .form-display_name -->
		 
		 <input class="form-control" type="email" name="email" placeholder="<?php echo esc_attr__( 'Почта', 'esenin' ); ?>" value="<?php echo $userdata->user_email ?>" /> 
         
		 <input class="form-control" type="text" name="esn_position" id="esn_position" placeholder="<?php echo esc_attr__( 'Увлечение', 'esenin' ); ?>" value="<?php echo get_user_meta($user_ID, 'esn_position', true ) ?>">
	     <input class="form-control" type="text" name="esn_location" id="esn_location" placeholder="<?php echo esc_attr__( 'Город', 'esenin' ); ?>" value="<?php echo get_user_meta($user_ID, 'esn_location', true ) ?>">
		
		 <textarea class="form-control" name="description" id="description" placeholder="<?php echo esc_attr__( 'О себе', 'esenin' ); ?>" rows="3" cols="50"><?php the_author_meta( 'description', $current_user->ID ); ?></textarea> 
       <div class="d-flex justify-content-end mt-3">
                 <button><?php echo esc_attr__( 'Сохранить', 'esenin' ); ?></button>
       </div>
  </div>
  <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab">
         <div class="mb-4">
		 <div class="fs-3 fw-bolder"><?php echo esc_attr__( 'Изменение пароля', 'esenin' ); ?></div>
		 <div class="fs-6 text-secondary"><?php echo esc_attr__( 'Для изменения пароля укажите старый.', 'esenin' ); ?></div>
		</div> 


	  <div class="password-show-hide">
       <input class="form-control" type="password" name="pwd1" id="user_pass" placeholder="<?php echo esc_attr__( 'Старый пароль', 'esenin' ); ?>" autocomplete="new-password" />	   	
        <svg onclick="togglePass()" id="view" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		   <title>Показать пароль</title>
		 <path d="M23.271,9.419C21.72,6.893,18.192,2.655,12,2.655S2.28,6.893.729,9.419a4.908,4.908,0,0,0,0,5.162C2.28,17.107,5.808,21.345,12,21.345s9.72-4.238,11.271-6.764A4.908,4.908,0,0,0,23.271,9.419Zm-1.705,4.115C20.234,15.7,17.219,19.345,12,19.345S3.766,15.7,2.434,13.534a2.918,2.918,0,0,1,0-3.068C3.766,8.3,6.781,4.655,12,4.655s8.234,3.641,9.566,5.811A2.918,2.918,0,0,1,21.566,13.534Z"/><path d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z"/>
		</svg>
        <svg onclick="togglePass()" hidden id="no_view" width="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		   <title>Скрыть пароль</title>
		 <path d="m14.828,19.071c.576.576.266,1.559-.534,1.707-.008.001-.016.003-.024.004-.322.059-.656-.053-.888-.285L2.216,9.332c-.361-.361-.397-.932-.083-1.334.004-.005.008-.011.013-.016.371-.474,1.08-.514,1.505-.088l11.177,11.177ZM.528,10.473c-.143.272-.261.514-.35.708-.237.521-.237,1.118,0,1.64.041.091.094.199.147.308l5.915,5.915c.782.782,1.758,1.346,2.833,1.603.086.021.173.04.261.059.537.116.902-.524.514-.912L.528,10.473Zm23.179,11.82c.391.391.391,1.023,0,1.414-.195.195-.451.293-.707.293s-.512-.098-.707-.293L.293,1.707C-.098,1.316-.098.684.293.293S1.316-.098,1.707.293l4.268,4.268c1.838-1.036,3.862-1.561,6.025-1.561,6.192,0,9.72,4.238,11.271,6.764.978,1.592.978,3.57,0,5.162-.632,1.029-1.678,2.473-3.178,3.753l3.614,3.614ZM7.455,6.041l1.788,1.788c1.94-1.283,4.586-1.071,6.293.636,1.707,1.708,1.919,4.353.636,6.293l2.502,2.502c1.368-1.135,2.322-2.45,2.894-3.381.581-.946.581-2.122,0-3.068-1.333-2.17-4.349-5.811-9.567-5.811-1.619,0-3.143.35-4.545,1.041Zm7.252,7.252c.531-1.115.336-2.492-.585-3.414-.922-.922-2.3-1.116-3.414-.585l4,4Z"/>
		</svg>
      </div>
		 
		 <input class="form-control" type="password" name="pwd2" placeholder="<?php echo esc_attr__( 'Новый пароль', 'esenin' ); ?>" />
	     <input class="form-control" type="password" name="pwd3" placeholder="<?php echo esc_attr__( 'Повторите новый пароль', 'esenin' ); ?>" />
        
	 <div class="d-flex justify-content-end mt-3">
                 <button><?php echo esc_attr__( 'Сохранить', 'esenin' ); ?></button>
     </div>
  </div>
   
  <div class="tab-pane fade" id="pills-social" role="tabpanel" aria-labelledby="pills-social-tab">
        <div class="mb-4">
		 <div class="fs-3 fw-bolder"><?php echo esc_attr__( 'Социальные сети', 'esenin' ); ?></div>
		 <div class="fs-6 text-secondary"><?php echo esc_attr__( 'Ссылки на ваши аккаунты в социальных сетях.', 'esenin' ); ?></div>
		</div> 
		
		<input class="form-control" type="text" name="esn_vk" id="esn_vk" placeholder="<?php echo esc_attr__( 'Вконтакте', 'esenin' ); ?>" value="<?php echo get_user_meta($user_ID, 'esn_vk', true ) ?>">
		<input class="form-control" type="text" name="esn_telegram" id="esn_telegram" placeholder="<?php echo esc_attr__( 'Telegram', 'esenin' ); ?>" value="<?php echo get_user_meta($user_ID, 'esn_telegram', true ) ?>">
		<input class="form-control" type="text" name="esn_instagram" id="esn_instagram" placeholder="<?php echo esc_attr__( 'Instagram', 'esenin' ); ?>" value="<?php echo get_user_meta($user_ID, 'esn_instagram', true ) ?>">
		<input class="form-control" type="text" name="esn_tiktok" id="esn_tiktok" placeholder="<?php echo esc_attr__( 'TikTok', 'esenin' ); ?>" value="<?php echo get_user_meta($user_ID, 'esn_tiktok', true ) ?>">
		<input class="form-control" type="text" name="esn_github" id="esn_github" placeholder="<?php echo esc_attr__( 'GitHub', 'esenin' ); ?>" value="<?php echo get_user_meta($user_ID, 'esn_github', true ) ?>">
		<input class="form-control" type="text" name="esn_youtube" id="esn_youtube" placeholder="<?php echo esc_attr__( 'YouTube', 'esenin' ); ?>" value="<?php echo get_user_meta($user_ID, 'esn_youtube', true ) ?>">
     <div class="d-flex justify-content-end mt-3">
                 <button><?php echo esc_attr__( 'Сохранить', 'esenin' ); ?></button>
     </div>
  </div>
  
   
</div> 

</form> 
 
  <div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade" id="pills-avatar" role="tabpanel" aria-labelledby="pills-avatar-tab">
       <?php /* <div class="mb-4">
		 <div class="fs-3 fw-bolder"><?php echo esc_attr__( 'Аватар', 'esenin' ); ?></div>
		 <div class="fs-6 text-secondary"><?php echo esc_attr__( 'Загрузите или измените аватар вашего профиля.', 'esenin' ); ?></div> 
		</div> */ ?>
		<?php echo do_shortcode('[esn-user-avatars]'); ?>		
  </div>
  </div>
</div>









<script>
$(document).ready(function(){
const pillsTab = document.querySelector('#pills-tab');
const pills = pillsTab.querySelectorAll('div[data-bs-toggle="pill"]');

pills.forEach(pill => {
  pill.addEventListener('shown.bs.tab', (event) => {
    const { target } = event;
    const { id: targetId } = target;
    
    savePillId(targetId);
  });
});

const savePillId = (selector) => {
  localStorage.setItem('activePillId', selector);
};

const getPillId = () => {
  const activePillId = localStorage.getItem('activePillId');
  
  // if local storage item is null, show default tab
  if (!activePillId) return;
  
  // call 'show' function
  const someTabTriggerEl = document.querySelector(`#${activePillId}`)
  const tab = new bootstrap.Tab(someTabTriggerEl);

  tab.show();
};

// get pill id on load
getPillId();
  });
  
  
 new SlimSelect({
  select: '#display_name',
  settings: {
    showSearch: false
  },
}) 
</script>



	<?php
	/**
	 * The esn_main_after hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_after' );
	?>
</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>