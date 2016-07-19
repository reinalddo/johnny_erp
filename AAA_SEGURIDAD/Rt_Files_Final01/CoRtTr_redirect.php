<?php
/*
 *      redireccionamiento de una pagina con el ID retencion al comprobante contable asociado
 */
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("tohtml.inc.php");
include_once("../Common.php");
include_once("GenUti.inc.php");
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->debug = CCGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

$ilDat = fDBValor($db, "concomprobantes", "com_TipoComp, com_RegNumero, com_NumComp, com_NumRetenc", " com_NumRetenc = " . fGetParam("id",-9999));
//print_r($ilFlag);
$slUrl="../In_Files/InTrTr_Cabe.php?pCabecera=false&" .  
        "com_RegNumero=" . $ilDat[1] .
	"&CoTrTr_detallePageSize=8&pTipoComp=" . $ilDat[0] .  "&pNumComp=" . $ilDat[2] . 
	"#bkInicio";

header("Location: " . $slUrl); /* Redirect browser */
exit
?>