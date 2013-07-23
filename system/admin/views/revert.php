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

require_once('../controllers/revert.php');

?>
<h1><?php echo lang('Revert'); ?></h1>
<p><?php echo lang('<strong>Warning:</strong> Reverting will remove all changes made and return to the current live state of the page'); ?></p>
<button class="btn_left" id="adm_btn_save" onclick="revertPage();"><?php echo lang('Revert'); ?></button><button class="btn_right" onclick="modal.hide();"><?php echo lang('Close'); ?></button>

<script>

    function revertPage(){
        $.get('system/admin/controllers/revert.php?id=<?php echo($_GET['id']); ?>',function(data){
            $('#adm_btn_save').html('<?php echo lang('Loading'); ?>...').attr('disabled', 'disabled').addClass('disabled');
            url.go(data);
        });
    }

</script>