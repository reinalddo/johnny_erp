/*
 * Grid Editable con informacion del detalle de un comprobante
 * Cambio de nombre desde CpTrTr_detacompro.js 
 * http://extjs.com/license
 */
Ext.ns('li.em');
Ext.ns('gen');
Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"
xg=Ext.grid;



gen.xmlReader = function(config, flds) { // Generic XML Reader for comboboxes
    var config = config || {};
    var cnf = {record: 'record'
	    ,id: config.id ||'cod'}
    var flds= flds || ['cod','txt']
    Ext.applyIf(config, {
	 record: 'record'
	,id: config.id
    });
    gen.xmlReader.superclass.constructor.call(this, config, flds); // esta bien incluido los campos????
};
Ext.extend(gen.xmlReader, Ext.data.XmlReader);
Ext.reg('genXmlReader', gen.xmlReader); 

gen.dsComboStore = function(config) {
    var config = config || {};
    Ext.applyIf(config, {
	proxy: 		new Ext.data.HttpProxy({url: '../Ge_Files/GeGeGe_queryToXml.php', metod: 'POST'})
	,reader: new Ext.data.XmlReader({record: 'record',id: 'cod'}, ['cod','txt'])
	,baseParams: {id: config.id, query:config.qry || ''}
    });
    gen.dsComboStore.superclass.constructor.call(this, config);
};
Ext.extend(gen.dsComboStore, Ext.data.Store);

dsPerso= new Ext.data.Store({
	proxy: 		new Ext.data.HttpProxy({url: '../Ge_Files/GeGeGe_queryToXml.php', metod: 'POST'})
	,reader: new Ext.data.XmlReader({record: 'record',id: 'cod'}, ['cod','txt'])
	,baseParams: {id: "SvTrTr_personas"}
});
dsRefer= new Ext.data.Store({
	proxy: 		new Ext.data.HttpProxy({url: '../Ge_Files/GeGeGe_queryToXml.php', metod: 'POST'})
	,reader: new Ext.data.XmlReader({record: 'record',id: 'cod'}, ['cod','txt'])
	,baseParams: {id: "CoTrTr_referen"}
});


dsComboStore = 	Ext.extend(Ext.data.Store,{
    proxy: 	new Ext.data.HttpProxy({url: '../Ge_Files/GeGeGe_queryToXml.php', metod: 'POST'})
    ,reader: new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt'])
    ,initComponent:function(config) {
	Ext.apply(this, {baseParams: {id: config.id}}); 
	dsComboStore.superclass.initComponent.apply(this, arguments);
    }
});

gen.cmbBoxClass = function (config){
    var config = config || {};
    Ext.applyIf(config, {
	displayField:	'txt'
	,valueField:     'cod'
	,allowBlank: false
	,selectOnFocus:true
	,typeAhead: 		true
	,mode: 'remote'
	,forceSelection: true
	,emptyText:''
	,allowBlank:false
	,listWidth: 350
	,listClass: 'x-combo-list-small'
	,width: 160
	,id:    Ext.id()
	,width: 160
	,minChars:  6
        ,queryDelay:4 
	,trigerAction: "query"
	,allQuery: ""
	,store : new gen.dsComboStore({id:config.sqlId})
	,lazyRender: true
	,cancelOnEsc: true
	,completeOnEnter:false
	,forceSelection:true
    })
    gen.cmbBoxClass.superclass.constructor.call(this, config);
}
Ext.extend(gen.cmbBoxClass, Ext.form.ComboBox)
Ext.reg('genCmbBox', gen.cmbBoxClass); 
/*-------------------------------------------*/
Ext.ns("Ext.ux.renderer");
Ext.ux.renderer.ComboRenderer = function(options) {
    var value = options.value;
    var combo = options.combo;
    var returnValue = value;
    var valueField = combo.valueField;
    var idx = combo.store.findBy(function(record) {
	if(record.get(valueField) == value) {
	    returnValue = record.get(combo.displayField);
	    record.set(combo.displayField);
	    return true;
	}
    return false
    });
    //if(idx < 0 && Ext.isEmpty(value) /*value == 0*/) { returnValue = '';}
    if(idx < 0  ) { returnValue = value;}
    else {
	if (!Ext.isEmpty(combo.hiddenName))
            if (options.record.data[combo.hiddenName] != "undefined")  //@Bug:corregido
                options.record.set(combo.hiddenName, combo.value);
            //options.record.data[combo.hiddenName]= combo.value||""
	options.record.data[options.meta.id] =  combo.lastSelectionText || ""
    }
    return returnValue;
};

Ext.ux.renderer.Combo = function(combo) {
    
    return function(value, meta, record) {
        return Ext.ux.renderer.ComboRenderer({value: value, meta: meta, record: record, combo: combo});
    };
}

var rdComboBaseGeneral = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;
var rdComboEmbaque = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt', 'txt1', 'txt2', 'txt3', 'txt4']
		    ) ;
    dsCmbEmpaque = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboEmbaque,
            baseParams: {id : 'LiEmTj_empaque'}
    });
    
