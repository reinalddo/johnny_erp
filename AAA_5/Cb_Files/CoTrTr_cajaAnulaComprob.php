<?php
/**
* Codigo para anulacion directa de movimientos, actualiza debito y credito a 0, y Glosa actualiza con 'ANULADO'
* Recibe como parametros :
* 	- tipo de comprobante,
* 	- numero de comprobante,
* 	- auxiliar
* 	- Fecha Contable
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         19/Mayo/09
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
*   Anulacion de movimientos
**/
function fActualizaMov($pRNum, $pConcep=false){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    //echo print_r($_SESSION)."-------";
    //echo $_SESSION["atr"]["CoTrTr"]["ANU"]."****";
    
    //echo date('Y-m-d')."------".$agPar["pFecContab"];
    //se realizan validacion para saber si el usuario tiene privilegios para ejecutar la opcion
    //y de que los comprobantes a anular solo sean del dia actual
    if (1 != $_SESSION["atr"]["CoTrTr"]["ANU"])
        return array("failure"=>true,"totalRecords"=>1,"message"=>"Usted no tiene privilegios para ejecutar esta opcion");
    elseif (date('Y-m-d') != date($agPar["pFecContab"]))
            return array("failure"=>true,"totalRecords"=>1,"message"=>"Solo se pueden anular comprobantes de la fecha actual.");
    
    //return array("success"=>true,"totalRecords"=>1);
    
    
    $trSql = "update concomprobantes set com_EstOperacion=9, com_Concepto=concat('Anulado. ',com_Concepto)
                where com_TipoComp='". $agPar["pTipoComp"] ."' and com_NumComp=". $agPar["pNumComp"] ."";
               
    if(fGetParam("pAppDbg",0)){
            print_r($agPar);
    }
    echo $trSql;
    if(!$db->Execute($trSql)) {
	    $olResp= array("failure"=>true,"totalRecords"=>0 );
            return $olResp;
    }
    //fDbgContab("Paso 1aa " . $sqltext);
    
    $trSql = "update condetalle set det_ValDebito = 0, det_ValCredito = 0
		, det_Glosa = concat('Anulado. ',det_Glosa),
                det_EstLibros = 1, det_FecLibros = '". date("Y-m-d") ."'
        where det_TipoComp='". $agPar["pTipoComp"] ."' and det_NumComp=". $agPar["pNumComp"] ."";
                
    if(!$db->Execute($trSql)) {
	    $olResp= array("failure"=>true,"totalRecords"=>0 );
            return $olResp;
    }
    
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
$ogResp=fActualizaMov($ilReg);
/*$ogJson = new Services_JSON();
print($ogJson->encode($ogResp));*/
print(json_encode($ogResp));
?>
