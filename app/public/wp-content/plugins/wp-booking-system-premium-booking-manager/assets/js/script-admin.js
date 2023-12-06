jQuery(function ($) {
    $(document).ready(function () {
        $(".wpbs-bm-calendar-row").each(function () {
            var $col = $(this);
            var levels = 0;

            // Prevent collision
            for (var i = 1; i <= 3; i++) {
                $col.find('.wpbs-bm-booking').each(function () {
                    var $booking1 = $(this);
                    $col.find('.wpbs-bm-booking').each(function () {
                        var $booking2 = $(this);
                        if ($booking1.data('id') != $booking2.data('id') && wpbs_collision($booking1, $booking2)) {
                            $booking2.css('top', function (index, curValue) {
                                return parseInt(curValue) + 28 + 'px';
                            });

                            levels = Math.max(levels, parseInt($booking2.css('top')) / 28);
                        }
                    })
                })
            }

            if (levels > 1) {
                $col.css('height', (levels + 1) * 28 + 49);
                $col.find('.wpbs-bm-calendar-col-fixed').css('height', (levels + 1) * 28 + 49);
            }
        });

        $('body').on('mouseover', ".wpbs-bm-calendar-day", function () {
            $(".wpbs-bm-calendar-day").removeClass('hover');
            $(this).addClass('hover')
            var index = $(this).index();

            $(".wpbs-bm-calendar-col-dates").each(function () {
                $(this).find(".wpbs-bm-calendar-day").eq(index).addClass('hover');
            });
        })

        $('body').on('mouseleave', ".wpbs-bm-calendar-day", function () {
            $(".wpbs-bm-calendar-day").removeClass('hover');

        });

        $(".wpbs-bm-select-container select").change(function () {
            $(this).parents('.wpbs-bm-calendar').append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
            $(this).parents('.wpbs-bm-calendar').addClass('wpbs-is-loading');

            $(this).parents('form').submit();
            $(this).parents('.wpbs-bm-calendar').find('select').attr('disabled', true);
        });

        $(".wpbs-bm-calendar-header-navigation a").click(function () {
            $(this).parents('.wpbs-bm-calendar').append('<div class="wpbs-overlay"><div class="wpbs-overlay-spinner"><div class="wpbs-overlay-bounce1"></div><div class="wpbs-overlay-bounce2"></div><div class="wpbs-overlay-bounce3"></div></div></div>');
            $(this).parents('.wpbs-bm-calendar').addClass('wpbs-is-loading');
            $(this).parents('.wpbs-bm-calendar').find('select').attr('disabled', true);
        })


        $(".wpbs-bm-calendar-rows-wrapper").scroll(function () {
            $(".wpbs-bm-calendar-row.wpbs-bm-calendar-row-header .wpbs-bm-calendar-col-dates").css('left', $('.wpbs-bm-calendar-rows-wrapper').scrollLeft() * -1);
        });

        $("body").on('change', '.hide-past-bookings input', function () {
            $checkbox = $(this);

            var data = {
                action: 'wpbs_set_hide_bookings_filter',
                hide_past_bookings: $checkbox.prop('checked') ? 'on' : ''
            };


            $.post(ajaxurl, data, function (response) {

                window.location.reload();

            });

        });



        wpbs_datepicker_week_start = (wpbs_plugin_settings.backend_start_day) ? wpbs_plugin_settings.backend_start_day : 1;

        // Bulk Edit Availability
        var wpbs_bm_start_date = $('#wpbs-bm-start-date').datepicker({
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
        }).on('change', function () {
            wpbs_bm_start_date.datepicker("option", "minDate", wpbs_datepicker_get_date(this));
        }),

            wpbs_bm_start_date = $('#wpbs-bm-end-date').datepicker({
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

    });

    function wpbs_collision($div1, $div2) {
        var x1 = $div1.offset().left;
        var y1 = $div1.offset().top;
        var h1 = $div1.outerHeight(true);
        var w1 = $div1.outerWidth(true);
        var b1 = y1 + h1;
        var r1 = x1 + w1;
        var x2 = $div2.offset().left;
        var y2 = $div2.offset().top;
        var h2 = $div2.outerHeight(true);
        var w2 = $div2.outerWidth(true);
        var b2 = y2 + h2;
        var r2 = x2 + w2;

        if (b1 < y2 || y1 > b2 || r1 < x2 || x1 > r2) return false;
        return true;
    }
})