var edCmbEmpaque = new Ext.form.ComboBox({
                        hideLabel:true,
			fieldLabel:'Empaque',
			id:'txt_tad_CodCaja',
			name:'txt_tad_CodCaja',
			width:160,
			store: dsCmbEmpaque,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tad_CodCaja',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250
                        ,listClass: 'x-combo-list-small'
                        ,queryDelay:4
                        ,lazyRender: true
                        ,cancelOnEsc: true
                        ,completeOnEnter:false
                        ,forceSelection:true
                        //,tabIndex:1006
			//,tabindex:4
			/*,listeners: {'select': function (combo,record){
					win.findById("txt_semana").setValue(record.data.semana);
				    }}*/
		    });

dsCmbMarca = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_marca'}
    });
var edCmbMarca = new Ext.form.ComboBox({
        hideLabel:true,
        fieldLabel:'Marca',
        id:'txt_tad_CodMarca',
        name:'txt_tad_CodMarca',
        width:160,
        store: dsCmbMarca,
        displayField:	'txt',
        valueField:     'cod',
        hiddenName:'tad_CodMarca',
        selectOnFocus:	true,
        typeAhead: 		true,
        mode: 'remote',
        minChars: 3,
        triggerAction: 	'all',
        forceSelection: true,
        emptyText:'',
        allowBlank:     false,
        listWidth:      250
        ,listClass: 'x-combo-list-small'
        ,queryDelay:4
        ,lazyRender: true
        ,cancelOnEsc: true
        ,completeOnEnter:false
        ,forceSelection:true
    });


edCmbMarca.on("select",function(pCmb, pRec, pIdx){
	    var olRs = Ext.getCmp("txt_tad_CodCaja").getStore()
	    olRs.proxy.getConnection().extraParams= {pMarca: pRec.data.cod};
	    olRs.load();
	});

dsCmbProducto = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_producto'}
    });
var edCmbProducto = new Ext.form.ComboBox({
        hideLabel:true,
        fieldLabel:'Marca',
        id:'txt_tad_CodProducto',
        name:'txt_tad_CodProducto',
        width:160,
        store: dsCmbProducto,
        displayField:	'txt',
        valueField:     'cod',
        hiddenName:'tad_CodProducto',
        selectOnFocus:	true,
        typeAhead: 		true,
        mode: 'remote',
        minChars: 3,
        triggerAction: 	'all',
        forceSelection: true,
        emptyText:'',
        allowBlank:     false,
        listWidth:      250
        ,listClass: 'x-combo-list-small'
        ,queryDelay:4
        ,lazyRender: true
        ,cancelOnEsc: true
        ,completeOnEnter:false
        ,forceSelection:true
    })



dsCmbCarton = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_componente'}
    });
var edCmbCarton = new Ext.form.ComboBox({
        hideLabel:true,
        id:'txt_tad_CodCompon1',
        name:'txt_tad_CodCompon1',
        width:160,
        store: dsCmbCarton,
        displayField:	'txt',
        valueField:     'cod',
        hiddenName:'tad_CodCompon1',
        selectOnFocus:	true,
        typeAhead: 		true,
        mode: 'remote',
        minChars: 2,
        triggerAction: 	'all',
        forceSelection: true,
        emptyText:'',
        allowBlank:     false,
        listWidth:      250
        ,listClass: 'x-combo-list-small'
        ,queryDelay:4
        ,lazyRender: true
        ,cancelOnEsc: true
        ,completeOnEnter:false
        ,forceSelection:true
    })


dsCmbPlastico = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_componente'}
    });
var edCmbPlastico = new Ext.form.ComboBox({
        hideLabel:true,
        id:'txt_tad_CodCompon2',
        name:'txt_tad_CodCompon2',
        width:160,
        store: dsCmbPlastico,
        displayField:	'txt',
        valueField:     'cod',
        hiddenName:'tad_CodCompon2',
        selectOnFocus:	true,
        typeAhead: 		true,
        mode: 'remote',
        minChars: 2,
        triggerAction: 	'all',
        forceSelection: true,
        emptyText:'',
        allowBlank:     false,
        listWidth:      250
        ,listClass: 'x-combo-list-small'
        ,queryDelay:4
        ,lazyRender: true
        ,cancelOnEsc: true
        ,completeOnEnter:false
        ,forceSelection:true
    })


dsCmbMaterial = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_componente'}
    });
var edCmbMaterial = new Ext.form.ComboBox({
        hideLabel:true,
        id:'txt_tad_CodCompon3',
        name:'txt_tad_CodCompon3',
        width:160,
        store: dsCmbMaterial,
        displayField:	'txt',
        valueField:     'cod',
        hiddenName:'tad_CodCompon3',
        selectOnFocus:	true,
        typeAhead: 		true,
        mode: 'remote',
        minChars: 3,
        triggerAction: 	'all',
        forceSelection: true,
        emptyText:'',
        allowBlank:     false,
        listWidth:      250
        ,listClass: 'x-combo-list-small'
        ,queryDelay:4
        ,lazyRender: true
        ,cancelOnEsc: true
        ,completeOnEnter:false
        ,forceSelection:true
    });

