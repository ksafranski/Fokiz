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

class Page {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $id            = 0;
    public $title         = "";
    public $template      = 0;
    public $url           = "";
    public $description   = "";
    public $keywords      = "";
    public $created       = "";
    public $modified      = "";
    public $content       = "";
    public $temp          = false;
    public $pub_notes     = "";
    public $tags          = "";

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    // ------------------------------------------------------------ //

    //////////////////////////////////////////////////////////////////
    // GET PAGE ID
    //////////////////////////////////////////////////////////////////

    public function GetPageID(){
        global $conn;
        $rs = $conn->prepare("SELECT pag_id FROM cms_pages WHERE pag_url=?");
        $rs->execute(array(strtolower($this->url)));
        $rowCount = $rs->rowCount();

        if($rowCount == 0){
            return 0;
        }

        $row = $rs->fetch();
        return $row['pag_id'];
    }

    //////////////////////////////////////////////////////////////////
    // LOAD
    //////////////////////////////////////////////////////////////////

    public function Load(){
        global $conn;
        $loaded = false;

        if($this->id == 0){
            // 404 - Not Found
            $this->title = "404 - Not Found";
            $this->template = 0;
            $this->description = "Page Not Found";
            $this->keywords = "404";
            $this->created = "0000-00-00 00:00:00";
            $this->modified = "0000-00-00 00:00:00";
            $this->content = file_get_contents(BASE_PATH . "templates/404.php");
            $this->tags = "";
            return;
        }

        // Admin login - check for temp/edits
        if(isset($_SESSION['usr_id'])){

            $rs = $conn->prepare("SELECT * FROM cms_pages_temp WHERE ptp_pag_id=?");
            $rs->execute(array($this->id));

            if($rs->rowCount() != 0){
                $row = $rs->fetch();

                $this->title = stripslashes($row['ptp_title']);
                $this->template = $row['ptp_template'];
                $this->url = $row['ptp_url'];
                $this->description = stripslashes($row['ptp_description']);
                $this->keywords = stripslashes($row['ptp_keywords']);
                $this->created = "0000-00-00 00:00:00";
                $this->modified = "0000-00-00 00:00:00";
                $this->content = $this->MapContent();

                $rs2 = $conn->prepare("SELECT * FROM cms_tags WHERE tag_pag_id=?");
                $rs2->execute(array($this->id));

                $this->tags = array();
                while($row2 = $rs2->fetch()){
                    array_push($this->tags, $row2['tag_title']);
                }
                $loaded = true;
            }
        }

        // Load live content
        if(!$loaded){
            // Load Page
            $rs = $conn->prepare("SELECT * FROM cms_pages WHERE pag_id=?");
            $rs->execute(array($this->id));
            $row = $rs->fetch();

            $this->title = stripslashes($row['pag_title']);
            $this->template = $row['pag_template'];
            $this->url = $row['pag_url'];
            $this->description = stripslashes($row['pag_description']);
            $this->keywords = stripslashes($row['pag_keywords']);
            $this->created = $row['pag_created'];
            $this->modified = $row['pag_modified'];
            $this->content = $this->MapContent();

            $rs2 = $conn->prepare("SELECT * FROM cms_tags WHERE tag_pag_id=?");
            $rs2->execute(array($this->id));

            $this->tags = array();
            while($row2 = $rs2->fetch()){
               array_push($this->tags, $row2['tag_title']);
            }
        }
    }

    //////////////////////////////////////////////////////////////////
    // MAP CONTENT
    //////////////////////////////////////////////////////////////////

    private function MapContent(){
        global $templates;
        global $conn;
        $tpl = $templates[$this->template];
        $content = file_get_contents(BASE_PATH . "templates/" . $tpl['file']);

        $rs = $conn->prepare("SELECT map_blk_id, map_region FROM cms_mapping WHERE map_pag_id=?");
        $rs->execute(array($this->id));

        if($rs->rowCount() != 0){
            while($row = $rs->fetch()){
                $block = new Block();
                $block->id = $row['map_blk_id'];
                $block->Load();

                // Replace contents
                $content = str_replace("[[block_" . $row['map_region'] . "]]",$block->content,$content);
                $content = str_replace("data-block-id=\"block_" . $row['map_region'] . "\"","data-block-id=\"" . $row['map_blk_id'] . "\"",$content);
            }
        }
        return $content;
    }

