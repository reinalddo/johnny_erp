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
$db->debug=fGetparam("pAdoDbg",false);
require('Smarty.class.php');
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = 'template';
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

$subtitulo = fGetParam('pCond','');
$Tpl->assign("subtitulo",$subtitulo);

$fecSaldos = fGetParam('com_FecContab_MIN','');
$fecSaldos = ($fecSaldos == "" ? "" : "com_FecContab " . $fecSaldos . "");


/*para consultar los detalles*/
$sSql = "select ' ' AS EMPRESA,
	per_codauxiliar AS CODIGO,
	concat(per_Apellidos, ' ', per_Nombres) as NOMBRE,
	det_NumCheque as 'NUM_FACT',
	ven_fecemision AS 'FECHA',
	ven_fecvencim	 AS 'VENCIMIENTO',
	ven_ValorFact      AS 'VALOR_FACT',
	SUM(det_ValDebito) AS 'ABONADO',
	sum(det_ValCredito - det_ValDebito)  AS 'SALDO'
        ,com_concepto CONCEPTO
    FROM  
            concomprobantes
            JOIN condetalle on det_regnumero = com_regnumero
            LEFT JOIN conpersonas on per_Codauxiliar = det_idauxiliar
            LEFT JOIN v_convencimientos on ven_idauxiliar = det_idauxiliar AND ven_Factura = det_numcheque 
    WHERE  det_codcuenta = '20000002'
    GROUP BY 1,2,3,4,5,6,7
    ORDER BY 3";


$rs = $db->execute($sSql . $slFrom);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('CoTrTr_CuentasPagar.tpl')) {
            }
    
            $Tpl->display('CoTrTr_CuentasPagar.tpl');
}
?>