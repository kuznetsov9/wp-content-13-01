<?php
/**
 * The template for displaying the hero 1 layout
 *
 * @package Esenin
 */

$home_hero_heading    = get_theme_mod( 'home_hero_heading' );
$home_hero_subheading = get_theme_mod( 'home_hero_subheading' );
$allowed_html         = array(
	'span' => array(),
);

?>

<?php if ( ! empty( $home_hero_heading ) || ! empty( $home_hero_subheading ) ) { ?>
	<section class="es-home-hero es-hero-type-1">
		<div class="es-hero-type-1__wrapper">
			<div class="es-hero__content">
				<?php if ( ! empty( $home_hero_heading ) ) { ?>
					<h1 class="es-hero__heading"><?php echo wp_kses( $home_hero_heading, $allowed_html ); ?></h1>
				<?php } ?>
				<?php if ( ! empty( $home_hero_subheading ) ) { ?>
					<div class="es-hero__subheading">
						<?php echo esc_html( $home_hero_subheading ); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
<?php }
