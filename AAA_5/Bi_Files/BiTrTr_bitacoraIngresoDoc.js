Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"

//slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d';
Ext.namespace("app", "app.bit");
Ext.onReady(function(){
    /****** VARIABLES GLOBALES: ******/
    /*var usuario = "";
    var codUser = "";*/
    /********************************/
    //fCargaUsuario();
    Ext.QuickTips.init();
    
    //-------------CAMPOS PARA CONSULTA DE TODAS LAS PANTALLAS -------------------
    var slWidth="width:99%; text-align:'left'";
    
    var campos = new Ext.FormPanel({
    id:'frcampos' ,
    labelWidth: 40,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: true,
    width: 300
    });
    campos.add({ xtype:'datefield'
                ,fieldLabel:'Desde'
                ,id:'bit_fechaDesde'
                ,renderer: Ext.util.Format.dateRenderer('d/m/Y')
                ,format:'d-m-Y'
                ,maxValue:new Date()
                ,value:new Date()
                ,width:100
                ,allowBlank:false});
    
    campos.add({ xtype:'datefield'
                ,fieldLabel:'Hasta'
                ,id:'bit_fechaHasta'
                ,renderer: Ext.util.Format.dateRenderer('d/m/Y')
                ,format:'d-m-Y'
                ,maxValue:new Date()
                ,value:new Date()
                ,width:100
                ,allowBlank:false});
    //-------------------------------------------------
    
    /** Panel para Reporte de Ubicacion
 **/
  bitRep1 = ({
            xtype: 'form'
            ,id : 'bitRep1'
            ,labelAlign: 'left'
	    ,labelWidth: 120
            ,baseCls: "x-plain"
            ,ctCls: 'x-box-layout-ct normal'
            ,defaults: {labelStyle: 'font-weight:bold; font-size:8px; font-family:Arial, text-transform: uppercase',
                        fieldStyle: 'font-size:8px; text-transform: uppercase'}
            ,items:[{
                    layout:'column'
                    ,border:false
                    ,items:[{
                            //columnWidth:0.50, 
                            layout: 'form'
			    ,labelWidth: 120
			    ,border:false
			    ,items: [{
                                        fieldLabel:'Tipo de Documento'
                                        ,id:'bit_reptipoDoc'
                                        ,xtype: 'genCmbBox'
                                        ,minChars: 1
                                        ,allowBlank:false
                                        ,sqlId: 'BiTrTr_bitTipoDoc'
					,width:200
                                    },{
                                        id:'bit_repidAux'
                                        ,xtype: 'genCmbBox'
                                        ,sqlId: 'BiTrTr_bitProveedor'
                                        ,minChars: 1
                                        ,allowBlank:false
                                        ,fieldLabel: 'Proveedor'
                                        ,labelWidth:300
                                        ,width:200
                                    },{
                                        fieldLabel:'Emision Desde'
                                        ,xtype:'datefield'
                                        ,id:'bit_repDesde'
                                        ,renderer: Ext.util.Format.dateRenderer('d/m/Y')
                                        ,format:'d-m-Y'
                                        ,width:100
                                        ,allowBlank:true
                                    },{
                                        fieldLabel:'Entrega Desde'
                                        ,xtype:'datefield'
                                        ,id:'bit_repEntregaDesde'
                                        ,renderer: Ext.util.Format.dateRenderer('d/m/Y')
                                        ,format:'d-m-Y'
                                        ,width:100
                                        ,allowBlank:true
                                    },{
                                        fieldLabel:'Excel'
                                        ,xtype:'checkbox'
                                        ,id:'pExcel'
                                        ,width:100
                                        ,allowBlank:true
                                    },{
                                        fieldLabel:'Solo Cerrados'
                                        ,xtype:'checkbox'
                                        ,id:'pCerrado'
                                        ,width:100
                                        ,allowBlank:true
                                        ,listeners: {'select': function (record){
                                            bloqueaCerrado
                                            }}
                                    }]
                            },
                            {
                            //columnWidth:0.50, 
                            layout: 'form'
			    ,labelWidth: 120
                            ,border:false
                            ,items: [{
                                        fieldLabel: 'No. de Documento'
                                        ,id:'bit_repnumDoc'
                                        ,xtype: 'textfield'
                                        ,width:100
                                        ,maxLength:13
                                        ,allowBlank:true
                                    },{
                                        id:'bit_repcodemp'
                                        ,xtype: 'genCmbBox'
                                        ,sqlId: 'BiTrTr_bitEmpresas'
                                        ,minChars: 1
                                        ,allowBlank:false
                                        ,fieldLabel: 'Empresa'
                                        ,labelWidth:60
                                        ,width:100
                                    },{
                                        fieldLabel:'Emision Hasta'
                                        ,xtype:'datefield'
                                        ,id:'bit_repHasta'
                                        ,renderer: Ext.util.Format.dateRenderer('d/m/Y')
                                        ,format:'d-m-Y'
                                        ,width:100
                                        ,allowBlank:true
                                    },{
                                        fieldLabel:'Entrega Hasta'
                                        ,xtype:'datefield'
                                        ,id:'bit_repEntregaHasta'
                                        ,renderer: Ext.util.Format.dateRenderer('d/m/Y')
                                        ,format:'d-m-Y'
                                        ,width:100
                                        ,allowBlank:true
                                    },{
                                        id:'bit_repusuario'
                                        ,xtype: 'genCmbBox'
                                        ,sqlId: 'BiTrTr_bitusuarios'
                                        ,minChars: 1
                                        ,allowBlank:false
                                        ,fieldLabel: 'Usuario:'
                                        ,labelWidth:60
                                        ,width:100
                                    },{
                                        fieldLabel:'Solo En Transito'
                                        ,xtype:'checkbox'
                                        ,id:'pTransito'
                                        ,width:100
                                        ,allowBlank:true
                                        
                                    }]
                            }]
                    }]
                    
                });
  

  
  
  

    var panelRep = new  Ext.Panel({
        anchor: 50
        ,widht:50
	,title:'Parametros para el reporte'
	,id:'panelRep'
	,autoScroll : true
        ,items: [bitRep1]
        ,bbar: [
                    {
                        text: 'Ubicación'
                        ,id: "btnRepUbicacion"
                        ,iconCls:'iconNuevo'
                        ,handler: function(){
                            var slUrl = "BiTrTr_bitacoraUbicacion.rpt.php?";
                            
                            if (Ext.getCmp("bit_repidAux").isValid()) {slUrl += "&pidAux="+Ext.getCmp("bit_repidAux").getValue()}
                            if (Ext.getCmp("bit_repcodemp").isValid()) {slUrl += "&pcodEmp="+Ext.getCmp("bit_repcodemp").getValue()}
                            if (Ext.getCmp("bit_reptipoDoc").isValid()) {slUrl += "&pTipoDoc="+Ext.getCmp("bit_reptipoDoc").getValue()}
                            if (Ext.getCmp("bit_repusuario").isValid()) {slUrl += "&pUsuario="+Ext.getCmp("bit_repusuario").getValue()}
                            
                            if (Ext.getCmp("bit_repDesde").getValue() != "") {slUrl += "&pDesde="+Ext.util.Format.date(Ext.getCmp("bit_repDesde").getValue(),'Y-m-d');}
                            if (Ext.getCmp("bit_repHasta").getValue() != "") {slUrl += "&pHasta="+Ext.util.Format.date(Ext.getCmp("bit_repHasta").getValue(),'Y-m-d');}
                            
                            if (Ext.getCmp("bit_repEntregaDesde").getValue() != "") {slUrl += "&pEntDesde="+Ext.util.Format.date(Ext.getCmp("bit_repEntregaDesde").getValue(),'Y-m-d');}
                            if (Ext.getCmp("bit_repEntregaHasta").getValue() != "") {slUrl += "&pEntHasta="+Ext.util.Format.date(Ext.getCmp("bit_repEntregaHasta").getValue(),'Y-m-d');}
                            
                            
                            if (Ext.getCmp("bit_repnumDoc").getValue() != "") {slUrl += "&pnumDoc="+Ext.getCmp("bit_repnumDoc").getValue()}
                            
                            if (Ext.getCmp("pExcel").getValue() == true) {slUrl += "&pExcel=1"}
                            if (Ext.getCmp("pCerrado").getValue() == true) {slUrl += "&pCerrado=1"}
                            if (Ext.getCmp("pTransito").getValue() == true) {slUrl += "&pTransito=1"}
                            
                            window.open(slUrl);
                        }
                        ,tooltip: "Muestra la ubicación del documento"
                    },{
                        text: 'Ubicación (Detalle)'
                        ,id: "btnRepUbicacion"
                        ,iconCls:'iconNuevo'
                        ,handler: function(){
                            var slUrl = "BiTrTr_bitacoraUbicacion_Detalle.rpt.php?";
                            
                            if (Ext.getCmp("bit_repidAux").isValid()) {slUrl += "&pidAux="+Ext.getCmp("bit_repidAux").getValue()}
                            if (Ext.getCmp("bit_repcodemp").isValid()) {slUrl += "&pcodEmp="+Ext.getCmp("bit_repcodemp").getValue()}
                            if (Ext.getCmp("bit_reptipoDoc").isValid()) {slUrl += "&pTipoDoc="+Ext.getCmp("bit_reptipoDoc").getValue()}
                            if (Ext.getCmp("bit_repusuario").isValid()) {slUrl += "&pUsuario="+Ext.getCmp("bit_repusuario").getValue()}
                            
                            if (Ext.getCmp("bit_repDesde").getValue() != "") {slUrl += "&pDesde="+Ext.util.Format.date(Ext.getCmp("bit_repDesde").getValue(),'Y-m-d');}
                            if (Ext.getCmp("bit_repHasta").getValue() != "") {slUrl += "&pHasta="+Ext.util.Format.date(Ext.getCmp("bit_repHasta").getValue(),'Y-m-d');}
                            
                            if (Ext.getCmp("bit_repEntregaDesde").getValue() != "") {slUrl += "&pEntDesde="+Ext.util.Format.date(Ext.getCmp("bit_repEntregaDesde").getValue(),'Y-m-d');}
                            if (Ext.getCmp("bit_repEntregaHasta").getValue() != "") {slUrl += "&pEntHasta="+Ext.util.Format.date(Ext.getCmp("bit_repEntregaHasta").getValue(),'Y-m-d');}
                            
                            
                            if (Ext.getCmp("bit_repnumDoc").getValue() != "") {slUrl += "&pnumDoc="+Ext.getCmp("bit_repnumDoc").getValue()}
                            
                            if (Ext.getCmp("pExcel").getValue() == true) {slUrl += "&pExcel=1"}
                            if (Ext.getCmp("pCerrado").getValue() == true) {slUrl += "&pCerrado=1"}
                            if (Ext.getCmp("pTransito").getValue() == true) {slUrl += "&pTransito=1"}
                            
                            window.open(slUrl);
                        }
                        ,tooltip: "Muestra la ubicación del documento"
                    },
                     {
                        text: 'Historico'
                        ,id: "btnRepHistorico"
                        ,iconCls:'iconNuevo'
                        ,handler: function(){
                            var slUrl = "BiTrTr_bitacoraHistorico.rpt.php?";
                            
                            if (Ext.getCmp("bit_repidAux").isValid()) {slUrl += "&pidAux="+Ext.getCmp("bit_repidAux").getValue()}
                            if (Ext.getCmp("bit_repcodemp").isValid()) {slUrl += "&pcodEmp="+Ext.getCmp("bit_repcodemp").getValue()}
                            if (Ext.getCmp("bit_reptipoDoc").isValid()) {slUrl += "&pTipoDoc="+Ext.getCmp("bit_reptipoDoc").getValue()}
                            if (Ext.getCmp("bit_repusuario").isValid()) {slUrl += "&pUsuario="+Ext.getCmp("bit_repusuario").getValue()}
                            
                            if (Ext.getCmp("bit_repDesde").getValue() != "") {slUrl += "&pDesde="+Ext.util.Format.date(Ext.getCmp("bit_repDesde").getValue(),'Y-m-d');}
                            if (Ext.getCmp("bit_repHasta").getValue() != "") {slUrl += "&pHasta="+Ext.util.Format.date(Ext.getCmp("bit_repHasta").getValue(),'Y-m-d');}
                            if (Ext.getCmp("bit_repnumDoc").getValue() != "") {slUrl += "&pnumDoc="+Ext.getCmp("bit_repnumDoc").getValue()}
                            if (Ext.getCmp("pExcel").getValue() == true) {slUrl += "&pExcel=1"}
                            if (Ext.getCmp("pCerrado").getValue() == true) {slUrl += "&pCerrado=1"}
                            if (Ext.getCmp("pTransito").getValue() == true) {slUrl += "&pTransito=1"}
                            
                            window.open(slUrl);
                        }
                        ,tooltip: "Reporte historico del documento"
                    }
               ]
    });

    var panelRep1 = new  Ext.Panel({
	id : "panelRep1"
        ,items:[panelRep]
    });
    /**-----------------------------------
     **/
    
    // Store para la consulta que carga los datos del formulario de documentos
    var olRecCab = Ext.data.Record.create([  // Estructura del registro
                {name: 'bit_codempresa',	type:'string'},
                {name: 'bit_tipoDoc',	type:'string'},
                {name: 'bit_secDoc',	type: 'string'},
                {name: 'bit_emiDoc',	type: 'string'},
		{name: 'bit_numDoc',	type: 'string'},
		{name: 'bit_idAux', type:'int'},
                {name: 'bit_registro', type:'int'},
                {name: 'bit_fechaDoc',type: 'date', dateFormat:'Y-m-d'},
		{name: 'bit_valor', type:'float'},
                {name: 'bit_fechaReg',   type: 'date', dateFormat:'Y-m-d h:i:s'},
                {name: 'bit_usuarioActual', type:'string'}
      ]);
    rdForm = new Ext.data.JsonReader({root : 'data',  successProperty: 'success',totalProperty: 'totalRecords'}, olRecCab);

    /**GRID PARA CONSULTAR TODOS LOS DOCUMENTOS INGRESADOS
    **/
    
    var bitstoreCons = new Ext.data.JsonStore({
        id:"stDetPago",
        url: '../Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'BiTrTr_bitTodosDoc',start:0, limit:25,sort:'bit_fechaReg'
                            
                            , dir:'DESC'},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	fields:fCampos(),
	sortInfo: {field:'bit_fechaReg', direction: 'DESC'},
        pruneModifiedRecords: true
    });
    
    var cmbitdetalle = new Ext.grid.ColumnModel({  
      columns:[new Ext.grid.RowNumberer({width: 30})
              ,{  
                  header: '<span style="color:blue;">EMPRESA</span>'
                  ,dataIndex: 'bit_empresa'
                  ,width:100
              },{  
                  header: '<span style="color:blue;">TIPO</span>'
                  ,dataIndex: 'bit_tipoDoc'
                  ,width:40
              },{  
                  header: '<span style="color:blue;">SECUENCIAL</span>'
                  ,dataIndex: 'bit_secDoc'
                  ,width: 40
              },{  
                  header: '<span style="color:blue;">PUNTO EMISION</span>'
                  ,dataIndex: 'bit_emiDoc'
                  ,width: 40
              },{  
                  header: '<span style="color:blue;">DOCUMENTO</span>'
                  ,dataIndex: 'bit_numDoc'
                  ,width: 80
              },{  
                  header: '<span style="color:blue;">PROVEEDOR</span>'
                  ,dataIndex: 'AuxNombre'
                  ,width: 200
              },{  
                  header: '<span style="color:blue;">EMISION</span>'
                  ,dataIndex: 'bit_fechaDoc'
                  ,width: 80
                  ,renderer:Ext.util.Format.dateRenderer('d-m-Y')
              },{  
                  header: '<span style="color:blue;">VALOR</span>'
                  ,dataIndex: 'bit_valor'
                  ,width: 100
                  ,renderer: 'usMoney'
              },{  
                  header: '<span style="color:blue;">FECHA <br> INGRESO</span>'
                  ,dataIndex: 'FechaReg'
                  ,width: 80
                  ,renderer:Ext.util.Format.dateRenderer('d-m-Y')
              },{  
                  header: '<span style="color:blue;">HORA <br> INGRESO</span>'
                  ,dataIndex: 'HoraReg'
                  ,width: 80
                  ,renderer:Ext.util.Format.dateRenderer('h:i:s a')
              },{  
                  header: '<span style="color:blue;">USUARIO <br> ACTUAL</span>'
                  ,dataIndex: 'bit_usuarioActual'
                  ,width: 60
              },{  
                  header: '<span style="color:blue;">MOVIMIENTO</span>'
                  ,dataIndex: 'movimiento'
                  ,width: 130
              },{  
                  header: '<span style="color:blue;">OBSERVACION</span>'
                  ,dataIndex: 'bit_observacion'
                  ,width: 150
              }]
              
    });
    
    
     /*var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',  dataIndex: 'bit_tipoDoc'},
        {type: 'numeric',  dataIndex: 'bit_numDoc'},
        {type: 'numeric',  dataIndex: 'bit_idAux'},
        {type: 'string',  dataIndex: 'AuxNombre'},
        {type: 'date',  dataIndex: 'bit_fechaDoc'},
        {type: 'numeric',  dataIndex: 'bit_valor'},
        {type: 'date',  dataIndex: 'bit_fechaReg'},
        {type: 'date',  dataIndex: 'FechaReg'},
        {type: 'date',  dataIndex: 'HoraReg'},
        {type: 'string',  dataIndex: 'bit_usuarioActual'},
        {type: 'string',  dataIndex: 'movimiento'}]
        , autoreload:true
    });*/
    
    var grd_bitdetalle  =  new Ext.grid.GridPanel({
               id: 'grd_bitdetalle'
               ,lazyRender: true
               ,store: bitstoreCons
	       ,stripeRows :true
               ,collapsible: true
               ,cm: cmbitdetalle
               //,plugins: [filters]
               // Paginacion
               ,bbar:[{
                        text: 'Consultar'
                        ,id: "btnCons"
                        ,handler: fCargaGrid
                        ,tooltip: "Consulta los documentos ingresados a bitacora"
                      },{
                        text: 'Imprimir'
                        ,id: "btnPrint"
                        ,handler: function(){basic_printGrid(grd_bitdetalle);}
                        ,iconCls:'iconImprimir'
                        ,tooltip : "Imprimir Documentos"
                      }]
	       ,height:350
	       ,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
	       ,title:"Documentos Ingresados a Bitacora (pendientes de envio)"
	       ,listeners: {
                    destroy: function(c) {
                        c.getStore().destroy();
                    }
        }
    });
    
    function fCampos(){
      return [
                {name: 'bit_empresa',	type:'string'},
                {name: 'bit_tipoDoc',	type:'string'},
                {name: 'bit_secDoc',	type: 'string'},
                {name: 'bit_emiDoc',	type: 'string'},
		{name: 'bit_numDoc',	type: 'string'},
		{name: 'AuxNombre', type:'string'},
                {name: 'bit_fechaDoc',type: 'date' ,dateFormat:'Y-m-d'},
		{name: 'bit_valor', type:'float'},
                {name: 'FechaReg',   type: 'date'},
                {name: 'HoraReg',   type: 'date' , dateFormat:'h:i:s a'},
                {name: 'bit_usuarioActual', type:'string'},
                {name: 'movimiento', type:'string'},
                {name: 'bit_observacion', type:'string'},
                {name: 'bit_fechaReg',   type: 'date',dateFormat:'Y-m-d h:i:s'},
                {name: 'bit_idAux', type:'int'},
                {name: 'bit_registro', type:'int'}
                ]
    }

    /**FORMULARIO PARA EL INGRESO DE DOCUMENTOS A BITACORA
     **/
    var bitIng= ({
            xtype: 'form'
            ,id : 'bitIngreso'
            ,labelAlign: 'left'
	    ,reader:rdForm
            ,labelWidth: 120
            ,baseCls: "x-plain"
            ,ctCls: 'x-box-layout-ct normal'
            ,defaults: {labelStyle: 'font-weight:bold; font-size:8px; font-family:Arial, text-transform: uppercase',
                        fieldStyle: 'font-size:8px; text-transform: uppercase'}
            ,items:[{
                    layout:'column'
                    ,border:false
                    ,items:[{
                            //columnWidth:0.30, 
                            layout: 'form'
                            ,border:false
                            ,items: [{
                                        fieldLabel:'Tipo de Documento'
                                        ,id:'bit_tipoDoc'
                                        ,xtype: 'genCmbBox'
                                        ,minChars: 1
                                        ,sqlId: 'BiTrTr_bitTipoDoc'
					,allowBlank:false
                                        ,width:200
                                        ,listeners: {'change': function (field,newValue,oldValue){
                                              fConsDocIngresado()
                                        }}
                                        
                                    },{
                                        id:'bit_idAux'
                                        ,xtype: 'genCmbBox'
                                        ,sqlId: 'BiTrTr_bitProveedor'
                                        ,minChars: 1
                                        ,allowBlank:false
                                        ,fieldLabel: 'Proveedor'
                                        ,labelWidth:300
                                        ,width:200
                                        ,listeners: {'change': function (field,newValue,oldValue){
                                            fConsDocIngresado()
                                        }}
                                    }
                                    
                                    ]
                            },
                            {
//                            columnWidth:0.20, 
                            layout: 'form'
                            ,border:false
                            ,items: [/*{
                                        fieldLabel: 'No. de Documento'
                                        ,id:'bit_numDoc'
                                        ,xtype: 'numberfield'
                                        ,width:100
                                        ,maxLength:13
                                        ,allowBlank:false
                                        ,listeners: {'change': function (field,newValue,oldValue){
                                            fConsDocIngresado()
                                        }}
                                    }*/{
                                        fieldLabel: 'Secuencial'
                                        ,id:'bit_secDoc'
                                        ,xtype: 'textfield'
                                        ,width:50
                                        ,maxLength:3
                                        ,minLength:3
                                        ,allowBlank:false
                                        ,listeners: {'change': function (field,newValue,oldValue){
                                            fConsDocIngresado()
                                        }}
                                    },{
                                        fieldLabel: 'Punto Emision'
                                        ,id:'bit_emiDoc'
                                        ,xtype: 'textfield'
                                        ,width:50
                                        ,maxLength:3
                                        ,minLength:3
                                        ,allowBlank:false
                                        ,listeners: {'change': function (field,newValue,oldValue){
                                            fConsDocIngresado()
                                        }}
                                    },{
                                        fieldLabel: 'No. de Documento'
                                        ,id:'bit_numDoc'
                                        ,xtype: 'textfield'
                                        ,width:100
                                        /*,maxLength:10
                                        ,minLength:6*/
					,allowBlank:false
                                        ,listeners: {'change': function (field,newValue,oldValue){
                                            fConsDocIngresado()
                                        }}
                                    },{
                                        id:'bit_codempresa'
                                        ,xtype: 'genCmbBox'
                                        ,sqlId: 'BiTrTr_bitEmpresas'
                                        ,minChars: 1
                                        ,allowBlank:false
                                        ,fieldLabel: 'Empresa'
                                        ,labelWidth:60
                                        ,width:100
                                    }]
                            },
                            {
//                            columnWidth:0.15,
                            labelWidth: 60
                            ,layout: 'form'
                            ,border:false
                            ,items: [{
                                        fieldLabel: 'Valor'
                                        ,id:'bit_valor'
                                        ,xtype: 'numberfield'
                                        ,width:100
                                        ,allowNegative: false
                                        ,allowDecimals:true
                                        ,decimalPrecision:2
                                        ,allowBlank:false
                                    },{
                                        fieldLabel: '<span style="color:gray;">Registro</span>'
                                        ,id:'bit_registro'
                                        ,xtype: 'field'
                                        ,width:100
                                        ,disabled:true
                                        ,allowNegative: false
                                        ,allowDecimals:true
                                        ,decimalPrecision:2
                                        ,allowBlank:false
                                    }]
                            }
                            ,{
                            //columnWidth:0.35,
                            layout: 'form'
                            ,border:false
                            ,items:[{
                                        fieldLabel:'Fecha de Emision'
                                        ,xtype:'datefield'
                                        ,id:'bit_fechaDoc'
                                        ,renderer: Ext.util.Format.dateRenderer('d/m/Y')
                                        ,format:'d-m-Y'
                                        ,maxValue:new Date()
                                        ,value:new Date()
                                        ,width:100
                                        ,allowBlank:false
                                    }]
                            }]
                    }]
                    
                });
    
    var panelBitIng = new  Ext.Panel({
        widht: 100,
        //height:200,
        title:'Ingresar Documento a Bitacora'
	,id:'panelBitIng'
        ,items: [bitIng,grd_bitdetalle]
        ,tbar: [
                    {
                        text: 'Nuevo'
                        ,id: "btnNew"
                        ,iconCls:'iconNuevo'
                        ,handler : fNuevo
                        ,tooltip: "Limpia el formulario para ingresar una nuevo documento."
                    }
                    ,{
                        text: 'Grabar'
                        ,id: "btnIng"
                        ,handler: function (){
                          fGrabar('ADD')}
                        ,iconCls:'iconGrabar'
                        ,tooltip : "Graba las modificaiones realizadas"
                    },{
                        text: 'Modificar'
                        ,id: "btnUpd"
                        ,handler:function (){
                          fGrabar('UPD')}
                        ,iconCls:'iconGrabar'
                        ,tooltip : "Actualiza las modificaiones realizadas"
                    },{
                        text: 'Eliminar'
                        ,id: "btnDel"
                        ,handler:function (){
                          fEliminar()}
                        ,iconCls:'iconBorrar'
                        ,tooltip : "Elimina el documento de bitacora"
                    }]
    });
    
    //agregar al panel

    
    
    var ing = new Ext.FormPanel({
    labelWidth: 100,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: true,
    width: 300
    });
    
    ing.add({xtype:	'button',
	id:     'btnBitIng',
	cls:	 'boton-menu',
	tooltip: 'Ingresar Documentos a Bitacora',
	text:    'Ingresar Documentos',
        style:   slWidth ,
	handler: function(){
	    addTab1({id:"TabIng",title: 'Ingreso',url:'',items:[panelBitIng]});
            Ext.getCmp("TabIng").doLayout();
	    fNuevo();
	    }
    });    
    
    
    
    
    
    var recib = new Ext.FormPanel({
    labelWidth: 100,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: true,
    width: 300
    });
    
    recib.add({xtype:	'button',
              id:       'btnBitRecibir',
              cls:	'boton-menu',
              tooltip:  'Recibir o Rechazar Documentos',
              text:     'Recibir &nbsp; Documentos',
              style:    slWidth ,
              handler: function(){
                        var fdesde = Ext.getCmp("bit_fechaDesde").getValue().format('Y-m-d');
                        var fhasta = Ext.getCmp("bit_fechaHasta").getValue().format('Y-m-d');
                        var todusr = app.bit.bitConsultaTotal;  
                        var slUrl = "BiTrTr_bitacoraRecibir.php?init=1&fecha_desde="+fdesde+"&fecha_hasta="+fhasta+"&todusr="+todusr;
                        addTab({id:'grdbitRecibir', title:'Recibir/Rechazar', url:slUrl, tip: 'Recibir o Rechazar Documentos'});
              }
    });
    
    var envia = new Ext.FormPanel({
    labelWidth: 100,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: true,
    width: 300
    });
    
    envia.add({xtype:	'button',
              id:       'btnBitEnviar',
              cls:	'boton-menu',
              tooltip:  'Enviar Documentos',
              text:     'Enviar /Devolver Doc',
              style:    slWidth ,
              handler: function(){
                        var fdesde = Ext.getCmp("bit_fechaDesde").getValue().format('Y-m-d');
                        var fhasta = Ext.getCmp("bit_fechaHasta").getValue().format('Y-m-d');
                        var todusr = app.bit.bitConsultaTotal;  
                        var slUrl = "BiTrTr_bitacoraEnviar.php?init=1&fecha_desde="+fdesde+"&fecha_hasta="+fhasta+"&todusr="+todusr;
                        addTab({id:'grdbitEnv', title:'Enviar', url:slUrl, tip: 'Enviar Documentos'});
              }
    });
    
    
    campos.render(document.body, 'divIzq01');
    ing.render(document.body, 'divIzq01');
    recib.render(document.body, 'divIzq01');
    envia.render(document.body, 'divIzq01');
    
    var reporte1 = new Ext.FormPanel({
    labelWidth: 100,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: true,
    width: 300
    });
    
    reporte1.add({xtype:	'button',
              id:       'btnBitRep1',
              cls:	'boton-menu',
              tooltip:  'Reportes de Bitacora de Documentos',
              text:     'Reportes de Bitacora',
              style:    slWidth ,
              handler: function(){
                        addTab1({id:"TabRep1",title: 'Reportes de Bitacora',url:'',items:[panelRep1]});
                        Ext.getCmp("TabRep1").doLayout();
                        
                        
                        /*evento para los checkbox*/
                        Ext.getCmp("pCerrado").on('check', function(){
                            bloqueaCerrado("pCerrado")
                        });
                        Ext.getCmp("pTransito").on('check', function(){
                            bloqueaCerrado("pTransito")
                        });
                        
                        
                        if (app.bit.bitReporteUbicacion != 1){
                            Ext.getCmp('btnRepUbicacion').disable();
                        }
                        if(app.bit.bitReporteHistorico != 1){
                          Ext.getCmp('btnRepHistorico').disable();
                        }
              }
    });
    
    reporte1.render(document.body, 'divIzq03');
    
    //fCargaGrid();    
    //grd_bitdetalle.on('rowdblclick', function(sm, rowIdx,e ) {
    grd_bitdetalle.on('rowclick', function(sm, rowIdx,e ) {
            var pRec = grd_bitdetalle.getStore().getAt(rowIdx);
            fCargaCab(pRec.get("bit_tipoDoc"), pRec.get("bit_secDoc"),pRec.get("bit_emiDoc"),pRec.get("bit_numDoc"), pRec.get("bit_idAux"), pRec.get("bit_registro")); 
     })
    
    
    
    fCargaPermisos('INS');//Insertar un nuevo documento
    fCargaPermisos('UPD');//Modificar documento
    fCargaPermisos('DEL');//Eliminar documentos de bitacora
    fCargaPermisos('QRY');
    fCargaPermisos('ING');//Ingresar documentos a bitacora
    fCargaPermisos('ENV');//Enviar documentos
    fCargaPermisos('RCB');//Recibir documentos
    fCargaPermisos('CTO');//Consulta los documentos de todos los usuarios
    fCargaPermisos('UBI');//Reporte de Ubicacion de documentos
    fCargaPermisos('HIS');//Reporte Historico de documentos
    fCargaPermisos('CIE');//Cerrar proceso de bitacora
    fCargaPermisos('RCH');//Cerrar proceso de bitacora
    
    /*Maximo y minimo para la longitud del secuencial del documento*/
    fDatosBitacora('lenSecuencial',1);
});


