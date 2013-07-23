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

require_once('../controllers/user_editor.php');
?>

<h1><?php echo lang('User Editor'); ?></h1>

<input type="hidden" name="id" value="<?php echo($user->id); ?>" />

<div id="adm_error"></div>
<label><?php echo lang('Username'); ?></label>
<input type="text" name="login" autofocus="true" value="<?php echo($user->login); ?>" />

<label><?php echo lang('Account Type'); ?></label>
<select name="type">
    <?php echo($type_selections); ?>
</select>

<div<?php if($user->id!="new"){ echo(" style=\"display:none\""); } ?>>

<label><?php echo lang('Password'); ?></label>
<input type="password" name="password1" />

<label><?php echo lang('Verify Password'); ?></label>
<input type="password" name="password2" />

</div>

<button id="adm_btn_save" class="btn_left" onclick="saveUser();">
    <?php echo lang('Save'); ?>
</button><button class="btn_right" onclick="modal.open('system/admin/views/users.php',500);">
    <?php echo lang('Close'); ?>
</button>

<script>

    $(function(){
        $('input[name="login"]').keyup(function(){
            checkUniqueLogin($('input[name="id"]').val(),$('input[name="login"]').val())
        });
    });

    function saveUser(){
        var id = $('input[name="id"]').val();
        var l = $('input[name="login"]').val();
        var t = $('select[name="type"]').val();
        var p1 = $('input[name="password1"]').val();
        var p2 = $('input[name="password2"]').val();
        if(p1!=p2){
            errormsg.show("<?php echo lang('Passwords Do Not Match'); ?>");
        }else if(!checkPassStrength() && id=="new"){
            errormsg.show("<?php echo lang('Password Minimum Of 8 Characters'); ?>");
        }else if(l==""){
            errormsg.show("<?php echo lang('Username is Required'); ?>");
        }else{
            $.post('system/admin/controllers/user_editor.php',{ i : id, t: t, l : l,  p : p1 },function(){
                modal.open('system/admin/views/users.php',500);
            });
        }
    }

    function checkPassStrength(){
        var p1 = $('input[name="password1"]').val();
        if(p1.length<8){ return false; }
        else{ return true; }
    }

    function checkUniqueLogin(i,l){
        if(i=="new"){ i=0; }
        var params = {
            i : i,
            l : l
        }
        $.post('system/admin/controllers/user_editor.php?checklogin=t',params,function(data){
            if(data==1){
                errormsg.show('<?php echo lang('Username Already In Use'); ?>');
                $('#adm_btn_save').attr('disabled', 'disabled').addClass('disabled');
                return false;
            }else{
                errormsg.hide();
                $('#adm_btn_save').removeAttr('disabled').removeClass('disabled');
                return true;
            }
        });
    }

</script>

