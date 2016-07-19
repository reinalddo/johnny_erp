Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"
Ext.namespace("app", "app.rli");
Ext.onReady(function(){
    Ext.QuickTips.init();
    
    
    var slWidth="width:99%; text-align:'left'";


    //agregar al panel

    var ing = new Ext.FormPanel({
    labelWidth: 100,
    frame: false,
    title: '',
    bodyStyle:'padding:5px 5px 0',
    border: true,
    width: 300
    });
    
    ing.add({xtype:	'button',
	id:     'btnLiCond',
	cls:	 'boton-menu',
	tooltip: 'Contabilizar Liquidacion de Fruta',
	text:    'Contabilizar LQF',
        style:   slWidth ,
	handler: function(){
	    addTab1({id:"TabRol",title: 'Rol Liquidacion',url:'',items:[panelLiCond]});
            Ext.getCmp("TabRol").doLayout();
	    fCargaPermisos('RLI');//Rol de Liquidacion
	    fCargaPermisos('COI');//Consumo de Inventario
	    fNuevo();
	    }
    });  

    ing.render(document.body, 'divIzq01');
    
    
    
    
}); //Final de funcion onReady
    
    var LiCond= ({
            xtype: 'form'
            ,id : 'LiCond'
            ,labelAlign: 'left'
	    ,labelWidth: 120
	    ,baseCls: "x-plain"
            ,ctCls: 'x-box-layout-ct normal'
            ,defaults: {labelStyle: 'font-weight:bold; font-size:8px; font-family:Arial, text-transform: uppercase',
                        fieldStyle: 'font-size:8px; text-transform: uppercase'}
            ,items:[{
                    layout:'column'
                    ,border:false
                    ,items:[{
                            labelWidth: 120
                            ,layout: 'form'
                            ,border:false
                            ,items: [{
                                        fieldLabel: 'Semana Inicio'
                                        ,id:'pIni'
                                        ,xtype: 'numberfield'
                                        ,width:100
					,minLength:4
					,maxLength:4
                                        ,allowNegative: false
                                        ,allowDecimals:false
                                        ,decimalPrecision:0
                                        ,allowBlank:false
                                    },{
                                        fieldLabel: 'Semana Fin'
                                        ,id:'pFin'
                                        ,xtype: 'numberfield'
                                        ,width:100
					,minLength:4
					,maxLength:4
                                        ,allowNegative: false
                                        ,allowDecimals:false
                                        ,decimalPrecision:0
                                        ,allowBlank:false
                                    }]
                            }]
                    }]
                });
    
    var panelCondicion = new  Ext.Panel({
        widht: 100,
        height:200,
        title:''
	,id:'panelCondicion'
        ,items: [LiCond]
	,bbar: [
                    {
                        text: 'Rol de Liquidacion'
                        ,id: "btnRol"
                        //,iconCls:'iconNuevo'
                        ,handler : fProcesarRol
                        ,tooltip: "Procesa el Rol de Liquidacion"
                    },{
                        text: 'Consumo de Inventario'
                        ,id: "btnConsumo"
                        ,handler : fProcesarConsumo
                        ,tooltip: "Procesa Consumo de Inventario"
                    }
                ]
    });
    
    
    var panelLiCond = new  Ext.Panel({
        widht: 100,
        height:200,
        title:''
	,id:'panelLiCond'
        ,items: [panelCondicion]
        
    });
   
    function addTab1(pPar){ //Add Tab sin la configuracion del autoload
      tabs_c.add({
      id: pPar.id
      ,title: pPar.title
      ,layout:'fit'
      ,closable: false
      ,collapsible: true
      ,items: pPar.items
    }).show();
  }
    
    
function fCargaPermisos($key){
    var olDat = Ext.Ajax.request({
	url: '../../AAA_5/Bi_Files/BiTrTr_variablesglobales.php?&pmodulo=LiLiPr'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                switch ($key){
                    case 'RLI':app.rli.btRolLiq = olRsp.valor;//Generar Rol de Liquidacion
                        if (olRsp.valor != 1){
			    Ext.getCmp('btnRol').disable();
			}
			break;
		    case 'COI':app.rli.btRolLiq = olRsp.valor;//Generar Consumo de Inventario
                        if (olRsp.valor != 1){
			    Ext.getCmp('btnConsumo').disable();
			}
			break;
                }
	    }
	}
	,params: {pKey: $key, pBool: 0}
    })
}



    
    function fNuevo(){
	Ext.getCmp("pIni").setValue(" ");
	Ext.getCmp("pFin").setValue(" ");
    }


    function fValidar(){
	if (Ext.getCmp("pIni").isValid() == false){
	    Ext.MessageBox.alert("Ingrese una Semana de Inicio correcta");
	    return false;
	}
	if (Ext.getCmp("pFin").isValid() == false){
	    Ext.MessageBox.alert("Ingrese una Semana de Fin correcta");
	    return false;
	}
	
	if ((Ext.getCmp("pIni").getValue()> 0) && (Ext.getCmp("pFin").getValue()> 0)){
	    return true;
	}
	return false;
    }


    function fProcesarRol(){
	if (fValidar() == true){
	    window.open("LiLiPr_contabrol.pro.php?pIni="+Ext.getCmp("pIni").getValue()+"&pFin="+Ext.getCmp("pFin").getValue(),"Procesar Rol de Liquidacion");
	}
	
    }
    
    function fProcesarConsumo(){
	if (fValidar() == true){
	    window.open("LiLiPr_contabconsumo.pro.php?pIni="+Ext.getCmp("pIni").getValue()+"&pFin="+Ext.getCmp("pFin").getValue(),"Procesar Consumo de Inventario");
	}
	
    }
