<?php
/**
 * Template part user logged
 *
 * @package Esenin
 */
$current_user = wp_get_current_user(); 
$url = home_url( $_SERVER['REQUEST_URI'] );
?>


<div class="dropdown">
  <div class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
    <?php echo get_avatar( $user_ID, 100, '', '', array('class'=>'header-avatar object-fit-cover') ); ?>
  </div>
  <ul class="dropdown-menu dropdown-menu-end"> 
    <li><a class="dropdown-item user-card" href="/author/<?php global $current_user; wp_get_current_user(); echo $current_user->user_login;?>">          
        <div class="flex-shrink-0">
          <?php echo get_avatar( $user_ID, 100, '', '', array('class'=>'object-fit-cover') ); ?>
        </div>
        <div class="flex-grow-1 ms-2">
		  <div class="d-flex flex-column">
		    <div class="user-card_name d-inline-block text-truncate" style="max-width: 165px;" title="<?php echo $current_user->display_name ?>">
               <?php echo $current_user->display_name ?>
			</div>
<div class="user-card_subtitle">
    <?php 
    if ( ! empty( $current_user->roles ) ) {
        $wp_roles = wp_roles();
        $role_name = $wp_roles->role_names[ $current_user->roles[0] ];
        echo esc_html( $role_name );
    }
    ?>
