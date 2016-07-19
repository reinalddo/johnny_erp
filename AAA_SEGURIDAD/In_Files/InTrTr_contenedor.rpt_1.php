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


$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
//$glFlag= fGetParam('pEmpq', false);

$pDias = fGetparam("pDias","");

$sSqlDias = "SELECT par_Valor1, par_Descripcion FROM genparametros WHERE par_Clave = 'DGRT'";
$rsDias = $db->execute($sSqlDias);
$arrDays[""] = "Todos";
while ($r = $rsDias->fetchRow()){       
       $arrDays[$r['par_Valor1']] = $r['par_Descripcion']; 
    }


$Tpl->assign('day_options', $arrDays );
if(isset($pDias))
$Tpl->assign('day_id', $pDias);

$where = "";
if($pDias!=""){
   $where = " INNER JOIN  conactivos  ON conactivos.act_codauxiliar =  invdetalle.det_coditem  AND conactivos.act_Abreviatura = '".$pDias."'";  
}else{
   $where = " LEFT JOIN  conactivos  ON conactivos.act_codauxiliar =  invdetalle.det_coditem "; 
}

$sSql = "SELECT (@a:=@a+1) AS SEC, (SELECT par_Descripcion FROM `genparametros` WHERE par_clave = 'EGNOM') AS SHO,
(SELECT par_Descripcion FROM `genparametros` WHERE par_clave = 'EGPAIS') AS COU, det_WorkOrder AS WOR, com_concepto AS INR, det_Tag AS TAG, 
com_Contenedor AS CON,
act_CodAnterior AS ITE,	act_Descripcion AS NAM, det_SerieOld AS SER, conactivos.act_Abreviatura AS PER
 FROM concomprobantes   
 JOIN (SELECT @a:= 0) AS secuencia
JOIN  invdetalle ON invdetalle.det_RegNumero = concomprobantes.com_RegNumero
$where";
        
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
    fErrorPage('','NO SE GENERO EL REPORTE DE CONTENEDORES', true,false);
}else{
//     
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
//    
    if (!$Tpl->is_cached('InTrTr_contenedor.tpl')) {
            }
//    
            $Tpl->display('InTrTr_contenedor.tpl');
}
?>