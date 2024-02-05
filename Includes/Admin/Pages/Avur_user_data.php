<?php

namespace AV_USER_REGISTRATION\Includes\Admin\Pages;

/**
 * UserManagementDashboard Class
 *
 * @since 1.0.0
 */
class Avur_user_data {

    /**
     * Method custom_user_list_page.
     *
     * @since 1.0.0
     */
    function custom_user_list_page() {

        // Pagination parameters
        $offset = 0;
        $users_per_page = -1;

        $users = get_users([
            'number' => $users_per_page,
            'offset' => $offset
        ]);

        $column_headers = [
            'ID',
            'Username',
            'Email',
            'Role',
            'Email Verifyed',
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
                            $is_varified = get_user_meta( $user->ID, 'avur_is_verify_email', true ) ? get_user_meta( $user->ID, 'avur_is_verify_email', true ) : '';
                        ?>
                        <tr>
                            <td><?php echo intval( $user->ID ); ?></td>
                            <td><?php echo esc_html( $user->user_login ); ?></td>
                            <td><?php echo esc_html( $user->user_email ); ?></td>
                            <td><?php echo implode( ', ', $user->roles ); ?></td>
                            <td><?php echo esc_html( $is_varified ); ?></td>
                            <td>
                            <a href="<?php echo admin_url( 'admin.php?page=advance-users&user_id=' . intval( $user->ID ) . '' ); ?>"><button class="avur-user-edit"><?php echo esc_html__( 'Edit', 'advance-user-registration' ) ?></button></a>
                                <?php if ( $user->roles ) : ?>
                                    <button class="avur-user-active"><?php echo esc_html__('Active', 'advance-user-registration') ?></button>
                                <?php else: ?>
                                <button class="avur-user-approve" dataid="<?php echo intval( $user->ID ); ?>"><?php echo esc_html__('Approve', 'advance-user-registration') ?></button>
                                <button class="avur-user-deny" dataid="<?php echo intval( $user->ID ); ?>"><?php echo esc_html__('Deny', 'advance-user-registration') ?></button>
                                <?php endif ?>
                                <button class="avur-user-delete" dataid="<?php echo intval( $user->ID ); ?>"><?php echo esc_html__('Delete', 'advance-user-registration') ?></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php
    }

    /**
     * Method avur_edit_user.
     *
     * @since 1.0.0
     */
    public function avur_edit_user() {
        global $wp_roles;

        $user_id  = isset( $_GET['user_id'] ) ? sanitize_text_field( $_GET['user_id'] ) : '';
        if ( ! $user_id ) {
            ?>
            <div class="wrap">
                <div class="user-profile-wraper">
                    <h2><?php echo esc_html__( 'Soemthing went wrong.', 'advance-user-registration' ); ?></h2>
                </div>
            </div>
            <?php
            return false;
        }

        $user_data       = get_userdata($user_id);
        $user_meta_datas = get_user_meta( $user_id, 'avur_user_meta_data', true ) ? get_user_meta( $user_id, 'avur_user_meta_data', true ) : [];
        $all_roles       = $wp_roles->get_names();
       
        ?>
        <div class="wrap">
           <div class="user-profile-wraper">
                <h2>Edit User Profile</h2>
                <form id="avur-user-profile-form" method="post" action="">
                    <input type="hidden" name="avur-user-id" value="<?php echo intval( $user_id ); ?>">
                    <div class="form-field">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo esc_attr( $user_data->user_login ); ?>" readonly>
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo esc_attr( $user_data->user_email  ); ?>">
                    </div>
                    <div class="form-field">
                        <label for="avur-password"><?php echo esc_html__( 'New Password', 'advance-user-registration' ); ?></label>
                        <input type="password" id="avur-password" name="avur-password">
                    </div>
                    <div class="form-field">
                        <label for="avur-user-role"><?php echo esc_html__( 'User Role', 'advance-user-registration' ); ?></label>
                        <select name="avur-user-role" id="avur-user-role">
                            <option value="">--Select--</option>
                            <?php 
                            foreach( $all_roles as $all_role => $r_name ) {
                                $selected = in_array( $all_role, $user_data->roles ) ? 'selected' : '';
                                printf( '<option value="%s" %s>%s</option>', $all_role, $selected, $r_name );
                            }
                            ?>
                        </select>
                    </div>
                    <?php 
                        foreach ( $user_meta_datas as $key => $user_meta_data ) :
                            $converted_string = ucwords(str_replace("_", " ", $key));
                        ?>
                        <div class="form-field">
                            <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html__( $converted_string, 'advance-user-registration' ); ?></label>
                            <input type="text" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $user_meta_data ); ?>">
                        </div>
                    <?php endforeach; ?>
                    <div class="form-field">
                        <input type="submit" id="avur-profile-update-btn" name="submit" class="button button-primary" value="Update Profile">
                    </div>
                </form>
           </div>
        </div>
        <?php
    }
}