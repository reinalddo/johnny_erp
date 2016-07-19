/*
 * @TODO: Ajustar la logica para embarques
 * Detalles de Embarque
 * Copyright(c) 2006-2007, Fausto Astudillo
 * @rev		fah 27/02/08	Soporte de tipo de contenedor
 *
 */
Ext.BLANK_IMAGE_URL = '../../resources/images/default/s.gif';
/*
*
*
*/
/*
*
*
*/
function fTraeLista(pCmb){
	a=0
}
/*
*   Renderizador de fechas para grids, garantiza la conversion de formato al cargar el gris y
*   al modificar el campo
*   @par    pVal    String      Valor stringual
*   @par    pCell   Object      Ref al objeto Celda
*   @par    prec    Object      Ref al Registro de datos actual
*   @par    pRidx   Integer     Indice de fila
*   @par    pCidx   Integer     Indice de clumna
*   @par    pGrid   Object      Ref al Objeto Grid Actual
**/
function fRenderDate(pVal,pCell,pRec, pRidx,pCidx,pGrid) {
    try {
        if (pVal.length == undefined) var dlFec=pVal;
        else var dlFec=Date.parseDate(pVal, "Y-m-d");
        pRec.data[this.name] =  dlFec.format('d/M/y');
        return dlFec.format('d/M/y');
    } catch (e){ return pVal};
}

/*
*   Limpia formulario para agregar datos nuevos
*/
function fLimpiarForm(pResp){
	if (pResp=="No") return false;
    var olAct = [
                {id: 'cnt_ID',   value:0},
		{id: 'cnt_Serie',    value:''},
                {id: 'cnt_Naviera',  value:''},
                {id: 'cnt_CandadoNav',  value:''},
                {id: 'cnt_CandadoCia',  value:''},
		{id: 'cnt_SelloNav', value:''},
		{id: 'cnt_SelloCia', value:''},
		{id: 'cnt_Embarque', value:0},
                {id: 'txt_Embarque', value:''},
		{id: 'cnt_Destino',  value:0},
                {id: 'txt_Destino',  value:''},
		{id: 'cnt_Tipo',  value:0},
                {id: 'txt_Tipo',  value:''},
		{id: 'cnt_Consignatario', value:0},
                {id: 'txt_Consignatario', value:''},
                {id: 'cnt_FecInicio', value:new Date().format('d/M/y')},
		{id: 'cnt_HorInicio', value:''},
		{id: 'cnt_FecFin',   value:''},
		{id: 'cnt_HorFin',   value:''},
		{id: 'cnt_Enchufe',  value:''},
		{id: 'cnt_CtrlTemp', value:''},
                {id: 'cnt_Temperatura', value:0},
                {id: 'cnt_Ventilacion', value:25},
		{id: 'cnt_CtrlEmbarque',  value:''},
		{id: 'cnt_Consignatario', value:0},
                {id: 'txt_Consignatario', value:''},
		{id: 'cnt_Observaciones', value:''},
                {id: 'cnt_Chequeador', value:0},
                {id: 'txt_Chequeador', value:''},
		{id: 'cnt_Usuario',  value:''},
		{id: 'cnt_FechaReg', value:''},
		{id: 'cnt_Anio',   value:0},
		{id: 'cnt_Semana', value:0},
		{id: 'cnt_Estado', value:0},
                {id: 'txt_Estado', value:''},
		{id: 'cnt_Peletizado', value:0},		
		{id: 'cnt_CantDeclarada', value:0},		
		{id: 'cnt_CodCajTra', value:''},
		{id: 'cnt_CantCajTra', value:0},
		{id: 'cnt_TipoCajTra', value:''},
		{id: 'cnt_FecZarpe', value:new Date().format('d/M/y')},
		{id: 'cnt_Chequeador2', value:0},
		{id: 'cnt_Chequeador3', value:0},
		{id: 'txt_Chequeador2', value:''},
		{id: 'txt_Chequeador3', value:''},
		{id: 'cnt_DestiFinal', value:''},
		{id: 'txt_DestiFinal', value:''}
	];

	fmFormGen.form.setValues(olAct);
	fInicializaFechas();
	document.getElementById('fmFormGen').enable();
	ilEventId=0;
}

gaHidden = new Array();
/*
*   Definir un arreglo conlos datos ocultos de los combos
*/
function fSelCombo(pCmb, pRec, pIdx){
	gaHidden[pCmb.hiddenName] = pCmb.getValue();
 }
