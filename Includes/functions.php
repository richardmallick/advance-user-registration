<?php

/**
 * Registration form error handling
 * 
 * @param array $args .
 * 
 * @since 1.0.0
 */
function avur_form_erro_handling( $output_array ) {

    $first_name       = $output_array['avur-first-name'];
    $last_name        = $output_array['avur-last-name'];
    $username         = $output_array['avur-username'];
    $email            = $output_array['avur-email'];
    $phone            = $output_array['avur-phone'];
    $password         = $output_array['avur-password'];
    $confirm_password = $output_array['avur-confirm-password'];
    $address          = $output_array['avur-address'];

    $errors = [];

    if ( empty( $first_name ) ) {
        $errors[] = esc_html__( 'First Name is Required', 'advance-user-registration'); 
    }
    if ( empty( $last_name ) ) {
        $errors[] = esc_html__( 'Last Name is Required', 'advance-user-registration'); 
    }
    if ( empty( $username ) ) {
        $errors[] = esc_html__( 'Username is Required', 'advance-user-registration'); 
    }
    if ( empty( $email ) ) {
        $errors[] = esc_html__( 'Email Address is Required', 'advance-user-registration'); 
    } elseif ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        $errors[] = esc_html__( 'Invalid Email Address', 'advance-user-registration' );
    }
    if ( empty( $phone ) ) {
        $errors[] = esc_html__( 'Phone is Required', 'advance-user-registration'); 
    }
    if ( empty( $password ) ) {
        $errors[] = esc_html__( 'Password is Required', 'advance-user-registration'); 
    }
    if ( empty( $confirm_password ) ) {
        $errors[] = esc_html__( 'Confirm Password is Required', 'advance-user-registration'); 
    } elseif ( $password !== $confirm_password ) {
        $errors[] = esc_html__( 'Confirm password should be match with password', 'advance-user-registration'); 
    }
    if ( empty( $address ) ) {
        $errors[] = esc_html__( 'Address is Required', 'advance-user-registration'); 
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
function avur_generate_verification_token() {

    $random_bytes       = random_bytes(32);
    $verification_token = base64_encode($random_bytes);
    $verification_token = strtr($verification_token, '+/', '-_');
    $verification_token = rtrim($verification_token, '=');

    return $verification_token;
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


// function custom_rewrite_rules() {
//     add_rewrite_rule('^verify-email/?', 'index.php?verify_email=true', 'top');
// }
// add_action('init', 'custom_rewrite_rules');

// function custom_query_vars( $vars ) {
//     $vars[] = 'verify_email';
//     return $vars;
// }
// add_filter('query_vars', 'custom_query_vars');

// function handle_email_verification() {
//     if ( get_query_var('verify_email') ) {
        
//         $token = isset( $_GET['token'] ) ? sanitize_text_field( $_GET['token'] ) : '';

//         // Check if the token exists in the database
//         $user_id = get_user_by_meta_data('verification_token', $token);

//         if ($user_id) {
//             // Mark the user as verified (update user meta or custom field)
//             update_user_meta($user_id, 'email_verified', true);

//             // Display a confirmation message to the user
//             echo 'Your email address has been verified. You can now login.';
//         } else {
//             // Token not found or invalid
//             echo 'Invalid verification token.';
//         }

//         exit; // Stop further execution
//     }
// }
// add_action('template_redirect', 'handle_email_verification');