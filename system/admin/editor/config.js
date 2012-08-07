/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
    config.toolbar = 'Default';
    config.contentsCss = ['css/screen.css', 'system/admin/editor/wysiwyg_overrides.css'];
    config.forcePasteAsPlainText = true;
    config.height = '500px';
    config.tabSpaces = 5;
    config.toolbarCanCollapse = false;
    config.format_tags = 'p;h1;h2;h3;h4;pre';
    config.dialog_backgroundCoverColor = 'rgb(0, 0, 0)';
    config.extraPlugins = 'insertmodule';
    config.removePlugins = 'elementspath';
    config.resize_enabled = false;

    config.toolbar_Default =
    [
        ['Format','-','Bold','Italic','Underline','Strike','-','NumberedList','BulletedList','-','Outdent','Indent','-','insertmodule','-','Link','Unlink','-','Image','SpecialChar','-','Source']
    ];
    
    /*  ALL TOOLBAR BUTTONS #############################################################
    
    'Source','Save','NewPage','Preview','Templates','Cut','Copy','Paste','PasteText','PasteFromWord','Print', 'SpellChecker', 'Scayt',
    'Undo','Redo','-','Find','Replace','SelectAll','RemoveFormat',
    'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button','ImageButton', 'HiddenField',
    'Bold','Italic','Underline','Strike','Subscript','Superscript','NumberedList','BulletedList','Outdent','Indent','Blockquote',
    'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock',
    'Link','Unlink','Anchor','Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak',
    'Styles','Format','Font','FontSize','TextColor','BGColor','Maximize', 'ShowBlocks','About'    
     
     ####################################################################################
     
     */

};
