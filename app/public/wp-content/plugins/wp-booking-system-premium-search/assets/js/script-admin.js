jQuery(function ($) {
    $(document).ready(function () {
        /**
         * Make the Selected Calendars available for selection if "Display Calendars" value is set
         * to "Selected Calendars"
         *
         */
        $(document).on('change', '#modal-add-search-widget-shortcode-calendars', function () {

            if ($(this).val() == '2')
                $('#modal-add-search-widget-shortcode-selected-calendars').parent().removeClass('wpbs-element-disabled');
            else
                $('#modal-add-search-widget-shortcode-selected-calendars').parent().addClass('wpbs-element-disabled');

        });

        $(document).on('change', '.wpbs-widget-display-calendars-select select', function () {

            if ($(this).val() == '2')
                $(this).parents('.widget-content').find('.wpbs-chosen-wrap').removeClass('wpbs-element-disabled');
            else
                $(this).parents('.widget-content').find('.wpbs-chosen-wrap').addClass('wpbs-element-disabled');

        });

        jQuery(document).on('widget-updated widget-added', function () {
            if (typeof $.fn.chosen != 'undefined') {
                $(".chosen-container").remove();
                $('.wpbs-chosen').chosen();
            }
        });


        /**
         * Builds the shortcode for the Search Widget and inserts it in the WordPress text editor
         *
         */
        $(document).on('click', '#wpbs-insert-shortcode-search-widget', function (e) {

            e.preventDefault();

            // Begin shortcode
            var shortcode = '[wpbs-search ';

            // Add the calendars shortcode attribute
            var calendars = $('#modal-add-search-widget-shortcode-calendars').val();

            if (calendars == '1')
                shortcode += 'calendars="all" ';

            // For selected calendars we want to maintain the order selected by the user, so grabbing
            // the value from the multiple select won't be enough, as this does not maintain the order
            else {

                var $select = $('#modal-add-search-widget-shortcode-selected-calendars');
                var select_val = '';

                $select.siblings('.chosen-container').find('li.search-choice a').each(function () {

                    select_val += $select.find('option').eq($(this).data('option-array-index')).val() + ','

                });

                // Trim the last comma
                select_val = select_val.slice(0, -1);

                shortcode += 'calendars="' + select_val + '" ';

            }

            // Add the rest of the attributes
            $('#wpbs-modal-add-calendar-shortcode.wpbs-active .wpbs-shortcode-generator-field-search-widget').each(function () {

                shortcode += $(this).data('attribute') + '="' + $(this).val() + '" ';

            });

            // End shortcode
            shortcode = shortcode.trim();
            shortcode += ']';

            window.send_to_editor(shortcode);

            $(this).closest('.wpbs-modal').find('.wpbs-modal-close').first().trigger('click');

        });
    });
});