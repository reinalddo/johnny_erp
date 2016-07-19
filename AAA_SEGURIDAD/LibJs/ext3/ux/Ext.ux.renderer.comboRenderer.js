/**
 *
 * @rev fah     02/08/2011   aplicar correctamente hiddenName, para evitar error si diddenName es nulo, ejm desde gridPrinter
 **/
Ext.ns("Ext.ux.renderer");
Ext.ux.renderer.ComboRenderer = function(options) {
    var text = options.value;
    var combo = options.combo.editor||options;
    var valueField = combo.dataIndex||options.combo.dataIndex;

    if(combo.valueField){
        if(combo.mode == 'remote' && Ext.isDefined(combo.store.totalLength)){
            /*combo.store.on('load', this(combo, arguments), null, {single: true});
            if(combo.store.lastOptions === null){
                var params;
                if(combo.valueParam){
                    params = {};
                    params[combo.valueParam] = v;
                }else{
                    var q = combo.allQuery;
                    combo.lastQuery = q;
                    combo.store.setBaseParam(combo.queryParam, q);
                    params = combo.getParams(q);
                }
                combo.store.load({params: params});
            }
            return;
        }*/
            var r = combo.findRecord(combo.valueField, text);
            if(r){
                text = r.data[combo.displayField];
                combo.value = r.data[combo.valueField];
                //if (Ext.isDefined(options.record.get(combo.hiddenName))){     //#fah02/08/2011
                if (Ext.isDefined(combo.hiddenName)){                           //#fah02/08/2011
                    if (options.record) options.record.set(combo.hiddenName, combo.value);  //#fah02/08/2011
                    //options.record[combo.hiddenName] =  combo.value;
                    //combo.hiddenField.setValue(combo.value);
                }
            }else if(this.valueNotFoundText !== undefined){
                text = this.valueNotFoundText;
            }
        }
        combo.lastSelectionText = text;
    }
    return text
};

Ext.ux.renderer.Combo = function(combo) {
    return function(value, meta, record) {
        return Ext.ux.renderer.ComboRenderer({value: value, meta: meta, record: record, combo: combo});
    };
}

Ext.ux.renderer.ComboGeneric = function(value, meta, record, row, col, store) {
	return Ext.ux.renderer.ComboRenderer(
			{ value: value,
			meta: meta,
			record: record,
			combo: this.getColumnModel().getCellEditor(col, row).field
			});
	}