<?php
/**
 * The template for displaying the hero 3 layout
 *
 * @package Esenin
 */

$args           = esn_get_hero_query_args();
$hero_post_meta = get_theme_mod( 'home_hero_meta', array( 'category', 'author', 'date', 'reading_time' ) );
$counter        = 0;

?>

<section class="es-home-hero es-hero-type-3">
	<div class="es-container">
		<div class="es-hero-type-3__container" >
			<?php
			$query_test = new \WP_Query( $args );

			if ( $query_test->have_posts() ) {
				while ( $query_test->have_posts() ) {
					$query_test->the_post();
					++$counter;

					if ( 1 === $counter ) {
						$featured_excerpt = esn_get_the_excerpt( 160 );
						?>
						<article class="es-entry es-entry__featured" >
							<div class="es-entry__outer ">
								<?php if ( has_post_thumbnail() ) { ?>
									<div class="es-entry__inner es-entry__thumbnail es-entry__overlay es-overlay-ratio es-ratio-landscape-16-9" data-scheme="inverse">
										<div class="es-overlay-post-meta es-meta-overlay-transparent">
											<?php esn_get_post_meta( array( 'category', 'reading_time' ), true, $hero_post_meta ); ?>
										</div>
										<div class="es-overlay-background es-overlay-transparent">
											<?php the_post_thumbnail( 'esn-large-uncropped' ); ?>
											<a class="es-overlay-link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"></a>
										</div>
									</div>
								<?php } ?>

								<div class="es-entry__inner es-entry__content">

									<?php esn_get_post_meta( array( 'author', 'date', 'comments', 'views' ), true, $hero_post_meta ); ?>

									<h2 class="es-entry__title">
										<a href="<?php echo esc_url( get_permalink() ); ?>">
											<?php echo esc_html( get_the_title() ); ?>
										</a>
									</h2>

									<?php if ( $featured_excerpt ) { ?>
										<div class="es-entry__subtitle">
											<span><?php echo esc_html( $featured_excerpt ); ?></span>
										</div>
									<?php } ?>

								</div>

							</div>
						</article>
						<?php
					} else {
						$excerpt = esn_get_the_excerpt();
						?>
						<article class="es-entry es-entry__list">
							<div class="es-entry__outer">
								<?php if ( has_post_thumbnail() ) { ?>
									<div class="es-entry__inner es-entry__thumbnail es-entry__overlay es-overlay-ratio" data-scheme="inverse">
										<div class="es-overlay-post-meta">
											<?php esn_get_post_meta( array( 'reading_time' ), true, $hero_post_meta ); ?>
										</div>
										<div class="es-overlay-background es-overlay-transparent">
											<?php the_post_thumbnail( 'esn-thumbnail' ); ?>
										</div>
										<a class="es-overlay-link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"></a>
									</div>
								<?php } ?>

								<div class="es-entry__inner es-entry__content">

									<?php esn_get_post_meta( array( 'author', 'date', 'comments', 'views' ), true, $hero_post_meta ); ?>

									<h2 class="es-entry__title">
										<a href="<?php echo esc_url( get_permalink() ); ?>">
											<?php echo esc_html( get_the_title() ); ?>
										</a>
									</h2>

									<?php if ( $excerpt ) { ?>
										<div class="es-entry__excerpt">
											<?php echo esc_html( $excerpt ); ?>
										</div>
									<?php } ?>

									<?php esn_get_post_meta( array( 'category' ), true, $hero_post_meta ); ?>

								</div>
							</div>
						</article>
						<?php
					}
				}
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
