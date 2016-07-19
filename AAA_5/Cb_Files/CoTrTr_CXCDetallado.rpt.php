<?php
/*    Reporte - Cuadro de Cuentas por Pagar
 */
// ob_start("ob_gzhandler");
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
        $this->template_dir = 'template';
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
$pCue_CodCuenta = fGetParam('Cue_CodCuenta',false);
$pidProvFact = fGetParam('idProvFact','');
$pcom_FecContab = fGetParam('com_Fec_Contab','');
$psal = fGetParam('sal',''); // Si envia valor es para los que tienen saldo mayor a 0 (pendientes)


$subtitulo="FACTURAS PENDIENTES";
$Tpl->assign("subtitulo",$subtitulo);


/* para tener el saldo por cliente y dias de vencimiento*/
$sSql = "select  CHE
		  ,det_TipoComp
		  ,det_NumComp
		  ,CONCAT(det_TipoComp, '-', det_NumComp) AS comp
		  ,FCO
		  ,com_FecContab
		  ,CCU
		  ,CUE
		  ,CAU
		  ,DES
		  ,det_ValDebito
		  ,det_ValCredito
		  ,SAL
		  ,det_Glosa
		  ,com_Concepto
		  from v_consalcxc
		  JOIN condetalle on det_CodCuenta=CCU AND det_IDAuxiliar=CAU and det_NumCheque = CHE
		  JOIN concomprobantes on com_RegNumero = det_RegNumero
         WHERE det_codcuenta in (   SELECT Cue_CodCuenta FROM concuentas
				    WHERE Cue_CodCuenta LIKE CONCAT((SELECT par_Valor1
								     FROM genparametros
								     WHERE par_Clave = 'CUCXC'), '%')
   			   ) ";
$sSql .= ($pCue_CodCuenta ? " and det_codcuenta = ". $pCue_CodCuenta  : "  " );

// $sSql .= ($pcom_FecContab ? " and com_FecContab <= ". $pcom_FecContab  : "  " );
$sSql .= ($pidProvFact ? " and CAU = ". $pidProvFact  : "  " );
$sSql .= ($psal ? " and SAL > 0 "  : "  " );
$sSql .= " ORDER BY CUE,DES,CHE,com_FecContab";

$rs = $db->execute($sSql . $slFrom);
    
if($rs->EOF){
    fErrorPage('','NO SE GENERARON CUENTAS POR PAGAR', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    //$Tpl->assign("agSaldos", $agSaldos);
    //$Tpl->assign("agValor", $agValor);
    $Tpl->assign("agData", $aDet);
    $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
    $Tpl->assign("slPiePag", $slPiePag);
    if (!$Tpl->is_cached('CoTrTr_CXCDetallado.tpl')) {
            }
            $Tpl->display('CoTrTr_CXCDetallado.tpl');
}
?>