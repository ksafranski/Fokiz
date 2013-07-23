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

require_once('../controllers/page_editor.php');

?>

<h1><?php echo lang('Page Properties'); ?></h1>

<div id="adm_error"></div>

<input type="hidden" name="id" value="<?php echo(escape($page->id)); ?>" />

<label><?php echo lang('Title'); ?></label>
<input type="text" name="title" value="<?php echo(escape($page->title)); ?>" autofocus="autofocus" />

<label><?php echo lang('Keywords'); ?></label>
<input type="text" name="keywords" value="<?php echo(escape($page->keywords)); ?>" />

<label><?php echo lang('Description'); ?></label>
<textarea style="min-height: 50px; height: 50px;" name="description"><?php echo(escape($page->description)); ?></textarea>

<label><?php echo lang('Template'); ?></label>
<select name="template">
    <?php echo($template_options); ?>
</select>

<label><?php echo lang('Tags'); ?></label>
<select name="tags[]" multiple="multiple" size="5" style="margin-bottom: 0;">
    <?php echo($tag_options); ?>
</select>
<span class="adm_note"><?php echo lang('CTRL+Click to select multiple or'); ?> <a style="font-weight: bold;" onclick="$('#addtag').slideDown(200);$('#addtag>input').focus();"><?php echo lang('Add Tag'); ?></a></span>
<div id="addtag" style="display: none;">
<label><?php echo lang('Add Tag'); ?></label>
<input type="text" name="addtag" />
</div>

<label><?php echo lang('Feed'); ?></label>
<select name="feed">
    <?php echo($feed_options); ?>
</select>

<div class="clear"></div>

<button class="btn_left" id="adm_btn_save" onclick="savePage();"><?php echo lang('Save'); ?></button><button class="btn_right" onclick="closeEditor();"><?php echo lang('Close'); ?></button>

<script>

    saved = false;

    $(function(){
        $('input,textarea').keypress(function(){ changeButton('<?php echo lang('Save Changes'); ?>'); });
        $('select').change(function(){ changeButton('<?php echo lang('Save Changes'); ?>'); });
        $('input[name="title"]').keyup(function(){ checkTitle($('input[name="id"]').val(),$(this).val()); });
        $('input[name="addtag"]').keypress(function(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            if(code == 13) { addTag($(this).val()); }
        });
    });

    function changeButton(t){ $('#adm_btn_save').html(t); }

    function savePage(){
        if(validatePage()){
            var params = {
                id: $('input[name="id"]').val(),
                title : $('input[name="title"]').val(),
                template : $('select[name="template"]').val(),
                keywords : $('input[name="keywords"]').val(),
                description : $('textarea[name="description"]').val(),
                tags : $('select[name="tags[]"]').val(),
                feed : $('select[name="feed"]').val()
            };
            $.post('system/admin/controllers/page_editor.php?save=t',params,function(data){
                changeButton('Page Saved');
                saved = true;
                if(data){ url.go(data); } // New page created, redirect
            });
        }
    }

    function closeEditor(){
        if(saved==true){
            url.refresh();
        }else{
            modal.hide();
        }
    }

    function checkTitle(i,t){
        var params = {
            id : i,
            title : t
        }
        $.post('system/admin/controllers/page_editor.php?checktitle=t',params,function(data){
            if(data==1){
                errormsg.show('<?php echo lang('Page Title Already In Use'); ?>');
                $('#adm_btn_save').attr('disabled', 'disabled').addClass('disabled');
            }else{
                errormsg.hide();
                $('#adm_btn_save').removeAttr('disabled').removeClass('disabled');
            }
        });
    }

    function validatePage(){
        var pass = true;
        if($('input[name="title"]').val()=="" || $('input[name="keywords"]').val()=="" || $('textarea[name="description"]').val()==""){
            pass = false;
        }
        if(!pass){
            errormsg.show('<?php echo lang('Title, Keywords and Description Must Be Completed'); ?>');
        }else{
            errormsg.hide();
        }
        return pass;
    }

    function addTag(t){
        $('select[name="tags[]"]')
         .prepend($("<option></option>")
         .attr("value",t)
         .attr("selected","selected")
         .text(t));
        $('input[name="addtag"]').val('');
    }

</script>