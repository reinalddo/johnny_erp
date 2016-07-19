/*
 * Ext JS Library 2.0.1
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */
Ext.ns('Ope', 'Ope.pre');
Ext.BLANK_IMAGE_URL = "../LibJava/ext-2.0/resources/images/default/s.gif"
xg=Ext.grid;
Ext.onReady(function(){
    Ext.QuickTips.init();
    var xg = Ext.grid;
    var reader = new Ext.data.JsonReader({
        fields: [
            {name: 'txp_Semana', type: 'int'},
            {name: 'txp_RefOperativa', type: 'int'},
            {name: 'txp_NombBuque', type: 'string'},
            {name: 'txp_Embarcador', type: 'int'},
            {name: 'txp_Productor', type: 'int'},
            {name: 'txp_CatProducto', type: 'string'},
            {name: 'txp_CodProducto', type: 'int'},
            {name: 'txp_Producto', type: 'string'},
            {name: 'tmp_Cantidad', type: 'int'},
            {name: 'tmp_PrecUnit', type:'float'},
            {name: 'tmp_Valor', type:'float'},
            {name: 'tmp_Flete', type:'bool'}
        ]
    });
    fInitExpander();
    fInitGridPrec();
    checkColumn = new Ext.grid.CheckColumn({
       id:"tmp_Flete",
       header: "F",
       dataIndex: 'tmp_Flete',
       width: 15
    });
    // define a custom summary function
    Ext.grid.GroupSummary.Calculations['totalCost'] = function(v, record, field){
        //record.data.tmp_Valor = record.data.tmp_Cantidad *record.data.tmp_PrecUnit;
        return v + (record.data.tmp_Cantidad * record.data.tmp_PrecUnit);
    }    
    summary = new Ext.grid.GroupSummary();
    gridSummary = new Ext.ux.grid.GridSummary();
/*    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',  dataIndex: 'txp_NomBuque'},
        {type: 'string',  dataIndex: 'txp_CatProducto'},
        {type: 'string',  dataIndex: 'txp_Producto'},
        {type: 'string',  dataIndex: 'txp_Productor'}
    ]});
*/  
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        //sorInfoX : {field:'txp_CatProducto', direction: 'ASC'},
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
        url: 'OpTrTr_precioscaptura.php',
        baseParams: {pEmb:getFromurl("pEmb"), pAnio:getFromurl("pAnio"), pSem:getFromurl("pSem"), meta: true, start:0, limit:100, sort:'txp_CatPoducto', dir:'ASC'},
        reader: reader,
        groupField: 'txp_Producto',        
        sortInfo: {field: 'txp_Productor', direction: 'ASC'},
        sortInfoX: {field: 'txp_Productor', direction: 'ASC'},
        pruneModifiedRecords: true,
        groupOnSort:false ,
        remoteSort: false
    });
    goBbar= new Ext.PagingToolbar({
            pageSize: 100
            ,store: store
            ,displayInfo: true
            ,displayMsg: 'Registros {0} - {1} de {2}'
            ,emptyMsg: "No hay datos que presentar"
    })
    var grid = new xg.EditorGridPanel({
        ds: store
        ,id: 'gridPrecProd'
        , columns: [ new Ext.grid.RowNumberer()
            ,expander
            ,{
                id: 'txp_Semana',
                header: "SEM",
                width: 7,
                sortable: true,
                dataIndex: 'txp_Semana',
                hideable: false}
            ,{
                header: "EMB",
                width: 7,
                hidden: true,
                dataIndex: 'txp_RefOperativa'}
            ,{
                id:"txp_NombBuque",
                header: "VAPOR",
                sortable: true,
                width: 20,
                dataIndex: 'txp_NombBuque'}
            ,{
                id:"txp_Embarcador",
                header: "COD",
                width: 8,
                dataIndex: 'txp_Embarcador'}
            ,{
                id:"txp_Productor",
                header: "PRODUCTOR",
                sortable: true,
                width: 40,
                dataIndex: 'txp_Productor'}
            ,{
                id:"txp_CatProducto",
                header: "FRUTA",
                sortable: true,                
                width: 20,
                dataIndex: 'txp_CatProducto'}
            ,{
                id:"txp_CodProducto",
                header: "COD",
                width: 10,
                dataIndex: 'txp_CodProducto'}
            ,{
                id:"txp_Producto",
                header: "PRODUCTO",
                width: 18,
                sortable: true,                
                dataIndex: 'txp_Producto'}
            ,{
                id:"tmp_Cantidad",
                header: "CANT",
                width: 15,
                align:'right',
                summaryType:'sum',
                sortable: true,
                renderer: fRendQuantity,
                summaryRenderer: fRendQuantity,                
                dataIndex: 'tmp_Cantidad'}
            ,{
                id:"tmp_Valor",
                header: "VALOR",
                width: 20,
                align:'right',
                summaryType:'sum',
                renderer: function(v, params, record){
                    record.data.tmp_Valor= record.data.tmp_Cantidad * record.data.tmp_PrecUnit
                    return Ext.util.Format.usMoney(record.data.tmp_Valor);
                },
                summaryRenderer: fRendQuantity,  
                dataIndex: 'tmp_Valor',
                style: 'text-align:right'}
            ,{
                id:"tmp_PrecUnit",
                header: "P.UNIT",
                width: 15,
                align:'right',
                sortable: true,
                groupable: false,
                dataIndex: 'tmp_PrecUnit',
                allowDecimals: true,
                renderer: fRendQtty4,                
                editor: new Ext.form.NumberField({
                   allowBlank: false,
                   allowNegative: false,
                   allowDecimals: true,
                   decimalPrecision: 4,
                   selectOnFocus:true,
                   style: 'text-align:right',
                   stateEvents:[{change:{scope:this, fn:fModifPrecio}}]
                })  }
            , checkColumn
        ]
        ,view: new Ext.grid.GroupingView({
            forceFit:true,
            showGroupName: false,
            enableNoGroups:false, // REQUIRED!
            hideGroupedColumn: true
        })
        ,plugins: [summary, checkColumn, expander, gridSummary]
        ,bbar: goBbar
        ,view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Detalles" : "Detalle"]})'
        })
        ,autoExpandColumn: 'txp_Productor'
        ,frame:true
        ,autowidth:true
        ,height: 660
        ,clicksToEdit: 1
        ,loadMask: true
        ,collapsible: true
        ,animCollapse: false
        ,trackMouseOver: false
        //enableColumnMove: false,
        ,iconCls: 'icon-grid'
        ,renderTo: document.body
    });
    grid.getView().on('refresh', function(){
	    Ext.each(grid.view.getRows(),function(row)
	    {
		record = grid.store.getAt(row.rowIndex);
		if (expander.state[record.id])
		    {
			expander.expandRow(row);
		    }
	    });//each
    });

    //grid.render(document.body)
    /*grid.store.on("beforeload", function(){
        if(this.lastOptions)
            this.params=this.lastOptions.params ;
        x=1
    })*/
    //grid.store.load({params: {pEmb:getFromurl("pEmb"), pAnio:getFromurl("pAnio"), pSem:getFromurl("pSem"), meta: true, start:0, limit:100, sort:'txp_CatPoducto', dir:'ASC'}});
    grid.store.load();
});

