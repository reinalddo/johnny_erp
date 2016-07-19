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
            ,url: (!sgLoadUrl)? 'CoTrCl_conciliaciongridDet.php?' : sgLoadUrl
            })
        ,baseParams:{
            init:0
            ,pPagina:0
            //,pCuent: app.cart.paramDetalle.pCuenta
            ,pAuxil:  app.cart.paramDetalle.pAuxil
            ,sort: "com_FecContab"
            ,order:"DESC"
            }
        ,sortInfo: {field: "com_FecContab", order:"DESC"}  // @bug: esto no funciona, fue necesario incluirlo en baseparams
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
 
    */
    var filtersDet = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',  dataIndex: 'com_TipoComp'},
        {type: 'numeric',  dataIndex: 'com_NumComp'},
        {type: 'date',  dataIndex: 'com_FecContab'},
        {type: 'numeric',  dataIndex: 'det_ValDebito'},
        {type: 'numeric',  dataIndex: 'det_ValCredito'},
        {type: 'string',  dataIndex: 'com_Concepto'},
        {type: 'numeric',  dataIndex: 'det_EstLibros'},
        {type: 'date',  dataIndex: 'det_FecLibros'}]
        , autoreload:true
    });

    name="Car";
    grid2 = new Ext.ux.AutoGridPanel({
        title:''
        ,plugins: [filtersDet]
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
			grid2.getSelectionModel().clearSelections();
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
        olPanel.add(grid2);
    //}
    
    
    this.grid2.store.load({params: {meta: true, start:0, limit:25, sort:'com_FecContab', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();
    
    /*grid2.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
                //debugger;
                //var olRec=grid2.getSelectedRecord().node;
                var ilTipo = r.data.com_TipoComp;
                var ilNum = r.data.com_NumComp;
                var sm2 = grid2.getSelectionModel().getSelected();
                if ('0' == r.data.det_EstLibros ){
                    sm2.set('det_EstLibros','1');//rec.id);
                    sm2.set('det_FecLibros',Date.parseDate(r.data.fecCorte, 'Y-m-d'));
                }
                else{
                    sm2.set('det_EstLibros','0');//rec.id);
                    sm2.set('det_FecLibros',Date.parseDate("2050-12-31", 'Y-m-d'));
                }
                
                //Ext.Msg.alert('AVISO', ilTipo+ilNum);
	});*/
    grid2.on('rowclick', function(sm, rowIdx, e) {
                //debugger;
                //var olRec=grid2.getSelectedRecord().node;
                var r = grid2.getStore().getAt(rowIdx);
                var ilTipo = r.data.com_TipoComp;
                var ilNum = r.data.com_NumComp;
                var sm2 = grid2.getSelectionModel().getSelected();
                if ('0' == r.data.det_EstLibros ){
                    sm2.set('det_EstLibros','1');//rec.id);
                    sm2.set('det_FecLibros',Date.parseDate(r.data.fecCorte, 'Y-m-d'));
                }
                else{
                    sm2.set('det_EstLibros','0');//rec.id);
                    sm2.set('det_FecLibros',Date.parseDate("2050-12-31", 'Y-m-d'));
                }
                fModifEstadoFechaConcil2(r,ilTipo+ilNum);
                //Ext.Msg.alert('AVISO', ilTipo+ilNum);
	});
    /*grid2.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        var flSum = Ext.getCmp('flSumaSelec');
	r.set("txt_pago",0);
        flSum.setValue(flSum.getValue() - r.data.txt_pago)
	//@TODO ; Cargar comprobante
	i=0;
	return true;
	}
    );*/
})

function fModifEstadoFechaConcil2(rec,comp) {
        //debugger;
        var olParam= {
            id : "CoTrCl_conciliacionact",
            estLibros : rec.data.det_EstLibros,
            fecLibros  : rec.data.det_FecLibros.dateFormat("Y-m-d"),
            regNumero  : rec.data.com_RegNumero,  // Requiere Rope en lugar de Emb
            auxil : Ext.getCmp("txt_bancos").getValue(),
            cuenta  : Ext.getCmp("txt_cuenta").getValue()//"100100"
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
