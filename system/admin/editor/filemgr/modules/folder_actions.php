<?php

require_once("../../../../../config.php");
require_once("../config.php");

$dirSplit = explode("/",$_GET['file']);

?>
<div>
<a onclick="openDialog('modules/add_folder.php?path=<?php echo(str_replace(" ", "%20", $_GET['file'])); ?>',300);" class="action_icon" id="folder_add"><?php echo lang('Add'); ?></a>
<?php if($_SESSION['usr_type']==User::ADMIN){ ?>
<a onclick="openDialog('modules/rename.php?type=folder&path=<?php echo(str_replace(" ", "%20", $_GET['file'])); ?>',300);" class="action_icon" id="folder_rename"><?php echo lang('Rename'); ?></a>
<a onclick="openDialog('modules/delete.php?type=folder&path=<?php echo(str_replace(" ", "%20", $_GET['file'])); ?>',300);" class="action_icon" id="folder_delete"><?php echo lang('Delete'); ?></a>
<?php } ?>
</div>