/*
*
*/
function fInicializaFechas(){
	fmFormGen.findById('cnt_FecInicio').setValue((new Date()));
	fmFormGen.findById('cnt_FecFin').setValue((new Date()));
  fmFormGen.findById('cnt_CtrlTemp').setValue((new Date()));
  fmFormGen.findById('cnt_CtrlEmbarque').setValue((new Date()));
  fmFormGen.findById('cnt_FechaReg').setValue((new Date())); 
}
/*-----------------------------------------------------------------------------------
*
*
*------------------------------------------------------------------------------------*/

Ext.onReady(function(){
    Ext.QuickTips.init();
	ogCon = new Ext.data.Connection();  // Objeto Global de conexion

	var rdComboBase = new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;
	dsComboBase = 	new Ext.data.Store({
		proxy: 		new Ext.data.HttpProxy({
			url: '../Ge_Files/GeGeGe_queryToXml.php',
			metod: 'POST',
			extraParams: {cnt_ID : getFromurl("cnt_ID",null)}
		}),
			reader: 	rdComboBase,
			baseParams: {id : 'OpTrTr_contenedor_est', pTipo: false, query:''}
	});

   var olRecDet = Ext.data.Record.create([     // Estructura del registro
		{name: 'cnt_ID',		type: 'int'},
		{name: 'cnt_Serial',		type: 'string'},
		{name: 'cnt_Naviera',	type: 'int'},
		{name: 'txt_Naviera',	type:'string'},
		{name: 'cnt_SelloNav',	type: 'string'},
		{name: 'cnt_SelloCia',	type: 'string'},
		{name: 'cnt_Embarque',	type: 'int'},
		{name: 'txt_Embarque',	type:'string'},
		{name: 'cnt_Naviera',	type: 'int'},
		{name: 'cnt_Destino',	type: 'int'},
		{name: 'txt_Destino',	type:'string'},
		{name: 'cnt_Tipo',	type: 'int'},
		{name: 'txt_Tipo',	type:'string'},
		{name: 'cnt_Consignatario',	type: 'int'},
		{name: 'txt_Consignatario',	type:'string'},
		{name: 'cnt_FecInicio',         type: 'date', dateFormat:'Y-m-d h:i'},
		{name: 'cnt_HorInicio',	        type: 'string'},
		{name: 'cnt_FecFin',	        type: 'date', dateFormat:'Y-m-d h:i'},
		{name: 'cnt_HorFin',	        type: 'string'},
		{name: 'cnt_Enchufe', 	        type: 'date', dateFormat:'Y-m-d h:i'},
		{name: 'cnt_CtrlTemp',	        type: 'date', dateFormat:'Y-m-d h:i'},
		{name: 'cnt_Temperatura'},
                {name: 'cnt_Ventilacion',       type:'int'},
		{name: 'cnt_CtrlEmbarque',	type: 'date', dateFormat:'Y-m-d h:i'},
		{name: 'cnt_Chequeador',	type:'int'},
		{name: 'txt_Chequeador',	type:'string'},
		{name: 'cnt_Estado',	type:'int'},
		{name: 'txt_Estado',	type:'string'},
		{name: 'cnt_Observaciones',	type:'string'},
		{name: 'cnt_Usuario',	type:'string'},
		{name: 'cnt_CodCajTra', type:'string'},
		{name: 'cnt_CantCajTra', type:'int'},
		{name: 'cnt_TipoCajTra', type:'string'},
		{name: 'cnt_FecZarpe', type:'date', dateFormat:'Y-m-d h:i'},
		{name: 'cnt_Chequeador2', type:'int'},
		{name: 'cnt_Chequeador3', type:'int'},
		{name: 'txt_Chequeador2', type:'string'},
		{name: 'txt_Chequeador3', type:'string'},
		{name: 'cnt_DestiFinal', type:'int'},
		{name: 'txt_DestiFinal', type:'string'},
		{name: 'cnt_CantDeclarada', type:'int'},
		{name: 'cnt_Paletizado', type:'int'}		
  ]);

	rdForm = new Ext.data.JsonReader({root : 'data',  successProperty: 'success',totalProperty: 'totalRecords',  id:'cnt_ID'}, olRecDet);

    var fm = Ext.form, Ed = Ext.grid.GridEditor;
    var slAction = getFromurl('pAct', false);

    var alFields = ['cnt_ID',     'cnt_Serie',        'cnt_Naviera',      'cnt_SelloNav',
      'cnt_SelloCia',             'cnt_Embarque',		  'cnt_Tipo', 'cnt_Destino',      'cnt_Consignatario',
      'cnt_FecInicio',		        'cnt_HorInicio',    'cnt_FecFin',       'cnt_HorFin',
      'cnt_Enchufe',		          'cnt_CtrlTemp',     'cnt_CtrlEmbarque', 'cnt_Chequeador',
      'cnt_Observaciones',        'cnt_Usuario',      'cnt_FechaReg',     'cnt_Anio',
      'cnt_Semana',
	   'cnt_CodCajTra',
		 'cnt_CantCajTra',
		 'cnt_TipoCajTra',
		 'cnt_FecZarpe',
		 'cnt_Chequeador2',
		 'cnt_Chequeador3',
		 'txt_Chequeador2',
		 'txt_Chequeador3',
		 'cnt_DestiFinal',
		 'txt_DestiFinal', 'cnt_Paletizado','cnt_CantDeclarada'
	  ];
    slDateFmt  ='d-M-y';
    slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|';

    slDateTimeFmt  ='d-M-y H:i';
    slDateTimeFmts = 'd-m-y H:i|d-m-Y H:i|d-M-y H:i|d-M-Y H:i|d/m/y H:i|d/m/Y H:i|d/M/y H:i|d/M/Y H:i|d-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y|d/M/Y';
    fmFormGen = new Ext.FormPanel({
        id:         'fmFormGen',
        labelAlign: 'right',
        labelWidth: 75,
        width:      810,
        /*height:     360,*/
        autoheight:	true,
        style:      'font-size:8px',
        trackResetOnLoad: true ,
				buttonAlign: 'center',
        reader:      rdForm,
        border:       false,
        errorReader: new Ext.form.XmlErrorReader(),
        items: [{
            xtype:'fieldset',
            collapsible: true,
						cls: 'floatleft',
            //float: true,
            height: 260,
            shadow:true,
            title: 'IDENTIFICACION',
            style: 'font-size:8px; float:left',
            width: 280,
            border: false,
            bodyBorder: false,
            //autoHeight:true,
            //defaults: {width: 85},
            defaultType: 'textfield',
            collapsed: false,
            items :[
                {fieldLabel: 'ID',					width:85,					type: 'numberfield',  readOnly:true,  id: 'cnt_ID'},
                new Ext.form.ComboBox({fieldLabel:'NAVIERA', 	id:'txt_Naviera',    name:'txt_Naviera',
                    width:180, 		store: dsComboBase, 			displayField:	'txt',	valueField:     'cod',
                    hiddenName:'cnt_Naviera',			    		  selectOnFocus:	true,	typeAhead: 		true,
                    mode: 'remote',	minChars: 3,						triggerAction: 	'all',	forceSelection: true,
                    emptyText:'',	allowBlank:     false,		listWidth:      250 }),
                {fieldLabel: 'SERIE',            width:85,  id: 'cnt_Serial',      allowBlank:false},                
                {fieldLabel: 'CANDADO NAVIERA',  width:85,  id: 'cnt_SelloNav', cls: 'floatleft'},
                {fieldLabel: 'CANDADO COMPAÑIA', width:85,  id: 'cnt_SelloCia'},
                new Ext.form.ComboBox({fieldLabel:'TIPO', 	id:'txt_Tipo',    name:'txt_Tipo',
                    width:180, 		store: dsComboBase, 			displayField:	'txt',	valueField:     'cod',
                    hiddenName:'cnt_Tipo',			    		  	selectOnFocus:	true,	typeAhead: 		true,
                    mode: 'remote',	minChars: 3,						triggerAction: 	'all',	forceSelection: true,
                    emptyText:'',	allowBlank:     false,		listWidth:      250 }),
    
	            new Ext.form.ComboBox({fieldLabel:'VAPOR', 	id:'txt_Embarque',    name:'txt_Embarque',
                    width:180, 		store: dsComboBase, 			displayField:	'txt',	valueField:     'cod',
                    hiddenName:'cnt_Embarque',			    		selectOnFocus:	true,	typeAhead: 		true,
                    mode: 'remote',	minChars: 3,						triggerAction: 	'all',	forceSelection: true,
                    emptyText:'',	allowBlank:     false,		listWidth:      250 }),
                new Ext.form.ComboBox({fieldLabel:'DESTINO', id:'txt_Destino',    	name:'txt_Destino',
                    width:180, 		store: dsComboBase, 	displayField:	'txt',	valueField:     'cod',
                    hiddenName:		'cnt_Destino',			selectOnFocus:	true,	typeAhead: 		true,
                    mode: 'remote',	minChars: 3,			triggerAction: 	'all',	forceSelection: true,
                    emptyText:'',	allowBlank:     false,	listWidth:      250 }),
                new Ext.form.ComboBox({fieldLabel:'CONSIG NATARIO',
                    id:'txt_Consignatario',    				name:'txt_Consignatario',
                    width:180, 		store: dsComboBase, 	displayField:	'txt',	valueField:     'cod',
                    hiddenName:		'cnt_Consignatario',	selectOnFocus:	true,	typeAhead: 		true,
                    mode: 'remote',	minChars: 3,			triggerAction: 	'all',	forceSelection: true,
                    emptyText:'',	allowBlank:     false,	listWidth:      250 }),
                new Ext.form.ComboBox({fieldLabel:'DESTINO FINAL',
                    id:'txt_DestiFinal',    				name:'txt_DestiFinal',
                    width:180, 		store: dsComboBase, 	displayField:	'txt',	valueField:     'cod',
                    hiddenName:		'cnt_DestiFinal',		selectOnFocus:	true,	typeAhead: 		true,
                    mode: 'remote',	minChars: 3,			triggerAction: 	'all',	forceSelection: true,
                    emptyText:'',	allowBlank:     false,	listWidth:      250 }),
				{fieldLabel: 'CANT DECLARADA',            	width:80,  id: 'cnt_CantDeclarada',      value:0, allowBlank:false},
				{fieldLabel: 'PALETIZADO',            		width:20,  id: 'cnt_Paletizado',      value:0,
				 allowBlank:false,	 						tooltip:"Digite 1 si es carga paletizada, caso contrario '0'"}
				/*new Ext.form.Checkbox({id:'cnt_Paletizado', fieldLabel:'PALETIZADO', 
					xtype:"radio",   name:"cnt_Paletizado",          inputValue:"cnt_Paletizado",
					checked: 1 })*/
                ]
            },{
              xtype:'fieldset',
              title: 'Control',
              style: 'font-size:8px; float:none',
							cls: 'floatleft',
              collapsible: true,
              //autoHeight:true,
              defaults: {xtype: 'datefield', width: 115, format: slDateTimeFmt, altFormats: slDateTimeFmts},
              width: 225,
			  height: 260,
              defaultType: 'datefield',
              items :[
                {fieldLabel: 'FCHA INICIO', 		id: 'cnt_FecInicio', 	value:''}, /*value: (new Date()).format('d/M/y H:i')*/
                {fieldLabel: 'FCHA TERMINO',		id: 'cnt_FecFin',	value: ''},
                {fieldLabel: 'FECHA ENCHUFE',		id: 'cnt_Enchufe',    value:''},
                {fieldLabel: 'FECHA FRIO',		  id: 'cnt_CtrlTemp',    value:''},
                {fieldLabel: 'TEMPERATURA',  		id: 'cnt_Temperatura', 	xtype: 'textfield', width: 90},
                {fieldLabel: 'VENTILACION',  		id: 'cnt_Ventilacion', 	xtype: 'textfield', width: 30},
                {fieldLabel: 'FECHA ZARPE',	  	id: 'cnt_FecZarpe',    value:'', format:slDateTimeFmt, altFormats: slDateTimeFmts},
                ]
            },{
              xtype:'fieldset',
              title: 'Datos Extra',
              collapsible: true,
              height:260,
              width: 300,
              //defaults: {width: 90},
              defaultType: 'textfield',
              items :[
                new Ext.form.ComboBox({fieldLabel:'CHEQUEADOR', id:'txt_Chequeador',    name:'txt_Chequeador',
                    width:180, 		store: dsComboBase, 	displayField:	'txt',	valueField:     'cod',
                    hiddenName:		'cnt_Chequeador',		selectOnFocus:	true,	typeAhead: 		true,
                    mode: 'remote',	minChars: 3,			triggerAction: 	'all',	forceSelection: true,
                    emptyText:'',	allowBlank:     true,	listWidth:      250 }),
                new Ext.form.ComboBox({fieldLabel:'ESTADO',
                    id:'txt_Estado',    				  name:'txt_Estado',
                    width:180, 		store: dsComboBase, 	displayField:	'txt',	valueField:     'cod',
                    hiddenName:		'cnt_Estado',		selectOnFocus:	true,				typeAhead: 		true,
                    mode: 'remote',	minChars: 3,	triggerAction: 	'all',			forceSelection: true,
                    emptyText:'',	allowBlank:     true,											listWidth:      250 }),
				new Ext.form.ComboBox({fieldLabel:'CHEQUEADOR2',
                    id:'txt_Chequeador2',    				  name:'txt_Chequeador2',
                    width:180, 		store: dsComboBase, 	displayField:	'txt',	valueField:     'cod',
                    hiddenName:		'cnt_Chequeador2',		selectOnFocus:	true,				typeAhead: 		true,
                    mode: 'remote',	minChars: 3,	triggerAction: 	'all',			forceSelection: true,
                    emptyText:'',	allowBlank:     true,											listWidth:      250 }),
				new Ext.form.ComboBox({fieldLabel:'CHEQUEADOR3',
                    id:'txt_Chequeador3',    				  name:'txt_Chequeador3',
                    width:180, 		store: dsComboBase, 	displayField:	'txt',	valueField:     'cod',
                    hiddenName:		'cnt_Chequeador3',		selectOnFocus:	true,				typeAhead: 		true,
                    mode: 'remote',	minChars: 3,	triggerAction: 	'all',			forceSelection: true,
                    emptyText:'',	allowBlank:     true,											listWidth:      250 }),
				{fieldLabel: 'COD CJA TRATADA',		id: 'cnt_CodCajTra',	  width:140},
				{fieldLabel: 'CANT CJA TRATADA',		id: 'cnt_CantCajTra',	  width:80},
				{fieldLabel: 'TIPO CJA TRATADA',		id: 'cnt_TipoCajTra',	  width:140},
                {fieldLabel: 'OBSERVA CIONES',		id: 'cnt_Observaciones',	  xtype:'textarea',  width:200, 			height:100},
                {fieldLabel: 'Usuario',           id: 'cnt_Usuario',          width:90,       value: ''}
                ]
            }
        ] 
    });

    fmFormGen.findById('txt_Embarque').on('beforequery', 	function (pEvt){fSetCombo({id:'emb', pTipo: false})});
    fmFormGen.findById('txt_Naviera').on('beforequery', 	function (pEvt){fSetCombo({id:'per', pTipo: 84})});
    fmFormGen.findById('txt_Destino').on('focus', 			function (pEvt){fSetCombo({id:'des', pTipo: false})});
    fmFormGen.findById('txt_Consignatario').on('beforequery', function (pEvt){fSetCombo({id:'per', pTipo: 50})});
    fmFormGen.findById('txt_Chequeador').on('beforequery', 	function (pEvt){fSetCombo({id:'per', pTipo: 54})});
    fmFormGen.findById('txt_Estado').on('beforequery', 		function (pEvt){fSetCombo({id:'est', pTipo: false})});
	fmFormGen.findById('txt_Tipo').on('beforequery', 		function (pEvt){fSetCombo({id:'tip', pTipo: false})});
	fmFormGen.findById('txt_Chequeador2').on('beforequery', 	function (pEvt){fSetCombo({id:'chk', pTipo: 54})});
	fmFormGen.findById('txt_Chequeador3').on('beforequery', 	function (pEvt){fSetCombo({id:'chk', pTipo: 54})});
	fmFormGen.findById('txt_DestiFinal').on('focus', 	function (pEvt){fSetCombo({id:'dfi', pTipo: false})});
  //  ilGrupo = getCookie("g_cooGrupo", 10);
	ilGrupo = 10;

	fmFormGen.addButton({
		id:     'btnGrabar',
		tooltip: 'Graba los cambios realizados a los datos en pantalla',
		text:    'GRABAR',          // Grabar Registro de Ordenes --------------------
		handler: fGrabar
	});

	fmFormGen.addButton({
		id:     'btnNuevo',
		tooltip: 'Anula los cambios realizados a los datos en pantalla y limpia el contenido, para agregar un registro Nuevo',
		text:	'NUEVO',
		disabled: false,
		handler:  fNuevo
	});

	var olBtnModificar = fmFormGen.addButton({
		id:     'btnModif',
		text:   'MODIFICAR',
		tooltip: 'Habilita el Registro para ser modificado',
		//disabled:true,
		handler: function(){ document.getElementById('fmFormGen').enable();	}
	});

	fmFormGen.findById('txt_Embarque').on("select", fSelCombo);
	fmFormGen.findById('txt_Naviera').on("select", fSelCombo);
	fmFormGen.findById('txt_Estado').on("select", fSelCombo);
	fmFormGen.findById('txt_Destino').on("select", fSelCombo);
	fmFormGen.findById('txt_DestiFinal').on("select", fSelCombo);
	fmFormGen.findById('txt_Chequeador').on("select", fSelCombo);
	fmFormGen.findById('txt_Chequeador2').on("select", fSelCombo);
	fmFormGen.findById('txt_Chequeador3').on("select", fSelCombo);
	fmFormGen.findById('txt_Consignatario').on("select", fSelCombo);
	//fmFormGen.findById('txt_Naviera').on("select", fSelCombo);
	fmFormGen.render('divForm');
  var ilID=getFromurl("cnt_ID",false);
  fCargaReg(ilID);
	if (!ilID) fInicializaFechas();
});

