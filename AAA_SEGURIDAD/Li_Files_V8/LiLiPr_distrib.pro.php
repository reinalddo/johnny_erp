<?php
/*
*   LiLiPr_distrib.pro.php: Proceso de distribucion de valores liquidados por cia.
*   @author     Fausto Astudillo
*   @param      string		pProc	Numero de Proceso
*   @param      string		pNliq	Numero de liquidacin(opcional)
*   @output     contenido pdf del reporte.
*   @rev	fah   16/07/09	Agregar el numero de proceso deliqudacinal registro liqliquidacionesdist (redundante,pero practico)
*   @rev	fah   29/07/09	No aplicar valor minimo de 1 Dolar
*   @rev	fah   19/08/09  Asegurarse de no restar los ingresos que no se distibuyen segun las tarjas
*/
error_reporting(E_ALL);
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){
	$slSql = "Select * from liqliquidaciones WHERE liq_codrubro not in (1,3,5)";
    return $rs;
}
/**
 *	Devuelve el texto SQL requerido para seleccionar la informacion del costo de fruta segun liqtarjadetal (PO,DiF)
 *	@param  string  $pCond 	Condicion de busqueda
 **/
function fSqlIngrFru($pCond){
// Precio Oficial
	return "
SELECT
	tad_liqproceso,
	tad_liqnumero,								
	tad_CodEmpresa,
	1 as codrubro,
	sum(tad_cantrecibida - tad_cantrechazada) AS CAJAS,
	SUM(round((tad_cantrecibida - tad_cantrechazada) * TAD_VALUNITARIO,2) ) as VALOR
FROM 
	liqtarjacabec
	JOIN liqtarjadetal ON tad_NUmTarja  = tar_NUmTarja
WHERE tad_liqnumero in (" . $pCond ." ) 
GROUP BY 1,2,3,4

UNION ".
//-- Bono
"
SELECT
	tad_liqproceso,
	tad_liqnumero,								
	tad_CodEmpresa,
	2 as codrubro,
	sum(tad_cantrecibida - tad_cantrechazada) AS CANTIDAD,
	SUM(round((tad_cantrecibida - tad_cantrechazada  ) * (if(TAD_DIFUNITARIO<0,ABS(TAD_DIFUNITARIO) ,0)),2)) as VALOR
FROM 
	liqtarjacabec
	JOIN liqtarjadetal ON tad_NUmTarja  = tar_NUmTarja 
WHERE tad_liqnumero in (" . $pCond ." ) 
GROUP BY 1,2,3,4

UNIon ".
// Adelanto
"SELECT
	tad_liqproceso,
	tad_liqnumero,								
	tad_CodEmpresa,
	5 as codrubro,
	sum(tad_cantrecibida - tad_cantrechazada) AS CANTIDAD,
	SUM(round((tad_cantrecibida - tad_cantrechazada  ) * (if(TAD_DIFUNITARIO<0,0,TAD_DIFUNITARIO)),2)) as VALOR
FROM 
	liqtarjacabec
	JOIN liqtarjadetal ON tad_NUmTarja  = tar_NUmTarja
WHERE tad_liqnumero in (" . $pCond ." )
GROUP BY 1,2,3,4

UNIon ".
// RETENCIONES
"SELECT
	tad_liqproceso,
	tad_liqnumero,								
	tad_CodEmpresa,
	16 as codrubro,
	sum(0) AS CANTIDAD,
	SUM(0.01 * ROUND((tad_CantRecibida - tad_cantrechazada)  * (tad_ValUnitario - if(tad_DifUnitario <0, tad_DifUnitario,0)), 2)) as VALOR
FROM 
	liqtarjacabec
	JOIN liqtarjadetal ON tad_NUmTarja  = tar_NUmTarja
WHERE tad_liqnumero in (" . $pCond ." ) 
GROUP BY 1,2,3,4
ORDER BY 1,2,3,4";
}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
include_once("GenUti.inc.php");
include_once("General.inc.php");
include_once("adodb.inc.php");
error_reporting(E_ALL);
$ilPag = 1;
$giAppDbg  = fGetParam('pAppDbg', 0);
$giProc  = trim(fGetParam('pPro', -1));	// Aplicar a unprocesoespecifico
$giNliq  = trim(fGetParam('pNliq', -1)); // Aplicar a una liquidacion
$giSema  = trim(fGetParam('pSem', -1)); // @TODO Aplicar a una semana
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
$slQry="";
$slQry = (($giProc >0) ? " liq_numproceso = " .$giProc : "");
$slQry .= ($giNliq >0) ? ((strlen($slQry)>0)? " AND " : "") ." liq_numliquida = " .$giNliq : "";
//$slQry = ($giRubr >0) ? ((strlen($slQry)>0)? " AND " : "") ." liq_codrubro   = " .$giRubr : "";
if ($giAppDbg) print_r($_GET);
$gsRubIng = "1,2,5";  // Codigo de Rubros asociados a fruta
$slCond= "Select distinct liq_numliquida from liqliquidaciones where " . $slQry . " ";
$db->Execute("delete from liqliquidacionesdist WHERE ldi_numliquida in ( " . $slCond . ")");
// Insertar Ingresos asociados a precio de fruta
$db->Execute("Insert into liqliquidacionesdist " . fSqlIngrFru($slCond));
if(fGetParam("pStep",0)==1) die("Ejecucion parcial");
set_time_limit (0) ;
/*$slSql = "
SELECT
	com_receptor,
	liq_numproceso as npro,
	liq_numliquida as nliq,
	liq_codrubro as rubr,
	rub_inddbcr as dbcr,
	liq_valtotal as valor
FROM  
	liqliquidaciones
	JOIN concomprobantes on com_tipocomp='LQ' and com_numcomp =liq_numliquida
	JOIN liqrubros on rub_codrubro = liq_codrubro
WHERE liq_NumLiquida in ( " . $slCond . ")  and loq_codrubro not in (". $gsRubIng . ") and liq_valtotal <> 0
ORDER by 1,3,2;
";*/
/* Valores pendientes de distribuir */
$slSql = "
SELECT
	com_receptor,
	lpd_numproceso as npro,
	lpd_numliquida as nliq,
	lpd_codrubro as rubr,
	rub_inddbcr as dbcr,
	lpd_valtotal as valor
