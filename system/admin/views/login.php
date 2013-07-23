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

require_once('../controllers/login.php');

?>
<div id="adm_login">
    <form method="post" action="" onsubmit="return false;">
    <span id="adm_error"></span>
    <input value="<?php echo lang('Username'); ?>" name="login" type="text" />
    <input value="<?php echo lang('Password'); ?>" name="password" type="password" />
    <button onclick="processAuth();" id="adm_btn_login"><?php echo lang('Login'); ?></button>
    </form>
</div>
<script>
    $(function(){

        // Check for previous credentials in localStorage
        if(localStorage.getItem("username") !== null){
            $('input[name="login"]').val(localStorage.getItem("username"));
            $('input[name="password"]').val('').focus();
        }

        // Clear LocalStorage
        localStorage.clear();

        $('input[name="login"], input[name="password"]').keypress(function(e){
            var code = (e.keyCode ? e.keyCode : e.which);
            if(code == 13){ processAuth(); }
        });
        $('input[name="login"], input[name="password"]').focus(function(){
            this.select();
        });
    });

    function processAuth(){
        // Get Values
        var login = $('input[name="login"]').val();
        var password = $('input[name="password"]').val();
        // Post
        $.post('system/admin/controllers/login.php',
               { l : login, p : password },
               function(data){
                   if(data==0){
                       errormsg.show('<?php echo lang('Failed Login Attempt'); ?>');
                       $('input[name="login"]').focus();
                   }else{
                       localStorage.username = login;
                       url.refresh();
                   }
               }
        );
    }
</script>