// A reusable error reader class for XML forms
Ext.form.XmlErrorReader = function(){
  Ext.form.XmlErrorReader.superclass.constructor.call(this, {
    record : 'recordset',
    success: '@success'
    }, [
    'id', 'msg'
    ]
);
};
Ext.extend(Ext.form.XmlErrorReader, Ext.data.XmlReader);

function fCargaReg(pID){
  fmFormGen.load({
    url: '../Ge_Files/GeGeGe_queryToJson.php',
    params: {id: 'OpTrTr_contenedor', cnt_ID: pID}, 
    /*callback: yourFunction,  @TODO */
    //scope: yourObject, // optional scope for the callback
    discardUrl: false,
    nocache: false,
    text: "Cargando...",
    timeout: 1,
    scripts: false,
	  metod: 'POST'}
	);
}
function fSetCombo(opt){
  opt.id = "OpTrTr_contenedor_" + opt.id; // añadir el sufijo
  with (dsComboBase.baseParams){
      id =opt.id;
      pTipo=opt.pTipo
  }
  dsComboBase.lastOptions = {params:{id: dsComboBase.baseParams.id, pTipo: dsComboBase.baseParams.pTipo}};
}

function fGrabar(){
	if (!fmFormGen.form.isDirty()) {
		Ext.Msg.alert('AVISO', 'Los datos no han cambiado. <br>No tiene sentido Grabar');
		return false;
	}
	if (!fmFormGen.form.isValid()) {
		Ext.Msg.alert('ATENCION!!', 'Hay Información incompleta o inválida');
		return false;
	}
	var olModif = fmFormGen.form.items.items.findAll(function(olField){return olField.isDirty()});
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
      eval("oParams." + pObj.id + " = '" +  pObj.getValue() + "'" );
  }
  })
	ilId  = fmFormGen.findById('cnt_ID').getValue();   //usa un valor ya asignado
	if  (ilId > 0) {
		oParams.cnt_ID = ilId;
		var slAction='UPD';
	}	else var slAction='ADD';
	var slParams  = Ext.urlEncode(oParams);
	slParams += "&" +  Ext.urlEncode(gaHidden);
	fmFormGen.disable();
  fGrabarRegistro(fmFormGen, slAction, oParams, slParams);
	fmFormGen.enable();
}

