Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"

var olPnlComision;

Ext.onReady(function(){
    Ext.QuickTips.init();
    
    var pBtnComision =  new Ext.FormPanel({
				labelWidth: 90,
				frame: false,
				bodyStyle:'padding:5px 5px 0',
				border: false,
				width: 250
    });
    //Ingresar partida
    var btIngComsion = new Ext.Button({ 
				text : "INGRESAR COMISIONES"
			       ,width: 150
			       ,handler : function(){
					if (!olPnlComision ){
					    olPnlComision = fComisiones();
					}
					addTab1({id:"tbComision",title: 'Comision',url:'',items:[olPnlComision]});
					Ext.getCmp("tbComision").doLayout();
				}
    }); pBtnComision.add(btIngComsion);
    pBtnComision.render(document.body, 'divIzq01');
    
    parametros = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 100
                        ,border:false
                        ,columnWidth:0.30
                        ,items: [{
                                    fieldLabel:'Semana'
                                    ,id:'pro_Semana'
                                    ,xtype: 'textfield'
                                    ,width:50
                                    ,allowBlank:false
                                    ,maxLength:4
                                    ,minLength:4
                                    
                                },{
                                    fieldLabel:'Exportar a Excel'
                                    ,id:'pExcel'
                                    ,xtype: 'checkbox'
                                    ,allowBlank:true
                                }]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 120
                        ,border:false
                        ,columnWidth:0.70
                        ,items: [{
                                    fieldLabel:'Productor'
                                    ,id:'com_codReceptor'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:false
                                    ,sqlId: 'LiLiRp_Productores'
                                    ,width:200
                                }]
                        }]
                }]
    });
    
    botones = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
		,items:[{layout: 'form'
                        ,border:false
			,columnWidth:0.30
                        ,items: [{
                                    text:'&nbsp;Rol&nbsp;&nbsp;&nbsp;&nbsp;de&nbsp;&nbsp;&nbsp;liquidacion'
                                    ,id:'bt_cuadroLiq'
                                    ,xtype: 'button'
				    ,width:100
                                    ,handler:function(){
					if (fValida() == true){
					    pPar = fParametros();
					    url = "LiLiRp_CuadroLiquidacion_2.rpt.php?"+pPar;
					    window.open(url)
                                        }
                                    }
                                }
				,{
                                    text:'Cuadro de embarque'
                                    ,id:'bt_cuadroEmb'
                                    ,xtype: 'button'
				    ,width:100
				    ,handler:function(){
					if (fValida() == true){
					    pPar = fParametros();
					    url = "LiLiRp_CuadroEmbarque.rpt.php?"+pPar;
					    window.open(url)
					}
                                    }
                                }]
                        },
			{ layout:'column'
			,border:false
			,columnWidth:0.30
			,items:[{layout: 'form'
				,border:false
				,items: [{
					    text:'&nbsp;Cuadro&nbsp;&nbsp;&nbsp;&nbsp;de&nbsp;&nbsp;&nbsp;&nbsp;precios&nbsp;'
					    ,id:'bt_cuadroPrec'
					    ,xtype: 'button'
					    ,minWidth:'100'
					    ,handler:function(){
						if (fValida() == true){
						    pPar = fParametros();
						    url = "LiLiRp_CuadroPrecios.rpt.php?"+pPar;
						    window.open(url)
						}
					    }
					},{
					    text:'Comisiones/Transporte'
					    ,id:'bt_comision'
					    ,xtype: 'button'
					    ,minWidth:'100'
					    ,handler:function(){
						if (fValida() == true){
						    pPar = fParametros();
						    url = "LiLiRp_Comisiones.rpt.php?"+pPar;
						    window.open(url)
						}
					    }
					}
					]
				}]
			}]
                },
		]
    });
    
    var pnl_RepLiq = new  Ext.Panel({
        //anchor: 100
        //widht:100
	title:'Parametros para el reporte'
	,id:'pnl_RepLiq'
	,autoScroll : true
        ,items: [parametros,botones]
    });
 
    //Ext.getCmp('bt_cuadroPrec').ownerCt.minButtonWidth = 200;
 
    addTab1({id:"TabRepLiq",title: 'Reportes de Liquidacion',url:'',items:[pnl_RepLiq]});
    Ext.getCmp("TabRepLiq").doLayout();
 
});


function fValida(){
    if (Ext.getCmp("pro_Semana").getValue() == ""){
	Ext.Msg.alert('ATENCION','Ingrese una semana correcta para generar el reporte');
	return false;
    }
    if (Ext.getCmp("pro_Semana").isValid() == false){
	Ext.Msg.alert('ATENCION','Ingrese una semana correcta para generar el reporte');
	return false;
    }
    return true;
}

