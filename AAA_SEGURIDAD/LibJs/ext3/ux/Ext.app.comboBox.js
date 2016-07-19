/**
*   A improved version of Ext.comboBox to function as expected: returning value, displaying text
*  @view    http://www.jasonclawson.com/2008/06/11/extjs-update-to-combobox-replacement/
*/

(function(){

  Ext.namespace("app.genform");
  var NS = app.genform;
  NS.ComboBox = function(cfg){
    if(!cfg) cfg = {};
    if(!cfg.store && cfg.url){
      cfg.store = new Ext.data.Store({
        baseParams:cfg.baseParams || {},
        url: cfg.url,
        reader : new Ext.data.JsonReader({}, [cfg.valueField, cfg.displayField || cfg.valueField])
      });
    } else {
      if (!cfg.store || Ext.type(cfg.store) == 'array' || cfg.store instanceof Ext.data.SimpleStore) {
        cfg.mode = "local";
      }
      else {
        cfg.mode = "remote";
      }
    }

    if(cfg.transform) {
      this.clearValueOnRender = !Ext.fly(cfg.transform).first("[selected=true]");
    }

    /*
     * If we have a valueField this will make
     * form.getValues return the correct value
     */
   if(cfg.valueField) {
       var extraCfg = {
         hiddenName : cfg.name,
         hiddenId : cfg.name+"Id"
       };
   } else {
     var extraCfg = {};
   }

    NS.ComboBox.superclass.constructor.call(this, Ext.apply(extraCfg, {
      minListWidth : cfg.width
    },cfg));
  };

  Ext.extend(NS.ComboBox, Ext.form.ComboBox, {
    editable:     false,
    triggerAction: 'all',
    autoLoad : true,
    forceReload:false,
    forceSelection:true,
    clearValueOnRender:false,

    initComponent : function(){

      if (this.clearValueOnRender) {
        this.on("render", function(){
          this.clearValue();
        }, this);
      }
      /*
       * If width is set to 'auto' and minListWidth is not set then we need
       * to set a minListWidth so the list is guranteed to at least be the
       * same size as the combo box
       */
      if (((!this.width || this.width == 'auto') && !this.minListWidth)) {
        this.on("render", function(){
          this.minListWidth = this.wrap.getWidth();
        }, this);
      }

      if (this.mode == "remote") {
        this.store.on('load', this.assureValueEntry, this);
        if (this.autoLoad) {
          this.on("render", function(){
            if (this.store.getCount() == 0) {
              if(this.triggerAction == 'all') {
                        this.doQuery(this.allQuery, true);
                    } else {
                        this.doQuery(this.getRawValue());
                    }
            }
          }, this);
        }
      } else {
        this.assureValueEntry(this.store);
      }

      NS.ComboBox.superclass.initComponent.apply(this, arguments);
    },

    assureValueEntry: function(){
      if(this.forceSelection)
        this.setValue(this.value);
    },

    setValue : function(v){
          var text = v;
      if(this.valueField){
              var r = this.findRecord(this.valueField, v);
              if(r){
                  text = r.data[this.displayField];
              }else if(this.valueNotFoundText !== undefined){
                  text = this.valueNotFoundText;
              }
          }
          this.lastSelectionText = text;
          if(this.hiddenField){
              this.hiddenField.value = v;
          }
          Ext.form.ComboBox.superclass.setValue.call(this, text);
          this.value = v;
      },

    /*
     * If you load via this method then we assume we don't need to run doQuery again.
     */
    load : function(options){
      this.store.load(options);
      var q = (this.triggerAction == 'all')?this.allQuery:this.getRawValue();
      if(q === undefined || q === null)
              q = '';
      this.lastQuery = q;
    },

    doQuery: function(){
      if (this.forceReload) {
        this.store.reload({
          callback: NS.ComboBox.superclass.doQuery.createDelegate(this, arguments, false)
        });
      } else {
        NS.ComboBox.superclass.doQuery.apply(this, arguments);
      }
    }
  });

}())