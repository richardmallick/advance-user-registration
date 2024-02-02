<?php

namespace AV_USER_REGISTRATION\Includes\Frontend;

use AV_USER_REGISTRATION\Includes\Frontend\Views\RegistrationForm;

/**
 * Create Shortcode
 *
 * @since 1.0.0
 */
class Shortcode {

	use RegistrationForm;
	
	/**
	 * Shortcode class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_shortcode('avur_registration_form', [ $this, 'avur_registration_form_render' ] );
	}

	/**
	 * Registration Form Display
	 *
	 * @param array $attributes .
	 * @since 1.0.0
	 */
	public function avur_registration_form_render( $attributes ) {

		wp_enqueue_script('avur-public-script');

		ob_start();
		$this->registration_form();
		$data = ob_get_clean();

		return ( $data );
	}

}
