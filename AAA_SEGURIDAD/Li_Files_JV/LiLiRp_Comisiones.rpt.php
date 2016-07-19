<?php
/*    Reporte - REPORTE GERENCIAL PARA APLESA - CUADRO DE COSTOS: COMISIONES Y TRANSPORTE
 */

ob_start("ob_gzhandler");
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

include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
// parametro para el query general
$pQry = fGetParam('pQryCom','');

// Parametros individuales para el query
$semana = fGetParam('pro_Semana','');
$com_codReceptor = fGetParam('com_codReceptor',false);
$subtitulo = fGetParam('pCond','');
$subtitulo=" ";
$Tpl->assign("subtitulo",$subtitulo);
$subtitulo2 = "Semana:".$semana;
$subtitulo2 .= ($com_codReceptor ? " Productor: ". $com_codReceptor  : "  " );
$Tpl->assign("subtitulo2",$subtitulo2);

/*para consultar los detalles*/
$sSql = " SELECT liquidacionDatoExtra.*
		  ,CONCAT(per_Apellidos,' ',per_Nombres) AS Txauxiliar
		  ,tpVar.par_Descripcion AS TxtipoVariable
		  ,tpVar.par_Valor1 ReqCajas
		  ,CASE tpVar.par_Valor1
			  WHEN 1 THEN lde_cajas*lde_precio
			  WHEN 0 THEN lde_precio
		  END AS PrecioTotal
		  ,egnom.par_Descripcion AS EGNombre
	  FROM liquidacionDatoExtra 
	  LEFT JOIN genparametros tpVar ON tpVar.par_Clave = 'LGCOST' AND tpVar.par_Secuencia = lde_tipoVariable
	  LEFT JOIN conpersonas ON per_CodAuxiliar = lde_auxiliar
	  LEFT JOIN genparametros egnom ON egnom.par_Clave = 'EGNOM'
	  WHERE lde_estado = 1 AND lde_semana =  ".$semana;

//$sSql .= ($com_codReceptor ? " and c.com_CodReceptor = ". $com_codReceptor  : "  " );
$sSql .= "  ORDER BY tpVar.par_Descripcion ";
	

$rs = $db->execute($sSql . $slFrom);

if($rs->EOF){
    fErrorPage('','NO SE GENERO INFORMACION PARA PRESENTAR', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    $Tpl->assign("agPeriodo", $Periodo);
    $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
    $Tpl->assign("slPiePag", $slPiePag);
    //$Tpl->assign("LiqProduc",$LiqProduc['LiqProduc']);
    if (!$Tpl->is_cached('../Li_Files/LiLiRp_Comisiones.tpl')) {
            }
            $Tpl->display('../Li_Files/LiLiRp_Comisiones.tpl');
}
?>