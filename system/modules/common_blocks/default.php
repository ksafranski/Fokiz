<?php

$vload = "";
$vquery = "";

if(!empty($_GET['load'])){
    $vload = $_GET['load'];
}

require_once('config.php');

// Pass in parameter
$param = "";
if(!empty($_GET['param'])){ $param = $_GET['param']; }

?>

<div class="common_block">
    <?php
    
    //////////////////////////////////////////////////////////////////
    // Get Data
    //////////////////////////////////////////////////////////////////
    
    $output = "<strong>[MISSING DATA]</strong>"; // In case block is missing
    if(isset($json_array[$param]['content'])){ $output = stripslashes($json_array[$param]['content']); }    
    echo($output);
    
    ?>
</div>