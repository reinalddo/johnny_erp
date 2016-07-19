<?php
ob_start("ob_gzhandler");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("adoConn.inc.php");
include("GenCifras.php");
require('Smarty.class.php');
include('tohtml.inc.php'); 
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        //$this->template_dir = './';
	$this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = fGetParam("pSmtDbg",false);
	
   }
}
if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}

include ("../../AAA_SEGURIDAD/LibPhp/excelOut.php");
$Tpl = new Smarty_AAA();

$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetparam("pAppDbg",false);

function &fDefineQry(&$db, $pQry=false){
   $slSql = "SELECT
                com_codreceptor AS 'CODRE',
                concat(per_Apellidos, ' ', per_nombres) as RECEP,
                det_coditem AS CODIT,
                left(concat(act_descripcion, ' ', act_descripcion1),25) as ITEM,
                com_refoperat AS 'SEMAN',
                SUM(if (com_tipocomp = 'EP' , det_cantequivale, 0000000000.00)) AS 'EGR',
                SUM(if (com_tipocomp = 'DV' , det_cantequivale * pro_signo, 0000000000.00)) AS 'DEV',
                SUM(if (com_tipocomp = 'LE' , det_cantequivale * pro_signo, 0000000000.00)) AS 'EMB',
                SUM(if (com_tipocomp = 'LI' and det_cantequivale > 0  , det_cantequivale * pro_signo, 0000000000.00)) AS 'COB',
                SUM(if (com_tipocomp = 'LI' and det_cantequivale > 0  , det_valtotal * pro_signo, 0000000000.00)) AS 'VCO',
                SUM(if (com_tipocomp = 'LI' and det_cantequivale < 0, det_cantequivale * pro_signo, 0000000000.00)) AS 'PAG',
                SUM(if (com_tipocomp = 'LI' and det_cantequivale < 0  , det_valtotal * pro_signo, 0000000000.00)) AS 'VPA',
                SUM((det_cantequivale * pro_signo)) AS 'SAL',
                SUM((det_cantequivale * pro_efeacumula)) AS 'HIS',
                SUM((det_valtotal * pro_efeacumula)) AS 'VHI'
                from invprocesos JOIN concomprobantes ON pro_codproceso = 20 AND com_tipocomp = cla_TipoTransacc
                     JOIN invdetalle ON det_regnumero = com_regnumero
                	 JOIN conactivos ON act_codauxiliar = det_coditem
                	 JOIN conpersonas ON per_codauxiliar = com_codreceptor
                     LEFT JOIN genunmedida ON uni_CodUnidad = det_unimedida
                WHERE (det_cantEquivale <>0 OR det_costotal <>0 ) "          ;
    if ($pQry) $slSql .= " AND   "  . $pQry ;

    $slSql .= " GROUP BY CODRE, RECEP, CODIT, ITEM, SEMAN ORDER  BY RECEP, ITEM, SEMAN";

    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE INVENTARIO', true,false);
    return $rsLiq;
}


$slQry   = fGetParam('pQryCom', false);
$db =& fConexion();
set_time_limit (0) ;
if ($slQry) $rs = fDefineQry($db, $slQry );
$slFontName = 'Courier';

$tplFile = 'InTrTr_karprodsemsum_v2.tpl';
$Tpl->assign("gsNumCols", 6);
$Tpl->assign("gsEmpresa", $_SESSION["g_empr"]);
$Tpl->assign("acumulado", $igAcumula);
$gsSubt= " " . fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
$Tpl->assign("gsNumCols", 7);
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);
$Tpl->display($tplFile);
?>