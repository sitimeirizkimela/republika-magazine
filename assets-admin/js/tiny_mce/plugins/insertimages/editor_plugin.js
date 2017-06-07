(function() {
	tinymce.create('tinymce.plugins.InsertImages', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceImages', function() {
				$.nmManual("/admin/image/form");	
			});

			// Register buttons
			ed.addButton('image', {title : 'Insert Images', cmd : 'mceImages'});
		},

		getInfo : function() {
			return {
				longname : 'Insert Images',
				author : 'Ainun Nazieb',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/emotions',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('insertimages', tinymce.plugins.InsertImages);
})();