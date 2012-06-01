<?php

    require_once('config.php'); // Includes root config file.

    //////////////////////////////////////////////////////////////////
    // Module Configuration
    //////////////////////////////////////////////////////////////////
    
    $module = new StdClass();
    $module->name           = "Flickr PhotoSet";
    $module->description    = "Inserts a Flickr Set based on provided Photo Set ID"; 
    $module->load           = "default.php";
    $module->css            = "screen.css";
    $module->js             = "common.js";
    $module->param          = "Flickr Photo Set ID";
    $module->param_options  = "";
    
    //////////////////////////////////////////////////////////////////
    // Get Module Folder
    //////////////////////////////////////////////////////////////////
    
    $module_path = explode("/",dirname(__FILE__));
    $module_path_nodes = count($module_path);
    
    $module->folder         = $module_path[($module_path_nodes-1)];
    
    //////////////////////////////////////////////////////////////////
    // Extra Config
    //////////////////////////////////////////////////////////////////
    
    $flickr_api_key = "ffb6a6553e6f2f39294fa1346e9f3924";

?>