
//Ext.onReady(function(){
    //debugger;
    Ext.QuickTips.init();
    
    var olEmbarque = {
                        xtype:'combo',
                        //fields: ["cod", "txt", "inicio","fin"],
                        //labelWidth:50,
                        fieldLabel: 'Embarque'/*,
                        //name: 'embarque',
                        id: 'embarque',
                        hiddenName: 'embarque',
                        //sqlId:"tarEmbarque",
                        //minChars:2,
                        expanded:true,
                        anchor:'95%'*/
                    };
    var olNumTarja = {
                        xtype:'textfield',
                        fieldLabel: 'Num Tarja'/*,
                        name: 'numtarja',
                        id: 'numtarja',
                        //anchor:'60%'
                        tabindex:1*/
                    };
    var olAnio = {
                        xtype:'numberfield',
                        fieldLabel: 'Anio'/*,
                        name: 'anio',
                        id: 'anio',
                        anchor:'75%'*/
                    };
    var olSemana = {
                    xtype:'numberfield',
                    fieldLabel: 'Semana'/*,
                    name: 'semana',
                    id: 'semana',
                    anchor:'95%'*/
                };
    var olHoraInicio = {
                    xtype:'timefield',
                    fieldLabel: 'Hora Inicio'/*,
                    labelWidth:50,
                    name: 'horainicio',
                    id: 'horainicio',
                    anchor:'100%'*/
                };
    var olHoraCierre = {
                    xtype:'timefield',
                    fieldLabel: 'Hora Cierre'/*,
                    labelWidth:50,
                    name: 'horacierre',
                    id: 'horacierre',
                    anchor:'100%'*/
                };
    var olFecha = {
                    xtype:'datefield',
                    fieldLabel: 'Fecha'/*,
                    name: 'fecha',
                    id: 'fecha',
                    anchor:'75%'*/
                };
    var olZona = {
                    xtype:'combo',
                    fieldLabel: 'Zona'/*,
                    name: 'zona',
                    id: 'zona',
                    //vtype:'email',
                    anchor:'95%'*/
                };
    var olPtoEmbarque = {
                        xtype:'combo',
                        fieldLabel: 'Pto.Embarque'/*,
                        name: 'puerto',
                        id: 'puerto',
                        //vtype:'email',
                        anchor:'95%'*/
                    };
    var olBodega = {
                        xtype:'textfield',
                        fieldLabel: 'Bodega / Piso'/*,
                        labelWidth:50,
                        name: 'bodega',
                        id: 'bodega',
                        anchor:'95%'*/
                    };
    var olPiso = {
                        xtype:'textfield',
                        fieldLabel: ' / '/*,
                        labelWidth:10,
                        name: 'piso',
                        id: 'piso',
                        anchor:'95%'*/
                    };
    var olContenedor = {
                        xtype:'textfield',
                        fieldLabel: 'Contenedor'/*,
                        name: 'contenedor',
                        id: 'contenedor',
                        anchor:'75%'*/
                    };
    var olSello =  {
                        xtype:'textfield',
                        fieldLabel: 'Sello'/*,
                        name: 'sello',
                        id: 'sello',
                        anchor:'75%'*/
                    };
    var olProductor = {
                        xtype:'combo',
                        fieldLabel: 'Productor'/*,
                        name: 'productor',
                        id: 'productor',
                        anchor:'95%'
                        ,tabindex:2*/
                    };
    var olHacienda = {
                        xtype:'combo',
                        fieldLabel: 'Hacienda'/*,
                        name: 'hacienda',
                        id: 'hacienda',
                        anchor:'95%'
                        ,tabindex:3*/
                    };
    var olTransporte = {
                        xtype:'combo',
                        fieldLabel: 'Transporte'/*,
                        name: 'transporte',
                        id: 'transporte',
                        //vtype:'email',
                        anchor:'95%'
                        ,tabindex:4*/
                    };
    var olTransportista = {
                        xtype:'combo',
                        fieldLabel: 'Transportista'/*,
                        name: 'transportista',
                        id: 'transportista',
                        //vtype:'email',
                        anchor:'95%'
                        ,tabindex:5*/
                    };
    var olCalidad = {
                        xtype:'textfield',
                        fieldLabel: '%Calidad'/*,
                        name: 'calidad',
                        id: 'calidad',
                        anchor:'75%'
                        ,tabindex:6*/
                    };
    var olObservacion = {
                        xtype:'textfield',
                        fieldLabel: 'Observacion'/*,
                        name: 'pbservacion',
                        id: 'observacion',
                        anchor:'95%'
                        ,tabindex:7*/
                    };
    var olPreEval = {
                        xtype:'checkbox',
                        fieldLabel: 'Pre-Evaluacion'/*,
                        name: 'pre_evaluacion',
                        id: 'pre_evaluacion',
                        anchor:'75%'
                        ,tabindex:8*/
                    };
    var olCodEval = {
                        xtype:'textfield',
                        fieldLabel: 'Cod Evaluador'/*,
                        name: 'codevaluador',
                        id: 'codevaluador',
                        anchor:'75%'
                        ,tabindex:9*/
                    };
    var olGrupoLiq = {
                        xtype:'textfield',
                        fieldLabel: 'Grupo Liq.'/*,
                        name: 'grupoliq',
                        id: 'grupoliq',
                        anchor:'75%'*/
                    };
    var olNumLiq = {
                        xtype:'textfield',
                        fieldLabel: 'Num Liq.'/*,
                        name: 'numliq',
                        id: 'numliq',
                        anchor:'75%'*/
                    };
    var olCartonera = {
                        xtype:'combo',
                        fieldLabel: 'Cartonera'/*,
                        name: 'cartonera',
                        id: 'cartonera',
                        //vtype:'email',
                        anchor:'95%'*/
                    } ;
    var olEstado = {
                        xtype:'combo',
                        fieldLabel: 'Estado'/*,
                        name: 'estado',
                        id: 'estado',
                        anchor:'95%'
                        ,tabindex:10*/
                    };
    var win
    if(!win){
    //if(!Ext.getCmp("win")) {
        //debugger;
        var fmFormTarja = new Ext.form.FormPanel({
            //layout:"form",
            id:"fmFormTarja",
            frame:true,
            title: 'Mantenimiento de Tarjas'//,
            //width:420
            //,height:240
            //,border:false
            ,layout:'fit'
            //,xtype:'form'
            ,items: [{
			// form as the only item in window
			 xtype:'form'	
			,labelWidth:60
			,frame:true
			,items:[{
				// column layout with 2 columns
				 layout:'column'

				// defaults for columns
				,defaults:{
					 columnWidth:0.5
					,layout:'form'
					,border:false
					,xtype:'panel'
					,bodyStyle:'padding:0 18px 0 0'
				}
				,items:[{
					// left column
					// defaults for fields
					 defaults:{anchor:'100%'}
					,items:[olEmbarque, olAnio, olFecha, olZona]
				},{
					// right column
					// defaults for fields
					 defaults:{anchor:'100%'}
					,items:[olNumTarja, olSemana
                                                ,{
                                                    layout:'column'
                                                    ,defaults:{
                                                            columnWidth:0.5
                                                           ,layout:'form'
                                                           ,border:false
                                                           ,xtype:'panel'
                                                           ,bodyStyle:'padding:0 18px 0 0'
                                                   }
                                                   ,items:[
                                                        {defaults:{anchor:'100%'}
                                                        ,items:[olHoraInicio]},
                                                        {defaults:{anchor:'100%'}
                                                        ,items:[olHoraCierre]}
                                                   ]
                                                },olPtoEmbarque, olNumLiq, olSello
                                        ]
				}]
			},olObservacion]
		}]
                ,buttons:[{
                     text:'Guardar'
                     ,formBind:true
                     ,scope:this
                     ,handler:this.submit
                        },{
                    text:'Cancelar'        
                    }
                ]
        });
    
        //fmFormTarja.show();
        //debugger;
        fmFormTarja.render("formT");
        //Ext.getCmp("pnlCen").doLayout();
        //fmFormTarja.doLayout();
        
        //fmFormTarja.load();
        
        /*var olPnlDer = Ext.getCmp("tbTarja");
        olPnlDer.add(fmFormTarja);
        olPnlDer.dolayout();*/
        win = new Ext.Window({
         //title:Ext.get('page-title').dom.innerHTML
	//	,renderTo:Ext.getBody()
	//	,iconCls:'icon-bulb'
                title: 'Mantenimiento de Tarjas',
		width:720
		,height:440
		,border:false
		,layout:'fit'
                //,labelWidth:150
		,items: [{
			// form as the only item in window
			 xtype:'form'	
			,labelWidth:80
			,frame:true
			,items:[{
				// column layout with 2 columns
				 layout:'column'

				// defaults for columns
				,defaults:{
					 columnWidth:0.5
					,layout:'form'
					,border:false
					,xtype:'panel'
					,bodyStyle:'padding:0 18px 0 0'
				}
				,items:[{
					// left column
					// defaults for fields
					 defaults:{anchor:'100%'}
					,items:[olEmbarque, olAnio, olFecha, olZona
                                                ,{
                                                    layout:'column'
                                                    ,defaults:{
                                                            columnWidth:0.5
                                                           ,layout:'form'
                                                           ,border:false
                                                           ,xtype:'panel'
                                                           ,bodyStyle:'padding:0 18px 0 0'
                                                   }
                                                   ,items:[
                                                        {defaults:{anchor:'100%'}
                                                        ,items:[olBodega]},
                                                        {defaults:{anchor:'100%'}
                                                        ,items:[olPiso]}
                                                   ]
                                                },olContenedor
                                        ]
				},{
					// right column
					// defaults for fields
					 defaults:{anchor:'100%'}
					,items:[olNumTarja, olSemana
                                                ,{
                                                    layout:'column'
                                                    ,defaults:{
                                                            columnWidth:0.5
                                                           ,layout:'form'
                                                           ,border:false
                                                           ,xtype:'panel'
                                                           ,bodyStyle:'padding:0 18px 0 0'
                                                   }
                                                   ,items:[
                                                        {defaults:{anchor:'100%'}
                                                        ,items:[olHoraInicio]},
                                                        {defaults:{anchor:'100%'}
                                                        ,items:[olHoraCierre]}
                                                   ]
                                                },olPtoEmbarque, olNumLiq, olSello
                                        ]
				}]
			},olObservacion]
		}]
                ,buttons:[{
                     text:'Guardar'
                     ,formBind:true
                     ,scope:this
                     ,handler:this.submit
                        },{
                    text:'Cancelar',
                    handler  : function(){
                        win.hide();
                    }
                    }
                ]
	});
	win.show();
    }

 
//});