/** FUNCIONES
 **/


function bloqueaCerrado(opcion){
    if (opcion == "pCerrado"){
      Ext.getCmp("pTransito").setValue(0);
    }
    else{
      Ext.getCmp("pCerrado").setValue(0);
    }
}


  




function fGrabar(accion){  
        if (!Ext.getCmp("bitIngreso").getForm().isDirty()) {
            Ext.Msg.alert('AVISO', 'Los datos no han cambiado.');
            return;
        }     
        if (!Ext.getCmp("bitIngreso").getForm().isValid()) {
            Ext.Msg.alert('ATENCION!', 'Hay Informacion incompleta o invalida');
            return; 
        }

        var olModif = Ext.getCmp("bitIngreso").getForm().items.items;//Todos los items del formulario
        oParams = {};
        olModif.collect(function(pObj, pIdx){
        switch (pObj.getXType()) {
            case "datefield":
                eval("oParams." + pObj.id + " = '" +  pObj.getValue().dateFormat("Y-m-d") + "'" ); //pObj.format
                break
            case "combo" :
                eval("oParams." + pObj.hiddenName + " = '" +  pObj.getValue() + "'" );
                break;
            default:
                if (pObj.id.substring(0,4) != "ext-"){
                    eval("oParams." + pObj.id + " = '" +  pObj.getValue() + "'" );
                }
            }
        })
        
        var slParams  = Ext.urlEncode(oParams);
        slParams += "&" +  Ext.urlEncode(gaHidden);
        oParams.bit_empresa = Ext.getCmp("bitIngreso").findById('bit_codempresa').getSelectedRecord().data.txt;
        
        if (accion == 'ADD'){
          // Usuario:
          //oParams.bit_usuarioActual = ses_usuario;
          oParams.bit_observacion = "INGRESO A BITACORA";
          slParams += "bit_usuarioActual=" + ses_usuario;
          slAction= accion;
          fGrabarCabecera(slAction, oParams, slParams);
        }
        else if(accion == 'UPD'){
           slAction=accion;
           fActualizaCabecera(slAction, oParams, slParams);
        }
};


