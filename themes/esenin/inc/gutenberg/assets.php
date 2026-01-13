<?php
/**
 * Assets
 *
 * All enqueues of scripts and styles.
 *
 * @package Esenin
 */

if ( ! function_exists( 'esn_editor_style' ) ) {
	/**
	 * Add callback for custom editor stylesheets.
	 */
	function esn_editor_style() {
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );
	}
}
add_action( 'current_screen', 'esn_editor_style' );

if ( ! function_exists( 'esn_enqueue_block_editor_assets' ) ) {
	/**
	 * Enqueue block editor specific scripts.
	 */
	function esn_enqueue_block_editor_assets() {
		if ( ! ( is_admin() && ! is_customize_preview() ) ) {
			return;
		}

		$version = esn_get_theme_data( 'Version' );

		// Register theme scripts.
		wp_register_script( 'es-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery', 'imagesloaded' ), $version, true );

		// Localization array.
		$localize = array(
			'siteSchemeMode'   => 'light',
			'siteSchemeToogle' => false,
		);

		// Localize the main theme scripts.
		wp_localize_script( 'es-scripts', 'esLocalize', $localize );

		// Enqueue theme scripts.
		wp_enqueue_script( 'es-scripts' );

		// Register theme styles.
		wp_register_style( 'es-editor', esn_style( get_template_directory_uri() . '/assets/ess/editor-style.ess' ), false, $version );

		// Enqueue typography styles.
		esn_enqueue_typography_styles( 'es-editor' );

		// Enqueue theme styles.
		wp_enqueue_style( 'es-editor' );
	}
	add_action( 'enqueue_block_assets', 'esn_enqueue_block_editor_assets' );
}
