<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'ESN_User_Online' ) ) {

    class ESN_User_Online {

        private const USER_INACTIVITY_MARGIN = 5 * MINUTE_IN_SECONDS;
        private const TRANSIENT_SELF_CLEAR = 30 * MINUTE_IN_SECONDS;

        public function __construct() {
            add_action( 'init', array( $this, 'esn_user_online_transient' ) );
            add_shortcode( 'user_online', array( $this, 'shortcode_user_online' ) ); // User online --> [user_online user_id="X"]
            add_shortcode( 'currently_online_users', array( $this, 'shortcode_get_currently_online_users' ) );  // Users List online --> [currently_online_users]
            add_shortcode( 'recently_offline_users', array( $this, 'shortcode_get_recently_offline_users' ) ); // Users List recently online --> [recently_offline_users]
            add_action( 'wp_dashboard_setup', array( $this, 'add_user_stats_widget' ) );
		}

        /**
         * Set & update esn_user_online_transient.
         *
         * @since 1.0.0
         */
        public function esn_user_online_transient() {
            if ( is_user_logged_in() ) {
                $esn_user_online_transient = get_transient( 'esn_user_online_transient' );

                if ( empty( $esn_user_online_transient ) ) {
                    $esn_user_online_transient = array();
                }

                $user_id = get_current_user_id();
                $timestamp = current_time( 'timestamp' );

                if ( empty( $esn_user_online_transient[$user_id] ) || ( $esn_user_online_transient[$user_id] < ( $timestamp - self::USER_INACTIVITY_MARGIN ) ) ) {
                    $esn_user_online_transient[$user_id] = $timestamp;
                    set_transient( 'esn_user_online_transient', $esn_user_online_transient, self::TRANSIENT_SELF_CLEAR );
                }
            }
        }

        /**
         * Get a specific user activity status from its ID.
         *
         * @since 1.0.0
         *
         * @param Integer $user_id The user ID.
         *
         * @return Bool True for online.
         */
        public function is_user_currently_online( $user_id ) {
            $esn_user_online_transient = get_transient( 'esn_user_online_transient' );

            if ( ! isset( $esn_user_online_transient[$user_id] ) ) {
                return false;
            }

            return $esn_user_online_transient[$user_id] > ( current_time( 'timestamp' ) - self::USER_INACTIVITY_MARGIN );
        }

        /**
         * Get an array of all users currently online.
         *
         * @since 1.0.0
         *
         * @return Array An array of currently online users ID.
         */
        public function get_currently_online_users() {
            $esn_user_online_transient = array_reverse( get_transient( 'esn_user_online_transient' ), true );
            $currently_online_users = array();

            foreach ( $esn_user_online_transient as $user_id => $timestamp ) {
                if ( $timestamp > ( current_time( 'timestamp' ) - self::USER_INACTIVITY_MARGIN ) ) {
                    array_push( $currently_online_users, $user_id );
                }
            }

            return $currently_online_users;
        }

        /**
         * Get an array of all users recently offline.
         *
         * @since 1.0.0
         *
         * @return Array An array of recently offline users ID.
         */
        public function get_recently_offline_users() {
            $esn_user_online_transient = array_reverse( get_transient( 'esn_user_online_transient' ), true );
            $recently_offline_users = array();

            foreach ( $esn_user_online_transient as $user_id => $timestamp ) {
                if ( $timestamp < ( current_time( 'timestamp' ) - self::USER_INACTIVITY_MARGIN ) ) {
                    array_push( $recently_offline_users, $user_id );
                }
            }

            return $recently_offline_users;
        }

        /**
         * Shortcode to check if a specific user is online.
         *
         * @param array $atts Shortcode attributes.
         * @return string Result.
         */
        public function shortcode_user_online( $atts ) {
            $atts = shortcode_atts( array(
                'user_id' => 0,
            ), $atts );

            if ( $this->is_user_currently_online( intval( $atts['user_id'] ) ) ) {
                return '<div class="esn-online-status" title="' . esc_attr__('Сейчас на сайте', 'esenin') . '"></div>';
            } else {
                return '';
            }
        }

        /**
         * Shortcode to get currently online users.
         *
         * @return string List of currently online users.
         */
       public function shortcode_get_currently_online_users() {
         $users = $this->get_currently_online_users();
        return !empty($users) ? implode(', ', $users) : __('Онлайн-пользователей нет.', 'esenin');
       }

        /**
        * Shortcode to get recently offline users.
        *
        * @return string List of recently offline users.
        */
       public function shortcode_get_recently_offline_users() {
         $users = $this->get_recently_offline_users();
        return !empty($users) ? implode(', ', $users) : __('Никто недавно не заходил.', 'esenin');
   }
   
   
   public function add_user_stats_widget() {
            wp_add_dashboard_widget(
                'user_stats_dashboard_widget',
                __('Статистика пользователей', 'esenin'),
                array( $this, 'display_user_stats_widget' )
            );
        }
        public function display_user_stats_widget() {
            $total_users = count_users();
            $total_registered = $total_users['total_users'];

            $new_users = $this->get_recently_registered_users(5);

            $online_users_ids = $this->get_currently_online_users();
            $online_users_count = count($online_users_ids);
            $online_users = $this->get_user_links_from_ids($online_users_ids);
			
			 echo '<style>
              .admin_display_user_stats_widget {                                 
                    background-color: #f9f9f9;
                    margin: -12px;
               }
              .admin_display_user_stats_reg {
                    border-bottom: 1px solid #ccc;
                  border-top: 1px solid #ccc;
					padding: 20px;	
                    background-color: #f2f2f2;	
                   					
               }
			   .admin_display_user_stats_online {
                    padding: 20px;	                   				
               }
              .admin_display_user_stats_count {
                    font-size: 17px;
                    font-weight: 400;
               }
               .admin_display_user_stats_list {
                    line-height: 1.5;
                    color: #646970;
                    margin-top: 5px;
               }
               .admin_display_user_stats_count strong {
                    color: #000;
					font-weight: 500;
                }
            </style>';

            echo '<div class="admin_display_user_stats_widget">';
            echo '<div class="admin_display_user_stats_reg">';
			echo '<div class="admin_display_user_stats_count">' . __('Всего пользователей: ', 'esenin') . '<strong>' . $total_registered . '</strong></div>';
            echo '<div class="admin_display_user_stats_list">' . __('Новые регистрации: ', 'esenin') . implode(', ', $new_users) . '</div>';
            echo '</div>';
			echo '<div class="admin_display_user_stats_online">';
			echo '<div class="admin_display_user_stats_count">' . __('Сейчас на сайте: ', 'esenin') . '<strong>' . $online_users_count . '</strong></div>';
            echo '<div class="admin_display_user_stats_list">' . __('Пользователи онлайн: ', 'esenin') . implode(', ', $online_users) . '</div>';
			echo '</div>';
			echo '</div>';
        }

        private function get_recently_registered_users($number = 5) {
            $args = array(
                'orderby' => 'registered',
                'order'   => 'DESC',
                'number'  => $number,
            );
            $users = get_users($args);
            $user_links = array();

            foreach ($users as $user) {
                $user_links[] = '<a href="' . esc_url(get_author_posts_url($user->ID)) . '" target="_blank">' . esc_html($user->display_name) . '</a>';
            }

            return $user_links;
        }

        private function get_user_links_from_ids($user_ids) {
            $user_links = array();
            foreach ($user_ids as $user_id) {
                $user_info = get_userdata($user_id);
                $user_links[] = '<a href="' . esc_url(get_author_posts_url($user_info->ID)) . '" target="_blank">' . esc_html($user_info->display_name) . '</a>';
            }
            return $user_links;
        }
    };

    $esn_user_online = new ESN_User_Online();

};

/**
 * Schedules a recurring daily event.
 *
 * @since 1.0.0
 */
if ( ! wp_next_scheduled ( 'schedule_event_delete_esn_user_online_transient' ) ) {
    wp_schedule_event( strtotime( '23:59:00' ), 'daily', 'schedule_event_delete_esn_user_online_transient' );
};

/**
 * Delete the esn_user_online_transient.
 *
 * @since 1.0.0
 */
add_action( 'schedule_event_delete_esn_user_online_transient', 'delete_esn_user_online_transient' );

if ( ! function_exists( 'delete_esn_user_online_transient' ) ) {

    function delete_esn_user_online_transient() {
        delete_transient( 'esn_user_online_transient' );
    };
};