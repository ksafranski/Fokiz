<?php

    require_once('config.php'); // Includes root config file.

    //////////////////////////////////////////////////////////////////
    // Module Configuration
    //////////////////////////////////////////////////////////////////

    $module = new StdClass();
    $module->name           = "Google Map";
    $module->description    = "Includes a Google Map based on the address/location provided.";
    $module->load           = "default.php";
    $module->css            = "screen.css";
    $module->js             = "common.js";
    $module->param          = "Address/Location";
    $module->param_options  = "";

    //////////////////////////////////////////////////////////////////
    // Get Module Folder
    //////////////////////////////////////////////////////////////////
    $module->folder = getModuleFolder(__FILE__);

?>