/*
 *      Definicones generales de DataSources comunes
 **/
/*
 *      Personas, codigo de auxiliar, nombre
 **/
dsPerso= new Ext.data.Store({
	proxy: 		new Ext.data.HttpProxy({url: '../Ge_Files/GeGeGe_queryToXml.php', metod: 'POST'})
	,reader: new Ext.data.XmlReader({record: 'record',id: 'cod'}, ['cod','txt'])
	,baseParams: {id: "SvTrTr_personas"}
});
/*
 *      Referencias operativas
 **/
dsRefer= new Ext.data.Store({
	proxy: 		new Ext.data.HttpProxy({url: '../Ge_Files/GeGeGe_queryToXml.php', metod: 'POST'})
	,reader: new Ext.data.XmlReader({record: 'record',id: 'cod'}, ['cod','txt'])
	,baseParams: {id: "CoTrTr_referen"}
});

edCmbProv= new gen.cmbBoxClass({fieldLabel:"ord_CodComisionista", hiddenName:"ord_CodComisionista", sqlId:"CoTrTr_personas", minChars:4, width:220});
