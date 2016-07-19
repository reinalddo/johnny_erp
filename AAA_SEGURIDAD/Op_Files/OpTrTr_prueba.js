/*
 * 
 **/
Ext.onReady(function(){
  //Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

  Ext.BLANK_IMAGE_URL = "/AAA/AAA_SEGURIDAD_2_2/LibJava/ext-2.0/resources/images/default/s.gif"
  var semana = new Ext.Panel({
    frame:true,
    title: 'Semana',
    collapsible:true,
    /*contentEl:'task-actions',*/
    titleCollapse: true,
    html:'<div id="divAco01"><div id="divDet"/><div id="divQry"/></div>',
    border:true,
    activeOnTop: true,
    autoheight:true,
    autowidth:true,
    iconCls:'nav'
    });
  var consultas = new Ext.Panel({
    id:'aco02',
    title:'Consultas',
    collapsible:true,
    html:'<div id="divAco2"/>',
    frame: true,
    border:true,
    autoheight:true,
    autoscroll: true,
    autowidth:true,
    iconCls:'nav'
  });
  var reportes= new Ext.Panel({
    id:'aco03',
    title:'Reportes',
    html:'<div id="divAco3"/>',
    frame: true,
    border:true,
    autoheight:true,
    autoscroll: true,
    autowidth:true,
    iconCls:'settings'
  });
  var viewport = new Ext.Viewport({
      id:"panels",
      layout:'border',
      items:[
          new Ext.BoxComponent({ region:'north',
            id: 'north-panel',
            hidden: false,
            el: 'north',
            height:32,
            margins:'0 0 0 0'
          }),/**/
          {
           region:'south',
           id:'south-panel',
           title:'South',
           hidden: true,
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
           id:'west-panel',
           title:'Funciones',
           hidden: false,
           frame: true,
           split:true,
           width: 300,
           minSize: 250,
           maxSize: 450,
           autoheight:true,
           collapsible: true,
           collapsed: false,
           margins:'0 0 0 5'/*,
           layout:'accordion'*/,
          items: [semana,consultas,reportes]
          },
          {
           region:'east',
           id:'east-panel',
           hidden: false,
           title:'East',
           split:true,
           width: 200,
           minSize: 175,
           maxSize: 400,
           collapsible: true,
           collapsed: true,
           margins:'0 0 0 0'
          },
           tabs_c = new Ext.TabPanel({
               id:'tabs-panel',
               region: 'center',
               activeTab: 0,
               resizeTabs:true,
               minTabWidth: 115,
               tabWidth:135,
               enableTabScroll:true,
               //plugins: new Ext.ux.TabCloseMenu(),
               deferredRender:true,
               plain:false,
               defaults:{autoScroll: true},
               items:[ {
                       title: 'Detalles',
                       disabled:false,
                       closable:true,
                       iconCls: 'add',
                       html: "<div id='divTabDet' />" 
                   }
               ]
           })
             ]
  }); // Ext.Viewport


  function my_doLayout() {
    tabs_c.doLayout();
  }
 
});   // Ext.onReady