/** Grabar documento en bitácora
 **/
function fGrabarCabecera(slAction, oParams, slParams){
  Ext.Ajax.request({
    waitMsg: 'GRABANDO...',
    url:	'BiTrTr_bitacoraIngresarDocu.php?tipo=' + slAction, 
    //url:	'../Ge_Files/GeGeGe_generic.crud.php?pAction=' + slAction, 
    method: 'POST',
    params: oParams,
    success: function(response,options){
      
      var resp = response.responseText;
      if (resp == 1){
        Ext.Msg.alert('Registro Creado');
        fCargaGrid();
        fNuevo();
      }else{
        Ext.Msg.alert('No se guardo el documento');
      }
    }, 
    failure: function(form, e) {
      if (e.failureType == 'server') {
        slMens = 'La operacion no se ejecuto. ' + e.result.errors.id + ': ' + e.result.errors.msg;
      } else {
        slMens = 'El comando no se pudo ejecutar en el servidor';
      }
      Ext.Msg.alert('Proceso Fallido', slMens);
    }
  }
  ); 
};

/** Actualizar documento en bitácora
 **/
function fActualizaCabecera(slAction, oParams, slParams){
  Ext.Ajax.request({
    waitMsg: 'ACTUALIZANDO...',
    url:	'BiTrTr_bitacoraIngresarDocu.php?tipo=' + slAction, 
    method: 'POST',
    params: oParams,
    success: function(response,options){
      var resp = response.responseText;
      if (resp == 1){
        Ext.Msg.alert('Registro Actualizado');
        fCargaGrid();
        fNuevo();
      }else{
        Ext.Msg.alert('No se actualizo del documento');
      }
    }, 
    failure: function(form, e) {
      if (e.failureType == 'server') {
        slMens = 'La operacion no se ejecuto. ' + e.result.errors.id + ': ' + e.result.errors.msg;
      } else {
        slMens = 'El comando no se pudo ejecutar en el servidor';
      }
      Ext.Msg.alert('Proceso Fallido', slMens);
    }
  }
  ); 
};

