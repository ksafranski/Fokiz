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
<?php if($pending && $_SESSION['usr_type']==User::ADMIN){ ?>
    <button class="strong btn_left" style="margin-left: 10px;" onclick="modal.open('system/admin/views/publish.php?id='+$('body').attr('data-id'),500);">
        <?php echo lang('Save &amp; Publish'); ?>
    </button>
    <button class="btn_right" onclick="modal.open('system/admin/views/revert.php?id='+$('body').attr('data-id'),300);">
        <?php echo lang('Revert'); ?>
    </button>
<?php } ?>

<?php if(!$no_edit){ ?>
    <button id="adm_btn_edit" class="btn_left" rel="0">
        <?php echo lang('Edit Content'); ?>
    </button>
    <button class="btn_right" onclick="modal.open('system/admin/views/page_editor.php?id='+$('body').attr('data-id'),500);">
        <?php echo lang('Page Properties'); ?>
    </button>
<?php } ?>

<button class="btn_left" onclick="modal.open('system/admin/views/assets.php',670);"><?php echo lang('Assets'); ?></button>
<?php if($_SESSION['usr_type']==User::ADMIN){ ?>
    <button rel="components" id="adm_btn_components" class="btn_mid"><?php echo lang('Components'); ?></button>
<?php } ?>

<?php echo($modules_button); ?>

<button rel="resources" id="adm_btn_resources" class="btn_right"><?php echo lang('Resources'); ?></button>

<?php if($_SESSION['usr_type']==User::ADMIN){ ?>
<ul class="adm_dropdown" id="adm_components">
    <li><a onclick="modal.open('system/admin/views/pages.php',700);"><?php echo lang('Page Manager'); ?></a></li>
    <li><a onclick="modal.open('system/admin/views/navigation.php',600);"><?php echo lang('Navigation'); ?></a></li>
    <li><a onclick="modal.open('system/admin/views/system.php',500);"><?php echo lang('Sitewide Settings'); ?></a></li>
    <li><a onclick="modal.open('system/admin/views/users.php',500);"><?php echo lang('User Management'); ?></a></li>
</ul>
<?php } ?>

<ul class="adm_dropdown" id="adm_resources">
    <?php

    if($resource){
        foreach($resource as $key=>$val){
            // Load into modal
            if($val[1]==0){
                echo("<li><a onclick=\"resource.load('$val[0]','$key');\">$key</a></li>");
            // Load in new window
            }else{
                echo("<li><a href=\"$val[0]\" target=\"_blank\">$key</a></li>");
            }
        }
    }else{
        echo("<li><a>");
        echo lang('No Resources Available');
        echo("</a></li>");
    }
    ?>
</ul>

<?php echo($modules_dropdown); ?>

<div class="right">
    <button class="btn_left" onclick="modal.open('system/admin/views/password.php',300);"><?php echo lang('Password'); ?></button>
    <button class="btn_mid" onclick="location.href='<?php echo(BASE_URL); ?>logout';"><?php echo lang('Log Out'); ?></button>
    <button onclick="modal.open('system/admin/views/about.php?id='+$('body').attr('data-id'),650);" class="btn_right">
        <img id="fokiz_logo" src="<?php echo(BASE_URL); ?>system/admin/images/fokiz_logo.png" />
    </button>
</div>