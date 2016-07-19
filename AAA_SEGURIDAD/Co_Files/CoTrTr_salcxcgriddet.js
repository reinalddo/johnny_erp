/*
*  Configuracion de Autogrid para detalles de cartera: Carga el resultado del detalle de una cuienta por cobrar/Pagar
*  especifica. Inicializa el grid, y luego llama a script php que devuelve los datos asociados, enviando como parametro
*  la cuenta y auxiliar requeridos.
*  
* @param	pCuent	Codigo de Cuenta a generar
* @param	pAuxil	Codigo de Auxiliar 
*
*
**/
//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
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

    /*@TODO:                                                                  sumatoria de  grid */
	// custom summary renderer example
    /*   
    function totalDetalle(v, params, data) {
        return v?  v : '';
    }
    var summaryDet = new Ext.ux.grid.GridSummary();    
 
    var filtersDet = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',   dataIndex: 'det_codcuenta'},
        {type: 'numeric',  dataIndex: 'det_idauxiliar'},
        {type: 'string',   dataIndex: 'txt_nombre'},
        {type: 'date',     dataIndex: 'det_fecha'},
	{type: 'numeric',  dataIndex: 'det_numcheque'},
        {type: 'string',   dataIndex: 'txt_nombcuenta'}]
        , autoreload:true
    });*/

    name="Car";
    grid2 = new Ext.ux.AutoGridPanel({
        title:''
        ,height: 300
        ,width: 800
        ,cnfSelMode: 'csm'
        ,loadMask: true
        ,stripeRows :true
        ,autoSave: true
        ,saveUrl: 'saveconfig.php'  
        ,store : store
        ,monitorResize:true
        ,animCollapse:false
        ,collapsible:true
        //colModel: new Ext.grid.ColumnModel([{id: 'null'}]), 
        ,bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: store,
            displayInfo: true,
            //displayMsg: '{0} a {1} de {2}',
            displayMsg: ' ',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-', {
		    pressed: false
		   //enableToggle:true
		   ,text: 'Aplicar'
		   //,disabled:true
		   ,cls: 'x-btn-text-icon details'
		   ,qtip: 'Genera un documento para liquidar los saldos marcados'
		   //,toggleHandler: toggleDetails
		   ,handler: fVerGridDetalle
		},{
		    xtype: 'numberfield'
		    ,id:    'flSumaSelec'
		    ,value: 0
		    ,width: 70
		    //,decimalPrecision: 2
		    //,renderer: 'money'
		},{
		     pressed: false
		    //enableToggle:true
		    ,text: 'Desmarcar'
		    ,cls: 'x-btn-text-icon details'
		    ,qtip: 'Desmarca TODOS los vencimientos marcados'
		    //,toggleHandler: toggleDetails
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
    
    grid2.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        var flSum = Ext.getCmp('flSumaSelec');
	if (r.data.txt_pago == 0) r.set("txt_pago",r.data.txt_valor);
        flSum.setValue(flSum.getValue() + r.data.txt_pago);
	//@TODO ; Cargar comprobante
	i=0;
	return true;
	});
    grid2.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        var flSum = Ext.getCmp('flSumaSelec');
	r.set("txt_pago",0);
        flSum.setValue(flSum.getValue() - r.data.txt_pago)
	//@TODO ; Cargar comprobante
	i=0;
	return true;
	}
    );
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
                ,width: 100 };
    var olBenef={xtype: "textfield"
                ,id:  "slBenef"
                ,tabindex: 30
                ,fieldLabel: "BENEFICIARIO"
		,value: grid2.getSelections()[0].data.txt_nombre
                ,width: 240 };		
    var olNumCheque ={xtype: "numberfield"
                ,id:  "ilNumCheque"
                ,tabindex: 30
                ,fieldLabel: "CHEQUE #"
                ,hidden:false
		,disabled: false
                ,width: 60 } ;
    var olFecha = {xtype: "datefield"
                ,id:  "txt_Fecha"
                ,format:app.gen.dateFmt
                ,altFormats:app.gen.dateFmts
                ,tabindex: 30
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
            ,tabindex: 30
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
                ,tabindex: 40
                ,enableColors: false
                ,enableAlignments: false
            }], buttons: [{
                text: 'Grabar'
                    ,formBind:true
                    ,scope:this
                    ,handler:function() {
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
				    title: 'Comprobante' + action.result.com_TipoComp + " - " + action.result.com_NumComp
				    ,items:[{xtype: "button",
					text:"Imprimir Comprobante " + action.result.com_TipoComp + " - " + action.result.com_NumComp
					,handler: fImprimirComp.createDelegate(this, [action.result])
				    }
				    ,{xtype: "button",
					text:"Imprimir Cheque # " + action.result.com_NumCheque
					,handler: fImprimirCheq.createDelegate(this, [action.result])
					}]
				    }).show();
				grid2.store.reload()
				Ext.getCmp("frmPago").load()
				}
                           ,params:{cmd:'save', com_Fecha: slFec, det:Ext.encode(griddata)}
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
}

function fImprimirCheq(pDat){
    new Ext.Window({
	url:"CoTrTr_chqvoucherfza.php"
	,params: {pQry: pDat.comRegNumero}
	,title: 'Comprobante '+ pDat.com_TipoComp + " - " + pDat.com_NumComp + "(" + pDat.comRegNumero + ")"
    })
}
/*
 *
 *
 *
 **/

function fImprimirComp(pDat){
    window.open("CoTrTr_egresofza.php?pQry=com_RegNumero=" + pDat.com_RegNumero)
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
