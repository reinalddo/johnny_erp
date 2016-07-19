/**
 * http://www.sencha.com/forum/showthread.php?108663-OPEN-1249-GrpSummary-bug-when-quot-Show-in-groups-quot-disabled-and-hideGroupedColumn-true
 * When using GroupSummary with hideGroupedColumn: true and after "show in
 * groups" has been deselected, the next time you choose to group by a column,
 * that column is hidden but no grouping takes place. You have to click "Group
 * by this field" a second time to make it happen.
 * <p>
 * Update: doAllWidths is needed to resize summary cells when columns are
 * resized. When no grouping applied, the method GroupingView.getGroups returns
 * an array with all the rows (simple rows), which is wrong, because we are
 * looking for "x-grid-group" rows.
 */
Ext.grid.GroupingView.override({
	hasGroupRows: function() {
		var fc = this.mainBody.dom.firstChild;
		return fc && fc.nodeType == 1 && fc.className.indexOf("x-grid-group") > -1;
	},
	getGroups: function() {
		return this.hasGroupRows() ? this.mainBody.dom.childNodes : [];
	}
});

/**
 * http://www.sencha.com/forum/showthread.php?111960-Bug-GroupSummary-invokes-renderer-without-scope.
 * GroupSummary does not invoke renderers the same way they are invoked in
 * GridView. This makes it impossible to access the right scope inside the
 * renderer function.
 */
Ext.ux.grid.GroupSummary.override({
	renderSummary : function(o, cs){
		cs = cs || this.view.getColumnData();
		var cfg = this.grid.getColumnModel().config,
			buf = [], c, p = {}, cf, last = cs.length-1;
		for(var i = 0, len = cs.length; i < len; i++){
			c = cs[i];
			cf = cfg[i];
			p.id = c.id;
			p.style = c.style;
			p.css = i == 0 ? 'x-grid3-cell-first ' : (i == last ? 'x-grid3-cell-last ' : '');
			if(cf.summaryType || cf.summaryRenderer){
				p.value = (cf.summaryRenderer || c.renderer).call(c.scope,o.data[c.name], p, o);
			}else{
				p.value = '';
			}
			if(p.value == undefined || p.value === "") p.value = "&#160;";
			buf[buf.length] = this.cellTpl.apply(p);
		}

		return this.rowTpl.apply({
			tstyle: 'width:'+this.view.getTotalWidth()+';',
			cells: buf.join('')
		});
	}
});

