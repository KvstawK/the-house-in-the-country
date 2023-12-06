<div class="rc-datepicker rc-datepicker-check-out">
	<div id="datepickerCheckOut" class="rc-datepicker check-out">
		<label for="departure" class="paragraph-first-line"><?php esc_html_e('Check-out', 'rc-rentals'); ?></label>
		<input role="combobox" type="text" id="departure" class="departure" name="departure" aria-autocomplete="none" aria-expanded="false" aria-haspopup="dialog" aria-controls="rc-datepicker-check-out-modal" readonly="readonly" aria-label="<?php esc_attr_e('Choose check-out date', 'rc-rentals'); ?>" required placeholder="<?php esc_attr_e('Check-out', 'rc-rentals'); ?>"><div class="rc-calendar-icon" aria-hidden="true"><?php echo wp_get_attachment_image(2218, 'full') ?></div>
		<div role="dialog" aria-modal="true" class="rc-datepicker-check-out-modal" aria-label="<?php esc_attr_e('Choose check-out date', 'rc-rentals'); ?>">
			<div class="rc-datepicker-header">
				<button type="button" class="prev-month" aria-label="<?php esc_attr_e('Previous month', 'rc-rentals'); ?>"><?php echo wp_get_attachment_image(169, 'full') ?></button>
				<div id="grid-label" class="month-year" aria-live="polite"></div>
				<button type="button" class="next-month" aria-label="<?php esc_attr_e('Next month', 'rc-rentals'); ?>"><?php echo wp_get_attachment_image(42, 'full') ?></button>
			</div>
			<div class="rc-datepicker-table">
				<table role="grid" class="rc-datepicker-table-dates">
					<thead>
					<tr>
						<th scope="col" abbr="<?php esc_attr_e('Sunday', 'rc-rentals'); ?>"><?php echo esc_html('Su') ?></th>
						<th scope="col" abbr="<?php esc_attr_e('Monday', 'rc-rentals'); ?>"><?php echo esc_html('Mo') ?></th>
						<th scope="col" abbr="<?php esc_attr_e('Tuesday', 'rc-rentals'); ?>"><?php echo esc_html('Tu') ?></th>
						<th scope="col" abbr="<?php esc_attr_e('Wednesday', 'rc-rentals'); ?>"><?php echo esc_html('We') ?></th>
						<th scope="col" abbr="<?php esc_attr_e('Thursday', 'rc-rentals'); ?>"><?php echo esc_html('Th') ?></th>
						<th scope="col" abbr="<?php esc_attr_e('Friday', 'rc-rentals'); ?>"><?php echo esc_html('Fr') ?></th>
						<th scope="col" abbr="<?php esc_attr_e('Saturday', 'rc-rentals'); ?>"><?php echo esc_html('Sa') ?></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td class="disabled" tabindex="0"></td>
						<td class="disabled" tabindex="0"></td>
						<td class="disabled" tabindex="0"></td>
						<td class="disabled" tabindex="0"></td>
						<td class="disabled" tabindex="0"></td>
						<td class="disabled" tabindex="0"></td>
						<td tabindex="0" data-date="2020-02-01"><?php echo esc_html('1') ?></td>
					</tr>
					<tr>
						<td tabindex="0" data-date="2020-02-02"><?php echo esc_html('2') ?></td>
						<td tabindex="0" data-date="2020-02-03"><?php echo esc_html('3') ?></td>
						<td tabindex="0" data-date="2020-02-04"><?php echo esc_html('4') ?></td>
						<td tabindex="0" data-date="2020-02-05"><?php echo esc_html('5') ?></td>
						<td tabindex="0" data-date="2020-02-06"><?php echo esc_html('6') ?></td>
						<td tabindex="0" data-date="2020-02-07"><?php echo esc_html('7') ?></td>
						<td tabindex="0" data-date="2020-02-08"><?php echo esc_html('8') ?></td>
					</tr>
					<tr>
						<td tabindex="0" data-date="2020-02-02"><?php echo esc_html('9') ?></td>
						<td tabindex="0" data-date="2020-02-03"><?php echo esc_html('10') ?></td>
						<td tabindex="0" data-date="2020-02-04"><?php echo esc_html('11') ?></td>
						<td tabindex="0" data-date="2020-02-05"><?php echo esc_html('12') ?></td>
						<td tabindex="0" data-date="2020-02-06"><?php echo esc_html('13') ?></td>
						<td tabindex="0" data-date="2020-02-07"><?php echo esc_html('14') ?></td>
						<td tabindex="0" data-date="2020-02-08"><?php echo esc_html('15') ?></td>
					</tr>
					<tr>
						<td tabindex="0" data-date="2020-02-02"><?php echo esc_html('16') ?></td>
						<td tabindex="0" data-date="2020-02-03"><?php echo esc_html('17') ?></td>
						<td tabindex="0" data-date="2020-02-04"><?php echo esc_html('18') ?></td>
						<td tabindex="0" data-date="2020-02-05"><?php echo esc_html('19') ?></td>
						<td role="gridcell" aria-selected="true" tabindex="0" data-date="2020-02-06"><?php echo esc_html('20') ?></td>
						<td tabindex="0" data-date="2020-02-07"><?php echo esc_html('21') ?></td>
						<td tabindex="0" data-date="2020-02-08"><?php echo esc_html('22') ?></td>
					</tr>
					<tr>
						<td tabindex="0" data-date="2020-02-02"><?php echo esc_html('23') ?></td>
						<td tabindex="0" data-date="2020-02-03"><?php echo esc_html('24') ?></td>
						<td tabindex="0" data-date="2020-02-04"><?php echo esc_html('25') ?></td>
						<td tabindex="0" data-date="2020-02-05"><?php echo esc_html('26') ?></td>
						<td tabindex="0" data-date="2020-02-06"><?php echo esc_html('27') ?></td>
						<td tabindex="0" data-date="2020-02-07"><?php echo esc_html('28') ?></td>
						<td tabindex="0" data-date="2020-02-08"><?php echo esc_html('29') ?></td>
					</tr>
					<tr>
						<td tabindex="0" data-date="2020-02-02"><?php echo esc_html('30') ?></td>
						<td tabindex="0" data-date="2020-02-03"><?php echo esc_html('31') ?></td>
						<td class="disabled" tabindex="0"></td>
						<td class="disabled" tabindex="0"></td>
						<td class="disabled" tabindex="0"></td>
						<td class="disabled" tabindex="0"></td>
						<td class="disabled" tabindex="0"></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>