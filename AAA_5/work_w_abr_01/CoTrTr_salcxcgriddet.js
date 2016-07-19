/*
*  Configuracion de Autogrid para detalles de cartera: Carga el resultado del detalle de una cuienta por cobrar/Pagar
*  especifica. Inicializa el grid, y luego llama a script php que devuelve los datos asociados, enviando como parametro
*  la cuenta y auxiliar requeridos.
* @param	pCuent	Codigo de Cuenta a generar
* @param	pAuxil	Codigo de Auxiliar
* @rev		esl	16/02/2011	Realizar pagos parciales de Documentos
* @rev	esl	22/Mzo/2012	Imprimir cheque por empresa, lee de genclasetran el campo cla_Cheque, si no tiene datos utiliza el link CoTrTr_chequefza.php
**/
//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.QuickTips.init();
    var store = new Ext.data.JsonStore({
        root: 'rows'
        ,totalProperty: 'totalCount'
        ,idProperty: 'id'
        ,remoteSort: true
        ,fields:['id']
	    ,proxy: new Ext.data.HttpProxy({
            method:"POST"
            ,url: (!sgLoadUrl)? 'CoTrTr_salcxcgriddet.php?' : sgLoadUrl
            })
        ,baseParams:{
            init:0
            ,pPagina:0
            ,pCuent: app.cart.paramDetalle.pCuenta
            ,pAuxil:  app.cart.paramDetalle.pAuxil
            ,sort: "det_numdocum"
            ,order:"ASC"
            }
        ,sortInfo: {field: "det_numdocum", order:"ASC"}  // @bug: esto no funciona, fue necesario incluirlo en baseparams
        ,remoteSort: true
    });
    store.setDefaultSort('det_numdocum', 'ASC');         // @bug: esto no funciona, fue necesario incluirlo en baseparams

var sm = new Ext.grid.CheckboxSelectionModel();

