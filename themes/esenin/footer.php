<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "es-site" div and all content after
 *
 * @package Esenin
 */

?>

							<?php
							/**
							 * The esn_main_content_end hook.
							 *
							 * @since 1.0.0
							 */
							do_action( 'esn_main_content_end' );
							?>

						</div>

						<?php
						/**
						 * The esn_main_content_after hook.
						 *
						 * @since 1.0.0
						 */
						do_action( 'esn_main_content_after' );
						?>

					</div>

					<?php
					/**
					 * The esn_site_content_end hook.
					 *
					 * @since 1.0.0
					 */
					do_action( 'esn_site_content_end' );
					?>

				</div>

				<?php
				/**
				 * The esn_site_content_after hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'esn_site_content_after' );
				?>

			</main>
          </div>
		<?php
		/**
		 * The esn_footer_before hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'esn_footer_before' );
		?>

		<?php get_template_part( 'template-parts/footer' ); ?>

		<?php
		/**
		 * The esn_footer_after hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'esn_footer_after' );
		?>
     
	</div>

	<?php
	/**
	 * The esn_site_end hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_site_end' );
	?>

</div>

<?php
/**
 * The esn_site_after hook.
 *
 * @since 1.0.0
 */
do_action( 'esn_site_after' );
?>

<?php wp_footer(); ?>

</body>
</html>
