<?php
/**
* Codigo para ingresar cabecera de conciliacion
* Recibe como parametros :
* 	- la Cuenta de Banco, auxiliar,
* 	- fecha de corte,
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         11/05/09
* 				
*/
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
//include_once("../LibPhp/ConLib.php");
include_once("GenUti.inc.php");
//include_once("../LibPhp/ConTranLib.php");
//include_once("../LibPhp/ConTasas.php");
$gbTrans	= false;
$db = Null;
$cla=null;
$olEsq=null;
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

/**
*   Contabilizacion de una transaccion de Cartera
*   @param      $pRNum      Integer     Nùmero de registro del comprobante
*   @param      $pConcep    String      Concepto de la transaccion
**/
function fIngresaCabecera($pRNum, $pConcep=false){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    
    if(fGetParam("pAppDbg",0)){
		print_r($agPar);
	}
    
    $trSql = "SELECT con_FecCorte
            FROM conconciliacion 
            WHERE con_CodAuxiliar = '" . $agPar["auxil"] ."' and con_FecCorte='". $agPar["fecCorte"] ."'";
	if(fGetParam("pAppDbg",0)){
		print($trSql);
	}
    
    $rs = $db->execute($trSql);
    
    if(!$rs->EOF){ //fErrorPage('',"NO SE PUDO DEFINIR LA TRANSACCION " . $pRNum);
        $olResp= array("success"=>true,"totalRecords"=>2);
        return $olResp;
    }
    $trSql = "insert conconciliacion (con_CodCuenta,con_CodAuxiliar,con_FecCorte,con_Ususario,con_Estado)
                values ('". $agPar["cuenta"] ."',". $agPar["auxil"] .",'". $agPar["fecCorte"] ."',
                '".$_SESSION['g_user']."',0)";

    	if(fGetParam("pAppDbg",0)){
		print($trSql);
	}
    //echo $trSql;
    if(!$db->Execute($trSql)) {
	    $olResp= array("failure"=>true,"totalRecords"=>0 );
            return $olResp;
    }
    //fDbgContab("Paso 1aa " . $sqltext);
    
    $olResp= array("success"=>true,"totalRecords"=>1);
    return $olResp;
}


/**
*       Graba texto en un archivolog para depurar este script
*
*/
function fDbgContab($pMsj){
    error_log($pMsj . " \n", 3,"/tmp/dimm_log.err");
    //echo $pMsj . " <br>";
}
/********************************************************************************************************
 *					Inicio de Proceso
 *
 ********************************************************************************************************/
fDbgContab("------------------------------------");
$cla = NULL;
$agPar = NULL;
//$igNumChq = NULL;
//$igSecue= 1;
$ogDet=array();
$ogResp=fIngresaCabecera($ilReg);
/*$ogJson = new Services_JSON();
print($ogJson->encode($ogResp));*/
print(json_encode($ogResp));
?>
