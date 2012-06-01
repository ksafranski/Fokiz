<?php

require_once('config.php');

?>
<table id="adm_blocks" class="adm_datatable">
    <thead>
        <tr>
            <th>Name</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($json_array as $block=>$data){ ?>
        <tr valign="top" id="block_<?php echo($block); ?>">
            <td><?php echo(stripslashes($block)); ?></td>
            <td class="adm_datatable_center"><a onclick="b_editor.open('<?php echo($block); ?>','no');">Edit</a></td>
            <td class="adm_datatable_center"><a onclick="b_editor.open('<?php echo($block); ?>','yes');">Copy</a></td>
            <td class="adm_datatable_center"><a onclick="b_list.delete_item('<?php echo($block); ?>');">Delete</a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>