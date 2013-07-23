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

require_once('../controllers/navigation.php');

?>
<h1><?php echo lang('Navigation'); ?></h1>

<div class="nav_col" id="nav1">
    <div id="add1" class="add_nav"><button class="strong">+</button></div>
    <h2><?php echo lang('Primary'); ?></h2>
    <select id="navselect1" name="navselect1" class="nav_select" size="10">
    </select>
</div>

<div class="nav_col" id="nav2">
    <div id="add2" class="add_nav"><button class="strong">+</button></div>
    <h2><?php echo lang('Secondary'); ?></h2>
    <select id="navselect2" name="navselect2" class="nav_select" size="10">
    </select>
</div>

<div class="nav_col_last" id="nav3">
    <div id="add3" class="add_nav"><button class="strong">+</button></div>
    <h2><?php echo lang('Tertiary'); ?></h2>
    <select id="navselect3" name="navselect3" class="nav_select" size="10">
    </select>
</div>

<div class="clear">
<div id="edit_nav" class="hide">
    <input type="hidden" name="id" />
    <input type="hidden" name="level" />
    <input type="hidden" name="parent" />
    <input type="hidden" name="index" />
    <table style="width:100%;">
        <tr>
            <td><label><?php echo lang('Title'); ?></label></td>
            <td><label><?php echo lang('URL'); ?></label></td>
            <td>&nbsp;</td>
            <td class="action_holder">&nbsp;</td>
        </tr>
        <tr>
            <td><input type="text" name="title" onkeyup="$('#nav_save').html('<?php echo lang('Save'); ?>');" /></td>
            <td><input type="text" name="url" onkeyup="$('#nav_save').html('<?php echo lang('Save'); ?>');" id="ac_url" /></td>
            <td>
                <button id="nav_save"><?php echo lang('Save'); ?></button>
            </td>
            <td class="action_holder">
                <select id="nav_actions" style="width: 110px;">
                    <option><?php echo lang('ACTIONS'); ?>...</option>
                    <option value="0"><?php echo lang('Move Up'); ?></option>
                    <option value="1"><?php echo lang('Move Down'); ?></option>
                    <option value="2"><?php echo lang('Delete'); ?></option>
                </select>
            </td>
        </tr>
    </table>
</div>
</div>

<hr />

<button onclick="url.refresh();"><?php echo lang("Close"); ?></button>

