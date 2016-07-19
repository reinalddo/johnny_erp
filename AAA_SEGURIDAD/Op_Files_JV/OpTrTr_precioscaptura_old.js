/*
*  proceso de Autogrid
*
*
*
**/
Ext.BLANK_IMAGE_URL = "/AAA/AAA_SEGURIDAD_2_2/LibJava/ext-2.0/resources/images/default/s.gif"
//alert("url:" + getFromurl('pUrl',""));    
Ext.onReady(function(){
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        sorInfoX : {field:'txp_CatProducto', direction: 'ASC'},
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
            url: (!sgLoadUrl)? 'OpTrTr_precioscaptura.php' : sgLoadUrl  // @fah 'OpTrTr_contenedores.php',
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id', start:0, limit:25},
            ['id']
        ),
        groupField: 'txp_CatProducto',        
        sortInfo: {field: 'txp_Productor', direction: 'ASC'},
        sortInfoX: {field: 'txp_Productor', direction: 'ASC'}, 
        groupOnSort:false ,
        remoteSort: false
    });
    store.on("beforeLoad", function(){
        this.baseParams = fParamObj();
        }
    )
    /*
    Ext.grid.GroupSummary.Calculations['Diferencia'] = function(v, record, field){
        return v + (record.data.cnt_CantDeclarada - record.data.vcp_CantNeta);
    }
    */
    var summary = new Ext.grid.GroupSummary();
    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',  dataIndex: 'txp_NomBuque'},
        {type: 'string',  dataIndex: 'txp_CatProducto'},
        {type: 'string',  dataIndex: 'txp_Producto'},
        {type: 'string',  dataIndex: 'txp_Productor'},
        {type: 'string',  dataIndex: 'txp_Marca'},
        {type: 'string',  dataIndex: 'txp_CajDescrip'}
    ]});

    name="Pre";
    grid1 = new Ext.ux.AutoGridPanel({
        title:'PRECIOS POR PRODUCTOR',
		plugins: [filters, summary],
        height: 300,
        width: 800,
        loadMask: true,
        stripeRows :true,
        autoSave: true,
        saveUrl: 'saveconfig.php',                
        store : store,
        bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: store,
            displayInfo: true,
            plugins: filters,
            displayMsg: 'Registros {0} - {1} de {2}',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-', {
                pressed: true,
                enableToggle:true,
                text: 'Detalles',
                cls: 'x-btn-text-icon details' ,
                toggleHandler: toggleDetails
            }]
        }),
        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? " " : " "]})',
            showGroupName: false,
            enableNoGroups:false /*, // REQUIRED!
            hideGroupedColumn: true            */
        }),
        listeners: {
            dblclick: function () {
                window.open("OpTrTr_contenedorfrm.php?cnt_ID=" + grid1.getSelections()[0].data.cnt_ID, "cnt", 'width=820, height=450, resizable=1, menubar=1');
            },
            destroy: function(c) {
                c.getStore().destroy();
            }
        }
        ,
        clicksToEdit: 1,
        collapsible: true,
        animCollapse: false,
        trackMouseOver: false,
        //enableColumnMove: false,
        iconCls: 'icon-grid',
        renderers: {tmp_PrecUnit: fRendPU}
    });

    // render grid
    /**/
    Ext.getCmp(gsObj).add(grid1);
    Ext.getCmp('panels').doLayout();
    this.grid1.render();
    this.grid1.store.load({params: {meta: true, start:0, limit:25, sort:'txt_Embarque', dir:'ASC'}});
})
function toggleDetails(btn, pressed){
    var view = grid1.getView();
    view.showPreview = pressed;
    view.refresh();
}
function fRenderGr(v, params, data){
   return ((v === 0 || v > 1) ? '(' + v +' Tasks)' : '(1 Task)');
   }
function fRendPU(v){
    if (v != 0){
        return "<span style='color:blue'>" + v + "</span>";
    }
}