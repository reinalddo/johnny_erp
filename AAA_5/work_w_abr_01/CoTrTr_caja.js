/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Fausto Astudillo
 *  @date       12/Oct/07
 *  @rev 	Gina Franco 15/May/09	Se agregaron botones para mostrar las cuentas
 *  @rev 	Gina Franco 19/May/09	Se agregaron controles en panel consulta (banco, fecha inicio y fin), con esto se mostraran los movimientos de un banco
 *  @rev	Erika Suarez 27/Marzo/2012	Reimpresion de Documentos (Comprobante,Cheque,Voucher)
*/
Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.namespace("app", "app.cart");
    
    Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
    Ext.QuickTips.init();
    olDet=Ext.get('divDet');
    var slWidth="width:250px; text-align:'left'";
    fCargaPermiso("rCo",app.cart.repConsol,1);
    
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
    slDateFmt  ='d-m-y';
    slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d';
   
   var rdComboBase = new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;  // Origen de datos Generico
   
   dsCmbBancos = new Ext.data.Store({
        proxy: 	new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php'				// Script de servidor generico que accede a BD
                    ,metod: 'POST'
                    //,extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}    //Parametros para obtener datos segun el contexto de la consulta sql
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'CoTrTr_bancos'}		// Parametro Basico: ID de consulta SQl (predefinida en PHP como variable de sesion)
    });
   
   var olBancos = new Ext.form.ComboBox({
			fieldLabel:'Bancos'
                        //,labelWidth:10
                        ,id:'txt_bancos'
			,name:'txt_bancos'
			//,width:160
			,store: dsCmbBancos
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'codAuxiliar'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      250 
		    });
    
        
    var olFechaIni = {	xtype:'datefield'
			,fieldLabel:'Fecha Inicio'
                        //,labelWidth:10
                        ,id:'txt_fecIni'
			,name:'txt_fecIni'
			,value: new Date().format('d-M-y')
			,emptyText:''
			,allowBlank:     false
			,format: slDateFmt
			,altFormats: slDateFmts
		    };
    var olFechaFin = {	xtype:'datefield'
			,fieldLabel:'Fecha Fin'
                        //,labelWidth:10
                        ,id:'txt_fecFin'
			,name:'txt_fecFin'
			,value: new Date().format('d-M-y')
			,emptyText:''
			,allowBlank:     false
			,format: slDateFmt
			,altFormats: slDateFmts
		    };
   
Ext.getDom('north').innerHTML = '<div id="north_left"></div><div id="north_right"></div>';


//////////////// [ Controles de Panel Acciones ] ////////////////////
var dr = new Ext.FormPanel({
    labelWidth: 90,
    frame: false,
    id:'busqueda',
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: false,
    width: 250,
	defaults: {width: 100},
	defaultType: 'datefield',
	items: [{
	      fieldLabel: 'Fecha de Corte',
	      name: 'txtFecha',
	      id: 'txtFecha',
	      value: new Date().format('d-M-y'),
	      format:'d-M-y',
	      endDateField: 'enddt' // id of the end date field
	}]
      });
    var slWidth="width:99%; text-align:'left'";
    dr.add({xtype:	'button',
	id:     'btnCxc',
	cls:	 'boton-menu',
	tooltip: 'Cuentas por Cobrar',
	text:    'Cuentas por Cobrar',
	style:   slWidth ,
	handler: function(){
                    //var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=C&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
//                    addTab({id:'gridConten', title:'Cuentas por Cobrar', url:slUrl});
			mostrarVentana("Cuentas por Cobrar","C");
	    		//fAgregarBotones("Cobrar","C");
                    //var w = Ext.getCmp('pnlIzq');
					//w.collapsed ? w.expand() : w.collapse();
	    }
    });
      
    dr.add({xtype:	'button',
	id:     'btnCxp',
	cls:	 'boton-menu',
	tooltip: 'Cuentas por Pagar',
	text:    'Cuentas por Pagar',
	style:   slWidth ,
	handler: function(){
	    //var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=P&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
	    //addTab({id:'gridConten2', title:'Cuentas por Pagar', url:slUrl});
	    mostrarVentana("Cuentas por Pagar","P");
	    //fAgregarBotones("Pagar","P");
	    //var w = Ext.getCmp('pnlIzq');
	    //w.collapsed ? w.expand() : w.collapse();
	    }
    });
    dr.render(document.body, 'divIzq01');
    
    //fCargarTree();

//////////////// [ Controles de Panel Consultas ] ////////////////////
var frmConsulta = new Ext.FormPanel({
    labelWidth: 70,
    frame: false,
    id:'consulta',
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: false,
    width: 250,
	defaults: {width: 110},
	defaultType: 'datefield',
	items: [olBancos, olFechaIni, olFechaFin]
  });
