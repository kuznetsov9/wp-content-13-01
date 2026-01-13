<?php
/**
 * Notifications Ajax.
 *
 * @package Esenin
 */
function create_notifications_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'notifications';
    $charset_collate = $wpdb->get_charset_collate();

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            action_user_id bigint(20),  
            message text NOT NULL,
            is_read tinyint(1) DEFAULT 0 NOT NULL,
            link text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    } else {
        $column_check = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE 'action_user_id'");
        if (empty($column_check)) {
            $wpdb->query("ALTER TABLE $table_name ADD action_user_id bigint(20)");
        }
    }
}
add_action('after_switch_theme', 'create_notifications_table');

function add_notification($user_id, $message, $link, $action_user_id = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'notifications';

    $wpdb->insert($table_name, array(
        'user_id' => $user_id,
        'action_user_id' => $action_user_id,
        'message' => $message,
        'link' => $link,
        'is_read' => 0,
    ));
}

add_action('wp_ajax_get_notifications', 'get_notifications');
function get_notifications() {
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'notifications';

    $notifications = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d ORDER BY created_at DESC", $user_id));

    if (empty($notifications)) {
        wp_send_json(['status' => 'empty', 'message' => 'Нет новых уведомлений']);
    } else {
        $notification_items = [];
        foreach ($notifications as &$notification) {
            $time_difference = human_time_diff(strtotime($notification->created_at), current_time('timestamp'));
            $notification->created_at = $time_difference;

            $action_user = get_userdata($notification->action_user_id);
            $notification->action_user_name = $action_user ? $action_user->display_name : 'Неизвестный';
            $notification->action_user_avatar = get_avatar($notification->action_user_id); // Получаем аватар

            $notification_items[] = '<div class="esn-notification-message dropdown-item">
				  <div class="flex-shrink-0">
				    <a href="' . esc_url(get_author_posts_url($notification->action_user_id)) . '">' . $notification->action_user_avatar . '</a>
				  </div>
                  <div class="flex-grow-1">
                    <div class="notif-item-text">
					  <a href="' . esc_url(get_author_posts_url($notification->action_user_id)) . '">' . esc_html($notification->action_user_name) . '</a>
                          ' . wp_kses_post($notification->message) . '
					  </div>
                      <div class="esn-notification-date">' . esc_html($notification->created_at) . '</div>
				  </div>
            </div>';
        }
        wp_send_json(['status' => 'success', 'notifications' => $notification_items]);
    }
}
 
add_action('wp_ajax_delete_all_notifications', 'delete_all_notifications');
function delete_all_notifications() {
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'notifications';

    $wpdb->delete($table_name, ['user_id' => $user_id]);
    wp_send_json(['status' => 'success', 'message' => 'Все уведомления удалены']);
}

add_action('wp_ajax_mark_as_read', 'mark_as_read');
function mark_as_read() {
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'notifications';

    $updated = $wpdb->update($table_name, ['is_read' => 1], ['user_id' => $user_id, 'is_read' => 0]);

    if ($updated) {
        wp_send_json(['status' => 'success', 'message' => 'Все уведомления помечены как прочитанные']);
    } else {
        wp_send_json(['status' => 'error', 'message' => 'Ошибка при обновлении уведомлений']);
    }
}

add_action('wp_ajax_get_unread_count', 'get_unread_count');
function get_unread_count() {
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'notifications';

    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND is_read = 0", $user_id));
    echo $count;
    wp_die();
}

add_action('template_redirect', function() {
    if (is_author() && get_current_user_id()) {
        $visited_user_id = get_queried_object_id();
        notify_on_profile_visit($visited_user_id);
    }
});


