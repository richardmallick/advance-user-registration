<?php

namespace AV_USER_REGISTRATION\Includes\Frontend\Views;

/**
 * Registration form trait
 *
 * @since 1.0.0
 */
trait Avur_registration_form{

    public function registration_form() {
        $avur_option_data = get_option( 'avur_user_registration_fields', true );
        ?>
        <div class="avur-container">
            <h2><?php echo esc_html__( 'Registration Form', 'advance-user-registration' ); ?></h2>
            <form action="#" method="post" id="avur-registration-form" class="avur-form">
                <div class="avur-form-group">
                    <label for="avur-username"><?php echo esc_html__( 'Username', 'advance-user-registration' ); ?><span>*</span></label>
                    <input type="text" id="avur-username" name="avur-username">
                </div>
                <div class="avur-form-group">
                    <label for="avur-email"><?php echo esc_html__( 'Email Address', 'advance-user-registration' ); ?><span>*</span></label>
                    <input type="email" id="avur-email" name="avur-email">
                </div>
                <div class="avur-form-group">
                    <label for="avur-password"><?php echo esc_html__( 'New Password', 'advance-user-registration' ); ?><span>*</span></label>
                    <input type="password" id="avur-password" name="avur-password">
                </div>
                <div class="avur-form-group">
                    <label for="avur-confirm-password"><?php echo esc_html__( 'Confirm Password', 'advanced-user-registration' ); ?><span>*</span></label>
                    <input type="password" id="avur-confirm-password" name="avur-confirm-password">
                </div>
                <?php
                foreach ( $avur_option_data as $avur_data ) {
                    
                    ?>
                    <div class="avur-form-group">
                        <label for="avur-password"><?php echo esc_html__( $avur_data[1], 'advance-user-registration' ); ?></label>
                        <input type="<?php echo esc_attr( trim( $avur_data[0] ) ); ?>" id="<?php echo esc_attr( trim( $avur_data[2] ) ); ?>" name="<?php echo esc_attr( trim( $avur_data[2] ) ); ?>">
                    </div>
                    <?php
                }
                ?>
                <div class="avur-error-message"></div>
                <button type="submit" id="avur-registration"><?php echo esc_html__( 'Register', 'advance-user-registration' ); ?></button>
            </form>
        </div>
        <?php
    }
}