( function() {
    CKEDITOR.plugins.add( 'insertmodule',
    {
        requires: [ 'iframedialog' ],
        init: function( editor )
        {
           var me = this;
           CKEDITOR.dialog.add( 'insertmodule', function ()
           {
              return {
                 title : 'Insert Module',
                 minWidth : 350,
                 minHeight : 250,
                 contents :
                       [
                          {
                             id : 'iframe',
                             label : 'Insert Module',
                             expand : true,
                             elements :
                                   [
                                      {
                                       type : 'html',
                                       id : 'pageinsertmodule',
                                       label : 'Embed Media',
                                       style : 'width : 100%;',
                                       html : '<iframe src="system/admin/editor/plugins/insertmodule/dialogs/module_selector.php" frameborder="0" name="iframeinsertmodule" id="iframeinsertmodule" allowtransparency="1" style="width:100%;margin:0;padding:0; height: 250px;"></iframe>'
                                      }
                                   ]
                          }
                       ],
                  onOk : function()
                 {
                      for (var i=0; i<window.frames.length; i++) {
                         if(window.frames[i].name == 'iframeinsertmodule') {
                            var module_content = window.frames[i].document.getElementById("embed").value;
                            var module_class = window.frames[i].document.getElementById("class").value;
                         }
                      }
                      final_html = escape('');
                      editor.insertHtml(final_html);
                      updated_editor_data = editor.getData();
                      clean_editor_data = updated_editor_data.replace(final_html,'<div class="module '+module_class+'">'+module_content+'</div><p>&nbsp;</p>');
                      editor.setData(clean_editor_data);
                 }
              };
           } );

            editor.addCommand( 'insertmodule', new CKEDITOR.dialogCommand( 'insertmodule' ) );

            editor.ui.addButton( 'insertmodule',
            {
                label: 'Insert Module',
                command: 'insertmodule',
                icon: this.path + 'images/icon.png'
            } );
        }
    } );
} )();