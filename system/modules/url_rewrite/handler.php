<?php

    $redirect = false;

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

    $url_rewrites = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/system/modules/url_rewrite/rules.json"), true);

    $current_path = formatPath($_SERVER['REQUEST_URI']);
    
    if(count($url_rewrites)){
        if(array_key_exists($current_path, $url_rewrites)){
            $redirect = $url_rewrites[$current_path];
        }
    }
    
    if($redirect){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . BASE_URL . ltrim($redirect,"/"));
        exit();
    }

?>