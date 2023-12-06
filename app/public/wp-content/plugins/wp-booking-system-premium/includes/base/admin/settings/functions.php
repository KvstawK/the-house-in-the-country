<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Includes the files needed for the Settings admin area
 *
 */
function wpbs_include_files_admin_settings()
{

    // Get legend admin dir path
    $dir_path = plugin_dir_path(__FILE__);

    // Include submenu page
    if (file_exists($dir_path . 'class-submenu-page-settings.php')) {
        include $dir_path . 'class-submenu-page-settings.php';
    }

}
add_action('wpbs_include_files', 'wpbs_include_files_admin_settings');

/**
 * Register the Settings admin submenu page
 *
 */
function wpbs_register_submenu_page_settings($submenu_pages)
{

    if (!is_array($submenu_pages)) {
        return $submenu_pages;
    }

    $submenu_pages['settings'] = array(
        'class_name' => 'WPBS_Submenu_Page_Settings',
        'data' => array(
            'page_title' => __('Settings', 'wp-booking-system'),
            'menu_title' => __('Settings', 'wp-booking-system'),
            'capability' => apply_filters('wpbs_submenu_page_capability_settings', 'manage_options'),
            'menu_slug' => 'wpbs-settings',
        ),
    );

    return $submenu_pages;

}
add_filter('wpbs_register_submenu_page', 'wpbs_register_submenu_page_settings', 50);

/**
 * List of countries for the Phone Number input
 *
 */
