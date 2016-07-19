<?php
/**		Contabilizacion de una transaccion de inventario aplicando plantilla (conplantillas)
 *	@param int 	$pReg	com_Regnumero del comprobante
 *	@return		void
 **/
//error_reporting (E_ALL);
include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
$gbTrans	= false;
/**
*   Contabilizacion de una transaccionde inventario
*   @param      $db     Object      Byref, Coneccion ala base de datos
*   @param      $pRNum  Integer     Nùmero de registro del comprobante
**/
function fContabInv($pRNum){
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    $db = &ADONewConnection('mysql');
    $db->autoRollback = true;
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
//  $db->PConnect('localhost', 'root', 'xx', 'datos');
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
    $db->debug=fGetParam('pAdoDbg', 0);
    $trSql = "SELECT com_tipocomp com_tipocomp,
					cla_descripcion cla_descripcion,
					cla_tipocomp cla_tipocomp,
					cla_contabilizacion cla_contabilizacion,
					cla_indtransfer cla_indtransfer,
                    cla_ctaorigen cla_ctaorigen,
					cla_ctadestino cla_ctadestino,
					cla_auxorigen cla_auxorigen,
                    cla_auxdestino cla_auxdestino,
					cla_ctaingresos cla_ctaingresos,
					cla_ctacosto cla_ctacosto,
					cla_ctadiferencia cla_ctadiferencia,
					cla_reqreferencia cla_reqreferencia,
                    cla_reqsemana cla_reqsemana,
					cla_clatransaccion cla_clatransaccion,
					cla_indicosteo cla_indicosteo,
					cla_ImpFlag cla_ImpFlag
            FROM concomprobantes JOIN genclasetran ON cla_tipocomp = com_tipocomp
            WHERE com_regnumero = " . $pRNum; // Alias de columna en minusculas
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
    $rs->MoveFirst();
    $cla = $rs->FetchNextObject(false);
/*  error_log ("func\r\n", 3, "/tmp/my-errors.log");
    error_log (" SQL: " . $trSql . "\r\n", 3, "/tmp/my-errors.log");
    error_log (print_r($cla) . " contab1: " . $cla->cla_contabilizacion . "\r\n", 3, "/tmp/my-errors.log");
 */
	// @TODO:  Soportar tres estados de IVA: 0=Excento, 1=Imponible, 2=Incluido!!!!!!!!!!
    if (!$cla->cla_contabilizacion ) return true; //					No Procesar si o es transaccion contabilizable
    $trSql = "SELECT
                com_regnumero,
                com_numcomp,
                com_codreceptor,
                com_concepto,
                det_secuencia,
                det_coditem ITE,
                det_cantequivale,
                ifnull(det_costotal * pro_signo,0) as COSTO,
                if(det_valtotal=0,det_costotal * pro_signo, det_valtotal * pro_signo) as VALOR,
                ifnull(det_valtotal * pro_signo, 0) AS tmpVALOR,
                uni_abreviatura,
                ifNULL(par_valor1,'10') AS SGR,
                act_grupo,
                if(act_IvaFlag>0,act_IvaFlag, ifNULL(par_valor3,0)) +0 as tmp_indiva,
				if(act_IvaFlag<>0, if(det_valtotal = 0,det_CosTotal, det_Valtotal) * 
						(if(act_IvaFlag>0,1, 0) +0), 0) AS 'BIIVA', 
				if(act_IvaFlag=0, if(det_valtotal = 0,det_CosTotal, det_Valtotal), 0) AS 'BIIVA0', 
                if(act_IceFlag, det_ValTotal * (if(act_IceFlag>0,act_IceFlag, ifNULL(par_valor3,0)) +0), 0)  AS 'TICE',
                if(act_IceFlag>0, 0, if(det_valtotal = 0,det_CosTotal, det_Valtotal) ) AS 'BICE',
                com_tsaimpuestos,
				com_tipocomp,
                act_descripcion,
                com_feccontab,
                com_emisor EMI,
				com_codreceptor REC,
                com_receptor txtREC,
				act_sufijocuenta SIT,
                com_refoperat ,
                com_numperiodo,
				com_libro,
                com_fecdigita
             FROM invprocesos p JOIN concomprobantes c ON  pro_codproceso = 1 AND p.cla_tipotransacc = c.com_tipocomp  JOIN invdetalle ON det_regnumero = com_regnumero
	               JOIN conactivos ON act_codauxiliar = det_coditem
	               JOIN genparametros ON par_CLAVE ='ACTGRU' AND par_secuencia = act_grupo
	               JOIN genunmedida on uni_codunidad = act_unimedida
            WHERE com_regnumero = " . $pRNum;
	$rs = $db->execute($trSql);
	if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum  );
	$alAcum=Array();
	$alAcum["COSTO"] = 0;
	$alAcum["VALOR"] = 0;
	$alAcum["BIIVA"] = 0;
	$alAcum["BIIVA0"] = 0;
	$alAcum["BICE"] = 0;
	$alAcum["TICE"] = 0;
	
    $rs->MoveFirst();
    $lastrec= array();
    $fNum=1;
    $fSuma=0;
    $fImpIva=0;
    $fImpIce=0;
    /*$lastrec['det_regnumero'] = $pRNum;
    $lastrec['det_tipocomp'] = $cla->com_tipocomp;
    $lastrec['det_estejecucion'] = 0;
    $lastrec['det_fecejecucion'] = 0;
    $lastrec['det_estlibros']   = 0;
    $lastrec['det_feclibros']   = 0;
    $lastrec['det_refoperativa']    = 0;
    $lastrec['det_numcheque']   = 0;
    $lastrec['det_feccheque']   =0;
    $lastrec['det_codcuenta']   = "";
   */

    $alTasas = array();
    $alTpte['tapa'] = 0;
    $alTpte['fond'] = 0;
	$fNum=1;
    while (!$rs->EOF) {
        $tra = $rs->FetchNextObject(false);
        if ($fNum ==1 )  { //                                En el primer registro de detalle
	    $lastrec = array(
		'det_regnumero' => $pRNum,
		'det_tipocomp' => $cla->com_tipocomp,
		'det_numcomp'  => $tra->com_numcomp,
		'det_secuencia'=> $fNum,
		'det_clasregistro' => 0,
		'det_idauxiliar'=> 0,
		'det_valdebito' => 0,
		'det_valcredito'=>0,
		'det_glosa' => '',
		'det_estejecucion' => 0,
		'det_fecejecucion' => '2020-12-31',
		'det_estlibros'  	=> 0,
		'det_feclibros' 	=> '2020-12-31',
		'det_refoperativa' => $tra->com_refoperat,
		'det_numcheque' 	=> $tra->com_refoperat,
		'det_feccheque' 	=> 0,
		'det_codcuenta' 	=> '' ) ;

            $alTasas=fTraeTasa($db, $tra->com_tsaimpuestos);
            $db->Execute("delete from condetalle WHERE det_regnumero = " . $pRNum );
			// Obtener la plantilla contable de la transaccion-variante
	    $slSql="SELECT pla_Secuencia,
		    pla_ClaseReg, 
		    pla_IndDetalle, 
		    pla_IndDbCr, 
		    pla_Variable, 
		    pla_Cuenta, 
		    pla_SufijoCta, 
		    pla_ClaseAuxil, 
		    pla_Observac, 
		    pla_AuxilDef
	    FROM
		    conplantillas 
	    WHERE
		    pla_TipoTrans = '" . $tra->com_tipocomp . "'
		    and pla_Variante = " . $tra->com_libro . "
		    and pla_estado= 1
	    ";
	    $rsp = $db->Execute($slSql);
	    //echo $slSql;
	    $cnt = 0;
	    $alPlant=Array();
	    while ($arr = $rsp->fetchRow()) {
	       if (strpos($arr["pla_Cuenta"], "{" ) === false) $arr["patron"] =0;
	       else $arr["patron"] =1;
	       $alPlant[$arr["pla_IndDetalle"]][count($alPlant[$arr["pla_IndDetalle"]])]=$arr;
	    }
	}
		$alAcum["COSTO"] += $tra->COSTO;
		$alAcum["VALOR"] += $tra->VALOR;
		$alAcum["BIIVA"] += $tra->BIIVA;
		$alAcum["BIIVA0"] += $tra->BIIVA0;
		$alAcum["BICE"] += $tra->BICE;
		$alAcum["TICE"] = $tra->TICE;
		
		$slCodCue = "";
		$ilCodAux = 0;
		if ($tra->VALOR == 0) {$tra->VALOR = $tra->COSTO;}
		$tra->IVA = 0;
		if ($cla->cla_ImpFlag == 1 && $tra->tmp_indiva)  { //                        Se aplica impuestos al item y transacc
			$tra->IVA = round(($tra->BIIVA * $alTasas['iva']) / 100, 2);
		}
		$tra->VALTOTAL= NZ($tra->BIIVA + $tra->BIIVA0 + $tra->IVA, 0); // Valor total, incluyendo Iva
		foreach($alPlant[1] AS $alRec){ 				// --------------------  Procesos aplicados al detalle
			//      Si no se ha digitado el valor, asignar el det_costotal como valor y recalcular con impuestos el costo
//fpr($alRec);
			$fNum+=1;
//echo "<br> sec:" . $fNum;
			$flValUni = 0;
			$alVarDat = (array) $tra;
//fpr($alVarDat);fpr($alRec);
			if ($alRec["pla_IndDbCr"] > 0){
				$lastrec['det_valdebito'] = abs($alVarDat[$alRec["pla_Variable"]]);
				$lastrec['det_valcredito'] = 0;
				$lastrec['det_secuencia'] = $fNum * (-1);
			}
			else {
				$lastrec['det_valdebito'] = 0;
				$lastrec['det_valcredito'] = abs($alVarDat[$alRec["pla_Variable"]]);
				$lastrec['det_secuencia'] = $fNum;
			}
			$lastrec['det_codcuenta'] = fAplicaPatron('/\{[A-Za-z0-9]+\}/ix', $alRec["pla_Cuenta"], $alVarDat);;
			$lastrec['det_idauxiliar'] = (strlen($alRec["pla_ClaseAuxil"]) > 0)? $alVarDat[$alRec["pla_ClaseAuxil"]]: $alRec["pla_AuxilDef"];
			$lastrec['det_clasregistro'] = 1;

			$flValUni = ($tra->det_cantequivale <> 0)? round((abs($tra->VALOR) / $tra->det_cantequivale),6) : 0 ;
			$flCosUni = ($tra->det_cantequivale <> 0)? round((abs($tra->COSTO) / $tra->det_cantequivale),6) : 0 ;
			$lastrec['det_glosa'] = $tra->det_cantequivale . "  " . $tra->uni_abreviatura . " a $ " .  $flCosUni;
			$slSql = "UPDATE invdetalle set det_costotal = " . abs($tra->COSTO) .
							", det_valtotal = " . abs($tra->VALOR) .
							", det_valunitario = " . $flValUni .
							", det_cosunitario = " . $flCosUni .
					" WHERE det_regnumero = " . $tra->com_regnumero . " AND det_secuencia = " . $tra->det_secuencia;
			$db->Execute($slSql);
//echo"<br>c detalle con";fpr($lastrec);
			if (!fInsDetalleCont($db, $lastrec))
				fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp']) . "-" . $lastrec['det_secuencia'] ;
			if ($cla->cla_indtransfer == 1 ) {
				$lastrec['det_secuencia'] = $fNum + 10000;
				$lastrec['det_valdebito'] = 0;
				$lastrec['det_codcuenta'] = $cla->cla_ctadestino . $tra->SGR;    		
				$lastrec['det_valcredito'] = $tra->COSTO; // 
				if (!fInsDetalleCont($db, $lastrec))
					fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp']) . "-" . $lastrec['det_secuencia'] ;
			}
			$fSuma += $tra->COSTO;
	        $fNum+=1;
			
/*        if ($tra->com_tipocomp == "EP"){ //					 Acumular cantidades de tapas y fondos entregados
        	if ($tra->act_grupo == 210){
        		$slDesc = strtolower(substr(trim($tra->act_descripcion), 0, 4));
				if ($slDesc == "tapa" ||  $slDesc == "fond"){
					$alTpte[$slDesc] += $tra->det_cantequivale;  			
				}
			}
			}*/
		}
		foreach($alPlant[5] AS $alRec){ 				// --------------------  Acumulacion de Valores
			$alVarDat = (array) $tra;
//echo "ACUMULACION " . $alRec["pla_Cuenta"] . " - " .$alRec["pla_ClaseAuxil"] . " - " . $alRec["pla_Variable"];
			$slCodCue = fAplicaPatron('/\{[A-Za-z0-9]+\}/ix', $alRec["pla_Cuenta"], $alVarDat);;
			$ilCodAux = (strlen($alRec["pla_ClaseAuxil"]) > 0)? $alVarDat[$alRec["pla_ClaseAuxil"]]: $alRec["pla_AuxilDef"];
			
			//if (!isset($alAcCt[$slCodCue])) $alAcCt[$slCodCue]= array();
			if (!isset($alAcCt[$slCodCue])) {
				$alAcCt[$slCodCue][$ilCodAux]['tipo'] = $alRec["pla_IndDbCr"];
				$alAcCt[$slCodCue][$ilCodAux]['valor'] = 0;
			}
			$alAcCt[$slCodCue][$ilCodAux]["valor"] += $alVarDat[$alRec["pla_Variable"]];
 //fpr($alAcCt);fpr($alVarDat);
		} //  Fin Acumulacion de valores
    }