dsCmbEtiqueta = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		      }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_componente'}
    });
var edCmbEtiqueta = new Ext.form.ComboBox({
        hideLabel:true,
        fieldLabel:'Marca',
        id:'txt_tad_CodCompon4',
        name:'txt_tad_CodCompon4',
        width:160,
        store: dsCmbEtiqueta,
        displayField:	'txt',
        valueField:     'cod',
        hiddenName:'tad_CodCompon4',
        selectOnFocus:	true,
        typeAhead: 		true,
        mode: 'remote',
        minChars: 3,
        triggerAction: 	'all',
        forceSelection: true,
        emptyText:'',
        allowBlank:     false,
        listWidth:      250
        ,listClass: 'x-combo-list-small'
        ,queryDelay:4
        ,lazyRender: true
        ,cancelOnEsc: true
        ,completeOnEnter:false
        ,forceSelection:true
    })


function comboBoxRenderer (pVal, pMeta, pRec, pRow, pCol, pStr) {
    var olGrid=Ext.getCmp("gridDetalle");
    var olEd=olGrid.getColumnModel().getCellEditor( pCol,  pRow);
    if (pRec.dirty){
        pRec.set(olEd.field.hiddenName, pVal)
        return olEd.field.store.getAt(olEd.field.selectedIndex).data.txt
    }
    else return pVal
}

function rendProve (pVal, pMeta, pRec, pRow, pCol, pStr) {
    var olGrid=Ext.getCmp("gridDetalle");
    var olEd=olGrid.getColumnModel().getCellEditor( pCol,  pRow);
    if (olEd.field.selectedIndex>=0){
        pRec.set(olEd.field.hiddenName, pVal)
        olEd.field.selectedIndex = -1
        return olEd.field.store.getAt(olEd.field.selectedIndex).data.txt
    }
    else return pVal
}
function rendRefer (pVal, pMeta, pRec, pRow, pCol, pStr) {
    var olGrid=Ext.getCmp("gridDetalle");
    var olEd=olGrid.getColumnModel().getCellEditor( pCol,  pRow);
    if (olEd.field.selectedIndex>=0){
        pRec.set(olEd.field.hiddenName, pVal)
        olEd.field.selectedIndex = -1
        return olEd.field.store.getAt(olEd.field.selectedIndex).data.txt
    }
    else return pVal
}

function fGridRenderDate(pVal,pMeta,pRec, pRidx,pCidx,pStor) {
    try {
        slRcFmt=pRec.fields.get("rga_FecEmision").dateFormat || "Y-m-d";
		slInFmt=Ext.getCmp("gridDetalle").getColumnModel().getCellEditor(pCidx,pRec).field.format || "d/M/y"
        if (pVal.length == undefined) var dlFec=pVal;
        else var dlFec=Date.parseDate(pVal, slRcFmt);
        pRec.data[this.name]=  dlFec.format(slRcFmt);
        return dlFec.format(slInFmt);
    } catch (e){ return pVal};
}

