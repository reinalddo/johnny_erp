/*
 *  @author     Gina Franco
*  @date       19/Mayo/09
*  
*  proceso de Autogrid
*   consulta los movimientos de un banco en un rango de fechas determinado
*   parametros:
*       pAuxil  = codigo de auxiliar(banco)
*       fecInic = fecha de inicio
*       fecFin  = fecha fin de rango
*
**/
//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
//alert("url:" + getFromurl('pUrl',""));    
Ext.onReady(function(){
    
    // Create grid view
    //se utiliza para cambiar color a lineas de comprobantes anulados
    var gridView = new Ext.grid.GridView({ 
       //forceFit: true, 
        getRowClass : function (row, index) {
                            //debugger;
                            var cls = ''; 
                            var data = row.data; 
                            switch (data.com_Concepto) { 
                               case 'Anulado' : 
                                  cls = 'yellow-row'// highlight row yellow 
                                  break; 
                               /*case '3m Co' : 
                                  cls = 'green-row' 
                                  break; 
                               case 'Altria Group Inc' : 
                                  cls = 'red-row' 
                                  break;  */
                            }//end switch 
                            return cls; 
                         } 
    });  //end gridView 

    
    
    Ext.namespace("app", "app.cart");		     // Iniciar namespace cart
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        sorInfoX : {field:'com_FecContab', direction: 'DESC'},
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
            url: (!sgLoadUrl)? 'CoTrTr_salcxcgrid_movi.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id', /*start:0, limit:9999,*/totalProperty:'totalCount',remoteSort: true},
            ['id']
        ),
        //groupField: 'nombrcue',        
        sortInfo: {field: 'com_FecContab', direction: 'DESC'},
        sortInfoX: {field: 'com_FecContab', direction: 'DESC'}, 
        groupOnSort:false ,
        remoteSort: true
        /*,onMetaChange : function(store, meta)
        {
            //remove meta
            if( store && store.lastOptions && store.lastOptions.params )
                delete store.lastOptions.params.meta;
        }*/
    });
    
    

    /*@TODO:                                                                  sumatoria de  grid */
	// custom summary renderer example
    /*   */
	function numContenedores(v, params, data) {
		return v? ((v === 0 || v > 1) ? '(' + v +' Contenedores)' : '(1 Contenedor)') : '';
	}
	function totalSaldo(v, params, data) {
		return v?  v : '';
	}
    var summary = new Ext.ux.grid.GridSummary();    
 
    /*
    Ext.grid.GroupSummary.Calculations['Diferencia'] = function(v, record, field){
        return v + (record.data.cnt_CantDeclarada - record.data.vcp_CantNeta);
    }
    */
    var groupSum = new Ext.grid.GroupSummary();
    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',  dataIndex: 'com_TipoComp'},
        {type: 'numeric',  dataIndex: 'com_NumComp'},
        {type: 'date',  dataIndex: 'com_FecContab', dateFormat: 'Y-m-d'},
        {type: 'numeric',  dataIndex: 'det_ValDebito'},
        {type: 'numeric',  dataIndex: 'det_ValCredito'},
        {type: 'string',  dataIndex: 'com_Concepto'},
        {type: 'numeric',  dataIndex: 'det_EstLibros'},
        {type: 'date',  dataIndex: 'det_FecLibros', dateFormat: 'Y-m-d'}]
        , autoreload:true
    });

    name="Cnt";
    grid1 = new Ext.ux.AutoGridPanel({
        id:'cajaConsMovi'
        ,title:''
	,plugins: [filters]
        ,height: 300
        ,width: 800
        ,cnfSelMode: 'csm'  //CnfSelMode: propiedad para definir el tipo de selección de datos==> csm(CheckSelectionMode), csm(CellSelectionMode), rsms(RowSelectionMode Single), rsm(RowSelectionMode Multiple)
        ,loadMask: true
        ,stripeRows :true
        ,autoSave: true
        ,saveUrl: 'saveconfig.php'
        ,store : store
        ,pageSize:100
        ,monitorResize:true
        ,view: gridView
        ,tbar: [{
                    text: 'Anular',
                    tooltip: 'Anula un comprobante, solo se debe hacer cuando el cheque no ha sido entregado',
                    id: 'btnAnular',
                    listeners: {
                        click: fAnular
                    }
                    ,iconCls:'iconAnular'
                }]
        ,bbar: new Ext.PagingToolbar({
            pageSize: 100,//(this.pageSize? this.pageSize:9999),
            store: store,
            displayInfo: true,
            plugins: [filters],
            displayMsg: 'Registros {0} - {1} de {2}',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-'
            ,{
                pressed: false,
                enableToggle:true,
                text: 'Imprimir',
                cls: 'x-btn-text-icon details' ,
                iconCls: 'iconImprimir',
                handler: basic_printGrid
            }]
        })
        /*view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Personas" : "Personas"]})'
        }),*/
        ,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
            }
            
        }
        /*,onRender:function() {
                //debugger;
                //grid1.store.load({params:{meta: true, start:0, limit:9999, sort:'com_FecContab', dir:'DESC'}})
            }*/
       /*,afterlayout:{scope:this, single:true, fn:function() {
                    debugger;
                     grid1.store.load({params:{start:0, limit:10}});
                }
            }*/
    });

    // render grid principal
    Ext.getCmp(gsObj).add(grid1);
   
    //Ext.getCmp('paneles').doLayout();
    Ext.getCmp(gsObj).doLayout();
    this.grid1.store.load({params: {meta: true, start:0, limit:100, sort:'com_FecContab', dir:'DESC'}});// @TODO: load method must be applied over a dinamic referenced object, not 'this.grid1' referenced
     
    grid1.getView().refresh();
    
    /*this.gridFoot = this.grid1.getView().getFooterPanel(true);
    this.paging = new Ext.PagingToolbar(this.gridFoot,store, {
        pageSize: 10,
        displayInfo: true,
        displayMsg: 'Displaying {0} - {1} of {2}',
        emptyMsg: 'Nothing to display'
    });
    this.grid1.store.load({params: {meta: true, start:0, limit:10, sort:'com_FecContab', dir:'DESC'}});// @TODO: load method must be applied over a dinamic referenced object, not 'this.grid1' referenced
    */
    /*grid1.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {

        Ext.getCmp('pnlIzq').collapse();
        olPanel=Ext.getCmp('pnlDer');
        olPanel.setWidth=1200;
        olPanel.collapsible=true;
        if (olPanel.collapsed)olPanel.expand();
        if(Ext.getCmp('grdDetalle')) Ext.getCmp('grdDetalle').destroy();
        app.cart.paramDetalle ={pCuenta: pRec.get("det_codcuenta"), pAuxil: pRec.get("det_idauxiliar")};
        if (pRid >=0)  {
            olPanel.add({
            id: 'grdDetalle',
            title: app.cart.paramDetalle.pAuxil + "  " + pRec.get("aux_nombre"),
            layout:'fit',
            closable: true,
            collapsible:true,
            autoLoad:{url:'CoTrTr_salcxcgriddet.php?',		// Objeto a cargar
                params:{
                init:1
                ,pPagina:0
                ,pObj: 'grdDetalle'
                ,pCuent: app.cart.paramDetalle.pCuenta
                ,pAuxil:  app.cart.paramDetalle.pAuxil
            }
            ,scripts: true, method: 'POST'}
            }).show();
            olPanel.doLayout();
        }				//Re-renderiza el contenido;
    })*/

