
Ext.grid.FilteredGridPanel = Ext.extend(Ext.grid.GridPanel, {
    closable:true,
    autoHeight:true,
    autoDestroy:false,
    frame:true,
    autoScroll:true,          
    botonCoincidencias:true,
    botonBorrarFiltros:true,
    
    initComponent : function(){
        Ext.grid.GridPanel.superclass.initComponent.call(this);

        this.autoScroll = true;
        if(this.columns && (this.columns instanceof Array)){
            this.colModel = new Ext.grid.FilteredColumnModel(this.columns);
        }
        this.searchFields=[];

        var templateText='<table border="0" cellspacing="0" cellpadding="0" class="x-grid3-header" style="{tstyle}">'+
        '<thead><tr class="x-grid3-hd-row x-grid3-header">{cells}</tr></thead>'+
        '<tbody><tr>';
        for(var i=0;i<this.columns.length;i++){
            templateText+=            '<td><div  id="'+this.id+'Search'+i+'"></div></td>'
        }
        templateText+='</tr></tbody></table>';

        delete this.columns;

        var headerTpl = new Ext.Template(templateText);
        if(!this.viewConfig)
            this.viewConfig={};
        Ext.apply(this.viewConfig,{forceFit:true,templates:{}} );
        this.viewConfig.templates.header=headerTpl;
        

 

        
        if(this.sm){
            this.selModel = this.sm;
            delete this.sm;
        }
        this.store = Ext.StoreMgr.lookup(this.store);

        this.addEvents( 'filterChanged');
        this.on('filterChanged',function(){this.programaBusqueda();},this)        
    },
    programaBusqueda:function(){
        if(this.cuentaAtras)
            clearTimeout(this.cuentaAtras);
        this.cuentaAtras=this.refresh.defer(500,this);

    },
    refresh:function(){
        this.store.load({
            params:{tabla:this.tabla,start:0,limit:this.getBottomToolbar().pageSize},
            callback:function(records,options,success){
                var datos=this.store.reader.jsonData;
                if(datos.success=='false'){
                    Ext.Msg.alert('Error',datos.message+'<br>'+datos.exception);
                }
            }.createDelegate(this)
        });
        cuentaAtras=undefined;    
    },
    
    createSearchFields:function(){
        //Hay que tomarlo del columnModel
        
        var searchFields=[];
        this.searchFields=searchFields;
        var i=0, cm=this.getColumnModel(),cuenta=0;
        this.store.filters=[];
        for(var i=0;i<cm.getColumnCount();i++){

            if(!cm.isHidden(i) && cm.getFilter(i)){
                var defaults={
                    renderTo: this.id+'Search'+cuenta,
                    xtype: 'textfield',
                    style:'background: #FFF0B9;',
                    listeners:{
                        valid: {fn:function(){this.fireEvent('filterChanged');},scope:this}
                    }
                }
                Ext.apply(defaults, cm.getFilter(i));
                searchFields[i]= Ext.ComponentMgr.create(defaults);           
                this.store.filters[i]=searchFields[i];
            }
            if(!cm.isHidden(i))
                cuenta++;
        }
        this.syncFields.defer(200,this);
        this.on('columnresize',this.syncFields,this);
        this.on('columnmove',this.createSearchFields,this);        
        this.on('resize',this.syncFields,this);                

    },
    syncFields:function(){
            var cm = this.getColumnModel();
            var sf = this.searchFields;
            for(var i=0;i<sf.length;i++){
                if(sf[i])
                    sf[i].setSize(cm.getColumnWidth(i));
            }
    },
    afterRender : function(){
        Ext.grid.GridPanel.superclass.afterRender.call(this);
        this.view.layout();
        this.viewReady = true;
        this.createSearchFields();
    }
});