    //////////////////////////////////////////////////////////////////
    // PUBLISH
    //////////////////////////////////////////////////////////////////

    public function Publish(){
        global $conn;

        // Save publish notes ////////////////////////////////////////
        if($this->pub_notes!=""){
            $rs = $conn->prepare("INSERT INTO cms_publish_notes (pbn_pag_id,pbn_notes,pbn_pub_date) VALUES (?,?, now())");
            $rs->execute(array($this->id, $this->pub_notes));
        }

        // URL Change? Update linkages ///////////////////////////////
        $rsCur = $conn->prepare("SELECT pag_url FROM cms_pages WHERE pag_id=?");
        $rsCur->execute(array($this->id));
        $rowCur = $rsCur->fetch();

        $cur_url = $rowCur['pag_url'];

        $new_url = $rowCur['pag_url'];
        $rsNew = $conn->prepare("SELECT ptp_url FROM cms_pages_temp WHERE ptp_pag_id=?");
        $rsNew->execute(array($this->id));
        if($rsNew->rowCount() != 0){
            $rowNew = $rsNew->fetch();
            $new_url = $rowNew['ptp_url'];
        }

        if($cur_url!=$new_url){ // Title/URL Change, Proceed...
            // Update navigation
            $rs = $conn->prepare("UPDATE cms_navigation SET nav_url=? WHERE nav_url=?");
            $rs->execute(array(FOKIZ_PATH . $new_url, FOKIZ_PATH . $cur_url));
            // Update blocks
            $rs = $conn->prepare("UPDATE cms_blocks SET blk_content = REPLACE(blk_content,?,?)");
            $rs->execute(array('href=' . FOKIZ_PATH . $new_url, 'href=' . FOKIZ_PATH . $cur_url));
            // Update blocks (temp)
            $rs = $conn->prepare("UPDATE cms_blocks_temp SET btp_content = REPLACE(btp_content,?,?)");
            $rs->execute(array('href=' . FOKIZ_PATH . $cur_url, 'href=' . FOKIZ_PATH . $new_url));
        }

        // Save page from temp ///////////////////////////////////////
        $this->Load(); // Load up variables (from temp)
        $this->temp = false;
        $this->Save();

        // Save blocks from temp /////////////////////////////////////
        $rs = $conn->prepare("SELECT map_blk_id FROM cms_mapping WHERE map_pag_id=?");
        $rs->execute(array($this->id));
        if($rs->rowCount() != 0){
            while($row = $rs->fetch()){
                $blk_id = $row['map_blk_id'];

                // Check for block changes
                $rsUpdateCheck = $conn->prepare("SELECT btp_content FROM cms_blocks_temp WHERE btp_blk_id=?");
                $rsUpdateCheck->execute(array($blk_id));

                if($rsUpdateCheck->rowCount() != 0){
                    $rowUpdateCheck = $rsUpdateCheck->fetch();
                    $blk_content = $rowUpdateCheck['btp_content'];

                    // Changes exits, save to live
                    $rsSave = $conn->prepare("UPDATE cms_blocks SET blk_content=?, blk_modified=now() WHERE blk_id=?");
                    $rsSave->execute(array($blk_content, $blk_id));

                    // Delete Temp
                    $rsDelTemp = $conn->prepare("DELETE FROM cms_blocks_temp WHERE btp_blk_id=?");
                    $rsDelTemp->execute(array($blk_id));
                }

            }
        }

        // Remove temp ///////////////////////////////////////////////
        $rs = $conn->prepare("DELETE FROM cms_pages_temp WHERE ptp_pag_id=?");
        $rs->execute(array($this->id));

        // Update sitemap.xml ////////////////////////////////////////
        $sitemap = new Feed();
        $sitemap->BuildXMLSitemap();

        // Update rss.xml ////////////////////////////////////////////
        $rss = new Feed();
        $rss->BuildRSS();

        // Return URL (For page reload) //////////////////////////////
        return $this->url;

    }

    //////////////////////////////////////////////////////////////////
    // REVERT
    //////////////////////////////////////////////////////////////////

    public function Revert(){
        global $conn;
        // Loop through mappings and delete temp blocks
        $rs = $conn->prepare("SELECT map_blk_id FROM cms_mapping WHERE map_pag_id=?");
        $rs->execute(array($this->id));
        if($rs->rowCount() != 0){
            while($row = $rs->fetch()){
                // Delete any lingering temp blocks
                $rsDelTempBlock = $conn->prepare("DELETE FROM cms_blocks_temp WHERE btp_blk_id=?");
                $rsDelTempBlock->execute(array($row['map_blk_id']));
            }
        }
        $rs = $conn->prepare("DELETE FROM cms_pages_temp WHERE ptp_pag_id=?");
        $rs->execute(array($this->id));

    }