function fModifPrecio(field, new_value, old_value, gridID) {
        var olSm =Ext.getCmp(gridID||"gridPrecProd").getSelectionModel();
        if(!olSm.hasSelection()) return;
        if (olSm.selections)
             olRec=olSm.selections.first().data;
        else olRec=olSm.selection.record.data;
        var olParam= {
            id : "OpTrTr_panelop_aplic",
            pSeman : getFromurl("pSem"),
            pAnio  : getFromurl("pAnio"),
            pRope  : getFromurl("pEmb"),  // Requiere Rope en lugar de Emb
            pNvoPr : new_value,
            pProd  : olRec.txp_Embarcador,
            pPrdc  : olRec.txp_CodProducto,
            pMarc  : olRec.txp_CodMarca || false,
            pEmpq  : olRec.txp_CodCaja || false,
            pTarj  : olRec.txp_NumTarja || false
        }
        Ext.Ajax.request({
            url: 'OpTrTr_aplicaprec.php',
            success: function(pResp, pOpt){
                //olRec=Ext.getCmp(gridID || "gridPrecProd").getSelectionModel().selections.first().data;
                olRec.tmp_Valor = olRec.tmp_Cantidad * olRec.tmp_PrecUnit
            },
            failure: function(pResp, pOpt){
                this.setValue(old_value)
            },
            headers: {
               'my-header': 'foo'
            },
            params: olParam,
            scope:  field
        });
    }
 

