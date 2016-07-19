Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"

var panelReemIng;
var panelRel;
Ext.namespace("app", "app.panlREE");
Ext.onReady(function(){
    Ext.QuickTips.init();
    
    
    Ext.ns("Ext.ux.renderer");
/**************************
 * RENDERER PARA COMBO
***************************/
Ext.ux.renderer.ComboRenderer = function(options) {
    var value = options.value;
    var combo = options.combo;

    var returnValue = value;
    var valueField = combo.valueField;
        
    var idx = combo.store.findBy(function(record) {
        if(record.get(valueField) == value) {
            returnValue = record.get(combo.displayField);
            return true;
        }
    });
    
    // This is our application specific and might need to be removed for your apps
    if(idx < 0 && value == 0) {
        returnValue = '';
    }
    
    return returnValue;
};

Ext.ux.renderer.Combo = function(combo) {
    return function(value, meta, record) {
        return Ext.ux.renderer.ComboRenderer({value: value, meta: meta, record: record, combo: combo});
    };
}
/*******************************
 * FINAL - RENDERER PARA COMBO
********************************/
    
    
    var btReembolso = {
        xtype:'button'
        ,id:'btReembolso'
        ,text:'Ingresar Reembolso'
        ,width:300
        ,handler: function(){
            if (panelReemIng == undefined){
                panelReemIng = mostrarReembolsoIng(); //Mostrar pantalla para relacionar auxiliar con centros de costo
            }
            addTab1({id:"TabIng",title: 'Ingreso',url:'',items:[panelReemIng]});
            Ext.getCmp("TabIng").doLayout();
            fNuevoReembolso();
            //Validaciones por permisos:
            if (app.panlREE.ReeImp == 0){
                Ext.getCmp("btnPrint").setVisible(false);
                Ext.getCmp("btnPrint").setDisabled(true);
            }
            if (app.panlREE.ReeAgrega == 0){
                Ext.getCmp("btnUpd").setVisible(false);
                Ext.getCmp("btnUpd").setDisabled(true);
            }
            if (app.panlREE.ReeElimina == 0){
                Ext.getCmp("btnDel").setVisible(false);
                Ext.getCmp("btnDel").setDisabled(true);
            }
        }
    }   
    var btCuentas = {
        xtype:'button'
        ,id:'btCuentas'
        ,text:'Asignar CC-Aux'
        ,handler: function(){
            if (app.panlREE.ReeAsigCC == 0){
                Ext.Msg.alert('No tiene permisos para realizar esta accion');
                return;
            }
            
            
            if (panelRel == undefined){
                panelRel = mostrarCCxAux(); //Mostrar pantalla para relacionar auxiliar con centros de costo
            }
            addTab1({id:"TabRel",title: 'Relacion Aux - CCosto',url:'',items:[panelRel]});
            Ext.getCmp("TabRel").doLayout();
            fNuevoCCxAux();
        }
    }
    
    var panelIng = new Ext.FormPanel({
        id:'pnlIng'
        ,width:300
        ,items:[btReembolso,btCuentas]
    });
    panelIng.render(document.body, 'divIzq01');
    
    
    var cmbReecons = {xtype: 'genCmbBox'
                        ,id: "cmbReecons"
                        ,fieldLabel:'Consultar'
                        ,minChars: 1
                        ,labelWidth:40
                        ,editable: false
                        ,allowBlank:true
                        ,sqlId: 'CoAdFi_EstRee'
                        ,width:100
                        ,listeners: {'select': function(cmb,record,index){
                                                var pEstado = "";
                                                pEstado = Ext.getCmp("cmbReecons").getValue();
                                                var slUrl = "CoAdFi_ReembolsoAprobar.php?init=1&pEstado="+pEstado;
                                                
                                                if (app.panlREE.ReeConsT == 0){
                                                    slUrl += "&tipCons=U"; //Consulta solcitud de todos
                                                }else{
                                                    slUrl += "&tipCons=T"; //Consulta solo transacciones ingresadas por el usuario
                                                }
                                                
                                                addTab({id:'TabApRe', title:'Consulta', url:slUrl, tip: 'Consulta de Transacciones'});
                                    }}
    };                       
    
    
    var btTodos = {xtype:	'button',
		id:     'btReeConsTot',
		cls:	 'boton-menu',
		tooltip: 'Consulta Todos',
		text:    'Consultar Todos',
		handler: function(){
			    var pEstado = "";
			    pEstado = "ree_Estado" //para que consulte todos los estados.
			    var slUrl = "CoAdFi_ReembolsoAprobar.php?init=1&pEstado="+pEstado;
                            if (app.panlREE.ReeConsT == 0){
                                slUrl += "&tipCons=U"; //Consulta solcitud de todos
                            }else{
                                slUrl += "&tipCons=T"; //Consulta solo transacciones ingresadas por el usuario
                            }
			    addTab({id:'TabApRe', title:'Consulta', url:slUrl, tip: 'Consulta de Transacciones'});
	    }
    };
    
    var panelCon = new Ext.FormPanel({
        id:'pnlCon'
        ,width:300
        ,items:[cmbReecons,btTodos]
    });
    panelCon.render(document.body, 'divIzq02');
   
    //Cargar permisos
    fCargaPermisos('RE1');//APROBAR
    fCargaPermisos('RE2');//CONSULTAR TODOS
    fCargaPermisos('RE3');//Asignar Centros de costos a auxiliar
    fCargaPermisos('RE4');//AGREGAR
    fCargaPermisos('RE5');//ELIMINAR
    fCargaPermisos('RE6');//IMPRIMIR
    
    if (app.panlREE.ReeAsigCC == 0){
        Ext.getCmp("btCuentas").setVisible(false);
        Ext.getCmp("btCuentas").setDisabled(true);
    }
    
   
});// FINAL DE ONREADY

