<?php
/**
 * Template name: Закладки
 *
 * Page with all bookmarks posts
 *
 * @package Esenin
 */
 
global $user_ID;
global $current_user, $wp_roles;
wp_get_current_user();
 
if( !$user_ID ) {
	header('location:' . site_url() . '/#login');
	exit;
} else {
	$userdata = get_user_by( 'id', $user_ID );
}

get_header(); ?>

<div id="primary" class="es-content-area">

	<?php
	/**
	 * The esn_main_before hook.
	 *
	 * @since 1.0.0
	 */
	do_action( 'esn_main_before' );
	?>
	
	
<?php
if (!defined('ABSPATH')) {
    exit; 
}

global $wpdb;
$user_id = get_current_user_id();
$table_name = $wpdb->prefix . 'bookmarks';

$paged = max(1, get_query_var('paged', 1));
$per_page = get_option('posts_per_page', 10); 
$offset = ($paged - 1) * $per_page;


$bookmarks = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $table_name WHERE user_id = %d LIMIT %d OFFSET %d", $user_id, $per_page, $offset));

$total_bookmarks = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE user_id = %d", $user_id));
$total_pages = ceil($total_bookmarks / $per_page);

$options = esn_get_archive_options();
$grid_columns_desktop = $options['columns'];
$columns_desktop = 'es-desktop-column-' . $grid_columns_desktop;
$main_classes = 'es-posts-area__' . $options['location'] . ' es-posts-area__' . $options['layout'];
?>

<div class="es-posts-area es-posts-area-posts">
    <div class="es-posts-area__outer">
        <div class="es-posts-area__main es-archive-<?php echo esc_attr($options['layout']); ?> <?php echo esc_attr($main_classes); ?> <?php echo ('list' === $options['layout'] || 'grid' === $options['layout']) ? esc_attr($columns_desktop) : ''; ?>">

<?php
if ($bookmarks) {    
    $bookmarks = array_reverse($bookmarks);

    foreach ($bookmarks as $bookmark) {
        $post = get_post($bookmark->post_id);
        if ($post) {
            setup_postdata($post);
            set_query_var('options', $options);

            $template_part = 'template-parts/archive/content-full'; // По умолчанию
            if ('overlay' === $options['layout']) {
                $template_part = 'template-parts/archive/entry-overlay';
            } elseif ('full' !== $options['layout']) {
                $template_part = 'template-parts/archive/entry';
            }

            get_template_part($template_part);
            wp_reset_postdata(); 
        }
    }
} else {
    echo '<div class="es-posts-area__no-posts">';
    echo esc_html__('Закладок пока нет..', 'esenin'); 
    echo '</div>';
}
?>

<?php 
// Standart Pagination (My Bookmarks)
if ( 'standard' === get_theme_mod( esn_get_archive_option( 'pagination_type' ), 'standard' ) ) : ?>
            <div class="es-posts-area__pagination mt-2">
                <nav class="navigation pagination">
                    <div class="nav-links">
<?php
if ($total_pages > 1) {
 if (wp_is_mobile()) {
        $pagination_links = '';

        if ($paged > 1) {
            $pagination_links .= '<a class="prev page-numbers current" href="' . esc_url(get_pagenum_link($paged - 1)) . '">' . __('Previous', 'esenin') . '</a>';
        }

        $pagination_links .= '<span class="current-page page-numbers current">' . $paged . ' ' . __('for ', 'esenin') . ' ' . $total_pages . '</span>';

        if ($paged < $total_pages) {
            $pagination_links .= '<a class="next page-numbers current" href="' . esc_url(get_pagenum_link($paged + 1)) . '">' . __('Next', 'esenin') . '</a>';
        }

        echo $pagination_links;

    } else {
$pagination_args = array(
    'total' => $total_pages,
    'current' => $paged, 
    'format' => 'page/%#%',
    'prev_text' => __('Предыдущая', 'esenin'),
    'next_text' => __('Следующая', 'esenin'),
	'mid_size' => 1,
    'end_size' => 1,
);
echo paginate_links($pagination_args);
	}
}
?>
                    </div>
                </nav>
            </div>
<?php endif; ?>       
		
		</div>
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