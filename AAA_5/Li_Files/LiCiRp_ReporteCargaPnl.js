Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"
var iltr_CodCliente = 0; // valor predeterminado del codigo del cliente, para el reporte que se ejecuta por default.
Ext.namespace("app", "app.Calidad");
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
					    url = "LiCiRp_ReporteCalidad2.rpt.php?"+pPar;
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
 
 
    /*new Ext.Button({
        text: 'Cargar Imagen',
        handler: function(){
	    Panel = fMostrarGaleria()
	    addTab1({id:"TabGaleria",title: 'Galeria',url:'',closable:true,items:[Panel]});
	    Ext.getCmp("TabGaleria").doLayout();
	}
	,iconCls:'new-tab'
		}).render(document.body, 'divIzq01');
    */
    /*new Ext.Button({
        text: 'Guardar Datos Tarjas',
        handler: function(){
	    PanelDatos = fMostrarDatos()
	    addTab1({id:"TabDatos",title: 'Datos',url:'',closable:true,items:[PanelDatos]});
	    Ext.getCmp("TabDatos").doLayout();
	}
	,iconCls:'new-tab'
		}).render(document.body, 'divIzq01');*/
    
    
    function fCargaPermisos($key){
	var olDat = Ext.Ajax.request({
	    url: '../Bi_Files/BiTrTr_variablesglobales?&pmodulo=LiCiRp'
	    ,callback: function(pOpt, pStat, pResp){
		//alert("consulta");
		if (true == pStat){
		    var olRsp = eval("(" + pResp.responseText + ")")
		    switch ($key){
			case 'ITC':app.Calidad.InsTextos = olRsp.valor;//Insertar textos del reporte de calidad
			    break; 
		    }
		    
		    
		    if (app.Calidad.InsTextos == 1){
	
			new Ext.Button({
			text: 'Textos del Reporte',
			id:'btMostrarTexto',
			handler: function(){
			    if(app.Calidad.InsTextos == 1){
				PanelTextos = fMostrarTextos()
				addTab1({id:"TabTextos",title: 'Datos',url:'',closable:true,items:[PanelTextos]});
				Ext.getCmp("TabTextos").doLayout();
				Ext.getCmp("btMostrarTexto").setDisabled(true);
			    }
			    else{
				Ext.Msg.alert("No tiene permisos para ver la opcion");
			    }
			}
			,iconCls:'new-tab'
				}).render(document.body, 'divIzq01');
		
		    }
		    
		    
		}
	    }
	    ,params: {pKey: $key, pBool: 0}
	})
    }
    
    
    //Cargar permisos
    fCargaPermisos('ITC');//Ingresar Textos del Reporte
    
    
    
   
    
    
     
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
    
    var FormParTarja = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 50
                        ,border:false
                        ,columnWidth:0.20
                        ,items: [{
							name: 		"tac_Semana"
							,id: 	"tac_Semana"
							,fieldLabel: 	"Semana"
							,xtype: 	"numberfield"
							,readOnly:	false
							,width:		'80%'
							,maxLength:4
							,minLength:4
				}]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 90
                        ,border:false
                        ,columnWidth:0.30
			,items: [{
							name: 		"tac_Tarja"
							,id: 	"tac_Tarja"
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
					//parametros del grid
					
					var ptac_Semana = Ext.getCmp('tac_Semana').getValue();
					var ptar_NumTarja = Ext.getCmp('tac_Tarja').getValue();
					
					if (ptac_Semana == '') ptac_Semana = 0;
					if (ptar_NumTarja == '') ptar_NumTarja = 0;
					
					// Cargar Grid
					storeGridTarjas =  Ext.getCmp("Grid_Tarjas").getStore();
					storeGridTarjas.removeAll();
					storeGridTarjas.baseParams.ptac_Semana=ptac_Semana;
					storeGridTarjas.baseParams.ptar_NumTarja=ptar_NumTarja;
					storeGridTarjas.load();
                                    }
                                }
				]
                        }]
                }]
    });
    
    function fDatos(){
    return [
	    {name: 'tar_NumTarja', type: 'int'}
            ,{name: 'tac_Semana', type: 'string'}
            ,{name: 'tac_Embarcador', type: 'int'}
	    ,{name: 'Embarcador', type: 'string'}
	    ,{name: 'tac_PromCalibracion', type: 'float'}
	    ,{name: 'tac_PromDedos', type: 'float'}
	    ,{name: 'tac_PromPeso', type: 'float'}
	    ]
    };
    
    var storeTarjas = new Ext.data.JsonStore({
        id:"storeTarjas",
        url: '../Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'LiCiRp_TarjasConsulta',start:0,/* ID: ptra_Id,*/ limit:100, sort:'tar_NumTarja', dir:'ASC'},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	fields:fDatos(),
	sortInfo: {field:'tar_NumTarja', direction: 'ASC'},
        pruneModifiedRecords: true
    });
    
    var TarjasColumnMode = new Ext.grid.ColumnModel(  
              [{  
                  header: 'Tarja'
                  ,dataIndex: 'tar_NumTarja'
                  ,width: 80
              },{  
                  header: 'Semana'
                  ,dataIndex: 'tac_Semana'
                  ,width: 100
              },{ 
                  header: 'Embarcador'
                  ,dataIndex: 'Embarcador'
                  ,width: 100
                  
              },{  
                  header: 'Prom. Calibracion'
                  ,dataIndex: 'tac_PromCalibracion'
                  ,width: 80
              },{  
                  header: 'Prom. Largo Dedo'
                  ,dataIndex: 'tac_PromDedos'
                  ,width: 100
              },{ 
                  header: 'Prom. Peso Caja'
                  ,dataIndex: 'tac_PromPeso'
                  ,width: 100
                  
              }]  
          );
    
    var Grid_Tarjas =  new Ext.grid.EditorGridPanel({
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
	       ,listeners: {
                    destroy: function(c) {
                        c.getStore().destroy();
                    }
        }
    });
    
    
    Grid_Tarjas.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {
	
	/*var oParam ={} ;
	oParam.tar_NumTarja = pRec.data.tar_NumTarja;
	oParam.tac_Semana = pRec.data.tac_Semana;
	
	oParam.tac_PromCalibracion = pRec.data.tac_PromCalibracion;
	oParam.tac_PromDedos = pRec.data.tac_PromDedos;
	oParam.tac_PromPeso = pRec.data.tac_PromPeso;*/
	
	Ext.getCmp('ptac_PromCalibracion').setValue(pRec.data.tac_PromCalibracion);
	Ext.getCmp('ptac_PromDedos').setValue(pRec.data.tac_PromDedos);
	Ext.getCmp('ptac_PromPeso').setValue(pRec.data.tac_PromPeso);
	Ext.getCmp('ptar_NumTarja').setValue(pRec.data.tar_NumTarja);
	Ext.getCmp('ptac_Semana').setValue(pRec.data.tac_Semana);
	
	
	
	

    })
    
    
    
    FormDatos = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 200
                        ,border:false
                        ,columnWidth:0.30
                        ,items: [{
						name: 		"tac_PromCalibracion"
						,id: 		"ptac_PromCalibracion"
						,fieldLabel: 	"Promedio Calibracion"
						,xtype: 	"numberfield"
						,readOnly:	false
						,width:		'80%'
				},{
						name: 		"tac_PromDedos"
						,id: 		"ptac_PromDedos"
						,fieldLabel: 	"Promedio Largo de Dedo"
						,xtype: 	"numberfield"
						,readOnly:	false
						,width:		'80%'
				},{
						name: 		"tac_PromPeso"
						,id: 		"ptac_PromPeso"
						,fieldLabel: 	"Promedio Peso por Caja"
						,xtype: 	"numberfield"
						,readOnly:	false
						,width:		'80%'
				},{
                                    text:	'Guardar Cambios'
                                    ,id:	'bt_Guardar'
                                    ,xtype: 	'button'
                                    ,handler:	function(){
					if (Ext.getCmp('ptar_NumTarja').getValue() > 0) {
					    
						var tac_PromCalibracion;
						var tac_PromDedos;
						var tac_PromPeso;
					    
						if (Ext.getCmp('ptac_PromCalibracion').getValue() == ""){tac_PromCalibracion=0; } else{tac_PromCalibracion = Ext.getCmp('ptac_PromCalibracion').getValue();}
						if (Ext.getCmp('ptac_PromDedos').getValue()	  == ""){tac_PromDedos=0; } 	  else{tac_PromDedos = Ext.getCmp('ptac_PromDedos').getValue();}
						if (Ext.getCmp('ptac_PromPeso').getValue()	  == ""){tac_PromPeso=0; } 	  else{tac_PromPeso = Ext.getCmp('ptac_PromPeso').getValue();}
					    
					    
						var oParam ={} ;
						oParam.ptar_NumTarja=		Ext.getCmp('ptar_NumTarja').getValue();
						oParam.ptac_Semana=		Ext.getCmp('ptac_Semana').getValue();
						
						oParam.ptac_PromCalibracion=	tac_PromCalibracion;
						oParam.ptac_PromDedos= 		tac_PromDedos;
						oParam.ptac_PromPeso= 		tac_PromPeso;
						
						oParam.tipo= 			'EXT';//DATOS EXTRAS DE LAS TARJAS - REPORTE DE CALIDAD 
						
						fActualizaTarja(oParam);
					}else{
						Ext.Msg.alert('ADVERTENCIA ', 'Seleccione la tarja a modificar.');
					}
                                    }
                                }
				]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 120
                        ,border:false
                        ,columnWidth:0.70
			,items: [{
				    name: 		"ptar_NumTarja"
				    ,id: 		"ptar_NumTarja"
				    ,fieldLabel: 	"Tarja"
				    ,xtype: 		"numberfield"
				    ,readOnly:		true
				    ,width:		'80%'
				    ,visible:		false
				},{
				    name: 		"ptac_Semana"
				    ,id: 		"ptac_Semana"
				    ,fieldLabel: 	"Semana"
				    ,xtype: 		"numberfield"
				    ,readOnly:		true
				    ,width:		'80%'
				    ,visible:		false
				}]
                        }]
                }]
    });
    
    var pnl_Datos = new Ext.Panel({
        //anchor: 100
        title:'DATOS EXTRAS DE LA TARJAS'
	,id:'pnl_Datos'
	,autoScroll : true
	,collapsible:true
        ,items: [FormDatos]
    });
    
    var pnl_Galeria = new  Ext.Panel({
        //anchor: 100
        widht:700
	,height:800
	,title:'DATOS DE TARJAS'
	,id:'pnl_Galeria'
	,autoScroll : true
        ,items: [FormParTarja,Grid_Tarjas,pnl_Datos]
    });
    
