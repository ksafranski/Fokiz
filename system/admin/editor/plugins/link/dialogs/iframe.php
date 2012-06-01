<?php

require_once('../../../../../../config.php');

?>
<html>
    <head>
        <style>
            body { font: normal 12px Arial,Helvetica,Tahoma,Verdana,Sans-Serif; overflow: hidden; }
            #page_links { border:1px solid #a0a0a0; background-color:white; padding: 2px 0; font-size: 12px; width: 97%; }
        </style>
    </head>
<body>
Page:<br />
<!--<input type="text" id="page_links" />-->
<select id="page_links">
    <option value="">- SELECT PAGE -</option>
    <?php
    
    $rs = mysql_query("SELECT pag_title, pag_url FROM cms_pages ORDER BY pag_title");
    while($row=mysql_fetch_array($rs)){
        echo("<option value=\"" . FOKIZ_PATH . stripslashes($row['pag_url']) . "\">" . stripslashes($row['pag_title']) . "</option>");
    }
    
    ?>
</select>
</body>
</html>