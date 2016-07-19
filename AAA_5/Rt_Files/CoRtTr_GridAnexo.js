/*
 * Grid Editable con informacion de cuentas de gastos y auxiliares
 */
Ext.ns('con');
Ext.ns('gen');
Ext.BLANK_IMAGE_URL = "../LibJs/ext/resources/images/default/s.gif"
xg=Ext.grid;
/*gen.xmlReader = function(config, flds) { // Generic XML Reader for comboboxes
    var config = config || {};
    var cnf = {record: 'record'
	    ,id: config.id ||'cod'}
    var flds= flds || ['cod','txt']
    Ext.applyIf(config, {
	 record: 'record'
	,id: config.id
    });
    gen.xmlReader.superclass.constructor.call(this, config, flds); // esta bien incluido los campos????
};
Ext.extend(gen.xmlReader, Ext.data.XmlReader);
Ext.reg('genXmlReader', gen.xmlReader); 
*/
/*-------------------------------------------*/
edCmbCue = {xtype: 'genCmbBox'
           ,sqlId: 'CoCuCu_cuentas'
           ,id: 'det_CodCuenta'
	   ,hiddenName:'det_CodCuenta'
	   ,minChars: 3
	   }
	   
edCmbAux = {xtype: 'genCmbBox'
           ,sqlId: 'CoCuCu_auxiliares'
           ,id: 'det_IDAuxiliar'
	   ,hiddenName:'det_IDAuxiliar'
	   }
 
olCtaGasto = {xtype: 'genCmbBox'
           ,sqlId: 'CoRtTr_CtaGasto'
           ,id: 'txt_CtaGasto'
	   ,fieldLabel:'Cuenta Gasto'
	   ,hiddenName:'txt_Cta'
	   ,minChars: 3
	   //,tabIndex:'910'
    }
/*---------------------------------------------------------*/


Ext.ns("Ext.ux.renderer");
Ext.ux.renderer.ComboRenderer = function(options) {
    var value = options.value;
    var combo = options.combo;
    var returnValue = value;
    var valueField = combo.valueField;
    var idx = combo.store.findBy(function(record) {
	if(record.get(valueField) == value) {
	    returnValue = record.get(combo.displayField);
	    record.set(combo.displayField);
	    return true;
	}
    });
    //if(idx < 0 && Ext.isEmpty(value) /*value == 0*/) { returnValue = '';}
    if(idx < 0  ) { returnValue = value;}
    else {
	if (!Ext.isEmpty(combo.hiddenName))
            if (options.record.data[combo.hiddenName] != "undefined")  //@Bug:corregido
                options.record.set(combo.hiddenName, combo.value);
	options.record.data[options.meta.id] =  combo.lastSelectionText || ""
    }
    return returnValue;
};



function comboBoxRenderer (pVal, pMeta, pRec, pRow, pCol, pStr) {
    var olGrid=Ext.getCmp("gridDetalle");
    var olEd=olGrid.getColumnModel().getCellEditor( pCol,  pRow);
    if (pRec.dirty){
        pRec.set(olEd.field.hiddenName, pVal)
        return olEd.field.store.getAt(olEd.field.selectedIndex).data.txt
    }
    else return pVal
}

