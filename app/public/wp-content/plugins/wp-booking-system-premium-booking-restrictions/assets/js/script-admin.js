jQuery(function ($) {

    /**
     * Show or hide the custom period date selection
     * 
     */
    $(".wpbs-booking-restrictions").on('change', '.wpbs-booking-restrictions-period', function (e) {
        e.preventDefault();
        $custom_period_section = $(this).parents('.wpbs-booking-restriction').find('.wpbs-booking-restrictions-custom-period');

        if ($(this).val() == 'all') {
            $custom_period_section.addClass('wpbs-hide');
        } else {
            $custom_period_section.removeClass('wpbs-hide');
        }
    })

    $(".wpbs-booking-restrictions-period").trigger('change');

    /**
     * Initialize Datepickers
     * 
     */
    $(".wpbs-settings-br-date-range-wrapper").each(function () {
        var $instance = $(this);

        var date_format = $instance.parents('.wpbs-settings-br-date-range-recurring').length ? 'dd MM' : 'dd MM yy';

        $instance.find(".wpbs-br-datepicker").datepicker({
            dateFormat: date_format,
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            altFormat: '@',
            altField: $instance.find('.wpbs-br-datepicker-timestamp'),
            beforeShow: function () {
                $('#ui-datepicker-div').addClass('wpbs-datepicker');
            },
            onClose: function () {
                $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                var selection = new Date(parseInt($instance.find('.wpbs-br-datepicker-timestamp').val()));
                selection.setMinutes(selection.getMinutes() - selection.getTimezoneOffset());
                $instance.find('.wpbs-br-datepicker-timestamp').val(selection.getTime());
            }
        })
    });

    /**
     * Change visible datepickers
     * 
     */
    $("#wpbs-booking-restrictions-wrapper").on('change', '.wpbs-booking-restrictions-custom-period select', function () {
        $instance = $(this).parents('.wpbs-booking-restrictions-custom-period');
        $instance.find('.wpbs-settings-br-date-range-type').hide();
        $instance.find('.wpbs-settings-br-date-range-' + $(this).val()).show();
    })
    $('.wpbs-booking-restrictions-custom-period select').trigger('change');

    /**
     * Set minimum days per stay based on general minimum days per stay value
     * 
     */
    $(".wpbs-booking-restrictions").on('change keyup', '.wpbs-booking-restrictions-minimum-stay', function (e) {
        e.preventDefault();
        $(this).parents('.wpbs-booking-restriction').find('.wpbs-booking-restrictions-minimum-stay-per-day').attr('min', $(this).val()).attr('placeholder', ($(this).val()) ? $(this).val() : 1);
    }).trigger('change');

    /**
     * Set minimum days per stay based on general maximum days per stay value
     * 
     */
    $(".wpbs-booking-restrictions").on('change keyup', '.wpbs-booking-restrictions-maximum-stay', function (e) {
        e.preventDefault();
        $(this).parents('.wpbs-booking-restriction').find('.wpbs-booking-restrictions-minimum-stay-per-day').attr('max', ($(this).val()) ? $(this).val() : '');
    });


    /**
     * Add new Restriction Rule
     * 
     */
    $(".wpbs-booking-restrictions-add-new").click(function (e) {
        e.preventDefault();

        // Get index
        var $index = $('.wpbs-booking-restrictions').data('index');

        // Increment it
        $('.wpbs-booking-restrictions').data('index', $index + 1);

        $restriction = $('.wpbs-booking-restrictions .wpbs-booking-restriction').first().clone();

        // Reset <input /> fields
        $restriction.find('input').each(function () {
            if ($(this).attr('type') != 'checkbox') {
                $(this).val('');
            } else {
                $(this).prop('checked', false);
            }
            $(this).attr('name', $(this).data('name').replace('id', $index))
        });

        // Reset <select /> fields
        $restriction.find('select').each(function () {
            $(this).find('option').first().prop('selected', true);
            $(this).attr('name', $(this).data('name').replace('id', $index))
        });

        $restriction.find('.wpbs-booking-restrictions-period').val('custom');
        $restriction.find('.wpbs-booking-restrictions-custom-period').removeClass('wpbs-hide');
        $restriction.find('.wpbs-booking-restrictions-priority').removeClass('wpbs-hide');

        $restriction.find('h3 span').text($restriction.find('h3').data('custom-period-title'))

        // Remove "Enforce Days" extra fields
        $restriction.find('.wpbs-enforce-days-field').slice(2).remove();

        // Initialize datepickers
        $restriction.find(".wpbs-settings-br-date-range-wrapper").each(function () {
            var $instance = $(this);

            var date_format = $instance.parents('.wpbs-settings-br-date-range-recurring').length ? 'dd MM' : 'dd MM yy';

            $instance.find(".wpbs-br-datepicker").removeClass('hasDatepicker').removeAttr('id').datepicker({
                dateFormat: date_format,
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                altFormat: '@',
                altField: $instance.find('.wpbs-br-datepicker-timestamp'),
                beforeShow: function () {
                    $('#ui-datepicker-div').addClass('wpbs-datepicker');
                },
                onClose: function () {
                    $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                    var selection = new Date(parseInt($instance.find('.wpbs-br-datepicker-timestamp').val()));
                    selection.setMinutes(selection.getMinutes() - selection.getTimezoneOffset());
                    $instance.find('.wpbs-br-datepicker-timestamp').val(selection.getTime());
                }
            });
        })

        $('.wpbs-booking-restrictions').append($restriction);



    });

    /**
     * Delete Restriction Rule
     * 
     */
    $(".wpbs-booking-restrictions").on('click', '.wpbs-booking-restriction-remove', function (e) {
        e.preventDefault();

        if (!confirm("Are you sure you want to remove this row?"))
            return false;

        $(this).parents('.wpbs-booking-restriction').remove();

    });


    /**
     * Add new Fixed Date Interval
     * 
     */
    $(".wpbs-fixed-intervals-add-new").click(function (e) {
        e.preventDefault();

        // Get index
        var $index = $('.wpbs-fixed-intervals').data('index');

        // Increment it
        $('.wpbs-fixed-intervals').data('index', $index + 1);

        $restriction = $('.wpbs-fixed-intervals .wpbs-fixed-interval').first().clone();

        // Reset <input /> fields
        $restriction.find('input').each(function () {
            $(this).val('');
            $(this).attr('name', $(this).data('name').replace('id', $index))
        });

        // Initialize datepickers
        $restriction.find(".wpbs-settings-br-date-range-wrapper").each(function () {
            var $instance = $(this);

            var date_format = $instance.parents('.wpbs-settings-br-date-range-recurring').length ? 'dd MM' : 'dd MM yy';

            $instance.find(".wpbs-br-datepicker").removeClass('hasDatepicker').removeAttr('id').datepicker({
                dateFormat: date_format,
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                altFormat: '@',
                altField: $instance.find('.wpbs-br-datepicker-timestamp'),
                beforeShow: function () {
                    $('#ui-datepicker-div').addClass('wpbs-datepicker');
                },
                onClose: function () {
                    $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                    var selection = new Date(parseInt($instance.find('.wpbs-br-datepicker-timestamp').val()));
                    selection.setMinutes(selection.getMinutes() - selection.getTimezoneOffset());
                    $instance.find('.wpbs-br-datepicker-timestamp').val(selection.getTime());
                }
            });
        })

        $('.wpbs-fixed-intervals .inside').append($restriction);

    });

    /**
     * Delete Fixed Interval Rule
     * 
     */
    $(".wpbs-fixed-intervals").on('click', '.wpbs-fixed-interval-remove', function (e) {
        e.preventDefault();

        if (!confirm("Are you sure you want to remove this row?"))
            return false;

        $(this).parents('.wpbs-fixed-interval').remove();

    });


});
