<?php
/**		Contabilizacion de una transaccion de inventario aplicando plantilla
 *	@param int 	$pReg	com_Regnumero del comprobante
 *	@return		void
 *	@rev	jvl	08/12/09	Modificacion de Manejo de periodos para seleccionar correctamente los datos
 *	@param 	PPro	Codigo de proceso en invprocesos al que debe aplicarse
 *	@param  pTipo   Tipo de comprobante a aplicar (si se omite, se aplica a todos los que componen el proceso)
 *	@param  pPerI	Periodo Inicial
 *	@param  pPerF	Periodo Final
 *	@param  pGen    inidicador de generaion del proceso: 1= Un comprobante especifico, 2= Un periodo, 3=Rango de p�riodos
 *	@param  preg	Numero de Registro del comprobante a aplicar
 *	@rev	jvl	30/01/10  Ajustes en instruccion SQl para procesar valores segun la naturaleza de la transaccion y no segun el indicador "signo" de invprocesos.
 *			Para evitar que se encere invdetalle
 *	@rev	jvl	22/02/10 Ajustes en Asignacion de valores para permitir transacciones con cantidfades en neg y que tengan efecto contable inverso.
 *	@rev	jvl	10/03/10 Incluir variable SSC (sufijo Subccategoria) para patrones de contabilizacion en cascada: Subcategoria + grupo de item
 *	@rev	jvl	21/07/10 Mejorar el filtrado inicial de comprobantes cuando se procesa periodos, para incluir solo los contabilizables
*	@rev	jvl	28/07/10	Excluir los items no inventariables de la contabilizacion
*	@rev	jvl	07/09/10	Actualizar status de comprobante al contabilizar (=5)
 **/