Ext.grid.CheckColumn = function(config){
    Ext.apply(this, config);
    if(!this.id){
        this.id = Ext.id();
}
    this.renderer = this.renderer.createDelegate(this);
    };
    Ext.grid.CheckColumn.prototype ={
    init : function(grid){
        this.grid = grid;
        this.grid.on('render', function(){
            var view = this.grid.getView();
            view.mainBody.on('mousedown', this.onMouseDown, this);
        }, this);
    },
    onMouseDown : function(e, t){
        if(t.className && t.className.indexOf('x-grid3-cc-'+this.id) != -1){
            e.stopEvent();
            var index = this.grid.getView().findRowIndex(t);
            var record = this.grid.store.getAt(index);
            record.set(this.dataIndex, !record.data[this.dataIndex]);
            fModifFlete(this, record.data[this.dataIndex], record) // @fah
        }
    },
    renderer : function(v, p, record){
        p.css += ' x-grid3-check-col-td'; 
        return '<div class="x-grid3-check-col'+(v?'-on':'')+' x-grid3-cc-'+this.id+'">&#160;</div>';
    }
};

function fModifFlete(field, new_value, olRec) {
        var olParam= {
            id : "opPrecios_flete",
            pSeman : getFromurl("pSem"),
            pAnio  : getFromurl("pAnio"),
            pEmb   : getFromurl("pEmb"), // requiere Emb 
            pFlete : new_value,
            pCant  : new_value? olRec.data.tmp_Cantidad : 0,
            pProd  : olRec.data.txp_Embarcador,
            pPrdc  : olRec.data.txp_CodProducto,
            pMarc  : olRec.data.txp_CodMarca || false,
            pEmpq  : olRec.data.txp_CodCaja || false,
            pTarj  : olRec.data.txp_NumTarja || false,
            pFech  : olRec.data.txp_Fecha    || false
        }
        old_value = !new_value;
        Ext.Ajax.request({
            //url: 'OpTrTr_aplicaflete.php',
            url: 'OpTrTr_aplicaflet2.php',
            success: function(pResp, pOpt){
            },
            failure: function(pResp, pOpt){
                this.setValue(old_value)
            },
            headers: {
               'my-header': 'foo'
            },
            params: olParam,
            scope:  field
        });
    }
