<?php
include("/SERVER/PHP/includes/adodb/adodb.inc.php");
include("/SERVER/PHP/includes/general/General.inc.php");
include("/SERVER/PHP/includes/general/GenUti.inc.php");
require ('../LibPhp/Smarty_AAA.php');
include("../LibPhp/LibInc.php");
//error_reporting(E_ALL);
$Tpl = new Smarty_AAA;
//$Tpl->debugging=true;
$db = NewADOConnection("mysql");
$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
//$db=& fConexion();
//set_time_limit (0) ;
$db->debug=fGetParam('pAdoDbg', 0);
//
$sSql= "SELECT tab_Codigo, tab_Abreviatura
		FROM fistablassri
		WHERE tab_codtabla = 'A'  ";
$rs = $db->Execute($sSql);
//print_r($db);
if ($rs){
	$rs->MoveFirst();
	$aNomCols = SmartyArray($rs);
	$Tpl->assign("aNomCols", $aNomCols);
	$Tpl->assign("numCols", $rs->RecordCount());
}
/**
*
*/
$sSql=
	"SELECT  tmp_CodCompr +0, tmp_DesCompr,
		mAX(IF (tmp_CodTrans = 1, mat.tab_Txtdata,'')) AS Col1,
		mAX(IF (tmp_CodTrans = 2, mat.tab_Txtdata,'')) AS Col2,
		mAX(IF (tmp_CodTrans = 3, mat.tab_Txtdata,'')) AS Col3,
		mAX(IF (tmp_CodTrans = 4, mat.tab_Txtdata,'')) AS Col4,
		mAX(IF (tmp_CodTrans = 5, mat.tab_Txtdata,'')) AS Col5,
		mAX(IF (tmp_CodTrans = 6, mat.tab_Txtdata,'')) AS Col6,
		mAX(IF (tmp_CodTrans = 7, mat.tab_Txtdata,'')) AS Col7
	FROM (
			select
			ttr.tab_Codigo   as tmp_CodTrans  ,
			tco.tab_codigo as tmp_CodCompr,
			tco.tab_Abreviatura as tmp_DesCompr
			from fistablassri ttr,  fistablassri tco
			where ttr.tab_codtabla = 'A' and tco.tab_codtabla = '2'  ) tmp
		LEFT JOIN fistablassri mat
			on mat.tab_codTabla = '99'  AND mat.tab_Codigo = tmp.tmp_CodTrans
			   and mat.tab_CodSecuencial  = tmp.tmp_codCompr
	GROUP BY 1
	ORDER BY 1,2";
$rs = $db->Execute($sSql);
//print_r($db);
$rs->MoveFirst();
if ($rs){
	$aRowData = SmartyArray($rs);
	$Tpl->assign("aRowData", $aRowData);
}
//echo "<br><br>";
//print_r($aRowData);

/*
*


if (fGetParam('selAuxiliar')) {
    $sSql= "
    $rs = $db->execute($sSql);
    $rs->MoveFirst();
    $aNomCols =& SmartyArray($rs);
    $Tpl->assign("aDet", $aDet);
}
$url_CrearNueva="../Co_Files/CoTrCl_mant.php?con_CodCuenta='1101020'&" . "con_CodAuxiliar=" . fGetParam('selAuxiliar', -1);
$Tpl->assign("url_CrearNueva", $url_CrearNueva);
*/
$rs=NULL;
$Tpl->display('CoRtTr_configtrans.tpl');

/****/

?>
