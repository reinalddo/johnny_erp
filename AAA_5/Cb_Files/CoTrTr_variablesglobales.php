<?php
/**
* Codigo para consultar las variables de sesion que contienen permisos
* Recibe como parametros :
* 	- clave
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         22/Junio/09
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
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

/**
*   Anulacion de movimientos
**/
function fgetParamatro(){
    //global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    //echo print_r($_SESSION)."-------";
    //echo $_SESSION["atr"]["CoTrTr"]["ANU"]."****";
    //se realizan validacion para saber si el usuario tiene privilegios para ejecutar la opcion
    
    //echo "<br/>".$_SESSION["atr"]["CoTrTr"][$agPar['pKey']]."---".$agPar['pKey'];
    
    if ("user" != $agPar['pKey']){
        $bool = $agPar['pBool'];
        if (1 == $bool){
            $valor = $_SESSION["atr"]["CoTrTr"][$agPar['pKey']];
            if (1 == $valor)
                $valor = true;
            else
                $valor = false;
            //$valor = ((is_null($_SESSION["atr"]["CoTrTr"][$agPar['pKey']]) || 0 == $_SESSION["atr"]["CoTrTr"][$agPar['pKey']]) ? false : $_SESSION["atr"]["CoTrTr"][$agPar['pKey']]);
        }else{
            $valor = (is_null($_SESSION["atr"]["CoTrTr"][$agPar['pKey']]) ? 0 : $_SESSION["atr"]["CoTrTr"][$agPar['pKey']]);
        }
        return array("success"=>true,"totalRecords"=>1,"message"=>"","valor"=>$valor);
    }else{
        return array("success"=>true,"totalRecords"=>1,"message"=>"","codUser"=>$_SESSION['g_user'],"usuario"=>$_SESSION['nomb']);
    }
}
/********************************************************************************************************
 *					Inicio de Proceso
 *
 ********************************************************************************************************/
//fDbgContab("------------------------------------");
$cla = NULL;
$agPar = NULL;
//$igSecue= 1;
$ogDet=array();
$ogResp=fgetParamatro();
/*$ogJson = new Services_JSON();
print($ogJson->encode($ogResp));*/
print(json_encode($ogResp));
?>
