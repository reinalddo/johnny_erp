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


$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
//$glFlag= fGetParam('pEmpq', false);

$pDesde = fGetparam("pDesde","");
$pHasta = fGetparam("pHasta","");


$where = "";
if($pDesde!=""){
   $where .= " AND c.com_FecTrans >= '$pDesde'";  
}
if($pHasta!=""){
   $where .= " AND c.com_FecTrans <= '$pHasta'";  
}

$sSqlDias = "SELECT c.`com_FecTrans` AS SEC, c.`com_NumComp` AS FTR, m.`mer_workorder` AS SHO, CONCAT(SUBSTRING(  mer_year, -2), mer_week)  AS COU,
  d.`det_ValTotal`/ 
  (SELECT 
    COUNT(*) 
  FROM
    `merca` mer
  WHERE mer.mer_invoice = m.`mer_invoice`) AS TDC  
FROM concomprobantes c
INNER JOIN `merca` m ON c.`com_NumComp` = m.`mer_invoice`
INNER JOIN `invdetalle` d ON c.`com_RegNumero` = d.`det_RegNUmero`
WHERE com_TipoComp = 'FA' $where";  // Trae Parametros de Dias de Garantia
//echo $sSqlDias;exit;
//$rsDias = $db->execute($sSqlDias);

        

$rs = $db->execute($sSqlDias);
//var_dump($rs);  
//$rs->MoveFirst();

//}
//print_r($agSaldos);

//echo basename($_SERVER[ "PHP_SELF"],".php");
$filename = basename($_SERVER[ "PHP_SELF"],".php");
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
    if (!$Tpl->is_cached('InTrTr_merca_facturas.tpl')) {
            }
//    
            $Tpl->display('InTrTr_merca_facturas.tpl');
}
?>