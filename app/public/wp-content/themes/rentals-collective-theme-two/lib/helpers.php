<?php

// Allow to add svg in the WordPress Library
add_filter(
    'wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
        $filetype = wp_check_filetype($filename, $mimes);
        return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
        ];

    }, 10, 4
);

function cc_mime_types( $mimes )
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function fix_svg()
{
    echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action('admin_head', 'fix_svg');


// Sanitize SVG's
function sanitize_svg($file) {
	$file_type = wp_check_filetype($file['tmp_name']);

	if ($file_type['type'] === 'image/svg+xml') {
		$svg_content = file_get_contents($file['tmp_name']);
		$sanitized_svg = preg_replace('/<script.*?\/script>/is', '', $svg_content);
		$sanitized_svg = preg_replace('/<style.*?\/style>/is', '', $sanitized_svg);
		$sanitized_svg = preg_replace('/ on\w+=".*?"/is', '', $sanitized_svg);

		file_put_contents($file['tmp_name'], $sanitized_svg);
	}

	return $file;
}
add_filter('wp_handle_upload_prefilter', 'sanitize_svg');


// Viber links protocol, needed for esc_url Viber links
add_filter(
    'kses_allowed_protocols',
    function ( $protocols ) {
        $protocols[] = 'viber';
        return $protocols;
    }
);


// Read More for blog posts link
function rentals_collective_theme_two_readmore_link()
{
    echo '<a href="' . esc_url(get_permalink()) . '" title="' . the_title_attribute(['echo' => false]) . '"><button class="btn">';
    /* translators: %s: Post Title */
    printf(
        wp_kses(
            __('Read More <span class="screen-reader-text">About %s</span>', 'rentals_collective_theme_two'),
            [
                'span' => [
                    'class' => []
                ]
            ]
        ),
        get_the_title()
    );
    echo wp_get_attachment_image(45, 'full') . '</button></a>';
}


// meta for single blog post
if(!function_exists('rentals_collective_theme_two_post_meta')) {
    function rentals_collective_theme_two_post_meta()
    {
        /* translators: %s: Post Date */
        printf(
            esc_html__('Posted on %s', 'rentals_collective_theme_two'),
            '<div><time datetime="' . esc_attr(get_the_date('c')) . '">' .  esc_html(get_the_date()) . '</time></div>'
        );
        /* translators: %s: Post Author */
        printf(
            esc_html__(' By: %s', 'rentals_collective_theme_two'),
            '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>'
        );
    }
}


// Only show blog posts from search widget
function SearchFilter($query)
{
    if (($query->is_search)&&(!is_admin())) {
        $query->set('post_type', 'post');
    }
    return $query;
}

add_filter('pre_get_posts', 'SearchFilter');


// Modify archive.php, get rid of the “Category:”, “Tag:”, “Author:”, “Archives:” and “Other taxonomy name:” in the archive title.
function rentals_collective_theme_two_archive_title( $title )
{
    if (is_category() ) {
        $title = single_cat_title('', false);
    } elseif (is_tag() ) {
        $title = single_tag_title('', false);
    } elseif (is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_post_type_archive() ) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax() ) {
        $title = single_term_title('', false);
    }

    return $title;
}

add_filter('get_the_archive_title', 'rentals_collective_theme_two_archive_title');


// Only show comments from the blog in recent comments widget
function rentals_collective_theme_two_comments_widget($comment_args)
{
    $comment_args['post_type'] = 'post';
    return $comment_args;
}

add_filter('widget_comments_args', 'rentals_collective_theme_two_comments_widget');


// Validate if user has a Gravatar
function validate_gravatar($email)
{
    // Craft a potential url and test its headers
    $hash = md5(strtolower(trim($email)));
    $uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
    $headers = @get_headers($uri);
    if (!preg_match("|200|", $headers[0])) {
        $has_valid_avatar = false;
    } else {
        $has_valid_avatar = true;
    }
    return $has_valid_avatar;
}


// Add plausible.io script
function add_to_head() {
	?>
	<script defer data-domain="thehouseinthecountry.com" src="https://plausible.io/js/script.js"></script>
	<?php
}
add_action( 'wp_head', 'add_to_head' );


// Async js
function load_js_asynchronously( $tag, $handle, $src ) {
	$async_scripts = array(
//		'/wp-includes/js/jquery/jquery.min.js',
//		'/wp-includes/js/jquery/jquery-migrate.min.js',
//		'/wp-includes/js/jquery/ui/core.min.js',
//		'/wp-includes/js/jquery/ui/datepicker.min.js',
		'/wp-content/plugins/ewww-image-optimizer/includes/lazysizes.min.js',
		'/wp-content/plugins/wp-booking-system-premium/assets/js/moment.min.js',
		'/wp-content/plugins/wp-booking-system-premium/assets/js/script-front-end.min.js',
		'/wp-content/plugins/wp-booking-system-premium-contracts/assets/js/script-front-end.min.js',
		'/wp-content/plugins/wp-booking-system-premium-contracts/assets/js/signature_pad.umd.min.js',
		'/wp-content/plugins/wp-booking-system-premium-search/assets/js/script-front-end.min.js',
//		'/wp-content/themes/rentals-collective-theme-two/assets/dist/js/app.js',
	);

	foreach ( $async_scripts as $async_script ) {
		if ( strpos( $src, $async_script ) !== false ) {
			$tag = str_replace( ' src', ' async="async" src', $tag );
		}
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'load_js_asynchronously', 10, 3 );


// Load asynchronous CSS
function load_css_asynchronously( $html, $handle, $href, $media ) {
	$async_styles = array(
//		'/wp-content/themes/rentals-collective-theme-two/assets/dist/css/styles.css',
		'/wp-includes/css/dist/block-library/style.min.css',
		'/wp-includes/css/classic-themes.min.css',
		'/wp-content/plugins/wp-booking-system-premium/assets/css/style-front-end.min.css',
		'/wp-content/plugins/wp-booking-system-premium-contracts/assets/css/style-front-end.min.css',
		'/wp-content/plugins/wp-booking-system-premium-search/assets/css/style-front-end.min.css',
		'/wp-content/plugins/wp-booking-system-premium-stripe/assets/css/style-front-end.min.css',
	);
	foreach ( $async_styles as $async_style ) {
		if ( strpos( $href, $async_style ) !== false ) {
			$html = <<<EOT
<link rel='stylesheet' id='$handle' href='$href' media='none' onload="if(media!='all')media='all'" />
<noscript><link rel='stylesheet' id='$handle-noscript' href='$href' media='all' /></noscript>
EOT;
		}
	}
	return $html;
}
add_filter( 'style_loader_tag', 'load_css_asynchronously', 10, 4 );


// Lazyload specific images
function add_lazy_loading_to_specific_images($content) {
	$specific_images = array(
		'https://thehouseinthecountry.com/wp-content/uploads/2023/04/sarhos-map-big.png',
		'https://thehouseinthecountry.com/wp-content/uploads/2023/04/hiking-yellow-mild.svg'
	);

	foreach ($specific_images as $image_url) {
		$pattern = '|<img(.*?)src=[\'"](' . preg_quote($image_url) . ')[\'"](.*?)>|i';
		$replacement = '<img$1src="$2"$3 loading="lazy">';
		$content = preg_replace($pattern, $replacement, $content);
	}

	return $content;
}
add_filter('the_content', 'add_lazy_loading_to_specific_images');



