/*
*  Configuracion de Autogrid para conciliaciones: Carga las conciliaciones realizadas de un banco
*  especifico. Inicializa el grid, y luego llama a script php que devuelve los datos asociados, enviando como parametro
*  la cuenta y auxiliar requeridos.
*  
* @param	pAuxil	Codigo de Auxiliar 
*
*
**/

Ext.namespace("app", "app.cheque");

//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
//debugger;
Ext.onReady(function(){
    //debugger;
    
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        sorInfoX : {field:'fecha', direction: 'DESC'},
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
            url: (!sgLoadUrl)? 'CoTrTr_chequesconfirmar.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id'/*, start:0, limit:9999*/},
            ['id']
        ),
        //groupField: 'ID',        
        sortInfo: {field: 'fecha', direction: 'DESC'},
        sortInfoX: {field: 'fecha', direction: 'DESC'}, 
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
        {type: 'numeric',  dataIndex: 'idbatch'},
        {type: 'date',  dataIndex: 'fecha'},
        {type: 'string',  dataIndex: 'origen'},
        {type: 'string',  dataIndex: 'observacion'},
        {type: 'numeric',  dataIndex: 'totCheq'}]
        , autoreload:true
    });
    
    name="Car";
    app.cheque.gridConfirm = new Ext.ux.AutoGridPanel({
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
        ,tbar:[{
                    text: 'Confirmar'
                    ,tooltip: 'Confirma que ha recibido todos los cheques de un batch'
                    ,id: 'btnConfirm'
                    ,listeners: {
                        click: fConfirmaRecepcion
                    }
                    ,iconCls:'iconConfirmar'
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
                        handler: function(){basic_printGrid(app.cheque.gridConfirm);}
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
        olPanel.add(app.cheque.gridConfirm);
    //}
    
    
    app.cheque.gridConfirm.store.load({params: {meta: true, start:0, limit:9999, sort:'fecha', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();
    
    /*app.cheque.gridDet.on('rowclick', function(sm, rowIdx, e) {
                //debugger;
                //var olRec=app.cheque.gridDet.getSelectedRecord().node;
                if (true == Ext.getCmp("chkModificar").checked){
                    var r = app.cheque.gridDet.getStore().getAt(rowIdx);
                    var ilTipo = r.data.com_TipoComp;
                    var ilNum = r.data.com_NumComp;
                    var sm2 = app.cheque.gridDet.getSelectionModel().getSelected();
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
	});*/
    
})


function fConfirmaRecepcion(){
    //debugger;
    if (0 == app.cheque.gridConfirm.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Por favor seleccione registros a confirmar');
        return;
    }
    
    var alSel = app.cheque.gridConfirm.getSelectionModel().selections;
    var olDeta='';
    for (var r=0 ; r <  alSel.items.length; r++) {
        olDeta += "&batch["+r+"]="+alSel.items[r].data.batch;
        olDeta += "&regNum["+r+"]="+alSel.items[r].data.regNum;
        olDeta += "&secuencia["+r+"]="+alSel.items[r].data.secuencia;
        olDeta += "&origen["+r+"]="+alSel.items[r].data.origen;
    }
    fGrabarConfirm(2,olDeta,r);
};
    
function fGrabarConfirm(opc, detalles, total){
            
    var olParam= {
        id : "CoTrTr_chequeGrabar"
        //,pDetalles : detalles
        ,pOpc  : opc
    }
    Ext.Ajax.request({
        url: 'CoTrTr_chequesgrabar.php?tot='+total+detalles,
        success: function(pResp, pOpt){
            //debugger;
            var olRsp = eval("(" + pResp.responseText + ")")
            if ("" != olRsp.message){
                //debugger;
               
                //Ext.getCmp('btnImprimir').enable();
                //Ext.getCmp('btnConfirmar').disable();
                app.cheque.gridConfirm.store.load({params: {meta: true, start:0, limit:9999, sort:'fecha', dir:'DESC'}});
                 Ext.Msg.alert('Alerta',olRsp.message);
                //Ext.getCmp('winProceder').close();
            }else{
                Ext.Msg.alert('Alerta',"Error al grabar");
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