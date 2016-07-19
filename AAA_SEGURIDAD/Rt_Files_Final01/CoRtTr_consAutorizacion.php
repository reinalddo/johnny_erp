<?php
/**
* Codigo para generar la consulta especifica de una autorizacion
* Recibe como parametros :
* 	- Id de la autorizacion
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         25/May/09
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
function fConsultarAutorizacion(){
    global $db, $cla, $olEsq;
    $ID = fGetParam('idAutoriz', '0');
    $opc = fGetParam('pOpc', 1);
    $proveedor = fGetParam('pProv', 0);
    $tipoComp = fGetParam('pTipoComp', '');
    $numComp = fGetParam('pNumComp', 0);
    $establec = fGetParam('pEstablecimiento', 0);
    $ptoEmision = fGetParam('pPuntoEmision', 0);
    
    //echo $opc."****".$ID."-----------";
    $trSql = "";
    if (1 == $opc){
	$trSql = "select * from genautsri where aut_autsri='" . $ID . "' and
		    aut_IdAuxiliar=".$proveedor." and aut_TipoDocum=".$tipoComp."
		    and aut_establecimiento=".$establec." and aut_puntoEmision=".$ptoEmision;
	//echo "1***".$trSql;
    }
    else{
	$trSql = "select aut_AutSri,aut_FecEmision,aut_FecVigencia/*,aut_NroInicial,aut_NroFinal*/
		    from genautsri where
		        aut_IdAuxiliar=".$proveedor." and aut_TipoDocum=".$tipoComp." and 
		        ".$numComp." between aut_NroInicial and aut_NroFinal
			and aut_establecimiento=".$establec." and aut_puntoEmision=".$ptoEmision."
		    union select '-','',''";
	//echo "2***".$trSql;
    }


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
$ogSal = fConsultarAutorizacion();
print(json_encode($ogSal));
?>
