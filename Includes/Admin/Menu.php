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
		add_submenu_page( 'advance-users', __( 'Users', 'advance-user-registration' ), __( 'Users', 'advance-user-registration' ), $user, 'advance-users', [ $this, 'render_user_management_page' ] );
		add_submenu_page( 'advance-users', __( 'Form', 'advance-user-registration' ), __( 'Form', 'advance-user-registration' ), $user, 'advance-users-form', [ $this, 'customize_registration_form_page' ] );
		add_submenu_page( 'advance-users', __( 'Settings', 'advance-user-registration' ), __('Settings', 'advance-user-registration' ), $user, 'advance-users-settings', [ $this, 'advance_users_settings_page' ] );
	}

	/**
	 * Plugin Admin Menu
	 *
	 * @return void
	 */
	public function render_user_management_page() {
		$user_id = isset( $_GET['user_id'] ) ? sanitize_text_field( $_GET['user_id'] ) : '';
		$avur_user_data_instance = new Pages\Avur_user_data();
		if ( $user_id ) {
			$avur_user_data_instance->avur_edit_user();
		} else {
			$avur_user_data_instance->custom_user_list_page();
		}
	}

	/**
	 * Customize Registration form.
	 *
	 * @return void
	 */
	public function customize_registration_form_page() {
		$avur_add_registration_field = new Pages\Avur_add_registration_field();
		$avur_add_registration_field->add_registration_form_field();
	}

	/**
	 * Users Settings.
	 *
	 * @return void
	 */
	public function advance_users_settings_page() {
		$avur_users_settings = new Pages\Avur_users_settings();
		$avur_users_settings->avur_users_settins();
	}

}
