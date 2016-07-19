/*
 * Ext JS Library 2.2.1
 * Copyright(c) 2006-2009, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

var windowIndex = 0;
/*
a[0].name = "modulo1";
a[0].links = "['S','none','Window1',900,450,900,445,'auto','../work/OpTrTr_pru.php']"

a[1].name = "modulo1";
a[1].links = "['S','none','Window1',900,450,900,445,'auto','../work/OpTrTr_pru.php']"

a[2].name = "modulo1";
a[2].links = "['S','none','Window1',900,450,900,445,'auto','../work/OpTrTr_pru.php']"
*/

var jasonmodulos = "{'data':['modulo1','modulo2','modulo3']}";
var jasonlinks = "{'modulo1':['S','none','Caja',900,450,900,445,'auto','../work/OpTrTr_pru.php'],'modulo2':   ['M','Tesorería','Caja',900,450,900,445,'auto','../work/OpTrTr_pru.php'],'modulo3':['M','Contabilidad','Contabilidad',1100,580,1095,575,'auto','../In_Files/InTrTr.php']}";

var jsonobj1 = eval("(" + jasonmodulos + ")");
var jsonobj2 = eval("(" + jasonlinks + ")");

var arraymodulos = jsonobj1['data'];


// Sample desktop configuration
MyDesktop = new Ext.app.App({
	init :function(){
		Ext.QuickTips.init();
	},

	getModules : function(){
		
		return fCrearMenus();
	},

    // config for the start menu
    getStartConfig : function(){
        return {
            title: 'User',
            iconCls: 'user',
            toolItems: [{
                text:'Settings',
                iconCls:'settings',
                scope:this
            },'-',{
                text:'Logout',
                iconCls:'logout',
                scope:this
            }]
        };
    }
});

//Funciones





// Menús
function fCrearMenus(){

clsMod = Ext.extend(Ext.app.Module, {
					init : function(){
						this.launcher = {
							text: "",
							iconCls:'bogus',
							handler : this.crearVentana,
							scope: this,
							widthwin:100,
							heightwin:500,
							widthifr:1000,
							heightifr:500,
							scroll:"auto",
							layout: 'fit',
							url:"",
							windowId: windowIndex
						}
							},
							
					crearVentana: function(src){
							var desktop = this.app.getDesktop();
							var win = desktop.getWindow('bogus'+src.windowId);
							if(!win){
								win = desktop.createWindow({
									id: 'bogus'+src.windowId,
									title:src.text,
									width:src.widthwin,
									height:src.heightwin,
									html : '<iframe frameborder="0" height="'+src.heightifr+'" align="center" width="'+src.widthifr+'" scrolling="'+src.scroll+'" src="'+src.url+'" ></iframe>',
									iconCls: 'bogus',
									shim:false,
									animCollapse:false,
									constrainHeader:true
								});
							}
							win.show();
					}
			});
			
clsMod2 = Ext.extend(Ext.app.Module, {
					init : function(){
						this.launcher = {
							text: "",
							iconCls:'bogus',
							handler : this.crearVentana,
							scope: this,
							widthwin:100,
							heightwin:500,
							widthifr:1000,
							heightifr:500,
							scroll:"auto",
							layout: 'fit',
							url:"",
							windowId: windowIndex
						}
							},
							
					crearVentana: function(src){
							var desktop = this.app.getDesktop();
							var win = desktop.getWindow('bogus'+src.windowId);
							if(!win){
								win = desktop.createWindow({
									id: 'bogus'+src.windowId,
									title:src.text,
									width:src.widthwin,
									height:src.heightwin,
									html : '<iframe frameborder="0" height="'+src.heightifr+'" align="center" width="'+src.widthifr+'" scrolling="'+src.scroll+'" src="'+src.url+'" ></iframe>',
									iconCls: 'bogus',
									shim:false,
									animCollapse:false,
									constrainHeader:true
								});
							}
							win.show();
					}
			});
			
var aoMd= new Array();
	for (j = 0; j < arraymodulos.length; j++)
	{
			var nodo=arraymodulos[j];
			
			var links = jsonobj2[nodo];
			 
			if (links[0] =="S"){
				aoMd[j]= new clsMod;
				aoMd[j].launcher.text= links[2];
				aoMd[j].launcher.iconCls='bogus';
				
				aoMd[j].launcher.widthwin=links[3];
				aoMd[j].launcher.heightwin=links[4];
				aoMd[j].launcher.widthifr=links[5];
				aoMd[j].launcher.heightifr=links[6];
				aoMd[j].launcher.scroll=links[7];
				aoMd[j].launcher.layout= 'fit';
				aoMd[j].launcher.url=links[8];
				aoMd[j].launcher.windowId= windowIndex;
				}
			else if (links[0] =="M"){
				
			
			
			}
							
	}

	return aoMd;
}


	MyDesktop.BogusMenuModule = Ext.extend(Ext.app.Module, {
			init : function(){
				this.launcher = {
					text: 'Tesorería',
					iconCls: 'bogus',
					handler: function() {
						return false;
					},
					menu: {
						items:[{
							text: 'Caja',
							iconCls:'bogus',
							handler : crearVentana,
							scope: this,
							widthwin:900,
							heightwin:450,
							widthifr:900,
							heightifr:445,
							scroll:"auto",
							layout: 'fit',
							url:"../work/OpTrTr_pru.php",
							windowId: windowIndex
							}]
					}
				}
			}
			
		});
						


MyDesktop.BogusMenuModule2 = Ext.extend(Ext.app.Module, {
    init : function(){
        this.launcher = {
            text: 'Contabilidad',
            iconCls: 'bogus',
            handler: function() {
				return false;
			},
            menu: {
                items:[{
                    text: 'Contabilidad',
                    iconCls:'bogus',
                    handler : crearVentana,
                    scope: this,
                    widthwin:1100,
                    heightwin:580,
                    widthifr:1095,
                    heightifr:575,
                    scroll:"auto",
                    url:"../In_Files/InTrTr.php",
                    windowId: windowIndex
                    }]
            }
        }
    }
	
});


