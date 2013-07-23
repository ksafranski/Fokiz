<?php
require_once('config.php');
require_once('system/modules/url_rewrite/handler.php');
?>
<!DOCTYPE html>

<!--[if lte IE 8 ]><html class="ie"><![endif]-->

<head>
    <meta charset="utf-8">
    <title><?php echo(escape($load->title)); ?></title>
    <meta name="description" content="<?php echo(escape($load->description)); ?>">
    <meta name="keywords" content="<?php echo(escape($load->keywords)); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo(BASE_URL); ?>" />
    <!--[if lt IE 9]>
    <script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js">IE7_PNG_SUFFIX=".png";</script>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="icon" type="image/x-icon" href="<?php echo(BASE_URL); ?>favicon.ico" />
    <link rel="stylesheet" href="<?php echo(FOKIZ_PATH); ?>css/reset.css" media="screen">
    <link rel="stylesheet" href="<?php echo(FOKIZ_PATH); ?>css/templates.css" media="screen">
    <link rel="stylesheet" href="<?php echo(FOKIZ_PATH); ?>css/screen.css" media="screen">
    <link rel="stylesheet" href="<?php echo(FOKIZ_PATH); ?>css/mobile.css" media="screen and (max-width: 600px)" />
    <link rel="stylesheet" href="<?php echo(FOKIZ_PATH); ?>css/print.css" media="print">
    <?php echo($load->add_css); ?>
</head>

<body data-id="<?php echo(escape($load->page_id)); ?>">

    <header>

        <h1>Fokiz Content Management System</h1>

        <nav><?php echo($load->navigation); ?></nav>

    </header>

    <div id="main"><!-- #main -->

        <?php echo($load->content); ?>

    <div class="clear"></div> <!-- Clear out floated columns -->
    </div><!-- /#main -->

    <footer>
    <div class="right"><?php echo($load->follow); ?></div>
    <p>Demo of Fokiz Content Management System &nbsp;&nbsp;&middot;&nbsp;&nbsp; Copyright &copy;<?php echo(date('Y')); ?></p>
    </footer>

    <?php echo($load->admin_elements); ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>
    !window.jQuery && document.write(
        unescape('%3Cscript src="<?php echo(FOKIZ_PATH); ?>js/jquery-1.7.2.min.js"%3E%3C/script%3E')
    );
</script>

<!-- Default Scripts -->
<script src="<?php echo(FOKIZ_PATH); ?>js/jquery.responsinav.min.js"></script>
<!--<script src="<?php echo(FOKIZ_PATH); ?>js/jquery.css3finalize.js"></script>-->
<script src="<?php echo(FOKIZ_PATH); ?>js/common.js"></script>

<?php echo($load->add_js); ?>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo(UA_CODE); ?>']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</body>
</html>
