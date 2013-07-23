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
permitUser(User::ADMIN);

checkToken(); // Check Authentication Token

    //////////////////////////////////////////////////////////////////
    // Return Level List
    //////////////////////////////////////////////////////////////////

    if(isset($_POST['list'])){
        $nav = new Navigation();
        $nav->parent = $_POST['list'];
        $list = $nav->ReturnLevelList();
        // Format output
        $output = "";
            foreach($list as $item){
                $id = escape($item['id']);
                $url = escape($item['url']);
                $title = escape($item['title']);
                $output .= "<option value=\"" . $id . "|!|" . $url . "|!|" . $title . "\">" . $title . "</option>";
            }
        echo($output);
    }

    //////////////////////////////////////////////////////////////////
    // Create Object
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['create'])){
        $nav = new Navigation();
        $nav->id = "new";
        $nav->title = $_POST['title'];
        $nav->url = $_POST['url'];
        $nav->parent = $_POST['parent'];
        echo($nav->Save());
    }

    //////////////////////////////////////////////////////////////////
    // Update Object
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['update'])){
        $nav = new Navigation();
        $nav->id = $_POST['id'];
        $nav->title = $_POST['title'];
        $nav->url = $_POST['url'];
        $nav->parent = $_POST['parent'];
        $nav->Save();
    }

    //////////////////////////////////////////////////////////////////
    // Move Object
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['move'])){
        $nav = new Navigation();
        $nav->id = $_POST['id'];
        $nav->parent = $_POST['parent'];
        $nav->move_dir = $_POST['direction'];
        $nav->Move();

    }

    //////////////////////////////////////////////////////////////////
    // Delete Object
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['delete'])){
        $nav = new Navigation();
        $nav->id = $_GET['delete'];
        $nav->Delete();
    }

?>