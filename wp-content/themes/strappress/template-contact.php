<?php
/*
Template Name: contact
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

<div class="row-fluid">
    <iframe width="100%" height="300"frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&source=s_q&hl=fr&geocode=&q=Save%2Bmy%2BSmartphone%2C%2BRue%2BDuret%2C%2B75116%2BParis%2C%2BFrance&ie=UTF8&z=16&t=m&iwloc=near&output=embed"></iframe>
</div>

<div class="row-fluid">
    <div class="span6">
        <h3>Adresse :</h3> 23 rue Duret 75116 Paris
        <h3>Métro :</h3> Ligne <?php echo '<img width="18" height="18" alt="1c" src="'.get_template_directory_uri().'/images/1c.png" />'; ?> ARGENTINE

        <h3>Téléphone :</h3> <a class="link_contact" href="tel:0177328846">+33 (0)1 77 32 88 46</a>
    </div>
    <div class="span6">
     <h3>Horaires d'ouverture :</h3>
     Mardi au Vendredi de 10h30 à 14h00
     et de 15h00 à 19h00<br />
     Samedi de 12h00 à 19h00
     <h3>E-mail :</h3> <a class="link_contact" href="mailto:contact@savemysmartphone.fr">contact@savemysmartphone.fr</a>
     <h3>Site Web :</h3> <a class="link_contact" href="http://www.savemysmartphone.fr" target="_blank">www.savemysmartphone.fr</a>
 </div>           
</div>

<div class="row-fluid">
<div class="span6">
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
    </div>
</div>

                    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>  

                </div><!-- end of .span9 -->

                <?php get_footer(); ?>