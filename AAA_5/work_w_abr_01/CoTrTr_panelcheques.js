/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Gina Franco
 *  @date       25/Jun/09 
*/
Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    //Ext.namespace("app", "app.cart");
    Ext.namespace("app", "app.cheque");
    fCargaUsuario()
    
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
	defaults: {width: 100, labelWidth:30},
	defaultType: 'datefield',
        labelWidth:30,
	items: [{
	      fieldLabel: 'Inicio',
	      name: 'txtFecIni',
	      id: 'txtFecIni',
	      value: new Date().format('d-M-y'),
	      format:'d-M-y',
	      endDateField: 'enddt' // id of the end date field
              ,format: slDateFmt
	      ,altFormats: slDateFmts
            },{
	      fieldLabel: 'Fin',
	      name: 'txtFecFin',
	      id: 'txtFecFin',
	      value: new Date().format('d-M-y'),
	      format:'d-M-y',
	      endDateField: 'enddt' // id of the end date field
              ,format: slDateFmt
	      ,altFormats: slDateFmts
            }
        ]
      });
    var slWidth="width:99%; text-align:'left'";
    dr.add({xtype:	'button',
	id:     'btnUbic',
	cls:	 'boton-menu',
	tooltip: 'Define Ubicacion',
	text:    'Definir Ubicacion',
	style:   slWidth ,
	handler: function(){
                    var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecIni").value, slDateFmt), 'Y-m-d');
		    var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecFin").value, slDateFmt), 'Y-m-d');
		    
                    var slUrl = "CoTrTr_chequesubicacion.php?init=1&pFecIni="+ slFecI+"&pFecFin="+slFecF;
                    addTab({id:'gridConten', title:'Ubicacion de Cheques', url:slUrl});
			//fAgregarBotones("Cobrar","C");
                    //var w = Ext.getCmp('pnlIzq');
					//w.collapsed ? w.expand() : w.collapse();
	    }
    });
    
    
    
    dr.add({xtype:	'button',
	id:     'btnConfir',
	cls:	 'boton-menu',
	tooltip: 'Confirmar Recepcion',
	text:    'Confirmar Recepcion',
	style:   slWidth ,
	handler: function(){
                var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecIni").value, slDateFmt), 'Y-m-d');
                var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecFin").value, slDateFmt), 'Y-m-d');
                
                var slUrl = "CoTrTr_chequesconfirmar.php?init=1&pFecIni="+ slFecI+"&pFecFin="+slFecF;
                addTab({id:'gridConfirmar', title:'Confirmar / Cheques', url:slUrl});
	    }
    });
    
    dr.add({xtype:	'button',
	id:     'btnEst',
	cls:	 'boton-menu',
	tooltip: 'Define Estado',
	text:    'Definir Estado',
	style:   slWidth ,
	handler: function(){
                    var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecIni").value, slDateFmt), 'Y-m-d');
		    var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecFin").value, slDateFmt), 'Y-m-d');
		    
                    var slUrl = "CoTrTr_chequesestado.php?init=1&pFecIni="+ slFecI+"&pFecFin="+slFecF;
                    addTab({id:'gridEstado', title:'Estado / Cheques', url:slUrl});
	    }
    });
    
    dr.add({xtype:	'button',
	id:     'btnEstCon',
	cls:	 'boton-menu',
	tooltip: 'Programacion',
	text:    'Programacion',
	style:   slWidth ,
	handler: function(){
                    var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecIni").value, slDateFmt), 'Y-m-d');
		    var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecFin").value, slDateFmt), 'Y-m-d');
		    
                    var slUrl = "CoTrTr_chequeEstadoConf.php?init=1&pFecIni="+ slFecI+"&pFecFin="+slFecF;
                    addTab({id:'gridEstadoConf', title:'Prog. / Cheques', url:slUrl});
	    }
    });
    
    
    dr.add({xtype:	'button',
	id:     'btnArchivo',
	cls:	 'boton-menu',
	tooltip: 'Control y Generacion de Archivo',
	text:    'Control y Gen. Archivo',
	style:   slWidth ,
	handler: function(){
                    var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecIni").value, slDateFmt), 'Y-m-d');
		    var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecFin").value, slDateFmt), 'Y-m-d');
		    
                    var slUrl = "CoTrTr_chequeArchivo.php?init=1&pFecIni="+ slFecI+"&pFecFin="+slFecF;
                    addTab({id:'gridArchivo', title:'Archivo / Cheques', url:slUrl});
	    }
    });
    
    dr.render(document.body, 'divIzq01');

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
	items: [olFechaIni, olFechaFin]
  });
