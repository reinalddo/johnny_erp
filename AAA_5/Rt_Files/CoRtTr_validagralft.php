<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
/*
*   Validacion  General de Ruc, de transacciones fiscales
*   @author     Fausto Astudillo
*   @param      string		pQryCom  Condición de búsqueda
*   @output     Lista de Inconsistencias
*/
error_reporting(E_ALL);
//set_error_handler("repErrorhandler");
include("../LibPhp/ComExCCS.php");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("../LibPhp/MisRuc.php");
?>
	<title>VALIDACION DE RUC</title>
	<style type="text/css">
	body{
		font-family: Trebuchet MS, Lucida Sans Unicode, Arial, sans-serif;
		font-size:0.8em;

	}
	p{
		margin-bottom:0px;
		font-weight:bold;
	}

	/* Start layout CSS */
	.tableWidget_headerCell,.tableWigdet_headerCellOver,.tableWigdet_headerCellDown{	/* General rules for both standard column header and mouse on header of sortable columns */
		cursor:pointer;
		border-bottom:3px solid #C5C2B2;
		border-right:1px solid #ACA899;
		border-left:1px solid #FFF;
		background-color: #ECE9D8;
	}

	.tableWidget_headerCell{	/* Standard column header */
		border-top:2px solid #ECE9D8;

	}

	.tableWigdet_headerCellOver{	/* Rollover on sortable column header */
		border-top:2px solid #FFC83C;
	}
	.tableWidget tbody .tableWidget_dataRollOver{	/* Rollover style on mouse over (Data) */
		background-color:#EEC83C;	/* No mouseover color in this example - specify another color if you want this */
	}

	.tableWigdet_headerCellDown{
		border-top:2px solid #FFC83C;
		background-color:#DBD8C5;
		border-left:1px solid #ACA899;
		border-right:1px solid #FFF;
	}
	.tableWidget td{
		margin:0px;
		padding:2px;
		border-bottom:1px solid #EAE9E1;	/* Border bottom of table data cells */

	}
	.tableWidget tbody{
		background-color:#FFF;
	}
	.tableWidget{
		font-family:arial;
		font-size:12px;
		width:400px;
	}

	/* End layout CSS */


	div.widget_tableDiv {
		border:1px solid #ACA899;	/* Border around entire widget */
		height: 200px;
		overflow:auto;
		overflow-y:auto;
		overflow:-moz-scrollbars-vertical;
		width:400px;

	}

	html>body div.widget_tableDiv {
		overflow: hidden;
		width:400px;
	}

	.tableWidget thead{
		position:relative;
	}
	.tableWidget thead tr{
		position:relative;
		top:0px;
		bottom:0px;
	}



	.tableWidget .scrollingContent{
		overflow-y:auto;
		overflow:-moz-scrollbars-vertical;
		width:100%;

	}
	</style>

	<script type="text/javascript">
	/*
	(C) www.dhtmlgoodies.com, October 2005

	This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.

	Terms of use:
	You are free to use this script as long as the copyright message is kept intact. However, you may not
	redistribute, sell or repost it without our permission.

	Thank you!

	www.dhtmlgoodies.com
	Alf Magne Kalleland

	*/
	var tableWidget_tableCounter = 0;
	var tableWidget_arraySort = new Array();
	var tableWidget_okToSort = true;
	var activeColumn = new Array();
	var arrowImagePath = "images/";	// Path to arrow images

	function addEndCol(obj)
	{
		if(document.all)return;
		var rows = obj.getElementsByTagName('TR');
		for(var no=0;no<rows.length;no++){
			var cell = rows[no].insertCell(-1);
			cell.innerHTML = ' ';
			cell.style.width = '13px';
			cell.width = '13';

		}

	}

	function highlightTableHeader()
	{
		this.className='tableWigdet_headerCellOver';
		if(document.all){	// I.E fix for "jumping" headings
			var divObj = this.parentNode.parentNode.parentNode.parentNode;
			this.parentNode.style.top = divObj.scrollTop + 'px';

		}

	}

	function deHighlightTableHeader()
	{
		this.className='tableWidget_headerCell';
	}

	function mousedownTableHeader()
	{
		this.className='tableWigdet_headerCellDown';
		if(document.all){	// I.E fix for "jumping" headings
			var divObj = this.parentNode.parentNode.parentNode.parentNode;
			this.parentNode.style.top = divObj.scrollTop + 'px';
		}
	}

	function sortNumeric(a,b){

		a = a.replace(/,/,'.');
		b = b.replace(/,/,'.');
		a = a.replace(/[^\d\.\/]/g,'');
		b = b.replace(/[^\d\.\/]/g,'');
		if(a.indexOf('/')>=0)a = eval(a);
		if(b.indexOf('/')>=0)b = eval(b);
		return a/1 - b/1;
	}


	function sortString(a, b) {

	  if ( a.toUpperCase() < b.toUpperCase() ) return -1;
	  if ( a.toUpperCase() > b.toUpperCase() ) return 1;
	  return 0;
	}
	function cancelTableWidgetEvent()
	{
		return false;
	}

	function sortTable()
	{
		if(!tableWidget_okToSort)return;
		tableWidget_okToSort = false;
		/* Getting index of current column */
		var obj = this;
		var indexThis = 0;
		while(obj.previousSibling){
			obj = obj.previousSibling;
			if(obj.tagName=='TD')indexThis++;
		}
		var images = this.getElementsByTagName('IMG');

		if(this.getAttribute('direction') || this.direction){
			direction = this.getAttribute('direction');
			if(navigator.userAgent.indexOf('Opera')>=0)direction = this.direction;
			if(direction=='ascending'){
				direction = 'descending';
				this.setAttribute('direction','descending');
				this.direction = 'descending';
			}else{
				direction = 'ascending';
				this.setAttribute('direction','ascending');
				this.direction = 'ascending';
			}
		}else{
			direction = 'ascending';
			this.setAttribute('direction','ascending');
			this.direction = 'ascending';
		}



		if(direction=='descending'){
			images[0].style.display='inline';
			images[0].style.visibility='visible';
			images[1].style.display='none';
		}else{
			images[1].style.display='inline';
			images[1].style.visibility='visible';
			images[0].style.display='none';
		}


		var tableObj = this.parentNode.parentNode.parentNode;
		var tBody = tableObj.getElementsByTagName('TBODY')[0];

		var widgetIndex = tableObj.id.replace(/[^\d]/g,'');
		var sortMethod = tableWidget_arraySort[widgetIndex][indexThis]; // N = numeric, S = String
		if(activeColumn[widgetIndex] && activeColumn[widgetIndex]!=this){
			var images = activeColumn[widgetIndex].getElementsByTagName('IMG');
			images[0].style.display='none';
			images[1].style.display='inline';
			images[1].style.visibility = 'hidden';
			if(activeColumn[widgetIndex])activeColumn[widgetIndex].removeAttribute('direction');
		}

		activeColumn[widgetIndex] = this;

		var cellArray = new Array();
		var cellObjArray = new Array();
		for(var no=1;no<tableObj.rows.length;no++){
			var content= tableObj.rows[no].cells[indexThis].innerHTML+'';
			cellArray.push(content);
			cellObjArray.push(tableObj.rows[no].cells[indexThis]);
		}

		if(sortMethod=='N'){
			cellArray = cellArray.sort(sortNumeric);
		}else{
			cellArray = cellArray.sort(sortString);
		}

		if(direction=='descending'){
			for(var no=cellArray.length;no>=0;no--){
				for(var no2=0;no2<cellObjArray.length;no2++){
					if(cellObjArray[no2].innerHTML == cellArray[no] && !cellObjArray[no2].getAttribute('allreadySorted')){
						cellObjArray[no2].setAttribute('allreadySorted','1');
						tBody.appendChild(cellObjArray[no2].parentNode);
					}
				}
			}
		}else{
			for(var no=0;no<cellArray.length;no++){
				for(var no2=0;no2<cellObjArray.length;no2++){
					if(cellObjArray[no2].innerHTML == cellArray[no] && !cellObjArray[no2].getAttribute('allreadySorted')){
						cellObjArray[no2].setAttribute('allreadySorted','1');
						tBody.appendChild(cellObjArray[no2].parentNode);
					}
				}
			}
		}

		for(var no2=0;no2<cellObjArray.length;no2++){
			cellObjArray[no2].removeAttribute('allreadySorted');
		}

		tableWidget_okToSort = true;


	}

	function initTableWidget(objId,width,height,sortArray)
	{
		width = width + '';
		height = height + '';
		var obj = document.getElementById(objId);
		tableWidget_arraySort[tableWidget_tableCounter] = sortArray;
		if(width.indexOf('%')>=0){
			obj.style.width = width;
			obj.parentNode.style.width = width;
		}else{
			obj.style.width = width + 'px';
			obj.parentNode.style.width = width + 'px';
		}

		if(height.indexOf('%')>=0){
			obj.style.height = height;
			obj.parentNode.style.height = height;

		}else{
			obj.style.height = height + 'px';
			obj.parentNode.style.height = height + 'px';
		}
		obj.id = 'tableWidget' + tableWidget_tableCounter;
		addEndCol(obj);

		obj.cellSpacing = 0;
		obj.cellPadding = 0;
		obj.className='tableWidget';
		var tHead = obj.getElementsByTagName('THEAD')[0];
		var cells = tHead.getElementsByTagName('TD');
		for(var no=0;no<cells.length;no++){
			cells[no].className = 'tableWidget_headerCell';
			cells[no].onselectstart = cancelTableWidgetEvent;
			if(no==cells.length-1){
				cells[no].style.borderRight = '0px';
			}
			if(sortArray[no]){
				cells[no].onmouseover = highlightTableHeader;
				cells[no].onmouseout =  deHighlightTableHeader;
				cells[no].onmousedown = mousedownTableHeader;
				cells[no].onmouseup = highlightTableHeader;
				cells[no].onclick = sortTable;

				var img = document.createElement('IMG');
				img.src = arrowImagePath + 'arrow_up.gif';
				cells[no].appendChild(img);
				img.style.visibility = 'hidden';

				var img = document.createElement('IMG');
				img.src = arrowImagePath + 'arrow_down.gif';
				cells[no].appendChild(img);
				img.style.display = 'none';


			}else{
				cells[no].style.cursor = 'default';
			}


		}
		var tBody = obj.getElementsByTagName('TBODY')[0];
		if(document.all && navigator.userAgent.indexOf('Opera')<0){
			tBody.className='scrollingContent';
			tBody.style.display='block';
		}else{
			tBody.className='scrollingContent';
			tBody.style.height = (obj.parentNode.clientHeight-tHead.offsetHeight) + 'px';
			if(navigator.userAgent.indexOf('Opera')>=0){
				obj.parentNode.style.overflow = 'auto';
			}
		}

		for(var no=1;no<obj.rows.length;no++){
			obj.rows[no].onmouseover = highlightDataRow;
			obj.rows[no].onmouseout = deHighlightDataRow;
			for(var no2=0;no2<sortArray.length;no2++){	/* Right align numeric cells */
				if(sortArray[no2] && sortArray[no2]=='N')obj.rows[no].cells[no2].style.textAlign='right';
			}
		}
		for(var no2=0;no2<sortArray.length;no2++){	/* Right align numeric cells */
			if(sortArray[no2] && sortArray[no2]=='N')obj.rows[0].cells[no2].style.textAlign='right';
		}

		tableWidget_tableCounter++;
	}


	function highlightDataRow()
	{
		if(navigator.userAgent.indexOf('Opera')>=0)return;
		this.className='tableWidget_dataRollOver';
		if(document.all){	// I.E fix for "jumping" headings
			var divObj = this.parentNode.parentNode.parentNode;
			var tHead = divObj.getElementsByTagName('TR')[0];
			tHead.style.top = divObj.scrollTop + 'px';

		}
	}

	function deHighlightDataRow()
	{
		if(navigator.userAgent.indexOf('Opera')>=0)return;
		this.className=null;
		if(document.all){	// I.E fix for "jumping" headings
			var divObj = this.parentNode.parentNode.parentNode;
			var tHead = divObj.getElementsByTagName('TR')[0];
			tHead.style.top = divObj.scrollTop + 'px';
		}
	}

	</script>


