<?php
/*
Plugin Name: Progress map by Codespacing
Description: <strong>Progress map</strong> is a Wordpress plugin for location listings. With this plugin, your locations will be listed on both Google map (as markers) and a carousel (as locations details), this last will be related to the map, which means that the selected item in the carousel will target its location in the map and vice versa.
Version: 1.3
Author: Codespacing
Author URI: 
*/

class CodespacingProgressMap{

    private $plugin_path;
    private $plugin_url;
    private $l10n;
    private $wpsf;

    function __construct() 
    {	
       
	    $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        $this->l10n = 'wp-settings-framework';
		 
        // Include and create a new WordPressSettingsFramework
        require_once( $this->plugin_path .'wp-settings-framework.php' );
        $this->wpsf = new WordPressSettingsFramework( $this->plugin_path .'settings/codespacing-progress-map.php' );
		
		// Call .js and .css files
		add_action('wp_enqueue_scripts', array(&$this, 'codespacing_progress_map_styles'));
		add_action('wp_enqueue_scripts', array(&$this, 'codespacing_progress_map_scripts'));
		
		// Add plugin menu
	    add_action( 'admin_menu', array(&$this, 'admin_menu'), 99 );
       	
		// Add custom header script
		add_filter('wp_head', array(&$this, 'codespacing_progress_map_header_script'));
		
	   	// Call custom functions
	    add_action( 'wpsf_before_settings', array(&$this, 'wpsf_before_settings') );
		add_action( 'wpsf_after_settings', array(&$this, 'wpsf_after_settings') );
		
        // Add an optional settings validation filter (recommended)
        add_filter( $this->wpsf->get_option_group() .'_settings_validate', array(&$this, 'validate_settings') );		
		
		// Add custom links to plugin instalation area
		add_filter( 'plugin_row_meta', array(&$this, 'plugin_meta_links'), 10, 2 );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array(&$this, 'add_plugin_action_links') );
	
		// Add "Location" meta box to "Add" custom post type area
		add_action('admin_init', array(&$this, 'codespacing_progress_map_meta_box'));
		add_action('save_post', array(&$this, 'codespacing_progress_map_insert_meta_box_fields'));
		
		// Ajax function
		add_action('wp_ajax_codespacing_progress_map_get_post_pinpoint', array(&$this, 'codespacing_progress_map_get_post_pinpoint'));
		add_action('wp_ajax_nopriv_codespacing_progress_map_get_post_pinpoint', array(&$this, 'codespacing_progress_map_get_post_pinpoint'));
		
		// Create plugin shortcode
		add_shortcode('codespacing_progress_map', array(&$this, 'codespacing_progress_map_display_shortcode'));
		
		// Call plugin settings
		$this->settings = wpsf_get_settings( $this->plugin_path .'settings/codespacing-progress-map.php' );

		// General settings
			$this->post_type = $this->settings['codespacingprogressmap_generalsettings_post_type'];
			$this->number_of_items = $this->settings['codespacingprogressmap_generalsettings_number_of_items'];
			$this->main_layout = $this->settings['codespacingprogressmap_generalsettings_main_layout'];			
			$this->layout_type = $this->settings['codespacingprogressmap_generalsettings_layout_type'];
			$this->layout_fixed_width = $this->settings['codespacingprogressmap_generalsettings_layout_fixed_width'];
			$this->layout_fixed_height = $this->settings['codespacingprogressmap_generalsettings_layout_fixed_height'];
			
		// Map settings
			$this->map_language = $this->settings['codespacingprogressmap_mapsettings_map_language'];
			$this->center = $this->settings['codespacingprogressmap_mapsettings_map_center'];
			$this->zoom = $this->settings['codespacingprogressmap_mapsettings_map_zoom'];
			$this->mapTypeControl = $this->settings['codespacingprogressmap_mapsettings_mapTypeControl'];
			$this->streetViewControl = $this->settings['codespacingprogressmap_mapsettings_streetViewControl'];
			$this->scrollwheel = $this->settings['codespacingprogressmap_mapsettings_scrollwheel'];
			$this->panControl = $this->settings['codespacingprogressmap_mapsettings_panControl'];					
			$this->zoomControl = $this->settings['codespacingprogressmap_mapsettings_zoomControl'];
			$this->zoomControlType = $this->settings['codespacingprogressmap_mapsettings_zoomControlType'];
			$this->show_infowindow = $this->settings['codespacingprogressmap_mapsettings_show_infowindow'];
			$this->infowindow_type = $this->settings['codespacingprogressmap_mapsettings_infowindow_type'];
			$this->marker_icon = !empty($this->settings['codespacingprogressmap_mapsettings_marker_icon']) ? $this->settings['codespacingprogressmap_mapsettings_marker_icon'] : $this->plugin_url . 'img/pin-blue.png';
			$this->big_cluster_icon = !empty($this->settings['codespacingprogressmap_mapsettings_big_cluster_icon']) ? $this->settings['codespacingprogressmap_mapsettings_big_cluster_icon'] : $this->plugin_url . 'img/big-cluster.png';
			$this->medium_cluster_icon = !empty($this->settings['codespacingprogressmap_mapsettings_medium_cluster_icon']) ? $this->settings['codespacingprogressmap_mapsettings_medium_cluster_icon'] : $this->plugin_url . 'img/medium-cluster.png';
			$this->small_cluster_icon = !empty($this->settings['codespacingprogressmap_mapsettings_small_cluster_icon']) ? $this->settings['codespacingprogressmap_mapsettings_small_cluster_icon'] : $this->plugin_url . 'img/small-cluster.png'; 
			$this->cluster_text_color = empty($this->settings['codespacingprogressmap_mapsettings_cluster_text_color']) ? '#ffffff' : $this->settings['codespacingprogressmap_mapsettings_cluster_text_color'];
			$this->zoom_in_icon = $this->settings['codespacingprogressmap_mapsettings_zoom_in_icon'];	
			$this->zoom_in_css = $this->settings['codespacingprogressmap_mapsettings_zoom_in_css'];	
			$this->zoom_out_icon = $this->settings['codespacingprogressmap_mapsettings_zoom_out_icon'];	
			$this->zoom_out_css = $this->settings['codespacingprogressmap_mapsettings_zoom_out_css'];	
		
		// Carousel settings
			$this->show_carousel = $this->settings['codespacingprogressmap_carouselsettings_show_carousel'];
			$this->carousel_mode = $this->settings['codespacingprogressmap_carouselsettings_carousel_mode'];
			$this->carousel_scroll = ($this->settings['codespacingprogressmap_carouselsettings_carousel_scroll'] != 0) ? $this->settings['codespacingprogressmap_carouselsettings_carousel_scroll'] : 1;
			$this->carousel_animation = $this->settings['codespacingprogressmap_carouselsettings_carousel_animation'];
			$this->carousel_easing = $this->settings['codespacingprogressmap_carouselsettings_carousel_easing'];
			$this->carousel_auto = $this->settings['codespacingprogressmap_carouselsettings_carousel_auto'];
			$this->carousel_wrap = $this->settings['codespacingprogressmap_carouselsettings_carousel_wrap'];	
			$this->scrollwheel_carousel = $this->settings['codespacingprogressmap_carouselsettings_scrollwheel_carousel'];	
		
