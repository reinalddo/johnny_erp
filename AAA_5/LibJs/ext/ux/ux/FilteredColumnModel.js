

Ext.grid.FilteredColumnModel= Ext.extend(Ext.grid.ColumnModel, {
    getFilter: function(index){
        return this.config[index].filter;
    }
});

