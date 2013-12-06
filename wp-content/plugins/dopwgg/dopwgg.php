<?php
/*
Plugin Name: Wall/Grid Gallery (WordPress Plugin)
Version: 1.8
Plugin URI: http://codecanyon.net/item/wallgrid-gallery-wordpress-plugin/270895?ref=DOTonPAPER
Description: This Plugin will help you to easily add a grid gallery to your WordPress website or blog. The gallery is completely customizable, resizable and is compatible with all browsers and devices (iPhone, iPad and Android smartphones). You will be able to insert it in any page or post you want with an inbuilt short code generator.<br /><br />If you like this plugin, feel free to rate it five stars at <a href="http://codecanyon.net/item/wallgrid-gallery-wordpress-plugin/270895?ref=DOTonPAPER" target="_blank">CodeCanyon</a> in your downloads section. If you encounter any problems please do not give a low rating but <a href="mailto:support@dotonpaper.zendesk.com">contact us</a> first so we can help you.
Author: Dot on Paper
Author URI: http://www.dotonpaper.net

Change log:

        1.8 (2012-03-25)

                * Change caption colors bug fix.
                * Database is deleted when you delete the plugin.
                * Display a list with default settings & all settings you created.
                * Gallery resize on hidden elements bug fix.
                * Setting number of columns bug fix.
                * Slow admin bug fix.
                * Small Admin display changes.
                * Update notification added.
                * Uploading Settings Images on MU bug fix.

        1.7 (2013-01-13)
                
                * Lightbox display bug on Chrome is fixed. 
                * Remove lightbox margins on mobile devices.
                * Set thumbnails size when gallery is responsive on mobile devices.
                
        1.6 (2012-12-05)

                * Small bugs fixes.

        1.5 (2012-10-11)

                * Data can be parsed in the gallery using HTML.
                * Small bugs fixes.
                * Upload methods script changes.

        1.4 (2012-06-30)

                * AddThis Social Share added.
                * Small bugs fixes.

        1.3 (2012-05-01)

                * Minor bugs fixes.
                * Responsive layout added. 
 
        1.2 (2012-02-05)

                * Admin sprite updated.
                * Can display thumbnail's info in a tooltip or label.
                * Caption added.
                * Change tables prefix fixed.
                * FTP upload added.
                * Initial thumbnails have better quality.
                * Install plugin by uploading fixed.
                * Integrate AJAX+JSON in Back End section.
                * Integrate AJAX+JSON in Front End gallery.
                * Jcrop updated.
                * Navigation buttons are now images.
                * You can add Youtube & Vimeo videos, HTML, Flash ...
                * You can disable/enable images/media.
                * Settings Edit fixes.
                * Simple AJAX file upload added.
                * Transition between lightbox items changed to fade.
                * Use WordPress native file upload system.
                * Use WordPress native jQuery.

	1.1 (2011-06-27)

		* Compatibility bug fixed.
		* Sorting bug fixed.
	
	1.0 (2011-06-05)
	
		* Initial release.
		
Installation: Upload the folder dopwgg from the zip file to "wp-content/plugins/" and activate the plugin in your admin panel or upload dopwgg.zip in the "Add new" section.
*/
    include_once "views/lang.php";
    include_once "views/templates.php";
    include_once "dopwgg-update.php";
    include_once "dopwgg-frontend.php";
    include_once "dopwgg-backend.php";
    include_once "dopwgg-widget.php";

    if (is_admin()){// If admin is loged in admin init administration panel.
        if (class_exists("DOPWallGridGalleryBackEnd")){
            $DOPWGG_pluginSeries = new DOPWallGridGalleryBackEnd();
        }

        if (!function_exists("DOPWallGridGalleryBackEnd_ap")){// Initialize the admin panel.
            function DOPWallGridGalleryBackEnd_ap(){
                global $DOPWGG_pluginSeries;

                if (!isset($DOPWGG_pluginSeries)){
                    return;
                }
                
                if (function_exists('add_options_page')){
                    add_menu_page(DOPWGG_TITLE, DOPWGG_TITLE, 'edit_posts', 'dopwgg', array(&$DOPWGG_pluginSeries, 'printAdminPage'), plugins_url('assets/gui/images/dop-icon.png', __FILE__));
                }
                
                register_uninstall_hook(__FILE__, array(&$DOPWGG_pluginSeries, 'uninstall'));
            }
        }

        if (isset($DOPWGG_pluginSeries)){// Init AJAX functions.
            add_action('admin_menu', 'DOPWallGridGalleryBackEnd_ap');
            add_action('wp_ajax_dopwgg_show_galleries', array(&$DOPWGG_pluginSeries, 'showGalleries'));
            add_action('wp_ajax_dopwgg_add_gallery', array(&$DOPWGG_pluginSeries, 'addGallery'));
            add_action('wp_ajax_dopwgg_delete_gallery', array(&$DOPWGG_pluginSeries, 'deleteGallery'));
            add_action('wp_ajax_dopwgg_show_gallery_settings', array(&$DOPWGG_pluginSeries, 'showGallerySettings'));
            add_action('wp_ajax_dopwgg_edit_gallery_settings', array(&$DOPWGG_pluginSeries, 'editGallerySettings'));
            add_action('wp_ajax_dopwgg_update_settings_image', array(&$DOPWGG_pluginSeries, 'updateSettingsImage'));
            add_action('wp_ajax_dopwgg_show_images', array(&$DOPWGG_pluginSeries, 'showImages'));
            add_action('wp_ajax_dopwgg_add_image_wp', array(&$DOPWGG_pluginSeries, 'addImageWP'));
            add_action('wp_ajax_dopwgg_add_image_ftp', array(&$DOPWGG_pluginSeries, 'addImageFTP'));
            add_action('wp_ajax_dopwgg_add_image', array(&$DOPWGG_pluginSeries, 'addImage'));
            add_action('wp_ajax_dopwgg_sort_images', array(&$DOPWGG_pluginSeries, 'sortImages'));
            add_action('wp_ajax_dopwgg_show_image', array(&$DOPWGG_pluginSeries, 'showImage'));
            add_action('wp_ajax_dopwgg_edit_image', array(&$DOPWGG_pluginSeries, 'editImage'));
            add_action('wp_ajax_dopwgg_delete_image', array(&$DOPWGG_pluginSeries, 'deleteImage'));
        
        }
    }
    else{// If you view the WordPress website init the gallery.
        if (class_exists("DOPWallGridGalleryFrontEnd")){
            $DOPWGG_pluginSeries = new DOPWallGridGalleryFrontEnd();
        }

        if (isset($DOPWGG_pluginSeries)){// Init AJAX functions.
            add_action('wp_ajax_dopwgg_get_gallery_data', array(&$DOPWGG_pluginSeries, 'getGalleryData'));
        }
    }
                
    add_action('widgets_init', create_function('', 'return register_widget("DOPWallGridGalleryWidget");'));

// Uninstall

    if (!function_exists("DOPWallGridGalleryUninstall")){
        function DOPWallGridGalleryUninstall() {
            global $wpdb;

            $tables = $wpdb->get_results('SHOW TABLES');

            foreach ($tables as $table){
                $table_name = $table->Tables_in_studios_wp;

                if (strrpos($table_name, 'dopwgg_settings') !== false ||
                    strrpos($table_name, 'dopwgg_galleries') !== false ||
                    strrpos($table_name, 'dopwgg_images') !== false){
                    $wpdb->query("DROP TABLE IF EXISTS $table_name");
                }
            }
            
            delete_option('DOPWGG_db_version');
        }
        
        register_uninstall_hook(__FILE__, 'DOPWallGridGalleryUninstall');
    }
    
?>