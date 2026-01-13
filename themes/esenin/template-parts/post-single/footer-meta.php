<?php
/**
 * @version 1.0
 */
 $comments_number = get_comments_number($post->ID);
 
 $share_url = get_permalink();			
 $title_post = rawurlencode( html_entity_decode( get_the_title( $post->ID ) ) );
 $excerpt_post = rawurlencode( html_entity_decode( get_the_excerpt( $post->ID ) ) );
 $image_post_url = get_the_post_thumbnail_url( $post->ID );

 $vk_share_url  = esc_url( 'https://vk.com/share.php?url=' . $share_url . '&image=' . $image_post_url . '&title=' . $title_post . '&comment=' . $excerpt_post, null, '' );
 $tg_share_url = esc_url( 'https://t.me/share/url?url=' . $share_url . '&text=' . $title_post, null, '' );	
?>

<div class="post-card p-0 d-flex align-items-center justify-content-center gap-3 pc-footer-meta">

<!-- Icon like -->
<?php if ( is_user_logged_in() ) { ?>
    <?php echo do_shortcode('[like_button]'); ?>
	<?php } else { ?>
	  <?php echo '<a href="' . wp_login_url() . '">' . do_shortcode('[like_button]') . '</a>'; ?>
<?php } ?>

<!-- Icon comments -->
  <a href="<?php comments_link() ?>" class="comments_link">
  <div class="pc-footer-button d-flex justify-content-center align-items-center">
   
    <?php if ($comments_number == 0) { ?>
		  <span class="pc-footer-button_icon">
		   <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="18" height="18">  
             <path d="M10.8,19.2c1.462,0,8.4.152,9.6-2.4.607-1.291-3.47-1.292-2.4-3.6a9.714,9.714,0,0,0,1.2-3.6A9.6,9.6,0,1,0,0,9.6C0,14.9,4.3,19.2,10.8,19.2Z" transform="translate(2.4 2.4)" fill="none" stroke-miterlimit="10"/>
           </svg>
		  </span>
		  <span class="pc-footer-button_label d-none d-md-flex">
		    <?php esc_html_e( 'Ответить', 'esenin' ); ?>
		  </span>
	<?php } else { ?>
	      <span class="pc-footer-button_icon">
		   <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="18" height="18">  
             <path d="M10.8,19.2c1.462,0,8.4.152,9.6-2.4.607-1.291-3.47-1.292-2.4-3.6a9.714,9.714,0,0,0,1.2-3.6A9.6,9.6,0,1,0,0,9.6C0,14.9,4.3,19.2,10.8,19.2Z" transform="translate(2.4 2.4)" fill="none" stroke-miterlimit="10"/>
           </svg>
		  </span>
		  <span class="pc-footer-button_label">
		    <?php echo $comments_number; ?>
		  </span>
	<?php } ?>
   
  </div>
  </a>
  
<!-- Icon bookmarks -->
<?php echo do_shortcode('[esen_bookmark_button]'); ?>

 
<!-- Icon share -->
<div class="pc-footer-button d-flex justify-content-center align-items-center">
  <div class="dropdown">
  <span class="pc-footer-button_icon" title="<?php esc_html_e( 'Поделиться', 'esenin' ); ?>" data-bs-toggle="dropdown" aria-expanded="false"> 
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path stroke="none" d="M12,2a10.032,10.032,0,0,1,7.122,3H16a1,1,0,0,0-1,1h0a1,1,0,0,0,1,1h4.143A1.858,1.858,0,0,0,22,5.143V1a1,1,0,0,0-1-1h0a1,1,0,0,0-1,1V3.078A11.981,11.981,0,0,0,.05,10.9a1.007,1.007,0,0,0,1,1.1h0a.982.982,0,0,0,.989-.878A10.014,10.014,0,0,1,12,2Z"/><path stroke="none" d="M22.951,12a.982.982,0,0,0-.989.878A9.986,9.986,0,0,1,4.878,19H8a1,1,0,0,0,1-1H9a1,1,0,0,0-1-1H3.857A1.856,1.856,0,0,0,2,18.857V23a1,1,0,0,0,1,1H3a1,1,0,0,0,1-1V20.922A11.981,11.981,0,0,0,23.95,13.1a1.007,1.007,0,0,0-1-1.1Z"/></svg>
  </span>
  
  <ul class="dropdown-menu pc-footer-social-share">
    <li><a class="dropdown-item d-flex align-items-center gap-2" target="_blank" href="<?php call_user_func( 'printf', '%s', $vk_share_url ); ?>" title="<?php esc_attr_e( 'Поделиться Вконтакте', 'esenin' ); ?>">
	  <img alt="" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/social/vk.svg">
         <span><?php esc_attr_e( 'Вконтакте', 'esenin' ); ?></span>
	</a></li>
    <li><a class="dropdown-item d-flex align-items-center gap-2" target="_blank" href="<?php call_user_func( 'printf', '%s', $tg_share_url ); ?>" title="<?php esc_attr_e( 'Разместить в Telegram', 'esenin' ); ?>">
	  <img alt="" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/social/telegram.svg">
         <span><?php esc_attr_e( 'Telegram', 'esenin' ); ?></span>
	</a></li>
	<li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item es-share__copy-link  d-flex align-items-center gap-2" target="_blank" href="<?php echo esc_url( $share_url ); ?>" title="<?php esc_attr_e( 'Копировать ссылку', 'esenin' ); ?>">
		<i class="es-icon es-icon-link fs-5"></i>
		 <span><?php esc_attr_e( 'Копировать', 'esenin' ); ?></span>
	</a></li>
  </ul>
  
  </div>
