jQuery(document).ready(function($){ 
		   
	// Control over the menu

	$("li#"+cspacing_admin_vars.first_section+"").addClass('current');
	
	$("div[class^=custom_section_]").hide();
	
	$("ul.codespacing_menu li").click(function(){
		$('div[class^=custom_section_]').hide();
		var id = $(this).attr('id');
		$(".custom_section_" + id + "").show();
		$("li").removeClass('current');
		$(this).addClass('current');
		$.cookie('codespacing_admin_menu', id, { expires: 1 });
	});	

	if($.cookie('codespacing_admin_menu') == null){
		$(".custom_section_"+cspacing_admin_vars.first_section+"").show();
		$("li").removeClass('current');
		$("li#"+cspacing_admin_vars.first_section+"").addClass('current');
	}else{
		$(".custom_section_" + $.cookie('codespacing_admin_menu') + "").show();
		$("li").removeClass('current');
		$("li#" + $.cookie('codespacing_admin_menu') + "").addClass('current');
	}
	

	// Customize Checkboxes and Radios button
	
	$('div[class^=custom_section_] input').iCheck({
	  checkboxClass: 'icheckbox_minimal-blue',
	  radioClass: 'iradio_minimal-blue',
	  increaseArea: '20%',
	});
			
				
	// ToolTip jQuery
	
	$.fn.qtip.styles.mystyle = {
	   width: 400,
	   height: 300,
	   padding: 0,
	   background: 0,
	   border:{
		   width:0
	   }
	}
	
	$('label[id=codespacingprogressmap_generalsettings_main_layout_mu-cd]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/mu-cd.png" />'
	});		
	
	$('label[id=codespacingprogressmap_generalsettings_main_layout_md-cu]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/md-cu.png" />'
	});		
	
	$('label[id=codespacingprogressmap_generalsettings_main_layout_mr-cl]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/mr-cl.png" />'
	});		
	
	$('label[id=codespacingprogressmap_generalsettings_main_layout_ml-cr]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/ml-cr.png" />'
	});		

	$('label[id=codespacingprogressmap_itemssettings_items_view_listview]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/horizontal.jpg" />'
	});		
	
	$('label[id=codespacingprogressmap_itemssettings_items_view_gridview]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/vertical.jpg" />'
	});		
	
	$('label[id=codespacingprogressmap_mapsettings_infowindow_type_content_style]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/customized-infowindow.jpg" />'
	});		
	
	$('label[id=codespacingprogressmap_mapsettings_infowindow_type_bubble_style]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/bubble-overlay.jpg" />'
	});		
	
	$('label[id=codespacingprogressmap_mapsettings_mapTypeControl_true], label[id=codespacingprogressmap_mapsettings_mapTypeControl_false]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/map-type.jpg" />'
	});		
	
	$('label[id=codespacingprogressmap_mapsettings_streetViewControl_true], label[id=codespacingprogressmap_mapsettings_streetViewControl_false]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/streetview.jpg" />'
	});		
	
	$('label[id=codespacingprogressmap_mapsettings_scrollwheel_true], label[id=codespacingprogressmap_mapsettings_scrollwheel_false]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/scroll-wheel.jpg" />'
	});		
	
	$('label[id=codespacingprogressmap_mapsettings_panControl_true], label[id=codespacingprogressmap_mapsettings_panControl_false]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/pancontrol.jpg" />'
	});		
	
	$('label[id=codespacingprogressmap_mapsettings_zoomControl_true], label[id=codespacingprogressmap_mapsettings_zoomControl_false]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/default-zoom.jpg" />'
	});		
	
	$('label[id=codespacingprogressmap_mapsettings_zoomControlType_customize]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/customized-zoom.jpg" />'
	});		
	
	$('label[id=codespacingprogressmap_mapsettings_zoomControlType_default]').qtip({
	  style: 'mystyle',
	  content: '<img src="'+cspacing_admin_vars.plugin_url+'img/tooltips/default-zoom.jpg" />'
	});		


	// jQuery Validate
	
	$("#codespacing_form").validate({
		wrapper: "em",
		rules: {
			"codespacingprogressmap_settings[codespacingprogressmap_generalsettings_post_type]": "required",
			
			"codespacingprogressmap_settings[codespacingprogressmap_generalsettings_layout_fixed_width]":{
				number: true
			},						
			"codespacingprogressmap_settings[codespacingprogressmap_generalsettings_layout_fixed_height]":{
				required: true,
				number: true
			},
			
			"codespacingprogressmap_settings[codespacingprogressmap_mapsettings_map_center]": "required",
			
			//"codespacingprogressmap_settings[codespacingprogressmap_mapsettings_marker_icon]": "required",
			//"codespacingprogressmap_settings[codespacingprogressmap_mapsettings_big_cluster_icon]": "required",
			//"codespacingprogressmap_settings[codespacingprogressmap_mapsettings_medium_cluster_icon]": "required",
			//"codespacingprogressmap_settings[codespacingprogressmap_mapsettings_small_cluster_icon]": "required",
			
			"codespacingprogressmap_settings[codespacingprogressmap_carouselsettings_carousel_scroll]":{
				required: true,
				number: true
			},
			"codespacingprogressmap_settings[codespacingprogressmap_carouselsettings_carousel_auto]":{
				required: true,
				number: true
			},
			
			"codespacingprogressmap_settings[codespacingprogressmap_itemssettings_horizontal_image_size]": "required",
			"codespacingprogressmap_settings[codespacingprogressmap_itemssettings_horizontal_details_size]": "required",
			"codespacingprogressmap_settings[codespacingprogressmap_itemssettings_vertical_image_size]": "required",
			"codespacingprogressmap_settings[codespacingprogressmap_itemssettings_vertical_details_size]": "required",
		}
	});
	
});
	
Cufon.replace("div[class^=custom_section_] > h3, ul.codespacing_menu li, div[class^=custom_section_] > p, p.lorem, input.custom-button-primary, span.section_sub_title");			

