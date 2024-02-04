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
		$this->avur_db();
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

	/**
	 * Advanced users DB
	 *
	 * @since 1.0.0
	 */
	private function avur_db() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
	
		// Table for users
		$users_table_name = $wpdb->prefix . 'avur_users';
		$users_sql        = "CREATE TABLE $users_table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_login varchar(50) NOT NULL,
			user_pass varchar(255) NOT NULL,
			user_nicename varchar(50) NOT NULL,
			user_email varchar(100) NOT NULL,
			user_url varchar(100) NOT NULL,
			user_activation_key varchar(255),
			user_status INT(10),
			PRIMARY KEY  (id)
		) $charset_collate;";
	
		// Table for user meta data
		$user_meta_table_name = $wpdb->prefix . 'avur_usermeta';
		$user_meta_sql        = "CREATE TABLE $user_meta_table_name (
			umeta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			user_id bigint(20) unsigned NOT NULL,
			meta_key varchar(255) DEFAULT NULL,
			meta_value longtext,
			PRIMARY KEY  (umeta_id),
			KEY user_id (user_id),
			KEY meta_key (meta_key(191))
		) $charset_collate;";
	
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $users_sql );
		dbDelta( $user_meta_sql );
	}	
}
