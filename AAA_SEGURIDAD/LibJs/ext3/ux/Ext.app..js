/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Gina Franco
 *  @date       13/Abr/09
 *  *****************************************************************************************************************
 *  *****************************************************************************************************************
 *  *****************************************************************************************************************
 *  ********************************* NO TE OLVIDES DE DOCUMENTAR EL CODIGO.!!!!!!!!!!!!!!!!!
 *  @rev	fah 29/04/09	A�adir propiedad tabibdex a los campos del formulario para darles secuencialidad
 */
Ext.ns("li.em", "app.li.tarjas");

//Ext.onReady(function(){
(function(){
	loadScript("LibJs/ext3/ux/menu/EditableItem.js",
				function(){
					loadScript("LibJs/ext3/ux/Reorderer.js",
						function(){
							loadScript("LibJs/ext3/ux/ToolbarReorderer.js",
								function(){
									loadScript("LibJs/ext3/ux/FilterRow.js",
										function(){
											loadScript("LibJs/ext3/ux/ToolbarDroppable.js",
											function(){
												loadScript("LibPhp/ExtDirect/api_tj.php?pAuto=1", function(){
													app.li.tarjas.initComponent();
												}
												)

											})
										}
									)
								}
							);
						}
					)
				}
			);
})()

app.li.tarjas.initComponent = function(){
    Ext.QuickTips.init();
    olDet=Ext.get('divDet');
	Ext.namespace("app", "app.cart");		   // Iniciar namespace cart

	app.li.tarjas.sumRechazadas  = function (v, record, field){
			return v + record.data.CajaRechazada;
		}
	app.li.tarjas.sumRecibidas=function (v, record, field){
			return v + record.data.CajasRecibida;
		}
	app.li.tarjas.sumCaidas = 	function (v, record, field){
			return v + record.data.CajaCaidas;
		}
	app.li.tarjas.sumEmbarcadas= function (v, record, field){
			return v + record.data.CajaEmbarcada;
		}

	app.li.tarjas.setGridOptions=function(){
		Ext.grid.GridSummary.Calculations['totalCajasRecha'] = app.li.tarjas.sumRechazadas;
		Ext.grid.GridSummary.Calculations['totalCajasRecib'] = app.li.tarjas.sumRecibidas;
		Ext.grid.GridSummary.Calculations['totalCajasCaidas'] = app.li.tarjas.sumCaidas;
		Ext.grid.GridSummary.Calculations['totalCajasEmbarcadas'] = this.sumEmbarcadas;

		Ext.grid.GroupSummary.Calculations['totalCajasRecha'] = app.li.tarjas.sumRechazadas;
		Ext.grid.GroupSummary.Calculations['totalCajasRecib'] = app.li.tarjas.sumRecibidas;
		Ext.grid.GroupSummary.Calculations['totalCajasCaidas'] = app.li.tarjas.sumCaidas;
		Ext.grid.GroupSummary.Calculations['totalCajasEmbarcadas'] = app.li.tarjas.sumEmbarcadas;

		//var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
		//	sorInfoX : {field:'tac_Semana', direction: 'DESC'},
		//	applySort: function(){
		//		Ext.data.GroupingStore.superclass.applySort.call(this);
		//		if (!this.sortInfo) this.sortInfo = this.sortInfoX;
		//		if(!this.groupOnSort && !this.remoteGroup){
		//		var gs = this.getGroupState();
		//		if(gs && gs != this.sortInfo.field){
		//		this.sortData(this.groupField);
		//		}
		//		}
		//	}
		//});

		/*@TODO:                                                                  sumatoria de  grid */
		// custom summary renderer example
		/*   */
		app.li.tarjas.pageSize = 1000;
		app.li.tarjas.store = {
			xtype: 			"groupingstore"
			,id: 			"strTarjas"
			,className: 	"_LiEmTj_tarjas"
			//,idProperty: 	"id"
			//,pruneModifiedRecords : true
			,autoSave:		false
			,reader: 		{idProperty:"id"}
			,baseParams:	{start:0, limit:app.li.tarjas.pageSize, sort:"tac_Semana", order:"DESC"}
			//,groupField : 	'aux_nombre'
			,proxy: new Ext.data.DirectProxy({
				paramsAsHash: true,
				directFn: app.direct.tj._LiEmTj_tarjas.getList
			,sortInfo: { field: 'tac_Semana', direction: 'DESC'  }
			})
		}
		app.li.tarjas.summary = new Ext.grid.GridSummary();
		app.li.tarjas.groupSum = new Ext.grid.GroupSummary();
		app.li.tarjas.filters = new app.gen.preConfigFilterRow;
	}

app.li.tarjas.getColsCfg= function(){
	return [new Ext.grid.RowNumberer(),
	{
		  name: 		'id'
		  ,id:		'id'
		  ,header: 	'ID'
		  ,dataIndex: 'id'
	},
	{
		  name: 		'tac_Semana',
		  header: 	'SEMANA',
		  //width: 	10,
		  //sortable: true,
		  dataIndex: 'tac_Semana'
	},
	{
		  name: 		'tar_NumTarja',
		  header: 	'NUM. TARJA',
		  //width: 	10,
		  //sortable: true,
		  dataIndex: 'tar_NumTarja'
	},
	{
		  name: 		'aux_nombre',
		  header: 	'PRODUCTOR',
		  width: 300,
		  //sortable: true,
		  dataIndex: 'aux_nombre'
	},
	{
		  name: 		'txt_empaque',
		  header: 	'EMPAQUE',
		  width: 	200,
		  //sortable: true,
		  dataIndex: 'txt_empaque'
	},
	{
		  name: 		'Vapor',
		  header: 	'VAPOR',
		  width: 	200,
		  //sortable: true,
		  dataIndex: 'Vapor'
	},
	{
		  name: 		'CajasRecibida',
		  header: 	'CAJAS RECIB.',
		  //width: 	10,
		  //sortable: true,
		  dataIndex: 'CajasRecibida',
		  summaryType: 'totalCajasRecib'
		  ,type:		'int'
   },
	{
		  name: 		'CajaRechazada',
		  header: 	'CAJAS RECHA.',
		  //width: 	10,
		  //sortable: true,
		  dataIndex: 'CajaRechazada',
		  summaryType: 'totalCajasRecha'
		  ,type:		'int'
	},
	{
		  name: 		'CajaCaidas',
		  header: 	'CAJAS CAID.',
		  //width: 	10,
		  //sortable: true,
		  dataIndex: 'CajaCaidas',
		  summaryType: 'totalCajasCaidas'
		  ,type:		'int'
	},
	{
		  name: 		'CajaEmbarcada',
		  header: 	'CAJAS EMBAR.',
		  //width: 	10,
		  //sortable: true,
		  dataIndex: 'CajaEmbarcada',
		  summaryType: 'totalCajasEmbarcadas'
		  ,type:		'int'
	},
	{
		  name: 		'contenedor',
		  header: 	'CONTENEDOR',
		  width: 	150,
		  //sortable: true,
		  dataIndex: 'contenedor'
	},
	{
	  name: 			'Piso',
	  header: 		'PISO',
	  //width: 		10,
	  //sortable: 	true,
	  dataIndex: 	'Piso'
	 },
	 {
	  name: 			'CodEvaludor',
	  header: 		'COD. EVALUADOR',
	  //width: 		10,
	  //sortable: 	true,
	  dataIndex: 	'CodEvaluador'
	  },
	  {
		  name: 		'Observaciones',
		  header: 	'OBSERVACIONES',
		  width: 	150,
		  //sortable: true,
		  dataIndex: 'Observaciones'
	  }
	]
}


	app.li.tarjas.getGridCfg= function(){
		app.li.tarjas.setGridOptions.createDelegate(app.li.tarjas)()
		return {
		title:			'TARJAS',
		//height: 		250,
		anchor:			"100% 100%",
		cnfSelMode: 	'rsms',  //CnfSelMode: propiedad para definir el tipo de selecci�n de datos==> csm(CheckSelectionMode), csm(CellSelectionMode), rsms(RowSelectionMode Single), rsm(RowSelectionMode Multiple)
		loadMask: 		true,
		stripeRows :	true,
		className:		"_LiEmTj_tarjas"
		,xtype:			"Ext.ux.app.directGrid"
		,defaults:		{width:	200}
		,editable:		false
		//autoSave: true,
		//saveUrl: 'saveconfig.php',
		,store: 		app.li.tarjas.store
	   // pageSize:20,
		,monitorResize:	true,
		columns: 		app.li.tarjas.getColsCfg(),
		plugins: 		[app.li.tarjas.filters , app.li.tarjas.summary, app.li.tarjas.groupSum],
		bbar: new Ext.PagingToolbar({
			pageSize: 		app.li.tarjas.pageSize,
			store: 			app.li.tarjas.store,
			displayInfo: 	true,
			//plugins: 		[app.li.tarjas.filters],
			displayMsg: 	'Registros {0} - {1} de {2}',
			emptyMsg: 		"No hay datos que presentar",
			items:[
				'-'/*, {
				pressed: false,
				enableToggle:true,
				text: 'Ver Detalles',
				cls: 'x-btn-text-icon details' ,
				toggleHandler: toggleDetails
			}*/
			,{
				pressed: 	false,
				enableToggle:true,
				text: 		'Imprimir',
				cls: 		'x-btn-text-icon details' ,
				iconCls: 	'iconImprimir',
				handler: 	basic_printGrid
			}]
		}),

		view: new Ext.grid.GroupingView({
			forceFit:true,
			groupTextTpl: 	'{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Tarjas" : "Tarjas"]})'
		}),
		listeners: {
			destroy: function(c) {
				c.getStore().destroy();
			}
		}
		};
	}

	app.modulos.elm[app.modulos.actual.id] = {
			title:			"TARJAS"
			,tabTip:		"Panel de Tarjas procesadas en una semana"
			,xtype:			"Ext.ux.app.panel"
			,namespace: 	"app.direct.tj"
			//,apiDescriptor: "APIDrh"
			,items: 		[app.li.tarjas.getGridCfg()]
			,anchor:		"100% 100%"
			,beforeShow: 	function(pPanel){

				var olMaster=pPanel.items.items[0];   	// Elemento Master

				var olMstore=olMaster.getStore();		//Store del Master
				var olMasterSm = olMaster.getSelectionModel()  // Selmodel Master

				olMasterSm.on('rowselect', function(pSm, pRid, pRec) {
					//this.Detalles();
					//ConsultaTarja(pRec.get("tar_NumTarja"),pRec.get("tac_Semana"));
				})
//debugger;
//Ext.util.Observable.capture(olMaster, console.info)
				olMstore.on("load", function(){
					//olMaster.getSelectionModel().selectFirstRow();
					}, this)
				if (app.loadMask) app.loadMask.hide();
				//olMstore.load({params: {meta: true, start:0, limit:25, sort:'tac_Semana', dir:'DESC'}});// @TODO: load method must be applied over a dinamic referenced object, not 'this.grid1' referenced
				return true;
			}
		}
	var olPanel = Ext.create(app.modulos.elm[app.modulos.actual.id], "panel");
	}