</div>

     
<!-- Icon views -->
  <div class="ms-auto es-post-views">
    <div class="d-flex gap-2">
	<?php
    setPostViews(get_the_ID()); // ПОДСЧЁТ ПРОСМОТРОВ
	if (getPostViews($post->ID) >= 0) { ?>
		<div class="pc-footer-button d-flex justify-content-center align-items-center">
		  <span class="pc-footer-button_icon">
		    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18"><path stroke="none" d="M23.271,9.419C21.72,6.893,18.192,2.655,12,2.655S2.28,6.893.729,9.419a4.908,4.908,0,0,0,0,5.162C2.28,17.107,5.808,21.345,12,21.345s9.72-4.238,11.271-6.764A4.908,4.908,0,0,0,23.271,9.419Zm-1.705,4.115C20.234,15.7,17.219,19.345,12,19.345S3.766,15.7,2.434,13.534a2.918,2.918,0,0,1,0-3.068C3.766,8.3,6.781,4.655,12,4.655s8.234,3.641,9.566,5.811A2.918,2.918,0,0,1,21.566,13.534Z"/><path d="M12,7a5,5,0,1,0,5,5A5.006,5.006,0,0,0,12,7Zm0,8a3,3,0,1,1,3-3A3,3,0,0,1,12,15Z" stroke="none"/></svg>
		  </span>
		  <span class="pc-footer-button_label">
		    <?php echo getPostViews($post->ID); ?>
		  </span>
		</div>
	<?php } ?>
	
	<?php if (is_plugin_active('front-editorjs/front_editorjs.php')) { ?>
	<?php
       $post_id = get_the_ID();
       $author_id = get_post_field('post_author', $post_id);
       $current_user = wp_get_current_user();
       $current_user_id = $current_user->ID;

// Проверяем права: админ, редактор или автор своего поста
    if (is_user_logged_in() && (current_user_can('administrator') || current_user_can('editor') || ($current_user_id == $author_id && current_user_can('author')))) {
        $edit_link = home_url('/editor/?fred=edit&fred_id=' . $post_id);
        // Генерим ссылку на удаление (в корзину)
        $delete_link = get_delete_post_link($post_id);
        ?>
        
        <a href="<?php echo esc_url($edit_link); ?>" class="fred-edit-icon" title="<?php esc_attr_e('Редактировать пост', 'esenin'); ?>">
            <div class="pc-footer-button d-flex justify-content-center align-items-center esn-edit-icon">
                <span class="pc-footer-button_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">
                        <path stroke="none" d="m12,0C5.383,0,0,5.383,0,12s5.383,12,12,12,12-5.383,12-12S18.617,0,12,0Zm0,22c-5.514,0-10-4.486-10-10S6.486,2,12,2s10,4.486,10,10-4.486,10-10,10Zm1.379-15.621l-5.914,5.914c-.944.944-1.465,2.2-1.465,3.535v1.172c0,.553.447,1,1,1h1.172c1.335,0,2.591-.521,3.535-1.465l5.914-5.914c1.17-1.17,1.17-3.072,0-4.242s-3.072-1.17-4.242,0Zm-3.086,8.742c-.559.559-1.332.879-2.121.879h-.172v-.172c0-.789.32-1.562.879-2.121l3.457-3.457,1.414,1.414-3.457,3.457Zm5.914-5.914l-1.043,1.043-1.414-1.414,1.043-1.043c.391-.391,1.023-.391,1.414,0s.39,1.024,0,1.414Z"/>
                    </svg>												
                </span>
            </div>
        </a>

        <a href="<?php echo esc_url($delete_link); ?>" 
           class="fred-delete-icon" 
           title="<?php esc_attr_e('Удалить пост', 'esenin'); ?>" 
           onclick="return confirm('Точно удаляем?');">
            <div class="pc-footer-button d-flex justify-content-center align-items-center esn-delete-icon">
                <span class="pc-footer-button_icon">
                    <svg xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="18" height="18"><path stroke="none" d="M16,8a1,1,0,0,0-1.414,0L12,10.586,9.414,8A1,1,0,0,0,8,9.414L10.586,12,8,14.586A1,1,0,0,0,9.414,16L12,13.414,14.586,16A1,1,0,0,0,16,14.586L13.414,12,16,9.414A1,1,0,0,0,16,8Z"/><path stroke="none" d="M12,0A12,12,0,1,0,24,12,12.013,12.013,0,0,0,12,0Zm0,22A10,10,0,1,1,22,12,10.011,10.011,0,0,1,12,22Z"/></svg>
                </span>
            </div>
        </a>

    <?php } ?>
    
    <?php
    $hidden_content = get_post_meta($post_id, '_hidden_post', true);
    if ($hidden_content === '1') { ?> 
        <div class="pc-footer_icon-hidden pc-footer-button d-flex justify-content-center align-items-center" title="<?php esc_attr_e('Скрытый контент', 'esenin'); ?>">
            <span class="pc-footer-button_icon ">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="17"><path stroke="none" d="M19,8.424V7A7,7,0,0,0,5,7V8.424A5,5,0,0,0,2,13v6a5.006,5.006,0,0,0,5,5H17a5.006,5.006,0,0,0,5-5V13A5,5,0,0,0,19,8.424ZM7,7A5,5,0,0,1,17,7V8H7ZM20,19a3,3,0,0,1-3,3H7a3,3,0,0,1-3-3V13a3,3,0,0,1,3-3H17a3,3,0,0,1,3,3Z"/><path d="M12,14a1,1,0,0,0-1,1v2a1,1,0,0,0,2,0V15A1,1,0,0,0,12,14Z"/></svg>
            </span>
        </div>
    <?php } ?>
<?php } ?>
	 </div>
  </div>
</div>
