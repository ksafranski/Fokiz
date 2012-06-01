<?php

require_once('config.php');

$block = $_GET['block'];
unset($json_array[$block]);

// Rebuild JSON
$output = json_encode($json_array);
$file = fopen($json_path,'w+');
fwrite($file, $output);
fclose($file);

?>