//fpr($alAcCt); // fpr($alVarDat);	
	// --------------------  Procesos aplicados Globalmente
//echo "ACUMULADOS:"; fpr($alAcCt);
    if ($cla->cla_indtransfer != 1 ) { // Las tranferencias se contabilizan individualmente
		$lastrec['det_clasregistro'] = 5;
		foreach($alAcCt AS $slCodCue => $alCtas){ 				// --------------------  Procesos aplicados al detalle
//fpr($alCtas); 			
			$lastrec['det_codcuenta'] = $slCodCue;
			foreach($alCtas AS $ilCodAux => $alDat) {
				$lastrec['det_valdebito'] = 0;
				$lastrec['det_valcredito'] = 0;
//fpr($alDat); 			
//echo "<br>CUE:" . $slCodCue;
				//foreach($alDat AS $ilCodAux => $alDat2) {
//fpr($alDat2);
					$fNum+=1;
					$lastrec['det_idauxiliar'] = $ilCodAux;
					if ($alDat["tipo"] == 1){
						$lastrec['det_valdebito'] = abs($alDat["valor"]);
						$lastrec['det_valcredito'] = 0;
						$lastrec['det_secuencia'] = $fNum * (-200);	// Debitos primero
					} else {
						$lastrec['det_valdebito'] = 0;
						$lastrec['det_valcredito'] = abs($alDat["valor"]);
						$lastrec['det_secuencia'] = $fNum ;	// credirtos al final
					}
					$lastrec['det_glosa'] = $slCodCue . " / " . $ilCodAux;
					if (!fInsDetalleCont($db, $lastrec))
						fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp']) . "-" . $lastrec['det_secuencia'] ;
					/*if ($cla->cla_indtransfer == 1 ) {
						$lastrec['det_secuencia'] = $fNum + 10000;
						$lastrec['det_valdebito'] = 0;
						$lastrec['det_codcuenta'] = $cla->cla_ctadestino . $tra->SGR;    		
						$lastrec['det_valcredito'] = $tra->COSTO; // 
						if (!fInsDetalleCont($db, $lastrec))
							fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp']) . "-" . $lastrec['det_secuencia'] ;
					}*/
				//}
			}
		}
	}
    return true;
}
/*
 *	Devuelve una cadena de texto ala cual se le ha plicado  una sustitucion de patrones
 *	@param	string	$pPatr	Patron a buscar
 *	@param	string	$pText	Texto original
 *	@param	array	$pVars	Arreglo de Variables a reemplazar
 *	@returns  string	pText modificado por los valores de pVars segun pPatr
 *	
 **/