Ext.onReady(function(){
    Ext.QuickTips.init();
    var xg = Ext.grid;
  
/**
 * gen.Data.Store
 * @extends Ext.data.Store
 * @cfg {String} url This will be a url of a location to load the references
 * This is a specialized Store which maintains data for a ComboBox with a cod field and a text fiels
  * Record defintion:
 *  - cod
 *  - text
 */
    
    Ext.ux.grid.GridSummary.Calculations['totalCantDesp'] = function(v, record, field){
        var a= v + record.data.tad_CantDespachada;
	return a;
    }
    Ext.ux.grid.GridSummary.Calculations['totalCantRec'] = function(v, record, field){
	    var a= v + record.data.tad_CantRecibida;
	    return a;
    } 
    Ext.ux.grid.GridSummary.Calculations['totalFaltSob'] = function(v, record, field){
	    var a= v + record.data.tad_CantRecibida-record.data.tad_CantDespachada;
	    return a;
    }
    Ext.ux.grid.GridSummary.Calculations['totalCantRech'] = function(v, record, field){
	    var a= v + record.data.tad_CantRechazada;
	    return a;
    }
    Ext.ux.grid.GridSummary.Calculations['totalCantCaida'] = function(v, record, field){
	    var a= v + record.data.tad_CantCaidas;
	    return a;
    }
   
    gridSummary = new Ext.ux.grid.GridSummary();
   
    li.em.detstore = new Ext.data.JsonStore({
        id:"stDetalle",
        url: 'LiEmTj_Tarjadetalle.php?',
        baseParams: {start:0, limit:50, sort:'tad_Secuencia', dir:'ASC'},
        root:"rows",
        fields: fCampos(),
        sortInfo: {field:'tad_Secuencia', direction: 'ASC'},
        pruneModifiedRecords: true
    });

    goBbar= new Ext.PagingToolbar({
        pageSize: 50
        ,store: li.em.detstore
        ,displayInfo: true
        ,displayMsg: 'Registros {0} - {1} de {2}'
        ,emptyMsg: "No hay datos que presentar"
        ,items: ["-",{width:400}, "-",{
            text: 'Agregar Detalles'
            ,id: "btnAdd"
            ,iconCls:'new-item'
            ,tooltip : "Agrega 5 lineas de detalle"
            ,handler : fAgregar
            ,disabled: true
            }
            ]
    })
    edDecimal = Ext.extend (Ext.form.NumberField, {
        allowBlank: false,
        allowNegative: false,
        allowDecimals: true,
        decimalPrecision: 2,
        selectOnFocus:true,
        style: 'text-align:right',
        change:function(a,b,c,d){
            m=a;
        },
        onChange:function(a,b,c,d){
            m=a;
        }
        }
    )
    Ext.reg('edDecimal', edDecimal);
    
    edDeb = new edDecimal();
    edDeb.on("change", function (){
        m=0
    })
 
    edDate = Ext.extend (Ext.form.DateField, {
        allowBlank: false,
        allowNegative: false,
        selectOnFocus:true,
        format: "Y-m-d",
        altFormats: "Y-m-d/|d/m/y|d/M/y|dmy|dMy|d-m-y|d-M-y",
        style: 'text-align:center',
        renderer: fGridRenderDate}
    )
    Ext.reg('edDate', edDate);

    edInteger = Ext.extend (Ext.form.NumberField, {
        allowBlank: false,
        allowNegative: false,
        allowDecimals: false,
        decimalPrecision: 0,
        selectOnFocus:true,
        style: 'text-align:right'
    })
    Ext.reg('edInteger', edInteger);
    
    olRec = Ext.data.Record.create([
        {name: '_newFlag',          type:'int'}
		,{name: 'tad_CodEmpresa',    type:'int',     defaultValue:"0"}
		,{name: 'tad_NumTarja',    type:'int',     defaultValue:getFromurl("tad_NumTarja", "")}
		,{name: 'tad_Secuencia',     type:'int'/*,     defaultValue:getFromurl("com_TipoComp", 'XX')*/}
		,{name: 'tad_CodProducto',      type:'int'/*,     defaultValue:getFromurl("com_NumComp", -1)*/}
		,{name: 'tad_CodEmpacador',    type:'int',     defaultValue:0}
		,{name: 'tad_CodMarca', type:'int',     defaultValue:0}
		,{name: 'tad_CodCaja',   type:'int',     defaultValue:0}
		,{name: 'tad_CantDespachada',    type:'float',   defaultValue:0}
		,{name: 'tad_CantRecibida',   type:'float',   defaultValue:0}
		,{name: 'tad_CantRechazada',        type:'float',  defaultValue:0}
		,{name: 'tad_CantCaidas',  type:'float', defaultValue:0}
		,{name: 'tad_CodCompon1',    type:'int',  defaultValue:''}
		,{name: 'tad_CodCompon2',  type:'int',   defaultValue:0}
		,{name: 'tad_CodCompon3',  type:'int',   defaultValue:0}
		,{name: 'tad_CodCompon4', type:'int',   defaultValue:0}
		,{name: 'tad_ValUnitario', type:'float',   defaultValue:0}
		,{name: 'tad_DifUnitario', type:'float',     defaultValue:0}
		,{name: 'tad_LisPrecio', type:'float',    defaultValue:0/*(new Date('31/12/2999')).format("d-m-y")*/}
		,{name: 'tad_LiqProceso',    type:'float',     defaultValue:0}
		,{name: 'tad_LiqNumero',    type:'float',    defaultValue:0}
		,{name: 'tad_Calidad',    type:'float',    defaultValue:0}
		,{name: 'tad_Peso',    type:'float',    defaultValue:0}
		,{name: 'tad_Largo',    type:'float',    defaultValue:0}
		,{name: 'tad_NumDedos',    type:'float',    defaultValue:0}
		,{name: 'tad_ClusCaja',    type:'float',    defaultValue:0}
		,{name: 'tad_Observaciones', type:'string',     defaultValue:0}
		,{name: 'tad_precpactado',    type:'int',     defaultValue:0}
		,{name: 'tad_indFlete',    type:'int',    defaultValue:0}
	]);
    edProve = new Ext.form.ComboBox({
        displayField:	'txt'
        ,valueField:     'cod'
        ,hiddenName:    "det_IdAuxiliar"
        ,allowBlank: false
        ,selectOnFocus:true
        ,typeAhead: 		true
        ,mode: 'remote'
        ,forceSelection: true
        ,emptyText:''
        ,allowBlank:false
        ,listWidth:      250
        ,listClass: 'x-combo-list-small'
        ,width: 160
        ,id:    Ext.id()
        ,trigerAction: "query"
        ,allQuery: ""
        ,lazyRender: true
        ,cancelOnEsc: true
        ,completeOnEnter:false
        ,width: 160
        ,minChars:  9
        ,store : dsPerso
    })

    edRefer = new Ext.form.ComboBox({
        displayField:	'txt'
        ,valueField:     'cod'
        ,hiddenName:    "det_RefOperativa"
        ,allowBlank: false
        ,selectOnFocus:true
        ,typeAhead: 		true
        ,mode: 'remote'
        ,forceSelection: true
        ,emptyText:''
        ,allowBlank:false
        ,listWidth:      250
        ,listClass: 'x-combo-list-small'
        ,width: 160
        ,id:    Ext.id()
        ,width: 160
        ,minChars:  9
        ,trigerAction: "query"
        ,allQuery: ""
        ,store : dsRefer
        ,lazyRender: true
        ,cancelOnEsc: true
        ,completeOnEnter:false
    })
    
    li.em.detgrid = new xg.EditorGridPanel({
        ds: li.em.detstore
        ,id: 'gridDetalle'
        , columns: [ new Ext.grid.RowNumberer()
			,{header: "EMPRE",      width:  20, hidden: true, dataIndex: 'tad_CodEmpresa'}
            ,{header: "NUM.TARJA",     width:  20, hidden: true, dataIndex: 'tad_NumTarja'}
     	    ,{header: "SEC.",       width:  20, hidden: false , dataIndex: 'tad_Secuencia'}
            ,{header: "cod marca",     width: 50, hidden: true, dataIndex: 'tad_CodMarca',       editor:new Ext.form.TextField()}
            ,{header: "MARCA",      width:  100, hidden: false , dataIndex: 'txp_Marca',         id:"txp_Marca", editor:edCmbMarca, renderer: Ext.ux.renderer.Combo(edCmbMarca)}
	        ,{header: "cod Empaque",     width: 50, hidden: true, dataIndex: 'tad_CodCaja',       editor:new Ext.form.TextField()}
            ,{header: "EMPAQUE",  width:  100, hidden: false, dataIndex: 'txp_CajDescrip',         id:"txp_CajDescrip", editor:edCmbEmpaque, renderer: Ext.ux.renderer.Combo(edCmbEmpaque)}
            ,{header: "cod producto",     width: 50, hidden: true, dataIndex: 'tad_CodProducto',       editor:new Ext.form.TextField()}
            ,{header: "PRODUCTO",      width:  100, hidden: false , dataIndex: 'txp_Producto',         id:"txp_Producto", editor:edCmbProducto, renderer: Ext.ux.renderer.Combo(edCmbProducto)}
            ,{header: "EMPACADOR",        width:  20, hidden: true, dataIndex: 'tad_CodEmpacador'}          
            ,{header: "DESPACHADO",     width: 50, hidden: false, dataIndex: 'tad_CantDespachada',       editor:new Ext.form.TextField(),summaryType: 'totalCantDesp',summaryRenderer: Ext.util.Format.usMone}
            ,{header: "RECIB.PTO",     width:  50, hidden: false , dataIndex: 'tad_CantRecibida',       editor:new Ext.form.TextField(),summaryType: 'totalCantRec',summaryRenderer: Ext.util.Format.number}
            ,{header: "FALT./ SOBR.",     width:  50, hidden: false , dataIndex: 'txt_falsob',renderer: function(v, params, record){
												val= (record.data.tad_CantRecibida-record.data.tad_CantDespachada);
												if(val > 0){
												    return '<span style="color:green;">' + val + '</span>';
												}else if(val < 0){
												    return '<span style="color:red;">' + val + '</span>';
												}
												return '<span style="color:gray;">' + val + '</span>';
											    },summaryType: 'totalFaltSob',summaryRenderer: Ext.util.Format.number}
	    ,{header: "RECHAZADA",       width:  50, hidden: false, dataIndex: 'tad_CantRechazada',      editor:new Ext.form.TextField(),summaryType: 'totalCantRech',summaryRenderer: Ext.util.Format.number}
            ,{header: "CAIDA",     width:  50, hidden: false , dataIndex: 'tad_CantCaidas',       editor:new Ext.form.TextField(),summaryType: 'totalCantCaida',summaryRenderer: Ext.util.Format.number}
	    ,{header: "EMBARCADO",     width:  50, hidden: false , dataIndex: 'txt_falsob',renderer: function(v, params, record){
												val= (record.data.tad_CantRecibida-record.data.tad_CantCaidas-record.data.tad_CantRechazada);
												if(val > 0){
												    return '<span style="color:green;">' + val + '</span>';
												}else if(val < 0){
												    return '<span style="color:red;">' + val + '</span>';
												}
												return '<span style="color:gray;">' + val + '</span>';
											    }}
	    ,{header: "$ CAJA",width: 50, hidden: true, dataIndex: 'tad_ValUnitario',          align:'right',  summaryType:'sum', renderer: fRendQuantity,    summaryRenderer: fRendQuantity, editor:edDeb}
            ,{header: "DIF PRECIO",   width:  50, hidden: true, dataIndex: 'tad_DifUnitario',     align:'right',  summaryType:'sum', renderer: fRendQuantity,    summaryRenderer: fRendQuantity, editor:new edDecimal}
            ,{header: "cod CARTON",     width:  80, hidden: true, id: 'tad_CodCompon1',             dataIndex: 'tad_CodCompon1', editor:new Ext.form.TextField()      }
            ,{header: "CARTON",  width:  100, hidden: true, dataIndex: 'txp_carton',         id:"txp_carton", editor:edCmbCarton, renderer: Ext.ux.renderer.Combo(edCmbCarton)}
            ,{header: "cod PLASTICO",    width:  80, hidden: true, id: 'tad_CodCompon2',            dataIndex: 'tad_CodCompon2', editor:new Ext.form.TextField()  }
            ,{header: "PLASTICO",  width:  100, hidden: true, dataIndex: 'txp_plastico',         id:"txp_plastico", editor:edCmbPlastico, renderer: Ext.ux.renderer.Combo(edCmbPlastico)}
            ,{header: "cod MATERIAL",      width: 80, hidden: true, dataIndex: 'tad_CodCompon3', id:'tad_CodCompon3' , editor:new Ext.form.TextField()}
            ,{header: "MATERIAL",  width:  100, hidden: true, dataIndex: 'txp_material',         id:"txp_material", editor:edCmbMaterial, renderer: Ext.ux.renderer.Combo(edCmbMaterial)}
            ,{header: "cod ETIQUETA", width: 80, hidden: true,   dataIndex: 'tad_CodCompon4', id:'tad_CodCompon4' , editor:new Ext.form.TextField()}
            ,{header: "ETIQUETA",  width:  100, hidden: true, dataIndex: 'txp_etiq', id:"txp_etiq", editor:edCmbEtiqueta, renderer: Ext.ux.renderer.Combo(edCmbEtiqueta)} 
            ,{header: "LIS PRECIO",   width:  50, hidden: true, dataIndex: 'tad_LisPrecio'}
            ,{header: "LIS PROCESO",   width:  20, hidden: true, dataIndex: 'tad_LiqProceso'}
            ,{header: "LIQ NUM",   width:  20, hidden: true, dataIndex: 'tad_LiqNumero'}
	        ,{header: "CALIDAD",   width:  50, hidden: false, dataIndex: 'tad_Calidad',
																					editor: new Ext.form.NumberField({
																		                allowBlank: false
																		                ,listeners: {'change': function (combo,record){
																							if(fValidarCampos(0,"tad_Calidad",record)==false)
																									record=0;
																						}}
																					})}
	        ,{header: "PESO",   width:  50, hidden: false, dataIndex: 'tad_Peso',
																		    	editor: new Ext.form.NumberField({
																	                allowBlank: false
																	                ,listeners: {'change': function (combo,record){
																						fValidarCampos(1,"tad_Peso",record)
																					}}
																				})}
	       ,{header: "CALIBRE",   width:  50, hidden: false, dataIndex: 'tad_Largo',
																				    	editor: new Ext.form.NumberField({
																			                allowBlank: false
																			                ,listeners: {'change': function (combo,record){
																								fValidarCampos(2,"tad_Largo",record)
																							}}
																						})}
	       ,{header: "DED. X CLUS.",   width:  50, hidden: false, dataIndex: 'tad_NumDedos',    	
	    																						editor: new Ext.form.NumberField({
																					            allowBlank: false
																					            ,listeners: {'change': function (combo,record){
																									fValidarCampos(3,"tad_NumDedos",record)
																								}}
																							})}
 	       ,{header: "CLUS. CAJA",   width:  50, hidden: false, dataIndex: 'tad_ClusCaja',
																								editor: new Ext.form.NumberField({
																						            allowBlank: false
																						            ,listeners: {'change': function (combo,record){
																										fValidarCampos(4,"tad_ClusCaja",record);
																										record=0;
																									}}
																								})}
            ,{header: "OBSERV.",  width:  80, hidden: false, dataIndex: 'tad_Observaciones', id:'tad_Observaciones' , editor:new Ext.form.TextField()}
            ,{header: "PREC.PACT.",  width:  20, hidden: true, dataIndex: 'tad_precpactado'}
            ,{header: "FLETE",    width:  20, hidden: true , dataIndex: 'tad_indFlete'}
       ]
       ,plugins: [gridSummary]     //[summary, checkColumn, expander, gridSummary]
        ,bbar: goBbar
        ,autowidth:true
        ,autoheight: true
        ,height:150
     	,width:770
	    ,clicksToEdit: 2
        ,loadMask: true
        ,collapsible: true
        ,animCollapse: false
        ,trackMouseOver: true
        //enableColumnMove: false,
        ,iconCls: 'icon-grid'
        ,stripeRows:true
	    ,monitorResize:true
	    ,forceFit:true
        ,sm: new Ext.grid.RowWithCellSelectionModel()
        ,renderTo: gsObj
		,keys: [
		{
			key: [10,13]
			,fn: function(){ alert("Return pressed"); }
		}, {
			key: Ext.EventObject.UP
			,fn: function(){ alert('Arriba'); }
		}, {
			key: Ext.EventObject.DOWN
			,fn: function(){ alert('Abaj'); }
		}, {
			key: Ext.EventObject.LEFT
			,fn: function(){ alert('Izquierda'); }
		}, {
			key: Ext.EventObject.RIGHT
			,fn: function(){ alert('Derecha'); }
		}
		]
  });

edCmbEmpaque.on("select", function(pCmb, pRec, pIdx){
			   olGrd = Ext.getCmp("gridDetalle");
			   var p=olGrd.getSelectionModel().selection.cell[0]
			    // var p= olGrd.getSelectionModel().getSelectedCell()[0];
			     var rec = olGrd.getStore().getAt(p);
			    rec.data.tad_CodCompon1=pRec.data.txt1;
			    rec.data.tad_CodCompon2=pRec.data.txt2;
			    rec.data.tad_CodCompon3=pRec.data.txt3;
			    rec.data.tad_CodCompon4=pRec.data.txt4;
});
    li.em.detgrid.store.on("load", function(){
        Ext.getCmp("btnAdd").enable();
    	fValidarSemana();
    })

    alPrimKeys= [];
    alPrimKeys["tad_CodEmpresa"] = 1;
    alPrimKeys["tad_NumTarja"] = 1;
    alPrimKeys["tad_Secuencia"] = 1;
    var ilReg = Ext.getCmp("frmMant").form.findField("tar_NumTarja").getValue();
    li.em.detgrid.getStore().load({init:22, params:{meta:true, tac_CodEmpresa:0, tad_NumTarja:ilReg}}); 
});


