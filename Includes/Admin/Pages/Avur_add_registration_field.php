<?php

namespace AV_USER_REGISTRATION\Includes\Admin\Pages;

/**
 * UserRegistrationFormCustomizer Class
 *
 * @since 1.0.0
 */
class Avur_add_registration_field {

    /**
     * Method custom_user_list_page.
     *
     * @since 1.0.0
     */
    public function add_registration_form_field() {
        $types = [
            'text',
            'number',
            'checkbox',
        ];
        $avur_option_data = get_option( 'avur_user_registration_fields', true ) ? get_option( 'avur_user_registration_fields', true ) : [1];
       ?>
       <div class="wrap">
           <div class="avur-admin-form-wrap">
                <h1><?php echo esc_html__( 'Form Fields', 'advance-user-registration' ) ?></h1>
                <br>
               <form id="avur-admin-from" action="">
                    <div id="fields-container">
                        <?php 
                        foreach( $avur_option_data as $data ):
                            $data_type  = isset( $data[0] ) && $data[0] ? $data[0] : '';
                            $label_name = isset( $data[1] ) && $data[1] ? $data[1] : '';
                            $field_name = isset( $data[2] ) && $data[2] ? $data[2] : '';
                        ?>
                        <div class="field-wrapper">
                            <select name="field-type">
                                <?php 
                                foreach( $types as $type ) {
                                    $selected = $data_type === $type ? 'selected' : '';
                                    printf( '<option value="%s" %s>%s</option>', $type, $selected, ucfirst( $type )  );
                                }
                                ?>
                            </select>
                            <input type="text" name="label-name" placeholder="Label Name" value="<?php echo esc_attr( $label_name ); ?>">
                            <input type="text" name="field-name" placeholder="Field Name ( unique )" value="<?php echo esc_attr( $field_name ); ?>">
                            <span class="avur-admin-from-remove">-</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
               </form>
               <button id="avur-add-new"><?php echo esc_html__( 'Add New Field', 'advance-user-registration' ) ?></button>
               <button id="avur-save-fields"><?php echo esc_html__( 'Save', 'advance-user-registration' ) ?></button>
           </div>
        </div>
       <?php
    }
}