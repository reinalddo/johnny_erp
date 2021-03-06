//Ext.ux = {};
/*Ext.ux.AutoGridClass = function(cnfg){
    if (cnfg.isEditor && cnfg.isEditor == true)
        return Ext.extend(Ext.grid.GridPanel(cnfg));
    else Ext.extend(Ext.grid.EditorGridPanel(cnfg));
}
Ext.ux.AutoGridPanel = function(cnfg){
    return Ext.extend(Ext.grid.AutogridClass, {   //fAH

*/

Ext.ux.AutoGridPanel = Ext.extend(Ext.grid.GridPanel, {
        initComponent : function(){
            if(this.columns && (this.columns instanceof Array)){
                this.colModel = new Ext.grid.ColumnModel(this.columns);
                delete this.columns;
            }
            // Create a empty colModel if none given
            if(!this.colModel) {
                this.colModel = new Ext.grid.ColumnModel([]);
            }
            Ext.ux.AutoGridPanel.superclass.initComponent.call(this);
            // register to the store's metachange event
            if(this.store){
                this.store.on("metachange", this.onMetaChange, this);
            }
            // Store the column model to the server on change
            if(this.autoSave) {
                this.colModel.on("widthchange", this.saveColumModel, this);
                this.colModel.on("hiddenchange", this.saveColumModel, this);
                this.colModel.on("columnmoved", this.saveColumModel, this);
                this.colModel.on("columnlockchange", this.saveColumModel, this);            
            }     
        },    
       onMetaChange : function(store, meta) {
            // console.log("onMetaChange", meta.fields);
            // loop for every field, only add fields with a header property (modified copy from ColumnModel constructor)
            var c;
            var config = [];
            var lookup = {};
            config[config.length] = new Ext.grid.RowNumberer();
            if (!Ext.isEmpty(this.cnfSelMode)) {
                var sm= new Ext.grid.RowSelectionModel();
                switch (this.cnfSelMode) {
                    case "csms":    // checkbosSelection model single
                    case "csm":     // checkbosSelection model 
                        config[config.length] = new Ext.grid.CheckboxSelectionModel();
                        sm = new Ext.grid.RowSelectionModel({singleSelect:true});
                        break;
                    case "csmm":    // checkbosSelection model multiple
                        config[config.length] = new Ext.grid.CheckboxSelectionModel();
                        //this.selModel = new Ext.grid.RowSelectionModel();
                        this.sm= config[config.length];
                        break;
                    case "rsm":     // rowSelection model 
                        this.selModel = new Ext.grid.RowSelectionModel();
                        break;
                    case "cel":     // cellSelection model multiple
                        this.selModel = new Ext.grid.CellSelectionModel();
                        break;
                    case "rsms":    // rowSelection model single
                        this.selModel = new Ext.grid.RowSelectionModel({singleSelect:true});
                        break;
                    default:
                        sm=new Ext.grid.RowSelectionModel();
                }
            }
            for(var i = 0, len = meta.fields.length; i < len; i++)
            {
                c = meta.fields[i];
                if(c.header !== undefined){                
                    if(typeof c.dataIndex == "undefined"){
                        c.dataIndex = c.name;
                    }
                    if(typeof c.id == "undefined"){c.id = 'c' + i;}
                    if(c.editor && c.editor.isFormField){
                        c.editor = new Ext.grid.GridEditor(c.editor);}
                    c.sortable = true;              
                    //delete c.name;
                    if(typeof c.renderer == "string") {
                        c.renderer = Ext.util.Format[c.renderer]}
                    /*else {
                        if(typeof c.renderer == "undefined"){
                            switch(c.type){
                                case "int":
                                    if(typeof(fRendInteger)=="function") c.renderer = Ext.util.Format("intSimple");
                                    break;
                                case "float":
                                    if(typeof(fRendQuantity)=="function") c.renderer = Ext.util.Format("float2Dec");
                                    break;
                                default:
                                    break;
                            }
                        }
                    }*/
                    config[config.length] = c;
                    lookup[c.id] = c;                
                }
            }
            // Store new configuration
            this.colModel.config = config;  
            this.colModel.lookup = lookup;  
            this.sm = sm;
            // Re-render grid
            if(this.rendered){
                this.view.refresh(true);
            }
            //if(!this.view.hmenu.items.keys.include("reset")) //fah 30/04/08  
            //    this.view.hmenu.add({id:"reset", text: "Reset Columns", cls: "xg-hmenu-reset-columns"});
     
            var cm = new Ext.grid.ColumnModel(config);
            //this.reconfigure(this.dataSource, cm);
            this.reconfigure(this.store, cm);
        }
        /*  @TODO,
        saveColumModel : function() {
            // Get Id, width and hidden propery from every column
            var c, config = this.colModel.config;
            var fields = [];
            for(var i = 0, len = config.length; i < len; i++)
            {
                c = config[i];
                fields[i] = {name: c.name, width: c.width};
                if(c.hidden) {
                    fields[i].hidden = true;
                }
            }
            var sortState = this.store.getSortState();
            // Send it to server
            //console.log("save config", fields);         
            Ext.Ajax.request({
                url: this.saveUrl,
                params : {fields: Ext.encode(fields), sort: Ext.encode(sortState)}
            });
        } */
 });

