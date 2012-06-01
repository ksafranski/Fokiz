<?php

require_once('config.php');

// Get Fields
$name = $_POST['name'];
$content = $_POST['content'];

// Remove if exists
unset($json_array[$name]);

// Build (or rebuild) array element
$json_array[$name]['content'] = $content;

// Write to file
$output = json_encode($json_array);
$file = fopen($json_path,'w+');
fwrite($file, $output);
fclose($file);

?>