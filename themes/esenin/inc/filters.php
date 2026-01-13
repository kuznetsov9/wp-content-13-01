<?php
/**
 * Filters
 *
 * Filtering native WordPress and third-party plugins' functions.
 *
 * @package Esenin
 */

if ( ! function_exists( 'esn_body_class' ) ) {
	/**
	 * Adds classes to <body> tag
	 *
	 * @param array $classes is an array of all body classes.
	 */
	function esn_body_class( $classes ) {

		// Page Layout.
		$classes[] = 'es-page-layout-' . esn_get_page_sidebar();

		// Sticky Navbar.
		if ( get_theme_mod( 'navbar_sticky', true ) ) {
			$classes['navbar_sticky'] = 'es-navbar-sticky-enabled';

			// Smart Navbar.
			if ( get_theme_mod( 'navbar_smart_sticky', false ) ) {
				$classes['navbar_sticky'] = 'es-navbar-smart-enabled';
			}
		}

		// Sticky Sidebar.
		if ( get_theme_mod( 'misc_sticky_sidebar', true ) ) {
			$classes[] = 'es-sticky-sidebar-enabled';

			$classes[] = get_theme_mod( 'misc_sticky_sidebar_method', 'es-stick-last' );
		} else {
			$classes[] = 'es-sticky-sidebar-disabled';
		}

		// Single Page Header Type.
		if ( is_single() ) {
			$classes[] = 'es-single-header-type-' . esn_get_page_header_type();
		}

		return $classes;
	}
}
add_filter( 'body_class', 'esn_body_class' );

if ( ! function_exists( 'esn_kses_allowed_html' ) ) {
	/**
	 * Filters the HTML that is allowed for a given context.
	 *
	 * @param array  $tags    Tags by.
	 * @param string $context Context name.
	 */
	function esn_kses_allowed_html( $tags, $context ) {

		if ( 'content' === $context ) {
			$tags = array(
				'a'      => array(
					'class'  => true,
					'href'   => true,
					'title'  => true,
					'target' => true,
					'rel'    => true,
				),
				'div'    => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'span'   => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'img'    => array(
					'class'  => true,
					'id'     => true,
					'src'    => true,
					'rel'    => true,
					'srcset' => true,
					'size'   => true,
				),
				'br'     => array(),
				'b'      => array(),
				'strong' => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'i'      => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'p'      => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'h1'     => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'h2'     => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'h3'     => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'h4'     => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'h5'     => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'h6'     => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
			);
		}

		if ( 'common' === $context ) {
			$tags = wp_kses_allowed_html( 'post' );
		}

		return $tags;
	}
	add_filter( 'wp_kses_allowed_html', 'esn_kses_allowed_html', 10, 2 );
}

if ( ! function_exists( 'esn_sitecontent_class' ) ) {
	/**
	 * Adds the classes for the site-content element.
	 *
	 * @param array $classes Classes to add to the class list.
	 */
	function esn_sitecontent_class( $classes ) {

		// Page Sidebar.
		if ( 'disabled' !== esn_get_page_sidebar() ) {
			$classes[] = 'es-sidebar-enabled es-sidebar-' . esn_get_page_sidebar();
		} else {
			$classes[] = 'es-sidebar-disabled';
		}

		// Post Metabar.
		/* if ( true === get_theme_mod( 'post_metabar', true ) && is_singular( 'post' ) ) {
			$classes[] = 'es-metabar-enabled';
		} else {
			$classes[] = 'es-metabar-disabled';
		} */

		return $classes;
	}
}
add_filter( 'esn_site_content_class', 'esn_sitecontent_class' );

if ( ! function_exists( 'esn_add_entry_class' ) ) {
	/**
	 * Add entry class to post_class
	 *
	 * @param array $classes One or more classes to add to the class list.
	 */
	function esn_add_entry_class( $classes ) {
		array_push( $classes, 'es-entry' );

		return $classes;
	}
}
add_filter( 'post_class', 'esn_add_entry_class' );

if ( ! function_exists( 'esn_remove_hentry_class' ) ) {
	/**
	 * Remove hentry from post_class
	 *
	 * @param array $classes One or more classes to add to the class list.
	 */
	function esn_remove_hentry_class( $classes ) {
		return array_diff( $classes, array( 'hentry' ) );
	}
}
add_filter( 'post_class', 'esn_remove_hentry_class' );

