<?php
/**
 * Footer Template
 *
 *
 * @file           footer.php
 * @package        StrapPress 
 * @author         Brad Williams 
 * @copyright      2011 - 2012 Brag Interactive
 * @license        license.txt
 * @version        Release: 2.1.1
 * @link           http://codex.wordpress.org/Theme_Development#Footer_.28footer.php.29
 * @since          available since Release 1.0
 */
?>
    </div><!-- end of wrapper-->
    <?php responsive_wrapper_end(); // after wrapper hook ?>
    
   
</div><!-- end of container -->
 <?php responsive_container_end(); // after container hook ?>

<div id="footer" class="clearfix">
    <div class="container">

    <div id="footer-wrapper">

      <div class="row-fluid">
    
    <div class="span12">
    
        <div class="span6">
		<?php if (has_nav_menu('footer-menu', 'responsive')) { ?>
	        <?php wp_nav_menu(array(
				    'container'       => '',
					'menu_class'      => 'footer-menu',
					'theme_location'  => 'footer-menu')
					); 
				?>
         <?php } ?>
         </div><!-- end of col-460 -->
         
         <div class="span6">
					<?php if(of_get_option('footer_social', '1')) {?>
            <?php
            // First let's check if any of this was set
		
                echo '<div class="social-icons">';
					
                if (of_get_option('twitter_url')) echo '<a href="' . of_get_option('twitter_url') . '">'
                    .'<i class="icon-twitter-sign"></i>'
                    .'</a>';

                if (of_get_option('fb_url')) echo '<a href="' . of_get_option('fb_url') . '">'
                    .'<i class="icon-facebook-sign"></i>'
                    .'</a>';

                if (of_get_option('pinterest_url')) echo '<a href="' . of_get_option('pinterest_url') . '">'
                    .'<i class="icon-pinterest-sign"></i>'
                    .'</a>'; 
  
                if (of_get_option('linkedin_url')) echo '<a href="' . of_get_option('linkedin_url') . '">'
                    .'<i class="icon-linkedin-sign"></i>'
                    .'</a>';

                 if (of_get_option('google_url')) echo '<a href="' . of_get_option('google_url') . '">'
                    .'<i class="icon-google-plus-sign"></i>'
                    .'</a>';

                if (of_get_option('github_url')) echo '<a href="' . of_get_option('github_url') . '">'
                    .'<i class="icon-github-sign"></i>'
                    .'</a>';
					
                if (of_get_option('rss_url')) echo '<a href="' . of_get_option('rss_url') . '">'
                    .'<i class="icon-rss"></i>'
                    .'</a>';
       
               
             
                echo '</div><!-- end of .social-icons -->';
         ?>
         <?php } ?>
         </div><!-- end of col-460 fit -->
       </div>

      <div class="row-fluid">

          <div class="span5 copyright">
              <?php
                $copyright_text = of_get_option('copyright_text');
                  if ($copyright_text){ ?> 
                <?php echo $copyright_text ?>
                <?php } else { ?>
                <?php esc_attr_e('&copy;', 'responsive'); ?> <?php _e(date('Y')); ?><a href="<?php echo home_url('/') ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
                    <?php bloginfo('name'); ?>
              <?php } ?>
          </div> <!-- end copyright -->
         
         <?php if(of_get_option('scroll_arrow', '1')) {?>
        <div class="span2 scroll-top"><a href="#scroll-top" title="<?php esc_attr_e( 'scroll to top', 'responsive' ); ?>"><?php _e( '<i class="icon-chevron-up"></i>', 'responsive' ); ?></a></div>
        <?php } ?> 

        <div class="span5 powered">
           <?php
                $powered_text = of_get_option('powered_text');
                  if ($powered_text){ ?> 
                <?php echo $powered_text ?>
                <?php } else { ?>
               <a href="<?php echo esc_url(__('http://strappress.com','responsive')); ?>" title="<?php esc_attr_e('StrapPress', 'responsive'); ?>">
                    <?php printf('StrapPress'); ?></a>
            developed by <a href="<?php echo esc_url(__('http://bragthemes','responsive')); ?>" title="<?php esc_attr_e('Brag Themes', 'responsive'); ?>">
                    <?php printf('Brag Themes'); ?></a>
              <?php } ?>    
        </div><!-- end .powered -->
      </div>
        
    </div><!-- end of col-940 -->
  </div>
    </div><!-- end #footer-wrapper -->
  </div>  
</div><!-- end #footer -->

<?php wp_footer(); ?>

</body>
</html>