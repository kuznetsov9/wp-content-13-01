<?php
/**
 * @version 1.0
 */


// Date posted and modified.
$lastmodified = get_the_modified_time('U');
$posted = get_the_time('U');

$posted_date_diff = human_time_diff($posted,current_time( 'U' )). ' ' . __('назад', 'esenin');
$posted_default_date = get_the_date('d.m.Y в H:i');

$modified_date_diff = human_time_diff($lastmodified,current_time('U')). ' ' . __('назад', 'esenin');
$modified_default_date = get_the_modified_date('d.m.Y в H:i');
?>

<div class="post-card__header">

 <div class="d-flex align-items-center justify-content-between m-0">
                    <div class="d-flex align-items-center">
						<div class="post-card-group-img">    
                          <a href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ) ?>" rel="author">
				             <?php echo get_avatar(get_the_author_meta("ID"));  ?>
	                      </a>
	 
	<?php 
      $categories = get_the_category();
      if( $categories ){
	foreach( $categories as $category ) {
		$esn_category_logo = get_term_meta( $category->term_id, 'esn_category_logo', true );
	}?>
	
	<?php if ( $esn_category_logo ) { ?>
	<div class="logo-theme">
	 <a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>">
      <?php esn_get_retina_image( $esn_category_logo,
										array(
											'alt'   => esc_attr( $category->name ),
											'title' => esc_attr( $category->name ),
										)
									);
									?>
     </a>									
    </div>
    <?php } ?>									
	<?php } ?>	
  </div>
  
   <div class="post-card-auth-theme">
    <span class="pc-author">
      <a href="<?php echo esc_url( get_author_posts_url( $post->post_author ) ) ?>" rel="author"> 
	    <?php 
		$author = get_the_author();
		echo $author; ?> 
	  </a>
	</span>
	
	<span class="pc-theme-date">	
     <?php if (!empty($categories)) { 
             echo the_category(', '); 
             echo '<span class="px-1">&#8226;</span>'; 
         } ?>
<?php
    echo "<span class='posted-date' title='$posted_default_date'>";
	  echo "$posted_date_diff";
	echo "</span>";
if ($lastmodified > $posted) { ?>
<span class="dropdown lastmodified-date">
  <span class="dropdown-toggle" id="lastmodified" data-bs-toggle="dropdown" aria-expanded="true">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M22.987,5.452c-.028-.177-.312-1.767-1.464-2.928-1.157-1.132-2.753-1.412-2.931-1.44-.237-.039-.479,.011-.682,.137-.071,.044-1.114,.697-3.173,2.438,1.059,.374,2.428,1.023,3.538,2.109,1.114,1.09,1.78,2.431,2.162,3.471,1.72-2.01,2.367-3.028,2.41-3.098,.128-.205,.178-.45,.14-.689Z"/>
          <path d="M12.95,5.223c-1.073,.968-2.322,2.144-3.752,3.564C3.135,14.807,1.545,17.214,1.48,17.313c-.091,.14-.146,.301-.159,.467l-.319,4.071c-.022,.292,.083,.578,.29,.785,.188,.188,.443,.293,.708,.293,.025,0,.051,0,.077-.003l4.101-.316c.165-.013,.324-.066,.463-.155,.1-.064,2.523-1.643,8.585-7.662,1.462-1.452,2.668-2.716,3.655-3.797-.151-.649-.678-2.501-2.005-3.798-1.346-1.317-3.283-1.833-3.927-1.975Z"/>
    </svg>
 </span>
  <div class="dropdown-menu" aria-labelledby="lastmodified">
    <div class="modified-details__title mb-2"><?php esc_html_e( 'Обновлено', 'esenin' ); ?></div>
	<div class="modified-details__value mb-3"><?php echo $modified_default_date; ?></div>
	<div class="modified-details__title mb-2"><?php esc_html_e( 'Опубликован', 'esenin' ); ?></div>
	<div class="modified-details__value"><?php echo $posted_default_date; ?></div>
  </div>
</span>
<?php } ?>	
	
	</span>
	
  </div>

</div>
								
							
        <div class="es-theme-item_right d-flex align-items-center">

    <?php 
    $current_post_categories = get_the_category(get_the_ID()); // Явно берем для текущего ID в цикле
    if ( ! empty( $current_post_categories ) ) {
        $main_cat = $current_post_categories[0]; 
        ?>
<?php if ( is_user_logged_in() ) : ?>
    <div class="esn-cat-subscribe-wrapper icon-only" data-cat-id="<?php echo esc_attr($main_cat->term_id); ?>">
        <?php echo do_shortcode('[cat_subscribe category_id="' . $main_cat->term_id . '"]'); ?>
    </div>
<?php endif; ?>
    <?php } ?>
    

			
       <?php
	   if (is_plugin_active('front-editorjs/front_editorjs.php')) {
         $post_id = get_the_ID();
         $nsfw_content = get_post_meta($post_id, '_nsfw_content', true);
           if ($nsfw_content === '1') { ?>  
            <div class="esn-icon-nsfw" title="<?php esc_attr_e('Контент для взрослых', 'esenin'); ?>">		   
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
  <path stroke="none" d="m10.125,14.125c0,1.861,1.514,3.375,3.375,3.375s3.375-1.514,3.375-3.375c0-.958-.405-1.819-1.049-2.434.491-.552.799-1.271.799-2.066,0-1.723-1.402-3.125-3.125-3.125s-3.125,1.402-3.125,3.125c0,.795.308,1.514.799,2.066-.643.615-1.049,1.476-1.049,2.434Zm3.375,1.375c-.758,0-1.375-.617-1.375-1.375s.617-1.375,1.375-1.375,1.375.617,1.375,1.375-.617,1.375-1.375,1.375Zm-1.125-5.875c0-.62.505-1.125,1.125-1.125s1.125.505,1.125,1.125-.505,1.125-1.125,1.125-1.125-.505-1.125-1.125Zm11.625,2.375c0,.553-.448,1-1,1h-1v1c0,.553-.448,1-1,1s-1-.447-1-1v-1h-1c-.552,0-1-.447-1-1s.448-1,1-1h1v-1c0-.553.448-1,1-1s1,.447,1,1v1h1c.552,0,1,.447,1,1Zm-17,4v-5.9l-.523.574c-.372.406-1.005.438-1.413.065-.408-.372-.438-1.004-.066-1.413l1.732-1.901c.372-.402.936-.532,1.439-.334.504.197.831.674.831,1.216v7.693c0,.553-.448,1-1,1s-1-.447-1-1Zm15.059,2.546c-2.227,3.415-5.987,5.454-10.059,5.454C5.383,24,0,18.617,0,12S5.383,0,12,0c4.071,0,7.832,2.039,10.059,5.454.302.462.171,1.082-.291,1.384-.464.3-1.083.171-1.384-.292-1.857-2.847-4.991-4.546-8.384-4.546C6.486,2,2,6.486,2,12s4.486,10,10,10c3.394,0,6.528-1.699,8.383-4.546.302-.462.921-.591,1.384-.292.463.302.593.922.292,1.384Z"/>
             </svg>	
            </div>			 
       <?php } } ?>



        </div>
 </div>			
	
</div>