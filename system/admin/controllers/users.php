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
    // List Users
    //////////////////////////////////////////////////////////////////

    $users = new User();
    $list = $users->GetList();

    //////////////////////////////////////////////////////////////////
    // Delete User
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['del']) && $_SESSION['usr_id'] != $_GET['del']){
        $user = new User();
        $user->id = $_GET['del'];
        $user->Delete();
    }

?>