//on REady

/*
*	-----------------------------------------------------------------------------------------
*/





function VerMantTarja(){

    //debugger;
    var frmMantTarWin;// = new Ext.Window({items:[olSemana]});
    var frmMant;

    /*Para Consultar Embarques*/
    var rdComboBase = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt', 'semana']
		    ) ;
    var dsCmbVapor = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'LiEmTj_embarlist'}
    });
    //dsCmbVapor.load({params: {pSeman : win.findById("txt_semana").getValue()}});

    /*Para Consultar Puertos de Embarque*/
    var rdComboBaseGeneral = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;

    var dsCmbPtoEmbarque = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_ptoEmbarque'}
    });

    /*Para Consultar empresas*/
    var dsCmbEmpresa = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_empresa'}
    });

    /*Para Consultar productores*/
    var dsCmbProductor = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_productores'}
    });

    /*Para Consultar evaluadores*/
    var dsCmbEvaluador_tx = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_evaluadores'}
    });


    /*Para Consultar Contenedors*/
    var dsCmbContenedor = 	new Ext.data.Store({
        proxy: 		new Ext.data.HttpProxy({
                url: '../Ge_Files/GeGeGe_queryToXml.php',
                metod: 'POST'//,
	    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
        }),
        reader: 	rdComboBaseGeneral,
        baseParams: {id : 'LiEmTj_contenedor'}
		});
    /*Para Consultar Grupo*/
    var dsCmbGrupo = 	new Ext.data.Store({
        proxy: 		new Ext.data.HttpProxy({
                url: '../Ge_Files/GeGeGe_queryToXml.php',
                metod: 'POST'//,
	    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
        }),
        reader: 	rdComboBaseGeneral,
        baseParams: {id : 'LiEmTj_grupo'}
		});

    /*Para Consultar estados*/
    var dsCmbEstado = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_estado'}
    });

    /*Para Consultar zonas*/
    var dsCmbZona = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_zona'}
    });

        /*Para Consultar paletizado*/
    var dsCmbPaletizado = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'LiEmTj_Paletizado'}
    });

    var dsCmbEvaluador = new Ext.data.SimpleStore({
        fields: ['cod', 'txt']
       ,data: [
            ['1', 'Evaluada en Campo']
           ,['2', 'Evaluada en Puerto']
       ]
    });

    slDateFmt  ='d-m-y';
    slDateFmts = 'd-m-y|d-m-Y|d-M-y|d-M-Y|d/m/y|d/m/Y|d/M/y |d/M/Y|Y-m-d';


    var olEmpresaCmb = new Ext.form.ComboBox({
			fieldLabel:'Empresa',
			id:'txt_Empresa',
			name:'txt_Empresa',
			//width:150,
			store: dsCmbEmpresa,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'empresa',
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

    var olEmbarque = new Ext.form.ComboBox({
			fieldLabel:'Embarque',
			id:'txt_Embarque',
			name:'txt_Embarque',
			width:150,
			store: dsCmbVapor,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_RefOperativa',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      350
			,tabIndex:1001
			,listClass: 'x-combo-list-small'
			,listeners: {'select': function (combo,record){
					frmMant.findById("tac_Semana").setValue(record.data.semana);
					fValidarSemana1();
				    }}
		    });

       var olPaletizado = new Ext.form.ComboBox({
			fieldLabel:'Tipo de Carga',
			id:'txt_Paletizado',
			name:'txt_Paletizado',
			width:150,
			store: dsCmbPaletizado,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'tac_Paletizado',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 3,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     true,
			listWidth:      350
			,tabIndex:1007
			,value:2
			,listClass: 'x-combo-list-small'
		    });


    var olAccion = {
                        xtype:'textfield'
                        ,fieldLabel: 'Accion'
                        ,name: 'accion'
                        ,id: 'accion'
			//,visible:false
			,hidden:true
			,hideLabel:true
			,value:'ADD'
                    };
    var olEmpresa = {
                        xtype:'textfield'
                        ,fieldLabel: 'cod empresa'
                        ,name: 'tac_CodEmpresa'
                        ,id: 'tac_CodEmpresa'
			//,visible:false
			,hidden:true
			,hideLabel:true
			,value:0
                    };

    var olCorte = {
                        xtype:'numberfield'
                        ,fieldLabel: 'Corte'
                        ,name: 'tac_Corte'
                        ,id: 'tac_Corte'
			//,visible:false
			//,hidden:true
			//,hideLabel:true
                        	,selectOnFocus:	true
			,tabIndex:1006

		    /*,listeners: {'change' : function (field, newValue, oldValue) {
								fCargarValidador();
							}
						}*/
             };

    var olNumTarja = {
		xtype:'numberfield'
		,fieldLabel: 'Num Tarja'
		,name: 'tar_NumTarja'
		,id: 'tar_NumTarja'
		,allowBlank:false
		,tabIndex:1000
		,listeners: {'change' : function (field, newValue, oldValue) {
		// how do I get access to this record here?
			olDat = Ext.Ajax.request({
				url: 'LiEmTj_Tarjavalidacion'
				,callback: function(pOpt, pStat, pResp){
					if (true == pStat){
						olRsp = eval("(" + pResp.responseText + ")");
						if (olRsp.info.tarja == 0){
							tmpsem=Ext.getCmp("tac_Semana").getValue();
							tmpval=Ext.getCmp("tac_Semana").isValid();
							if(tmpsem=='' && tmpval==false)
								Ext.getCmp("tac_Semana").markInvalid();
							else
							{//return true;
								Ext.getCmp("gridDetalle").store.removeAll();
								Ext.getCmp("frmMantWin").setTitle("Nueva Tarja");
								Ext.getCmp("frmMant").findById('accion').setValue('ADD');
								Ext.getCmp("btnAdd").enable();
								Ext.getCmp("btnEliminar").enable();
								Ext.getCmp("btEliminar").enable();
								Ext.getCmp("btnGrabar").enable();
								Ext.getCmp("frmMant").findById("tar_NumTarja").clearInvalid();
								Ext.getCmp("frmMant").getForm().clearInvalid();
								slMens1='Cambio de Numero Tarja de:'+oldValue+ ' a:'+newValue;
								fGrabarBitacora(newValue,slMens1);
								fAgregar();

							}
						}else{
							Ext.Msg.alert('AVISO', 'Numero de Tarja ya fue ingresada en semana '+olRsp.info.tac_Semana);
							Ext.getCmp("frmMant").findById("tar_NumTarja").markInvalid();
							Ext.getCmp("btnGrabar").disable();
							Ext.getCmp("btnAdd").disable();
							Ext.getCmp("btEliminar").disable();
							Ext.getCmp("btnEliminar").disable();
						}
					}
				}
				,params: {numTarja: Ext.getCmp("frmMant").findById("tar_NumTarja").getValue(),semana: Ext.getCmp("frmMant").findById("tac_Semana").getValue()}
			});
		}
	}

	};

    fechahoy = hoy();
     var olFecha = {
		xtype:'datefield',
		fieldLabel: 'Fecha',
		name: 'tac_Fecha',
		id: 'tac_Fecha',
		allowBlank:false
		,format: slDateFmt
		,altFormats: slDateFmts
		,tabIndex:1003
		,value:fechahoy
	};
    var olAnio = {
		xtype:'numberfield',
		fieldLabel: 'Anio',
		name: 'txt_anio',
		id: 'txt_anio'
	};
    var olSemana = {
		xtype:'numberfield',
		fieldLabel: 'Semana',
		name: 'tac_Semana',
		id: 'tac_Semana',
		allowBlank:false
		,tabIndex:1002
		,listeners: {'change' : function (field, newValue, oldValue) {
				fValidarSemana1();
			}
		}
	};
    var olHoraInicio = {
		xtype:'timefield',
		fieldLabel: 'Hora Inicio',
		name: 'tac_Hora',
		id: 'tac_Hora'
		,format:'H:i'//'h:i a'
		,minValue: '00:00'
		,maxValue: '23:59'
		,maxLength:5
		,tabIndex:1004
	};
    var olHoraCierre = {
		xtype:'timefield'
		,fieldLabel: 'Hora Cierre'
		,name: 'tac_HoraFin'
		,id: 'tac_HoraFin'
		,format:'H:i'//'h:i a'
		,minValue: '00:00'
		,maxValue: '23:59'
		,maxLength:5
		,tabIndex:1005
    };

    var olZona = new Ext.form.ComboBox({
		fieldLabel:'Zona',
		id:'txt_zona',
		name:'txt_zona',
		//width:150,
		store: dsCmbZona,
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'tac_Zona',
		selectOnFocus:	true,
		typeAhead: 		true,
		mode: 'remote',
		minChars: 1,
		triggerAction: 	'all',
		forceSelection: true,
		emptyText:'',
		allowBlank:     false,
		listWidth:      250
		,tabIndex:1008
	});
    var olPtoEmbarque = new Ext.form.ComboBox({
		fieldLabel:'Pto.Embarque',
		id:'txt_PtoEmbarque',
		name:'txt_PtoEmbarque',
		width:150,
		store: dsCmbPtoEmbarque,
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'tac_PueRecepcion',
		selectOnFocus:	true,
		typeAhead: 		true,
		mode: 'remote',
		minChars: 3,
		triggerAction: 	'all',
		forceSelection: true,
		emptyText:'',
		allowBlank:     false,
		listWidth:      250
		,value:1
		,tabIndex:1010
	});
    var olBodega = {
		xtype:'textfield',
		fieldLabel: 'Bodega',
		name: 'tac_Bodega',
		id: 'tac_Bodega'
		,tabIndex:1012
	};
    var olPiso = {
		xtype:'textfield',
		fieldLabel: 'Piso',
		name: 'tac_Piso',
		id: 'tac_Piso'
		,tabIndex:1013
    };
    var olContenedor = {
    	xtype:'textfield'
        ,fieldLabel: 'Contenedor'
        ,name: 'tac_Contenedor'
        ,id: 'tac_Contenedor'
    	,allowBlank:true
    	,width:80
    	,tabIndex:1015
    };

    var olSello =  {
		xtype:'textfield',
		fieldLabel: 'Sello',
		name: 'tac_Sello',
		id: 'tac_Sello'
	,tabIndex:1014
    };
    var olProductor = new Ext.form.ComboBox({
		fieldLabel:'Productor/Grupo',
		id:'txt_productor',
		name:'txt_productor',
		width:150,
		store: dsCmbProductor,
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'tac_Embarcador',
		selectOnFocus:	true,
		typeAhead: 		true,
		mode: 'remote',
		minChars: 3,
		triggerAction: 	'all',
		forceSelection: true,
		emptyText:'',
		allowBlank:     false,
		listWidth:      350
		,tabIndex:1016
	});

    var olHacienda = {
		xtype:'combo',
		fieldLabel: 'Hacienda',
		name: 'tac_UniProduccion',
		id: 'tac_UniProduccion'
		,tabIndex:1017
    };
    var olTransporte = {
		xtype:'textfield',
		fieldLabel: 'Transporte',
		name: 'tac_Transporte',
		id: 'tac_Transporte'
		,tabIndex:1019
    };
    var olTransportista = {
		xtype:'textfield',
		fieldLabel: 'Transportista',
		name: 'tac_RefTranspor',
		id: 'tac_RefTranspor'
		,tabIndex:1020
    };
    var olCalidad = {
		xtype:'textfield',
		fieldLabel: '%Calidad',
		name: 'tac_ResCalidad',
		id: 'tac_ResCalidad'
		,tabIndex:1021
    };
    var olObservacion = {
		xtype:'textfield',
		fieldLabel: 'Observacion',
		name: 'tac_Observaciones',
		id: 'tac_Observaciones'
		,tabIndex:1025
    };

    var olPreEval = new Ext.form.ComboBox({
		fieldLabel:'Evaluacion',
		id:'txt_Evaluada',
		name:'txt_Evaluada',
		width:150,
		store: dsCmbEvaluador,
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'tac_Evaluada',
		selectOnFocus:	true,
		mode: 'local',
		forceSelection: true,
		emptyText:'',
		allowBlank:     false,
		listWidth:      350
		,tabIndex:1022
    });



   var olCodEval = new Ext.form.ComboBox({
		fieldLabel:'Cod Evaluador',
		id:'txt_CodEvaluador',
		name:'txt_CodEvaluador',
		width:150,
		store: dsCmbEvaluador_tx,
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'tac_CodEvaluador',
		selectOnFocus:	true,
		typeAhead: true,
		mode: 'remote',
		minChars: 3,
		triggerAction: 	'all',
		forceSelection: true,
		emptyText:'',
		allowBlank:     false,
		listWidth:      350
		,tabIndex:1023
	});

    var olGrupoLiq = new Ext.form.ComboBox({
		fieldLabel:'Productor',
		id:'txt_GrupLiquidacion',
		name:'txt_GrupLiquidacion',
		width:150,
		store: dsCmbGrupo,
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'tac_GrupLiquidacion',
		selectOnFocus:	true,
		typeAhead: 		true,
		mode: 'remote',
		minChars: 3,
		triggerAction: 	'all',
		forceSelection: true,
		emptyText:'',
		allowBlank:     true,
		listWidth:      350
		,tabIndex:1009
	    });

    olGrupoLiq.on("select", function(pCmb, pRec, pIdx){
    	Ext.getCmp("txt_productor").setValue(pRec.data.cod);
    });

    var olNumLiq = {
		xtype:'textfield'
		,fieldLabel: 'Num Liq.'
		,name: 'tac_NumLiquid'
		,id: 'tac_NumLiquid'
		,readOnly:true
		,tabIndex:1011
	};

    var olCartonera = {
		xtype:'combo',
		fieldLabel: 'Cartonera',
		name: 'Cartonera',
		id: 'Cartonera',
		hiddenName:'tac_CodCartonera'
		,tabIndex:1018
    } ;

    var olEstado = new Ext.form.ComboBox({
		fieldLabel:'Estado',
		id:'txt_estado',
		name:'txt_estado',
		//width:150,
		store: dsCmbEstado,
		displayField:	'txt',
		valueField:     'cod',
		hiddenName:'tac_Estado',
		selectOnFocus:	true,
		typeAhead: 		true,
		mode: 'remote',
		minChars: 3,
		triggerAction: 	'all',
		forceSelection: true,
		emptyText:'',
		allowBlank:     false,
		listWidth:      250
		,value:1
		,tabIndex:1024
	});


    var olDigitado ={
		xtype:'textfield',
		fieldLabel: 'Digitador Por',
		name: 'tac_Usuario',
		id: 'tac_Usuario'
		,readOnly:true
	} ;

    var olFechaReg ={
		xtype:'textfield',
		hideLabel: true,
		name: 'tac_FecDigitacion',
		id: 'tac_FecDigitacion'
		,readOnly:true
	} ;

    var olDetalle = new Ext.grid.EditorGridPanel({
		labelWidth: 200,
		labelAlign: 'right',
		buttonAlign: 'left',
		width:700,
		height: 150,
		bodyStyle: "padding: 0px;",
		enableDragDrop: true,
		autoExpandColumn : 'opts',
		clicksToEdit: 1,
		stripeRows: true,
		ds: new Ext.data.SimpleStore({
			  fields: [
				'cola',
				'colb',
				'colc',
				'cold',
				'options'
			  ],
			  data :  [
				  [false, '1111111', 1111, 0, ''],
				  [true, '22222', 2222, 0, ''],
				  [false, '33333333', 3333, 0, '']
			  ]
		}),
		columns:
		[
		  {
			header: "first",width: 40,fixed: true,
			editor: new Ext.form.TextField({})
		  },
		  {header: "second",fixed: true,align: 'right',width: 100,
			editor: new Ext.form.TextField()
		  },
		  {header: "third",fixed: true,width: 60,
			editor: new Ext.form.TextField()
		  },
		  {header: "f",fixed: true,width: 60,
			editor: new Ext.form.TextField()
		  },
		  {header: "fv",id:'opts',fixed: false,
			editor: new Ext.form.TextField()
		  }
		],
		tbar: [{
		  text: 'add row',
		  handler : function(){
			  //do something
		  }
		}]
    });

    var estilo = 'background-color: #E7E7E7; font-size:4px; border-style: none; ';
    var pad = 'padding-left:10px';
    if(!Ext.getCmp("frmMant")){

	var frmMant = new Ext.form.FormPanel({
	    bodyStyle:estilo//'padding:5px',
	    ,width: 995
	    //defaults: {width: 230},
	    ,baseCls: 'x-small-editor'
	    ,border:true
	    ,frame:true
	    ,forceFit:true
	    ,id:'frmMant'
	    ,items:[
	    {
		    // column layout with 2 columns
		     layout:'column'
		    ,bodyStyle: estilo
		    ,defaults:{
			     columnWidth:0.32
			    ,layout:'form'
			    ,border:false
			    ,xtype:'panel'
			    //,collapsed: true
			    ,labelWidth:90
			    ,bodyStyle: estilo+pad
		    }
		    ,items:[{
			    columnWidth:0.36,
			    defaults:{anchor:'97%'}
			    ,items:[olAccion,olNumTarja,olFecha,olCorte,olGrupoLiq,olBodega,olContenedor]
		    },{
			    labelWidth:90
			    ,defaults:{anchor:'97%'}
			    ,items:[olEmpresa,olEmbarque,olHoraInicio,olPaletizado,olPtoEmbarque, olPiso]
		    },{
			defaults:{anchor:'97%'}
			,items:[olSemana,olHoraCierre,olZona,olNumLiq,olSello]
		      }
		    ]
	    },{
		    // column layout with 2 columns
		     layout:'column'
		    ,bodyStyle: estilo
		    ,defaults:{
			     columnWidth:0.30
			    ,layout:'form'
			    ,border:false
			    ,xtype:'panel'
			    ,labelWidth:90
			    ,bodyStyle: estilo+pad
		    }
		    ,items:[{
			    columnWidth:0.38,
			    defaults:{anchor:'97%'},
			    items:[olProductor, olTransporte, olPreEval]

		    },{
			    columnWidth:0.32
			    //,labelWidth:70
			    ,defaults:{anchor:'97%'}
			    ,items:[olHacienda, olTransportista, olCodEval]

		    },{
			labelWidth:55
			,defaults:{anchor:'97%'}
			,items:[olCartonera, olCalidad, olEstado]
		      }
		    ]
	    },{
		layout:'column'
		,bodyStyle: estilo
		,defaults:{
			columnWidth:0.5
		       ,layout:'form'
		       ,border:false
		       ,xtype:'panel'
		       ,labelWidth:90
		       ,bodyStyle: estilo+pad
	       }
	       ,items:[
		    {columnWidth:0.6, defaults:{anchor:'97%'}
		    ,items:[olObservacion]},
		    {columnWidth:0.4, defaults:{anchor:'97%'}
		    ,items:[
			{
			    layout:'column'
			    ,bodyStyle: estilo
			    ,labelWidth:90
			    ,defaults:{
				    columnWidth:0.5
				   ,layout:'form'
				   ,border:false
				   ,xtype:'panel'
				   ,bodyStyle: estilo
			   }
			   ,items:[
				{columnWidth:0.6, defaults:{anchor:'97%'}
				,items:[olDigitado]},
				{columnWidth:0.4, defaults:{anchor:'97%'}
				,items:[olFechaReg]}
			   ]
			}
		    ]}
	       ]
	    }
	    ],
	    buttons:[
		{
		    text:'Nuevo'
		    ,iconCls: 'iconNuevo'
		    ,disabled:add
		    ,id:'btnNuevo'
		    ,handler:function(){
					Ext.getCmp("btnGrabar").enable();
					fResetear();
					Ext.getCmp("frmMantWin").setTitle("Nueva Tarja");
					Ext.getCmp("frmMant").findById('accion').setValue('ADD');
					li.em.detgrid.getStore().load({init:22, params:{meta:false, tac_CodEmpresa:0, tad_NumTarja:-999}});
					li.em.detgrid.getStore().removeAll();
		}
		},{
		    text:'Guardar',
		    type:'submit',
		    id:'btnGrabar',
		    handler:fGrabar,
		    disabled:upd
		    ,iconCls: 'iconGrabar'
		},{
		    text:'Eliminar',
		    type:'submit',
		    id:'btEliminar',
		    disabled:del,
		    handler:fEliminarTodo
		    ,iconCls: 'iconBorrar'
		},
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
            title:'Mantenimiento de Tarjas',
            layout:'fit',
            pageX:200,
            pageY:70,
            width:1000,
            height:520,
            id: "frmMantWin",
            items:frmMant
        });
        win.show();



	if (!Ext.getCmp("grdTarjaDet")){
	  frmMant.add({
		 id: 'grdTarjaDet',
		 title: "",
		 layout:'fit',
		 closable: true,
		 collapsible:true,
		 autoLoad:{url:'LiEmTj_Tarjadetalle.php?'+Orden,		// Objeto a cargar
		   params:{
		   init:1
		   ,pPagina:0
		   ,pObj: 'grdTarjaDet'
			,tad_NumTarja: -999
		 }
		 ,scripts: true, method: 'POST'}
	  })
	}
	else {
	   var ilReg = Ext.getCmp("tar_NumTarja").getValue();
	   li.em.detgrid.getStore().load({init:1, params:{tad_codEmpresa:0, tad_NumTarja:ilReg, meta:false, zzz:666}});
	}
	 frmMant.doLayout()
    }
}

gaHidden = new Array();

function fGrabar(){

    if (!Ext.getCmp("frmMant").getForm().isDirty()) {
	    Ext.Msg.alert('AVISO', 'Los datos no han cambiado. <br>No tiene sentido Grabar');
	    return false;
    }
    if (!Ext.getCmp("frmMant").getForm().isValid()) {
	    Ext.Msg.alert('ATENCION!!', 'Hay Informacion incompleta o invalida');
	    return false;
    }
    var olModif = Ext.getCmp("frmMant").getForm().items.items;

    oParams = {};
    olModif.collect(function(pObj, pIdx){
    switch (pObj.getXType()) {
	case "datefield":
	    eval("oParams." + pObj.id + " = '" +  pObj.getValue().dateFormat(pObj.format) + "'" );
	    break
	case "combo" :
	    eval("oParams." + pObj.hiddenName + " = '" +  pObj.getValue() + "'" );
	    break;
	default:
	    eval("oParams." + pObj.id + " = '" +  pObj.getValue() + "'" );
    }
    })
    //debugger;
    ilId  = Ext.getCmp("frmMant").findById('tar_NumTarja').getValue();   //usa un valor ya asignado
    /*if  (ilId > 0) {
	    oParams.cnt_ID = ilId;
	    var slAction='UPD';
    }	else var slAction='ADD';*/
    ilAcc  = Ext.getCmp("frmMant").findById('accion').getValue();   //usa un valor ya asignado
    if  (ilAcc =='UPD') {
	    oParams.cnt_ID = ilId;
	    var slAction='UPD';
    }	else var slAction='ADD';
    var slParams  = Ext.urlEncode(oParams);
    slParams += "&" +  Ext.urlEncode(gaHidden);
    Ext.getCmp("frmMant").disable();
    //Ext.Msg.alert('AVISO', 'Grabar 22');
    fGrabarRegistro(Ext.getCmp("frmMant"), slAction, oParams, slParams);

    Ext.getCmp("frmMant").enable();
};

/************************************/
/* Funcion para cargar el
 * orden detalles
 * 29/03/10			                */
/************************************/

function fOrdenDetalles()
{
    var olDat = Ext.Ajax.request({
		url: 'LiEmTj_CamposValidadores'
		,callback: function(pOpt, pStat, pResp){
		    if (true == pStat){
			 olRsp = eval("(" + pResp.responseText + ")");
			if (olRsp.info.msg == "-"){
			    Orden='pOrd=1';
			}else{
			    Orden=olRsp.info.msg;
			}
			VerMantTarja();
			return;
		    }
		}
		,params: {pOpc: 4
			}
	    });
}
/************************************/
/* Funcion para cargar los
 * dataos de validacion
 * 29/03/10			                */
/************************************/

function fCargarValidador()
{
    var ilSeman= Ext.getCmp("frmMant").form.findField("tac_Semana").getValue();
    var ilCorte = Ext.getCmp("frmMant").form.findField("tac_Corte").getValue();
    var olDat = Ext.Ajax.request({
		url: 'LiEmTj_CamposValidadores'
		,callback: function(pOpt, pStat, pResp){
		    if (true == pStat){
				olRsp = eval("(" + pResp.responseText + ")");
			   if (olRsp.info.length == 0){
				   alert('No hay datos para comparar');
				   return false;
			   }else{
				   Validaciones['tad_Calidad']=[];
				   Validaciones['tad_Calidad']['max']=olRsp.info.cco_MaxCalidad;
				   Validaciones['tad_Calidad']['min']=olRsp.info.cco_MinCalidad;
				   Validaciones['tad_Peso']=[];
				   Validaciones['tad_Peso']['max']=olRsp.info.cco_MaxPeso;
				   Validaciones['tad_Peso']['min']=olRsp.info.cco_MinPeso;
				   Validaciones["tad_Largo"]=[];
				   Validaciones["tad_Largo"]['max']=olRsp.info.cco_MaxLargo;
				   Validaciones["tad_Largo"]['min']=olRsp.info.cco_MinLargo;
				   Validaciones["tad_NumDedos"]=[];
				   Validaciones["tad_NumDedos"]['max']=olRsp.info.cco_MaxDedos;
				   Validaciones["tad_NumDedos"]['min']=olRsp.info.cco_MinDedos;
				   Validaciones["tad_ClusCaja"]=[];
				   Validaciones["tad_ClusCaja"]['max']=olRsp.info.cco_MaxClusters;
				   Validaciones["tad_ClusCaja"]['min']=olRsp.info.cco_MinClusters;
				   return true;
			   }
		    }
		}
		,params: {pSem: ilSeman,
				  pCor: ilCorte,
			      pOpc: 1
			}
	    });
}


function fGrabarBitacora(NumTarja,Accion)
{
	Ext.Ajax.request({
	    waitMsg: 'Grabando...',
	    url:	'LiEmTj_Bitacora',
	    method: 'POST',
	    params: {pNumTarja: NumTarja,pAccion:Accion},
	    success: function(response,options){
           var responseData = Ext.util.JSON.decode(response.responseText);
	    },
	    failure: function(form, e) {
	      if (e.failureType == 'server') {
	        slMens = 'La operación no se ejecutó. ' + e.result.errors.id + ': ' + e.result.errors.msg;
	      } else {
	        slMens = 'El comando no se pudo ejecutar en el servidor';
	      }
	      Ext.Msg.alert('Proceso Fallido', slMens);
	    }
	  }//end request config
); //end request
}

function fGrabarRegistro(fmForm, slAction, oParams, slParams){
    //debugger;
  oParams.pTabla = 'liqtarjacabec';
  Ext.Ajax.request({
    waitMsg: 'GRABANDO...',
    url:	'../Ge_Files/GeGeGe_generic.crud.php?pAction=' + slAction, //-------------    +'&' + slParams,
    method: 'POST',
    params: oParams,
    success: function(response,options){
      var responseData = Ext.util.JSON.decode(response.responseText);
      if (false == responseData.success){
			slMens = responseData.message;
      }
      else
      {
    	  //debugger;
	      switch (slAction) {
	        case "ADD":
	          slMens = 'Registro Creado';
			  Ext.getCmp("frmMant").findById('accion').setValue('UPD');
			  slMens1='ING: E: R:'+oParams.tar_NumTarja+' F:'+oParams.tac_Fecha+ 'S:'+oParams.tac_Semana;
	          //Ext.getCmp("frmMant").findById('tar_NumTarja').setValue(responseData.lastId);
	          //fCargaReg(Ext.getCmp("frmMant").findById('tar_NumTarja').getValue()); //solo por prueba se quito
	          break;
	        case "UPD":
	          slMens = 'Registro Actualizado';          //fCargaReg(Ext.getCmp("frmMant").findById('tar_NumTarja').getValue());//solo por prueba se quito
	          slMens1='Detalles Modificados CAB. R:'+oParams.tar_NumTarja+ 'S:'+oParams.tac_Semana;
	          break;
	      }
	      fGrabarBitacora(Ext.getCmp("frmMant").findById('tar_NumTarja').getValue(),slMens1);
		  fGrabar1(slAction);
      }
      Ext.Msg.alert('AVISO ', slMens);
    },
    failure: function(form, e) {
      if (e.failureType == 'server') {
        slMens = 'La operaci�n no se ejecut�. ' + e.result.errors.id + ': ' + e.result.errors.msg;
      } else {
        slMens = 'El comando no se pudo ejecutar en el servidor';
      }
      Ext.Msg.alert('Proceso Fallido', slMens);
    }
  }//end request config
  ); //end request
}; //end updateDB

function fEliminarTodo()
{
	ilId  = Ext.getCmp("frmMant").findById('tar_NumTarja').getValue();
	Ext.Ajax.request({
		    waitMsg: 'Eliminando...',
		    url:	'LiEmTj_EliminarTarja',
		    method: 'POST',
		    params: {numTarja: ilId},
		    success: function(response,options){
	           var responseData = Ext.util.JSON.decode(response.responseText);
	           if (false == responseData.success)
		  		        Ext.Msg.alert('ERROR', 'Registro No Elimnado');
		  		else
		  		{
		  			    msj='ELI. E: R:'+ilId+' F:'+Ext.getCmp("frmMant").findById('tac_Fecha').getValue();
	  				    fGrabarBitacora(ilId,msj);
		  		    	Ext.Msg.alert('AVISO ', 'Registro Elimnado');
	           			Ext.getCmp("frmMantWin").close();
		  		}
		    },
		    failure: function(form, e) {
		      if (e.failureType == 'server') {
		        slMens = 'La operaci�n no se ejecut�. ' + e.result.errors.id + ': ' + e.result.errors.msg;
		      } else {
		        slMens = 'El comando no se pudo ejecutar en el servidor';
		      }
		      Ext.Msg.alert('Proceso Fallido', slMens);
		    }
		  }//end request config
   ); //end request
}
function fValidarSemana1()
{
    var olDat = Ext.Ajax.request({
		url: 'LiEmTj_CamposValidadores'
		,callback: function(pOpt, pStat, pResp){
		    if (true == pStat){
			 olRsp = eval("(" + pResp.responseText + ")");
					if (olRsp.info.msg == "-"){
						 Ext.Msg.alert('AVISO', 'Error: Periodo Cerrado');
						 Ext.getCmp("tac_Semana").markInvalid();
						 Ext.getCmp("btnGrabar").disable();
						 Ext.getCmp("btnEliminar").disable();
						 Ext.getCmp("btEliminar").disable();
						 Ext.getCmp("btnAdd").disable();
						 return false;
					}
					else
					{
					     fAgregar();
					    Ext.getCmp("btnAdd").enable();
					    Ext.getCmp("btnEliminar").enable();
						Ext.getCmp("btEliminar").enable();
						 Ext.getCmp("btnGrabar").enable();
						 Ext.getCmp("frmMant").findById("tar_NumTarja").clearInvalid();
						 Ext.getCmp("frmMant").getForm().clearInvalid()
					     Ext.getCmp("tac_Semana").clearInvalid();
					     return true;
					}
		    }
		}
		,params: {pSem: Ext.getCmp("frmMant").form.findField("tac_Semana").getValue()
			,pOpc: 2
			}
	    });
}

function fValidarSemana()
{
    var olDat = Ext.Ajax.request({
		url: 'LiEmTj_CamposValidadores'
		,callback: function(pOpt, pStat, pResp){
		    if (true == pStat){
			 olRsp = eval("(" + pResp.responseText + ")");
					if (olRsp.info.msg == "-"){
						 Ext.Msg.alert('AVISO', 'Error: Periodo Cerrado');
						 Ext.getCmp("btnGrabar").disable();
						 Ext.getCmp("btnEliminar").disable();
						 Ext.getCmp("btEliminar").disable();
						 Ext.getCmp("btnAdd").disable();
						 Ext.getCmp("tac_Semana").markInvalid();
						 return false;
					}
					else
					{
					     Ext.getCmp("tac_Semana").clearInvalid();
					     fValidarTarjaLiqui();
					     return true;
					}
		    }
		}
		,params: {pSem: Ext.getCmp("tac_Semana").getValue()
			,pOpc: 2
			}
	    });
}
function fValidarTarjaLiqui()
{
	var NumTarja=Ext.getCmp("tar_NumTarja").getValue();
	if(!NumTarja)
		NumTarja=0;
    var olDat = Ext.Ajax.request({
		url: 'LiEmTj_CamposValidadores'
		,callback: function(pOpt, pStat, pResp){
		    if (true == pStat){
			 olRsp = eval("(" + pResp.responseText + ")");
					if (olRsp.info.contador != 0 ){
						 upd=true;
						 del=true;
						 Ext.getCmp("btnEliminar").disable();
						 Ext.getCmp("btEliminar").disable();
						 Ext.getCmp("btnGrabar").disable();
						 Ext.getCmp("btnAdd").disable();
						 Ext.Msg.alert('AVISO', 'Error: Tarja Liquidada o en proceso');
						 return false;
					}
					else
					{
						//fCargarValidador();
					     return true;
					}
		    }
		}
		,params: {Tarja: NumTarja
			,pOpc: 3
			}
	    });
}
function hoy()
{
 var fechaActual = new Date();
    dia = fechaActual.getDate();
    mes = fechaActual.getMonth() +1;
    anno = fechaActual.getYear();
    if (dia <10) dia = "0" + dia;
    if (mes <10) mes = "0" + mes;
    if (anno < 1000) anno+=1900;
    fechaHoy = dia + "/" + mes + "/" + anno;
    return fechaHoy;
}
function fResetear()
{
  Ext.getCmp("frmMant").findById('tar_NumTarja').reset();
  Ext.getCmp("frmMant").findById('tac_Transporte').reset();
  Ext.getCmp("frmMant").findById('tac_RefTranspor').reset();
  Ext.getCmp("frmMant").findById('txt_GrupLiquidacion').reset();
  Ext.getCmp("frmMant").findById('txt_productor').reset();
  Ext.getCmp("frmMant").findById('tac_Observaciones').reset();

    Ext.getCmp("frmMant").findById('txt_zona').reset();
    Ext.getCmp("frmMant").findById('tac_ResCalidad').reset();
    Ext.getCmp("frmMant").findById('tac_Hora').reset();
    Ext.getCmp("frmMant").findById('tac_HoraFin').reset();
}
