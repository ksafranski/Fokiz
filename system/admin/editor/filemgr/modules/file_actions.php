<?php

require_once("../../../../../config.php");
require_once("../config.php");

$dir = "";

if (!empty($_GET['dir']))
  {
    $dir = $_GET['dir'];
    }

?>
<div>
<?php
if (empty($_GET['file']))
  {
    echo("");
    }
else
  {
  if ($_GET['file']!='none')
    {
        $node = explode("/",$_GET['file']);
    $node = end($node);

        $dir = str_replace($node,"",$_GET['file']);

        $openPath = BASE_URL . str_replace("//","",$assets) . str_replace(" ", "%20", $_GET['file']);

      ?>
    <a onclick="popUp('<?php echo($openPath); ?>');" class="action_icon" id="file_view" target="_blank"><?php echo lang('Open'); ?></a>
    <a onclick="openDialog('modules/rename.php?type=file&path=<?php echo(str_replace(" ", "%20", $_GET['file'])); ?>',300);" class="action_icon" id="file_rename"><?php echo lang('Rename'); ?></a>
    <?php if($_SESSION['usr_type']==User::ADMIN){ ?>
    <a onclick="openDialog('modules/delete.php?type=file&path=<?php echo(str_replace(" ", "%20", $_GET['file'])); ?>',300);" class="action_icon" id="file_delete"><?php echo lang('Delete'); ?></a>
    <?php } ?>
    <div id="action_divider"></div>
    <?php
        }
    }
if ($dir!="")
  {
    ?>
  <a onclick="activateUploader('<?php echo($assets . str_replace(" ", "%20", $dir)); ?>', '<?php echo("/" . str_replace(" ", "%20", $dir)); ?>');" class="action_icon" id="file_upload"><?php echo lang('Upload'); ?></a>
  <?php
    }
    ?>
</div>