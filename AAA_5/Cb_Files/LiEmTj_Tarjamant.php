<?php
/**
* Codigo para generar el mantenimiento de tarja (ingreso, modificacion)
* Recibe como parametros todos los campos de la tabla de tarja
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         16/04/09
* 				
*/
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("../LibPhp/ConLib.php");
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
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
*   Define que instruccion ejecutar
*   @param      $pRNum      Integer     Nùmero de registro del comprobante
*   @param      $pConcep    String      Concepto de la transaccion
**/
function fProceso($pRNum, $pConcep=false){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    /*$trSql = "insert into zz_ame.liqtarjacabec 
	(tac_CodEmpresa, tar_NumTarja, tac_RefOperativa, tac_UniProduccion, tac_Semana, tac_Fecha,
            tac_Embarcador, tac_CodEmpaque, tac_Zona, tac_CodOrigen, tac_Transporte, tac_RefTranspor, 
            tac_Bodega, tac_Piso, tac_Contenedor, tac_Sello, tac_Hora, tac_HoraFin, tac_CodCartonera, 
            tac_ResCalidad, tac_GrupLiquidacion, tac_Estado, tac_NumLiquid, tac_PueRecepcion, tac_Observaciones, 
            tac_Evaluada, tac_FecDigitacion, tac_CodEvaluador, tac_Usuario
	)
	values
	(tac_CodEmpresa, tar_NumTarja, tac_RefOperativa, tac_UniProduccion, tac_Semana, tac_Fecha,
            tac_Embarcador, tac_CodEmpaque, tac_Zona, tac_CodOrigen, tac_Transporte, tac_RefTranspor, 
            tac_Bodega, tac_Piso, tac_Contenedor, tac_Sello, tac_Hora, tac_HoraFin, tac_CodCartonera, 
            tac_ResCalidad, tac_GrupLiquidacion, tac_Estado, tac_NumLiquid, tac_PueRecepcion, tac_Observaciones, 
            tac_Evaluada, tac_FecDigitacion, tac_CodEvaluador, tac_Usuario
	)";*/
	if(fGetParam("pAppDbg",0)){
		print_r($agPar);
	}
    
    //$rs = $db->execute($trSql);
    fDbgContab("Paso 1a " . $trSql);
    
    print_r($agPar);
    
    $olResp= array("success"=>true,"totalRecords"=>1, "com_RegNumero"=>$ilReg,
		"com_TipoComp"=>$agPar["com_TipoComp"], "com_NumComp"=>$ilNumComp, "com_NumCheque"=>$igNumChq );
    return $olResp;
}
/**
*       Graba texto en un archivolog para depurar este script
*
*/
function fDbgTarja($pMsj){
    error_log($pMsj . " \n", 3,"/tmp/dimm_log.err");
    //echo $pMsj . " <br>";
}
/********************************************************************************************************
 *					Inicio de Proceso
 *
 ********************************************************************************************************/
fDbgTarja("------------------------------------");
$cla = NULL;
$agPar = NULL;
$igNumChq = NULL;
$igSecue= 1;
$ogDet=array();
$ogResp=fProceso($ilReg);
/*$ogJson = new Services_JSON();
print($ogJson->encode($ogResp));*/
print(json_encode($ogResp));
?>