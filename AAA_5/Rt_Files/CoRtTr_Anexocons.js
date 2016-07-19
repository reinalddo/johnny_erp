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
    Ext.namespace("app", "app.CoRtTr");
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
    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'numeric',  dataIndex: 'ID'},
        {type: 'numeric',  dataIndex: 'tipoTransac'},
        {type: 'string',  dataIndex: 'codSustento'},
        //{type: 'numeric',  dataIndex: 'saldo'},
        {type: 'string',  dataIndex: 'devIva'},
	{type: 'numeric',  dataIndex: 'codProv'},
        {type: 'string',  dataIndex: "aux_nombre"},
        //{type: 'string',  dataIndex: 'comprobante'},
	{type: 'string',  dataIndex: 'establecimiento'},
	{type: 'string',  dataIndex: 'puntoEmision'},
	{type: 'string',  dataIndex: 'secuencial'},
        {type: 'string',  dataIndex: 'concepto'}]
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
    app.CoRtTr.grid1 = new Ext.ux.AutoGridPanel({
        title:'',
		plugins: [filters],
        height: 230,
        width: 800,
        cnfSelMode: 'rsms',  //CnfSelMode: propiedad para definir el tipo de selección de datos==> csm(CheckSelectionMode), csm(CellSelectionMode), rsms(RowSelectionMode Single), rsm(RowSelectionMode Multiple)
        loadMask: true,
        stripeRows :true,
        autoSave: true,
        saveUrl: 'saveconfig.php',                
        store : store,
        pageSize:75
        //,monitorResize:true
        ,bbar: new Ext.PagingToolbar({
            pageSize: (this.pageSize? this.pageSize:75),
            store: store,
            displayInfo: true,
            plugins: [filters],
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
    Ext.getCmp(gsObj).add(app.CoRtTr.grid1);
   
    //Ext.getCmp('paneles').doLayout();
    Ext.getCmp(gsObj).doLayout();
       
    app.CoRtTr.grid1.store.load({params: {meta: true, start:0, limit:75, sort:'ID', dir:'DESC'}});// @TODO: load method must be applied over a dinamic referenced object, not 'this.grid1' referenced
 
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
 
    //app.CoRtTr.grid1.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {
    app.CoRtTr.grid1.on('rowdblclick', function(sm, rowIdx,e ) {
        //Ext.MessageBox.alert('Mensaje',pRec.get("ID") );
	//debugger;
	var pRec = app.CoRtTr.grid1.getStore().getAt(rowIdx);
        VerMantAnexo(); //AYUDA
        ConsultaAnexo(pRec.get("ID")); //AYUDA
        //Ext.getCmp("frmMantWin").setTitle("Anexo "+pRec.get("ID"));
        
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
    var view = app.CoRtTr.grid1.getView();
    view.showPreview = pressed;
    view.refresh();
}





function ConsultaAnexo(id){
    
    olDat = Ext.Ajax.request({
	url: 'CoRtTr_AnexoConsEspe'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                //debugger;
		app.CoRtTr.msgValidaRuc = "";
		app.CoRtTr.msgValidaRetencion = "-";
		
		Ext.getCmp("frmMant").findById("regNum").setValue(olRsp.info.com_RegNumero);
		Ext.getCmp("frmMant").findById("ContTipoComp").setValue(olRsp.info.com_TipoComp);
		
	        Ext.getCmp("frmMant").findById("ID").setValue(olRsp.info.ID);
                //Ext.getCmp("frmMantWin").setTitle("Anexo "+olRsp.info.ID);
		Ext.getCmp("frmMant").findById("devolIva").setValue(olRsp.info.devIva);
		//Ext.getCmp("frmMant").findById("devolIva").items.itemAt(0).setRawValue('S');
                Ext.getCmp("frmMant").findById("txt_tipoTrans").setValue(olRsp.info.tipoTransac);
		
		Ext.getCmp("frmMant").findById("codigosSustento").setValue(olRsp.info.sus_CodCompr);
		app.CoRtTr.dsCmbTipoComp.baseParams.pCodigos=Ext.getCmp("frmMant").findById("codigosSustento").getValue();
                app.CoRtTr.dsCmbTipoComp.reload();
		
                Ext.getCmp("frmMant").findById("txt_sustento").setValue(olRsp.info.codSustento);
                Ext.getCmp("frmMant").findById("txtProv").setValue(olRsp.info.codProv);
                Ext.getCmp("frmMant").findById("txtProvFact").setValue(olRsp.info.idProvFact);
		Ext.getCmp("frmMant").findById("tpIdProvFact").setValue(olRsp.info.tpIdProvFact);
		Ext.getCmp("frmMant").findById("txt_rucProv").setValue(olRsp.info.txt_rucProv);
		Ext.getCmp("frmMant").findById("txt_rucProvFact").setValue(olRsp.info.txt_rucProvFact);
		
                Ext.getCmp("frmMant").findById("txt_tipoComp").setValue(olRsp.info.tipoComprobante);
                Ext.getCmp("frmMant").findById("establecimiento").setValue(olRsp.info.establecimiento);
                Ext.getCmp("frmMant").findById("puntoEmision").setValue(olRsp.info.puntoEmision);
                Ext.getCmp("frmMant").findById("secuencial").setValue(olRsp.info.secuencial);
                Ext.getCmp("frmMant").findById("autorizacion").setValue(olRsp.info.autorizacion);
		
		Ext.getCmp("fechaAut1").setValue(olRsp.info.txt_FecEmision);
		Ext.getCmp("fechaAut2").setValue(olRsp.info.txt_FecVigencia);
		
                Ext.getCmp("frmMant").findById("concepto").setValue(olRsp.info.concepto);
                Ext.getCmp("frmMant").findById("fechaEmision").setValue(olRsp.info.fechaEmision);
                Ext.getCmp("frmMant").findById("fechaRegistro").setValue(olRsp.info.fechaRegistro);
		Ext.getCmp("frmMant").findById("txt_Embarque").setValue(olRsp.info.tac_RefOperativa);
		Ext.getCmp("frmMant").findById("semana").setValue(olRsp.info.semana);
                //debugger;
                //Datos de pestaña 'Datos IVA / ICE'
		//Ext.getCmp("frmMant").findById("tabDatos").items.itemAt(0).items.itemAt(0).items.item
                Ext.getCmp("baseImponible").setValue(olRsp.info.baseImponible);
                Ext.getCmp("baseImpGrav").setValue(olRsp.info.baseImpGrav);
                Ext.getCmp("txt_IVA").setValue(olRsp.info.porcentajeIva);
                Ext.getCmp("porc_iva").setValue(olRsp.info.civ_Porcent);
		Ext.getCmp("montoIva").setValue(olRsp.info.montoIva);
                Ext.getCmp("baseImpIce").setValue(olRsp.info.baseImpIce);
                Ext.getCmp("txt_ICE").setValue(olRsp.info.porcentajeIce);
		Ext.getCmp("porc_ice").setValue(olRsp.info.pic_Porcent);
                Ext.getCmp("montoIce").setValue(olRsp.info.montoIce);
                
                //Datos de pestaña 'Retenciones'
		//Ext.getCmp("frmMant").findById("tabDatos").items.itemAt(1).items.itemAt(0).items.item
                Ext.getCmp("montoIvaBienes").setValue(olRsp.info.montoIvaBienes);
                Ext.getCmp("txt_retIvaBienes").setValue(olRsp.info.porRetBienes);
                Ext.getCmp("valorRetBienes").setValue(olRsp.info.valorRetBienes);
		Ext.getCmp("porc_ret_iva_b").setValue(olRsp.info.prb_Porcent);
                Ext.getCmp("montoIvaServicios").setValue(olRsp.info.montoIvaServicios);
                Ext.getCmp("txt_RetIvaServ").setValue(olRsp.info.porRetServicios);
		Ext.getCmp("porc_ret_iva_s").setValue(olRsp.info.prs_Porcent);
                Ext.getCmp("valorRetServicios").setValue(olRsp.info.valorRetServicios);
                
		//Ext.getCmp("frmMant").findById
                Ext.getCmp("estabRetencion1").setValue(olRsp.info.estabRetencion1);
                Ext.getCmp("puntoEmiRetencion1").setValue(olRsp.info.puntoEmiRetencion1);
                Ext.getCmp("secRetencion1").setValue(olRsp.info.secRetencion1);
                Ext.getCmp("autRetencion1").setValue(olRsp.info.autRetencion1);
                Ext.getCmp("fechaEmiRet1").setValue(olRsp.info.fechaEmiRet1);
		
		Ext.getCmp("retfechaAut1").setValue(olRsp.info.txt_FecEmisionRet);
		Ext.getCmp("retfechaAut2").setValue(olRsp.info.txt_FecVigenciaRet);
                
		//Ext.getCmp("frmMant").findById("tabDatos").items.itemAt(1).items.itemAt(2).items.item
                Ext.getCmp("txt_codRet1").setValue(olRsp.info.codRetAir);
                Ext.getCmp("baseImpAir").setValue(olRsp.info.baseImpAir);
                Ext.getCmp("porcentajeAir").setValue(olRsp.info.porcentajeAir);
                Ext.getCmp("valRetAir").setValue(olRsp.info.valRetAir);
                Ext.getCmp("txt_codRet2").setValue(olRsp.info.codRetAir2);
                Ext.getCmp("baseImpAir2").setValue(olRsp.info.baseImpAir2);
                Ext.getCmp("porcentajeAir2").setValue(olRsp.info.porcentajeAir2);
                Ext.getCmp("valRetAir2").setValue(olRsp.info.valRetAir2);
                Ext.getCmp("txt_codRet3").setValue(olRsp.info.codRetAir3);
                Ext.getCmp("baseImpAir3").setValue(olRsp.info.baseImpAir3);
                Ext.getCmp("porcentajeAir3").setValue(olRsp.info.porcentajeAir3);
                Ext.getCmp("valRetAir3").setValue(olRsp.info.valRetAir3);
                
                //Datos de pestaña 'Datos Generales'
                Ext.getCmp("frmMant").findById("docModificado").setValue(olRsp.info.docModificado);
                Ext.getCmp("frmMant").findById("fechaEmiModificado").setValue(olRsp.info.fechaEmiModificado);
                Ext.getCmp("frmMant").findById("estabModificado").setValue(olRsp.info.estabModificado);
                Ext.getCmp("frmMant").findById("ptoEmiModificado").setValue(olRsp.info.ptoEmiModificado);
                Ext.getCmp("frmMant").findById("secModificado").setValue(olRsp.info.secModificado);
                Ext.getCmp("frmMant").findById("autModificado").setValue(olRsp.info.autModificado);
                Ext.getCmp("frmMant").findById("contratoPartidoPolitico").setValue(olRsp.info.contratoPartidoPolitico);
                Ext.getCmp("frmMant").findById("montoTituloOneroso").setValue(olRsp.info.montoTituloOneroso);
                Ext.getCmp("frmMant").findById("montoTituloGratuito").setValue(olRsp.info.montoTituloGratuito);
                Ext.getCmp("frmMant").findById("numeroComprobantes").setValue(olRsp.info.numeroComprobantes);
		Ext.getCmp("frmMant").findById("ivaPresuntivo1").setValue(olRsp.info.ivaPresuntivo);
		Ext.getCmp("frmMant").findById("retPresuntiva1").setValue(olRsp.info.retPresuntiva);
                
                
                //Datos de pestaña 'Import / Export'
                Ext.getCmp("frmMant").findById("distAduanero").setValue(olRsp.info.distAduanero);
                Ext.getCmp("frmMant").findById("anio").setValue(olRsp.info.anio);
                Ext.getCmp("frmMant").findById("regimen").setValue(olRsp.info.regimen);
                Ext.getCmp("frmMant").findById("correlativo").setValue(olRsp.info.correlativo);
                Ext.getCmp("frmMant").findById("verificador").setValue(olRsp.info.verificador);
		Ext.getCmp("frmMant").findById("tipImpExp1").setValue(olRsp.info.tipImpExp);
                //tipImpExp
                Ext.getCmp("frmMant").findById("valorCifFob").setValue(olRsp.info.valorCifFob);
                Ext.getCmp("frmMant").findById("valorFobComprobante").setValue(olRsp.info.valorFobComprobante);
                Ext.getCmp("frmMant").findById("fechaEmbarque").setValue(olRsp.info.fechaEmbarque);
                Ext.getCmp("frmMant").findById("documEmbarque").setValue(olRsp.info.documEmbarque);
                
		
		//Datos de cuenta de gastos
		Ext.getCmp("frmMant").findById("txt_CtaGasto").setValue(olRsp.info.det_CodCuenta);
		Ext.getCmp("frmMant").findById("txt_CtaAuxiliar").setValue(olRsp.info.det_IDAuxiliar);
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
	,params: {idAnexo: id}//, pAux:ilAuxi, pBan:true, pTip: Ext.getCmp("slFormaPago").getValue()}
    })
}