﻿/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function(){var a=function(f,g){var h=f.document,i=h.getBody(),j=false,k=function(){j=true;};i.on(g,k);h.$.execCommand(g);i.removeListener(g,k);return j;},b=CKEDITOR.env.ie?function(f,g){return a(f,g);}:function(f,g){try{return f.document.$.execCommand(g);}catch(h){return false;}},c=function(f){this.type=f;this.canUndo=this.type=='cut';};c.prototype={exec:function(f,g){var h=b(f,this.type);if(!h)alert(f.lang.embed[this.type+'Error']);return h;}};var d=CKEDITOR.env.ie?{exec:function(f,g){f.focus();if(!f.fire('beforePaste')&&!a(f,'paste'))f.openDialog('embed');}}:{exec:function(f){try{if(!f.fire('beforeembed')&&!f.document.$.execCommand('embed',false,null))throw 0;}catch(g){f.openDialog('embed');}}},e=function(f){switch(f.data.keyCode){case CKEDITOR.CTRL+86:case CKEDITOR.SHIFT+45:var g=this;g.fire('saveSnapshot');if(g.fire('beforeembed'))f.cancel();setTimeout(function(){g.fire('saveSnapshot');},0);return;case CKEDITOR.CTRL+88:case CKEDITOR.SHIFT+46:g=this;g.fire('saveSnapshot');setTimeout(function(){g.fire('saveSnapshot');},0);}};CKEDITOR.plugins.add('embed',{init:function(f){function g(i,j,k,l){var m=f.lang[j];f.addCommand(j,k);f.ui.addButton(i,{label:m,command:j});if(f.addMenuItems)f.addMenuItem(j,{label:m,command:j,group:'embed',order:l});};g('Cut','cut',new c('cut'),1);g('Copy','copy',new c('copy'),4);g('embed','embed',d,8);CKEDITOR.dialog.add('embed',CKEDITOR.getUrl(this.path+'dialogs/embed.js'));f.on('key',e,f);if(f.contextMenu){function h(i){return f.document.$.queryCommandEnabled(i)?CKEDITOR.TRISTATE_OFF:CKEDITOR.TRISTATE_DISABLED;};f.contextMenu.addListener(function(){return{cut:h('Cut'),copy:h('Cut'),embed:CKEDITOR.env.webkit?CKEDITOR.TRISTATE_OFF:h('embed')};});}}});})();