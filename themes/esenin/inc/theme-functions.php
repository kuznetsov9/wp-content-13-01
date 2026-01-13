<?php
/**
 * Theme Functions
 *
 * Utility functions.
 *
 * @package Esenin
 */

if ( ! function_exists( 'esn_doing_request' ) ) {
	/**
	 * Determines whether the current request is a WordPress REST or Ajax request.
	 */
	function esn_doing_request() {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		}
	}
}

if ( ! function_exists( 'esn_is_context_editor' ) ) {
	/**
	 * Determines whether the current request is from WordPress Editor.
	 */
	function esn_is_context_editor() {
		wp_verify_nonce( null );

		if ( isset( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'] ) { // Input var ok; sanitization ok.
			return true;
		}
	}
}

if ( ! function_exists( 'esn_style' ) ) {
	/**
	 * Processing path of style.
	 *
	 * @param string $path URL to the stylesheet.
	 */
	function esn_style( $path ) {
		// Check RTL.
		if ( is_rtl() ) {
			return $path;
		}

		// Check Dev.
		$dev = get_theme_file_path( 'style-dev.css' );

		if ( file_exists( $dev ) ) {
			return str_replace( '.css', '-dev.css', $path );
		}

		return $path;
	}
}

if ( ! function_exists( 'esn_typography' ) ) {
	/**
	 * Output typography style.
	 *
	 * @param string $field   The field name of kirki.
	 * @param string $type    The type of typography.
	 * @param string $default The default value.
	 */
	function esn_typography( $field, $type, $default ) {
		$value = $default;

		$field_value = get_theme_mod( $field );

		if ( is_array( $field_value ) && $field_value ) {
			if ( isset( $field_value[ $type ] ) ) {
				$value = $field_value[ $type ];
			}
		}

		echo wp_kses( $value, 'content' );
	}
}

if ( ! function_exists( 'esn_enqueue_typography_styles' ) ) {
	/**
	 * Includes typography styles.
	 *
	 * @param string $handle Handle of the style to which typography styles will be added.
	 */
	function esn_enqueue_typography_styles( $handle ) {
		ob_start();

		require get_template_directory() . '/inc/typography.php';

		$typography_css = ob_get_clean();

		if ( $typography_css ) {
			wp_add_inline_style( $handle, $typography_css );
		}
	}
}

if ( ! function_exists( 'esn_component' ) ) {
	/**
	 * Display or return the component from the theme
	 *
	 * @param string $name     The name of component.
	 * @param bool   $output   Output or return.
	 * @param array  $settings The advanced settings.
	 */
	function esn_component( $name, $output = true, $settings = array() ) {

		global $esn_components;

		$func_name = sprintf( 'esn_%s', $name );

		// Set cache key.
		$cache_key = sprintf( '%s_%s', $name, md5( maybe_serialize( $settings ) ) );

		// Get component from object cache.
		$markup = isset( $esn_components[ $cache_key ] ) ? $esn_components[ $cache_key ] : null;

		// Call component.
		if ( empty( $markup ) && function_exists( $func_name ) ) {
			ob_start();
			/**
			 * The esn_component_before hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'esn_component_before', $name, $settings );

			call_user_func( $func_name, $settings );

			/**
			 * The esn_component_after hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'esn_component_after', $name, $settings );

			$markup = ob_get_clean();

			if ( ! $esn_components ) {
				$esn_components = array();
			}

			$esn_components[ $cache_key ] = $markup;
		}

		// If there is no markup.
		if ( ! $markup ) {
			return;
		}

		// If output is enabled.
		if ( $output ) {
			return call_user_func( 'printf', '%s', $markup );
		}

		return $markup;
	}
}

if ( ! function_exists( 'esn_get_theme_data' ) ) {
	/**
	 * Get data about the theme.
	 *
	 * @param mixed $name The name of param.
	 */
	function esn_get_theme_data( $name ) {
		$theme = wp_get_theme( get_template() );

		return $theme->get( $name );
	}
}

if ( ! function_exists( 'esn_encode_data' ) ) {
	/**
	 * Encode data
	 *
	 * @param  mixed  $content    The content.
	 * @param  string $secret_key The key.
	 * @return string
	 */
	function esn_encode_data( $content, $secret_key = 'esenin' ) {

		$content = wp_json_encode( $content );

		return call_user_func( sprintf( 'base64_%s', 'encode' ), $content );
	}
}

if ( ! function_exists( 'esn_decode_data' ) ) {
	/**
	 * Decode data
	 *
	 * @param  string $content    The content.
	 * @param  string $secret_key The key.
	 * @return string
	 */
	function esn_decode_data( $content, $secret_key = 'esenin' ) {

		$content = call_user_func( sprintf( 'base64_%s', 'decode' ), $content );

		return json_decode( $content, true );
	}
}

if ( ! function_exists( 'esn_hex2rgba' ) ) {
	/**
	 * Convert hex to rgb.
	 *
	 * @param mixed $hex    Color.
	 * @param bool  $format Format.
	 */
	function esn_hex2rgba( $hex, $format = true ) {
		$hex = trim( $hex, ' #' );

		$size = strlen( $hex );
		if ( 3 === $size || 4 === $size ) {
			$parts = str_split( $hex, 1 );
			$hex   = '';
			foreach ( $parts as $row ) {
				$hex .= $row . $row;
			}
		}

		$dec = hexdec( $hex );
		$rgb = array();

		if ( 3 === $size || 6 === $size ) {
			$rgb['red']   = 0xFF & ( $dec >> 0x10 );
			$rgb['green'] = 0xFF & ( $dec >> 0x8 );
			$rgb['blue']  = 0xFF & $dec;

			$output = implode( ',', $rgb );

			if ( $format ) {
				$output = sprintf( 'rgba(%s, 1)', $output );
			}

			return $output;

		} elseif ( 5 === $size || 8 === $size ) {
			$rgb['red']   = 0xFF & ( $dec >> 0x16 );
			$rgb['green'] = 0xFF & ( $dec >> 0x10 );
			$rgb['blue']  = 0xFF & ( $dec >> 0x8 );

			$output = implode( ',', $rgb );

			if ( $format ) {
				$alpha = 0xFF & $dec;

				$output = sprintf( 'rgba(%s, %s)', $output, round( ( $alpha / ( 255 / 100 ) ) / 100, 2 ) );
			}

			return $output;
		}
	}
}

if ( ! function_exists( 'esn_rgba2hex' ) ) {
	/**
	 * Convert rgba to hex.
	 *
	 * @param mixed $color Color.
	 */
	function esn_rgba2hex( $color ) {
		if ( isset( $color[0] ) && '#' === $color[0] ) {
			return $color;
		}

		$rgba = array();

		if ( preg_match_all( '#\((([^()]+|(?R))*)\)#', $color, $matches ) ) {
			$rgba = explode( ',', implode( ' ', $matches[1] ) );
		} else {
			$rgba = explode( ',', $color );
		}

		$rr = dechex( $rgba['0'] );
		$gg = dechex( $rgba['1'] );
		$bb = dechex( $rgba['2'] );

		if ( array_key_exists( '3', $rgba ) ) {
			$aa = dechex( $rgba['3'] * 255 );

			return strtoupper( "#$aa$rr$gg$bb" );
		} else {
			return strtoupper( "#$rr$gg$bb" );
		}
	}
}

if ( ! function_exists( 'esn_get_round_number' ) ) {
	/**
	 * Get rounded number.
	 *
	 * @param int $number    Input number.
	 * @param int $min_value Minimum value to round number.
	 * @param int $decimal   How may decimals shall be in the rounded number.
	 */
	function esn_get_round_number( $number, $min_value = 1000, $decimal = 1 ) {
		if ( $number < $min_value ) {
			return number_format_i18n( $number );
		}
		$alphabets = array(
			1000000000 => esc_html__( 'B', 'esenin' ),
			1000000    => esc_html__( 'M', 'esenin' ),
			1000       => esc_html__( 'K', 'esenin' ),
		);
		foreach ( $alphabets as $key => $value ) {
			if ( $number >= $key ) {
				return number_format_i18n( round( $number / $key, $decimal ), $decimal ) . $value;
			}
		}
	}
}

if ( ! function_exists( 'esn_the_round_number' ) ) {
	/**
	 * Echo rounded number.
	 *
	 * @param int $number    Input number.
	 * @param int $min_value Minimum value to round number.
	 * @param int $decimal   How may decimals shall be in the rounded number.
	 */
	function esn_the_round_number( $number, $min_value = 1000, $decimal = 1 ) {
		echo esc_html( esn_get_round_number( $number, $min_value, $decimal ) );
	}
}

if ( ! function_exists( 'esn_str_truncate' ) ) {
	/**
	 * Truncates string with specified length
	 *
	 * @param  string $string      Text string.
	 * @param  int    $length      Letters length.
	 * @param  string $etc         End truncate.
	 * @param  bool   $break_words Break words or not.
	 * @return string
	 */
	function esn_str_truncate( $string, $length = 80, $etc = '&hellip;', $break_words = false ) {
		if ( 0 === $length ) {
			return '';
		}

		if ( function_exists( 'mb_strlen' ) ) {

			// MultiBite string functions.
			if ( mb_strlen( $string ) > $length ) {
				$length -= min( $length, mb_strlen( $etc ) );
				if ( ! $break_words ) {
					$string = preg_replace( '/\s+?(\S+)?$/', '', mb_substr( $string, 0, $length + 1 ) );
				}

				return mb_substr( $string, 0, $length ) . $etc;
			}
		} else {

			// Default string functions.
			if ( strlen( $string ) > $length ) {
				$length -= min( $length, strlen( $etc ) );
				if ( ! $break_words ) {
					$string = preg_replace( '/\s+?(\S+)?$/', '', substr( $string, 0, $length + 1 ) );
				}

				return substr( $string, 0, $length ) . $etc;
			}
		}

		return $string;
	}
}

if ( ! function_exists( 'esn_convert_retina_link' ) ) {
	/**
	 * Convert retina link.
	 *
	 * @param array  $dirname   Dirname.
	 * @param string $filename  Original filename.
	 * @param string $extension File extension.
	 *
	 * @return string Converted URL with the '@2x' suffix.
	 */
	function esn_convert_retina_link( $dirname, $filename, $extension ) {
		$filename_parts = explode( '-', $filename );

		$last_part = end( $filename_parts );

		if ( is_numeric( $last_part ) ) {
			array_pop( $filename_parts );
		}

		$filename = implode( '-', $filename_parts );

		$filename = $filename . '@2x';

		if ( is_numeric( $last_part ) ) {
			$filename = $filename . '-' . $last_part;
		}

		$new_url = $dirname . '/' . $filename . '.' . $extension;

		return $new_url;
	}
}

if ( ! function_exists( 'esn_get_retina_image' ) ) {
	/**
	 * Get retina image.
	 *
	 * @param int    $attachment_id Image attachment ID.
	 * @param array  $attr          Attributes for the image markup. Default empty.
	 * @param string $type          The tag of type.
	 */
	function esn_get_retina_image( $attachment_id, $attr = array(), $type = 'img' ) {
		$attachment_url = wp_get_attachment_url( $attachment_id );

		// Retina image.
		$attached_file = get_attached_file( $attachment_id );

		if ( $attached_file ) {
			$uriinfo  = pathinfo( $attachment_url );
			$pathinfo = pathinfo( $attached_file );

			$retina_uri  = sprintf( '%s/%s@2x.%s', $uriinfo['dirname'], $uriinfo['filename'], $uriinfo['extension'] );
			$retina_file = sprintf( '%s/%s@2x.%s', $pathinfo['dirname'], $pathinfo['filename'], $pathinfo['extension'] );

			if ( file_exists( $retina_file ) ) {
				$attr['srcset'] = sprintf( '%s 1x, %s 2x', $attachment_url, $retina_uri );
			} else {
				$retina_uri  = esn_convert_retina_link( $uriinfo['dirname'], $uriinfo['filename'], $uriinfo['extension'] );
				$retina_file = esn_convert_retina_link( $pathinfo['dirname'], $pathinfo['filename'], $pathinfo['extension'] );

				if ( file_exists( $retina_file ) ) {
					$attr['srcset'] = sprintf( '%s 1x, %s 2x', $attachment_url, $retina_uri );
				}
			}
		}

		// Sizes.
		if ( 'amp-img' === $type || 'logo' === $type ) {
			$data = wp_get_attachment_image_src( $attachment_id, 'full' );

			if ( isset( $data[1] ) ) {
				$attr['width'] = $data[1];
			}
			if ( isset( $data[2] ) ) {
				$attr['height'] = $data[2];
			}

			// Calc max height and set new width depending on proportion.
			if ( isset( $attr['width'] ) && isset( $attr['height'] ) ) {
				/**
				 * The esn_amp_navbar_height hook.
				 *
				 * @since 1.0.0
				 */
				if ( 'amp-img' === $type ) {
					$max_height = apply_filters( 'esn_amp_navbar_height', 88 ) - 20;
				} elseif ( 'logo' === $type ) {
					$header_height = get_theme_mod( 'header_height', 88 );
					$header_height = (int) $header_height;
					$max_height    = $header_height - 20;
				}

				if ( $max_height > 0 && $attr['height'] > $max_height ) {
					$attr['width'] = $attr['width'] / $attr['height'] * $max_height;

					$attr['height'] = $max_height;
				}
			}

			if ( 'logo' === $type ) {
				$type = 'img';
			}
		}

		// Attr.
		$output = __return_null();

		foreach ( $attr as $name => $value ) {
			$output .= sprintf( ' %s="%s" ', esc_attr( $name ), esc_attr( $value ) );
		}

		// Image output.
		call_user_func( 'printf', '<%1$s src="%2$s" %3$s>', esc_attr( $type ), esc_url( $attachment_url ), $output );
	}
}

if ( ! function_exists( 'esn_offcanvas_exists' ) ) {
	/**
	 * Check if offcanvas exists.
	 */
	function esn_offcanvas_exists() {
		$locations = get_nav_menu_locations();

		if ( isset( $locations['primary'] ) || isset( $locations['mobile'] ) || is_active_sidebar( 'sidebar-offcanvas' ) ) {
			return true;
		}
	}
}

if ( ! function_exists( 'esn_site_content_class' ) ) {
	/**
	 * Display the classes for the es-site-content element.
	 *
	 * @param array $class Classes to add to the class list.
	 */
	function esn_site_content_class( $class = array() ) {
		$class[] = 'es-site-content';

		/**
		 * The esn_site_content_class hook.
		 *
		 * @since 1.0.0
		 */
		$class = apply_filters( 'esn_site_content_class', $class );

		// Separates classes with a single space, collates classes.
		printf( 'class="%s"', esc_attr( join( ' ', $class ) ) );
	}
}

if ( ! function_exists( 'esn_site_submenu_class' ) ) {
	/**
	 * Display the classes for the site-submenu element.
	 *
	 * @param array $class Classes to add to the class list.
	 */
	function esn_site_submenu_class( $class = array() ) {
		$class[] = 'es-site-submenu';

		/**
		 * The esn_site_submenu_class hook.
		 *
		 * @since 1.0.0
		 */
		$class = apply_filters( 'esn_site_submenu_class', $class );

		// Separates classes with a single space, collates classes.
		printf( 'class="%s"', esc_attr( join( ' ', $class ) ) );
	}
}

if ( ! function_exists( 'esn_site_scheme_data' ) ) {
	/**
	 * Get site scheme data
	 */
	function esn_site_scheme_data() {

		// Get options.
		$color_scheme = get_theme_mod( 'color_scheme', 'system' ); // Field. User’s system preference.
		$color_toggle = get_theme_mod( 'color_scheme_toggle', true ); // Field. Enable dark/light mode toggle.

		// Set site scheme.
		$site_scheme = __return_empty_string();

		switch ( $color_scheme ) {
			case 'dark':
				$site_scheme = 'dark';
				break;
			case 'light':
				$site_scheme = 'light';
				break;
			case 'system':
				$site_scheme = 'auto';
				break;
		}

		if ( $color_toggle ) {
			if ( isset( $_COOKIE['_color_schema'] ) && 'light' === $_COOKIE['_color_schema'] ) {
				$site_scheme = 'light';
			}
			if ( isset( $_COOKIE['_color_schema'] ) && 'dark' === $_COOKIE['_color_schema'] ) {
				$site_scheme = 'dark';
			}
		}

		return $site_scheme;
	}
}

if ( ! function_exists( 'esn_get_the_excerpt' ) ) {
	/**
	 * Filters the number of words in an excerpt.
	 */
	function esn_get_the_excerpt_length() {
		return 5000;
	}

	/**
	 * Get excerpt of post.
	 *
	 * @param int    $length      Letters length.
	 * @param string $etc         End truncate.
	 * @param bool   $break_words Break words or not.
	 */
	function esn_get_the_excerpt( $length = 80, $etc = '&hellip;', $break_words = false ) {
		add_filter( 'excerpt_length', 'esn_get_the_excerpt_length' );

		$excerpt = get_the_excerpt();

		call_user_func( 'remove_filter', 'excerpt_length', 'esn_get_the_excerpt_length' );

		return esn_str_truncate( $excerpt, $length, $etc, $break_words );
	}
}

if ( ! function_exists( 'esn_get_archive_location' ) ) {
	/**
	 * Returns Archive Location.
	 */
	function esn_get_archive_location() {

		global $wp_query;

		if ( isset( $wp_query->query_vars['esn_query']['location'] ) ) {
			return $wp_query->query_vars['esn_query']['location'];
		}

		if ( is_home() ) {
			return 'home';
		} else {
			return 'archive';
		}
	}
}

if ( ! function_exists( 'esn_get_archive_option' ) ) {
	/**
	 * Returns Archive Option Name.
	 *
	 * @param string $option_name The customize option name.
	 */
	function esn_get_archive_option( $option_name ) {

		return esn_get_archive_location() . '_' . $option_name;
	}
}

if ( ! function_exists( 'esn_get_archive_options' ) ) {
	/**
	 * Returns Archive Options.
	 */
	function esn_get_archive_options() {

		$options = array(
			'location'          => esn_get_archive_location(),
			'meta'              => esn_get_archive_option( 'post_meta' ),
			'layout'            => get_theme_mod( esn_get_archive_option( 'layout' ), 'full' ),
			'columns'           => get_theme_mod( esn_get_archive_option( 'columns_desktop' ), 1 ),
			'image_orientation' => get_theme_mod( esn_get_archive_option( 'image_orientation' ), 'landscape-16-9' ),
			'image_size'        => get_theme_mod( esn_get_archive_option( 'image_size' ), 'esn-thumbnail' ),
			'summary_type'      => get_theme_mod( esn_get_archive_option( 'summary' ), 'summary' ),
			'excerpt'           => get_theme_mod( esn_get_archive_option( 'excerpt' ), true ),
			'discover_more'     => get_theme_mod( esn_get_archive_option( 'discover_more' ), true ),
		);

		/**
		 * The esn_get_archive_options hook.
		 *
		 * @since 1.0.0
		 */
		$options = apply_filters( 'esn_get_archive_options', $options );

		return $options;
	}
}

if ( ! function_exists( 'esn_get_page_preview' ) ) {
	/**
	 * Returns Page Preview.
	 */
	function esn_get_page_preview() {

		if ( is_home() ) {
			/**
			 * The esn_page_media_preview hook.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'esn_page_media_preview', get_theme_mod( 'home_media_preview', 'cropped' ) );
		}

		if ( is_singular( array( 'post', 'page' ) ) ) {

			$post_type = get_post_type( get_queried_object_id() );

			/**
			 * The esn_page_media_preview hook.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'esn_page_media_preview', get_theme_mod( $post_type . '_media_preview', 'cropped' ) );
		}

		if ( is_archive() ) {
			/**
			 * The esn_page_media_preview hook.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'esn_page_media_preview', get_theme_mod( 'archive_media_preview', 'cropped' ) );
		}

		if ( is_404() ) {
			/**
			 * The esn_page_media_preview hook.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'esn_page_media_preview', 'cropped' );
		}

		/**
		 * The esn_page_media_preview hook.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'esn_page_media_preview', 'cropped' );
	}
}

if ( ! function_exists( 'esn_get_page_sidebar' ) ) {
	/**
	 * Returns Page Sidebar: right, left or disabled.
	 *
	 * @param int    $post_id The ID of post.
	 * @param string $layout  The layout of post.
	 */
	function esn_get_page_sidebar( $post_id = false, $layout = false ) {

		/**
		 * The esn_sidebar hook.
		 *
		 * @since 1.0.0
		 */
		$location = apply_filters( 'esn_sidebar', 'sidebar-main' );

		if ( ! is_active_sidebar( $location ) ) {
			return 'disabled';
		}

		$home_id = false;

		if ( 'page' === get_option( 'show_on_front', 'posts' ) ) {

			$page_on_front = get_option( 'page_on_front' );

			if ( $post_id && intval( $post_id ) === intval( $page_on_front ) ) {
				$home_id = $post_id;
			}
		}

		if ( is_home() || $home_id ) {

			$show_on_front = get_option( 'show_on_front', 'posts' );

			if ( 'posts' === $show_on_front ) {

				return apply_filters( 'esn_page_sidebar', get_theme_mod( 'home_sidebar', 'right' ) );
			}

			if ( 'page' === $show_on_front ) {

				$home_id = $home_id ? $home_id : get_queried_object_id();

				// Get layout for the blog posts page.
				if ( ! $layout ) {
					$layout = get_post_meta( $home_id, 'esn_singular_sidebar', true );
				}

				if ( ! $layout || 'default' === $layout ) {

					return apply_filters( 'esn_page_sidebar', get_theme_mod( 'page_sidebar', 'disabled' ) );
				}

				return apply_filters( 'esn_page_sidebar', $layout );
			}
		}

		if ( is_singular( array( 'post', 'page' ) ) || $post_id ) {

			$post_id = $post_id ? $post_id : get_queried_object_id();

			// Get layout for current post.
			if ( ! $layout ) {
				$layout = get_post_meta( $post_id, 'esn_singular_sidebar', true );
			}

			if ( ! $layout || 'default' === $layout ) {

				$post_type = get_post_type( $post_id );

				return apply_filters( 'esn_page_sidebar', get_theme_mod( $post_type . '_sidebar', 'right' ) );
			}

			return apply_filters( 'esn_page_sidebar', $layout );
		}

		if ( is_archive() ) {

			return apply_filters( 'esn_page_sidebar', get_theme_mod( 'archive_sidebar', 'right' ) );
		}

		if ( is_search() ) {

			return apply_filters( 'esn_page_sidebar', 'disabled' );
		}

		if ( is_404() ) {

			return apply_filters( 'esn_page_sidebar', 'disabled' );
		}

		return apply_filters( 'esn_page_sidebar', 'disabled' );
	}
}

if ( ! function_exists( 'esn_get_page_header_type' ) ) {
	/**
	 * Returns Page Header
	 */
	function esn_get_page_header_type() {

		$allow = array( 'none', 'standard', 'split', 'overlay', 'title' );

		if ( is_singular( array( 'post', 'page' ) ) ) {
			$page_header_type = get_post_meta( get_queried_object_id(), 'esn_page_header_type', true );

			if ( ! in_array( $page_header_type, $allow, true ) || 'default' === $page_header_type ) {

				$post_type = get_post_type( get_queried_object_id() );

				/**
				 * The esn_page_header_type hook.
				 *
				 * @since 1.0.0
				 */
				return apply_filters( 'esn_page_header_type', get_theme_mod( $post_type . '_header_type', 'standard' ) );
			}

			/**
			 * The esn_page_header_type hook.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'esn_page_header_type', $page_header_type );
		}

		/**
		 * The esn_page_header_type hook.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'esn_page_header_type', 'standard' );
	}
}

if ( ! function_exists( 'esn_post_views_enabled' ) ) {
	/**
	 * Check post views module.
	 *
	 * @return string Type.
	 */
	function esn_post_views_enabled() {

		// Post Views Counter.
		if ( class_exists( 'Post_Views_Counter' ) ) {
			return 'post_views';
		}
	}
}

if ( ! function_exists( 'esn_get_page_id_by_title' ) ) {
	/**
	 * Get page id by title
	 *
	 * @param string $title Page title.
	 */
	function esn_get_page_id_by_title( $title ) {
		$query = new WP_Query();

		$pages = $query->query(
			array(
				'post_type' => 'page',
				'title'     => $title,
			)
		);

		if ( $pages ) {
			foreach ( $pages as $find_page ) {
				return $find_page->ID;
			}
		}
	}
}

if ( ! function_exists( 'esn_breadcrumbs' ) ) {
	/**
	 * SEO Breadcrumbs
	 */
	function esn_breadcrumbs() {

		if ( esn_doing_request() ) {
			return;
		}

		ob_start();

		$wrap_before = '<div class="es-breadcrumbs" id="breadcrumbs">';
		$wrap_after  = '</div>';

		if ( function_exists( 'yoast_breadcrumb' ) ) {

			yoast_breadcrumb( '<div class="es-breadcrumbs" id="breadcrumbs">', '</div>' );

		} elseif ( function_exists( 'rank_math_the_breadcrumbs' ) ) {

			$args = array(
				'wrap_before' => $wrap_before,
				'wrap_after'  => $wrap_after,
			);
			rank_math_the_breadcrumbs( $args );

		} elseif ( function_exists( 'aioseo_breadcrumbs' ) ) {

			$breadcrumbs = str_replace( '<div class="aioseo-breadcrumbs">', $wrap_before, aioseo_breadcrumbs( false ) );
			echo wp_kses_post( $breadcrumbs );

		}

		$output_breadcrumbs = ob_get_clean();

		/**
		 * The esn_breadcrumbs hook.
		 *
		 * @since 1.0.0
		 */
		$output_breadcrumbs = apply_filters( 'esn_breadcrumbs', $output_breadcrumbs );

		$header_type = esn_get_page_header_type();

		call_user_func( 'printf', '%s', $output_breadcrumbs );
	}
}

if ( ! function_exists( 'esn_get_available_image_sizes' ) ) {
	/**
	 * Get the available image sizes
	 */
	function esn_get_available_image_sizes() {
		$wais = & $GLOBALS['_wp_additional_image_sizes'];

		$sizes       = array();
		$image_sizes = get_intermediate_image_sizes();

		if ( is_array( $image_sizes ) && $image_sizes ) {
			foreach ( $image_sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
					$sizes[ $size ] = array(
						'width'  => get_option( "{$size}_size_w" ),
						'height' => get_option( "{$size}_size_h" ),
						'crop'   => (bool) get_option( "{$size}_crop" ),
					);
				} elseif ( isset( $wais[ $size ] ) ) {
					$sizes[ $size ] = array(
						'width'  => $wais[ $size ]['width'],
						'height' => $wais[ $size ]['height'],
						'crop'   => $wais[ $size ]['crop'],
					);
				}

				// Size registered, but has 0 width and height.
				if ( 0 === (int) $sizes[ $size ]['width'] && 0 === (int) $sizes[ $size ]['height'] ) {
					unset( $sizes[ $size ] );
				}
			}
		}

		return $sizes;
	}
}

if ( ! function_exists( 'esn_get_image_size' ) ) {
	/**
	 * Gets the data of a specific image size.
	 *
	 * @param string $size Name of the size.
	 */
	function esn_get_image_size( $size ) {
		if ( ! is_string( $size ) ) {
			return;
		}

		$sizes = esn_get_available_image_sizes();

		return isset( $sizes[ $size ] ) ? $sizes[ $size ] : false;
	}
}

if ( ! function_exists( 'esn_get_list_available_image_sizes' ) ) {
	/**
	 * Get the list available image sizes
	 */
	function esn_get_list_available_image_sizes() {

		$image_sizes = wp_cache_get( 'esn_available_image_sizes' );

		if ( empty( $image_sizes ) ) {
			$image_sizes = array();

			$intermediate_image_sizes = get_intermediate_image_sizes();

			foreach ( $intermediate_image_sizes as $size ) {
				$image_sizes[ $size ] = $size;

				$data = esn_get_image_size( $size );

				if ( isset( $data['width'] ) || isset( $data['height'] ) ) {

					$width  = '~';
					$height = '~';

					if ( isset( $data['width'] ) && $data['width'] ) {
						$width = $data['width'] . 'px';
					}
					if ( isset( $data['height'] ) && $data['height'] ) {
						$height = $data['height'] . 'px';
					}

					$image_sizes[ $size ] .= sprintf( ' [%s, %s]', $width, $height );
				}
			}

			wp_cache_set( 'esn_available_image_sizes', $image_sizes );
		}

		return $image_sizes;
	}
}

if ( ! function_exists( 'esn_get_hero_query_args' ) ) {
	/**
	 * Get query arguments for the hero section.
	 */
	function esn_get_hero_query_args() {
		$args = array(
			'post_type'           => 'post',
			'posts_per_page'      => 4,
			'ignore_sticky_posts' => true,
		);

		$max_count = get_theme_mod( 'home_hero_max_count', 3 );

		switch ( get_theme_mod( 'home_hero_layout', 'hero-type-1' ) ) {
			case 'hero-type-2':
				if ( ! empty( $max_count ) ) {
					$args['posts_per_page'] = $max_count;
				}
				break;
		}

		$hero_filter_posts = get_theme_mod( 'home_hero_filter_posts' );
		$hero_posts        = ! empty( $hero_filter_posts ) ? explode( ',', $hero_filter_posts ) : array();

		if ( ! empty( $hero_posts ) ) {
			$args['post__in'] = $hero_posts;
			$args['orderby']  = 'post__in';
		}

		$hero_filter_tags = get_theme_mod( 'home_hero_filter_tags' );
		$hero_tags        = ! empty( $hero_filter_tags ) ? explode( ',', $hero_filter_tags ) : array();

		if ( ! empty( $hero_tags ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'terms'    => $hero_tags,
				),
			);
		}

		$hero_filter_categories = get_theme_mod( 'home_hero_filter_categories' );
		$hero_categories        = ! empty( $hero_filter_categories ) ? explode( ',', $hero_filter_categories ) : array();

		if ( ! empty( $hero_categories ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => $hero_categories,
				),
			);
		}

		return $args;
	}
}

if ( ! function_exists( 'esn_user_social_links' ) ) {
	/**
	 * User Social URLs
	 */
	function esn_user_social_links() {

		$options = array(
		    'vk'  => array(
				'key'  => 'vk',
				'icon' => '<svg width="29" height="28" viewBox="0 0 101 100" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_2_16)"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.52944 7.02944C0.5 14.0589 0.5 25.3726 0.5 48V52C0.5 74.6274 0.5 85.9411 7.52944 92.9706C14.5589 100 25.8726 100 48.5 100H52.5C75.1274 100 86.4411 100 93.4706 92.9706C100.5 85.9411 100.5 74.6274 100.5 52V48C100.5 25.3726 100.5 14.0589 93.4706 7.02944C86.4411 0 75.1274 0 52.5 0H48.5C25.8726 0 14.5589 0 7.52944 7.02944ZM17.3752 30.4169C17.9168 56.4169 30.9167 72.0418 53.7084 72.0418H55.0003V57.1668C63.3753 58.0001 69.7082 64.1252 72.2498 72.0418H84.0835C80.8335 60.2085 72.2914 53.6668 66.9581 51.1668C72.2914 48.0835 79.7915 40.5835 81.5831 30.4169H70.8328C68.4995 38.6669 61.5836 46.1668 55.0003 46.8751V30.4169H44.2499V59.2501C37.5833 57.5835 29.1668 49.5002 28.7918 30.4169H17.3752Z" fill="black"/></g><defs><clipPath id="clip0_2_16"><rect width="100" height="100" fill="white" transform="translate(0.5)"/></clipPath></defs></svg>',
			),		
			'facebook'   => array(
				'key'  => 'facebook',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12C2 16.991 5.657 21.128 10.438 21.879V14.89H7.898V12H10.438V9.797C10.438 7.291 11.93 5.907 14.215 5.907C15.309 5.907 16.453 6.102 16.453 6.102V8.562H15.193C13.95 8.562 13.563 9.333 13.563 10.124V12H16.336L15.893 14.89H13.563V21.879C18.343 21.129 22 16.99 22 12C22 6.477 17.523 2 12 2Z"/></svg>',
			),
			'twitter'    => array(
				'key'     => 'twitter',
				'prepend' => 'https://twitter.com/',
				'icon'    => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path d="M13.8994 10.472L21.3321 2.02222H19.5708L13.117 9.35906L7.96237 2.02222H2.01709L9.81193 13.1168L2.01709 21.9777H3.7785L10.5939 14.2298L16.0376 21.9777H21.9829L13.899 10.472H13.8994ZM11.4869 13.2146L10.6972 12.1098L4.41317 3.31901H7.1186L12.1899 10.4135L12.9796 11.5182L19.5717 20.7399H16.8662L11.4869 13.215V13.2146Z"/></svg>',
			),
			'instagram'  => array(
				'key'  => 'instagram',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><g clip-path="url(#clip0_3400_4483)"><path d="M12 2C14.717 2 15.056 2.01 16.122 2.06C17.187 2.11 17.912 2.277 18.55 2.525C19.21 2.779 19.766 3.123 20.322 3.678C20.8305 4.1779 21.224 4.78259 21.475 5.45C21.722 6.087 21.89 6.813 21.94 7.878C21.987 8.944 22 9.283 22 12C22 14.717 21.99 15.056 21.94 16.122C21.89 17.187 21.722 17.912 21.475 18.55C21.2247 19.2178 20.8311 19.8226 20.322 20.322C19.822 20.8303 19.2173 21.2238 18.55 21.475C17.913 21.722 17.187 21.89 16.122 21.94C15.056 21.987 14.717 22 12 22C9.283 22 8.944 21.99 7.878 21.94C6.813 21.89 6.088 21.722 5.45 21.475C4.78233 21.2245 4.17753 20.8309 3.678 20.322C3.16941 19.8222 2.77593 19.2175 2.525 18.55C2.277 17.913 2.11 17.187 2.06 16.122C2.013 15.056 2 14.717 2 12C2 9.283 2.01 8.944 2.06 7.878C2.11 6.812 2.277 6.088 2.525 5.45C2.77524 4.78218 3.1688 4.17732 3.678 3.678C4.17767 3.16923 4.78243 2.77573 5.45 2.525C6.088 2.277 6.812 2.11 7.878 2.06C8.944 2.013 9.283 2 12 2ZM12 7C10.6739 7 9.40215 7.52678 8.46447 8.46447C7.52678 9.40215 7 10.6739 7 12C7 13.3261 7.52678 14.5979 8.46447 15.5355C9.40215 16.4732 10.6739 17 12 17C13.3261 17 14.5979 16.4732 15.5355 15.5355C16.4732 14.5979 17 13.3261 17 12C17 10.6739 16.4732 9.40215 15.5355 8.46447C14.5979 7.52678 13.3261 7 12 7ZM18.5 6.75C18.5 6.41848 18.3683 6.10054 18.1339 5.86612C17.8995 5.6317 17.5815 5.5 17.25 5.5C16.9185 5.5 16.6005 5.6317 16.3661 5.86612C16.1317 6.10054 16 6.41848 16 6.75C16 7.08152 16.1317 7.39946 16.3661 7.63388C16.6005 7.8683 16.9185 8 17.25 8C17.5815 8 17.8995 7.8683 18.1339 7.63388C18.3683 7.39946 18.5 7.08152 18.5 6.75ZM12 9C12.7956 9 13.5587 9.31607 14.1213 9.87868C14.6839 10.4413 15 11.2044 15 12C15 12.7956 14.6839 13.5587 14.1213 14.1213C13.5587 14.6839 12.7956 15 12 15C11.2044 15 10.4413 14.6839 9.87868 14.1213C9.31607 13.5587 9 12.7956 9 12C9 11.2044 9.31607 10.4413 9.87868 9.87868C10.4413 9.31607 11.2044 9 12 9Z"/></g><defs><clipPath id="clip0_3400_4483"><rect width="28" height="28" fill="white"/></clipPath></defs></svg>',
			),
			'linkedin'   => array(
				'key'  => 'linkedin',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.66667 2C2.74619 2 2 2.74619 2 3.66667V20.3333C2 21.2538 2.74619 22 3.66667 22H20.3333C21.2538 22 22 21.2538 22 20.3333V3.66667C22 2.74619 21.2538 2 20.3333 2H3.66667ZM8.13418 6.44747C8.14043 7.50997 7.34512 8.16466 6.40137 8.15997C5.5123 8.15528 4.7373 7.44747 4.74199 6.44903C4.74668 5.50997 5.48887 4.75528 6.45293 4.77716C7.43106 4.79903 8.14043 5.51622 8.13418 6.44747ZM12.3108 9.51307H9.51079H9.50922V19.024H12.4686V18.8021C12.4686 18.38 12.4682 17.9578 12.4679 17.5354C12.467 16.409 12.466 15.2813 12.4718 14.1552C12.4733 13.8818 12.4858 13.5974 12.5561 13.3364C12.8201 12.3614 13.6968 11.7318 14.6749 11.8866C15.303 11.9849 15.7186 12.349 15.8936 12.9412C16.0014 13.3114 16.0499 13.7099 16.0546 14.0959C16.0672 15.2599 16.0654 16.4239 16.0637 17.588C16.063 17.9989 16.0623 18.41 16.0623 18.8209V19.0224H19.0311V18.7943C19.0311 18.2921 19.0309 17.79 19.0306 17.2879C19.03 16.0329 19.0293 14.7779 19.0327 13.5224C19.0342 12.9552 18.9733 12.3959 18.8342 11.8474C18.6264 11.0318 18.1968 10.3568 17.4983 9.86933C17.003 9.52243 16.4592 9.299 15.8514 9.274C15.7822 9.27112 15.7124 9.26736 15.6423 9.26357C15.3316 9.24677 15.0157 9.2297 14.7186 9.28962C13.8686 9.45993 13.1218 9.849 12.5577 10.5349C12.4921 10.6136 12.428 10.6934 12.3323 10.8127L12.3108 10.8397V9.51307ZM4.9796 19.0271H7.92491V9.51926H4.9796V19.0271Z" /></svg>',
			),
			'myspace'    => array(
				'key'  => 'myspace',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path d="M19.8694 2H4.13063C2.95283 2 1.99854 2.95401 1.99854 4.13168V19.8683C1.99854 21.046 2.95283 22 4.13063 22H19.8694C21.0471 22 22.0015 21.046 22.0015 19.8683V4.13168C22.0015 2.95401 21.0471 2 19.8694 2ZM14.9898 5.58237C16.1037 5.58237 17.0066 6.48581 17.0066 7.60001C17.0066 8.71324 16.1037 9.61672 14.9898 9.61672C13.8754 9.61672 12.9725 8.71324 12.9725 7.60001C12.9725 6.48581 13.8754 5.58237 14.9898 5.58237ZM10.9258 6.2494C11.7837 6.2494 12.4814 6.94702 12.4814 7.80654C12.4814 8.66505 11.7838 9.36271 10.9258 9.36271C10.066 9.36271 9.36831 8.66505 9.36831 7.80654C9.36835 6.94702 10.066 6.2494 10.9258 6.2494ZM7.93989 6.75812C8.51765 6.75812 8.98734 7.22807 8.98734 7.80654C8.98734 8.38401 8.51769 8.85399 7.93989 8.85399C7.36209 8.85399 6.89185 8.38405 6.89185 7.80654C6.89185 7.22807 7.36213 6.75812 7.93989 6.75812ZM17.7152 17.946C17.7152 18.2126 17.4991 18.4287 17.2326 18.4287H12.4827C12.2161 18.4287 12 18.2126 12 17.946V15.5717H8.91045C8.64386 15.5717 8.42776 15.3556 8.42776 15.0891V13.4286H6.76732C6.50073 13.4286 6.28463 13.2125 6.28463 12.946V10.9286C6.28463 9.94244 7.08459 9.14289 8.07074 9.14289C8.7951 9.14289 9.41461 9.57543 9.6947 10.1949C10.0608 9.98644 10.4776 9.8571 10.9284 9.8571C11.8788 9.8571 12.6963 10.3945 13.119 11.1756C13.6017 10.8023 14.2003 10.5714 14.8576 10.5714C16.4359 10.5714 17.7152 11.8507 17.7152 13.4287V17.946Z" /></svg>',
			),
			'pinterest'  => array(
				'key'  => 'pinterest',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path d="M12.001 22C6.47813 22 2.00098 17.5228 2.00098 12C2.00098 6.47715 6.47813 2 12.001 2C17.5238 2 22.001 6.47715 22.001 12C22.001 17.5228 17.5238 22 12.001 22ZM18.671 12C18.65 11.425 18.2932 10.916 17.7598 10.7C17.2265 10.4841 16.6161 10.6016 16.201 11C15.0634 10.2267 13.7262 9.7995 12.351 9.77L13.001 6.65L15.141 7.1C15.1935 7.58851 15.5932 7.96647 16.0839 7.99172C16.5745 8.01696 17.0109 7.68201 17.1133 7.20147C17.2157 6.72094 16.9538 6.23719 16.4955 6.06019C16.0372 5.88318 15.5181 6.06536 15.271 6.49L12.821 6C12.74 5.98224 12.6554 5.99763 12.5858 6.04272C12.5163 6.08781 12.4678 6.15886 12.451 6.24L11.711 9.71C10.3189 9.73099 8.96325 10.1585 7.81098 10.94C7.38972 10.5436 6.77418 10.4333 6.2415 10.6588C5.70882 10.8842 5.35944 11.4028 5.35067 11.9812C5.3419 12.5595 5.67538 13.0885 6.20098 13.33C6.18972 13.4765 6.18972 13.6235 6.20098 13.77C6.20098 16.01 8.81098 17.83 12.031 17.83C15.251 17.83 17.861 16.01 17.861 13.77C17.8722 13.6235 17.8722 13.4765 17.861 13.33C18.3646 13.0797 18.6797 12.5623 18.671 12ZM8.67098 13C8.67098 12.4477 9.11869 12 9.67098 12C10.2233 12 10.671 12.4477 10.671 13C10.671 13.5523 10.2233 14 9.67098 14C9.40576 14 9.15141 13.8946 8.96387 13.7071C8.77633 13.5196 8.67098 13.2652 8.67098 13ZM14.481 15.75C13.7715 16.2847 12.8986 16.5568 12.011 16.52C11.1234 16.5568 10.2505 16.2847 9.54098 15.75C9.45288 15.6427 9.46057 15.486 9.55877 15.3878C9.65696 15.2896 9.81363 15.2819 9.92098 15.37C10.5222 15.811 11.2561 16.0333 12.001 16C12.7468 16.0406 13.4841 15.8254 14.091 15.39C14.1624 15.3203 14.2656 15.2941 14.3617 15.3211C14.4577 15.3482 14.5321 15.4244 14.5567 15.5211C14.5813 15.6178 14.5524 15.7203 14.481 15.79V15.75ZM14.301 14.04C13.7487 14.04 13.301 13.5923 13.301 13.04C13.301 12.4877 13.7487 12.04 14.301 12.04C14.8533 12.04 15.301 12.4877 15.301 13.04C15.312 13.3138 15.2101 13.5802 15.0192 13.7767C14.8282 13.9733 14.565 14.083 14.291 14.08L14.301 14.04Z"/></svg>',
			),
			'youtube'    => array(
				'key'  => 'youtube',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"><path d="M21.8593 7.22498C21.6189 6.36844 20.9255 5.69771 20.0366 5.46862C18.8427 5.02444 7.27814 4.8068 3.92342 5.48135C3.03455 5.71298 2.33851 6.38116 2.10078 7.23771C1.56191 9.51589 1.52097 14.4413 2.11399 16.7704C2.35436 17.627 3.04776 18.2977 3.93663 18.5268C6.30078 19.0512 17.5404 19.125 20.0498 18.5268C20.9387 18.2952 21.6347 17.627 21.8725 16.7704C22.447 14.2886 22.4879 9.66862 21.8593 7.22498Z"/><path d="M15.3877 11.9975L9.99902 9.01929V14.9757L15.3877 11.9975Z" fill="white"/></svg>',
			),
			'soundcloud' => array(
				'key'  => 'soundcloud',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 20 20"><g clip-path="url(#clip0_3402_895)"><path d="M0.226503 9.49439C0.184772 9.49439 0.151649 9.52666 0.146253 9.57268L0 10.6836L0.146253 11.7754C0.151649 11.8214 0.184751 11.8535 0.226503 11.8535C0.266922 11.8535 0.299842 11.8216 0.306289 11.7758L0.479642 10.6836L0.306289 9.57245C0.300044 9.52664 0.26672 9.49439 0.226503 9.49439ZM1.04899 8.88741C1.0421 8.83967 1.00791 8.80675 0.965752 8.80675C0.923373 8.80675 0.888311 8.84074 0.882511 8.88741C0.882511 8.88783 0.685917 10.6836 0.685917 10.6836L0.882511 12.44C0.888108 12.4871 0.923171 12.5211 0.965752 12.5211C1.00769 12.5211 1.0419 12.488 1.04877 12.4405L1.27244 10.6836L1.04899 8.88741ZM4.14713 7.23059C4.06647 7.23059 3.99978 7.29661 3.99592 7.3805L3.83934 10.6851L3.99592 12.8207C4 12.9042 4.06647 12.97 4.14713 12.97C4.22736 12.97 4.29382 12.9039 4.29855 12.8203V12.8209L4.47556 10.6851L4.29855 7.3805C4.2938 7.29661 4.22734 7.23059 4.14713 7.23059ZM2.54364 8.37915C2.4817 8.37915 2.43115 8.42862 2.42618 8.49464L2.24982 10.6842L2.42618 12.8026C2.43091 12.8682 2.48168 12.9177 2.54364 12.9177C2.60493 12.9177 2.65547 12.8682 2.66087 12.8026L2.86132 10.6842L2.66087 8.4942C2.65547 8.42862 2.60493 8.37915 2.54364 8.37915ZM5.77665 12.9698C5.87666 12.9698 5.95796 12.8891 5.96204 12.7859L6.11563 10.6862L5.96227 6.28654C5.95818 6.18329 5.87686 6.10264 5.77687 6.10264C5.67598 6.10264 5.59448 6.18351 5.59123 6.28676L5.45508 10.686L5.59123 12.7865C5.59446 12.8889 5.67596 12.9698 5.77665 12.9698ZM9.11266 12.9797C9.25033 12.9797 9.3639 12.8663 9.36647 12.727L9.47359 10.6875L9.36645 5.60622C9.36408 5.46706 9.2503 5.3535 9.11264 5.3535C8.97477 5.3535 8.861 5.46686 8.85883 5.60644L8.76312 10.6858C8.76312 10.689 8.85883 12.7282 8.85883 12.7282C8.86102 12.8663 8.975 12.9797 9.11266 12.9797ZM7.43195 12.9717C7.55177 12.9717 7.64833 12.8756 7.65156 12.753V12.7545L7.78189 10.6866L7.65134 6.25515C7.64811 6.13254 7.55155 6.03663 7.43173 6.03663C7.31106 6.03663 7.2145 6.13256 7.21192 6.25515L7.096 10.6868L7.21214 12.7543C7.21472 12.8756 7.31128 12.9717 7.43195 12.9717ZM3.34226 12.9524C3.41346 12.9524 3.47111 12.8954 3.47649 12.8203L3.66534 10.6843L3.47649 8.65231C3.47133 8.57726 3.4137 8.52067 3.34226 8.52067C3.27001 8.52067 3.21236 8.57766 3.20785 8.65318L3.04157 10.6843L3.20785 12.8201C3.21258 12.8954 3.26999 12.9524 3.34226 12.9524ZM1.75148 12.8354C1.80352 12.8354 1.84568 12.7943 1.8517 12.7375L2.06377 10.6838L1.8519 8.55272C1.84566 8.49573 1.8035 8.45463 1.75146 8.45463C1.69876 8.45463 1.6566 8.49594 1.65122 8.55272L1.46453 10.6838L1.65122 12.7371C1.6564 12.794 1.69855 12.8354 1.75148 12.8354ZM8.26909 6.18137C8.13897 6.18137 8.03463 6.28484 8.03206 6.41733L7.92625 10.687L8.03206 12.7416C8.03465 12.8723 8.13874 12.9758 8.26909 12.9758C8.399 12.9758 8.50269 12.8726 8.50568 12.7401V12.7418L8.62441 10.6873L8.50568 6.41689C8.50267 6.28484 8.39898 6.18137 8.26909 6.18137ZM4.95886 12.9741C5.04877 12.9741 5.12298 12.9007 5.12729 12.8072L5.29248 10.6857L5.12729 6.6253C5.12276 6.53151 5.04877 6.4584 4.95886 6.4584C4.86811 6.4584 4.79412 6.53173 4.79024 6.6253L4.64399 10.6857L4.79046 12.808C4.7941 12.9007 4.86809 12.9741 4.95886 12.9741ZM6.80347 12.7738V12.7727L6.94542 10.6864L6.80347 6.13857C6.80001 6.02566 6.71097 5.93704 6.60106 5.93704C6.49049 5.93704 6.40145 6.02544 6.39846 6.13857L6.27242 10.6862L6.39866 12.7736C6.40167 12.8855 6.49073 12.9738 6.60107 12.9738C6.71119 12.9738 6.79981 12.8855 6.80349 12.773V12.7738H6.80347ZM17.5402 8.0649C17.2032 8.0649 16.8816 8.13308 16.5887 8.25589C16.3929 6.03877 14.5337 4.29956 12.2658 4.29956C11.7109 4.29956 11.17 4.40883 10.6923 4.59358C10.5066 4.66543 10.4572 4.73941 10.4555 4.88288V12.6906C10.4574 12.8411 10.5742 12.9665 10.7211 12.9814C10.7273 12.982 17.4962 12.9855 17.5401 12.9855C18.8985 12.9855 20 11.884 20 10.5253C20 9.16657 18.8987 8.0649 17.5402 8.0649ZM9.95257 4.876C9.8048 4.876 9.68351 4.99732 9.68134 5.14636L9.57036 10.6881L9.68157 12.6994C9.68351 12.8463 9.80482 12.9674 9.95257 12.9674C10.0999 12.9674 10.2212 12.8463 10.2234 12.6973V12.6996L10.344 10.6881L10.2234 5.14594C10.2212 4.99732 10.0999 4.876 9.95257 4.876ZM1.98657 14.5494C1.69556 14.481 1.61683 14.4444 1.61683 14.3291C1.61683 14.2478 1.68243 14.1637 1.87902 14.1637C2.047 14.1637 2.17822 14.2319 2.2961 14.3526L2.56088 14.0958C2.38774 13.9149 2.17801 13.8073 1.89238 13.8073C1.53017 13.8073 1.23657 14.0119 1.23657 14.3451C1.23657 14.7068 1.47276 14.8144 1.81088 14.8929C2.15694 14.9714 2.21997 15.0241 2.21997 15.1422C2.21997 15.2811 2.11737 15.3415 1.90012 15.3415C1.7244 15.3415 1.55921 15.2813 1.4306 15.1314L1.16578 15.3676C1.30472 15.5721 1.57251 15.7006 1.879 15.7006C2.38017 15.7006 2.6004 15.4646 2.6004 15.1159C2.60042 14.7172 2.27779 14.6176 1.98657 14.5494ZM3.67585 13.8073C3.17513 13.8073 2.87895 14.1954 2.87895 14.7539C2.87895 15.3125 3.17511 15.7003 3.67585 15.7003C4.17659 15.7003 4.47318 15.3125 4.47318 14.7539C4.47318 14.1954 4.17659 13.8073 3.67585 13.8073ZM3.67585 15.3362C3.37969 15.3362 3.25621 15.0817 3.25621 14.7541C3.25621 14.4264 3.37967 14.1719 3.67585 14.1719C3.97246 14.1719 4.09549 14.4264 4.09549 14.7541C4.09549 15.0817 3.97246 15.3362 3.67585 15.3362ZM5.95495 14.8669C5.95495 15.1604 5.81042 15.3415 5.57726 15.3415C5.34389 15.3415 5.20234 15.1553 5.20234 14.8617V13.8359H4.83498V14.8669C4.83498 15.4016 5.13418 15.7006 5.57724 15.7006C6.04399 15.7006 6.32209 15.3936 6.32209 14.8617V13.8359H5.95495V14.8669ZM7.90579 14.6124C7.90579 14.7148 7.91095 14.9454 7.91095 15.0136C7.8873 14.9662 7.82708 14.8746 7.78254 14.8062L7.13191 13.8357H6.78065V15.6718H7.14266V14.8639C7.14266 14.7615 7.13749 14.5309 7.13749 14.4627C7.16093 14.5098 7.22157 14.6017 7.26589 14.6701L7.93997 15.6717H8.26776V13.8357H7.90577L7.90579 14.6124ZM9.31398 13.8359H8.7397V15.6719H9.28751C9.75963 15.6719 10.2266 15.399 10.2266 14.7539C10.2266 14.0824 9.83837 13.8359 9.31398 13.8359ZM9.28751 15.3153H9.10686V14.1928H9.30087C9.6891 14.1928 9.84914 14.3788 9.84914 14.7539C9.84912 15.0897 9.67318 15.3153 9.28751 15.3153ZM11.3621 14.1717C11.5272 14.1717 11.6322 14.2453 11.6978 14.3711L12.0387 14.2139C11.9232 13.9753 11.7133 13.8073 11.3672 13.8073C10.8877 13.8073 10.5518 14.1954 10.5518 14.7539C10.5518 15.3334 10.8742 15.7003 11.354 15.7003C11.6872 15.7003 11.9075 15.5457 12.0333 15.2861L11.724 15.1024C11.627 15.2652 11.5298 15.336 11.3646 15.336C11.0893 15.336 10.9293 15.0841 10.9293 14.7539C10.9291 14.4132 11.0869 14.1717 11.3621 14.1717ZM12.7238 13.8359H12.3566V15.6719H13.46V15.3097H12.7238V13.8359ZM14.4273 13.8073C13.9261 13.8073 13.6299 14.1954 13.6299 14.7539C13.6299 15.3125 13.9261 15.7003 14.4273 15.7003C14.928 15.7003 15.2244 15.3125 15.2244 14.7539C15.2244 14.1954 14.928 13.8073 14.4273 13.8073ZM14.4273 15.3362C14.1307 15.3362 14.0076 15.0817 14.0076 14.7541C14.0076 14.4264 14.1307 14.1719 14.4273 14.1719C14.7232 14.1719 14.8465 14.4264 14.8465 14.7541C14.8465 15.0817 14.7232 15.3362 14.4273 15.3362ZM16.7059 14.8669C16.7059 15.1604 16.562 15.3415 16.3287 15.3415C16.0949 15.3415 15.9535 15.1553 15.9535 14.8617V13.8359H15.5864V14.8669C15.5864 15.4016 15.8854 15.7006 16.3287 15.7006C16.7954 15.7006 17.0733 15.3936 17.0733 14.8617V13.8359H16.7059V14.8669ZM18.1064 13.8359H17.5323V15.6719H18.0801C18.5524 15.6719 19.019 15.399 19.019 14.7539C19.019 14.0824 18.631 13.8359 18.1064 13.8359ZM18.0801 15.3153H17.8992V14.1928H18.0935C18.4812 14.1928 18.6413 14.3788 18.6413 14.7539C18.6413 15.0897 18.4655 15.3153 18.0801 15.3153Z" /></g><defs><clipPath id="clip0_3402_895"><rect width="28" height="28" fill="white"/></clipPath></defs></svg>',
			),
			'tumblr'     => array(
				'key'  => 'tumblr',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 20 20"><g clip-path="url(#clip0_3402_907)"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 0V20H20V0H0ZM14.2212 16.1534C13.7059 16.4291 13.1706 16.6274 12.6139 16.7473C12.1399 16.8658 11.6258 16.9231 11.0693 16.9231C10.5899 16.9231 10.0386 16.8458 9.40475 16.69C8.88936 16.4916 8.49393 16.2533 8.21824 15.9776C7.85873 15.654 7.62035 15.3424 7.50585 15.0255C7.3447 14.6247 7.26615 14.1506 7.26615 13.5993V8.78294H5.77873V6.82273C6.25675 6.66031 6.70949 6.40598 7.14624 6.04642C7.58308 5.69354 7.90127 5.29807 8.09833 4.86127C8.25415 4.50176 8.41525 3.90923 8.5724 3.07692H10.4606V6.65099H13.6592V8.78294H10.4606V12.2891C10.4606 13.0854 10.5325 13.5994 10.6524 13.8391C10.6936 14.0362 10.8348 14.2133 11.0692 14.3744C11.4687 14.5675 11.8229 14.666 12.1398 14.666C12.8109 14.666 13.5087 14.4316 14.2211 13.9536V16.1534H14.2212Z" /></g><defs><clipPath id="clip0_3402_907"><rect width="20" height="20" fill="white"/></clipPath></defs></svg>',
			),
			'wikipedia'  => array(
				'key'  => 'wikipedia',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 20 20"><path d="M15.1358 3.73584V4.07005C14.6949 4.14835 14.3613 4.28685 14.1347 4.48556C13.8102 4.78064 13.4276 5.23227 13.1766 5.84047L8.06951 16.2642H7.72971L2.60269 5.70498C2.36391 5.16302 2.03929 4.83182 1.94133 4.71138C1.78827 4.52471 1.6 4.37868 1.37653 4.27329C1.15305 4.16792 0.85152 4.10017 0.471924 4.07005V3.73584H5.46066V4.07005C4.88513 4.12426 4.61075 4.22061 4.43932 4.3591C4.26789 4.49761 4.18217 4.67526 4.18218 4.89203C4.18217 5.19313 4.32299 5.66283 4.60463 6.30114L8.39094 13.4822L12.0929 6.39146C12.3807 5.69294 12.6194 5.20819 12.6194 4.93719C12.6194 4.76257 12.5306 4.59547 12.3531 4.43588C12.1755 4.27631 11.9746 4.1634 11.5522 4.09715C11.5215 4.09114 11.4695 4.08211 11.396 4.07005V3.73584H15.1358Z" /><path d="M19.5281 3.73584V4.07005C19.0873 4.14835 18.7536 4.28685 18.5271 4.48556C18.2026 4.78064 17.82 5.23227 17.569 5.84047L13.0869 16.2642H12.7471L8.01068 5.70498C7.7719 5.16302 7.44728 4.83182 7.34932 4.71138C7.19625 4.52471 7.00799 4.37868 6.78452 4.27329C6.56104 4.16792 6.3586 4.10017 5.979 4.07005V3.73584H10.8686V4.07005C10.2931 4.12426 10.0187 4.22061 9.84731 4.3591C9.67588 4.49761 9.59016 4.67526 9.59017 4.89203C9.59016 5.19313 9.73098 5.66283 10.0126 6.30114L13.4083 13.4822L16.4853 6.39146C16.773 5.69294 17.0118 5.20819 17.0118 4.93719C17.0118 4.76257 16.923 4.59547 16.7455 4.43588C16.5679 4.27631 16.2679 4.1634 15.8454 4.09715C15.8148 4.09114 15.7628 4.08211 15.6893 4.07005V3.73584H19.5281Z" /></svg>',
			),
		);

		$output = array();

		foreach ( $options as $social => $settings ) {

			$url = get_user_meta( get_the_author_meta( 'ID' ), $settings['key'], true );

			if ( 'twitter' === $social && $url ) {
				$url = sprintf( 'https://twitter.com/%s/', $url );
			}

			if ( $url && ! empty( $settings['icon'] ) ) {
				$output[] = '<a href="' . esc_url_raw( $url ) . '">' . $settings['icon'] . '<span class="screen-reader-text">' . $social . '</span></a>';
			}
		}

		if ( ! empty( $output ) ) {
			call_user_func( 'printf', '%s', '<div class="es-page__author-social-links">' . join( ' ', $output ) . '</div>' );
		}
	}
}

if ( ! function_exists( 'esn_get_read_next_post_ids' ) ) {
	/**
	 * Retrieve read next posts.
	 *
	 * Will retrive the newer posts from the current category,
	 * if the limit is not reached it will add older posts from current category,
	 * if the limit still not reached it will add posts from other categories.
	 *
	 * @param int $limit Optional. Number of posts to return.
	 * @param int $read_next_posts Optional. Post sorting.
	 */
	function esn_get_read_next_post_ids( $limit = 4, $read_next_posts = 'after' ) {

		$post_ids                = array();
		$current_post_id         = get_the_ID();
		$exclude_post_ids        = array( $current_post_id );
		$current_post_categories = wp_get_post_categories( $current_post_id, array( 'fields' => 'ids' ) );

		$order            = 'ASC';
		$date_query_param = 'after';

		if ( 'before' === $read_next_posts ) {
			$order            = 'DESC';
			$date_query_param = 'before';
		}

		if ( 'new' === $read_next_posts ) {
			$order            = 'DESC';
			$date_query_param = 'after';
		}

		$args = array(
			'post_type'           => 'post',
			'posts_per_page'      => $limit,
			'post_status'         => 'publish',
			'orderby'             => 'date',
			'order'               => $order,
			'date_query'          => array(
				$date_query_param => get_the_time( 'Y-m-d H:i:s', $current_post_id ),
			),
			'ignore_sticky_posts' => true,
			'cat'                 => $current_post_categories,
		);

		$next_posts_query = new WP_Query( $args );

		if ( $next_posts_query->have_posts() ) {
			$post_ids         = wp_list_pluck( $next_posts_query->posts, 'ID' );
			$exclude_post_ids = array_merge( $exclude_post_ids, $post_ids );
		}

		$posts_count = count( $post_ids );

		if ( $posts_count < $limit ) {
			$posts_to_fetch = $limit - $posts_count;

			$category = get_the_category( $current_post_id );

			if ( isset( $category[0] ) ) {
				$category_id = $category[0]->term_id;

				$args_category = array(
					'post_type'           => 'post',
					'posts_per_page'      => $posts_to_fetch,
					'cat'                 => $category_id,
					'post_status'         => 'publish',
					'orderby'             => 'date',
					'order'               => $order,
					'ignore_sticky_posts' => true,
					'cat'                 => $current_post_categories,
					'post__not_in'        => $exclude_post_ids,
				);

				$category_query = new WP_Query( $args_category );

				if ( $category_query->have_posts() ) {
					$category_post_ids = wp_list_pluck( $category_query->posts, 'ID' );
					$post_ids          = array_merge( $post_ids, $category_post_ids );
					$exclude_post_ids  = array_merge( $exclude_post_ids, $post_ids );
				}
			}
		}

		$posts_count = count( $post_ids );

		if ( $posts_count < $limit ) {
			$posts_to_fetch = $limit - $posts_count;

			$args_additional = array(
				'post_type'           => 'post',
				'posts_per_page'      => $posts_to_fetch,
				'post_status'         => 'publish',
				'orderby'             => 'date',
				'ignore_sticky_posts' => true,
				'post__not_in'        => $exclude_post_ids,
			);

			$additional_posts_query = new WP_Query( $args_additional );

			if ( $additional_posts_query->have_posts() ) {
				$additional_post_ids = wp_list_pluck( $additional_posts_query->posts, 'ID' );
				$post_ids            = array_merge( $post_ids, $additional_post_ids );
			}
		}

		wp_reset_postdata();

		return $post_ids;
	}
}

if ( ! function_exists( 'esn_calculate_post_reading_time' ) ) {
	/**
	 * Calculate Post Reading Time in Minutes
	 *
	 * @param int $post_id The post ID.
	 */
	function esn_calculate_post_reading_time( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$post_content     = get_post_field( 'post_content', $post_id );
		$strip_shortcodes = strip_shortcodes( $post_content );
		$strip_tags       = wp_strip_all_tags( $strip_shortcodes );

		$str        = preg_replace( '/[[:punct:]]/', '', $strip_tags );
		$str        = preg_replace( '/[\s]+/', ' ', $str );
		$word_count = count( (array) array_filter( (array) explode( ' ', $str ) ) );

		$reading_time = intval( ceil( $word_count / 265 ) );

		// Filter for Reading Time.
		if ( function_exists( 'iconv_strlen' ) ) {
			$reading_time = apply_filters( 'esn_calculate_reading_time', $reading_time, iconv_strlen( $strip_tags ) );
		} else {
			$reading_time = apply_filters( 'esn_calculate_reading_time', $reading_time, mb_strlen( $strip_tags ) );
		}

		return $reading_time;
	}
}

if ( ! function_exists( 'esn_get_post_reading_time' ) ) {
	/**
	 * Get Post Reading Time from Post Meta
	 *
	 * @param int $post_id The post ID.
	 */
	function esn_get_post_reading_time( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		// Get existing post meta.
		$reading_time = get_post_meta( $post_id, '_esn_reading_time', true );

		// Calculate and save reading time, if there's no existing post meta.
		if ( ! $reading_time ) {
			$reading_time = esn_calculate_post_reading_time( $post_id );
			update_post_meta( $post_id, '_esn_reading_time', $reading_time );
		}

		return $reading_time;
	}
}

if ( ! function_exists( 'esn_detect_color_scheme' ) ) {
	/**
	 * Detect color scheme.
	 *
	 * @param mixed $color Color.
	 * @param int   $level Detect level.
	 */
	function esn_detect_color_scheme( $color, $level = 190 ) {

		// Trim color.
		$color = trim( $color );

		// Excludes.
		if ( in_array( $color, array( '#0e131a' ), true ) ) {
			return 'dark';
		}

		// Set alpha channel.
		$alpha = 1;

		$rgba = array( 255, 255, 255 );

		// If HEX format.
		if ( isset( $color[0] ) && '#' === $color[0] ) {
			// Remove '#' from start.
			$color = str_replace( '#', '', trim( $color ) );

			if ( 3 === strlen( $color ) ) {
				$color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
			}

			$rgba[0] = hexdec( substr( $color, 0, 2 ) );
			$rgba[1] = hexdec( substr( $color, 2, 2 ) );
			$rgba[2] = hexdec( substr( $color, 4, 2 ) );

		} elseif ( preg_match_all( '#\((([^()]+|(?R))*)\)#', $color, $color_reg ) ) {
			// Convert RGB or RGBA.
			$rgba = explode( ',', implode( ' ', $color_reg[1] ) );

			if ( array_key_exists( '3', $rgba ) ) {
				$alpha = (float) $rgba['3'];
			}
		}

		// Apply alpha channel.
		foreach ( $rgba as $key => $channel ) {
			$rgba[ $key ] = str_pad( $channel + ceil( ( 255 - $channel ) * ( 1 - $alpha ) ), 2, '0', STR_PAD_LEFT );
		}

		// Set default scheme.
		$scheme = 'light';

		// Get brightness.
		$brightness = ( ( $rgba[0] * 299 ) + ( $rgba[1] * 587 ) + ( $rgba[2] * 114 ) ) / 1000;

		// If color gray.
		if ( $rgba[0] === $rgba[1] && $rgba[1] === $rgba[2] ) {
			if ( $brightness < $level ) {
				$scheme = 'dark';
			}
		} elseif ( $brightness < $level ) {
				$scheme = 'inverse';
		}

		return $scheme;
	}
}

if ( ! function_exists( 'esn_color_scheme' ) ) {
	/**
	 * Output color scheme.
	 *
	 * @param mixed $light Light color.
	 * @param mixed $dark  Dark color.
	 */
	function esn_color_scheme( $light, $dark = '' ) {

		$data = esn_site_scheme_data();

		$site_scheme = esn_site_scheme_data();

		if ( 'auto' === $site_scheme ) {
			return sprintf( 'data-scheme="auto" data-l="%s" data-d="%s"', esn_detect_color_scheme( $light ), esn_detect_color_scheme( $dark ) );
		}

		if ( 'dark' === $site_scheme ) {
			$scheme = esn_detect_color_scheme( $dark );
		} else {
			$scheme = esn_detect_color_scheme( $light );
		}

		return sprintf( 'data-scheme="%s"', $scheme );
	}
}

if ( ! function_exists( 'esn_header_scheme_attr' ) ) {
	/**
	 * Get Site Header scheme attribute
	 *
	 * @param bool $is_echo Optional. Whether to echo or return the classes. Default true for echo.
	 */
	function esn_header_scheme_attr( $is_echo = false ) {

		$scheme = esn_color_scheme(
			get_theme_mod( 'color_header_background', '#FFFFFF' ),
			get_theme_mod( 'color_header_background_is_dark', '#161616' )
		);

		$scheme_attr = apply_filters( 'esn_header_scheme_attr', $scheme );

		if ( $is_echo ) {
			echo wp_kses( $scheme_attr, 'esn' );
		} else {
			return $scheme_attr;
		}
	}
}

if ( ! function_exists( 'esn_header_attr' ) ) {
	/**
	 * Get Site Header attributes
	 */
	function esn_header_attr() {
		$attributes = esn_header_scheme_attr();

		echo wp_kses( apply_filters( 'esn_header_attr', $attributes ), 'esn' );
	}
}

if ( ! function_exists( 'esn_footer_scheme_attr' ) ) {
	/**
	 * Get Site Footer scheme attribute
	 *
	 * @param bool $is_echo Optional. Whether to echo or return the classes. Default false for echo.
	 */
	function esn_footer_scheme_attr( $is_echo = false ) {
		$scheme = esn_color_scheme(
			get_theme_mod( 'color_footer_background', '#FFFFFF' ),
			get_theme_mod( 'color_footer_background_dark', '#161616' )
		);

		$scheme_attr = apply_filters( 'esn_footer_scheme_attr', $scheme );
		if ( $is_echo ) {
			echo wp_kses( $scheme_attr, 'esn' );
		} else {
			return $scheme_attr;
		}
	}
}

if ( ! function_exists( 'esn_footer_attr' ) ) {
	/**
	 * Get Site Footer attributes
	 */
	function esn_footer_attr() {
		$attributes = esn_footer_scheme_attr();

		echo wp_kses( apply_filters( 'esn_footer_attr', $attributes ), 'esn' );
	}
}

if ( ! function_exists( 'esn_offcanvas_scheme_attr' ) ) {
	/**
	 * Get Site Offcanvas scheme attribute
	 *
	 * @param bool $is_echo Optional. Whether to echo or return the classes. Default true for echo.
	 */
	function esn_offcanvas_scheme_attr( $is_echo = false ) {

		$scheme = esn_color_scheme(
			get_theme_mod( 'color_offcanvas_background', '#FFFFFF' ),
			get_theme_mod( 'color_offcanvas_background_is_dark', '#161616' )
		);

		$scheme_attr = apply_filters( 'esn_offcanvas_scheme_attr', $scheme );

		if ( $is_echo ) {
			echo wp_kses( $scheme_attr, 'esn' );
		} else {
			return $scheme_attr;
		}
	}
}

if ( ! function_exists( 'esn_offcanvas_attr' ) ) {
	/**
	 * Get Site Offcanvas attributes
	 */
	function esn_offcanvas_attr() {
		$attributes = esn_offcanvas_scheme_attr();

		echo wp_kses( apply_filters( 'esn_offcanvas_attr', $attributes ), 'esn' );
	}
}

/**
 * Add Fields to User Profile.
 *
 * @param object $user The user details.
 */
function add_esn_user_profile_fields( $user ) {
	?>
	<h1 style="margin-top: 20px; font-weight: 700;"><?php echo esc_html__( 'Дополнительная информация', 'esenin' ); ?></h1>
	<table class="form-table">
		<tr>
			<th><label for="position"><?php echo esc_html__( 'Увлечение', 'esenin' ); ?></label></th>
			<td>
				<input type="text" name="esn_position" id="esn_position" value="<?php echo esc_attr( get_the_author_meta( 'esn_position', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="location"><?php echo esc_html__( 'Город', 'esenin' ); ?></label></th>
			<td>
				<input type="text" name="esn_location" id="esn_location" value="<?php echo esc_attr( get_the_author_meta( 'esn_location', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
		<?php wp_nonce_field( 'esn_user_profile_nonce', 'esn_user_profile_nonce_field' ); ?>
	</table>
	<h3><?php echo esc_html__( 'Социальные сети', 'esenin' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="position"><?php echo esc_html__( 'Вконтакте', 'esenin' ); ?></label></th>
			<td>
				<input type="text" name="esn_vk" id="esn_vk" value="<?php echo esc_attr( get_the_author_meta( 'esn_vk', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="location"><?php echo esc_html__( 'Telegram', 'esenin' ); ?></label></th>
			<td>
				<input type="text" name="esn_telegram" id="esn_telegram" value="<?php echo esc_attr( get_the_author_meta( 'esn_telegram', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="position"><?php echo esc_html__( 'Instagram', 'esenin' ); ?></label></th>
			<td>
				<input type="text" name="esn_instagram" id="esn_instagram" value="<?php echo esc_attr( get_the_author_meta( 'esn_instagram', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="location"><?php echo esc_html__( 'TikTok', 'esenin' ); ?></label></th>
			<td>
				<input type="text" name="esn_tiktok" id="esn_tiktok" value="<?php echo esc_attr( get_the_author_meta( 'esn_tiktok', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="position"><?php echo esc_html__( 'GitHub', 'esenin' ); ?></label></th>
			<td>
				<input type="text" name="esn_github" id="esn_github" value="<?php echo esc_attr( get_the_author_meta( 'esn_github', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="location"><?php echo esc_html__( 'YouTube', 'esenin' ); ?></label></th>
			<td>
				<input type="text" name="esn_youtube" id="esn_youtube" value="<?php echo esc_attr( get_the_author_meta( 'esn_youtube', $user->ID ) ); ?>" class="regular-text" />
			</td>
		</tr>
		<?php wp_nonce_field( 'esn_user_profile_nonce', 'esn_user_profile_nonce_field' ); ?>
	</table>
	<?php
}
add_action( 'show_user_profile', 'add_esn_user_profile_fields' );
add_action( 'edit_user_profile', 'add_esn_user_profile_fields' );


/**
 * Save Custom Fields from User Profile.
 *
 * @param int $user_id User ID.
 */
function save_esn_user_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) || ! isset( $_POST['esn_user_profile_nonce_field'] ) || ! wp_verify_nonce( $_POST['esn_user_profile_nonce_field'], 'esn_user_profile_nonce' ) ) {
		return false;
	}

	if ( isset( $_POST['esn_position'] ) ) {
		$position = sanitize_text_field( wp_unslash( $_POST['esn_position'] ) );
		update_user_meta( $user_id, 'esn_position', $position );
	}

	if ( isset( $_POST['esn_location'] ) ) {
		$location = sanitize_text_field( wp_unslash( $_POST['esn_location'] ) );
		update_user_meta( $user_id, 'esn_location', $location );
	}
	/*
 * Social links.
 */
	if ( isset( $_POST['esn_vk'] ) ) {
		$vk = sanitize_text_field( wp_unslash( $_POST['esn_vk'] ) );
		update_user_meta( $user_id, 'esn_vk', $vk );
	}
	if ( isset( $_POST['esn_telegram'] ) ) {
		$telegram = sanitize_text_field( wp_unslash( $_POST['esn_telegram'] ) );
		update_user_meta( $user_id, 'esn_telegram', $telegram );
	}
	if ( isset( $_POST['esn_instagram'] ) ) {
		$instagram = sanitize_text_field( wp_unslash( $_POST['esn_instagram'] ) );
		update_user_meta( $user_id, 'esn_instagram', $instagram );
	}
	if ( isset( $_POST['esn_tiktok'] ) ) {
		$tiktok = sanitize_text_field( wp_unslash( $_POST['esn_tiktok'] ) );
		update_user_meta( $user_id, 'esn_tiktok', $tiktok );
	}
	if ( isset( $_POST['esn_github'] ) ) {
		$github = sanitize_text_field( wp_unslash( $_POST['esn_github'] ) );
		update_user_meta( $user_id, 'esn_github', $github );
	}
	if ( isset( $_POST['esn_youtube'] ) ) {
		$youtube = sanitize_text_field( wp_unslash( $_POST['esn_youtube'] ) );
		update_user_meta( $user_id, 'esn_youtube', $youtube );
	}
}

add_action( 'personal_options_update', 'save_esn_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_esn_user_profile_fields' );

//// Feed Comments (Esenin) ////

if ( ! function_exists( 'esn_comments_callback' ) ) {
    function esn_comments_callback( $comment, $args, $depth ) {
        $tag               = ( 'div' === $args['style'] ) ? 'div' : 'li';
        $post_author_id    = get_post_field( 'post_author', $comment->comment_post_ID );
        $is_by_post_author = ( $comment->user_id === $post_author_id );
        $has_children      = ! empty( $args['has_children'] );
        $comment_author_id = $comment->user_id;
        $unique_id = uniqid("comment-");		
        $is_deleted = get_comment_meta($comment->comment_ID, 'deleted', true);
		$parent_comment_id = $comment->comment_parent;
		$parent_comment = get_comment($parent_comment_id);
		
        ?>
        <<?php echo esc_attr( $tag ); ?> <?php comment_class( $has_children ? 'parent' : '', $comment ); ?> id="<?php echo esc_attr( $unique_id ); ?>">          		
			<article id="div-<?php echo esc_attr( get_comment_ID() ); ?>" class="comment-body">
                <div class="comment-meta">
                    <div class="comment-author vcard">
                        <?php
                        if ( 0 !== $args['avatar_size'] ) {
                            echo get_avatar( $comment, $args['avatar_size'] );
                        }
                        echo '<div class="flex-grow-1">';
						echo '<div class="d-flex align-items-center">';
                        if ( $comment_author_id ) {
                            $author_url         = get_author_posts_url( $comment_author_id );
                            $author_posts_count = count_user_posts( $comment_author_id );
                            $author_label = $is_by_post_author ? ' <span title="' . __('Автор этого поста', 'esenin') . '" class="author-label">' . __('Автор', 'esenin') . '</span>' : '';
                            $reply_comm = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17.71,9.88l-4.3-4.29a2,2,0,0,0-2.82,0L6.29,9.88a1,1,0,0,0,0,1.41,1,1,0,0,0,1.42,0L11,8V19a1,1,0,0,0,2,0V8l3.29,3.29a1,1,0,1,0,1.42-1.41Z"/></svg>';                            													
							
							if ( $author_posts_count >= 0 ) {
                                ?>
                                <b class="fn">
                                    <a href="<?php echo esc_url( $author_url ); ?>" class="url">
                                        <?php echo esc_html( get_comment_author() ) . $author_label; ?>
                                    </a>
                                </b>
                                <?php
                            } else {
                                ?>
                                <b class="fn"><?php echo esc_html( get_comment_author() ) . $author_label; ?></b>
                                <?php
                            }
                        } else {
                            ?>
                            <b class="fn"><?php echo get_comment_author_link(); ?></b>
                            <?php
                        }
                        ?>
						
						<?php if ($parent_comment_id) : ?>
                            <div class="reply-arrow" data-parent-id="<?php echo esc_attr($parent_comment_id); ?>" title="<?php esc_html_e('ответ для ', 'esenin'); ?><?php echo get_comment_author($parent_comment); ?>" data-comment-id="<?php echo esc_attr($unique_id); ?>">
                                <?php echo $reply_comm; ?>
                            </div>
                        <?php endif; ?>
                        </div>
                        <div class="comment-metadata d-flex gap-3 align-items-center">
                        
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                            <?php echo get_comment_date('d.m.Y в H:i'); ?>
                        </a>
						
						
                        </div>
		
                       </div>
                    </div>					
                </div>

                <div class="comment-content" id="comment-<?php comment_ID(); ?>">
                    <?php if ( '0' === $comment->comment_approved ) { ?>
                        <em class="comment-awaiting-moderation"><?php esc_html_e( 'Ваш комментарий на модерации.', 'esenin' ); ?></em>
                    <?php } ?>
                     <?php if ($is_deleted) { ?>
                    <span class="comment-text"><?php esc_html_e( 'Комментарий удалён..', 'esenin' ); ?></span>
                <?php } else { ?>
                    <span class="comment-text"><?php comment_text(); ?></span>
                <?php } ?>

                    <div class="edit-comment-form" id="edit-comment-<?php comment_ID(); ?>" style="display:none;">
                        <div class="edit-comment-error" style="color: red; display: none;"></div>
                        <textarea class="edit-comment-text" rows="4" data-original-content="<?php echo esc_textarea($comment->comment_content); ?>"><?php echo esc_textarea($comment->comment_content); ?></textarea>
                        <div class="d-flex align-items-center justify-content-end mt-3 gap-2">
                            <button class="save-comment-btn" data-comment-id="<?php comment_ID(); ?>" style="display: none;"><?php _e('Сохранить', 'esenin'); ?></button>
                            <button class="cancel-edit-btn es-btn-dark"><?php _e('Отменить', 'esenin'); ?></button>
                        </div>
                    </div>
                </div>

               <?php if (!$is_deleted) : ?>
                <div class="reply d-flex align-items-center justify-content-start gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <?php if ( is_user_logged_in() ) { ?>
                            <?php echo do_shortcode('[like_comment_button id="'.esc_attr($comment->comment_ID).'"]'); ?>
                        <?php } else { ?>
                            <a href="<?php echo wp_login_url(); ?>"><?php echo do_shortcode('[like_comment_button id="'.esc_attr($comment->comment_ID).'"]'); ?></a>
                        <?php } ?>

                        <?php
                        comment_reply_link(
                            array_merge(
                                $args,
                                array(
                                    'depth'     => $depth,
                                    'max_depth' => $args['max_depth'],
                                )
                            )
                        );
                        ?>
                    </div>

                    <?php  
                        $current_user = wp_get_current_user();
                        $is_admin = user_can($current_user, 'administrator');
                        $is_guest = !is_user_logged_in();
                        $comment_time = strtotime($comment->comment_date);
                        $current_time = current_time('timestamp');
                        $time_diff = $current_time - $comment_time;

                        $edit_comment_name = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22.987,5.452c-.028-.177-.312-1.767-1.464-2.928-1.157-1.132-2.753-1.412-2.931-1.44-.237-.039-.479,.011-.682,.137-.071,.044-1.114,.697-3.173,2.438,1.059,.374,2.428,1.023,3.538,2.109,1.114,1.09,1.78,2.431,2.162,3.471,1.72-2.01,2.367-3.028,2.41-3.098,.128-.205,.178-.45,.14-.689Z"></path><path d="M12.95,5.223c-1.073,.968-2.322,2.144-3.752,3.564C3.135,14.807,1.545,17.214,1.48,17.313c-.091,.14-.146,.301-.159,.467l-.319,4.071c-.022,.292,.083,.578,.29,.785,.188,.188,.443,.293,.708,.293,.025,0,.051,0,.077-.003l4.101-.316c.165-.013,.324-.066,.463-.155,.1-.064,2.523-1.643,8.585-7.662,1.462-1.452,2.668-2.716,3.655-3.797-.151-.649-.678-2.501-2.005-3.798-1.346-1.317-3.283-1.833-3.927-1.975Z"></path></svg>';                       
                        $delete_comment_name = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18,6h0a1,1,0,0,0-1.414,0L12,10.586,7.414,6A1,1,0,0,0,6,6H6A1,1,0,0,0,6,7.414L10.586,12,6,16.586A1,1,0,0,0,6,18H6a1,1,0,0,0,1.414,0L12,13.414,16.586,18A1,1,0,0,0,18,18h0a1,1,0,0,0,0-1.414L13.414,12,18,7.414A1,1,0,0,0,18,6Z"/></svg>';
						if ($is_admin) {
							echo '<div class="d-flex gap-1">';
                            echo '<div title="' . __('Изменить', 'esenin') . '" class="edit-comment-btn admin-edit-comment" data-comment-id="' . esc_attr($comment->comment_ID) . '">' . $edit_comment_name . '</div>';
                            echo '<div title="' . __('Удалить на сайте', 'esenin') . '" class="delete-comment-btn" data-comment-id="' . esc_attr($comment->comment_ID) . '">' . $delete_comment_name . '</div>';
                            echo '</div>';
					   } elseif (is_user_logged_in() && $comment->user_id == $current_user->ID && $time_diff <= 1800) {
                            echo '<div title="' . __('Разрешено 30 минут', 'esenin') . '" class="edit-comment-btn user-edit-comment d-flex justify-content-center align-items-center" data-comment-id="' . esc_attr($comment->comment_ID) . '">';
                            echo '<div class="edit-comment-icon">' . $edit_comment_name . '</div>';
                            echo '<div class="edit-comment-label">' . esc_html__( 'Изменить', 'esenin' ) . '</div>';
                            echo '</div>';
                        }
                    ?>
                </div>
				   <?php endif; ?>
            </article>
        </<?php echo esc_attr( $tag ); ?>>        

        <?php
    }
}


function custom_get_comments_number($post_id = null) {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    $comments = get_comments(array(
        'post_id' => $post_id,
        'approve' => '',
        'count' => true,
    ));

    return $comments;
}

function enqueue_comment_edit_scripts() {
    if (is_single() && comments_open() && custom_get_comments_number() > 0) { 
        wp_enqueue_script('jquery');
        wp_enqueue_script('comments-edit', get_template_directory_uri() . '/assets/static/js/comments.js', array('jquery'), null, true);
        wp_localize_script('comments-edit', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    } 
}
add_action('wp_enqueue_scripts', 'enqueue_comment_edit_scripts');



function delete_comment_callback() {
    if ( isset($_POST['comment_id']) ) {
        $comment_id = intval($_POST['comment_id']);

        $current_user = wp_get_current_user();
        if ( user_can($current_user, 'administrator') || user_can($current_user, 'edit_comment', $comment_id) ) {
            add_comment_meta($comment_id, 'deleted', true);

          
            wp_send_json_success();
        } else {
            wp_send_json_error(array('message' => 'У вас нет прав на удаление этого комментария.'));
        }
    } else {
        wp_send_json_error(array('message' => 'Ошибка: недопустимые данные.'));
    }
}

add_action('wp_ajax_delete_comment', 'delete_comment_callback');
add_action('wp_ajax_nopriv_delete_comment', 'delete_comment_callback');

function update_comment_callback() {
    if ( isset($_POST['comment_id']) && isset($_POST['comment_content']) ) {
        $comment_id = intval($_POST['comment_id']);
        $comment_content = sanitize_text_field($_POST['comment_content']);

     
        if (strlen($comment_content) < 2) {
            wp_send_json_error(array('message' => 'Как-то совсем пусто..'));
            return; 
        }

        $current_user = wp_get_current_user();
        $is_admin = user_can($current_user, 'administrator');

        if (!$is_admin) {
            $commentdata = array(
                'comment_content' => $comment_content,
                'comment_ID' => $comment_id,
                'comment_approved' => 0, 
            );
        } else {
            $commentdata = array(
                'comment_content' => $comment_content,
                'comment_ID' => $comment_id,
            );
        }

        $result = wp_update_comment($commentdata);

        if ($result) {
            wp_send_json_success(array('comment_id' => $comment_id, 'comment_content' => $comment_content));
        } else {
            wp_send_json_error(array('message' => 'Ошибка при обновлении.'));
        }
    } else {
        wp_send_json_error(array('message' => 'Ошибка: недопустимые данные.'));
    }
}

add_action('wp_ajax_update_comment', 'update_comment_callback');
add_action('wp_ajax_nopriv_update_comment', 'update_comment_callback');


// Добавляем в консоли для'deleted' пометку
add_action('comment_post', 'mark_comment_as_deleted', 10, 2);
function mark_comment_as_deleted($comment_ID, $comment_approved) {
    if ($comment_approved == 'spam') { 
        update_comment_meta($comment_ID, 'deleted', true);
    }
}

add_action('admin_footer', 'highlight_deleted_comments');
function highlight_deleted_comments() {
    echo '<style>
        .deleted-comment { background-color: #f8d7da !important; }
    </style>';
}

add_filter('comment_row_actions', 'add_deleted_class', 10, 2);
function add_deleted_class($actions, $comment) {
    if (get_comment_meta($comment->comment_ID, 'deleted', true)) {
        unset($actions['edit']); 
       
    }
    return $actions;
}

add_filter('comment_class', 'highlight_deleted_comment_class', 10, 2);
function highlight_deleted_comment_class($classes, $comment_ID) {
    if (get_comment_meta($comment_ID, 'deleted', true)) {
        $classes[] = 'deleted-comment'; 
    }
    return $classes;
}



add_filter('comment_text', 'add_deleted_notice_to_comment_text', 10, 2);
function add_deleted_notice_to_comment_text($comment_text, $comment = null) {
    // 1. Проверяем, что $comment — это вообще объект
    if ( ! is_object( $comment ) ) {
        return $comment_text;
    }

    // 2. Берем ID безопасно
    $comment_id = isset($comment->comment_ID) ? $comment->comment_ID : 0;

    if ($comment_id && get_comment_meta($comment_id, 'deleted', true)) {
        $comment_text .= ' <div style="display: inline-flex; margin: 2px auto; color: #ff2056; font-weight: 400; font-size: 14px;background: #ffccd3;padding: 2px 6px 4px; border-radius: 4px; line-height: normal;">комментарий удалён в ленте</div>';
    }
    return $comment_text;
}


/**
	 * State Auto Load Next Post.
 */

 if ( ! function_exists( 'esn_get_state_load_nextpost' ) ) {
	
	function esn_get_state_load_nextpost() {

		if ( is_singular( 'post' ) ) {
			$page_load_nextpost = get_post_meta( get_queried_object_id(), 'esn_page_load_nextpost', true );

			if ( ! $page_load_nextpost || 'default' === $page_load_nextpost ) {

				return apply_filters( 'esn_page_load_nextpost', get_theme_mod( 'post_load_nextpost', false ) );
			}

			$page_load_nextpost = 'enabled' === $page_load_nextpost ? true : false;

			return apply_filters( 'esn_page_load_nextpost', $page_load_nextpost );
		}

		return apply_filters( 'esn_page_load_nextpost', false );
	}
} 


add_action( 'wp_enqueue_scripts', 'my_load_scripts' );
	
	function my_load_scripts() {

		if( !is_admin() ) {
			wp_deregister_script( 'jquery-core' ); 
			wp_register_script( 'jquery-core', '//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', array(), null, true ); 
			add_filter( 'script_loader_src', 'jquery_local_loader', 10, 2 ); 
			wp_enqueue_script( 'jquery' );
		}

	}


	add_action( 'wp_head', 'jquery_local_loader' );

	function jquery_local_loader( $src, $handle = null ) {

		static $add_jquery_fallback = false;

		if( $add_jquery_fallback ) {
			echo '<script>window.jQuery || document.write(\'<script src="' . includes_url() . 'js/jquery/jquery.js"><\/script>\')</script>' . "\n";
			$add_jquery_fallback = false;
		}

		if( $handle === 'jquery-core' ) {
			$add_jquery_fallback = true;
		}

		return $src;

	}


	function port_remove_cssjs_ver( $src ) {
		if( strpos( $src, '?ver=' ) )
		$src = remove_query_arg( 'ver', $src );
		return $src;
	}

	add_filter( 'script_loader_src', 'port_remove_cssjs_ver', 10, 2 );
	
	
/**
 * Required and Recommended Plugins
 */
function esn_register_plugins() {
    $plugins = array(
        array(
            'name'     => 'Front EditorJS',
            'slug'     => 'front-editorjs',
            'required' => true,
            'source'   => get_template_directory_uri() . '/core/plugins/front-editorjs.zip',
        ),
		array(
            'name'     => 'Related Linking',
            'slug'     => 'related-linking',
            'required' => false,
            'source'   => get_template_directory_uri() . '/core/plugins/related-linking.zip',
        ),
		array(
            'name'     => 'Better Messages',
            'slug'     => 'bp-better-messages',
            'required' => false,
        ),
    );

    $config = array(
        'id'           => 'esn',
        'default_path' => '',
        'menu'         => 'esn-install-plugins',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
    );

    tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'esn_register_plugins' );

// Функция для активации плагина и перенаправления
function esn_activate_plugin_and_redirect() {
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'esn-install-plugins' &&
         isset( $_GET['plugin'] ) && $_GET['plugin'] === 'front-editorjs' &&
         isset( $_GET['tgmpa-activate'] ) && $_GET['tgmpa-activate'] === 'activate-plugin' ) {

        $plugin_slug = 'front-editorjs';
        $plugin_path = 'front-editorjs/front_editorjs.php';

        if ( ! is_plugin_active( $plugin_path ) ) {
            ob_start();
            $result = activate_plugin( $plugin_path );
            ob_end_clean(); 

            if ( ! is_wp_error( $result ) ) {
                wp_redirect( admin_url() );
                exit;
            }
        } 

        wp_redirect( admin_url() );
        exit;
    }
}
add_action( 'admin_init', 'esn_activate_plugin_and_redirect' );

// Добавляем хук для скрытия уведомлений
add_action('admin_init', function() {
    if (is_plugin_active('front-editorjs/front_editorjs.php')) {
        add_filter('admin_footer', function() {
            echo '<style>
                .wrap .notice.error,
                .wrap #message.notice {
                    display: none !important;
                }
            </style>';
        });
    }
});


/**
 * Хуки для Better Messages
 */
function setup_personal_messages_page() {
    if (is_plugin_active('bp-better-messages/bp-better-messages.php')) {
        $page_title = __('Личные сообщения', 'esenin');
        $page_slug = 'pm';

        $page_check = get_page_by_path($page_slug);

        if (!isset($page_check->ID)) {
            $new_page_id = wp_insert_post(array(
                'post_title' => $page_title,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $page_slug,
                'meta_input' => array(
                    '_wp_page_template' => 'pages/messages.php' 
                ),
            ));
        }
    }
}
add_action('init', 'setup_personal_messages_page');

function delete_personal_messages_page() {
    $page_slug = 'pm';
    $page = get_page_by_path($page_slug);

    if ($page) {
        wp_delete_post($page->ID, true);
    }
}
add_action('deactivate_bp-better-messages/bp-better-messages.php', 'delete_personal_messages_page');

function enqueue_better_messages_custom_styles() {
    if (is_plugin_active('bp-better-messages/bp-better-messages.php')) {
        wp_enqueue_style('custom-bm-styles', get_template_directory_uri() . '/assets/css/bm-styles.css');
    }
}
add_action('wp_enqueue_scripts', 'enqueue_better_messages_custom_styles');

add_filter('nav_menu_item_title', 'add_shortcode_to_menu_item', 10, 4);
function add_shortcode_to_menu_item($title, $item, $args, $depth) {
    if ($depth === 0) {
        $url = $item->url;
        $slug = isset($item->slug) ? $item->slug : '';

        if (strpos($url, 'pm') !== false || $slug === 'pm') {
            return $title . do_shortcode('[better_messages_unread_counter hide_when_no_messages="1" preserve_space="1"]');
        }
    }

    return $title;
}