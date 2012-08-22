<?php

    require_once('config.php'); // Includes root config file.

    //////////////////////////////////////////////////////////////////
    // Module Configuration
    //////////////////////////////////////////////////////////////////
    
    $module = new StdClass();
    $module->name           = "Contact Form";
    $module->description    = "Inserts an email contact form"; 
    $module->load           = "default.php";
    $module->css            = "screen.css";
    $module->js             = "common.js";
    $module->param          = "Recipient Email Address or Comma-Separated List";
    $module->param_options  = "";
    
    //////////////////////////////////////////////////////////////////
    // Get Module Folder
    //////////////////////////////////////////////////////////////////
    
    $module_path = explode("/",dirname(__FILE__));
    $module_path_nodes = count($module_path);
    
    $module->folder         = $module_path[($module_path_nodes-1)];

?>