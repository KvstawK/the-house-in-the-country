$ = jQuery.noConflict();

var wpbs_unsaved_changes = false;
var wpbs_form_submitting = false;

jQuery(function ($) {

    if (!$("#wpbs-form-builder").length) return;

    var wpbs_form_builder = new WPBS_Form_Builder(wpbs_form_data, wpbs_available_field_types, wpbs_available_field_types_options, wpbs_languages);

    /**
     * Set the form_submitting variable.
     * 
     */
    jQuery(".wpbs-save-form").click(function () {
        wpbs_form_submitting = true;
    })

    /**
     * Set the unsaved_changes variable.
     * 
     */
    jQuery(".wpbs-wrap-edit-form").on('change keyup', 'input, select, textarea', function () {
        wpbs_unsaved_changes = true;
    })

    /**
     * Add new fields to the form builder
     *
     */
    $(".wpbs-form-builder-add-form-fields a").click(function (e) {
        e.preventDefault();
        // Add new field
        var form_field = $.extend(true, {}, wpbs_available_field_types[$(this).data('field-type')]);

        form_field['id'] = $("#wpbs_form_field_id_index").val();
        $("#wpbs_form_field_id_index").val(parseInt(form_field['id']) + 1);
        wpbs_form_builder.form_data.push(form_field);
        wpbs_form_builder.render();

        // Close all fields
        $("#wpbs-form-builder .form-field").removeClass('open').find(".form-field-content").hide();

        // Open last field
        $("#wpbs-form-builder .form-field").last().addClass('open').find(".form-field-content").show();

        // Show notice on notification pages
        wpbs_show_form_changed_notice();
    })

    /**
     * Update form object when interacting with fields
     *
     */
    $("#wpbs-form-builder").on('keyup change', 'input, textarea, select', function () {
        field = $(this);
        eq = field.parents('.form-field').index();

        if (!field.data('language')) {
            return;
        }

        language = field.data('language');
        key = field.data('key');

        if (typeof wpbs_form_builder.form_data[eq]['values'][language] === 'undefined') {
            wpbs_form_builder.form_data[eq]['values'][language] = [];
        }

        if (field.attr('type') == 'checkbox') {
            wpbs_form_builder.form_data[eq]['values'][language][key] = field.prop('checked') == true ? 'on' : '';
        } else {
            if (key == 'conditional_logic_rules') {
                if (typeof wpbs_form_builder.form_data[eq]['values'][language][key] === 'undefined') {
                    wpbs_form_builder.form_data[eq]['values'][language][key] = [];
                }
                if (typeof wpbs_form_builder.form_data[eq]['values'][language][key][field.data('index')] === 'undefined') {
                    wpbs_form_builder.form_data[eq]['values'][language][key][field.data('index')] = [];
                }
                wpbs_form_builder.form_data[eq]['values'][language][key][field.data('index')][field.data('subkey')] = field.val();
            } else {
                if (key == 'options' || key == 'options_pricing') {
                    if (typeof wpbs_form_builder.form_data[eq]['values'][language][key] === 'undefined') {
                        wpbs_form_builder.form_data[eq]['values'][language][key] = [];
                    }
                    wpbs_form_builder.form_data[eq]['values'][language][key][field.parent().index()] = field.val();
                } else {
                    wpbs_form_builder.form_data[eq]['values'][language][key] = field.val();
                }
            }
        }

        // // Update conditional logic field labels
        wpbs_form_builder.form_data.forEach(function (field_data) {
            $('select.wpbs-update-field-names option[value="' + field_data['id'] + '"]').text(field_data['values']['default']['label']);
        })

    })

    /**
     * Join pricing fields into one.
     * 
     */
    $("#wpbs-form-builder").on('keyup change', '.form-field-pricing-fields', function () {
        $parent = $(this).parent();
        $parent.find('input').eq(2).val($parent.find('.price').val() + '|' + $parent.find('.value').val()).trigger('keyup')
    })


    /**
     * Duplicate the label option into the field header
     */
    $("#wpbs-form-builder").on('keyup', 'input[data-key="label"][data-language="default"]', function () {
        field = $(this);
        field_value = field.val() ? wpbs_escape_attr(field.val()) : field.parents('.form-field').find('.form-field-header-label').data('field-type');
        field.parents('.form-field').find('.form-field-header-label').html(field_value);
    });

    /**
     * Remove fields button
     */
    $("#wpbs-form-builder").on('click', '.form-field-remove', function (e) {
        e.preventDefault();

        if (!confirm("Are you sure you want to remove this field?"))
            return false;

        field = $(this);
        eq = field.parents('.form-field').index();
        wpbs_form_builder.form_data = wpbs_form_builder.form_data.filter(function (item, index) {
            return index !== eq
        })

        wpbs_form_data = wpbs_form_builder.form_data;

        wpbs_form_builder.render();

        // Show notice on notification pages
        wpbs_show_form_changed_notice();
    })

    /**
     * Remove fields button
     */
    $("#wpbs-form-builder").on('click', '.form-field-duplicate', function (e) {
        e.preventDefault();

        field = $(this);
        id = field.parents('.form-field').data('field-id');

        wpbs_form_data = wpbs_form_builder.form_data;

        for (var i = 0; i < wpbs_form_data.length; i++) {
            if (parseInt(wpbs_form_data[i]['id']) === parseInt(id)) {

                var field_data = $.extend(true, {}, wpbs_form_data[i]);
                break;
            }
        }

        field_data['id'] = $("#wpbs_form_field_id_index").val();
        $("#wpbs_form_field_id_index").val(parseInt(field_data['id']) + 1);

        if (typeof field_data['values']['default']['label'] !== 'undefined') {
            field_data['values']['default']['label'] = 'Duplicate of ' + field_data['values']['default']['label'];
        }

        wpbs_form_builder.form_data.push(field_data);
        wpbs_form_builder.render();

        // Show notice on notification pages
        wpbs_show_form_changed_notice();
    })

    /**
     * Make fields collapsable
     */
    $("#wpbs-form-builder").on('click', '.form-field-header', function (e) {
        e.preventDefault();

        field = $(this);
        field.parents('.form-field').toggleClass('open').find(".form-field-content").slideToggle();
    })

    /**
     * Field options accordion
     */
    $("#wpbs-form-builder").on('click', '.form-field-accordion-open', function (e) {
        e.preventDefault();

        accordion = $(this);
        accordion.parents('.form-field-accordion').toggleClass('open').find(".form-field-accordion-inner").slideToggle();

        if (accordion.parents('.form-field-accordion').find('.form-field-tabs').length) {
            accordion.parents('.form-field-accordion').find('.form-field-tabs .form-field-tabs-navigation a').first().trigger('click');
        }
    })

    /**
     * Field options tabs
     */
    $("#wpbs-form-builder").on('click', '.form-field-tabs-navigation a', function (e) {
        e.preventDefault();
        var tab = $(this);

        tab.parents('.form-field-tabs').find(".form-field-tab").hide();
        tab.parents('.form-field-tabs').find(".form-field-tabs-navigation a").removeClass('active');

        tab.addClass('active');
        $(tab.data('tab')).show();
    })



    /**
     * Add option to dropdown, radio, checkboxes, etc. fields
     */
    $("#wpbs-form-builder").on('click', '.form-field-add-option', function (e) {
        e.preventDefault();
        options = $(this).parents('.form-field-options');
        $field_option = options.find('.form-field-option-placeholder').clone();
        $field_option.removeClass('form-field-option-placeholder').find('input[data-name]').attr('name', options.find('input[data-name]').data('name'))
        $field_option.appendTo(options.find('.form-field-options-inner-fields'));
    })

    /**
     * Remove dropdown, radio, checkboxes, etc. option fields
     */
    $("#wpbs-form-builder").on('click', '.form-field-option-field-remove', function (e) {
        e.preventDefault();

        if (!confirm("Are you sure you want to remove this option?"))
            return false;

        var field = $(this).siblings('input[data-key]');
        var field_parent = field.parents('.form-field-options');
        var eq = field.parents('.form-field').index();

        // Remove the field.
        $(this).parent().remove();

        // Rebuild field options.
        wpbs_form_builder.form_data[eq]['values'][field.data('language')][field.data('key')] = [];

        field_parent.find('.form-field-options-inner-fields input[data-key]').each(function () {
            var option_field = $(this);
            wpbs_form_builder.form_data[eq]['values'][field.data('language')][field.data('key')][option_field.parent().index()] = option_field.val();
        })

    })



    /**
     * Make fields sortable
     */
    $('#wpbs-form-builder').sortable({
        handle: '.form-field-sort',
        placeholder: 'form-field-placeholder',
        containment: '#wpcontent',
        update: function () {
            form_sorted = [];
            $("#wpbs-form-builder .form-field").each(function (i) {
                new_position = $(this).data('order');
                form_sorted[i] = wpbs_form_builder.form_data[new_position];
            })
            wpbs_form_builder.form_data = form_sorted;
            wpbs_form_builder.render();
        }
    });

    /**
     * Conditional Logic
     * 
     */
    $("#wpbs-form-builder").on('click', '.conditional-logic-add-rule', function (e) {
        e.preventDefault();
        $parent = $(this).parents('.form-field-row-type-conditional-logic-wrapper');

        if (!$parent.find(".conditional-logic-rule-0").length) {
            return false;
        }

        $main_rule = $parent.find(".conditional-logic-rule-0");
        field_index = $main_rule.data('field-index');
        rule_index = 0;
        $parent.find(".conditional-logic-rule").each(function () {
            if ($(this).data('rule-index') > rule_index) {
                rule_index = $(this).data('rule-index');
            }
        })
        rule_index++;

        $parent.find('.conditional-logic-rules').append(wpbs_form_builder.conditional_logic_repeater_fields_html(field_index, rule_index));

    });

    $("#wpbs-form-builder").on('click', '.conditional-logic-remove-rule', function (e) {
        e.preventDefault();
        if (!confirm("Are you sure you want to remove this rule?"))
            return false;

        $rule = $(this).parents('.conditional-logic-rule');
        field_index = $rule.data('field-index');
        rule_index = $rule.data('rule-index');
        delete wpbs_form_data[field_index]['values']['default']['conditional_logic_rules'][rule_index];
        $rule.remove();
    });

    $("#wpbs-form-builder").on('change', '.form-field-row-type-conditional-logic input', function (e) {
        if ($(this).prop('checked')) {
            $(this).parents('.form-field-accordion-inner').find('.form-field-row-type-conditional-logic-wrapper').removeClass('form-field-row-type-conditional-logic-disabled').addClass('form-field-row-type-conditional-logic-enabled');
        } else {
            $(this).parents('.form-field-accordion-inner').find('.form-field-row-type-conditional-logic-wrapper').removeClass('form-field-row-type-conditional-logic-enabled').addClass('form-field-row-type-conditional-logic-disabled');
        }
    });

    /**
     * Datepicker field for conditional logic Start Date and End Date values
     * 
     */
    $("#wpbs-form-builder").on('change', '.form-field-row-type-conditional-logic-rule-field select', function (e) {
        var $row = $(this).parents('.conditional-logic-rule');
        if (($(this).val() == 'start_date' || $(this).val() == 'end_date')) {
            if ($row.find(".form-field-row-type-conditional-logic-rule-value input").hasClass('hasDatepicker')) {
                return;
            }
            $row.find(".form-field-row-type-conditional-logic-rule-value input").val('');
            $row.find(".form-field-row-type-conditional-logic-rule-value input").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                selectOtherMonths: true,
                firstDay: wpbs_datepicker_week_start,
                beforeShow: function () {
                    jQuery('#ui-datepicker-div').addClass('wpbs-datepicker');
                },
                onClose: function (value, object) {
                    jQuery('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                },

            }).keyup(function (e) {
                if (e.keyCode == 8 || e.keyCode == 46) {
                    $.datepicker._clearDate(this);
                }
            });;

            $row.find(".form-field-row-type-conditional-logic-rule-value input").prop('readonly', true);
            $('.form-field-row-type-conditional-logic-rule-condition option[value="contains"], .form-field-row-type-conditional-logic-rule-condition option[value="starts"], .form-field-row-type-conditional-logic-rule-condition option[value="ends"]').prop('disabled', true)
        } else {

            if ($row.find(".form-field-row-type-conditional-logic-rule-value input").hasClass('hasDatepicker')) {
                $row.find(".form-field-row-type-conditional-logic-rule-value input").datepicker('destroy');
                $row.find(".form-field-row-type-conditional-logic-rule-value input").removeClass('hasDatepicker');
            }

            $row.find(".form-field-row-type-conditional-logic-rule-value input").prop('readonly', false);
            $row.find('.form-field-row-type-conditional-logic-rule-condition option[value="contains"], .form-field-row-type-conditional-logic-rule-condition option[value="starts"], .form-field-row-type-conditional-logic-rule-condition option[value="ends"]').prop('disabled', false)
        }

        if ($(this).val() == 'start_weekday' || $(this).val() == 'end_weekday') {
            $row.find(".form-field-row-type-conditional-logic-rule-value input").hide();
            $row.find(".form-field-row-type-conditional-logic-rule-value select").show();
        } else {
            $row.find(".form-field-row-type-conditional-logic-rule-value input").show();
            $row.find(".form-field-row-type-conditional-logic-rule-value select").hide();
        }
    });






    /**
     * Toggle confirmation messages
     */
    $(".wpbs-wrap-edit-form").on('change', '#form_confirmation_type', function () {
        $(this).parents('.wpbs-tab').find(".wpbs-confirmation-type").hide();
        $(".wpbs-confirmation-type-" + $(this).val()).show();
    })

    /**
     * Show a warning on the notifications page if the form has changed
     */
    function wpbs_show_form_changed_notice() {
        $(".wpbs-form-changed-notice").show();
    }

    // Build the form.
    wpbs_form_builder.render();

});

