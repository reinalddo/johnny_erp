<?php
/**
* Codigo para grabar asociaciones entre IB y OC
* Recibe como parametros :
* 	- enlTipo,      //tipo de comprobante a asociar por ej: OC
* 	- enlID,        //numComp de OC
* 	- enlTipoComp,  //tipo de comprobante por ej: IB
* 	- enlNumComp    //numComp de IB
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         15/05/09
* @rev	esl	20/12/2010	Parametrizar base para inventario.
* @rev	esl	09/02/2011	Que no guarde en Costafrut el IB sino en la empresa que está activa al hacer el enlace
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
*   @param      $pRNum      Integer     Numero de registro del comprobante
*   @param      $pConcep    String      Concepto de la transaccion
**/
function fIngresar($pRNum, $pConcep=false){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq, $baseInv;
    
    $baseInv = "09_inventario.";
    //@rev	esl	20/12/2010	Parametrizar base para inventario.
    $sql = "SELECT par_Valor1 FROM genparametros WHERE par_Clave = 'IDATO' AND par_Secuencia = 1";
    $rs = $db->execute($sql);
    $r = $rs->fetchRow(); 
    $baseInv = $r['par_Valor1'];
    // print($baseInv);
        
    
    $agPar = fGetAllParams("ALL");
    $trSql = "insert ".$baseInv.".invenlace
        (enl_CodEmpresa,enl_RegNumero,enl_Secuencia,enl_RegNumero2,enl_Secuencia2,enl_OpCode, enl_Usuario)
        value ('". $_SESSION['g_dbase'] ."',". $agPar["enlRegNum"] .",". $agPar["enlSecuencia"] .",
		". $agPar["enlRegNum1"] .",". $agPar["enlSecuencia1"] .",'-','" . $_SESSION['g_user'] . "')";

    	if(fGetParam("pAppDbg",0)){
		print_r($agPar);
	}
    //echo $trSql;
    if(!$db->Execute($trSql)) {
	    $olResp= array("failure"=>true,"totalRecords"=>0 );
            return $olResp;
    }
    //fDbgContab("Paso 1aa " . $sqltext);
    fContabilizar($agPar["enlRegNum1"],$agPar["enlSecuencia1"],$agPar["enlRegNum"],$agPar["enlSecuencia"]);
    
    // Copiar IB de inventario a costafru despues de que fue actualizado el costo
    fCopiarIB($agPar["enlRegNum1"]);
    
    $olResp= array("success"=>true,"totalRecords"=>1);
    return $olResp;
    
    
    
}


