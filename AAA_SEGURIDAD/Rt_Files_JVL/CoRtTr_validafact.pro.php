<?php
/*
*   Proceso que consulta si ya esta registrada un documento por identificacion de proveedor y numero secuencial
*   @author     Erika Suarez
*/
error_reporting(E_ALL);
include("../LibPhp/ComExCCS.php");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){
    $alSql = Array();
    $idProv = fGetParam('pProv', 0);
    $estab = fGetParam('pEst', 0);
    $ptoEmi = fGetParam('pPun', 0);
    $Sec = fGetParam('pSecu', 0);
    $ID = fGetParam('pID', 0);
    
    
    $alSql[] = "select count(*) as Num
		from fiscompras
		where idProvFact = ".$idProv."
		and establecimiento= ".$estab."
		and puntoEmision= ".$ptoEmi."
		and secuencial = ".$Sec;
    
    if ($ID > 0){
     $alSql[] = "select count(*) as Num
		from fiscompras
		where idProvFact = ".$idProv."
		and establecimiento= ".$estab."
		and puntoEmision= ".$ptoEmi."
		and secuencial = ".$Sec." and ID != ".$ID;
    }
    
    $rs= fSQL($db, $alSql);
    if (!$rs) die("NO SE EJECUTO LA CONSULTA: " . $alSql[0]);
    return $rs;
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
    $db = NewADOConnection(DBTYPE);
    $db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
    $db->SetFetchMode(ADODB_FETCH_BOTH);
    $db->debug=fGetParam('pAdoDbg', 0);
    
    $pQry = rawurldecode(fGetParam("pQry", ''));
    $pLim = rawurldecode(fGetParam('pLim', 10));
    $pMax = rawurldecode(fGetParam('pMax', 10));
    
    $rs= fDefineQry($db);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR DATOS");
    
    $rs->MoveFirst();
    $Num="";
    while ($record =$rs->FetchRow()) {
	$Num = $record['Num'];
    }    
	echo  $Num;
?>
