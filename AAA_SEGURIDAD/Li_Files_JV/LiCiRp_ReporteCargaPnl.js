Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"

Ext.onReady(function(){
    Ext.QuickTips.init();
    parametros = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 100
                        ,border:false
                        ,columnWidth:0.50
                        ,items: [{
                                    fieldLabel:'Semana'
                                    ,id:'pSemana'
                                    ,xtype: 'textfield'
                                    ,width:50
                                    ,allowBlank:false
                                    ,maxLength:4
                                    ,minLength:4
                                    
                                },{
                                    fieldLabel:'Cliente'
                                    ,id:'pCliente'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:false
                                    ,sqlId: 'LiCiRp_Cliente'
                                    ,width:200
                                },{
                                    fieldLabel:'Vapor'
                                    ,id:'pVapor'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:false
                                    ,sqlId: 'LiCiRp_Vapor'
                                    ,width:200
                                }]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 120
                        ,border:false
                        ,columnWidth:0.70
                        ,items: [/*{
                                    fieldLabel:'Cliente'
                                    ,id:'pCliente'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:false
                                    ,sqlId: 'LiLiRp_Productores'
                                    ,width:200
                                }*/]
                        }]
                }]
    });
    
    parametrosCalidad = ({
         xtype:'form'
	,title:'Parametros para el reporte de calidad'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 100
                        ,border:false
                        ,columnWidth:0.90
                        ,items: [{
                                    fieldLabel:'Embarque'
                                    ,id:'pEmbarque'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:false
                                    ,sqlId: 'LiCiRp_Embarques'
                                    ,width:500
				    ,listWidth:500
                                }]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 120
                        ,border:false
                        ,columnWidth:0.10
                        ,items: [/*{
                                    fieldLabel:'Cliente'
                                    ,id:'pCliente'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:false
                                    ,sqlId: 'LiLiRp_Productores'
                                    ,width:200
                                }*/]
                        }]
                }]
    });
    
    
    botones = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 100
                        ,border:false
                        ,items: [/*{
                                    text:'Reporte de Carga'
                                    ,id:'bt_RpCarga'
                                    ,xtype: 'button'
                                    ,handler:function(){
					if (fValidar() == true){;
					    pPar = fParametros();
					    url = "LiCiRp_ReporteCarga.rpt.php?"+pPar;
					    window.open(url)
					}
                                    }
                                },*/{
                                    text:'Reporte de Calidad'
                                    ,id:'bt_RpCalidad'
                                    ,xtype: 'button'
                                    ,handler:function(){
					if (fValidarCalidad() == true){;
					    pPar = fParametrosCalidad();
					    url = "LiCiRp_ReporteCalidad.rpt.php?"+pPar;
					    window.open(url)
					}
                                    }
                                }]
                        }]
                }]
    });
    
    var pnl_RepCli = new  Ext.Panel({
        //anchor: 100
        widht:700
	,title:'Parametros para el reporte'
	,id:'pnl_RepCli'
	,autoScroll : true
        ,items: [parametros,parametrosCalidad,botones]
    });
 
 
   addTab1({id:"TabRepCli",title: 'Reportes de Cliente',url:'',items:[pnl_RepCli]});
   Ext.getCmp("TabRepCli").doLayout();
 
 
    new Ext.Button({
        text: 'Cargar Imagen',
        handler: function(){
	    Panel = fMostrarGaleria()
	    addTab1({id:"TabGaleria",title: 'Galeria',url:'',closable:true,items:[Panel]});
	    Ext.getCmp("TabGaleria").doLayout();
	}
	,iconCls:'new-tab'
		}).render(document.body, 'divIzq01');
    
    new Ext.Button({
        text: 'Guardar Datos Tarjas',
        handler: function(){
	    PanelDatos = fMostrarDatos()
	    addTab1({id:"TabDatos",title: 'Datos',url:'',closable:true,items:[PanelDatos]});
	    Ext.getCmp("TabDatos").doLayout();
	}
	,iconCls:'new-tab'
		}).render(document.body, 'divIzq01');
     
});


function fValidar(){    
    if (!Ext.getCmp("pSemana").getValue() != "") {Ext.Msg.alert("Ingrese la Semana");return false;}
    if (!Ext.getCmp("pCliente").isValid()) {Ext.Msg.alert("Ingrese el Cliente"); return false;}
    if (!Ext.getCmp("pVapor").isValid()) {Ext.Msg.alert("Ingrese el Vapor"); return false;}
    
    return true;
}
function fValidarCalidad(){    
    if (!Ext.getCmp("pEmbarque").isValid()) {Ext.Msg.alert("Ingrese el  Embarque"); return false;}
    
    return true;
}

