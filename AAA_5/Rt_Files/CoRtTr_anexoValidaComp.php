<?php
/**
* Codigo para validar si un numero de comprobante ya ha sido ingresado
* Recibe como parametros :
* 	- Tipo de Comprobante
* 	- Codigo de Proveedor
* 	- Establecimiento
* 	- Punto de emision
* 	- Secuencial
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         28/May/09
* 				
*/
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("GenUti.inc.php");
include_once(RelativePath . "/LibPhp/MisRuc.php");
//include_once("MisRuc.php");
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
*   Valida si ya se ha utilizado un numero de comprobante dependiendo del tipo y proveedor
*
**/
function fValidar(){
    global $db, $cla, $olEsq;
    $tipoComp = fGetParam('pTipoComp', '0') ;
    $prov = fGetParam('pProvFact', '0') ;
    $estab = fGetParam('pEstab', '0') ;
    $puntoEmi = fGetParam('pPuntoEmi', '0') ;
    $secuen = fGetParam('pSecuencial', '0') ;
    $id = fGetParam('pId', '0');
    if ("" == $id)  $id = 0;
    $trSql = "select concat('Anexo: ',ID,' - Fecha de Registro: ',fechaRegistro,' - Concepto: ',concepto) msg 
                from fiscompras
                where tipoComprobante = ".$tipoComp." and idProvFact = ".$prov." and
                establecimiento = '".$estab."' and puntoEmision='".$puntoEmi."' and secuencial = '".$secuen."'
                and ID <> ".$id."
                union select '-'";

    //echo $trSql;
    $alInfo=$db->GetRow($trSql);//toda la informacion del anexo
	
    //$alInfo["ban_PxmoChq"]=$ilNumChq;
    return array("info" => $alInfo );
    
}

/**
*   Valida Ruc de proveedores
*
**/

function fValidarRUC()
{
    //global $fiscompras;
	//echo "<br> 1<br>";
    $idProv = fGetParam('pIdProv', '0');
    $rucProv = fGetParam('pRucProv', '0');
    $idProvFact = fGetParam('pIdProvFact', '0');
    $rucProvFact = fGetParam('pRucProvFact', '0');
    
    $mensaje = "";
    $blRcode = fValidaRuc($rucProv, "R");
    //echo "<br> 2<br>".$rucProv."<br>".$rucProvFact."<br>";
    if($blRcode < 0 ) {
	//echo "<br> pr: $blRcode <br>";
	//$mensaje = "RUC DE PROVEDOR CONTABLE INVALIDO (" . $blRcode . "). ";
	$mensaje = "RUC DE PROVEDOR CONTABLE INVALIDO (" . $rucProv . "). ";
    }
    $blRcode2 = fValidaRuc($rucProvFact, "R");
    //echo "<br> 3 / $blRcode2 <br>";
    if($blRcode2 < 0) {
	//echo "<br> fa: $blRcode <br>";
	$mensaje .= "RUC DE PROVEEDOR FISCAL INVALIDO (" . $blRcode2 . ")";
    }
    return (array("success"=>true,"totalRecords"=>0,"msg"=>$mensaje,"prov"=>$blRcode,"provFact"=>$blRcode2));
}

/**
*   Valida si un comprobante de retencion no ha sido utilizado
*
**/
function fValidarRetencion(){
    global $db, $cla, $olEsq;
    $establecimiento = fGetParam('pEstab', '0') ;
    $puntoEmision = fGetParam('pPtoEmi', '0') ;
    $secuencial = fGetParam('pSecuencial', '0') ;
    
    $trSql = "select concat('Retencion ya fue utilizada en Anexo: ',ID,', Concepto: ',concepto) msg from fiscompras
		where estabRetencion1=".$establecimiento." and puntoEmiRetencion1=".$puntoEmision." and
		secRetencion1=".$secuencial."
		union
		select '-'";

    echo $trSql;
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

$opc = fGetParam('pOpc', 1);
//echo "<script>alert('$opc')</script>";
//echo $opc;
if (1 == $opc)
    $ogSal = fValidar();
elseif (2 == $opc)
    $ogSal = fValidarRUC();
else
    $ogSal = fValidarRetencion();
print(json_encode($ogSal));
?>
