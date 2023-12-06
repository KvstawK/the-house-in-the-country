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

calendars[0] = { value : 0, label : __( 'Select Calendar...', 'wp-booking-system' ) };

for( var i = 0; i < wpbs_calendars.length; i++ ) {

    var calendar_name = jQuery('<textarea />').html(wpbs_calendars[i].name).text();
    calendars.push({ value: wpbs_calendars[i].id, label : calendar_name } );

}

// The options for the Calendars dropdown
var forms = [];

forms[0] = { value : 0, label : __( 'Select Form...', 'wp-booking-system' ) };

for( var i = 0; i < wpbs_forms.length; i++ ) {

    forms.push( { value : wpbs_forms[i].id, label : wpbs_forms[i].name } );

}

// The options for the Months to Display dropdown
var months_to_display = [];

for( var i = 1; i <= 24; i++ ) {

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
registerBlockType( 'wp-booking-system/single-calendar', {

    // The block's title
    title : 'Single Calendar',

    // The block's icon
    icon : 'calendar-alt',

    // The block category the block should be added to
    category : 'wp-booking-system',

    // The block's attributes, needed to save the data
    attributes : {

        id : {
            type : 'string'
        },

        form_id : {
            type : 'string'
        },

        title : {
            type : 'string'
        },

        legend : {
            type : 'string'
        },

        legend_position : {
            type : 'string'
        },

        display : {
            type : 'string'
        },

        year : {
            type : 'string'
        },

        month : {
            type : 'string'
        },

        start : {
            type : 'string'
        },

        dropdown : {
            type : 'string'
        },

        jump : {
            type : 'string'
        },

        history : {
            type : 'string'
        },

        tooltip : {
            type : 'string'
        },

        highlighttoday : {
            type : 'string'
        },

        weeknumbers : {
            type    : 'string',
            default : 'no'
        },

        show_prices : {
            type : 'string'
        },

        language : {
            type    : 'string',
            default : 'auto'
        },

        auto_pending : {
            type : 'string',
            default : 'yes'
        },

        selection_type : {
            type : 'string',
            default : 'multiple'
        },

        selection_style : {
            type : 'string',
            default : 'split'
        },

        minimum_days : {
            type : 'string'
        },

        maximum_days : {
            type : 'string'
        },

        booking_start_day : {
            type : 'string'
        },

        booking_end_day : {
            type : 'string'
        },

        show_date_selection : {
            type : 'string',
            default : 'no'
        },

        form_position : {
            type : 'string',
            default : 'bottom'
        },

    },

    edit : function( props ) {

        return [

            <ServerSideRender 
                block      = "wp-booking-system/single-calendar"
                attributes = { props.attributes } />,

            <InspectorControls key="inspector">

                <PanelBody
                    title       = { __( 'Calendar', 'wp-booking-system' ) }
                    initialOpen = { true } >

                    <SelectControl
                        value    = { props.attributes.id }
                        options  = { calendars }
                        onChange = { (new_value) => props.setAttributes( { id : new_value } ) } />

                </PanelBody>

                <PanelBody
                    title       = { __( 'Form', 'wp-booking-system' ) }
                    initialOpen = { true } >

                    <SelectControl
                        value    = { props.attributes.form_id }
                        options  = { forms }
                        onChange = { (new_value) => props.setAttributes( { form_id : new_value } ) } />

                </PanelBody>

                <PanelBody
                    title       = { __( 'Calendar Options', 'wp-booking-system' ) }
                    initialOpen = { true } >

                    <SelectControl
                        label   = { __( 'Display Calendar Title', 'wp-booking-system' ) }
                        value   = { props.attributes.title }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system' ) },
                            { value : 'no',  label : __( 'No', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { title : new_value } ) } />

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
                            { value : 'side', label : __( 'Side', 'wp-booking-system' ) },
                            { value : 'top', label : __( 'Top', 'wp-booking-system' ) },
                            { value : 'bottom',  label : __( 'Bottom', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { legend_position : new_value } ) } />

                    <SelectControl
                        label   = { __( 'Months to Display', 'wp-booking-system' ) }
                        value   = { props.attributes.display }
                        options = { months_to_display }
                        onChange = { (new_value) => props.setAttributes( { display : new_value } ) } />

                    <SelectControl
                        label   = { __( 'Start Year', 'wp-booking-system' ) }
                        value   = { props.attributes.year }
                        options = { start_year }
                        onChange = { (new_value) => props.setAttributes( { year : new_value } ) } />

                    <SelectControl
                        label   = { __( 'Start Month', 'wp-booking-system' ) }
                        value   = { props.attributes.month }
                        options = { start_month }
                        onChange = { (new_value) => props.setAttributes( { month : new_value } ) } />

                     <SelectControl
                        label   = { __( 'Display Dropdown', 'wp-booking-system' ) }
                        value   = { props.attributes.dropdown }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system' ) },
                            { value : 'no',  label : __( 'No', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { dropdown : new_value } ) } />
                    
                    <SelectControl
                        label   = { __( 'Week Start Day', 'wp-booking-system' ) }
                        value   = { props.attributes.start }
                        options = {[
                            { value : '1', label : __( 'Monday', 'wp-booking-system' ) },
                            { value : '2', label : __( 'Tuesday', 'wp-booking-system' ) },
                            { value : '3', label : __( 'Wednesday', 'wp-booking-system' ) },
                            { value : '4', label : __( 'Thursday', 'wp-booking-system' ) },
                            { value : '5', label : __( 'Friday', 'wp-booking-system' ) },
                            { value : '6', label : __( 'Saturday', 'wp-booking-system' ) },
                            { value : '7', label : __( 'Sunday', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { start : new_value } ) } />

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
                        label   = { __( 'Show Week Numbers', 'wp-booking-system' ) }
                        value   = { props.attributes.weeknumbers }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system' ) },
                            { value : 'no',  label : __( 'No', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { weeknumbers : new_value } ) } />
                    
                    <SelectControl
                        label   = { __( 'Highlight Today', 'wp-booking-system' ) }
                        value   = { props.attributes.highlighttoday }
                        options = {[
                            { value : 'no',  label : __( 'No', 'wp-booking-system' ) },
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { highlighttoday : new_value } ) } />
                    
                    <SelectControl
                        label   = { __( 'Show Prices', 'wp-booking-system' ) }
                        value   = { props.attributes.show_prices }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system' ) },
                            { value : 'no',  label : __( 'No', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { show_prices : new_value } ) } />

                    <SelectControl
                        label   = { __( 'Language', 'wp-booking-system' ) }
                        value   = { props.attributes.language }
                        options = { languages }
                        onChange = { (new_value) => props.setAttributes( { language : new_value } ) } />

                </PanelBody>


                <PanelBody
                    title       = { __( 'Form Options', 'wp-booking-system' ) }
                    initialOpen = { true } >

                    <SelectControl
                        label   = { __( 'Form Position', 'wp-booking-system' ) }
                        value   = { props.attributes.form_position }
                        options = {[
                            { value : 'bottom', label : __( 'Bottom', 'wp-booking-system' ) },
                            { value : 'side',  label : __( 'Side', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { form_position : new_value } ) } />

                    <SelectControl
                        label   = { __( 'Auto Accept Bookings', 'wp-booking-system' ) }
                        value   = { props.attributes.auto_pending }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system' ) },
                            { value : 'no',  label : __( 'No', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { auto_pending : new_value } ) } />
                    
                    <SelectControl
                        label   = { __( 'Selection Type', 'wp-booking-system' ) }
                        value   = { props.attributes.selection_type }
                        options = {[
                            { value : 'multiple', label : __( 'Date Range', 'wp-booking-system' ) },
                            { value : 'single',  label : __( 'Single Day', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { selection_type : new_value } ) } />

                    <SelectControl
                        label   = { __( 'Selection Style', 'wp-booking-system' ) }
                        value   = { props.attributes.selection_style }
                        options = {[
                            { value : 'normal', label : __( 'Normal', 'wp-booking-system' ) },
                            { value : 'split',  label : __( 'Split', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { selection_style : new_value } ) } />

                    <TextControl
                        label   = { __( 'Minimum Days', 'wp-booking-system' ) }
                        value   = { props.attributes.minimum_days }
                        onChange = { (new_value) => props.setAttributes( { minimum_days : new_value } ) } />
                    
                    <TextControl
                        label   = { __( 'Maximum Days', 'wp-booking-system' ) }
                        value   = { props.attributes.maximum_days }
                        onChange = { (new_value) => props.setAttributes( { maximum_days : new_value } ) } />

                    <SelectControl
                        label   = { __( 'Booking Start Day', 'wp-booking-system' ) }
                        value   = { props.attributes.booking_start_day }
                        options = {[
                            { value : '0', label : '-' },
                            { value : '1', label : __( 'Monday', 'wp-booking-system' ) },
                            { value : '2', label : __( 'Tuesday', 'wp-booking-system' ) },
                            { value : '3', label : __( 'Wednesday', 'wp-booking-system' ) },
                            { value : '4', label : __( 'Thursday', 'wp-booking-system' ) },
                            { value : '5', label : __( 'Friday', 'wp-booking-system' ) },
                            { value : '6', label : __( 'Saturday', 'wp-booking-system' ) },
                            { value : '7', label : __( 'Sunday', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { booking_start_day : new_value } ) } />
                    
                    <SelectControl
                        label   = { __( 'Booking End Day', 'wp-booking-system' ) }
                        value   = { props.attributes.booking_end_day }
                        options = {[
                            { value : '0', label : '-' },
                            { value : '1', label : __( 'Monday', 'wp-booking-system' ) },
                            { value : '2', label : __( 'Tuesday', 'wp-booking-system' ) },
                            { value : '3', label : __( 'Wednesday', 'wp-booking-system' ) },
                            { value : '4', label : __( 'Thursday', 'wp-booking-system' ) },
                            { value : '5', label : __( 'Friday', 'wp-booking-system' ) },
                            { value : '6', label : __( 'Saturday', 'wp-booking-system' ) },
                            { value : '7', label : __( 'Sunday', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { booking_end_day : new_value } ) } />
                    
                    <SelectControl
                        label   = { __( 'Show Date Selection', 'wp-booking-system' ) }
                        value   = { props.attributes.show_date_selection }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-booking-system' ) },
                            { value : 'no',  label : __( 'No', 'wp-booking-system' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { show_date_selection : new_value } ) } />

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

        $('.wpbs-container-loaded').each( function() {

            if( $(this).attr( 'data-just-loaded' ) == '1' ) {
                $(window).trigger( 'resize' );
                $(this).attr( 'data-just-loaded', '0' );
            }

        });

    }, 250 );

});