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

require_once('../../../config.php');
permitUser(User::ADMIN, User::EDITOR);
checkToken(); // Check Authentication Token

    //////////////////////////////////////////////////////////////////
    // Load Page
    //////////////////////////////////////////////////////////////////

    // Get tag list
    $tags = new Tag();
    $all_tags = $tags->GetList();
    $page_tags = array();

    if(!empty($_GET['id'])){
        if($_GET['id']=='new'){
            $page = new Page();
            $page->id = 0;
            $page->title = "";
            $page->template = 0;
            $page->keywords = "";
            $page->description = "";
        }else{
            $page = new Page();
            $page->id = $_GET['id'];
            $page->Load();
            // Get page's tags
            $tags->pag_id = $page->id;
            $page_tags = $tags->GetList();
        }

        // Build Template Options
        $template_options = "";
        foreach($templates as $i=>$t){
            if($page->template==$i){
                $template_options .= "<option selected=\"selected\" value=\"$i\">" . escape($t['description']) . "</option>";
            }else{
                $template_options .= "<option value=\"$i\">" . escape($t['description']) . "</option>";
            }
        }

        // Build Tag Options
        $tag_options = "";
        foreach($all_tags as $tag){
            if(in_array($tag,$page_tags)){
                $tag_options .= "<option selected=\"selected\" value=\"" . escape($tag) . "\">" . escape($tag) . "</option>";
            }else{
                $tag_options .= "<option value=\"" . escape($tag) . "\">" . escape($tag) . "</option>";
            }
        }

        // Build Feed Options
        $feed = new Feed();
        $feed->pag_id = $page->id;
        $feed_options = "<option value=\"0\">" . $lang['DO NOT INCLUDE this page in Feed'] . "</option>";
        if($feed->CheckItem()){
            $feed_options .= "<option selected=\"selected\" value=\"1\">" . $lang['INCLUDE this page in Feed'] . "</option>";
        }else{
            $feed_options .= "<option value=\"1\">" . $lang['INCLUDE this page in Feed'] . "</option>";
        }

    }

    //////////////////////////////////////////////////////////////////
    // Save Page (Temp)
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['save'])){
        $page = new Page();
        $page->temp = true;
        $page->id = $_POST['id'];
        $page->title = $_POST['title'];
        $page->template = $_POST['template'];
        $page->keywords = $_POST['keywords'];
        $page->description = $_POST['description'];
        if($page->id==0){ // New page
            $page->temp = false;
            $page->url = $_POST['title'];
            $returned = $page->Save(); // Returns new URL
            echo($returned);
            // Get new id
            $page->url = $returned;
            $page->id = $page->GetPageID();
        }else{
            $page->Save();
            $page->id = $_POST['id'];
        }


        // Tags
        $tags = $_POST['tags'];
        $tag_obj = new Tag();
        $tag_obj->pag_id = $page->id;
        $tag_obj->Remove();
        if(is_array($tags)){
            foreach($tags as $tag){
                $tag_obj->title = $tag;
                $tag_obj->pag_id = $page->id;
                $tag_obj->Add();
            }
        }

        // Feed
        $feed = new Feed();
        $feed->pag_id = $page->id;
        if($_POST['feed']==0){ // Remove from feed
            $feed->RemoveItem();
        }else{ // Add to feed
            $feed->AddItem();
        }

    }

    //////////////////////////////////////////////////////////////////
    // Check Duplicate Title
    //////////////////////////////////////////////////////////////////

    if(!empty($_GET['checktitle'])){
        $page = new Page();
        $page->id = $_POST['id'];
        $page->title = $_POST['title'];
        // Return
        echo($page->CheckTitle());
    }

?>