function fMove(pDir){
    var olGrd = Ext.getCmp("gridDetalle");
    if (olGrd) var olSel = olGrd.getSelectionModel();
}

function fGrabar1() {
    var olGrd = Ext.getCmp("gridDetalle");
    olGrd.stopEditing();
    var olRst = olGrd.store.getModifiedRecords();   
    var olCm=olGrd.getColumnModel();
    var slPar = "";
    var ilCnt = 0;
    var alPar = [];
    var pItm=null;
    var m;
    var pFld;

    for (var pIdx=0; pIdx < olRst.length; pIdx++ ){
        pItm=olRst[pIdx]; 
        ilCnt++;
        var alR = [];
        for (var i=0; i< pItm.fields.keys.length; i++){
            pFld =pItm.fields.keys[i] ;
            pI = i;
            m= pItm.modified[pFld];
            
            if((typeof(pItm.modified[pFld]) != "undefined" ||   // it is in modified fields
                (alPrimKeys[pFld]) ||                           // it is in PK
                (typeof( pItm.data._newFlag) != "undefined" && pItm.data._newFlag == true)  )) {
                slPar +=  "&" + pFld + "[" + pIdx + "]=" + olRst[pIdx].data[pFld]; // pasa solo los modificados o claves
                alR[pFld] = olRst[pIdx].data[pFld];
                ilColIdx  = olCm.findColumnIndex(pFld)
                if (ilColIdx >= 0) { // There is a Clumn for that dataIndex
                    olEdi = olCm.getCellEditor(ilColIdx,1);
                    if(typeof(olEdi) == "object"  && typeof(olEdi.field) == "object" && typeof(olEdi.field.hiddenName) == "string") { // INclir el campo oculto de los combos
                        pFld=olEdi.field.hiddenName;
                        slPar +=  "&" + pFld + "[" + pIdx + "]=" + olRst[pIdx].data[pFld]; 
                        alR[pFld] = olRst[pIdx].data[pFld];
                    } 
                }
            }
        }
        alPar[alPar.length] = alR;
    }
    a=[];
    a['uno']=[];
    a['uno'][1]= "un-1";
    a['uno'][2]= "un-2";
    a['dos']=20;
    a['tres']=30;
    ex=Ext.encode(a);
    slPar = 'LiEmTj_detallecrud.php?cnt=' + ilCnt + slPar;
    Ext.Ajax.request({
        url: slPar
        ,method:   "GET"
	,disableCaching:true
        ,success: function(pResp, pOpt){
            eval("var olResp=" + pResp.responseText)
            if (!olResp.success){
                alert ("No se grabaron todos los registros");
                }
	    }
        ,failure: function(pResp, pOpt){
                alert("No se aplico el proceso de Grabacion");
            }
        });
}

