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

    //////////////////////////////////////////////////////////////////
    // Define Loaded Properties
    //////////////////////////////////////////////////////////////////

    $load = new StdClass();
    $load->add_css        = "";
    $load->add_js         = "";
    $load->navigation     = "";
    $load->title          = "";
    $load->page_id        = "";
    $load->description    = "";
    $load->keywords       = "";
    $load->content        = "";
    $load->admin_elements = "";
    $load->follow         = "";
    $load->tags           = "";


    //////////////////////////////////////////////////////////////////
    // LOAD SYSTEM
    //////////////////////////////////////////////////////////////////

    $system = new System();
    $system->Load();

    //////////////////////////////////////////////////////////////////
    // LOAD PAGE
    //////////////////////////////////////////////////////////////////

    $page = new Page();

    $url = "";
    if(isset($_GET['load'])){
        $url = $_GET['load'];
    }

    if($url!="" && !isReserved($url)){
        // Get ID From URL
        $page->url = $url;
        $page->id = $page->GetPageID();
    }else{
        // Load default page
        $page->id = $system->default_page;
    }

    $page->Load();

    //////////////////////////////////////////////////////////////////
    // LOAD PAGE ELEMENTS
    //////////////////////////////////////////////////////////////////

    // Navigation
    $nav = new Navigation();
    $nav->active = "/" . $page->url;
    $load->navigation = $nav->GetStructure();

    // Page Properties & Elements
    $load->title = $page->title . " " . TITLE_DIVIDER . " " . $system->title;
    if($page->description==""){
        $load->description = $system->description;
    }else{
        $load->description = $page->description;
    }
    $keywords = $page->keywords . "," . $system->keywords;

    // Clean Keywords
    $keywords = str_replace(" ","",$keywords); // Remove spaces
    $keywords = explode(",",$keywords); // Explode
    $keywords = array_unique($keywords); // Unique only
    $load->keywords = ltrim(rtrim(implode(",",$keywords),","),","); // Remove lead/trail comma's and compile

    $load->content = render($page->content,$load);
    $load->page_id = $page->id;

    // Load tags
    $load->tags = $page->tags;

    // Follow Links
    if($follow){
        $follow_list = "<ul class=\"follow\">";
        foreach($follow as $key=>$val){
            $follow_list .= "<li class=\"" . str_replace(" ","_",strtolower($key)) . "\">";
            $follow_list .= "<a title=\"" . ucwords($key) . "\" href=\"" . $val . "\" target=\"_blank\">";
            $follow_list .= ucwords($key);
            $follow_list .= "</a></li>";
        }
        $follow_list .= "</ul>";
        $load->follow = $follow_list;
    }

    //////////////////////////////////////////////////////////////////
    // CHECK ADMIN
    //////////////////////////////////////////////////////////////////

    // Logout
    if(isset($_GET['load']) && $_GET['load']=="logout"){ session_unset(); session_destroy(); session_start(); }

    if((isset($_GET['load']) && $_GET['load']=="admin")
       || (isset($_GET['query']) && $_GET['query']=="admin")
       || (isset($_SESSION['usr_id'])))
    {
        $load->add_css .= "<link rel=\"stylesheet\" href=\"" . FOKIZ_PATH . "system/admin/css/screen.css\" media=\"screen\">\n";
        $load->add_css .= "<link rel=\"stylesheet\" href=\"" . FOKIZ_PATH . "system/admin/css/datatables.css\" media=\"screen\">\n";
        $load->admin_elements  .= "<!-- Admin Elements -->\n";
        $load->admin_elements .= "    <div id=\"adm_modal\"><div title=\"Move this window\" id=\"adm_drag_handle\"></div><div id=\"adm_modal_contents\"></div></div>\n";
        $load->admin_elements .= "    <div id=\"adm_bar\"><div id=\"adm_bar_contents\"></div><div id=\"adm_bar_control\"><a rel=\"0\"></a></div></div>\n";
        $load->admin_elements .= "    <div id=\"adm_overlay\"></div>\n";
        $load->admin_elements .= "    <!-- /Admin Elements -->\n";
        $load->add_js  .= "<!-- Admin Scripts -->\n";
        $load->add_js  .= "<script src=\"" . FOKIZ_PATH . "system/admin/js/common.js\"></script>\n";
        $load->add_js  .= "<script src=\"" . FOKIZ_PATH . "system/admin/js/jquery-ui.js\"></script>\n";
        $load->add_js  .= "<script src=\"" . FOKIZ_PATH . "system/admin/js/jquery.datatables.js\"></script>\n";
        $load->add_js  .= "<script src=\"" . FOKIZ_PATH . "system/admin/js/jquery.autocomplete.js\"></script>\n";
        $load->add_js  .= "<script src=\"" . FOKIZ_PATH . "system/admin/editor/ckeditor.js\"></script>\n";
    }

?>
