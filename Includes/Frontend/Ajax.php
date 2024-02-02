<?php

namespace AV_USER_REGISTRATION\Includes\Frontend;

/**
 * Frontend Ajax Handler Class
 */
class Ajax {

	/**
	 * Method __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_ajax_avur_user_registration', [ $this, 'avur_user_registration' ] );
	}

	/**
	 * Method avur_user_registration.
	 */
	public function avur_user_registration() {

		$nonce = isset( $_REQUEST['_nonce'] ) && '' !== $_REQUEST['_nonce'] ? sanitize_text_field( wp_unslash( $_REQUEST['_nonce'] ) ) : '';
	
		if ( ! wp_verify_nonce( $nonce, 'avur-form-nonce' ) ) {
			return esc_html__( 'Nonce Varification Failed!', 'advance-user-registration' );
		}

		$inserted_datas = isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ? filter_input( INPUT_POST, 'data', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY ) : [];

		$users_array = [];
		foreach( $inserted_datas as $inserted_data ) {
			$users_array[$inserted_data['name']] = $inserted_data['value'];
		}

		$errors = avur_form_erro_handling( $users_array );

		if ( $errors ) {
			wp_send_json_success( [
				'error' => $errors,
			] );
		}

		$username    = $users_array['avur-username'] ? sanitize_text_field( $users_array['avur-username'] ) : '';
		$email       = $users_array['avur-email'] ? sanitize_email( $users_array['avur-email'] ) : '';
		$password    = $users_array['avur-password'] ? sanitize_text_field( $users_array['avur-password'] ) : '';
		
		$args = [
			'user_login'    => $username,
			'user_email'    => $email,
			'user_pass'     => $password,
			'user_nicename' => $username,
		];

		unset( $users_array['avur-username'] );
		unset( $users_array['avur-email'] );
		unset( $users_array['avur-password'] );
		unset( $users_array['avur-confirm-password'] );

		$user_id = wp_insert_user( $args );

		if ( $user_id ) {

			 // Update user meta with phone number and address
			 update_user_meta( $user_id, 'avur_user_meta_data', $users_array );

			// Update user role to contributor
			$user_data = array(
				'ID'   => $user_id,
				'role' => 'contributor',
            );
            wp_update_user( $user_data );
			 
			// $verification_token  = avur_generate_verification_token();
			//avur_send_email_for_verification( $username, $email, $verification_token );

			wp_send_json_success( [
				'message' => esc_html__( 'Success! An email has been sent to your email address. Please verify your email to gain access.', 'advance-user-registration' ),
			] );
		} else {
			wp_send_json_success( [
				'error' => esc_html__( 'Something went wrong! Please try again later.', 'advance-user-registration' ),
			] );
		}
	}

}