frmConsulta.add({xtype:	'button',
	id:     'btnConsCheEnv',
	cls:	 'boton-menu',
	tooltip: 'Consulta cheques enviados',
	text:    'Cheques enviados',
	style:   slWidth ,
	handler: function(){
		    //debugger;
                    //var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=C&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
//                    addTab({id:'gridConten', title:'Cuentas por Cobrar', url:slUrl});
		    
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
		    var slUrl = "CoTrTr_chequesconsubicacion.php?init=1&fecIni=" + slFecI + "&fecFin=" + slFecF + "&env=1";
		    //app.cart.paramDetalleCons ={pAuxil: Ext.getCmp("txt_bancos").getValue(), fecIni: slFecI, fecFin: slFecF};
		    addTab({id:'gridConsCheEnv', title:'Cheques enviados', url:slUrl, tip: 'Cheques enviados'});
	    }
    });
frmConsulta.add({xtype:	'button',
	id:     'btnConsCheRec',
	cls:	 'boton-menu',
	tooltip: 'Consulta cheques recibidos',
	text:    'Cheques recibidos',
	style:   slWidth ,
	handler: function(){
		    //debugger;
                    //var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=C&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
//                    addTab({id:'gridConten', title:'Cuentas por Cobrar', url:slUrl});
		    
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
		    var slUrl = "CoTrTr_chequesconsubicacion.php?init=1&fecIni=" + slFecI + "&fecFin=" + slFecF + "&env=0";
		    //app.cart.paramDetalleCons ={pAuxil: Ext.getCmp("txt_bancos").getValue(), fecIni: slFecI, fecFin: slFecF};
		    addTab({id:'gridConsCheRec', title:'Cheques recibidos', url:slUrl, tip: 'Cheques recibidos'});
	    }
    });

frmConsulta.add({xtype:	'button',
	id:     'btnConsCheSub',
	cls:	 'boton-menu',
	tooltip: 'Consulta cheques subidos',
	text:    'Cheques subidos',
	style:   slWidth ,
	handler: function(){
		    //debugger;
                    //var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=C&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
//                    addTab({id:'gridConten', title:'Cuentas por Cobrar', url:slUrl});
		    
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
		    var slUrl = "CoTrTr_cheque_consSubidos.php?init=1&fecIni=" + slFecI + "&fecFin=" + slFecF + "&env=0";
		    //app.cart.paramDetalleCons ={pAuxil: Ext.getCmp("txt_bancos").getValue(), fecIni: slFecI, fecFin: slFecF};
		    addTab({id:'gridConsCheSub', title:'Cheques subidos', url:slUrl, tip: 'Cheques subidos'});
	    }
    });

frmConsulta.add({xtype:	'button',
	id:     'btnConsChePend',
	cls:	 'boton-menu',
	tooltip: 'Consulta cheques pendientes',
	text:    'Cheques pendientes',
	style:   slWidth ,
	handler: function(){
		    //debugger;
                    //var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=C&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
//                    addTab({id:'gridConten', title:'Cuentas por Cobrar', url:slUrl});
		    
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
		    var slUrl = "CoTrTr_cheque_consPend.php?init=1&fecIni=" + slFecI + "&fecFin=" + slFecF + "&env=0";
		    //app.cart.paramDetalleCons ={pAuxil: Ext.getCmp("txt_bancos").getValue(), fecIni: slFecI, fecFin: slFecF};
		    addTab({id:'gridConsChePend', title:'Cheques pendientes', url:slUrl, tip: 'Cheques pendientes'});
	    }
    });

frmConsulta.render(document.body, 'divIzq02');


//////////////// [ Controles de Panel Reportes ] ////////////////////
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
        baseParams: {id : 'CoTrTr_estado'}
});

