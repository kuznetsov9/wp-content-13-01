<?php
/**
 * The template part for displaying off-canvas area.
 *
 * @package Esenin
 */

if ( esn_offcanvas_exists() ) {
	?>

	<div class="es-site-overlay"></div>

	<div class="es-offcanvas">
		<div class="es-offcanvas__header">
			<?php
			/**
			 * The esn_offcanvas_header_start hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'esn_offcanvas_header_start' );
			?>
            <div class="mt-3">
			<?php esn_component( 'header_logo' ); ?>
            </div>
			<nav class="es-offcanvas__nav">
				<span class="es-offcanvas__toggle" role="button" aria-label="<?php echo esc_attr__( 'Close mobile menu button', 'esenin' ); ?>"><i class="es-icon es-icon-x"></i></span>
			</nav>

			<?php
			/**
			 * The esn_offcanvas_header_end hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'esn_offcanvas_header_end' );
			?>
		</div>
		<aside class="es-offcanvas__sidebar">
			<div class="es-offcanvas__inner es-offcanvas__area es-widget-area">
				<?php
				$locations = get_nav_menu_locations();

				// Get menu by location.
				if ( isset( $locations['mobile_primary'] ) ) {

					
					if ( isset( $locations['mobile_primary'] ) ) {
						$location = $locations['mobile_primary'];
					}

					the_widget( 'WP_Nav_Menu_Widget', array( 'nav_menu' => $location ), array(
						'before_widget' => '<div class="widget %s">',
						'after_widget'  => '</div>',
					) );
				}

				// Get secondary menu by location.
				if ( isset( $locations['secondary'] ) ) {
					the_widget( 'WP_Nav_Menu_Widget', array( 'nav_menu' => $locations['secondary'] ), array(
						'before_widget' => '<div class="widget %s">',
						'after_widget'  => '</div>',
					) );
				}
				?>

				<?php esn_component( 'off_canvas_button' ); ?>

				<?php dynamic_sidebar( 'sidebar-offcanvas' ); ?>
				
				<div class="es-bottombar mb-3">
				<?php 
				// FOOTER MENU.
				$locations = get_nav_menu_locations();				
				if ( isset( $locations['mobile_footer'] ) ) {

					
					if ( isset( $locations['mobile_footer'] ) ) {
						$location = $locations['mobile_footer'];
					}

					the_widget( 'WP_Nav_Menu_Widget', array( 'nav_menu' => $location ), array(
						'before_widget' => '<div class="widget %s">',
						'after_widget'  => '</div>',
					) );
				}

				?>
				</div>

				<div class="es-offcanvas__bottombar">
					<?php
						esn_component( 'misc_social_links' );
						esn_component( 'off_canvas_scheme_toggle' );
					?>
				</div>
			</div>
		</aside>
	</div>
	<?php
}
