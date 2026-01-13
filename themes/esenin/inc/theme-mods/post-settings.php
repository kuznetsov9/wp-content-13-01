<?php
/**
 * Post Settings
 *
 * @package Esenin
 */

ESN_Customizer::add_section(
	'post_settings',
	array(
		'title' => esc_html__( 'Post Settings', 'esenin' ),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'post_collapsible_common',
		'section'     => 'post_settings',
		'label'       => esc_html__( 'Common', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => true,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'post_sidebar',
		'label'    => esc_html__( 'Default Sidebar', 'esenin' ),
		'section'  => 'post_settings',
		'default'  => 'right',
		'choices'  => array(
			'right'    => esc_html__( 'Right Sidebar', 'esenin' ),
			'disabled' => esc_html__( 'No Sidebar', 'esenin' ),
		),
	)
);

/* ESN_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'post_header_type',
		'label'    => esc_html__( 'Default Page Header Type', 'esenin' ),
		'section'  => 'post_settings',
		'default'  => 'standard',
		'choices'  => array(
			'standard' => esc_html__( 'Standard', 'esenin' ),
			'split'    => esc_html__( 'Split', 'esenin' ),
			'overlay'  => esc_html__( 'Overlay', 'esenin' ),
			'title'    => esc_html__( 'Page Title Only', 'esenin' ),
			'none'     => esc_html__( 'None', 'esenin' ),
		),
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'post_media_preview',
		'label'           => esc_html__( 'Standard Page Header Preview', 'esenin' ),
		'section'         => 'post_settings',
		'default'         => 'cropped',
		'choices'         => array(
			'cropped'   => esc_html__( 'Display Cropped Image', 'esenin' ),
			'uncropped' => esc_html__( 'Display Preview in Original Ratio', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'post_header_type',
					'operator' => '==',
					'value'    => 'standard',
				),
			),
		),
	)
); */



/*  ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_metabar',
		'label'    => esc_html__( 'Display metabar section', 'esenin' ),
		'section'  => 'post_settings',
		'default'  => false,
	)
); */

/*
ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'post_reading_time',
		'label'           => esc_html__( 'Display reading time', 'esenin' ),
		'section'         => 'post_settings',
		'default'         => true,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'post_metabar',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
	)
); 
*/
ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_subtitle',
		'label'    => esc_html__( 'Display excerpt as post subtitle', 'esenin' ),
		'section'  => 'post_settings',
		'default'  => true,
	)
);

/* ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_tags',
		'label'    => esc_html__( 'Display tags', 'esenin' ),
		'section'  => 'post_settings',
		'default'  => true,
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_footer',
		'label'    => esc_html__( 'Display post footer', 'esenin' ),
		'section'  => 'post_settings',
		'default'  => true,
	)
); */

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_prev_next',
		'label'    => esc_html__( 'Enable prev/next section', 'esenin' ),
		'section'  => 'post_settings',
		'default'  => true,
	)
);
/* ESN_Customizer::add_field(
	array(
		'type'     => 'collapsible',
		'settings' => 'post_collapsible_read_next',
		'section'  => 'post_settings',
		'label'    => esc_html__( 'Read Next Links', 'esenin' ),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_read_next',
		'label'    => esc_html__( 'Display read next links', 'esenin' ),
		'section'  => 'post_settings',
		'default'  => false,
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'post_read_next_posts',
		'label'           => esc_html__( 'Display posts', 'esenin' ),
		'description'     => esc_html__( 'The section will display posts from the current category, published after the current post\'s date, before it, or the newest posts. In case fewer than tree or four posts meet the requirements, the section will display other posts from the current category. In case there are fewer than three or four posts in the current category, the section will display posts from other categories.', 'esenin' ),
		'section'         => 'post_settings',
		'default'         => 'after',
		'choices'         => apply_filters(
			'esn_header_layouts',
			array(
				'after'  => esc_html__( 'After current post date', 'esenin' ),
				'before' => esc_html__( 'Before current post date', 'esenin' ),
				'new'    => esc_html__( 'Newest posts', 'esenin' ),
			)
		),
		'active_callback' => array(
			array(
				'setting'  => 'post_read_next',
				'operator' => '==',
				'value'    => false,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'post_read_next_image_orientation',
		'label'           => esc_html__( 'Image Orientation', 'esenin' ),
		'section'         => 'post_settings',
		'default'         => 'landscape-16-9',
		'choices'         => array(
			'original'       => esc_html__( 'Original', 'esenin' ),
			'landscape'      => esc_html__( 'Landscape 4:3', 'esenin' ),
			'landscape-3-2'  => esc_html__( 'Landscape 3:2', 'esenin' ),
			'landscape-16-9' => esc_html__( 'Landscape 16:9', 'esenin' ),
			'landscape-21-9' => esc_html__( 'Landscape 21:9', 'esenin' ),
			'portrait'       => esc_html__( 'Portrait 3:4', 'esenin' ),
			'portrait-2-3'   => esc_html__( 'Portrait 2:3', 'esenin' ),
			'square'         => esc_html__( 'Square', 'esenin' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'post_read_next',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'post_read_next_image_size',
		'label'           => esc_html__( 'Image Size', 'esenin' ),
		'section'         => 'post_settings',
		'default'         => 'esn-thumbnail',
		'choices'         => esn_get_list_available_image_sizes(),
		'active_callback' => array(
			array(
				'setting'  => 'post_read_next',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'read_next_excerpt',
		'label'           => esc_html__( 'Display excerpt', 'esenin' ),
		'section'         => 'post_settings',
		'default'         => true,
		'active_callback' => array(
			array(
				'setting'  => 'post_read_next',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'read_next_discover_more',
		'label'           => esc_html__( 'Display discover more', 'esenin' ),
		'section'         => 'post_settings',
		'default'         => false,
		'active_callback' => array(
			array(
				'setting'  => 'post_read_next',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'            => 'multicheck',
		'settings'        => 'post_read_next_meta',
		'label'           => esc_html__( 'Post Meta', 'esenin' ),
		'section'         => 'post_settings',
		'default'         => array( 'category', 'date', 'author', 'reading_time' ),		
		'choices'         => apply_filters(
			'esn_post_meta_choices',
			array(
				'category'     => esc_html__( 'Category', 'esenin' ),
				'reading_time' => esc_html__( 'Reading time', 'esenin' ),
				'date'         => esc_html__( 'Date', 'esenin' ),
				'author'       => esc_html__( 'Author', 'esenin' ),
				'comments'     => esc_html__( 'Comments', 'esenin' ),
				'views'        => esc_html__( 'Views', 'esenin' ),
			)
		),
		'active_callback' => array(
			array(
				'setting'  => 'post_read_next',
				'operator' => '==',
				'value'    => false,
			),
		),
	)
); */


// Auto Load next post.
/* ESN_Customizer::add_field(
	array(
		'type'     => 'collapsible',
		'settings' => 'post_collapsible_load_nextpost',
		'section'  => 'post_settings',
		'label'    => esc_html__( 'Auto Load Next Post', 'esenin' ),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'post_load_nextpost',
		'label'    => esc_html__( 'Enable the auto load next post feature', 'esenin' ),
		'section'  => 'post_settings',
		'default'  => false,
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'post_load_nextpost_same_category',
		'label'           => esc_html__( 'Auto load posts from the same category only', 'esenin' ),
		'section'         => 'post_settings',
		'default'         => false,
		'active_callback' => array(
			array(
				'setting'  => 'post_load_nextpost',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'post_load_nextpost_reverse',
		'label'           => esc_html__( 'Auto load previous posts instead of next ones', 'esenin' ),
		'section'         => 'post_settings',
		'default'         => false,
		'active_callback' => array(
			array(
				'setting'  => 'post_load_nextpost',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
); */
