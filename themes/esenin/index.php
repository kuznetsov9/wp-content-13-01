<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Esenin
 */

get_header(); ?>
<div id="primary" class="es-content-area">

	<?php
	/**
	 * The esn_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_before' );
	?>

	<?php
	if ( have_posts() ) {
		// Set options.
		$options = esn_get_archive_options();

		$grid_columns_desktop = $options['columns'];

		$columns_desktop = 'es-desktop-column-' . $grid_columns_desktop;

		// Location.
		$main_classes = ' es-posts-area__' . $options['location'];

		// Layout.
		$main_classes .= ' es-posts-area__' . $options['layout'];

		?>

		<div class="es-posts-area es-posts-area-posts">
			<div class="es-posts-area__outer">

				<div class="es-posts-area__main es-archive-<?php echo esc_attr( $options['layout'] ); ?> <?php echo esc_attr( $main_classes ); ?> <?php echo ( 'list' === $options['layout'] || 'grid' === $options['layout'] ) ? esc_attr( $columns_desktop ) : ''; ?>">
					<?php
					// Start the Loop.
					while ( have_posts() ) {
						the_post();

						set_query_var( 'options', $options );

						if ( isset( $options['layout'] ) && 'full' === $options['layout'] ) {
							get_template_part( 'template-parts/archive/content-full' );
						} elseif ( 'overlay' === $options['layout'] ) {
							get_template_part( 'template-parts/archive/entry-overlay' );
						} else {
							get_template_part( 'template-parts/archive/entry' );
						}
					}
					?>
				</div>
			</div>

			<?php
			/* Posts Pagination */
			if ( 'standard' === get_theme_mod( esn_get_archive_option( 'pagination_type' ), 'standard' ) ) {
				?>
				<div class="es-posts-area__pagination">
					<?php
if ( wp_is_mobile() ) {
    compact_pagination();
} else {
    the_posts_pagination( 
        array( 
            'prev_text' => __( 'Previous', 'esenin' ),
			'next_text' => __( 'Next', 'esenin' ),
        ) 
    );
}
?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} elseif ( ! get_query_var( 'esn_have_search' ) ) {
		?>
		<div class="entry-content es-content-not-found">

			<?php if ( is_search() ) { ?>
				<div class="es-content-not-found-content text-center fs-6">
					<?php esc_html_e( 'It seems we cannot find what you are looking for. Please check the spelling or rephrase.', 'esenin' ); ?>
				</div>
			<?php } elseif ( is_404() ) { ?>
				<div class="es-content-not-found-content">
					<?php esc_html_e( 'Unfortunately the page you are looking for is not available.', 'esenin' ); ?>
				</div>
				<a class="es-button" href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'Back to Home', 'esenin' ); ?></a>
			<?php } ?>

		</div>
		<?php
	}
	?>

	<?php
	/**
	 * The esn_main_after hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_after' );
	?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>