<?php
/**
 * Header Settings
 *
 * @package Esenin
 */

ESN_Customizer::add_section(
	'header',
	array(
		'title' => esc_html__( 'Header Settings', 'esenin' ),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'header_collapsible_common',
		'section'     => 'header',
		'label'       => esc_html__( 'Common', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => true,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'header_initial_height',
		'label'    => esc_html__( 'Header Initial Height', 'esenin' ),
		'section'  => 'header',
		'default'  => '70px',
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-header-initial-height',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'header_height',
		'label'    => esc_html__( 'Header Height', 'esenin' ),
		'section'  => 'header',
		'default'  => '70px',
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-header-height',
			),
		),
	)
);


ESN_Customizer::add_field(
	array(
		'type'     => 'dimension',
		'settings' => 'header_border_width',
		'label'    => esc_html__( 'Header Border Width', 'esenin' ),
		'section'  => 'header',
		'default'  => '1px',
		'output'   => array(
			array(
				'element'  => ':root',
				'property' => '--es-header-border-width',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'checkbox',
		'settings'    => 'navbar_sticky',
		'label'       => esc_html__( 'Make navigation bar sticky', 'esenin' ),
		'description' => esc_html__( 'Enabling this option will make navigation bar visible when scrolling.', 'esenin' ),
		'section'     => 'header',
		'default'     => true,
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'navbar_smart_sticky',
		'label'           => esc_html__( 'Enable the smart sticky feature', 'esenin' ),
		'description'     => esc_html__( 'Enabling this option will reveal navigation bar when scrolling up and hide it when scrolling down.', 'esenin' ),
		'section'         => 'header',
		'default'         => false,
		'active_callback' => array(
			array(
				'setting'  => 'navbar_sticky',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

/* ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'header_offcanvas',
		'label'    => esc_html__( 'Display offcanvas toggle button', 'esenin' ),
		'section'  => 'header',
		'default'  => false,
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'header_navigation_menu',
		'label'    => esc_html__( 'Display navigation menu', 'esenin' ),
		'section'  => 'header',
		'default'  => false,
	)
); */

ESN_Customizer::add_field(
	array(
		'type'     => 'collapsible',
		'settings' => 'header_collapsible_search',
		'section'  => 'header',
		'label'    => esc_html__( 'Search', 'esenin' ),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'header_search_button',
		'label'    => esc_html__( 'Display search button', 'esenin' ),
		'section'  => 'header',
		'default'  => true,
	)
);
/* ////////////////
ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'header_search_show_categories',
		'label'           => esc_html__( 'Display categories', 'esenin' ),
		'section'         => 'header',
		'default'         => false,
		'active_callback' => array(
			array(
				'setting'  => 'header_search_button',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'header_search_heading',
		'label'           => esc_html__( 'Heading', 'esenin' ),
		'section'         => 'header',
		'default'         => esc_html__( 'Что будем искать?', 'esenin' ),
		'active_callback' => array(
			array(
				'setting'  => 'header_search_button',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'header_search_filter_categories',
		'label'           => esc_html__( 'Categories', 'esenin' ),
		'description'     => esc_html__( 'Add comma-separated list of category slugs. For example: &laquo;travel, lifestyle, food&raquo;. Leave empty for all categories.', 'esenin' ),
		'section'         => 'header',
		'default'         => '',
		'active_callback' => array(
			array(
				'setting'  => 'header_search_button',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'header_search_show_categories',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'header_search_categories_limit',
		'label'           => esc_html__( 'Limit', 'esenin' ),
		'section'         => 'header',
		'default'         => 4,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 99,
			'step' => 1,
		),
		'active_callback' => array(
			array(
				'setting'  => 'header_search_button',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'header_search_show_categories',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'header_search_filter_categories',
				'operator' => '==',
				'value'    => '',
			),
		),
	)
); */

ESN_Customizer::add_field(
	array(
		'type'     => 'collapsible',
		'settings' => 'header_collapsible_custom_button',
		'section'  => 'header',
		'label'    => esc_html__( 'Custom Button', 'esenin' ),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'header_custom_button',
		'label'    => esc_html__( 'Display custom button', 'esenin' ),
		'section'  => 'header',
		'default'  => false,
	)
);


ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'header_custom_button_label',
		'label'           => esc_html__( 'Button Label', 'esenin' ),
		'section'         => 'header',
		'default'         => '',
		'active_callback' => array(
			array(
				'setting'  => 'header_custom_button',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'header_custom_button_link',
		'label'           => esc_html__( 'Button Link', 'esenin' ),
		'section'         => 'header',
		'default'         => '',
		'active_callback' => array(
			array(
				'setting'  => 'header_custom_button',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'header_custom_button_target',
		'section'         => 'header',
		'label'           => esc_html__( 'Target', 'esenin' ),
		'default'         => '_self',
		'choices'         => array(
			'_self'  => esc_html__( 'In the active tab', 'esenin' ),
			'_blank' => esc_html__( 'In a new tab', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'header_custom_button',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
	)
);
