
Ext.onReady(function(){
	var aux;
    rdComboGeneral = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt'] ) ;
    
    var store = new Ext.data.JsonStore({
        root: 'rows'
        ,totalProperty: 'totalCount'
        ,idProperty: 'id'
        ,remoteSort: true
        ,fields:['id']

	    ,proxy: new Ext.data.HttpProxy({
            method:"POST"
            ,url: (!sgLoadUrl)? 'CoTrTr_gridocenlazados.php?' : sgLoadUrl
            })
        ,baseParams:{
            init:0
            ,pPagina:0
            //,pCuent: app.cart.paramDetalle.pCuenta
            //,pAuxil:  app.cart.paramDetalle.pAuxil
            ,sort: "com_NumComp"
            ,order:"ASC"
            }
        ,sortInfo: {field: "com_NumComp", order:"ASC"}  // @bug: esto no funciona, fue necesario incluirlo en baseparams
        ,remoteSort: true
    });
    store.setDefaultSort('com_NumComp', 'ASC');    

    name="Car";
    grid2 = new Ext.ux.AutoGridPanel({
    //grid2 = new Ext.grid.GridPanel({ 
    	title:''
        ,id:'detconsultar'
        ,height: 300
        ,width: 800
//        ,cnfSelMode: 'csm'
        ,loadMask: true
        ,stripeRows :true
        ,autoSave: true
        ,saveUrl: 'saveconfig.php'
        ,store : store
        ,monitorResize:true
        ,animCollapse:false
        ,collapsible:true
        ,tbar: [
		{
		    text: 'Liberar'
		    ,tooltip: 'Selecciones los OC a liberar'
		    ,id: 'Lib'
		    ,listeners: {
					click: fLiberar
		    }
		}
        ]
        ,bbar: new Ext.PagingToolbar({
            pageSize: 250,
            store: store,
            displayInfo: true,
            displayMsg: '{0} a {1} de {2}',
            //displayMsg: ' ',
            emptyMsg: "No hay datos que presentar"})
        ,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
            }
        }
    });
    Ext.getCmp(gsObj).add(grid2); 
    Ext.getCmp(gsObj).doLayout();
    grid2.store.load({params: {meta: true, start:0, limit:250}});
    
    grid2.on('rowclick', function(sm, rowIdx, e) {
                var r = grid2.getStore().getAt(rowIdx);
                var sm2 = grid2.getSelectionModel().getSelected();
                if ('0' == r.data.seleccionar ){
                    sm2.set('seleccionar','1');
                 }
                else{
                    sm2.set('seleccionar','0');//rec.id);
                    //sm2.set('det_FecLibros',Date.parseDate("2050-12-31", 'Y-m-d'));
                }
	});
    
})
function fLiberar(){
    //debugger;
    var tot = grid2.getStore().getCount();
    var ind = 0; var band = 0;
    var rec; var field; var value;
    var olParam;
    var cntfalta=0;
    var ind1=0;
    var resta=0;
    var tarjas=new Array();
    while (ind < tot){
        rec = grid2.getStore().getAt(ind);
            if ('1' == rec.data.seleccionar ){
			band=1;
			tarjas[ind1]=rec.data.RegNum;
			ind1++;
            }
            ind++;
      }
    if (0 == band){
        Ext.Msg.alert('AVISO', "Seleccione por lo menos 1 OC para liberar");
    }
    else{
	aux=tarjas.toString(",");
	fProcesoDesliqui(aux)
    }
    //debugger;
}

function fProcesoDesliqui(aux)
{
    Ext.Ajax.request({
	    waitMsg: 'Grabando...',
	    url:	'CoTrTr_liberaoc.php',
	    method: 'POST',
	    params: {pOc:aux},
	    success: function(response,options){
            var responseData = Ext.util.JSON.decode(response.responseText);
		if (false == responseData.success)
		{
				    Ext.Msg.alert('ALERTA', 'No se liberaron los OC:'+aux);
		} 
		else
		{
		    Ext.Msg.alert('EXITO', 'OC:'+aux+' LIBERADAS');
		    grid2.store.reload();
		}	
	    }, 
	    failure: function(form, e) {
	      if (e.failureType == 'server') {
	        slMens = 'La operacion no se ejecuto. ' + e.result.errors.id + ': ' + e.result.errors.msg;
	      } else {
	        slMens = 'El comando no se pudo ejecutar en el servidor';
	      }
	      Ext.Msg.alert('Proceso Fallido', slMens);
	    }
	  }//end request config
); //end request  
}