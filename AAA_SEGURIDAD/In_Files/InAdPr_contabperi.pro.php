<?php
error_reporting (E_ALL);
include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
$db->debug=fGetParam('pAdoDbg', 0);
/**
*   Contabilizacion de un periodo de Inventario
*   @param      $pIni Integer     Periodo Inicial
*   @param      $pFin Integer     Periodo Final
**/
function fContPerInv($pIni, $pFin){
    global $fSuma;
    global $fSumV;
    global $fSumD;
    global $fNum;
    global $pRNum;
    global $tra;
    global $lastTra;
    global $lastRcp;
    global $lastEmi;
    global $lastIte;
//
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    $db = &ADONewConnection('mysql');
    $db->autoRollback = true;
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
//  $db->PConnect('localhost', 'root', 'xx', 'datos');
//  $db->SetFetchMode(ADODB_FETCH_BOTH);
    $db->debug=fGetParam('pAdoDbg', 0);
/*  error_log ("func\r\n", 3, "/tmp/my-errors.log");
    error_log (" SQL: " . $trSql . "\r\n", 3, "/tmp/my-errors.log");
    error_log (print_r($cla) . " contab1: " . $cla->cla_contabilizacion . "\r\n", 3, "/tmp/my-errors.log");
 */
 /**
    Seleccionar transacciones del periodo, que no son costeadoras (indicosteo = 0)
 **/
    $trSql = "SELECT com_tipocomp, com_numcomp, com_codreceptor, com_emisor,
                        com_tsaimpuestos, left(com_concepto, 30) as com_concepto,
                        det_regnumero, det_secuencia, det_coditem,
                    	cla_clatransaccion, cla_indtransfer,
                    	cla_ctaorigen, cla_ctadestino, cla_ctaingresos, cla_ctadiferencia,
                    	ifNULL(par_valor1,'10') AS gru_sufijocta,
                    	cla_auxorigen, cla_auxdestino, cla_ImpFlag,
                    	det_cantequivale * pro_signo as det_cantequivale,
                    	det_costotal * pro_signo as det_costotal,
                    	det_valtotal * pro_signo as det_valtotal,
                    	uni_abreviatura,
                    	o.cue_tipauxiliar as cue_oriauxil,
                    	d.cue_tipauxiliar as cue_desauxil,
                    	i.cue_tipauxiliar as cue_ingauxil,
                    	e.cue_tipauxiliar as cue_egrauxil,                    	
                    	f.cue_tipauxiliar as cue_difauxil,
                        if(act_IvaFlag, if(det_valtotal = 0,det_CosTotal, det_Valtotal) * (if(act_IvaFlag>0,act_IvaFlag, ifNULL(par_valor3,0)) +0), 0)  AS 'IIV',
                    	if(act_IvaFlag>0, 0, if(det_valtotal = 0,det_CosTotal, det_Valtotal)) AS 'NIV',
                        if(act_IceFlag, det_ValTotal * (if(act_IceFlag>0,act_IceFlag, ifNULL(par_valor3,0)) +0), 0)  AS 'IIC',
                        if(act_IceFlag>0, 0, if(det_valtotal = 0,det_CosTotal, det_Valtotal) ) AS 'NIC'
                FROM invprocesos p JOIN concomprobantes c ON p.cla_tipotransacc = c.com_tipocomp  AND
                        com_numperiodo BETWEEN   " . $pIni . " AND " . $pFin . "
                    	JOIN invdetalle on det_regnumero = com_regnumero
	                    JOIN conactivos ON act_codauxiliar = det_coditem
	                    JOIN genparametros ON par_CLAVE ='ACTGRU' AND par_secuencia = act_grupo
                    	LEFT JOIN genclasetran t on  t.cla_tipocomp = p.cla_tipotransacc AND cla_indicosteo = 0
                    	LEFT JOIN concuentas o on o.cue_codcuenta = cla_ctaorigen
			            LEFT JOIN concuentas d on d.cue_codcuenta = cla_ctadestino
			            LEFT JOIN concuentas i on i.cue_codcuenta = cla_ctaingresos
			            LEFT JOIN concuentas e on e.cue_codcuenta = cla_ctacosto
			            LEFT JOIN concuentas f on f.cue_codcuenta = cla_ctadiferencia
			            LEFT JOIN genunmedida ON uni_codunidad = act_unimedida
                WHERE pro_codproceso = 1 and cla_contabilizacion = 1 " ;
//              Correccion para contabilizar todos los comprobantes, sin importar el estado.
//              WHERE pro_codproceso = 1 and com_estproceso = 5 and cla_contabilizacion = 1 " ;
    if (fGetParam('pTra', false)) $trSql .= " AND com_tipocomp = '" . fGetParam('pTra', false) . "' ";
    $trSql .= " ORDER BY com_tipocomp, com_numcomp, det_secuencia ";
//                        and com_feccontab BEtWEEN '2005-01-28' and '2005-01-28'
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LAS TRANSACCIONES " . $pRNum  );
    $rs->MoveFirst();
    $lastrec= array();
    $fNum=1;
    $fSuma=0;   // Suma del costo
    $fSumV=0;   // Suma del valor de venta
    $fSumD=0;   // Suma de Diferencias venta - costo
    $fImpIva=0;
    $fImpIce=0;
    $lastrec['det_regnumero'] = -1;
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
    while (!$rs->EOF) {
echo $tra->det_regnumero  . "<br>";    	
        $tra = $rs->FetchNextObject(false);
        if ($tra->det_regnumero  != $lastrec['det_regnumero']) {
            if ($fSuma != 0) fCierraComp($db, $lastrec);
            if ($tra->cla_ImpFlag) //                        Se aplica impuestos a la transaccion
                $alTasas=fTraeTasa($db, $tra->com_tsaimpuestos);
            $db->Execute("delete from condetalle WHERE det_regnumero = " . $tra->det_regnumero  );
            $fNum =1;
            $lastrec['det_regnumero'] = $tra->det_regnumero;
        }
        $lastrec['det_tipocomp'] = $tra->com_tipocomp;
        $lastrec['det_regnumero'] = $tra->det_regnumero;
        $lastrec['det_secuencia'] =$fNum;
        $lastrec['det_codcuenta'] = $tra->cla_ctaorigen . $tra->gru_sufijocta;
        $lastrec['det_valcredito'] = 0;
        $lastrec['det_glosa'] = $tra->det_cantequivale . "  " . $tra->uni_abreviatura  ;
        $lastrec['det_idauxiliar'] = $tra->det_coditem;
        $fImpIva += $tra->IIV;
        $fImpIva += $tra->IIC;
        $lastrec['det_numcomp'] = $tra->com_numcomp;
        $lastrec['det_clasregistro'] = 11;
        $lastrec['det_valdebito'] = $tra->det_costotal;
        if (!fInsDetalleCont($db, $lastrec))
            fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['com_tipocomp'] . "-" . $lastrec['com_numcomp']) . "-" . $lastrec['det_secuencia'] ;
    	if ($tra->cla_indtransfer == 1 ) { //  En caso de tranferencias, contabilizar individualmente
    		$lastrec['det_valdebito'] = 0;
    		$lastrec['det_secuencia'] =$fNum + 1000;
    		$lastrec['det_codcuenta'] = $tra->cla_ctadestino . $tra->gru_sufijocta;
echo " trans " . $tra->cla_indtransfer . "br";
echo $tra->cla_ctadestino . $tra->gru_sufijocta;
    		$lastrec['det_valcredito'] = $tra->det_costotal; // 
    		if (!fInsDetalleCont($db, $lastrec))
            	fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp']) . "-" . $lastrec['det_secuencia'] ;
    	}
		else $fSuma += $tra->det_costotal;
        $fSumV += $tra->det_valtotal;
        $fSumD += $tra->det_valtotal - $tra->det_costotal;
        $fNum+=1;
        $lastTra = $tra; //         Para mantener datos del ultimo comprobante procesado.
	$lastRcp = $tra->com_codreceptor;  // ultimos valores de datos importantes  fah Ago 08
	$lastEmi = $tra->com_emisor;
	$lastIte = $tra->det_coditem;
    }
    if ($fSuma != 0 ) fCierraComp($db, $lastrec);
    echo " PROCESO COMPLETADO" ;
}

