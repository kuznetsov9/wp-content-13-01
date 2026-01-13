<?php
/**
 * Homepage Settings
 *
 * @package Esenin
 */

/**
 * Removes default WordPress Static Front Page section
 * and re-adds it in our own panel with the same parameters.
 *
 * @param object $wp_customize Instance of the WP_Customize_Manager class.
 */
function esn_reorder_customizer_settings( $wp_customize ) {

	// Get current front page section parameters.
	$static_front_page = $wp_customize->get_section( 'static_front_page' );

	// Remove existing section, so that we can later re-add it to our panel.
	$wp_customize->remove_section( 'static_front_page' );

	// Re-add static front page section with a new name, but same description.
	$wp_customize->add_section(
		'static_front_page',
		array(
			'title'           => esc_html__( 'Static Front Page', 'esenin' ),
			'description'     => $static_front_page->description,
			'panel'           => 'home_panel',
			'active_callback' => $static_front_page->active_callback,
		)
	);
}
add_action( 'customize_register', 'esn_reorder_customizer_settings' );

ESN_Customizer::add_panel(
	'home_panel',
	array(
		'title' => esc_html__( 'Front Page Settings', 'esenin' ),
	)
);

ESN_Customizer::add_section(
	'home_settings',
	array(
		'title' => esc_html__( 'Latest Posts Layout', 'esenin' ),
		'panel' => 'home_panel',
	)
);

ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'home_collapsible_common',
		'section'     => 'home_settings',
		'label'       => esc_html__( 'Common', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => true,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'home_sidebar',
		'label'    => esc_html__( 'Default Sidebar', 'esenin' ),
		'section'  => 'home_settings',
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
		'settings' => 'home_layout',
		'label'    => esc_html__( 'Layout', 'esenin' ),
		'section'  => 'home_settings',
		'default'  => 'full',
		'choices'  => array(
			'overlay' => esc_html__( 'Overlay Layout', 'esenin' ),
			'full'    => esc_html__( 'Full Post Layout', 'esenin' ),
		),
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'home_excerpt',
		'label'           => esc_html__( 'Display excerpt', 'esenin' ),
		'section'         => 'home_settings',
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
); */

/* ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'home_discover_more',
		'label'           => esc_html__( 'Display discover more', 'esenin' ),
		'section'         => 'home_settings',
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
); */

/* ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'home_image_orientation',
		'label'           => esc_html__( 'Image Orientation', 'esenin' ),
		'section'         => 'home_settings',
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
						'setting'  => 'home_layout',
						'operator' => '!=',
						'value'    => 'full',
					),
					array(
						'setting'  => 'home_layout',
						'operator' => '!=',
						'value'    => 'overlay',
					),
				),
			),
		),
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'            => 'select',
		'settings'        => 'home_image_size',
		'label'           => esc_html__( 'Image Size', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => 'esn-thumbnail',
		'choices'         => esn_get_list_available_image_sizes(),
		'active_callback' => array(
			array(
				array(
					array(
						'setting'  => 'home_layout',
						'operator' => '!=',
						'value'    => 'full',
					),
					array(
						'setting'  => 'home_layout',
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
		'settings'        => 'home_media_preview',
		'label'           => esc_html__( 'Post Preview Image Size', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => 'cropped',
		'choices'         => array(
			'cropped'   => esc_html__( 'Display Cropped Image', 'esenin' ),
			'uncropped' => esc_html__( 'Display Preview in Original Ratio', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'home_layout',
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
		'settings'        => 'home_summary',
		'label'           => esc_html__( 'Full Post Summary', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => 'summary',
		'choices'         => array(
			'summary' => esc_html__( 'Use Excerpts', 'esenin' ),
			'content' => esc_html__( 'Use Read More Tag', 'esenin' ),
		),
		'active_callback' => array(
			array(
				array(
					'setting'  => 'home_layout',
					'operator' => '==',
					'value'    => 'full',
				),
			),
		),
	)
); */

ESN_Customizer::add_field(
	array(
		'type'     => 'radio',
		'settings' => 'home_pagination_type',
		'label'    => esc_html__( 'Pagination', 'esenin' ),
		'section'  => 'home_settings',
		'default'  => 'standard',
		'choices'  => array(
			'standard'  => esc_html__( 'Standard', 'esenin' ),
			'load-more' => esc_html__( 'Load More Button', 'esenin' ),
			'infinite'  => esc_html__( 'Infinite Load', 'esenin' ),
		),
	)
);

/* ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'home_collapsible_post_meta',
		'section'     => 'home_settings',
		'label'       => esc_html__( 'Post Meta', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'     => 'multicheck',
		'settings' => 'home_post_meta',
		'label'    => esc_html__( 'Post Meta', 'esenin' ),
		'section'  => 'home_settings',
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
);  */

/* ESN_Customizer::add_field(
	array(
		'type'            => 'collapsible',
		'settings'        => 'home_collapsible_number_of_columns',
		'section'         => 'home_settings',
		'label'           => esc_html__( 'Number of Columns', 'esenin' ),
		'input_attrs'     => array(
			'collapsed' => false,
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'home_columns_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => 2,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 3,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'  => '.es-posts-area__home.es-posts-area__grid',
				'property' => '--es-posts-area-grid-columns',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'home_columns_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => 2,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 2,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-posts-area__grid',
				'property'    => '--es-posts-area-grid-columns',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'home_columns_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => 1,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 2,
			'step' => 1,
		),
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-posts-area__grid',
				'property'    => '--es-posts-area-grid-columns',
				'media_query' => '@media (max-width: 767.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'        => 'collapsible',
		'settings'    => 'home_collapsible_gap_between_rows',
		'section'     => 'home_settings',
		'label'       => esc_html__( 'Gap between Rows', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_list_gap_between_rows_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '40px',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__home.es-archive-list',
				'property' => '--es-posts-area-grid-row-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'list',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_grid_gap_between_rows_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '48px',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__home.es-archive-grid',
				'property' => '--es-posts-area-grid-row-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_overlay_gap_between_rows_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__home.es-archive-overlay',
				'property' => '--es-posts-area-grid-row-gap',
			),
		),
		'active_callback' => array(
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
		'settings'        => 'home_full_gap_between_rows_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '48px',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__home.es-archive-full',
				'property' => '--es-posts-area-grid-row-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_list_gap_between_rows_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '40px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-list',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'list',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_grid_gap_between_rows_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '38px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-grid',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_overlay_gap_between_rows_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-overlay',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
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
		'settings'        => 'home_full_gap_between_rows_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '36px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-full',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_grid_gap_between_rows_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '40px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-grid',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_overlay_gap_between_rows_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-overlay',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
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
		'settings'        => 'home_full_gap_between_rows_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '40px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-full',
				'property'    => '--es-posts-area-grid-row-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'collapsible',
		'settings'        => 'home_collapsible_gap_between_columns',
		'section'         => 'home_settings',
		'label'           => esc_html__( 'Gap between Columns', 'esenin' ),
		'input_attrs'     => array(
			'collapsed' => false,
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
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
		'settings'        => 'home_gap_between_columns_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__home.es-posts-area__grid',
				'property' => '--es-posts-area-grid-column-gap',
			),
			array(
				'element'  => '.es-posts-area__home.es-posts-area__overlay',
				'property' => '--es-posts-area-grid-column-gap',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
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
		'settings'        => 'home_gap_between_columns_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-posts-area__grid',
				'property'    => '--es-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
			array(
				'element'     => '.es-posts-area__home.es-posts-area__overlay',
				'property'    => '--es-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
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
		'settings'        => 'home_gap_between_columns_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '24px',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-posts-area__grid',
				'property'    => '--es-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
			array(
				'element'     => '.es-posts-area__home.es-posts-area__overlay',
				'property'    => '--es-posts-area-grid-column-gap',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
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
		'type'        => 'collapsible',
		'settings'    => 'home_collapsible_title_size',
		'section'     => 'home_settings',
		'label'       => esc_html__( 'Title Font Size', 'esenin' ),
		'input_attrs' => array(
			'collapsed' => false,
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_list_title_size_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '1.5rem',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__home.es-archive-list',
				'property' => '--es-entry-title-font-size',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'list',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_grid_title_size_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '1.3125rem',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__home.es-archive-grid',
				'property' => '--es-entry-title-font-size',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_overlay_title_size_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '2.0625rem',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__home.es-archive-overlay',
				'property' => '--es-entry-title-font-size',
			),
		),
		'active_callback' => array(
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
		'settings'        => 'home_full_title_size_desktop',
		'label'           => esc_html__( 'Desktop', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '2rem',
		'output'          => array(
			array(
				'element'  => '.es-posts-area__home.es-archive-full',
				'property' => '--es-entry-title-font-size',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_list_title_size_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '1.375rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-list',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'list',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_grid_title_size_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '1.3125rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-grid',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_overlay_title_size_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '1.75rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-overlay',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
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
		'settings'        => 'home_full_title_size_tablet',
		'label'           => esc_html__( 'Tablet', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '2rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-full',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 991.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_list_title_size_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '1.375rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-list',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'list',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_grid_title_size_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '1.3125rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-grid',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'grid',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'dimension',
		'settings'        => 'home_overlay_title_size_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '1.25rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-overlay',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
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
		'settings'        => 'home_full_title_size_mobile',
		'label'           => esc_html__( 'Mobile', 'esenin' ),
		'section'         => 'home_settings',
		'default'         => '1.375rem',
		'output'          => array(
			array(
				'element'     => '.es-posts-area__home.es-archive-full',
				'property'    => '--es-entry-title-font-size',
				'media_query' => '@media (max-width: 575.98px)',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_layout',
				'operator' => '==',
				'value'    => 'full',
			),
		),
	)
); */

