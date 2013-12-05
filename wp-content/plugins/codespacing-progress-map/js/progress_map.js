//=======================//
//==== Map functions ====//
//=======================//
	
	// Load map options
	function codespacing_load_map_options(){

		var latlng = progress_map_vars.center.split(',');

		var default_options = {
			center:[latlng[0], latlng[1]],
			zoom: parseInt(progress_map_vars.zoom),
			scrollwheel: eval(progress_map_vars.scrollwheel),
			panControl: eval(progress_map_vars.panControl),	
			panControlOptions: {
				position: google.maps.ControlPosition.RIGHT_TOP  
			},					
			mapTypeControl: eval(progress_map_vars.mapTypeControl),
			mapTypeControlOptions: {
				position: google.maps.ControlPosition.TOP_RIGHT
			},
			streetViewControl: eval(progress_map_vars.streetViewControl),	
			streetViewControlOptions: {
				position: google.maps.ControlPosition.RIGHT_TOP  
			},							
		};
		
		if(progress_map_vars.zoomControl == 'true' && progress_map_vars.zoomControlType == 'default'){
			
			var zoom_options = {
				zoomControl: true,
				zoomControlOptions:{
					style: google.maps.ZoomControlStyle.SMALL 
				},
			};
		
		}else{
			var zoom_options = {
				zoomControl: false,
			};
		}
		
		var map_options = jQuery.extend({}, default_options, zoom_options);
		
		return map_options;
		
	}
	
	// Create pins	
	function codespacing_new_pin($this, i, post_id, lat, lng, post_url, marker_img, items_title, items_details){
		
		var plugin_map = $this;
		
		plugin_map.gmap3({ 
		  marker:{
			latLng: [lat, lng],
			options:{
				icon: new google.maps.MarkerImage(progress_map_vars.marker_icon),							
			},
			callback: function(marker){			
				
				var overlay_id = marker.__gm_id;
				
				// Get items details
				codespacing_ajax_item_details(post_id);	
				
				// Create carousel items
				if(progress_map_vars.show_carousel == 'true'){
				
					var output = '';
					
					if(progress_map_vars.items_view == "listview"){ 
					
						item_width = parseInt(progress_map_vars.horizontal_item_width);										
						item_height = parseInt(progress_map_vars.horizontal_item_height);
						item_css = progress_map_vars.horizontal_item_css;
						items_background  = progress_map_vars.items_background;
						
						// Horizontal view
						
						output += '<li id="list_items_'+post_id+'" class="carousel_item_'+i+'" name="'+lat+'_'+lng+'" value="'+i+'" style="width:'+item_width+'px; height:'+item_height+'px; background-color:'+items_background+'; margin:4px 3px; '+item_css+'">';
							output += '<div class="spinner"></div>';							
						output += '</li>';
					
					}else if(progress_map_vars.items_view == "gridview"){ 
					
						item_width = parseInt(progress_map_vars.vertical_item_width);
						item_height = parseInt(progress_map_vars.vertical_item_height);
						item_css = progress_map_vars.vertical_item_css;
						items_background  = progress_map_vars.items_background;
						
						// Vertical view
						
						output += '<li id="list_items_'+post_id+'" class="carousel_item_'+i+'" name="'+lat+'_'+lng+'" value="'+i+'" style="width:'+item_width+'px; height:'+item_height+'px; background-color:'+items_background+'; margin:4px 3px; '+item_css+'">';
							output += '<div class="spinner"></div>';
						output += '</li>';
					
					}
					
					// Center the map at the marker lat,lng on item carousel click event	
					var $button = jQuery(output);
					$button.click(function(){
						
						overlay_id = marker.__gm_id;
						
						// Move the clicked carousel item to the first position
						codespacing_call_carousel_item(jQuery('ul#codespacing_progress_map_carousel').data('jcarousel'), i);
						
						// Add items carousel active style
						codespacing_carousel_item_hover_style('li.carousel_item_'+i+'');
						
						var map = $this.gmap3("get");
							
						map.panTo(marker.position);
						map.setCenter(marker.position);
						map.setZoom(12);

						if(progress_map_vars.infowindow_type != 'content_style'){		
							// Add overlay active style (used only for bubble infowindow style) 
							jQuery('div.marker_holder div.pin_overlay_content').removeClass('pin_overlay_content-active');
							jQuery('div#bubble_'+overlay_id+' div.pin_overlay_content').addClass('pin_overlay_content-active');	
						}
						
					}).css('cursor','pointer');

					// Add item content to the carousel
					jQuery('ul#codespacing_progress_map_carousel').append($button);
					
				}						
				
			},
			// marker events
			events:{
				mouseover: function(marker){	
					
					var overlay_id = marker.__gm_id;	
					
					// Call active overlay style			
					jQuery('div.marker_holder div.pin_overlay_content').removeClass('pin_overlay_content-active');
					jQuery('div#bubble_'+overlay_id+' div.pin_overlay_content').addClass('pin_overlay_content-active');	
					
					// Call carousel item active style
					if(progress_map_vars.show_carousel == 'true'){
						
						codespacing_call_carousel_item(jQuery('ul#codespacing_progress_map_carousel').data('jcarousel'), i);
						codespacing_carousel_item_hover_style('li.carousel_item_'+i+'');
					
					}
				
				},
				mouseout: function(marker){	
					
					var overlay_id = marker.__gm_id;
				
					// Remove overlay item style
					jQuery('div.marker_holder div.pin_overlay_content').removeClass('pin_overlay_content-active');
					jQuery('div#bubble_'+overlay_id+' div.pin_overlay_content').addClass('pin_overlay_content-active');	

				},
				// Click event is used only for content infowindow style
				click: function(marker){
						
					// first, hide all infowindows
					jQuery('div.infoWindowOverlay').hide();
						
					// Then show the current infowindow
					jQuery('div#infowindow_'+i+'').show();																
					
					// Center the map on that marker
					var latLng = new google.maps.LatLng (lat, lng);							
					var map = plugin_map.gmap3("get");														
					map.panTo(latLng);
					map.setCenter(latLng);	
					
					// Call custom scroll bar for infowindow
					jQuery("div.infoWindowOverlayTopRight p").mCustomScrollbar("destroy");
					jQuery("div.infoWindowOverlayTopRight p").mCustomScrollbar({
						autoHideScrollbar:true,
						theme:"dark-thin"
					});
																			
				}
			}											 
		  },
		  overlay: codespacing_create_marker_overlay(i, lat, lng, post_url, marker_img, items_title, items_details)

		});
		
	}
	
	// Create overlay
	function codespacing_create_marker_overlay(i, lat, lng, post_url, marker_img, items_title, items_details){
		
		var overlay = { latLng: [lat, lng] };
		
		if( progress_map_vars.show_infowindow == 'true' ){
		  
			var overlay = {
			
				latLng: [lat, lng],
			
				options: codespacing_overlay_content_options(i, post_url, marker_img, items_title, items_details),
			
				// Overlay event
				events:{
					mouseover: function(overlay){	
												
						// Call active overlay style																				
						jQuery('div.marker_holder div.pin_overlay_content').removeClass('pin_overlay_content-active');
						jQuery('div#bubble_'+(i+1)+' div.pin_overlay_content').addClass('pin_overlay_content-active');			
						
						// Call carousel item active style	
						if(progress_map_vars.show_carousel == 'true'){
							
							codespacing_call_carousel_item(jQuery('ul#codespacing_progress_map_carousel').data('jcarousel'), i);
							codespacing_carousel_item_hover_style('li.carousel_item_'+i+'');
							
						}
						
					},
					mouseout: function(overlay){
								
						// Remove overlay active event									
						jQuery('div.marker_holder div.pin_overlay_content').removeClass('pin_overlay_content-active');
						jQuery('div#bubble_'+(i+1)+' div.pin_overlay_content').addClass('pin_overlay_content-active');			
						
					},
					// Click event used only for content infowindow style
					click: function(overlay){
						
						// Hide current infowindow
						jQuery('div.infoWindowOverlayClose').click(function(){
							jQuery('div.infoWindowOverlay').hide();
						});
						
					}
				}
			
			}
		  
		}
		
		return overlay;
		
	}
	
	// Create overlay options
	function codespacing_overlay_content_options(i, post_url, marker_img, items_title, items_details){
		
		// Content style overlay
		
		if( progress_map_vars.infowindow_type == 'content_style' ){
			
			var overlay_options = {
				
				// Content infowindow (content style)
				content: '<div id="infowindow_'+i+'" name="'+i+'" value="" class="infoWindowOverlay overlay_'+i+'">'+
							'<div class="infoWindowOverlayTop">'+
								'<div class="infoWindowOverlayTopLeft">'+
									'<div class="InfoWindowOverlayImgHolder">'+
										'<div class="infoWindowOverlayImg" style="background:#fff url('+marker_img+') no-repeat;"></div>'+
									'</div>'+
									'<div class="infoWindowOverlayClose"></div>'+
								'</div>'+
								'<div class="infoWindowOverlayTopRight">'+
									'<p><a href="'+post_url+'">'+items_title+'</a><br />'+
									items_details+'</p>'+
								'</div>'+
							'</div>'+
							'<div>'+
								'<div class="infoWindowOverlayArrow"></div>'+
							'</div>'+
						'</div>',
						
				offset:{
					x:-125,
					y:-168
				}
				
			};
				
		// Bubble style overlay
			
		}else if( progress_map_vars.infowindow_type == 'bubble_style' ){
			
			var overlay_options = {
					
				// Rounded infowindow (bubble style)
				content: '<div id="bubble_'+(i+1)+'" class="marker_holder overlay_'+i+'" name="'+i+'">'+
							'<div class="pin_overlay img-'+i+'">'+
								'<div class="pin_overlay_img" style="background-image: url('+marker_img+');">'+
									'<div class="pin_overlay_content">'+
										'<a href="'+post_url+'"><u>More</u></a>'+
									'</div>'+
								'</div>'+
							'</div>'+
						 '</div>',		
													
				offset:{
					x:4,
					y:-80
				}
			
			};
			
		}
		
		return overlay_options;
	
	}
	
	// Clustering markers
	function codespacing_clustering(plugin_map){
				
		var markerCluster;
				
		plugin_map.gmap3({
			get: {
				name: 'marker',
				all: true,
				callback: function(objs){
					var mapObject = jQuery(this).gmap3('get');
					markerCluster  = new MarkerClusterer(mapObject, objs, {
						gridSize: 60,
						styles: [{
									url: progress_map_vars.small_cluster_icon,
									height: 57,
									width: 57,
									textColor: progress_map_vars.cluster_text_color,
									fontFamily: 'Arial'
								}, {
									url: progress_map_vars.medium_cluster_icon,
									height: 75,
									width: 75,
									textColor: progress_map_vars.cluster_text_color,
									fontFamily: 'Arial'
								}, {
									url: progress_map_vars.big_cluster_icon,
									height: 100,
									width: 100,
									textColor: progress_map_vars.cluster_text_color,
									fontFamily: 'Arial'
								}],
						zoomOnClick: true,								
					});
						
					// On load, Hide and show overlays depending on markers positions							
					setTimeout(function() {						
						codespacing_remove_overlays(markerCluster.getClusters());
						codespacing_load_overlays(plugin_map);	
					}, 1000);				
					
					// On zoom changed, Hide and show overlays depending on markers positions														
					google.maps.event.addListener(mapObject, 'zoom_changed', function() {				
						setTimeout(function() {
							codespacing_remove_overlays(markerCluster.getClusters());
							codespacing_load_overlays(plugin_map);
						}, 1000);
					});
					
					// On cluster click, Hide and show overlays depending on markers positions							
					google.maps.event.addListener(markerCluster, 'clusterclick', function(cluster) {
						setTimeout(function() {
							codespacing_load_overlays(plugin_map);
						}, 1000);								
					});
					
				}
			}
		});
	
	}
	
	// Get items data function via ajax
	function codespacing_ajax_item_details(post_id){
	
		jQuery.post(
			progress_map_vars.ajaxurl,
			{
				action: 'codespacing_progress_map_get_post_pinpoint',
				post_id: post_id,
				items_view: progress_map_vars.items_view,
			},
			function(data){	
				jQuery("li#list_items_"+post_id+"").fadeIn('slow').html(data);															
			}
		);
	
	}
	
	// Load overlays for markers outside clusters
	function codespacing_load_overlays(plugin_map){
	
		plugin_map.gmap3({
			get: {
				name: 'marker',
				all:  true,
				callback: function(objs) {
					jQuery.each(objs, function(i, obj) {									
						if(obj.getMap()) {
							var marker_id = obj.__gm_id;
							jQuery('div#bubble_'+marker_id+'').css({'display':'block'}); 
						};
					});
				}
			}
		});
	
	}										

	// hide overlays for markers inside clusters
	function codespacing_remove_overlays(clusters){
		
		jQuery('div.infoWindowOverlay').hide();					
		jQuery.each(clusters, function(i, cluster) {
			var markers = cluster.getMarkers();						
			if(markers.length > 1) { 
				jQuery.each(markers, function(i, marker) {								
					var marker_id = marker.__gm_id;
					jQuery('div#bubble_'+marker_id+'').css({'display':'none'}); 
				});
			}
		});	
		
	}

	// Zoom-in function
	function codespacing_zoom_in(selector, mapObj){
		
		selector.click(function(){
			
			var zoomLevel = jQuery(mapObj).gmap3('get').getZoom();
			
			zoomLevel++;
			
			if(zoomLevel > 19) zoomLevel = 19;
			
			mapObj.gmap3({ 
			
				map:{
					
					options: { zoom: zoomLevel }
				}
				
			});
		
		});
		
	}

	// Zoom-out function
	function codespacing_zoom_out(selector, mapObj){
		
		selector.click(function(){
			
			var zoomLevel = jQuery(mapObj).gmap3('get').getZoom();
			
			zoomLevel--;
			
			if(zoomLevel <= 1) zoomLevel = 1;
			
			mapObj.gmap3({ 
			
				map:{
					
					options: { zoom: zoomLevel }
					
				}
				
			});
		
		});
		
	}
	
