<link rel="stylesheet" type="text/css" href="../styles/default.css" />
<style>

html, body {
  width: 205px;
  margin: 0;
    padding: 0;
    overflow: hidden;
    background: #fbfbfb;
}

</style>
<?php

include("../config.php");

$message = "";

$path = $_GET['path'];

// Save changes

if (!empty($_GET['upload']))
  {
    $target_path = $root . $_GET['path'] . basename($_FILES['uploadedfile']['name']); 

        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
                $message = "File uploaded.";
        } else{
                $message = "File upload error.";
        }
    }

?>
<?php if ($message!="") 
  {    
    echo("<div class=\"message\">$message</div><a href=\"upload_frame.php?path=$path\">Upload another file</a>"); 
    } 
else
  {    
?>
<form name="uploader" id="uploader" enctype="multipart/form-data" method="post" action="upload_frame.php?path=<?php echo($path); ?>&upload=t">
<label>
Select File:
<input type="file" name="uploadedfile" id="uploadedfile" onKeyDown="return ignoreEnter(event)" />
</label>
</form>
<?php
  }
?>
