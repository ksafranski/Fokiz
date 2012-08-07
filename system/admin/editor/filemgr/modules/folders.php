<?php

require_once("../../../../../config.php");
require_once("../config.php");

$_POST['dir'] = urldecode($_POST['dir']);

if( file_exists($root . $_POST['dir']) ) {
    $files = scandir($root . $_POST['dir']);
    natcasesort($files);
    if( count($files) > 2 ) { /* The 2 accounts for . and .. */
        echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
        // All dirs
        foreach( $files as $file ) {
            if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) && htmlentities($file)!='xml' && $file!='_sized' ) {
                echo "<li class=\"directory collapsed\"><a onclick=\"selectFolder(this);\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
            }
        }
        
        echo "</ul>";    
    }
}
else
{
echo("PATH ERROR!");
}

?>