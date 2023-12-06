/**
 * This is the object that stores all calendar data when editing a calendar
 *
 * Its structure is by year, month, day basis. Eg: wpbs_calendar_data[2018][1][21]
 *
 */
var wpbs_calendar_data = {};
var wpbs_unsaved_changes = false;
var wpbs_form_submitting = false;

jQuery(function ($) {

	/**
	 * Retrieves an array with the query arguments found in a given string
	 *
	 */
    function get_query_args(string) {

        var query_arr = string.replace('?', '').split('&');
        var query_params = [];

        for (var q = 0, q_query_arr = query_arr.length; q < q_query_arr; q++) {

            var q_arr = query_arr[q].split('=');
            query_params[q_arr[0]] = q_arr[1];

        }

        return query_params;

    }

	/**
	 * Resizes the calendar to always have square dates
	 *
	 */
    function resize_calendar($calendars_wrapper) {

        var td_width = $calendars_wrapper.find('td').first().width();
        $calendars_wrapper.find('td .wpbs-date-inner, td .wpbs-week-number').css('height', td_width + 'px');
        $calendars_wrapper.find('td .wpbs-date-inner, td .wpbs-week-number').css('line-height', td_width + 'px');

        $calendars_wrapper.css('visibility', 'visible');

    }


	/**
	 * Refreshed the output of the calendar with the given data
	 *
	 */
    function refresh_calendar($calendar_container, current_year, current_month) {

        var $calendar_container = $calendar_container;
        var $calendar_editor = $('.wpbs-calendar-editor');

        if ($calendar_container.hasClass('wpbs-is-loading'))
            return false;

		/**
		 * Prepare the calendar data
		 *
		 */
        var data = $calendar_container.data();

        data['action'] = 'wpbs_refresh_calendar_editor';
        data['current_year'] = current_year;
        data['current_month'] = current_month;
        data['calendar_data'] = JSON.stringify(wpbs_calendar_data);

		/**
		 * Add loading animation
		 *
		 */
        $calendar_container.find('.wpbs-calendar').append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
        $calendar_container.addClass('wpbs-is-loading');
        $calendar_container.find('select').attr('disabled', true);

        $('.wpbs-calendar-editor').append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');

        if(typeof wpbs_bulk_edit_availability_start_date !== 'undefined'){
			wpbs_bulk_editor_default_date = new Date();
			wpbs_bulk_editor_default_date.setMonth(current_month - 1);
			wpbs_bulk_editor_default_date.setYear(current_year);
			wpbs_bulk_edit_availability_start_date.datepicker('option', 'defaultDate', wpbs_bulk_editor_default_date)
		}

		/**
		 * Make the request
		 *
		 */
        $.post(ajaxurl, data, function (response) {

            response = JSON.parse(response);

            $calendar_container.replaceWith(response.calendar);
            $calendar_editor.replaceWith(response.calendar_editor);

            resize_calendar($('.wpbs-container[data-id="' + data['id'] + '"]'));
            refresh_calendar_dates();
            wpbs_calendar_editor_dynamic_layout();

        });

    }


	/**
	 * Updates the calendar legend items icons of each date from the 
	 * data found in wpbs_calendar_data for the legend items
	 *
	 */
    function refresh_calendar_dates() {

        var $calendar_container = $('.wpbs-container');

        var year = $calendar_container.data('current_year');
        var month = $calendar_container.data('current_month');

        if (typeof wpbs_calendar_data[year] == 'undefined')
            return false;

        if (typeof wpbs_calendar_data[year][month] == 'undefined')
            return false;

        for (day in wpbs_calendar_data[year][month]) {

            if (typeof wpbs_calendar_data[year][month][day] == 'undefined')
                continue;

            if (typeof wpbs_calendar_data[year][month][day]['legend_item_id'] == 'undefined')
                continue;

            var $legend_item_selector = $('.wpbs-calendar-date-legend-item select[data-year="' + year + '"][data-month="' + month + '"][data-day="' + day + '"]');

            $calendar_container.find('[data-year="' + year + '"][data-month="' + month + '"][data-day="' + day + '"] .wpbs-legend-item-icon')
                .attr('class', 'wpbs-legend-item-icon wpbs-legend-item-icon-' + $legend_item_selector.val())
                .attr('data-type', $legend_item_selector.find('option:selected').data('type'));

            $calendar_container.find('[data-year="' + year + '"][data-month="' + month + '"][data-day="' + day + '"] .wpbs-legend-item-icon')


            $calendar_container.find('[data-year="' + year + '"][data-month="' + month + '"][data-day="' + day + '"] .wpbs-legend-item-icon div:nth-child(1)').html('<svg preserveAspectRatio="none" viewBox="0 0 200 200"><polygon points="0,0 0,200 200,0" /></svg>');
            $calendar_container.find('[data-year="' + year + '"][data-month="' + month + '"][data-day="' + day + '"] .wpbs-legend-item-icon div:nth-child(2)').html('<svg preserveAspectRatio="none" viewBox="0 0 200 200"><polygon points="0,200 200,200 200,0" /></svg>');
        

        }

    }


	/**
	 * Callback function that is triggered on changes made to input, textarea, select, etc.
	 * fields from the calendar editor
	 *
	 */
    function calendar_editor_field_change($input) {

		/**
		 * Exit if the input does not have the needed data values
		 *
		 */
        if (typeof $input.data('year') == 'undefined')
            return false;

        if (typeof $input.data('month') == 'undefined')
            return false;

        if (typeof $input.data('day') == 'undefined')
            return false;

        if (typeof $input.data('name') == 'undefined')
            return false;

		/**
		 * Sanitize the data values and set them as variables
		 *
		 */

        var year = parseInt($input.data('year'));
        var month = parseInt($input.data('month'));
        var day = parseInt($input.data('day'));
        var name = $input.data('name');

        // Update data
        update_calendar_data(year, month, day, name, $input.val());

        wpbs_unsaved_changes = true;

    }


	/**
	 * Updates the calendar data object with new data from the provided field
	 *
	 */
    function update_calendar_data(year, month, day, field_name, field_value) {

        if (typeof field_name == 'undefined')
            return false;

        if (typeof field_value == 'undefined')
            return false;

		/**
		 * Create the object for each date layer if needed
		 *
		 */
        if (typeof wpbs_calendar_data[year] == 'undefined')
            wpbs_calendar_data[year] = {};

        if (typeof wpbs_calendar_data[year][month] == 'undefined')
            wpbs_calendar_data[year][month] = {};

        if (typeof wpbs_calendar_data[year][month][day] == 'undefined')
            wpbs_calendar_data[year][month][day] = {};

		/**
		 * Set the value for the current date
		 *
		 */
        wpbs_calendar_data[year][month][day][field_name] = field_value;

    }


	/**
	 * Resize the calendars on page load
	 *
	 */
    $('.wpbs-container').each(function () {
        resize_calendar($(this));
    });

	/**
	 * Resize the calendars on page resize
	 *
	 */
    $(window).on('resize orientatiochange', function () {
        $('.wpbs-container').each(function () {
            var $calendar_container = $(this);
            setTimeout(function () {
                resize_calendar($calendar_container);
            }, 50);
        });
    });


	/**
	 * Handles the navigation of the Previous button
	 *
	 */
    $('.wpbs-wrap-edit-calendar').on('click', '.wpbs-container .wpbs-prev', function (e) {

        e.preventDefault();

        // Set container
        var $container = $(this).closest('.wpbs-container');

        // Set the current year and month that are displayed in the calendar
        var current_month = $container.data('current_month');
        var current_year = $container.data('current_year');

        // Calculate the 
        var navigate_count = 1;

        // Take into account jump months option
        if (typeof $container.data('jump_months') != 'undefined' && $container.data('jump_months') == '1')
            navigate_count = parseInt($container.data('months_to_show'));

        for (var i = 1; i <= navigate_count; i++) {

            current_month -= 1;

            if (current_month < 1) {
                current_month = 12;
                current_year -= 1;
            }

        }

        refresh_calendar($container, current_year, current_month);

    });

	/**
	 * Handles the navigation of the Next button
	 *
	 */
    $('.wpbs-wrap-edit-calendar').on('click', '.wpbs-container .wpbs-next', function (e) {

        e.preventDefault();

        // Set container
        var $container = $(this).closest('.wpbs-container');

        // Set the current year and month that are displayed in the calendar
        var current_month = $container.data('current_month');
        var current_year = $container.data('current_year');

        // Calculate the 
        var navigate_count = 1;

        // Take into account jump months option
        if (typeof $container.data('jump_months') != 'undefined' && $container.data('jump_months') == '1')
            navigate_count = parseInt($container.data('months_to_show'));

        for (var i = 1; i <= navigate_count; i++) {

            current_month += 1;

            if (current_month > 12) {
                current_month = 1;
                current_year += 1;
            }

        }

        refresh_calendar($container, current_year, current_month);

    });

	/**
	 * Handles the navigation of the Month Selector
	 *
	 */
    $('.wpbs-wrap-edit-calendar').on('change', '.wpbs-container .wpbs-select-container select', function () {

        // Set container
        var $container = $(this).closest('.wpbs-container');

        var date = new Date($(this).val() * 1000);

        var year = date.getFullYear();
        var month = date.getMonth() + 1;

        refresh_calendar($container, year, month);

    });


	/**
	 * Updates the calendar wpbs_calendar_data object when doing changes in the
	 * calendar editor
	 *
	 */
    $(document).on('change', '.wpbs-calendar-editor select', function () {

        calendar_editor_field_change($(this));

    });

    $(document).on('keyup change', '.wpbs-calendar-editor input, .wpbs-calendar-editor textarea', function () {

        calendar_editor_field_change($(this));

    });


	/**
	 * Handle legend item select change in edit caledndar screen
	 *
	 */
    $(document).on('change', '.wpbs-calendar-date-legend-item select', function () {

        $(this).siblings('div').find('.wpbs-legend-item-icon')
            .attr('class', 'wpbs-legend-item-icon wpbs-legend-item-icon-' + $(this).val())
            .attr('data-type', $(this).find('option:selected').data('type'));
        
        $(this).siblings('div').find('.wpbs-legend-item-icon div:nth-child(1)').html('<svg height="100%" width="100%" viewBox="0 0 200 200" preserveAspectRatio="none"><polygon points="0,0 0,200 200,0" /></svg>');
        $(this).siblings('div').find('.wpbs-legend-item-icon div:nth-child(2)').html('<svg height="100%" width="100%" viewBox="0 0 200 200" preserveAspectRatio="none"><polygon points="0,200 200,200 200,0" /></svg>');
        

        $(this).closest('.wpbs-calendar-date-legend-item')
            .attr('class', 'wpbs-calendar-date-legend-item wpbs-calendar-date-legend-item-' + $(this).val());

        refresh_calendar_dates();

    });

	/**
	 * Handles the saving of the calendar by making an AJAX call to the server
	 * with the wpbs_calendar_data.
	 *
	 * Upon success refreshes the page and adds a success message
	 *
	 */
    $(document).on('click', '.wpbs-save-calendar', function (e) {

        e.preventDefault();

        wpbs_form_submitting = true;

        var form_data = $(this).closest('form').serialize();

        var data = {
            action: 'wpbs_save_calendar_data',
            form_data: form_data,
            calendar_data: JSON.stringify(wpbs_calendar_data),
            current_year: $('.wpbs-container').data('current_year'),
            current_month: $('.wpbs-container').data('current_month')
        }

        // Disable all buttons and show loading spinner
        $('.wpbs-wrap-edit-calendar input, .wpbs-wrap-edit-calendar select, .wpbs-wrap-edit-calendar textarea').attr('disabled', true);
        $(this).siblings('.wpbs-save-calendar-spinner').css('visibility', 'visible');

        $.post(ajaxurl, data, function (response) {

            if (typeof response != 'undefined')
                window.location.replace(response);

        });

    });


	/**
	 * Initialize datepickers for Bulk Availability and CSV Export
	 *
	 */

    wpbs_datepicker_week_start = (wpbs_plugin_settings.backend_start_day) ? wpbs_plugin_settings.backend_start_day : 1;

    // Bulk Edit Availability
    var wpbs_bulk_edit_availability_start_date = $('#wpbs-bulk-edit-availability-start-date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        firstDay: wpbs_datepicker_week_start,
        beforeShow: function () {
            $('#ui-datepicker-div').addClass('wpbs-datepicker');
        },
        onClose: function () {
            $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
            setTimeout(function(){
                wpbs_bulk_edit_availability_end_date.datepicker('show')
            }, 10)
        }
    }).on('change', function () {
        wpbs_bulk_edit_availability_end_date.datepicker("option", "minDate", wpbs_datepicker_get_date(this));
    }),

        wpbs_bulk_edit_availability_end_date = $('#wpbs-bulk-edit-availability-end-date').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            firstDay: wpbs_datepicker_week_start,
            beforeShow: function () {
                $('#ui-datepicker-div').addClass('wpbs-datepicker');
            },
            onClose: function () {
                $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
            }
        })

    // CSV Export
    var wpbs_export_csv_start_date = $('#wpbs-export-csv-start-date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        firstDay: wpbs_datepicker_week_start,
        beforeShow: function () {
            $('#ui-datepicker-div').addClass('wpbs-datepicker');
        },
        onClose: function () {
            $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
            setTimeout(function(){
                wpbs_export_csv_end_date.datepicker('show');
            }, 10)
        }
    }).on('change', function () {
        wpbs_export_csv_end_date.datepicker("option", "minDate", wpbs_datepicker_get_date(this));
    }),
        wpbs_export_csv_end_date = $('#wpbs-export-csv-end-date').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            firstDay: wpbs_datepicker_week_start,
            beforeShow: function () {
                $('#ui-datepicker-div').addClass('wpbs-datepicker');
            },
            onClose: function () {
                $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
            }
        })



	/**
	 * Handle bulk edit availability
	 *
	 */
    var wpbs_calendar_data_cache = '';

    $(document).on('click', '#wpbs-bulk-edit-availability', function (e) {

        e.preventDefault();

        wpbs_unsaved_changes = true;

        var start_date = new Date($('#wpbs-bulk-edit-availability-start-date').val());
        var end_date = new Date($('#wpbs-bulk-edit-availability-end-date').val());
        var legend_item_id = $('#wpbs-bulk-edit-availability-legend-item').val();
        var price = $('#wpbs-bulk-edit-availability-price').val();
        var inventory = $('#wpbs-bulk-edit-availability-inventory').val();
        var description = $('#wpbs-bulk-edit-availability-description').val();
        var tooltip = $('#wpbs-bulk-edit-availability-tooltip').val();
        var overwrite = $('#wpbs-bulk-edit-availability-overwrite:checked').length;

        var week_days = [];
        $.each($('.wpbs-bulk-edit-availability-week-day:checked'), function () {
            week_days.push(parseInt($(this).val()));
        });

        if (isNaN(start_date.getTime()) || isNaN(end_date.getTime()))
            return false;

        var start_year = start_date.getUTCFullYear();
        var start_month = start_date.getUTCMonth() + 1;
        var start_day = start_date.getUTCDate();

        var end_year = end_date.getUTCFullYear();
        var end_month = end_date.getUTCMonth() + 1;
        var end_day = end_date.getUTCDate();

        // Cache the calendar data before manipulating it
        wpbs_calendar_data_cache = JSON.stringify(wpbs_calendar_data);

        for (var year = start_year; year <= end_year; year++) {

            for (var month = (year == start_year ? start_month : 1); month <= (year == end_year ? end_month : 12); month++) {

                // Get the last day of the month
                var last_month_day = new Date(year, month, 0);

                for (var day = (year == start_year && month == start_month ? start_day : 1); day <= (year == end_year && month == end_month ? end_day : last_month_day.getDate()); day++) {

                    var day_of_the_week = new Date(year, month - 1, day).getDay();

                    if (week_days.length > 0 && $.inArray(day_of_the_week, week_days) === -1) {
                        continue;
                    }

                    // Update the calendar data
                    if (legend_item_id || !overwrite) {
                        update_calendar_data(year, month, day, 'legend_item_id', legend_item_id);
                    }

                    if (description || !overwrite) {
                        update_calendar_data(year, month, day, 'description', description);
                    }

                    if (price || !overwrite) {
                        update_calendar_data(year, month, day, 'price', price);
                    }

                    if (inventory || !overwrite) {
                        update_calendar_data(year, month, day, 'inventory', inventory);
                    }

                    if (tooltip || !overwrite) {
                        update_calendar_data(year, month, day, 'tooltip', tooltip);
                    }

                }

            }

        }

        // Refresh the calendar to add the data
        refresh_calendar($('.wpbs-container'), $('.wpbs-container').data('current_year'), $('.wpbs-container').data('current_month'));

        // Make the Undo button available
        $('#wpbs-bulk-edit-availability-undo').removeClass('wpbs-inactive');

    });

	/**
	 * Undo the latest bulk availability changes
	 *
	 */
    $(document).on('click', '#wpbs-bulk-edit-availability-undo', function (e) {

        e.preventDefault();

        $(this).blur();

        if ($(this).hasClass('wpbs-inactive'))
            return false;

        if (!confirm("Are you sure you want to undo the last bulk action?"))
            return false;

        // Set the calendar data to the cached one
        wpbs_calendar_data = JSON.parse(wpbs_calendar_data_cache);

        // Refresh the calendar to add the data
        refresh_calendar($('.wpbs-container'), $('.wpbs-container').data('current_year'), $('.wpbs-container').data('current_month'));

        // Make the Undo button unavailable
        $('#wpbs-bulk-edit-availability-undo').addClass('wpbs-inactive');

    });

    $(document).on('click', '#wpbs-bulk-edit-availability-booking', function (e) {
        e.preventDefault();

        $modal = $(this).parents('#wpbs-booking-details-modal-inner');

        $modal.find('.wpbs-calendar-editor select').val($("#wpbs-bulk-edit-availability-booking-legend-item").val()).trigger('change');
        $modal.find('.wpbs-calendar-editor .wpbs-calendar-date-description input').val($("#wpbs-bulk-edit-availability-booking-description").val()).trigger('keyup');
        $modal.find('.wpbs-calendar-editor .wpbs-calendar-date-tooltip input').val($("#wpbs-bulk-edit-availability-booking-tooltip").val()).trigger('keyup');

        if($("#wpbs-bulk-edit-availability-booking-inventory").length){
            $modal.find('.wpbs-calendar-editor .wpbs-calendar-date-inventory input').val($("#wpbs-bulk-edit-availability-booking-inventory").val()).trigger('keyup');
        }

    });


});

/**
 * Datepicker helper function
 * 
 */
function wpbs_datepicker_get_date(element) {
    var date;
    try {
        date = $.datepicker.parseDate('yy-mm-dd', element.value);
    } catch (error) {
        date = null;
    }
    return date;
}

window.onload = function () {
    window.addEventListener("beforeunload", function (e) {
        if (wpbs_form_submitting || !wpbs_unsaved_changes) {
            return undefined;
        }

        var confirmationMessage = 'It looks like you have been editing something. '
            + 'If you leave before saving, your changes will be lost.';

        (e || window.event).returnValue = confirmationMessage; //Gecko + IE
        return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
    });
};