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

checkToken(); // Check Authentication Token

    //////////////////////////////////////////////////////////////////
    // Build AutoComplete List
    //////////////////////////////////////////////////////////////////

    $rs = $conn->prepare("SELECT pag_title, pag_url FROM cms_pages WHERE pag_title LIKE ?");
    $rs->bindValue(1, '%$_GET["q"]%', PDO::PARAM_STR);
    $rs->execute();
    if($rs->rowCount() != 0){
        while($row = $rs->fetch()){
            echo(FOKIZ_PATH.$row['pag_url'] . ":::" . stripslashes($row['pag_title']) . "\n");
        }
    }

?>