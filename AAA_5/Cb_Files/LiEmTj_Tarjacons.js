/*
*  proceso de Autogrid
*
*
*
**/
//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
//alert("url:" + getFromurl('pUrl',""));    
Ext.onReady(function(){
    Ext.namespace("app", "app.cart");		     // Iniciar namespace cart
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        sorInfoX : {field:'tac_Semana', direction: 'DESC'},
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
            url: (!sgLoadUrl)? 'LiEmTj_Tarjacons.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id', start:0, limit:9999},
            ['id']
        ),
        groupField: 'tac_Semana',        
        sortInfo: {field: 'tac_Semana', direction: 'DESC'},
        sortInfoX: {field: 'tac_Semana', direction: 'DESC'}, 
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
 
    var groupSum = new Ext.grid.GroupSummary();
    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'numeric',  dataIndex: 'tar_NumTarja'},
        {type: 'numeric',  dataIndex: 'tac_Semana'},
	    {type: 'date',  dataIndex: 'tac_Fecha'},
        {type: 'string',  dataIndex: 'aux_nombre'},
        //{type: 'numeric',  dataIndex: 'saldo'},
        {type: 'string',  dataIndex: 'buq_Descripcion'}]
        , autoreload:true
    });

    name="Cnt";
    grid1 = new Ext.ux.AutoGridPanel({
        title:'',
		plugins: [filters, summary, groupSum],
        height: 250,
        width: 800,
        cnfSelMode: 'rsms',  //CnfSelMode: propiedad para definir el tipo de selecci�n de datos==> csm(CheckSelectionMode), csm(CellSelectionMode), rsms(RowSelectionMode Single), rsm(RowSelectionMode Multiple)
        loadMask: true,
        stripeRows :true,
        autoSave: true,
        saveUrl: 'saveconfig.php',                
        store : store,
        pageSize:75,
        monitorResize:true,
        bbar: new Ext.PagingToolbar({
            pageSize: (this.pageSize? this.pageSize:75),
            store: store,
            displayInfo: true,
            plugins: [filters],
            displayMsg: 'Registros {0} - {1} de {2}',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-'/*, {
                pressed: false,
                enableToggle:true,
                text: 'Ver Detalles',
                cls: 'x-btn-text-icon details' ,
                toggleHandler: toggleDetails
            }*/
            ,{
                pressed: false,
                enableToggle:true,
                text: 'Imprimir',
                cls: 'x-btn-text-icon details' ,
		iconCls: 'iconImprimir',
                handler: basic_printGrid
            }]
        }),
        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Tarjas" : "Tarjas"]})'
        }),
        listeners: {
            destroy: function(c) {
                c.getStore().destroy();
            }
        }
    });

    // render grid principal
    Ext.getCmp(gsObj).add(grid1);
   
    //Ext.getCmp('paneles').doLayout();
    Ext.getCmp(gsObj).doLayout();
    this.grid1.store.load({params: {meta: true, start:0, limit:75, sort:'tac_Semana', dir:'DESC'}});// @TODO: load method must be applied over a dinamic referenced object, not 'this.grid1' referenced
 
    grid1.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {
	VerMantTarja();
        ConsultaTarja(pRec.get("tar_NumTarja"),pRec.get("tac_Semana"));
    })

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
    var view = grid1.getView();
    view.showPreview = pressed;
    view.refresh();
}


