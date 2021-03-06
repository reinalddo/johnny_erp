<?php
/**		ARCHIVO RENOMBRADO, ANTES: InAdPr_contabapli.pro.php
 *		Contabilizacion de una transaccion de comercio exterior aplicando plantilla
 *	@param int 	$pReg	com_Regnumero del comprobante
 *	@return		void
 *	@rev	esl	01/03/10 Modificacion del archivo para contabilizaci�n de transacciones de Comercio Exterior
 *	@param 	PPro	Codigo de proceso en invprocesos al que debe aplicarse
 *	@param  pTipo   Tipo de comprobante a aplicar (si se omite, se aplica a todos los que componen el proceso)
 *	@param  pPerI	Periodo Inicial
 *	@param  pPerF	Periodo Final
 *	@param  pGen    inidicador de generaion del proceso: 1= Un comprobante especifico, 2= Un periodo, 3=Rango de p�riodos
 *	@param  preg	Numero de Registro del comprobante a aplicar
 **/
//error_reporting (E_ALL);
include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
$gbTrans	= false;
/**
*   Contabilizacion de una transaccionde. Se presume por omision que el proceso en el de Inventario (Kardex)
*   @param      $db     Object      Byref, Coneccion a la base de datos
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
		    cla_ctadiferencia cla_ctadiferencia,
		    cla_reqreferencia cla_reqreferencia,
                    cla_reqsemana cla_reqsemana,
		    cla_clatransaccion cla_clatransaccion,
		    cla_ImpFlag cla_ImpFlag
            FROM concomprobantes JOIN genclasetran ON cla_tipocomp = com_tipocomp
            WHERE com_regnumero = " . $pRNum; // Alias de columna en minusculas
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage(''," * NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
    $rs->MoveFirst();
    $cla = $rs->FetchNextObject(false);

    ferr("<br> CONTABILIZA " . $cla->cla_contabilizacion );
    if (!$cla->cla_contabilizacion ) return true; //No Procesar si o es transaccion contabilizable

	$trSql = "SELECT
                com_regnumero,
                com_numcomp,
                com_codreceptor,
                com_concepto,
                -- det_secuencia,
                -- det_coditem ITE,
                -- det_cantequivale,
                -- if(det_valtotal=0,det_costotal * cla_clatransaccion, det_valtotal * cla_clatransaccion) as VALOR,
                -- ifnull(det_valtotal * cla_clatransaccion, 0) AS tmpVALOR,
                -- uni_abreviatura,
                com_tipocomp,
                -- act_descripcion,
                com_feccontab,
                com_emisor EMI,
		com_codreceptor REC,
                com_receptor txtREC,
		-- act_sufijocuenta SIT,
                com_refoperat ,
                com_numperiodo,
		com_libro,
                com_fecdigita,
		per_codAnterior as SFR,
		li.par_valor3 as LIB
             FROM genclasetran
		    JOIN concomprobantes c ON  cla_tipocomp = c.com_tipocomp
		    JOIN conpersonas on per_codauxiliar = com_codreceptor
		    -- JOIN invdetalle ON det_regnumero = com_regnumero
	            -- JOIN conactivos ON act_codauxiliar = det_coditem
	            JOIN genparametros li ON li.par_CLAVE ='CLIBRO' AND li.par_secuencia = com_libro
		    -- JOIN genunmedida on uni_codunidad = act_unimedida
            WHERE com_regnumero = " . $pRNum .
	    " ORDER BY 1 "
	    ;
	$rs = $db->execute($trSql);
    ferr("<br> Paso b" );
    if (!$rs) fErrorPage('',"*** NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum  );
	$alAcum=Array();
	$alAcum["VALOR"] = 0;
	
    $rs->MoveFirst();
    $lastrec= array();
    $fNum=1;
    $fSuma=0;
        
    $alTasas = array();
    $alTpte['tapa'] = 0;
    $alTpte['fond'] = 0;
    $fNum=1;
    ferr("<br> Paso c" );
    while (!$rs->EOF) {
	ferr("<br> Paso d" );
        $tra = $rs->FetchNextObject(false);
	ferr("<br> Paso c1" );
        if ($fNum ==1 )  { // En el primer registro de detalle
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
	
	    
	    $db->Execute("delete from condetalle WHERE det_regnumero = " . $pRNum );
	    //	Obtener la plantilla contable de la transaccion-variante
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
	    ";
	    $rsp = $db->Execute($slSql);
	    $cnt = 0;
	    $alPlant=Array();
	    ferr("<br> Paso d2" );
	    while ($arr = $rsp->fetchRow()) {
	       if (strpos($arr["pla_Cuenta"], "{" ) === false) $arr["patron"] =0;
	       else $arr["patron"] =1;
	       $alPlant[$arr["pla_IndDetalle"]][count($alPlant[$arr["pla_IndDetalle"]])]=$arr;
	    }
	}
	//print_r($alPlant);
	ferr("<br> Paso e" );
	
	$alAcum["VALOR"] += $tra->VALOR;
	
	$slCodCue = "";
	$ilCodAux = 0;
	
	//$tra->VALTOTAL= NZ($tra->BIIVA + $tra->BIIVA0 + $tra->IVA, 0); // Valor total, incluyendo Iva
	foreach($alPlant[1] AS $alRec){	// --------------------  Procesos aplicados al detalle
	    
	    ferr("<br> Paso f ". $alRec["pla_Variable"]);
	    $fNum+=1;
	    $flValUni = 0;
	    $alVarDat = (array) $tra;
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

	    //$flValUni = ($tra->det_cantequivale <> 0)? round(($tra->VALOR / $tra->det_cantequivale),6) : 0 ;
	    
	    //$lastrec['det_glosa'] = $tra->det_cantequivale . "  " . $tra->uni_abreviatura . " a $ " .  $flCosUni;
	    /*$slSql = "UPDATE invdetalle set  ".
			" det_valunitario = " . $flValUni .
			", det_cosunitario = " . $flCosUni .
		     "WHERE det_regnumero = " . $tra->com_regnumero . " AND det_secuencia = " . $tra->det_secuencia;

	    $db->Execute($slSql);*/
	    //print_r($lastrec);
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

	}
	foreach($alPlant[5] AS $alRec){ 				// --------------------  Acumulacion de Valores
	    $alVarDat = (array) $tra;
	    $slCodCue = fAplicaPatron('/\{[A-Za-z0-9]+\}/ix', $alRec["pla_Cuenta"], $alVarDat);;
	    $ilCodAux = (strlen($alRec["pla_ClaseAuxil"]) > 0)? $alVarDat[$alRec["pla_ClaseAuxil"]]: $alRec["pla_AuxilDef"];

	    if (!isset($alAcCt[$slCodCue])) {
		    $alAcCt[$slCodCue][$ilCodAux]['tipo'] = $alRec["pla_IndDbCr"];
		    $alAcCt[$slCodCue][$ilCodAux]['valor'] = 0;
	    }
	    $alAcCt[$slCodCue][$ilCodAux]["valor"] += $alVarDat[$alRec["pla_Variable"]];
	    
	} //  Fin Acumulacion de valores
    }
    
    if ($cla->cla_indtransfer != 1 ) { // Las tranferencias se contabilizan individualmente
	$lastrec['det_clasregistro'] = 5;
	foreach($alAcCt AS $slCodCue => $alCtas){ // --------------------  Procesos aplicados al detalle
	    $lastrec['det_codcuenta'] = $slCodCue;
	    foreach($alCtas AS $ilCodAux => $alDat) {
		$lastrec['det_valdebito'] = 0;
		$lastrec['det_valcredito'] = 0;
			$fNum+=1;
			$lastrec['det_idauxiliar'] = $ilCodAux;
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
	    }
	}
    }
