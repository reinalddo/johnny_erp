<?php
//error_reporting (E_ALL);
if (!isset($_SESSION["g_user"]) && (isset($_GET["ByPass"]) && $_GET["ByPass"]=='prueba'))
{
    include_once("General2.inc.php");
    define("RelativePath", "..");
    include_once("adodb.inc.php");
    include_once("tohtml.inc.php");
    include_once("../Common.php");
    include_once("../LibPhp/ConLib.php");
    include_once("GenUti.inc.php");
}
else include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
$gbTrans	= false;
/**
*   Contabilizacion de una transaccionde inventario
*   @param      $db     Object      Byref, Coneccion ala base de datos
*   @param      $pRNum  Integer     Nmero de registro del comprobante
**/
function fContabInv($pRNum){
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    $db = &ADONewConnection('mysql');
    $db->autoRollback = true;
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
//  $db->PConnect('localhost', 'root', 'xx', 'datos');
//  $db->SetFetchMode(ADODB_FETCH_BOTH);
//  $db->debug=fGetParam('pAdoDbg', 0);
    $trSql = "SELECT com_tipocomp, cla_descripcion, cla_tipocomp, cla_contabilizacion, cla_indtransfer,
                    cla_ctaorigen, cla_ctadestino, cla_auxorigen,
                    cla_auxdestino, cla_ctaingresos, cla_ctacosto, cla_ctadiferencia, cla_reqreferencia,
                    cla_reqsemana,cla_clatransaccion, cla_indicosteo, cla_ImpFlag
            FROM concomprobantes JOIN genclasetran ON cla_tipocomp = com_tipocomp
            WHERE com_regnumero = " . $pRNum;
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
    $rs->MoveFirst();
    $cla = $rs->FetchNextObject(false);
/*  error_log ("func\r\n", 3, "/tmp/my-errors.log");
    error_log (" SQL: " . $trSql . "\r\n", 3, "/tmp/my-errors.log");
    error_log (print_r($cla) . " contab1: " . $cla->cla_contabilizacion . "\r\n", 3, "/tmp/my-errors.log");
 */
    if (!$cla->cla_contabilizacion ) return true; //					No Procesar si o es transaccion contabilizable
    $trSql = "SELECT
                com_regnumero,
                com_numcomp,
                com_codreceptor,
                com_concepto,
                det_secuencia,
                det_coditem,
                det_cantequivale,
                ifnull(det_costotal * pro_signo,0000000000.00) as det_costotal,
                if(det_valtotal=0,det_costotal * pro_signo, det_valtotal * pro_signo) as det_valtotal,
                ifnull(det_valtotal * pro_signo, 0000000000.00) AS tmp_valtotal,
                uni_abreviatura,
                ifNULL(par_valor1,'10') AS tmp_sufijocta,
                act_grupo,
                if(act_IvaFlag>0,act_IvaFlag, ifNULL(par_valor3,0000000000.00)) +0000000000.00 as tmp_indiva,
                if(act_IvaFlag, if(det_valtotal = 0000000000.00,det_CosTotal, det_Valtotal) * (if(act_IvaFlag>0,act_IvaFlag, ifNULL(par_valor3,0)) +0000000000.00), 0000000000.00)  AS 'IIV',
            	if(act_IvaFlag>0, 0000000000.00, if(det_valtotal = 0,det_CosTotal, det_Valtotal)) AS 'NIV',
                if(act_IceFlag, det_ValTotal * (if(act_IceFlag>0,act_IceFlag, ifNULL(par_valor3,0000000000.00)) +0000000000.00), 0000000000.00)  AS 'IIC',
                if(act_IceFlag>0, 0000000000.00, if(det_valtotal = 0000000000.00,det_CosTotal, det_Valtotal) ) AS 'NIC',
                com_tsaimpuestos,
		com_tipocomp,
                act_descripcion,
                com_feccontab,
                com_codreceptor,
                com_receptor,
                com_refoperat,
                com_numperiodo,
                com_fecdigita
             FROM invprocesos p JOIN concomprobantes c ON  pro_codproceso = 1 AND p.cla_tipotransacc = c.com_tipocomp  JOIN invdetalle ON det_regnumero = com_regnumero
	               JOIN conactivos ON act_codauxiliar = det_coditem
	               JOIN genparametros ON par_CLAVE ='ACTGRU' AND par_secuencia = act_grupo
	               JOIN genunmedida on uni_codunidad = act_unimedida
            WHERE com_regnumero = " . $pRNum;
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum  );
    $rs->MoveFirst();
    $lastrec= array();
    $fNum=1;
    $fSuma=0;
    $fImpIva=0;
    $fImpIce=0;
    $lastrec['det_estejecucion'] = 0;
    $lastrec['det_fecejecucion'] = 0;
    $lastrec['det_estlibros']   = 0;
    $lastrec['det_feclibros']   = 0;
    $lastrec['det_refoperativa']    = 0;
    $lastrec['det_numcheque']   = 0;
    $lastrec['det_feccheque']   =0;
    $lastrec['det_codcuenta']   = "";
