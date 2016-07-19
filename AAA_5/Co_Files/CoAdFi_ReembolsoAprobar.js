/*
*  Configuracion de Autogrid para presentar las transacciones para comex
**/

Ext.namespace("app", "app.consREE");
Ext.onReady(function(){
    
    var storeX= Ext.extend(Ext.data.GroupingStore,{  
        sorInfoX : {field:'ree_Id', direction: 'DESC'},
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
            url: (!sgLoadUrl)? 'CoAdFi_ReembolsoAprobar.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'ree_Id'},
            ['id']
        ),
        sortInfo: {field: 'ree_Id', direction: 'DESC'},
        sortInfoX: {field: 'ree_Id', direction: 'DESC'}, 
        groupOnSort:false ,
        remoteSort: true
    });
    
    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type:'numeric', dataIndex: 'ree_Id'}
	,{type:'numeric', dataIndex: 'ree_Emisor'}
	,{type:'string', dataIndex: 'Emisor'}
	,{type:'string', dataIndex: 'ree_Fecha'}
	,{type:'numeric', dataIndex: 'ree_Valor'}
	,{type:'string', dataIndex: 'Estado'}
        ], autoreload:true
    });
    
    app.consREE.gridTrans = new Ext.ux.AutoGridPanel({
        title:''
        ,plugins: [filters]
        ,id:'gridDetalle'
        ,height: 200
	//,forceLayout :true
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
        ,tbar: new Ext.PagingToolbar({
            pageSize: 50,
            store: store,
            displayInfo: true,
            plugins: [filters],
	    displayMsg: ' ',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-',{
                        pressed: false,
                        enableToggle:true,
                        text: 'Imprimir',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconImprimir',
                        handler: function(){basic_printGrid(app.consREE.gridTrans);}
                    }
		    ,'-'
		    ,{
                        id: "cmbEstados"
			,editable: false
                        ,xtype: 'genCmbBox'
			,width:100
			,hidden:false
			,minChars: 1
			,sqlId: 'CoAdFi_EstReeAp'
			,listeners: {'select': function(cmb,record,index){
                                Ext.getCmp("btnAplicar").disabled = false;
			    }}
			
                    },{
					pressed: false
					,enableToggle:true
					//,disabled:true
					,id:'btnAplicar'
					,text: 'Aplicar'
					,iconCls: 'iconAplicar'
					,cls: 'x-btn-text-icon details' 
					,handler: function(){aplicarEstado()}
				    }
		    
		    ,'-'
        ]})
        ,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
            }
        }
    });
    
    var olPanel = Ext.getCmp(gsObj);
    olPanel.add(app.consREE.gridTrans);
    app.consREE.gridTrans.store.load({params: {meta: true, start:0, limit:9999, sort:'ree_Id', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();
   
    app.consREE.gridTrans.on('rowdblclick', function(sm, rowIdx,e ) {
	var pRec = app.consREE.gridTrans.getStore().getAt(rowIdx);
	ConsultarTrans(pRec.get("ree_Id"))
 })
//
    app.consREE.gridTrans.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {
	
    })
    
    if (app.panlREE.ReeAprobar == 0){
	Ext.getCmp("cmbEstados").setVisible(false);
	Ext.getCmp("cmbEstados").setDisabled(true);
	
	Ext.getCmp("btnAplicar").setVisible(false);
	Ext.getCmp("btnAplicar").setDisabled(true);
    }
    
    
});