class WPBS_Form_Builder {
    constructor(form_data, available_field_types, available_field_types_options, languages) {
        this.wrapper = document.getElementById('wpbs-form-builder');
        this.output;
        this.form_data = form_data;
        this.available_field_types = available_field_types;
        this.available_field_types_options = available_field_types_options;
        this.languages = languages;
    }

    build() {
        this.output = '';
        if (this.form_data.length) {
            this.fields();
            jQuery(".wpbs-start-building").hide();
        } else {
            jQuery(".wpbs-start-building").show();
        }
    }
    /**
     * Build the Fields
     */
    fields() {
        this.form_data.forEach(function (field, i) {
            this.field(field, i);
        }.bind(this))
    }

    /**
     * Build each individual field
     * 
     * @param field 
     * @param i 
     */
    field(field, i) {

        if (typeof this.available_field_types[field.type] === "undefined") {
            return false
        }

        this.output += '<div class="form-field form-field-type-' + field.type + '" data-order="' + i + '" data-field-id="' + field.id + '">';
        this.output += '<div class="form-field-inner">';
        this.field_header(field);
        this.output += '<div class="form-field-content"><div class="form-field-content-inner">';
        this.output += '<input type="hidden" name="form_fields[' + i + '][type]" value="' + field.type + '" />';
        this.output += '<input type="hidden" name="form_fields[' + i + '][id]" value="' + field.id + '" />';
        this.field_options(field, i);
        this.field_translation_options(field, i);
        this.field_conditional_logic(field, i);
        this.output += '</div></div>';
        this.output += '</div>';
        this.output += '</div>';
    }

