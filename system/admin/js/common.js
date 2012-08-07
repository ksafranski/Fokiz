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
    // INIT
    //////////////////////////////////////////////////////////////////

    $(function(){
        bar.init();
        modal.hide();
    });
    
    //////////////////////////////////////////////////////////////////
    // OBJECTS
    //////////////////////////////////////////////////////////////////

    // ------------------------------------------------------------ //
    
    //////////////////////////////////////////////////////////////////
    // BAR
    //////////////////////////////////////////////////////////////////
    
    bar_obj = $('#adm_bar');
    bar_obj_contents = $('#adm_bar_contents');
    bar_control = $('#adm_bar_control a');
    
    var bar = {
    
        init : function(){
            bar_obj.fadeIn(200);
            
            // Hide Bar (If set in LS)
            if(localStorage.bar=='hide'){
                var new_top = bar_obj.outerHeight()-2;
                bar_obj.css({ 'top' : '-'+new_top+'px' },300);
                bar_control.attr('rel','1').removeClass('up').addClass('down');
            }
            
            bar_obj_contents.load('system/admin/views/bar.php?page='+$('body').attr('data-id'),function(){
                // Edit Mode
                if(localStorage.editmode=='on'){
                    edit_mode.activate();
                    $.get('system/admin/helpers/js_lang.php?t='+escape('Done Editing'),function(data){
                        $('#adm_btn_edit').attr('rel','1').html(data);
                    });
                }else{
                    edit_mode.deactivate();
                    $.get('system/admin/helpers/js_lang.php?t='+escape('Edit Content'),function(data){
                        $('#adm_btn_edit').attr('rel','0').html(data);
                    });
                }
                $('#adm_btn_edit').click(function(){ 
                    var cur = $(this).attr('rel');
                    if(cur==0){
                        edit_mode.activate();
                        $.get('system/admin/helpers/js_lang.php?t='+escape('Done Editing'),function(data){
                            $('#adm_btn_edit').attr('rel','1').html(data);
                        });
                    }else{
                        edit_mode.deactivate();
                        $.get('system/admin/helpers/js_lang.php?t='+escape('Edit Content'),function(data){
                            $('#adm_btn_edit').attr('rel','0').html(data);
                        });
                    }
                });
                
                // Bar show/hide control
                bar_control.click(function(){ bar.toggle(); });
                
                // Init dropdowns
                dropdowns.init();
            });
        },
        
        toggle : function(){
            rel = bar_control.attr('rel');
            if(rel==0){ bar.hide(); bar_control.attr('rel','1').removeClass('up').addClass('down'); }
            else{ bar.show(); bar_control.attr('rel','0').removeClass('down').addClass('up'); }
        },
        
        show : function(){
            bar_obj.animate({ 'top' : '0' },300);
            localStorage.bar = 'show';
        },
        
        hide : function(){
            var new_top = bar_obj.outerHeight()-2;
            bar_obj.animate({ 'top' : '-'+new_top+'px' },300);
            localStorage.bar = 'hide';
            dropdowns.hide();
        }
        
    };
    
    //////////////////////////////////////////////////////////////////
    // BAR DROPDOWNS
    //////////////////////////////////////////////////////////////////

    var dropdowns = {
        
        init : function(){
            $('#adm_btn_components, #adm_btn_resources, #adm_btn_modules').click(function(){
                if(!$(this).hasClass('active')){
                    dropdowns.hide();
                    var child = '#adm_'+$(this).attr('rel');
                    var ofs = $(this).offset();
                    var l = ofs.left;
                    $(child).css({'left':l+'px'}).fadeIn(300);
                    $(child).hover(function(){ },function(){ dropdowns.hide(); });
                    $(this).addClass('active');
                }else{
                    $(this).removeClass('active');
                    dropdowns.hide();
                }
            });
            
            $('.adm_dropdown').each(function(){
                $(this).click(function(){
                    dropdowns.hide();
                });
            });
        },
        
        hide : function(){
            $('#adm_bar button.active').removeClass('active');
            $('.adm_dropdown').each(function(){
                $(this).fadeOut(300);
            });
        }
    }

    //////////////////////////////////////////////////////////////////
    // MODAL
    //////////////////////////////////////////////////////////////////
    
    modal_obj = $('#adm_modal');
    modal_obj_contents = $('#adm_modal_contents');
    modal_obj_overlay = $('#adm_overlay');
    
    var modal = {

        open : function(u,w,h){
            if(h===undefined){ h = 0; } 
            modal.size(w,h);
            modal_obj.draggable({ handle: '#adm_drag_handle' });
            modal.load(u);
            modal.show();
        },
        
        size : function(w,h){
            if(h==0){ h = 'auto'; }else{ h = h+'px'; }
            modal_obj.css({ 'top':(window.pageYOffset+100)+'px','left':'50%','width':w+'px','height':h,'margin':'0 0 0 -'+(Math.round(w/2))+'px' });
        },
        
        load : function(u){ modal_obj_contents.html('<div id="adm_loader"></div>'); $.get(u,function(data){
            modal_obj_contents.html(data);
        }); 
        },
        
        show : function(){ modal_obj.fadeIn(300); modal_obj_overlay.fadeIn(300); },
        
        hide : function(){ modal_obj.fadeOut(300); modal_obj_overlay.fadeOut(300); }
    
    };
    
    //////////////////////////////////////////////////////////////////
    // EDIT
    //////////////////////////////////////////////////////////////////
    
    var edit_mode = {
    
        activate : function(){
            
            localStorage.editmode = 'on';
            $('body').append('<div id="adm_highlight"></div>');
            highlight_obj = $('#adm_highlight'); 
            
            // Handle mouseover of blocks
            $('.block').each(function(){
                $(this).mouseover(function(){
                    var h = $(this).outerHeight();
                    var w = $(this).outerWidth();
                    var o = $(this).offset();
                    var l = o.left;
                    var t = o.top;
                    var id = $(this).attr('data-block-id');
                    $.data(highlight_obj, { 'edit':id, 'width':w });
                    highlight_obj
                        .css({ 'width':(w+10)+'px','height':(h+10)+'px','top':(t-7)+'px','left':(l-7)+'px' })
                        .show();
                });
                
                highlight_obj.mouseout(function(){ $(this).hide(); });
            });
            highlight_obj.click(function(){
                var id = $.data(highlight_obj, 'edit');
                var w = parseInt($.data(highlight_obj, 'width'))+40;
                // Minimum supported width
                if(w<310){ var w=310; }
                modal.open('system/admin/views/block_editor.php?id='+id, w);
            });
        },
        
        deactivate : function(){
            localStorage.editmode = 'off';
            $('#adm_highlight').remove(); 
        }
    
    };
    
    //////////////////////////////////////////////////////////////////
    // RESOURCES (Modal)
    //////////////////////////////////////////////////////////////////
    
    var resource = {
        
        load : function(u,t){
            var w = window.innerWidth - 200;
            var h = window.innerHeight - 250;
            var u = 'system/admin/views/resource.php?title='+t+'&ext_url='+u+'&height='+h;
            modal.open(u,w,h);
        }
        
    }
    
    //////////////////////////////////////////////////////////////////
    // DATATABLES
    //////////////////////////////////////////////////////////////////
    
    var datatable = {
        
        init : function(id){
            // Build sorting array
            //"aoColumns": [null,null,{ "bSortable": false },null]
            var aoColumns = [];
            $('#'+id+' thead th').each( function(){
                if($(this).hasClass('no-sort')){
                    aoColumns.push({ "bSortable": false });
                }else{
                    aoColumns.push(null);
                }
            });
            $('#'+id).dataTable({
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "aoColumns": aoColumns,
                "fnDrawCallback": function(){
                  $('table#'+id+' td').bind('mouseenter', function () { $(this).parent().children().each(function(){$(this).addClass('datatablerowhighlight');}); });
                  $('table#'+id+' td').bind('mouseleave', function () { $(this).parent().children().each(function(){$(this).removeClass('datatablerowhighlight');}); });
                }
            });
        }
        
    }
    
    //////////////////////////////////////////////////////////////////
    // URL
    //////////////////////////////////////////////////////////////////
    
    var url = {
        
        refresh : function(){ location.reload(true); },
        
        go : function(u){ location.href=u; }
    }
    
    //////////////////////////////////////////////////////////////////
    // ERROR
    //////////////////////////////////////////////////////////////////
    
    $('*').ajaxComplete(function(){ error_obj = $('#adm_error'); });
    
    var errormsg = {
        
        show : function(msg){
            errormsg.hide();
            error_obj.html(msg).fadeIn(500);
        },
        
        hide : function(){
            error_obj.fadeOut(300);
        }
        
    };