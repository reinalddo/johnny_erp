/**
 * EditorGrid validation plugin
 * Adds validation functions to the grid
 *
 * @author  Jozef Sakalos, aka Saki
 * @version 0.1
 *
 * Usage:
 * grid = new Ext.grid.EditorGrid({plugins:new Ext.ux.plugins.GridValidator(), ...})
 *
 * @from  http://www.sencha.com/forum/showthread.php?21158-EditorGrid-validation-plugin/page4
 */
Ext.ux.plugins.GridValidator = function(config) {

    // initialize plugin
    this.init = function(grid) {
        Ext.apply(grid, {
            /**
             * Checks if a grid cell is valid
             * @param {Integer} col Cell column index
             * @param {Integer} row Cell row index
             * @param {Boolean} validateModifiedOnly true to validate modified fields only
             * @return {Boolean} true = valid, false = invalid
             */
            isCellValid:function(col, row, validateModifiedOnly) {
                if(!this.colModel.isCellEditable(col, row)) {
                    return true;
                }
                var ed = this.colModel.getCellEditor(col, row);
                if(!ed) {
                    return true;
                }
                var record = this.store.getAt(row);
                if(!record) {
                    return true;
                }
                var field = this.colModel.getDataIndex(col);
                if(!record.isModified(field)) {
                    return true;
                }
                ed.field.setValue(record.data[field]);
                return ed.field.isValid(true);
            } // end of function isCellValid

            /**
             * Checks if grid has valid data
             * @param {Boolean} editInvalid true to automatically start editing of the first invalid cell
             * @param {Boolean} validateModifiedOnly true to validate modified fields only
             * @return {Boolean} true = valid, false = invalid
             */
            ,isValid:function(editInvalid, validateModifiedOnly) {
                var cols = this.colModel.getColumnCount();
                var rows = this.store.getCount();
                var r, c;
                var valid = true;
                for(r = 0; r < rows; r++) {
                    for(c = 0; c < cols; c++) {
                        valid = this.isCellValid(c, r, validateModifiedOnly);
                        if(!valid) {
                            break;
                        }
                    }
                    if(!valid) {
                        break;
                    }
                }
                if(editInvalid && !valid) {
                    this.startEditing(r, c);
                }
                return valid;
            } // end of function isValid
        });
    }; // end of function init
}; // GridValidator plugin end


/**

        function validateEditorGridPanel(gridId){
              var grid = Ext.getCmp(gridId);
              var rows = grid.store.data.length;
              var cols = grid.colModel.config.length;

              for (var row = 0; row < rows; row++){
                    var record = grid.store.getAt(row);
                    for (var col = 0; col < cols; col++){
                          var cellEditor = grid.colModel.getCellEditor(col, row);
                                if (cellEditor != undefined) {
                                      var columnName = grid.colModel.getDataIndex(col);
                                      var columnValue = record.data[columnName];
                                      cellEditor.field.setValue(columnValue);
                                      if (!cellEditor.field.isValid()) {
                                            grid.startEditing(row, col);
                                            return false;
                                      }
                                }
                    }
              }
              return true;
        }

**/