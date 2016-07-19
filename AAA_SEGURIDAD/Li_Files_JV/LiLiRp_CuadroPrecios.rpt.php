<?php
/*    Reporte - REPORTE GERENCIAL PARA APLESA - CUADRO DE PRECIOS
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
$subtitulo="CUADRO DE PRECIOS";
$Tpl->assign("subtitulo",$subtitulo);
$subtitulo2 = "Semana:".$semana;
$subtitulo2 .= ($com_codReceptor ? " Productor: ". $com_codReceptor  : "  " );
$Tpl->assign("subtitulo2",$subtitulo2);


/*para consultar CAJAS LIQUIDADAS AL PRODUCTOR*/
/*$sSql = " SELECT ifnull(SUM(liq_Cantidad),0) AS LiqProduc 
FROM liqliquidaciones WHERE liq_NumProceso IN (SELECT pro_id FROM liqprocesos WHERE pro_Semana = ".$semana.") AND liq_CodRubro = 1  ";
$rs = $db->execute($sSql . $slFrom);
$LiqProduc = $rs->FetchRow();
*/

/*para consultar los detalles*/
$sSql = " SELECT     t.tac_Embarcador, CONCAT(p.per_Apellidos,' ',p.per_Nombres) AS productor,
		     tad_tipoCaja, CASE WHEN ifnull(tad_tipoCaja,2) IS NULL THEN 'SIN CLASIFICAR' ELSE tCja.par_Descripcion END AS txTipoCja,
		     d.tad_codProducto,
		     upper(CONCAT(act_Descripcion, '', act_Descripcion1)) AS Producto,
		     (d.tad_ValUnitario-d.tad_DifUnitario) AS pCaj, SUM(d.tad_CantRecibida-d.tad_CantCaidas-d.tad_CantRechazada) AS CajEmb,
		     SUM(d.tad_CantRecibida-d.tad_CantCaidas-d.tad_CantRechazada) * (d.tad_ValUnitario-d.tad_DifUnitario) AS tFruta,
		     egnom.par_Descripcion AS EGNombre,tac_Zona, tzona.par_Descripcion AS txzona 
	     FROM liqtarjacabec t 
		     LEFT JOIN liqtarjadetal d ON d.tad_NumTarja = t.tar_NumTarja
		     LEFT JOIN conpersonas p ON p.per_CodAuxiliar = t.tac_Embarcador
		     LEFT JOIN genparametros egnom ON egnom.par_Clave = 'EGNOM'
		     LEFT JOIN genparametros tzona ON tzona.par_Clave = 'LSZON' AND tzona.par_Secuencia = tac_zona
		     LEFT JOIN genparametros tCja ON tCja.par_Clave = 'OTPCAJ' AND tCja.par_Secuencia = ifnull(tad_tipoCaja,2) /* si no se especifica van a 2-SPOT*/
		     LEFT JOIN conactivos ON act_CodAuxiliar = d.tad_codProducto
		     WHERE t.tac_Semana  =  ".$semana;
//$sSql .= ($com_codReceptor ? " and c.com_CodReceptor = ". $com_codReceptor  : "  " );
$sSql .= "  GROUP BY  t.tac_Embarcador, ifnull(tad_tipoCaja,2), d.tad_codProducto, (d.tad_ValUnitario-d.tad_DifUnitario)
	    ORDER BY 4, 2 ";
	

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
    if (!$Tpl->is_cached('../Li_Files/LiLiRp_CuadroPrecios.tpl')) {
            }
            $Tpl->display('../Li_Files/LiLiRp_CuadroPrecios.tpl');
}
?>