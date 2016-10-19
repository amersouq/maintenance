<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Travel_Diaries
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php esc_html_e( 'Comments', 'travel-diaries' ); ?>
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
                    'callback'   => 'travel_diaries_theme_comment',
                    'avatar_size'=> 83,
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'travel-diaries' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'travel-diaries' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'travel-diaries' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php
		endif; // Check for comment navigation.

	endif; // Check for have_comments().


	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'travel-diaries' ); ?></p>
	<?php
	endif;
    
    //https://codex.wordpress.org/Function_Reference/comment_form
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    
    $fields =  array(
        'author' =>
            '<p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'travel-diaries' ) . '<span class="required">*</span></label><input id="author" name="author" type="text" placeholder="' . esc_attr__( 'Name*', 'travel-diaries' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
            '" size="30"' . $aria_req . ' /></p>',
        'email' =>
            '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'travel-diaries' ) . '<span class="required">*</span></label><input id="email" name="email" type="text" placeholder="' . esc_attr__( 'Email*', 'travel-diaries' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) .
            '" size="30"' . $aria_req . ' /></p>',
        'url' =>
            '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'travel-diaries' ) . '</label><input id="url" name="url" type="text" placeholder="' . esc_attr__( 'Website', 'travel-diaries' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
            '" size="30" /></p>',
    );
        
    $comment_arg = array(
        'id_form'           => 'commentform',
        'id_submit'         => 'submit',
        'class_submit'      => 'submit',
        'name_submit'       => 'submit',
        'title_reply'       => __( 'Leave a Reply', 'travel-diaries' ),
        'title_reply_to'    => __( 'Leave a Reply to %s', 'travel-diaries' ),
        'cancel_reply_link' => __( 'Cancel Reply', 'travel-diaries' ),
        'label_submit'      => __( 'POST COMMENT', 'travel-diaries' ),
        'format'            => 'xhtml',

        'comment_field' =>  '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'travel-diaries' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'travel-diaries' ) . '" cols="45" rows="8" aria-required="true">' .
            '</textarea></p>',

        'must_log_in' => '<p class="must-log-in">' .
        sprintf(
        __( 'You must be <a href="%s">logged in</a> to post a comment.', 'travel-diaries' ),
        wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
        ) . '</p>',

        'logged_in_as' => '<p class="logged-in-as">' .
        sprintf(
        __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'travel-diaries' ),
        admin_url( 'profile.php' ),
        $user_identity,
        wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
        ) . '</p>',            

        'comment_notes_after' => '',

        'fields' => apply_filters( 'comment_form_default_fields', $fields ),
    );
    
	comment_form( $comment_arg );
	?>

</div><!-- #comments -->
