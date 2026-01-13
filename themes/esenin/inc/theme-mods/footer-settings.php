<?php
/**
 * Footer Settings
 *
 * @package Esenin
 */

ESN_Customizer::add_section(
	'footer',
	array(
		'title' => esc_html__( 'Footer Settings', 'esenin' ),
	)
);

/* ESN_Customizer::add_field(
	array(
		'type'              => 'textarea',
		'settings'          => 'footer_text',
		'label'             => esc_html__( 'Footer Text', 'esenin' ),
		'section'           => 'footer',
		'sanitize_callback' => function ( $val ) {
			return wp_kses( $val, 'content' );
		},
	)
); */

ESN_Customizer::add_field(
	array(
		'type'              => 'textarea',
		'settings'          => 'footer_copyright',
		'label'             => esc_html__( 'Footer Copyright', 'esenin' ),
		'section'           => 'footer',
		'default'           => esc_html__( '© 2025 — Esenin. All Rights Reserved.', 'esenin' ),
		'sanitize_callback' => function ( $val ) {
			return wp_kses( $val, 'content' );
		},
	)
);
