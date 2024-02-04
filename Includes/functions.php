<?php

/**
 * Registration from data insert to avur_users table
 * 
 * @param array $args .
 * 
 * @since 1.0.0
 */
function avur_user_data_insert( $args = [] ) {

    global $wpdb;

    $default = [
        'user_login'          => '',
        'user_pass'           => '',
        'user_nicename'       => '',
        'user_email'          => '',
        'user_url'            => '',
        'user_activation_key' => '',
        'user_status'         => 0,
    ];

    $data = wp_parse_args( $args, $default );

    $inserted = $wpdb->insert(
        "{$wpdb->prefix}avur_users",
        $data,
        [
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%d',
        ]
    );

    if ( ! $inserted ) {
        return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data', 'advanced-user-registration' ) );
    }

    return $wpdb->insert_id;
}

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
 * Update user meta based on User ID and Key
 * 
 * @since 1.0.0
 */
function update_avur_user_meta( $user_id, $meta_key, $meta_value ) {
    global $wpdb;

    $user_id    = absint( $user_id );
    $meta_key   = sanitize_key( $meta_key );
    $meta_value = maybe_serialize( $meta_value );

    $existing_meta = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}avur_usermeta WHERE user_id = %d AND meta_key = %s",
            $user_id,
            $meta_key
        )
    );

    if ( $existing_meta ) {
        $wpdb->update(
            "{$wpdb->prefix}avur_usermeta",
            array( 'meta_value' => $meta_value ),
            array( 'user_id' => $user_id, 'meta_key' => $meta_key ),
            array( '%s' ),
            array( '%d', '%s' )
        );
    } else {
        $wpdb->insert(
            "{$wpdb->prefix}avur_usermeta",
            array(
                'user_id'    => $user_id,
                'meta_key'   => $meta_key,
                'meta_value' => $meta_value,
            ),
            array( '%d', '%s', '%s' )
        );
    }
}

/**
 * Get user meta based on User ID and Key
 * 
 * @since 1.0.0
 */
function get_avur_user_meta( $user_id, $meta_key, $single = false ) {
    global $wpdb;

    $user_id  = absint( $user_id );
    $meta_key = sanitize_key( $meta_key );

    $sql = $wpdb->prepare(
        "SELECT meta_value FROM {$wpdb->prefix}avur_usermeta WHERE user_id = %d AND meta_key = %s",
        $user_id,
        $meta_key
    );

    $meta_values = $wpdb->get_col( $sql );

    if ( $meta_values && $single ) {
        return maybe_unserialize( $meta_values[0] );
    } elseif ( $meta_values && ! $single ) {
        return array_map( 'maybe_unserialize', $meta_values );
    } else {
        return $single ? '' : array();
    }
}

/**
 * Fetch data from user table
 * 
 * @since 1.0.0
 */
function avur_fetch_data_from_user_table( $users_per_page, $offset ) {
    
    $approved_users = get_users([
        'number' => $users_per_page,
        'offset' => $offset
    ]);

    return $approved_users;
}

/**
 * Fetch data from avur user table
 * 
 * @since 1.0.0
 */
function avur_fetch_data_from_avur_user_table( $users_per_page, $offset ) {

    global $wpdb;

    // Calculate offset and limit
    $limit  = $users_per_page;
    $offset = absint( $offset );

    if ( $limit > 0 ) {
        $users = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}avur_users LIMIT %d OFFSET %d",
                $limit,
                $offset
            ),
            ARRAY_A
        );
    } else {
        $users = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}avur_users"
            ),
            ARRAY_A
        );
    }
    

    return $users;
}

/**
 * Fetch data from avur user table by id
 * 
 * @since 1.0.0
 */
function avur_fetch_data_from_avur_user_table_by_id( $user_id ) {
    
    global $wpdb;

    // Prepare SQL query
    $query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}avur_users WHERE ID = %d", $user_id );

    // Fetch user from database
    $users_array = $wpdb->get_row($query);

    $args = [
        'user_login'    => $users_array->user_login,
        'user_email'    => $users_array->user_email,
        'user_pass'     => $users_array->user_pass,
        'user_nicename' => $users_array->user_nicename,
    ];
    
    $table_name = $wpdb->prefix . 'users';
    $wpdb->insert($table_name, $args);
    $user_id = $wpdb->insert_id;
    return $user_id;
}

/**
 * Delete user after approve
 * 
 * @since 1.0.0
 */
function avur_delete_from_avur_user_table_by_id($user_id) {
    global $wpdb;

    // Delete the user from the avur_users table
    $wpdb->delete(
        $wpdb->prefix . 'avur_users',
        array('ID' => $user_id)
    );
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
