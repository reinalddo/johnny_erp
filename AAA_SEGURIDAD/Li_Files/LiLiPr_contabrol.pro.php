<?php
/** Proceso Post Liquidacion, cONTABILIZACION DE rOL cxc, cxp, Distibuido
*   Genera un comprobante contable con la liquidacion, que representa elcheque a pagar
*   Graba un registro de enlace entre liquidacion y comprobante.
*   Actualiza el status del proceso y liquidaciones, para que no se puedan modificar
*   @author   fah  Feb 18/04
*   @rev     fah  21/09/2010    Eliminacion previa de comprobante a grabar, para evitar error de PK
*   @rev esl 2011/11/24  Modificacion para Frutiboni, GENERAR ROL DE LIQUIDACION PARA EMPRESA QUE NO ES CONSOLIDADA
**/

//error_reporting (E_ALL);
include ("../LibPhp/LibInc.php");
include("LiLiPr_func.inc.php");
include("../LibPhp/ConTranLib.php");
//echo DBSRVR . " " . DBUSER . " " . DBNAME;
//setlocale (LC_TIME, "es_ec");
//
echo "
<!doctype html public '-//W3C//DTD HTML 4.0 //EN'>
<html>
<head>
    <META HTTP-EQUIV='Pragma' CONTENT='no-cache'>
    <META HTTP-EQUIV='Cache-Control' CONTENT='no-cache'>
    <title>LIQUIDACIONES: PROCESO 06</title>
</head>
<body bgcolor='#fffff7' link='#000000' alink='#ff0000' vlink='#000000' text='#000000' style='PADDING-RIGHT: 0px; PADDING-LEFT: 0px; FONT-SIZE: 11px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; magin: 0; spacing: 0; font-size:10px' bottommargin='0' nowrap leftmargin='0' topmargin='17' rightmargin='0' marginwidth='0' marginheight='0'>
<style type='text/css'>
</style>
";

