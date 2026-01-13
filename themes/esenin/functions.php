<?php
/**
 * Esenin functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Esenin
 */
if ( ! class_exists( 'Esenin' ) ) {
		
	add_filter( 'show_admin_bar', '__return_false' );
	
	/**
	 * Main Core Class
	 */
	class Esenin {
		/**
		 * __construct
		 *
		 * This function will initialize the initialize
		 */
		public function __construct() {
			$this->init();
			$this->theme_files();			
		}

		/**
		 * Init
		 */
		public function init() {
			add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
		}

		/**
		 * Theme support
		 */
		public function theme_support() {
			add_theme_support( 'wp-block-styles' );
			add_theme_support( 'custom-logo' );
			add_theme_support( 'custom-header' );
			add_theme_support( 'custom-background' );
			register_block_pattern();
			add_editor_style();
		}

		public function theme_setup() {
			load_theme_textdomain( 'esenin', get_template_directory() . '/languages' );

			add_theme_support( 'automatic-feed-links' );

			add_theme_support( 'title-tag' );

			register_nav_menus(
				array(
					'primary' => esc_html__( 'Primary', 'esenin' ),
					'footer'  => esc_html__( 'Footer', 'esenin' ),
					'mobile_primary'  => esc_html__( 'Mobile Primary', 'esenin' ),
					'mobile_footer'  => esc_html__( 'Mobile Footer', 'esenin' ),		
				)
			);

			add_theme_support(
				'html5',
				array(
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				)
			);

			// Add support for responsive embeds.
			add_theme_support( 'responsive-embeds' );

			// Supported Formats.
			add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio' ) );

			// Add theme support for selective refresh for widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );

			// Add support for full and wide align images.
			add_theme_support( 'align-wide' );

			add_theme_support( 'post-thumbnails' );
			
			
			
		}
		/**
		 * Include theme files
		 */
		public function theme_files() {
						
			require_once get_theme_file_path( '/inc/deprecated.php' );
			require_once get_theme_file_path( '/inc/theme-setup.php' );
			require_once get_theme_file_path( '/core/customizer/class-customizer.php' );
			require_once get_theme_file_path( '/core/promo-banner/class-promo-banner.php' );
			
			require_once get_theme_file_path( '/inc/assets.php' );
			require_once get_theme_file_path( '/inc/widgets-init.php' );
			require_once get_theme_file_path( '/inc/theme-functions.php' );
			require_once get_theme_file_path( '/inc/theme-mods.php' );
			require_once get_theme_file_path( '/inc/widgets-mods.php' );
			require_once get_theme_file_path( '/inc/filters.php' );
			require_once get_theme_file_path( '/inc/gutenberg.php' );
			require_once get_theme_file_path( '/inc/actions.php' );
			require_once get_theme_file_path( '/inc/partials.php' );
			require_once get_theme_file_path( '/inc/theme-tags.php' );
			require_once get_theme_file_path( '/inc/post-meta.php' );
			require_once get_theme_file_path( '/inc/load-more.php' );
			require_once get_theme_file_path( '/inc/custom-content.php' );
			require_once get_theme_file_path( '/inc/categories.php' );
			require_once get_theme_file_path( '/inc/load-nextpost.php' );
			
			require_once get_theme_file_path( '/inc/convert-img-to-webp.php' );
			require_once get_theme_file_path( '/inc/auth.php' );
			require_once get_theme_file_path( '/inc/ajax-search.php' );
			require_once get_theme_file_path( '/inc/custom-menu.php' );
			require_once get_theme_file_path( '/inc/start-pages.php' );
			require_once get_theme_file_path( '/inc/narrow.php' );
			
			require_once get_theme_file_path( '/inc/config/user-avatar.php' );
			require_once get_theme_file_path( '/inc/config/users-list.php' );
			require_once get_theme_file_path( '/inc/config/user-subscription.php' );
			require_once get_theme_file_path( '/inc/config/category-subscription.php' );
			require_once get_theme_file_path( '/inc/config/bookmarks.php' );
		    require_once get_theme_file_path( '/inc/config/karma.php' );
			require_once get_theme_file_path( '/inc/config/notifications.php' );
			require_once get_theme_file_path( '/inc/config/user-online.php' );
			
			require_once get_theme_file_path( '/inc/post-card-excerpt.php' );
			require_once get_theme_file_path( '/inc/likers.php' );
			require_once get_theme_file_path( '/inc/likers-comments.php' );	
            require_once get_theme_file_path( '/inc/oauth2.php' );
			
			require_once get_theme_file_path( 'inc/tablefoot-mapping.php' );	
			require_once get_theme_file_path( 'inc/tablefoot-core.php' );
			require_once get_theme_file_path( 'inc/tablefoot-shortcodes.php' );  			

			require_once get_theme_file_path('/inc/team-mapping.php');
			require_once get_theme_file_path('/inc/team-core.php');
			require_once get_theme_file_path('/inc/team-shortcode.php');
			
			require get_template_directory() . '/inc/db-optimization.php';
			require get_template_directory() . '/inc/vk-exporter.php';
		}
	}

	// Initialize.
	new Esenin();
}

add_filter( 'site_icon_meta_tags', function( $meta_tags ) {
    foreach ( $meta_tags as &$tag ) {
        if ( strpos( $tag, '.svg' ) !== false ) {
            // Втыкаем тайп прямо в середину тега
            $tag = str_replace( ' href=', ' type="image/svg+xml" href=', $tag );
        }
    }
    return $meta_tags;
}, 10, 1 );
