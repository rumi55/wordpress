/*
* Title                   : Wall/Grid Gallery (WordPress Plugin)
* Version                 : 1.8
* File                    : dopwgg-backend.js
* File Version            : 1.7
* Created / Last Modified : 25 March 2013
* Author                  : Dot on Paper
* Copyright               : © 2011 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Wall/Grid Gallery Admin Scripts.
*/

//Declare global variables.
var currGallery = 0,
currImage = 0,
clearClick = true,
imageDisplay = false,
imageWidth = 0,
imageHeight = 0,
$jDOPWGG = jQuery.noConflict();

$jDOPWGG(document).ready(function(){
    dopwggResize();

    $jDOPWGG(window).resize(function(){
        dopwggResize();
    });
    
    $jDOPWGG(document).scroll(function(){
        dopwggResize();
    });

    switch (DOPWGG_curr_page){
        case 'Galleries List':
            dopwggShowGalleries();
            break;
    }
});

function dopwggResize(){// ResiE admin panel.
    $jDOPWGG('.column2', '.DOPWGG-admin').width(($jDOPWGG('.DOPWGG-admin').width()-$jDOPWGG('.column1', '.DOPWGG-admin').width()-2)/2);
    $jDOPWGG('.column3', '.DOPWGG-admin').width(($jDOPWGG('.DOPWGG-admin').width()-$jDOPWGG('.column1', '.DOPWGG-admin').width()-2)/2);
    $jDOPWGG('.column-separator', '.DOPWGG-admin').height(0);
    $jDOPWGG('.column-separator', '.DOPWGG-admin').height($jDOPWGG('.DOPWGG-admin').height()-$jDOPWGG('h2', '.DOPWGG-admin').height()-parseInt($jDOPWGG('h2', '.DOPWGG-admin').css('padding-top'))-parseInt($jDOPWGG('h2', '.DOPWGG-admin').css('padding-bottom')));
    $jDOPWGG('.main', '.DOPWGG-admin').css('display', 'block');

    $jDOPWGG('.column-input', '.DOPWGG-admin').width($jDOPWGG('.column-content', '.column3', '.DOPWGG-admin').width()-20);
    $jDOPWGG('.column-image', '.DOPWGG-admin').width($jDOPWGG('.column-input', '.DOPWGG-admin').width()+10);
    
    if (imageDisplay){
        $jDOPWGG('span', '.column-image', '.DOPWGG-admin').width($jDOPWGG('.column-image', '.DOPWGG-admin').width());
        $jDOPWGG('span', '.column-image', '.DOPWGG-admin').height($jDOPWGG('.column-image', '.DOPWGG-admin').width()*imageHeight/imageWidth);
        $jDOPWGG('img', '.column-image', '.DOPWGG-admin').width($jDOPWGG('span', '.column-image', '.DOPWGG-admin').width());
        $jDOPWGG('img', '.column-image', '.DOPWGG-admin').height($jDOPWGG('span', '.column-image', '.DOPWGG-admin').height());
        $jDOPWGG('img', '.column-image', '.DOPWGG-admin').css('margin-top', 0);
        $jDOPWGG('img', '.column-image', '.DOPWGG-admin').css('margin-left', 0);
    }
}

// Galleries

function dopwggShowGalleries(){// Show all galleries.
    dopwggRemoveColumns(2);
    dopwggToggleMessage('show', DOPWGG_LOAD);
    
    $jDOPWGG.post(ajaxurl, {action: 'dopwgg_show_galleries'}, function(data){
        $jDOPWGG('.column-content', '.column1', '.DOPWGG-admin').html(data);
        dopwggGalleriesEvents();
        dopwggToggleMessage('hide', DOPWGG_GALLERIES_LOADED);
    });
}

function dopwggAddGallery(){// Add gallery via AJAX.
    if (clearClick){
        dopwggRemoveColumns(2);
        dopwggToggleMessage('show', DOPWGG_ADD_GALLERY_SUBMITED);
        
        $jDOPWGG.post(ajaxurl, {action: 'dopwgg_add_gallery'}, function(data){
            $jDOPWGG('.column-content', '.column1', '.DOPWGG-admin').html(data);
            dopwggGalleriesEvents();
            dopwggToggleMessage('hide', DOPWGG_ADD_GALERRY_SUCCESS);
        });
    }
}

function dopwggShowDefaultSettings(){// Show default settings.
    if (clearClick){
        $jDOPWGG('li', '.column1', '.DOPWGG-admin').removeClass('item-selected');
        currGallery = 0;
        currImage = 0;
        dopwggRemoveColumns(2);
        $jDOPWGG('#gallery_id').val(0);
        dopwggToggleMessage('show', DOPWGG_LOAD);
        
        $jDOPWGG.post(ajaxurl, {action: 'dopwgg_show_gallery_settings',
                                gallery_id: $jDOPWGG('#gallery_id').val(),
                                settings_id: 0}, function(data){
            var HeaderHTML = new Array(),
            json = $jDOPWGG.parseJSON(data);

            HeaderHTML.push('<input type="button" name="DOPWGG_gallery_submit" class="submit-style" onclick="dopwggEditGallerySettings()" title="'+DOPWGG_EDIT_GALLERIES_SUBMIT+'" value="'+DOPWGG_SUBMIT+'" />');
            HeaderHTML.push('<a href="javascript:void()" class="header-help" title="'+DOPWGG_GALLERIES_EDIT_INFO_HELP+'"></a>');

            $jDOPWGG('.column-header', '.column2', '.DOPWGG-admin').html(HeaderHTML.join(''));
            dopwggSettingsForm(json, 2);

            dopwggResize();
            dopwggToggleMessage('hide', DOPWGG_GALLERY_LOADED);
        });
    }
}

function dopwggShowGallerySettings(){// Show gallery settings.
    if (clearClick){
        $jDOPWGG('li', '.column2', '.DOPWGG-admin').removeClass('item-image-selected');
        dopwggRemoveColumns(3);
        dopwggToggleMessage('show', DOPWGG_LOAD);
        
        $jDOPWGG.post(ajaxurl, {action: 'dopwgg_show_gallery_settings',
                                gallery_id: $jDOPWGG('#gallery_id').val(),
                                settings_id: 0}, function(data){
            var HeaderHTML = new Array(),
            json = $jDOPWGG.parseJSON(data);
            
            HeaderHTML.push('<input type="button" name="DOPWGG_gallery_submit" class="submit-style" onclick="dopwggEditGallerySettings()" title="'+DOPWGG_EDIT_GALLERY_SUBMIT+'" value="'+DOPWGG_SUBMIT+'" />');
            HeaderHTML.push('<input type="button" name="DOPWGG_gallery_delete" class="submit-style" onclick="dopwggDeleteGallery('+$jDOPWGG('#gallery_id').val()+')" title="'+DOPWGG_DELETE_GALLERY_SUBMIT+'" value="'+DOPWGG_DELETE+'" />');
            HeaderHTML.push('<a href="javascript:void()" class="header-help" title="'+DOPWGG_GALLERY_EDIT_INFO_HELP+'"></a>');
            HeaderHTML.push('<input type="button" name="DOPWGG_gallery_use_settings" class="submit-style right" onclick="dopwggDefaultGallery()" title="'+DOPWGG_DEFAULT+'" value="'+DOPWGG_DEFAULT+'" />');
            HeaderHTML.push('<select name="DOPWGG_gallery_predefined_settings" id="DOPWGG_gallery_predefined_settings" class="select-style right">'+json['predefined_settings']+'</select>');
            
            $jDOPWGG('.column-header', '.column3', '.DOPWGG-admin').html(HeaderHTML.join(''));
            dopwggSettingsForm(json, 3);
            
            dopwggResize();
            dopwggToggleMessage('hide', DOPWGG_GALLERY_LOADED);
        });
    }
}

function dopwggEditGallerySettings(){// Edit Gallery Settings.
    if (clearClick){
        dopwggToggleMessage('show', DOPWGG_SAVE);
        
        $jDOPWGG.post(ajaxurl, {action:'dopwgg_edit_gallery_settings',
                                gallery_id: $jDOPWGG('#gallery_id').val(),
                                name: $jDOPWGG('#name').val(),
                                data_parse_method: $jDOPWGG('#data_parse_method').val(),
                                width: $jDOPWGG('#width').val(),
                                height: $jDOPWGG('#height').val(),
                                bg_color: $jDOPWGG('#bg_color').val(),
                                bg_alpha: $jDOPWGG('#bg_alpha').val(),
                                no_lines: $jDOPWGG('#no_lines').val(),
                                no_columns: $jDOPWGG('#no_columns').val(),   
                                images_order: $jDOPWGG('#images_order').val(),   
                                responsive_enabled: $jDOPWGG('#responsive_enabled').val(),                      
                                thumbnails_spacing: $jDOPWGG('#thumbnails_spacing').val(),
                                thumbnails_padding_top: $jDOPWGG('#thumbnails_padding_top').val(),
                                thumbnails_padding_right: $jDOPWGG('#thumbnails_padding_right').val(),
                                thumbnails_padding_bottom: $jDOPWGG('#thumbnails_padding_bottom').val(),
                                thumbnails_padding_left: $jDOPWGG('#thumbnails_padding_left').val(),
                                thumbnails_navigation : $jDOPWGG('#thumbnails_navigation').val(),
                                thumbnails_scroll_scrub_color: $jDOPWGG('#thumbnails_scroll_scrub_color').val(),
                                thumbnails_scroll_bar_color: $jDOPWGG('#thumbnails_scroll_bar_color').val(),
                                thumbnails_info: $jDOPWGG('#thumbnails_info').val(),
                                thumbnail_width: $jDOPWGG('#thumbnail_width').val(),
                                thumbnail_height: $jDOPWGG('#thumbnail_height').val(),
                                thumbnail_width_mobile: $jDOPWGG('#thumbnail_width_mobile').val(),
                                thumbnail_height_mobile: $jDOPWGG('#thumbnail_height_mobile').val(),
                                thumbnail_alpha: $jDOPWGG('#thumbnail_alpha').val(),
                                thumbnail_alpha_hover: $jDOPWGG('#thumbnail_alpha_hover').val(),
                                thumbnail_bg_color: $jDOPWGG('#thumbnail_bg_color').val(),
                                thumbnail_bg_color_hover: $jDOPWGG('#thumbnail_bg_color_hover').val(),
                                thumbnail_border_size: $jDOPWGG('#thumbnail_border_size').val(),
                                thumbnail_border_color: $jDOPWGG('#thumbnail_border_color').val(),
                                thumbnail_border_color_hover: $jDOPWGG('#thumbnail_border_color_hover').val(),
                                thumbnail_padding_top: $jDOPWGG('#thumbnail_padding_top').val(),
                                thumbnail_padding_right: $jDOPWGG('#thumbnail_padding_right').val(),
                                thumbnail_padding_bottom: $jDOPWGG('#thumbnail_padding_bottom').val(),
                                thumbnail_padding_left: $jDOPWGG('#thumbnail_padding_left').val(),
                                lightbox_position: $jDOPWGG('#lightbox_position').val(),
                                lightbox_window_color: $jDOPWGG('#lightbox_window_color').val(),
                                lightbox_window_alpha: $jDOPWGG('#lightbox_window_alpha').val(),
                                lightbox_bg_color: $jDOPWGG('#lightbox_bg_color').val(),
                                lightbox_bg_alpha: $jDOPWGG('#lightbox_bg_alpha').val(),
                                lightbox_margin_top: $jDOPWGG('#lightbox_margin_top').val(),
                                lightbox_margin_right: $jDOPWGG('#lightbox_margin_right').val(),
                                lightbox_margin_bottom: $jDOPWGG('#lightbox_margin_bottom').val(),
                                lightbox_margin_left: $jDOPWGG('#lightbox_margin_left').val(),
                                lightbox_padding_top: $jDOPWGG('#lightbox_padding_top').val(),
                                lightbox_padding_right: $jDOPWGG('#lightbox_padding_right').val(),
                                lightbox_padding_bottom: $jDOPWGG('#lightbox_padding_bottom').val(),
                                lightbox_padding_left: $jDOPWGG('#lightbox_padding_left').val(),
                                caption_height: $jDOPWGG('#caption_height').val(),
                                caption_title_color: $jDOPWGG('#caption_title_color').val(),
                                caption_text_color: $jDOPWGG('#caption_text_color').val(),
                                caption_scroll_scrub_color: $jDOPWGG('#caption_scroll_scrub_color').val(),
                                caption_scroll_bg_color: $jDOPWGG('#caption_scroll_bg_color').val(),
                                social_share_enabled: $jDOPWGG('#social_share_enabled').val(),
                                tooltip_bg_color: $jDOPWGG('#tooltip_bg_color').val(),
                                tooltip_stroke_color: $jDOPWGG('#tooltip_stroke_color').val(),
                                tooltip_text_color: $jDOPWGG('#tooltip_text_color').val(),
                                label_text_color: $jDOPWGG('#label_text_color').val(),
                                label_position: $jDOPWGG('#label_position').val(),
                                label_text_color_hover: $jDOPWGG('#label_text_color_hover').val()}, function(data){
            if ($jDOPWGG('#gallery_id').val() != '0'){
                $jDOPWGG('.name', '#DOPWGG-ID-'+$jDOPWGG('#gallery_id').val()).html($jDOPWGG('#name').val());
                dopwggToggleMessage('hide', DOPWGG_EDIT_GALLERY_SUCCESS);
            }
            else{
                dopwggToggleMessage('hide', DOPWGG_EDIT_GALLERIES_SUCCESS);
            }
        });
    }
}

