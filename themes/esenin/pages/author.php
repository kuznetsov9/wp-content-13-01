<?php
/**
 * The template for displaying all authors pages.
 *
 * @package Esenin
 */

get_header(); 

$subtitle  = esc_html__( 'All Posts By', 'esenin' );
$author_id = get_queried_object_id();
$position  = get_user_meta( $author_id, 'esn_position', true );
$location  = get_user_meta( $author_id, 'esn_location', true );
$num_posts = count_user_posts( $author_id );

$options = esn_get_archive_options();
$grid_columns_desktop = $options['columns'];
$columns_desktop = 'es-desktop-column-' . $grid_columns_desktop;
$main_classes = ' es-posts-area__' . $options['location'] . ' es-posts-area__' . $options['layout'];
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

	
<div class="upc">
<div class="profile-rank">
<?php echo do_shortcode('[user_karma_rank id="' . $author_id . '"]'); ?>
<?php echo do_shortcode('[user_karma_popup id="' . $author_id . '"]'); ?>
</div>

<div class="profile-cover">
</div>

 <div class="profile-header d-flex align-items-start justify-content-between">
   <div class="profile-avatar">
    <?php echo get_avatar( $author_id, 100 ); ?>	
	    <?php echo do_shortcode('[user_online user_id="' . $author_id . '"]'); ?>	
   </div>

   <div class="profile-btn-subscribe">
     <?php if ( get_current_user_id() != $author_id ) : ?>
                             <?php echo do_shortcode('[subscription_button user_id="' . $author_id . '"]'); ?>
                         <?php else : ?>
							<a href="/edit-profile">
							<button class="user-subscribe-button my-profile-link">
							<?php echo esc_attr__( 'Настройки', 'esenin' ); ?> 
							</button>
							</a>							
     <?php endif; ?> 
   </div>  
 </div>
 <div class="profile-name">
                <?php the_archive_title( '<h1>', '</h1>' ); ?>
					<?php if ( $position && ! empty( $position ) ) { ?>
						<div class="profile-interest"><?php echo esc_html( $position ); ?></div>
					<?php } ?>
 </div>
 <div class="profile-stats">
 <?php if ( count_user_posts( $author_id ) == 0 ) : ?>
    <?php 
		//echo esc_attr__( 'Статей нет', 'esenin' );
		echo "";
      else : ?>
     <div class="profile-count-posts">
			<span class="fw-bold"><?php echo $num_posts; ?></span>
								<?php echo num_decline( $num_posts, 'статья,статьи,статей', 0 ); ?>
	 </div>
 <?php endif; ?>
						   
		 <?php echo do_shortcode('[subscribers_list user_id="' . $author_id . '"]'); ?>
         <?php echo do_shortcode('[subscriptions_list user_id="' . $author_id . '"]'); ?>		 
 </div>
 


<?php
$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'posts'; 
$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;


$posts_per_page = get_option('posts_per_page', 10); 
$comments_per_page = get_option('posts_per_page', 10);



function get_author_posts($author_id, $paged, $posts_per_page) {
    $orderby = 'date'; 
    $order = 'DESC'; 
    $date_query = array();

    if (isset($_GET['sort'])) {
        switch ($_GET['sort']) {
            case 'new':
                $orderby = 'date';
                break;
            case 'popular':
                $orderby = 'meta_value_num';
                $order = 'DESC'; 
                $meta_key = 'popularity';
                break;
            case 'month':
                $orderby = 'meta_value_num';
                $order = 'DESC';
                $meta_key = 'popularity';
                $date_query[] = array('after' => '1 month ago');
                break;
            case 'year':
                $orderby = 'meta_value_num';
                $order = 'DESC';
                $meta_key = 'popularity';
                $date_query[] = array('after' => '1 year ago');
                break;
        }
    }

    $args = array(
        'author' => $author_id,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'orderby' => $orderby,
        'order' => $order,
    );

    if (!empty($date_query)) {
        $args['date_query'] = $date_query; 
    }

    if (isset($meta_key)) {
        $args['meta_key'] = $meta_key;
    }

    return new WP_Query($args);
}


if ($current_tab === 'posts') {
    $author_posts = get_author_posts($author_id, $current_page, $posts_per_page);
    $total_posts = $author_posts->found_posts;
    $total_pages = ceil($total_posts / $posts_per_page);
}


function get_author_comments($author_id, $paged, $comments_per_page) {
    return get_comments(array(
        'user_id' => $author_id,
        'paged' => $paged,
        'number' => $comments_per_page,
        'status' => 'approve', 
        'order' => 'DESC', 
    ));
}


if ($current_tab === 'comments') {
    $comments = get_author_comments($author_id, $current_page, $comments_per_page);
    $total_comments = get_comments(array('user_id' => $author_id, 'count' => true));
    $total_pages_comments = ceil($total_comments / $comments_per_page);
}