frmConsulta.add({xtype:	'button',
	id:     'btnConsMovi',
	cls:	 'boton-menu',
	tooltip: 'Consulta movimientos del banco en el rango de fechas seleccionadas',
	text:    'Consultar',
	style:   slWidth ,
	handler: function(){
		    //debugger;
                    //var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=C&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
//                    addTab({id:'gridConten', title:'Cuentas por Cobrar', url:slUrl});
		    if (!Ext.getCmp("txt_bancos").value){
			Ext.Msg.alert('AVISO', "Seleccione un banco");
			return;
		    }
		    if (!Ext.getCmp("txt_fecIni").value){
			Ext.Msg.alert('AVISO', "Seleccione fecha de inicio");
			return;
		    }
		    if (!Ext.getCmp("txt_fecFin").value){
			Ext.Msg.alert('AVISO', "Seleccione fecha de fin");
			return;
		    }
		    var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecIni").value, slDateFmt), 'Y-m-d');
		    var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecFin").value, slDateFmt), 'Y-m-d');
		    var slUrl = "CoTrTr_salcxcgrid_movi.php?init=1&pAuxil=" + Ext.getCmp("txt_bancos").getValue() + "&fecIni=" + slFecI + "&fecFin=" + slFecF;
		    app.cart.paramDetalleCons ={pAuxil: Ext.getCmp("txt_bancos").getValue(), fecIni: slFecI, fecFin: slFecF};
		    addTab({id:'gridConsMoviFec', title:'Movimientos', url:slUrl, tip: 'Movimientos'});
	    }
    });


var rdComboBaseGeneral = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;
var dsCmbEstado = 	new Ext.data.Store({
        proxy: 		new Ext.data.HttpProxy({
                url: '../Ge_Files/GeGeGe_queryToXml.php',
                metod: 'POST'//,
                //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
        }),
        reader: 	rdComboBaseGeneral,
        baseParams: {id : 'CoTrTr_beneficiario'}
});
var olBeneficiario = new Ext.form.ComboBox({
                    fieldLabel:'Beneficiario',
                    id:'txtBeneficiario',
                    name:'txtBeneficiario',
                    width:90,
                    store: dsCmbEstado,
                    displayField:	'txt',
                    valueField:     'cod',
                    hiddenName:'estado',
                    selectOnFocus:	true,
                    typeAhead: 		true,
                    mode: 'remote',
                    minChars: 1,
                    triggerAction: 	'all',
                    forceSelection: true,
                    emptyText:'',
                    allowBlank:     false,
                    listWidth:      250,
                    labelWidth: 50
                });
    var olFechaIni2 = {	xtype:'datefield'
			,fieldLabel:'Fecha Inicio'
                        //,labelWidth:10
                        ,id:'txt_fecIni2'
			,name:'txt_fecIni2'
			,value: new Date().format('d-M-y')
			,emptyText:''
			,allowBlank:     false
			,format: slDateFmt
			,altFormats: slDateFmts
			,width:90
		    };
    var olFechaFin2 = {	xtype:'datefield'
			,fieldLabel:'Fecha Fin'
                        //,labelWidth:10
                        ,id:'txt_fecFin2'
			,name:'txt_fecFin2'
			,value: new Date().format('d-M-y')
			,emptyText:''
			,allowBlank:     false
			,format: slDateFmt
			,altFormats: slDateFmts
			,width:90
		    };
/*frmConsulta.add(
		{
                    xtype:'fieldset', title: 'Cuentas por Cobrar', autoHeight:true, defaultType: 'radio',
                    collapsible: true, width: 220,
		    frame:false,
                    items: [olFechaIni2, olFechaFin2, olBeneficiario
                            ,{xtype:	'button',
                                id:     'btnRepEst',
                                cls:	 'boton-menu',
                                tooltip: 'Consultar Estado de Cuenta',
                                text:    'Estado de Cuenta',
                                //style:   slWidth ,
				width:90,
                                handler: function(){
					    debugger;
                                            if (!Ext.getCmp("txtBeneficiario").value){
						Ext.Msg.alert('AVISO', "Seleccione persona");
						return;
					    }
					    
					    if (!Ext.getCmp("txt_fecIni2").value){
						Ext.Msg.alert('AVISO', "Seleccione fecha de inicio");
						return;
					    }
					    if (!Ext.getCmp("txt_fecFin2").value){
						Ext.Msg.alert('AVISO', "Seleccione fecha de fin");
						return;
					    }
					    var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecIni2").value, slDateFmt), 'Y-m-d');
					    var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecFin2").value, slDateFmt), 'Y-m-d');
					    var slUrl = "CoTrTr_estadoCuenta.php?init=1&pAuxil=" + Ext.getCmp("txtBeneficiario").getValue() + "&fecIni=" + slFecI + "&fecFin=" + slFecF;
					    app.cart.paramDetalleCons ={pAuxil: Ext.getCmp("txtBeneficiario").getValue(), fecIni: slFecI, fecFin: slFecF};
					    addTab({id:'gridConsEstCuenta', title:'Estado de Cuenta', url:slUrl, tip: 'Estado de Cuenta'});
                                    }
                            },{xtype:	'button',
                                id:     'btnConsCtaCobrar',
                                cls:	 'boton-menu',
                                tooltip: 'Consultar Cuentas por cobrar',
                                text:    'Cuentas por Cobrar',
                                style:   slWidth ,
                                handler: function(){
					    if (!Ext.getCmp("txt_fecIni2").value){
						Ext.Msg.alert('AVISO', "Seleccione fecha de inicio");
						return;
					    }
					    if (!Ext.getCmp("txt_fecFin2").value){
						Ext.Msg.alert('AVISO', "Seleccione fecha de fin");
						return;
					    }
					    var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecIni2").value, slDateFmt), 'Y-m-d');
					    var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecFin2").value, slDateFmt), 'Y-m-d');
					    var slUrl = "CoTrTr_detCuentasCobrar.php?init=1&fecIni=" + slFecI + "&fecFin=" + slFecF;
					    app.cart.paramDetalleCons ={ fecIni: slFecI, fecFin: slFecF};
					    addTab({id:'gridConsCtaCobrar', title:'Cuentas por cobrar', url:slUrl, tip: 'Cuentas por Cobrar'});
                                    }
                            }
			]
                }
		);*/

   