		// Carousel style
			$this->carousel_css = $this->settings['codespacingprogressmap_carouselstyle_carousel_css'];	
			$this->horizontal_left_arrow_icon = $this->settings['codespacingprogressmap_carouselstyle_horizontal_left_arrow_icon'];	
			$this->horizontal_right_arrow_icon = $this->settings['codespacingprogressmap_carouselstyle_horizontal_right_arrow_icon'];	
			$this->vertical_top_arrow_icon = $this->settings['codespacingprogressmap_carouselstyle_vertical_top_arrow_icon'];	
			$this->vertical_bottom_arrow_icon = $this->settings['codespacingprogressmap_carouselstyle_vertical_bottom_arrow_icon'];	
			$this->items_background = $this->settings['codespacingprogressmap_carouselstyle_items_background'];	
			$this->items_hover_background = $this->settings['codespacingprogressmap_carouselstyle_items_hover_background'];	
			
		// Items Settings
			$this->items_view = $this->settings['codespacingprogressmap_itemssettings_items_view'];
			$this->show_details_btn = $this->settings['codespacingprogressmap_itemssettings_show_details_btn'];
			$this->items_title = $this->settings['codespacingprogressmap_itemssettings_items_title'];
			$this->items_details = $this->settings['codespacingprogressmap_itemssettings_items_details'];
			
			$this->horizontal_item_css = $this->settings['codespacingprogressmap_itemssettings_horizontal_item_css'];
			$this->horizontal_title_css = $this->settings['codespacingprogressmap_itemssettings_horizontal_title_css'];
			$this->horizontal_details_css = $this->settings['codespacingprogressmap_itemssettings_horizontal_details_css'];
			$this->vertical_item_css = $this->settings['codespacingprogressmap_itemssettings_vertical_item_css'];
			$this->vertical_title_css = $this->settings['codespacingprogressmap_itemssettings_vertical_title_css'];
			$this->vertical_details_css = $this->settings['codespacingprogressmap_itemssettings_vertical_details_css'];
			$this->details_btn_css = $this->settings['codespacingprogressmap_itemssettings_details_btn_css'];
			$this->details_btn_text = $this->settings['codespacingprogressmap_itemssettings_details_btn_text'];
			
			$this->horizontal_item_size = $this->settings['codespacingprogressmap_itemssettings_horizontal_item_size'];
				
				if($explode_horizontal_item_size = explode(',', $this->horizontal_item_size)){
					$this->horizontal_item_width = $explode_horizontal_item_size[0];
					$this->horizontal_item_height = $explode_horizontal_item_size[1];
				}else{
					$this->horizontal_item_width = '414';
					$this->horizontal_item_height = '120';
				}
			
			$this->vertical_item_size = $this->settings['codespacingprogressmap_itemssettings_vertical_item_size'];
				
				if($explode_vertical_item_size = explode(',', $this->vertical_item_size)){
					$this->vertical_item_width = $explode_vertical_item_size[0];
					$this->vertical_item_height = $explode_vertical_item_size[1];
				}else{
					$this->vertica_item_width = '414';
					$this->vertica_item_height = '120';
				}
			
			$this->horizontal_image_size = $this->settings['codespacingprogressmap_itemssettings_horizontal_image_size'];
				
				if($explode_horizontal_img_size = explode(',', $this->horizontal_image_size)){
					$this->horizontal_img_width = $explode_horizontal_img_size[0];
					$this->horizontal_img_height = $explode_horizontal_img_size[1];
				}else{
					$this->horizontal_img_width = '170';
					$this->horizontal_img_height = '120';
				}
					
			$this->vertical_image_size = $this->settings['codespacingprogressmap_itemssettings_vertical_image_size'];			
				
				if($explode_vertical_img_size = explode(',', $this->vertical_image_size)){
					$this->vertical_img_width = $explode_vertical_img_size[0];
					$this->vertical_img_height = $explode_vertical_img_size[1];
				}else{
					$this->vertical_img_width = '174';
					$this->vertical_img_height = '90';
				}
			
			// Add Images Size
			if(function_exists('add_image_size')){
				add_image_size( 'cspacing-horizontal-thumbnail', $this->horizontal_img_width, $this->horizontal_img_height, true );
				add_image_size( 'cspacing-vertical-thumbnail', $this->vertical_img_width, $this->vertical_img_height, true );
				add_image_size( 'cspacing-marker-thumbnail', 55, 55, true );
			}
		
			$this->horizontal_details_size = $this->settings['codespacingprogressmap_itemssettings_horizontal_details_size'];
				
				if($explode_horizontal_details_size = explode(',', $this->horizontal_details_size)){
					$this->horizontal_details_width = $explode_horizontal_details_size[0];
					$this->horizontal_details_height = $explode_horizontal_details_size[1];
				}else{
					$this->horizontal_details_width = '167';
					$this->horizontal_details_height = '120';
				}
			
			$this->vertical_details_size = $this->settings['codespacingprogressmap_itemssettings_vertical_details_size'];
				
				if($explode_vertical_img_size = explode(',', $this->vertical_details_size)){
					$this->vertical_details_width = $explode_vertical_img_size[0];
					$this->vertical_details_height = $explode_vertical_img_size[1];
				}else{
					$this->vertical_details_width = '164';
					$this->vertical_details_height = '50';
				}
		
