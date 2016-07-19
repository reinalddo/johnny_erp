<?php
/**
* Codigo para grabar valores de liquidaciones de productores por rubro.
* Recibe como parametros :
*       - semana,
* 	- productor,
* 	- rubro,
* 	- valor
* @package      AAA
* @subpackage   Liquidaciones
* @Author       Gina Franco
* @Date         08/Junio/09
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
*   Graba valores de rubros de liquidaciones
**/
function fGrabaValor(){
    global $db, $agPar;
    $agPar = fGetAllParams("ALL");
    //verifica si no esta creado el registro
    $trSql = "select des_ID from liqdefaults
            where des_semana=". $agPar["pSemana"] ." and des_Productor=". $agPar["pProductor"] ."
             and des_rubro=". $agPar["pRubro"] ."";
             
    $rs = $db->execute($trSql);
	//echo $rs;
    $rs->MoveFirst();
    $registro = $rs->FetchNextObject(false);
    $slId = $registro->des_ID;
    //echo "--".$slId;
    if ("" == $slId){ 
        $trSql = "insert into liqdefaults (des_Semana, des_Productor, des_Rubro, des_Valor, des_Usuario)
	values
	( ". $agPar["pSemana"] .", ". $agPar["pProductor"] .", ". $agPar["pRubro"] .", ". $agPar["pValor"] .",
        '" . $_SESSION['g_user'] . "')";
    }else{
        $trSql = "update liqdefaults set des_Valor=". $agPar["pValor"] ."
             where des_semana=". $agPar["pSemana"] ." and des_Productor=". $agPar["pProductor"] ."
             and des_rubro=". $agPar["pRubro"] ."";
    }
    
    
    	if(fGetParam("pAppDbg",0)){
		print_r($agPar);
                echo $trSql;
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
$ogResp=fGrabaValor();
/*$ogJson = new Services_JSON();
print($ogJson->encode($ogResp));*/
print(json_encode($ogResp));
?>
