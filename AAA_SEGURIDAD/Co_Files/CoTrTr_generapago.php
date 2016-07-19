<?php
/**
* Codigo para generar la transaccion contable a partir de la Transaccion de cartera
* Recibe como parametros :
* 	- Tipo de Comprobante,
* 	- la Cuenta de Banco, auxiliar,
* 	- valor,
* 	- fecha de emision,
* 	- codigo de CXC/CXP,
* 	- auxiliar Proveedor/Cliente,
* 	- detalle de facturas  o documentos a liquidar
* @package      AAA
* @subpackage   Contabilidad
* @Author       Fausto Astudillo
* @Date         25/03/09
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
*   Contabilizacion de una transaccion de Cartera
*   @param      $pRNum      Integer     Nùmero de registro del comprobante
*   @param      $pConcep    String      Concepto de la transaccion
**/
function fContabDim($pRNum, $pConcep=false){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    $trSql = "SELECT cla_descripcion,
		    cla_tipocomp as cla_tipocomp,
		    cla_contabilizacion as cla_contabilizacion,
		    cla_indicador as cla_indicador,
                    cla_ctaorigen as cla_ctaorigen,
		    cla_ctadestino as cla_ctadestino,
		    cla_auxorigen as cla_auxorigen,
                    cla_auxdestino as cla_auxdestino,
		    cla_ctaingresos as cla_ctaingresos,
		    cla_ctacosto as cla_ctacosto,
		    cla_ctadiferencia as cla_ctadiferencia,
		    cla_reqreferencia as cla_ctareqfererencia,
                    cla_reqsemana as cla_reqsemana,
		    cla_clatransaccion as cla_clatransaccion,
		    cla_indcheque as cla_indcheque,
		    cla_emidefault as cla_emidefault,
		    cla_recdefault as cla_recdefault,
		    cla_reqemisor as cla_reqemisor,
		    cla_reqreceptor as cla_reqreceptor,
		    cla_indicosteo as cla_indicosteo, cla_ImpFlag as cla_ImpFlag
            FROM genclasetran 
            WHERE cla_tipocomp = '" . $agPar["com_TipoComp"] ."'";
	if(fGetParam("pAppDbg",0)){
		print_r($agPar);
	}
    $ogDet=json_decode($agPar['det']);
    $rs = $db->execute($trSql);
    fDbgContab("Paso 1a " . $sqltext);
    if (!$rs) fErrorPage('',"NO SE PUDO DEFINIR LA TRANSACCION " . $pRNum);
    $rs->MoveFirst();
    fDbgContab("Paso 1aa " . $sqltext);
    $cla = $rs->FetchNextObject(false);


    $slSql = " per_Aplicacion = 'CO' AND '" . $agPar["com_Fecha"] . "' BETWEEN per_fecinicial AND per_fecfinal " ;
    list($ilPer, $ilEstado)  = fDBValor($db, "conperiodos", "per_PerContable, per_Estado", $slSql);

    $ilNumComp = fDBValor($db, "concomprobantes", "ifNULL(max(com_numcomp) +1,1)", "com_tipocomp = '" . $agPar["com_TipoComp"] ."' ");
    $pRNum= 0;

    $agPar["com_NumComp"]= $ilNumComp;  //	subir como param global el numcomp
    $ilReg = fAgregaComprobante($db, $agPar["com_TipoComp"], $agPar["com_NumComp"], 0, $agPar["com_Fecha"],
            $agPar["com_Fecha"], $agPar["com_Fecha"], $cla->cla_emidefault, $ogDet[0]->det_idauxiliar,   $agPar["slBenef"],
	    $agPar["com_Concepto"], 0, 1, 9999, $pRNum, 0, 5, -1,-1, 593, $_SESSION['g_user'], $ilPer, 0);
    fDbgContab("Paso 4 " . $ilReg );
    $agPar["com_RegNumero"] = $ilReg;	//	subir como param global el Id de registro    

    $igNumChq = 0;
    if($cla->cla_indcheque == 1){
	if ($agPar["ilNumCheque"] == 0){
	    $igNumChq = fDBValor($db, "condetalle", "ifNULL(max(det_numcheque)+1,1)",  "det_codcuenta = '" . $cla->cla_ctaorigen .
		    "' AND det_idauxiliar = " . $cla->cla_auxorigen);
	}
	else $igNumChq =$agPar["ilNumCheque"] ;
    }
    if($cla->cla_indicador == -1){
		fProcesaCartera();
		$igSecue++;
		fProcesaBanco();
    }
    else {
		fProcesaBanco();
		$igSecue++;
		fProcesaCartera();
    }
	$olResp= array("success"=>true,"totalRecords"=>1, "com_RegNumero"=>$ilReg,
		"com_TipoComp"=>$agPar["com_TipoComp"], "com_NumComp"=>$ilNumComp, "com_NumCheque"=>$igNumChq );
	return $olResp;
}