//error_reporting (E_ALL);
include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
$gbTrans	= false;
/**
*   Contabilizacion de una transaccionde inventario. Se presume por omision que el proceso en el de Inventario (Kardex)
*   @param      $db     Object      Byref, Coneccion ala base de datos
*   @param      $pRNum  Integer     N�mero de registro del comprobante
**/
function fContabInv($pRNum){
    global $db, $pPro;
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
    if (!$rs) fErrorPage(''," * NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
    $rs->MoveFirst();
    $cla = $rs->FetchNextObject(false);
/*  error_log ("func\r\n", 3, "/tmp/my-errors.log");
    error_log (" SQL: " . $trSql . "\r\n", 3, "/tmp/my-errors.log");
    error_log (print_r($cla) . " contab1: " . $cla->cla_contabilizacion . "\r\n", 3, "/tmp/my-errors.log");
 */
	// @TODO:  Soportar tres estados de IVA: 0=Excento, 1=Imponible, 2=Incluido!!!!!!!!!!
    ferr("<br> CONTABILIZA " . $cla->cla_contabilizacion );
    if (!$cla->cla_contabilizacion ) return true; //					No Procesar si o es transaccion contabilizable
    //							Cambio de pro_signo a cla_clatransaccion  #fah 30/01/10
    /* $trSql = "SELECT
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
                com_fecdigita,
		per_codAnterior as SFR,
		act_IvaFlag as TIVA
             FROM invprocesos p
		    JOIN concomprobantes c ON  pro_codproceso = " . $pPro . " AND p.cla_tipotransacc = c.com_tipocomp
		    JOIN conpersonas on per_codauxiliar = com_codreceptor
		    JOIN invdetalle ON det_regnumero = com_regnumero
	            JOIN conactivos ON act_codauxiliar = det_coditem
	            JOIN genparametros ON par_CLAVE ='ACTGRU' AND par_secuencia = act_grupo
	            JOIN genunmedida on uni_codunidad = act_unimedida
            WHERE com_regnumero = " . $pRNum .
	    " ORDER BY 1, pro_orden ";
	    */
	/* @TODO: ANALIZAR CASO  DE ICE EN LA CONTABILIZACION */
	$trSql = "SELECT
                com_regnumero,
                com_numcomp,
                com_codreceptor,
                com_concepto,
                det_secuencia,
                det_coditem ITE,
                det_cantequivale,
                ifnull(det_costotal * cla_clatransaccion,0) as COSTO,
                if(det_valtotal=0,det_costotal * cla_clatransaccion, det_valtotal * cla_clatransaccion) as VALOR,
                ifnull(det_valtotal * cla_clatransaccion, 0) AS tmpVALOR,
		det_DescTotal as 'DESC',
                uni_abreviatura,
                ifNULL(gr.par_valor1,'10') AS SGR,
		ifNULL(sg.par_valor1,'10') AS SSG,
		ifNULL(cl.par_valor1,'10') AS SCL,
		ifNULL(gr.par_valor2,'NN') AS GRCTA,
		ifNULL(sc.par_valor1,'') AS SSC,
                act_grupo,
                if(act_IvaFlag>0,act_IvaFlag, ifNULL(iv.par_valor1,0)) +0 as tmp_indiva,
		if(act_IvaFlag<>0, if(det_valtotal = 0,det_CosTotal, det_Valtotal) *
		(if(act_IvaFlag>0,1, 0) +0), 0) AS 'BIIVA',
		if(act_IvaFlag=0, if(det_valtotal = 0,det_CosTotal, det_Valtotal), 0) AS 'BIIVA0',
                if(act_IceFlag, det_ValTotal * (if(act_IceFlag>0,act_IceFlag, 0) +0), 0)  AS 'TICE',
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
                com_fecdigita,
		per_codAnterior as SFR,
		act_IvaFlag as TIVA,
		li.par_valor3 as LIB
             FROM genclasetran
		    JOIN concomprobantes c ON  cla_tipocomp = c.com_tipocomp
		    JOIN conpersonas on per_codauxiliar = com_codreceptor
		    JOIN invdetalle ON det_regnumero = com_regnumero
	            JOIN conactivos ON act_codauxiliar = det_coditem
	            JOIN genparametros gr ON gr.par_CLAVE ='ACTGRU' AND gr.par_secuencia = act_grupo
		    JOIN genparametros sg ON sg.par_CLAVE ='ACTSGR' AND sg.par_secuencia = act_SubGrupo
		    JOIN genparametros sc ON sc.par_CLAVE ='ACTSUB' AND sc.par_secuencia = act_subcategoria
		    JOIN genparametros li ON li.par_CLAVE ='CLIBRO' AND li.par_secuencia = com_libro
		    JOIN genparametros cl ON cl.par_CLAVE ='ACTCLA' AND cl.par_secuencia = act_tipo
		    JOIN genparametros iv ON iv.par_CLAVE ='CTIVA'  AND iv.par_secuencia = act_IvaFlag
	            JOIN genunmedida on uni_codunidad = act_unimedida
            WHERE com_regnumero = " . $pRNum . " and act_inventariable = 1 ";
	    " ORDER BY 1 "
	    ;
	$rs = $db->execute($trSql);
    ferr("<br> Paso b" );
    if (!$rs) fErrorPage('',"*** NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum  );
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
    ferr("<br> Paso c" );
    while (!$rs->EOF) {
	ferr("<br> Paso d" );
        $tra = $rs->FetchNextObject(false);
	ferr("<br> Paso c1" );
	
		$varCta = "";
		$varCta = $tra->GRCTA;
	echo("Secuencia?:".$fNum);
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
		'det_numcheque' 	=> $tra->com_numcomp,
		'det_feccheque' 	=> 0,
		'det_codcuenta' 	=> '' ) ;
	    //if ($cla->cla_ImpFlag) //                        Se aplica impuestos a la transaccion
	    $alTasas=fTraeTasa($db, $tra->com_tsaimpuestos);
	    $db->Execute("delete from condetalle WHERE det_regnumero = " . $pRNum );
	    //											Obtener la plantilla contable de la transaccion-variante
	    
	    ferr("<br> Paso c2" );
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
	    ORDER BY pla_secuencia
	    ";
	    $rsp = $db->Execute($slSql);
	    $cnt = 0;
	    $alPlant=Array();
	    ferr("<br> Paso d2" );
	    while ($arr = $rsp->fetchRow()) {
		// verificar si usa cuenta variable definida en el grupo del activo:
	       if($arr["pla_Cuenta"] === 'GRCTA') $arr["pla_Cuenta"] = $varCta;
	       
	       if (strpos($arr["pla_Cuenta"], "{" ) === false) $arr["patron"] =0;
	       else $arr["patron"] =1;
	       $alPlant[$arr["pla_IndDetalle"]][count($alPlant[$arr["pla_IndDetalle"]])]=$arr;
	    }
	}
	//print_r($alPlant);
	ferr("<br> Paso e" );
	$alAcum["COSTO"] += $tra->COSTO;
	$alAcum["VALOR"] += $tra->VALOR;
	$alAcum["BIIVA"] += $tra->BIIVA;
	$alAcum["BIIVA0"] += $tra->BIIVA0;
	$alAcum["BICE"] += $tra->BICE;
	$alAcum["TICE"] = $tra->TICE;

	$slCodCue = "";
	$ilCodAux = 0;
	if ($tra->VALOR == 0 AND $tra->COSTO != 0) {$tra->VALOR = $tra->COSTO;}
	$tra->IVA = 0;
	if ($cla->cla_ImpFlag == 1 && $tra->tmp_indiva)  { //                        Se aplica impuestos al item y transacc
		$tra->IVA = round(($tra->BIIVA * $alTasas['iva']) / 100, 2);
	}
	ferr("<br><br><br>Iva: " . $tra->IVA. " / " . $cla->cla_ImpFlag ." / ".$tra->tmp_indiva);
	$tra->VALTOTAL= NZ($tra->BIIVA + $tra->BIIVA0 + $tra->IVA, 0); // Valor total, incluyendo Iva
	foreach($alPlant[1] AS $alRec){ 				// --------------------  Procesos aplicados al detalle
	    //      Si no se ha digitado el valor, asignar el det_costotal como valor y recalcular con impuestos el costo
	    //fpr($alRec);
	    ferr("<br> Paso f ". $alRec["pla_Variable"]);
	    $fNum+=1;
	    //echo "<br> sec:" . $fNum;
	    $flValUni = 0;
	    $alVarDat = (array) $tra;
	    //fpr($alVarDat);fpr($alRec);
	    if ($alRec["pla_IndDbCr"] > 0){
		    $lastrec['det_valdebito'] = $alVarDat[$alRec["pla_Variable"]];
		    $lastrec['det_valcredito'] = 0;
		    $lastrec['det_secuencia'] = $fNum * (-1);
		    ferr("  DB". $alRec["pla_Variable"]);
	    }
	    else {
		    $lastrec['det_valdebito'] = 0;
		    $lastrec['det_valcredito'] = $alVarDat[$alRec["pla_Variable"]];
		    $lastrec['det_secuencia'] = $fNum;
		    ferr("  CR". $alRec["pla_Variable"]);
	    }
	    $lastrec['det_codcuenta'] = fAplicaPatron('/\{[A-Za-z0-9]+\}/ix', $alRec["pla_Cuenta"], $alVarDat);;
	    $lastrec['det_idauxiliar'] = (strlen($alRec["pla_ClaseAuxil"]) > 0)? $alVarDat[$alRec["pla_ClaseAuxil"]]: $alRec["pla_AuxilDef"];
	    $lastrec['det_clasregistro'] = 1;

	    print("Det_CANTEQUIVALENTE".$tra->det_cantequivale);
	    print("Det_COSTO".$tra->COSTO);


	    $flValUni = ($tra->det_cantequivale <> 0)? round(($tra->VALOR / $tra->det_cantequivale),6) : 0 ;
	    $flCosUni = ($tra->det_cantequivale <> 0)? round(($tra->COSTO / $tra->det_cantequivale),6) : 0 ;
	    $lastrec['det_glosa'] = $tra->det_cantequivale . "  " . $tra->uni_abreviatura . " a $ " .  $flCosUni;
	    /*$slSql = "UPDATE invdetalle set det_costotal = " . abs($tra->COSTO) .
					    ", det_valtotal = " . abs($tra->VALOR) .
					    ", det_valunitario = " . $flValUni .
					    ", det_cosunitario = " . $flCosUni .
			    " WHERE det_regnumero = " . $tra->com_regnumero . " AND det_secuencia = " . $tra->det_secuencia;
			    */
	   /* $slSql = "UPDATE invdetalle set  ".
					    " det_valunitario = " . $flValUni .
					    ", det_cosunitario = " . $flCosUni .
			    " WHERE det_regnumero = " . $tra->com_regnumero . " AND det_secuencia = " . $tra->det_secuencia;

	    $db->Execute($slSql);*/
//print_r($lastrec);
	    //echo"<br>c detalle con";fpr($lastrec);
//	   print_r($lastrec);
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
		    
		    /*if ($ilCodAux == 25092){
			    print("---------------------->INDICADOR:".$alRec["pla_IndDbCr"]);
			    print("---------------------->INDICADOR=TIPO:".$alAcCt[$slCodCue][$ilCodAux]['tipo']);
			    print("---------------------->INDICADOR=CUENTA:".isset($alAcCt[$slCodCue]));
		    }*/
	    }
	    //$alAcCt[$slCodCue][$ilCodAux]['tipo'] = $alRec["pla_IndDbCr"]; //@rev esl 24/01/2012 que todas las transacciones tengan indicador de transaccion
	    
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
			if ($ilCodAux == 25092){
			    print("---------------------->TIPO:".$alDat["tipo"]);
			    print_r($alDat);
			    }
			if ($alDat["tipo"] == 1){
				$lastrec['det_valdebito'] = $alDat["valor"];
				$lastrec['det_valcredito'] = 0;
				$lastrec['det_secuencia'] = $fNum * (-200);	// Debitos primero
			} else {
				$lastrec['det_valdebito'] = 0;
				$lastrec['det_valcredito'] = $alDat["valor"];
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
//die("--nnnn---");
    
    $slSql = "UPDATE concomprobantes SET com_estproceso = " . 5 .   //#fah 07/09/10
			    " WHERE com_regnumero = " . $tra->com_regnumero ;
			    
    $db->Execute($slSql);
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
//echo "<br><br><br>PATRON : " . $pPatr . " / " .  $pText ;
//echo "<br> ";
//obsafe_print_r($pVars);
	$slCodCue="";
	if(preg_match_all($pPatr, $pText, $alVars)){
		foreach($alVars AS $slVar){ // resolver variables en el codigo de la cuenta
//echo "<br>------------<br>patr: " ; fpr($slVar);
//echo "<br>------------<br>variables: " ; fpr($pVars);
			if (is_array($slVar)){
			    foreach($slVar AS $kk=> $slVar2) {
//echo "<br>------------<br>var2: " ; fpr($slVar2);
				    $alk=array("{","}");
				    $slVar2=str_replace($alk, "",$slVar2);
//echo "<br>------------<br>var2-2: str_resplace($slVar2 " . $pVars[$slVar2] .  " -- " . $pText .")" ; fpr($slVar2);
				    $pText = str_replace("{".$slVar2."}", $pVars[$slVar2], $pText);
//echo " reempl:   " . $slVar2. " / " . $slCodCue;
			    }
			    $slCodCue = $pText;
			} else {
				$alk=array("{","}");
				$slVar=str_replace($alk, "",$slVar);
				$slCodCue = str_replace($slVar, $pVars[$slVar], $pText);
			}
		}
	}
	if (!is_array($alVars) || (is_array($alVars) && is_array($alVars[0]) && count($alVars[0]) <1)) return $pText;
//echo  " //// " . $slCodCue;
	return $slCodCue;
}

function fpr($a){
	echo "<br><pre>";print_r($a);
	echo "</pre><br>";
}
function ferr($msg="-"){
	$dbg = fGetparam('pAppDbg', false);
	if ($dbg == 1) echo "<br>" . $msg;
}

function fContabPer ($pIni, $pFin=0){
    global $db, $pPro;

    $pTip = fGetparam("pTip");
    if (strlen($pTip) <> 0) {
	$slCond = "com_tipocomp in(" . $pTip . ")";
    }
    $slCond .= strlen($slCond) >0 ? " AND " :"";

    $slCond .= " com_numperiodo " ;


    if ($pFin <> 0) {		// Si hay parametro de periodo final, armar condicion de periodo multiple
	$slCond .= " BETWEEN " . $pIni . " AND " . $pFin;
    } else $slCond .= " = " . $pIni ;

					    // fah081209
    /*$slSql = "SELECT distinct com_regnumero
		    FROM invprocesos p JOIN concomprobantes c ON pro_codproceso = " . $pPro . " AND com_tipocomp = cla_tipotransacc
		    WHERE " . $slCond;
    */
    /*$slSql = "SELECT distinct com_regnumero
		    FROM concomprobantes c
		    WHERE " . $slCond;
  */		    
    $slSql = "SELECT DISTINCT com_regnumero, com_tipocomp, com_numcomp, cla_Contabilizacion
		FROM concomprobantes c 
		JOIN genclasetran ON cla_aplicacion = 'IN' AND cla_tipocomp = com_tipocomp
		JOIN invprocesos ON pro_codproceso = 1 AND cla_tipotransacc = com_tipocomp 
		WHERE " . $slCond . " AND cla_contabilizacion = 1 " ; // #fah21/07/10   Solo movimientos contabilizables del Kardex
	    
    $rs = $db->execute($slSql);

    if (!$rs) fErrorPage('',"** NO SE PUDO SELECCIONAR LAS TRANSACCIONES " . $pRNum  );
     while ($tra = $rs->FetchNextObject(false)) {
	fContabInv($tra->com_regnumero);
     }
}
function FmensajeSalida($pMens){
    echo "<br> " . $_SESSION['g_empre']. ".- CONTABILIZACION ";
    echo "<br>$pMens";
    echo "<br>Proceso Completado! ";
}

/*----------------------------------------------------------------------------------------------------------------------------
 *	Procesamiento
 *	Se bifurca el procesamiento segun el parametro pGen, si este no esta presente, no se ejecuta nada a menos que se llame
 *	expresamente la funcion y este script funciona como una libreria de apoyo.
 *	Para que se ejecute Standalone, se requiere el parametro pGen > 0, y definir un periodo o rango de periodos y
 *	opcionalmente un tipo de comprobante
 ----------------------------------------------------------------------------------------------------------------------------**/
    //ini_set('memory_limit', '64M');
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    $db = &ADONewConnection('mysql');
    $db->autoRollback = true;
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
    $db->debug=fGetParam('pAdoDbg', 0);
    $blTipo = fGetparam('pGen', 0) ;
    $pPro = fGetparam('pPro', 1) ; // Se presume por omision que el proceso es el de kardx de inv.
    $ilPer = fGetparam('pPer', false);
    $ilPerI = fGetparam('pPerI', 0);
    $ilPerF = fGetparam('pPerF', 0);
    if($ilPerI >0 and $ilPerF >= $ilPerI) $blTipo = 3;
    if($ilPer >0 ) $blTipo = 2;
    switch($blTipo){
	case 1:		// Aplicar a un comprobante
	    if (fGetparam('pReg', false) )
		fContabInv(fGetparam('pReg', false));
	    break;		//Aplicar a un periodo
	case 2:
	    if (fGetparam('pPer', false) )
		fContabPer();
		fMensajeSalida("PERIODO " . $ilPer);
	    break;
	case 3:		//Aplicar a un periodo multiple.
	    fContabPer($ilPerI, $ilPerF);
	    fMensajeSalida("PERIODO DESDE " . $ilPerI . " HASTA " . $ilPerF);
	    break;
    }
    //

if (fGetparam('pGen', false) && fGetparam('pReg', false)) //  El proceso es llamado directamente para una factura
    fContabInv(fGetparam('pReg', false));
if (fGetparam('pGen', false) && fGetparam('pPer', false)) //  El proceso es llamado directamente para un periodos
    fContabPer(fGetparam('pPer', false));
if (fGetparam('pGen', false) && fGetparam('pPerI', false)) //  El proceso es llamado directamente para rango de periodos
    fContabPer(fGetparam('pPerI', false), fGetparam('pPerF', false));

?>
