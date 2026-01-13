<?php
/**
 * Template name: Уведомления
 *
 * Page Notifications
 *
 * @package Esenin
 */

if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}

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
	<?php echo do_shortcode('[esenin_notifications_list]'); ?>
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