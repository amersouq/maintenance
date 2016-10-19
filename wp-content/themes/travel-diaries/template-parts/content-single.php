<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Travel_Diaries
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		
        <?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
        
		<div class="entry-meta">
			<?php travel_diaries_posted_on(); ?>
		</div><!-- .entry-meta -->
        
	</header><!-- .entry-header -->
    
    <?php 
    if( has_post_thumbnail() ){
        echo '<div class="post-thumbnail">';
        is_active_sidebar( 'right-sidebar' ) ? the_post_thumbnail( 'travel-diaries-image-with-sidebar' ) : the_post_thumbnail( 'travel-diaries-image-full-width' );
        echo '</div>';
    } ?>
    
	<div class="entry-content">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'travel-diaries' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'travel-diaries' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php travel_diaries_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
