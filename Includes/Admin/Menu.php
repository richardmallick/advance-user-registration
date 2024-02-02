<?php

namespace AV_USER_REGISTRATION\Includes\Admin;

/**
 * Admin Menu Class
 *
 * @since 1.0.0
 */
class Menu {

	/**
	 * Menu class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'regiter_admin_menu' ] );
	}

	/**
	 * Register Admin Menue
	 *
	 * @return void
	 */
	public function regiter_admin_menu() {
		$user = 'manage_options';
		add_menu_page( __( 'Advance Users', 'advance-user-registration' ), __( 'Advance Users', 'advance-user-registration' ), $user, 'advance-users', [ $this, 'render_user_management_page' ], 'dashicons-groups', 68 );
	}

	/**
	 * Plugin Admin Menu
	 *
	 * @return void
	 */
	public function render_user_management_page() {
		$userManagementDashboardInstance = new Pages\UserManagementDashboard();
		$userManagementDashboardInstance->custom_user_list_page();
	}

}