/* ESN_Customizer::add_section(
	'home_hero_section',
	array(
		'title'    => esc_html__( 'Hero Section', 'esenin' ),
		'panel'    => 'home_panel',
		'priority' => 10,
	)
); */

ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'home_hero',
		'label'    => esc_html__( 'Display hero section', 'esenin' ),
		'section'  => 'home_hero_section',
		'default'  => false,
		'priority' => 10,
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'collapsible',
		'settings'        => 'home_hero_collapsible_common',
		'section'         => 'home_hero_section',
		'label'           => esc_html__( 'Common', 'esenin' ),
		'input_attrs'     => array(
			'collapsed' => true,
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'home_hero_layout',
		'label'           => esc_html__( 'Layout', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => 'hero-type-1',
		'priority'        => 10,
		'choices'         => array(
			'hero-type-1' => esc_html__( 'Type 1', 'esenin' ),
			'hero-type-2' => esc_html__( 'Type 2', 'esenin' ),
			'hero-type-3' => esc_html__( 'Type 3', 'esenin' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'textarea',
		'settings'        => 'home_hero_heading',
		'label'           => esc_html__( 'Heading', 'esenin' ),
		'description'     => esc_html__( 'Wrap phrases with <span></span> tags to emphasize and highlight.', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => '',
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '==',
					'value'    => 'hero-type-1',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'textarea',
		'settings'        => 'home_hero_subheading',
		'label'           => esc_html__( 'Subheading', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => '',
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '==',
					'value'    => 'hero-type-1',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'home_hero_slider_autoplay',
		'label'           => esc_html__( 'Slider Autoplay', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => 'yes',
		'priority'        => 10,
		'choices'         => array(
			'yes' => esc_html__( 'Enabled', 'esenin' ),
			'no'  => esc_html__( 'Disabled', 'esenin' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '==',
					'value'    => 'hero-type-2',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'home_hero_slider_delay',
		'label'           => esc_html__( 'Autoplay Delay (ms)', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => '5000',
		'input_attrs'     => array(
			'min'  => 3000,
			'max'  => 10000,
			'step' => 500,
		),
		'output'          => array(
			array(
				'element'  => ':root',
				'property' => '--es-hero-slider-delay-ms',
				'context'  => array( 'editor', 'front' ),
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'home_hero_slider_autoplay',
				'operator' => '==',
				'value'    => 'yes',
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '==',
					'value'    => 'hero-type-2',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'radio',
		'settings'        => 'home_hero_slider_parallax',
		'label'           => esc_html__( 'Slider Parallax Effect', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => 'yes',
		'priority'        => 10,
		'choices'         => array(
			'yes' => esc_html__( 'Enabled', 'esenin' ),
			'no'  => esc_html__( 'Disabled', 'esenin' ),
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '==',
					'value'    => 'hero-type-2',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'multicheck',
		'settings'        => 'home_hero_meta',
		'label'           => esc_html__( 'Post Meta', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => array( 'category', 'author', 'date', 'reading_time' ),
		'priority'        => 10,
		'choices'         => apply_filters(
			'esn_post_meta_choices',
			array(
				'category'     => esc_html__( 'Category', 'esenin' ),
				'reading_time' => esc_html__( 'Reading time', 'esenin' ),
				'author'       => esc_html__( 'Author', 'esenin' ),
				'date'         => esc_html__( 'Date', 'esenin' ),
				'comments'     => esc_html__( 'Comments', 'esenin' ),
				'views'        => esc_html__( 'Views', 'esenin' ),
			)
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '!=',
					'value'    => 'hero-type-1',
				),
			),
		),
	)
);

/* ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'home_hero_discover_more',
		'label'           => esc_html__( 'Display discover more', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => true,
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '==',
					'value'    => 'hero-type-2',
				),
			),
		),
	)
); */

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'home_hero_filter_categories',
		'label'           => esc_html__( 'Filter by Categories', 'esenin' ),
		'description'     => esc_html__( 'Add comma-separated list of category slugs. For example: &laquo;travel, lifestyle, food&raquo;. Leave empty for all categories.', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => '',
		'priority'        => 10,
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'home_hero_filter_tags',
				'operator' => '==',
				'value'    => '',
			),
			array(
				'setting'  => 'home_hero_filter_posts',
				'operator' => '==',
				'value'    => '',
			),
			array(
				'setting'  => 'home_hero_layout',
				'operator' => '!=',
				'value'    => 'hero-type-1',
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'home_hero_filter_tags',
		'label'           => esc_html__( 'Filter by Tags', 'esenin' ),
		'description'     => esc_html__( 'Add comma-separated list of tag slugs. For example: &laquo;worth-reading, top-5, playlists&raquo;. Leave empty for all tags.', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => '',
		'priority'        => 10,
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'home_hero_filter_categories',
				'operator' => '==',
				'value'    => '',
			),
			array(
				'setting'  => 'home_hero_filter_posts',
				'operator' => '==',
				'value'    => '',
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '!=',
					'value'    => 'hero-type-1',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'home_hero_filter_posts',
		'label'           => esc_html__( 'Filter by Posts', 'esenin' ),
		'description'     => esc_html__( 'Add comma-separated list of post IDs (max. 5). For example: 12, 34, 145. Leave empty for all posts.', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => '',
		'priority'        => 10,
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'home_hero_filter_categories',
				'operator' => '==',
				'value'    => '',
			),
			array(
				'setting'  => 'home_hero_filter_tags',
				'operator' => '==',
				'value'    => '',
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '!=',
					'value'    => 'hero-type-1',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'home_hero_max_count',
		'label'           => esc_html__( 'Maximum Posts', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => '3',
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '==',
					'value'    => 'hero-type-2',
				),
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'checkbox',
		'settings'        => 'home_hero_exclude',
		'label'           => esc_html__( 'Exclude hero posts from the main archive', 'esenin' ),
		'section'         => 'home_hero_section',
		'default'         => false,
		'priority'        => 10,
		'active_callback' => array(
			array(
				'setting'  => 'home_hero',
				'operator' => '==',
				'value'    => true,
			),
			array(
				array(
					'setting'  => 'home_hero_layout',
					'operator' => '!=',
					'value'    => 'hero-type-1',
				),
			),
		),
	)
);
/* ESN_Customizer::add_section(
	'home_featured_categories',
	array(
		'title'    => esc_html__( 'Featured Categories', 'esenin' ),
		'panel'    => 'home_panel',
		'priority' => 20,
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'     => 'checkbox',
		'settings' => 'home_show_categories',
		'label'    => esc_html__( 'Display categories', 'esenin' ),
		'section'  => 'home_featured_categories',
		'default'  => false,
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'home_categories_heading',
		'label'           => esc_html__( 'Heading', 'esenin' ),
		'section'         => 'home_featured_categories',
		'default'         => esc_html__( 'Популярные темы', 'esenin' ),
		'active_callback' => array(
			array(
				'setting'  => 'home_show_categories',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
); */

/* ESN_Customizer::add_field(
	array(
		'type'            => 'text',
		'settings'        => 'home_categories_filter',
		'label'           => esc_html__( 'Filter Categories By Slug', 'esenin' ),
		'description'     => esc_html__( 'Add comma-separated list of category slugs. For example: &laquo;travel, lifestyle, food&raquo;. Leave empty for all categories.', 'esenin' ),
		'section'         => 'home_featured_categories',
		'default'         => '',
		'priority'        => 10,
		'active_callback' => array(
			array(
				'setting'  => 'home_show_categories',
				'operator' => '==',
				'value'    => true,
			),
		),
	)
);

ESN_Customizer::add_field(
	array(
		'type'            => 'number',
		'settings'        => 'home_categories_limit',
		'label'           => esc_html__( 'Limit', 'esenin' ),
		'section'         => 'home_featured_categories',
		'default'         => 8,
		'input_attrs'     => array(
			'min'  => 1,
			'max'  => 99,
			'step' => 1,
		),
		'active_callback' => array(
			array(
				'setting'  => 'home_show_categories',
				'operator' => '==',
				'value'    => true,
			),
			array(
				'setting'  => 'home_categories_filter',
				'operator' => '==',
				'value'    => '',
			),
		),
	)
);
 */