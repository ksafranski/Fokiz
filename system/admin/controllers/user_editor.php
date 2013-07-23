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
    // USER TYPES
    //////////////////////////////////////////////////////////////////

    $usr_type[User::ADMIN] = lang('Administrator');
    $usr_type[User::EDITOR] = lang('Editor');

    //////////////////////////////////////////////////////////////////
    // Get Information
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['id'])){

        $user = new User();

        if($_GET['id']=="new"){
            $user->id = "new";
            $user->login = "";
            $user->type = User::EDITOR;
        }else{
            $user->id = $_GET['id'];
            $user->GetAccount();
        }

        // Build user-type selector
        $type_selections = "";
        foreach($usr_type as $key=>$type){
            $type_selections .= "<option ";

            if($user->type==$key){
                $type_selections .= "selected=\"selected\" ";
            }

            $type_selections .= "value=\"$key\">$type</option>";
        }

    }

    //////////////////////////////////////////////////////////////////
    // Save Account
    //////////////////////////////////////////////////////////////////

    if(!empty($_POST['i'])){
        $user = new User();
        $user->id = $_POST['i'];
        $user->login = $_POST['l'];
        $user->type = $_POST['t'];
        $user->password = $_POST['p'];
        $user->Save();
    }

    //////////////////////////////////////////////////////////////////
    // Check Duplicate Login
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['checklogin'])){
        $user = new User();
        $user->id = $_POST['i'];
        $user->login = $_POST['l'];
        // Return
        echo($user->CheckLogin());
    }

?>