//============================//
//==== Carousel functions ====//
//============================//

	// Initialize carousel
	function codespacing_init_carousel(){
	
		if(progress_map_vars.show_carousel == 'true'){
			
			var vertical_value = false;	
			
			if(progress_map_vars.main_layout == "mr-cl" || progress_map_vars.main_layout == "ml-cr"){
				
				var vertical_value = true;
				
			}
			
			var size = {}; 
			
			if(progress_map_vars.number_of_items != '')
				var size = { size: parseInt(progress_map_vars.number_of_items) };
			
			var default_options = {
				
				scroll: eval(progress_map_vars.carousel_scroll),
				wrap: progress_map_vars.carousel_wrap,
				auto: eval(progress_map_vars.carousel_auto),		
				initCallback: codespacing_carousel_init_callback,
				itemFallbackDimension: 184,
				itemFirstInCallback: {
				  onAfterAnimation: codespacing_carousel_itemFirstInCallback
				},
				itemLastInCallback: {
				  onAfterAnimation: codespacing_carousel_itemFirstInCallback
				},
				rtl: eval(progress_map_vars.carousel_mode),
				animation: progress_map_vars.carousel_animation,
				easing: progress_map_vars.carousel_easing,
				vertical: vertical_value,	
			
			};
			
			var carousel_options = jQuery.extend({}, default_options, size);
			
			// Init jcarousel
			jQuery('ul#codespacing_progress_map_carousel').jcarousel(carousel_options);	
		
		}		
		
	}
	
	// Move the map when the carousel is on the auto-scroll mode
	function codespacing_carousel_itemFirstInCallback(carousel){
		
		if(eval(progress_map_vars.carousel_auto) > 0){
														   
			firstItem = (carousel.first + parseInt(progress_map_vars.carousel_scroll));
			
			overlay_id = jQuery('.jcarousel-item-'+ firstItem +'').attr('value');
			
			if(overlay_id){
				
				item_latlng = jQuery('.jcarousel-item-'+ firstItem +'').attr('name').split('_');
				this_lat = item_latlng[0].replace(/\"/g, '');
				this_lng = item_latlng[1].replace(/\"/g, '');
					
				codespacing_carousel_item_hover_style('li[value='+overlay_id+']');
					
				var latLng = new google.maps.LatLng (this_lat, this_lng);
				
				var map = jQuery('div#codespacing_progress_map_div').gmap3("get");							
				
				map.panTo(latLng);
				map.setCenter(latLng);
				map.setZoom(12);
				
				// Overlay active style
				setTimeout(function() {					
					overlay_id = ++overlay_id;
					jQuery('div.marker_holder div.pin_overlay_content').removeClass('pin_overlay_content-active');
					jQuery('div#bubble_'+overlay_id+' div.pin_overlay_content').addClass('pin_overlay_content-active');	
				}, 600);
				
			}
			
		}
				
	}
	
	// Carousel callback function
	function codespacing_carousel_init_callback(carousel){
		
		if(progress_map_vars.scrollwheel_carousel == 'true'){
				
			// Move the carousel with scroll wheel
			jQuery('ul#codespacing_progress_map_carousel').mousewheel(function(event, delta) {
				if (delta > 0)
					{carousel.prev();}
				else if (delta < 0)
					{carousel.next();}
			});
			
		}
		
		// Pause autoscrolling if the user moves with the cursor over the carousel
		carousel.clip.hover(function() {
			carousel.stopAuto();
		}, function() {
			carousel.startAuto();
		});
	
		carousel.buttonNext.bind('click', function() {
												   
			firstItem = (carousel.first + parseInt(progress_map_vars.carousel_scroll));
			
			overlay_id = jQuery('.jcarousel-item-'+ firstItem +'').attr('value');
			
			if(overlay_id){
				
				item_latlng = jQuery('.jcarousel-item-'+ firstItem +'').attr('name').split('_');
				this_lat = item_latlng[0].replace(/\"/g, '');
				this_lng = item_latlng[1].replace(/\"/g, '');
					
				codespacing_carousel_item_hover_style('li[value='+overlay_id+']');
					
				var latLng = new google.maps.LatLng (this_lat, this_lng);
				
				var map = jQuery('div#codespacing_progress_map_div').gmap3("get");							
				
				map.panTo(latLng);
				map.setCenter(latLng);
				map.setZoom(12);
				
				// Overlay active style
				setTimeout(function() {					
					overlay_id = ++overlay_id;
					jQuery('div.marker_holder div.pin_overlay_content').removeClass('pin_overlay_content-active');
					jQuery('div#bubble_'+overlay_id+' div.pin_overlay_content').addClass('pin_overlay_content-active');	
				}, 600);
				
			}
				
		});
		
		carousel.buttonPrev.bind('click', function() {
												   
			firstItem = (carousel.first - (parseInt(progress_map_vars.carousel_scroll) - 1));
			
			overlay_id = jQuery('.jcarousel-item-'+ firstItem +'').attr('value');
			
			if(overlay_id){
				
				item_latlng = jQuery('.jcarousel-item-'+ firstItem +'').attr('name').split('_');
				this_lat = item_latlng[0].replace(/\"/g, '');
				this_lng = item_latlng[1].replace(/\"/g, '');
					
				codespacing_carousel_item_hover_style('li[value='+overlay_id+']');
					
				var latLng = new google.maps.LatLng (this_lat, this_lng);
				
				var map = jQuery('div#codespacing_progress_map_div').gmap3("get");							
				
				map.panTo(latLng);
				map.setCenter(latLng);
				map.setZoom(12);
				
				// Overlay active style
				setTimeout(function() {
					overlay_id = ++overlay_id;						
					jQuery('div.marker_holder div.pin_overlay_content').removeClass('pin_overlay_content-active');
					jQuery('div#bubble_'+overlay_id+' div.pin_overlay_content').addClass('pin_overlay_content-active');	
				}, 600);
				
				outerHTML = jQuery('<div>').append(jQuery('.jcarousel-item-'+ firstItem +'').clone()).html();
			
			}
			
		});
		
	}					
	
	// Call carousel items								
	function codespacing_call_carousel_item(carousel, id){
		
		carousel.scroll(jQuery.jcarousel.intval(id));
		return false;
		
	}
	
	// Custom style for the first and selected carousel item
	function codespacing_carousel_item_hover_style(item_selector){								

		jQuery('li[class^=carousel_item_]').css({'background-color':progress_map_vars.items_background});
		jQuery(item_selector).css({'background-color':progress_map_vars.items_hover_background});	
		
	}

//=========================//
//==== Other functions ====//
//=========================//
	
	// Clean string from alpha chars
	function codespacing_stripAlphaChars(pstrSource){ 
	
		var m_strOut = new String(pstrSource); 
		m_strOut = m_strOut.replace(/[^0-9]/g, ''); 
		return m_strOut; 
		
	}
	
	function alerte(obj) {
		
		if (typeof obj == 'object') {
			var foo = '';
			for (var i in obj) {
				if (obj.hasOwnProperty(i)) {
					foo += '[' + i + '] => ' + obj[i] + '\n';
				}
			}
			alert(foo);
		}else {
			alert(obj);
		}
		
	}

jQuery(document).ready(function($) {
	
	setTimeout(function() {
		
		jQuery('.jcarousel-skin-default .jcarousel-container').css({'background-color':progress_map_vars.carousel_background});
		
	}, 1000);
	
});