var olEstadoRep = new Ext.form.ComboBox({
                    fieldLabel:'Estado',
                    id:'txtestado',
                    name:'txtestado',
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
var frmReporte= new Ext.FormPanel({
    labelWidth: 70,
    frame: false,
    id:'reporte',
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: false,
    width: 250,
	defaults: {width: 110},
	defaultType: 'radio',
	items: [{xtype:'datefield',
                    fieldLabel: 'Inicio',
                    name: 'txtFecIniRep',
                    id: 'txtFecIniRep',
                    value: new Date().format('d-M-y'),
                    format:'d-M-y',
                    endDateField: 'enddt' // id of the end date field
                    ,format: slDateFmt
                    ,altFormats: slDateFmts
              },{xtype:'datefield',
                    fieldLabel: 'Fin',
                    name: 'txtFecFinRep',
                    id: 'txtFecFinRep',
                    value: new Date().format('d-M-y'),
                    format:'d-M-y',
                    endDateField: 'enddt' // id of the end date field
                    ,format: slDateFmt
                    ,altFormats: slDateFmts
              },{
                    xtype:'fieldset', title: 'Ubicacion', autoHeight:true, defaultType: 'radio',
                    collapsible: true, width: 230,
		    frame:false,
                    items: [{
                            fieldLabel: '',
                            labelSeparator: '',
                            boxLabel: 'Custodia',
                            name: 'reporte',
                            inputValue: 'custodia'
                            ,id:'ubiCustodia'
                        },{
                            fieldLabel: '',
                            labelSeparator: '',
                            boxLabel: 'Otro Depto.',
                            name: 'reporte',
                            inputValue: 'otro'
                            ,id:'ubiOtro'
                        },{
                            fieldLabel: '',
                            labelSeparator: '',
                            boxLabel: 'Todos',
                            name: 'reporte',
                            inputValue: 'todos'
                            ,id:'ubiTodos'
                        },{xtype:	'button',
                            id:     'btnRepUbi',
                            cls:	 'boton-menu',
                            tooltip: 'Consultar Ubicacion',
                            text:    'Consultar Ubicacion',
                            style:   slWidth ,
                            handler: function(){
                                        debugger;
                                        var opc = 1;
                                        var ubicacion = true;
                                        if (Ext.getCmp('ubiOtro').checked == true){
                                            opc = 2;                        
                                        }
                                        else if (Ext.getCmp('ubiTodos').checked == true){
                                            opc = 3;    
                                        }
                                        //var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecIni").value, slDateFmt), 'Y-m-d');
                                        //var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecFin").value, slDateFmt), 'Y-m-d');
                                        var slUrl = "CoTrTr_chequesubicacion.rpt.php?init=1&pOpc="+opc;
                                        var slTitulo = "UBICACION DE CHEQUES";
                                        /*if (false == ubicacion){
                                            slUrl = "CoTrTr_chequeEstado.rpt.php?init=1&pOpc="+opc;
                                            slTitulo = "ESTADO DE CHEQUES";
                                        }*/
                                            
                                        window.open(slUrl, slTitulo, "status=0,width=500,height=550");
                                        //app.cart.paramDetalleCons ={pAuxil: Ext.getCmp("txt_bancos").getValue(), fecIni: slFecI, fecFin: slFecF};
                                        //addTab({id:'gridConsCheEnv', title:'Cheques enviados', url:slUrl, tip: 'Cheques enviados'});
                                }
                        }
			]
                },{
                    xtype:'fieldset', title: 'Estado', autoHeight:true, defaultType: 'radio',
                    collapsible: true, width: 230,
		    frame:false,
                    items: [olEstadoRep
                            ,{
                                xtype:'checkbox'
                                ,fieldLabel: 'Todos los movimientos'
                                ,name: 'chkTodos'
                                ,id: 'chkTodos'
                                ,text:'Muestra todos los movimientos de los cheques'
                            },{
                                xtype:'checkbox'
                                ,fieldLabel: 'Todos las empresas'
                                ,name: 'chkEmpresas'
                                ,id: 'chkEmpresas'
                                ,text:'Muestra todos los movimientos de los cheques de todas las empresas'
                            },{xtype:	'button',
                                id:     'btnRepEst',
                                cls:	 'boton-menu',
                                tooltip: 'Consultar Estado',
                                text:    'Consultar Estado',
                                style:   slWidth ,
                                handler: function(){
                                            debugger;
                                            var opc = 1;
                                            var ubicacion = true;
                                            
                                            opc = Ext.getCmp('txtestado').getValue();
                                            
                                            var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecIniRep").value, slDateFmt), 'Y-m-d');
                                            var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecFinRep").value, slDateFmt), 'Y-m-d');
                                            /*var slUrl = "CoTrTr_chequesubicacion.rpt.php?init=1&pOpc="+opc;
                                            var slTitulo = "UBICACION DE CHEQUES";
                                            if (false == ubicacion){*/
                                            if (Ext.getCmp('chkEmpresas').checked == true){
                                                slUrl = "CoTrTr_chequeEstadoEmp.rpt.php?init=1&pOpc="+opc+"&fIni="+slFecI+"&fFin="+slFecF;
                                                slTitulo = "ESTADO DE CHEQUES - TODAS LAS EMPRESAS";                                            
                                            }else if (Ext.getCmp('chkTodos').checked == false){
                                                slUrl = "CoTrTr_chequeEstado.rpt.php?init=1&pOpc="+opc+"&fIni="+slFecI+"&fFin="+slFecF;
                                                slTitulo = "ESTADO DE CHEQUES";
                                            }else{
                                                 slUrl = "CoTrTr_chequeEstadoTodos.rpt.php?fIni="+slFecI+"&fFin="+slFecF;
                                                slTitulo = "ESTADO DE CHEQUES";                                                
                                            }
                                            window.open(slUrl, slTitulo, "status=0,width=800,height=550,scrollbars=1");
                                            //app.cart.paramDetalleCons ={pAuxil: Ext.getCmp("txt_bancos").getValue(), fecIni: slFecI, fecFin: slFecF};
                                            //addTab({id:'gridConsCheEnv', title:'Cheques enviados', url:slUrl, tip: 'Cheques enviados'});
                                    }
                            }
			]
                }
                ]
  });


frmReporte.render(document.body, 'divIzq03');


fCargaPermisos('CUB');
fCargaPermisos('CES');
fCargaPermisos('COC');
fCargaPermisos('RCU');
fCargaPermisos('RCE');
fCargaPermisos('rCO');
fCargaPermisos('GAR');
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
  
 
  
var global_printer = null;  // it has to be on the index page or the generator page  always
function printmygridGO(obj){  global_printer.printGrid(obj);	} 
function printmygridGOcustom(obj){ global_printer.printCustom(obj);	}  	
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
function fCargaUsuario(){
        
    var olDat = Ext.Ajax.request({
	url: 'CoTrTr_variablesglobales'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                //debugger;
		//$var = olRsp.valor;
		app.cheque.codUser = olRsp.codUser;
                app.cheque.usuario = olRsp.usuario;
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
	,params: {pKey: 'user', pBool: 0}//, pAux:ilAuxi, pBan:true, pTip: Ext.getCmp("slFormaPago").getValue()}
    })
}

function fCargaPermisos($key){
        
    var olDat = Ext.Ajax.request({
	url: 'CoTrTr_variablesglobales'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                //debugger;
		//$var = olRsp.valor;
                switch ($key){
                    case 'CUB':app.cheque.ubicacion = olRsp.valor;
                        if (olRsp.valor != 1) Ext.getCmp('btnUbic').disable();
                        break;
                    case 'CES':app.cheque.estado = olRsp.valor;
                        if (olRsp.valor != 1) Ext.getCmp('btnEst').disable();
                        break;
                    case 'COC':app.cheque.estadoConf = olRsp.valor;
                        if (olRsp.valor != 1) Ext.getCmp('btnEstCon').disable();
                        break;
                    case 'RCU':app.cheque.repUbicacion = olRsp.valor;
                        if (olRsp.valor != 1) Ext.getCmp('btnRepUbi').disable();
                        break;
                    case 'RCE':app.cheque.repEstado = olRsp.valor;
                        if (olRsp.valor != 1) Ext.getCmp('btnRepEst').disable();
                        break;
                    case 'rCo':app.cheque.repEstadoCons = olRsp.valor;
                        if (olRsp.valor != 1) Ext.getCmp('chkEmpresas').disable();
                        break;
                    case 'GAR':app.cheque.genArchivo = olRsp.valor;//generacion de archivo
                        if (olRsp.valor != 1) Ext.getCmp('btnArchivo').disable();
                        break;
                }
		
                //app.cheque.usuario = olRsp.usuario;
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
	,params: {pKey: $key, pBool: 0}//, pAux:ilAuxi, pBan:true, pTip: Ext.getCmp("slFormaPago").getValue()}
    })
}