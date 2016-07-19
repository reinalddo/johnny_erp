<?php
/*
*   Detalle de Tarjas diario
*   Detalle de Tarjas 
*   @date	11/03/09    
*   @author     fah
*   @rev 	fah 02/06/09	Correccion de Query, para agrupar correctamente los datos
*/
ob_start("ob_gzhandler");
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("adoConn.inc.php");
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

include ("../../AAA_SEGURIDAD/LibPhp/excelOut.php");



$Tpl = new Smarty_AAA();
$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetparam("pAppDbg",false);
/*
 ********************************
*/

/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function &fDefineQry(&$db, $pQry=false){
   global $rsH, $rsG;
  // echo 'original: '.$pQry;
   $db->Execute("SET lc_time_names = 'es_EC'");
   $v=substr ($pQry, 0, 13);
   $f1=substr ($pQry, 23, 8);
   $f2=substr ($pQry, 38, 8);
   list($d,$m,$a)=explode("/",$f1);
   $f1n='20'.$a."-".$m."-".$d;
   list($d,$m,$a)=explode("/",$f2);
   $f2n='20'.$a."-".$m."-".$d;
   
   if($v=='com_FecContab')
   {
    $slCond=$pQry;
    $slCond='com_FecContab BETWEEN "'.$f1n.'" AND "'.$f2n.'"';
   }
   else
   {
      $slCond=$pQry;
   }
   
  // ECHO 'condicion: '.$slCond; 
   
   $slSqlH ="select
		  com_NumComp as factura, 
		  com_codreceptor , 
		  com_refoperat as 'semana', 
		  cl.aux_nombre AS 'productor', 
		  bo.aux_codigo AS 'cod_bod_origen', 
		  SUBSTRING(bo.aux_nombre,8) AS 'bodega', 
		  cl.aux_codigo AS 'codProductor', 
		  act_codauxiliar AS coditem, 
		  concat(act_descripcion, ' ', ifnull(act_descripcion1,'')) as 'item', 
		  det_ValUnitario as 'valor_unitario' ,
		  det_cantequivale as 'cantidad',
		  
		  CASE  WHEN (act_IvaFlag=2) THEN det_ValTotal
		  WHEN (act_IvaFlag=3) THEN (det_ValTotal/(1+(tsd_PorcentajeBI/100)))
		  ELSE det_ValTotal
		  END AS 'BASE_IMPONIBLE',
		  
		  
		  CASE  WHEN (act_IvaFlag=0) THEN '0'
		  ELSE tsd_PorcentajeBI
		  END AS 'PORCENTAJE_IVA',
		  CASE  WHEN (act_IvaFlag=0) THEN '0'
		  ELSE det_ValTotal*(tsd_PorcentajeBI/100)
		  END AS 'IVA',
		  CASE  WHEN (act_IvaFlag=2) THEN (det_ValTotal+(det_ValTotal*(tsd_PorcentajeBI/100)))
		     WHEN (act_IvaFlag=3) THEN det_ValTotal
		  ELSE det_ValTotal
		  END AS 'TOTAL'
	       from
		  concomprobantes
		  left join invdetalle on det_regnumero = com_regnumero 
		  left join v_conauxiliar cl on cl.aux_codigo = com_codreceptor 
		  left join v_conauxiliar bo on bo.aux_codigo = com_emisor 
		  left join conactivos it on it.act_codauxiliar = det_coditem 
		  left join invprecios on pre_lisprecios = 2 and pre_coditem = det_coditem 
		  left join invprocesos on pro_codproceso = 5 and cla_TipoTransacc = com_TipoComp,
		  gentasacabecera,
		  gentasadetalle
	       WHERE 
		  com_TipoComp='FA' AND 
		  tsc_ID=tsd_ID AND
		  tsd_Rubro=1 AND $slCond
	       ORDER BY
			bodega,productor";

   $rsG = $db->Execute($slSqlH);
   return $rsG;	
}
/**
 *		Procesamiento
*/

include ("../../AAA_SEGURIDAD/LibPhp/pie.php");

/*							Recibir Parametros de Filtrado */

$pSem = fGetParam('com_RefOperat', false);
$gsCond = fGetParam('pQryCom', false);

/*							Armar Condicion SQL*/

set_time_limit (0) ;
$db->debug=fGetParam('pAdoDbg', 0);
$rs = fDefineQry($db, $gsCond );//se procesa el reporte
$c=substr ($gsCond, 22);
$tplFile = 'InTrTr_facturas.tpl';
$Tpl->assign("gsNumCols", 15);
$Tpl->assign("gsEmpresa", $_SESSION["g_empr"]);
$Tpl->assign("dh", $c);
$gsSubt= " " . fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
$Tpl->assign("gsNumCols", 7);
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);

$Tpl->display($tplFile);

?>
