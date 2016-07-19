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
    var store = new storeX({
	    proxy: new Ext.data.HttpProxy({
            method:"GET",
            url: (!sgLoadUrl)? 'CoTrTr_salcxcgrid.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id', start:0, limit:9999},
            ['id']
        ),
        groupField: 'nombrcue',        
        sortInfo: {field: 'aux_Nombre', direction: 'ASC'},
        sortInfoX: {field: 'aux_Nombre', direction: 'ASC'}, 
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
        {type: 'string',  dataIndex: 'det_codcuenta'},
        {type: 'numeric',  dataIndex: 'det_idauxiliar'},
        {type: 'string',  dataIndex: 'aux_nombre'},
        {type: 'numeric',  dataIndex: 'saldo'},
        {type: 'string',  dataIndex: 'nombcuenta'}]
        , autoreload:true
    });

    name="Cnt";
    grid1 = new Ext.ux.AutoGridPanel({
        title:'',
		plugins: [filters, summary, groupSum],
        height: 300,
        width: 800,
        cnfSelMode: 'rsms',  //CnfSelMode: propiedad para definir el tipo de selección de datos==> csm(CheckSelectionMode), csm(CellSelectionMode), rsms(RowSelectionMode Single), rsm(RowSelectionMode Multiple)
        loadMask: true,
        stripeRows :true,
        autoSave: true,
        saveUrl: 'saveconfig.php',                
        store : store,
        pageSize:25,
        monitorResize:true
	,tbar: [{
                    text: 'Consultar Cuenta'
                    ,tooltip: 'Consulta de movimientos de cuenta'
                    ,id: 'btnConsultarCta'
                    ,listeners: {
                        click: verDetalleCuenta
                    }
                    ,iconCls:'iconConsultar'
                }]
        ,bbar: new Ext.PagingToolbar({
            pageSize: (this.pageSize? this.pageSize:9999),
            store: store,
            displayInfo: true,
            plugins: [filters],
            displayMsg: 'Registros {0} - {1} de {2}',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-', {
                pressed: false,
                enableToggle:true,
                text: 'Ver Detalles',
                cls: 'x-btn-text-icon details' ,
                toggleHandler: toggleDetails
            }
            ,{
                pressed: false,
                enableToggle:true,
                text: 'Imprimir',
                cls: 'x-btn-text-icon details' ,
                handler: function(){basic_printGrid(grid1);}
            }]
        }),
        view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Personas" : "Personas"]})'
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
    this.grid1.store.load({params: {meta: true, start:0, limit:9999, sort:'aux_nombre', dir:'ASC'}});// @TODO: load method must be applied over a dinamic referenced object, not 'this.grid1' referenced
 
    grid1.getSelectionModel().on('rowselect', function(pSm, pRid, pRec) {

        Ext.getCmp('pnlIzq').collapse();
        olPanel=Ext.getCmp('pnlDer');
        olPanel.setWidth=1200;
        olPanel.collapsible=true;
        if (olPanel.collapsed)olPanel.expand();
        if(Ext.getCmp('grdDetalle')) Ext.getCmp('grdDetalle').destroy();
        app.cart.paramDetalle ={pCuenta: pRec.get("det_codcuenta"), pAuxil: pRec.get("det_idauxiliar")};
	
        if (pRid >=0)  {
            Tipo_Trans = pRec.data.pTipo;
            if (Tipo_Trans == 'C'){
                var signo = 1;    
            }else{
                var signo = -1;
            }
            
            olPanel.add({
            id: 'grdDetalle',
            title: app.cart.paramDetalle.pAuxil + "  " + pRec.get("aux_nombre"),
            layout:'fit',
            closable: true,
            collapsible:true,
            autoLoad:{url:'CoTrTr_salcxcgriddet.php?',		// Objeto a cargar
                params:{
                init:1
                ,pPagina:0
                ,pObj: 'grdDetalle'
                ,pCuent: app.cart.paramDetalle.pCuenta
                ,pAuxil:  app.cart.paramDetalle.pAuxil
                ,pSigno:signo //Tipo Trans esta definido en el php CoTrTr_salcxcgrid.php
            }
            ,scripts: true, method: 'POST'}
            }).show();
            olPanel.doLayout();
        }				//Re-renderiza el contenido;
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

function verDetalleCuenta(){
    fActivaBuscarCombo();
    var color = "#E7E7E7"
    var estilo = 'background-color: '+color+'; font-size:4px; border-style: none; ';
    var estilo2 = 'background-color: '+color+'; font-size:12px; border-style: none; ';
    
    var slDateFmt  ='d-m-y';
    var slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d';
    
    var rdComboBaseGeneral = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;
    
    /*Para  consultar cuentas */
    var dsCta = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'CoTtTr_ConsCta'}
    });
    var olConsCta = new Ext.form.ComboBox({
			fieldLabel:'Cuenta'
			,id:'txt_ConsCta'
			,name:'txt_ConsCta'
			//width:150,
			,store: dsCta
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'cons_cta'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 2
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,listClass: 'x-combo-list-small'
			,allowBlank:false
			});
    
    /*Para  consultar auxiliares */
    var dsAux = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'CoTtTr_ConsAux'}
    });
    var olConsAux = new Ext.form.ComboBox({
			fieldLabel:'Auxiliar'
			,id:'txt_ConsAux'
			,name:'txt_ConsAux'
			//width:150,
			,store: dsAux
			,displayField:	'txt'
			,valueField:     'cod'
			,hiddenName:'cons_aux'
			,selectOnFocus:	true
			,typeAhead: 		true
			,mode: 'remote'
			,minChars: 2
			,triggerAction: 	'all'
			,forceSelection: true
			,emptyText:''
			,allowBlank:     false
			,listWidth:      350 
			,listClass: 'x-combo-list-small'
			,allowBlank:false
			});
    
    var olConsNdoc = {
	    xtype:'numberfield'
	    ,fieldLabel: 'Numero Doc.'
	    ,name: 'cons_Ndoc'
	    ,id: 'cons_Ndoc'
	    /*,allowBlank:false
	    ,hidden:true
	    ,hideLabel:true*/
	};
    /*olConsNdoc.on("specialkey", specialKey, this);
    function specialKey( field, e ) {
      if ( e.getKey() == e.RETURN || e.getKey() == e.ENTER ) {
	  //...code to submit form goes here
	 // be careful of scope if calling your button click handler...
	 fConsultarDetalleCta();
      }
    }*/
    var olConsultar = {xtype:	'button'
		,id:     'btnConsCtaDet'
		,text: 'Consultar'
		//,cls:	 'boton-menu'
		,tooltip: 'Consulta de Detalle de cuentas'
		,text:    ''
		//,width:10
		//,style: 'width:10px;'  // slWidth 
		,iconCls: 'iconBuscar'
		,handler: function(){
		    fConsultarDetalleCta();
		}
	    };//);
    var frmMantConsCta = new Ext.form.FormPanel({
	    //utl:'view/userContacts.cfm',
	    //,bodyStyle:'padding:5px'
	    width: 'auto'//270
	    //defaults: {width: 230},
	    ,border:false
	    ,id:'frmMantConsCta'
            ,layout:'form'
            //style: 'font-size:4px !important; background-color:white !important',
	    ,bodyStyle:estilo//'background-color: '+color+'; font-size:4px;'
            ,defaults:{
		anchor:'98%'
		,bodyStyle:estilo//'background-color: '+color+'; font-size:6px;'
		//,labelStyle:'background-color: #D0D0D0'
		}
	    ,items:[olConsCta,olConsAux, olConsNdoc
		    /*,{columnWidth:1, xtype: 'container',
				    layout: 'table', autoEl: {}, layoutConfig: {columns: 5}
				    ,defaults:{anchor:'100%', sytle:'margin-left:50%'}
					 ,items:[olConsultar]}	*/	    
		    ]
	    ,buttons:[
		{
		    text:'Consultar'
		    ,iconCls: 'iconBuscar'
		    ,handler:fConsultarDetalleCta
		}
		,{
		    text:'Salir'
		    ,iconCls: 'iconSalir'
		    ,handler:function(){
			winConsCta.close();
		    }
		}
	    ]
    });
    
    
    //debugger;
    
    Ext.getCmp("frmMantConsCta").findById("txt_ConsCta").setValue(app.cart.paramDetalle.pCuenta);
    Ext.getCmp("frmMantConsCta").findById("txt_ConsAux").setValue(app.cart.paramDetalle.pAuxil);
    
    var winConsCta = new Ext.Window({
            title:'Consulta de Detalle de Cuenta',
            layout:'fit',
            width:680,
            height:350,
	    id: "frmMantConsCtaWin",
            style: 'font-size:8px',
            border:false,
            items:frmMantConsCta
        });
    winConsCta.show();
    fConsultarDetalleCta();
}

function fConsultarDetalleCta(){
    //debugger;
    if (Ext.getCmp('grdCompDetCta')) Ext.getCmp('grdCompDetCta').destroy();
    Ext.getCmp("frmMantConsCta").add({
		id: 'grdCompDetCta',
		title: "",
		layout:'fit',
		closable: true,
		collapsible:true,
		autoLoad:{url:'CoTrTr_salcxcgrid_detcta.php?',		// Objeto a cargar
			params:{
			init:1
			,pPagina:0
			,pObj: 'grdCompDetCta'
			,pConsCta: Ext.getCmp("frmMantConsCta").findById("txt_ConsCta").getValue()
			,pConsAux: Ext.getCmp("frmMantConsCta").findById("txt_ConsAux").getValue()
			,pConsNumCheque: Ext.getCmp("frmMantConsCta").findById("cons_Ndoc").getValue()
		}
		,scripts: true, method: 'POST'}
		})
    Ext.getCmp("frmMantConsCta").doLayout()

}

function fActivaBuscarCombo(){
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

}

