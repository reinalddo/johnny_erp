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
function fValidaIngreso($pRNum, $pConcep=false){
    //global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    //$agPar = fGetAllParams("ALL");
    //echo print_r($_SESSION)."-------";
    //echo $_SESSION["atr"]["CoTrTr"]["ANU"]."****";
    //se realizan validacion para saber si el usuario tiene privilegios para ejecutar la opcion
    
    if (1 != $_SESSION["atr"]["LiLiLi"]["DES"])
        return array("failure"=>true,"totalRecords"=>0,"message"=>"Usted no tiene privilegios para ejecutar esta opcion");
    else
        return array("success"=>true,"totalRecords"=>1,"message"=>"");
    
}

function fSemanaCerrada(){
    global $db;
    
    $semana = fGetParam('pSemana', 0);
    
    $trSql = "select case when per_Bandera=5 then 'cerrado' else 'abierto' end estado 
                from conperiodos
                where per_Aplicacion='LI' and per_NumPeriodo=".$semana;
                
    
    $alInfo=$db->GetRow($trSql);//toda la informacion del anexo
	
    //$alInfo["ban_PxmoChq"]=$ilNumChq;
    return array("info" => $alInfo );
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
$opcion = fGetParam("pOpc",1);
//$igSecue= 1;
$ogDet=array();
if (1 == $opcion)
    $ogResp=fValidaIngreso($ilReg);
else
    $ogResp=fSemanaCerrada();
/*$ogJson = new Services_JSON();
print($ogJson->encode($ogResp));*/
print(json_encode($ogResp));
?>
