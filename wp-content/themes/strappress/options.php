<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {
	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {

	// Menu position
	
	// Buttons
	$btn_color = array("default" => "Default Gray","primary" => "Blue","info" => "Baby Blue","success" => "Green","warning" => "Yellow","danger" => "Red","inverse" => "Black");
	$btn_size = array("mini" => "Mini","small" => "Small","default" => "Medium","large" => "Large");

	// fixed or scroll position
	$fixed_scroll = array("fixed" => "Fixed","static" => "Static");

	// Menu color
	$nav_color = array("grey" => "Grey","black" => "Black");

	// Homepage Latest Blog or Featured Image
	$hp_array = array(
		'featured' => __('Featured Hero Unit', 'responsive'),
		'latest' => __('Latest Blog Post', 'responsive')
		
	);
	
	
	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_bloginfo('template_directory') . '/images/';
		
	$options = array();

	$options[] = array( "name" => "General Settings",
						"type" => "heading");

	$options[] = array( "name" => "Logo",
						"desc" => "Upload image for Logo",
						"id" => "logo_upload",
						"type" => "upload");

	$options[] = array( "name" => "Menu Position",
						"desc" => "Fixed to the top of the window or scroll with content.",
						"id" => "nav_position",
						"std" => "fixed",
						"type" => "select",
						"class" => "mini", //mini, tiny, small
						"options" => $fixed_scroll);

	$options[] = array( "name" => "Menu Color",
						"desc" => "Deafult Grey or Black Styling",
						"id" => "nav_color",
						"std" => "grey",
						"type" => "select",
						"class" => "mini", //mini, tiny, small
						"options" => $nav_color);

	$options[] = array( "name" => "Search bar",
						"desc" => "Show search bar in top nav",
						"id" => "search_bar",
						"std" => "1",
						"type" => "checkbox");
						
	$options[] = array( "name" => "Link Color",
						"desc" => "Default used if no color is selected.",
						"id" => "link_color",
						"std" => "",
						"type" => "color");
					
	$options[] = array( "name" => "Link:hover Color",
						"desc" => "Default used if no color is selected.",
						"id" => "link_hover_color",
						"std" => "",
						"type" => "color");
						
	$options[] = array( "name" => "Link:active Color",
						"desc" => "Default used if no color is selected.",
						"id" => "link_active_color",
						"std" => "",
						"type" => "color");

	$options[] = array( "name" => "Breadcrumbs",
						"desc" => "Use breadcrumbs on pages",
						"id" => "breadcrumbs",
						"std" => "1",
						"type" => "checkbox");

	$options[] = array( "name" => "Copyright Text",
						"desc" => "",
						"id" => "copyright_text",
						"std" => "",
						"type" => "textarea");

	$options[] = array( "name" => "Powered By Text",
						"desc" => "",
						"id" => "powered_text",
						"std" => "",
						"type" => "textarea");

	$options[] = array( "name" => "Scroll to Top",
						"desc" => "Use Scroll to Top Arrow",
						"id" => "scroll_arrow",
						"std" => "1",
						"type" => "checkbox");
			

	$options[] = array( "name" => "Homepage",
						"type" => "heading");
	
	$options[] = array(
						'name' => __('Home Hero Unit', 'responsive'),
						'desc' => __('Featured Content or Latest Post', 'responsive'),
						'id' => 'hero_radio',
						'std' => 'featured',
						'type' => 'radio',
						'options' => $hp_array);
	
	$options[] = array( "name" => "Featured Content Heading",
						"desc" => "This is the heading of the featured content",
						"id" => "featured_heading",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Sub Heading",
						"desc" => "This is the sub heading of the featured content",
						"id" => "home_subheadline",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Content",
						"desc" => "Featured content copy",
						"id" => "home_content_area",
						"std" => "",
						"type" => "textarea");

	$options[] = array( "name" => "Call to Action Button",
						"desc" => "Check to display the button",
						"id" => "display_button",
						"std" => "1",
						"type" => "checkbox");

	$options[] = array( "name" => "Button Text",
						"desc" => "text that will display on button",
						"id" => "cta_text",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Button URL",
						"desc" => "URL for the button",
						"id" => "cta_url",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Button Size",
						"desc" => "size of call to action button",
						"id" => "cta_size",
						"std" => "",
						"type" => "select",
						"options" => $btn_size);

	$options[] = array( "name" => "Call to Action Button Full Width",
						"desc" => "Check to make button full width",
						"id" => "button_block",
						"std" => "1",
						"type" => "checkbox");


	$options[] = array( "name" => "Button Color",
						"desc" => "color of call to action button",
						"id" => "cta_color",
						"std" => "",
						"type" => "select",
						"options" => $btn_color);

	$options[] = array( "name" => "Featured Content",
						"desc" => "Paste your video or image source",
						"id" => "featured_content",
						"std" => "",
						"type" => "textarea");

	$options[] = array( "name" => "Portfolio",
						"type" => "heading");

	$options[] = array( "name" => "Portfolio Columns",
						"desc" => "Select the number of columns (two, three, or four)",
						"id" => "portfolio_column",
						"std" => "three",
						"type" => "images",
						"options" => array(
							'two' => $imagepath . '2c.png',
							'three' => $imagepath . '3c.png',
							'four' => $imagepath . '4c.png')
						);

	$options[] = array( "name" => "Filter Buttons",
						"desc" => "Use filter buttons",
						"id" => "filter_btns",
						"std" => "1",
						"type" => "checkbox");

	$options[] = array( "name" => "Filter Button Size",
						"desc" => "size of filter button",
						"id" => "f_btn_size",
						"std" => "",
						"type" => "select",
						"options" => $btn_size);

	$options[] = array( "name" => "Filter Button Color",
						"desc" => "color of filter button",
						"id" => "f_btn_color",
						"std" => "",
						"type" => "select",
						"options" => $btn_color);

	$options[] = array( "name" => "Project Buttons",
						"desc" => "Use project buttons",
						"id" => "project_btns",
						"std" => "1",
						"type" => "checkbox");

	$options[] = array( "name" => "Project Button Full Width",
						"desc" => "Check to make button full width",
						"id" => "p_button_block",
						"std" => "1",
						"type" => "checkbox");

	$options[] = array( "name" => "Project Button Size",
						"desc" => "size of project button",
						"id" => "p_btn_size",
						"std" => "",
						"type" => "select",
						"options" => $btn_size);

	$options[] = array( "name" => "Project Button Color",
						"desc" => "color of project button",
						"id" => "p_btn_color",
						"std" => "",
						"type" => "select",
						"options" => $btn_color);

	$options[] = array( "name" => "Button Text",
						"desc" => "Text for Button",
						"id" => "p_button_text",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Project Titles",
						"desc" => "Display project titles",
						"id" => "project_title",
						"std" => "1",
						"type" => "checkbox");
						
	$options[] = array( "name" => "Social",
						"type" => "heading");

	$options[] = array( "name" => "Social Icons",
						"desc" => "Show social icons in top nav",
						"id" => "header_social",
						"std" => "0",
						"type" => "checkbox");

	$options[] = array( "name" => "Social Icons",
						"desc" => "Show social icons in footer",
						"id" => "footer_social",
						"std" => "1",
						"type" => "checkbox");

	$options[] = array( "name" => "Twitter",
						"desc" => "Complete URL",
						"id" => "twitter_url",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Facebook",
						"desc" => "Complete URL",
						"id" => "fb_url",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Pinterest",
						"desc" => "Complete URL",
						"id" => "pinterest_url",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "LinkedIn",
						"desc" => "Complete URL",
						"id" => "linkedin_url",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Google+",
						"desc" => "Complete URL",
						"id" => "google_url",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "Github",
						"desc" => "Complete URL",
						"id" => "github_url",
						"std" => "",
						"type" => "text");

	$options[] = array( "name" => "RSS Feed",
						"desc" => "Complete URL",
						"id" => "rss_url",
						"std" => "",
						"type" => "text");

	
						
				
	return $options;
}

/* 
 * This is an example of how to add custom scripts to the options panel.
 * This example shows/hides an option when a checkbox is clicked.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function($) {

	$('#example_showhidden').click(function() {
  		$('#section-example_text_hidden').fadeToggle(400);
	});
	
	if ($('#example_showhidden:checked').val() !== undefined) {
		$('#section-example_text_hidden').show();
	}
	
});
</script>

<?php
}