/*
*  Configuracion de Autogrid para presentar cheques que no han sido pagados.
*  
* @param	pAuxil	Codigo de Auxiliar 
*
*
**/

Ext.namespace("app", "app.cheque");

//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
//debugger;
Ext.onReady(function(){
    //debugger;
    
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
            url: (!sgLoadUrl)? 'CoTrTr_chequeEstadoConf.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id',totalProperty:"totalRecords"/*, start:0, limit:9999*/},
            ['id']
        ),
        //groupField: 'ID',        
        sortInfo: {field: 'fecha', direction: 'DESC'},
        sortInfoX: {field: 'fecha', direction: 'DESC'}, 
        groupOnSort:false ,
        remoteSort: true
    });
    
    /*var store = new Ext.data.JsonStore({
        root: 'rows'
        ,totalProperty: 'totalCount'
        ,idProperty: 'id'
        ,remoteSort: true
        ,fields:['id']

	    ,proxy: new Ext.data.HttpProxy({
            method:"POST"
            ,url: (!sgLoadUrl)? 'CoTrCl_conciliaciongridDet.php?' : sgLoadUrl
            })
        ,baseParams:{
            init:0
            ,pPagina:0
            //,pCuent: app.cart.paramDetalle.pCuenta
            ,pAuxil:  app.cart.paramDetalle.pAuxil
            ,fecCorte:  app.cart.paramDetalle.fecCorte
            ,sort: "com_FecContab"
            ,order:"DESC"
            ,dir:"DESC"
            }
        ,sortInfo: {field: "com_FecContab", order:"DESC"}  // @bug: esto no funciona, fue necesario incluirlo en baseparams
        ,remoteSort: true
    });*/
    //store.setDefaultSort('det_numdocum', 'ASC');         // @bug: esto no funciona, fue necesario incluirlo en baseparams

    /*@TODO:                                                                  sumatoria de  grid */
	// custom summary renderer example
    /*   
    function totalDetalle(v, params, data) {
        return v?  v : '';
    }
    var summaryDet = new Ext.ux.grid.GridSummary();    
 
    */
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
    
    name="Car";
    app.cheque.gridEstCon = new Ext.ux.AutoGridPanel({
        title:''
        ,plugins: [filters]
        //,id:'gridConciliacion'
        ,height: 200
        //,width: 450
        ,style:"width:100%; font-size:8px"
        ,cnfSelMode: 'csmm'
        ,loadMask: true
        ,stripeRows :true
        ,autoSave: true
        ,saveUrl: 'saveconfig.php'  
        ,store : store
        ,monitorResize:true
        ,animCollapse:false
        ,collapsible:true
        //colModel: new Ext.grid.ColumnModel([{id: 'null'}]),
        ,tbar:[{
                    text: 'Programar'
                    ,tooltip: 'Programa o Reprograma un grupo de cheques'
                    ,id: 'btnProgramar'
                    ,listeners: {
                        click: fGrabaEstado
                    }
                    ,iconCls:'iconProgramar'
                }
                ,{
                    text: 'Ver Detalle'
                    ,tooltip: 'Muestra los movimientos de un cheque'
                    ,id: 'btnVerDet'
                    ,listeners: {
                        click: fVerDetalleEstado
                    }
                    ,iconCls:'iconConsultar'
                }
               ]
        ,bbar: new Ext.PagingToolbar({
            pageSize: 50,
            store: store,
            displayInfo: true,
            plugins: [filters],
            //displayMsg: '{0} a {1} de {2}',
            displayMsg: ' ',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-',{
                        pressed: false,
                        enableToggle:true,
                        text: 'Imprimir',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconImprimir',
                        handler: function(){basic_printGrid(app.cheque.gridEstCon);}
                    }                
        ]})
        //,plugins: [filtersDet, summaryDet],
        ,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
                //if(Ext.getCmp("frmPago")) Ext.getCmp("frmPago").destroy();
            }
        }
    });
    //debugger;
    //if(!Ext.getCmp("gridConciliacion")){
        var olPanel = Ext.getCmp(gsObj);
        olPanel.add(app.cheque.gridEstCon);
    //}
    
    
    app.cheque.gridEstCon.store.load({params: {meta: true, start:0, limit:25, sort:'com_FecContab', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();
    
    /*app.cheque.gridDet.on('rowclick', function(sm, rowIdx, e) {
                //debugger;
                //var olRec=app.cheque.gridDet.getSelectedRecord().node;
                if (true == Ext.getCmp("chkModificar").checked){
                    var r = app.cheque.gridDet.getStore().getAt(rowIdx);
                    var ilTipo = r.data.com_TipoComp;
                    var ilNum = r.data.com_NumComp;
                    var sm2 = app.cheque.gridDet.getSelectionModel().getSelected();
                    if ('0' == r.data.det_EstLibros ){
                        sm2.set('det_EstLibros','1');//rec.id);
                        sm2.set('det_FecLibros',Date.parseDate(r.data.fecCorte, 'Y-m-d'));
                    }
                    else{
                        sm2.set('det_EstLibros','0');//rec.id);
                        sm2.set('det_FecLibros',Date.parseDate("2050-12-31", 'Y-m-d'));
                    }
                    fModifEstadoFechaConcil(r,ilTipo+ilNum);
                }
                //Ext.Msg.alert('AVISO', ilTipo+ilNum);
	});*/
    
})



/* Muestra una pantalla, donde se selecciona el usuario de destino, y se
*  puede ingresar una observacion
*/
function fGrabaEstado(){
    if (0 == app.cheque.gridEstCon.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Por favor seleccione cheque a programar');
        return;
    }
    //debugger;
        
    var olObservacion = {
            xtype:'textarea'
            ,fieldLabel:'Observacion'
            ,id:'est_observacion'
        };
    var rdComboBaseGeneral2 = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;
    var dsCmbEstado2 = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral2,
            baseParams: {id : 'CoTrTr_estado'}
    });
    
    var olCmbEstado = new Ext.form.ComboBox({
			fieldLabel:'Estado',
			id:'txtestadoDoc',
			name:'txtestadoDoc',
			width:150,
			store: dsCmbEstado2,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'txt_estado',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 1,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250 
		    });
     var olFechaEstado = {	xtype:'datefield'
			,fieldLabel:'Fecha Progr.'
                        ,labelStyle:'width:300;'
                        ,labelWidth:300
                        ,id:'txt_fecProg'
			,name:'txt_fecProg'
			,value: new Date().format('d-M-y')
			,emptyText:''
			,allowBlank:     false
			,format: slDateFmt
			,altFormats: slDateFmts
		    };
     var olPagadoEstado = {	xtype:'checkbox'
			,fieldLabel:'Pagado'
                        ,labelStyle:'width:300;'
                        ,labelWidth:300
                        ,id:'txt_pagado'
			,name:'txt_pagado'
			,allowBlank:     false
		    };
    var olArchivadoEstado = {	xtype:'checkbox'
			,fieldLabel:'Archivado'
                        ,labelStyle:'width:300;'
                        ,labelWidth:300
                        ,id:'txt_archivado'
			,name:'txt_archivado'
			,allowBlank:     false
		    };
     var color = "#E7E7E7"
    var estilo = 'background-color: '+color+'; font-size:4px; border-style: none; ';
    if (!Ext.getCmp('winEstado')){
        var frm = new Ext.form.FormPanel({
            id: "frmWinEstado"
            ,width:400
            ,baseCls: 'x-small-editor'
	    ,border:false
            ,layout:'form'
            ,labelWidth:65
            ,bodyStyle:estilo
            ,defaults:{anchor:'97%'}
            ,items:[ //olFechaEstado,olPagadoEstado,olArchivadoEstado
                    {
				    layout:'column'
				    ,bodyStyle:estilo
                                    ,defaults:{
                                            columnWidth:0.23
                                           ,layout:'form'
                                           ,border:false
                                           ,xtype:'panel'
					   ,bodyStyle:estilo+'padding-left:5px;'
                                           ,labelWidth:52
                                   }
                                   ,items:[
                                        {columnWidth:0.5, /*labelWidth:85,*/ defaults:{anchor:'97%'}
                                        ,items:[olCmbEstado]},
                                        {columnWidth:0.5, labelWidth:85,defaults:{anchor:'97%'}
                                        ,items:[olFechaEstado]}/*,
                                        {defaults:{anchor:'97%'}
                                        ,items:[olArchivadoEstado]}*/
                                   ]
                                }
                        ,olObservacion
                    ]
            ,buttons:[
		{
		    text:'Proceder'//,
		    //type:'submit',
		    ,iconCls: 'iconProceder'
		    ,handler:fConfirmarEstado
		}/*,{
		    text:'Imprimir',
		    type:'submit'
		    //,handler:fGrabar
		    ,iconCls: 'iconImprimir'
		}*/,
		{
		    text:'Salir'
		    ,iconCls: 'iconSalir'
		    ,handler:function(){
			win.close();
		    }
		}
	    ]
        });
        
        var win = new Ext.Window({
            title:'Definir Usuario'
            ,layout:'fit'
            ,width:400
            ,height:170
	    ,id: "winEstado"
            ,items: frm
        });
        win.show();
    }
}
    

