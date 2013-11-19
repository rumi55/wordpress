<?php
/**
 * Front Page
 *
 * Note: You can overwrite home.php as well as any other Template in Child Theme.
 * Create the same file (name) include in /child-theme/ and you're all set to go!
 * @see            http://codex.wordpress.org/Child_Themes
 *
 * @file           home.php
 * @package        StrapPress 
 * @author         Brad Williams 
 * @copyright      2011 - 2012 Brag Interactive
 * @license        license.txt
 * @version        Release: 2.1.1
 * @link           N/A
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>

			<?php
                // get homepage selection
                $hero_unit = of_get_option('hero_radio', 'no entry' );
                
            ?>


		 <?php if( $hero_unit === "featured") {?>

        <div class="hero-unit">
        	<div class="row-fluid">
        
        <div class="span6">

            <?php
            
			// First let's check if headline was set
			    if(of_get_option('featured_heading', 'no entry')) {
                    echo '<h1 class="featured-title">'; 
				    echo of_get_option('featured_heading', 'no entry' );
				    echo '</h1>'; 
			// If not display dummy headline for preview purposes
			      } else { 
			        echo '<h1 class="featured-title">';
				    echo __('Responsive!','responsive');
				    echo '</h1>';
				  }
			?>
                    
            <?php 
			// First let's check if headline was set
			    if(of_get_option('home_subheadline', 'no entry')) {
                    echo '<h2 class="featured-subtitle">'; 
				    echo of_get_option('home_subheadline', 'no entry');
				    echo '</h2>'; 
			// If not display dummy headline for preview purposes
			      } else { 
			        echo '<h2 class="featured-subtitle">';
				    echo __('Bootstrap WordPress Theme','responsive');
				    echo '</h2>';
				  }
			?>
            
            <?php 
			// First let's check if content is in place
			    if(of_get_option('home_content_area', 'no entry')) {
                    echo '<p>'; 
				    echo of_get_option('home_content_area', 'no entry');
				    echo '</p>'; 
			// If not let's show dummy content for demo purposes
			      } else { 
			        echo '<p>';
				    echo __('A responsive WordPress theme with all the Twitter Bootstrap goodies. Check out the page layouts, features,
				    	and shortcodes this theme has to offer. Feel free to look around.','responsive');
				    echo '</p>';
				  }
			?>
            
            <?php  
				$cta_btn_size = 'btn-'.of_get_option('cta_size', '' );
				$cta_btn_color = 'btn-'.of_get_option('cta_color', '' );
				$cta_btn_text = of_get_option('cta_text', '' );
				$cta_btn_url = of_get_option('cta_url', '' );

				if(of_get_option('button_block', '1')) {
					$cta_btn_block = "btn-block";
				}
            ?>
		    <?php if(of_get_option('display_button', '1')) {?>    
            <div class="call-to-action">

            <?php
			// First let's check if button was set
			    if(of_get_option('cta_text', 'no entry' )) {
					echo '<a href="'.$cta_btn_url.'" class="btn '.$cta_btn_block.' '.$cta_btn_size.' '.$cta_btn_color.'">'; 
					echo of_get_option('cta_text', 'no entry' );
				    echo '</a>';
			// If not display dummy button text for preview purposes
			      } else { 
					echo '<a href="#nogo" class="btn btn-block btn-large btn-warning">'; 
					echo __('Call to Action','responsive');
				    echo '</a>';
				  }
			?>  
            
            </div><!-- end of .call-to-action -->
            <?php } ?>           
            
        </div><!-- end of .col-460 -->

        <div id="hero-image" class="span6"> 
                           
            <?php 
			// First let's check if headline was set
			    if (of_get_option('featured_content', 'no entry')) {
					echo of_get_option('featured_content', 'no entry');
		    // If not display dummy headline for preview purposes
			      } else {             
                    echo '<img class="aligncenter" src="'.get_stylesheet_directory_uri().'/images/featured-image.png" width="440" height="300" alt="" />'; 
 				  }
			?> 
                                   
        </div><!-- end of .col-460 fit --> 
   		</div>
        </div><!-- end of .hero-unit -->
         <?php } else { ?>     
  
<div class="hero-unit">
			<?php
    $recentPosts = new WP_Query();
    $recentPosts->query('showposts=1');
?>
<?php global $more; $more = 0; ?>
<?php while ($recentPosts->have_posts()) : $recentPosts->the_post(); ?>
     <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'responsive'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h1>
                
                <div class="post-meta">
                <?php 
                    printf( __( '<i class="icon-time"></i> %2$s <i class="icon-user"></i> %3$s', 'responsive' ),'meta-prep meta-prep-author',
		            sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
			            get_permalink(),
			            esc_attr( get_the_time() ),
			            get_the_date()
		            ),
		            sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			            get_author_posts_url( get_the_author_meta( 'ID' ) ),
			        sprintf( esc_attr__( 'View all posts by %s', 'responsive' ), get_the_author() ),
			            get_the_author()
		                )
			        );
		        ?>
				    <?php if ( comments_open() ) : ?>
                        <span class="comments-link">
                        <span class="mdash">&mdash;</span>
                    <?php comments_popup_link(__('No Comments <i class="icon-arrow-down"></i>', 'responsive'), __('1 Comment <i class="icon-arrow-down"></i>', 'responsive'), __('% Comments <i class="icon-arrow-down"></i>', 'responsive')); ?>
                        </span>
                    <?php endif; ?> 
                </div><!-- end of .post-meta -->
                
                <div class="post-entry">
                    <?php if ( has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                    <?php the_post_thumbnail(); ?>
                        </a>
                    <?php endif; ?>
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

            <div class="post-edit"><?php edit_post_link(__('Edit', 'responsive')); ?></div>               
            </div><!-- end of #post-<?php the_ID(); ?> -->

<?php endwhile; ?>

</div>

			 <?php } ?> 

<?php get_sidebar('home'); ?>
<?php get_footer(); ?>