// Shortcode ==> Notifications_bell
function notifications_bell_shortcode() {
    ob_start(); ?>	
    <div id="notifications-bell" class="position-relative">
	
        <div class="btn-notification" id="bell-button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <g id="_01_align_center" data-name="01 align center">
                    <path d="M23.259,16.2l-2.6-9.371A9.321,9.321,0,0,0,2.576,7.3L.565,16.35A3,3,0,0,0,3.493,20H7.1a5,5,0,0,0,9.8,0h3.47a3,3,0,0,0,2.89-3.8ZM12,22a3,3,0,0,1-2.816-2h5.632A3,3,0,0,1,12,22Zm9.165-4.395a.993.993,0,0,1-.8.395H3.493a1,1,0,0,1-.976-1.217l2.011-9.05a7.321,7.321,0,0,1,14.2-.372l2.6,9.371A.993.993,0,0,1,21.165,17.605Z"/>
                </g>
            </svg>
            <span id="unread-count" class="notification-count" style="display: none;"></span>
        </div>
        <div id="notifications-dropdown" class="dropdown-menu dropdown-lg-menu-end" aria-labelledby="bell-button">
            <div class="dropdown-header d-flex justify-content-between align-items-center">
                <span class="notification-dropdown-header-title"><?php esc_html_e('Уведомления', 'esenin'); ?></span>
                 
				<span class="notification-icon" id="additional-dropdown-toggle" style="cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                        <circle cx="2" cy="12" r="2"/>
                        <circle cx="12" cy="12" r="2"/>
                        <circle cx="22" cy="12" r="2"/>
                    </svg>
                </span>
			
            </div>
           
			<div class="additional-dropdown dropdown-menu" aria-labelledby="additional-dropdown-toggle" style="display: none;">
                <a class="dropdown-item" href="#" id="delete-all"><?php esc_html_e('Удалить все', 'esenin'); ?></a>
                <a class="dropdown-item" href="#" id="mark-read"><?php esc_html_e('Прочитать все', 'esenin'); ?></a>				 				 
				<a class="dropdown-item esn-modal-open" href="#notify-setting" id="setting-notification"><?php esc_html_e('Настройки показа', 'esenin'); ?></a>
			</div>

            <div id="notifications-content" class="notifications-scrollable"></div>
            <div id="view-all" class="view-all-notifications" style="display:none;">
                <a href="/notifications"><?php esc_html_e('Все уведомления', 'esenin'); ?></a>
            </div>
        </div>
    </div>
<div id="notify-setting" class="modal">    
  <div class="modal__window">
    <div class="modal__window_header">
	    <div class="modal__window_header-title">
	     <?php esc_html_e('Настройка получения', 'esenin'); ?>
		</div>
		<a class="modal__close" onclick="closeModal()">
	    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071 5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z"/>
        </svg>
	    </a>
	</div>
      <div class="modal__window_body">	 	           
		 <?php echo do_shortcode('[notification_settings]'); ?>
	  </div>
    </div> 	
</div>	     
    <script>
    jQuery(document).ready(function($) {
        function fetchNotifications() {
            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                method: "POST",
                data: { action: "get_notifications" },
                success: function(response) {
                    if (response.status === 'empty') {
                        $('#notifications-content').html('<div class="esn-notification-item notification-not"><div><?php echo esc_js(__('Новых уведомлений нет', 'esenin')); ?></div><div><?php echo esc_js(__('Пишите хорошие статьи, комментируйте, и здесь станет не так пусто', 'esenin')); ?></div></div>');
                        $('#view-all').hide();
                        $('#delete-all, #mark-read').hide();
                    } else {
                        $('#notifications-content').html(response.notifications.slice(0, 10).join(''));
                        $('#view-all').toggle(response.notifications.length > 10); // Показываем "Все уведомления" если больше 10
                        $('#delete-all, #mark-read').show();
                    }
                },
                error: function() {
                    $('#notifications-content').html('<div class="esn-notification-item notification-error"><?php echo esc_js(__('Ошибка загрузки уведомлений', 'esenin')); ?></div>');
                    $('#view-all').hide();
                    $('#delete-all, #mark-read').hide();
                }
            });
        }

        function fetchUnreadCount() {
            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                method: "POST",
                data: { action: "get_unread_count" },
                success: function(count) {
                    $('#unread-count').text(count);
                    $('#unread-count').toggle(count > 0);
                },
                error: function() {
                    $('#unread-count').text('0').hide();
                }
            });
        }

        $('#bell-button').on('click', function() {
            fetchNotifications();
            fetchUnreadCount();
            $('.additional-dropdown').hide();
        });

        $('#additional-dropdown-toggle').on('click', function(e) {
            e.stopPropagation();
            $('.additional-dropdown').toggle();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#notifications-dropdown').length && !$(e.target).closest('#bell-button').length) {
                $('#notifications-dropdown').removeClass('show');
                $('.additional-dropdown').hide();
            } else if ($(e.target).closest('#notifications-dropdown').length) {
                $('.additional-dropdown').hide();
            }
        });

        $('#mark-read').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                method: "POST",
                data: { action: "mark_as_read" },
                success: function(response) {
                    if (response.status === 'success') {
                        fetchUnreadCount();
                        $('#notifications-content').html('<div class="esn-notification-item notification-read"><?php echo esc_js(__('Уведомления отмечены как прочитанные', 'esenin')); ?></div>');
                    } else {
                        $('#notifications-content').html('<div class="esn-notification-item notification-error"><?php echo esc_js(__('Непрочитанных нет', 'esenin')); ?></div>');
                    }
                    $('.additional-dropdown').hide();
                },
                error: function() {
                    $('#notifications-content').html('<div class="esn-notification-item notification-error"><?php echo esc_js(__('Непрочитанных нет', 'esenin')); ?></div>');
                }
            });
        });

        $('#delete-all').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                method: "POST",
                data: { action: "delete_all_notifications" },
                success: function(response) {
                    if (response.status === 'success') {
                        fetchUnreadCount();
                        $('#notifications-content').html('<div class="esn-notification-item notification-delete"><?php echo esc_js(__('Все уведомления удалены', 'esenin')); ?></div>');
                    } else {
                        $('#notifications-content').html('<div class="esn-notification-item notification-error"><?php echo esc_js(__('Ошибка при удалении уведомлений', 'esenin')); ?></div>');
                    }
                    $('.additional-dropdown').hide();
                },
                error: function() {
                    $('#notifications-content').html('<div class="esn-notification-item notification-error"><?php echo esc_js(__('Ошибка при удалении уведомлений', 'esenin')); ?></div>');
                }
            });
        });

        setInterval(fetchUnreadCount, 30000);
        fetchUnreadCount();	  
    });
	

