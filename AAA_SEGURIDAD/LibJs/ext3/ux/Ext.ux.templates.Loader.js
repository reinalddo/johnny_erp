/**
*	Ext.ux.templates.Loader
*
*/Ext.ns('Ext.ux.templates');

Ext.ux.templates.Loader = function(){
	var that = {};

	var map = {};

	that.getTemplate = function(url, callback) {
		if (map[url] === undefined) {
			Ext.Ajax.request({
				url: url,
				success: function(xhr){
					var template = new Ext.XTemplate(xhr.responseText);
					template.compile();
					map[url] = template;
					callback(template);
				}
			});
		} else {
			callback(map[url]);
		}
	};

	return that;
}();