function fActualizaTarja(oParams){
  Ext.Ajax.request({
    waitMsg: 'GRABANDO...',
    url:	'../Li_Files/LiCiRp_ReporteCargaPnlGraba.php',
    method: 'POST',
    params: oParams,
    success: function(response,options){
      var responseData = Ext.util.JSON.decode(response.responseText);
      if (false == responseData.success){
	    slMens = responseData.message;
      }
      else{  
	    slMens = responseData.message;
	    // Volver a consultar el grid de tarjas:
	    // Cargar Grid
	    var ptac_Semana = 	Ext.getCmp('tac_Semana').getValue();
	    var ptar_NumTarja = Ext.getCmp('tac_Tarja').getValue();
	    
	    if (ptac_Semana == '') ptac_Semana = 0;
	    if (ptar_NumTarja == '') ptar_NumTarja = 0;
	    storeGridTarjas =  Ext.getCmp("Grid_Tarjas").getStore();
	    storeGridTarjas.removeAll();
	    storeGridTarjas.baseParams.ptac_Semana=ptac_Semana;
	    storeGridTarjas.baseParams.ptar_NumTarja=ptar_NumTarja;
	    storeGridTarjas.load();
	    
      }  
      Ext.Msg.alert('AVISO ', slMens);
    }, 
    failure: function(form, e) {
      if (e.failureType == 'server') {
        slMens = 'La operacion no se ejecuto. ' + e.result.errors.id + ': ' + e.result.errors.msg;
      } else {
        slMens = 'El comando no se pudo ejecutar en el servidor';
      }
      Ext.Msg.alert('Proceso Fallido', slMens);
    }
    }); 
}; 
    
    
    
    return pnl_Galeria;
}




