Ext.onReady(function(){

    Ext.QuickTips.init();
    //debugger;
    var olComprobante = {
                    xtype:'combo',
                    fieldLabel: 'comprobante',
                    name: 'comprobante',
                    id: 'comprobante',
                    //vtype:'email',
                    anchor:'95%'
                }
                
    var olNum_comprobante = {
                    xtype:'numberfield',
                    fieldLabel: 'num_comprobante',
                    name: 'num_comprobante',
                    id: 'num_comprobante',
                    anchor:'95%'
                }
                
    var olEmbarque = {
                        xtype:'combo',
                        fields: ["cod", "txt", "inicio","fin"],
                        //labelWidth:50,
                        fieldLabel: 'Embarque',
                        name: 'embarque',
                        id: 'embarque',
                        hiddenName: 'embarque',
                        sqlId:"tarEmbarque",
                        minChars:2,
                        expanded:true,
                        anchor:'95%'
                    }
    var olNumTarja = {
                        xtype:'textfield',
                        fieldLabel: 'Num Tarja',
                        name: 'numtarja',
                        id: 'numtarja',
                        anchor:'60%'
                        ,tabindex:1
                    }
    var olAnio = {
                        xtype:'numberfield',
                        fieldLabel: 'Anio',
                        name: 'anio',
                        id: 'anio',
                        anchor:'75%'
                    }
    var olHoraInicio = {
                    xtype:'textfield',
                    fieldLabel: 'Hora Inicio',
                    labelWidth:50,
                    name: 'horainicio',
                    id: 'horainicio',
                    anchor:'100%'
                }
    var olHoraCierre = {
                    xtype:'textfield',
                    fieldLabel: 'Hora Cierre',
                    labelWidth:50,
                    name: 'horacierre',
                    id: 'horacierre',
                    anchor:'100%'
                }
    var olFecha = {
                    xtype:'datefield',
                    fieldLabel: 'Fecha',
                    name: 'fecha',
                    id: 'fecha',
                    anchor:'75%'
                }
    
    var olPtoEmbarque = {
                        xtype:'combo',
                        fieldLabel: 'Pto.Embarque',
                        name: 'puerto',
                        id: 'puerto',
                        //vtype:'email',
                        anchor:'95%'
                    }
    var olBodega = {
                        xtype:'textfield',
                        fieldLabel: 'Bodega / Piso',
                        labelWidth:50,
                        name: 'bodega',
                        id: 'bodega',
                        anchor:'95%'
                    }
    var olPiso = {
                        xtype:'textfield',
                        fieldLabel: ' / ',
                        labelWidth:10,
                        name: 'piso',
                        id: 'piso',
                        anchor:'95%'
                    }
    var olContenedor = {
                        xtype:'textfield',
                        fieldLabel: 'Contenedor',
                        name: 'contenedor',
                        id: 'contenedor',
                        anchor:'75%'
                    }
    var olSello =  {
                        xtype:'textfield',
                        fieldLabel: 'Sello',
                        name: 'sello',
                        id: 'sello',
                        anchor:'75%'
                    }
    var olProductor = {
                        xtype:'combo',
                        fieldLabel: 'Productor',
                        name: 'productor',
                        id: 'productor',
                        anchor:'95%'
                        ,tabindex:2
                    }
    var olHacienda = {
                        xtype:'combo',
                        fieldLabel: 'Hacienda',
                        name: 'hacienda',
                        id: 'hacienda',
                        anchor:'95%'
                        ,tabindex:3
                    }
    var olTransporte = {
                        xtype:'combo',
                        fieldLabel: 'Transporte',
                        name: 'transporte',
                        id: 'transporte',
                        //vtype:'email',
                        anchor:'95%'
                        ,tabindex:4
                    }
    var olTransportista = {
                        xtype:'combo',
                        fieldLabel: 'Transportista',
                        name: 'transportista',
                        id: 'transportista',
                        //vtype:'email',
                        anchor:'95%'
                        ,tabindex:5
                    }
    var olCalidad = {
                        xtype:'textfield',
                        fieldLabel: '%Calidad',
                        name: 'calidad',
                        id: 'calidad',
                        anchor:'75%'
                        ,tabindex:6
                    }
    var olObservacion = {
                        xtype:'textfield',
                        fieldLabel: 'Observacion',
                        name: 'pbservacion',
                        id: 'observacion',
                        anchor:'95%'
                        ,tabindex:7
                    }
    var olPreEval = {
                        xtype:'checkbox',
                        fieldLabel: 'Pre-Evaluacion',
                        name: 'pre_evaluacion',
                        id: 'pre_evaluacion',
                        anchor:'75%'
                        ,tabindex:8
                    }
    var olCodEval = {
                        xtype:'textfield',
                        fieldLabel: 'Cod Evaluador',
                        name: 'codevaluador',
                        id: 'codevaluador',
                        anchor:'75%'
                        ,tabindex:9
                    }
    var olGrupoLiq = {
                        xtype:'textfield',
                        fieldLabel: 'Grupo Liq.',
                        name: 'grupoliq',
                        id: 'grupoliq',
                        anchor:'75%'
                    }
    var olNumLiq = {
                        xtype:'textfield',
                        fieldLabel: 'Num Liq.',
                        name: 'numliq',
                        id: 'numliq',
                        anchor:'75%'
                    }
    var olCartonera = {
                        xtype:'combo',
                        fieldLabel: 'Cartonera',
                        name: 'cartonera',
                        id: 'cartonera',
                        //vtype:'email',
                        anchor:'95%'
                    }
    var olEstado = {
                        xtype:'combo',
                        fieldLabel: 'Estado',
                        name: 'estado',
                        id: 'estado',
                        anchor:'95%'
                        ,tabindex:10
                    }
    if(!Ext.getCmp("tbTarjas")) {
        fmFormGen = new Ext.FormPanel({
            xtype: "form",
            //labelAlign: 'top',
            //labelWidth:100,
            id:"tbTarjas",
            frame:true,
            title: 'Mantenimiento de Tarjas',
            bodyStyle:'padding:5px 5px 0',
            width: 600,
            collapsible: true,
            split:true,
            items: [{
                layout:'column',
                items:[{
                    columnWidth:.5,
                    layout: 'form',
                    items: [olEmbarque, olAnio, olFecha, olZona,
                            {layout:'column'
                                ,border:false,
                                items:[{
                                    columnWidth:.65,
                                    layout: 'form',
                                    items: [olBodega]
                                },{
                                    columnWidth:.35,
                                    layout: 'form',
                                    labelWidth:15,
                                    items: [olPiso]
                            }]}
                            ,olContenedor]
                },{
                    columnWidth:.5,
                    layout: 'form',
                    items: [olNumTarja, olSemana
                            ,{layout:'column'
                                ,border:false,
                                items:[{
                                    columnWidth:.55,
                                    layout: 'form',
                                    items: [olHoraInicio]
                                },{
                                    columnWidth:.45,
                                    layout: 'form',
                                    labelWidth:65,
                                    items: [olHoraCierre]
                                }]}
                            , olPtoEmbarque, olNumLiq, olSello]
                }]
            },{
                layout:'column'
                ,border:false
                ,baseClass: "x-form"
                ,extraCls: "x-form",
                items:[{
                    columnWidth:.5,
                    layout: 'form',
                    items: [olProductor, olTransporte, olGrupoLiq, olCalidad, olPreEval, olCodEval]
                },{
                    columnWidth:.5,
                    layout: 'form',
                    items: [olHacienda, olTransportista, olCartonera, olObservacion, olEstado]
                }]
            }
            /*,{ //espacio para grid
                xtype:'htmleditor',
                id:'bio',
                fieldLabel:'Biography',
                height:200,
                anchor:'98%'
            }*/],
    
            buttons: [{
                text: 'Guardar'
            },{
                text: 'Cancelar'
            }]
        });
    
        fmFormGen.render("form");
    }

 /*
	Ext.QuickTips.init();
 
	var simpleForm = new Ext.FormPanel ({
		labelWidth: 75, 		// label settings here cascade unless overridden
        url:'save-form.php',	// when this form submitted, data goes here
        frame:true,
        title: 'Mantenimiento de Tarjas',
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {width: 230},
        defaultType: 'textfield',
 
        items: [{*/
				/*
					here same as <input type="text" name="name" /> in HTML
				*/
               /* fieldLabel: 'Tarja Num',
                name: 'numTarja',
                id: 'numTarja',
                allowBlank:false
            },{
                xtype: "numberfield",
                fieldLabel: 'Semana',
                name: 'semana',
                id: 'semana'
            },{
                xtype: "datefield",
                fieldLabel: 'Fecha',
                name: 'fecha',
                id: 'fecha'
	    },{
                xtype: "combo",
                fieldLabel: 'Embarque',
                name: 'embarque',
                id: 'embarque'
	    },{
                xtype: "combo",
                fieldLabel: 'Pto. Embarque',
                name: 'puerto',
                id: 'puerto'
		}],
        
        buttons: [{
            text: 'Save',			
			handler: function () {
				// when this button clicked, sumbit this form
				simpleForm.getForm().submit({
					waitMsg: 'Saving...',		// Wait Message
					success: function () {		// When saving data success
						Ext.MessageBox.alert ('Message','Data has been saved');
						// clear the form
						simpleForm.getForm().reset();
					},
					failure: function () {		// when saving data failed
						Ext.MessageBox.alert ('Message','Saving data failed');
					}
				});
			}
        },{
            text: 'Cancel',
			handler: function () {
				// when this button clicked, reset this form
				simpleForm.getForm().reset();
			}
        }]
 
	});
        var olValor={xtype: "numberfield"
                ,id:  "flValor"
                ,tabindex: 30
                //,value: Ext.getCmp("flSumaSelec").getValue()
                ,fieldLabel: "VALOR"
                ,width: 100 };
 
	// finally render the form
	simpleForm.render ('form'); // render form to simple-form element (see simple-form.html)
 */
});