/** ELIMINAR DOCUMENTO:
  */
var respuesta = false; // para el mensaje al eliminar el documento
function fEliminar(){
  var tipoDoc = Ext.getCmp("bitIngreso").findById('bit_tipoDoc').getValue();
  var secDoc = Ext.getCmp("bitIngreso").findById('bit_secDoc').getValue();
  var emiDoc = Ext.getCmp("bitIngreso").findById('bit_emiDoc').getValue();
  var numDoc = Ext.getCmp("bitIngreso").findById('bit_numDoc').getValue();
  var Auxiliar = Ext.getCmp("bitIngreso").findById('bit_idAux').getValue();
  var Registro = Ext.getCmp("bitIngreso").findById('bit_registro').getValue();
  
  Ext.MessageBox.confirm('Eliminar', '¿Esta seguro que desea eliminar el documento: '+tipoDoc+'-'+numDoc+'-'+Auxiliar+'?',
    function eliminar(respuesta){
    if(respuesta == 'yes'){
        
	var oParams = {};
        // claves primarias del registro
        oParams.bit_tipoDoc = tipoDoc;
        oParams.bit_secDoc = secDoc;
        oParams.bit_emiDoc = emiDoc;
        oParams.bit_numDoc = numDoc;
        oParams.bit_idAux = Auxiliar;
        oParams.bit_registro = Registro;
        
	Ext.Ajax.request({
		url:	'BiTrTr_bitacoraIngresarDocu.php?tipo=DEL', 
                method: 'POST',
                params: oParams,
                success: function(response,options){
                  var resp = response.responseText;
                  if (resp == 1){
                    Ext.Msg.alert('Registro Eliminado');
                    fCargaGrid();
                    fNuevo();
                  }else{
                    Ext.Msg.alert('No se eliminó el documento');
                  }
		}
		,failure: function(pResp, pOpt){
			alert("No se aplico el proceso de eliminacion");
		    }
		});
    }
    }
);
}
/**OTRAS FUNCIONES
 **/
