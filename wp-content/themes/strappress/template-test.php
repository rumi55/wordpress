<?php
/*
Template Name: test
*/
?>

<?php
/**
 * Pages Template
 *
 *
 * @file           page.php
 * @package        StrapPress 
 * @author         Brad Williams 
 * @copyright      2011 - 2012 Brag Interactive
 * @license        license.txt
 * @version        Release: 2.1.1
 * @link           http://codex.wordpress.org/Theme_Development#Pages_.28page.php.29
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>

    <div class="row-fluid">
        <div class="span">
            <div id="content">

<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

         <?php if(of_get_option('breadcrumbs', '1')) {?>
        <?php echo responsive_breadcrumb_lists(); ?>
        <?php } ?>
        
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <div class="page-header">
                <h1 class="page-title"><?php the_title(); ?></h1>
             
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

      		    <div class="sixteen colums">
			          <div id="accordion-container">
        	
                <h2 class="accordion-header">Choisissez la marque de votre téléphone ou de votre tablette</h2>
                
                <div class="accordion-content">
                	
					<?php
                    	$list_marque = $wpdb->get_results("SELECT * FROM wp_phone_company");
                    ?>

                    <div class="phone_company">
                    
                        <ul>
                        
							<?php
                                $dir = get_template_directory_uri();
								foreach ($list_marque as $marque) {
									echo '
									<li>
									<div class="company_logo">
									   <a class="anil" href="models.php?pc_id='.$marque->pc_id.'">
										<img src="'.$dir.'/'.$marque->pc_image.'"/>
									</a>
									</div>
									</li> ';
								}
                            ?>
                        
                            
                        </ul>
                    
                    </div>
                    
                    <div style="clear:both;"></div>
                </div>
                
                
                
                <h2 class="accordion-header">Choisissez le modèle de votre smartphone ou tablette</h2>
                
                <div class="accordion-content">
                
                	<div class="brand_image_container">
                        <div class="brand_image">
                            <img src="images/brands/apple_hover.png" />
                        </div>
                    </div>
                    
                    <div class="brand_phone_model_container">
                    
                    	<div class="brand_phone_model">
                            <div class="phone_model">
                            
                            <ul>
                                <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/iphone_5.png">
                                        </a>
                                    </div>
                                </li>
                                
                                 <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/iphone_4s.png">
                                        </a>
                                    </div>
                                </li>
                                
                                <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/iphone_4.png">
                                        </a>
                                    </div>
                                </li>
                                
                                
                                <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/iphone_3gs.png">
                                        </a>
                                    </div>
                                </li>
                                
                                <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/iphone_3g.png">
                                        </a>
                                    </div>
                                </li>
                                
                                <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/ipod-touch_3g.png">
                                        </a>
                                    </div>
                                </li>
                                
                                
                                <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/ipod-touch_4g.png">
                                        </a>
                                    </div>
                                </li>
                                
                                
                                <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/ipad_2.png">
                                        </a>
                                    </div>
                                </li>
                                
                                
                                <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/ipad_retina.png">
                                        </a>
                                    </div>
                                </li>
                                
                                
                                <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/ipod-touch_3g.png">
                                        </a>
                                    </div>
                                </li>
                                
                                
                                <li>
                                    <div class="model_image"> 	
                                        <a href="#">
                                        <img src="images/phone_model/ipod-touch_4g.png">
                                        </a>
                                    </div>
                                </li>
                                
                                
                            </ul>
                            </div>
                    </div>
                    
                    </div>
                    
                    <div style="clear:both;"></div>
                
                </div>
                
                
                
                
                <h2 class="accordion-header">Choisissez le type de réparation</h2>
                
                <div class="accordion-content">
               
                	<div class="phone_service">
                    	
                        <div class="phone_service_left">
                        	<div class="ps_image">
                        		<img src="images/phone_model/ipad_retina.png">          
                            </div>
                        </div>
                        
                        
                        <div class="phone_service_right">
                            <div class="phone_service_box">
                            	
                                <ul>
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                    <li>
                                    <div class="service-name">
                                    <a href="#">écran cassé</a>
                                    </div>  
                                    <div class="service-price"><span>à partir de</span> 219,00 €</div>  
                                    </li>
                                    
                                </ul>
                            
                            </div>
                        </div>
                    
                    </div>
                	
                    <div style="clear:both;"></div>
                </div>
                
                
                
                <h2 class="accordion-header">Nous faire parvenir votre appareil</h2>
                
                <div class="accordion-content">
					
                    <div class="phone_service sub_service_container">
                    	
                        <div class="phone_service_left">
                        	<div class="ps_image">
                        		<img src="images/phone_model/ipad_retina.png">          
                            </div>
                        </div>
                        
                        
                        <div class="phone_service_right">
                            <div class="phone_service_box">
                            	

                                <div class="sub_service_box en_boutique_box">
                                	<div class="service_details">
                                    	<span class="service_name">LCD + Vitre blanc</span>
                                        <br/>
                                        <span class="service_price">89,00 €</span>
                                    </div>
                                </div>

                                
                                
                                <div class="sub_service_box par_courrier_box">
                                	<div class="service_details">
                                    	<span class="service_name">LCD + Vitre blanc</span>
                                        <br/>
                                        <span class="service_price">89,00 €</span>
                                    </div>
                                </div>
                                
                                <div class="sub_service_box par_coursier_box">
                                	<div class="service_details">
                                    	<span class="service_name">LCD + Vitre blanc</span>
                                        <br/>
                                        <span class="service_price">89,00 €</span>
                                    </div>
                                </div>
                                
                                	
                            </div>
                        </div>
                    
                    </div>
                	
                    <div style="clear:both;"></div>
                    
                </div>
        
        </div>
	</div>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript">
$(document).ready(function()
{
	//Add Inactive Class To All Accordion Headers
	$('.accordion-header').addClass('inactive-header');

	
	//Open The First Accordion Section When Page Loads
	$('.accordion-header').first().addClass('active-header').removeClass('inactive-header');
	$('.accordion-content').first().slideDown().addClass('open-content');
	
});   
</script>
      
        </div><!-- end of .span9 -->

<?php get_footer(); ?>