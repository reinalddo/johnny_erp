/**
 * Grid based on a directStore, preconfigured to
 *@author fah
 *@created	12/12/2010
 *@rev	fah	01/02/2011	Added config options: hideButtons TRUE/FALSE to hide default buttons
  *@rev	fah	01/02/2011	Added config options: ignoreButtons Comma separated list of ids for buttons must be hidde
 */
Ext.ns("Ext.ux.app");
Ext.reg("jsonReader", Ext.data.JsonReader);
Ext.ux.app.getFieldsFromCM = function(pArr){
            var alRes = new Array();
            //var alCol = olColModel.config;
            Ext.each(pArr, function(pEl){
                var slTipo = "";
                if(!pEl.type) pEl.type = "string";
                if(!pEl.dataIndex && pEl.name) pEl.dataIndex = pEl.name;
                if (pEl.name && pEl.type){
                    var alEl = {name:pEl.name}
                    switch (alEl.type ){
			        case "int":
			        case "float":
						case "number":
						value=0;
                    case "date":
                        alEl.dateFormat = "Y-m-d";
                        pEl.type = "date"
						pEl.value= new Date().format("Y-m-d")
                        break;
                    case "datetime":
                        alEl.dateFormat = "Y-m-d H:i:s";
                        pEl.type = "date"
						pEl.value = new Date().format("Y-m-d H:i:s")
                        break;
                    default:
                        alEl.type = pEl.type || "string"
						value="";
                        break;
                    }
                alRes.push(alEl);
                }
            });
            return alRes;
}
Ext.ux.app.directGrid = Ext.extend(Ext.grid.EditorGridPanel,{
	/**
	 * @var	 atoLoad   bool		Flag to force loading data after render
	 */
	loadOnRender:	true
    ,initComponent: function(){
        this.currentRecord=false;		// last selected record  Index
		this.currentCellIdx = null;	    // last selected cell index
		this.currentCell    = null;		// last selected cell object
        this.combos = [];
        var pConfig = this.initialConfig; /// @TODO probar scope de esta variable
        pConfig.editable= Ext.isDefined(pConfig.editable) ? pConfig.editable : true; // defaults to an editable grid
        /*****************************/
		//var alCols = Ext.ux.util.clone_obj(this.initialConfig.columns || this.initialConfig.colCnfg || [])
		var alCols = (this.initialConfig.columns || this.initialConfig.columnModel|| this.initialConfig.colCnfg ) ?
			this.initialConfig.columns || this.initialConfig.colCnfg  || this.initialConfig.columnModel:
			this.getColumnsCnfg();

        var olColModel = new Ext.grid.ColumnModel({
            defaults:	pConfig.defaults ||{}
            ,columns: 	alCols
        });
        pConfig.columnModel =olColModel;
        for (var i = 0, len = olColModel.config.length; i < len; i++) {
            var c = olColModel.config[i];

            if (!Ext.isEmpty(olColModel.config[i])) {
                //olColModel.config[i].id = (olColModel.config[i].name?olColModel.config[i].name:i);
                if (!olColModel.config[i].dataIndex)  olColModel.config[i].dataIndex = olColModel.config[i].name;
                var ilMinW = 40;
				Ext.applyIf(olColModel.config[i], pConfig.defaults ||{})
                olColModel.config[i].editable = (olColModel.config[i].editable == undefined  && !olColModel.config[i].readOnly) ? pConfig.editable : olColModel.config[i].editable;
                if (!olColModel.config[i].editor && (!olColModel.config[i].readOnly)){
                    switch (olColModel.config[i].type || "string"){
                    case "int":
                        ilMinW = 40;
                        if (olColModel.config[i].editable) olColModel.config[i].editor = new Ext.form.NumberField()
                        break;
                    case "float":
                        ilMinW = 65;
                        if (olColModel.config[i].editable) olColModel.config[i].editor = new Ext.form.NumberField()
                        break;
                    case "date":
                        ilMinW = 85;
						olColModel.config[i].dbDateFormat =  "Y-m-d";
                        olColModel.config[i].renderer = fRenderDate
                        if (olColModel.config[i].editable) olColModel.config[i].editor = new Ext.form.DateField({xtype: "datefield",  format:"Y-m-d", altFormats: app.gen.dateFmts})
                        break;
                    case "datetime":
						olColModel.config[i].dbDateFormat =  "Y-m-d h:i:s";
                        ilMinW = 110;
                        olColModel.config[i].renderer = fRenderDate
                        if (olColModel.config[i].editable) olColModel.config[i].editor = new Ext.form.DateField({xtype: "datefield", format:"Y-m-d h:i:s", format:app.gen.dateTimeFmt, altFormats: app.gen.dateTimeFmts})
                        break;
                    default:
                        ilMinW = 50;
                        if (olColModel.config[i].editable) olColModel.config[i].editor = new Ext.form.TextField()
                    }
                }
				olColModel.config[i].ownerGrid = this;			// pointer to the containig grid of this column
				if(olColModel.config[i].editor) olColModel.config[i].editor.ownerGrid = this;
                if(!olColModel.config[i].width) olColModel.config[i].width = ilMinW;
                if(olColModel.config[i].editor &&
                   olColModel.config[i].editor.xtype && (                   // Genera lista de combos
                    olColModel.config[i].editor.xtype == "genCmbBox" ||
                    olColModel.config[i].editor.xtype == "combo" ||
                    olColModel.config[i].editor.xtype == "combobox"
                    ))
                    this.combos.push(olColModel.config[i].id)
            }
            olColModel.config[i].filter={};
        } //eof for

        Ext.each(this.combos, (function(pCmb){
            var olCol = olColModel.getColumnById(pCmb) // this.grid.getColumnModel().getColumnById(pCmb)
            if(olCol && !olCol.renderer){
				olCol.renderer = Ext.ux.renderer.Combo(olCol)
				if (olCol.editor.preLoad) olCol.editor.getStore().load();             // Initial Load of Combos Store to display correct data at render time
			}
        }).createDelegate(this))

		 var olReader = new Ext.data.JsonReader(Ext.applyIf(this.initialConfig.store.reader ||{}, { // Apli defaults to Reader
            root: 'data'
			,paramsAsHash: true
			,totalProperty: 'total'
            ,fields: Ext.ux.app.getFieldsFromCM(pConfig.columnModel.columns)
        }))

		if (this.initialConfig.editable) {
			this.initialConfig.store.writer = new Ext.data.JsonWriter(Ext.applyIf(this.initialConfig.store.writer || {},  { // Apli defaults to Writer
				encode: false
				,paramsAsHash: true
				,writeAllFields:  true
				,listful: true
			}))
		}

        this.initialConfig.bodyStyle= 'background:transparent !important'; //'padding:15px;background:transparent';
        this.initialConfig.store.reader = olReader
		this.initialConfig.store = Ext.create(Ext.applyIf(this.initialConfig.store,{
			remoteSort: 	true
			,listeners:{
				beforewrite : function( pStr, pActn, pRs, pOpt, pArg){
					app.saveMask.show()
				}.createDelegate(this)
				,write : function( pStr, pActn, pTrn, pRs){
					app.saveMask.hide()
					//if(app.loadMask) app.loadMask.hide();
					//switch(pAct ){
					//	case "create":
					//			pStr.loadData(pRs, false);
					//	break;
					//}
				}.createDelegate(this)

				}}), "directstore");

		if (this.initialConfig.store.proxy.api.read == undefined &&   // Compatibility with early versions
			this.initialConfig.store.apiProvider ){
			var olProv = this.initialConfig.store.apiProvider;
			this.initialConfig.store.proxy.api =  {
					create:     olProv.create  || undefined
					,read:      olProv.getList || undefined
					,update:    olProv.update  || undefined
					,destroy:   olProv.destroy || undefined
				}
		}

        if ((this.initialConfig.bbar && this.initialConfig.bbar != false)  ||
			undefined == this.initialConfig.bbar ) {
			this.initialConfig.bbar = Ext.apply(
			{
				xtype:			"paging"
				,pageSize: 		40
				,displayInfo: 	true
				,plugins: 		new Ext.ux.ProgressBarPager()
				,store:			this.initialConfig.store
			},this.initialConfig.bbar);
		}
        this.initialConfig.colModel =  pConfig.columnModel;

        this.filtersConfig(this, olColModel);
        var alPlugins = this.initialConfig.columnFilters || true? [new app.gen.preConfigFilterRow]
						: [];
		this.initialConfig.plugins 	= (this.initialConfig.plugins?
									   this.initialConfig.plugins.concat(alPlugins) : alPlugins);
		this.initialConfig.tbar 	= this.buildDefaultButtons();

        var config = {
            frame:			this.initialConfig.frame ||false
            ,title:			pConfig.title || "*"
            ,closable: 		true
			,stripeRows: 	this.initialConfig.stripeRows ||true
            ,header: 		this.initialConfig.header ||false
            ,sm: 			this.initialConfig.sm || new Ext.grid.RowWithCellSelectionModel() // new Ext.grid.RowWithCellSelectionModel() //----
            ,plugins: 		this.initialConfig.plugins
			,loadOnRender:	true
            ,onRender:		function() {
                Ext.ux.app.directGrid.superclass.onRender.apply(this, arguments);
				if (this.loadOnRender && false !== this.store.autoLoad)
					this.store.load();
            }
        }
        Ext.apply(this, Ext.applyIf(this.initialConfig, config));
        Ext.ux.app.directGrid.superclass.initComponent.apply(this, arguments) // arguments);
		// Define a clean Record
		this.loadMask = new Ext.LoadMask( Ext.getBody(), {msg:"Cargando Datos...", store: this.getStore()})

		this.on('cellclick',
			function(pGrid, pRIdx, pCIdx, pEvt){
				this.currentRecord = pRIdx
				if ("number" == typeof pCIdx){
					this.currentCellIdx = pCIdx
					this.currentCell    = this.getColumnModel().getColumnAt(pCIdx)
				}
			}
		)
		if (!this.editable){
			//this.on('celldblclick',
			//	function( pGrid, pRow, pCol, pEvt){
			//		console.log("cdb");
			//		this.getSelectionModel().selectRow(pRow);
			//		//this.dataWin.createDelegate(this)();
			//	})
			this.on('rowdblclick',
				function( pGrd, pIdx, pEvt){
					this.currentRecord = pIdx
					this.getSelectionModel().selectRow(pIdx);
					this.dataWin.createDelegate(this)(pEvt);
				})
			}

		this.store.defaultRecord={}
		this.getStore().fields.each(function(pItem, pIdx, pLong){
			this.store.defaultRecord[pItem.name] = pItem.name.defaultValue ||"";
			}, this)

    }
    ,defaults: {
        sortable: true,
        //menuDisabled: true,
        width: 100
    }
	,addRow: function(){}
	,deleteRows: function(){
		var olSm = this.getSelectionModel()
		if (olSm.getCount() > 0){
			var olData = olSm.getSelections() || false
		}
		else var olData = olSm.selection.record || false
		if (!olData){
			Ext.Msg.show({
			title:	'Eliminación de  Registros',
			msg: 	'Antes de proceder, DEBE seleccionar el (los) registros a Eliminar',
			buttons: Ext.Msg.OK
		})
		}
		Ext.Msg.show({
			title:	'Eliminación de  Registros',
			msg: 	'Esta acción eliminará definitivamente los registros seleccionados.<br>Realmente desea continuar?',
			buttons: Ext.Msg.YESNO,
			fn: 	function(pBtn, pText){
				if (pBtn == "YES" || "SI" ){
					this.store.remove(olData);
					this.store.save();
				}
			}.createDelegate(this)
		});
	}
    ,buildDefaultButtons: function(){
        var defaultButtons = [
			{ text: "   ",
				width:100, disabled:true,
				handler: Ext.emptyFn()
			}
            ,{  name:     'btnADD'
                ,text: 'AGREGAR'
                ,tooltip: 'HAbilita el ingreso de un nuevo registro'
                ,cls:'x-btn-text-icon'
                ,icon: 'Images/famfam/add.png'
				//,disabled:	this.enableFunc("btnADD")
                ,handler : this.addRow.createDelegate(this)
				,hidden : this.initialConfig.hideButtons || false
            },{name:     'btnSAV'
                ,text: 'GRABAR'
                ,tooltip: 'Graba los datos ingresads, modificados ó eliminados'
                ,cls:'x-btn-text-icon'
                ,icon: 'Images/famfam/accept.png'
                ,disabled: true
                ,handler: function() {
					//this.loadMask.show()
					if (this.editing) this.stopEditing();
                    this.store.save()
                }
                ,scope: this
				,hidden : this.initialConfig.hideButtons || false
            },{name:     'btnRFR'
                ,text: 	 'REFRESCAR'
                ,tooltip: 'Recarga la informacion'
                ,cls:	  'x-btn-text-icon'
                ,icon:    'Images/famfam/cog.png'

                ,handler: function() {
                    this.store.load()
                }
                ,scope: this
				,hidden : this.initialConfig.hideButtons || false
            },{
                name:     	'btnDEL'
                ,text:   	'ELIMINAR'
                ,tooltip: 	'Borra los registros marcados'
                ,cls:		'x-btn-text-icon'
                ,icon: 		'Images/famfam/application_delete.png'
                ,disabled:	true
                ,handler: 	this.deleteRows
                ,scope: 	this
				,hidden : 	this.initialConfig.hideButtons || false
            },{name:     	'btnFTR'
                ,text: 		'FILTRAR...'
                ,tooltip: 	'Define y Aplica condiciones de busqueda para los datos. Segun criterios libres'
                ,cls:		'x-btn-text-icon'
                ,icon: 		'Images/filter16.ico' //filter.png'
                ,handler: 	function(){
                  this.ownerCt.ownerCt.filterDialog.show();
                }
				,hidden : this.initialConfig.hideButtons || false
            }
        ]
        if (typeof this.initialConfig.buildButtons == "function" ) {
            var alBtns =this.initialConfig.buildButtons.createDelegate(this)()
            Ext.each(alBtns, function(olBtn){
                defaultButtons.push(olBtn)}
            )
        }
        if (this.initialConfig.editable){
            var ilIdx = defaultButtons.findIndexByCol("btnSAV","name")
            if (ilIdx >=0)  defaultButtons[ilIdx].disabled=false;
            var ilIdx = defaultButtons.findIndexByCol("btnDEL","name")
            if (ilIdx >=0)  defaultButtons[ilIdx].disabled=false;
        }
		else {
			defaultButtons.splice(defaultButtons.findIndexByCol("btnSAV","name"),1)
			defaultButtons.splice(defaultButtons.findIndexByCol("btnDEL","name"),1)
		}
        if (this.initialConfig.ignoreBtns){
			alIgn = this.initialConfig.ignoreBtns.split(",")
			//alIgn.each(function(pItem){
			//	defaultButtons.splice(defaultButtons.findIndexByCol(pItem,"name"),1)
			//})
			for  (var k = 0; k< alIgn.length;k ++){
				defaultButtons.splice(defaultButtons.findIndexByCol(alIgn[k],"name"),1)
			}

		}
		if (this.noBtns){
			return []
		}
		else
        return defaultButtons;
    }

    /**
     *  Returns an array with a normalized filter for the fields containing names and editors.
     *  Generates a default textfield filter if no editor configuration.
     *  To disable filtering the "filter" property must be set on false
     *  @param  array   pArr    Array of fields config
     */
    ,getFiltersFromCM: function(pArr){
        var alRes = new Array();
        //var alCol = olColModel.config;
        Ext.each(pArr, function(pEl){
            var slTipo = "";
            if (pEl.name && !(pEl.filter && pEl.filter == false)){
                //var alEl =pEl.editor|| pEl.filter;
                var alEl = {}
                alEl.id = "_fltr_" + pEl.name;
                alEl.label = pEl.header
                alEl.type = pEl.type || "string"
                switch (alEl.type ){
                case "date":
                    alEl.format = "Y-m-d";
                    break;
                case "datetime":
                    alEl.format = "Y-m-d h:i:s";
                    break;
                default:
                    break;
                }

                alEl = Ext.apply(alEl, pEl.filter)
                alRes.push(alEl);
            }
        })
        return alRes;
    }
    ,filtersConfig: function(pGrid, pColModel){
        var filterCfg=this.getFiltersFromCM(pColModel.columns);
        var fieldManager=   new Ext.ux.netbox.core.FieldManager(filterCfg);
        pGrid.filterModel =   new Ext.ux.netbox.core.FilterModel(fieldManager);
        //localFilterResolver=new Ext.ux.netbox.core.LocalStoreFilterResolver(filterModel);
      //--------------------------------------------------------------------------------------
      // QuickFilter
        var quickFilter= new Ext.ux.netbox.core.QuickFilterModelView({
            filterModel: pGrid.filterModel
        });

        quickFilter.on("filterChanged",pGrid.filterApply);

		this.contextMenuManager=new Ext.ux.netbox.ContextMenuManager({menu: {items:[quickFilter.getFilterMenu(),quickFilter.getRemoveFilterMenu()]}});
        var button2=new Ext.Button({text: "Aplicar"});
        button2.on("click",pGrid.filterApply.createDelegate(this));

        pGrid.filterDialog = new Ext.Window({
              title: 'Filters',
              width:600,
              height:350,
              layout: 'border',
              closeAction: 'hide',
              items: [{ filterModel: pGrid.filterModel,
                 region: 'center',
                 xtype: 'dynamicFilter',
                 buttons: [button2]
              }]
            });
        pGrid.filterDialog.on('beforeshow', (pGrid.onShowFilterDialog).createDelegate(this));
        pGrid.filterDialog.on('beforehide', (pGrid.onCloseFilterDialog).createDelegate(this));

      //--------------------------------------------------------------------------------------
      // Preference Manager
        pGrid.prefManager=new Ext.ux.netbox.PreferenceManager({
            id: 'prefManagerId',
            userName: 'user',
            getFn: pGrid.provaGetFunc.createDelegate(this),
            setFn: pGrid.provaSetFunc.createDelegate(this),
            fnScope: this,
            getAllPrefURL:'http://getAllPrefURL',
            applyDefaultPrefURL:'http://applyDefaultPrefURL',
            loadPrefURL:'http://loadPrefURL',
            savePrefURL:'http://savePrefURL',
            deletePrefURL:'http://deletePrefURL'
        });

        var prefManagView = new Ext.ux.netbox.PreferenceManagerView({preferenceManager: pGrid.prefManager});
        //@TODO: Preferences
        //pGrid.getTopToolbar().add({text: 'Preference', menu: pGrid.prefManagView});

        //pGrid.prefManager.applyDefaultPreference();
      //--------------------------------------------------------------------------------------

    },

    onShowFilterDialog: function(){
      this.filterBackup = this.filterModel.getFilterObj();
    }

    , onCloseFilterDialog: function(){
      if(Ext.util.JSON.encode(this.filterBackup)!=Ext.util.JSON.encode(this.filterModel.getFilterObj())){
        Ext.MessageBox.confirm('Confirmar', 'El filtro no se aplico. Que desea hacerlo ahora?', (this.whatDoYouWantToDo).createDelegate(this));
      }
    }
    , whatDoYouWantToDo: function (btn){
      if(btn=='yes')
        this.filterApply();
      if(btn=='no')
        this.filterModel.setFilterObj(this.filterBackup);
    }
    ,provaGetFunc: function (){
      return({grid:this.getState(),filter: this.filterModel.getFilterObj()});
    }

    ,provaSetFunc: function(pref){
      if(pref.filter){
        this.filterModel.setFilterObj(pref.filter);
      }
      if(pref.grid){
        this.getView().userResized=true;
        this.applyState(pref.grid);
        this.getColumnModel().setConfig(this.getColumnModel().config);
      }
      this.filterApply();
    }
    , filterApply: function(){
        this.getStore().setBaseParam("filter", this.filterModel.getFilterObj());
        this.getStore().load();
        this.filterBackup = this.filterModel.getFilterObj();
    }
});
Ext.reg("Ext.ux.app.directGrid", Ext.ux.app.directGrid);
//eof Ext.ux.app.directGrid ---------
