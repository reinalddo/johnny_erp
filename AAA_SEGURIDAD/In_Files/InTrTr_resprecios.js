/**
*
*
**/
/*/
*   Variables Generales
**/
var customerGrid, oPreciosProv, oPreciosProvEd, detailGrid;
var slCol1, oPreciosProvEd;
window.onload=bodyOnLoad;

/**
document.onkeydown=function(e){
    alert(Event.element.id)}
document.onkeypress=function(){ alert("Kp")}
document.onkeyup=function(){ alert("Kup")}
**/
function bodyOnLoad() {
  Rico.Corner.round('explanation')
  Rico.Corner.round('oPreciosProv_header')
//  Rico.Corner.round('pieCen')
  fInicializaGrid();
  fInicializaGridEd();
}
/**
*   Inicializacion de Datagrid basado en RicoLivegrid.
**/
function fInicializaGrid(){
//  specNume = {type:'number', className:'posNumber', format:{decPlaces:2, thouSep:'', decPoint:'.',negSign:'-'},canFilter:true};
  specNume = {className:'posNumber', format:{decPlaces:2, thouSep:',', decPoint:'.',negSign:'-'},canFilter:true};
  var opts = {
  		bufferTimeout    : 200000,
               menuEvent     : 'contextmenu',
               frozenColumns : 3,
               canSortDefault: true,
               canHideDefault: true,
               allowColResize: true,
               canFilterDefault:  true,
               highltOnMouseOver: true,
               columnSpecs   : [,,,,,specNume,specNume,specNume],
               headingRow    : 0,
               prefetchBuffer: true,
               beforeRowHandler:    false,
               beforeSaveHandler:   false,
               afterRowHandler:     false,
               onSuccesSaveHandler: false,
               onFailSaveHandler:   false
             };
  // -1 on the next line tells LiveGrid to determine the number of rows based on window size
  oPreciosProv=new Rico.LiveGrid ('oPreciosProv', -14, 50, 'InTrTr_resprecios.pro.php',opts);

}

function fProcesarFact(pFact){
    pFact=getFromurl("pFact", -1);
    var oTabla1 = $("oPreciosProv_tab1");
    var oTabla0 = $("oPreciosProv_tab0");
    var ilRows = oTabla1.childNodes[1].childNodes.length;
    var ilCols = oTabla1.childNodes[1].childNodes[0].childNodes.length;
    var r = 0;
    var c = 0;
    var slTexto="";
    var valor = "";
    var ilModif=0;
    alCol1=new Array();
    alCol4=new Array();
    for (r; r< ilRows; r++){
        slRubro = RicoUtil.getInnerText(oTabla0.childNodes[1].childNodes[r].childNodes[1]);
        slValor = RicoUtil.getInnerText(oTabla1.childNodes[1].childNodes[r].childNodes[4]);
        flValor = parseFloat(slValor);
        if (isNaN(flValor)) flValor = 0;
        if (flValor != 0) {
            ilModif++;
            alCol4.push(flValor);
            alCol1.push(slRubro);
        }
    }
    slCol1=alCol1.toString();
    slCol4=alCol4.toString();
    document.location.replace("../In_Files/InTrTr_resprecios.php?pText="+ getFromurl("pText", "") +
                              "&pFact="+pFact +"&numRows=" + ilModif+ "&pCol1=" + slCol1 +
                              "&pCol4=" + slCol4 + "&pId=" + getFromurl("pId", ""));
}

/**
*   Inicializacion de Datagrid Editable basado en RicoLivegrid.
**/
function fInicializaGridEd(){
  var options = {
      canEdit: false,
      canEditCols   : [false,false,false,false,false],
      canAdd: false,
      CanDelete: false,
      ConfirmDelete: false,
      ConfirmDeleteCol:-1,
      RecordName:'Resumen de Precios',
      dataMenuHandler: fMenuGrid,
      updateUrl: false,
      deleteUrl: false
    };
    oPreciosProvEd=new Rico.TableEdit(oPreciosProv, options);
}


function keyfilter(txtbox,idx) {
  oPreciosProv.columns[idx].setFilter('LIKE ',txtbox.value+'%',Rico.TableColumn.USERFILTER,function() {txtbox.value='';});
}

function fMenuGrid(objCell,onBlankRow){
  if (onBlankRow==true) return true;
  gridMenu.addMenuItem("-------", fProcesarFact , true);
  return true;
 /* */
}

function beforeSaveRow(d, r){
    oPreciosProvEd.finishEditMode();
    oPreciosProvEd.grid.lastEditedRow=-1;
    d.lastEditedRow=-1;
    return false;
}
function afterSaveRow(d, r){
    d.lastEditedRow=-1;
    return true;
}

/**
*   Manejador de Evento Click para la celda 4. En este contexto 'this' hacereferencia al objeto 'celda'
**/
function col4_onClick(e, obj){
    this.cell.childNodes[0].innerHTML = this.cell.previousSibling.childNodes[0].innerHTML;
    var a=this.cell.id.split(/_/);
    var l=a.length;
    var r=parseInt(a[l-2]);
    var c=parseInt(a[l-1]);
//    oPreciosProv.rowHighlight(r+oPreciosProv.headerRowCnt);
    oPreciosProv.rowIdx = r;
    oPreciosProvEd.rowIdx = r;
    if (oPreciosProv.lastEditedRow <0) {
        oPreciosProvEd.grid.lastEditedRow=r;
        oPreciosProvEd.editRecord();
    }
}

