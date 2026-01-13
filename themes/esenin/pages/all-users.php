<?php
/**
 * Template name: Все пользователи
 *
 * Page with all site users
 *
 * @package Esenin
 */

get_header(); ?>

<div id="primary" class="es-content-area">
  <div class="es-page-mw">
	<?php
	/**
	 * The esn_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_before' );
	?>
	<?php echo do_shortcode('[esn-all-users]'); ?>
	<?php
	/**
	 * The esn_main_after hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_after' );
	?>
  </div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); 