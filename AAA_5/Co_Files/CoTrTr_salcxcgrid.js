/*
*  proceso de Autogrid
*
*
*
**/
//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
//alert("url:" + getFromurl('pUrl',""));    
Ext.onReady(function(){
    Ext.namespace("app", "app.cart");		     // Iniciar namespace cart
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        sorInfoX : {field:'txt_Embarque', direction: 'ASC'},
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
            url: (!sgLoadUrl)? 'CoTrTr_salcxcgrid.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id', start:0, limit:9999},
            ['id']
        ),
        groupField: 'nombrcue',        
        sortInfo: {field: 'aux_Nombre', direction: 'ASC'},
        sortInfoX: {field: 'aux_Nombre', direction: 'ASC'}, 
        groupOnSort:false ,
        remoteSort: true
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
        {type: 'string',  dataIndex: 'det_codcuenta'},
        {type: 'numeric',  dataIndex: 'det_idauxiliar'},
        {type: 'string',  dataIndex: 'aux_nombre'},
        {type: 'numeric',  dataIndex: 'saldo'},
        {type: 'string',  dataIndex: 'nombcuenta'}]
        , autoreload:true
    });

    name="Cnt";
    grid1 = new Ext.ux.AutoGridPanel({
        title:'',
		plugins: [filters, summary, groupSum],
        height: 300,
        width: 800,
        cnfSelMode: 'rsms',  //CnfSelMode: propiedad para definir el tipo de selección de datos==> csm(CheckSelectionMode), csm(CellSelectionMode), rsms(RowSelectionMode Single), rsm(RowSelectionMode Multiple)
        loadMask: true,
        stripeRows :true,
        autoSave: true,
        saveUrl: 'saveconfig.php',                
        store : store,
        pageSize:25,
        monitorResize:true,
        bbar: new Ext.PagingToolbar({
            pageSize: (this.pageSize? this.pageSize:9999),
            store: store,
            displayInfo: true,
            plugins: [filters],
            displayMsg: 'Registros {0} - {1} de {2}',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-', {
                pressed: false,
                enableToggle:true,
                text: 'Ver Detalles',
                cls: 'x-btn-text-icon details' ,
                toggleHandler: toggleDetails
            }
            ,{
                pressed: false,
                enableToggle:true,
                text: 'Imprimir',
                cls: 'x-btn-text-icon details' ,
                handler: basic_printGrid
            }]
        }),
        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Personas" : "Personas"]})'
        }),
        listeners: {
            destroy: function(c) {
                c.getStore().destroy();
            }
        }
    });

    // render grid principal
    Ext.getCmp(gsObj).add(grid1);
   
    //Ext.getCmp('paneles').doLayout();
    Ext.getCmp(gsObj).doLayout();
    this.grid1.store.load({params: {meta: true, start:0, limit:9999, sort:'aux_nombre', dir:'ASC'}});// @TODO: load method must be applied over a dinamic referenced object, not 'this.grid1' referenced
 
    grid1.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {

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
    })

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
