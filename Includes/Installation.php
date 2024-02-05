<?php

namespace AV_USER_REGISTRATION\Includes;

/**
 * Plugin Installation Class
 *
 * @since 1.0.0
 */
class Installation {

	/**
	 * Installation class constructor
	 *
	 * Database
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->activate();
	}

	/**
	 * After activate Plugin
	 *
	 * @since 1.0.0
	 */
	private function activate() {

		$installed = get_option( 'avur_installed' );

		if ( ! $installed ) {
			update_option( 'avur_installed', time() );
		}

		update_option( 'avur_version', AVUR_VERSION );

		add_option( 'avur_activation_redirect', true );
	}	
}