document.addEventListener('DOMContentLoaded', function() {
    const bellButton = document.getElementById('bell-button');
    const svgIcon = bellButton.querySelector('svg');
    const dropdownMenu = document.getElementById('notifications-dropdown');

    function toggleRotate() {
        svgIcon.classList.toggle('rotate');
    }

    bellButton.addEventListener('click', function() {
        toggleRotate();
    });

    document.addEventListener('click', function(event) {
        const isClickInsideDropdown = dropdownMenu.contains(event.target);
        const isClickOnBellButton = bellButton.contains(event.target);

        if (!isClickInsideDropdown && !isClickOnBellButton) {
            svgIcon.classList.remove('rotate');
        }
    });
});
    </script>	
    <?php
    return ob_get_clean();
}
add_shortcode('esenin_notifications_bell', 'notifications_bell_shortcode');


// Shortcode ==> Notifications_list
function esenin_notifications_list_shortcode($atts) {
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'notifications';

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $limit = 20;
    $offset = ($paged - 1) * $limit;

    $notifications = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE user_id = %d ORDER BY created_at DESC LIMIT %d OFFSET %d",
        $user_id, $limit, $offset
    ));
	
    $total_notifications = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE user_id = %d",
        $user_id
    ));

    ob_start(); ?>
    <div id="notifications-list" class="notifications-list">
        <div class="esn-notifications-list">
            <div class="dropdown-header d-flex justify-content-between align-items-center">
                <span class="notification-dropdown-header-title"><?php esc_html_e('Уведомления', 'esenin'); ?></span>
                <div class="dropdown">
                    <span class="notification-icon" id="esenin-list-dropdown-toggle" style="cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                            <circle cx="2" cy="12" r="2"/>
                            <circle cx="12" cy="12" r="2"/>
                            <circle cx="22" cy="12" r="2"/>
                        </svg>
                    </span>
                    <div class="additional-dropdown-page dropdown-menu dropdown-menu-end" aria-labelledby="esenin-list-dropdown-toggle">
                        <?php if (!empty($notifications)): ?>
                            <a class="dropdown-item unique-delete-all" href="#" id="esenin-list-delete-all"><?php esc_html_e('Удалить все', 'esenin'); ?></a>
                            <a class="dropdown-item unique-mark-read" href="#" id="esenin-list-mark-read"><?php esc_html_e('Прочитать все', 'esenin'); ?></a>
                        <?php endif; ?>
                            <a class="dropdown-item unique-settings" href="#notify-setting" id="esenin-list-setting-notification"><?php esc_html_e('Настройки показа', 'esenin'); ?></a>
                    </div>
                </div>
            </div>

            <div id="notifications-content-list" class="notifications-items">
                <?php if (empty($notifications)): ?>
                    <div class="esn-notification-item notification-not"><?php esc_html_e('Новых уведомлений нет', 'esenin'); ?></div>
                <?php else: 
                    foreach ($notifications as $notification): 
                        $action_user = get_userdata($notification->action_user_id);
                        $action_user_avatar = get_avatar($notification->action_user_id);
                        $time_difference = human_time_diff(strtotime($notification->created_at), current_time('timestamp')); ?>
                        <div class="dropdown-item esn-notification-message">
                            <div class="flex-shrink-0">
                                <a href="<?php echo esc_url(get_author_posts_url($notification->action_user_id)); ?>"><?php echo $action_user_avatar; ?></a>
                            </div>
                            <div class="flex-grow-1">
                                <div class="notif-item-text">
                                    <a href="<?php echo esc_url(get_author_posts_url($notification->action_user_id)); ?>"><?php echo esc_html($action_user->display_name); ?></a>
                                    <?php echo wp_kses_post($notification->message); ?>
                                </div>
                                <div class="esn-notification-date"><?php echo esc_html($time_difference . ' ' . __('назад', 'esenin')); ?></div>
                            </div>
                        </div>
                    <?php endforeach; 
                endif; ?>
            </div>
        </div>

        <?php if ($total_notifications > $limit): ?>
            <div class="es-posts-area__pagination">    
                <nav class="navigation pagination">
                    <div class="nav-links">
                        <?php
                        $big = 999999999; 
                        $current_page = max(1, $paged);
                        $total_pages = ceil($total_notifications / $limit);

                        if ($total_pages <= 1) {
                            return;
                        }

                        $prev_link = $current_page > 1 ? get_pagenum_link($current_page - 1) : '#';
                        $next_link = $current_page < $total_pages ? get_pagenum_link($current_page + 1) : '#';

                        if ( wp_is_mobile() ) {
                            if ($current_page > 1) {
                                echo '<a class="prev page-numbers current" href="' . esc_url($prev_link) . '">' . __( 'Previous', 'esenin' ) . '</a>';
                            }

                            echo '<span class="current-page page-numbers current">' . $current_page . ' ' . __( 'из', 'esenin' ) . ' ' . $total_pages . '</span>';

                            if ($current_page < $total_pages) {
                                echo '<a class="next page-numbers current" href="' . esc_url($next_link) . '">' . __( 'Next', 'esenin' ) . '</a>';
                            }
                        } else {
                            echo paginate_links(array(
                                'base'    => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                'current' => $current_page,
                                'total'   => $total_pages,
                                'mid_size' => 1,
                                'end_size' => 1,
                            ));
                        } 
                        ?>    
                    </div>    
                </nav>        
            </div>    
        <?php endif; ?>
    </div>

    <script>
        jQuery(document).ready(function($) {
            $('#esenin-list-delete-all').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    method: "POST",
                    data: { action: "delete_all_notifications" },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#notifications-content-list').html('<div class="esn-notification-item notification-delete"><?php echo esc_js(__('Все уведомления удалены', 'esenin')); ?></div>');
                        } else {
                            $('#notifications-content-list').html('<div class="esn-notification-item notification-error"><?php echo esc_js(__('Ошибка при удалении уведомлений', 'esenin')); ?></div>');
                        }
                    },
                    error: function() {
                        $('#notifications-content-list').html('<div class="esn-notification-item notification-error"><?php echo esc_js(__('Ошибка при удалении уведомлений', 'esenin')); ?></div>');
                    }
                });
            });

            $('#esenin-list-mark-read').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    method: "POST",
                    data: { action: "mark_as_read" },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#notifications-content-list').html('<div class="esn-notification-item notification-read"><?php echo esc_js(__('Уведомления отмечены как прочитанные', 'esenin')); ?></div>');
                        } else {
                            $('#notifications-content-list').html('<div class="esn-notification-item notification-error"><?php echo esc_js(__('Непрочитанных нет', 'esenin')); ?></div>');
                        }
                    },
                    error: function() {
                        $('#notifications-content-list').html('<div class="esn-notification-item notification-error"><?php echo esc_js(__('Непрочитанных нет', 'esenin')); ?></div>');
                    }
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('esenin_notifications_list', 'esenin_notifications_list_shortcode');



