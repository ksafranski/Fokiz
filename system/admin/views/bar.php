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

require_once('../controllers/bar.php'); 

?>
<?php if($pending && $_SESSION['admin_type']==0){ ?><button class="strong btn_left" style="margin-left: 10px;" onclick="modal.open('system/admin/views/publish.php?id='+$('body').attr('data-id'),500);"><?php lang('Save &amp; Publish'); ?></button><button class="btn_right" onclick="modal.open('system/admin/views/revert.php?id='+$('body').attr('data-id'),300);"><?php lang('Revert'); ?></button><?php } ?>

<?php if(!$no_edit){ ?>
<button id="adm_btn_edit" class="btn_left" rel="0"><?php lang('Edit Content'); ?></button><button class="btn_right" onclick="modal.open('system/admin/views/page_editor.php?id='+$('body').attr('data-id'),500);"><?php lang('Page Properties'); ?></button>
<?php } ?>

<button class="btn_left" onclick="modal.open('system/admin/views/assets.php',670);"><?php lang('Assets'); ?></button><?php if($_SESSION['admin_type']==0){ ?><button rel="components" id="adm_btn_components" class="btn_mid"><?php lang('Components'); ?></button><?php } ?><?php echo($modules_button); ?><button rel="resources" id="adm_btn_resources" class="btn_right"><?php lang('Resources'); ?></button>

<?php if($_SESSION['admin_type']==0){ ?>
<ul class="adm_dropdown" id="adm_components">
    <li><a onclick="modal.open('system/admin/views/pages.php',700);"><?php lang('Page Manager'); ?></a></li>
    <li><a onclick="modal.open('system/admin/views/navigation.php',600);"><?php lang('Navigation'); ?></a></li>
    <li><a onclick="modal.open('system/admin/views/system.php',500);"><?php lang('Sitewide Settings'); ?></a></li>
    <li><a onclick="modal.open('system/admin/views/users.php',500);"><?php lang('User Management'); ?></a></li>
</ul>
<?php } ?>

<ul class="adm_dropdown" id="adm_resources">
    <?php
    
    if($resource){
        foreach($resource as $key=>$val){ 
            if($val[1]==0){ // Load into modal
                echo("<li><a onclick=\"resource.load('$val[0]','$key');\">$key</a></li>"); 
            }else{ // Load in new window
                echo("<li><a href=\"$val[0]\" target=\"_blank\">$key</a></li>"); 
            }            
        }
    }else{
        echo("<li><a>");
        lang('No Resources Available');
        echo("</a></li>");
    }
    ?>
</ul>

<?php echo($modules_dropdown); ?>

<div class="right">
    <button class="btn_left" onclick="modal.open('system/admin/views/password.php',300);"><?php lang('Password'); ?></button><button class="btn_mid" onclick="location.href='<?php echo(BASE_URL); ?>logout';"><?php lang('Log Out'); ?></button><button onclick="modal.open('system/admin/views/about.php?id='+$('body').attr('data-id'),650);" class="btn_right"><img id="fokiz_logo" src="<?php echo(BASE_URL); ?>system/admin/images/fokiz_logo.png" /></button>
</div>