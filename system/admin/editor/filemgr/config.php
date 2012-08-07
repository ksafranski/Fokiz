<?php

checkToken();

// Prevent IE Chaching Problem

header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$relroot = FOKIZ_PATH;

$assets = $relroot . "/assets/";

$root = BASE_PATH . "/assets/";

?>