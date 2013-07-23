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

require_once('../controllers/users.php');

?>

<h1><?php echo lang('User Management'); ?></h1>

<button onclick="editUser('new');"><?php echo lang('Create New User'); ?></button>

<div class="adm_v_spacer"></div>

<input id="cur_user" type="hidden" value="<?php echo(escape($_SESSION['usr_id'])); ?>" />

<table id="users" class="adm_datatable">
    <thead>
        <tr>
            <th><?php echo lang('Username'); ?></th>
            <th><?php echo lang('Type'); ?></th>
            <th width="5" class="no-sort"></th>
            <th width="5" class="no-sort"></th>
            <th width="5" class="no-sort"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $user){ ?>
        <tr valign="top" id="user_<?php echo(escape($user['id'])); ?>">
            <td><?php echo(escape($user['login'])); ?></td>
            <td>
                <?php
                    if($user['type']==0){
                        echo lang('Administrator');
                    }else{
                        echo lang('Editor');
                    }
                ?>
            </td>
            <td class="adm_datatable_center">
                <a onclick="editUser(<?php echo(escape($user['id'])); ?>);"><?php echo lang('Settings'); ?></a>
            </td>
            <td class="adm_datatable_center">
                <a onclick="changePassword(<?php echo(escape($user['id'])); ?>);"><?php echo lang('Password'); ?></a>
            </td>
            <td class="adm_datatable_center">
                <a onclick="deleteUser(<?php echo(escape($user['id'])); ?>);"><?php echo lang('Delete'); ?></a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<button onclick="modal.hide();"><?php echo lang('Close'); ?></button>

<script>
    $(function(){
        datatable.init('users');
    });

    function editUser(id){
        modal.open('system/admin/views/user_editor.php?id='+id,400);
    }

    function changePassword(id){
        modal.open('system/admin/views/password.php?id='+id,300);
    }

    function deleteUser(id){

        var count = $('.adm_datatable tr').length;
        if(count==1){
            alert('<?php echo lang('You must have at least one user in the system.'); ?>');
        }else{
            if($('#cur_user').val()==id){
                alert('<?php echo lang('You are currently logged in as this user. It cannot be deleted.'); ?>');
            }else{
                var answer = confirm('<?php echo lang('Delete the selected user permanently?'); ?>');
                if(answer){
                    $.get('system/admin/controllers/users.php?del='+id);
                    $('tr#user_'+id).remove();
                }
            }
        }
    }
</script>