var respuesta = false; // para el mensaje
function aplicarEstado(){
    if (app.panlREE.ReeAprobar == 0){
        Ext.Msg.alert('No tiene permisos para realizar esta accion');
        return;
    }
    
    
    var tx_EstNuevo = 0;    
    if (! Ext.getCmp("cmbEstados").getSelectedRecord() ){
	Ext.MessageBox.alert('Seleccione un Estado para aplicar');
    }
    else{
	tx_EstNuevo =  Ext.getCmp("cmbEstados").getSelectedRecord().data.cod;
	tx_TxEstNuevo =  Ext.getCmp("cmbEstados").getSelectedRecord().data.txt;
	Ext.MessageBox.confirm('Aplicar Estado', '¿Está seguro que desea aplicar el estado '+tx_TxEstNuevo+' a la transacción?',
	function aplicar(respuesta) {
	 if(respuesta == 'yes'){
	    //Proceso para aplicar estado
	    var tx_EstAct;
	    var olGrd = Ext.getCmp("gridDetalle");
	    var olRst = olGrd.getSelections();
	    var tx_ree_Id;
	    
	    Ext.each(olRst, function(pItm, pIdx, pArr){
		    tx_EstAct  = olRst[pIdx].data["ree_Estado"];
		    tx_ree_Id = olRst[pIdx].data["ree_Id"];
		    if (tx_EstAct != 1){ // Sólo las transacciones pendientes.
		       Ext.MessageBox.alert('No se puede cambiar el estado de la transacción '+tx_ree_Id+' - no está PENDIENTE');
		    }
		    else{
			if (tx_EstNuevo == 2){ //Si se está aprobando la transacción hay que contabilizar
			    fContabilizar(tx_ree_Id,tx_EstNuevo); 
			}
			else{
			    // Si no se va a contabilizar entonces solo actualiza el estado de la transaccion
			    actualizarEstado(tx_EstNuevo, tx_ree_Id,'',0)
			}
		    }		    
	    })
	 }
	});	
    }
}

function actualizarEstado(pEstNuevo, pree_Id, tpComp, nComp){
    // Actualizar estado en tabla genTransac
    var slAction = 'UPD';
    oParams = {};
    oParams.pTabla = 'conReembolso';
    oParams.ree_Id = pree_Id;
    oParams.ree_Estado = pEstNuevo
    oParams.ree_UsuAprueba = ses_usuario;
    oParams.ree_FecAprueba = new Date().format('Y-m-d');
    oParams.ree_TipoComp = tpComp;
    oParams.ree_NumComp = nComp;
		    
    Ext.Ajax.request({
	waitMsg: 'GRABANDO...',
	url:	'../Ge_Files/GeGeGe_generic.crud.php?pAction=' + slAction,
	method: 'POST',
	params: oParams,
	success: function(response,options){
	      Ext.Msg.alert('Actualizar Estado', 'Transaccion Actualizada');
	      //Ext.getCmp("gridDetalle").store.load();
	      app.consREE.gridTrans.store.load({params: {meta: true, start:0, limit:9999, sort:'ree_Id', dir:'DESC'}});
	      //window.open("CoAdFi_ReembolsoRep.rpt.php?&ree_Id="+pree_Id);
	      
	      if (nComp > 0){
		// Guardar registro en bitacora: Cambio de estado de la transaccion
		fGrabarBitacora("APR.",'REE',pree_Id, 0,0, 0);
		// Guardar registro en bitacora: Creacion del comprobante
		fGrabarBitacora("APR",'RG',pree_Id, 0,0, nComp);
		// IMPRIMIR REPORTE:
		var url = "";
		url = "CoAdFi_ReembolsoRep.rpt.php?&ree_Id="+pree_Id;
		window.open(url);
		
	      }
	      else{
		// Guardar registro en bitacora: Cambio de estado de la transaccion
		fGrabarBitacora("ANUL.",'REE',pree_Id, 0,0, 0);
	      }
	  }, 
	failure: function(form, e) {
	  Ext.Msg.alert('Actualizar Estado', 'No se aplico el estado para la transaccion: '+pree_Id);
	}
      });
}

function fContabilizar(ree_Id, pEstNuevo){
    // Verificar si requiere plan de pagos, si tiene ingresado plan de pagos.
    Ext.Ajax.request({
		waitMsg: 'GRABANDO...',
		url:	'CoAdFi_Reembcontabapli.pro.php',
		method: 'POST'
		,params: {pReg: 1
			  ,pGen:1
			  ,pTipo:'RG'
			  ,PPro:0
			  ,pTra:ree_Id}
		,success: function(response,options){
		  var responseData = Ext.util.JSON.decode(response.responseText);
		  actualizarEstado(pEstNuevo, ree_Id,responseData[0].com_TipoComp,responseData[0].com_NumComp)
		}, 
		failure: function(form, e) {
		  Ext.Msg.alert('Contabilizar', "No fue contabilizada la transaccion");
		}
	      });
}
