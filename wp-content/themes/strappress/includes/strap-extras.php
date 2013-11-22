<?php
/* Disable the Admin Bar */
show_admin_bar(false);
?>
<?php
function bootstrap_scripts()
{
	// Register the script for this theme:
	wp_register_script( 'bootstrap-script', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ) );
	wp_register_script( 'strap-extras-script', get_template_directory_uri() . '/js/strap-extras.js', array( 'jquery' ) );

	//  enqueue the script:
	wp_enqueue_script( 'bootstrap-script' );
	wp_enqueue_script( 'strap-extras-script' );
}
add_action( 'wp_enqueue_scripts', 'bootstrap_scripts' );

function bootstrap_styles()
{
	// Register the style like this for a theme:
	wp_register_style( 'bootstrap-styles', get_template_directory_uri() . '/css/bootstrap.css', array(), '2.0.4', 'all' );
    wp_register_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '2.0', 'all' );
	wp_register_style( 'colorbox', get_template_directory_uri() . '/css/colorbox.css', array(), '1.3.19', 'all' );


	//  enqueue the style:
	wp_enqueue_style( 'bootstrap-styles' );
    wp_enqueue_style( 'font-awesome' );
	wp_enqueue_style( 'colorbox' );
}
add_action( 'wp_enqueue_scripts', 'bootstrap_styles' );

?>
<?php

add_action( 'after_setup_theme', 'bootstrap_setup' );

if ( ! function_exists( 'bootstrap_setup' ) ):

	function bootstrap_setup(){

		add_action( 'init', 'register_menu' );

		function register_menu(){
			register_nav_menu( 'top-bar', 'Top Menu' ); 
		}

	

	}

endif;
?>
<?php
// Put post thumbnails into rss feed
function wpfme_feed_post_thumbnail($content) {
	global $post;
	if(has_post_thumbnail($post->ID)) {
		$content = '' . $content;
	}
	return $content;
}
add_filter('the_excerpt_rss', 'wpfme_feed_post_thumbnail');
add_filter('the_content_feed', 'wpfme_feed_post_thumbnail');
?>
<?php
// Custom Pagination
function custom_link_pages($args = '') {
        $defaults = array(
                'before' => '<li>' . __('Pages:'), 'after' => '</li>',
                'link_before' => '', 'link_after' => '',
                'next_or_number' => 'number', 'nextpagelink' => __('Next page'),
                'previouspagelink' => __('Previous page'), 'pagelink' => '%',
                'echo' => 1
        );

        $r = wp_parse_args( $args, $defaults );
        $r = apply_filters( 'wp_link_pages_args', $r );
        extract( $r, EXTR_SKIP );

        global $page, $numpages, $multipage, $more, $pagenow;
        $output = '';
        if ( $multipage ) {
                if ( 'number' == $next_or_number ) {
                        $output .= $before;
                        for ( $i = 1; $i < ($numpages+1); $i = $i + 1 ) {
                                $j = str_replace('%',$i,$pagelink);
                                $output .= ' ';
                                if ( ($i != $page) || ((!$more) && ($page==1)) ) {
                                        $output .= '<li>' . _wp_link_page($i);
                                } elseif ( $i == $page ) {
                                    $output .= '<li><a href="#">';
                                }
                                $output .= $link_before . $j . $link_after;
                                if ( ($i != $page) || ( $i == $page ) || ((!$more) && ($page==1)) )
                                        $output .= '</a></li>';
                        }
                        $output .= $after;
                } else {
                        if ( $more ) {
                                $output .= $before;
                                $i = $page - 1;
                                if ( $i && $more ) {
                                        $output .= _wp_link_page($i);
                                        $output .= $link_before. $previouspagelink . $link_after . '</a></li>';
                                }
                                $i = $page + 1;
                                if ( $i <= $numpages && $more ) {
                                        $output .= _wp_link_page($i);
                                        $output .= $link_before. $nextpagelink . $link_after . '</a></li>';
                                }
                                $output .= $after;
                        }
                }
        }

        if ( $echo )
                echo $output;

        return $output;
}

// Custom Next/Previous Page
add_filter('wp_link_pages_args', 'wp_link_pages_args_prevnext_add');
/**
 * Add prev and next links to a numbered link list
 */
function wp_link_pages_args_prevnext_add($args)
{
    global $page, $numpages, $more, $pagenow;

    if (!$args['next_or_number'] == 'next_and_number')
        return $args; # exit early

    $args['next_or_number'] = 'number'; # keep numbering for the main part
    if (!$more)
        return $args; # exit early

    if($page-1) # there is a previous page
        $args['before'] .= '<li>' . _wp_link_page($page-1)
        . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a></li>'
    ;

    if ($page<$numpages) # there is a next page
        $args['after'] = '<li>' . _wp_link_page($page+1)
        . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a></li>'
        . $args['after']
    ;

    return $args;
}
?>
<?php

function add_class_the_tags($html){
    $postid = get_the_ID();
    $html = str_replace('<a','<a class="btn btn-mini btn-info"',$html);
    return $html;
}
add_filter('the_tags','add_class_the_tags',10,1);
?>
<?php
// adds the colorbox jQuery code
function insert_colorbox_js() {
?>
    <script type="text/javascript">
    // <![CDATA[
    jQuery(document).ready(function($){
        $("a[rel='colorbox']").colorbox({
                transition:'elastic', 
                opacity:'0.7', 
                maxHeight:'90%'
        });
        $("a.gallery").colorbox({
                rel:'group'
        });
        $("a[rel='colorboxvideo']").colorbox({
                iframe:true, 
                transition:'elastic', 
                opacity:'0.7',
                innerWidth:'60%', 
                innerHeight:'80%'
        });
    });  
    // ]]>
    </script>
<?php
}
add_action( 'wp_head', 'insert_colorbox_js' );
?>
<?php
// automatically add colorbox rel attributes to embedded images
function insert_colorbox_rel($content) {
	$pattern = '/<a(.*?)href="(.*?).(bmp|gif|jpeg|jpg|png)"(.*?)>/i';
  	$replacement = '<a$1href="$2.$3" rel=\'colorbox\'$4>';
	$content = preg_replace( $pattern, $replacement, $content );
	return $content;
}
add_filter( 'the_content', 'insert_colorbox_rel' );
?>
<?php
/*
 * This is an example of how to override a default filter
 * for 'textarea' sanitization and $allowedposttags + embed and script.
 */
add_action('admin_init','optionscheck_change_santiziation', 100);
function optionscheck_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'custom_sanitize_textarea' );
}
function custom_sanitize_textarea($input) {
    global $allowedposttags;
    $custom_allowedtags["embed"] = array(
      "src" => array(),
      "type" => array(),
      "allowfullscreen" => array(),
      "allowscriptaccess" => array(),
      "height" => array(),
          "width" => array()
      );
      $custom_allowedtags["script"] = array();
      $custom_allowedtags = array_merge($custom_allowedtags, $allowedposttags);
      $output = wp_kses( $input, $custom_allowedtags);
    return $output;
}
?>