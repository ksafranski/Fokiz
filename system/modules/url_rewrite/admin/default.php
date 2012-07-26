<?php require_once('config.php'); ?>
<h1>URL Rewrites</h1>
<button id="new-rewrite" onclick="url_rewrite.add();">Add Rewrite Rule</button>
<br /><br />
<div id="adm_error"></div>

<div id="region">
    
</div>

<button onclick="modal.hide();">Close</button>

<script>

var url_rewrite = {
    
    list : function(){
        errormsg.hide();
        $('#region').load('system/modules/url_rewrite/admin/list.php');
    },

    add : function(){
        $('#region').load('system/modules/url_rewrite/admin/add.php');
    },
    
    save : function(){
        var pass = true;
        $('#add_rule input').each(function(){
            if($(this).val()==''){ pass = false; }
        });
        
        if(!pass){
            errormsg.show('Both fields are required');
        }else{
            $.post('system/modules/url_rewrite/admin/save.php', $('#add_rule').serialize(), function(){
                $('#add_ip input').val('');
                url_rewrite.list();
            });
        }
    },
    
    remove : function(path_old){
        var answer = confirm('Permanently remove this rewrite from list?');
        if(answer){
            $.post('system/modules/url_rewrite/admin/remove.php',{ path_old: path_old },function(){
                $('tr[data-path="'+path_old+'"]').fadeOut(200).remove();
            });
        }
    }

}

$(function(){
    url_rewrite.list();
});

</script>