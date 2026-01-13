<?php
/**
 * Template Tags
 *
 * Functions that are called directly from template parts or within actions.
 *
 * @package Esenin
 */

if ( ! function_exists( 'esn_header_nav_menu' ) ) {
	class ESN_NAV_Walker extends Walker_Nav_Menu {
		/**
		 * Starts the element output.
		 *
		 * @since 3.0.0
		 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
		 * @since 5.9.0 Renamed `$item` to `$data_object` and `$id` to `$current_object_id`
		 *              to match parent class for PHP 8 named parameter support.
		 *
		 * @see Walker::start_el()
		 *
		 * @param string   $output            Used to append additional content (passed by reference).
		 * @param WP_Post  $data_object       Menu item data object.
		 * @param int      $depth             Depth of menu item. Used for padding.
		 * @param stdClass $args              An object of wp_nav_menu() arguments.
		 * @param int      $current_object_id Optional. ID of the current menu item. Default 0.
		 */
		public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
			// Restores the more descriptive, specific name for use within this method.
			$menu_item = $data_object;

			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

			$classes   = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
			$classes[] = 'menu-item-' . $menu_item->ID;

			/**
			 * Filters the arguments for a single nav menu item.
			 *
			 * @since 4.4.0
			 *
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param WP_Post  $menu_item Menu item data object.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 */
			$args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

			/**
			 * Filters the CSS classes applied to a menu item's list item element.
			 *
			 * @since 3.0.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string[] $classes   Array of the CSS classes that are applied to the menu item's `<li>` element.
			 * @param WP_Post  $menu_item The current menu item object.
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 */
			$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			 * Filters the ID applied to a menu item's list item element.
			 *
			 * @since 3.0.1
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string   $menu_id   The ID that is applied to the menu item's `<li>` element.
			 * @param WP_Post  $menu_item The current menu item.
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 */
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $class_names . '>';

			$atts           = array();
			$atts['title']  = ! empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
			$atts['target'] = ! empty( $menu_item->target ) ? $menu_item->target : '';
			if ( '_blank' === $menu_item->target && empty( $menu_item->xfn ) ) {
				$atts['rel'] = 'noopener';
			} else {
				$atts['rel'] = $menu_item->xfn;
			}
			$atts['href']         = ! empty( $menu_item->url ) ? $menu_item->url : '';
			$atts['aria-current'] = $menu_item->current ? 'page' : '';

			if ( '#' === trim( $menu_item->url ) ) {
					$atts['class'] = 'menu-item-without-link';
			}

			/**
			 * Filters the HTML attributes applied to a menu item's anchor element.
			 *
			 * @since 3.6.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array $atts {
			 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
			 *
			 *     @type string $title        Title attribute.
			 *     @type string $target       Target attribute.
			 *     @type string $rel          The rel attribute.
			 *     @type string $href         The href attribute.
			 *     @type string $aria-current The aria-current attribute.
			 * }
			 * @param WP_Post  $menu_item The current menu item object.
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 */
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			/**
			 * The the_title hook.
			 *
			 * @since 1.0.0
			 */
			$title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );

			/**
			 * Filters a menu item's title.
			 *
			 * @since 4.4.0
			 *
			 * @param string   $title     The menu item's title.
			 * @param WP_Post  $menu_item The current menu item object.
			 * @param stdClass $args      An object of wp_nav_menu() arguments.
			 * @param int      $depth     Depth of menu item. Used for padding.
			 */
			$title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

			$link_tag = 'a';

			$item_output  = $args->before;
			$item_output .= '<' . $link_tag . $attributes . '>';
			$item_output .= $args->link_before . '<span class="d-flex">' . $title . '</span>' . $args->link_after;
			$item_output .= '</' . $link_tag . '>';
			$item_output .= $args->after;

			/**
			 * Filters a menu item's starting output.
			 *
			 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
			 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
			 * no filter for modifying the opening and closing `<li>` for a menu item.
			 *
			 * @since 3.0.0
			 *
			 * @param string   $item_output The menu item's starting HTML output.
			 * @param WP_Post  $menu_item   Menu item data object.
			 * @param int      $depth       Depth of menu item. Used for padding.
			 * @param stdClass $args        An object of wp_nav_menu() arguments.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
		}
	}

	/**
	 * Header Nav Menu
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_header_nav_menu( $settings = array() ) {
		if ( ! get_theme_mod( 'header_navigation_menu', true ) ) {
			return;
		}

		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'menu_class'      => 'es-header__nav-inner',
					'theme_location'  => 'primary',
					'container'       => 'nav',
					'container_class' => 'es-header__nav',
					'walker'          => new ESN_NAV_Walker(),
				)
			);
		}
	}
}

if ( ! function_exists( 'esn_header_additional_menu' ) ) {
	/**
	 * Header Additional Menu
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_header_additional_menu( $settings = array() ) {
		if ( has_nav_menu( 'additional' ) ) {
			wp_nav_menu(
				array(
					'menu_class'      => 'es-header__top-nav',
					'theme_location'  => 'additional',
					'container'       => '',
					'container_class' => '',
					'depth'           => 1,
				)
			);
		}
	}
}

if ( ! function_exists( 'esn_header_logo' ) ) {
	/**
	 * Header Logo
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_header_logo( $settings = array() ) {

		$logo_default_name = 'logo';
		$logo_dark_name    = 'logo_dark';
		$logo_class        = null;

		$defaults = array(
			'variant' => null,
			'tag'     => 'div',
		);

		if ( is_front_page() && get_theme_mod( 'title_tag', true ) ) {
			$defaults['tag'] = 'h1';
		}

		$settings = apply_filters( 'esn_header_logo_settings', wp_parse_args( $settings, $defaults ) );

		// For hide logo.
		if ( 'hide' === $settings['variant'] ) {
			$logo_class = 'es-logo-hide';
		}

		// Get default logo.
		$logo_url = get_theme_mod( $logo_default_name );

		$logo_id = attachment_url_to_postid( $logo_url );

		// Set mode of logo.
		$logo_mode = 'es-logo-once';

		// Check display mode.
		if ( $logo_id ) {
			$logo_mode = 'es-logo-default';
		}

		// Logo tag
		$logo_tag = $settings['tag'];
		?>
		<<?php echo esc_html( $logo_tag ); ?> class="es-logo">
			<a class="es-header__logo <?php echo esc_attr( $logo_mode ); ?> <?php echo esc_attr( $logo_class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				if ( $logo_id ) {
					esn_get_retina_image( $logo_id, array( 'alt' => get_bloginfo( 'name' ) ), 'logo' );
				} else {
					bloginfo( 'name' );
				}
				?>
			</a>

			<?php
			if ( 'es-logo-default' === $logo_mode ) {

				$logo_dark_url = get_theme_mod( $logo_dark_name ) ? get_theme_mod( $logo_dark_name ) : $logo_url;

				$logo_dark_id = attachment_url_to_postid( $logo_dark_url );

				if ( $logo_dark_id ) {
					?>
						<a class="es-header__logo es-logo-dark <?php echo esc_attr( $logo_class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php esn_get_retina_image( $logo_dark_id, array( 'alt' => get_bloginfo( 'name' ) ), 'logo' ); ?>
						</a>
					<?php
				}
			}
			?>
		</<?php echo esc_html( $logo_tag ); ?>>
		<?php
	}
}

if ( ! function_exists( 'esn_header_offcanvas_toggle' ) ) {
	/**
	 * Header Offcanvas Toggle
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_header_offcanvas_toggle( $settings = array() ) {

		if ( esn_offcanvas_exists() ) {

			if ( ! isset( $settings['mobile'] ) ) {
				if ( ! get_theme_mod( 'header_offcanvas', false ) ) {
					return;
				}
			}

			$class = __return_empty_string();
			?>
				<span class="es-header__offcanvas-toggle <?php echo esc_attr( $class ); ?>" role="button" aria-label="<?php echo esc_attr__( 'Mobile menu button', 'esenin' ); ?>">
					<i class="es-icon es-icon-menu1"></i>
				</span>
			<?php
		}
	}
}

if ( ! function_exists( 'esn_header_search_toggle' ) ) {
	/**
	 * Header Search Toggle
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_header_search_toggle( $settings = array() ) {
		if ( ! get_theme_mod( 'header_search_button', true ) ) {
			return;
		}
		?>
		<span class="es-header__search-toggle" role="button" aria-label="<?php echo esc_attr__( 'Search', 'esenin' ); ?>">
			<i class="es-icon es-icon-search"></i>
		</span>
		<?php
	}
}

if ( ! function_exists( 'esn_header_scheme_toggle' ) ) {
	/**
	 * Header Scheme Toggle
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_header_scheme_toggle( $settings = array() ) {
		if ( ! get_theme_mod( 'color_scheme_toggle', true ) ) {
			return;
		}
		?>
			<span class="es-site-scheme-toggle es-header__scheme-toggle" role="button" aria-label="<?php echo esc_attr__( 'Dark mode toggle button', 'esenin' ); ?>">
				<span class="es-header__scheme-toggle-icons">
					<span class="es-header__scheme-es-icon-box es-light-mode" data-mode="light"><i class="es-header__scheme-toggle-icon es-icon es-icon-light-mode"></i></span>
					<span class="es-header__scheme-es-icon-box es-dark-mode" data-mode="dark"><i class="es-header__scheme-toggle-icon es-icon es-icon-dark-mode"></i></span>
				</span>
			</span>
		<?php
	}
}

if ( ! function_exists( 'esn_header_scheme_toggle_mobile' ) ) {
	/**
	 * Header Scheme Toggle Mobile
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_header_scheme_toggle_mobile( $settings = array() ) {
		if ( ! get_theme_mod( 'color_scheme_toggle', true ) ) {
			return;
		}
		?>
		<span class="es-header__scheme-toggle es-header__scheme-toggle-mobile es-site-scheme-toggle" role="button" aria-label="<?php echo esc_attr__( 'Scheme Toggle', 'esenin' ); ?>">
			<span class="es-header__scheme-toggle-icons">
				<span class="es-header__scheme-es-icon-box es-light-mode" data-mode="light"><i class="es-header__scheme-toggle-icon es-icon es-icon-light-mode"></i></span>
				<span class="es-header__scheme-es-icon-box es-dark-mode" data-mode="dark"><i class="es-header__scheme-toggle-icon es-icon es-icon-dark-mode"></i></span>
			</span>
		</span>
		<?php
	}
}

if ( ! function_exists( 'esn_header_custom_button' ) ) {
	/**
	 * Header Custom Button
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_header_custom_button( $settings = array() ) {
		if ( ! get_theme_mod( 'header_custom_button', false ) ) {
			return;
		}

		$button = get_theme_mod( 'header_custom_button_label' );
		$link   = get_theme_mod( 'header_custom_button_link' );
		$target = get_theme_mod( 'header_custom_button_target', '_self' );

		if ( $button && $link ) {
			?>
			<a class="es-button es-header__custom-button" href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $target ); ?>">
				<?php echo wp_kses( $button, 'content' ); ?>
			</a>
			<?php
		}
	}
}
if ( ! function_exists( 'esn_discover_more_button' ) ) {
	/**
	 * Discover More Button
	 */
	function esn_discover_more_button() {
		$button_label = sprintf(
		/* translators: %s: Post Title */
			__( 'Discover More: %s', 'esenin' ),
			get_the_title()
		);
		?>
		<div class="es-entry__discover-more">
			<a class="es-button" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( $button_label ); ?>">
				<?php esc_html_e( 'Discover More', 'esenin' ); ?>
			</a>
		</div>
		<?php
	}
}

