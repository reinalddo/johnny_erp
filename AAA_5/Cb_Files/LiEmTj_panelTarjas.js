/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Gina Franco
 *  @date       13/Abr/09
 *  *****************************************************************************************************************
 *  *****************************************************************************************************************
 *  *****************************************************************************************************************
 *  ********************************* NO TE OLVIDES DE DOCUMENTAR EL CODIGO.!!!!!!!!!!!!!!!!!1
 *  @rev	fah 29/04/09	A�adir propiedad tabibdex a los campos del formulario para darles secuencialidad
*/
Ext.ns("li.em");
Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
    Ext.QuickTips.init();
    olDet=Ext.get('divDet');
    Validaciones = [];
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

var olSemana = {
        xtype:'numberfield'
        ,fieldLabel: 'Semana'
        ,name: 'Sem'
        ,id: 'Sem'
		,allowBlank:false
		//,readOnly: true
		//,visible:false
		//,hidden:true
		//,hideLabel:true
		,width:80
    };
//calendario fecha de corte
    var dr = new Ext.FormPanel({
    labelWidth: 90,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: false,
    width: 250/*,
	defaults: {width: 100},
	defaultType: 'datefield',
	items: [{
	      fieldLabel: 'Fecha de Corte',
	      name: 'txtFecha',
	      id: 'txtFecha',
	      value: new Date().format('d-M-y'),
	      format:'d-M-y',
	      endDateField: 'enddt' // id of the end date field
	}]*/
      });
    var slWidth="width:99%; text-align:'left'";
    dr.add({xtype:	'button',
	id:     'btnCxc',
	cls:	 'boton-menu',
	tooltip: 'Ingreso de Tarjas',
	text:    'Ingreso',
	disabled: add,
	style:   slWidth ,
	handler: VerMantTarja/*function(){
                    var slUrl = "LiEmTj_Tarjamant.php";
                    addTab({id:'tbTarja', title:'Tarjas', url:slUrl});
                    //var w = Ext.getCmp('pnlIzq');
					//w.collapsed ? w.expand() : w.collapse();
	    }*/
    });
    dr.add(
    		{
                        xtype:'fieldset', title: 'Consulta de Tarjas', autoHeight:true, defaultType: 'radio',
                        collapsible: true, width: 220,
    		    		frame:false,
                        items: [olSemana,{xtype:	'button',
                        	id:     'btnCxp',
                        	cls:	 'boton-menu',
                        	tooltip: 'Consulta de Tarjas',
                        	text:    'Consulta',
                        	disabled: upd,
                        	style:   slWidth ,
                        	handler: function(){
                        	    var Sem = Ext.getCmp("Sem").getValue();
                        	    if(!Sem)
                        	    	alert("Debe escribir una Semana para la busqueda");
                        	    else
                        	    {	
	                        	    var slUrl = "LiEmTj_Tarjacons.php?init=1&pTipo=P&pPagina=0&Sem="+Sem//&pCierre=2009-04-13";
	                        	    addTab({id:'tab1', title:'Consulta', url:slUrl, width:450});
                        	    }
                        	    //var w = Ext.getCmp('pnlIzq');
                        	    //w.collapsed ? w.expand() : w.collapse();
                        	    }
                            }
    					]
            }
    		);
    dr.render(document.body, 'divIzq01');
		new Ext.Button({
        text: 'Reporte 1',
        handler: addTab
        
        //iconCls:'new-tab'
		}).render(document.body, 'divIzq03');
		
		new Ext.Button({
        text: 'Reporte 2',
        handler: addTab
      
        //iconCls:'new-tab'
		}).render(document.body, 'divIzq03');
		new Ext.Button({
		
        text: 'Reporte 3',
        handler: addTab
       
        //iconCls:'new-tab'
		}).render(document.body, 'divIzq03');


}   
)//on REady



/*
*	Agregador de componentes al tab-panel
*/


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
    /*
    zz=new Ext.Panel({
            xtype: "formpanel",
            //baseClass: "x-form",
            //extraCls: "x-form",
            //renderTo: document.getElementById("formT"),
            //labelAlign: 'top',
            labelWidth:100,
            layout:"form",
            id:"fmFormTarja",
            frame:true,
            title: 'Mantenimiento de Tarjas',
            bodyStyle:'padding:5px 5px 0',
            width: 600,
            //collapsible: true,
            //split:true,
            items: [{ //espacio para grid
                xtype:'textfield',
                id:'bio',
                fieldLabel:'Biography',
                height:20,
                width:50
                ,anchor:'98%'
                ,tabindex:1
		}],
    
            buttons: [{
                text: 'Guardar'
            },{
                text: 'Cancelar'
            }]
        });
    tabs_c.add(zz);
    Ext.getCmp("pnlCen").doLayout();*/
  }
  
