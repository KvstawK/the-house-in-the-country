import React from 'react';
import Select from 'react-select';

const { ServerSideRender, PanelBody, SelectControl }  = wp.components;

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

// The options for the Months to Display dropdown
var months_to_display = [];

for( var i = 1; i <= 12; i++ ) {

    months_to_display.push( { value : i, label : i } );

}

// The options for the Start Year dropdown
var start_year   = [];
var current_date = new Date();

start_year[0] = { value : 0, label : __( 'Current Year', 'wp-booking-system' ) };

for( var i = current_date.getFullYear(); i <= current_date.getFullYear() + 10; i++ ) {

    start_year.push( { value : i, label : i } );

}

// The options for the Start Month dropdown
var start_month = [];
var month_names  = [ 
    __( 'January', 'wp-booking-system' ),
    __( 'February', 'wp-booking-system' ),
    __( 'March', 'wp-booking-system' ),
    __( 'April', 'wp-booking-system' ),
    __( 'May', 'wp-booking-system' ),
    __( 'June', 'wp-booking-system' ),
    __( 'July', 'wp-booking-system' ),
    __( 'August', 'wp-booking-system' ),
    __( 'September', 'wp-booking-system' ),
    __( 'October', 'wp-booking-system' ),
    __( 'November', 'wp-booking-system' ),
    __( 'December', 'wp-booking-system')
];

start_month[0] = { value : 0, label : __( 'Current Month', 'wp-booking-system' ) };

for( var i = 1; i <= 12; i++ ) {

    start_month.push( { value : i, label : month_names[i-1] } );

}

// The option for the Language dropdown
var languages = [];

languages[0] = { value : 'auto', label : __( 'Auto', 'wp-booking-system' ) };

for( var i = 0; i < wpbs_languages.length; i++ ) {

    languages.push( { value : wpbs_languages[i].code, label : wpbs_languages[i].name } );

}

// Register the block
registerBlockType( 'wp-booking-system/overview-calendar', {

	// The block's title
    title : 'Multiple Overview Calendar',

    // The block's icon
    icon : 'calendar',

    // The block category the block should be added to
    category : 'wp-booking-system',

    // The block's attributes, needed to save the data
	attributes : {

		calendars : {
            type : 'string',
            default : null
        },

        legend : {
            type : 'string'
        },

        legend_position : {
            type : 'string'
        },

        start_year : {
            type : 'string'
        },

        start_month : {
            type : 'string'
        },

        history : {
            type : 'string'
        },

        tooltip : {
            type : 'string'
        },

        weeknumbers : {
            type    : 'string',
            default : 'no'
        },

        language : {
            type    : 'string',
            default : 'auto'
        }

	},

	edit : function( props ) {

		const selected = ( typeof props.attributes.calendars != 'undefined' ? JSON.parse( props.attributes.calendars ) : {} )
    	const handleSelectChange = ( calendars ) => props.setAttributes( { calendars: JSON.stringify( calendars ) } );

		return [

			<ServerSideRender 
				block 	   = "wp-booking-system/overview-calendar"
				attributes = { props.attributes } />,

			<InspectorControls key="inspector">

				<PanelBody
					title       = { __( 'Calendars', 'wp-booking-system' ) }
                    initialOpen = { true } >

                    <Select
                    	label    = { __( 'Calendars', 'wp-booking-system' ) }
                        name     = 'select-two'
                        value    = { selected }
                        onChange = { handleSelectChange }
                        options  = { calendars }
						isMulti  = 'true' />

					<p class="description">{ __( 'Select the calendars you wish to display in the overview, or leave empty to show all calendars.', 'wp-booking-system' ) }</p>

                </PanelBody>

				<PanelBody
					title       = { __( 'Calendar Basic Options', 'wp-booking-system' ) }
                    initialOpen = { true } >

					<SelectControl
						label   = { __( 'Display Legend', 'wp-booking-system' ) }
                        value   = { props.attributes.legend }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system' ) },
                            { value : 'no',  label : __( 'No', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { legend : new_value } ) } />

                    <SelectControl
						label   = { __( 'Legend Position', 'wp-booking-system' ) }
                        value   = { props.attributes.legend_position }
                        options = {[
                            { value : 'top', label : __( 'Top', 'wp-booking-system' ) },
                            { value : 'bottom',  label : __( 'Bottom', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { legend_position : new_value } ) } />

                    <SelectControl
						label   = { __( 'Start Year', 'wp-booking-system' ) }
                        value   = { props.attributes.start_year }
                        options = { start_year }
                        onChange = { (new_value) => props.setAttributes( { start_year : new_value } ) } />

                    <SelectControl
						label   = { __( 'Start Month', 'wp-booking-system' ) }
                        value   = { props.attributes.start_month }
                        options = { start_month }
                        onChange = { (new_value) => props.setAttributes( { start_month : new_value } ) } />

				</PanelBody>


				<PanelBody
					title       = { __( 'Calendar Advanced Options', 'wp-booking-system' ) }
                    initialOpen = { true } >

                    <SelectControl
						label   = { __( 'Show History', 'wp-booking-system' ) }
                        value   = { props.attributes.history }
                        options = {[
                            { value : '1', label : __( 'Display booking history', 'wp-booking-system' ) },
                            { value : '2', label : __( 'Replace booking history with the default legend item', 'wp-booking-system' ) },
                            { value : '3', label : __( 'Use the Booking History Color from the Settings', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { history : new_value } ) } />

                    <SelectControl
						label   = { __( 'Display Tooltips', 'wp-booking-system' ) }
                        value   = { props.attributes.tooltip }
                        options = {[
                            { value : '1', label : __( 'No', 'wp-booking-system' ) },
                            { value : '2', label : __( 'Yes', 'wp-booking-system' ) },
                            { value : '3', label : __( 'Yes, with red indicator', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { tooltip : new_value } ) } />

                    <SelectControl
						label   = { __( 'Show Weekday Abbreviations', 'wp-booking-system' ) }
                        value   = { props.attributes.weeknumbers }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system' ) },
                            { value : 'no',  label : __( 'No', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { weeknumbers : new_value } ) } />

                    <SelectControl
						label   = { __( 'Language', 'wp-booking-system' ) }
                        value   = { props.attributes.language }
                        options = { languages }
                        onChange = { (new_value) => props.setAttributes( { language : new_value } ) } />

				</PanelBody>

			</InspectorControls>
		];
	},

	save : function() {
		return null;
	}

});


jQuery( function($) {

	/**
	 * Runs every 250 milliseconds to check if a calendar was just loaded
	 * and if it was, trigger the window resize to show it
	 *
	 */
	setInterval( function() {

	    $('.wpbs-overview-container-loaded').each( function() {

	        if( $(this).attr( 'data-just-loaded' ) == '1' ) {
	            $(window).trigger( 'resize' );
	            $(this).attr( 'data-just-loaded', '0' );
	        }

	    });

	}, 250 );

});