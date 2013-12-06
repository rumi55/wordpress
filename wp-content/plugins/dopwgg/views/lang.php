<?php

/*
* Title                   : Wall/Grid Gallery (WordPress Plugin)
* Version                 : 1.8
* File                    : lang.php
* File Version            : 1.7
* Created / Last Modified : 25 March 2013
* Author                  : Dot on Paper
* Copyright               : © 2011 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Wall/Grid Gallery Translation.
*/

    define('DOPWGG_TITLE', "Wall/Grid Gallery");

    // Loading ...
    define('DOPWGG_LOAD', "Load data ...");
    define('DOPWGG_GALLERIES_LOADED', "Galleries list loaded.");
    define('DOPWGG_IMAGES_LOADED', "Images list loaded.");
    define('DOPWGG_NO_GALLERIES', "No galleries.");
    define('DOPWGG_NO_IMAGES', "No images.");
    define('DOPWGG_GALLERY_LOADED', "Gallery data loaded.");
    define('DOPWGG_IMAGE_LOADED', "Image loaded.");

    // Save ...
    define('DOPWGG_SAVE', "Save data ...");
    define('DOPWGG_SELECT_FILE', "Select File");

    // Help
    define('DOPWGG_GALLERIES_HELP', "Click on the 'plus' icon to add a gallery. Click on a gallery item to open the editing area. Click on the 'pencil' icon to edit galleries default settings.");
    define('DOPWGG_GALLERIES_EDIT_INFO_HELP', "Click 'Submit Button' to save changes.");
    define('DOPWGG_GALLERY_EDIT_HELP', "Click on the 'plus' icon to add images. Click on an image to open the editing area. You can drag images to sort them. Click on the 'pencil' icon to edit gallery settings.");
    define('DOPWGG_GALLERY_EDIT_INFO_HELP', "Click 'Submit Button' to save changes. Images are saved automaticaly. Click 'Delete Button' to delete the gallery. Click 'Use Settings' to use the predefined settings; the current settings will be deleted.");
    define('DOPWGG_ADD_IMAGES_HELP', "You have 4 upload types (WordPress, AJAX, Uploadify, FTP). At least one should work.");
    define('DOPWGG_ADD_IMAGES_HELP_WP', "You can use the default WordPress Uploader. To add an image to the gallery select it from WordPress and press Insert into Post.");
    define('DOPWGG_ADD_IMAGES_HELP_AJAX', "Just a simple AJAX upload. Just select an image and the upload will start automatically.");
    define('DOPWGG_ADD_IMAGES_HELP_UPLOADIFY', "You can use this option if you want to upload a single or multiple images to your gallery. Just select the images and the upload will start automatically. Uploadify will not display the progress bar and image processing will go slower if you have a firewall enabled.");
    define('DOPWGG_ADD_IMAGES_HELP_FTP', "Copy all the images in ftp-uploads in Thumbnail Gallery plugin folder. Press Add Images to add the content of the folder to your gallery. This will take some time depending on the number and size of the images. On some servers the images' names that contain other characters different from alphanumeric ones will not be uploaded. Change the names for them to work.");
    define('DOPWGG_IMAGE_EDIT_HELP', "Drag the mouse over the big image to select a new thumbnail. Click 'Submit Button' to save the thumbnail, title, caption, media, lightbox media or enable/disable the image. Click 'Delete Button' to delete the image.");

    // Form
    define('DOPWGG_SUBMIT', "Submit");
    define('DOPWGG_DELETE', "Delete");
    define('DOPWGG_DEFAULT', "Use Settings");

    // Add Gallery
    define('DOPWGG_ADD_GALLERY_NAME', "New Gallery");
    define('DOPWGG_ADD_GALLERY_SUBMIT', "Add Gallery");
    define('DOPWGG_ADD_GALLERY_SUBMITED', "Adding gallery ...");
    define('DOPWGG_ADD_GALERRY_SUCCESS', "You have succesfully added a new gallery.");

    // Edit Galleries
    define('DOPWGG_EDIT_GALLERIES_SUBMIT', "Edit Galleries Default Settings");
    define('DOPWGG_EDIT_GALLERIES_SUCCESS', "You have succesfully edited the default settings.");

    // Edit Gallery
    define('DOPWGG_EDIT_GALLERY_SUBMIT', "Edit Gallery");
    define('DOPWGG_EDIT_GALLERY_SUCCESS', "You have succesfully edited the gallery.");
    define('DOPWGG_EDIT_GALLERY_USE_DEFAULT_CONFIRMATION', "Are you sure you want to use this predefined settings. Current settings are going to be deleted?");

    // Delete Gallery
    define('DOPWGG_DELETE_GALLERY_CONFIRMATION', "Are you sure you want to delete this gallery?");
    define('DOPWGG_DELETE_GALLERY_SUBMIT', "Delete Gallery");
    define('DOPWGG_DELETE_GALLERY_SUBMITED', "Deleting gallery ...");
    define('DOPWGG_DELETE_GALERRY_SUCCESS', "You have succesfully deleted the gallery.");

    // Add Image
    define('DOPWGG_ADD_IMAGE_SUBMIT', "Add Images");
    define('DOPWGG_ADD_IMAGE_WP_UPLOAD', "Default WordPress file upload");
    define('DOPWGG_ADD_IMAGE_SIMPLE_UPLOAD', "Simple AJAX file upload");
    define('DOPWGG_ADD_IMAGE_MULTIPLE_UPLOAD', "Multiple files upload (Uploadify jQuery Plugin)");
    define('DOPWGG_ADD_IMAGE_FTP_UPLOAD', "FTP file upload");
    define('DOPWGG_ADD_IMAGE_SUBMITED', "Adding images ...");
    define('DOPWGG_ADD_IMAGE_SUCCESS', "You have succesfully added a new image.");
    define('DOPWGG_SELECT_IMAGES', "Select Images");
    define('DOPWGG_SELECT_FTP_IMAGES', "Add Images");

    // Sort Image
    define('DOPWGG_SORT_IMAGES_SUBMITED', "Sorting images ...");
    define('DOPWGG_SORT_IMAGES_SUCCESS', "You have succesfully sorted the images.");

    // Edit Image
    define('DOPWGG_EDIT_IMAGE_SUBMIT', "Edit Image");
    define('DOPWGG_EDIT_IMAGE_SUCCESS', "You have succesfully edited the image.");
    define('DOPWGG_EDIT_IMAGE_CROP_THUMBNAIL', "Crop Thumbnail");
    define('DOPWGG_EDIT_IMAGE_CURRENT_THUMBNAIL', "Current Thumbnail");
    define('DOPWGG_EDIT_IMAGE_TITLE', "Title");
    define('DOPWGG_EDIT_IMAGE_CAPTION', "Caption");
    define('DOPWGG_EDIT_IMAGE_MEDIA', "Media: Add videos (YouTube, Vimeo, ...), HTML, Flash, ...<br />IMPORTANT: Make sure that all the code is in one html tag. Iframe embedding code will work :).");
    define('DOPWGG_EDIT_IMAGE_LINK', "Link");
    define('DOPWGG_EDIT_IMAGE_LINK_TARGET', "Link Target");
    define('DOPWGG_EDIT_IMAGE_ENABLED', "Enabled");

    // Delete Image
    define('DOPWGG_DELETE_IMAGE_CONFIRMATION', "Are you sure you want to delete this image?");
    define('DOPWGG_DELETE_IMAGE_SUBMIT', "Delete Image");
    define('DOPWGG_DELETE_IMAGE_SUBMITED', "Deleting image ...");
    define('DOPWGG_DELETE_IMAGE_SUCCESS', "You have succesfully deleted the image.");

    // TinyMCE
    define('DOPWGG_TINYMCE_ADD', 'Add Wall/Grid Gallery');

    // Settings
    define('DOPWGG_DEFAULT_SETTINGS', "Default Settings");
    
    define('DOPWGG_GENERAL_STYLES_SETTINGS', "General Styles & Settings");
    define('DOPWGG_GALLERY_NAME', "Name");
    define('DOPWGG_DATA_PARSE_METHOD', "Gallery Data Parse Method");
    define('DOPWGG_WIDTH', "Width");
    define('DOPWGG_HEIGHT', "Height");
    define('DOPWGG_BG_COLOR', "Background Color");
    define('DOPWGG_BG_ALPHA', "Background Alpha");
    define('DOPWGG_NO_LINES', "Number of Lines");
    define('DOPWGG_NO_COLUMNS', "Number of Columns");
    define('DOPWGG_IMAGES_ORDER', "Images Order");
    define('DOPWGG_RESPONSIVE_ENABLED', "Responsive Enabled");   

    define('DOPWGG_THUMBNAILS_STYLES_SETTINGS', "Thumbnails Styles & Settings");
    define('DOPWGG_THUMBNAILS_SPACING', "Thumbnails Spacing");
    define('DOPWGG_THUMBNAILS_PADDING_TOP', "Thumbnails Padding Top");
    define('DOPWGG_THUMBNAILS_PADDING_RIGHT', "Thumbnails Padding Right");
    define('DOPWGG_THUMBNAILS_PADDING_BOTTOM', "Thumbnails Padding Bottom");
    define('DOPWGG_THUMBNAILS_PADDING_LEFT', "Thumbnails Padding Left");
    define('DOPWGG_THUMBNAILS_NAVIGATION', "Thumbnails Navigation");
    define('DOPWGG_THUMBNAILS_SCROLL_SCRUB_COLOR', "Thumbnails Scroll Scrub Color");
    define('DOPWGG_THUMBNAILS_SCROLL_BAR_COLOR', "Thumbnails Scroll Bar Color");
    define('DOPWGG_THUMBNAILS_INFO', "Info Thumbnails Display");

    define('DOPWGG_THUMBNAIL_STYLES_SETTINGS', "Styles & Settings for a Thumbnail");
    define('DOPWGG_THUMBNAIL_LOADER', "Thumbnail Loader");
    define('DOPWGG_ADD_THUMBNAIL_LOADER_SUBMITED', "Adding thumbnail loader...");
    define('DOPWGG_ADD_THUMBNAIL_LOADER_SUCCESS', "Thumbnail loader added.");
    define('DOPWGG_THUMBNAIL_WIDTH', "Thumbnail Width");
    define('DOPWGG_THUMBNAIL_HEIGHT', "Thumbnail Height");
    define('DOPWGG_THUMBNAIL_WIDTH_MOBILE', "Mobile Thumbnail Width");
    define('DOPWGG_THUMBNAIL_HEIGHT_MOBILE', "Mobile Thumbnail Height");
    define('DOPWGG_THUMBNAIL_ALPHA', "Thumbnail Alpha");
    define('DOPWGG_THUMBNAIL_ALPHA_HOVER', "Thumbnail Alpha Hover");
    define('DOPWGG_THUMBNAIL_BG_COLOR', "Thumbnail Background Color");
    define('DOPWGG_THUMBNAIL_BG_COLOR_HOVER', "Thumbnail Background Color Hover");
    define('DOPWGG_THUMBNAIL_BORDER_SIZE', "Thumbnail Border Size");
    define('DOPWGG_THUMBNAIL_BORDER_COLOR', "Thumbnail Border Color");
    define('DOPWGG_THUMBNAIL_BORDER_COLOR_HOVER', "Thumbnail Border Color Hover");
    define('DOPWGG_THUMBNAIL_PADDING_TOP', "Thumbnail Padding Top");
    define('DOPWGG_THUMBNAIL_PADDING_RIGHT', "Thumbnail Padding Right");
    define('DOPWGG_THUMBNAIL_PADDING_BOTTOM', "Thumbnail Padding Bottom");
    define('DOPWGG_THUMBNAIL_PADDING_LEFT', "Thumbnail Padding Left");
    
    define('DOPWGG_LIGHTBOX_STYLES_SETTINGS', "Lightbox Styles & Settings");
    define('DOPWGG_LIGHTBOX_POSITION', "Lightbox Position");
    define('DOPWGG_LIGHTBOX_WINDOW_COLOR', "Lightbox Window Color");
    define('DOPWGG_LIGHTBOX_WINDOW_ALPHA', "Lightbox Window Alpha");
    define('DOPWGG_LIGHTBOX_LOADER', "Lightbox Loader");
    define('DOPWGG_ADD_LIGHTBOX_LOADER_SUBMITED', "Adding lightbox loader...");
    define('DOPWGG_ADD_LIGHTBOX_LOADER_SUCCESS', "Lightbox loader added.");
    define('DOPWGG_LIGHTBOX_BACKGROUND_COLOR', "Lightbox Background Color");
    define('DOPWGG_LIGHTBOX_BACKGROUND_ALPHA', "Lightbox Background Alpha");
    define('DOPWGG_LIGHTBOX_MARGIN_TOP', "Lightbox Margin Top");
    define('DOPWGG_LIGHTBOX_MARGIN_RIGHT', "Lightbox Margin Right");
    define('DOPWGG_LIGHTBOX_MARGIN_BOTTOM', "Lightbox Margin Bottom");
    define('DOPWGG_LIGHTBOX_MARGIN_LEFT', "Lightbox Margin Left");
    define('DOPWGG_LIGHTBOX_PADDING_TOP', "Lightbox Padding Top");
    define('DOPWGG_LIGHTBOX_PADDING_RIGHT', "Lightbox Padding Right");
    define('DOPWGG_LIGHTBOX_PADDING_BOTTOM', "Lightbox Padding Bottom");
    define('DOPWGG_LIGHTBOX_PADDING_LEFT', "Lightbox Padding Left");
    
    define('DOPWGG_LIGHTBOX_NAVIGATION_STYLES_SETTINGS', "Lightbox Navigation Styles & Settings");
    define('DOPWGG_LIGHTBOX_NAVIGATION_PREV', "Lightbox Navigation Previous Button Image");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_PREV_SUBMITED', "Uploading previous button image ...");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_PREV_SUCCESS', "Previous button image uploaded.");
    define('DOPWGG_LIGHTBOX_NAVIGATION_PREV_HOVER', "Lightbox Navigation Previous Button Hover Image");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_PREV_HOVER_SUBMITED', "Uploading previous button hover image ...");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_PREV_HOVER_SUCCESS', "Previous button hover image uploaded.");
    define('DOPWGG_LIGHTBOX_NAVIGATION_NEXT', "Lightbox Navigation Next Button Image");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_NEXT_SUBMITED', "Uploading next button image ...");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_NEXT_SUCCESS', "Next button image uploaded.");
    define('DOPWGG_LIGHTBOX_NAVIGATION_NEXT_HOVER', "Lightbox Navigation Next Button Hover Image");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_NEXT_HOVER_SUBMITED', "Uploading next button hover image ...");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_NEXT_HOVER_SUCCESS', "Next button hover image uploaded.");
    define('DOPWGG_LIGHTBOX_NAVIGATION_CLOSE', "Lightbox Navigation Close Button Image");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_CLOSE_SUBMITED', "Uploading close button image ...");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_CLOSE_SUCCESS', "Close button image uploaded.");
    define('DOPWGG_LIGHTBOX_NAVIGATION_CLOSE_HOVER', "Lightbox Navigation Close Button Hover Image");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_CLOSE_HOVER_SUBMITED', "Uploading close button hover image ...");
    define('DOPWGG_ADD_LIGHTBOX_NAVIGATION_CLOSE_HOVER_SUCCESS', "Close button hover image uploaded.");
    
    define('DOPWGG_CAPTION_STYLES_SETTINGS', "Image Caption Styles & Settings");
    define('DOPWGG_CAPTION_HEIGHT', "Caption Height");
    define('DOPWGG_CAPTION_TITLE_COLOR', "Caption Title Color");
    define('DOPWGG_CAPTION_TEXT_COLOR', "Caption Text Color");
    define('DOPWGG_CAPTION_SCROLL_SCRUB_COLOR', "Caption Scroll Scrub Color");
    define('DOPWGG_CAPTION_SCROLL_BG_COLOR', "Caption Scroll Background Color");
    
    define('DOPWGG_SOCIAL_SHARE_STYLES_SETTINGS', "Social Share Styles & Settings");
    define('DOPWGG_SOCIAL_SHARE_ENABLED', "Social Share Enabled");
    define('DOPWGG_SOCIAL_SHARE_LIGHTBOX', "Lightbox Social Share Button Image");
    define('DOPWGG_SOCIAL_SHARE_LIGHTBOX_SUBMITED', "Uploading lightbox social share button image ...");
    define('DOPWGG_SOCIAL_SHARE_LIGHTBOX_SUCCESS', "Lightbox social share button image uploaded.");
    
    define('DOPWGG_TOOLTIP_STYLES_SETTINGS', "Tooltip Styles & Settings");
    define('DOPWGG_TOOLTIP_BG_COLOR', "Tooltip Background Color");
    define('DOPWGG_TOOLTIP_STROKE_COLOR', "Tooltip Stroke Color");
    define('DOPWGG_TOOLTIP_TEXT_COLOR', "Tooltip Text Color");
    
    define('DOPWGG_LABEL_STYLES_SETTINGS', "Label Styles & Settings");
    define('DOPWGG_LABEL_POSITION', "Label Position");
    define('DOPWGG_LABEL_TEXT_COLOR', "Label Text Color");
    define('DOPWGG_LABEL_TEXT_COLOR_HOVER', "Label Text Hover Color");
    
    define('DOPWGG_GO_TOP', "go top");

    define('DOPWGG_GALLERY_NAME_INFO', "Change gallery name.");
    define('DOPWGG_DATA_PARSE_METHOD_INFO', "Gallery Data Parse Method (ajax, html). Default value: ajax. Set the method by which the data will be parsed to the gallery.");
    define('DOPWGG_WIDTH_INFO', "Width (value in pixels). Default value: 900. Set the width of the gallery.");
    define('DOPWGG_HEIGHT_INFO', "Height (value in pixels). Default value: 0. Set the height of the gallery. If you set the value to 0 all thumbnails are going to be displayed.");
    define('DOPWGG_BG_COLOR_INFO', "Background Color (color hex code). Default value: f1f1f1. Set gallery background color.");
    define('DOPWGG_BG_ALPHA_INFO', "Background Alpha (value from 0 to 100). Default value: 100. Set gallery background alpha.");
    define('DOPWGG_NO_LINES_INFO', "Number of Lines (auto, number). Default value: 3. Set the number of lines for the grid.");
    define('DOPWGG_NO_COLUMNS_INFO', "Number of Columns (auto, number). Default value: 4. Set the number of columns for the grid.");
    define('DOPWGG_IMAGES_ORDER_INFO', "Images Order (normal, random). Default value: normal. Set images order.");
    define('DOPWGG_RESPONSIVE_ENABLED_INFO', "Responsive Enabled (true, false). Default value: true. Enable responsive layout.");

    define('DOPWGG_THUMBNAILS_SPACING_INFO', "Thumbnails Spacing (value in pixels). Default value: 15. Set the space between thumbnails.");
    define('DOPWGG_THUMBNAILS_PADDING_TOP_INFO', "Thumbnails Padding Top (value in pixels). Default value: 0. Set the top padding for the thumbnails.");
    define('DOPWGG_THUMBNAILS_PADDING_RIGHT_INFO', "Thumbnails Padding Right (value in pixels). Default value: 0. Set the right padding for the thumbnails.");
    define('DOPWGG_THUMBNAILS_PADDING_BOTTOM_INFO', "Thumbnails Padding Bottom (value in pixels). Default value: 0. Set the bottom padding for the thumbnails.");
    define('DOPWGG_THUMBNAILS_PADDING_LEFT_INFO', "Thumbnails Padding Left (value in pixels). Default value: 0. Set the left padding for the thumbnails.");
    define('DOPWGG_THUMBNAILS_NAVIGATION_INFO', "Thumbnails Navigation (mouse, scroll). Default value: mouse. Set how you navigate through the thumbnails.");
    define('DOPWGG_THUMBNAILS_SCROLL_SCRUB_COLOR_INFO', "Thumbnails Scroll Scrub Color (color hex code). Default value: 777777. Set the scroll scrub color.");
    define('DOPWGG_THUMBNAILS_SCROLL_BAR_COLOR_INFO', "Thumbnails Scroll Bar Color (color hex code). Default value: e0e0e0. Set the scroll bar color.");
    define('DOPWGG_THUMBNAILS_INFO_INFO', "Info Thumbnails Display (none, tooltip, label). Default value: tooltip. Display a small info text on the thumbnails, a tooltip or a label on bottom.");
    
    define('DOPWGG_THUMBNAIL_LOADER_INFO', "Thumbnail Loader (path to image). Set the loader for the thumbnails.");
    define('DOPWGG_THUMBNAIL_WIDTH_INFO', "Thumbnail Width (the size in pixels). Default value: 200. Set the width of a thumbnail.");
    define('DOPWGG_THUMBNAIL_HEIGHT_INFO', "Thumbnail Height (the size in pixels). Default value: 100. Set the height of a thumbnail.");
    define('DOPWGG_THUMBNAIL_WIDTH_MOBILE_INFO', "Mobile Thumbnail Width (the size in pixels). Default value: 100. Set the width of a thumbnail on mobile devices.");
    define('DOPWGG_THUMBNAIL_HEIGHT_MOBILE_INFO', "Mobile Thumbnail Height (the size in pixels). Default value: 50. Set the height of a thumbnail on mobile devices.");
    define('DOPWGG_THUMBNAIL_ALPHA_INFO', "Thumbnail Alpha (value from 0 to 100). Default value: 80. Set the transparancy of a thumbnail.");
    define('DOPWGG_THUMBNAIL_ALPHA_HOVER_INFO', "Thumbnail Alpha Hover (value from 0 to 100). Default value: 100. Set the transparancy of a thumbnail when hover.");
    define('DOPWGG_THUMBNAIL_BG_COLOR_INFO', "Thumbnail Background Color (color hex code). Default value: cccccc. Set the color of a thumbnail's background.");
    define('DOPWGG_THUMBNAIL_BG_COLOR_HOVER_INFO', "Thumbnail Background Color Hover (color hex code). Default value: 000000. Set the color of a thumbnail's background when hover.");
    define('DOPWGG_THUMBNAIL_BORDER_SIZE_INFO', "Thumbnail Border Size (value in pixels). Default value: 0. Set the size of a thumbnail's border.");
    define('DOPWGG_THUMBNAIL_BORDER_COLOR_INFO', "Thumbnail Border Color (color hex code). Default value: cccccc. Set the color of a thumbnail's border.");
    define('DOPWGG_THUMBNAIL_BORDER_COLOR_HOVER_INFO', "Thumbnail Border Color Hover (color hex code). Default value: 000000. Set the color of a thumbnail's border when hover.");
    define('DOPWGG_THUMBNAIL_PADDING_TOP_INFO', "Thumbnail Padding Top (value in pixels). Default value: 3. Set top padding value of a thumbnail.");
    define('DOPWGG_THUMBNAIL_PADDING_RIGHT_INFO', "Thumbnail Padding Right (value in pixels). Default value: 3. Set right padding value of a thumbnail.");
    define('DOPWGG_THUMBNAIL_PADDING_BOTTOM_INFO', "Thumbnail Padding Bottom (value in pixels). Default value: 3. Set bottom padding value of a thumbnail.");
    define('DOPWGG_THUMBNAIL_PADDING_LEFT_INFO', "Thumbnail Padding Left (value in pixels). Default value: 3. Set left padding value of a thumbnail.");

    define('DOPWGG_LIGHTBOX_POSITION_INFO', "Lightbox Position (document, gallery). Default value: document. If the value is document the lightbox is displayed over the web page fitting in the browser's window, else the lightbox is displayed in the gallery's container.");
    define('DOPWGG_LIGHTBOX_WINDOW_COLOR_INFO', "Lightbox Window Color (color hex code). Default value: 000000. Set the color for the lightbox window.");
    define('DOPWGG_LIGHTBOX_WINDOW_ALPHA_INFO', "Lightbox Window Alpha (value from 0 to 100). Default value: 80. Set the transparancy for the lightbox window.");
    define('DOPWGG_LIGHTBOX_LOADER_INFO', "Lightbox Loader (path to image). Set the loader for the lightbox image.");
    define('DOPWGG_LIGHTBOX_BACKGROUND_COLOR_INFO', "Lightbox Background Color (color hex code). Default value: 000000. Set the color for the lightbox background.");
    define('DOPWGG_LIGHTBOX_BACKGROUND_ALPHA_INFO', "Lightbox Background Alpha (value from 0 to 100). Default value: 100. Set the transparancy for the lightbox background.");
    define('DOPWGG_LIGHTBOX_MARGIN_TOP_INFO', "Lightbox Margin Top (value in pixels). Default value: 70. Set top margin value for the lightbox.");
    define('DOPWGG_LIGHTBOX_MARGIN_RIGHT_INFO', "Lightbox Margin Right (value in pixels). Default value: 70. Set right margin value for the lightbox.");
    define('DOPWGG_LIGHTBOX_MARGIN_BOTTOM_INFO', "Lightbox Margin Bottom (value in pixels). Default value: 70. Set bottom margin value for the lightbox.");
    define('DOPWGG_LIGHTBOX_MARGIN_LEFT_INFO', "Lightbox Margin Left (value in pixels). Default value: 70. Set top left value for the lightbox.");
    define('DOPWGG_LIGHTBOX_PADDING_TOP_INFO', "Lightbox Padding Top (value in pixels). Default value: 10. Set top padding value for the lightbox.");
    define('DOPWGG_LIGHTBOX_PADDING_RIGHT_INFO', "Lightbox Padding Right (value in pixels). Default value: 10. Set right padding value for the lightbox.");
    define('DOPWGG_LIGHTBOX_PADDING_BOTTOM_INFO', "Lightbox Padding Bottom (value in pixels). Default value: 10. Set bottom padding value for the lightbox.");
    define('DOPWGG_LIGHTBOX_PADDING_LEFT_INFO', "Lightbox Padding Left (value in pixels). Default value: 10. Set left padding value for the lightbox.");
    
    define('DOPWGG_LIGHTBOX_NAVIGATION_PREV_INFO', "Lightbox Navigation Previous Button Image (path to image). Upload the image for lightbox navigation's previous button.");
    define('DOPWGG_LIGHTBOX_NAVIGATION_PREV_HOVER_INFO', "Lightbox Navigation Previous Button Hover Image (path to image). Upload the image for lightbox navigation's previous hover button.");
    define('DOPWGG_LIGHTBOX_NAVIGATION_NEXT_INFO', "Lightbox Navigation Next Button Image (path to image). Upload the image for lightbox navigation's next button.");
    define('DOPWGG_LIGHTBOX_NAVIGATION_NEXT_HOVER_INFO', "Lightbox Navigation Next Button Hover Image (path to image). Upload the image for lightbox navigation's next hover button.");
    define('DOPWGG_LIGHTBOX_NAVIGATION_CLOSE_INFO', "Lightbox Navigation Close Button Image (path to image). Upload the image for lightbox navigation's close button.");
    define('DOPWGG_LIGHTBOX_NAVIGATION_CLOSE_HOVER_INFO', "Lightbox Navigation Close Button Hover Image (path to image). Upload the image for lightbox navigation's close hover button.");
    
    define('DOPWGG_CAPTION_HEIGHT_INFO', "Caption Height (value in pixels). Default value: 75. Set caption height.");
    define('DOPWGG_CAPTION_TITLE_COLOR_INFO', "Caption Title Color (color hex code). Default value: eeeeee. Set caption title color.");
    define('DOPWGG_CAPTION_TEXT_COLOR_INFO', "Caption Text Color (color hex code). Default value: dddddd. Set caption text color.");
    define('DOPWGG_CAPTION_SCROLL_SCRUB_COLOR_INFO', "Caption Scroll Scrub Color (color hex code). Default value: 777777. Set scroll scrub color.");
    define('DOPWGG_CAPTION_SCROLL_BG_COLOR_INFO', "Caption Scroll Background Color (color hex code). Default value: e0e0e0. Set scroll background color.");

    define('DOPWGG_SOCIAL_SHARE_ENABLED_INFO', "Social Share Enabled (true, false). Default value: true. Enable AddThis Social Share.");
    define('DOPWGG_SOCIAL_SHARE_LIGHTBOX_INFO', "Lightbox Social Share Button Image (path to image). Upload the image for lightbox social share button.");

    define('DOPWGG_TOOLTIP_BG_COLOR_INFO', "Tooltip Background Color (color hex code). Default value: ffffff. Set tooltip background color.");
    define('DOPWGG_TOOLTIP_STROKE_COLOR_INFO', "Tooltip Stroke Color (color hex code). Default value: 000000. Set tooltip stroke color.");
    define('DOPWGG_TOOLTIP_TEXT_COLOR_INFO', "Tooltip Text Color (color hex code). Default value: 000000. Set tooltip text color.");

    define('DOPWGG_LABEL_POSITION_INFO', "Label Position (bottom, top). Default value: bottom. Set label position.");
    define('DOPWGG_LABEL_TEXT_COLOR_INFO', "Label Text Color (color hex code). Default value: 000000. Set label text color.");
    define('DOPWGG_LABEL_TEXT_COLOR_HOVER_INFO', "Label Text Color Hover (color hex code). Default value: ffffff. Set label text hover color.");

    // Widget    
    define('DOPWGG_WIDGET_TITLE', "Wall/Grid Gallery");
    define('DOPWGG_WIDGET_DESCRIPTION', "Select the ID of the Gallery you want in the widget.");
    define('DOPWGG_WIDGET_LABEL_TITLE', "Title:");
    define('DOPWGG_WIDGET_LABEL_ID', "Select Gallery ID:");
    define('DOPWGG_WIDGET_NO_SCROLLERS', "No galleries.");
    
    // HELP
    define('DOPWGG_HELP_DOCUMENTATION', "Documentation");
    define('DOPWGG_HELP_FAQ', "FAQ");

?>