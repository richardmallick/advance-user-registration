<?php

namespace AV_USER_REGISTRATION\Includes\Admin\Pages;

/**
 * UsersSettings Class
 *
 * @since 1.0.0
 */
class Avur_users_settings {

    /**
     * Method custom_user_list_page.
     *
     * @since 1.0.0
     */
    function avur_users_settins() {
        ?>
       <div class="wrap">
           <div class="avur-admin-form-wrap">
                <h1><?php echo esc_html__( 'Settings', 'advance-user-registration' ) ?></h1>
                <br>
           </div>
        </div>
       <?php
    }
}