<?php
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
require "class.pAjax.php";
include("../LibPhp/LibInc.php");
//error_reporting(E_ALL);
$gbConec= true;
$db = NewADOConnection("mysql");
$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or $gbConec= false;
$db->debug=fGetParam('pAdoDbg', 0);
class datosRetorno {
    var $codRetorno = 0;
    var $mensaje = " ";
    var $datos = array();
}
$olResp = new datosRetorno();
/**
*   Funcion que se exporta al proceso Ajax
*   param:  $sql        string      cadena de sentencia SQl q ejecutar
*   return: object                  Objeto conteniendo la respuesta
*/
function fDBejecutar($pSql=false){
	global $gbConec;
	global $db;
	global $olResp;
	if (!$pSql) $pSql = fgetParam("pSql",false);
	if (!$pSql) {
	    $olResp->codRetorno = -1;
		$olResp->mensaje = "NO SE HA DEFINIDO INSTRUCCION SQL";
	}
	if (!$gbConec) {
	    $ilRetCode=0;
	    $olResp->codRetorno = -1; $olResp->mensaje = "No existe conexion a la Base de Datos";
	    return $olResp;
	}
	$olResp->codRetorno = -1;
	$olResp->mensaje = "No se ejecuto correctamente la instruccion SQL";
	$rs=null;
	$rs = $db->Execute($pSql); 
	error_log($pSql ."\n",3,"/tmp/comando.err");
	if (fGetParam('pAjaxDbg', false)  == 1) error_log($pSql ."\n",3,"/tmp/comando.err");
	if ($rs) {
		$rs->MoveFirst();
		$olResp->codRetorno = 0;
		$olResp->mensaje = "OK";
		if ($rs->RecordCount() > 0)	$olResp->datos =SmartyArray($rs);
	}
	return $olResp;
}

function fErrorSql() {
    $olResp->codRetorno = -1;
	$olResp->mensaje = "Instruccion SQL mal definida ";
};

$AJAX = new pAjax;
$AJAX->enableExportProtection();
$AJAX->disableDomainProtection();
$AJAX->export("fDBejecutar");
$AJAX->handleRequest();
$pSqlInt = fgetParam("pSqlInt",false);
if ( $pSqlInt){
	fDBEjecutar($pSqlInt);
	print_r($olResp);
	}
?>
