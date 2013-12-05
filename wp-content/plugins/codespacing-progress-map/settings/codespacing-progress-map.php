<?php

global $wpsf_settings;

$plugin_url = plugin_dir_url( __FILE__ );

// General Settings section

$wpsf_settings[] = array(
    'section_id' => 'generalsettings',
    'section_title' => 'General Settings',
    'section_description' => 'The plugin needs a few settings to properly work. Enter your content type, select your main layout &amp; layout type and you\'re done.',
    'section_order' => 1,
    'fields' => array(
        array(
            'id' => 'post_type',
            'title' => 'Content type',
            'desc' => 'Enter for which content types Progress Map should be available during post creation/editing. (Default:post)',
            'type' => 'text',
            'std' => 'post',
        ),	
        array(
            'id' => 'number_of_items',
            'title' => 'Number of items', 
            'desc' => 'Enter the number of items to show on the map. Leave this field empty to get all items.',
            'type' => 'text',
            'std' => '',
        ),			
        array(
            'id' => 'main_layout',
            'title' => 'Main layout',
            'desc' => 'Select main layout alignment.',
            'type' => 'radio',
            'std' => 'mu-cd',
            'choices' => array(
				'mu-cd' => 'Map-Up, Carousel-Down',
				'md-cu' => 'Map-Down, Carousel-Up',
				'mr-cl' => 'Map-Right, Carousel-Left',
				'ml-cr' => 'Map-Left, Carousel-Right',
            )
        ),		
        array(
            'id' => 'layout_type',
            'title' => 'Layout type',
            'desc' => 'Select main layout type.',
            'type' => 'radio',
            'std' => 'full_width',
            'choices' => array(
                'fixed' => 'Fixed',
                'full_width' => 'Full width'
            )
        ),
        array(
            'id' => 'layout_fixed_width',
            'title' => 'Layout width',
            'desc' => 'Select the width (in pixels) of the layout. (Works only for the fixed layout)',
            'type' => 'text',
            'std' => '700'		
        ),	
        array(
            'id' => 'layout_fixed_height',
            'title' => 'Layout height',
            'desc' => 'Select the height (in pixels) of the layout.',
            'type' => 'text',
            'std' => '600'		
        ),	
		
	)
		
);


// Map Settings section

