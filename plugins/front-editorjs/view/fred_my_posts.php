<?php

function fred_my_posts_shortcode() {
    if (!is_user_logged_in()) {
        return '<div class="es-auth-error">' . __('Для управления своими постами требуется авторизация.', 'front-editorjs') . '</div>';
    }

    wp_enqueue_style('fred-style', FRONT_EDITORJS_PLUGIN_URL . '/assets/css/style.css');
    wp_enqueue_script('fred-script-my-posts', FRONT_EDITORJS_PLUGIN_URL . '/assets/js/vendor/my-posts.js', array('jquery'), '1.0', true);

    $current_user = wp_get_current_user();
    $author_id = $current_user->ID;

    $current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'published';
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    $posts_per_page = 5;
    $args = array(
        'author'         => $author_id,
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'post_status'    => $current_tab === 'published' ? 'publish' : 'pending',
    );

    $args = apply_filters('fred_my_posts_orderby', $args, $current_tab);
    $query = new WP_Query($args);

    ob_start();
    ?>
    <div class="fred-tab-buttons-block">    
        <div class="fred-tab-buttons">
            <a class="fred-tab-button <?php echo $current_tab === 'published' ? 'active' : ''; ?>" href="<?php echo esc_url(add_query_arg(array('tab' => 'published', 'paged' => 1), get_permalink())); ?>">
                <?php esc_html_e('Опубликованные', 'front-editorjs'); ?>
            </a>
            <a class="fred-tab-button <?php echo $current_tab === 'pending' ? 'active' : ''; ?>" href="<?php echo esc_url(add_query_arg(array('tab' => 'pending', 'paged' => 1), get_permalink())); ?>">
                <?php esc_html_e('В ожидании', 'front-editorjs'); ?>
            </a>
        </div>
    </div>

    <div class="my-posts-content">
        <div class="tab-pane fade show active" id="<?php echo esc_attr($current_tab); ?>" role="tabpanel">
            <?php if ($query->have_posts()): ?>
                <div class="my-posts-list">
                    <?php while ($query->have_posts()): $query->the_post(); ?>
                        <?php
                        $post_id = get_the_ID();
                        $edit_link = home_url('/editor/?fred=edit&fred_id=' . $post_id);
                        ?>
                        <div class="fred-my-post row align-items-start" id="post-<?php echo $post_id; ?>">
                            <div class="col fred-mypostslist">
                                <a class="fred-post-title" href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a>                                  
                                <div class="fred-post-actions d-flex align-items-center justify-content-start gap-2 mt-1">
                                    <a href="<?php echo esc_url($edit_link); ?>" class="fred-edit-icon" title="<?php esc_attr_e('Редактировать пост', 'front-editorjs'); ?>">
                                        <div class="fred-footer-button d-flex justify-content-center align-items-center">
                                            <span class="fred-footer-button_icon"> 
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="14" height="14">
                                                    <path stroke="none" d="M24,3.46c-.05-1.03-.54-1.99-1.34-2.64-1.43-1.17-3.61-1.01-4.98,.36l-1.67,1.67c-.81-.54-1.77-.84-2.77-.84-1.34,0-2.59,.52-3.54,1.46l-3.03,3.03c-.39,.39-.39,1.02,0,1.41s1.02,.39,1.41,0l3.03-3.03c.89-.89,2.3-1.08,3.42-.57L2.07,16.79c-.69,.69-1.07,1.6-1.07,2.57,0,.63,.16,1.23,.46,1.77l-1.16,1.16c-.39,.39-.39,1.02,0,1.41,.2,.2,.45,.29,.71,.29s.51-.1,.71-.29l1.16-1.16c.53,.3,1.14,.46,1.77,.46,.97,0,1.89-.38,2.57-1.07L22.93,6.21c.73-.73,1.11-1.73,1.06-2.76ZM5.8,20.52c-.62,.62-1.7,.62-2.32,0-.31-.31-.48-.72-.48-1.16s.17-.85,.48-1.16L16.08,5.61l2.32,2.32L5.8,20.52ZM21.52,4.8l-1.71,1.71-2.32-2.32,1.6-1.6c.37-.37,.85-.56,1.32-.56,.36,0,.7,.11,.98,.34,.37,.3,.58,.72,.61,1.19,.02,.46-.15,.92-.48,1.24Z"/>
                                                </svg>
                                            </span>
                                        </div>
                                    </a>
                                    <?php
                                    $post_time = strtotime(get_the_date('Y-m-d H:i:s'));
                                    $current_time = time();
                                    $time_difference = $current_time - $post_time;
                                    $user_roles = $current_user->roles;
                                    ?>
                                    <?php if (in_array('author', $user_roles) || in_array('subscriber', $user_roles)): ?>
                                        <?php if ($time_difference <= 3600): ?>
                                            <a href="#" class="fred-delete-icon" title="<?php esc_attr_e('Удалить пост', 'front-editorjs'); ?>" data-post-id="<?php echo $post_id; ?>" data-current-tab="<?php echo $current_tab; ?>">
                                                <div class="fred-footer-button d-flex justify-content-center align-items-center">
                                                    <span class="fred-footer-button_icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                                                            <path stroke="none" d="M18,6h0a1,1,0,0,0-1.414,0L12,10.586,7.414,6A1,1,0,0,0,6,6H6A1,1,0,0,0,6,7.414L10.586,12,6,16.586A1,1,0,0,0,6,18H6a1,1,0,0,0,1.414,0L12,13.414,16.586,18A1,1,0,0,0,18,18h0a1,1,0,0,0,0-1.414L13.414,12,18,7.414A1,1,0,0,0,18,6Z"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="#" class="fred-delete-icon" title="<?php esc_attr_e('Удалить пост', 'front-editorjs'); ?>" data-post-id="<?php echo $post_id; ?>" data-current-tab="<?php echo $current_tab; ?>">
                                            <div class="fred-footer-button d-flex justify-content-center align-items-center">
                                                <span class="fred-footer-button_icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                                                        <path stroke="none" d="M18,6h0a1,1,0,0,0-1.414,0L12,10.586,7.414,6A1,1,0,0,0,6,6H6A1,1,0,0,0,6,7.414L10.586,12,6,16.586A1,1,0,0,0,6,18H6a1,1,0,0,0,1.414,0L12,13.414,16.586,18A1,1,0,0,0,18,18h0a1,1,0,0,0,0-1.414L13.414,12,18,7.414A1,1,0,0,0,18,6Z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (has_post_thumbnail()): ?>
                                <div class="fred-image-post col-auto">
                                    <a href="<?php echo esc_url(get_permalink()); ?>">
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
                <?php
                echo fred_render_pagination($query);
                wp_reset_postdata();
                ?>
            <?php else: ?>
                <div class="fred-no-posts mt-4"><?php echo $current_tab === 'published' ? __('Опубликованных постов нет.', 'front-editorjs') : __('Постов на рассмотрении нет.', 'front-editorjs'); ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="fred-delete-popup" id="fred-delete-popup" style="display:none;">
        <div class="fred-popup-content">
            <div class="fred-popup-content-title">
                <?php esc_html_e('Вы уверены?', 'front-editorjs'); ?>
            </div>
            <div class="fred-popup-buttons">
                <button class="fred-confirm-delete" type="button"><?php esc_html_e('Подтвердить', 'front-editorjs'); ?></button>
                <button class="fred-cancel-delete button-black" type="button"><?php esc_html_e('Отменить', 'front-editorjs'); ?></button>
            </div>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('fred_my_posts', 'fred_my_posts_shortcode');

function fred_render_pagination($query) {
    $total_pages = $query->max_num_pages;
    $paged = $query->get('paged');

    if ($total_pages > 1) {
        $pagination_html = '<div class="fred-posts-area__pagination mt-5 d-flex align-items-center justify-content-center"><nav class="navigation pagination"><div class="nav-links">';
        if (wp_is_mobile()) {
            if ($paged > 1) {
                $pagination_html .= '<a class="prev page-numbers" href="' . esc_url(add_query_arg(array('paged' => $paged - 1, 'tab' => $query->get('post_status') === 'publish' ? 'published' : 'pending'))) . '">' . __('Предыдущая', 'front-editorjs') . '</a>';
            }
            $pagination_html .= '<span class="current-page page-numbers">' . esc_html($paged) . ' ' . __('из', 'front-editorjs') . ' ' . esc_html($total_pages) . '</span>';
            if ($paged < $total_pages) {
                $pagination_html .= '<a class="next page-numbers" href="' . esc_url(add_query_arg(array('paged' => $paged + 1, 'tab' => $query->get('post_status') === 'publish' ? 'published' : 'pending'))) . '">' . __('Следующая', 'front-editorjs') . '</a>';
            }
        } else {
            $pagination_args = array(
                'total' => $total_pages,
                'current' => $paged,
                'prev_text' => __('Предыдущая', 'front-editorjs'),
                'next_text' => __('Следующая', 'front-editorjs'),
                'mid_size' => 1,
                'end_size' => 1,
            );
            $pagination_html .= paginate_links($pagination_args);
        }
        $pagination_html .= '</div></nav></div>';
        return $pagination_html;
    }

    return '';
}

function fred_my_posts_query_vars($vars) {
    $vars[] = 'tab';
    return $vars;
}
add_filter('query_vars', 'fred_my_posts_query_vars');

function fred_my_posts_rewrite_rules() {
    add_rewrite_rule(
        '^myposts/tab/([^/]*)/page/([0-9]{1,})/?$',
        'index.php?pagename=myposts&tab=$matches[1]&paged=$matches[2]',
        'top'
    );
    flush_rewrite_rules();
}
add_action('init', 'fred_my_posts_rewrite_rules');

function fred_add_rewrite_endpoints() {
    add_rewrite_endpoint('tab', EP_PERMALINK);
}
add_action('init', 'fred_add_rewrite_endpoints');

function fred_add_ajax_url() {
    ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <?php
}
add_action('wp_head', 'fred_add_ajax_url');

add_action('wp_ajax_delete_my_post', 'fred_delete_my_post');
add_action('wp_ajax_nopriv_delete_my_post', 'fred_delete_my_post');

function fred_delete_my_post() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!is_user_logged_in()) {
        wp_send_json_error(__('Вы должны быть авторизованы, чтобы удалить эту запись.', 'front-editorjs'));
    }

    if (!current_user_can('delete_posts') && (get_post_field('post_author', $post_id) != get_current_user_id() || (time() - strtotime(get_post_field('post_date', $post_id)) > 2 * HOUR_IN_SECONDS))) {
        wp_send_json_error(__('У вас нет прав на удаление этой записи.', 'front-editorjs'));
    }

    $result = wp_trash_post($post_id);

    if ($result) {
        wp_send_json_success(array('message' => __('Запись успешно перемещена в корзину.', 'front-editorjs')));
    } else {
        wp_send_json_error(array('message' => __('Не удалось переместить запись в корзину.', 'front-editorjs')));
    }

    wp_die();
}

add_filter('fred_my_posts_orderby', function ($args, $current_tab) {
    $args['orderby'] = 'date';
    $args['order'] = 'DESC';
    return $args;
}, 10, 2);