    /**
     * Build the header for a field
     * 
     * @param  field 
     */
    field_header(field) {
        var field_nice_name = field.type.replace(/_/g, ' ');
        var label = (typeof field['values']['default'] !== 'undefined' && typeof field['values']['default']['label'] !== 'undefined' && field['values']['default']['label'] != '') ? field['values']['default']['label'] : '<span><span>' + field_nice_name + '</span> Field</span>';



        this.output += '<div class="form-field-header">';
        this.output += '<div class="form-field-header-buttons">';
        this.output += '<span class="form-field-id">ID:' + field.id + '</span>';
        this.output += '<a href="#" title="Remove" class="form-field-remove"><i class="wpbs-icon-close"></i></a>';
        this.output += '<a href="#" title="Duplicate" class="form-field-duplicate"><i class="wpbs-icon-copy"></i></a>';
        this.output += '<a href="#" title="Reorder" class="form-field-sort"><i class="wpbs-icon-sort"></i></a>';
        this.output += '<a href="#" class="form-field-toggle"><i class="wpbs-icon-down-arrow"></i></a>';
        this.output += '</div>';

        this.output += '<p><i class="wpbs-icon-' + field.type + '"></i> <span data-field-type="<span><span>' + field_nice_name + '</span> Field</span>" class="form-field-header-label">' + label + '</span></p>';
        this.output += '</div>';
    }