function fParametros(){
    pPar = "";
    if (Ext.getCmp("com_codReceptor").isValid()) {pPar += "&com_codReceptor="+Ext.getCmp("com_codReceptor").getValue()}
    if (Ext.getCmp("pro_Semana").getValue() != "") {pPar += "&pro_Semana="+Ext.getCmp("pro_Semana").getValue()}
    if (Ext.getCmp("pExcel").getValue() == true) {pPar += "&pExcel=1"}
    return pPar;
}



/* ***************** MOSTRAR PANTALLA PARA GUARDAR COMISIONES Y TRANSPORTE *********************** */
function fComisiones(){
    // Para que lea el Store para los datos del formulario
    function olRecComision(){
	return [
		{name: 'lde_id', type: 'int'}
	       ,{name: 'lde_semana', type: 'int'}
	       ,{name: 'lde_tipoVariable', type: 'int'}
	       ,{name: 'lde_cajas', type: 'int'}
	       ,{name: 'lde_precio', type: 'float'}
	       ,{name: 'lde_auxiliar', type: 'int'}
	       ,{name: 'lde_estado', type: 'int'}
	       ,{name: 'TxtipoVariable', type: 'string'}
	       ,{name: 'PrecioTotal', type: 'float'}
	       ,{name: 'Txauxiliar', type: 'string'}
	       ]};
	
    //var olRecCom = Ext.data.Record.create();
    rdComision = new Ext.data.JsonReader({root : 'data',  successProperty: 'success',totalProperty: 'totalRecords',  id:'lde_id'}, olRecComision());
    
    parametrosComision = ({
         xtype:'form'
	,id:'parametrosComision'
	,reader	:rdComision
	,tbar:[	{
		    text	:'Nuevo'
		    ,id		:'btn_nuevo'
		    ,xtype	:'button'
		    ,width	:60
		    ,iconCls	:'iconNuevo'
		    ,handler	: function (){
				Ext.getCmp("parametrosComision").getForm().reset();
		    }
		 },{
		    text	:'Guardar'
		    ,id		:'btn_guardar'
		    ,xtype	:'button'
		    ,width	:60
		    ,iconCls	:'iconGrabar'
		    ,handler	: function (){
				oParmGrab = {};
				fGrabaComision('ING');
		    }
		 },{
		    text	:'Eliminar'
		    ,id		:'btn_eliminar'
		    ,xtype	:'button'
		    ,width	:60
		    ,iconCls	:'iconEliminar'
		    ,handler	: function (){
				if (Ext.getCmp('lde_id').getValue() == 0 || Ext.getCmp('lde_id').getValue() == "") {
				    Ext.Msg.alert('ATENCION','SELECCIONE REGISTRO A ELIMINAR');
				    return;
				}
				Ext.MessageBox.confirm('ELIMINAR', 'Esta seguro de eliminar el registro?',
				function aplicar(respuesta) {
				    if(respuesta == 'yes'){
					oParmGrab = {};
					fGrabaComision('DEL');
				    }
				});
		    }
		 }
	]
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 100
                        ,border:false
                        ,columnWidth:0.30
                        ,items: [{
                                    fieldLabel:'Semana'
                                    ,id:'lde_semana'
                                    ,xtype: 'textfield'
                                    ,width:50
                                    ,allowBlank:false
                                    ,maxLength:4
                                    ,minLength:4
                                    
                                },
				{
				  fieldLabel	:'Tipo Costo'
				  ,id		:'lde_tipoVariable'
				  ,xtype	:'genCmbBox'
				  ,minChars	:1
				  ,allowBlank	:false
				  ,sqlId	:'LiLiRp_TipoComision'
				  ,width	:150
				  ,listeners: {'select': function (field,newValue,oldValue){
						    //Habilitar campos de acuerdo a lo parametrizado para el tipo de costo(Comision o transporte)
						    oParTipCosto = {};
						    oParTipCosto.ReqAux   = this.getXmlField('ReqAux');
						    oParTipCosto.ReqCajas = this.getXmlField('ReqCajas');
						    oParTipCosto.ReqTpCja = this.getXmlField('ReqTpCja');
						    fHabilitaCmpo(oParTipCosto,'S');
						}}
				},
				{
                                    fieldLabel:'No. Cajas'
                                    ,id:'lde_cajas'
                                    ,xtype: 'numberfield'
                                    ,width:150
                                    ,allowBlank:true
				    ,allowDecimals:false
                                    ,minValue:0
				    ,disabled:true
                                }]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 120
                        ,border:false
                        ,columnWidth:0.70
                        ,items: [{
                                    fieldLabel:'ID'
                                    ,id:'lde_id'
                                    ,xtype: 'textfield'
                                    ,width:50
                                    ,allowBlank:false
				    ,value:0
                                    ,maxLength:4
                                    ,minLength:4
				    ,disabled:true
                                    
                                },{
                                    fieldLabel:'Comisionista'
                                    ,id:'lde_auxiliar'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:true
                                    ,sqlId: 'LiLiRp_Comisionista'
                                    ,width:200
				    ,disabled:true
                                },
				{
                                    fieldLabel:'Precio'
                                    ,id:'lde_precio'
                                    ,xtype: 'numberfield'
                                    ,width:50
                                    ,allowBlank:false
				    ,allowDecimals:true
				    ,decimalPrecision:4
                                    ,minValue:0
                                }]
                        }]
                }]
    });
    
    var stComision = new Ext.data.JsonStore({
			id:"stComision",
			url: '../../AAA_5/Ge_Files/GeGeGe_queryToJson.php',
			baseParams: {id: 'LiLiRp_ComisionSemanas',start:0,/* ID: ptra_Id,*/ limit:100, sort:'lde_id', dir:'ASC'},
			root : 'data',
			successProperty: 'success',
			totalProperty:'totalRecords',
			fields:olRecComision(),
			sortInfo: {field:'lde_id', direction: 'ASC'},
			pruneModifiedRecords: true
		    });
    var ComisionCM = new Ext.grid.ColumnModel(  
			      [{  
				   header: 'ID'
				  ,dataIndex: 'lde_id'
				  ,width: 40
			       },{  
				  header: 'Semana'
				  ,dataIndex: 'lde_semana'
				  ,width: 50
			       },{  
				  header: 'Tipo Costo'
				  ,dataIndex: 'TxtipoVariable'
				  ,width: 80
			       },{  
				  header: 'Comisionista'
				  ,dataIndex: 'Txauxiliar'
				  ,width: 120
			       },{  
				  header: 'Cajas'
				  ,dataIndex: 'lde_cajas'
				  ,width: 40
			       },{  
				  header: 'Precio'
				  ,dataIndex: 'lde_precio'
				  ,width: 80
			       },{  
				  header: 'Total'
				  ,dataIndex: 'PrecioTotal'
				  ,width: 80
			       }
			      ]
		    );
    var grdComision = new Ext.grid.GridPanel({
			       id: 'grdComision'
			       ,lazyRender: true
			       ,store: stComision
			       ,stripeRows :true
			       ,collapsible: true
			       ,cm: ComisionCM
			       ,clicksToEdit: 1
			       ,height:400
			       ,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
			       ,title:"Historial de Costos por semana"
			       ,tbar: [	'Semana a consultar:',{
					    fieldLabel:'Semana a consultar'
					    ,id:'lde_semanaCons'
					    ,xtype: 'textfield'
					    ,width:50
					    ,allowBlank:false
					    ,maxLength:4
					    ,minLength:4
					    
					},
					{
					    id:'btnCons'
					   ,text: 'Consultar'
					   ,iconCls:'iconBuscar'
					   ,handler: function () {
						    if (Ext.getCmp("lde_semanaCons").isValid() == false){
							Ext.Msg.alert('ATENCION','Ingrese una semana valida para consultar');
							return;
						    }
						    storeComisionGrd = Ext.getCmp("grdComision").getStore();
						    storeComisionGrd.removeAll();
						    storeComisionGrd.baseParams.semana = Ext.getCmp("lde_semanaCons").getValue();
						    storeComisionGrd.load();
					}
				 }
			       ]
			       ,listeners: {
				    destroy: function(c) {
					c.getStore().destroy();
				    }
				}
		    });
    grdComision.on('rowdblclick', function(sm, rowIdx,e ) {
	var pRec = grdComision.getStore().getAt(rowIdx);
	ConsComision(pRec.get("lde_id"));
    });
    
    function ConsComision(id){
	Ext.getCmp("parametrosComision").getForm().reset();
	
	Ext.getCmp("parametrosComision").load({
	    url: '../../AAA_5/Ge_Files/GeGeGe_queryToJson.php',
	    params: {id: 'LiLiRp_ComisionEspec', IDCom: id}, 
	    discardUrl: false,
	    nocache: false,
	    text: "Cargando...",
	    timeout: 1,
	    scripts: false,
		  metod: 'POST'
	    ,success: function(pResp, pOpt){
		var Resp = pResp.reader.jsonData.data[0];
		oParTipCosto = {};
		oParTipCosto.ReqAux   = Resp.ReqAux;
		oParTipCosto.ReqCajas = Resp.ReqCajas;
		oParTipCosto.ReqTpCja = Resp.ReqTpCja;
		fHabilitaCmpo(oParTipCosto,'N');
	    }
	});
    }
    /** 
     * 	HABILITAR CAMPOS DE COMISION  */
    function fHabilitaCmpo(oParTipCosto,limpiaCmpo){
	//oParTipCosto.ReqTpCja;
	
	if (oParTipCosto.ReqAux == 1){ //Desbloquea Auxiliar de Comisionista
	    Ext.getCmp("lde_auxiliar").setDisabled(false);
	    Ext.getCmp("lde_auxiliar").allowBlank =false;
	}else{
	    Ext.getCmp("lde_auxiliar").setDisabled(true);
	    Ext.getCmp("lde_auxiliar").allowBlank =true;
	}
	
	if (oParTipCosto.ReqCajas == 1){ //Desbloquea Campo para numero de cajas
	    Ext.getCmp("lde_cajas").setDisabled(false);
	    Ext.getCmp("lde_cajas").allowBlank =false;
	}else{
	    Ext.getCmp("lde_cajas").setDisabled(true);
	    Ext.getCmp("lde_cajas").allowBlank =true;
	}
	
	if (limpiaCmpo == 'S'){
	    Ext.getCmp("lde_cajas").setValue("");
	    Ext.getCmp('lde_auxiliar').setValue("");
	    Ext.getCmp('lde_precio').setValue("");    
	}
    }
    

    /**
     * GRABAR DATOS   */
    function fGrabaComision(slAction){
	if (slAction == 'DEL'){
	    if (Ext.getCmp('lde_id').getValue() == 0 || Ext.getCmp('lde_id').getValue() == "") {
		Ext.Msg.alert('ATENCION','SELECCIONE REGISTRO A ELIMINAR');
		return;
	    }
	}else{
	    if (Ext.getCmp('parametrosComision').getForm().isValid() == false) {
		Ext.Msg.alert('ATENCION','Existen datos incompletos o invalidos');
		return;
	    }    
	}
	
	
	var olModif = Ext.getCmp("parametrosComision").getForm().items.items;//Todos los items del formulario
		oParams = {};
		olModif.collect(function(pObj, pIdx){
		switch (pObj.getXType()) {
		    case "datefield":
			eval("oParams." + pObj.id + " = '" +  pObj.getValue().dateFormat("Y-m-d") + "'" ); //pObj.format
			break
		    case "combo" :
			eval("oParams." + pObj.hiddenName + " = '" +  pObj.getValue() + "'" );
			break;
		    default:
			if (pObj.id.substring(0,4) != "ext-"){
			    eval("oParams." + pObj.id + " = '" +  pObj.getValue() + "'" );
			}
		    }
		})
		
	// Asignar Null a auxiliar si es que está sin seleccionar
	if (Ext.getCmp('lde_auxiliar').getValue() == "") oParams.lde_auxiliar = "NULL"
	if (Ext.getCmp('lde_cajas').getValue() == "") oParams.lde_cajas = "NULL"
	
	var slParams  = Ext.urlEncode(oParams);
	slParams += "&" +  Ext.urlEncode(gaHidden);
	
	
	
	Ext.Ajax.request({
	    waitMsg: 'GRABANDO...',
	    url:	'LiLiRp_panelReportesGrabar.php?tipo=' + slAction +'&'+ slParams, 
	    method: 'POST',
	    params: oParams,
	    success: function(response,options,respuesta){
		var resp = response.responseText;
		if (slAction == 'ING'){
		    if (resp == 0){
		      Ext.Msg.alert('No se guardo el registro');
		    }else{
		      Ext.Msg.alert('Registro guardado');
		      Ext.getCmp("lde_id").setValue(resp);
		    }    
		}else{
		    Ext.Msg.alert(resp);
		    Ext.getCmp("parametrosComision").getForm().reset();
		}
		
		if (Ext.getCmp("lde_semanaCons").isValid() == true){
		    storeComisionGrd = Ext.getCmp("grdComision").getStore();
		    storeComisionGrd.removeAll();
		    storeComisionGrd.baseParams.semana = Ext.getCmp("lde_semanaCons").getValue();
		    storeComisionGrd.load();
		}
						    
		
	    }, 
	    failure: function(form, e) {
	      if (e.failureType == 'server') {slMens = 'La operacion no se ejecuto. ' + e.result.errors.id + ': ' + e.result.errors.msg;
	      } else { slMens = 'El comando no se pudo ejecutar en el servidor'; }
	      Ext.Msg.alert('Proceso Fallido', slMens);
	    }
	  }
	  );
	
    }
    /**
     * RETORNAR PANEL DE INGRESO DE DATOS  */
    pnlComision = new Ext.Panel({
				items:[parametrosComision,grdComision] 
			   });


    return pnlComision;
}
/*-----------------------------------------------------------------------*/
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