/**
 **	FUNCIONES PARA GUARDAR LOS TEXTOS DEL REPORTE DE CALIDAD
 **/
function fMostrarTextos(){
    
    var FormParTextos = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 50
                        ,border:false
                        ,columnWidth:0.40
                        ,items: [{
                                    fieldLabel:'Semana Desde'
                                    ,id:'cb_semanaDesde'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:true
                                    ,sqlId: 'LiCiRp_TxtSemanaDesde'
                                    ,width:200
                                }]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 90
                        ,border:false
                        ,columnWidth:0.10
			,items: [{
                                    fieldLabel:''
                                    ,id:'cb_CodCliente'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:false
                                    ,sqlId: 'LiCiRp_TxtCodigoCliente'
                                    ,width:200
				    ,hidden:true
                                }]
                        }
			,{layout: 'form'
                        ,labelWidth: 60
                        ,border:false
                        ,columnWidth:0.30
			,items: [{
                                    text:'Buscar'
                                    ,id:'bt_Textos'
                                    ,xtype: 'button'
                                    ,handler:function(){
					//parametros del grid
					
					var pltr_semanaDesde = Ext.getCmp('cb_semanaDesde').getValue();
					var pltr_CodCliente = Ext.getCmp('cb_CodCliente').getValue();
					pltr_CodCliente = iltr_CodCliente; // por el momento solo se va a traer los textos predeterminados con codigo de cliente 0
					
					//if (pltr_semanaDesde == ''){Ext.Msg.alert("Seleccione una semana ")}
					//else{
					    
					    if (pltr_semanaDesde == ''){pltr_semanaDesde = 'ltr_semanaDesde'}
					    
					    // Cargar Grid
					    storeGridTextos =  Ext.getCmp("Grid_Textos").getStore();
					    storeGridTextos.removeAll();
					    storeGridTextos.baseParams.pltr_semanaDesde=pltr_semanaDesde;
					    storeGridTextos.baseParams.pltr_CodCliente=pltr_CodCliente;
					    storeGridTextos.load();
					//}
					
                                    }
                                }
				]
                        }]
                }]
    });
    
    function fRegistro(){
    return [
	    {name: 'ltr_registro', type: 'int'}
	    ,{name: 'ltr_tipo', type: 'string'}
            ,{name: 'ltr_semanaDesde', type: 'int'}
	    ,{name: 'ltr_CodCliente', type: 'int'}
	    ,{name: 'ltr_txt1Titulo', type: 'string'}
	    ,{name: 'ltr_txt1Desc', type: 'string'}
	    ,{name: 'ltr_txt2Titulo', type: 'string'}
	    ,{name: 'ltr_txt2Desc', type: 'string'}
	    ,{name: 'ltr_txt3Titulo', type: 'int'}
	    ,{name: 'ltr_txt3Desc', type: 'int'}
	    ,{name: 'ltr_visible', type: 'int'}
	    ,{name: 'txVisible', type: 'string'}
	    ]
    };
    
    var storeTextos = new Ext.data.JsonStore({
        id:"storeTextos",
        url: '../Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'LiCiRp_Textos',start:0,/* ID: ptra_Id,*/ limit:100, sort:'ltr_semanaDesde', dir:'ASC'},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	fields:fRegistro(),
	sortInfo: {field:'ltr_semanaDesde', direction: 'ASC'},
        pruneModifiedRecords: true
    });
    
    var TextosColumnMode = new Ext.grid.ColumnModel(  
              [{  
                  header: 'Semana Desde'
                  ,dataIndex: 'ltr_semanaDesde'
                  ,width: 100
              },{  
                  header: 'Titulo Texto 1'
                  ,dataIndex: 'ltr_txt1Titulo'
                  ,width: 60
              },{ 
                  header: 'Texto 1'
                  ,dataIndex: 'ltr_txt1Desc'
                  ,width: 200
                  
              },{  
                  header: 'Titulo Texto 2'
                  ,dataIndex: 'ltr_txt2Titulo'
                  ,width: 150
              },{  
                  header: 'Texto 2'
                  ,dataIndex: 'ltr_txt2Desc'
                  ,width: 150
              },{  
                  header: 'Titulo Texto 3'
                  ,dataIndex: 'ltr_txt3Titulo'
                  ,width: 150
              },{  
                  header: 'Texto 3'
                  ,dataIndex: 'ltr_txt3Desc'
                  ,width: 150
              },{ 
                  header: 'Visible'
                  ,dataIndex: 'txVisible'
                  ,width: 60
                  
              }]  
          );
    
    var Grid_Textos =  new Ext.grid.EditorGridPanel({
               id: 'Grid_Textos'
               ,lazyRender: true
               ,store: storeTextos
	       ,stripeRows :true
               ,collapsible: true
               ,cm: TextosColumnMode
	       ,clicksToEdit: 1
	       ,height:150
	       ,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
	       ,title:"Textos del Reporte"
	       ,listeners: {
                    destroy: function(c) {
                        c.getStore().destroy();
                    }
        }
    });
    
    
    Grid_Textos.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {
	
	Ext.getCmp('ltr_registro').setValue(pRec.data.ltr_registro);
	Ext.getCmp('ltr_semanaDesde').setValue(pRec.data.ltr_semanaDesde);
	Ext.getCmp('ltr_txt1Titulo').setValue(pRec.data.ltr_txt1Titulo);
	Ext.getCmp('ltr_txt1Desc').setValue(pRec.data.ltr_txt1Desc);
	Ext.getCmp('ltr_txt2Titulo').setValue(pRec.data.ltr_txt2Titulo);
	Ext.getCmp('ltr_txt2Desc').setValue(pRec.data.ltr_txt2Desc);
	Ext.getCmp('ltr_txt3Titulo').setValue(pRec.data.ltr_txt3Titulo);
	Ext.getCmp('ltr_txt3Desc').setValue(pRec.data.ltr_txt3Desc);
	Ext.getCmp('ltr_visible').setValue(pRec.data.ltr_visible);
	fConsulta();
    })


