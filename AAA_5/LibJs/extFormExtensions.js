/*
 * Add a method to the BasicForm that clears the isDirty flag to return "false" again.
 **/
Ext.onReady(function(){
Ext.override(Ext.form.BasicForm,{
    clearDirty : function(nodeToRecurse){
        nodeToRecurse = nodeToRecurse || this;
        nodeToRecurse.items.each(function(f){
            if(f.items){
                this.clearDirtyFlag(f);
            } else if(f.originalValue && f.getValue()){
                f.originalValue = f.getValue();
            }
        });
    }
});
});
Ext.override(Ext.form.Field, {
   /*
    *   Sets the label of a field after it is rendered
    **/
   setLabel: function(text){
      var r = this.getEl().up('div.x-form-item');
      r.dom.firstChild.firstChild.nodeValue = String.format('{0}', text);
   },
   /*
    *   Overide to enable tooltip for fields. Tip text is defined in toolTip Property
    **/
   afterRender : function() {
        if(this.toolTip){
                Ext.QuickTips.register({
                target:  this.getEl(),
                title: '',
                text: this.toolTip,
                enabled: true
            });
            var label = findLabel(this);
            if(label){
                Ext.QuickTips.register({
                    target:  label,
                    title: '',
                    text: this.toolTip,
                    enabled: true
                });
            }
          }
          Ext.form.Field.superclass.afterRender.call(this);
  }
});

var findLabel = function(field) {

    var wrapDiv = null;
    var label = null
    //find form-element and label?
    wrapDiv = field.getEl().up('div.x-form-element');
    if(wrapDiv)
    {
        label = wrapDiv.child('label');
    }
    if(label) {
        return label;
    }

    //find form-item and label
    wrapDiv = field.getEl().up('div.x-form-item');
    if(wrapDiv)
    {
        label = wrapDiv.child('label');
    }
    if(label) {
        return label;
    }
}
