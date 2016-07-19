/*
 * 	Panel Central del modulo Caja
 *  @author     Fausto Astudillo
 *  @editor		Juan Camilo Vélez 
 *  @date       04/Feb/09
*/
Ext.BLANK_IMAGE_URL = "/AAA/AAA_SEGURIDAD_2_2/LibJava/ext-2.0/resources/images/default/s.gif"
Ext.onReady(function(){
    Ext.QuickTips.init();
    olDet=Ext.get('divDet');
    var olAccion = new Ext.Action({
        text: 'Accion ',
        handler: function(){
            fManejador(this.id);
            Ext.example.msg('click', 'Ha presionado la opcion ' + this.text);
        },
        iconCls: 'blist'
    });
	var slWidth="width:250px; text-align:'left'";
    fmQry = new Ext.FormPanel({
        labelWidth: 50, // label settings here cascade unless overridden
        url:'',
        bodyStyle:'padding:5px 5px 0',
        autoheight:true,
        autowidth: true,
		border:false,
		frame:false,
        defaults: {width: 35},
        defaultType: 'textfield',
        items: [{
            xtype:'fieldset', title: '', autoHeight:true, defaultType: 'textfield',
            collapsible: true, width: 150,
			frame:false,
            items: [{fieldLabel: 'Anio',    name: 'txt_AnioOp', id: 'txt_AnioOp', width: 35, allowBlank:false },
			{fieldLabel: 'Semana', name: 'txt_Seman',  id: 'txt_Seman',  width: 35, align: "right", allowBlank:false}
			]
        }]
    });
    var slUrlBase = "../Op_Files/OpTrTr_contenedores.php?init=1&auto=1&pUrl=";
    //botón 1
    btVer1 = new Ext.Button ();
    btVer1.text= "Funcion 1";
	btVer1.cls= "boton-menu";
	btVer1.tooltip="Tooltip de la función 1";
    btVer1.handler= function(){
                    var slUrl = slUrlBase+'OpTrTr_contenedores.php'+ "&" + fParamQry()
                    addTab({id:'gridConten1', title:'Función 1', url:slUrl})
                    }
    fmQry.add(btVer1);
    
    //botón 2
    btVer2 = new Ext.Button ();
    btVer2.text= "Funcion 2";
	btVer2.cls= "boton-menu";
	btVer2.tooltip="Tooltip de la función 2";
    btVer2.handler= function(){
                    var slUrl = slUrlBase+'OpTrTr_contenedores.php'+ "&" + fParamQry()
                    addTab({id:'gridConten2', title:'Función 2', url:slUrl})
                    }
    fmQry.add(btVer2);

    //botón 3
  
	fmQry.add({
		xtype:	'button',
		id:     'btnGridConten',
		cls:	 'boton-menu',
		tooltip: 'Tooltip de la función 3',
		text:    'Funcion 3',
		style:   slWidth ,
		handler: function(){
                    var slUrl = slUrlBase+'OpTrTr_contenedores.php'+ "&" + fParamQry()
                    addTab({id:'gridConten', title:'Función 3', url:slUrl})
                    }
	});

   
    fmQry.render(Ext.get('divQry'));


    var tb = new Ext.Toolbar();
    tb.render('north');     
   
   /*
    *   Formulario de selecc de emb
    **/
    var rdComboBase = new Ext.data.XmlReader({record: 'record', id:'cod'},['cod', 'txt']) ;
    dsCmbVapor = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST',
                    extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'OpTrTr_embarlist'}
    });
    dsCmbConten = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST',
                    extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'OpTrTr_contenlist'}
    });
    dsCmbDesti = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST',
                    extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'OpTrTr_destilist'}
    });
    dsCmbConsig = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST',
                    extraParams: {pAnio : Ext.get("txt_AnioOp"), pSeman : Ext.get("txt_Seman")}
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'OpTrTr_consiglist'}
    });
    dsCmbNaviera = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST',
                    extraParams: {pAnio : Ext.get("txt_AnioOp").getValue(), pSeman : Ext.get("txt_Seman").getValue()}
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'OpTrTr_navilist'}
    });
    dsCmbFruta = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST',
                    extraParams: {pAnio : Ext.get("txt_AnioOp").getValue(), pSeman : Ext.get("txt_Seman").getValue()}
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'OpTrTr_frutlist'}
    });


    dsCmbDestiFi = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST',
                    extraParams: {pAnio : Ext.get("txt_AnioOp").getValue(), pSeman : Ext.get("txt_Seman").getValue()}
            }),
            reader: 	rdComboBase,
            baseParams: {id : 'OpTrTr_destfilist'}
    });
    dsCmbNaviera.on("beforeload", function(){
        this.proxy.conn.extraParams.pVapo= Ext.get("txt_Embarque").getValue()
        this.baseParams.pVapo= Ext.get("txt_Embarque").getValue();
        this.baseParams.pAnio= Ext.get("txt_AnioOp").getValue();
        this.baseParams.pSeman= Ext.get("txt_Seman").getValue();
    })
   
   fmEmb =   new Ext.FormPanel({
        labelWidth: 80, // label settings here cascade unless overridden
        url:'',
        bodyStyle:'padding:5px 5px 0',
        autoheight:true,
        autowidth: true,
		border:false,
		frame:false,
        defaultType: 'textfield',
        items:[  ]
    });
    
	agOpt= new Array();
	agOpt['btnResMar']    ="../Op_Files/OpTrTr_restarjdiamarc.php";


	fmEmb.add({
		xtype:	'button',
		id:     'btnResMar',
		tooltip: 'Tooltip de la consulta1',
		style:   slWidth,
		cls:	 'boton-menu',
		text:    'Consulta1',          // Lo que hace la consulta 1 --------------------
		handler: fManejador
	});

	fmEmb.render('divAco2');
}   
)


