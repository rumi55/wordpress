<?php   
/*
Plugin Name: All Around Slider
Plugin URI: http://goo.gl/HqQ4dR
Description: All Around – jQuery Content Slider / Carousel
Author: br0
Version: 1.0.4
Author URI: http://codecanyon.net/item/all-around-wordpress-content-slider-carousel/5266981 */

$all_around_version='1.0.4';

if (isset($_GET['get_version'])) {echo $all_around_version; exit;}

if (!class_exists("all_around_admin")) {
	require_once dirname( __FILE__ ) . '/all_around_wp_class.php';	
	$all_around = new all_around_wrapper_admin (__FILE__, 'all_around', 'All Around Slider', $all_around_version);
}


?>