if ( ! function_exists( 'esn_max_srcset_image_width' ) ) {
	/**
	 * Changes max image width in srcset attribute
	 *
	 * @param int   $max_width  The maximum image width to be included in the 'srcset'. Default '1600'.
	 * @param array $size_array Array of width and height values in pixels (in that order).
	 */
	function esn_max_srcset_image_width( $max_width, $size_array ) {
		return 3840;
	}
}
add_filter( 'max_srcset_image_width', 'esn_max_srcset_image_width', 10, 2 );

if ( ! function_exists( 'esn_get_the_archive_title' ) ) {
	/**
	 * Archive Title
	 *
	 * Removes default prefixes, like "Category:" from archive titles.
	 *
	 * @param string $title Archive title.
	 */
	function esn_get_the_archive_title( $title ) {
		if ( is_category() ) {

			$title = single_cat_title( '', false );

		} elseif ( is_tag() ) {

			$title = single_tag_title( '', false );

		} elseif ( is_author() ) {

			$title = get_the_author( '', false );

		}

		return $title;
	}
}
add_filter( 'get_the_archive_title', 'esn_get_the_archive_title' );

if ( ! function_exists( 'esn_excerpt_length' ) ) {
	/**
	 * Excerpt Length
	 *
	 * @param string $length of the excerpt.
	 */
	function esn_excerpt_length( $length ) {
		return 18;
	}
}
add_filter( 'excerpt_length', 'esn_excerpt_length' );

if ( ! function_exists( 'esn_strip_shortcode_from_excerpt' ) ) {
	/**
	 * Strip shortcodes from excerpt
	 *
	 * @param string $content Excerpt.
	 */
	function esn_strip_shortcode_from_excerpt( $content ) {
		$content = strip_shortcodes( $content );
		return $content;
	}
}
add_filter( 'the_excerpt', 'esn_strip_shortcode_from_excerpt' );

if ( ! function_exists( 'esn_strip_tags_from_excerpt' ) ) {
	/**
	 * Strip HTML from excerpt
	 *
	 * @param string $content Excerpt.
	 */
	function esn_strip_tags_from_excerpt( $content ) {
		$content = wp_strip_all_tags( $content );
		return $content;
	}
}
add_filter( 'the_excerpt', 'esn_strip_tags_from_excerpt' );

if ( ! function_exists( 'esn_excerpt_more' ) ) {
	/**
	 * Excerpt Suffix
	 *
	 * @param string $more suffix for the excerpt.
	 */
	function esn_excerpt_more( $more ) {
		return '&hellip;';
	}
}
add_filter( 'excerpt_more', 'esn_excerpt_more' );

if ( ! function_exists( 'esn_post_meta_process' ) ) {
	/**
	 * Pre processing post meta choices
	 *
	 * @param array $data Post meta list.
	 */
	function esn_post_meta_process( $data ) {
		if ( esn_post_views_enabled() ) {
			$data['views'] = esc_html__( 'Views', 'esenin' );
		}
		return $data;
	}
}
add_filter( 'esn_post_meta_choices', 'esn_post_meta_process' );

if ( ! function_exists( 'esn_search_only_posts' ) ) {
	/**
	 * Search only posts.
	 *
	 * @param object $query The WP_Query instance (passed by reference).
	 */
	function esn_search_only_posts( $query ) {
		if ( ! is_admin() && $query->is_main_query() && $query->is_search ) {
			$query->set( 'post_type', 'post' );
		}
	}
	add_action( 'pre_get_posts', 'esn_search_only_posts' );
}

if ( ! function_exists( 'esn_show_pending_posts_for_moderators' ) ) {
	/**
	 * Позволяет модераторам и админам видеть посты на проверке в общей ленте.
	 *
	 * @param object $query The WP_Query instance.
	 */
	function esn_show_pending_posts_for_moderators( $query ) {
		// Работаем только на фронте, только для главного запроса
		if ( ! is_admin() && $query->is_main_query() ) {
			
			// Проверяем права: editor и выше умеют 'edit_others_posts'
			if ( current_user_can( 'edit_others_posts' ) ) {
				
				// Если мы не в поиске (там свои правила), расширяем статусы
				if ( ! $query->is_search ) {
					$query->set( 'post_status', array( 'publish', 'pending' ) );
				}
			}
		}
	}
	add_action( 'pre_get_posts', 'esn_show_pending_posts_for_moderators' );
}

