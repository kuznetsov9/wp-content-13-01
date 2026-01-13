<?php
/**
 * Template name: Популярные статьи
 */
get_header(); 
$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
$timeframe = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'week';

$query = get_popular_posts($timeframe, $paged);
?>

<div id="primary" class="es-content-area">

    <?php do_action('esn_main_before'); ?>

    <?php get_template_part('inc/filter/in_popular_posts'); ?>

    <?php if ($query->have_posts()) : 
        $options = esn_get_archive_options();
        $grid_columns_desktop = $options['columns'];
        $columns_desktop = 'es-desktop-column-' . $grid_columns_desktop;

        $main_classes = ' es-posts-area__' . $options['location'];
        $main_classes .= ' es-posts-area__' . $options['layout'];
        
        // Получаем закодированные настройки для JS
        $archive_data = esn_get_popular_archive_data($query, $timeframe);
    ?>

        <div class="es-posts-area es-posts-area-posts" data-posts-area="<?php echo $archive_data; ?>">
            <div class="es-posts-area__outer">
                <div class="es-posts-area__main es-archive-<?php echo esc_attr($options['layout']); ?> <?php echo esc_attr($main_classes); ?> <?php echo ('list' === $options['layout'] || 'grid' === $options['layout']) ? esc_attr($columns_desktop) : ''; ?>">

                    <?php
                    while ($query->have_posts()) {
                        $query->the_post();
                        set_query_var('options', $options);

                        if (isset($options['layout']) && 'full' === $options['layout']) {
                            get_template_part('template-parts/archive/content-full');
                        } elseif ('overlay' === $options['layout']) {
                            get_template_part('template-parts/archive/entry-overlay');
                        } else {
                            get_template_part('template-parts/archive/entry');
                        }
                    }
                    ?>
                </div>
            </div>
            
            </div>

    <?php else : ?>
        <div class="es-content-none"><?php esc_html_e('Пока не найдены..', 'esenin'); ?></div>
    <?php endif; ?>

    <?php do_action('esn_main_after'); ?>
</div>

<?php 
get_sidebar();
get_footer(); 
?>