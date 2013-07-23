<?php

require_once("../../../../../config.php");
require_once("../config.php");

$message = "";

$path = $_GET['path'];

?>
<div class="modal_contents">
<h3><?php echo lang('Upload'); ?></h3>

<a href="#" id="processUpload" onclick="$('#fileInput').fileUploadStart();"><?php echo lang('Upload'); ?></a>
<div style="display:none;"><input type="file" name="fileInput" id="fileInput" style="display: none; visibility: hidden;" /></div>
<input type="button" class="button" value="Close" onclick="$('#files').html('<img src=images/spinner.gif />');$('#file_actions').html('<img src=images/spinner.gif />');$('#files').load('modules/files.php?dir=<?php echo(str_replace(" ","%20",$path)); ?>');$('#file_actions').load('modules/file_actions.php?dir=<?php echo(str_replace(" ","%20",$path)); ?>');$('#dialog').jqmHide()" />
<div class="clear"></div>
</div>