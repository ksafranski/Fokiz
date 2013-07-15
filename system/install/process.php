<?php

    require_once('../../config.php');
    global $conn;

    ini_set('display_errors', '1');
    error_reporting(E_ALL | E_STRICT);

    if(!$install){ exit(); }

    // Get initial u/n and p/w
    $un = $_POST['u'];
    $pw = sha1(md5($_POST['p']));

    //////////////////////////////////////////////////////////////////
    // Install Database
    //////////////////////////////////////////////////////////////////

    // Create CMS_BLOCKS /////////////////////////////////////////////

    $query = "CREATE TABLE cms_blocks (
      blk_id int(11) NOT NULL AUTO_INCREMENT,
      blk_content longtext NOT NULL,
      blk_created datetime NOT NULL,
      blk_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (blk_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Insert Default CMS_BLOCKS /////////////////////////////////////

    $query = "
    INSERT INTO cms_blocks (blk_id, blk_content, blk_created, blk_modified) VALUES
    (1, '<h1>Welcome to Fokiz</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus quis lectus metus, at posuere neque. Sed pharetra nibh eget orci convallis at posuere leo convallis. Sed blandit augue vitae augue scelerisque bibendum. Vivamus sit amet libero turpis, non venenatis urna. In blandit, odio convallis suscipit venenatis, ante ipsum cursus augue.</p><p>Et mollis nunc diam eget sapien. Nulla facilisi. Etiam feugiat imperdiet rhoncus. Sed suscipit bibendum enim, sed volutpat tortor malesuada non. Morbi fringilla dui non purus porttitor mattis. Suspendisse quis vulputate risus. Phasellus erat velit, sagittis sed varius volutpat, placerat nec urna. Nam eu metus vitae dolor fringilla feugiat. Nulla.</p>', now(), now()),
    (2, '<h1>Facilisi Etiam Enim</h1><p>Facilisi. Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas ligula, quis hendrerit ipsum ullamcorper et. Phasellus id tristique orci. Proin consequat mi at felis scelerisque ullamcorper. Etiam tempus, felis vel eleifend porta, velit nunc mattis urna, at ullamcorper erat diam dignissim ante. Pellentesque justo risus.</p>', now(), now()),
    (3, '<h1>About Fokiz.</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus quis lectus metus, at posuere neque. Sed pharetra nibh eget orci convallis at posuere leo convallis. Sed blandit augue vitae augue scelerisque bibendum. Vivamus sit amet libero turpis, non venenatis urna. In blandit, odio convallis suscipit venenatis, ante ipsum cursus augue.</p>', now(), now()),
    (4, '<h1>Facilisi Etiam</h1><p>Facilisi. Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas ligula, quis hendrerit ipsum ullamcorper et. Phasellus id tristique orci. Proin consequat mi at felis scelerisque ullamcorper. Etiam tempus, felis vel eleifend porta, velit nunc mattis urna, at ullamcorper erat diam dignissim ante. Pellentesque justo risus!&nbsp;Proin consequat mi at felis scelerisque ullamcorper.</p><p>Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas.</p>', now(), now()),
    (5, '<h1>Phasellus Quis</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus quis lectus metus, at posuere neque. Sed pharetra nibh eget orci convallis at posuere leo convallis. Sed blandit augue vitae augue scelerisque bibendum. Vivamus sit amet libero turpis, non venenatis urna. In blandit, odio convallis suscipit venenatis, ante ipsum cursus augue.</p><p>Sed pharetra nibh eget orci convallis at posuere leo convallis. Sed blandit augue vitae augue scelerisque bibendum.&nbsp;</p>', now(), now()),
    (6, '<h1>Services Provided</h1><p>Facilisi. Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas ligula, quis hendrerit ipsum ullamcorper et. Phasellus id tristique orci. Proin consequat mi at felis scelerisque ullamcorper. Etiam tempus, felis vel eleifend porta, velit nunc mattis urna, at ullamcorper erat diam dignissim ante. Pellentesque justo risus.</p>', now(), now()),
    (7, '<h1>Facilisi Etiam</h1><p>Facilisi. Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas ligula, quis hendrerit ipsum ullamcorper et. Phasellus id tristique orci. Proin consequat mi at felis scelerisque ullamcorper. Etiam tempus, felis vel eleifend porta, velit nunc mattis urna, at ullamcorper erat diam dignissim ante. Pellentesque justo risus.</p>', now(), now()),
    (8, '<h1>Facilisi Etiam</h1><p>Facilisi. Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas ligula, quis hendrerit ipsum ullamcorper et. Phasellus id tristique orci. Proin consequat mi at felis scelerisque ullamcorper. Etiam tempus, felis vel eleifend porta, velit nunc mattis urna, at ullamcorper erat diam dignissim ante. Pellentesque justo risus.</p>', now(), now()),
    (9, '<h1>Facilisi Etiam</h1> <p>Facilisi. Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas ligula, quis hendrerit ipsum ullamcorper et. Phasellus id tristique orci. Proin consequat mi at felis scelerisque ullamcorper. Etiam tempus, felis vel eleifend porta, velit nunc mattis urna, at ullamcorper erat diam dignissim ante. Pellentesque justo risus.</p>', now(), now()),
    (10, '<h1>Contact Us</h1><p>Facilisi. Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas ligula, quis hendrerit ipsum ullamcorper et. Phasellus id tristique orci. Proin consequat mi at felis scelerisque ullamcorper. Etiam tempus, felis vel eleifend porta, velit nunc mattis urna, at ullamcorper erat diam dignissim ante. Pellentesque justo risus.</p>', now(), now()),
    (11, '<h2>Content Section</h2><p>Facilisi. Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas ligula, quis hendrerit ipsum ullamcorper et. Phasellus id tristique orci. Proin consequat.</p>', now(), now()),
    (15, '<h2>Content Section</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus quis lectus metus, at posuere neque. Sed pharetra nibh eget orci convallis at posuere leo convallis. Sed blandit augue vitae augue scelerisque bibendum. Vivamus sit amet libero turpis, non venenatis urna. In blandit, odio convallis suscipit venenatis, ante ipsum cursus augue.</p>', now(), now()),
    (12, '<h1>Facilisi Etiam</h1><p>Facilisi. Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas ligula, quis hendrerit ipsum ullamcorper et. Phasellus id tristique orci. Proin consequat mi at felis scelerisque ullamcorper. Etiam tempus, felis vel eleifend porta, velit nunc mattis urna, at ullamcorper erat diam dignissim ante. Pellentesque justo risus!&nbsp;Proin consequat mi at felis scelerisque ullamcorper.</p><p>Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas.</p>', now(), now()),
    (14, '<h2>Lorem Ipsum Dolor Sit</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus quis lectus metus, at posuere neque. Sed pharetra nibh eget orci convallis at posuere leo convallis. Sed blandit augue vitae augue scelerisque bibendum.</p><p>Et mollis nunc diam eget sapien. Nulla facilisi. Etiam feugiat imperdiet rhoncus. Sed suscipit bibendum enim, sed volutpat tortor malesuada non. Morbi fringilla dui non purus porttitor mattis. Suspendisse quis vulputate risus. Phasellus erat velit, sagittis sed varius volutpat, placerat nec urna. Nam eu metus vitae dolor fringilla feugiat. Nulla.</p>', now(), now()),
    (13, '<h2>Content Section</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus quis lectus metus, at posuere neque. Sed pharetra nibh eget orci convallis at posuere leo convallis. Sed blandit augue vitae augue scelerisque bibendum. Vivamus sit amet libero turpis, non venenatis urna. In blandit, odio convallis suscipit venenatis, ante ipsum cursus augue.</p>', now(), now());
    ";

    $conn->query($query);


    // Create CMS_BLOCKS_TEMP ////////////////////////////////////////

    $query = "CREATE TABLE cms_blocks_temp (
      btp_id int(11) NOT NULL AUTO_INCREMENT,
      btp_blk_id int(11) NOT NULL,
      btp_content longtext NOT NULL,
      PRIMARY KEY (btp_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Create CMS_FEED ///////////////////////////////////////////////

    $query = "CREATE TABLE cms_feed (
      fed_id int(11) NOT NULL AUTO_INCREMENT,
      fed_pag_id int(11) NOT NULL,
      fed_pub_date datetime NOT NULL,
      PRIMARY KEY (fed_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Create CMS_MAPPING ////////////////////////////////////////////

    $query = "CREATE TABLE cms_mapping (
      map_id int(11) NOT NULL AUTO_INCREMENT,
      map_pag_id int(11) NOT NULL,
      map_blk_id int(11) NOT NULL,
      map_region int(2) NOT NULL,
      PRIMARY KEY (map_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Insert Default CMS_MAPPING ////////////////////////////////////

    $query = "
    INSERT INTO cms_mapping (map_id, map_pag_id, map_blk_id, map_region) VALUES
    (1, 1, 1, 0),
    (2, 1, 2, 1),
    (3, 2, 3, 0),
    (4, 2, 4, 1),
    (5, 2, 5, 2),
    (6, 3, 6, 0),
    (7, 3, 7, 1),
    (8, 3, 8, 2),
    (9, 3, 9, 3),
    (10, 4, 10, 0),
    (11, 4, 11, 1),
    (12, 2, 12, 3),
    (13, 1, 14, 2),
    (14, 4, 13, 2),
    (15, 1, 15, 3);
    ";

    $conn->query($query);

    // Create CMS_NAVIGATION /////////////////////////////////////////

    $query = "CREATE TABLE cms_navigation (
      nav_id int(11) NOT NULL AUTO_INCREMENT,
      nav_parent int(11) NOT NULL DEFAULT '0',
      nav_title varchar(255) NOT NULL,
      nav_url varchar(255) NOT NULL,
      nav_order int(2) NOT NULL,
      PRIMARY KEY (nav_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Insert Default CMS_NAVIGATION /////////////////////////////////

    $query = "
    INSERT INTO cms_navigation (nav_id, nav_parent, nav_title, nav_url, nav_order) VALUES
    (1, 0, 'Home', '" . FOKIZ_PATH . "home', 1),
    (2, 0, 'About', '" . FOKIZ_PATH . "about', 2),
    (3, 0, 'Services', '" . FOKIZ_PATH . "services', 3),
    (4, 0, 'Contact', '" . FOKIZ_PATH . "contact', 4);
    ";

    $conn->query($query);

    // Create CMS_PAGES //////////////////////////////////////////////

    $query = "CREATE TABLE cms_pages (
      pag_id int(11) NOT NULL AUTO_INCREMENT,
      pag_title varchar(255) NOT NULL,
      pag_template int(2) NOT NULL,
      pag_url varchar(255) NOT NULL,
      pag_description varchar(255) DEFAULT NULL,
      pag_keywords varchar(255) DEFAULT NULL,
      pag_created datetime NOT NULL,
      pag_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (pag_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Insert Default CMS_PAGES //////////////////////////////////////

    $query = "
    INSERT INTO cms_pages (pag_id, pag_title, pag_template, pag_url, pag_description, pag_keywords, pag_created, pag_modified) VALUES
    (1, 'Home', 6, 'home', 'Welcome to the Website', 'home,page', now(), now()),
    (2, 'About', 8, 'about', 'The About Page', 'about,page', now(), now()),
    (3, 'Services', 8, 'services', 'The Services Page', 'services,page', now(), now()),
    (4, 'Contact', 6, 'contact', 'The Contact Page', 'contact,page', now(), now());
    ";

    $conn->query($query);

    // Create CMS_PAGES_TEMP /////////////////////////////////////////

    $query = "CREATE TABLE cms_pages_temp (
      ptp_id int(11) NOT NULL AUTO_INCREMENT,
      ptp_pag_id int(11) NOT NULL,
      ptp_title varchar(255) NOT NULL,
      ptp_template int(2) NOT NULL,
      ptp_url varchar(255) NOT NULL,
      ptp_description longtext NOT NULL,
      ptp_keywords longtext NOT NULL,
      PRIMARY KEY (ptp_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Create CMS_PUBLISH_NOTES //////////////////////////////////////

    $query = "CREATE TABLE cms_publish_notes (
      pbn_id int(11) NOT NULL AUTO_INCREMENT,
      pbn_pag_id int(11) NOT NULL,
      pbn_notes longtext NOT NULL,
      pbn_pub_date datetime NOT NULL,
      PRIMARY KEY (pbn_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Create CMS_SYSTEM /////////////////////////////////////////////

    $query = "CREATE TABLE cms_system (
      sys_id int(1) NOT NULL,
      sys_title varchar(255) NOT NULL,
      sys_description longtext,
      sys_keywords varchar(255) DEFAULT NULL,
      sys_default_page int(11) NOT NULL DEFAULT '1',
      PRIMARY KEY (sys_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Insert Default CMS_SYSTEM /////////////////////////////////////

    $query = "
    INSERT INTO cms_system (sys_id, sys_title, sys_description, sys_keywords, sys_default_page) VALUES
    (1, 'Fokiz', 'Fokiz CMS', 'fokiz,cms,website', 1);
    ";

    $conn->query($query);

    // Create CMS_TAGS ///////////////////////////////////////////////

    $query = "CREATE TABLE cms_tags (
      tag_id int(11) NOT NULL AUTO_INCREMENT,
      tag_title varchar(255) NOT NULL,
      tag_pag_id int(11) NOT NULL,
      PRIMARY KEY (tag_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Create CMS_USERS //////////////////////////////////////////////

    $query = "CREATE TABLE cms_users (
      usr_id int(5) NOT NULL AUTO_INCREMENT,
      usr_login varchar(255) NOT NULL,
      usr_password varchar(255) NOT NULL,
      usr_type int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (usr_id)
    ) ENGINE=MyISAM;";

    $conn->query($query);

    // Insert Default CMS_USERS //////////////////////////////////////

    $query = "
    INSERT INTO cms_users (usr_id, usr_login, usr_password, usr_type) VALUES
    (1, ?, ?, 0);
    ";

    $conn->prepare($query)->execute(array($un, $pw));

    //////////////////////////////////////////////////////////////////
    // Echo PASS
    //////////////////////////////////////////////////////////////////

    echo('pass');

    // Echo'ing 'pass' let's the processor know it went through, any errors
    // or stray data and it won't match the return condition.


?>