jQuery(function ($) {

    var wpbs_s_display_date_format = wpbs_s_localized_data.date_format;
    var wpbs_s_date_format = 'yy-m-d';


    /**
     * The datepickers
     * 
     */

    wpbs_s_search_widget_initialize_datepickers();

    $('.wpbs_s-search-widget').each(function () {
        var $instance = $(this);

        $instance.data('pagination_ppp', (Math.abs($instance.data('results_per_page')) > 0 ? Math.abs($instance.data('results_per_page')) : 10));
        $instance.data('pagination_total', $instance.find(".wpbs_s-search-widget-result").length);
        $instance.data('pagination_pages', Math.ceil($instance.data('pagination_total') / $instance.data('pagination_ppp')));
        $instance.data('pagination_current_page', 0);

        wpbs_s_search_widget_pagination($instance);
    });

    // Add padding to form
    wpbs_s_search_widget_add_padding();
    wpbs_s_search_widget_size();

    $(window).bind('resize', function () {
        // Add padding to form
        wpbs_s_search_widget_add_padding();
        wpbs_s_search_widget_size();
    });


    function wpbs_s_search_widget_add_padding() {
        $(".wpbs_s-search-widget .wpbs_s-search-widget-form").css('padding-right', $(".wpbs_s-search-widget .wpbs_s-search-widget-form .wpbs_s-search-widget-field.wpbs_s-search-widget-field-submit").width());
    };

    function wpbs_s_search_widget_size() {
        $(".wpbs_s-search-widget").each(function () {
            $widget = $(this);
            if ($widget.width() < 500) {
                $widget.addClass('wpbs_s-search-widget-small');
            } else {
                $widget.removeClass('wpbs_s-search-widget-small');
            };
        });
    };

    $(document).on('click', ".wpbs_s-search-widget-datepicker-submit", function (e) {
        e.preventDefault();

        var $button = $(this);
        var $container = $button.parents('.wpbs_s-search-widget');
        var $form = $container.find('.wpbs_s-search-widget-form');

        if ($container.data('redirect')) {
            $form.submit();
            return false;
        }


        $form.addClass('wpbs_s-searching');
        $button.prop('disabled', true);

        $container.find(".wpbs_s-search-widget-results-wrap").empty();
        $container.find(".wpbs_s-search-widget-error-field").empty();

        var data = {
            action: 'wpbs_s_search_calendars',
            start_date: $form.find('.wpbs_s-search-widget-datepicker-standard-format-start-date').val(),
            end_date: $form.find('.wpbs_s-search-widget-datepicker-standard-format-end-date').val(),
            form_data: $form.serialize(),
            args: $container.data(),
            wpbs_s_token: wpbs_s_localized_data.search_form_nonce
        }

        $.post(wpbs_s_localized_data.ajax_url, data, function (response) {
            $form.removeClass('wpbs_s-searching');
            $button.prop('disabled', false);

            $container.html($(response).html())
            wpbs_s_search_widget_initialize_datepickers();

            // Pagination
            $container.data('pagination_total', $container.find(".wpbs_s-search-widget-result").length);
            $container.data('pagination_pages', (Math.ceil($container.data('pagination_total') / $container.data('pagination_ppp'))))
            $container.data('pagination_current_page', 0);

            wpbs_s_search_widget_pagination($container);

            // Add padding to form
            wpbs_s_search_widget_add_padding();
            wpbs_s_search_widget_size();
            $container.animate({ opacity: 1 }, 200);

            // Resize the results on page resize
            $('.wpbs_s-search-widget').each(function () {
                resize_calendar($(this));
            });
        });

    });

    $(document).on('keyup change', ".wpbs_s-search-widget-additional-field input, .wpbs_s-search-widget-additional-field select", function (e) {
        wpbs_datepicker_update_state($(this).attr('name'), $(this).val())
    })


    function wpbs_s_search_widget_initialize_datepickers() {

        if ($('body').hasClass('wp-admin')) return false;

        $('.wpbs_s-search-widget').each(function () {

            var $instance = $(this);

            var wpbs_s_start_day = $instance.data('start_day') ? $instance.data('start_day') : 1;

            var start_date = $instance.find(".wpbs_s-search-widget-datepicker-start-date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: wpbs_s_display_date_format,
                firstDay: wpbs_s_start_day,
                minDate: wpbs_s_localized_data.start_date_min_date,
                altFormat: wpbs_s_date_format,
                altField: $instance.find('.wpbs_s-search-widget-datepicker-standard-format-start-date'),
                showOtherMonths: true,
                selectOtherMonths: true,
                beforeShow: function () {
                    $('#ui-datepicker-div').addClass('wpbs-datepicker');
                },
                onClose: function () {
                    $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                    setTimeout(function () {
                        $instance.find(".wpbs_s-search-widget-datepicker-end-date").datepicker('show');
                    }, 10);
                },
            }).on("change", function () {
                end_date.datepicker("option", "minDate", wpbs_s_datepicker_get_date(this, $instance.data('minimum_stay')));

                if (!$instance.data('redirect')) {
                    wpbs_datepicker_update_state('wpbs-search-start-date', $instance.find(".wpbs_s-search-widget-datepicker-standard-format-start-date").val())
                }
            })

            var end_date = $instance.find(".wpbs_s-search-widget-datepicker-end-date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: wpbs_s_display_date_format,
                firstDay: wpbs_s_start_day,
                minDate: wpbs_s_datepicker_get_date($instance.find(".wpbs_s-search-widget-datepicker-start-date")[0], $instance.data('minimum_stay')),
                altFormat: wpbs_s_date_format,
                altField: $instance.find('.wpbs_s-search-widget-datepicker-standard-format-end-date'),
                showOtherMonths: true,
                selectOtherMonths: true,
                beforeShow: function () {
                    $('#ui-datepicker-div').addClass('wpbs-datepicker');
                },
                onClose: function () {
                    $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                },
            }).on("change", function () {
                if (!$instance.data('redirect')) {
                    wpbs_datepicker_update_state('wpbs-search-end-date', $instance.find(".wpbs_s-search-widget-datepicker-standard-format-end-date").val())
                }
            });

            $(document).trigger('wpbs_search_init', [$instance]);


        });
    };

    /**
     * Search Widget pagination
     * 
     */
    function wpbs_s_search_widget_pagination($instance) {

        $instance.find(".wpbs_s-search-widget-result").hide();

        var wpbs_s_pagination_current_page = $instance.data('pagination_current_page');
        var wpbs_s_pagination_ppp = $instance.data('pagination_ppp');
        var wpbs_s_pagination_total = $instance.data('pagination_total');
        var wpbs_s_pagination_pages = $instance.data('pagination_pages');

        $instance.find(".wpbs_s-search-widget-result").slice(wpbs_s_pagination_current_page * wpbs_s_pagination_ppp, (wpbs_s_pagination_current_page + 1) * wpbs_s_pagination_ppp).show();

        if (wpbs_s_pagination_total > wpbs_s_pagination_ppp) {
            $instance.find(".wpbs_s-search-pagination").remove();
            $instance.find(".wpbs_s-search-widget-results-wrap").append('<div class="wpbs_s-search-pagination"><ul></ul></div>');

            $instance.find('.wpbs_s-search-pagination ul').append('<li><a ' + (wpbs_s_pagination_current_page == 0 ? 'class="wpbs_s-pagination-disabled"' : '') + ' href="#" data-page="previous">' + $instance.find('.wpbs_s-search-widget-results-wrap').data('label-previous') + '</a></li>');

            for (i = 0; i < wpbs_s_pagination_pages; i++) {
                $instance.find('.wpbs_s-search-pagination ul').append('<li><a ' + (wpbs_s_pagination_current_page == i ? 'class="wpbs_s-pagination-active"' : '') + ' href="#" data-page="' + i + '">' + (i + 1) + '</a></li>');
            }

            $instance.find('.wpbs_s-search-pagination ul').append('<li><a ' + (wpbs_s_pagination_current_page == (wpbs_s_pagination_pages - 1) ? 'class="wpbs_s-pagination-disabled"' : '') + ' href="#" data-page="next">' + $instance.find('.wpbs_s-search-widget-results-wrap').data('label-next') + '</a></li>');
        }
    }

    $("body").on('click', '.wpbs_s-search-pagination li a', function (e) {
        e.preventDefault();

        var $instance = $(this).parents('.wpbs_s-search-widget');

        if ($(this).hasClass('wpbs_s-pagination-disabled')) {
            return false;
        }

        var wpbs_s_pagination_pages = $instance.data('pagination_pages');
        var wpbs_s_pagination_current_page = $instance.data('pagination_current_page');

        var page = $(this).data('page');

        if (page == 'next' && wpbs_s_pagination_current_page != (wpbs_s_pagination_pages - 1)) {
            page = wpbs_s_pagination_current_page + 1;
        }
        if (page == 'previous' && wpbs_s_pagination_current_page != 0) {
            page = wpbs_s_pagination_current_page - 1;
        }

        $instance.data('pagination_current_page', page);

        wpbs_s_search_widget_pagination($instance);
    });

    /**
     * Helper function to get the date of a datepicker element
     * 
     * 
     * 
     */
    function wpbs_s_datepicker_get_date(element, offset) {
        var date;
        try {
            date = $.datepicker.parseDate(wpbs_s_display_date_format, element.value);
        } catch (error) {
            date = null;
        }

        if (offset !== false && date !== null) {
            date.setDate(date.getDate() + offset);
        }

        return date;
    };

    /**
     * Helper function to change the url history state
     * 
     */
    function wpbs_datepicker_update_state(key, value) {
        value = encodeURIComponent(value);
        var baseUrl = [location.protocol, '//', location.host, location.pathname].join(''),
            urlQueryString = document.location.search,
            newParam = key + '=' + value,
            params = '?' + newParam;

        // If the "search" string exists, then build params from it
        if (urlQueryString) {
            keyRegex = new RegExp('([\?&])' + key + '[^&]*');

            // If param exists already, update it
            if (urlQueryString.match(keyRegex) !== null) {
                params = urlQueryString.replace(keyRegex, "$1" + newParam);
            } else { // Otherwise, add it to end of query string
                params = urlQueryString + '&' + newParam;
            }
        }
        window.history.replaceState({}, "", baseUrl + params);
    };



    /**
     * Resizes the results
     *
     */
    function resize_calendar($results_wrapper) {

        /**
         * Set variables
         *
         */
        var $results_wrapper_width = $results_wrapper.find('.wpbs_s-search-widget-results');
        var results_min_width = 250;
        var results_max_width = 500;


        /**
         * Set the results min and max width from the data attributes
         *
         */
        $results_wrapper.find('.wpbs_s-search-widget-result').css('min-width', results_min_width);

        $results_wrapper.find('.wpbs_s-search-widget-result').css('max-width', results_max_width)


        /**
         * Set the column count
         *
         */
        var column_count = 0;

        if ($results_wrapper_width.width() < (10 + results_min_width) * 2)
            column_count = 1;

        else if ($results_wrapper_width.width() < (10 + results_min_width) * 3)
            column_count = 2;

        else if ($results_wrapper_width.width() < (10 + results_min_width) * 4)
            column_count = 3;

        else if ($results_wrapper_width.width() < (10 + results_min_width) * 6)
            column_count = 4;
        else
            column_count = 6;


        // Adjust for when there are fewer months in a calendar than columns
        if ($results_wrapper.find('.wpbs_s-search-widget-result').length <= column_count)
            column_count = $results_wrapper.find('.wpbs_s-search-widget-result').length;

        // Set column count
        $results_wrapper.attr('data-columns', column_count);
    }


    /**
     * Resize the results on page load
     *
     */
    $('.wpbs_s-search-widget').each(function () {
        resize_calendar($(this));
    });

    /**
     * Resize the results on page resize
     *
     */
    $(window).on('resize', function () {
        $('.wpbs_s-search-widget').each(function () {
            resize_calendar($(this));
        });
    });

    /**
     * Elementor trigger resize after popup opens, if by any chance the calendar is in a popup.
     * 
     */
    $(document).on('elementor/popup/show', () => {
        $(".wpbs_s-search-widget-field input").removeClass('hasDatepicker');
        wpbs_s_search_widget_initialize_datepickers();
        $(window).trigger('resize');
    });

});