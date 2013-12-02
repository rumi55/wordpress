<?php
/**
 * Header Template
 *
 *
 * @file           header.php
 * @package        StrapPress 
 * @author         Brad Williams 
 * @copyright      2011 - 2012 Brag Interactive
 * @license        license.txt
 * @version        Release: 2.1.1
 * @link           http://codex.wordpress.org/Theme_Development#Document_Head_.28header.php.29
 * @since          available since Release 1.0
 */
?>
<!doctype html>
<!--[if lt IE 7 ]> <html class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>Save my Smartphone - Réparation de smartphone et tablette tactile a Paris et alentours</title>
<meta content="Save my Smartphone,smartphone, save, réparation,smartphone,smartphones,réparation tablette, réparation tablette tactile, écran cassé, iphone, iphone 4,écran cassé iphone, galaxy S3,réparation galaxy S3, vitre cassé, vitre cassée iphone, batterie iphone, iphone 5,changement batterie iPhone,remplacement écran,ipad,réparation vitre ipad, réparation pas chère,bouton home,réparation bouton home,face arrière iphone,samsung galaxy S4,réparation S4,apple iphone,téléphone portable, réparation téléphone,écran fissuré iphone" name="keywords">
<meta content="Save my Smarpthone " name="Author">

<meta value="index,follow" name="Robots">
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">

<!-- <title><?php wp_title('&#124;', true, 'right'); ?><?php bloginfo('name'); ?></title> -->
<?php $dir = get_template_directory_uri(); ?>
<link href=<?php echo $dir."/images/favicon.ico" ?> rel="shortcut icon">
<link href=<?php echo $dir."/images/apple-touch-icon.png" ?> rel="apple-touch-icon">
<link href=<?php echo $dir."/images/apple-touch-icon-72x72.png" ?> sizes="72x72" rel="apple-touch-icon">
<link href=<?php echo $dir."/images/apple-touch-icon-114x114.png" ?> sizes="114x114" rel="apple-touch-icon">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link href="http://fonts.googleapis.com/css?family=Arimo:400,700,400italic,700italic" rel="stylesheet" type="text/css">
<?php wp_enqueue_style('responsive-style', get_stylesheet_uri(), false, '1.2.0');?>
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php get_template_directory_uri();?>/css/font-awesome-ie7.css">
<![endif]-->

<?php wp_head(); ?>

  <?php
            $theme_options_styles = '';
            
            $link_color = of_get_option('link_color');
            if ($link_color) {
                $theme_options_styles .= '
                a{ 
                    color: ' . $link_color . '; 
                }';
            }
            
            $link_hover_color = of_get_option('link_hover_color');
            if ($link_hover_color) {
                $theme_options_styles .= '
                a:hover{ 
                    color: ' . $link_hover_color . '; 
                }';
            }
            
            $link_active_color = of_get_option('link_active_color');
            if ($link_active_color) {
                $theme_options_styles .= '
                a:active{ 
                    color: ' . $link_active_color . '; 
                }';
            }

            $topbar_position = of_get_option('nav_position');
            if ($topbar_position == 'static') {
                $theme_options_styles .= '
                .navbar{ 
                    position: relative !important; 
                }

                body {padding:0;}
                
                .home #wrapper, #wrapper {
                    margin-top: 0 !important;
                }

                '   
                ;
            }

            if($theme_options_styles){
                echo '<style>' 
                . $theme_options_styles . '
                </style>';
            }
        ?>  
        <!--<link rel="stylesheet" type="text/css" href="../wp-content/themes/strappress/css/old-site/base.css">-->
        <link rel="stylesheet" type="text/css" href=<?php echo $dir."/css/old-site/layout.css" ?>>
        <!--<link rel="stylesheet" type="text/css" href="../wp-content/themes/strappress/css/old-site/skeleton.css">-->


</head>

<body <?php body_class(); ?>>
    <div class="cloud-container">
                 
<?php responsive_container(); // before container hook ?>

         
    <?php responsive_header(); // before header hook ?>
    <div id="header">
  
        
    <?php responsive_in_header(); // header hook ?>
   
	<?php $nav_color = of_get_option('nav_color');?>

    <div class="navbar navbar-fixed-top <?php if($nav_color =='black') { echo 'navbar-inverse'; } ?>">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>

           <?php if ( of_get_option('logo_upload') ) { ?>
            <div id="logo"><a href=" <?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="homepage">
            <img src="<?php echo of_get_option('logo_upload'); ?>" alt="<?php bloginfo( 'name' ) ?>"/>
            </a></div><!-- end of #logo -->
            <?php } else { ?>
            <?php if (is_front_page()) { ?>
            <a class="brand" href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="homepage"><?php bloginfo( 'name' ) ?></a>
            <?php } else { ?>
            <a class="brand" href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="homepage"><?php bloginfo( 'name' ) ?></a>
            <?php } } ?>
        



          <div class="nav-collapse">
			   <?php

                $args = array(
                    'theme_location' => 'top-bar',
                    'depth'      => 2,
                    'container'  => false,
                    'menu_class'     => 'nav'                  
                );

                wp_nav_menu($args);

            ?>
            
            <!--
            <?php if(of_get_option('search_bar', '1')) {?>
                            <form class="navbar-search pull-right" role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
                                <input name="s" id="s" type="text" class="search-query" placeholder="<?php _e('Recherche','responsive'); ?>">
                            </form>
                            <?php } ?>
            -->
 

            
             <?php if(of_get_option('header_social', '1')) {?>

            <?php        
            // First let's check if any of this was set
        
                echo '<div class="social-icons nav pull-right">';
                    
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
          </div>

        </div>
        </div>
     </div>           

           
 
    </div><!-- end of #header -->
    <?php responsive_header_end(); // after header hook ?>
    
	<?php responsive_wrapper(); // before wrapper ?>
   
    <div class="container">
    
        <div id="wrapper" class="clearfix">
    
    <?php responsive_in_wrapper(); // wrapper hook ?>
