<?php

    //////////////////////////////////////////////////////////////////
    // Includes
    //////////////////////////////////////////////////////////////////

    require_once('config.php'); // System include file

    //////////////////////////////////////////////////////////////////
    // Module Configuration
    //////////////////////////////////////////////////////////////////

    $module = new StdClass();
    $module->name           = "Common Blocks";
    $module->description    = "Insert blocks of content used throughout the site.";
    $module->load           = "default.php";
    $module->css            = "";
    $module->js             = "";
    $module->param          = "Block";
    $module->param_options  = "";

    //////////////////////////////////////////////////////////////////
    // Get Module Folder
    //////////////////////////////////////////////////////////////////
    $module->folder = getModuleFolder(__FILE__);

    //////////////////////////////////////////////////////////////////
    // Load & Query Variables - www.website.com/LOAD/QUERY
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['load'])){ $qs_load = $_GET['load']; }else{ $qs_load = ""; }
    if(!empty($_GET['query'])){ $qs_query = $_GET['query']; }else{ $qs_query = ""; }

    //////////////////////////////////////////////////////////////////
    // Get JSON
    //////////////////////////////////////////////////////////////////

    $json_path = BASE_PATH . "/system/modules/" . $module->folder . "/blocks.json";
    $json_file = file_get_contents($json_path);
    $json_array = json_decode($json_file,true);
    asort($json_array);

    //////////////////////////////////////////////////////////////////
    // Define Param Options
    //////////////////////////////////////////////////////////////////

    foreach($json_array as $block=>$data){
        if($module->param_options!=""){ $module->param_options .= ","; }
        $module->param_options .= $block;
    }

?>