var cm_grid2 = new Ext.grid.ColumnModel({  
      columns:[new Ext.grid.RowNumberer({width: 30})
              ,sm
	      ,{  
                  header: 'DOCUMENTO'
                  ,dataIndex: 'det_numdocum'
                  ,width:100
              },{  
                  header: 'VALOR'
		  ,renderer: Ext.util.Format.usMoney
		  ,dataIndex: 'txt_valor'
              },{ header: 'VALOR PAGAR'
		  ,renderer: Ext.util.Format.usMoney
                  ,dataIndex: 'txt_pago'
                  ,editor: new Ext.form.NumberField({ /*@rev	esl	16/02/2011	Realizar pagos parciales de Documentos*/
			allowBlank: false
			,allowNegative: false
		    })
              }]
              
    });



    name="Car";
    grid2 = new Ext.grid.EditorGridPanel({
        id:'grid2'
	,lazyRender: true
	,store : store
        ,stripeRows :true
        ,collapsible:true
        ,cm: cm_grid2 //ColumnModel
	,clicksToEdit: 1
	,height: 300
	,sm:sm
	//,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
        ,title:''
	
        //,width: 800
	//,cnfSelMode: 'csm'
        /*,loadMask: true
        ,autoSave: true
        ,saveUrl: 'saveconfig.php'  
        ,monitorResize:true
        ,animCollapse:false
        ,columnLines: true
	*/
        ,bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: store,
            displayInfo: true,
            //displayMsg: '{0} a {1} de {2}',
            displayMsg: ' ',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-',{
		    pressed: false,
		    enableToggle:true,
		    text: 'Imprimir',
		    //cls: 'x-btn-text-icon details' ,
		    handler: function(){basic_printGrid(grid2);}
		}, {
		    pressed: false
		   ,text: 'Aplicar'
		   ,qtip: 'Genera un documento para liquidar los saldos marcados'
		   ,handler: fVerGridDetalle
		},{
		    xtype: 'numberfield'
		    ,id:    'flSumaSelec'
		    ,value: 0
		    ,width: 70
		    //,disabled:true
		    ,readOnly:true
		    ,decimalPrecision: 2
		    ,renderer: Ext.util.Format.usMoney
		},{
		     pressed: false
		    ,text: 'Desmarcar'
		    ,qtip: 'Desmarca TODOS los vencimientos marcados'
		    ,handler: function(){
			grid2.getSelectionModel().clearSelections();
		}}
        ]})
        //,plugins: [filtersDet, summaryDet],
        ,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
                if(Ext.getCmp("frmPago")) Ext.getCmp("frmPago").destroy();
            }
        }
    });

    var olPanel = Ext.getCmp(gsObj);
    olPanel.add(grid2);
    
    this.grid2.store.load({params: {meta: true, start:0, limit:25, sort:'com_feccontab', dir:'ASC'}});
    Ext.getCmp("pnlDer").doLayout();
    
    /********************* Funcion para despues de editar el contenido de la celda del Grid:**********************/
    grid2.on('afteredit', function(e){
	var txValor = 0;
	var txPago = 0;
	txValor = e.record.data.txt_valor;
	txPago = e.record.data.txt_pago;
	
	
	if (Tipo_Trans == 'C'){ /*Validacion solo cuando la transaccion es cuentas x cobrar*/
		if (txPago < 0){ /* Si se ingresa una cantidad en negativo se la pone en positivo */
		e.record.set('txt_pago',txPago * -1);
		txPago = e.record.data.txt_pago;
	    }
	}else{/*Validacion solo cuando la transaccion es cuentas x pagar*/
	    if (txPago > 0){ /* Si se ingresa una cantidad en positivo se la pone en negativo */
		e.record.set('txt_pago',txPago * -1);
		txPago = e.record.data.txt_pago;
	    }
	    /*Como ambos valores son negativos, se los convierte en positivo solo para verificar si la cantidad ingresada es mayor
	      Pero al enviar los valores al proceso de grabacion deben ir en negativo.
	    */
	    txValor = txValor * -1;
	    txPago = txPago * -1;
	}
	
	
	if (txPago > txValor){
	    Ext.Msg.alert('ATENCION!','El valor del pago ('+txPago+') no puede ser mayor al saldo del documento ('+txValor+')');
	    e.record.set('txt_pago',0);
	}
	fsuma() //Actualizar el total a aplicar.
	return true;
    });
    
    
    grid2.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	/*        
	var flSum = Ext.getCmp('flSumaSelec');
	flSum.setValue(flSum.getValue() + r.data.txt_pago);
	//@TODO ; Cargar comprobante
	i=0;
	*/
	
	if (r.data.txt_pago == 0) r.set("txt_pago",r.data.txt_valor);
	fsuma()
	return true;
	});
    
    grid2.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	/*
	var flSum = Ext.getCmp('flSumaSelec');
	//r.set("txt_pago",0);
        flSum.setValue(flSum.getValue() - r.data.txt_pago)
	// setear la columna del pago con 0
	//@TODO ; Cargar comprobante
	i=0;
	*/
	
	r.set("txt_pago",0);
	fsuma()
	return true;
	});
    
    function fsuma(){
	    var alSel = grid2.getSelectionModel().selections;
	    var txtPago = 0;
	    for (var i=0 ; i <  alSel.items.length; i++) {
			   txtPago = txtPago + alSel.items[i].data.txt_pago
	    }
	    var flSum = Ext.getCmp('flSumaSelec');
	    flSum.setValue(txtPago);
	    // Si ya esta la ventana de aplicar pago, actualizar tambien el campo del valor:
	    if(Ext.getCmp("flValor")) {
		Ext.getCmp("flValor").setValue(txtPago);
	    }
	    
	}
})
/*
 *	Define el Auxiliar en base a la configuracion de la Transaccion: busca el registro correspondiente al valor del campo "cla_EmiDeafult"
 *	en el store del combo com_Emisorr y lo selecciona
 *
 ***/
function fSelFmaPago(){
    var olRec=this.getSelectedRecord().node;
    var ilAuxi = Ext.DomQuery.selectValue("cla_EmiDefault", olRec);
    var slCuen = Ext.DomQuery.selectValue("cla_CtaOrigen", olRec);
    Ext.getCmp("slEmisor").setValue(ilAuxi);
    var olEmi= Ext.getCmp("slEmisor");
    olEmi.store.on("load",function(){
	// olEmi.select(olEmi.getStore().find("cod", ilAuxi,1)); no funciona esta opcion
	olEmi.setValue(ilAuxi);
    })
    olEmi.store.load();
    //olEmi.select(olEmi.getStore().find(ilAuxi),1);
    if (ilAuxi != 0) Ext.getCmp("slEmisor").disabled=1;
    else Ext.getCmp("slEmisor").disabled=0;
    olDat = Ext.Ajax.request({
	url: 'CoTrTr_salcuenta'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
	    var olRsp = eval("(" + pResp.responseText + ")")
	    Ext.getCmp("ilNumCheque").setValue(olRsp.info.ban_PxmoChq)
	    Ext.getCmp("flSaldo").setValue(olRsp.sal);
	    }
	}
	,success: function(pRes,pPar){
	    Ext.getCmp("ilNumCheque").setValue(0)
	    Ext.getCmp("flSaldo").setValue(0)
	    }
	,failure: function(pObj, pRes){
	    Ext.getCmp("ilNumCheque").setValue(0)
	    Ext.getCmp("flSaldo").setValue(0)
	    }
	,params: {pCta: slCuen, pAux:ilAuxi, pBan:true, pTip: Ext.getCmp("slFormaPago").getValue()}
    })
}
/*
 *  Despliega Grid con detalles de vencimientos de una cuenta especifica.
 **/
 
