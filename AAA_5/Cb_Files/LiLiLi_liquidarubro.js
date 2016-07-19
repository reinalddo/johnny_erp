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
    Ext.namespace("app", "app.LiLiLi");
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        sorInfoX : {field:'ID', direction: 'DESC'},
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
            url: (!sgLoadUrl)? 'CoRtTr_Anexocons.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id', start:0, limit:9999},
            ['id']
        ),
        //groupField: 'ID',        
        sortInfo: {field: 'ID', direction: 'DESC'},
        sortInfoX: {field: 'ID', direction: 'DESC'}, 
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
    /*var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'numeric',  dataIndex: 'ID'},
        {type: 'numeric',  dataIndex: 'tipoTransac'},
        {type: 'string',  dataIndex: 'codSustento'},
        //{type: 'numeric',  dataIndex: 'saldo'},
        {type: 'string',  dataIndex: 'devIva'},
	{type: 'numeric',  dataIndex: 'codProv'},
        {type: 'string',  dataIndex: "aux_nombre"},
        {type: 'string',  dataIndex: 'comprobante'},
        {type: 'string',  dataIndex: 'concepto'}]
        , autoreload:true
    });*/


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

    //debugger;
    name="Cnt";
    app.LiLiLi.grid1 = new Ext.ux.AutoEditorGridPanel({//Ext.grid.EditorGridPanel({
        title:'',
		//plugins: [filters],
        height: 230,
        width: 800,
        //cnfSelMode: 'cel',  //CnfSelMode: propiedad para definir el tipo de selección de datos==> csm(CheckSelectionMode), csm(CellSelectionMode), rsms(RowSelectionMode Single), rsm(RowSelectionMode Multiple)
        selModel: new Ext.grid.RowSelectionModel({singleSelect:true}),
        loadMask: true,
        stripeRows :true,
        autoSave: true,
        saveUrl: 'saveconfig.php',                
        store : store,
        pageSize:75
        //,monitorResize:true
        ,clicksToEdit: (1 == app.LiLiLi.modificarPermitido ? 1 : 1000)
        ,bbar: new Ext.PagingToolbar({
            pageSize: (this.pageSize? this.pageSize:75),
            store: store,
            displayInfo: true,
            //plugins: [filters],
            displayMsg: 'Registros {0} - {1} de {2}',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-',{
                pressed: false,
                enableToggle:true,
                text: 'Imprimir',
                cls: 'x-btn-text-icon details' ,
		iconCls: 'iconImprimir',
                handler: basic_printGrid
            }]
        })
        ,listeners: {
                render: function(grid) {
                    app.LiLiLi.grid1.getView().el.select('.x-grid3-header').setStyle('display',    'block');
                    app.LiLiLi.grid1.getView().el.select('.x-grid3-header').setStyle('word-break',    'break-all');
                    app.LiLiLi.grid1.getView().el.select('.x-grid3-header').setStyle('font-size',    '8px');
                    app.LiLiLi.grid1.getView().el.select('.x-grid3-header').setStyle('line-height',    '50px');
                    app.LiLiLi.grid1.getView().el.select('.x-grid3-header').setStyle('text-wrap',    'normal');
                    
                    
                    app.LiLiLi.grid1.getView().el.select('.x-grid3-cell').setStyle('font-size',    '6px');
                }
                ,afteredit: function(grid){
                    //debugger;
                    fGrabarValor(grid);
                }
                ,beforeedit: function(grid){
                    //debugger;
                    if (0 == app.LiLiLi.modificarPermitido){
                        grid.cancel=true;
                        Ext.Msg.alert("Alerta","No se pueden modificar valores porque semana ya fue cerrada");
                    }
                }
            }
        /*,view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Anexos" : "Anexos"]})'
        })*/
        /*,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
            }
	    
        }*/
        //,renderTo: document.body
    });

    // render grid principal
    Ext.getCmp(gsObj).add(app.LiLiLi.grid1);
   
    //Ext.getCmp('paneles').doLayout();
    Ext.getCmp(gsObj).doLayout();
       
    app.LiLiLi.grid1.store.load({params: {meta: true, start:0, limit:75, sort:'ID', dir:'DESC'}});// @TODO: load method must be applied over a dinamic referenced object, not 'this.grid1' referenced
    //debugger;
    /*if (0 == app.LiLiLi.modificarPermitido)
        app.LiLiLi.grid1.suspendEvents();//setDisabled(true);*/
    
    //debugger;
    /*// render grid
    this.grid1.render();
    
    this.gridFoot = this.grid1.getView().getFooterPanel(true);
    this.paging = new Ext.PagingToolbar(this.gridFoot,this.store, {
	pageSize: 4,
	displayInfo: true,
	displayMsg: 'Displaying {0} - {1} of {2}',
	emptyMsg: 'Nothing to display'
    });
    
    // load + metadata
    this.store.load({params: {meta: true, start:0, limit:4}});*/
 
    
	/*//Must reset the proxy in order for it to retrieve data from the server again.
        store.proxy.reset();
        
        //This load demonstrats having different parameters for both the proxy and the remote backend.
        //Parameters passed in "params" are only seen by the proxy unless you set remoteParamsAsParams to
        //true in the PagingMemoryRemoteProxy config. remoteParams are of course sent to the backend.
        store.load({
          params: { //These parameters are only seen by our proxy, not the backend.
            start: 0, 
            limit: 75,
            remote: true //Set remote to true so the proxy can retrieve the data remotely. We use this because of the loadRemoteParam: 'remote' param.
          }, 
          remoteParams: { //The parameters sent remotely to the backend
            start: 0, 
            limit: 200, 
            query: 'update text editor' //The backend take a 'query' parameters and returns forum results based on it.
          }
        });*/
 
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

function fGrabarValor(grid){
    //debugger;
    //Ext.Msg.alert('prueba',grid.field+' '+grid.record.data.des_Productor+' '+grid.value);
    var codRubro = grid.field;
    codRubro = codRubro.substr(codRubro.length-4,codRubro.length);
    var ind = codRubro.indexOf("-");
    codRubro = codRubro.substr(ind+1,codRubro.length);
    
    //new_value = grid.value;
    
    var olParam= {
        id : "olGrabaRubroLiquidacion",
        pSemana : Ext.getCmp("txt_semana").getValue(),
        pProductor  : grid.record.data.des_Productor,
        pRubro   : codRubro, 
        pValor : grid.value
    };
    //old_value = !new_value;
    var olDat = Ext.Ajax.request({
        url: 'LiLiLi_liquidarubrograbar.php',
        callback: function(pOpt, pStat, pResp)
        {
            //debugger;
            if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
            }
            //grid.getGridEl().unmask();
        }
        ,success: function(pResp, pOpt){
            //debugger;
        },
        failure: function(pResp, pOpt){
            //debugger;
            Ext.Msg.alert('Alerta','Ocurrio un problema al grabar.');
        },
        headers: {
           'my-header': 'foo'
        },
        params: olParam
        //,scope:  this
    });
}



