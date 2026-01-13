<?php
/**
 * Template part singular content
 *
 * @package Esenin
 */

$thumbnail_size          = 'esn-large';
/* $thumbnail_overlay_class = 'es-overlay-ratio es-ratio-landscape-16-9'; */
$thumbnail_overlay_class = '';

if ( 'uncropped' === esn_get_page_preview() ) {
	$thumbnail_size          = sprintf( '%s-uncropped', $thumbnail_size );
	$thumbnail_overlay_class = '';
}
?>

<div class="es-entry__wrap">

	<?php
	/**
	 * The esn_entry_wrap_start hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_entry_wrap_start' );
	
	
	?>
                                            
	<div class="es-entry__container">

		<?php
		/**
		 * The esn_entry_container_start hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'esn_entry_container_start' );
		?>

		<?php if ( get_post_status() === 'pending' ) : ?>
    <div class="esn-moderation-alert">
        <div class="esn-moderation-alert__icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
        </div>
        <div class="esn-moderation-alert__text">
            <strong>Пост на модерации.</strong> Сейчас его видишь только ты и админы РФПЛ.РФ. <br> Как только проверим — он залетит в общий эфир.
        </div>
    </div>
<?php endif; ?>
		
		<div class="es-entry__content-wrap">
			<?php
			/**
			 * The esn_entry_content_before hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'esn_entry_content_before' );
			?>
   <div class="es-post">
   
			<?php
              if ( is_single() ) : ?>
			<?php get_template_part( 'template-parts/post-card/header-meta' ); ?>
			<?php endif; ?>
			  <div class="es-entry-title-and-subtitle<?php echo is_single() ? '' : '--page'; ?>">
		         <?php
		          // Title.
		          the_title( '<h1 class="es-entry__title"><span>', '</span></h1>' );
		          // Subtitle.
		          esn_post_subtitle();
		           ?>
	          </div>
			  			  
			  <?php if ( has_post_thumbnail() ) { ?>
	             
		        
		            
	          
               <?php } ?>
			
			<div class="entry-content">
				<?php the_content(); ?>								
			</div>
						   
			<?php
              if ( is_single() ) : ?>
                 <div class="post-footer">   
                     <?php get_template_part( 'template-parts/post-single/footer-meta' ); ?>
                 </div>
            <?php endif; ?>
			
   </div>
		   
		   
		   
			<?php
			/**
			 * The esn_entry_content_after hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'esn_entry_content_after' );
			?>
		</div>

		<?php
		/**
		 * The esn_entry_container_end hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'esn_entry_container_end' );
		?>

	</div>

	<?php
	/**
	 * The esn_entry_wrap_end hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_entry_wrap_end' );
	?>
</div>
