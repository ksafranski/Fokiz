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

    define("VERSION","2.5");

    //////////////////////////////////////////////////////////////////
    // Error reporting
    //////////////////////////////////////////////////////////////////

    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    //////////////////////////////////////////////////////////////////
    // Database Connection
    //////////////////////////////////////////////////////////////////

    try {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        error_log("Connection failed: ". $e->getMessage());
    }

    //////////////////////////////////////////////////////////////////
    // Session initialization
    //////////////////////////////////////////////////////////////////

    @session_start();
    ini_set("session.cache_expire", 360000);
    ini_set("session.gc_maxlifetime", "3600000");

    //////////////////////////////////////////////////////////////////
    // Define Paths
    //////////////////////////////////////////////////////////////////

    $_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'] . FOKIZ_PATH;
    define("BASE_PATH" , $_SERVER['DOCUMENT_ROOT']);

    function defineURL(){
        $URL = 'http';
        if(!empty($_SERVER['HTTPS'])){ $URL .= "s"; }
        $URL .= "://";
        if($_SERVER["SERVER_PORT"]!="80" && $_SERVER["SERVER_PORT"]!="443"){ $URL .= $_SERVER['HTTP_HOST'].":".$_SERVER["SERVER_PORT"]; }
        else { $URL .= $_SERVER['HTTP_HOST']; }
        return $URL . FOKIZ_PATH;
    }

    define("BASE_URL", defineURL());

    //////////////////////////////////////////////////////////////////
    // Check if installed
    //////////////////////////////////////////////////////////////////

    $install = true;

    if(isset($conn)){
        $rs = $conn->query("SELECT sys_id FROM cms_system");

        if($rs!=false){
            $install = false;
        }
    }

    if($install){
        if($_SERVER['PHP_SELF']!=FOKIZ_PATH.'system/install/index.php' &&
           $_SERVER['PHP_SELF']!=FOKIZ_PATH.'system/install/process.php')
        {
            header('location: ' . FOKIZ_PATH . 'system/install/index.php');
        }
    }


    //////////////////////////////////////////////////////////////////
    // Check Token
    //////////////////////////////////////////////////////////////////

    function checkToken(){
        if(!isset($_SESSION['admin'])){
            echo("<script>$(function(){ window.location = '/admin';  });</script>");
            exit();
        }
    }

    //////////////////////////////////////////////////////////////////
    // Set Language
    //////////////////////////////////////////////////////////////////

    if(!defined('LANGUAGE')){ define("LANGUAGE","en"); }
    require_once('lang/' . strtolower(LANGUAGE) . '.php');

    function lang($text){
        global $lang;
        if(isset($lang[$text])){
            echo($lang[$text]);
        }else{
            echo("????????");
        }
    }

    //////////////////////////////////////////////////////////////////
    // USER TYPES
    //////////////////////////////////////////////////////////////////

    $usr_type[0] = $lang['Administrator'];
    $usr_type[1] = $lang['Editor'];

    //////////////////////////////////////////////////////////////////
    // Default Block Content
    //////////////////////////////////////////////////////////////////

    define("DEFAULT_BLOCK_CONTENT","<h2>Lorem Ipsum Dolor</h2><p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Phasellus quis lectus metus, at posuere neque. Sed pharetra
            nibh eget orci convallis at posuere leo convallis. Sed blandit
            augue vitae augue scelerisque bibendum. Vivamus sit amet
            libero turpis, non venenatis urna. In blandit, odio convallis
            suscipit venenatis, ante ipsum cursus augue.
            </p>");

    //////////////////////////////////////////////////////////////////
    // Check Reserved Title/URL
    //////////////////////////////////////////////////////////////////

    function isReserved($t){
        // Defines CMS Action URLs
        $reserved = array("admin","logout");
        if(in_array(strtolower($t),$reserved)){ return true; }
        else{ return false; }
    }

    //////////////////////////////////////////////////////////////////
    // Shorten string
    //////////////////////////////////////////////////////////////////

    function shortenString($val,$len){
        if(strlen($val) > $len){ return(rtrim(substr($val, 0, $len)) . "&hellip;"); }else{ return($val); }
    }

    //////////////////////////////////////////////////////////////////
    // Timestamp formatting
    //////////////////////////////////////////////////////////////////

    function formatTimestamp($val){
        return str_replace(" ", "&nbsp;", date('n/j/y', strtotime($val)));
    }

    //////////////////////////////////////////////////////////////////
    // RENDER CUSTOM VARIABLES (MODULES)
    //////////////////////////////////////////////////////////////////

    function render($content,$load){
        preg_match_all('/\[\[(.+?):(.+?)\]\]/', $content, $aryMatch, PREG_PATTERN_ORDER);
        if(count($aryMatch[1])>0){
            $aryInsert = array();
            foreach($aryMatch[1] as $m_key=>$m_val) {
                if (!array_key_exists($aryMatch[0][$m_key],$aryInsert)){
                    $cur_key = $aryMatch[2][$m_key];
                    switch($m_val){
                        case 'module':
                            $path = "";
                            $param = "";
                            // Check for parameters
                            $mod = explode('=&gt;',$cur_key);
                            $path = $mod[0];
                            if (sizeof($mod)>1){ $param = $mod[1]; } else { $param=''; };
                            if(file_exists(BASE_PATH . "system/modules/$path/config.php")){
                                require_once(BASE_PATH . "system/modules/$path/config.php");
                                // Load Resources
                                if($module->css!=""){
                                    $csses = explode(",",$module->css);
                                    foreach($csses as $css){
                                        $load->add_css .= "<link rel=\"stylesheet\" href=\"" . FOKIZ_PATH . "system/modules/" . $path . "/" . $css . "\" media=\"screen\">";
                                    }
                                }
                                if($module->js!=""){
                                    $jses = explode(",",$module->js);
                                    foreach($jses as $js){
                                        $load->add_js .= "<script src=\"" . FOKIZ_PATH . "system/modules/" . $path . "/" . $js . "\"></script>";
                                    }
                                }
                                ob_start();
                                $_GET['param'] = $param;
                                include(BASE_PATH . "system/modules/$path/" . $module->load);
                                $strOutput = ob_get_clean();
                            }else{ $strOutput = "<strong>[MODULE INCLUDE ERROR]</strong>"; }
                            break;
                        default:
                            $strOutput = '';
                    }
                    $aryInsert[$aryMatch[0][$m_key]] = $strOutput;
                }
            }
            foreach($aryInsert as $search=>$replace) { $content = str_replace($search,$replace,$content); }
        }
        return($content);
    }

?>
