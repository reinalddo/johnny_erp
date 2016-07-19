/**
 * @class DirectForm
 * @extends Ext.form.FormPanel
 *
 * Direct Form Class
 *
 * @author    fah
 * @copyright (c) 2010, Ing. Fausto Astudillo
 * @version   1.0
 * @date      <ul>
 * <li>21. January 2011</li>
 * </ul>
 * @revision  $Id$
 * @depends
 *
 * @see       http://tdg-i.com/364/abstract-classes-with-ext-js
 * @see       http://blog.extjs.eu/know-how/writing-a-big-application-in-ext/
 * @see       http://blog.extjs.eu/know-how/factory-functions-in-ext-extensions/
 *
 * @license   This file is released under the
 * <a target="_blank" href="http://www.gnu.org/licenses/gpl.html">GNU GPL 3.0</a>
 * license. It’s free for use in GPL and GPL compatible open source software,
 * but if you want to use the component in a commercial software (closed source),
 * you have to get a commercial license.
 * @rev     fah 27/07/2011      Reprogramar actionComplete, afterActionComplete, afterSaveRecord y afterUpdateRecord para interpretar mejor datos y seuencia de eventos
 * @rev     fah 27/07/2011      _refreshForm:   modularizacin del proceso para actualizar info del form con datos que vienen del server
 * @rev     fah 23/03/2011      updateMasterCt: determinacion directa del registro a modificar atravez de olStr.reader.meta.idProperty
 * @rev     fah 25/03/2011      Agregado _afterSaveRecord, _afterUpdateRecord  como previos a los eventos
 * @rev     fah 25/03/2011      afterSaveRecord  integrado con logica de actualizacion datos Grid y Formulario que reflejen operacion
 * @rev     fah 09/10/2011      Modificar secuencia de eventos after save para que el mensaje de confirma-grabacion, se presente en evento afterSave/afterUpdate
 */
Ext.override(Ext.form.DateField, {
    format: app.gen.dateFmt
    ,altFormats: app.gen.dateFmts
    })