function fAplicaPatron($pPatr, $pText, $pVars){
	$slCodCue="";
	if(preg_match_all($pPatr, $pText, $alVars)){
		foreach($alVars AS $slVar){ // resolver variables en el codigo de la cuenta
//echo "patr: " ; fpr($slVar);			
			if (is_array($slVar)){
				foreach($slVar AS $slVar2) {
					$alk=array("{","}");
					$slVar2=str_replace($alk, "",$slVar2);
					$slCodCue = str_replace($slVar, $pVars[$slVar2], $pText);
//echo "  " . $slVar2. " / " . $slCodCue;
				}
			} else {
				$alk=array("{","}");
				$slVar=str_replace($alk, "",$slVar);
				$slCodCue = str_replace($slVar, $pVars[$slVar], $pText);
			}
		}
	}
	if (!is_array($alVars) || (is_array($alVars) && is_array($alVars[0]) && count($alVars[0]) <1)) return $pText;
	return $slCodCue;
}

function fpr($a){
	echo "<br><pre>";print_r($a);
	echo "</pre><br>";
}
function ferr($msg="-"){
	$dbg = fGetparam('pAppDbg', false);
	if ($dbg = 1) echo "<br>" . $msg;
}

if (fGetparam('pGen', false) && fGetparam('pReg', false)) //  El proceso es llamado directamente
    fContabInv(fGetparam('pReg', false));
?>
