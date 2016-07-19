// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Ext.ux.MetaForm
 *
 * @author    Ing. Jozef Sakalos
 * @copyright (c) 2008, by Ing. Jozef Sakalos
 * @date      6. February 2007
 * @version   $Id: Ext.ux.MetaForm.js 80 2008-03-20 00:44:36Z jozo $
 *
 * @license Ext.ux.MetaForm is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

// isArray is in SVN only
if(!Ext.isArray) {
    Ext.isArray = function(v) {
        return v && 'function' == typeof v.pop;
    }
}

/**
 *
 * @class Ext.ux.MetaForm
 * @extends Ext.FormPanel
 */
Ext.ux.MetaForm = Ext.extend(Ext.FormPanel, {
    // configurables
     autoInit:true
    ,border:false
    ,frame:true
    ,loadingText:'Loading...'
    ,savingText:'Saving...'
    ,buttonMinWidth:90
    ,columnCount:1
    ,layout: 'column'
    /**
     * createButtons {Array} array of buttons to create
     *         valid values are ['meta', 'load', defaults', 'reset', 'save', 'ok', 'cancel']
     */
    /**
     * ignoreFields: {Array} of field names to ignore when received in metaData
     */

    // {{{
    ,initComponent:function() {

        // create one item to avoid error
        Ext.apply(this, {
            items:this.items || {}
        }); // eo apply

        // get buttons if we have button creation routines
        if('function' == typeof this.getButton) {
            this.buttons = this.getButtons();
        }

        // call parent
        Ext.ux.MetaForm.superclass.initComponent.apply(this, arguments);
        
        this.addEvents('cancel', 'ok');

        // install event handlers on basic form
        this.form.on({
             beforeaction:{scope:this, fn:function(form, action) {
                    this.beforeAction(this, action);
            }}
             
            ,actioncomplete:{scope:this, fn:function(form, action) {
                // (re) configure the form if we have (new) metaData
                if('load' === action.type && action.result.metaData) {
                    this.onMetaChange(this, action.result.metaData);
                }
                // update bound data on successful submit
                else if('submit' == action.type) {
                    this.updateBoundData();
                }
            }}
        });
        this.form.trackResetOnLoad = true;

    } // eo function initComponent
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
			this.form.setValues(this.data);
        };
    } // eo function beforeAction
    // }}}
    // {{{
    /**
     * @param {Object} data A reference to on external data object. The idea is that form can display/change an external object
     */
    ,bind:function(data) {
        this.data = data;
        this.form.setValues(this.data);
    } // eo function bind
    // }}}
    // {{{
    /**
     * override this if you want a special buttons config
     */
    ,getButtons:function() {
        var buttons = [];
        if(Ext.isArray(this.createButtons)) {
            Ext.each(this.createButtons, function(name) {

                var button;
                switch(name) {
                    case 'meta':
                        button = this.getButton(name, {
                            handler:this.load.createDelegate(this, [{params:{meta:true}}])
                        });
                    break;

                    case 'load':
                        button = this.getButton(name, {
                             scope:this
                            ,handler:this.load
                        });
                    break;

                    case 'defaults':
                        button = this.getButton(name, {
                             scope:this
                            ,handler:this.setDefaultValues
                        });
                    break;

                    case 'reset':
                        button = this.getButton(name, {
                             scope:this
                            ,handler:this.reset
                        });
                    break;

                    case 'save':
                    case 'submit':
                        button = this.getButton(name, {
                            handler:this.submit.createDelegate(this, [{params:{cmd:'setPref'}}])
                        });
                    break;

                    case 'ok':
                        button = this.getButton(name, {
                             scope:this
                            ,handler:this.onOk
                        });
                    break;

                    case 'cancel':
                        button = this.getButton(name, {
                             scope:this
                            ,handler:this.onCancel
                        });
                    break;
                }
                if(button) {
                    Ext.apply(button, {
                        minWidth:this.buttonMinWidth
                    });
                    buttons.push(button);
                }
            }, this);
        };
        return buttons;
    } // eo function getButtons
    // }}}
    // {{{
    ,getOptions:function(o) {
        var options = {
             url:this.url
            ,method:this.method || 'post'
        };
        Ext.apply(options, o);
        options.params = Ext.apply(this.baseParams || {}, o.params);
        return options;
    } // eo function getOptions
    // }}}
    // {{{
    /**
     * @return {Object} object with name/value pairs using fields.getValue() methods
     */
    ,getValues:function() {
        var values = {};
        this.form.items.each(function(f) {
            values[f.name] = f.getValue();
        });
        return values;
    } // eo function getValues
    // }}}
    // {{{
    ,load:function(o) {
        var options = this.getOptions(o);
        if(this.loadingText) {
            options.waitMsg = this.loadingText;
        }
        this.form.load(options);
    } // eo function load
    // }}}
    // {{{
    /**
     * cancel button handler - fires cancel event only
     */
    ,onCancel:function() {
        this.fireEvent('cancel', this);
    } // eo function onCancel
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
        this.removeAll();

        // declare varables
        var columns, colIndex, tabIndex, ignore = {};

        // add column layout
        this.add(new Ext.TabPanel({
		    activeTab: 0,
		    frame: true,
		    autoWidth: true,
		    layoutOnTabChange: true
        }));
        
        tabIndex = 0;

        if(Ext.isArray(this.ignoreFields)) {
            Ext.each(this.ignoreFields, function(f) {
                ignore[f] = true;
            });
        }
        // loop through metadata colums or fields
        // format follows grid column model structure
        Ext.each(meta.columns || meta.fields, function(item) {
            if(true === ignore[item.name]) {
                return;
            }
            
            this.items.get(0).add(new Ext.Panel({
            	title:	item.general.title,
            	layout:'form',
            	defaults: {width: 230},
            	defaultType: 'textfield'
            }));
            
            colIndex = 0;
            
            columns = this.items.get(0).items.get(tabIndex);

            Ext.each(item.fields, function(field) {
             	
            	
		                     	
		        if(field.editor.regex){
                //alert(item.editor.regex);
                	field.editor.regex= new RegExp(field.editor.regex);
	            }
	            var config = Ext.apply({}, field.editor, {
	                 name:field.name || field.dataIndex
	                ,fieldLabel:field.fieldLabel || field.header
	                ,defaultValue:field.defaultValue
	                ,xtype:field.editor && field.editor.xtype ? field.editor.xtype : 'textfield'
	            });
	
	            // handle regexps
	            if(config.editor && config.editor.regex) {
	                config.editor.regex = new RegExp(field.editor.regex);
	            }
	
	            // to avoid checkbox misalignment
	            if('checkbox' === config.xtype) {
	                Ext.apply(config, {
	                      boxLabel:' '
	                     ,checked:field.defaultValue
	                });
	            }
	            
	            if('combo' === config.xtype) {
	                Ext.apply(config, {
	                    store: new Ext.data.JsonStore({
						    url: '/service/?mode=' + config.service,
						    root: 'items',
						    fields: ['name', 'label']
						}),
	                    mode: 'remote',
	                    triggerAction: 'all',
	            		selectOnFocus:true,
	            		valueField:'name',
	               		displayField:'label'
	                   
	                });
	            }
	            if(meta.formConfig.msgTarget) {
	                config.msgTarget = meta.formConfig.msgTarget;
	            }
	
	            // add to columns on ltr principle
	            columns.add(config);
	            colIndex = colIndex == this.columnCount ? 0 : colIndex;
	            
            }, this);
			tabIndex = tabIndex+1;
        }, this);

        this.doLayout();
    } // eo function onMetaChange
    // }}}
    // {{{
    ,onOk:function() {
        this.updateBoundData();
        this.fireEvent('ok', this);
    }
    // }}}
    // {{{
    ,onRender:function() {
        // call parent
        Ext.ux.MetaForm.superclass.onRender.apply(this, arguments);

        this.form.waitMsgTarget = this.el;

        if(true === this.autoInit) {
            this.load({params:{meta:true}});
        }
        else if ('object' == typeof this.autoInit) {
            this.load(this.autoInit);
        }
    } // eo function onRender
    // }}}
    // {{{
    /**
     * private, removes all items from both formpanel and basic form
     */
    ,removeAll:function() {
        // remove border from header
        var hd = this.body.up('div.x-panel-bwrap').prev();
        if(hd) {
            hd.applyStyles({border:'none'});
        }
        // remove form panel items
        this.items.each(this.remove, this);

        // remove basic form items
        this.form.items.clear();
    } // eo function removeAllItems
    // }}}
    // {{{
    ,reset:function() {
        this.form.reset();
    } // eo function reset
    // }}}
    // {{{
    ,setDefaultValues:function() {
        this.form.items.each(function(item) {
            item.setValue(item.defaultValue);
        });
    } // eo function setDefaultValues
    // }}}
    // {{{
    ,submit:function(o) {
        var options = this.getOptions(o);
        if(this.savingText) {
            options.waitMsg = this.savingText;
        }
        this.form.submit(options);
    } // eo function submit
    // }}}
    // {{{
    ,updateBoundData:function() {
        if(this.data) {
            Ext.apply(this.data, this.getValues());
        }
    } // eo function updateBoundData
    // }}}

});

// register xtype
Ext.reg('metaform', Ext.ux.MetaForm);

// eof