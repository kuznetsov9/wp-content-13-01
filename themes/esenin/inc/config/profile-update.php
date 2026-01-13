<?php
$url = explode("?",$_SERVER['HTTP_REFERER']);
 
if(!defined('ABSPATH')) {
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
}
 
if( !is_user_logged_in() ) exit;
 
$user_ID = get_current_user_id();
$user = get_user_by( 'id', $user_ID );
 
 
if( $_POST['pwd1'] || $_POST['pwd2'] || $_POST['pwd3'] ) {
 
	if( $_POST['pwd1'] && $_POST['pwd2'] && $_POST['pwd3'] ) {
 
		if( $_POST['pwd2'] == $_POST['pwd3'] ){
 

			if( strlen( $_POST['pwd2'] ) < 8 ) {
				header('location:' . $url[0] . '?status=short');
				exit;
			}
 
			if( wp_check_password( $_POST['pwd1'], $user->data->user_pass, $user->ID) ) {
				wp_set_password( $_POST['pwd2'], $user_ID );
				$creds['user_login'] = $user->user_login;
				$creds['user_password'] = $_POST['pwd2'];
				$creds['remember'] = true;
				$user = wp_signon( $creds, false );
			} else {
				header('location:' . $url[0] . '?status=wrong');
				exit;
			}
 
		} else {
			header('location:' . $url[0] . '?status=wrongshift');
			exit;
		}
 
	} else {
		header('location:' . $url[0] . '?status=required');
		exit;
	}
}
 
if( /* $_POST['first_name'] && $_POST['last_name'] && */ is_email($_POST['email']) ) {
 
	if( email_exists( $_POST['email'] ) && $_POST['email'] != $user->user_email ) {
		header('location:' . $url[0] . '?status=exist');
		exit;
	}
 
	wp_update_user( array( 
			'ID' => $user_ID, 
			'user_email' => $_POST['email'],
			'first_name' => $_POST['first_name'],
			'last_name' => $_POST['last_name'],
			'display_name' => $_POST['display_name'] ));

	 update_user_meta( $current_user->ID, 'description', esc_attr( $_POST['description'] ) );
	 update_user_meta( $current_user->ID, 'esn_position', esc_attr( $_POST['esn_position'] ) );
	 update_user_meta( $current_user->ID, 'esn_location', esc_attr( $_POST['esn_location'] ) );
	 update_user_meta($current_user->ID, 'display_name', esc_attr($_POST['display_name']));
	 
	 update_user_meta($current_user->ID, 'esn_vk', esc_attr($_POST['esn_vk']));
	 update_user_meta($current_user->ID, 'esn_telegram', esc_attr($_POST['esn_telegram']));
	 update_user_meta($current_user->ID, 'esn_instagram', esc_attr($_POST['esn_instagram']));
	 update_user_meta($current_user->ID, 'esn_tiktok', esc_attr($_POST['esn_tiktok']));
	 update_user_meta($current_user->ID, 'esn_github', esc_attr($_POST['esn_github']));
	 update_user_meta($current_user->ID, 'esn_youtube', esc_attr($_POST['esn_youtube']));

	 
} else {
	header('location:' . $url[0] . '?status=required');
	exit;
}
 
header('location:' . $url[0] . '?status=ok');
exit;