$user_info = get_userdata($author_id);
$location = get_user_meta($author_id, 'esn_location', true);
$social_links = array(
    'vk' => get_user_meta($author_id, 'esn_vk', true),
    'telegram' => get_user_meta($author_id, 'esn_telegram', true),
    'instagram' => get_user_meta($author_id, 'esn_instagram', true),
    'tiktok' => get_user_meta($author_id, 'esn_tiktok', true),
    'github' => get_user_meta($author_id, 'esn_github', true),
    'youtube' => get_user_meta($author_id, 'esn_youtube', true),
);
?>

<div class="profile-tabs">
    <ul class="nav nav-underline">
        <li class="nav-item">
            <a class="nav-link <?php echo $current_tab === 'posts' ? 'active' : ''; ?>" 
               href="<?php echo esc_url(get_author_posts_url($author_id)); ?>?tab=posts">
                <?php esc_html_e('Посты', 'esenin'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_tab === 'comments' ? 'active' : ''; ?>" 
               href="<?php echo esc_url(get_author_posts_url($author_id)); ?>?tab=comments">
                <?php esc_html_e('Комментарии', 'esenin'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo $current_tab === 'about' ? 'active' : ''; ?>" 
               href="<?php echo esc_url(get_author_posts_url($author_id)); ?>?tab=about">
                <?php esc_html_e('О себе', 'esenin'); ?>
            </a>
        </li>
    </ul>
</div>

</div>

<div class="profile-tabs-content">
    <?php if ($current_tab === 'posts'): ?>
	<?php
	  // Sort Authors
       $author_id = get_queried_object_id();
       $post_count = count_user_posts($author_id);
         if ($post_count >= 2) {
             get_template_part('inc/filter/in_themes--authors');
           } ?>
        <div class="es-posts-area es-posts-area-posts">
            <div class="es-posts-area__outer">
                <div class="es-posts-area__main es-archive-<?php echo esc_attr($options['layout']); ?> <?php echo esc_attr($main_classes); ?>">
                    <?php if ($author_posts->have_posts()): ?>
                        <?php while ($author_posts->have_posts()): $author_posts->the_post(); ?>
                            <?php set_query_var('options', $options);
                            $template_part = 'template-parts/archive/content-full'; 
                            if ('overlay' === $options['layout']) {
                                $template_part = 'template-parts/archive/entry-overlay';
                            } elseif ('full' !== $options['layout']) {
                                $template_part = 'template-parts/archive/entry';
                            }
                            get_template_part($template_part);
                            endwhile; ?>

<?php 
// Standart Pagination (Author -> Posts)
if ( 'standard' === get_theme_mod( esn_get_archive_option( 'pagination_type' ), 'standard' ) ) : ?>
                        <div class="es-posts-area__pagination mt-2">   
                            <nav class="navigation pagination">
                                <div class="nav-links">
                                    <?php
									if ($total_pages > 1) {
                                        if (wp_is_mobile()) {
	                                     
                                          if ($current_page > 1) {
                                              echo '<a class="prev page-numbers current" href="?tab=posts&page=' . ($current_page - 1) . '">' . __('Previous', 'esenin') . '</a>';
                                            }

                                          echo '<span class="current-page page-numbers current">' . $current_page . ' ' . __('for ', 'esenin') . ' ' . $total_pages . '</span>';

                                          if ($current_page < $total_pages) {
                                              echo '<a class="next page-numbers current" href="?tab=posts&page=' . ($current_page + 1) . '">' . __('Next', 'esenin') . '</a>';
                                            }
                                       
	                              } else {
                                    echo paginate_links(array(
                                        'total' => $total_pages,
                                        'current' => $current_page,
                                        'format' => '?tab=posts&page=%#%',
                                        'prev_text' => __( 'Previous', 'esenin' ),
			                            'next_text' => __( 'Next', 'esenin' ),
										'mid_size' => 1,
                                        'end_size' => 1,
                                    ));
									}
                                   }
                                    ?>
                                </div> 
                            </nav> 
                        </div>
<?php endif; ?>
                    
					<?php else: ?>
                        <div class="es-posts-area__no-posts">
                            <?php esc_html_e('Здесь пока ничего нет', 'esenin'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php wp_reset_postdata(); ?>

    <?php elseif ($current_tab === 'comments'): ?>
        <div class="es-posts-area es-posts-area-posts">
            <div class="es-posts-area__outer">
                <div class="es-posts-area__main es-archive-<?php echo esc_attr($options['layout']); ?> <?php echo esc_attr($main_classes); ?>">
                    <?php if ($comments): ?>
                        <?php foreach ($comments as $comment): ?>
                            <?php $post = get_post($comment->comment_post_ID);
							$is_deleted = get_comment_meta($comment->comment_ID, 'deleted', true);
                            if ($post): ?>
                            <div class="profile-card-comment">
                                <div class="d-flex flex-start align-items-center pcc-avatar">             
                                    <?php echo get_avatar($comment->comment_author_email, 100); ?>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="pcc-name"><?php echo esc_html($comment->comment_author); ?></span> 
                                            <span class="pcc-in-post"><?php esc_html_e('в посте', 'esenin'); ?></span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <a class="pcc-title-post" href="<?php echo esc_url(get_permalink($post->ID)); ?>" title="<?php echo esc_html(get_the_title($post->ID)); ?>">
                                                <?php echo esc_html(get_the_title($post->ID)); ?>
                                            </a>
                                            <span class="pcc-date"><?php echo esc_html(get_comment_date('', $comment)); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
								<?php if ($is_deleted) { ?>
                                   <div class="pcc-content"><?php esc_html_e('Комментарий удалён..', 'esenin'); ?> 😒</div>
                                <?php } else { ?>
                                   <div class="pcc-content"><?php echo esc_html($comment->comment_content); ?></div>
					            <?php } ?>
                                
								<?php if (!$is_deleted) : ?>
								<div class="d-flex justify-content-start align-items-center gap-3">
								    <?php if ( is_user_logged_in() ) { ?>
                                         <?php echo do_shortcode('[like_comment_button id="'.esc_attr($comment->comment_ID).'"]'); ?>
                                    <?php } else { ?>
                                         <a href="<?php echo wp_login_url(); ?>"><?php echo do_shortcode('[like_comment_button id="'.esc_attr($comment->comment_ID).'"]'); ?></a>
                                    <?php } ?>
								
								
                                    <a class="pcc-reply" href="<?php comment_link(); ?>" class="d-flex align-items-center me-3">
                                        <?php esc_html_e('Ответить', 'esenin'); ?>
                                    </a>
                                </div>
								<?php endif; ?>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <div class="es-posts-area__pagination mt-2">   
                            <nav class="navigation pagination">
                                <div class="nav-links">
                                    <?php
                                    
									$current_url = home_url(add_query_arg(array(), $wp->request));
						
                              if (wp_is_mobile()) {
								   
                                 if ($current_page > 1): ?>
                                    <a class="prev page-numbers current" href="<?php echo esc_url($current_url . '?tab=comments&page=' . ($current_page - 1)); ?>"><?php esc_html_e('Previous', 'esenin'); ?></a>
                                <?php endif; ?>

                                <span class="page-numbers current">
                                    <?php echo $current_page . ' ' . __('for ', 'esenin') . ' ' . $total_pages_comments; ?>
                                </span>

                                <?php if ($current_page < $total_pages_comments): ?>
                                     <a class="next page-numbers current" href="<?php echo esc_url($current_url . '?tab=comments&page=' . ($current_page + 1)); ?>"><?php esc_html_e('Next', 'esenin'); ?></a>
                                <?php endif;

								   
								  } else {
									
                                    if ($current_page > 1) {
                                        echo '<a class="prev page-numbers" href="' . esc_url($current_url . '?tab=comments&page=' . ($current_page - 1)) . '">' . __('Previous', 'esenin') . '</a> ';
                                    }
                                    if ($total_pages_comments > 1) {
                                        for ($i = 1; $i <= $total_pages_comments; $i++) {
                                            if ($i === $current_page) {
                                                echo '<span aria-current="page" class="page-numbers current">' . $i . '</span> ';
                                            } else {
                                                echo '<a class="page-numbers" href="' . esc_url($current_url . '?tab=comments&page=' . $i) . '">' . $i . '</a> ';
                                            }
                                        }
                                    }
                                    if ($current_page < $total_pages_comments) {
                                        echo '<a class="next page-numbers" href="' . esc_url($current_url . '?tab=comments&page=' . ($current_page + 1)) . '">' . __('Next', 'esenin') . '</a>';
                                    }
									
							       } 
                                    ?>
                                </div> 
                            </nav> 
                        </div>
                    <?php else: ?>
                        <div class="es-posts-area__no-posts">
                            <?php esc_html_e('Пользователь не комментировал', 'esenin'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

   <?php elseif ($current_tab === 'about'): ?>
    <div class="profile-card-about">
        <?php if (!empty($user_info->description) || !empty($location) || !empty(array_filter($social_links))): ?>		   
            <?php if ($location): ?>                
				<div class="es-page__author-location pca-info"><i class="es-icon es-icon-location-fill"></i> <?php echo esc_attr( $location ); ?></div>
            <?php endif; ?>
			<?php if ($user_info->description): ?>
			<div class="pca-description pca-info">			 
			<?php echo esc_html($user_info->description); ?>
			</div>
			<?php endif; ?>
			<?php if (array_filter($social_links)): ?>
            <div class="profile-social-links pca-info">
                <?php foreach ($social_links as $platform => $link): ?>
                    <?php if ($link): ?>
                        <a href="<?php echo esc_url($link); ?>" title="<?php echo $platform; ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/social/' . $platform . '.svg'); ?>" alt="<?php echo esc_attr(ucfirst($platform)); ?>">
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
			<?php endif; ?>
        <?php else: ?>
            <div class="es-posts-area__no-posts p-0">
                            <?php esc_html_e('Данные отсутствуют', 'esenin'); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
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