<script>

    $(function(){
        // URL Autocomplete
        $("#ac_url").autocomplete('system/admin/helpers/autocomplete.php', {
            formatItem: function(rowdata) {
                var details = rowdata[0].split(":::");
                return details[1];
            },
            formatResult: function (rowdata) {
                var details = rowdata[0].split(":::");
                $('#ac_url').val(details[0]);
                return details[0];
            }
         });

        // Init, Load First
        loadList(1,0);
        // On selection
        $('.nav_select').each(function(){
            $(this).change(function(){
                var level = $(this).parent('div').attr('id').replace("nav","");
                var value = $(this).val();
                var values = value.split('|!|');
                var nav_id = values[0];
                var nav_url = values[1];
                var nav_title = values[2];
                $('input[name="index"]').val($('#navselect'+level+' option:selected').index());
                if(level<3){
                    loadList(parseInt(level)+1,nav_id);
                    $('.action_holder').each(function(){ $(this).show(); });
                }
                loadObject(nav_id,nav_url,nav_title,level,$('#navselect'+level).attr('rel'));
            });
        });
        // Save Object
        $('#nav_save').click(function(){ saveObject(); $(this).html('<?php echo lang('SAVED'); ?>'); });
        // Add Object
        $('.add_nav').each(function(){
            $(this).click(function(){
                var level = $(this).parent('div').attr('id').replace('nav','');
                loadObject('new','','',level,$(this).siblings('select').attr('rel'));
                $('.action_holder').each(function(){ $(this).hide(); });
                $('#navselect'+level).addClass('nav_active');
            });
        });
        // Actions
        $('#nav_actions').change(function(){
            var action = parseInt($(this).val());
            switch(action){
                case 0:
                    moveObjectUp();
                    break;
                case 1:
                    moveObjectDown();
                    break;
                case 2:
                    deleteObject();
                    break;
            }
            $("#nav_actions option:first").attr('selected','selected');
        });

    });

    function loadList(id,parent){
        // Hide add buttons
        $('#add2, #add3').hide();
        // Show add button(s)
        if(id==2){ $('#add2').show(); }
        if(id==3){ $('#add2, #add3').show(); }
        // Show loading...
        $('#nav'+id+' select').html("<option><?php echo lang('Loading'); ?>...</option>");
        // Process
        $.post('system/admin/controllers/navigation.php',{list:parent},function(data){
            $('#nav'+id+' select').html(data).attr('rel',parent);
        });
    }

    function loadObject(i,u,t,l,p){
        $('#edit_nav').removeClass('hide');
        $('input[name="level"]').val(l);
        $('input[name="parent"]').val(p);
        $('input[name="id"]').val(i);
        $('input[name="title"]').val(t);
        $('input[name="url"]').val(u);
        $('.nav_active').removeClass('nav_active');
        $('#nav_save').html('<?php echo lang('Save'); ?>');
    }

    function saveObject(){
        if($('input[name="id"]').val()=="new"){
            createObject();
        }else{
            updateObject();
        }
    }

    function createObject(){
        var params = {
            parent: $('input[name="parent"]').val(),
            id : $('input[name="id"]').val(),
            title: $('input[name="title"]').val(),
            url : $('input[name="url"]').val(),
            parent : $('input[name="parent"]').val()
        };
        $.post('system/admin/controllers/navigation.php?create=t',params,function(data){
            $('input[name="id"]').val(data);
            // Create option element
            $('#nav'+$('input[name="level"]').val()+' select')
             .append($("<option></option>")
             .attr("value",data+'|!|'+$('input[name="url"]').val()+'|!|'+$('input[name="title"]').val())
             .attr("selected","selected")
             .text($('input[name="title"]').val()));
            // Update index value
            var index = $('#nav'+$('input[name="level"]').val()+' select option').size()-1;
            $('input[name="index"]').val(index);
            loadList(parseInt($('input[name="level"]').val())+1,data);
            $('.action_holder').show();
            $('#nav'+$('input[name="level"]').val()+' select').removeClass('nav_active');
        });
    }

    function updateObject(){
        var params = {
            id : $('input[name="id"]').val(),
            title: $('input[name="title"]').val(),
            url : $('input[name="url"]').val()
        };
        $.post('system/admin/controllers/navigation.php?update=t',params,function(){
            var level = $('input[name="level"]').val();
            var index = $('input[name="index"]').val();
            $('#nav'+level+' select option').eq(index)
                .text($('input[name="title"]').val())
                .val($('input[name="id"]').val()+'|!|'+$('input[name="url"]').val()+'|!|'+$('input[name="title"]').val());
        });
    }

    function moveObjectUp(){
        if($('input[name="index"]').val()==0){
            alert('<?php echo lang('This item is already at the first position.'); ?>');
        }else{
            var level = $('input[name="level"]').val();
            move('navselect'+level,'up');
            $('input[name="index"]').val($('#nav'+level+' select option:selected').index());
            $.post('system/admin/controllers/navigation.php?move=t',{ id : $('input[name="id"]').val(), parent : $('input[name="parent"]').val(), direction : '0' });
        }
    }

    function moveObjectDown(){
        if($('input[name="index"]').val()==($('#navselect'+$('input[name="level"]').val()+' option').size()-1)){
            alert('<?php echo lang('This item is already at the last position.'); ?>');
        }else{
            var level = $('input[name="level"]').val();
            move('navselect'+level,'down');
            $('input[name="index"]').val($('#nav'+level+' select option:selected').index());
            $.post('system/admin/controllers/navigation.php?move=t',{ id : $('input[name="id"]').val(), parent : $('input[name="parent"]').val(), direction : '1' });
        }
    }

    function deleteObject(){
        var answer = confirm("<?php echo lang('Delete this item permanently?'); ?>");
        if(answer){
            $.get('system/admin/controllers/navigation.php?delete='+$('input[name="id"]').val(),function(){
                $('#edit_nav').addClass('hide');
                loadList(parseInt($('input[name="level"]').val()),$('input[name="parent"]').val());
                level = parseInt($('input[name="level"]').val());
                $('#navselect'+(level+1)).html('<option></option>');
                $('#navselect'+(level+2)).html('<option></option>');
            });
        }
    }

    function move(name,w){
        var sel=document.getElementsByName(name)[0];
        var opt=sel.options[sel.selectedIndex];
        if(w=='up'){
        var prev=opt.previousSibling;
            while(prev&&prev.nodeType!=1){
            prev=prev.previousSibling;
            }
        prev?sel.insertBefore(opt,prev):sel.appendChild(opt)
        }
        else{
        var next=opt.nextSibling;
            while(next&&next.nodeType!=1){
            next=next.nextSibling;
            }
            if(!next){sel.insertBefore(opt,sel.options[0])}
            else{
            var nextnext=next.nextSibling;
                while(next&&next.nodeType!=1){
                next=next.nextSibling;
                }
            nextnext?sel.insertBefore(opt,nextnext):sel.appendChild(opt);
            }
        }
    }

</script>
