(function ($) {

    wp.customize('header_background_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header').css('background-color', newval);
        });
    });

    wp.customize('header_text_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-calendar-header-navigation').css('color', newval);
        });
    });

    wp.customize('header_arrow_background_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-calendar-header-navigation .wpbs-next, .wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-calendar-header-navigation .wpbs-prev').css('background-color', newval);
        });
    });

    wp.customize('header_arrow_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-calendar-header-navigation .wpbs-prev .wpbs-arrow').css('border-color', 'transparent ' + newval + ' transparent transparent');
            $('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-calendar-header-navigation .wpbs-next .wpbs-arrow').css('border-color', 'transparent transparent transparent' + newval);
        });
    });

    wp.customize('calendar_background_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar, .wpbs-legend').css('background-color', newval);
        });
    });
    wp.customize('calendar_border_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar, .wpbs-legend').css('border-color', newval);
        });
    });
    wp.customize('calendar_text_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar, .wpbs-legend').css('color', newval);
        });
    });


    wp.customize('header_dropdown_background_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-select-container select').css('background-color', newval);
        });
    });
    wp.customize('header_dropdown_border_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-select-container select').css('border-color', newval);
        });
    });
    wp.customize('header_dropdown_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar .wpbs-calendar-header .wpbs-select-container select').css('color', newval);
        });
    });

    wp.customize('dates_pad_cells', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar table tr td .wpbs-date.wpbs-gap').css('background-color', newval);
        });
    });

    wp.customize('dates_weeknumbers', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar table td .wpbs-week-number').css('background-color', newval);
        });
    });

    wp.customize('dates_border_radius', function (value) {
        value.bind(function (newval) {
            $('.wpbs-container .wpbs-calendars .wpbs-calendar table tr td .wpbs-date, .wpbs-container .wpbs-calendars .wpbs-calendar table td .wpbs-week-number').css('border-radius', newval + 'px');
        });
    });

    wp.customize('form_text_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-form-container, .wpbs-main-wrapper .wpbs-form-confirmation-message').css('color', newval);
        });
    });

    wp.customize('form_field_background_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=email], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=number], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=text], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field textarea, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field select, .wpbs-form-field-signature .wpbs-signature-pad canvas').each(function () {
                $(this)[0].style.setProperty('background-color', newval, 'important');
            })
        });
    });

    wp.customize('form_field_border_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=email], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=number], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=text], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field textarea, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field select, .wpbs-form-field-signature .wpbs-signature-pad canvas').each(function () {
                $(this)[0].style.setProperty('border-color', newval, 'important');
            })

        });
    });

    wp.customize('form_payment_description', function (value) {
        value.bind(function (newval) {
            $('.wpbs-payment-method-description').each(function () {
                $(this)[0].style.setProperty('background-color', newval, 'important');
            })

        });
    });

    wp.customize('form_field_text_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=email], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=number], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=text], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field textarea, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field select, .wpbs-payment-method-description').each(function () {
                $(this)[0].style.setProperty('color', newval, 'important');
            });
            $(".wpbs-form-field-signature .wpbs-signature-pad a.wpbs-clear-signature svg path").css('fill', newval)

        });
    });

    wp.customize('form_field_description_text_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field-description small').each(function () {
                $(this)[0].style.setProperty('color', newval, 'important');
            })
        });
    });

    wp.customize('form_field_border_radius', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=email], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=number], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type=text], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field textarea, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field select, .wpbs-form-field-signature .wpbs-signature-pad canvas').each(function () {
                $(this)[0].style.setProperty('border-radius', newval + 'px', 'important');
            });
            $('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button.wpbs-coupon-code-button').each(function () {
                $(this)[0].style.setProperty('border-top-right-radius', newval + 'px', 'important');
                $(this)[0].style.setProperty('border-bottom-right-radius', newval + 'px', 'important');
            });

            $('.iti--separate-dial-code .iti__selected-flag').each(function () {
                $(this)[0].style.setProperty('border-top-left-radius', newval + 'px', 'important');
                $(this)[0].style.setProperty('border-bottom-left-radius', newval + 'px', 'important');
            });


        });
    });

    wp.customize('form_button_background_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button.wpbs-coupon-code-button, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button[type="submit"], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type="submit"], .wpbs-payment-confirmation-stripe-form #wpbs-stripe-card-button, .wpbs-payment-confirmation-square-form #wpbs-square-card-button, #wpbs-authorize-net-button-container #wpbs-authorize-net-submit, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-radio .wpbs-form-field-input label input:checked ~ span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-payment_method .wpbs-form-field-input label input:checked ~ span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-checkbox .wpbs-form-field-input label input:checked ~ span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-consent .wpbs-form-field-input label input:checked ~ span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_radio .wpbs-form-field-input label input:checked ~ span, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_checkbox .wpbs-form-field-input label input:checked ~ span').each(function () {
                $(this)[0].style.setProperty('background-color', newval, 'important');
            });

            $(".wpbs-main-wrapper #wpbs-edit-order").each(function () {
                $(this)[0].style.setProperty('color', newval, 'important');
            });
        });
    });

    wp.customize('form_button_text_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button.wpbs-coupon-code-button, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button[type="submit"], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type="submit"], .wpbs-payment-confirmation-stripe-form #wpbs-stripe-card-button, .wpbs-payment-confirmation-square-form #wpbs-square-card-button, #wpbs-authorize-net-button-container #wpbs-authorize-net-submit').each(function () {
                $(this)[0].style.setProperty('color', newval, 'important');
            });

            $(".wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-checkbox .wpbs-form-field-input label span:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-consent .wpbs-form-field-input label span:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_checkbox .wpbs-form-field-input label span:after").each(function () {
                $(this)[0].style.setProperty('border-color', newval, 'important');
            });

            $('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-payment_method .wpbs-form-field-input label input[type="radio"]:checked~span:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-product_radio .wpbs-form-field-input label input[type="radio"]:checked~span:after, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field.wpbs-form-field-radio .wpbs-form-field-input label input[type="radio"]:checked~span:after').each(function () {
                $(this)[0].style.setProperty('background', newval, 'important');
            });
        });
    });

    wp.customize('form_button_border_radius', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-form-container .wpbs-form-field button[type="submit"], .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field input[type="submit"], .wpbs-payment-confirmation-stripe-form #wpbs-stripe-card-button, .wpbs-payment-confirmation-square-form #wpbs-square-card-button, #wpbs-authorize-net-button-container #wpbs-authorize-net-submit').each(function () {
                $(this)[0].style.setProperty('border-radius', newval + 'px', 'important');
            });

        });
    });

    wp.customize('form_error_message_color', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-form-general-error, .wpbs-main-wrapper .wpbs-form-container .wpbs-form-field-error').each(function () {
                $(this)[0].style.setProperty('color', newval, 'important');
            });
        });
    });

    wp.customize('form_max_width', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper .wpbs-payment-confirmation, .wpbs-main-wrapper .wpbs-form-container').each(function () {
                $(this)[0].style.setProperty('max-width', newval + 'px', 'important');
            });
        });
    });

    wp.customize('form_table_heading_background', function (value) {
        value.bind(function (newval) {
            $('.wpbs-main-wrapper table.wpbs-pricing-table thead th, .wpbs-main-wrapper table.wpbs-pricing-table tr.wpbs-line-item-subtotal td, .wpbs-main-wrapper table.wpbs-pricing-table tr.wpbs-line-item-total td').each(function () {
                $(this)[0].style.setProperty('background-color', newval, 'important');
            });
            $('.wpbs-main-wrapper table.wpbs-pricing-table td, .wpbs-main-wrapper table.wpbs-pricing-table th, .wpbs-main-wrapper table.wpbs-pricing-table td:first-child, .wpbs-main-wrapper table.wpbs-pricing-table th:first-child').each(function () {
                $(this)[0].style.setProperty('border-color', newval, 'important');
            });
        });
    });


})(jQuery);