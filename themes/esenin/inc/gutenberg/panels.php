<?php
/**
 * Adding New Panels.
 *
 * @package Esenin
 */

/**
 * Register meta fields for gutenberg panels
 */
function esn_gutenberg_panels_register_meta() {

	$post_types = array( 'post', 'page' );

	// Loop Post Types.
	foreach ( $post_types as $post_type ) {

		/**
		 * ==================================
		 * Layout Options
		 * ==================================
		 */

		register_post_meta(
			$post_type,
			'esn_singular_sidebar',
			array(
				'show_in_rest'  => true,
				'type'          => 'string',
				'single'        => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

		/* register_post_meta(
			$post_type,
			'esn_page_header_type',
			array(
				'show_in_rest'  => true,
				'type'          => 'string',
				'single'        => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		); */

		register_post_meta(
			$post_type,
			'esn_page_load_nextpost',
			array(
				'show_in_rest'  => true,
				'type'          => 'string',
				'single'        => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
add_action( 'init', 'esn_gutenberg_panels_register_meta' );

/**
 * Filters whether a meta key is considered protected.
 *
 * @param bool   $protected Whether the key is considered protected.
 * @param string $meta_key  Metadata key.
 * @param string $meta_type Type of object metadata is for.
 */
function esn_is_protected_meta( $protected, $meta_key, $meta_type ) {
	$hide_meta_keys = array(
		'esn_singular_sidebar',
		/* 'esn_page_header_type', */
		/* 'esn_page_load_nextpost', */
	);

	if ( in_array( $meta_key, $hide_meta_keys, true ) ) {
		$protected = true;
	}

	return $protected;
}
add_filter( 'is_protected_meta', 'esn_is_protected_meta', 10, 3 );

/**
 * Enqueue assets  for gutenberg panels
 */
function esn_gutenberg_panels_assets() {

	$post_id = get_the_ID();

	if ( ! $post_id ) {
		return;
	}

	$post = get_post( $post_id );

	$page_static = array();

	// Add pages static.
	$page_static[] = get_option( 'page_on_front' );
	$page_static[] = get_option( 'page_for_posts' );

	// Set options.
	$singular_sidebar = array(
		array(
			'value' => 'default',
			'label' => esc_html__( 'Default', 'esenin' ),
		),
		array(
			'value' => 'right',
			'label' => esc_html__( 'Right Sidebar', 'esenin' ),
		),
		array(
			'value' => 'disabled',
			'label' => esc_html__( 'No Sidebar', 'esenin' ),
		),
	);

	$page_header_type = array();
	$page_load_nextpost = array();

	if ( 'post' === $post->post_type || 'page' === $post->post_type ) {
		$page_header_type = array(
			array(
				'value' => 'default',
				'label' => esc_html__( 'Default', 'esenin' ),
			),
			array(
				'value' => 'standard',
				'label' => esc_html__( 'Standard', 'esenin' ),
			),
			array(
				'value' => 'split',
				'label' => esc_html__( 'Split', 'esenin' ),
			),
			array(
				'value' => 'overlay',
				'label' => esc_html__( 'Overlay', 'esenin' ),
			),
			array(
				'value' => 'title',
				'label' => esc_html__( 'Page Title Only', 'esenin' ),
			),
			array(
				'value' => 'none',
				'label' => esc_html__( 'None', 'esenin' ),
			),
		);

		if ( 'post' === $post->post_type ) {
			$page_load_nextpost = array(
				array(
					'value' => 'default',
					'label' => esc_html__( 'Default', 'esenin' ),
				),
				array(
					'value' => 'enabled',
					'label' => esc_html__( 'Enabled', 'esenin' ),
				),
				array(
					'value' => 'disabled',
					'label' => esc_html__( 'Disabled', 'esenin' ),
				),
			);
		}
	}

	$panels_data = array(
		'postType'         => $post->post_type,
		'singularSidebar'  => $singular_sidebar,
		/* 'pageHeaderType'   => $page_header_type, */
		/* 'pageLoadNextpost' => apply_filters( 'esn_editor_page_load_nextpost', $page_load_nextpost ), */
	);

	// Enqueue scripts.
	wp_enqueue_script(
		'esn-editor-panels',
		get_template_directory_uri() . '/assets/jsx/panels.js',
		array(
			'wp-i18n',
			'wp-blocks',
			'wp-edit-post',
			'wp-element',
			'wp-editor',
			'wp-components',
			'wp-data',
			'wp-plugins',
			'wp-edit-post',
			'wp-hooks',
		),
		esn_get_theme_data( 'Version' ),
		true
	);

	// Localize scripts.
	wp_localize_script(
		'esn-editor-panels',
		'esPanelsData',
		apply_filters( 'esn_panels_data', $panels_data, $post )
	);
}
add_action( 'enqueue_block_editor_assets', 'esn_gutenberg_panels_assets' );
