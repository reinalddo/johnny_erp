/*
*  Configuracion de Autogrid para ubicacion de cheques
*  
* @param	pAuxil	Codigo de Auxiliar 
*
*
**/

Ext.namespace("app", "app.cheque");

//Ext.BLANK_IMAGE_URL = "/AAA/AAA_5/LibJs/ext/resources/images/default/s.gif"
//debugger;
Ext.onReady(function(){
    //debugger;
    
    // Create grid view
    //se utiliza para cambiar color a lineas de comprobantes anulados
    var gridView = new Ext.grid.GridView({ 
       //forceFit: true, 
        getRowClass : function (row, index) {
                            //debugger;
                            var cls = ''; 
                            var data = row.data; 
                            switch (data.confirmado ) { 
                               case 'n' : 
                                  cls = 'noconfirm-row'// highlight row yellow 
                                  break; 
                               /*case '3m Co' : 
                                  cls = 'green-row' 
                                  break; 
                               case 'Altria Group Inc' : 
                                  cls = 'red-row' 
                                  break;  */
                            }//end switch 
                            return cls; 
                         } 
    });  //end gridView 
    
    var storeX= Ext.extend(Ext.data.GroupingStore,{  // Requerido porque se pierde el sortInfo cuando se aplica el sort
        sorInfoX : {field:'fecha', direction: 'DESC'},
        applySort: function(){
            Ext.data.GroupingStore.superclass.applySort.call(this);
            if (!this.sortInfo) this.sortInfo = this.sortInfoX;
            if(!this.groupOnSort && !this.remoteGroup){
            var gs = this.getGroupState();
            if(gs && gs != this.sortInfo.field){
            this.sortData(this.groupField);
            }
            }
        }
    });
    var store = new storeX({
	    proxy: new Ext.data.HttpProxy({
            method:"GET",
            url: (!sgLoadUrl)? 'CoTrTr_chequesubicacion.php' : sgLoadUrl  
        }),
        reader: new Ext.data.JsonReader(
            {root: 'rows', id: 'id'/*, start:0, limit:9999*/},
            ['id']
        ),
        //groupField: 'ID',        
        sortInfo: {field: 'fecha', direction: 'DESC'},
        sortInfoX: {field: 'fecha', direction: 'DESC'}, 
        groupOnSort:false ,
        remoteSort: true
    });
    
    /*var store = new Ext.data.JsonStore({
        root: 'rows'
        ,totalProperty: 'totalCount'
        ,idProperty: 'id'
        ,remoteSort: true
        ,fields:['id']

	    ,proxy: new Ext.data.HttpProxy({
            method:"POST"
            ,url: (!sgLoadUrl)? 'CoTrCl_conciliaciongridDet.php?' : sgLoadUrl
            })
        ,baseParams:{
            init:0
            ,pPagina:0
            //,pCuent: app.cart.paramDetalle.pCuenta
            ,pAuxil:  app.cart.paramDetalle.pAuxil
            ,fecCorte:  app.cart.paramDetalle.fecCorte
            ,sort: "com_FecContab"
            ,order:"DESC"
            ,dir:"DESC"
            }
        ,sortInfo: {field: "com_FecContab", order:"DESC"}  // @bug: esto no funciona, fue necesario incluirlo en baseparams
        ,remoteSort: true
    });*/
    //store.setDefaultSort('det_numdocum', 'ASC');         // @bug: esto no funciona, fue necesario incluirlo en baseparams

    /*@TODO:                                                                  sumatoria de  grid */
	// custom summary renderer example
    /*   
    function totalDetalle(v, params, data) {
        return v?  v : '';
    }
    var summaryDet = new Ext.ux.grid.GridSummary();    
 
    */
    var filters = new this.Ext.ux.grid.GridFilters({filters:[
        {type: 'string',  dataIndex: 'banco'},
        {type: 'numeric',  dataIndex: 'cheque'},
        {type: 'date',  dataIndex: 'fecha'},
        {type: 'numeric',  dataIndex: 'valor'},
        {type: 'string',  dataIndex: 'beneficiario'},
        {type: 'string',  dataIndex: 'concepto'},
        {type: 'string',  dataIndex: 'tipoComp'},
        {type: 'numeric',  dataIndex: 'numComp'}]
        , autoreload:true
    });
    
    name="Car";
    app.cheque.gridDet = new Ext.ux.AutoGridPanel({
        title:''
        ,plugins: [filters]
        //,id:'gridConciliacion'
        ,height: 200
        //,width: 450
        ,style:"width:100%; font-size:8px"
        ,cnfSelMode: 'csmm'
        ,loadMask: true
        ,stripeRows :true
        ,autoSave: true
        ,saveUrl: 'saveconfig.php'  
        ,store : store
        ,monitorResize:true
        ,animCollapse:false
        ,collapsible:true
        ,view: gridView
        //colModel: new Ext.grid.ColumnModel([{id: 'null'}]),
        ,tbar:[{
                    text: 'Enviar'
                    ,tooltip: 'Envia cheque a un usuario determinado.'
                    ,id: 'btnEnviar'
                    ,listeners: {
                        click: fEnviar
                    }
                    ,iconCls:'iconEnviar'
                }
               ]
        ,bbar: new Ext.PagingToolbar({
            pageSize: 25,
            store: store,
            displayInfo: true,
            plugins: [filters],
            //displayMsg: '{0} a {1} de {2}',
            displayMsg: ' ',
            emptyMsg: "No hay datos que presentar",
            items:[
                '-',{
                        pressed: false,
                        enableToggle:true,
                        text: 'Imprimir',
                        cls: 'x-btn-text-icon details' ,
                        iconCls: 'iconImprimir',
                        handler: function(){basic_printGrid(app.cheque.gridDet);}
                    }                
        ]})
        //,plugins: [filtersDet, summaryDet],
        ,listeners: {
            destroy: function(c) {
                c.getStore().destroy();
                //if(Ext.getCmp("frmPago")) Ext.getCmp("frmPago").destroy();
            }
        }
    });
    //debugger;
    //if(!Ext.getCmp("gridConciliacion")){
        var olPanel = Ext.getCmp(gsObj);
        olPanel.add(app.cheque.gridDet);
    //}
    
    
    app.cheque.gridDet.store.load({params: {meta: true, start:0, limit:9999, sort:'com_FecContab', dir:'DESC'}});
    Ext.getCmp(gsObj).doLayout();
    
    /*app.cheque.gridDet.on('rowclick', function(sm, rowIdx, e) {
                //debugger;
                //var olRec=app.cheque.gridDet.getSelectedRecord().node;
                if (true == Ext.getCmp("chkModificar").checked){
                    var r = app.cheque.gridDet.getStore().getAt(rowIdx);
                    var ilTipo = r.data.com_TipoComp;
                    var ilNum = r.data.com_NumComp;
                    var sm2 = app.cheque.gridDet.getSelectionModel().getSelected();
                    if ('0' == r.data.det_EstLibros ){
                        sm2.set('det_EstLibros','1');//rec.id);
                        sm2.set('det_FecLibros',Date.parseDate(r.data.fecCorte, 'Y-m-d'));
                    }
                    else{
                        sm2.set('det_EstLibros','0');//rec.id);
                        sm2.set('det_FecLibros',Date.parseDate("2050-12-31", 'Y-m-d'));
                    }
                    fModifEstadoFechaConcil(r,ilTipo+ilNum);
                }
                //Ext.Msg.alert('AVISO', ilTipo+ilNum);
	});*/
    
})

