/*
*  Configuracion de Autogrid para conciliaciones: Carga las conciliaciones realizadas de un banco
*  especifico. Inicializa el grid, y luego llama a script php que devuelve los datos asociados, enviando como parametro
*  la cuenta y auxiliar requeridos.
*  
* @param	pAuxil	Codigo de Auxiliar 
*
*
**/

Ext.namespace("app", "app.cl");

//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
//debugger;
Ext.onReady(function(){
    //debugger;
    
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        //sorInfoX : {field:'com_FecContab', direction: 'DESC'},
	sorInfoX : {field:'id', direction: 'DESC'},
        applySort: function(){
            Ext.data.GroupingStore.superclass.applySort.call(this);
            if (!this.sortInfo) this.sortInfo = this.sortInfoX;
            if(!this.groupOnSort && !this.remoteGroup){
            var gs = this.getGroupState();
            if(gs && gs != this.sortInfo.field){
            this.sortData(this.groupField);
            }
            }
        }
    });
    var store = new storeX({
	    proxy: new Ext.data.HttpProxy({
            method:"GET",
            url: (!sgLoadUrl)? 'CoTrCl_conciliaciongridDet.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id',totalProperty:"totalRecords" /*, start:0, limit:9999*/},
            ['id']
        ),
        //groupField: 'ID',
	//sortInfoX: {field: 'id', direction: 'DESC'}, 
	sortInfo: {field: 'com_FecContab', direction: 'DESC'},
        sortInfoX: {field: 'com_FecContab', direction: 'DESC'}, 
        groupOnSort:false ,
        remoteSort: true
    });
    
    /*var store = new Ext.data.JsonStore({
        root: 'rows'
        ,totalProperty: 'totalCount'
        ,idProperty: 'id'
        ,remoteSort: true
        ,fields:['id']

	    ,proxy: new Ext.data.HttpProxy({
            method:"POST"
            ,url: (!sgLoadUrl)? 'CoTrCl_conciliaciongridDet.php?' : sgLoadUrl
            })
        ,baseParams:{
            init:0
            ,pPagina:0
            //,pCuent: app.cart.paramDetalle.pCuenta
            ,pAuxil:  app.cart.paramDetalle.pAuxil
            ,fecCorte:  app.cart.paramDetalle.fecCorte
            ,sort: "com_FecContab"
            ,order:"DESC"
            ,dir:"DESC"
            }
        ,sortInfo: {field: "com_FecContab", order:"DESC"}  // @bug: esto no funciona, fue necesario incluirlo en baseparams
        ,remoteSort: true
    });*/
    //store.setDefaultSort('det_numdocum', 'ASC');         // @bug: esto no funciona, fue necesario incluirlo en baseparams

    /*@TODO:                                                                  sumatoria de  grid */
	// custom summary renderer example
    /*   
    function totalDetalle(v, params, data) {
        return v?  v : '';
    }
    var summaryDet = new Ext.ux.grid.GridSummary();    
 
    */
    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',  dataIndex: 'com_TipoComp'},
        {type: 'numeric',  dataIndex: 'com_NumComp'},
        {type: 'date',  dataIndex: 'com_FecContab'},
        {type: 'numeric',  dataIndex: 'det_ValDebito'},
        {type: 'numeric',  dataIndex: 'det_ValCredito'},
        {type: 'string',  dataIndex: 'com_Concepto'},
        {type: 'numeric',  dataIndex: 'det_NumCheque'},
        {type: 'numeric',  dataIndex: 'det_EstLibros'},
        {type: 'date',  dataIndex: 'det_FecLibros'}]
        , autoreload:true
    });
    
    olModificar = {
        xtype:'checkbox'
        ,fieldLabel: 'Modificar'
        ,name: 'chkModificar'
        ,id: 'chkModificar'
        ,text:'Modificar'
    };

    name="Car";
    app.cl.gridDet = new Ext.ux.AutoGridPanel({
        title:''
        ,plugins: [filters]
        //,id:'gridConciliacion'
        ,height: 200
        //,width: 450
        ,style:"width:100%; font-size:8px"
        ,cnfSelMode: 'csmm'
        ,loadMask: true
        ,stripeRows :true
        ,autoSave: true
        ,saveUrl: 'saveconfig.php'  
        ,store : store
        ,monitorResize:true
        ,animCollapse:false
        ,collapsible:true
        //colModel: new Ext.grid.ColumnModel([{id: 'null'}]),
        ,tbar:[olModificar
               ,'<span style="color:red;">Modificar?</span>'
               ,'      |        '
               ,{
                    text: 'Anular'
                    ,tooltip: 'Anulacion diferida de un comprobante'
                    ,id: 'btnAnular'
                    ,listeners: {
                        click: fAnular
                    }
                    ,iconCls:'iconAnular'
                }
               ]
        ,bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: store,
            displayInfo: true,
            plugins: [filters],
            //displayMsg: '{0} a {1} de {2}',
            displayMsg: ' ',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-',{
                        pressed: false,
                        enableToggle:true,
                        text: 'Imprimir',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconImprimir',
                        handler: function(){basic_printGrid(app.cl.gridDet);}
                    }
		    /*      
		    * Agregar Panel para botones de reportes
		    * esl	21/Julio/2010 
		    */
		    ,'-'
		    ,'Reportes:'
		    ,{
                        xtype:"checkbox"
			,id:"pTip"
                        ,boxLabel: 'Estructurado'
                    }
		    ,{
                        xtype:"button"
			,id:"btLibro"
			,text: 'Libro Banco'
			,handler: function(){fReportes(1);}
                    }
		    ,{
                        pressed: false
			,enableToggle:true
                        ,text: 'Est.Cuenta.'
                        ,handler: function(){fReportes(2);}
                    }
		    ,{
                        pressed: false
			,enableToggle:true
                        ,text: 'Conciliacion'
                        ,handler: function(){fReportes(3);}
                    }
        ]})
        //,plugins: [filtersDet, summaryDet],
        ,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
                //if(Ext.getCmp("frmPago")) Ext.getCmp("frmPago").destroy();
            }
        }
    });
    //debugger;
    //if(!Ext.getCmp("gridConciliacion")){
        var olPanel = Ext.getCmp(gsObj);
        olPanel.add(app.cl.gridDet);
    //}
    
     
    
    app.cl.gridDet.store.load({params: {meta: true, start:0, limit:25, sort:'com_FecContab', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();
    
    //app.cl.gridDet.getColumnModel().getColumnById('det_FecLibros').addClass('redbg');
                                          
    
    
    /*app.cl.gridDet.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
                //debugger;
                //var olRec=app.cl.gridDet.getSelectedRecord().node;
                var ilTipo = r.data.com_TipoComp;
                var ilNum = r.data.com_NumComp;
                var sm2 = app.cl.gridDet.getSelectionModel().getSelected();
                if ('0' == r.data.det_EstLibros ){
                    sm2.set('det_EstLibros','1');//rec.id);
                    sm2.set('det_FecLibros',Date.parseDate(r.data.fecCorte, 'Y-m-d'));
                }
                else{
                    sm2.set('det_EstLibros','0');//rec.id);
                    sm2.set('det_FecLibros',Date.parseDate("2050-12-31", 'Y-m-d'));
                }
                fModifEstadoFechaConcil(r,ilTipo+ilNum);
                //Ext.Msg.alert('AVISO', ilTipo+ilNum);
	});*/
    app.cl.gridDet.on('rowclick', function(sm, rowIdx, e) {
                //debugger;
                //var olRec=app.cl.gridDet.getSelectedRecord().node;
                if (true == Ext.getCmp("chkModificar").checked){
                    var r = app.cl.gridDet.getStore().getAt(rowIdx);
                    var ilTipo = r.data.com_TipoComp;
                    var ilNum = r.data.com_NumComp;
                    var sm2 = app.cl.gridDet.getSelectionModel().getSelected();
                    if ('0' == r.data.det_EstLibros ){
                        sm2.set('det_EstLibros','1');//rec.id);
                        sm2.set('det_FecLibros',Date.parseDate(r.data.fecCorte, 'Y-m-d'));
                    }
                    else{
                        sm2.set('det_EstLibros','0');//rec.id);
                        sm2.set('det_FecLibros',Date.parseDate("2050-12-31", 'Y-m-d'));
                    }
                    fModifEstadoFechaConcil(r,ilTipo+ilNum);
                }
                //Ext.Msg.alert('AVISO', ilTipo+ilNum);
	});
    /*app.cl.gridDet.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        var flSum = Ext.getCmp('flSumaSelec');
	r.set("txt_pago",0);
        flSum.setValue(flSum.getValue() - r.data.txt_pago)
	//@TODO ; Cargar comprobante
	i=0;
	return true;
	}
    );*/
})

/*Modifica el estado de las transacciones
 *fecLibros, EstLibros
*/
function fModifEstadoFechaConcil(rec,comp) {
        //debugger;
        var olParam= {
            id : "CoTrCl_conciliacionact",
            estLibros : rec.data.det_EstLibros,
            fecLibros  : rec.data.det_FecLibros.dateFormat("Y-m-d"),
            regNumero  : rec.data.com_RegNumero,  // Requiere Rope en lugar de Emb
            auxil : Ext.getCmp("txt_bancos").getValue(),
            cuenta  : Ext.getCmp("txt_cuenta").getValue(),//"100100"
            fecCorte : rec.data.fecCorte
        }
        Ext.Ajax.request({
            url: 'CoTrCl_conciliaciongrabar.php',
            success: function(pResp, pOpt){
                //debugger;
                //Ext.Msg.alert('AVISO', "Comprobante Actualizado "+comp);
                
                //olRec.tmp_Valor = olRec.tmp_Cantidad * olRec.tmp_PrecUnit
            },
            failure: function(pResp, pOpt){
                Ext.Msg.alert('AVISO', "Error al actualizar comprobante "+comp);
            },
            headers: {
               'my-header': 'foo'
            },
            params: olParam,
            scope:  this
        });
    }


/* Permite anular un comprobante de forma diferida, es decir:
 * lo que esta en el debe va al haber y viceversa 
*/
function fAnular(){
    
    //debugger;
    
    if (0 == app.cl.gridDet.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Por favor seleccione comprobante a anular');
        return;
    }
    
    var slRegNum = app.cl.gridDet.getSelectionModel().getSelected().data.com_RegNumero;
    var slFecCorte = app.cl.gridDet.getSelectionModel().getSelected().data.fecCorte;
    
    /*if ("Anulado" == slConcepto){
        Ext.Msg.alert('ALERTA','Comprobante ya esta anulado');
        return;
    }*/
        
    var olParam= {
        id : "CoTrTr_anulacionDif"
        ,pReg : slRegNum
        ,pFec  : slFecCorte
    }
    Ext.Ajax.request({
        url: 'CoTrTr_anula.php',
        success: function(pResp, pOpt){
            //debugger;
            var olRsp = eval("(" + pResp.responseText + ")")
            if ("" != olRsp.message){
                Ext.Msg.alert('Alerta',olRsp.message);
            }else{
                /*var sm2 = app.cl.gridDet.getSelectionModel().getSelected();
                sm2.set('com_Concepto','Anulado');//rec.id);
                sm2.set('det_ValDebito',0);
                sm2.set('det_ValCredito',0);*/
                Ext.Msg.alert('Alerta','Comprobante '+slTipoComp+slNumComp+' fue anulado exitosamente.');
            }
            //olRec.tmp_Valor = olRec.tmp_Cantidad * olRec.tmp_PrecUnit
        },
        failure: function(pResp, pOpt){
            debugger;
            Ext.Msg.alert('AVISO', "Error al actualizar comprobante "+slTipoComp+slNumComp);
        },
        headers: {
           'my-header': 'foo'
        },
        params: olParam,
        scope:  this
    });
    
    
}


// esl	21/07/2010	Funcion que muestra los reportes LibroBanco, Estado de Cuenta y de Conciliacion
function fReportes(tipo){
    /*
     *Parametros de reporte: (tipo)
     *1 = libro bancos
     *2 = estado de cuentas
     *3 = conciliacion
    */
    var pagina = "";
    var url = "";
    var pTip = "";
 
    
    if (app.cl.gridDet.store.data.length >= 1){
    
	if (tipo==1){
	    pagina = "../../AAA_SEGURIDAD/Co_Files/CoTrTr_libroban.rpt.php?";
	}else if (tipo==2){
	    pagina = "../../AAA_SEGURIDAD/Co_Files/CoTrTr_estadocue.rpt.php?";
	}else if(tipo==3){
	    pagina = "../../AAA_SEGURIDAD/Co_Files/CoTrTr_conciliacion.rpt.php?";
	}
	
	if (Ext.getCmp("pTip").checked == true){
	    pTip="T";
	}
	
	// Se toman los datos de cualquier fila porque para todas es igual, en este caso de la primera siempre que exista 1 o mas
	var r = app.cl.gridDet.getStore().data.items[0].data;
	
	
	url = "pQryCom=det_codcuenta='"+r.det_codcuenta+"' AND det_idauxiliar = '"+r.det_idauxiliar+"'&pID="+r.IdRegistro+" &pCue="+r.det_codcuenta+"&pAux="+r.det_idauxiliar+" &pFec='"+new Date().format("Y-m-d")+"'&pTip="+pTip;
    
	window.open(pagina+url);
    }
}
    
    
    