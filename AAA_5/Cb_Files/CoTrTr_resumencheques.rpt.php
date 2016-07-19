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


$fecIni = fGetParam('pFecIni',Date);
$fecFin = fGetParam('pFecFin',Date);

//setlocale(LC_TIME, 'es_ES');
$ini = strftime('%d / %b / %Y',strtotime($fecIni));
$fin = strftime('%d / %b / %Y',strtotime($fecFin));
$subtitulo = $fecIni <> $fecFin ? "Movimientos Desde: ".$ini." Hasta: ".$fin : "Movimientos al ".$ini;//$fecIni;//fGetParam('pCond','');
$Tpl->assign("subtitulo",$subtitulo);


//consulta movimiento de cuenta de caja
$sSql = "select p.per_CodAuxiliar cod
            , concat(p.per_Apellidos, ' ', p.per_Nombres) as txt, ban_NumCta ctacte
            , det_NumCheque cheque	
            ,/*left(concat(IFNULL(com_codreceptor, concat(left(per.per_Apellidos,20), ' ',
            left(per.per_Nombres,12))), ' ', det_glosa),35)*/
            case when com_CodReceptor is null then det_Glosa
            else concat('(',com_CodReceptor,') ',left(per.per_Apellidos,20), ' ', left(per.per_Nombres,12)) end
            beneficiario
            ,com_FecContab fecha, det_ValDebito, det_ValCredito,'Inicio' estatus
            , case when com_EstOperacion = 9 then 'Anulado' else '' end detalle
        from concomprobantes
        JOIN condetalle on (det_RegNumero = com_RegNumero)
        JOIN conpersonas p ON (p.per_CodAuxiliar = det_idauxiliar)
        join concategorias c on cat_codauxiliar = per_codauxiliar  and cat_Categoria in (10)
        join v_baninfo b on ban_Id = per_codauxiliar
        left join conpersonas per on (per.per_CodAuxiliar = com_codreceptor) 
        where com_FecContab between '".$fecIni."' and '".$fecFin."'
        and det_NumCheque = (
                        select min(det_NumCheque) chFin
                        from concomprobantes
                        JOIN condetalle on (det_RegNumero = com_RegNumero)
                        JOIN conpersonas p3 ON (p3.per_CodAuxiliar = det_idauxiliar)
                        inner join concategorias c on cat_codauxiliar = per_codauxiliar  and cat_Categoria in (10)
                        where com_FecContab between '".$fecIni."' and '".$fecFin."'
                        and p3.per_CodAuxiliar=p.per_CodAuxiliar
                        and com_tipocomp in (select cla_TipoTransacc from 09_base.invprocesos
                           where pro_codproceso=22 and pro_Orden=3))
        group by 1,2
        union
        select p.per_CodAuxiliar cod
            , concat(p.per_Apellidos, ' ', p.per_Nombres) as txt, ban_NumCta ctacte
            , det_NumCheque chFin
            ,/*left(concat(IFNULL(com_codreceptor, concat(left(per.per_Apellidos,20), ' ',
            left(per.per_Nombres,12))), ' ', det_glosa),35)*/
            case when com_CodReceptor is null then det_Glosa
            else concat('(',com_CodReceptor,') ',left(per.per_Apellidos,20), ' ', left(per.per_Nombres,12)) end
            beneficiario
            ,com_FecContab  fecha, det_ValDebito, det_ValCredito,'Final' estatus
            , case when com_EstOperacion = 9 then 'Anulado' else '' end detalle
        from concomprobantes
        JOIN condetalle on (det_RegNumero = com_RegNumero)
        JOIN conpersonas p ON (p.per_CodAuxiliar = det_idauxiliar)
        join concategorias c on cat_codauxiliar = per_codauxiliar  and cat_Categoria in (10)
        join v_baninfo b on ban_Id = per_codauxiliar
        left join conpersonas per on (per.per_CodAuxiliar = com_codreceptor) 
        where com_FecContab between '".$fecIni."' and '".$fecFin."'
        and det_NumCheque = (
                        select max(det_NumCheque) chFin
                        from concomprobantes
                        JOIN condetalle on (det_RegNumero = com_RegNumero)
                        JOIN conpersonas p3 ON (p3.per_CodAuxiliar = det_idauxiliar)
                        inner join concategorias c on cat_codauxiliar = per_codauxiliar  and cat_Categoria in (10)
                        where com_FecContab between '".$fecIni."' and '".$fecFin."'
                        and p3.per_CodAuxiliar=p.per_CodAuxiliar
                        and com_tipocomp in (select cla_TipoTransacc from 09_base.invprocesos
                           where pro_codproceso=22 and pro_Orden=3))
        group by 1,2,3
        order by txt,cheque";
$rs = $db->execute($sSql);

$filename = basename($_SERVER[ "PHP_SELF"],".php");
$Tpl->assign("agArchivo", $filename);

//}
//$rs = $db->execute($sSql . $slFrom);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
//    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
//    
    if (!$Tpl->is_cached('CoTrTr_resumencheques.tpl')) {
            }
//    
            $Tpl->display('CoTrTr_resumencheques.tpl');
}
?>