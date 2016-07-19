<?php
ob_start("ob_gzhandler");
ob_start("ob_gzhandler");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("adoConn.inc.php");
include("GenCifras.php");
require('Smarty.class.php');

class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = './';
	//$this->template_dir = '../templates';
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

include ("../LibPhp/excelOut.php");
$Tpl = new Smarty_AAA();

$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetparam("pAppDbg",false);

function &fDefineQry(&$db){
    global $gdDesde;
    global $gdHasta;
    global $Pivot;
    global $ColumPivot;
    global $TipoComp;
    $cnt=0;
    $PivotSql ="SELECT DISTINCT(det_codcuenta) As txt_cod,
                  ifnull(cue_descripcion,'***') as txt_descr
				FROM concomprobantes 
				         JOIN condetalle ON det_regnumero = com_regnumero
				         LEFT JOIN concuentas on cue_codcuenta = det_codcuenta
				WHERE
				com_tipocomp  = ".$TipoComp." and (com_FecTrans BETWEEN '".$gdDesde."' AND '".$gdHasta."')
				ORDER BY txt_cod,txt_descr";
    
    $Pivot = $db->GetAll($PivotSql);
    
    if(!$Pivot) fErrorPage('','NO SE GENERARON LOS DETALLES DE CONTABILIZACION', false,false);
   
	foreach ($Pivot as $k=>$v){
		$ColumPivot[$cnt]=$v['txt_cod'].'-'. $v['txt_descr'];
	    $slCond .= ", SUM(IF (det_CodCuenta= ". $v['txt_cod'] .", det_ValDebito - det_ValCredito, '')) AS  '" .$v['txt_cod'].'-'. $v['txt_descr'] ."'";
	    $cnt++;
	}
	//print_r($db);
	//print_r($ColumPivot);
	
    $slSql = "SELECT com_TipoComp as TIP, com_NumComp as NUM,
    CONCAT('../In_Files/InTrTr_Cabe.php?pMod=CO&com_RegNumero=',com_RegNumero,'&pTipoComp=',com_TipoComp) AS url ,
    com_FecTrans as FTR,com_FecContab as FCT,
        com_FecVencim as FCV,CONCAT(par_Secuencia,'-',par_Descripcion) AS LIB,com_Usuario as USU,
				CONCAT(com_CodReceptor,'-',com_Receptor) AS CLI,per_Ruc AS RUC
				".$slCond.",
				com_Concepto as CON
				FROM concomprobantes 
				     LEFT JOIN condetalle ON det_regnumero = com_regnumero 
				     LEFT JOIN conpersonas ON per_codauxiliar = com_codreceptor
				     LEFT JOIN genparametros	ON par_Clave='CLIBRO' AND par_secuencia = com_libro
				where com_TipoComp= ".$TipoComp." AND com_FecTrans BETWEEN '".$gdDesde."' AND '".$gdHasta."' 
				GROUP BY 1,2
				ORDER BY 1,2";
    $rsLiq = $db->Execute($slSql);
    if(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE CONTABILIZACION', false,false);
    return $rsLiq;
}



include("../LibPhp/pie.php");
$db =& fConexion();
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$slFontName = 'Helvetica';
$gdDesde=false;
$gdHasta= false;
$Pivot = Array();
$ColumPivot = Array();
$gdDesde = fGetParam('pFecI', false);
$gdHasta = fGetParam('pFecF', false);
$TipoComp = fGetParam('com_TipoComp', "'FA'");
//echo($gdDesde);
//echo($gdHasta);
$gaCols=array();
if (!$gdDesde) $gdDesde = fDBValor($db, 'genparametros', 'par_Valor1', "par_clave = 'ININIC' AND par_secuencia=1");
if (!$gdHasta) $gdHasta = date("Y-m-d", time());

$rs = fDefineQry($db);
$tplFile = 'CoTrTr_libroventas.tpl';
$Tpl->assign("gsNumCols", count($gaCols) + 4);
$Tpl->assign("gaColumnas", $gaCols);
$Tpl->assign("gsEmpresa",$_SESSION["g_empr"]);
$gsSubt= " " . fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
$Tpl->assign("gsNumCols", 7);
$Tpl->assign("gsPivot", $ColumPivot);
$Tpl->assign("gsDesde", $gdDesde);
$Tpl->assign("gsHasta", $gdHasta);
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);
$Tpl->display($tplFile);
?>

