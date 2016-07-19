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
	tooltip: 'Ingresa un Comprobante Nuevo',
	text:    'INGRESAR',
	style:   slWidth ,
	handler: fAgregarComprob
    });
    dr.add({xtype:	'button',
	id:     'modificar',
	cls:	 'boton-menu',
	tooltip: 'Modifica un comprobante',
	text:    'MODIFICAR',
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
  

function fAgregarComprob(){
    
    //debugger;
    var color = "#E7E7E7";
    var estilo = 'background-color: '+color+'; font-size:4px; border-style: none; ';
    var winComprob;// = new Ext.Window({items:[olSemana]});
    var frmComprob;
    
    /*Para Consultar Embarques*/      
    var rdComboBase = new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;
    
    //////////////////////////////////////////////////////////////////////*******
    /////////////////////////////////////////////////////////////////////
    dsCmbTipoComp = new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({
					url: '../Ge_Files/GeGeGe_queryToXml.php',
					metod: 'POST'
				    }),
		  reader:		rdComboBase,
		  baseParams: {id : 'CoTrTr_tipocomp'}
    });
    //dsCmbVapor.load({params: {pSeman : win.findById("txt_semana").getValue()}});

    /*Para Consultar Puertos de Embarque*/      
    var rdComboBaseGeneral = new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;
    dsCmbPersonas = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'CoTrTr_personas'}
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
    
