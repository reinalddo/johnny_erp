/*  Basic Combo Box with remote XMl dataSource. Defines a comboBox that reads a remote XML structure with a value field (cod) and a text field (txt)
 *   @package	gen
 *   @subpackage 
 *   @author	fah 08/01/08 
 **/
Ext.ns('gen');
/*  @class gen.cmbReader   Generic XML reader, specially adapted for comboboxes. Reads a recordset of records called  'record', with fields defined via pFlds parameter
 *  @param  pCnf    object  Configuration object of this Xml reader
 *  @param  pFlds   array   Array of fields to read
 **/
gen.XmlReader = function(pCnf, pFlds) { // Generic XML Reader for comboboxes
    var config = pCnf || {};
    var cnf = {record: 'record'
	,id: pCnf.id ||'cod'}
    var flds= flds || ['cod','txt']
    Ext.applyIf(pCnf, {
	record: 'record'
	,id: pCnf.sqlId
    });
    gen.XmlReader.superclass.constructor.call(this, config, flds); // esta bien incluido los campos????
};
Ext.extend(gen.XmlReader, Ext.data.XmlReader);
Ext.reg('genXmlReader', gen.XmlReader); 

/*  @class gen.dsComboStore   Generic Data Store specially adapted for comboboxes with remote data.
 *  This class have the following defaults:
 *      - url: '../Ge_Files/GeGeGe_queryToXml.php' the server side xml generator
 *      - method: 'POST'
 *      - record: 'record' uses the 'record' property of pCnf parameter. (the Xml tag that identifies every record)
 *      - id:     'cod',  uses recId property of pCnf parameter, (This field identifies every record in dataset, must be unique)
 *      - fields: ['cod','txt'] use 'fields' property of pCnf parameter. (The list of fields to parse)
 *  MUST BE DEFINED:
 *      - sqlId   property to identify the SQL instruction to execute at server side by the script defined in url
 *      
 *  @param  pCnf    object  Configuration object of this dataStore
  **/
gen.dsComboStore = function(pCnf) {
    var config = pCnf || {};
    Ext.applyIf(config, {
	proxy: 		new Ext.data.HttpProxy({url: config.url || '../Ge_Files/GeGeGe_queryToXml.php', metod: 'POST'})
	,reader: 	new Ext.data.XmlReader({record: config.record || 'record',  id: config.recId ||'cod'}, config.fields || ['cod','txt'])
	,baseParams: {id: config.sqlId, query:config.qry || ''}
    });
    gen.dsComboStore.superclass.constructor.call(this, config);
};
Ext.extend(gen.dsComboStore, Ext.data.Store);

/*  @class gen.dsComboStore   Generic ComboBox with remote xml Data Source
 *  This class have the following defaults:
 *      - dispalyField:  txt
 *      - valueField:	 cod
 *      - mode:			 remote
 *  MUST BE DEFINED in pCnf param object:
 *      - url	  Script to execute al server side to generate xml response (defaults to '../Ge_Files/GeGeGe_queryToXml.php')
 *      - sqlId   property to identify the SQL instruction to execute at server side by the script defined in url
 *      
 *  @param  pCnf    object  Configuration object of this dataStore
  **/
gen.cmbBoxClass = function (pCnf){
    var config = pCnf || {};
    Ext.applyIf(config, {
        displayField:	'txt'
        ,valueField:     'cod'
        ,allowBlank: false
        ,selectOnFocus:true
        ,typeAhead: 		true
        ,mode: 'remote'
        ,forceSelection: true
        ,emptyText:''
        ,allowBlank:false
        ,listWidth: 350
        ,listClass: 'x-combo-list-small'
        ,width: 160
        ,id:    Ext.id()
        ,width: 160
        ,minChars:  6
        ,triggerAction: "all"
        ,allQuery: ""
        ,store : new gen.dsComboStore({url: config.url || '../Ge_Files/GeGeGe_queryToXml.php', sqlId:config.sqlId})
        ,lazyRender: true
        ,cancelOnEsc: true
        ,completeOnEnter:false
        ,forceSelection:true
    /*
     *  @method Extracts the complete 'selected' record from the objects recordset.
     *
     *
     **/
    ,getSelectedRecord: function(){
	var ilIdx =this.getSelectedIndex();
	if (ilIdx >=0)	return this.getStore().getAt(this.getSelectedIndex())
	else return false;
    }
    /*
     *  @method Extracts an 'aditional' field from selected record when it contains extra fields not sown in combo
     *
     *@param pField  Field name to extract
     **/
    ,getXmlField: function(pField){
	return(Ext.DomQuery.selectValue(pField,this.getSelectedRecord().node));
    }
    })
    
    gen.cmbBoxClass.superclass.constructor.call(this, config);

}
Ext.extend( gen.cmbBoxClass, Ext.form.ComboBox)
Ext.reg('genCmbBox', gen.cmbBoxClass); 
