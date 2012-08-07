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

checkToken(); // Check Authentication Token

    //////////////////////////////////////////////////////////////////
    // Load System
    //////////////////////////////////////////////////////////////////

    $system = new System();
    $system->Load();
    
    // Default Page Options
    
    $default_page_options = "";
    $rs = mysql_query("SELECT pag_id, pag_title FROM cms_pages ORDER BY pag_title");
    if(mysql_num_rows($rs)!=0){
        while($row=mysql_fetch_array($rs)){
            if($system->default_page==$row['pag_id']){
                $default_page_options .= "<option selected=\"selected\" value=\"" . $row['pag_id'] . "\">" . stripslashes($row['pag_title']) . "</option>";
            }else{
                $default_page_options .= "<option value=\"" . $row['pag_id'] . "\">" . stripslashes($row['pag_title']) . "</option>";
            }
        }
    }
    
    //////////////////////////////////////////////////////////////////
    // Save System
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['save'])){
        $system = new System();
        $system->title = scrub($_POST['title']);
        $system->keywords = scrub($_POST['keywords']);
        $system->description = scrub($_POST['description']);
        $system->default_page = $_POST['default_page'];
        $system->Save();
    }

?>