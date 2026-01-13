<?php

function create_user_karma_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_karma';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        user_id bigint(20) NOT NULL,
        karma int(11) DEFAULT 0,
        PRIMARY KEY  (user_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'create_user_karma_table');

function update_user_karma($user_id, $points) {
    // === ВОТ ЭТА ЗАЩИТА ===
    // Если ID юзера нет, или он равен 0 (гость), или меньше 0 — шлём его лесом
    if (empty($user_id) || $user_id <= 0) {
        return;
    }
    // ======================

    global $wpdb;
    $table_name = $wpdb->prefix . 'user_karma';

    $current_karma = $wpdb->get_var($wpdb->prepare("SELECT karma FROM $table_name WHERE user_id = %d", $user_id));

    if (is_null($current_karma)) {
        $wpdb->insert($table_name, array('user_id' => $user_id, 'karma' => $points));
    } else {
        $wpdb->update($table_name, array('karma' => $current_karma + $points), array('user_id' => $user_id));
    }
}

add_action('wp_insert_post', 'increment_user_karma_on_post', 10, 2);
function increment_user_karma_on_post($post_id, $post) {
    if ($post->post_type == 'post' && $post->post_status == 'publish') {
        $user_id = $post->post_author;
        update_user_karma($user_id, 10);
    } 
}

add_action('wp_trash_post', 'decrement_user_karma_on_post', 10);
add_action('transition_post_status', 'handle_post_status_changes', 10, 3);

function decrement_user_karma_on_post($post_id) {
    $post = get_post($post_id);
    if ($post->post_type == 'post' && $post->post_status == 'publish') {
        $user_id = $post->post_author;
        update_user_karma($user_id, -10);
    }
}

function handle_post_status_changes($new_status, $old_status, $post) {
    if ($post->post_type == 'post') {
        $user_id = $post->post_author;
        if ($new_status !== 'publish' && $old_status === 'publish') {
            update_user_karma($user_id, -10); // Уменьше карму на 10
        }
    }
}

add_action('wp_insert_comment', 'increment_user_karma_on_comment', 10, 2);
function increment_user_karma_on_comment($comment_id, $comment) {
    $user_id = $comment->user_id;
    update_user_karma($user_id, 2);
}

add_action('wp_set_comment_status', 'update_user_karma_on_comment_status_change', 10, 2);
function update_user_karma_on_comment_status_change($comment_id, $status) {
    $comment = get_comment($comment_id);
    $user_id = $comment->user_id;

    if ($status == 'approved') {
        if ($comment->comment_approved == '0') {
            update_user_karma($user_id, 2);
        }
    } elseif ($status == 'spam' || $status == 'trash' || $status == 'deleted') {
        update_user_karma($user_id, -2);
    } elseif ($status == 'pending') {
        if ($comment->comment_approved == '1') {
            update_user_karma($user_id, -2);
        }
    }
}

function display_user_karma($atts) {
    global $wpdb;
    $user_id = intval($atts['id']);
    $table_name = $wpdb->prefix . 'user_karma';

    $karma = $wpdb->get_var($wpdb->prepare("SELECT karma FROM $table_name WHERE user_id = %d", $user_id));

    if ($karma === null) {
        return '';
    }

    $karma_color = 'var(--es-color-primary)';
    if ($karma > 0) {
        $karma_color = '#00ad64'; 
    } elseif ($karma < 0) {
        $karma_color = '#ff2056'; 
    }

    $current_user = wp_get_current_user();
    $author_id = get_queried_object_id();
    $title_karma = ($current_user->ID === $author_id) ? esc_html__('Ваш рейтинг', 'esenin') : esc_html__('Рейтинг пользователя', 'esenin');
    $svg_icon = '<svg class="icon-karma-profile" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M1.327,12.4,4.887,15,3.535,19.187A3.178,3.178,0,0,0,4.719,22.8a3.177,3.177,0,0,0,3.8-.019L12,20.219l3.482,2.559a3.227,3.227,0,0,0,4.983-3.591L19.113,15l3.56-2.6a3.227,3.227,0,0,0-1.9-5.832H16.4L15.073,2.432a3.227,3.227,0,0,0-6.146,0L7.6,6.568H3.231a3.227,3.227,0,0,0-1.9,5.832Z"></path></svg>';

    $karma_display = abs($karma);
    return '<div title="' . $title_karma . '" class="karma" style="color: ' . esc_attr($karma_color) . ';">' . $svg_icon . '<div class="karma-count">' . esc_html($karma_display) . '</div></div>';
}

add_shortcode('user_karma', 'display_user_karma');

