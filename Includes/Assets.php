<?php

namespace AV_USER_REGISTRATION\Includes;

/**
 * Assets Handler Class
 *
 * @since 1.0.0
 */
class Assets {

	/**
	 * Assets class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scriptss' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'public_enqueue_scripts' ] );
	}

	/**
	 * Method admin_enqueue_scriptss.
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue_scriptss() {

		$current_screen = get_current_screen()->id;
		
		if ( 'toplevel_page_advance-user-registration' === $current_screen ) {
			// CSS
			wp_enqueue_style( 'avur-admin-style', AVUR_ASSETS . 'css/admin-style.css', null, filemtime( AVUR_PATH . 'assets/css/admin-style.css' ) );
			
			// JS
			wp_enqueue_script( 'avur-admin-script', AVUR_ASSETS . 'js/admin-script.js', ['jquery'], filemtime( AVUR_PATH . 'assets/js/admin-script.js' ), true );
			wp_localize_script(
				'avur-admin-script',
				'avurAdmin',
				[
					'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
					'wpte_nonce' => wp_create_nonce( 'avur-admin-nonce' ),
					'error'      => __( 'Something Went Wrong!', 'advance-user-registration' ),
				]
			);
		}
	}

	/**
	 * Method public_enqueue_scripts.
	 *
	 * @since 1.0.0
	 */
	public function public_enqueue_scripts() {
		// CSS
		wp_enqueue_style( 'avur-public-style', AVUR_ASSETS . 'css/style.css', null, filemtime( AVUR_PATH . 'assets/css/style.css' ) );
		
		// JS
		wp_register_script( 'avur-public-script', AVUR_ASSETS . 'js/script.js', ['jquery'], filemtime( AVUR_PATH . 'assets/js/script.js' ), true );

		wp_localize_script(
			'avur-public-script',
			'avurForm',
			[
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'avur_nonce' => wp_create_nonce( 'avur-form-nonce' ),
				'error'      => __( 'Something Went Wrong!', 'advance-user-registration' ),
			]
		);
	}

}
