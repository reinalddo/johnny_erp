/*
*  Configuracion de Autogrid para presentar las transacciones para comex
**/

Ext.namespace("app", "app.bit");
Ext.onReady(function(){
    
    var storeX= Ext.extend(Ext.data.GroupingStore,{  
        sorInfoX : {field:'bit_numDoc', direction: 'DESC'},
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
            url: (!sgLoadUrl)? 'BiTrTr_bitacoraRecibir.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'bit_numDoc'},
            ['id']
        ),
        sortInfo: {field: 'bit_numDoc', direction: 'DESC'},
        sortInfoX: {field: 'bit_numDoc', direction: 'DESC'}, 
        groupOnSort:false ,
        remoteSort: true
    });
    
   
   /* GRID PARA CONSULTAR DOCUMENTOS PENDIENTES*/
    app.bit.gridRecib = new Ext.ux.AutoGridPanel({
       
        title:''
        ,id:'grdbitRecib'
        ,height:450
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
	,tbar:new Ext.Toolbar({
	    displayInfo: true,
	    displayMsg: 'Seleccione los documentos a procesar'
	    ,items:[{	pressed: false,
                        enableToggle:true,
                        text: 'Recibir',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconAplicar',
                        handler: function(){fmostrarRecib();}
                    },{	pressed: false,
                        enableToggle:true,
                        text: 'Rechazar',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconBorrar',
                        handler: function(){fmostrarRechazar();}
                    },'-',"Para seleccionar varios documentos use la tecla Ctrl"]
	    })
        ,bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: store,
            displayInfo: true,
            displayMsg: ' ',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-',{
                        pressed: false,
                        enableToggle:true,
                        text: 'Imprimir',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconImprimir',
                        handler: function(){basic_printGrid(app.bit.gridRecib);}
                    }
        ]})
        ,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
            }
        }
    });

    
    
    /**AGREGAR GRID AL PANEL
    */
    var panelgrd = new  Ext.Panel({
	id : "panelGrd"
	,items:[app.bit.gridRecib]
    });
    Ext.getCmp(gsObj).add(panelgrd);
    app.bit.gridRecib.store.load({params: {meta: true, start:0, limit:25, totalProperty:"totalRecords",sort:'bit_numDoc', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();
    
    app.bit.gridRecib.on('rowdblclick', function(sm, rowIdx,e ) {
	var pRec = app.bit.gridRecib.getStore().getAt(rowIdx);
	//ConsultarTrans(pRec.get("bit_numDoc"))
    })
    
    app.bit.gridRecib.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {
    })
});


function fmostrarRecib(){
    if (0 == app.bit.gridRecib.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Seleccione un documento');
        return;
    }
	
    var win;
    if(!Ext.getCmp('winRecibe') && !Ext.getCmp('winRechazo')){
	var frmRecib = new Ext.form.FormPanel({
            id: "frmbitRecib"
            ,width:300
            ,baseCls: 'x-small-editor'
	    ,border:false
            ,layout:'form'
	    ,labelWidth:70
            ,defaults:{anchor:'97%'}
            ,items:[{
			fieldLabel:'Movimiento'
			,id:'bit_movimiento'
			,xtype: 'genCmbBox'
			,minChars: 1
			,sqlId: 'BiTrTr_bitmovimiento'
			,allowBlank:false
			,width:200
		    },{
			fieldLabel:'Observacion'
			,id:'bit_observacion'
			,xtype: 'textfield'
			,maxLength:200
			,width:200
		    }]
            ,buttons: [{
		text:'Recibir'
		,iconCls: 'iconAplicar'
		,handler: function(){
		    var oParams = {};
		    oParams.bit_movimiento = Ext.getCmp('frmbitRecib').findById('bit_movimiento').getValue();
		    oParams.bit_observacion = Ext.getCmp('frmbitRecib').findById('bit_observacion').getValue();
		    
		    var alSel = app.bit.gridRecib.getSelectionModel().selections;
		    for (var i=0 ; i <  alSel.items.length; i++) {
			oParams.bit_tipoDoc = alSel.items[i].data.bit_tipoDoc;
			oParams.bit_secDoc  = alSel.items[i].data.bit_secDoc;
			oParams.bit_emiDoc  = alSel.items[i].data.bit_emiDoc;
			oParams.bit_numDoc  = alSel.items[i].data.bit_numDoc;
			oParams.bit_idAux   = alSel.items[i].data.bit_idAux;
			oParams.bit_registro   = alSel.items[i].data.bit_registro;
			oParams.bit_secuencia   = alSel.items[i].data.bit_secuencia;
			fRecibir(oParams,'RCB');
			win.close();
		    }
		}
	    },{
		text: 'Cancelar',
		handler: function(){
		    win.close();
		}
	    }]
        });
	
	win = new Ext.Window({
	    id:'winRecibe',
	    layout:'fit',
	    width:300,
	    height:150,
	    title:"Seleccionar tipo de movimiento",
	    plain: true,
	    items:frmRecib
	    });
	win.show();
	win.center();
    }
};

