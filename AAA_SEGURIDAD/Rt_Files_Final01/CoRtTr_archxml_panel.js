/**
 *	esl	18/04/2011	Panel para el ingreso de informacion para los anexos
 *	DAUS Y ANULADOS	
 */
Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"
Ext.namespace("app", "app.rli");
Ext.onReady(function(){
    Ext.QuickTips.init();
    var slWidth="width:99%; text-align:'left'";
    Parametros();
    /*Agregar DAUS*/
    mostrarDaus();
    addTab1({id:"TabDaus",title: 'DAUS',url:'',items:[panelDaus]});
    Ext.getCmp("TabDaus").doLayout();
    /*Agregar Anulados*/
    mostrarAnulados();
    addTab1({id:"TabAnulados",title: 'ANULADOS',url:'',items:[panelAnulados]});
    Ext.getCmp("TabAnulados").doLayout();
}); //Final de funcion onReady

/**  PARAMETROS **/
function Parametros(){
    anio = { fieldLabel:'ANIO'
	    ,id:'anio'
	    ,xtype:'numberfield'
	    ,width:100
	    ,allowNegative: false
	    ,allowDecimals:false
	    ,maxLength:4
	    ,minLength:4
	    ,decimalPrecision:0
	    ,allowBlank:false	    
    };   
    mes = {id:'mes'
	    ,xtype: 'genCmbBox'
	    ,sqlId: 'CoRtTr_AnxMeses'
	    ,minChars: 1
	    ,allowBlank:false
	    ,fieldLabel: 'MES'
	    ,width:100
	    ,listWidth: 100
	    }
    panelParametro = new Ext.FormPanel({
	items:[anio, mes]
    });
    panelParametro.render(document.body, 'divIzq01');
}
/**
 * 	FUNCIONES DAUS
 **/