function fConfirmarEstado(){
        //debugger;
    var alSel = app.cheque.gridEstCon.getSelectionModel().selections;
    var slFec = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txt_fecProg").value, slDateFmt), 'Y-m-d');
    var slEstado = Ext.getCmp('txtestadoDoc').getValue();
    //var pagado= (true == Ext.getCmp('txt_pagado').checked ? 1 : 0);
    //var archivado= (true == Ext.getCmp('txt_archivado').checked ? 1 : 0);
    
    var olDeta='&obs='+Ext.getCmp('est_observacion').getValue()+"&fecha="+slFec+"&estado="+slEstado;//+"&archivado="+archivado;
    for (var r=0 ; r <  alSel.items.length; r++) {
        //griddata.push(alSel.items[r].data);
        //glosa += "Cheque # "+alSel.items[r].data.cheque+" - Banco: "+alSel.items[r].data.banco+"\n";
        //debugger;
        olDeta += "&cheque["+r+"]="+alSel.items[r].data.cheque;
        olDeta += "&banco["+r+"]="+alSel.items[r].data.banco;
        olDeta += "&regnum["+r+"]="+alSel.items[r].data.regNum;
        olDeta += "&secuencia["+r+"]="+alSel.items[r].data.secuencia;
        //olDeta += "&obser["+r+"]="+app.cheque.observacion;
    }
    fGrabarEstado(3,olDeta,r);
};
    
