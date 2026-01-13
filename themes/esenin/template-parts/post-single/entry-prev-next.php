<?php
/**
 * The template part for displaying post prev next section.
 *
 * @package Esenin
 */

$prev_post = get_previous_post();
$next_post = get_next_post();

if ( $prev_post || $next_post ) {
	?>

	<div class="es-entry-prev-next">
		<?php
		// Prev post.
		if ( $prev_post ) {
			$query = new WP_Query(
				array(
					'posts_per_page' => 1,
					'p'              => $prev_post->ID,
				)
			);

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					?>
					<div class="es-entry-prev-next__item es-entry__prev">
						<a class="es-entry-prev-next__link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( $prev_post->post_title ); ?>"></a>

						<div class="es-entry-prev-next__label">
							<span class="es-entry-prev-next__icon es-prev-icon"><i class="es-icon es-icon-chevron-left"></i></span>
							<span><?php echo esc_html__( 'Предыдущая статья', 'esenin' ); ?></span>
						</div>

						<div class="es-entry">
							<div class="es-entry__outer">
								<div class="es-entry__inner es-entry__content">
									<h2 class="es-entry__title"><?php echo esc_html( $prev_post->post_title ); ?></h2>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}
			wp_reset_postdata();
		}

		// Next post.
		if ( $next_post ) {
			$query = new WP_Query(
				array(
					'posts_per_page' => 1,
					'p'              => $next_post->ID,
				)
			);

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					?>
					<div class="es-entry-prev-next__item es-entry__next">
						<a class="es-entry-prev-next__link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( $next_post->post_title ); ?>"></a>

						<div class="es-entry-prev-next__label">
							<span><?php echo esc_html__( 'Следующая статья', 'esenin' ); ?></span>
							<span class="es-entry-prev-next__icon es-next-icon"><i class="es-icon es-icon-chevron-right"></i></span>
						</div>

						<div class="es-entry">
							<div class="es-entry__outer">
								<div class="es-entry__inner es-entry__content ">
									<h2 class="es-entry__title"><?php echo esc_html( $next_post->post_title ); ?></h2>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}
			wp_reset_postdata();
		}
		?>
	</div>
	<?php
}
