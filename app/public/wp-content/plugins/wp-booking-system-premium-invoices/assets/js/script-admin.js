jQuery(function ($) {
    /**
     * Toggle button logo type
     * 
     */
    $("#invoice_logo_type").change(function () {
        $(".wpbs-invoice-settings-logo-type").hide();
        $(".wpbs-invoice-settings-logo-type-" + $(this).val()).show();
    }).trigger('change');

    /**
     * Toggle button invoice number type
     * 
     */
    $("#invoice_number_type").change(function () {
        $(".wpbs-invoice-settings-number-type").hide();
        $(".wpbs-invoice-settings-number-type-" + $(this).val()).show();
    }).trigger('change');

    /**
     * AJAX function to update invoice details in booking modal
     * 
     */
    $(document).on('click', "#wpbs-update-invoice-details", function(e){
        e.preventDefault();

        $button = $(this);

        $button.text($button.data('label-wait'))

        // Prepare the data
        var data = {
            action: 'wpbs_update_invoice_details',
            wpbs_token: wpbs_localized_data_invoice.update_invoice_details,
            booking_id: $button.data('booking-id'),
            buyer_details: $("#wpbs-invoice-details").val(),
        }

        // Send the request
        $.post(ajaxurl, data, function (response) {
            $button.text($button.data('label-done'))
            setTimeout(function(){
                $button.text($button.data('label'));
            }, 1000)
        });
    })
});