// Shortcode ==> Notifications_setting
add_shortcode('notification_settings', 'notification_settings_shortcode');

function notification_settings_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>' . __('Для изменения настроек уведомлений требуется авторизация.', 'esenin') . '</p>';
    }

    $user_id = get_current_user_id();
    $settings = get_user_meta($user_id, 'notification_settings', true);

    $defaults = [
        'check_notify_comment_reply' => '1',
        'check_notify_comment_post' => '1',
        'check_notify_sub_unsub' => '1',
        'check_notify_likes_post_comment' => '1',
        'check_notify_approve_post_comment' => '1',
        'check_notify_profile_visit' => '1',
    ];

    if (!$settings) {
        $settings = $defaults; 
        update_user_meta($user_id, 'notification_settings', $settings); 
    } else {
        $settings = wp_parse_args($settings, $defaults); 
    }

    ob_start();    
    ?>
    <div class="notify-setting-modal">
        <div id="notification-response"></div>
        <form id="notification-settings-form">       
            <label>
                <input type="checkbox" name="check_notify_comment_reply" value="1" <?php checked($settings['check_notify_comment_reply'], '1'); ?> />
                <span><?php _e('Ответы на комментарии', 'esenin'); ?></span>
            </label></br>
            <label>
                <input type="checkbox" name="check_notify_comment_post" value="1" <?php checked($settings['check_notify_comment_post'], '1'); ?> />
                <span><?php _e('Новые комментарии к постам', 'esenin'); ?></span>
            </label></br>
            <label>
                <input type="checkbox" name="check_notify_sub_unsub" value="1" <?php checked($settings['check_notify_sub_unsub'], '1'); ?> />
                <span><?php _e('Новые подписчики', 'esenin'); ?></span>
            </label></br>
            <label>
                <input type="checkbox" name="check_notify_likes_post_comment" value="1" <?php checked($settings['check_notify_likes_post_comment'], '1'); ?> />
                <span><?php _e('Оценки постов и комментариев', 'esenin'); ?></span>
            </label></br>
            <label>
                <input type="checkbox" name="check_notify_approve_post_comment" value="1" <?php checked($settings['check_notify_approve_post_comment'], '1'); ?> />
                <span><?php _e('Одобрения постов и комментариев', 'esenin'); ?></span>
            </label></br>
            <label>
                <input type="checkbox" name="check_notify_profile_visit" value="1" <?php checked($settings['check_notify_profile_visit'], '1'); ?> />
                <span><?php _e('Гости профиля', 'esenin'); ?></span>
            </label>
            <div class="notify-setting-modal-footer">
                <button type="submit"><?php _e('Сохранить', 'esenin'); ?></button>
            </div>
        </form>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('#notification-settings-form').on('submit', function(e) {
                e.preventDefault();
                var data = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url("admin-ajax.php"); ?>',
                    data: {
                        action: 'save_notification_settings',
                        settings: data,
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#notification-response').html('<div class="alert alert-success">' + response.data + '</div>');
                        } else {
                            $('#notification-response').html('<div class="alert alert-danger">' + response.data + '</div>');
                        }

                        setTimeout(function() {
                            $('#notification-response').fadeOut();
                        }, 3000);
                    },
                    error: function() {
                        $('#notification-response').html('<div class="alert alert-danger"><?php _e("Произошла ошибка, попробуйте позже.", "esenin"); ?></div>');

                        setTimeout(function() {
                            $('#notification-response').slideUp(300);
                        }, 3000);
                    }
                });
            });
        });
    </script>
    <?php
    return ob_get_clean();
}

