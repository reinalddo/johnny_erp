<?php
/*    Reporte de comprobantes anulados. Formato HTML
 *    @param   string  pAux     Auxiliar
 *    @param   string pNomAux   nombre de auxiliar
 *    @param   date  pInicio     Inico de rango de fechas
 *    @param   date   pFin       Fin de rango de fechas
 *
 *  @author Gina Franco 17/Jun/09
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

if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$auxiliar= fGetParam('pAux', 103);
$nomAux= fGetParam('pNomAux', "prueba");
$inicio = fGetParam('pInicio','2000-12-31');
$fin = fGetParam('pFin','2000-12-31');

$subtitulo = "Desde: ".$inicio." - Hasta: ".$fin;
$Tpl->assign("subtitulo",$subtitulo);


/*para consultar los detalles*/
$sSql = "select per_CodAuxiliar aux, concat(per_Apellidos, ' ', per_Nombres) nomAux,
                com_TipoComp tipo, com_NumComp numComp, com_FecContab fecha, det_ValDebito debito
		, det_ValCredito credito, com_Concepto concepto, det_NumCheque cheque,  
		concat(com_usuario,', ',com_fecDigita) usuario,com_RegNumero regNum
        from concomprobantes c
                inner join condetalle d on c.com_RegNumero=d.det_RegNumero
                inner join conpersonas aux on per_codauxiliar=det_IDAuxiliar
		JOIN concategorias ON  cat_codauxiliar = per_codauxiliar and cat_categoria = 10
        where /*det_IDAuxiliar=auxiliar    and */   
         com_FecContab between '".$inicio."' and '".$fin."'
        and (det_EstLibros=1 and com_Concepto like 'ANULA%')";

/*$sSql .= ($pQry ? " WHERE " . $pQry  : " " );
$sSql .= " ORDER BY det_CodCuenta, det_IDauxiliar, com_FecContab, com_TipoComp, com_NumComp";*/


$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('CoTrCl_anulaciondiferida.tpl')) {
            }
    
            $Tpl->display('CoTrCl_anulaciondiferida.tpl');
}
?>