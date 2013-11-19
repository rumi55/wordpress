<?php
/**
 * Blog Template
 *
   Template Name: Blog (full posts)
 *
 * @file           blog.php
 * @package        StrapPress 
 * @author         Brad Williams 
 * @copyright      2011 - 2012 Brag Interactive
 * @license        license.txt
 * @version        Release: 2.1.1
 * @link           http://codex.wordpress.org/Templates
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>
<?php global $more; $more = 0; ?>

<div class="row-fluid">
        <div class="span9">

        <div id="content-blog">
<?php
    $limit = get_option('posts_per_page');
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    query_posts('showposts=' . $limit . '&paged=' . $paged);
    $wp_query->is_archive = true; $wp_query->is_home = false;
    ?>    
<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
        
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <h1 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'responsive'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h1>
                
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
        
        <?php if (  $wp_query->max_num_pages > 1 ) : ?>
        <ul class="pager">
			<li class="previous"><?php next_posts_link( __( '&#8249; Older posts', 'responsive' ) ); ?></li>
            <li class="next"><?php previous_posts_link( __( 'Newer posts &#8250;', 'responsive' ) ); ?></li>
		</ul><!-- end of .navigation -->
        <?php endif; ?>

	    <?php else : ?>

        <h1 class="title-404"><?php _e('404 &#8212; Fancy meeting you here!', 'responsive'); ?></h1>
        <p><?php _e('Don&#39;t panic, we&#39;ll get through this together. Let&#39;s explore our options here.', 'responsive'); ?></p>
        <h6><?php _e( 'You can return', 'responsive' ); ?> <a href="<?php echo home_url(); ?>/" title="<?php esc_attr_e( 'Home', 'responsive' ); ?>"><?php _e( '&#9166; Home', 'responsive' ); ?></a> <?php _e( 'or search for the page you were looking for', 'responsive' ); ?></h6>
        <?php get_search_form(); ?>
        
<?php endif; ?>  
      
        </div><!-- end of #content-blog -->
    </div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>