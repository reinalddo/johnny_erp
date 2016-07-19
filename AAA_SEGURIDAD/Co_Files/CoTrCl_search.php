<?php
require '../LibPhp/Smarty_AAA.php';
//include("../LibPhp/LibInc.php");
include_once("General.inc.php");
define("RelativePath", "..");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");

error_reporting(E_ALL);
$Tpl = new Smarty_AAA;
//$Tpl->debugging=true;
//set_time_limit (0) ;
$sSql= "SELECT per_CodAuxiliar, concat(per_Apellidos, ' ', per_Nombres) as txt_Nombre " .
            "FROM conpersonas JOIN concategorias ON  cat_codauxiliar = per_codauxiliar
             WHERE cat_categoria = 10  ";
$rs = $db->execute($sSql);
$rs->MoveFirst();
$aAux = SmartyArray($rs);
$Tpl->assign("aAux", $aAux);
$Tpl->assign("First_URL", '');
$Tpl->assign("Next_URL", '');
$Tpl->assign("Prev_URL", '');
$Tpl->assign("Last_URL", '');
$Tpl->assign("Page_Number", '1');
$Tpl->assign("Total_Pages", '1');
if (fGetParam('selAuxiliar')) {
    $sSql= "SELECT  concat('../Co_Files/CoTrCl_mant.php?con_CodCuenta=',
                	con_CodCuenta, '&con_CodAuxiliar=', con_CodAuxiliar, '&con_IdRegistro=',
                con_IdRegistro)as txt_Url,
                con_FecCorte,
                con_DebIncluidos, con_CreIncluidos,
                concat(ifnull(con_Ususario, ''), '-', con_FecRegistro) as txt_Digitado
                from conconciliacion
                where con_CodCuenta = '100100' and con_CodAuxiliar = " . fGetParam('selAuxiliar', -1) .
                " order by 2 desc";

    echo "<br><br>" . $sSql;
    echo "<br><br> DAT" ;
    $db->debug = fGetParam("pAdoDbg", 0);
    $arr=$db->GetArray($sSql);
    print_r($arr);
    echo "<br><br>" ;

    $rs = $db->execute($sSql);
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("aDet", $aDet);
    $Tpl->debug = fGetParam("pAppDbg", 0);
}
$url_CrearNueva="../Co_Files/CoTrCl_mant.php?con_CodCuenta='100100'&" . "con_CodAuxiliar=" . fGetParam('selAuxiliar', -1);
$Tpl->assign("url_CrearNueva", $url_CrearNueva);
$rs=NULL;
$Tpl->display('CoTrCl_search.tpl');
/****/

?>