function fAgregar()
{
    var olGrd = Ext.getCmp("gridDetalle");
    olGrd.stopEditing();
    var i=0;
    var p=olGrd.store.data.length;
    var ilSec= p>0 ? olGrd.getStore().getAt(p-1).get("tad_Secuencia") : 1;
    var n=5
    var alRecs=[];
    for (i=1; i<=n; i++) {
        var r=new olRec({
        _newFlag:true
		,tad_CodEmpresa:"0"
        ,tad_NumTarja:Ext.getCmp("tar_NumTarja").getValue()
        ,tad_Secuencia:ilSec+i
        ,tad_CodProducto:0
        ,tad_CodEmpacador:''
        ,tad_CodMarca:0
        ,tad_CodCaja:0
        ,tad_CantDespachada:0
        ,tad_CantRecibida:0
        ,tad_CantRechazada:0
        ,tad_CantCaidas:0
        ,tad_CodCompon1:0
        ,tad_CodCompon2:0
        ,tad_CodCompon3:0
        ,tad_CodCompon4:0
        ,tad_ValUnitario	:0
        ,tad_DifUnitario:0
        ,tad_LisPrecio:0
        ,tad_LiqProceso:0
        ,tad_LiqNumero:0
        ,tad_Observaciones:''
        ,tad_precpactado:0
        ,tad_indFlete:0
        ,tad_Calidad:0
        ,tad_Peso:0
        ,tad_Largo:0
        ,tad_NumDedos:0
        ,tad_ClusCaja:0
        ,txp_CajDescrip:''
        ,txp_Marca:''
        ,txp_producto:''
        ,txp_productor:''
     });
     alRecs.push(r);
    }
    olGrd.store.insert(p, alRecs);
    olGrd.startEditing(li.em.detstore.data.length -n, 1)
}

