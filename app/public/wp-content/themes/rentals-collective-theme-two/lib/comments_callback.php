<?php
function rentals_collective_theme_two_comment_callback( $comment, $args, $depth)
{
	$tag = ( $args['style'] === 'div') ? 'div' : 'li';
	?>
	<<?php echo $tag ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(['comment', $comment->comment_parent ? 'comment--child' : '']) ?>>
	<article id="div-comment-<?php comment_ID(); ?>" class="comment__body">

		<?php edit_comment_link(esc_html__('Edit Comment', 'rentals_collective_theme_one'), '<span class="comment__edit-link">', '</span>'); ?>

		<div class="comment">

			<div class="comment__author">

				<div class="comment__author-image">
					<?php
					$default = wp_get_attachment_image(1983, 'full');
					$avatar = get_avatar($comment->comment_author_email, $args['avatar_size'], $default, false, array('class' => 'comment__avatar'));
					if (validate_gravatar($comment->comment_author_email) !== false) {
						echo $avatar;
					} else {
						echo $default;
					}
					?>
				</div>
				<div class="comment__author-info">
					<p class="headline-3 headline-3--font-size-xsm"><?php echo get_comment_author_link($comment); ?></p>
                    <a class="comment__time" href="<?php echo esc_url(get_comment_link($comment, $args)); ?>">
                        <time datetime="<?php comment_time('c'); ?>">
							<?php
							echo esc_html(get_comment_date('F j, Y'));
							?>
                        </time>
                    </a>
                </div>
			</div>
			<div class="comment__content">
				<?php if($comment->comment_approved == '0') { ?>
					<p class="comment__awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'rentals_collective_theme_one'); ?></p>
				<?php } ?>

				<?php
				if ($comment->comment_type === 'comment' || (( $comment->comment_type === 'pingback' || $comment->comment_type === 'trackback') && $args['short_ping'] )) {
					comment_text();
				};
				?>

				<?php
				comment_reply_link(
					array_merge(
						$args, array(
							'depth' => $depth,
							'add_below' => 'div-comment',
							'before' => '<div class="comment__reply-link">',
							'after' => '</div>'
						)
					)
				);
				?>
			</div>
		</div>

	</article>
	<?php
}
