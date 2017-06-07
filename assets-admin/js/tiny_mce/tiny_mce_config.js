tinyMCE.init({
	theme : 'advanced',
	mode : 'textareas',
	plugins: 'table,insertimages,paste',
	theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,separator,bullist,numlist,separator,undo,redo,separator,link,unlink,separator,justifyleft,justifycenter,justifyright,justifyfull,code,image,blockquote,quote,removeformat',
	theme_advanced_buttons2 : 'table,tablecontrols',
	theme_advanced_buttons3 : '',
	theme_advanced_toolbar_location : 'top',
	editor_deselector : "mceNoEditor",
	paste_text_sticky : true,
	verify_html: false,
	relative_urls : false,
	remove_script_host : false,
	convert_urls : false,
	setup : function(ed) {
	    ed.onInit.add(function(ed) {
	      ed.pasteAsPlainText = true;
	    });
  	},
	extended_valid_elements : "iframe[src|width|height|frameborder|id|style|scrolling|name|marginwidth|marginheight],script[src|type]" //request by gadir 28-02-2013
});