function fParametros(){
    
    pPar = "";
    if (Ext.getCmp("pSemana").getValue() != "") {pPar += "&pSemana="+Ext.getCmp("pSemana").getValue()} else {Ext.Msg.alert("Ingrese la Semana"); return;}
    if (Ext.getCmp("pCliente").isValid()) {pPar += "&pCliente="+Ext.getCmp("pCliente").getValue()} else {Ext.Msg.alert("Ingrese el Cliente"); return;}
    if (Ext.getCmp("pVapor").isValid()) {pPar += "&pVapor="+Ext.getCmp("pVapor").getValue()} else {Ext.Msg.alert("Ingrese el Vapor"); return;}
    
    return pPar;
}

function fParametrosCalidad(){
    
    pPar = "";
    if (Ext.getCmp("pEmbarque").isValid()) {pPar += "&pEmbarque="+Ext.getCmp("pEmbarque").getValue()} else {Ext.Msg.alert("Ingrese el Embarque"); return;}
    
    return pPar;
}

/**
 **	FUNCIONES PARA GUARDAR INFORMACION EXTRA DE LAS TARJAS
 **/
function fMostrarDatos(){
    
    FormParTarja = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 50
                        ,border:false
                        ,columnWidth:0.20
                        ,items: [{
							name: 		"tac_Semana"
							,dataIndex: 	"tac_Semana"
							,fieldLabel: 	"Semana"
							,xtype: 	"numberfield"
							,readOnly:	false
							,width:		'80%'
				}]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 90
                        ,border:false
                        ,columnWidth:0.30
			,items: [{
							name: 		"tac_Tarja"
							,dataIndex: 	"tac_Tarja"
							,fieldLabel: 	"Tarja"
							,xtype: 		"numberfield"
							,readOnly:		false
							,width:			'80%'
				}/*,{
						    fieldLabel:		'Embarque'
						    ,id:		'tac_Viaje'
						    ,xtype: 		'genCmbBox'
						    ,minChars: 		1
						    ,allowBlank:	false
						    ,sqlId: 		'LiCiRp_Embarques'
						    ,width:		'80%'
						    ,listWidth:		500
                                }*/]
                        }
			,{layout: 'form'
                        ,labelWidth: 60
                        ,border:false
                        ,columnWidth:0.30
			,items: [{
                                    text:'Buscar'
                                    ,id:'bt_Tarjas'
                                    ,xtype: 'button'
                                    ,handler:function(){
					// Funcion para Traer Tarjas
					Ext.getCmp('Grid_Tarjas').getStore().load();
                                    }
                                }
				]
                        }]
                }]
    });
    
    
    var storeTarjas = new Ext.data.JsonStore({
        id:"storeTarjas",
        url: '../Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'LiCiRp_TarjasConsulta',start:0,/* ID: ptra_Id,*/ limit:100, sort:'red_Sec', dir:'ASC'},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	//fields:fCamposReembolso(),
	sortInfo: {field:'red_Sec', direction: 'ASC'},
        pruneModifiedRecords: true
    });
    
    var TarjasColumnMode = new Ext.grid.ColumnModel(  
              [{  
                  header: 'Tarja'
                  ,dataIndex: 'red_MotivoCC'
                  ,width: 80
              },{  
                  header: 'Marca'
                  ,dataIndex: 'red_Aux'
                  ,width: 100
              },{ 
                  header: 'Empaque'
                  ,dataIndex: 'red_Valor'
                  ,width: 100
                  
              }]  
          );
    
    var Grid_Tarjas =  new Ext.grid.GridPanel({
               id: 'Grid_Tarjas'
               ,lazyRender: true
               ,store: storeTarjas
	       ,stripeRows :true
               ,collapsible: true
               ,cm: TarjasColumnMode
	       ,clicksToEdit: 1
	       ,height:300
	       ,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
	       ,title:"Tarjas"
	       ,buttonBar:true
	       ,listeners: {
                    destroy: function(c) {
                        c.getStore().destroy();
                    }
        }
    });
    
    	
    FormDatos = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 100
                        ,border:false
                        ,columnWidth:0.30
                        ,items: [{
							name: 		"tac_PromCalibracion"
							,dataIndex: 	"tac_PromCalibracion"
							,fieldLabel: 	"Promedio Calibracion"
							,xtype: 		"numberfield"
							,readOnly:		false
							,tooltip:		'Para cambiar la imagen, <br>presione el boton de la derecha'
							,width:			'80%'
				},{
							name: 		"tac_PromDedos"
							,dataIndex: 	"tac_PromDedos"
							,fieldLabel: 	"Promedio Largo de Dedo"
							,xtype: 		"numberfield"
							,readOnly:		false
							,tooltip:		'Para cambiar la imagen, <br>presione el boton de la derecha'
							,width:			'80%'
				},{
							name: 		"tac_PromPeso"
							,dataIndex: 	"tac_PromPeso"
							,fieldLabel: 	"Promedio Peso por Caja"
							,xtype: 		"numberfield"
							,readOnly:		false
							,tooltip:		'Para cambiar la imagen, <br>presione el boton de la derecha'
							,width:			'80%'
				}
				]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 120
                        ,border:false
                        ,columnWidth:0.30
			,items: []
                        }]
                }]
    });
    
    var pnl_Galeria = new  Ext.Panel({
        //anchor: 100
        widht:700
	,height:200
	,title:'Cargar Imagenes'
	,id:'pnl_RepCli'
	,autoScroll : true
        ,items: [FormParTarja,Grid_Tarjas,FormDatos]
    });
    
    return pnl_Galeria;

}


