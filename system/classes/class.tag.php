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

class Tag {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $id            = 0;
    public $title         = "";
    public $pag_id        = 0;

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    // ------------------------------------------------------------ //

    //////////////////////////////////////////////////////////////////
    // ADD TAG
    //////////////////////////////////////////////////////////////////

    public function Add(){
        global $conn;
        $rs = $conn->prepare("INSERT INTO cms_tags (tag_title,tag_pag_id) VALUES (?,?)");
        $rs->execute(array($this->title, $this->pag_id));
    }

    //////////////////////////////////////////////////////////////////
    // REMOVE TAG
    //////////////////////////////////////////////////////////////////

    public function Remove(){
        global $conn;
        // Delete by title
        if($this->title != ""){
            $rs = $conn->prepare("DELETE FROM cms_tags WHERE tag_title=?");
            $rs->execute(array($this->title));
        }
        // Delete by associated page id
        if($this->pag_id != 0){
            $rs = $conn->prepare("DELETE FROM cms_tags WHERE tag_pag_id=?");
            $rs->execute(array($this->pag_id));
        }
    }

    //////////////////////////////////////////////////////////////////
    // LIST TAGS
    //////////////////////////////////////////////////////////////////

    public function GetList(){
        global $conn;
        $tags = array();

        // Get full list
        if($this->pag_id == 0){
            $rs = $conn->query("SELECT DISTINCT tag_title FROM cms_tags ORDER BY tag_title");
        // Get page specific
        }else{
            $rs = $conn->prepare("SELECT DISTINCT tag_title FROM cms_tags WHERE tag_pag_id=? ORDER BY tag_title");
            $rs->execute(array($this->pag_id));
        }

        if($rs->rowCount() != 0){
            while($row = $rs->fetch()){
                $tags[] = stripslashes($row['tag_title']);
            }
        }
        return $tags;
    }
}