function fGrabarEstado(opc, detalles, total){
    //debugger;
    if (0 == app.cheque.gridEstCon.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Por favor seleccione');
        return;
    }
    
    var olParam= {
        id : "CoTrTr_chequeGrabar"
        //,pDetalles : detalles
        ,pOpc  : opc
    }
    Ext.Ajax.request({
        url: 'CoTrTr_chequesgrabar.php?tot='+total+detalles,
        success: function(pResp, pOpt){
            //debugger;
            var olRsp = eval("(" + pResp.responseText + ")")
            if ("" != olRsp.message){
                //debugger;
               
                //Ext.getCmp('btnImprimir').enable();
                //Ext.getCmp('btnConfirmar').disable();
                //
                 Ext.Msg.alert('Alerta',olRsp.message);
                Ext.getCmp('winEstado').close();
		//app.cheque.gridEstCon.store.load({params: {meta: true, start:0, limit:25, sort:'com_FecContab', dir:'DESC'}});
		//app.cheque.gridEstCon.store.reload();
		var slFecI = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecIni").value, slDateFmt), 'Y-m-d');
		    var slFecF = Ext.util.Format.date(Date.parseDate(Ext.getCmp("txtFecFin").value, slDateFmt), 'Y-m-d');
		    
                    var slUrl = "CoTrTr_chequeEstadoConf.php?init=1&pFecIni="+ slFecI+"&pFecFin="+slFecF;
                    addTab({id:'gridEstadoConf', title:'Prog. / Cheques', url:slUrl});
            }else{
                Ext.Msg.alert('Alerta',"Error al grabar");
            }
            //olRec.tmp_Valor = olRec.tmp_Cantidad * olRec.tmp_PrecUnit
        },
        failure: function(pResp, pOpt){
            debugger;
            Ext.Msg.alert('AVISO', "Error al actualizar comprobante "+slTipoComp+slNumComp);
        },
        headers: {
           'my-header': 'foo'
        },
        params: olParam,
        scope:  this
    });
    
    
    
}

