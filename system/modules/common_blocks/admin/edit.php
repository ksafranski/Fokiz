<?php

require_once('config.php');

// Get product data

$block = urldecode($_GET['block']);
$copy = $_GET['copy'];

$content = "";

// Get Content
if($block!="new"){ $content = $json_array[$block]['content']; }

$hide_field = "blk_hide";
if($block=="new" || $copy=="yes"){
    $block = "";
    $hide_field = "";
}
?>
<div class="<?php echo($hide_field); ?>">
<span class="blk_name_note">(Letters, Numbers &amp; Underscores)</span>
<label>Name</label>
<input type="text" name="name" value="<?php echo($block); ?>" onkeypress="validateName(event);" />
</div>

<label class="<?php echo($hide_field); ?>">Content</label>
<textarea class="hide" name="content"><?php echo(stripslashes($content)); ?></textarea>

<style>
    .blk_name_note { float: right; width: auto; margin: 0 !important; padding: 0; line-height: 100%; color: #666 !important; }
    .blk_hide { display: none !important; }
</style>

<script>

var instance = "content";
if (CKEDITOR.instances[instance]) { CKEDITOR.remove(CKEDITOR.instances[instance]); } // Cleanup (AJAX)
var editor = CKEDITOR.replace(instance,{
     filebrowserBrowseUrl : 'system/admin/editor/filemgr/index.php?type=File',
     filebrowserImageBrowseUrl : 'system/admin/editor/filemgr/index.php?type=Images',
     filebrowserFlashBrowseUrl : 'system/admin/editor/filemgr/index.php?type=Flash',
     filebrowserWindowWidth : '720',
     filebrowserWindowHeight : '615',
     language: '<?php echo(strtolower(LANGUAGE)); ?>'
});

function validateName(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode( key );
    var regex = /^[A-Za-z0-9_]*$/;
    if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
}

</script>