/*  this.grid1.onBeforerender=function(){
		cm=Ext.getCmp(gsObj).getColumnModel();
		cm.add(sm);
		cm.reconfigure()
    }
    this.grid1.onBeforeRender=function(){
		cm=Ext.getCmp(gsObj).getColumnModel();
		cm.add(sm);
    }
*/

})

function toggleDetails(btn, pressed){
    var view = grid1.getView();
    view.showPreview = pressed;
    view.refresh();
}

/* Permite anular un comprobante de forma directa, es decir:
 * actualiza a 0 debito y credito
 * en glosa actualiza con 'ANULADO'
*/
function fAnular(){
    
    //debugger;
    
    if (0 == grid1.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Por favor seleccione comprobante a anular');
        return;
    }
    
    var slTipoComp = grid1.getSelectionModel().getSelected().data.com_TipoComp;
    var slNumComp = grid1.getSelectionModel().getSelected().data.com_NumComp;
    var slConcepto = grid1.getSelectionModel().getSelected().data.com_Concepto;
    var slFecContab = new Date();
    slFecContab = grid1.getSelectionModel().getSelected().data.com_FecContab;
    slFecContab = slFecContab.format("Y-m-d");
    
    if ("Anulado" == slConcepto){
        Ext.Msg.alert('ALERTA','Comprobante ya esta anulado');
        return;
    }
        
    var olParam= {
        id : "CoTrTr_anulaComprob"
        ,pTipoComp : slTipoComp
        ,pNumComp  : slNumComp
        ,pAuxil  : Ext.getCmp("txt_bancos").getValue()
        ,pFecContab : slFecContab
    }
    Ext.Ajax.request({
        url: 'CoTrTr_cajaAnulaComprob.php',
        success: function(pResp, pOpt){
            //debugger;
            var olRsp = eval("(" + pResp.responseText + ")")
            if ("" != olRsp.message){
                Ext.Msg.alert('Alerta',olRsp.message);
            }else{
                var sm2 = grid1.getSelectionModel().getSelected();
                sm2.set('com_Concepto','Anulado');//rec.id);
                sm2.set('det_ValDebito',0);
                sm2.set('det_ValCredito',0);
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