/*
 *	inicializa el arreglo de detalle contable
 **/
    function fIniDetalle(){
	global $agPar;
        return array('det_regnumero'=> $agPar["com_RegNumero"],
	'det_tipocomp' 		=> $agPar["com_TipoComp"],
        'det_numcomp'  		=> $agPar["com_NumComp"],
        'det_secuencia'		=> 1,
        'det_clasregistro' 	=> 0,
        'det_idauxiliar'	=> 0 ,
        'det_valdebito' 	=> 0,
        'det_valcredito'	=> 0,
        'det_glosa' 		=> $agPar["com_Concepto"],
        'det_estejecucion' 	=> 0,
        'det_fecejecucion' 	=> '2020-12-31',				//@TODO: Hacer una fecha futura en base a la fecha contable!!!
        'det_estlibros'  	=> 0,
        'det_feclibros' 	=> '2020-12-31',
        'det_refoperativa' 	=> 0,
        'det_numcheque' 	=> 0,
        'det_feccheque' 	=>'0000-00-00',
	'det_valdebito'		=> 0,
	'det_valcredito'	=>0,
        'det_codcuenta' 	=> '' ) ;
    }
/*
 *	Graba asiento contable en cuenta Bancos 
 **/
function fProcesaBanco(){
    global $db, $cla, $agPar, $igNumChq, $igSecue, $ogDet;
    $alDet = fIniDetalle();
    $alDet["det_codcuenta"]	= $cla->cla_ctaorigen;
    $alDet["det_glosa"] 	= substr($agPar["com_Concepto"],0, 50) . $agPar["slBenef"];
    $alDet['det_idauxiliar']= $agPar["com_Emisor"];
    $alDet["det_numcheque"]	= $igNumChq;			// es el numero de cheque
    $alDet["det_feccheque"]	= $agPar["com_FechCheq"];
    $alDet['det_valdebito']	= $agPar["flValor"] * $cla->cla_indicador; // segun el signo del indicador, se aplica como DB/CR al grabar;
    $alDet['det_valcredito']	= 0;
    $alDet['det_secuencia']	= $igSecue;
	if(fGetParam("pAppDbg",0)){
		print_r($alDet);
	}
	fInsdetalleCont($db, $alDet);
}
/*
 *	Graba asiento contable en cuenta de cartera
 **/
function fProcesaCartera(){
    global $db, $cla, $agPar, $igNumChq, $igSecue, $ogDet;
    $igSecue++;
    foreach($ogDet as $k => $r) {
		$alDet = fIniDetalle();
		$alDet["det_codcuenta"]	= $r->det_codcuenta;
		$alDet["det_glosa"] 	= substr($agPar["com_Concepto"],0, 40);
		$alDet['det_idauxiliar']= $r->det_idauxiliar;
		$alDet["det_numcheque"]	= $r->det_numdocum; 			// es el numero de documento
		$alDet["det_feccheque"]	= $agPar["com_FechCheq"];
		$alDet['det_valcredito']= $r->txt_pago * $cla->cla_indicador; // segun el signo del indicador, se aplica como DB/CR al grabar
		$alDet['det_valdebito']	= 0;
		$alDet['det_secuencia']	= $igSecue++;
		if(fGetParam("pAppDbg",0)){
			echo"<br>---registro:----";
			print_r($r);
			echo"<br>---arreglo:-----";
			print_r($alDet);
		}
		fInsdetalleCont($db, $alDet);
	}
}
/**
*   definir el auxiliar correspondiente al contexto del mov.
*
*/
function fTraeAuxiliar($pTip, $pCod){
    global $db, $cla, $olEsq;
    $slDato= substr($olEsq->par_Clave,4,2);
    if ($slDato  == "CL") {  // el movimiento se asocia al Cliente
        $iAuxi = $cla->codProv;
    }
    else {
    $iAuxi = NZ(fDBValor($db, "fistablassri", "tab_txtData3", "tab_codTabla = '" . $pTip . "' AND tab_codigo = '" . $pCod . "'" ),0);
    }
//echo "<br>aux" . $olEsq->par_Clave. " / " .$slDato . " $iAuxi <br>";
   error_log("  aux: " . $iAuxi. " \n", 3,"/tmp/dimm_log.err");	
    return $iAuxi;
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
$igNumChq = NULL;
$igSecue= 1;
$ogDet=array();
$ogResp=fContabDim($ilReg);
/*$ogJson = new Services_JSON();
print($ogJson->encode($ogResp));*/
print(json_encode($ogResp));
?>