frmConsulta.render(document.body, 'divIzq02');


//////////////// [ Controles de Panel Reportes ] ////////////////////
//debugger;
frmReporte = new Ext.FormPanel({
        labelWidth: 50, // label settings here cascade unless overridden
        url:'',
        bodyStyle:'padding:5px 5px 0',
        autoheight:true,
        autowidth: true,
		border:false,
		frame:false,
        defaults: {width: 80},
        defaultType: 'datefield',
        items: [{xtype:'datefield', fieldLabel: 'Inicio',    name: 'txt_RepInicio', id: 'txt_RepInicio', width: 80, allowBlank:false
			    ,value: new Date().format('d-M-y'),format: slDateFmt,altFormats: slDateFmts },
		    {xtype:'datefield', fieldLabel: 'Fin', name: 'txt_RepFin',  id: 'txt_RepFin',  width: 80, align: "right", allowBlank:false
			    ,value: new Date().format('d-M-y'),format: slDateFmt,altFormats: slDateFmts}
		    ,{
			xtype:'checkbox'
			,fieldLabel: 'Excel'
			,name: 'chkExcel'
			,id: 'chkExcel'
			,text:'Formato Excel'
		    }
		    ,{text: 'Cierre de Caja'
			,xtype:'button'
			,cls:	 'boton-menu'
			,minWidth:100
			,handler: function(){
				var slExcel = '';
				if (true == Ext.getCmp("chkExcel").checked) slExcel = "&pExcel=1";
				var slFecIni = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_RepInicio").value, slDateFmt), 'Y-m-d');
				var slFecFin = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_RepFin").value, slDateFmt), 'Y-m-d');
//				var slUrl = "CoTrTr_cierrecaja.rpt.php?pFecIni="+slFecIni+"&pFecFin="+slFecFin+slExcel;
				var slUrl = "../Co_Files/CoTrTr_cierrecajaCta.rpt.php?pFecIni="+slFecIni+"&pFecFin="+slFecFin+slExcel;
				window.open(slUrl, "ppre1", 'width=1024, height=670, resizable=1, menubar=1');
			    }
		    }
		    ,{text: 'Resumen de Cheques'
			,xtype:'button'
			,cls:	 'boton-menu'
			,minWidth:100
			,handler: function(){
				var slExcel = '';
				if (true == Ext.getCmp("chkExcel").checked) slExcel = "&pExcel=1";
				var slFecIni = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_RepInicio").value, slDateFmt), 'Y-m-d');
				var slFecFin = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_RepFin").value, slDateFmt), 'Y-m-d');
				var slUrl = "CoTrTr_resumencheques.rpt.php?pFecIni="+slFecIni+"&pFecFin="+slFecFin+slExcel;
				window.open(slUrl, "ppre2", 'width=1024, height=670, resizable=1, menubar=1');
			    }
		    }
		    ,{text: 'Consolidado Cierre de Caja'
			,xtype:'button'
			,cls:	 'boton-menu'
			,minWidth:100
			//,disabled:(!app.cart.repConsol)
			,handler: function(){
				if (false == app.cart.repConsol){
				    Ext.Msg.alert('Alerta','Usted no tiene los permisos necesarios para visualizar el reporte.');
				    return;
				}
				var slExcel = '';
				if (true == Ext.getCmp("chkExcel").checked) slExcel = "&pExcel=1";
				var slFecIni = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_RepInicio").value, slDateFmt), 'Y-m-d');
				var slFecFin = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_RepFin").value, slDateFmt), 'Y-m-d');
				var slUrl = "CoTrTr_cierrecajageneral.rpt.php?pFecIni="+slFecIni+"&pFecFin="+slFecFin+slExcel;
				window.open(slUrl, "ppre3", 'width=1024, height=670, resizable=1, menubar=1');
			    }
		    }
		    ,{text: 'Consolidado Resumen de Cheques'
			,xtype:'button'
			,cls:	 'boton-menu'
			,minWidth:100
			//,disabled:(!app.cart.repConsol)
			,handler: function(){
				if (false == app.cart.repConsol){
				    Ext.Msg.alert('Alerta','Usted no tiene los permisos necesarios para visualizar el reporte.');
				    return;
				}
				var slExcel = '';
				if (true == Ext.getCmp("chkExcel").checked) slExcel = "&pExcel=1";
				var slFecIni = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_RepInicio").value, slDateFmt), 'Y-m-d');
				var slFecFin = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_RepFin").value, slDateFmt), 'Y-m-d');
				var slUrl = "CoTrTr_resumenchequesgeneral.rpt.php?pFecIni="+slFecIni+"&pFecFin="+slFecFin+slExcel;
				window.open(slUrl, "ppre3", 'width=1024, height=670, resizable=1, menubar=1');
			    }
		    },{text: 'Reporte de Cuentas por Cobrar'
			,xtype:'button'
			,cls:	 'boton-menu'
			,minWidth:100
			,handler: function(){
				/*if (false == app.cart.repConsol){
				    Ext.Msg.alert('Alerta','Usted no tiene los permisos necesarios para visualizar el reporte.');
				    return;
				}*/
				var slUrl = "CoTrTr_condRepCXC.php";
				window.open(slUrl, "ppre3", 'width=1024, height=670, resizable=1, menubar=1');
				
			    }
		    },{text: 'Reimpresion'
			,xtype:'button'
			,cls:	 'boton-menu'
			,minWidth:100
			,handler: function(){
				//#rev	esl	Reimpresion de Documentos (Comprobante,Cheque,Voucher)
				fReimpresion();
				
			    }
		    }
		    
		
	]
    });
