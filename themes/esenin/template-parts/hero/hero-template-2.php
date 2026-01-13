<?php
/**
 * The template for displaying the hero 2 layout
 *
 * @package Esenin
 */

$args               = esn_get_hero_query_args();
$hero_post_meta     = get_theme_mod( 'home_hero_meta', array( 'category', 'author', 'date', 'reading_time' ) );
$hero_discover_more = get_theme_mod( 'home_hero_discover_more', true );

$parallax       = 'yes' === get_theme_mod( 'home_hero_slider_parallax', 'yes' ) ? true : false;
$autoplay       = 'yes' === get_theme_mod( 'home_hero_slider_autoplay', 'yes' ) ? true : false;
$autoplay_delay = 5000;

if ( $autoplay ) {
	$autoplay_delay = get_theme_mod( 'home_hero_slider_delay', 5000 );
}

$counter   = 0;
$hero_type = get_theme_mod( 'home_hero_layout', 'hero-type-2' );
$gap       = 32;

?>

<section class="es-home-hero es-hero-type-2">
	<div
		class="es-hero-type-2__slider"
		data-es-autoplay="<?php echo esc_attr( $autoplay ); ?>"
		data-es-autoplay-delay="<?php echo esc_attr( $autoplay_delay ); ?>"
		data-es-parallax="<?php echo esc_attr( $parallax ); ?>"
		data-es-gap="<?php echo esc_attr( $gap ); ?>"
	>
		<div class="es-hero-type-2__wrapper" data-scheme="inverse">

			<?php
			$query = new \WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					++$counter;
					?>

					<article class="es-hero-type-2__item es-entry">

						<div class="es-entry__outer es-entry__overlay es-overlay-ratio es-ratio-landscape-16-9">
							<div class="es-entry__inner es-entry__thumbnail">
								<div class="es-overlay-background">
									<?php the_post_thumbnail( 'esn-large-uncropped' ); ?>
								</div>
							</div>

							<div class="es-entry__inner es-entry__content es-overlay-content" data-swiper-parallax-x="-400" data-swiper-parallax-duration="800" >

								<div class="es-entry__content-top">
									<div class="es-overlay-post-meta es-meta-overlay-transparent">
										<?php esn_get_post_meta( array( 'category', 'reading_time' ), true, $hero_post_meta ); ?>
									</div>
								</div>

								<div class="es-entry__content-body">
									<?php esn_get_post_meta( array( 'author', 'date', 'comments', 'views' ), true, $hero_post_meta ); ?>

									<h2 class="es-entry__title">
										<?php echo esc_html( get_the_title() ); ?>
									</h2>

									<?php
									$excerpt = esn_get_the_excerpt( 150 );
									if ( $excerpt ) {
										?>
										<div class="es-entry__subtitle">
											<?php echo esc_html( $excerpt ); ?>
										</div>
									<?php } ?>

									<?php if ( $hero_discover_more ) { ?>
										<?php esn_component( 'discover_more_button' ); ?>
									<?php } ?>
								</div>

							</div>

							<a class="es-overlay-link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"></a>
						</div>

					</article>

					<?php
				}
				wp_reset_postdata();
			}
			?>
		</div>

		<?php if ( $counter > 1 ) { ?>
			<?php if ( $counter <= 5 ) { ?>
				<div class="es-hero-type-2__pagination" data-scheme="inverse"></div>
			<?php } ?>
			<div class="es-hero-type-2__button-prev"></div>
			<div class="es-hero-type-2__button-next"></div>
		<?php } ?>
	</div>
</section>
