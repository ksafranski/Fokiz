// Load on document ready ##############################################################

$(document).ready(function() {
    
    // Handle form field focus (in lieu of :focus) ##########################
    setTimeout(formFocusFix,2000); // Pause for AJAX forms to load
    
    // Load filetree plugin #################################################
    $('#folders').fileTree({ 
        root: '',
        script: 'modules/folders.php',
        multiFolder: false
    });
    
    // Modal dialogs ########################################################
    $('#dialog').jqm();
    $('#uploader').jqm();    
    
});


// AJAX POST Processor ################################################################

function ajaxPost(url,formid,containerid)
  {
  $.post(url, $('#'+formid).serializeArray(), function(data)
    {
      $('#'+containerid).html(data);
            $('#return-message').fadeOut(8000);
    });            
  }
    
// Form Focus Fix ######################################################################

function formFocusFix()
  {
    $('input, textarea, select').focus(function(){ $(this).addClass('focus'); });
    $('input, textarea, select').blur(function(){ $(this).removeClass('focus'); });
    }
    
// Valid name (rename) checking ########################################################

function validName(f)
  {
  var isNameValid = /^[a-z A-Z 0-9.+_-]*$/.test(f);
    if(!isNameValid)
    {
    $('#name_warning').show();
    $('#save_button').attr("disabled", "disabled");
    }
    else
    {
    $('#name_warning').hide();
    $('#save_button').removeAttr("disabled");
    }
  }
    
// Ignore enter key ####################################################################

function ignoreEnter(e)
  {
    if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)){ return false; }
    }
    
// AJAX Post Function ##################################################################

function ajaxPost(url,formid,containerid)
  {
  $.post(url, $('#'+formid).serializeArray(), function(data)
    {
        $('#'+containerid).html(data);
        $('.message').fadeOut(5000);
        });
    setTimeout(formFocusFix,1000);
  }
    
// Open modal dialog ###################################################################
    
function openDialog(url,width)
  {
    $('#dialog').html('');
    $('#dialog').jqmShow();
    $('#dialog').css('width',width+'px');
    $('#dialog').css('margin-left','-'+width/2+'px');
    $('#dialog').load(url);
    setTimeout(formFocusFix,1000);
    }
    
// Resize image for thumbnail view #####################################################

function ResizeImage(image)
{
    if (image.className == "Thumbnail")
    {
              var maxwidth = 200;
                var maxheight = 200;
                
        w = image.width;
        h = image.height;      
        if( w == 0 || h == 0 )
        {
            image.width = maxwidth;
            image.height = maxheight;
        }
        else if (w > h)
        {
            if (w > maxwidth) image.width = maxwidth;
        }
        else
        {
            if (h > maxheight) image.height = maxheight;
        }      
        image.className = "ScaledThumbnail";
    }
}
    
// Actions performed when file selected ################################################

function selectFile(fitem,root)
  {
    path = $(fitem).attr('rel').replace(/ /g,"%20");
    $('#choose_file_button').attr('rel',root+'assets/'+path);
    $('#choose_file_button').removeAttr("disabled");
    $('#file_actions').html('<img src="images/spinner.gif" />');
    $('#file_actions').load('modules/file_actions.php?file='+path);
    $('#files a').removeClass('itemSelected');
    $(fitem).addClass('itemSelected');
    }
    
// Actions performed when folder selected ##############################################

function selectFolder(fitem)
  {
    path = $(fitem).attr('rel').replace(/ /g,"%20");
    $('#choose_file_button').attr("disabled", "disabled");
    $('#files').html('<img src="images/spinner.gif" />');
    $('#file_actions').html('<img src="images/spinner.gif" />');
    $('#folder_actions').html('<img src="images/spinner.gif" />');
    $('#files').load('modules/files.php?dir='+path);
    $('#file_actions').load('modules/file_actions.php?file=none&dir='+path);
    $('#folder_actions').load('modules/folder_actions.php?file='+path);
    $('#folders a').removeClass('itemSelected');
    $(fitem).addClass('itemSelected');
    }

// Create a new folder #################################################################

function addFolder(path,name)
  {
    ajaxPost('modules/add_folder.php?path='+path+'&save=t','addfolder','dialog');    
    $('a.itemSelected').after('<ul class="jqueryFileTree" style="display:block;"><li class="directory collapsed"><a rel="'+path+name+'/" href="#" class="" onclick="selectFolder(this);">'+name+'</li></ul>');
    $('a.itemSelected').removeClass('itemSelected');
    }
    
// Rename item #########################################################################

function renameItem(path,type,name,strippath)
  {
    ajaxPost('modules/rename.php?type='+type+'&path='+path+'&save=t','rename','dialog');
    $('#'+type+'s .itemSelected').html(name);$('#'+type+'s .itemSelected').attr('rel',strippath+name+'/');
    }
    
// Delete item #########################################################################

function deleteItem(path,type,dir)
  {
    $('#dialog').load('modules/delete.php?path='+path+'&type='+type+'&del=t');
    $('#'+type+'s .itemSelected').parent().hide();
    $('#file_actions').load('modules/file_actions.php?dir='+dir);
    if (type=='folder')
      {
        $('#files').load('modules/files.php');
        }
    }
    
// Upload file #########################################################################

function activateUploader(path, shortpath){
    $('#uploader').jqmShow();
    $('#uploader').css('width','360px');
    $('#uploader').css('margin-left','-180px');
    // Replace close button
    $('#closeUploader').attr('rel',shortpath);
    // Load file uploader
    $('#fileInput').fileUpload ({
    'uploader'       : 'images/uploader.swf',
    'script'         : 'modules/upload_handler.php',
    'cancelImg'      : 'images/cancel.png',
    'queueSizeLimit' : 5,
    'multi'          : true,
    'auto'           : false,
    'wmode'          : 'window',
    'folder'         : path
    });
}

  // refresh on close...
    function refreshonclose(path) {
      $('#files').html('<img src=images/spinner.gif />');
        $('#file_actions').html('<img src=images/spinner.gif />');
        $('#files').load('modules/files.php?dir='+path);
        $('#file_actions').load('modules/file_actions.php?dir='+path);
        $('#upregion').html('');
        $('#upregion').html('<input type="file" name="fileInput" id="fileInput" />');
        $('#uploader').jqmHide()
    }
        
        
// Pop-Up file preview window ##########################################################

var win = null;
function popUp(url) {
      var name = 'file_view';
        var w = 800;
        var h = 600;
        var features = 'resizable,scrollbars,status';
    var winl = (screen.width-w)/2;
    var wint = (screen.height-h)/2;
    if (winl < 0) winl = 0;
    if (wint < 0) wint = 0;
    var settings = 'height=' + h + ',';
    settings += 'width=' + w + ',';
    settings += 'top=' + wint + ',';
    settings += 'left=' + winl + ',';
    settings += features;
    win = window.open(url,name,settings);
    win.window.focus();
}