/*
    function fRegistroInsertar(){
    return [
	    {name: 'ltr_registro', type: 'int'}
	    ,{name: 'ltr_tipo', type: 'string'}
            ,{name: 'ltr_semanaDesde', type: 'int'}
	    ,{name: 'ltr_CodCliente', type: 'int'}
	    ,{name: 'ltr_txt1Titulo', type: 'string'}
	    ,{name: 'ltr_txt1Desc', type: 'string'}
	    ,{name: 'ltr_txt2Titulo', type: 'string'}
	    ,{name: 'ltr_txt2Desc', type: 'string'}
	    ,{name: 'ltr_txt3Titulo', type: 'int'}
	    ,{name: 'ltr_txt3Desc', type: 'int'}
	    ,{name: 'ltr_visible', type: 'int'}
	    ,{name: 'txVisible', type: 'string'}
	    ]
    };
    
    var storeTextosInsertar = new Ext.data.JsonStore({
        id:"storeTextos",
        url: '../Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'LiCiRp_TextosInsertar',start:0, limit:100, sort:'ltr_semanaDesde', dir:'ASC'},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	fields:fRegistroInsertar(),
	sortInfo: {field:'ltr_semanaDesde', direction: 'ASC'},
        pruneModifiedRecords: true
    });

*/

// Store para los datos del formulario
    var olRecIns = Ext.data.Record.create([  // Estructura del registro
	     {name: 'ltr_registro', type: 'int'}
	    ,{name: 'ltr_tipo', type: 'string'}
            ,{name: 'ltr_semanaDesde', type: 'int'}
	    ,{name: 'ltr_CodCliente', type: 'int'}
	    ,{name: 'ltr_txt1Titulo', type: 'string'}
	    ,{name: 'ltr_txt1Desc', type: 'string'}
	    ,{name: 'ltr_txt2Titulo', type: 'string'}
	    ,{name: 'ltr_txt2Desc', type: 'string'}
	    ,{name: 'ltr_txt3Titulo', type: 'int'}
	    ,{name: 'ltr_txt3Desc', type: 'int'}
	    ,{name: 'ltr_visible', type: 'int'}
	    ,{name: 'txVisible', type: 'string'}
      ]);
    rdFormIns = new Ext.data.JsonReader({root : 'data',  successProperty: 'success',totalProperty: 'totalRecords',  id:'ltr_semanaDesde'}, olRecIns);


    FormularioTextos = ({
	
         xtype:'form'
	,id: 'FormularioTextos'
	//,store: storeTextosInsertar
	,reader:rdFormIns
	,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 200
                        ,border:false
                        ,columnWidth:0.30
                        ,items: [{
						name: 		"ltr_registro"
						,id: 		"ltr_registro"
						,fieldLabel: 	"Registro"
						,xtype: 	"numberfield"
						,readOnly:	true
						,disabled:	true
						//,allowBlank:	false
						,value:		0	
						,width:		'80%'
				},{
						name: 		"ltr_semanaDesde"
						,id: 		"ltr_semanaDesde"
						,fieldLabel: 	"SemanaDesde"
						,xtype: 	"numberfield"
						,readOnly:	false
						,allowBlank:	false
						,width:		'80%'
						,maxLength:4
						,minLength:4
				},{
						name: 		"ltr_txt1Titulo"
						,id: 		"ltr_txt1Titulo"
						,fieldLabel: 	"Titulo de Texto 1"
						,xtype: 	"numberfield"
						,readOnly:	false
						,width:		'80%'
				},{
						name: 		"ltr_txt2Titulo"
						,id: 		"ltr_txt2Titulo"
						,fieldLabel: 	"Titulo de Texto 2"
						,xtype: 	"textfield"
						,readOnly:	false
						,width:		'80%'
				},{
						name: 		"ltr_txt3Titulo"
						,id: 		"ltr_txt3Titulo"
						,fieldLabel: 	"Titulo de Texto 3"
						,xtype: 	"textfield"
						,readOnly:	false
						,width:		'80%'
				},{
                                    text:	'Guardar Cambios'
                                    ,id:	'bt_GuardarTextos'
                                    ,xtype: 	'button'
                                    ,handler:	function(){
					// Funcion para validar los datos
					if ((Ext.getCmp('ltr_semanaDesde').getValue() > 0) /*&& (Ext.getCmp('ltr_CodCliente').getValue() >= 0)*/ ) {
					    
					   if ( Ext.getCmp('ltr_registro').getValue() > 0){
						// Registro Existente - Actualizar estado visible
					    
						var oParam ={} ;
						oParam.pltr_visible =		Ext.getCmp('ltr_visible').getValue();

						
						oParam.pltr_tipo=		'CALIDAD';
						oParam.pltr_semanaDesde= 	Ext.getCmp('ltr_semanaDesde').getValue();
						//oParam.pltr_CodCliente= 	Ext.getCmp('ltr_CodCliente').getValue();
						oParam.pltr_CodCliente= 	iltr_CodCliente;
						
						oParam.tipo= 			'TXT_ACT';//ACTUALIZAR ESTADO DEL TEXTO PARA EL REPORTE DE CALIDAD 
						
						fActualizaTextos(oParam);
					    }
					    else{
						
						if (Ext.getCmp('ltr_visible').getValue() == "" || Ext.getCmp('ltr_visible').getValue() == " "){
							Ext.Msg.alert('ADVERTENCIA ', 'Especifique si el registro va a ser visible o no');
							
						}else{
							// Registro Nuevo - Actualizar estado visible
							var oParam ={} ;
							
							oParam.pltr_tipo=		'CALIDAD';
							oParam.pltr_semanaDesde= 	Ext.getCmp('ltr_semanaDesde').getValue();
							//oParam.pltr_CodCliente= 	Ext.getCmp('ltr_CodCliente').getValue();
							oParam.pltr_CodCliente= 	iltr_CodCliente;
							
							oParam.pltr_txt1Titulo =	Ext.getCmp('ltr_txt1Titulo').getValue();
							oParam.pltr_txt1Desc =		Ext.getCmp('ltr_txt1Desc').getValue();
							oParam.pltr_txt2Titulo =	Ext.getCmp('ltr_txt2Titulo').getValue();
							oParam.pltr_txt2Desc =		Ext.getCmp('ltr_txt2Desc').getValue();
							oParam.pltr_txt3Titulo =	Ext.getCmp('ltr_txt3Titulo').getValue();
							oParam.pltr_txt3Desc =		Ext.getCmp('ltr_txt3Desc').getValue();
							oParam.pltr_visible =		Ext.getCmp('ltr_visible').getValue();
							oParam.tipo= 			'TXT_ING';//ACTUALIZAR ESTADO DEL TEXTO PARA EL REPORTE DE CALIDAD 
							
							fActualizaTextos(oParam);
							fNuevo();
					}
					    }
					}else{
					    Ext.Msg.alert('ADVERTENCIA ', 'Ingrese la semana desde la que aplicara el el formato');
					}
                                    }
                                }
				,{
                                    text:	'Nuevo Registro'
                                    ,id:	'bt_NuevoTexto'
                                    ,xtype: 	'button'
                                    ,handler:	function(){
						fNuevo();
                                    }
                                }
				]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 120
                        ,border:false
                        ,columnWidth:0.70
			,items: [{
                                    fieldLabel:'Visible'
                                    ,id:'ltr_visible'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:false
                                    ,sqlId: 'LiCiRp_TxtVisible'
				    ,allowBlank:	false
                                    ,width:200
				    
                                },{
				    name: 		"ltr_txt1Desc"
				    ,id: 		"ltr_txt1Desc"
				    ,fieldLabel: 	"Texto 1"
				    ,xtype: 		"textarea"
				    ,readOnly:		false
				    ,width:		'80%'
				    ,visible:		true
				},{
				    name: 		"ltr_txt2Desc"
				    ,id: 		"ltr_txt2Desc"
				    ,fieldLabel: 	"Texto 2"
				    ,xtype: 		"textarea"
				    ,readOnly:		false
				    ,width:		'80%'
				    ,visible:		true
				},{
				    name: 		"ltr_txt3Desc"
				    ,id: 		"ltr_txt3Desc"
				    ,fieldLabel: 	"Texto 3"
				    ,xtype: 		"textarea"
				    ,readOnly:		false
				    ,width:		'80%'
				    ,visible:		true
				}]
                        }]
                }]
    });
    
    
    var pnl_Textos = new Ext.Panel({
        //anchor: 100
        title:'TEXTOS DEL REPORTE'
	,id:'pnl_Textos'
	,autoScroll : true
	,collapsible:true
        ,items: [FormularioTextos]
    });
    
    var pnl_TextoRep = new  Ext.Panel({
        //anchor: 100
        widht:700
	,height:800
	,title:'TEXTOS'
	,id:'pnl_TextoRep'
	,autoScroll : true
        ,items: [FormParTextos,Grid_Textos,pnl_Textos]
    });
    

