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
        global $wp_roles;
        $all_roles = $wp_roles->get_names();
        $avur_get_settings      = get_option( 'avur_get_settings', true ) ? get_option( 'avur_get_settings', true ) : [];
        $avur_user_role         = isset( $avur_get_settings['avur-setting-user-role'] ) ? $avur_get_settings['avur-setting-user-role'] : '';
        $avur_email_verify_sub  = isset( $avur_get_settings['avur-email-verify-sub'] ) ? $avur_get_settings['avur-email-verify-sub'] : '';
        $avur_email_verify_body = isset( $avur_get_settings['avur-email-verify-body'] ) ? $avur_get_settings['avur-email-verify-body'] : '';
        ?>
       <div class="wrap">
           <div class="avur-admin-form-wrap">
                <h1><?php echo esc_html__( 'Settings', 'advance-user-registration' ) ?></h1>
                <br>
                <form id="avur-option-data-form" action="">
                    <div class="form-field">
                        <label for="avur-setting-user-role"><?php echo esc_html__( 'Role', 'advance-user-registration' ) ?></label>
                        <select name="avur-setting-user-role" id="avur-setting-user-role">
                            <option value="">--Select--</option>
                            <?php
                            foreach( $all_roles as $all_role => $r_name ) {
                                $selected = $all_role === $avur_user_role ? 'selected' : '';
                                printf( '<option value="%s" %s>%s</option>', $all_role, $selected, $r_name );
                            }
                            ?>
                        </select>
                        <p><?php echo esc_html__( 'Set the default role. User will get the role after approval.', 'advance-user-registration' ) ?></p>
                    </div>
                    <div class="form-field">
                        <label for="avur-email-verify-sub"><?php echo esc_html__( 'Verification Email Sub', 'advance-user-registration' ) ?></label>
                        <input type="text" name="avur-email-verify-sub" id="avur-email-verify-sub" placeholder="Subject" value="<?php echo esc_attr( $avur_email_verify_sub ); ?>">
                    </div>
                    <div class="form-field">
                        <label for="avur-email-verify-body"><?php echo esc_html__( 'Verification Email Body', 'advance-user-registration' ) ?></label>
                        <textarea name="avur-email-verify-body" id="avur-email-verify-body" cols="30" rows="5" placeholder="Write here..."><?php echo esc_textarea( $avur_email_verify_body ); ?></textarea>
                    </div>
                    <div class="form-field">
                        <input type="submit" id="avur-option-data" name="submit" class="button button-primary" value="Update">
                    </div>
                </form>
                    
           </div>
        </div>
       <?php
    }
}