function mostrarDaus(){
    
    // Store del Grid: para las funciones de agregar filas.
    olRecDaus = new Ext.data.Record.create([
		{name: 'dau_id',	type:'int'},
		{name: 'dau_aniocarga',	type:'string'},
                {name: 'dau_mescarga',	type:'string'},
                {name: 'dau_ordenemb',type:'string'},
		{name: 'dau_zarpe',   type: 'date' , dateFormat:'Y-m-d'},
		{name: 'dau_fact', type: 'string'},
                {name: 'dau_valortotalfob', type:'float'},
                {name: 'dau_numerodau', type:'string'},
                /*{name: 'dau_numreferendo',   type: 'string'},*/
		{name: 'dau_distAduanero',   type: 'string'},
		{name: 'dau_anio',   type: 'string'},
		{name: 'dau_regimen',   type: 'string'},
		{name: 'dau_correlativo',   type: 'string'},
		{name: 'dau_verificador',   type: 'string'},
		{name: 'dau_autorizacion',   type: 'string'},
		{name: 'dau_fechaingreso', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'dau_usuario',   type: 'string'},
		{name: 'dau_estado',   type: 'int'},
		{name: 'dau_fechaModifica',   type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'dau_usuarioModifica',   type: 'string'}
		]);
    
    
    var store_daus = new Ext.data.JsonStore({
        id:"store_daus",
        url: '../../AAA_5/Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'CoTrTr_anxDaus',start:0, limit:25,sort:'dau_id' /*,dir:'DESC'*/},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	fields: olRecDaus,
	sortInfo: {field:'dau_id'/*, direction: 'DESC'*/},
        pruneModifiedRecords: true
    });
    
    var cm_daus = new Ext.grid.ColumnModel({  
      columns:[new Ext.grid.RowNumberer({width: 30})
              ,{  
                  header: 'ANIO'
                  ,dataIndex: 'dau_aniocarga'
                  ,width: 40
              },{  
                  header: 'MES'
                  ,dataIndex: 'dau_mescarga'
                  ,width: 40
              },{  
                  header: 'DOC TRANSP'
                  ,dataIndex: 'dau_ordenemb'
                  ,width: 100
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			,minLength:13
			,maxLength:13
		     })
              },{  
                  header: 'FECHA <br> ZARPE'
                  ,dataIndex: 'dau_zarpe'
                  ,width: 70
                  ,renderer:Ext.util.Format.dateRenderer('d-m-Y')
		  ,editor: new Ext.form.DateField({
                         allowBlank: false
		     })
              },{  
                  header: 'FACTURA'
                  ,dataIndex: 'dau_fact'
                  ,width: 80
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,minLength:1
			 ,maxLength:9
		     })
              },{  
                  header: 'TOTAL FOB'
                  ,dataIndex: 'dau_valortotalfob'
                  ,width: 100
		  ,renderer:'usMoney'
		  ,editor: new Ext.form.NumberField({
                         allowBlank: false
			,decimalPrecision:2
		     })
              },{  
                  header: 'No DAUS (fue)'
                  ,dataIndex: 'dau_numerodau'
                  ,width: 100
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,minLength:13
			 ,maxLength:13
		     })
              }/*,{  
                  header: 'No REFERENDO'
                  ,dataIndex: 'dau_numreferendo'
                  ,width: 150
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
		     })
              }*/,{  
                  header: 'REFERENDO <br> DIST ADUANERO'
                  ,dataIndex: 'dau_distAduanero'
                  ,width: 100
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,minLength:3
			 ,maxLength:3
		     })
              },{  
                  header: 'REFERENDO <br> ANIO'
                  ,dataIndex: 'dau_anio'
                  ,width: 100
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,minLength:4
			 ,maxLength:4
		     })
              },{  
                  header: 'REFERENDO <br> REGIMEN'
                  ,dataIndex: 'dau_regimen'
                  ,width: 100
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,minLength:2
			 ,maxLength:2
		     })
              },{  
                  header: 'REFERENDO <br> CORRELATIVO'
                  ,dataIndex: 'dau_correlativo'
                  ,width: 100
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,minLength:6
			 ,maxLength:6
		     })
              },{  
                  header: 'REFERENDO <br> VERIFICADOR'
                  ,dataIndex: 'dau_verificador'
                  ,width: 100
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,minLength:1
			 ,maxLength:1
		     })
              },{  
                  header: 'AUTORIZACION'
                  ,dataIndex: 'dau_autorizacion'
                  ,width: 120
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,minLength:3
			 ,maxLength:10
		     })
              }]
              
    });
    
    var grd_daus  =  new Ext.grid.EditorGridPanel({
               id: 'grd_daus'
               ,lazyRender: true
               ,store: store_daus
	       ,stripeRows :true
	       ,clicksToEdit: 1
               ,collapsible: true
               ,cm: cm_daus
               ,bbar:[{
                        text: 'Agregar'
                        ,id: "btnAddDaus"
                        ,handler: fAgregaGridDaus
                        ,tooltip : "Agregar Daus"
                      },{
                        text: 'Eliminar'
                        ,id: "btnDelDaus"
                        ,handler: fEliminarGridDaus
                        ,tooltip : "Eliminar Daus"
                      },{
                        text: 'Guardar'
                        ,id: "btnSaveDaus"
                        ,handler: function(){fGuardarGrid("grd_daus",1);} /*TIPO=1 - guardar registro DAUS*/
                        ,tooltip : "Guarda los cambios realizados"
                      },{
                        text: 'Consultar'
                        ,id: "btnConsDaus"
                        ,handler: function(){fCargaGrid("grd_daus");} 
                        ,tooltip: "Consultar los registros para daus en el año y mes especificado"
                      },{
                        text: 'Imprimir'
                        ,id: "btnPrintDaus"
                        ,handler: function(){basic_printGrid(grd_daus);}
                        //,iconCls:'iconImprimir'
                        ,tooltip : "Imprimir Daus"
                      }]
	       ,height:500
	       ,selModel: new Ext.grid.RowSelectionModel({singleSelect:false})
	       ,title:"DAUS"
	       ,listeners: {
                    destroy: function(c) {
                        c.getStore().destroy();
                    }
        }
    });
    
    panelDaus = new  Ext.Panel({
        title:''
	,id:'panelDaus'
        ,items: [grd_daus]

    });
}
function fAgregaGridDaus(){
	if (fValidarParametros() == true){
		var olGrd = Ext.getCmp("grd_daus");
		olGrd.stopEditing();
		var p=olGrd.store.data.length;
		var alRecs=[];
                var r=new olRecDaus({
			     dau_id:0 /*Se la ingresa con 0 xq eso se genera automaticamete cuando se inserte el registro, ademas asi se controla si el registro es nuevo o no -para alguna eliminacion o actualizacion*/
			    ,dau_aniocarga: Ext.getCmp("anio").getValue()
			    ,dau_mescarga: Ext.getCmp("mes").getValue()
			    ,dau_ordenemb:""
			    ,dau_zarpe: new Date()
			    ,dau_fact:""
			    ,dau_valortotalfob:0
			    ,dau_numerodau:""
			    /* ,dau_numreferendo:""*/
			    ,dau_distAduanero:""
			    ,dau_anio:""
			    ,dau_regimen:""
			    ,dau_correlativo:""
			    ,dau_verificador:""
			    ,dau_autorizacion:""
			    ,dau_fechaingreso: new Date()
			    ,dau_usuario:""
			    ,dau_estado:1
			    ,dau_fechaModifica:"0000-00-00"
			    ,dau_usuarioModifica:""
			    });
		alRecs.push(r);
		olGrd.store.insert(p,alRecs);
	}
}

