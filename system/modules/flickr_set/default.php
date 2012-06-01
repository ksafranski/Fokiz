<?php

require_once('config.php');

// Pass in parameter
$param = "";
if(!empty($_GET['param'])){ $param = $_GET['param']; }

// Require phpFlickr
require_once('phpFlickr.php');

$f = new phpFlickr($flickr_api_key);
$photos = $f->photosets_getPhotos($param);
$html = "<ul class=\"flickr_set\">";
foreach ($photos['photoset']['photo'] as $photo) {
    $html .= "<li><a class=\"flickr_set_image\" rel=\"$param\" href='".$f->buildPhotoURL($photo, "large")."' title='".$photo['title']."'>";
    $html .= "<img border='0' alt='$photo[title]' src='".$f->buildPhotoURL($photo, "Square")."' id='photo_".$photo['id']."'></a></li>";
}
$html .= "</ul>";

echo $html;

?>
