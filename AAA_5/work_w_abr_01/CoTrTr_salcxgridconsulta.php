<?php
/**
* Codigo consultar las cuentas (cobrar o pagar) 
* Recibe como parametros :
* 	- tipo de cuenta
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         15/May/09
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
function fConsulta(){
    global $db, $cla, $olEsq;
    $tipo = fGetParam('pTipo', '0') ;
    
    $trSql = "SELECT det_codcuenta AS codcuenta, /*concat(det_Codcuenta, ' ', cue_Descripcion)*/
            replace(replace(replace(replace(replace(replace(concat(cue_Descripcion, ' - ', det_Codcuenta),'Ñ','N'),'á','a'),'é','e'),'í','i'),'ó','o'),'ú','u') as nombrcue
            FROM concomprobantes join condetalle on det_regnumero = com_regnumero 
            JOIN concuentas on cue_codcuenta = det_codcuenta 
            JOIN genparametros on par_clave = 'CUCX".$tipo."' 
            LEFT JOIN v_genvariables on iab_objetoid = det_idauxiliar and iab_variableid = 11 
            WHERE det_codcuenta like concat(par_valor1 , '%') 
            GROUP BY 1,2
            ORDER BY det_codcuenta" ;

    //echo $trSql;
    $alInfo=$db->GetArray($trSql);//toda la informacion del anexo
    //print_r($alInfo);
    //$alInfo["ban_PxmoChq"]=$ilNumChq;
    //return array("info" => $alInfo);
    return $alInfo;
    
}
/********************************************************************************************************
 *					Inicio de Proceso
 *
 ********************************************************************************************************/
$cla = NULL;
$alPar = NULL;
//$ilNumChq = NULL;
//$ilSecue= 1;
$ogSal = fConsulta();
print(json_encode($ogSal));
?>
