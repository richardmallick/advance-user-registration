<?php

namespace AV_USER_REGISTRATION\Includes;

/**
 * Frontend Handler Class
 *
 * @since 1.0.0
 */
class Frontend {

	/**
	 * Frontend class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		new Frontend\Shortcode();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			new Frontend\Ajax();
		}
	}
}
