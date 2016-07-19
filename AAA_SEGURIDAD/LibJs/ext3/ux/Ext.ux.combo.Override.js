/**
 * Override to form.Field component
 * calls the setValue method adding an optinal second parameter to distinguish this call from another
 * @see http://www.sencha.com/forum/showthread.php?75751-OPEN-42-ComboBox-s-setValue-call-with-a-remotely-loaded-Store/page5
 **/

Ext.override(Ext.form.Field,  {
    reset : function(){
        this.setValue(this.originalValue, true);
        this.clearInvalid();
    }
});


/**
 * Override to ComboBox.setValue
 * @param   {string}  Value to apply
 * @param   {bool}      throughReset   Wen called from a reset method.
     * @see http://www.sencha.com/forum/showthread.php?75751-OPEN-42-ComboBox-s-setValue-call-with-a-remotely-loaded-Store/page5
 **/

Ext.override(Ext.form.ComboBox, {
    loadOnReset: true,
    setValue : function(v, throughReset){
        this.value = v;             // to grant a isDirty call works
        var text = v;
        if(this.valueField){
            var dustyRecords;
            if(this.mode == 'remote' && Ext.isDefined(this.store.totalLength) && v && this.lastQuery!='' && this.forceSelection===true && (!throughReset || this.loadOnReset)){
                dustyRecords = (this.findRecord(this.valueField, v))?false:true;
            }
            /*if we get value AND store is loaded AND there was a lastQuery,
             *then we might NOT find the record in the store.
             *if that's the case then we have dusty records.
             *When dustyRecords=true then also load the store*/
            if(this.mode == 'remote' &&
               (!Ext.isDefined(this.store.totalLength) || dustyRecords===true) &&
               (!throughReset || this.loadOnReset)){
                this.store.on('load', this.setValue.createDelegate(this, arguments), null, {single: true});
                if(this.store.lastOptions === null || dustyRecords===true){
                    var params;
                    if(this.valueParam){
                        params = {};
                        params[this.valueParam] = v;
                    }else{
                        var q = this.allQuery;
                        this.lastQuery = q;
                        this.store.setBaseParam(this.queryParam, q);
                        params = this.getParams(q);
                    }
                    this.store.load({params: params});
                }
                return;
            }
            var r = this.findRecord(this.valueField, v);
            if(r){
                text = r.data[this.displayField];
            }else if(this.valueNotFoundText !== undefined){
                text = this.valueNotFoundText;
            }
        }
        this.lastSelectionText = text;
        if(this.hiddenField){
            this.hiddenField.value = Ext.value(v, '');
        }
        if(this.hiddenName){
            this.hiddenValue = Ext.value(v, '');
        }
        Ext.form.ComboBox.superclass.setValue.call(this, text);
        this.value = v;
    }
/*
 * 	Añadir Metodo getSelectedIndex al combo box
 *  @return     el indice correspondiente al registro seleccionado en el Combo.
 */
  ,getSelectedIndex : function() {
    var s = this.store;
    return s.indexOf(s.query(this.valueField, this.getValue()).get(0));
  }
  ////,specialkey: function(field, e){
  ////      if (e.getKey() == e.TAB ) {
  ////          field.setValue(field.selectedIndex);// i got simple store with items like 0:0 1:1 etc
  ////      }
  ////  }
    ,listeners: {
        specialkey: function(field, e){
            // e.HOME, e.END, e.PAGE_UP, e.PAGE_DOWN,
            // e.TAB, e.ESC, arrow keys: e.LEFT, e.RIGHT, e.UP, e.DOWN
            if (e.getKey() == e.TAB) {
                this.onViewClick(false)
                this.view.clearSelections()
            }
        }
    }

});

Ext.util.Format.comboRenderer = function(combo){
    return function(value){
        var record = combo.findRecord(combo.valueField || combo.displayField, value);
        return record ? record.get(combo.displayField) : combo.valueNotFoundText;
    }
}

