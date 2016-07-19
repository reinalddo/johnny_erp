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
        ,deferredRender:false
        ,layoutOnTabChange:true
        ,defaults:{
             layout:'form'
            ,hideMode:'offsets'
        }
    }
    ,formConfig:{}

    // {{{
    ,initComponent:function() {
        // {{{
        // hard coded (cannot be changed from outside)
        config = {
            items:{}
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
            this.form.setValues(result.data);
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


        // tabs loop
        Ext.each(tabPanelConfig.items, function(tab) {
            var columnCount = tab.columnCount || tabPanelConfig.columnCount || 1;
            var columnWidth = 1/columnCount;
            var colPanel = {
                 xtype:'panel'
                ,border:false
                ,layout:'column'
                ,defaults:Ext.apply(Ext.ux.clone(formConfig), tab.formConfig || {}, {
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
            var tabIndex = 1;
            Ext.each(tab.fields, function(field) {
                field.tabIndex = tabIndex++;
                colPanel.items[colIndex].items.push(field);
                colIndex++;
                colIndex = colIndex === columnCount ? 0 : colIndex;
            });

            // we don't need fields array anymore
            delete(tab.fields);

            // add column layout panel to tab
            tab.items = Ext.ux.clone(colPanel);

            // otherwise BasicForm::findField doesn't work
            Ext.each(tab.items.items, function(col) {
                col.listeners = {add:{scope:this, fn:this.onAdd}};
            }, this);

        }, this); // eo tabs loop

        this.add(tabPanelConfig);
        this.doLayout();
        this.afterMetaChange(form, meta);

    } // eo function onMetaChange
    // }}}
    // {{{
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
 
}); // eo extend
 
// register xtype
Ext.reg('metatabsform', Ext.ux.MetaTabsForm); 