/* Al seleccionar el tipo de Comprobante: Aplica las modificaciones correspondientes al formato del comprobantes
	*  ajustandose a las caracteristicas de la transaccion,ocultando y presentando los campos requeridos en base a lo
	*  establecido en la configuracion de la transaccion
	*  Actualiza la propiedad configTrans con datos de la transaccion elegida
	*  @param  pObj		Object		Objeto en el que se produjo el evento
	*  @param  pRec		Object		Registro de datos origen del combo
	*/
	function fOnSelTipoComp(combo,pRec){
		olRec=pRec.data;
		if (true == olRec.ReqEmisor){	// Si la tRansaccion requiere un "Emisor"
			frmComprob.findById("com_Emisor").setValue(olRec.cla_EmiDefault);
			frmComprob.findById("com_Emisor").fieldLabel(olrec.cla_TxtEmisor);
		}
		else {
			frmComprob.findById("com_Emisor").setValue(0);
			frmComprob.findById("com_Emisor").hide();
		}
		if (true == olRec.ReqReceptor){	// Si la tRansaccion requiere un "receptor"
			frmComprob.findById("com_CodReceptor").setValue(olRec.cla_EmiDefault);
			frmComprob.findById("com_CodReceptor").fieldLabel(olrec.cla_TxtReceptor);
		}
		else {
			frmComprob.findById("com_CodReceptor").setValue(0);
			frmComprob.findById("com_CodReceptor").hide();
			frmComprob.findById("com_Receptor").setValue("-");
		}
		frmComprob.configTrans =prec.Data;
	}

    var olTipoComp = new Ext.form.ComboBox({
		fieldLabel:'Tipo',
		id:'txt_comprobante',
		name:'txt_comprobante',
		width:150,
		store: dsCmbTipoComp,
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'com_TipoComp',
		selectOnFocus:	true,
		typeAhead: 		true,
		mode: 'remote',
		minChars: 3,
		triggerAction: 	'all',
		forceSelection: true,
		emptyText:'',
		allowBlank:     false,
		listWidth:      350 ,
		tabindex:3
		,listeners: {'select': fOnSelTipoComp}
	});
    slDateFmt  ='d-m-y';
    slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|';
    var olRegNumero = {
		xtype:'textfield',
	    hideLabel: true,
		name: 'com_RegNumero',
		id: 'com_RegNumero'
	};

	var olNumComp = {
		xtype:'numberfield',
		hideLabel: true,
		name: 'com_NumComp',
		id: 'com_NumComp',
		allowBlank:false
		,validator: function(field){
			if(field == 1){
				 return true
			 }else{
				 Ext.Msg.alert('AVISO', 'Comprobante ya fue ingresado');
				 return false;
			 }
		}
		,validateOnBlur : true 
	};
    var olFecTrans = {
		xtype:'datefield',
		fieldLabel: 'Fch. Transac.',
		name: 'com_FecTrans',
		tooltip: 'Fecha de Transaccion (en que se emite)',
		id: 'com_FecTrans',
		allowBlank:false
		,format: slDateFmt
		,altFormats: slDateFmts
	};
	var olFecContab = {
		xtype:'datefield',
		fieldLabel: 'Fch.Contable',
		name: 'com_FecContab',
		id: 'com_FecContab',
		allowBlank:false
		,format: slDateFmt
		,altFormats: slDateFmts
		,tooltip: 'Fecha de efecto Contable, que aplica para estados financieros'
    };
    var olFecVencim = {
		xtype:'datefield',
		fieldLabel: 'Fch. Vencim.',
		name: 'com_FecVencim',
		id: 'com_FecVencim',
		allowBlank:false
		,format: slDateFmt
		,altFormats: slDateFmts
	};
    var olRefOperat = {
        xtype:'numberfield',
		fieldLabel: 'Ref.Operativa',
		name: 'com_RefOperat', //debe ser igual al campo de la tabla
		id: 'com_RefOperat',
		allowBlank:false
	    ,tabindex:2
    };
    var olValor = {
		xtype:'textfield',
		fieldLabel: 'Valor',
		name: 'com_Valor',
		id: 'tac_valor'
	};
    var olNumRetenc = {
		xtype:'textfield',
		hideLabel: true,
		name: 'com_NumRetenc',
		id: 'com_NumRetenc'
	};
    var olEmisor = new Ext.form.ComboBox({
		fieldLabel:'',
		id:'txt_Emisor',
		name:'txt_Emisor',
		//width:150,
		store: dsCmbPersonas,
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'com_Emisor',
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

    var olEmpresa =  {
		xtype:'textfield',
		fieldLabel: 'Empresa',
		name: 'com_CodEmpresa',
		id: 'com_CodEmpresa'
	};
     var olReceptor= {
		xtype:'textfield',
		fieldLabel: '-',
		name: 'com_Receptor',
		id: 'com_Receptor'
	};
	var olCodReceptor = {
		xtype:'numberfield',
		hideLabel: true,
		//fieldLabel: 'Cod_Ben',
		name: 'com_Codreceptor', //debe ser igual al campo de la tabla
		id: 'com_Codreceptor',
		allowBlank:false
		,tabindex:2 //orden d tab cuando se lo presiona
	};
    var olConcepto = {
		xtype:'textfield',
		fieldLabel: 'com_Concepto',
		name: 'com_Concepto',
		id: 'com_Concepto'
	};
    var olEstado = new Ext.form.ComboBox({
		fieldLabel:'Estado',
		id:'txt_estado',
		name:'txt_estado',
		//width:150,
		store: {xtype: 'genCmbStore', sqlId:'CoTrTr_Estados'},
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
    
    var olFechaReg ={
		xtype:'textfield',
		hideLabel: true,
		//text: 'Value1',
		name: 'tac_FecDigitacion',
		id: 'tac_FecDigitacion'
		,readOnly:true
	} ;
////////////////////////////////////////////////////////////////////////////////////////////////////////
	var olMoneda = new Ext.form.ComboBox({
		fieldLabel:'Moneda',
		id:'txt_moneda',		//el mismo nombre de la base de datos
		name:'txt_moneda',
		width:150,
		store: {xtype: 'genCmbStore', sqlId:'CoTrTr_Monedas'},
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'com_CodMoneda',//el nombre del campo de la base cuando es combo y cuando es texfiel en el id
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
		,listeners: {'select': function (combo,record){
				frmComprob.findById("tac_Semana").setValue(record.data.semana);
				}}
		});
	var olTipoCambio ={
		xtype:'numberfield',
		hideLabel: true,
		name: 'com_TipoCambio',
		id: 'com_TipoCambio'
		,readOnly:true
	} ;
	      
	var olLibro = new Ext.form.ComboBox({
		fieldLabel:'Libro',
		id:'txt_libro',
		name:'txt_libro',
		width:150,
		store: {xtype: 'genCmbStore', sqlId:'CoTrTr_Libros'},
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'com_Libro',
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
		    
	var olNumRetenc = {
		xtype:'textfield',
		fieldLabel: 'Retenc #',
		name: 'com_NumRetenc',
		id: 'com_NumRetenc'
	};
		    
	var olRefOperat = {
		xtype:'textfield'
		,fieldLabel: 'Ref.Operativa'
		,tooltip: 'Referencia Operativa: Unidad deprodcucion que puede ser Semana, Vapor, Numero de orden de produccion, etc.'
		,name: 'com_RefOperat'
		,id: 'com_RefOperat'
	};
		    
		    
    var olPanel = new Ext.Panel({
		frame:true,
		split: true,
		title: 'Datos',
		border: true,
		collapsible:true,
		bodyStyle:'background-color: '+color,
		autoHeight: true,
		items: [
			{
				layout: 'column',//asi sale como columna
				items:[
					{//columnas	  
						xtype:'fieldset',
						autoHeight:true,
						defaults:{anchor:'85%'},
						border: false,
						labelWidth:70,//distancia entre etiqueta y control
						bodyStyle:'background-color: '+color,
						items :[olMoneda]
					},
					{//columnas		  
						xtype:'fieldset',
						border: false,
						autoHeight:true,
						defaults:{anchor:'50%'},
						//bodyStyle:'background-color: '+color+';margin-left: 91px',//distancias entre objetos
						bodyStyle:'background-color: '+color,
						items :[olTipoCambio]
					},
					{//columnas	  
						xtype:'fieldset',
						autoHeight:true,
						defaults:{anchor:'80%'},
						border: false,
						labelWidth:70,//distancia entre etiqueta y control
						bodyStyle:'background-color: '+color+';margin-left: 71px',
						items :[olLibro]
					},
					{//columnas	  
						xtype:'fieldset',
						autoHeight:true,
						//columnWidth:0.74,
						//defaults:{anchor:'78%'},
						//defaults:{anchor:'60%'},
						defaults:{anchor:'78%'},
						border: false,
						labelWidth:65,//distancia entre etiqueta y control
						
						items :[olNumRetenc]
					},
					{//columnas	  
						xtype:'fieldset',
						autoHeight:true,
						defaults:{anchor:'80%'},
						border: false,
						labelWidth:65,//distancia entre etiqueta y control
						//bodyStyle:'background-color: '+color+';margin-left: 0.1px',
						items :[olRefOperat]
					}
			  ]
			}
		]
	});
//////////////////////////////////////////////////////////////////////////////////////////////////////		    
  var olPnlComp = new Ext.form.FieldSet({
		frame:true
		,split: true
		,title: 'Datos'
		,border: true
		,collapsible:true
		,bodyStyle:'background-color: '+color
		,autoHeight: true
		//,layout: 'column'
		/*,defaults:	{
				columnWidth:0.30
				,layout:'form'
				,border:false
				,labelWidth:71      
				,bodyStyle:'background-color: '+color
			}*/
		,items: [olTipoComp,olNumComp, olRegNumero
			/*layout: 'column'
			,bodyStyle:estilo
			, items:[{  
					items :[olTipoComp]
				},{
					items :[olNumComp]
				},{
					items :[olRegNumero]
				}]*/
		]
	})  
    if(!Ext.getCmp("frmComprob")){
	//debugger;
	
	
		//the form
		var frmComprob = new Ext.form.FormPanel({
	    //utl:'view/userContacts.cfm',
	    bodyStyle:'padding:5px',
	    width: 730,
	    //defaults: {width: 230},
	    border:false,
	    id:'frmComprob',
	    items:[olPnlComp,
	    {
			// column layout with 2 columns
			 layout:'column'
	
			// defaults for columns
			,bodyStyle:estilo
			,defaults:	{
			columnWidth:0.32
			,layout:'form'
			,border:false
			,xtype:'panel'
			,labelWidth:71      //aqui pongo q tan cerca deben estar los labels
			,bodyStyle:'background-color: '+color
		}
	    ,items:[
			{//columnas
				columnWidth:0.25,
				defaults:{anchor:'100%'}
				,items:[olTipoComp]//primera columna priemra fila
			},{	    
				columnWidth:0.03,
				defaults:{anchor:'100%'}
				,items:[olNumComp]//segunda columna priemra fila
			},{
				columnWidth:0.05,
				defaults:{anchor:'100%'}
				,items:[olRegNumero]// tercera columna priemra fila
			},{
				columnWidth:0.05,
				defaults:{anchor:'100%'}
				,items:[]// cuarta columna priemra fila
			},{
				columnWidth:0.2,
				defaults:{anchor:'100%'}
				,items:[olFecTrans]// quinta columna priemra fila
			},{
				columnWidth:0.2,
				defaults:{anchor:'100%'}
				,items:[olFecContab]// sexta columna priemra fila
			},{
				columnWidth:0.2,
				defaults:{anchor:'100%'}
				,items:[olFecVencim]// septima columna priemra fila
			}
	    ]
	},{
		// column layout with 2 columns
		layout:'column'
		,bodyStyle:estilo
		,defaults:	{
			 columnWidth:0.32
			,layout:'form'
			,border:false
			,xtype:'panel',
			labelWidth:71      //aqui pongo q tan cerca deben estar los labels DE LOS TEXT
		}
	    ,items:[
			{//columnas
				columnWidth:0.25,
				defaults:{anchor:'100%'}
				,items:[olEmisor]//primera columna segunda fila
				//,bodyStyle:'padding:0 50px 0 0'
				,bodyStyle:'background-color: '+color
			},{	    
				
				columnWidth:0.43,
				defaults:{anchor:'100%'}
				,items:[olCodReceptor]//tercera columna segunda fila
				,bodyStyle:'background-color: '+color+';margin-left: 91px'
			},{
				columnWidth:0.20,
				defaults:{anchor:'100%'}
				,items:[olReceptor]// cuarta columna segunda fila
				 ,bodyStyle:'background-color: '+color
			}
		]
	},{
		layout:'column'
		,bodyStyle:estilo
		,defaults:{
			columnWidth:0.5
		       ,layout:'form'
		       ,labelWidth:71
		       ,border:false
		       ,xtype:'panel'
		       ,bodyStyle:'background-color: '+color
	       }
	       ,items:[
			{
			    columnWidth:0.48,
			    defaults:{anchor:'100%'}
			    ,items:[olConcepto]
			},
			{
			    columnWidth:0.40
			    ,labelWidth:55,
			    defaults:{anchor:'45%'}
			    ,items:[olValor]// quinta columna segunda fila
			    ,bodyStyle:'background-color: '+color+';margin-left: 84px'
			}	
	       ]
	    },olPanel
    ],buttons:[
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
	id: "frmComprobWin",
		items:frmComprob
	});
	win.show();

    }
}

gaHidden = new Array();

function fGrabar(){
    //debugger;
    if (!Ext.getCmp("frmComprob").getForm().isDirty()) {
	    Ext.Msg.alert('AVISO', 'Los datos no han cambiado. <br>No tiene sentido Grabar');
	    return false;
    }
    if (!Ext.getCmp("frmComprob").getForm().isValid()) {
	    Ext.Msg.alert('ATENCION!!', 'Hay Información incompleta o inválida');
	    return false;
    }
    var olModif = Ext.getCmp("frmComprob").getForm().items.items.findAll(function(olField){return olField.isDirty()});
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
    ilId  = Ext.getCmp("frmComprob").findById('com_RegNumero').getValue();   //usa un valor ya asignado
    if  (ilId > 0) {
	    oParams.cnt_ID = ilId;
	    var slAction='UPD';
    }	else var slAction='ADD';
    var slAction='ADD'; //solo por prueba quitar despues
    var slParams  = Ext.urlEncode(oParams);
    slParams += "&" +  Ext.urlEncode(gaHidden);
    Ext.getCmp("frmComprob").disable();
    Ext.Msg.alert('AVISO', 'Grabar 22');
    fGrabarRegistro(Ext.getCmp("frmComprob"), slAction, oParams, slParams);
    
    Ext.getCmp("frmComprob").enable();
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
  oParams.pTabla = 'conComprobantes';
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
          //Ext.getCmp("frmComprob").findById('tar_NumTarja').setValue(responseData.lastId);
          //fCargaReg(Ext.getCmp("frmComprob").findById('tar_NumTarja').getValue()); //solo por prueba se quito
          break;
        case "UPD":
          slMens = 'Registro Actualizado';
          //fCargaReg(Ext.getCmp("frmComprob").findById('tar_NumTarja').getValue());//solo por prueba se quito
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