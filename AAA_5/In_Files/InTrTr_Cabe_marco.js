/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Fausto Astudillo
 *  @date       12/Oct/07
*/
Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
    Ext.QuickTips.init();
    olDet=Ext.get('divDet');
    var slWidth="width:250px; text-align:'left'";
    
   /*
    *   Ejemplo de manejo de ComboBox para opciones de filtrado de datos de emb
    **
    var rdComboBase = new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;  // Origen de datos Generico
    
    dsCmbNaviera = new Ext.data.Store({
        proxy: 	new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',				// Script de servidor generico que accede a BD
                    metod: 'POST',
                    extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}    //Parametros para obtener datos segun el contexto de la consulta sql
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'XXXX_sufijo'}		// Parametro Basico: ID de consulta SQl (predefinida en PHP como variable de sesion)
    });
    dsCmbNaviera.on("beforeload", function(){		// para actualizar parametros en cada ejecucion si han existido valores modificados
        this.proxy.conn.extraParams.pVapo= Ext.get("txt_Embarque").getValue()
        this.baseParams.pVapo= Ext.get("txt_Embarque").getValue();
        this.baseParams.pAnio= Ext.get("txt_AnioOp").getValue();
        this.baseParams.pSeman= Ext.get("txt_Seman").getValue();
    })
    */
   
Ext.getDom('north').innerHTML = '<div id="north_left"></div><div id="north_right"></div>';

//calendario fecha de corte
var dr = new Ext.FormPanel({
    labelWidth: 90,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: false,
    width: 250,
	defaults: {width: 100},
	items: [/*new Ext.form.ComboBox({
					fieldLabel: 'Comprobantexx',
					id:'comprobantes',
					name:'comprobantes',
					width:150,
					//store: dsCmbVapor,
					displayField:	'txt',
					valueField:     'cod',
					hiddenName:'cnt_Embarque',
					selectOnFocus:	true,
					typeAhead: 		true,
					mode: 'remote',
					minChars: 3,
					triggerAction: 	'all',
					forceSelection: true,
					emptyText:'',
					allowBlank:     false,
					listWidth:      250
				    }) */
		]
    });
    var slWidth="width:99%; text-align:'left'";
    var slUrlBase = "../Op_Files/OpTrTr_contenedores.php?init=1&auto=1&pUrl=";
    dr.add({xtype:	'button',
	id:     'ingresar',
	cls:	 'boton-menu',
	tooltip: 'ingresar',
	text:    'ingresar',
	style:   slWidth ,
	handler: VerMantTarja
    });
    dr.add({xtype:	'button',
	id:     'modificar',
	cls:	 'boton-menu',
	tooltip: 'modificar',
	text:    'modificar',
	style:   slWidth ,
	handler: function(){
	    var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=P&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
	    //var slUrl = "CoTrTr_salcxcgrid.php?init=1&pCuenta=P&pPagina=0&pCierre="+  Ext.getCmp('txtFecha').value;
	    addTab({id:'gridConten2', title:'Cuentas por Pagar', url:slUrl});
	    //var w = Ext.getCmp('pnlIzq');
	    //w.collapsed ? w.expand() : w.collapse();
	    }
    });
    dr.add({xtype:	'button',
	id:     'eliminar',
	cls:	 'boton-menu',
	tooltip: 'eliminar',
	text:    'eliminar',
	style:   slWidth ,
	handler: function(){
	    var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=P&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
	    //var slUrl = "CoTrTr_salcxcgrid.php?init=1&pCuenta=P&pPagina=0&pCierre="+  Ext.getCmp('txtFecha').value;
	    addTab({id:'gridConten2', title:'Cuentas por Pagar', url:slUrl});
	    //var w = Ext.getCmp('pnlIzq');
	    //w.collapsed ? w.expand() : w.collapse();
	    }
    });
    
    dr.render(document.body, 'divIzq01');
    new Ext.Button({
			text: 'Consulta de Comprobantes',
			handler: addTab
		}).render(document.body, 'divIzq02');
    new Ext.Button({
			text: 'Reporte 1',
			handler: addTab
		}).render(document.body, 'divIzq03');



})//on REady



/*
*	Agregador de componentes al tab-panel
*/


function addTab(pPar){
      tabs_c.add({
      id: pPar.id,
      title: pPar.title,
      layout:'fit',
      closable: true,
      autoLoad:{url:pPar.url,
            params:{pObj: pPar.id},
            scripts: true,
            method: 'post'}
    }).show();
  }
  