function fNuevo(){
      Ext.getCmp("bitIngreso").getForm().reset();
      Ext.getCmp("btnIng").show();
      Ext.getCmp("btnUpd").hide();
      Ext.getCmp("btnDel").hide();
      Ext.getCmp("bitIngreso").findById("bit_tipoDoc").setDisabled(false);
      Ext.getCmp("bitIngreso").findById("bit_secDoc").setDisabled(false);
      Ext.getCmp("bitIngreso").findById("bit_emiDoc").setDisabled(false);
      Ext.getCmp("bitIngreso").findById("bit_numDoc").setDisabled(false);
      Ext.getCmp("bitIngreso").findById("bit_idAux").setDisabled(false);
      
      // Bloquear botones dependiendo de los permisos del perfil:
      if (app.bit.bitInsertar != 1) Ext.getCmp('btnIng').disable();
      if (app.bit.bitActualizar != 1) Ext.getCmp('btnUpd').disable();
      if (app.bit.bitEliminar != 1) Ext.getCmp('btnDel').disable();
}

function fBotonesCons(){
      Ext.getCmp("btnIng").hide();
      Ext.getCmp("btnUpd").show();
      Ext.getCmp("btnDel").show();
      Ext.getCmp("bitIngreso").findById("bit_tipoDoc").setDisabled(true);
      Ext.getCmp("bitIngreso").findById("bit_secDoc").setDisabled(true);
      Ext.getCmp("bitIngreso").findById("bit_emiDoc").setDisabled(true);
      Ext.getCmp("bitIngreso").findById("bit_numDoc").setDisabled(true);
      Ext.getCmp("bitIngreso").findById("bit_idAux").setDisabled(true);
}

