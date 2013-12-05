var all_around_events=new Array();
var all_around_control_changed_callback_for_all=null;
function all_around_control_changed(name, value) {
	if (all_around_control_changed_callback_for_all!=null) all_around_control_changed_callback_for_all(name, value);
	var i;
	var count=all_around_events.length;			
	for (i=0; i<count; i++) {
		if (all_around_events[i].name==name) all_around_events[i].func(name, value);
	}
}

function all_around_add_event(name, func) {
	var i=all_around_events.length;
	all_around_events[i]={
		'name': name,
		'func': func
	};
}

var all_around_create_popup;
var all_around_remove_popup;
var all_around_set_popup_content;


(function($){

		all_around_create_popup = function(title, content, width, width_unit, height, scroll, center, loader_margin_top, loader_margin_left) {
			if (typeof title=='undefined') title='Popup';
			if (typeof content=='undefined') content='';
			if (typeof width=='undefined') width='100';
			if (typeof width_unit=='undefined') width_unit='%';
			if (typeof height=='undefined') height='500';
			if (typeof scroll=='undefined') scroll=1;
			if (typeof center=='undefined') center=1;
			var margin_left=width/2;
			var margin_top=height/2;
			var holder_height=height-30;
			var html='';
			html+='<div id="all_around_overlay"></div>';
			html+='<div id="all_around_popup" style="width: '+width+width_unit+'; margin-left: -'+margin_left+width_unit+'; height: '+height+'px; margin-top: -'+margin_top+'px; visibility: visible;">';
			html+='<div id="all_around_title">';
			html+='<div id="all_around_ajaxWindowTitle">'+title+'</div>';
			html+='<div id="all_around_closeAjaxWindow"><a id="all_around_closeWindowButton" href="#" title="Close"><img alt="Close" src="'+all_around_plugin_url+'images/tb-close.png"></a></div>';
			html+='</div>';
			var scroll_style='';
			if (scroll) scroll_style='overflow-y: scroll; ';
			var center_style='';
			if (center) center_style='text-align: center; ';
			html+='<div id="all_around_popup_holder" style="margin: 0px auto; '+scroll_style+'position: relative; width: 100%; height: '+holder_height+'px; '+center_style+'padding-top: 1px;">';
			if (content=='') {
				if (typeof loader_margin_top=='undefined') loader_margin_top=margin_top;
				var loader_margin_left_string='';
				if (typeof loader_margin_left!='undefined') loader_margin_left_string=' margin-left: '+loader_margin_left+'px';
				html += '<img style="width: 208px; margin-top:'+loader_margin_top+'px;'+loader_margin_left_string+'" id="all_around_loader" src="'+all_around_plugin_url+'images/loadingAnimation.gif" />';
			} else {
				html+=content;
			}
			html+='</div>';
			html+='</div>';
			$('body').append(html);
			$('#all_around_closeWindowButton').click(function(e){
				e.preventDefault();
				all_around_remove_popup();
			});
		}

		all_around_remove_popup=function() {
			$('#all_around_overlay').remove();
			$('#all_around_popup').remove();
		}
		
		all_around_set_popup_content=function(buffer) {
			$('#all_around_popup_holder').html(buffer);
		}

	$(document).ready(function(){

		$('body').prepend('<div id="fbuilder_background"></div>');
		function all_around_handle_upload(data_input, url) {
			//console.log(url);
			var img_pos=url.indexOf('<img');
			if (img_pos>0) {
				url=url.substring(img_pos);
				img_pos2=url.indexOf('>');
				if (img_pos2>0) {
					url=url.substring(0, img_pos2+1);
					while (url.indexOf('\\"')>-1) url=url.replace('\\"','"');
					var $jurl=$(url);
					url = $jurl.attr('src');
				}
			}

			$('#'+data_input).val(url);
			$('#'+data_input+'_img').attr('src', url);
			all_around_control_changed(data_input, url);

			if (all_around_uploader_type==1) tb_remove();
		}
		
		var all_around_upload_data_input;
		window.send_to_editor = function(html) {
			if (all_around_uploader_type==1) all_around_handle_upload(all_around_upload_data_input, html);
		}

		$('.fbuilder_image_button').live('click', function(e) {
			//console.log('uploading...');
			e.preventDefault();
			var data_input=$(this).attr('data-input');
			all_around_upload_data_input=data_input;
			//console.log('all_around_uploader_type='+all_around_uploader_type);
			if (all_around_uploader_type==2) {
				wp.media.editor.send.attachment = function(props, attachment) {
					all_around_handle_upload(data_input, attachment.url);
				}
				wp.media.editor.open(this);
			}
			if (all_around_uploader_type==1) {
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');

			}

			return false;
		});

		$( ".sortable" ).sortable();
		//$( ".sortable" ).disableSelection();

		$('.fbuilder_select')
			.live('mouseenter', function(){
				$(this).data('hover',true);
			}).live('mouseleave',function(){
				$(this).data('hover', false);
			});
			
		$('.fbuilder_select span, .fbuilder_select .drop_button').live('click', function(e){
			e.preventDefault();
			$parent = $(this).parent();
			if(!$parent.hasClass('active')) {
				$parent.addClass('active').find('ul').show();
			}
			else {
				$parent.removeClass('active').find('ul').hide();
			}
		});
		$('.fbuilder_select ul a').live('click', function(e){
			e.preventDefault();
			var $parent = $(this).parent().parent().parent();
			var name=$parent.attr('data-name');
			var $select = $('input[name='+name+']');
			var value=$(this).attr('data-value');
			$select.val(value);
			all_around_control_changed(name, value);
			//$select.val($(this).attr('data-value'));
			$parent.find('span').html($(this).html());
			$parent.removeClass('active').find('ul').hide();
			$parent.find('ul a.selected').removeClass('selected');
			$(this).addClass('selected');
			
			//fbuilderContolChange($select);
			
		});
		$('body').click(function(){
			$('.fbuilder_select.active').each(function(){
				if(!$(this).data('hover')) {
					$(this).removeClass('active').find('ul').hide();
				}
			});
		});
		

		$( ".fbuilder_number_bar" ).each(function(){
			if(!$(this).hasClass('ui-slider')) {
				var min = parseInt($(this).attr('data-min'));
				var max = parseInt($(this).attr('data-max'));
				var std = parseInt($(this).attr('data-std'));
				var unit = $(this).attr('data-unit');
				$(this).slider({
					min: min,
					max: max,
					value: std,
					range: "min",
					slide: function( event, ui ) {
						$(this).parent().find( ".fbuilder_number_amount" ).val( ui.value );
					},
					change : function( event, ui) {
						var $input = $(this).parent().find( ".fbuilder_number_amount" );
						//fbuilderContolChange($input);
						
					}
				});
			}
		});
		
		$('.fbuilder_checkbox').live('click', function(){
			var $input = $(this).parent().find('.fbuilder_checkbox_input');
			if($(this).hasClass('active')) {
				$input.val('0');
				var name=$input.attr('name');
				all_around_control_changed(name, 0);
				$(this).removeClass('active');
			}
			else {
				$input.val('1');
				var name=$input.attr('name');
				all_around_control_changed(name, 1);
				$(this).addClass('active');
			}
			//fbuilderContolChange($input);			
		});
		$('.fbuilder_checkbox_label').live('click', function(){
			$(this).prevAll('.fbuilder_checkbox:first').click();
		});

		$( '.fbuilder_color' ).each(function(){
			$(this).parent().find('.fbuilder_color_display').css('background', $(this).val());
			$(this).iris({
				width:228,
				target:$(this).parent().find('.fbuilder_colorpicker'),
				change: function(event, ui) {
					$(this).val(ui.color.toString());
				    $(this).parent().find('.fbuilder_color_display').css( 'background-color', ui.color.toString());
					//fbuilderContolChange($(this), true);
				}
			});
		});		


		$( '.fbuilder_color' ).live('focus', function(){
			$(this).parent().find('.fbuilder_colorpicker').addClass('active').show();
			//$(this).parent().find('.iris-picker').show();
			$(this).iris('show');
			//fbuilderRefreshControls();
		}).live('mouseenter', function(){
			$(this).parent().find('.fbuilder_colorpicker').data('hover', true);
		}).live('mouseleave', function(){
			$(this).parent().find('.fbuilder_colorpicker').data('hover', false);
		});
		
		$( '.fbuilder_colorpicker' ).live('mouseenter', function(){
			$(this).data('hover', true);
		}).live('mouseleave', function(){
			$(this).data('hover', false);
		});
		
		$('body').click(function(){
			$('.fbuilder_colorpicker.active').each(function(){
				if(!$(this).data('hover')) {
					$(this).removeClass('active').hide();
					//fbuilderRefreshControls();
				}
			});
		});
		
		$('.fbuilder_collapsible_header').live('click', function() {
			//alert($(this).next().html());
			//.click();
			var bthis=$('.fbuilder_collapse_trigger', $(this));
			collapse_trigger_click(bthis);
		});
		
		function collapse_trigger_click(bthis) {
			//console.log('2 point');
			var $content = $(bthis).parent().parent().children('.fbuilder_collapsible_content');
			if(!$(bthis).hasClass('active')) {
				$(bthis).html('-').addClass('active');
				$content.show();
			}
			else {
				$(bthis).html('+').removeClass('active');
				$content.hide();
			}
			//fbuilderRefreshControls();
		}

		//$('.fbuilder_collapse_trigger').live('click', function(){
			//console.log('1 point');
			//collapse_trigger_click(this);
		//});
		
		$('.fbuilder_textarea').live('input propertychange', function(){
			var name=$(this).attr('name');
			var value=$(this).val();
			all_around_control_changed(name, value);
		});
		$('.fbuilder_input').live('input propertychange', function(){
			var name=$(this).attr('name');
			var value=$(this).val();
			all_around_control_changed(name, value);
		});
		
	});


})(jQuery);