FROM  
	v_liqliqdistpend
	JOIN concomprobantes on com_tipocomp='LQ' and com_numcomp =lpd_numliquida
	JOIN liqrubros on rub_codrubro = lpd_codrubro
WHERE lpd_NumLiquida in ( " . $slCond . ")  and lpd_pendiente > 0.02
ORDER by 1,3,2;
";
//WHERE lpd_NumLiquida in ( " . $slCond . ")  and lpd_codrubro not in ( " . $gsRubIng . ") and lpd_pendiente <> 0
//WHERE lpd_NumLiquida in ( " . $slCond . ")  and lpd_codrubro not in (". $gsRubIng . ") and lpd_pendiente <> 0
$rs =& $db->Execute($slSql);
$ilCurrLiq = -999; // Liquidacion Actual
$ilCurrEmp = 0;//	Indice de Empresa actual
$alEmp = array();
$slText=" ";

echo "<br>DISTRIBUCION DE LIQUIDACIONES POR CIA:</font></td></tr><tr><td>";
echo "<table width='90%' align='center' ><tr><td >NOMBRE</td ><td >EMPRESA</td ><td >LIQ. NRO.</td ><tr>";
if ($rs) {
	while ($rec = $rs->FetchNextObject(false)) {
		if ($ilCurrLiq <> $rec->nliq){
			$slSql ="
				SELECT 
					ldi_codempresa EMPR,
					sum(ldi_valtotal * rub_IndDbCr )as  INGR,
					sum(0 )as  EGRE
					FROM  
					liqliquidacionesdist 
					JOIN liqrubros on rub_codrubro = ldi_codrubro
					WHERE ldi_numliquida in ( " . $rec->nliq . ") AND ldi_codrubro in (". $gsRubIng. " )
				GROUP BY
					1
				order by 1,2,3 "; //				Valores totales de Ingresos por Cia
			$alValEmp= $db->GetAssoc($slSql, false, true);
			$alEmp = array_keys($alValEmp);
			if ($giAppDbg) print_r($alEmp);
			$ilCurrLiq = $rec->nliq;
			$ilCurrEmp = 0;//	Indice de Empresa actual
			echo "<tr><td >". $rec->com_receptor . "</td ><td >" . "</td ><td >". $rec->nliq . "</td ><tr>";
		}
		$flAplicar = $rec->valor;	     // VAlor a aplicar
		while ($flAplicar <> 0){
			$flValApli = 0;
			$slEmpre = $alEmp[$ilCurrEmp]; // DB de empresa actual
			if ($rec->dbcr < 0 ) {// Es un egreso
				$flMaxDesc= $alValEmp[$slEmpre]["INGR"] - $alValEmp[$slEmpre]["EGRE"]; // -1; NO aplicar valor minimo // Maximo valor disponible para descontar
				if ($flAplicar <= $flMaxDesc) { // Si el valor adescontar <= Maximo Posible en la empr actual
					$flValApli =  $flAplicar;		 //Aplicarlo
				} else {
					$flValApli =  $flMaxDesc;    // Descontar solo el maximo posible
				}
				$flAplicar -= $flValApli;	     // Actualizar el VAlor pendiente de aplicar
				$alValEmp[$slEmpre]["EGRE"] += $flValApli;  //#fah19/08/09  Solo se incrementa con egresos
			}
			else {	//			Es un Ingreso
				$flAplicar = 0;	     // VAlor pendiente de aplicar
				$alValEmp[$slEmpre]["INGR"] += $rec->valor;  // Incrementa el total de ingresos
				$flValApli =$rec->valor;	// valor a grabar
			}
			$slSql = "Replace liqliquidacionesdist
				VALUES(" . $rec->npro . "," . $rec->nliq . ",'" . $slEmpre . "',". $rec->rubr . ",0," . $flValApli. " )";
			$db->Execute($slSql );
			if ($flAplicar > 0){ // Si hay Valor pendiente,
				$ilCurrEmp += 1; // Aplicar en  la sigte empresa
			}
		}
	}
}

//$rs = fDefineQry($db, $slCond);
?>