function fCargaGrid(){
      //Cargar datos del grid:
      storeConsGeneral =  Ext.getCmp("grd_bitdetalle").getStore();
      storeConsGeneral.baseParams.fecha_desde= Ext.getCmp("bit_fechaDesde").getValue().format('Y-m-d')
      storeConsGeneral.baseParams.fecha_hasta= Ext.getCmp("bit_fechaHasta").getValue().format('Y-m-d')
      
      storeConsGeneral.load();
}

function fCargaPermisos($key){
    var olDat = Ext.Ajax.request({
	url: 'BiTrTr_variablesglobales'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                switch ($key){
                    case 'INS':app.bit.bitInsertar = olRsp.valor;//Insertar documentos
                        //if (olRsp.valor != 1) Ext.getCmp('btnIng').disable();
                        break; 
                    case 'UPD':app.bit.bitActualizar = olRsp.valor;//Actualizar documentos
                        //if (olRsp.valor != 1) Ext.getCmp('btnUpd').disable();
                        break;
                    case 'DEL':app.bit.bitEliminar = olRsp.valor;//Eliminar documentos
                        //if (olRsp.valor != 1) Ext.getCmp('btnDel').disable();
                        break;
                    case 'QRY':app.bit.bitConsulta = olRsp.valor;//Consultas
                           break; 
                    case 'ING':app.bit.bitIngresar = olRsp.valor;//Ingresar documentos a bitacora
                          if (olRsp.valor != 1) Ext.getCmp('btnBitIng').disable();
                          break;
                    case 'ENV':app.bit.bitEnviar = olRsp.valor; //Enviar o devolver documentos
                        if (olRsp.valor != 1) Ext.getCmp('btnBitEnviar').disable();
                        break; 
                    case 'RCB':app.bit.bitRecibir = olRsp.valor;//Recibir documentos
                        if (olRsp.valor != 1) Ext.getCmp('btnBitRecibir').disable();
                        break;
                    case 'CTO':app.bit.bitConsultaTotal = olRsp.valor;//Consulta los documentos de todos los usuarios
                        /*if (olRsp.valor != 1) Ext.getCmp('btnBitRep1').disable();*/
                        break;
                    case 'UBI':app.bit.bitReporteUbicacion = olRsp.valor;//Ubicacion de documentos
                        //if (olRsp.valor != 1) Ext.getCmp('btnRepUbicacion').disable();
                        break;  
                    case 'HIS':app.bit.bitReporteHistorico = olRsp.valor;//Historico de documentos
                        break;
                    case 'CIE':app.bit.bitCierreProceso = olRsp.valor;//botón para cerrar el proceso para el documento
                        break;
		    case 'RCH':app.bit.bitDevolucionCliente = olRsp.valor;//botón para cerrar el proceso para el documento
                        break;
                }
	    }
	}
	,params: {pKey: $key, pBool: 0}
    })
}


