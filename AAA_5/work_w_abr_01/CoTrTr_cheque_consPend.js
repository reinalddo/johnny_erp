/*
*  Configuracion de Autogrid para presentar cheques que no han sido pagados.
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
            url: (!sgLoadUrl)? 'CoTrTr_cheque_consSubidos.php' : sgLoadUrl  
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
        {type: 'string',  dataIndex: 'banco'},
        {type: 'numeric',  dataIndex: 'cheque'},
        {type: 'date',  dataIndex: 'fecha'},
        {type: 'numeric',  dataIndex: 'valor'},
        {type: 'string',  dataIndex: 'beneficiario'},
        {type: 'string',  dataIndex: 'concepto'},
        {type: 'string',  dataIndex: 'tipoComp'},
        {type: 'string',  dataIndex: 'observacion'},
        {type: 'date',  dataIndex: 'fecRegistro'},
        {type: 'string',  dataIndex: 'usuario'}]
        , autoreload:true
    });
    
    name="Car";
    app.cheque.gridCheqPend = new Ext.ux.AutoGridPanel({
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
        /*,tbar:[{
                    text: 'Confirmar'
                    ,tooltip: 'Confirmar cheques con los cuales se va a generar archivo'
                    ,id: 'btnArchivo'
                    ,listeners: {
                        click: fGrabaEstado
                    }
                    ,iconCls:'iconProgramar'
                }
               ]*/
        ,bbar: new Ext.PagingToolbar({
            pageSize: 50,
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
                        handler: function(){basic_printGrid(app.cheque.gridCheqPend);}
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
        olPanel.add(app.cheque.gridCheqPend);
    //}
    
    
    app.cheque.gridCheqPend.store.load({params: {meta: true, start:0, limit:9999, sort:'com_FecContab', dir:'DESC'}});
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
    
});