Ext.onReady(function(){
//crearGrid = function(){
    Ext.QuickTips.init();
    var xg = Ext.grid;
    
    // define a custom summary function
    Ext.grid.GroupSummary.Calculations['totalCost'] = function(v, record, field){
        return v + (record.data.tmp_Cantidad * record.data.tmp_PrecUnit);
    }    
    summary = new Ext.grid.GroupSummary();
    gridSummary = new Ext.ux.grid.GridSummary();
    
    store = new Ext.data.JsonStore({
        id:"stDetalle",
        url: 'CoRtTr_GridAnexo.php?init=-1',
        baseParams: {com_RegNumero:getFromurl("com_RegNumero"), meta: true, start:0, limit:100, sort:'det_Secuencia', dir:'ASC'},
        root:"rows",
        fields: fCampos(),
        sortInfo: {field:'det_Secuencia', direction: 'ASC'},
        pruneModifiedRecords: true
    });

    goBbar= new Ext.PagingToolbar({
        pageSize: 100
        ,store: store
        ,displayInfo: true
        ,displayMsg: 'Registros {0} - {1} de {2}'
        ,emptyMsg: "No hay datos que presentar"
        ,items: ["-",{width:400}, "-",{
            text: 'Agregar Registro'
            ,id: "btnAdd"
            ,iconCls:'new-item'
            ,tooltip : "Agrega 5 lineas de detalle"
            ,handler : fAgregar
            }
            , new Ext.Button({
                text: 'Grabar'
                ,id: "btnUpd"
                ,iconCls:'save'                    
                ,handler: fGrabar
                ,tooltip : "Graba las modificaiones realizadas"
                }),
            new Ext.Button({
                text: 'Eliminar'
                ,id: "btnDel"
                ,iconCls:'delete'                    
                ,handler: fEliminar
                //,disabled:true
                ,tooltip : "Elimina los registros marcados. Para marcar un registro, haga click en la columna numerdad de la izquierda. Puede aplicar las teclas 'Shift' y 'Ctrl'"
                })
            ]
    })
    

     olRec = Ext.data.Record.create([
        {name: '_newFlag',          type:'int'}
	,{name: 'det_CodEmpresa',    type:'string',     defaultValue:0}
	,{name: 'det_RegNumero',    type:'int',     defaultValue:getFromurl("com_RegNumero", "")}
	,{name: 'det_TipoComp',     type:'int',     defaultValue:getFromurl("com_TipoComp", 'XX')}
	,{name: 'det_NumComp',      type:'int',     defaultValue:getFromurl("com_NumComp", -1)}
	,{name: 'det_Secuencia',    type:'int',     defaultValue:0}
	,{name: 'det_ClasRegistro', type:'int',     defaultValue:0}
	,{name: 'det_ValDebito',    type:'float',   defaultValue:0}
	,{name: 'det_ValCredito',   type:'float',   defaultValue:0}
	,{name: 'det_Glosa',        type:'string',  defaultValue:0}
	,{name: 'det_IDAuxiliar',   type:'int',     defaultValue:0}
	//,{name: 'aux_Nombre',       type:'string',  defaultValue:''}
	,{name: 'det_CodCuenta',    type:'int',     defaultValue:0}
	//,{name: 'txt_Cuenta',       type:'string',  defaultValue:''}	
 ]
    );
        
    grid = new Ext.ux.AutoEditorGridPanel({ //new xg.EditorGridPanel({
        ds: store
        ,id: 'gridDetalle'
        ,plugins: [gridSummary]     
        ,bbar: goBbar
        ,tbar: new Ext.Toolbar()
        ,frame:true
        ,autowidth:true
        ,height: 280
        ,clicksToEdit: 1
        ,loadMask: true
        ,collapsible: true
        ,animCollapse: false
        ,trackMouseOver: true
        ,iconCls: 'icon-grid'
        ,stripeRows:true
        ,sm: new Ext.grid.RowWithCellSelectionModel()
        ,renderTo: document.body                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
        ,keys: [{
          key: [Ext.EventObject.UP, Ext.EventObject.DOWN],
          fn: function(){
            grid.getView().focusEl.focus(); }
        }]
  });
    
    grid.store.on("update", function(pRs, pRec, pAct){
        if (pRec.isModified("det_ValDebito") && pRec.get("det_ValDebito") != 0)
            pRec.set("det_ValCredito", 0);
        if (pRec.isModified("det_ValCredito") && pRec.get("det_ValCredito") != 0)
            pRec.set("det_ValDebito", 0);
        gridSummary.refreshSummary();
        var flDb = Ext.DomQuery.selectNumber("div.x-grid3-col-det_ValDebito", grid.view.summary.dom)
        var flCr = Ext.DomQuery.selectNumber("div.x-grid3-col-det_ValCredito", grid.view.summary.dom)
        if(flDb == flCr)    Ext.getCmp("btnUpd").enable()
        else Ext.getCmp("btnUpd").disable();
    })
    grid.store.on("load", function(){
        Ext.getCmp("btnAdd").enable();
        Ext.getCmp("btnUpd").disable();
        Ext.getCmp("btnDel").disable();
    })
     grid.getSelectionModel().on ("rowselect", function(){
        Ext.getCmp("btnDel").enable();
    })
    alPrimKeys= [];
    alPrimKeys["det_RegNumero"] = 1;
    alPrimKeys["det_Secuencia"] = 1;
    alPrimKeys["det_TipoComp"] = 1;
    alPrimKeys["det_NumComp"] = 1;
    alPrimKeys["det_CodEmpresa"] = 1;
    grid.store.load();
}
)
// hasta aquí onReady

function fMove(pDir){
    var olGrd = Ext.getCmp("gridDetalle");
    if (olGrd) var olSel = olGrd.getSelectionModel();
}


