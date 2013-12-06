<?php

/*
* Title                   : Wall/Grid Gallery (WordPress Plugin)
* Version                 : 1.8
* File                    : dopwgg-backend.php
* File Version            : 1.7
* Created / Last Modified : 25 March 2013
* Author                  : Dot on Paper
* Copyright               : © 2011 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Wall/Grid Gallery Back End Class.
*/

    if (!class_exists("DOPWallGridGalleryBackEnd")){
        class DOPWallGridGalleryBackEnd{
            private $DOPWGG_AddEditGalleries;
            private $DOPWGG_db_version = 1.8;

            function DOPWallGridGalleryBackEnd(){// Constructor.
                if (is_admin()){
                    if ($this->validPage()){
                        $this->DOPWGG_AddEditGalleries = new DOPWGGTemplates();
                        add_action('admin_enqueue_scripts', array(&$this, 'addStyles'));
                        add_action('admin_enqueue_scripts', array(&$this, 'addScripts'));
                    }
                    $this->addDOPWGGtoTinyMCE();
                    $this->init();
                }
            }
            
            function addStyles(){
                // Register Styles.
                wp_register_style('DOPWGG_UploadifyStyle', plugins_url('libraries/gui/css/uploadify.css', __FILE__));
                wp_register_style('DOPWGG_JcropStyle', plugins_url('libraries/gui/css/jquery.Jcrop.css', __FILE__));
                wp_register_style('DOPWGG_ColorPickerStyle', plugins_url('libraries/gui/css/colorpicker.css', __FILE__));
                wp_register_style('DOPWGG_AdminStyle', plugins_url('assets/gui/css/backend-style.css', __FILE__));

                // Enqueue Styles.
                wp_enqueue_style('thickbox');
                wp_enqueue_style('DOPWGG_UploadifyStyle');
                wp_enqueue_style('DOPWGG_JcropStyle');
                wp_enqueue_style('DOPWGG_ColorPickerStyle');
                wp_enqueue_style('DOPWGG_AdminStyle');
            }
            
            function addScripts(){
                // Register JavaScript.
                wp_register_script('DOPWGG_SwfJS', plugins_url('libraries/js/swfobject.js', __FILE__), array('jquery'));
                wp_register_script('DOPWGG_UploadifyJS', plugins_url('libraries/js/jquery.uploadify.min.js', __FILE__), array('jquery'));
                wp_register_script('DOPWGG_JcropJS', plugins_url('libraries/js/jquery.Jcrop.min.js', __FILE__), array('jquery'));
                wp_register_script('DOPWGG_ColorPickerJS', plugins_url('libraries/js/colorpicker.js', __FILE__), array('jquery'));
                wp_register_script('DOPWGG_DOPImageLoaderJS', plugins_url('libraries/js/jquery.dop.ImageLoader.min.js', __FILE__), array('jquery'));
                wp_register_script('DOPWGG_DOPWGGJS', plugins_url('assets/js/dopwgg-backend.js', __FILE__), array('jquery'));

                // Enqueue JavaScript.
                if (!wp_script_is('jquery', 'queue')){
                    wp_enqueue_script('jquery');
                }
                
                if (!wp_script_is('jquery-ui-sortable', 'queue')){
                    wp_enqueue_script('jquery-ui-sortable');
                }
                wp_enqueue_script('media-upload');
                
                if (!wp_script_is('thickbox', 'queue')){
                    wp_enqueue_script('thickbox');
                }
                wp_enqueue_script('my-upload');
                wp_enqueue_script('DOPWGG_SwfJS');
                wp_enqueue_script('DOPWGG_UploadifyJS');
                wp_enqueue_script('DOPWGG_JcropJS');
                wp_enqueue_script('DOPWGG_ColorPickerJS');
                wp_enqueue_script('DOPWGG_DOPImageLoaderJS');
                wp_enqueue_script('DOPWGG_DOPWGGJS');
            }
            
            function init(){// Admin init.
                $this->initConstants();
                
                if (is_admin()){
                    if ($this->validPage()){
                        $this->initTables();
                        
                        if (strrpos(strtolower(php_uname()), 'windows') === false){
                            $this->initUploadFolders();
                        }
                    }
                }
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

            function validPage(){// Valid Admin Page.
                if (isset($_GET['page'])){
                    if ($_GET['page'] == 'dopwgg' || $_GET['page'] == 'dopwgg-settings'){
                        return true;
                    }
                    else{
                        return false;
                    }
                }
                else{
                    return false;
                }
            }

            function initTables(){// Tables init.
                $current_db_version = get_option('DOPWGG_db_version');
                
                if ($this->DOPWGG_db_version != $current_db_version){
                    require_once(ABSPATH.'wp-admin/includes/upgrade.php');

                    $sql_settings = "CREATE TABLE " . DOPWGG_Settings_table . " (
                                        id int NOT NULL AUTO_INCREMENT,
                                        name VARCHAR(128) DEFAULT '" . DOPWGG_ADD_GALLERY_NAME . "' COLLATE utf8_unicode_ci NOT NULL,
                                        gallery_id int DEFAULT 0 NOT NULL,
                                        data_parse_method VARCHAR(4) DEFAULT 'ajax' COLLATE utf8_unicode_ci NOT NULL,
                                        width int DEFAULT 900 NOT NULL,
                                        height int DEFAULT 0 NOT NULL,
                                        bg_color VARCHAR(6) DEFAULT 'ffffff' COLLATE utf8_unicode_ci NOT NULL,
                                        bg_alpha int DEFAULT 100 NOT NULL,
                                        no_lines VARCHAR(6) DEFAULT '3' COLLATE utf8_unicode_ci NOT NULL,
                                        no_columns VARCHAR(6) DEFAULT '4' COLLATE utf8_unicode_ci NOT NULL,  
                                        images_order VARCHAR(6) DEFAULT 'normal' COLLATE utf8_unicode_ci NOT NULL,  
                                        responsive_enabled VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,                                
                                        thumbnails_spacing int DEFAULT 15 NOT NULL,
                                        thumbnails_padding_top int DEFAULT 0 NOT NULL,
                                        thumbnails_padding_right int DEFAULT 0 NOT NULL,
                                        thumbnails_padding_bottom int DEFAULT 0 NOT NULL,
                                        thumbnails_padding_left int DEFAULT 0 NOT NULL,
                                        thumbnails_navigation VARCHAR(6) DEFAULT 'mouse' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnails_scroll_scrub_color VARCHAR(6) DEFAULT '777777' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnails_scroll_bar_color VARCHAR(6) DEFAULT 'e0e0e0' COLLATE utf8_unicode_ci NOT NULL,                             
                                        thumbnails_info VARCHAR(8) DEFAULT 'tooltip' COLLATE utf8_unicode_ci NOT NULL,                                  
                                        thumbnail_loader VARCHAR(128) DEFAULT 'assets/gui/images/ThumbnailLoader.gif' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_width int DEFAULT 200 NOT NULL,
                                        thumbnail_height int DEFAULT 100 NOT NULL,
                                        thumbnail_width_mobile int DEFAULT 100 NOT NULL,
                                        thumbnail_height_mobile int DEFAULT 50 NOT NULL,
                                        thumbnail_alpha int DEFAULT 80 NOT NULL,
                                        thumbnail_alpha_hover int DEFAULT 100 NOT NULL,
                                        thumbnail_bg_color VARCHAR(6) DEFAULT 'cccccc' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_bg_color_hover VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_border_size int DEFAULT 0 NOT NULL,
                                        thumbnail_border_color VARCHAR(6) DEFAULT 'cccccc' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_border_color_hover VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        thumbnail_padding_top int DEFAULT 3 NOT NULL,
                                        thumbnail_padding_right int DEFAULT 3 NOT NULL,
                                        thumbnail_padding_bottom int DEFAULT 3 NOT NULL,
                                        thumbnail_padding_left int DEFAULT 3 NOT NULL,
                                        lightbox_position VARCHAR(8) DEFAULT 'document' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_window_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_window_alpha int DEFAULT 80 NOT NULL,
                                        lightbox_loader VARCHAR(128) DEFAULT 'assets/gui/images/LightboxLoader.gif' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_bg_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_bg_alpha int DEFAULT 100 NOT NULL,
                                        lightbox_margin_top int DEFAULT 70 NOT NULL,
                                        lightbox_margin_right int DEFAULT 70 NOT NULL,
                                        lightbox_margin_bottom int DEFAULT 70 NOT NULL,
                                        lightbox_margin_left int DEFAULT 70 NOT NULL,
                                        lightbox_padding_top int DEFAULT 10 NOT NULL,
                                        lightbox_padding_right int DEFAULT 10 NOT NULL,
                                        lightbox_padding_bottom int DEFAULT 10 NOT NULL,
                                        lightbox_padding_left int DEFAULT 10 NOT NULL,                                    
                                        lightbox_navigation_prev VARCHAR(128) DEFAULT 'assets/gui/images/LightboxPrev.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_prev_hover VARCHAR(128) DEFAULT 'assets/gui/images/LightboxPrevHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_next VARCHAR(128) DEFAULT 'assets/gui/images/LightboxNext.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_next_hover VARCHAR(128) DEFAULT 'assets/gui/images/LightboxNextHover.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_close VARCHAR(128) DEFAULT 'assets/gui/images/LightboxClose.png' COLLATE utf8_unicode_ci NOT NULL,
                                        lightbox_navigation_close_hover VARCHAR(128) DEFAULT 'assets/gui/images/LightboxCloseHover.png' COLLATE utf8_unicode_ci NOT NULL,                                    
                                        caption_height int DEFAULT 75 NOT NULL,
                                        caption_title_color VARCHAR(6) DEFAULT 'eeeeee' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_text_color VARCHAR(6) DEFAULT 'dddddd' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_scroll_scrub_color VARCHAR(6) DEFAULT '777777' COLLATE utf8_unicode_ci NOT NULL,
                                        caption_scroll_bg_color VARCHAR(6) DEFAULT 'e0e0e0' COLLATE utf8_unicode_ci NOT NULL,
                                        social_share_enabled VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        social_share_lightbox VARCHAR(128) DEFAULT 'assets/gui/images/SocialShareLightbox.png' COLLATE utf8_unicode_ci NOT NULL,
                                        tooltip_bg_color VARCHAR(6) DEFAULT 'ffffff' COLLATE utf8_unicode_ci NOT NULL,
                                        tooltip_stroke_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,
                                        tooltip_text_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,  
                                        label_position VARCHAR(6) DEFAULT 'bottom' COLLATE utf8_unicode_ci NOT NULL,                                  
                                        label_text_color VARCHAR(6) DEFAULT '000000' COLLATE utf8_unicode_ci NOT NULL,                             
                                        label_text_color_hover VARCHAR(6) DEFAULT 'ffffff' COLLATE utf8_unicode_ci NOT NULL,
                                        UNIQUE KEY id (id)
                                    );";

                    $sql_galleries = "CREATE TABLE " . DOPWGG_Galleries_table . " (
                                        id int NOT NULL AUTO_INCREMENT,
                                        name VARCHAR(128) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
                                        UNIQUE KEY id (id)
                                    );";

                    $sql_images = "CREATE TABLE " . DOPWGG_Images_table . " (
                                        id int NOT NULL AUTO_INCREMENT,
                                        gallery_id int NOT NULL,
                                        name VARCHAR(128) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
                                        title VARCHAR(128) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
                                        caption VARCHAR(4096) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
                                        media VARCHAR(4096) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
                                        link VARCHAR(128) DEFAULT '' COLLATE utf8_unicode_ci NOT NULL,
                                        target VARCHAR(16) DEFAULT '_blank' COLLATE utf8_unicode_ci NOT NULL,
                                        enabled VARCHAR(6) DEFAULT 'true' COLLATE utf8_unicode_ci NOT NULL,
                                        position int DEFAULT 0 NOT NULL,
                                        UNIQUE KEY id (id)
                                    );";

                    dbDelta($sql_settings);
                    dbDelta($sql_galleries);
                    dbDelta($sql_images);

                    if ($current_db_version == ''){
                        add_option('DOPWGG_db_version', $this->DOPWGG_db_version);
                    }
                    else{
                        update_option('DOPWGG_db_version', $this->DOPWGG_db_version);
                    }

                    $this->initTablesData();
                }
            }
            
            function initTablesData(){
                global $wpdb;

                $settings = $wpdb->get_results('SELECT * FROM '.DOPWGG_Settings_table.' WHERE gallery_id=0');
                
                if ($wpdb->num_rows == 0){
                    dbDelta($wpdb->insert(DOPWGG_Settings_table, array('name' => DOPWGG_DEFAULT_SETTINGS,
                                                                       'gallery_id' => 0)));
                    
                    dbDelta($wpdb->insert(DOPWGG_Settings_table, array('name' => 'Example 1',
                                                                       'gallery_id' => 0)));
                    
                    dbDelta($wpdb->insert(DOPWGG_Settings_table, array('name' => 'Example 2',
                                                                       'gallery_id' => 0,
                                                                       'width' => 900,
                                                                       'height' => 0,
                                                                       'bg_color' => 'ffffff',
                                                                       'bg_alpha' => 100,
                                                                       'no_lines' => '3',
                                                                       'no_columns' => '4',
                                                                       'images_order' => 'normal',
                                                                       'responsive_enabled' => 'true',
                                                                       'thumbnails_spacing' => 15,
                                                                       'thumbnails_padding_top' => 0,
                                                                       'thumbnails_padding_right' => 0,
                                                                       'thumbnails_padding_bottom' => 0,
                                                                       'thumbnails_padding_left' => 0,
                                                                       'thumbnails_navigation' => 'mouse',
                                                                       'thumbnails_scroll_scrub_color' => '777777',
                                                                       'thumbnails_scroll_bar_color' => 'e0e0e0',
                                                                       'thumbnails_info' => 'label',
                                                                       'thumbnail_loader' => 'assets/gui/images/ThumbnailLoader2.gif',
                                                                       'thumbnail_width' => 200,
                                                                       'thumbnail_height' => 100,
                                                                       'thumbnail_width_mobile' => 100,
                                                                       'thumbnail_height_mobile' => 50,
                                                                       'thumbnail_alpha' => 100,
                                                                       'thumbnail_alpha_hover' => 100,
                                                                       'thumbnail_bg_color' => '000000',
                                                                       'thumbnail_bg_color_hover' => 'afbd21',
                                                                       'thumbnail_border_size' => 0,
                                                                       'thumbnail_border_color' => 'cccccc',
                                                                       'thumbnail_border_color_hover' => '000000',
                                                                       'thumbnail_padding_top' => 3,
                                                                       'thumbnail_padding_right' => 3,
                                                                       'thumbnail_padding_bottom' => 3,
                                                                       'thumbnail_padding_left' => 3,
                                                                       'lightbox_position' => 'document',
                                                                       'lightbox_window_color' => '000000',
                                                                       'lightbox_window_alpha' => 80,
                                                                       'lightbox_loader' => 'assets/gui/images/LightboxLoader2.gif',
                                                                       'lightbox_bg_color' => '000000',
                                                                       'lightbox_bg_alpha' => 100,
                                                                       'lightbox_margin_top' => 50,
                                                                       'lightbox_margin_right' => 50,
                                                                       'lightbox_margin_bottom' => 50,
                                                                       'lightbox_margin_left' => 50,
                                                                       'lightbox_padding_top' => 10,
                                                                       'lightbox_padding_right' => 10,
                                                                       'lightbox_padding_bottom' => 10,
                                                                       'lightbox_padding_left' => 10,
                                                                       'lightbox_navigation_prev' => 'assets/gui/images/LightboxPrev2.png',
                                                                       'lightbox_navigation_prev_hover' => 'assets/gui/images/LightboxPrevHover2.png',
                                                                       'lightbox_navigation_next' => 'assets/gui/images/LightboxNext2.png',
                                                                       'lightbox_navigation_next_hover' => 'assets/gui/images/LightboxNextHover2.png',
                                                                       'lightbox_navigation_close' => 'assets/gui/images/LightboxClose2.png',
                                                                       'lightbox_navigation_close_hover' => 'assets/gui/images/LightboxCloseHover2.png',
                                                                       'caption_height' => 100,
                                                                       'caption_title_color' => 'eeeeee',
                                                                       'caption_text_color' => 'dddddd',
                                                                       'caption_scroll_scrub_color' => '777777',
                                                                       'caption_scroll_bg_color' => 'e0e0e0',
                                                                       'social_share_enabled' => 'true',
                                                                       'social_share_lightbox' => 'assets/gui/images/SocialShareLightbox2.png',
                                                                       'tooltip_bg_color' => 'ffffff',
                                                                       'tooltip_stroke_color' => '000000',
                                                                       'tooltip_text_color' => '000000',
                                                                       'label_position' => 'bottom',
                                                                       'label_text_color' => 'ffffff',
                                                                       'label_text_color_hover' => 'ffffff')));
                    
                    dbDelta($wpdb->insert(DOPWGG_Settings_table, array('name' => 'Example 3',
                                                                       'gallery_id' => 0,
                                                                       'width' => 900,
                                                                       'height' => 450,
                                                                       'bg_color' => 'ffffff',
                                                                       'bg_alpha' => 100,
                                                                       'no_lines' => '3',
                                                                       'no_columns' => '7',
                                                                       'images_order' => 'normal',
                                                                       'responsive_enabled' => 'true',
                                                                       'thumbnails_spacing' => 15,
                                                                       'thumbnails_padding_top' => 0,
                                                                       'thumbnails_padding_right' => 15,
                                                                       'thumbnails_padding_bottom' => 0,
                                                                       'thumbnails_padding_left' => 0,
                                                                       'thumbnails_navigation' => 'scroll',
                                                                       'thumbnails_scroll_scrub_color' => 'afbd21',
                                                                       'thumbnails_scroll_bar_color' => 'e0e0e0',
                                                                       'thumbnails_info' => 'none',
                                                                       'thumbnail_loader' => 'assets/gui/images/ThumbnailLoader3.gif',
                                                                       'thumbnail_width' => 100,
                                                                       'thumbnail_height' => 100,
                                                                       'thumbnail_width_mobile' => 50,
                                                                       'thumbnail_height_mobile' => 50,
                                                                       'thumbnail_alpha' => 80,
                                                                       'thumbnail_alpha_hover' => 100,
                                                                       'thumbnail_bg_color' => '9fad9f',
                                                                       'thumbnail_bg_color_hover' => 'c1d72e',
                                                                       'thumbnail_border_size' => 0,
                                                                       'thumbnail_border_color' => 'cccccc',
                                                                       'thumbnail_border_color_hover' => '000000',
                                                                       'thumbnail_padding_top' => 3,
                                                                       'thumbnail_padding_right' => 3,
                                                                       'thumbnail_padding_bottom' => 3,
                                                                       'thumbnail_padding_left' => 3,
                                                                       'lightbox_position' => 'gallery',
                                                                       'lightbox_window_color' => 'ffffff',
                                                                       'lightbox_window_alpha' => 100,
                                                                       'lightbox_loader' => 'assets/gui/images/LightboxLoader3.gif',
                                                                       'lightbox_bg_color' => 'ffffff',
                                                                       'lightbox_bg_alpha' => 100,
                                                                       'lightbox_margin_top' => 0,
                                                                       'lightbox_margin_right' => 0,
                                                                       'lightbox_margin_bottom' => 0,
                                                                       'lightbox_margin_left' => 0,
                                                                       'lightbox_padding_top' => 0,
                                                                       'lightbox_padding_right' => 0,
                                                                       'lightbox_padding_bottom' => 0,
                                                                       'lightbox_padding_left' => 0,
                                                                       'lightbox_navigation_prev' => 'assets/gui/images/LightboxPrev3.png',
                                                                       'lightbox_navigation_prev_hover' => 'assets/gui/images/LightboxPrevHover3.png',
                                                                       'lightbox_navigation_next' => 'assets/gui/images/LightboxNext3.png',
                                                                       'lightbox_navigation_next_hover' => 'assets/gui/images/LightboxNextHover3.png',
                                                                       'lightbox_navigation_close' => 'assets/gui/images/LightboxClose3.png',
                                                                       'lightbox_navigation_close_hover' => 'assets/gui/images/LightboxCloseHover3.png',
                                                                       'caption_height' => 24,
                                                                       'caption_title_color' => '222222',
                                                                       'caption_text_color' => 'dddddd',
                                                                       'caption_scroll_scrub_color' => '777777',
                                                                       'caption_scroll_bg_color' => 'e0e0e0',
                                                                       'social_share_enabled' => 'true',
                                                                       'social_share_lightbox' => 'assets/gui/images/SocialShareLightbox3.png',
                                                                       'tooltip_bg_color' => 'ffffff',
                                                                       'tooltip_stroke_color' => '000000',
                                                                       'tooltip_text_color' => '000000',
                                                                       'label_position' => 'bottom',
                                                                       'label_text_color' => '000000',
                                                                       'label_text_color_hover' => 'ffffff')));
                }
            }

            function initUploadFolders(){
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/settings');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/settings/lightbox-loader');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/settings/lightbox-navigation-close');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/settings/lightbox-navigation-close-hover');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/settings/lightbox-navigation-next');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/settings/lightbox-navigation-next-hover');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/settings/lightbox-navigation-prev');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/settings/lightbox-navigation-prev-hover');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/settings/social-share-lightbox');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/settings/thumb-loader');
                $this->verifyUploadFolder('../wp-content/plugins/dopwgg/uploads/thumbs');
            }
            
            function verifyUploadFolder($folder){
                if (!file_exists($folder)){
                    mkdir($folder, 0777);                
                }
                else{
                    if (substr(decoct(fileperms($folder)), 1) != '0777'){
                        if (@chmod($folder, 0777)){
                            // File permissions changed.
                        }
                        else{
                            // File permissions didn't changed.
                        }
                    }
                }
            }
            
            function printAdminPage(){// Prints out the admin page.
                $this->DOPWGG_AddEditGalleries->galleriesList();
            }