function fEliminar() {
    var olGrd = Ext.getCmp("gridDetalle");
    var olRst = olGrd.getSelections();
    var slPar = "";
    var ilCnt = 0;
    var alPar = [];
    Ext.each(olRst, function(pItm, pIdx, pArr){
        ilCnt++;
        var alR = [];
        Ext.each(pItm.fields.keys, function(pFld, pI, pA){
            if(alPrimKeys[pFld]){
                slPar +=  "&" + pFld + "[" + pIdx + "]=" + olRst[pIdx].data[pFld];
                alR[pFld] = olRst[pIdx].data[pFld];
            }
        })
        alPar[alPar.length] = alR;
    })
    slPar = 'LiEmTj_detallecrud.php?pAction=DEL&cnt=' + ilCnt + slPar;
    Ext.Ajax.request({
        url: slPar
        ,method:   "GET"
        ,success: function(pResp, pOpt){
            eval("var olResp=" + pResp.responseText)
            if (!olResp.success){
                alert ("No se eliminarion todos los registros")
                }
            olGrd.store.load();
            }
        ,failure: function(pResp, pOpt){
                alert("No se aplico el proceso de Eliminacion")
            }
        });
}

function fReader(){
    return new Ext.data.JsonReader({fields:[fCampos()]})
}

