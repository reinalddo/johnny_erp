/*
 *  Logica asociada al Panel de cada modulo
 *  Funciones para pantalla de mantenimiento de Anexos
 *  @author     Gina Franco
 *  @date       20/Abr/09
 *
 *  @rev	Gina Franco	25/May/2009	Se aumento funcionalidad para dar mantenimiento a autorizaciones
 *  @rev	Gina Franco	26/May/2009	Se agrego combos de cuenta y auxiliar para realizar contabilizacion de anexo.
*/
Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.namespace("app", "app.CoRtTr");
    Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
    Ext.QuickTips.init();
    olDet=Ext.get('divDet');
    var slWidth="width:250px; text-align:'left'";
    
    app.CoRtTr.msgValidaRetencion = "-";
    
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
	tooltip: 'Ingreso de Anexos',
	text:    'Ingreso',
	style:   slWidth ,
	handler: VerMantAnexo
    });
    
    dr.add({xtype:	'button',
	id:     'btnCxp',
	cls:	 'boton-menu',
	tooltip: 'Consulta de Anexos',
	text:    'Consulta',
	style:   slWidth ,
	handler: function(){
	    var slUrl = "CoRtTr_Anexocons.php?init=1&pTipo=P&pPagina=0&pCierre=2009-04-13";
	    addTab({id:'tab1', title:'Consulta', url:slUrl, width:450});
	    //var w = Ext.getCmp('pnlIzq');
	    //w.collapsed ? w.expand() : w.collapse();
	    }
    });
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
			grid: app.CoRtTr.grid1,  // grid object 
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
			showFooter:true  // if the footer is shown on the pinting html 
			,styles:'default' // wich style youre gonna use 
		}); 
		global_printer.prepare(); // prepare the document 
}

function basic_printGrid_exclude(){
		global_printer = new Ext.grid.XPrinter({
			grid: app.CoRtTr.grid1,  // grid object 
			pathPrinter:'./printer',  	 // relative to where the Printer folder resides  
			//logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
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
			showFooter:true  // if the footer is shown on the pinting html 
			//,styles:'default' // wich style youre gonna use 
		}); 
		global_printer.prepare(); // prepare the document 
}


