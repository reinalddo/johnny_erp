<?php
// AAA Dimm 2.1.0  (C) 2003-2006 Fausto Astudillo

/**
* Codigo para generar la transaccion contable a partir de la Transaccion DIM
* @package      AAA
* @subpackage   Dimm
* @Author       Fausto Astudillo
* @Date         22/Oct/2006
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
/**
*   Contabilizacion de una transaccionde Dimm
*   @param      $pRNum      Integer     Nùmero de registro del comprobante
*   @param      $pConcep    String      Concepto de la transaccion
**/
function fContabDim($pRNum, $pConcep=false){
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    $db = &ADONewConnection('mysql');
    $db->autoRollback = true;
    $db->debug = CCGetParam("pAdoDbg",0);
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
echo $trSql . "<br>";
    $trSql = "SELECT fiscompras.*, cla_descripcion, cla_tipocomp, cla_contabilizacion, cla_indtransfer,
                    cla_ctaorigen, cla_ctadestino, cla_auxorigen,
                    cla_auxdestino, cla_ctaingresos, cla_ctacosto, cla_ctadiferencia, cla_reqreferencia,
                    cla_reqsemana,cla_clatransaccion, cla_indicosteo, cla_ImpFlag
            FROM fiscompras JOIN fistablassri ON tab_codtabla = 'A' and tab_codigo = tipoTransac
                             LEFT JOIN genclasetran ON cla_tipocomp = tab_IndProceso
            WHERE ID = " . $pRNum;
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
    $rs->MoveFirst();
    $cla = $rs->FetchNextObject(false);
/*  error_log ("func\r\n", 3, "/tmp/my-errors.log");
    error_log (" SQL: " . $trSql . "\r\n", 3, "/tmp/my-errors.log");
    error_log (print_r($cla) . " contab1: " . $cla->cla_contabilizacion . "\r\n", 3, "/tmp/my-errors.log");
 */
    $slGlosa="Doc: " . $cla->tipoComprobante . "/ " . $cla->establecimiento . "-" . $cla->puntoEmision . "-" .
             $cla->puntoemision . "-" . $cla->secuencial ;
    $slGloRet="Ret: " . $cla->estabRetencion1 . "-" . $cla->puntoEmiRetencion1 . "-" .    $cla->puntoEmiRetencion1 . "-" . $cla->secRetencion1 ;
    if (!$pConcep) $pConcep = $slGlosa;
    if (!$cla->cla_contabilizacion ) return true; //					No Procesar si o es transaccion contabilizable
	$slTipoComp = $cla->cla_tipocomp;
    $db->Execute("DELETE FROM concomprobantes WHERE com_tipocomp = '" . $cla->cla_tipocomp . "' AND com_numretenc = " . $pRNum);
	error_log("1    ", 3 ,"/tmp/err-fff.txt");
	$slFecR = date('Y-m-d H:n');
	print_r($cla);
	$ilNumComp = fPxmoComprob($db, $cla->cla_tipocomp,$cla->cla_tipocomp);
	echo "numcomp: " . $ilNumComp;
	die();
/**
	fAgregaComprobante(&$db, $TipoComp, $NumComp, $RegNumero, $FecTrans, $FecContab, $FecVencim, $Emisor,
                    $CodReceptor, $Receptor, $Concepto, $Valor, $TipoCambio, $Libro, $NumRetenc, $RefOperat,
                    $EstProceso, $EstOperacion, $NumProceso, $CodMoneda, $Usuario, $NumPeriodo, $FecDigita)
                    **/
/**
	$tlSql ="SELECT per_PerContable, per_Estado ".
					"FROM conperiodos ".
					"WHERE per_Aplicacion = 'CO' AND " .
                           $cla->fechaRegistro . " BETWEEN per_fecinicial AND per_fecfinal ";
**/
    $slSql = " per_Aplicacion = 'CO' AND '" . $cla->fechaRegistro . "' BETWEEN per_fecinicial AND per_fecfinal " ;
    list($ilPer, $ilEstado)  = fDBValor($db, "conperiodos", "per_PerContable, per_Estado", $slSql);

	$ilReg = fAgregaComprobante($db, $cla->cla_tipocomp, $ilNumComp, 0, $cla->fechaEmision,
                      $cla->fechaRegistro, $cla->fechaRegistro, 0, $cla->codProv, '',
					  $pConcep, 0, 1, 999, $pRNum, 0, -1, -1,
            		  -1, 593, $_SESSION['g_user'], $ilPer, 0);
            		 /*****************************************
            		 ME QUEDO AQUI!!!!!!!!!!!!!
            		 *****************************************/
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
	$rsEsq= $db->Execute("SELECT * " .
                                "FROM gencatparam JOIN genparametros ON  par_categoria = cat_codigo ".
                                "WHERE cat_clave = 'CD" . $cla->cla_tipocomp ."' ORDER BY par_secuencia" );
    $rsEsq->MoveFirst();
    while ($olEsq = $rsEsq->FetchNextObject(false)){
        $alDet['det_valdebito']= 0;
        $alDet['det_valcredito']=0;
        $alDet['det_secuencia']++;
        $alDet["det_codcuenta"]=$olEsq->par_Valor1;
        $alDet["det_glosa"] = $slGlosa . " ". $olEsq->par_Valor3 . " " ;
        $alDet['det_idauxiliar']=0;
        switch($olEsq->par_Valor3){
            case "PROVISION":
                $flSumRFte = $cla->valRetAir3 +$cla->valRetAir2 + $cla->valRetAir;
                $flSumRIva = $cla->valorRetBienes + $cla->valorRetServicios;
                $flValor= NZ($cla->baseImponible,0) + NZ($cla->baseImpGrav,0) + NZ($cla->montoIva,0)  + NZ($cla->montoIce,0);
//                          - $flSumRFte - $flSumRIva  ;
                $alDet["det_idauxiliar"]= $cla->codProv;
                $alDet["det_valdebito"] = ($olEsq->par_valor2=="D") ? NZ($flValor,0) : NZ($flValor,0)* (-1);
                fInsdetalleCont($db, $alDet);
//echo " <br> $flSumRFte  // $flSumRIva   ";
                if ($flSumRFte != 0){
                    $alDet['det_valcredito']=0;
                    $alDet["det_valdebito"] = ($olEsq->par_valor2=="D") ? NZ($flSumRFte,0)* (-1) : NZ($flSumRFte,0); // Cambio el signo
echo $alDet["det_valdebito"];
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] = $slGloRet . " Retencion Fuente"  ;
                    fInsdetalleCont($db, $alDet);
                }
                if ($flSumRIva !=0){
                    $alDet['det_valcredito']=0;
                    $alDet["det_valdebito"] = ($olEsq->par_valor2=="D") ? NZ($flSumRIva,0) * (-1) : NZ($flSumRIva,0);  // Cambiao el signo
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] = $slGloRet . " Retencion Iva"  ;
                    fInsdetalleCont($db, $alDet);
                }
                $flValor=0;
                break;
            case "VALOR_IVA":
                $flValor= NZ($cla->montoIva,0);
                $ilAuxil=0;
                break;
            case "RESULTADO":
            case "GASTO":
                $flValor= NZ($cla->baseImponible,0) + NZ($cla->baseImpGrav,0);
                $ilAuxil=0;
                break;
            case "RET_FUENTE":
                if ($cla->valRetAir != 0){
                    if($olEsq->par_valor2=="D")
                        $alDet["det_valdebito"]= NZ($cla->valRetAir,0);
                    else
                        $alDet["det_valcredito"]= NZ($cla->valRetAir,0);
                    $alDet["det_idauxiliar"]=$cla->codRetAir;
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] .=  ":  Base " . NZ($cla->baseImpAir,0) ;
                    fInsdetalleCont($db, $alDet);

                }
                if ($cla->valRetAir2 != 0){
                    if($olEsq->par_valor2=="D")
                        $alDet["det_valdebito"]= NZ($cla->valRetAir2,0);
                    else
                        $alDet["det_valcredito"]= NZ($cla->valRetAir2,0);
                    $alDet["det_idauxiliar"]=$cla->codRetAir2;
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] .= ":  Base " . NZ($cla->baseImpAir2,0) ;
                    fInsdetalleCont($db, $alDet);
                }
                if ($cla->valRetAir3 <>0){
                    if($olEsq->par_valor2=="D")
                        $alDet["det_valdebito"]= NZ($cla->valRetAir3,0);
                    else
                        $alDet["det_valcredito"]= NZ($cla->valRetAir3,0);
                    $alDet["det_idauxiliar"]=$cla->codRetAir3;
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] .= ":  Base " . NZ($cla->baseImpAir3,0) ;
                    fInsdetalleCont($db, $alDet);
                }
                $flValor=0;
                break;
            case "IVA_RETENIDO":
                if ($cla->montoIvaBienes != 0){
                    if($olEsq->par_valor2=="D")
                        $alDet["det_valdebito"]= NZ($cla->valorRetBienes,0);
                    else
                        $alDet["det_valcredito"]= NZ($cla->valorRetBienes,0);
                    $alDet["det_idauxiliar"]=$cla->porRetBienes;
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] =  $slGloRet . ": Iva Bienes de " . NZ($cla->montoIvaBienes,0) ;
                    fInsdetalleCont($db, $alDet);
                }
               echo "<br>" . $cla->valorRetServicios;
                if ($cla->montoIvaServicios != 0){
                    if($olEsq->par_Valor2=="D")
                        $alDet["det_valdebito"]= NZ($cla->valorRetServicios,0);
                    else
                        $alDet["det_valcredito"]= NZ($cla->valorRetServicios,0);
                    $alDet["det_idauxiliar"]=$cla->porRetServicios;
                    $alDet["det_secuencia"]++;
                    $alDet["det_glosa"] =  $slGloRet . ": Iva Servicios de " . NZ($cla->montoIvaServicios,0) ;
                    fInsdetalleCont($db, $alDet);
                }
                $flValor=0;
                break;
        }
        if ($flValor != 0){
            if($olEsq->par_valor2=="D")
                $alDet["det_valdebito"]= $flValor;
            else
                $alDet["det_valcredito"]= $flValor;
            $alDet["det_idauxiliar"]= $ilAuxil;
            fInsdetalleCont($db, $alDet);
        }
    }
    return true;
}
if (fGetparam('pGen', false) && fGetparam('pReg', false)) //  El proceso es llamado directamente
    fContabInv(fGetparam('pReg', false));
?>