Ext.ux.AutoEditorGridPanel = Ext.extend(Ext.grid.EditorGridPanel, {
        initComponent : function(){
            if(this.columns && (this.columns instanceof Array)){
                this.colModel = new Ext.grid.ColumnModel(this.columns);
                delete this.columns;
            }
            // Create a empty colModel if none given
            if(!this.colModel) {
                this.colModel = new Ext.grid.ColumnModel([]);
            }
            Ext.ux.AutoGridPanel.superclass.initComponent.call(this);
            // register to the store's metachange event
            if(this.store){
                this.store.on("metachange", this.onMetaChange, this);
            }
            // Store the column model to the server on change
            if(this.autoSave) {
                this.colModel.on("widthchange", this.saveColumModel, this);
                this.colModel.on("hiddenchange", this.saveColumModel, this);
                this.colModel.on("columnmoved", this.saveColumModel, this);
                this.colModel.on("columnlockchange", this.saveColumModel, this);            
            }     
        },    
       onMetaChange : function(store, meta) {
            // console.log("onMetaChange", meta.fields);
            // loop for every field, only add fields with a header property (modified copy from ColumnModel constructor)
            var c;
            var config = [];
            var lookup = {};
            config[config.length] = new Ext.grid.RowNumberer();
            if (!Ext.isEmpty(this.cnfSelMode)) {
                switch (this.cnfSelMode) {
                    case "csms":    // checkbosSelection model single
                    case "csm":     // checkbosSelection model 
                        config[config.length] = new Ext.grid.CheckboxSelectionModel();
                        this.selModel = new Ext.grid.RowSelectionModel({singleSelect:true});
                        break;
                    case "csmm":    // checkbosSelection model multiple
                        config[config.length] = new Ext.grid.CheckboxSelectionModel();
                        this.selModel = new Ext.grid.RowSelectionModel();
                        break;
                    case "rsm":     // rowSelection model 
                        this.selModel = new Ext.grid.RowSelectionModel();
                        break;
                    case "cel":     // cellSelection model multiple
                        this.selModel = new Ext.grid.CellSelectionModel();
                        break;
                    case "rsms":    // rowSelection model single
                        this.selModel = new Ext.grid.RowSelectionModel({singleSelect:true});
                        break;
                }
            }
            for(var i = 0, len = meta.fields.length; i < len; i++)
            {
                c = meta.fields[i];
                if(c.header !== undefined){                
                    if(typeof c.dataIndex == "undefined"){
                        c.dataIndex = c.name;
                    }
                    if(typeof c.id == "undefined"){c.id = 'c' + i;}
                    //if(c.editor && c.editor.isFormField){
                    var olEditor = c.editor;
                    if(c.editor /*&& c.editor.isFormField*/){
                        delete (c.editor);
                        delete (c.renderer);
                        c.editor = new Ext.grid.GridEditor(olEditor);}
                    c.sortable = true;              
                    //delete c.name;
                    if(typeof c.renderer == "string") {c.renderer = Ext.util.Format[c.renderer]}
                    /*else {
                        if(typeof c.renderer == "undefined"){
                            switch(c.type){
                                case "int":
                                    if(typeof(fRendInteger)=="function") c.renderer = Ext.util.Format("intSimple");
                                    break;
                                case "float":
                                    if(typeof(fRendQuantity)=="function") c.renderer = Ext.util.Format("float2Dec");
                                    break;
                                default:
                                    break;
                            }
                        }
                    }*/
                    config[config.length] = c;
                    lookup[c.id] = c;                
                }
            }
            // Store new configuration
            this.colModel.config = config;  
            this.colModel.lookup = lookup;  
            
            // Re-render grid
            if(this.rendered){
                this.view.refresh(true);
            }
            //if(!this.view.hmenu.items.keys.include("reset")) //fah 30/04/08  
            //    this.view.hmenu.add({id:"reset", text: "Reset Columns", cls: "xg-hmenu-reset-columns"});
     
            var cm = new Ext.grid.ColumnModel(config);
            //this.reconfigure(this.dataSource, cm);
            this.reconfigure(this.store, cm);
        }
        /*  @TODO,
        saveColumModel : function() {
            // Get Id, width and hidden propery from every column
            var c, config = this.colModel.config;
            var fields = [];
            for(var i = 0, len = config.length; i < len; i++)
            {
                c = config[i];
                fields[i] = {name: c.name, width: c.width};
                if(c.hidden) {
                    fields[i].hidden = true;
                }
            }
            var sortState = this.store.getSortState();
            // Send it to server
            //console.log("save config", fields);         
            Ext.Ajax.request({
                url: this.saveUrl,
                params : {fields: Ext.encode(fields), sort: Ext.encode(sortState)}
            });
        } */
 });