function VerMantAnexo(){
    
    //debugger;
    var frmMantTarWin;// = new Ext.Window({items:[olSemana]});
    var frmMant;
    
    var color = "#E7E7E7"
    var estilo = 'background-color: '+color+'; font-size:4px; border-style: none; ';
    var estilo2 = 'background-color: '+color+'; font-size:12px; border-style: none; ';
    
    var olSustentoCod = {
		xtype:'textfield'
		,fieldLabel: 'Cod. Sustento'
		,name: 'codigosSustento'
		,id: 'codigosSustento'
		,hidden:true
		,hideLabel:true
		,value:"%"
	    };
    
    
    
    app.CoRtTr.rdComboBaseGeneral = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;
    
    /*Para Consultar Embarques*/
    app.CoRtTr.rdComboBaseVapor = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt','txt_sem'] ) ;
    app.CoRtTr.dsCmbVapor = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBaseVapor,
            baseParams: {id : 'CoRtTr_embarlist'}
    });
    
    /*Para Consultar tipos de transaccion*/      
    app.CoRtTr.dsCmbTipoTrans = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBaseGeneral,
            baseParams: {id : 'CoRtTr_tipoTrans'}
    });
    
    /*Para  consultar tipos de sustentos */
    app.CoRtTr.rdComboBaseSust = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt', 'codigos']
		    ) ;
    app.CoRtTr.dsCmbSustento = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBaseSust,
            baseParams: {id : 'CoRtTr_sustento'}
    });

    /*Para  consultar tipos de comprobantes */
    app.CoRtTr.dsCmbTipoComp = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php'
                    ,metod: 'POST'
		    ,extraParams: {pCodigos : Ext.get("codigosSustento")}//, pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBaseGeneral,
            baseParams: {id : 'CoRtTr_tipoComp'}
    });
    
    app.CoRtTr.rdComboBaseProvFact = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt', 'tipo', 'ruc']
				);
    /*Para  consultar proveedores */
    app.CoRtTr.dsCmbProveedor = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBaseProvFact,
            baseParams: {id : 'CoRtTr_proveedor'}
    });
    
    /*Para  consultar proveedores fact */    
    app.CoRtTr.dsCmbProveedorFact = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBaseProvFact,
            baseParams: {id : 'CoRtTr_proveedor'}
    });
    
    
    app.CoRtTr.rdComboBasePorc = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt', 'porc']
			);
    
    
    /*Para  consultar porcentajes de IVA */
    app.CoRtTr.dsCmbPorcIVA = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBasePorc,
            baseParams: {id : 'CoRtTr_porcIVA'}
    });
    
    /*Para  consultar porcentajes de ICE */
    app.CoRtTr.dsCmbPorcICE = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBasePorc,
            baseParams: {id : 'CoRtTr_porcICE'}
    });
    
    /*Para  consultar porcentajes de retencion IVA Bienes */
    app.CoRtTr.dsCmbPorcRetIvaBienes = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBasePorc,
            baseParams: {id : 'CoRtTr_porcRetIvaBienes'}
    });
    
    //Para  consultar porcentajes de retencion IVA Servicios 
    app.CoRtTr.dsCmbPorcRetIvaServ = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBasePorc,
            baseParams: {id : 'CoRtTr_porcRetIvaServ'}
    });
    
    //Para  consultar codigo de retencion
    app.CoRtTr.dsCmbCodRet = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBasePorc,
            baseParams: {id : 'CoRtTr_codRetencion'}
    });
    //Para  consultar codigo de retencion
    app.CoRtTr.dsCmbCodRet2 = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBasePorc,
            baseParams: {id : 'CoRtTr_codRetencion'}
    });
    //Para  consultar codigo de retencion
    app.CoRtTr.dsCmbCodRet3 = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBasePorc,
            baseParams: {id : 'CoRtTr_codRetencion'}
    });
    

    var slDateFmt  ='d-m-y';
    var slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d|dmy|dmY';
    
    
    app.CoRtTr.dsCmbEstados = new Ext.data.SimpleStore({
                 fields: ['id', 'desc']
                ,data: [
                     ['S', 'Si']
                    ,['N', 'No']
                ]
            })
    app.CoRtTr.dsCmbTipos = new Ext.data.SimpleStore({
                 fields: ['id', 'desc']
                ,data: [
                     [1, 'Bienes']
                    ,[2, 'Servicios']
                ]
            })
    /*Ext.apply(Ext.form.VTypes, {
	'estab' : function(v){
	    //validate it
	},
	'ipText' : 'Invalid ip address',
	'ipMask' : /^(\d{1,3})\
    });*/
    
    //////////////////// [ Controles para consultar comprobante de contabilidad ] ////////////////////
    var olCompRegNum = {
                        xtype:'textfield'
                        ,fieldLabel: 'Reg Num'
                        ,name: 'regNum'
                        ,id: 'regNum'
			//,visible:false
			,hidden:true
			,hideLabel:true
			//,width:30
                    };
    var olCompTipo = {
                        xtype:'textfield'
                        ,fieldLabel: 'Tipo Comp'
                        ,name: 'ContTipoComp'
                        ,id: 'ContTipoComp'
			//,visible:false
			,hidden:true
			,hideLabel:true
			//,width:30
                    };
    
    var olID = {
                        xtype:'numberfield'
                        ,fieldLabel: 'ID'
                        ,name: 'ID'
                        ,id: 'ID'
			//,visible:false
			,hidden:true
			,hideLabel:true
			//,width:30
                    };
    var olTipoTransac = new Ext.form.ComboBox({
			fieldLabel:'Tipo Transaccion'
			,id:'txt_tipoTrans'
			,name:'txt_tipoTrans'
			,store: app.CoRtTr.dsCmbTipoTrans
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'tipoTransac'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      250 
			,tabindex:1
			,width:50
			//,itemStyle:'background-color: #D0D0D0'
			//,labelStyle:'background-color: #D0D0D0'
		    });
    var olSustento = new Ext.form.ComboBox({
			fieldLabel:'Cod Sustento'
			,id:'txt_sustento'
			,name:'txt_sustento'
			//width:150,
			,store: app.CoRtTr.dsCmbSustento
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'codSustento'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			//,listWidth:      250 
			,tabindex:2
			,listClass: 'x-combo-list-small'
			,listeners: {'select': function (combo,record){
					frmMant.findById("codigosSustento").setValue(record.data.codigos);
				    }}
		    });
    var olProveedor = new Ext.form.ComboBox({
			fieldLabel:'Proveedor'
			,id:'txtProv'
			,name:'txtProv'
			//width:150,
			,store: app.CoRtTr.dsCmbProveedor
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'codProv'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 2
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			//,listWidth:      250 
			,tabindex:3
			,listWidth:      350 
			,listClass: 'x-combo-list-small'
			,listeners: {'select': function (combo,record){
					//debugger;
					//frmMant.findById("txtProvFact").store.filter('cod',record.data.cod);
					
					/* var comboCity = Ext.getCmp('txtProvFact');        
					comboCity.clearValue();
					comboCity.store.filter('cod', combo.getValue());*/
					
					frmMant.findById("txtProvFact").setValue(record.data.cod);
					frmMant.findById("tpIdProvFact").setValue(record.data.tipo);
					frmMant.findById("txt_rucProv").setValue(record.data.ruc);
					frmMant.findById("txt_rucProvFact").setValue(record.data.ruc);
					fValidaRuc();
				    }}
		    });
    var olRucProv = {
                        xtype:'textfield'
                        ,fieldLabel: 'ID'
                        ,name: 'txt_rucProv'
                        ,id: 'txt_rucProv'
			//,visible:false
			,hidden:true
			,hideLabel:true
			//,width:30
                    };    
    var olRucProvFact = {
                        xtype:'textfield'
                        ,fieldLabel: 'ID'
                        ,name: 'txt_rucProvFact'
                        ,id: 'txt_rucProvFact'
			//,visible:false
			,hidden:true
			,hideLabel:true
			//,width:30
                    };   
    var olProveedorFact = new Ext.form.ComboBox({
			fieldLabel:'Proveedor Fact.'
			,id:'txtProvFact'
			,name:'txtProvFact'
			//width:150,
			,store: app.CoRtTr.dsCmbProveedorFact
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'idProvFact'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 2
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			//,listWidth:      250 ,
			,tabindex:4
			,listWidth:      350 
			,listClass: 'x-combo-list-small'
			,listeners: {'select': function (combo,record){
					frmMant.findById("tpIdProvFact").setValue(record.data.tipo);
					frmMant.findById("txt_rucProvFact").setValue(record.data.ruc);
					fValidaRuc();
				    }}
		    });
    var olTipoProvFact = {
                        xtype:'textfield'
                        ,hideLabel: true
                        ,name: 'tpIdProvFact'
                        ,id: 'tpIdProvFact'
			,readOnly: true
                    };
    var olTipoComp = new Ext.form.ComboBox({
			fieldLabel:'Tipo Comp.'
			,id:'txt_tipoComp'
			,name:'txt_tipoComp'
			//width:150,
			,store: app.CoRtTr.dsCmbTipoComp
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'tipoComprobante'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      480 
			,tabindex:3
			,listeners: {'change': function (combo,record){
					//debugger;
					if ("" != Ext.getCmp("secuencial").getValue()){
					    fConsultaAutorizacionExistente(Ext.getCmp("txtProvFact").getValue(),Ext.getCmp("txt_tipoComp").getValue(),Ext.getCmp("secuencial").getValue(),Ext.getCmp("establecimiento").getValue(),Ext.getCmp("puntoEmision").getValue(),0);
					}
					/*if ("" != Ext.getCmp("autorizacion").getValue() && "" == Ext.getCmp('fechaAut1').getValue()){
					    fMantAutorizacion();
					    fConsultaAutorizacion(Ext.getCmp("autorizacion").getValue(),0);
					}*/
				    }}
		    });
    var olNComp1 = new Ext.form.TextField({
		    fieldLabel: 'N.Comp'
                    ,name: 'establecimiento'
                    ,id: 'establecimiento'
		    ,maxLength:3
		    ,minLength:3
		    ,stripCharsRe: /[^0123456789]/gi
		    ,mask: "###"
		    ,value:"001"
		    ,allowBlank: false
		});		
    var olNComp2 = new Ext.form.TextField({
		    hideLabel: true
                    ,name: 'puntoEmision'
                    ,id: 'puntoEmision'
		    ,maxLength:3
		    ,minLength:3
		    ,stripCharsRe: /[^0123456789]/gi
		    ,value:"001"
		    ,allowBlank: false
		}); 
    var olNComp3 = {
                        xtype:'textfield'
                        ,hideLabel: true
                        ,name: 'secuencial'
                        ,id: 'secuencial'
			,maxLength:7
			,minLength:7
			,stripCharsRe: /[^0123456789]/gi
			,allowBlank: false
			,listeners: {'change': function (text,newValue,oldValue){
					//debugger;
					num = newValue; limite = newValue.length;
					if (limite < 7){
					    while (limite < 7){
						num = '0'+num;
						limite = num.length;
					    }
					    Ext.getCmp("secuencial").setValue(num);
					}
					var olDat = Ext.Ajax.request({
						url: 'CoRtTr_anexoValidaComp'
						,callback: function(pOpt, pStat, pResp){
						    if (true == pStat){
							//debugger;
							 olRsp = eval("(" + pResp.responseText + ")");
							if (olRsp.info.msg == "-"){
							    //return true;
							    Ext.getCmp("secuencial").clearInvalid();
							    Ext.getCmp("frmMant").getForm().clearInvalid();
							    Ext.getCmp("txt_compValido").setValue('S');
							    fConsultaAutorizacionExistente(Ext.getCmp("txtProvFact").getValue(),Ext.getCmp("txt_tipoComp").getValue(),Ext.getCmp("secuencial").getValue(),Ext.getCmp("establecimiento").getValue(),Ext.getCmp("puntoEmision").getValue(),0);
							}else{
							    Ext.Msg.alert('AVISO', 'Numero de Comprobante ya fue utilizado en \n'+olRsp.info.msg);
							    //return false;
							    Ext.getCmp("secuencial").markInvalid();
							    Ext.getCmp("frmMant").getForm().markInvalid();
							    Ext.getCmp("txt_compValido").setValue('N');
							}				    
						    }
						}
						,params: {pTipoComp: Ext.getCmp("txt_tipoComp").getValue()
							,pProvFact: Ext.getCmp("txtProvFact").getValue()
							,pEstab: Ext.getCmp("establecimiento").getValue()
							,pPuntoEmi: Ext.getCmp("puntoEmision").getValue()
							,pSecuencial: Ext.getCmp("secuencial").getValue()
							,pId: Ext.getCmp("ID").getValue()
							,pOpc: 1
							}
					    });	
				    }}
                    };
    var olCompValido = {
                        xtype:'textfield'
                        ,fieldLabel: 'Valido?'
                        ,name: 'txt_compValido'
                        ,id: 'txt_compValido'
			//,visible:false
			,hidden:true
			,hideLabel:true
			//,width:30
                    };
    var olFechaEmi = {
                    xtype:'datefield'
                    ,fieldLabel: 'Fecha Emision'
                    ,name: 'fechaEmision'
                    ,id: 'fechaEmision'
		    ,allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
		    ,value:new Date().format('d-M-y')
		    ,listeners: {'change': function (field, newValue, oldValue){
					//debugger;
					if ("" != newValue){
					    Ext.getCmp("fechaEmiRet1").setValue(newValue);
					}
					/*if ("" != Ext.getCmp("autorizacion").getValue() && "" == Ext.getCmp('fechaAut1').getValue()){
					    fMantAutorizacion();
					    fConsultaAutorizacion(Ext.getCmp("autorizacion").getValue(),0);
					}*/
				    }}
                };
    var olAutorizacion = {
                        xtype:'textfield'
                        ,fieldLabel: 'Autorizacion'
                        ,name: 'autorizacion'
                        ,id: 'autorizacion'
			,allowBlank: false
			,listeners: {'change': function (combo,record){
					//debugger;
					if ("" != Ext.getCmp("autorizacion").getValue()){
					    fConsultaAutorizacion(Ext.getCmp("autorizacion").getValue(),1,Ext.getCmp("txtProvFact").getValue(),Ext.getCmp("txt_tipoComp").getValue(),Ext.getCmp("establecimiento").getValue(),Ext.getCmp("puntoEmision").getValue(),0);
					}
					/*if ("" != Ext.getCmp("autorizacion").getValue() && "" == Ext.getCmp('fechaAut1').getValue()){
					    fMantAutorizacion();
					    fConsultaAutorizacion(Ext.getCmp("autorizacion").getValue(),0);
					}*/
				    }}
                    };
    var olVerAutoriz = /*new Ext.Button(*/{xtype:	'button'
		,id:     'btnConsAut'
		//,cls:	 'boton-menu'
		,tooltip: 'Consulta de Autorizaciones'
		,text:    ''
		//,width:10
		//,style: 'width:10px;'  // slWidth 
		,iconCls: 'iconBuscar'
		,handler: function(){
		    fMantAutorizacion();
		    fConsultaAutorizacion(Ext.getCmp("autorizacion").getValue(),0,Ext.getCmp("txtProvFact").getValue(),Ext.getCmp("txt_tipoComp").getValue(),Ext.getCmp("establecimiento").getValue(),Ext.getCmp("puntoEmision").getValue(),0);
		    }
	    };//);
    var olFechaAut1 = {
                    xtype:'datefield'
                    ,fieldLabel: 'Fecha 1'
                    ,name: 'fechaAut1'
                    ,id: 'fechaAut1'
		    ,allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
		    ,hideLabel : true
		    ,readOnly:true
		    ,disabled:true
                };
    var olFechaAut2 = {
                    xtype:'datefield'
                    ,fieldLabel: 'Fecha 2'
                    ,name: 'fechaAut2'
                    ,id: 'fechaAut2'
		    ,allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
		    ,hideLabel : true
		    ,readOnly:true
		    ,disabled:true
                };
    var olConcepto = {
                        xtype:'textfield',
                        fieldLabel: 'Concepto',
                        name: 'concepto',
                        id: 'concepto'
			,allowBlank:false
                    };
    var olDigitado ={
                        xtype:'textfield',
                        fieldLabel: 'Digitador Por',
			//text: 'Value1',
                        name: 'tac_Usuario',
                        id: 'tac_Usuario'
			,readOnly:true
                    } ;
    var olFechaReg ={
                        xtype:'datefield'
			,fieldLabel: 'Fecha Registro'
                        //hideLabel: true,
			//text: 'Value1',
                        ,name: 'fechaRegistro'
                        ,id: 'fechaRegistro'
			//,readOnly:true
			,allowBlank:false
			,format: slDateFmt
			,altFormats: slDateFmts
			,value:new Date().format('d-M-y')
                    } ;
    
    var olAplica =new Ext.form.ComboBox({
	    
	    store: app.CoRtTr.dsCmbEstados
            ,valueField: 'id'
            ,displayField: 'desc'
            ,triggerAction:'all'
            ,mode: 'local'
            ,forceSelection:true
            ,editable:false
            ,id:'devolIva'
	    ,fieldLabel: 'Aplica Dev IVA?' 
	    ,hiddenName:'devIva'
	});
    olAplica.setValue('N');
    
    var olEmbarque = new Ext.form.ComboBox({
			fieldLabel:'Ref.Operativa',
			id:'txt_Embarque',
			name:'txt_Embarque',
			width:150,
			store: app.CoRtTr.dsCmbVapor,
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
			listWidth:      350 ,
			tabindex:3
			,listClass: 'x-combo-list-small'
			,cls: 'font-size:4px !important;'
			,listeners: {'select': function (combo,record){
					Ext.getCmp("semana").setValue(record.data.txt_sem);
				    }}
		    });
    var olSemana = {
                        xtype:'numberfield'
                        ,fieldLabel: 'Semana'
                        ,name: 'semana'
                        ,id: 'semana'
			,readOnly: true
			//,visible:false
			//,hidden:true
			//,hideLabel:true
			//,width:30
                    };
        
    /* controles para pestaña 'Datos de IVA / ICE'*/
    var olBaseIVA_0 = {
                        xtype:'numberfield'
                        ,name: 'baseImponible'
                        ,id: 'baseImponible'
			,tabIndex:'150'
			,listeners: {'change': function (combo,record){
					//debugger;
					var base_iva = Ext.getCmp("baseImpGrav").getValue();
					var base_iva0 = Ext.getCmp("baseImponible").getValue();
					var totIva = base_iva0;
					if (base_iva != ""){
					    totIva = totIva + base_iva;
					}
					Ext.getCmp("baseImpAir").setValue(totIva );
					
				    }}
                    };
    var olBaseIVA = {
                        xtype:'numberfield'
                        ,name: 'baseImpGrav'
                        ,id: 'baseImpGrav'
			,tabIndex:'151'
			,listeners: {'change': function (combo,record){
					//debugger;
					var base_iva = Ext.getCmp("baseImpGrav").getValue();
					var porc = Ext.getCmp("porc_iva").getValue();
					if (base_iva != "" && porc != ""){
					    var calcula_monto = base_iva * (porc / 100);
					    calcula_monto = fRoundNumber(calcula_monto,2);
					                      
					    Ext.getCmp("montoIva").setValue(calcula_monto);
					    var Bienes = Ext.getCmp("montoIvaBienes").getValue();
					    var Serv = Ext.getCmp("montoIvaServicios").getValue();
					    if ((Bienes == 0 || Bienes == "") && (Serv == 0 || Serv == ""))
						Ext.getCmp("montoIvaBienes").setValue(calcula_monto);
					}
					
					var base_iva0 = Ext.getCmp("baseImponible").getValue();
					var totIva = base_iva;
					if (base_iva0 != ""){
					    totIva = totIva + base_iva0;
					}
					Ext.getCmp("baseImpAir").setValue(totIva );
					//debugger;
					if (0 == base_iva){
					    Ext.getCmp("txt_IVA").setValue(0);
					    Ext.getCmp("porc_iva").setValue(0);
					    Ext.getCmp("montoIva").setValue(0);
					    Ext.getCmp("montoIvaBienes").setValue(0);
					    
					    
					}
					
				    }}
                    };
    var olIvaGrabable = new Ext.form.ComboBox({
			//fieldLabel:'Tipo Comp.'
			id:'txt_IVA'
			//name:'txt_Embarque',
			//width:150,
			,store: app.CoRtTr.dsCmbPorcIVA
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'porcentajeIva'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,tabIndex:'152'
			,listeners: {'select': function (combo,record){
					//debugger;
					Ext.getCmp("porc_iva").setValue(record.data.porc);
					var base_iva = Ext.getCmp("baseImpGrav").getValue();
					var porc = Ext.getCmp("porc_iva").getValue();
					if (base_iva != "" && porc != ""){
					    var calcula_monto = base_iva * (porc / 100);
					                      
					    Ext.getCmp("montoIva").setValue(calcula_monto);
					    Ext.getCmp("montoIvaBienes").setValue(calcula_monto);
					}
					
					
				    }}
		    });
    var olPorcIVA = {
                        xtype:'numberfield'
                        ,name: 'porc_iva'
                        ,id: 'porc_iva'
			,tabIndex:'153'
			,readOnly:true
                    };
    var olMontoIVA = {
                        xtype:'numberfield'
                        ,name: 'montoIva'
                        ,id: 'montoIva'
			,tabIndex:'154'
			,readOnly:true
                    };
    var olBaseICE = {
                        xtype:'numberfield'
                        ,name: 'baseImpIce'
                        ,id: 'baseImpIce'
			,tabIndex:'155'
			,listeners: {'change': function (combo,record){
					//debugger;
					//frmMant.findById("porc_iva").setValue(record.data.porc);
					//frmMant.findById("tabDatos").items.itemAt(0).items.itemAt(0).items.item("porc_ice").setValue(record.data.porc);
					var base_ice = Ext.getCmp("baseImpIce").getValue();
					var porc = Ext.getCmp("porc_ice").getValue();
					if (base_ice!= "" && porc != ""){
					    var calcula_monto = base_ice * (porc / 100);
					                      
					    Ext.getCmp("montoIce").setValue(calcula_monto);
					}
					if (0 == base_ice){
					    Ext.getCmp("txt_ICE").setValue(0);
					    Ext.getCmp("porc_ice").setValue(0);
					    Ext.getCmp("montoIce").setValue(0);
					}
				    }}
                    };
    var olICE = new Ext.form.ComboBox({
			//fieldLabel:'Tipo Comp.'
			id:'txt_ICE'
			//name:'txt_Embarque',
			//width:150,
			,store: app.CoRtTr.dsCmbPorcICE
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'porcentajeIce'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,tabIndex:'156'
			,listeners: {'select': function (combo,record){
					//debugger;
					//frmMant.findById("porc_iva").setValue(record.data.porc);
					Ext.getCmp("porc_ice").setValue(record.data.porc);
					var base_ice = Ext.getCmp("baseImpIce").getValue();
					var porc = Ext.getCmp("porc_ice").getValue();
					if (base_ice != "" && porc != ""){
					    var calcula_monto = base_ice * (porc / 100);
					                      
					    Ext.getCmp("montoIce").setValue(calcula_monto);
					}
				    }}
		    });
    var olPorcICE = {
                        xtype:'numberfield'
                        ,name: 'porc_ice'
                        ,id: 'porc_ice'
			,tabIndex:'157'
			,readOnly:true
                    };
    var olMontoICE = {
                        xtype:'numberfield'
                        ,name: 'montoIce'
                        ,id: 'montoIce'
			,tabIndex:'158'
			,readOnly:true
                    };
		    
    /* controles para pestaña 'Retenciones'*/
    var olBaseRetIVAbienes = {
                        xtype:'numberfield'
                        ,name: 'montoIvaBienes'
                        ,id: 'montoIvaBienes'
			,tabIndex:'200'
			,listeners: {'change': function (combo,record){
					//debugger;
					//frmMant.findById("porc_iva").setValue(record.data.porc);
					//frmMant.findById("tabDatos").items.itemAt(0).items.itemAt(0).items.item("porc_ice").setValue(record.data.porc);
					var base_ret_b = Ext.getCmp("montoIvaBienes").getValue();
					var porc = Ext.getCmp("porc_ret_iva_b").getValue();
					if (base_ret_b != "" && porc != ""){
					    var calcula_monto = base_ret_b * (porc / 100);
					                      
					    Ext.getCmp("valorRetBienes").setValue(calcula_monto);
					}else if (base_ret_b != "" && porc == 0){
						Ext.getCmp("valorRetBienes").setValue(0);
					}
				    }}
			,validator: function(field){
					//debugger;
					var base_ret_s = Ext.getCmp("montoIvaServicios").getValue();
					var base_ret_b = Ext.getCmp("montoIvaBienes").getValue();
					var monto_ivaGra = Ext.getCmp("montoIva").getValue();
					if (monto_ivaGra != ""){
					    var totRet = 0;
					    if (base_ret_b != "")
						totRet = base_ret_b;
					    else{
						Ext.getCmp("valorRetBienes").setValue(0);
						Ext.getCmp("porc_ret_iva_b").setValue(0);
						Ext.getCmp("txt_retIvaBienes").setValue(0);
					    }
					    if (base_ret_s != "")
						totRet = totRet + base_ret_s;
					    else{
						Ext.getCmp("valorRetServicios").setValue(0);
						Ext.getCmp("porc_ret_iva_s").setValue(0);
						Ext.getCmp("txt_RetIvaServ").setValue(0);
					    }
					    if (totRet > monto_ivaGra){
						Ext.MessageBox.alert('Mensaje', 'La Base imponible de Retenciones de IVA (' + totRet + ') no debe ser mayor al monto total de IVA '+monto_ivaGra);
						return false;
					    }else{
						return true;
					    }
					}
					else{
					    if (base_ret_b == 0){
						Ext.getCmp("valorRetBienes").setValue(0);
						Ext.getCmp("porc_ret_iva_b").setValue(0);
						Ext.getCmp("txt_retIvaBienes").setValue(0);
					    }
					    if (base_ret_s == 0){
						Ext.getCmp("valorRetServicios").setValue(0);
						Ext.getCmp("porc_ret_iva_s").setValue(0);
						Ext.getCmp("txt_RetIvaServ").setValue(0);
					    }
					    return true;					
					}
                                    }
                        , validateOnBlur : true 
                    };
    var olRetIVAbienes = new Ext.form.ComboBox({
			//fieldLabel:'Tipo Comp.'
			id:'txt_retIvaBienes'
			//name:'txt_Embarque',
			//width:150,
			,store: app.CoRtTr.dsCmbPorcRetIvaBienes
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'porRetBienes'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,tabIndex:'201'
			,listeners: {'select': function (combo,record){
					//debugger;
					//frmMant.findById("porc_iva").setValue(record.data.porc);
					Ext.getCmp("porc_ret_iva_b").setValue(record.data.porc);
					var base_iva = Ext.getCmp("montoIvaBienes").getValue();
					var porc = Ext.getCmp("porc_ret_iva_b").getValue();
					if (base_iva != "" && porc != ""){
					    var calcula_monto = base_iva * (porc / 100);
					                      
					    Ext.getCmp("valorRetBienes").setValue(calcula_monto);
					}else if (base_iva != "" && porc == 0){
						 Ext.getCmp("valorRetBienes").setValue(0);
					}
					
				    }}
		    });
    var olPorcRetIVAbienes = {
                        xtype:'numberfield'
                        ,name: 'porc_ret_iva_b'
                        ,id: 'porc_ret_iva_b'
			,readOnly:true
                    };
    var olMontoRetIVAbienes = {
                        xtype:'numberfield'
                        ,name: 'valorRetBienes'
                        ,id: 'valorRetBienes'
			,tabIndex:'202'
			,readOnly:true
                    };
    var olBaseRetIVAserv = {
                        xtype:'numberfield'
                        ,name: 'montoIvaServicios'
                        ,id: 'montoIvaServicios'
			,tabIndex:'203'
			,listeners: {'change': function (combo,record){
					//debugger;
					//frmMant.findById("porc_iva").setValue(record.data.porc);
					//frmMant.findById("tabDatos").items.itemAt(0).items.itemAt(0).items.item("porc_ice").setValue(record.data.porc);
					var base_ret_s = Ext.getCmp("montoIvaServicios").getValue();
					var porc = Ext.getCmp("porc_ret_iva_s").getValue();
					if (base_ret_s!= "" && porc != ""){
					    var calcula_monto = base_ret_s * (porc / 100);
					                      
					    Ext.getCmp("valorRetServicios").setValue(calcula_monto);
					}else if (base_ret_s!= "" && porc == 0){
						    Ext.getCmp("valorRetServicios").setValue(0);
					}
				    }}
			,validator: function(field){
					//debugger;
					var base_ret_s = Ext.getCmp("montoIvaServicios").getValue();
					var base_ret_b = Ext.getCmp("montoIvaBienes").getValue();
					var monto_ivaGra = Ext.getCmp("montoIva").getValue();
					if (monto_ivaGra != ""){
					    var totRet = 0;
					    if (base_ret_b != "")
						totRet = base_ret_b;
					    else{
						Ext.getCmp("valorRetBienes").setValue(0);
						Ext.getCmp("porc_ret_iva_b").setValue(0);
						Ext.getCmp("txt_retIvaBienes").setValue(0);
					    }
					    if (base_ret_s != "")
						totRet = totRet + base_ret_s;
					    else{
						Ext.getCmp("valorRetServicios").setValue(0);
						Ext.getCmp("porc_ret_iva_s").setValue(0);
						Ext.getCmp("txt_RetIvaServ").setValue(0);
					    }
					    
					    if (totRet > monto_ivaGra){
						Ext.MessageBox.alert('Mensaje', 'La Base imponible de Retenciones de IVA (' + totRet + ') no debe ser mayor al monto total de IVA '+monto_ivaGra);
						return false;
					    }else{
						return true;
					    }
					}
					else{
					    if (base_ret_b == 0){
						Ext.getCmp("valorRetBienes").setValue(0);
						Ext.getCmp("porc_ret_iva_b").setValue(0);
						Ext.getCmp("txt_retIvaBienes").setValue(0);
					    }
					    if (base_ret_s == 0){
						Ext.getCmp("valorRetServicios").setValue(0);
						Ext.getCmp("porc_ret_iva_s").setValue(0);
						Ext.getCmp("txt_RetIvaServ").setValue(0);
					    }
					    return true;					
					}
                                    }
                        , validateOnBlur : true 
                    };
    var olRetIVAserv = new Ext.form.ComboBox({
			//fieldLabel:'Tipo Comp.'
			id:'txt_RetIvaServ'
			//name:'txt_Embarque',
			//width:150,
			,store: app.CoRtTr.dsCmbPorcRetIvaServ
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'porRetServicios'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,tabIndex:'204'
			,listeners: {'select': function (combo,record){
					//debugger;
					//frmMant.findById("porc_iva").setValue(record.data.porc);
					Ext.getCmp("porc_ret_iva_s").setValue(record.data.porc);
					var base_ice = Ext.getCmp("montoIvaServicios").getValue();
					var porc = Ext.getCmp("porc_ret_iva_s").getValue();
					if (base_ice != "" && porc != ""){
					    var calcula_monto = base_ice * (porc / 100);
					                      
					    Ext.getCmp("valorRetServicios").setValue(calcula_monto);
					}else if (base_ice != "" && porc == 0){
						Ext.getCmp("valorRetServicios").setValue(0);
					}
				    }}
		    });
    var olPorcRetIVAserv = {
                        xtype:'numberfield'
                        ,name: 'porc_ret_iva_s'
                        ,id: 'porc_ret_iva_s'
			,readOnly:true
                    };
    var olMontoRetIVAserv = {
                        xtype:'numberfield'
                        ,name: 'valorRetServicios'
                        ,id: 'valorRetServicios'
			,readOnly:true
                    };
    var olRetComprobante1 = {
                        xtype:'textfield'
			,fieldLabel:'Comp. / Retencion'
                        ,name: 'estabRetencion1'
			,id: 'estabRetencion1'
			//,maxLength:3
			//,minLength:3
			,stripCharsRe: /[^0123456789]/gi
			,mask: "###"
			,value:"001"
			,tabIndex:'205'
                    };
    var olRetComprobante2 = {
                        xtype:'textfield'
			//,fieldLabel:'Comprobante de Retencion'
			,hideLabel:true
                        ,name: 'puntoEmiRetencion1'
                        ,id: 'puntoEmiRetencion1'
			//,maxLength:3
			//,minLength:3
			,stripCharsRe: /[^0123456789]/gi
			,mask: "###"
			,value:"001"
			,tabIndex:'206'
                    };
    var olRetComprobante3 = {
                        xtype:'numberfield'
			//,fieldLabel:'Comprobante de Retencion'
			,hideLabel:true
                        ,name: 'secRetencion1'
                        ,id: 'secRetencion1'
			//,maxLength:7
			//,minLength:7
			,stripCharsRe: /[^0123456789]/gi
			,tabIndex:'207'
			,listeners: {'change': function (text,newValue,oldValue){
			    fValidaRetencion();
			    if ("" != newValue)
				fConsultaAutorizacionExistente(-99,Ext.getCmp("txt_tipoComp").getValue(),Ext.getCmp("secRetencion1").getValue(),Ext.getCmp("estabRetencion1").getValue(),Ext.getCmp("puntoEmiRetencion1").getValue(),1);
			}}
                    };
    var olRetAutorizacion = {
                        xtype:'numberfield'
			,fieldLabel:'Autorizacion'
                        ,name: 'autRetencion1'
                        ,id: 'autRetencion1'
			,labelWidth:10
			,tabIndex:'208'
                    };
    var olRetVerAutoriz = /*new Ext.Button(*/{xtype:	'button'
		,id:     'btnConsAutRet'
		//,cls:	 'boton-menu'
		,tooltip: 'Consulta de Autorizaciones de la Empresa'
		,text:    ''
		//,width:10
		//,style: 'width:10px;'  // slWidth 
		,iconCls: 'iconBuscar'
		,handler: function(){
		    fMantAutorizacion();
		    fConsultaAutorizacion(Ext.getCmp("autRetencion1").getValue(),0,-99,Ext.getCmp("txt_tipoComp").getValue(),Ext.getCmp("estabRetencion1").getValue(),Ext.getCmp("puntoEmiRetencion1").getValue(),1);
		    }
	    };//);
    var olRetFechaAut1 = {
                    xtype:'datefield'
                    ,fieldLabel: 'Fecha 1'
                    ,name: 'retfechaAut1'
                    ,id: 'retfechaAut1'
		    ,allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
		    ,hideLabel : true
		    ,readOnly:true
		    ,disabled:true
                };
    var olRetFechaAut2 = {
                    xtype:'datefield'
                    ,fieldLabel: 'Fecha 2'
                    ,name: 'retfechaAut2'
                    ,id: 'retfechaAut2'
		    ,allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
		    ,hideLabel : true
		    ,readOnly:true
		    ,disabled:true
                };
    var olRetFechaEmi = {
                        xtype:'datefield'
			,fieldLabel:'Fecha Emision'
                        ,name: 'fechaEmiRet1'
			,id: 'fechaEmiRet1'
			,tabIndex:'209'
			,format: slDateFmt
			,altFormats: slDateFmts
			,allowBlank:false
			,value:new Date().format('d-M-y')
                    };
    
    var olRetCodFuente1 = new Ext.form.ComboBox({
			//fieldLabel:'Tipo Comp.'
			id:'txt_codRet1'
			//name:'txt_Embarque',
			//width:150,
			,store: app.CoRtTr.dsCmbCodRet
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'codRetAir'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,tabIndex:'210'
			,listeners: {'select': function (combo,record){
					//debugger;
					Ext.getCmp("porcentajeAir").setValue(record.data.porc);
					var base_ice = Ext.getCmp("baseImpAir").getValue();
					var porc = Ext.getCmp("porcentajeAir").getValue();
					if (base_ice != "" && porc != ""){
					    var calcula_monto = base_ice * (porc / 100);
					                      
					    Ext.getCmp("valRetAir").setValue(calcula_monto);
					}else if (base_ice != "" && porc == 0){
						Ext.getCmp("valRetAir").setValue(0);
					}
					//frmMant.findById("tabDatos").items.itemAt(1).items.itemAt(2).items.item("porcentajeAir").setValue(record.data.porc);
					//var base_ice = frmMant.findById("tabDatos").items.itemAt(1).items.itemAt(2).items.item("baseImpAir").getValue();
					//var porc = frmMant.findById("tabDatos").items.itemAt(1).items.itemAt(2).items.item("porcentajeAir").getValue();
					//if (base_ice != "" && porc != ""){
					    //var calcula_monto = base_ice * (porc / 100);
//					                      
					    //frmMant.findById("tabDatos").items.itemAt(1).items.itemAt(2).items.item("valRetAir").setValue(calcula_monto);
					//}else if (base_ice != "" && porc == 0){
						//frmMant.findById("tabDatos").items.itemAt(1).items.itemAt(2).items.item("valRetAir").setValue(0);
					//}
				    }}
		    });
    var olRetBaseImp1 = {
                        xtype:'numberfield'
			//,fieldLabel:'Fecha Emision'
                        ,name: 'baseImpAir'
                        ,id: 'baseImpAir'
			,tabIndex:'211'
                    };
    var olRetPorc1 = {
                        xtype:'numberfield'
			//,fieldLabel:'Fecha Emision'
                        ,name: 'porcentajeAir'
                        ,id: 'porcentajeAir'
			,readOnly:true
                    };
    var olRetMonto1 = {
                        xtype:'numberfield'
			//,fieldLabel:'Fecha Emision'
                        ,name: 'valRetAir'
                        ,id: 'valRetAir'
			,readOnly:true
                    };
    var olRetCodFuente2 = new Ext.form.ComboBox({
			//fieldLabel:'Tipo Comp.'
			id:'txt_codRet2'
			//name:'txt_Embarque',
			//width:150,
			,store: app.CoRtTr.dsCmbCodRet2
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'codRetAir2'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,tabIndex:'212'
			,listeners: {'select': function (combo,record){
					//debugger;
					Ext.getCmp("porcentajeAir2").setValue(record.data.porc);
					var base_ice = Ext.getCmp("baseImpAir2").getValue();
					var porc = Ext.getCmp("porcentajeAir2").getValue();
					if (base_ice != "" && porc != ""){
					    var calcula_monto = base_ice * (porc / 100);
					                      
					    Ext.getCmp("valRetAir2").setValue(calcula_monto);
					}else if (base_ice != "" && porc == 0){
						Ext.getCmp("valRetAir2").setValue(0);
					}
					
				    }}
		    });
    var olRetBaseImp2 = {
                        xtype:'numberfield'
			//,fieldLabel:'Fecha Emision'
                        ,name: 'baseImpAir2'
                        ,id: 'baseImpAir2'
			,tabIndex:'213'
                    };
    var olRetPorc2 = {
                        xtype:'numberfield'
			//,fieldLabel:'Fecha Emision'
                        ,name: 'porcentajeAir2'
                        ,id: 'porcentajeAir2'
			,readOnly:true
                    };
    var olRetMonto2 = {
                        xtype:'numberfield'
			//,fieldLabel:'Fecha Emision'
                        ,name: 'valRetAir2'
                        ,id: 'valRetAir2'
			,readOnly:true
                    };
    var olRetCodFuente3 = new Ext.form.ComboBox({
			//fieldLabel:'Tipo Comp.'
			id:'txt_codRet3'
			//name:'txt_Embarque',
			//width:150,
			,store: app.CoRtTr.dsCmbCodRet3
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'codRetAir3'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,tabIndex:'214'
			,listeners: {'select': function (combo,record){
					//debugger;
					Ext.getCmp("porcentajeAir3").setValue(record.data.porc);
					var base_ice = Ext.getCmp("baseImpAir3").getValue();
					var porc = Ext.getCmp("porcentajeAir3").getValue();
					if (base_ice != "" && porc != ""){
					    var calcula_monto = base_ice * (porc / 100);
					                      
					    Ext.getCmp("valRetAir3").setValue(calcula_monto);
					}else if (base_ice != "" && porc == 0){
						Ext.getCmp("valRetAir3").setValue(0);
					}
					
					
				    }}
		    });
    var olRetBaseImp3 = {
                        xtype:'numberfield'
			//,fieldLabel:'Fecha Emision'
                        ,name: 'baseImpAir3'
                        ,id: 'baseImpAir3'
			,tabIndex:'215'
                    };
    var olRetPorc3 = {
                        xtype:'numberfield'
			//,fieldLabel:'Fecha Emision'
                        ,name: 'porcentajeAir3'
                        ,id: 'porcentajeAir3'
			,readOnly:true
                    };
    var olRetMonto3 = {
                        xtype:'numberfield'
			//,fieldLabel:'Fecha Emision'
                        ,name: 'valRetAir3'
                        ,id: 'valRetAir3'
			,readOnly:true
                    };
    
    /* campos para pestaña Datos Generales */
    var olCodDocumModif = {
                        xtype:'numberfield'
			,fieldLabel:'Cod.Docum.Modif'
                        ,name: 'docModificado'
                        ,id: 'docModificado'
                    };
    var olFechaEmiModif = {
                        xtype:'datefield'
			,fieldLabel:'Fecha Emi. Modific'
                        ,name: 'fechaEmiModificado'
                        ,id: 'fechaEmiModificado'
			,format: slDateFmt
			,altFormats: slDateFmts
			,allowBlank:false
			,value:new Date().format('d-M-y')
                    };
    var olNCompModif1 = {
                        xtype:'numberfield'
			//,hideLabel:true
			,fieldLabel:'Nro.Compr.Modif'
                        ,name: 'estabModificado'
                        ,id: 'estabModificado'
                    };
    var olNCompModif2 = {
                        xtype:'numberfield'
			,hideLabel:true
                        ,name: 'ptoEmiModificado'
                        ,id: 'ptoEmiModificado'
                    };
    var olNCompModif3 = {
                        xtype:'numberfield'
			,hideLabel:true
                        ,name: 'secModificado'
                        ,id: 'secModificado'
                    };
    var olAutorizModif = {
                        xtype:'numberfield'
			,fieldLabel:'Autotiz.Modif'
                        ,name: 'autModificado'
                        ,id: 'autModificado'
                    };	    
    var olContraPart = {
                        xtype:'numberfield'
			,fieldLabel:'Contr.Part Polit'
                        ,name: 'contratoPartidoPolitico'
                        ,id: 'contratoPartidoPolitico'
                    };
    var olMontoTituloOner = {
                        xtype:'numberfield'
			,fieldLabel:'Monto Titulo Oneroso'
                        ,name: 'montoTituloOneroso'
                        ,id: 'montoTituloOneroso'
                    };
    var olMontoTituloGra = {
                        xtype:'numberfield'
			,fieldLabel:'Monto Titulo Gra'
                        ,name: 'montoTituloGratuito'
                        ,id: 'montoTituloGratuito'
                    };
    var olNumComprobantes = {
                        xtype:'numberfield'
			,fieldLabel:'Num Comprobantes'
                        ,name: 'numeroComprobantes'
                        ,id: 'numeroComprobantes'
                    };
    var olIvaPresuntivo = new Ext.form.ComboBox({
	    
	    store: app.CoRtTr.dsCmbEstados
            ,valueField: 'id'
            ,displayField: 'desc'
            ,triggerAction:'all'
            ,mode: 'local'
            ,forceSelection:true
            ,editable:false
            ,id:'ivaPresuntivo1'
	    ,fieldLabel: 'Iva Presuntivo'
	    ,hiddenName:'ivaPresuntivo'
	    ,width:50
	});
    olIvaPresuntivo.setValue('N');
    
    var olRetPresuntiva = new Ext.form.ComboBox({
	    
	    store: app.CoRtTr.dsCmbEstados
            ,valueField: 'id'
            ,displayField: 'desc'
            ,triggerAction:'all'
            ,mode: 'local'
            ,forceSelection:true
            ,editable:false
            ,id:'retPresuntiva1'
	    ,fieldLabel: 'Ret Presuntiva'
	    ,hiddenName:'retPresuntiva'
	    ,width:50
	});
    olRetPresuntiva.setValue('N');
   
		    
    /* campos para pestaña Import / Export*/   
    var olRefrAduanero1 = {
                        xtype:'textfield'
			//,hideLabel:true
			,fieldLabel:'Refrendo Aduanero'
                        ,name: 'distAduanero'
                        ,id: 'distAduanero'
			,maxLength:3
			,minLength:3
			,stripCharsRe: /[^0123456789]/gi
			,value: '028'
                    };
    var olRefrAduanero2 = {
                        xtype:'numberfield'
			,hideLabel:true
			//,fieldLabel:'Refrendo Aduanero'
			,labelWidth:0
                        ,name: 'anio'
                        ,id: 'anio'
			,value: '2009'
                    };
    var olRefrAduanero3 = {
                        xtype:'numberfield'
			,hideLabel:true
			//,fieldLabel:'Refrendo Aduanero'
                        ,name: 'regimen'
                        ,id: 'regimen'
			,value: '40'
                    };
    var olRefrAduanero4 = {
                        xtype:'numberfield'
			,hideLabel:true
			//,fieldLabel:'Refrendo Aduanero'
                        ,name: 'correlativo'
                        ,id: 'correlativo'
                    };
    var olRefrAduanero5 = {
                        xtype:'numberfield'
			,hideLabel:true
			//,fieldLabel:'Refrendo Aduanero'
                        ,name: 'verificador'
                        ,id: 'verificador'
                    };
    var olImExTipoCompr = {
                        xtype:'numberfield'
			,fieldLabel:'Tipo Comprobante'
                        /*,name: 'cod_docum_modif'
                        ,id: 'cod_docum_modif'*/
                    };
    var olImExTipo = new Ext.form.ComboBox({
	    
	    store: app.CoRtTr.dsCmbTipos
            ,valueField: 'id'
            ,displayField: 'desc'
            ,triggerAction:'all'
            ,mode: 'local'
            ,forceSelection:true
            ,editable:false
            ,id:'tipImpExp1'
	    ,fieldLabel: 'Tipo'
	    ,hiddenName:'tipImpExp'
	    ,width:80
	});
    olImExTipo.setValue(1);
    
    var olImExValor = {
                        xtype:'numberfield'
			,fieldLabel:'Valor FOB / CIF'
                        ,name: 'valorCifFob'
                        ,id: 'valorCifFob'
                    };
    var olImExValorCompr = {
                        xtype:'numberfield'
			,fieldLabel:'Valor de Comprob'
                        ,name: 'valorFobComprobante'
                        ,id: 'valorFobComprobante'
                    };
    var olImExFecEmb = {
                        xtype:'datefield'
			,fieldLabel:'Fecha de Embarque'
                        ,name: 'fechaEmbarque'
                        ,id: 'fechaEmbarque'
			,format: slDateFmt
			,altFormats: slDateFmts
			,allowBlank:false
			,value:new Date().format('d-M-y')
                    };
    var olImExDocEmb = {
                        xtype:'numberfield'
			,fieldLabel:'Docum. Embarque'
                        ,name: 'documEmbarque'
                        ,id: 'documEmbarque'
                    };
		    
    
    var olTab = new Ext.TabPanel({
	id: "tabDatos"
        /*plain:true,
        defaults:{autoScroll: true},*/
	//,activeItem:1
	,border:false
	,height:260
	,width:730

	// this line is necessary for anchoring to work at 
	// lower level containers and for full height of tabs
	//,anchor:'100% 100%'

	// only fields from an active tab are submitted
	// if the following line is not persent
	,deferredRender:false
	//,layoutOnTabChange: true
	//,anchor: '-3'
	//,autoHeight:true
	,bodyStyle:estilo
	,tabIndex:'50'
	// tabs
	,defaults:{
		 layout:'form'
		//,labelWidth:80
		,defaultType:'textfield'
		//,bodyStyle:'padding:5px'
		,bodyStyle:estilo
		// as we use deferredRender:false we mustn't
		// render tabs into display:none containers
		,hideMode:'offsets'
		,labelStyle: 'font-size:8px'
	}
	
        ,items:[{
                title: 'Datos IVA / ICE'
                //html: "My content was added during construction."
		,autoScroll:true
		//,defaults:{anchor:'-20'}
		,bodyStyle:estilo
		// fields
		,items:[{
		    xtype: 'checkboxgroup'
		    ,itemCls: 'x-check-group-alt'
		    ,fieldLabel: ''//Custom Layout<br />(w/ validation)'
		    //,allowBlank: false
		    ,labelSeparator: ''
		    ,bodyStyle:estilo2
		    ,anchor: '95%'
		    ,items: [
			{
			    columnWidth: '.2'
			    ,bodyStyle:estilo2
			    ,defaults:{anchor: '95%', hideLabel: true}
			    ,items: [
				{xtype: 'label', text: 'Descripcion', cls:'x-form-check-group-label', anchor:'-15'}
				,{xtype: 'textfield' ,name:'field9',fieldLabel:'Field 9', value:'IVA Tarifa 0%', readOnly:true, anchor: '95%'}
				,{xtype: 'textfield' ,name:'field10',fieldLabel:'Field 10', value:'IVA Gravable', readOnly:true, anchor: '95%'}
				,{xtype: 'textfield' ,name:'field11',fieldLabel:'Field 11', value:'ICE', readOnly:true}
			    ]
			},{
			columnWidth: '.16'
			,bodyStyle:estilo2
			,defaults:{anchor: '95%', hideLabel: true}
			,items: [
			    {xtype: 'label', text: 'Base Imp.', cls:'x-form-check-group-label', anchor:'-15'}
			    ,olBaseIVA_0
			    ,olBaseIVA
			    ,olBaseICE
			    ]
			},{
			    columnWidth: '.4'
			    ,bodyStyle:estilo2
			    ,defaults:{anchor: '95%', hideLabel: true}
			    ,items: [
				{xtype: 'label', text: 'Cod. Imp.', cls:'x-form-check-group-label', anchor:'-15'}
				,{xtype: 'textfield' ,name:'field3',fieldLabel:'Field 3', readOnly:true}
				,olIvaGrabable
				,olICE
			    ]
			},{
			    columnWidth: '.08'
			    ,bodyStyle:estilo2
			    ,defaults:{anchor: '95%', hideLabel: true}
			    ,items: [
				{xtype: 'label', text: '%', cls:'x-form-check-group-label', anchor:'-15'}
				,{xtype: 'textfield' ,name:'field3',fieldLabel:'Field 3', readOnly:true}
				,olPorcIVA
				,olPorcICE
			    ]
			},{
			    columnWidth: '.16'
			    ,bodyStyle:estilo2
			    ,defaults:{anchor: '95%', hideLabel: true}
			    ,items: [
				{xtype: 'label', text: 'Monto', cls:'x-form-check-group-label', anchor:'-15'}
				,{xtype: 'numberfield' ,name:'field4',fieldLabel:'Field 4', readOnly:true}
				,olMontoIVA
				,olMontoICE
			    ]
			}
		    ]
		}]
            },{
                title: 'Retenciones'
		,autoScroll:true
		,bodyStyle:estilo
                //autoLoad:'ajax1.htm'
		,items:[{
		    xtype: 'checkboxgroup'
		    ,itemCls: 'x-check-group-alt'
		    ,fieldLabel: ''//Custom Layout<br />(w/ validation)'
		    ,bodyStyle:estilo
		    ,labelSeparator: ''
		    ,anchor: '95%'
		    ,items: [
			{
			    columnWidth: '.2'
			    ,bodyStyle:estilo2
			    ,defaults:{anchor: '95%', hideLabel: true}
			    ,items: [
				{xtype: 'label', text: 'Descripcion', cls:'x-form-check-group-label', anchor:'-15'}
				,{xtype: 'textfield' ,name:'field9',fieldLabel:'Field 9', value:'Ret. IVA Bienes', readOnly:true}
				,{xtype: 'textfield' ,name:'field10',fieldLabel:'Field 10', value:'Ret. IVA Servicios', readOnly:true}				
			    ]
			},{
			    columnWidth: '.16'
			    ,bodyStyle:estilo2
			    ,defaults:{anchor: '95%', hideLabel: true}
			    ,items: [
				{xtype: 'label', text: 'Base Imp.', cls:'x-form-check-group-label', anchor:'-15'}
				,olBaseRetIVAbienes
				,olBaseRetIVAserv
				]
			},{
			    columnWidth: '.4'
			    ,bodyStyle:estilo2
			    ,defaults:{anchor: '95%', hideLabel: true}
			    ,items: [
				{xtype: 'label', text: 'Cod. Imp.', cls:'x-form-check-group-label', anchor:'-15'}
				,olRetIVAbienes
				,olRetIVAserv
			    ]
			},{
			    columnWidth: '.08'
			    ,bodyStyle:estilo2
			    ,defaults:{anchor: '95%', hideLabel: true}
			    ,items: [
				{xtype: 'label', text: '%', cls:'x-form-check-group-label', anchor:'-15'}
				,olPorcRetIVAbienes
				,olPorcRetIVAserv
			    ]
			},{
			    columnWidth: '.16'
			    ,bodyStyle:estilo2
			    ,defaults:{anchor: '95%', hideLabel: true}
			    ,items: [
				{xtype: 'label', text: 'Monto', cls:'x-form-check-group-label', anchor:'-15'}
				,olMontoRetIVAbienes
				,olMontoRetIVAserv
			    ]
			}
		    ]
		    }
		    ,{
			xtype: 'checkboxgroup'
			,itemCls: 'x-check-group-alt'
			,fieldLabel: ''//Custom Layout<br />(w/ validation)'
			,bodyStyle:estilo
			,labelSeparator: ''
			,anchor: '97%'
			,items: [
			    {
				columnWidth: '.05'
				,bodyStyle:estilo2
				,defaults:{anchor: '95%', hideLabel: true}
				,items: [
				    {xtype: 'label', text: '#', cls:'x-form-check-group-label', anchor:'-15'}
				    ,{xtype: 'textfield' ,name:'field9', value:'1', readOnly:true}
				    ,{xtype: 'textfield' ,name:'field10', value:'2', readOnly:true}
				    ,{xtype: 'textfield' ,name:'field11', value:'3', readOnly:true}
				]
			    },{
			    columnWidth: '.55'
			    ,bodyStyle:estilo2
			    ,defaults:{anchor: '95%', hideLabel: true}
			    ,items: [
				{xtype: 'label', text: 'Cod. de Ret. en la Fuente', cls:'x-form-check-group-label', anchor:'-15'}
				,olRetCodFuente1
				,olRetCodFuente2
				,olRetCodFuente3
				]
			    },{
				columnWidth: '.16'
				,bodyStyle:estilo2
				,defaults:{anchor: '95%', hideLabel: true}
				,items: [
				    {xtype: 'label', text: 'Base Imp.', cls:'x-form-check-group-label', anchor:'-15'}
				    ,olRetBaseImp1
				    ,olRetBaseImp2
				    ,olRetBaseImp3
				]
			    },{
				columnWidth: '.08'
				,bodyStyle:estilo2
				,defaults:{anchor: '95%', hideLabel: true}
				,items: [
				    {xtype: 'label', text: '%', cls:'x-form-check-group-label', anchor:'-15'}
				    ,olRetPorc1
				    ,olRetPorc2
				    ,olRetPorc3
				]
			    },{
				columnWidth: '.16'
				,bodyStyle:estilo2
				,defaults:{anchor: '95%', hideLabel: true}
				,items: [
				    {xtype: 'label', text: 'Monto', cls:'x-form-check-group-label', anchor:'-15'}
				    ,olRetMonto1
				    ,olRetMonto2
				    ,olRetMonto3
				]
			    }
			]
		    }
		    ,{
			xtype : 'panel'
			//,id: 'datosGen'
			,border:false
			,layout: 'table'
			,hideMode: 'offsets'
			,anchor: '0'
			,bodyStyle:estilo
			,items: [
			{
			    layout:'column'
			    ,border:false
			    ,bodyStyle:estilo
			    ,defaults:{
				    //columnWidth:0.1
				   layout:'form'
				   ,border:false
				   ,xtype:'panel'
				   ,bodyStyle:estilo
				   ,labelWidth:61
				   //,bodyStyle:'padding:0 18px 0 0'
			   }
			   ,items:[
				{columnWidth:0.15,defaults:{anchor:'97%', labelWidth:100}
				,items:[olRetComprobante1]},
				{columnWidth:0.06,defaults:{anchor:'97%'}
				,items:[olRetComprobante2]},
				{columnWidth:0.10,defaults:{anchor:'97%'}
				,items:[olRetComprobante3]},
				{columnWidth:0.22,defaults:{anchor:'97%', labelWidth:50}
				,items:[olRetAutorizacion]},
				{columnWidth:0.05, xtype: 'container',
				    layout: 'table', autoEl: {}, layoutConfig: {columns: 1}
				    ,defaults:{anchor:'97%'}
					 ,items:[olRetVerAutoriz]},
				{columnWidth:0.11,defaults:{anchor:'97%', labelWidth:50}
				,items:[olRetFechaAut1]},
				{columnWidth:0.11,defaults:{anchor:'97%', labelWidth:50}
				,items:[olRetFechaAut2]},
				{columnWidth:0.20,defaults:{anchor:'97%'}
				,items:[olRetFechaEmi]}
				
			   ]
			}
			]
		    }
		]
            },{
                
		title: 'Datos Generales'
		,bodyStyle:estilo
		,width:'700'
		,items: [
		    {
			 xtype : 'panel'
			,bodyStyle:estilo
			,border:false
			//,id: 'datosGen'
			,layout: 'table'
			,layoutConfig: {columns: 3 }
			//,hideMode: 'offsets'
			//,anchor: '0'
			,items: [
				{
				    colspan:3//,html:'1,1'
				    ,layout:'column'
				    ,border:false
				    ,bodyStyle:estilo
				    ,defaults:{
					    columnWidth:0.5
					   ,layout:'form'
					   //,border:false
					   //,xtype:'panel'
					   ,bodyStyle:estilo
					   ,width : 250
					   ,labelWidth:110
					   //,bodyStyle:'padding:0 18px 0 0'
				   }
				   ,items:[
					{defaults:{anchor:'97%'}
					,items:[olCodDocumModif]},
					{defaults:{anchor:'97%'}
					,items:[olFechaEmiModif]}
				   ]
				}
			    ]
		    }	
		    ,{
			 xtype : 'panel'
			,bodyStyle:estilo
			,border:false
			//,id: 'datosGen'
			,layout: 'table'
			,hideMode: 'offsets'
			,anchor: '0'
			,items: [
				{
				    layout:'column'
				    ,border:false
				    ,bodyStyle:estilo
				    ,defaults:{
					    columnWidth:0.27
					   ,layout:'form'
					   //,border:false
					   //,xtype:'panel'
					   ,bodyStyle:estilo
					   ,labelWidth:110
				   }
				   ,items:[
					{defaults:{anchor:'97%'}
					,items:[olNCompModif1]},
					{columnWidth:0.1, defaults:{anchor:'97%'}
					,items:[olNCompModif2]},
					{columnWidth:0.20, defaults:{anchor:'97%'}
					,items:[olNCompModif3]},
					{columnWidth:0.30, defaults:{anchor:'97%'}
					,items:[olAutorizModif]}
				   ]
				}
			    ]
		    }
		    ,{
			 xtype : 'panel'
			,bodyStyle:estilo
			,border:false
			//,id: 'datosGen'
			,layout: 'table'
			,hideMode: 'offsets'
			,anchor: '0'
			,items: [
				{
				    layout:'column'
				    ,border:false
				    ,bodyStyle:estilo
				    ,defaults:{
					    columnWidth:0.3
					   ,layout:'form'
					   //,border:false
					   //,xtype:'panel'
					   ,bodyStyle:estilo
					   ,labelWidth:110
				   }
				   ,items:[
					{defaults:{anchor:'97%'}
					,items:[olContraPart]},
					{columnWidth:0.35, labelWidth:140, defaults:{anchor:'97%'}
					,items:[olMontoTituloOner]},
					{columnWidth:0.35, defaults:{anchor:'97%'}
					,items:[olMontoTituloGra]}
				   ]
				}
			    ]
		    }
		    ,{
			 xtype : 'panel'
			,bodyStyle:estilo
			,border:false
			//,id: 'datosGen'
			,layout: 'table'
			,hideMode: 'offsets'
			,anchor: '0'
			,items: [
				{
				    layout:'column'
				    ,border:false
				    ,bodyStyle:estilo
				    ,defaults:{
					    columnWidth:0.3
					   ,layout:'form'
					   //,border:false
					   //,xtype:'panel'
					   ,bodyStyle:estilo
					   ,labelWidth:110
				   }
				   ,items:[
					{defaults:{anchor:'97%'}
					,items:[olNumComprobantes]},
					{defaults:{anchor:'97%'}
					,items:[olIvaPresuntivo]},
					{columnWidth:0.35, defaults:{anchor:'97%'}
					,items:[olRetPresuntiva]}
				   ]
				}
			    ]
		    }
		]
	    },{
                title: 'Import / Export'
		,bodyStyle:estilo
                //listeners: {activate: handleActivate},
                //html: "I am tab 4's content. I also have an event listener attached."
		,items: [
		    {
			 xtype : 'panel'
			,bodyStyle:estilo
			,border:false
			//,id: 'datosGen'
			,layout: 'table'
			,layoutConfig: {columns: 3 }
			//,hideMode: 'offsets'
			//,anchor: '0'
			,items: [
				{
				    colspan:3//,html:'1,1'
				    ,layout:'column'
				    ,border:false
				    ,bodyStyle:estilo
				    ,defaults:{
					    columnWidth:0.1
					   ,layout:'form'
					   //,border:false
					   //,xtype:'panel'
					   ,bodyStyle:estilo
					   ,width : 80
					   ,labelWidth:100
					   //,bodyStyle:'padding:0 18px 0 0'
				   }
				   ,items:[
					{columnWidth:0.3,labelWidth:120,width : 250,defaults:{anchor:'97%'}
					,items:[olRefrAduanero1]},
					{defaults:{anchor:'97%'}
					,items:[olRefrAduanero2]},
					{defaults:{anchor:'97%'}
					,items:[olRefrAduanero3]},
					{defaults:{anchor:'97%'}
					,items:[olRefrAduanero4]},
					{defaults:{anchor:'97%'}
					,items:[olRefrAduanero5]},
					{columnWidth:0.3,labelWidth:120,defaults:{anchor:'97%'}
					,items:[olImExTipoCompr]}
				   ]
				}
			    ]
		    }	
		    ,{
			 xtype : 'panel'
			,bodyStyle:estilo
			,border:false
			//,id: 'datosGen'
			,layout: 'table'
			,hideMode: 'offsets'
			,anchor: '0'
			,items: [
				{
				    layout:'column'
				    ,border:false
				    ,bodyStyle:estilo
				    ,defaults:{
					    columnWidth:0.30
					   ,layout:'form'
					   //,border:false
					   //,xtype:'panel'
					   ,bodyStyle:estilo
					   ,labelWidth:100
				   }
				   ,items:[
					{labelWidth:120,defaults:{anchor:'97%'}
					,items:[olImExTipo]},
					{defaults:{anchor:'97%'}
					,items:[olImExValor]},
					{labelWidth:130,defaults:{anchor:'97%'}
					,items:[olImExValorCompr]}
				   ]
				}
			    ]
		    }
		    ,{
			 xtype : 'panel'
			,bodyStyle:estilo
			,border:false
			//,id: 'datosGen'
			,layout: 'table'
			,hideMode: 'offsets'
			,anchor: '0'
			,items: [
				{
				    layout:'column'
				    ,border:false
				    ,bodyStyle:estilo
				    ,defaults:{
					    columnWidth:0.5
					   ,layout:'form'
					   //,border:false
					   //,xtype:'panel'
					   ,bodyStyle:estilo
					   ,labelWidth:120
				   }
				   ,items:[
					{defaults:{anchor:'97%'}
					,items:[olImExFecEmb]},
					{defaults:{anchor:'97%'}
					,items:[olImExDocEmb]}
				   ]
				}
			    ]
		    }		    
		]
            },{
                title: 'Contabilidad'
                //disabled:true,
                //html: "Can't see me cause I'm disabled"
		//autoLoad:'../In_Files/InTrTr_Cabe.php?com_RegNumero=4909&pTipoComp=PC'
		,listeners:{
		    activate : function(){ 
			//alert('you clicked me');
			//debugger;
			numReg = Ext.getCmp("frmMant").findById("regNum").getValue();
			tipoCompr = Ext.getCmp("frmMant").findById("ContTipoComp").getValue();
			if ("" == numReg){
			    Ext.Msg.alert("Alerta","Anexo no contabilizado");
			}
			else{
			    window.open("../In_Files/InTrTr_Cabe.php?com_RegNumero="+numReg+"&pTipoComp="+tipoCompr);
			}
		    } 
		}
            }
        ]
    });
    
    
    
    
    ///////////////// [ Controles para contabilizacion ]//////////////////
    /*Para Consultar cuentas*/      
    app.CoRtTr.dsCmbCtaGasto = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBaseGeneral,
            baseParams: {id : 'CoRtTr_CtaGasto'}
    });
    var olCtaGasto = new Ext.form.ComboBox({
			fieldLabel:'Cuenta Gasto'
			,id:'txt_CtaGasto'
			//name:'txt_Embarque',
			//width:150,
			,store: app.CoRtTr.dsCmbCtaGasto
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'txt_Cta'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350
			,listClass: 'x-combo-list-small'
			,tabIndex:'910'
		    });
    /*Para Consultar cuentas*/      
    app.CoRtTr.dsCmbCtaAux = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	app.CoRtTr.rdComboBaseGeneral,
            baseParams: {id : 'CoRtTr_CtaGastoAux'}
    });
    var olCtaAux = new Ext.form.ComboBox({
			fieldLabel:'Auxiliar'
			,labelWidth:10
			,id:'txt_CtaAuxiliar'
			//name:'txt_Embarque',
			//,width:250
			,store: app.CoRtTr.dsCmbCtaAux
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'txt_CtaAux'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 2
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350
			,listClass: 'x-combo-list-small'
			//,anchor:'97%'
			,tabIndex:'911'
		    });
    //if(!win){
    //debugger;
    
    if(!Ext.getCmp("frmMant")){
	//debugger;
	
	
	//the form
	var frmMant = new Ext.form.FormPanel({
	    //utl:'view/userContacts.cfm',
	    //,bodyStyle:'padding:5px'
	    width: 740
	    //defaults: {width: 230},
	    ,border:false
	    ,id:'frmMant'
            ,layout:'form'
	    ,baseCls: 'x-small-editor'
            //style: 'font-size:4px !important; background-color:white !important',
	    ,bodyStyle:estilo//'background-color: '+color+'; font-size:4px;'
            ,defaults:{
		anchor:'98%'
		,bodyStyle:estilo//'background-color: '+color+'; font-size:6px;'
		//,labelStyle: 'font-size:4px'
		//,labelStyle:'background-color: #D0D0D0'
		}
	    ,items:[//olTipoTransac
		    olCompRegNum, olCompTipo,
		    {
			//bodyStyle:estilo//'background-color: '+color+';border-style: none;'
                        layout:'column'
                        ,defaults:{
                               layout:'form'
                               ,border:false
                               ,xtype:'panel'
			       //,class:'x-form'
			       //, style:'background-color: #DBDBDB'
			       //,ctClass:'pnl-col'
                               ,bodyStyle:'background-color: '+color+';border-style: none;'//padding:0 18px 0 0'
			       
			       //,labelStyle:'background-color: #D0D0D0'
                       }
                       ,items:[
				{columnWidth:0.6, defaults:{anchor:'97%'}
					 ,items:[olID, olTipoTransac]}
				,{columnWidth:0.4, defaults:{anchor:'97%'}
					 ,items:[olAplica]}
			      ]
		    }
		   ,olSustentoCod ,olSustento//, olProveedor, olProveedorFact,
		   ,{
                        layout:'column'
			//,bodyStyle:'background-color: '+color
                        ,defaults:{
                               layout:'form'
                               ,border:false
                               ,xtype:'panel'
                               //,bodyStyle:'padding:0 18px 0 0'
			       ,bodyStyle:'background-color: '+color
                       }
                       ,items:[
				{columnWidth:0.5, defaults:{anchor:'97%'}
					 ,items:[olProveedor,olRucProv,olRucProvFact]}
				,{columnWidth:0.5, defaults:{anchor:'97%'}
					 ,items:[//olProveedorFact
						 {
						    layout:'column'
						    ,bodyStyle:estilo
						    ,defaults:{
							    layout:'form'
							   ,border:false
							   ,xtype:'panel'
							   ,bodyStyle:estilo
							   //,labelWidth:42
						   }
						   ,items:[
							{columnWidth:0.91, defaults:{anchor:'97%'}
							,items:[olProveedorFact]},
							{columnWidth:0.09, defaults:{anchor:'97%'}
							,items:[olTipoProvFact]}
						   ]
						}
					    ]}
			      ]
		    }
                    ,{
                        layout:'column'
			//,bodyStyle:'background-color: '+color
                        ,defaults:{
                               layout:'form'
                               ,border:false
                               ,xtype:'panel'
                               //,bodyStyle:'padding:0 18px 0 0'
			       ,bodyStyle:'background-color: '+color+'; font-size:8px;'
                       }
                       ,items:[
                            {columnWidth:0.6, defaults:{anchor:'97%'}
                            ,items:[olTipoComp]},
                            {columnWidth:0.4, defaults:{anchor:'97%'}
                            ,items:[
                                {
				    layout:'column'
				    ,bodyStyle:estilo
                                    ,defaults:{
                                            columnWidth:0.4
                                           ,layout:'form'
                                           ,border:false
                                           ,xtype:'panel'
					   ,bodyStyle:estilo
                                           ,labelWidth:42
                                   }
                                   ,items:[
                                        {columnWidth:0.3, defaults:{anchor:'97%'}
                                        ,items:[olNComp1]},
                                        {columnWidth:0.15, defaults:{anchor:'97%'}
                                        ,items:[olNComp2]},
                                        {defaults:{anchor:'97%'}
                                        ,items:[olNComp3, olCompValido]}
                                   ]
                                }
                            ]}
                       ]
                    }
                    ,//olAutorizacion
		    {
                        layout:'column'
			,bodyStyle:estilo
                        ,defaults:{
                               layout:'form'
                               ,border:false
                               ,xtype:'panel'
                               //,bodyStyle:'padding:0 18px 0 0'
			       ,bodyStyle:estilo
                       }
                       ,items:[
				{columnWidth:0.28, defaults:{anchor:'97%'}
					 ,items:[olAutorizacion]}
				,{columnWidth:0.05, xtype: 'container',
				    layout: 'table', autoEl: {}, layoutConfig: {columns: 1}
				    ,defaults:{anchor:'97%'}
					 ,items:[olVerAutoriz]}
				,{columnWidth:0.12, defaults:{anchor:'97%'}
					 ,items:[olFechaAut1]}
				,{columnWidth:0.12, defaults:{anchor:'97%'}
					 ,items:[olFechaAut2]}
				,{columnWidth:0.43, defaults:{anchor:'97%', labelWidth:48}
					 ,items:[olEmbarque]}
			      ]
		    }
		    /*,{
			    xtype: 'container',
			    layout: 'table',
			    autoEl: {},
			    layoutConfig: {columns: 3},
			    defaultType: 'button',
			    items: [{
			       text: 'b1'
			    },{
			       text: 'b2'
			    },{
			       text: 'b3'
			   }]
		    }*/
		    , olConcepto
		    ,{
                        layout:'column'
			,bodyStyle:estilo
                        ,defaults:{
                               layout:'form'
                               ,border:false
                               ,xtype:'panel'
			       ,bodyStyle:estilo
                               //,bodyStyle:'padding:0 18px 0 0'
                       }
                       ,items:[
                            {columnWidth:0.3, defaults:{anchor:'97%'}
				,items:[olFechaEmi]}
			    ,{columnWidth:0.3, defaults:{anchor:'97%'}
				,items:[olFechaReg]}
			    ,{columnWidth:0.20, defaults:{anchor:'97%', labelWidth:50}
				,items:[olSemana]}
			]
		    }
		    , olTab
		    //,olCtaGasto , olCtaAux
		    ,{
                        layout:'column'
			,bodyStyle:estilo
                        ,defaults:{
                               layout:'form'
                               ,border:false
                               ,xtype:'panel'
			       ,bodyStyle:estilo
                               //,bodyStyle:'padding:0 18px 0 0'
                       }
                       ,items:[
                            {columnWidth:0.5, defaults:{anchor:'97%'}
                            ,items:[olCtaGasto]}
			    ,{columnWidth:0.5, defaults:{anchor:'97%'}
                            ,items:[olCtaAux]}
			]
		    }
	    ],
	    buttons:[
		{
		    text:'Nuevo'//,
		    //type:'submit',
		    ,iconCls: 'iconNuevo'
		    ,handler:function(){
			Ext.getCmp("frmMant").getForm().reset();
			Ext.getCmp("frmMantWin").setTitle("Nuevo Anexo");
		    }
		    ,tabIndex:'916'
		},{
		    text:'Guardar'
		    ,type:'submit'
		    ,iconCls: 'iconGrabar'
		    ,handler:fGrabar
		    ,tabIndex:'912'
		},{
		    text:'Imprimir'
		    //,type:'submit'
		    ,iconCls: 'iconImprimir'
		    ,handler:function(){
			window.open("../../AAA_SEGURIDAD/Rt_Files/CoRtTr_comprob.rpt.php?ID="+Ext.getCmp("frmMant").findById('ID').getValue(),"Anexo","status=1");
		    }
		    ,tabIndex:'913'
		},{
		    text:'Imprimir Blanco'
		    //,type:'submit'
		    ,iconCls: 'iconImprimir'
		    ,handler:function(){
			window.open("CoRtTr_comprob_blanco.rpt.php?ID="+Ext.getCmp("frmMant").findById('ID').getValue(),"Anexo","status=1");
		    }
		    ,tabIndex:'914'
		},
		{
		    text:'Salir'
		    ,iconCls: 'iconSalir'
		    ,handler:function(){
			win.close();
		    }
		    ,tabIndex:'915'
		}
	    ]
	});

        
	frmMant.findById('txt_sustento').on('change',
            function (pEvt){
		//debugger;
                app.CoRtTr.dsCmbTipoComp.baseParams.pCodigos=win.findById("codigosSustento").getValue();
                app.CoRtTr.dsCmbTipoComp.reload();
            });
	frmMant.findById('txt_tipoComp').on('beforequery',
            function (pEvt){
		//debugger;
                app.CoRtTr.dsCmbTipoComp.baseParams.pCodigos=win.findById("codigosSustento").getValue();
            });
	
	
	//Ext.Msg.alert('test','das ist ein test');
        var win = new Ext.Window({
            title:'Anexos',
            layout:'fit',
            width:855,
            height:560,
	    id: "frmMantWin",
            style: 'font-size:8px',
            border:false,
            items:frmMant
        });
        win.show();
        
        

    }
};

