

jQuery(function ($) {

    var wpbs_bookings_page = 1;
    var wpbs_bookings_posts_per_page = wpbs_localized_data_booking.bookings_per_page;

    function wpbs_bookings_use_ajax() {
        return $("#wpbs-bookings").hasClass('wpbs-bookings-ajax');
    }

    function wpbs_bookings_ajax_reload() {

        $("#wpbs-bookings").addClass('wpbs-loading')

        data = {
            action: 'wpbs_bookings_outputter_ajax',
            data: $("#wpbs-bookings").data(),
        }

        // Make the request
        $.post(ajaxurl, data, function (response) {
            $("#wpbs-bookings").replaceWith(response);
            wpbs_bookings_dynamic_layout();

            if ($("#wpbs-bookings").data('tab') == 'trash') {
                $("#wpbs-empty-trash").css('display', 'inline-block')
                $("#wpbs-add-booking-open-modal").hide();
            } else {
                $("#wpbs-empty-trash").hide();
                $("#wpbs-add-booking-open-modal").show();
            }

            if ($("#wpbs-bookings-search").val()) {
                input_value = $("#wpbs-bookings-search").val();
                $("#wpbs-bookings-search").focus();
                $("#wpbs-bookings-search").val('').val(input_value);
            }

            if (!$(".wpbs-booking-field").length) {
                if ($("#wpbs-bookings-search").val()) {
                    $(".wpbs-bookings-no-search-results strong").text($("#wpbs-bookings-search").val())
                    $(".wpbs-bookings-no-search-results").show();
                } else {
                    $(".wpbs-bookings-no-results strong").text($(".wpbs-bookings-tab-navigation li a.current .tab-label").text())
                    $(".wpbs-bookings-no-results").show();
                }
            }


        });
    }

    /**
     * Sorting
     * 
     */
    function wpbs_bookings_sort() {

        if (wpbs_bookings_use_ajax()) {
            return false;
        }
        // Get select values
        var sort_by = $("#wpbs-bookings-order-by").val() ? $("#wpbs-bookings-order-by").val() : 'id';
        var sort_order = $("#wpbs-bookings-order").val() ? $("#wpbs-bookings-order").val() : 'desc';

        // Save fields in a variable
        var bookings = $(".wpbs-bookings-tab.active .wpbs-booking-field");

        // Do the actual sorting
        bookings.sort(function (a, b) {
            if (sort_order == 'asc') {
                return wpbs_bookings_sort_asc(a, b, sort_by);
            }
            return wpbs_bookings_sort_desc(a, b, sort_by);
        });

        // Put back the fields in the correct order
        $(".wpbs-bookings-tab.active").html(bookings);

        // Reset pagination
        wpbs_bookings_reset_pagination();

    }
    // ASC sorting function
    function wpbs_bookings_sort_asc(a, b, sort_by) {
        return $(a).data(sort_by) - $(b).data(sort_by)
    }

    // DESC sorting function
    function wpbs_bookings_sort_desc(a, b, sort_by) {
        return $(b).data(sort_by) - $(a).data(sort_by)
    }

    /**
     * Tab Count
     * 
     */
    function wpbs_bookings_tab_count() {
        if (wpbs_bookings_use_ajax()) {
            return false;
        }
        $(".wpbs-bookings-tab-navigation li").each(function () {
            $li = $(this);
            $li.find('.count').text('(' + $("#" + $li.find('a').data('tab')).find('.wpbs-booking-field:not(.hidden):not(.wpbs-hide-past-booking)').length + ')');
        })
    }

    /**
     * Pagination Function
     * 
     */

    function wpbs_bookings_pagination() {

        if (wpbs_bookings_use_ajax()) {
            return;
        }

        if (!$(".wpbs-bookings-pagination").length) {
            return;
        }

        // Show the pagination after script has loaded
        $(".wpbs-bookings-pagination").show();

        // Get total number of items and total number of pages
        total = $(".wpbs-bookings-tab.active .wpbs-booking-field:not(.hidden):not(.wpbs-hide-past-booking)").length
        pages = Math.ceil(total / wpbs_bookings_posts_per_page);

        // If there aren't enough results, hide the pagination
        if (pages <= 1) {
            $(".wpbs-bookings-pagination").hide();
        }

        // Change pagination interface numbers
        $("#wpbs-bookings-postbox .displaying-num").text(total + ' bookings');
        $("#wpbs-bookings-postbox .current-page").text(wpbs_bookings_page);
        $("#wpbs-bookings-postbox .total-pages").text(pages);

        // Hide all fields
        $(".wpbs-bookings-tab.active .wpbs-booking-field").hide();

        // And display the ones on the current page
        for (var i = (wpbs_bookings_page - 1) * wpbs_bookings_posts_per_page; i < wpbs_bookings_posts_per_page * wpbs_bookings_page; i++) {
            $(".wpbs-bookings-tab.active .wpbs-booking-field:not(.hidden):not(.wpbs-hide-past-booking)").eq(i).show();
        }

        // Disable or enable buttons depending on the page we're on
        if (wpbs_bookings_page == 1) {
            $(".prev-page, .first-page").addClass('disabled');
        } else {
            $(".prev-page, .first-page").removeClass('disabled');
        }

        if (wpbs_bookings_page == pages) {
            $(".next-page, .last-page").addClass('disabled');
        } else {
            $(".next-page, .last-page").removeClass('disabled');
        }

        // Resize layout
        wpbs_bookings_dynamic_layout();
    }

    /**
     * Reset Pagination
     * 
     */

    function wpbs_bookings_reset_pagination() {
        wpbs_bookings_page = 1;
        $("#wpbs-bookings-search").val('');
        $(".wpbs-booking-field").removeClass('hidden').show();
        wpbs_bookings_pagination();
    }

    /**
     * Custom jQuery :selector
     * 
     */
    jQuery.expr[':'].wpbs_icontains = function (a, i, m) {
        return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };

    /**
     * Set the widths for the booking desctiption columns
     * 
     */
    function wpbs_bookings_dynamic_layout() {

        // Set same width for check in dates
        var max_check_in_date = 0;
        $(".wpbs-booking-field-check-in-date").width('auto').each(function () {
            if ($(this).outerWidth(true) > max_check_in_date) {
                max_check_in_date = $(this).outerWidth(true);
            }
        });
        $(".wpbs-booking-field-check-in-date").width(max_check_in_date);

        //Set same width for check out dates
        var max_check_in_date = 0;
        $(".wpbs-booking-field-check-out-date").width('auto').each(function () {
            if ($(this).outerWidth(true) > max_check_in_date) {
                max_check_in_date = $(this).outerWidth(true);
            }
        });
        $(".wpbs-booking-field-check-out-date").width(max_check_in_date);

        for (var i = 1; i < 20; i++) {
            var span_width = 0;
            $("p.wpbs-booking-field-details > span:nth-child(" + i + ")").width('auto').each(function () {
                if ($(this).outerWidth(true) > span_width) {
                    span_width = $(this).outerWidth(true);
                }
            });
            $("p.wpbs-booking-field-details > span:nth-child(" + i + ")").width(span_width);
        }
    }

    // Set Sizes
    wpbs_bookings_dynamic_layout();
    $(window).resize(wpbs_bookings_dynamic_layout);

    // Tab counts
    wpbs_bookings_tab_count();

    // Initialize Pagination
    wpbs_bookings_pagination();

    /**
     * Sorting boxes handlers
     * 
     */

    $("#wpbs-bookings-postbox").on('change', '#wpbs-bookings-order-by', function () {

        // Prepare data
        data = {
            action: 'wpbs_booking_remember_orderby_option',
            wpbs_token: wpbs_localized_data_booking.remember_orderby_option,
            value: $(this).val()
        }

        // Make the request
        $.post(ajaxurl, data);

        if (wpbs_bookings_use_ajax()) {
            $("#wpbs-bookings").data('orderby', $(this).val());
            wpbs_bookings_ajax_reload();
        } else {
            wpbs_bookings_sort();
        }

    });

    $("#wpbs-bookings-postbox").on('change', '#wpbs-bookings-order', function () {

        // Prepare data
        data = {
            action: 'wpbs_booking_remember_order_option',
            wpbs_token: wpbs_localized_data_booking.remember_order_option,
            value: $(this).val()
        }

        // Make the request
        $.post(ajaxurl, data);

        if (wpbs_bookings_use_ajax()) {
            $("#wpbs-bookings").data('order', $(this).val());
            wpbs_bookings_ajax_reload();
        } else {
            wpbs_bookings_sort();
        }
    });

    /**
     * Tabs Click Handler
     */
    $("#wpbs-bookings-postbox").on('click', '.wpbs-bookings-tab-navigation a', function (e) {


        e.preventDefault();
        $a = $(this);

        $(".wpbs-bookings-tab-navigation a").removeClass('current');
        $a.addClass('current');

        if (wpbs_bookings_use_ajax()) {
            $("#wpbs-bookings").data('tab', $a.data('tab-name'));
            wpbs_bookings_ajax_reload();

        } else {

            $(".wpbs-bookings-tab").removeClass('active').hide();

            $("p.wpbs-bookings-no-results").hide();
            $("p.wpbs-bookings-no-search-results").hide();

            $tab = $("#" + $a.data('tab'));
            $tab.addClass('active').show();

            if (!$tab.find('.wpbs-booking-field').length) {
                $('.wpbs-bookings-no-results strong').text($(".wpbs-bookings-tab-navigation li a.current .tab-label").text())
                $('.wpbs-bookings-no-results').show();
            }

            if ($a.data('tab') == 'wpbs-bookings-tab-trash') {
                $("#wpbs-add-booking-open-modal").hide();
                if ($('a[data-tab="' + $a.data('tab') + '"]').find('.count').text() != '(0)') {
                    $("#wpbs-empty-trash").css('display', 'inline-block')
                }
            } else {
                $("#wpbs-empty-trash").hide();
                $("#wpbs-add-booking-open-modal").show();
            }

            wpbs_bookings_sort();

            wpbs_bookings_reset_pagination();

            wpbs_bookings_dynamic_layout();
        }
    });

    /**
     * Open first tab Handler
     * 
     * Checks to see if there are new bookings and shows them. If not, show the Accepted bookings tab.
     * 
     */

    if (!$("#wpbs-bookings-tab-pending > .wpbs-booking-field").length) {
        $('.wpbs-bookings-tab-navigation a[data-tab="wpbs-bookings-tab-accepted"]').trigger('click');
    }


    /**
     * Search
     */
    $("#wpbs-bookings-postbox").on('keydown', '#wpbs-bookings-search', function (e) {
        if (e.keyCode == 13) {
            return false;
        }
    });

    var wpbs_search_timeout;
    var wpbs_search_actions = wpbs_bookings_use_ajax() ? 'keyup search' : 'keyup change search';
    $("#wpbs-bookings-postbox").on(wpbs_search_actions, '#wpbs-bookings-search', function (e) {
        var val = $(this).val();
        clearTimeout(wpbs_search_timeout);
        if (wpbs_bookings_use_ajax()) {

            wpbs_search_timeout = setTimeout(function () {
                $("#wpbs-bookings").data('search', val);
                wpbs_bookings_ajax_reload();
            }, 750);
        } else {

            // Split query to match each word
            var words = val.split(' ');

            // Hide all fields
            $('.wpbs-bookings-tab.active .wpbs-booking-field').hide().addClass('hidden');

            // Hide no-results message
            $("p.wpbs-bookings-no-search-results").hide();

            // Loop through fields
            $('.wpbs-bookings-tab.active .wpbs-booking-field').each(function () {
                var found = 0;
                var $field = $(this);

                //Loop though words
                words.forEach(function (word) {
                    if (
                        $field.find('.wpbs-booking-field-details span span:wpbs_icontains("' + word + '")').length ||
                        $field.find('.wpbs-booking-field-booking-id:wpbs_icontains("' + word + '")').length
                    ) {
                        found++;
                    }
                })
                if (found == words.length) {
                    // Show only matched fields
                    $field.show().removeClass('hidden');
                }
            })

            // Reset search
            wpbs_bookings_page = 1;
            wpbs_bookings_pagination();

            // Check if there are no results
            if (!$('.wpbs-bookings-tab.active .wpbs-booking-field:visible').length) {
                $("p.wpbs-bookings-no-search-results strong").text(val);
                $("p.wpbs-bookings-no-search-results").show();
            }

            wpbs_bookings_dynamic_layout();

        }


    })

    /**
     * Pagination Navigation
     * 
     */

    // First Page
    $(".wpbs-bookings-pagination").on('click', '.first-page.button:not(.disabled)', function (e) {
        e.preventDefault();
        wpbs_bookings_page = 1;
        wpbs_bookings_pagination();
    })

    // Previous Page
    $(".wpbs-bookings-pagination").on('click', '.prev-page.button:not(.disabled)', function (e) {
        e.preventDefault();
        wpbs_bookings_page--;
        wpbs_bookings_pagination();
    })

    // Next Page
    $(".wpbs-bookings-pagination").on('click', '.next-page.button:not(.disabled)', function (e) {
        e.preventDefault();
        wpbs_bookings_page++;
        wpbs_bookings_pagination();
    });

    // Last Page
    $(".wpbs-bookings-pagination").on('click', '.last-page.button:not(.disabled)', function (e) {
        e.preventDefault();
        wpbs_bookings_page = Math.ceil($(".wpbs-bookings-tab.active .wpbs-booking-field:not(.hidden):not(.wpbs-hide-past-booking)").length / wpbs_bookings_posts_per_page);
        wpbs_bookings_pagination();
    });

    $("#wpbs-bookings-postbox").on('click', '.wpbs-bookings-ajax .wpbs-bookings-pagination a', function (e) {
        e.preventDefault();
        $("#wpbs-bookings").data('page', $(this).data('page'));
        wpbs_bookings_ajax_reload();
    });

    /** Hide past bookings */
    $(document).on('change', '#hide-past-bookings', function (e) {


        // Prepare data
        data = {
            action: 'wpbs_booking_remember_hide_past_option',
            wpbs_token: wpbs_localized_data_booking.remember_hide_past_option,
            remember: $(this).prop('checked')
        }

        // Make the request
        $.post(ajaxurl, data);

        if (wpbs_bookings_use_ajax()) {
            $("#wpbs-bookings").data('hide-past-bookings', $(this).prop('checked') ? '1' : '');
            wpbs_bookings_ajax_reload();

        } else {

            if ($(this).prop('checked') === true) {
                $(".wpbs-is-past-booking").addClass('wpbs-hide-past-booking');
            } else {
                $(".wpbs-is-past-booking").removeClass('wpbs-hide-past-booking');
            }

            wpbs_bookings_sort();

            wpbs_bookings_tab_count();

            wpbs_bookings_reset_pagination();

            wpbs_bookings_dynamic_layout();
        }
    })

    /**
     * Booking Details
     * 
     */

    // Open Modal
    $(document).on('click', '.wpbs-open-booking-details', function (e) {
        e.preventDefault();
        $a = $(this);

        // Remove the "NEW" tag
        if ($a.hasClass('wpbs-booking-field-is-read-0')) {
            $a.removeClass('wpbs-booking-field-is-read-0').addClass('wpbs-booking-field-is-read-1')
            $a.find('.wpbs-booking-field-new-booking').remove();

            $(".wpbs-bookings-count-circle").each(function () {
                var count = parseInt($(this).text()) - 1;
                $(this).text(count);
                if (count == 0) {
                    $(".wpbs-admin-bar-bookings-count-wrap").text($(".wpbs-admin-bar-bookings-count").data('no-bookings-label'));
                    $(".wpbs-bookings-removable-count").each(function () {
                        if ($(this).text() == '0') {
                            $(this).remove();
                        }
                        if ($(this).find('.wpbs-bookings-count-circle').text() == '0') {
                            $(this).remove();
                        }
                    })
                }
            });

        }

        $("html").css('overflow', 'hidden');

        booking_id = parseInt($a.data('id'));

        wpbs_open_booking_modal(booking_id)

    });

    // Close Modal
    $(document).on('click', '#wpbs-booking-details-modal-close, #wpbs-booking-details-modal-overlay', function (e) {
        e.preventDefault();

        // Remove the overlay
        $("#wpbs-booking-details-modal-overlay").animate({ opacity: 0 }, 400, function () {
            $("html").css('overflow', 'visible');
            $("#wpbs-booking-details-modal-overlay").remove();
            wpbs_calendar_editor_dynamic_layout();
        });
    });


    // Add stop propagation to inner modal
    $(document).on('click', '#wpbs-booking-details-modal-inner', function (e) {
        e.stopPropagation();
    });


    // Bind escape key to close the modal
    $(document).keyup(function (e) {
        if (e.key === "Escape" && $("#wpbs-booking-details-modal-overlay").length) {
            $("#wpbs-booking-details-modal-overlay").animate({ opacity: 0 }, 400, function () {
                $("html").css('overflow', 'visible');
                $("#wpbs-booking-details-modal-overlay").remove();
                wpbs_calendar_editor_dynamic_layout();
            });
        };
    });

    $(document).on('change', '#wpbs-booking-details-modal-inner h3 .wpbs-notification-toggle', function () {
        $(this).parents('.wpbs-tab').find(".wpbs-booking-details-modal-email-wrapper").toggleClass('wpbs-booking-details-modal-email-wrapper-show');
    })


    /**
     * Handles the saving of the calendar by making an AJAX call to the server
     * with the wpbs_calendar_data.
     *
     * Upon success refreshes the page and adds a success message
     *
     */
    $(document).on('click', '.wpbs-action-update-booking', function (e) {

        e.preventDefault();

        wpbs_form_submitting = true;

        $button = $(this);

        if ($button.data('action') == 'delete' && !confirm("Are you sure you want to delete this booking?")) {
            return false;
        }

        // Trigger MCE Save so .serialize() will work
        if (typeof tinyMCE !== "undefined") {
            tinyMCE.triggerSave();
        }

        // Prepare data
        var form_data = $('.wpbs-wrap-edit-calendar form').serialize();
        var email_form_data = $('.wpbs-booking-details-modal-accept-booking-email form').serialize();
        var data = {
            action: 'wpbs_save_calendar_data',
            form_data: form_data,
            email_form_data: email_form_data,
            booking_action: $button.data('action'),
            calendar_data: JSON.stringify(wpbs_calendar_data),
            booking_id: $button.data('booking-id'),
            current_year: $('.wpbs-container').data('current_year'),
            current_month: $('.wpbs-container').data('current_month')
        }

        // Disable all buttons
        $('#wpbs-booking-details-modal-inner input, #wpbs-booking-details-modal-inner select, #wpbs-booking-details-modal-inner textarea, #wpbs-booking-details-modal-inner button').attr('disabled', true);

        // Send the request
        $.post(ajaxurl, data, function (response) {
            if (typeof response != 'undefined')
                window.location.replace(response);
        });

    });


    /**
     * Permanently Delete Booking Confirmation
     */
    $(document).on('click', '.wpbs-permanently-delete-booking', function (e) {

        if (!confirm("Are you sure you want to permanently delete this booking?")) {
            return false;
        }

    });

    /**
     * Email Customer Form
     * 
     */
    $(document).on('click', '.wpbs-booking-details-modal-email-customer-inner #wpbs-email-customer', function (e) {

        e.preventDefault();

        $button = $(this);

        // Trigger MCE Save so .serialize() will work
        if (typeof tinyMCE !== "undefined") {
            tinyMCE.triggerSave();
        }

        // Prepare Data
        var form_data = $('.wpbs-booking-details-modal-email-customer-inner form').serialize();
        var data = {
            action: 'wpbs_booking_email_customer',
            wpbs_token: wpbs_localized_data_booking.email_customer_token,
            form_data: form_data,
            id: $button.data('booking-id')
        }

        // Disable all buttons
        $('#wpbs-booking-details-modal-inner input, #wpbs-booking-details-modal-inner select, #wpbs-booking-details-modal-inner textarea, #wpbs-booking-details-modal-inner button').attr('disabled', true);

        // Send the request
        $.post(ajaxurl, data, function (response) {
            $('.wpbs-booking-details-modal-email-customer-inner form').html(response);
            $('#wpbs-booking-details-modal-inner input, #wpbs-booking-details-modal-inner select, #wpbs-booking-details-modal-inner textarea, #wpbs-booking-details-modal-inner button').attr('disabled', false);
        });

    });

    /**
     * Highlight all Booking IDs when hovering
     * 
     */
    $(document).on('mouseenter', '.wpbs-calendar-date .wpbs-calendar-date-booking-id', function () {
        id = $(this).data('id');
        $('.wpbs-calendar-date-booking-id[data-id="' + id + '"]').addClass('hover');
    });

    $(document).on('mouseleave', '.wpbs-calendar-date .wpbs-calendar-date-booking-id', function () {
        $('.wpbs-calendar-date-booking-id').removeClass('hover');
    });

    /**
     * Email Templates
     * 
     */
    $(document).on('change', '.wpbs-load-tinymce-content', function () {
        tinymce_id = $(this).data('tinymce');
        tinymce_content = $(this).find('option:selected').data('text');
        tinymce.get(tinymce_id).setContent(tinymce_content);
        $("#" + $(this).data('subject')).val($(this).find('option:selected').text())
    });



    /**
     * Mark Booking Part Payment as Paid/Unpaid
     * 
     */
    $(document).on('click', '.wpbs-part-payment-change-status', function (e) {

        e.preventDefault();

        $button = $(this);

        $(".wpbs-order-information-part-payment-actions > *").css('opacity', 0.5);

        // Prepare the data
        var data = {
            action: 'wpbs_booking_part_payment_change_status',
            wpbs_token: wpbs_localized_data_booking.change_payment_status,
            id: $button.parents('.wpbs-order-information-part-payment-actions').data('booking-id'),
            payment_type: $button.parents('.wpbs-order-information-part-payment-actions').data('booking-payment'),

        }

        // Send the request
        $.post(ajaxurl, data, function (response) {
            wpbs_booking_part_payment_update_status();
        });

    });

    /**
     * Update the HTML for part payment statuses
     * 
     */
    function wpbs_booking_part_payment_update_status() {
        $(".wpbs-order-information-part-payment-actions").each(function () {
            var $wrap = $(this);

            // Prepare the data
            var data = {
                action: 'wpbs_booking_part_payment_update_status',
                wpbs_token: wpbs_localized_data_booking.change_payment_status,
                id: $wrap.data('booking-id'),
                payment_type: $wrap.data('booking-payment'),

            }

            // Send the request
            $.post(ajaxurl, data, function (response) {
                $wrap.html(response);
            });

        })
    }

    /**
     * Mark Bank Transfer as Paid/Unpaid
     * 
     */
    $(document).on('click', '.wpbs-payment-change-status', function (e) {

        e.preventDefault();

        $button = $(this);

        $(".wpbs-order-information-payment-actions > *").css('opacity', 0.5);

        // Prepare the data
        var data = {
            action: 'wpbs_booking_change_status',
            wpbs_token: wpbs_localized_data_booking.change_payment_status,
            id: $button.parents('.wpbs-order-information-payment-actions').data('booking-id')
        }

        // Send the request
        $.post(ajaxurl, data, function (response) {
            wpbs_booking_update_status();
        });

    });

    /**
     * Update the HTML for part payment statuses
     * 
     */
    function wpbs_booking_update_status() {
        $(".wpbs-order-information-payment-actions").each(function () {
            var $wrap = $(this);

            // Prepare the data
            var data = {
                action: 'wpbs_booking_update_status',
                wpbs_token: wpbs_localized_data_booking.change_payment_status,
                id: $wrap.data('booking-id')
            }

            // Send the request
            $.post(ajaxurl, data, function (response) {
                $wrap.html(response);
            });

        })
    }

    /**
     * Add Booking Note
     * 
     */
    $(document).on('click', '#wpbs_modal_add_booking_note', function (e) {
        e.preventDefault();
        var $button = $(this);
        var $note = $(this).siblings('#wpbs_modal_booking_note')

        $button.prop('disabled', true);
        $note.prop('disabled', true);

        // Remove the "no notes" message
        $(".wpbs-booking-details-modal-note-no-results").remove();

        // Prepare the data
        var data = {
            action: 'wpbs_booking_add_note',
            wpbs_token: wpbs_localized_data_booking.booking_notes,
            booking_id: $button.data('booking-id'),
            note: $note.val(),
        }

        // Send the request
        $.post(ajaxurl, data, function (response) {
            $button.prop('disabled', false);
            $note.prop('disabled', false).val('');

            if (response != '0') {
                $(".wpbs-booking-details-modal-notes-wrap").append(response);
            }
        });

    });

    /**
     * Delete Booking Note
     * 
     */
    $(document).on('click', '.wpbs-booking-details-modal-note-remove', function (e) {
        e.preventDefault();

        if (!confirm("Are you sure you want to delete this note?"))
            return false;

        var $button = $(this);
        var $wrap = $button.parents('.wpbs-booking-details-modal-note');

        $wrap.css('opacity', 0.4);

        // Prepare the data
        var data = {
            action: 'wpbs_booking_delete_note',
            wpbs_token: wpbs_localized_data_booking.booking_notes,
            booking_id: $button.data('booking-id'),
            note_id: $button.data('booking-note'),
        }

        // Send the request
        $.post(ajaxurl, data, function (response) {
            $wrap.remove();
        });

    });

    /**
     * Edit Booking
     * 
     */

    $(document).on('click', '.wpbs-edit-booking-details .edit-booking-details-save', function (e) {
        e.preventDefault();

        $button = $(this);
        $form = $button.parents('form');

        type = $form.data('type');

        // Prepare data
        data = {
            action: 'wpbs_edit_booking_details',
            form_data: $form.serialize(),
            token: $("#wpbs_edit_" + type + "_token").val(),
            type: type
        }

        $button.prop('disabled', true);
        $form.find('.edit-booking-details-cancel').prop('disabled', true);
        $form.find('input').prop('disabled', true);
        $form.find('textarea').prop('disabled', true);
        $form.find('table').css('opacity', '0.7')

        // Make the request
        $.post(ajaxurl, data, function (response) {
            if (type == 'booking_details') {
                $form.find('.wpbs-edit-booking-details-field-editable').each(function () {
                    value = $(this).find('.wpbs-edit-booking-details-field-edit textarea').length ? $(this).find('.wpbs-edit-booking-details-field-edit textarea').val() : $(this).find('.wpbs-edit-booking-details-field-edit input').val()

                    if (value)
                        $(this).find('.wpbs-edit-booking-details-field-view p').html(wpbs_nl2br(value))
                })
            }
            if (type == 'booking_data') {
                dates = JSON.parse(response);

                $form.find('.wpbs-edit-booking-field-start_date p').text(dates['start_date']);
                $form.find('.wpbs-edit-booking-field-end_date p').text(dates['end_date']);

                $.each(['reminder_email_date', 'follow_up_email_date', 'payment_email_date', 'reminder_sms_date', 'follow_up_sms_date', 'payment_sms_date'], function (i, field) {
                    if (typeof dates[field] !== 'undefined') {
                        $form.find('.wpbs-edit-booking-field-' + field + ' p strong').text(dates[field]);
                    }
                })

            }

            if (type == 'payment_details') {
                fields = JSON.parse(response);

                $form = $('.wpbs-edit-booking-details[data-type="payment_details"]');

                for (var key in fields) {
                    if (fields.hasOwnProperty(key)) {
                        $form.find('.wpbs-payment-details-field-' + key + ' .wpbs-edit-booking-details-field-view .wpbs-price').text(fields[key])
                    }
                }

            }
            wpbs_edit_booking_details_close();

        });
    });

    $(document).on('click', '.wpbs-edit-booking-details .edit-booking-details-open', function (e) {

        e.preventDefault();

        $button = $(this);
        $form = $(this).parents('form');

        wpbs_edit_booking_details_init_datepicker();

        $form.find('.edit-booking-details-cancel').prop('disabled', false);
        $form.find('.edit-booking-details-save').prop('disabled', false);
        $form.find('input').prop('disabled', false);
        $form.find('textarea').prop('disabled', false);
        $form.find('.wpbs-page-notice').show();


        $button.hide();
        $form.find('.wpbs-edit-booking-details-field-editable .wpbs-edit-booking-details-field-edit').show();
        $form.find('.wpbs-edit-booking-details-field-editable .wpbs-edit-booking-details-field-view').hide();
        $form.find('.edit-booking-details-save').show();
        $form.find('.edit-booking-details-cancel').show();

    });

    $(document).on('click', '.wpbs-edit-booking-details .edit-booking-details-cancel', function (e) {
        e.preventDefault();

        wpbs_edit_booking_details_close();
    });

    function wpbs_edit_booking_details_close() {
        $form = $('.wpbs-edit-booking-details');

        $form.find('table').css('opacity', '1')
        $form.find('.edit-booking-details-open').show();
        $form.find('.wpbs-edit-booking-details-field-editable .wpbs-edit-booking-details-field-edit').hide();
        $form.find('.wpbs-edit-booking-details-field-editable .wpbs-edit-booking-details-field-view').show();
        $form.find('.edit-booking-details-save').hide();
        $form.find('.wpbs-page-notice').hide();
        $form.find('.edit-booking-details-cancel').hide();
    }

    function wpbs_edit_booking_details_init_datepicker() {

        wpbs_datepicker_week_start = (wpbs_plugin_settings.backend_start_day) ? wpbs_plugin_settings.backend_start_day : 1;

        $('.wpbs-edit-booking-datepicker').datepicker({
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
    }

    wpbs_bookings_sort();

    /**
     * Refunds
     * 
     */
    $(document).on('click', '.wpbs-booking-details-refund-form-open', function (e) {
        e.preventDefault();
        $(this).hide();
        $(".wpbs-booking-details-refund-form").show();
    });

    $(document).on('click', '#wpbs-refund-cancel', function (e) {
        e.preventDefault();
        $(".wpbs-booking-details-refund-form-open").show();
        $(".wpbs-booking-details-refund-form").hide();
    });

    $(document).on('click', '.wpbs-refund-process', function (e) {
        e.preventDefault();

        if (!confirm("Are you sure you want proceed with the refund?")) {
            return false;
        }

        $button = $(this);
        var $parent = $(this).parents('.wpbs-booking-details-refund-charge')

        // Prepare the data
        var data = {
            action: 'wpbs_process_refund',
            amount: $parent.find(".wpbs-refund-amount").val(),
            reason: $parent.find(".wpbs-refund-reason").val(),
            charge_id: $button.data('charge-id'),
            payment_gateway: $button.data('payment-gateway'),
            booking_id: $button.data('booking-id')
        }

        var $row = $button.parents('td');
        $row.css('opacity', 0.4);

        // Send the request
        $.post(ajaxurl, data, function (response) {

            response = JSON.parse(response);

            if (response.error) {
                $row.css('opacity', 1);
                $parent.find(".wpbs-booking-details-refund-error").html(response.error_message);
            } else {

                $table = $button.parents('table');
                $table.find('.wpbs-booking-details-refund-row').remove();
                $table.append(response.html)
            }

        });

    })

    /**
     * Security Deposits
     * 
     */

    $(document).on('click', '.wpbs-security-deposit-toggle-refunded-status', function (e) {
        e.preventDefault();
        var $button = $(this);

        // Prepare the data
        var data = {
            action: 'wpbs_security_deposit_toggle_refunded_status',
            payment_id: $button.data('payment-id'),
        }

        $('#wpbs-booking-details-modal-refunds').css('opacity', 0.4);

        // Send the request
        $.post(ajaxurl, data, function (response) {
            $('#wpbs-booking-details-modal-refunds').html(response);
            $('#wpbs-booking-details-modal-refunds').css('opacity', 1);
        });
    });

    $(document).on('click', '.wpbs-security-deposit-cancel-automatic-refund', function (e) {
        e.preventDefault();
        var $button = $(this);

        if (!confirm("Are you sure you want to cancel the automatic refund? You can still manually issue the refund.")) {
            return false;
        }

        // Prepare the data
        var data = {
            action: 'wpbs_security_deposit_cancel_automatic_refund',
            payment_id: $button.data('payment-id'),
        }

        $('#wpbs-booking-details-modal-refunds').css('opacity', 0.4);

        // Send the request
        $.post(ajaxurl, data, function (response) {
            $('#wpbs-booking-details-modal-refunds').html(response);
            $('#wpbs-booking-details-modal-refunds').css('opacity', 1);
        });
    });

    $(document).on('click', '.wpbs-security-deposit-manual-refund', function (e) {
        e.preventDefault();
        var $button = $(this);

        if (!confirm("Are you sure you want to refund the security deposit?")) {
            return false;
        }

        // Prepare the data
        var data = {
            action: 'wpbs_security_deposit_manual_refund',
            payment_id: $button.data('payment-id'),
        }

        $('#wpbs-booking-details-modal-refunds').css('opacity', 0.4);

        // Send the request
        $.post(ajaxurl, data, function (response) {
            response = JSON.parse(response)
            $('#wpbs-booking-details-modal-refunds').html(response['security_deposit']);
            $('#wpbs-booking-details-modal-refunds').css('opacity', 1);

            $table = $('form.wpbs-edit-booking-details table');
            $table.find('.wpbs-booking-details-refund-row').remove();
            $table.append(response['refunds'])
        });
    });

    /**
     * Delete cron job
     * 
     */
    $(document).on('click', '.wpbs-remove-scheduled-action', function (e) {
        e.preventDefault();
        var $button = $(this);


        if (!confirm("Are you sure you want to delete this scheduled event?")) {
            return false;
        }

        // Prepare the data
        var data = {
            action: 'wpbs_remove_scheduled_event',
            cron: $button.data('cron'),
            booking_id: $button.data('id'),
        }

        var $row = $button.parents('tr');
        $row.css('opacity', 0.4);

        // Send the request
        $.post(ajaxurl, data, function (response) {
            $row.remove();
        });
    })

    /**
     * Add Bookings
     * 
     */

    // Close Modal
    $(document).on('click', '#wpbs-add-booking-modal-close, #wpbs-add-booking-modal-overlay', function (e) {
        e.preventDefault();

        // Remove the overlay
        $("#wpbs-add-booking-modal-overlay").animate({ opacity: 0 }, 400, function () {
            $("html").css('overflow', 'visible');
            $("#wpbs-add-booking-modal-overlay").hide().css('opacity', 0);
            $("#wpbs-add-booking-modal-inner").css('opacity', 0);
            wpbs_calendar_editor_dynamic_layout();
        });
    });


    // Add stop propagation to inner modal
    $(document).on('click', '#wpbs-add-booking-modal-inner', function (e) {
        e.stopPropagation();
    });


    // Bind escape key to close the modal
    $(document).keyup(function (e) {
        if (e.key === "Escape" && $("#wpbs-add-booking-modal-overlay").length) {
            $("#wpbs-add-booking-modal-overlay").animate({ opacity: 0 }, 400, function () {
                $("html").css('overflow', 'visible');
                $("#wpbs-add-booking-modal-overlay").hide().css('opacity', 0);
                $("#wpbs-add-booking-modal-inner").css('opacity', 0);
                wpbs_calendar_editor_dynamic_layout();
            });
        };
    });

    // Save calendar options
    $(document).on('click', '#wpbs-add-booking-options-save', function (e) {
        e.preventDefault();

        // Prepare data
        data = {
            action: 'wpbs_add_booking_save_calendar_options',
            options: $(".wpbs-add-booking-options-form").serialize(),
        }

        $("#wpbs-add-booking").html('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
        $("#wpbs-add-booking").addClass('wpbs-is-loading');

        $(".wpbs-add-booking-options").slideUp();
        $("#wpbs-add-booking-edit-options").removeClass('wpbs-hidden');

        // Make the request
        $.post(ajaxurl, data, function (response) {
            $("#wpbs-add-booking").html(response);
            $(window).trigger('resize')
        });
    })

    // Open the Options
    $(document).on('click', '#wpbs-add-booking-edit-options', function (e) {
        e.preventDefault();

        $(this).addClass('wpbs-hidden');
        $(".wpbs-add-booking-options").slideDown();
    });

    /**
     * Move Booking
     * 
     */

    // Open
    $(document).on('click', '.wpbs-move-booking', function (e) {
        e.preventDefault();

        $(".wpbs-booking-details-main").hide();
        $(".wpbs-booking-details-modal-move-booking").show();

    });

    // Close 
    $(document).on('click', '.wpbs-action-move-booking-cancel', function (e) {
        e.preventDefault();

        $(".wpbs-booking-details-main").show();
        $(".wpbs-booking-details-modal-move-booking").hide();

    });

    // Open the modal
    $(document).on('click', '#wpbs-add-booking-open-modal', function (e) {
        e.preventDefault();

        $("html").css('overflow', 'hidden');

        $("#wpbs-add-booking-modal-overlay").show();
        $("#wpbs-add-booking-modal-overlay").animate({ opacity: 1 });

        // Prepare data
        data = {
            action: 'wpbs_add_booking_save_calendar_options',
            options: $(".wpbs-add-booking-options-form").serialize(),
        }

        // Make the request
        $.post(ajaxurl, data, function (response) {
            $("#wpbs-add-booking").html(response);
            $(window).trigger('resize');
            $(document).trigger('wpbs_add_booking_modal_opened');
            $("#wpbs-add-booking-modal-inner").animate({ opacity: 1 });
        });
    });

    /**
     * Empty Trash
     * 
     */
    $(document).on('click', '#wpbs-empty-trash', function (e) {

        if (!confirm('Are you sure you want to permanently delete all the bookings in the "Deleted" tab? This action cannot be undone.')) {
            return false;
        }

    });

    /**
     * Remember "Include Booking Details" option
     * 
     */
    $(document).on('change', '#booking_email_customer_include_booking_details, #booking_email_accept_booking_include_booking_details', function () {

        // Prepare data
        data = {
            action: 'wpbs_booking_modal_remember_include_booking_details',
            value: $(this).prop('checked')
        }

        // Make the request
        $.post(ajaxurl, data);
    });

});

function wpbs_open_booking_modal(booking_id, active_tab = '') {
    // Prepare data
    data = {
        action: 'wpbs_open_booking_details',
        wpbs_token: wpbs_localized_data_booking.open_bookings_token,
        id: booking_id
    }

    // Add the overlay
    jQuery("body").append('<div id="wpbs-booking-details-modal-overlay" />');
    jQuery("#wpbs-booking-details-modal-overlay").animate({ opacity: 1 }, 400);

    // Make the request
    jQuery.post(ajaxurl, data, function (response) {

        jQuery("#wpbs-booking-details-modal-overlay").html(response);
        jQuery("#wpbs-booking-details-modal-inner").animate({ opacity: 1 }, 400);

        if (active_tab) {
            jQuery('.wpbs-nav-tab-wrapper a[data-tab="' + active_tab + '"]').trigger('click')
        }

        // Hacky-hack to make wp_editor work :(
        jQuery(".wpbs-wp-editor-ajax").each(function () {
            var tiny_mce_id = jQuery(this).data('id');

            if (typeof tinyMCE === "undefined") {
                return false;
            }

            tinyMCE.execCommand('mceRemoveEditor', true, tiny_mce_id);

            tinyMCE.init(tinyMCEPreInit.mceInit['wpbs_placeholder_editor']);
            tinyMCE.execCommand('mceAddEditor', true, tiny_mce_id);

            setTimeout(function () {
                quicktags({ id: tiny_mce_id });

                // Set email templates
                jQuery(".wpbs-load-tinymce-content").each(function () {
                    if (jQuery(this).data('auto-load') != 0) {
                        jQuery(this).find('option[value="' + jQuery('#booking_email_accept_load_template').data('auto-load') + '"]').prop('selected', true);
                        jQuery(this).trigger('change');
                    }
                })

            }, 1000)
        });
    });
}