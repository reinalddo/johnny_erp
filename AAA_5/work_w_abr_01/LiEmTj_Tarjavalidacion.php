<?php
/**
* Codigo para validar si un numero de tarja ya ha sido utilizado
* Recibe como parametros :
* 	- Numero de Tarja
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         22/04/09
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
    $numTarja = fGetParam('numTarja', '0') ;
    $Semana = fGetParam('semana', '0') ;
    $trSql = "select  count(tar_NumTarja) tarja,tac_Semana from liqtarjacabec
            where tar_NumTarja=".$numTarja;//." and tac_Semana=".$Semana;


    $alInfo=$db->GetRow($trSql);//toda la informacion del anexo
	
    //$alInfo["ban_PxmoChq"]=$ilNumChq;
    return array("info" => $alInfo );
    
}
/********************************************************************************************************
 *					Inicio de Proceso
 *
 ********************************************************************************************************/
$cla = NULL;
$alPar = NULL;
//$ilNumChq = NULL;
//$ilSecue= 1;
$ogSal = fProceso();
print(json_encode($ogSal));
?>
