<?php
/*
*   Detalle de Tarjas 
*   Detalle de Tarjas 
*   @date	11/03/09    
*   @author     fah
*   @
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

include "../LibPhp/excelOut.php";
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
//
   if ($_SESSION["g_dbase"] == "09_forzaliq") { // si se procesa en BD consolidadora
      $slSqlH ="
	select
	    emp_Descripcion as txp_Shipper,
	    ifnull(cns_RegNumero, `txp_NumTarja`) as txp_NumTarja ,
	   `txp_RefOperativa` ,
	   `txp_NombBuque`,
	   `txp_NumViaje`,
	    txp_Observaciones,
	   `txp_Semana`,
	   `txp_Fecha`,
	   `txp_Embarcador`,
	   `txp_Zona`,
	   `txp_NombZona`,
	   `txp_Contenedor`,
	   `txp_ResCalidad`,
	   `txp_Estado`,
	   `txp_NumLiquid`,
	    txp_Bodega,
	    txp_Piso,
	   txp_PtoDescripcion,	
	   `txp_PueRecepcion`,
	    `txp_Secuencia`,
	   `txp_CodProducto`,
	   txp_Producto,
	   `txp_CodEmpacador`,
	   `txp_Productor`,
	    `txp_CatOrden`,
	    `txp_Marca`,
	    `txp_CajDescrip`,
	    `txp_CajAbrevia`,
	    `txp_CantNeta`,
	    `txp_CantDespachada`,
	    `txp_CantRecibida`,
	    `txp_CantRecibida` - `txp_CantRechazada` as txp_CantPagar,
	    txp_CantDespachada - txp_CantRecibida AS txp_Faltante,
	    `txp_CantCaidas`,
	    `txp_ValUnitario`,
	    `txp_DifUnitario`,
	    if(`txp_DifUnitario` <0, -txp_DifUnitario,0) as txp_Bono,
	    if(`txp_DifUnitario` >0,  txp_DifUnitario,0) as txp_DifPrec,
	    `txp_PrecUnit`,
	    `txp_PrecUnit` * (`txp_CantRecibida` - `txp_CantRechazada` ) as txp_ValTotal,
	    `txp_LiqNumero`,
	    `txp_IndFlete` 
      FROM v_opetarjexpand
	 join genconsolidacion on txp_numtarja = cns_id and cns_tiporeg =  'T' 
	 join seguridad.segempresas on emp_basedatos = cns_codempre
      WHERE $pQry
      ORDER BY txp_Producto, txp_Shipper,  txp_NombZona, txp_Productor, txp_NumTarja
      " ;
   }
   else {
      $slSqlH ="
	select
	    '" . $_SESSION["g_empre"] . "' as txp_Shipper,
	    txp_NumTarja as txp_NumTarja ,
	   `txp_RefOperativa` ,
	   `txp_NombBuque`,
	   `txp_NumViaje`,
	    txp_Observaciones,
	   `txp_Semana`,
	   `txp_Fecha`,
	   `txp_Embarcador`,
	   `txp_Zona`,
	   `txp_NombZona`,
	   `txp_Contenedor`,
	   `txp_ResCalidad`,
	   `txp_Estado`,
	   `txp_NumLiquid`,
	    txp_Bodega,
	    txp_Piso,
	   txp_PtoDescripcion,	
	   `txp_PueRecepcion`,
	    `txp_Secuencia`,
	   `txp_CodProducto`,
	   txp_Producto,
	   `txp_CodEmpacador`,
	   `txp_Productor`,
	    `txp_CatOrden`,
	    `txp_Marca`,
	    `txp_CajDescrip`,
	    `txp_CajAbrevia`,
	    `txp_CantNeta`,
	    `txp_CantDespachada`,
	    `txp_CantRecibida`,
	    `txp_CantRecibida` - `txp_CantRechazada` as txp_CantPagar,
	    txp_CantDespachada - txp_CantRecibida AS txp_Faltante,
	    `txp_CantCaidas`,
	    `txp_ValUnitario`,
	    `txp_DifUnitario`,
	    if(`txp_DifUnitario` <0, -txp_DifUnitario,0) as txp_Bono,
	    if(`txp_DifUnitario` >0,  txp_DifUnitario,0) as txp_DifPrec,
	    `txp_PrecUnit`,
	    `txp_PrecUnit` * (`txp_CantRecibida` - `txp_CantRechazada` ) as txp_ValTotal,
	    `txp_LiqNumero`,
	    `txp_IndFlete` 
      FROM v_opetarjexpand
      WHERE $pQry
      ORDER BY txp_Producto, txp_Shipper,  txp_NombZona, txp_Productor, txp_NumTarja
      " ;
   }
   $rsG = $db->Execute($slSqlH);
   return $rsG;	
}
/**
 *		Procesamiento
*/
/*							Recibir Parametros de Filtrado */

$pSem = fGetParam('tac_Semana', false);
$pPue = fGetParam('tac_PueRecepcion', false);
$pFec = fGetParam('tac_Fecha', false);
$pZOn = fGetParam('tac_Zona', false);
$pMar = fGetParam('tac_CodMarca', false);
$pPrd = fGetParam('tac_CodProducto', false);
$pCaj = fGetParam('tac_CodProducto', false);

/*							Armar Condicion SQL*/
$gsCond = "";
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pSem != false) ? " txp_Semana  = " . $pSem:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pPue != false) ? " txp_PueRecepcion  = " . $pPue:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pFec != false) ? " txp_Fecha  = '" . fText2Fecha($pFec,'Y-m-d') ."'" : " true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pZon != false) ? " txp_Zona  = " . $pZon:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pPro != false) ? " txp_CodProducto  = " . $pZon:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pMar != false) ? " txp_CodMarca  = " . $pZon:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pCaj != false) ? " txp_CodCaja  = " . $pZon:" true ");
set_time_limit (0) ;
$db->debug=fGetParam("pAdoDbg", 0);
$rs = fDefineQry($db, $gsCond );
$tplFile = 'LiEmTr_dettarjdiario_total_prec.tpl';
$Tpl->assign("gsNumCols", 15);
$Tpl->assign("gsEmpresa", $_SESSION["g_empr"]);
$gsSubt= "DETALLE DE TARJAS<br>" . fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
$Tpl->assign("gsNumCols", 15);
$aDet =& SmartyArray($rs);
if (count($aDet) <1){
   fErrorPage("NO EXISTE INFORMACION PARA LA CONDICION ESPECIFICADA");
}
$Tpl->assign("agData", $aDet);
//if (!$Tpl->is_cached($tplFile)) {
//}
$Tpl->display($tplFile);

?>
