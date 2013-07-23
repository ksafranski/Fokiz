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

require_once('../controllers/system.php');

?>
<h1><?php echo lang('Sitewide Settings'); ?></h1>

<div id="adm_error"></div>

<label><?php echo lang('Title'); ?></label>
<input type="text" name="title" value="<?php echo(escape($system->title)); ?>" />

<label><?php echo lang('Keywords'); ?></label>
<input type="text" name="keywords" value="<?php echo(escape($system->keywords)); ?>" />

<label><?php echo lang('Description'); ?></label>
<textarea name="description" rows="3"><?php echo(escape($system->description)); ?></textarea>

<label><?php echo lang('Default (Home) Page)'); ?></label>
<select name="default_page">
    <?php echo($default_page_options); ?>
</select>

<button id="adm_btn_system" class="btn_left" onclick="saveSystem();">
    <?php echo lang('Save'); ?>
</button><button class="btn_right" onclick="modal.hide();">
    <?php echo lang('Close'); ?>
</button>

<script>
    $(function(){
        $('input,textarea').keypress(function(){ changeButton('<?php echo lang('Save Changes'); ?>'); });
        $('select').change(function(){ changeButton('<?php echo lang('Save Changes'); ?>'); });
        $('input[name="title"]').keyup(function(){
            if($(this).val()==""){
                errormsg.show('<?php echo lang('Title Cannot Be Blank'); ?>');
                $('#adm_btn_system').attr('disabled', 'disabled').addClass('disabled');
            }else{
                errormsg.hide();
                $('#adm_btn_system').removeAttr('disabled').removeClass('disabled');
            }
        });
    });

    function changeButton(t){ $('#adm_btn_system').html(t); }

    function saveSystem(){
        var params = {
            title : $('input[name="title"]').val(),
            keywords : $('input[name="keywords"]').val(),
            description : $('textarea[name="description"]').val(),
            default_page : $('select[name="default_page"]').val()
        };
        $.post('system/admin/controllers/system.php?save=t',params,function(){
            changeButton('<?php echo lang('SAVED'); ?>');
        });
    }

</script>