    /**
     * Build the field options
     * 
     * @param field 
     * @param i 
     * @param language 
     */
    field_options(field, i, language = 'default') {

        // Primary Fields
        if (this.available_field_types[field.type].supports.primary) {
            this.available_field_types[field.type].supports.primary.forEach(function (option) {
                this.field_option(option, i, language)
            }.bind(this))
        }

        if (language == 'default' && this.available_field_types[field.type].supports.secondary) {
            this.output += '<div class="form-field-accordion">';
            this.output += '<div class="form-field-accordion-header">';
            this.output += '<a href="#" class="form-field-accordion-open">Advanced Options <i class="wpbs-icon-down-arrow"></i></a>';
            this.output += '</div>';
            this.output += '<div class="form-field-accordion-inner">';
        }

        //Secondary Fields
        if (this.available_field_types[field.type].supports.secondary) {
            this.available_field_types[field.type].supports.secondary.forEach(function (option) {
                this.field_option(option, i, language)
            }.bind(this))
        }

        if (language == 'default' && this.available_field_types[field.type].supports.secondary) {
            this.output += '</div>';
            this.output += '</div>';
        }

    }

    /**
     * Build the translatable options
     * 
     * @param field 
     * @param i 
     */
    field_translation_options(field, i) {

        // If there are no languages, we skip this section
        if (!this.available_field_types[field.type].languages) return;

        this.output += '<div class="form-field-accordion">';
        this.output += '<div class="form-field-accordion-header">';
        this.output += '<a href="#" class="form-field-accordion-open">Translations <i class="wpbs-icon-down-arrow"></i></a>';
        this.output += '</div>';
        this.output += '<div class="form-field-accordion-inner">';

        this.output += '<div class="form-field-tabs">';
        this.output += '<div class="form-field-tabs-navigation">';
        this.available_field_types[field.type].languages.forEach(function (language, j) {
            this.output += '<a href="#" data-tab="#form-field-tab-' + language + '-' + i + '-' + j + '"><img src="' + wpbs_localized_data.wpbs_plugins_dir_url + '/assets/img/flags/' + language + '.png" />' + this.languages[language] + '</a>';
        }.bind(this))
        this.output += '</div>';

        this.output += '<div class="form-field-tabs-inner">';

        this.available_field_types[field.type].languages.forEach(function (language, j) {
            this.output += '<div id="form-field-tab-' + language + '-' + i + '-' + j + '" class="form-field-tab form-field-translation form-field-translation-' + language + '">';
            this.field_options(field, i, language);
            this.output += '</div>';
        }.bind(this))

        this.output += '</div>';
        this.output += '</div>';

        this.output += '</div>';
        this.output += '</div>';
    }

