<?php
/**
 * The template for displaying the footer layout
 *
 * @package Esenin
 */

?>
<footer class="es-footer d-none">
	<div class="es-container">
		<div class="es-footer__item">
			<div class="es-footer__item-inner">
				<div class="es-footer__col es-col-left">
					<?php esn_component( 'footer_logo' ); ?>
					<?php esn_component( 'footer_description' ); ?>
					<?php esn_component( 'misc_social_links' ); ?>
				</div>
				<?php if ( esn_component( 'footer_nav_menu', false ) ) { ?>
					<div class="es-footer__col es-col-right">
						<?php esn_component( 'footer_nav_menu' ); ?>
					</div>
				<?php } ?>
			</div>
			<?php if ( get_theme_mod( 'footer_copyright', esc_html__( '© 2025 — РФПЛ. All Rights Reserved.', 'esenin' ) ) ) { ?>
				<div class="es-footer__item-inner-bottom">
					<?php esn_component( 'footer_copyright' ); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</footer>

<?php 
/**
 * Вызываем наш мобильный бар через wp_is_mobile.
 * Помни: если на сайте есть кэш (WP Rocket и т.д.), 
 * включи в нем разделение кэша для мобильных устройств!
 */
if ( wp_is_mobile() ) {
	get_template_part( 'template-parts/mobile-bottom-bar' );
}
?>

<?php 
/**
 * Кнопка Scroll to Top.
 * На мобилках она может перекрывать наш новый бар.
 * Если будет мешать — её тоже можно обернуть в if ( ! wp_is_mobile() )
 */
esn_component( 'scroll_to_top' ); 
?>