var global_printer = null;  // it has to be on the index page or the generator page  always
function printmygridGO(obj){  global_printer.printGrid(obj);	} 
function printmygridGOcustom(obj){ global_printer.printCustom(obj);	}  	
function basic_printGrid(){
		global_printer = new Ext.grid.XPrinter({
			grid:grid1,  // grid object 
			pathPrinter:'../Libjs/ext/ux/printer',  	 // relative to where the Printer folder resides  
			logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
			pdfEnable: true,  // enables link PDF printing (only save as) 
			hasrowAction:false, 
			localeData:{
				Title:'',	
				subTitle:'',	
				footerText:'', 
				pageNo:'Page # ',	//page label
				printText:'Print',  //print document action label 
				closeText:'Close',  //close window action label 
				pdfText:'PDF'
            },useCustom:{  // in this case you leave null values as we dont use a custom store and TPL
				custom:false,
				customStore:null, 
				columns:[], 
				alignCols:[],
				pageToolbar:null, 
				useIt: false, 
				showIt: false, 
				location: 'bbar'
			},
			showdate:true,// show print date 
			showdateFormat:'Y-F-d H:i:s', // 
			showFooter:true,  // if the footer is shown on the pinting html 
			styles:'default' // wich style youre gonna use 
		}); 
		global_printer.prepare(); // prepare the document 
}

function basic_printGrid_exclude(){
		global_printer = new Ext.grid.XPrinter({
			grid:grid,  // grid object 
			pathPrinter:'./printer',  	 // relative to where the Printer folder resides  
			logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
			pdfEnable: true,  // enables link PDF printing (only save as) 
			hasrowAction:false, 
			excludefields:'4,',  // 0 based index , if it has numberer or action it counts as a column 
			localeData:{
				Title:'Array Grid Demo printing',	
				subTitle:'by XtPrinter',	
				footerText:'Report Footer goes here', 
				pageNo:'Page # ',	//page label
				printText:'Print',  //print document action label 
				closeText:'Close',  //close window action label 
				pdfText:'PDF'
            }, useCustom:{  // in this case you leave null values as we dont use a custom store and TPL
				custom:false,
				customStore:null, 
				columns:[], 
				alignCols:[],
				pageToolbar:null, 
				useIt: false, 
				showIt: false, 
				location: 'bbar'
			},
			showdate:true,// show print date 
			showdateFormat:'Y-F-d H:i:s', // 
			showFooter:true,  // if the footer is shown on the pinting html 
			styles:'default' // wich style youre gonna use 
		}); 
		global_printer.prepare(); // prepare the document 
}

