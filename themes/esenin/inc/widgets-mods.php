<?php
/**
 * Widgets mods
 *
 * @package Esenin
 */

/**
 * Register Widgets Mods
 */
function esn_register_widgets_mods() { 
    
	/** All themes */
	require get_template_directory() . '/inc/widgets/wid-themes.php';
	
	/** Latest comments */
	require get_template_directory() . '/inc/widgets/wid-latest-comments.php';
	
	/** Custom posts */
	require get_template_directory() . '/inc/widgets/wid-custom-posts.php';
	
	/** Top authors */
	require get_template_directory() . '/inc/widgets/wid-top-authors.php';
		
 }
add_action( 'after_setup_theme', 'esn_register_widgets_mods', 20 );