<?php
/*    Reporte de Ventas. Formato HTML para Asisbane
 *    @param   integer  pMes     Numero de mes a ejecutar el reporte
 *    @param   integer  pAnio    Anio para ejecutar el reporte
 *    @param   varchar  pQry	 Condicion para el query
 *    @rev	fah 2015-03-31		 Habilitar más memoria para este script y no reporte error  "Allowed memory size of ...... bytes exhausted ...."
 */
ini_set("memory_limit","2000M");		// #fah2015-03-01    Soportar procesos largos
ini_set('max_execution_time', 300);		// #fah2015-03-01    Soportar procesos largos
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
        $this->template_dir = './';
	//$this->template_dir = '../templates';
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
$pQry = fGetParam('pQry','');

$Tpl->assign("subtitulo",$subtitulo);

$sSql = "SELECT   c.com_TipoComp,
		  c.com_NumComp,
		  c.com_FecContab,
		  DATE_FORMAT(c.com_FecContab,'%M-%Y') AS mes,
		  c.com_emisor,
		  c.com_CodReceptor,
		  (SELECT per_Ruc FROM conpersonas WHERE per_codAuxiliar=c.com_CodReceptor) AS RUC,
		  concat(ifnull(cli.per_Apellidos,''),' ',ifnull(cli.per_Nombres,'')) as cliente,
		  c.com_Receptor,
		  c.com_Libro,
		  lib.par_Descripcion as libro,
		  c.com_RefOperat,
		  i.det_CodItem,
		  CONCAT(ifnull(act_Descripcion,''),'',ifnull(act_Descripcion1,'')) as item,
		  i.det_CanDespachada, 
		  i.det_UniMedida,
		  um.uni_Descripcion as uniMedida,
		  i.det_CantEquivale, 
		  i.det_CosTotal, 
		  i.det_ValTotal,
		  i.det_DescTotal, 
		  i.det_RefOperativa,
		  i.det_Estado,
		  i.det_cosunitario, 
		  i.det_valunitario,
		  i.det_Destino,
		  
		  (CASE act_IvaFlag WHEN 0 THEN det_ValTotal ELSE 0 END) AS base0,
		  (CASE act_IvaFlag WHEN 0 THEN 0 ELSE det_ValTotal END) AS baseIva,
		  (CASE act_IvaFlag WHEN 0 THEN 0 ELSE (det_ValTotal*0.12) END) AS montoIva,
		  (CASE act_IvaFlag
		     WHEN 0 THEN (CASE act_IvaFlag WHEN 0 THEN det_ValTotal ELSE 0 END)
		     ELSE (CASE act_IvaFlag WHEN 0 THEN 0 ELSE det_ValTotal END) + (CASE act_IvaFlag WHEN 0 THEN 0 ELSE (det_ValTotal*0.12) END)
		  END) AS totalFac,
IF(i.`det_Secuencia` >= 2, '', (SELECT valorRetIva FROM v_retencionVTA rt WHERE rt.documento = c.com_NumComp AND rt.com_tipocomp = 'RI'))	AS	  valorRetIva,
IF(i.`det_Secuencia` >= 2, '', (SELECT valorRetRenta FROM v_retencionVTA rt WHERE rt.documento = c.com_NumComp AND rt.com_tipocomp = 'RT'))	AS	  valorRetRenta
		  
	  FROM concomprobantes c
	  JOIN invdetalle i ON i.det_regnumero = c.com_regnumero
	  left join conpersonas cli on cli.per_codauxiliar = c.com_CodReceptor
	  left join genparametros lib on lib.par_Clave = 'CLIBRO' and lib.par_Secuencia = c.com_Libro
	  left join genunmedida um on um.uni_CodUnidad = i.det_UniMedida
	  left join conactivos act on act.act_codauxiliar = i.det_CodItem
	  WHERE c.com_TipoComp IN (SELECT par_Valor4 FROM genparametros WHERE `genparametros`.`par_Clave` IN ('CLOCAL'))
	  and i.det_ValTotal != 0 
	  ";
	  
	  //"and month(c.com_FecContab) = 1 and year(c.com_FecContab) = 2015";
	  
$pQry = str_replace("fechaRegistro","c.com_FecContab",$pQry);  //reemplazar fecha de registro por fecha contable

$sSql .= ($pQry ? " and ". $pQry  : "  " );
$sSql .= " order by DATE_FORMAT(c.com_FecContab,'%Y-%m'), c.com_TipoComp, c.com_NumComp";

$sSql = str_replace("and YEAR(c.com_FecContab)= AND","and",$sSql);
$sSql = str_replace("and MONTH(c.com_FecContab)=  order","order",$sSql);

$sSql = str_replace("and MONTH(c.com_FecContab)=   order","order",$sSql);

// echo $sSql;

$rs = $db->execute($sSql);
$tplFile = 'CoRtTr_Ventas_Rep.tpl';
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);
$Tpl->display($tplFile);
?>
