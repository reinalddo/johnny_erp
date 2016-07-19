/*
*  proceso de Autogrid
*
*
*
**/
Ext.BLANK_IMAGE_URL = "/AAA/AAA_SEGURIDAD_2_2/LibJava/ext-2.0/resources/images/default/s.gif"
//alert("url:" + getFromurl('pUrl',""));    
Ext.onReady(function(){
//    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
//    var store= new Ext.data.Store({
        //proxy: new Ext.data.HttpProxy({
        //url: (!sgLoadUrl)? 'OpTrTr_contenedores.php' : sgLoadUrl /*getFromurl('pUrl',"")*/ /* @fah 'OpTrTr_contenedores.php'*/
        //}),
        //groupField: 'cnt_Embarque',
        //reader: new Ext.data.JsonReader(
            //{root: 'rows', id: 'id', start:0, limit:25},
            //['id']
        //)
    //});
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        sorInfoX : {field:'txt_Embarque', direction: 'ASC'},
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
    
    //Ext.extend(Ext.data.GroupingStore, storeX);
//    var store = new Ext.data.GroupingStore({
    var store = new storeX({
			proxy: new Ext.data.HttpProxy({
            method:"GET",
            url: (!sgLoadUrl)? 'OpTrTr_contenedores.php' : sgLoadUrl  // @fah 'OpTrTr_contenedores.php'
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id', start:0, limit:25},
            ['id']
        ),
        groupField: 'txt_Embarque',        
        sortInfo: {field: 'txt_Embarque', direction: 'ASC'},
        sortInfoX: {field: 'txt_Embarque', direction: 'ASC'}, 
        groupOnSort:false ,
        remoteSort: true
    });

    /*@TODO:                                                                  sumatoria de  grid */
	// custom summary renderer example
    /*   */
	function numContenedores(v, params, data) {
		return v? ((v === 0 || v > 1) ? '(' + v +' Contenedores)' : '(1 Contenedor)') : '';
	}
	function totalDeclarado(v, params, data) {
		return v?  v : '';
	}
	function totalTarjas(v, params, data) {
		return v? (v) : '';
	}
	function totalDiferencia(v, params, data) {
		return v? (v) : '';
	}
    var summary = new Ext.ux.grid.GridSummary();    
 
    /*
    Ext.grid.GroupSummary.Calculations['Diferencia'] = function(v, record, field){
        return v + (record.data.cnt_CantDeclarada - record.data.vcp_CantNeta);
    }
    */
    var groupSum = new Ext.grid.GroupSummary();
    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'numeric',  dataIndex: 'cnt_ID'},
        {type: 'string',  dataIndex: 'cnt_Serial'},
        {type: 'string',  dataIndex: 'txt_Naviera'},
        {type: 'string',  dataIndex: 'txt_Embarque'},
        {type: 'string',  dataIndex: 'txt_Consignatario'}]
        , autoreload:true
    });

    name="Cnt";
    grid1 = new Ext.ux.AutoGridPanel({
        title:'GRID',
		plugins: [filters, summary, groupSum],
        height: 300,
        width: 800,
        loadMask: true,
        stripeRows :true,
        autoSave: true,
        saveUrl: 'saveconfig.php',                
        store : store,
        tbar: [{
            text: 'Editar',
            tooltip: 'Presione  para Modificar datos ',
            id: 'icoEdit' + name,
            listeners: {
                click: function () {
                    window.open("OpTrTr_contenedorfrm.php?cnt_ID=" + grid1.getSelectionModel().selection.record.data.cnt_ID, "cnt", 'width=820, height=450, resizable=1, menubar=1');
                    }
                },
                iconCls:'add'
            }, '-', {
            text: 'Agregar',
            tooltip: 'Presione aqui para agregar un registro',
            id: 'icoAdd' + name,
            listeners: {
                click: function () {
                    window.open("OpTrTr_contenedorfrm.php?", "cntN", 'width=820, height=450, resizable=1, menubar=1');
                }},
                iconCls:'add'
            }, '-', {
            text: 'Eliminar',
            tooltip: 'Presione aqui para eliminar un registro',
            id: 'icoDelete' + name,
            listeners: {
                click: function () {
                    alert("No esta permitido eliminar")
                }
                },
                iconCls:'remove' 
            }, '->', // next fields will be aligned to the right 
            {
                text: 'Refrescar',
                tooltip: 'Presione aqui para refrescar los datos',
                handler: function () {
                    //Etb.defBdd.ds[name].reload();//
                    alert("ref");
                },
                iconCls:'refresh'
            }
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: store,
            displayInfo: true,
            plugins: [filters],
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
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Contenedores" : "Contenedor"]})'
        }),
        listeners: {
            dblclick: function () {
                    window.open("OpTrTr_contenedorfrm.php?cnt_ID=" + grid1.getSelectionModel().selection.record.data.cnt_ID, "cnt", 'width=820, height=450, resizable=1, menubar=1');
            },
            destroy: function(c) {
                c.getStore().destroy();
            }
        }
    });

    // render grid
    /**/
    Ext.getCmp(gsObj).add(grid1);
    Ext.getCmp('panels').doLayout();
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