function VerMantTarja(){
    
    //debugger;
    var frmMantTarWin;// = new Ext.Window({items:[olSemana]});
    var frmMant;
    
    /*Para Consultar Embarques*/      
    var rdComboBase = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt', 'semana']
		    ) ;
    
    //////////////////////////////////////////////////////////////////////*******
    /////////////////////////////////////////////////////////////////////
    dsCmbVapor = new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({
					url: '../Ge_Files/GeGeGe_queryToXml.php',
					metod: 'POST'
				    }),
		  reader:		rdComboBase,
		  baseParams: {id : 'LiEmTj_embarlist'}
    });
    //dsCmbVapor.load({params: {pSeman : win.findById("txt_semana").getValue()}});

    /*Para Consultar Puertos de Embarque*/      
    var rdComboBaseGeneral = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;
    dsCmbPtoEmbarque = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_ptoEmbarque'}
    });
    
    /*Para Consultar productores*/      
    dsCmbProductor = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_productores'}
    });
    
    /*Para Consultar estados*/      
    dsCmbEstado = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_estado'}
    });
    
    /*Para Consultar zonas*/      
    dsCmbZona = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_zona'}
    });
    //////////////////////////////////////////////////777777777777
    /////////////////////////////////////////////////////777777777777
    //////////////////////////////////////////////////////////////
    var oltipComp = new Ext.form.ComboBox({
			fieldLabel:'Tip_Comp',
			id:'txt_comprobante',
			name:'txt_comprobante',
			width:150,
			store: dsCmbVapor,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_RefOperativa',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250 ,
			tabindex:3
			/*,onSelect: function(record){
			    //alert(record.data.semana);
			    win.findById("txt_semana").setValue(record.data.semana);
			}*/
			,listeners: {'select': function (combo,record){
					frmMant.findById("tac_Semana").setValue(record.data.semana);
				    }}
		    });
    slDateFmt  ='d-m-y';
    slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|';
    var olNumTarja = {
                        xtype:'numberfield',
                        fieldLabel: '# Comp',
                        name: 'comp_numero',
                        id: 'comp_numero',
			allowBlank:false
			,validator: function(field){
                                        if(field == 1){
					    return true
					}else{
					    Ext.Msg.alert('AVISO', 'Numero de Tarja ya fue ingresada');
                                            return false;
					}
                                    }
                        , validateOnBlur : true 
                    };

    var olFechaCont = {
                    xtype:'datefield',
                    fieldLabel: 'Fecha_Cont',
                    name: 'tac_Fecha_Cont',
                    id: 'tac_Fecha_Cont',
		    allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
                };
    var olSemana = {
                    xtype:'numberfield',
                    fieldLabel: 'Comprobante',
                    name: 'tac_comprobante', //debe ser igual al campo de la tabla
                    id: 'tac_comprobante',
		    allowBlank:false
		    ,tabindex:2
                };
    var olFechaVenc = {
                    xtype:'datefield',
                    fieldLabel: 'Fecha_Venc',
                    name: 'tac_Fecha_Venc',
                    id: 'tac_Fecha_Venc',
		    allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
                };
    var olvalor = {
                        xtype:'textfield',
                        fieldLabel: 'Valor',
                        name: 'tac_valor',
                        id: 'tac_valor'
                    };
    var olFechaTrans = {
                    xtype:'datefield',
                    fieldLabel: 'Fecha_Trans',
                    name: 'tac_Fecha_trans',
                    id: 'tac_Fecha_trans',
		    allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
                };
    var olRetencion = {
                        xtype:'textfield',
                        fieldLabel: '# Retencion',
                        name: 'tac_retencion',
                        id: 'tac_retencion'
                    };
    var olMoneda = new Ext.form.ComboBox({
			fieldLabel:'Moneda',
			id:'txt_moneda',
			name:'txt_moneda',
			//width:150,
			store: dsCmbZona,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_Zona',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250 ,
			tabindex:10			
		    });
    var olBanco = new Ext.form.ComboBox({
			fieldLabel:'Banco',
			id:'txt_banco',
			name:'txt_banco',
			//width:150,
			store: dsCmbZona,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_Zona',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250 ,
			tabindex:10			
		    });
    var olEstado = new Ext.form.ComboBox({
			fieldLabel:'Estado',
			id:'txt_estado',
			name:'txt_estado',
			//width:150,
			store: dsCmbZona,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_Zona',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250 ,
			tabindex:10			
		    });
    var olContenedor = {
                        xtype:'textfield',
                        fieldLabel: 'Contenedor',
                        name: 'tac_Contenedor',
                        id: 'tac_Contenedor'
                    };
    var olSello =  {
                        xtype:'textfield',
                        fieldLabel: 'Sello',
                        name: 'tac_Sello',
                        id: 'tac_Sello'
                    };
    var olProductor = new Ext.form.ComboBox({
			fieldLabel:'Productor',
			id:'txt_productor',
			name:'txt_productor',
			width:150,
			store: dsCmbProductor,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_Embarcador',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250 ,
			tabindex:4			
		    });
    var olHacienda = {
                        xtype:'combo',
                        fieldLabel: 'Hacienda',
                        name: 'tac_UniProduccion',
                        id: 'tac_UniProduccion'
                    };
    var olTransporte = {
                        xtype:'textfield',
                        fieldLabel: 'Transporte',
                        name: 'tac_Transporte',
                        id: 'tac_Transporte'
                    };
    var olTransportista = {
                        xtype:'textfield',
                        fieldLabel: 'Transportista',
                        name: 'tac_RefTranspor',
                        id: 'tac_RefTranspor'
                    };
    var olCalidad = {
                        xtype:'textfield',
                        fieldLabel: '%Calidad',
                        name: 'tac_ResCalidad',
                        id: 'tac_ResCalidad'
                    };
    var olObservacion = {
                        xtype:'textfield',
                        fieldLabel: 'Observacion',
                        name: 'tac_Observaciones',
                        id: 'tac_Observaciones'
                    };
    var olPreEval = {
                        xtype:'checkbox',
                        fieldLabel: 'Pre-Evaluacion',
                        name: 'tac_Evaluada',
                        id: 'tac_Evaluada'
                    };
    var olCodEval = {
                        xtype:'textfield',
                        fieldLabel: 'Cod Evaluador',
                        name: 'tac_CodEvaluador',
                        id: 'tac_CodEvaluador'
                    };
    var olGrupoLiq = {
                        xtype:'textfield',
                        fieldLabel: 'Grupo Liq.',
                        name: 'tac_GrupLiquidacion',
                        id: 'tac_GrupLiquidacion'
                    };
    var olNumLiq = {
                        xtype:'textfield',
                        fieldLabel: 'Num Liq.',
                        name: 'tac_NumLiquid',
                        id: 'tac_NumLiquid'
                    };
    var olCartonera = {
                        xtype:'combo',
                        fieldLabel: 'Cartonera',
                        name: 'Cartonera',
                        id: 'Cartonera',
			hiddenName:'tac_CodCartonera'
                    } ;
    var olEstado = new Ext.form.ComboBox({
			fieldLabel:'Estado',
			id:'txt_estado',
			name:'txt_estado',
			//width:150,
			store: dsCmbEstado,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_Estado',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250 ,
			tabindex:10			
		    });
    
    
    var olDigitado ={
                        xtype:'textfield',
                        fieldLabel: 'Digitador Por',
			//text: 'Value1',
                        name: 'tac_Usuario',
                        id: 'tac_Usuario'
			,readOnly:true
                    } ;
    var olFechaReg ={
                        xtype:'textfield',
                        hideLabel: true,
			//text: 'Value1',
                        name: 'tac_FecDigitacion',
                        id: 'tac_FecDigitacion'
			,readOnly:true
                    } ;
    
    //if(!win){
    //debugger;
    if(!Ext.getCmp("frmMant")){
	//debugger;
	
	
	//the form
	var frmMant = new Ext.form.FormPanel({
	    //utl:'view/userContacts.cfm',
	    bodyStyle:'padding:5px',
	    width: 730,
	    //defaults: {width: 230},
	    border:false,
	    id:'frmMant',
	    items:[
	    {
		    // column layout with 2 columns
		     layout:'column'

		    // defaults for columns
		    ,defaults:{
			     columnWidth:0.32
			    ,layout:'form'
			    ,border:false
			    ,xtype:'panel'
			    ,bodyStyle:'padding:0 18px 0 0'
		    }
		    ,items:[
				{
				    columnWidth:0.36,
				    defaults:{anchor:'100%'}
				    ,items:[oltipComp, olSemana, olRetencion, olBanco, olContenedor]//primera columna
				},
				{	    
				    defaults:{anchor:'100%'}
				    ,items:[olNumTarja, olFechaCont, olMoneda, olEstado, olSello]//segunda columna
				},
				{
				    defaults:{anchor:'100%'}
				    ,items:[olFechaTrans, olFechaVenc, olvalor, olGrupoLiq, olNumLiq]// tercera columna
				}
			    ]
	    },
    {
		    // column layout with 2 columns
		     layout:'column'

		    // defaults for columns
		    ,defaults:{
			     columnWidth:0.32
			    ,layout:'form'
			    ,border:false
			    ,xtype:'panel'
			    ,bodyStyle:'padding:0 18px 0 0'
		    }
		    ,items:[{
			    // left column
			    // defaults for fields
			    defaults:{anchor:'100%'}
			    ,items:[olProductor, olTransporte, olPreEval]
		    },{
			    // right column
			    // defaults for fields
			    columnWidth:0.36,
			     defaults:{anchor:'100%'}
			    ,items:[olHacienda, olTransportista, olCodEval]
		    },{
			defaults:{anchor:'100%'}
			,items:[olCartonera, olCalidad, olEstado]
		      }
		    ]
	    },{
		layout:'column'
		,defaults:{
			columnWidth:0.5
		       ,layout:'form'
		       ,border:false
		       ,xtype:'panel'
		       ,bodyStyle:'padding:0 18px 0 0'
	       }
	       ,items:[
		    {columnWidth:0.6, defaults:{anchor:'100%'}
		    ,items:[olObservacion]},
		    {columnWidth:0.4, defaults:{anchor:'100%'}
		    ,items:[
			{
			    layout:'column'
			    ,defaults:{
				    columnWidth:0.5
				   ,layout:'form'
				   ,border:false
				   ,xtype:'panel'
				   ,bodyStyle:'padding:0 18px 0 0'
			   }
			   ,items:[
				{columnWidth:0.6, defaults:{anchor:'100%'}
				,items:[olDigitado]},
				{columnWidth:0.4, defaults:{anchor:'100%'}
				,items:[olFechaReg]}
			   ]
			}
		    ]}
	       ]
	    }
	    ],
	    buttons:[
		{
		    text:'Nuevo'//,
		    //type:'submit',
		    //handler:fGrabar
		},{
		    text:'Guardar',
		    type:'submit',
		    handler:fGrabar
		},
		{
		    text:'Salir',
		    handler:function(){
			win.close();
		    }
		}
	    ]
	});

        //Ext.Msg.alert('test','das ist ein test');
        var win = new Ext.Window({
            title:'Mantenimiento de Tarjas',
            layout:'fit',
            width:750,
            height:440,
	    id: "frmMantWin",
            items:frmMant
        });
        win.show();

    }
}

