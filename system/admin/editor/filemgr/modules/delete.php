<?php

require_once("../../../../../config.php");
require_once("../config.php");

function recursive_remove_directory($directory, $empty=FALSE)
 {
     // if the path has a slash at the end we remove it here
     if(substr($directory,-1) == '/')
     {
         $directory = substr($directory,0,-1);
     }

     // if the path is not valid or is not a directory ...
     if(!file_exists($directory) || !is_dir($directory))
     {
         // ... we return false and exit the function
         return FALSE;

     // ... if the path is not readable
     }elseif(!is_readable($directory))
     {
         // ... we return false and exit the function
         return FALSE;

     // ... else if the path is readable
     }else{

         // we open the directory
         $handle = opendir($directory);

         // and scan through the items inside
         while (FALSE !== ($item = readdir($handle)))
         {
             // if the filepointer is not the current directory
             // or the parent directory
             if($item != '.' && $item != '..')
             {
                 // we build the new path to delete
                 $path = $directory.'/'.$item;

                 // if the new path is a directory
                 if(is_dir($path))
                 {
                     // we call this function with the new path
                     recursive_remove_directory($path);

                 // if the new path is a file
                 }else{
                     // we remove the file
                     unlink($path);
                 }
             }
         }
         // close the directory
         closedir($handle);

         // if the option to empty is not set to true
         if($empty == FALSE)
         {
             // try to delete the now empty directory
             if(!rmdir($directory))
             {
                 // return false if not possible
                 return FALSE;
             }
         }
         // return success
         return TRUE;
     }
 }

$message = "";

$path = $_GET['path'];
$type = ucfirst($_GET['type']);

// Save changes

if (!empty($_GET['del']))
  {
    if ($type=="Folder") // Delete folder and all contents
    {
    recursive_remove_directory($root . $path,FALSE);
        }
    else // Delete file
      {
        unlink($root . $path);
        }
    $message = "$type " . $lang['Successfully Deleted'];
    }

if ($type=="Folder")
  {
    $path = substr_replace($path,"",-1); // Removes trailing slash
    }

$node = explode("/",$path);
$node = end($node);

$dir = str_replace($node,"",$path);
?>
<div class="modal_contents">
<h3>Delete <?php echo($type); ?></h3>
<?php

if ($message!="")
  {
  echo("<div class=\"message\">$message</div>");
    }
else
  {
?>
<p class="bold"><?php echo lang('Are you sure you wish to delete the selected item?'); ?></p>
<input type="button" class="button" value="<?php echo lang('Delete'); ?>" onclick="deleteItem('<?php echo(str_replace(" ","%20",$path)); ?>','<?php echo(strtolower($type)); ?>','<?php echo(str_replace(" ","%20",$dir)); ?>')" />
<?php
  }
?>
<input type="button" class="button" value="<?php echo lang('Close'); ?>" onclick="$('#dialog').jqmHide()" />
<div class="clear"></div>
</div>