add_action('wp_ajax_save_notification_settings', 'save_notification_settings');

function save_notification_settings() {
    if (!is_user_logged_in()) {
        wp_send_json_error(__('Вы не авторизованы.', 'esenin'));
    }

    $user_id = get_current_user_id();
    parse_str($_POST['settings'], $output);

    $valid_keys = [
        'check_notify_comment_reply',
        'check_notify_comment_post',
        'check_notify_sub_unsub',
        'check_notify_likes_post_comment',
        'check_notify_approve_post_comment',
        'check_notify_profile_visit'
    ];

    foreach ($valid_keys as $key) {
        $output[$key] = isset($output[$key]) ? '1' : '0';
    }

    update_user_meta($user_id, 'notification_settings', $output);

    wp_send_json_success(__('Настройки получения сохранены.', 'esenin'));
}



//////////////////////////////////////////////////////

// Notification Logic for Esenin

/////////////////////////////////////////////////////

// Notification when a comment is approved
// Уведомление при одобрении комментария
add_action('wp_set_comment_status', 'notify_on_approved_comment', 10, 2);
function notify_on_approved_comment($comment_ID, $status) {
    if ($status == 'approve') { 
        $comment = get_comment($comment_ID);
        $post = get_post($comment->comment_post_ID);
        $user_id = $comment->user_id;

        if (!empty(get_user_meta($user_id, 'notification_settings', true)['check_notify_approve_post_comment'])) {
            $message = sprintf('%s', 
                ' ' . __('- ваш комментарий одобрен в посте', 'esenin') . ' <a href="' . esc_url(get_permalink($post) . '#comment-' . $comment_ID) . '">' . esc_html($post->post_title) . '.</a>'
            );

            add_notification($user_id, $message, esc_url(get_permalink($post) . '#comment-' . $comment_ID), $user_id);
        }
    }
}

