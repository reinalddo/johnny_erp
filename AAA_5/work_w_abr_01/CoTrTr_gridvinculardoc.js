/*
*  Configuracion de Autogrid para mostrar las ordenes de compra de un rango de fechas
*  Inicializa el grid, y luego llama a script php que devuelve los datos asociados, enviando como parametro
*  la cuenta y auxiliar requeridos.
*
* @param	pFecIni	    //fecha de inicio de consulta
* @param	pFecFin	    //fecha de fin de consulta
*
*
**/
//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
//debugger;
Ext.onReady(function(){
    //debugger;

    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        sorInfoX : {field:'com_Numcomp', direction: 'DESC'},
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
            url: (!sgLoadUrl)? 'CoTrTr_gridvinculardoc.php' : sgLoadUrl
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id', start:0, limit:9999},
            ['id']
        ),
        //groupField: 'com_Comproba',
        sortInfo: {field: 'com_Numcomp', direction: 'DESC'},
        sortInfoX: {field: 'com_Numcomp', direction: 'DESC'},
       // groupOnSort:true ,
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
            ,url: (!sgLoadUrl)? 'CoTrTr_gridvinculardoc.php?' : sgLoadUrl
            })
        ,baseParams:{
            init:0
            ,pPagina:0
            //,pTComp: app.vincular.paramDetalle.pTComp
            //,pNComp:  app.vincular.paramDetalle.pNComp
            ,pFecIni: app.vincular.paramDetalle.pFecIni
            ,pFecFin:  app.vincular.paramDetalle.pFecFin
            ,sort: "com_Numcomp"
            ,order:"DESC"
            }
        ,sortInfo: {field: "com_Numcomp", order:"DESC"}  // @bug: esto no funciona, fue necesario incluirlo en baseparams
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
    var filtersDet = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',   dataIndex: 'com_Comproba'},
	{type: 'numeric',   dataIndex: 'Cod_Item'},
	{type: 'string',   dataIndex: 'Item'},
	{type: 'numeric',   dataIndex: 'Pedido'},
	{type: 'numeric',   dataIndex: 'Redibido'},
	{type: 'numeric',   dataIndex: 'Pendiente'},
        {type: 'date',  dataIndex: 'com_FecTrans'},
        {type: 'date',   dataIndex: 'com_FecContab'},
        {type: 'string',     dataIndex: 'com_Receptor'},
	{type: 'string',  dataIndex: 'com_Concepto'},
        {type: 'string',   dataIndex: 'com_Usuario'}]
        , autoreload:true
    });

    name="Car";
    grid2 = new Ext.ux.AutoGridPanel({
        title:''
        ,id:'gridVincularDoc'
        ,height: 200
        //,width: 450
        ,style:"width:100%; font-size:8px"
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
            pageSize: 100,
            store: store,
            displayInfo: true,
            //displayMsg: '{0} a {1} de {2}',
            plugins: [filtersDet],
            displayMsg: ' ',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-',{
                    pressed: false,
                    enableToggle:true,
                    text: 'Imprimir',
                    cls: 'x-btn-text-icon details' ,
                    iconCls: 'iconImprimir',
                    handler: basic_printGrid
                }
        ]})
        ,plugins: [filtersDet/*, summaryDet*/]
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
        olPanel.add(grid2);
    //}


    this.grid2.store.load({params: {meta: true, start:0, limit:100, sort:'com_NumComp', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();

    grid2.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
    //cnt=olDat.conn.getresponseText();
	    //debugger;
                Ext.getCmp('pnlIzq').collapse();
                olPanel=Ext.getCmp('pnlDer');
                olPanel.setWidth=1200;
                olPanel.collapsible=true;
                if (olPanel.collapsed)olPanel.expand();
                if(Ext.getCmp('grdDetalle')) Ext.getCmp('grdDetalle').destroy();
                if (rowIdx >=0)  {
                    olPanel.add({
                    id: 'grdDetalle',
                    title: "Ingresos a Bodega",//app.cart.paramDetalle.pAuxil + "  " + pRec.get("aux_nombre"),
                    layout:'fit',
                    closable: true,
                    collapsible:true,
                    autoLoad:{url:'CoTrTr_gridvinculardet.php?',		// Objeto a cargar
                        params:{
                        init:1
                        ,pPagina:0
                        ,pObj: 'grdDetalle'
						,pRegNum: r.data.det_RegNUmero
						,pSecuencia: r.data.det_Secuencia
                        ,pTComp: r.data.com_TipoComp
                        ,pNComp:  r.data.com_Numcomp
                        ,proveedor: r.data.com_CodReceptor
			,pCodItem: r.data.Cod_Item
			,pCantidad: r.data.Pendiente
                    }
                    ,scripts: true, method: 'POST'}
                    }).show();
                    olPanel.doLayout();
                }
	});

})
