<?php
    
    //////////////////////////////////////////////////////////////////
    // Includes
    //////////////////////////////////////////////////////////////////
    
    if(empty($check_config)){
        require_once('../../../../config.php'); // System include file
    }

    //////////////////////////////////////////////////////////////////
    // Module Admin Configuration
    //////////////////////////////////////////////////////////////////
    
    $module_admin = new StdClass();
    $module_admin->name           = "URL Rewrites";
    $module_admin->load           = "default.php";
    $module_admin->dialog_width   = 500;
    
    //////////////////////////////////////////////////////////////////
    // JSON Functions
    //////////////////////////////////////////////////////////////////
    
    function getJSON(){
        return json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "system/modules/url_rewrite/rules.json"), true);
    }
    
    function saveJSON($a){
        $fh = fopen($_SERVER['DOCUMENT_ROOT'] . "system/modules/url_rewrite/rules.json", 'w') or die("can't open file");
        fwrite($fh, json_encode($a));
        fclose($fh);
    }
    
    // Set array value
    $url_rewrites = getJSON();
    
?>