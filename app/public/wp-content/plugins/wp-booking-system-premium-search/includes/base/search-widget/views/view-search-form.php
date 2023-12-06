<?php
$start_date = '';
if(!is_null($this->start_date) && $start_date = DateTime::createFromFormat('Y-m-d', $this->start_date)){
    $start_date = date_i18n(get_option('date_format'), $start_date->format('U'));
}

$end_date = '';
if(!is_null($this->end_date) && $end_date = DateTime::createFromFormat('Y-m-d', $this->end_date)){
    $end_date = date_i18n(get_option('date_format'), $end_date->format('U'));
}

$additional_fields = wpbs_s_get_additional_search_fields(); 

$fields_count = 1;
if($this->get_search_type() == 'multiple'){
    $fields_count += 1;
}

$fields_count += count($additional_fields);

?>

<?php if($this->args['title'] == 'yes'): ?>
<h2><?php echo $this->get_search_widget_string('widget_title'); ?></h2>
<?php endif; ?>

<form action="<?php echo $this->args['redirect'] ? : '#';?>" method="GET" class="wpbs_s-search-widget-form wpbs_s-search-widget-form-fields-<?php echo $fields_count;?>" autocomplete="off">

    <?php do_action('wpbs_search_form_fields_before', $this) ?>

    <div class="wpbs_s-search-widget-field wpbs-search-widget-field-start-date">
        <label for="wpbs_s-search-widget-datepicker-start-date-<?php echo $this->unique;?>"><?php echo $this->get_search_widget_string($this->get_search_type() == 'multiple' ? 'start_date_label' : 'date_label'); ?></label>
        <input type="text" placeholder="<?php echo $this->get_search_widget_string('start_date_placeholder'); ?>" value="<?php echo $start_date; ?>" id="wpbs_s-search-widget-datepicker-start-date-<?php echo $this->unique;?>" class="wpbs_s-search-widget-datepicker wpbs_s-search-widget-datepicker-start-date" name="start-date" readonly />
        <input type="hidden" value="<?php echo (!is_null($this->start_date)) ? $this->start_date : '';?>" id="wpbs_s-search-widget-datepicker-standard-format-start-date-<?php echo $this->unique;?>" class="wpbs_s-search-widget-datepicker-standard-format-start-date" name="wpbs-search-start-date" />
    </div>

    <?php if($this->get_search_type() == 'multiple'): ?>

        <div class="wpbs_s-search-widget-field wpbs-search-widget-field-end-date">
            <label for="wpbs_s-search-widget-datepicker-end-date-<?php echo $this->unique;?>"><?php echo $this->get_search_widget_string('end_date_label'); ?></label>
            <input type="text" placeholder="<?php echo $this->get_search_widget_string('end_date_placeholder'); ?>" value="<?php echo $end_date; ?>" id="wpbs_s-search-widget-datepicker-end-date-<?php echo $this->unique;?>" class="wpbs_s-search-widget-datepicker wpbs_s-search-widget-datepicker-end-date" name="end-date" readonly />
            <input type="hidden" value="<?php echo (!is_null($this->end_date)) ? $this->end_date : '';?>" id="wpbs_s-search-widget-datepicker-standard-format-end-date-<?php echo $this->unique;?>" class="wpbs_s-search-widget-datepicker-standard-format-end-date" name="wpbs-search-end-date" />
        </div>

    <?php endif; ?>

    <?php foreach($additional_fields as $field):?>

        

        <div class="wpbs_s-search-widget-field wpbs_s-search-widget-additional-field <?php echo $field['required'] ? 'wpbs-search-widget-field-required' : '';?>" wpbs-search-widget-field-<?php echo $field['slug'];?>">
            <label for="wpbs_s-search-widget-field-<?php echo $field['slug'];?>-<?php echo $this->unique;?>"><?php echo $field['name'] ?></label>

            <?php if($field['type'] == 'text'): ?>
                <input type="text" placeholder="<?php echo esc_attr($field['placeholder']); ?>" value="<?php echo isset($this->additional_data[$field['slug']]) ? esc_attr($this->additional_data[$field['slug']]) : ''; ?>" id="wpbs_s-search-widget-field-<?php echo $field['slug'];?>-<?php echo $this->unique;?>" class="wpbs_s-search-widget-field-<?php echo $field['slug'];?>" name="<?php echo sanitize_title($field['slug']);?>" />
            <?php elseif($field['type'] == 'dropdown'): ?>
                <span class="wpbs_s-search-widget-field-select-wrap">
                    <select name="<?php echo sanitize_title($field['slug']);?>" class="wpbs_s-search-widget-field-<?php echo $field['slug'];?>" >
                        <?php $value = isset($this->additional_data[$field['slug']]) ? esc_attr($this->additional_data[$field['slug']]) : ''; ?>
                        <?php if($field['placeholder']): ?>
                            <option value="" <?php echo !$value ? 'selected' : '';?>><?php echo sanitize_text_field($field['placeholder']);?></option>
                        <?php endif ?>
                        <?php foreach($field['values'] as $field_key => $field_value):?>
                            <option value="<?php echo esc_attr($field_key);?>" <?php echo $value == esc_attr($field_value) ? 'selected' : '';?>><?php echo sanitize_text_field($field_value);?></option>
                        <?php endforeach; ?>
                    </select>
                </span>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

    <div class="wpbs_s-search-widget-field wpbs_s-search-widget-field-submit">
        <button class="wpbs_s-search-widget-datepicker-submit"><?php echo $this->get_search_widget_string('search_button_label'); ?></button>
    </div>

</form>
