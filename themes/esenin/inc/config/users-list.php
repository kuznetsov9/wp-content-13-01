<?php

// All Users for Esenin

function esn_user_listing($atts, $content = null) {
    global $post;

    extract(shortcode_atts(array(
        "role" => '',
        "number" => '10'
    ), $atts));

    $role = sanitize_text_field($role);
    $number = sanitize_text_field($number);

    ob_start();

    $search = ( isset($_GET["as"]) ) ? sanitize_text_field($_GET["as"]) : false;

    $page = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $offset = ($page - 1) * $number;

    $args = array(
        'number' => $number,
        'offset' => $offset,
        'orderby' => 'registered',
        'order' => 'DESC',
        'role__not_in' => array('pending') 
    );

    if ($search) {
        $args['search'] = '*' . $search . '*';
        $args['role'] = $role;
    }

    $my_users = new WP_User_Query($args);
    $total_authors = $my_users->total_users;
    $total_pages = intval($total_authors / $number) + 1;

    $authors = $my_users->get_results();
?>
    <div class="all-users-header">
       <span class="all-users-title">    
       <?php echo esc_attr__( 'Пользователи', 'esenin' ); ?>
       </span>
       <span class="all-users-counts text-success"><?php echo $total_authors; ?></span>
    </div>

    <div class="author-search">        
        <div class="search-container position-relative">
            <form class="d-flex align-items-center" method="get" id="sul-searchform" action="<?php the_permalink() ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon feather feather-search">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input class="form-control search-input ps-5 field" type="text" name="as" id="sul-s" placeholder="<?php echo esc_attr__( 'Поиск пользователя', 'esenin' ); ?>">
                <button class="btn btn-search ms-2 submit" type="submit" name="submit" id="sul-searchsubmit"><?php echo esc_attr__( 'Найти', 'esenin' ); ?></button>
            </form>
        </div>

    <?php 
    if($search){ ?>
        <div class="card-search-result d-flex flex-column gap-3 text-center"> 
            <h4 class="mb-0"><?php echo esc_attr__( 'Результат поиска:', 'esenin' ); ?> <em><?php echo $search; ?></em></h4>
            <a href="<?php the_permalink(); ?>"><?php echo esc_attr__( 'Вернуться к списку', 'esenin' ); ?></a>  
        </div>
    <?php } ?>

    </div><!-- .author-search -->

<?php if (!empty($authors)) { ?>
    <div class="author-list team-boxed row people">
<?php
    foreach($authors as $author) {
        $author_info = get_userdata($author->ID);
        $num_posts = count_user_posts($author->ID);
        $author_id = $author->ID;
        ?>

        <div class="col-md-6 user-card-item">
            <div class="user-card-box">
                <a href="<?php echo get_author_posts_url($author->ID); ?>">
                    <?php echo get_avatar($author->ID, 100, '', '', array('class' => 'rounded-circle')); ?>
                    <?php echo do_shortcode('[user_online user_id="' . $author_id . '"]'); ?>                    
                </a>
                <div class="user-card-name">
                    <a href="<?php echo get_author_posts_url($author->ID); ?>"><?php echo $author_info->display_name; ?></a>
                </div>
                <div class="user-card-info d-flex justify-content-center align-items-center gap-3">                        
                    <?php if (count_user_posts($author->ID) == 0) : ?>
                        <?php echo ""; ?>
                    <?php else : ?>
                        <div class="user-post-count">
                            <span class="fw-bold"><?php echo $num_posts; ?></span>
                            <?php echo num_decline($num_posts, 'статья,статьи,статей', 0); ?>
                        </div>
                    <?php endif; ?>
                 <?php $subscribers_count = do_shortcode('[subscribers_count user_id="' . $author_id . '"]');
                       $subscriptions_count = do_shortcode('[subscriptions_count user_id="' . $author_id . '"]');

                       if (empty($subscribers_count) && empty($subscriptions_count)) {
                           echo '<span class="esn-no-activity">' . __('Не активен', 'esenin') . '</span>';
                             } else {
                       if (!empty($subscribers_count)) {
                           echo do_shortcode('[subscribers_count user_id="' . $author_id . '"]');
                             }
                       if (!empty($subscriptions_count)) {
                           echo do_shortcode('[subscriptions_count user_id="' . $author_id . '"]');
                             }
                        } ?>
                </div>
                <div> 
                    <?php if (get_current_user_id() != $author_id) : ?>
                        <?php echo do_shortcode('[subscription_button user_id="' . $author_id . '"]'); ?>
                    <?php else : ?>
                        <a href="/author/<?php global $current_user; wp_get_current_user(); echo $current_user->user_login;?>">
                            <button class="user-subscribe-button my-profile-link">
                                <?php echo esc_attr__( 'Мой профиль', 'esenin' ); ?> 
                            </button>
                        </a>                            
                    <?php endif; ?> 
                </div>                    
            </div>
        </div>
        <?php
    }
?>
    </div> 
<?php } else { ?>
    <h5><?php echo esc_attr__( 'Пользователи не найдены..', 'esenin' ); ?></h5>
<?php } //endif ?>

     <nav id="nav-single" class="user-card-nav">
        <?php if ($page != 1) { ?>
            <a rel="prev" href="<?php the_permalink() ?>/page/<?php echo $page - 1; ?>/">
                <span class="nav-previous">             
                    <span class="meta-nav">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22"><path d="M10.6,12.71a1,1,0,0,1,0-1.42l4.59-4.58a1,1,0,0,0,0-1.42,1,1,0,0,0-1.41,0L9.19,9.88a3,3,0,0,0,0,4.24l4.59,4.59a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.42Z"/></svg>
                    </span> 
                    <?php echo esc_attr__( 'Назад', 'esenin' ); ?>              
                </span>
            </a> 
        <?php } ?>

        <?php if ($page < $total_pages) { ?>
            <a rel="next" href="<?php the_permalink() ?>/page/<?php echo $page + 1; ?>/">
                <span class="nav-next">              
                    <?php echo esc_attr__( 'Дальше', 'esenin' ); ?>
                    <span class="meta-nav">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22"><path d="M15.4,9.88,10.81,5.29a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L14,11.29a1,1,0,0,1,0,1.42L9.4,17.29a1,1,0,0,0,1.41,1.42l4.59-4.59A3,3,0,0,0,15.4,9.88Z"/></svg>
                    </span>              
                </span>
            </a>
        <?php } ?>
    </nav> 

<?php 
    $output = ob_get_contents();
    ob_end_clean();

    if (is_page()) return $output;
}

add_shortcode('esn-all-users', 'esn_user_listing');