    /**
     * Build the conditional logic options
     * 
     * @param field 
     * @param i 
     */
    field_conditional_logic(field, i) {
        if (field['type'] == 'payment_method' || field['type'] == 'captcha' || field['type'] == 'consent' || field['type'] == 'total' || field['type'] == 'hidden' || field['type'] == 'security_deposit') {
            return;
        }

        this.output += '<div class="form-field-accordion">';
        this.output += '<div class="form-field-accordion-header">';
        this.output += '<a href="#" class="form-field-accordion-open">Conditional Logic <i class="wpbs-icon-down-arrow"></i></a>';
        this.output += '</div>';
        this.output += '<div class="form-field-accordion-inner">';
        this.output += '<div class="form-field-row form-field-row-type-conditional-logic">';
        var conditional_fields_status = (typeof field['values']['default']['conditional_logic'] !== 'undefined' && field['values']['default']['conditional_logic'] == 'on') ? 'enabled' : 'disabled';
        this.field_option_type_checkbox('conditional_logic', 'Enable', 'default', i);
        this.output += '</div>';

        this.output += '<div class="form-field-row form-field-row-type-conditional-logic-wrapper form-field-row-type-conditional-logic-' + conditional_fields_status + '">';

        var value = (typeof field['values']['default'] !== 'undefined' && typeof field['values']['default']['conditional_logic_action'] !== 'undefined') ? field['values']['default']['conditional_logic_action'] : 'show';
        this.output += '<div class="form-field-row form-field-row-type-conditional-logic-action"><select id="form-field-' + i + '-layout-default" type="text" data-language="default" data-key="conditional_logic_action" value="wpbs-field-layout-default" name="form_fields[' + i + '][values][default][conditional_logic_action]"><option value="show" ' + (value == 'show' ? 'selected="selected"' : '') + '>Show</option><option value="hide" ' + (value == 'hide' ? 'selected="selected"' : '') + '>Hide</option></select></div>';

        this.output += '<span>this field if</span>';

        var value = (typeof field['values']['default'] !== 'undefined' && typeof field['values']['default']['conditional_logic_logic_type'] !== 'undefined') ? field['values']['default']['conditional_logic_logic_type'] : 'all';
        this.output += '<div class="form-field-row form-field-row-type-conditional-logic-type"><select id="form-field-' + i + '-layout-default" type="text" data-language="default" data-key="conditional_logic_logic_type" value="wpbs-field-layout-default" name="form_fields[' + i + '][values][default][conditional_logic_logic_type]"><option value="all" ' + (value == 'all' ? 'selected="selected"' : '') + '>All</option><option value="any" ' + (value == 'any' ? 'selected="selected"' : '') + '>Any</option></select></div>';

        this.output += '<span>of the following match:</span>';

        this.output += '<div class="conditional-logic-rules">';
        if (typeof field['values']['default']['conditional_logic_rules'] !== 'undefined' && field['values']['default']['conditional_logic_rules'].length > 0) {
            jQuery.each(field['values']['default']['conditional_logic_rules'], function (rule_index, rule) {
                this.conditional_logic_repeater_fields(i, rule_index);
            }.bind(this))
        } else {
            this.conditional_logic_repeater_fields(i, 0);
        }

        this.output += '</div>';

        this.output += '</div>';
        this.output += '</div>';
        this.output += '</div>';
    }

    conditional_logic_repeater_fields(i, rule_index) {
        this.output += this.conditional_logic_repeater_fields_html(i, rule_index);
    }

    conditional_logic_repeater_fields_html(i, rule_index) {

        var output = '';
        var field = this.form_data[i];
        output += '<div class="conditional-logic-rule conditional-logic-rule-' + rule_index + '" data-rule-index="' + rule_index + '" data-field-index="' + i + '">';

        // Field
        var value = (typeof field['values']['default'] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'][rule_index] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'][rule_index]['field'] !== 'undefined') ? field['values']['default']['conditional_logic_rules'][rule_index]['field'] : '';
        output += '<div class="form-field-row form-field-row-type-conditional-logic-rule-field"><select id="form-field-' + i + '-cf-field-' + rule_index + '" type="text" data-language="default" data-key="conditional_logic_rules" class="wpbs-update-field-names" data-index="' + rule_index + '" data-subkey="field" name="form_fields[' + i + '][values][default][conditional_logic_rules][' + rule_index + '][field]"><option value=""></option>';
        output += '<optgroup label="Form Fields">"';
        this.form_data.forEach(function (fields, field_index) {
            if (field_index == i) { return true; }
            if (fields['type'] == 'total' || fields['type'] == 'captcha' || fields['type'] == 'consent' || fields['type'] == 'hidden') {
                return true;
            }
            output += '<option ' + (value == fields['id'] ? 'selected="selected"' : '') + ' value="' + fields['id'] + '">' + fields['values']['default']['label'] + '</option>';
        }.bind(this));
        output += '</optgroup>';
        output += '<optgroup label="Calendar Rules">"';
        output += '<option ' + (value == 'stay_length' ? 'selected="selected"' : '') + ' value="stay_length">Stay length</option>';
        output += '<option ' + (value == 'start_date' ? 'selected="selected"' : '') + ' value="start_date">Start Date</option>';
        output += '<option ' + (value == 'end_date' ? 'selected="selected"' : '') + ' value="end_date">End Date</option>';
        output += '<option ' + (value == 'start_weekday' ? 'selected="selected"' : '') + ' value="start_weekday">Start Weekday</option>';
        output += '<option ' + (value == 'end_weekday' ? 'selected="selected"' : '') + ' value="end_weekday">End Weekday</option>';
        output += '<option ' + (value == 'calendar_id' ? 'selected="selected"' : '') + ' value="calendar_id">Calendar ID</option>';
        output += '</optgroup>';
        output += '</select></div>';