function fNuevo(){
	Ext.getCmp('ltr_registro').setValue("0");
	Ext.getCmp('ltr_semanaDesde').setValue(" ");
	Ext.getCmp('ltr_txt1Titulo').setValue(" ");
	Ext.getCmp('ltr_txt1Desc').setValue(" ");
	Ext.getCmp('ltr_txt2Titulo').setValue(" ");
	Ext.getCmp('ltr_txt2Desc').setValue(" ");
	Ext.getCmp('ltr_txt3Titulo').setValue(" ");
	Ext.getCmp('ltr_txt3Desc').setValue(" ");
	Ext.getCmp('ltr_visible').setValue(" ");
	
	Ext.getCmp('ltr_semanaDesde').setDisabled(false);
	Ext.getCmp('ltr_txt1Titulo').setDisabled(false);
	Ext.getCmp('ltr_txt1Desc').setDisabled(false);
	Ext.getCmp('ltr_txt2Titulo').setDisabled(false);
	Ext.getCmp('ltr_txt2Desc').setDisabled(false);
	Ext.getCmp('ltr_txt3Titulo').setDisabled(false);
	Ext.getCmp('ltr_txt3Desc').setDisabled(false);
	Ext.getCmp('ltr_visible').setDisabled(false);
	
	// Cargar Grid
	/*storeFormInsertar =  Ext.getCmp("FormularioTextos").store;
	storeFormInsertar.removeAll();
	storeFormInsertar.baseParams.pltr_CodCliente= iltr_CodCliente;
	storeFormInsertar.load();
	
	storeFormInsertar;*/
	
	
	Ext.getCmp("FormularioTextos").load({
	url: '../Ge_Files/GeGeGe_queryToJson.php',
	params: {id: 'LiCiRp_TextosInsertar', pltr_CodCliente: iltr_CodCliente}, 
	discardUrl: false,
	nocache: false,
	text: "Cargando...",
	timeout: 1,
	scripts: false,
	metod: 'POST'
	,success: function(pResp, pOpt){
		
	}
       });
	
	
}

