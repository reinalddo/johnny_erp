<?php
/*
*   Generar un string Xml con informacion de Base dedatos.
*	La sentencia Sql, se asigna via Sesion, o get
*	@ToDo:      Modificar, Hacer una funcion Generica, OO
*/
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fAplicaCondicion(&$db){
	global $gsSql, $appStatus, $giTotalRecs, $giPageRecs;
	$id=fGetParam('id', false);
	if ($id) {
		//$alQry=  (isset($_SESSION[$id]) && strlen($_SESSION[$id] > 1)) ? $_SESSION[$id] : false;
		$alQry=  $_SESSION[$id];
	}
	else
		$alQry=  fGetParam('pQry', false) ;
	if (!$id || $alQry === false ) {
		$appStatus->setError(false, 'h', '"DEBE DEFINIR UNA IDENTIFICACION DE INSTRUCCION SQL"') ;
		return;
	}
	//print_r($_SESSION);
	//echo $id . " / " . $pQry;
	/**
	*   Procesar registros de la BD
	**/
/*	echo "ech  " . $alQry. "<br> arr:";
	print_r($alQry);
	*/
	$alDefs= $_SESSION[$id . "_defs"]; //  defaul Values for params (if no defined via GET/POST)
	if (!is_array($alQry)){
	    $slTmp = $alQry;
	    unset($alQry);
		$alQry[]=$slTmp;
		unset($slTmp);
	}
	$gsSql="";
	$ilInd=0;
	$slQryCount = (isset($_SESSION[$id .'_count'])?$_SESSION[$id .'_count']: false);
	foreach($alQry as $slQry){
		$alVars = Array();
		preg_match_all ('/\{[a-z_A-Z0-9]+\}/',  $slQry, $alVars);
		if (count( $alVars)>0){
			foreach ($alVars[0] as $slParName) {
			    $slParTag   = $slParName;
			    $slParName  = str_replace("{", "", $slParName);
			    $slParName  = str_replace("}", "", $slParName);
			    $slDefValue = (isset($_SESSION[$id . "_defs"][$slParName])?
							  $_SESSION[$id . "_defs"][$slParName]:"");
                $slParValue = fGetParam($slParName, $slDefValue) ;
	//		    echo "<br> name:" . $slParName . " / tag:" . $slParTag .
	//				 " / val:" . $slParValue . "  post: " . $_POST[$slParName];
		        $slQry = str_replace($slParTag, $slParValue, $slQry);
//				if ($slQryCount && $ilInd == 0) $slQryCount = str_replace($slParTag, $slParValue, $slQryCount);
			}
		}
		$alQry[$ilInd]=$slQry;
		$gsSql .= $slQry ;  // the last Query
		$ilInd++;
	}
/*	list($slP1, $slP2) = split(" [fF][rR][oO][mM] ", $slQry, 2);
	echo "$slP1 \n $slP2\n\n";
	list($slP3, $slP4) = split(" LIMIT ", $slP2);
	echo "$slP3 \n $slP4\n";
	*/

    $rs= fSQL($db, $alQry);
    /*$giPageRecs=$rs->RecordCount();
    $slQryCount = "SELECT FOUND_ROWS() as totalRecs";
	if($rs=$db->execute($slQryCunt)){
	    $r=$db->fetchRow();
	    $giTotalRecs=$r['totalRecs'];
	}*/
    return $rs;
}
    /*
*   Recorre el recordset para generar cada campo en formato XML
*/

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
ob_start();
if (!isset ($_SESSION)) session_start();
include('General.inc.php');
include('GenUti.inc.php');
include('adodb.inc.php'); # load code common to ADOdb
include('adoConn.inc.php'); # load Common conection deffs
$db->debug=$_GET['pAdoDbg'];
$gsSql="";
$giTotalRecs = 0;
$giPageRecs = 0;
//print_r($db);
$rsDb = fAplicaCondicion($db) ;
if($db->debug >= 2) {
	header("Content-Type: text/html");
	if($db->debug >= 3)print_r($_SESSION);
	}
 else
	header("Content-Type: text/xml");

//
$doc  = NULL;
$doc  = new DomDocument('1.0', 'UTF-8');
$root = $doc->createElement('XmlData');
$root = $doc->appendChild($root);
//
//
$rset = $doc->createElement('recordset');

if ($rsDb){
	$ilCount= 0;
	while ($alRec = $rsDb->FetchRow()) {
		$xmlRec = $doc->createElement('record');
		foreach ($alRec as $slK => $slV) {
	    	$xmlField = $doc->createElement($slK);
			$xmlVal   = $doc->createTextNode(utf8_encode($slV));
			$v        = $xmlField->appendChild($xmlVal);
            $x        = $xmlRec->appendChild($xmlField);
	    }
		$det   = $rset->appendChild($xmlRec);
		$ilCount++;
    }
    
    $slQryCount = "SELECT FOUND_ROWS() as totalRecs";
	if($rs=$db->execute($slQryCount)){
	    $r=$rs->fetchRow();
	    $giTotalRecs=$r['totalRecs'];
	}
    $root->setAttribute('success', !$db->ErrorNo());
	$root->setAttribute('pageRecords', $rsDb->recordCount());
	$root->setAttribute('totalRecords',$giTotalRecs);
    $root->appendChild($rset);
} else {
	$slMensaje = 'ERROR EN LA CONSULTA';
    $root->setAttribute('success', 'false');
    $root->setAttribute('message', $slMensaje);
	$root->setAttribute('totalRecords', $giTotalRecs);
	$root->setAttribute('pageRecords', $giPageRecs);
	$xmlE = $doc->createElement('error');
	$xmlV = $doc->createTextNode(utf8_encode($appStatus->getError('t')));
	$v    = $xmlE->appendChild($xmlV);
	$root->appendChild($xmlE);
}
if ($_SESSION['pAppDbg'] == 2) {
	$msge =$doc->createElement('XmlSql');
	$t    = $doc->createTextNode("-- " . utf8_encode($gsSql) ." --" );
	$v    = $msge->appendChild($t);
	$root->appendChild($msge);
}
$xml_string = $doc->saveXML();
/*--------------*/
/*
$xml_string = str_replace("><", ">\r\n<", $xml_string);
$slArch=DBNAME . "_" . $pAnio . "_" . $pMes . ".xml";
$slRutaArch="../pdf_files/" . $slArch;
if($file = fopen($slRutaArch,"w")) {
 	fwrite($file, $xml_string);
 	fclose($file);
	}
 */
/*--------------*/
if($db->debug) echo "<code>";
echo $xml_string;
if($db->debug) echo "</code>";
?>
