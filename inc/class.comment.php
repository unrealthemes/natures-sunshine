<?php

/**
 * CUSTOM COMMENT WALKER
 * A custom walker for comments, based on the walker in Twenty Nineteen.
 *
 * @since Twenty Twenty 1.0
 */
class UT_Walker_Comment extends Walker_Comment {

	/**
	 * Outputs a comment in the HTML5 format.
	 *
	 * @since Twenty Twenty 1.0
	 *
	 * @see wp_list_comments()
	 * @see https://developer.wordpress.org/reference/functions/get_comment_author_url/
	 * @see https://developer.wordpress.org/reference/functions/get_comment_author/
	 * @see https://developer.wordpress.org/reference/functions/get_avatar/
	 * @see https://developer.wordpress.org/reference/functions/get_comment_reply_link/
	 * @see https://developer.wordpress.org/reference/functions/get_edit_comment_link/
	 *
	 * @param WP_Comment $comment Comment to display.
	 * @param int        $depth   Depth of the current comment.
	 * @param array      $args    An array of arguments.
	 */
	protected function html5_comment( $comment, $depth, $args ) {

		$comment_author_url = get_comment_author_url( $comment );
		$comment_author = get_comment_author( $comment );
		$comment_timestamp = sprintf( __( '%1$s at %2$s', 'twentytwenty' ), get_comment_date( '', $comment ), get_comment_time() );
		$comment_reply_link = get_comment_reply_link(
			array_merge(
				$args,
				array(
					'add_below' => 'div-comment',
					'depth'     => $depth,
					'max_depth' => $args['max_depth'],
					'before'    => '<span class="comment-reply" data-fancybox="" data-src="#comment-form">',
					'after'     => '</span>',
				)
			)
		);
		// $class = ( $this->has_children ) ? 'class="comments__item-row comments__item-row--answer"' : 'class="comments__item-row parent"';
		$class = 'class="comments__item-row"';
		?>

		<div <?php comment_class( $this->has_children ? 'comments__item text parent' : 'comments__item text', $comment ); ?>>

            <div id="comment-<?php comment_ID(); ?>" <?php echo $class; ?>>
                <div class="comments__item-top">
                    <p class="comments__item-author">
						<strong>
							<?php echo $comment_author; ?>
						</strong>
					</p>
                    <span class="comments__item-date">
						<?php
						echo get_comment_date( 'd F Y', $comment );
						// printf(
						// 	'<time datetime="%s" title="%s">%s</time>',
						// 	get_comment_time( 'c' ),
						// 	esc_attr( $comment_timestamp ),
						// 	esc_html( $comment_timestamp )
						// );

						// if ( get_edit_comment_link() ) {
						// 	printf(
						// 		' <span aria-hidden="true">•</span> <a class="comment-edit-link" href="%s">%s</a>',
						// 		esc_url( get_edit_comment_link() ),
						// 		__( 'Edit', 'twentytwenty' )
						// 	);
						// }
						?>
					</span>
                </div>
                <div class="comments__item-text">
                    <?php comment_text(); ?>
                </div>

				<?php 
				if ( is_user_logged_in() ) : 
					echo $comment_reply_link; 
				endif;
				?>

            </div>

		<?php
	}
}