$wpsf_settings[] = array(
    'section_id' => 'mapsettings',
    'section_title' => 'Map Settings',
    'section_description' => 'The maps displayed through the Google Maps API contain UI elements to allow user interaction with the map. These elements are known as controls and you can include variations of these controls in your Google Maps API application.',
    'section_order' => 2,
    'fields' => array(
		array(
            'id' => 'map_language',
            'title' => 'Map language',
            'desc' => 'Localize your Maps API application by altering default language settings. See also the <a href="https://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1" target="_blank">supported list of languages</a>.',
            'type' => 'text',
            'std' => 'en'		
        ),
        array(
            'id' => 'map_center',
            'title' => 'Map center',
            'desc' => 'Enter a center point for the map. (Latitude then Longitude separated by comma). Refer to <a href="https://maps.google.com/" target="_blank">https://maps.google.com/</a> to get you center point.',
            'type' => 'text',
            'std' => '51.53096,-0.121064'		
        ),
		array(
            'id' => 'map_zoom',
            'title' => 'Map zoom',
            'desc' => 'Select the map zoom.',
            'type' => 'select',
            'std' => '12',
            'choices' => array(
				'0' => '0',
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',
				'10' => '10',
				'11' => '11',
				'12' => '12',
				'13' => '13',
				'14' => '14',
				'15' => '15',
				'16' => '16',
				'17' => '17',
				'18' => '18',
				'19' => '19'
            )
        ),
		array(
            'id' => 'ui_elements_section',
            'title' => '<span class="section_sub_title">UI elements</span>',
            'desc' => '',
            'type' => 'custom',
        ),					
        array(
            'id' => 'mapTypeControl',
            'title' => 'Show map type control',
            'desc' => 'The MapType control lets the user toggle between map types (such as ROADMAP and SATELLITE). This control appears by default in the top right corner of the map.',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
        array(
            'id' => 'streetViewControl',
            'title' => 'Show street view control',
            'desc' => 'The Street View control contains a Pegman icon which can be dragged onto the map to enable Street View. This control appears by default in the right top corner of the map.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
        array(
            'id' => 'scrollwheel',
            'title' => 'Scroll wheel',
            'desc' => 'Allow/Disallow the zoom-in and zoom-out of the map using the scroll wheel.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
        array(
            'id' => 'panControl',
            'title' => 'Show pan control',
            'desc' => 'The Pan control displays buttons for panning the map. This control appears by default in the right top corner of the map.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
        array(
            'id' => 'zoomControl',
            'title' => 'Show zoom control',
            'desc' => 'The Zoom control displays a small "+/-" buttons to control the zoom level of the map. This control appears by default in the top left corner of the map on non-touch devices or in the bottom left corner on touch devices.',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
        array(
            'id' => 'zoomControlType',
            'title' => 'Zoom control Type',
            'desc' => 'Select the zoom control type.',
            'type' => 'radio',
            'std' => 'customize',
            'choices' => array(
				'customize' => 'Customize type',
				'default' => 'Default type'
            )
        ),
		array(
            'id' => 'customizations_section',
            'title' => '<span class="section_sub_title">Customizations</span>',
            'desc' => '',
            'type' => 'custom',
        ),							
        array(
            'id' => 'marker_icon',
            'title' => 'Marker image',
            'desc' => 'Upload a new marker image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
        array(
            'id' => 'big_cluster_icon',
            'title' => 'Large cluster image',
            'desc' => 'Upload a new large cluster image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
        array(
            'id' => 'medium_cluster_icon',
            'title' => 'Medium cluster image',
            'desc' => 'Upload a new medium cluster image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
        array(
            'id' => 'small_cluster_icon',
            'title' => 'Small cluster image',
            'desc' => 'Upload a new small cluster image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
		array(
            'id' => 'cluster_text_color',
            'title' => 'Clusters text color',
            'desc' => 'Change the text color of all your clusters.',
            'type' => 'color',
            'std' => ''
        ),
        array(
            'id' => 'zoom_in_icon',
            'title' => 'Zoom-in image',
            'desc' => 'Upload a new zoom-in button image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
		array(
            'id' => 'zoom_in_css',
            'title' => 'Zoom-in CSS',
            'desc' => 'Enter your custom CSS to customize the zoom-in button.',
            'type' => 'textarea',
            'std' => ''
        ),					
        array(
            'id' => 'zoom_out_icon',
            'title' => 'Zoom-out image',
            'desc' => 'Upload a new zoom-out button image. You can always find the original marker at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),		
		array(
            'id' => 'zoom_out_css',
            'title' => 'Zoom-out CSS',
            'desc' => 'Enter your custom CSS to customize the zoom-out button.',
            'type' => 'textarea',
            'std' => ''
        ),			
        array(
            'id' => 'show_infowindow',
            'title' => 'Show Infowindow',
            'desc' => 'Show/Hide the infowindow.',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),	
        array(
            'id' => 'infowindow_type',
            'title' => 'Infowindow type',
            'desc' => 'Select the infowindow type. Switch between the two options to see the difference.',
            'type' => 'radio',
            'std' => 'bubble_style',
            'choices' => array(
				'content_style' => 'Content style',
				'bubble_style' => 'Bubble style'
            )
        ),													
    )
);

	
// Carousel Settings section

$wpsf_settings[] = array(
    'section_id' => 'carouselsettings',
    'section_title' => 'Carousel Settings',
    'section_description' => 'Use this interface to control the carousel settings.',
    'section_order' => 3,
    'fields' => array(
        array(
            'id' => 'show_carousel',
            'title' => 'Show carousel',
            'desc' => 'Show/Hide the map\'s carousel.',
            'type' => 'radio',
            'std' => 'true',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),
        array(
            'id' => 'carousel_mode',
            'title' => 'Mode',
            'desc' => 'Specifies wether the carousel appears in RTL mode or LTR mode.',
            'type' => 'select',
            'std' => 'false',
            'choices' => array(
				'true' => 'Right-to-left',
				'false' => 'Left-to-right'
            )
        ),
        array(
            'id' => 'carousel_scroll',
            'title' => 'Scroll',
            'desc' => 'The number of items to scroll by.',
            'type' => 'text',
            'std' => '1',
        ),
        array(
            'id' => 'carousel_animation',
            'title' => 'Animation',
            'desc' => 'The speed of the scroll animation ("slow" or "fast").',
            'type' => 'select',
            'std' => 'fast',
            'choices' => array(
				'slow' => 'slow',
				'fast' => 'Fast'
            )
        ),
        array(
            'id' => 'carousel_easing',
            'title' => 'Easing',
            'desc' => 'The name of the easing effect that you want to use. <a href="http://jqueryui.com/resources/demos/effect/easing.html" target="_blank">(See jQuery Demo)</a>',
            'type' => 'select',
            'std' => 'faste',
            'choices' => array(
				'linear' => 'linear',
				'swing' => 'swing',
				'easeInQuad' => 'easeInQuad',
				'easeOutQuad' => 'easeOutQuad',
				'easeInOutQuad' => 'easeInOutQuad',
				'easeInCubic' => 'easeInCubic',
				'easeOutCubic' => 'easeOutCubic',
				'easeInOutCubic' => 'easeInOutCubic',
				'easeInQuart' => 'easeInQuart',
				'easeOutQuart' => 'easeOutQuart',
				'easeInOutQuart' => 'easeInOutQuart',
				'easeInQuint' => 'easeInQuint',
				'easeOutQuint' => 'easeOutQuint',
				'easeInOutQuint' => 'easeInOutQuint',
				'easeInExpo' => 'easeInExpo',
				'easeOutExpo' => 'easeOutExpo',
				'easeInOutExpo' => 'easeInOutExpo',
				'easeInSine' => 'easeInSine',
				'easeOutSine' => 'easeOutSine',
				'easeInOutSine' => 'easeInOutSine',
				'easeInCirc' => 'easeInCirc',
				'easeOutCirc' => 'easeOutCirc',
				'easeInOutCirc' => 'easeInOutCirc',
				'easeInElastic' => 'easeInElastic',
				'easeOutElastic' => 'easeOutElastic',
				'easeInOutElastic' => 'easeInOutElastic',
				'easeInBack' => 'easeInBack',
				'easeOutBack' => 'easeOutBack',
				'easeInOutBack' => 'easeInOutBack',
				'easeInBounce' => 'easeInBounce',
				'easeOutBounce' => 'easeOutBounce',
				'easeInOutBounce' => 'easeInOutBounce',
            )
        ),		
        array(
            'id' => 'carousel_auto',
            'title' => 'Auto',
            'desc' => 'Specifies how many seconds to periodically autoscroll the content. If set to 0 (default) then autoscrolling is turned off.',
            'type' => 'text',
            'std' => '0',
        ),
        array(
            'id' => 'carousel_wrap',
            'title' => 'Wrap',
            'desc' => 'Specifies whether to wrap at the first/last item (or both) and jump back to the start/end. If set to null, wrapping is turned off.',
            'type' => 'select',
            'std' => 'circular',
            'choices' => array(
				'first' => 'First',
				'last' => 'Last',
				'both' => 'Both',
				'circular' => 'Circular',
				'null' => 'Null'
            )
        ),
        array(
            'id' => 'scrollwheel_carousel',
            'title' => 'Scroll wheel',
            'desc' => 'Move the carousel with scroll wheel.',
            'type' => 'radio',
            'std' => 'false',
            'choices' => array(
				'true' => 'Yes',
				'false' => 'No'
            )
        ),		
	)

);	


// Carousel Style section

$wpsf_settings[] = array(
    'section_id' => 'carouselstyle',
    'section_title' => 'Carousel Style',
    'section_description' => 'Use this interface to customize the carousel style.',
    'section_order' => 4,
    'fields' => array(
        array(
            'id' => 'carousel_css',
            'title' => 'Carousel CSS',
            'desc' => 'Add your custom CSS to customize the carousel style.',
            'type' => 'textarea',
            'std' => ''
        ),
        array(
            'id' => 'horizontal_left_arrow_icon',
            'title' => 'Horizontal left arrow image',
            'desc' => 'Upload a new left arrow image. You can always find the original arrow at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
        array(
            'id' => 'horizontal_right_arrow_icon',
            'title' => 'Horizontal right arrow image',
            'desc' => 'Upload a new right arrow image. You can always find the original arrow at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
        array(
            'id' => 'vertical_top_arrow_icon',
            'title' => 'Vertical top arrow image',
            'desc' => 'Upload a new top arrow image. You can always find the original arrow at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),
        array(
            'id' => 'vertical_bottom_arrow_icon',
            'title' => 'Vertical bottom arrow image',
            'desc' => 'Upload a new bottom arrow image. You can always find the original arrow at the plugin\'s images directory.',
            'type' => 'file',
            'std' => ''
        ),	
        array(
            'id' => 'items_background',
            'title' => 'Carousel items background color',
            'desc' => 'Use this field to change the default background color of the carousel items.',
            'type' => 'color',
            'std' => '#f9f9f9'
        ),
		array(
            'id' => 'items_hover_background',
            'title' => 'Active carousel items background color',
            'desc' => 'Use this field to change the default background color of the carousel items when one of them is selected.',
            'type' => 'color',
            'std' => '#f3f3f3'
        ),		
	)
);


// Items settings

$wpsf_settings[] = array(
    'section_id' => 'itemssettings',
    'section_title' => 'Items Settings',
    'section_description' => 'Use this interface to customize the carousel items style &amp; content.',
    'section_order' => 5,
    'fields' => array(
        array(
            'id' => 'items_view',
            'title' => 'Items view',
            'desc' => 'Select main view of carousel items.',
            'type' => 'radio',
            'std' => 'listview',
            'choices' => array(
				'listview' => 'Horizontal',
				'gridview' => 'Vertical',
            )
        ),
		array(
            'id' => 'horizontal_item_section',
            'title' => '<span class="section_sub_title">Horizontal view</span>',
            'desc' => '',
            'type' => 'custom',
        ),			
        array(
            'id' => 'horizontal_item_size',
            'title' => 'Items size <sup>(Horizontal view)</sup>',
            'desc' => 'Enter the size (in pixels) of the carousel items. This field is related to the items within the horizontal view. (Width then height separated by comma. Default: 414,120)',
            'type' => 'text',
            'std' => '414,120',
        ),	
        array(
            'id' => 'horizontal_item_css',
            'title' => 'Items CSS <sup>(Horizontal view)</sup>',
            'desc' => 'Enter yout custom css for the carousel items. This field is related to the items within the horizontal view.',
            'type' => 'textarea',
            'std' => '',
        ),									
        array(
            'id' => 'horizontal_image_size',
            'title' => 'Image size <sup>(Horizontal view)</sup>',
            'desc' => 'Enter the image size (in pixels) of the carousel items. This field is related to the items within the horizontal view. (Width then height separated by comma. Default: 174,120)',
            'type' => 'text',
            'std' => '174,120',
        ),		
        array(
            'id' => 'horizontal_details_size',
            'title' => 'Description area size <sup>(Horizontal view)</sup>',
            'desc' => 'Enter the size (in pixels) of the items description area. This field is related to the items within the horizontal view. (Width then height separated by comma. Default: 240,120)',
            'type' => 'text',
            'std' => '240,120',
        ),
        array(
            'id' => 'horizontal_title_css',
            'title' => 'Title css <sup>(Horizontal view)</sup>',
            'desc' => 'Customize the items title area and text by entring your css. This field is related to the items within the horizontal view.',
            'type' => 'textarea',
            'std' => '',
        ),	
        array(
            'id' => 'horizontal_details_css',
            'title' => 'Description css <sup>(Horizontal view)</sup>',
            'desc' => 'Customize the items description area and text by entring your css. This field is related to the items within the horizontal view.',
            'type' => 'textarea',
            'std' => '',
        ),			
		array(
            'id' => 'vertical_item_section',
            'title' => '<span class="section_sub_title">Vertical view</span>',
            'desc' => '',
            'type' => 'custom',
        ),			
        array(
            'id' => 'vertical_item_size',
            'title' => 'Items size <sup>(Vertical view)</sup>',
            'desc' => 'Enter the size (in pixels) of the carousel items. This field is related to the items within the vertical view. (Width then height separated by comma. Default: 174,240)',
            'type' => 'text',
            'std' => '174,240',
        ),	
        array(
            'id' => 'vertical_item_css',
            'title' => 'Items CSS <sup>(Vertical view)</sup>',
            'desc' => 'Enter yout custom css for the carousel items. This field is related to the items within the vertical view.',
            'type' => 'textarea',
            'std' => '',
        ),															
        array(
            'id' => 'vertical_image_size',
            'title' => 'Image size <sup>(Vertical view)</sup>',
            'desc' => 'Enter the image size (in pixels) of the carousel items. This field is related to the items within the vertical view. (Width then height separated by comma. Default: 174,90)',
            'type' => 'text',
            'std' => '174,90',
        ),												
        array(
            'id' => 'vertical_details_size',
            'title' => 'Description area size <sup>(Vertical view)</sup>',
            'desc' => 'Enter the size (in pixels) of the items description area. This field is related to the items within the vertical view. (Width then height separated by comma. Default: 174,150)',
            'type' => 'text',
            'std' => '174,150',
        ),		
        array(
            'id' => 'vertical_title_css',
            'title' => 'Title css <sup>(Vertical view)</sup>',
            'desc' => 'Customize the items title area and text by entring your css. This field is related to the items within the vertical view.',
            'type' => 'textarea',
            'std' => '',
        ),	
        array(
            'id' => 'vertical_details_css',
            'title' => 'Description css <sup>(Vertical view)</sup>',
            'desc' => 'Customize the items description area and text by entring your css. This field is related to the items within the vertical view.',
            'type' => 'textarea',
            'std' => '',
        ),		
		array(
            'id' => 'more_item_section',
            'title' => '<span class="section_sub_title">Content settings</span>',
            'desc' => '',
            'type' => 'custom',
        ),										
        array(
            'id' => 'show_details_btn',
            'title' => '"More" button',
            'desc' => 'Show/Hide "More" button',
            'type' => 'radio',
            'std' => 'yes',
            'choices' => array(
				'yes' => 'Show',
				'no' => 'Hide',
            )
        ),
        array(
            'id' => 'details_btn_text',
            'title' => 'Button text',
            'desc' => 'Enter your customize text to show on the "More" Button.',
            'type' => 'text',
            'std' => 'More',
        ),				
        array(
            'id' => 'details_btn_css',
            'title' => 'Button CSS',
            'desc' => 'Enter your CSS to customize the "More" Button\'s look',
            'type' => 'textarea',
            'std' => '',
        ),		
        array(
            'id' => 'items_title',
            'title' => 'Items title',
            'desc' => 'Create your customized items title by entering the name of your custom fields. You can use as many you want. Leave this field empty to use the default title.
					<br /><strong>Syntax:</strong> [meta_key<sup>1</sup>][separator<sup>1</sup>][meta_key<sup>2</sup>][separator<sup>2</sup>][meta_key<sup>n</sup>]...[title lenght].
					<br /><strong>Example of use:</strong> [post_category][s=,][post_address][l=50]
					<br /><strong>*</strong> To insert empty an space enter [-]',
            'type' => 'textarea',
            'std' => '',
        ),
        array(
            'id' => 'items_details',
            'title' => 'Items description',
            'desc' => 'Create your customized description content by entering the name of your custom fields. You can use as many you want. Leave this field empty to use the default description.
					<br /><strong>Syntax:</strong> [t=label<sup>1</sup>][meta_key<sup>1</sup>][separator<sup>1</sup>][t=label<sup>2</sup>][meta_key<sup>2</sup>][separator<sup>2</sup>][t=label<sup>n</sup>][meta_key<sup>n</sup>]...[description lenght]
					<br /><strong>Example of use:</strong> [t=Category:][-][post_category][s=br][t=Address:][-][post_address]
					<br /><strong>*</strong> To insert new ligne enter [s=br]
					<br /><strong>*</strong> To insert an empty space enter [-]',
            'type' => 'textarea',
            'std' => '[l=100]',
        ),	
	)
);


// Overlay settings

$wpsf_settings[] = array(
    'section_id' => 'overlaysettings',
    'section_title' => 'Form Area Settings',
    'section_description' => 'Use this interface to control the form area settings &amp; style.',
    'section_order' => 6,
    'fields' => array(
        array(
            'id' => 'show_overlay',
            'title' => 'Show form area',
            'desc' => 'Show/Hide form area',
            'type' => 'radio',
            'std' => 'no',
            'choices' => array(
				'yes' => 'Show',
				'no' => 'Hide',
            )
        ),	
        array(
            'id' => 'overlay_path',
            'title' => 'Page path',
            'desc' => 'Enter the full url to the page containing the items to show inside the form area.',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'id' => 'overlay_draggable',
            'title' => 'Draggable?',
            'desc' => 'Allow the form area to be moved using the mouse.',
            'type' => 'radio',
            'std' => 'yes',
			'choices' => array(
				'yes' => 'Allow',
				'no' => 'Disallow'
			)
        ),	
        array(
            'id' => 'overlay_width',
            'title' => 'Area width',
            'desc' => 'Enter the width (in pixels or % or auto) of the form area.',
            'type' => 'text',
            'std' => '400',
        ),
        array(
            'id' => 'overlay_height',
            'title' => 'Area height',
            'desc' => 'Enter the height (in pixels) of the form area. Following value is also accepted, <u>"auto"</u>',
            'type' => 'text',
            'std' => '250',
        ),
        array(
            'id' => 'overlay_top',
            'title' => 'Top position',
            'desc' => 'Enter the top position (in pixels) of the form area .',
            'type' => 'text',
            'std' => '10',
        ),
        array(
            'id' => 'overlay_left',
            'title' => 'Left position',
            'desc' => 'Enter the left position (in pixels) of the form area.',
            'type' => 'text',
            'std' => '30',
        ),
        array(
            'id' => 'overlay_color',
            'title' => 'Background color',
            'desc' => 'Choose the color of the form area. Enter <u>#</u> for transparent background.',
            'type' => 'color',
            'std' => '#ffffff',
        ),
        array(
            'id' => 'overlay_opacity',
            'title' => 'Area opacity',
            'desc' => 'Enter the opacity (from 0.1 to 1) of the form area.',
            'type' => 'text',
            'std' => '0.8',
        ),		
        array(
            'id' => 'overlay_css',
            'title' => 'More CSS code',
            'desc' => 'Add CSS code for more control over the form area.',
            'type' => 'textarea',
            'std' => '',
        ),
	)
	
);

?>