    //////////////////////////////////////////////////////////////////
    // SAVE
    //////////////////////////////////////////////////////////////////

    public function Save(){
        global $conn;
        $return = "";

        // Format URL
        $this->FormatURL();

        // Save temp/edit page ///////////////////////////////////////
        if($this->temp){
            $rs = $conn->prepare("SELECT ptp_id FROM cms_pages_temp WHERE ptp_pag_id=?");
            $rs->execute(array($this->id));
            if($rs->rowCount() == 0){
                // Create temp
                $rs = $conn->prepare("INSERT INTO cms_pages_temp (ptp_pag_id,ptp_title,ptp_template,ptp_url,ptp_description,ptp_keywords) VALUES (?,?,?,?,?,?)");
                $rs->execute(array($this->id, $this->title, $this->template, $this->url, $this->description, $this->keywords));
                $this->id = $conn->lastInsertId();
            }else{
                // Update temp
                $rs = $conn->prepare("UPDATE cms_pages_temp SET ptp_title=?, ptp_template=?, ptp_url=?, ptp_description=?, ptp_keywords=? WHERE ptp_pag_id=?");
                $rs->execute(array($this->title, $this->template, $this->url, $this->description, $this->keywords, $this->id));
            }

        // Save live content page ////////////////////////////////////
        }else{
            if($this->id==0){
                // Create live
                $rs = $conn->prepare("INSERT INTO cms_pages (pag_title,pag_template,pag_url,pag_description,pag_keywords,pag_created,pag_modified) VALUES (?,?,?,?,?,now(),now())");
                $rs->execute(array($this->title, $this->template, $this->url, $this->description, $this->keywords));
                $_SESSION['cur_page'] = $conn->lastInsertId();

                $return = $this->url;
            }else{
                // Update live
                $rs = $conn->prepare("UPDATE cms_pages SET pag_title=?, pag_template=?, pag_url=?, pag_description=?, pag_keywords=?, pag_modified=now() WHERE pag_id=?");
                $rs->execute(array($this->title, $this->template, $this->url, $this->description, $this->keywords, $this->id));
            }
        }

        // Ensure all needed blocks exist
        $this->MatchBlocks();

        return $return;
    }

    //////////////////////////////////////////////////////////////////
    // MATCH BLOCKS (Ensure blocks exist for all tpl regions)
    //////////////////////////////////////////////////////////////////

    private function MatchBlocks(){
        global $templates;
        global $conn;
        $blk_count = $templates[$this->template]['blocks'];
        $i = 1;
        while($i<=$blk_count){
            $rs = $conn->prepare("SELECT * FROM cms_mapping WHERE map_pag_id=? AND map_region=?");
            $rs->execute(array($_SESSION['cur_page'], ($i-1)));
            if($rs->rowCount() == 0){
                // Create the block
                $rsCreateBlock = $conn->prepare("INSERT INTO cms_blocks (blk_content,blk_created,blk_modified) VALUES (?,now(),now())");
                $rsCreateBlock->execute(array(DEFAULT_BLOCK_CONTENT));
                // Map the block
                $rsMapBlock = $conn->prepare("INSERT INTO cms_mapping (map_pag_id,map_blk_id,map_region) VALUES (?,?,?)");
                $rsMapBlock->execute(array($_SESSION['cur_page'], $conn->lastInsertId(), ($i-1)));
            }
            $i++;
        }
    }

    //////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////

