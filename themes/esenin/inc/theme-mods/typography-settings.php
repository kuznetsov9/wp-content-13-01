<?php
/**
 * Typography
 *
 * @package Esenin
 */

ESN_Customizer::add_panel(
	'typography',
	array(
		'title' => esc_html__( 'Typography', 'esenin' ),
	)
);

ESN_Customizer::add_section(
	'typography_general',
	array(
		'title' => esc_html__( 'General', 'esenin' ),
		'panel' => 'typography',
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_base',
		'label'    => esc_html__( 'Base Font', 'esenin' ),
		'section'  => 'typography_general',
		'default'  => array(
			'font-family'    => 'Roboto',
			'variant'        => '400',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '1rem',
			'letter-spacing' => 'normal',
			'line-height'    => '1.55',
		),
		'choices'  => array(
			'variant' => array(
				'regular',
				'italic',
				'500italic',
				'500',
				'700',
				'700italic',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'typography',
		'settings'    => 'font_primary',
		'label'       => esc_html__( 'Primary Font', 'esenin' ),
		'description' => esc_html__( 'Used for buttons, and tags and other actionable elements.', 'esenin' ),
		'section'     => 'typography_general',
		'default'     => array(
			'font-family'    => 'Roboto',
			'variant'        => '500',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '1rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
			'line-height'    => '1.2',
		),
		'choices'     => array(
			'variant' => array(
				'regular',
				'italic',
				'400',
				'400italic',
				'700',
				'700italic',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'typography',
		'settings'    => 'font_secondary',
		'label'       => esc_html__( 'Secondary Font', 'esenin' ),
		'description' => esc_html__( 'Used for breadcrumbs and other secondary elements.', 'esenin' ),
		'section'     => 'typography_general',
		'default'     => array(
			'font-family'    => 'Roboto',
			'variant'        => '400',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '0.875rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
			'line-height'    => '1.55',
		),
		'choices'     => array(
			'variant' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'typography_advanced_settings',
		'label'    => esc_html__( 'Display advanced typography settings', 'esenin' ),
		'section'  => 'typography_general',
		'default'  => false,
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_section_headings',
		'label'           => esc_html__( 'Section Headings Font', 'esenin' ),
		'section'         => 'typography_general',
		'default'         => array(
			'font-family'    => 'Roboto',
			'variant'        => '700',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '0.75rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'uppercase',
			'line-height'    => '1.2',
		),
		'choices'         => array(
			'variant' => array(
				'regular',
				'italic',
				'500',
				'500italic',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'typography_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_post_title',
		'label'           => esc_html__( 'Post Title Font', 'esenin' ),
		'section'         => 'typography_general',
		'default'         => array(
			'font-family'    => 'Roboto',
			'variant'        => '500',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '3.25rem',
			'letter-spacing' => 'normal',
			'line-height'    => '1.2',
		),
		'choices'         => array(
			'variant' => array(
				'regular',
				'italic',
				'400',
				'400italic',
				'700',
				'700italic',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'typography_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_post_subtitle',
		'label'           => esc_html__( 'Post Subtitle Font', 'esenin' ),
		'section'         => 'typography_general',
		'default'         => array(
			'font-family'    => 'Roboto',
			'variant'        => '400',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '1.125rem',
			'letter-spacing' => 'normal',
			'line-height'    => '1.55',
		),
		'choices'         => array(
			'variant' => array(
				'regular',
				'italic',
				'500italic',
				'500',
				'700',
				'700italic',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'typography_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_category',
		'label'           => esc_html__( 'Post Category Font', 'esenin' ),
		'section'         => 'typography_general',
		'default'         => array(
			'font-family'    => 'Roboto',
			'variant'        => '700',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '0.6875rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'uppercase',
			'line-height'    => '1.2',
		),
		'choices'         => array(),
		'active_callback' => array(
			array(
				'setting'  => 'typography_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_post_meta',
		'label'           => esc_html__( 'Post Meta Font', 'esenin' ),
		'section'         => 'typography_general',
		'default'         => array(
			'font-family'    => 'Roboto',
			'variant'        => '500',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '0.9375rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
			'line-height'    => '1.2',
		),
		'choices'         => array(
			'variant' => array(
				'regular',
				'italic',
				'400',
				'400italic',
				'700',
				'700italic',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'typography_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_post_content',
		'label'           => esc_html__( 'Post Content Font', 'esenin' ),
		'section'         => 'typography_general',
		'default'         => array(
			'font-family'    => 'Roboto',
			'variant'        => '400',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '16px',
			'letter-spacing' => 'normal',
			'line-height'    => '1.55',
		),
		'choices'         => array(
			'variant' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'typography_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_input',
		'label'           => esc_html__( 'Input Font', 'esenin' ),
		'section'         => 'typography_general',
		'default'         => array(
			'font-family'    => 'Roboto',
			'variant'        => '400',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '0.875rem',
			'line-height'    => '1.55rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
		),
		'choices'         => array(),
		'active_callback' => array(
			array(
				'setting'  => 'typography_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_entry_title',
		'label'           => esc_html__( 'Entry Title Font', 'esenin' ),
		'section'         => 'typography_general',
		'default'         => array(
			'font-family'    => 'Roboto',
			'variant'        => '700',
			'subsets'        => array( 'cyrillic' ),
			'letter-spacing' => 'normal',
			'line-height'    => '1.2',
		),
		'choices'         => array(
			'variant' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'typography_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_excerpt',
		'label'           => esc_html__( 'Entry Excerpt Font', 'esenin' ),
		'section'         => 'typography_general',
		'default'         => array(
			'font-family'    => 'Roboto',
			'variant'        => '400',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '1rem',
			'letter-spacing' => 'normal',
			'line-height'    => '1.55',
		),
		'choices'         => array(
			'variant' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'600',
				'700',
				'700italic',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'typography_advanced_settings',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_section(
	'typography_logos',
	array(
		'title' => esc_html__( 'Logos', 'esenin' ),
		'panel' => 'typography',
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_main_logo',
		'label'           => esc_html__( 'Main Logo', 'esenin' ),
		'description'     => esc_html__( 'The main logo is used in the navigation bar and mobile view of your website.', 'esenin' ),
		'section'         => 'typography_logos',
		'default'         => array(
			'font-family'    => 'Roboto',
			'font-size'      => '1.8rem',
			'variant'        => '700',
			'subsets'        => array( 'cyrillic' ),
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
		),
		'choices'         => array(),
		'active_callback' => array(
			array(
				'setting'  => 'logo',
				'operator' => '==',
				'value'    => '',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'typography',
		'settings'        => 'font_footer_logo',
		'label'           => esc_html__( 'Footer Logo', 'esenin' ),
		'description'     => esc_html__( 'The footer logo is used in the site footer in desktop and mobile view.', 'esenin' ),
		'section'         => 'typography_logos',
		'default'         => array(
			'font-family'    => 'Roboto',
			'font-size'      => '1.8rem',
			'variant'        => '700',
			'subsets'        => array( 'cyrillic' ),
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
		),
		'choices'         => array(),
		'active_callback' => array(
			array(
				'setting'  => 'footer_logo',
				'operator' => '==',
				'value'    => '',
			),
		),
	)
);

ESN_Customizer::add_section(
	'typography_headings',
	array(
		'title' => esc_html__( 'Headings', 'esenin' ),
		'panel' => 'typography',
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_headings',
		'label'    => esc_html__( 'Headings', 'esenin' ),
		'section'  => 'typography_headings',
		'default'  => array(
			'font-family'    => 'Roboto',
			'variant'        => '500',
			'subsets'        => array( 'cyrillic' ),
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
			'line-height'    => '1.2',
		),
		'choices'  => array(
			'variant' => array(
				'regular',
				'italic',
				'500',
				'500italic',
				'700',
				'700italic',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'typography_headings_collapsible',
		'section'     => 'typography_headings',
		'label'       => esc_html__( 'Headings Font Size', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);


ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'font_h1_size',
		'label'             => esc_html__( 'Heading 1', 'esenin' ),
		'section'           => 'typography_headings',
		'default'           => '3.25rem',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-heading-1-font-size',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'font_h2_size',
		'label'             => esc_html__( 'Heading 2', 'esenin' ),
		'section'           => 'typography_headings',
		'default'           => '2.625rem',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-heading-2-font-size',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'font_h3_size',
		'label'             => esc_html__( 'Heading 3', 'esenin' ),
		'section'           => 'typography_headings',
		'default'           => '2.0625rem',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-heading-3-font-size',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'font_h4_size',
		'label'             => esc_html__( 'Heading 4', 'esenin' ),
		'section'           => 'typography_headings',
		'default'           => '1.5rem',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-heading-4-font-size',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'font_h5_size',
		'label'             => esc_html__( 'Heading 5', 'esenin' ),
		'section'           => 'typography_headings',
		'default'           => '1.3125rem',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-heading-5-font-size',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'dimension',
		'settings'          => 'font_h6_size',
		'label'             => esc_html__( 'Heading 6', 'esenin' ),
		'section'           => 'typography_headings',
		'default'           => '1.125rem',
		'sanitize_callback' => 'esc_html',
		'output'            => array(
			array(
				'element'  => ':root',
				'property' => '--es-heading-6-font-size',
				'context'  => array( 'editor', 'front' ),
			),
		),
	)
);

ESN_Customizer::add_section(
	'typography_navigation',
	array(
		'title' => esc_html__( 'Navigation', 'esenin' ),
		'panel' => 'typography',
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'typography',
		'settings'    => 'font_menu',
		'label'       => esc_html__( 'Menu Font', 'esenin' ),
		'description' => esc_html__( 'Used for main top level menu elements.', 'esenin' ),
		'section'     => 'typography_navigation',
		'default'     => array(
			'font-family'    => 'Roboto',
			'variant'        => '500',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '1rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
			'line-height'    => '1.2',
		),
		'choices'     => array(),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'typography',
		'settings'    => 'font_submenu',
		'label'       => esc_html__( 'Submenu Font', 'esenin' ),
		'description' => esc_html__( 'Used for submenu elements.', 'esenin' ),
		'section'     => 'typography_navigation',
		'default'     => array(
			'font-family'    => 'Roboto',
			'subsets'        => array( 'cyrillic' ),
			'variant'        => '500',
			'font-size'      => '1rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
			'line-height'    => '1.2',
		),
		'choices'     => array(),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_footer_menu',
		'label'    => esc_html__( 'Footer Menu Font', 'esenin' ),
		'section'  => 'typography_navigation',
		'default'  => array(
			'font-family'    => 'Roboto',
			'variant'        => '700',
			'subsets'        => array( 'cyrillic' ),
			'font-size'      => '0.75rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'uppercase',
			'line-height'    => '1.2',
		),
		'choices'  => array(),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'typography',
		'settings' => 'font_footer_submenu',
		'label'    => esc_html__( 'Footer Submenu Font', 'esenin' ),
		'section'  => 'typography_navigation',
		'default'  => array(
			'font-family'    => 'Roboto',
			'subsets'        => array( 'cyrillic' ),
			'variant'        => '500',
			'font-size'      => '1rem',
			'letter-spacing' => 'normal',
			'text-transform' => 'none',
			'line-height'    => '1.2',
		),
		'choices'  => array(),
	)
);
