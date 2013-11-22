<?php
/**
 * Error 404 Template
 *
 *
 * @file           404.php
 * @package        StrapPress 
 * @author         Brad Williams 
 * @copyright      2011 - 2012 Brag Interactive
 * @license        license.txt
 * @version        Release: 2.1.1
 * @link           http://codex.wordpress.org/Creating_an_Error_404_Page
 * @since          available since Release 1.0
 */
?>
<?php get_header(); ?>

<div class="row-fluid">
    <div class="span12">
        <div id="content-full">
            <div id="post-0" class="error404">
                <div class="post-entry">
                    <h1 class="title-404"><?php _e('404 &#8212; OUUUUUPS!', 'responsive'); ?></h1>
                    <p><?php _e('Page introuvable', 'responsive'); ?></p>
                    <?php get_search_form(); ?>
                </div><!-- end of .post-entry -->
            </div><!-- end of #post-0 -->
        </div><!-- end of #content-full -->
    </div>
</div>

<?php get_footer(); ?>