<?php
/**
* Codigo para Aplicar la consolidacion de transacciones
* Recibe como parametros :
* 	- Empresas a consolidar,
* 	- Periodo (semana)
* 	- Tipo de consolidacion: Incremental o total,
* @package      AAA
* @subpackage   Operaciones
* @Author       Fausto Astudillo
* @Date         20/04/09
* 				
*/
include_once("General.inc.php");
define("RelativePath", "..");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");

$agPar = fGetAllParams("ALL");
if ($agPar['pAdoDbg']) print_r($agPar);
$agEmpr = explode(",", $agPar['cns_Empresa']);
switch ($agPar['cns_Proc']){
    case "tar":
        $slProc = "spLiqConsolidaTar";
        break;
    case "cxc":
        $slProc = "spLiqConsolidaCon";
        break;
    default:
        $slProc = "";
}
if(!isset($agPar['cns_Tipo'])) $agPar['cns_Tipo'] = "R";
foreach($agEmpr as $k => $slEmpresa) {
//echo $slEmpresa;
    $slSql="call ". $slProc . "( '" . $slEmpresa . "', " . $agPar['cns_Periodo'] . ",'" . $agPar['cns_Tipo'] . "', " . $agPar['pAdoDbg'] . " )";
    if ($agPar['pAdoDbg']) { echo "<br>" ; echo $slSql; echo "<br>"; };
    $olRes = $db->Execute($slSql);
    if ($agPar['pAdoDbg']) { echo "<br>" ; print_r($olRes); echo "<br>"; };
}
?>