if ( ! function_exists( 'esn_footer_logo' ) ) {
	/**
	 * Footer Logo
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_footer_logo( $settings = array() ) {
		$logo_url = get_theme_mod( 'footer_logo' );

		$logo_id = attachment_url_to_postid( $logo_url );

		$logo_mode = 'es-logo-once';

		if ( $logo_id ) {
			$logo_mode = 'es-logo-default';
		}
		?>
		<div class="es-logo">
			<a class="es-footer__logo <?php echo esc_attr( $logo_mode ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				if ( $logo_id ) {
					esn_get_retina_image( $logo_id, array( 'alt' => get_bloginfo( 'name' ) ), 'logo' );
				} else {
					bloginfo( 'name' );
				}
				?>
			</a>

			<?php
			if ( 'es-logo-default' === $logo_mode ) {

				$logo_dark_url = get_theme_mod( 'footer_logo_dark' ) ? get_theme_mod( 'footer_logo_dark' ) : $logo_url;

				$logo_dark_id = attachment_url_to_postid( $logo_dark_url );

				if ( $logo_dark_id ) {
					?>
						<a class="es-footer__logo es-logo-dark" href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<?php esn_get_retina_image( $logo_dark_id, array( 'alt' => get_bloginfo( 'name' ) ), 'logo' ); ?>
						</a>
					<?php
				}
			}
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'esn_footer_description' ) ) {
	/**
	 * Footer Description
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_footer_description( $settings = array() ) {

		$footer_text = get_theme_mod( 'footer_text' );
		if ( $footer_text ) {
			?>
			<div class="es-footer__desc">
				<?php echo do_shortcode( $footer_text ); ?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'esn_footer_copyright' ) ) {
	/**
	 * Footer Copyright
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_footer_copyright( $settings = array() ) {
		$footer_copyright = get_theme_mod( 'footer_copyright', '© 2025 — РФПЛ.РФ.<br> Все права защищены.' );
		if ( $footer_copyright ) {
			?>
			<div class="es-footer__copyright">
				<?php echo do_shortcode( $footer_copyright ); ?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'esn_footer_nav_menu' ) ) {
	/**
	 * Footer Nav Menu
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_footer_nav_menu( $settings = array() ) {

		$settings = array_merge(
			array(
				'menu_class' => null,
			),
			$settings
		);

		if ( has_nav_menu( 'footer' ) ) {
			?>
			<div class="es-footer__nav-menu">
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'footer',
						'container_class' => '',
						'menu_class'      => sprintf( 'es-footer__nav %s', $settings['menu_class'] ),
						'container'       => '',
						'depth'           => 2,
					)
				);
				?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'esn_misc_subscribe' ) ) {
	/**
	 * Subscribe section
	 */
	function esn_misc_subscribe() {
		$misc_subscribe = get_theme_mod( 'misc_subscribe', false );

		if ( ! $misc_subscribe ) {
			return;
		}

		if ( is_404() ) {
			return;
		}

		$subscribe_heading                = get_theme_mod( 'misc_subscribe_heading' );
		$subscribe_mailchimp              = get_theme_mod( 'misc_subscribe_mailchimp' );
		$subscribe_description            = get_theme_mod( 'misc_subscribe_description' );
		$misc_subscribe_short_description = get_theme_mod( 'misc_subscribe_short_description' );

		if ( $misc_subscribe ) {
			?>
			<div class="es-subscribe-section">
				<div class="es-container">
					<div class="es-subscribe">
						<div class="es-subscribe__content">
							<?php if ( $subscribe_heading ) { ?>
								<div class="es-subscribe__header">
									<h3 class="es-subscribe__heading">
										<?php echo esc_html( $subscribe_heading ); ?>
									</h3>
									<?php if ( $subscribe_description ) { ?>
										<div class="es-subscribe__description">
											<?php echo do_shortcode( $subscribe_description ); ?>
										</div>
									<?php } ?>
								</div>
							<?php } ?>

							<?php if ( $subscribe_mailchimp ) { ?>
								<form class="es-subscribe__form es-form-box" action="<?php echo esc_url( $subscribe_mailchimp ); ?>" method="post" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="novalidate">
									<div class="es-form-group es-subscribe__form-group" >
										<input type="email" placeholder="<?php esc_attr_e( 'Enter Your Email', 'esenin' ); ?>" name="EMAIL" required>
										<button type="submit" value="<?php esc_attr_e( 'Subscribe', 'esenin' ); ?>" aria-label="<?php echo esc_attr__( 'Subscribe', 'esenin' ); ?>" name="subscribe" class="es-button-animated">
											<i class="es-icon es-icon-send"></i>
											<span>
												<span><?php esc_html_e( 'Subscribe', 'esenin' ); ?></span>
											</span>
										</button>
									</div>
									<div class="es-subscribe__form-response clear" id="mce-responses">
										<div class="response" id="mce-error-response" style="display:none"></div>
										<div class="response" id="mce-success-response" style="display:none"></div>
									</div>
								</form>
							<?php } ?>

							<?php if ( $misc_subscribe_short_description ) { ?>
								<div class="es-subscribe__short-description">
									<?php echo do_shortcode( $misc_subscribe_short_description ); ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
}
if ( ! function_exists( 'esn_off_canvas_button' ) ) {
	/**
	 * Off-Canvas Button
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_off_canvas_button( $settings = array() ) {
		if ( ! get_theme_mod( 'header_custom_button', false ) ) {
			return;
		}

		$button = get_theme_mod( 'header_custom_button_label' );
		$link   = get_theme_mod( 'header_custom_button_link' );

		if ( $button && $link ) {
			?>
			<div class="es-offcanvas__button">
				<a class="es-button es-offcanvas__button" href="<?php echo esc_url( $link ); ?>" target="_blank">
					<?php echo wp_kses( $button, 'content' ); ?>
				</a>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'esn_scroll_to_top' ) ) {
	/**
	 * Scroll to Top
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_scroll_to_top( $settings = array() ) {
		if ( ! get_theme_mod( 'misc_scroll_to_top', true ) ) {
			return;
		}
		?>
			<button class="es-scroll-top" role="button" aria-label="<?php echo esc_attr__( 'Scroll to top button', 'esenin' ); ?>">
				<i class="es-icon-chevron-up"></i>
				<div class="es-scroll-top-border">
					<svg width="49" height="49" viewBox="0 0 49 49">
						<path d="M24.5,2 a22.5,22.5 0 0,1 0,45 a22.5,22.5 0 0,1 0,-45" style="stroke-width: 2; fill: none;"></path>
					</svg>
				</div>
				<div class="es-scroll-top-progress">
					<svg width="49" height="49" viewBox="0 0 49 49">
						<path d="M24.5,2 a22.5,22.5 0 0,1 0,45 a22.5,22.5 0 0,1 0,-45" style="stroke-width: 2; fill: none;"></path>
					</svg>
				</div>
			</button>
		<?php
	}
}

if ( ! function_exists( 'esn_off_canvas_scheme_toggle' ) ) {
	/**
	 * Offcanvas Scheme Toggle
	 *
	 * @param array $settings The advanced settings.
	 */
	function esn_off_canvas_scheme_toggle( $settings = array() ) {
		if ( ! get_theme_mod( 'color_scheme_toggle', true ) ) {
			return;
		}
		?>
			<span class="es-site-scheme-toggle es-offcanvas__scheme-toggle" role="button" aria-label="<?php echo esc_attr__( 'Scheme Toggle', 'esenin' ); ?>">
				<span class="es-header__scheme-toggle-icons">
					<span class="es-header__scheme-es-icon-box es-light-mode" data-mode="light"><i class="es-header__scheme-toggle-icon es-icon es-icon-light-mode"></i></span>
					<span class="es-header__scheme-es-icon-box es-dark-mode" data-mode="dark"><i class="es-header__scheme-toggle-icon es-icon es-icon-dark-mode"></i></span>
				</span>
			</span>
		<?php
	}
}

if ( ! function_exists( 'esn_the_post_format_icon' ) ) {
	/**
	 * Post Format Icon
	 *
	 * @param string $content After content.
	 */
	function esn_the_post_format_icon( $content = null ) {
		$post_format = get_post_format();

		if ( 'gallery' === $post_format ) {
			$attachments = count(
				(array) get_children(
					array(
						'post_parent' => get_the_ID(),
						'post_type'   => 'attachment',
					)
				)
			);

			$content = $attachments ? sprintf( '<span>%s</span>', $attachments ) : '';
		}

		if ( $post_format ) {
			?>
			<span class="es-entry-format">
				<a class="es-format-icon es-format-<?php echo esc_attr( $post_format ); ?>" href="<?php the_permalink(); ?>">
					<?php echo wp_kses( $content, 'content' ); ?>
				</a>
			</span>
			<?php
		}
	}
}

if ( ! function_exists( 'esn_post_subtitle' ) ) {
	/**
	 * Post Subtitle
	 */
	function esn_post_subtitle() {
		if ( ! is_single() ) {
			return;
		}

		if ( get_theme_mod( 'post_subtitle', true ) ) {
			/**
			 * The plugins/wp_subtitle/get_subtitle hook.
			 *
			 * @since 1.0.0
			 */
			$subtitle = apply_filters(
				'plugins/wp_subtitle/get_subtitle',
				'',
				array(
					'before'  => '',
					'after'   => '',
					'post_id' => get_the_ID(),
				)
			);

			if ( $subtitle ) {
				?>
				<div class="es-entry__subtitle">
					<?php echo wp_kses( $subtitle, 'content' ); ?>
				</div>
				<?php
			} elseif ( has_excerpt() ) {
				?>
				<div class="es-entry__subtitle">
					<?php the_excerpt(); ?>
				</div>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'esn_archive_post_description' ) ) {
	/**
	 * Post Description in Archive Pages
	 */
	function esn_archive_post_description() {
		$description = get_the_archive_description();
		if ( $description ) {
			?>
			<div class="es-page__archive-description">
				<?php echo do_shortcode( $description ); ?>
			</div>
			<?php
		}
	}
}

if ( ! function_exists( 'esn_archive_category_thumbnail' ) ) {
	/**
	 * Category Thumbnail in Archive Pages
	 */
	function esn_archive_category_thumbnail() {
		$category_id = get_queried_object_id();
		$category    = get_term( $category_id, 'category' );
		if ( $category ) {
			$esn_category_logo = get_term_meta( $category_id, 'esn_category_logo', true );
			if ( $esn_category_logo ) {
				?>
			<div class="es-page__archive-thumbnail">
				<div class="es-page__archive-thumbnail-box">
					<?php
					esn_get_retina_image(
						$esn_category_logo,
						array(
							'alt'   => esc_attr( $category->name ),
							'title' => esc_attr( $category->name ),
						)
					);
					?>
				</div>
			</div>
				<?php
			}
		}
	}
}


if ( ! function_exists( 'esn_share_links' ) ) {
	/**
	 * Share Links section
	 */
	function esn_share_links() {
		?>

		<div class="es-share">

			<?php
			$share_url = get_permalink();

			$text = rawurlencode( html_entity_decode( get_the_title( get_the_ID() ) ) );

			$twitter_share_url  = esc_url( 'https://twitter.com/share?t=' . $text . '&url=' . $share_url, null, '' );
			$facebook_share_url = esc_url( 'https://www.facebook.com/sharer.php?text=' . $text . '&u=' . $share_url, null, '' );
			$linkedin_share_url = esc_url( 'https://www.linkedin.com/shareArticle?mini=true&url=' . $share_url, null, '' );
			?>

			<a class="es-share__link" target="_blank" href="<?php call_user_func( 'printf', '%s', $twitter_share_url ); ?>" title="<?php esc_attr_e( 'Share in Twitter', 'esenin' ); ?>">
				<i class="es-icon es-icon-twitter-x"></i>
			</a>

			<a class="es-share__link" target="_blank" href="<?php call_user_func( 'printf', '%s', $facebook_share_url ); ?>" title="<?php esc_attr_e( 'Share on Facebook', 'esenin' ); ?>">
				<i class="es-icon es-icon-facebook"></i>
			</a>

			<a class="es-share__link" target="_blank" href="<?php call_user_func( 'printf', '%s', $linkedin_share_url ); ?>" title="<?php esc_attr_e( 'Share in Linkedin', 'esenin' ); ?>">
				<i class="es-icon es-icon-linkedIn"></i>
			</a>

			<a class="es-share__link es-share__copy-link" target="_blank" href="<?php echo esc_url( $share_url ); ?>" title="<?php esc_attr_e( 'Copy link', 'esenin' ); ?>">
				<i class="es-icon es-icon-link"></i>
			</a>

		</div>

		<?php
	}
}