// Galleries            
            function showGalleries(){// Show Galleries List.
                global $wpdb;
                
                $galleriesHTML = array();
                array_push($galleriesHTML, '<ul>');

                $galleries = $wpdb->get_results('SELECT * FROM '.DOPWGG_Galleries_table.' ORDER BY id DESC');
                
                if ($wpdb->num_rows != 0){
                    foreach ($galleries as $gallery) {
                        array_push($galleriesHTML, '<li class="item" id="DOPWGG-ID-'.$gallery->id.'"><span class="id">ID '.$gallery->id.':</span> <span class="name">'.$this->shortGalleryName($gallery->name, 25).'</span></li>');
                    }
                }
                else{
                    array_push($galleriesHTML, '<li class="no-data">'.DOPWGG_NO_GALLERIES.'</li>');
                }
                array_push($galleriesHTML, '</ul>');
                
                echo implode('', $galleriesHTML);
                
            	die();                
            }
        
            function addGallery(){// Add Gallery.
                global $wpdb;

                $wpdb->insert(DOPWGG_Galleries_table, array('name' => DOPWGG_ADD_GALLERY_NAME));
                $wpdb->insert(DOPWGG_Settings_table, array('gallery_id' => $wpdb->insert_id));
                $this->showGalleries();

            	die();
            }

            function deleteGallery(){// Delete Gallery.
                global $wpdb;

                $wpdb->query('DELETE FROM '.DOPWGG_Galleries_table.' WHERE id="'.$_POST['id'].'"');
                $wpdb->query('DELETE FROM '.DOPWGG_Settings_table.' WHERE gallery_id="'.$_POST['id'].'"');
                
                $images = $wpdb->get_results('SELECT * FROM '.DOPWGG_Images_table.' WHERE gallery_id="'.$_POST['id'].'" ORDER BY position');
                
                foreach ($images as $image) {
                    $wpdb->query('DELETE FROM '.DOPWGG_Images_table.' WHERE id="'.$image->id.'"');
                    unlink(DOPWGG_Plugin_AbsPath.'uploads/'.$image->name);
                    unlink(DOPWGG_Plugin_AbsPath.'uploads/thumbs/'.$image->name);
                }

                $galleries = $wpdb->get_results('SELECT * FROM '.DOPWGG_Galleries_table.' ORDER BY id');
                
                echo $wpdb->num_rows;

            	die();
            }            

            function shortGalleryName($name, $size){// Return a short name for the gallery.
                $new_name = '';
                $pieces = str_split($name);
               
                if (count($pieces) <= $size){
                    $new_name = $name;
                }
                else{
                    for ($i=0; $i<$size-3; $i++){
                        $new_name .= $pieces[$i];
                    }
                    $new_name .= '...';
                }

                return $new_name;
            }
            
