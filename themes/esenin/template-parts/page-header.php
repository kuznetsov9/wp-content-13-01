<?php
/**
 * The template part for displaying page header.
 *
 * @package Esenin
 */

// Init clasfor header.
$class = null;

// If description exists.
if ( get_the_archive_description() ) {
	$class = 'es-page__header-has-description';
}
?>

<?php
do_action( 'esn_page_header_before' );

if ( is_search() ) {

	?>
	<div class="es-page__header <?php echo esc_attr( $class ); ?>">
		<h1 class="es-page__title"><span><?php esc_html_e( 'Search Results:', 'esenin' ); ?> <?php echo get_search_query(); ?></span></h1>
		<?php
		get_template_part( 'searchform' );
		?>
	</div>
	<?php

} elseif ( is_404() ) {
	?>
	<div class="es-page-404__icon">
		<i class="es-icon es-icon-not-found"></i>
	</div>
	<div class="es-page__header <?php echo esc_attr( $class ); ?>">
		<h1 class="es-page__title"><?php esc_html_e( 'Page Not Found', 'esenin' ); ?></h1>
	</div>
	<?php
}

do_action( 'esn_page_header_after' );