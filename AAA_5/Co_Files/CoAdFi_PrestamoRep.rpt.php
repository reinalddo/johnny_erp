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
$ptra_Id = fGetParam('tra_Id',0);

$subtitulo="TRANSACCION";
$Tpl->assign("subtitulo",$subtitulo);

/*para consultar los detalles*/
$sSql = "select   tra.tra_Id
	,tra.tra_Receptor
	,concat(per_Apellidos,' ',per_Nombres) as tx_Receptor
	,tra.tra_Concepto
	,tra.tra_Motivo
	,mot.par_Descripcion as tx_Motivo
	,tra.tra_Fecha as fecTra
	,tra.tra_Cuotas
	,tra.tra_Valor as valTotal
	,tra.tra_Estado
	,est.par_Descripcion as tx_Estado
	,tra.tra_Usuario
	,tra.tra_Semana as semanaTra
	,tra.tra_UsuAprueba
	,tra.tra_FecAprueba
	,tra.tra_TipoComp
	,tra.tra_NumComp
	,det.tra_Cuota
	,det.tra_Valor as valCuota
	,det.tra_Semana as semanaCuota
	,det.tra_Fecha as fecDet
	,det.tra_Fecha_vence
from genTransac tra
INNER JOIN conpersonas per on per.per_CodAuxiliar = tra.tra_Receptor
LEFT JOIN genTransacDetal det on det.tra_Id = tra.tra_Id
JOIN genparametros mot on mot.par_clave = 'CLIBRO' AND mot.PAR_VALOR4 = 'COMEX' AND mot.par_Secuencia = tra.tra_Motivo
JOIN genparametros est on est.par_clave = 'CADEST' AND est.PAR_VALOR4 = 'COMEX' AND est.par_Valor1 = tra.tra_Estado
where tra.tra_Id = ". $ptra_Id."
order by tra.tra_Id, det.tra_Cuota, tra.tra_Fecha";
$rs = $db->execute($sSql . $slFrom);


$sSql2 ="select   tra_Id
		  ,tra_TipoComp
		  ,tra_NumComp
		  ,tra_Usuario
		  ,tra_UsuAprueba
		  ,tra_FecAprueba
		  ,com_TipoComp
		  ,com_NumComp
		  ,com_Valor
		  ,det_IdAuxiliar
		  ,concat(per_Apellidos,' ',per_Nombres) as tx_Auxiliar
		  ,det_CodCuenta
		  ,cue_Descripcion as tx_Cuenta
		  ,det_ValDebito
		  ,det_ValCredito
		  ,det_NumCheque
	  from genTransac
	  LEFT JOIN concomprobantes on com_tipoComp = tra_TipoComp and com_NumComp = tra_NumComp 
	  LEFT JOIN condetalle on det_tipoComp = com_tipoComp and det_numComp = com_NumComp
	  LEFT JOIN conpersonas on per_CodAuxiliar = det_IdAuxiliar
	  LEFT JOIN concuentas on Cue_CodCuenta = det_CodCuenta
	  where tra_Id =". $ptra_Id." ";
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
    if (!$Tpl->is_cached('CoAdFi_PrestamoRep.tpl')) {
            }
            $Tpl->display('CoAdFi_PrestamoRep.tpl');
}
?>