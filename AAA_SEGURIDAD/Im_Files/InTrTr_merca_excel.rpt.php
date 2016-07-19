<?php
/*    Reporte de cierre de caja. Formato HTML
 *    @param   integer  pFecIni     Fecha de Inicio para consulta
 *    @param   integer  pFecFin     Fecha Final de rango
 *    JOHNNY VALENCIA L. - 20 Jun 2015 13h00
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
header("Content-Type:  application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");    


$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
//$glFlag= fGetParam('pEmpq', false);

$pSemana = fGetparam("pSemana","");

$sSql = "SELECT mer_refOperat MSM, mer_equipment AS MCO, costo_interno MCI, costo_externo MCE, ifnull(costo_externo,0)- ifnull(costo_interno,0) AS MRT, mer_workorder MWO  FROM ( SELECT * FROM (SELECT CONCAT(SUBSTRING(  mer_year, -2), mer_week) AS mer_refOperat, CONCAT(SUBSTRING(  YEAR(mer_date), -2), mer_week) AS mer_refOperat2, mer_equipment,  SUM(mer_total_to_paid) AS costo_externo, mer_workorder, merca.id AS merid
FROM merca WHERE mer_invoice IS NULL 
GROUP BY mer_refOperat, mer_workorder) AS C
LEFT JOIN ( SELECT * FROM (
	SELECT com_refOperat, com_Contenedor, SUM(det_CosTotal) AS costo_interno
	FROM concomprobantes   
	JOIN  invdetalle ON invdetalle.det_RegNumero = concomprobantes.com_RegNumero AND com_TipoComp = 'EG' 
	GROUP BY com_refOperat, com_Contenedor) AS B) AS A  ON  C.mer_refOperat =A.com_refOperat  AND A.com_Contenedor = C.mer_equipment 
AND merid IS NOT NULL
UNION
SELECT * FROM (
SELECT com_refOperat, com_Contenedor, SUM(det_CosTotal) AS costo_interno
	FROM concomprobantes   
	JOIN  invdetalle ON invdetalle.det_RegNumero = concomprobantes.com_RegNumero AND com_TipoComp = 'EG' 
	GROUP BY com_refOperat, com_Contenedor) AS A   
LEFT JOIN	
( 
SELECT * FROM (SELECT CONCAT(SUBSTRING(  mer_year, -2), mer_week) AS mer_refOperat, CONCAT(SUBSTRING(  YEAR(mer_date), -2), mer_week) AS mer_refOperat2, mer_equipment,  SUM(mer_total_to_paid) AS costo_externo, mer_workorder, merca.id AS merid
FROM merca WHERE mer_invoice IS NULL 
GROUP BY mer_refOperat, mer_workorder) AS B ) AS C  ON  C.mer_refOperat =A.com_refOperat  AND A.com_Contenedor = C.mer_equipment AND merid IS NOT NULL"
        . ") AS S WHERE mer_refOperat =  '$pSemana'";
//echo $sSql;exit;
        

$rs = $db->execute($sSql);
//$rs->MoveFirst();

//}
//print_r($agSaldos);

//echo basename($_SERVER[ "PHP_SELF"],".php");
$filename = basename($_SERVER[ "PHP_SELF"],".php").".xls";
//echo $filename;
$Tpl->assign("agArchivo", $filename);

//$rs = $db->execute($sSql . $slFrom);
if($rs->EOF){//0 == count($agSaldos)){
    fErrorPage('','NO SE GENERO EL REPORTE DE CONTENEDORES', true,false);
}else{
//     
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
//    
    if (!$Tpl->is_cached('InTrTr_mercaexcel.tpl')) {
            }
//    
            $Tpl->display('InTrTr_mercaexcel.tpl');
}
?>