function fDatosBitacora(opc, sec){
    var olDat = Ext.Ajax.request({
	url: '../Ge_Files/GeGeGe_queryToJson.php'
        ,params: {id: 'BiTrTr_bitDatos',p_Secuencia:sec}
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
		switch (opc){
                    case 'lenSecuencial':
			// Longitud del Secuencial NumDoc
			Ext.getCmp("bitIngreso").findById("bit_numDoc").maxLength=olRsp.data[0].par_Valor1;
			Ext.getCmp("bitIngreso").findById("bit_numDoc").minLength=olRsp.data[0].par_Valor2;
                        break;
		}
	    }
	}
    })
}


function fConsDocIngresado(){
  var tipoDoc = Ext.getCmp("bitIngreso").findById('bit_tipoDoc').getValue();
  var secDoc = Ext.getCmp("bitIngreso").findById('bit_secDoc').getValue();
  var emiDoc = Ext.getCmp("bitIngreso").findById('bit_emiDoc').getValue();
  var numDoc = Ext.getCmp("bitIngreso").findById('bit_numDoc').getValue();
  var Auxiliar = Ext.getCmp("bitIngreso").findById('bit_idAux').getValue();
  
  var slAction = "CON";
  var oParams = {};
  
  if (tipoDoc != "" && numDoc != "" && Auxiliar != ""){
    
    oParams.bit_tipoDoc = tipoDoc;
    oParams.bit_secDoc = secDoc;
    oParams.bit_emiDoc = emiDoc;
    oParams.bit_numDoc = numDoc;
    oParams.bit_idAux = Auxiliar;
    
    Ext.Ajax.request({
      waitMsg: 'GRABANDO...',
      url:	'BiTrTr_bitacoraIngresarDocu.php?tipo=' + slAction, 
      method: 'POST',
      params: oParams,
      success: function(response,options){
        var resp = response.responseText;
        if (resp != 0){
          Ext.Msg.alert('Alerta',"El documento ya fue ingresado");
          Ext.getCmp("bitIngreso").findById('bit_numDoc').setValue(" ");
        }
      }, 
      failure: function(form, e) {
        if (e.failureType == 'server') {
          slMens = 'La operacion no se ejecuto. ' + e.result.errors.id + ': ' + e.result.errors.msg;
        } else {
          slMens = 'El comando no se pudo ejecutar en el servidor';
        }
        Ext.Msg.alert('Proceso Fallido', slMens);
      }
    }
    );
  }
};