        // Condition
        var value = (typeof field['values']['default'] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'][rule_index] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'][rule_index]['condition'] !== 'undefined') ? field['values']['default']['conditional_logic_rules'][rule_index]['condition'] : 'is';
        output += '<div class="form-field-row form-field-row-type-conditional-logic-rule-condition"><select id="form-field-' + i + '-cf-condition-' + rule_index + '" type="text" data-language="default" data-key="conditional_logic_rules" data-index="' + rule_index + '" data-subkey="condition"  name="form_fields[' + i + '][values][default][conditional_logic_rules][' + rule_index + '][condition]"><option value="is" ' + (value == 'is' ? 'selected="selected"' : '') + '>is</option><option value="isnot" ' + (value == 'isnot' ? 'selected="selected"' : '') + '>is not</option><option value="greater" ' + (value == 'greater' ? 'selected="selected"' : '') + '>is greater than</option><option value="lower" ' + (value == 'lower' ? 'selected="selected"' : '') + '>is lower than</option><option value="contains" ' + (value == 'contains' ? 'selected="selected"' : '') + '>contains</option><option value="starts" ' + (value == 'starts' ? 'selected="selected"' : '') + '>starts with</option><option value="ends" ' + (value == 'ends' ? 'selected="selected"' : '') + '>ends with</option></select></div>';

        // Value
        var value = (typeof field['values']['default'] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'][rule_index] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'][rule_index]['value'] !== 'undefined') ? field['values']['default']['conditional_logic_rules'][rule_index]['value'] : '';
        var select_value = (typeof field['values']['default'] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'][rule_index] !== 'undefined' && typeof field['values']['default']['conditional_logic_rules'][rule_index]['select_value'] !== 'undefined') ? field['values']['default']['conditional_logic_rules'][rule_index]['select_value'] : '';
        output += '<div class="form-field-row form-field-row-type-conditional-logic-rule-value"><input id="form-field-' + i + '-cf-value-' + rule_index + '" type="text" data-language="default" data-key="conditional_logic_rules" data-index="' + rule_index + '" data-subkey="value" name="form_fields[' + i + '][values][default][conditional_logic_rules][' + rule_index + '][value]" value="' + value + '" /><select id="form-field-' + i + '-cf-select-value-' + rule_index + '"  data-language="default" data-key="conditional_logic_rules" data-index="' + rule_index + '" data-subkey="select_value" name="form_fields[' + i + '][values][default][conditional_logic_rules][' + rule_index + '][select_value]"><option ' + (select_value == 1 ? 'selected' : '') + ' value="1">Monday</option><option ' + (select_value == 2 ? 'selected' : '') + ' value="2">Tuesday</option><option ' + (select_value == 3 ? 'selected' : '') + ' value="3">Wednesday</option><option ' + (select_value == 4 ? 'selected' : '') + ' value="4">Thursday</option><option ' + (select_value == 5 ? 'selected' : '') + ' value="5">Friday</option><option ' + (select_value == 6 ? 'selected' : '') + ' value="6">Saturday</option><option ' + (select_value == 7 ? 'selected' : '') + ' value="7">Sunday</option></select></div>';


        if (rule_index != 0) {
            output += '<a href="#" class="conditional-logic-remove-rule"><span class="dashicons dashicons-remove"></span></a>';
        }
        output += '<a href="#" class="conditional-logic-add-rule"><span class="dashicons dashicons-insert"></span></a>';

        output += '</div>';

        return output;
    }

    /**
     * Build the field option
     * 
     * @param field 
     * @param i 
     */
    field_option(option, i, language = 'default') {

        // If we build translation options and the field does not support translation, we skip it
        if (language != 'default' && this.available_field_types_options[option].translatable == false) return;

        var key = this.available_field_types_options[option].key;
        var label = this.available_field_types_options[option].label;
        var input = typeof this.available_field_types_options[option].input !== 'undefined' ? this.available_field_types_options[option].input : 'text';
        var options = typeof this.available_field_types_options[option].options !== 'undefined' ? this.available_field_types_options[option].options : false;
        var default_value = typeof this.available_field_types_options[option].default_value !== 'undefined' ? this.available_field_types_options[option].default_value : '';

        this.output += '<div class="form-field-row form-field-row-type-' + key + '">';

        switch (key) {
            case 'required':
            case 'hide_label':
            case 'dynamic_population':
                this.field_option_type_checkbox(key, label, language, i);
                break;
            case 'options':
                this.field_option_type_options(key, label, language, i);
                break;
            case 'options_pricing':
                this.field_option_type_options_pricing(key, label, language, i);
                break;
            case 'pricing_type':
            case 'inventory_type':
            case 'date_range_type':
            case 'date_format':
            case 'layout':
            case 'decimals':
                this.field_option_type_dropdown(key, label, language, options, i);
                break;
            case 'multiplication':
                this.field_option_type_multiplication(key, label, language, i);
                break;
            case 'date_range':
                this.field_option_type_date_range(key, label, language, i);
                break;
            case 'pricing':
                this.field_option_type_pricing(key, label, default_value, language, i, '0.01');
                break;
            case 'min':
            case 'max':
                this.field_option_type_pricing(key, label, default_value, language, i, '1');
                break;
            default:
                if (key.indexOf('notice_') === -1) {
                    this.field_option_type_default(key, label, input, default_value, language, i);
                } else {
                    this.field_option_type_notice(label);
                }
        }

        this.output += '</div>';
    }


    /**
     * Build the field option inputs based on type
     * 
     * @param key 
     * @param label 
     * @param language 
     * @param i 
     */
    field_option_type_default(key, label, input, default_value, language, i) {
        var value = (typeof this.form_data[i]['values'][language] !== 'undefined' && typeof this.form_data[i]['values'][language][key] !== 'undefined') ? this.form_data[i]['values'][language][key] : default_value;
        if (input == 'textarea') {
            this.output += '<label for="form-field-' + i + '-' + key + '-' + language + '">' + label + '</label><textarea id="form-field-' + i + '-' + key + '-' + language + '" type="text" data-language="' + language + '" data-key="' + key + '" name="form_fields[' + i + '][values][' + language + '][' + key + ']">' + value + '</textarea>';
        } else {
            this.output += '<label for="form-field-' + i + '-' + key + '-' + language + '">' + label + '</label><input id="form-field-' + i + '-' + key + '-' + language + '" type="text" data-language="' + language + '" data-key="' + key + '" value="' + value + '" name="form_fields[' + i + '][values][' + language + '][' + key + ']" />';
        }

    }

    field_option_type_pricing(key, label, default_value, language, i, step) {
        var value = (typeof this.form_data[i]['values'][language] !== 'undefined' && typeof this.form_data[i]['values'][language][key] !== 'undefined') ? this.form_data[i]['values'][language][key] : default_value;
        this.output += '<label for="form-field-' + i + '-' + key + '-' + language + '">' + label + '</label><input id="form-field-' + i + '-' + key + '-' + language + '" type="number" min="0" step="' + step + '" data-language="' + language + '" data-key="' + key + '" value="' + value + '" name="form_fields[' + i + '][values][' + language + '][' + key + ']" />';
    }

    field_option_type_dropdown(key, label, language, options, i) {
        var value = (typeof this.form_data[i]['values'][language] !== 'undefined' && typeof this.form_data[i]['values'][language][key] !== 'undefined') ? this.form_data[i]['values'][language][key] : '';
        this.output += '<label for="form-field-' + i + '-' + key + '-' + language + '">' + label + '</label><select id="form-field-' + i + '-' + key + '-' + language + '" type="text" data-language="' + language + '" data-key="' + key + '" value="' + value + '" name="form_fields[' + i + '][values][' + language + '][' + key + ']">';
        for (var option in options) {
            var selected = (value == option) ? 'selected' : '';
            this.output += '<option value="' + option + '" ' + selected + '>' + options[option] + '</option>';
        }
        this.output += '</select>';
    }

    field_option_type_multiplication(key, label, language, i) {
        var value = (typeof this.form_data[i]['values'][language] !== 'undefined' && typeof this.form_data[i]['values'][language][key] !== 'undefined') ? this.form_data[i]['values'][language][key] : '';
        this.output += '<label for="form-field-' + i + '-' + key + '">' + label + '</label><select id="form-field-' + i + '-' + key + '" type="text" data-language="' + language + '" data-key="' + key + '" value="' + value + '" name="form_fields[' + i + '][values][' + language + '][' + key + ']">';

        this.output += '<option value="0" ' + selected + '>Do not multiply</option>';

        for (var field in wpbs_form_data) {
            if (field == i) {
                continue;
            }
            if (wpbs_form_data[field]['type'] != 'dropdown' && wpbs_form_data[field]['type'] != 'checkbox' && wpbs_form_data[field]['type'] != 'radio' && wpbs_form_data[field]['type'] != 'inventory' && wpbs_form_data[field]['type'] != 'product_dropdown' && wpbs_form_data[field]['type'] != 'product_checkbox' && wpbs_form_data[field]['type'] != 'product_radio' && wpbs_form_data[field]['type'] != 'number' && wpbs_form_data[field]['type'] != 'product_number') {
                continue;
            }
            var selected = (wpbs_form_data[field]['id'] == value) ? 'selected' : '';
            this.output += '<option value="' + wpbs_form_data[field]['id'] + '" ' + selected + '>Multiply by the value of the "' + wpbs_form_data[field]['values']['default']['label'] + '" field</option>';
        }
        this.output += '</select>';
    }

    field_option_type_notice(label) {
        this.output += '<div class="wpbs-page-notice notice-error"><p>' + label + '</p></div>';
    }

    field_option_type_checkbox(key, label, language, i) {
        var value = (typeof this.form_data[i]['values'][language][key] !== 'undefined' && this.form_data[i]['values'][language][key] == 'on') ? 'checked' : '';
        this.output += '<label for="form-field-' + i + '-' + key + '">' + label + '</label><label class="wpbs-checkbox-switch" for="form-field-' + i + '-' + key + '"><input id="form-field-' + i + '-' + key + '" type="checkbox" data-language="' + language + '" data-key="' + key + '" ' + value + ' name="form_fields[' + i + '][values][' + language + '][' + key + ']" /><div class="wpbs-checkbox-slider"></div></label>';
    }


    field_option_type_options(key, label, language, i) {
        this.output += '<div class="form-field-options">';
        this.output += '<label>' + label + '</label>';
        this.output += '<div class="form-field-options-inner">';

        this.output += '<div class="form-field-option-placeholder"><input type="text" data-name="form_fields[' + i + '][values][' + language + '][' + key + '][]" data-language="' + language + '" data-key="' + key + '" /><a href="#" class="form-field-option-field-remove"><i class="wpbs-icon-close"></i></a></div>';

        this.output += '<div class="form-field-options-inner-fields">';
        if (typeof this.form_data[i]['values'][language] !== 'undefined' && typeof this.form_data[i]['values'][language][key] !== 'undefined') {
            this.form_data[i]['values'][language][key].forEach(function (value) {
                if (value)
                    this.output += '<div><input type="text" data-language="' + language + '" data-key="' + key + '" name="form_fields[' + i + '][values][' + language + '][' + key + '][]" value="' + value + '" /><a href="#" class="form-field-option-field-remove"><i class="wpbs-icon-close"></i></a></div>';
            }.bind(this))
        }

        this.output += '</div>';

        this.output += '<a href="#" class="form-field-add-option button button-secondary">Add Option</a>';

        this.output += '</div>';

        this.output += '</div>';
    }

    field_option_type_options_pricing(key, label, language, i) {
        this.output += '<div class="form-field-options">';
        this.output += '<label>' + label + '</label>';
        this.output += '<div class="form-field-options-inner">';

        this.output += '<div class="form-field-option-placeholder"><input type="number" step="0.01" class="form-field-pricing-fields price" /><input type="text" class="form-field-pricing-fields value" /><input type="hidden" data-name="form_fields[' + i + '][values][' + language + '][' + key + '][]" data-language="' + language + '" data-key="' + key + '" /><a href="#" class="form-field-option-field-remove"><i class="wpbs-icon-close"></i></a></div>';

        this.output += '<div class="form-field-options-inner-heading"><span>Price</span><span>Name</span></div>';
        this.output += '<div class="form-field-options-inner-fields">';
        if (typeof this.form_data[i]['values'][language] !== 'undefined' && typeof this.form_data[i]['values'][language][key] !== 'undefined') {
            this.form_data[i]['values'][language][key].forEach(function (value) {
                if (value) {
                    var values = value.split('|');
                }
                var value_price = (typeof values !== 'undefined') ? values[0] : '';
                var value_label = (typeof values !== 'undefined') ? values[1] : '';
                this.output += '<div><input type="number" step="0.01" class="form-field-pricing-fields price" value="' + value_price + '" /><input type="text" class="form-field-pricing-fields value" value="' + value_label + '" /><input type="hidden" data-language="' + language + '" data-key="' + key + '" name="form_fields[' + i + '][values][' + language + '][' + key + '][]" value="' + value + '" /><a href="#" class="form-field-option-field-remove"><i class="wpbs-icon-close"></i></a></div>';
            }.bind(this))
        }

        this.output += '</div>';

        this.output += '<a href="#" class="form-field-add-option button button-secondary">Add Option</a>';

        this.output += '</div>';

        this.output += '</div>';
    }

    field_option_type_date_range(key, label, language, i) {
        var value = (typeof this.form_data[i]['values'][language] !== 'undefined' && typeof this.form_data[i]['values'][language][key] !== 'undefined') ? this.form_data[i]['values'][language][key] : '';

        var start_date = '', end_date = '', split_value;

        if (value) {
            split_value = value.split('|');
            start_date = split_value[0];
            end_date = split_value[1];
        }


        this.output += '<label for="form-field-' + i + '-' + key + '-' + language + '">' + label + '</label>';
        this.output += '<input class="form-field-option-datepicker form-field-option-date-range-start" type="text" value="' + start_date + '" placeholder="Start date" />';
        this.output += '<input class="form-field-option-datepicker form-field-option-date-range-end" type="text" value="' + end_date + '" placeholder="End date" />';
        this.output += '<input class="form-field-option-date-range" id="form-field-' + i + '-' + key + '-' + language + '" type="hidden" data-language="' + language + '" data-key="' + key + '" value="' + value + '" name="form_fields[' + i + '][values][' + language + '][' + key + ']" />';

    }

    datepickers() {
        jQuery(".form-field-option-datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            firstDay: wpbs_datepicker_week_start,
            beforeShow: function () {
                jQuery('#ui-datepicker-div').addClass('wpbs-datepicker');
            },
            onClose: function (value, object) {
                jQuery('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                var $parent = jQuery('#' + object.id).parents('.form-field-row');
                $parent.find('.form-field-option-date-range').val($parent.find('.form-field-option-date-range-start').val() + '|' + $parent.find('.form-field-option-date-range-end').val())
                $parent.find('.form-field-option-date-range').trigger('change');
            },

        }).keyup(function (e) {
            if (e.keyCode == 8 || e.keyCode == 46) {
                $.datepicker._clearDate(this);
            }
        });;

        // Conditional logic fields
        jQuery('.conditional-logic-rule').each(function () {
            if (jQuery(this).find('.form-field-row-type-conditional-logic-rule-field select').val() == 'start_date' || jQuery(this).find('.form-field-row-type-conditional-logic-rule-field select').val() == 'end_date') {
                jQuery(this).find(".form-field-row-type-conditional-logic-rule-value input").datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    firstDay: wpbs_datepicker_week_start,
                    beforeShow: function () {
                        jQuery('#ui-datepicker-div').addClass('wpbs-datepicker');
                    },
                    onClose: function (value, object) {
                        jQuery('#ui-datepicker-div').hide().removeClass('wpbs-datepicker');
                    },
                }).keyup(function (e) {
                    if (e.keyCode == 8 || e.keyCode == 46) {
                        $.datepicker._clearDate(this);
                    }
                });;

                jQuery(this).find('.form-field-row-type-conditional-logic-rule-condition option[value="contains"], .form-field-row-type-conditional-logic-rule-condition option[value="starts"], .form-field-row-type-conditional-logic-rule-condition option[value="ends"]').prop('disabled', true);
                jQuery(this).find(".form-field-row-type-conditional-logic-rule-value input").prop('readonly', true);
            }

            if (jQuery(this).find('.form-field-row-type-conditional-logic-rule-field select').val() == 'start_weekday' || jQuery(this).find('.form-field-row-type-conditional-logic-rule-field select').val() == 'end_weekday') {
                jQuery(this).find('.form-field-row-type-conditional-logic-rule-value input').hide();
                jQuery(this).find('.form-field-row-type-conditional-logic-rule-value select').show();
            }
        })
    }

    /**
     * Render the form
     */
    render() {
        this.build();
        this.wrapper.innerHTML = this.output;
        this.datepickers()
    }
}