function fVerDetalles(gridId, pOpc, rowId, rowNum){
    var olRec=Ext.getCmp("gridPrecProd").store.getAt(rowNum).data
	olParam= {
	    id : "opPrecios"
	    ,pSeman : getFromurl("pSem")
	    ,pAnio  : getFromurl("pAnio")
	    ,pEmb   : getFromurl("pEmb")
	    ,pProd  : olRec.txp_Embarcador
	    ,pPrdc  : olRec.txp_CodProducto
	    ,meta: true
	    ,start:0
	    ,limit:100
	    ,sort:'txp_CatPoducto'
	    ,dir:'ASC'
        ,op:  pOpc || 'mar'
	    }
    xg.strPrecios2 = new Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
            pruneModifiedRecords: true
            ,groupOnSort:false 
            ,remoteSort: false
            ,initComponent:function(config) {
                this.sortInfoX= this.sortInfo;  // @fah: para asegurar sortinfoal iniciar ds
                Ext.apply(this, config); // e/o apply
                xg.storeX.superclass.initComponent.apply(this, arguments);
             } // e/o function initComponent
            ,onLoad:function() {
                xg.storeX.superclass.onLoad.apply(this, arguments);
            }        
            ,applySort: function(){
                Ext.data.GroupingStore.superclass.applySort.call(this);
                if (!this.sortInfo) this.sortInfo = this.sortInfoX;
                var gs = this.getGroupState();
                if(!this.sortInfo || (gs && gs != this.sortInfo.field)){
                    this.sortData(this.groupField);
                }
            }
        });
	var strGrd2 = new xg.strPrecios2({
	    id: "strMar"
	    ,url: 'OpTrTr_precioscaptura.php?'
	    ,reader: new Ext.data.JsonReader(fCampos2())
	    ,sortInfo: {field: 'txp_Productor', direction: 'ASC'}
	    ,groupField: 'txp_Marca'
	    ,baseParams: {
		pEmb:getFromurl("pEmb")
		,pAnio:getFromurl("pAnio")
		,pSem:getFromurl("pSem")
		,meta: true
		,start:0
		,limit:100
                }
        })

	var grd2class = Ext.extend(xg.EditorGridPanel, {
	    border:false
        //,plugins: [summary, checkColumn, expander]        
	    ,plugins: [summary, checkColumn, gridSummary]
	    ,frame:true
	    ,autowidth:true
	    ,clicksToEdit: 1
	    ,loadMask: true
	    ,collapsible: true
	    ,animCollapse: false
	    ,trackMouseOver: false
	    ,iconCls: 'icon-grid'
	    ,initComponent:function(config) {
		Ext.apply(this, config); // e/o apply
		grd2class.superclass.initComponent.apply(this, arguments);
	    } // e/o function initComponent
	    ,onRender:function() {
            var gridId=this.id;
            var olCm=Ext.getCmp(this.id).getColumnModel();
            olCm.getColumnById("tmp_PrecUnit").editor.on(
                    "complete", function(fld, oVal, nVal){fModifPrecio(fld, oVal, nVal, gridId);})
            xg.grdPrecios.superclass.onRender.apply(this, arguments);
	    } // e/o function onRender
	}); // eo extend

	Ext.reg('grdPrecios2', grd2class);
    grd2 = new grd2class({
        //id: "gridPrecMar"
        id: gridId
	    ,cm: new Ext.grid.ColumnModel(fColumns2())
	    ,sm: new Ext.grid.CellSelectionModel({singleSelect:true})        
	    ,ds: strGrd2
        ,width:720
        ,defaults: {width:25}
	    ,height: 200
        //,autoheight: true
        ,plugins: [/*summary, gridSummary*/]  // @TODO: habilitar gridrsumm
	    ,forceFit:true
        ,shadow:true
        ,bbar: new Ext.PagingToolbar({
            pageSize: 100
            ,store: strGrd2
            ,displayInfo: true
            ,displayMsg: 'Registros {0} - {1} de {2}'
            ,emptyMsg: "No hay datos que presentar"
            ,items:[
                '-'
                , {
                pressed: false
                ,enableToggle:false
                ,text: 'Marcas'
                ,tootTip:"Cantidades embarcadas por Marca"
                ,cls: 'x-btn-text-icon details' 
                ,toggleHandler: fRecargaDetalles.createDelegate(this, ["mar",rowNum] , true)
                }, {
                pressed: false
                ,enableToggle:true
                ,text: 'Dias'
                ,tootTip:"Cantidades embarcadas por Dia, Marca y Tipo de Caja"
                ,cls: 'x-btn-text-icon details' 
                ,toggleHandler: fRecargaDetalles.createDelegate(this, ["dia",rowNum], true)
                }, {
                pressed: false
                ,enableToggle:true
                ,text: 'Tarjas'
                ,cls: 'x-btn-text-icon details' 
                ,toggleHandler: fRecargaDetalles.createDelegate(this, ["tar",rowNum], true)
            },{
                pressed: false
                ,enableToggle:true
                ,text: 'CERRAR'
                ,cls: 'x-btn-text-icon details' 
                ,toggleHandler: fCerrarGrid.createDelegate(this, [gridId], true)
            }]
        })
        ,view: new Ext.grid.GroupingView({
            forceFit:true
            ,showGroupName: false
            ,enableNoGroups:false 
            ,hideGroupedColumn: true
            ,groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "detalles" : "detalle"]})'
            })
	    ,renderTo: rowId
	})
/**/
	grd2.render(rowId);
    grd2.getEl().swallowEvent([ 'mouseover','mouseout', 'mousedown', 'keydown', 'click', 'dblclick' ]);
    grd2.on("cellclick", function(grid, rowIndex, columnIndex, e) {
        var slField = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
        if (slField == "tmp_Flete"){
            var olRec = grid.getStore().getAt(rowIndex);  // Get the Record
            olRec.set(slField, !olRec.data[slField]);
            var slVal = olRec.get(slField);
            fModifFlete(null, slVal, olRec) // @fah
        }
    })

    grd2.store.on("metachange", fRedrawGrid.createDelegate(grd2));
	grd2.store.load({params: olParam});

    }