/* Rechazar documentos en lugar de recibirlos.
*/
function fmostrarRechazar(){
    if (0 == app.bit.gridRecib.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Seleccione un documento');
        return;
    }
	
    if(!Ext.getCmp('winRecibe') && !Ext.getCmp('winRechazo2')){
	var frmRechaz2 = new Ext.form.FormPanel({
            id: "frmbitRechaz2"
            ,width:300
            ,baseCls: 'x-small-editor'
	    ,border:false
            ,layout:'form'
	    ,labelWidth:70
            ,defaults:{anchor:'97%'}
            ,items:[{
			fieldLabel:'Motivo'
			,id:'bit_motivoRechazo'
			,xtype: 'genCmbBox'
			,minChars: 1
			,sqlId: 'BiTrTr_bitmotivoRechazo'
			,allowBlank:false
			,width:200
		    },{
			fieldLabel:'Revisor'
			,id:'bit_revisor'
			,xtype: 'genCmbBox'
			,minChars: 1
			,sqlId: 'BiTrTr_bitRevisores'
			,allowBlank:false
			,width:200
		    },{
			fieldLabel:'Observacion'
			,id:'bit_observacion2'
			,xtype: 'textfield'
			,maxLength:200
			,width:200
		    }]
            ,buttons: [{
		text:'Rechazar'
		,iconCls: 'iconBorrar'
		,handler: function(){
		    var oParams = {};
		    oParams.bit_motivoRechazo = Ext.getCmp('frmbitRechaz2').findById('bit_motivoRechazo').getValue();
		    oParams.bit_observacion = Ext.getCmp('frmbitRechaz2').findById('bit_observacion2').getValue();
		    oParams.bit_revisor = Ext.getCmp('frmbitRechaz2').findById('bit_revisor').getValue();
		   
		    var alSel = app.bit.gridRecib.getSelectionModel().selections;
		    for (var i=0 ; i <alSel.items.length; i++) {
			oParams.bit_tipoDoc = alSel.items[i].data.bit_tipoDoc;
			oParams.bit_secDoc  = alSel.items[i].data.bit_secDoc;
			oParams.bit_emiDoc  = alSel.items[i].data.bit_emiDoc;
			oParams.bit_numDoc  = alSel.items[i].data.bit_numDoc;
			oParams.bit_idAux   = alSel.items[i].data.bit_idAux;
			oParams.bit_registro  = alSel.items[i].data.bit_registro;
			oParams.bit_secuencia   = alSel.items[i].data.bit_secuencia;
			fRechaza(oParams,'DEV'); 
			win3.close();
		    }
		}
	    },{
		text: 'Cancelar',
		handler: function(){
		    win3.close();
		}
	    }]
        });
	
	var win3 = new Ext.Window({
	    id:'winRechazo2',
	    layout:'fit',
	    width:300,
	    height:150,
	    title:"Seleccionar motivo del rechazo",
	    plain: true,
	    items:frmRechaz2
	    });
	win3.show();
	win3.center();
    }
};

function fRechaza(oParams,tipo){
        Ext.Ajax.request({
		url:"BiTrTr_bitacoraEnviaDoc.php?tipo="+tipo
		,params:oParams
		,waitMsg: 'ACTUALIZANDO...'
		,method:   'POST'
		,success: function(pResp, pOpt){
			
		        app.bit.gridRecib.store.load({params: {meta: true, start:0, limit:25, totalProperty:"totalRecords", sort:'bit_numDoc', dir:'DESC'}});
			var mensaje = pResp.responseText;
			Ext.Msg.alert('Alerta',mensaje);
		}
		,failure: function(pResp, pOpt){
			Ext.Msg.alert("No se actualizaron los registros");
		}
		});
};



function fRecibir(oParams,tipo){
        Ext.Ajax.request({
		url:"BiTrTr_bitacoraRecibeDoc.php?tipo="+tipo
		,params:oParams
		,waitMsg: 'ACTUALIZANDO...'
		,method:   'POST'
		,success: function(pResp, pOpt){
			
		        app.bit.gridRecib.store.load({params: {meta: true, start:0, limit:25, totalProperty:"totalRecords", sort:'bit_numDoc', dir:'DESC'}});
			var mensaje = pResp.responseText;
			Ext.Msg.alert('Alerta',mensaje);
		}
		,failure: function(pResp, pOpt){
			alert("No se actualizaron los registros");
		}
		});
};



