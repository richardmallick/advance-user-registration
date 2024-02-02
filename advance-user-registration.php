<?php
/**
 * Plugin Name:       Advance User Registration
 * Plugin URI:        https://richard-mallick.com
 * Description:       This is Advance User Registration Plugin.
 * Version:           1.0.0
 * Author:            Richard
 * Author URI:        https://richard-mallick.com
 * Text Domain:       advance-user-registration
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Advance User Registration.
 */

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( esc_html__( 'You can\'t access this page', 'advance-user-registration' ) );
}

/**
 * Included Autoload File
 */
require_once __DIR__ . '/vendor/autoload.php';

/** If class `Advance_User_Registration` doesn't exists yet. */
if ( ! class_exists( 'Advance_User_Registration' ) ) {

    /**
     * Sets up and initializes the plugin.
     * Main initiation class
     *
     * @since 1.0.0
     */
    final class Advance_User_Registration {

        /**
         * Plugin Version
         */
        const VERSION = '1.0.0';

        /**
         * Php Version
         */
        const MIN_PHP_VERSION = '7.4';

        /**
         * WordPress Version
         */
        const MIN_WP_VERSION = '6.2';

        /**
         * Class Constractor
         */
        private function __construct() {

            $this->define_constance();
            register_activation_hook( __FILE__, [ $this, 'activate' ] );
            register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
            add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
            add_action( 'init', [ $this, 'i18n' ] );
            add_action( 'admin_init', [$this, 'activation_redirect'] );
            add_filter( 'plugin_action_links_' . plugin_basename( AVUR_FILE ), [ __CLASS__, 'avur_action_links' ] );
        }

        /**
         * Initilize a singleton instance
         *
         * @return /Advance_User_Registration
         */
        public static function init() {

            static $instance = false;

            if ( ! $instance ) {

                $instance = new self();
            }

            return $instance;
        }

        /**
         * Plugin Constance
         *
         * @return void
         */
        public function define_constance() {

            define( 'AVUR_VERSION', self::VERSION );
            define( 'AVUR_FILE', __FILE__ );
            define( 'AVUR_PATH', plugin_dir_path( __FILE__ ) );
            define( 'AVUR_URL', plugins_url( '', AVUR_FILE ) );
            define( 'AVUR_ASSETS', AVUR_URL . '/assets/' );
            define( 'AVUR_MINIMUM_PHP_VERSION', self::MIN_PHP_VERSION );
            define( 'AVUR_MINIMUM_WP_VERSION', self::MIN_WP_VERSION );
        }

        /**
         * Load Textdomain
         *
         * Load plugin localization files.
         *
         * Fired by `init` action hook.
         *
         * @since 1.0.0
         *
         * @access public
         */
        public function i18n() {
            load_plugin_textdomain( 'advance-user-registration' );
        }

        /**
         * After Activate Plugin
         *
         * Fired by `register_activation_hook` hook.
         *
         * @return void
         *
         * @since 1.0.0
         *
         * @access public
         */
        public function activate() {
            new AV_USER_REGISTRATION\Includes\Installation();
        }

        /**
         * After Deactivate Plugin
         *
         * @since 1.0.0
         * 
         * @return void
         */
        public function deactivate() {
            // Plugin deactivation code here.
        }

        /**
         * Plugins Loaded
         *
         * @since 1.0.0
         * 
         * @return void
         */
        public function init_plugin() {

            new AV_USER_REGISTRATION\Includes\Assets();

            if ( is_admin() ) {
                new AV_USER_REGISTRATION\Includes\Admin();
            }
            new AV_USER_REGISTRATION\Includes\Frontend();

        }

        /**
         *
         * Redirect to settings page after activation the plugin
         * 
         * @since 1.0.0
         */
        public function activation_redirect() {

            if ( get_option( 'avur_activation_redirect', false ) ) {

                delete_option( 'avur_activation_redirect' );

                wp_safe_redirect( admin_url( 'admin.php?page=advance-users' ) );
                exit();
            }
        }

        /**
         * Plugin Page Settings menu
         *
         * @since 1.0.0
         * 
         * @param mixed $links .
         */
        public static function avur_action_links( $links ) {

            if ( ! current_user_can( 'manage_options' ) ) {
                return $links;
            }

            $links = array_merge(
                [
                    sprintf(
                        '<a href="%s">%s</a>',
                        admin_url( 'admin.php?page=advance-users' ),
                        esc_html__( 'Settings', 'advance-user-registration' )
                    ),
                ],
                $links
            );

            return $links;
        }
    }

}

/**
 * Initilize the main plugin
 *
 * @since 1.0.0
 * 
 * @return /Advance_User_Registration
 */
function ad_user_registration() {

    if ( class_exists( 'Advance_User_Registration' ) ) {
        return Advance_User_Registration::init();
    }

    return false;
}

/**
 * Kick-off the plugin
 */
ad_user_registration();