function fInitExpander(){
    expander = new xg.RowExpander({
        tpl : new Ext.Template('<div style="padding-left:42px" id="r-{txp_Embarcador}-{txp_CodProducto}" ></div>')
        ,enableCaching:false
    });
    expander.on('expand', expandedRow);
    function expandedRow(obj, record, body, rowIndex){
        id = "r-" + record.data.txp_Embarcador + "-" + record.data.txp_CodProducto
        id2 = "g-" + rowIndex
        this.id=id;
        fVerDetalles(id2, "mar", id, rowIndex)
    }	 
}
function fCampos(){
    return [
        {name: 'txp_Semana', type: 'int'},
        {name: 'txp_RefOperativa', type: 'int'},
        {name: 'txp_NombBuque', type: 'string'},
        {name: 'txp_Embarcador', type: 'int'},
        {name: 'txp_Productor', type: 'int'},
        {name: 'txp_CatProducto', type: 'string'},
        {name: 'txp_CodProducto', type: 'int'},
        {name: 'txp_Producto', type: 'string'},
        {name: 'tmp_Cantidad', type: 'int'},
        {name: 'tmp_PrecUnit', type:'float'},
        {name: 'tmp_Valor', type:'float'},
        {name: 'tmp_Flete', type:'bool'}
	    ];
    }

function fCampos2(){
    return [
        {name: 'txp_Semana', type: 'int'},
        {name: 'txp_RefOperativa', type: 'int'},
        {name: 'txp_Embarcador', type: 'int'},
        {name: 'txp_CodProducto', type: 'int'},
        {name: 'txp_CodMarca', type: 'int'},
        {name: 'txp_Marca', type: 'string'},
        {name: 'txp_CodCaja', type: 'int'},
        {name: 'txp_CajDescrip', type: 'string'},
        {name: 'txp_Fecha',  type: 'date', dateFormat:"Y-m-d"}, //type:"string"},
        {name: 'txp_NumTarja', type: 'int'},
        {name: 'tmp_Cantidad', type: 'int'},
        {name: 'tmp_PrecUnit', type:'float'},
        {name: 'tmp_Valor', type:'float'},
        {name: 'tmp_Flete', type:'bool'}
	    ];
    }
function fColumns2(){
	var checkColumn2 = new Ext.grid.CheckColumn({
	    id: 'tmp_Flete',
	    header: "F",
	    dataIndex: 'tmp_Flete',
	    width: 15
	});
	// define a custom summary function
	Ext.grid.GroupSummary.Calculations['totalCost'] = function(v, record, field){
	    return v + (record.data.tmp_Cantidad * record.data.tmp_PrecUnit);
	}    
        return [new Ext.grid.RowNumberer(),
	    /*expander,*/
	    {
		id: 'txp_Semana',
		header: "S",
		width: 7,
		sortable: true,
		hidden: true,                
		dataIndex: 'txp_Semana',
		hideable: false}
	    ,{
        id: 'txp_RefOperativa',
		header: "EMB",
		sortable: true,
        width: 7,
		hidden: true,
		dataIndex: 'txp_RefOperativa'}
	    ,{
		id:"txp_Embarcador",
		header: "COD",
		width: 8,
		hidden: true,                
		dataIndex: 'txp_Embarcador'}
	    ,{
		id:"txp_CodProducto",
		header: "COD",
		width: 10,
		hidden: true,
		dataIndex: 'txp_CodProducto'}
            ,{
		id:"txp_CodMarca",
		header: "COD",
		width: 10,
		hidden: true,
		dataIndex: 'txp_CodMarca'}
            ,{
		id:"txp_Marca",
		header: "MARCA",
		width: 40,
		hidden: false,
		dataIndex: 'txp_Marca'}
            ,{
		id:"txp_CodCaja",
		header: "COD",
		width: 10,
		hidden: true,
		dataIndex: 'txp_CodCaja'}
        ,{
		id:"txp_CajDescrip",
		header: "TIPO CAJA",
		width: 45,
		hidden: false,
                sortable: true,
		dataIndex: 'txp_CajDescrip'}                      
        ,{
		id:"txp_Fecha",
		header: "FECHA",
		width: 65,
		hidden: true,
                sortable: true,
                align: "left",
                format: "d/MM/y",
                dateFormat: "d/M/y",
		dataIndex: 'txp_Fecha'}                      
        ,{
		id:"txp_NumTarja",
		header: "TARJA #",
		width: 65,
		hidden: true,
        sortable: true,
		dataIndex: 'txp_NumTarja'}                      
	    ,{
		id:"tmp_Cantidad",
		header: "CANT",
		width: 30,
		align:'right',
        sortable: true,
		summaryType:'sum',
		renderer: fRendQuantity,
		summaryRenderer: fRendQuantity,                
		dataIndex: 'tmp_Cantidad'}
	    ,{
		id:"tmp_Valor",
		header: "VALOR",
		width: 30,
		align:'right',
                sortable: true,
		summaryType:'sum',
		renderer: function(v, params, record){
		    record.data.tmp_Valor= record.data.tmp_Cantidad * record.data.tmp_PrecUnit
		    return Ext.util.Format.usMoney(record.data.tmp_Valor);
		},
		summaryRenderer: fRendQuantity,  
		dataIndex: 'tmp_Valor',
		style: 'text-align:right'}
	    ,{
		id:"tmp_PrecUnit",
		header: "P.UNIT",
		width: 30,
		align:'right',
		sortable: false,
		groupable: false,
		dataIndex: 'tmp_PrecUnit',
		allowDecimals: true,
		renderer: fRendQtty4
		,editor: new Ext.form.NumberField({
		    allowBlank: false
		    ,allowNegative: false
		    ,allowDecimals: true
		    ,decimalPrecision: 2
		    ,style: 'text-align:right'
    		,selectOnFocus:true
                })
		//,completeOnEnter: true
		,cancelOnEsc:true
	    }, checkColumn2 
	];
    }
