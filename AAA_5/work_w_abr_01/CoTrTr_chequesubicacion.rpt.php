<?php
/*    Reporte de cierre de caja. Formato HTML
 *    @param   integer  pFecIni     Fecha de Inicio para consulta
 *    @param   integer  pFecFin     Fecha Final de rango
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
    case 1: $subtitulo = "CUSTODIA";
            $filtro = " having count(d.com_regnum) = 1";
        break;
    case 2: $subtitulo = "OTROS DEPARTAMENTOS";
            $filtro = " having count(d.com_regnum) > 1";
        break;
    case 3: $subtitulo = "TODOS";
            $filtro = " ";
        break;
}

$Tpl->assign("subtitulo",$subtitulo);

$Tpl->assign("opc",$opc);


//consulto cheques
$sSql = "select '' empresa, com_regnumero regNum, d.det_secuencia secuencia, per_Apellidos banco, det_NumCheque cheque, 
                    com_FecContab fecha, det_ValCredito valor, com_Receptor beneficiario, com_Concepto concepto, 
                    com_TipoComp tipoComp, com_NumComp numComp, origen, observacion
        from concheques_cab cab
        inner join concheques_det det on cab.idbatch=det.idbatch
        join concomprobantes c on c.com_regnumero=det.com_regnum
                join condetalle d on c.com_regnumero=d.det_regnumero
                join conpersonas p on det_idauxiliar=per_CodAuxiliar
                join concategorias cat on cat_CodAuxiliar=per_CodAuxiliar and cat_Categoria=10
        where destino = '".$_SESSION['g_user']."' and confirmado=1 and tipo=2
        and cab.idbatch in 
        (
        select  max(c.idbatch)
        from concheques_cab c 
        inner join concheques_det d on c.idbatch=d.idbatch /*and (archivado is null or archivado<>1)*/
        where destino = '".$_SESSION['g_user']."' and confirmado=1 and tipo=2
        group by c.idbatch
        order by fecconfir desc
        )
	and
	det.com_regnum in 
	(
	select  d.com_regnum
        from concheques_cab c 
        inner join concheques_det d on c.idbatch=d.idbatch /*and (archivado is null or archivado<>1)*/
        where tipo=1 and operacion=2
	/*group by d.com_regnum
        ".$filtro."*/
	/*having count(d.com_regnum) > 1*/
	)";

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
    if (!$Tpl->is_cached('CoTrTr_chequesubicacion.tpl')) {
            }
//    
            $Tpl->display('CoTrTr_chequesubicacion.tpl');
}
?>