<?php
//error_reporting(E_ALL);
include("General.inc.php");
include('tohtml.inc.php');
include("GenUti.inc.php");
include_once("adodb.inc.php");
session_start();
if (!isset($_SESSION['t'])) $_SESSION['t']=1;
if (fGetParam('b',0) == 0 ) $_SESSION['t']=1;
$_SESSION['t']+=1;
$aTxt = Array();
$aDat = Array();
$aExt = Array();
$rs = NULL;
$db=NULL;
//error_log("iini---\n",3,"/tmp/sugerir.err");
require_once "class.pAjax.php";
//error_log("1--\n",3,"/tmp/sugerir.err");
function suggestData($text='') {
    global $aDat;
    global $aTxt;
    global $rs;
    global $db;
    $db = NewADOConnection(DBTYPE);
    $db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
/**
  	$db =& ADONewConnection('access');
	$dsn = "Driver={Microsoft Access Driver (*.mdb)};Dbq=e:\\DESARROLLO\RTA\RTA 2004.mdb;Uid=Admin;Pwd=;";
	$db->Connect($dsn);
**/
/**
  	$db =& ADONewConnection('odbc');
    $db->PConnect('rtadatos','admin','').
**/
    $db->SetFetchMode(ADODB_FETCH_NUM);
    $db->debug=fGetParam('pAdoDbg', 0);
    $pQry = rawurldecode(fGetParam("pQry", ''));    // texto de evaluacion de la condicion base (LIKE + el contenido de esta variable)
    $pLim = rawurldecode(fGetParam('pLim', 10));
    $pMax = rawurldecode(fGetParam('pMax', 10));
    $pSQL = str_replace('|', '&',rawurldecode(fGetParam('pSql', false))); // Sentencia SQl con la condicion base parcial
    $pCon = str_replace('|', '&',rawurldecode(fGetParam('pCon', false))); // Condicion SQL secundaria, a�adida a la base
    $pQry = str_replace('*', '%',$pQry); // Cambiar el signo  * por  %
    set_time_limit (0) ;
/**
    $j=fGetParam('pSql', false);
    echo  $pSQL . " ----" . $j. "  - <br> ";
    echo rawurldecode(fGetParam('pSql', false)) . "  -- <br> ";
    echo str_replace(' ', '@',rawurldecode(fGetParam('pCon', false))) . " --- <br> ";
**/
/*
    $trSql = ($pSQL ? $pSQL :
                "SELECT concat(per_Apellidos, ' ', per_Nombres) as per_Nombres,
                per_CodAuxiliar, per_Apellidos, per_Nombres as txt_nombres, per_ruc
                FROM conpersonas
                WHERE concat(per_Apellidos, ' ', per_Nombres) LIKE " ) .
                " '" .$pQry . "%' " . ($pCon ? $pCon : '') . " ORDER BY 1 ";
*/
    $trSql = $pSQL;
//    error_log($trSql ."\n",3,"/tmp/sugerir.err");
    if (fGetParam("pAjaxDbg", false)) error_log($trSql ."\n",3,"/tmp/sugerir.err");
    $rs = $db->SelectLimit($trSql,  $pLim);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR DATOS");
    $rs->MoveFirst();
    $slQry   = fGetParam('pQryLiq', false);
    $recno=0;
    $ilFields = $rs->FieldCount();
    while ($record =$rs->FetchRow()) {
        // -----------------                    DATA RECORD TO PROCESS
        $aTxt[]= $record['per_Nombres'];
        for ($i=0; $i < $ilFields; $i++) {      // Prepara un arreglo por cada columna de salida
            $aDat[$i][$recno] = str_replace('&', '-', (is_null($record[$i])? '': $record[$i])) ;
        }
        
//            print_r($record);
//            echo "<br>";
        $recno+=1;
    }
    $database = & $aDat;
//    print_r($database);
//    return suggestions($text, $database);
      return $database;
}

function suggestions($text, $database) {
    $return = array();
    for ($i = 0; $i < count($database); $i++) {
        if (strtolower($text) == strtolower(substr($database[$i], 0, strlen($text))))
            $return[] = $database[$i];
    }
    return $return;
}

$AJAX = new pAjax;
$AJAX->enableExportProtection();
$AJAX->export("suggestData");
$AJAX->handleRequest();
$resp = suggestData('');
if (fGetParam('pAdoDbg', false)) {
    print_r($aDat);
    rs2html($rs,'border=2 cellpadding=3',array('NOMBRE DE CLIENTE','CODIGO'));
}
?>
