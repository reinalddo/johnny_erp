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
        $this->template_dir = './';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = fGetParam("pSmtDbg",false);
   }
}

include "../../AAA_SEGURIDAD/LibPhp/excelOut.php";

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
   
   $db->Execute("SET lc_time_names = 'es_EC'");
   $ilSem=urldecode(fGetParam("pSem",0));
   $ilEmb=urldecode(fGetParam("pEmb",0));
   $slCond = 'tac_semana = ' . $ilSem . ($ilEmb? '  AND tac_refoperativa = ' . $ilEmb : "");
// Grupos de Cabecera
  /*
   
									      MARCO:!!!!!!!!!!!!!!!!!!!!!!!!!  ESTE QUERY ESTA EQUIVOCADO!!!!!!!!!!!!!!!!
   $slSqlH ="  select 
		  com_codreceptor , 
		  com_refoperat	as 'semana',
		  cl.aux_nombre AS 'productor',
		  bo.aux_codigo AS 'cod_bod_origen', 
		  bo.aux_nombre AS 'bodega',
		  cl.aux_codigo AS 'codProductor',
		  act_codauxiliar AS coditem,
		  concat(act_descripcion, ' ', ifnull(act_descripcion,'')) as 'item',
		  pre_preunitario as 'precio',
		  det_cantequivale * pre_preunitario as total,
		  CONCAT(com_TipoComp,com_NumComp) as tipo_comp
		  ,SUM((pro_Signo*det_CantEquivale)) as 'cantidad'
	       from
		  concomprobantes 
		  join invdetalle on det_regnumero = com_regnumero 
		  join v_conauxiliar cl on cl.aux_codigo = com_codreceptor
		  join v_conauxiliar bo on bo.aux_codigo = com_emisor
		  join conactivos it on it.act_codauxiliar = det_coditem
		  left join invprecios on pre_lisprecios = 2 and pre_coditem = det_coditem
		  left join invprocesos on pro_codproceso = 5 and cla_TipoTransacc = com_TipoComp 
	       WHERE ACT_GRUPO=320 AND $pQry
	       GROUP BY 
			com_codreceptor
	       ORDER BY
			bodega,productor" ;				
*/
   $slSqlH ="select 
		  com_codreceptor , 
		  com_refoperat	as 'semana',
		  cl.aux_nombre AS 'productor',
		  bo.aux_codigo AS 'cod_bod_origen', 
		  bo.aux_nombre AS 'bodega',
		  cl.aux_codigo AS 'codProductor',
		  act_codauxiliar AS coditem,
		  concat(act_descripcion, ' ', ifnull(act_descripcion1,'')) as 'item',
		  pre_preunitario as 'precio',
		  sum(det_cantequivale * pre_preunitario) as total,
		  SUM((pro_Signo*det_CantEquivale)) as 'cantidad'
	       from
		  concomprobantes 
		  join invdetalle on det_regnumero = com_regnumero 
		  join v_conauxiliar cl on cl.aux_codigo = com_codreceptor
		  join v_conauxiliar bo on bo.aux_codigo = com_emisor
		  join conactivos it on it.act_codauxiliar = det_coditem
		  left join invprecios on pre_lisprecios = 2 and pre_coditem = det_coditem
		  left join invprocesos on pro_codproceso = 5 and cla_TipoTransacc = com_TipoComp 
	       WHERE ACT_GRUPO=320 AND com_TipoComp<>'TI' AND com_TipoComp<>'TE' AND $pQry
	       GROUP BY  1,2,3,4,5,6,7,8,9
	       ORDER BY
			bodega,productor";

   $rsG = $db->Execute($slSqlH);
   return $rsG;	
}
/**
 *		Procesamiento
*/
/*							Recibir Parametros de Filtrado */

$pSem = fGetParam('com_RefOperat', false);
$gsCond = fGetParam('pQryCom', false);

/*							Armar Condicion SQL*/

set_time_limit (0) ;
$db->debug=fGetParam('pAdoDbg', 0);
$rs = fDefineQry($db, $gsCond );
$tplFile = 'InTrTr_desp_g.rpt.php.tpl';
$Tpl->assign("gsNumCols", 15);
$Tpl->assign("gsEmpresa", $_SESSION["g_empr"]);
$gsSubt= " " . fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
$Tpl->assign("gsNumCols", 7);
$aDet =& SmartyArray($rs);

//print_r($aDet);
$Tpl->assign("agData", $aDet);
//if (!$Tpl->is_cached($tplFile)) {
//}
$Tpl->display($tplFile);

?>