//
    $alTasas = array();
    $alTpte['tapa'] = 0;
    $alTpte['fond'] = 0;
    while (!$rs->EOF) {
        $tra = $rs->FetchNextObject(false);
        if ($fNum ==1 )  { //                                En el primer registro de detalle
            if ($cla->cla_ImpFlag) //                        Se aplica impuestos a la transaccion
                $alTasas=fTraeTasa($db, $tra->com_tsaimpuestos);
            $db->Execute("delete from condetalle WHERE det_regnumero = " . $pRNum );
        }
        $lastrec['det_regnumero'] = $pRNum;
        $lastrec['det_tipocomp'] = $cla->com_tipocomp;
        $lastrec['det_secuencia'] =$fNum;
        $lastrec['det_codcuenta'] = $cla->cla_ctaorigen . $tra->tmp_sufijocta;
        $lastrec['det_valcredito'] = 0;
        $lastrec['det_idauxiliar'] = $tra->det_coditem;
        $fImpIva += $tra->IIV;
        $fImpIva += $tra->IIC;
        $lastrec['det_numcomp'] = $tra->com_numcomp;
        $lastrec['det_clasregistro'] = 0;
//      Si no se ha digitado el valor, asignar el det_costotal como valor y recalcular con impuestos el costo
        $flValUni = 0;
        if ($tra->tmp_valtotal == 0) {
            $tra->tmp_valtotal = $tra->det_costotal;
        }
        if ($cla->cla_ImpFlag == 1 && $tra->tmp_indiva)  { //                        Se aplica impuestos al item y transacc
            $tra->det_costotal = round(($tra->tmp_valtotal + ($tra->tmp_valtotal * $alTasas['iva'] / 100)),2);
        }
        $flValUni = ($tra->det_cantequivale <> 0)? round((abs($tra->tmp_valtotal) / $tra->det_cantequivale),6) : 0 ;
        $flCosUni = ($tra->det_cantequivale <> 0)? round((abs($tra->det_costotal) / $tra->det_cantequivale),6) : 0 ;
        $lastrec['det_glosa'] = $tra->det_cantequivale . "  " . $tra->uni_abreviatura . " a $ " .  $flCosUni;
        $slSql = "UPDATE invdetalle set det_costotal = " . abs($tra->det_costotal) .
                                     ", det_valtotal = " . abs($tra->tmp_valtotal) .
                                     ", det_valunitario = " . $flValUni .
                                     ", det_cosunitario = " . $flCosUni .
                    " WHERE det_regnumero = " . $tra->com_regnumero . " AND det_secuencia = " . $tra->det_secuencia;
        $db->Execute($slSql);
        $lastrec['det_valdebito'] = $tra->det_costotal;
//echo "tipo. " . $tra->com_tipocomp;        
//print_r($alTpte);    
        if (!fInsDetalleCont($db, $lastrec))
            fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp']) . "-" . $lastrec['det_secuencia'] ;
    	if ($cla->cla_indtransfer == 1 ) {
    		$lastrec['det_secuencia'] = $fNum + 10000;
    		$lastrec['det_valdebito'] = 0;
			$lastrec['det_codcuenta'] = $cla->cla_ctadestino . $tra->tmp_sufijocta;    		
    		$lastrec['det_valcredito'] = $tra->det_costotal; // 
    		if (!fInsDetalleCont($db, $lastrec))
            	fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp']) . "-" . $lastrec['det_secuencia'] ;
    	}
        $fSuma += $tra->det_costotal;
        $fNum+=1;
        if ($tra->com_tipocomp == "EP"){ //					 Acumular cantidades de tapas y fondos entregados
        	if ($tra->act_grupo == 210){
        		$slDesc = strtolower(substr(trim($tra->act_descripcion), 0, 4));
				if ($slDesc == "tapa" ||  $slDesc == "fond"){
					$alTpte[$slDesc] += $tra->det_cantequivale;  			
				}
			}
		}	
    }
    if ($fSuma <> 0 && $cla->cla_indtransfer != 1 ) { // Las tranferencias se contabilizan individualmente
        $lastrec['det_valdebito'] = 0;
        $lastrec['det_valcredito'] = 0;
        if ($cla->cla_ImpFlag == 2)  { //                   Se aplica impuestos como rubro separado del costo
            if ($cla->clatransaccion = 1) { //              Es un Ingreso, se paga Iva
                $slCuenta = NZ(fDBValor($db, 'genparametros', 'par_valor1', "par_clave='CIVAPA'"), false);
                $slAuxili = NZ(fDBValor($db, 'genparametros', 'par_valor2', "par_clave='CIVAPA'"), 0);
                if (!$slCuenta) fErrorPage('',"NO SE SE HA DEFINIDO LA CUENTA DE IVA PAGADO 'CIVAPA' ");
                $lastrec['det_valdebito'] = round($fImpIva * (-1) * $alTasas['iva'],2);
            }
            else { //                                   Es un Egreso, se cobra iva
                $slCuenta = NZ(fDBValor($db, 'genparametros', 'par_valor1', "par_clave='CIVACO'"), false);
                $slAuxili = NZ(fDBValor($db, 'genparametros', 'par_valor2', "par_clave='CIVACO'"), 0);
                if (!$slCuenta) fErrorPage('',"NO SE SE HA DEFINIDO LA CUENTA DE IVA COBRADO 'CIVACO' ");
                $lastrec['det_valdebito'] = round($fImpIva * (-1) * $alTasas['iva'],2);
            }
            $lastrec['det_regnumero'] = $pRNum;
            $fNum=+1;
            $lastrec['det_secuencia'] = $fNum;
            $lastrec['det_codcuenta'] = $slCuenta;
            $lastrec['det_idauxiliar'] = 0;
            $lastrec['det_clasregistro'] = 5;
            if (!fInsDetalleCont($db, $lastrec))
                fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;
        } //                                    fin impuestos
//                                              Registro de Cuenta de cierre
        $lastrec['det_regnumero'] = $pRNum;
        $fNum+=1;
        $lastrec['det_secuencia'] =$fNum;
        $lastrec['det_codcuenta'] = $cla->cla_ctadestino;
        $lastrec['det_glosa'] = $tra->com_concepto;
        if ($cla->auxdestino > 0 ) //                           Si la transaccion tiene definido un aux. especifico
            $lastrec['det_idauxiliar'] = $cla->auxdestino;      // se lo aplica, caso contrario,
        else                        //                          se aplica el aux. de la cabecera
            $lastrec['det_idauxiliar'] = $tra->com_codreceptor;
        $lastrec['det_clasregistro'] = 6;
        $lastrec['det_valdebito'] += $fSuma * (-1) ; //         Cambiar el signo
        $lastrec['det_valcredito'] =0;
        if (!fInsDetalleCont($db, $lastrec))
            fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp'] . "-" . $lastrec['det_secuencia'] );
        $sSql ="UPDATE  concomprobantes SET com_estproceso = 5  WHERE com_regnumero = " . $pRNum;
        $db->Execute($sSql);
    }
	if ($tra->com_tipocomp == "EP"  and $tra->com_refoperat >= 28 and ($alTpte['tapa'] > 0 || $alTpte['fond'] > 0)) {
		$flTtte = 0.015;
		$flValor = 0;
		$flTotal = 0;
		$slTipoComp = "TR";
        $db->Execute("delete from concomprobantes WHERE com_tipocomp = 'TR' AND com_numcomp = " . $tra->com_numcomp);
		error_log("1    ", 3 ,"/tmp/err-fff.txt");

		$ilReg = fAgregaComprobante($db, 'TR', $tra->com_numcomp, 0, $tra->com_feccontab, $tra->com_feccontab,
						   $tra->com_feccontab, 0, $tra->com_codreceptor, $tra->com_receptor,
						  'TRANSPORTE A BODEGA', 0, 1, 999, 0, $tra->com_refoperat, 5, -1,
                		  -1, 593, $_SESSION['g_user'], $tra->com_numperiodo, $tra->com_fecdigita);
        $flValor = round(($alTpte['tapa'] * $flTtte),2);
		$flTotal += $flValor; 
//error_log($flValor . "  ", 3 , "/tmp/err-fff.txt");
		$alRegis = 	array(	'det_regnumero'=> $ilReg,
    		'det_tipocomp' => $slTipoComp,
            'det_numcomp'  => $tra->com_numcomp,
            'det_secuencia'=> 1,
            'det_clasregistro' => 0,
            'det_idauxiliar'=> $tra->com_codreceptor ,
            'det_valdebito' => $flValor,
            'det_valcredito'=>0,
            'det_glosa' => 'Por ' . $alTpte['tapa'] . ' tapas a ' .  $flTtte,
            'det_estejecucion' => 0,
            'det_fecejecucion' => '2020-12-31',
            'det_estlibros'  	=> 0,
            'det_feclibros' 	=> '2020-12-31',
            'det_refoperativa' => $tra->com_refoperat,
            'det_numcheque' 	=> 0,
            'det_feccheque' 	=> 0,
            'det_codcuenta' 	=> '1141025' ) ;
		fInsdetalleCont($db, $alRegis);
        $flValor = round($alTpte['fond'] * $flTtte,2);
		$flTotal += $flValor;
		$alRegis['det_secuencia']	= 2;
		$alRegis['det_idauxiliar']	= $tra->com_codreceptor;
		$alRegis['det_valdebito']	= $flValor;
		$alRegis['det_valcredito']	= 0;
		$alRegis['det_glosa']		= 'Por ' . $alTpte['fond'] . ' fondos a ' .  $flTtte;
		$alRegis['det_codcuenta']  	= '1141025';
		fInsdetalleCont($db, $alRegis);
		$alRegis['det_secuencia']	= 3;
		$alRegis['det_idauxiliar']	= 0;
		$alRegis['det_clasereg']	= 99;
		$alRegis['det_valdebito']	= 0;
		$alRegis['det_valcredito']	= $flTotal;
		$alRegis['det_glosa']		= 'Cobrado a  ' . $tra->com_receptor;
		$alRegis['det_codcuenta']  	= '4191010006';
		fInsdetalleCont($db, $alRegis);
	}
    return true;
}
if (fGetparam('pGen', false) && fGetparam('pReg', false)) //  El proceso es llamado directamente
    fContabInv(fGetparam('pReg', false));
?>