var global_printer = null;  // it has to be on the index page or the generator page  always
function printmygridGO(obj){  global_printer.printGrid(obj);	} 
function printmygridGOcustom(obj){ global_printer.printCustom(obj);	}  	
function basic_printGrid(){
		global_printer = new Ext.grid.XPrinter({
			grid:grid1,  // grid object 
			pathPrinter:'../LibJs/ext/ux/printer',  	 // relative to where the Printer folder resides  
			//logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
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
    dsCmbVapor = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBase,
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
    
    /*Para Consultar empresas*/      
    dsCmbEmpresa = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_empresa'}
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
    
    /*Para Consultar Grupo*/   
    dsCmbGrupo = 	new Ext.data.Store({
        proxy: 		new Ext.data.HttpProxy({
                url: '../Ge_Files/GeGeGe_queryToXml.php',
                metod: 'POST'//,
	    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
        }),
        reader: 	rdComboBaseGeneral,
        baseParams: {id : 'LiEmTj_grupo'}
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
    
        /*Para Consultar paletizado*/      
    dsCmbPaletizado = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_Paletizado'}
    });    
    slDateFmt  ='d-m-y';
    slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d';
    
    
    var olEmpresaCmb = new Ext.form.ComboBox({
			fieldLabel:'Empresa',
			id:'txt_Empresa',
			name:'txt_Empresa',
			//width:150,
			store: dsCmbEmpresa,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'empresa',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 1,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250 
		    });
    
    var olEmbarque = new Ext.form.ComboBox({
			fieldLabel:'Embarque',
			id:'txt_Embarque',
			name:'txt_Embarque',
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
			listWidth:      350
			,tabIndex:1000
			,listClass: 'x-combo-list-small'
			,listeners: {'select': function (combo,record){
					frmMant.findById("tac_Semana").setValue(record.data.semana);
				    }}
		    });
    
       var olPaletizado = new Ext.form.ComboBox({
			fieldLabel:'Paletizado',
			id:'txt_Paletizado',
			name:'txt_Paletizado',
			width:150,
			store: dsCmbPaletizado,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_Paletizado',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     true,
			listWidth:      350
			,tabIndex:1000
			,value:1
			,listClass: 'x-combo-list-small'
		    });

    
    var olAccion = {
                        xtype:'textfield'
                        ,fieldLabel: 'Accion'
                        ,name: 'accion'
                        ,id: 'accion'
			//,visible:false
			,hidden:true
			,hideLabel:true
			,value:'ADD'
                    };
    var olEmpresa = {
                        xtype:'textfield'
                        ,fieldLabel: 'cod empresa'
                        ,name: 'tac_CodEmpresa'
                        ,id: 'tac_CodEmpresa'
			//,visible:false
			,hidden:true
			,hideLabel:true
			,value:0
                    };

    var olCorte = {
                        xtype:'numberfield'
                        ,fieldLabel: 'Corte'
                        ,name: 'tac_Corte'
                        ,id: 'tac_Corte'
			//,visible:false
			//,hidden:true
			//,hideLabel:true
			,tabIndex:30
		    ,listeners: {'change' : function (field, newValue, oldValue) {
								fCargarValidador();
							}
						}
             };
		    
    var olNumTarja = {
                        xtype:'numberfield'
                        ,fieldLabel: 'Num Tarja'
                        ,name: 'tar_NumTarja'
                        ,id: 'tar_NumTarja'
                        ,allowBlank:false
                        ,tabIndex:1000
                        ,listeners: {'change' : function (field, newValue, oldValue) {
					// how do I get access to this record here?
					    olDat = Ext.Ajax.request({
						url: 'LiEmTj_Tarjavalidacion'
						,callback: function(pOpt, pStat, pResp){
						    if (true == pStat){
							 olRsp = eval("(" + pResp.responseText + ")");
							if (olRsp.info.tarja == 0){
							    //return true;
							    Ext.getCmp("frmMant").findById("tar_NumTarja").clearInvalid();
							    Ext.getCmp("frmMant").getForm().clearInvalid();
							    Ext.getCmp("btnAdd").enable();
							    Ext.getCmp("btnGrabar").enable();
							}else{
							    Ext.Msg.alert('AVISO', 'Numero de Tarja ya fue ingresada en semana '+olRsp.info.tac_Semana);
							    Ext.getCmp("frmMant").findById("tar_NumTarja").markInvalid();
							    Ext.getCmp("frmMant").getForm().markInvalid();
							    Ext.getCmp("btnGrabar").disable();
							}				    
						    }
						}
						,params: {numTarja: Ext.getCmp("frmMant").findById("tar_NumTarja").getValue(),semana: Ext.getCmp("frmMant").findById("tac_Semana").getValue()}
					    });	
					}
				    }

                    };
     var olFecha = {
                    xtype:'datefield',
                    fieldLabel: 'Fecha',
                    name: 'tac_Fecha',
                    id: 'tac_Fecha',
		    allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
		    ,tabIndex:1001
                };   
    var olAnio = {
                        xtype:'numberfield',
                        fieldLabel: 'Anio',
                        name: 'txt_anio',
                        id: 'txt_anio'
			//allowBlank:false
			//,tabindex:1                        
                    };
    var olSemana = {
                    xtype:'numberfield',
                    fieldLabel: 'Semana',
                    name: 'tac_Semana',
                    id: 'tac_Semana',
		    allowBlank:false
		    ,tabIndex:1002
		    ,listeners: {'change' : function (field, newValue, oldValue) {
    							fValidarSemana();
							}
    					}
                };
    var olHoraInicio = {
                    xtype:'timefield',
                    fieldLabel: 'Hora Inicio',
                    name: 'tac_Hora',
                    id: 'tac_Hora'
		    ,format:'H:i'//'h:i a'
		    ,minValue: '00:00'
		    ,maxValue: '23:59'
		    ,maxLength:5
		    ,tabIndex:1003
                };
    var olHoraCierre = {
                    xtype:'timefield'
                    ,fieldLabel: 'Hora Cierre'
                    ,name: 'tac_HoraFin'
                    ,id: 'tac_HoraFin'
		    ,format:'H:i'//'h:i a'
		    ,minValue: '00:00'
		    ,maxValue: '23:59'
		    ,maxLength:5
		    ,tabIndex:1004
                };

    var olZona = new Ext.form.ComboBox({
			fieldLabel:'Zona',
			id:'txt_zona',
			name:'txt_zona',
			//width:150,
			store: dsCmbZona,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_Zona',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 1,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250 
			,tabIndex:1005
		    });
    var olPtoEmbarque = new Ext.form.ComboBox({
			fieldLabel:'Pto.Embarque',
			id:'txt_PtoEmbarque',
			name:'txt_PtoEmbarque',
			width:150,
			store: dsCmbPtoEmbarque,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_PueRecepcion',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250
			,tabIndex:1006
			//,tabindex:4
			/*,listeners: {'select': function (combo,record){
					win.findById("txt_semana").setValue(record.data.semana);
				    }}*/
		    });
    var olBodega = {
                        xtype:'textfield',
                        fieldLabel: 'Bodega',
                        name: 'tac_Bodega',
                        id: 'tac_Bodega'
			,tabIndex:1009
                    };
    var olPiso = {
                        xtype:'textfield',
                        fieldLabel: 'Piso',
                        name: 'tac_Piso',
                        id: 'tac_Piso'
			,tabIndex:1010
                    };
    var olContenedor = {
                        xtype:'textfield',
                        fieldLabel: 'Contenedor',
                        name: 'tac_Contenedor',
                        id: 'tac_Contenedor'
			,tabIndex:1011
                    };
    var olSello =  {
                        xtype:'textfield',
                        fieldLabel: 'Sello',
                        name: 'tac_Sello',
                        id: 'tac_Sello'
			,tabIndex:1012
                    };
    var olProductor = new Ext.form.ComboBox({
			fieldLabel:'Productor/Grupo',
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
			listWidth:      250 
			,tabIndex:1013		
		    });
    var olHacienda = {
                        xtype:'combo',
                        fieldLabel: 'Hacienda',
                        name: 'tac_UniProduccion',
                        id: 'tac_UniProduccion'
			,tabIndex:1014		
                    };
    var olTransporte = {
                        xtype:'textfield',
                        fieldLabel: 'Transporte',
                        name: 'tac_Transporte',
                        id: 'tac_Transporte'
			,tabIndex:1015
                    };
    var olTransportista = {
                        xtype:'textfield',
                        fieldLabel: 'Transportista',
                        name: 'tac_RefTranspor',
                        id: 'tac_RefTranspor'
			,tabIndex:1016
                    };
    var olCalidad = {
                        xtype:'textfield',
                        fieldLabel: '%Calidad',
                        name: 'tac_ResCalidad',
                        id: 'tac_ResCalidad'
			,tabIndex:1017
                    };
    var olObservacion = {
                        xtype:'textfield',
                        fieldLabel: 'Observacion',
                        name: 'tac_Observaciones',
                        id: 'tac_Observaciones'
			,tabIndex:1018
                    };
    var olPreEval = {
                        xtype:'checkbox'
                        ,fieldLabel: 'Pre-Evaluacion'
                        ,name: 'tac_Evaluada'
                        ,id: 'tac_Evaluada'
			,tabIndex:1019
			//,labelWidth:100
                    };
    var olCodEval = {
                        xtype:'textfield',
                        fieldLabel: 'Cod Evaluador',
                        name: 'tac_CodEvaluador',
                        id: 'tac_CodEvaluador'
			,tabIndex:1020
                    };
    var olGrupoLiq = new Ext.form.ComboBox({
		fieldLabel:'Grupo Liq.',
		id:'txt_GrupLiquidacion',
		name:'txt_GrupLiquidacion',
		width:150,
		store: dsCmbGrupo,
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'tac_GrupLiquidacion',
		selectOnFocus:	true,
		typeAhead: 		true,
		mode: 'remote',
		minChars: 3,
		triggerAction: 	'all',
		forceSelection: true,
		emptyText:'',
		allowBlank:     false,
		listWidth:      250 
		,tabIndex:1013		
	    });
    
    var olNumLiq = {
                        xtype:'textfield'
                        ,fieldLabel: 'Num Liq.'
                        ,name: 'tac_NumLiquid'
                        ,id: 'tac_NumLiquid'
			,readOnly:true
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
			listWidth:      250 
			,tabIndex:'12'		
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
    
    
    

    var olDetalle = new Ext.grid.EditorGridPanel({
                      labelWidth: 200,
                      labelAlign: 'right',
                      buttonAlign: 'left',
                      width:700,
                      height: 150,
                      bodyStyle: "padding: 0px;",
                      enableDragDrop: true,
                      autoExpandColumn : 'opts',
                      clicksToEdit: 1,
                      stripeRows: true,
                      ds: new Ext.data.SimpleStore({
                            fields: [
                              'cola',
                              'colb',
                              'colc',
                              'cold',
                              'options'
                            ],
                            data :  [
                                [false, '1111111', 1111, 0, ''],
                                [true, '22222', 2222, 0, ''],
                                [false, '33333333', 3333, 0, '']
                            ]
                      }),
                      columns: 
                      [
                        {
                          header: "first",width: 40,fixed: true,
                          editor: new Ext.form.TextField({})
                        },
                        {header: "second",fixed: true,align: 'right',width: 100,
                          editor: new Ext.form.TextField()
                        },
                        {header: "third",fixed: true,width: 60,
                          editor: new Ext.form.TextField()
                        },
                        {header: "f",fixed: true,width: 60,
                          editor: new Ext.form.TextField()
                        },
                        {header: "fv",id:'opts',fixed: false,
                          editor: new Ext.form.TextField()
                        }
                      ],
                      tbar: [{
                        text: 'add row',
                        handler : function(){
                            //do something
                        }
                      }]/*,
                      buttons:
                      [
                        "ok"
                      ]*/
    });

    
    
    //if(!win){
    //debugger;
    
    var estilo = 'background-color: #E7E7E7; font-size:4px; border-style: none; ';
    var pad = 'padding-left:10px';
    if(!Ext.getCmp("frmMant")){
	//debugger;
	
	
	//the form
	var frmMant = new Ext.form.FormPanel({
	    //utl:'view/userContacts.cfm',
	    bodyStyle:estilo//'padding:5px',
	    ,width: 780
	    //defaults: {width: 230},
	    ,baseCls: 'x-small-editor'
	    ,border:false
	    ,id:'frmMant'
	    ,items:[
	    {
		    // column layout with 2 columns
		     layout:'column'
		    ,bodyStyle: estilo
		    // defaults for columns
		    ,defaults:{
			     columnWidth:0.32
			    ,layout:'form'
			    ,border:false
			    ,xtype:'panel'
			    ,labelWidth:70
			    ,bodyStyle: estilo+pad
		    }
		    ,items:[{
			    // left column
			    // defaults for fields
			    columnWidth:0.36,
			    defaults:{anchor:'97%'}
			    ,items:[olAccion, olEmpresa, olEmbarque, olSemana,olHoraCierre, olBodega,olContenedor,olPaletizado]
		    },{
			    // right column
			    // defaults for fields
			    labelWidth:80
			    ,defaults:{anchor:'97%'}
			    ,items:[olNumTarja, olCorte, olZona,olPtoEmbarque, olPiso]
		    },{
			defaults:{anchor:'97%'}
			,items:[olFecha,olHoraInicio,olGrupoLiq,olNumLiq,olSello]
		      }
		    ]
	    },{
		    // column layout with 2 columns
		     layout:'column'
		    ,bodyStyle: estilo//'background-color: #DBDBDB; font-size:6px;'
		    // defaults for columns
		    ,defaults:{
			     columnWidth:0.30
			    ,layout:'form'
			    ,border:false
			    ,xtype:'panel'
			    ,labelWidth:90
			    ,bodyStyle: estilo+pad
		    }
		    ,items:[{
			    // left column
			    // defaults for fields
			    columnWidth:0.38,
			    defaults:{anchor:'97%'}
			    ,items:[olProductor, olTransporte, olPreEval]
		    },{
			    // right column
			    // defaults for fields
			    columnWidth:0.32
			    ,labelWidth:70
			    ,defaults:{anchor:'97%'}
			    ,items:[olHacienda, olTransportista, olCodEval]
		    },{
			labelWidth:55
			,defaults:{anchor:'97%'}
			,items:[olCartonera, olCalidad, olEstado]
		      }
		    ]
	    },{
		layout:'column'
		,bodyStyle: estilo//'background-color: #DBDBDB; font-size:6px;'
		,defaults:{
			columnWidth:0.5
		       ,layout:'form'
		       ,border:false
		       ,xtype:'panel'
		       ,labelWidth:70
		       ,bodyStyle: estilo+pad
	       }
	       ,items:[
		    {columnWidth:0.6, defaults:{anchor:'97%'/*,bodyStyle:'background-color: #DBDBDB; '*/}
		    ,items:[olObservacion]},
		    {columnWidth:0.4, defaults:{anchor:'97%'}
		    ,items:[
			{
			    layout:'column'
			    ,bodyStyle: estilo
			    ,labelWidth:80
			    ,defaults:{
				    columnWidth:0.5
				   ,layout:'form'
				   ,border:false
				   ,xtype:'panel'
				   //,bodyStyle:'padding:0 18px 0 0'
				   ,bodyStyle: estilo
			   }
			   ,items:[
				{columnWidth:0.6, defaults:{anchor:'97%'}
				,items:[olDigitado]},
				{columnWidth:0.4, defaults:{anchor:'97%'}
				,items:[olFechaReg]}
			   ]
			}
		    ]}
	       ]
	    }
	    ],
	    buttons:[
		{
		    text:'Nuevo'
		    ,iconCls: 'iconNuevo'
		    ,disabled:add
		    ,id:'btnNuevo'
		    ,handler:function(){
					Ext.getCmp("btnGrabar").enable();
					Ext.getCmp("frmMant").getForm().reset();
					Ext.getCmp("frmMantWin").setTitle("Nueva Tarja");
					//li.em.detgrid.getStore().load({init:22, params:{meta:false, tac_CodEmpresa:0, tad_NumTarja:-999}});
					li.em.detgrid.getStore().load({init:22, params:{meta:false, tac_CodEmpresa:0, tad_NumTarja:-999}});
					li.em.detgrid.getStore().removeAll();
		}
		},{
		    text:'Guardar',
		    type:'submit',
		    id:'btnGrabar',
		    handler:fGrabar
		    ,disabled:upd
		    ,iconCls: 'iconGrabar'
		},{
		    text:'Eliminar',
		    type:'submit',
		    id:'btnEliminar',
		    handler:fEliminarTodo
		    ,disabled:del
		    ,iconCls: 'iconBorrar'
		},
		{
		    text:'Salir'
		    ,iconCls: 'iconSalir'
		    ,handler:function(){
			win.close();
		    }
		}
	    ]
	});

        var win = new Ext.Window({
            title:'Mantenimiento de Tarjas',
            layout:'fit',
	    pageX:250,
	    pageY:10,
            width:790,
            height:520,
	    id: "frmMantWin",
            items:frmMant
        });
        win.show();
	
	
	
	if (!Ext.getCmp("grdTarjaDet")){
	  frmMant.add({
		 id: 'grdTarjaDet',
		 title: "",
		 layout:'fit',
		 closable: true,
		 collapsible:true,
		 autoLoad:{url:'LiEmTj_Tarjadetalle.php?',		// Objeto a cargar
		   params:{
		   init:1
		   ,pPagina:0
		   ,pObj: 'grdTarjaDet'
		 }
		 ,scripts: true, method: 'POST'}
	  })
	}
	else {
	   var ilReg = Ext.getCmp("tar_NumTarja").getValue();
	   li.em.detgrid.getStore().load({init:1, params:{tad_codEmpresa:0, tad_numTarja:ilReg, meta:false, zzz:666}});
	}   
	 frmMant.doLayout()
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
	    Ext.Msg.alert('ATENCION!!', 'Hay Informacion incompleta o invalida');
	    return false;
    }
    var olModif = Ext.getCmp("frmMant").getForm().items.items;//.findAll(function(olField){return olField.isDirty()});
    //Ext.Msg.alert('AVISO', 'Grabar');
    
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
    //debugger;
    ilId  = Ext.getCmp("frmMant").findById('tar_NumTarja').getValue();   //usa un valor ya asignado
    /*if  (ilId > 0) {
	    oParams.cnt_ID = ilId;
	    var slAction='UPD';
    }	else var slAction='ADD';*/
    ilAcc  = Ext.getCmp("frmMant").findById('accion').getValue();   //usa un valor ya asignado
    if  (ilAcc == "UPD") {
	    oParams.cnt_ID = ilId;
	    var slAction='UPD';
    }	else var slAction='ADD';
    var slParams  = Ext.urlEncode(oParams);
    slParams += "&" +  Ext.urlEncode(gaHidden);
    Ext.getCmp("frmMant").disable();
    //Ext.Msg.alert('AVISO', 'Grabar 22');
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

/************************************/
/* Funcion para cargar los
 * dataos de validacion
 * 29/03/10			                */
/************************************/

function fCargarValidador()
{
    var ilSeman= Ext.getCmp("frmMant").form.findField("tac_Semana").getValue();
    var ilCorte = Ext.getCmp("frmMant").form.findField("tac_Corte").getValue();
    var olDat = Ext.Ajax.request({
		url: 'LiEmTj_CamposValidadores'
		,callback: function(pOpt, pStat, pResp){
		    if (true == pStat){
			 olRsp = eval("(" + pResp.responseText + ")");
			if (olRsp.info.length == 0){
			    alert('No hay datos para comparar');
			    return false;
			}else{
				Validaciones['tad_Calidad']=[];
				Validaciones['tad_Calidad']['max']=olRsp.info.cco_MaxCalidad;
				Validaciones['tad_Calidad']['min']=olRsp.info.cco_MinCalidad;
				Validaciones['tad_Peso']=[];
				Validaciones['tad_Peso']['max']=olRsp.info.cco_MaxPeso;
				Validaciones['tad_Peso']['min']=olRsp.info.cco_MinPeso;
				Validaciones["tad_Largo"]=[];
				Validaciones["tad_Largo"]['max']=olRsp.info.cco_MaxLargo;
				Validaciones["tad_Largo"]['min']=olRsp.info.cco_MinLargo;
				Validaciones["tad_NumDedos"]=[];
				Validaciones["tad_NumDedos"]['max']=olRsp.info.cco_MaxDedos;
				Validaciones["tad_NumDedos"]['min']=olRsp.info.cco_MinDedos;
				Validaciones["tad_ClusCaja"]=[];
				Validaciones["tad_ClusCaja"]['max']=olRsp.info.cco_MaxClusters;
				Validaciones["tad_ClusCaja"]['min']=olRsp.info.cco_MinClusters;
			    return;
			}				    
		    }
		}
		,params: {pSem: ilSeman,
				  pCor: ilCorte,
			      pOpc: 1
			}
	    });
}




function fGrabarRegistro(fmForm, slAction, oParams, slParams){
    //debugger;
  oParams.pTabla = 'liqtarjacabec';
  Ext.Ajax.request({
    waitMsg: 'GRABANDO...',
    url:	'../Ge_Files/GeGeGe_generic.crud.php?pAction=' + slAction, //-------------    +'&' + slParams,
    method: 'POST',
    params: oParams,
    success: function(response,options){
      var responseData = Ext.util.JSON.decode(response.responseText);
      fGrabar1();
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
      if (false == responseData.success){
	slMens = responseData.message;
      }
      Ext.Msg.alert('AVISO ', slMens);
    }, 
    failure: function(form, e) {
      if (e.failureType == 'server') {
        slMens = 'La operaci�n no se ejecut�. ' + e.result.errors.id + ': ' + e.result.errors.msg;
      } else {
        slMens = 'El comando no se pudo ejecutar en el servidor';
      }
      Ext.Msg.alert('Proceso Fallido', slMens);
    }
  }//end request config
  ); //end request  
}; //end updateDB 

function fEliminarTodo()
{
	ilId  = Ext.getCmp("frmMant").findById('tar_NumTarja').getValue();
	Ext.Ajax.request({
		    waitMsg: 'Eliminando...',
		    url:	'LiEmTj_EliminarTarja',
		    method: 'POST',
		    params: {numTarja: ilId},
		    success: function(response,options){
	           var responseData = Ext.util.JSON.decode(response.responseText);
	           if (false == responseData.success)
		  		        Ext.Msg.alert('ERROR', 'Registro No Elimnado');
		  		else
		  		{
		  		    	Ext.Msg.alert('AVISO ', 'Registro Elimnado');
	           			Ext.getCmp("frmMantWin").close();
		  		}
		    }, 
		    failure: function(form, e) {
		      if (e.failureType == 'server') {
		        slMens = 'La operaci�n no se ejecut�. ' + e.result.errors.id + ': ' + e.result.errors.msg;
		      } else {
		        slMens = 'El comando no se pudo ejecutar en el servidor';
		      }
		      Ext.Msg.alert('Proceso Fallido', slMens);
		    }
		  }//end request config
   ); //end request  
}

function fValidarSemana()
{
    var olDat = Ext.Ajax.request({
		url: 'LiEmTj_CamposValidadores'
		,callback: function(pOpt, pStat, pResp){
		    if (true == pStat){
			 olRsp = eval("(" + pResp.responseText + ")");
					if (olRsp.info.msg == "-"){
						 Ext.Msg.alert('AVISO', 'Error: Periodo Cerrado');
						 upd=true;
						 Ext.getCmp("btnGrabar").disable();
						 Ext.getCmp("btnEliminar").disable();
						 Ext.getCmp("btnAdd").disable();
						 Ext.getCmp("tac_Semana").markInvalid();
						 return false;
					}
					else
					{
					     Ext.getCmp("tac_Semana").clearInvalid();
					     fValidarTarjaLiqui();	
					     return true;
					}				    
		    }
		}
		,params: {pSem: Ext.getCmp("tac_Semana").getValue()
			,pOpc: 2
			}
	    });
}
function fValidarTarjaLiqui()
{
	var NumTarja=Ext.getCmp("tar_NumTarja").getValue();
	if(!NumTarja)
		NumTarja=0;
    var olDat = Ext.Ajax.request({
		url: 'LiEmTj_CamposValidadores'
		,callback: function(pOpt, pStat, pResp){
		    if (true == pStat){
			 olRsp = eval("(" + pResp.responseText + ")");
					if (olRsp.info.contador != 0 ){
						 Ext.Msg.alert('AVISO', 'Error: Tarja Liquidada o en proceso');
						 upd=true;
						 Ext.getCmp("btnGrabar").disable();
						 Ext.getCmp("btnEliminar").disable();
						 Ext.getCmp("btnAdd").disable();
						 return false;
					}
					else
					{
						fCargarValidador();
					     return true;
					}				    
		    }
		}
		,params: {Tarja: NumTarja
			,pOpc: 3
			}
	    });
}