function fEliminarGridDaus(){
    var olGrd = Ext.getCmp("grd_daus");
    var p=olGrd.store.data.length;
    if (p > 0){
	var trec = olGrd.getSelections().length;
	if (trec > 0){
		var respuesta = false; // para el mensaje al eliminar la transaccion
		Ext.MessageBox.confirm('Eliminar', 'Esta seguro que desea continuar? esta accion eliminara permanentemente los registros seleccionados',
		    function grabar(respuesta){
		    if(respuesta == 'yes'){
			    record = olGrd.getSelections();
			    var i;
			    for (i=0; i < record.length; i++ ){
				olGrd.stopEditing();
				if (record[i].data.dau_id == 0){ //El registro es nuevo
				    var idx = olGrd.store.indexOf(record[i]); 
				    olGrd.store.removeAt(idx);
				}else{ //El registro existe en la base
					    var oParams = {};
					    oParams['dau_aniocarga'] = record[i].data.dau_aniocarga;
					    oParams['dau_mescarga'] = record[i].data.dau_mescarga;
					    oParams['dau_id'] = record[i].data.dau_id;
				            fProcesar(oParams,2);
					    var idx = olGrd.store.indexOf(record[i]); 
					    olGrd.store.removeAt(idx);
				    }
			    }
		}});
	}
    }
}

/**
 * 	FUNCIONES ANULADOS
 **/
