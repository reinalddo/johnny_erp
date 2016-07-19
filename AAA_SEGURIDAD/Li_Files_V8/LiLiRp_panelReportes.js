Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"

Ext.onReady(function(){
    Ext.QuickTips.init();
    parametros = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 100
                        ,border:false
                        ,columnWidth:0.30
                        ,items: [{
                                    fieldLabel:'Semana'
                                    ,id:'pro_Semana'
                                    ,xtype: 'textfield'
                                    ,width:50
                                    ,allowBlank:false
                                    ,maxLength:4
                                    ,minLength:4
                                    
                                },{
                                    fieldLabel:'Exportar a Excel'
                                    ,id:'pExcel'
                                    ,xtype: 'checkbox'
                                    ,allowBlank:true
                                }]
                        }
                       ,{layout: 'form'
                        ,labelWidth: 120
                        ,border:false
                        ,columnWidth:0.70
                        ,items: [{
                                    fieldLabel:'Productor'
                                    ,id:'com_codReceptor'
                                    ,xtype: 'genCmbBox'
                                    ,minChars: 1
                                    ,allowBlank:false
                                    ,sqlId: 'LiLiRp_Productores'
                                    ,width:200
                                }]
                        }]
                }]
    });
    
    botones = ({
         xtype:'form'
        ,items:[{ layout:'column'
                ,border:false
                ,items:[{layout: 'form'
                        ,labelWidth: 100
                        ,border:false
                        ,items: [{
                                    text:'Cuadro de Liquidacion Detallado'
                                    ,id:'bt_cuadroLiq'
                                    ,xtype: 'button'
                                    ,handler:function(){
                                        pPar = fParametros();
                                        url = "LiLiRp_CuadroLiquidacion.rpt.php?"+pPar;
                                        window.open(url)
                                        
                                    }
                                }]
                        }]
                }]
    });
    
    var pnl_RepLiq = new  Ext.Panel({
        //anchor: 100
        //widht:100
	title:'Parametros para el reporte'
	,id:'pnl_RepLiq'
	,autoScroll : true
        ,items: [parametros,botones]
    });
 
 
   addTab1({id:"TabRepLiq",title: 'Reportes de Liquidacion',url:'',items:[pnl_RepLiq]});
   Ext.getCmp("TabRepLiq").doLayout();
 
});
function fParametros(){
    pPar = "";
    if (Ext.getCmp("com_codReceptor").isValid()) {pPar += "&com_codReceptor="+Ext.getCmp("com_codReceptor").getValue()}
    if (Ext.getCmp("pro_Semana").getValue() != "") {pPar += "&pro_Semana="+Ext.getCmp("pro_Semana").getValue()}
    if (Ext.getCmp("pExcel").getValue() == true) {pPar += "&pExcel=1"}
    return pPar;
}


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