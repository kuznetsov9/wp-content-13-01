<?php
/**
 * Theme Setup
 *
 * @package Esenin
 */

/**
 * The function sets the default options to plugins.
 *
 * Set Post Views Counter location to manual.
 *
 * @param string $plugin Plugin name.
 */
function esn_plugin_set_options( $plugin ) {
	if ( 'post-views-counter' === $plugin ) {
		// Get display options.
		$display_options = get_option( 'post_views_counter_settings_display' );

		$display_options = $display_options ? $display_options : array();
		// Set position value.
		$display_options['position'] = 'manual';
		// Update options.
		update_option( 'post_views_counter_settings_display', $display_options );
	}

	if ( 'wp-seo' === $plugin ) {
		// Get display options.
		$display_options = get_option( 'wpseo_titles' );

		$display_options = $display_options ? $display_options : array();
		// Set position value.
		$display_options['breadcrumbs-sep'] = '<span class="es-separator"></span>';
		// Update options.
		update_option( 'wpseo_titles', $display_options );
	}
}

/**
 * Hook into activated_plugin action.
 *
 * @param string $plugin Plugin path to main plugin file with plugin data.
 */
function esn_activated_plugin( $plugin ) {
	// Check if PVC constant is defined, use it to get PVC path anc compare to activated plugin.
	if ( 'post-views-counter/post-views-counter.php' === $plugin ) {
		esn_plugin_set_options( 'post-views-counter' );
	}

	// Check if WPSEO constant is defined, use it to get WPSEO path anc compare to activated plugin.
	if ( 'wordpress-seo/wp-seo.php' === $plugin ) {
		esn_plugin_set_options( 'wp-seo' );
	}
}
add_action( 'activated_plugin', 'esn_activated_plugin' );

/**
 * Hook into after_switch_theme action.
 */
function esn_activated_theme() {
	esn_plugin_set_options( 'post-views-counter' );
	esn_plugin_set_options( 'wp-seo' );
}
add_action( 'after_switch_theme', 'esn_activated_theme' );

/**
 * Remove AMP link.
 */
function esn_admin_remove_amp_link() {
	remove_action( 'admin_menu', 'amp_add_customizer_link' );
}
add_action( 'after_setup_theme', 'esn_admin_remove_amp_link', 20 );

/**
 * Remove AMP panel.
 *
 * @param object $wp_customize Instance of the WP_Customize_Manager class.
 */
function esn_customizer_remove_amp_panel( $wp_customize ) {
	$wp_customize->remove_panel( 'amp_panel' );
}
add_action( 'customize_register', 'esn_customizer_remove_amp_panel', 1000 );
