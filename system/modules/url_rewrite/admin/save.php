<?php

require_once('config.php');

function formatPath($p){

    // Remove domain name
    $ap = parse_url($p);
    $p = $ap['path'];

    // Add Leading slash
    if(substr($p,0,1)!="/"){ $p = "/".$p; }
    
    // Strip trailing slash
    if(strlen($p)>1){ // Ensure not a root "/" redirect
        if(substr($p, -1, 1)=="/"){
            $p = rtrim($p,"/");
        }
    }
    
    // Return
    return $p;
}

$path_old = $_POST['path_old'];
$path_new = $_POST['path_new'];

$url_rewrites[formatPath($path_old)]=formatPath($path_new);

saveJSON($url_rewrites);

?>