// Notification when a post is approved
// Уведомление при одобрении поста
add_action('transition_post_status', 'notify_author_on_post_approved', 10, 3);
function notify_author_on_post_approved($new_status, $old_status, $post) {
    if ($new_status === 'publish' && ($old_status === 'pending' || $old_status === 'draft')) {
        $author_id = $post->post_author;

        if (!empty(get_user_meta($author_id, 'notification_settings', true)['check_notify_approve_post_comment'])) {
            $message = sprintf(
                __('- ваш пост опубликован в ленте. %s.', 'esenin'),
                '<a href="' . esc_url(get_permalink($post->ID)) . '">' . esc_html($post->post_title) . '</a>'
            );

            add_notification($author_id, $message, esc_url(get_permalink($post->ID)), $author_id);
        }
    }
}

// Notification when commenting on a post
// Уведомление при комментарии поста
add_action('wp_set_comment_status', 'notify_on_comment_status_change', 10, 2);
function notify_on_comment_status_change($comment_ID, $comment_status) {
    if ($comment_status == 'approve') {
        $comment = get_comment($comment_ID);
        $post = get_post($comment->comment_post_ID);

        if ($post->post_author != $comment->user_id) {
            $message = sprintf('%s', 
                ' ' . __('ответил(а) на ваш пост', 'esenin') . ' <a href="' . esc_url(get_permalink($post) . '#comment-' . $comment_ID) . '">' . esc_html($post->post_title) . '.</a>'
            );

            if (!empty(get_user_meta($post->post_author, 'notification_settings', true)['check_notify_comment_post'])) {
                add_notification($post->post_author, $message, esc_url(get_permalink($post) . '#comment-' . $comment_ID), $comment->user_id);
            }
        }
    }
}

