<?php

/*
* Title                   : Wall/Grid Gallery (WordPress Plugin)
* Version                 : 1.8
* File                    : dopwgg-frontend.php
* File Version            : 1.0
* Created / Last Modified : 05 February 2012
* Author                  : Dot on Paper
* Copyright               : © 2011 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Wall/Grid Gallery Front End AJAX.
*/

    define("DOING_AJAX", true);

    require_once("../../../wp-load.php"); // Add wp-load.php file.
    
    if(!isset($_REQUEST["action"]) || trim($_REQUEST["action"])==""){
        die("-1");
    }

    @header("Content-Type: text/html; charset=".get_option("blog_charset"));

    include_once('dopwgg.php'); // Including your plugin’s main file where ajax actions are defined.
    send_nosniff_header();

    if(has_action("wp_ajax_".$_REQUEST["action"])){
        do_action("wp_ajax_".$_REQUEST["action"]);
        exit;
    }
    status_header(404);

?>