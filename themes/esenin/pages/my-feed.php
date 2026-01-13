<?php
/**
 * Template name: Моя лента
 *
 * Page with my feed
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
    do_action( 'esn_main_before' );
    ?>

<?php
global $wpdb;

$user_id = get_current_user_id();

$subscribed_categories = $wpdb->get_col($wpdb->prepare("
    SELECT category_id FROM {$wpdb->prefix}category_subscriptions
    WHERE user_id = %d
", $user_id));

$subscribed_users = $wpdb->get_col($wpdb->prepare("
    SELECT user_id FROM {$wpdb->prefix}user_subscriptions
    WHERE subscriber_id = %d
", $user_id));

if (!empty($subscribed_categories) || !empty($subscribed_users)) {

    $post_ids = [];

    function get_post_ids_by_user_or_category($user_ids, $category_ids, $wpdb) {
        $post_ids = [];

        if (!empty($user_ids)) {
            $user_ids_placeholder = implode(',', array_fill(0, count($user_ids), '%d'));
            $user_posts = $wpdb->get_col($wpdb->prepare("
                SELECT ID FROM {$wpdb->prefix}posts 
                WHERE post_author IN ($user_ids_placeholder) AND post_status = 'publish' AND post_type = 'post'
            ", ...$user_ids));
            $post_ids = array_merge($post_ids, $user_posts);
        }

        if (!empty($category_ids)) {
            $category_ids_placeholder = implode(',', array_fill(0, count($category_ids), '%d'));
            $category_posts = $wpdb->get_col($wpdb->prepare("
                SELECT ID FROM {$wpdb->prefix}posts 
                WHERE ID IN (
                    SELECT object_id FROM {$wpdb->prefix}term_relationships WHERE term_taxonomy_id IN ($category_ids_placeholder)
                ) AND post_status = 'publish' AND post_type = 'post'
            ", ...$category_ids));
            $post_ids = array_merge($post_ids, $category_posts);
        }

        return array_unique($post_ids);
    }

    $post_ids = get_post_ids_by_user_or_category($subscribed_users, $subscribed_categories, $wpdb);

    $posts_per_page = get_option('posts_per_page', 10); 
    $paged = max(1, get_query_var('paged', 1)); 
    $total_posts = count($post_ids); 
    $total_pages = ceil($total_posts / $posts_per_page); 

    if (!empty($post_ids)) {
        $ids_placeholder = implode(',', array_map('intval', $post_ids));

        $query = "
            SELECT * FROM {$wpdb->prefix}posts 
            WHERE ID IN ($ids_placeholder) AND post_status = 'publish' 
            ORDER BY post_date DESC
            LIMIT " . (($paged - 1) * $posts_per_page) . ", $posts_per_page
        ";

        $posts = $wpdb->get_results($query);

        $options = esn_get_archive_options();
        $grid_columns_desktop = $options['columns'];
        $columns_desktop = 'es-desktop-column-' . $grid_columns_desktop;
        $main_classes = ' es-posts-area__' . $options['location'] . ' es-posts-area__' . $options['layout'];
        ?>

        <div class="es-posts-area es-posts-area-posts">
            <div class="es-posts-area__outer">
                <div class="es-posts-area__main es-archive-<?php echo esc_attr($options['layout']); ?> <?php echo esc_attr($main_classes); ?> <?php echo ('list' === $options['layout'] || 'grid' === $options['layout']) ? esc_attr($columns_desktop) : ''; ?>">

                    <?php if ($posts) {
                        foreach ($posts as $post) {
                            setup_postdata($post); 
                            set_query_var('options', $options);

                            $template_part = 'template-parts/archive/content-full';
                            if ('overlay' === $options['layout']) {
                                $template_part = 'template-parts/archive/entry-overlay';
                            } elseif ('full' !== $options['layout']) {
                                $template_part = 'template-parts/archive/entry';
                            }
                            get_template_part($template_part);
                        }
                        wp_reset_postdata(); ?>

                        <?php 
                        if ('standard' === get_theme_mod(esn_get_archive_option('pagination_type'), 'standard')) : ?> 
                            <div class="es-posts-area__pagination mt-2">    
                                <nav class="navigation pagination">
                                    <div class="nav-links">
                                        <?php
                                        if ($total_pages > 1) {
                                            echo paginate_links(array(
                                                'total' => $total_pages,
                                                'current' => $paged,
                                                'format' => 'page/%#%',
                                                'prev_text' => __('Previous', 'esenin'),
                                                'next_text' => __('Next', 'esenin'),
                                                'mid_size' => 1,
                                                'end_size' => 1,
                                            ));
                                        }
                                        ?>
                                    </div> 
                                </nav> 
                            </div>
                        <?php endif; ?>                   
                    <?php } else { ?>
                        <div class="es-posts-area__no-posts">
                            <?php esc_html_e('Здесь пока пусто..', 'esenin'); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    <?php } else { ?>
        <div class="es-posts-area__no-posts">
            <?php esc_html_e('Здесь пока пусто..', 'esenin'); ?>
        </div>
    <?php }
} else { ?>
    <div class="es-posts-area__no-posts">
        <?php esc_html_e('Здесь пока пусто..', 'esenin'); ?>
    </div>
<?php } ?>


    <?php
    do_action('esn_main_after');
    ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>