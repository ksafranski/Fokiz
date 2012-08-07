<?php require_once('config.php'); ?>

<button class="right" onclick="b_editor.open('new','no');">Add New Block</button>

<h1>Common Blocks</h1>

<div id="adm_error"></div>

<div id="adm_block_workspace"></div>

<span id="adm_block_edit_btns" style="display: none;">
    <button class="btn_left" id="adm_block_save" onclick="b_editor.save();">Save Block</button><button class="btn_right" onclick="b_list.display();">Return to List</button>
</span>

<button id="adm_close" onclick="modal.hide();">Close</button>

<script>

//////////////////////////////////////////////////////////////////////
// Objects
//////////////////////////////////////////////////////////////////////

workspace = $('#adm_block_workspace');
edit_buttons = $('#adm_block_edit_btns');
save_block_btn = $('#adm_block_save');

//////////////////////////////////////////////////////////////////////
// Actions
//////////////////////////////////////////////////////////////////////

b_list = {
    
    display : function(){ workspace.load('system/modules/<?php echo($module->folder); ?>/admin/list.php', function(){
        datatable.init('adm_blocks');
        edit_buttons.hide();
        errormsg.hide();
    }); },
        
    delete_item : function(block){
        var answer = confirm('This will permanently remove the item. Continue?');
        if(answer){
            $.get('system/modules/<?php echo($module->folder); ?>/admin/delete.php?block='+block,function(){
                $('tr#block_'+block).fadeOut(500);  
            });  
        }
    }
    
};

b_editor = {

    open : function(block,copy){
        workspace.load('system/modules/<?php echo($module->folder); ?>/admin/edit.php?block='+block+'&copy='+copy, function(){
            edit_buttons.show();
            errormsg.hide();
            save_block_btn.html('Save Block');
        });
    },
    
    save : function(){
        // Validate product name
        if($('input[name="name"]').val()==""){
            errormsg.show('A Name is Required');
        }else{
            errormsg.hide();
            // Get fields
            var blk_content = CKEDITOR.instances.content.getData();
            var params =  { 
                name : $('input[name="name"]').val(),
                content : blk_content
            };
            // Save
            $.post('system/modules/<?php echo($module->folder); ?>/admin/save.php',params,function(data){
                save_block_btn.html('Block Saved!');
                setTimeout(function(){ save_block_btn.html('Save Block'); },5000)
            });  
        } 
    }   
};

//////////////////////////////////////////////////////////////////////
// Init
//////////////////////////////////////////////////////////////////////

$(function(){
    b_list.display();
});

</script>