function mostrarAnulados(){
    olRecAnulados = new Ext.data.Record.create([
		{name: 'id',	type:'int'},
		{name: 'anio',	type:'int'},
                {name: 'mes',	type: 'int'},
		{name: 'tipoComprobante',	type:'string'},
		{name: 'establecimiento',   type: 'string'},
		{name: 'puntoEmision',   type: 'string'},
		{name: 'secuencialInicio',   type: 'string'},
		{name: 'secuencialFin',   type: 'string'},
		{name: 'autorizacion',   type: 'string'},
		{name: 'fechaIngreso', type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'usuario',   type: 'string'},
		{name: 'estado',   type: 'string'},
		{name: 'fechaModifica',   type: 'date', dateFormat:'Y-m-d H:i:s'},
		{name: 'usuarioModifica',   type: 'string'}
		]);
    
    var store_anulados = new Ext.data.JsonStore({
        id:"store_anulados",
        url: '../../AAA_5/Ge_Files/GeGeGe_queryToJson.php',
        baseParams: {id: 'CoTrTr_anxAnulados',start:0, limit:25,sort:'id' /*,dir:'DESC'*/},
        root : 'data',
	successProperty: 'success',
	totalProperty:'totalRecords',
	fields: olRecAnulados,
	sortInfo: {field:'id'/*, direction: 'DESC'*/},
        pruneModifiedRecords: true
    });
    
    rdComboBaseGeneral1 = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;
    
    dstipoCmp = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
           }),
            reader: 	rdComboBaseGeneral1,
            baseParams: {id : 'CoRtTr_AnxTipoCmp', query: 'query' || ''}
    });
    
    var tipoCmp = new Ext.form.ComboBox({
			fieldLabel:'tipo Comprobante'
			,id:'cod_tipoCmp'
			,name:'cod_tipoCmp'
			,store: dstipoCmp
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'tipoCmp'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,triggerAction: 	'query'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,listClass: 'x-combo-list-small'
			,minChars: 0
			,allQuery:""
			,lazyRender: true
			,cancelOnEsc: true
			,completeOnEnter:false
			,forceSelection:true
			,listClass: 'x-combo-list-small'
			,queryDelay:2 
			});
    
    Ext.util.Format.comboRenderer = function(combo){
	return function(value){
	    var record = combo.findRecord(combo.valueField, value);
	    return record ? record.get(combo.displayField) : combo.valueNotFoundText;
	}
    }
	    
    var cm_anulados = new Ext.grid.ColumnModel({  
      columns:[new Ext.grid.RowNumberer({width: 30})
              ,{  
                  header: 'ANIO'
                  ,dataIndex: 'anio'
                  ,width: 40
              },{  
                  header: 'MES'
                  ,dataIndex: 'mes'
                  ,width: 40
              },{  
                  header: 'TIPO COMPROBANTE'
                  ,dataIndex: 'tipoComprobante'
                  ,width: 130
		  ,editor: tipoCmp
		  ,renderer: Ext.util.Format.comboRenderer(tipoCmp)
		  
		  
              },{  
                  header: 'ESTABLECIMIENTO'
                  ,dataIndex: 'establecimiento'
                  ,width: 150
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,maxLength:3
		     })
              },{  
                  header: 'PUNTO DE EMISION'
                  ,dataIndex: 'puntoEmision'
                  ,width: 120
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,maxLength:3
		})
              },{  
                  header: 'SECUENCIAL INICIO'
                  ,dataIndex: 'secuencialInicio'
                  ,width: 120
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,maxLength:9
		     })
                  
              },{  
                  header: 'SECUENCIAL FIN'
                  ,dataIndex: 'secuencialFin'
                  ,width: 120
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,maxLength:9
		     })
              },{  
                  header: 'AUTORIZACION'
                  ,dataIndex: 'autorizacion'
                  ,width: 120
		  ,editor: new Ext.form.TextField({
                         allowBlank: false
			 ,maxLength:10
		     })
              }]
              
    });
    
    var grd_anulados  =  new Ext.grid.EditorGridPanel({
               id: 'grd_anulados'
               ,lazyRender: true
               ,store: store_anulados
	       ,stripeRows :true
	       ,clicksToEdit: 1
               ,collapsible: true
               ,cm: cm_anulados
               ,bbar:[{
                        text: 'Agregar'
                        ,id: "btnAddAnulados"
                        ,handler: fAgregaGridAnulados
                        ,tooltip : "Agregar Anulado"
                      },{
                        text: 'Eliminar'
                        ,id: "btnDelAnulados"
                        ,handler: fEliminarGridAnulados
                        ,tooltip : "Eliminar Anulado"
                      },{
                        text: 'Guardar'
                        ,id: "btnSaveAnulado"
                        ,handler: function(){fGuardarGrid("grd_anulados",3);} /*TIPO=3 - guardar registro ANULADOS*/
                        ,tooltip : "Guarda los cambios realizados"
                      },{
                        text: 'Consultar'
                        ,id: "btnConsAnulados"
                        ,handler: function(){fCargaGrid("grd_anulados");} 
                        ,tooltip: "Consultar los registros de comprobantes anulados en el año y mes especificado"
                      },{
                        text: 'Imprimir'
                        ,id: "btnPrintAnulados"
                        ,handler: function(){basic_printGrid(grd_anulados);}
                        //,iconCls:'iconImprimir'
                        ,tooltip : "Imprimir Comprobantes Anulados"
                      }]
	       ,height:500
	       ,selModel: new Ext.grid.RowSelectionModel({singleSelect:false})
	       ,title:"ANULADOS"
	       ,listeners: {
                    destroy: function(c) {
                        c.getStore().destroy();
                    }
        }
    });
    
    panelAnulados = new  Ext.Panel({
        title:''
	,id:'panelAnulados'
        ,items: [grd_anulados]

    });
}

function fAgregaGridAnulados(){
	if (fValidarParametros() == true){
		var olGrd = Ext.getCmp("grd_anulados");
		olGrd.stopEditing();
		var p=olGrd.store.data.length;
		var alRecs=[];
                var r=new olRecAnulados({
			    id:0 /*Se la ingresa con 0 xq eso se genera automaticamete cuando se inserte el registro, ademas asi se controla si el registro es nuevo o no -para alguna eliminacion o actualizacion*/
			    ,anio:Ext.getCmp("anio").getValue()
			    ,mes:Ext.getCmp("mes").getValue()
			    ,tipoComprobante:1
			    ,establecimiento:"001"
			    ,puntoEmision:"001"
			    ,secuencialInicio:""
			    ,secuencialFin:""
			    ,autorizacion:""
			    ,fechaIngreso:new Date()
			    ,usuario:""
			    ,estado:1
			    ,fechaModifica:"0000-00-00"
			    ,usuarioModifica:""
			    });
		alRecs.push(r);
		olGrd.store.insert(p,alRecs);
	}
}