</head>
<body>
<div class="widget_tableDiv">
<table id="myTable">
	<thead>
		<tr>
			<td>CODIGO</td>
			<td>NOMBRE</td>
			<td>RUC</td>
			<td>TIPO ID</td>
			<td>OBSERVACIONES</td>
		</tr>
	</thead>
	<tbody class="scrollingContent">
<?php
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
//
    $alSql[] = "SELECT DISTINCT per_CodAuxiliar,
					concat(per_Apellidos, ' ', per_Nombres) as txt_Descripcion,
					case per_TipoID
						when 1 then 2
						when 2 then 1
					else 1
					end as per_TipoID,
					per_Ruc
				FROM conpersonas join fistransac on tmp_codauxiliar = per_codauxiliar
				WHERE per_codAuxiliar <> 0
				" . (($pQry) ? " AND " . $pQry : " " ) . " ORDER BY 2 ";

    $rs= fSQL($db, $alSql);
    if (!$rs) die("NO SE EJECUTo LA CONSULTA: " . $alSql[0]);
    return $rs;
}


//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//

    $db = NewADOConnection(DBTYPE);
    $db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
    $db->SetFetchMode(ADODB_FETCH_BOTH);
    $db->debug=fGetParam('pAdoDbg', 0);
    $pQry = rawurldecode(fGetParam("pQry", ''));    // texto de evaluacion de la condicion base (LIKE + el contenido de esta variable)
    $pLim = rawurldecode(fGetParam('pLim', 10));
    $pMax = rawurldecode(fGetParam('pMax', 10));
    set_time_limit (0) ;
	$rs= fDefineQry($db, $pQry);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR DATOS");
    $rs->MoveFirst();
    $recno=0;
    $ilFields = $rs->FieldCount();
    $txt="";
    $i=0;
