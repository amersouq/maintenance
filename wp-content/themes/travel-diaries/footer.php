<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Travel_Diaries
 */

?>
<?php if( !is_page_template( 'template-home.php' ) ){?>
            </div><!-- .row -->
        </div><!-- .container -->
	</div><!-- #content -->
<?php } ?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		
    <?php if( is_active_sidebar( 'footer-one' ) || is_active_sidebar( 'footer-two' ) || is_active_sidebar( 'footer-three' ) || is_active_sidebar( 'footer-four' ) ){?>
        <div class="footer-t">
			<div class="container">
				<div class="row">
                
					<?php if( is_active_sidebar( 'footer-one' ) ){ ?>
                    <div class="columns-4">
                        <?php dynamic_sidebar( 'footer-one' ); ?>
                    </div>
					<?php } ?>
                    
                    <?php if( is_active_sidebar( 'footer-two' ) ){ ?>
					<div class="columns-4">
						<?php dynamic_sidebar( 'footer-two' ); ?>
					</div>
                    <?php } ?>
                    
                    <?php if( is_active_sidebar( 'footer-three' ) ){ ?>
					<div class="columns-4">
						<?php dynamic_sidebar( 'footer-three' ); ?>
					</div>
                    <?php } ?>
                    
                    <?php if( is_active_sidebar( 'footer-four' ) ){ ?>
					<div class="columns-4">
						<?php dynamic_sidebar( 'footer-four' ); ?>
					</div>
                    <?php } ?>
                    
				</div><!-- .row -->
			</div><!-- .container -->
		</div><!-- .footer-t -->
		<?php } ?>
        
        <div class="footer-b">
			<div class="container">
				<?php echo "إدارة التشغيل و الصيانة - جامعة الجوف"; ?>
			</div><!-- .container -->
		</div><!-- .footer-b -->
                <div class="no-print"><?php if ($_SESSION["staff_user_details"]) echo do_shortcode('[google-translator]'); ?></div>
	</footer><!-- #colophon -->
</div><!-- #page -->


<?php wp_footer(); ?>

</body>
</html>
