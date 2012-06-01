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

class Block {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $id            = 0;   
    public $content       = "";
    public $created       = "";
    public $modified      = "";
    public $temp          = false;
    
    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////
    
    // ------------------------------------------------------------ //
    
    //////////////////////////////////////////////////////////////////
    // LOAD
    //////////////////////////////////////////////////////////////////
    
    public function Load(){
        
        // Admin login - check for temp/edits ////////////////////////        
        if(isset($_SESSION['admin'])){
            $rs = mysql_query("SELECT btp_content FROM cms_blocks_temp WHERE btp_blk_id=" . $this->id);
            if(mysql_num_rows($rs)!=0){
                // Pull from temp/edits
                $row = mysql_fetch_array($rs);
                $this->content = stripslashes($row['btp_content']);
                $this->temp = true;
            }
        }
        
        // Load live block contents //////////////////////////////////
        if($this->temp==false){
            $rs = mysql_query("SELECT * FROM cms_blocks WHERE blk_id=" . $this->id);
            if(mysql_num_rows($rs)!=0){
                $row = mysql_fetch_array($rs);
                $this->content = stripslashes($row['blk_content']);
                $this->created = $row['blk_created'];
                $this->modified = $row['blk_modified']; 
            }
        }
    }
    
    //////////////////////////////////////////////////////////////////
    // SAVE
    //////////////////////////////////////////////////////////////////
    
    public function Save(){
    
        // Save temp/edit block //////////////////////////////////////
        if($this->temp==true){
            $rs = mysql_query("SELECT btp_id FROM cms_blocks_temp WHERE btp_blk_id=" . $this->id);
            if(mysql_num_rows($rs)==0){
                // Create temp
                $rs = mysql_query("INSERT INTO cms_blocks_temp (btp_blk_id,btp_content) VALUES (" . $this->id . ",'" . scrub($this->content) . "')");
            }else{
                // Update temp
                $rs = mysql_query("UPDATE cms_blocks_temp SET btp_content='" . scrub($this->content) . "' WHERE btp_blk_id=" . $this->id);
            }
        
        // Save live content block ///////////////////////////////////
        }else{
            if($this->id=="new"){
                // Create live
                $rs = mysql_query("INSERT INTO cms_blocks (blk_content,blk_created) VALUES ('" . scrub($this->content) . "',now())");
                $this->id = mysql_insert_id();
            }else{
                // Update live
                $rs = mysql_query("UPDATE cms_blocks SET blk_content='" . scrub($this->content) . "' WHERE blk_id=" . $this->id);
            }
        }
    }
    
    //////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////
    
    public function Delete(){
        // Delete temp/edits /////////////////////////////////////////
        if($this->$temp==true){
            $rs = mysql_query("DELETE FROM cms_blocks_temp WHERE btp_blk_id=" . $this->id);
        // Delete live block /////////////////////////////////////////
        }else{
            $rs = mysql_query("DELETE FROM cms_blocks WHERE blk_id=" . $this->id);
        }
    }
    
}

?>