function fVerDetalleEstado(){
    //debugger;
    if (0 == app.cheque.gridEstCon.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Por favor seleccione cheque a programar');
        return;
    }
    var alSel = app.cheque.gridEstCon.getSelectionModel().selections;
    var olBanco;
    var olCheque;
    for (var r=0 ; r <  alSel.items.length; r++) {
        olCheque = alSel.items[r].data.cheque;
        olBanco = alSel.items[r].data.aux;//alSel.items[r].data.banco;
        //olDeta += "&regnum["+r+"]="+alSel.items[r].data.regNum;
        //olDeta += "&secuencia["+r+"]="+alSel.items[r].data.secuencia;
        //olDeta += "&obser["+r+"]="+app.cheque.observacion;
    }
    

    fActivaBuscarCombo();
    var color = "#E7E7E7"
    var estilo = 'background-color: '+color+'; font-size:4px; border-style: none; ';
    var estilo2 = 'background-color: '+color+'; font-size:12px; border-style: none; ';
    
    var slDateFmt  ='d-m-y';
    var slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d';
    
    var rdComboBaseGeneral = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;
    
    /*Para  consultar auxiliares */
    var dsAux = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'CoTrTr_bancos'}
    });
    var olConsAux = new Ext.form.ComboBox({
			fieldLabel:'Banco'
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
	    ,fieldLabel: 'Cheque'
	    ,name: 'cons_Ndoc'
	    ,id: 'cons_Ndoc'
            ,value: olCheque
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
		,id:     'btnConsChequeDet'
		,text: 'Consultar'
		//,cls:	 'boton-menu'
		,tooltip: 'Consulta de Detalle de programacion de cheques'
		,text:    ''
		//,width:10
		//,style: 'width:10px;'  // slWidth 
		,iconCls: 'iconBuscar'
		,handler: function(){
		    fConsultarDetalleCheque();
		}
	    };//);
    var frmMantConsCta = new Ext.form.FormPanel({
	    //utl:'view/userContacts.cfm',
	    //,bodyStyle:'padding:5px'
	    width: 'auto'//270
	    //defaults: {width: 230},
	    ,border:false
	    ,id:'frmMantConsCheque'
            ,layout:'form'
            //style: 'font-size:4px !important; background-color:white !important',
	    ,bodyStyle:estilo//'background-color: '+color+'; font-size:4px;'
            ,defaults:{
		anchor:'98%'
		,bodyStyle:estilo
		}
	    ,items:[olConsAux, olConsNdoc]
	    ,buttons:[
		{
		    text:'Consultar'
		    ,iconCls: 'iconBuscar'
		    ,handler:fConsultarDetalleCheque
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
    
    Ext.getCmp("frmMantConsCheque").findById("txt_ConsAux").setValue(olBanco);
    
    var winConsCta = new Ext.Window({
            title:'Consulta de Detalle de Cuenta',
            layout:'fit',
            width:680,
            height:350,
	    id: "frmMantConsChequeWin",
            style: 'font-size:8px',
            border:false,
            items:frmMantConsCta
        });
    winConsCta.show();
    fConsultarDetalleCheque();
}

function fConsultarDetalleCheque(){
    //debugger;
    if (Ext.getCmp('grdCompDetCheque')) Ext.getCmp('grdCompDetCheque').destroy();
    Ext.getCmp("frmMantConsCheque").add({
		id: 'grdCompDetCheque',
		title: "",
		layout:'fit',
		closable: true,
		collapsible:true,
		autoLoad:{url:'CoTrTr_chequesestado_det.php?',		// Objeto a cargar
			params:{
			init:1
			,pPagina:0
			,pObj: 'grdCompDetCheque'
			//,pConsCta: Ext.getCmp("frmMantConsCta").findById("txt_ConsCta").getValue()
			,pConsAux: Ext.getCmp("frmMantConsCheque").findById("txt_ConsAux").getValue()
			,pConsNumCheque: Ext.getCmp("frmMantConsCheque").findById("cons_Ndoc").getValue()
		}
		,scripts: true, method: 'POST'}
		})
    Ext.getCmp("frmMantConsCheque").doLayout()

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