//    echo "<table >";
//    echo "<tr><td>CODIG<td >NOMBRE<td>RUC<td>TIPO ID <td>OBSERVACIONES <td></tr>";
    $aOut= array();
    while ($record =$rs->FetchRow()) {
//    	print_r($record);
        // -----------------                    DATA RECORD TO PROCESS
		$iRet = fValidaRuc($record['per_Ruc'], $record['per_TipoID']);
		if ($iRet < 0) {
	        echo "<tr>";
    	    echo "<td>";

			$aOut[$i]['COD'] = $record['per_CodAuxiliar'];
			echo $aOut[$i]['COD'] . "<td style='text-align:left'>";
			$aOut[$i]['NOMBRE'] = $record['txt_Descripcion'];
	        echo $aOut[$i]['NOMBRE'] . "<td>";
			$aOut[$i]['RUC'] = $record['per_Ruc'];
			echo  $aOut[$i]['RUC'] . "<td>";
			$aOut[$i]['TIPO'] = $record['per_TipoID'];
			echo $aOut[$i]['TIPO'] . "<td>";
			switch ($iRet) {
			    case -2:
			        $aOut[$i]['OBS'] = 'Digitos 1 y 2 Invalidos';
			        break;
				case -3:
				    $aOut[$i]['OBS'] = 'Digito 3 Invalido';
				    break;
                case -13:
				    $aOut[$i]['OBS'] = 'Longitud Invalida';
				    break;
				case -99:
					$aOut[$i]['OBS'] = 'Digito verificador Invalido';
					break;
				case -90:
					$aOut[$i]['OBS'] = 'Tipo de ID Invalido';
					break;
				default:
				    $aOut[$i]['OBS'] = 'posicion ' . $iRet .' Invalida';
			}
			echo $aOut[$i]['OBS'] . "<td>";
			$i++;
			echo "</tr>";
		}
    }
//  echo "</table> <br>------ " . $i . " registros";
    echo "</tr> <tr><td colspan=4>  " . $i . " Registros </td> </tr>";
//  print_r($database);
//  return suggestions($text, $database);
?>
	</tbody>
</table>
</div>
<script type="text/javascript">
initTableWidget('myTable',800,500,Array('S','S',false,'N','S'));
</script>

</body>
</html>
