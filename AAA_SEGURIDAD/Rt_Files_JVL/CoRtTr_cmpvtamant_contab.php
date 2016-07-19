<?php
// AAA Dimm 2.1.0  (C) 2003-2006 Fausto Astudillo

/**
* Codigo para generar la transaccion contable a partir de la Transaccion DIM
* @package      AAA
* @subpackage   Dimm
* @Author       Johnny Valencia
* @Date         22/Ago/2006
* @rev		jvl 05/02/09	Soporte de contabilizacion dinamica para los casos de compras de inventario con y sin Credito Tributario (sustento 06 y 07),
* @rev		jvl 07/08/2013	Soporte de contabilizacion dinamica para los casos de compras de fruta (sustento 06 y 07),
* 				
*/
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
include_once("tohtml.inc.php");
include_once("../Common.php");
include_once("../LibPhp/ConLib.php");
include_once("GenUti.inc.php");
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
    $db->debug = CCGetParam("pAdoDbg",0);
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

/**
*   Contabilizacion de una transaccionde Dimm
*   @param      $pRNum      Integer     Nùmero de registro del comprobante
*   @param      $pConcep    String      Concepto de la transaccion
**/
function fContabDim($pRNum, $pConcep=false){
    global $db, $cla, $olEsq;
     $trSql = "SELECT fiscompras.*, cla_descripcion,
		    cla_tipocomp as cla_tipocomp, cla_contabilizacion as cla_contabilizacion, cla_indtransfer,
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
		    cla_indicosteo as cla_indicosteo, cla_ImpFlag as cla_ImpFlag,
		    tab_IndProceso as tab_IndProceso, tab_txtData3 as tab_txtData3
            FROM fiscompras JOIN fistablassri ON tab_codtabla = 'A' and tab_codigo = tipoTransac
            LEFT JOIN genclasetran ON cla_tipocomp = tab_txtData3
            WHERE ID = " . $pRNum;

    $rs = $db->execute($trSql);
	fDbgContab("Paso 1a " . $sqltext);
	if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
    $rs->MoveFirst();
    fDbgContab("Paso 1aa " . $sqltext);
    $cla = $rs->FetchNextObject(false);
/*  error_log ("func\r\n", 3, "/tmp/my-errors.log");
    error_log (" SQL: " . $trSql . "\r\n", 3, "/tmp/my-errors.log");
    error_log (print_r($cla) . " contab1: " . $cla->cla_contabilizacion . "\r\n", 3, "/tmp/my-errors.log");
 */
/* fah 05/02/09   Validacion de Fecha de Inicio de Contabilizacion DIMM*/
    $slFec = $cla->fechaRegistro;
    $slCond = "par_clave='DIMINI' AND par_valor1 <= '" . $cla->fechaRegistro . "'";
    $ilFlag = fDBValor($db, "genparametros", "par_valor1", $slCond);
    if ($ilFlag >=1) exit;

    $slGlosa="Doc: " . $cla->tipoComprobante . "/ " . $cla->establecimiento . "-" . $cla->puntoEmision . "-" .
             $cla->puntoemision . "-" . $cla->secuencial ;
    $slGloRet="Ret: " . $cla->estabRetencion1 . "-" . $cla->puntoEmiRetencion1 . "-" .    $cla->puntoEmiRetencion1 . "-" . $cla->secRetencion1 ;
    if (!$pConcep) $pConcep = $slGlosa;
    fDbgContab("Paso 1b. Indicador de Contabilizable:" . $cla->cla_contabilizacion);	
    fDbgContab("Paso 1c. :" . $trSql);	
    if (!$cla->cla_contabilizacion ){
    	fDbgContab("TRANSACCION $cla->tipocomp CONTABLE NO CONTABILIZABLE \n" . $trSql);	
     	return true; //					No Procesar si o es transaccion contabilizable
		}
    fDbgContab("Paso 2 " . $sqltext);	
	$slTipoComp = $cla->cla_tipocomp;
    $db->Execute("DELETE FROM concomprobantes WHERE com_tipocomp = '" . $cla->cla_tipocomp . "' AND com_numretenc = " . $pRNum);
	fDbgContab("1    ");
	$slFecR = date('Y-m-d H:n');
	$ilNumComp = fPxmoComprob($db, $cla->cla_tipocomp,$cla->cla_tipocomp);
    $slSql = " per_Aplicacion = 'CO' AND '" . $cla->fechaRegistro . "' BETWEEN per_fecinicial AND per_fecfinal " ;
    list($ilPer, $ilEstado)  = fDBValor($db, "conperiodos", "per_PerContable, per_Estado", $slSql);

	$ilReg = fAgregaComprobante($db, $cla->cla_tipocomp, $ilNumComp, 0, $cla->fechaEmision,
                      $cla->fechaRegistro, $cla->fechaRegistro, 0, $cla->codProv, '',
					  $pConcep, 0, 1, 9999, $pRNum, 0, -1, -1,
            		  -1, 593, $_SESSION['g_user'], $ilPer, 0);
    fDbgContab("Paso 4 " . $ilReg );	
	$alDet = 	array(	'det_regnumero'=> $ilReg,
		'det_tipocomp' => $cla->cla_tipocomp,
        'det_numcomp'  => $ilNumComp,
        'det_secuencia'=> 0,
        'det_clasregistro' => 0,
        'det_idauxiliar'=> 0 ,
        'det_valdebito' => 0,
        'det_valcredito'=>0,
        'det_glosa' => $slGlosa,
        'det_estejecucion' => 0,
        'det_fecejecucion' => '2020-12-31',
        'det_estlibros'  	=> 0,
        'det_feclibros' 	=> '2020-12-31',
        'det_refoperativa' => 0,
        'det_numcheque' 	=> 0,
        'det_feccheque' 	=>'0000-00-00',
        'det_codcuenta' 	=> '' ) ;
	$slSql = "SELECT * " .
				"FROM gencatparam JOIN genparametros ON  par_categoria = cat_codigo ".
                "WHERE cat_clave = 'CD" . $cla->tab_IndProceso ."' ORDER BY par_secuencia" ;
			 
    fDbgContab("Paso 5 " . $slSql  );	
	$rsEsq= $db->Execute($slSql );
    fDbgContab("Param: " . $slSql );	
    $rsEsq->MoveFirst();
    $flSumRFte = $cla->valRetAir3 +$cla->valRetAir2 + $cla->valRetAir;
    $flSumRIva = $cla->valorRetBienes + $cla->valorRetServicios;
    $flValorTot= NZ($cla->baseImponible,0) + NZ($cla->baseImpGrav,0) + 
				 NZ($cla->montoIva,0)  + NZ($cla->montoIce,0);
    while ($olEsq = $rsEsq->FetchNextObject(false)){
//print_r($olEsq);
//echo "<br>";
        if (substr($olEsq->par_valor2,0,1)=="X") continue 1;
        $alDet['det_valdebito']= 0;
        $alDet['det_valcredito']=0;
        $alDet['det_secuencia']++;
        $alDet["det_codcuenta"]=$olEsq->par_Valor1;
        $alDet["det_glosa"] = $slGlosa . " ". $olEsq->par_Valor3 . " " ;
        $alDet['det_idauxiliar']=0;
	$alDet["det_numcheque"]= 0;
	$alDet["det_feccheque"]= "0000-00-00";

        switch($olEsq->par_Valor3){
           case "PROVISION":			//Provision simple:  Total Facturado menos Retenciones en la Cta Proveedor
   			If ($slTipoComp<>'CF') {      			
       			$flValor=$flValorTot - $flSumRFte - $flSumRIva;
                $alDet["det_idauxiliar"]= $cla->codProv;
                $alDet["det_valdebito"] = ($olEsq->par_valor2=="D") ? NZ($flValor,0) : NZ($flValor,0)* (-1);
		$alDet["det_numcheque"]= $cla->secuencial;
		$alDet["det_feccheque"]= $cla->fechaEmision;
                fInsdetalleCont($db, $alDet);
                fDbgContab($olEsq->par_Valor3 . $flValor );	
                $flValor=0;}
                break;
           case "PRODUCTOR":			//Provision simple:  Total Facturado menos Retenciones en la Cta Proveedor
   			If ($slTipoComp=='CF') {      			
       			$flValor=$flValorTot - $flSumRFte - $flSumRIva;
                $alDet["det_idauxiliar"]= $cla->codProv;
                $alDet["det_valdebito"] = ($olEsq->par_valor2=="D") ? NZ($flValor,0) : NZ($flValor,0)* (-1);
		$alDet["det_numcheque"]= $cla->secuencial;
		$alDet["det_feccheque"]= $cla->fechaEmision;
                fInsdetalleCont($db, $alDet);
                fDbgContab($olEsq->par_Valor3 . $flValor );	
                $flValor=0;}
                break;

           case "PROVISION_DET":			//Provision detallada:  Total Facturado y Retenciones en la Cta Proveedor
       			$flValor=$flValorTot - $flSumRfte - $flSumRIva;
                $alDet["det_idauxiliar"]= $cla->codProv;
                $alDet["det_valdebito"] = ($olEsq->par_valor2=="D") ? NZ($flValor,0) : NZ($flValor,0)* (-1);
		$alDet["det_numcheque"]= $cla->secuencial;
		$alDet["det_feccheque"]= $cla->fechaEmision;
                fInsdetalleCont($db, $alDet);
                if ($flSumRFte != 0){
                    $alDet['det_valcredito']=0;
                    $alDet["det_valdebito"] = ($olEsq->par_valor2=="D") ? NZ($flSumRFte,0)* (-1) : NZ($flSumRFte,0); // Cambio el signo
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] = $slGlosa . " Retencion Fuente "  ;
                    fInsdetalleCont($db, $alDet);
                }
                if ($flSumRIva !=0){
                    $alDet['det_valcredito']=0;
                    $alDet["det_valdebito"] = ($olEsq->par_valor2=="D") ? NZ($flSumRIva,0) * (-1) : NZ($flSumRIva,0);  // Cambiao el signo
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] = $slGlosa . " Retencion Iva"  ;
                    fInsdetalleCont($db, $alDet);
                }
                fDbgContab($olEsq->par_Valor3 . $flValor );	
                $flValor=0;
                break;
            case "VALOR_IVA":
		$alDet["det_numcheque"]= $cla->secuencial;
		$alDet["det_feccheque"]= $cla->fechaEmision;
		if (($cla->devIva== "N" && $olEsq->par_Clave == "CDEGCT") ||    // #fah05/02/09
		    ($cla->devIva== "S" && $olEsq->par_Clave == "CDEGIV")){
		    $flValor=0;
		    break;
		}
		    $slAuxBien = "0";
		    $slAuxServ = "0";				
		    if (strlen($olEsq->par_Valor4) > 0){
			 list($slAuxBien, $slAuxServ) = split($olEsq->par_Valor4, ",");
		    }
                $alDet["det_codcuenta"]=$olEsq->par_Valor1;
                $alDet["det_valdebito"]= 0;
                $alDet["det_valcredito"]= 0;
                if ($cla->montoIvaBienes != 0){
                    $alDet["det_secuencia"]++;
                    $alDet["det_idauxiliar"]=$slAuxBien;
                    $alDet["det_glosa"] =  $slGlosa . " Bienes con Iva" ;
                    if($olEsq->par_valor2=="D") {
                        $alDet["det_valdebito"]= NZ($cla->montoIvaBienes,0);
                        $alDet["det_valcredito"]= 0; }
                    else {
                        $alDet["det_valdebito"]= 0;
                        $alDet["det_valcredito"]= NZ($cla->montoIvaBienes,0);}
                    fInsdetalleCont($db, $alDet);
                }
                if ($cla->montoIvaServicios != 0){
                    $alDet["det_secuencia"]++;
                    $alDet["det_idauxiliar"]=$slAuxServ; 
                    $alDet["det_glosa"] =  $slGlosa . " Servicios con Iva" ;
                    if($olEsq->par_valor2=="D") {
                        $alDet["det_valdebito"]= NZ($cla->montoIvaServicios,0);
                        $alDet["det_valcredito"]= 0;}
                    else {
                        $alDet["det_valdebito"]= 0;
                        $alDet["det_valcredito"]= NZ($cla->montoIvaServicios,0);}
                    fInsdetalleCont($db, $alDet);
                }
                $flValor=0;
                break;
            case "IVAINV": // #fah05/02/09
		if ($cla->codSustento !="06" &&   $cla->codSustento != "07"){ //		Se aplica solo para compra de inv
		    $flValor=0;
		    break;
		}
		$alDet["det_numcheque"]= $cla->secuencial;
		$alDet["det_feccheque"]= $cla->fechaEmision;
		$slAuxBien = "0";
		$slAuxServ = "0";				
		if (strlen($olEsq->par_Valor4) > 0){
		     list($slAuxBien, $slAuxServ) = split($olEsq->par_Valor4, ",");
		}
                $alDet["det_codcuenta"]=$olEsq->par_Valor1;
                $alDet["det_valdebito"]= 0;
                $alDet["det_valcredito"]= 0;
                if ($cla->montoIvaBienes != 0){
                    $alDet["det_secuencia"]++;
                    $alDet["det_idauxiliar"]=$slAuxBien;
                    $alDet["det_glosa"] =  $slGlosa . " Bienes con Iva" ;
                    if($olEsq->par_valor2=="D") {
                        $alDet["det_valdebito"]= NZ($cla->montoIvaBienes,0);
                        $alDet["det_valcredito"]= 0; }
                    else {
                        $alDet["det_valdebito"]= 0;
                        $alDet["det_valcredito"]= NZ($cla->montoIvaBienes,0);}
                    fInsdetalleCont($db, $alDet);
                }
                if ($cla->montoIvaServicios != 0){
                    $alDet["det_secuencia"]++;
                    $alDet["det_idauxiliar"]=$slAuxServ; 
                    $alDet["det_glosa"] =  $slGlosa . " Servicios con Iva" ;
                    if($olEsq->par_valor2=="D") {
                        $alDet["det_valdebito"]= NZ($cla->montoIvaServicios,0);
                        $alDet["det_valcredito"]= 0;}
                    else {
                        $alDet["det_valdebito"]= 0;
                        $alDet["det_valcredito"]= NZ($cla->montoIvaServicios,0);}
                    fInsdetalleCont($db, $alDet);
                }
                $flValor=0;
                break;
	    
            case "RESULTADO":
            case "GASTO":
            case "POR_COBRAR":
		$alDet["det_numcheque"]= $cla->secuencial;
		$alDet["det_feccheque"]= $cla->fechaEmision;
		if ($cla->codSustento == "06" or $cla->codSustento == "07" ){	//#fah05/02/09 No se aplica en caso de compra de inventario
			$flValor= 0;
			break;
		    }
		else {
		    $flValor= NZ($cla->baseImponible,0) + NZ($cla->baseImpGrav,0);
		    $ilAuxil=0;
		    fDbgContab($olEsq->par_Valor3 . $flValor);
		}
		break;

	    case "COSTOINV":
		$alDet["det_numcheque"]= $cla->secuencial;
		$alDet["det_feccheque"]= $cla->fechaEmision;
		switch ($cla->codSustento){
		case "06":
		    $alDet["det_secuencia"]++;
		    $alDet["det_idauxiliar"]=0; 
		    $alDet["det_glosa"] =  $slGlosa . " Invent con CT" ;
		    if($olEsq->par_valor2=="D") {
			$alDet["det_valdebito"]= NZ($cla->baseIvaBienes,0);
			$alDet["det_valcredito"]= 0;}
		    else {
			$alDet["det_valdebito"]= 0;
			$alDet["det_valcredito"]= NZ($cla->baseIvaBienes,0);}
		    fInsdetalleCont($db, $alDet);
		    $flValor= 0;
		    break;
		case "07":
		    $alDet["det_secuencia"]++;
		    $alDet["det_idauxiliar"]=0; 
		    $alDet["det_glosa"] =  $slGlosa . " Invent con CT" ;
		    if($olEsq->par_valor2=="D") {
			$alDet["det_valdebito"]= NZ($cla->baseIvaBienes,0);
			$alDet["det_valcredito"]= 0;}
		    else {
			$alDet["det_valdebito"]= 0;
			$alDet["det_valcredito"]= NZ($cla->baseIvaBienes,0);}
		    fInsdetalleCont($db, $alDet);
		    $flValor= 0;			
		    break;
		default:
		    $flValor= 0;			
		    break;
		}
		$flValor= 0;
		break;
            case "RET_FUENTE":
		$alDet["det_numcheque"]= $cla->secRetencion1;
		$alDet["det_feccheque"]= $cla->fechaEmiRet1;
                fDbgContab("RET F: " . $cla->valRetAir . " / " . $cla->valRetAir2 . " / " . $cla->valRetAir3 . " \n");	
                if ($cla->valRetAir != 0){
                    if($olEsq->par_valor2=="D")
                        $alDet["det_valdebito"]= NZ($cla->valRetAir,0);
                    else
                        $alDet["det_valcredito"]= NZ($cla->valRetAir,0);
                    $alDet["det_idauxiliar"]=fTraeAuxiliar('10', $cla->codRetAir);
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] = $slGloRet .  " Base " . NZ($cla->baseImpAir,0) ;
                    fDbgContab("RET F1: " . $cla->valRetAir);	
                    fInsdetalleCont($db, $alDet);
                }
                if ($cla->valRetAir2 != 0){
                    if($olEsq->par_valor2=="D")
                        $alDet["det_valdebito"]= NZ($cla->valRetAir2,0);
                    else
                        $alDet["det_valcredito"]= NZ($cla->valRetAir2,0);
                    $alDet["det_idauxiliar"]=fTraeAuxiliar('10', $cla->codRetAir2);
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] = $slGloRet .  " Base " . NZ($cla->baseImpAir2,0) ;
                    fDbgContab("RET F1: " . $cla->valRetAir2);	
                    fInsdetalleCont($db, $alDet);
                }
                if ($cla->valRetAir3 <>0){
                    if($olEsq->par_valor2=="D")
                        $alDet["det_valdebito"]= NZ($cla->valRetAir3,0);
                    else
                        $alDet["det_valcredito"]= NZ($cla->valRetAir3,0);
                    $alDet["det_idauxiliar"]=fTraeAuxiliar('10', $cla->codRetAir3);
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] = $slGloRet .  NZ($cla->baseImpAir3,0) ;
                    fDbgContab("RET F2: " . $cla->valRetAir3 . " \n");	
                    fInsdetalleCont($db, $alDet);
                }
                $flValor=0;
                break;
            case "IVA_RETENIDO":
		$alDet["det_numcheque"]= $cla->secRetencion1;
		$alDet["det_feccheque"]= $cla->fechaEmiRet1;
                if ($cla->montoIvaBienes != 0){
    	            fDbgContab("RET IVA BIENES: " );	
                    if($olEsq->par_valor2=="D")
                        $alDet["det_valdebito"]= NZ($cla->valorRetBienes,0);
                    else
                        $alDet["det_valcredito"]= NZ($cla->valorRetBienes,0);
                    $alDet["det_idauxiliar"]=fTraeAuxiliar('5A', $cla->porRetBienes);
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] =  $slGloRet . ": Ret. Iva-B de " . NZ($cla->montoIvaBienes,0) ;
                    fInsdetalleCont($db, $alDet);
                 }
                if ($cla->montoIvaServicios != 0){
	                fDbgContab("RET IVA SERVICIOS: " );	
                    if($olEsq->par_valor2=="D")
                        $alDet["det_valdebito"]= NZ($cla->valorRetServicios,0);
                    else
                        $alDet["det_valcredito"]= NZ($cla->valorRetServicios,0);
                    $alDet["det_idauxiliar"]=fTraeAuxiliar('5', $cla->porRetServicios);
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] =  $slGloRet . ": Ret. Iva-S de " . NZ($cla->montoIvaServicios,0) ;
                    fInsdetalleCont($db, $alDet);
                 }
                $flValor=0;
                break;
        }
        if ($flValor != 0){
            fDbgContab("LOOOP: " . $olEsq->par_Clave . " " . $flValor);	
            if($olEsq->par_valor2=="D")
                $alDet["det_valdebito"]= $flValor;
            else
                $alDet["det_valcredito"]= $flValor;
            $alDet["det_idauxiliar"]= $ilAuxil;
            fInsdetalleCont($db, $alDet);
        }
    }
   fDbgContab("SALIDA: " . $flValor);	
    return true;
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
fDbgContab("------------------------------------");
$blFlag=fGetparam('pGen', false);
$ilReg=fGetparam('pReg', 0);
if ($blFlag && $ilReg <> 0) { //  El proceso es llamado directamente
    fDbgContab("   Inicio Con");
    fContabDim($ilReg);
}
?>