function fCampos(){
    return [
     {name: 'newFlag',        type:'int'}
	,{name: 'tad_CodEmpresa',  type:'int'}
	,{name: 'tad_NumTarja',  type:'float'}
	,{name: 'tad_Secuencia',  type:'int'}
	,{name: 'tad_CodProducto',  type:'int'}
	,{name: 'tad_CodEmpacador', type:'int'}
	,{name: 'tad_CodMarca',  type:'int'}
	,{name: 'tad_CodCaja',  type:'int'}
	,{name: 'tad_CantDespachada',  type:'int'}
	,{name: 'tad_CantRecibida',  type:'int'}
	,{name: 'tad_CantRechazada',  type:'int'}
	,{name: 'tad_CantCaidas',  type:'integer'}
	,{name: 'tad_CodCompon1',  type:'int'}
	,{name: 'tad_CodCompon2',  type:'int'}
	,{name: 'tad_CodCompon3',  type:'int'}
	,{name: 'tad_CodCompon4',  type:'int'}
	,{name: 'tad_ValUnitario',  type:'float'}
	,{name: 'tad_DifUnitario',  type:'float'}
	,{name: 'tad_LisPrecio', type:'float'}
	,{name: 'tad_LiqProceso	',  type:'float'}
	,{name: 'tad_LiqNumero', type:'int'}
	,{name: 'tad_Observaciones',  type:'string'}
	,{name: 'tad_precpactado',  type:'int'}
	,{name: 'tad_indFlete', type:'int'}
        ,{name: 'tad_NumDedos', type:'int'}
	,{name: 'tad_ClusCaja', type:'int'}
	,{name: 'tad_Calidad', type:'int'}
	,{name: 'tad_Largo', type:'int'}
	,{name: 'tad_Dedos', type:'int'}
	,{name: 'txp_CajDescrip',  type:'string'}
	,{name: 'txp_Marca',  type:'string'}
	,{name: 'txp_Productor',  type:'string'}
	,{name: 'txp_Producto',  type:'string'}
    ,{name: 'txp_carton',  type:'string'}
    ,{name: 'txp_plastico',  type:'string'}
    ,{name: 'txp_material',  type:'string'}
    ,{name: 'txp_etiq',  type:'string'}
 ]
}

/************************************/
/* Funcion para Validar el max y min
   del cambio enviado por parametro
   29/03/10			                */
/************************************/
function fValidarCampos(pNumCampo,pCampo,pValor)
{
	olGrd = Ext.getCmp("gridDetalle");
	var p= olGrd.getSelectionModel().selection.cell[0];
	var rec = olGrd.getStore().getAt(p);
    var max = Validaciones[pCampo]['max'];
    var min = Validaciones[pCampo]['min'];
	if(pValor >= min && max >= pValor)
    {
		rec.set(pCampo,pValor);
    	return true;
    }
    else	
    {
    	//debugger;
    	rec.set(pCampo,0);
    	pValor=0;
		alert("Error: Dato ingresado fuera del rango Max: "+max+"/ Min: "+min);
		return true;
    }
}


