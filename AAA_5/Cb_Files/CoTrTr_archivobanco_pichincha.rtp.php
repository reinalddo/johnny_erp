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

$filename = date('Y').date('m').date('d').".xls";

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");  
header ("Cache-Control: no-cache, must-revalidate");  
header ("Pragma: no-cache");  
header ("Content-type: application/x-msexcel");  
header ("Content-Disposition: attachment; filename=\"" . $filename . "\"" );

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
//$glFlag= fGetParam('pEmpq', false);
//$pQry = fGetParam('pQryCom','');
//
//$anio = fGetParam('s_Anio',date('Y'));
//$mes = fGetParam('s_Mes',date('m'));
//
//if ($anio == '') $anio = date('Y');
//if ($mes == '') $mes = date('m');
//
//$subtitulo = fGetParam('pCond',"Año: ".$anio." - Mes: ".$mes);
//$Tpl->assign("subtitulo",$subtitulo);

$IdBatch = fGetParam('batch',0);
/*para consultar los detalles*/
$sSql = "select iab_ValorTex numcta , det_NumCheque cheque, det_ValCredito valor, com_Receptor beneficiario
		/*,cab.idbatch,com_regnumero regNum, d.det_secuencia secuencia, det_idauxiliar aux, 
		p.per_Apellidos banco ,  com_FecContab fecha, 
		per.per_ruc ruc, com_Concepto concepto, com_TipoComp tipoComp, 
		com_NumComp numComp, origen , observacion, fecRegistro, usuario, operacion, 
		per.per_tipoId tipoIdent, per.per_direccion direccion */
        from concheques_cab cab
        inner join concheques_det det on cab.idbatch=det.idbatch
        join concomprobantes c on c.com_regnumero=det.com_regnum
                join condetalle d on c.com_regnumero=d.det_regnumero
                join conpersonas p on det_idauxiliar=p.per_CodAuxiliar
		join conpersonas per on com_codreceptor=per.per_CodAuxiliar
                join concategorias cat on cat_CodAuxiliar=p.per_CodAuxiliar and cat_Categoria=10
                join genvariables i on det_idauxiliar=iab_ObjetoID and iab_tipo=10 and iab_VariableID=3
	where  tipo=3 and det_idauxiliar=101 and cab.idbatch=".$IdBatch;


$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('CoTrTr_archivobanco_pichincha.tpl')) {
            }
    
            $Tpl->display('CoTrTr_archivobanco_pichincha.tpl');
}
?>