function fValidarAnexo(){
  var anexoValido = false;
  //debugger;
  /*para comprobar que los proveedores tengan ruc valido
  */
  //fValidaRuc();
  /*if ("" != app.CoRtTr.msgValidaRuc){
    //Ext.Msg.alert(app.CoRtTr.msgValidaRuc);
    return anexoValido;
  }*/
  if (app.CoRtTr.msgValidaRuc != ""){
    Ext.Msg.alert("Alerta",app.CoRtTr.msgValidaRuc);
    return anexoValido;
  }
  
  if (app.CoRtTr.msgValidaRetencion != "-"){
    Ext.Msg.alert("Alerta",app.CoRtTr.msgValidaRetencion);
    return anexoValido;
  }
  
  
  
  //debugger;
  /*Valida que comprobante no hay sido ingresado
  */
  if ('N' == Ext.getCmp("txt_compValido").getValue()){
	Ext.getCmp("secuencial").markInvalid();
	Ext.Msg.alert("Alerta","Comprobante ya fue ingresado");
	return anexoValido;
  }
  
  if ("N" == app.CoRtTr.compValido){
	Ext.Msg.alert("Alerta","Numero de Comprobante no esta en rango de comprobantes de autorizacion.");
	return anexoValido;
  }
  
  
    /*
     Valida que fecha de emision este en el rango de fechas de la autotizacion del SRI
    */    
    var fec1 = Ext.getCmp("frmMant").findById('fechaAut1').getValue();
    var fec2 = Ext.getCmp("frmMant").findById('fechaAut2').getValue();
    var fecEmi = Ext.getCmp("frmMant").findById('fechaEmision').getValue().dateFormat("Y-m-d");
  
    if  ("" == fec1 || "" == fec2){
	Ext.Msg.alert("Alerta","Ingrese Autorizacion");
	return anexoValido;
    }else{
	fec1 = fec1.dateFormat("Y-m-d");
	fec2 = fec2.dateFormat("Y-m-d");
    }
  
    if (fecEmi < fec1 || fecEmi > fec2){
	Ext.Msg.alert("Alerta","Fecha de Emision debe estar en el rango de fechas de la autorizacion");
	return anexoValido;
    }
    //debugger;
    /*
     Valida que valores retenidos no sean mayor al monto de IVA
*/
    var montoIVA = Ext.getCmp("frmMant").findById("tabDatos").items.itemAt(0).items.itemAt(0).items.item("montoIva").getValue();
    var base_ret_s = Ext.getCmp("frmMant").findById("tabDatos").items.itemAt(1).items.itemAt(0).items.item("montoIvaServicios").getValue();
    var base_ret_b = Ext.getCmp("frmMant").findById("tabDatos").items.itemAt(1).items.itemAt(0).items.item("montoIvaBienes").getValue();
    			
    if (montoIVA == "") montoIVA = 0;
    
    var totRet = 0;
    if (base_ret_b != "")
	totRet = base_ret_b;
    if (base_ret_s != "")
	totRet = totRet + base_ret_s;
    if (totRet > montoIVA){
	Ext.MessageBox.alert('Mensaje', 'La Base imponible de Retenciones de IVA (' + totRet + ') no debe ser mayor al monto total de IVA '+montoIVA);
	return anexoValido;
    }
    if (montoIVA != 0 && totRet == 0){
	Ext.MessageBox.alert('Mensaje', 'No se han retenido valores de IVA');
	return anexoValido;
    }
    if (montoIVA == 0 && totRet != 0){
	Ext.MessageBox.alert('Mensaje', 'No se pueden retener valores porque monto de IVA es 0');
	return anexoValido;
    }
  
  
  /*
     Valida que fecha de emision este en el rango de fechas de la autotizacion de retencion de la empresa
    */    
    var fec1 = Ext.getCmp('retfechaAut1').getValue();
    var fec2 = Ext.getCmp('retfechaAut2').getValue();
    var fecEmi = Ext.getCmp('fechaEmiRet1').getValue().dateFormat("Y-m-d");
    var autRet = Ext.getCmp('autRetencion1').getValue();
  
    if  (("" != autRet) && ("" == fec1 || "" == fec2)){
	Ext.Msg.alert("Alerta","Ingrese Autorizacion de retencion");
	return anexoValido;
    }else{
	if (("" != fec1 && "" != fec2)){
	    fec1 = fec1.dateFormat("Y-m-d");
	    fec2 = fec2.dateFormat("Y-m-d");
	}
    }
    if (("" != fec1 && "" != fec2)){
	if (fecEmi < fec1 || fecEmi > fec2){
	    Ext.Msg.alert("Alerta","Fecha de Emision debe estar en el rango de fechas de la autorizacion de retencion");
	    return anexoValido;
	}
    }
    if ("" == fec1 || "" == fec2){
	Ext.getCmp('retfechaAut1').setValue(new Date().format('d-M-y'));
	Ext.getCmp('retfechaAut2').setValue(new Date().format('d-M-y'));
    }
  
  
  
  anexoValido = true;
  return anexoValido;
};

