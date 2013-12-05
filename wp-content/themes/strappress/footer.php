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
$dir = get_template_directory_uri();
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
              </a><a href="http://www.ab-agency.fr" target="_blank">Réalisé par l'agence web AB Agency</a>
          </div><!-- end of col-460 fit -->
        </div>        
      </div>
      <div class="row-fluid">
				<div class="facebook offset4 span1">
					<div class="fb-like" data-href="https://www.facebook.com/savemysmartphone" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
					</div>
				<div class="twitter span2">
					<a href="https://twitter.com/damienmorin" class="twitter-follow-button" data-show-count="true" data-lang="fr">Follow @twitterapi</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</div>
				<div class="googleplus span1">
					<div class="g-plusone" data-size="medium" data-href="https://plus.google.com/107957583297179616406"></div>
				</div>
      </div>

    </div><!-- end of col-940 -->
  </div>
</div><!-- end #footer-wrapper -->
</div>
<script type="text/javascript">
    window.___gcfg = {lang: 'fr'};

    (function() {
      var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
      po.src = 'https://apis.google.com/js/platform.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
</div><!-- end #footer -->

<?php wp_footer(); ?>
</div>
</body>
</html>