frmReporte.render(document.body, 'divIzq03');
    /*new Ext.Button({
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
*/

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
      tabTip:pPar.tip,
      autoLoad:{url:pPar.url,
            params:{pObj: pPar.id},
            scripts: true,
            method: 'post'}
    }).show();
  }
  
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
  
var global_printer = null;  // it has to be on the index page or the generator page  always
function printmygridGO(obj){  global_printer.printGrid(obj);	} 
function printmygridGOcustom(obj){ global_printer.printCustom(obj);	}  	
/*function basic_printGrid(){
	    //debugger;
		global_printer = new Ext.grid.XPrinter({
			grid:grid1,  // grid object 
			pathPrinter:'../LibJs/ext/ux/printer',  	 // relative to where the Printer folder resides  
			//logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
			pdfEnable: true,  // enables link PDF printing (only save as) 
			hasrowAction:false, 
			localeData:{
				Title:'Movimientos',	
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
}*/
function basic_printGrid(gridPrint){
	    //debugger;
		global_printer = new Ext.grid.XPrinter({
			grid:gridPrint,  // grid object 
			pathPrinter:'../LibJs/ext/ux/printer',  	 // relative to where the Printer folder resides  
			//logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
			pdfEnable: true,  // enables link PDF printing (only save as) 
			hasrowAction:false, 
			localeData:{
				Title:'Movimientos',	
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
function basic_printGrid2(gridPrint, titulo){
	    //debugger;
		global_printer = new Ext.grid.XPrinter({
			grid:gridPrint,  // grid object 
			pathPrinter:'../LibJs/ext/ux/printer',  	 // relative to where the Printer folder resides  
			//logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
			pdfEnable: true,  // enables link PDF printing (only save as) 
			hasrowAction:false, 
			localeData:{
				Title:titulo,//'Movimientos',	
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


function fAgregarBotones(tipo, tipo2){
    //debugger;
   
    if (Ext.getCmp('cuentas')) Ext.getCmp('cuentas').destroy();
    /*var rdComboBase2 = new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;  // Origen de datos Generico
   
    var dsCuentas = new Ext.data.Store({
        proxy: 	new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php'				// Script de servidor generico que accede a BD
                    ,metod: 'POST'
                    //,extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}    //Parametros para obtener datos segun el contexto de la consulta sql
            }),
            reader: 	rdComboBase2,
            baseParams: {id : 'CoTrTr_bancos'}		// Parametro Basico: ID de consulta SQl (predefinida en PHP como variable de sesion)
    });
      
    var frmCuentas = new Ext.Panel({//Ext.form.FormPanel({
			title:'Cuentas por '+tipo
			,id : 'cuentas'
			,height:270
			,width:220
			,collapsible:true
			,layout: 'fit'//'form'
			,autoScroll: true
			,bodyStyle: 'margin-top:10px; text-align:left;'
			//,buttonAlign: 'left'			
			//,items:[olBoton]
		    });
   
   frmCuentas.add({
			    xtype:"multiselect",
			    //fieldLabel:"Multiselect",
			    id:"multiselect",
			    name:"multiselect"
			    ,store: dsCuentas
			    ,displayField:	'txt'
			    ,valueField:     'cod'
			    ,dataFields:["cod", "txt"]
			    ,data:[[123,"One Hundred Twenty Three"],
				    ["1", "One"], ["2", "Two"], ["3", "Three"], ["4", "Four"], ["5", "Five"],
				    ["6", "Six"], ["7", "Seven"], ["8", "Eight"], ["9", "Nine"]],
			    valueField:"code",
			    displayField:"desc",
			    ,width:220
			    ,height:200
			    ,allowBlank:true
			    ,legend:"Cuentas"
			    ,tbar:[{
				text:"Limpiar",
				handler:function(){
					Ext.getCmp("cuentas").findById("multiselect").reset();
				    }
			    }]
			    ,listeners: {'click': function (combo,record){
					//debugger;
					Ext.Msg.alert("Alerta",Ext.getCmp("multiselect").getValue());
				    }}
		});
		
    frmCuentas.render(document.body, 'divIzq01');*/
   
    
    olDat = Ext.Ajax.request({
	url: 'CoTrTr_salcxgridconsulta'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
		//debugger;
		var olRsp = eval("(" + pResp.responseText + ")");
		//debugger;
		var i = 0;
		var frmCuentas = new Ext.Panel({//Ext.form.FormPanel({
			title:'Cuentas por '+tipo
			,id : 'cuentas'
			,height:270
			,width:220
			,collapsible:true
			,layout: 'fit'//'form'
			,autoScroll: true
			,bodyStyle: 'margin-top:10px; text-align:left;'
			//,buttonAlign: 'left'			
			//,items:[olBoton]
		    });
		for (var i in olRsp)
		{
		    
		    if (IsNumeric(i)){
			//debugger;
			//var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo="+tipo2+"&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value + "&pCuenta=" + olRsp[i].codcuenta;
			olBoton1 = new Ext.Button({//new Ext.form.Label({//new Ext.Button({
			    text: olRsp[i].nombrcue
			    ,id: olRsp[i].codcuenta
			    ,name: tipo2
			    ,tooltip:olRsp[i].nombrcue
			    //,html:"<a href='#' onclick='addTab({id:'gridConten"+olRsp[i].codcuenta+"', title:'Cx"+tipo2+" "+olRsp[i].codcuenta+"', url:'"+slUrl+"', tip: '"+olRsp[i].nombrcue+"'});'>"+olRsp[i].nombrcue+"</a>"
			    //,style:"display:block;"
			    ,width: 200
			    ,minWidth:200
			    ,maxWidth:200
			    //,style:'text-align:left !important;'
			    ,cls:'x-btn-izq'//'x-btn-left'
			    //,href:'#'
			    //,ctCls:'textLeft'//'x-btn-left'
			    //,menuAlign: 'l'
			    //,align: 'left'
			    ,handler: function(btn){
				//debugger;
				var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo="+btn.name+"&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value + "&pCuenta=" + btn.id;
	                        addTab({id:'gridConten'+btn.id, title:'Cx'+btn.name+' '+btn.id, url:slUrl, tip: btn.text});
				//Ext.Msg.alert(btn.id);
			    }
			});
			//olBoton1.getEl().addClass('textLeft');

			//debugger;
			frmCuentas.add(olBoton1);
		    }//else{return;}
		}
		//frmCuentas.doLayout();
		/*frmCuentas.add({
			    xtype:"multiselect",
			    //fieldLabel:"Multiselect",
			    id:"multiselect",
			    name:"multiselect",
			    dataFields:["code", "desc"], 
			    data:[[123,"One Hundred Twenty Three"],
				    ["1", "One"], ["2", "Two"], ["3", "Three"], ["4", "Four"], ["5", "Five"],
				    ["6", "Six"], ["7", "Seven"], ["8", "Eight"], ["9", "Nine"]],
			    valueField:"code",
			    displayField:"desc",
			    width:220,
			    height:200,
			    allowBlank:true
			    ,legend:"Cuentas"
			    ,listeners: {'click': function (combo,record){
					//debugger;
					Ext.Msg.alert("Alerta",Ext.getCmp("multiselect").getValue());
				    }}
		});*/
		
		frmCuentas.render(document.body, 'divIzq01');
		if (0 == i)
		    Ext.Msg.alert("Alerta","No hay cuentas por "+tipo);
		
	    }
	}
	,success: function(pRes,pPar){
	    //Ext.getCmp("ilNumCheque").setValue(0)
	    //Ext.getCmp("flSaldo").setValue(0)
	    }
	,failure: function(pObj, pRes){
	    //Ext.getCmp("ilNumCheque").setValue(0)
	    //Ext.getCmp("flSaldo").setValue(0)
	    }
	,params: {pTipo: tipo2}
    })
    
    /*frmCuentas.add(olBoton);*/
    //frmCuentas.render(document.body, 'divIzq01');
    //Ext.getCmp('busqueda').add(frmCuentas);
    //Ext.getCmp('busqueda').doLayout();
};

function fAccion(){
    Ext.Msg.alert(1);
}


function IsNumeric(sText){
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;

 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
   }
   
function fCargaPermiso($key, $var, $bool){
        
    var olDat = Ext.Ajax.request({
	url: 'CoTrTr_variablesglobales'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                //debugger;
		$var = olRsp.valor;
		app.cart.repConsol = olRsp.valor;
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
	,params: {pKey: $key, pBool: $bool}//, pAux:ilAuxi, pBan:true, pTip: Ext.getCmp("slFormaPago").getValue()}
    })
}


    //debugger;
    olTreeLoader = new Ext.tree.TreeLoader({
        dataUrl:'CoTrTr_cajaconsulta.php',
        baseParams: {pPrdc: 0, id:'CoTrTr_lista', pArbol:'A', pNivel:-1},
        uiProviders:{'col': Ext.tree.ColumnNodeUI }
    });
    olTreeLoader.on("beforeload", function(pTLdr, pNode) {
	//debugger;
        /*var ilAnio =fmQry.form.findField("txt_AnioOp").getValue();
        var ilSem  =fmQry.form.findField("txt_Seman").getValue();
        if (ilSem <1)
            return false;*/
        if (pNode.isRoot) ilNiv=0;
        else ilNiv = Number(pNode.attributes.txt_tipo); //ilNiv = pNode.getDepth() -1;
        if (ilNiv >= 0 ) {
            var olParams = fAnalizaArbol(pNode, tree.tipo, ilNiv);
            pTLdr.baseParams = olParams;
        }
        if (ilNiv < 1 ) pTLdr.dfault = {pPrdc:0}
		/*if (tree.tipo != "E") {
			if (ilNiv == 1 ) pTLdr.dfault = {pPrdc:pNode.attributes.id}; // en el primer Nodo define el producto
			pTLdr.baseParams.pPrdc= 	 pTLdr.dfault.pPrdc;
		}*/
        pTLdr.baseParams.id= 	 'CoTrTr_lista';
        pTLdr.baseParams.pNivel=  ilNiv;
	pTLdr.baseParams.pTipo = ilNiv >= 0 ? pNode.attributes.tipo2 : '';
        /*pTLdr.baseParams.pAnioOp= ilAnio;
        pTLdr.baseParams.pSeman=  ilSem;*/
        pTLdr.baseParams.pArbol=  'E';//tree.tipo;
    }, this);
    
    var tree = new Ext.tree.ColumnTree({
        //el:'divTabDet', //'divDet',
        autoWidth:true,
        autoHeight:true,
        rootVisible:false,
        animCollapse : false,
        animate : false,
        autoScroll:true,
	height:270,
	width:220,
        //title: 'Cuentas',
        columns:[
        {header:'Descripcion', width:70, dataIndex:'tipo'},
        {header:'Nombre', width:130, align: "right", dataIndex:'nombrcue'},
        {header:'Cuenta', width:50, align: "right", dataIndex:'codcuenta'}/*,
        {header:'Valor', width:80, align: "right", dataIndex:'txt_valor'}*/
    ],
        loader: olTreeLoader,
        root: new Ext.tree.AsyncTreeNode({id:0, text:'Detalles'})
    });
	treeEd = new Ext.tree.TreeEditor(tree, new Ext.form.Field({
        cancelOnEsc:true
        , completeOnEnter:true
        , ignoreNoChange:true
        /*, stateEvents:
        [{change:{scope:this, fn:fModifNode}}]*/
    }), 2);
 
 
function fCargarTree(){
 //tree.tipo = "E";
    olTreeLoader.load(tree.root); 
 
    var frmCtas = new Ext.Panel({//Ext.form.FormPanel({
			title:'Cuentas'
			,id : 'cuentas'
			,height:270
			,width:220
			,collapsible:true
			,layout: 'fit'//'form'
			,autoScroll: true
			,bodyStyle: 'margin-top:10px; text-align:left;'
			//,buttonAlign: 'left'			
			,items:[tree]
		    });
 
 
 
    frmCtas.render(document.body, 'divIzq01');
    
    
}


function fAnalizaArbol(pNode, pTipo, pNivel){
    //debugger;
   var olParams = {};
   //olParams.pRope = pNode.parentNode.attributes.txt_rope;
//   olParams.pPrdc = pNode.attributes.tipo;
   olParams.pRope = pNode.attributes.tipo;
    /*switch (pTipo) {
        case "E":
            switch (pNivel) {
               case 1:
                    olParams.pRope = pNode.attributes.txt_rope
                    //olParams.pPran = pNode.attributes.txt_precio
                    break;
                case 2:
                    olParams.pRope = pNode.parentNode.attributes.txt_rope
					olParams.pPrdc = pNode.attributes.tipo	
                    break;
                case 3:
                    olParams.pRope = pNode.parentNode.parentNode.attributes.txt_rope
					olParams.pPrdc = pNode.parentNode.attributes.item
					olParams.pProd = pNode.attributes.id	
                    break;
                case 4:
                    olParams.pRope = pNode.parentNode.parentNode.parentNode.attributes.txt_rope
					olParams.pPrdc = pNode.parentNode.parentNode.attributes.item
					olParams.pProd = pNode.parentNode.attributes.id
					olParams.pEmpq = pNode.attributes.id						
                    break;                
                case 5:
                    olParams.pRope = pNode.parentNode.parentNode.parentNode.parentNode.attributes.txt_rope
					olParams.pPrdc = pNode.parentNode.parentNode.parentNode.attributes.item
					olParams.pProd = pNode.parentNode.parentNode.attributes.id
					olParams.pEmpq = pNode.parentNode.attributes.id						
                    olParams.pTarj = pNode.attributes.id
                    olParams.pPran = pNode.attributes.txt_precio
                    break;                
                default:
                    break;            }
            break;
        case "A":
            switch (pNivel) {
                case 1:
                    olParams.pPrdc = pNode.attributes.id;
                    //olParams.pPran = pNode.attributes.txt_precio
                    break;
                case 2:
                    olParams.pPrdc = pNode.parentNode.attributes.id;
                    olParams.pProd = pNode.attributes.id;
                    //olParams.pPran = pNode.attributes.txt_precio
                    break;
                case 3:
                    olParams.pPrdc = pNode.parentNode.parentNode.attributes.id;
                    olParams.pProd = pNode.parentNode.attributes.id;
                    olParams.pPran = pNode.attributes.txt_precio
                    olParams.pRope = pNode.attributes.txt_rope
                    //olParams.pPran = pNode.attributes.txt_precio
                    break;
                case 4:
                    olParams.pPrdc = pNode.parentNode.parentNode.parentNode.attributes.id;
                    olParams.pProd = pNode.parentNode.parentNode.attributes.id;
                    olParams.pRope = pNode.parentNode.attributes.txt_rope;
                    olParams.pEmpq = pNode.attributes.id
                    //olParams.pPran = pNode.attributes.txt_precio
                    break;                
                case 5:
                    olParams.pPrdc = pNode.parentNode.parentNode.parentNode.parentNode.attributes.id;
                    olParams.pProd = pNode.parentNode.parentNode.parentNode.attributes.id;
                    olParams.pRope = pNode.parentNode.parentNode.attributes.txt_rope;
                    olParams.pEmpq = pNode.parentNode.attributes.id
                    olParams.pTarj = pNode.attributes.id
                    olParams.pPran = pNode.attributes.txt_precio
                    break;                
                default:
                    break;
            }
            break;
        case "C":
            switch (pNivel) {
                case 1:
                    olParams.pPrdc = pNode.attributes.id;
                case 2:
                    olParams.pPrdc = pNode.parentNode.attributes.id;
                    olParams.pCont = pNode.attributes.txt_conte;
                    break;
                case 3:
                    olParams.pPrdc = pNode.parentNode.parentNode.attributes.id;
                    olParams.pCont = pNode.parentNode.attributes.txt_conte;
                    olParams.pMarc = pNode.attributes.id;
                    break;
                case 4:
                    olParams.pPrdc = pNode.parentNode.parentNode.parentNode.attributes.id;
                    olParams.pCont = pNode.parentNode.parentNode.attributes.txt_conte;
                    olParams.pMarc = pNode.parentNode.attributes.id;
                    olParams.pEmpq = pNode.attributes.id;
                    break;                
                case 5:
                    olParams.pPrdc = pNode.parentNode.parentNode.parentNode.parentNode.attributes.id;
                    olParams.pCont =pNode.parentNode.parentNode.parentNode.attributes.txt_conte;
                    olParams.pMarc =pNode.parentNode.parentNode.attributes.id;
                    olParams.pEmpq =pNode.parentNode.attributes.id;
                    olParams.pTarj =pNode.attributes.txt_precio
                    break;                
                default:
            }            
    }*/
    return olParams;    
}

function mostrarVentana(pTitulo, pTipoCta){
    //debugger;
    app.cart.tipoCta = pTipoCta;
    goXmlR= new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;  // Origen de datos Generico
    
    goDsEmpr = new Ext.data.Store({
	proxy: 	new Ext.data.HttpProxy({
			url: '../Ge_Files/GeGeGe_queryToXml.php'
                    ,metod: 'POST'
		    ,extraParams: {pTipo : app.cart.tipoCta}
		}),
		reader: 	goXmlR,
		baseParams: {id : "CoTrTr_cuentas"}		// Parametro Basico: ID de consulta SQl (predefinida en PHP como variable de sesion)
    });
    if (Ext.getCmp('winCuentas')) Ext.getCmp('winCuentas').destroy();
        
    var win = new Ext.Window({
            title:pTitulo
            ,layout:'anchor'//'fit'
	    ,anchorSize: {width:350, height:80}
            ,width:335
            ,height:130
	    ,id: "winCuentas"
            ,items: //frm
	    [
		 {
			xtype:"multiselect"
			,fieldLabel:"Cuentas"
			,hideLabel:true
			,name:"cns_cuentas"
			,id:"cns_cuentas"
			,store: goDsEmpr
		    /*,dataFields:["cod", "txt"]*/		    
			,valueField:"cod"
			,displayField:"txt"
			//,width:340
			//,height:80
			//,allowBlank:true
			//,anchor:'100%'
			//,autoScroll: true
			//,overflow: 'auto'
			,width:320
			,anchor:'right 20%'
		    }
	    ]
	    ,autoScroll: false
	    ,collapsible : true
        });
        win.show();
	
    goDsEmpr.baseParams.pTipo=app.cart.tipoCta;
    goDsEmpr.load();
    
    Ext.getCmp("cns_cuentas").on("dblclick", function(vw, index, node, e){
	//debugger;
	var pCuenta = Ext.getCmp("cns_cuentas").store.data.items[index].data.cod;
	var pNombre = Ext.getCmp("cns_cuentas").store.data.items[index].data.txt;
	var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo="+app.cart.tipoCta+"&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value + "&pCuenta=" + pCuenta;
	addTab({id:'gridConten'+pCuenta, title:'Cx'+app.cart.tipoCta+' '+pCuenta, url:slUrl, tip: pNombre});
			//Ext.Msg.alert('1',Ext.getCmp("cns_cuentas").store.data.items[index].id);
    });
    
    /*aMultiselect.on('click', function (vw, index, node, e) {
                      alert(index);
                }); */


}
function fReimpresion(){
    var pReimp = new Ext.FormPanel({
	
	 title:'Reimpresion de Cheque'
	 ,id:'pReimp'
	,items:[{
		    fieldLabel	:'Tipo de Comprobante'
		    ,id		:'TipoComp'
		    ,xtype	:'genCmbBox'
		    ,forceSelection :false
		    ,minChars	:1
		    ,allowBlank	:false
		    ,sqlId	:'CoTrTr_TiposComp'
		    ,width	:'200'
		    ,listWidth	:200
		},
		{
		    fieldLabel	:'Numero de Comprobante'
		    ,id		:'NumComp'
		    ,xtype	:'numberfield'
		    ,allowBlank	:false
		    ,allowDecimals:false
		    ,decimalPrecision:0
		    ,width	:'200'
		    ,disabled	:false
		    ,listeners: {'change': function (field,newValue,oldValue){
				    fRegNumero()
				}}
		}
	]
	,height:'150'
	 });
    
	var pBotones = new Ext.FormPanel({
	 items:[ {
		    text	:'Cheque'
		    //,id		:'btn_CrearMemo'
		    ,xtype	:'button'
		    ,width	:60
		    ,iconCls	:'iconNuevo'
		    ,handler	: function (){
				 if (!Ext.getCmp("pReimp").getForm().isValid()) {
				    Ext.Msg.alert('ATENCION!', 'Hay Informacion incompleta o invalida');
				    return false; 
				}
				 pTip=  Ext.getCmp("TipoComp").getSelectedRecord().data.cod;
				 pComp = Ext.getCmp("NumComp").getValue();
				 Url = Ext.getCmp("TipoComp").getXmlField('cla_Cheque');
				 window.open(Url+"?&pQryCom=com_TipoComp='"+pTip+"' AND com_NumComp="+pComp);
		    }
		 }]
    });
    
    /*if (!olPnlMemorando ){
	    olPnlMemorando = fCreaMemorando();
	}*/
    
	pnlReimpresion = new Ext.Panel({
			    items:[pReimp,pBotones] 
		       });
    
	if (! Ext.getCmp('tbReimpresion')){
	    addTab1({id:"tbReimpresion",title: 'Reimpresion',url:'',items:[pnlReimpresion]});
	    Ext.getCmp("tbReimpresion").doLayout();    
	}
    
}

