<?php

/**
 * Registration form error handling
 * 
 * @param array $args .
 * 
 * @since 1.0.0
 */
function avur_form_erro_handling( $output_array ) {

    $username         = $output_array['avur-username'];
    $email            = $output_array['avur-email'];
    $password         = $output_array['avur-password'];
    $confirm_password = $output_array['avur-confirm-password'];

    $errors = [];

    if ( empty( $username ) ) {
        $errors[] = esc_html__( 'Username is Required', 'advance-user-registration'); 
    }
    if ( empty( $email ) ) {
        $errors[] = esc_html__( 'Email Address is Required', 'advance-user-registration'); 
    } elseif ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        $errors[] = esc_html__( 'Invalid Email Address', 'advance-user-registration' );
    }
    if ( empty( $password ) ) {
        $errors[] = esc_html__( 'Password is Required', 'advance-user-registration'); 
    }
    if ( empty( $confirm_password ) ) {
        $errors[] = esc_html__( 'Confirm Password is Required', 'advance-user-registration'); 
    } elseif ( $password !== $confirm_password ) {
        $errors[] = esc_html__( 'Confirm password should be match with password', 'advance-user-registration'); 
    }

    if ( $errors ) {
        ob_start();
        echo '<ul>';
        foreach( $errors as $error ) {
            echo "<li>$error</li>";
        }
        echo '</ul>';
        return ob_get_clean();
    }

    return false;
}

/**
 * Generate verification token
 * 
 * @since 1.0.0
 */
function avur_registration_fields_validate($input_array) {
    foreach ($input_array as $item) {
        if (in_array('', $item)) {
            return false;
        }
    }
    return true;
}

/**
 * Delete user after approve
 * 
 * @since 1.0.0
 */
function avur_delete_user_by_id( $user_id ) {
    global $wpdb;

    // Delete the user from the avur_users table
    $result = $wpdb->delete(
        $wpdb->prefix . 'users',
        array('ID' => $user_id)
    );

    return $result;
}

/**
 * Generate verification token
 * 
 * @since 1.0.0
 */
function avur_generate_verification_token() {

    $random_bytes       = random_bytes(32);
    $verification_token = base64_encode($random_bytes);
    $verification_token = strtr($verification_token, '+/', '-_');
    $verification_token = rtrim($verification_token, '=');

    return $verification_token;
}

/**
 * Save settings data
 * 
 * @since 1.0.0
 */
function avur_save_admin_settings_data( $datas ) {
    
    $datas = [
        [
            'key' => '',
            'value' => ''
        ]
    ];

    foreach( $datas as $data ) {
        update_option( $data['key'], $data['value'] );
    }

}

function avur_send_email_for_verification( $username, $user_email, $verification_token ) {

    $verification_url = add_query_arg(
        array(
            'token' => $verification_token,
            'username' => $username,
        ),
        home_url('/verify-email')
    );

    $email_subject = 'Verify Your Email Address';
    $email_message = 'Thank you for registering. Please click on the following link to verify your email address: ' . $verification_url;

    // Send the verification email to the user
    wp_mail( $user_email, $email_subject, $email_message );
}