if ( ! function_exists( 'esn_comment_form_defaults' ) ) {
	/**
	 * Pre processing post meta choices
	 *
	 * @param array $defaults The default comment form arguments.
	 */
	function esn_comment_form_defaults( $defaults ) {

		$label       = esc_html__( 'Your Message', 'esenin' ) . ( ' <span class="required">*</span>' );
		$placeholder = esc_html__( 'Text', 'esenin' );

		$defaults['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . $label . '</label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required" placeholder="' . $placeholder . '"></textarea></p>';

		return $defaults;
	}
}
add_filter( 'comment_form_defaults', 'esn_comment_form_defaults' );

if ( ! function_exists( 'esn_register_block_styles' ) ) {
	/**
	 * Register block styles.
	 */
	function esn_register_block_styles() {
		if ( ! function_exists( 'register_block_style' ) ) {
			return;
		}

		register_block_style(
			'core/heading',
			array(
				'name'  => 'es-heading-gradient',
				'label' => esc_html__( 'Inline Gradient', 'esenin' ),
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'  => 'es-about',
				'label' => esc_html__( 'About Widget', 'esenin' ),
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'  => 'es-about-page',
				'label' => esc_html__( 'About Page', 'esenin' ),
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'  => 'es-contact-form',
				'label' => esc_html__( 'Contact Form', 'esenin' ),
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'  => 'es-technologies',
				'label' => esc_html__( 'Technologies', 'esenin' ),
			)
		);

		register_block_style(
			'core/columns',
			array(
				'name'  => 'es-work-experience',
				'label' => esc_html__( 'Work Experience', 'esenin' ),
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'  => 'es-posts-slider',
				'label' => esc_html__( 'Posts Slider', 'esenin' ),
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'  => 'es-category-grid',
				'label' => esc_html__( 'Category Grid', 'esenin' ),
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'  => 'es-work-experience-layout',
				'label' => esc_html__( 'Experience Grid', 'esenin' ),
			)
		);

		register_block_style(
			'core/categories',
			array(
				'name'  => 'es-tiles',
				'label' => esc_html__( 'Tiles', 'esenin' ),
			)
		);

		register_block_style(
			'core/latest-posts',
			array(
				'name'  => 'es-tile-layout',
				'label' => esc_html__( 'Tile', 'esenin' ),
			)
		);

		register_block_style(
			'core/heading',
			array(
				'name'  => 'es-link',
				'label' => esc_html__( 'Link Style', 'esenin' ),
			)
		);

		register_block_style(
			'core/heading',
			array(
				'name'  => 'es-location',
				'label' => esc_html__( 'Location Style', 'esenin' ),
			)
		);
	}
	add_action( 'init', 'esn_register_block_styles' );
}

if ( ! function_exists( 'esn_comment_form_default_fields' ) ) {
	/**
	 * Pre processing post meta choices
	 *
	 * @param string[] $fields Array of the default comment fields.
	 */
	function esn_comment_form_default_fields( $fields ) {
		$commenter = wp_get_current_commenter();
		$user      = wp_get_current_user();
		$req       = get_option( 'require_name_email' );
		$html_req  = ( $req ? " required='required'" : '' );

		$label_author = esc_html__( 'Your Name', 'esenin' ) . ( $req ? ' <span class="required">*</span>' : '' );
		$label_email  = esc_html__( 'Email Address', 'esenin' ) . ( $req ? ' <span class="required">*</span>' : '' );
		$label_url    = esc_html__( 'Website', 'esenin' );

		$fields['author'] = '<p class="comment-form-author"><label for="author">' . $label_author . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245" ' . wp_kses( $html_req, 'esn' ) . '></p>';
		$fields['email']  = '<p class="comment-form-email"><label for="email">' . $label_email . '</label><input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" ' . wp_kses( $html_req, 'esn' ) . '></p>';
		$fields['url']    = '<p class="comment-form-url"><label for="url">' . $label_url . '</label><input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200"></p>';
		return $fields;
	}
}
add_filter( 'comment_form_default_fields', 'esn_comment_form_default_fields' );

if ( ! function_exists( 'esn_singular_register_options' ) ) {
	/**
	 * Register options for single and page
	 */
	function esn_singular_register_options() {
		$function = sprintf( 'add_meta_%s', 'box' );

		$function(
			'esn_singular_options',
			esc_html__( 'Singular Options', 'esenin' ),
			function () {
				return __return_empty_string();
			},
			array( 'post', 'page' ),
			'side'
		);
	}
	add_action( sprintf( 'add_meta_%s', 'boxes' ), 'esn_singular_register_options' );
}

/**
 * -------------------------------------------------------------------------
 * [ Primary Menu ]
 * -------------------------------------------------------------------------
 */

if ( ! function_exists( 'esn_primary_menu_item_args' ) ) {
	/**
	 * Filters the arguments for a single nav menu item.
	 *
	 * @param object $args  An object of wp_nav_menu() arguments.
	 * @param object $item  (WP_Post) Menu item data object.
	 * @param int    $depth Depth of menu item. Used for padding.
	 */
	function esn_primary_menu_item_args( $args, $item, $depth ) {
		$args->link_before = '';
		$args->link_after  = '';
		if ( 'primary' === $args->theme_location && 0 === $depth ) {
			$args->link_before = '<span>';
			$args->link_after  = '</span>';
		}
		return $args;
	}
	add_filter( 'nav_menu_item_args', 'esn_primary_menu_item_args', 10, 3 );
}

if ( version_compare( get_bloginfo( 'version' ), '5.4', '>=' ) ) {
	/**
	 * Add badge custom fields to menu item
	 *
	 * @param int $id object id.
	 */
	function esn_menu_item_badge_fields( $id ) {

		wp_nonce_field( 'esn_menu_meta_nonce', 'esn_menu_meta_nonce_name' );

		$badge_text = get_post_meta( $id, '_esn_menu_badge_text', true );
		?>
		<p class="description description-thin">
			<label><?php esc_html_e( 'Badge Text', 'esenin' ); ?><br>
				<input type="text" class="widefat <?php echo esc_attr( $id ); ?>" name="esn_menu_badge_text[<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_attr( $badge_text ); ?>">
			</label>
		</p>
		<?php
	}
	add_action( 'wp_nav_menu_item_custom_fields', 'esn_menu_item_badge_fields' );

	/**
	 * Save the badge menu item meta
	 *
	 * @param int $menu_id menu id.
	 * @param int $menu_item_db_id menu item db id.
	 */
	function esn_menu_item_badge_fields_update( $menu_id, $menu_item_db_id ) {

		// Check ajax.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		// Security.
		check_admin_referer( 'esn_menu_meta_nonce', 'esn_menu_meta_nonce_name' );

		// Save badge text.
		if ( isset( $_POST['esn_menu_badge_text'][ $menu_item_db_id ] ) ) {
			$sanitized_data = sanitize_text_field( $_POST['esn_menu_badge_text'][ $menu_item_db_id ] );
			update_post_meta( $menu_item_db_id, '_esn_menu_badge_text', $sanitized_data );
		} else {
			delete_post_meta( $menu_item_db_id, '_esn_menu_badge_text' );
		}
	}
	add_action( 'wp_update_nav_menu_item', 'esn_menu_item_badge_fields_update', 10, 2 );

	/**
	 * Displays badge text on the front-end.
	 *
	 * @param string  $title The menu item's title.
	 * @param WP_Post $item The current menu item.
	 * @return string
	 */
	function esn_badge_menu_item( $title, $item ) {
    // Проверяем, является ли $item объектом и существует ли его идентификатор
    if ( is_object( $item ) && isset( $item->ID ) ) {
        // Получаем текст баджа из метаполя
        $badge_text = get_post_meta( $item->ID, '_esn_menu_badge_text', true );

        // Если текст баджа не пустой, модифицируем заголовок
        if ( ! empty( $badge_text ) ) {
            // Оборачиваем текст "Популярное" и бадж в span
            $title = '<span class="text-item-badge">' . $title . ' <span class="esn-badge text-truncate" style="max-width: 160px;">' . esc_html( $badge_text ) . '</span></span>';
        }
    }
    return $title;
}
add_filter( 'nav_menu_item_title', 'esn_badge_menu_item', 8, 2 );
}

if ( ! function_exists( 'esn_exclude_hero_posts_from_query' ) ) {
	/**
	 * Exclude Hero from the Main Query
	 *
	 * @param array $query Default query.
	 */
	function esn_exclude_hero_posts_from_query( $query ) {
		if ( is_admin() ) {
			return $query;
		}

		if ( false === get_theme_mod( 'home_hero', false ) ) {
			return $query;
		}

		if ( ! $query->is_home ) {
			return $query;
		}

		if ( false === get_theme_mod( 'home_hero_exclude', false ) ) {
			return $query;
		}

		if ( ! $query->is_main_query() ) {
			return $query;
		}

		if ( 'hero-type-1' === get_theme_mod( 'home_hero_layout', 'hero-type-1' ) ) {
			return $query;
		}

		$args = esn_get_hero_query_args();

		$args['fields'] = 'ids';

		$query_to_exclude = new WP_Query( $args );

		if ( isset( $query_to_exclude->posts ) && $query_to_exclude->posts ) {
			$post_ids = $query_to_exclude->posts;
		}

		if ( ! isset( $post_ids ) || ! $post_ids ) {
			return $query;
		}

		$query->set( 'post__not_in', array_merge( $query->get( 'post__not_in' ), $post_ids ) );

		return $query;
	}
}
add_action( 'pre_get_posts', 'esn_exclude_hero_posts_from_query', 9999 );

/**
 * -------------------------------------------------------------------------
 * Render Blocks
 * -------------------------------------------------------------------------
 */

if ( ! function_exists( 'esn_custom_render_block_categories' ) ) {
	/**
	 * Block Render Categories
	 *
	 * @param string $block_content The content.
	 * @param array  $block         The block.
	 */
	function esn_custom_render_block_categories( $block_content, $block ) {

		if ( 'core/categories' === $block['blockName'] && false !== strpos( $block_content, 'is-style-es-tiles' ) ) {
			$block_content = preg_replace_callback(
				'|<a(.*?)href="(.*?)">(.*?)<\/a> \((.*?)\)|',
				function ( $matches ) {
					return '<a' . $matches[1] . 'href="' . $matches[2] . '">' . $matches[3] . ' <span>' . $matches[4] . '</span></a>';
				},
				$block_content
			);
		}

		return $block_content;
	}
}
add_filter( 'render_block', 'esn_custom_render_block_categories', 10, 2 );

if ( ! function_exists( 'esn_custom_render_block_social_links' ) ) {
	/**
	 * Block Render Social Links
	 *
	 * @param string $block_content The content.
	 * @param array  $block         The block.
	 */
	function esn_custom_render_block_social_links( $block_content, $block ) {

		if ( 'core/social-links' === $block['blockName'] ) {
			$block_content = preg_replace_callback(
				'|<a(.*?)href="([^"]+)"|',
				function ( $matches ) {

					$domain = parse_url( $matches[2], PHP_URL_HOST );

					$title = explode( '.', str_replace( 'www.', '', $domain ) )[0];

					return '<a' . $matches[1] . 'href="' . $matches[2] . '"' . ( $title ? ' title="' . ucfirst( $title ) . '"' : '' );
				},
				$block_content
			);
		}

		return $block_content;
	}
}
add_filter( 'render_block', 'esn_custom_render_block_social_links', 10, 2 );

if ( ! function_exists( 'esn_custom_render_block_latest_posts' ) ) {
	/**
	 * Block Render Latest Posts
	 *
	 * @param string $block_content The content.
	 * @param array  $block         The block.
	 */
	function esn_custom_render_block_latest_posts( $block_content, $block ) {

		if ( 'core/latest-posts' === $block['blockName'] ) {

			$has_tile_layout_class = isset( $block['attrs']['className'] ) && strpos( $block['attrs']['className'], 'is-style-es-tile-layout' ) !== false;

			if ( $has_tile_layout_class ) {

				$has_dates  = preg_match( '/has-dates/', $block_content );
				$has_author = preg_match( '/has-author/', $block_content );

				$meta_pattern = '|(<div class="wp-block-latest-posts__post-author">.*?<\/div><time datetime=".*?" class="wp-block-latest-posts__post-date">)(.*?)(<\/time>)|';

				if ( $has_dates && $has_author ) {
					$container_pattern = '|(<div class="wp-block-latest-posts__category">.*?</div><a class="wp-block-latest-posts__post-title" href="([^<]*?)">.*?</a><div class="wp-block-latest-posts__meta">.*?<time datetime=".*?" class="wp-block-latest-posts__post-date">.*?</time></div>)|';
				} elseif ( $has_author && ! $has_dates ) {
					$container_pattern = '|(<div class="wp-block-latest-posts__category">.*?</div><a class="wp-block-latest-posts__post-title" href="([^<]*?)">.*?</a><div class="wp-block-latest-posts__meta">.*?<div class="wp-block-latest-posts__post-author">.*?</div></div>)|';
					$meta_pattern      = '|(<div class="wp-block-latest-posts__post-author">.*?<\/div>)|';
				} elseif ( $has_dates && ! $has_author ) {
					$container_pattern = '|(<div class="wp-block-latest-posts__category">.*?</div><a class="wp-block-latest-posts__post-title" href="([^<]*?)">.*?</a><div class="wp-block-latest-posts__meta">.*?<time datetime=".*?" class="wp-block-latest-posts__post-date">.*?</time></div>)|';
					$meta_pattern      = '|(<time datetime=".*?" class="wp-block-latest-posts__post-date">)(.*?)(<\/time>)|';
				} else {
					$container_pattern = '|(<div class="wp-block-latest-posts__category">.*?</div><a class="wp-block-latest-posts__post-title" href="([^<]*?)">.*?</a>)|';
				}

				// Change Ul & li to div, add swiper elements.
				$block_content = preg_replace_callback(
					'/<ul(.*?)>(.*?)<\/ul>/is',
					function ( $matches ) {
						$pagination = '<div class="es-latest-posts-slider__pagination"></div>';
						$arrows     = '<div class="es-latest-posts-slider__button-prev"></div><div class="es-latest-posts-slider__button-next"></div>';

						$inner_content             = preg_replace( '/<li(.*?)>(.*?)<\/li>/is', '<div$1 class="es-latest-posts-slider__item">$2</div>', $matches[2] );
						$inner_content_with_parent = '<div class="es-latest-posts-slider__wrapper">' . $inner_content . '</div>' . $arrows;

						return '<div class="es-latest-posts-slider"><div' . $matches[1] . '>' . $inner_content_with_parent . '</div>' . $pagination . '</div>';
					},
					$block_content
				);

				// Add author Link.
				$block_content = preg_replace_callback(
					'|(<a class="wp-block-latest-posts__post-title" href=".*?">([^<]*?)</a>)<div class="wp-block-latest-posts__post-author">([^<]*?)<\/div>|',
					function ( $matches ) {

						$output = $matches[0];

						$items = get_posts(
							array(
								'post_type'      => 'post',
								'post_status'    => 'publish',
								's'              => html_entity_decode( $matches[2] ),
								'posts_per_page' => 1,
							)
						);

						if ( ! empty( $items ) ) {
							$author_id  = $items[0]->post_author;
							$author_url = get_author_posts_url( $author_id );
							$full_name  = trim( str_replace( 'by', '', $matches[3] ) );

							if ( $author_url && $full_name ) {
								$output = str_replace( $full_name, sprintf( '<a href="%s">%s</a>', $author_url, $full_name ), $output );
							}
						}

						return $output;
					},
					$block_content
				);

				// Add Meta container.
				$block_content = preg_replace_callback(
					$meta_pattern,
					function ( $matches ) {
						$time_string = '<span>' . __( 'on', 'esenin' ) . '</span>';
						$output      = trim( str_replace( 'by', '', $matches[1] ) );

						if ( $matches[2] && $matches[3] ) {
							$output = trim( str_replace( 'by', '', $matches[1] ) ) . $time_string . trim( $matches[2] ) . $matches[3];
						}

						$items = get_posts(
							array(
								'post_type'      => 'post',
								'post_status'    => 'publish',
								'posts_per_page' => 1,
							)
						);

						if ( ! empty( $items ) ) {
							$output = '<div class="wp-block-latest-posts__meta">' . $output . '</div>';
						}

						return $output;
					},
					$block_content
				);

				// Add Category.
				$block_content = preg_replace_callback(
					'|(<a class="wp-block-latest-posts__post-title" href=".*?">([^<]*?)</a>)|',
					function ( $matches ) {

						$output     = $matches[0];
						$post_title = html_entity_decode( $matches[1] );

						$items = get_posts(
							array(
								'post_type'      => 'post',
								'post_status'    => 'publish',
								'name'           => sanitize_title( $post_title ),
								'posts_per_page' => 1,
							)
						);

						if ( ! empty( $items ) ) {
							// Fetch current post category.
							$categories        = get_the_category( $items[0]->ID );
							$post_category_url = ! empty( $categories ) ? get_category_link( $categories[0]->term_id ) : '';
							$post_category     = ! empty( $categories ) ? '<div class="wp-block-latest-posts__category"><a href="' . $post_category_url . '">' . $categories[0]->name . '</a></div>' : '';

							$output = $post_category . $output;
						}

						return $output;
					},
					$block_content
				);

				// Add Content container & overlay link.
				$block_content = preg_replace_callback(
					$container_pattern,
					function ( $matches ) {
						$output = $matches[0];

						$items = get_posts(
							array(
								'post_type'      => 'post',
								'post_status'    => 'publish',
								'posts_per_page' => 1,
							)
						);

						if ( ! empty( $items ) ) {
							$post_permalink = $matches[2];

							$link_label = sprintf(
							/* translators: %s: Post Title */
								__( 'Read More: %s', 'esenin' ),
								$items[0]->post_title
							);

							$output = '<div class="wp-block-latest-posts__content" data-scheme="inverse"><a class="wp-block-latest-posts__link" href="' . esc_url( $post_permalink ) . '" title="' . esc_attr( $link_label ) . '"></a><div class="wp-block-latest-posts__content-inner">' . $output . '</div></div>';
						}

						return $output;
					},
					$block_content
				);

			}
		}

		return $block_content;
	}
}
add_filter( 'render_block', 'esn_custom_render_block_latest_posts', 10, 2 );

/**
 * -------------------------------------------------------------------------
 * Breadcrumbs separator
 * -------------------------------------------------------------------------
 */
if ( ! function_exists( 'esn_replace_breadcrumb_separator' ) ) {
	/**
	 * Change the breadcrumbs HTML output.
	 *
	 * @param string $html HTML output.
	 */
	function esn_replace_breadcrumb_separator( $html ) {
		$breadcrumbs_separators = array(
			'#<span class="separator">(.*?)</span>#',
			'#<span class="aioseo-breadcrumb-separator">(.*?)</span>#',
		);

		$html = preg_replace( $breadcrumbs_separators, '<span class="es-separator"></span>', $html );

		return $html;
	}
}
add_filter( 'rank_math/frontend/breadcrumb/html', 'esn_replace_breadcrumb_separator' );
add_filter( 'aioseo_breadcrumbs_separator', 'esn_replace_breadcrumb_separator' );

if ( ! function_exists( 'esn_comment_form' ) ) {
	/**
	 * Customize the comment form fields.
	 *
	 * @param array $args The default comment form fields.
	 */
function esn_comment_form( $args ) {

    // 1. Убиваем надписи про email и обязательные поля ПЕРЕД формой
    $args['comment_notes_before'] = '';
    
    // 2. Убиваем любые надписи ПОСЛЕ формы (если они есть)
    $args['comment_notes_after'] = '';

    // Твоя прошлая логика по удалению полей для гостей
    if ( ! is_user_logged_in() ) {
        unset( $args['fields']['author'] );
        unset( $args['fields']['email'] );
        unset( $args['fields']['url'] );
        unset( $args['fields']['cookies'] );
    }

    // Поле самого комментария
    $args['comment_field'] = 
    '<p class="comment-form-comment">' .
        '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required" aria-required="true" placeholder="' . esc_attr__( 'Your Comment', 'esenin' ) . '"></textarea>' .
    '</p>';

    return $args;
}
}
add_filter( 'comment_form_defaults', 'esn_comment_form' );


if ( ! function_exists( 'esn_update_post_reading_time' ) ) {
	/**
	 * Update Post Reading Time on Post Save
	 *
	 * @param int $post_id The post ID.
	 */
	function esn_update_post_reading_time( $post_id ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$reading_time = esn_calculate_post_reading_time( $post_id );

		update_post_meta( $post_id, '_esn_reading_time', $reading_time );
	}
}
add_action( 'save_post', 'esn_update_post_reading_time', 10, 1 );

// Remove p tags from Contact Form 7.
add_filter( 'wpcf7_autop_or_not', '__return_false' );

