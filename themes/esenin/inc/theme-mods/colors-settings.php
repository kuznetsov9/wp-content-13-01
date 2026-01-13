<?php
/**
 * Colors
 *
 * @package Esenin
 */

ESN_Customizer::add_panel(
	'colors',
	array(
		'title' => esc_html__( 'Colors', 'esenin' ),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'color_scheme',
		'label'    => esc_html__( 'Site Color Scheme', 'esenin' ),
		'section'  => 'colors',
		'default'  => 'system',
		'choices'  => array(
			'system' => esc_html__( 'User’s system preference', 'esenin' ),
			'light'  => esc_html__( 'Light', 'esenin' ),
			'dark'   => esc_html__( 'Dark', 'esenin' ),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'color_scheme_toggle',
		'label'    => esc_html__( 'Enable dark/light mode toggle', 'esenin' ),
		'section'  => 'colors',
		'default'  => true,
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'divider',
		'settings' => wp_unique_id( 'divider' ),
		'section'  => 'colors',
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_primary',
		'label'    => esc_html__( 'Primary Color', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#000',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-primary-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_primary_color_is_dark',
		'label'    => esc_html__( 'Primary Color Dark', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#FFFFFF',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-primary-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_secondary',
		'label'    => esc_html__( 'Secondary Color', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#606060',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-secondary-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_secondary_color_is_dark',
		'label'    => esc_html__( 'Secondary Color Dark', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#CDCDCD',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-secondary-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_accent',
		'label'    => esc_html__( 'Accent Color', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#00ad64',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-accent-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_accent_color_is_dark',
		'label'    => esc_html__( 'Accent Color Dark', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#FFFFFF',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-accent-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'divider',
		'settings' => wp_unique_id( 'divider' ),
		'section'  => 'colors',
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'heading',
		'label'    => esc_html__( 'Site Background Color', 'esenin' ),
		'settings' => wp_unique_id( 'heading' ),
		'section'  => 'colors',
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_site_background_start',
		'label'    => esc_html__( 'Gradient Start', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#f4f4f5', 
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-site-background-start',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_site_background_start_is_dark',
		'label'    => esc_html__( 'Gradient Start Dark', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#1C1C1C',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-site-background-start',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_site_background_end',
		'label'    => esc_html__( 'Gradient End', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#f4f4f5',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-site-background-end',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_site_background_end_is_dark',
		'label'    => esc_html__( 'Gradient End Dark', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#1C1C1C',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-site-background-end',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'divider',
		'settings' => wp_unique_id( 'divider' ),
		'section'  => 'colors',
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_layout_background',
		'label'    => esc_html__( 'Layout Background', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#FFFFFF',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-layout-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_layout_background_is_dark',
		'label'    => esc_html__( 'Layout Background Dark', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#222222',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-layout-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_offcanvas_background',
		'label'    => esc_html__( 'Offcanvas Background', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#FFFFFF',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-offcanvas-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_offcanvas_background_is_dark',
		'label'    => esc_html__( 'Offcanvas Background Dark', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#222222',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-offcanvas-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'divider',
		'settings' => wp_unique_id( 'divider' ),
		'section'  => 'colors',
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'heading',
		'label'    => esc_html__( 'Site Header Color', 'esenin' ),
		'settings' => wp_unique_id( 'heading' ),
		'section'  => 'colors',
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_header_background',
		'label'    => esc_html__( 'Header Background', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#f4f4f5',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-header-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_header_background_is_dark',
		'label'    => esc_html__( 'Header Background Dark', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#1C1C1C',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-header-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_header_submenu_background',
		'label'    => esc_html__( 'Header Submenu Background', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#FFFFFF',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-header-submenu-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_header_submenu_background_is_dark',
		'label'    => esc_html__( 'Header Submenu Background Dark', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#222222',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-header-submenu-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_header_highlight_background',
		'label'    => esc_html__( 'Highlight Color', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#e9e9e9',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-header-highlight-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'color-alpha',
		'settings' => 'color_header_highlight_background_is_dark',
		'label'    => esc_html__( 'Highlight Color Dark', 'esenin' ),
		'section'  => 'colors',
		'default'  => '#3D3D3D',
		'alpha'    => true,
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-header-highlight-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'divider',
		'settings' => wp_unique_id( 'divider' ),
		'section'  => 'colors',
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'color_advanced_settings',
		'label'    => esc_html__( 'Display advanced color settings', 'esenin' ),
		'section'  => 'colors',
		'default'  => true,
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'divider',
		'settings'        => wp_unique_id( 'divider' ),
		'section'         => 'colors',
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => false,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_input_background',
		'label'           => esc_html__( 'Input Background', 'esenin' ),
		'default'         => '#FFFFFF',
		'section'         => 'colors',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-input-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_input_background_is_dark',
		'label'           => esc_html__( 'Input Background Dark', 'esenin' ),
		'default'         => '#222222',
		'section'         => 'colors',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-input-background',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_input',
		'label'           => esc_html__( 'Input Color', 'esenin' ),
		'default'         => '#000',
		'section'         => 'colors',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-input-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_input_color_is_dark',
		'label'           => esc_html__( 'Input Color Dark', 'esenin' ),
		'default'         => '#FFFFFF',
		'section'         => 'colors',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-input-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'divider',
		'settings'        => wp_unique_id( 'divider' ),
		'section'         => 'colors',
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'heading',
		'label'           => esc_html__( 'Site Buttons Color', 'esenin' ),
		'settings'        => wp_unique_id( 'heading' ),
		'section'         => 'colors',
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_start',
		'label'           => esc_html__( 'Primary Gradient Start', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#00ad64',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-button-background-start',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_start_is_dark',
		'label'           => esc_html__( 'Primary Dark Gradient Start', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#0f0f0f',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-button-background-start',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_end',
		'label'           => esc_html__( 'Primary Gradient End', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#00ad64',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-button-background-end',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_end_is_dark',
		'label'           => esc_html__( 'Primary Dark Gradient End', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#0F0F0F',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-button-background-end',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button',
		'label'           => esc_html__( 'Primary Text Color', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#FFFFFF',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-button-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_color_is_dark',
		'label'           => esc_html__( 'Primary Text Color Dark', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#FFFFFF',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-button-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_hover_start',
		'label'           => esc_html__( 'Hover Gradient Start', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#009a59',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-button-hover-background-start',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_hover_start_is_dark',
		'label'           => esc_html__( 'Hover Dark Gradient Start', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#010101',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-button-hover-background-start',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_hover_end',
		'label'           => esc_html__( 'Hover Gradient End', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#009a59',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-button-hover-background-end',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_background_hover_end_is_dark',
		'label'           => esc_html__( 'Hover Dark Gradient End', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#010101',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-button-hover-background-end',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_hover',
		'label'           => esc_html__( 'Hover Text Color', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#FFFFFF',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-button-hover-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_button_hover_color_is_dark',
		'label'           => esc_html__( 'Hover Text Color Dark', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#FFFFFF',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-button-hover-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'divider',
		'settings'        => wp_unique_id( 'divider' ),
		'section'         => 'colors',
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_border',
		'label'           => esc_html__( 'Border Color', 'esenin' ),
		'description'     => esc_html__( 'Used on Form Inputs, Separators etc.', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#e4e4e7',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-light-border-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_border_color_is_dark',
		'label'           => esc_html__( 'Border Color Dark', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#2E2E2E',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-dark-border-color',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_overlay',
		'label'           => esc_html__( 'Overlay Background', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#000000',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root, [data-scheme="light"]',
				'property' => '--es-light-overlay-background-rgb',
				'context'  => array( 'editor', 'front' ),
				'convert'  => 'rgb',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'color-alpha',
		'settings'        => 'color_overlay_color_is_dark',
		'label'           => esc_html__( 'Overlay Background Dark', 'esenin' ),
		'section'         => 'colors',
		'default'         => '#000000',
		'alpha'           => true,
		'output'          => array(
			array(
				'element'  => ':root, [data-scheme="dark"]',
				'property' => '--es-dark-overlay-background-rgb',
				'context'  => array( 'editor', 'front' ),
				'convert'  => 'rgb',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'color_advanced_border_settings',
		'label'    => esc_html__( 'Display border radius settings', 'esenin' ),
		'section'  => 'colors',
		'default'  => false,
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'divider',
		'settings'        => wp_unique_id( 'divider' ),
		'section'         => 'color_border_settings',
		'active_callback' => array(
			array(
				'setting'  => 'color_advanced_border_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'color_layout elements_border_radius',
		'label'             => esc_html__( 'Layout Elements Border Radius', 'esenin' ),
		'description'       => esc_html__( 'Used on Form Elements, Blockquotes, Block Groups etc.', 'esenin' ),
		'section'           => 'colors',
		'default'           => '8px',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-layout-elements-border-radius',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback'   => array(
			array(
				'setting'  => 'color_advanced_border_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'color_thumbnail_border_radius',
		'label'             => esc_html__( 'Thumbnail Border Radius', 'esenin' ),
		'section'           => 'colors',
		'default'           => '10px',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-thumbnail-border-radius',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback'   => array(
			array(
				'setting'  => 'color_advanced_border_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'color_button_border_radius',
		'label'             => esc_html__( 'Button Border Radius', 'esenin' ),
		'section'           => 'colors',
		'default'           => '8px',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-button-border-radius',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback'   => array(
			array(
				'setting'  => 'color_advanced_border_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'color_input_border_radius',
		'label'             => esc_html__( 'Input Border Radius', 'esenin' ),
		'section'           => 'colors',
		'default'           => '8px',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-input-border-radius',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback'   => array(
			array(
				'setting'  => 'color_advanced_border_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'color_tag_border_radius',
		'label'             => esc_html__( 'Tag Border Radius', 'esenin' ),
		'section'           => 'colors',
		'default'           => '6px',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-tag-border-radius',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback'   => array(
			array(
				'setting'  => 'color_advanced_border_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);
