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

class Navigation {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $id            = 0;
    public $parent        = 0;
    public $title         = "";
    public $url           = "#";
    public $order         = 0;
    public $move_dir      = 0;

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    // ------------------------------------------------------------ //

    //////////////////////////////////////////////////////////////////
    // GET STRUCTURE
    //////////////////////////////////////////////////////////////////

    public function GetStructure(){
        function buildStructure($parent=0, $active_obj) {
            global $conn;
            $rs = $conn->prepare("SELECT * FROM cms_navigation WHERE nav_parent=? ORDER BY nav_order");
            $rs->execute(array($parent));
            $items = array();
            while($row = $rs->fetch()){
                $attr = "";
                if($active_obj == $row['nav_url']){
                    $attr = " class=\"active\"";
                }
                $liItem = '<li>';
                $liItem .= '<a' . $attr . ' href="' . $row['nav_url'] . '">';
                $liItem .= escape(stripslashes($row['nav_title']));
                $liItem .= "</a>";
                $liItem .= buildStructure($row['nav_id'],$active_obj);
                $liItem .= '</li>';
                $items[] = $liItem;
            }
            if(count($items)){
                return '<ul>'.implode('', $items).'</ul>';
            }else{
                return '';
            }
        }
        return buildStructure(0, $this->active);
    }

    //////////////////////////////////////////////////////////////////
    // GET NODE
    //////////////////////////////////////////////////////////////////

    public function GetNode(){
        global $conn;
        $rs = $conn->prepare("SELECT * FROM cms_navigation WHERE nav_id=?");
        $rs->execute(array($this->id));
        if($rs->rowCount() != 0){
            $row = $rs->fetch();
            $this->parent = $row['nav_parent'];
            $this->title = stripslashes($row['nav_title']);
            $this->url = $row['nav_url'];
            $this->order = $row['nav_order'];
        }
    }

    //////////////////////////////////////////////////////////////////
    // RETURN NAV LEVEL LIST (Edit)
    //////////////////////////////////////////////////////////////////

    public function ReturnLevelList(){
        global $conn;
        $list = array();
        $rs = $conn->prepare("SELECT * FROM cms_navigation WHERE nav_parent=? ORDER BY nav_order");
        $rs->execute(array($this->parent));
        if($rs->rowCount() != 0){
            while($row = $rs->fetch()){
                $list[] = array(
                    "id" => $row['nav_id'],
                    "parent" => $row['nav_parent'],
                    "title" => stripslashes($row['nav_title']),
                    "url" => $row['nav_url'],
                    "order" => $row['nav_order']
                );
            }
        }
        return $list;
    }

    //////////////////////////////////////////////////////////////////
    // SAVE
    //////////////////////////////////////////////////////////////////

    public function Save(){
        global $conn;
        // Create new node ///////////////////////////////////////////
        if($this->id=="new"){
            // Get last position
            $rs = $conn->prepare("SELECT MAX(nav_order) as max_pos FROM cms_navigation WHERE nav_parent=?");
            $rs->execute(array($this->parent));
            if($rs->rowCount() == 0){
                $this->order = 0;
            }else{
                $row = $rs->fetch();
                $this->order = $row['max_pos']+1;
            }
            $rs = $conn->prepare("INSERT INTO cms_navigation(nav_parent,nav_title,nav_url,nav_order) VALUES (?,?,?,?)");
            $rs->execute(array($this->parent, $this->title, $this->url, $this->order));
            return $conn->lastInsertId();
        // Update node ///////////////////////////////////////////////
        }else{
            $rs = $conn->prepare("UPDATE cms_navigation SET nav_title=?, nav_url=? WHERE nav_id=?");
            $rs->execute(array($this->title, $this->url, $this->id));
        }
    }

    //////////////////////////////////////////////////////////////////
    // MOVE NODE POSITION
    //////////////////////////////////////////////////////////////////

    public function Move(){
        global $conn;

        $rs = $conn->prepare("SELECT nav_order FROM cms_navigation WHERE nav_id=?");
        $rs->execute(array($this->id));
        $row = $rs->fetch();
        $cur_pos = $row['nav_order'];
        // Move object up
        if($this->move_dir==0){
            $rs = $conn->prepare("SELECT nav_id, nav_order FROM cms_navigation WHERE nav_order<? AND nav_parent=? ORDER BY nav_order DESC");
            $rs->execute(array($cur_pos, $this->parent));
        }else{
            $rs = $conn->prepare("SELECT nav_id, nav_order FROM cms_navigation WHERE nav_order>? AND nav_parent=? ORDER BY nav_order");
            $rs->execute(array($cur_pos, $this->parent));
        }

        if($rs->rowCount() != 0){
            $row = $rs->fetch();
            $replace = $row['nav_id'];
            $new_pos = $row['nav_order'];
            // Change Up
            $rs2 = $conn->prepare("UPDATE cms_navigation SET nav_order=$new_pos WHERE nav_id=?");
            $rs2->execute(array($this->id));
            $rs3 = $conn->prepare("UPDATE cms_navigation SET nav_order=$cur_pos WHERE nav_id=?");
            $rs3->execute(array($replace));
        }

    }

    //////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////

    public function Delete(){
        global $conn;

        function deleteChildren($id){
            global $conn;

            $rs = $conn->prepare("SELECT nav_id FROM cms_navigation WHERE nav_parent=?");
            $rs->execute(array($id));
            if($rs->rowCount() != 0){
                while($row = $rs->fetch()){
                    deleteChildren($row['nav_id']);
                    $rs2 = $conn->prepare("DELETE FROM cms_navigation WHERE nav_id=?");
                    $rs2->execute(array($row['nav_id']));
                }
            }
        }
        // Delete recursive children
        deleteChildren($this->id);
        // Delete object
        $rs = $conn->prepare("DELETE FROM cms_navigation WHERE nav_id=?");
        $rs->execute(array($this->id));
    }

}

?>