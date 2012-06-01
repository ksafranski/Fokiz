<?php

require_once('../../../../../../config.php');

checkToken(); // Check Authentication Token

$modules = array();

// Build modules array
foreach(glob('../../../../../modules/*', GLOB_ONLYDIR) as $dir) {
    $dir = str_replace("../../../../../modules/","",$dir);
    require("../../../../../modules/$dir/config.php");
    $modules[] = array(
        'folder'=>$dir,
        'name'=>$module->name,
        'desc'=>$module->description,
        'param'=>$module->param,
        'param_options'=>$module->param_options
    );
}

?>
<html>
<head>
<style>
    html, body { font: normal 13px Arial, Helvetica, sans-serif; background: #ebebeb; line-height: 150%; }
    input, select { border:1px solid #a0a0a0; background-color:white; padding: 2px 0; font-size: 12px; width: 100%; }
    .param { display: none; }
    .desc { height: 50px; padding: 5px; margin: 0 0 10px 0; overflow: auto; border: 1px solid #ccc; background: #e3e3e3; }
</style>
</head>
<body>

Module:<br />
<select id="module_selector">
    <option value="">-SELECT MODULE-</option>
    <?php
    
    // List out all modules
    foreach($modules as $module){
        echo("<option value=\"" . $module['folder'] . "\">" . $module['name'] . "</option>");
    }
    
    ?>
</select>

<?php

    // Build containers for description and parameters
    foreach($modules as $module){
    
        echo("<div class=\"param\" id=\"" . $module['folder'] . "\">");
        
        echo("<br />Description:<p class=\"desc\">" . $module['desc'] . "</p>");
        
        // Parameters
        if($module['param']!=""){
        
            echo($module['param'] . ":<br />");
            
            // Option or input?
            if($module['param_options']==""){
                echo("<input class=\"param_field\" name=\"param\" id=\"param\" value=\"\" />");
            }else{
                echo("<select class=\"param_field\" id=\"param\" name=\"param\">");
                
                    $params = explode(",",$module['param_options']);
                    foreach($params as $param){
                        echo("<option value=\"$param\">$param</option>");
                    }
                
                echo("</select>");
            }
        
        }
        
        echo("</div>");
    
    }

?>

<!-- This content gets inserted... -->
<input type="hidden" id="embed" value="STUFF..." />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script>!window.jQuery && document.write(unescape('%3Cscript src="/js/jquery-1.6.2.min.js"%3E%3C/script%3E'));</script>

<script>

    selector = $('#module_selector');
    embed = $('#embed');

    $(function(){
    
        selector.change(function(){
            var cur = $(this).val();
            $('.param').hide();
            $('#'+cur).show();
            buildEmbed();
        });
        
        $('.param_field').each(function(){
            $(this).keydown(function(){ buildEmbed(); });
            $(this).change(function(){ buildEmbed(); });
        });
        
    
    });
    
    function buildEmbed(){
        var param = $('#'+selector.val()+'>#param').val();
        if(param!="" && param!=undefined){
            embed.val('[[module:'+selector.val()+'=>'+$('#'+selector.val()+'>[name="param"]').val()+']]');
        }else{
            embed.val('[[module:'+selector.val()+']]');
        }
    }

</script>

</body>
</html>