;(function($){
    $(document).ready(function($) {
        // Filter data table
        $('#user-filter-input').on('input', function() {
            var filter = $(this).val().toLowerCase();
            $('#user-list-table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(filter) > -1);
            });
        });

        // Add fields
        $('#avur-add-new').click(function() {
            $('#fields-container').append(`
                <div class="field-wrapper">
                    <select name="field-type">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                    <input type="text" name="label-name" placeholder="Label Name">
                    <input type="text" name="field-name" placeholder="Field Name ( unique )">
                    <span class="avur-admin-from-remove">-</span>
                </div>
            `);
        });

        // Remove registration from field
        $(document).on('click', '.avur-admin-from-remove', function(){
            $(this).parent().remove();
        });

        // Save fields data
        $('#avur-save-fields').on('click', function(e){

            e.preventDefault();

            data = $('#avur-admin-from').serializeArray();

            $.ajax({
                type: 'POST',
                url: avurAdmin.ajaxUrl,
                data: {
                    action: "avur_user_fields",
                    _nonce: avurAdmin.avur_nonce,
                    data: data,
                },
                beforeSend: function () {
                    $('#avur-save-fields').text('Saving...');
                },
                success: function (response) {
                    if ( response.data.error ) {
                        alert(response.data.error);
                    }
                    if ( response.data.message ) {
                        $('#avur-save-fields').text('Saved');
                     }
                },
                error: function (data) {
                    $('.avur-error-message').show();
                    $('.avur-error-message').html( 'Something went wrong! Please try again later.' );
                }
            });
        });

        // Update Profile data.
        $('#avur-user-profile-form').on('submit', function(e){

            e.preventDefault();

            data = $(this).serializeArray();

            $.ajax({
                type: 'POST',
                url: avurAdmin.ajaxUrl,
                data: {
                    action: "avur_user_profile_data_update",
                    _nonce: avurAdmin.avur_nonce,
                    data: data,
                },
                beforeSend: function () {
                    $('#avur-profile-update-btn').val('Updating...');
                },
                success: function (response) {
                    if ( response.data.error ) {
                        alert(response.data.error);
                    }
                    if ( response.data.message ) {
                        alert(response.data.message);
                        $('#avur-profile-update-btn').val('Update Profile');
                     }
                },
                error: function (data) {
                    alert('Something went wrong! Please try again later.');
                }
            });
        });
        
        // Approve user
        $('.avur-user-approve').on('click', function(e){
            e.preventDefault();

            var user_id = $(this).attr('dataid'),
                This    = this;

            $.ajax({
                type: 'POST',
                url: avurAdmin.ajaxUrl,
                data: {
                    action: "avur_approve_user",
                    _nonce: avurAdmin.avur_nonce,
                    user_id: user_id,
                },
                beforeSend: function () {
                    $(This).val('Updating...');
                },
                success: function (response) {
                    if ( response.data.error ) {
                        alert(response.data.error);
                    }
                    if ( response.data.message ) {
                        alert(response.data.message);
                        location.reload();
                    }
                },
                error: function (data) {
                    alert('Something went wrong! Please try again later.');
                }
            });
        });

         // Delete user from table
        $('.avur-user-delete').on('click', function(e){
            e.preventDefault();

            var user_id = $(this).attr('dataid'),
                This    = this;

            $.ajax({
                type: 'POST',
                url: avurAdmin.ajaxUrl,
                data: {
                    action: "avur_delete_user",
                    _nonce: avurAdmin.avur_nonce,
                    user_id: user_id,
                },
                beforeSend: function () {
                    $(This).val('Deleting...');
                },
                success: function (response) {
                    if ( response.data.error ) {
                        alert(response.data.error);
                    }
                    if ( response.data.message ) {
                        $(This).closest('tr').css('background-color', 'red');
                        var $tr = $(This).closest('tr');
                        setTimeout(function() {
                            $tr.remove();
                        }, 500);
                    }
                },
                error: function (data) {
                    alert('Something went wrong! Please try again later.');
                }
            });
           
        });
         // Delete user from table
        $('#avur-option-data-form').on('submit', function(e){
            e.preventDefault();

            var data = $(this).serializeArray();

            $.ajax({
                type: 'POST',
                url: avurAdmin.ajaxUrl,
                data: {
                    action: "avur_save_settings_data",
                    _nonce: avurAdmin.avur_nonce,
                    data: data,
                },
                beforeSend: function () {
                    $('#avur-option-data').val('Updating...');
                },
                success: function (response) {
                    if ( response.data.error ) {
                        alert(response.data.error);
                    }
                    if ( response.data.message ) {
                        alert(response.data.message);
                        $('#avur-option-data').val('Update');
                    }
                },
                error: function (data) {
                    alert('Something went wrong! Please try again later.');
                }
            });
           
        });
    });
})(jQuery);