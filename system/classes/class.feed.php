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

class Feed {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $id            = 0;
    public $pag_id        = 0;
    public $publish_date  = "";

    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////

    // ------------------------------------------------------------ //

    //////////////////////////////////////////////////////////////////
    // ADD ITEM
    //////////////////////////////////////////////////////////////////

    public function AddItem(){
        global $conn;
        if(!$this->CheckItem()){
            $rs = $conn->prepare("INSERT INTO cms_feed (fed_pag_id,fed_pub_date) VALUES (?,now())");
            $rs->execute(array($this->pag_id));
        }
    }

    //////////////////////////////////////////////////////////////////
    // REMOVE ITEM
    //////////////////////////////////////////////////////////////////

    public function RemoveItem(){
        global $conn;
        $rs = $conn->prepare("DELETE FROM cms_feed WHERE fed_pag_id=?");
        $rs->execute(array($this->pag_id));
    }

    //////////////////////////////////////////////////////////////////
    // CHECK ITEM
    //////////////////////////////////////////////////////////////////

    public function CheckItem(){
        global $conn;
        $rs = $conn->prepare("SELECT fed_id FROM cms_feed WHERE fed_pag_id=?");
        $rs->execute(array($this->pag_id));

        if($rs->rowCount() == 0){
            return false;
        }

        return true;
    }

    //////////////////////////////////////////////////////////////////
    // RETURN LIST
    //////////////////////////////////////////////////////////////////

    public function GetList(){
        global $conn;
        $rs = $conn->query("SELECT fed_pag_id, fed_pub_date FROM cms_feed ORDER BY fed_pub_date DESC");
        if($rs->rowCount() != 0){
            $output = array();
            while($row = $rs->fetch()){
                $output[] = array("pag_id"=>$row['fed_pag_id'], "pub_date"=>$row['fed_pub_date']);
            }
        }else{
            $output = 0;
        }
        return $output;
    }

    //////////////////////////////////////////////////////////////////
    // BUILD RSS
    //////////////////////////////////////////////////////////////////

    public function BuildRSS(){
        global $conn;

        $system = new System();
        $system->Load();
        $xmlfileName = BASE_PATH . "rss.xml";
        $xml_dec = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r\r";
        $xml_dec .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\r";
        $xml_dec .=    "  <channel>\r";
        $xml_dec .= "  <atom:link href=\"" . BASE_URL . "rss.xml\" rel=\"self\" type=\"application/rss+xml\" />\r";
        $xml_dec .=    "  <title><![CDATA[" . $system->title . "]]></title>\r";
        $xml_dec .= "  <link>" . BASE_URL . "</link>\r";
        $xml_dec .= "  <description><![CDATA[" . $system->description  . "]]></description>\r";
        $xml_dec .= "  <pubDate>" . date('D, d M Y h:i:s') . " +0600</pubDate>\r";
        $xml_dec .= "  <language>en-us</language>\r\r";

        // Create document
        $rootElementStart = "  <item>\r";
        $rootElementEnd = "  </item>\r\r";
        $xml_doc = $xml_dec;

        // Loop in pages
        $i = 0;
        $rs = $conn->query("SELECT fed_pag_id, fed_pub_date FROM cms_feed ORDER BY fed_pub_date DESC");
        if ($rs->rowCount() > 0){
            while ($row = $rs->fetch()){
                $rsPage = $conn->prepare("SELECT pag_title, pag_description, pag_url FROM cms_pages WHERE pag_id=?");
                $rsPage->execute(array($row['fed_pag_id']));
                if($rsPage->rowCount()!= 0){
                    $rowPage = $rsPage->fetch();
                    $xml_doc .=  $rootElementStart;
                    $xml_doc .= "    <title><![CDATA[" . stripslashes($rowPage['pag_title']) . "]]></title>\r";
                    $xml_doc .= "    <link>" . BASE_URL . stripslashes($rowPage['pag_url']) . "</link>\r";
                    $xml_doc .= "    <guid>" . BASE_URL . stripslashes($rowPage['pag_url']) . "</guid>\r";
                    $xml_doc .= "    <pubDate>" . date('D, d M Y h:i:s', strtotime($row['fed_pub_date'])) . " +0600</pubDate>\r";
                    $xml_doc .= "    <description><![CDATA[" . stripslashes($rowPage['pag_description']) . "]]></description>\r";
                    $xml_doc .= $rootElementEnd;
                    $i++;
                }
                if($i==10){ break; }
            }
        }
        // Write file
        $xml_doc .= "  </channel>\r</rss>";
        $fp = fopen($xmlfileName,'w');
        $write = fwrite($fp,$xml_doc);
    }

    //////////////////////////////////////////////////////////////////
    // BUILD XML SITEMAP
    //////////////////////////////////////////////////////////////////

    public function BuildXMLSitemap(){
        global $conn;

        $xmlfileName = BASE_PATH . "/sitemap.xml";
        $xml_dec = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\r<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r";

        // Create document
        $rootElementStart = "  <url>\r";
        $rootElementEnd = "  </url>\r";
        $xml_doc = $xml_dec;

        // Create home page entry

        $xml_doc .=  $rootElementStart;
        $xml_doc .=  "    <loc>" . BASE_URL . "</loc>\r";
        $xml_doc .=  "    <priority>0.9</priority>\r";
        $xml_doc .=  "    <changefreq>daily</changefreq>\r";
        $xml_doc .=  $rootElementEnd;

        // Loop in pages
        $rs = $conn->query("SELECT pag_url FROM cms_pages ORDER BY pag_created");
        if ($rs->rowCount() != 0){
        while ($row = $rs->fetch() ){
            $xml_doc .=  $rootElementStart;
            $xml_doc .=  "    <loc>" . BASE_URL . stripslashes($row['pag_url']) . "</loc>\r";
            $xml_doc .=  "    <priority>0.7</priority>\r";
            // Change frequency
            $xml_doc .=  "    <changefreq>weekly</changefreq>\r";
            $xml_doc .=  $rootElementEnd;
            }
        }
        // Write file
        $xml_doc .= "</urlset>";
        $fp = fopen($xmlfileName,'w');
        $write = fwrite($fp,$xml_doc);
    }
}