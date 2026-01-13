<?php
/**
 * Editor Settings.
 *
 * @package Esenin
 */

/**
 * Enqueue editor scripts
 */
function esn_block_editor_scripts() {
	wp_enqueue_script(
		'es-editor-scripts',
		get_template_directory_uri() . '/assets/jsx/editor-scripts.js',
		array(
			'wp-editor',
			'wp-element',
			'wp-compose',
			'wp-data',
			'wp-plugins',
		),
		esn_get_theme_data( 'Version' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'esn_block_editor_scripts' );

/**
 * Adds classes to <div class="editor-styles-wrapper"> tag
 */
function esn_block_editor_wrapper() {
	$script_handle = 'es-editor-wrapper';
	$script_file   = 'editor-wrapper.js';

	if ( 'enqueue_block_assets' === current_filter() ) {
		if ( ! ( is_admin() && ! is_customize_preview() ) ) {
			return;
		}

		$script_handle = 'es-editor-iframe';
		$script_file   = 'editor-iframe.js';
	}

	$post_id = get_the_ID();

	if ( ! $post_id ) {
		return;
	}

	// Set post type.
	$post_type = sprintf( 'post-type-%s', get_post_type( $post_id ) );

	// Set page layout.
	$default_layout = esn_get_page_sidebar( $post_id, 'default' );
	$page_layout    = esn_get_page_sidebar( $post_id, false );

	if ( 'disabled' === $default_layout ) {
		$default_layout = 'es-sidebar-disabled';
	} else {
		$default_layout = 'es-sidebar-enabled';
	}

	if ( 'disabled' === $page_layout ) {
		$page_layout = 'es-sidebar-disabled';
	} else {
		$page_layout = 'es-sidebar-enabled';
	}

	// Set breakpoints.
	$breakpoints = array(
		'es-breakpoint-up-576px'  => 576,
		'es-breakpoint-up-768px'  => 768,
		'es-breakpoint-up-992px'  => 992,
		'es-breakpoint-up-1200px' => 1200,
		'es-breakpoint-up-1336px' => 1336,
		'es-breakpoint-up-1920px' => 1920,
	);

	wp_enqueue_script(
		$script_handle,
		get_template_directory_uri() . '/assets/jsx/' . $script_file,
		array(
			'wp-editor',
			'wp-element',
			'wp-compose',
			'wp-data',
			'wp-plugins',
		),
		esn_get_theme_data( 'Version' ),
		true
	);

	wp_localize_script(
		$script_handle,
		'esnGWrapper',
		array(
			'post_type'      => $post_type,
			'default_layout' => $default_layout,
			'page_layout'    => $page_layout,
			'breakpoints'    => $breakpoints,
		)
	);
}
add_action( 'enqueue_block_editor_assets', 'esn_block_editor_wrapper' );
add_action( 'enqueue_block_assets', 'esn_block_editor_wrapper' );

/**
 * Change editor color palette.
 */
function esn_change_editor_color_palette() {
	// Editor Color Palette.
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => esc_html__( 'Primary', 'esenin' ),
				'slug'  => 'primary',
				'color' => get_theme_mod( 'color_primary', '#29294B' ),
			),
			array(
				'name'  => esc_html__( 'Secondary', 'esenin' ),
				'slug'  => 'secondary',
				'color' => get_theme_mod( 'color_secondary', '#696981' ),
			),
			array(
				'name'  => esc_html__( 'Layout', 'esenin' ),
				'slug'  => 'layout',
				'color' => get_theme_mod( 'color_layout_background', '#F1F1F1' ),
			),
			array(
				'name'  => esc_html__( 'Accent', 'esenin' ),
				'slug'  => 'accent',
				'color' => get_theme_mod( 'color_accent', '#5955D1' ),
			),
			array(
				'name'  => esc_html__( 'Border', 'esenin' ),
				'slug'  => 'border',
				'color' => get_theme_mod( 'color_border', '#E1E1E8' ),
			),
			array(
				'name'  => esc_html__( 'Blue', 'esenin' ),
				'slug'  => 'blue',
				'color' => '#59BACC',
			),
			array(
				'name'  => esc_html__( 'Green', 'esenin' ),
				'slug'  => 'green',
				'color' => '#58AD69',
			),
			array(
				'name'  => esc_html__( 'Orange', 'esenin' ),
				'slug'  => 'orange',
				'color' => '#FFBC49',
			),
			array(
				'name'  => esc_html__( 'Red', 'esenin' ),
				'slug'  => 'red',
				'color' => '#e32c26',
			),
			array(
				'name'  => esc_html__( 'Pale Pink', 'esenin' ),
				'slug'  => 'pale-pink',
				'color' => '#f78da7',
			),
			array(
				'name'  => esc_html__( 'White', 'esenin' ),
				'slug'  => 'white',
				'color' => '#FFFFFF',
			),
			array(
				'name'  => esc_html__( 'Gray 50', 'esenin' ),
				'slug'  => 'gray-50',
				'color' => '#f8f9fa',
			),
			array(
				'name'  => esc_html__( 'Gray 100', 'esenin' ),
				'slug'  => 'gray-100',
				'color' => '#f8f9fa',
			),
			array(
				'name'  => esc_html__( 'Gray 200', 'esenin' ),
				'slug'  => 'gray-200',
				'color' => '#E1E1E8',
			),
		)
	);
}
add_action( 'after_setup_theme', 'esn_change_editor_color_palette' );
