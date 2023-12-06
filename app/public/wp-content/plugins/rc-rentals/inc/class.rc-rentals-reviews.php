<?php

if(!class_exists('RC_Rentals_Reviews')) {
    class RC_Rentals_Reviews {
        function __construct() {
            add_action( 'comment_form_logged_in_after', array($this, 'rc_rentals_reviews_comment_rating_field' ));
            add_action( 'comment_form_after_fields', array($this, 'rc_rentals_reviews_comment_rating_field'));
            add_action( 'comment_post', array($this, 'rc_rentals_reviews_save_comment_rating'));
            add_filter( 'preprocess_comment', array($this ,'rc_rentals_reviews_require_rating'));
            add_filter( 'comment_text', array($this ,'rc_rentals_reviews_display_rating'));

//            add_filter('comment_form_default_fields', array($this, 'add_comment_fields'));

//            add_action( 'comment_form_after_fields', array($this, 'rc_rentals_reviews_comment_image_field'));
//            add_action( 'comment_post', array($this, 'rc_rentals_reviews_save_image_upload'));
//            add_filter( 'the_content', array($this ,'rc_rentals_reviews_display_average_rating'));
        }

        //Create the rating interface.
        public function rc_rentals_reviews_comment_rating_field() {
            global $post;
            if($post->post_type == 'rc-rentals') :
            ?>
            <div class="headline-3"><?php
                esc_html_e('How would you rate your accommodation in ', 'rc-rentals');
                echo $post->post_title . '?';
                ?></div>
            <fieldset class="rc-rentals__reviews-comments-rating">
                <span class="rc-rentals__reviews-comments-rating-container">
                    <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                        <label title="<?php esc_html_e('Rate your staying with ', 'rc-rentals'); ?><?php echo esc_html($i) ?><?php esc_html_e(' points', 'rc-rentals'); ?>" for="rating-<?php echo esc_attr( $i ); ?>">
                        <input type="radio" id="rating-<?php echo esc_attr( $i ); ?>" name="rating" value="<?php echo esc_attr( $i ); ?>" /><span class="star">&#9734</span></label>
                    <?php endfor; ?>
<!--                    <input type="radio" id="rating-0" class="star-clear" name="rating" value="0" /><label for="rating-0">0</label>-->
                </span>
            </fieldset>
            <?php endif;
        }

        //Save the rating submitted by the user.
        public function rc_rentals_reviews_save_comment_rating( $comment_id ) {
            if ( ( isset( $_POST['rating'] ) ) && ( '' !== $_POST['rating'] ) )
                $rating = intval( $_POST['rating'] );
            add_comment_meta( $comment_id, 'rating', $rating );
        }

        //Make the rating required.
        public function rc_rentals_reviews_require_rating( $commentdata ) {
            if ( ! is_admin() && ( ! isset( $_POST['rating'] ) || 0 === intval( $_POST['rating'] ) ) )
                wp_die( __( 'Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.' ) );
            return $commentdata;
        }

        //Display the rating on a submitted comment.
        public function rc_rentals_reviews_display_rating( $comment_text ){

            if ( $rating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
                $stars = '<p class="admin-stars">';
                for ( $i = 1; $i <= $rating; $i++ ) {
                    $stars .= '<span class="admin-stars-filled">&#9733</span>';
                }
                $stars .= '</p>';
                $comment_text = $comment_text . $stars;
                return $comment_text;
            } else {
                return $comment_text;
            }
        }

        //Get the average rating of a post.
//        public function rc_rentals_reviews_get_average_ratings( $id ) {
//            $comments = get_approved_comments( $id );
//
//            if ( $comments ) {
//                $i = 0;
//                $total = 0;
//                foreach( $comments as $comment ){
//                    $rate = get_comment_meta( $comment->comment_ID, 'rating', true );
//                    if( isset( $rate ) && '' !== $rate ) {
//                        $i++;
//                        $total += $rate;
//                    }
//                }
//
//                if ( 0 === $i ) {
//                    return false;
//                } else {
//                    return round( $total / $i, 1 );
//                }
//            } else {
//                return false;
//            }
//        }
//
//        //Display the average rating above the content.
//        public function rc_rentals_reviews_display_average_rating( $content ) {
//
//            global $post;
//
//            if ( false === rc_rentals_reviews_get_average_ratings( $post->ID ) ) {
//                return $content;
//            }
//
//            $stars   = '';
//            $average = rc_rentals_reviews_get_average_ratings( $post->ID );
//
//            for ( $i = 1; $i <= $average + 1; $i++ ) {
//
//                $width = intval( $i - $average > 0 ? 20 - ( ( $i - $average ) * 20 ) : 20 );
//
//                if ( 0 === $width ) {
//                    continue;
//                }
//
//                $stars .= '<span style="overflow:hidden; width:' . $width . 'px" class="dashicons dashicons-star-filled"></span>';
//
//                if ( $i - $average > 0 ) {
//                    $stars .= '<span style="overflow:hidden; position:relative; left:-' . $width .'px;" class="dashicons dashicons-star-empty"></span>';
//                }
//            }
//
//            $custom_content  = '<p class="average-rating">This post\'s average rating is: ' . $average .' ' . $stars .'</p>';
//            $custom_content .= $content;
//            return $custom_content;
//        }

//        public function add_comment_fields($fields) {
//        $fields['age'] = '<p class="comment-form-age"><label for="photo">' . __( 'Upload photo' ) . '</label>' .
//            '<input id="photo" name="photo" type="image" alt="" size="30" /></p>';
//        return $fields;
//        }
    }
}