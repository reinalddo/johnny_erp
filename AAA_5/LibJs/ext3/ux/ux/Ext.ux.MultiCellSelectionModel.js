Ext.namespace("Ext.ux.grid");

Ext.ux.grid.MultiCellSelectionModel = function(config) {
    Ext.apply(this, config);
    this.addEvents({
        "beforecellselect": true,
        "selectionchange": true
    });
    var ck = this.cellKey;
    this.selection = new Ext.util.MixedCollection(false, function(o){
        return ck(o);
    });
    Ext.ux.grid.MultiCellSelectionModel.superclass.constructor.call(this);
}
Ext.extend(Ext.ux.grid.MultiCellSelectionModel, Ext.grid.AbstractSelectionModel, {
    constrainToSingleRow: false,
    unselectableColumns: [],
    returnRecord: true,
    returnDataIndex: true,
    keyNavEnabled: true,
    initEvents: function() {
        this.grid.on("cellmousedown", this.handleMouseDown, this);
        this.view = this.grid.view;

        this.view.on("beforerowremoved", this.clearSelections, this);
        this.view.on("beforerowsinserted", this.clearSelections, this);

        if(this.grid.isEditor)
            this.grid.on("beforeedit", this.beforeEdit,  this);

        var moveSelectionFunc = function(adjust) {
            return function(e) {
                if(this._activeItem) {
                    var active = this.selection.get(this._activeItem);
                    var row = active.row, col = active.col;

                    if(this._expandedTo) {  // e.shiftKey && 
                        row = this._expandedTo[0];
                        col = this._expandedTo[1];
                    }
                    var adjusted = adjust(row,col);
                    row = adjusted[0]; col = adjusted[1];
                    if(this.isCellAvailable(row,col) && this.isCellSelectable(row,col,this.getColumnDataIndex(col))) {
                        e.shiftKey ? this.expandSelection(row, col, true) : this.select(row, col, false);
                    }
                }
            }
        };

        if(this.keyNavEnabled) {
            this.keyNav = new Ext.KeyNav(this.grid.getGridEl(), {
                scope: this,
                up: moveSelectionFunc(function(row, col) {    return [row - 1, col     ]; }),
                down: moveSelectionFunc(function(row, col) {  return [row + 1, col     ]; }),
                left: moveSelectionFunc(function(row, col) {  return [row    , col - 1 ]; }),
                right: moveSelectionFunc(function(row, col) { return [row    , col + 1 ]; }),
                "enter": function(e) {
                    if(this._activeItem) {
                        var active = this.selection.get(this._activeItem), g = this.grid;
                        if(g.isEditor && !g.editing) {
                            g.startEditing(active.row, active.col);
                            e.stopEvent();
                        }
                    }
                }
            })
        }
    },
    isCellAvailable: function(row, col) {
        return col > -1 && row > -1 && !!this.view.getRow(row) && !!this.view.getCell(row,col);
    },
    beforeEdit: function() {

    },
    hasSelection: function() {
        return this.selection.getCount() > 0;
    },
    clearSelections: function(preventNotify) {
        if(this.hasSelection()) {
            if(!preventNotify)
                this.selection.each(function(cell){
                    this.view.onCellDeselect(cell.row, cell.col);
                }, this);
            this.selection.clear();
            this.fireEvent("selectionchange", this, this.selection);
        }
    },
    handleMouseDown: function(g, row, col, e) {
        if(e.button != 0 || this.isLocked()) return;
        var hs = this.hasSelection();
        if(hs) {
            if(e.shiftKey) this.expandSelection(row, col, true);
            else if(e.ctrlKey) this.selectAdditionalCell(row,col);
            else this.select(row, col);
        } else this.select(row, col);
    },
    captureMouseMove: function(on) {
        if(on) {
            Ext.getDoc().on('mouseup', this.captureMouseMove.createDelegate(this, [false]), this, { single: true });
            this.view.el.on('mousemove', this.onMouseMove, this);
        } else {
            this.view.el.un('mousemove', this.onMouseMove, this);
            this.fireEvent("selectionchange", this, this.selection);            
        }
    },
    onMouseMove: function(e, el) {
        var row = this.view.findRowIndex(el), col = this.view.findCellIndex(el);
        if(!(col === false) && (!this._expandedTo || !(row == this._expandedTo[0] && col == this._expandedTo[1]))) {
            this.expandSelection(row, col, true);
        }
    },
    isCellSelectable: function(row, col, dataIndex) {
        return (!this.grid.getColumnModel().isHidden(col)) && !(dataIndex && this.unselectableColumns.indexOf(dataIndex) != -1);

    },
    getColumnDataIndex: function(col) {
        if(this.returnDataIndex) {
            var cm = this.grid.getColumnModel();
            return cm.getColumnById(cm.getColumnId(col)).dataIndex;
        }
    },
    selectCell: function(row, col, preventViewNotify, preventFocus, r) {
        var dataIndex = this.getColumnDataIndex(col);
        if(!this.isCellSelectable(row, col, dataIndex)) return;

        r = this.returnRecord && (r || this.grid.store.getAt(row));
        var sel = {
            record : r,
            dataIndex: dataIndex,
            cell : [row, col],
            row: row, col: col
        };
        var key = this.cellKey(row, col);

        this.selection.add(key, sel);
        if(!preventViewNotify){
            var v = this.grid.getView();
            v.onCellSelect(row, col);
            if(preventFocus !== true){
                v.focusCell(row, col);
            }
        }
        return key;
    },
    deselectCellByKey: function(cellkey, preventViewNotify) {
        var cell = this.selection.get(cellkey);
        if(!cell) return;
        if(!preventViewNotify) this.view.onCellDeselect(cell.row, cell.col);
        this.selection.remove(cell);
    },
    deselectCell: function(row, col) {
        this.deselectCellByKey(this.cellKey(row, col));
    },
    deselectCells: function(cells) {
        for(var i=0; i<cells.length;i++)
            this.deselectCellByKey(cells[i]);
    },
    cellKey: function(row,col) {
        return String.format("{0}::{1}", row, col);
    },
    expandSelection: function(row, col, clearExpandedSelection) {
        if(this._expandedSelection && clearExpandedSelection) {
            this.deselectCells(this._expandedSelection);
        }
        var active = this.selection.get(this._activeItem);
        this._expandedSelection = [];
        if(this.constrainToSingleRow || active.row == row) {
            var start = active.col + 1, end = col;
            if(col < active.col) {
                start = col;
                end = active.col -1;
            }
            for(var i = start; i <= end; i++) {
                var s = this.selectCell(active.row, i, false, true);
                this._expandedSelection.push(s);
            }
            this._expandedTo = [active.row, col];
        } else {
            var x0 = Math.min(col, active.col), x1 = Math.max(col, active.col);
            var y0 = Math.min(row, active.row), y1 = Math.max(row, active.row);
            for(var x=x0; x<=x1; x++) {
                for(var y=y0; y<=y1;y++) {
                    if(!(x==active.col && y==active.row)) {
                        var s = this.selectCell(y, x, false, true);
                        this._expandedSelection.push(s);
                    }
                }
            }
            this._expandedTo = [row, col];
        }
        this.fireEvent("selectionchange", this, this.selection);
    },
    getSelections: function() {
        return this.selection;
    },
    selectAdditionalCell: function(row, col, captureMouseMove) {
        var key = this.cellKey(row, col);
        if(this.constrainToSingleRow && this._activeItem) {
            var active = this.selection.get(this._activeItem);
            if(active.row != row) return;
        }
        if(this.selection.containsKey(key)) {
            this.deselectCellByKey(key);
        } else {
            this._activeItem = this.selectCell(row, col);
            this._expandedSelection = false;
            this._expandedTo = false;

            if(captureMouseMove !== false)
                this.captureMouseMove(true);
        }
    },
    select : function(rowIndex, colIndex, captureMouseMove, preventViewNotify, preventFocus, /*internal*/ r){
        if(this.fireEvent("beforecellselect", this, rowIndex, colIndex) !== false){
            this.clearSelections();
            this._activeItem = this.selectCell(rowIndex, colIndex, preventViewNotify, preventFocus, r);
            this._expandedSelection = false;
            this._expandedTo = false;
            if(captureMouseMove !== false)
                this.captureMouseMove(true);
            this.fireEvent("selectionchange", this, this.selection);
        }
    }
});