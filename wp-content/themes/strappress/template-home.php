<?php
/*
Template Name: home
?>

<?php
/**
 * Pages Template
 *
 *
 * @file           template-home.php
 * @package        StrapPress 
 * @author         Brad Williams 
 * @copyright      2011 - 2012 Brag Interactive
 * @license        license.txt
 * @version        Release: 2.1.1
 * @link           http://codex.wordpress.org/Theme_Development#Pages_.28page.php.29
 * @since          available since Release 1.0
 */
?>


<?php get_header();
$dir = get_template_directory_uri(); ?>
    <div id="content">

        <?php if (have_posts()) : ?>

          <?php while (have_posts()) : the_post(); ?>

           <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <div class="page-header">
				<div class="hero-unit">
					<div class="row-fluid">
						<div class="span6">
                            <div class="floating2">
    							<img class="floating" alt="iphone" src="wp-content/themes/strappress/images/iphone.png">
    						</div>
                                <img class="shadow" alt="shadow" src="wp-content/themes/strappress/images/shadow.png">
                        </div>

						<div id="hero-image" class="span6">
							<h1 class="title_home"><a href="tarifs/">Nous sauvons vos smartphones et tablettes tactiles</a></h1>
							<div class="row-fluid">
								<center><a href="charte-qualite"><img src="wp-content/themes/strappress/images/garantie.png"/></a>
								<a href="charte-qualite"><img src="wp-content/themes/strappress/images/rep_express.png"/></a></center>
							</div>
						</div>
					</div> 
				</div>
				<div class="row-fluid">
					<center><h2>Vu sur BFM Business</h2></center>
					<div class="picto_home home2">
				    	<center><object id="flashObj" width="480" height="270" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,47,0"><param name="movie" value="http://c.brightcove.com/services/viewer/federated_f9?isVid=1&isUI=1" /><param name="bgcolor" value="#FFFFFF" /><param name="flashVars" value="videoId=2767624404001&playerID=1145041260001&playerKey=AQ~~,AAABCkUhxfE~,OL4KeREqNaXsUSopmr6xNsd1gcdbfk93&domain=embed&dynamicStreaming=true" /><param name="base" value="http://admin.brightcove.com" /><param name="seamlesstabbing" value="false" /><param name="allowFullScreen" value="true" /><param name="swLiveConnect" value="true" /><param name="allowScriptAccess" value="always" /><embed src="http://c.brightcove.com/services/viewer/federated_f9?isVid=1&isUI=1" bgcolor="#FFFFFF" flashVars="videoId=2767624404001&playerID=1145041260001&playerKey=AQ~~,AAABCkUhxfE~,OL4KeREqNaXsUSopmr6xNsd1gcdbfk93&domain=embed&dynamicStreaming=true" base="http://admin.brightcove.com" name="flashObj" width="480" height="270" seamlesstabbing="false" type="application/x-shockwave-flash" allowFullScreen="true" allowScriptAccess="always" swLiveConnect="true" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></object></center>
					</div>
				</div>
                <div class="post-entry">
                    <?php the_content(__('Read more &#8250;', 'responsive')); ?>
                    <?php custom_link_pages(array(
                        'before' => '<div class="pagination"><ul>' . __(''),
                        'after' => '</ul></div>',
                            'next_or_number' => 'next_and_number', # activate parameter overloading
                            'nextpagelink' => __('&rarr;'),
                            'previouspagelink' => __('&larr;'),
                            'pagelink' => '%',
                            'echo' => 1 )
                            ); ?>
                </div><!-- end of .post-entry -->

                <?php if ( comments_open() ) : ?>
                    <div class="post-data">
                        <?php the_tags(__('Tagged with:', 'responsive') . ' ', ', ', '<br />'); ?> 
                        <?php the_category(__('Posted in %s', 'responsive') . ', '); ?> 
                    </div><!-- end of .post-data -->
                <?php endif; ?>             

            </div><!-- end of #post-<?php the_ID(); ?> -->

                    <?php comments_template( '', true ); ?>

                <?php endwhile; ?> 

                <?php if (  $wp_query->max_num_pages > 1 ) : ?>
                    <div class="navigation">
                     <div class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></div>
                     <div class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></div>
                 </div><!-- end of .navigation -->
             <?php endif; ?>

         <?php else : ?>

            <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'responsive'); ?></h1>
            <p><?php _e('Don&#39;t panic, we&#39;ll get through this together. Let&#39;s explore our options here.', 'responsive'); ?></p>
            <h6><?php _e( 'You can return', 'responsive' ); ?> <a href="<?php echo home_url(); ?>/" title="<?php esc_attr_e( 'Home', 'responsive' ); ?>"><?php _e( '&#9166; Home', 'responsive' ); ?></a> <?php _e( 'or search for the page you were looking for', 'responsive' ); ?></h6>
            <?php get_search_form(); ?>

        <?php endif; ?>  
    </div><!-- end of #content -->
</div>
<?php get_footer(); ?>