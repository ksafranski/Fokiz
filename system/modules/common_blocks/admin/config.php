<?php
    
    //////////////////////////////////////////////////////////////////
    // Includes
    //////////////////////////////////////////////////////////////////
    
    if(empty($check_config)){
        require_once('../../../../config.php'); // System include file
        include('../config.php'); // Module (main) config file
        checkToken(); // Check Authentication Token
    }

    //////////////////////////////////////////////////////////////////
    // Module Admin Configuration
    //////////////////////////////////////////////////////////////////
    
    $module_admin = new StdClass();
    $module_admin->name           = "Common Blocks";
    $module_admin->load           = "default.php";
    $module_admin->dialog_width   = 600;
    
?>

    