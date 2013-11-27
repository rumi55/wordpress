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

          <div class="span6 copyright">
              <?php
              $copyright_text = of_get_option('copyright_text');
              if ($copyright_text){ ?> 
              <?php echo $copyright_text ?>
              <?php } else { ?>
              <?php esc_attr_e('&copy;', 'responsive'); ?> <?php _e(date('Y')); ?><a href="<?php echo home_url('/') ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
              <?php bloginfo('name'); ?>
              <?php } ?>
              Réalisé par l'agence web <a href="www.ab-agency.fr">AB Agency</a>
          </div><!-- end of col-460 fit -->
        </div>        
      </div>

    </div><!-- end of col-940 -->
  </div>
</div><!-- end #footer-wrapper -->
</div>  
</div><!-- end #footer -->

<?php wp_footer(); ?>
</div>
</body>
</html>