function fVerGridDetalle(){
    /* ACEPTAR CUALQUIER CAMBIO EN EL GRID */
	grid2.stopEditing();
    /* ------------------------------- */
    var olAuxil = new gen.cmbBoxClass({id:"slEmisor"
        ,tabindex: 	20
        ,hideLabel:	true
        ,labelWidth:0
        ,sqlId: 	"banAuxil"
        ,hiddenName:	"com_Emisor"
        ,width: 	130})
    var olFmaPago = {xtype: "genCmbBox"		
                ,id:  "slFormaPago"
                ,tabindex: 10
                ,fields: ["cod", "txt", "cla_tipoEmisor","cla_EmiDefault","cla_IndCheque","cla_txtReceptor",
                    "cla_recDefault","cla_CtaOrigen","cla_auxorigen","cla_CtaDestino","cla_AuxDestino"]
                ,labelWidth:100
                ,fieldLabel: "FMA. DE PAGO"
                ,hiddenName: "com_TipoComp"
                ,sqlId:	 "carTipoTr"
                ,minChars:2
                ,expanded:true
                ,listeners : {"select":{fn: fSelFmaPago}}
                ,width:175};
    var olValor={xtype: "numberfield"
                ,id:  "flValor"
                ,tabindex: 30
                ,value: Ext.getCmp("flSumaSelec").getValue()
                ,fieldLabel: "VALOR"
		,width: 100
		,readOnly:true
		//,disabled:true
		};

    var olBenef={xtype: "textfield"
                ,id:  "slBenef"
                ,tabindex: 60
                ,fieldLabel: "BENEFICIARIO"
		,value: grid2.getSelections()[0].data.txt_Beneficiario
                ,width: 240};		
    var olNumCheque ={xtype: "numberfield"
                ,id:  "ilNumCheque"
                ,tabindex: 40
                ,fieldLabel: "CHEQUE/DOC"
                ,hidden:false
		,disabled: false
                ,width: 60} ;
    var olFecha = {xtype: "datefield"
                ,id:  "txt_Fecha"
                ,format:app.gen.dateFmt
                ,altFormats:app.gen.dateFmts
                ,tabindex: 50
                ,labelWidth: 30
                ,labelStyle: "width:15px"
                ,fieldLabel: "FECHA"
                ,allowBlank:false
                ,blankText:"Ingrese una Fecha Vàlida. Ejm: 01/01/09 ó 01/Ene/09"
                ,value: new Date().format('d/M/y')
                ,hidden:false
                ,width: 100
                }
    var olSaldo={xtype: "numberfield"
            ,id:  "flSaldo"
            ,tabindex: 70
            ,value: 0
            ,fieldLabel: "SALDO"
            ,width: 70
            }
    if(!Ext.getCmp("frmPago")) {
        var olForm = new Ext.form.FormPanel({
            xtype: "form"
            ,id:   "frmPago"
            ,labelWidth : 90
            ,baseClass: "x-form"
            ,defaults: {labelWidth:100}
            ,items:[{
            layout:"column"			// Columnas
            ,border:false
            ,extraCls: "x-form"
            ,items:[{			//Primera columna
                columnWidth: .65
                    ,layout: 'form'
                    ,border:false
                    ,items:[olFmaPago, olValor, olNumCheque, olFecha ]
                },{columnWidth: .35
                    ,layout: 'form'
                    ,border:false
                    ,items:[ olAuxil]
                },olSaldo]
            },olBenef 						//Sin Columnas, todo el ancho
	    ,{
                xtype: 'textarea'
                ,labelWidth:100
                ,id:  "com_Concepto"
                ,fieldLabel: "CONCEPTO"
                ,width: 400
                ,tabindex: 70
                ,enableColors: false
                ,enableAlignments: false
            }], buttons: [{
                text: 'Grabar'
                    ,formBind:true
                    ,scope:this
                    ,handler:function() {
			// concatenar numero de documento al concepto si es: Cuentas por Cobrar
			if (Tipo_Trans == 'C'){
			    var NumDocumento = " DOC/"+Ext.getCmp("ilNumCheque").getValue();
			    //Ext.getCmp("com_Concepto").setValue((Ext.getCmp("com_Concepto").getValue()+" DOC."+Ext.getCmp("ilNumCheque").getValue()));
			}
			else{
			    var NumDocumento = " ";
			}
			
                        var alSel = grid2.getSelectionModel().selections;
                        var griddata = [];
                        for (var r=0 ; r <  alSel.items.length; r++) {
                            griddata.push(alSel.items[r].data);
                        }
                        var slFec = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_Fecha").value, "d-M-y"), 'Y-m-d');;
                        Ext.getCmp("frmPago").getForm().submit({
                            url:"CoTrTr_generapago.php"
                           ,scope:this
                           ,success:function(form, action) {
				new Ext.Window({
				    title: 'COMPROBANTE ' + action.result.com_TipoComp + " - " + action.result.com_NumComp +
					    "(" + action.result.com_RegNumero + ")"
				    ,items:[{
					xtype: "button",
					    text:"Imprimir VOUCHER " + action.result.com_TipoComp + " - " + action.result.com_NumComp
					    ,handler: fImprimirVoucher.createDelegate(this, [action.result])
					},{xtype: "button",
					    text:"Imprimir CHEQUE # " + action.result.com_NumCheque + " suelto"
					    ,handler: fImprimirCheque.createDelegate(this, [action.result])
					},{xtype: "button",
					    text:"Imprimir COMPROBANTE SIMPLE " + action.result.com_TipoComp + " - " + action.result.com_NumComp
					    ,handler: fImprimirCompr.createDelegate(this, [action.result])
					}]
				    }).show();
				grid2.store.reload()
				Ext.getCmp("frmPago").load()
                                
                                
                                
                                
				}
                           ,params:{cmd:'save', com_Fecha: slFec, documento : NumDocumento, det:Ext.encode(griddata)}
                           //,waitMsg:'Grabando...'
                        })
                    }
                },{text: 'Cancelar'
            }] // eo Buttons
        }); // eo form frmPago
    var olPnlDer =Ext.getCmp("pnlDer");
    olPnlDer.add(olForm);
    olPnlDer.doLayout();
    }
        
    
    
    
    // Asignar valores:
        Ext.getCmp("flValor").setValue(Ext.getCmp("flSumaSelec").getValue());
        Ext.getCmp("slBenef").setValue(grid2.getSelections()[0].data.txt_Beneficiario);
    
    /**
     *     Tipo_Trans - variable definida en CoTrTr_salcxcgrid.php
     *     Guarda el Tipo de Transaccion
     *     Cuentas por Cobrar = 1
     *     Cuentas por Pagar = -1
    */
    //alert(Tipo_Trans);
    if (Tipo_Trans == 'C'){
        Ext.getCmp("slBenef").setLabel("CLIENTE");
    }else{
        Ext.getCmp("slBenef").setLabel("BENEFICIARIO");
    }
}





