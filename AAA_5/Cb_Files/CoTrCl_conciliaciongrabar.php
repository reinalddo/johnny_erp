<?php
/**
* Codigo para actualizar estado en libros y fecha de conciliacion de movimientos
* Recibe como parametros :
* 	- Numero de Registro,
* 	- la Cuenta de Banco, auxiliar,
* 	- estado en libros,
* 	- fecha de libros,
* 	- fecha de corte
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         08/05/09
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
function fActualizaMov($pRNum, $pConcep=false){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    $trSql = "update condetalle set det_EstLibros=". $agPar["estLibros"] .",
                                    det_FecLibros='". $agPar["fecLibros"] ."'
             where det_RegNumero=". $agPar["regNumero"] ." and det_IDAuxiliar=". $agPar["auxil"] ."
             and det_CodCuenta='". $agPar["cuenta"] ."'";
    	if(fGetParam("pAppDbg",0)){
		print_r($agPar);
	}
    //echo $trSql;
    if(!$db->Execute($trSql)) {
	    $olResp= array("failure"=>true,"totalRecords"=>0 );
            return $olResp;
    }
    
    if (1 == $agPar["estLibros"])
        $signo = "+";
    else
        $signo = "-";
        
        
    $trSql = "update conconciliacion inner join condetalle on conconciliacion.con_CodCuenta=condetalle.det_CodCuenta
                    and conconciliacion.con_CodAuxiliar=condetalle.det_IDAuxiliar
        set con_DebIncluidos=con_DebIncluidos" . $signo . "ifnull(det_ValDebito,0)
        where con_CodCuenta='". $agPar["cuenta"] ."' and con_CodAuxiliar=". $agPar["auxil"] ."
        and con_FecCorte = '". $agPar["fecCorte"] ."'
        and det_RegNumero=". $agPar["regNumero"] ." and det_ValDebito<>0";// and det_tipocomp<>'AN'";
    if(!$db->Execute($trSql)) {
            $olResp= array("failure"=>true,"totalRecords"=>0 );
            return $olResp;
    }
    $trSql = "update conconciliacion inner join condetalle on conconciliacion.con_CodCuenta=condetalle.det_CodCuenta
                and conconciliacion.con_CodAuxiliar=condetalle.det_IDAuxiliar 
        set con_CreIncluidos=con_CreIncluidos" . $signo . "ifnull(det_ValCredito,0)
        where con_CodCuenta='". $agPar["cuenta"] ."' and con_CodAuxiliar=". $agPar["auxil"] ."
        and con_FecCorte = '". $agPar["fecCorte"] ."'
        and det_RegNumero=". $agPar["regNumero"] ." and det_ValCredito<>0";// and det_tipocomp<>'AN'";
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
$ogResp=fActualizaMov($ilReg);
/*$ogJson = new Services_JSON();
print($ogJson->encode($ogResp));*/
print(json_encode($ogResp));
?>