/**
 **	FUNCIONES PARA CARGAR IMAGENES AL SERVIDOR
 **/
function fMostrarGaleria(){
    var goConfigGaleria = {path:'../Li_Files/imagenes'
				//,onSelect: function(){alert('seleeeee')}
				//,onDblClick: this.onSelectImg.createDelegate(this)		// Ejecucion en ambito del fromulario
			}
			
    FormGaleria = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 100
                        ,border:false
                        ,columnWidth:0.70
                        ,items: [{
                                     fieldLabel:'Embarque'
                                    ,id:'tdc_CodEmbarque'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:false
                                    ,sqlId: 'LiCiRp_Embarques'
                                    ,width:500
				    ,listWidth:500
                                 },
				 {
							name: 			"prg_Imagen"
							,dataIndex: 	"prg_Imagen"
							,fieldLabel: 	"IMAGEN"
							,xtype: 		"textfield"
							,readOnly:		true
							,tooltip:		'Para cambiar la imagen, <br>presione el boton de la derecha'
							//,anchor:		'80%'
							,width:			'80%'
							//,render:		function(pVal){
							//	//slText = (pVal.length >1 ? pVal : pVal.substring(pVal.length-20, pVal.length))
							//	return (pVal.length >1 ? pVal : pVal.substring(pVal.length-20, pVal.length ))
							//}
				}
				]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 120
                        ,border:false
                        ,columnWidth:0.30
			,items: [{
					name: 		"espacio"
					,dataIndex: 	"espacio"
					,xtype: 	"textfield"
					,anchor:	'1%'
					,hidden:	true
				},{xtype:'button'
					,text:			'...'
					//,anchor:		'15%'
					,width:			'10%'
					,tooltip:		'Cargar Imagen'
					,handler:		function(){
						if (app.gen.galeria ){
							if (!app.gen.galeria.win){
								app.gen.galeria.open(goConfigGaleria)
							} else {
								app.gen.galeria.win.expand();
								app.gen.galeria.win.setVisible(true);
							}
						}
						else {
							loadScript('css/extGalery.css');
							loadScript('LibJs/ext3/ux/extGalery/data-view.css')
							loadScript('LibJs/ext3/ux/extGalery/fileuploadfield.css')
							loadScript('LibJs/ext3/ux/Ext.ux.app.galeria.js?', function(){
								loadScript('LibJs/ext3/ux/extGalery/FileUploadField.js', function(){
									loadScript('LibJs/ext3/ux/extGalery/DataView-more.js', function(){
										app.gen.galeria.open(goConfigGaleria)
									}.createDelegate(this))		// Ejecucion en ambito del fromulario
							 }.createDelegate(this))
							}.createDelegate(this)
							)
						}
					}//.createDelegate(this)						// Ejecucion en ambito del fromulario
				}]
                        }]
                }]
    });
    
    var pnl_Galeria = new  Ext.Panel({
        //anchor: 100
        widht:700
	,height:200
	,title:'Cargar Imagenes'
	,id:'pnl_RepCli'
	,autoScroll : true
        ,items: [FormGaleria]
    });
    
    return pnl_Galeria;

}


/**
 **	OTRAS FUNCIONES
 **/
function addTab1(pPar){ //Add Tab sin la configuracion del autoload
      tabs_c.add({
      id: pPar.id
      ,title: pPar.title
      ,layout:'fit'
      ,closable: false
      ,collapsible: true
      ,items: pPar.items
    }).show();
  }
  
function addTab(pPar){
      //debugger;
      tabs_c.add({
      id: pPar.id,
      title: pPar.title,
      layout:'fit',
      closable: true,
      collapsible: true,
      autoLoad:{url:pPar.url,
            params:{pObj: pPar.id},
            scripts: true,
            method: 'post'}
    }).show();
  }