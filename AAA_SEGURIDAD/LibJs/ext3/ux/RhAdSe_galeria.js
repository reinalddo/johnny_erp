(function(){
	Ext.ns('app.gen.galeria');
	app.gen.galeria = {
		showImages: function(config){
			var slPath = config.path? 'pPath=' + config.path : ''
			  var store = new Ext.data.JsonStore({
				  proxy: new Ext.data.HttpProxy({
					  url: 'LibPhp/extGallery-get-images.php?' + slPath,
					  method: 'POST'
				  }),
				  root: 'images',
				  fields: [
					  'name', 'url',
					  { name: 'size', type: 'float' },
					  { name: 'lastmod', type: 'date', dateFormat: 'timestamp' },
					  'thumb_url'
				  ]
			  });
			  store.load();

			  var tpl = new Ext.XTemplate(
				  '<tpl for=".">',
					  '<div class="thumb-wrap" id="{name}">',
					  '<div class="thumb"><img src="{thumb_url}" title="{name}"></div>',
					  '<span class="x-editable">{shortName}</span></div>',
				  '</tpl>',
				  '<div class="x-clear"></div>'
			  );

			  var tplDetail = new Ext.XTemplate(
				  '<div class="details">',
					  '<tpl for=".">',
						  '<img src="{thumb_url}"><div class="details-info">',
						  '<b>Archivo:</b>',
						  '<span>{name}</span>',
						  '<b>Tamaño:</b>',
						  '<span>{sizeString}</span>',
						  '<b>Ultima Modificación:</b>',
						  '<span>{dateString}</span>',
						  '<span><a href="{url}" target="_blank">ver el Original</a></span></div>',
					  '</tpl>',
				  '</div>'
			  );

			  var tbar = new Ext.Toolbar({
				  style: 'border:1px solid #99BBE8;'
			  });

			  tbar.add('->', {
				  text: 'Recargar',
				  icon: 'Images/famfam/arrow_refresh.png',
				  handler: function() {
					  store.load();
				  }
			  }, {
				  text: 'Eliminar',
				  icon: 'Images/delete.png',
				  handler: function() {
					  var records = datav.getSelectedRecords();
					  if (records.length != 0) {
						  var imgName = '';
						  for (var i = 0; i < records.length; i++) {
							  imgName = imgName + records[i].data.name + ';';
						  }
						  Ext.Ajax.request({
							 url: 'LibPhp/extGallery-delete.php?' + slPath,
							 method: 'post',
							 params: { images: imgName},
							 success: function() {
								 store.load();
							 }
						  });
					  }
				  }}
			);

			  var datav = new Ext.DataView({
				  autoScroll: 	true,
				  store: 		store,
				  tpl: 			tpl,
				  //autoHeight: 	true,
				  ref:			'../..',
				  height: 		400,
				  anchor:		'100% 90%',
				  multiSelect: 	config.multiselect || true,
				  overClass: 	'x-view-over',
				  itemSelector: 'div.thumb-wrap',
				  emptyText: 	'Sin archivos para presentar',
				  style: 		'border:1px solid #99BBE8; border-top-width: 0',

	  //            plugins: [
	  //                new Ext.DataView.DragSelector(),
	  //            ],
	  /**/
				  prepareData: function(data){
					  data.shortName = Ext.util.Format.ellipsis(data.name, 15);
					  data.sizeString = Ext.util.Format.fileSize(data.size);
					  data.dateString = data.lastmod.format("m/d/Y g:i a");
					  return data;
				  },

				  listeners: {
					  selectionchange: {
						  fn: function(dv,nodes){
							  var l = nodes.length;
							  var s = l != 1 ? 's' : '';
							  panelLeft.setTitle('Galeria de Imagenes('+l+' imagnes'+s+' seleccionadas)');
						  }
					  },

					  click: {
						  fn: function() {
							  var selNode = datav.getSelectedRecords();
							  this.prepareData(selNode[0].data)
							  tplDetail.overwrite(panelRightBottom.body, selNode[0].data);
						  }
					  }
				  }
			  })

			datav.on({
				'click' : {
					fn: config.onClick || Ext.emptyFn,
					scope: this
				},
				'selectionchange' : {
					fn: config.onSelect || Ext.emptyFn,
					scope: this
				},
				'dblclick' : {
					fn: config.onDblClick || Ext.emptyFn,
					scope: this
				}
				});

			  var panelLeft = new Ext.Panel({
				  id: 'images-view',
				  frame: true,
				  //anchor:	'100%',
				  width: 400,
				  height: 200,
				  autoHeight: true,
				  layout: 'auto',
				  title: 'Galeria de Imagenes(0 archivos seleccionados)',
				  items: [tbar,datav]
			  });
			  panelLeft.render('left');

			  var panelRightTop = new Ext.FormPanel({
				  title: 'Cargar Imagenes',
				  width: 300,
				  //anchor:	'35%',
				  renderTo: 'right-top',
				  buttonAlign: 'center',
				  labelWidth: 70,
				  fileUpload: true,
				  frame: true,
				  items: [{
					  xtype: 'fileuploadfield',
					  emptyText: '',
					  fieldLabel: 'Archivo 1',
					  buttonText: '...',
					  tootltip:		'Seleccionar un archivo a cargar',
					  width: 200,
					  name: 'img[]'
				  }, {
					  xtype: 'fileuploadfield',
					  emptyText: '',
					  fieldLabel: 'Archivo 2',
					  buttonText: '...',
					  tootltip:		'Seleccionar un archivo a cargar',
					  width: 200,
					  name: 'img[]'
				  }, {
					  xtype: 'fileuploadfield',
					  emptyText: '',
					  fieldLabel: 'Archivo 3',
					  buttonText: '...',
					  tootltip:		'Seleccionar un archivo a cargar',
					  width: 200,
					  name: 'img[]'
				  }, {
					  xtype: 'fileuploadfield',
					  emptyText: '',
					  fieldLabel: 'Archivo 4',
					  buttonText: '...',
					  tootltip:		'Seleccionar un archivo a cargar',
					  width: 200,
					  name: 'img[]'
				  }, {
					  xtype: 'fileuploadfield',
					  emptyText: '',
					  fieldLabel: 'Archivo 5',
					  buttonText: '...',
					  tootltip:		'Seleccionar un archivo a cargar',
					  width: 200,
					  name: 'img[]'
				  }],
				  buttons: [{
					  text: 'Upload',
					  handler: function() {
						  panelRightTop.getForm().submit({
							  url: 'LibPhp/extGallery-upload.php?' + slPath,
							  waitMsg: 'Cargando ....',
							  success: function(form, o) {
								  obj = Ext.util.JSON.decode(o.response.responseText);
								  if (obj.failed == '0' && obj.uploaded != '0') {
									  Ext.Msg.alert('Proceso Completado', 'Todos los archivos cargados');
								  } else if (obj.uploaded == '0') {
									  Ext.Msg.alert('Proceso Completado', 'No se cargo ningún archivo');
								  } else {
									  Ext.Msg.alert('Proceso Completado',
										  obj.uploaded + ' archivos cargados <br/>' +
										  obj.failed + ' archivos no cargados');
								  }
								  panelRightTop.getForm().reset();
								  store.load();
							  }
						  });
					  }
				  }, {
					  text: 'Reset',
					  handler: function() {
						  panelRightTop.getForm().reset();
					  }
				  }]
			  });

			  var panelRightBottom = new Ext.Panel({
				  title: 'Detalles del Archivo',
				  frame: true,
				  width: 270,
				  //anchor:	'35%',
				  height: 255,
				  id: 'panelDetail',
				  renderTo: 'right-bottom',
				  tpl: tplDetail
			});
	   }
		,open: function(config){
			app.gen.galeria.win = new Ext.Window({
				title:				'IMAGENES'
				,width:				730
				,height:			500
				,layout: 			'fit'
				,collapsible:		true
				,resizeable:		false
				//,closeAction: 		'hide'
				,html:
					'<div id="container" style:"width:100%; height:100%">' +
						'<div id="left" style:"height:100%"></div>' +
						'<div id="right">' +
							'<div id="right-top"></div>' +
							'<div id="right-bottom"></div>' +
						'</div>' +
						'<div class="clear"></div>' +
					'</div> '
				,onDestroy: 	function(){
					delete app.gen.galeria.win
				}

			});
			app.gen.galeria.win.show();
			app.gen.galeria.showImages(config);
		}
	}

})();
