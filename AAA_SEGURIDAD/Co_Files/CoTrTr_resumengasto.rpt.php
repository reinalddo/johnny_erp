<?php
/*    Reporte de resumen de Gastos
 *    @param   string  pQryCom     //filtro de la consulta sino lo trae consulta la cuenta 6
 *
 */
//$FileName = "CoTrTr_productoresdet.rpt.php";
//include_once("../LibPhp/GenCifras.php");
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

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');

if ("" == $pQry)
   $pQry = " det_codcuenta like '6%' ";


//$subtitulo = fGetParam('pCond',"Año: ".$anio." - Mes: ".$mes);
$Tpl->assign("subtitulo",$subtitulo);


/*para consultar los detalles*/
$sSql = "select year(com_feccontab) ANIO,
        month(com_feccontab) MES,
        a.cue_descripcion TIPO,
        p.cue_descripcion AREA,
        c.cue_descripcion CENTRO,
        par_descripcion as CLASE,
        det_idauxiliar cod,
        concat(per_apellidos, ' ', per_nombres) as RUBRO,
        sum(det_valdebito - det_valcredito) as VALOR
        from concomprobantes 
                join condetalle on det_regnumero = com_regnumero
                left join concuentas c on det_codcuenta = c.cue_codcuenta
                left join concuentas p on c.cue_padre = p.cue_id
                left join concuentas a on p.cue_padre = a.cue_id
                left join conpersonas on per_codauxiliar  = det_idauxiliar
                left join genparametros on par_clave = 'PERSUB' and par_secuencia = per_Subcategoria
        where /*det_codcuenta like '6%'*/ ".$pQry."
        group by 1,2,3,4,5,6,7
        having sum(det_valdebito - det_valcredito) <> 0";

//echo $sSql;

$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
   
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('CoTrTr_resumengasto.tpl')) {
            }
    
            $Tpl->display('CoTrTr_resumengasto.tpl');
}
?>