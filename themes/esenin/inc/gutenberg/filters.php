<?php
/**
 * Filters.
 *
 * @package Esenin
 */

/**
 * Disable wp_check_widget_editor_deps.
 */
function esn_disable_wp_check_widget_editor_deps() {
	call_user_func( 'remove_filter', 'admin_head', 'wp_check_widget_editor_deps' );
}
add_filter( 'init', 'esn_disable_wp_check_widget_editor_deps' );
