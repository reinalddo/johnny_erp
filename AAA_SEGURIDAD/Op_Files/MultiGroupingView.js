Ext.ux.MultiGroupingView = Ext.extend(Ext.grid.GroupingView,{
    displayEmptyFields: false,
    displayFieldSeperator: ', ',
    
    renderRows : function(){
        var groupField = this.getGroupField();
        var eg = !!groupField;
        // if they turned off grouping and the last grouped field is hidden
        if(this.hideGroupedColumn) {
            var colIndexes = [];           
            for(i=0,len=groupField.length; i<len; ++i){
                colIndexes.push(this.cm.findColumnIndex(groupField[i]));
            }
            if(!eg && this.lastGroupField !== undefined) {
                this.mainBody.update('');
                for(i=0,len=this.lastGroupField.length; i<len; ++i){
                    this.cm.setHidden(this.cm.findColumnIndex(this.lastGroupField[i]), false);
                }
                delete this.lastGroupField;
            }else if (eg && colIndexes.length > 0 && this.lastGroupField === undefined) {
                this.lastGroupField = groupField;
                for(i=0,len=colIndexes.length; i<len; ++i){
                    this.cm.setHidden(colIndexes[i], true);
                }                
            }else if (eg && this.lastGroupField !== undefined && groupField !== this.lastGroupField) {
                this.mainBody.update('');
                for(i=0,len=this.lastGroupField.length; i<len; ++i){
                    this.cm.setHidden(this.cm.findColumnIndex(this.lastGroupField[i]), false);
                }
                this.lastGroupField = groupField;
                for(i=0,len=colIndexes.length; i<len; ++i){
                    this.cm.setHidden(colIndexes[i], true);
                }  
            }
        }
        return Ext.ux.MultiGroupingView.superclass.renderRows.apply(this, arguments);
    },
    
 doRender : function(cs, rs, ds, startRow, colCount, stripe){
        if(rs.length < 1){
            return '';
        }
        var groupField = this.getGroupField();
        this.enableGrouping = !!groupField;

        if(!this.enableGrouping || this.isUpdating){
            return Ext.grid.GroupingView.superclass.doRender.apply(this, arguments);
        }
        
        var gstyle = 'width:'+this.getTotalWidth()+';';

        var gidPrefix = this.grid.getGridEl().id;
        
            var groups = [], curGroup, i, len, gid;
            for(i = 0, len = rs.length; i < len; i++)
            {
                var rowIndex = startRow + i;
                var r = rs[i];
               
                    var gvalue=[];
                    var fieldName;
                    var grpDisplayValues = [];
                    var v;
                    for(j=0,gfLen=groupField.length; j<gfLen; ++j){
                        fieldName = groupField[j];
                        v = r.data[fieldName];
                        if(v){
                          gvalue.push(v);
                          grpDisplayValues.push(fieldName + ': ' + v);
                        }
                        else if(this.displayEmptyFields){
                          grpDisplayValues.push(fieldName + ': ');
                        }                        
                    }
                                        
                    //g = this.getGroup(gvalue, r, groupRenderer, rowIndex, colIndexes[index], ds);
                    if(gvalue.length < 1 && this.emptyGroupText)
                        g = this.emptyGroupText;
                    else
                        g = grpDisplayValues.join(this.displayFieldSeperator);
                    
                    if(!curGroup || curGroup.group != g)
                    {
                        gid = gidPrefix + '-gp-' + groupField + '-' + Ext.util.Format.htmlEncode(g);
                           // if state is defined use it, however state is in terms of expanded
                        // so negate it, otherwise use the default.
                        var isCollapsed  = typeof this.state[gid] !== 'undefined' ? !this.state[gid] : this.startCollapsed;
                        var gcls = isCollapsed ? 'x-grid-group-collapsed' : '';    
                        curGroup = {
                            group: g,
                            gvalue: gvalue,
                            text: g,
                            groupId: gid,
                            startRow: rowIndex,
                            rs: [r],
                            cls: gcls,
                            style: gstyle
                        };
                        groups.push(curGroup);
                    }
                    else
                    {
                        curGroup.rs.push(r);
                    }
                    r._groupId = gid;
            }

        var buf = [];
        for(i = 0, len = groups.length; i < len; i++){
            var g = groups[i];
            this.doGroupStart(buf, g, cs, ds, colCount);
            buf[buf.length] = Ext.grid.GroupingView.superclass.doRender.call(
                    this, cs, g.rs, ds, g.startRow, colCount, stripe);

            this.doGroupEnd(buf, g, cs, ds, colCount);
        }
        return buf.join('');
    }
});  

