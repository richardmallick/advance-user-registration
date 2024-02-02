<?php

namespace AV_USER_REGISTRATION\Includes\Admin\Pages;

/**
 * UserManagementDashboard Class
 *
 * @since 1.0.0
 */
class UserManagementDashboard {

    /**
     * Method custom_user_list_page.
     *
     * @since 1.0.0
     */
    function custom_user_list_page() {
        // Query users
        $args = array(
            'role__in' => array( 'subscriber', 'contributor', 'author', 'editor', 'administrator' ), // Include desired roles
            'orderby' => 'display_name', // Order by display name
            'order' => 'ASC', // Ascending order
            'number' => -1, // Retrieve all users
        );
        $user_query = new \WP_User_Query( $args );

        // Get the results
        $users = $user_query->get_results();

        $column_headers = [
            'ID',
            'Name',
            'Username',
            'Email',
            'Phone',
            'Address',
            'Website',
            'Role',
            'Action',
        ];

        // Display filterable data table
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'User List', 'advance-user-registration' ) ?></h1>
            <br>
            <form id="user-filter-form">
                <input type="text" id="user-filter-input" placeholder="Filter by">
            </form>
            <br>
            <table id="user-list-table" class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <?php 
                        foreach( $column_headers as $column_header ) {
                            printf('<th>%s</th>', esc_html__( $column_header, 'advance-user-registration' ) );
                        } 
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($users as $user) :
                        $f_name  = get_user_meta( $user->ID, 'first_name', true ) ? get_user_meta( $user->ID, 'first_name', true ) : '';
                        $l_name  = get_user_meta( $user->ID, 'last_name', true ) ? get_user_meta( $user->ID, 'last_name', true ) : '';
                        $phone   = get_user_meta( $user->ID, 'phone_number', true ) ? get_user_meta( $user->ID, 'phone_number', true ) : '';
                        $address = get_user_meta( $user->ID, 'address', true ) ? get_user_meta( $user->ID, 'address', true ) : '';
                    ?>
                        <tr>
                            <td><?php echo intval( $user->ID ); ?></td>
                            <td><?php echo esc_html( $f_name . ' ' . $l_name ); ?></td>
                            <td><?php echo esc_html( $user->user_login ); ?></td>
                            <td><?php echo esc_html( $user->user_email ); ?></td>
                            <td><?php echo esc_html( $phone ); ?></td>
                            <td><?php echo esc_html( $address ); ?></td>
                            <td><?php echo esc_url( $user->user_url ); ?></td>
                            <td><?php echo implode(', ', $user->roles); ?></td>
                            <td>
                                <button id="avur-user-edit" dataid="<?php echo intval( $user->ID ); ?>"><?php echo esc_html__('Edit', 'advance-user-registration') ?></button>
                                <button id="avur-user-approve" dataid="<?php echo intval( $user->ID ); ?>"><?php echo esc_html__('Approve', 'advance-user-registration') ?></button>
                                <button id="avur-user-deny" dataid="<?php echo intval( $user->ID ); ?>"><?php echo esc_html__('Deny', 'advance-user-registration') ?></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <script>
            jQuery(document).ready(function($) {
                // Filter data table
                $('#user-filter-input').on('input', function() {
                    var filter = $(this).val().toLowerCase();
                    $('#user-list-table tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(filter) > -1);
                    });
                });
            });
        </script>
    <?php
    }
}