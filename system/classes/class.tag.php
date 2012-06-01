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
        $rs = mysql_query("INSERT INTO cms_tags (tag_title,tag_pag_id) 
              VALUES ('" . scrub($this->title) . "'," . $this->pag_id . ")");
    }
    
    //////////////////////////////////////////////////////////////////
    // REMOVE TAG
    //////////////////////////////////////////////////////////////////
    
    public function Remove(){
        // Delete by title
        if($this->title!=""){
            $rs = mysql_query("DELETE FROM cms_tags WHERE tag_title='" . scrub($this->title) . "'");
        }
        // Delete by associated page id
        if($this->pag_id!=0){
            $rs = mysql_query("DELETE FROM cms_tags WHERE tag_pag_id=" . $this->pag_id);
        }
    }
    
    //////////////////////////////////////////////////////////////////
    // LIST TAGS
    //////////////////////////////////////////////////////////////////
    
    public function GetList(){
        $tags = array();
        if($this->pag_id==0){ // Get full list
            $rs = mysql_query("SELECT DISTINCT tag_title FROM cms_tags ORDER BY tag_title");
        }else{ // Get page specific
            $rs = mysql_query("SELECT DISTINCT tag_title FROM cms_tags WHERE tag_pag_id=" . $this->pag_id . " ORDER BY tag_title");
        }
        if(mysql_num_rows($rs)!=0){
            while($row=mysql_fetch_array($rs)){
                $tags[] = stripslashes($row['tag_title']);
            }
        }
        return $tags;
    }
    
    
}