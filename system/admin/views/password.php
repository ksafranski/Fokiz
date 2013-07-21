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

require_once('../controllers/password.php');

?>
<h1><?php echo lang('Change Password'); ?></h1>

<input type="hidden" name="id" value="<?php echo($usr_id); ?>" />

<div id="adm_error"></div>
<label><?php echo lang('New Password'); ?></label>
<input type="password" name="password1" autofocus="true" />

<label><?php echo lang('Verify New Password'); ?></label>
<input type="password" name="password2" />

<button id="adm_btn_password" class="btn_left">
    <?php echo lang('Change Password'); ?>
</button><button class="btn_right"
    <?php if($change_type==SELF_CHANGE){ ?>
        onclick="modal.hide();"
    <?php }else{ ?>
        onclick="modal.open('system/admin/views/users.php',500);"
    <?php } ?>><?php echo lang('Close'); ?>
</button>

<script>

    $(function(){
        $('#adm_btn_password').click(function(){ changePassword(); });
        $('input[name="password1"], input[name="password2"]').keypress(function(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            if(code == 13) { changePassword(); }
        });
    });

    function changePassword(){
        var id = $('input[name="id"]').val();
        var p1 = $('input[name="password1"]').val();
        var p2 = $('input[name="password2"]').val();
        if(p1!=p2){
            errormsg.show('<?php echo lang('Passwords Do Not Match'); ?>');
        }else if(!checkPassStrength()){
            errormsg.show('<?php echo lang('Password Minimum Of 8 Characters'); ?>');
        }else{
            $.post('system/admin/controllers/password.php',{ i : id, p : p1 },function(){
                $('input[name="password1"], input[name="password2"]').val('');
                errormsg.show('<?php echo lang('Password Changed!'); ?>');
            });
        }
    }

    function checkPassStrength(){
        var p1 = $('input[name="password1"]').val();
        if(p1.length<8){ return false; }
        else{ return true; }
    }

</script>