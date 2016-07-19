/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Fausto Astudillo
 *  @date       12/Oct/07
*/
Ext.BLANK_IMAGE_URL = "/AAA/AAA_SEGURIDAD_2_2/LibJava/ext-2.0/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.QuickTips.init();
    olDet=Ext.get('divDet');
    var slWidth="width:250px; text-align:'left'";
    
   /*
    *   Ejemplo de manejo de ComboBox para opciones de filtrado de datos de emb
    **
    var rdComboBase = new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;  // Origen de datos Generico
    
    dsCmbNaviera = new Ext.data.Store({
        proxy: 	new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',				// Script de servidor generico que accede a BD
                    metod: 'POST',
                    extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}    //Parametros para obtener datos segun el contexto de la consulta sql
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'XXXX_sufijo'}		// Parametro Basico: ID de consulta SQl (predefinida en PHP como variable de sesion)
    });
    dsCmbNaviera.on("beforeload", function(){		// para actualizar parametros en cada ejecucion si han existido valores modificados
        this.proxy.conn.extraParams.pVapo= Ext.get("txt_Embarque").getValue()
        this.baseParams.pVapo= Ext.get("txt_Embarque").getValue();
        this.baseParams.pAnio= Ext.get("txt_AnioOp").getValue();
        this.baseParams.pSeman= Ext.get("txt_Seman").getValue();
    })
    */
  
}   
)//on REady

/*
 *
 **/

/*
*	Agregador de componentes al tab-panel
*/
function addTab(pPar){
      tabs_c.add({
      id: pPar.id,
      title: pPar.title,
      layout:'fit',
	  closable: true,
      autoLoad:{url:pPar.url + "&pObj=" + pPar.id+ "&" + fParamQry(), scripts: true, method: 'post'}
    }).show();
  }