function fEliminarGridAnulados(){
    var olGrd = Ext.getCmp("grd_anulados");
    var p=olGrd.store.data.length;
    if (p > 0){
	var trec = olGrd.getSelections().length;
	if (trec > 0){
		var respuesta = false; // para el mensaje al eliminar la transaccion
		Ext.MessageBox.confirm('Eliminar', 'Esta seguro que desea continuar? esta accion eliminara permanentemente los registros seleccionados',
		    function grabar(respuesta){
		    if(respuesta == 'yes'){
			    record = olGrd.getSelections();
			    var i;
			    for (i=0; i < record.length; i++ ){
				olGrd.stopEditing();
				if (record[i].data.dau_id == 0){ //El registro es nuevo
				    var idx = olGrd.store.indexOf(record[i]); 
				    olGrd.store.removeAt(idx);
				}else{ //El registro existe en la base
					    var oParams = {};
					    oParams['anio'] = record[i].data.anio;
					    oParams['mes'] = record[i].data.mes;
					    oParams['id'] = record[i].data.id;
				            fProcesar(oParams,4);
					    var idx = olGrd.store.indexOf(record[i]); 
					    olGrd.store.removeAt(idx);
				    }
			    }
		}});
	}
    }
}

/**
 * 	FUNCIONES GENERALES
 **/
function fCargaGrid(grid){
      if (fValidarParametros() == true){
		Ext.getCmp(grid).getStore().removeAll();
		storeGrd =  Ext.getCmp(grid).getStore();
		storeGrd.baseParams.panio= Ext.getCmp("anio").getValue();
		storeGrd.baseParams.pmes= Ext.getCmp("mes").getValue();
		storeGrd.load();
      }
}
function fGuardarGrid(grid, tipo){
	if (fValidarParametros() == true){
	var olGrd = Ext.getCmp(grid);
	var olRec = olGrd.getStore().modified; //Solo se guardan los items que han sido modificados o los que se insertaron en el grid
	if (olRec.size() > 0){ //El total de registros modificados
		
	    var respuesta = false; // para el mensaje al eliminar la transaccion
	    Ext.MessageBox.confirm('Guardar Cambios', 'Esta seguro que desea guardar los cambios realizados?',
		function grabar(respuesta){
		if(respuesta == 'yes'){
		    olGrd.stopEditing();
		    var i;
		    
		    for (i=0; i < olRec.size(); i++ ){ // para recorrer cada registro
			var url = "";
			var oParams = {};
			for (var j=0; j< olRec[i].fields.keys.length; j++){ //para recorrer cada columna del registro 
			    pFld = olRec[i].fields.keys[j]; //el nombre de la columna
			    m= olRec[i].data[pFld]; //el dato de la columna
			    
			    if((typeof(olRec[i].data[pFld]) != "undefined" )) {  // it is in modified fields
				//url += "&"+pFld+"="+olRec[i].data[pFld]; //url
				oParams[pFld] = olRec[i].data[pFld]; //oParams
			    }
			}
			//Guardar registro 
			fProcesar(oParams,tipo);
		    }
		    
		}
		}
	    );
	    
	}
	
	}
}




function fProcesar(oParams,tipo){
        Ext.Ajax.request({
		url:"CoRtTr_archxml_panel_grabar.php?tipo="+tipo
		,params:oParams
		,waitMsg: 'ACTUALIZANDO...'
		,method:   'POST'
		,success: function(pResp, pOpt){
		        //fCargaGridDaus()
			var mensaje = pResp.responseText;
			Ext.Msg.alert('Alerta',mensaje);
		}
		,failure: function(pResp, pOpt){
			Ext.Msg.alert("No se actualizaron los registros");
		}
		});
};



function fValidarDaus(){
	
	/*if (Ext.getCmp("pIni").isValid() == false){
		Ext.MessageBox.alert("Ingrese una Semana de Inicio correcta");
		return false;
	}
	if ((Ext.getCmp("pIni").getValue()> 0) && (Ext.getCmp("pFin").getValue()> 0)){
		return true;
	}
	return false;*/
}

function fValidarParametros(){
	if ((Ext.getCmp("anio").isValid() == true) && (Ext.getCmp("mes").isValid() == true)){
		return true;
	}
	Ext.MessageBox.alert("Ingrese anio y mes");
	return false;
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
            },useCustom:{   // in this case you leave null values as we dont use a custom store and TPL
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
				pageNo:'Page # ',	//pa ge label
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

