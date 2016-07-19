/*
 * AAA 
 * Copyright(c) 2006-2008, Fausto Astudillo
 * 
 * @packgage    Operaciones
 * @subpackage  Precios
 * Genera un grid con detalles de embarque de un productor específico. Recibe parametros:
 * @param   pEmb    int ID de Embarque
 * @param   pIte    int ID de producto
 * @param   pMar    int ID de Marca
 */

// setup an App namespace
// This is done to prevent collisions in the global namespace
Ext.ns('Ope', 'Ope.Pre');

/**
 * Ope.stDeta
 * @extends Ext.data.Store
 * @cfg {String} url This will be a url of a location to load the store
 * Store especializado que maneja detalles de precios.
 * Utiliza formato jsom
 */
Ope.Pre.stDeta = function(config) {
	var config = config || {};
	Ext.applyIf(config, {
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'txp_Semana', type: 'int'},
                {name: 'txp_RefOperativa', type: 'int'},
                {name: 'txp_Embarcador', type: 'int'},
                {name: 'txp_CodProducto', type: 'int'},
                {name: 'txp_CodMarca', type: 'int'},
                {name: 'txp_Marca', type: 'string'},                
                {name: 'tmp_Cantidad', type: 'int'},
                {name: 'tmp_PrecUnit', type:'float'},
                {name: 'tmp_Valor', type:'float'}
            ]
        })
	});
	Ope.Pre.stDeta.superclass.constructor.call(this, config);
};
Ext.extend(Ope.Pre.stDeta, Ext.data.Store);



/**
 * Ope.Pre.DetGrid
 * @extends Ext.grid.GridPanel
 * Presenta una grilla con detalle de precios por marca de productores
 * 
 * It follows a very custom pattern used only when extending Ext.Components
 * in which you can omit the constructor.
 * 
 * It also registers the class with the Component Manager with an xtype of
 * bookgrid. This allows the application to take care of the lazy-instatiation
 * facilities provided in Ext 2.0's Component Model.
 */
Ope.Pre.detGrid = Ext.extend(Ext.grid.GridPanel, {
	// override 
	initComponent : function() {
		Ext.apply(this, {
	        columns: [{
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
                width: 30,
                dataIndex: 'txp_Productor'}
            ,{
                id:"txp_CatProducto",
                header: "TIPO FRUTA",
                width: 25,
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
                dataIndex: 'txp_Producto'}
            ,{
                id:"tmp_Cantidad",
                header: "CANT",
                width: 15,
                align:'right',
                summaryType:'sum',
                renderer: fRendQuantity,
                summaryRenderer: fRendQuantity,                
                dataIndex: 'tmp_Cantidad'}
            ,{
                id:"tmp_Valor",
                header: "VALOR",
                width: 20,
                align:'right',
                summaryType:'totalCost',
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
                sortable: false,
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
        ],
			sm: new Ext.grid.RowSelectionModel({singleSelect: true}),
			store: new Ope.Pre.stDeta({
				storeId: 'stPrecDeta',
				url: '../Ope_Files/OpTrTr_precioscaptura.php'
			}),
			viewConfig: {
				forceFit: true
			}
		});
		Ope.Pre.detGrid.superclass.initComponent.call(this);		
	}
});
Ext.reg('detGrid', Ope.Pre.detGrid);


/**
 * Ope.pre.detPrecios
 * @extends Ext.Panel
 * Panel especializado quemaneja detalles de precios a productores
  * 
 * This demonstrates adding 2 custom properties (tplMarkup and 
 * startingMarkup) to the class. It also overrides the initComponent
 * method and adds a new method called updateDetail.
 * 
 * The class will be registered with an xtype of 'bookdetail'
 */
Ope.Pre.detPrecios = Ext.extend(Ext.Panel, {
	tplMarkup: [
		'Productor: {txp_Productor}<br/>',
		'Detalle de Marcas'
	],
	startingMarkup: 'Por favor seleccione una fila para ver los detalles',
	initComponent: function() {
		this.tpl = new Ext.Template(this.tplMarkup);
		Ext.apply(this, {
			bodyStyle: {
				background: '#ffffff',
				padding: '7px'
			},
			html: this.startingMarkup
		});
		Ope.Pre.detPrecios.superclass.initComponent.call(this);
	},
	updateDetail: function(data) {
		this.tpl.overwrite(this.body, data);		
	}
});
Ext.reg('detPrecios', Ope.Pre.detPrecios);


/**
 * Ope.Pre.pnlPrecios
 * @extends Ext.Panel
 * 
 * Panel especializado, compuesto de un grilla de precios y un panel con detalle por marca o dias
 * Proporciona el enlace entre los doscomponentes para permitir intercomunicarse entre si
 */
Ope.Pre.pnlPrecios = Ext.extend(Ext.Panel, {
	initComponent: function() {
		// used applyIf rather than apply so user could
		// override the defaults
		Ext.applyIf(this, {
			frame: true,
			title: 'Precios por Productor',
			width: 540,
			height: 400,
			layout: 'border',
			items: [{
				xtype: 'detGrid',
				itemId: 'detalleGrid',
				region: 'north',
				height: 210,
				split: true
			},{
				xtype: 'detPrecios',
				itemId: 'detallePrecios',
				region: 'center'
			}]			
		})
		Ope.Pre.pnlPrecios.superclass.initComponent.call(this);
	},
	initEvents: function() {
		// call the superclass's initEvents implementation
		Ope.Pre.pnlPrecios.superclass.initEvents.call(this);
		// now add application specific events
		// notice we use the selectionmodel's rowselect event rather
		// than a click event from the grid to provide key navigation
		// as well as mouse navigation
		var preGridSm = this.getComponent('detalleGrid').getSelectionModel();		
		preGridSm.on('rowselect', this.onRowSelect, this);		
	},
	// add a method called onRowSelect
	// This matches the method signature as defined by the 'rowselect'
	// event defined in Ext.grid.RowSelectionModel
	onRowSelect: function(sm, rowIdx, r) {
		// getComponent will retrieve itemId's or id's. Note that itemId's 
		// are scoped locally to this instance of a component to avoid
		// conflicts with the ComponentMgr
		var detailPanel = this.getComponent('detallePrecios');
		detailPanel.updateDetail(r.data);
	}
});
// register an xtype with this class
Ext.reg('pnlPrecios', Ope.Pre.pnlPrecios);

Ext.onReady(function() {
	var preApp = new Ope.Pre.pnlPrecios({
		renderTo: document.body //'binding-example'
	});
	Ext.StoreMgr.get('stPrecios').load();
});