/*
*  Configuracion de Autogrid para conciliaciones: Carga las conciliaciones realizadas de un banco
*  especifico. Inicializa el grid, y luego llama a script php que devuelve los datos asociados, enviando como parametro
*  la cuenta y auxiliar requeridos.
*  
* @param	pAuxil	Codigo de Auxiliar 
*
*
**/
//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
//debugger;
Ext.namespace("app", "app.cl");
Ext.onReady(function(){
    //debugger;
    var store = new Ext.data.JsonStore({
        root: 'rows'
        ,totalProperty: 'totalCount'
        ,idProperty: 'id'
        ,remoteSort: true
        ,fields:['id']

	    ,proxy: new Ext.data.HttpProxy({
            method:"POST"
            ,url: (!sgLoadUrl)? 'CoTrCl_conciliaciongrid.php?' : sgLoadUrl
            })
        ,baseParams:{
            init:0
            ,pPagina:0
            ,pCuent: app.cl.paramDetalle.pCuenta
            ,pAuxil:  app.cl.paramDetalle.pAuxil
            ,sort: "con_FecCorte"
            ,order:"DESC"
            }
        ,sortInfo: {field: "con_FecCorte", order:"DESC"}  // @bug: esto no funciona, fue necesario incluirlo en baseparams
        ,remoteSort: true
    });
    //store.setDefaultSort('det_numdocum', 'ASC');         // @bug: esto no funciona, fue necesario incluirlo en baseparams

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
    app.cl.gridBuscar = new Ext.ux.AutoGridPanel({
        title:''
        ,id:'gridConciliacion'
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
            pageSize: 25,
            store: store,
            displayInfo: true,
            //displayMsg: '{0} a {1} de {2}',
            displayMsg: ' ',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-'/*, {
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
			app.cl.gridBuscar.getSelectionModel().clearSelections();
		}}*/
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
        olPanel.add(app.cl.gridBuscar);
    //}
    
    
    app.cl.gridBuscar.store.load({params: {meta: true, start:0, limit:25, sort:'con_FecCorte', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();
    
    app.cl.gridBuscar.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
                //debugger;
                var slFec = r.data.con_FecCorte.dateFormat('Y-m-d');
                var slFec2 = r.data.con_FecCorte.dateFormat('d-m-y');
                var slUrl = "CoTrCl_conciliaciongridDet.php?init=1&pAuxil=" + Ext.getCmp("txt_bancos").getValue() + "&fecCorte=" + slFec + "&pCuenta=" + Ext.getCmp("txt_cuenta").getValue();
		app.cl.paramDetalle ={fecCorte: slFec};
                addTab({id:'gridConcDet', title:'Movim. al '+slFec2, url:slUrl});
                tabs_c.findById("gridConcDet").setTitle('Movim. al '+slFec2);
	});
    /*app.cl.gridBuscar.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        var flSum = Ext.getCmp('flSumaSelec');
	r.set("txt_pago",0);
        flSum.setValue(flSum.getValue() - r.data.txt_pago)
	//@TODO ; Cargar comprobante
	i=0;
	return true;
	}
    );*/
})


