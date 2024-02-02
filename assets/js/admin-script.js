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
                    <input type="text" name="field-name" placeholder="Field Name">
                </div>
            `);
        });

        // Save fields data
        $('#avur-save-fields').click(function(e){

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
    });
})(jQuery);