/*
 * 	Panel Central del modulo Operaciones
 *  @author     Fausto Astudillo
 *  @date       12/Oct/07
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
            xtype:'fieldset', title: 'Defina la semana', autoHeight:true, defaultType: 'textfield',
            collapsible: true, width: 150,
			frame:false,
            items: [{fieldLabel: 'Anio',    name: 'txt_AnioOp', id: 'txt_AnioOp', width: 35, allowBlank:false },
			{fieldLabel: 'Semana', name: 'txt_Seman',  id: 'txt_Seman',  width: 35, align: "right", allowBlank:false}
			]
        }]
    });
    olTreeLoader = new Ext.tree.TreeLoader({
        dataUrl:'OpTrTr_tree_emb.php',
        baseParams: {pPrdc: 0, id:'OpTrTr_panelop', pArbol:'A', pNivel:-1},
        uiProviders:{'col': Ext.tree.ColumnNodeUI }
    });
    olTreeLoader.on("beforeload", function(pTLdr, pNode) {
        var ilAnio =fmQry.form.findField("txt_AnioOp").getValue();
        var ilSem  =fmQry.form.findField("txt_Seman").getValue();
        if (ilSem <1)
            return false;
        if (pNode.isRoot) ilNiv=0;
        else ilNiv = Number(pNode.attributes.txt_tipo); //ilNiv = pNode.getDepth() -1;
        if (ilNiv >= 0 ) {
            var olParams = fAnalizaArbol(pNode, tree.tipo, ilNiv);
            pTLdr.baseParams = olParams;
        }
        if (ilNiv < 1 ) pTLdr.dfault = {pPrdc:0}
		if (tree.tipo != "E") {
			if (ilNiv == 1 ) pTLdr.dfault = {pPrdc:pNode.attributes.id}; // en el primer Nodo define el producto
			pTLdr.baseParams.pPrdc= 	 pTLdr.dfault.pPrdc;
		}
        pTLdr.baseParams.id= 	 'OpTrTr_panelop';
        pTLdr.baseParams.pNivel=  ilNiv;        
        pTLdr.baseParams.pAnioOp= ilAnio;
        pTLdr.baseParams.pSeman=  ilSem;
        pTLdr.baseParams.pArbol=  tree.tipo;
    }, this);
    btVer1 = new Ext.Button ();
    btVer1.text= "Precios Por Embarque";
	btVer1.cls= "boton-menu";
	btVer1.tooltip="Indice por Embarque / Vapor de Productores que han embarcado en la semana";
    btVer1.handler= function (){	fCargarTree("E")	};
    fmQry.add(btVer1);
    var slUrlBase = "../Op_Files/OpTrTr_contenedores.php?init=1&auto=1&pUrl=";
	fmQry.add({
		xtype:	'button',
		id:     'btnGridConten',
		cls:	 'boton-menu',
		tooltip: 'Contenedores registrados para la semana',
		text:    'Lista de Contenedores',
		style:   slWidth ,
		handler: function(){
                    var slUrl = slUrlBase+'OpTrTr_contenedores.php'+ "&" + fParamQry()
                    addTab({id:'gridConten', title:'Contenedores', url:slUrl})
                    }
	});
   	fmQry.add({
		xtype:	'button',
		id:     'btnGridPrecios',
		cls:	 'boton-menu',
		tooltip: 'Cuadro de Precios por productor',
		text:    'Cuadro de Precios',
		style:   slWidth ,
		handler: function(){
                    var slUrl = "../Op_Files/OpTrTr_precioscaptura.php?init=1&pObj=document.body&pPagina=1&"+fParamQry()
                    window.open(slUrl, "ppre", 'width=1024, height=670, resizable=1, menubar=1');
                    //addTab({id:'gridPrecios', title:'PRECIOS POR PRODUCTOR', url:slUrl})
                    }
	});
    
	fmQry.add({
		xtype:	'button',
		id:     'btnGridPedidos',
		cls:	 'boton-menu',
		tooltip: 'Pedidos realizados por clientes para la semana',
		text:    'Pedidos de Clientes',
		style:   slWidth ,
		handler: function(){addTab({id:'gridPedidos', title:'Pedidos', url:slUrlBase+'OpTrTr_pedidos.php'})}
	});
    fmQry.render(Ext.get('divQry'));

    var tree = new Ext.tree.ColumnTree({
        el:'divTabDet', //'divDet',
        autoWidth:true,
        autoHeight:true,
        rootVisible:false,
        animCollapse : false,
        animate : false,
        autoScroll:true,
        title: 'Orden de Embarques',
        columns:[
        {header:'Descripcion', width:350, dataIndex:'text'},
        {header:'Cantidad (Cjas.)', width:80, align: "right", dataIndex:'txt_cantidad'},
        {header:'Prec. Unit.', width:80, align: "right", dataIndex:'txt_precio',
           editor: new Ext.form.TextField({allowBlank: false})},
        {header:'Valor', width:80, align: "right", dataIndex:'txt_valor'}
    ],
        loader: olTreeLoader,
        root: new Ext.tree.AsyncTreeNode({id:0, text:'Detalles'})
    });
	treeEd = new Ext.tree.TreeEditor(tree, new Ext.form.Field({
        cancelOnEsc:true
        , completeOnEnter:true
        , ignoreNoChange:true
        , stateEvents:
        [{change:{scope:this, fn:fModifNode}}]
    }), 2);
 
    tree.render();
    var tb = new Ext.Toolbar();
    tb.render('north');     
   function fCargarTree1(){
        tree.tipo = "A";
        olTreeLoader.load(tree.root);
   }
   function fCargarTree2(){
        tree.tipo = "E";
        olTreeLoader.load(tree.root);
   }
   function fCargarTree(pTipo){
        tree.tipo = pTipo;
        olTreeLoader.load(tree.root);
   }

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
        items:[
            new Ext.form.ComboBox({fieldLabel:'VAPOR', 	    id:'txt_Embarque',    name:'txt_Embarque',
                width:150, 		store: dsCmbVapor, 			displayField:	'txt',	valueField:     'cod',
                hiddenName:'cnt_Embarque',			    		selectOnFocus:	true,	typeAhead: 		true,
                mode: 'remote',	minChars: 3,					triggerAction: 	'all',	forceSelection: true,
                emptyText:'',	allowBlank:     false,		    listWidth:      250}),
			new Ext.form.ComboBox({fieldLabel:'CONTENEDOR', 	    id:'txt_Conten',    name:'txt_Conten',
                width:150, 		store: dsCmbConten, 			displayField:	'txt',	valueField:     'cod',
                hiddenName:'cnt_Conten',			    		selectOnFocus:	true,	typeAhead: 		true,
                mode: 'remote',	minChars: 3,					triggerAction: 	'all',	forceSelection: true,
                emptyText:'',	allowBlank:     false,		    listWidth:      250,	value:' TODOS'}),
			new Ext.form.ComboBox({fieldLabel:'DESTINO', 	    id:'txt_Desti',    name:'txt_Desti',
                width:150, 		store: dsCmbDesti, 			displayField:	'txt',	valueField:     'cod',
                hiddenName:'cnt_Desti',			    		selectOnFocus:	true,	typeAhead: 		true,
                mode: 'remote',	minChars: 3,					triggerAction: 	'all',	forceSelection: true,
                emptyText:'',	allowBlank:     false,		    listWidth:      250,		value:' TODOS'}),
			new Ext.form.ComboBox({fieldLabel:'CONSIGNATARIO', 	    id:'txt_Consig',    name:'txt_Consig',
                width:150, 		store: dsCmbConsig, 			displayField:	'txt',	valueField:     'cod',
                hiddenName:'cnt_Consig',			    		selectOnFocus:	true,	typeAhead: 		true,
                mode: 'remote',	minChars: 3,					triggerAction: 	'all',	forceSelection: true,
                emptyText:'',	allowBlank:     false,		    listWidth:      250,	value:' TODOS'}),
                new Ext.form.ComboBox({fieldLabel:'FRUTA', 	    id:'txt_Fruta',    name:'txt_Fruta',
                width:150, 		store: dsCmbFruta, 			displayField:	'txt',	valueField:     'cod',
                hiddenName:'cnt_Fruta',			    		selectOnFocus:	true,	typeAhead: 		true,
                mode: 'remote',	minChars: 3,					triggerAction: 	'all',	forceSelection: true,
                emptyText:'',	allowBlank:     false,		    listWidth:      250,	value:' TODOS'}),
                new Ext.form.ComboBox({fieldLabel:'NAVIERA', 	    id:'txt_Naviera',    name:'txt_Naviera',
                width:150, 		store: dsCmbNaviera, 			displayField:	'txt',	valueField:     'cod',
                hiddenName:'cnt_Naviera',			    		selectOnFocus:	true,	typeAhead: 		true,
                mode: 'remote',	minChars: 3,					triggerAction: 	'all',	forceSelection: true,
                emptyText:'',	allowBlank:     false,		    listWidth:      250,	value:' TODOS'}),
		new Ext.form.ComboBox({fieldLabel:'DEST.FINAL', 	    id:'txt_DestFi',    name:'txt_DestFi',
                width:150, 		store: dsCmbDestiFi, 			displayField:	'txt',	valueField:     'cod',
                hiddenName:'cnt_DestFi',			    		selectOnFocus:	true,	typeAhead: 		true,
                mode: 'remote',	minChars: 3,					triggerAction: 	'all',	forceSelection: true,
                emptyText:'',	allowBlank:     false,		    listWidth:      250,	value:' TODOS'}),
            ]
    });
	agOpt= new Array();
	agOpt['btnResMar']    ="../Op_Files/OpTrTr_restarjdiamarc.php";
	agOpt['btnResPrecios']="../Op_Files/OpTrTr_restarjlistpre.php";
	agOpt['btnResPreciosCaj']="../Op_Files/OpTrTr_restarjlistpre_emp.php";
	agOpt['btnResPreciosCaj2']="../Op_Files/OpTrTr_resprecios.php";
	agOpt['btnResVapMarc']="../Op_Files/OpTrTr_restarjvapmarc.php";
	agOpt['btnResVapClie']="../Op_Files/OpTrTr_restarjvapclie.php";
	agOpt['btnResContPro']="../Op_Files/OpTrTr_restarjconten.php";
	agOpt['btnResContMar']="../Op_Files/OpTrTr_restarjcontmar.php";
    agOpt['btnResContMarN']="../Op_Files/OpTrTr_restarjcontmar.php?pRpt=N&";
	agOpt['btnResContCon']="../Op_Files/OpTrTr_restarjcontcon.php";
	agOpt['btnResGenEmb']="../Op_Files/OpTrTr_resgenembar.php";
	agOpt['btnResDestMar']="../Op_Files/OpTrTr_restarjdestmarc.php";
    agOpt['btnResMagInfo']="../Op_Files/OpTrTr_magapinfohtml.php";
    agOpt['btnResPagoBce']="../Op_Files/OpTrTr_pagosbceinfohtml.php";

	fmEmb.add({
		xtype:	'button',
		id:     'btnResMar',
		tooltip: 'Cantidades embarcadas por Dia, Productor y Marca',
		style:   slWidth,
		cls:	 'boton-menu',
		text:    'Resumen Diario marca',          // Grabar Registro de Ordenes --------------------
		handler: fManejador
	});

	fmEmb.add({
		xtype:	'button',
		id:     'btnResPrecios',
		tooltip: 'Lista Alfabetica de Precios por Marca y Tipo de Caja',
		text:	'Lista de Precios /Marca',
		disabled: false,
		cls:	 'boton-menu',
		handler:  fManejador
	});
	fmEmb.add({
		xtype:	'button',
		id:     'btnResPreciosCaj',
		tooltip: 'Lista Alfabetica de Precios por Marca y Tipo de Caja',
		text:	'Lista de Precios / Tipo Caja',
		disabled: false,
		cls:	 'boton-menu',
		handler:  fManejador
	});

	fmEmb.add({
		xtype:	'button',
		id:     'btnResPreciosCaj2',
		tooltip: 'Resumen de Precios, por Tipo de Caja, Marca y Precio',
		text:	'Resumen de Precios / Tipo Caja',
		disabled: false,
		cls:	 'boton-menu',
		handler:  fManejador
	});

	fmEmb.add({
		xtype:	'button',
		id:     'btnResVapMarc',
		cls:	 'boton-menu',
		tooltip: 'Cantidades embarcadas por Vapor, Consignatario Marca y productor',
		text:    'Resumen Vapor / Consig',
		style:   slWidth,
		handler: fManejador
	});
		fmEmb.add({
		xtype:	'button',
		id:     'btnResVapClie',
		cls:	 'boton-menu',
		tooltip: 'Cantidades embarcadas por Vapor, Consignatario Marca, Tipo Caja y productor',
		text:    'Resumen Vapor / Consig / Caja',
		style:   slWidth,
		handler: fManejador
	});
	fmEmb.add({
		xtype:	'button',
		id:     'btnResDestMar',
		cls:	 'boton-menu',
		tooltip: 'Cantidades embarcadas por Vapor, Destino, Consignatario Marca, Tipo Caja y productor',
		text:    'Resumen Vapor / Dest / Consig / Caja',
		style:   slWidth,
		handler: fManejador
	});		

	fmEmb.add({
		xtype:	'button',
		id:     'btnResContPro',
		cls:	 'boton-menu',
		tooltip: 'Listado de cantidades embarcadas por Contenedor',
		text:    'Resumen por Contenedor',
		style:   slWidth,
		handler: fManejador
	});


	fmEmb.add({
		xtype:	'button',
		id:     'btnResContMar',
		cls:	 'boton-menu',
		tooltip: 'Resumen embarcado por Contenedor, Marca',
		text:    'Marcas por Contenedor',          // Grabar Registro de Ordenes --------------------
        style:   slWidth,
		handler: fManejador
	});

	fmEmb.add({
		xtype:	'button',
		id:     'btnResContMarN',
		cls:	 'boton-menu',
		tooltip: 'Resumen embarcado por Contenedor, Marca, incluye el nombre del productor',
		text:    'Marcas por Contenedor c/Nombre',          // Grabar Registro de Ordenes --------------------
                style:   slWidth,
		handler: fManejador
	});
        
	fmEmb.add({
		xtype:	'button',
		id:     'btnResGenEmb',
		cls:	 'boton-menu',
		tooltip: 'Resumen General de Embarque, Naviera, Consignatario, destino Final',
		text:    'Res. General por Embarque',          // Grabar Registro de Ordenes --------------------
        style:   slWidth,
		handler: fManejador
	});
	
	fmEmb.add({
		xtype:	'button',
		id:     'btnResContCon',
		cls:	 'boton-menu',
		tooltip: 'Resumen embarcado por Consignatario, Contenedor y Marca',
		text:    'Consignatarios',          // Grabar Registro de Ordenes --------------------
        style:   slWidth,
		handler: fManejador
	});
    fmEmb.add({
		xtype:	'button',
		id:     'btnResMagInfo',
		cls:	 'boton-menu',
		tooltip: 'Resumen embarcado por Consignatario preparado para <b>MAGAP<b>',
		text:    'Cuadro Magap',          // Grabar Registro de Ordenes --------------------
        style:   slWidth,
		handler: fManejador
	});
    fmEmb.add({
		xtype:	 'button',
		id:      'btnResPagoBce',
		cls:	 'boton-menu',
		tooltip: 'Resumen de Liquidaciones a Pagar <b>BCE<b>',
		text:    'Cuadro Banco',          // Grabar Registro de Ordenes --------------------
        style:   slWidth,
		handler: fManejador
	});
    fmEmb.findById('txt_Embarque').on('beforequery',
            function (pEvt){
                dsCmbVapor.baseParams.pAnio=fmQry.findById('txt_AnioOp').getValue();
                dsCmbVapor.baseParams.pSeman=fmQry.findById('txt_Seman').getValue();    
            });
	fmEmb.findById('txt_Embarque').on('change',
            function (pEvt){
                dsCmbConten.baseParams.pEmb=fmEmb.findById('txt_Embarque').getValue();
                dsCmbConten.reload();
            });
	fmEmb.findById('txt_Conten').on('beforequery',
            function (pEvt){
                dsCmbConten.baseParams.pAnio=fmQry.findById('txt_AnioOp').getValue();
                dsCmbConten.baseParams.pSeman=fmQry.findById('txt_Seman').getValue();    
				dsCmbConten.baseParams.pEmb=fmEmb.findById('txt_Embarque').getValue();
            });
	fmEmb.findById('txt_Desti').on('beforequery',
            function (pEvt){
                dsCmbDesti.baseParams.pAnio=fmQry.findById('txt_AnioOp').getValue();
                dsCmbDesti.baseParams.pSeman=fmQry.findById('txt_Seman').getValue();    
				dsCmbDesti.baseParams.pEmb=fmEmb.findById('txt_Embarque').getValue();
            });
	fmEmb.findById('txt_Fruta').on('beforequery',
            function (pEvt){
                dsCmbFruta.baseParams.pAnio=fmQry.findById('txt_AnioOp').getValue();
                dsCmbFruta.baseParams.pSeman=fmQry.findById('txt_Seman').getValue();
		dsCmbFruta.baseParams.pEmb=fmEmb.findById('txt_Embarque').getValue();
            });
       
	fmEmb.findById('txt_Consig').on('beforequery',
            function (pEvt){
                dsCmbConsig.baseParams.pAnio=fmQry.findById('txt_AnioOp').getValue();
                dsCmbConsig.baseParams.pSeman=fmQry.findById('txt_Seman').getValue();    
				dsCmbConsig.baseParams.pEmb=fmEmb.findById('txt_Embarque').getValue();
            });
	fmEmb.render('divAco2');
}   
)//on REady

/*
 *
 **/
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
function fModifNode(field, new_value, old_value) {
    olNode=this.treeEd.editNode;
    var olParam = fAnalizaArbol(olNode, olNode.ownerTree.tipo, olNode.getDepth())
    olParam.id = "OpTrTr_panelop_aplic"
    olParam.pSeman = fmQry.findById("txt_Seman").getValue();
    olParam.pNvoPr = new_value;
    Ext.Ajax.request({
        url: 'OpTrTr_aplicaprec.php',
        success: function(pResp, pOpt){
            curNode = this;
            olNode=this.parentNode.parentNode ? this.parentNode.parentNode: (this.parentNode ? this.parentNode:this);
            /*olTreeLoader.load(olNode, function(){curNode.parentNode.expand});*/ // @fah: evitar redibujar pantalla
        },
        failure: function(pResp, pOpt){
        },
        headers: {
           'my-header': 'foo'
        },
        params: olParam,
        scope:  olNode
    });
}

