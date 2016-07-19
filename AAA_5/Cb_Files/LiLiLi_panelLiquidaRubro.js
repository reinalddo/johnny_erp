/*
 *  Logica asociada al Panel de cada modulo
 *  @author     Gina Franco
 *  @date       05/Jun/09
 *  *****************************************************************************************************************
 *  *****************************************************************************************************************
 *  *****************************************************************************************************************
 */
Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){
    fValidaIngreso(1,0);
    Ext.BLANK_IMAGE_URL = "../../AAA_5/LibJs/ext/resources/images/default/s.gif"
    Ext.QuickTips.init();
    
    Ext.namespace("app", "app.LiLiLi");
    
    olDet=Ext.get('divDet');
    var slWidth="width:250px; text-align:'left'";
    
   
        
    var olSemana = {	xtype:'numberfield'
			,fieldLabel:'Semana'
                        ,labelWidth:10
                        ,id:'txt_semana'
			,name:'txt_semana'
			,width:160
			,emptyText:''
			,allowBlank:     false
                        ,listeners: {'change': function (text,newValue, oldValue){
					//debugger;
					if ("" != newValue){
					    fValidaIngreso(2,newValue);
					}
				    }}
		    };

   
Ext.getDom('north').innerHTML = '<div id="north_left"></div><div id="north_right"></div>';

//calendario fecha de corte
    var dr = new Ext.FormPanel({
    labelWidth: 40
    ,frame: false
    ,title: '' 
    ,id:'frmBuscar'
    ,bodyStyle:'padding:5px 5px 0'
    ,border: false
    ,width: 250
	//,defaults: {width: 160}
	,items: [olSemana]
      });
    var slWidth="width:80%; text-align:'center'";
    dr.add({xtype:	'button',
	id:     'btnCxc',
	cls:	 'boton-menu',
	tooltip: 'Consulta Rubros por productor',
	text:    'Consultar',
	style:   slWidth ,
	handler: function(){
		    //debugger;
                    if (0 == app.LiLiLi.ingPermitido){
                        Ext.Msg.alert('AVISO', "Usted no tiene privilegios para ver efectuar esta operacion");
			return;
                    }
		    if (!Ext.getCmp("txt_semana").value){
			Ext.Msg.alert('AVISO', "Ingrese semana");
			return;
		    }
                    
		    var slUrl = "LiLiLi_liquidarubro.php?init=1&pSemana=" + Ext.getCmp("txt_semana").getValue();
		    /*app.cart.paramDetalle ={pAuxil: Ext.getCmp("txt_bancos").getValue()
					    ,pCuenta: Ext.getCmp("txt_cuenta").getValue()};*/
                    addTab({id:'gridLiquida', title:'Liquidacion', url:slUrl});
                    //var w = Ext.getCmp('pnlIzq');
					//w.collapsed ? w.expand() : w.collapse();
	    }
    });
        
    dr.render(document.body, 'divIzq01');
    
    
//////////////// [ Controles de Panel Reportes ] ////////////////////
    /*new Ext.Button({
        text: 'Saldos Diarios'
	,minWidth:100
        ,handler: function(){
	    var slUrl = "../Co_Files/CoTrTr_saldiario.rpt.php";
            window.open(slUrl, "ppre", 'width=1024, height=670, resizable=1, menubar=1');
	}
    }).render(document.body, 'divIzq03');
		
    new Ext.Button({
        text: 'Conciliacion'
	,minWidth:100
        ,handler: function(){
	    Ext.Msg.alert("Alerta","Pendiente");
	}
    }).render(document.body, 'divIzq03');
    
    new Ext.Button({
	text: 'Libro Bancos'
	,minWidth:100
        ,handler: function(){
	    Ext.Msg.alert("Alerta","Pendiente");
	}
    }).render(document.body, 'divIzq03');
*/
}   
)//on REady

/*
*	Agregador de componentes a un panel
*/


function addComponente(pPar){
      //debugger;
      var olPanel = Ext.getCmp("pnlIzq");
      olPanel.add({
      id: pPar.id,
      //title: pPar.title,
      //layout:'fit',
      //closable: true,
      //collapsible: true,
      autoLoad:{url:pPar.url,
            params:{pObj: "frmBuscar"},//"pnlIzq"},
            scripts: true,
            method: 'post'}
    }).show();    
  }

/*
*	Agregador de componentes al tab-panel
*/


function addTab(pPar){
      //debugger;
    tabs_c.add({
      id: pPar.id,
      title: pPar.title,
      layout:'fit',
      closable: true,
      collapsible: true,
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
			grid:app.LiLiLi.grid1,  // grid object 
			pathPrinter:'../LibJs/ext/ux/printer',  	 // relative to where the Printer folder resides  
			//logoURL: 'ext_logo.jpg', // relative to the html files where it goes the base printing  
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

function fValidaIngreso($opcion, $semana){
        
    var olDat = Ext.Ajax.request({
	url: 'LiLiLi_liquidarubrovalidaing'
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
                var olRsp = eval("(" + pResp.responseText + ")")
                //debugger;	        
		if (1 == $opcion){
                    if (olRsp.message == ""){
                        app.LiLiLi.ingPermitido = 1;
                    }else{
                        app.LiLiLi.ingPermitido = 0;
                    }
		}else{
                    if (olRsp.info.estado == "cerrado"){
                        app.LiLiLi.modificarPermitido = 0;
                    }else{
                        app.LiLiLi.modificarPermitido = 1;
                    }
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
	,params: {pOpc: $opcion, pSemana:$semana}//, pAux:ilAuxi, pBan:true, pTip: Ext.getCmp("slFormaPago").getValue()}
    })
}


