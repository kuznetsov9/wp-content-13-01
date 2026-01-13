<?php
/**
 * Template part for displaying full posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Esenin
 */

$options = get_query_var( 'options' );

// Thumbnail size.
$thumbnail_size = 'esn-large';

if ( 'uncropped' === esn_get_page_preview() ) {
	$thumbnail_size = sprintf( '%s-uncropped', $thumbnail_size );
}

$excerpt = es_excerpt();

?>

<article <?php post_class(); ?>>
  <div class="post-card">

<?php get_template_part( 'template-parts/post-card/header-meta' ); ?>



				<?php
				the_title( '<h2 class="es-entry__title pc-title"><a href="' . esc_url( get_permalink() ) . '"><span>', '</span></a></h2>' );
				?>
				<div class="es-entry-type-<?php echo esc_attr( $options['summary_type'] ); ?> pc-excerpt">
					<?php
					if ( 'summary' === $options['summary_type'] ) {
						echo $excerpt;
					} else {
						$more_link_text = sprintf(
							/* translators: %s: Name of current post */
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'esenin' ),
							get_the_title()
						);

						the_content( $more_link_text );
					}
					?>
				</div>

    
    <div class="es-entry__full-header">
		<div class="es-entry__container">
			<?php if ( has_post_thumbnail() ) { ?>
				<figure class="es-entry__inner es-entry__thumbnail " data-scheme="inverse">
					<div class="es-overlay-post-meta es-meta-overlay-background"><?php esn_get_post_meta( array( '', 'reading_time' ), true, $options['meta'] ); ?></div>
					<div class="es-overlay-transparent">
						<?php the_post_thumbnail( $thumbnail_size ); ?>
					</div>
					<a class="es-overlay-link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"></a>
				</figure>
			<?php } ?>
		</div>
	</div>
	
<?php get_template_part( 'template-parts/post-card/footer-meta' ); ?>
<?php get_template_part( 'template-parts/post-card/footer-last-comment' ); ?>
	 
	
  </div>	
</article>
