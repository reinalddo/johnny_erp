<?php
/*    Reporte de Productores, detalle General Formato HTML
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
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');

$Tpl->assign("subtitulo",$subtitulo);

$sSql = "SELECT 
            codigo,
            nombre,
            CONCAT (per_CodAuxiliar,' ',per_Apellidos) AS banco,
            numero_cta_cte,
            num_inscr_magap,
            tipo_pago,
            benef_alterno,
            tipo_cuenta,
            zona_origen,
            zona_pago,
            zona_corte
         FROM 
            v_conauxvar
            LEFT JOIN conpersonas ON BANCO=per_CodAuxiliar;";
	    
$rs = $db->execute($sSql);
$tplFile = 'InTrTr_productoresdet.tpl';
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);
$Tpl->display($tplFile);
?>
