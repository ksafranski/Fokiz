<?php
  require_once('../../../../config.php');
  permitUser(User::ADMIN, User::EDITOR);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex,nofollow" />
<title>File Manager</title>
<link type="text/css" rel="stylesheet" href="styles/default.css" />
<!--[if IE]><link type="text/css" rel="stylesheet" href="styles/ie_tweaks.css" /><![endif]-->
<script type="text/javascript" charset="utf-8">
sessionId = "<?php echo session_id(); ?>";
</script>
<script language="javascript" type="text/javascript" src="scripts/jquery.js"></script>
<script language="javascript" type="text/javascript" src="scripts/jquery.filetree.js"></script>
<script language="javascript" type="text/javascript" src="scripts/jquery.modal.js"></script>
<script language="javascript" type="text/javascript" src="scripts/jquery.uploadify.js"></script>
<script language="javascript" type="text/javascript" src="scripts/main.js"></script>
<script language="javascript" type="text/javascript">

  function returnSelected(val){
        /*window.opener.document.getElementById('62_textInput').value=val;
        window.opener.document.getElementById('cmbLinkProtocol').value='';
        window.opener.document.getElementById('62_textInput').focus();
        */
        var dialog = window.opener.CKEDITOR.dialog.getCurrent();
        <?php
        if(isset($_GET['type'])){
          if($_GET['type']=="File"){
              echo("dialog.setValueOf('info','protocol','');dialog.setValueOf('info','url',val);");
          }
          if($_GET['type']=="Images"){
              echo("dialog.setValueOf('info','txtUrl',val);");
          }
          if($_GET['type']=="Flash"){
              echo("dialog.setValueOf('info','src',val);");
          }
        }

        ?>
    self.close();
    }

</script>
<!--[if lt IE 7]><script language="javascript" type="text/javascript" src="scripts/ie6.js"></script><![endif]-->
</head>
<body oncontextmenu="return false;">
<script language="javascript" type="text/javascript" src="scripts/tooltip.js"></script>
<div id="col_folders">

  <div class="col_header">
    <span></span>
    <h2><?php echo lang('Folders'); ?></h2>
  </div>

  <div class="col_body">

    <div id="folders"></div>

  </div>

  <div id="folder_actions" class="actions">
  </div>

</div>


<div id="col_files">

  <div class="col_header">
    <span></span>
    <h2><?php echo lang('Files'); ?></h2>
  </div>

  <div class="col_body">

    <div id="files"></div>

  </div>

  <div id="file_actions" class="actions">
  </div>

</div>

    <input <?php if(empty($_GET['type'])) { echo("style=\"display:none;\""); } ?> type="button" class="button right" style="margin-right: 0; -moz-border-radius-topleft: 0px; -moz-border-radius-topright: 5px; -moz-border-radius-bottomright: 5px; -moz-border-radius-bottomleft: 0px; -webkit-border-radius: 0px 5px 5px 0px; border-left: none;" value="<?php echo lang('Close'); ?>" onclick="self.close();" /><input <?php if(empty($_GET['type'])) { echo("style=\"display:none;\""); } ?> style="margin-right: 0;-moz-border-radius-topleft: 5px; -moz-border-radius-topright: 0px; -moz-border-radius-bottomright: 0px; -moz-border-radius-bottomleft: 5px; -webkit-border-radius: 5px 0px 0px 5px;" onclick="returnSelected($(this).attr('rel'));" id="choose_file_button" type="button" class="button right" value="<?php echo lang('Use Selected File'); ?>" rel="Some URL" disabled="disabled" />

<div class="jqmWindow" id="dialog"></div>

<!-- File Uploader Window -->
<div class="jqmWindow" id="uploader">
<div class="modal_contents">
<h3><?php echo lang('Upload'); ?></h3>
<input type="button" class="button right" value="Upload file(s)" style="margin: 0 0 0 -5px;" onclick="$('#fileInput').fileUploadStart();" />
<div id="upregion"><input type="file" name="fileInput" id="fileInput" /></div>
    <hr style="clear: both; height: 1px; border: none; border-top: 1px solid #e6e6e6; margin: 15px 0 5px 0;" />
<input type="button" class="button" value="Close" id="closeUploader" onclick="refreshonclose($(this).attr('rel'));" rel="" />
<div class="clear"></div>
</div>
</div>

</body>
</html>
