// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Ext.ux.MetaTabsForm
 *
 * @author    Ing. Jozef Sakáloš
 * @copyright (c) 2008, by Ing. Jozef Sakáloš
 * @date      21. July 2008
 * @version   $Id: Ext.ux.MetaTabsForm.js 303 2008-08-06 18:19:43Z jozo $
 *
 * @license Ext.ux.MetaTabsForm.js is licensed under the terms of the Open Source
 * LGPL 3.0 license. Commercial use is permitted to the extent that the 
 * code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */
 
/*global Ext */
 
/**
 *
 * @class Ext.ux.MetaTabsForm
 * @extends Ext.Panel
 */
Ext.ux.MetaTabsForm = Ext.extend(Ext.form.FormPanel, {

    // {{{
    // localizable texts
     loadingText:'Loading'
    ,savingText:'Saving'
    // }}}

    // config - can be changed from outside
    ,autoInit:true
    ,tabPanelConfig:{
         xtype:'tabpanel'
        ,border:false
        ,anchor:'100% 100%'
        ,deferredRender:true
        ,layoutOnTabChange:true
        //,hideMode : 'offsets'
        ,height: 400
        ,activeTab:0
        ,defaults:{
             layout:'form'
            ,hideMode:'offsets'
          }
    }
     ,currTab: 0
     ,currSubTab:0

    ,formConfig:{}

    // {{{
    ,initComponent:function() {
        // {{{
        // hard coded (cannot be changed from outside)
        config = {
            items:{}
            ,crudUrl:""  // Url for Create, Read, Update and delete Operations
        };
        // apply config
        Ext.apply(this, config);
        Ext.apply(this.initialConfig, config);
        // }}}
 
        // call parent
        Ext.ux.MetaTabsForm.superclass.initComponent.apply(this, arguments);
 
        // install event handlers on basic form
        this.form.on({
             beforeaction:{scope:this, fn:this.beforeAction}
            ,actioncomplete:{scope:this, fn:function(form, action) {
                // (re) configure the form if we have (new) metaData
                if('load' === action.type && action.result.metaData) {
                    this.onMetaChange(this, action.result.metaData);
                }
                // update bound data on successful submit
                else if('submit' === action.type) {
                    this.updateBoundData();
                }
            }}
        });
        this.form.trackResetOnLoad = true;
        /*fah this.form.setValues = this.setValues;     // @fah  Nov/30/08 */
 
    } // e/o function initComponent
    // }}}
    // {{{
    ,onRender:function() {
 
        // call parent
        Ext.ux.MetaTabsForm.superclass.onRender.apply(this, arguments);
 
        this.form.waitMsgTarget = this.el;

        if(true === this.autoInit) {
            this.load({params:{meta:true}});
        }
        else if ('object' === typeof this.autoInit) {
            this.load(this.autoInit);
        }
 
    } // e/o function onRender
    // }}}
    // {{{
    /**
     * private, changes order of execution in Ext.form.Action.Load::success
     * to allow reading of data in this server request (otherwise data would
     * be loaded to the form before onMetaChange is run from actioncomplete event
     */
    ,beforeAction:function(form, action) {
        action.success = function(response) {
            var result = this.processResponse(response);
            if(result === true || !result.success || !result.data){
                this.failureType = Ext.form.Action.LOAD_FAILURE;
                this.form.afterAction(this, false);
                return;
            }
            // original
//            this.form.clearInvalid();
//            this.form.setValues(result.data);
//            this.form.afterAction(this, true);

            this.form.afterAction(this, true);
            this.form.clearInvalid();
            this.form.setValues(result.data.data || result.data);
        };
    } // eo function beforeAction
    // }}}
    // {{{
    /**
     * Override this if you need a custom functionality
     *
     * @param {Ext.FormPanel} this
     * @param {Object} meta Metadata
     * @return void
     */
    ,onMetaChange:function(form, meta) {
  // remove old items
        this.removeAll();

        // get config of tab panel
        var tabPanelConfig = Ext.apply(Ext.ux.clone(this.tabPanelConfig), meta.tabPanelConfig || {});
        Ext.apply(tabPanelConfig.defaults, meta.tabPanelConfig.defaults || {}, this.tabPanelConfig.defaults);

        // get config of form 
        var formConfig = Ext.apply(Ext.ux.clone(this.formConfig), meta.formConfig || {});
        Ext.apply(formConfig.defaults, meta.formConfig.defaults || {}, this.formConfig.defaults);
        
        // get defaults for fields
        var fieldConfig = Ext.apply(Ext.ux.clone(meta.fieldConfig || {}));

        // tabs loop
        Ext.each(tabPanelConfig.items, function(tab) {
          this.currTab *= 1000;
          tabClone=Ext.ux.clone(tab);
          if(tab.config){
               this.setConfig(tab, tab.config)
               delete (tab.config)
          }
          if(isset(tab.items) && tab.items.length > 0){
               var itemCnt=0;
               Ext.each(tab.items, function(pnl) {  // sub tabs loop
                    if (pnl.config){
                         this.setConfig(pnl, pnl.config)
                         pnl.defaults = Ext.ux.clone(formConfig.defaults || {})
                         delete (pnl.config)
                    }
                    if(isset(pnl.fields) && pnl.fields.length > 0){
                         this.currSubTab = this.currTab + (100 * itemCnt) ;
                         pnl = this.setFields(pnl, formConfig, fieldConfig, this.currSubTab)
                    }
               }, this) // eo tabs.items loop
          }    // eo tab.fields if
          if(isset(tab.fields) && tab.fields.length){
               this.currSubTab = this.currTab + 100;
               tab = this.setFields(tab, formConfig, fieldConfig, this.currSubTab)
          }
          if (tab.items.length == 0) delete(tab.items);
        }, this); // eo tabs loop
        
          /*  ----------------------------------------------------------------*/
        /*tabPanelConfig.addListener(
               'activate', function(){Ext.Msg.alert('Activado', 'prueba'), this}
          )*/
        var pp= this.add(tabPanelConfig);
        pp.addListener(
               'activate', function(){Ext.Msg.alert('Activado', 'prueba'), this}
          )
        this.doLayout();
        this.afterMetaChange(form, meta);

    } // eo function onMetaChange
    // }}}
    // {{{
    /*
     *    Generates an array of fields from a given pnl.fields. Aplies options based in the following rules of precedence:
     *    Fields options defined for every field, then conditional apply of global Options for fields (fieldConfig), finally,
     *    conditional apply of form options (formConfig)
     *
     *    @param {object}   pnl         Current panel
     *    @param {object}   formConfig  default Config Option for the form
     *    @param {object}   fieldConfig default Config Options for the Fields
     *    @returns {object}   pnl       the input panel, including the configurated fields as his items.
     **/
    ,setFields: function(pnl, formConfig, fieldConfig, tabIdxIni){
          if (pnl.columnCount > pnl.fields.length) pnl.columnCount = pnl.fields.length; // to avoid errors in rendering empty columns
          var columnCount = pnl.columnCount || 1; // *tabPanelConfig.columnCount || 1;
          var columnWidth = 1/columnCount;
          var colPanel = {
               xtype:'panel'
              ,border:false
              ,layout:'column'
              ,defaults:Ext.apply(Ext.ux.clone(formConfig.defaults), pnl.defaults || {}, {
                   border:false
                  ,layout:'form'
                  ,columnWidth:columnWidth
              })
               
              // add all columns
              ,items:function() {
                  var cols = [];
                  for(var i = 0; i < columnCount; i++) {
                      cols.push({items:[]});
                  }
                  return cols;
              }()
          };

          // add fields to columns
          var colIndex = 0;
          var tabIndex = tabIdxIni  || 1;
          Ext.each(pnl.fields, function(field) {
/*---*/
               Ext.applyIf(field, fieldConfig);        // applies global defaults for fields
               //Ext.applyIf(field, pnl.defaults);       // applies defaults for this panel
               Ext.applyIf(field, field.editor, {      // applies options for this field's editor.
                    name:field.name || field.dataIndex
                   ,fieldLabel:field.fieldLabel || field.header
                   ,defaultValue:field.defaultValue
                   ,id:field.id
                   ,xtype:field.editor && field.editor.xtype ? field.editor.xtype : 'textfield'
               });
   
               // handle regexps
               if(field.editor && field.editor.regex) {
                   config.editor.regex = new RegExp(field.editor.regex);
               }
   
               // to avoid checkbox misalignment
               if('checkbox' === config.xtype) {
                   Ext.apply(config, {
                         boxLabel:' '
                        ,checked:field.defaultValue
                   });
               }
               if(this.formConfig.msgTarget) {
                   field.msgTarget = meta.formConfig.msgTarget;
               }                
               
/*----*/
               //field.xtype = field.editor && field.editor.xtype ? field.editor.xtype : 'textfield';
               field.tabIndex = tabIndex++;
               colPanel.items[colIndex].items.push(field);
               colIndex++;
               colIndex = colIndex === columnCount ? 0 : colIndex;
          }, this);

          // we don't need fields array anymore
          //---delete(tab.fields);
          delete(pnl.fields);

          // add column layout panel to tab
          pnl.items = Ext.ux.clone(colPanel);

          // otherwise BasicForm::findField doesn't work
          Ext.each(pnl.items.items, function(col) {
              col.listeners = {add:{scope:this, fn:this.onAdd}};
          }, this);
          return pnl;
    }
    ,setConfig: function(pElm, pCnfg, pDefs){
          return Ext.apply(pElm, pCnfg, pDefs)
    }
    /**
     * private, removes all items from both formpanel and basic form
     */
    ,removeAll:function() {
        // remove form panel items
        this.items.each(this.remove, this);

        // remove basic form items
        this.form.items.clear();
    } // eo function removeAllItems
    // }}}

    ,afterMetaChange:Ext.emptyFn
     /**
     * Override this if you need a custom functionality
     * process of a Http successful operation
     * @param 
     * @param 
     * @return void
     */
    ,successProc:function() {}
    /**
     * Override this if you need a custom functionality
     * process of a Http failed operation
     * @param 
     * @param 
     * @return void
     */
    ,failProc:function() {}
 
}); // eo extend
 
// register xtype
Ext.reg('metatabsform', Ext.ux.MetaTabsForm); 

function isset(variable_name) {
    try {
         if (typeof(eval(variable_name)) != 'undefined')
         if (eval(variable_name) != null)
         return true;
     } catch(e) { }
    return false;
   }

/**************************************
   buttons: [{
            text: 'Save',
            handler: function(){
                if(fp.getForm().isValid()){
                    var sb = Ext.getCmp('form-statusbar');
                    sb.showBusy('Saving form...');
                    fp.getEl().mask();
                    fp.getForm().submit({
                        url: 'fake.php',
                        success: function(){
                            sb.setStatus({
                                text:'Form saved!', 
                                iconCls:'',
                                clear: true
                            });
                            fp.getEl().unmask();
                        }
                    });
                }
            }
        }]
  */