//die("--nnnn---");
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
			if (is_array($slVar)){
			    foreach($slVar AS $kk=> $slVar2) {
				    $alk=array("{","}");
				    $slVar2=str_replace($alk, "",$slVar2);
				    $pText = str_replace("{".$slVar2."}", $pVars[$slVar2], $pText);
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
	return $slCodCue;
}


function fGrabaComp(){
    
    $ilNumComp = NZ(fDBValor($db, 'concomprobantes', 'MAX(com_numcomp)', "com_tipocomp = '" .$TComp. "'"),0) + 1 ;
    $ilAux = AplicaAuxil($lastrec['cue_reqauxiliar'], $lastrec['cue_tipauxiliar'], $lastrec['det_idauxiliar'], $ilUltimoProd);
    
    // **************************** Verificar periodo contable ***************************
    //$fecComp = Fecha del Comprobante
    $slSqlper="SELECT per_PerContable, per_estado, per_Semana FROM conperiodos WHERE per_Aplicacion ='CO' AND ".$fecComp." between per_FecInicial and per_FecFinal";
    $rs= $db->Execute($slSqlper);
    $ilPeriodo=0;
    if ($rs->RecordCount() == 1) {
	    $ilPeriodo = $rs->fields[0];
	    if ($rs->fields[1] == -1) fErrorPage('','LA SEMANA ESTA CERRADA, NO SE PUEDE ALTERAR INFORMACION', true, false);
	    }
    if (!isset($ilPeriodo)  || NZ($ilPeriodo) == 0) fErrorPage('','NO SE DETERMINO EL PERIODO CONTABLE CORRESPONDIENTE A ESTA SEMANA', true, false);
    // ------------------------------- GRABAR CABECERA DEL COMPROBANTE CONTABLE --------------------------
    
    // preguntar: codigo de receptor.... $rsdat['det_feccontab'], $ilAux, $rsdat['det_idauxiliar'], $slProductor,
    // 		  Numero de comprobante $pGlosa . " (liq # " . $lastrec['det_numcomp'] . ")",
    //		  Semana y Numero de Proceso 0, 1, 9999, 0, $ilSemana, 5, 1, $ilNumProces,
    if (!fAgregaComprobante($db, $TComp, $ilNumComp, 0, $rsdat['det_feccontab'], $rsdat['det_feccontab'],
                $rsdat['det_feccontab'], 0, 0, 0,
                $pGlosa . " (liq # comprobante )",
                0, 1, 9999, 0, $rs->fields[3], 5, 1, 0,
                593, $slUsuario, $ilPeriodo, date("Y-m-d")))
                fErrorPage('',"NO SE PUDO GRABAR EL COMPROBANTE  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp);
            $ilRegNumero = $db->Insert_ID();
}

function fpr($a){
	echo "<br><pre>";print_r($a);
	echo "</pre><br>";
}
function ferr($msg="-"){
	$dbg = fGetparam('pAppDbg', false);
	if ($dbg == 1) echo "<br>" . $msg;
}

function fContabPer($pIni, $pFin=0){
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
    $slSql = "SELECT distinct com_regnumero
		    FROM concomprobantes c
		    WHERE " . $slCond;
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
//     echo "JVL imprime :   "  $bltipo;
    $pPro = fGetparam('pPro', 1) ; // Se presume por omision que el proceso es el de kardx de inv.
    $ilPer = fGetparam('pPer', false);
    $ilPerI = fGetparam('pPerI', 0);
    $ilPerF = fGetparam('pPerF', 0);
    $ilRegNumero = 0;
    
    // variables agregadas para ingresar la cabecera del comprobante contable
    if(isset($_SESSION["g_user"])) $slUsuario=$_SESSION["g_user"];
    $TComp = fGetparam('pTpComp', false); //Tipo de Comprobante
    //-------------------------
    fGrabaComp();
    
    if($ilPerI >0 and $ilPerF >= $ilPerI) $blTipo = 3;
    if($ilPer >0 ) $blTipo = 2;
    switch($blTipo){
	case 1:		// Aplicar a un comprobante
	    if (fGetparam('pReg', false) )
		//fContabInv(fGetparam('pReg', false));
		fContabInv($ilRegNumero);
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
?>
