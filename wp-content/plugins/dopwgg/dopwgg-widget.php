<?php

/*
* Title                   : Wall/Grid Gallery (WordPress Plugin)
* Version                 : 1.8
* File                    : dopwgg-widget.php
* File Version            : 1.2
* Created / Last Modified : 13 January 2013
* Author                  : Dot on Paper
* Copyright               : © 2011 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Wall/Grid Gallery Widget Class.
*/
  
    class DOPWallGridGalleryWidget extends WP_Widget{
        
        function DOPWallGridGalleryWidget(){
            $widget_ops = array('classname' => 'DOPWallGridGalleryWidget', 'description' => DOPWGG_WIDGET_DESCRIPTION);
            $this->WP_Widget('DOPWallGridGalleryWidget', DOPWGG_WIDGET_TITLE, $widget_ops);
        }
 
        function form($instance){
            global $wpdb;
            
            $instance = wp_parse_args((array)$instance, array('title' => '', 'id' => '0'));
            $title = $instance['title'];
            $id = $instance['id'];
                            
            $galleryHTML = array();
            
            array_push($galleryHTML, '<p>');
            array_push($galleryHTML, '    <label for="'.$this->get_field_id('title').'">'.DOPWGG_WIDGET_LABEL_TITLE.' </label>');
            array_push($galleryHTML, '    <input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.esc_attr($title).'" />');
            
            array_push($galleryHTML, '    <label for="'.$this->get_field_id('id').'" style=" display: block; padding-top: 10px;">'.DOPWGG_WIDGET_LABEL_ID.' </label>');
            array_push($galleryHTML, '    <select class="widefat" id="'.$this->get_field_id('id').'" name="'.$this->get_field_name('id').'">');

            $galleries = $wpdb->get_results('SELECT * FROM '.DOPWGG_Galleries_table.' ORDER BY id DESC');

            if ($wpdb->num_rows != 0){
                foreach ($galleries as $gallery) {
                    if (esc_attr($id) == $gallery->id){
                        array_push($galleryHTML, '<option value="'.$gallery->id.'" selected="selected">'.$gallery->id.' - '.$gallery->name.'</option>');
                        
                    }
                    else{
                        array_push($galleryHTML, '<option value="'.$gallery->id.'">'.$gallery->id.' - '.$gallery->name.'</option>');
                    }
                }
            }
            else{
                array_push($galleryHTML, '<option value="0">'.DOPWGG_WIDGET_NO_SCROLLERS.'</option>');
            }
            
            array_push($galleryHTML, '    </select>');
            array_push($galleryHTML, '</p>');

            echo implode('', $galleryHTML);
        }
 
        function update($new_instance, $old_instance){
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['id'] = $new_instance['id'];
            
            return $instance;
        }

        function widget($args, $instance){
            global $wpdb;
            $data = array();
            $imagesList = array();
            extract($args, EXTR_SKIP);

            echo $before_widget;
            $title = empty($instance['title']) ? ' ':apply_filters('widget_title', $instance['title']);
            $id = empty($instance['id']) ? '0':$instance['id'];
 
            if (!empty($title)){
                echo $before_title.$title.$after_title;        
            }

            extract(shortcode_atts(array(
                'class' => 'dopwgg',
            ), $atts));

            $default_settings = $wpdb->get_row('SELECT * FROM '.DOPWGG_Settings_table.' WHERE gallery_id="0"');
            $settings = $wpdb->get_row('SELECT * FROM '.DOPWGG_Settings_table.' WHERE gallery_id="'.$id.'"');

            if ($default_settings->data_parse_method == 'ajax'){
                 echo '<div class="DOPWallGridGalleryContainer" id="DOPWallGridGallery'.$id.'">
                         <a href="'.DOPWGG_Plugin_URL.'frontend-ajax.php"></a>
                     </div>
                     <script type="text/JavaScript">
                         jQuery(document).ready(function(){
                             jQuery(\'#DOPWallGridGallery'.$id.'\').DOPWallGridGallery();
                         });
                     </script>';
            }
            else{
                $images = $wpdb->get_results('SELECT * FROM '.DOPWGG_Images_table.' WHERE gallery_id="'.$id.'" AND enabled="true" ORDER BY position');

                foreach ($images as $image){
                    array_push($imagesList, '<li>
                                                <span class="Image">'.DOPWGG_Plugin_URL.'uploads/'.$image->name.'</span>
                                                <span class="Thumb">'.DOPWGG_Plugin_URL.'uploads/thumbs/'.$image->name.'</span>
                                                <span class="CaptionTitle">'.stripslashes($image->title).'</span>
                                                <span class="CaptionText">'.stripslashes($image->caption).'</span>
                                                <span class="Media">'.stripslashes($image->media).'</span>
                                                <span class="Link">'.stripslashes($image->link).'</span>
                                                <span class="Target">'.stripslashes($image->target).'</span>
                                             </li>');
                }
            
                echo '<div class="DOPWallGridGalleryContainer" id="DOPWallGridGallery'.$id.'">
                        <ul class="Settings" style="display:none;">
                            <li class="Width">'.$settings->width.'</li>
                            <li class="Height">'.$settings->height.'</li>
                            <li class="BgColor">'.$settings->bg_color.'</li>
                            <li class="BgAlpha">'.$settings->bg_alpha.'</li>
                            <li class="NoLines">'.$settings->no_lines.'</li>
                            <li class="NoColumns">'.$settings->no_columns.'</li>
                            <li class="ImagesOrder">'.$settings->images_order.'</li>
                            <li class="ResponsiveEnabled">'.$settings->responsive_enabled.'</li>
                            <li class="ThumbnailsSpacing">'.$settings->thumbnails_spacing.'</li>
                            <li class="ThumbnailsPaddingTop">'.$settings->thumbnails_padding_top.'</li>
                            <li class="ThumbnailsPaddingRight">'.$settings->thumbnails_padding_right.'</li>
                            <li class="ThumbnailsPaddingBottom">'.$settings->thumbnails_padding_bottom.'</li>
                            <li class="ThumbnailsPaddingLeft">'.$settings->thumbnails_padding_left.'</li>
                            <li class="ThumbnailsNavigation">'.$settings->thumbnails_navigation.'</li>
                            <li class="ThumbnailsScrollScrubColor">'.$settings->thumbnails_scroll_scrub_color.'</li>
                            <li class="ThumbnailsScrollBarColor">'.$settings->thumbnails_scroll_bar_color.'</li>
                            <li class="ThumbnailsInfo">'.$settings->thumbnails_info.'</li>
                            <li class="ThumbnailLoader">'.DOPWGG_Plugin_URL.$settings->thumbnail_loader.'</li>
                            <li class="ThumbnailWidth">'.$settings->thumbnail_width.'</li>
                            <li class="ThumbnailHeight">'.$settings->thumbnail_height.'</li>
                            <li class="ThumbnailWidthMobile">'.$settings->thumbnail_width_mobile.'</li>
                            <li class="ThumbnailHeightMobile">'.$settings->thumbnail_height_mobile.'</li>
                            <li class="ThumbnailAlpha">'.$settings->thumbnail_alpha.'</li>
                            <li class="ThumbnailAlphaHover">'.$settings->thumbnail_alpha_hover.'</li>
                            <li class="ThumbnailBgColor">'.$settings->thumbnail_bg_color.'</li>
                            <li class="ThumbnailBgColorHover">'.$settings->thumbnail_bg_color_hover.'</li>
                            <li class="ThumbnailBorderSize">'.$settings->thumbnail_border_size.'</li>
                            <li class="ThumbnailBorderColor">'.$settings->thumbnail_border_color.'</li>
                            <li class="ThumbnailBorderColorHover">'.$settings->thumbnail_border_color_hover.'</li>
                            <li class="ThumbnailPaddingTop">'.$settings->thumbnail_padding_top.'</li>
                            <li class="ThumbnailPaddingRight">'.$settings->thumbnail_padding_right.'</li>
                            <li class="ThumbnailPaddingBottom">'.$settings->thumbnail_padding_bottom.'</li>
                            <li class="ThumbnailPaddingLeft">'.$settings->thumbnail_padding_left.'</li>
                            <li class="LightboxPosition">'.$settings->lightbox_position.'</li>
                            <li class="LightboxWindowColor">'.$settings->lightbox_window_color.'</li>
                            <li class="LightboxWindowAlpha">'.$settings->lightbox_window_alpha.'</li>
                            <li class="LightboxLoader">'.DOPWGG_Plugin_URL.$settings->lightbox_loader.'</li>
                            <li class="LightboxBgColor">'.$settings->lightbox_bg_color.'</li>
                            <li class="LightboxBgAlpha">'.$settings->lightbox_bg_alpha.'</li>
                            <li class="LightboxMarginTop">'.$settings->lightbox_margin_top.'</li>
                            <li class="LightboxMarginRight">'.$settings->lightbox_margin_right.'</li>
                            <li class="LightboxMarginBottom">'.$settings->lightbox_margin_bottom.'</li>
                            <li class="LightboxMarginLeft">'.$settings->lightbox_margin_left.'</li>
                            <li class="LightboxPaddingTop">'.$settings->lightbox_padding_top.'</li>
                            <li class="LightboxPaddingRight">'.$settings->lightbox_padding_right.'</li>
                            <li class="LightboxPaddingBottom">'.$settings->lightbox_padding_bottom.'</li>
                            <li class="LightboxPaddingLeft">'.$settings->lightbox_padding_left.'</li>
                            <li class="LightboxNavigationPrev">'.DOPWGG_Plugin_URL.$settings->lightbox_navigation_prev.'</li>
                            <li class="LightboxNavigationPrevHover">'.DOPWGG_Plugin_URL.$settings->lightbox_navigation_prev_hover.'</li>
                            <li class="LightboxNavigationNext">'.DOPWGG_Plugin_URL.$settings->lightbox_navigation_next.'</li>
                            <li class="LightboxNavigationNextHover">'.DOPWGG_Plugin_URL.$settings->lightbox_navigation_next_hover.'</li>
                            <li class="LightboxNavigationClose">'.DOPWGG_Plugin_URL.$settings->lightbox_navigation_close.'</li>
                            <li class="LightboxNavigationCloseHover">'.DOPWGG_Plugin_URL.$settings->lightbox_navigation_close_hover.'</li>
                            <li class="CaptionHeight">'.$settings->caption_height.'</li>
                            <li class="CaptionTitleColor">'.$settings->caption_title_color.'</li>
                            <li class="CaptionTextColor">'.$settings->caption_text_color.'</li>
                            <li class="CaptionScrollScrubColor">'.$settings->caption_scroll_scrub_color.'</li>
                            <li class="CaptionScrollBgColor">'.$settings->caption_scroll_bg_color.'</li>    
                            <li class="SocialShareEnabled">'.$settings->social_share_enabled.'</li>
                            <li class="SocialShareLightbox">'.DOPWGG_Plugin_URL.$settings->social_share_lightbox.'</li>
                            <li class="TooltipBgColor">'.$settings->tooltip_bg_color.'</li>
                            <li class="TooltipStrokeColor">'.$settings->tooltip_stroke_color.'</li>
                            <li class="TooltipTextColor">'.$settings->tooltip_text_color.'</li>
                            <li class="LabelPosition">'.$settings->label_position.'</li>
                            <li class="LabelTextColor">'.$settings->label_text_color.'</li>
                            <li class="LabelTextColorHover">'.$settings->label_text_color_hover.'</li>
                        </ul>
                        <ul class="Content" style="display:none;">'.implode('', $imagesList).'</ul>
                    </div>
                    <script type="text/JavaScript">
                        jQuery(document).ready(function(){
                            jQuery(\'#DOPWallGridGallery'.$id.'\').DOPWallGridGallery({\'ParseMethod\': \'HTML\'});
                        });
                    </script>';
            }

            echo $after_widget;
        }

    }

?>