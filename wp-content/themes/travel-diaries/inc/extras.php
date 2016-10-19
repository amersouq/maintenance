<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Travel_Diaries
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $travel_diaries_classes Classes for the body element.
 * @return array
 */
function travel_diaries_body_classes( $classes ) {
	
    global $post;
    
    // Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color';
	}
    
    if( !( is_active_sidebar( 'right-sidebar' )) || is_page_template( 'template-home.php' ) ) {
		$classes[] = 'full-width';	
	}
    
    if( is_page() ){
		$sidebar_layout = get_post_meta( $post->ID, 'travel_diaries_sidebar_layout', true );
        if( $sidebar_layout == 'no-sidebar' )
		$classes[] = 'full-width';
	}
    
    return $classes;
}
add_filter( 'body_class', 'travel_diaries_body_classes' );

if( ! function_exists( 'travel_diaries_excerpt' ) ):  
/**
 * travel_diaries_excerpt can truncate a string up to a number of characters while preserving whole words and HTML tags
 *
 * @param string $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 *
 * @return string Trimmed string.
 * 
 * @link http://alanwhipple.com/2011/05/25/php-truncate-string-preserving-html-tags-words/
 */
function travel_diaries_excerpt($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
	if ($considerHtml) {
		// if the plain text is shorter than the maximum length, return the whole text
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		// splits all html-tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($ending);
		$open_tags = array();
		$truncate = '';
		foreach ($lines as $line_matchings) {
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if (!empty($line_matchings[1])) {
				// if it's an "empty element" with or without xhtml-conform closing slash
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
				// if tag is a closing tag
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
					unset($open_tags[$pos]);
					}
				// if tag is an opening tag
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if ($total_length+$content_length> $length) {
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach ($entities[0] as $entity) {
						if ($entity[1]+1-$entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
				// maximum lenght is reached, so get off the loop
				break;
			} else {
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			// if the maximum length is reached, get off the loop
			if($total_length>= $length) {
				break;
			}
		}
	} else {
		if (strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = substr($text, 0, $length - strlen($ending));
		}
	}
	// if the words shouldn't be cut in the middle...
	if (!$exact) {
		// ...search the last occurance of a space...
		$spacepos = strrpos($truncate, ' ');
		if (isset($spacepos)) {
			// ...and cut the text in this position
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	// add the defined ending to the text
	$truncate .= $ending;
	if($considerHtml) {
		// close all unclosed html-tags
		foreach ($open_tags as $tag) {
			$truncate .= '</' . $tag . '>';
		}
	}
	return $truncate;
}
endif; // End function_exists

/**
 * Callback for Social Links 
 */
function travel_diaries_social_cb(){
    $facebook    = get_theme_mod( 'travel_diaries_facebook' );
    $twitter     = get_theme_mod( 'travel_diaries_twitter' );
    $instagram   = get_theme_mod( 'travel_diaries_instagram' );
    $google_plus = get_theme_mod( 'travel_diaries_google_plus' );    
    $linkedin    = get_theme_mod( 'travel_diaries_linkedin' );
    $youtube     = get_theme_mod( 'travel_diaries_youtube' );    
    
    if( $facebook || $twitter || $instagram || $google_plus || $linkedin || $youtube ){
    ?>
    <ul class="social-networks">
		<?php if( $facebook ){?>
            <li><a href="<?php echo esc_url( $facebook );?>" target="_blank" title="<?php esc_html_e( 'Facebook', 'travel-diaries' ); ?>"><span class="fa fa-facebook"></span></a></li>
		<?php } if( $google_plus ){?>
            <li><a href="<?php echo esc_url( $google_plus );?>" target="_blank" title="<?php esc_html_e( 'Google Plus', 'travel-diaries' ); ?>"><span class="fa fa-google-plus"></span></a></li>
        <?php } if( $instagram ){?>
            <li><a href="<?php echo esc_url( $instagram );?>" target="_blank" title="<?php esc_html_e( 'Instagram', 'travel-diaries' ); ?>"><span class="fa fa-instagram"></span></a></li>
		<?php } if( $linkedin ){?>
            <li><a href="<?php echo esc_url( $linkedin );?>" target="_blank" title="<?php esc_html_e( 'LinkedIn', 'travel-diaries' ); ?>"><span class="fa fa-linkedin"></span></a></li>
        <?php } if( $twitter ){?>    
            <li><a href="<?php echo esc_url( $twitter );?>" target="_blank" title="<?php esc_html_e( 'Twitter', 'travel-diaries' ); ?>"><span class="fa fa-twitter"></span></a></li>
		<?php } if( $youtube ){?>
            <li><a href="<?php echo esc_url( $youtube );?>" target="_blank" title="<?php esc_html_e( 'YouTube', 'travel-diaries' ); ?>"><span class="fa fa-youtube"></span></a></li>
		<?php } ?>
	</ul>
    <?php
    }
}
add_action( 'travel_diaries_social', 'travel_diaries_social_cb' );
 
/**
 * Callback for Header Info Contents 
 */
function travel_diaries_header_info_cb(){
    
    $info           = '';
    $first_label    = get_theme_mod( 'travel_diaries_first_label' );
    $first_content  = get_theme_mod( 'travel_diaries_first_content' );
    $second_label   = get_theme_mod( 'travel_diaries_second_label' );
    $second_content = get_theme_mod( 'travel_diaries_second_content' );
    $third_label    = get_theme_mod( 'travel_diaries_third_label' );
    $third_content  = get_theme_mod( 'travel_diaries_third_content' );
    
    if( $first_label || $first_content || $second_label || $second_content || $third_label || $third_content ){
        $info = '<ul class="info-lists">';
        if( $first_label && $first_content ){ 
            $info .= '<li>'. esc_html( $first_label ) .'<strong>'. esc_html( $first_content ) .'</strong></li>';
        }         
        if( $second_label && $second_content ){
            $info .= '<li>'. esc_html( $second_label ) .'<strong>'. esc_html( $second_content ) .'</strong></li>';            
        } 
        if( $third_label && $third_content ){
            $info .= '<li>'. esc_html( $third_label ) .'<strong>'. esc_html( $third_content ) .'</strong></li>';            
        } 
        $info .= '</ul>';
    }    
    echo $info;    
}
add_action( 'travel_diaries_header_info', 'travel_diaries_header_info_cb' );
 
/** 
* Hook to move comment text field to the bottom in WP 4.4
* 
* @link http://www.wpbeginner.com/wp-tutorials/how-to-move-comment-text-field-to-bottom-in-wordpress-4-4/ 
*/
function travel_diaries_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}
add_filter( 'comment_form_fields', 'travel_diaries_move_comment_field_to_bottom' );

/**
 * Callback function for Comment List *
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
function travel_diaries_theme_comment( $comment, $args, $depth ){
    $GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php printf( __( '<b class="fn">%s</b>', 'travel-diaries' ), get_comment_author_link() ); ?>
	</div>
	<?php if ( $comment->comment_approved == '0' ) : ?>
		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'travel-diaries' ); ?></em>
		<br />
	<?php endif; ?>

	<div class="comment-metadata commentmetadata">
    <?php esc_html_e( 'Posted on', 'travel-diaries' );?>
    <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
		<time><?php echo esc_html( get_comment_date() ); ?></time>
    </a>
	</div>
    
    <div class="comment-content"><?php comment_text(); ?></div>
    
	<div class="reply">
	<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}

/**
 * Fuction to get Sections 
 */
function travel_diaries_get_sections(){
    
    $sections = array( 
        'banner-section' => array(
            'class' => 'banner',
            'id'    => 'banner'    
        ),
        'featured-section' => array(
            'class' => 'featured-on',
            'id'    => 'featuredon'
        ),
        'recent-section' => array(
            'class' => 'recent-posts',
            'id'    => 'recentpost'
        ),
        'article-section' => array(
            'class' => 'popular-posts',
            'id'    => 'populararticle'
        ),
        'clients-section' => array(
            'class' => 'clients',
            'id'    => 'client'
        ),
        'guide-section' => array(
            'class' => 'guide',
            'id'    => 'guide'
        )                
    );
    
    $enabled_section = array();
    
    foreach ( $sections as $section ) {        
        if ( esc_attr( get_theme_mod( 'travel_diaries_ed_' . $section['id'] . '_section' ) ) == 1 ){
            $enabled_section[] = array(
                'id' => $section['id'],
                'class' => $section['class']
            );
        }
    }
    
    return $enabled_section;
}

/**
 * Custom CSS
*/
function travel_diaries_custom_css(){
    $custom_css = get_theme_mod( 'travel_diaries_custom_css' );
    if( !empty( $custom_css ) ){
		echo '<style type="text/css">';
		echo wp_strip_all_tags( $custom_css );
		echo '</style>';
	}
}
add_action( 'wp_head', 'travel_diaries_custom_css', 100 );

if ( ! function_exists( 'travel_diaries_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function travel_diaries_excerpt_more() {
	return ' &hellip; ';
}
add_filter( 'excerpt_more', 'travel_diaries_excerpt_more' );
endif;

if ( ! function_exists( 'travel_diaries_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function travel_diaries_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'travel_diaries_excerpt_length', 999 );
endif;

/**
 * Footer Credits 
*/
function travel_diaries_footer_credit(){
    
    $text  = '<span class="copyright">';
    $text .=  esc_html__( 'Copyright &copy; ', 'travel-diaries' ) . date('Y'); 
    $text .= ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>. ';
    $text .= '</span><span class="site-info">';
    $text .= '<a href="' . esc_url( 'http://raratheme.com/wordpress-themes/travel-diaries/' ) .'" rel="author" target="_blank">' . esc_html__( 'Theme Travel Diaries by Rara Theme', 'travel-diaries' ) .'</a>. ';
    $text .= sprintf( esc_html__( 'Powered by %s', 'travel-diaries' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'travel-diaries' ) ) .'" target="_blank">WordPress</a>.' );
    $text .= '</span>';
    
    echo apply_filters( 'travel_diaries_footer_text', $text );    
}
add_action( 'travel_diaries_footer', 'travel_diaries_footer_credit' );

/**
 * Function for logo listing 
*/
function travel_diaries_logo_listing( $logo, $link = false, $client = false ){
    $return = '';
    if( $logo ){
	$return  = ( $client == true ) ? '<div class="columns-2">' : '<li>';
    if( $link ) $return .= '<a href="'. esc_url( $link ) .'" target="_blank">'; 
    $return .= '<img src="' . esc_url( $logo ) . '" alt="" />';
    if( $link ) $return .= '</a>';
    $return .= ( $client == true ) ? '</div>' : '</li>';
    echo $return;
    }
}

/**
 * Query Newsletter activation
*/
function is_newsletter_activated(){
    return class_exists( 'newsletter' ) ? true : false;
}

/**
 * Return sidebar layouts for pages
*/
function travel_diaries_sidebar_layout(){
    global $post;
    
    if( get_post_meta( $post->ID, 'travel_diaries_sidebar_layout', true ) ){
        return get_post_meta( $post->ID, 'travel_diaries_sidebar_layout', true );    
    }else{
        return 'right-sidebar';
    }
}