function fValidaRuc(){
    //debugger;
    
    var olDat = Ext.Ajax.request({
	url: 'CoRtTr_anexoValidaComp'
	,callback: function(pOpt, pStat, pResp){
	     //debugger;
	    if (true == pStat){
		 
		   olRsp = eval("(" + pResp.responseText + ")");
		   app.CoRtTr.msgValidaRuc = olRsp.msg;
		  /* Ext.getCmp("txt_rucProvValido").setValue(olRsp.prov);
		   Ext.getCmp("txt_rucProvFactValido").setValue(olRsp.provFact);*/
		  if ("" != olRsp.msg){
		   Ext.Msg.alert("ALERTA",app.CoRtTr.msgValidaRuc);
		  }
		  return olRsp.msg;
						      
	      }
	}
	,params: {pIdProv: Ext.getCmp("txtProv").getValue()
		  ,pRucProv: Ext.getCmp("txt_rucProv").getValue()
		  ,pIdProvFact: Ext.getCmp("txtProvFact").getValue()
		  ,pRucProvFact: Ext.getCmp("txt_rucProvFact").getValue()
		  ,pOpc: 2
	    }//, pAux:ilAuxi, pBan:true, pTip: Ext.getCmp("slFormaPago").getValue()}
    })
};

function fValidaRetencion(){
    //debugger;
    
    var olDat = Ext.Ajax.request({
	url: 'CoRtTr_anexoValidaComp'
	,callback: function(pOpt, pStat, pResp){
	     //debugger;
	    if (true == pStat){
		 
		   olRsp = eval("(" + pResp.responseText + ")");
		   app.CoRtTr.msgValidaRetencion = olRsp.info.msg;
		  /* Ext.getCmp("txt_rucProvValido").setValue(olRsp.prov);
		   Ext.getCmp("txt_rucProvFactValido").setValue(olRsp.provFact);*/
		  if ("-" != olRsp.info.msg){
			Ext.Msg.alert("ALERTA",app.CoRtTr.msgValidaRetencion);
		  }
		  return olRsp.msg;
						      
	      }
	}
	,params: {pEstab: Ext.getCmp("estabRetencion1").getValue()
		  ,pPtoEmi: Ext.getCmp("puntoEmiRetencion1").getValue()
		  ,pSecuencial: Ext.getCmp("secRetencion1").getValue()
		  ,pOpc: 3
	    }//, pAux:ilAuxi, pBan:true, pTip: Ext.getCmp("slFormaPago").getValue()}
    })
};


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
    
    if (!fValidarAnexo()) return false;
    
    var olModif = Ext.getCmp("frmMant").getForm().items.items;//.findAll(function(olField){return olField.isDirty()});
    //Ext.Msg.alert('AVISO', 'Grabar');
    //debugger;
    oParams = {};
    olModif.collect(function(pObj, pIdx){
	//debugger;
    switch (pObj.getXType()) {
	case "datefield":
	    eval("oParams." + pObj.id + " = '" +  pObj.getValue().dateFormat(pObj.format) + "'" );
	    break
	case "combo" :
	    eval("oParams." + pObj.hiddenName + " = '" +  pObj.getValue() + "'" );
	    break;
	case "checkboxgroup":
	    var cont = 0;
	    var limite = pObj.items.getCount();
	    while (cont < limite){
		//debugger;
		switch (pObj.items.itemAt(cont).getXType()) {
		    case "datefield":
			eval("oParams." + pObj.items.itemAt(cont).id + " = '" +  pObj.items.itemAt(cont).getValue().dateFormat(pObj.items.itemAt(cont).format) + "'" );
			break;
		    case "combo" :
			eval("oParams." + pObj.items.itemAt(cont).hiddenName + " = '" +  pObj.items.itemAt(cont).getValue() + "'" );
			break;
		    default:
			if (pObj.items.itemAt(cont).id.substring(0,4) != "ext-"){
			    eval("oParams." + pObj.items.itemAt(cont).id + " = '" +  pObj.items.itemAt(cont).getValue() + "'" );
			}
		}	
		cont++;
	    }
	    break;
	default:
	    if (pObj.id.substring(0,4) != "ext-"){
		eval("oParams." + pObj.id + " = '" +  pObj.getValue() + "'" );
	    }
    }
    })
    //debugger;
    var ilId  = Ext.getCmp("frmMant").findById('ID').getValue();   //usa un valor ya asignado
    if  (ilId > 0) {
	    oParams.cnt_ID = ilId;
	    var slAction='UPD';
    }	else var slAction='ADD';
    //var slAction='ADD'; //solo por prueba quitar despues
    var slParams  = Ext.urlEncode(oParams);
    slParams += "&" +  Ext.urlEncode(gaHidden);
    //Ext.getCmp("frmMant").disable();
    //Ext.Msg.alert('AVISO', 'Grabar 22');
    fGrabarRegistro(Ext.getCmp("frmMant"), slAction, oParams, slParams);
    
    //Ext.getCmp("frmMant").enable();
    return 1;
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
  oParams.pTabla = 'fiscompras';
  Ext.Ajax.request({
    waitMsg: 'GRABANDO...',
    url:	'../Ge_Files/GeGeGe_generic.crud.php?pAction=' + slAction, //-------------    +'&' + slParams,
    method: 'POST',
    params: oParams,
    success: function(response,options){
      var responseData = Ext.util.JSON.decode(response.responseText);
      //debugger;
      switch (slAction) {
        case "ADD":
          slMens = 'Registro Creado';
	  fContabilizar(responseData.lastId);
	  Ext.getCmp("frmMant").findById('ID').setValue(responseData.lastId);
	  Ext.getCmp("frmMantWin").setTitle("Anexo "+responseData.lastId);
	  //Ext.getCmp("frmMant").findById('tar_NumTarja').setValue(responseData.lastId);
          //fCargaReg(Ext.getCmp("frmMant").findById('tar_NumTarja').getValue()); //solo por prueba se quito
          break;
        case "UPD":
          slMens = 'Registro Actualizado';
	  fContabilizar(Ext.getCmp("frmMant").findById('ID').getValue());
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

function fContabilizar(id){
    //debugger;
  //oParams.pTabla = 'fiscompras';
  Ext.Ajax.request({
    waitMsg: 'GRABANDO...',
    url:	'CoRtTr_cmpvtamant_contab_v2.php'
    ,method: 'POST'
    ,params: {pReg: id
		, pCtaGasto:Ext.getCmp("txt_CtaGasto").getValue()
		, pAuxGasto:Ext.getCmp("txt_CtaAuxiliar").getValue() }
    ,success: function(response,options){
      var responseData = Ext.util.JSON.decode(response.responseText);
      //debugger;
      //Ext.Msg.alert('AVISO ', slMens);
	Ext.getCmp("frmMant").findById("regNum").setValue(responseData.regNum);
	Ext.getCmp("frmMant").findById("ContTipoComp").setValue(responseData.tipocomp);
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

//////////////////////[ Funciones para Mantenimiento de Autorizaciones]/////////////////////////////

function fMantAutorizacion(){
        
    var color = "#E7E7E7"
    var estilo = 'background-color: '+color+'; font-size:4px; border-style: none; ';
    var estilo2 = 'background-color: '+color+'; font-size:12px; border-style: none; ';
    
    var slDateFmt  ='d-m-y';
    var slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d';
    
    var rdComboBaseGeneral = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;
    
     var olAutID = {
	    xtype:'numberfield'
	    ,fieldLabel: 'Numero Aut. SRI'
	    ,name: 'aut_ID'
	    ,id: 'aut_ID'
	    /*,listeners: {'change': function (combo,record){
			    NroAut = frmMantAut.findById("aut_ID").getValue();
			    frmMantAut.findById("aut_AutSri").setValue(NroAut);
			}}*/
	    ,hidden:true
	    ,hideLabel:true
	};
    var olAutSRI = {
	    xtype:'numberfield'
	    ,fieldLabel: 'Numero Aut. SRI'
	    ,name: 'aut_AutSri'
	    ,id: 'aut_AutSri'
	    ,allowBlank:false
	    /*,hidden:true
	    ,hideLabel:true*/
	};
    
    /*Para  consultar proveedores */
    app.CoRtTr.dsAutCmbProveedor = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'CoRtTr_proveedor'}
    });
    var olAutProveedor = new Ext.form.ComboBox({
			fieldLabel:'Proveedor'
			,id:'txtAutProv'
			,name:'txtAutProv'
			//width:150,
			,store: app.CoRtTr.dsAutCmbProveedor
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'aut_IdAuxiliar'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 2
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,listClass: 'x-combo-list-small'
			,allowBlank:false
			});
	
    /*Para  consultar tipos de documentos */
    app.CoRtTr.dsAutCmbTipoDoc = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php'
                    ,metod: 'POST'
		    //,extraParams: {pCodigos : '%'}//, pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'CoRtTr_tipoDoc'}
    });
	
    var olAutTipoDoc = new Ext.form.ComboBox({
			fieldLabel:'Tipo Documento'
			,id:'txt_tipoDoc'
			,name:'txt_tipoDoc'
			//width:150,
			,store: app.CoRtTr.dsAutCmbTipoDoc
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'aut_TipoDocum'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      480 
			,listClass: 'x-combo-list-small'
			,allowBlank:false
		    });
    
    var olAutEstab = new Ext.form.TextField({
		    fieldLabel: 'Establecimiento'
                    ,name: 'aut_establecimiento'
                    ,id: 'aut_establecimiento'
		    ,maxLength:3
		    ,minLength:3
		    ,stripCharsRe: /[^0123456789]/gi
		    ,mask: "###"
		    ,value:"001"
		    ,allowBlank: false
		});		
    var olAutPtoEmision = new Ext.form.TextField({
		    //hideLabel: true
		    fieldLabel: 'Pto.Emision'
                    ,name: 'aut_puntoEmision'
                    ,id: 'aut_puntoEmision'
		    ,maxLength:3
		    ,minLength:3
		    ,stripCharsRe: /[^0123456789]/gi
		    ,value:"001"
		    ,allowBlank: false
		}); 
    
    var olAutFechaEmi = {
                    xtype:'datefield'
                    ,fieldLabel: 'Fecha Emision'
                    ,name: 'aut_FecEmision'
                    ,id: 'aut_FecEmision'
		    ,allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
                };
		
    var olAutFechaVig = {
                    xtype:'datefield'
                    ,fieldLabel: 'Fecha Vigencia'
                    ,name: 'aut_FecVigencia'
                    ,id: 'aut_FecVigencia'
		    ,allowBlank:false
		    ,format: slDateFmt
		    ,altFormats: slDateFmts
                };
		
		
    var olAutNroInicial = {
	    xtype:'numberfield'
	    ,fieldLabel: 'Numero Inicial'
	    ,name: 'aut_NroInicial'
	    ,id: 'aut_NroInicial'
	    //,visible:false
	    //,hidden:true
	    //,hideLabel:true
	    //,width:30
	};
	
    var olAutNroFinal = {
	    xtype:'numberfield'
	    ,fieldLabel: 'Numero Final'
	    ,name: 'aut_NroFinal'
	    ,id: 'aut_NroFinal'
	    //,visible:false
	    //,hidden:true
	    //,hideLabel:true
	    //,width:30
	};
    
    var olAutRucImp = {
	    xtype:'numberfield'
	    ,fieldLabel: 'RUC Imprenta'
	    ,name: 'aut_RucImprenta'
	    ,id: 'aut_RucImprenta'
	    //,visible:false
	    //,hidden:true
	    //,hideLabel:true
	    //,width:30
	};
    var olAutImp = {
	    xtype:'numberfield'
	    ,fieldLabel: 'Aut. Imprenta'
	    ,name: 'aut_AutImprenta'
	    ,id: 'aut_AutImprenta'
	    //,visible:false
	    //,hidden:true
	    //,hideLabel:true
	    //,width:30
	};
    var olAutDigitado = {
	    xtype:'textfield'
	    ,fieldLabel: 'Digitado'
	    ,name: 'aut_Usuario'
	    ,id: 'aut_Usuario'
	    ,readOnly:true
	};
	
    var olAutDigFecha = {
	    xtype:'textfield'
	    //,fieldLabel: 'Digitado'
	    ,name: 'aut_FecRegistro'
	    ,id: 'aut_FecRegistro'
	    //,visible:false
	    //,hidden:true
	    ,hideLabel:true
	    ,readOnly:true
	    //,width:30
	};
    
    var olDatosOpc = new Ext.Panel({//Ext.form.FormPanel({
	title:'Datos Opcionales'
	,bodyStyle:estilo
	,border:false
	,layout:'form'
	,labelWidth:85
	,items:[{
		    //bodyStyle:estilo//'background-color: '+color+';border-style: none;'
		    layout:'column'
		    ,defaults:{
			   layout:'form'
			   ,border:false
			   ,xtype:'panel'
			   ,bodyStyle:estilo//'background-color: '+color+';border-style: none;'//padding:0 18px 0 0'
		    }
		   ,items:[
			    {columnWidth:0.6, defaults:{anchor:'97%'}
				     ,items:[olAutRucImp]}
			    ,{columnWidth:0.4, defaults:{anchor:'97%'}
				     ,items:[olAutImp]}
			  ]
		}
	    ]
    });
    //debugger;
    if(!Ext.getCmp("frmMantAut")){
	var frmMantAut = new Ext.form.FormPanel({
		//utl:'view/userContacts.cfm',
		//,bodyStyle:'padding:5px'
		width: 'auto'//270
		//defaults: {width: 230},
		,border:false
		,id:'frmMantAut'
		,layout:'form'
		//style: 'font-size:4px !important; background-color:white !important',
		,bodyStyle:estilo//'background-color: '+color+'; font-size:4px;'
		,defaults:{
		    anchor:'98%'
		    ,bodyStyle:estilo//'background-color: '+color+'; font-size:6px;'
		    //,labelStyle:'background-color: #D0D0D0'
		    }
		,items:[olAutID, olAutSRI, olAutProveedor, olAutTipoDoc
			//, olAutFechaEmi, olAutFechaVig
			,{
			    //bodyStyle:estilo//'background-color: '+color+';border-style: none;'
			    layout:'column'
			    ,defaults:{
				   layout:'form'
				   ,border:false
				   ,xtype:'panel'
				   ,bodyStyle:estilo//'background-color: '+color+';border-style: none;'//padding:0 18px 0 0'
			    }
			   ,items:[
				    {columnWidth:0.5, defaults:{anchor:'97%'}
					     ,items:[olAutEstab]}
				    ,{columnWidth:0.5, defaults:{anchor:'97%'}
					     ,items:[olAutPtoEmision]}
				  ]
			}
			,{
			    //bodyStyle:estilo//'background-color: '+color+';border-style: none;'
			    layout:'column'
			    ,defaults:{
				   layout:'form'
				   ,border:false
				   ,xtype:'panel'
				   ,bodyStyle:estilo//'background-color: '+color+';border-style: none;'//padding:0 18px 0 0'
			    }
			   ,items:[
				    {columnWidth:0.5, defaults:{anchor:'97%'}
					     ,items:[olAutFechaEmi]}
				    ,{columnWidth:0.5, defaults:{anchor:'97%'}
					     ,items:[olAutFechaVig]}
				  ]
			}
			,{
			    //bodyStyle:estilo//'background-color: '+color+';border-style: none;'
			    layout:'column'
			    ,defaults:{
				   layout:'form'
				   ,border:false
				   ,xtype:'panel'
				   ,bodyStyle:estilo//'background-color: '+color+';border-style: none;'//padding:0 18px 0 0'
			    }
			   ,items:[
				    {columnWidth:0.5, defaults:{anchor:'97%'}
					     ,items:[olAutNroInicial]}
				    ,{columnWidth:0.5, defaults:{anchor:'97%'}
					     ,items:[olAutNroFinal]}
				  ]
			}//,olAutRucImp,olAutImp		
			,olDatosOpc
			,{
			    //bodyStyle:estilo//'background-color: '+color+';border-style: none;'
			    layout:'column'
			    ,defaults:{
				   layout:'form'
				   ,border:false
				   ,xtype:'panel'
				   ,bodyStyle:estilo//'background-color: '+color+';border-style: none;'//padding:0 18px 0 0'
			    }
			   ,items:[
				    {columnWidth:0.5, defaults:{anchor:'97%'}
					     ,items:[olAutDigitado]}
				    ,{columnWidth:0.3, defaults:{anchor:'97%'}
					     ,items:[olAutDigFecha]}
				  ]
			}
			]
		,buttons:[
		    {
			text:'Grabar'//,
			,type:'submit'
			,iconCls: 'iconGrabar'
			,handler:fGrabarAutoriz/*function(){
			    Ext.getCmp("frmMant").getForm().reset();
			}*/
		    },{
			text:'Borrar'
			,iconCls: 'iconBorrar'
			//,type:'submit'
			//,handler:fGrabar
		    },
		    {
			text:'Salir'
			,iconCls: 'iconSalir'
			,handler:function(){
			    winAut.close();
			}
		    }
		]
	});
	
	var winAut = new Ext.Window({
            title:'Autorizaciones SRI',
            layout:'fit',
            width:420,
            height:300,
	    id: "frmMantAutWin",
            style: 'font-size:8px',
            border:false,
            items:frmMantAut
        });
	winAut.show();
    }
    //debugger;
    
    
    
};

function fGrabarAutoriz(){
    //debugger;
    
    
    
    if (!Ext.getCmp("frmMantAut").getForm().isDirty()) {
	    Ext.Msg.alert('AVISO', 'Los datos no han cambiado. <br>No tiene sentido Grabar');
	    return false;
    }
    if (!Ext.getCmp("frmMantAut").getForm().isValid()) {
	    Ext.Msg.alert('ATENCION!!', 'Hay Información incompleta o inválida');
	    return false;
    }
    if (-99 != Ext.getCmp("frmMantAut").findById('txtAutProv').getValue()){
	if (parseFloat(Ext.getCmp("secuencial").getValue()) < Ext.getCmp("frmMantAut").findById('aut_NroInicial').getValue() 
		    || parseFloat(Ext.getCmp("secuencial").getValue()) > Ext.getCmp("frmMantAut").findById('aut_NroFinal').getValue()){
	    app.CoRtTr.compValido = "N";
	    Ext.Msg.alert("Alerta","Numero de Comprobante no esta en rango de comprobantes de autorizacion.");
	    return false;
	}else{
	    app.CoRtTr.compValido = "S";
	}
    }else{
	if (parseFloat(Ext.getCmp("secRetencion1").getValue()) < Ext.getCmp("frmMantAut").findById('aut_NroInicial').getValue() 
		    || parseFloat(Ext.getCmp("secRetencion1").getValue()) > Ext.getCmp("frmMantAut").findById('aut_NroFinal').getValue()){
	    app.CoRtTr.compValidoRet = "N";
	    Ext.Msg.alert("Alerta","Numero de retencion no esta en rango de autorizacion.");
	    return false;
	}else{
	    app.CoRtTr.compValidoRet = "S";
	}
    }
    
    var olModif = Ext.getCmp("frmMantAut").getForm().items.items;//.findAll(function(olField){return olField.isDirty()});
    //Ext.Msg.alert('AVISO', 'Grabar');
    //debugger;
        
    oParams = {};
    
    var ilId  = Ext.getCmp("frmMantAut").findById('aut_ID').getValue();   //usa un valor ya asignado
    if  (ilId > 0) {
	    oParams.cnt_ID = ilId;
	    var slAction='UPD';
    }	else var slAction='ADD';
    
    var NroAut = Ext.getCmp("frmMantAut").findById("aut_AutSri").getValue();
    Ext.getCmp("frmMantAut").findById("aut_ID").setValue(NroAut);
    
    
    olModif.collect(function(pObj, pIdx){
    switch (pObj.getXType()) {
	case "datefield":
	    eval("oParams." + pObj.id + " = '" +  pObj.getValue().dateFormat(pObj.format) + "'" );
	    break
	case "combo" :
	    eval("oParams." + pObj.hiddenName + " = '" +  pObj.getValue() + "'" );
	    break;
	case "checkboxgroup":
	    var cont = 0;
	    var limite = pObj.items.getCount();
	    while (cont < limite){
		//debugger;
		switch (pObj.items.itemAt(cont).getXType()) {
		    case "datefield":
			eval("oParams." + pObj.items.itemAt(cont).id + " = '" +  pObj.items.itemAt(cont).getValue().dateFormat(pObj.items.itemAt(cont).format) + "'" );
			break;
		    case "combo" :
			eval("oParams." + pObj.items.itemAt(cont).hiddenName + " = '" +  pObj.items.itemAt(cont).getValue() + "'" );
			break;
		    default:
			if (pObj.items.itemAt(cont).id.substring(0,4) != "ext-"){
			    eval("oParams." + pObj.items.itemAt(cont).id + " = '" +  pObj.items.itemAt(cont).getValue() + "'" );
			}
		}	
		cont++;
	    }
	    break;
	default:
	    if (pObj.id.substring(0,4) != "ext-"){
		eval("oParams." + pObj.id + " = '" +  pObj.getValue() + "'" );
	    }
    }
    })
    //debugger;
    
    //var slAction='ADD'; //solo por prueba quitar despues
    var slParams  = Ext.urlEncode(oParams);
    slParams += "&" +  Ext.urlEncode(gaHidden);
    Ext.getCmp("frmMantAut").disable();
    //Ext.Msg.alert('AVISO', 'Grabar 22');
    fGrabarRegistroAutoriz(Ext.getCmp("frmMantAut"), slAction, oParams, slParams);
    
    Ext.getCmp("frmMantAut").enable();
    return 1;
};

function fGrabarRegistroAutoriz(fmForm, slAction, oParams, slParams){
  oParams.pTabla = '09_base.genautsri';
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
      //debugger;
      var fec1 = Ext.getCmp("frmMantAut").findById('aut_FecEmision').getValue();
      var fec2 = Ext.getCmp("frmMantAut").findById('aut_FecVigencia').getValue();
      var aut = Ext.getCmp("frmMantAut").findById('aut_AutSri').getValue();
      
      if (-99 != Ext.getCmp("frmMantAut").findById('txtAutProv').getValue()){
	Ext.getCmp("frmMant").findById('fechaAut1').setValue(fec1);
	Ext.getCmp("frmMant").findById('fechaAut2').setValue(fec2);
	Ext.getCmp("frmMant").findById('autorizacion').setValue(aut);
      }else{
	Ext.getCmp("frmMant").findById('retfechaAut1').setValue(fec1);
	Ext.getCmp("frmMant").findById('retfechaAut2').setValue(fec2);
	Ext.getCmp("frmMant").findById('autRetencion1').setValue(aut);
      }
      
      Ext.Msg.alert('AVISO ', slMens);
      
        
      
      Ext.getCmp("frmMantAutWin").close();
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

function fConsultaAutorizacion(id, opcSoloFechas, proveedor, tipoComp, establecimiento, puntoEmision, paraEstaEmpresa){
    //debugger;
    Ext.override(Ext.data.Store, {
            // private
            // Keeps track of the load status of the store. Set to true after a successful load event
            loaded: false,
            /**
             * Returns true if the store has previously performed a successful load function.
             * @return {Boolean} Whether the store is loaded.
             */
            isLoaded: function(){
                return this.loaded
            },
            // private
            // Called as a callback by the Reader during a load operation.
        loadRecords : function(o, options, success){
            if(!o || success === false){
                if(success !== false){
                    this.fireEvent("load", this, [], options);
                }
                if(options.callback){
                    options.callback.call(options.scope || this, [], options, false);
                }
                return;
            }
            var r = o.records, t = o.totalRecords || r.length;
            if(!options || options.add !== true){
                if(this.pruneModifiedRecords){
                    this.modified = [];
                }
                for(var i = 0, len = r.length; i < len; i++){
                    r[i].join(this);
                }
                if(this.snapshot){
                    this.data = this.snapshot;
                    delete this.snapshot;
                }
                this.data.clear();
                this.data.addAll(r);
                this.totalLength = t;
                this.applySort();
                this.fireEvent("datachanged", this);
            }else{
                this.totalLength = Math.max(t, this.data.length+r.length);
                this.add(r);
            }
                    this.loaded = true;
            this.fireEvent("load", this, r, options);
            if(options.callback){
                options.callback.call(options.scope || this, r, options, true);
            }
        }
    });
    
    Ext.override(Ext.form.ComboBox,{
        /**
         * Sets the specified value into the field.  If the value finds a match, the corresponding record text
         * will be displayed in the field.  If the value does not match the data value of an existing item,
         * and the valueNotFoundText config option is defined, it will be displayed as the default field text.
         * Otherwise the field will be blank (although the value will still be set).
         * @param {String} value The value to match
         */
        setValue : function(v){
            var text = v;
                    if (v && this.mode == 'remote' && !this.store.isLoaded()) {
                        this.lastQuery = '';
                        this.store.load({
                            scope: this,
                            params: this.getParams(),
                            callback: function(){
                                this.setValue(v)
                            }
                        })
                    }
            if(this.valueField){
                var r = this.findRecord(this.valueField, v);
                if(r){
                    text = r.data[this.displayField];
                }else if(this.valueNotFoundText !== undefined){
                    text = this.valueNotFoundText;
                }
            }
            this.lastSelectionText = text;
            if(this.hiddenField){
                this.hiddenField.value = v;
            }
            Ext.form.ComboBox.superclass.setValue.call(this, text);
            this.value = v;
        }
    });

    
    var olDat = Ext.Ajax.request({
	url: 'CoRtTr_consAutorizacion'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                ///debugger;
		if (1!= paraEstaEmpresa){//para proveedores
		    if (0 == opcSoloFechas){
			if (!olRsp.info.aut_ID){
			    Ext.getCmp("frmMantAut").findById("txtAutProv").setValue(Ext.getCmp("txtProvFact").getValue());
			    Ext.getCmp("frmMantAut").findById("txt_tipoDoc").setValue(Ext.getCmp("txt_tipoComp").getValue());
			    Ext.getCmp("frmMantAut").findById("aut_AutSri").setValue(Ext.getCmp("autorizacion").getValue());
			    Ext.getCmp("frmMantAut").findById("aut_establecimiento").setValue(Ext.getCmp("establecimiento").getValue());
			    Ext.getCmp("frmMantAut").findById("aut_puntoEmision").setValue(Ext.getCmp("puntoEmision").getValue());
			}else{
			    Ext.getCmp("frmMantAutWin").setTitle("Autorizacion "+olRsp.info.aut_ID);
			    
			    Ext.getCmp("frmMantAut").findById("aut_ID").setValue(olRsp.info.aut_ID);
			    Ext.getCmp("frmMantAut").findById("txtAutProv").setValue(olRsp.info.aut_IdAuxiliar);
			    Ext.getCmp("frmMantAut").findById("txt_tipoDoc").setValue(olRsp.info.aut_TipoDocum);
			    Ext.getCmp("frmMantAut").findById("aut_AutSri").setValue(olRsp.info.aut_AutSri);
			    Ext.getCmp("frmMantAut").findById("aut_AutImprenta").setValue(olRsp.info.aut_AutImprenta);
			    Ext.getCmp("frmMantAut").findById("aut_RucImprenta").setValue(olRsp.info.aut_RucImprenta);
			    Ext.getCmp("frmMantAut").findById("aut_FecEmision").setValue(olRsp.info.aut_FecEmision);
			    Ext.getCmp("frmMantAut").findById("aut_FecVigencia").setValue(olRsp.info.aut_FecVigencia);
			    Ext.getCmp("frmMantAut").findById("aut_NroInicial").setValue(olRsp.info.aut_NroInicial);
			    Ext.getCmp("frmMantAut").findById("aut_NroFinal").setValue(olRsp.info.aut_NroFinal);
			    Ext.getCmp("frmMantAut").findById("aut_FecRegistro").setValue(olRsp.info.aut_FecRegistro);
			    Ext.getCmp("frmMantAut").findById("aut_Usuario").setValue(olRsp.info.aut_Usuario);
			    Ext.getCmp("frmMantAut").findById("aut_establecimiento").setValue(olRsp.info.aut_establecimiento);
			    Ext.getCmp("frmMantAut").findById("aut_puntoEmision").setValue(olRsp.info.aut_puntoEmision);
			}
		    }else{
			if (!olRsp.info.aut_ID){
			    Ext.getCmp("frmMant").findById('fechaAut1').setValue("");
			    Ext.getCmp("frmMant").findById('fechaAut2').setValue("");
			    fMantAutorizacion();
			    fConsultaAutorizacion(Ext.getCmp("autorizacion").getValue(),0,proveedor, tipoComp, establecimiento, puntoEmision, paraEstaEmpresa);
			}else{
			    
			    Ext.getCmp("frmMant").findById('fechaAut1').setValue(olRsp.info.aut_FecEmision);
			    Ext.getCmp("frmMant").findById('fechaAut2').setValue(olRsp.info.aut_FecVigencia);
			    if (parseFloat(Ext.getCmp("secuencial").getValue()) < olRsp.info.aut_NroInicial 
					    || parseFloat(Ext.getCmp("secuencial").getValue()) > olRsp.info.aut_NroFinal){
				app.CoRtTr.compValido = "N";
				Ext.Msg.alert("Alerta","Numero de Comprobante no esta en rango de comprobantes de autorizacion.");
			    }else{
				app.CoRtTr.compValido = "S";
			    }
			}
		    }
		}else{//datos de retencion de empresa
		    if (0 == opcSoloFechas){
			if (!olRsp.info.aut_ID){
			    Ext.getCmp("frmMantAut").findById("txtAutProv").setValue(proveedor);
			    Ext.getCmp("frmMantAut").findById("txt_tipoDoc").setValue(tipoComp);
			    Ext.getCmp("frmMantAut").findById("aut_AutSri").setValue(id);
			    Ext.getCmp("frmMantAut").findById("aut_establecimiento").setValue(establecimiento);
			    Ext.getCmp("frmMantAut").findById("aut_puntoEmision").setValue(puntoEmision);
			}else{
			    Ext.getCmp("frmMantAutWin").setTitle("Autorizacion "+olRsp.info.aut_ID);
			    
			    Ext.getCmp("frmMantAut").findById("aut_ID").setValue(olRsp.info.aut_ID);
			    Ext.getCmp("frmMantAut").findById("txtAutProv").setValue(olRsp.info.aut_IdAuxiliar);
			    Ext.getCmp("frmMantAut").findById("txt_tipoDoc").setValue(olRsp.info.aut_TipoDocum);
			    Ext.getCmp("frmMantAut").findById("aut_AutSri").setValue(olRsp.info.aut_AutSri);
			    Ext.getCmp("frmMantAut").findById("aut_AutImprenta").setValue(olRsp.info.aut_AutImprenta);
			    Ext.getCmp("frmMantAut").findById("aut_RucImprenta").setValue(olRsp.info.aut_RucImprenta);
			    Ext.getCmp("frmMantAut").findById("aut_FecEmision").setValue(olRsp.info.aut_FecEmision);
			    Ext.getCmp("frmMantAut").findById("aut_FecVigencia").setValue(olRsp.info.aut_FecVigencia);
			    Ext.getCmp("frmMantAut").findById("aut_NroInicial").setValue(olRsp.info.aut_NroInicial);
			    Ext.getCmp("frmMantAut").findById("aut_NroFinal").setValue(olRsp.info.aut_NroFinal);
			    Ext.getCmp("frmMantAut").findById("aut_FecRegistro").setValue(olRsp.info.aut_FecRegistro);
			    Ext.getCmp("frmMantAut").findById("aut_Usuario").setValue(olRsp.info.aut_Usuario);
			    Ext.getCmp("frmMantAut").findById("aut_establecimiento").setValue(olRsp.info.aut_establecimiento);
			    Ext.getCmp("frmMantAut").findById("aut_puntoEmision").setValue(olRsp.info.aut_puntoEmision);
			}
		    }else{
			if (!olRsp.info.aut_ID){
			    Ext.getCmp("frmMant").findById('retfechaAut1').setValue("");
			    Ext.getCmp("frmMant").findById('retfechaAut2').setValue("");
			    fMantAutorizacion();
			    fConsultaAutorizacion(id,0,proveedor, tipoComp, establecimiento, puntoEmision, paraEstaEmpresa);
			}else{
			    
			    Ext.getCmp("frmMant").findById('retfechaAut1').setValue(olRsp.info.aut_FecEmision);
			    Ext.getCmp("frmMant").findById('retfechaAut2').setValue(olRsp.info.aut_FecVigencia);
			    if (parseFloat(Ext.getCmp("secRetencion1").getValue()) < olRsp.info.aut_NroInicial 
					    || parseFloat(Ext.getCmp("secRetencion1").getValue()) > olRsp.info.aut_NroFinal){
				app.CoRtTr.compValidoRet = "N";
				Ext.Msg.alert("Alerta","Numero de retencion no esta en rango de autorizacion.");
			    }else{
				app.CoRtTr.compValidoRet = "S";
			    }
			}
		    }
		}
                
	    }
	}
	/*,success: function(pRes,pPar){
	    Ext.getCmp("ilNumCheque").setValue(0)
	    Ext.getCmp("flSaldo").setValue(0)
	    }
	,failure: function(pObj, pRes){
	    Ext.getCmp("ilNumCheque").setValue(0)
	    Ext.getCmp("flSaldo").setValue(0)
	    }*/
	,params: {idAutoriz: id, pOpc: 1
		, pProv: proveedor//Ext.getCmp("txtProvFact").getValue()
		, pTipoComp: tipoComp//Ext.getCmp("txt_tipoComp").getValue()
		, pNumComp: 0
		, pEstablecimiento: establecimiento//Ext.getCmp("establecimiento").getValue()
		, pPuntoEmision: puntoEmision//Ext.getCmp("puntoEmision").getValue()
		}
    })
}

//si viene 1 en empresa significa que hay validar retencion
function fConsultaAutorizacionExistente(proveedor, tipoComp, numComp, establecimiento, puntoEmision, empresa){
        
    var olDat = Ext.Ajax.request({
	url: 'CoRtTr_consAutorizacion'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                //debugger;
	        
		if (1 != empresa){//autorizacion de proveedor
		    if (olRsp.info.aut_AutSri != "-"){
			Ext.getCmp("frmMant").findById('fechaAut1').setValue(olRsp.info.aut_FecEmision);
			Ext.getCmp("frmMant").findById('fechaAut2').setValue(olRsp.info.aut_FecVigencia);
			Ext.getCmp("frmMant").findById('autorizacion').setValue(olRsp.info.aut_AutSri);
		    }else{
			Ext.getCmp("frmMant").findById('fechaAut1').setValue("");
			Ext.getCmp("frmMant").findById('fechaAut2').setValue("");
			Ext.getCmp("frmMant").findById('autorizacion').setValue("");
		    }
		}else{//autorizacion de empresa
		    if (olRsp.info.aut_AutSri != "-"){
			Ext.getCmp('retfechaAut1').setValue(olRsp.info.aut_FecEmision);
			Ext.getCmp('retfechaAut2').setValue(olRsp.info.aut_FecVigencia);
			Ext.getCmp('autRetencion1').setValue(olRsp.info.aut_AutSri);
		    }else{
			Ext.getCmp('retfechaAut1').setValue("");
			Ext.getCmp('retfechaAut2').setValue("");
			Ext.getCmp('autRetencion1').setValue("");
		    }
		}
		
                
	    }
	}
	/*,success: function(pRes,pPar){
	    Ext.getCmp("ilNumCheque").setValue(0)
	    Ext.getCmp("flSaldo").setValue(0)
	    }
	,failure: function(pObj, pRes){
	    Ext.getCmp("ilNumCheque").setValue(0)
	    Ext.getCmp("flSaldo").setValue(0)
	    }*/
	,params: {idAutoriz: 0
	    , pOpc: 2
	    , pProv: proveedor
	    , pTipoComp: tipoComp
	    , pNumComp: numComp
	    , pEstablecimiento: establecimiento//Ext.getCmp("establecimiento").getValue()
	    , pPuntoEmision: puntoEmision//Ext.getCmp("puntoEmision").getValue()
	    }
    })
}
