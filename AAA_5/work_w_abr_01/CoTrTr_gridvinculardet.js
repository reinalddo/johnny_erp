/*
*  Configuracion de Autogrid para detalles de cartera: Carga el resultado del detalle de una cuienta por cobrar/Pagar
*  especifica. Inicializa el grid, y luego llama a script php que devuelve los datos asociados, enviando como parametro
*  la cuenta y auxiliar requeridos.
*
* @param	pCuent	Codigo de Cuenta a generar
* @param	pAuxil	Codigo de Auxiliar
*
*
**/
//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
Ext.onReady(function(){

      gridSummary = new Ext.ux.grid.GridSummary();


    var store = new Ext.data.JsonStore({
        root: 'rows'
        ,totalProperty: 'totalCount'
        ,idProperty: 'id'
        ,remoteSort: true
        ,fields:['id']

	    ,proxy: new Ext.data.HttpProxy({
            method:"POST"
            ,url: (!sgLoadUrl)? 'CoTrTr_gridvinculardet.php?' : sgLoadUrl
            })
        ,baseParams:{
            init:0
            ,pPagina:0
            //,pCuent: app.cart.paramDetalle.pCuenta
            //,pAuxil:  app.cart.paramDetalle.pAuxil
            ,sort: "com_numcomp"
            ,order:"ASC"
            }
        ,sortInfo: {field: "com_numcomp", order:"ASC"}  // @bug: esto no funciona, fue necesario incluirlo en baseparams
        ,remoteSort: true
    });
    store.setDefaultSort('com_numcomp', 'ASC');         // @bug: esto no funciona, fue necesario incluirlo en baseparams

    /*@TODO:                                                                  sumatoria de  grid */
	// custom summary renderer example
    /*
    function totalDetalle(v, params, data) {
        return v?  v : '';
    }
    var summaryDet = new Ext.ux.grid.GridSummary();

    var filtersDet = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',   dataIndex: 'det_codcuenta'},
        {type: 'numeric',  dataIndex: 'det_idauxiliar'},
        {type: 'string',   dataIndex: 'txt_nombre'},
        {type: 'date',     dataIndex: 'det_fecha'},
	{type: 'numeric',  dataIndex: 'det_numcheque'},
        {type: 'string',   dataIndex: 'txt_nombcuenta'}]
        , autoreload:true
    });*/

    name="Car";
    grid2 = new Ext.ux.AutoGridPanel({
        title:''
        ,id:'detvincular'
        ,height: 300
        ,width: 800
        //,cnfSelMode: 'csm'
        ,loadMask: true
        ,stripeRows :true
        ,autoSave: true
        ,saveUrl: 'saveconfig.php'
        ,store : store
        ,monitorResize:true
        ,animCollapse:false
        ,collapsible:true
        //colModel: new Ext.grid.ColumnModel([{id: 'null'}]),
        ,bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: store,
            displayInfo: true,
            //displayMsg: '{0} a {1} de {2}',
            displayMsg: ' ',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-', {
		    pressed: false
		   //enableToggle:true
		   ,text: 'Grabar'
		   //,disabled:true
		   ,iconCls: 'iconGrabar'
		   ,cls: 'x-btn-text-icon details'
		   ,qtip: 'Genera un documento para liquidar los saldos marcados'
		   //,toggleHandler: toggleDetails
		   ,handler: fGrabar
		}/*,{
		     pressed: false
		    //enableToggle:true
		    ,text: 'Desmarcar'
		    ,cls: 'x-btn-text-icon details'
		    ,qtip: 'Desmarca TODOS los movimientos marcados'
		    //,toggleHandler: toggleDetails
		    ,handler: fDesmarcar
                }*/
        ]})
	,plugins: [gridSummary]
        //,plugins: [filtersDet, summaryDet],
        ,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
            }
        }
    });

    var olPanel = Ext.getCmp(gsObj);
    olPanel.add(grid2);

    this.grid2.store.load({params: {meta: true, start:0, limit:25, sort:'com_feccontab', dir:'ASC'}});
    Ext.getCmp("pnlDer").doLayout();

    grid2.on('rowclick', function(sm, rowIdx, e) {
                //debugger;
                //var olRec=grid2.getSelectedRecord().node;
                var r = grid2.getStore().getAt(rowIdx);
                /*var ilTipo = r.data.com_TipoComp;
                var ilNum = r.data.com_NumComp;*/
                var sm2 = grid2.getSelectionModel().getSelected();
                if ('0' == r.data.seleccionar ){
                    sm2.set('seleccionar','1');//rec.id);
                    //sm2.set('det_FecLibros',Date.parseDate(r.data.fecCorte, 'Y-m-d'));
                }
                else{
                    sm2.set('seleccionar','0');//rec.id);
                    //sm2.set('det_FecLibros',Date.parseDate("2050-12-31", 'Y-m-d'));
                }
                //fModifEstadoFechaConcil(r,ilTipo+ilNum);
                //Ext.Msg.alert('AVISO', ilTipo+ilNum);
	});

    /*grid2.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
        var flSum = Ext.getCmp('flSumaSelec');
	if (r.data.txt_pago == 0) r.set("txt_pago",r.data.txt_valor);
        flSum.setValue(flSum.getValue() + r.data.txt_pago);
	//@TODO ; Cargar comprobante
	i=0;
	return true;
	});
    grid2.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
        var flSum = Ext.getCmp('flSumaSelec');
	r.set("txt_pago",0);
        flSum.setValue(flSum.getValue() - r.data.txt_pago)
	//@TODO ; Cargar comprobante
	i=0;
	return true;
	}
    );*/
})
/*
 *	Desmarca todos los selecionados
 *
 ***/
