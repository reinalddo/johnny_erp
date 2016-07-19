<?php
/**    Reporte que muestra los datos de la transacción,
 *     si ésta ya ha sido aprobada muestra tambien los datos de la contabilizacion.
 */
ob_start("ob_gzhandler");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");
$db->debug=fGetparam("pAdoDbg",false);
require('Smarty.class.php');
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
	$this->debugging = false;
   }
}

include("../LibPhp/excelOut.php");
header('Content-type: xhtml+xml; charset=utf-8');
$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);

// parametro para el query general
$pQry = fGetParam('pQryCom','');

// Parametros individuales para el query
$pree_Id = fGetParam('ree_Id',0);

$subtitulo="TRANSACCION";
$Tpl->assign("subtitulo",$subtitulo);

/*para consultar los detalles*/
$sSql = "SELECT   tra.ree_Id
	,tra.ree_Emisor
	,CONCAT(per.per_Apellidos,' ',per.per_Nombres) AS tx_Emisor
	,tra.ree_Concepto
	,tra.ree_Fecha AS fecTra
	,tra.ree_Valor AS valTotal
	,tra.ree_Estado
	,est.par_Descripcion AS tx_Estado
	,tra.ree_Usuario
	,tra.ree_RefOperat AS semanaTra
	,tra.ree_UsuAprueba
	,tra.ree_FecAprueba
	,tra.ree_TipoComp
	,tra.ree_NumComp
	,det.red_Sec
	,det.red_MotivoCC
	,cue_Descripcion
	,det.red_Aux
	,CONCAT(aux.per_Apellidos,' ',aux.per_Nombres) AS tx_Aux
	,det.red_Valor
FROM conReembolso tra
INNER JOIN conpersonas per ON per.per_CodAuxiliar = tra.ree_Emisor
LEFT JOIN conReembolsoDetal det ON det.red_Id = tra.ree_Id
LEFT JOIN concuentas ON Cue_CodCuenta = det.red_MotivoCC
LEFT JOIN conpersonas aux ON aux.per_CodAuxiliar = det.red_Aux
JOIN genparametros est ON est.par_clave = 'CADEST' AND est.PAR_VALOR4 = 'REEMB' AND est.par_Valor1 = tra.ree_Estado
WHERE tra.ree_Id = ". $pree_Id."
order by tra.ree_Id, det.red_Sec";
$rs = $db->execute($sSql . $slFrom);


$sSql2 ="SELECT   ree_Id
		  ,ree_TipoComp
		  ,ree_NumComp
		  ,ree_Usuario
		  ,ree_UsuAprueba
		  ,ree_FecAprueba
		  ,com_TipoComp
		  ,com_NumComp
		  ,com_Valor
		  ,det_IdAuxiliar
		  ,CONCAT(per_Apellidos,' ',per_Nombres) AS tx_Auxiliar
		  ,det_CodCuenta
		  ,cue_Descripcion AS tx_Cuenta
		  ,det_ValDebito
		  ,det_ValCredito
		  ,det_NumCheque
	  FROM conReembolso
	  LEFT JOIN concomprobantes ON com_tipoComp = ree_TipoComp AND com_NumComp = ree_NumComp 
	  LEFT JOIN condetalle ON det_tipoComp = com_tipoComp AND det_numComp = com_NumComp
	  LEFT JOIN conpersonas ON per_CodAuxiliar = det_IdAuxiliar
	  LEFT JOIN concuentas ON Cue_CodCuenta = det_CodCuenta
	  WHERE ree_Id =". $pree_Id." ";
$rs2 = $db->execute($sSql2 . $slFrom);

if(!$rs2->EOF){
//    fErrorPage('','NO SE CONSULTARON LOS DATOS CONTABLES', true,false);
//}else{
    $rs2->MoveFirst();
    $aDet2 =& SmartyArray($rs2);
    $Tpl->assign("agContab", $aDet2);
}

if($rs->EOF){
    fErrorPage('','NO SE CONSULTARON LOS DATOS DE LA TRANSACCION', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
    $Tpl->assign("slPiePag", $slPiePag);
    if (!$Tpl->is_cached('CoAdFi_ReembolsoRep.tpl')) {
            }
            $Tpl->display('CoAdFi_ReembolsoRep.tpl');
}
?>