<?php
/**
 * Archive Settings
 *
 * @package Esenin
 */

ESN_Customizer::add_section(
	'archive_settings',
	array(
		'title' => esc_html__( 'Archive Settings', 'esenin' ),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'archive_collapsible_common',
		'section'     => 'archive_settings',
		'label'       => esc_html__( 'Common', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => true,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'archive_sidebar',
		'label'    => esc_html__( 'Default Sidebar', 'esenin' ),
		'section'  => 'archive_settings',
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
		'settings' => 'archive_layout',
		'label'    => esc_html__( 'Layout', 'esenin' ),
		'section'  => 'archive_settings',
		'default'  => 'full',
		'choices'  => array(
			'list'    => esc_html__( 'List Layout', 'esenin' ),
			'grid'    => esc_html__( 'Grid Layout', 'esenin' ), 
			'overlay' => esc_html__( 'Overlay Layout', 'esenin' ),
			'full'    => esc_html__( 'Full Post Layout', 'esenin' ),
		),
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'archive_excerpt',
		'label'           => esc_html__( 'Display excerpt', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => true,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'home_layout',
					'operator' => '!=',
					'value'    => 'full',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'archive_discover_more',
		'label'           => esc_html__( 'Display discover more', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => true,
		'active_callback' => array(
			array(
				array(
					'setting'  => 'home_layout',
					'operator' => '!=',
					'value'    => 'full',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'archive_image_orientation',
		'label'           => esc_html__( 'Image Orientation', 'esenin' ),
		'section'         => 'archive_settings',
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
				array(
					array(
						'setting'  => 'archive_layout',
						'operator' => '!=',
						'value'    => 'full',
					),
					array(
						'setting'  => 'archive_layout',
						'operator' => '!=',
						'value'    => 'overlay',
					),
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'archive_image_size',
		'label'           => esc_html__( 'Image Size', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => 'esn-thumbnail',
		'choices'         => esn_get_list_available_image_sizes(),
		'active_callback' => array(
			array(
				array(
					array(
						'setting'  => 'archive_layout',
						'operator' => '!=',
						'value'    => 'full',
					),
					array(
						'setting'  => 'archive_layout',
						'operator' => '!=',
						'value'    => 'overlay',
					),
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'archive_media_preview',
		'label'           => esc_html__( 'Post Preview Image Size', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => 'cropped',
		'choices'         => array(
			'cropped'   => esc_html__( 'Display Cropped Image', 'esenin' ),
			'uncropped' => esc_html__( 'Display Preview in Original Ratio', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'archive_layout',
					'operator' => '==',
					'value'    => 'full',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'archive_summary',
		'label'           => esc_html__( 'Full Post Summary', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => 'summary',
		'choices'         => array(
			'summary' => esc_html__( 'Use Excerpts', 'esenin' ),
			'content' => esc_html__( 'Use Read More Tag', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'archive_layout',
					'operator' => '==',
					'value'    => 'full',
				),
			),
		),
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'archive_pagination_type',
		'label'    => esc_html__( 'Pagination', 'esenin' ),
		'section'  => 'archive_settings',
		'default'  => 'standard',
		'choices'  => array(
			'standard'  => esc_html__( 'Standard', 'esenin' ),
			'load-more' => esc_html__( 'Load More Button', 'esenin' ),
			'infinite'  => esc_html__( 'Infinite Load', 'esenin' ),
		),
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'archive_collapsible_post_meta',
		'section'     => 'archive_settings',
		'label'       => esc_html__( 'Post Meta', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'     => 'multicheck',
		'settings' => 'archive_post_meta',
		'label'    => esc_html__( 'Post Meta', 'esenin' ),
		'section'  => 'archive_settings',
		'default'  => array( 'category', 'date', 'author', 'reading_time' ),
		'choices'  => apply_filters(
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
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'            => 'collapsible',
		'settings'        => 'archive_collapsible_number_of_olumns',
		'section'         => 'archive_settings',
		'label'           => esc_html__( 'Number of Columns', 'esenin' ),
		'input_attrs'     => array(
			'collapsed' => false,
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'archive_columns_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => 2,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 3,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'  => '.es-posts-area__archive.es-posts-area__grid',
				'property' => '--es-posts-area-grid-columns',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'archive_columns_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => 2,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 2,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-posts-area__grid',
				'property'    => '--es-posts-area-grid-columns',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'archive_columns_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => 1,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 2,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-posts-area__grid',
				'property'    => '--es-posts-area-grid-columns',
				'media_query' => '@media (max-width: 767.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'archive_collapsible_gap_between_rows',
		'section'     => 'archive_settings',
		'label'       => esc_html__( 'Gap between Rows', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_list_gap_between_rows_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '40px',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__archive.es-archive-list',
				'property' => '--es-posts-area-grid-row-gap',
			),
			array(
				'element'  => '.es-read-next .es-posts-area__read-next',
				'property' => '--es-posts-area-grid-row-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'list',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_grid_gap_between_rows_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '48px',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__archive.es-archive-grid',
				'property' => '--es-posts-area-grid-row-gap',
			),
			array(
				'element'  => '.es-read-next .es-posts-area__read-next',
				'property' => '--es-posts-area-grid-row-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_overlay_gap_between_rows_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__archive.es-archive-overlay',
				'property' => '--es-posts-area-grid-row-gap',
			),
			array(
				'element'  => '.es-read-next .es-posts-area__read-next',
				'property' => '--es-posts-area-grid-row-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'overlay',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_full_gap_between_rows_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '48px',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__archive.es-archive-full',
				'property' => '--es-posts-area-grid-row-gap',
			),
			array(
				'element'  => '.es-read-next .es-posts-area__read-next',
				'property' => '--es-posts-area-grid-row-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_list_gap_between_rows_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '40px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-list',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
			array(
				'element'     => '.es-read-next .es-posts-area__read-next',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'list',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_grid_gap_between_rows_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '38px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-grid',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
			array(
				'element'     => '.es-read-next .es-posts-area__read-next',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_overlay_gap_between_rows_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-overlay',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
			array(
				'element'     => '.es-read-next .es-posts-area__read-next',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'overlay',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_full_gap_between_rows_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '36px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-full',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
			array(
				'element'     => '.es-read-next .es-posts-area__read-next',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_grid_gap_between_rows_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '40px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-grid',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
			array(
				'element'     => '.es-read-next .es-posts-area__read-next',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_overlay_gap_between_rows_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-overlay',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
			array(
				'element'     => '.es-read-next .es-posts-area__read-next',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'overlay',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_full_gap_between_rows_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '40px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-full',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
			array(
				'element'     => '.es-read-next .es-posts-area__read-next',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'collapsible',
		'settings'        => 'archive_collapsible_gap_between_columns',
		'section'         => 'archive_settings',
		'label'           => esc_html__( 'Gap between Columns', 'esenin' ),
		'input_attrs'     => array(
			'collapsed' => false,
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'overlay',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_gap_between_columns_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__archive.es-posts-area__grid',
				'property' => '--es-posts-area-grid-column-gap',
			),
			array(
				'element'  => '.es-posts-area__archive.es-posts-area__overlay',
				'property' => '--es-posts-area-grid-column-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'overlay',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_gap_between_columns_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-posts-area__grid',
				'property'    => '--es-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
			array(
				'element'     => '.es-posts-area__archive.es-posts-area__overlay',
				'property'    => '--es-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'overlay',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_gap_between_columns_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-posts-area__grid',
				'property'    => '--es-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
			array(
				'element'     => '.es-posts-area__archive.es-posts-area__overlay',
				'property'    => '--es-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'overlay',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'archive_collapsible_title_size',
		'section'     => 'archive_settings',
		'label'       => esc_html__( 'Title Font Size', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_list_title_size_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '1.5rem',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__archive.es-archive-list',
				'property' => '--es-entry-title-font-size',
			),
			array(
				'element'  => '.es-posts-area__read-next',
				'property' => '--es-entry-title-font-size',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'list',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_grid_title_size_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '1.3125rem',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__archive.es-archive-grid',
				'property' => '--es-entry-title-font-size',
			),
			array(
				'element'  => '.es-posts-area__read-next',
				'property' => '--es-entry-title-font-size',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_overlay_title_size_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '2.0625rem',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__archive.es-archive-overlay',
				'property' => '--es-entry-title-font-size',
			),
			array(
				'element'  => '.es-posts-area__read-next',
				'property' => '--es-entry-title-font-size',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'overlay',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_full_title_size_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '2rem',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__archive.es-archive-full',
				'property' => '--es-entry-title-font-size',
			),
			array(
				'element'  => '.es-posts-area__read-next',
				'property' => '--es-entry-title-font-size',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_list_title_size_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '1.375rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-list',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
			array(
				'element'     => '.es-posts-area__read-next',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'list',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_grid_title_size_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '1.3125rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-grid',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
			array(
				'element'     => '.es-posts-area__read-next',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_overlay_title_size_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '1.75rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-overlay',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
			array(
				'element'     => '.es-posts-area__read-next',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'overlay',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_full_title_size_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '2rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-full',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
			array(
				'element'     => '.es-posts-area__read-next',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_list_title_size_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '1.375rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-list',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
			array(
				'element'     => '.es-posts-area__read-next',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'list',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_grid_title_size_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '1.3125rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-grid',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
			array(
				'element'     => '.es-posts-area__read-next',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_overlay_title_size_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '1.25rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-overlay',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
			array(
				'element'     => '.es-posts-area__read-next',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'overlay',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'archive_full_title_size_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'archive_settings',
		'default'         => '1.375rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__archive.es-archive-full',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
			array(
				'element'     => '.es-posts-area__read-next',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'archive_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
); */
