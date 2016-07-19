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

if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');

$subtitulo = fGetParam('pCond','');
$Tpl->assign("subtitulo",$subtitulo);

$fecSaldos = fGetParam('com_FecContab_MIN','');
$fecSaldos = ($fecSaldos == "" ? "" : "com_FecContab " . $fecSaldos . "");

/*para consultar los saldos de la cuentas*/
if ($fecSaldos != ""){
    $sSql = "SELECT
        concat(det_CodCuenta, '   ') AS 'det_CodCuenta',
        IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'det_CodAuxiliar',
        sum(det_ValDebito - det_ValCredito)  AS 'txt_Saldo'
            
     FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)";
    $sSql .= /*($pQry ? " WHERE " . $pQry . ($fecSaldos=="" ? "" : " and " .$fecSaldos) : )*/
                    ($fecSaldos=="" ? "" : " where ".$fecSaldos) ;
     /*where com_FecContab < '2009.04.01' and det_IdAuxiliar in ('101','102','103')*/
    $sSql .= " group by det_codCuenta,det_IdAuxiliar
    ORDER BY det_CodCuenta, det_IDauxiliar, com_FecContab, com_TipoComp, com_NumComp";
    
    
    
    $rs = $db->execute($sSql);
    $rs->MoveFirst();
    while ($r = $rs->fetchRow()){
       $agSaldos[$r['det_CodCuenta']][$r['det_CodAuxiliar']] = $r['txt_Saldo'];
       
    }
}
/*print_r($agSaldos);*/

$Tpl->assign("agSaldos", $agSaldos);
$acumula = 0;
$Tpl->assign("acumula",$acumula);


/*para consultar los detalles*/
$sSql = "SELECT
   com_RegNumero AS 'REG',
    det_secuencia AS 'SEC',
    com_TipoComp AS 'TIP',
    concat(com_TipoComp, '-', com_NumComp) AS 'txt_Compr',
    com_FecTrans AS 'FTR',
    com_FecContab AS 'com_FecContab',
    com_Emisor AS 'CEM',
    com_CodReceptor AS 'CRE',
    com_Receptor AS 'REC',
    left(com_Concepto,25) AS 'CON',
    concat(det_CodCuenta, '   ') AS 'det_CodCuenta',
    '----' AS 'ABC',
    IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'det_CodAuxiliar',
    cue_Descripcion AS 'txt_NombCuenta',
    concat(IF(det_idauxiliar <> 0 ,
		concat(IFNULL( concat(act_descripcion, ' ', ifnull(act_descripcion1,' ')),
		   concat(p.per_Apellidos, ' ', ifnull(p.per_Nombres, ' ')) )), '') )   AS 'txt_NombAuxiliar',
    det_Glosa AS 'com_Concepto',
    det_NumCheque as 'com_Cheque',
    det_ValDebito  AS 'det_ValorDeb',
    det_ValCredito AS 'det_ValorCre',
    det_ValDebito - det_ValCredito  AS 'txt_Saldo'
FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)
      LEFT JOIN conpersonas p ON (p.per_CodAuxiliar = det_IdAuxiliar)
    LEFT JOIN conactivos  ON (act_CodAuxiliar = det_IdAuxiliar)
    LEFT JOIN concuentas  ON (cue_codcuenta = det_codcuenta)";
/*where com_FecContab between '2009.04.01' and '2009.04.01' and det_IdAuxiliar in ('101','102','103')*/
$sSql .= ($pQry ? " WHERE " . $pQry  : " " );
$sSql .= " ORDER BY det_CodCuenta, det_IDauxiliar, com_FecContab, com_TipoComp, com_NumComp";


$rs = $db->execute($sSql . $slFrom);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('CoTrTr_movimientos.tpl')) {
            }
    
            $Tpl->display('CoTrTr_movimientos.tpl');
}
?>