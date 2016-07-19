/*
*  Configuracion de Autogrid para presentar las transacciones para comex
**/

Ext.namespace("app", "app.bit");
Ext.onReady(function(){
    
    
    var storeX2= Ext.extend(Ext.data.GroupingStore,{  
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
    var store2 = new storeX2({
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
     var filters = new this.Ext.ux.grid.GridFilters({filters:[
	{type: 'string',  dataIndex: 'bit_empresa'},
        {type: 'string',  dataIndex: 'bit_tipoDoc'},
        {type: 'string',  dataIndex: 'bit_secDoc'},
	{type: 'string',  dataIndex: 'bit_emiDoc'},
	{type: 'string',  dataIndex: 'bit_numDoc'},
        {type: 'numeric',  dataIndex: 'bit_idAux'},
	{type: 'numeric',  dataIndex: 'bit_registro'},
        {type: 'string',  dataIndex: 'AuxNombre'},
        {type: 'date',  dataIndex: 'bit_fechaDoc'},
        {type: 'numeric',  dataIndex: 'bit_valor'},
        {type: 'date',  dataIndex: 'bit_fechaReg'},
        {type: 'date',  dataIndex: 'FechaReg'},
        {type: 'date',  dataIndex: 'HoraReg'},
        {type: 'string',  dataIndex: 'bit_usuarioActual'},
        {type: 'string',  dataIndex: 'movimiento'}]
        , autoreload:true
    });
    app.bit.gridEnvia = new Ext.ux.AutoGridPanel({
       
        title:''
	,plugins: [filters]
        ,id:'grdbitEnvia'
        ,height:450
	,pageSize: 25
	,style:"width:100%; font-size:8px"
        ,cnfSelMode: 'csmm'
	,loadMask: true
        ,stripeRows :true
        ,autoSave: true
	,saveUrl: 'saveconfig.php'  
        ,store : store2
        ,monitorResize:true
        ,animCollapse:false
        ,collapsible:true
	,tbar:new Ext.Toolbar({
	    items:[{	pressed: false,
                        enableToggle:true,
                        text: 'Enviar',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconAplicar',
                        handler: function(){fmostrarenviar();}
                    },{	pressed: false,
                        enableToggle:true,
                        text: 'Rechazar',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconBorrar',
                        handler: function(){fmostrarRechz('DEV');}
                    },{	pressed: false,
                        enableToggle:true,
                        text: 'Devolver a Cliente',
			id:'btn_devCliente',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconBorrar',
                        handler: function(){fmostrarRechz('RCH');}
                    },{	pressed: false,
                        enableToggle:true,
                        text: 'Cerrar Proceso',
			id:'btn_cierre',
                        cls: 'x-btn-text-icon details',
                        iconCls: 'iconEliminar',
                        handler: function(){fCerrarProceso();}
                    },'-','Para seleccionar varios documentos use la tecla Ctrl']
	    })
        ,bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: store2,
	    plugin:[filters],
            displayInfo: true,
            displayMsg: '',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-',{
                        pressed: false,
                        enableToggle:true,
                        text: 'Imprimir',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconImprimir',
                        handler: function(){basic_printGrid(app.bit.gridEnvia);}
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
    var panelgrdEnv = new  Ext.Panel({
	id : "panelGrdEnv"
	,items:[app.bit.gridEnvia]
    });
    Ext.getCmp(gsObj).add(panelgrdEnv);
    app.bit.gridEnvia.store.load({params: {meta: true, start:0, limit:25, totalProperty:"totalRecords", sort:'bit_numDoc', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();
   
    app.bit.gridEnvia.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {
	//Si usuario destino no esta en blanco significa que el documento ha sido enviado, no se lo puede volver a enviar hasta que el usuario lo reciba.
	// validar por usuariodestino, no por estado porque es el estado del registro que envia el documento a otro usuario no del que está en espera de ser recibido
	if (pRec.data.bit_usuariodestino != ''){
	    Ext.Msg.alert("El documento ya fue enviado a otro usuario");
	    app.bit.gridEnvia.getSelectionModel().deselectRow(pRid);
	}
	
    })
    
    if (app.bit.bitCierreProceso != 1){
        Ext.getCmp('btn_cierre').disable();
    }
    
    if (app.bit.bitDevolucionCliente != 1){
        Ext.getCmp('btn_devCliente').disable();
    }
    
    

});

var respuesta = false; // para el mensaje 
function fCerrarProceso(){
    if (0 == app.bit.gridEnvia.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Seleccione un documento');
        return;
    }
    
    Ext.MessageBox.confirm('Cerrar Proceso', '¿Esta seguro que desea cerrar el proceso para el documento seleccionado?',
	function eliminar(respuesta){
	if(respuesta == 'yes'){
	    //Cerrar proceso de bitacora para documentos seleccionados
		var oParams = {};
		
		var alSel = app.bit.gridEnvia.getSelectionModel().selections;
		
		for (var i=0 ; i <  alSel.items.length; i++) {
		    oParams.bit_tipoDoc = alSel.items[i].data.bit_tipoDoc;
		    oParams.bit_secDoc  = alSel.items[i].data.bit_secDoc;
		    oParams.bit_emiDoc  = alSel.items[i].data.bit_emiDoc;
		    oParams.bit_numDoc  = alSel.items[i].data.bit_numDoc;
		    oParams.bit_idAux   = alSel.items[i].data.bit_idAux;
		    oParams.bit_registro   = alSel.items[i].data.bit_registro;
		    fEnviar(oParams,'CIE');
		}
	   
	}
	}
    );
    
    
    
};


function fmostrarenviar(){
    if (0 == app.bit.gridEnvia.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Seleccione un documento');
        return;
    }
	
    var win3;
    if(!Ext.getCmp('winEnvia')){
	var frmEnvia = new Ext.form.FormPanel({
            id: "frmbitRecib"
            ,width:300
            ,baseCls: 'x-small-editor'
	    ,border:false
            ,layout:'form'
	    ,labelWidth:70
            ,defaults:{anchor:'97%'}
            ,items:[{
			fieldLabel:'Destino'
			,id:'bit_usuariodestino'
			,xtype: 'genCmbBox'
			,minChars: 1
			,sqlId: 'BiTrTr_bitusuarios'
			,allowBlank:false
			,width:200
		    },{
			fieldLabel:'Observacion'
			,id:'bit_observacionenvio'
			,xtype: 'textfield'
			,maxLength:200
			,width:200
		    }]
            ,buttons: [{
		text:'Enviar'
		,iconCls: 'iconAplicar'
		,handler: function(){
		    var oParams = {};
		    oParams.bit_usuariodestino = Ext.getCmp('frmbitRecib').findById('bit_usuariodestino').getValue();
		    oParams.bit_observacionenvio = Ext.getCmp('frmbitRecib').findById('bit_observacionenvio').getValue();
		    
		    var alSel = app.bit.gridEnvia.getSelectionModel().selections;
		    for (var i=0 ; i <  alSel.items.length; i++) {
			oParams.bit_tipoDoc = alSel.items[i].data.bit_tipoDoc;
			oParams.bit_secDoc  = alSel.items[i].data.bit_secDoc;
			oParams.bit_emiDoc  = alSel.items[i].data.bit_emiDoc;
			oParams.bit_numDoc  = alSel.items[i].data.bit_numDoc;
			oParams.bit_idAux   = alSel.items[i].data.bit_idAux;
			oParams.bit_registro   = alSel.items[i].data.bit_registro;
			oParams.bit_secuencia   = alSel.items[i].data.bit_secuencia;
			fEnviar(oParams,'ENV');
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
	
	win3 = new Ext.Window({
	    id:'winEnvia',
	    layout:'fit',
	    width:300,
	    height:150,
	    title:"Seleccionar usuario",
	    plain: true,
	    items:frmEnvia
	    });
	win3.show();
	win3.center();
    }
};



function fmostrarRechz(tipo){
    if (0 == app.bit.gridEnvia.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Seleccione un documento');
        return;
    }
	
    if(!Ext.getCmp('winEnvia') && !Ext.getCmp('winRechazo')){
	disable_revisor = false;
	
	if (tipo == 'RCH'){
	  disable_revisor = true;    
	}
	
	var frmRechaz = new Ext.form.FormPanel({
            id: "frmbitRechaz"
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
			,disabled:disable_revisor
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
		    oParams.bit_motivoRechazo = Ext.getCmp('frmbitRechaz').findById('bit_motivoRechazo').getValue();
		    oParams.bit_observacion = Ext.getCmp('frmbitRechaz').findById('bit_observacion2').getValue();
		    oParams.bit_revisor = Ext.getCmp('frmbitRechaz').findById('bit_revisor').getValue();
		   
		    var alSel = app.bit.gridEnvia.getSelectionModel().selections;
		    for (var i=0 ; i <alSel.items.length; i++) {
			oParams.bit_tipoDoc = alSel.items[i].data.bit_tipoDoc;
			oParams.bit_secDoc  = alSel.items[i].data.bit_secDoc;
			oParams.bit_emiDoc  = alSel.items[i].data.bit_emiDoc;
			oParams.bit_numDoc  = alSel.items[i].data.bit_numDoc;
			oParams.bit_idAux   = alSel.items[i].data.bit_idAux;
			oParams.bit_registro  = alSel.items[i].data.bit_registro;
			oParams.bit_secuencia   = alSel.items[i].data.bit_secuencia;
			fEnviar(oParams,tipo); 
			win2.close();
		    }
		}
	    },{
		text: 'Cancelar',
		handler: function(){
		    win2.close();
		}
	    }]
        });
	
	if (tipo == 'RCH'){
	    titulo = "<br><span style='color:blue;'>(Devolver a Cliente)</span>";    
	} else {
	    titulo = " ";
	}
		
	var win2 = new Ext.Window({
	    id:'winRechazo',
	    layout:'fit',
	    width:300,
	    height:170,
	    title:"Seleccionar motivo del rechazo"+titulo,
	    plain: true,
	    items:frmRechaz
	    });
	win2.show();
	win2.center();
	
    }
};

function fEnviar(oParams,tipo){
        Ext.Ajax.request({
		url:"BiTrTr_bitacoraEnviaDoc.php?tipo="+tipo
		,params:oParams
		,waitMsg: 'ACTUALIZANDO...'
		,method:   'POST'
		,success: function(pResp, pOpt){
			
		        app.bit.gridEnvia.store.load({params: {meta: true, start:0, limit:25, totalProperty:"totalRecords", sort:'bit_numDoc', dir:'DESC'}});
			var mensaje = pResp.responseText;
			Ext.Msg.alert('Alerta',mensaje);
		}
		,failure: function(pResp, pOpt){
			Ext.Msg.alert("No se actualizaron los registros");
		}
		});
};



