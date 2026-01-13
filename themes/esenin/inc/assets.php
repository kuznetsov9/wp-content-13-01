<?php
/**
 * Assets
 *
 * All enqueues of scripts and styles.
 *
 * @package Esenin
 */

if ( ! function_exists( 'esn_content_width' ) ) {
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function esn_content_width() {
		/**
		 * The esn_content_width hook.
		 *
		 * @since 1.0.0
		 */
		$GLOBALS['content_width'] = apply_filters( 'esn_content_width', 1200 );
	}
}
add_action( 'after_setup_theme', 'esn_content_width', 0 );

if ( ! function_exists( 'esn_enqueue_scripts' ) ) {
	/**
	 * Enqueue scripts and styles.
	 */
	function esn_enqueue_scripts() {

		$version = esn_get_theme_data( 'Version' );

		// Register theme scripts.
		wp_register_script( 'esn-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array(), $version, true );

		// Localization array.
		$localize = array(
			'siteSchemeMode'   => get_theme_mod( 'color_scheme', 'system' ),
			'siteSchemeToogle' => get_theme_mod( 'color_scheme_toggle', true ),
		);

		// Localize the main theme scripts.
		wp_localize_script( 'esn-scripts', 'esLocalize', $localize );

		// Enqueue theme scripts.
		wp_enqueue_script( 'esn-scripts' );

		// Enqueue comment reply script.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );

			wp_register_script( 'esn-comment-reply', get_template_directory_uri() . '/assets/static/js/comment-reply.js', array(), $version, true );
			wp_enqueue_script( 'esn-comment-reply' );
		}

		wp_deregister_style( 'swiper' );
		wp_dequeue_style( 'swiper' );

		wp_dequeue_script( sprintf( '%s-reply', 'comment' ) );

		// Register theme styles.
		wp_register_style( 'esn-styles', esn_style( get_template_directory_uri() . '/style.css' ), array(), $version );

		// Enqueue theme styles.
		wp_enqueue_style( 'esn-styles' );

		// Enqueue typography styles.
		esn_enqueue_typography_styles( 'esn-styles' );

		// Dequeue Contact Form 7 styles.
		wp_dequeue_style( 'contact-form-7' );
		
		
	}
}
add_action( 'wp_enqueue_scripts', 'esn_enqueue_scripts', 99 );

/**
 * Add Bootstrap 5.3.3
 */
function bootstrap_scripts() {
	wp_enqueue_style( 'esn-bootstrap-style', get_template_directory_uri() . '/assets/static/bootstrap/bootstrap.min.css', array(), '5.3.3' );
	wp_enqueue_script( 'esn-bootstrap-script', get_template_directory_uri() . '/assets/static/bootstrap/bootstrap.bundle.min.js', array(), '5.3.3', true );
}
add_action( 'wp_enqueue_scripts', 'bootstrap_scripts' );

/**
 * Add SlimSelect 2.9.0 (for filtering and sorting)
 */
function slimselect_scripts() {
	wp_enqueue_style( 'esn-slimselect-style', get_template_directory_uri() . '/inc/filter/select/select.css', array(), '2.9.0' );
	wp_enqueue_script( 'esn-slimselect-script', get_template_directory_uri() . '/inc/filter/select/select.js', array(), '2.9.0', false );
}
add_action( 'wp_enqueue_scripts', 'slimselect_scripts' );


/**
 * Custom styles plugin Front Editor
 */
if ( ! function_exists( 'is_plugin_active' ) ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
function my_enqueue_styles() {
    if ( is_plugin_active( 'front-editor/front-editor.php' ) ) {
        wp_enqueue_style( 'new-style-plugin', get_template_directory_uri() . '/front-user-submit/new-style-plugin.css', [], null, 'all' );
    }
}
add_action( 'wp_enqueue_scripts', 'my_enqueue_styles' );

function esn_cropper_scripts() {
    // Тянем из CDN, чтобы не грузить твой сервак лишними файлами
    wp_enqueue_style( 'cropper-css', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css', array(), '1.5.13' );
    wp_enqueue_script( 'cropper-js', 'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js', array('jquery'), '1.5.13', true );
}
add_action( 'wp_enqueue_scripts', 'esn_cropper_scripts' );