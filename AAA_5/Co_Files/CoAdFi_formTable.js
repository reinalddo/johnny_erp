Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"
    // ***************************** VARIABLES GENERALES **************************************
    /**
     * flaPlanPag == 1 usa plan de pagos
     * flaPlanPag == 0 no usa plan de pagos
     **/
    var flaPlanPag=0; //Guarda si se usa o no el grid para la planificacion de pagos.
    var MaxNumCuotas=0; // Maximo numero de cuotas que se puede permitir dependiendo de la transaccion
    var olRecConsEsp; // para el store del grid
    var ptra_Id=0; // para guardar el id de la consulta.
   // ses_usuario guarda el usuario - esta definida en CoAdFi_formTable.php
   // ptra_Id guarda el id de la consulta - se asigna al dar doble clic en la consulta
   // ****************************************************************************************
    slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d';
    
Ext.onReady(function(){
    Ext.QuickTips.init();
    // GRID DEL PLAN DE PAGOS
    var storeConsEsp = new Ext.data.JsonStore({
        id:"stDetPago",
        url: '../Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'CoAdFi_StoreDet',start:0,ID: ptra_Id, limit:100, sort:'tra_Cuota', dir:'ASC'},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	fields:fCampos(),
	sortInfo: {field:'tra_Cuota', direction: 'ASC'},
        pruneModifiedRecords: true
    });

    var CuotaColumnMode = new Ext.grid.ColumnModel(  
              [{  
                  header: 'No Cuota'
                  ,dataIndex: 'tra_Cuota'
                  ,width: 60
              },{  
                  header: 'Valor'
                  ,dataIndex: 'tra_Valor'
                  ,width: 100
                  ,editor: new Ext.form.NumberField({
                        allowBlank: false
			,decimalPrecision:2
			,listeners: {'change': function (field,newValue,oldValue){
					fSuma(); //Sumar las cuotas del plan de pagos
					var TotalCuotas = Ext.getCmp('txtSuma').getValue();
					var TotValor = Ext.getCmp("formulario").findById("tra_Valor").getValue();
					if (TotalCuotas > TotValor) {
					    Ext.Msg.alert('ATENCION!', 'El valor de la transaccion ('+TotValor+') debe ser el valor total de las cuotas ('+TotalCuotas+')');
					}
                                    }}
		     })
              },{  
                  header: 'Fecha Vencimiento'
                  ,dataIndex: 'tra_Fecha_vence'
                  ,width: 100
		  ,renderer: Ext.util.Format.dateRenderer('d/m/Y')
		  ,editor: new Ext.form.DateField({
                        allowBlank: false
			,renderer: Ext.util.Format.dateRenderer('d/m/Y')
			,format:'d/m/Y'
                  
                     }) 
              },{  
                  header: 'Semana'
                  ,dataIndex: 'tra_Semana'
                  ,width: 100
                  ,editor: new Ext.form.NumberField({
                        allowBlank: false
                        ,minValue:1
                        ,minLength:4
                        ,maxLength:4
                     })
              }]  
          );
    
    var Grid_Cuota  =  new Ext.grid.EditorGridPanel({
               id: 'gridCuotas'
               ,lazyRender: true
               ,store: storeConsEsp
	       ,stripeRows :true
               ,collapsible: true
               ,cm: CuotaColumnMode
	       ,clicksToEdit: 2
	       ,height:190
	       ,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
	       ,title:"Plan de Pago"
	       ,bbar:[{
                        text: 'Agregar'
                        ,id: "btnAdd"
                        ,handler : fAnadirFila
			,iconCls: 'iconAplicar'
                        ,tooltip: "Nueva fila"
                    }
                    ,{
                        text: 'Eliminar'
                        ,id: "btnSup"
                        ,disabled: true
                        ,tooltip : "Eliminar Filas"
			,iconCls: 'iconEliminar'
			,handler : function(){
			    fEliminaFila()
			}
                    }
		    ,{
                        id: "txtSuma"
                        ,disabled:true
                        ,tooltip : "Total de las cuotas del plan de pagos"
			,xtype: 'numberfield'
			,allowDecimals:true
                        ,decimalPrecision:2
                        ,value:0
                    }]
               ,listeners: {
                    destroy: function(c) {
                        c.getStore().destroy();
                    }
        }
    });
    
    
    Grid_Cuota.getSelectionModel().on('rowselect', function() {
        Ext.getCmp("btnSup").setDisabled(false);
    })
    Grid_Cuota.getSelectionModel().on('rowdeselect', function() {
        Ext.getCmp("btnSup").setDisabled(true);
    })
    Grid_Cuota.on('afteredit', function(e){
	e.record.data.tra_Saldo = e.record.data.tra_Valor;
    })

           
    // Store del Grid: para las funciones de agregar filas.
    olRecConsEsp = new Ext.data.Record.create([
            {name: '_newFlag'  ,type:'int'}
            ,{name: 'tra_Cuota', type: 'int'}  
            ,{name: 'tra_Valor', type: 'int'}
	    ,{name: 'tra_Saldo', type: 'int'}
            ,{name: 'tra_Fecha_vence', type: 'date', dateFormat: 'Y-m-d h:i'}
	    ,{name: 'tra_Semana', type: 'int'}
            ]);
    
    // Store para los datos del formulario
    var olRecCab = Ext.data.Record.create([  // Estructura del registro
		{name: 'tra_Id',		type: 'int'},
		{name: 'tra_Emisor',		type: 'int'},
		{name: 'tra_Receptor',	type: 'int'},
		{name: 'tra_Concepto',	type:'string'},
		{name: 'tra_RefOperat',	type: 'int'},
		{name: 'tra_Motivo',	type: 'string'},
		{name: 'tra_Fecha',         type: 'date', dateFormat:'Y-m-d h:i'},
		{name: 'tra_Cuotas', type:'int'},
		{name: 'tra_Valor', type:'int'},
		{name: 'tra_Estado', type:'int'},
		{name: 'tra_Usuario', type:'string'},
		{name: 'tra_Semana', type:'int'},
		{name: 'par_Descripcion', type:'string'},
		{name: 'planPago', type:'string'},
		{name: 'maxCuotas', type:'string'}
      ]);
    rdForm = new Ext.data.JsonReader({root : 'data',  successProperty: 'success',totalProperty: 'totalRecords',  id:'tra_Id'}, olRecCab);

    var olForm= ({
            xtype: 'form'
            ,id : 'formulario'
            ,labelAlign: 'left'
	    ,reader:rdForm
            ,labelWidth: 70
            ,baseCls: "x-plain"
            ,ctCls: 'x-box-layout-ct normal'
            ,defaults: {labelStyle: 'font-weight:bold; font-size:8px; font-family:Arial, text-transform: uppercase',
                        fieldStyle: 'font-size:8px; text-transform: uppercase'}
            ,items:[{
                    layout:'column'
                    ,border:false
                    ,items:[{
                            columnWidth:0.6, 
                            layout: 'form'
                            ,border:false
                            ,items: [
                                    {
                                        fieldLabel:'# Trans.'
                                        ,id:'tra_Id'
                                        ,xtype: 'numberfield'
                                        ,anchor:'40%'
                                        ,disabled:true
                                    }
                                    ,{
                                        id:'tra_Receptor'
                                        ,fieldLabel: 'Receptor'
                                        ,xtype: 'genCmbBox'
                                        ,minChars: 2
                                        ,sqlId: 'CoAdFi_productor'
					,anchor:'80%'
					,allowBlank: false 
                                    }
                                    ,{
                                        id:'tra_Motivo'
                                        ,fieldLabel: 'Transaccion'
                                        ,xtype:'genCmbBox'
                                        ,minChars: 1
                                        ,sqlId: 'CoAdFi_transaccion'
                                        ,anchor:'80%'
                                        ,allowBlank:false
					,listeners: {'change': function (field,newValue,oldValue){
                                           flaPlanPag = this.getXmlField('planPago');
					   MaxNumCuotas = this.getXmlField('maxCuotas');
					   Ext.getCmp("formulario").findById("tra_Cuotas").maxValue=MaxNumCuotas;
					   if (flaPlanPag == 1) {
					    Ext.getCmp("formulario").findById("tra_Cuotas").minValue=1;
					   }else {
					    Ext.getCmp("formulario").findById("tra_Cuotas").minValue=0;
					   }
					   Ext.getCmp("TabIng").doLayout();
					   fCuotas();
                                        }}
				    }
                                    ,{
                                        id:'tra_Estado'
                                        ,fieldLabel:'Estado'
                                        ,xtype:'genCmbBox'
                                        ,minChars: 1
                                        ,sqlId: 'CoAdFi_estados'
                                        ,disabled:true
                                        ,value:1
                                    }
				    ,{
                                        id:'tra_Semana'
                                        ,fieldLabel:'Semana'
                                        ,xtype:'numberfield'
                                        ,anchor:'40%'
                                        ,allowBlank:false
                                        ,allowDecimals:false
                                        ,minLength:4
					,maxLength:4
					,listeners: {'change': function (field,newValue,oldValue){
                                            fCuotas()
                                        }}
                                    }
                                    ]
                            }
                            ,{
                            columnWidth:.40,
                            layout: 'form'
                            ,border:false
                            ,items:[{
                                        id:'tra_Fecha'
                                        ,fieldLabel:'Emision'
                                        ,xtype:'datefield'
                                        ,anchor:'60%'
                                        ,format:'y-m-d'
                                        ,maxValue:new Date()
                                        ,value:new Date()
                                        ,disabled:true
                                        
                                    }
                                    ,{
                                        id:'tra_Cuotas'
                                        ,fieldLabel:'Cuotas'
                                        ,colspan:1
                                        ,xtype:'numberfield'
                                        ,allowBlank:false
                                        ,anchor:'40%'
                                        ,minValue:0
					,value:0
                                        ,allowDecimals:false
					,maxValue:MaxNumCuotas
					,maxLength:2
                                        ,listeners: {'change': function (field,newValue,oldValue){
                                            fCuotas()
                                        }}
                                    }
                                    ,{
                                        id:'tra_Valor'
                                        ,fieldLabel:'Valor'
                                        ,colspan:1
                                        ,xtype:'numberfield'
                                        ,allowBlank:false
                                        ,anchor:'60%'
                                        ,align:'top'
					,allowDecimals:true
                                        ,decimalPrecision:2
                                        ,allowNegative:false
                                        ,listeners: {'change': function (field,newValue,oldValue){
                                            fCuotas()
                                        }}
                                    }
                                    ,{
                                        id:'tra_Concepto'
                                        ,fieldLabel: 'Concepto'
                                        ,xtype:'textarea'
					,anchor:'90%'
                                    }]
                            }]
                    }]
                    
                });

    var p = new  Ext.Panel({
        anchor: 50
	,title:'Pagos a Proveedores'
	,id:'panelTrans'
        ,items: [olForm,Grid_Cuota]
        ,tbar: [
                    {
                        text: 'Nuevo'
                        ,id: "btnNew"
                        ,iconCls:'iconNuevo'
                        ,handler : fNuevo
                        ,tooltip: "Limpia el formulario para ingresar una nueva transaccion."
                    }
                    ,{
                        text: 'Grabar'
                        ,id: "btnUpd"
                        ,handler: fGrabar
                        ,iconCls:'iconGrabar'
                        ,tooltip : "Graba las modificaiones realizadas"
                    }
                    ,{
                        text: 'Eliminar'
                        ,id: "btnDel"
                        ,handler: fEliminar
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
       
    var slWidth="width:99%; text-align:'left'";
    var ing = new Ext.FormPanel({
    labelWidth: 90,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: true,
    width: 300
    });
    
    ing.add({xtype:	'button',
	id:     'btnIngTrans',
	cls:	 'boton-menu',
	tooltip: 'Ingreso de transacciones',
	text:    'Ingresar',
	style:   slWidth ,
	handler: function(){
	    addTab1({id:"TabIng",title: 'Ingreso',url:'',items:[p]});
            Ext.getCmp("TabIng").doLayout();
	    fNuevo();
	    }
    });    
    
    ing.render(document.body, 'divIzq01');
       
    var dr = new Ext.FormPanel({
    labelWidth: 70,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: false,
    width: 300
    });
     
    
    dr.add({xtype: 'genCmbBox'
		,id: "cmbEstadosT"
		,fieldLabel:'Mostrar los'
		,minChars: 1
                ,editable: false
		,allowBlank:true
		,sqlId: 'CoAdFi_estados'
		,width:100
		,listeners: {'select': function(cmb,record,index){
                                        var pEstado = "";
					pEstado = Ext.getCmp("cmbEstadosT").getValue();
					var slUrl = "CoAdFi_PrestamoCons.php?init=1&pEstado="+pEstado;
					addTab({id:'gridConsTrans', title:'Consulta', url:slUrl, tip: 'Consulta de Transacciones'});
                            }}
    });
    dr.add({xtype:	'button',
		id:     'btnConsTrans',
		cls:	 'boton-menu',
		tooltip: 'Consulta de transacciones',
		text:    'Consultar Todos',
		style:   slWidth,
		handler: function(){
			    var pEstado = "";
			    pEstado = "tra_Estado" //para que consulte todos los estados.
			    var slUrl = "CoAdFi_PrestamoCons.php?init=1&pEstado="+pEstado;
			    addTab({id:'gridConsTrans', title:'Consulta', url:slUrl, tip: 'Consulta de Transacciones'});
	    }
    });
    
    dr.render(document.body, 'divIzq02');
        
    var pRep = new Ext.FormPanel({
    labelWidth: 90,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: false,
    width: 300
    });
     
    
    function fCuotas(){
    fLimpiarGrid();
    /**
     * flaPlanPag == 1 usa plan de pagos
     * flaPlanPag == 0 no usa plan de pagos
     **/
    if (flaPlanPag == 1) {
	var cuotas = Ext.getCmp("formulario").findById("tra_Cuotas").getValue();
	var valor = Ext.getCmp("formulario").findById("tra_Valor").getValue();
	if (cuotas>0 && valor>0)
	fAgregarGrid();
	Ext.getCmp("panelTrans").doLayout();
	Ext.getCmp("TabIng").doLayout();
    }
     
}
    function fAgregarGrid(){
        if (!Ext.getCmp("formulario").findById("tra_Cuotas").isValid()) {
                        Ext.Msg.alert('ATENCION!', 'El número de cuotas es incorrecto');
                      
            }
        else if (!Ext.getCmp("formulario").findById("tra_Valor").isValid()) {
                        Ext.Msg.alert('ATENCION!', 'El Valor es incorrecto');
                      
            }
	else if (!Ext.getCmp("formulario").findById("tra_Semana").isValid()) {
                        Ext.Msg.alert('ATENCION!', 'Ingrese una semana correcta');
                      
            }
        else{
                var olGrd = Ext.getCmp("gridCuotas");
		olGrd.stopEditing();
		var i=0;
                var p=olGrd.store.data.length;
		var n=Ext.getCmp("formulario").findById("tra_Cuotas").getValue()
		var semana=Ext.getCmp("formulario").findById("tra_Semana").getValue()
		var j = n;
		var fecha_vence;
		semana = semana + n
		var val = Ext.getCmp("formulario").findById("tra_Valor").getValue();
		var alRecs=[];
                for (i=1; i<=n; i++) {
                    // Esto inserta las filas de abajo hacia arriba, la primera fila insertada es la ultima
		    fecha_vence = new Date().add('d',7*j); // para agegar una semana;
                    var valCuota = parseFloat((val/n).toFixed(2));
                    // asignar la diferencia a la ultima cuota
                    if (i == 1){
                        var valFinal = (valCuota*n);
                        var valdif = val - valFinal;
                        if (valdif != 0 ){
                            valCuota = valCuota+valdif;
                        }
                    }
                    
		    var r=new olRecConsEsp({
		    _newFlag:true
		    ,tra_Cuota: j
                    ,tra_Valor: valCuota
		    ,tra_Saldo: valCuota
                    ,tra_Fecha_vence: fecha_vence
		    ,tra_Semana: semana
                    });
                    alRecs.push(r);
		    j--;
		    semana --;
                }
		olGrd.store.insert(p,alRecs);
		
		//Suma del grid:
		fSuma()
	    }
    }
    
    
    // Llaves primarias de la planificacion de pagos
    alPrimKeys= [];
    alPrimKeys["tra_Id"] = 1;
    alPrimKeys["tra_Cuota"] = 1;
   
});

function fLimpiarGrid(){
    var olGrd = Ext.getCmp("gridCuotas");
    olGrd.getStore().removeAll();
    TotalCuotas = Ext.getCmp('txtSuma');
    TotalCuotas.setValue(0);
}

function fNumCuota(){
    var olGrd = Ext.getCmp("gridCuotas");
    olGrd.stopEditing();
    var i;
    var j=1;
    for (i=0; i < olGrd.store.data.length; i++) {
	olGrd.getStore().data.items[i].set("tra_Cuota",j)
	j++;
    }
}


function fNuevo(){
    Ext.getCmp("formulario").getForm().reset();
    fLimpiarGrid();
    // Limpiar variables generales:
    flaPlanPag = 0;
    MaxNumCuotas = 0;
    Ext.getCmp("formulario").findById("tra_Cuotas").maxValue=MaxNumCuotas;
    Ext.getCmp("btnUpd").setDisabled(false);
    Ext.getCmp("btnDel").setDisabled(false);
}
//
function fGrabar(){
	    if (fValidar() == true){
                var olModif = Ext.getCmp("formulario").getForm().items.items;//Todos los items del formulario
                oParams = {};
                olModif.collect(function(pObj, pIdx){
                switch (pObj.getXType()) {
                    case "datefield":
                        eval("oParams." + pObj.id + " = '" +  pObj.getValue().dateFormat(pObj.format) + "'" );
                        break
                    case "combo" :
                        eval("oParams." + pObj.hiddenName + " = '" +  pObj.getValue() + "'" );
                        break;
                    default:
			
                        if (pObj.id.substring(0,4) != "ext-"){
                        
			    if (pObj.id == "tra_Concepto"){
				oParams.tra_Concepto = pObj.getValue();
//				eval("oParams." + pObj.id + " = '" +  pObj.getValue() + "'" );
			    }else{
				eval("oParams." + pObj.id + " = '" +  pObj.getValue() + "'" );
			    }
			    			
			}
                                }
                })
                
                var ilId  = Ext.getCmp("formulario").findById('tra_Id').getValue();   //usa un valor ya asignado
                if  (ilId > 0) {
                        oParams.tra_Id = ilId;
                        var slAction='UPD';
                }	else var slAction='ADD';
                
                var slParams  = Ext.urlEncode(oParams);
                slParams += "&" +  Ext.urlEncode(gaHidden);
                
                // Usuario:
                oParams.tra_Usuario = ses_usuario;
		slParams += "&tra_Usuario=" + ses_usuario;

                fGrabarRegistro(Ext.getCmp("formulario"), slAction, oParams, slParams);
                
            }
            

};


function fGrabarRegistro(fmForm, slAction, oParams, slParams){
    var slAct= "";
    var ivalor = Ext.getCmp("formulario").findById('tra_Valor').getValue();
    var imotivo = Ext.getCmp("formulario").findById('tra_Motivo').getValue();
    var itra_Id;
    
  oParams.pTabla = 'genTransac';
  Ext.Ajax.request({
    waitMsg: 'GRABANDO...',
    url:	'../Ge_Files/GeGeGe_generic.crud.php?pAction=' + slAction, //-------------    +'&' + slParams,
    method: 'POST',
    params: oParams,
    success: function(response,options){
      var responseData = Ext.util.JSON.decode(response.responseText);
      switch (slAction) {
        case "ADD":
          slMens = 'Registro Creado';
	  Ext.getCmp("formulario").findById('tra_Id').setValue(responseData.lastId);
	  slAct = "ING";
	  itra_Id = responseData.lastId;
	  break;
        case "UPD":
          slMens = 'Registro Actualizado';
	  slAct = "MOD";
	  itra_Id = Ext.getCmp("formulario").findById('tra_Id').getValue();
	  break;
      }
      
      //if (flaPlanPag == 1) { // Si usa plan de pagos hay que guardar el grid de pagos
	var olGrd = Ext.getCmp("gridCuotas");
        //if (olGrd.store.data.length > 0) {
                fGrabarDetalle(slMens)
	//    }
	//}
	//else{
	    Ext.Msg.alert('AVISO ', slMens);
	//}
	
    // Guardar registro en bitacora:
      fGrabarBitacora(slAct, 'PRE', itra_Id,imotivo, ivalor, 0);
      
	
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





function fGrabarDetalle(slMens) {
    var olGrd = Ext.getCmp("gridCuotas");
    olGrd.stopEditing();
    var olRst = olGrd.store.data.items;
    var olCm=olGrd.getColumnModel();
    var slPar = "";
    var ilCnt = 0;
    var alPar = [];
    var pItm=null;
    var m;
    var pFld;
    var oParams = {};
    var tr_Id = Ext.getCmp("formulario").findById('tra_Id').getValue();;
    
    //---------- PRIMERO SE ELIMINAN LOS REGISTROS ANTERIORES SI ES QUE EXISTEN:------------------
	oParams.pTabla="genTransacDetal";
	oParams.tra_Id=tr_Id
	Ext.Ajax.request({
			url: '../Ge_Files/GeGeGe_generic.crud.php?pAction=DEL'
			,params:oParams
			,waitMsg: 'GRABANDO...'
			,method:   "POST"
			,success: function(pResp, pOpt){
			    }
			,failure: function(pResp, pOpt){
                                Ext.Msg.alert('AVISO ', "No se elimino el plan de pagos")
				return false
			    }
			});
    // Grabar los registros:
    for (var pIdx=0; pIdx < olRst.length; pIdx++ ){
        pItm=olRst[pIdx]; 
        ilCnt++;
        var alR = [];
        for (var i=0; i< pItm.fields.keys.length; i++){
            pFld =pItm.fields.keys[i] ;
            pI = i;
            m= pItm.data[pFld];
            
            if((typeof(pItm.data[pFld]) != "undefined" ||   // it is in modified fields
                (alPrimKeys[pFld]) ||                           // it is in PK
                (typeof( pItm.data._newFlag) != "undefined" && pItm.data._newFlag == true)  )) {
                
                oParams[pFld]=olRst[pIdx].data[pFld];
            }
        }
        alPar[alPar.length] = alR;
	
	//---------- DESDE AQUI se envia a grabar cada regitro del grid:------------------
		oParams.tra_Id= tr_Id;
		oParams.pTabla="genTransacDetal";
		oParams.tra_Usuario=ses_usuario;
		Ext.Ajax.request({
			url: '../Ge_Files/GeGeGe_generic.crud.php?pAction=REP'
			,params:oParams
			,waitMsg: 'GRABANDO PLAN DE PAGOS...'
			,method:   "POST"
			,success: function(pResp, pOpt){
			    eval("olResp=" + pResp.responseText)
                           // var olResp = Ext.util.JSON.decode(pResp.responseText);
			    if (!olResp.success){
				Ext.Msg.alert('AVISO ', "No se grabaron todos los registros")
				}
			    else {
				// Ext.Msg.alert('AVISO ', slMens)
				 }
			    }
			,failure: function(pResp, pOpt){
				Ext.Msg.alert('AVISO ', "No se guardo el Plan de Pagos")
			    }
			});
		// Limpiar el parametro para guardar el nuevo registro
		slPar = "";
	//------------------------------ HASTA AQUI -------------------------------------
    }
}

function fImprimir(){
    var tra_Id = Ext.getCmp("formulario").findById("tra_Id").getValue();
    if (tra_Id > 0) {
	var url = "";
	var parametros = "";
	url = "CoAdFi_PrestamoRep.rpt.php?";
	parametros = "&tra_Id="+tra_Id;
	url += parametros;
	window.open(url);
    }
}


var respuesta = false; // para el mensaje al eliminar la transaccion
function fEliminar(){
  Ext.MessageBox.confirm('Eliminar', '¿Esta seguro que desea eliminar el registro?',
    function eliminar(respuesta){
    if(respuesta == 'yes'){
	var oParams = {};
	var ilId = Ext.getCmp("formulario").findById('tra_Id').getValue();
	var ivalor = Ext.getCmp("formulario").findById('tra_Valor').getValue();
	var imotivo = Ext.getCmp("formulario").findById('tra_Motivo').getValue();
	
	oParams.pTabla ="genTransac";
	oParams.tra_Id = ilId
	Ext.Ajax.request({
		url:'../Ge_Files/GeGeGe_generic.crud.php?pAction=DEL'
		,params:oParams
		,waitMsg: 'ELIMINANDO...'
		,method:   'POST'
		,success: function(pResp, pOpt){
		    eval("var olResp=" + pResp.responseText)
		    if (!olResp.success){
			alert ("No se elimino el registro");
			}
		    else{
			oParams.pTabla ="genTransacDetal";
			oParams.tra_Id = ilId
			Ext.Ajax.request({
				url: '../Ge_Files/GeGeGe_generic.crud.php?pAction=DEL'
				,params:oParams
				,waitMsg: 'ELIMINANDO...'
				,method:   "POST"
				,success: function(pResp, pOpt){
				    eval("var olResp=" + pResp.responseText)
				    if (!olResp.success){
					alert ("No se elimino el plan de pagos");
					}
				    }
				,failure: function(pResp, pOpt){
					alert("No se elimino el plan de pagos");
				    }
				});
			Ext.Msg.alert('Aviso', 'El registro ha sido eliminado');
			fNuevo();
			}
			// Guardar registro en bitacora:
			fGrabarBitacora("ELIM.",'PRE',ilId, imotivo,ivalor, 0);
		    }
		,failure: function(pResp, pOpt){
			alert("No se aplico el proceso de eliminacion");
		    }
		});
    }
    }
);
}

function fGrabarBitacora(slAction, pTipo, ptra_Id, pMotivo, pValor, pnumComp){
  oParams.pTabla = 'segbitacora';
  var Hoy = new Date;
  
  if (pTipo == 'PP'){
    oParams.bit_TipoObj = pTipo;
    oParams.bit_NumeroObj = pnumComp;
    oParams.bit_anotacion = slAction+' Trans.'+ptra_Id+' F:'+Hoy.format('Y-m-d');
  }
  else{
    oParams.bit_TipoObj = 'PRE';
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


//
function fSuma(){
    var TotalCuotas;
    var olGrd = Ext.getCmp("gridCuotas");
    var i;
   
    
    TotalCuotas = Ext.getCmp('txtSuma');
    TotalCuotas.setValue(0);
    
    olGrd.stopEditing();
    
    for (i=0; i<olGrd.store.data.length; i++) {
	TotalCuotas.setValue(TotalCuotas.getValue() + olGrd.store.data.items[i].data.tra_Valor);
    }
}

function fValidar(){
    if (!Ext.getCmp("formulario").getForm().isDirty()) {
	Ext.Msg.alert('AVISO', 'Los datos no han cambiado.');
	return false;
    }
    
    if (!Ext.getCmp("formulario").getForm().isValid()) {
	Ext.Msg.alert('ATENCION!', 'Hay Informacion incompleta o invalida');
	return false;  
    }
    
    // Validaciones al grid de pagos:
    if (flaPlanPag == 1) {
	var olGrd = Ext.getCmp("gridCuotas");
        if (olGrd.store.data.length < 1) {
	    Ext.Msg.alert('ATENCION!', 'El tipo de transaccion seleccionado requiere plan de pagos, es necesaria ingresar una cuota');
	    return false;
	}
	
	fSuma(); //Sumar las cuotas del plan de pagos
	var TotalCuotas = Ext.getCmp('txtSuma').getValue();
	var TotValor = Ext.getCmp("formulario").findById("tra_Valor").getValue();
	if (TotalCuotas != TotValor) {
	    Ext.Msg.alert('ATENCION!', 'El valor de la transaccion ('+TotValor+') debe ser el valor total de las cuotas ('+TotalCuotas+')');
	    return false;
	}
    }    
    return true;
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
      ,closable: true
      ,collapsible: true
      ,items: pPar.items
    }).show();
  }
  


function fCargaCab(pID){
  Ext.getCmp("formulario").load({
    url: '../Ge_Files/GeGeGe_queryToJson.php',
    params: {id: 'CoAdFi_StoreTrans', cnt_ID: pID}, 
    discardUrl: false,
    nocache: false,
    text: "Cargando...",
    timeout: 1,
    scripts: false,
	  metod: 'POST'
    ,success: function(pResp, pOpt){
			var recForm = Ext.getCmp("formulario").getForm().reader.jsonData.data[0];
			flaPlanPag = recForm.planPago; 
			MaxNumCuotas = recForm.maxCuotas;
			Ext.getCmp("formulario").findById("tra_Cuotas").maxValue=MaxNumCuotas;
			
			// Habilitar los botones:
			if (Ext.getCmp("formulario").findById("tra_Estado").getValue() == 1){
			   Ext.getCmp("btnUpd").setDisabled(false);
			   Ext.getCmp("btnDel").setDisabled(false);
			    }
			else{
			  Ext.getCmp("btnUpd").setDisabled(true);
			  Ext.getCmp("btnDel").setDisabled(true);
			   }
			   
			// Cargar Grid
                        fLimpiarGrid();
                        ptra_Id = pID;
                        storeConsEsp =  Ext.getCmp("gridCuotas").getStore();
                        storeConsEsp.baseParams.ID=ptra_Id;
			storeConsEsp.load();
    }
   });
}

function ConsultarTrans(id){    
    // mostrar el tab de la transaccion:
    addTab1({id:"TabIng",title: 'Ingreso',items:[Ext.getCmp("panelTrans")]});
    Ext.getCmp("TabIng").doLayout();
    fCargaCab(id);
}


function fCampos(){
    return [
	    {name: '_newFlag'  ,type:'int'}
            ,{name: 'tra_Cuota', type: 'int'}  
            ,{name: 'tra_Valor', type: 'int'}
	    ,{name: 'tra_Saldo', type: 'int'}
            ,{name: 'tra_Fecha_vence', type: 'date', dateFormat: 'Y-m-d h:i'}
	    ,{name: 'tra_Semana', type: 'int'}
	    ]
}
	    
function fGrid(pID){
    var storeConsEsp = new Ext.data.JsonStore({
        id:"stDetPago",
        url: '../Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'CoAdFi_StoreDet',ID:pID, /*meta: true,*/  start:0, limit:100, sort:'tra_Cuota', dir:'ASC'},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	fields:fCampos(),
	sortInfo: {field:'tra_Cuota', direction: 'ASC'},
        pruneModifiedRecords: true
    });
    
    var CuotaColumnMode = new Ext.grid.ColumnModel(  
              [{  
                  header: 'No Cuota'
                  ,dataIndex: 'tra_Cuota'
                  ,width: 60
              },{  
                  header: 'Valor'
                  ,dataIndex: 'tra_Valor'
                  ,width: 100
                  ,editor: new Ext.form.NumberField({
                        allowBlank: false
			,decimalPrecision:2
			,listeners: {'change': function (field,newValue,oldValue){
					Ext.getCmp("gridCuotas").store.data.items[0].data.tra_Saldo = newValue;
					fSuma(); //Sumar las cuotas del plan de pagos
					var TotalCuotas = Ext.getCmp('txtSuma').getValue();
					var TotValor = Ext.getCmp("formulario").findById("tra_Valor").getValue();
					if (TotalCuotas > TotValor) {
					    Ext.Msg.alert('ATENCION!', 'El valor de la transaccion ('+TotValor+') debe ser el valor total de las cuotas ('+TotalCuotas+')');
					}
                                    }}
                     })
              },{  
                  header: 'Fecha Vencimiento'
                  ,dataIndex: 'tra_Fecha_vence'
                  ,width: 100
		  ,renderer: Ext.util.Format.dateRenderer('m/d/Y')
		  ,editor: new Ext.form.DateField({
                        allowBlank: false
			,format:'Y-m-d'
                  
                     }) 
              },{  
                  header: 'Semana'
                  ,dataIndex: 'tra_Semana'
                  ,width: 100
                  ,editor: new Ext.form.NumberField({
                        allowBlank: false
                     })
              }]  
          );
    
    var Grid_Cuota  =  new Ext.grid.EditorGridPanel({
               id: 'gridCuotas'
               ,lazyRender: true
               ,store: storeConsEsp
	       ,stripeRows :true
               ,collapsible: true
               ,cm: CuotaColumnMode
	       ,clicksToEdit: 1
	       ,autoDestroy:true
	       ,height:190
	       ,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
	       ,title:"Plan de Pago"
	       ,bbar:[{
                        text: 'Agregar'
                        ,id: "btnAdd"
                        ,handler : fAnadirFila
			,iconCls: 'iconAplicar'
                        ,tooltip: "Nueva fila"
                    }
                    ,{
                        text: 'Eliminar'
                        ,id: "btnSup"
                        ,tooltip : "Eliminar Filas"
			,iconCls: 'iconEliminar'
			,handler : function(){
			    fEliminaFila()
			}
                    }
		    ,{
                        id: "txtSuma"
                        ,disabled:true
                        ,tooltip : "Total de las cuotas del plan de pagos"
			,xtype: 'numberfield'
			,allowDecimals:true
                        ,decimalPrecision:2
		     }]
    });
    return Grid_Cuota;
   
}

function fAnadirFila(){
    if (!Ext.getCmp("formulario").findById("tra_Cuotas").isValid()) {
        Ext.Msg.alert('ATENCION!', 'El número de cuotas es incorrecto');
       }
    else if (!Ext.getCmp("formulario").findById("tra_Valor").isValid()) {
        Ext.Msg.alert('ATENCION!', 'El Valor es incorrecto');
       }
else if (!Ext.getCmp("formulario").findById("tra_Semana").isValid()) {
                    Ext.Msg.alert('ATENCION!', 'Ingrese una semana correcta');
                  
    }
    else{
            var olGrd = Ext.getCmp("gridCuotas");
	olGrd.stopEditing();
	
            var p=olGrd.store.data.length;
	var semana=Ext.getCmp("formulario").findById("tra_Semana").getValue()
	var alRecs=[];
            var r=new olRecConsEsp({
	    _newFlag:true
                ,tra_Cuota: p + 1
                ,tra_Valor: 0
	    ,tra_Saldo: 0
                ,tra_Fecha_vence:new Date()
	    ,tra_Semana: semana + 1
                });
                alRecs.push(r);
            var n=Ext.getCmp("formulario").findById("tra_Cuotas").getValue()
            olGrd.store.insert(p, alRecs);
	Ext.getCmp("formulario").findById("tra_Cuotas").setValue(n+1);
	
	//Suma del grid:
	fSuma()
    }
}

function fEliminaFila(){
var olGrd = Ext.getCmp("gridCuotas");
    var p=olGrd.store.data.length;

if (p > 0){
    var record = olGrd.getSelections();
    var i;
    for (i=0; i < record.length; i++ ){
        olGrd.stopEditing();
	var idx = olGrd.store.indexOf(record[i]); 

	olGrd.store.removeAt(idx);
	var n=Ext.getCmp("formulario").findById("tra_Cuotas").getValue();
	Ext.getCmp("formulario").findById("tra_Cuotas").setValue(n-1);
    }
    fNumCuota() //Actualizar el numero de cuotas.
    fSuma() //Suma del grid.	    
    }
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
