<?php

/*
* Title                   : Wall/Grid Gallery (WordPress Plugin)
* Version                 : 1.8
* File                    : dopwgg-frontend.php
* File Version            : 1.6
* Created / Last Modified : 13 January 2013
* Author                  : Dot on Paper
* Copyright               : © 2011 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Wall/Grid Gallery Front End Class.
*/

    if (!class_exists("DOPWallGridGalleryFrontEnd")){
        class DOPWallGridGalleryFrontEnd{
            function DOPWallGridGalleryFrontEnd(){// Constructor.
                add_action('wp_enqueue_scripts', array(&$this, 'addStyles'));
                add_action('wp_enqueue_scripts', array(&$this, 'addScripts'));
                $this->init();
            }
            
            function addStyles(){
                // Register Styles.
                wp_register_style('DOPWGG_JScrollPaneStyle', plugins_url('libraries/gui/css/jquery.jscrollpane.css', __FILE__));
                wp_register_style('DOPWGG_WallGridGalleryStyle', plugins_url('assets/gui/css/jquery.dop.WallGridGallery.css', __FILE__));
                
                // Enqueue Styles.
                wp_enqueue_style('DOPWGG_JScrollPaneStyle');
                wp_enqueue_style('DOPWGG_WallGridGalleryStyle');
            }
            
            function addScripts(){
                // Register JavaScript.
                if (preg_match('/MSIE 7/i', $_SERVER['HTTP_USER_AGENT'])){
                    wp_register_script('DOPWGG_json2', plugins_url('libraries/js/json2.js', __FILE__), array('jquery'));
                }
                wp_register_script('DOPWGG_MouseWheelJS', plugins_url('libraries/js/jquery.mousewheel.js', __FILE__), array('jquery'));
                wp_register_script('DOPWGG_JScrollPaneJS', plugins_url('libraries/js/jquery.jscrollpane.min.js', __FILE__), array('jquery'));
                wp_register_script('DOPWGG_WallGridGalleryJS', plugins_url('assets/js/jquery.dop.WallGridGallery.js', __FILE__), array('jquery'));

                // Enqueue JavaScript.
                if (!wp_script_is('jquery', 'queue')){
                    wp_enqueue_script('jquery');
                }
                
                if (preg_match('/MSIE 7/i', $_SERVER['HTTP_USER_AGENT'])){
                    wp_enqueue_script('DOPWGG_json2');
                }
                wp_enqueue_script('DOPWGG_MouseWheelJS');
                wp_enqueue_script('DOPWGG_JScrollPaneJS');
                wp_enqueue_script('DOPWGG_WallGridGalleryJS');
            }

            function init(){// Init Gallery.
                $this->initConstants();
                add_shortcode('dopwgg', array(&$this, 'captionShortcode'));
            }

            function initConstants(){// Constants init.
                global $wpdb;
                
                // Paths
                define('DOPWGG_Plugin_AbsPath', ABSPATH.'wp-content/plugins/dopwgg/');
                define('DOPWGG_Plugin_URL', WP_PLUGIN_URL.'/dopwgg/');

                // Tables
                define('DOPWGG_Settings_table', $wpdb->prefix.'dopwgg_settings');
                define('DOPWGG_Galleries_table', $wpdb->prefix.'dopwgg_galleries');
                define('DOPWGG_Images_table', $wpdb->prefix.'dopwgg_images');
            }

            function captionShortcode($atts, $content = null){// Read Shortcodes.
                global $wpdb;
                $data = array();
                $imagesList = array();
                
                extract(shortcode_atts(array(
                    'class' => 'dopwgg',
                ), $atts));
                
                $default_settings = $wpdb->get_row('SELECT * FROM '.DOPWGG_Settings_table.' WHERE gallery_id="0"');
                $settings = $wpdb->get_row('SELECT * FROM '.DOPWGG_Settings_table.' WHERE gallery_id="'.$atts['id'].'"');
                
                if ($default_settings->data_parse_method == 'ajax'){
                    $data = '<div class="DOPWallGridGalleryContainer" id="DOPWallGridGallery'.$atts['id'].'">
                                 <a href="'.DOPWGG_Plugin_URL.'frontend-ajax.php"></a>
                             </div>
                             <script type="text/JavaScript">
                                 jQuery(document).ready(function(){
                                     jQuery(\'#DOPWallGridGallery'.$atts['id'].'\').DOPWallGridGallery();
                                 });
                             </script>';
                }
                else{
                    $images = $wpdb->get_results('SELECT * FROM '.DOPWGG_Images_table.' WHERE gallery_id="'.$atts['id'].'" AND enabled="true" ORDER BY position');

                    foreach ($images as $image){
                        array_push($imagesList, '<li>
                                                    <span class="Image">'.DOPWGG_Plugin_URL.'uploads/'.$image->name.'</span>
                                                    <span class="Thumb">'.DOPWGG_Plugin_URL.'uploads/thumbs/'.$image->name.'</span>
                                                    <span class="CaptionTitle">'.stripslashes($image->title).'</span>
                                                    <span class="CaptionText">'.stripslashes($image->caption).'</span>
                                                    <span class="Media">'.stripslashes($image->media).'</span>
                                                    <span class="Link">'.stripslashes($image->link).'</span>
                                                    <span class="LinkTarget">'.stripslashes($image->target).'</span>
                                                 </li>');
                    }
                
                    $data = '<div class="DOPWallGridGalleryContainer" id="DOPWallGridGallery'.$atts['id'].'">
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
                                    jQuery(\'#DOPWallGridGallery'.$atts['id'].'\').DOPWallGridGallery({\'ParseMethod\': \'HTML\'});
                                });
                            </script>';
                }
                
                return $data;
            }

            function getGalleryData(){// Get Gallery Info.
                global $wpdb;
                $data = array();

                $settings = $wpdb->get_row('SELECT * FROM '.DOPWGG_Settings_table.' WHERE gallery_id="'.$_POST['id'].'"');
                
                $data['Width'] = $settings->width;
                $data['Height'] = $settings->height;
                $data['BgColor'] = $settings->bg_color;
                $data['BgAlpha'] = $settings->bg_alpha;
                $data['NoLines'] = $settings->no_lines;
                $data['NoColumns'] = $settings->no_columns;  
                $data['ImagesOrder'] = $settings->images_order;
                $data['ResponsiveEnabled'] = $settings->responsive_enabled;                               
                $data['ThumbnailsSpacing'] = $settings->thumbnails_spacing;
                $data['ThumbnailsPaddingTop'] = $settings->thumbnails_padding_top;
                $data['ThumbnailsPaddingRight'] = $settings->thumbnails_padding_right;
                $data['ThumbnailsPaddingBottom'] = $settings->thumbnails_padding_bottom;
                $data['ThumbnailsPaddingLeft'] = $settings->thumbnails_padding_left;
                $data['ThumbnailsNavigation'] = $settings->thumbnails_navigation;
                $data['ThumbnailsScrollScrubColor'] = $settings->thumbnails_scroll_scrub_color;
                $data['ThumbnailsScrollBarColor'] = $settings->thumbnails_scroll_bar_color;   
                $data['ThumbnailsInfo'] = $settings->thumbnails_info;                             
                $data['ThumbnailLoader'] = DOPWGG_Plugin_URL.$settings->thumbnail_loader;
                $data['ThumbnailWidth'] = $settings->thumbnail_width;
                $data['ThumbnailHeight'] = $settings->thumbnail_height;
                $data['ThumbnailWidthMobile'] = $settings->thumbnail_width_mobile;
                $data['ThumbnailHeightMobile'] = $settings->thumbnail_height_mobile;
                $data['ThumbnailAlpha'] = $settings->thumbnail_alpha;
                $data['ThumbnailAlphaHover'] = $settings->thumbnail_alpha_hover;
                $data['ThumbnailBgColor'] = $settings->thumbnail_bg_color;
                $data['ThumbnailBgColorHover'] = $settings->thumbnail_bg_color_hover;
                $data['ThumbnailBorderSize'] = $settings->thumbnail_border_size;
                $data['ThumbnailBorderColor'] = $settings->thumbnail_border_color;
                $data['ThumbnailBorderColorHover'] = $settings->thumbnail_border_color_hover;
                $data['ThumbnailPaddingTop'] = $settings->thumbnail_padding_top;
                $data['ThumbnailPaddingRight'] = $settings->thumbnail_padding_right;
                $data['ThumbnailPaddingBottom'] = $settings->thumbnail_padding_bottom;
                $data['ThumbnailPaddingLeft'] = $settings->thumbnail_padding_left;
                $data['LightboxPosition'] = $settings->lightbox_position;
                $data['LightboxWindowColor'] = $settings->lightbox_window_color;
                $data['LightboxWindowAlpha'] = $settings->lightbox_window_alpha;
                $data['LightboxLoader'] = DOPWGG_Plugin_URL.$settings->lightbox_loader;
                $data['LightboxBgColor'] = $settings->lightbox_bg_color;
                $data['LightboxBgAlpha'] = $settings->lightbox_bg_alpha;
                $data['LightboxMarginTop'] = $settings->lightbox_margin_top;
                $data['LightboxMarginRight'] = $settings->lightbox_margin_right;
                $data['LightboxMarginBottom'] = $settings->lightbox_margin_bottom;
                $data['LightboxMarginLeft'] = $settings->lightbox_margin_left;
                $data['LightboxPaddingTop'] = $settings->lightbox_padding_top;
                $data['LightboxPaddingRight'] = $settings->lightbox_padding_right;
                $data['LightboxPaddingBottom'] = $settings->lightbox_padding_bottom;
                $data['LightboxPaddingLeft'] = $settings->lightbox_padding_left;
                $data['LightboxNavigationPrev'] = DOPWGG_Plugin_URL.$settings->lightbox_navigation_prev;
                $data['LightboxNavigationPrevHover'] = DOPWGG_Plugin_URL.$settings->lightbox_navigation_prev_hover;
                $data['LightboxNavigationNext'] = DOPWGG_Plugin_URL.$settings->lightbox_navigation_next;
                $data['LightboxNavigationNextHover'] = DOPWGG_Plugin_URL.$settings->lightbox_navigation_next_hover;
                $data['LightboxNavigationClose'] = DOPWGG_Plugin_URL.$settings->lightbox_navigation_close;
                $data['LightboxNavigationCloseHover'] = DOPWGG_Plugin_URL.$settings->lightbox_navigation_close_hover;
                $data['CaptionHeight'] = $settings->caption_height;
                $data['CaptionTitleColor'] = $settings->caption_title_color;
                $data['CaptionTextColor'] = $settings->caption_text_color;
                $data['CaptionScrollScrubColor'] = $settings->caption_scroll_scrub_color;
                $data['CaptionScrollBgColor'] = $settings->caption_scroll_bg_color;
                $data['SocialShareEnabled'] = $settings->social_share_enabled;   
                $data['SocialShareLightbox'] = DOPWGG_Plugin_URL.$settings->social_share_lightbox;      
                $data['TooltipBgColor'] = $settings->tooltip_bg_color;
                $data['TooltipStrokeColor'] = $settings->tooltip_stroke_color;
                $data['TooltipTextColor'] = $settings->tooltip_text_color;   
                $data['LabelPosition'] = $settings->label_position;   
                $data['LabelTextColor'] = $settings->label_text_color;   
                $data['LabelTextColorHover'] = $settings->label_text_color_hover;     

                $data['Data'] = $this->getImages($_POST['id']);    
                
                echo json_encode($data);
            }

            function getImages($id){// Get Images.
                global $wpdb;

                $imagesList = array();
                $images = $wpdb->get_results('SELECT * FROM '.DOPWGG_Images_table.' WHERE gallery_id="'.$_POST['id'].'" AND enabled="true" ORDER BY position');

                foreach ($images as $image){
                    array_push($imagesList, array('Image' => DOPWGG_Plugin_URL.'uploads/'.$image->name,
                                                  'Thumb' => DOPWGG_Plugin_URL.'uploads/thumbs/'.$image->name,
                                                  'CaptionTitle' => stripslashes($image->title),
                                                  'CaptionText' => stripslashes($image->caption),
                                                  'Media' => stripslashes($image->media),
                                                  'Link' => stripslashes($image->link),
                                                  'LinkTarget' => stripslashes($image->target)));
                }

                return $imagesList;
            }
        }
    }
?>