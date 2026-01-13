<?php
/**
 * Widgets Init
 *
 * Register sitebar locations for widgets.
 *
 * @package Esenin
 */

if ( ! function_exists( 'esn_widgets_init' ) ) {
	/**
	 * Register sidebars
	 */
	function esn_widgets_init() {
		
		register_sidebar(
			array(
				'name'          => esc_html__( 'Aside Sidebar', 'esenin' ),
				'id'            => 'aside-sidebar',
				'before_widget' => '<div class="widget %1$s %2$s">',
				'after_widget'  => '</div>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Right Sidebar', 'esenin' ),
				'id'            => 'sidebar-main',
				'before_widget' => '<div class="widget %1$s %2$s">',
				'after_widget'  => '</div>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Mobile (Offcanvas)', 'esenin' ),
				'id'            => 'sidebar-offcanvas',
				'before_widget' => '<div class="widget %1$s %2$s">',
				'after_widget'  => '</div>',
			)
		);
	}
	add_action( 'widgets_init', 'esn_widgets_init' );
}