// Notification when commenting on a post
// Уведомление при комментировании поста
add_action('comment_post', 'notify_on_new_comment', 10, 2);
function notify_on_new_comment($comment_ID, $comment_approved) {
    if ($comment_approved) {
        $comment = get_comment($comment_ID);
        $post = get_post($comment->comment_post_ID);

        if ($post->post_author != $comment->user_id) {
            $message = sprintf('%s', 
                ' ' . __('ответил(а) на ваш пост', 'esenin') . ' <a href="' . esc_url(get_permalink($post) . '#comment-' . $comment_ID) . '">' . esc_html($post->post_title) . '.</a>'
            );

            if (!empty(get_user_meta($post->post_author, 'notification_settings', true)['check_notify_comment_post'])) {
                add_notification($post->post_author, $message, esc_url(get_permalink($post) . '#comment-' . $comment_ID), $comment->user_id);
            }
        }
    }
}

// Notification when replying to a comment
// Уведомление при ответе на комментарий
add_action('wp_set_comment_status', 'notify_on_comment_reply_status_change', 10, 2);
function notify_on_comment_reply_status_change($comment_ID, $comment_status) {
    if ($comment_status == 'approve') {
        $comment = get_comment($comment_ID);
        $post = get_post($comment->comment_post_ID);
        $parent_comment_ID = $comment->comment_parent;

        if ($parent_comment_ID) {
            $parent_comment = get_comment($parent_comment_ID);
            $parent_author_id = $parent_comment->user_id;

            if ($parent_author_id != $comment->user_id) {
                $message = sprintf('%s',
                    ' ' . __('ответил(а) на ваш комментарий', 'esenin') . ' <a href="' . esc_url(get_permalink($post) . '#comment-' . $comment_ID) . '">' . esc_html($comment->comment_content) . '.</a>'
                );

                if (!empty(get_user_meta($parent_author_id, 'notification_settings', true)['check_notify_comment_reply'])) {
                    add_notification($parent_author_id, $message, esc_url(get_permalink($post) . '#comment-' . $comment_ID), $comment->user_id);
                }
            }
        }
    }
}

// Notification when visiting a profile
// Уведомление при посещении профиля
function notify_on_profile_visit($visited_user_id) {
    $current_user_id = get_current_user_id();

    // Проверка, что текущий пользователь не анонимный и не является просмотренным профилем
    if ($current_user_id && $current_user_id != $visited_user_id) {
        // Проверка, что профайл просматриваемого пользователя является автором
        if (is_author($visited_user_id)) {
            // Условия незагрузки страницы и отсутствия таба
            if (!is_paged() && !isset($_GET['tab'])) {
                // Проверка параметров сортировки
                if (!isset($_GET['sort'])) {
                    if (!current_user_can('administrator')) {
                        $visitor_name = get_the_author_meta('display_name', $current_user_id);
                        $visitor_profile_link = get_author_posts_url($current_user_id);

                        // Формирование сообщения для уведомления
                        $message = sprintf('%s', 
                            __('посетил(а) ваш профиль.', 'esenin')
                        );

                        // Проверка настроек уведомлений для пользователя
                        if (!empty(get_user_meta($visited_user_id, 'notification_settings', true)['check_notify_profile_visit'])) {
                            add_notification($visited_user_id, $message, esc_url($visitor_profile_link), $current_user_id);
                        }
                    }
                }
            }
        }
    }
}

