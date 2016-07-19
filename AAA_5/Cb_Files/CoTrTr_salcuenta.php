<?php
/**
* Codigo para generar la transaccion contable a partir de la Transaccion de cartera
* Recibe como parametros :
* 	- Tipo de Comprobante,
* 	- la Cuenta de Banco, auxiliar,
* 	- valor,
* 	- fecha de emision,
* 	- codigo de CXC/CXP,
* 	- auxiliar Proveedor/Cliente,
* 	- detalle de facturas  o documentos a liquidar
* @package      AAA
* @subpackage   Contabilidad
* @Author       Fausto Astudillo
* @Date         25/03/09
* 				
*/
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("GenUti.inc.php");
$gbTrans	= false;
$db = Null;
$cla=null;
$olEsq=null;
$db = &ADONewConnection('mysql');
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

/**
*   Calcula el saldo de una cuenta contable y su auxiliar
*   @param      $pRNum      Integer     Nùmero de registro del comprobante
*   @param      $pConcep    String      Concepto de la transaccion
**/
function fProceso(){
    global $db, $cla, $olEsq;
    $slCta = fGetParam('pCta', '-') ;
    $slAux = fGetParam('pAux', '0');
    $trSql = "SELECT sum(det_valdebito - det_Valcredito) as txt_Saldo
            FROM concomprobantes join condetalle on det_codempresa = com_codempresa AND det_regnumero = com_regnumero
            WHERE det_codcuenta = '". $slCta . "' AND det_idauxiliar = " . $slAux;

    $flSal = NZ($db->GetOne($trSql),0); // Saldo de la cuenta
    if (fGetParam("pBan", false)){
	$alInfo=$db->GetRow("SELECT * FROM v_baninfo where ban_id = ".  fGetParam('pAux', '0')); // INfo adicional del banco, secuencias, num cta , etc
	$ilNumChq = $db->GetOne("SELECT ifNULL(max(det_numcheque)+1, " . $alInfo["ban_ChqInicio"] . ")  FROM condetalle ".
				    "WHERE det_codcuenta = '" . $slCta ."' AND det_idauxiliar = " . $slAux .
					" AND  det_numcheque BETWEEN " . $alInfo["ban_ChqInicio"] . " AND " . $alInfo["ban_ChqFin"] );
	$alInfo["ban_PxmoChq"]=$ilNumChq;
	return array("sal"=> $flSal, "info" => $alInfo );
    }
    
}
/********************************************************************************************************
 *					Inicio de Proceso
 *
 ********************************************************************************************************/
$cla = NULL;
$alPar = NULL;
$ilNumChq = NULL;
$ilSecue= 1;
$ogSal = fProceso();
print(json_encode($ogSal));
?>