gaHidden = new Array();

function fGrabar(){
    //debugger;
    
    if (!Ext.getCmp("frmMant").getForm().isDirty()) {
	    Ext.Msg.alert('AVISO', 'Los datos no han cambiado. <br>No tiene sentido Grabar');
	    return false;
    }
    if (!Ext.getCmp("frmMant").getForm().isValid()) {
	    Ext.Msg.alert('ATENCION!!', 'Hay Información incompleta o inválida');
	    return false;
    }
    var olModif = Ext.getCmp("frmMant").getForm().items.items.findAll(function(olField){return olField.isDirty()});
    Ext.Msg.alert('AVISO', 'Grabar');
    
    oParams = {};
    olModif.collect(function(pObj, pIdx){
    switch (pObj.getXType()) {
	case "datefield":
	    eval("oParams." + pObj.id + " = '" +  pObj.getValue().dateFormat(pObj.format) + "'" );
	    break
	case "combo" :
	    eval("oParams." + pObj.hiddenName + " = '" +  pObj.getValue() + "'" );
	    break;
	default:
	    eval("oParams." + pObj.id + " = '" +  pObj.getValue() + "'" );
    }
    })
    ilId  = Ext.getCmp("frmMant").findById('tar_NumTarja').getValue();   //usa un valor ya asignado
    if  (ilId > 0) {
	    oParams.cnt_ID = ilId;
	    var slAction='UPD';
    }	else var slAction='ADD';
    var slAction='ADD'; //solo por prueba quitar despues
    var slParams  = Ext.urlEncode(oParams);
    slParams += "&" +  Ext.urlEncode(gaHidden);
    Ext.getCmp("frmMant").disable();
    Ext.Msg.alert('AVISO', 'Grabar 22');
    fGrabarRegistro(Ext.getCmp("frmMant"), slAction, oParams, slParams);
    
    Ext.getCmp("frmMant").enable();
};