function fConsulta(){
	Ext.getCmp('ltr_semanaDesde').setDisabled(true);
	Ext.getCmp('ltr_txt1Titulo').setDisabled(true);
	Ext.getCmp('ltr_txt1Desc').setDisabled(true);
	Ext.getCmp('ltr_txt2Titulo').setDisabled(true);
	Ext.getCmp('ltr_txt2Desc').setDisabled(true);
	Ext.getCmp('ltr_txt3Titulo').setDisabled(true);
	Ext.getCmp('ltr_txt3Desc').setDisabled(true);
	Ext.getCmp('ltr_visible').setDisabled(false);
}



function fActualizaTextos(oParams){
  Ext.Ajax.request({
    waitMsg: 'GRABANDO...',
    url:	'../Li_Files/LiCiRp_ReporteCargaPnlGraba.php',
    method: 'POST',
    params: oParams,
    success: function(response,options){
      var responseData = Ext.util.JSON.decode(response.responseText);
      if (false == responseData.success){
	    slMens = responseData.message;
      }
      else{  
	    slMens = responseData.message;
	    // Volver a consultar el grid de tarjas:
	    // Cargar Grid
	    var pltr_semanaDesde = oParams.pltr_semanaDesde;
	    var pltr_CodCliente  = oParams.pltr_CodCliente;
	    
	    storeTextos =  Ext.getCmp("Grid_Textos").getStore();
	    storeTextos.removeAll();
	    storeTextos.baseParams.pltr_semanaDesde=pltr_semanaDesde;
	    storeTextos.baseParams.pltr_CodCliente=pltr_CodCliente;
	    storeTextos.load();
	    
      }  
      Ext.Msg.alert('AVISO ', slMens);
    }, 
    failure: function(form, e) {
      if (e.failureType == 'server') {
        slMens = 'La operacion no se ejecuto. ' + e.result.errors.id + ': ' + e.result.errors.msg;
      } else {
        slMens = 'El comando no se pudo ejecutar en el servidor';
      }
      Ext.Msg.alert('Proceso Fallido', slMens);
    }
    }); 
}; 
    

    return pnl_TextoRep;
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