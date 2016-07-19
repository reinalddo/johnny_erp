Ext.namespace("Ext.ux");

Ext.ux.RemoteCheckboxGroup = Ext.extend(Ext.form.CheckboxGroup, {
    url: '',
    method: 'POST',
    params: '',
    defaultItems: [{boxLabel: 'Cargando...', hidden: true, disabled: true }],
    generateChecboxHandler: null,
    successHandle: function(response, opts) {
        var data = Ext.decode(response.responseText);
        if (data.success && cbObj.generateChecboxHandler != null)
        {
            cbObj.panel.getComponent(0).removeAll();
            var item;
            for (var i = 0; i < data.count; ++i) {
                item = cbObj.generateChecboxHandler(data.rows[i]);
                cbObj.panel.getComponent(0).add(item);
            }
            cbObj.panel.getComponent(0).doLayout();
        }
        myMask.hide();
    },

    failureHandle: function() {},

    onRender: function(H, F) {
        myMask = new Ext.LoadMask(this.ownerCt.container, { msg: "Please wait..." });
        myMask.show();
        cbObj = this;

        Ext.Ajax.request({
            //headers:['Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8'],
            method: this.method,
            url: this.url,
            params: this.params,
            autoAbort: false,
            success: this.successHandle,
            failure: this.failureHandle
        });

        this.items = this.defaultItems;
        Ext.ux.RemoteCheckboxGroup.superclass.onRender.apply(this, arguments);
    }
});
Ext.reg("remotecheckboxgroup", Ext.ux.RemoteCheckboxGroup);
