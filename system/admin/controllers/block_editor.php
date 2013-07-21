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
    // Load Block (Temp)
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['id'])){
        $block = new Block();
        $block->id = $_GET['id'];
        $block->Load();
    }

    //////////////////////////////////////////////////////////////////
    // Save Block (Temp)
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['save'])){
        $block = new Block();
        $block->temp = true;
        $block->id = $_POST['id'];
        $block->content = $_POST['content'];
        $block->Save();
    }


?>