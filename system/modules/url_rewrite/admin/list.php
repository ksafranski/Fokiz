<?php 

    require_once('config.php'); 

    function shortenLeft($val){
        if(strlen($val) > 35){ return("&hellip;" . ltrim(substr($val, 0, 34))); }else{ return($val); }
    }

?>
<table id="url_rewrites" width="100%">
    <thead>
        <tr>
            <th>Old Path</th>
            <th>New Path</th>
            <th width="10%" class="no-sort">Remove</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $out = "";
        if(count($url_rewrites)>0){
            foreach($url_rewrites as $old_path=>$new_path){
                $out .= "<tr data-path=\"$old_path\">";
                $out .= "<td><span title=\"$old_path\">" . shortenLeft($old_path) . "</span></td>";
                $out .= "<td><span title=\"$new_path\">" . shortenLeft($new_path) . "</span></td>";
                $out .= "<td style=\"text-align:center;\"><a onclick=\"url_rewrite.remove('$old_path');\">X</a></td>";
                $out .= "</tr>";
            }
        }
        echo($out);
        ?>
    </tbody>
</table>
<script>
    $(function(){ 
        datatable.init('url_rewrites');
    });
</script>