function fCierraComp($db, & $lastrec) {
    global $fSuma;
    global $fSumV;
    global $fSumD;
    global $fNum;
    global $pImpIva;
    global $pImpIce;
    global $tra;
    global $lastTra;
    global $lastRcp;
    if ($fSuma <> 0) {
        $lastrec['det_valdebito'] = 0;
        $lastrec['det_valcredito'] = 0;
        if ($tra->cla_ImpFlag == 2)  { //                   Se aplica impuestos como rubro separado del costo
            if ($tra->cla_clatransaccion = 1) { //              Es un Ingreso, se paga Iva
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
            $fNum=+1;
            $lastrec['det_secuencia'] = $fNum;
            $lastrec['det_codcuenta'] = $slCuenta;
            $lastrec['det_idauxiliar'] = 0;
            $lastrec['det_clasregistro'] = 15;
            if (!fInsDetalleCont($db, $lastrec))
                fErrorPage('',"NO SE PUDO GRABAR EL DETALLE EXTRA DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;
        }
    }
    $fNum+=1;
    $lastrec['det_secuencia'] =$fNum;
    $lastrec['det_codcuenta'] = $lastTra->cla_ctadestino;
    $lastrec['det_glosa'] = (strlen($lastTra->com_concepto) >0) ? $lastTra->com_concepto : 'Total';
    $lastrec['det_idauxiliar'] = fDefineAux($lastTra->cue_desauxil, $lastrec);
    $lastrec['det_clasregistro'] = 16;
    $lastrec['det_valdebito'] += $fSuma * (-1) ; //         Cambiar el signo
    $lastrec['det_valcredito'] =0;
    if (!fInsDetalleCont($db, $lastrec))
        fErrorPage('',"NO SE PUDO GRABAR EL DETALLE FINAL DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp'] . "-" . $lastrec['det_secuencia'] );
    $fNum=1;
    $fSuma = 0;
    $fSumV = 0;
    $fSumD = 0;
    $fNum  = 0;
    $pRNum = 0;
    $pImpIva = 0;
    $pImpIce = 0;
    return true;
}
function fDefineAux($pTipo, & $pRec){
    global $tra;
    global $lastTra;
    global $lastRcp;
    global $lastEmi;
    global $lastIte;
    switch ($pTipo) {
        case 50:    // Clientes
        case 52:    // Productore
        case 53:    // proveedores
        case 54:    // Empleados
//            return $lastTra->com_codreceptor;
            return $lastRcp;
            break;
        case 14:    // activo Fijo
        case 16:    // producto final
        case 30:    // items de inv
//            return $lastTra->det_coditem;
            return $lastIte;
            break;
        case 15:    // Bodega
//            return $lastTra->com_emisor;
            return $lastEmi;
            break;
        case 80:    // Especif. de costos
        default:
            return 0;
    }
}
/**
    INICIO
**/
set_time_limit(20*60);
$fSuma = 0;
$fSumV = 0;
$fSumD = 0;
$fNum  = 0;
$pRNum = 0;
$pImpIva = 0;
$pImpIce = 0;
$tra=NULL;
$lastTra=NULL;
$lastIte=NULL;
$lastRcp=NULL;
$lastEmi=NULL;
//
//if (fGetparam('pGen', false) && fGetparam('pReg', false)) //  El proceso es llamado directamente
set_time_limit(0);
$inicio = microtime();
$txt="";
$txt .= "<br> Inicio: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
fContPerInv(fGetparam('pPerI', false), fGetparam('pPerF', false));
$final = microtime();
$txt .= "<br> Finalizo: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
$txt .= "<br> TIEMPO UTILIZADO:      ". round(microtime_diff($inicio ,$final),2) . " segs. <br>";
echo $txt;
?>
