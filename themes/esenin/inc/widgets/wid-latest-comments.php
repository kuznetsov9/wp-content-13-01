<?php

function register_recent_comments_widget() {
    class Recent_Comments_Widget extends WP_Widget {
        public function __construct() {
            parent::__construct(
                'recent_comments_widget',
                __('(ESENIN) Комментарии', 'esenin'),
				array('description' => __('Виджет с выводом последних комментариев с поддержкой AJAX обновлений', 'esenin'))
            );

            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_footer', array($this, 'add_ajax_script'));
        }

        public function widget($args, $instance) {
            $number = !empty($instance['number']) ? intval($instance['number']) : 5;
            $title = !empty($instance['title']) ? sanitize_text_field($instance['title']) : __('Обсуждают', 'esenin');
            $use_ajax = !empty($instance['use_ajax']) ? (bool)$instance['use_ajax'] : false;
            $update_interval = !empty($instance['update_interval']) ? intval($instance['update_interval']) : 5; 
			

            echo $args['before_widget'];
            if (!empty($title)) {
                echo $args['before_title'] . esc_html($title) . $args['after_title'];
            }

            echo '<div class="recent-comments" id="recent-comments">';
            $this->load_comments($number);
            echo '</div>';

            if ($use_ajax) {
                echo '<div class="loading" style="display: none;">' . __('Загрузка комментариев..', 'esenin') . '</div>';
                echo '<input type="hidden" id="ajax_update_interval" value="' . esc_attr($update_interval) . '">'; 
                echo '<input type="hidden" id="last_comment_time" value="' . current_time('mysql') . '">';
            }

            echo $args['after_widget'];
        }

        public function load_comments($number) {
            $args = array(
                'number' => $number,
                'status' => 'approve',
                'post_status' => 'publish',
                'orderby' => 'comment_date',
                'order' => 'DESC',
            );
           
            $comments = get_comments($args);
            if ($comments) {
                foreach ($comments as $comment) {
                    $post = get_post($comment->comment_post_ID); 
					$is_deleted = get_comment_meta($comment->comment_ID, 'deleted', true);
					?>
                    <div class="comment-item" data-comment-id="<?php echo esc_attr($comment->comment_ID); ?>" data-comment-time="<?php echo esc_attr($comment->comment_date); ?>">                                    
                        <div class="d-flex flex-start align-items-center pcc-avatar">             
                            <?php echo get_avatar($comment->comment_author_email, 100); ?>                                
                        <div>                                        
                            <div class="d-flex align-items-center gap-2">
                                <span class="pcc-name"><a href="<?php echo get_author_posts_url( $comment->user_id ); ?>"><?php echo esc_html($comment->comment_author); ?></a></span>                                        
                                <span class="pcc-in-post"><?php esc_html_e('в посте', 'esenin'); ?></span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <a class="pcc-title-post" href="<?php echo esc_url(get_permalink($post->ID)); ?>" title="<?php echo esc_html(get_the_title($post->ID)); ?>">
                                    <?php echo esc_html(get_the_title($post->ID)); ?>
                                </a>
                                
                            </div>
                        </div>
                    </div>
					
					<?php if ($is_deleted) { ?>
                      <div class="pcc-content"><?php esc_html_e('Комментарий удалён..', 'esenin'); ?> 😒</div>
                    <?php } else { ?>
                      <div class="pcc-content"><?php echo esc_html($comment->comment_content); ?></div>
					<?php } ?>
					
					<div class="d-flex justify-content-between align-items-center">
					<?php if (!$is_deleted) : ?>
                    <div class="comment-item-footer d-flex align-items-center justify-content-start gap-3">
					    <?php if ( is_user_logged_in() ) { ?>
                             <?php echo do_shortcode('[like_comment_button id="'.esc_attr($comment->comment_ID).'"]'); ?>
	                    <?php } else { ?>
	                         <?php echo '<a href="' . wp_login_url() . '">' . do_shortcode('[like_comment_button id="'.esc_attr($comment->comment_ID).'"]') . '</a>'; ?>
                        <?php } ?>                
					    <a class="pcc-reply" href="<?php echo get_permalink($comment->comment_post_ID) . "#comment-" . $comment->comment_ID ?>" class="d-flex align-items-center me-3">
                            <?php esc_html_e('Ответить', 'esenin'); ?>
                        </a>
                    </div>
					 <?php endif; ?>
					 <span class="pcc-date"><?php echo human_time_diff(strtotime($comment->comment_date_gmt), current_time('timestamp', 1)); ?></span>
					 </div>
                    </div>
               <?php }
            }
        }

        public function enqueue_scripts() {
            wp_enqueue_script('jquery');
            wp_localize_script('jquery', 'ajax_comments', array(
                'ajax_url' => admin_url('admin-ajax.php'),
            ));
            wp_enqueue_script('like-comment-system-ajax', get_template_directory_uri() . '/assets/static/js/likers-comments.js', array('jquery'), null, true);
        }

        public function add_ajax_script() {
            ?>
            <script type="text/javascript">
                (function($) {
                    let lastCommentTime = $('#last_comment_time').val(); 
                    let ajaxUpdateInterval = $('#ajax_update_interval').val() * 1000; 

                    function load_new_comments() {
                        $.ajax({
                            url: ajax_comments.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'load_new_comments',
                                last_comment_time: lastCommentTime,
                            },
                            success: function(data) {
                                if (data) {
                                    let newComments = $(data);
                                    newComments.each(function() {
                                        let commentId = $(this).data('comment-id');

                                        initialize_like_button($(this));

                                        if ($('#recent-comments .comment-item[data-comment-id="' + commentId + '"]').length === 0) {
                                            $(this).prependTo('#recent-comments'); 
                                            lastCommentTime = $(this).data('comment-time'); 
                                        }
                                    });
                                }
                            }
                        });
                    }

                    function initialize_like_button(commentElement) {
                        commentElement.find('.like-comm-btn').on('click', function() {
                            var commentId = commentElement.data('comment-id');

                            $.ajax({
                                type: 'POST',
                                url: ajax_comments.ajax_url, 
                                data: {
                                    action: 'like_comment_system_handle_like',
                                    comment_id: commentId
                                },
                                success: function(response) {
                                    if (response.success) {
                                        var likeCountElement = commentElement.find('.like-count');
                                        var likeCount = parseInt(likeCountElement.text());

                                        if (response.data.action === 'remove') {
                                            $(this).removeClass('liked');
                                            likeCount--;
                                        } else {
                                            $(this).addClass('liked');
                                            likeCount++;
                                        }
                                        likeCountElement.text(likeCount);
                                    } else {
                                        alert(response.data);
                                    }
                                }.bind(this)
                            });
                        });

                        commentElement.find('.like-count').on('mouseenter', function() {
                            var commentId = commentElement.data('comment-id');
                            updateVoters(commentId);
                        });
                    }

                    function updateVoters(commentId) {
                        $.ajax({
                            type: 'POST',
                            url: ajax_comments.ajax_url,
                            data: {
                                action: 'like_comment_system_get_voters',
                                comment_id: commentId
                            },
                            success: function(response) {
                                if (response.success) {
                                    var dropdownContent = $('.like-comment-button[data-comment-id="' + commentId + '"]').find('.dropdown-content');
                                    dropdownContent.html(response.data.votersHtml);
                                    dropdownContent.show();
                                }
                            }
                        });
                    }

                    $(document).on('mouseleave', '.like-comment-button', function() {
                        $(this).find('.dropdown-content').hide();
                    });

                    if (ajaxUpdateInterval) {
                        setInterval(load_new_comments, ajaxUpdateInterval);
                    }
                })(jQuery);
            </script>
            <?php
        }

        public function form($instance) {
            $number = !empty($instance['number']) ? intval($instance['number']) : 5;
            $title = !empty($instance['title']) ? sanitize_text_field($instance['title']) : __('Обсуждают', 'esenin');
            $use_ajax = !empty($instance['use_ajax']) ? (bool)$instance['use_ajax'] : false;
            $update_interval = !empty($instance['update_interval']) ? intval($instance['update_interval']) : 5;
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Заголовок:', 'esenin'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Количество:', 'esenin'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" min="1" value="<?php echo esc_attr($number); ?>" />
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($use_ajax); ?> id="<?php echo $this->get_field_id('use_ajax'); ?>" name="<?php echo $this->get_field_name('use_ajax'); ?>" />
                <label for="<?php echo $this->get_field_id('use_ajax'); ?>"><?php _e('Использовать AJAX обновление?', 'esenin'); ?></label>
            </p>
            <p class="ajax-update-interval" style="<?php echo $use_ajax ? '' : 'display: none;'; ?>">
                <label for="<?php echo $this->get_field_id('update_interval'); ?>"><?php _e('Интервал обновления (секунд):', 'esenin'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('update_interval'); ?>" name="<?php echo $this->get_field_name('update_interval'); ?>" type="number" min="1" value="<?php echo esc_attr($update_interval); ?>" />
            </p>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('#<?php echo $this->get_field_id('use_ajax'); ?>').change(function() {
                        $('.ajax-update-interval').toggle(this.checked);
                    }).change(); 
                });
            </script>
            <?php
        }
    }

    function load_new_comments() {
        $last_comment_time = isset($_POST['last_comment_time']) ? sanitize_text_field($_POST['last_comment_time']) : '';

        $args = array(
            'status' => 'approve',
            'post_status' => 'publish',
            'orderby' => 'comment_date',
            'order' => 'DESC',
            'date_query' => array(
                array(
                    'after' => date('Y-m-d H:i:s', strtotime($last_comment_time)),
                    'inclusive' => true,
                ),
            ),
        );

        $comments = get_comments($args);
        if ($comments) {
            foreach ($comments as $comment) {
                $post = get_post($comment->comment_post_ID);
				$is_deleted = get_comment_meta($comment->comment_ID, 'deleted', true); ?>                                                                                                    
                <div class="comment-item" data-comment-id="<?php echo esc_attr($comment->comment_ID); ?>" data-comment-time="<?php echo esc_attr($comment->comment_date); ?>">                                   
                    <div class="d-flex flex-start align-items-center pcc-avatar">             
                        <?php echo get_avatar($comment->comment_author_email, 100); ?>                                
                    <div>                                        
                        <div class="d-flex align-items-center gap-2">
                            <span class="pcc-name"><a href="<?php echo get_author_posts_url( $comment->user_id ); ?>"><?php echo esc_html($comment->comment_author); ?></a></span>                                        
                            <span class="pcc-in-post"><?php esc_html_e('в посте', 'esenin'); ?></span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <a class="pcc-title-post" href="<?php echo esc_url(get_permalink($post->ID)); ?>" title="<?php echo esc_html(get_the_title($post->ID)); ?>">
                                <?php echo esc_html(get_the_title($post->ID)); ?>
                            </a>
                        </div>
                    </div>
                </div>
                    <?php if ($is_deleted) { ?>
                      <div class="pcc-content"><?php esc_html_e('Комментарий удалён..', 'esenin'); ?> 😒</div>
                    <?php } else { ?>
                      <div class="pcc-content"><?php echo esc_html($comment->comment_content); ?></div>
					<?php } ?>
				<div class="d-flex justify-content-between align-items-center">	
				 <?php if (!$is_deleted) : ?>
                <div class="comment-item-footer d-flex align-items-center justify-content-start gap-3">
                    <?php if ( is_user_logged_in() ) { ?>
                             <?php echo do_shortcode('[like_comment_button id="'.esc_attr($comment->comment_ID).'"]'); ?>
	                    <?php } else { ?>
	                         <?php echo '<a href="' . wp_login_url() . '">' . do_shortcode('[like_comment_button id="'.esc_attr($comment->comment_ID).'"]') . '</a>'; ?>
                    <?php } ?>
                    <a class="pcc-reply" href="<?php echo get_permalink($comment->comment_post_ID) . "#comment-" . $comment->comment_ID ?>" class="d-flex align-items-center me-3">
                        <?php esc_html_e('Ответить', 'esenin'); ?>
                    </a>
                </div>
				  <?php endif; ?>
				  <span class="pcc-date"><?php echo human_time_diff(strtotime($comment->comment_date_gmt), current_time('timestamp', 1)); ?></span>
                </div>
                </div>
               <?php }
        }
        wp_die();
    }

    register_widget('Recent_Comments_Widget');
}

add_action('widgets_init', 'register_recent_comments_widget');
add_action('wp_ajax_load_new_comments', 'load_new_comments');
add_action('wp_ajax_nopriv_load_new_comments', 'load_new_comments');