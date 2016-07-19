/*
*  Configuracion de Autogrid para presentar las transacciones para comex
**/

Ext.namespace("app", "app.cons");
Ext.onReady(function(){
    
    var storeX= Ext.extend(Ext.data.GroupingStore,{  
        sorInfoX : {field:'tra_Id', direction: 'DESC'},
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
            url: (!sgLoadUrl)? 'CoAdFi_PrestamoCons.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'tra_Id'},
            ['id']
        ),
        sortInfo: {field: 'tra_Id', direction: 'DESC'},
        sortInfoX: {field: 'tra_Id', direction: 'DESC'}, 
        groupOnSort:false ,
        remoteSort: true
    });
    
    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type:'numeric', dataIndex: 'tra_Id'}
	,{type:'numeric', dataIndex: 'tra_Receptor'}
	,{type:'string', dataIndex: 'aux_nombre'}
	,{type:'string', dataIndex: 'tra_Motivo'}
	,{type:'numeric', dataIndex: 'tra_Cuotas'}
	,{type:'numeric', dataIndex: 'tra_Valor'}
	,{type:'string', dataIndex: 'transaccion'}
	,{type:'string', dataIndex: 'estado'}]
        , autoreload:true
    });
    
    app.cons.gridTrans = new Ext.ux.AutoGridPanel({
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
                        handler: function(){basic_printGrid(app.cons.gridTrans);}
                    }
		    ,'-'/*,{
                        pressed: false
                        ,enableToggle:true
                        ,text: 'Cobros'
			,iconCls: 'iconBuscar'
                        ,cls: 'x-btn-text-icon details' 
                        ,handler: function(){mostrarCobros()}
                    }
		    ,'-'*/
		    ,{
                        id: "cmbEstados"
			,editable: false
                        ,xtype: 'genCmbBox'
			,width:100
			,hidden:false
			,minChars: 1
			,sqlId: 'CoAdFi_estadosAp'
			,listeners: {'select': function(cmb,record,index){
                                Ext.getCmp("btnAplicar").disabled = false;
			    }}
			
                    }
		    ,{
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
        
    olPanel.add(app.cons.gridTrans);
    app.cons.gridTrans.store.load({params: {meta: true, start:0, limit:9999, sort:'tra_Id', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();
   
    app.cons.gridTrans.on('rowdblclick', function(sm, rowIdx,e ) {
	var pRec = app.cons.gridTrans.getStore().getAt(rowIdx);
	ConsultarTrans(pRec.get("tra_Id"))
 })
//
    app.cons.gridTrans.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {
	
    })

});

function mostrarCobros(){
    var tx_TraId=0;
    var olGrd = Ext.getCmp("gridDetalle");
    var olRst = olGrd.getSelections();
    
    Ext.each(olRst, function(pItm, pIdx, pArr){
        tx_TraId  = olRst[pIdx].data["tra_Id"];
	var slUrl = "CoAdFi_PrestamoCob.php?init=1&tra_Id="+tx_TraId;
	addTab({id:'gridCob_'+tx_TraId, title:'Cobro Trans. No: '+tx_TraId, url:slUrl, tip: 'Consulta de Transacciones'});
    })
}

var respuesta = false; // para el mensaje

function aplicarEstado(){
    var tx_EstNuevo = 0;    
    if (! Ext.getCmp("cmbEstados").getSelectedRecord() ){
	Ext.MessageBox.alert('Seleccione un Estado para aplicar');
    }
    else{
	tx_EstNuevo =  Ext.getCmp("cmbEstados").getSelectedRecord().data.cod;
	Ext.MessageBox.confirm('Aplicar Estado', '¿Está seguro que desea aplicar el estado a la transacción?',
	function aplicar(respuesta) {
	 if(respuesta == 'yes'){
	    //Proceso para aplicar estado
	    var tx_EstAct;
	    var olGrd = Ext.getCmp("gridDetalle");
	    var olRst = olGrd.getSelections();
	    var tx_tra_Id;
	    
	    Ext.each(olRst, function(pItm, pIdx, pArr){
		    tx_EstAct  = olRst[pIdx].data["tra_Estado"];
		    tx_tra_Id = olRst[pIdx].data["tra_Id"];
		    if (tx_EstAct != 1){ // Sólo las transacciones pendientes.
		       Ext.MessageBox.alert('No se puede cambiar el estado de la transacción '+tx_tra_Id+' - no está PENDIENTE');
		    }
		    else{
			if (tx_EstNuevo == 2){ //Si se está aprobando la transacción hay que contabilizar
			    //fContabilizar(options.params.tra_Id);
			    fContabilizar(tx_tra_Id,tx_EstNuevo); 
			}
			else{
			    // Si no se va a contabilizar entonces solo actualiza el estado de la transaccion
			    actualizarEstado(tx_EstNuevo, tx_tra_Id,'',0)
			}
		    }		    
	    })
	 }
	});	
    }
}

function actualizarEstado(pEstNuevo, ptra_Id, tpComp, nComp){
    // Actualizar estado en tabla genTransac
    var slAction = 'UPD';
    oParams = {};
    oParams.pTabla = 'genTransac';
    oParams.tra_Id = ptra_Id;
    oParams.tra_Estado = pEstNuevo
    oParams.tra_UsuAprueba = ses_usuario;
    oParams.tra_FecAprueba = new Date().format('Y-m-d');
    oParams.tra_TipoComp = tpComp;
    oParams.tra_NumComp = nComp;
		    
    Ext.Ajax.request({
	waitMsg: 'GRABANDO...',
	url:	'../Ge_Files/GeGeGe_generic.crud.php?pAction=' + slAction,
	method: 'POST',
	params: oParams,
	success: function(response,options){
	      Ext.Msg.alert('Actualizar Estado', 'Transaccion Actualizada');
	      Ext.getCmp("gridDetalle").store.load();
	      window.open("CoAdFi_PrestamoRep.rpt.php?&tra_Id="+ptra_Id);
	      
	      if (nComp > 0){
		// Guardar registro en bitacora: Cambio de estado de la transaccion
		fGrabarBitacora("APR.",'PRE',ptra_Id, 0,0, 0);
		// Guardar registro en bitacora: Creacion del comprobante
		fGrabarBitacora("APR",'PP',ptra_Id, 0,0, nComp);
	      }
	      else{
		// Guardar registro en bitacora: Cambio de estado de la transaccion
		fGrabarBitacora("ANUL.",'PRE',ptra_Id, 0,0, 0);
	      }
	  }, 
	failure: function(form, e) {
	  Ext.Msg.alert('Actualizar Estado', 'No se aplico el estado para la transaccion: '+ptra_Id);
	}
      });
}

function fContabilizar(tra_Id, pEstNuevo){
    // Verificar si requiere plan de pagos, si tiene ingresado plan de pagos.
    Ext.Ajax.request({
		      waitMsg: 'CONSULTANDO DATOS DE LA TRANSACCION...'
		      ,url: '../Ge_Files/GeGeGe_queryToJson.php'
		      ,params: {id: 'CoAdFi_CuotasAp', ID: tra_Id}
		      ,method: 'POST'
		      ,success: function(response, opts) {
			    // var obj = Ext.decode(response.responseText);
			    eval("obj=" + response.responseText)
			    obj = obj.data[0];
			    var bOk = 0; // bok = 1 El plan de pagos es correcto bok=0 Hay algun problema con el plan de pagos, no se debe contabilizar
			    var cuotasdet = parseInt(obj.cuotasdet);
			    var maxcuotas = parseInt(obj.maxcuotas);
			    
			    // validaciones de las cuotas:
			    if ((obj.planpago ==1) && (obj.cuotasdet<1)){ //Si usa plan de pagos y no tiene cuotas ingresadas
				bOk = 0;
			    }else if ( cuotasdet > maxcuotas){ //Si el numero de cuotas es mayor al numero de cuotas permitido
				bOk = 0;
			    }else if (obj.cuotastra != obj.cuotasdet){ //Si el numero de cuotas es diferente al numero de cuotas en la cabecera de la transaccion
				bOk = 0;
			    }else{
				bOk = 1;
			    }
			    
			    if (bOk == 1) {
			     // Contabilizar la transaccion
			    Ext.Ajax.request({
					      waitMsg: 'GRABANDO...',
					      url:	'CoAdFi_contabapli.pro.php',
					      method: 'POST'
					      ,params: {pReg: 1
							,pGen:1
							,pTipo:'PP'
							,PPro:0
							,pTra:tra_Id}
					      ,success: function(response,options){
						var responseData = Ext.util.JSON.decode(response.responseText);
						actualizarEstado(pEstNuevo, tra_Id,responseData[0].com_TipoComp,responseData[0].com_NumComp)
					      }, 
					      failure: function(form, e) {
						Ext.Msg.alert('Contabilizar', "No fue contabilizada la transaccion");
					      }
					    });
			    }
			    else{
				Ext.Msg.alert("Advertencia",'Las cuotas del plan de pago de la transaccion:'+tra_Id+' no son correctas - no se puede contabilizar');
			    }
		      }, 
		      failure: function(form, e) {
			Ext.Msg.alert('No se pudo leer informacion de la transaccion');
		      }
		    });
   
}
