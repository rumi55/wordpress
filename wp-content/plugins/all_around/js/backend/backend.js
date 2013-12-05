function all_around_get_ajax_array(response) {
	var arr=new Array();
	var i, is;
	for (var key in response) {
		if (key.substring(0,4)=='data') {
			is=key.substring(4);
			i=parseInt(is, 10);
			arr[i]=response[key];
		}
	}
	return arr;
}

var all_around_send_ajax;
var all_around_ajax_load_form;
var all_around_fields_changed = new Array();
var all_around_loader_image;
var all_around_loader_image2;
var all_around_loader_image3;

(function($){

	$(document).ready(function(){

		all_around_loader_image='<img style="width: 208px; float: none;" id="all_around_loader" src="'+all_around_plugin_url+'images/loadingAnimation.gif" />';
		all_around_loader_image2='<img id="all_around_loader" src="'+all_around_plugin_url+'images/loadingAnimation.gif" style="width: 208px; margin-top:27px; margin-left: 130px" title="">';
		all_around_loader_image3='<img id="all_around_loader" src="'+all_around_plugin_url+'images/loadingAnimation.gif" style="width: 208px; display: block; margin-bottom: 15px; margin-left: 15px;" title="">';

		all_around_send_ajax = function(action, pdata, callback, datatype) {
			if (typeof datatype=='undefined') datatype='json';
			//if (datatype!='text') datatype+=' text';
			var sdata=all_around_ajax_action_param+'='+action+'&'+pdata;
			//alert(sdata);
			$.ajax({
				type: 'POST',
				dataType: datatype,
				url: all_around_ajax_receiver, 
				data: sdata,
				success: function(response) {
					callback(response, datatype, 1);
				},
				error: function(request, status){
					//alert(request.responseText);
					console.log('Ajax error: '+request.responseText);
					callback(request.responseText, 'text', 0);
				}
			});
		}
		
		all_around_ajax_load_form = function(target, custom_form, sub_item_id) {
			var item_id=$('#element_id').val();
			var data='item_id='+item_id+'&custom_form='+custom_form+'&sub_item_id='+sub_item_id;
			var sub_item_id_i = parseInt(sub_item_id, 10);
			var check=1;
			if (typeof all_around_fields_changed[sub_item_id_i] == 'undefined') check=0;
			else {
				if (all_around_fields_changed[sub_item_id_i] == 0) check=0;
			}
			if (check==1) {
				var r=confirm("This operation will reset content fields. Continue?");
				if (r==false) return;
			}
			$(target).prepend(all_around_loader_image3);
			all_around_send_ajax('all_around_get_custom_form', data, function(response) {
				if (response.status==1) {
					all_around_fields_changed[sub_item_id_i] = 0;
					$(target).html(response.data);
					var types=custom_form.substring(12);
					var type=parseInt(types, 10)
					//console.log('type='+type+', i='+sub_item_id_i);
					all_around_update_content(type, sub_item_id_i);
				}
			});	
		}
		
		function all_around_update_content(type, i) {
			if (type==4) return;
			var title=$('#item_'+i+'_title').val();
			//console.log('type = '+type); console.log('title = "'+title+'"'); console.log('i = "'+i+'"');
			var buffer='';
			if (type==0) {
				var first_field=$('#item_'+i+'_f1_first_field').val();
				var first_field_value=$('#item_'+i+'_f1_first_field_value').val();
				var second_field=$('#item_'+i+'_f1_second_field').val();
				var second_field_value=$('#item_'+i+'_f1_second_field_value').val();
				var third_field=$('#item_'+i+'_f1_third_field').val();
				var third_field_value=$('#item_'+i+'_f1_third_field_value').val();
				var fourth_field=$('#item_'+i+'_f1_fourth_field').val();
				var fourth_field_value=$('#item_'+i+'_f1_fourth_field_value').val();
				var about=$('#item_'+i+'_f1_about').val();
				var facebook_link=$('#item_'+i+'_f1_facebook_link').val();
				var twitter_link=$('#item_'+i+'_f1_twitter_link').val();
				var pinterest_link=$('#item_'+i+'_f1_pinterest_link').val();
				var youtube_link=$('#item_'+i+'_f1_youtube_link').val();
				if (title!='') buffer+='<h3>'+title+'</h3><br /><br />\n';
				if (first_field!='' && first_field_value!='') buffer+='<span><span class="bold">'+first_field+' </span>'+first_field_value+'</span><br />\n';
				if (second_field!='' && second_field_value!='') buffer+='<span><span class="bold">'+second_field+' </span>'+second_field_value+'</span><br />\n';
				if (third_field!='' && third_field_value!='') buffer+='<span><span class="bold">'+third_field+' </span>'+third_field_value+'</span><br />\n';
				if (fourth_field!='' && fourth_field_value!='') buffer+='<span><span class="bold">'+fourth_field+' </span>'+fourth_field_value+'</span><br />\n';
				if (about!='') buffer+='<br /><span>'+about+'</span><br />\n';
				if (facebook_link!='' || twitter_link!='' || pinterest_link!='' || youtube_link!='') buffer+='<br /><br />\n';
				if (facebook_link!='') buffer+='<a href="'+facebook_link+'" class="button_socials button_hover_effect fb" data-hovercolor="#496dba" data-hoveroutcolor="#3b5a9a"></a>\n';
				if (twitter_link!='') buffer+='<a href="'+twitter_link+'" class="button_socials button_hover_effect tw" data-hovercolor="#4bb8e7" data-hoveroutcolor="#23aae1"></a>\n';
				if (pinterest_link!='') buffer+='<a href="'+pinterest_link+'" class="button_socials button_hover_effect pin" data-hovercolor="#de343d" data-hoveroutcolor="#cc2129"></a>\n';
				if (youtube_link!='') buffer+='<a href="'+youtube_link+'" class="button_socials button_hover_effect yt" data-hoveroutcolor="#bb000e" data-hovercolor="#fd0013"></a>';
			}
			if (type==1) {
				var about=$('#item_'+i+'_f2_about').val();
				var first_field=$('#item_'+i+'_f2_first_field').val();
				var first_field_value=$('#item_'+i+'_f2_first_field_value').val();
				var second_field=$('#item_'+i+'_f2_second_field').val();
				var second_field_value=$('#item_'+i+'_f2_second_field_value').val();
				var button_text=$('#item_'+i+'_f2_button_text').val();
				var button_link=$('#item_'+i+'_f2_button_link').val();

				if (title!='') buffer+='<h3>'+title+'</h3><br /><br />\n';
				if (about!='') buffer+='<span>'+about+'</span><br /><br /><br />\n';
				if (first_field!='' && first_field_value!='') buffer+='<span><span class="bold">'+first_field+' </span>'+first_field_value+'</span><br />\n';
				if (second_field!='' && second_field_value!='') buffer+='<span><span class="bold">'+second_field+' </span>'+second_field_value+'</span><br />\n';
				if (button_text!='' && button_link!='') buffer+='<br /><br /><a href="'+button_link+'" class="button_regular button_hover_effect" data-hovercolor="#1fdab5" data-hoveroutcolor="#1ab99b">'+button_text+'</a>';
			}
			if (type==2) {
				var first_show=$('#item_'+i+'_f3_first_show').val();
				var first_image=$('#item_'+i+'_f3_first_image').val();
				var first_title=$('#item_'+i+'_f3_first_title').val();
				var first_about=$('#item_'+i+'_f3_first_about').val();
				var second_show=$('#item_'+i+'_f3_second_show').val();
				var second_image=$('#item_'+i+'_f3_second_image').val();
				var second_title=$('#item_'+i+'_f3_second_title').val();
				var second_about=$('#item_'+i+'_f3_second_about').val();
				var third_show=$('#item_'+i+'_f3_third_show').val();
				var third_image=$('#item_'+i+'_f3_third_image').val();
				var third_title=$('#item_'+i+'_f3_third_title').val();
				var third_about=$('#item_'+i+'_f3_third_about').val();
				var fourth_show=$('#item_'+i+'_f3_fourth_show').val();
				var fourth_image=$('#item_'+i+'_f3_fourth_image').val();
				var fourth_title=$('#item_'+i+'_f3_fourth_title').val();
				var fourth_about=$('#item_'+i+'_f3_fourth_about').val();

				if (title!='') buffer+='<h3>'+title+'</h3><br /><br />\n<div class="separator"></div><br /><br />\n';
				if (first_show==1) {
					buffer+='<div class="col-1-4_block">\n';
					if (first_image!='') buffer+='	<div class="content_img_wrap"><img src="'+first_image+'" alt="" style="width: 182px;" /><a href="'+first_image+'" class="hover_link" rel="prettyPhoto"><img src="'+all_around_plugin_url+'images/more.png" alt="More" /></a></div><br />\n';
					if (first_title!='') buffer+='	<h4>'+first_title+'</h4><br />\n';
					if (first_about!='') buffer+='	<span>'+first_about+'</span>\n';
					buffer+='</div>\n';
				}
				if (second_show==1) {
					buffer+='<div class="col-1-4_block">\n';
					if (second_image!='') buffer+='	<div class="content_img_wrap"><img src="'+second_image+'" alt="" style="width: 182px;" /><a href="'+second_image+'" class="hover_link" rel="prettyPhoto"><img src="'+all_around_plugin_url+'images/more.png" alt="More" /></a></div><br />\n';
					if (second_title!='') buffer+='	<h4>'+second_title+'</h4><br />\n';
					if (second_about!='') buffer+='	<span>'+second_about+'</span>\n';
					buffer+='</div>\n';
				}
				if (third_show==1) {
					buffer+='<div class="col-1-4_block">\n';
					if (third_image!='') buffer+='	<div class="content_img_wrap"><img src="'+third_image+'" alt="" style="width: 182px;" /><a href="'+third_image+'" class="hover_link" rel="prettyPhoto"><img src="'+all_around_plugin_url+'images/more.png" alt="More" /></a></div><br />\n';
					if (third_title!='') buffer+='	<h4>'+third_title+'</h4><br />\n';
					if (third_about!='') buffer+='	<span>'+third_about+'</span>\n';
					buffer+='</div>\n';
				}
				if (fourth_show==1) {
					console.log('fourth_show='+fourth_show);
					buffer+='<div class="col-1-4_block">\n';
					if (fourth_image!='') buffer+='	<div class="content_img_wrap"><img src="'+fourth_image+'" alt="" style="width: 182px;" /><a href="'+fourth_image+'" class="hover_link" rel="prettyPhoto"><img src="'+all_around_plugin_url+'images/more.png" alt="More" /></a></div><br />\n';
					if (fourth_title!='') buffer+='	<h4>'+fourth_title+'</h4><br />\n';
					if (fourth_about!='') buffer+='	<span>'+fourth_about+'</span>\n';
					buffer+='</div>';
				}
			}
			if (type==3) {
				var first_show=$('#item_'+i+'_f4_first_show').val();
				var first_title=$('#item_'+i+'_f4_first_title').val();
				var first_image=$('#item_'+i+'_f4_first_image').val();
				var first_about=$('#item_'+i+'_f4_first_about').val();
				var first_button_text=$('#item_'+i+'_f4_first_button_text').val();
				var first_button_link=$('#item_'+i+'_f4_first_button_link').val();
				var second_show=$('#item_'+i+'_f4_second_show').val();
				var second_title=$('#item_'+i+'_f4_second_title').val();
				var second_image=$('#item_'+i+'_f4_second_image').val();
				var second_about=$('#item_'+i+'_f4_second_about').val();
				var second_button_text=$('#item_'+i+'_f4_second_button_text').val();
				var second_button_link=$('#item_'+i+'_f4_second_button_link').val();
				var third_show=$('#item_'+i+'_f4_third_show').val();
				var third_title=$('#item_'+i+'_f4_third_title').val();
				var third_image=$('#item_'+i+'_f4_third_image').val();
				var third_about=$('#item_'+i+'_f4_third_about').val();
				var third_button_text=$('#item_'+i+'_f4_third_button_text').val();
				var third_button_link=$('#item_'+i+'_f4_third_button_link').val();

				if (title!='') buffer+='<h3>'+title+'</h3><br /><br />\n<div class="separator"></div><br /><br />\n';
				if (first_show==1) {
					buffer+='<div class="col-1-3_block">\n';
					if (first_title!='') buffer+='	<h4>'+first_title+'</h4><br />\n';
					if (first_image!='') buffer+='	<img src="'+first_image+'" alt="" style="width: 230px;" /><br />\n';
					if (first_about!='') buffer+='	<br /><span>'+first_about+'</span><br />\n';
					if (first_button_text!='' && first_button_link!='') buffer+='	<br /><br /><a href="'+first_button_link+'" class="button_regular button_hover_effect" data-hovercolor="#1fdab5" data-hoveroutcolor="#1ab99b">'+first_button_text+'</a>\n';
					buffer+='</div>\n';
				}
				if (second_show==1) {
					buffer+='<div class="col-1-3_block">\n';
					if (second_title!='') buffer+='	<h4>'+second_title+'</h4><br />\n';
					if (second_image!='') buffer+='	<img src="'+second_image+'" alt="" style="width: 230px;" /><br />\n';
					if (second_about!='') buffer+='	<br /><span>'+second_about+'</span><br />\n';
					if (second_button_text!='' && second_button_link!='') buffer+='	<br /><br /><a href="'+second_button_link+'" class="button_regular button_hover_effect" data-hovercolor="#1fdab5" data-hoveroutcolor="#1ab99b">'+second_button_text+'</a>\n';
					buffer+='</div>\n';
				}
				if (third_show==1) {
					buffer+='<div class="col-1-3_block">\n';
					if (third_title!='') buffer+='	<h4>'+third_title+'</h4><br />\n';
					if (third_image!='') buffer+='	<img src="'+third_image+'" alt="" style="width: 230px;" /><br />\n';
					if (third_about!='') buffer+='	<br /><span>'+third_about+'</span><br />\n';
					if (third_button_text!='' && third_button_link!='') buffer+='	<br /><br /><a href="'+third_button_link+'" class="button_regular button_hover_effect" data-hovercolor="#1fdab5" data-hoveroutcolor="#1ab99b">'+third_button_text+'</a>\n';
					buffer+='</div>\n';
				}
			}
			
			$('#item_'+i+'_content').val(buffer);
		
		}

		all_around_control_changed_callback_for_all = function(name, value) {
			//	console.log('Changed: '+name+' = '+value);
			if (name=='settings_param_hv_switch') {
				if (value==0) $('#settings_param_wrapper_text_max_height_span').html('Slider height:');
				if (value==1) $('#settings_param_wrapper_text_max_height_span').html('Slider width:');
			}
			if (name.indexOf('_f1_')>-1 || name.indexOf('_f2_')>-1 || name.indexOf('_f3_')>-1 || name.indexOf('_f4_')>-1 || name.indexOf('_title')>-1) {
				var p=name.indexOf('_',5);
				var is=name.substring(5, p);
				var i=parseInt(is,10);
				all_around_fields_changed[i]=1;
				var type=$('#item_'+i+'_content_type').val();
				all_around_update_content(type, i);
			}
		}
		
		if (all_around_should_check_for_update==1) {
			all_around_send_ajax('all_around_get_responder_answer', 'action2=check_for_update&var1='+all_around_version, function(response) {
				if (response.status==1) {
					var r=response.data;
					if (r.substring(0,3)=='New') {
						//console.log(response.data);
						$notification=$('#all_around_update_notification');
						$notification.html(response.data);
						$notification.fadeIn('slow');
					}
				}
			});
		}

		$('#all_around_save_button').on('click', function(e) {
			e.preventDefault();
			$('#all_around_save_loader').show();
			var postForm = $('#form1').serialize();
			postForm=postForm.replace(/\&/g, '[odvoji]');
			//$('#save-loader').show();
			//alert(postForm);
			var data='all_around_data=' + postForm;
			all_around_send_ajax(all_around_ajax_save_handler, data, function(response, type, status){
				//alert("Saved\nstatus="+status+"\ntype="+type+"\ndata="+response.data);
				$('#all_around_save_loader').hide();
				$('#all_around_save_status').fadeIn('slow', function(){
					$(this).fadeOut('slow');
				});
				var id='';
				if (typeof response.id!='undefined') id=response.id;
				if (response.status==2) {
					window.location=all_around_admin_url+'&action=edit&id='+response.id;
					return;
				}
				//$('#usquare_id').val(response);
				//$('#save-loader').hide();
				//saved_alert ();
			});
		});

		$('#all_around_add_new_item').on('click', function(e) {
			e.preventDefault();
			var count=$('li.all_around_primary_sortable').length;
			all_around_send_ajax('all_around_add_subitem', 'count='+count, function(response) {
				//alert("New item\nstatus="+response.status+"\ndata="+response.data);
				if (response.status==1) {
					$('#all_around_sortable').append('<li class="ui-state-default all_around_primary_sortable">'+response.data+'</li>');
					all_around_update_content(0, count);
				}
			});
		});
		function all_around_add_new_item_from_post(id) {
			var count=$('li.all_around_primary_sortable').length;
			all_around_send_ajax('all_around_add_subitem', 'count='+count+'&from_post='+id, function(response) {
				//alert("New item\nstatus="+response.status+"\ndata="+response.data);
				if (response.status==1) {
					$('#all_around_sortable').append('<li class="ui-state-default all_around_primary_sortable">'+response.data+'</li>');
				}
			});
		}
		function all_around_add_new_item_from_category(id) {
			var count=$('li.all_around_primary_sortable').length;
			all_around_send_ajax('all_around_add_subitem_from_category', 'count='+count+'&category='+id, function(response) {
				//alert("New item\nstatus="+response.status+"\ndata="+response.data);
				if (response.status==1) {
					var arr=all_around_get_ajax_array(response);
					for (i=0; i<arr.length; i++) {
						//console.log(i + ' = ' + arr[i]);
						$('#all_around_sortable').append('<li class="ui-state-default all_around_primary_sortable">'+arr[i]+'</li>');
					}
				}
				all_around_remove_popup();
			});
		}
		//all_around_add_new_item_from_category(1);
		
		$('.all_around_delete').on('click', function(e) {
			e.preventDefault();
			var url=$(this).attr('href');
			//alert(url);
			var r=confirm("Are you sure you want delete this slider?");
			if (r==true) {
				window.location=url;
			}
		});
		
		
		$('#all_around_preview_button').click(function(e){
			e.preventDefault();
			all_around_create_popup('Preview', '', 98, '%', 600);
			var postForm = $('#form1').serialize();
			postForm=postForm.replace(/\&/g, '[odvoji]');
			var data='all_around_data=' + postForm;
			all_around_send_ajax(all_around_ajax_preview_handler, data, function(response) {
				//alert(response.data);
				$('#all_around_loader').remove();
				$('#all_around_popup_holder').html(response.data);
			});
		});

		$('#all_around_add_new_from_post').click(function(e){
			var buffer='<label for="all_around_search_input">Search posts:</label><input id="all_around_search_input" style="width:260px;" name="all_around_search_input">';
			buffer+='<ul id="all_around_search_ul" style=""></ul>';

			all_around_create_popup('Insert from post', buffer, 450, 'px', 250, 0);
			
			$('#all_around_search_input').focus();
			$('#all_around_search_input').keyup(function(e){
				var qinput = $(this).val();
				console.log('qinput='+qinput);
				$('#all_around_search_ul').html('<li style="text-align: center;">'+all_around_loader_image+'</li>');

				all_around_send_ajax('all_around_post_search', 'query='+qinput, function(response, dataType, success) {
					//alert(response.data);
					//console.log('dataType='+dataType+', success='+success);
					if (dataType=='text' || success==0) return;
					$('#all_around_search_ul').html(response.data);
					$('.all_around_search_li_a').click(function(e) {
						e.preventDefault();
						var id=$(this).attr('data-id');
						all_around_add_new_item_from_post(id);
						all_around_remove_popup();
					});
				});
			});
		});

		$('#all_around_add_new_from_category').click(function(e){
			all_around_create_popup('Insert from category', '', 450, 'px', 100, 0, 0, 27, 130);
			all_around_send_ajax('all_around_get_categories_listbox', '', function(response) {
				var buffer='<div style="position: relative; margin-left: 40px; margin-top: 20px;"><label for="all_around_search_input">Choose category: </label>'+response.data;
				buffer+=' <a class="fbuilder_gradient fbuilder_button fbuilder_toggle_clear left" id="all_around_category_button" style="float: none; padding: 5px; display: inline-block; width: 50px;">Add</a></div>';
				all_around_set_popup_content(buffer);
				$('#all_around_category_button').click(function(e){
					var val=$('#all_around_category_select').val();
					//alert(val);
					all_around_set_popup_content(all_around_loader_image2);
					all_around_add_new_item_from_category(val);
				});
			});
		});
		
		$('.all_around_delete_subitem').live('click',function(e){
			console.log('link click');
			e.preventDefault();
			e.stopPropagation();
			var r=confirm("Are you sure you want delete this item?");
			if (r==true) {
				$(this).parents('.all_around_primary_sortable').remove();
			}
		});


	});

})(jQuery);