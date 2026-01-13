<?php
/**
 * The template for displaying the hero layout
 *
 * @package Esenin
 */

/**
 * The esn_hero_before hook.
 *
 * @since 1.0.0
 */
do_action( 'esn_hero_before' );

switch ( get_theme_mod( 'home_hero_layout', 'hero-type-1' ) ) {
	case 'hero-type-1':
		get_template_part( 'template-parts/hero/hero-template-1' );
		break;
	case 'hero-type-2':
		get_template_part( 'template-parts/hero/hero-template-2' );
		break;
	case 'hero-type-3':
		get_template_part( 'template-parts/hero/hero-template-3' );
		break;
}

/**
 * The esn_hero_after hook.
 *
 * @since 1.0.0
 */
do_action( 'esn_hero_after' );