function ConsultaTarja(nTarja,nSemana){
    
    Ext.override(Ext.data.Store, {
            // private
            // Keeps track of the load status of the store. Set to true after a successful load event
            loaded: false,
            /**
             * Returns true if the store has previously performed a successful load function.
             * @return {Boolean} Whether the store is loaded.
             */
            isLoaded: function(){
                return this.loaded
            },
            // private
            // Called as a callback by the Reader during a load operation.
        loadRecords : function(o, options, success){
            if(!o || success === false){
                if(success !== false){
                    this.fireEvent("load", this, [], options);
                }
                if(options.callback){
                    options.callback.call(options.scope || this, [], options, false);
                }
                return;
            }
            var r = o.records, t = o.totalRecords || r.length;
            if(!options || options.add !== true){
                if(this.pruneModifiedRecords){
                    this.modified = [];
                }
                for(var i = 0, len = r.length; i < len; i++){
                    r[i].join(this);
                }
                if(this.snapshot){
                    this.data = this.snapshot;
                    delete this.snapshot;
                }
                this.data.clear();
                this.data.addAll(r);
                this.totalLength = t;
                this.applySort();
                this.fireEvent("datachanged", this);
            }else{
                this.totalLength = Math.max(t, this.data.length+r.length);
                this.add(r);
            }
                    this.loaded = true;
            this.fireEvent("load", this, r, options);
            if(options.callback){
                options.callback.call(options.scope || this, r, options, true);
            }
        }
    });
    
    Ext.override(Ext.form.ComboBox,{
        /**
         * Sets the specified value into the field.  If the value finds a match, the corresponding record text
         * will be displayed in the field.  If the value does not match the data value of an existing item,
         * and the valueNotFoundText config option is defined, it will be displayed as the default field text.
         * Otherwise the field will be blank (although the value will still be set).
         * @param {String} value The value to match
         */
        setValue : function(v){
            var text = v;
                    if (v && this.mode == 'remote' && !this.store.isLoaded()) {
                        this.lastQuery = '';
                        this.store.load({
                            scope: this,
                            params: this.getParams(),
                            callback: function(){
                                this.setValue(v)
                            }
                        })
                    }
            if(this.valueField){
                var r = this.findRecord(this.valueField, v);
                if(r){
                    text = r.data[this.displayField];
                }else if(this.valueNotFoundText !== undefined){
                    text = this.valueNotFoundText;
                }
            }
            this.lastSelectionText = text;
            if(this.hiddenField){
                this.hiddenField.value = v;
            }
            Ext.form.ComboBox.superclass.setValue.call(this, text);
            this.value = v;
        }
    });

    
    olDat = Ext.Ajax.request({
	url: 'LiEmTj_TarjaconsEspec'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                //debugger;
		Ext.getCmp("frmMant").findById("accion").setValue('UPD');
	        Ext.getCmp("frmMant").findById("txt_Embarque").setValue(olRsp.info.tac_RefOperativa);
                Ext.getCmp("frmMantWin").setTitle("Tarja "+olRsp.info.tar_NumTarja);
                Ext.getCmp("frmMant").findById("tar_NumTarja").setValue(olRsp.info.tar_NumTarja);
		Ext.getCmp("frmMant").findById("tac_CodEmpresa").setValue(olRsp.info.tac_CodEmpresa);
                Ext.getCmp("frmMant").findById("tac_Semana").setValue(olRsp.info.tac_Semana);
                Ext.getCmp("frmMant").findById("tac_Hora").setValue(olRsp.info.tac_Hora);
                Ext.getCmp("frmMant").findById("tac_HoraFin").setValue(olRsp.info.tac_HoraFin);
                Ext.getCmp("frmMant").findById("tac_Fecha").setValue(olRsp.info.tac_Fecha);
                Ext.getCmp("frmMant").findById("txt_zona").setValue(olRsp.info.tac_Zona);
                Ext.getCmp("frmMant").findById("txt_PtoEmbarque").setValue(olRsp.info.tac_PueRecepcion);
                Ext.getCmp("frmMant").findById("tac_Bodega").setValue(olRsp.info.tac_Bodega);
                Ext.getCmp("frmMant").findById("tac_Piso").setValue(olRsp.info.tac_Piso);
                Ext.getCmp("frmMant").findById("tac_Contenedor").setValue(olRsp.info.tac_Contenedor);
                Ext.getCmp("frmMant").findById("tac_Sello").setValue(olRsp.info.tac_Sello);
                Ext.getCmp("frmMant").findById("txt_productor").setValue(olRsp.info.tac_Embarcador);
		//Ext.getCmp("frmMant").findById("tac_UniProduccion").setValue(olRsp.info.tac_UniProduccion);
		Ext.getCmp("frmMant").findById("tac_Transporte").setValue(olRsp.info.tac_Transporte);
		//Agregado
		Ext.getCmp("frmMant").findById("tac_Corte").setValue(olRsp.info.tac_Corte);
		Ext.getCmp("frmMant").findById("txt_Paletizado").setValue(olRsp.info.tac_Paletizado);
		
		Ext.getCmp("frmMant").findById("tac_RefTranspor").setValue(olRsp.info.tac_RefTranspor);
		Ext.getCmp("frmMant").findById("tac_ResCalidad").setValue(olRsp.info.tac_ResCalidad);
		Ext.getCmp("frmMant").findById("tac_Observaciones").setValue(olRsp.info.tac_Observaciones);
		Ext.getCmp("frmMant").findById("tac_Evaluada").setValue(olRsp.info.tac_Evaluada);
		Ext.getCmp("frmMant").findById("tac_CodEvaluador").setValue(olRsp.info.tac_CodEvaluador);
                Ext.getCmp("frmMant").findById("txt_GrupLiquidacion").setValue(olRsp.info.tac_GrupLiquidacion);
                Ext.getCmp("frmMant").findById("tac_NumLiquid").setValue(olRsp.info.tac_NumLiquid);
		//Ext.getCmp("frmMant").findById("tac_CodCartonera").setValue(olRsp.info.tac_CodCartonera);
		Ext.getCmp("frmMant").findById("txt_estado").setValue(olRsp.info.tac_Estado);
		Ext.getCmp("frmMant").findById("tac_Usuario").setValue(olRsp.info.tac_Usuario);
		Ext.getCmp("frmMant").findById("tac_FecDigitacion").setValue(olRsp.info.tac_FecDigitacion);
	    }
	}
	/*,success: function(pRes,pPar){
	    Ext.getCmp("ilNumCheque").setValue(0)
	    Ext.getCmp("flSaldo").setValue(0)
	    }
	,failure: function(pObj, pRes){
	    Ext.getCmp("ilNumCheque").setValue(0)
	    Ext.getCmp("flSaldo").setValue(0)
	    }*/
	,params: {numTarja: nTarja,semana: nSemana}//, pAux:ilAuxi, pBan:true, pTip: Ext.getCmp("slFormaPago").getValue()}
    })
}