    public function Delete(){
        global $conn;

        // Loop through mappings and delete blocks
        $rs = $conn->prepare("SELECT map_blk_id FROM cms_mapping WHERE map_pag_id=?");
        $rs->execute(array($this->id));

        if($rs->rowCount() != 0){
            while($row = $rs->fetch()){
                // Delete block
                $rsDelBlock = $conn->prepare("DELETE FROM cms_blocks WHERE blk_id=?");
                $rsDelBlock->execute(array($row['map_blk_id']));
                // Delete any lingering temp blocks
                $rsDelTempBlock = $conn->prepare("DELETE FROM cms_blocks_temp WHERE btp_blk_id=?");
                $rsDelTempBlock->execute(array($row['map_blk_id']));
            }
        }
        // Delete mappings
        $rs = $conn->prepare("DELETE FROM cms_mapping WHERE map_pag_id=?");
        $rs->execute(array($this->id));
        // Delete any lingering temp page
        $rs = $conn->prepare("DELETE FROM cms_pages_temp WHERE ptp_pag_id=?");
        $rs->execute(array($this->id));
        // Delete the page
        $rs = $conn->prepare("DELETE FROM cms_pages WHERE pag_id=?");
        $rs->execute(array($this->id));
        // Delete feed entries
        $rs = $conn->prepare("DELETE FROM cms_feed WHERE fed_pag_id=?");
        $rs->execute(array($this->id));
        // Delete tags
        $rs = $conn->prepare("DELETE FROM cms_tags WHERE tag_pag_id=?");
        $rs->execute(array($this->id));

        // Update sitemap.xml
        $sitemap = new Feed();
        $sitemap->BuildXMLSitemap();

        // Update rss.xml
        $rss = new Feed();
        $rss->BuildRSS();
    }

    //////////////////////////////////////////////////////////////////
    // RETURN LISTING
    //////////////////////////////////////////////////////////////////

    public function GetList(){
        global $conn;
        $output = array();

        $rs = $conn->query("SELECT * FROM cms_pages ORDER BY pag_title");
        if($rs->rowCount() == 0){
            $output = 0;
        }else{
            while($row = $rs->fetch()){
                $output[] = array(
                    "id"          => $row['pag_id'],
                    "title"       => stripslashes($row['pag_title']),
                    "template"    => $row['pag_template'],
                    "url"         => stripslashes($row['pag_url']),
                    "description" => stripslashes($row['pag_description']),
                    "keywords"    => stripslashes($row['pag_keywords']),
                    "created"     => $row['pag_created'],
                    "modified"    => $row['pag_modified'],
                    "pending"     => $this->CheckPending($row['pag_id'])
                );
            }
        }
        return $output;

    }

    //////////////////////////////////////////////////////////////////
    // CHECK PENDING SAVE
    //////////////////////////////////////////////////////////////////

    public function CheckPending($id){
        global $conn;
        $pending = false;

        // Check page edits
        $rs = $conn->prepare("SELECT ptp_id FROM cms_pages_temp WHERE ptp_pag_id=?");
        $rs->execute(array($id));
        if($rs->rowCount() != 0){
            $pending = true;
        }

        // Check block edits
        $rs = $conn->prepare("SELECT map_blk_id FROM cms_mapping WHERE map_pag_id=?");
        $rs->execute(array($id));
        if($rs->rowCount() != 0){
            while($row = $rs->fetch()){
                $rsCheckBlock = $conn->prepare("SELECT btp_id FROM cms_blocks_temp WHERE btp_blk_id=?");
                $rsCheckBlock->execute(array($row['map_blk_id']));
                if($rsCheckBlock->rowCount() != 0){
                    $pending = true;
                     break;
                }
            }
        }

        return $pending;

    }

    //////////////////////////////////////////////////////////////////
    // CHECK DUPLICATE TITLE
    //////////////////////////////////////////////////////////////////

    public function CheckTitle(){
        global $conn;
        $pass = 0;

        // Check live
        $rs = $conn->prepare("SELECT pag_id FROM cms_pages WHERE pag_title=? AND pag_id!=?");
        $rs->execute(array($this->title, $this->id));
        if($rs->rowCount() != 0){ $pass = 1; }
        // Check temp
        $rs = $conn->prepare("SELECT ptp_pag_id FROM cms_pages_temp WHERE ptp_title=? AND ptp_pag_id!=?");
        $rs->execute(array($this->title, $this->id));
        if($rs->rowCount() != 0){ $pass = 1; }
        // Check reserved
        if(isReserved($this->title)){ $pass = 1; }

        return $pass;

    }

    //////////////////////////////////////////////////////////////////
    // FORMAT URL
    //////////////////////////////////////////////////////////////////

    private function FormatURL(){
        $code_entities_match = array(' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','=');
        $code_entities_replace = array('-','-','','','','','','','','','','','','','','','','','','','','','','','','');
        $output = str_replace($code_entities_match, $code_entities_replace, strtolower($this->title));
        // Strip any output'd double-hyphens
        $this->url = str_replace('--','-',$output);
    }

}

?>
