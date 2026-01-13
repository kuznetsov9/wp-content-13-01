<?php
/**
 * The template for displaying all single posts and attachments.
 *
 * @package Esenin
 */

get_header(); ?>

<div id="primary" class="es-content-area es-single w-100">

	<?php
	/**
	 * The esn_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_before' );
	?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<?php
		/**
		 * The esn_post_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'esn_post_before' );
		?>

			<?php get_template_part( 'template-parts/content-singular' ); ?>

		<?php
		/**
		 * The esn_post_after hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'esn_post_after' );
		?>

	<?php endwhile; ?>

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
