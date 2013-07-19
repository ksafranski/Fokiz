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
        global $conn;

        // Admin login - check for temp/edits ////////////////////////
        if(isset($_SESSION['usr_id'])){
            $rs = $conn->prepare("SELECT btp_content FROM cms_blocks_temp WHERE btp_blk_id=?");
            $rs->execute(array($this->id));
            if($rs->rowCount() != 0){
                // Pull from temp/edits
                $row = $rs->fetch();
                $this->content = stripslashes($row['btp_content']);
                $this->temp = true;
            }
        }

        // Load live block contents //////////////////////////////////
        if(!$this->temp){
            $rs = $conn->prepare("SELECT * FROM cms_blocks WHERE blk_id=?");
            $rs->execute(array($this->id));
            if($rs->rowCount() != 0){
                $row = $rs->fetch();
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
        global $conn;
        // Save temp/edit block //////////////////////////////////////
        if($this->temp){
            $rs = $conn->prepare("SELECT btp_id FROM cms_blocks_temp WHERE btp_blk_id=?");
            $rs->execute(array($this->id));
            // Create temp
            if($rs->rowCount() == 0){
                $rs = $conn->prepare("INSERT INTO cms_blocks_temp (btp_blk_id,btp_content) VALUES (?,?)");
                $rs->execute(array($this->id, $this->content));
            // Update temp
            }else{
                $rs = $conn->prepare("UPDATE cms_blocks_temp SET btp_content=? WHERE btp_blk_id=?");
                $rs->execute(array($this->content , $this->id));
            }

        // Save live content block ///////////////////////////////////
        }else{
            // Create live
            if($this->id=="new"){
                $rs = $conn->prepare("INSERT INTO cms_blocks (blk_content,blk_created) VALUES (?, now())");
                $rs->execute(array($this->content));
                $this->id = $conn->lastInsertId();
            // Update live
            }else{
                $rs = $conn->prepare("UPDATE cms_blocks SET blk_content=? WHERE blk_id=?");
                $rs->execute(array($this->content, $this->id));
            }
        }
    }

    //////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////

    public function Delete(){
        global $conn;
        // Delete temp/edits /////////////////////////////////////////
        if($this->$temp==true){
            $rs = $conn->prepare("DELETE FROM cms_blocks_temp WHERE btp_blk_id=?");
            $rs->execute(array($this->id));
        // Delete live block /////////////////////////////////////////
        }else{
            $rs = $conn->prepare("DELETE FROM cms_blocks WHERE blk_id=?");
            $rs->execute(array($this->id));
        }
    }

}

?>
