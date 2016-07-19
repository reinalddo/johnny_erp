<?php
/*    Reporte de tarjas, detalle General por Puerto, Vapor, Marca. Formato HTML
 *    @param   integer  pSem     Numero de semana a procesar
 *    @param   integer  pEmb     Numero de Embarque
 *    @param   string   PMarca   Marca
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
	ID AS ANEXO,
	idProvFact AS ID_PROVEEDOR,
	CONCAT ( per_Apellidos,' ', per_Nombres) AS PROVEEDOR,
	CONCAT(establecimiento,'-',puntoEmision,'-',secuenciaL) AS COMPROBANTE,
	fechaRegistro AS FECHA_REGISTRO,
	'PROVISION_SIN_ASIENTO' AS MOTIVO
FROM 
	fiscompras
	JOIN  conpersonas ON idProvFact=per_CodAuxiliar
WHERE ID NOT IN (SELECT com_NumRetenc FROM concomprobantes WHERE (com_TipoComp='PC' or com_TipoComp='CF'))
UNION	
SELECT 
	'' AS ANEXO, 
	 com_CodReceptor AS ID_PROVEEDOR,	
	CONCAT ( per_Apellidos,' ', per_Nombres) AS PROVEEDOR,
	CONCAT(com_TipoComp,' ',com_NumComp) AS COMPROBANTE,
	com_FecContab AS FECHA_REGISTRO,
	'PROVISION_SIN_ANEXO' AS MOTIVO
FROM concomprobantes 
	JOIN  conpersonas ON com_CodReceptor=per_CodAuxiliar
WHERE com_TipoComp='PC' AND com_NumRetenc NOT IN (SELECT ID FROM fiscompras)
ORDER BY MOTIVO,FECHA_REGISTRO";

//echo $sSql;

$rs = $db->execute($sSql);
$tplFile = 'CoRtTr_Anexo_Rep.tpl';
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);
$Tpl->display($tplFile);
?>
