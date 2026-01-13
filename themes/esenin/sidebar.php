<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Esenin
 */

/**
 * The esn_sidebar hook.
 *
 * @since 1.0.0
 */
$sidebar = apply_filters( 'esn_sidebar', 'sidebar-main' );

if ( 'disabled' !== esn_get_page_sidebar() ) {
	?>
	<aside id="secondary" class="es-widget-area es-sidebar__area">
		<div class="es-sidebar__inner">

			<?php
			/**
			 * The esn_sidebar_start hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'esn_sidebar_start' );
			?>

			<?php dynamic_sidebar( $sidebar ); ?>

			<?php
			/**
			 * The esn_sidebar_end hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'esn_sidebar_end' );
			?>

		</div>
	</aside>
	<?php
}
