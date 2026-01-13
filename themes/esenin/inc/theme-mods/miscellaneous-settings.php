<?php
/**
 * Miscellaneous Settings
 *
 * @package Esenin
 */

ESN_Customizer::add_section(
	'miscellaneous',
	array(
		'title' => esc_html__( 'Miscellaneous Settings', 'esenin' ),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'misc_published_date',
		'label'    => esc_html__( 'Display published date instead of modified date', 'esenin' ),
		'section'  => 'miscellaneous',
		'default'  => true,
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'misc_social_links_section',
		'label'       => esc_html__( 'Social Links', 'esenin' ),
		'section'     => 'miscellaneous',
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'misc_social_links',
		'label'    => esc_html__( 'Enable social links', 'esenin' ),
		'section'  => 'miscellaneous',
		'default'  => false,
	)
);

// Social 1.
ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_1',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Social Link 1', 'esenin' ),
		'default'         => 'none',
		'choices'         => array(
			'none'   => esc_html__( 'None', 'esenin' ),
			'preset' => esc_html__( 'Preset', 'esenin' ),
			'custom' => esc_html__( 'Custom', 'esenin' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'misc_social_links',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_1_network',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Social Network', 'esenin' ),
		'default'         => 'instagram',
		'choices'         => array(
		    'vk'        => esc_html__( 'Vkontakte', 'esenin' ),
			'facebook'  => esc_html__( 'Facebook', 'esenin' ),
			'instagram' => esc_html__( 'Instagram', 'esenin' ),
			'twitter'   => esc_html__( 'X (Twitter)', 'esenin' ),
			'youtube'   => esc_html__( 'Youtube', 'esenin' ),
			'linkedin'  => esc_html__( 'LinkedIn', 'esenin' ),
			'pinterest' => esc_html__( 'Pinterest', 'esenin' ),
			'dribbble'  => esc_html__( 'Dribbble', 'esenin' ),
			'behance'   => esc_html__( 'Behance', 'esenin' ),
			'reddit'    => esc_html__( 'Reddit', 'esenin' ),
			'github'    => esc_html__( 'Github', 'esenin' ),
			'telegram'  => esc_html__( 'Telegram', 'esenin' ),			
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_1',
					'operator' => '==',
					'value'    => 'preset',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'image',
		'settings'        => 'misc_social_1_icon',
		'label'           => esc_html__( 'Icon', 'esenin' ),
		'description'     => esc_html__( 'Please upload the 2x version of your icon via Media Library with ', 'esenin' ) . '<code>@2x</code>' . esc_html__( ' suffix for Retina screens support. For example ', 'esenin' ) . '<code>icon@2x.png</code>' . esc_html__( '. Recommended width and height is 20px (40px for Retina version).', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_1',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_social_1_label',
		'label'           => esc_html__( 'Label', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_1',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_social_1_url',
		'label'           => esc_html__( 'URL', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_1',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_1_target',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Target', 'esenin' ),
		'default'         => '_blank',
		'choices'         => array(
			'_self'  => esc_html__( 'In the active tab', 'esenin' ),
			'_blank' => esc_html__( 'In a new tab', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_1',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

// Social 2.
ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_2',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Social Link 2', 'esenin' ),
		'default'         => 'none',
		'choices'         => array(
			'none'   => esc_html__( 'None', 'esenin' ),
			'preset' => esc_html__( 'Preset', 'esenin' ),
			'custom' => esc_html__( 'Custom', 'esenin' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'misc_social_links',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_2_network',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Social Network', 'esenin' ),
		'default'         => 'instagram',
		'choices'         => array(
			'vk'        => esc_html__( 'Vkontakte', 'esenin' ),
			'facebook'  => esc_html__( 'Facebook', 'esenin' ),
			'instagram' => esc_html__( 'Instagram', 'esenin' ),
			'twitter'   => esc_html__( 'X (Twitter)', 'esenin' ),
			'youtube'   => esc_html__( 'Youtube', 'esenin' ),
			'linkedin'  => esc_html__( 'LinkedIn', 'esenin' ),
			'pinterest' => esc_html__( 'Pinterest', 'esenin' ),
			'dribbble'  => esc_html__( 'Dribbble', 'esenin' ),
			'behance'   => esc_html__( 'Behance', 'esenin' ),
			'reddit'    => esc_html__( 'Reddit', 'esenin' ),
			'github'    => esc_html__( 'Github', 'esenin' ),
			'telegram'  => esc_html__( 'Telegram', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_2',
					'operator' => '==',
					'value'    => 'preset',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'image',
		'settings'        => 'misc_social_2_icon',
		'label'           => esc_html__( 'Icon', 'esenin' ),
		'description'     => esc_html__( 'Please upload the 2x version of your icon via Media Library with ', 'esenin' ) . '<code>@2x</code>' . esc_html__( ' suffix for Retina screens support. For example ', 'esenin' ) . '<code>icon@2x.png</code>' . esc_html__( '. Recommended width and height is 20px (40px for Retina version).', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_2',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_social_2_label',
		'label'           => esc_html__( 'Label', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_2',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_social_2_url',
		'label'           => esc_html__( 'URL', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_2',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_2_target',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Target', 'esenin' ),
		'default'         => '_blank',
		'choices'         => array(
			'_self'  => esc_html__( 'In the active tab', 'esenin' ),
			'_blank' => esc_html__( 'In a new tab', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_2',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

// Social 3.
ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_3',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Social Link 3', 'esenin' ),
		'default'         => 'none',
		'choices'         => array(
			'none'   => esc_html__( 'None', 'esenin' ),
			'preset' => esc_html__( 'Preset', 'esenin' ),
			'custom' => esc_html__( 'Custom', 'esenin' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'misc_social_links',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_3_network',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Social Network', 'esenin' ),
		'default'         => 'instagram',
		'choices'         => array(
			'vk'        => esc_html__( 'Vkontakte', 'esenin' ),
			'facebook'  => esc_html__( 'Facebook', 'esenin' ),
			'instagram' => esc_html__( 'Instagram', 'esenin' ),
			'twitter'   => esc_html__( 'X (Twitter)', 'esenin' ),
			'youtube'   => esc_html__( 'Youtube', 'esenin' ),
			'linkedin'  => esc_html__( 'LinkedIn', 'esenin' ),
			'pinterest' => esc_html__( 'Pinterest', 'esenin' ),
			'dribbble'  => esc_html__( 'Dribbble', 'esenin' ),
			'behance'   => esc_html__( 'Behance', 'esenin' ),
			'reddit'    => esc_html__( 'Reddit', 'esenin' ),
			'github'    => esc_html__( 'Github', 'esenin' ),
			'telegram'  => esc_html__( 'Telegram', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_3',
					'operator' => '==',
					'value'    => 'preset',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'image',
		'settings'        => 'misc_social_3_icon',
		'label'           => esc_html__( 'Icon', 'esenin' ),
		'description'     => esc_html__( 'Please upload the 2x version of your icon via Media Library with ', 'esenin' ) . '<code>@2x</code>' . esc_html__( ' suffix for Retina screens support. For example ', 'esenin' ) . '<code>icon@2x.png</code>' . esc_html__( '. Recommended width and height is 20px (40px for Retina version).', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_3',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_social_3_label',
		'label'           => esc_html__( 'Label', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_3',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_social_3_url',
		'label'           => esc_html__( 'URL', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_3',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_3_target',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Target', 'esenin' ),
		'default'         => '_blank',
		'choices'         => array(
			'_self'  => esc_html__( 'In the active tab', 'esenin' ),
			'_blank' => esc_html__( 'In a new tab', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_3',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

// Social 4.
ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_4',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Social Link 4', 'esenin' ),
		'default'         => 'none',
		'choices'         => array(
			'none'   => esc_html__( 'None', 'esenin' ),
			'preset' => esc_html__( 'Preset', 'esenin' ),
			'custom' => esc_html__( 'Custom', 'esenin' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'misc_social_links',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_4_network',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Social Network', 'esenin' ),
		'default'         => 'instagram',
		'choices'         => array(
			'vk'        => esc_html__( 'Vkontakte', 'esenin' ),
			'facebook'  => esc_html__( 'Facebook', 'esenin' ),
			'instagram' => esc_html__( 'Instagram', 'esenin' ),
			'twitter'   => esc_html__( 'X (Twitter)', 'esenin' ),
			'youtube'   => esc_html__( 'Youtube', 'esenin' ),
			'linkedin'  => esc_html__( 'LinkedIn', 'esenin' ),
			'pinterest' => esc_html__( 'Pinterest', 'esenin' ),
			'dribbble'  => esc_html__( 'Dribbble', 'esenin' ),
			'behance'   => esc_html__( 'Behance', 'esenin' ),
			'reddit'    => esc_html__( 'Reddit', 'esenin' ),
			'github'    => esc_html__( 'Github', 'esenin' ),
			'telegram'  => esc_html__( 'Telegram', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_4',
					'operator' => '==',
					'value'    => 'preset',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'image',
		'settings'        => 'misc_social_4_icon',
		'label'           => esc_html__( 'Icon', 'esenin' ),
		'description'     => esc_html__( 'Please upload the 2x version of your icon via Media Library with ', 'esenin' ) . '<code>@2x</code>' . esc_html__( ' suffix for Retina screens support. For example ', 'esenin' ) . '<code>icon@2x.png</code>' . esc_html__( '. Recommended width and height is 20px (40px for Retina version).', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_4',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_social_4_label',
		'label'           => esc_html__( 'Label', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_4',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_social_4_url',
		'label'           => esc_html__( 'URL', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_4',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_4_target',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Target', 'esenin' ),
		'default'         => '_blank',
		'choices'         => array(
			'_self'  => esc_html__( 'In the active tab', 'esenin' ),
			'_blank' => esc_html__( 'In a new tab', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_4',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);


// Social 5.
ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_5',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Social Link 5', 'esenin' ),
		'default'         => 'none',
		'choices'         => array(
			'none'   => esc_html__( 'None', 'esenin' ),
			'preset' => esc_html__( 'Preset', 'esenin' ),
			'custom' => esc_html__( 'Custom', 'esenin' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'misc_social_links',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_5_network',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Social Network', 'esenin' ),
		'default'         => 'instagram',
		'choices'         => array(
			'vk'        => esc_html__( 'Vkontakte', 'esenin' ),
			'facebook'  => esc_html__( 'Facebook', 'esenin' ),
			'instagram' => esc_html__( 'Instagram', 'esenin' ),
			'twitter'   => esc_html__( 'X (Twitter)', 'esenin' ),
			'youtube'   => esc_html__( 'Youtube', 'esenin' ),
			'linkedin'  => esc_html__( 'LinkedIn', 'esenin' ),
			'pinterest' => esc_html__( 'Pinterest', 'esenin' ),
			'dribbble'  => esc_html__( 'Dribbble', 'esenin' ),
			'behance'   => esc_html__( 'Behance', 'esenin' ),
			'reddit'    => esc_html__( 'Reddit', 'esenin' ),
			'github'    => esc_html__( 'Github', 'esenin' ),
			'telegram'  => esc_html__( 'Telegram', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_5',
					'operator' => '==',
					'value'    => 'preset',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'image',
		'settings'        => 'misc_social_5_icon',
		'label'           => esc_html__( 'Icon', 'esenin' ),
		'description'     => esc_html__( 'Please upload the 2x version of your icon via Media Library with ', 'esenin' ) . '<code>@2x</code>' . esc_html__( ' suffix for Retina screens support. For example ', 'esenin' ) . '<code>icon@2x.png</code>' . esc_html__( '. Recommended width and height is 20px (40px for Retina version).', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_5',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_social_5_label',
		'label'           => esc_html__( 'Label', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_5',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_social_5_url',
		'label'           => esc_html__( 'URL', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => '',
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_5',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'misc_social_5_target',
		'section'         => 'miscellaneous',
		'label'           => esc_html__( 'Target', 'esenin' ),
		'default'         => '_blank',
		'choices'         => array(
			'_self'  => esc_html__( 'In the active tab', 'esenin' ),
			'_blank' => esc_html__( 'In a new tab', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'misc_social_links',
					'operator' => '==',
					'value'    => true,
				),
			),
			array(
				array(
					'setting'  => 'misc_social_5',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),
	)
);


ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'misc_sticky_sidebar_section',
		'label'       => esc_html__( 'Sticky Sidebar', 'esenin' ),
		'section'     => 'miscellaneous',
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'misc_sticky_sidebar',
		'label'    => esc_html__( 'Enable sticky sidebar', 'esenin' ),
		'section'  => 'miscellaneous',
		'default'  => true,
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'misc_sticky_sidebar_method',
		'label'           => esc_html__( 'Sticky Method', 'esenin' ),
		'section'         => 'miscellaneous',
		'default'         => 'es-stick-last',
		'choices'         => array(
			'es-stick-to-top'    => esc_html__( 'Sidebar top edge', 'esenin' ),
			'es-stick-to-bottom' => esc_html__( 'Sidebar bottom edge', 'esenin' ),
			'es-stick-last'      => esc_html__( 'Last widget top edge', 'esenin' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'misc_sticky_sidebar',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'misc_scroll_to_top_section',
		'label'       => esc_html__( 'Scroll to Top', 'esenin' ),
		'section'     => 'miscellaneous',
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'misc_scroll_to_top',
		'label'    => esc_html__( 'Enable scroll to top button', 'esenin' ),
		'section'  => 'miscellaneous',
		'default'  => true,
	)
);
/* ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'footer_collapsible_subscribe',
		'label'       => esc_html__( 'Subscribe', 'esenin' ),
		'section'     => 'miscellaneous',
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'misc_subscribe',
		'label'    => esc_html__( 'Enable subscribe section', 'esenin' ),
		'section'  => 'miscellaneous',
		'default'  => false,
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_subscribe_heading',
		'label'           => esc_html__( 'Heading', 'esenin' ),
		'section'         => 'miscellaneous',
		'active_callback' => array(
			array(
				'setting'  => 'misc_subscribe',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'textarea',
		'settings'          => 'misc_subscribe_description',
		'label'             => esc_html__( 'Description', 'esenin' ),
		'section'           => 'miscellaneous',
		'sanitize_callback' => function ( $val ) {
			return wp_kses( $val, 'content' );
		},
		'active_callback'   => array(
			array(
				'setting'  => 'misc_subscribe',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'misc_subscribe_mailchimp',
		'label'           => esc_html__( 'Mailchimp Form Link', 'esenin' ),
		'section'         => 'miscellaneous',
		'active_callback' => array(
			array(
				'setting'  => 'misc_subscribe',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'              => 'text',
		'settings'          => 'misc_subscribe_short_description',
		'label'             => esc_html__( 'Short Description', 'esenin' ),
		'section'           => 'miscellaneous',
		'sanitize_callback' => function ( $val ) {
			return wp_kses( $val, 'content' );
		},
		'active_callback'   => array(
			array(
				'setting'  => 'misc_subscribe',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
); */
