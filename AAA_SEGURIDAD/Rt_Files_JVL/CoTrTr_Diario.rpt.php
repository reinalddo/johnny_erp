<?php
/*    Reporte de tarjas, detalle General por Puerto, Vapor, Marca. Formato HTML
 *    @param   integer  pSem     Numero de semana a procesar
 *    @param   integer  pEmb     Numero de Embarque
 *    @param   string   PMarca   Marca
 *
 */
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");
ini_set("memory_limit", "64M");
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
/*
if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}*/

include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');

$anio = fGetParam('s_Anio',date('Y'));
$mes = fGetParam('s_Mes',date('m'));

if ($anio == '') $anio = date('Y');
if ($mes == '') $mes = date('m');

$subtitulo = fGetParam('pCond',"Año: ".$anio." - Mes: ".$mes);
$Tpl->assign("subtitulo",$subtitulo);


/*para consultar los detalles*/
$sSql = "SELECT
    com_TipoComp AS 'tipo',
    com_NumComp AS 'numero',
    com_FecContab AS 'fecha',
    cue_Descripcion nombre,
    concat(left(com_Concepto,100), ' / ', det_glosa) AS 'glosa',
    det_ValDebito  AS 'debito',
    det_ValCredito AS 'credito'
    ,det_secuencia
    ,left(concat(IF(det_idauxiliar <> 0 ,
		concat(IFNULL( concat(act_descripcion, ' ', ifnull(act_descripcion1,' ')),
		   concat(p.per_Apellidos, ' ', ifnull(p.per_Nombres, ' ')) )), '') ),25) nom_aux
    ,det_NumCheque cheque
    ,det_codcuenta codcuenta
    ,det_IdAuxiliar codauxiliar
FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)
      LEFT JOIN conpersonas p ON (p.per_CodAuxiliar = det_IdAuxiliar)
    LEFT JOIN conactivos  ON (act_CodAuxiliar = det_IdAuxiliar)
    LEFT JOIN concuentas  ON (cue_codcuenta = det_codcuenta)
where YEAR(com_FecContab) = ".$anio." and MONTH(com_FecContab) = ".$mes."
order by com_FecContab,com_TipoComp,com_NumComp,det_secuencia";

/*$sSql .= ($pQry ? " WHERE " . $pQry  : " " );
$sSql .= " ORDER BY det_CodCuenta, det_IDauxiliar, com_FecContab, com_TipoComp, com_NumComp";*/


$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('CoTrTr_Diario.tpl')) {
            }
    
            $Tpl->display('CoTrTr_Diario.tpl');
}
?>