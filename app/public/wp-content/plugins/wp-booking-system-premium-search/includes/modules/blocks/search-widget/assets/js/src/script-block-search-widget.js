import React from 'react';
import Select from 'react-select';

const { ServerSideRender, PanelBody, SelectControl, TextControl }  = wp.components;

const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const { __ } 				= wp.i18n;


/**
 * Block inspector controls options
 *
 */

// The options for the Calendars dropdown
var calendars = [];

for( var i = 0; i < wpbs_calendars.length; i++ ) {

    var calendar_name = jQuery('<textarea />').html(wpbs_calendars[i].name).text();
    calendars.push({ value: wpbs_calendars[i].id, label : calendar_name } );

}

// The option for the Language dropdown
var languages = [];

languages[0] = { value : 'auto', label : __( 'Auto', 'wp-booking-system-search') };

for( var i = 0; i < wpbs_languages.length; i++ ) {

    languages.push( { value : wpbs_languages[i].code, label : wpbs_languages[i].name } );

}

// Register the block
registerBlockType( 'wp-booking-system/search-widget', {

	// The block's title
    title : 'Calendar Search Widget',

    // The block's icon
    icon : 'search',

    // The block category the block should be added to
    category : 'wp-booking-system',

    // The block's attributes, needed to save the data
	attributes : {

		calendars : {
            type : 'string',
            default : null
        },
        language : {
            type    : 'string',
            default : 'auto'
        },

		title : {
            type    : 'string',
            default : 'yes'
        },

        mark_selection : {
            type    : 'string',
            default : 'yes'
        },

        start_day : {
            type    : 'string',
            default : 'yes'
        },

        selection_type : {
            type    : 'string',
            default : 'multiple'
        },

        minimum_stay : {
            type    : 'string',
            default : '0'
        },

        featured_image : {
            type    : 'string',
            default : 'no'
        },

        starting_price : {
            type    : 'string',
            default : 'no'
        },

        show_results_on_load : {
            type    : 'string',
            default : 'no'
        },

        results_layout : {
            type    : 'string',
            default : 'list'
        },

        results_per_page : {
            type    : 'string',
            default : '10'
        },

        redirect : {
            type    : 'string',
            default : ''
        }
    

	},

	edit : function( props ) {

		const selected = ( typeof props.attributes.calendars != 'undefined' ? JSON.parse( props.attributes.calendars ) : {} )
    	const handleSelectChange = ( calendars ) => props.setAttributes( { calendars: JSON.stringify( calendars ) } );

		return [

			<ServerSideRender 
				block 	   = "wp-booking-system/search-widget"
				attributes = { props.attributes } />,

			<InspectorControls key="inspector">

				<PanelBody
					title       = { __( 'Calendars', 'wp-booking-system-search') }
                    initialOpen = { true } >

                    <Select
                    	label    = { __( 'Calendars', 'wp-booking-system-search') }
                        name     = 'select-two'
                        value    = { selected }
                        onChange = { handleSelectChange }
                        options  = { calendars }
						isMulti  = 'true' />

					<p class="description">{ __( 'Select the calendars you wish to be included in the search, or leave empty to show all calendars.', 'wp-booking-system-search') }</p>

                    <SelectControl
						label   = { __( 'Language', 'wp-booking-system-search') }
                        value   = { props.attributes.language }
                        options = { languages }
                        onChange = { (new_value) => props.setAttributes( { language : new_value } ) } />
					
					<SelectControl
						label   = { __( 'Widget Title', 'wp-booking-system-search') }
                        value   = { props.attributes.title }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system-search') },
                            { value : 'no',  label : __( 'No', 'wp-booking-system-search') }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { title : new_value } ) } />
                    
                    <SelectControl
                        label   = { __( 'Week Start Day', 'wp-booking-system-search' ) }
                        value   = { props.attributes.start_day }
                        options = {[
                            { value : '1', label : __( 'Monday', 'wp-booking-system-search' ) },
                            { value : '2', label : __( 'Tuesday', 'wp-booking-system-search' ) },
                            { value : '3', label : __( 'Wednesday', 'wp-booking-system-search' ) },
                            { value : '4', label : __( 'Thursday', 'wp-booking-system-search' ) },
                            { value : '5', label : __( 'Friday', 'wp-booking-system-search' ) },
                            { value : '6', label : __( 'Saturday', 'wp-booking-system-search' ) },
                            { value : '7', label : __( 'Sunday', 'wp-booking-system-search' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { start_day : new_value } ) } />
					
					<SelectControl
						label   = { __( 'Automatically Mark Selection', 'wp-booking-system-search') }
                        value   = { props.attributes.mark_selection }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system-search') },
                            { value : 'no',  label : __( 'No', 'wp-booking-system-search') }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { mark_selection : new_value } ) } />

                    <SelectControl
						label   = { __( 'Selection Type', 'wp-booking-system-search') }
                        value   = { props.attributes.selection_type }
                        options = {[
                            { value : 'multiple', label : __( 'Date Range', 'wp-booking-system-search') },
                            { value : 'single',  label : __( 'Single Day', 'wp-booking-system-search') }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { selection_type : new_value } ) } />
                    
                    <TextControl
						label   = { __( 'Minimum Stay', 'wp-booking-system-search') }
                        value   = { props.attributes.minimum_stay }
                        onChange = { (new_value) => props.setAttributes( { minimum_stay : new_value } ) } />
                    
                    <SelectControl
						label   = { __( 'Show Featured Image', 'wp-booking-system-search') }
                        value   = { props.attributes.featured_image }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system-search') },
                            { value : 'no',  label : __( 'No', 'wp-booking-system-search') }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { featured_image : new_value } ) } />
                    
                    <SelectControl
						label   = { __( 'Show Starting Price', 'wp-booking-system-search') }
                        value   = { props.attributes.starting_price }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system-search') },
                            { value : 'no',  label : __( 'No', 'wp-booking-system-search') }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { starting_price : new_value } ) } />
                    
                    <SelectControl
						label   = { __( 'Show Resutls on Load', 'wp-booking-system-search') }
                        value   = { props.attributes.show_results_on_load }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system-search') },
                            { value : 'no',  label : __( 'No', 'wp-booking-system-search') }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { show_results_on_load : new_value } ) } />
                    
                    <SelectControl
						label   = { __( 'Results Layout', 'wp-booking-system-search') }
                        value   = { props.attributes.results_layout }
                        options = {[
                            { value : 'list', label : __( 'List', 'wp-booking-system-search') },
                            { value : 'grid',  label : __( 'Grid', 'wp-booking-system-search') }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { results_layout : new_value } ) } />
                    
                    <TextControl
						label   = { __( 'Results per Page', 'wp-booking-system-search') }
                        value   = { props.attributes.results_per_page }
                        onChange = { (new_value) => props.setAttributes( { results_per_page : new_value } ) } />

                    <TextControl
						label   = { __( 'Redirect', 'wp-booking-system-search') }
                        value   = { props.attributes.redirect }
                        onChange = { (new_value) => props.setAttributes( { redirect : new_value } ) } />

				</PanelBody>

			</InspectorControls>
		];
	},

	save : function() {
		return null;
	}

});


jQuery(function ($) {

	/**
	 * Runs every 250 milliseconds to check if a calendar was just loaded
	 * and if it was, trigger the window resize to show it
	 *
	 */
	setInterval(function () {

        jQuery('.wpbs-search-container-loaded').each(function () {
            if (jQuery(this).attr('data-just-loaded') == '1') {
                jQuery(window).trigger('resize');
                jQuery(this).attr('data-just-loaded', '0');
            }
        });
		
	}, 250);
});