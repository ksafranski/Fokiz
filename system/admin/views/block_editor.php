<?php

/*
 * This file is part of the Fokiz Content Management System
 * <http://www.fokiz.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once('../controllers/block_editor.php');

?>
<h1>Content Editor</h1>
<input type="hidden" name="id" value="<?php echo($block->id); ?>" />
<textarea class="hide" name="adm_content_editor"><?php echo($block->content); ?></textarea>
<button onclick="saveBlock();" id="adm_btn_save" class="btn_left"><?php echo lang('Save'); ?></button><button class="btn_right" onclick="closeEditor();"><?php echo lang('Close'); ?></button>

<script>

    saved = false;

    var codemirror_rootpath = 'system/admin/editor/codemirror/';
    var instance = "adm_content_editor";
    if (CKEDITOR.instances[instance]) { CKEDITOR.remove(CKEDITOR.instances[instance]); } // Cleanup (AJAX)
    var editor = CKEDITOR.replace(instance,{
         filebrowserBrowseUrl : 'system/admin/editor/filemgr/index.php?type=File',
         filebrowserImageBrowseUrl : 'system/admin/editor/filemgr/index.php?type=Images',
         filebrowserFlashBrowseUrl : 'system/admin/editor/filemgr/index.php?type=Flash',
         filebrowserWindowWidth : '720',
         filebrowserWindowHeight : '615',
         language: '<?php echo(strtolower(LANGUAGE)); ?>'
    });

    editor.on('focus', function() {
        changeButton('Save');
        editor.on('getSnapshot', function() { changeButton('<?php echo lang('Save Changes'); ?>'); });
        editor.on('dialogHide', function() { changeButton('<?php echo lang('Save Changes'); ?>'); });
    });

    function changeButton(t){ $('#adm_btn_save').html(t); }

    function saveBlock(){
        var content = CKEDITOR.instances.adm_content_editor.getData();
        var id = $('input[name="id"]').val();
        var params = { id: id, content : content };
        $.post('system/admin/controllers/block_editor.php?save=t',params,function(){
            changeButton('<?php echo lang('Content Saved'); ?>');
            saved = true;
        });
    }

    function closeEditor(){
        if(saved==true){
            url.refresh();
        }else{
            modal.hide();
        }
    }

</script>