function display_user_karma_popup($atts) {
    global $wpdb;
    $user_id = intval($atts['id']);
    $table_name = $wpdb->prefix . 'user_karma';

    $karma = $wpdb->get_var($wpdb->prepare("SELECT karma FROM $table_name WHERE user_id = %d", $user_id));

    if ($karma === null) {
        return '';
    }

    $karma_color = 'var(--es-color-primary)';
    if ($karma > 0) {
        $karma_color = '#00ad64';
    } elseif ($karma < 0) {
        $karma_color = '#ff2056';
    }

    $current_user = wp_get_current_user();
    $author_id = get_queried_object_id();
    $title_karma = ($current_user->ID === $author_id) ? esc_html__('Ваш рейтинг', 'esenin') : esc_html__('Рейтинг', 'esenin');
    $svg_icon = '<svg class="icon-karma-profile" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M1.327,12.4,4.887,15,3.535,19.187A3.178,3.178,0,0,0,4.719,22.8a3.177,3.177,0,0,0,3.8-.019L12,20.219l3.482,2.559a3.227,3.227,0,0,0,4.983-3.591L19.113,15l3.56-2.6a3.227,3.227,0,0,0-1.9-5.832H16.4L15.073,2.432a3.227,3.227,0,0,0-6.146,0L7.6,6.568H3.231a3.227,3.227,0,0,0-1.9,5.832Z"></path></svg>';

    $karma_display = abs($karma);

    $popup_html = '<div class="karma-popup">
                       <div class="popup-content">

	    
		<a class="modal__close close_karma_modal close-popup" onclick="closeModal()">
	    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/>
        </svg>
		</a>

                         <div class="karma-title d-flex align-items-center justify-content-center gap-3">
                           <div>' . $title_karma . ':</div>
                           <div class="karma-title-count" style="color:' . esc_attr($karma_color) . ' !important;">' . esc_html($karma) . '</div>
                         </div> 
                         <div class="accordion accordion-flush" id="accordionKarma">
                          <div class="accordion-item">
                           <div id="flush-headingOne">
                              <span class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                ' . esc_html__('За что начисляется?', 'esenin') . '
                              </span>
                           </div>
                           <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionKarma">
                              <div class="accordion-body">
                               <div class="karma-popup_desc">' . esc_html__('Публикация поста:', 'esenin') . '<span style="color: #00ad64;"> +10 </span></div> 
                               <div class="karma-popup_desc">' . esc_html__('Удаление поста:', 'esenin') . '<span style="color: #ff2056;"> -10 </span></div>
                               <div class="karma-popup_desc">' . esc_html__('Публикация комментария:', 'esenin') . '<span style="color: #00ad64;"> +2 </span></div> 
                               <div class="karma-popup_desc">' . esc_html__('Удаление комментария:', 'esenin') . '<span style="color: #ff2056;"> -2 </span></div>
                               <div class="karma-popup_desc">' . esc_html__('Оценили ваш пост:', 'esenin') . '<span style="color: #00ad64;"> +1 </span></div> 
                               <div class="karma-popup_desc">' . esc_html__('Убрали оценку с поста:', 'esenin') . '<span style="color: #ff2056;"> -1 </span></div>  
                               <div class="karma-popup_desc">' . esc_html__('Оценили ваш комментарий:', 'esenin') . '<span style="color: #00ad64;"> +1 </span></div> 
                               <div class="karma-popup_desc">' . esc_html__('Убрали оценку с комментария:', 'esenin') . '<span style="color: #ff2056;"> -1 </span></div>
                              </div>
                           </div>
                         </div>  
                        </div>                       
                       </div>
                    </div>';

    return '<div class="karma" title="' . $title_karma . '" style="color: ' . esc_attr($karma_color) . ';" onclick="showPopup();">' . $svg_icon . '<div class="karma-count">' . esc_html($karma_display) . '</div></div>' . $popup_html . '
            <script>
                function showPopup() {
                    document.querySelector(".karma-popup").style.display = "block";
                }
                document.querySelector(".close-popup").onclick = function() {
                    document.querySelector(".karma-popup").style.display = "none";
                };
                window.onclick = function(event) {
                    if (event.target.classList.contains("karma-popup")) {
                        document.querySelector(".karma-popup").style.display = "none";
                    }
                };
            </script>';
}

add_shortcode('user_karma_popup', 'display_user_karma_popup');


// Shortcode for displaying top on the author page (author.php)
function get_user_karma_rank($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_karma';

    $user_karma = $wpdb->get_var($wpdb->prepare(
        "SELECT karma FROM $table_name WHERE user_id = %d",
        $user_id
    ));

    if ($user_karma <= 0) {
        return '';
    }

    $rank = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) + 1 FROM $table_name WHERE karma > %d AND karma > 0",
        $user_karma
    ));

    if (is_null($rank)) {
        $rank = 1; 
    }

    $svg_icon = '<img src="data:image/svg+xml;charset=UTF-8,' . rawurlencode('
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0074e4">
            <path d="M11.24,24a2.262,2.262,0,0,1-.948-.212,2.18,2.18,0,0,1-1.2-2.622L10.653,16H6.975A3,3,0,0,1,4.1,12.131l3.024-10A2.983,2.983,0,0,1,10,0h3.693a2.6,2.6,0,0,1,2.433,3.511L14.443,8H17a3,3,0,0,1,2.483,4.684l-6.4,10.3A2.2,2.2,0,0,1,11.24,24Z"/>
        </svg>
    ') . '" alt="Icon-Rank"/>';

    if ($rank <= 10) {
        return '<div class="user_karma_rank">' . $svg_icon . ' Топ-' . $rank . '</div>';
    } elseif ($rank <= 20) {
        return '<div class="user_karma_rank">' . $svg_icon . ' Топ-20</div>';
    } elseif ($rank <= 30) {
        return '<div class="user_karma_rank">' . $svg_icon . ' Топ-30</div>';
    } elseif ($rank <= 50) {
        return '<div class="user_karma_rank">' . $svg_icon . ' Топ-50</div>';
    } else {
        return '<div class="user_karma_rank"></div>';
    }
}

function user_karma_rank_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => 0,
    ), $atts);

    if ($atts['id'] > 0) {
        $rank = get_user_karma_rank($atts['id']);
        return $rank;
    }

    return '<div class="user_karma_rank">Ошибка: ID пользователя не указан.</div>';
}
add_shortcode('user_karma_rank', 'user_karma_rank_shortcode');
