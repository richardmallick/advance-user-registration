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

		add_action( 'wp_ajax_avur_approve_user', [ $this, 'avur_approve_user' ] );

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