function fNuevo(e){
		Ext.MessageBox.show({
			title:'ATENCION',
			msg: 'Desea Crear un nuevo Registro?<br>(Perderá las modificaciones no grabadas) ',
			buttons: Ext.MessageBox.YESNO,
			fn: fLimpiarForm,
			animEl: 'btnNuevo'
		});
}
 function fGrabarRegistro(fmForm, slAction, oParams, slParams){
  oParams.pTabla = 'opecontenedores';
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
          fmFormGen.findById('cnt_ID').setValue(responseData.lastId);
          fCargaReg(fmFormGen.findById('cnt_ID').getValue());
          break;
        case "UPD":
          slMens = 'Registro Actualizado';
          fCargaReg(fmFormGen.findById('cnt_ID').getValue());
          break;
      }
      Ext.Msg.alert('AVISO ', slMens);
    }, 
    failure: function(form, e) {
      if (e.failureType == 'server') {
        slMens = 'La operación no se ejecutó. ' + e.result.errors.id + ': ' + e.result.errors.msg;
      } else {
        slMens = 'El comando no se pudo ejecutar en el servidor';
      }
      Ext.Msg.alert('Proceso Fallido', slMens);
    }
  }//end request config
  ); //end request  
}; //end updateDB 

/*
*
*
*/
function fEliminaRegistro() {
	ogCon = new Ext.data.Connection();

}
