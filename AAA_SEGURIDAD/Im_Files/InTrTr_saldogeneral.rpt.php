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
        $this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}
//echo print_r($_SESSION)."-------";
if (1 == $_SESSION["atr"]["InTrTr"]["CON"]){
   include("../LibPhp/excelOut.php");
}



//if (fGetparam("pExcel",false)){
//   header("Content-Type:  application/vnd.ms-excel");
//   header("Expires: 0");
//   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//}


$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
//$glFlag= fGetParam('pEmpq', false);
$pPeriodo = fGetParam('pPerio',0);



$sSql = "SELECT per_fecInicial, per_FecFinal FROM conperiodos WHERE per_aplicacion = 'IN' AND per_numPeriodo = ".$pPeriodo;
$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r = $rs->fetchRow()){

    $fecInic = $r['per_fecInicial'];
    $fecFin =  $r['per_FecFinal'];
}

$subtitulo = "PERIODO DE ".$fecInic." A ".$fecFin;
$Tpl->assign("subtitulo",$subtitulo);

$sSql = "SELECT par_descripcion desGru,act_grupo AS 'GRU', det_coditem AS 'ITE', concat(act_descripcion, ' ', act_descripcion1) as 'DES' , 
    uni_abreviatura as 'UNI', sum(if ( com_feccontab < '".$fecInic."' , det_cantequivale * pro_signo, 0)) as SAN, 
    sum(if ( com_feccontab < '".$fecInic."' , det_cosTotal * pro_signo, 0)) as VAN, 
    sum(if ( com_feccontab >= '".$fecInic."' AND pro_signo = 1, det_cantequivale , 0)) as CIN, 
    sum(if ( com_feccontab >= '".$fecInic."' AND pro_signo = 1, det_cosTotal , 0)) as VIN, 
    sum(if ( com_feccontab >= '".$fecInic."' AND pro_signo = -1 , det_cantequivale , 0)) as CEG, 
    sum(if ( com_feccontab >= '".$fecInic."' AND pro_signo = -1 , det_cosTotal , 0)) as VEG, 
    sum(det_cantequivale * pro_signo) as SAC, sum(det_cosTotal * pro_signo) as VAC, 
    sum(det_cosTotal * pro_signo) / sum(det_cantequivale * pro_signo) as PUN 
    FROM invprocesos JOIN concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc 
    JOIN invdetalle ON det_regnumero = com_regnumero 
    JOIN conactivos ON act_codauxiliar = det_coditem 
    JOIN genunmedida ON uni_codunidad= act_unimedida 
    join genparametros on par_ClaVE = 'actgru' and PAR_SECUENCIA=act_grupo
    WHERE com_feccontab >= '2004-12-31' AND com_feccontab <= '".$fecFin."' AND (det_cantequivale <> 0 OR det_cosTotal <> 0) 
    GROUP BY 1,2,3,4 
    HAVING SAN<>0 OR CIN <>0 OR CEG <> 0 OR SAC <>0 OR VAC <>0 
    ORDER BY 1,4;
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
    if (!$Tpl->is_cached('InTrTr_saldogeneral.tpl')) {
            }
//    
            $Tpl->display('InTrTr_saldogeneral.tpl');
}
?>