Ext.ns("Ext.ux.app");
Ext.ux.app.directForm= Ext.extend(Ext.form.FormPanel, {
    border:false
    /**
     * @cfg {Number} columnCount
     * MetaForm has a column layout insise with this number of columns (defaults to 1)
     */
    ,columnCount:       1
    /**
     * @cfg {Boolean} finalFieldsFlag
     * Flag to prevent regeneration of items property by onMetaChange. defaults true.
     * If finalFieldsFlag = true, the initialization process, receives the items property and generates
     * a items property based on predefined standards, it asummes every element of items is a simple field.
     * If you require special forms structure, mus be set this flag to false.
     */
    ,finalFieldsFlag:   false
    ,pkField:           null
    ,cls:               "font-size:10px"
    ,typeMapping:       {float:"numberfield", string:"textfield", date: "xdatefield", datetime: "xdatetime", timestamp:"xdatetime", int:"numberfield"}
    ,animCollapse:      false
    // {{{
    // private
    ,initComponent:function() {

        // create preCnfg config object
        var config = {};
        /**
         * @var     masterID ID of the master component to be updated after form CRUD operations
         */
        this.directProvider=null;
        config.labelAlign   = "right";
        config.layout       = this.initialConfig.layout || "form";
        config.defaults     = this.initialConfig.defaults ||
                ("form" == this.initialConfig.layout ) ?
                {xtype:'textfield', msgTarget:"side", cls:"font:10px tahoma,arial,helvetica,sans-serif;" }
                : {};
        config.finalFieldsFlag=   this.initialConfig.finalFieldsFlag || false  ;
        config.paramsAsHash = true


        //Basic buttons configuration. Add or remove buttons on buildButtons method
        var alBtns = [{  name:     'btnADD'
                ,text: 'AGREGAR'
                ,tooltip: 'HAbilita el ingreso de un nuevo registro'
                ,cls:'x-btn-text-icon'
                ,icon: 'Images/famfam/add.png'
                ,handler : this.addRecord
                ,scope: this
                ,disabled:true
            },{name:     'btnUPD'
                ,text: 'GRABAR'
                ,tooltip: 'Graba los datos ingresados ó modificados '
                ,cls:'x-btn-text-icon'
                ,icon: 'Images/famfam/accept.png'
                ,disabled:true
                ,handler: this.save
                ,scope: this
                ,formBind: true
            },{name:     'btnRFR'
                ,text: 'REFRESCAR'
                ,tooltip: 'Recarga la informacion'
                ,cls:'x-btn-text-icon'
                ,icon: 'Images/famfam/cog.png'
                ,disabled:true
                ,handler: this.load
                ,scope: this
            },{
                name:     'btnDEL'
                ,text:   'ELIMINAR'
                ,tooltip: 'Borra los registros marcados'
                ,cls:'x-btn-text-icon'
                ,icon: 'Images/famfam/application_delete.png'
                //,disabled:true
                ,handler: this.deleteRec
                ,scope: this
            }]

        config.buttons =new Array()
        if(this.initialConfig.ignoreButtons && this.initialConfig.ignoreButtons.length > 0){
            var alIgnore = this.initialConfig.ignoreButtons.split(",")

            var ilIdx = null
            for (var i in alBtns){
                var obj = alBtns[i]
                olIdx = alIgnore.indexOf(obj.name||obj.itemId)
                if (!olIdx){
                    config.buttons.push(obj)
                }
            }
        } else config.buttons = alBtns;

        // build config
        this.buildConfig(config);

        var olItems = config.items;
        if (!config.finalFieldsFlag){   // items property is a final representation
            delete config.items;
            config.items = [];
        }

        if (!this.api && config.namespace && config.className){
            this.directProvider = eval("(" + config.namespace + "." + config.className + ")")
            this.api={
                load:   this.directProvider.getList
               ,submit: this.directProvider.update
               ,destroy: this.directProvider.deleteRecord
            }
        }
        this.addEvents('cancel', 'ok');
        // apply config
        Ext.apply(this, Ext.apply(this.initialConfig, config));
        // call parent
        Ext.ux.app.directForm.superclass.initComponent.call(this);

        if (!this.initialConfig.finalFieldsFlag){   // items property is a final representation
            this.onMetaChange(this, olItems);
        }

        // install event handlers on basic form
        this.form.on({
            //beforeshow:     {scope:this, fn:this.beforeShow}
            beforeaction:   {scope:this, fn:this.beforeAction}
            ,actioncomplete:{scope:this, fn:this.actionComplete}
            ,actionfail:    {scope:this, fn:this.actionFail}
        });
        this.form.on({clientvalidation: {scope:this, fn:this.clientvalidation}})

    } // eo function initComponent
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
        if(false === this.fireEvent('beforemetachange', this, meta)) {
            return;
        }
        this.removeAll();
        this.hasMeta = false;

        // declare varables
        var columns, colIndex, tabIndex, ignore = {};

        // add column layout
        this.add(new Ext.Panel({
             layout:'column'
            ,anchor:'-25'
            ,cls: "font-size:10px !important"
            ,border:false
            ,defaults:(function(){
                this.columnCount = meta.formConfig ? meta.formConfig.columnCount || this.columnCount : this.columnCount;
                return Ext.apply({}, meta.formConfig || {}, {
                    columnWidth:    1/this.columnCount
                    ,autoHeight:    true
                    ,border:        false
                    ,hideLabel:     true
                    ,layout:        'form'

                });
            }).createDelegate(this)()
            ,items:(function(){
                var items = [];
                for(var i = 0; i < this.columnCount; i++) {
                    items.push({
                         defaults:  {anchor: "100% ", height:20, xtype: "textfield"}   // Ext.applyIf(this.initialConfig.defaults || {}, {anchor: "100% ", height:20, xtype: "textfield"})
                        ,listeners: {
                            // otherwise basic form findField does not work
                            add:{scope:this, fn:this.onAdd}
                        }
                    });
                }
                return items;
            }).createDelegate(this)()
        }));

        columns = this.items.get(0).items;
        colIndex = 0;
        tabIndex = 1;

        if(Ext.isArray(this.ignoreFields)) {
            Ext.each(this.ignoreFields, function(f) {
                ignore[f] = true;
            });
        }
        // loop through metadata colums or fields
        // format follows grid column model structure
        Ext.each(meta.columns || meta.fields || meta, function(item) {
            if(true === ignore[item.name]) {
                return;
            }
            if (!item.xtype)
            item = Ext.applyIf(item, item.editor);
            var config = Ext.apply({}, item, {
                name:item.name || item.dataIndex || ""
                ,fieldLabel:item.fieldLabel || item.header
                ,defaultValue:item.defaultValue
            });
            if (!config.dataIndex) config.dataIndex = config.name || '' // use a mapping technique
            if (!config.xtype) config.xtype = this.typeMapping[config.type || 'string'] // use a mapping technique

            // handle regexps
            if(config.editor && config.editor.regex) {
                config.editor.regex = new RegExp(item.editor.regex);
            } else  delete item.editor;

            // to avoid checkbox misalignment
            if('checkbox' === config.xtype) {
                Ext.apply(config, {
                      boxLabel:' '
                     ,checked:item.defaultValue
                });
            }
            if(('genCmbBox' === config.xtype || 'combobox' === config.xtype) && !config.hiddenName && config.dataIndex) {
                config.hiddenName = config.dataIndex
            }
            if(meta.formConfig && meta.formConfig.msgTarget) {
                config.msgTarget = meta.formConfig.msgTarget;
            }

            // add to columns on ltr principle
            config.tabIndex = tabIndex++;
            columns.get(colIndex++).add(config);
            colIndex = colIndex === this.columnCount ? 0 : colIndex;

        }, this);
        if(this.rendered && 'string' !== typeof this.layout) {
            this.el.setVisible(false);
            this.doLayout();
            this.el.setVisible(true);
        }
        this.hasMeta = true;
        if(this.data) {
            // give DOM some time to settle
            (function() {
                this.form.setValues(this.data);
            }.defer(1, this))
        }
        this.afterMetaChange();
        this.fireEvent('metachange', this, meta);

        // try to focus the first field
        if(this.focusFirstField) {
            var firstField = this.form.items.itemAt(0);
            if(firstField && firstField.focus) {
                var delay = this.ownerCt && this.ownerCt.isXType('window') ? 1000 : 100;
                firstField.focus(firstField.selectOnFocus, delay);
            }
        }
    } // eo function onMetaChange
    // }}}
    // {{{
    /**
     * Override this if you need a custom functionality
     */
    ,afterMetaChange:function() {}
    // {{{
    /**
     * Builds the config object
     * @param {Object} config The config object is passed here
     * from initComponent by reference. Do not create or return
     * a new config object, add to the passed one instead.
     *
     * You can override this function if you need to customize it
     * or you can override individual build functions called.
     */
    ,buildConfig:function(config) {
        this.buildItems.createDelegate(this)(config);
        this.buildButtons(config);
        this.buildTbar(config);
        this.buildBbar(config);
    } // eo function buildConfig
    // }}}
    // {{{
    /**
     * Builds items
     * @param {Object} config The config object is passed here
     * from buildConfig by reference. Do not create or return
     * a new config object, add to the passed one instead.
     *
     * You can override this function if you need to customize it.
     */
    ,buildItems:function(config) {
        config.items = undefined;
    } // eo function buildItems
    // }}}
    // {{{
    /**
     * Builds buttons
     * @param {Object} config The config object is passed here
     * from buildConfig by reference. Do not create or return
     * a new config object, add to the passed one instead.
     *
     * You can override this function if you need to customize it.
     */
    ,buildButtons:function(config) {} // eo function buildButtons
    // }}}
    // {{{
    /**
     * Builds top toolbar and its items
     * @param {Object} config The config object is passed here
     * from buildConfig by reference. Do not create or return
     * a new config object, add to the passed one instead.
     *
     * You can override this function if you need to customize it.
     */
    ,buildTbar:function(config) {
        config.tbar = undefined;
    } // eo function buildTbar
    // }}}
    // {{{
    /**
     * Builds bottom toolbar and its items
     * @param {Object} config The config object is passed here
     * from buildConfig by reference. Do not create or return
     * a new config object, add to the passed one instead.
     *
     * You can override this function if you need to customize it.
     */
    ,buildBbar:function(config) {
        config.bbar = undefined;
    } // eo function buildBbar
    // }}}
    /**
     *Returns a specified button
     *@method
     *@param    {string}    Name of the button
     */
    ,getButton: function(pBtnName){
        return this.buttons[this.buttons.findIndexByCol(pBtnName, "name")]
    }
    /**
     * Fires before show.
     * @event
    *
     * You can override this function if you need to customize it.
     */
    ,beforeShow: function(){return true}
    /**
     * Fires before an action is executed.
     * @event
     * @param {Object} config The config object is passed here
     * from buildConfig by reference. Do not create or return
     * a new config object, add to the passed one instead.
     *
     * You can override this function if you need to customize it.
     */
    ,beforeAction: function(){return true}
    /**
     * Fires after  an successful action is executed. Updates the form content with data from server contained in the first elemnt of "data" array or in a "data" object
     * @event
     * @param {Object} pForm The current Form
     * @param {Object} pAction action firing the event
     *
     * You can override this function if you need to customize it.
     */
    ,actionComplete: function(pForm, pAction){
        var olRec ={};
        Ext.Msg.minWidth = 200;

        if (!pAction.result.data){
            Ext.msgBox({title: "ATENCION:!", msg:"El servidor no ha retornado informacion"})
        }
        olRec.data = pAction.result.data[0] || pAction.result.data
        if(this.beforeLoadRecord(pForm, pAction)){
            pAction.form.loadRecord(olRec);
            this.form.clearDirty();
        }

        if (pAction.type == "load" || pAction.type == "directload") {
            if ("function" == typeof this.afterLoadRecord) this.afterLoadRecord(pForm, pAction);

            return true
        }

        if(this.editMode){
            this._afterUpdateRecord(olRec, pAction);
            this.afterUpdateRecord(olRec, pAction);
        }
        else {
            this._afterSaveRecord(olRec, pAction);
            this.afterSaveRecord(olRec, pAction);
        }

        this.afterActionComplete(pAction);
        return true}
    /**
     * Fires after  Notificacion of actionComplete.
     * @event
     * @param {object} pAction the current action
     *
     * You can override this function if you need to customize it.
     */
    ,afterActionComplete: function (pAction){
    }
    /**
     * Fires after  a validation occurs
     * @event
     * @param {Object} pFrm  the current Form
     * @oaram {bool}   pVAlid Validation result
     *
     * You can override this function if you need to customize it.
     */
    ,clientvalidation: function(pFrm, pValid){
        this.buttons[this.buttons.findIndexByCol("btnUPD", "name")].enable();
    }
    /**
     *  Basic functionality of saving data from form. Assigns Edit or Insert  mode properties based on pkField value
     */
    ,save: function(){
        var olData={};
        if (null === this.pkField || undefined === this.pkField){
            Ext.Msg.show({
                title:  'ATENCION',
                msg:    'NO SE HA CONFIGURADO EL PARAMETRO "pkField"<br>OPERACION CANCELADA',
                buttons: Ext.Msg.OK,
                fn:      function(){},
                animEl:  'elId',
                minWidth: 200,
                icon:   Ext.MessageBox.WARNING
             });
            return false;
        }
        var ilId = this.getForm().findField(this.pkField).getValue();
        if (ilId > 0 ) {
            olData[this.pkField] = ilId;
            this.editMode =true;
            this.insertMode=false;
        }
        else{
            this.editMode =false;
            this.insertMode=true;
        }
        //this.getForm().submit({params:olData});
        /* @event */
        if (this.beforeSubmit()){
            this.getForm().submit({params:olData});
        }
    }
    /**
     * Fires after  an Save action is executed to Update a 'master' grid, if is defined
     * @param {Object} pRec the current Record
     * @param {Object} pAction the current Action Object
     *
     */
    ,_afterSaveRecord: function(pRec, pAction){
        if (!this.masterId) return;
        var olStr = Ext.getCmp(this.masterId).getStore();
        var olRec = olStr.recordType;
        var olR   = new olRec();
        Ext.iterate(pRec.data, function(pNom, pVal, pRec){
            olR.set(pNom, pVal );
            })
        olStr.insert(0, olR);
    }
    /**
     * Fires before Loading data received from server
     * @event
     * @param {object} pForm    the submitted form
     * @param {object} pAction  the current action
     *
     * You can override this function if you need to customize it.
     */
    ,beforeLoadRecord: function (pForm, pAction){
        return true;
    }
    /**
     * Fires after  an Save action is executed.
     * @event
     * @param {Object} pRec the current Record
     * @param {Object} pAction the current Action Object
     * @rev fah 09/10/2011
     * You can override this function if you need to customize it.
     */
    ,afterSaveRecord: function(pRec, pAction){
        this.showMsg('NOTIFICACION',pAction.result.msg || "REGISTRO GRABADO");
    }
    /**
     * Fires after  an LOad action is executed.
     * @event
     * @param {Object} pRec the current Record
     *
     * You can override this function if you need to customize it.
     */
    ,afterLoadRecord: function(pRec){}
    /**
     * Fires after  an update action is executed. Locates the edited record in Master Grid, and updates with data from Form
     * @param {Object} pRec the current Record
     *
     * You can override this function if you need to customize it.
     */
    ,_afterUpdateRecord: function(pRec,pAction){
        this.updateMasterCt(pRec)
    }
    /**
     * Fires after  an update action is executed. Locates the edited record in Master Grid, and updates with data from Form
     * @event
     * @param {Object} pRec the current Record
     * @rev fah 09/10/2011
     * You can override this function if you need to customize it.
     */
    ,afterUpdateRecord: function(pRec,pAction){
        this.showMsg('NOTIFICACION',pAction.result.msg || "REGISTRO MODIFICADO");
    }
    /**
     * Updates the grid record (if any)
     * @event
     * @param {Object} pRec the current Record
     *
     */
    ,updateMasterCt: function(pRec){
        if (!this.masterId) return;
        var olGrd = Ext.getCmp(this.masterId) || null;
        if (null == olGrd) return;                   // if the 'master' does not exist
        var olStr = olGrd .getStore();
        ilId = this.getForm().findField(olStr.reader.meta.idProperty).getValue(); // value of "id" field
        if (!ilId) {
            alert("ATENCION !!!   No se ha definido ID de Registro en Store.reader.meta.idProperty")
            return
        }

        ilIdx =  olStr.find(olStr.reader.meta.idProperty, ilId);
        if (!ilIdx) return;
        var olRec = olStr.getAt(ilIdx)               // The record with id
        var alRec = this.getModifiedValues();       // Modified Values from form
        Ext.iterate(alRec, function(item, i) {      // Updates the corresponding grid's recordset
            olRec.set(item, i);
        }, this)
        if (undefined != olGrd.currentRecord && olGrd.currentRecord >= 0 ){
            var olSm = olGrd.getSelectionModel()
            olSm.selectRow(olGrd.currentRecord);
        }
    }
    ,
    getIdValue: function(){
        if (this.idProperty) return this.getForm().findField(this.idProperty).getValue;
        if (!this.masterId)  {
            return false;
        }
        var olStr = Ext.getCmp(this.masterId).getStore();
        return this.getForm().findField(olStr.reader.meta.idProperty).getValue();
    }
    /**
     * Fires after  an Destroy /Delete action is executed.
     * @event
     * @param {Object} pRec the current Record
     *
     * You can override this function if you need to customize it.
     */
    ,afterDeleteRecord: function(){}
    /**
     * Fires after  an failfull action is executed.
     * @event
     * @param {Object} pForm The current Form
     * @param {Object} pAction action firing the event
     *
     * You can override this function if you need to customize it.
     */
    ,actionFail: function(pForm, pAction){
        switch (pAction.failureType) {
            case Ext.form.Action.CLIENT_INVALID:
                this.showMsg('ALERTA','No puede grabar datos invalidos ');//Ext.Msg.alert('Falló', 'No puede grabar datos invalidos ');
                break;
            case Ext.form.Action.CONNECT_FAILURE:
                this.showMsg('ALERTA','Problemas de comunicacion con el servidor ');//Ext.Msg.alert('Falló', 'Problemas de comunicacion con el servidor');
                break;
            case Ext.form.Action.SERVER_INVALID:
                this.showMsg('NOTIFICACION',pAction.result.msg); //Ext.Msg.alert('Falló', pAction.result.msg);
        }
        return true}
    /**
     * To present a message
     * @event
     * @param {string}   Message to display
     * @param {function} Callback function
     * @param {object}   options for dialog
     *
     * You can override this function if you need to customize it.
     */
    ,showMsg: function(pTitl, pMsg, pFun, pOpt){
        Ext.Msg.show({
           title:pTitl ||'NOTIFICACION',
           msg: pMsg,
           buttons: Ext.Msg.OK,
           fn: pFun || function(){},
           animEl: 'elId',
           minWidth: 200,
           icon: Ext.MessageBox.QUESTION
        });
    }
    /**
     * Delete a record
     * @event
     *
     * You can override this function if you need to customize it.
     */
    ,deleteRec: function(){
        Ext.Msg.confirm(
           'CONFIRMACION',
           (this.contentName? 'DESEA ELIMINAR ESTE(A) ' + this.contentName + ' ?' : 'DESEA ELIMINAR ESTE REGISTRO?'),
           function(pBtn, pDat){
                if (pBtn == "yes"){
                    Ext.MessageBox.show({
                        msg: 'ELIMINANDO.....'
                        , progressText: 'Eliminando '
                        , width:300
                        , wait:true
                        , waitConfig: {interval:200}
                        , icon:'ext-mb-download' //custom class in msg-box.html
                        , animEl: 'mb7'
                    });
                    this.api.destroy({data:this.getIdValue()},
                        (function(){
                            Ext.MessageBox.hide();
                            this.getForm().reset();
                            if (!this.masterId) return;
                            var olGrd = Ext.getCmp(this.masterId);
                            var olStr = olGrd .getStore();
                            ilId = this.getForm().findField(olStr.reader.meta.idProperty).getValue()
                            olStr.remove(olStr.getById(ilId));
                            olStr.commitChanges();
                        }).createDelegate(this)
                    )
                }
            }
            ,this
        );
    }
    // @TODO:   is this betten than clientvalidation event?
    //,enableWriteButtons: function(){
    //    this.
    //}
    //,disableWriteButtons: function(){
    //
    //}
    //,listeners: {change: function(){
    //        if (this.isValid())
    //            this.enableWriteButtons()
    //        else
    //            this.disableWriteButtons()
    //    }
    //}

}); // eo extend
Ext.reg("Ext.ux.app.directForm", Ext.ux.app.directForm);
// eof