</div>
          </div>			
        </div>  
    </a></li>
    <li><hr class="dropdown-divider"></li>
    <?php if (is_plugin_active('front-editorjs/front_editorjs.php')) { ?>
	<li><a class="dropdown-item d-inline-block d-md-none" href="/editor">
	  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m12 0a12 12 0 1 0 12 12 12.013 12.013 0 0 0 -12-12zm0 22a10 10 0 1 1 10-10 10.011 10.011 0 0 1 -10 10zm5-10a1 1 0 0 1 -1 1h-3v3a1 1 0 0 1 -2 0v-3h-3a1 1 0 0 1 0-2h3v-3a1 1 0 0 1 2 0v3h3a1 1 0 0 1 1 1z"/></svg>
	  <?php esc_attr_e( 'Добавить пост', 'esenin' ); ?></a></li>
	<li><a class="dropdown-item" href="/my-posts">
	  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M24,3.46c-.05-1.03-.54-1.99-1.34-2.64-1.43-1.17-3.61-1.01-4.98,.36l-1.67,1.67c-.81-.54-1.77-.84-2.77-.84-1.34,0-2.59,.52-3.54,1.46l-3.03,3.03c-.39,.39-.39,1.02,0,1.41s1.02,.39,1.41,0l3.03-3.03c.89-.89,2.3-1.08,3.42-.57L2.07,16.79c-.69,.69-1.07,1.6-1.07,2.57,0,.63,.16,1.23,.46,1.77l-1.16,1.16c-.39,.39-.39,1.02,0,1.41,.2,.2,.45,.29,.71,.29s.51-.1,.71-.29l1.16-1.16c.53,.3,1.14,.46,1.77,.46,.97,0,1.89-.38,2.57-1.07L22.93,6.21c.73-.73,1.11-1.73,1.06-2.76ZM5.8,20.52c-.62,.62-1.7,.62-2.32,0-.31-.31-.48-.72-.48-1.16s.17-.85,.48-1.16L16.08,5.61l2.32,2.32L5.8,20.52ZM21.52,4.8l-1.71,1.71-2.32-2.32,1.6-1.6c.37-.37,.85-.56,1.32-.56,.36,0,.7,.11,.98,.34,.37,.3,.58,.72,.61,1.19,.02,.46-.15,.92-.48,1.24Z"/>
      </svg>
	  <?php esc_attr_e( 'Мои статьи', 'esenin' ); ?></a></li>
	 <?php } ?>
	  	<li><a class="dropdown-item" href="/pm">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M23.119.882a2.966,2.966,0,0,0-2.8-.8l-16,3.37a4.995,4.995,0,0,0-2.853,8.481L3.184,13.65a1,1,0,0,1,.293.708v3.168a2.965,2.965,0,0,0,.3,1.285l-.008.007.026.026A3,3,0,0,0,5.157,20.2l.026.026.007-.008a2.965,2.965,0,0,0,1.285.3H9.643a1,1,0,0,1,.707.292l1.717,1.717A4.963,4.963,0,0,0,15.587,24a5.049,5.049,0,0,0,1.605-.264,4.933,4.933,0,0,0,3.344-3.986L23.911,3.715A2.975,2.975,0,0,0,23.119.882ZM4.6,12.238,2.881,10.521a2.94,2.94,0,0,1-.722-3.074,2.978,2.978,0,0,1,2.5-2.026L20.5,2.086,5.475,17.113V14.358A2.978,2.978,0,0,0,4.6,12.238Zm13.971,7.17a3,3,0,0,1-5.089,1.712L11.762,19.4a2.978,2.978,0,0,0-2.119-.878H6.888L21.915,3.5Z"/></svg>
	  <?php esc_attr_e( 'Сообщения', 'esenin' ); ?></a></li>
	<li><a class="dropdown-item" href="/my-subscriptions">
	  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="m18.214,9.098c.387.394.381,1.027-.014,1.414l-4.426,4.345c-.783.768-1.791,1.151-2.8,1.151-.998,0-1.996-.376-2.776-1.129l-1.899-1.867c-.394-.387-.399-1.02-.012-1.414.386-.395,1.021-.4,1.414-.012l1.893,1.861c.776.75,2.001.746,2.781-.018l4.425-4.344c.393-.388,1.024-.381,1.414.013Zm5.786,2.902c0,6.617-5.383,12-12,12S0,18.617,0,12,5.383,0,12,0s12,5.383,12,12Zm-2,0c0-5.514-4.486-10-10-10S2,6.486,2,12s4.486,10,10,10,10-4.486,10-10Z"/>
      </svg>
	  <?php esc_attr_e( 'Подписки', 'esenin' ); ?></a></li>
	<li><a class="dropdown-item" href="/bookmarks">
	  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path d="M20.137,24a2.8,2.8,0,0,1-1.987-.835L12,17.051,5.85,23.169a2.8,2.8,0,0,1-3.095.609A2.8,2.8,0,0,1,1,21.154V5A5,5,0,0,1,6,0H18a5,5,0,0,1,5,5V21.154a2.8,2.8,0,0,1-1.751,2.624A2.867,2.867,0,0,1,20.137,24ZM6,2A3,3,0,0,0,3,5V21.154a.843.843,0,0,0,1.437.6h0L11.3,14.933a1,1,0,0,1,1.41,0l6.855,6.819a.843.843,0,0,0,1.437-.6V5a3,3,0,0,0-3-3Z"/>
      </svg>
	  <?php esc_attr_e( 'Закладки', 'esenin' ); ?></a></li> 
    <li><a class="dropdown-item" href="/edit-profile">
	  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
	    <path d="M12,8a4,4,0,1,0,4,4A4,4,0,0,0,12,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,14Z"/><path d="M21.294,13.9l-.444-.256a9.1,9.1,0,0,0,0-3.29l.444-.256a3,3,0,1,0-3-5.2l-.445.257A8.977,8.977,0,0,0,15,3.513V3A3,3,0,0,0,9,3v.513A8.977,8.977,0,0,0,6.152,5.159L5.705,4.9a3,3,0,0,0-3,5.2l.444.256a9.1,9.1,0,0,0,0,3.29l-.444.256a3,3,0,1,0,3,5.2l.445-.257A8.977,8.977,0,0,0,9,20.487V21a3,3,0,0,0,6,0v-.513a8.977,8.977,0,0,0,2.848-1.646l.447.258a3,3,0,0,0,3-5.2Zm-2.548-3.776a7.048,7.048,0,0,1,0,3.75,1,1,0,0,0,.464,1.133l1.084.626a1,1,0,0,1-1,1.733l-1.086-.628a1,1,0,0,0-1.215.165,6.984,6.984,0,0,1-3.243,1.875,1,1,0,0,0-.751.969V21a1,1,0,0,1-2,0V19.748a1,1,0,0,0-.751-.969A6.984,6.984,0,0,1,7.006,16.9a1,1,0,0,0-1.215-.165l-1.084.627a1,1,0,1,1-1-1.732l1.084-.626a1,1,0,0,0,.464-1.133,7.048,7.048,0,0,1,0-3.75A1,1,0,0,0,4.79,8.992L3.706,8.366a1,1,0,0,1,1-1.733l1.086.628A1,1,0,0,0,7.006,7.1a6.984,6.984,0,0,1,3.243-1.875A1,1,0,0,0,11,4.252V3a1,1,0,0,1,2,0V4.252a1,1,0,0,0,.751.969A6.984,6.984,0,0,1,16.994,7.1a1,1,0,0,0,1.215.165l1.084-.627a1,1,0,1,1,1,1.732l-1.084.626A1,1,0,0,0,18.746,10.125Z"/>
	  </svg>
	  <?php esc_attr_e( 'Настройки', 'esenin' ); ?></a></li>
    <li><hr class="dropdown-divider"></li>