function fGrabar() {
    var olGrd = Ext.getCmp("gridDetalle");
    olGrd.stopEditing();
    var olRst = olGrd.store.getModifiedRecords();   
    var olCm=olGrd.getColumnModel();
    var slPar = "";
    var ilCnt = 0;
    var alPar = [];
    var pItm=null;
    var m;
    var pFld;
    
    for (var pIdx=0; pIdx < olRst.length; pIdx++ ){
        pItm=olRst[pIdx]; 
        ilCnt++;
        var alR = [];
        for (var i=0; i< pItm.fields.keys.length; i++){
            pFld =pItm.fields.keys[i] ;
            pI = i;
            m= pItm.modified[pFld];
            
            if((typeof(pItm.modified[pFld]) != "undefined" ||   // it is in modified fields
                (alPrimKeys[pFld]) ||                           // it is in PK
                (typeof( pItm.data._newFlag) != "undefined" && pItm.data._newFlag == true)  )) {
                slPar +=  "&" + pFld + "[" + pIdx + "]=" + olRst[pIdx].data[pFld]; // pasa solo los modificados o claves
                alR[pFld] = olRst[pIdx].data[pFld];
                ilColIdx  = olCm.findColumnIndex(pFld)
                if (ilColIdx >= 0) { // There is a Clumn for that dataIndex
                    olEdi = olCm.getCellEditor(ilColIdx,1);
                    if(typeof(olEdi) == "object"  && typeof(olEdi.field) == "object" && typeof(olEdi.field.hiddenName) == "string") { // INclir el campo oculto de los combos
                        pFld=olEdi.field.hiddenName;
                        slPar +=  "&" + pFld + "[" + pIdx + "]=" + olRst[pIdx].data[pFld]; 
                        alR[pFld] = olRst[pIdx].data[pFld];
                    } 
                }
            }
        }
        alPar[alPar.length] = alR;
    }
    a=[];
    a['uno']=[];
    a['uno'][1]= "un-1";
    a['uno'][2]= "un-2";
    a['dos']=20;
    a['tres']=30;
    ex=Ext.encode(a);
    slPar = '../Co_Files/CoTrTr_condetallecrud.php?cnt=' + ilCnt + slPar;
    olPar ={pCta: Ext.getCmp("gridDetalle") } //  definiendo parametros de cierre
    Ext.Ajax.request({
        url: slPar
        ,method:   "POST"
        ,params: {par:ex}
        ,success: function(pResp, pOpt){
            eval("var olResp=" + pResp.responseText)
            if (!olResp.success){
                alert ("No se grabaron todos los registros");
                }
            olGrd.store.load();
            }
        ,failure: function(pResp, pOpt){
                alert("No se aplico el proceso de Grabacion");
            }
        
        });
    }
function fAgregar(){
    var olGrd = Ext.getCmp("gridDetalle");
    olGrd.stopEditing();
    var i=0;
    var p=olGrd.store.data.length;
    var ilSec= p>0 ? olGrd.getStore().getAt(p-1).get("det_Secuencia") : 1;
    var n=5
    var alRecs=[];
    for (i=1; i<=n; i++) {
        var r=new olRec({
        _newFlag:true
        ,det_RegNumero:getFromurl("com_RegNumero", "")
        ,det_TipoComp:getFromurl("com_TipoComp", 'XX')
        ,det_NumComp:getFromurl("com_NumComp", -1)
        ,det_Secuencia:ilSec+i
	,det_CodEmpresa:0
        ,det_ClasRegistro:5
        ,det_ValDebito:0
        ,det_ValCredito:0
        ,det_Glosa:''
	,det_IDAuxiliar:0
	//,aux_Nombre:''
	,det_CodCuenta:0
	//,txt_Cuenta:''
        });
        alRecs.push(r);
    }
    olGrd.store.insert(p, alRecs);
    olGrd.startEditing(store.data.length -n, 1)
}
function fEliminar() {
    
    var olGrd = Ext.getCmp("gridDetalle");
    var olRst = olGrd.getSelections();
    var slPar = "";
    var ilCnt = 0;
    var alPar = [];
    Ext.each(olRst, function(pItm, pIdx, pArr){
        ilCnt++;
        var alR = [];
        Ext.each(pItm.fields.keys, function(pFld, pI, pA){
            if(alPrimKeys[pFld]){
                slPar +=  "&" + pFld + "[" + pIdx + "]=" + olRst[pIdx].data[pFld];
                alR[pFld] = olRst[pIdx].data[pFld];
            }
        })
        alPar[alPar.length] = alR;
    })
    slPar = '../Co_Files/CoTrTr_condetallecrud.php?pAction=DEL&cnt=' + ilCnt + slPar;
    Ext.Ajax.request({
        url: slPar
        ,method:   "POST"
        ,success: function(pResp, pOpt){
            eval("var olResp=" + pResp.responseText)
            if (!olResp.success){
                alert ("No se eliminarion todos los registros")
                }
            olGrd.store.load();
            }
        ,failure: function(pResp, pOpt){
                alert("No se aplico el proceso de Eliminacion")
            }
        });
    
    }


function fReader(){
    return new Ext.data.JsonReader({fields:[fCampos()]})
}

function fCampos(){
    return [
		{name: 'newFlag',        type:'int'}
		,{name: 'det_ValDebito',  type:'float'}
		,{name: 'det_ValCredito',  type:'float'}
		,{name: 'det_Glosa',  type:'string'}
		,{name: 'det_IDAuxiliar',  type:'int'}
		,{name: 'det_CodCuenta',  type:'int'}
	    ]
}