// Settings
            function showGallerySettings(){// Show Gallery Settings.
                global $wpdb;
                $result = array();
                $predefined_settings_list = array();
                
                $predefined_settings = $wpdb->get_results('SELECT * FROM '.DOPWGG_Settings_table.' ORDER BY id');
                
                foreach ($predefined_settings as $ps){
                    array_push($predefined_settings_list, '<option value="'.$ps->id.'">'.($ps->gallery_id != 0 ? $ps->gallery_id.'. ':'').$ps->name.'</option>');
                }
                
                $result['predefined_settings'] = implode('', $predefined_settings_list);
                
                $gallery = $wpdb->get_row('SELECT * FROM '.DOPWGG_Galleries_table.' WHERE id="'.$_POST['gallery_id'].'"');
                
                if ($_POST['settings_id'] != 0){
                    $settings = $wpdb->get_row('SELECT * FROM '.DOPWGG_Settings_table.' WHERE id="'.$_POST['settings_id'].'"');
                }
                else{
                    $settings = $wpdb->get_row('SELECT * FROM '.DOPWGG_Settings_table.' WHERE gallery_id="'.$_POST['gallery_id'].'"');
                }

                if ($_POST['gallery_id'] != 0){
                    $result['name'] = $gallery->name;
                }
                else{
                    $result['name'] = $settings->name;
                }
                
                $result['a_settings_id'] = $_POST['settings_id'];
                $result['a_gallery_id'] = $_POST['gallery_id'];
                $result['data_parse_method'] = $settings->data_parse_method;
                $result['width'] = $settings->width;
                $result['height'] = $settings->height;
                $result['bg_color'] = $settings->bg_color;
                $result['bg_alpha'] = $settings->bg_alpha;
                $result['no_lines'] = $settings->no_lines;
                $result['no_columns'] = $settings->no_columns; 
                $result['images_order'] = $settings->images_order;     
                $result['responsive_enabled'] = $settings->responsive_enabled;                          
                $result['thumbnails_spacing'] = $settings->thumbnails_spacing;
                $result['thumbnails_padding_top'] = $settings->thumbnails_padding_top;
                $result['thumbnails_padding_right'] = $settings->thumbnails_padding_right;
                $result['thumbnails_padding_bottom'] = $settings->thumbnails_padding_bottom;
                $result['thumbnails_padding_left'] = $settings->thumbnails_padding_left;
                $result['thumbnails_navigation'] = $settings->thumbnails_navigation;
                $result['thumbnails_scroll_scrub_color'] = $settings->thumbnails_scroll_scrub_color;
                $result['thumbnails_scroll_bar_color'] = $settings->thumbnails_scroll_bar_color;
                $result['thumbnails_info'] = $settings->thumbnails_info;            
                $result['thumbnail_loader'] = $settings->thumbnail_loader;
                $result['thumbnail_width'] = $settings->thumbnail_width;
                $result['thumbnail_height'] = $settings->thumbnail_height;
                $result['thumbnail_width_mobile'] = $settings->thumbnail_width_mobile;
                $result['thumbnail_height_mobile'] = $settings->thumbnail_height_mobile;
                $result['thumbnail_alpha'] = $settings->thumbnail_alpha;
                $result['thumbnail_alpha_hover'] = $settings->thumbnail_alpha_hover;
                $result['thumbnail_bg_color'] = $settings->thumbnail_bg_color;
                $result['thumbnail_bg_color_hover'] = $settings->thumbnail_bg_color_hover;
                $result['thumbnail_border_size'] = $settings->thumbnail_border_size;
                $result['thumbnail_border_color'] = $settings->thumbnail_border_color;
                $result['thumbnail_border_color_hover'] = $settings->thumbnail_border_color_hover;
                $result['thumbnail_padding_top'] = $settings->thumbnail_padding_top;
                $result['thumbnail_padding_right'] = $settings->thumbnail_padding_right;
                $result['thumbnail_padding_bottom'] = $settings->thumbnail_padding_bottom;
                $result['thumbnail_padding_left'] = $settings->thumbnail_padding_left;
                $result['lightbox_position'] = $settings->lightbox_position;
                $result['lightbox_window_color'] = $settings->lightbox_window_color;
                $result['lightbox_window_alpha'] = $settings->lightbox_window_alpha;
                $result['lightbox_loader'] = $settings->lightbox_loader;
                $result['lightbox_bg_color'] = $settings->lightbox_bg_color;
                $result['lightbox_bg_alpha'] = $settings->lightbox_bg_alpha;
                $result['lightbox_margin_top'] = $settings->lightbox_margin_top;
                $result['lightbox_margin_right'] = $settings->lightbox_margin_right;
                $result['lightbox_margin_bottom'] = $settings->lightbox_margin_bottom;
                $result['lightbox_margin_left'] = $settings->lightbox_margin_left;
                $result['lightbox_padding_top'] = $settings->lightbox_padding_top;
                $result['lightbox_padding_right'] = $settings->lightbox_padding_right;
                $result['lightbox_padding_bottom'] = $settings->lightbox_padding_bottom;
                $result['lightbox_padding_left'] = $settings->lightbox_padding_left;
                $result['lightbox_navigation_prev'] = $settings->lightbox_navigation_prev;
                $result['lightbox_navigation_prev_hover'] = $settings->lightbox_navigation_prev_hover;
                $result['lightbox_navigation_next'] = $settings->lightbox_navigation_next;
                $result['lightbox_navigation_next_hover'] = $settings->lightbox_navigation_next_hover;
                $result['lightbox_navigation_close'] = $settings->lightbox_navigation_close;
                $result['lightbox_navigation_close_hover'] = $settings->lightbox_navigation_close_hover;       
                $result['caption_height'] = $settings->caption_height;
                $result['caption_title_color'] = $settings->caption_title_color;
                $result['caption_text_color'] = $settings->caption_text_color;
                $result['caption_scroll_scrub_color'] = $settings->caption_scroll_scrub_color;
                $result['caption_scroll_bg_color'] = $settings->caption_scroll_bg_color;  
                $result['social_share_enabled'] = $settings->social_share_enabled;
                $result['social_share_lightbox'] = $settings->social_share_lightbox;
                $result['tooltip_bg_color'] = $settings->tooltip_bg_color;
                $result['tooltip_stroke_color'] = $settings->tooltip_stroke_color;
                $result['tooltip_text_color'] = $settings->tooltip_text_color;      
                $result['label_position'] = $settings->label_position;      
                $result['label_text_color'] = $settings->label_text_color;     
                $result['label_text_color_hover'] = $settings->label_text_color_hover;

                echo json_encode($result);
            	die();
            }

            function editGallerySettings(){// Edit Gallery Settings.
                global $wpdb;
                
                $settings = array('name' => $_POST['name'],
                                  'data_parse_method' => $_POST['data_parse_method'],
                                  'width' => $_POST['width'],
                                  'height' => $_POST['height'],
                                  'bg_color' => $_POST['bg_color'],
                                  'bg_alpha' => $_POST['bg_alpha'],
                                  'no_lines' => $_POST['no_lines'],
                                  'no_columns' => $_POST['no_columns'],
                                  'images_order' => $_POST['images_order'],
                                  'responsive_enabled' => $_POST['responsive_enabled'],
                                  'thumbnails_spacing' => $_POST['thumbnails_spacing'],
                                  'thumbnails_padding_top' => $_POST['thumbnails_padding_top'],
                                  'thumbnails_padding_right' => $_POST['thumbnails_padding_right'],
                                  'thumbnails_padding_bottom' => $_POST['thumbnails_padding_bottom'],
                                  'thumbnails_padding_left' => $_POST['thumbnails_padding_left'],
                                  'thumbnails_navigation' => $_POST['thumbnails_navigation'],
                                  'thumbnails_scroll_scrub_color' => $_POST['thumbnails_scroll_scrub_color'],
                                  'thumbnails_scroll_bar_color' => $_POST['thumbnails_scroll_bar_color'],
                                  'thumbnails_info' => $_POST['thumbnails_info'],
                                  'thumbnail_width' => $_POST['thumbnail_width'],
                                  'thumbnail_height' => $_POST['thumbnail_height'],
                                  'thumbnail_width_mobile' => $_POST['thumbnail_width_mobile'],
                                  'thumbnail_height_mobile' => $_POST['thumbnail_height_mobile'],
                                  'thumbnail_alpha' => $_POST['thumbnail_alpha'],
                                  'thumbnail_alpha_hover' => $_POST['thumbnail_alpha_hover'],
                                  'thumbnail_bg_color' => $_POST['thumbnail_bg_color'],
                                  'thumbnail_bg_color_hover' => $_POST['thumbnail_bg_color_hover'],
                                  'thumbnail_border_size' => $_POST['thumbnail_border_size'],
                                  'thumbnail_border_color' => $_POST['thumbnail_border_color'],
                                  'thumbnail_border_color_hover' => $_POST['thumbnail_border_color_hover'],
                                  'thumbnail_padding_top' => $_POST['thumbnail_padding_top'],
                                  'thumbnail_padding_right' => $_POST['thumbnail_padding_right'],
                                  'thumbnail_padding_bottom' => $_POST['thumbnail_padding_bottom'],
                                  'thumbnail_padding_left' => $_POST['thumbnail_padding_left'],
                                  'lightbox_position' => $_POST['lightbox_position'],
                                  'lightbox_window_color' => $_POST['lightbox_window_color'],
                                  'lightbox_window_alpha' => $_POST['lightbox_window_alpha'],
                                  'lightbox_bg_color' => $_POST['lightbox_bg_color'],
                                  'lightbox_bg_alpha' => $_POST['lightbox_bg_alpha'],
                                  'lightbox_margin_top' => $_POST['lightbox_margin_top'],
                                  'lightbox_margin_right' => $_POST['lightbox_margin_right'],
                                  'lightbox_margin_bottom' => $_POST['lightbox_margin_bottom'],
                                  'lightbox_margin_left' => $_POST['lightbox_margin_left'],
                                  'lightbox_padding_top' => $_POST['lightbox_padding_top'],
                                  'lightbox_padding_right' => $_POST['lightbox_padding_right'],
                                  'lightbox_padding_bottom' => $_POST['lightbox_padding_bottom'],
                                  'lightbox_padding_left' => $_POST['lightbox_padding_left'],
                                  'caption_height' => $_POST['caption_height'],
                                  'caption_title_color' => $_POST['caption_title_color'],
                                  'caption_text_color' => $_POST['caption_text_color'],
                                  'caption_scroll_scrub_color' => $_POST['caption_scroll_scrub_color'],
                                  'caption_scroll_bg_color' => $_POST['caption_scroll_bg_color'],
                                  'social_share_enabled' => $_POST['social_share_enabled'], 
                                  'tooltip_bg_color' => $_POST['tooltip_bg_color'],
                                  'tooltip_stroke_color' => $_POST['tooltip_stroke_color'],
                                  'tooltip_text_color' => $_POST['tooltip_text_color'],
                                  'label_position' => $_POST['label_position'],
                                  'label_text_color' => $_POST['label_text_color'],
                                  'label_text_color_hover' => $_POST['label_text_color_hover']);     
                
                if (isset($_POST['thumbnail_loader'])){
                    $settings['thumbnail_loader'] = $_POST['thumbnail_loader'];
                    $settings['lightbox_loader'] = $_POST['lightbox_loader'];                    
                    $settings['lightbox_navigation_prev'] = $_POST['lightbox_navigation_prev'];
                    $settings['lightbox_navigation_prev_hover'] = $_POST['lightbox_navigation_prev_hover'];
                    $settings['lightbox_navigation_next'] = $_POST['lightbox_navigation_next'];
                    $settings['lightbox_navigation_next_hover'] = $_POST['lightbox_navigation_next_hover'];
                    $settings['lightbox_navigation_close'] = $_POST['lightbox_navigation_close'];
                    $settings['lightbox_navigation_close_hover'] = $_POST['lightbox_navigation_close_hover'];
                    $settings['social_share_lightbox'] = $_POST['social_share_lightbox'];
                }
                
                $wpdb->update(DOPWGG_Galleries_table, array('name' => $_POST['name']), array(id => $_POST['gallery_id']));
                
                if ($_POST['gallery_id'] == 0){
                    $wpdb->update(DOPWGG_Settings_table, $settings, array(id => 1));
                }
                else{
                    $wpdb->update(DOPWGG_Settings_table, $settings, array(gallery_id => $_POST['gallery_id']));
                }
                
                echo '';
                
            	die();
            }
            
            function updateSettingsImage(){// Update Settings Images via AJAX.
                if (isset($_POST['gallery_id'])){
                    global $wpdb;
                    
                    switch ($_POST['item']){
                        case 'thumbnail_loader':
                            $wpdb->update(DOPWGG_Settings_table, array('thumbnail_loader' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_loader':
                            $wpdb->update(DOPWGG_Settings_table, array('lightbox_loader' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_prev':
                            $wpdb->update(DOPWGG_Settings_table, array('lightbox_navigation_prev' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_prev_hover':
                            $wpdb->update(DOPWGG_Settings_table, array('lightbox_navigation_prev_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_next':
                            $wpdb->update(DOPWGG_Settings_table, array('lightbox_navigation_next' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_next_hover':
                            $wpdb->update(DOPWGG_Settings_table, array('lightbox_navigation_next_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_close':
                            $wpdb->update(DOPWGG_Settings_table, array('lightbox_navigation_close' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'lightbox_navigation_close_hover':
                            $wpdb->update(DOPWGG_Settings_table, array('lightbox_navigation_close_hover' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break;
                        case 'social_share_lightbox':
                            $wpdb->update(DOPWGG_Settings_table, array('social_share_lightbox' => $_POST['path']), array(gallery_id => $_POST['gallery_id']));
                            break; 
                    }
                    
                    echo '';
                }
            }

// Images            
            function showImages(){// Show Images List.
                if (isset($_POST['gallery_id'])){
                    global $wpdb;
                    $imagesHTML = array();
                    array_push($imagesHTML, '<ul>');

                    $images = $wpdb->get_results('SELECT * FROM '.DOPWGG_Images_table.' WHERE gallery_id="'.$_POST['gallery_id'].'" ORDER BY position');
                    
                    if ($wpdb->num_rows != 0){
                        foreach ($images as $image) {
                            if ($image->enabled == 'true'){
                                array_push($imagesHTML, '<li class="item-image" id="DOPWGG-image-ID-'.$image->id.'"><img src="'.DOPWGG_Plugin_URL.'uploads/thumbs/'.$image->name.'" alt="" /></li>');
                            }
                            else{
                                array_push($imagesHTML, '<li class="item-image item-image-disabled" id="DOPWGG-image-ID-'.$image->id.'"><img src="'.DOPWGG_Plugin_URL.'uploads/thumbs/'.$image->name.'" alt="" /></li>');
                            }
                        }
                    }
                    else{
                        array_push($imagesHTML, '<li class="no-data">'.DOPWGG_NO_IMAGES.'</li>');
                    }

                    array_push($imagesHTML, '</ul>');
                    
                    echo implode('', $imagesHTML);

                    die();
                }
            }

            function addImageWP(){// Add Images from WP Media.
                global $wpdb;
                
                $urlPieces = explode('wp-content/', $_POST['image_url']);
                $imagePieces = explode('/', $urlPieces[1]);
                
                $targetPath = DOPWGG_Plugin_AbsPath.'uploads';
                $ext = substr($imagePieces[count($imagePieces)-1], strrpos($imagePieces[count($imagePieces)-1], '.') + 1);

                $newName = $this->generateName();
                
                // File and new size
                $filename = str_replace('//','/',$targetPath).'/'.$newName.'.'.$ext;
                copy(ABSPATH.'wp-content/'.$urlPieces[1], $filename);
                
                // CREATE THUMBNAIL
               
                // Get new sizes
                list($width, $height) = getimagesize($filename);
                $newheight = 300;
                $newwidth = $width*$newheight/$height;

                if ($newwidth < 300){
                    $newwidth = 300;
                    $newheight = $height*$newwidth/$width;
                }

                // Load
                $thumb = ImageCreateTrueColor($newwidth, $newheight);
                
                if ($ext == 'png'){
                    imagealphablending($thumb, false);
                    imagesavealpha($thumb, true);  
                }
                
                if ($ext == 'png'){
                    $source = imagecreatefrompng($filename);
                    imagealphablending($source, true);
                }
                else{
                    $source = imagecreatefromjpeg($filename);
                }

                // Resize
                imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                // Output
                if ($ext == 'png'){
                    $source = imagepng($thumb, $targetPath.'/thumbs/'.$newName.'.'.$ext);
                }
                else{
                    $source = imagejpeg($thumb, $targetPath.'/thumbs/'.$newName.'.'.$ext, 100);
                }
                
                $images = $wpdb->get_results('SELECT * FROM '.DOPWGG_Images_table.' WHERE gallery_id="'.$_POST['gallery_id'].'" ORDER BY position');
                $wpdb->insert(DOPWGG_Images_table, array('gallery_id' => $_POST['gallery_id'],
                                                        'name' => $newName.'.'.$ext,
                                                        'position' => $wpdb->num_rows+1));
                
                echo $wpdb->insert_id.';;;'.$newName.'.'.$ext;
                
            	die();
            }
            
            function addImageFTP(){// Add Images from FTP.
                global $wpdb;
                
                $folder = DOPWGG_Plugin_AbsPath.'ftp-uploads';
                $images = array();
                $folderData = opendir($folder);
   
                while (($file = readdir($folderData)) !== false){
                    if ($file != '.' && $file != '..'){
                        array_push($images, "$file");
                    }
                }
                
                closedir($folderData);

                $result = array();
                $targetPath = DOPWGG_Plugin_AbsPath.'uploads';
                sort($images);
                
                foreach ($images as $image):
                    $ext = substr($image, strrpos($image, '.')+1);
                    $newName = $this->generateName();

                    // File and new size
                    $filename = str_replace('//','/',$targetPath).'/'.$newName.'.'.$ext;

                    // Get new sizes
                    copy(DOPWGG_Plugin_AbsPath.'ftp-uploads/'.$image, $filename);

                    // CREATE THUMBNAIL

                    // Get new sizes
                    list($width, $height) = getimagesize($filename);
                    $newheight = 300;
                    $newwidth = $width*$newheight/$height;

                    if ($newwidth < 300){
                        $newwidth = 300;
                        $newheight = $height*$newwidth/$width;
                    }

                    // Load
                    $thumb = ImageCreateTrueColor($newwidth, $newheight);
                    
                    if ($ext == 'png'){
                        imagealphablending($thumb, false);
                        imagesavealpha($thumb, true);  
                    }
                    
                    if ($ext == 'png'){
                        $source = imagecreatefrompng($filename);
                        imagealphablending($source, true);
                    }
                    else{
                        $source = imagecreatefromjpeg($filename);
                    }

                    // Resize
                    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                    // Output
                    if ($ext == 'png'){
                        $source = imagepng($thumb, $targetPath.'/thumbs/'.$newName.'.'.$ext);
                    }
                    else{
                        $source = imagejpeg($thumb, $targetPath.'/thumbs/'.$newName.'.'.$ext, 100);
                    }

                    $images = $wpdb->get_results('SELECT * FROM '.DOPWGG_Images_table.' WHERE gallery_id="'.$_POST['gallery_id'].'" ORDER BY position');
                    $wpdb->insert(DOPWGG_Images_table, array('gallery_id' => $_POST['gallery_id'],
                                                             'name' => $newName.'.'.$ext,
                                                             'position' => $wpdb->num_rows+1));

                    array_push($result, $wpdb->insert_id.';;;'.$newName.'.'.$ext);
                endforeach;
                
                echo implode(';;;;;', $result);
                
            	die();
            }
            
            function addImage(){// Add Image via AJAX.
                global $wpdb;                
                
                $images = $wpdb->get_results('SELECT * FROM '.DOPWGG_Images_table.' WHERE gallery_id="'.$_POST['gallery_id'].'" ORDER BY position');
                $wpdb->insert(DOPWGG_Images_table, array('gallery_id' => $_POST['gallery_id'],
                                                        'name' => $_POST['name'],
                                                        'position' => $wpdb->num_rows+1));
                echo $wpdb->insert_id;
                
            	die();
            }

            function sortImages(){// Sort Images via AJAX.
                global $wpdb;

                $order = array();
                $order = explode(',', $_POST['data']);

                for ($i=0; $i<count($order)-1; $i++){
                    $newPos = $i+1;
                    $wpdb->update(DOPWGG_Images_table, array('position' => $newPos), array(id => $order[$i]));
                }

                echo $_POST['data'];

            	die();
            }

            function showImage(){// Show Image details.
                global $wpdb;
                $result = array();

                $image = $wpdb->get_row('SELECT * FROM '.DOPWGG_Images_table.' WHERE id="'.$_POST['image_id'].'"');
                $settings = $wpdb->get_row('SELECT * FROM '.DOPWGG_Settings_table.' WHERE gallery_id="'.$image->gallery_id.'"');
                
                $result['id'] = $image->id;
                $result['name'] = $image->name;
                $result['thumbnail_width'] = $settings->thumbnail_width;
                $result['thumbnail_height'] = $settings->thumbnail_height;
                $result['title'] = stripslashes($image->title);
                $result['caption'] = preg_replace("/<br>/", "\n", stripslashes($image->caption));
                $result['media'] = stripslashes($image->media);
                $result['link'] = $image->link;
                $result['target'] = $image->target;
                $result['enabled'] = $image->enabled;

                echo json_encode($result);
            	die();
            }

            function editImage(){// Edit Image.
                global $wpdb;

                $wpdb->update(DOPWGG_Images_table, array('title' => $_POST['image_title'], 'caption' => preg_replace('`[\r\n]`', "<br>", $_POST['image_caption']), 'media' => $_POST['image_media'], 'link' => $_POST['image_link'], 'target' => $_POST['image_link_target'], 'enabled' => $_POST['image_enabled']), array('id' => $_POST['image_id']));

                if ($_POST['crop_width'] > 0){
                    list($width, $height) = getimagesize(DOPWGG_Plugin_AbsPath.'uploads/'.$_POST['image_name']);
                    $pr = $width/$_POST['image_width'];
                    $ext = substr($_POST['image_name'], strrpos($_POST['image_name'], '.') + 1);

                    $src = DOPWGG_Plugin_AbsPath.'uploads/'.$_POST['image_name'];

                    if ($ext == 'png'){
                        $img_r = imagecreatefrompng($src);
                        imagealphablending($img_r, true);
                    }
                    else{
                        $img_r = imagecreatefromjpeg($src);
                    }

                    $thumb = ImageCreateTrueColor($_POST['thumb_width'], $_POST['thumb_height']);
                    
                    if ($ext == 'png'){
                        imagealphablending($thumb, false);
                        imagesavealpha($thumb, true);  
                    }

                    imagecopyresampled($thumb, $img_r , 0, 0, $_POST['crop_x']*$pr, $_POST['crop_y']*$pr, $_POST['thumb_width'], $_POST['thumb_height'], $_POST['crop_width']*$pr, $_POST['crop_height']*$pr);

                    if ($ext == 'png'){
                        $source = imagepng($thumb, DOPWGG_Plugin_AbsPath.'uploads/thumbs/'.$_POST['image_name']);
                    }
                    else{
                        $source = imagejpeg($thumb, DOPWGG_Plugin_AbsPath.'uploads/thumbs/'.$_POST['image_name'], 100);
                    }

                    echo DOPWGG_Plugin_URL.'uploads/thumbs/'.$_POST['image_name'];
                }
                else{
                    echo '';
                }

            	die();
            }

            function deleteImage(){// Delete Image.
                global $wpdb;

                $image = $wpdb->get_row('SELECT * FROM '.DOPWGG_Images_table.' WHERE id="'.$_POST['image_id'].'"');
                $position = $image->position;

                $wpdb->query('DELETE FROM '.DOPWGG_Images_table.' WHERE id="'.$_POST['image_id'].'"');
                unlink(DOPWGG_Plugin_AbsPath.'uploads/'.$image->name);
                unlink(DOPWGG_Plugin_AbsPath.'uploads/thumbs/'.$image->name);

                $images = $wpdb->get_results('SELECT * FROM '.DOPWGG_Images_table.' WHERE gallery_id="'.$image->gallery_id.'" ORDER BY position');
                $num_rows = $wpdb->num_rows;
                
                foreach ($images as $image) {
                    if($image->position > $position){
                        $newPosition = $image->position-1;
                        $wpdb->update(DOPWGG_Images_table, array('position' => $newPosition), array(id => $image->id));
                    }
                }
                
                echo $num_rows;

            	die();
            }        
            
// Functions            
            private function generateName(){
                $len = 64;
                $base = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
                $max = strlen($base)-1;
                $newName = '';
                mt_srand((double)microtime()*1000000);
                
                while (strlen($newName)<$len+1){
                    $newName .= $base{mt_rand(0,$max)};
                }
                
                return $newName;
            }  
            
// Editor Changes
            function addDOPWGGtoTinyMCE(){// Add gallery button to TinyMCE Editor.
                add_filter('tiny_mce_version', array (&$this, 'changeTinyMCEVersion'));
                add_action('init', array (&$this, 'addDOPWGGButtons'));
            }

            function tinyMCEGalleries(){// Send data to editor button.
                global $wpdb;
                $tinyMCE_data = '';
                $galleriesList = array();

                $galleries = $wpdb->get_results('SELECT * FROM '.DOPWGG_Galleries_table.' ORDER BY id');
                
                foreach ($galleries as $gallery) {
                    array_push($galleriesList, $gallery->id.';;'.$gallery->name);
                }
                $tinyMCE_data = DOPWGG_TINYMCE_ADD.';;;;;'.implode(';;;', $galleriesList);
                
                echo '<script type="text/JavaScript">'.
                     '    var DOPWGG_tinyMCE_data = "'.$tinyMCE_data.'"'.
                     '</script>';
            }

            function addDOPWGGButtons(){// Add Button.
                if (!current_user_can('edit_posts') && !current_user_can('edit_pages')){
                    return;
                }

                if (get_user_option('rich_editing') == 'true'){
                    add_action('admin_head', array (&$this, 'tinyMCEGalleries'));
                    add_filter('mce_external_plugins', array (&$this, 'addDOPWGGTinyMCEPlugin'), 5);
                    add_filter('mce_buttons', array (&$this, 'registerDOPWGGTinyMCEPlugin'), 5);
                }
            }

            function registerDOPWGGTinyMCEPlugin($buttons){// Register editor buttons.
                array_push($buttons, '', 'DOPWGG');
                return $buttons;
            }

            function addDOPWGGTinyMCEPlugin($plugin_array){// Add plugin to TinyMCE editor.
                $plugin_array['DOPWGG'] =  DOPWGG_Plugin_URL.'assets/js/tinymce-plugin.js';
                return $plugin_array;
            }

            function changeTinyMCEVersion($version){// TinyMCE version.
                $version = $version+100;
                return $version;
            }
        }
    }
?>