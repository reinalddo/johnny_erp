Ext.ux.MultiGroupingStore = Ext.extend(Ext.data.GroupingStore,{
    sortInfo: [],
    
    sort : function(field, dir){
        var f = [];
        
        if(Ext.isArray(field)){        
            for(i=0,len=field.length; i<len; ++i){
              f.push(this.fields.get(field[i]));
            }
        }
        else{
           f.push(this.fields.get(field));
        }
        
        if(f.length < 1){
            return false;
        }
        
        if(!dir){
            if(this.sortInfo && this.sortInfo.length > 0 && this.sortInfo[0].field == f[0].name){ // toggle sort dir
                dir = (this.sortToggle[f[0].name] || "ASC").toggle("ASC", "DESC");
            }else{
                dir = f[0].sortDir;
            }
        }
        
        var st = (this.sortToggle) ? this.sortToggle[f[0].name] : null;
        var si = (this.sortInfo) ? this.sortInfo : null;
        
        this.sortToggle[f[0].name] = dir;
        this.sortInfo = [];
        for(i=0,len=f.length; i<len; ++i){
            this.sortInfo.push({field: f[i].name, direction: dir});
        } 
       
        if(!this.remoteSort){
            this.applySort();
            this.fireEvent("datachanged", this);
        }else{
            if (!this.load(this.lastOptions)) {
                if (st) {
                    this.sortToggle[f[0].name] = st;
                }
                if (si) {
                    this.sortInfo = si;
                }
            }
        }
    },
      
    setDefaultSort : function(field, dir){
        dir = dir ? dir.toUpperCase() : "ASC";
        this.sortInfo=[];
        
        if(!Ext.isArray(field))
            this.sortInfo.push({field: field, direction: dir});
        else{
          for(i=0,len=field.length; i<len; ++i){
            this.sortInfo.push({field: field[i].field, direction: dir});
            this.sortToggle[field[i]] = dir;
          }            
        }
    },
    
    constructor: function(config){
        Ext.ux.MultiGroupingStore.superclass.constructor.apply(this,arguments);
    },   
  
    
    groupBy : function(field, forceRegroup){
        if(!forceRegroup && this.groupField == field){          
            return; // already grouped by this field
        }
        
        this.groupField = field;
        
        if(this.remoteGroup){
            if(!this.baseParams){
                this.baseParams = {};
            }
            this.baseParams['groupBy'] = field;
        }
        if(this.groupOnSort){
            this.sort(field);
            return;
        }
        if(this.remoteGroup){
            this.reload();
        }else{
            var si = this.sortInfo || [];
            if(si.field != field){
                this.applySort();
            }else{
                this.sortData(field);
            }
            this.fireEvent('datachanged', this);
        }
    },
    
    applySort : function(){
        var si = this.sortInfo;
        
        if(si && si.length > 0 && !this.remoteSort){
            this.sortData(si, si[0].direction);
        }
        
        if(!this.groupOnSort && !this.remoteGroup){
            var gs = this.getGroupState();
            if(gs && gs != this.sortInfo){
                this.sortData(this.groupField);
            }
        }
    },
    
    getGroupState : function(){
        return this.groupOnSort && this.groupField !== false ?
               (this.sortInfo ? this.sortInfo : undefined) : this.groupField;
    },
    
    sortData : function(flist, direction){
        direction = direction || 'ASC';
        
        var st=[];
        var o;
        for(i=0,len=flist.length; i < len; ++i){
            o = flist[i];
            st.push(this.fields.get(o.field ? o.field : o).sortType);
        }
        
        var fn = function(r1, r2){
            var v1=[];
            var v2=[];
            var len = flist.length;
            var o;
            var name;
            for(i=0; i<len; ++i){
              o = flist[i];
              name= o.field ? o.field : o;
              
              v1.push(st[i](r1.data[name]));
              v2.push(st[i](r2.data[name]));       
            }            
           
            var result;
            for(i=0; i<len; ++i){
                result = v1[i] > v2[i] ? 1 : (v1[i] < v2[i] ? -1 : 0);
                if(result != 0)
                    return result;
            }
            
            return result; //if it gets here, that means all fields are equal
        };
        
        this.data.sort(direction, fn);
        if(this.snapshot && this.snapshot != this.data){
            this.snapshot.sort(direction, fn);
        }
    }    
    
});  