/**
 * PANTALLA DE CENTROS DE COSTOS POR AUXILIARES DE PERSONAS.
*/
function mostrarReembolsoIng(){
    olRecReembolso = new Ext.data.Record.create([
            {name: 'red_Sec', type: 'int'}
            ,{name: 'red_MotivoCC', type: 'string'}
            ,{name: 'red_Aux', type: 'int'}
            ,{name: 'red_Valor', type: 'int'}
	    ,{name: 'red_Estado', type: 'int'}
            ]);
    
    function fCamposReembolso(){
    return [
	    {name: 'red_Sec', type: 'int'}
            ,{name: 'red_MotivoCC', type: 'string'}
            ,{name: 'red_Aux', type: 'int'}
            ,{name: 'red_Valor', type: 'int'}
	    ,{name: 'red_Estado', type: 'int'}
	    ]
    }
    // Grid
    var storeReembolso = new Ext.data.JsonStore({
        id:"storeReembolso",
        url: '../Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'CoAdFi_ReeDetalle',start:0,/* ID: ptra_Id,*/ limit:100, sort:'red_Sec', dir:'ASC'},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	fields:fCamposReembolso(),
	sortInfo: {field:'red_Sec', direction: 'ASC'},
        pruneModifiedRecords: true
    });
    
     //Reader para los combos
     rdComboBase = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt','txt1']
		    ) ;
     //COMBO DE MOTIVOS
     dsCbMotivo = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
           }),
            reader: 	rdComboBase,
            baseParams: {id : 'CoAdFi_CCostoxUsuario', query: 'query' || ''}
     });
     var cbMotivo = new Ext.form.ComboBox({
			fieldLabel:'MOTIVO'
			,id:'red_MotivoCC'
			,store: dsCbMotivo
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'det_CodItem'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 	'query'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,listClass: 'x-combo-list-small'
			,allowBlank:false
			,allQuery:" "
			,lazyRender: true
			,cancelOnEsc: true
			,completeOnEnter:false
			,forceSelection:true
			,listClass: 'x-combo-list-small'
			,queryDelay:2 
    });
    // COMBO DE TIPOS -AUXILIARES
    dsCbTipo = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
           }),
            reader: 	rdComboBase,
            baseParams: {id : 'CoAdFi_TipoMov', query: 'query' || ''}
     });
     var cbTipo = new Ext.form.ComboBox({
			fieldLabel:'TIPO'
			,id:'red_Aux'
			,store: dsCbTipo
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'det_CodItem'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 1
			,triggerAction: 'query'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,listClass: 'x-combo-list-small'
			,allowBlank:false
			,allQuery:""
			,lazyRender: true
			,cancelOnEsc: true
			,completeOnEnter:false
			,forceSelection:true
			,listClass: 'x-combo-list-small'
			,queryDelay:2 
    });
    
    
    
    
    
    
    var ReembolsoColumnMode = new Ext.grid.ColumnModel(  
              [{  
                  header: 'Motivo'
                  ,dataIndex: 'red_MotivoCC'
                  ,width: 300
                  ,editor: cbMotivo
		  ,renderer: Ext.ux.renderer.Combo(cbMotivo) //Ext.util.Format.comboRenderer(cbMotivo)
              },{  
                  header: 'Tipo'
                  ,dataIndex: 'red_Aux'
                  ,editor: cbTipo
                  ,renderer: Ext.ux.renderer.Combo(cbTipo) 
                  ,width: 300
              },{ 
                  header: 'Valor'
                  ,dataIndex: 'red_Valor'
                  ,editor: new Ext.form.NumberField({
                        allowBlank: false
                        ,allowDecimals:true
                        ,decimalPrecision:2
                        ,minLength:1
                        ,listeners: {'change': function (field,newValue,oldValue){
                                        fvTotal();
                                    }}
                     })
                  ,width: 100
                  
              }]  
          );
    
    var Grid_Reembolso =  new Ext.grid.EditorGridPanel({
               id: 'Grid_Reembolso'
               ,lazyRender: true
               ,store: storeReembolso
	       ,stripeRows :true
               ,collapsible: true
               ,cm: ReembolsoColumnMode
	       ,clicksToEdit: 1
	       ,height:300
	       ,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
	       ,title:"Centros de Costos Asignados a Auxiliar"
	       ,bbar:[{
                        text: 'Agregar Fila'
                        ,id: "btnAgrega"
                        ,disabled: false
                        ,tooltip : "Agregar Filas"
			,iconCls: 'iconAplicar'
			,handler : function(){
			    fagregarFila()
                            }
                        },{
                        text: 'Eliminar Fila'
                        ,id: "btnQuitar"
                        ,disabled: false
                        ,tooltip : "Eliminar Filas"
			,iconCls: 'iconEliminar'
			,handler : function(){
			    fEliminaFila()
                            }
                        }
                    ]
               ,listeners: {
                    destroy: function(c) {
                        c.getStore().destroy();
                    }
        }
    });
     
     
    // Para que lea el Store para los datos del formulario
    var olRecCabRee = Ext.data.Record.create([  // Estructura del registro
		{name: 'ree_Id',		type: 'int'},
		{name: 'ree_Emisor',		type: 'int'},
		{name: 'ree_Concepto',	type:'string'},
		{name: 'ree_RefOperat',	type: 'int'},
		{name: 'ree_Fecha',         type: 'date', dateFormat:'y-m-d'},
		{name: 'ree_Valor', type:'int'},
		{name: 'ree_Estado', type:'int'},
		{name: 'ree_Usuario', type:'string'},
		{name: 'ree_TipoComp', type:'string'},
		{name: 'ree_NumComp', type:'int'}
      ]);
    rdFormRee = new Ext.data.JsonReader({root : 'data',  successProperty: 'success',totalProperty: 'totalRecords',  id:'tra_Id'}, olRecCabRee); 
    
    var olReembIng= ({
            xtype: 'form'
            ,id : 'olReembIng'
            ,labelAlign: 'left'
	    ,reader:rdFormRee
            ,labelWidth: 70
            ,baseCls: "x-plain"
            ,ctCls: 'x-box-layout-ct normal'
            ,defaults: {labelStyle: 'font-weight:bold; font-size:8px; font-family:Arial, text-transform: uppercase',
                        fieldStyle: 'font-size:8px; text-transform: uppercase'}
            ,items:[{
                    layout:'column'
                    ,border:false
                    ,items:[{
                            columnWidth:.3, 
                            layout: 'form'
                            ,border:false
                            ,items: [{
                                        fieldLabel:'No.'
                                        ,id:'ree_Id'
                                        ,xtype: 'numberfield'
                                        ,anchor:'40%'
                                        ,disabled:true
                                    },{
                                        id:'ree_Valor'
                                        ,fieldLabel:'Valor total'
                                        ,colspan:1
                                        ,xtype:'numberfield'
                                        ,allowBlank:false
                                        ,anchor:'60%'
                                        ,align:'top'
					,allowDecimals:true
                                        ,decimalPrecision:2
                                        //,allowNegative:false
                                        ,disabled:true
                                    },{
                                        id:'ree_RefOperat'
                                        ,fieldLabel:'Semana'
                                        ,xtype:'numberfield'
                                        ,width:100//,anchor:'40%'
                                        ,value:0
                                        ,allowBlank: false
                                        ,minValue:1
                                        ,minLength:4
                                        ,maxLength:4
                                        ,listeners: {'change': function (field,newValue,oldValue){
                                                if (Ext.getCmp('ree_RefOperat').isValid()){
                                                    Ext.getCmp('ree_Concepto').setValue("REEMBOLSO DE GASTOS - SEM:"+newValue);    
                                                }
                                                else{
                                                    Ext.Msg.alert("ATENCION","INGRESE UNA SEMANA CORRECTA");
                                                }
                                                
                                            }}
                                    }
                                    ]
                            }
                            ,{
                            columnWidth:.7,
                            layout: 'form'
                            ,border:false
                            ,items: [{
                                        id:'ree_Fecha'
                                        ,fieldLabel:'Ingreso'
                                        ,xtype:'datefield'
                                        ,width:120//,anchor:'40%'
                                        ,format:'y-m-d'
                                        ,maxValue:new Date()
                                        ,value:new Date()
                                        ,disabled:false
                                        ,allowBlank: false
                                        
                                    },{
                                        id:'ree_Concepto'
                                        ,fieldLabel:'Concepto'
                                        ,xtype:'textfield'
                                        ,width:300//,anchor:'40%'
                                        ,value:"REEMBOLSO DE GASTOS"
                                        ,disabled:true
                                        
                                    },{xtype: 'genCmbBox'
                                            ,sqlId: 'CoAdFi_EstRee'
                                            ,fieldLabel:'Estado'
                                            ,id: 'ree_Estado'
                                            ,minChars: 1
                                            ,value:1
                                            ,disabled:true
                                     }
                                    ]
                            }]
                    }]
                    
                });
    var p = new  Ext.Panel({
        anchor: 50
	,title:'Solicitud de Reembolso - Usuario:'
	,id:'panelTrans'
        ,items: [olReembIng,Grid_Reembolso]
        ,tbar: [
                    {
                        text: 'Nuevo'
                        ,id: "btnNew"
                        ,iconCls:'iconNuevo'
                        ,handler : fNuevoReembolso
                        ,tooltip: "Limpia el formulario para ingresar una nueva transaccion."
                    }
                    ,{
                        text: 'Grabar'
                        ,id: "btnUpd"
                        ,handler: fgrabarReembolso
                        ,iconCls:'iconGrabar'
                        ,tooltip : "Graba las modificaiones realizadas"
                    }
                    ,{
                        text: 'Eliminar'
                        ,id: "btnDel"
                        ,handler: feliminarReembolso
                        ,iconCls:'iconBorrar'
                        ,tooltip : "Elimina la transaccion"	
                    }
		    ,{
                        text: 'Imprimir'
                        ,id: "btnPrint"
                        ,handler: fImprimir
                        ,iconCls:'iconImprimir'
                        ,tooltip : "Imprimir datos de la transaccion"
                    }]
    });  
    return p;
    
