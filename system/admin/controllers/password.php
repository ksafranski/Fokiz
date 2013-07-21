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
permitUser(User::ADMIN, User::EDITOR);

checkToken(); // Check Authentication Token

    //////////////////////////////////////////////////////////////////
    // Determine Self or Admin Change
    //////////////////////////////////////////////////////////////////
    const ADMIN_CHANGE = 0;
    const SELF_CHANGE = 1;

    if(!empty($_GET['id'])){
        $usr_id = $_GET['id'];
        $change_type = ADMIN_CHANGE;
    }else{
        $usr_id = $_SESSION['usr_id'];
        $change_type = SELF_CHANGE;
    }

    //////////////////////////////////////////////////////////////////
    // Save Password
    //////////////////////////////////////////////////////////////////
    if(!empty($_POST['p'])
       && ($_SESSION['usr_id'] == $_POST['i']  || $_SESSION['usr_type'] == User::ADMIN)
    ){
        $user = new User();
        $user->id = $_POST['i'];
        $user->password = $_POST['p'];

        $user->ChangePassword();
    }

?>