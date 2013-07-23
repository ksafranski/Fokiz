<?php

require_once("../../../../../config.php");
require_once("../config.php");

$message = "";

// Get type

$type = ucfirst($_GET['type']);
$path = $_GET['path'];

if ($type=="Folder")
  {
    $path = substr_replace($path,"",-1); // Removes trailing slash
    }

$node = explode("/",$path);
$node = end($node);

// Save changes

if (!empty($_GET['save']))
  {
  $newPath = str_replace($node,$_POST['newname'],$path);
    rename($root . $path, $root . $newPath);
    $path = $newPath;
    $node = $_POST['newname'];
    $message = $lang['Successfully Renamed Item'];
    }

if ($type=="File")
  {
    $filePath = str_replace($node,"",$path);
    $filePath = str_replace(" ","%20",$filePath);
  }
?>
<div class="modal_contents">
<h3><?php echo lang('Rename'); ?></h3>
<?php if ($message!="") {    echo("<div class=\"message\">$message</div>"); } ?>
<form name="rename" id="rename">
<label>
<?php echo($type) ?> <?php echo lang('Name'); ?>
<input type="text" name="newname" id="newname" value="<?php echo($node); ?>" onkeyup="validName(this.value);" onKeyDown="return ignoreEnter(event);" />
<div id="name_warning" style="display: none;"><?php echo lang('Invalid Name'); ?></div>
</label>

<input type="button" class="button" value="<?php echo lang('Rename'); ?>" id="save_button" onclick="renameItem('<?php if ($type=="Folder") { echo($path . "/"); } else { echo($path); } ?>','<?php echo(strtolower($type)); ?>',$('#newname').val(),'<?php echo(str_replace($node,"",$path)); ?>');" />

<input type="button" class="button" value="<?php echo lang('Close'); ?>" onclick="<?php

if ($type=="Folder") { echo("$('#files').load('modules/files.php?dir=$path/');"); } else { echo("$('#files').load('modules/files.php?dir=$filePath');"); }

$path = str_replace(" ","%20",$path);

if ($type=="File")
  {
    $path = str_replace($node,"",$path);
    $path = str_replace(" ","%20",$filePath);
  }
else
  {
    $path = $path . "/";
    }
?>$('#file_actions').load('modules/file_actions.php?file=none&dir=<?php echo($path); ?>');$('#dialog').jqmHide()" />
</form>
<div class="clear"></div>
</div>