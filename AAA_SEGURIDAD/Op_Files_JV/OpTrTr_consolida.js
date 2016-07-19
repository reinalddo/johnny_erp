/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Fausto Astudillo
 *  @date       12/Oct/07
*/
Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"
    Ext.QuickTips.init();
    olDet=Ext.get('divDet');
    var slWidth="width:250px; text-align:'left'";
    
    Ext.namespace("app", "app.consol");
    
    fCargaPermiso("OpTrTr","CTI",app.consol.repConsol,1);
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
   
    goXmlR= new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;  // Origen de datos Generico
    
	goDsEmpr = new Ext.data.Store({
        proxy: 	new Ext.data.HttpProxy({
			url: '../Ge_Files/GeGeGe_queryToXml.php',				// Script de servidor generico que accede a BD
			metod: 'POST'
		}),
		reader: 	goXmlR,
		baseParams: {id : "cns_empresas"}		// Parametro Basico: ID de consulta SQl (predefinida en PHP como variable de sesion)
    });   
   
Ext.getDom('north').innerHTML = '<div id="north_left" style="width:500px;"></div><div id="north_right"></div>';

//calendario fecha de corte
var olFrmParams = new Ext.FormPanel({
    labelWidth: 90
    ,frame: false
    ,title: ''
	,id:"frmParams"
    ,bodyStyle:'padding:5px 5px 0'
    ,border: false
    ,width: 350
	,defaults: {width: 200}
	,defaultType: 'datefield'
	,items: [{
		    xtype:"multiselect"
		    ,fieldLabel:"Empresa"
		    ,name:"cns_Empresa"
				,id:"cns_Empresa"
				,store: goDsEmpr
		    /*,dataFields:["cod", "txt"]*/
		    ,valueField:"cod"
		    ,displayField:"txt"
		    ,width:250
		    ,height:200
		    ,allowBlank:false
		},{
		    xtype: "genCmbBox"
		    ,fieldLabel:'Semana'
		    ,id:'cns_Semana'
		    ,sqlId:"cns_periodos"
		    ,name:'cns_Semana'
		    ,width:150
		    //store: dsCmbConten,
		    ,hiddenName:'cns_Periodo'
		    ,minChars: 3
		    ,triggerAction: 	'all'
		    ,forceSelection: true
		    ,emptyText:'Debe seleccionar una Semana'
		    ,allowBlank:     false
		    ,listWidth:      250
		}]
    });
    var slWidth="width:99%; text-align:'left'";
    var slUrlBase = "OpCnTr_consolida.pro.php?";
    olFrmParams.add({xtype:	'button',
		id:     'btnTar',
		cls:	 'boton-menu',
		tooltip: 'Incorpora Las Tarjas de las companias seleccionadas',
		text: 	'Consolidar Tarjas',
		style:   slWidth ,
		//handler: function(){addTab({id:'gridConsolidacion', proc:"tar", title:'Consolidacion', url:slUrlBase})}
		handler: function(){
		    ejecutarProc("tar");
		}
		/*handler: function(){
						var slUrl = "CoTrTr_salcxcgrid.php?init=1&pTipo=C&pPagina=0&pCierre="+ Ext.getCmp('txtFecha').value;
						addTab({id:'gridConten', title:'Cuentas por Cobrar', url:slUrl});
						//var w = Ext.getCmp('pnlIzq');
						//w.collapsed ? w.expand() : w.collapse();
			}*/
		});
    olFrmParams.add({xtype:	'button',
		id:     'btnCxc',
		cls:	'boton-menu',
		tooltip: 'Incorpora movimientos de CXC e inventarios de las companias seleccionadas',
		text: 	'Consolidar CXC e Inv.',
		style:   slWidth ,
		//handler: function(){addTab({id:'gridConsolidacion', proc:"cxc", title:'Consolidacion', url:slUrlBase})}
		handler: function(){
		    ejecutarProc("cxc");
		}
    });
  
    olFrmParams.render(document.body, 'divIzq01');
	goDsEmpr.load();
	
	//debugger;
    //Ext.getCmp('pnlIzq').collapse();
        /*olPanel=Ext.getCmp('pnlIzq');
        olPanel.width=700;
	olPanel.setSize(1000,1500);*/
	//olPanel.setStyleAttribute("width", "700");
	//olPanel.setStyleAttribute("backgroundColor", "#eeeeee" );

	/*olPanel.collapsible=true;
        if (olPanel.collapsed)olPanel.expand();
	olPanel.doLayout();*/
})//on REady



/*
*	Agregador de componentes al tab-panel
*/
function addTab(pPar){
	var olPar = Ext.getCmp("frmParams").getForm().getValues();
	olPar.cns_Proc= pPar.proc;
    tabs_c.add({
      id: pPar.id,
      title: pPar.title,
      layout:'fit',
      closable: true,
      autoLoad:{url:pPar.url,
            params: olPar,
            scripts: true,
            method: 'post'}
    }).show();
  }
  
function ejecutarProc(proc){
    /*if (!Ext.getCmp("cns_Empresa").value){
	Ext.Msg.alert('AVISO', "Seleccione empresa a procesar");
	return;
    }*/
    if (!Ext.getCmp("cns_Semana").value){
	Ext.Msg.alert('AVISO', "Seleccione semana a procesar");
	return;
    }
    
    
    var box = Ext.MessageBox.wait('Ejecutando proceso, por favor espere', 'Sistema');
    var olPar = Ext.getCmp("frmParams").getForm().getValues();
    olPar.cns_Proc= proc;
    Ext.Ajax.request({
	url: 'OpCnTr_consolida.pro.php?',
	timeout: 60000,
	success: function(pResp, pOpt){
	    box.hide();
	    //debugger;
	    var olRsp = eval("(" + pResp.responseText + ")")
	    //debugger;
	    if ("" != olRsp.mensaje){
		//debugger;
		Ext.Msg.alert('Alerta',olRsp.mensaje);
		
	    }else{
		Ext.Msg.alert('Alerta',"Error al grabar");
	    }
	    //olRec.tmp_Valor = olRec.tmp_Cantidad * olRec.tmp_PrecUnit
	},
	failure: function(pResp, pOpt){
	    box.hide();
	    //debugger;
	    Ext.Msg.alert('AVISO', "Error al actualizar comprobante "+slTipoComp+slNumComp);
	},
	headers: {
	   'my-header': 'foo'
	},
	params: olPar,
	scope:  this
    });
}


function fCargaPermiso($modulo, $key, $var, $bool){
        
    var olDat = Ext.Ajax.request({
	url: '../LibPhp/CoTrTr_variablesglobales'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                //debugger;
		$var = olRsp.valor;
		app.consol.permiso = olRsp.valor;
		
		if (false == olRsp.valor){
		    Ext.getCmp('btnTar').disabled();
		    Ext.Msg.alert('Alerta','Usted no tiene permisos para ejecutar este proceso');
		}
	    }
	}
	/*,success: function(pRes,pPar){
	    Ext.getCmp("ilNumCheque").setValue(0)
	    Ext.getCmp("flSaldo").setValue(0)
	    }
	,failure: function(pObj, pRes){
	    Ext.getCmp("ilNumCheque").setValue(0)
	    Ext.getCmp("flSaldo").setValue(0)
	    }*/
	,params: {pModulo: $modulo, pKey: $key, pBool: $bool}//, pAux:ilAuxi, pBan:true, pTip: Ext.getCmp("slFormaPago").getValue()}
    })
}