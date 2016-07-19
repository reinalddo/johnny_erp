/*
 *  Estructura del paneles de un mudulo, Habilita un ViewPort (paneles) que presenta las siguientes secciones:
 *    - pnlSup:   Panel Superior (vacio)
 *    - pnlInf:   Panel Inferior (vacio)
 *    - pnlIzq:   Panel Izquierda: acordeon con 3 paneles: izq01, izq02, izq03
 *    - pnlCen:   Panel Central :   Panel Central, compuesto por un Tab-Panel
 *    - pnlDer:   Panel Derecha:   Panel 
 **/
Ext.onReady(function(){
  //Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

  Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"
/*  var acciones = new Ext.Panel({ // Sustituidos por definiciones en items
    id:'izq01',
    title:'Acciones',
    collapsible:true,
    html:'<div id="divIzq01"/>',
    frame: false,
    border:false,
    autoheight:true,
    autoscroll: true,
    autowidth:true,
    iconCls:'nav'
  });
  var consultas = new Ext.Panel({
    id:'izq02',
    title:'Consultas',
    collapsible:true,
    html:'<div id="divIzq02"/>',
    frame: false,
    border:false,
    autoheight:true,
    autoscroll: true,
    autowidth:true,
    iconCls:'nav'
  });

  var reportes= new Ext.Panel({
    id:'izq03',
    title:'Reportes',
    html:'<div id="divIzq03"/>',
    collapsible:true,
    frame: false,
    border:false,
    autoheight:true,
    autoscroll: true,
    autowidth:true,
    iconCls:'settings'
  }); */
  var viewport = new Ext.Viewport({
      id:"paneles",
      layout:'border',
      items:[
          new Ext.BoxComponent({ region:'north',
            id: 'pnlSup',
            hidden: false,
            el: 'north',
            height:70,
            margins:'0 0 0 0'
          }),/**/
          {
           region:'south',
           id:'pnlInf',
           title:'South',
           hidden: false,
           split:true,
           height: 100,
           minSize: 100,
           maxSize: 200,
           collapsible: true,
           collapsed: true,
           margins:'0 0 0 0'
          },
          {
           region:'west',
           id:'pnlIzq',
           title:'Funciones',
           hidden: false,
           frame: true,
           split:true,
           width: 240,
           minSize: 200,
           maxSize: 450,
           autoheight:true,
           collapsible: true,
           collapsed: false,
           animCollapse: true,
           margins:'0 0 0 5'/*,
           layout:'accordion'*/,
           layout:'accordion',
              layoutConfig:{
                  animate:false
              },
              items: [{id:'izq01',
                title:'Acciones',
                collapsible:true,
                animCollapse:false,
                animate:false,
                html:'<div id="divIzq01"/>',
                frame: false,
                border:false,
                autoheight:true,
                autoscroll: true,
                autowidth:true,
                iconCls:'nav'}
              ,{id:'izq02',
                title:'Consultas',
                collapsible:true,
                animCollapse:false,
                animate:false,
                html:'<div id="divIzq02"/>',
                frame: false,
                border:false,
                autoheight:true,
                autoscroll: true,
                autowidth:true,
                iconCls:'nav'}
              ,{ id:'izq03',
                title:'Reportes',
                html:'<div id="divIzq03"/>',
                collapsible:true,
                animate:false,
                frame: false,
                border:false,
                autoheight:true,
                autoscroll: true,
                autowidth:true,
                iconCls:'settings'}
              ]
          }
          //items: [acciones, consultas, reportes]
          ,{region:'east',
           id:'pnlDer',
           html:'<div id="divpnlDer"><div/>',
           hidden: false,
           title:'Detalle',
           split:true,
           width: 500,
           minSize: 175,
           maxSize: 800,
           collapsible: true,
           collapsed: true,
           animCollapse: false,
           margins:'0 0 0 0'
           /*bodyStyle: {background:"#F0F0F0 none repeat scroll 0 0"}*/
          },
           tabs_c = new Ext.TabPanel({
               id:'pnlCen',
               region: 'center',
               //activeTab: 0,
               resizeTabs:true,
               minTabWidth: 115,
               tabWidth:135,
               enableTabScroll:true,
               //plugins: new Ext.ux.TabCloseMenu(),
               deferredRender:true,
               animCollapse: false,
               plain:false,
               defaults:{autoScroll: true}
               /*bodyStyle: {background:"#F0F0F0 none repeat scroll 0 0"}*/
               //items:[]
           })
             ]
  }); // Ext.Viewport

  /*function my_doLayout() {
    tabs_c.doLayout();
  }*/
});   // Ext.onReady