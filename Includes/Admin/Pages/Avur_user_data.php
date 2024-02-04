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

        $approved_users = avur_fetch_data_from_user_table( $users_per_page, $offset );
        $pending_users  = avur_fetch_data_from_avur_user_table( $users_per_page, $offset );

        $column_headers = [
            'ID',
            'Username',
            'Email',
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
                    <?php foreach ($approved_users as $user) : ?>
                        <tr>
                            <td><?php echo intval( $user->ID ); ?></td>
                            <td><?php echo esc_html( $user->user_login ); ?></td>
                            <td><?php echo esc_html( $user->user_email ); ?></td>
                            <td><?php echo implode(', ', $user->roles); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=advance-users&user_id=' . intval( $user->ID ) . '&table=users'); ?>"><button class="approved-avur-user-edit"><?php echo esc_html__('Edit', 'advance-user-registration') ?></button></a>
                                <button dataid="<?php echo intval( $user->ID ); ?>"><?php echo esc_html__('Active', 'advance-user-registration') ?></button>
                                <button class="approved-avur-user-delete" dataid="<?php echo intval( $user->ID ); ?>"><?php echo esc_html__('Delete', 'advance-user-registration') ?></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php
                        foreach ( $pending_users as $user ) :
                            $user_id    = $user['id'] ? intval( $user['id'] ) : '';
                            $user_name  = $user['user_login'] ? $user['user_login'] : '';
                            $user_email = $user['user_email'] ? $user['user_email'] : '';
                     ?>
                        <tr>
                            <td><?php echo intval( $user_id ); ?></td>
                            <td><?php echo esc_html( $user_name ); ?></td>
                            <td><?php echo esc_html( $user_email ); ?></td>
                            <td><?php echo ''; ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=advance-users&user_id=' . intval( $user_id ) . '&table=avur_users'); ?>"><button class="avur-user-edit"><?php echo esc_html__('Edit', 'advance-user-registration') ?></button></a>
                                <button class="avur-user-approve" dataid="<?php echo intval( $user_id ); ?>"><?php echo esc_html__('Approve', 'advance-user-registration') ?></button>
                                <button class="avur-user-deny" dataid="<?php echo intval( $user_id ); ?>"><?php echo esc_html__('Deny', 'advance-user-registration') ?></button>
                                <button class="avur-user-delete" dataid="<?php echo intval( $user_id ); ?>"><?php echo esc_html__('Delete', 'advance-user-registration') ?></button>
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

        $user_id         = isset( $_GET['user_id'] ) ? sanitize_text_field( $_GET['user_id'] ) : '';
        $table           = isset( $_GET['table'] ) ? sanitize_text_field( $_GET['table'] ) : '';
        if ( 'users' === $table ) {
            $user_data       = get_userdata($user_id);
            $user_meta_datas = get_user_meta( $user_id, 'avur_user_meta_data', true );
            $all_roles       = $wp_roles->get_names();
        } else {
            $user_data       = avur_fetch_data_from_user_table_by_id( $user_id );
            $user_meta_datas = get_avur_user_meta( $user_id, 'avur_user_meta_data', true );
            $all_roles       = [];
        }
       
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