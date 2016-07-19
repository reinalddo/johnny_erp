<?php
/*    Reporte de tarjas, detalle General por Puerto, Vapor, Marca. Formato HTML
 *    @param   integer  pSem     Numero de semana a procesar
 *    @param   integer  pEmb     Numero de Embarque
 *    @param   string   PMarca   Marca
 *
 */
//$FileName = "CoTrTr_productoresdet.rpt.php";
include_once("../LibPhp/GenCifras.php");
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

include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');
$numero = fGetParam('regNumero','');


//$anio = fGetParam('s_Anio',date('Y'));
//$mes = fGetParam('s_Mes',date('m'));

//if ($anio == '') $anio = date('Y');
//if ($mes == '') $mes = date('m');

//$subtitulo = fGetParam('pCond',"Año: ".$anio." - Mes: ".$mes);
$Tpl->assign("subtitulo",$subtitulo);


/*para consultar los detalles*/
$sSql = "select com_NumComp,com_FecTrans, com_Receptor, per_RUC ruc, com_Concepto, com_FecContab, 
        det_Secuencia, det_CodItem, act_descripcion descripcion, det_UniMedida, uni_Abreviatura, det_CanDespachada, 
        det_CosUnitario, det_CosTotal, com_Usuario
        from concomprobantes c 
                inner join invdetalle d on c.com_RegNumero=d.det_RegNumero
                inner join conactivos a on d.det_CodItem=act_CodAuxiliar
                inner join genunmedida m on d.det_UniMedida=uni_CodUnidad
                inner join conpersonas p on c.com_CodReceptor=per_CodAuxiliar
        where /*com_NumComp = 904001*/
        ".$pQry."
        order by com_FecDigita desc,det_Secuencia";

//echo $sSql;

$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
    
    while ($r = $rs->fetchRow()){
       $total += $r['det_CosTotal'];
       
    }
    //echo $total."------";
    $letras = num2letras($total, false, 2, 2, " Dolares", " ctvs. ");//num2letras($total,false,1,2);
    $Tpl->assign("letras", $letras);
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('InTrTr_ordencompra.tpl')) {
            }
    
            $Tpl->display('InTrTr_ordencompra.tpl');
}
?>