<?php
/*
*   Lista de precios por Contenedor, marca
*   Tabla pivoteada de cantidades embarcadas, generadas en columnas por producto y marca
*   @created    Ene/22/08
*   @author     fah
*   @
*/
ob_start("ob_gzhandler");
error_reporting(E_ALL);
include("adodb.inc.php");
include("General.inc.php");
include("GenUti.inc.php");
include("adoConn.inc.php");
include "pivottable.inc.php";
require('Smarty.class.php');
include('tohtml.inc.php'); 
if (fGetParam("pOut",false) == "X") header("Content-Type:  application/vnd.ms-excel");
/*header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
*/
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
$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetParam("pAppDbg",false);
/*
 ********************************
*/


/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function &fDefineQry(&$db, $pQry=false){
   global $rsH, $rsG, $gsCond, $Tpl, $db, $pEmb;
   set_time_limit (0) ;
   $rsG = NULL;
   $rsH = NULL; 
 
   $Tpl->assign("asHoy", date("d /M /y, g:i a"));
      
   $sql = " select txp_NombBuque, txp_refoperativa, txp_producto, txp_Marca, caj_Descripcion,
            txp_Precunit,
            sum(txp_cantneta) as tmp_Cantidad, sum(txp_cantneta * txp_precunit) as tmp_Valor
            from v_opetarjexpand join liqcajas on caj_codcaja = txp_codcaja
            join opecontenedores on cnt_embarque = txp_refOperativa and cnt_serial = txp_contenedor
            where " . $gsCond. "
            group by 1,2 ,3,4, 5,6
            order by 1,2 ,3,4, 5,6
            " ;
   $rs=  $db->Execute($sql );           
   return $rs;
}
	
/**
 *		Procesamiento
*/
$f1 = fopen("../css/report.css", "r");
$style_pr = fread ($f1, filesize("../css/report.css"));
$f2 = fopen("../css/AAA_basico.css", "r");
$style_sc = fread ($f2, filesize("../css/AAA_basico.css"));
$gsCond="";
$pAnio = fGetParam('pAnio', false);
$pSem = fGetParam('pSem', false);
$pEmb = fGetParam('pEmb', false);
$pCont = fGetParam('pCont', false);
$pDesti = fGetParam('pDest', false);
$pConsig = fGetParam('pClie', false);
$pDestFi = fGetParam('pDestFi', false);
if ($pCont == -9999 || $pCont == ' TODOS') $pCont = false;
if ($pEmb == -9999 || $pEmb == ' TODOS') $pEmb = false;
if ($pCont == -9999 || $pCont == ' TODOS') $pCont = false;
if ($pDesti == -9999 || $pDesti == ' TODOS') $pDesti = false;
if ($pConsig == -9999 || $pConsig == ' TODOS') $pConsig = false;
if ($pDestFi == -9999 || $pDestFi == ' TODOS') $pDestFi = false;
//$gsCond = ($pAnio != false) ?  " emb_anooperacion   = " . $pAnio :"";
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pSem != false) ? " txp_Semana  = " . $pSem:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pEmb != false) ? " txp_RefOperativa  = " . $pEmb:" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pCont != false )? " txp_contenedor  = '" . $pCont ."'" :" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pDesti != false )? " cnt_destino  = '" . $pDesti ."'" :" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pConsig != false )? " cnt_consignatario  = '" . $pConsig ."'" :" true ");
$gsCond .= (strlen($gsCond )>0 ? " AND " : "") . (($pDestFi != false )? " cnt_destifinal  = '" . $pDestFi ."'" :" true ");

$rs = &fDefineQry($db );

$tplFile = 'OpTrTr_resprecios.tpl';
//rs2html($rs);
$rs->MoveFirst();
$aDet =& SmartyArray($rs);
//obsafe_print_r($aDet);
$Tpl->assign("pSem", $pSem);
$Tpl->assign("style_sc", $style_sc);
$Tpl->assign("style_pr", $style_pr);
$Tpl->assign("agData", $aDet);
//if (!$Tpl->is_cached($tplFile)) {
//}
$Tpl->display($tplFile);
?>
