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
    
    
    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////
    
    // ------------------------------------------------------------ //
    
    //////////////////////////////////////////////////////////////////
    // GET PAGE ID
    //////////////////////////////////////////////////////////////////
    
    public function GetPageID(){
        $rs = mysql_query("SELECT pag_id FROM cms_pages WHERE pag_url='" . scrub(strtolower($this->url)) . "'");
        if(mysql_num_rows($rs)==0){ return 0; }
        else{ $row = mysql_fetch_array($rs); return $row['pag_id']; } 
    }
    
    //////////////////////////////////////////////////////////////////
    // LOAD
    //////////////////////////////////////////////////////////////////
    
    public function Load(){
        if($this->id==0){
            // 404 - Not Found
            $this->title = "404 - Not Found";
            $this->template = 0;
            $this->description = "Page Not Found";
            $this->keywords = "404";
            $this->created = "0000-00-00 00:00:00";
            $this->modified = "0000-00-00 00:00:00";
            $this->content = file_get_contents(BASE_PATH . "templates/404.php");
        }else{        
            $loaded = false;        
            // Admin login - check for temp/edits
            if(isset($_SESSION['admin'])){
                $rs = mysql_query("SELECT * FROM cms_pages_temp WHERE ptp_pag_id=" . $this->id);
                if(mysql_num_rows($rs)!=0){
                    $row = mysql_fetch_array($rs);
                    $this->title = stripslashes($row['ptp_title']);
                    $this->template = $row['ptp_template'];
                    $this->url = $row['ptp_url'];
                    $this->description = stripslashes($row['ptp_description']);
                    $this->keywords = stripslashes($row['ptp_keywords']);
                    $this->created = "0000-00-00 00:00:00";
                    $this->modified = "0000-00-00 00:00:00";
                    $this->content = $this->MapContent();
                    $loaded = true;
                }
            }          
            // Load live content
            if($loaded==false){
                // Load Page
                $rs = mysql_query("SELECT * FROM cms_pages WHERE pag_id=" . $this->id);
                $row = mysql_fetch_array($rs);
                $this->title = stripslashes($row['pag_title']);
                $this->template = $row['pag_template'];
                $this->url = $row['pag_url'];
                $this->description = stripslashes($row['pag_description']);
                $this->keywords = stripslashes($row['pag_keywords']);
                $this->created = $row['pag_created'];
                $this->modified = $row['pag_modified'];
                $this->content = $this->MapContent();
            }
        }
    }
    
    //////////////////////////////////////////////////////////////////
    // MAP CONTENT
    //////////////////////////////////////////////////////////////////
    
    private function MapContent(){
        global $templates;
        $tpl = $templates[$this->template];
        $content = file_get_contents(BASE_PATH . "templates/" . $tpl['file']);
        $rs = mysql_query("SELECT map_blk_id, map_region FROM cms_mapping WHERE map_pag_id=" . $this->id);
        if(mysql_num_rows($rs)!=0){
            while($row=mysql_fetch_array($rs)){                 
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
    
        // Save publish notes ////////////////////////////////////////
        if($this->pub_notes!=""){
            $rs = mysql_query("INSERT INTO cms_publish_notes (pbn_pag_id,pbn_notes,pbn_pub_date) VALUES (" 
                . $this->id . ",'" . scrub($this->pub_notes) . "',now())");
        }
       
        // URL Change? Update linkages ///////////////////////////////
        $rsCur = mysql_query("SELECT pag_url FROM cms_pages WHERE pag_id=" . $this->id);
        $rowCur = mysql_fetch_array($rsCur);
        $cur_url = $rowCur['pag_url'];
        
        $new_url = $rowCur['pag_url'];
        $rsNew = mysql_query("SELECT ptp_url FROM cms_pages_temp WHERE ptp_pag_id=" . $this->id);
        if(mysql_num_rows($rsNew)!=0){ 
            $rowNew = mysql_fetch_array($rsNew); 
            $new_url = $rowNew['ptp_url']; 
        }
        
        if($cur_url!=$new_url){ // Title/URL Change, Proceed...
            // Update navigation
            $rs = mysql_query("UPDATE cms_navigation SET nav_url='" . FOKIZ_PATH . $new_url . "' WHERE nav_url='" . FOKIZ_PATH . $cur_url . "'");
            // Update blocks
            $rs = mysql_query("UPDATE cms_blocks SET blk_content = REPLACE(blk_content,'href=\"" . FOKIZ_PATH . $cur_url . "','href=\"" . FOKIZ_PATH . $new_url . "')");
            // Update blocks (temp)
            $rs = mysql_query("UPDATE cms_blocks_temp SET btp_content = REPLACE(btp_content,'href=\"" . FOKIZ_PATH . $cur_url . "','href=\"" . FOKIZ_PATH . $new_url . "')");
        }
        
        // Save page from temp ///////////////////////////////////////
        $this->Load(); // Load up variables (from temp)
        $this->temp = false;
        $this->Save();
        
        // Save blocks from temp /////////////////////////////////////
        $rs = mysql_query("SELECT map_blk_id FROM cms_mapping WHERE map_pag_id=" . $this->id);
        if(mysql_num_rows($rs)!=0){
            while($row=mysql_fetch_array($rs)){
                $blk_id = $row['map_blk_id'];
                // Check for block changes
                $rsUpdateCheck = mysql_query("SELECT btp_content FROM cms_blocks_temp WHERE btp_blk_id=$blk_id");
                if(mysql_num_rows($rsUpdateCheck)!=0){
                    $rowUpdateCheck = mysql_fetch_array($rsUpdateCheck);
                    $blk_content = $rowUpdateCheck['btp_content'];
                    // Changes exits, save to live
                    $rsSave = mysql_query("UPDATE cms_blocks SET blk_content='$blk_content', blk_modified=now() WHERE blk_id=$blk_id");
                    // Delete Temp
                    $rsDelTemp = mysql_query("DELETE FROM cms_blocks_temp WHERE btp_blk_id=" . $blk_id);
                }
                
            }      
        }
        
        // Remove temp ///////////////////////////////////////////////
        $rs = mysql_query("DELETE FROM cms_pages_temp WHERE ptp_pag_id=" . $this->id); 
        
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
        
        // Loop through mappings and delete temp blocks
        $rs = mysql_query("SELECT map_blk_id FROM cms_mapping WHERE map_pag_id=" . $this->id);
        if(mysql_num_rows($rs)!=0){
            while($row=mysql_fetch_array($rs)){
                // Delete any lingering temp blocks
                $rsDelTempBlock = mysql_query("DELETE FROM cms_blocks_temp WHERE btp_blk_id=" . $row['map_blk_id']);
            }
        }    
        $rs = mysql_query("DELETE FROM cms_pages_temp WHERE ptp_pag_id=" . $this->id);
        
    }
    
    //////////////////////////////////////////////////////////////////
    // SAVE
    //////////////////////////////////////////////////////////////////
    
    public function Save(){
    
        $return = "";
        
        // Format URL
        $this->FormatURL();
    
        // Save temp/edit page ///////////////////////////////////////
        if($this->temp==true){
            $rs = mysql_query("SELECT ptp_id FROM cms_pages_temp WHERE ptp_pag_id=" . $this->id);
            if(mysql_num_rows($rs)==0){
                // Create temp
                $rs = mysql_query("INSERT INTO cms_pages_temp (ptp_pag_id,ptp_title,ptp_template,ptp_url,ptp_description,ptp_keywords) VALUES ("
                    . $this->id . ",'" . scrub($this->title) . "'," . $this->template . ",'" . $this->url . "','" . scrub($this->description) . "','" . scrub($this->keywords) . "')");
                $this->id = mysql_insert_id();
            }else{
                // Update temp
                $rs = mysql_query("UPDATE cms_pages_temp SET ptp_title='" . scrub($this->title) . "', ptp_template=" . $this->template . ", ptp_url='" . $this->url
                    . "', ptp_description='" . scrub($this->description) . "', ptp_keywords='" . scrub($this->keywords) . "' WHERE ptp_pag_id=" . $this->id);
            }
        
        // Save live content page ////////////////////////////////////
        }else{
            if($this->id==0){
                // Create live
                $rs = mysql_query("INSERT INTO cms_pages (pag_title,pag_template,pag_url,pag_description,pag_keywords,pag_created,pag_modified) VALUES ('"
                    . scrub($this->title) . "'," . $this->template . ",'" . $this->url . "','" . scrub($this->description) . "','" . scrub($this->keywords) . "',now(),now())");
                $_SESSION['cur_page'] = mysql_insert_id();
                $return = $this->url;
            }else{
                // Update live
                $rs = mysql_query("UPDATE cms_pages SET pag_title='" . scrub($this->title) . "', pag_template=" . $this->template . ", pag_url='" . $this->url
                    . "', pag_description='" . scrub($this->description) . "', pag_keywords='" . scrub($this->keywords) . "', pag_modified=now() WHERE pag_id=" . $this->id);
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
        $blk_count = $templates[$this->template]['blocks'];
        $i = 1;
        while($i<=$blk_count){
            $rs = mysql_query("SELECT * FROM cms_mapping WHERE map_pag_id=" . $_SESSION['cur_page'] . " AND map_region=" . ($i-1));
            if(mysql_num_rows($rs)==0){
                // Create the block
                $rsCreateBlock = mysql_query("INSERT INTO cms_blocks (blk_content,blk_created,blk_modified) VALUES ('" . scrub(DEFAULT_BLOCK_CONTENT) . "',now(),now())");              
                // Map the block
                $rsMapBlock = mysql_query("INSERT INTO cms_mapping (map_pag_id,map_blk_id,map_region) VALUES (" . $_SESSION['cur_page'] . "," . mysql_insert_id() . "," . ($i-1) . ")");
            }
            $i++;
        }
    }
    
    //////////////////////////////////////////////////////////////////
    // DELETE
    //////////////////////////////////////////////////////////////////
    
    public function Delete(){
        // Loop through mappings and delete blocks
        $rs = mysql_query("SELECT map_blk_id FROM cms_mapping WHERE map_pag_id=" . $this->id);
        if(mysql_num_rows($rs)!=0){
            while($row=mysql_fetch_array($rs)){
                // Delete block
                $rsDelBlock = mysql_query("DELETE FROM cms_blocks WHERE blk_id=" . $row['map_blk_id']);
                // Delete any lingering temp blocks
                $rsDelTempBlock = mysql_query("DELETE FROM cms_blocks_temp WHERE btp_blk_id=" . $row['map_blk_id']);
            }
        }
        // Delete mappings
        $rs = mysql_query("DELETE FROM cms_mapping WHERE map_pag_id=" . $this->id);
        // Delete any lingering temp page
        $rs = mysql_query("DELETE FROM cms_pages_temp WHERE ptp_pag_id=" . $this->id);
        // Delete the page
        $rs = mysql_query("DELETE FROM cms_pages WHERE pag_id=" . $this->id);
        // Delete feed entries
        $rs = mysql_query("DELETE FROM cms_feed WHERE fed_pag_id=" . $this->id);
        // Delete tags
        $rs = mysql_query("DELETE FROM cms_tags WHERE tag_pag_id=" . $this->id);
        
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
    
        $output = array();
        
        $rs = mysql_query("SELECT * FROM cms_pages ORDER BY pag_title");
        if(mysql_num_rows($rs)==0){
            $output = 0;
        }else{
            while($row=mysql_fetch_array($rs)){
                $output[] = array(
                    "id"          => $row['pag_id'],
                    "title"       => stripslashes($row['pag_title']),
                    "template"    => $row['pag_template'],
                    "url"         => stripslashes($row['pag_url']),
                    "description" => stripslashes($row['pag_description']),
                    "keywords"    => stripslashes($row['pag_keywords']),
                    "created"     => $row['pag_created'],
                    "modified"    => $row['pag_modified']
                ); 
            }
        }
        return $output; 
    
    }
    
    //////////////////////////////////////////////////////////////////
    // CHECK DUPLICATE TITLE
    //////////////////////////////////////////////////////////////////
    
    public function CheckTitle(){
    
        $pass = 0;
    
        // Check live
        $rs = mysql_query("SELECT pag_id FROM cms_pages WHERE pag_title='" . $this->title . "' AND pag_id!=" . $this->id);
        if(mysql_num_rows($rs)!=0){ $pass = 1; }
        // Check temp
        $rs = mysql_query("SELECT ptp_pag_id FROM cms_pages_temp WHERE ptp_title='" . $this->title . "' AND ptp_pag_id!=" . $this->id);
        if(mysql_num_rows($rs)!=0){ $pass = 1; }
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