<?php if( current_user_can('moderator') || current_user_can('administrator') || current_user_can('editor') ) { 
    // Тянем данные
    $count_posts = wp_count_posts();
    $pending_posts = $count_posts->pending; // Статьи на проверке
    $pending_comments = wp_count_comments()->moderated; // Комменты на модерации
?>
    <li><a class="dropdown-item d-flex align-items-center justify-content-between" href="/wp-admin" target="blank">
        <div class="d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 18px; margin-right: 8px;">
                <path d="M23.9,11.437A12,12,0,0,0,0,13a11.878,11.878,0,0,0,3.759,8.712A4.84,4.84,0,0,0,7.113,23H16.88a4.994,4.994,0,0,0,3.509-1.429A11.944,11.944,0,0,0,23.9,11.437Zm-4.909,8.7A3,3,0,0,1,16.88,21H7.113a2.862,2.862,0,0,1-1.981-.741A9.9,9.9,0,0,1,2,13,10.014,10.014,0,0,1,5.338,5.543,9.881,9.881,0,0,1,11.986,3a10.553,10.553,0,0,1,1.174.066,9.994,9.994,0,0,1,5.831,17.076ZM7.807,17.285a1,1,0,0,1-1.4,1.43A8,8,0,0,1,12,5a8.072,8.072,0,0,1,1.143.081,1,1,0,0,1,.847,1.133.989.989,0,0,1-1.133.848,6,6,0,0,0-5.05,10.223Zm12.112-5.428A8.072,8.072,0,0,1,20,13a7.931,7.931,0,0,1-2.408,5.716,1,1,0,0,1-1.4-1.432,5.98,5.98,0,0,0,1.744-5.141,1,1,0,0,1,1.981-.286Zm-5.993.631a2.033,2.033,0,1,1-1.414-1.414l3.781-3.781a1,1,0,1,1,1.414,1.414Z"/>
            </svg>
            <?php esc_attr_e( 'Админ-панель', 'esenin' ); ?>
        </div>
        
        <?php if ($pending_posts > 0 || $pending_comments > 0) : ?>
            <div class="ms-2">
                <?php if ($pending_posts > 0) : ?>
                    <span class="badge rounded-pill bg-danger" title="Посты на модерации"><?php echo $pending_posts; ?></span>
                <?php endif; ?>
                <?php if ($pending_comments > 0) : ?>
                    <span class="badge rounded-pill bg-warning text-dark" title="Комменты на модерации"><?php echo $pending_comments; ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </a></li>
<?php } ?>
    <li><a class="dropdown-item" href="<?php echo wp_logout_url($url); ?>">
	  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
	    <path d="M11.476,15a1,1,0,0,0-1,1v3a3,3,0,0,1-3,3H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2H7.476a3,3,0,0,1,3,3V8a1,1,0,0,0,2,0V5a5.006,5.006,0,0,0-5-5H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H7.476a5.006,5.006,0,0,0,5-5V16A1,1,0,0,0,11.476,15Z"/><path d="M22.867,9.879,18.281,5.293a1,1,0,1,0-1.414,1.414l4.262,4.263L6,11a1,1,0,0,0,0,2H6l15.188-.031-4.323,4.324a1,1,0,1,0,1.414,1.414l4.586-4.586A3,3,0,0,0,22.867,9.879Z"/>
	  </svg>
	  <?php esc_attr_e( 'Выйти', 'esenin' ); ?></a></li>
  </ul>
</div>