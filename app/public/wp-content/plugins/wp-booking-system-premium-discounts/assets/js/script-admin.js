jQuery(function ($) {

    /**
     * Change the discount value icon
     */
    $("#discount_type").change(function () {
        wpbs_change_discount_value_icon();
    })
    wpbs_change_discount_value_icon();

    function wpbs_change_discount_value_icon() {
        $(".wpbs-discount-value-field-inner .discount-type").hide();
        $(".wpbs-discount-value-field-inner .discount-type-" + $("#discount_type").val()).show();
    }

    /**
     * Show/hide the "Apply To" field.
     */
    $("#discount_type").change(function () {
        if ($(this).val() == 'percentage') {
            $(".wpbs-settings-field-discount-apply-to").show();
            $(".wpbs-settings-field-discount-application").hide();
        } else {
            $(".wpbs-settings-field-discount-apply-to").hide();
            $(".wpbs-settings-field-discount-application").show();
        }
    }).trigger('change');

    /**
     * Change the coupon value icon
     */
    $("#coupon_type").change(function () {
        wpbs_change_coupon_value_icon();
    })
    wpbs_change_coupon_value_icon();

    function wpbs_change_coupon_value_icon() {
        $(".wpbs-coupon-value-field-inner .coupon-type").hide();
        $(".wpbs-coupon-value-field-inner .coupon-type-" + $("#coupon_type").val()).show();
    }

    /**
     * Show/hide the "Apply To" field.
     */
    $("#coupon_type").change(function () {
        if ($(this).val() == 'percentage') {
            $(".wpbs-settings-field-coupon-apply-to").show();
        } else {
            $(".wpbs-settings-field-coupon-apply-to").hide();
        }
    }).trigger('change');

    /**
     * Handles the displaying of the correct comparison dropdown
     * 
     */
    $(".wpbs-discount-rules").on('change', '.discount-rule-condition', function () {
        $field = $(this);
        $(this).parents('.wpbs-discount-rule').removeClass().addClass('wpbs-discount-rule wpbs-discount-rule-' + $field.val());
        $comparison_rules_select = $(this).parents('.wpbs-discount-rule').find('.discount-rule-comparison');
        var comparison_rules = $field.find('option:selected').data('comparison-rules');

        $comparison_rules_select.empty();
        $.each(comparison_rules, function (key, value) {
            $comparison_rules_select.append('<option value="' + key + '">' + value + '</option>');
        })
    })

    /**
     * If the page is empty, add the first group
     * 
     */
    if (!$(".wpbs-discount-rules .wpbs-discount-rule").length) {
        wpbs_add_discount_group();
    }

    /**
     * Add discount rule click event
     * 
     */
    $(".wpbs-discount-rules").on('click', '.discount-rule-add-and', function (e) {
        e.preventDefault();
        var group_index = $(this).parents('.wpbs-discount-rule-group').index();
        var rule_index = $(this).parents('.wpbs-discount-rule').index();

        wpbs_add_discount_rule(group_index, rule_index);
    });

    /**
     * Add discount group click event
     */
    $(".wpbs-discount-rules").on('click', '.discount-rule-add-or', function (e) {
        e.preventDefault();
        wpbs_add_discount_group();
    });

    /**
     * Add discount group click event
     */
    $(".wpbs-discount-rules").on('click', '.wpbs-discount-rule-remove', function (e) {
        e.preventDefault();
        $rule = $(this).parents('.wpbs-discount-rule');


        // Remove group if we are deleting the last rule
        if ($rule.parents('.wpbs-discount-rule-group').find('.wpbs-discount-rule').length == 1) {
            $rule.parents('.wpbs-discount-rule-group').remove();
            wpbs_discount_regenerate_group_index();
        }

        // Remove rule
        $rule.remove();

    });

    /**
     * Add discount rule
     * 
     */
    function wpbs_add_discount_rule(group_index, rule_index) {
        var discount_rule_html = $('<div />');
        discount_rule_html.html($("#wpbs-discount-rule-template").html());

        discount_rule_html.find('[data-name]').each(function () {
            $(this).attr('name', $(this).data('name').replace('index', group_index));
        });

        if ($(".wpbs-discount-rule-group").eq(group_index).find('.wpbs-discount-rule-group-inner .wpbs-discount-rule').length) {
            $(discount_rule_html.html()).insertAfter($(".wpbs-discount-rule-group").eq(group_index).find('.wpbs-discount-rule-group-inner .wpbs-discount-rule').eq(rule_index))
            $(".wpbs-discount-rule-group").eq(group_index).find('.wpbs-discount-rule').eq(rule_index + 1).find('.discount-rule-condition').trigger('change');
        } else {
            $(".wpbs-discount-rule-group").eq(group_index).find('.wpbs-discount-rule-group-inner').append(discount_rule_html.html());
            $(".wpbs-discount-rule-group").eq(group_index).find('.discount-rule-condition').trigger('change');
        }

    }

    /**
     * Add discount group
     * 
     */
    function wpbs_add_discount_group() {
        var group_index = $(".wpbs-discount-rule-group").length
        $('<div class="wpbs-discount-rule-group" data-index="' + group_index + '"><div class="wpbs-discount-rule-group-inner"></div><div class="discount-rule-group-separator"><p>or</p></div></div>').insertBefore($(".discount-rule-group-add-group"));
        wpbs_add_discount_rule(group_index, 0);
    }

    /**
     * Regenerate Group Indexes
     * 
     */
    function wpbs_discount_regenerate_group_index() {
        $(".wpbs-discount-rule-group").each(function () {
            var $group = $(this);
            var index = $group.index();
            $group.data('index', index);

            $group.find('[data-name]').each(function () {
                $(this).attr('name', $(this).data('name').replace('index', index));
            });
        })
    }

    /**
     * Validity Datepickers
     * 
     */

    $('#wpbs-coupon-validity-from, #wpbs-coupon-validity-to, .wpbs-discount-validity-datepicker').datepicker({
        maxDate: "+10Y",
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        constrainInput: false,
        showOtherMonths: true,
        selectOtherMonths: true,
        beforeShow: function () {
            $('#ui-datepicker-div').addClass('wpbs-datepicker');
        },
        onClose: function () {
            $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
        },
    }).keyup(function (e) {
        if (e.keyCode == 8 || e.keyCode == 46) {
            $.datepicker._clearDate(this);
        }
    });;

    $('body').on('click', '.wpbs-discount-add-validity-period', function (e) {
        e.preventDefault();

        $row = $(".wpbs-discount-validity-row:first").clone();

        $row.find('input').removeAttr('id').removeClass('hasDatepicker').val('');

        $row.find('input').datepicker({
            maxDate: "+10Y",
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            constrainInput: false,
            showOtherMonths: true,
            selectOtherMonths: true,
            beforeShow: function () {
                $('#ui-datepicker-div').addClass('wpbs-datepicker');
            },
            onClose: function () {
                $('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
            },
        }).keyup(function (e) {
            if (e.keyCode == 8 || e.keyCode == 46) {
                $.datepicker._clearDate(this);
            }
        });;

        $row.insertBefore($(".wpbs-discount-validity-wrapper a.button"));

        wpbs_reset_validity_period_field_index();
    });

    $('body').on('click', '.wpbs-discount-remove-validity-period', function (e) {
        e.preventDefault();
        $(this).parents('.wpbs-discount-validity-row').remove();
        wpbs_reset_validity_period_field_index();
    });

    function wpbs_reset_validity_period_field_index() {
        $(".wpbs-discount-validity-row").each(function (i) {
            $(this).data('index', i);
            $(this).find('input.wpbs-discount-validity-date-from').attr('name', 'discount_validity_period[' + i + '][from]');
            $(this).find('input.wpbs-discount-validity-date-to').attr('name', 'discount_validity_period[' + i + '][to]');
        });
    }


})