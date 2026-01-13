<?php
/**
 * Typography
 *
 * @package Esenin
 */

?>
:root {
	/* Base Font */
	--es-font-base-family: <?php esn_typography( 'font_base', 'font-family', 'Roboto' ); ?>;
	--es-font-base-size: <?php esn_typography( 'font_base', 'font-size', '1rem' ); ?>;
	--es-font-base-weight: <?php esn_typography( 'font_base', 'font-weight', '400' ); ?>;
	--es-font-base-style: <?php esn_typography( 'font_base', 'font-style', 'normal' ); ?>;
	--es-font-base-letter-spacing: <?php esn_typography( 'font_base', 'letter-spacing', 'normal' ); ?>;
	--es-font-base-line-height: <?php esn_typography( 'font_base', 'line-height', '1.55' ); ?>;

	/* Primary Font */
	--es-font-primary-family: <?php esn_typography( 'font_primary', 'font-family', 'Roboto' ); ?>;
	--es-font-primary-size: <?php esn_typography( 'font_primary', 'font-size', '1rem' ); ?>;
	--es-font-primary-weight: <?php esn_typography( 'font_primary', 'font-weight', '500' ); ?>;
	--es-font-primary-style: <?php esn_typography( 'font_primary', 'font-style', 'normal' ); ?>;
	--es-font-primary-letter-spacing: <?php esn_typography( 'font_primary', 'letter-spacing', 'normal' ); ?>;
	--es-font-primary-text-transform: <?php esn_typography( 'font_primary', 'text-transform', 'none' ); ?>;
	--es-font-primary-line-height: <?php esn_typography( 'font_primary', 'line-height', '1.2' ); ?>;

	/* Secondary Font */
	--es-font-secondary-family: <?php esn_typography( 'font_secondary', 'font-family', 'Roboto' ); ?>;
	--es-font-secondary-size: <?php esn_typography( 'font_secondary', 'font-size', '0.875rem' ); ?>;
	--es-font-secondary-weight: <?php esn_typography( 'font_secondary', 'font-weight', '400' ); ?>;
	--es-font-secondary-style: <?php esn_typography( 'font_secondary', 'font-style', 'normal' ); ?>;
	--es-font-secondary-letter-spacing: <?php esn_typography( 'font_secondary', 'letter-spacing', 'normal' ); ?>;
	--es-font-secondary-text-transform: <?php esn_typography( 'font_secondary', 'text-transform', 'none' ); ?>;
	--es-font-secondary-line-height: <?php esn_typography( 'font_secondary', 'line-height', '1.55' ); ?>;

	/* Section Headings Font */
	--es-font-section-headings-family: <?php esn_typography( 'font_section_headings', 'font-family', 'Roboto' ); ?>;
	--es-font-section-headings-size: <?php esn_typography( 'font_section_headings', 'font-size', '0.75rem' ); ?>;
	--es-font-section-headings-weight: <?php esn_typography( 'font_section_headings', 'font-weight', '500' ); ?>;
	--es-font-section-headings-style: <?php esn_typography( 'font_section_headings', 'font-style', 'normal' ); ?>;
	--es-font-section-headings-letter-spacing: <?php esn_typography( 'font_section_headings', 'letter-spacing', 'normal' ); ?>;
	--es-font-section-headings-text-transform: <?php esn_typography( 'font_section_headings', 'text-transform', 'uppercase' ); ?>;
	--es-font-section-headings-line-height: <?php esn_typography( 'font_section_headings', 'line-height', '1.2' ); ?>;

	/* Post Title Font Size */
	--es-font-post-title-family: <?php esn_typography( 'font_post_title', 'font-family', 'Roboto' ); ?>;
	--es-font-post-title-weight: <?php esn_typography( 'font_post_title', 'font-weight', '500' ); ?>;
	--es-font-post-title-size: <?php esn_typography( 'font_post_title', 'font-size', '3.25rem' ); ?>;
	--es-font-post-title-letter-spacing: <?php esn_typography( 'font_post_title', 'letter-spacing', 'normal' ); ?>;
	--es-font-post-title-line-height: <?php esn_typography( 'font_post_title', 'line-height', '1.2' ); ?>;

	/* Post Subbtitle */
	--es-font-post-subtitle-family: <?php esn_typography( 'font_post_subtitle', 'font-family', 'Roboto' ); ?>;
	--es-font-post-subtitle-weight: <?php esn_typography( 'font_post_subtitle', 'font-weight', '400' ); ?>;
	--es-font-post-subtitle-size: <?php esn_typography( 'font_post_subtitle', 'font-size', '17px' ); ?>;
	--es-font-post-subtitle-letter-spacing: <?php esn_typography( 'font_post_subtitle', 'letter-spacing', 'normal' ); ?>;
	--es-font-post-subtitle-line-height: <?php esn_typography( 'font_post_subtitle', 'line-height', '1.55' ); ?>;

	/* Post Category Font */
	--es-font-category-family: <?php esn_typography( 'font_category', 'font-family', 'Roboto' ); ?>;
	--es-font-category-size: <?php esn_typography( 'font_category', 'font-size', '0.6875rem' ); ?>;
	--es-font-category-weight: <?php esn_typography( 'font_category', 'font-weight', '500' ); ?>;
	--es-font-category-style: <?php esn_typography( 'font_category', 'font-style', 'normal' ); ?>;
	--es-font-category-letter-spacing: <?php esn_typography( 'font_category', 'letter-spacing', 'normal' ); ?>;
	--es-font-category-text-transform: <?php esn_typography( 'font_category', 'text-transform', 'uppercase' ); ?>;
	--es-font-category-line-height: <?php esn_typography( 'font_category', 'line-height', '1.2' ); ?>;

	/* Post Meta Font */
	--es-font-post-meta-family: <?php esn_typography( 'font_post_meta', 'font-family', 'Roboto' ); ?>;
	--es-font-post-meta-size: <?php esn_typography( 'font_post_meta', 'font-size', '0.9375rem' ); ?>;
	--es-font-post-meta-weight: <?php esn_typography( 'font_post_meta', 'font-weight', '500' ); ?>;
	--es-font-post-meta-style: <?php esn_typography( 'font_post_meta', 'font-style', 'normal' ); ?>;
	--es-font-post-meta-letter-spacing: <?php esn_typography( 'font_post_meta', 'letter-spacing', 'normal' ); ?>;
	--es-font-post-meta-text-transform: <?php esn_typography( 'font_post_meta', 'text-transform', 'none' ); ?>;
	--es-font-post-meta-line-height: <?php esn_typography( 'font_post_meta', 'line-height', '1.2' ); ?>;

	/* Post Content */
	--es-font-post-content-family: <?php esn_typography( 'font_post_content', 'font-family', 'Roboto' ); ?>;
	--es-font-post-content-weight: <?php esn_typography( 'font_post_content', 'font-weight', '400' ); ?>;
	--es-font-post-content-size: <?php esn_typography( 'font_post_content', 'font-size', '0.875rem' ); ?>;
	--es-font-post-content-letter-spacing: <?php esn_typography( 'font_post_content', 'letter-spacing', 'normal' ); ?>;
	--es-font-post-content-line-height: <?php esn_typography( 'font_post_content', 'line-height', '1.25rem' ); ?>;

	/* Input Font */
	--es-font-input-family: <?php esn_typography( 'font_input', 'font-family', 'Roboto' ); ?>;
	--es-font-input-size: <?php esn_typography( 'font_input', 'font-size', '0.875rem' ); ?>;
	--es-font-input-weight: <?php esn_typography( 'font_input', 'font-weight', '400' ); ?>;
	--es-font-input-style: <?php esn_typography( 'font_input', 'font-style', 'normal' ); ?>;
	--es-font-input-line-height: <?php esn_typography( 'font_input', 'line-height', '1.55' ); ?>;
	--es-font-input-letter-spacing: <?php esn_typography( 'font_input', 'letter-spacing', 'normal' ); ?>;
	--es-font-input-text-transform: <?php esn_typography( 'font_input', 'text-transform', 'none' ); ?>;

	/* Entry Title Font Size */
	--es-font-entry-title-family: <?php esn_typography( 'font_entry_title', 'font-family', 'Roboto' ); ?>;
	--es-font-entry-title-weight: <?php esn_typography( 'font_entry_title', 'font-weight', '700' ); ?>;
	--es-font-entry-title-letter-spacing: <?php esn_typography( 'font_entry_title', 'letter-spacing', 'normal' ); ?>;
	--es-font-entry-title-line-height: <?php esn_typography( 'font_entry_title', 'line-height', '1.4' ); ?>;

	/* Entry Excerpt */
	--es-font-entry-excerpt-family: <?php esn_typography( 'font_excerpt', 'font-family', 'Roboto' ); ?>;
	--es-font-entry-excerpt-weight: <?php esn_typography( 'font_excerpt', 'font-weight', '400' ); ?>;
	--es-font-entry-excerpt-size: <?php esn_typography( 'font_excerpt', 'font-size', '1rem' ); ?>;
	--es-font-entry-excerpt-letter-spacing: <?php esn_typography( 'font_excerpt', 'letter-spacing', 'normal' ); ?>;
	--es-font-entry-excerpt-line-height: <?php esn_typography( 'font_excerpt', 'line-height', '1.55' ); ?>;

	/* Logos --------------- */

	/* Main Logo */
	--es-font-main-logo-family: <?php esn_typography( 'font_main_logo', 'font-family', 'Roboto' ); ?>;
	--es-font-main-logo-size: <?php esn_typography( 'font_main_logo', 'font-size', '1.7rem' ); ?>;
	--es-font-main-logo-weight: <?php esn_typography( 'font_main_logo', 'font-weight', '700' ); ?>;
	--es-font-main-logo-style: <?php esn_typography( 'font_main_logo', 'font-style', 'normal' ); ?>;
	--es-font-main-logo-letter-spacing: <?php esn_typography( 'font_main_logo', 'letter-spacing', 'normal' ); ?>;
	--es-font-main-logo-text-transform: <?php esn_typography( 'font_main_logo', 'text-transform', 'none' ); ?>;

	/* Footer Logo */
	--es-font-footer-logo-family: <?php esn_typography( 'font_footer_logo', 'font-family', 'Roboto' ); ?>;
	--es-font-footer-logo-size: <?php esn_typography( 'font_footer_logo', 'font-size', '1.7rem' ); ?>;
	--es-font-footer-logo-weight: <?php esn_typography( 'font_footer_logo', 'font-weight', '700' ); ?>;
	--es-font-footer-logo-style: <?php esn_typography( 'font_footer_logo', 'font-style', 'normal' ); ?>;
	--es-font-footer-logo-letter-spacing: <?php esn_typography( 'font_footer_logo', 'letter-spacing', 'normal' ); ?>;
	--es-font-footer-logo-text-transform: <?php esn_typography( 'font_footer_logo', 'text-transform', 'none' ); ?>;

	/* Headings --------------- */

	/* Headings */
	--es-font-headings-family: <?php esn_typography( 'font_headings', 'font-family', 'Roboto' ); ?>;
	--es-font-headings-weight: <?php esn_typography( 'font_headings', 'font-weight', '700' ); ?>;
	--es-font-headings-style: <?php esn_typography( 'font_headings', 'font-style', 'normal' ); ?>;
	--es-font-headings-line-height: <?php esn_typography( 'font_headings', 'line-height', '1.2' ); ?>;
	--es-font-headings-letter-spacing: <?php esn_typography( 'font_headings', 'letter-spacing', 'normal' ); ?>;
	--es-font-headings-text-transform: <?php esn_typography( 'font_headings', 'text-transform', 'none' ); ?>;

	/* Menu Font --------------- */

	/* Menu */
	/* Used for main top level menu elements. */
	--es-font-menu-family: <?php esn_typography( 'font_menu', 'font-family', 'Roboto' ); ?>;
	--es-font-menu-size: <?php esn_typography( 'font_menu', 'font-size', '1rem' ); ?>;
	--es-font-menu-weight: <?php esn_typography( 'font_menu', 'font-weight', '500' ); ?>;
	--es-font-menu-style: <?php esn_typography( 'font_menu', 'font-style', 'normal' ); ?>;
	--es-font-menu-letter-spacing: <?php esn_typography( 'font_menu', 'letter-spacing', 'normal' ); ?>;
	--es-font-menu-text-transform: <?php esn_typography( 'font_menu', 'text-transform', 'none' ); ?>;
	--es-font-menu-line-height: <?php esn_typography( 'font_menu', 'line-height', '1.2' ); ?>;

	/* Submenu Font */
	/* Used for submenu elements. */
	--es-font-submenu-family: <?php esn_typography( 'font_submenu', 'font-family', 'Roboto' ); ?>;
	--es-font-submenu-size: <?php esn_typography( 'font_submenu', 'font-size', '1rem' ); ?>;
	--es-font-submenu-weight: <?php esn_typography( 'font_submenu', 'font-weight', '500' ); ?>;
	--es-font-submenu-style: <?php esn_typography( 'font_submenu', 'font-style', 'normal' ); ?>;
	--es-font-submenu-letter-spacing: <?php esn_typography( 'font_submenu', 'letter-spacing', 'normal' ); ?>;
	--es-font-submenu-text-transform: <?php esn_typography( 'font_submenu', 'text-transform', 'none' ); ?>;
	--es-font-submenu-line-height: <?php esn_typography( 'font_submenu', 'line-height', '1.2' ); ?>;

	/* Footer Menu */
	--es-font-footer-menu-family: <?php esn_typography( 'font_footer_menu', 'font-family', 'Roboto' ); ?>;
	--es-font-footer-menu-size: <?php esn_typography( 'font_footer_menu', 'font-size', '0.75rem' ); ?>;
	--es-font-footer-menu-weight: <?php esn_typography( 'font_footer_menu', 'font-weight', '500' ); ?>;
	--es-font-footer-menu-style: <?php esn_typography( 'font_footer_menu', 'font-style', 'normal' ); ?>;
	--es-font-footer-menu-letter-spacing: <?php esn_typography( 'font_footer_menu', 'letter-spacing', 'normal' ); ?>;
	--es-font-footer-menu-text-transform: <?php esn_typography( 'font_footer_menu', 'text-transform', 'uppercase' ); ?>;
	--es-font-footer-menu-line-height: <?php esn_typography( 'font_footer_menu', 'line-height', '1.2' ); ?>;

	/* Footer Submenu Font */
	--es-font-footer-submenu-family: <?php esn_typography( 'font_footer_submenu', 'font-family', 'Roboto' ); ?>;
	--es-font-footer-submenu-size: <?php esn_typography( 'font_footer_submenu', 'font-size', '1rem' ); ?>;
	--es-font-footer-submenu-weight: <?php esn_typography( 'font_footer_submenu', 'font-weight', '500' ); ?>;
	--es-font-footer-submenu-style: <?php esn_typography( 'font_footer_submenu', 'font-style', 'normal' ); ?>;
	--es-font-footer-submenu-letter-spacing: <?php esn_typography( 'font_footer_submenu', 'letter-spacing', 'normal' ); ?>;
	--es-font-footer-submenu-text-transform: <?php esn_typography( 'font_footer_submenu', 'text-transform', 'none' ); ?>;
	--es-font-footer-submenu-line-height: <?php esn_typography( 'font_footer_submenu', 'line-height', '1.2' ); ?>;
}
