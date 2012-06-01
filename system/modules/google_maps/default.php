<?php

require_once('config.php');

// Pass in parameter
$param = "";
if(!empty($_GET['param'])){ $param = $_GET['param']; }

$url = "http://maps.google.com/maps?q=" . str_replace(" ","+",$param) . "&amp;output=embed";

echo("<div class=\"google_map\"><iframe src=\"$url\">You must have iFrames enabled to view this content.</iframe></div>");
?>


