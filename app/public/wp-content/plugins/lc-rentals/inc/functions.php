<?php

//Get the average rating of a post.
function lc_rentals_reviews_get_average_ratings( $id ) {
    $comments = get_approved_comments( $id );

    if ( $comments ) {
        $i = 0;
        $total = 0;
        foreach( $comments as $comment ){
            $rate = get_comment_meta( $comment->comment_ID, 'rating', true );
            if( isset( $rate ) && '' !== $rate ) {
                $i++;
                $total += $rate;
            }
        }

        if ( 0 === $i ) {
            return false;
        } else {
            return number_format( $total / $i, 1 );
        }
    } else {
        return false;
    }
}


//Display the average rating above the content.
//add_filter( 'the_content', 'lc_rentals_reviews_display_average_rating' );
//function lc_rentals_reviews_display_average_rating( $content ) {
//
//    global $post;
//
//    if ( false === lc_rentals_reviews_get_average_ratings( $post->ID ) ) {
//        return $content;
//    }
//
//    $stars   = '';
//    $average = lc_rentals_reviews_get_average_ratings( $post->ID );
//
//    for ( $i = 1; $i <= $average + 1; $i++ ) {
//
//        $width = intval( $i - $average > 0 ? 20 - ( ( $i - $average ) * 20 ) : 20 );
//
//        if ( 0 === $width ) {
//            continue;
//        }
//
//        $stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="star-filled"></span>';
//
//        if ( $i - $average > 0 ) {
//            $stars .= '<span style="overflow:hidden; position:relative; left:-' . $width .'px;" class="star-empty"></span>';
//        }
//    }
//
//    $custom_content  = '<p class="average-rating">This post\'s average rating is: ' . $average .' ' . $stars .'</p>';
//    $custom_content .= $content;
//    return $custom_content;
//}


// After review submit, redirect to the post page
add_filter( 'comment_post_redirect', function ( $location ) {
    return get_permalink($_POST['comment_post_ID']);
} );


