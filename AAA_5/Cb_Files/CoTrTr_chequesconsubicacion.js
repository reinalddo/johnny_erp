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
            url: (!sgLoadUrl)? 'CoTrTr_chequesubicacion.php' : sgLoadUrl  
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
        {type: 'numeric',  dataIndex: 'numComp'}]
        , autoreload:true
    });
    
    name="Car";
    app.cheque.gridCons = new Ext.ux.AutoGridPanel({
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
                    text: 'Reimprimir'
                    ,tooltip: 'Reimprimir constancia de envio'
                    ,id: 'btnReimprimir'
                    ,listeners: {
                        click: fReimprimir
                    }
                    ,iconCls:'iconReimprimir'
                }
               ]*/
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
                        handler: function(){basic_printGrid(app.cheque.gridCons);}
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
        olPanel.add(app.cheque.gridCons);
    //}
    
    
    app.cheque.gridCons.store.load({params: {meta: true, start:0, limit:9999, sort:'com_FecContab', dir:'DESC'}});
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



/* Muestra una pantalla,se muestran que cheques se enviaron y a quien
*/
function fReimprimir(){
    
    var alSel = app.cheque.gridCons.getSelectionModel().selections;
    var griddata = [];
    var glosa = "";
    olDeta='';// = new Array();
    for (var r=0 ; r <  alSel.items.length; r++) {
        griddata.push(alSel.items[r].data);
        glosa += "Cheque # "+alSel.items[r].data.cheque+" - Banco: "+alSel.items[r].data.banco+"\n";
        //debugger;
        olDeta += "&cheque["+r+"]="+alSel.items[r].data.cheque;
        olDeta += "&banco["+r+"]="+alSel.items[r].data.banco;
        olDeta += "&regnum["+r+"]="+alSel.items[r].data.regNum;
        olDeta += "&secuencia["+r+"]="+alSel.items[r].data.secuencia;
    }
    
    //debugger;
    //fGrabar(1, olDeta, r);
    //Ext.Msg.alert("Alerta",glosa);
    //debugger;
    app.cheque.codDestino = Ext.getCmp("txtusuario").getValue();
    app.cheque.destino = Ext.getCmp("txtusuario").lastSelectionText;
    app.cheque.observacion = Ext.getCmp("observacion").getValue();
    glosa = "Usuario Origen: "+app.cheque.usuario+"\n"+
            "Usuario destino: "+app.cheque.destino
            +"\n"+glosa;
    if ("" != app.cheque.observacion)
        glosa +="\n\n Observacion: "+app.cheque.observacion;
    
    var olTexto = {
            xtype:'textarea'
            ,fieldLabel:'Por favor verifique que los datos sean correctos'
            ,id:'confirmar'
            ,value:glosa
            ,height:140
        };
        
    if (!Ext.getCmp('winProceder')){
        Ext.getCmp('winUbicar').close();
        var frm = new Ext.form.FormPanel({
            id: "frmWinProceder"
            ,width:350
            ,baseCls: 'x-small-editor'
	    ,border:false
            ,layout:'form'
            ,labelWidth:65
            ,defaults:{anchor:'97%'}
            ,items:[olTexto]
            ,buttons:[
		/*{
                    id : 'btnConfirmar',
		    text:'Confirmar'//,
		    //type:'submit',
		    ,iconCls: 'iconNuevo'
		    ,handler:fConfirmar
		},*/{
                    id : 'btnImprimir',
		    text:'Imprimir',
		    type:'submit'
		    //,handler:fGrabar
		    ,iconCls: 'iconImprimir'
                    ,disabled: true
		},
		{
		    text:'Salir'
		    ,iconCls: 'iconSalir'
		    ,handler:function(){
			win.close();
		    }
		}
	    ]
        });
    
        var win = new Ext.Window({
            title:'Confirmacion'
            ,layout:'fit'
            ,width:350
            ,height:220
	    ,id: "winProceder"
            ,items: frm
        });
        win.show();
    }
    
    
    return true;
};