function fValidar(){
    var alSel = app.cheque.gridDet.getSelectionModel().selections;
    var resp = true;
    for (var r=0 ; r <  alSel.items.length; r++) {
        confirmado = alSel.items[r].data.confirmado;
        if ("n" == confirmado){
            resp = false;
            Ext.Msg.alert("Alerta","Cheque # "+alSel.items[r].data.cheque+" - Banco: "+alSel.items[r].data.banco+" ya fue enviado a otra persona, pero no ha sido confirmada la recepcion del mismo.");
        }
    }
    return resp;
}

/* Muestra una pantalla, donde se selecciona el usuario de destino, y se
*  puede ingresar una observacion
*/
function fEnviar(){
    if (0 == app.cheque.gridDet.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Por favor seleccione comprobante a anular');
        return;
    }
    if (!fValidar()) return;
    //debugger;
    var rdComboBaseGeneral = new Ext.data.XmlReader(
				{record: 'record', id:'cod'},['cod', 'txt']
		    ) ;
    dsCmbUsuario = 	new Ext.data.Store({
            proxy: 		new Ext.data.HttpProxy({
                    url: '../Ge_Files/GeGeGe_queryToXml.php',
                    metod: 'POST'//,
		    //extraParams: {pAnio : Ext.get("txt_anio"), pSeman : win.findById("txt_semana").getValue()}//win.getComponent("txt_semana")}
            }),
            reader: 	rdComboBaseGeneral,
            baseParams: {id : 'CoTrTr_usuarios'}
    });
    
    var olUsuario = new Ext.form.ComboBox({
			fieldLabel:'Usuario',
			id:'txtusuario',
			name:'txtusuario',
			//width:150,
			store: dsCmbUsuario,
			displayField:	'txt',
			valueField:     'cod',
			hiddenName:'user',
			selectOnFocus:	true,
			typeAhead: 		true,
			mode: 'remote',
			minChars: 1,
			triggerAction: 	'all',
			forceSelection: true,
			emptyText:'',
			allowBlank:     false,
			listWidth:      250 
		    });
    
    var olObservacion = {
            xtype:'textarea'
            ,fieldLabel:'Observacion'
            ,id:'observacion'
        };
    if (!Ext.getCmp('winUbicar')){
        var frm = new Ext.form.FormPanel({
            id: "frmWinUbicar"
            ,width:350
            ,baseCls: 'x-small-editor'
	    ,border:false
            ,layout:'form'
            ,labelWidth:65
            ,defaults:{anchor:'97%'}
            ,items:[olUsuario, olObservacion]
            ,buttons:[
		{
		    text:'Proceder'//,
		    //type:'submit',
		    ,iconCls: 'iconProceder'
		    ,handler:fProceder
		}/*,{
		    text:'Imprimir',
		    type:'submit'
		    //,handler:fGrabar
		    ,iconCls: 'iconImprimir'
		}*/,
		{
		    text:'Salir'
		    ,iconCls: 'iconSalir'
		    ,handler:function(){
			win.close();
		    }
		}
	    ]
        });
        
        var win = new Ext.Window({
            title:'Definir Usuario'
            ,layout:'fit'
            ,width:350
            ,height:170
	    ,id: "winUbicar"
            ,items: frm
        });
        win.show();
    }
}
    
