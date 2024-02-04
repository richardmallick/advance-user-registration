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
        $avur_option_data = get_option( 'avur_user_registration_fields', true );
       ?>
       <div class="wrap">
           <div class="avur-admin-form-wrap">
                <h1><?php echo esc_html__( 'Form Fields', 'advance-user-registration' ) ?></h1>
                <br>
               <form id="avur-admin-from" action="">
                    <div id="fields-container">
                        <?php 
                        foreach( $avur_option_data as $data ):
                        ?>
                        <div class="field-wrapper">
                            <select name="field-type">
                                <?php 
                                foreach( $types as $type ) {
                                    $selected = $data[0] === $type ? 'selected' : '';
                                    printf( '<option value="%s" %s>%s</option>', $type, $selected, ucfirst( $type )  );
                                }
                                ?>
                            </select>
                            <input type="text" name="label-name" placeholder="Label Name" value="<?php echo esc_html( $data[1] ); ?>">
                            <input type="text" name="field-name" placeholder="Field Name" value="<?php echo esc_html( $data[2] ); ?>">
                            <span class="avur-admin-from-remove">-</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
               </form>
               <button id="avur-add-new">Add New Field</button>
               <button id="avur-save-fields">Save</button>
           </div>
        </div>
       <?php
    }
}