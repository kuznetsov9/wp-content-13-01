<?php
/**
 * Additional Content.
 *
 * @package Esenin
 */

/**
 * Define array of Additional Content Locations
 */
function esn_get_custom_content_locations() {
	$content = array(
		array(
			'slug'     => 'header',
			'name'     => esc_html__( 'Header', 'esenin' ),
			'template' => array( 'home', 'front_page', 'single', 'page', 'archive' ),
		),
		array(
			'slug'     => 'hero',
			'name'     => esc_html__( 'Hero', 'esenin' ),
			'template' => array( 'home' ),
		),
		array(
			'slug'     => 'site_content',
			'name'     => esc_html__( 'Site Content', 'esenin' ),
			'template' => array( 'home', 'front_page', 'single', 'page', 'archive' ),
		),
		array(
			'slug'     => 'main',
			'name'     => esc_html__( 'Main', 'esenin' ),
			'template' => array( 'home', 'front_page', 'single', 'page', 'archive' ),
		),
		array(
			'slug'     => 'post',
			'name'     => esc_html__( 'Post', 'esenin' ),
			'template' => array( 'single' ),
		),
		array(
			'slug'     => 'post_content',
			'name'     => esc_html__( 'Post Content', 'esenin' ),
			'template' => array( 'single' ),
		),
		array(
			'slug'     => 'page',
			'name'     => esc_html__( 'Page', 'esenin' ),
			'template' => array( 'page' ),
		),
		array(
			'slug'     => 'page_content',
			'name'     => esc_html__( 'Page Content', 'esenin' ),
			'template' => array( 'page' ),
		),
		array(
			'slug'     => 'author',
			'name'     => esc_html__( 'Post Author', 'esenin' ),
			'template' => array( 'single' ),
		),
		array(
			'slug'     => 'pagination',
			'name'     => esc_html__( 'Post Pagination', 'esenin' ),
			'template' => array( 'single' ),
		),
		array(
			'slug'     => 'comments',
			'name'     => esc_html__( 'Comments', 'esenin' ),
			'template' => array( 'single', 'page' ),
		),
		array(
			'slug'     => 'footer',
			'name'     => esc_html__( 'Footer', 'esenin' ),
			'template' => array( 'home', 'front_page', 'single', 'page', 'archive' ),
		),
	);
	return apply_filters( 'esn_custom_content_locations', $content );
}

/**
 * Define array of Additional Content Pages
 */
function esn_get_custom_content_pages() {
	$pages = array(
		'home'       => esc_html__( 'Homepage', 'esenin' ),
		'front_page' => esc_html__( 'Front Page', 'esenin' ),
		'single'     => esc_html__( 'Post', 'esenin' ),
		'page'       => esc_html__( 'Page', 'esenin' ),
		'archive'    => esc_html__( 'Archive', 'esenin' ),
	);
	return $pages;
}

/**
 * Init Additional Content
 */
function esn_init_custom_content() {

	$locations = esn_get_custom_content_locations();
	$pages     = esn_get_custom_content_pages();

	/**
	 * Customizer Settings
	 */

	ESN_Customizer::add_panel(
		'custom_content',
		array(
			'title'    => esc_html__( 'Additional Content', 'esenin' ),
			'priority' => 200,
		)
	);

	ESN_Customizer::add_section(
		'custom_content_general',
		array(
			'title' => esc_html__( 'General', 'esenin' ),
			'panel' => 'custom_content',
		)
	);

	ESN_Customizer::add_field(
		array(
			'type'        => 'checkbox',
			'settings'    => 'custom_content_adsense',
			'label'       => esc_html__( 'Load Google AdSense scripts', 'esenin' ),
			'description' => esc_html__( 'Enable this if you\'re using Google AdSense.', 'esenin' ),
			'section'     => 'custom_content_general',
			'default'     => false,
		)
	);

	foreach ( $pages as $page_slug => $page_name ) {

		ESN_Customizer::add_section(
			'custom_content_' . $page_slug,
			array(
				'title' => $page_name,
				'panel' => 'custom_content',
			)
		);

		foreach ( $locations as $location ) {

			// Check if ads location is supported by the current page template.
			if ( in_array( $page_slug, $location['template'], true ) ) {

				ESN_Customizer::add_field(
					array(
						'type'              => 'textarea',
						'settings'          => 'custom_content_' . $page_slug . '_' . $location['slug'] . '_before',
						'label'             => esc_html__( 'Before', 'esenin' ) . ' ' . $location['name'],
						'section'           => 'custom_content_' . $page_slug,
						'default'           => '',
						'sanitize_callback' => 'esn_unsanitize',
					)
				);

				ESN_Customizer::add_field(
					array(
						'type'              => 'textarea',
						'settings'          => 'custom_content_' . $page_slug . '_' . $location['slug'] . '_after',
						'label'             => esc_html__( 'After', 'esenin' ) . ' ' . $location['name'],
						'section'           => 'custom_content_' . $page_slug,
						'default'           => '',
						'sanitize_callback' => 'esn_unsanitize',
					)
				);
			}
		}
	}

	/**
	 * Removes Sanitizing
	 *
	 * @param string $content Initial content.
	 */
	function esn_unsanitize( $content ) {
		return $content;
	}

	/**
	 * Load Google AdSense scripts
	 */
	function esn_custom_content_enqueue_scripts() {

		if ( get_theme_mod( 'custom_content_adsense', false ) ) {
			// Register Google AdSense scripts.
			wp_register_script( 'esn_adsense', '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js' );

			// Enqueue Google AdSense.
			wp_enqueue_script( 'esn_adsense' );

		}
	}
	add_action( 'wp_enqueue_scripts', 'esn_custom_content_enqueue_scripts' );

	/**
	 * Actions
	 */
	function esn_custom_content_display() {

		// Get current action name.
		$current = current_filter();

		// Get ads pages.
		$pages = esn_get_custom_content_pages();

		foreach ( $pages as $page_slug => $page_name ) {

			$location = "is_$page_slug";

			// On normal pages only.
			if ( 'is_page' === $location ) {
				$location = is_front_page() || is_home() ? null : $location;
			}

			if ( $location && function_exists( $location ) && $location() ) {

				// Get ads locations.
				$locations = esn_get_custom_content_locations();

				// Loop through all locations.
				foreach ( $locations as $location ) {
					// Check if ads location is supported by the current page template.
					if ( in_array( $page_slug, $location['template'], true ) ) {
						// Before.
						if ( 'esn_' . $location['slug'] . '_before' === $current ) {
							$code = get_theme_mod( 'custom_content_' . $page_slug . '_' . $location['slug'] . '_before' );
							if ( $code ) {
								echo '<section class="es-custom-content es-custom-content-' . esc_html( $location['slug'] ) . '-before">' . do_blocks( do_shortcode( $code ) ) . '</section>';
							}
						}
						// After.
						if ( 'esn_' . $location['slug'] . '_after' === $current ) {
							$code = get_theme_mod( 'custom_content_' . $page_slug . '_' . $location['slug'] . '_after' );
							if ( $code ) {
								echo '<section class="es-custom-content es-custom-content-' . esc_html( $location['slug'] ) . '-after">' . do_blocks( do_shortcode( $code ) ) . '</section>';
							}
						}
					}
				}
			}
		}
	}

	foreach ( $locations as $location ) {
		add_action( 'esn_' . $location['slug'] . '_before', 'esn_custom_content_display', 5 );
		add_action( 'esn_' . $location['slug'] . '_after', 'esn_custom_content_display', 999 );
	}
}

add_action( 'init', 'esn_init_custom_content' );