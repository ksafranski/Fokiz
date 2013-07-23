<?php

    require_once('../../config.php');

    $pass = true;

    //////////////////////////////////////////////////////////////////
    // Check Permissions
    //////////////////////////////////////////////////////////////////

    $permissions['assets'] = true;
    $permissions['sitemap'] = true;
    $permissions['rss'] = true;

    if(!is_writeable($_SERVER['DOCUMENT_ROOT'] . "/assets")){ $permissions['assets'] = false; $pass = false; }
    if(!is_writeable($_SERVER['DOCUMENT_ROOT'] . "/sitemap.xml")){ $permissions['sitemap'] = false; $pass = false; }
    if(!is_writeable($_SERVER['DOCUMENT_ROOT'] . "/rss.xml")){ $permissions['rss'] = false; $pass = false; }

    //////////////////////////////////////////////////////////////////
    // Check Database
    //////////////////////////////////////////////////////////////////

    $database = true;

    try {
        $conn = new PDO(DB_DSN, DB_USER, DB_PASS);
    } catch (PDOException $e) {
        $database = false;
    }

    if($database==false){ $pass = false; }


?>
<!DOCTYPE html>

    <head>
        <title><?php echo lang('Fokiz Installer'); ?></title>
        <style>
            html, body { width: 100%; height: 100%; font: normal 13px Arial, Helvetica, sans-serif; line-height: 170%; background: #fff; overflow: hidden; }
            #dialog { display: block; position: absolute; z-index: 9999; width: 400px; margin: 0 0 0 -200px; padding: 15px; top: 130px; left: 50%; background: #e8e8e8; border: 2px solid #fff; color: #333; font: normal 13px 'Ubuntu', Verdana, Arial, sans-serif;
                -webkit-box-shadow: 0px 0px 40px 5px rgba(0, 0, 0, .4); -moz-box-shadow: 0px 0px 40px 5px rgba(0, 0, 0, .4); box-shadow: 0px 0px 40px 5px rgba(0, 0, 0, .4); -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;
            }

            h1 { margin: 0 0 15px 0; font-weight: normal; font-size: 22px; }

            label { display: block; font-weight: bold; color: #666; margin: 15px 0 5px 0; }

            input { width: 100%; display: inline !important; line-height: 100%; outline: none; padding: 5px 10px; margin: 0 10px 0 0; background: #fff; color: #707070; border: 1px solid #b8b8b8; font: normal 13px 'Ubuntu', Verdana, Arial, sans-serif;
                -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
            }
            input:focus { border: 1px solid #8c8c8c; color: #333; }

            button { width: auto; cursor: pointer; display: inline !important; line-height: 100%; outline: none; padding: 8px 10px; margin: 0 10px 0 0; background: #fff; color: #333; border: 1px solid #8f8f8f;
                background: rgb(247,247,247); /* Old browsers */
                background: -moz-linear-gradient(top, rgba(247,247,247,1) 0%, rgba(206,206,206,1) 100%); /* FF3.6+ */
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(247,247,247,1)), color-stop(100%,rgba(206,206,206,1))); /* Chrome,Safari4+ */
                background: -webkit-linear-gradient(top, rgba(247,247,247,1) 0%,rgba(206,206,206,1) 100%); /* Chrome10+,Safari5.1+ */
                background: -o-linear-gradient(top, rgba(247,247,247,1) 0%,rgba(206,206,206,1) 100%); /* Opera 11.10+ */
                background: -ms-linear-gradient(top, rgba(247,247,247,1) 0%,rgba(206,206,206,1) 100%); /* IE10+ */
                background: linear-gradient(top, rgba(247,247,247,1) 0%,rgba(206,206,206,1) 100%); /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7f7f7', endColorstr='#cecece',GradientType=0 ); /* IE6-9 */
                -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;
            }

            button:hover { color: #000;
                background: rgb(255,255,255); /* Old browsers */
                background: -moz-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(229,229,229,1) 100%); /* FF3.6+ */
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(229,229,229,1))); /* Chrome,Safari4+ */
                background: -webkit-linear-gradient(top, rgba(255,255,255,1) 0%,rgba(229,229,229,1) 100%); /* Chrome10+,Safari5.1+ */
                background: -o-linear-gradient(top, rgba(255,255,255,1) 0%,rgba(229,229,229,1) 100%); /* Opera 11.10+ */
                background: -ms-linear-gradient(top, rgba(255,255,255,1) 0%,rgba(229,229,229,1) 100%); /* IE10+ */
                background: linear-gradient(top, rgba(255,255,255,1) 0%,rgba(229,229,229,1) 100%); /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
            }

            p { margin: 20px 0; }

            ul { display: block; margin: 20px 0; padding: 0; background: #1a1a1a; border: 2px solid #000; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; }
            ul li { display: block; margin: 10px; list-style: none; color: #fff; font-family: "Lucida Console", "Courier New", Courier, Monaco, monospace !important; }

            hr { height: 1px; border: none; border-top: 1px solid #d1d1d1; margin: 15px 0; }

        </style>
    </head>

    <body>

    <div id="dialog">
        <h1><?php echo lang('Fokiz Installer'); ?></h1>
        <hr />
        <?php
        //////////////////////////////////////////////////////////////
        // FAIL
        //////////////////////////////////////////////////////////////
        if($pass==false){
        ?>
        <p>
        <?php echo lang('Something is not right. The system check returned the following:'); ?>
        </p>
        <ul>
        <?php
            if($permissions['assets']==false){ echo("<li>/assets " . $lang['must be writeable'] . "</li>"); }
            if($permissions['sitemap']==false){ echo("<li>/sitemap.xml " . $lang['must be writeable'] . "</li>"); }
            if($permissions['rss']==false){ echo("<li>/rss.xml " . $lang['must be writeable'] . "</li>"); }
            if($database==false){ echo("<li>" . $lang['Could not connect to database (Check /config.php)'] . "</li>"); }
        ?>
        </ul>
        <p>
        <?php echo lang('Once you have remedied the issues above please press the button to check requirements.'); ?>
        </p>
        <hr />
        <button id="rescan"><?php echo lang('Check Requirements'); ?></button>
        <?php
        //////////////////////////////////////////////////////////////
        // PASS
        //////////////////////////////////////////////////////////////
        }else{
        ?>
        <div id="install">
            <p>
            <?php echo lang('After a quick check it appears that all requirements have been met!<br />Please provide a username and password for the system, then press the [Install Fokiz] button to continue:'); ?>
            </p>
            <label><?php echo lang('Username'); ?></label>
            <input type="text" name="username" />
            <label><?php echo lang('Password'); ?></label>
            <input type="password" name="password" />
            <label><?php echo lang('Verify Password'); ?></label>
            <input type="password" name="password_v" />
            <hr />
            <button id="process"><?php echo lang('Install Fokiz'); ?></button>
        </div>
        <div id="error" style="display: none;">
            <p style="color: #a80a0a; font-weight: bold;"><?php echo lang('There was a problem installing the system. It is highly suggested you check all requirements and try again.'); ?></p>
        </div>
        <div id="complete" style="display: none;">
            <p>
            <?php echo lang('The system has been successfully installed.<br />To continue to the website, please click the button below.'); ?>
            </p>
            <hr />
            <button id="finish"><?php echo lang('Proceed to Website'); ?> &raquo;</button>
        </div>
        <?php
        }
        ?>
    </div>


    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo(BASE_URL); ?>js/jquery-1.6.2.min.js"%3E%3C/script%3E'));</script>

    <script>

        $(function(){

            // Rescan
            $('#rescan').click(function(){ location.reload(true); });

            // Process
            $('#process').click(function(){
                // Check account fields
                var un = $('input[name="username"]').val();
                var pw = $('input[name="password"]').val();
                var pv = $('input[name="password_v"]').val();
                var pass = true;
                // Validate fields
                if(un=="" || pw==""){ pass=false; alert('<?php echo lang('All Fields Must Be Filled Out'); ?>'); }
                // Check password length
                if(pw.length<8){ pass=false; alert('<?php echo lang('Password Minimum Of 8 Characters'); ?>'); }
                // Check passwords match
                if(pw!=pv){ pass=false; alert('<?php echo lang('Passwords Do Not Match'); ?>'); }

                if(pass==true){
                $('#process').html('Processing...').attr('disabled','disabled');
                    var params = {
                        u : un,
                        p : pw
                    };
                    $.post('<?php echo(BASE_URL); ?>system/install/process.php',params,function(data){
                        if(data=='pass'){
                            $('#install').hide();
                            $('#complete').show();
                        }else{
                            $('#install').hide();
                            $('#error').show();
                        }
                    });
                }
            });

            // Finish
            $('#finish').click(function(){ location.href='<?php echo(BASE_URL); ?>'; });

        });

    </script>

    </body>

</html>
