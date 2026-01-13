<aside class="left__sidebar d-none d-lg-block">
			<div class="es-offcanvas__inner es-offcanvas__area es-widget-area">
				<?php
				$locations = get_nav_menu_locations();

				// Get menu by location.
				if ( isset( $locations['primary'] )) {

					if ( isset( $locations['primary'] ) ) {
						$location = $locations['primary'];
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

				

				<?php dynamic_sidebar( 'aside-sidebar' ); ?>

				<div class="es-bottombar">
						
				<?php 
				// FOOTER MENU.
				$locations = get_nav_menu_locations();				
				if ( isset( $locations['footer'] ) ) {

					if ( isset( $locations['footer'] ) ) {
						$location = $locations['footer'];
					}
                   

				   
					the_widget( 'WP_Nav_Menu_Widget', array( 'nav_menu' => $location ), array(
						'before_widget' => '<div class="widget %s">',
						'after_widget'  => '</div>',
					) );
				}

				?>
				
				<?php esn_component( 'misc_social_links' ); ?>
				
				
				<?php if ( get_theme_mod( 'footer_copyright', esc_html__( '© 2025 — РФПЛ.РФ. Все права защищены.', 'esenin' ) ) ) { ?>
				<div class="es-footer__item-inner-bottom">
					<?php esn_component( 'footer_copyright' ); ?>
				</div>
			    <?php } ?>
					
				</div>
			</div>
</aside>

