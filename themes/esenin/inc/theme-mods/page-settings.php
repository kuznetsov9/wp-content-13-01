<?php
/**
 * Page Settings
 *
 * @package Esenin
 */

ESN_Customizer::add_section(
	'page_settings',
	array(
		'title' => esc_html__( 'Page Settings', 'esenin' ),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'page_sidebar',
		'label'    => esc_html__( 'Default Sidebar', 'esenin' ),
		'section'  => 'page_settings',
		'default'  => 'disabled',
		'choices'  => array(
			'right'    => esc_html__( 'Right Sidebar', 'esenin' ),
			'disabled' => esc_html__( 'No Sidebar', 'esenin' ),
		),
	)
);
/* ESN_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'page_header_type',
		'label'    => esc_html__( 'Page Header Type', 'esenin' ),
		'section'  => 'page_settings',
		'default'  => 'standard',
		'choices'  => array(
			'standard' => esc_html__( 'Standard', 'esenin' ),
			'split'    => esc_html__( 'Split', 'esenin' ),
			'overlay'  => esc_html__( 'Overlay', 'esenin' ),
			'title'    => esc_html__( 'Page Title Only', 'esenin' ),
			'none'     => esc_html__( 'None', 'esenin' ),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'page_media_preview',
		'label'           => esc_html__( 'Standard Page Header Preview', 'esenin' ),
		'section'         => 'page_settings',
		'default'         => 'cropped',
		'choices'         => array(
			'cropped'   => esc_html__( 'Display Cropped Image', 'esenin' ),
			'uncropped' => esc_html__( 'Display Preview in Original Ratio', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'page_header_type',
					'operator' => '==',
					'value'    => 'standard',
				),
			),
		),
	)
); */