function fImprimirCheq(pDat){
 
}
/*
 *
 *
 *
 **/

function fImprimirVoucher(pDat){
    window.open("../Co_Files/CoTrTr_egresofza.php?pQry=com_RegNumero=" + pDat.com_RegNumero)
}
function fImprimirCompr(pDat){
    window.open("../Co_Files/CoTrTr_egresofzasimple.php?pQry=com_RegNumero=" + pDat.com_RegNumero)
}
function fImprimirCheque(pDat){
    //window.open("../Co_Files/CoTrTr_chequefza.php?pQry=com_RegNumero=" + pDat.com_RegNumero)
    //#rev	esl	22/Mzo/2012	Imprimir cheque por empresa, lee de genclasetran el campo cla_Cheque, si no tiene datos utiliza el link CoTrTr_chequefza.php
    window.open(pDat.cla_Cheque+"?&pQry=com_RegNumero=" + pDat.com_RegNumero+"&pQryCom=com_TipoComp='"+ pDat.com_TipoComp+"' AND com_NumComp="+pDat.com_NumComp)
}

/*
 *
 *
 *
 **/
function fGetAjaxData(pUrl, pPar){
    var olResp={};
    Ext.Ajax.request({
	url: pUrl
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
		olResp = eval("(" + pResp.responseText + ")")
		olResp.status=true
	    }
	}
	,failure: function(pObj, pRes){
	    olResp.status=false
	    }
	,params: pPar
    })
    return olResp;
}
