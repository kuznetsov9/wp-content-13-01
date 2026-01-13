<?php
/**
 * The template Category
 *
 * @package Esenin
 */

get_header(); 

$category = get_queried_object();
$category_id = $category->term_id;
$category_description = $category->description;
$esn_category_logo = get_term_meta( $category->term_id, 'esn_category_logo', true );
?>
<div id="primary" class="es-content-area">

	<?php
	/**
	 * The esn_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_before' );
	?>
	
	
	
<div class="theme-card">
    <div class="d-flex justify-content-between flex-column flex-md-row">
        <div class="d-flex align-items-center justify-content-center flex-column flex-md-row">
            <?php if ($esn_category_logo) { ?>
                <div class="es-theme-item__logo me-3 flex-shrink-0 mt-2 mt-md-0">
                    <?php esn_get_retina_image($esn_category_logo); ?>
                </div>
            <?php } ?>

            <div class="flex-grow-1 text-center text-md-start">
                <?php the_archive_title('<h1 class="es-page__title mt-3 mt-md-0">', '</h1>'); ?>

                <div class="es-theme-item_count gap-3 d-flex justify-content-center justify-content-md-start mt-1">
                    <div class="theme-posts_count">
                        <span class="fw-bold"><?php 
                            $num_posts = $category->count; 
                            echo $num_posts; ?></span>
                        <?php echo num_decline($num_posts, 'статья,статьи,статей', 0); ?>
                    </div>
					<?php echo do_shortcode('[category_subscribers_list category_id="' . $category_id . '"]'); ?>
                </div>
            </div>
        </div>
        <div class="es-theme-item_right d-flex align-items-start justify-content-center mt-3 mt-md-0">
            <div class="theme-subscribe">
				<?php echo do_shortcode('[cat_subscribe category_id="' . $category_id . '"]'); ?>
            </div>
        </div>
    </div>

    <div class="theme-tabs d-flex justify-content-center justify-content-md-start">
        <ul class="nav nav-underline" id="themeTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="posts-tab" data-bs-toggle="tab" href="#posts" role="tab" aria-controls="posts" aria-selected="true"><?php esc_html_e('Статьи', 'esenin'); ?></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="about-tab" data-bs-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="false"><?php esc_html_e('Описание', 'esenin'); ?></a>
            </li>
        </ul>
    </div>
</div>					
							
<div class="tab-content theme-tabs-content" id="themeTabContent">

        <div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
<?php
// Sort theme
$current_category = get_queried_object();
$args = array(
    'cat' => $current_category->term_id,
	'post_status' => 'publish',
    'posts_per_page' => -1 
);
$query = new WP_Query($args);
if ($query->found_posts >= 2) {
    get_template_part('inc/filter/in_themes--authors');
}
wp_reset_postdata();
?>		
	<?php
	if ( have_posts() ) {
		// Set options.
		$options = esn_get_archive_options();

		$grid_columns_desktop = $options['columns'];

		$columns_desktop = 'es-desktop-column-' . $grid_columns_desktop;

		// Location.
		$main_classes = ' es-posts-area__' . $options['location'];

		// Layout.
		$main_classes .= ' es-posts-area__' . $options['layout'];

		?>

		<div class="es-posts-area es-posts-area-posts">
			<div class="es-posts-area__outer">

				<div class="es-posts-area__main es-archive-<?php echo esc_attr( $options['layout'] ); ?> <?php echo esc_attr( $main_classes ); ?> <?php echo ( 'list' === $options['layout'] || 'grid' === $options['layout'] ) ? esc_attr( $columns_desktop ) : ''; ?>">
					<?php
					// Start the Loop.
					while ( have_posts() ) {
						the_post();

						set_query_var( 'options', $options );

						if ( isset( $options['layout'] ) && 'full' === $options['layout'] ) {
							get_template_part( 'template-parts/archive/content-full' );
						} elseif ( 'overlay' === $options['layout'] ) {
							get_template_part( 'template-parts/archive/entry-overlay' );
						} else {
							get_template_part( 'template-parts/archive/entry' );
						}
					}
					?>
				</div>
			</div>

			<?php
			/* Posts Pagination */
			if ( 'standard' === get_theme_mod( esn_get_archive_option( 'pagination_type' ), 'standard' ) ) {
				?>
				<div class="es-posts-area__pagination">
					<?php
if ( wp_is_mobile() ) {
    compact_pagination();
} else {
    the_posts_pagination( 
        array( 
            'prev_text' => __( 'Previous', 'esenin' ),
			'next_text' => __( 'Next', 'esenin' ),
        ) 
    );
}
?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} elseif ( ! get_query_var( 'esn_have_search' ) ) {
		?>
		<div class="entry-content es-content-not-found">

			<?php if ( is_search() ) { ?>
				<div class="es-content-not-found-content">
					<?php esc_html_e( 'It seems we cannot find what you are looking for. Please check the spelling or rephrase.', 'esenin' ); ?>
				</div>
			<?php } elseif ( is_404() ) { ?>
				<div class="es-content-not-found-content">
					<?php esc_html_e( 'Unfortunately the page you are looking for is not available.', 'esenin' ); ?>
				</div>
				<a class="es-button" href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'Back to Home', 'esenin' ); ?></a>
			<?php } ?>

		</div>
		<?php
	}
	?>

	 </div>
        <div class="tab-pane fade theme-about" id="about" role="tabpanel" aria-labelledby="about-tab">
          
		  
		  <?php if ( $category_description ) { ?>								
					<?php echo $category_description; ?>
              <?php } else { ?>	
               		 <?php esc_html_e( 'Описание не задано..', 'esenin' ); ?>	  
		   <?php } ?>   
		  
        </div>
    </div>
	
	
	
	
	
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
