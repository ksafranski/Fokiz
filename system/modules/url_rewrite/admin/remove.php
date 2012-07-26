<?php

require_once('config.php');

unset($url_rewrites[$_POST['path_old']]);

saveJSON($url_rewrites);

?>