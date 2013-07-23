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
    // DATABASE
    //////////////////////////////////////////////////////////////////

    define("DB_HOST", "localhost");
    define("DB_USER", "fokiz_db_user");
    define("DB_PASS", "fokiz_db_password");
    define("DB_NAME", "fokiz_database");

    define("DB_CHARSET", "utf8");
    define("CHARSET", "utf-8");
    define("DB_DSN", "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET);

    //////////////////////////////////////////////////////////////////
    // ROOT PATH (Requires trailing and leading slash, "/" for root)
    //////////////////////////////////////////////////////////////////

    define("FOKIZ_PATH","/");

    //////////////////////////////////////////////////////////////////
    // LANGUAGE (See: /system/lang folder)
    //////////////////////////////////////////////////////////////////

    define("LANGUAGE","en");

    //////////////////////////////////////////////////////////////////
    // ANALYTICS
    //////////////////////////////////////////////////////////////////

    define("UA_CODE","UA-XXXXX-X");

    //////////////////////////////////////////////////////////////////
    // FOLLOW LINKS
    //////////////////////////////////////////////////////////////////

    // Links built on pages for following the site. Uses class
    // .follow and builds ul.follow>li>a structure ($load->follow)

    $follow['Facebook'] = "http://www.facebook.com";
    $follow['Twitter'] = "http://www.twitter.com";
    $follow['RSS'] = "/rss.xml";

    //////////////////////////////////////////////////////////////////
    // RESOURCES
    //////////////////////////////////////////////////////////////////

    // Links to outside resources such as analytics, CRM's, etc...
    // Format $resource['NAME'] = array('LINK','OPEN')
    // OPEN = 0: Modal, 1: New Window

    $resource['Google Analytics'] = array("https://www.google.com/analytics",1);
    $resource['HootSuite'] = array("http://www.hootsuite.com",0);

    //////////////////////////////////////////////////////////////////
    // TITLE DIVIDER
    //////////////////////////////////////////////////////////////////

    define("TITLE_DIVIDER","|");

    //////////////////////////////////////////////////////////////////
    // TIMEZONE
    //////////////////////////////////////////////////////////////////

    date_default_timezone_set('America/Chicago');

    //////////////////////////////////////////////////////////////////
    // LOAD RESOURCES
    //////////////////////////////////////////////////////////////////

    require_once('system/common.php');
    if(!$install){
    require_once('templates/config_templates.php');
    require_once('system/classes/class.system.php');
    require_once('system/classes/class.page.php');
    require_once('system/classes/class.block.php');
    require_once('system/classes/class.navigation.php');
    require_once('system/classes/class.user.php');
    require_once('system/classes/class.feed.php');
    require_once('system/classes/class.tag.php');
    require_once('system/classes/class.image.php');
    require_once('system/classes/class.apiql.php');
    require_once('system/loader.php');
    }

?>
