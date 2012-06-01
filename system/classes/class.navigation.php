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
        
        function buildStructure($parent=0,$active_obj) {
            $rs = mysql_query("SELECT * FROM cms_navigation WHERE 
                  nav_parent=$parent ORDER BY nav_order");
            $items = array();
            while($row = mysql_fetch_array($rs)){
                $attr = ""; if($active_obj==$row['nav_url']){ $attr = " class=\"active\""; }
                $items[] = '<li><a' . $attr . ' href="' . $row['nav_url'] . '">'.stripslashes($row['nav_title'])."</a>".buildStructure($row['nav_id'],$active_obj).'</li>';
            }
            if(count($items)){
                return '<ul>'.implode('', $items).'</ul>';
            }else{
                return '';
            }
        }   
        return buildStructure(0,$this->active);
        
    }
    
    //////////////////////////////////////////////////////////////////
    // GET NODE
    //////////////////////////////////////////////////////////////////
    
    public function GetNode(){
        $rs = mysql_query("SELECT * FROM cms_navigation WHERE nav_id=" . $this->id);
        if(mysql_num_rows($rs)!=0){
            $row = mysql_fetch_array($rs);
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
        $list = array();
        $rs = mysql_query("SELECT * FROM cms_navigation WHERE nav_parent=" . $this->parent .
              " ORDER BY nav_order");
        if(mysql_num_rows($rs)!=0){
            while($row=mysql_fetch_array($rs)){
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
        // Create new node ///////////////////////////////////////////
        if($this->id=="new"){
            // Get last position
            $rs = mysql_query("SELECT MAX(nav_order) as max_pos FROM cms_navigation WHERE nav_parent=" . $this->parent);
            if(mysql_num_rows($rs)==0){
                $this->order = 0;
            }else{
                $row = mysql_fetch_array($rs);
                $this->order = $row['max_pos']+1;
            }
            $rs = mysql_query("INSERT INTO cms_navigation(nav_parent,nav_title,nav_url,nav_order) VALUES (" 
                . $this->parent . ",'" . scrub($this->title) . "','" . $this->url . "'," . $this->order . ")");
            return mysql_insert_id();
        // Update node ///////////////////////////////////////////////
        }else{
            $rs = mysql_query("UPDATE cms_navigation SET nav_title='" . scrub($this->title) 
                . "', nav_url='" . scrub($this->url) . "' WHERE nav_id=" . $this->id);
        }
    }
    
    //////////////////////////////////////////////////////////////////
    // MOVE NODE POSITION
    //////////////////////////////////////////////////////////////////
    
    public function Move(){
                
        $rs = mysql_query("SELECT nav_order FROM cms_navigation WHERE nav_id=" . $this->id);
        $row = mysql_fetch_array($rs);
        $cur_pos = $row['nav_order'];
        if($this->move_dir==0){ // Move object up
            $rs = mysql_query("SELECT nav_id, nav_order FROM cms_navigation WHERE nav_order<" . $cur_pos . " AND nav_parent=" . $this->parent . " ORDER BY nav_order DESC");
        }else{ // Move object down
            $rs = mysql_query("SELECT nav_id, nav_order FROM cms_navigation WHERE nav_order>" . $cur_pos . " AND nav_parent=" . $this->parent . " ORDER BY nav_order");
        }

        if(mysql_num_rows($rs)!=0){
            $row = mysql_fetch_array($rs);
            $replace = $row['nav_id'];
            $new_pos = $row['nav_order'];
            // Change Up
            mysql_query("UPDATE cms_navigation SET nav_order=$new_pos WHERE nav_id=" . $this->id);
            mysql_query("UPDATE cms_navigation SET nav_order=$cur_pos WHERE nav_id=$replace");
        }
        
    }
    
    //////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////
    
    public function Delete(){
        function deleteChildren($id){
            $rs = mysql_query("SELECT nav_id FROM cms_navigation WHERE nav_parent=$id");
            if(mysql_num_rows($rs)!=0){
                while($row=mysql_fetch_array($rs)){
                    deleteChildren($row['nav_id']);
                    mysql_query("DELETE FROM cms_navigation WHERE nav_id=" . $row['nav_id']);
                }
            }
        }
        // Delete recursive children
        deleteChildren($this->id);
        // Delete object
        $rs = mysql_query("DELETE FROM cms_navigation WHERE nav_id=" . $this->id);
    }
    
}

?>