function dopwggDefaultGallery(){// Add default settings to gallery.
    if (clearClick){
        if (confirm(DOPWGG_EDIT_GALLERY_USE_DEFAULT_CONFIRMATION)){
            dopwggToggleMessage('show', DOPWGG_SAVE);
            
            $jDOPWGG.post(ajaxurl, {action: 'dopwgg_show_gallery_settings',
                                    gallery_id: 0,
                                    settings_id: $jDOPWGG('#DOPWGG_gallery_predefined_settings').val()}, function(data){
                var json = $jDOPWGG.parseJSON(data);
                
                $jDOPWGG('#width').val(json['width']);
                $jDOPWGG('#height').val(json['height']);
                $jDOPWGG('#bg_color').val(json['bg_color']);
                $jDOPWGG('#bg_alpha').val(json['bg_alpha']);
                $jDOPWGG('#no_lines').val(json['no_lines']);
                $jDOPWGG('#no_columns').val(json['no_columns']);
                $jDOPWGG('#images_order').val(json['images_order']);
                $jDOPWGG('#responsive_enabled').val(json['responsive_enabled']);                
               
                $jDOPWGG('#thumbnails_spacing').val(json['thumbnails_spacing']);
                $jDOPWGG('#thumbnails_padding_top').val(json['thumbnails_padding_top']);
                $jDOPWGG('#thumbnails_padding_right').val(json['thumbnails_padding_right']);
                $jDOPWGG('#thumbnails_padding_bottom').val(json['thumbnails_padding_bottom']);
                $jDOPWGG('#thumbnails_padding_left').val(json['thumbnails_padding_left']);
                $jDOPWGG('#thumbnails_navigation').val(json['thumbnails_navigation']);
                $jDOPWGG('#thumbnails_scroll_scrub_color').val(json['thumbnails_scroll_scrub_color']);
                $jDOPWGG('#thumbnails_scroll_bar_color').val(json['thumbnails_scroll_bar_color']); 
                $jDOPWGG('#thumbnails_info').val(json['thumbnails_info']);              

                $jDOPWGG('#thumbnail_loader_image').html('<img src="'+DOPWGG_plugin_url+json['thumbnail_loader']+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
                $jDOPWGG('#thumbnail_width').val(json['thumbnail_width']);
                $jDOPWGG('#thumbnail_height').val(json['thumbnail_height']);
                $jDOPWGG('#thumbnail_width_mobile').val(json['thumbnail_width_mobile']);
                $jDOPWGG('#thumbnail_height_mobile').val(json['thumbnail_height_mobile']);
                $jDOPWGG('#thumbnail_alpha').val(json['thumbnail_alpha']);
                $jDOPWGG('#thumbnail_alpha_hover').val(json['thumbnail_alpha_hover']);
                $jDOPWGG('#thumbnail_bg_color').val(json['thumbnail_bg_color']);
                $jDOPWGG('#thumbnail_bg_color_hover').val(json['thumbnail_bg_color_hover']);
                $jDOPWGG('#thumbnail_border_size').val(json['thumbnail_border_size']);
                $jDOPWGG('#thumbnail_border_color').val(json['thumbnail_border_color']);
                $jDOPWGG('#thumbnail_border_color_hover').val(json['thumbnail_border_color_hover']);
                $jDOPWGG('#thumbnail_padding_top').val(json['thumbnail_padding_top']);
                $jDOPWGG('#thumbnail_padding_right').val(json['thumbnail_padding_right']);
                $jDOPWGG('#thumbnail_padding_bottom').val(json['thumbnail_padding_bottom']);
                $jDOPWGG('#thumbnail_padding_left').val(json['thumbnail_padding_left']);
                
                $jDOPWGG('#lightbox_position').val(json['lightbox_position']);
                $jDOPWGG('#lightbox_window_color').val(json['lightbox_window_color']);
                $jDOPWGG('#lightbox_window_alpha').val(json['lightbox_window_alpha']);
                $jDOPWGG('#lightbox_loader_image').html('<img src="'+DOPWGG_plugin_url+json['lightbox_loader']+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
                $jDOPWGG('#lightbox_bg_color').val(json['lightbox_bg_color']);
                $jDOPWGG('#lightbox_bg_alpha').val(json['lightbox_bg_alpha']);
                $jDOPWGG('#lightbox_margin_top').val(json['lightbox_margin_top']);
                $jDOPWGG('#lightbox_margin_right').val(json['lightbox_margin_right']);
                $jDOPWGG('#lightbox_margin_bottom').val(json['lightbox_margin_bottom']);
                $jDOPWGG('#lightbox_margin_left').val(json['lightbox_margin_left']);
                $jDOPWGG('#lightbox_padding_top').val(json['lightbox_padding_top']);
                $jDOPWGG('#lightbox_padding_right').val(json['lightbox_padding_right']);
                $jDOPWGG('#lightbox_padding_bottom').val(json['lightbox_padding_bottom']);
                $jDOPWGG('#lightbox_padding_left').val(json['lightbox_padding_left']);
                $jDOPWGG('#lightbox_navigation_prev_image').html('<img src="'+DOPWGG_plugin_url+json['lightbox_navigation_prev']+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
                $jDOPWGG('#lightbox_navigation_prev_hover_image').html('<img src="'+DOPWGG_plugin_url+json['lightbox_navigation_prev_hover']+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
                $jDOPWGG('#lightbox_navigation_next_image').html('<img src="'+DOPWGG_plugin_url+json['lightbox_navigation_next']+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
                $jDOPWGG('#lightbox_navigation_next_hover_image').html('<img src="'+DOPWGG_plugin_url+json['lightbox_navigation_next_hover']+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
                $jDOPWGG('#lightbox_navigation_close_image').html('<img src="'+DOPWGG_plugin_url+json['lightbox_navigation_close']+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
                $jDOPWGG('#lightbox_navigation_close_hover_image').html('<img src="'+DOPWGG_plugin_url+json['lightbox_navigation_close_hover']+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
                               
                $jDOPWGG('#caption_height').val(json['caption_height']);
                $jDOPWGG('#caption_title_color').val(json['caption_title_color']);
                $jDOPWGG('#caption_text_color').val(json['caption_text_color']);
                $jDOPWGG('#caption_scroll_scrub_color').val(json['caption_scroll_scrub_color']);
                $jDOPWGG('#caption_scroll_bg_color').val(json['caption_scroll_bg_color']);
                
                $jDOPWGG('#social_share_enabled').val(json['social_share_enabled']);  
                $jDOPWGG('#social_share_lightbox_image').html('<img src="'+DOPWGG_plugin_url+json['social_share_lightbox']+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
                
                $jDOPWGG('#tooltip_bg_color').val(json['tooltip_bg_color']);
                $jDOPWGG('#tooltip_stroke_color').val(json['tooltip_stroke_color']);
                $jDOPWGG('#tooltip_text_color').val(json['tooltip_text_color']);
                
                $jDOPWGG('#label_position').val(json['label_position']);
                $jDOPWGG('#label_text_color').val(json['label_text_color']);
                $jDOPWGG('#label_text_color_hover').val(json['label_text_color_hover']);
    
                $jDOPWGG('#bg_color').removeAttr('style').css({'background-color': '#'+json['bg_color'],
                                                               'color': dopwggIdealTextColor(json['bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#thumbnails_scroll_scrub_color').removeAttr('style').css({'background-color': '#'+json['thumbnails_scroll_scrub_color'],
                                                                                    'color': dopwggIdealTextColor(json['thumbnails_scroll_scrub_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#thumbnails_scroll_bar_color').removeAttr('style').css({'background-color': '#'+json['thumbnails_scroll_bar_color'],
                                                                                  'color': dopwggIdealTextColor(json['thumbnails_scroll_bar_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#thumbnail_bg_color').removeAttr('style').css({'background-color': '#'+json['thumbnail_bg_color'],
                                                                         'color': dopwggIdealTextColor(json['thumbnail_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#thumbnail_bg_color_hover').removeAttr('style').css({'background-color': '#'+json['thumbnail_bg_color_hover'],
                                                                               'color': dopwggIdealTextColor(json['thumbnail_bg_color_hover']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#thumbnail_border_color').removeAttr('style').css({'background-color': '#'+json['thumbnail_border_color'],
                                                                             'color': dopwggIdealTextColor(json['thumbnail_border_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#thumbnail_border_color_hover').removeAttr('style').css({'background-color': '#'+json['thumbnail_border_color_hover'],
                                                                                   'color': dopwggIdealTextColor(json['thumbnail_border_color_hover']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#lightbox_window_color').removeAttr('style').css({'background-color': '#'+json['lightbox_window_color'],
                                                                            'color': dopwggIdealTextColor(json['lightbox_window_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#lightbox_bg_color').removeAttr('style').css({'background-color': '#'+json['lightbox_bg_color'],
                                                                        'color': dopwggIdealTextColor(json['lightbox_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#caption_title_color').removeAttr('style').css({'background-color': '#'+json['caption_title_color'],
                                                                          'color': dopwggIdealTextColor(json['caption_title_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#caption_text_color').removeAttr('style').css({'background-color': '#'+json['caption_text_color'],
                                                                         'color': dopwggIdealTextColor(json['caption_text_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#caption_scroll_scrub_color').removeAttr('style').css({'background-color': '#'+json['caption_scroll_scrub_color'],
                                                                                 'color': dopwggIdealTextColor(json['caption_scroll_scrub_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#caption_scroll_bg_color').removeAttr('style').css({'background-color': '#'+json['caption_scroll_bg_color'],
                                                                              'color': dopwggIdealTextColor(json['caption_scroll_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#tooltip_bg_color').removeAttr('style').css({'background-color': '#'+json['tooltip_bg_color'],
                                                                       'color': dopwggIdealTextColor(json['tooltip_bg_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#tooltip_stroke_color').removeAttr('style').css({'background-color': '#'+json['tooltip_stroke_color'],
                                                                           'color': dopwggIdealTextColor(json['tooltip_stroke_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#tooltip_text_color').removeAttr('style').css({'background-color': '#'+json['tooltip_text_color'],
                                                                         'color': dopwggIdealTextColor(json['tooltip_text_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#label_text_color').removeAttr('style').css({'background-color': '#'+json['label_text_color'],
                                                                       'color': dopwggIdealTextColor(json['label_text_color']) == 'white' ? '#ffffff':'#0000000'});
                $jDOPWGG('#label_text_color_hover').removeAttr('style').css({'background-color': '#'+json['label_text_color_hover'],
                                                                             'color': dopwggIdealTextColor(json['label_text_color_hover']) == 'white' ? '#ffffff':'#0000000'});
                
                $jDOPWGG.post(ajaxurl, {action:'dopwgg_edit_gallery_settings',
                                        gallery_id: $jDOPWGG('#gallery_id').val(),
                                        name: $jDOPWGG('#name').val(),
                                        data_parse_method: $jDOPWGG('#data_parse_method').val(),
                                        width: $jDOPWGG('#width').val(),
                                        height: $jDOPWGG('#height').val(),
                                        bg_color: $jDOPWGG('#bg_color').val(),
                                        bg_alpha: $jDOPWGG('#bg_alpha').val(),
                                        no_lines: $jDOPWGG('#no_lines').val(),
                                        no_columns: $jDOPWGG('#no_columns').val(), 
                                        images_order: $jDOPWGG('#images_order').val(),  
                                        responsive_enabled: $jDOPWGG('#responsive_enabled').val(),  
                                        thumbnails_spacing: $jDOPWGG('#thumbnails_spacing').val(),
                                        thumbnails_padding_top: $jDOPWGG('#thumbnails_padding_top').val(),
                                        thumbnails_padding_right: $jDOPWGG('#thumbnails_padding_right').val(),
                                        thumbnails_padding_bottom: $jDOPWGG('#thumbnails_padding_bottom').val(),
                                        thumbnails_padding_left: $jDOPWGG('#thumbnails_padding_left').val(),
                                        thumbnails_navigation : $jDOPWGG('#thumbnails_navigation').val(),
                                        thumbnails_scroll_scrub_color: $jDOPWGG('#thumbnails_scroll_scrub_color').val(),
                                        thumbnails_scroll_bar_color: $jDOPWGG('#thumbnails_scroll_bar_color').val(),
                                        thumbnails_info: $jDOPWGG('#thumbnails_info').val(),
                                        thumbnail_loader: data['thumbnail_loader'],
                                        thumbnail_width: $jDOPWGG('#thumbnail_width').val(),
                                        thumbnail_height: $jDOPWGG('#thumbnail_height').val(),
                                        thumbnail_width_mobile: $jDOPWGG('#thumbnail_width_mobile').val(),
                                        thumbnail_height_mobile: $jDOPWGG('#thumbnail_height_mobile').val(),
                                        thumbnail_alpha: $jDOPWGG('#thumbnail_alpha').val(),
                                        thumbnail_alpha_hover: $jDOPWGG('#thumbnail_alpha_hover').val(),
                                        thumbnail_bg_color: $jDOPWGG('#thumbnail_bg_color').val(),
                                        thumbnail_bg_color_hover: $jDOPWGG('#thumbnail_bg_color_hover').val(),
                                        thumbnail_border_size: $jDOPWGG('#thumbnail_border_size').val(),
                                        thumbnail_border_color: $jDOPWGG('#thumbnail_border_color').val(),
                                        thumbnail_border_color_hover: $jDOPWGG('#thumbnail_border_color_hover').val(),
                                        thumbnail_padding_top: $jDOPWGG('#thumbnail_padding_top').val(),
                                        thumbnail_padding_right: $jDOPWGG('#thumbnail_padding_right').val(),
                                        thumbnail_padding_bottom: $jDOPWGG('#thumbnail_padding_bottom').val(),
                                        thumbnail_padding_left: $jDOPWGG('#thumbnail_padding_left').val(),
                                        lightbox_position: $jDOPWGG('#lightbox_position').val(),
                                        lightbox_window_color: $jDOPWGG('#lightbox_window_color').val(),
                                        lightbox_window_alpha: $jDOPWGG('#lightbox_window_alpha').val(),
                                        lightbox_loader: data['lightbox_loader'],
                                        lightbox_bg_color: $jDOPWGG('#lightbox_bg_color').val(),
                                        lightbox_bg_alpha: $jDOPWGG('#lightbox_bg_alpha').val(),
                                        lightbox_margin_top: $jDOPWGG('#lightbox_margin_top').val(),
                                        lightbox_margin_right: $jDOPWGG('#lightbox_margin_right').val(),
                                        lightbox_margin_bottom: $jDOPWGG('#lightbox_margin_bottom').val(),
                                        lightbox_margin_left: $jDOPWGG('#lightbox_margin_left').val(),
                                        lightbox_padding_top: $jDOPWGG('#lightbox_padding_top').val(),
                                        lightbox_padding_right: $jDOPWGG('#lightbox_padding_right').val(),
                                        lightbox_padding_bottom: $jDOPWGG('#lightbox_padding_bottom').val(),
                                        lightbox_padding_left: $jDOPWGG('#lightbox_padding_left').val(),
                                        lightbox_navigation_prev: json['lightbox_navigation_prev'],
                                        lightbox_navigation_prev_hover: json['lightbox_navigation_prev_hover'],
                                        lightbox_navigation_next: json['lightbox_navigation_next'],
                                        lightbox_navigation_next_hover: json['lightbox_navigation_next_hover'],
                                        lightbox_navigation_close: json['lightbox_navigation_close'],
                                        lightbox_navigation_close_hover: json['lightbox_navigation_close_hover'],
                                        caption_height: $jDOPWGG('#caption_height').val(),
                                        caption_title_color: $jDOPWGG('#caption_title_color').val(),
                                        caption_text_color: $jDOPWGG('#caption_text_color').val(),
                                        caption_scroll_scrub_color: $jDOPWGG('#caption_scroll_scrub_color').val(),
                                        caption_scroll_bg_color: $jDOPWGG('#caption_scroll_bg_color').val(),
                                        social_share_enabled: $jDOPWGG('#social_share_enabled').val(),
                                        social_share_lightbox: json['social_share_lightbox'],    
                                        tooltip_bg_color: $jDOPWGG('#tooltip_bg_color').val(),
                                        tooltip_stroke_color: $jDOPWGG('#tooltip_stroke_color').val(),
                                        tooltip_text_color: $jDOPWGG('#tooltip_text_color').val(),
                                        label_position: $jDOPWGG('#label_position').val(),
                                        label_text_color: $jDOPWGG('#label_text_color').val(),
                                        label_text_color_hover: $jDOPWGG('#label_text_color_hover').val()}, function(data){
                    dopwggToggleMessage('hide', DOPWGG_EDIT_GALLERY_SUCCESS);
                });
            });
        }
    }
}

function dopwggDeleteGallery(id){// Delete gallery
    if (clearClick){
        if (confirm(DOPWGG_DELETE_GALLERY_CONFIRMATION)){
            dopwggToggleMessage('show', DOPWGG_DELETE_GALLERY_SUBMITED);
            
            $jDOPWGG.post(ajaxurl, {action:'dopwgg_delete_gallery', id:id}, function(data){
                dopwggRemoveColumns(2);
                
                $jDOPWGG('#DOPWGG-ID-'+id).stop(true, true).animate({'opacity':0}, 600, function(){
                    $jDOPWGG(this).remove();
                    
                    if (data == '0'){
                        $jDOPWGG('.column-content', '.column1', '.DOPWGG-admin').html('<ul><li class="no-data">'+DOPWGG_NO_GALLERIES+'</li></ul>');
                    }
                    dopwggToggleMessage('hide', DOPWGG_DELETE_GALERRY_SUCCESS);
                });
            });
        }
    }
}

function dopwggGalleriesEvents(){// Init Gallery Events.
    $jDOPWGG('li', '.column1', '.DOPWGG-admin').click(function(){
        if (clearClick){
            var id = $jDOPWGG(this).attr('id').split('-')[2];
            
            if (currGallery != id){
                currGallery = id;
                $jDOPWGG('li', '.column1', '.DOPWGG-admin').removeClass('item-selected');
                $jDOPWGG(this).addClass('item-selected');
                dopwggShowImages(id);
            }
        }
    });
}

// Images

function dopwggShowImages(gallery_id){// Show Images List.
    if (clearClick){
        $jDOPWGG('#gallery_id').val(gallery_id);
        dopwggRemoveColumns(2);
        dopwggToggleMessage('show', DOPWGG_LOAD);
        
        $jDOPWGG.post(ajaxurl, {action:'dopwgg_show_images', gallery_id:gallery_id}, function(data){
            var HeaderHTML = new Array();
            HeaderHTML.push('<div class="add-button">');
            HeaderHTML.push('    <a href="javascript:dopwggAddImages()" title="'+DOPWGG_ADD_IMAGE_SUBMIT+'"></a>');
            HeaderHTML.push('</div>');
            HeaderHTML.push('<div class="edit-button">');
            HeaderHTML.push('    <a href="javascript:dopwggShowGallerySettings()" title="'+DOPWGG_EDIT_GALLERY_SUBMIT+'"></a>');
            HeaderHTML.push('</div>');
            HeaderHTML.push('<a href="javascript:void()" class="header-help" title="'+DOPWGG_GALLERY_EDIT_HELP+'"></a>');
            
            $jDOPWGG('.column-header', '.column2', '.DOPWGG-admin').html(HeaderHTML.join(''));
            $jDOPWGG('.column-content', '.column2', '.DOPWGG-admin').html(data);
            $jDOPWGG('.column-content', '.column2', '.DOPWGG-admin').DOPImageLoader({'LoaderURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/no-image.png'});
            dopwggImagesEvents();
            dopwggToggleMessage('hide', DOPWGG_IMAGES_LOADED);
        });
    }
}

function dopwggImagesEvents(){// Init Images Events.
    $jDOPWGG('li', '.column2', '.DOPWGG-admin').click(function(){
        var id = $jDOPWGG(this).attr('id').split('-')[3];
        
        if (currImage != id && clearClick){
            $jDOPWGG('li', '.column2', '.DOPWGG-admin').removeClass('item-image-selected');
            $jDOPWGG(this).addClass('item-image-selected');
            dopwggShowImage(id);
        }
    });

    $jDOPWGG('ul', '.column2').sortable({opacity:0.6, cursor:'move', update:function(){
        if (clearClick){
            var data = '';
            
            dopwggToggleMessage('show', DOPWGG_SORT_IMAGES_SUBMITED);
            
            $jDOPWGG('li', '.column2', '.DOPWGG-admin').each(function(){
                data += $jDOPWGG(this).attr('id').split('-')[3]+',';
            });
            
            $jDOPWGG.post(ajaxurl, {action:'dopwgg_sort_images', gallery_id:$jDOPWGG('#gallery_id').val(), data:data}, function(data){
                dopwggToggleMessage('hide', DOPWGG_SORT_IMAGES_SUCCESS);
            });
        }
    },
    stop:function(){
        $jDOPWGG('li', '.column2').removeAttr('style');
    }});
}

function dopwggAddImages(){// Add Image/Images.
    if (clearClick){
        $jDOPWGG('li', '.column2', '.DOPWGG-admin').removeClass('item-image-selected');
        dopwggRemoveColumns(3);
        
        var uploadifyHTML = new Array(), HeaderHTML = new Array();
        HeaderHTML.push('<a href="javascript:void()" class="header-help" title="'+DOPWGG_ADD_IMAGES_HELP+'"></a>');

        uploadifyHTML.push('<h3 class="settings">'+DOPWGG_ADD_IMAGE_WP_UPLOAD+'</h3>');
        uploadifyHTML.push('<input name="dopwgg_wp_image" id="dopwgg_wp_image" type="button" value="'+DOPWGG_SELECT_IMAGES+'" class="select-images" />');
        uploadifyHTML.push('<a href="javascript:void()" class="header-help" title="'+DOPWGG_ADD_IMAGES_HELP_WP+'"></a><br class="DOPWGG-clear" />');

        uploadifyHTML.push('<h3 class="settings">'+DOPWGG_ADD_IMAGE_SIMPLE_UPLOAD+'</h3>');
        uploadifyHTML.push('<form action="'+DOPWGG_plugin_url+'libraries/php/upload.php?path='+DOPWGG_plugin_abs+'" method="post" enctype="multipart/form-data" id="dopwgg_ajax_upload_form" name="dopwgg_ajax_upload_form" target="dopwgg_upload_target" onsubmit="dopwggUploadImage()" >');
        uploadifyHTML.push('    <input name="dopwgg_image" type="file" onchange="$jDOPWGG(\'#dopwgg_ajax_upload_form\').submit(); return false;" style="margin:5px 0 0 10px"; />');
        uploadifyHTML.push('    <a href="javascript:void()" class="header-help" title="'+DOPWGG_ADD_IMAGES_HELP_AJAX+'"></a><br class="DOPWGG-clear" />');
        uploadifyHTML.push('</form>');
        uploadifyHTML.push('<iframe id="dopwgg_upload_target" name="dopwgg_upload_target" src="javascript:void(0)" style="display: none;"></iframe>');
        
        uploadifyHTML.push('<h3 class="settings">'+DOPWGG_ADD_IMAGE_MULTIPLE_UPLOAD+'</h3>');
        uploadifyHTML.push('<div class="uploadifyContainer" style="float:left; margin-top:5px;">');
        uploadifyHTML.push('    <div><input type="file" name="uploadify" id="uploadify" style="width:100px;" /></div>');
        uploadifyHTML.push('    <div id="fileQueue"></div>');
        uploadifyHTML.push('</div>');
        uploadifyHTML.push('<a href="javascript:void()" class="header-help" title="'+DOPWGG_ADD_IMAGES_HELP_UPLOADIFY+'"></a><br class="DOPWGG-clear" />');  
        
        uploadifyHTML.push('<h3 class="settings">'+DOPWGG_ADD_IMAGE_FTP_UPLOAD+'</h3>');
        uploadifyHTML.push('<input name="dopwgg_ftp_image" id="dopwgg_ftp_image" type="button" value="'+DOPWGG_SELECT_FTP_IMAGES+'" class="select-images" />');
        uploadifyHTML.push('<a href="javascript:void()" class="header-help" title="'+DOPWGG_ADD_IMAGES_HELP_FTP+'"></a><br class="DOPWGG-clear" />');

        $jDOPWGG('.column-header', '.column3', '.DOPWGG-admin').html(HeaderHTML.join(''));
        $jDOPWGG('.column-content', '.column3', '.DOPWGG-admin').html(uploadifyHTML.join(''));
        
        // Add Images from WP Media.
        
        $jDOPWGG('#dopwgg_wp_image').click(function(){
            if (clearClick){
                tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
            }
            return false;                
        });

        window.send_to_editor = function(html){
            dopwggToggleMessage('show', DOPWGG_ADD_IMAGE_SUBMITED);

            setTimeout(function(){
                dopwggResize();
            }, 100);
            
            $jDOPWGG.post(ajaxurl, {action:'dopwgg_add_image_wp', gallery_id:$jDOPWGG('#gallery_id').val(), image_url:$jDOPWGG('img', html).attr('src')}, function(data){
                var imageID = data.split(';;;')[0],
                imageName = data.split(';;;')[1];
                
                if ($jDOPWGG('ul', '.column2', '.DOPWGG-admin').html() == '<li class="no-data">'+DOPWGG_NO_IMAGES+'</li>'){
                    $jDOPWGG('ul', '.column2', '.DOPWGG-admin').html('<li class="item-image" id="DOPWGG-image-ID-'+imageID+'"><img src="'+DOPWGG_plugin_url+'uploads/thumbs/'+imageName+'" alt="" /></li>');
                }
                else{
                    $jDOPWGG('ul', '.column2', '.DOPWGG-admin').append('<li class="item-image" id="DOPWGG-image-ID-'+imageID+'"><img src="'+DOPWGG_plugin_url+'uploads/thumbs/'+imageName+'" alt="" /></li>');
                }

                dopwggResize();

                $jDOPWGG('#DOPWGG-image-ID-'+imageID).click(function(){
                    var id = $jDOPWGG(this).attr('id').split('-')[3];

                    if (currImage != id && clearClick){
                        $jDOPWGG('li', '.column2', '.DOPWGG-admin').removeClass('item-image-selected');
                        $jDOPWGG(this).addClass('item-image-selected');
                        dopwggShowImage(id);
                    }
                });

                $jDOPWGG('#DOPWGG-image-ID-'+imageID).DOPImageLoader({'LoaderURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/no-image.png'});
                
                dopwggToggleMessage('hide', DOPWGG_ADD_IMAGE_SUCCESS);
            });
            
            tb_remove();
        }

        // Add Images width Uploadify.
        
        $jDOPWGG('#uploadify').uploadify({
            'uploader'       : DOPWGG_plugin_url+'libraries/swf/uploadify.swf',
            'script'         : DOPWGG_plugin_url+'libraries/php/uploadify.php?path='+DOPWGG_plugin_abs,
            'cancelImg'      : DOPWGG_plugin_url+'libraries/gui/images/uploadify/cancel.png',
            'folder'         : '',
            'queueID'        : 'fileQueue',
            'buttonText'     : DOPWGG_SELECT_IMAGES,
            'auto'           : true,
            'multi'          : true,
            'onError'        : function (event,ID,fileObj,errorObj){
                                    alert(errorObj.type + ' Error: ' + errorObj.info);
                               },
            'onInit'         : function(){
                                   dopwggResize();
                               },
            'onCancel'         : function(event,ID,fileObj,data){
                                   dopwggResize();
                               },
            'onSelect'       : function(event, ID, fileObj){
                                   clearClick = false;
                                   dopwggToggleMessage('show', DOPWGG_ADD_IMAGE_SUBMITED);
                                   setTimeout(function(){
                                       dopwggResize();
                                   }, 100);
                               },
            'onComplete'     : function(event, ID, fileObj, response, data){                          
                                   if (response != '-1'){
                                       setTimeout(function(){
                                           dopwggResize();
                                       }, 1000);
                                       
                                       $jDOPWGG.post(ajaxurl, {action:'dopwgg_add_image', gallery_id:$jDOPWGG('#gallery_id').val(), name:response}, function(data){
                                           if ($jDOPWGG('ul', '.column2', '.DOPWGG-admin').html() == '<li class="no-data">'+DOPWGG_NO_IMAGES+'</li>'){
                                               $jDOPWGG('ul', '.column2', '.DOPWGG-admin').html('<li class="item-image" id="DOPWGG-image-ID-'+data+'"><img src="'+DOPWGG_plugin_url+'uploads/thumbs/'+response+'" alt="" /></li>');
                                           }
                                           else{
                                               $jDOPWGG('ul', '.column2', '.DOPWGG-admin').append('<li class="item-image" id="DOPWGG-image-ID-'+data+'"><img src="'+DOPWGG_plugin_url+'uploads/thumbs/'+response+'" alt="" /></li>');
                                           }
                                           dopwggResize();
                                       
                                           $jDOPWGG('#DOPWGG-image-ID-'+data).click(function(){
                                               var id = $jDOPWGG(this).attr('id').split('-')[3];
                                               if (currImage != id && clearClick){
                                                   $jDOPWGG('li', '.column2', '.DOPWGG-admin').removeClass('item-image-selected');
                                                   $jDOPWGG(this).addClass('item-image-selected');
                                                   dopwggShowImage(id);
                                               }
                                           });
                                           $jDOPWGG('#DOPWGG-image-ID-'+data).DOPImageLoader({'LoaderURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/no-image.png'});
                                       });
                                   }
                               },
            'onAllComplete'  : function(event, data){
                                   dopwggToggleMessage('hide', DOPWGG_ADD_IMAGE_SUCCESS);
                               }
        });
        
        // Add Images from FTP.
                
        $jDOPWGG('#dopwgg_ftp_image').click(function(){
            if (clearClick){
                dopwggToggleMessage('show', DOPWGG_ADD_IMAGE_SUBMITED);

                $jDOPWGG.post(ajaxurl, {action:'dopwgg_add_image_ftp', gallery_id:$jDOPWGG('#gallery_id').val()}, function(data){
                    var images = data.split(';;;;;'), 
                    i, imageName, imageID;
                    
                    for (i=0; i<images.length; i++){
                        imageID = images[i].split(';;;')[0];
                        imageName = images[i].split(';;;')[1];
                        
                        if (imageName != undefined){
                            if ($jDOPWGG('ul', '.column2', '.DOPWGG-admin').html() == '<li class="no-data">'+DOPWGG_NO_IMAGES+'</li>'){
                                $jDOPWGG('ul', '.column2', '.DOPWGG-admin').html('<li class="item-image" id="DOPWGG-image-ID-'+imageID+'"><img src="'+DOPWGG_plugin_url+'uploads/thumbs/'+imageName+'" alt="" /></li>');
                            }
                            else{
                                $jDOPWGG('ul', '.column2', '.DOPWGG-admin').append('<li class="item-image" id="DOPWGG-image-ID-'+imageID+'"><img src="'+DOPWGG_plugin_url+'uploads/thumbs/'+imageName+'" alt="" /></li>');
                            }

                            dopwggResize();

                            $jDOPWGG('#DOPWGG-image-ID-'+imageID).click(function(){
                                var id = $jDOPWGG(this).attr('id').split('-')[3];

                                if (currImage != id && clearClick){
                                    $jDOPWGG('li', '.column2', '.DOPWGG-admin').removeClass('item-image-selected');
                                    $jDOPWGG(this).addClass('item-image-selected');
                                    dopwggShowImage(id);
                                }
                            });

                            $jDOPWGG('#DOPWGG-image-ID-'+imageID).DOPImageLoader({'LoaderURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/no-image.png'});
                        }
                    }

                    dopwggToggleMessage('hide', DOPWGG_ADD_IMAGE_SUCCESS);
                });            
            }
        });

        dopwggResize();
    }
}

function dopwggUploadImage(){
    dopwggToggleMessage('show', DOPWGG_ADD_IMAGE_SUBMITED);
}

function dopwggUploadImageSuccess(response){   
    if (response != '-1'){
        setTimeout(function(){
            dopwggResize();
        }, 1000);
        
        $jDOPWGG.post(ajaxurl, {action:'dopwgg_add_image', gallery_id:$jDOPWGG('#gallery_id').val(), name:response}, function(data){
            if ($jDOPWGG('ul', '.column2', '.DOPWGG-admin').html() == '<li class="no-data">'+DOPWGG_NO_IMAGES+'</li>'){
                $jDOPWGG('ul', '.column2', '.DOPWGG-admin').html('<li class="item-image" id="DOPWGG-image-ID-'+data+'"><img src="'+DOPWGG_plugin_url+'uploads/thumbs/'+response+'" alt="" /></li>');
            }
            else{
                $jDOPWGG('ul', '.column2', '.DOPWGG-admin').append('<li class="item-image" id="DOPWGG-image-ID-'+data+'"><img src="'+DOPWGG_plugin_url+'uploads/thumbs/'+response+'" alt="" /></li>');
            }
            dopwggResize();
            
            $jDOPWGG('#DOPWGG-image-ID-'+data).click(function(){
                var id = $jDOPWGG(this).attr('id').split('-')[3];
            
                if (currImage != id && clearClick){
                    $jDOPWGG('li', '.column2', '.DOPWGG-admin').removeClass('item-image-selected');
                    $jDOPWGG(this).addClass('item-image-selected');
                    dopwggShowImage(id);
                }
            });
            dopwggToggleMessage('hide', DOPWGG_ADD_IMAGE_SUCCESS);
            $jDOPWGG('#DOPWGG-image-ID-'+data).DOPImageLoader({'LoaderURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/no-image.png'});
        });
    }
    else{
        dopwggToggleMessage('hide', DOPWGG_ADD_IMAGE_SUCCESS);
    }
}

function dopwggShowImage(id){// Show Image Details.
    if (clearClick){
        dopwggRemoveColumns(3);
        currImage = id;
        dopwggToggleMessage('show', DOPWGG_LOAD);
        
        $jDOPWGG.post(ajaxurl, {action:'dopwgg_show_image', image_id:id}, function(data){            
            var json = $jDOPWGG.parseJSON(data),
            HeaderHTML = new Array(), HTML = new Array();
            
            HeaderHTML.push('<input type="button" name="DOPWGG_image_submit" class="submit-style" onclick="dopwggEditImage('+json['id']+')" title="'+DOPWGG_EDIT_IMAGE_SUBMIT+'" value="'+DOPWGG_SUBMIT+'" />');
            HeaderHTML.push('<input type="button" name="DOPWGG_image_delete" class="submit-style" onclick="dopwggDeleteImage('+json['id']+')" title="'+DOPWGG_DELETE_IMAGE_SUBMIT+'" value="'+DOPWGG_DELETE+'" />');
            HeaderHTML.push('<a href="javascript:void()" class="header-help" title="'+DOPWGG_IMAGE_EDIT_HELP+'"></a>');

            HTML.push('<input type="hidden" name="crop_x" id="crop_x" value="0" />');
            HTML.push('<input type="hidden" name="crop_y" id="crop_y" value="0" />');
            HTML.push('<input type="hidden" name="crop_width" id="crop_width" value="0" />');
            HTML.push('<input type="hidden" name="crop_height" id="crop_height" value="0" />');
            HTML.push('<input type="hidden" name="image_width" id="image_width" value="0" />');
            HTML.push('<input type="hidden" name="image_height" id="image_height" value="0" />');
            HTML.push('<input type="hidden" name="image_name" id="image_name" value="'+json['name']+'" />');
            HTML.push('<input type="hidden" name="thumb_width" id="thumb_width" value="'+json['thumbnail_width']+'" />');
            HTML.push('<input type="hidden" name="thumb_height" id="thumb_height" value="'+json['thumbnail_height']+'" />');
            HTML.push('<div class="column-image">');
            HTML.push('    <img src="'+DOPWGG_plugin_url+'uploads/'+json['name']+'" alt="" />');
            HTML.push('</div>');
            HTML.push('<div class="column-thumbnail-left">');
            HTML.push('    <label class="label">'+DOPWGG_EDIT_IMAGE_CROP_THUMBNAIL+'</label>');
            HTML.push('    <div class="column-thumbnail" style="width:'+json['thumbnail_width']+'px; height:'+json['thumbnail_height']+'px;">');
            HTML.push('        <img src="'+DOPWGG_plugin_url+'uploads/'+json['name']+'" style="width:'+json['thumbnail_width']+'px; height:'+json['thumbnail_height']+'px;" alt="" />');
            HTML.push('    </div>');
            HTML.push('</div>');
            HTML.push('<div class="column-thumbnail-right">');
            HTML.push('    <label class="label">'+DOPWGG_EDIT_IMAGE_CURRENT_THUMBNAIL+'</label>');
            HTML.push('    <div class="column-thumbnail" id="DOPWGG-curr-thumb" style="float: right; width:'+json['thumbnail_width']+'px; height:'+json['thumbnail_height']+'px;">');
            HTML.push('        <img src="'+DOPWGG_plugin_url+'uploads/thumbs/'+json['name']+'?cacheBuster='+dopwggRandomString(64)+'" style="width:'+json['thumbnail_width']+'px; height:'+json['thumbnail_height']+'px;" alt="" />');
            HTML.push('    </div>');
            HTML.push('</div>');
            HTML.push('<br class="DOPWGG-clear" />');
            HTML.push('<label class="label" for="image_title">'+DOPWGG_EDIT_IMAGE_TITLE+'</label>');
            HTML.push('<input type="text" class="column-input" name="image_title" id="image_title" value="'+json['title']+'" />');
            HTML.push('<label class="label" for="image_caption">'+DOPWGG_EDIT_IMAGE_CAPTION+'</label>');
            HTML.push('<textarea class="column-input" name="image_caption" id="image_caption" cols="" rows="6">'+json['caption']+'</textarea>');
            HTML.push('<label class="label" for="image_media">'+DOPWGG_EDIT_IMAGE_MEDIA+'</label>');
            HTML.push('<textarea class="column-input" name="image_media" id="image_media" cols="" rows="6">'+json['media']+'</textarea>');
            HTML.push('<label class="label" for="image_link">'+DOPWGG_EDIT_IMAGE_LINK+'</label>');
            HTML.push('<input type="text" class="column-input" name="image_link" id="image_link" value="'+json['link']+'" />');
            HTML.push('<label class="label" for="image_link_target">'+DOPWGG_EDIT_IMAGE_LINK_TARGET+'</label>');
            HTML.push('<select class="column-select" name="image_link_target" id="image_link_target">');
            if (json['target'] == '_self'){
                HTML.push('<option value="_blank">_blank</option>');
                HTML.push('<option value="_self" selected="selected">_self</option>');
                HTML.push('<option value="_parent">_parent</option>');
                HTML.push('<option value="_top">_top</option>');
            }
            else if (json['target'] == '_parent'){
                HTML.push('<option value="_blank">_blank</option>');
                HTML.push('<option value="_self">_self</option>');
                HTML.push('<option value="_parent" selected="selected">_parent</option>');
                HTML.push('<option value="_top">_top</option>');
            }
            else if (json['target'] == '_top'){
                HTML.push('<option value="_blank">_blank</option>');
                HTML.push('<option value="_self">_self</option>');
                HTML.push('<option value="_parent">_parent</option>');
                HTML.push('<option value="_top" selected="selected">_top</option>');
            }
            else{
                HTML.push('<option value="_blank" selected="selected">_blank</option>');
                HTML.push('<option value="_self">_self</option>');
                HTML.push('<option value="_parent">_parent</option>');
                HTML.push('<option value="_top">_top</option>');
            }
            HTML.push('</select>');
            HTML.push('<label class="label" for="image_enabled">'+DOPWGG_EDIT_IMAGE_ENABLED+'</label>');
            HTML.push('<select class="column-select" name="image_enabled" id="image_enabled">');
            if (json['enabled'] == 'true'){
                HTML.push('<option value="true" selected="selected">true</option>');
                HTML.push('<option value="false">false</option>');
            }
            else{
                HTML.push('<option value="true">true</option>');
                HTML.push('<option value="false" selected="selected">false</option>');
            }
            HTML.push('</select>');


            $jDOPWGG('.column-header', '.column3', '.DOPWGG-admin').html(HeaderHTML.join(''));
            $jDOPWGG('.column-content', '.column3', '.DOPWGG-admin').html(HTML.join(''));
            dopwggResize();
            $jDOPWGG('.column-image', '.DOPWGG-admin').DOPImageLoader({'LoaderURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/loader.gif', 'NoImageURL': DOPWGG_plugin_url+'libraries/gui/images/image-loader/no-image.png', 'SuccessCallback': 'dopwggInitJcrop()'});
            
            dopwggToggleMessage('hide', DOPWGG_IMAGE_LOADED);
        });
    }
}

function dopwggInitJcrop(){// Init Jcrop. (For croping thumbnails)
    imageDisplay = true;
    imageWidth = $jDOPWGG('img', '.column-image', '.DOPWGG-admin').width();
    imageHeight = $jDOPWGG('img', '.column-image', '.DOPWGG-admin').height();
    dopwggResize();     
    $jDOPWGG('img', '.column-image', '.DOPWGG-admin').Jcrop({onChange: doppwggShowCropPreview, onSelect: doppwggShowCropPreview, aspectRatio: $jDOPWGG('.column-thumbnail', '.DOPWGG-admin').width()/$jDOPWGG('.column-thumbnail', '.DOPWGG-admin').height(), minSize: [$jDOPWGG('.column-thumbnail', '.DOPWGG-admin').width(), $jDOPWGG('.column-thumbnail', '.DOPWGG-admin').height()]});
    
    setTimeout(function(){
        dopwggResize();        
    }, 1000);
}

function doppwggShowCropPreview(coords){// Select thumbnail with Jcrop.
    if (parseInt(coords.w) > 0){
        $jDOPWGG('#crop_x').val(coords.x);
        $jDOPWGG('#crop_y').val(coords.y);
        $jDOPWGG('#crop_width').val(coords.w);
        $jDOPWGG('#crop_height').val(coords.h);
        $jDOPWGG('#image_width').val($jDOPWGG('img', '.column-image', '.DOPWGG-admin').width());
        $jDOPWGG('#image_height').val($jDOPWGG('img', '.column-image', '.DOPWGG-admin').height());

        var rx = $jDOPWGG('.column-thumbnail', '.DOPWGG-admin').width()/coords.w;
        var ry = $jDOPWGG('.column-thumbnail', '.DOPWGG-admin').height()/coords.h;

        $jDOPWGG('img', '.column-thumbnail-left', '.DOPWGG-admin').css({
            width: Math.round(rx*$jDOPWGG('img', '.column-image', '.DOPWGG-admin').width()) + 'px',
            height: Math.round(ry*$jDOPWGG('img', '.column-image', '.DOPWGG-admin').height()) + 'px',
            marginLeft: '-'+Math.round(rx * coords.x)+'px',
            marginTop: '-'+Math.round(ry * coords.y)+'px'
        });
    }
}

function dopwggEditImage(id){// Edit Image Details.
    if (clearClick){
        dopwggToggleMessage('show', DOPWGG_SAVE);
        
        $jDOPWGG.post(ajaxurl, {action:'dopwgg_edit_image',
                                image_id:id,
                                crop_x: $jDOPWGG('#crop_x').val(),
                                crop_y: $jDOPWGG('#crop_y').val(),
                                crop_width: $jDOPWGG('#crop_width').val(),
                                crop_height: $jDOPWGG('#crop_height').val(),
                                image_width: $jDOPWGG('#image_width').val(),
                                image_height: $jDOPWGG('#image_height').val(),
                                image_name: $jDOPWGG('#image_name').val(),
                                thumb_width: $jDOPWGG('#thumb_width').val(),
                                thumb_height: $jDOPWGG('#thumb_height').val(),
                                image_title: $jDOPWGG('#image_title').val(),
                                image_caption: $jDOPWGG('#image_caption').val(),
                                image_media: $jDOPWGG('#image_media').val(),
                                image_link: $jDOPWGG('#image_link').val(),
                                image_link_target: $jDOPWGG('#image_link_target').val(),
                                image_enabled: $jDOPWGG('#image_enabled').val()}, function(data){
            dopwggToggleMessage('hide', DOPWGG_EDIT_IMAGE_SUCCESS);
            
            if ($jDOPWGG('#image_enabled').val() == 'true'){
                $jDOPWGG('#DOPWGG-image-ID-'+id).removeClass('item-image-disabled');
            }
            else{
                $jDOPWGG('#DOPWGG-image-ID-'+id).addClass('item-image-disabled');
            }
            
            if (data != ''){
                $jDOPWGG('#DOPWGG-curr-thumb').html('<img src="'+data+'?cacheBuster='+dopwggRandomString(64)+'" style="width:'+$jDOPWGG('#thumb_width').val()+'px; height:'+$jDOPWGG('#thumb_height').val()+'px;" alt="" />');
            }
        });
    }
}

function dopwggDeleteImage(id){// Delete Image.
    if (clearClick){
        if (confirm(DOPWGG_DELETE_IMAGE_CONFIRMATION)){
            dopwggToggleMessage('show', DOPWGG_DELETE_IMAGE_SUBMITED);
            
            $jDOPWGG.post(ajaxurl, {action:'dopwgg_delete_image', image_id: id}, function(data){
                dopwggRemoveColumns(3);
                
                $jDOPWGG('#DOPWGG-image-ID-'+id).stop(true, true).animate({'opacity':0}, 600, function(){
                    $jDOPWGG(this).remove();
                    dopwggToggleMessage('hide', DOPWGG_DELETE_GALERRY_SUCCESS);
                    
                    if (data == '0'){
                        $jDOPWGG('.column-content', '.column2', '.DOPWGG-admin').html('<ul><li class="no-data">'+DOPWGG_NO_IMAGES+'</li></ul>');
                    }
                });
            });
        }
    }
}

// Settings

function dopwggSettingsForm(data, column){// Settings Form.
    var HTML = new Array();
    
    HTML.push('<form method="post" class="settings" action="" onsubmit="return false;">');

// General Styles & Settings
    HTML.push('    <h3 class="settings">'+DOPWGG_GENERAL_STYLES_SETTINGS+'</h3>');
    
    if ($jDOPWGG('#gallery_id').val() != '0'){
        HTML.push(dopwggSettingsFormInput('name', data['name'], DOPWGG_GALLERY_NAME, '', '', '', 'help', DOPWGG_GALLERY_NAME_INFO));
    }
    else{
        HTML.push('<input type="hidden" name="name" id="name" value="'+data['name']+'" />');
        HTML.push(dopwggSettingsFormSelect('data_parse_method', data['data_parse_method'], DOPWGG_DATA_PARSE_METHOD, '', '', '', 'help', DOPWGG_DATA_PARSE_METHOD_INFO, 'ajax;;html'));
    }
    
    HTML.push(dopwggSettingsFormInput('width', data['width'], DOPWGG_WIDTH, '', 'px', 'small', 'help-small', DOPWGG_WIDTH_INFO));
    HTML.push(dopwggSettingsFormInput('height', data['height'], DOPWGG_HEIGHT, '', 'px', 'small', 'help-small', DOPWGG_HEIGHT_INFO));
    HTML.push(dopwggSettingsFormInput('bg_color', data['bg_color'], DOPWGG_BG_COLOR, '#', '', 'small', 'help-small', DOPWGG_BG_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('bg_alpha', data['bg_alpha'], DOPWGG_BG_ALPHA, '', '', 'small', 'help-small', DOPWGG_BG_ALPHA_INFO));
    HTML.push(dopwggSettingsFormInput('no_lines', data['no_lines'], DOPWGG_NO_LINES, '', '', 'small', 'help-small', DOPWGG_NO_LINES_INFO));
    HTML.push(dopwggSettingsFormInput('no_columns', data['no_columns'], DOPWGG_NO_COLUMNS, '', '', 'small', 'help-small', DOPWGG_NO_COLUMNS_INFO));
    HTML.push(dopwggSettingsFormSelect('images_order', data['images_order'], DOPWGG_IMAGES_ORDER, '', '', '', 'help', DOPWGG_IMAGES_ORDER_INFO, 'normal;;random'));
    HTML.push(dopwggSettingsFormSelect('responsive_enabled', data['responsive_enabled'], DOPWGG_RESPONSIVE_ENABLED, '', '', '', 'help', DOPWGG_RESPONSIVE_ENABLED_INFO, 'true;;false'));
        
// Thumbnails Styles & Settings
    HTML.push('    <a href="javascript:dopwggMoveTop()" class="go-top">'+DOPWGG_GO_TOP+'</a><h3 class="settings">'+DOPWGG_THUMBNAILS_STYLES_SETTINGS+'</h3>');
    HTML.push(dopwggSettingsFormInput('thumbnails_spacing', data['thumbnails_spacing'], DOPWGG_THUMBNAILS_SPACING, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAILS_SPACING_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnails_padding_top', data['thumbnails_padding_top'], DOPWGG_THUMBNAILS_PADDING_TOP, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAILS_PADDING_TOP_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnails_padding_right', data['thumbnails_padding_right'], DOPWGG_THUMBNAILS_PADDING_RIGHT, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAILS_PADDING_RIGHT_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnails_padding_bottom', data['thumbnails_padding_bottom'], DOPWGG_THUMBNAILS_PADDING_BOTTOM, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAILS_PADDING_BOTTOM_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnails_padding_left', data['thumbnails_padding_left'], DOPWGG_THUMBNAILS_PADDING_LEFT, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAILS_PADDING_LEFT_INFO));
    HTML.push(dopwggSettingsFormSelect('thumbnails_navigation', data['thumbnails_navigation'], DOPWGG_THUMBNAILS_NAVIGATION, '', '', '', 'help', DOPWGG_THUMBNAILS_NAVIGATION_INFO, 'mouse;;scroll'));
    HTML.push(dopwggSettingsFormInput('thumbnails_scroll_scrub_color', data['thumbnails_scroll_scrub_color'], DOPWGG_THUMBNAILS_SCROLL_SCRUB_COLOR, '#', '', 'small', 'help-small', DOPWGG_THUMBNAILS_SCROLL_SCRUB_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnails_scroll_bar_color', data['thumbnails_scroll_bar_color'], DOPWGG_THUMBNAILS_SCROLL_BAR_COLOR, '#', '', 'small', 'help-small', DOPWGG_THUMBNAILS_SCROLL_BAR_COLOR_INFO));
    HTML.push(dopwggSettingsFormSelect('thumbnails_info', data['thumbnails_info'], DOPWGG_THUMBNAILS_INFO, '', '', '', 'help', DOPWGG_THUMBNAILS_INFO_INFO, 'none;;tooltip;;label'));
    
// Styles & Settings for a Thumbnail
    HTML.push('    <a href="javascript:dopwggMoveTop()" class="go-top">'+DOPWGG_GO_TOP+'</a><h3 class="settings">'+DOPWGG_THUMBNAIL_STYLES_SETTINGS+'</h3>');
    HTML.push(dopwggSettingsFormImage('thumbnail_loader', data['thumbnail_loader'], DOPWGG_THUMBNAIL_LOADER, 'help-image', DOPWGG_THUMBNAIL_LOADER_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_width', data['thumbnail_width'], DOPWGG_THUMBNAIL_WIDTH, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAIL_WIDTH_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_height', data['thumbnail_height'], DOPWGG_THUMBNAIL_HEIGHT, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAIL_HEIGHT_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_width_mobile', data['thumbnail_width_mobile'], DOPWGG_THUMBNAIL_WIDTH_MOBILE, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAIL_WIDTH_MOBILE_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_height_mobile', data['thumbnail_height_mobile'], DOPWGG_THUMBNAIL_HEIGHT_MOBILE, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAIL_HEIGHT_MOBILE_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_alpha', data['thumbnail_alpha'], DOPWGG_THUMBNAIL_ALPHA, '', '', 'small', 'help-small', DOPWGG_THUMBNAIL_ALPHA_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_alpha_hover', data['thumbnail_alpha_hover'], DOPWGG_THUMBNAIL_ALPHA_HOVER, '', '', 'small', 'help-small', DOPWGG_THUMBNAIL_ALPHA_HOVER_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_bg_color', data['thumbnail_bg_color'], DOPWGG_THUMBNAIL_BG_COLOR, '#', '', 'small', 'help-small', DOPWGG_THUMBNAIL_BG_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_bg_color_hover', data['thumbnail_bg_color_hover'], DOPWGG_THUMBNAIL_BG_COLOR_HOVER, '#', '', 'small', 'help-small', DOPWGG_THUMBNAIL_BG_COLOR_HOVER_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_border_size', data['thumbnail_border_size'], DOPWGG_THUMBNAIL_BORDER_SIZE, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAIL_BORDER_SIZE_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_border_color', data['thumbnail_border_color'], DOPWGG_THUMBNAIL_BORDER_COLOR, '#', '', 'small', 'help-small', DOPWGG_THUMBNAIL_BORDER_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_border_color_hover', data['thumbnail_border_color_hover'], DOPWGG_THUMBNAIL_BORDER_COLOR_HOVER, '#', '', 'small', 'help-small', DOPWGG_THUMBNAIL_BORDER_COLOR_HOVER_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_padding_top', data['thumbnail_padding_top'], DOPWGG_THUMBNAIL_PADDING_TOP, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAIL_PADDING_TOP_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_padding_right', data['thumbnail_padding_right'], DOPWGG_THUMBNAIL_PADDING_RIGHT, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAIL_PADDING_RIGHT_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_padding_bottom', data['thumbnail_padding_bottom'], DOPWGG_THUMBNAIL_PADDING_BOTTOM, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAIL_PADDING_BOTTOM_INFO));
    HTML.push(dopwggSettingsFormInput('thumbnail_padding_left', data['thumbnail_padding_left'], DOPWGG_THUMBNAIL_PADDING_LEFT, '', 'px', 'small', 'help-small', DOPWGG_THUMBNAIL_PADDING_LEFT_INFO));

// Lightbox Styles & Settings
    HTML.push('    <a href="javascript:dopwggMoveTop()" class="go-top">'+DOPWGG_GO_TOP+'</a><h3 class="settings">'+DOPWGG_LIGHTBOX_STYLES_SETTINGS+'</h3>');
    HTML.push(dopwggSettingsFormSelect('lightbox_position', data['lightbox_position'], DOPWGG_LIGHTBOX_POSITION, '', '', '', 'help', DOPWGG_LIGHTBOX_POSITION_INFO, 'document;;gallery'));
    HTML.push(dopwggSettingsFormInput('lightbox_window_color', data['lightbox_window_color'], DOPWGG_LIGHTBOX_WINDOW_COLOR, '#', '', 'small', 'help-small', DOPWGG_LIGHTBOX_WINDOW_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_window_alpha', data['lightbox_window_alpha'], DOPWGG_LIGHTBOX_WINDOW_ALPHA, '', '', 'small', 'help-small', DOPWGG_LIGHTBOX_WINDOW_ALPHA_INFO));
    HTML.push(dopwggSettingsFormImage('lightbox_loader', data['lightbox_loader'], DOPWGG_LIGHTBOX_LOADER, 'help-image', DOPWGG_LIGHTBOX_LOADER_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_bg_color', data['lightbox_bg_color'], DOPWGG_LIGHTBOX_BACKGROUND_COLOR, '#', '', 'small', 'help-small', DOPWGG_LIGHTBOX_BACKGROUND_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_bg_alpha', data['lightbox_bg_alpha'], DOPWGG_LIGHTBOX_BACKGROUND_ALPHA, '', '', 'small', 'help-small', DOPWGG_LIGHTBOX_BACKGROUND_ALPHA_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_margin_top', data['lightbox_margin_top'], DOPWGG_LIGHTBOX_MARGIN_TOP, '', 'px', 'small', 'help-small', DOPWGG_LIGHTBOX_MARGIN_TOP_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_margin_right', data['lightbox_margin_right'], DOPWGG_LIGHTBOX_MARGIN_RIGHT, '', 'px', 'small', 'help-small', DOPWGG_LIGHTBOX_MARGIN_RIGHT_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_margin_bottom', data['lightbox_margin_bottom'], DOPWGG_LIGHTBOX_MARGIN_BOTTOM, '', 'px', 'small', 'help-small', DOPWGG_LIGHTBOX_MARGIN_BOTTOM_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_margin_left', data['lightbox_margin_left'], DOPWGG_LIGHTBOX_MARGIN_LEFT, '', 'px', 'small', 'help-small', DOPWGG_LIGHTBOX_MARGIN_LEFT_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_padding_top', data['lightbox_padding_top'], DOPWGG_LIGHTBOX_PADDING_TOP, '', 'px', 'small', 'help-small', DOPWGG_LIGHTBOX_PADDING_TOP_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_padding_right', data['lightbox_padding_right'], DOPWGG_LIGHTBOX_PADDING_RIGHT, '', 'px', 'small', 'help-small', DOPWGG_LIGHTBOX_PADDING_RIGHT_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_padding_bottom', data['lightbox_padding_bottom'], DOPWGG_LIGHTBOX_PADDING_BOTTOM, '', 'px', 'small', 'help-small', DOPWGG_LIGHTBOX_PADDING_BOTTOM_INFO));
    HTML.push(dopwggSettingsFormInput('lightbox_padding_left', data['lightbox_padding_left'], DOPWGG_LIGHTBOX_PADDING_LEFT, '', 'px', 'small', 'help-small', DOPWGG_LIGHTBOX_PADDING_LEFT_INFO));
        
// Lightbox Navigation Icons Styles & Settings
    HTML.push('    <a href="javascript:dopwggMoveTop()" class="go-top">'+DOPWGG_GO_TOP+'</a><h3 class="settings">'+DOPWGG_LIGHTBOX_NAVIGATION_STYLES_SETTINGS+'</h3>');
    HTML.push(dopwggSettingsFormImage('lightbox_navigation_prev', data['lightbox_navigation_prev'], DOPWGG_LIGHTBOX_NAVIGATION_PREV, 'help-image', DOPWGG_LIGHTBOX_NAVIGATION_PREV_INFO));
    HTML.push(dopwggSettingsFormImage('lightbox_navigation_prev_hover', data['lightbox_navigation_prev_hover'], DOPWGG_LIGHTBOX_NAVIGATION_PREV_HOVER, 'help-image', DOPWGG_LIGHTBOX_NAVIGATION_PREV_HOVER_INFO));
    HTML.push(dopwggSettingsFormImage('lightbox_navigation_next', data['lightbox_navigation_next'], DOPWGG_LIGHTBOX_NAVIGATION_NEXT, 'help-image', DOPWGG_LIGHTBOX_NAVIGATION_NEXT_INFO));
    HTML.push(dopwggSettingsFormImage('lightbox_navigation_next_hover', data['lightbox_navigation_next_hover'], DOPWGG_LIGHTBOX_NAVIGATION_NEXT_HOVER, 'help-image', DOPWGG_LIGHTBOX_NAVIGATION_NEXT_HOVER_INFO));
    HTML.push(dopwggSettingsFormImage('lightbox_navigation_close', data['lightbox_navigation_close'], DOPWGG_LIGHTBOX_NAVIGATION_CLOSE, 'help-image', DOPWGG_LIGHTBOX_NAVIGATION_CLOSE_INFO));
    HTML.push(dopwggSettingsFormImage('lightbox_navigation_close_hover', data['lightbox_navigation_close_hover'], DOPWGG_LIGHTBOX_NAVIGATION_CLOSE_HOVER, 'help-image', DOPWGG_LIGHTBOX_NAVIGATION_CLOSE_HOVER_INFO));    
    
// Caption Styles & Settings
    HTML.push('    <a href="javascript:dopwggMoveTop()" class="go-top">'+DOPWGG_GO_TOP+'</a><h3 class="settings">'+DOPWGG_CAPTION_STYLES_SETTINGS+'</h3>');
    HTML.push(dopwggSettingsFormInput('caption_height', data['caption_height'], DOPWGG_CAPTION_HEIGHT, '', 'px', 'small', 'help-small', DOPWGG_CAPTION_HEIGHT_INFO));
    HTML.push(dopwggSettingsFormInput('caption_title_color', data['caption_title_color'], DOPWGG_CAPTION_TITLE_COLOR, '#', '', 'small', 'help-small', DOPWGG_CAPTION_TITLE_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('caption_text_color', data['caption_text_color'], DOPWGG_CAPTION_TEXT_COLOR, '#', '', 'small', 'help-small', DOPWGG_CAPTION_TEXT_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('caption_scroll_scrub_color', data['caption_scroll_scrub_color'], DOPWGG_CAPTION_SCROLL_SCRUB_COLOR, '#', '', 'small', 'help-small', DOPWGG_CAPTION_SCROLL_SCRUB_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('caption_scroll_bg_color', data['caption_scroll_bg_color'], DOPWGG_CAPTION_SCROLL_BG_COLOR, '#', '', 'small', 'help-small', DOPWGG_CAPTION_SCROLL_BG_COLOR_INFO));
    
// Social Share Styles & Settings 
    HTML.push('    <a href="javascript:dopwggMoveTop()" class="go-top">'+DOPWGG_GO_TOP+'</a><h3 class="settings">'+DOPWGG_SOCIAL_SHARE_STYLES_SETTINGS+'</h3>');
    HTML.push(dopwggSettingsFormSelect('social_share_enabled', data['social_share_enabled'], DOPWGG_SOCIAL_SHARE_ENABLED, '', '', '', 'help', DOPWGG_SOCIAL_SHARE_ENABLED_INFO, 'true;;false'));
    HTML.push(dopwggSettingsFormImage('social_share_lightbox', data['social_share_lightbox'], DOPWGG_SOCIAL_SHARE_LIGHTBOX, 'help-image', DOPWGG_SOCIAL_SHARE_LIGHTBOX_INFO)); 
    
// Tooltip Styles & Settings
    HTML.push('    <a href="javascript:dopwggMoveTop()" class="go-top">'+DOPWGG_GO_TOP+'</a><h3 class="settings">'+DOPWGG_TOOLTIP_STYLES_SETTINGS+'</h3>');
    HTML.push(dopwggSettingsFormInput('tooltip_bg_color', data['tooltip_bg_color'], DOPWGG_TOOLTIP_BG_COLOR, '#', '', 'small', 'help-small', DOPWGG_TOOLTIP_BG_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('tooltip_stroke_color', data['tooltip_stroke_color'], DOPWGG_TOOLTIP_STROKE_COLOR, '#', '', 'small', 'help-small', DOPWGG_TOOLTIP_STROKE_COLOR_INFO));
    HTML.push(dopwggSettingsFormInput('tooltip_text_color', data['tooltip_text_color'], DOPWGG_TOOLTIP_TEXT_COLOR, '#', '', 'small', 'help-small', DOPWGG_TOOLTIP_TEXT_COLOR_INFO));
    
// Label Styles & Settings
    HTML.push('    <a href="javascript:dopwggMoveTop()" class="go-top">'+DOPWGG_GO_TOP+'</a><h3 class="settings">'+DOPWGG_LABEL_STYLES_SETTINGS+'</h3>');
    HTML.push(dopwggSettingsFormSelect('label_position', data['label_position'], DOPWGG_LABEL_POSITION, '', '', '', 'help', DOPWGG_LABEL_POSITION_INFO, 'bottom;;top'));
    HTML.push(dopwggSettingsFormInput('label_text_color', data['label_text_color'], DOPWGG_LABEL_TEXT_COLOR, '#', '', 'small', 'help-small', DOPWGG_LABEL_TEXT_COLOR_INFO));    
    HTML.push(dopwggSettingsFormInput('label_text_color_hover', data['label_text_color_hover'], DOPWGG_LABEL_TEXT_COLOR_HOVER, '#', '', 'small', 'help-small', DOPWGG_LABEL_TEXT_COLOR_HOVER_INFO));    

    HTML.push('</form>');

    $jDOPWGG('.column-content', '.column'+column, '.DOPWGG-admin').html(HTML.join(''));
    
    setTimeout(function(){
        dopwggResize();
        setTimeout(function(){
           dopwggResize();
        }, 10000);
    }, 5000);
    
    $jDOPWGG('#bg_color,\n\
              #thumbnails_scroll_scrub_color,\n\
              #thumbnails_scroll_bar_color,\n\
              #thumbnail_bg_color,\n\
              #thumbnail_bg_color_hover,\n\
              #thumbnail_border_color,\n\
              #thumbnail_border_color_hover,\n\
              #lightbox_window_color,\n\
              #lightbox_bg_color,\n\
              #caption_title_color,\n\
              #caption_text_color,\n\
              #caption_scroll_scrub_color,\n\
              #caption_scroll_bg_color,\n\
              #tooltip_bg_color,\n\
              #tooltip_stroke_color,\n\
              #tooltip_text_color,\n\
              #label_text_color,\n\
              #label_text_color_hover').ColorPicker({
        onSubmit:function(hsb, hex, rgb, el){
            $jDOPWGG(el).val(hex);
            $jDOPWGG(el).ColorPickerHide();
            $jDOPWGG(el).removeAttr('style');
            $jDOPWGG(el).css({'background-color': '#'+hex,
                              'color': dopwggIdealTextColor(hex) == 'white' ? '#ffffff':'#0000000'});
        },
        onBeforeShow:function(){
            $jDOPWGG(this).ColorPickerSetColor(this.value);
        },
        onShow:function(colpkr){
            $jDOPWGG(colpkr).fadeIn(500);
            return false;
        },
        onHide:function(colpkr){
            $jDOPWGG(colpkr).fadeOut(500);
            return false;
        }
    })
    .bind('keyup', function(){
        $jDOPWGG(this).ColorPickerSetColor(this.value);
        $jDOPWGG(this).removeAttr('style');
        
        if (this.value.length != 6){
            $jDOPWGG(this).css({'background-color': '#ffffff',
                                'color': '#0000000'});
        }
        else{
            $jDOPWGG(this).css({'background-color': '#'+this.value,
                                'color': dopwggIdealTextColor(this.value) == 'white' ? '#ffffff':'#0000000'});
        }
    });
    
    $jDOPWGG('#bg_color').css({'background-color': '#'+data['bg_color'],
                               'color': dopwggIdealTextColor(data['bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#thumbnails_scroll_scrub_color').css({'background-color': '#'+data['thumbnails_scroll_scrub_color'],
                                                    'color': dopwggIdealTextColor(data['thumbnails_scroll_scrub_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#thumbnails_scroll_bar_color').css({'background-color': '#'+data['thumbnails_scroll_bar_color'],
                                                  'color': dopwggIdealTextColor(data['thumbnails_scroll_bar_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#thumbnail_bg_color').css({'background-color': '#'+data['thumbnail_bg_color'],
                                         'color': dopwggIdealTextColor(data['thumbnail_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#thumbnail_bg_color_hover').css({'background-color': '#'+data['thumbnail_bg_color_hover'],
                                               'color': dopwggIdealTextColor(data['thumbnail_bg_color_hover']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#thumbnail_border_color').css({'background-color': '#'+data['thumbnail_border_color'],
                                             'color': dopwggIdealTextColor(data['thumbnail_border_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#thumbnail_border_color_hover').css({'background-color': '#'+data['thumbnail_border_color_hover'],
                                                   'color': dopwggIdealTextColor(data['thumbnail_border_color_hover']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#lightbox_window_color').css({'background-color': '#'+data['lightbox_window_color'],
                                            'color': dopwggIdealTextColor(data['lightbox_window_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#lightbox_bg_color').css({'background-color': '#'+data['lightbox_bg_color'],
                                        'color': dopwggIdealTextColor(data['lightbox_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#caption_title_color').css({'background-color': '#'+data['caption_title_color'],
                                          'color': dopwggIdealTextColor(data['caption_title_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#caption_text_color').css({'background-color': '#'+data['caption_text_color'],
                                         'color': dopwggIdealTextColor(data['caption_text_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#caption_scroll_scrub_color').css({'background-color': '#'+data['caption_scroll_scrub_color'],
                                                 'color': dopwggIdealTextColor(data['caption_scroll_scrub_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#caption_scroll_bg_color').css({'background-color': '#'+data['caption_scroll_bg_color'],
                                              'color': dopwggIdealTextColor(data['caption_scroll_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#tooltip_bg_color').css({'background-color': '#'+data['tooltip_bg_color'],
                                       'color': dopwggIdealTextColor(data['tooltip_bg_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#tooltip_stroke_color').css({'background-color': '#'+data['tooltip_stroke_color'],
                                           'color': dopwggIdealTextColor(data['tooltip_stroke_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#tooltip_text_color').css({'background-color': '#'+data['tooltip_text_color'],
                                         'color': dopwggIdealTextColor(data['tooltip_text_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#label_text_color').css({'background-color': '#'+data['label_text_color'],
                                       'color': dopwggIdealTextColor(data['label_text_color']) == 'white' ? '#ffffff':'#0000000'});
    $jDOPWGG('#label_text_color_hover').css({'background-color': '#'+data['label_text_color_hover'],
                                             'color': dopwggIdealTextColor(data['label_text_color_hover']) == 'white' ? '#ffffff':'#0000000'});
    
    dopwggSettingsImageUpload('thumbnail_loader', 'uploads/settings/thumb-loader/', DOPWGG_ADD_THUMBNAIL_LOADER_SUBMITED, DOPWGG_ADD_THUMBNAIL_LOADER_SUCCESS);        
    dopwggSettingsImageUpload('lightbox_loader', 'uploads/settings/lightbox-loader/', DOPWGG_ADD_LIGHTBOX_LOADER_SUBMITED, DOPWGG_ADD_LIGHTBOX_LOADER_SUCCESS);
    dopwggSettingsImageUpload('lightbox_navigation_prev', 'uploads/settings/lightbox-navigation-prev/', DOPWGG_ADD_LIGHTBOX_NAVIGATION_PREV_SUBMITED, DOPWGG_ADD_LIGHTBOX_NAVIGATION_PREV_SUCCESS);
    dopwggSettingsImageUpload('lightbox_navigation_prev_hover', 'uploads/settings/lightbox-navigation-prev-hover/', DOPWGG_ADD_LIGHTBOX_NAVIGATION_PREV_HOVER_SUBMITED, DOPWGG_ADD_LIGHTBOX_NAVIGATION_PREV_HOVER_SUCCESS);
    dopwggSettingsImageUpload('lightbox_navigation_next', 'uploads/settings/lightbox-navigation-next/', DOPWGG_ADD_LIGHTBOX_NAVIGATION_NEXT_SUBMITED, DOPWGG_ADD_LIGHTBOX_NAVIGATION_NEXT_SUCCESS);
    dopwggSettingsImageUpload('lightbox_navigation_next_hover', 'uploads/settings/lightbox-navigation-next-hover/', DOPWGG_ADD_LIGHTBOX_NAVIGATION_NEXT_HOVER_SUBMITED, DOPWGG_ADD_LIGHTBOX_NAVIGATION_NEXT_HOVER_SUCCESS);
    dopwggSettingsImageUpload('lightbox_navigation_close', 'uploads/settings/lightbox-navigation-close/', DOPWGG_ADD_LIGHTBOX_NAVIGATION_CLOSE_SUBMITED, DOPWGG_ADD_LIGHTBOX_NAVIGATION_CLOSE_SUCCESS);
    dopwggSettingsImageUpload('lightbox_navigation_close_hover', 'uploads/settings/lightbox-navigation-close-hover/', DOPWGG_ADD_LIGHTBOX_NAVIGATION_CLOSE_HOVER_SUBMITED, DOPWGG_ADD_LIGHTBOX_NAVIGATION_CLOSE_HOVER_SUCCESS);
    dopwggSettingsImageUpload('social_share_lightbox', 'uploads/settings/social-share-lightbox/', DOPWGG_SOCIAL_SHARE_LIGHTBOX_SUBMITED, DOPWGG_SOCIAL_SHARE_LIGHTBOX_SUCCESS);
}

function dopwggSettingsFormInput(id, value, label, pre, suf, input_class, help_class, help){// Create an Input Field.
    var inputHTML = new Array();

    inputHTML.push('    <div class="setting-box">');
    inputHTML.push('        <label for="'+id+'">'+label+'</label>');
    inputHTML.push('        <span class="pre">'+pre+'</span><input type="text" class="'+input_class+'" name="'+id+'" id="'+id+'" value="'+value+'" /><span class="suf">'+suf+'</span>');
    inputHTML.push('        <a href="javascript:void()" class="'+help_class+'" title="'+help+'"></a>');
    inputHTML.push('        <br class="DOPWGG-clear" />');
    inputHTML.push('    </div>');

    return inputHTML.join('');
}

function dopwggSettingsFormSelect(id, value, label, pre, suf, input_class, help_class, help, values){// Create a Combo Box.
    var selectHTML = new Array(), i,
    valuesList = values.split(';;');

    selectHTML.push('    <div class="setting-box">');
    selectHTML.push('        <label for="'+id+'">'+label+'</label>');
    selectHTML.push('        <span class="pre">'+pre+'</span>');
    selectHTML.push('            <select name="'+id+'" id="'+id+'">');
    
    for (i=0; i<valuesList.length; i++){
        if (valuesList[i] == value){
            selectHTML.push('        <option value="'+valuesList[i]+'" selected="selected">'+valuesList[i]+'</option>');
        }
        else{
            selectHTML.push('        <option value="'+valuesList[i]+'">'+valuesList[i]+'</option>');
        }
    }
    selectHTML.push('            </select>');
    selectHTML.push('        <span class="suf">'+suf+'</span>');
    selectHTML.push('        <a href="javascript:void()" class="'+help_class+'" title="'+help+'"></a>');
    selectHTML.push('        <br class="DOPWGG-clear" />');
    selectHTML.push('    </div>');

    return selectHTML.join('');
}

function dopwggSettingsFormImage(id, value, label, help_class, help){// Create an Image Field.
    var imageHTML = new Array();

    imageHTML.push('    <div class="setting-box">');
    imageHTML.push('        <label for="'+id+'">'+label+'</label>');
    imageHTML.push('        <span class="pre"></span>');
    imageHTML.push('        <div class="uploadifyContainer" style="float:left; margin:0; width:120px;">');
    imageHTML.push('            <div><input type="file" name="'+id+'" id="'+id+'" style="width:120px;" /></div>');
    imageHTML.push('            <div id="fileQueue_'+id+'"></div>');
    imageHTML.push('        </div>');
    imageHTML.push('        <a href="javascript:void()" class="'+help_class+'" title="'+help+'"></a>');
    imageHTML.push('        <br class="DOPWGG-clear" />');
    imageHTML.push('        <label for=""></label>');
    imageHTML.push('        <span class="pre"></span>');
    imageHTML.push('        <div class="uploadifyContainer" id="'+id+'_image" style="float:left; margin:5px 0 0 0; padding:0 0 10px 0;">');
    imageHTML.push('            <img src="'+DOPWGG_plugin_url+value+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
    imageHTML.push('        </div>');
    imageHTML.push('        <br class="DOPWGG-clear" />');
    imageHTML.push('    </div>');

    return imageHTML.join('');
}

function dopwggSettingsImageUpload(id, path, submitMessage, successMessage){
    $jDOPWGG('#'+id).uploadify({
        'uploader'       : DOPWGG_plugin_url+'libraries/swf/uploadify.swf',
        'script'         : DOPWGG_plugin_url+'libraries/php/uploadify-settings.php?data='+DOPWGG_plugin_abs+';;'+path+';;'+$jDOPWGG('#blog_id').val()+'-'+$jDOPWGG('#gallery_id').val(),
        'cancelImg'      : DOPWGG_plugin_url+'libraries/gui/images/uploadify/cancel.png',
        'folder'         : '',
        'queueID'        : 'fileQueue_'+id,
        'buttonText'     : DOPWGG_SELECT_FILE,
        'auto'           : true,
        'multi'          : false,
        'onInit'         : function(){
                               dopwggResize();
                           },
        'onCancel'         : function(event,ID,fileObj,data){
                               dopwggResize();
                           },
        'onSelect'       : function(event, ID, fileObj){
                               clearClick = false;
                               dopwggToggleMessage('show', submitMessage);
                               setTimeout(function(){
                                   dopwggResize();
                               }, 100);
                           },
        'onComplete'     : function(event, ID, fileObj, response, data){
                               if (response != -1){
                                   setTimeout(function(){
                                       dopwggResize();
                                   }, 1000);

                                   $jDOPWGG.post(ajaxurl, {action:'dopwgg_update_settings_image', item:id, gallery_id:$jDOPWGG('#gallery_id').val(), path:response}, function(data){
                                       $jDOPWGG('#'+id+'_image').html('<img src="'+DOPWGG_plugin_url+response+'?cacheBuster='+dopwggRandomString(64)+'" alt="" />');
                                       dopwggToggleMessage('hide', successMessage);
                                   });
                               }
                           }
    });
}

// Functions

function dopwggRemoveColumns(no){// Clear columns content.
    if (no <= 2){
        $jDOPWGG('.column-header', '.column2', '.DOPWGG-admin').html('');
        $jDOPWGG('.column-content', '.column2', '.DOPWGG-admin').html('');
    }
    if (no <= 3){
        $jDOPWGG('.column-header', '.column3', '.DOPWGG-admin').html('');
        $jDOPWGG('.column-content', '.column3', '.DOPWGG-admin').html('');
        imageDisplay = false;
        currImage = 0;
        dopwggResize();
    }
}

function dopwggToggleMessage(action, message){// Display Info Messages.
    dopwggResize();
    
    if (action == 'show'){
        clearClick = false;
        $jDOPWGG('#DOPWGG-admin-message').addClass('loader');
        $jDOPWGG('#DOPWGG-admin-message').html(message);
        $jDOPWGG('#DOPWGG-admin-message').stop(true, true).animate({'opacity':1}, 600);
    }
    else{
        clearClick = true;
        $jDOPWGG('#DOPWGG-admin-message').removeClass('loader');
        $jDOPWGG('#DOPWGG-admin-message').html(message);
        
        setTimeout(function(){
            $jDOPWGG('#DOPWGG-admin-message').stop(true, true).animate({'opacity':0}, 600, function(){
                $jDOPWGG('#DOPWGG-admin-message').html('');
            });
        }, 2000);
    }
}

function dopwggMoveTop(){
    jQuery('html').stop(true, true).animate({'scrollTop':'0'}, 300);
    jQuery('body').stop(true, true).animate({'scrollTop':'0'}, 300);
}

function dopwggRandomString(string_length){// Create a string with random elements
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz",
    random_string = '';

    for (var i=0; i<string_length; i++){
        var rnum = Math.floor(Math.random()*chars.length);
        random_string += chars.substring(rnum,rnum+1);
    }
    return random_string;
}

function dopwggIdealTextColor(bgColor){
    var rgb = /rgb\((\d+).*?(\d+).*?(\d+)\)/.exec(bgColor);
    
    if (rgb != null){
        return parseInt(rgb[1], 10)+parseInt(rgb[2], 10)+parseInt(rgb[3], 10) < 3*256/2 ? 'white' : 'black';
    }
    else{
        return parseInt(bgColor.substring(0, 2), 16)+parseInt(bgColor.substring(2, 4), 16)+parseInt(bgColor.substring(4, 6), 16) < 3*256/2 ? 'white' : 'black';
    }
}