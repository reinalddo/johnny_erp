/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Gina Franco
 *  @date       30/Abr/09
 *  *****************************************************************************************************************
 *  *****************************************************************************************************************
 *  *****************************************************************************************************************
 *  @rev	fah 29/04/09	Añadir propiedad tabindex a los campos del formulario para darles secuencialidad
*/
Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
    Ext.QuickTips.init();
    //app.cart
    Ext.namespace("app", "app.cl");
    
    olDet=Ext.get('divDet');
    var slWidth="width:250px; text-align:'left'";
    
   /*
    *   Ejemplo de manejo de ComboBox para opciones de filtrado de datos de emb
    ***/
    var slDateFmt  ='d-m-y';
    var slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d';
    var rdComboBase = new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;  // Origen de datos Generico
    
    dsCmbCuentas = new Ext.data.Store({
        proxy: 	new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php'				// Script de servidor generico que accede a BD
                    ,metod: 'POST'
                    //,extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}    //Parametros para obtener datos segun el contexto de la consulta sql
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'CoTrCl_cuentas'}		// Parametro Basico: ID de consulta SQl (predefinida en PHP como variable de sesion)
    });
    
    dsCmbBancos = new Ext.data.Store({
        proxy: 	new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php'				// Script de servidor generico que accede a BD
                    ,metod: 'POST'
                    //,extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}    //Parametros para obtener datos segun el contexto de la consulta sql
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'CoTrCl_bancos'}		// Parametro Basico: ID de consulta SQl (predefinida en PHP como variable de sesion)
    });
    
    
    var olCuenta = new Ext.form.ComboBox({
			fieldLabel:'Cuenta'
                        ,labelWidth:10
                        ,id:'txt_cuenta'
			,name:'txt_cuenta'
			,width:160
			,store: dsCmbCuentas
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'codCuenta'
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
    
    var olBancos = new Ext.form.ComboBox({
			fieldLabel:'Bancos'
                        ,labelWidth:10
                        ,id:'txt_bancos'
			,name:'txt_bancos'
			,width:160
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
    
        
    var olFechaCorte = {	xtype:'datefield'
			,fieldLabel:'Fecha Corte'
                        ,labelWidth:10
                        ,id:'txt_fecCorte'
			,name:'txt_fecCorte'
			,width:160
			,emptyText:''
			,allowBlank:     false
		    };

   
Ext.getDom('north').innerHTML = '<div id="north_left"></div><div id="north_right"></div>';

//calendario fecha de corte
    var dr = new Ext.FormPanel({
    labelWidth: 40
    ,frame: false
    ,title: '' 
    ,id:'frmBuscar'
    ,bodyStyle:'padding:5px 5px 0'
    ,border: false
    ,width: 250
	//,defaults: {width: 160}
	,items: [olCuenta, olBancos, olFechaCorte]
      });
    var slWidth="width:80%; text-align:'center'";
    dr.add({xtype:	'button',
	id:     'btnCxc',
	cls:	 'boton-menu',
	tooltip: 'Busqueda de Conciliaciones',
	text:    'Buscar Conciliacion',
	style:   slWidth ,
	handler: function(){
		    //debugger;
		    if (!Ext.getCmp("txt_cuenta").value){
			Ext.Msg.alert('AVISO', "Seleccione cuenta");
			return;
		    }
		    if (!Ext.getCmp("txt_bancos").value){
			Ext.Msg.alert('AVISO', "Seleccione cuenta");
			return;
		    }
                    var slUrl = "CoTrCl_conciliaciongrid.php?init=1&pAuxil=" + Ext.getCmp("txt_bancos").getValue() + "&pCuenta=" + Ext.getCmp("txt_cuenta").getValue();
		    app.cl.paramDetalle ={pAuxil: Ext.getCmp("txt_bancos").getValue()
					    ,pCuenta: Ext.getCmp("txt_cuenta").getValue()};
                    addTab({id:'gridConc', title:'Conciliacion', url:slUrl});
                    //var w = Ext.getCmp('pnlIzq');
					//w.collapsed ? w.expand() : w.collapse();
	    }
    });
    dr.add({xtype:	'button'
	,id:     'btnNuevo'
	,cls:	 'boton-menu'
	,tooltip: 'Nueva Conciliacion'
	,text:    'Nuevo'
	,style:   slWidth 
	,handler: function(){
		    //debugger;
		    if (!Ext.getCmp("txt_fecCorte").value){
			Ext.Msg.alert('AVISO', "Seleccione fecha de corte");
			return;
		    }
		    var slFec = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecCorte").value, "d/m/Y"), 'Y-m-d');
		    var slUrl = "CoTrCl_conciliacionnueva.php?init=1&pAuxil=" + Ext.getCmp("txt_bancos").getValue() + "&fecCorte=" + slFec + "&pCuenta=" + Ext.getCmp("txt_cuenta").getValue();
		    app.cl.paramDetalle ={fecCorteNuevo: slFec};
		    addTab({id:'gridConcNueva', title:'Nuevo', url:slUrl});
		    fIngresaCabConcil(slFec);
	    }
    });
    
    dr.render(document.body, 'divIzq01');
    
    
    //////////////// [ Controles de Panel Consultas ] ////////////////////
var dsCmbBancosCons = new Ext.data.Store({
        proxy: 	new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php'				// Script de servidor generico que accede a BD
                    ,metod: 'POST'
                    //,extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}    //Parametros para obtener datos segun el contexto de la consulta sql
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'CoTrCl_bancos'}		// Parametro Basico: ID de consulta SQl (predefinida en PHP como variable de sesion)
    });
   
var olBancosCons = new Ext.form.ComboBox({
		     fieldLabel:'Bancos'
		     //,labelWidth:10
		     ,id:'txt_bancosCons'
		     ,name:'txt_bancosCons'
		     //,width:160
		     ,store: dsCmbBancosCons
		     ,displayField:	'txt'
		     ,valueField:     'cod'
		     ,hiddenName:'codAuxiliar2'
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
    
        
var olFechaIniCons = {	xtype:'datefield'
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
var olFechaFinCons = {	xtype:'datefield'
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
	items: [olBancosCons, olFechaIniCons, olFechaFinCons]
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
		    if (!Ext.getCmp("txt_bancosCons").value){
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
		    var slUrl = "CoTrCl_conciliacionConsMoviNoLibros.php?init=1&pAuxil=" + Ext.getCmp("txt_bancosCons").getValue() + "&fecIni=" + slFecI + "&fecFin=" + slFecF;
		    app.cl.paramDetalleCons ={pAuxil: Ext.getCmp("txt_bancosCons").getValue(), fecIni: slFecI, fecFin: slFecF};
		    addTab({id:'gridConsMoviFec', title:'Movimientos', url:slUrl, tip: 'Movimientos'});
	    }
    });
frmConsulta.render(document.body, 'divIzq02');
    
    
//////////////// [ Controles de Panel Reportes ] ////////////////////
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
        items: [{
            xtype:'fieldset', title: 'Movimientos Anulados', autoHeight:true, defaultType: 'datefield',
            collapsible: true, width: 200,
			frame:false,
            items: [{xtype:'datefield', fieldLabel: 'Inicio',    name: 'txt_RepInicio', id: 'txt_RepInicio', width: 80, allowBlank:false
			    ,value: new Date().format('d-M-y'),format: slDateFmt,altFormats: slDateFmts },
		    {xtype:'datefield', fieldLabel: 'Fin', name: 'txt_RepFin',  id: 'txt_RepFin',  width: 80, align: "right", allowBlank:false
			    ,value: new Date().format('d-M-y'),format: slDateFmt,altFormats: slDateFmts}
		    ,{text: 'Ver'
			,xtype:'button'
			,minWidth:100
			,handler: function(){
				var slFecIni = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_RepInicio").value, slDateFmt), 'Y-m-d');
				var slFecFin = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_RepFin").value, slDateFmt), 'Y-m-d');
				var slUrl = "CoTrCl_anulaciondiferida.rpt.php?pInicio="+slFecIni+"&pFin="+slFecFin;
				window.open(slUrl, "ppre", 'width=1024, height=670, resizable=1, menubar=1');
			    }
		    }
		    ]
        }]
    });
    
    var btnSalDiario = new Ext.Button({
        text: 'Saldos Diarios'
	,minWidth:100
        ,handler: function(){
	    var slUrl = "../Co_Files/CoTrTr_saldiario.rpt.php";
            window.open(slUrl, "ppre", 'width=1024, height=670, resizable=1, menubar=1');
	}
    });//.render(document.body, 'divIzq03');
    frmReporte.add(btnSalDiario);
    	
    var btnConc = new Ext.Button({
        text: 'Conciliacion'
	,minWidth:100
        ,handler: function(){
	    Ext.Msg.alert("Alerta","Pendiente");
	}
    });//.render(document.body, 'divIzq03');
    frmReporte.add(btnConc);
    
    var btnLibBanco = new Ext.Button({
	text: 'Libro Bancos'
	,minWidth:100
        ,handler: function(){
	    Ext.Msg.alert("Alerta","Pendiente");
	}
    });//.render(document.body, 'divIzq03');
    frmReporte.add(btnLibBanco);
    
    frmReporte.render(document.body, 'divIzq03');

}   
)//on REady

/*
*	Agregador de componentes a un panel
*/


function addComponente(pPar){
      //debugger;
      var olPanel = Ext.getCmp("pnlIzq");
      olPanel.add({
      id: pPar.id,
      //title: pPar.title,
      //layout:'fit',
      //closable: true,
      //collapsible: true,
      autoLoad:{url:pPar.url,
            params:{pObj: "frmBuscar"},//"pnlIzq"},
            scripts: true,
            method: 'post'}
    }).show();    
  }

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
    
  }
  