Ext.ux.grid.combocolumn = Ext.extend(Ext.grid.Column, {
    constructor: function(config){
        if (!config.editor){
			config.editor = Ext.applyIf({xtype: "genCmbBox"}, config)
			}
        Ext.ux.grid.combocolumn.superclass.constructor.call(this, config);
        var pCombo = this.editor.field ? this.editor.field : this.editor;
        var that = this;
        //this.renderer = Ext.util.Format.comboRenderer.createDelegate(this, [olFld], true)();
        this.renderer = function(pVal, pMeta, pRec, pRow, pCol, pGStr, pCombo) {
           //return Ext.util.Format.comboRenderer(pVal, pMeta, pRec, pRow, pCol, pGStr, olFld);
           if (this.getCellEditor().row == pRow){
                var record = pCombo.findRecord(pCombo.valueField || pCombo.displayField, pVal);
                return record ? record.get(pCombo.displayField) : pCombo.valueNotFoundText;
           } else {
                //if(this.getCellEditor().row && this.getCellEditor().row != pRow) return
                var olRec = this.getEditor().ownerGrid.getStore().getAt(pRow);
                if (olRec.phantom || !olRec.json) return '';  // it's a new record and has not a record
                if (olRec.data && olRec.data[this.relatedField]) return olRec.data[this.relatedField];
                if (!this.relatedField) return pVal;
                return olRec.json[this.relatedField];
           }
        }.createDelegate(this, [pCombo], true)
        //this,renderer = function(pVal, pMeta, pRec, pRow, pCol, pGStr, pFld) {
        //    var record = combo.findRecord(combo.valueField || combo.displayField, value);
        //    return record ? record.get(combo.displayField) : combo.valueNotFoundText;
        //}()



    }
});

//    //var olCmb =  this.getColumnModel().getCellEditor(pCol, pRow).field;

Ext.grid.Column.types['combocolumn'] = Ext.ux.grid.combocolumn;

/**
 *	Combo Column   version a prueba
 *@author	fah		01/01/2010
 *
 */
Ext.ns("Ext.ux.app");
Ext.ux.app.comboColapp = Ext.extend(Ext.grid.Column,{
	/**this.getEditor().ownerGrid.getStore().getAt(ilRow).data.det_Secuencia:
	 * @var {string} relatedField   Name of the field has the text of selected item. Must be present in grid record's data or json element
	 */
	relatedField:	null
	,width:		'100'
	,constructor: function(config){
		if (!config.editor){
			config.editor = Ext.applyIf({xtype: "genCmbBox"}, config)
			}
            var olRec = {}
            config.editor.postSelect= function(pRec, pIdx){
                ilRow = (this.getCellEditor().row)
                var olGridRec = this.ownerGrid.store.getAt(this.getCellEditor().row)
                if (Ext.isDefined(olGridRec.data)) {
                    olGridRec.data[this.dataIndex||this.name] = pRec.get(this.editor.valueField)
                    olGridRec.data[this.relatedField]  =       pRec.get(this.editor.displayField)
                    olGridRec.json[this.relatedField]  =       pRec.get(this.editor.displayField)
                    olGridRec.json[this.dataIndex||this.name] = pRec.get(this.editor.valueField)
                    //olGridRec.commit();
                }
                this.updated = true;
            }
		Ext.ux.app.comboColapp.superclass.constructor.call(this, config);
        this.editor.onSelect = this.editor.onSelect.createSequence(this.editor.postSelect, this)
	}
    ,beforerenderer: function(pVal, pMeta, pGRec, pRow, pCell, pGStr ){
        debugger;

    }
    ,renderer:	function(pVal, pMeta, pGRec, pRow, pCell, pGStr ){
        //if (this.editor && this.editor.lastSelectionText) return this.editor.lastSelectionText
        //var olRec = this.ownerGrid.getStore().getAt(this.ownerGrid.currentRow);
        if(this.getCellEditor().row && this.getCellEditor().row != pRow) return
        ilRow = pRow
        var olRec = this.getEditor().ownerGrid.getStore().getAt(ilRow);
        if (olRec.phantom || !olRec.json) return '';  // it's a new record and has not a record
        if (olRec.data && olRec.data[this.relatedField]) return olRec.data[this.relatedField];
        if (!this.relatedField) return pVal;
        return olRec.json[this.relatedField];
    }
})
Ext.grid.Column.types['comboColapp'] = Ext.ux.app.comboColapp;
;
//Ext.util.Format.cmbGridRenderer =  function(pVal, pMeta, pRec, pRow, pCol, pGStr) {
//    //var olCmb =  this.getColumnModel().getCellEditor(pCol, pRow).field;
//    return        function(pVal, pMeta, pRec, pRow, pCell, pGStr ){
//        if (pGRec.phantom || !pGRec.json) return '';  // it's a new record and has not a record
//        if (pGRec.data && pGRec.data[this.relatedField]) return pGRec.data[this.relatedField];
//        if (!this.relatedField) return pVal;
//        return pGRec.json[this.relatedField];
//    }.createDelegate(this)
//}