// Notification on subscription
// Уведомление при подписке
add_action('user_subscribed', 'notify_on_subscription', 10, 2);
function notify_on_subscription($subscriber_id, $user_id) {
    if ($subscriber_id != $user_id) {
        $message = sprintf(
            '%s',
            ' ' . __('подписался(ась) на ваш профиль.', 'esenin')
        );

        if (!empty(get_user_meta($user_id, 'notification_settings', true)['check_notify_sub_unsub'])) {
            add_notification($user_id, $message, esc_url(get_author_posts_url($subscriber_id)), $subscriber_id);
        }
    }
}

// Notification on unsubscription
// Уведомление об отписке
add_action('user_unsubscribed', 'notify_on_unsubscription', 10, 2);
function notify_on_unsubscription($subscriber_id, $user_id) {
    if ($subscriber_id != $user_id) {
        $message = sprintf(
            '%s',
            ' ' . __('отписался(ась) от вашего профиля.', 'esenin')
        );

        if (!empty(get_user_meta($user_id, 'notification_settings', true)['check_notify_sub_unsub'])) {
            add_notification($user_id, $message, esc_url(get_author_posts_url($subscriber_id)), $subscriber_id);
        }
    }
}

// Notification when rating posts
// Уведомление при оценке постов
function notify_on_like($post_id, $user_id, $action) {
    $post = get_post($post_id);
    $post_author_id = $post->post_author;

    if ($user_id != $post_author_id) {
        if ($action === 'add') {
            $message = sprintf('%s',
                ' ' . __('понравился ваш пост', 'esenin') . ' <a href="' . esc_url(get_permalink($post)) . '">' . esc_html($post->post_title) . '.</a>'
            );
        } else {
            $message = sprintf('%s',
                ' ' . __('больше не нравится ваш пост', 'esenin') . ' <a href="' . esc_url(get_permalink($post)) . '">' . esc_html($post->post_title) . '.</a>'
            );
        }

        if (!empty(get_user_meta($post_author_id, 'notification_settings', true)['check_notify_likes_post_comment'])) {
            add_notification($post_author_id, $message, esc_url(get_permalink($post)), $user_id);
        }
    }
}

// Notification when comments are rated positively
// Уведомление при положительной оценке комментариев
function send_notification_on_like_addition($commented_user_id, $comment, $post, $liker_user_id) {
    if ($liker_user_id != $commented_user_id) {
        $message = sprintf(
            '%s',
            ' ' . __('понравился ваш комментарий', 'esenin') . ' <a href="' . esc_url(get_comment_link($comment->comment_ID, $post->ID)) . '">' . esc_html(wp_trim_words($comment->comment_content, 10)) . '.</a>'
        );

        if (!empty(get_user_meta($commented_user_id, 'notification_settings', true)['check_notify_likes_post_comment'])) {
            add_notification($commented_user_id, $message, esc_url(get_comment_link($comment->comment_ID, $post->ID)), $liker_user_id);
        }
    }
}

// Notification about comment like being removed
// Уведомление о удалении лайка комментария
function send_notification_on_like_removal($commented_user_id, $comment, $post, $liker_user_id) {
    if ($liker_user_id != $commented_user_id) {
        $message = sprintf(
            '%s',
            ' ' . __('больше не нравится ваш комментарий', 'esenin') . ' <a href="' . esc_url(get_comment_link($comment->comment_ID, $post->ID)) . '">' . esc_html(wp_trim_words($comment->comment_content, 10)) . '.</a>'
        );

        if (!empty(get_user_meta($commented_user_id, 'notification_settings', true)['check_notify_likes_post_comment'])) {
            add_notification($commented_user_id, $message, esc_url(get_comment_link($comment->comment_ID, $post->ID)), $liker_user_id);
        }
    }
}