function fCargaCab(bit_tipo, bit_secuencial, bit_emision, bit_numero, bit_auxiliar, bit_registro){
  Ext.getCmp("bitIngreso").load({
    url: '../Ge_Files/GeGeGe_queryToJson.php',
    params: {id: 'BiTrTr_bitDocumento', pbit_tipoDoc: bit_tipo, pbit_secDoc:bit_secuencial, pbit_emiDoc: bit_emision,pbit_numDoc:bit_numero,pbit_idAux: bit_auxiliar, pbit_registro:bit_registro}, 
    discardUrl: false,
    nocache: false,
    text: "Cargando...",
    timeout: 1,
    scripts: false,
	  metod: 'POST'
    ,success: function(pResp, pOpt){
			var recForm = Ext.getCmp("bitIngreso").getForm().reader.jsonData.data[0];
                        fBotonesCons();
    }
   });
}

function addTab(pPar){
      tabs_c.add({
      id: pPar.id
      ,title: pPar.title
      ,layout:'fit'
      ,closable: true
      ,collapsible: true
      ,items: pPar.items
      ,autoLoad:{url:pPar.url,
		 params:{pObj:pPar.id},
		 scripts: true,
		 method: 'post'}
    }).show();
  }
function addTab1(pPar){ //Add Tab sin la configuracion del autoload
      tabs_c.add({
      id: pPar.id
      ,title: pPar.title
      ,layout:'fit'
      ,closable: false
      ,collapsible: true
      ,items: pPar.items
    }).show();
  }

// Funciones para imprimir
function printmygridGO(obj){  global_printer.printGrid(obj);	} 
function printmygridGOcustom(obj){ global_printer.printCustom(obj);	}  	
function basic_printGrid(objgrid){
		global_printer = new Ext.grid.XPrinter({
			grid: objgrid,  // grid object 
			pathPrinter:'../LibJs/ext/ux/printer',  	 // relative to where the Printer folder resides  
			//logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
			pdfEnable: true,  // enables link PDF printing (only save as) 
			hasrowAction:false, 
			localeData:{
				Title:'',	
				subTitle:'',	
				footerText:'', 
				pageNo:'Page # ',	//page label
				printText:'Print',  //print document action label 
				closeText:'Close',  //close window action label 
				pdfText:'PDF'
            },useCustom:{  // in this case you leave null values as we dont use a custom store and TPL
				custom:false,
				customStore:null, 
				columns:[], 
				alignCols:[],
				pageToolbar:null, 
				useIt: false, 
				showIt: false, 
				location: 'bbar'
			},
			showdate:true,// show print date 
			showdateFormat:'Y-F-d H:i:s', // 
			showFooter:true  // if the footer is shown on the pinting html 
			,styles:'default' // wich style youre gonna use 
		}); 
		global_printer.prepare(); // prepare the document 
}

function basic_printGrid_exclude(objgrid){
		global_printer = new Ext.grid.XPrinter({
			grid: objgrid,  // grid object 
			pathPrinter:'./printer',  	 // relative to where the Printer folder resides  
			//logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
			pdfEnable: true,  // enables link PDF printing (only save as) 
			hasrowAction:false, 
			excludefields:'4,',  // 0 based index , if it has numberer or action it counts as a column 
			localeData:{
				Title:'Array Grid Demo printing',	
				subTitle:'by XtPrinter',	
				footerText:'Report Footer goes here', 
				pageNo:'Page # ',	//page label
				printText:'Print',  //print document action label 
				closeText:'Close',  //close window action label 
				pdfText:'PDF'
            }, useCustom:{  // in this case you leave null values as we dont use a custom store and TPL
				custom:false,
				customStore:null, 
				columns:[], 
				alignCols:[],
				pageToolbar:null, 
				useIt: false, 
				showIt: false, 
				location: 'bbar'
			},
			showdate:true,// show print date 
			showdateFormat:'Y-F-d H:i:s', // 
			showFooter:true  // if the footer is shown on the pinting html 
			//,styles:'default' // wich style youre gonna use 
		}); 
		global_printer.prepare(); // prepare the document 
}
