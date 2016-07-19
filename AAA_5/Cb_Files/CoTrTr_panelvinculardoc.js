/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Gina Franco
 *  @date       14/May/09
*/
Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
    Ext.QuickTips.init();
    
    Ext.namespace("app", "app.vincular");
    
    olDet=Ext.get('divDet');
    var slWidth="width:250px; text-align:'left'";
    
   /*
    *   Ejemplo de manejo de ComboBox para opciones de filtrado de datos de emb
    ***/
    var rdComboBase = new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;  // Origen de datos Generico
    
    dsCmbTipoComp = new Ext.data.Store({
        proxy: 	new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php'				// Script de servidor generico que accede a BD
                    ,metod: 'POST'
                    //,extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}    //Parametros para obtener datos segun el contexto de la consulta sql
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'CoTrTr_Proveedor'}		// Parametro Basico: ID de consulta SQl (predefinida en PHP como variable de sesion)
    });
    
      
    var olTipoComp = new Ext.form.ComboBox({
			fieldLabel:'Tipo Comp.'
                        ,labelWidth:10
                        ,id:'txt_tipoComp'
			,name:'txt_tipoComp'
			,width:160
			,store: dsCmbTipoComp
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'tipoComp'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			//,emptyText:''
			,allowBlank:     false
			,listWidth:      250 
		    });
    var olProveedor = new Ext.form.ComboBox({
			fieldLabel:'Proveedor'
                        ,labelWidth:20
                        ,id:'txt_proveedor'
			,name:'txt_proveedor'
			,width:100
			,store: dsCmbTipoComp
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'proveedor'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     true
			,listWidth:      250 
		    });
    var olNumComp = {	xtype:'numberfield'
			,fieldLabel:'Num. Comp.'
                        ,labelWidth:10
                        ,id:'txt_numComp'
			,name:'txt_numComp'
			,width:160
			,emptyText:''
			,allowBlank:     false
		    };
   
    
        
    var olFechaInicio = {	xtype:'datefield'
			,fieldLabel:'Desde'
                        ,labelWidth:10
                        ,id:'txt_fecIni'
			,name:'txt_fecIni'
			,width:160
			,emptyText:''
			,allowBlank:     false
		    };
                    
    var olFechaFin = {	xtype:'datefield'
			,fieldLabel:'Fin'
                        ,labelWidth:10
                        ,id:'txt_fecFin'
			,name:'txt_fecFin'
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
	,items: [olFechaInicio, olFechaFin ]
      });
    var slWidth="width:80%; text-align:'center'";
    dr.add({xtype:	'button',
	id:     'btnCxc',
	cls:	 'boton-menu',
	tooltip: 'Busqueda de Conciliaciones',
	text:    'Buscar',
	style:   slWidth ,
	handler: function(){
		    //debugger;
		    /*if (!Ext.getCmp("txt_tipoComp").value){
			Ext.Msg.alert('AVISO', "Seleccione Tipo de Comprobante");
			return;
		    }
		    if (!Ext.getCmp("txt_numComp").value){
			Ext.Msg.alert('AVISO', "Seleccione Numero de Comprobante");
			return;
		    }*/
                    if (!Ext.getCmp("txt_fecIni").value){
			Ext.Msg.alert('AVISO', "Seleccione fecha de inicio");
			return;
		    }
                    if (!Ext.getCmp("txt_fecFin").value){
			Ext.Msg.alert('AVISO', "Seleccione fecha de fin");
			return;
		    }
		    var slFecIni = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecIni").value, "d/m/Y"), 'Y-m-d');
                    var slFecFin = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecFin").value, "d/m/Y"), 'Y-m-d');
                    /*var slTcomp = Ext.getCmp("txt_tipoComp").value;
                    var slNcomp = Ext.getCmp("txt_numComp").value;*/
                    var slUrl = "CoTrTr_gridvinculardoc.php?init=1&pFecIni="+slFecIni+'&pFecFin='+slFecFin;
		    app.vincular.paramDetalle ={/*pTComp: slTcomp
					    ,pNComp: slNcomp
                                            ,*/pFecIni: slFecIni
                                            ,pFecFin: slFecFin};
                    addTab({id:'gridVincular', title:'Documentos', url:slUrl});
                    //var w = Ext.getCmp('pnlIzq');
					//w.collapsed ? w.expand() : w.collapse();
	    }
    });
    /*dr.add({xtype:	'button'
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
		    app.cart.paramDetalle ={fecCorteNuevo: slFec};
		    addTab({id:'gridConcNueva', title:'Nuevo', url:slUrl});
		    fIngresaCabConcil(slFec);
	    }
    });*/
    
    dr.render(document.body, 'divIzq01');
	
	   var olNumCompInicio1 = {	xtype:'numberfield'
			,fieldLabel:' Desde Num.'
            ,labelWidth:10
            ,id:'txt_numComp1'
			,name:'txt_numComp2'
			,width:100
			//,emptyText:''
			,allowBlank:     true
		    };
     var olNumCompFinal1 = {	xtype:'numberfield'
			,fieldLabel:'Hasta Num.'
                        ,labelWidth:10
                        ,id:'txt_numComp2'
			,name:'txt_numComp2'
			,width:100
			//,emptyText:''
			,allowBlank:     true
		    };
    
        
    var olFechaInicio1 = {	xtype:'datefield'
			,fieldLabel:'Desde'
                        ,labelWidth:10
                        ,id:'txt_fecIni1'
			,name:'txt_fecIni1'
			,width:100
			//,emptyText:''
			,allowBlank:    true
		    };
                    
    var olFechaFin1 = {	xtype:'datefield'
			,fieldLabel:'Fin'
                        ,labelWidth:10
                        ,id:'txt_fecFin2'
			,name:'txt_fecFin2'
			,width:100
			//,emptyText:''
			,allowBlank:     true
		    };
	
	 var frreportes = new Ext.FormPanel({
    labelWidth: 50,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
	border: false,
    width: 250});
	
	frreportes.add(
		{
                    xtype:'fieldset', title: 'Consulta de enlazes por Num.Comp', autoHeight:true, defaultType: 'radio',
                    collapsible: true, width: 220,
		    		frame:false,
                    items: [olNumCompInicio1,olNumCompFinal1,
							{xtype:	'button',
						    id:'Rp1',
						    text: 'Reporte',
						    cls:'boton-menu',
						    style:slWidth, 
						    handler: function(){
								var condicion="";
								if (!Ext.getCmp("txt_numComp1").value && !Ext.getCmp("txt_numComp2").value)
								{
										var slUrl = "CoTrTr_ReporteEnlazes.rpt.php?init=1&cond="+condicion;
										//addTab({id:'tab2', title:'PROFORMA', url:slUrl, width:450});
										window.open(slUrl);
								}
							       else if (Ext.getCmp("txt_numComp1").value && !Ext.getCmp("txt_numComp2").value)
								{	     alert("Debe ingresar una numero final");
									 	return;
								}
							   else if (!Ext.getCmp("txt_numComp1").value && Ext.getCmp("txt_numComp2").value)
								{	     alert("Debe ingresar una numero inicial");
										 return;
								}
								else if (Ext.getCmp("txt_numComp1").value && Ext.getCmp("txt_numComp2").value)
								{	     
										var slNumIni= Ext.getCmp("txt_numComp1").getValue();
                    								var slNumFin= Ext.getCmp("txt_numComp2").getValue();
													condicion= "WHERE c.com_NumComp between "+slNumIni+" and "+slNumFin;
													var slUrl = "CoTrTr_ReporteEnlazes.rpt.php?init=1&cond="+condicion;
													//addTab({id:'tab2', title:'PROFORMA', url:slUrl, width:450});
													window.open(slUrl);
										
								}
								else
								{	     alert("ERROR");
										 return;
								}
								
						    }
								}
					]
        }
		);

    frreportes.add(
		{
                    xtype:'fieldset', title: 'Consulta de enlazes por Proveedor', autoHeight:true, defaultType: 'radio',
                    collapsible: true, width: 220,
		    		frame:false,
                    items: [olFechaInicio1,olFechaFin1,olProveedor,
							{xtype:	'button',
						    id:'Rp1',
						    text: 'Reporte',
						    cls:'boton-menu',
						    style:slWidth, 
						    handler: function(){
								var condicion="";
								if (!Ext.getCmp("txt_fecIni1").value && !Ext.getCmp("txt_fecFin2").value && !Ext.getCmp("txt_proveedor").value)
								{
										var slUrl = "CoTrTr_ReporteEnlazes.rpt.php?init=1&cond="+condicion;
										//addTab({id:'tab2', title:'PROFORMA', url:slUrl, width:450});
										window.open(slUrl);
								}
								else if (Ext.getCmp("txt_fecIni1").value && Ext.getCmp("txt_fecFin2").value && Ext.getCmp("txt_proveedor").value)
								{	     
												    var slFecIni = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecIni1").value, "d/m/Y"), 'Y-m-d');
												    var slFecFin = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecFin2").value, "d/m/Y"), 'Y-m-d');
												    var slReceptor= Ext.getCmp("txt_proveedor").getValue();
												    condicion= "WHERE c.com_FecContab between '"+slFecIni+"' and '"+slFecFin+"'"+"AND c.com_CodReceptor="+slReceptor;
												    var slUrl = "CoTrTr_ReporteEnlazes.rpt.php?init=1&cond="+condicion;
												    window.open(slUrl);
										
								}
								else if (Ext.getCmp("txt_fecIni1").value && !Ext.getCmp("txt_fecFin2").value && !Ext.getCmp("txt_proveedor").value)
								{
									     alert("Debe ingresar una fecha final");
										 	return;
								}
								else if (!Ext.getCmp("txt_fecIni1").value && Ext.getCmp("txt_fecFin2").value && !Ext.getCmp("txt_proveedor").value)
								{	
									     alert("Debe ingresar una fecha inicial");
										 	return;
								}
							        else if (Ext.getCmp("txt_fecIni1").value && Ext.getCmp("txt_fecFin2").value && !Ext.getCmp("txt_proveedor").value)
								{	     
												    var slFecIni = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecIni1").value, "d/m/Y"), 'Y-m-d');
                    								var slFecFin = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecFin2").value, "d/m/Y"), 'Y-m-d');
													condicion= "WHERE c.com_FecContab between '"+slFecIni+"' and '"+slFecFin+"'";
													var slUrl = "CoTrTr_ReporteEnlazes.rpt.php?init=1&cond="+condicion;
													//addTab({id:'tab2', title:'PROFORMA', url:slUrl, width:450});
													window.open(slUrl);
										
								}
								else if (!Ext.getCmp("txt_fecIni1").value && !Ext.getCmp("txt_fecFin2").value && Ext.getCmp("txt_proveedor").value)
								{	     
												    var slReceptor= Ext.getCmp("txt_proveedor").getValue();
													condicion= "WHERE c.com_CodReceptor="+slReceptor;
													var slUrl = "CoTrTr_ReporteEnlazes.rpt.php?init=1&cond="+condicion;
													//addTab({id:'tab2', title:'PROFORMA', url:slUrl, width:450});
													window.open(slUrl);
										
								}
								else
								{	     alert("ERROR");
										 return;
								}
								
						    }
								}
					]
        }
		);
	frreportes.render(document.body, 'divIzq03');

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
function basic_printGrid(){
		global_printer = new Ext.grid.XPrinter({
			grid:grid2,  // grid object 
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

