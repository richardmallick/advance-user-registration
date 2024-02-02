<?php

namespace AV_USER_REGISTRATION\Includes\Frontend\Views;

/**
 * Registration form trait
 *
 * @since 1.0.0
 */
trait RegistrationForm{

    public function registration_form() {
        ?>
        <div class="avur-container">
            <h2><?php echo esc_html__( 'Registration Form', 'advance-user-registration' ); ?></h2>
            <form action="#" method="post" id="avur-registration-form" class="avur-form">
                <div class="avur-form-group">
                    <label for="avur-first-name"><?php echo esc_html__( 'First Name', 'advance-user-registration' ); ?><span>*</span></label>
                    <input type="text" id="avur-first-name" name="avur-first-name">
                </div>
                <div class="avur-form-group">
                    <label for="avur-last-name"><?php echo esc_html__( 'Last Name', 'advance-user-registration' ); ?><span>*</span></label>
                    <input type="text" id="avur-last-name" name="avur-last-name">
                </div>
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
                    <label for="avur-confirm-password"><?php echo esc_html__( 'Confirm Password', 'advance-user-registration' ); ?><span>*</span></label>
                    <input type="password" id="avur-confirm-password" name="avur-confirm-password">
                </div>
                <div class="avur-form-group">
                    <label for="avur-phone"><?php echo esc_html__( 'Phone Number', 'advance-user-registration' ); ?><span>*</span></label>
                    <input type="tel" id="avur-phone" name="avur-phone">
                </div>
                <div class="avur-form-group">
                    <label for="avur-address"><?php echo esc_html__( 'Address', 'advance-user-registration' ); ?><span>*</span></label>
                    <input type="text" id="avur-address" name="avur-address">
                </div>
                <div class="avur-form-group">
                    <label for="avur-website"><?php echo esc_html__( 'Website', 'advance-user-registration' ); ?></label>
                    <input type="text" id="avur-website" name="avur-website">
                </div>

                <div class="avur-error-message"></div>
                
                <button type="submit" id="avur-registration"><?php echo esc_html__( 'Register', 'advance-user-registration' ); ?></button>
            </form>
        </div>
        <?php
    }
}