/*
* Inicializacion de estructuras de grid
*
*/
function fInitGridPrec(pPlugIns){
    xg.strPrecios = new Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        pruneModifiedRecords: true
        ,groupOnSort:false 
        ,remoteSort: false
        ,initComponent:function(config) {
            this.sortInfoX= this.sortInfo;  // @fah: para asegurar sortinfoal iniciar ds
            Ext.apply(this, config); // e/o apply
            xg.storeX.superclass.initComponent.apply(this, arguments);
         } // e/o function initComponent
        ,onLoad:function() {
            xg.storeX.superclass.onLoad.apply(this, arguments);
        }        
        ,applySort: function(){
            Ext.data.GroupingStore.superclass.applySort.call(this);
            if (!this.sortInfo) this.sortInfo = this.sortInfoX;
            var gs = this.getGroupState();
            if(!this.sortInfo || (gs && gs != this.sortInfo.field)){
                this.sortData(this.groupField);
            }
        }
    });

    	strProd = new xg.strPrecios({
	    id:"strProd"
	    ,url: 'OpTrTr_precioscaptura.php?op=pro'
	    ,reader: new Ext.data.JsonReader(fCampos())
	    ,sortInfo: {field: 'txp_Productor', direction: 'ASC'}
	    ,groupField: 'txp_Producto'
	    ,baseParams: {
		pEmb:getFromurl("pEmb")
		,pAnio:getFromurl("pAnio")
		,pSem:getFromurl("pSem")
		,meta: true
		,start:0
		,limit:100} }
	);
        
    	xg.grdPrecios = Ext.extend(xg.EditorGridPanel, {
	    border:false
	    ,plugins: pPlugIns
	    ,frame:true
	    ,autowidth:true
	    ,clicksToEdit: 1
	    ,loadMask: true
	    ,collapsible: true
	    ,animCollapse: false
	    ,trackMouseOver: false
	    ,iconCls: 'icon-grid'
	    ,initComponent:function(config) {
		Ext.apply(this, config); // e/o apply
		xg.grdPrecios.superclass.initComponent.apply(this, arguments);
	    } // e/o function initComponent
	     
	    ,onRender:function() {
		var gridId=this.id;
		switch(this.id){
		    case "gridPrecProd":
			this.getColumnModel().getColumnById("tmp_PrecUnit").editor.on(
			    "complete", function(fld, oVal, nVal){fModifPrecio(fld, oVal, nVal, "gridPrecProd");});
			break;
		    case "gridPrecMar":
			this.getColumnModel().getColumnById("tmp_PrecUnit").editor.on(
			    "complete", function(fld, oVal, nVal){
			    fModifPrecio(fld, oVal, nVal, "gridPrecMar");
			    }
			)
			break;
		    case "gridPrecTar":
			this.getColumnModel().getColumnById("tmp_PrecUnit").editor.on(
			    "complete", function(fld, oVal, nVal){
			    fModifPrecio(fld, oVal, nVal, "gridPrecTar");
			    }
			)
			break;  
		}
		//this.getColumnModel().getColumnById("tmp_PrecUnit").editor.completeOnEnter= true
		this.getColumnModel().getColumnById("tmp_PrecUnit").editor.cancelOnEsc=true
		this.getColumnModel().getColumnById("tmp_PrecUnit").editor.selectOnFocus=true
    
		//this.getColumnModel().getColumnById("tmp_PrecUnit").completeOnEnter= true
		this.getColumnModel().getColumnById("tmp_PrecUnit").cancelOnEsc=true
		this.getColumnModel().getColumnById("tmp_PrecUnit").selectOnFocus=true
		this.getColumnModel().getColumnById("tmp_PrecUnit").gridId=gridId;
		xg.grdPrecios.superclass.onRender.apply(this, arguments);
	    } // e/o function onRender
	}); // eo extend
	Ext.reg('grdPrecios', xg.grdPrecios);     
    }
