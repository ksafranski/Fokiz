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

require_once('../controllers/publish.php');

?>
<h1><?php echo lang('Save &amp; Publish'); ?></h1>
<p><?php echo lang('Proceeding will make the content and all changes to this page publicly available'); ?></p>
<input type="hidden" name="id" value="<?php echo($_GET['id']); ?>" />
<label><?php echo lang('Notes'); ?></label>
<textarea name="notes" cols="3"></textarea>
<button class="btn_left" id="adm_btn_save" onclick="publishPage();"><?php echo lang('Save &amp; Publish'); ?></button><button class="btn_right" onclick="modal.hide();"><?php echo lang('Close'); ?></button>

<script>

    function publishPage(){
        var params = {
            id : $('input[name="id"]').val(),
            notes : $('textarea[name="notes"]').val()
        }
        $.post('system/admin/controllers/publish.php?publish=t',params,function(data){
            $('#adm_btn_save').html('Publishing...').attr('disabled', 'disabled').addClass('disabled');
            url.go(data);
        });
    }

</script>