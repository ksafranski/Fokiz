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

require_once('../../../config.php');
global $conn;

$pag_id = $_GET['page'];

    //////////////////////////////////////////////////////////////////
    // Check Login
    //////////////////////////////////////////////////////////////////

    if(empty($_SESSION['usr_id'])){
        require_once('login.php');
        exit();
    }else{
        $_SESSION['cur_page'] = $_GET['page'];
    }

    //////////////////////////////////////////////////////////////////
    // Determine if page is pending changes
    //////////////////////////////////////////////////////////////////

    $pending = false;

    // Check page edits
    $rs = $conn->prepare("SELECT ptp_id FROM cms_pages_temp WHERE ptp_pag_id=?");
    $rs->execute(array($pag_id));
    if($rs->rowCount() != 0){ $pending = true; }

    // Check block edits
    $rs = $conn->prepare("SELECT map_blk_id FROM cms_mapping WHERE map_pag_id=?");
    $rs->execute(array($pag_id));
    if($rs->rowCount() != 0){
        while($row = $rs->fetch()){
            $rsCheckBlock = $conn->prepare("SELECT btp_id FROM cms_blocks_temp WHERE btp_blk_id=?");
            $rsCheckBlock->execute(array($row['map_blk_id']));
            if($rsCheckBlock->rowCount() != 0){ $pending = true; break; }
        }
    }

    //////////////////////////////////////////////////////////////////
    // Determine No_Edit (404)
    //////////////////////////////////////////////////////////////////

    $no_edit = false;
    if($pag_id==0){ $no_edit=true; }

    //////////////////////////////////////////////////////////////////
    // Load Modules
    //////////////////////////////////////////////////////////////////

    $display_modules = false;
    $modules_button = "";
    $modules_dropdown = "";

    foreach(glob('../../modules/*', GLOB_ONLYDIR) as $dir) {
        $dir = str_replace("../../modules/","",$dir);
        if(file_exists("../../modules/$dir/admin/config.php")){
            $display_modules = true;
            $check_config = true;
            require("../../modules/$dir/admin/config.php");
            $modules[] = array(
                'folder'=>$dir,
                'name'=>$module_admin->name,
                'load'=>$module_admin->load,
                'width'=>$module_admin->dialog_width
            );
        }
    }

    if($display_modules==true){
        // Create button
        $modules_button = "<button rel=\"modules\" id=\"adm_btn_modules\" class=\"btn_mid\">" . lang('Modules') .  "</button>";

        // Create Drop-Down
        $modules_dropdown = "<ul class=\"adm_dropdown\" id=\"adm_modules\">";
        foreach($modules as $module){
            $modules_dropdown .= "<li><a onclick=\"modal.open('system/modules/" . $module['folder'] . "/admin/" . $module['load'] . "'," . $module['width'] . ");\">" . $module['name'] . "</a></li>";
        }
        $modules_dropdown .= "</ul>";
    }

?>