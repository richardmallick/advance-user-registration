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

		//add_action( 'wp_ajax_avur_approve_user', [ $this, 'avur_approve_user' ] );
		add_action( 'wp_ajax_avur_user_fields', [ $this, 'avur_user_fields' ] );

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
	 * Method avur_approve_user
	 *
	 * @return mixed
	 */
	public function avur_approve_user() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce = isset( $_REQUEST['_nonce'] ) && '' !== $_REQUEST['_nonce'] ? sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'avur-admin-nonce' ) ) {
			return esc_html__( 'Nonce Varification Failed!', 'wpte-product-layout' );
		}

	}

}
