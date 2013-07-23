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

require_once('../controllers/pages.php');

?>
<h1><?php echo lang('Page Manager'); ?></h1>

<button onclick="modal.open('system/admin/views/page_editor.php?id=new',500);"><?php echo lang('Create New Page'); ?></button>
<div class="adm_v_spacer"></div>

<input type="hidden" id="cur_page" value="<?php echo($_SESSION['cur_page']); ?>" />
<input type="hidden" id="def_page" value="<?php echo($system->default_page); ?>" />

<table id="pages" class="adm_datatable">
    <thead>
        <tr>
            <th class="no-sort"></th>
            <th><?php echo lang('Title'); ?></th>
            <th><?php echo lang('Description'); ?></th>
            <th><?php echo lang('Keywords'); ?></th>
            <th><?php echo lang('Created'); ?></th>
            <th><?php echo lang('Modified'); ?></th>
            <th class="no-sort"></th>
            <th class="no-sort"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $page){ ?>
        <tr valign="top" id="page_<?php echo(escape($page['id'])); ?>" <?php if($page['pending']){ echo("style=\"font-weight:bold;\""); } ?>>
            <td>
            <?php
            if($page['pending']){
                echo("<a title=\"Pending Changes\" href=\"" . BASE_URL . $page['url'] . "\"><img width=\"12\" height=\"12\" src=\"system/admin/editor/filemgr/images/icon_file_rename.png\"></a>");
            }
            ?>
            </td>
            <td>
            <?php

            echo(escape($page['title']));

            ?></td>
            <td><span class="adm_breakable"><?php echo(escape($page['description'])); ?></span></td>
            <td><span class="adm_breakable"><?php echo(escape($page['keywords'])); ?></span></td>
            <td><?php echo(formatTimestamp($page['created'])); ?></td>
            <td><?php echo(formatTimestamp($page['modified'])); ?></td>
            <td class="adm_datatable_center"><a href="<?php echo(BASE_URL . escape($page['url'])); ?>"><?php echo lang('Go'); ?>&nbsp;&raquo;</a></td>
            <td class="adm_datatable_center"><a onclick="deletePage(<?php echo(escape($page['id'])); ?>);"><?php echo lang('Delete'); ?></a></td>
        </tr>
        <?php } ?>
    </tbody>
</table>


<button onclick="modal.hide();"><?php echo lang('Close'); ?></button>

<script>

    $(function(){
        datatable.init('pages');
    });

    function deletePage(id){
        if($('#cur_page').val()==id){
            alert('<?php echo lang('You are currently viewing this page. You must move to a different page first.'); ?>');
        }else if($('#def_page').val()==id){
            alert('<?php echo lang('The page selected is the website default and cannot be deleted.'); ?>');
        }else{
            var answer = confirm("<?php echo lang('Delete page permanantly?'); ?>");
            if(answer){
                $.get('system/admin/controllers/pages.php?del='+id);
                $('tr#page_'+id).remove();
            }
        }
    }

</script>