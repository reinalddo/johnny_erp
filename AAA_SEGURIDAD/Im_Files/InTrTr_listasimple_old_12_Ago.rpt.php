<?php
/*    Reporte de lista simple. Formato HTML
 *    @param   integer  pFecIni     Fecha de Inicio para consulta
 *    @param   integer  pFecFin     Fecha Final de rango
 *
 */
ini_set("memory_limit", "64M");
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
if (1 == $_SESSION["atr"]["InTrTr"]["CON"]){
   include("../LibPhp/excelOut.php");
}

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
//$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');



//$sSql = "SELECT per_fecInicial, per_FecFinal FROM conperiodos WHERE per_aplicacion = 'IN' AND per_numPeriodo = 10";
//$rs = $db->execute($sSql);
//$rs->MoveFirst();
//while ($r = $rs->fetchRow()){
//
//    $fecInic = $r['per_fecInicial'];
//    $fecFin =  $r['per_FecFinal'];
//}

$subtitulo = fGetParam('pCond','');
$Tpl->assign("subtitulo",$subtitulo);

$sSql = "SELECT com_tipocomp AS TIPO, com_numcomp AS COMPR, det_secuencia AS SECUE, com_feccontab AS FECHA, com_refoperat AS REFOP, 
    com_emisor AS CODEM, concat(b.per_Apellidos, ' ', b.per_nombres) as BODEG, com_codreceptor AS CODRE, 
    concat(p.per_Apellidos, ' ', p.per_nombres) as RECEP, det_coditem AS CODIT, 
    left(concat(act_descripcion, ' ', act_descripcion1),25) as ITEM, det_candespachada AS CANTI, det_cantequivale AS CANTE, 
    uni_abreviatura AS UNIDA, det_costotal AS COSTO, det_valtotal AS VALOR 
    FROM genclasetran JOIN concomprobantes ON cla_aplicacion = 'IN' AND com_tipoComp = cla_tipoComp 
            LEFT JOIN conpersonas b ON b.per_codauxiliar = com_emisor 
            LEFT JOIN conpersonas p ON p.per_codauxiliar = com_codreceptor 
            LEFT JOIN invdetalle ON det_regnumero = com_regnumero 
            LEFT JOIN conactivos ON act_codauxiliar = det_coditem 
            LEFT JOIN genunmedida ON uni_CodUnidad = act_unimedida 
    WHERE com_tipocomp IN ('IB', 'EP', 'DV', 'TI', 'TE', 'XO', 'XD', 'DK', 'CO') 
    ".($pQry != '' ? ' AND '.$pQry : '')."
    /*com_Emisor=302 AND com_TipoComp='TI' */
    ORDER BY com_emisor, com_numcomp, com_tipocomp
    ";
        
//echo $sSql;
$rs = $db->execute($sSql);
//$rs->MoveFirst();

//}
//print_r($agSaldos);

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
    if (!$Tpl->is_cached('InTrTr_listasimple.tpl')) {
            }
//    
            $Tpl->display('InTrTr_listasimple.tpl');
}
?>