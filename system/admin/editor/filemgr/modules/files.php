<?php

function formatRawSize($bytes) {
    if(!empty($bytes)) {
            $s = array('bytes', 'kb', 'MB', 'GB', 'TB', 'PB');
            $e = floor(log($bytes)/log(1024));
            $output = sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
        return $output;
    }
}

require_once("../../../../../config.php");
require_once("../config.php");

if (!empty($_GET['dir']))
{

$_GET['dir'] = urldecode($_GET['dir']);

$counter = 0;

if( file_exists($root . $_GET['dir']) ) {
    $files = scandir($root . $_GET['dir']);
    natcasesort($files);
    echo("<ul class=\"jqueryFileTree fileViewer\">");
        // All files
        foreach( $files as $file ) {
          $counter++;
            if( file_exists($root . $_GET['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_GET['dir'] . $file) ) {
                $ext = preg_replace('/^.*\./', '', $file);
                
                $file_size = formatRawSize(filesize($root . $_GET['dir'] . $file));
                
                  if ($file_size=="") { $file_size="0 bytes"; }
                
                $file_mod = date("m/d/Y", fileatime($root . $_GET['dir'] . $file));
                
                $tooltip = "";
                $preload = "";
                
                if ($ext=="gif" || $ext=="jpg" || $ext=="png")
                  {
                    $tooltip = "onmouseover=\"Tip('<img class=thumbnail src=" . str_replace(" ","%20",$relroot) . "assets/" . str_replace(" ","%20",htmlentities($_GET['dir'] . $file)) . ">');\" onmouseout=\"UnTip();\"";
                    $preload = "<img class=\"hide\" src=" . $relroot . "assets/" . htmlentities($_GET['dir'] . $file) . " />";
                    }
                
                echo "<li $tooltip class=\"file ext_$ext\"><a onclick=\"selectFile(this,'$relroot');\" rel=\"" . str_ireplace("//", "/", htmlentities($_GET['dir'] . $file)) . "\"><div class=\"fileInfo\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>$file_size</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>$file_mod</td></tr></table></div>" . shortenString($file,40) . "</a>$preload</li>";
            }
        }
        echo "</ul>";    
    }
    
if ($counter==0)
  {
    echo ("<div class=\"nofiles\"></div>");
    }
}
?>