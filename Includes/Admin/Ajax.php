<?php

namespace AV_USER_REGISTRATION\Includes\Admin;

/**
 * Ajax Handler Class
 */
class Ajax {

	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct() {

		add_action( 'wp_ajax_avur_user_fields', [ $this, 'avur_user_fields' ] );
		add_action( 'wp_ajax_avur_user_profile_data_update', [ $this, 'avur_user_profile_data_update' ] );
		add_action( 'wp_ajax_avur_approve_user', [ $this, 'avur_approve_user' ] );
		add_action( 'wp_ajax_avur_delete_user', [ $this, 'avur_delete_user' ] );
		add_action( 'wp_ajax_avur_save_settings_data', [ $this, 'avur_save_settings_data' ] );

	}

	/**
	 * Method avur_user_fields
	 *
	 * @return mixed
	 */
	public function avur_user_fields() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce = isset( $_REQUEST['_nonce'] ) && '' !== $_REQUEST['_nonce'] ? sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'avur-admin-nonce' ) ) {
			return esc_html__( 'Nonce Varification Failed!', 'wpte-product-layout' );
		}

		$inserted_datas = isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ? filter_input( INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY ) : [];

		$fields_array = array();
		$temp_array = array();

		foreach ($inserted_datas as $item) {
			if ( $item['name'] === 'field-type' ) {
				if ( ! empty( $temp_array ) ) {
					$fields_array[] = $temp_array;
					$temp_array = array();
				}
				$temp_array[] = $item['value'];
			} elseif ( $item['name'] === 'label-name' || $item['name'] === 'field-name' ) {
				$temp_array[] = $item['value'];
			}
		}

		if ( ! empty( $temp_array ) ) {
			$fields_array[] = $temp_array;
		}

		$is_valid_fields = avur_registration_fields_validate( $fields_array );

		if ( $is_valid_fields ) {
			update_option( 'avur_user_registration_fields', $fields_array );
			wp_send_json_success( [
				'message' => esc_html__( 'User data has been saved.', 'advance-user-registration' ),
			] );
		} else {
			wp_send_json_success( [
				'error' => esc_html__( 'All fields are required. Some fields data are missing! Please check and try again.', 'advance-user-registration' ),
			] );
		}

	}

	/**
	 * Method avur_user_profile_data_update
	 *
	 * @return mixed
	 */
	public function avur_user_profile_data_update() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce = isset( $_REQUEST['_nonce'] ) && '' !== $_REQUEST['_nonce'] ? sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'avur-admin-nonce' ) ) {
			return esc_html__( 'Nonce Varification Failed!', 'wpte-product-layout' );
		}

		$inserted_datas = isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ? filter_input( INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY ) : [];

		$users_array = [];
		foreach( $inserted_datas as $inserted_data ) {
			$users_array[$inserted_data['name']] = $inserted_data['value'];
		}

		$user_id   = $users_array['avur-user-id'] ? sanitize_text_field( $users_array['avur-user-id'] ) : '';
		$email     = $users_array['email'] ? sanitize_email( $users_array['email'] ) : '';
		$password  = $users_array['avur-password'] ? sanitize_text_field( $users_array['avur-password'] ) : '';
		$user_role = $users_array['avur-user-role'] ? sanitize_text_field( $users_array['avur-user-role'] ) : '';

		$user_data = array(
			'ID'         => $user_id,
			'user_email' => $email,
			'user_pass'  => $password,
			'role'       => $user_role,
		);

		$update = wp_update_user( $user_data );
		
		unset( $users_array['avur-user-id'] );
		unset( $users_array['username'] );
		unset( $users_array['email'] );
		unset( $users_array['avur-password'] );
		unset( $users_array['avur-user-role'] );
		update_user_meta( $user_id, 'avur_user_meta_data', $users_array );

		if ( $update ) {
			wp_send_json_success( [
				'message' => esc_html__( 'Success! The profile has been updated.', 'advance-user-registration' ),
			] );
		} else {
			wp_send_json_success( [
				'error' => esc_html__( 'Something went wrong! Please try again later.', 'advance-user-registration' ),
			] );
		}
		
	}

	/**
	 * Method avur_approve_user.
	 */
	public function avur_approve_user() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce             = isset( $_REQUEST['_nonce'] ) && '' !== $_REQUEST['_nonce'] ? sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ) : '';
		$avur_get_settings = get_option( 'avur_get_settings', true ) ? get_option( 'avur_get_settings', true ) : [];
		$role              = isset( $avur_get_settings['avur-setting-user-role'] ) && ! empty( $avur_get_settings['avur-setting-user-role'] ) ? $avur_get_settings['avur-setting-user-role'] : 'subscriber';
		if ( ! wp_verify_nonce( $nonce, 'avur-admin-nonce' ) ) {
			return esc_html__( 'Nonce Varification Failed!', 'advance-user-registration' );
		}

		$user_id = isset( $_POST['user_id'] ) && ! empty( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : '';

		if ( $user_id ) {

			// Update user role to contributor
			$user_data = array(
				'ID'   => $user_id,
				'role' => $role,
            );
            wp_update_user( $user_data );

			wp_send_json_success( [
				'message' => esc_html__( 'Success! The user has been approved.', 'advance-user-registration' ),
			] );
		} else {
			wp_send_json_success( [
				'error' => esc_html__( 'Something went wrong! Please try again later.', 'advance-user-registration' ),
			] );
		}
	}

	/**
	 * Method avur_delete_user.
	 */
	public function avur_delete_user() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce = isset( $_REQUEST['_nonce'] ) && '' !== $_REQUEST['_nonce'] ? sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ) : '';
	
		if ( ! wp_verify_nonce( $nonce, 'avur-admin-nonce' ) ) {
			return esc_html__( 'Nonce Varification Failed!', 'advance-user-registration' );
		}

		$user_id = isset( $_POST['user_id'] ) && ! empty( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : '';
		$reslut  = avur_delete_user_by_id( $user_id );

		if ( $reslut ) {
			wp_send_json_success( [
				'message' => esc_html__( 'Success! The user has been deleted.', 'advance-user-registration' ),
			] );
		} else {
			wp_send_json_success( [
				'error' => esc_html__( 'Something went wrong! Please try again later.', 'advance-user-registration' ),
			] );
		}
	}

	/**
	 * Method avur_save_settings_data.
	 */
	public function avur_save_settings_data() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce = isset( $_REQUEST['_nonce'] ) && '' !== $_REQUEST['_nonce'] ? sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'avur-admin-nonce' ) ) {
			return esc_html__( 'Nonce Varification Failed!', 'wpte-product-layout' );
		}

		$inserted_datas = isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ? filter_input( INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY ) : [];

		$avur_options_array = [];
		foreach( $inserted_datas as $inserted_data ) {
			$avur_options_array[$inserted_data['name']] = $inserted_data['value'];
		}

		if ( $avur_options_array ) {
			update_option( 'avur_get_settings', $avur_options_array );
			
			wp_send_json_success( [
				'message' => esc_html__( 'Success! Data has been saved.', 'advance-user-registration' ),
			] );

		} else {
			wp_send_json_success( [
				'error' => esc_html__( 'Something went wrong! Please try again later.', 'advance-user-registration' ),
			] );
		}

	}

}
