<?php

    require_once('config.php'); // Includes root config file.

    //////////////////////////////////////////////////////////////////
    // Module Configuration
    //////////////////////////////////////////////////////////////////

    $module = new StdClass();
    $module->name           = "Twitter Feed";
    $module->description    = "Shows the most recent Tweets by a user (in the last 7 days)";
    $module->load           = "default.php";
    $module->css            = "screen.css";
    $module->js             = "";
    $module->param          = "Twitter Username";
    $module->param_options  = "";

    //////////////////////////////////////////////////////////////////
    // Get Module Folder
    //////////////////////////////////////////////////////////////////
    $module->folder = getModuleFolder(__FILE__);

    //////////////////////////////////////////////////////////////////
    // Extra Config
    //////////////////////////////////////////////////////////////////

    $limit = 5; // Maximum number to show

?>