var global_printer = null;  // it has to be on the index page or the generator page  always
function printmygridGO(obj){  global_printer.printGrid(obj);	} 
function printmygridGOcustom(obj){ global_printer.printCustom(obj);	}  	
function basic_printGrid(oGrid){
		global_printer = new Ext.grid.XPrinter({
			grid:oGrid,  // grid object 
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

function fIngresaCabConcil(fechaCorte) {
        //debugger;
        var olParam= {
            id : "CoTrCl_conciliacioning",
            fecCorte : fechaCorte,
            //fecLibros  : rec.data.det_FecLibros.dateFormat("Y-m-d"),
            //regNumero  : rec.data.com_RegNumero,  // Requiere Rope en lugar de Emb
            auxil : Ext.getCmp("txt_bancos").getValue(),
            cuenta  : Ext.getCmp("txt_cuenta").getValue()//"100100"
        }
        Ext.Ajax.request({
            url: 'CoTrCl_conciliacionnuevagrabar.php',
            success: function(pResp, pOpt){
                //debugger;
                //Ext.Msg.alert('AVISO', "Comprobante Actualizado "+comp);
                
                //olRec.tmp_Valor = olRec.tmp_Cantidad * olRec.tmp_PrecUnit
            },
            failure: function(pResp, pOpt){
		//debugger;
                Ext.Msg.alert('AVISO', "Error al ingresar conciliacion "+fechaCorte);
            },
            headers: {
               'my-header': 'foo'
            },
            params: olParam,
            scope:  this
        });
    }

