/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Fausto Astudillo
 *  @date       12/Oct/07
*/
Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
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
	items: [new Ext.form.ComboBox({
					fieldLabel: 'Empresa',
					id:'empresa',
					name:'empresa',
					width:150,
					//store: dsCmbVapor,
					displayField:	'txt',
					valueField:     'cod',
					hiddenName:'cnt_Embarque',
					selectOnFocus:	true,
					typeAhead: 		true,
					mode: 'remote',
					minChars: 3,
					triggerAction: 	'all',
					forceSelection: true,
					emptyText:'',
					allowBlank:     false,
					listWidth:      250
				    }),
		new Ext.form.ComboBox({
					fieldLabel:'Semana',
					id:'semana',
					name:'semana',
					width:150,
					//store: dsCmbConten,
					displayField:	'txt',
					valueField:     'cod',
					hiddenName:'cnt_Conten',
					selectOnFocus:	true,
					typeAhead: 		true,
					mode: 'remote',
					minChars: 3,
					triggerAction: 	'all',
					forceSelection: true,
					emptyText:'',
					allowBlank:     false,
					listWidth:      250
				    })
		]
    });
    var slWidth="width:99%; text-align:'left'";
    var slUrlBase = "../Op_Files/OpTrTr_contenedores.php?init=1&auto=1&pUrl=";
    dr.add({xtype:	'button',
	id:     'btnCxc',
	cls:	 'boton-menu',
	tooltip: 'Consolidar',
	text:    'Consolidar',
	style:   slWidth ,
	handler: function(){addTab({id:'gridConsolidacion', title:'Consolidacion', url:slUrlBase+'OpTrTr_pedidos.php'})}
	/*handler: function(){
                    var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=C&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
                    addTab({id:'gridConten', title:'Cuentas por Cobrar', url:slUrl});
                    //var w = Ext.getCmp('pnlIzq');
					//w.collapsed ? w.expand() : w.collapse();
	    }*/
    });
    dr.add({xtype:	'button',
	id:     'btnCxc',
	cls:	 'boton-menu',
	tooltip: 'Consolidar',
	text:    'Consolidar',
	style:   slWidth ,
	handler: function(){addTab({id:'gridConsolidacion', title:'Consolidacion', url:slUrlBase+'OpTrTr_pedidos.php'})}
	/*handler: function(){
                    var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=C&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
                    addTab({id:'gridConten', title:'Cuentas por Cobrar', url:slUrl});
                    //var w = Ext.getCmp('pnlIzq');
					//w.collapsed ? w.expand() : w.collapse();
	    }*/
    });
    
    dr.render(document.body, 'divIzq01');



})//on REady



/*
*	Agregador de componentes al tab-panel
*/


function addTab(pPar){
      tabs_c.add({
      id: pPar.id,
      title: pPar.title,
      layout:'fit',
      closable: true,
      autoLoad:{url:pPar.url,
            params:{pObj: pPar.id},
            scripts: true,
            method: 'post'}
    }).show();
  }
  
var global_printer = null;  // it has to be on the index page or the generator page  always
function printmygridGO(obj){  global_printer.printGrid(obj);	} 
function printmygridGOcustom(obj){ global_printer.printCustom(obj);	}  	
function basic_printGrid(){
		global_printer = new Ext.grid.XPrinter({
			grid:grid1,  // grid object 
			pathPrinter:'../Libjs/ext/ux/printer',  	 // relative to where the Printer folder resides  
			logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
			pdfEnable: true,  // enables link PDF printing (only save as) 
			hasrowAction:false, 
			localeData:{
				Title:'',	
				subTitle:'',	
				footerText:'', 
				pageNo:'Page # ',	//page label
				printText:'Print',  //print document action label 
				closeText:'Close',  //close window action label 
				pdfText:'PDF'
            },useCustom:{  // in this case you leave null values as we dont use a custom store and TPL
				custom:false,
				customStore:null, 
				columns:[], 
				alignCols:[],
				pageToolbar:null, 
				useIt: false, 
				showIt: false, 
				location: 'bbar'
			},
			showdate:true,// show print date 
			showdateFormat:'Y-F-d H:i:s', // 
			showFooter:true,  // if the footer is shown on the pinting html 
			styles:'default' // wich style youre gonna use 
		}); 
		global_printer.prepare(); // prepare the document 
}

function basic_printGrid_exclude(){
		global_printer = new Ext.grid.XPrinter({
			grid:grid,  // grid object 
			pathPrinter:'./printer',  	 // relative to where the Printer folder resides  
			logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
			pdfEnable: true,  // enables link PDF printing (only save as) 
			hasrowAction:false, 
			excludefields:'4,',  // 0 based index , if it has numberer or action it counts as a column 
			localeData:{
				Title:'Array Grid Demo printing',	
				subTitle:'by XtPrinter',	
				footerText:'Report Footer goes here', 
				pageNo:'Page # ',	//page label
				printText:'Print',  //print document action label 
				closeText:'Close',  //close window action label 
				pdfText:'PDF'
            }, useCustom:{  // in this case you leave null values as we dont use a custom store and TPL
				custom:false,
				customStore:null, 
				columns:[], 
				alignCols:[],
				pageToolbar:null, 
				useIt: false, 
				showIt: false, 
				location: 'bbar'
			},
			showdate:true,// show print date 
			showdateFormat:'Y-F-d H:i:s', // 
			showFooter:true,  // if the footer is shown on the pinting html 
			styles:'default' // wich style youre gonna use 
		}); 
		global_printer.prepare(); // prepare the document 
}  