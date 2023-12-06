jQuery(function ($) {
    /**
     * Toggle button logo type
     * 
     */
    $("#contract_logo_type").change(function () {
        $(".wpbs-contract-settings-logo-type").hide();
        $(".wpbs-contract-settings-logo-type-" + $(this).val()).show();
    }).trigger('change');

    /**
     * AJAX function to update contract details in booking modal
     * 
     */
    $(document).on('click', "#wpbs-update-contract-details", function (e) {
        e.preventDefault();

        $button = $(this);

        $button.text($button.data('label-wait'))

        // Prepare the data
        var data = {
            action: 'wpbs_update_contract_details',
            wpbs_token: wpbs_localized_data_contract.update_contract_details,
            booking_id: $button.data('booking-id'),
            contract_content: tinyMCE.get('contract_content').getContent({format: 'html'})
        }

        // Send the request
        $.post(ajaxurl, data, function (response) {
            $button.text($button.data('label-done'))
            setTimeout(function () {
                $button.text($button.data('label'));
            }, 1000)
        });
    })

    $(document).on('click', "#wpbs-regenerate-contract-details", function (e) {
        e.preventDefault();

        if (!confirm("Are you sure you want to regenerate this contract's content? This will overwrite the content with the original content from the Form Settings."))
            return false;

        $button = $(this);

        $button.text($button.data('label-wait'))

        // Prepare the data
        var data = {
            action: 'wpbs_regenerate_contract_details',
            wpbs_token: wpbs_localized_data_contract.update_contract_details,
            booking_id: $button.data('booking-id'),
            contract_content: tinyMCE.get('contract_content').getContent({format: 'html'})
        }

        // Send the request
        $.post(ajaxurl, data, function (response) {
            $button.text($button.data('label-done'))
            setTimeout(function () {
                $button.text($button.data('label'));
            }, 1000)

            tinyMCE.get('contract_content').setContent(response, {format : 'raw'})
        });
    })

});