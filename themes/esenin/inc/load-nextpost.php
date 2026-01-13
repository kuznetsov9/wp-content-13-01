<?php
/**
 * Load Load Next Post via AJAX.
 *
 * @package Esenin
 */

/**
 * Retrieve next post that is adjacent to current post.
 */
function esn_nextpost_get_id() {
	global $post;

	$next_post = false;

	$in_same_term = get_theme_mod( 'post_load_nextpost_same_category', false );

	if ( get_theme_mod( 'post_load_nextpost_reverse', false ) ) {
		$object_next_post = get_previous_post( $in_same_term );
	} else {
		$object_next_post = get_next_post( $in_same_term );
	}

	if ( isset( $object_next_post->ID ) ) {

		$post = get_post( $object_next_post->ID );

		setup_postdata( $post );

		$next_post = $object_next_post->ID;
	}

	wp_reset_postdata();

	return $next_post;
}

/**
 * Localize the main theme scripts.
 */
function esn_nextpost_more_js() {
	if ( ! esn_get_state_load_nextpost() ) {
		return;
	}

	if ( ! is_singular( 'post' ) ) {
		return false;
	}

	$ajax_type = version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ? 'ajax_restapi' : 'ajax';
	$ajax_type = apply_filters( 'ajax_load_nextpost_method', $ajax_type );

	$args = array(
		'type'         => $ajax_type,
		'not_in'       => (array) get_the_ID(),
		'next_post'    => esn_nextpost_get_id(),
		'current_user' => get_current_user_id(),
		'nonce'        => wp_create_nonce( 'esn-load-nextpost-nonce' ),
		'rest_url'     => esc_url( get_rest_url( null, '/esn/v1/more-nextpost' ) ),
		'url'          => admin_url( 'admin-ajax.php' ),
	);

	wp_localize_script( 'esn-scripts', 'esn_ajax_nextpost', $args );
}
add_action( 'wp_enqueue_scripts', 'esn_nextpost_more_js', 99 );

/**
 * Get More Post
 */
function esn_load_nextpost() {
	global $esn_related_not_in;
	global $wp_query;
	global $post;
	global $more;

	// Check Nonce.
	wp_verify_nonce( null );

	$not_in    = array();
	$next_post = null;

	if ( isset( $_POST['not_in'] ) ) { // Input var ok.
		$not_in = (array) wp_unslash( $_POST['not_in'] );  // Input var ok.
	}

	if ( isset( $_POST['next_post'] ) ) { // Input var ok.
		$post_id = (int) $_POST['next_post'];  // Input var ok.
	}

	if ( isset( $_POST['current_user'] ) ) { // Input var ok.
		wp_set_current_user( (int) $_POST['current_user'] ); // Input var ok.
	}

	// Get Post.
	ob_start();

	if ( isset( $post_id ) ) {

		// Add post id for filter.
		array_push( $not_in, (string) $post_id );

		// Set global filter.
		$esn_related_not_in = $not_in;

		// Query Args.
		$args = array(
			'p' => $post_id,
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) :

			while ( $query->have_posts() ) :
				$query->the_post();

				// Set wp_query data.
				$wp_query              = $query;
				$wp_query->is_single   = true;
				$wp_query->is_singular = true;

				// Set global more.
				$more = 1;
				?>
				<div class="es-nextpost-section" data-title="<?php the_title_attribute(); ?>"
					data-url="<?php echo esc_url( get_permalink() ); ?>">

					<?php do_action( 'esn_load_nextpost_before' ); ?>

					<div <?php esn_site_content_class(); ?>>

						<?php do_action( 'esn_site_content_start' ); ?>

						<div class="es-container">

							<?php do_action( 'esn_main_content_before' ); ?>

							<div id="content" class="es-main-content">

								<?php do_action( 'esn_main_content_start' ); ?>

								<div id="primary" class="es-content-area">

									<?php do_action( 'esn_main_before' ); ?>

									<?php
									// Single before hook.
									do_action( 'esn_post_before' );

									// Include singular template.
									get_template_part( 'template-parts/content-singular' );

									// Single after hook.
									do_action( 'esn_post_after' );

									// Set next post.
									$next_post = esn_nextpost_get_id();
									?>

									<?php do_action( 'esn_main_after' ); ?>

								</div>

								<?php get_sidebar(); ?>

							</div>

							<?php do_action( 'esn_main_content_after' ); ?>

						</div>

						<?php do_action( 'esn_site_content_end' ); ?>

					</div>

					<?php do_action( 'esn_load_nextpost_after' ); ?>
				</div>
				<?php

			endwhile;

		endif;

		wp_reset_postdata();
	}

	$content = ob_get_clean();

	if ( ! $content ) {
		$next_post = null;
	}

	// Return Result.
	$result = array(
		'not_in'    => $not_in,
		'next_post' => $next_post,
		'content'   => $content,
		'title'     => get_the_title(),
	);

	return $result;
}

/**
 * AJAX Load Nextpost
 */
function esn_ajax_load_nextpost() {

	// Check Nonce.
	check_ajax_referer( 'esn-load-nextpost-nonce', 'nonce' );

	// Get Post.
	$data = esn_load_nextpost();

	// Return Result.
	wp_send_json_success( $data );
}
add_action( 'wp_ajax_esn_ajax_load_nextpost', 'esn_ajax_load_nextpost' );
add_action( 'wp_ajax_nopriv_esn_ajax_load_nextpost', 'esn_ajax_load_nextpost' );


/**
 * Nextpost API Response
 *
 * @param array $request REST API Request.
 */
function esn_load_nextpost_restapi( $request ) {

	// Get Data.
	$data = array(
		'success' => true,
		'data'    => esn_load_nextpost(),
	);

	// Return Result.
	return rest_ensure_response( $data );
}

/**
 * Register REST Nextpost Routes
 */
function esn_register_nextpost_route() {

	register_rest_route(
		'esn/v1',
		'/more-nextpost',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'esn_load_nextpost_restapi',
			'permission_callback' => function () {
				return true;
			},
		)
	);
}
add_action( 'rest_api_init', 'esn_register_nextpost_route' );

/**
 * Filter all auto load posts from related.
 *
 * @param object $data The query.
 */
function esn_nextpost_filter_related( $data ) {
	global $esn_related_not_in;

	if ( ! is_single() ) {
		return $data;
	}

	if ( isset( $data->query_vars['query_type'] ) && 'related' === $data->query_vars['query_type'] ) {
		// Exclude next post.
		if ( esn_get_state_load_nextpost() ) {
			$next_post = esn_nextpost_get_id();

			$data->query_vars['post__not_in'][] = $next_post ? $next_post : false;
		}

		// Exclude loaded posts.
		$data->query_vars['post__not_in'] = array_merge(
			(array) $data->query_vars['post__not_in'],
			(array) $esn_related_not_in
		);
	}

	return $data;
}
add_action( 'pre_get_posts', 'esn_nextpost_filter_related' );
