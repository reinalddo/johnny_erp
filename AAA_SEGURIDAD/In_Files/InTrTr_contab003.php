<?php
//error_reporting (E_ALL);
if (!isset($_SESSION["g_user"]) && (isset($_GET["ByPass"]) && $_GET["ByPass"]=='prueba'))
{
    include_once("General2.inc.php");
    define("RelativePath", "..");
    include_once("adodb.inc.php");
    include_once("tohtml.inc.php");
    include_once("../Common.php");
    include_once("../LibPhp/ConLib.php");
    include_once("GenUti.inc.php");
}
else include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
include_once("../In_Files/InTrTr_contab.php");
$db->debug=fGetParam('pAdoDbg', 1);
/** INICIO
**/
$lastTra = NULL;
//$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->debug=fGetParam('pAdoDbg', 0);
    $trSql = "SELECT com_regnumero, com_tipocomp, com_numcomp, com_feccontab
                FROM concomprobantes
                WHERE com_tipocomp = 'IB' AND com_feccontab BETWEEN '2005-02-01' AND '2005-02-28'
                ORDER BY 4, 1,2" ;
//                AND com_regnumero = 42246
    $cs = $db->execute($trSql);
    if (!$cs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
    $cs->MoveFirst();
    
/*  error_log ("func\r\n", 3, "/tmp/my-errors.log");
    error_log (" SQL: " . $trSql . "\r\n", 3, "/tmp/my-errors.log");
    error_log (print_r($cla) . " contab1: " . $cla->cla_contabilizacion . "\r\n", 3, "/tmp/my-errors.log");
*/
//if (fGetparam('pGen', false) && fGetparam('pReg', false)) //  El proceso es llamado directamente
    while (!$com->EOF) {
        $com = $cs->FetchNextObject(false);
//        if ($com->com_regnumero > 0 )
        echo "<br> " . $com->com_feccontab . " "  . $com->com_regnumero . " " . $com->com_tipocomp . "  " . $com->com_numcomp;
        fContabInv($com->com_regnumero);
    }
?>
