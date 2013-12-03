<?php
/*
Template Name: medias
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


<?php get_header();
$dir = get_template_directory_uri(); ?>

<div class="row-fluid">
    <div id="content">

        <?php if (have_posts()) : ?>

          <?php while (have_posts()) : the_post(); ?>

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
</div>

<div class="main">

<div class="row-fluid">

        <div class="span6">
            <h3>Interview Startup</h3>
            <p>Save my Smartphone donne une interview pour Startup, un magazine dédié aux "Start-up".</p>
            <img src="../wp-content/themes/strappress/images/medias/startup.png"/>
        </div>     

   

        <div class="span6">
            <h3>01Net Magazine</h3>
            <p>Damien Morin, mis en lumière à travers un article étonnant et spontané</p>
            <img src="../wp-content/themes/strappress/images/medias/001.gif"/>
        </div>

        </div>

<div class="row-fluid">

        <div class="span6">
            <h3>Le bonbon</h3>
            <p>Save my Smartphone, article à l’image du magazine Le Bonbon, décalé, 
            branché et efficace</p>
            <img src="../wp-content/themes/strappress/images/medias/004.jpg"/>
        </div>     

   

        <div class="span6">
            <h3>Neo Domo</h3>
            <p>Save my Smarthpone mis en avant sur Neo Domo, le nouveau magazine dédié à 
            la maison connectée, écologique et intelligente.</p>
            <img src="../wp-content/themes/strappress/images/medias/003.jpg"/>
        </div>

        </div>

<div class="row-fluid">

        <div class="span6">
            <img src="../wp-content/themes/strappress/images/medias/002.jpg"/>
        </div>       

   

        <div class="span6">
            <h3>DegroupNews</h3>
            <p>Save my Smartphone, présenté sur Degroup News, site d’informations sur les 
            telecommunications</p>
            <img src="../wp-content/themes/strappress/images/001.jpg"/>
        </div>

        </div>


    <div class="row-fluid">

        <div class="span6">
            <h3>Interview de Damien Morin sur BFM Business - Made in Paris</h3>
            <iframe width="100%" height="300px" src="//www.youtube.com/embed/Mxl6FjvWS3A" frameborder="0" allowfullscreen></iframe>
        </div>       

   

        <div class="span6">
            <h3>Reportage M6</h3>
            <p>M6 ont choisi Save my Smartphone pour leur sujet sur la réparation des smartphones dans leur émission 100% Mag!</p>
            <img src="../wp-content/themes/strappress/images/medias/vid.png"/>
        </div>

        </div>


        <div class="row-fluid">

        <div class="span6">
             <h3>Interview BFM Business</h3>
            <p>Save my Smartphone mit en lumière par un journaliste présentant comment faire réparer son smartphone.</p>
            <iframe width="100%" height="300px" src="//www.youtube.com/embed/_MOsxyz6hUk" frameborder="0" allowfullscreen></iframe>
        </div>


        <div class="span6">
            <h3>Comuniqué dans Libération</h3>
            <p>Save my Smartphone apparait dans un communiqué dans Libé.</p>
            <img src="../wp-content/themes/strappress/images/medias/libe.png"/>
        </div>
     
        </div>

        <div class="row-fluid">

   
        <div class="span6">
            <h3>Article l'Express</h3>
            <p>Save my Smartphone mit en lumière par un journaliste présentant comment faire réparer son smartphone.</p>
            <img src="../wp-content/themes/strappress/images/medias/express.jpg"/>
        </div>
                    
      
        <div class="span6">
            <h3>Article Sortir A Paris</h3>
            <p>Save my Smartphone mit en lumière sur le site de réference pour les sorties à Paris.</p>
            <img src="../wp-content/themes/strappress/images/medias/sortiraparis.png"/>
        </div>

        </div>

        <div class="row-fluid">
      

        <div class="span6">
            <h3>Article Digital News</h3>
            <p>Save my Smartphone mit en lumière sur le site Digital News.</p>
            <img src="../wp-content/themes/strappress/images/medias/dg.png"/>
        </div>
       


        <div class="span6">
            <h3>Article Channel News</h3>
            <p>Save my Smartphone mis en lumière sur le site Channel News.</p>
            <img src="../wp-content/themes/strappress/images/medias/cnews.png"/>
        </div>

        </div>      
   
</div>



<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>  

</div>
</div>
<?php get_footer(); ?>