function addTab(pPar){
      tabs_c.add({
      id: pPar.id,
      title: pPar.title,
      layout:'fit',
	  closable: true,
      autoLoad:{url:pPar.url + "&pObj=" + pPar.id+ "&" + fParamQry(), scripts: true, method: 'post'}
    }).show();
  }

function fParamQry() {
	slUrl="pSem=" 	+ fmQry.findById("txt_Seman").getValue() +
		"&pAnio=" 	+ fmQry.findById("txt_AnioOp").getValue() +
		"&pEmb="	+ fmEmb.findById("txt_Embarque").getValue() +
		"&pCont="	+ fmEmb.findById("txt_Conten").getValue()  +  
		"&pDest="	+ fmEmb.findById("txt_Desti").getValue()  +  
		"&pClie="	+ fmEmb.findById("txt_Consig").getValue() +
		"&pDestFi="	+ fmEmb.findById("txt_DestFi").getValue()+
		"&pNavi="	+ fmEmb.findById("txt_Naviera").hiddenField.getValue() +
                "&pFruta="	+ fmEmb.findById("txt_Fruta").hiddenField.getValue() +
                "&pDest="	+ fmEmb.findById("txt_Desti").hiddenField.getValue()
	;
	return slUrl;
}
/*
 *
 **/
function fParamObj() {
    olPar= { 
	pSem:     fmQry.findById("txt_Seman").getValue() ,
	pAnio:    fmQry.findById("txt_AnioOp").getValue() ,
	pEmb:     fmEmb.findById("txt_Embarque").getValue(),
	pCont:    fmEmb.findById("txt_Conten").getValue()  ,  
	pDest:    fmEmb.findById("txt_Desti").getValue()  , 
	pClie:    fmEmb.findById("txt_Consig").getValue() ,
	pDestFi:  fmEmb.findById("txt_DestFi").getValue() /*+
	pGrup=    fmEmb.findById("txt_Destino").getValue() +
	pDest=    fmEmb.findById("txt_Destino").getValue() + */
    };
	return olPar;
}
/*
*
*/
function fManejador(pObj){
	slUrl=agOpt[pObj.id] + "?" + fParamQry();
	window.open(slUrl, "abc", 'width=1000, height=700, resizable=1, menubar=1');
}