/**
*   Proceso de Contabilizacion de un conjuno de transacciones de liquidacion
*   @param  db          object      Referncia a un objeto de conexion a BD
*   @param  rs          object      Ref. a un ADO recorset
*   @param  pGlosa      string      Glosa contable para cada detalle del registro
*   @param  pprev       bool        Inidicador de procesamiento del total previo (caso Commprob ingreso)
*   @param  pGenCh      bool     	Indicador de Generacion de cheques, defaul=true
*
*/
function fContabiliza2(&$db, &$rs, $pGlosa, $pPrev=false, $pGenCh=true ) {
    global $ilNumProces;
    global $ilEstadoProc;
    global $ilSemana;
    global $ilPeriodo;
    global $dlFecCierre;
    global $slUsuario;
    global $ilNumCompro;
    global $ilNumCheque;
    global $ilUltimoProd, $dlFecLiquid;
    global $agPla; //                               Plantilla de Cuentas
    //global $DB2;

    if ($rs->RecordCount() < 1) {
        echo "   ----- NO EXISTE LIQUIDACIONES PARA PROCESAR ---</td></tr><tr><td>";
        return false;
    }
    if ($pGenCh) $ilNumChq = fGetParam('pChq', false);
    else $ilNumChq = 0;
    $ilPxmoCom = fGetParam('pCom', false);
    $ilChqFlag = false; //                                                  Bandera indicadora d emision de cheques
    $ilComFlag = false; //                                                  Bandera indicadora d emision de comprobantes
    if ($ilNumChq && intval($ilNumChq > 0)) $ilChqFlag = true;
    if ($ilPxmoCom && intval($ilPxmoCom > 0)) $ilComFlag = true;
    $ilUltimoProd = false;
    $ilUltimaEmpr = false;
    $ilLiqNumero = false;
    $lastrec=array();
    $fSuma = 0;
    $fNum= 0;
    $lastrec['det_estlibros'] = 0;
//    rs2html($rs,'border=2 cellpadding=3');
   $ilRegnumero = 0;
   while (!$rs->EOF) {
        $rsdat = $rs->GetRowAssoc();
        $fNum+=1;
        //$rsdat = array_change_key_case($rsdat, CASE_LOWER);
//print_r($rsdat);
        $DB2 = $rsdat['EM']; // la empresa se define con cada registro
        $ilUltimoProd = $rsdat['CO'];
            $lastrec['det_idauxiliar'] = $ilUltimoProd;
            $lastrec['det_secuencia'] = $fNum + 1;
            $lastrec['det_regnumero'] = $ilRegNumero;
            $lastrec['det_numcomp']   = $ilNumComp;
            $lastrec['det_glosa']     = $pGlosa;
            $lastrec['det_codcuenta'] = $agPla['CXL'];
            $lastrec['det_valdebito'] = -$fSuma;
            $lastrec['det_valcredito'] = 0;
            $lastrec['det_tipocomp'] = "PL";
            $lastrec['det_feclibros'] = '2020-12-31'; //        Fecha futura de proceso en el banco
            $lastrec['det_glosa'] = $pGlosa;
            $lastrec['det_numcheque'] = $ilSemana;
            $lastrec['det_estejecucion'] = 0;
            $lastrec['det_clasregistro'] = 0;
            $fSuma = 0;
            $ilAux=0;
            $fNum=1;
            $slProductor = fDBValor($db, 'conpersonas', "concat(per_Apellidos, ' ' , per_Nombres)", "per_codAuxiliar = " . $ilUltimoProd );
            //$ilNumComp = NZ(fDBValor($db, $DB2 .'.concomprobantes', 'MAX(com_numcomp)', "com_tipocomp = '" .$lastrec['det_tipocomp']. "'"),0) + 1 ;
            $ilNumComp = $rsdat['NU'];
            $lastrec['det_numcomp']  = $ilNumComp;
            $ilAux = $ilUltimoProd;
            $l = strlen($slProductor);
            $p = 60 - $l;
//            echo str_pad(' ', 40*6, '&nbsp;') . $slProductor . str_repeat('&nbsp;',$p) . $lastrec['det_tipocomp'] . " - " . $ilNumComp .  "   Cheque: " . ($ilNumChq ) . "<br>";
            echo "&nbsp;&nbsp;&nbsp;" .
                $slProductor . "</td><td>" . $lastrec['det_tipocomp'] . " - " . $ilNumComp .
                "</td><td> " . $DB2 ."</td><td> " .
                (($rsdat["TP"] == "TR")? "SPI": "CH") .
                "</td><td style='text-align:right'>&nbsp;" . number_format($rsdat['TT'] ,2,".",",").
                "</td></tr><tr><td>";
//echo "<br>1";
            $db->Execute("Delete FROM " . $DB2 . ".condetalle WHERE det_regnumero in " .
                            "(select com_regnumero from ". $DB2 . ".concomprobantes where com_tipocomp = 'PL' AND com_refOperat = $ilSemana AND com_codreceptor = " . $ilUltimoProd ." ) ");

            $db->Execute("Delete FROM " . $DB2 . ".concomprobantes where com_tipocomp = 'PL' AND com_refOperat = $ilSemana AND com_codreceptor = " . $ilUltimoProd  );
            
            if (!fAgregaComprobante($db, $lastrec['det_tipocomp'], $ilNumComp, 0,
                $dlFecLiquid, $dlFecLiquid,$dlFecLiquid,
                $ilAux, $lastrec['det_idauxiliar'], $slProductor,
                $pGlosa . " (liq # " . $lastrec['det_numcomp'] . ")",
                0, 1, 9999, 0, $ilSemana, 5, 1, $ilNumProces,
                593, $slUsuario, $ilPeriodo, date("Y-m-d"), $DB2))
                fErrorPage('',"NO SE PUDO GRABAR EL COMPROBANTE  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp);
            $ilRegNumero = $db->Insert_ID();
            fDesBloquea($db, "concomprobantes");
            $slSql = "REPLACE INTO conenlace(enl_tipo, enl_ID, enl_opCode, enl_secuencia, enl_tipocomp, enl_numcomp, enl_usuario)
                            VALUES ('LQ'," . $lastrec['det_numcomp']. "," . $ilEstadoProc . "," . 0 . ",'" .
                                    $lastrec['det_tipocomp'] . "'," . $ilNumComp . ",'". $slUsuario ."')";
            if (!$db->Execute($slSql)) fErrorPage('',"NO SE PUDO GRABAR EL ENLACE  " . $lastrec['det_numcomp'] . "-" . $ilNumComp);
            $fSuma = 0;
//echo "<br>2";
        $fNum +=1;
        $lastrec['det_secuencia'] = $fNum;
        $lastrec['det_regnumero'] = $ilRegNumero;
        $lastrec['det_numcomp']   = $ilNumComp;
        $lastrec['det_glosa']     = $pGlosa;
        $lastrec['det_refoperativa']     = $ilSemana;
        //$lastrec=$rsdat;
        if ($lastrec['det_codcuenta'] == ''){
                $lastrec['det_codcuenta']=$agPla['CAP'];
        }
        $lastrec['det_valdebito'] = 0;
        $lastrec['det_valcredito'] = 0;
        $lastrec['det_codcuenta'] = $agPla['CXL'];
        $lastrec['det_idauxiliar'] = $ilUltimoProd;;
//echo "<br>3";
        if ($rsdat['TR'] <> 0 ){
            $lastrec['det_codcuenta'] = $agPla['CXL'];
            $lastrec['det_valdebito'] = $rsdat['TT'];
            if(!fInsDetalleCont($db, $lastrec, $DB2))
                fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE:  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;
            $lastrec['det_secuencia']++ ;
            $lastrec['det_valcredito'] = 0;
            $lastrec['det_codcuenta'] = $agPla['CXL'];
            $lastrec['det_valcredito'] = $rsdat['D11'];
                    $lastrec['det_glosa']     = "ALCANCE POR COBRAR, SEM ". $ilSemana;
            if(!fInsDetalleCont($db, $lastrec, $DB2))
                fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE:  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;
            $lastrec['det_glosa']     = $pGlosa;
            $lastrec['det_secuencia']++ ;
            $lastrec['det_codcuenta'] = $agPla['CXP'];
            $lastrec['det_valdebito'] = 0;
            $lastrec['det_valcredito'] = $rsdat['TR'];
            if(!fInsDetalleCont($db, $lastrec, $DB2))
                fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE:  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;

        } else {
            $lastrec['det_secuencia']++ ;
            $lastrec['det_codcuenta'] = $agPla['CXL'];
            $lastrec['det_valdebito'] = $rsdat['TT'];
            $lastrec['det_valcredito'] = 0;
            if(!fInsDetalleCont($db, $lastrec, $DB2))
                fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE:  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;
            $lastrec['det_secuencia']++ ;
            $lastrec['det_codcuenta'] = $agPla['CXP'];
            $lastrec['det_valdebito'] = 0;
            $lastrec['det_valcredito'] = $rsdat['TT'];
            if(!fInsDetalleCont($db, $lastrec, $DB2))
                fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE:  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;
        }
        $finalrec = $lastrec;
      $rs->MoveNext();
    }
    return true;
}
/*
*   Procesamiento de datos para Cerrar un comprobante, si no se envia la cuenta y auxiliar se asina la que viene de totales,
*   @par    db          object  Pbjeto conexion DB
*   @par    fSuma       float   Suma de las transacciones anteriores
*   @par    lastrec     Array   Arreglo con datos de la transaccion
*   @par    pCue        Coigo de Cuenta que cierra el comprobante
*   @par    pAux        Auxiliar utilizado para cerrar
*   @rev    fahMay/07/09    Provision de Pago de Liquidaciones en una cia externa.
*/
function fCierraComprob(&$db, $fSuma, &$lastrec) {
    global $agPla;
    global $ilUltimoProd;
    if ($fSuma <> 0) { //                                                                   Procesaer el asiento de cierre para el productor previo
        $lastrec['det_secuencia'] +=99;
        $lastrec['det_clasregistro'] =9;//                                                  fah Feb/28/07 Indica que es una trans que cierra el comprobante
        if ($lastrec['det_valdebito'] >0 ){ //                                              fah Feb/28/07 Comprobante cuyo cierre es al debito (normalmente debe ser CR
               //$lastrec['det_idauxiliar'] = AplicaAuxil(true, $agPla['TCI'], $agPla['ACI'], $ilUltimoProd);
               $lastrec['det_idauxiliar'] = AplicaAuxil(true, 52, $agPla['ACI'], $ilUltimoProd);
               $lastrec['det_codcuenta'] = $agPla['CCN']; //                                fah Feb/28/07 Aplicar una cuenta para manejar valores inversos
        }else  $lastrec['det_idauxiliar'] = AplicaAuxil(true, 52, $agPla['ACI'], $ilUltimoProd); // @TODO: revisar $agPla['TCI'] para que no se anecesario este valor fijo Cerar con el productor
        //if(0 == $lastrec['det_idauxiliar'] ) $lastrec['det_idauxiliar'] = $ilUltimoProd;
        if(!fInsDetalleCont($db, $lastrec)) fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE... " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp']) . "-" . $lastrec['det_secuencia'] ;
    }
}
/**
*   Analiza el tipo de auxiliar que requiere una cuenta, y lo asigna segun
*   @par        $pReq    Bolean     Indicador Si la cuenta requiere auiliar
*   @par        $pTip    int        Tipo de auxiliar requerido
*   @par        $pGen    int        Auxiliar General
*   @par        $pProd   int        Auxiliar especifico
*   @return    lastrec modificado
*/
function AplicaAuxil($pReq, $pTip, $pGen, $pProd) {
        if (!$pReq) return 0; //                                    La cuenta No requiere aux.
        switch ($pTip)  {
            case 9999:
            case 62:
                return 0;
            case 52:
            case -1: //                                             Aplicar el codigo del productor
                return $pProd;
                break;
            case 0: //                                              No se aplica auxiliar
                return 0;
                break;
            default: //                                             un auxiliar especifico (banco)
                return $pGen;
                break;
        }
}
/**
*   Deshace los comprobantes generados en procesos previos
*   @par        $db		 obj		Objeto de Conexion
*   @par        $ilEstadoproc		Codigo de estado
*   @par        $ilNumProces		Numero de Proceso
*   @par        $ilNumProduc		Codigo de Productor
*   @return    lastrec modificado
*/
function fLimpiezaPrev(&$db,$ilEstadoProc, $ilNumProces, $ilNumProduc) {
	global $agPla;
	$slSql[] = "DROP TABLE IF EXISTS tmp_prev ";
	$slSql[] = "CREATE TABLE  tmp_prev
	                SELECT c.com_regnumero AS tmp_regNumero
	                    FROM concomprobantes l
	                         JOIN conenlace  ON enl_Tipo = 'LQ' AND enl_ID = l.com_numcomp AND enl_opCode = " . ($ilEstadoProc==26?6:$ilEstadoProc) . "
	                         JOIN concomprobantes c  ON c.com_Tipocomp = enl_tipocomp
	                                                AND c.com_tipocomp <> 'LQ' AND c.com_numcomp = enl_numcomp
	                         WHERE l.com_tipocomp= 'LQ' AND l.com_numProceso = " . $ilNumProces . "  ";
	$slSql[] = "DELETE FROM concomprobantes
	            WHERE com_regnumero in (SELECT tmp_regNumero FROM tmp_prev) " . ((strlen($ilNumProduc)>0)? " AND com_codReceptor = " . $ilNumProduc : " ");
	$slSql[] = "DROP TABLE IF EXISTS tmp_liquidado ";
	$slSql[] = "CREATE TEMPORARY TABLE  tmp_liquidado (tmp_NUmliquida INTEGER(10), tmp_liqTotal FLOAT(16,2))";
	$slSql[] = "CREATE INDEX irep ON tmp_liquidado (tmp_numliquida)";
	if(!fSQL($db, $slSql)) fErrorPage('','NO FUE POSIBLE SELECCIONAR INFORMACION PRELIMINAR', true, false);;
	$slUrl = "http://".$_SERVER['HTTP_HOST']."/AAA/AAA_SEGURIDAD/Li_Files/LiLiPr_mant.php?pOpCode=R&pro_ID=". $ilNumProces;
	$agPla['CCI'] = '';
	$agPla['TCI'] = '';
	$agPla['ACI'] = '';
	$agPla['CAP'] = "";
	$agPla['TAP'] = "";
    $agPla['CCN'] ="";
    $agPla['ACN'] ="";

$agPla['AAP'] = "";
}

/*
 *  Leer datos  de liqliquidacionesdist en base al criterio
 **/
function &fDefineQry(&$db, $pQry=false){
    global $gsTitGeneral;
    IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_liquidaciones")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
    IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_liqtarjadetal")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
    IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_procesos")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
    $pTipo = (isset($_GET['pTipo']))? $_GET['pTipo']:30; // tIPO DE rEPORTE A GENERAR

    /*$slSql = "SELECT 
	case when ldi_codempresa=0 then '' else  ldi_codempresa end AS EMP,
	pro_semana 	AS SEM,
        com_feccontab	AS FEC,
	pro_ID 		AS PRC,
	ldi_numliquida 	AS NLQ,
	ldi_codrubro 	AS RUB,
        rub_desccorta   AS DRU,
	com_codreceptor AS PRO,
        CONCAT(per_Apellidos, ' ', per_nombres) AS NOM,
        rub_ctadestino	AS CUE,
	rub_tipoauxiliar AS TIP,
	rub_Auxiliar	AS AUX,
        SUM(ldi_valtotal * rub_IndDbCr) AS VAL
        FROM liqprocesos
            INNER JOIN liqliquidacionesdist ON ldi_NumProceso = pro_ID
            INNER JOIN liqrubros ON rub_codrubro = ldi_codrubro
            INNER JOIN concomprobantes ON com_tipocomp = 'LQ' AND com_numcomp = ldi_numliquida
            INNER JOIN conpersonas ON per_codauxiliar = com_CodReceptor
	WHERE /*ldi_codempresa IN (SELECT emp_basedatos FROM seguridad.segempresas)
            AND *//*ESTA PARTE SE COMENTA PARA LAS EMPRESAS QUE NO SON CORPORATIVAS*/ /*" . $pQry . "
	GROUP BY 1,2,3,4,5,6,7,8,9,10
        ORDER BY EMP, SEM, NLQ, RUB";*/
        
    /**
     * @rev esl 2011/11/24  Modificacion para Frutiboni, GENERAR ROL DE LIQUIDACION PARA EMPRESA QUE NO ES CONSOLIDADA
     * No lee los datos de liqliquidacionesdist sino de liqliquidaciones
     * */
    $slSql = "SELECT 
                    /*case when ldi_codempresa=0 then '' else  ldi_codempresa end*/ '' AS EMP,
                    pro_semana 	AS SEM,
                    com_feccontab	AS FEC,
                    pro_ID 		AS PRC,
                    liq_numliquida 	AS NLQ,
                    liq_codrubro 	AS RUB,
                    rub_desccorta   AS DRU,
                    com_codreceptor AS PRO,
                    CONCAT(per_Apellidos, ' ', per_nombres) AS NOM,
                    rub_ctadestino	AS CUE,
                    rub_tipoauxiliar AS TIP,
                    rub_Auxiliar	AS AUX,
                    SUM(liq_valtotal * rub_IndDbCr) AS VAL
            FROM liqprocesos
                INNER JOIN liqliquidaciones ON liq_NumProceso = pro_ID
                INNER JOIN liqrubros ON rub_codrubro = liq_codrubro
                INNER JOIN concomprobantes ON com_tipocomp = 'LQ' AND com_numcomp = liq_numliquida
                INNER JOIN conpersonas ON per_codauxiliar = com_CodReceptor
            WHERE /*ldi_codempresa IN (SELECT emp_basedatos FROM seguridad.segempresas)
                AND *//*ESTA PARTE SE COMENTA PARA LAS EMPRESAS QUE NO SON CORPORATIVAS*/ " . $pQry . "
            GROUP BY 1,2,3,4,5,6,7,8,9,10
            ORDER BY EMP, SEM, NLQ, RUB";
        

    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('ATENCION','NO SE GENERARO INFORMACION DE LIQUIDACION', true,false);
    return $rsLiq;
}
/*
 *Recorre el recordset de liquidaciones, en cada cambio de empresa, elimina los comprobantes Rl de la semana en proceso,
 *en cada nueva liquidacion crea un comprobante RL cuyo numero es el numero de liquidacion
 *@param $db Object Apuntador a la conexion de BD de liquidaciones
 *@param $rs Object Recordset de liquidaciones
 *@param $pCue String   Codigo de Cuenta que cierra el comprob
 */
function fContabiliza($db, $rsliq, $pCue) {
    $slEmpActual = '-';
    $ilUltimoProd = "-";
    $ilNumComp  = -999;
    $lastrec=array();
    $DB2=NULL;
    $flSuma = 0;
    $fNum=1;
    while (!$rsliq->EOF) {
        $rsdat = array();
        $alDat = $rsliq->FetchRow();
        if (fGetParam("pAppDbg", false)) print_r($alDat)        ;
        if ($slEmpActual != $alDat['EMP']) {
            echo "<br><br><br>SEMANA : " . $alDat['SEM']. " EMPRESA: " . $alDat['EMP']  . " paso: $ilNumComp viene: " . $alDat['NLQ'];
            if ($flSuma <>0){              // Cierra un comprobante
                $fNum +=1;
                $lastrec['det_idauxiliar'] = $ilUltimoProd;;
                $lastrec['det_secuencia'] = $fNum ;
                $lastrec['det_glosa']     = "LIQ FRUTA SEM " . $alDat['SEM'];
                $lastrec['det_codcuenta'] = $pCue;
                $lastrec['det_valdebito'] = -$flSuma;
                $lastrec['det_valcredito'] = 0;
                $flSuma = 0;
                fGrabaDetalle($DB2, $lastrec) ;
            }
            if (is_object($DB2)) $DB2->Close();
            $DB2 = &ADONewConnection('mysql');
            $DB2->autoRollback = true;
            $DB2->PConnect(DBSRVR, DBUSER, DBPASS, $alDat['EMP']); 
            $DB2->debug=fGetParam('pAdoDbg', 0);
            fDesBloquea($DB2);
            $ilUltimoProd   = "-";
            $ilNumComp = 999999;
            $slSqlCond = $alDat['EMP'] . ".concomprobantes where com_tipocomp = 'LF' AND com_refOperat = " . $alDat['SEM'] ." ";
            $DB2->Execute("Delete FROM " . $alDdat['EMP'] . ".condetalle WHERE det_regnumero in (select com_regnumero from " . $slSqlCond . ")");
            $DB2->Execute("Delete FROM " . $slSqlCond );
            $slEmpActual = $alDat['EMP'];
            $fNum = 1;
        }
        if ($ilNumComp != $alDat['NLQ']){  // nuevo NUmero de Liquidacion
            if ($flSuma <>0){              // Cierra un comprobante
                $fNum +=1;
                $lastrec['det_idauxiliar'] = $ilUltimoProd;;
                $lastrec['det_secuencia'] = $fNum ;
                $lastrec['det_glosa']     = "LIQ FRUTA SEM " . $alDat['SEM'];
                $lastrec['det_codcuenta'] = $pCue;
                $lastrec['det_valdebito'] = -$flSuma;
                $lastrec['det_valcredito'] = 0;
                $flSuma = 0;
                fGrabaDetalle($DB2, $lastrec) ;
            }
            $ilNumComp = $alDat['NLQ'];
            $alPer = $DB2->GetRow("SELECT per_PerContable AS PER , per_estado AS EST
                    FROM conperiodos 
                    WHERE per_Aplicacion = 'CO' AND '" . $alDat['FEC'] . "' BETWEEN per_FecInicial AND per_FecFinal"
                    );
            print_r($alPer);
           
        $db->Execute("DELETE FROM concomprobantes WHERE com_tipocomp ='LF' AND com_numcomp = " . $alDat['NLQ']  ); // Elimina comprobante anterior //@TODO:  Dinamizar el tipo de cambio y MONEDA !!!!!!!!!
            $db->Execute("DELETE FROM concomprobantes WHERE com_tipocomp ='LF' AND com_numcomp = " . $alDat['NLQ']  ); // Elimina comprobante anterior
            if (!fAgregaComprobante($db, 'LF', $alDat['NLQ'], 0,
                $alDat['FEC'], $alDat['FEC'], $alDat['FEC'], 
                0, $alDat['PRO'], $alDat['NOM'],
                "ROL LIQUIDACION SEM " . $alDat['SEM'],
                0, 1, 9999, 0, $alDat['SEM'], 5, 1, $alDat['PRO'],
                593, $_SESSION['g_user'], $alPer['PER'], date("Y-m-d"),$alDat['EMP'] )){     // $DB2)){
                    fErrorPage('',"NO SE PUDO GRABAR EL COMPROBANTE RL-". $alDat['NLQ']);
            }
            $ilRegNumero = $DB2->Insert_ID();
            $ilUltimoProd = $alDat['PRO'];
            fDesBloquea($DB2, "concomprobantes");
            $flSuma = 0;
//echo "<br>2";
            $fNum =1;
            $lastrec['det_regnumero'] = $ilRegNumero;
            $lastrec['det_numcomp']   = $alDat['NLQ'];
            $lastrec['det_tipocomp'] = "RL";
            $lastrec['det_feclibros'] = '2020-12-31'; //        Fecha futura de proceso en el banco
            $lastrec['det_numcheque'] = $alDat['SEM'];
            $lastrec['det_refoperativa']     = $alDat['SEM'];
            $lastrec['det_estejecucion'] = 0;
            $lastrec['det_estlibros'] = 0;
            $lastrec['det_clasregistro'] = 0;
            $ilUltimoProd   = $alDat['PRO'];
            echo ("<br> comprob: " . $alDat['NLQ'] ." - reg: " . $ilRegNumero);
        } //---------------------------------------- eof  Nueva liquidacion
        if ($alDat['TIP'] == '-')            $lastrec['det_idauxiliar'] = $alDat['AUX'];
        else $lastrec['det_idauxiliar'] = $alDat[$alDat['TIP']];
        $fNum +=1;
        $lastrec['det_secuencia'] = $fNum ; 
        $lastrec['det_glosa']     = substr($alDat['NOM'],0, 25) . " / " . substr($alDat['DRU'], 0, 20);
        $lastrec['det_codcuenta'] = $alDat['CUE'];
        $lastrec['det_valdebito'] = $alDat['VAL'];
        $lastrec['det_valcredito'] = 0;
        $flSuma += $alDat['VAL'];
        //echo " <br> sec2: $fNum ";        
        fGrabaDetalle($DB2, $lastrec) ;
    }
    if ($flSuma <>0){      //-----------------------------   Si queda un comprobante por cerrar
        ++$fNum; 
        $lastrec['det_idauxiliar'] = $ilUltimoProd;
        $lastrec['det_secuencia'] = $fNum ;
        $lastrec['det_glosa']     = "LIQ FRUTA SEM " . $lastrec['det_refoperativa']  ;
        $lastrec['det_codcuenta'] = $pCue;
        $lastrec['det_valdebito'] = -$flSuma;
        $lastrec['det_valcredito'] = 0;
        $flSuma = 0;
        fGrabaDetalle($DB2, $lastrec) ;
    }
    
    if($DB2) $DB2->Close();
}

function fGrabaDetalle($pDb, $pRec){
    if(!fInsDetalleCont($pDb, $pRec))
        fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE... " . $pRec['EMP'] . " -" . $pRec['det_tipocomp'] . "-" . $pRec['det_numcomp']) . "-" . $pRec['det_secuencia'] ;
}
/*---------------------------------------------------------------------------------------------------------------------------------------------
    Procesamiento
---------------------------------------------------------------------------------------------------------------------------------------------*/
// $IsValid = checkdate($ValidatingDate[ccsMonth], $ValidatingDate[ccsDay], $ValidatingDate[ccsYear]);
$inicio = microtime();
$txt = "<br> Inicio&nbsp;&nbsp;&nbsp;&nbsp;: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
if(isset($_SESSION["g_user"])) $slUsuario=$_SESSION["g_user"];
$ilSemana=fGetParam('pSem',false);
$ilSemIni=fGetParam('pIni',false);
$ilSemFin=fGetParam('pFin',false);

$slQry = "";
if ($ilSemana) $slQry .= " pro_semana = " . $ilSemana;
else if($ilSemIni && $ilSemFin ) $slQry .= " pro_semana BETWEEN  " . $ilSemIni . " AND " . $ilSemFin;

$DB2 = fGetParam("pDB2", "");
if (strlen($DB2) > 0) $DB2 .=  "";

$ilPeriodo = false;
//
//$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC; 
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->debug=fGetParam('pAdoDbg', 0);
fDesBloquea($db);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry);
fContabiliza($db, $rs, "20000099" );

        echo "<br>CONTABILIZACION DE LIQUIDACION  :</font></td></tr><tr><td>";
        $gsTitGeneral="";

    $final = microtime();
    $db->Close();

?>
    </tr>
    <tr>
        <td align='CENTER' style='font-size:11px'>

        </td>
    </tr>
</table>
    <?
// echo $txt;
$txt .= "<br> Finalizo: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
$txt .= "<br> TIEMPO UTILIZADO:      ".
        round(microtime_diff($inicio ,$final),2) . " segs. <br>";
echo "<center> $txt <br> PROCESAMIENTO OK </center>";
$txt.= " ." ;
    ?>
</body>
</html>




