/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Fausto Astudillo
 *  @date       12/Oct/07
*/
Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
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
   
Ext.getDom('north').innerHTML = '<div id="north_left"></div><div id="north_right"></div>';

//calendario fecha de corte
    var dr = new Ext.FormPanel({
	labelWidth: 90,
	frame: false,
	title: '',
	bodyStyle:'padding:5px 5px 0',
	border: false,
	width: 250,
	defaults: {width: 100},
	defaultType: 'datefield',
	items: [{
	      fieldLabel: 'Fecha de Corte',
	      name: 'txtFecha',
	      id: 'txtFecha',
	      value: new Date().format('d-M-y'),
	      format:'d-M-y',
	      endDateField: 'enddt' // id of the end date field
	}]
      });
    var slWidth="width:250px; text-align:'left'";
    dr.add({xtype:	'button',
	id:     'btnCxc',
	cls:	 'boton-menu',
	tooltip: 'Cuentas por Cobrar',
	text:    'Cuentas por Cobrar',
	style:   slWidth ,
	handler: function(){
                    var slUrl = "CoTrTr_salcxcgrid.php?init=1&pCuenta=C&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
                    addTab({id:'gridConten', title:'Cuentas por Cobrar', url:slUrl});
                    //var w = Ext.getCmp('pnlIzq');
					//w.collapsed ? w.expand() : w.collapse();
	    }
    });
    
    dr.add({xtype:	'button',
	id:     'btnCxp',
	cls:	 'boton-menu',
	tooltip: 'Cuentas por Pagar',
	text:    'Cuentas por Pagar',
	style:   slWidth ,
	handler: function(){
                    var slUrl = "CoTrTr_salcxcgrid.php?init=1&pCuenta=C&pPagina=0&pCierre="+  Ext.getCmp('txtFecha').value;
                    addTab({id:'gridConten2', title:'Cuentas por Pagar', url:slUrl});
                    //var w = Ext.getCmp('pnlIzq');
					//w.collapsed ? w.expand() : w.collapse();
	    }
    });
    dr.render(document.body, 'divIzq01');
    
//parte derecha

 var dr2 = new Ext.FormPanel({
	labelWidth: 90,
	frame: false,
	title: '',
	bodyStyle:'padding:5px 5px 0',
	border: false,
	width: 250,
	defaults: {width: 100},
	defaultType: 'datefield'
 });
 
  dr2.add({xtype:	'button',
	id:     'btnCxp',
	cls:	 'boton-menu',
	tooltip: 'Grabar',
	text:    'Grabar',
	//style:   slWidth ,
	handler: function(){
                    var slUrl = "CoTrTr_salcxcgrid.php?init=1&pCuenta=C&pPagina=0&pCierre="+  Ext.getCmp('txtFecha').value;
                    addTab({id:'gridConten2', title:'Grabar', url:slUrl});
                    
	    }
    });
 
 dr2.render(document.body, 'divpnlDer');  
    
/*
//tab de inicio		
tabs_c.add({ contentEl:'pnlCen',
                        title: 'Inicio',
                        closable:false,
			html: '<div id="centeyy"/>',
                        autoScroll:true
            }).show();
/**/
    
//botones de reportes

		new Ext.Button({
        text: 'Reporte 1',
        handler: addTab,
        
        //iconCls:'new-tab'
		}).render(document.body, 'divIzq03');
		
		new Ext.Button({
        text: 'Reporte 2',
        handler: addTab,
      
        //iconCls:'new-tab'
		}).render(document.body, 'divIzq03');
		new Ext.Button({
		
        text: 'Reporte 3',
        handler: addTab,
       
        //iconCls:'new-tab'
		}).render(document.body, 'divIzq03');


}   
)//on REady



/*
*	Agregador de componentes al tab-panel
*/


function addTab(pPar){
      tabs_c.add({
      id: pPar.id,
      title: pPar.title,
      layout:'fit',
      closable: true,
      autoLoad:{url:pPar.url + "&pObj=" + pPar.id /*+ "&" + fParamQry()*/, scripts: true, method: 'post'}
    }).show();
  }