function fAnalizaArbol(pNode, pTipo, pNivel){
   var olParams = {};
    switch (pTipo) {
        case "E":
            switch (pNivel) {
               case 1:
                    olParams.pRope = pNode.attributes.txt_rope
                    //olParams.pPran = pNode.attributes.txt_precio
                    break;
                case 2:
                    olParams.pRope = pNode.parentNode.attributes.txt_rope
					olParams.pPrdc = pNode.attributes.item	
                    break;
                case 3:
                    olParams.pRope = pNode.parentNode.parentNode.attributes.txt_rope
					olParams.pPrdc = pNode.parentNode.attributes.item
					olParams.pProd = pNode.attributes.id	
                    break;
                case 4:
                    olParams.pRope = pNode.parentNode.parentNode.parentNode.attributes.txt_rope
					olParams.pPrdc = pNode.parentNode.parentNode.attributes.item
					olParams.pProd = pNode.parentNode.attributes.id
					olParams.pEmpq = pNode.attributes.id						
                    break;                
                case 5:
                    olParams.pRope = pNode.parentNode.parentNode.parentNode.parentNode.attributes.txt_rope
					olParams.pPrdc = pNode.parentNode.parentNode.parentNode.attributes.item
					olParams.pProd = pNode.parentNode.parentNode.attributes.id
					olParams.pEmpq = pNode.parentNode.attributes.id						
                    olParams.pTarj = pNode.attributes.id
                    olParams.pPran = pNode.attributes.txt_precio
                    break;                
                default:
                    break;            }
            break;
        case "A":
            switch (pNivel) {
                case 1:
                    olParams.pPrdc = pNode.attributes.id;
                    //olParams.pPran = pNode.attributes.txt_precio
                    break;
                case 2:
                    olParams.pPrdc = pNode.parentNode.attributes.id;
                    olParams.pProd = pNode.attributes.id;
                    //olParams.pPran = pNode.attributes.txt_precio
                    break;
                case 3:
                    olParams.pPrdc = pNode.parentNode.parentNode.attributes.id;
                    olParams.pProd = pNode.parentNode.attributes.id;
                    olParams.pPran = pNode.attributes.txt_precio
                    olParams.pRope = pNode.attributes.txt_rope
                    //olParams.pPran = pNode.attributes.txt_precio
                    break;
                case 4:
                    olParams.pPrdc = pNode.parentNode.parentNode.parentNode.attributes.id;
                    olParams.pProd = pNode.parentNode.parentNode.attributes.id;
                    olParams.pRope = pNode.parentNode.attributes.txt_rope;
                    olParams.pEmpq = pNode.attributes.id
                    //olParams.pPran = pNode.attributes.txt_precio
                    break;                
                case 5:
                    olParams.pPrdc = pNode.parentNode.parentNode.parentNode.parentNode.attributes.id;
                    olParams.pProd = pNode.parentNode.parentNode.parentNode.attributes.id;
                    olParams.pRope = pNode.parentNode.parentNode.attributes.txt_rope;
                    olParams.pEmpq = pNode.parentNode.attributes.id
                    olParams.pTarj = pNode.attributes.id
                    olParams.pPran = pNode.attributes.txt_precio
                    break;                
                default:
                    break;
            }
            break;
        case "C":
            switch (pNivel) {
                case 1:
                    olParams.pPrdc = pNode.attributes.id;
                case 2:
                    olParams.pPrdc = pNode.parentNode.attributes.id;
                    olParams.pCont = pNode.attributes.txt_conte;
                    break;
                case 3:
                    olParams.pPrdc = pNode.parentNode.parentNode.attributes.id;
                    olParams.pCont = pNode.parentNode.attributes.txt_conte;
                    olParams.pMarc = pNode.attributes.id;
                    break;
                case 4:
                    olParams.pPrdc = pNode.parentNode.parentNode.parentNode.attributes.id;
                    olParams.pCont = pNode.parentNode.parentNode.attributes.txt_conte;
                    olParams.pMarc = pNode.parentNode.attributes.id;
                    olParams.pEmpq = pNode.attributes.id;
                    break;                
                case 5:
                    olParams.pPrdc = pNode.parentNode.parentNode.parentNode.parentNode.attributes.id;
                    olParams.pCont =pNode.parentNode.parentNode.parentNode.attributes.txt_conte;
                    olParams.pMarc =pNode.parentNode.parentNode.attributes.id;
                    olParams.pEmpq =pNode.parentNode.attributes.id;
                    olParams.pTarj =pNode.attributes.txt_precio
                    break;                
                default:
            }            
    }
    return olParams;    
}
function addTab(pPar){
      tabs_c.add({
      id: pPar.id,
      title: pPar.title,
      layout:'fit',
	  closable: true,
      autoLoad:{url:pPar.url + "&pObj=" + pPar.id+ "&" + fParamQry(), scripts: true, method: 'post'}
    }).show();
  }