function fgrabarReembolso(){
    if (app.panlREE.ReeAgrega == 0){
        Ext.Msg.alert('No tiene permisos para realizar esta accion');
        return;
    }
    
    
    if (fValidaCabReembolso() == false){
        return
    }
    if (fValidaDetReembolso() == false){
        return
    }
    //Llamada para grabar
    oParams = {};
    oParams.ree_Concepto = Ext.getCmp("ree_Concepto").getValue();
    oParams.ree_RefOperat  = Ext.getCmp("ree_RefOperat").getValue();
    oParams.pFuncion = "INREE"; //Nuevo Reembolso
    oParams.ree_Valor = Ext.getCmp("ree_Valor").getValue() ;
    if (Ext.getCmp("ree_Id").getValue() == "") {
        oParams.ree_Id = 0; //Nuevo Reembolso       
    }else{
        oParams.ree_Id = Ext.getCmp("ree_Id").getValue(); //Actualizar Reembolso existente
    }
    oParams.ree_Fecha  = Ext.getCmp("ree_Fecha").getValue().dateFormat('Y-m-d H:i:s');
    
    //Guardar cabecera
    Ext.Ajax.request({
        waitMsg: 'GRABANDO...',
        url:	'CoAdFi_ReembolsoGrabar.php?', 
        method: 'POST',
        params: oParams,
        success: function(response,options){
            var resp = response.responseText;
            if (resp == -1){
                Ext.Msg.alert("No se guardaron los registros");
            }else{
                Ext.getCmp("ree_Id").setValue(resp) //Setear id
                //************************************************************Eliminar Detalle anterior
                    oParams = {};
                    oParams.pFuncion = "SURED";
                    oParams.red_Id = resp;
                    
                    Ext.Ajax.request({
                    waitMsg: 'GRABANDO...',
                    url:	'CoAdFi_ReembolsoGrabar.php?', 
                    method: 'POST',
                    params: oParams,
                    success: function(response,options){
                        var resp = response.responseText;
                        if (resp == -1){
                            Ext.Msg.alert("No se guardaron los registros");
                        }else{
                                //************************************************************Guardar Detalle
                                var i;
                                Ext.getCmp("Grid_Reembolso").stopEditing();
                                for (i=0; i<Ext.getCmp("Grid_Reembolso").store.data.length; i++) {
                                    oParams = {};
                                    oParams.red_Concepto = " ";
                                    oParams.red_MotivoCC = Ext.getCmp("Grid_Reembolso").store.data.items[i].data.red_MotivoCC;
                                    oParams.red_Aux = Ext.getCmp("Grid_Reembolso").store.data.items[i].data.red_Aux;
                                    oParams.red_Valor = Ext.getCmp("Grid_Reembolso").store.data.items[i].data.red_Valor;
                                    oParams.pFuncion = "INRED";
                                    oParams.red_Id = Ext.getCmp("ree_Id").getValue();
                                    
                                    Ext.Ajax.request({
                                    waitMsg: 'GRABANDO...',
                                    url:	'CoAdFi_ReembolsoGrabar.php?', 
                                    method: 'POST',
                                    params: oParams,
                                    success: function(response,options){
                                        var resp = response.responseText;
                                        if (resp == -1){
                                            Ext.Msg.alert("No se guardaron los registros");
                                        }else{
                                            Ext.Msg.alert("Datos guardados");
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
                                    });    
                                }
                                //************************************************************final Guardar Detalle
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
                    });
                    // final eliminar detalles anteriores
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
      });
    // final guardar cabecera
}

var respuesta = false; // para el mensaje al eliminar la transaccion
function feliminarReembolso(){
    if (app.panlREE.ReeElimina == 0){
        Ext.Msg.alert('No tiene permisos para realizar esta accion');
        return;
    }
    
    if (Ext.getCmp("ree_Id").getValue() == ""){
        return
    }
        
    Ext.MessageBox.confirm('Eliminar', '¿Esta seguro que desea eliminar el registro?',
    function eliminar(respuesta){
    if(respuesta == 'yes'){
            oParams = {};
            oParams.ree_Id = Ext.getCmp("ree_Id").getValue(); 
            oParams.pFuncion = "SUREE"; //Eliminar Reembolso
            
            Ext.Ajax.request({
                waitMsg: 'GRABANDO...',
                url:	'CoAdFi_ReembolsoGrabar.php?', 
                method: 'POST',
                params: oParams,
                success: function(response,options){
                    var resp = response.responseText;
                    if (resp == -1){
                        Ext.Msg.alert("No se elimino el registro");
                    }else{
                        Ext.Msg.alert("Registro eliminado");
                        fNuevoReembolso();
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
              });
        }
    });  
}


function fagregarFila(){
        var olGrd = Ext.getCmp("Grid_Reembolso");
        olGrd.stopEditing();
        var p=olGrd.store.data.length;
        //var semana=Ext.getCmp("formulario").findById("tra_Semana").getValue()
        var alRecs=[];
        var r=new olRecReembolso({
             red_Sec:0
            ,red_MotivoCC: " "
            ,red_Aux: " "
            ,red_Valor:0.00
            //,cxa_estado:new Date()
            ,red_Estado: 1
        });
        alRecs.push(r);
        olGrd.store.insert(p, alRecs);
        
}
function fvTotal(){
    var ValorT;
    var i;
        
    ValorT = 0;
    Ext.getCmp("Grid_Reembolso").stopEditing();
    
    for (i=0; i<Ext.getCmp("Grid_Reembolso").store.data.length; i++) {
	ValorT = ValorT + Ext.getCmp("Grid_Reembolso").store.data.items[i].data.red_Valor;
    }
    
    Ext.getCmp("ree_Valor").setValue(ValorT);
}

function fEliminaFila(){
    var olGrd = Ext.getCmp("Grid_Reembolso");
    var p=olGrd.store.data.length;

if (p > 0){
    var record = olGrd.getSelections();
    var i;
    for (i=0; i < record.length; i++ ){
        olGrd.stopEditing();
	var idx = olGrd.store.indexOf(record[i]); 

	olGrd.store.removeAt(idx);
    }
    fvTotal();    
    }
}
function fValidaCabReembolso(){
    if (Ext.getCmp("ree_RefOperat").isValid() == false){
        Ext.Msg.alert("ATENCION","Ingrese la semana");
        return false;
    }
    if (Ext.getCmp("ree_Fecha").isValid() == false){
        Ext.Msg.alert("ATENCION","Ingrese la fecha");
        return false;
    }
    if (Ext.getCmp("ree_Valor").getValue() == 0){
        Ext.Msg.alert("ATENCION","INGRESE EL VALOR DEL REEMBOLSO");
        return false;
    }
    return true
}

function fValidaDetReembolso(){
    
    var i;
    Ext.getCmp("Grid_Reembolso").stopEditing();
    
    for (i=0; i<Ext.getCmp("Grid_Reembolso").store.data.length; i++) {
	if (Ext.getCmp("Grid_Reembolso").store.data.items[i].data.red_MotivoCC == " "){
            Ext.Msg.alert("ATENCION","Ingrese el MOTIVO en todas las filas a grabar");
            return false;
        }
        if (Ext.getCmp("Grid_Reembolso").store.data.items[i].data.red_Aux == " "){
            Ext.Msg.alert("ATENCION","Ingrese el TIPO en todas las filas a grabar");
            return false;
        }
        
    }
    
    return true
}


function fCargaCabRee(pID){
  Ext.getCmp("olReembIng").load({
    url: '../Ge_Files/GeGeGe_queryToJson.php',
    params: {id: 'CoAdFi_StoreTrans', cnt_ID: pID}, 
    discardUrl: false,
    nocache: false,
    text: "Cargando...",
    timeout: 1,
    scripts: false,
	  metod: 'POST'
    ,success: function(pResp, pOpt){
			var recForm = Ext.getCmp("olReembIng").getForm().reader.jsonData.data[0];
			
			// Habilitar los botones:
			if (Ext.getCmp("olReembIng").findById("ree_Estado").getValue() == 1){
			   Ext.getCmp("btnUpd").setDisabled(false);
			   Ext.getCmp("btnDel").setDisabled(false);
			    }
			else{
			  Ext.getCmp("btnUpd").setDisabled(true);
			  Ext.getCmp("btnDel").setDisabled(true);
			   }
			   
			// Cargar Grid
                        pree_Id = pID;
                        storeConsEsp =  Ext.getCmp("Grid_Reembolso").getStore();
                        storeConsEsp.removeAll();
                        storeConsEsp.baseParams.ID=pree_Id;
			storeConsEsp.load();
    }
   });
}

} //final de mostrar reembolso

function mostrarCCxAux(){
   function fCampos(){
    return [
	    ,{name: 'cxa_CodCuenta', type: 'string'}
            ,{name: 'cCuenta', type: 'string'}
            ,{name: 'cxa_codPersona', type: 'int'}
	    ,{name: 'cxa_estado', type: 'int'}
	    ]
    }
    // Grid
    var storeConsEsp = new Ext.data.JsonStore({
        id:"stDetCCAux",
        url: '../Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'CoAdFi_CCxAux',start:0,/* ID: ptra_Id,*/ limit:100, sort:'cxa_CodCuenta', dir:'ASC'},
        //extraParams:{cxa_codPersona},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	fields:fCampos(),
	sortInfo: {field:'cxa_CodCuenta', direction: 'ASC'},
        pruneModifiedRecords: true
    });
    
    var CCostoColumnMode = new Ext.grid.ColumnModel(  
              [{  
                  header: 'Centro de Costo'
                  ,dataIndex: 'cxa_CodCuenta'
                  ,width: 100
              },{  
                  header: 'Centro de Costo'
                  ,dataIndex: 'cCuenta'
                  ,width: 500
              }]  
          );
    
     var Grid_CCosto =  new Ext.grid.EditorGridPanel({
               id: 'gridCCosto'
               ,lazyRender: true
               ,store: storeConsEsp
	       ,stripeRows :true
               ,collapsible: true
               ,cm: CCostoColumnMode
	       ,clicksToEdit: 2
	       ,height:300
	       ,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
	       ,title:"Centros de Costos Asignados a Auxiliar"
	       ,bbar:[{
                        text: 'Eliminar'
                        ,id: "btnSup"
                        ,disabled: false
                        ,tooltip : "Eliminar Filas"
			,iconCls: 'iconEliminar'
			,handler : function(){
			    fEliminarCC()
                            }
                        }
                        ,{
                         text: 'Actualizar'
                        ,id: "btnAct"
                        ,disabled: false
                        ,tooltip : "Actualizar Registros"
			,handler : function(){
			    var cod = Ext.getCmp("cxa_codPersona").getSelectedRecord().data.cod
                            var txt = Ext.getCmp("cxa_codPersona").getSelectedRecord().data.txt
                            fConsultarGrid(cod,txt);
                            }
                        }
                    ]
               ,listeners: {
                    destroy: function(c) {
                        c.getStore().destroy();
                    }
        }
    });
    
     var olForm= ({
            xtype: 'form'
            ,id : 'formulario'
            ,labelAlign: 'left'
	    //,reader:rdForm
            ,labelWidth: 70
            ,baseCls: "x-plain"
            ,ctCls: 'x-box-layout-ct normal'
            ,defaults: {labelStyle: 'font-weight:bold; font-size:8px; font-family:Arial, text-transform: uppercase',
                        fieldStyle: 'font-size:8px; text-transform: uppercase'}
            ,tbar:[     {xtype: 'button'
                        ,id: 'btnGrabar'
                        ,iconCls: 'iconAplicar'
                        ,text: "Agregar"
                        ,handler: function(){
                            fAgregarCC()
                        }
                 }]
            ,items:[{
                    layout:'column'
                    ,border:false
                    ,items:[{
                            columnWidth:1, 
                            layout: 'form'
                            ,border:false
                            ,items: [
                                    {xtype: 'genCmbBox'
                                            ,sqlId: 'CoAdFi_Auxiliar'
                                            ,fieldLabel:'Auxiliar'
                                            ,id: 'cxa_codPersona'
                                            ,hiddenName:'idAuxiliar'
                                            ,minChars: 1
                                            ,allowBlank: false
                                            /*,listeners: {'change': function (field,newValue,oldValue){
                                                                Ext.Msg.alert(newValue);
                                                          }}*/
                                            ,listeners: {'select': function (field,e,Value){
                                                                //Ext.Msg.alert(e.data.cod);
                                                                fConsultarGrid(e.data.cod, e.data.txt);
                                                          }}
                                     }
                                    ,{xtype: 'genCmbBox'
                                            ,sqlId: 'CoAdFi_CCosto'
                                            ,fieldLabel:'Centro de Costo'
                                            ,id: 'cxa_codCuenta'
                                            ,hiddenName:'idCCosto'
                                            ,minChars: 1
                                            ,width:400
                                            ,listWidth:500
                                            ,allowBlank: false 
                                     },
                                     Grid_CCosto
                                    ]
                            }
                            /*,{
                            columnWidth:.20,
                            layout: 'form'
                            ,border:false
                            //,items:[]
                            }*/]
                    }]
                    
                });
    
    
    var pAuxCC = new  Ext.Panel({
        anchor: 50
	,title:'Relacionar Centros de Costos con Auxiliares'
	,id:'panelAuxCC'
        ,items: [olForm]
    });  
    return pAuxCC;
    
    
    //return olForm;
    
    /* var winCCAux = new Ext.Window({
            title:'Centros de Costos por Auxiliar',
            layout:'fit',
            width:700,
            height:450,
	    id: "frmCCAux",
            style: 'font-size:8px',
            border:true,
            items:[olForm]
        });
    winCCAux.show();
    */
/**
 *  FUNCIONES
 */
function fConsultarGrid(codAux, txtAux){
    Ext.getCmp("gridCCosto").getStore().removeAll();
    Ext.getCmp("gridCCosto").getStore().load({params: {cxa_codPersona: codAux} });
    Ext.getCmp("gridCCosto").setTitle("Centros de Costos Asignados a Auxiliar "+txtAux);
}
function fAgregarCC(){
        //Validar que se haya seleccionado auxiliar y cuenta contable
        if (!Ext.getCmp("cxa_codPersona").isValid()) {
            Ext.Msg.alert('ATENCION', 'Seleccione un auxiliar');
            return 
        }
        
        if (!Ext.getCmp("cxa_codCuenta").isValid()) {
            Ext.Msg.alert('ATENCION', 'Seleccione un centro de costo');
            return 
        }
        //Llamada para grabar
        oParams = {};
        oParams.cxa_codPersona = Ext.getCmp("cxa_codPersona").getValue();
        oParams.cxa_codCuenta  = Ext.getCmp("cxa_codCuenta").getValue();
        oParams.pFuncion = "ING"; //Grabar relacion CCxAux
        
        Ext.Ajax.request({
            waitMsg: 'GRABANDO...',
            url:	'CoAdFi_ReembolsoGrabar.php?', 
            method: 'POST',
            params: oParams,
            success: function(response,options){
                var resp = response.responseText;
                Ext.Msg.alert(resp);
                
                var cod = oParams.cxa_codPersona
                var txt = Ext.getCmp("cxa_codPersona").getSelectedRecord().data.txt
                fConsultarGrid(cod,txt);
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
    
var respuesta = false; // para el mensaje al eliminar la transaccion
function fEliminarCC(){
    var olGrd = Ext.getCmp("gridCCosto");
    var record = olGrd.getSelections();
    if (record.length < 1) {
        Ext.Msg.alert('ATENCION', 'No se ha seleccionado registro a eliminar');
        return 
    }
            
    Ext.MessageBox.confirm('Eliminar', '¿Esta seguro que desea eliminar el registro?',
    function eliminar(respuesta){
    if(respuesta == 'yes'){
            var i;
            for (i=0; i < record.length; i++ ){
                //Llamada para grabar
                oParams = {};
                oParams.cxa_codPersona = record[i].data.cxa_codPersona;
                oParams.cxa_codCuenta  = record[i].data.cxa_CodCuenta;
                oParams.pFuncion = "SUP"; //Eliminar relacion CCxAux
                
                Ext.Ajax.request({
                    waitMsg: 'GRABANDO...',
                    url:	'CoAdFi_ReembolsoGrabar.php?', 
                    method: 'POST',
                    params: oParams,
                    success: function(response,options){
                        var resp = response.responseText;
                        Ext.Msg.alert(resp);
                        
                        var cod = oParams.cxa_codPersona
                        var txt = Ext.getCmp("cxa_codPersona").getSelectedRecord().data.txt
                        fConsultarGrid(cod,txt);
                    }, 
                    failure: function(form, e) {
                      if (e.failureType == 'server') {
                        slMens = 'La operacion no se ejecuto. ' + e.result.errors.id + ': ' + e.result.errors.msg;
                      } else {
                        slMens = 'El comando no se pudo ejecutar en el servidor';
                      }
                      Ext.Msg.alert('Proceso Fallido', slMens);
                    }
                  });
            }
        }
    });
}    
    
}

function fNuevoCCxAux(){
    Ext.getCmp("formulario").getForm().reset();
    
    var olGrd = Ext.getCmp("gridCCosto");
    olGrd.getStore().removeAll();
    
}
function fNuevoReembolso(){
    Ext.getCmp("olReembIng").getForm().reset();
    Ext.getCmp("btnUpd").setDisabled(false);
    Ext.getCmp("btnDel").setDisabled(false);
    
    var olGrd = Ext.getCmp("Grid_Reembolso");
    olGrd.getStore().removeAll();
}

function fGrabarBitacora(slAction, pTipo, ptra_Id, pMotivo, pValor, pnumComp){
  oParams.pTabla = 'segbitacora';
  var Hoy = new Date;
  
  if (pTipo == 'RG'){
    oParams.bit_TipoObj = pTipo;
    oParams.bit_NumeroObj = pnumComp;
    oParams.bit_anotacion = slAction+' Trans.'+ptra_Id+' F:'+Hoy.format('Y-m-d');
  }
  else{
    oParams.bit_TipoObj = 'REE';
    oParams.bit_NumeroObj = ptra_Id;
    oParams.bit_anotacion = slAction+' Trans. '+ptra_Id+' Tipo Trans. '+pMotivo;
  }
  oParams.bit_FechaHora = Hoy;
  oParams.bit_Valor1 = pValor;
  oParams.bit_CantRegis = 0;
  oParams.bit_Valor2 = 0;
  oParams.bit_autoriza = "";
  oParams.bit_Estado = 0;
  oParams.bit_modCodigo = 0;
  oParams.bit_IDusuario = ses_usuario;
  
  Ext.Ajax.request({
    waitMsg: 'GRABANDO BITACORA...',
    url: '../Ge_Files/GeGeGe_generic.crud.php?pAction=ADD',
    method: 'POST',
    params: oParams,
    success: function(response,options){
     
    }, 
    failure: function(form, e){
      if (e.failureType == 'server') {
        slMens = 'La operacion no se ejecuto. ' + e.result.errors.id + ': ' + e.result.errors.msg;
      } else {
        slMens = 'El comando no se pudo ejecutar en el servidor';
      }
      Ext.Msg.alert('Proceso Fallido', slMens);
    }
  }); 
};


function fCargaCabRee(pID){
  Ext.getCmp("olReembIng").load({
    url: '../Ge_Files/GeGeGe_queryToJson.php',
    params: {id: 'CoAdFi_ReeCabecera', pree_Id: pID}, 
    discardUrl: false,
    nocache: false,
    text: "Cargando...",
    timeout: 1,
    scripts: false,
	  metod: 'POST'
    ,success: function(pResp, pOpt){
			var recForm = Ext.getCmp("olReembIng").getForm().reader.jsonData.data[0];
			
			// Habilitar los botones:
			if (Ext.getCmp("olReembIng").findById("ree_Estado").getValue() == 1){
			   Ext.getCmp("btnUpd").setDisabled(false);
			   Ext.getCmp("btnDel").setDisabled(false);
			    }
			else{
			  Ext.getCmp("btnUpd").setDisabled(true);
			  Ext.getCmp("btnDel").setDisabled(true);
			   }
			// Cargar Combo de Motivos:
                        //Ext.getCmp("red_MotivoCC").getStore().load();
                        
			// Cargar Grid
                        pree_Id = pID;
                        storeConsEsp =  Ext.getCmp("Grid_Reembolso").getStore();
                        storeConsEsp.baseParams.pree_Id=pree_Id;
			storeConsEsp.load();
                        storeConsEsp.load();
    }
   });
}


function ConsultarTrans(id){
    if (panelReemIng == undefined){
        panelReemIng = mostrarReembolsoIng(); //Mostrar pantalla para relacionar auxiliar con centros de costo
    }
    
    addTab1({id:"TabIng",title: 'Ingreso',url:'',items:[panelReemIng]});
    Ext.getCmp("TabIng").doLayout();
    fNuevoReembolso();
    
    // mostrar el tab de la transaccion:
    fCargaCabRee(id);
}

function fImprimir(){
    if (app.panlREE.ReeImp == 0){
        Ext.Msg.alert('No tiene permisos para realizar esta accion');
        return;
    }
    
    var ree_Id = Ext.getCmp("ree_Id").getValue();
    if (ree_Id > 0) {
	var url = "";
	var parametros = "";
	url = "CoAdFi_ReembolsoRep.rpt.php?";
	parametros = "&ree_Id="+ree_Id;
	url += parametros;
	window.open(url);
    }
}


function fCargaPermisos($key){
    var olDat = Ext.Ajax.request({
	url: '../Bi_Files/BiTrTr_variablesglobales?&pmodulo=CoAdFi'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                switch ($key){
                    case 'RE1':app.panlREE.ReeAprobar = olRsp.valor;//Aprobar Reembolso
                        break; 
                    case 'RE2':app.panlREE.ReeConsT = olRsp.valor;//Consultar todas las solicitudes
                        break;
                    case 'RE3':app.panlREE.ReeAsigCC = olRsp.valor;//Asignar Centros de costos a auxiliar
                        break;
                    case 'RE4':app.panlREE.ReeAgrega = olRsp.valor;//Agregar
                           break;
                    case 'RE5':app.panlREE.ReeElimina = olRsp.valor;//Eliminar
                           break;
                    case 'RE6':app.panlREE.ReeImp = olRsp.valor;//Imprimir
                           break;
                }
	    }
	}
	,params: {pKey: $key, pBool: 0}
    })
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