function fPageToolBar(){
    return  new Ext.PagingToolbar({
	    pageSize: 100
	    ,store: strProd
	    ,displayInfo: true
	    //,plugins: summary //, filters
	    ,displayMsg: 'Registros {0} - {1} de {2}'
	    ,emptyMsg: "No hay datos que presentar"
	    ,items:[
		'-'
		, {
		enableToggle:true
		,pressed: true
        ,toggleGroup: "btnTipos"
		,text: 'Marcas'
		,tootTip:"Cantidades embarcadas por Marca."
		,cls: 'x-btn-text-icon details' 
		,handler: fVerMarcas
		}, {
		pressed: false
		,enableToggle:true
        ,toggleGroup: "btnTipos"
		,text: 'Dias'
		,tootTip:"Cantidades embarcadas por Dia, Marca y Tipo de Caja"
		,cls: 'x-btn-text-icon details' 
		,toggleHandler: fVerDias
		}, {
		pressed: false
		,enableToggle:true
        ,toggleGroup: "btnTipos"
		,text: 'Tarjas'
		,cls: 'x-btn-text-icon details' 
		,toggleHandler: fVerTarjas}
	    ]
	})
    }
function fRecargaDetalles(pBotn, pNN, pTipo, pRowNum){
        var olRec=Ext.getCmp("gridPrecProd").store.getAt(pRowNum).data
    	var olPars= {
	    id : "opPrecios"
	    ,pSeman : getFromurl("pSem")
	    ,pAnio  : getFromurl("pAnio")
	    ,pEmb   : getFromurl("pEmb")
	    ,pProd  : olRec.txp_Embarcador
	    ,pPrdc  : olRec.txp_CodProducto
	    ,meta: true
	    ,start:0
	    ,limit:100
	    ,sort:'txp_CatPoducto'
	    ,dir:'ASC'
	    }
        olPars.op=pTipo;
        Ext.getCmp("g-"+pRowNum).store.load({params: olPars});
    }

function fRedrawGrid(store, meta) {
        // loop for every field, only add fields with a header property (modified copy from ColumnModel constructor)
        var c;
        var config = [];
        var lookup = {};
        var cm = this.getColumnModel();
        for(var i = 0, len = meta.fields.length; i < len; i++)
        {
            mf=meta.fields[i];
            cm.getColumnById(mf.id).hidden = mf.hidden
        }   
        // Re-render grid
        if(this.rendered){
            this.view.refresh(true);
        } else this.render()
        if(!this.view.hmenu.items.keys.include("reset")) //fah 30/04/08  
            this.view.hmenu.add({id:"reset", text: "Reset Columns", cls: "xg-hmenu-reset-columns"});
    }
function fCerrarGrid(pBoton, pNN, pGrid){
    Ext.getCmp(pGrid).destroy()
}