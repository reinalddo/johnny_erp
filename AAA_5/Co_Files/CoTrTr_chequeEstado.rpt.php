<?php
/*    Reporte de cierre de caja. Formato HTML
 *    @param   integer  pFecIni     Fecha de Inicio para consulta
 *    @param   integer  pFecFin     Fecha Final de rango
 *    @rev  esl   13/10/2010  se cambio fecha de registro por fecha contable para la consulta de la informacion
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
        $this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}

//if (fGetparam("pExcel",false)){
//   header("Content-Type:  application/vnd.ms-excel");
//   header("Expires: 0");
//   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//}
include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');


$opc = fGetParam('pOpc',1);
//$fecFin = fGetParam('pFecFin',Date);

//setlocale(LC_TIME, 'es_ES');
//$ini = strftime('%d / %b / %Y',strtotime($fecIni));

switch($opc){
    case 1: $subtitulo = "EMITIDOS";
            $filtro = " having count(d.com_regnum) = 1";
        break;
    case 2: $subtitulo = "CONFIRMADOS";
            $filtro = " having count(d.com_regnum) = 2";
        break;
    case 3: $subtitulo = "PAGADOS";
            $filtro = " ";
            $filtro2 = " and estOperacion=99";
        break;
    case 4: $subtitulo = "RECONFIRMADOS";
            $filtro = " having count(d.com_regnum) > 2";
        break;
    case 5: $subtitulo = "ARCHIVADOS";
            $filtro = " ";
            $filtro2 = " and archivado=1";
        break;
    case 6: $subtitulo = "TODOS";
            $filtro = " ";
            
        break;
}
$fecIni = fGetParam('fIni',Date);
$fecFin = fGetParam('fFin',Date);
$subtitulo =  "<br/>Desde: ".$fecIni." - Hasta: ".$fecFin;
$Tpl->assign("subtitulo",$subtitulo);

$Tpl->assign("opc",$opc);


//consulto cheques
//if (1 == $opc || 2 == $opc || 4 == $opc){
    $sSql = "select '' empresa, com_regnumero regNum, d.det_secuencia secuencia, per_Apellidos banco, det_NumCheque cheque, 
                    com_FecContab fecha, det_ValCredito valor, com_Receptor beneficiario, com_Concepto concepto, 
                    com_TipoComp tipoComp, com_NumComp numComp, usuario origen, observacion, fecConfir, cab.fecha fecEmi
        from concheques_cab cab
        inner join concheques_det det on cab.idbatch=det.idbatch
        join concomprobantes c on c.com_regnumero=det.com_regnum
                join condetalle d on c.com_regnumero=d.det_regnumero
                join conpersonas p on det_idauxiliar=per_CodAuxiliar
                join concategorias cat on cat_CodAuxiliar=per_CodAuxiliar and cat_Categoria=10
                JOIN conChequeUltMov ult ON ult.com_regnum = det.com_regNum AND ult.ultBatch = det.IdBatch
        where tipo=1 and operacion = ".$opc."  and det_ValCredito >= 0 and com_FecContab between '".$fecIni."' and  '".$fecFin."'";
        
        //where tipo=1 and operacion = ".$opc."  and det_ValCredito >= 0 and fecRegistro between '".$fecIni."' and  '".$fecFin."'";
        
//}else{
//    $sSql = "select '' empresa, com_regnumero regNum, d.det_secuencia secuencia, per_Apellidos banco, det_NumCheque cheque, 
//                    com_FecContab fecha, det_ValCredito valor, com_Receptor beneficiario, com_Concepto concepto, 
//                    com_TipoComp tipoComp, com_NumComp numComp, usuario origen, observacion, fecConfir, cab.fecha fecEmi
//        from concheques_cab cab
//        inner join concheques_det det on cab.idbatch=det.idbatch
//        join concomprobantes c on c.com_regnumero=det.com_regnum
//                join condetalle d on c.com_regnumero=d.det_regnumero
//                join conpersonas p on det_idauxiliar=per_CodAuxiliar
//                join concategorias cat on cat_CodAuxiliar=per_CodAuxiliar and cat_Categoria=10
//        where /*destino = '".$_SESSION['g_user']."' and confirmado=1 and */tipo=1 ".$filtro2."
//        and cab.idbatch in 
//        (
//        select  max(c.idbatch)
//        from concheques_cab c 
//        inner join concheques_det d on c.idbatch=d.idbatch
//        where /*destino = 'gina' and confirmado=1 and */tipo=1  ".$filtro2."
//        group by com_regnum order by c.idbatch desc
//        ) /*and
//	det.com_regnum in 
//	(
//	select  d.com_regnum
//        from concheques_cab c 
//        inner join concheques_det d on c.idbatch=d.idbatch
//        where tipo=1
//	group by d.com_regnum
//        ".$filtro."*/
//	/*having count(d.com_regnum) > 1*/
//	/*)*/";
//}
//echo $sSql;
$rs = $db->execute($sSql);
//$rs->MoveFirst();

//}
//print_r($agSaldos);



//echo "<br/>".count($agSaldos);
//echo "<br/>".$agSaldos[102]['dep'];

//$Tpl->assign("agSaldos", $agSaldos);
//$acumula = 0;
//$Tpl->assign("acumula",$acumula);


//echo basename($_SERVER[ "PHP_SELF"],".php");
$filename = basename($_SERVER[ "PHP_SELF"],".php");
$Tpl->assign("agArchivo", $filename);

//$rs = $db->execute($sSql . $slFrom);
if($rs->EOF){//0 == count($agSaldos)){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
//    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
//    
    if (!$Tpl->is_cached('CoTrTr_chequeEstado.tpl')) {
            }
//    
            $Tpl->display('CoTrTr_chequeEstado.tpl');
}
?>