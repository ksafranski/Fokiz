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

<button class="right" onclick="editUser('new');"><?php lang('Create New User'); ?></button>

<h1><?php lang('User Management'); ?></h1>

<input id="cur_user" type="hidden" value="<?php echo($_SESSION['admin']); ?>" />

<table id="users" class="adm_datatable">
    <thead>
        <tr>
            <th><?php lang('Username'); ?></th>
            <th><?php lang('Type'); ?></th>
            <th width="5"><?php lang('Modify'); ?></th>
            <th width="5"><?php lang('Password'); ?></th>
            <th width="5"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $user){ ?>
        <tr valign="top" id="user_<?php echo($user['id']); ?>">
            <td><?php echo($user['login']); ?></td>
            <td><?php if($user['type']==0){ lang('Administrator'); }else{ lang('Editor'); } ?></td>
            <td class="adm_datatable_center"><a onclick="editUser(<?php echo($user['id']); ?>);"><?php lang('Settings'); ?></a></td>
            <td class="adm_datatable_center"><a onclick="changePassword(<?php echo($user['id']); ?>);"><?php lang('Change'); ?></a></td>
            <td class="adm_datatable_center"><a onclick="deleteUser(<?php echo($user['id']); ?>);"><?php lang('Delete'); ?></a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<button onclick="modal.hide();"><?php lang('Close'); ?></button>

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
            alert('<?php lang('You must have at least one user in the system.'); ?>');
        }else{
            if($('#cur_user').val()==id){
                alert('<?php lang('You are currently logged in as this user. It cannot be deleted.'); ?>');
            }else{
                var answer = confirm('<?php lang('Delete the selected user permanently?'); ?>');
                if(answer){
                    $.get('system/admin/controllers/users.php?del='+id);
                    $('tr#user_'+id).remove();
                }
            }
        }
    }
</script>