function wpbs_intl_tel_input_countries_list()
{
    return array("us" => "United States", "gb" => "United Kingdom", "af" => "Afghanistan (&#x202B;افغانستان&#x202C;&lrm;)", "al" => "Albania (Shqipëri)", "dz" => "Algeria (&#x202B;الجزائر&#x202C;&lrm;)", "as" => "American Samoa", "ad" => "Andorra", "ao" => "Angola", "ai" => "Anguilla", "ag" => "Antigua and Barbuda", "ar" => "Argentina", "am" => "Armenia (Հայաստան)", "aw" => "Aruba", "ac" => "Ascension Island", "au" => "Australia", "at" => "Austria (Österreich)", "az" => "Azerbaijan (Azərbaycan)", "bs" => "Bahamas", "bh" => "Bahrain (&#x202B;البحرين&#x202C;&lrm;)", "bd" => "Bangladesh (বাংলাদেশ)", "bb" => "Barbados", "by" => "Belarus (Беларусь)", "be" => "Belgium (België)", "bz" => "Belize", "bj" => "Benin (Bénin)", "bm" => "Bermuda", "bt" => "Bhutan (འབྲུག)", "bo" => "Bolivia", "ba" => "Bosnia and Herzegovina (Босна и Херцеговина)", "bw" => "Botswana", "br" => "Brazil (Brasil)", "io" => "British Indian Ocean Territory", "vg" => "British Virgin Islands", "bn" => "Brunei", "bg" => "Bulgaria (България)", "bf" => "Burkina Faso", "bi" => "Burundi (Uburundi)", "kh" => "Cambodia (កម្ពុជា)", "cm" => "Cameroon (Cameroun)", "ca" => "Canada", "cv" => "Cape Verde (Kabu Verdi)", "bq" => "Caribbean Netherlands", "ky" => "Cayman Islands", "cf" => "Central African Republic (République centrafricaine)", "td" => "Chad (Tchad)", "cl" => "Chile", "cn" => "China (中国)", "cx" => "Christmas Island", "cc" => "Cocos (Keeling) Islands", "co" => "Colombia", "km" => "Comoros (&#x202B;جزر القمر&#x202C;&lrm;)", "cd" => "Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)", "cg" => "Congo (Republic) (Congo-Brazzaville)", "ck" => "Cook Islands", "cr" => "Costa Rica", "ci" => "Côte d’Ivoire", "hr" => "Croatia (Hrvatska)", "cu" => "Cuba", "cw" => "Curaçao", "cy" => "Cyprus (Κύπρος)", "cz" => "Czech Republic (Česká republika)", "dk" => "Denmark (Danmark)", "dj" => "Djibouti", "dm" => "Dominica", "do" => "Dominican Republic (República Dominicana)", "ec" => "Ecuador", "eg" => "Egypt (&#x202B;مصر&#x202C;&lrm;)", "sv" => "El Salvador", "gq" => "Equatorial Guinea (Guinea Ecuatorial)", "er" => "Eritrea", "ee" => "Estonia (Eesti)", "sz" => "Eswatini", "et" => "Ethiopia", "fk" => "Falkland Islands (Islas Malvinas)", "fo" => "Faroe Islands (Føroyar)", "fj" => "Fiji", "fi" => "Finland (Suomi)", "fr" => "France", "gf" => "French Guiana (Guyane française)", "pf" => "French Polynesia (Polynésie française)", "ga" => "Gabon", "gm" => "Gambia", "ge" => "Georgia (საქართველო)", "de" => "Germany (Deutschland)", "gh" => "Ghana (Gaana)", "gi" => "Gibraltar", "gr" => "Greece (Ελλάδα)", "gl" => "Greenland (Kalaallit Nunaat)", "gd" => "Grenada", "gp" => "Guadeloupe", "gu" => "Guam", "gt" => "Guatemala", "gg" => "Guernsey", "gn" => "Guinea (Guinée)", "gw" => "Guinea-Bissau (Guiné Bissau)", "gy" => "Guyana", "ht" => "Haiti", "hn" => "Honduras", "hk" => "Hong Kong (香港)", "hu" => "Hungary (Magyarország)", "is" => "Iceland (Ísland)", "in" => "India (भारत)", "id" => "Indonesia", "ir" => "Iran (&#x202B;ایران&#x202C;&lrm;)", "iq" => "Iraq (&#x202B;العراق&#x202C;&lrm;)", "ie" => "Ireland", "im" => "Isle of Man", "il" => "Israel (&#x202B;ישראל&#x202C;&lrm;)", "it" => "Italy (Italia)", "jm" => "Jamaica", "jp" => "Japan (日本)", "je" => "Jersey", "jo" => "Jordan (&#x202B;الأردن&#x202C;&lrm;)", "kz" => "Kazakhstan (Казахстан)", "ke" => "Kenya", "ki" => "Kiribati", "xk" => "Kosovo", "kw" => "Kuwait (&#x202B;الكويت&#x202C;&lrm;)", "kg" => "Kyrgyzstan (Кыргызстан)", "la" => "Laos (ລາວ)", "lv" => "Latvia (Latvija)", "lb" => "Lebanon (&#x202B;لبنان&#x202C;&lrm;)", "ls" => "Lesotho", "lr" => "Liberia", "ly" => "Libya (&#x202B;ليبيا&#x202C;&lrm;)", "li" => "Liechtenstein", "lt" => "Lithuania (Lietuva)", "lu" => "Luxembourg", "mo" => "Macau (澳門)", "mk" => "North Macedonia (Македонија)", "mg" => "Madagascar (Madagasikara)", "mw" => "Malawi", "my" => "Malaysia", "mv" => "Maldives", "ml" => "Mali", "mt" => "Malta", "mh" => "Marshall Islands", "mq" => "Martinique", "mr" => "Mauritania (&#x202B;موريتانيا&#x202C;&lrm;)", "mu" => "Mauritius (Moris)", "yt" => "Mayotte", "mx" => "Mexico (México)", "fm" => "Micronesia", "md" => "Moldova (Republica Moldova)", "mc" => "Monaco", "mn" => "Mongolia (Монгол)", "me" => "Montenegro (Crna Gora)", "ms" => "Montserrat", "ma" => "Morocco (&#x202B;المغرب&#x202C;&lrm;)", "mz" => "Mozambique (Moçambique)", "mm" => "Myanmar (Burma) (မြန်မာ)", "na" => "Namibia (Namibië)", "nr" => "Nauru", "np" => "Nepal (नेपाल)", "nl" => "Netherlands (Nederland)", "nc" => "New Caledonia (Nouvelle-Calédonie)", "nz" => "New Zealand", "ni" => "Nicaragua", "ne" => "Niger (Nijar)", "ng" => "Nigeria", "nu" => "Niue", "nf" => "Norfolk Island", "kp" => "North Korea (조선 민주주의 인민 공화국)", "mp" => "Northern Mariana Islands", "no" => "Norway (Norge)", "om" => "Oman (&#x202B;عُمان&#x202C;&lrm;)", "pk" => "Pakistan (&#x202B;پاکستان&#x202C;&lrm;)", "pw" => "Palau", "ps" => "Palestine (&#x202B;فلسطين&#x202C;&lrm;)", "pa" => "Panama (Panamá)", "pg" => "Papua New Guinea", "py" => "Paraguay", "pe" => "Peru (Perú)", "ph" => "Philippines", "pl" => "Poland (Polska)", "pt" => "Portugal", "pr" => "Puerto Rico", "qa" => "Qatar (&#x202B;قطر&#x202C;&lrm;)", "re" => "Réunion (La Réunion)", "ro" => "Romania (România)", "ru" => "Russia (Россия)", "rw" => "Rwanda", "bl" => "Saint Barthélemy", "sh" => "Saint Helena", "kn" => "Saint Kitts and Nevis", "lc" => "Saint Lucia", "mf" => "Saint Martin (Saint-Martin (partie française))", "pm" => "Saint Pierre and Miquelon (Saint-Pierre-et-Miquelon)", "vc" => "Saint Vincent and the Grenadines", "ws" => "Samoa", "sm" => "San Marino", "st" => "São Tomé and Príncipe (São Tomé e Príncipe)", "sa" => "Saudi Arabia (&#x202B;المملكة العربية السعودية&#x202C;&lrm;)", "sn" => "Senegal (Sénégal)", "rs" => "Serbia (Србија)", "sc" => "Seychelles", "sl" => "Sierra Leone", "sg" => "Singapore", "sx" => "Sint Maarten", "sk" => "Slovakia (Slovensko)", "si" => "Slovenia (Slovenija)", "sb" => "Solomon Islands", "so" => "Somalia (Soomaaliya)", "za" => "South Africa", "kr" => "South Korea (대한민국)", "ss" => "South Sudan (&#x202B;جنوب السودان&#x202C;&lrm;)", "es" => "Spain (España)", "lk" => "Sri Lanka (ශ්&zwj;රී ලංකාව)", "sd" => "Sudan (&#x202B;السودان&#x202C;&lrm;)", "sr" => "Suriname", "sj" => "Svalbard and Jan Mayen", "se" => "Sweden (Sverige)", "ch" => "Switzerland (Schweiz)", "sy" => "Syria (&#x202B;سوريا&#x202C;&lrm;)", "tw" => "Taiwan (台灣)", "tj" => "Tajikistan", "tz" => "Tanzania", "th" => "Thailand (ไทย)", "tl" => "Timor-Leste", "tg" => "Togo", "tk" => "Tokelau", "to" => "Tonga", "tt" => "Trinidad and Tobago", "tn" => "Tunisia (&#x202B;تونس&#x202C;&lrm;)", "tr" => "Turkey (Türkiye)", "tm" => "Turkmenistan", "tc" => "Turks and Caicos Islands", "tv" => "Tuvalu", "vi" => "U.S. Virgin Islands", "ug" => "Uganda", "ua" => "Ukraine (Україна)", "ae" => "United Arab Emirates (&#x202B;الإمارات العربية المتحدة&#x202C;&lrm;)", "gb" => "United Kingdom", "us" => "United States", "uy" => "Uruguay", "uz" => "Uzbekistan (Oʻzbekiston)", "vu" => "Vanuatu", "va" => "Vatican City (Città del Vaticano)", "ve" => "Venezuela", "vn" => "Vietnam (Việt Nam)", "wf" => "Wallis and Futuna (Wallis-et-Futuna)", "eh" => "Western Sahara (&#x202B;الصحراء الغربية&#x202C;&lrm;)", "ye" => "Yemen (&#x202B;اليمن&#x202C;&lrm;)", "zm" => "Zambia", "zw" => "Zimbabwe", "ax" => "Åland Islands");
}

/**
 * Check if we have the Enhanced UI option disabled, and modify the settings array so we don't lose options that are not visible on the page.
 *
 */
function wpbs_enhanced_admin_ui_settings($new_value, $old_value)
{
    if (wpbs_enhanced_admin_ui() === true) {
        return $new_value;
    }
    return array_merge($old_value, $new_value);
}
add_action('pre_update_option_wpbs_settings', 'wpbs_enhanced_admin_ui_settings', 10, 3);