		// Overlay settings
			$this->show_overlay = $this->settings['codespacingprogressmap_overlaysettings_show_overlay'];
			$this->overlay_path = $this->settings['codespacingprogressmap_overlaysettings_overlay_path'];
			$this->overlay_draggable = $this->settings['codespacingprogressmap_overlaysettings_overlay_draggable'];
			$this->overlay_width = $this->settings['codespacingprogressmap_overlaysettings_overlay_width'];
			$this->overlay_height = $this->settings['codespacingprogressmap_overlaysettings_overlay_height'];
			$this->overlay_top = $this->settings['codespacingprogressmap_overlaysettings_overlay_top'];
   			$this->overlay_left = $this->settings['codespacingprogressmap_overlaysettings_overlay_left'];
			$this->overlay_color = $this->settings['codespacingprogressmap_overlaysettings_overlay_color'];
			$this->overlay_opacity = $this->settings['codespacingprogressmap_overlaysettings_overlay_opacity'];
			$this->overlay_css = $this->settings['codespacingprogressmap_overlaysettings_overlay_css'];
    
	}
    
    function admin_menu()
    {	
		
        add_menu_page( __( 'Progress map', $this->l10n ), __( 'Progress map', $this->l10n ), 'manage_options', 'cs_progress_map_plugin', array(&$this, 'settings_page'), $this->plugin_url.'/img/menu-icon.png' );
        //add_submenu_page( 'wpsf', __( 'Settings', $this->l10n ), __( 'Settings', $this->l10n ), 'update_core', 'wpsf', array(&$this, 'settings_page') );
		
    }
    
    function settings_page()
	{
	    // Your settings page
	    ?>
		<div class="wrap">
			<!--<div id="icon-options-general" class="icon32"></div>
			<h2>Progress map Plugin</h2>-->
			<?php 
			// Output your settings form
			$this->wpsf->settings(); 
			?>
		</div>
		<?php
		
		// Get settings
		//$settings = wpsf_get_settings( $this->plugin_path .'settings/codespacing-map-location.php' );
		//echo '<pre>'.print_r($settings,true).'</pre>';
		
		// Get individual setting
		//$setting = wpsf_get_setting( wpsf_get_option_group( $this->plugin_path .'settings/codespacing-map-location.php' ), 'general', 'text' );
		//var_dump($setting);
	}
	
	// Add settings link to plugin instalation area
	function add_plugin_action_links( $links ) {
	 
		return array_merge(
			array(
				'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=cs_progress_map_plugin">Settings</a>'
			),
			$links
		);
	 
	}	

	// Add plugin site link to plugin instalation area
	function plugin_meta_links( $links, $file ) {
	 
		$plugin = plugin_basename(__FILE__);
	 
		// create link
		if ( $file == $plugin ) {
			return array_merge(
				$links,
				array( '<a href="http://codecanyon.net/user/codespacing">CodeCanyon</a>' )
			);
		}
		return $links;
	 
	}
	
	function validate_settings( $input )
	{
	    // Do your settings validation here
	    // Same as $sanitize_callback from http://codex.wordpress.org/Function_Reference/register_setting
    	return $input;
	}	
	
	// Register & Enqueue CSS files
	function codespacing_progress_map_styles() 
	{
		
		wp_register_style('cspacing_pm_bootstrap_css', $this->plugin_url .'css/bootstrap.css');
		wp_register_style('cspacing_pm_carousel_css', $this->plugin_url .'css/carousel/default.css');
		wp_register_style('cspacing_pm_map_css', $this->plugin_url .'css/style.css');
		wp_register_style('cspacing_pm_loading_css', $this->plugin_url .'css/loading.css');
		wp_register_style('cspacing_pm_mCustomScrollbar_css', $this->plugin_url .'css/jquery.mCustomScrollbar.css');
		
		wp_enqueue_style('cspacing_pm_bootstrap_css');
		wp_enqueue_style('cspacing_pm_carousel_css');
		wp_enqueue_style('cspacing_pm_map_css');
		wp_enqueue_style('cspacing_pm_loading_css');
		wp_enqueue_style('cspacing_pm_mCustomScrollbar_css');
		 
	}	
	
	// Register & Enqueue JS files
	function codespacing_progress_map_scripts()
	{
		 
		wp_register_script('cspacing_pm_jqueryui_js', $this->plugin_url .'js/jquery-ui-1.10.3.custom.min.js', array( 'jquery' ));
		wp_register_script('cspacing_pm_livequery_js', $this->plugin_url .'js/jquery.livequery.min.js', array( 'jquery' ));
		wp_register_script('cspacing_pm_easing', $this->plugin_url .'js/jquery.easing.1.3.js', array( 'jquery' ));
		wp_register_script('cspacing_pm_google_maps_api', 'http://maps.google.com/maps/api/js?v=3.exp&sensor=true&language='.$this->map_language.'', array( 'jquery' ));
		wp_register_script('cspacing_pm_gmap3_js', $this->plugin_url .'js/gmap3.min.js', array( 'jquery' ));
		wp_register_script('cspacing_pm_markerclusterer_js', $this->plugin_url .'js/MarkerClustererPlus.js');
		wp_register_script('cspacing_pm_jcarousel_js', $this->plugin_url .'js/jquery.jcarousel.min.js', array( 'jquery' ));
		wp_register_script('cspacing_pm_mCustomScrollbar_js', $this->plugin_url .'js/jquery.mCustomScrollbar.min.js', array( 'jquery' ));
		wp_register_script('cspacing_pm_progress_map_js', $this->plugin_url .'js/progress_map.js', array( 'jquery' ));			
		
		wp_localize_script('cspacing_pm_progress_map_js', 'progress_map_vars', array(
		
			'ajaxurl' => get_bloginfo('url') . '/wp-admin/admin-ajax.php',
			'plugin_url' => $this->plugin_url,
			'number_of_items' => $this->number_of_items,
			'center' => $this->center,
			'zoom' => $this->zoom,
			'scrollwheel' => $this->scrollwheel,
			'panControl' => $this->panControl,
			'mapTypeControl' => $this->mapTypeControl,
			'streetViewControl' => $this->streetViewControl,
			'zoomControl' => $this->zoomControl,
			'zoomControlType' => $this->zoomControlType,
			'marker_icon' => $this->marker_icon,
			'big_cluster_icon' => $this->big_cluster_icon,
			'medium_cluster_icon' => $this->medium_cluster_icon,
			'small_cluster_icon' => $this->small_cluster_icon,
			'cluster_text_color' => $this->cluster_text_color,
			'items_view' => $this->items_view,
			'show_carousel' => $this->show_carousel,
			'carousel_scroll' => $this->carousel_scroll,
			'carousel_wrap' => $this->carousel_wrap,
			'carousel_auto' => $this->carousel_auto,
			'carousel_mode' => $this->carousel_mode,
			'carousel_animation' => $this->carousel_animation,
			'carousel_easing' => $this->carousel_easing,
			'main_layout' => $this->main_layout,
			'horizontal_item_css' => $this->horizontal_item_css,
			'horizontal_item_width' => $this->horizontal_item_width,
			'horizontal_item_height' => $this->horizontal_item_height,
			'horizontal_img_width' => $this->horizontal_img_width,
			'horizontal_details_width' => $this->horizontal_details_width,
			'horizontal_img_height' => $this->horizontal_img_height,
			'vertical_item_css' => $this->vertical_item_css,
			'vertical_item_width' => $this->vertical_item_width,
			'vertical_item_height' => $this->vertical_item_height,			
			'vertical_img_width' => $this->vertical_img_width,
			'vertical_img_height' => $this->vertical_img_height,	
			'vertical_details_height' => $this->vertical_details_height,
			'show_infowindow' => $this->show_infowindow,
			'infowindow_type' => $this->infowindow_type,
			'overlay_draggable' => $this->overlay_draggable,				
			'carousel_css' => $this->carousel_css,
			'horizontal_left_arrow_icon' => $this->horizontal_left_arrow_icon,
			'horizontal_right_arrow_icon' => $this->horizontal_right_arrow_icon,
			'vertical_top_arrow_icon' => $this->vertical_top_arrow_icon,
			'vertical_bottom_arrow_icon' => $this->vertical_bottom_arrow_icon,
			'items_background' => $this->items_background,
			'items_hover_background' => $this->items_hover_background,
			'details_btn_css' => $this->details_btn_css,
			'scrollwheel_carousel' => $this->scrollwheel_carousel,
		));
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('cspacing_pm_jqueryui_js');
		wp_enqueue_script('cspacing_pm_livequery_js');
		wp_enqueue_script('cspacing_pm_easing');
		wp_enqueue_script('cspacing_pm_google_maps_api');
		wp_enqueue_script('cspacing_pm_gmap3_js');
		wp_enqueue_script('cspacing_pm_markerclusterer_js');	
		wp_enqueue_script('cspacing_pm_jcarousel_js');
		wp_enqueue_script('cspacing_pm_mCustomScrollbar_js');
		wp_enqueue_script('cspacing_pm_progress_map_js');
		
	}
	
	// Create "Location" meta box 
	function codespacing_progress_map_meta_box()
	{ 
	
		// Attachment
		add_meta_box(
			'codespacing_progress_map_meta_box_form',
			'Location',
			array(&$this, 'codespacing_progress_map_meta_box_form'),
			''.$this->post_type.'',
			'side'
		);
	
	}
	
	// Create "Location" form
	function codespacing_progress_map_meta_box_form()
	{
		
		global $post;

		wp_nonce_field($this->plugin_path, 'codespacing_progress_map_meta_box_form_nonce');
		
		$html = '';
		
		$html .= '<div style="padding:5px 0 10px 0; margin:5px 0;">';
			
			$html .= '<div class="no_address_found"></div>';
			
			$html .= '<div style="border-bottom:1px solid #ededed; padding-bottom:10px; margin-bottom:10px;">';
			
				$html .= '<label for="codespacing_progress_map_address" style="font-weight:bold; padding:5px 50px 0 0; width:252px; display:block; float:left">Enter address</label>';
					
					$html .= '<input type="text" name="codespacing_progress_map_address" id="codespacing_progress_map_address" value="'.get_post_meta($post->ID, 'codespacing_progress_map_address', true).'" style="width:255px; margin-bottom:5px;" />';
					
					$html .= '<input type="button" value="Get Pinpoint" id="codespacing_copypinpoint" class="button button-primary button-large" style="float:right;" />';
					
					$html .= '<input type="button" class="button tagadd button-large" id="codespacing_search_address" value="Search" style="margin-right:5px; float:right;" />';
					
					$html .= '<div style="clear:both"></div>';
					
			$html .= '</div>';
				
			$html .= '<div style="float:left; margin-right:16px; width:120px;">';
			
				$html .= '<label for="codespacing_progress_map_lat" style="font-weight:bold; padding:5px 50px 0 0; width:115px; display:block; float:left">Latitude</label>';
		
					$html .= '<input type="text" name="codespacing_progress_map_lat" id="codespacing_progress_map_lat" value="'.get_post_meta($post->ID, 'codespacing_progress_map_lat', true).'" style="width:115px;" />';
			
			$html .= '</div>';
			
			$html .= '<div style="float:left; width:120px;">';
			
			$html .= '<label for="codespacing_progress_map_lng" style="font-weight:bold; padding:5px 50px 0 0; width:115px; display:block; float:left">Longitude</label>';
	
				$html .= '<input type="text" name="codespacing_progress_map_lng" id="codespacing_progress_map_lng" value="'.get_post_meta($post->ID, 'codespacing_progress_map_lng', true).'" style="width:115px;" />';
			
			$html .= '</div>';
			
			$html .= '<div style="clear:both"></div>';
			
		$html .= '</div>';

		$post_lat = get_post_meta($post->ID, 'codespacing_progress_map_lat', true);
		$post_lng = get_post_meta($post->ID, 'codespacing_progress_map_lng', true);

		if(empty($post_lat) && empty($post_lng))
		{
			
			$post_lat = 37.09024;
			$post_lng = -95.71289100000001;
			
		}
                            
		?>
			
		<script>
        
        jQuery(document).ready(function($){
                                        
            var map;
			
            var error_address1 = 'We could not understand the location ';
			var error_address2 = '<br /><br /><u>Suggestions</u>:';
				error_address2 += '<ul>'
					error_address2 += '<li>- Make sure all street and city names are spelled correctly.</li>';
					error_address2 += '<li>- Make sure your address includes a city and state.</li>';
					error_address2 += '<li>- Try entering a zip code.</li>';
				error_address2 += '</ul><hr />';

            google.maps.visualRefresh = true;
            
            map = new GMaps({
                el: '#codespacing_widget_map_container',
                lat: <?php echo $post_lat; ?>,
                lng: <?php echo $post_lng; ?>,
                zoom: 9
            });
            map.addMarker({
                lat: <?php echo $post_lat; ?>,
                lng: <?php echo $post_lng; ?>,
                draggable: true,
            });

            $('input#codespacing_search_address').livequery('click', function(e){
				e.preventDefault();
				GMaps.geocode({
				  address: $('input#codespacing_progress_map_address').val().trim(),
				  callback: function(results, status){
					if(status=='OK'){						
					  $('.no_address_found').empty();						 
					  var latlng = results[0].geometry.location;
					  map.removeMarkers();
					  map.setCenter(latlng.lat(), latlng.lng());
					  map.addMarker({
						lat: latlng.lat(),
						lng: latlng.lng(),
						draggable: true,
					  });
					}else $('.no_address_found').html(error_address1 + '<strong>' + $('input#codespacing_progress_map_address').val().trim() + '</strong>' + error_address2);
				  }
				});
				return false;
            });
                          
            $('input#codespacing_progress_map_address').keypress(function(e){
                if (e.keyCode == 13) {
                    e.preventDefault();
                    GMaps.geocode({
                      address: $(this).val().trim(),
                      callback: function(results, status){
                        if(status=='OK'){	
						  $('.no_address_found').empty();
                          var latlng = results[0].geometry.location;
                          map.removeMarkers();
                          map.setCenter(latlng.lat(), latlng.lng());
                          map.addMarker({
                            lat: latlng.lat(),
                            lng: latlng.lng(),
                            draggable: true,
                          });
                        }else $('.no_address_found').html(error_address1 + '<strong>' + $('input#codespacing_progress_map_address').val().trim() + '</strong>' + error_address2);
                      }
                    });
                    return false;
                }		
            });
              
            $('input#codespacing_copypinpoint').click(function(e){
                e.preventDefault();
                $("input#codespacing_progress_map_lat").val(map.markers[0].getPosition().lat());
                $("input#codespacing_progress_map_lng").val(map.markers[0].getPosition().lng());
            });

        });
        </script>
            
        <?php 
		
		$html .= '<div id="location_container" style="width:252px; margin-top:20px;">'; 
			
			$html .= '<div id="codespacing_widget_map_container" style="display:block; width:252px; height:250px; margin:0 auto; border:1px solid #d9d9d9;"></div>';
			
		$html .= '</div>';
		
		echo $html;
			
	}
	
	// Save "Location" data (lat, lng)
	function codespacing_progress_map_insert_meta_box_fields()
	{
		
		global $post;
	
		/* --- security verification --- */
		if(!isset($_POST['codespacing_progress_map_meta_box_form_nonce']) || !wp_verify_nonce($_POST['codespacing_progress_map_meta_box_form_nonce'], $this->plugin_path)) {
		  return;
		} // end if

		if(isset($_POST['codespacing_progress_map_address'])) update_post_meta($post->ID, "codespacing_progress_map_address", $_POST["codespacing_progress_map_address"]);		  
		if(isset($_POST['codespacing_progress_map_lat'])) update_post_meta($post->ID, "codespacing_progress_map_lat", $_POST["codespacing_progress_map_lat"]);
		if(isset($_POST['codespacing_progress_map_lng'])) update_post_meta($post->ID, "codespacing_progress_map_lng", $_POST["codespacing_progress_map_lng"]);		 

	}
	
	// Parse item custom title
	function codespacing_progress_map_items_title($post_id)
	{
		// Custom title structure
		$post_meta = $this->items_title;
		
		// Init vars
		$items_title = '';		
		$items_title_lenght = 0;
		
		// If no custom title is set ...
		// ... Call item original title
		if(empty($post_meta)){
			
			$items_title = get_the_title($post_id);
		
		// If custom title is set ...	
		}else{
			
			// ... Get post metas from custom title structure
			$explode_post_meta = explode('][', $post_meta);
			
			// Loop throught post metas
			foreach($explode_post_meta as $single_post_meta){
				
				// Clean post meta name 
				$single_post_meta = str_replace(array('[', ']'), '', $single_post_meta);
				
				// Get the first two letters from post meta name
				$check_string = substr($single_post_meta, 0, 2);
				
				// Separator case
				if($check_string === 's='){
					
					// Add separator to title
					$items_title .= str_replace('s=', '', $single_post_meta);
				
				// Lenght case	
				}elseif($check_string === 'l='){
					
					// Define title lenght
					$items_title_lenght = str_replace('l=', '', $single_post_meta);
				
				// Empty space case
				}elseif($single_post_meta == '-'){
					
					// Add space to title
					$items_title .= ' ';
				
				// Post metas case		
				}else{
					
					// Add post meta value to title
					$items_title .= get_post_meta($post_id, $single_post_meta, true);
						
				}
				
			}
			
			// If custom title is empty (Maybe someone will type something by error), call original title
			if(empty($items_title)) $items_title = get_the_title($post_id);
			
		}
		
		// Show title as title lenght is defined	
		if($items_title_lenght > 0) $items_title = substr($items_title, 0, $items_title_lenght);
		
		return $items_title;
		
	}
	
	// Parse item custom details
	function codespacing_progress_map_items_details($post_id)
	{
		
		// Custom details structure
		$post_meta = $this->items_details;		
		
		// Init vars
		$items_details = '';
		
		// If new structure is set ...
		if(!empty($post_meta)){
			
			// ... Get post metas from custom details structure
			$explode_post_meta = explode('][', $post_meta);
			
			// Loop throught post metas
			foreach($explode_post_meta as $single_post_meta){
				
				// Clean post meta name
				$single_post_meta = str_replace(array('[', ']'), '', $single_post_meta);
				
				// Get the first two letters from post meta name
				$check_string = substr($single_post_meta, 0, 2);
				
				// Separator case
				if($check_string === 's='){
					
					// Add separator to details
					$separator = str_replace('s=', '', $single_post_meta);
					
					$separator == 'br' ? $items_details .= '<br />' : $items_details .= $separator;
				
				// Meta post title OR Label case	
				}elseif($check_string === 't='){
					
					// Add label to details
					$items_details .= str_replace('t=', '', $single_post_meta);
				
				// Lenght case		
				}elseif($check_string === 'l='){
					
					// Define details lenght
					$items_details_lenght = str_replace('l=', '', $single_post_meta);
				
				// Empty space case
				}elseif($single_post_meta == '-'){
					
					// Add space to details
					$items_details .= ' ';
				
				// Post metas case			
				}else{
					
					// Add post metas to details
					$items_details .= get_post_meta($post_id, $single_post_meta, true);
						
				}
				
			}						
			
		}
		
		// If no custom detils structure is set ...
		if(empty($post_meta) || empty($items_details)){
			
			// Get original post details
			$post_record = get_post($post_id, ARRAY_A);
			
			// Post content
			$post_content = $post_record['post_content'];
			
			// Post excerpt
			$post_excerpt = $post_record['post_excerpt'];
			
			// Excerpt is recommended
			(!empty($post_excerpt)) ? $items_details = $post_excerpt : $items_details = $post_content;
			
			// Show excerpt/content as details lenght is defined	
			if($items_details_lenght > 0) $items_details = substr($items_details, 0, $items_details_lenght).'&hellip;';
			
		}
				
		return $items_details;
		
	}
	
	// Ajax function: Get Item details 
	function codespacing_progress_map_get_post_pinpoint()
	{

		// Items ID
		$post_id = esc_attr($_POST['post_id']);
		
		// View style (horizontal/vertical)
		$items_view = esc_attr($_POST['items_view']);
		
		// Get items title or custom title		
		$item_title = $this->codespacing_progress_map_items_title($post_id); 
		
		// Create items single page link
		$the_permalink = get_permalink($post_id);
	
		
		/* ========================= */
		/* ==== Horizontal view ==== */
		/* ========================= */
				
		if($items_view == "listview"){
			
			$parameter = array(
				'style' => "width:".$this->horizontal_img_width."px; height:".$this->horizontal_img_height."px;"
			);
			
			// Item thumb
			$post_thumbnail = get_the_post_thumbnail($post_id, 'cspacing-horizontal-thumbnail', $parameter);
							
			$output  = '<div class="item_infos">';
							
								
				/* =========================== */
				/* ==== LTR carousel mode ==== */
				/* =========================== */
				
				if($this->carousel_mode == 'false'){
					
					// Image or Thumb area			
					$output .= '<div class="item_img">';
							
						$output .= $post_thumbnail;
			
					$output .= '</div>';
					
					// Details area
					$output .= '<div class="details_container">';
						
						// "More" Button						
						if($this->show_details_btn == 'yes')
							$output .= '<div class="details_btn" style="'.$this->details_btn_css.'"><a href="'.$the_permalink.'">'.$this->details_btn_text.'</a></div>';
						
						// Item title
						$output .= '<div class="details_title">'.$item_title.'</div>';
						
						// Items details
						$output .= '<div class="details_infos">'.$this->codespacing_progress_map_items_details($post_id).'</div>';
						
					$output .= '</div>';
								
								
				/* =========================== */
				/* ==== RTL carousel mode ==== */
				/* =========================== */
				
				}else{
				
					// Details area
					$output .= '<div class="details_container">';
						
						// "More" Button						
						if($this->show_details_btn == 'yes')
							$output .= '<div class="details_btn" style="'.$this->details_btn_css.'"><a href="'.$the_permalink.'">'.$this->details_btn_text.'</a></div>';
						
						// Item title
						$output .= '<div class="details_title">'.$item_title.'</div>';
						
						// Items details
						$output .= '<div class="details_infos">'.$this->codespacing_progress_map_items_details($post_id).'</div>';
						
					$output .= '</div>';
					
					// Image or Thumb area			
					$output .= '<div class="item_img">';
							
						$output .= $post_thumbnail;
			
					$output .= '</div>';
				
				}
				
				$output .= '<div style="clear:both"></div>';				
				
			$output .= '</div>';
		
		
		/* ======================= */
		/* ==== Vertical view ==== */
		/* ======================= */
				
		}elseif($items_view == "gridview"){					
		
			$parameter = array(
				"width:".$this->vertical_img_width."px; height:".$this->vertical_img_height."px;"
			);
			
			// Item thumb
			$post_thumbnail = get_the_post_thumbnail($post_id, 'cspacing-vertical-thumbnail', $parameter);
			
					
			$output  = '<div class="item_infos">';
				
				// Image or Thumb area								
				$output .= '<div class="item_img">';
						
					$output .= $post_thumbnail;
		
				$output .= '</div>';
				
				// Details area		
				$output .= '<div class="details_container">';
					
					// "More" Button								
					if($this->show_details_btn == 'yes')
						$output .= '<div class="details_btn" style="'.$this->details_btn_css.'"><a href="'.$the_permalink.'">'.$this->details_btn_text.'</a></div>';
					
					// Item title
					$output .= '<div class="details_title">'.$item_title.'</div>';
					
					// Items details
					$output .= '<div class="details_infos">'.$this->codespacing_progress_map_items_details($post_id).'</div>';
					
				$output .= '</div>';
				
				$output .= '<div style="clear:both"></div>';
				
			$output .= '</div>';
			
		}

		echo $output;
		
		die();
		
	}
	
	// Add items to the header!
	function codespacing_progress_map_header_script() {
		
		$header_style = '<style type="text/css">';
		
		// Carousel Style
		
		$header_style .= '.jcarousel-skin-default .jcarousel-container{ '. $this->carousel_css. ' }';
		
		
		// Carousel Items Style
		if($this->items_view == "listview"){
			
			$header_style .= '.details_container{ width:'.$this->horizontal_details_width.'px; height:'.$this->horizontal_details_height.'px; }';
			$header_style .= '.item_img{ width:'.$this->horizontal_img_width.'px; height:'.$this->horizontal_img_height.'px; float:left; }';
			
			$margin = ($this->carousel_mode == 'false') ? 'left' : 'right';
			
			$header_style .= '.details_btn{ margin-'.$margin.':'.($this->horizontal_details_width-80).'px; margin-top:'.($this->horizontal_details_height-40).'px; }';
			$header_style .= '.details_title{ width:240px; '.$this->horizontal_title_css.' }';
			$header_style .= '.details_infos{ width:240px; '.$this->horizontal_details_css.' }';
			
		}else{
			
			$header_style .= '.details_container{ width:'.$this->vertical_details_width.'px; height:'.$this->vertical_details_height.'px; }';
			$header_style .= '.item_img{ width:'.$this->vertical_img_width.'px; height:'.$this->vertical_img_height.'px; }';
			
			$margin = ($this->carousel_mode == 'false') ? 'left' : 'right';
			
			$header_style .= '.details_btn{ margin-'.$margin.':'.($this->vertical_details_width-80).'px; margin-top:'.($this->vertical_details_height-40).'px; }';
			$header_style .= '.details_title{ width:174px; '.$this->vertical_title_css.' }';
			$header_style .= '.details_infos{ width:174px; '.$this->vertical_details_css.' }';
			
		}
		
		
		// Horizontal Right Arrow CSS Style
		
		if(!empty($this->horizontal_right_arrow_icon)){
			
			$header_style .= '		
				.jcarousel-skin-default .jcarousel-next-horizontal:hover,
				.jcarousel-skin-default .jcarousel-next-horizontal:focus {
					background-image: url('.$this->horizontal_right_arrow_icon.') !important;
				}';
		
		}
		
		
		// Horizontal Left Arrow CSS Style
		
		if(!empty($this->horizontal_left_arrow_icon)){
		
			$header_style .= '	
				.jcarousel-skin-default .jcarousel-prev-horizontal:hover, 
				.jcarousel-skin-default .jcarousel-prev-horizontal:focus {
				   background-image: url('.$this->horizontal_left_arrow_icon.') !important;
				}';
			
		}
		
		
		// Vertical Top Arrow CSS Style		
		
		if(!empty($this->vertical_top_arrow_icon)){
					
			$header_style .= '	
				.jcarousel-skin-default .jcarousel-prev-vertical:hover,
				.jcarousel-skin-default .jcarousel-prev-vertical:focus,
				.jcarousel-skin-default .jcarousel-prev-vertical:active {
					background-image: url('.$this->vertical_top_arrow_icon.') !important;
				}';
		
		}


		// Vertical Bottom Arrow CSS Style		
		
		if(!empty($this->vertical_bottom_arrow_icon)){ 
			
			$header_style .= '	
				.jcarousel-skin-default .jcarousel-next-vertical:hover,
				.jcarousel-skin-default .jcarousel-next-vertical:focus,
				.jcarousel-skin-default .jcarousel-next-vertical:active {
				   background-image: url('.$this->vertical_bottom_arrow_icon.') !important;
				}';
				
		}
		
		
		// Zoom-In & Zoom-out CSS Style			
				
		$zoom_in_background  = !empty($this->zoom_in_icon) ? 'background-image:url('.$this->zoom_in_icon.') !important' : '';
		$zoom_out_background = !empty($this->zoom_out_icon) ? 'background-image:url('.$this->zoom_out_icon.') !important' : '';
		
			$header_style .= '.codespacing_map_zoom_in{'.$this->zoom_in_css.' '.$zoom_in_background.'}';
			$header_style .= '.codespacing_map_zoom_out{'.$this->zoom_out_css.' '.$zoom_out_background.'}';
		
		
		// Custom Vertical Carousel CSS
		
		if(($this->main_layout == "mr-cl" || $this->main_layout == "ml-cr") && $this->show_carousel == 'true')
			$header_style .= '.jcarousel-skin-default .jcarousel-container-vertical { height:'.$this->layout_fixed_height.'px !important; }';
                        
		
		// Form Area CSS Style
		if($this->show_overlay == 'yes'){
		
			// Get overlay width, height, color for "content style" infowindow
			(substr($this->overlay_width, -1, 1) === '%' || $this->overlay_width == 'auto') ? $overlay_width = $this->overlay_width : $overlay_width = $this->overlay_width.'px';
			($this->overlay_height == 'auto') ? $overlay_height = 'auto' : $overlay_height = $this->overlay_height.'px';
			($this->overlay_color == '#') ? $overlay_color = 'none' : $overlay_color = $this->overlay_color;
			
			$header_style .= '
				.plugin_overlay{
					position:absolute;
					margin-top:'.$this->overlay_top.'px; 
					margin-left:'.$this->overlay_left.'px; 
					width:'.$overlay_width.'; 
					height:'.$overlay_height.'; 
					background:'.$overlay_color.'; 
					opacity:'.$this->overlay_opacity.'; 
					z-index:9999; 
					overflow:hidden;
				}';

		}
		
		$header_style .= '</style>';
		
		echo $header_style;
		
	}
	
	// Frontend plugin display		
	function codespacing_progress_map_display_shortcode($atts)
	{
		
		// Query limit
		$nbr_items = (empty($this->number_of_items)) ? -1 : $this->number_of_items;
		
		// Call items ids
		$query_args = array( 'post_type' => ''.$this->post_type.'',
							 'post_status' => 'publish',
							 'posts_per_page' => $nbr_items, 
							 'fields' => 'ids');
		
		// Execute query
		$post_ids = serialize(query_posts( $query_args ));
		
		// Reset query
		wp_reset_query();
	 	
		
		// Overide the default post_ids array by the shortcode atts post_ids	
		extract( shortcode_atts( array(
	      'post_ids' => $post_ids,
     	), $atts ) ); 
		
		$post_ids = unserialize($post_ids);
		
		?>
		
        <script>
		
			jQuery(document).ready(function($) { 
				
				// jQuery code for search form area (overlay area)
				<?php if($this->overlay_draggable == 'yes'){ ?>
				
					// Allow Draggable option
					$("div.plugin_overlay").draggable({ containment: "div.codespacing_progress_map_area", scroll: false });
					
				<?php } ?>
				
				// init plugin map
				var plugin_map = $('div#codespacing_progress_map_div');
				
				// Load Map options	
				var map_options = codespacing_load_map_options();
				
				// Activate the new google map visual	
				google.maps.visualRefresh = true;
								
				// Create the map
			  	plugin_map.gmap3({	
				  		  
					map:{
					  
						options: map_options,
					
						callback: function(map){
						
							// Create Pins
							<?php 
					
							$i = 1;
							
							// Count items
							$count_post = count($post_ids);
							
							if($count_post > 0){
								
								// Loop throught items
								foreach($post_ids as $post_id){
									
									// Get lat and lng data
									$lat = get_post_meta($post_id, 'codespacing_progress_map_lat', true);
									$lng = get_post_meta($post_id, 'codespacing_progress_map_lng', true);
									
									// Show items only if lat and lng are not empty
									if(!empty($lat) && !empty($lng)){
										
										$marker_img_array = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'cspacing-marker-thumbnail' );
										$marker_img = $marker_img_array[0];
										$item_title = $this->codespacing_progress_map_items_title($post_id);
										$items_details = $this->codespacing_progress_map_items_details($post_id);
										$the_permalink = get_permalink($post_id); 
										
										?>							

										// Create the pin
										codespacing_new_pin( plugin_map, <?php echo $i; ?>, '<?php echo $post_id; ?>', <?php echo $lat; ?>, <?php echo $lng; ?>, '<?php echo $the_permalink; ?>', '<?php echo $marker_img; ?>', '<?php echo $item_title; ?>', '<?php echo $items_details; ?>' );
										
										<?php 
										
										$i++;			
										
									}
								
								} 
							
							}
							
							?>
						
						}
				  
					}
					
				});								
				
				// Initialize carousel								
				codespacing_init_carousel();
				
				// Clustring markers
				codespacing_clustering(plugin_map);	
								
				// Call zoom-in function
				codespacing_zoom_in($('div.codespacing_map_zoom_in'), plugin_map);
			
				// Call zoom-out function
				codespacing_zoom_out($('div.codespacing_map_zoom_out'), plugin_map);
				
			});
		
		</script> 
		
		<?php
		
		// Define fixed/fullwidth layout height and width	
		if($this->layout_type == 'fixed')
			$layout_style = "width:".$this->layout_fixed_width."px; height:".$this->layout_fixed_height."px;";
		else ($this->main_layout == "mu-cd" || $this->main_layout == "md-cu") ? $layout_style = "width:100%; height:".($this->layout_fixed_height+20)."px;" 
  																		      : $layout_style = "width:100%; height:".$this->layout_fixed_height."px;";
		
		
		$output = '';
		
		// Plugin Container
			
		$output .= '<div class="codespacing_progress_map_area" style="'.$layout_style.'">';
			
			// Plugin Overlay
			
			if($this->show_overlay == 'yes'){
					
				$output .= '<div class="plugin_overlay">';
					
					if(!empty($this->overlay_path)) $output .= file_get_contents($this->overlay_path);
				
				$output .= '</div>';
			
			} 
						
			// Plugin Map
					
								
			/* =============================== */
			/* ==== Map-Up, Carousel-Down ==== */
			/* =============================== */
			
            if($this->main_layout == "mu-cd"){
				
				if($this->items_view == "listview")
					$carousel_height = $this->horizontal_item_height + 8;
					
				elseif($this->items_view == "gridview")
					$carousel_height = $this->vertical_item_height + 8;
				
				$map_height = ($this->show_carousel == 'true') ? $this->layout_fixed_height - $carousel_height . 'px' : $this->layout_fixed_height . 'px';
                
				
				$output .= '<div class="row" style="margin:0; padding:0">';
                				            
					// Zoom Control
					
					if($this->zoomControl == 'true' && $this->zoomControlType == 'customize'){
																	
						$output .= '<div class="codespacing_zoom_container">';
							$output .= '<div class="codespacing_map_zoom_in"></div>';
							$output .= '<div class="codespacing_map_zoom_out"></div>';
						$output .= '</div>';
					
					}
					
					// Map
					
					$output .= '<div id="codespacing_progress_map_div" class="col col-lg-12 col-xs-12 col-sm-12 col-md-12" style="height:'.$map_height.';"></div>';
					
					// Carousel
					
					if($this->show_carousel == 'true'){
						
						$output .= '<div id="codespacing_progress_map_carousel_container" class="col col-lg-12 col-xs-12 col-sm-12 col-md-12" style="margin:0; padding:0; height:auto;">';
						
							$output .= '<ul id="codespacing_progress_map_carousel" class="jcarousel-skin-default" style="height:'.$carousel_height.'px;"></ul>';
						
						$output .= '</div>';
					
					}
					
				$output .= '</div>';
			
								
			/* =============================== */
			/* ==== Carousel-Down, Map-Up ==== */
			/* =============================== */
			
            }elseif($this->main_layout == "md-cu"){
				
				if($this->items_view == "listview")
					$carousel_height = $this->horizontal_item_height + 8;
					
				elseif($this->items_view == "gridview")
					$carousel_height = $this->vertical_item_height + 8;
				
				($this->show_carousel == 'true') ? $map_height = $this->layout_fixed_height - $carousel_height . 'px'
												 : $map_height = $this->layout_fixed_height . 'px';
                
				$output .= '<div class="row" style="margin:0; padding:0">';
					
					// Carousel
					
					if($this->show_carousel == 'true'){
						
						$output .= '<div id="codespacing_progress_map_carousel_container" class="col col-lg-12 col-xs-12 col-sm-12 col-md-12" style="margin:0; padding:0; height:auto;">';
							
							$output .= '<ul id="codespacing_progress_map_carousel" class="jcarousel-skin-default" style="height:'.$carousel_height.'px;"></ul>';
						
						$output .= '</div>';
						
					}
			
					// Zoom Control
					
					if($this->zoomControl == 'true' && $this->zoomControlType == 'customize'){
					
						$output .= '<div class="codespacing_zoom_container">';
							$output .= '<div class="codespacing_map_zoom_in"></div>';
							$output .= '<div class="codespacing_map_zoom_out"></div>';
						$output .= '</div>';
					
					}
					
					// Map
					
					$output .= '<div id="codespacing_progress_map_div" class="col col-lg-12 col-xs-12 col-sm-12 col-md-12" style="height:'.$map_height.';"></div>';
                
				$output .= '</div>';
								
								
			/* ================================== */
			/* ==== Map-Right, Carousel-Left ==== */
			/* ================================== */
			
            }elseif($this->main_layout == "mr-cl"){
                
				if($this->items_view == "listview"){
					
					$carousel_width = $this->horizontal_item_width + 8;
					
				}elseif($this->items_view == "gridview"){
					
					$carousel_width = $this->vertical_item_width + 8;
					
				}
				            
                $output .= '<div style="width:100%; height:100%; margin:0; padding:0;">';
					
					if($this->show_carousel == 'true'){
						
						$map_width = 'auto';
						$margin_left = 'margin-left:'.($carousel_width+20).'px;';
						$zoom_left = 'left:'.($carousel_width+20).'px;';
						
					}else{
						
						$map_width = '100%';
						$margin_left = '';
						$zoom_left = '';
						
					}
					
					// Zoom Control
					
					if($this->zoomControl == 'true' && $this->zoomControlType == 'customize'){
					
						$output .= '<div class="codespacing_zoom_container" style="'.$zoom_left.'">';
							$output .= '<div class="codespacing_map_zoom_in"></div>';
							$output .= '<div class="codespacing_map_zoom_out"></div>';
						$output .= '</div>';
					
					}
					
					if($this->show_carousel == 'true'){ 
						
						// Carousel
						
						$output .= '<div id="codespacing_progress_map_carousel_container" style="left:0; position:absolute; width:auto; height:auto;">';
							
							$output .= '<ul id="codespacing_progress_map_carousel" class="jcarousel-skin-default" style="width:'.$carousel_width.'px; height:'.$this->layout_fixed_height.'px"></ul>';
						
						$output .= '</div>';
						
					}
					
					// Map
					
					$output .= '<div style="height:'.$this->layout_fixed_height.'px; width:'.$map_width.'; position:relative; overflow:hidden; '.$margin_left.'">';
					
						$output .= '<div id="codespacing_progress_map_div" class="gmap3" style="width:100%; height:100%"></div>';
					
					$output .= '</div>';
									
				$output .= '<div>';
							
								
			/* ================================== */
			/* ==== Map-Left, Carousel-Right ==== */
			/* ================================== */
			
            }elseif($this->main_layout == "ml-cr"){
                
				if($this->items_view == "listview"){
					
					$carousel_width = $this->horizontal_item_width + 8;
					
				}elseif($this->items_view == "gridview"){
					
					$carousel_width = $this->vertical_item_width + 8;
					
				}
				
                $output .= '<div style="width:100%; height:100%; margin:0; padding:0;">';
					
					if($this->show_carousel == 'true'){
						
						$map_width = 'auto';
						$margin_right = 'margin-right:'.($carousel_width+20).'px;';
						
					}else{
						
						$map_width = '100%';
						$margin_right = '';
						
					}
					
					// Zoom Control
					
					if($this->zoomControl == 'true' && $this->zoomControlType == 'customize'){
					
						$output .= '<div class="codespacing_zoom_container">';
							$output .= '<div class="codespacing_map_zoom_in"></div>';
							$output .= '<div class="codespacing_map_zoom_out"></div>';
						$output .= '</div>';
					
					}
					
					if($this->show_carousel == 'true'){
                        
                        // Carousel
						
						$output .= '<div id="codespacing_progress_map_carousel_container" style="right:0; position:absolute; width:auto; height:auto;">';
							
							$output .= '<ul id="codespacing_progress_map_carousel" class="jcarousel-skin-default" style="width:'.$carousel_width.'px; height:'.$this->layout_fixed_height.'px"></ul>';
						
						$output .= '</div>';
						
					}
					
					// Map
					
					$output .= '<div style="height:'.$this->layout_fixed_height.'px; width:'.$map_width.'; position:relative; overflow:hidden; '.$margin_right.'">';
					
						$output .= '<div id="codespacing_progress_map_div" class="gmap3" style="width:100%; height:100%"></div>';
					
					$output .= '</div>';
					
				$output .= '<div>';
                
            }
			
		$output .= '</div>';
		
		return $output;
		
	} 
   	
	function wpsf_before_settings(){	
		
		global $wpsf_settings;
		
		$sections = array();

		echo '<div class="codespacing_container" style="padding:0px; margin-top:30px; height:auto; width:761px;">';
			
			echo '<div class="codespacing_header"><img src="'.$this->plugin_url.'img/progress-map.png" /></div>';
			
			echo '<div class="codespacing_menu_container" style="width:auto; float:left; height:auto;">';
				
				echo '<ul class="codespacing_menu">';
					
					if(!empty($wpsf_settings)){
						
						usort($wpsf_settings, array(&$this->wpsf, 'sort_array'));
						
						$first_section = $wpsf_settings[0]['section_id'];
						
						foreach($wpsf_settings as $section){
							
							if(isset($section['section_id']) && $section['section_id'] && isset($section['section_title'])){
								
								echo '<li class="codespacing_li" id='.$section['section_id'].'>'.$section['section_title'].'</li>';
								
								$sections[$section['section_id']] = $section['section_title'];								
								
							}
							
						}
							
					}
				
				echo '</ul>';
				
			echo '</div>';
			 
			echo '<div style="width:500px; height:auto; padding:10px 30px; float:left; border-left: 1px solid #e8ebec; border-top:0px solid #097faa; background:#f7f8f8 url('.$this->plugin_url.'img/bg.png) repeat;">';	
			
	}
	
	function wpsf_after_settings(){
		
			echo '</div>';
			
			echo '<div style="clear:both"></div>';
			
		echo '</div>';	
		
	}
	
}

new CodespacingProgressMap();

?>