/*function fCargaReg(pID){
  fmFormGen.load({
    url: '../Ge_Files/GeGeGe_queryToJson.php',
    params: {id: 'OpTrTr_contenedor', cnt_ID: pID}, 
    //callback: yourFunction,  @TODO 
    //scope: yourObject, // optional scope for the callback
    discardUrl: false,
    nocache: false,
    text: "Cargando...",
    timeout: 1,
    scripts: false,
	  metod: 'POST'}
	);
}*/

function fGrabarRegistro(fmForm, slAction, oParams, slParams){
  oParams.pTabla = 'liqtarjacabec';
  Ext.Ajax.request({
    waitMsg: 'GRABANDO...',
    url:	'../Ge_Files/GeGeGe_generic.crud.php?pAction=' + slAction, //-------------    +'&' + slParams,
    method: 'POST',
    params: oParams,
    success: function(response,options){
      var responseData = Ext.util.JSON.decode(response.responseText);
      switch (slAction) {
        case "ADD":
          slMens = 'Registro Creado';
          //Ext.getCmp("frmMant").findById('tar_NumTarja').setValue(responseData.lastId);
          //fCargaReg(Ext.getCmp("frmMant").findById('tar_NumTarja').getValue()); //solo por prueba se quito
          break;
        case "UPD":
          slMens = 'Registro Actualizado';
          //fCargaReg(Ext.getCmp("frmMant").findById('tar_NumTarja').getValue());//solo por prueba se quito
          break;
      }
      Ext.Msg.alert('AVISO ', slMens);
    }, 
    failure: function(form, e) {
      if (e.failureType == 'server') {
        slMens = 'La operación no se ejecutó. ' + e.result.errors.id + ': ' + e.result.errors.msg;
      } else {
        slMens = 'El comando no se pudo ejecutar en el servidor';
      }
      Ext.Msg.alert('Proceso Fallido', slMens);
    }
  }//end request config
  ); //end request  
}; //end updateDB