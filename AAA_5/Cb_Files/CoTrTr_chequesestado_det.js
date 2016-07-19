/*
*  proceso de Autogrid
*
*
*
**/
//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
//alert("url:" + getFromurl('pUrl',""));    
Ext.onReady(function(){
    //Ext.namespace("app", "app.cart");		     // Iniciar namespace cart
    Ext.namespace("app", "app.cheque");
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        sorInfoX : {field:'fecha', direction: 'DESC'},
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
            method:"GET",
            url: (!sgLoadUrl)? 'CoTrTr_chequesestado_det.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id', start:0, limit:9999},
            ['id']
        ),
        //groupField: 'ID',        
        sortInfo: {field: 'fecha', direction: 'DESC'},
        sortInfoX: {field: 'fecha', direction: 'DESC'}, 
        groupOnSort:false ,
        remoteSort: true
    });
    
   

    /*@TODO:                                                                  sumatoria de  grid */
	// custom summary renderer example
    /*   */
	function numContenedores(v, params, data) {
		return v? ((v === 0 || v > 1) ? '(' + v +' Contenedores)' : '(1 Contenedor)') : '';
	}
	function totalSaldo(v, params, data) {
		return v?  v : '';
	}
    var summary = new Ext.ux.grid.GridSummary();    
 
    /*
    Ext.grid.GroupSummary.Calculations['Diferencia'] = function(v, record, field){
        return v + (record.data.cnt_CantDeclarada - record.data.vcp_CantNeta);
    }
    */
    var groupSum = new Ext.grid.GroupSummary();
    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',  dataIndex: 'banco'},
        {type: 'numeric',  dataIndex: 'cheque'},
        {type: 'date',  dataIndex: 'fecha'},
        {type: 'numeric',  dataIndex: 'valor'},
        {type: 'string',  dataIndex: 'beneficiario'},
        {type: 'string',  dataIndex: 'concepto'},
        {type: 'string',  dataIndex: 'tipoComp'},
        {type: 'string',  dataIndex: 'observacion'},
        {type: 'date',  dataIndex: 'fecRegistro'},
        {type: 'string',  dataIndex: 'usuario'}]
        , autoreload:true
    });


    /*var pagingBar = new Ext.PagingToolbar({
        pageSize: 75,
        store: store,
        displayInfo: true,
        displayMsg: 'Registros {0} - {1} de {2}',
        emptyMsg: "No hay datos que presentar",
        
        items:[
            '-', {
                pressed: false,
                enableToggle:true,
                text: 'Imprimir',
                cls: 'x-btn-text-icon details' ,
		iconCls: 'iconImprimir',
                handler: basic_printGrid
            }
        ]
    });*/


    name="Cnt";
    app.cheque.gridDetCh = new Ext.ux.AutoGridPanel({
        title:'',
		//plugins: [filters],
        height: 210,
        width: 'auto',//800,
        cnfSelMode: 'rsms',  //CnfSelMode: propiedad para definir el tipo de selección de datos==> csm(CheckSelectionMode), csm(CellSelectionMode), rsms(RowSelectionMode Single), rsm(RowSelectionMode Multiple)
        loadMask: true,
        stripeRows :true,
        autoSave: true,
        saveUrl: 'saveconfig.php',                
        store : store,
        pageSize:75
        //,monitorResize:true
        ,renderTo: gsObj
        ,bbar: new Ext.PagingToolbar({
            pageSize: (this.pageSize? this.pageSize:75),
            store: store,
            displayInfo: true,
            plugins: [filters],
            displayMsg: 'Registros {0} - {1} de {2}',
            emptyMsg: "No hay datos que presentar"
            ,items:[
                '-',{
                pressed: false,
                enableToggle:true,
                text: 'Imprimir',
                cls: 'x-btn-text-icon details' ,
		iconCls: 'iconImprimir',
                handler: function(){basic_printGrid(app.cheque.gridDetCh);}
            }]
        })
        /*,view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Anexos" : "Anexos"]})'
        })*/
        ,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
            }
	    
        }
    });

    // render grid principal
    //Ext.getCmp(gsObj).add(app.CoRtTr.grid1);
   
    //Ext.getCmp('paneles').doLayout();
    //Ext.getCmp(gsObj).doLayout();
       
    app.cheque.gridDetCh.store.load({params: {meta: true, start:0, limit:9999, sort:'fecha', dir:'DESC'}});// @TODO: load method must be applied over a dinamic referenced object, not 'this.grid1' referenced
 
    
 
    /*app.CoRtTr.grid1.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {
        //Ext.MessageBox.alert('Mensaje',pRec.get("ID") );
    
        VerMantAnexo();
        ConsultaAnexo(pRec.get("ID"));
        //Ext.getCmp("frmMantWin").setTitle("Anexo "+pRec.get("ID"));
        
    })*/

/*  this.grid1.onBeforerender=function(){
		cm=Ext.getCmp(gsObj).getColumnModel();
		cm.add(sm);
		cm.reconfigure()
    }
    this.grid1.onBeforeRender=function(){
		cm=Ext.getCmp(gsObj).getColumnModel();
		cm.add(sm);
    }
*/

})

function toggleDetails(btn, pressed){
    var view = app.CoRtTr.grid1.getView();
    view.showPreview = pressed;
    view.refresh();
}





