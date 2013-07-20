<?php

require_once("../../../../../config.php");
require_once("../config.php");

$message = "";

$path = $_GET['path'];

// Save changes

if (!empty($_GET['save']))
  {
    mkdir($root . $path . $_POST['foldername'],0777);
    $message = $lang['Folder Successfully Created'];
    }
?>
<div class="modal_contents">
<h3>Create Folder</h3>
<?php

if ($message!="")
  {
  echo("<div class=\"message\">$message</div>");
    }
else
  {
?>
<form name="addfolder" id="addfolder">
<label>
<?php echo lang('Folder Name'); ?>
<input type="text" name="foldername" id="foldername" onkeyup="validName(this.value);" onKeyDown="return ignoreEnter(event)" />
<div id="name_warning" style="display: none;"><?php echo lang('Invalid Folder Name'); ?></div>
</label>
<input type="button" class="button" id="save_button" value="<?php echo lang('Save'); ?>" onclick="addFolder('<?php echo($path); ?>',$('#foldername').val());" />
<?php
  }
?>
<input type="button" class="button" value="<?php echo lang('Close'); ?>" onclick="$('#dialog').jqmHide()" />
</form>
<div class="clear"></div>
</div>