function fProceder(){
    if ("" == Ext.getCmp("txtusuario").getValue()){
        Ext.Msg.alert("Alerta",'Por favor seleccione usuario');
        return false;
    }
    
    var alSel = app.cheque.gridDet.getSelectionModel().selections;
    var griddata = [];
    var glosa = "";
    olDeta='';// = new Array();
    for (var r=0 ; r <  alSel.items.length; r++) {
        griddata.push(alSel.items[r].data);
        glosa += "» Cheque # "+alSel.items[r].data.cheque+" - Banco: "+alSel.items[r].data.banco;
        glosa += " - Beneficiario: "+alSel.items[r].data.beneficiario;
        glosa += " - Valor: "+alSel.items[r].data.valor+"\n";
        //debugger;
        olDeta += "&cheque["+r+"]="+alSel.items[r].data.cheque;
        olDeta += "&banco["+r+"]="+alSel.items[r].data.banco;
        olDeta += "&regnum["+r+"]="+alSel.items[r].data.regNum;
        olDeta += "&secuencia["+r+"]="+alSel.items[r].data.secuencia;
    }
    
    //debugger;
    //fGrabar(1, olDeta, r);
    //Ext.Msg.alert("Alerta",glosa);
    //debugger;
    app.cheque.codDestino = Ext.getCmp("txtusuario").getValue();
    app.cheque.destino = Ext.getCmp("txtusuario").lastSelectionText;
    app.cheque.observacion = Ext.getCmp("observacion").getValue();
    
    glosa = "Usuario Origen: "+app.cheque.usuario+"\n"+
            "Usuario destino: "+app.cheque.destino
            +"\n\n Detalle de cheques:\n"+glosa;
    if ("" != app.cheque.observacion)
        glosa +="\n\n Observacion: "+app.cheque.observacion;
    
    var olTexto = {
            xtype:'textarea'
            ,fieldLabel:'Por favor verifique que los datos sean correctos'
            ,id:'confirmar'
            ,value:glosa
            ,height:140
        };
        
    if (!Ext.getCmp('winProceder')){
        Ext.getCmp('winUbicar').close();
        var frm = new Ext.form.FormPanel({
            id: "frmWinProceder"
            ,width:350
            ,baseCls: 'x-small-editor'
	    ,border:false
            ,layout:'form'
            ,labelWidth:65
            ,defaults:{anchor:'97%'}
            ,items:[olTexto]
            ,buttons:[
		{
                    id : 'btnConfirmar',
		    text:'Confirmar'//,
		    //type:'submit',
		    ,iconCls: 'iconConfirmar'
		    ,handler:fConfirmar
		},{
                    id : 'btnImprimir',
		    text:'Imprimir',
		    type:'submit'
		    ,handler:fImprimir
		    ,iconCls: 'iconImprimir'
                    ,disabled: true
		},
		{
		    text:'Salir'
		    ,iconCls: 'iconSalir'
		    ,handler:function(){
			win.close();
		    }
		}
	    ]
        });
    
        var win = new Ext.Window({
            title:'Confirmacion'
            ,layout:'fit'
            ,width:350
            ,height:220
	    ,id: "winProceder"
            ,items: frm
        });
        win.show();
    }
    
    
    return true;
};