function fCopiarIB($pRegIB){
    global $db,$agPar,$baseInv;
    //Tipo y numero de comprobante que se va a copiar
    $Sql="select com_TipoComp, com_NumComp from ".$baseInv.".concomprobantes where com_RegNUmero = ".$pRegIB;
    $comp=$db->GetRow($Sql);
    $tipoComp=$comp['com_TipoComp'];
    $numComp =$comp['com_NumComp'];
    
    //Verificar si ya esta copiado el comprobante
    $Sql="select com_RegNumero from concomprobantes where com_TipoComp = '".$tipoComp."' and com_NumComp = ".$numComp;
    $rsexiste = $db->execute($Sql);
    
    if($rsexiste->EOF){
	// Nuevo comprobante
	print("Nuevo comprobante");
	
	$Sql = "INSERT INTO concomprobantes (com_CodEmpresa,com_Establecimie,com_PtoEmision,com_TipoComp,com_NumComp,com_FecTrans,com_FecContab,com_FecVencim,
			com_emisor,com_CodReceptor,com_Receptor,com_Concepto,com_Valor,com_TipoCambio,com_Libro,com_NumRetenc,com_RefOperat,
			com_EstProceso,com_EstOperacion,com_NumProceso,com_CodMoneda,com_Usuario,com_NumPeriodo,com_PerOperativo,com_Vendedor,com_TsaImpuestos
			)			    
		SELECT ".$baseInv.".concomprobantes.com_CodEmpresa
				,".$baseInv.".concomprobantes.com_Establecimie,".$baseInv.".concomprobantes.com_PtoEmision,".$baseInv.".concomprobantes.com_TipoComp,
				".$baseInv.".concomprobantes.com_NumComp,".$baseInv.".concomprobantes.com_FecTrans,".$baseInv.".concomprobantes.com_FecContab,
				".$baseInv.".concomprobantes.com_FecVencim,".$baseInv.".concomprobantes.com_emisor,".$baseInv.".concomprobantes.com_CodReceptor,
				".$baseInv.".concomprobantes.com_Receptor,".$baseInv.".concomprobantes.com_Concepto,".$baseInv.".concomprobantes.com_Valor,
				".$baseInv.".concomprobantes.com_TipoCambio,".$baseInv.".concomprobantes.com_Libro,".$baseInv.".concomprobantes.com_NumRetenc,
				".$baseInv.".concomprobantes.com_RefOperat,".$baseInv.".concomprobantes.com_EstProceso,".$baseInv.".concomprobantes.com_EstOperacion,
				".$baseInv.".concomprobantes.com_NumProceso,".$baseInv.".concomprobantes.com_CodMoneda,".$baseInv.".concomprobantes.com_Usuario,
				".$baseInv.".concomprobantes.com_NumPeriodo,".$baseInv.".concomprobantes.com_PerOperativo,".$baseInv.".concomprobantes.com_Vendedor,
				".$baseInv.".concomprobantes.com_TsaImpuestos
		FROM ".$baseInv.".concomprobantes
		WHERE ".$baseInv.".concomprobantes.com_TipoComp = '".$tipoComp."' 
		AND ".$baseInv.".concomprobantes.com_NumComp = ".$numComp."
		";
	//print($Sql);
	
	if(!$db->Execute($Sql)) {
		print("Error, no se copio el comprobante");
	}
	else{
	    // Insertar invdetalle
	    $Sql = "INSERT INTO invdetalle (det_CodEmpresa,det_RegNUmero,det_Secuencia,det_NumSerie,det_CodItem,det_CanDespachada,det_UniMedida
				,det_CantEquivale,det_CosTotal,det_ValTotal,det_DescTotal,det_RefOperativa,det_Estado,det_cosunitario
				,det_valunitario,det_Destino,det_Lote,det_IndiceIva,det_IndiceIce
				)
		SELECT ".$baseInv.".invdetalle.det_CodEmpresa,c.com_RegNumero
				,".$baseInv.".invdetalle.det_Secuencia
				,".$baseInv.".invdetalle.det_NumSerie,".$baseInv.".invdetalle.det_CodItem,".$baseInv.".invdetalle.det_CanDespachada
				,".$baseInv.".invdetalle.det_UniMedida,".$baseInv.".invdetalle.det_CantEquivale,".$baseInv.".invdetalle.det_CosTotal
				,".$baseInv.".invdetalle.det_ValTotal,".$baseInv.".invdetalle.det_DescTotal,".$baseInv.".invdetalle.det_RefOperativa
				,".$baseInv.".invdetalle.det_Estado,".$baseInv.".invdetalle.det_cosunitario,".$baseInv.".invdetalle.det_valunitario
				,".$baseInv.".invdetalle.det_Destino,".$baseInv.".invdetalle.det_Lote,".$baseInv.".invdetalle.det_IndiceIva
				,".$baseInv.".invdetalle.det_IndiceIce
		FROM ".$baseInv.".invdetalle 
		JOIN ".$baseInv.".concomprobantes ON ".$baseInv.".invdetalle.det_RegNUmero = ".$baseInv.".concomprobantes.com_RegNumero
						   AND ".$baseInv.".concomprobantes.com_TipoComp = '".$tipoComp."' 
						   AND ".$baseInv.".concomprobantes.com_NumComp = ".$numComp."
		JOIN concomprobantes  c ON ".$baseInv.".concomprobantes.com_TipoComp = c.com_TipoComp
						   AND ".$baseInv.".concomprobantes.com_NumComp = c.com_NumComp
		";
	    if(!$db->Execute($Sql)) {
		    print("Error, no se copio el detalle del comprobante");
	    }
	    else{
	        //print("insertado");
	    }
	}
	
	
    }else{
	//Actualizar el comprobante existente
	$r = $rsexiste->fetchRow();
	$comRegNum =$r['com_RegNumero'];
	
	/*
	    enlRegNum1 - numero de registro de IB en 09_inventario
	    enlSecuencia1 - numero de secuencia del item en 09_inventario
	*/
	
	//Actualizar invdetalle
	    $Sql = "UPDATE invdetalle d
		    JOIN ".$baseInv.".invdetalle d2 ON d2.det_RegNUmero = ". $agPar["enlRegNum1"] ." AND d2.det_Secuencia = ". $agPar["enlSecuencia1"] ." AND d2.det_CodItem = d.det_CodItem
		    SET 	d.det_CanDespachada = d2.det_CanDespachada,
			    d.det_CantEquivale = d2.det_CantEquivale,
			    d.det_CosTotal = d2.det_CosTotal,
			    d.det_ValTotal = d2.det_ValTotal,
			    d.det_DescTotal = d2.det_DescTotal,
			    d.det_CosUnitario = d2.det_CosUnitario,
			    d.det_ValUnitario = d2.det_ValUnitario
		    WHERE d.det_RegNUmero = ".$comRegNum."
		";
	    if(!$db->Execute($Sql)) {
		    print("Error, no se copio el detalle del comprobante");
	    }
	    else{
	        //print("insertado");
	    }
    }    
}






function fContabilizar($pReg,$pSec,$pRegOc,$pSecOc)
{
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq,$baseInv;
    $Sql="select det_ValTotal,det_CantEquivale from invdetalle where det_RegNUmero=".$pRegOc." and det_Secuencia=".$pSecOc;
    $res=$db->GetRow($Sql);
    $ValorTotal=$res['det_ValTotal'];$Cantidad=$res['det_CantEquivale'];
    $trSql="update ".$baseInv.".invdetalle set det_CosTotal=(".$ValorTotal."/".$Cantidad.") * det_CantEquivale, det_cosunitario=(".$ValorTotal."/".$Cantidad.")
	    where det_RegNUmero=".$pReg." and det_Secuencia=".$pSec;
    if(fGetParam("pAppDbg",0)){
		print_r($agPar);
	}
    //echo $trSql; 
    if(!$db->Execute($trSql)) {
	    $olResp= array("failure"=>true,"totalRecords"=>0 );
            return $olResp;
    }
    //fDbgContab("Paso 1aa " . $sqltext);
    fCerrarCompro($pReg);
    //$olResp= array("success"=>true,"totalRecords"=>1);
    //return $olResp;
	
}
function fCerrarCompro($pReg)
{
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq,$baseInv;
    $trSql="update ".$baseInv.".concomprobantes
	    set com_EstProceso=7,com_EstOperacion=7
	    where com_RegNumero=".$pReg;
    if(fGetParam("pAppDbg",0)){
		print_r($agPar);
    }
    //echo $trSql;
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
$ogResp=fIngresar($ilReg);
/*$ogJson = new Services_JSON();
print($ogJson->encode($ogResp));*/
print(json_encode($ogResp));
?>
