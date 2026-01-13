<?php
/**
 * Template name: Темы сайта
 *
 * Page with all site users
 *
 * @package Esenin
 */

get_header(); 

$args = array(
			'taxonomy' => 'category',
			'orderby'  => 'count',
			'order'    => 'DESC',
		);
$categories = get_categories( $args );

?>

<div id="primary" class="es-content-area">
 <div class="es-page-mw">
	<?php
	/**
	 * The esn_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_before' );
	?>
	
                  
<div class="es-page-themes-list">
	<div class="all-themes-header">
       <span class="all-themes-title">	
	   <?php echo esc_attr__( 'Темы сайта', 'esenin' ); ?>
	   </span>	   
	</div>
				<div class="es-themes-list__wrapper">
					<?php
					foreach ( $categories as $category ) {
						$esn_category_logo = get_term_meta( $category->term_id, 'esn_category_logo', true );
						$category_id = $category->term_id;
						?>
						
	            <div class="d-flex align-items-center justify-content-between m-0 es-theme-item">
                    <div class=" d-flex align-items-center">
							<?php if ( $esn_category_logo ) { ?>
								
									<div class="es-theme-item__logo me-2 flex-shrink-0">
									<a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>">
									<?php
									esn_get_retina_image(
										$esn_category_logo,
										array(
											'alt'   => esc_attr( $category->name ),
											'title' => esc_attr( $category->name ),
										)
									);
									?>
									</a>
									</div>
								
							<?php } ?>
														
							<div class="flex-grow-1">
                               <a href="<?php echo get_category_link($category->term_id); ?>" class="es-theme-item_name">
				                  <?php echo $category->cat_name; ?>
			                   </a>
				                <span class="es-theme-item_count gap-3 d-none d-md-flex">
				                  <?php echo num_decline( (int) $category->count, 'статья,статьи,статей', 'esenin' ); ?>
								  <?php echo do_shortcode('[cat_subscribe_count category_id="' . $category_id . '"]'); ?>
				                </span>
								
		                    </div>
							
					</div>
                  <div class="es-theme-item_right d-flex align-items-center">
                      <div class="theme-subscribe">					  
					  <?php echo do_shortcode('[cat_subscribe category_id="' . $category_id . '"]'); ?>
					  </div>
                  </div>
              </div>				
						
						
						
					<?php } ?>
				</div>
</div>


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