function fDesmarcar(){
    //debugger;
    var tot = grid2.getStore().getCount();
    var ind = 0;
    var rec; var field; var value;
    while (ind < tot){
        rec = grid2.getStore().getAt(ind);
        if ('1' == rec.data.seleccionar ){
            //debugger;
            //var record = grid.getStore().getAt(row);
            field = grid2.getColumnModel().getDataIndex(3);
            value = rec.get(field);
            rec.set(field, '0');

            //Ext.Msg.alert('AVISO', rec.data.txt_comprobante);
        }
        ind++;
    }
}
/*
 *	Grabar en la tabla conenlace todos los movimientos seleccionados
 *
 ***/
function fGrabar(){
    //debugger;
    var tot = grid2.getStore().getCount();
    var ind = 0; var band = 0;
    var rec; var field; var value;
    var olParam;
    var cntfalta=0;
    var ind1=0;
    var resta=0;
    while (ind < tot){
        rec = grid2.getStore().getAt(ind);
            if ('1' == rec.data.seleccionar ){
                 if(ind1==0)
                    var cntfalta=rec.data.CantidadFalta;
                  else
                     var cntfalta=cntfalta-resta;
                var cnt=rec.data.Cantidad;
                if(cntfalta >= cnt)
                {
                  resta=cnt;
                  band = 1;
                  ind1= 1;
                 // debugger;
                  olParam= {
                      id : "CoTrCl_ingVinculacion",
                      enlRegNum: rec.data.RegNum,
                      enlSecuencia: rec.data.Secuencia,
                      enlRegNum1: rec.data.RegNum1,
                      enlSecuencia1: rec.data.det_Secuencia
                  }
                  Ext.Ajax.request({
                      url: 'CoTrTr_vinculardoc_grabar.php',
                      success: function(pResp, pOpt){
                          //debugger;
                          //Ext.Msg.alert('AVISO', "Comprobante Actualizado "+comp);

                          //olRec.tmp_Valor = olRec.tmp_Cantidad * olRec.tmp_PrecUnit
                      },
                      failure: function(pResp, pOpt){
                          Ext.Msg.alert('AVISO', "Error al ingresar");
                      },
                      headers: {
                         'my-header': 'foo'
                      },
                      params: olParam,
                      scope:  this
                  });

                  Ext.Msg.alert('AVISO', "Grabacion Exitosa");
              }
              else
                  {
                        band = 1;
                        Ext.Msg.alert('AVISO', "Le faltan "+cntfalta+" cantidades e intento enlazarla con "+ cnt);
                        break;
                  }
            }
            ind++;
      }
    if (0 == band){
        Ext.Msg.alert('AVISO', "Seleccione por lo menos 1 comprobante a asociar");
    }
}

function ValidarCtnUni(pNumpIB,pNumOC)
{
var olDat = Ext.Ajax.request({
						url: 'CoRtTr_CntUniValida'
						,callback: function(pOpt, pStat, pResp){
						    if (true == pStat){
							    olRsp = eval("(" + pResp.responseText + ")");
							if (olRsp.info.msg == "-"){
							    Ext.Msg.alert('AVISO', '');
							    Ext.getCmp("pa_o").clearInvalid();
							    return true;
							}else{
							    Ext.Msg.alert('AVISO', 'No se puede enlazar\n'+olRsp.info.msg);
							    Ext.getCmp("pa_o").markInvalid();
							    Ext.getCmp("pa_o").disable();
							    return false;
							}
						    }
							    return false;
						}
						,params: {pNIB: pNumpIB
							,pNIC: pNumOC
							,pOpc: 1
							}
					    });
}
/*
 *
 *
 *
 **/
function fGetAjaxData(pUrl, pPar){
    var olResp={};
    Ext.Ajax.request({
	url: pUrl
	,callback: function(pOpt, pStat, pResp){
	    if (true == pStat){
		olResp = eval("(" + pResp.responseText + ")")
		olResp.status=true
	    }
	}
	,failure: function(pObj, pRes){
	    olResp.status=false
	    }
	,params: pPar
    })
    return olResp;
}