function fConfirmar(){
        
    var alSel = app.cheque.gridDet.getSelectionModel().selections;
    //var griddata = [];
    //var glosa = "";
    var olDeta='&obs='+app.cheque.observacion+"&origen="+app.cheque.codUser+"&destino="+app.cheque.codDestino;
    for (var r=0 ; r <  alSel.items.length; r++) {
        //griddata.push(alSel.items[r].data);
        //glosa += "Cheque # "+alSel.items[r].data.cheque+" - Banco: "+alSel.items[r].data.banco+"\n";
        //debugger;
        olDeta += "&cheque["+r+"]="+alSel.items[r].data.cheque;
        olDeta += "&banco["+r+"]="+alSel.items[r].data.banco;
        olDeta += "&regnum["+r+"]="+alSel.items[r].data.regNum;
        olDeta += "&secuencia["+r+"]="+alSel.items[r].data.secuencia;
        //olDeta += "&obser["+r+"]="+app.cheque.observacion;
    }
    fGrabar(1,olDeta,r);
};
    
function fGrabar(opc, detalles, total){
    if (0 == app.cheque.gridDet.getSelectionModel().getCount()){
        Ext.Msg.alert('Alerta','Por favor seleccione comprobante a anular');
        return;
    }
    
    //var slRegNum = app.cheque.gridDet.getSelectionModel().getSelected().data.com_RegNumero;
    //var slFecCorte = app.cheque.gridDet.getSelectionModel().getSelected().data.fecCorte;
    
    /*if ("Anulado" == slConcepto){
        Ext.Msg.alert('ALERTA','Comprobante ya esta anulado');
        return;
    }*/
        
    var olParam= {
        id : "CoTrTr_chequeGrabar"
        //,pDetalles : detalles
        ,pOpc  : opc
    }
    Ext.Ajax.request({
        url: 'CoTrTr_chequesgrabar.php?tot='+total+detalles,
        success: function(pResp, pOpt){
            //debugger;
            var olRsp = eval("(" + pResp.responseText + ")")
            if ("" != olRsp.message){
                //debugger;
               
                Ext.getCmp('btnImprimir').enable();
                Ext.getCmp('btnConfirmar').disable();
                app.cheque.gridDet.store.load({params: {meta: true, start:0, limit:9999, sort:'com_FecContab', dir:'DESC'}});
                 Ext.Msg.alert('Alerta',olRsp.message);
                //Ext.getCmp('winProceder').close();
            }else{
                Ext.Msg.alert('Alerta',"Error al grabar");
            }
            //olRec.tmp_Valor = olRec.tmp_Cantidad * olRec.tmp_PrecUnit
        },
        failure: function(pResp, pOpt){
            debugger;
            Ext.Msg.alert('AVISO', "Error al actualizar comprobante "+slTipoComp+slNumComp);
        },
        headers: {
           'my-header': 'foo'
        },
        params: olParam,
        scope:  this
    });
    
    
    
}

function fImprimir(){
    //debugger;
    var detalle = Ext.getCmp("confirmar").getValue().replace(/\n/g,"<br/>");
    detalle = detalle.replace(/Usuario Origen:/g,"<strong>Usuario Origen:</strong>");
    detalle = detalle.replace(/Usuario destino:/g,"<strong>Usuario destino:</strong>");
    detalle = detalle.replace(/Detalle de cheques:/g,"<strong>Detalle de cheques:</strong>");
    detalle = detalle.replace(/Observacion:/g,"<strong>Observacion:</strong>");
    
    var now = new Date();

    var strHtml = "";
    strHtml += '<style type="text/css">html{font-family: "Courier New", Courier, monospace;font-size: 11px;}';
    strHtml += '.firmas{ border-top: 1px solid black; margin-left:70px;}</style>';
    strHtml += new Date().format('d-M-Y')+"<br/><br/>";
    strHtml += detalle;//"Hello world<br />How are you?";
    strHtml += '<br/><br/><br/><br/><br/><span class="firmas">Entregue Conforme</span>';
    strHtml += '<span class="firmas">Recibi Conforme</span>';
    strHtml += '<br/><br/><p style="font-size: 9px;">Fecha/Hora Impresion: '+now.toLocaleString() +'</p>';
    strHtml += '<script type="text/javascript">';
    strHtml += "window.print();</script>";
    
    var atributos = "status=0,width=400,height=450";
    
    var wPopUp = window.open( "", "wPopUp", atributos );
    wPopUp.document.writeln( strHtml );
    wPopUp.document.close();
}