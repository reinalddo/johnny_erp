<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
    <title>LIQUIDACIONES: PROCESO 06</title>
</head>
<body bgcolor="#fffff7" link="#000000" alink="#ff0000" vlink="#000000" text="#000000" style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; FONT-SIZE: 11px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; magin: 0; spacing: 0; font-size:10px" bottommargin="0" nowrap leftmargin="0" topmargin="17" rightmargin="0" marginwidth="0" marginheight="0">
<style type="text/css">
</style>

<?php
/** Proceso Post Liquidacion, paso 6. Provision de pago, Distibuido
*   Genera un comprobante contable con la liquidacion, que representa elcheque a pagar
*   Graba un registro de enlace entre liquidacion y comprobante.
*   Actualiza el status del proceso y liquidaciones, para que no se puedan modificar
*   @Crea   fah  Feb 18/04
*   @Rev    fah  Abr/06/04
*   @Rev    fah  Jun/08/04
*   @Rev    fah  JUl/14/04
*   @Rev    fah  Ene/15/05
*   @Rev    fah  Feb/28/07  Correccion del proceso de contbilizacion de negativos,
*                           eliminada funcion fProcesaNegat() porque no tiene efecto
*   @Rev    fah  Mar/17/07  Procesar correctamente los negativos del cheque final
*   @Rev    fah  Nov/22/07  Asignar fecha futura a fecha de ejecucion de cheques para conciliacion manual
*   @Rev    fah  May/07/09  Manejar una segunda Base de datos para la provision contable (d2)
**/
//error_reporting (E_ALL);
include ("../LibPhp/LibInc.php");
include("LiLiPr_func.inc.php");
//include("GenUti.inc.php");
include("../LibPhp/ConTranLib.php");
//echo DBSRVR . " " . DBUSER . " " . DBNAME;
//setlocale (LC_TIME, "es_ec");
//
/**
/**
*   Genera una tabla temporal con los tatales por liquidacion segn la estructura enviada como parametro
*   @param  object  $db     Ref. a la instancia de conexion a BD
*   @param  integer $pProc  Numero de proceso a contab
*   @param  integer $pID    ID de reporte que se meplea como seleccionador de rubros
*   @param  String  $pTip   Tipo de Comprobante a Generar
*   @param  String  $pCue   Codigo de Cuenta de Cierre
*   @param  String  $pSuf   Sufijo para el nombre del campo de cuenta y auxiliar contables
*   @param  String  $pPos   Indicador para incluir solo positivos.
*   @return Object  Recordset
*/
function fSelTotalesLiq(&$db, $pProc = -1, $pID=0, $pSig=1, $pPos =true) {
    global $ilEstadoproc;
    global $ilNumProduc;
    $slSql[] = "INSERT INTO tmp_liquidado
                    SELECT  liq_numliquida AS tmp_NumLiquida,
                            sum(liq_ValTotal * rub_inddbcr * (" . $pSig . "))  AS tmp_LiqTotal
                    FROM liqreportes JOIN liqliquidaciones ON liq_numproceso = " . $pProc . " AND liq_codrubro = rep_codrubro
                         JOIN liqrubros ON rub_codrubro = liq_codrubro
                         JOIN concomprobantes ON com_tipocomp ='LQ' AND com_numcomp = liq_numliquida
                    WHERE rep_reporteID = " . $pID . ((strlen($ilNumProduc)>0)? " AND com_codReceptor = " . $ilNumProduc : " " ).
                    " GROUP BY 1 HAVING tmp_LiqTotal " . ($pPos ? " > 0" : " <=0 ");
    $slSql[] = "CREATE INDEX itot ON tmp_liquidado (tmp_Numliquida)";
    fSQL($db, $slSql);
}

/**
*   Genera una tabla temporal con los tatales por liquidacion SOLO PARA CUADRAR SEMANAS 1 y 2 incluye ceros y neg
*   @param  object  $db     Ref. a la instancia de conexion a BD
*   @param  integer $pProc  Numero de proceso a contab
*   @param  integer $pID    ID de reporte que se meplea como seleccionador de rubros
*   @param  String  $pTip   Tipo de Comprobante a Generar
*   @param  String  $pCue   Codigo de Cuenta de Cierre
*   @param  String  $pSuf   Sufijo para el nombre del campo de cuenta y auxiliar contables
*   @return Object  Recordset
*/
function fSelTotalesGrl(&$db, $pProc = -1, $pID=0, $pSig=1) {
    global $ilEstadoproc;
    global $ilNumProduc;
    $slSql[] = "INSERT INTO tmp_liquidado
                    SELECT  liq_numliquida AS tmp_NumLiquida,
                            sum(liq_ValTotal * rub_inddbcr * (" . $pSig . "))  AS tmp_LiqTotal
                    FROM liqreportes JOIN liqliquidaciones ON liq_numproceso = " . $pProc . " AND liq_codrubro = rep_codrubro
                         JOIN liqrubros ON rub_codrubro = liq_codrubro
                         JOIN concomprobantes ON com_tipocomp ='LQ' AND com_numcomp = liq_numliquida
                    WHERE rep_reporteID = " . $pID . ((strlen($ilNumProduc)>0)? " AND com_codReceptor = " . $ilNumProduc : " ") .
                    " GROUP BY 1 ";
    $slSql[] = "CREATE INDEX itot ON tmp_liquidado (tmp_Numliquida)";
    fSQL($db, $slSql);
}
/**
*   Genera una tabla temporal con los tatales NEGATIVOS por liquidacion segn la estructura enviada como parametro
*   @param  object  $db     Ref. a la instancia de conexion a BD
*   @param  integer $pProc  Numero de proceso a contab
*   @param  integer $pID    ID de reporte que se meplea como seleccionador de rubros
*   @param  String  $pSig	Signo con que se aplicanlas transacc
*   @return Object  Recordset
*/
function fSelTotalesNeg(&$db, $pProc = -1, $pID=0, $pSig=1) {
    global $ilEstadoproc;
    global $ilNumProduc;
    $slSql[] = "INSERT INTO tmp_liquidado
                    SELECT  liq_numliquida AS tmp_NumLiquida,
                            sum(liq_ValTotal * rub_inddbcr * (" . $pSig . "))  AS tmp_LiqTotal
                    FROM liqreportes JOIN liqliquidaciones ON liq_numproceso = " . $pProc . " AND liq_codrubro = rep_codrubro
                         JOIN liqrubros ON rub_codrubro = liq_codrubro
                         JOIN concomprobantes ON com_tipocomp ='LQ' AND com_numcomp = liq_numliquida
                    WHERE rep_reporteID = " . $pID . ((strlen($ilNumProduc)>0)? " AND com_codReceptor = " . $ilNumProduc : " " ) .
                    " GROUP BY 1 HAVING tmp_LiqTotal <= 0";
    $slSql[] = "CREATE INDEX itot ON tmp_liquidado (tmp_Numliquida)";
    fSQL($db, $slSql);
}
/**
*   Genera una tabla temporal con los Rubros que intervienen en el cheque final
*   @param  object  $db     Ref. a la instancia de conexion a BD
*/
function fSelRubrosFin(&$db) {
    $slSql[] = "DROP TABLE IF EXISTS tmp_report ";
    $slSql[] = "CREATE TEMPORARY TABLE tmp_report
                SELECT a.* FROM
                    	liqreportes a join liqrubros on rub_codrubro = rep_codrubro
	               where rep_reporteID = 30 AND a.rep_codrubro not in
				    	(select rep_codrubro
					FROM liqreportes b
							where b.rep_reporteID = 20) ";
    fSQL($db, $slSql);
}
/**
*   Genera una tabla temporal con los Rubros normals que intervienen en liquidaciones
*   @param  object  $db     Ref. a la instancia de conexion a BD
*/
function fSelRubrosLiq(&$db, $pRep) {
    $slSql[] = "DROP TABLE IF EXISTS tmp_report ";
    $slSql[] = "CREATE TEMPORARY TABLE tmp_report
                SELECT a.* FROM
                    	liqreportes a
	               where rep_reporteID = " . $pRep ;
    $slSql[] = "CREATE INDEX irep ON tmp_report (rep_codrubro)";
    fSQL($db, $slSql);
}

/**
*   Seleccion de informacion para el proceso de contabilizacion
*   @param  object  $db     Ref. a la instancia de conexion a BD
*   @param  integer $pProc  Numero de proceso a contab
*   @param  integer $pID    ID de reporte que se meplea como seleccionador de rubros
*   @param  String  $pTip   Tipo de Comprobante a Generar
*   @param  String  $pCue   Codigo de Cuenta de Cierre
*   @param  String  $Aux    auxiliar a utilizar: -1 del productor, 0=Ninguno, 1-> Aux. especifico
*   @param  String  $pSuf   Sufijo para el nombre del campo de cuenta y auxiliar contables
*   @return Object  Recordset
*/
function fSelDatosContab(&$db, $pProc = -1, $pID = -1, $pTip="LQ", $pSig=1) {
    global $ilEstadoProc;
    global $ilNumProces;
    global $ilNumCompro;
    global $ilNumCheque;
    global $ilNumProduc;
    global $gProdQry;
//                                         Detallez a incluir en el proceso
    $slSql[] ="SELECT
                ldi_codempresa as tmp_codempre,
                com_regnumero AS det_regnumero,
                '". $pTip . "' AS det_tipocomp,
                ldi_numliquida AS det_numcomp,
                com_feccontab AS det_feccontab,
                ldi_secuencia AS det_secuencia,
                1 AS det_clasregistro,
                com_codreceptor AS com_productor,
                com_codreceptor AS det_idauxiliar,
                (rub_Inddbcr * (" . $pSig . ") * liq_valtotal) AS det_valdebito,
                0000000000.00 AS det_valcredito,
                ldi_descripcion AS det_glosa,
                0 AS det_estejecucion,
                com_feccontab AS det_fecejecucion,
                0 AS det_estLibros,
                '2020-12-31' AS det_feclibros,
                pro_semana AS det_refoperativa,
                0 AS det_numcheque ,
                com_feccontab AS det_feccheque,
                rub_ctadestino AS det_codcuenta,
                pro_semana,
                tmp_liqtotal,
                cue_reqauxiliar as cue_reqauxiliar,
                cue_tipauxiliar as cue_tipauxiliar
            FROM tmp_liquidado
                 JOIN liqliquidacionesdist ON ldi_NumLiquida = tmp_NUmLiquida
                 JOIN liqprocesos ON pro_ID = " . $pProc . " AND liq_numproceso = pro_ID
                 JOIN concomprobantes ON  com_tipocomp = 'LQ' AND  com_numcomp = tmp_NUmLiquida AND com_numproceso =  liq_numproceso
                 JOIN liqrubros ON rub_codrubro = liq_codrubro
                 JOIN tmp_report ON rep_ReporteID = " . $pID . " AND  rep_codrubro = liq_codrubro
                 JOIN concuentas ON cue_codcuenta = rub_ctadestino
            WHERE liq_ValTotal <> 0 " .  $gProdQry . "
           ORDER BY 3,4, rep_posordinal  " ;
    return fSQL($db, $slSql);
}
/**
*   Seleccion de informacion para el proceso de contabilizacion de Ingreso a Caja
*   @param  object  $db     Ref. a la instancia de conexion a BD
*   @param  integer $pProc  Numero de proceso a contab
*   @param  integer $pID    ID de reporte que se meplea como seleccionador de rubros
*   @param  String  $pTip   Tipo de Comprobante a Generar
*   @param  String  $pCue   Codigo de Cuenta de Cierre
*   @param  String  $pSuf   Sufijo para el nombre del campo de cuenta y auxiliar contables
*   @return Object  Recordset
*/
function fSelDatosContIng(&$db, $pProc = -1, $pID = -1, $pTip="", $pSig=1) {
    global $ilNumProces;
    global $ilNumCompro;
    global $ilNumCheque;
    global $ilNumProduc;
    global $gProdQry;
//                                                        ToTales por liquidacion Oficial
    $slSql = "SELECT
                com_regnumero AS det_regnumero,
                '". $pTip . "' AS det_tipocomp,
                liq_numliquida AS det_numcomp,
                com_feccontab AS det_feccontab,
                rub_posordinal AS det_secuencia,
                1 AS det_clasregistro,
                com_codreceptor AS com_productor,
                com_codreceptor AS det_idauxiliar,
                rub_Inddbcr * (" . $pSig . ") * liq_valtotal AS det_valdebito,
                0000000000.00 AS det_valcredito,
                liq_descripcion AS det_glosa,
                0 AS det_estejecucion,
                com_feccontab AS det_fecejecucion,
                0 AS det_estLibros,
                '2020-12-31' AS det_feclibros,
                pro_semana AS det_refoperativa,
                0 AS det_numcheque ,
                com_feccontab AS det_feccheque,
                rub_ctadestino AS det_codcuenta, 
                pro_semana,
                IFNULL(tmp_liqtotal,0000000000.00) as tmp_liqtotal,
                cue_reqauxiliar,
                cue_tipauxiliar
            FROM tmp_liquidado
                JOIN liqliquidaciones ON liq_NumLiquida = tmp_NUmLiquida
            	join liqprocesos on pro_ID = " . $pProc . " AND liq_numproceso = pro_ID
            	join concomprobantes on com_numproceso = liq_numproceso AND com_tipocomp = 'LQ'
            				AND com_numcomp = liq_numliquida
            	JOIN tmp_report  on rep_codrubro = liq_codrubro
            	join liqrubros on rub_codrubro = liq_codrubro
            	join concuentas ON cue_codcuenta = rub_ctadestino
            	WHERE liq_ValTotal <> 0 " . $gProdQry . "
           ORDER BY 3,4, rep_posordinal  " ;
    return fSQL($db, $slSql);
}
/**
*   Seleccion de datos para el proceso de contabilizacion de un Debito Generado por un rubro
*   @param  object  $db     Ref. a la instancia de conexion a BD
*   @param  integer $pProc  Numero de proceso a contab
*   @param  integer $pID    ID de reporte que se meplea como seleccionador de rubros
*   @param  String  $pTip   Tipo de Comprobante a Generar
*   @param  String  $pCue   Codigo de Cuenta de Cierre
*   @param  String  $pSuf   Sufijo para el nombre del campo de cuenta y auxiliar contables
*   @return Object  Recordset
*/
function fSelDatosConDeb(&$db, $pProc = -1, $pID = -1, $pTip="LQ", $pSig=1) {
    global $ilEstadoProc;
    global $gCtaTrans;
    global $gAuxApert;
    global $ilNumProces;
    global $ilNumCompro;
    global $ilNumCheque;
    global $ilNumProduc;
    global $gProdQry;

//                                                        Rubros a incluir
//                        sum(if(rub_Inddbcr=1,liq_valtotal, 0)) AS det_valdebito,
//                        sum(if(rub_Inddbcr=1, 0,liq_valtotal)) AS det_valcredito
/*
    $slSql[] = "INSERT INTO  tmp_liquidado
                    SELECT  liq_Numliquida AS tmp_NumLiquida,
                        sum(liq_valtotal * rub_Inddbcr * " . $pSig . ") AS tmp_Liqtotal
                        FROM liqprocesos
                                JOIN liqliquidaciones ON pro_ID = " . $pProc . " AND liq_numproceso = pro_ID
                                JOIN liqreportes ON rep_ReporteID = " . $pID . " AND  rep_codrubro = liq_codrubro
                                JOIN liqrubros ON rub_codrubro = liq_codrubro
                        GROUP BY 1 HAVING tmp_liqtotal > 0";
*/
    $slSql[] ="SELECT
                com_regnumero AS det_regnumero, '".
                $pTip . "' AS det_tipocomp,
                com_numcomp AS det_numcomp,
                com_feccontab AS det_feccontab,
                1 AS det_secuencia,
                1 AS det_clasregistro,
                com_codreceptor AS com_productor,
                com_codreceptor AS det_idauxiliar,
                (liq_valtotal * rub_Inddbcr * " . $pSig . ") AS det_valdebito,
                0000000000.00 AS det_valcredito,
                ' ' AS det_glosa,
                0 AS det_estejecucion,
                com_feccontab AS det_fecejecucion,
                0 AS det_estLibros,
                '2020-12-31' AS det_feclibros,
                0 AS det_refoperativa,
                0 AS det_numcheque ,
                com_feccontab AS det_feccheque,
                '' AS det_codcuenta,
                0 AS pro_semana,
                0000000000.00 AS tmp_liqtotal,
                1 AS cue_reqauxiliar,
                -1 AS cue_tipauxiliar
            FROM tmp_liquidado
                JOIN concomprobantes ON  com_tipocomp = 'LQ' AND  com_numcomp = tmp_numliquida
                JOIN liqliquidaciones ON liq_numliquida = tmp_numliquida
                JOIN liqprocesos ON pro_ID = " . $pProc . " AND liq_numproceso = pro_ID
                JOIN liqreportes ON rep_ReporteID = " . $pID . " AND  rep_codrubro = liq_codrubro
                JOIN liqrubros ON rub_codrubro = liq_codrubro
           ORDER BY 3,4" ;
    return fSQL($db, $slSql);
}
/**
*   Seleccion de datos para el proceso de contabilizacion de Cheque Final
*   @param  object  $db     Ref. a la instancia de conexion a BD
*   @param  integer $pProc  Numero de proceso a contab
*   @param  integer $pID    ID de reporte que se meplea como seleccionador de rubros
*   @param  String  $pTip   Tipo de Comprobante a Generar
*   @param  String  $pSig	Signoautilizar
*   @param  String  $pCta   Cod de Cuenta que se debe incluir
*   @return Object  Recordset
*/
function fSelDatosFinal(&$db, $pProc = -1, $pID = -1, $pTip="LQ", $pSig=1, $pCta="''") {
    global $ilEstadoProc;
    global $gCtaTrans;
    global $gAuxApert;
    global $ilNumProces;
    global $ilNumCompro;
    global $ilNumCheque;
    global $ilNumProduc;
    global $gProdQry;

//                                                        Rubros a incluir
    $slSql[] ="SELECT
                com_regnumero AS det_regnumero, '".
                $pTip . "' AS det_tipocomp,
                com_numcomp AS det_numcomp,
                com_feccontab AS det_feccontab,
                1 AS det_secuencia,
                1 AS det_clasregistro,
                com_codreceptor AS com_productor,
                com_codreceptor AS det_idauxiliar,
                sum((liq_valtotal * rub_Inddbcr * " . $pSig . ")) AS det_valdebito,
                0000000000.00 AS det_valcredito,
                ' ' AS det_glosa,
                0 AS det_estejecucion,
                com_feccontab AS det_fecejecucion,
                0 AS det_estLibros,
                '2020-12-31' AS det_feclibros,
                0 AS det_refoperativa,
                0 AS det_numcheque ,
                com_feccontab AS det_feccheque, " .
                $pCta . " AS det_codcuenta,
                0 AS pro_semana,
                0000000000.00 AS tmp_liqtotal,
                1 AS cue_reqauxiliar,
                -1 AS cue_tipauxiliar
            FROM tmp_liquidado
                JOIN concomprobantes ON  com_tipocomp = 'LQ' AND  com_numcomp = tmp_numliquida
                JOIN liqliquidaciones ON liq_numliquida = tmp_numliquida
                JOIN liqprocesos ON pro_ID = " . $pProc . " AND liq_numproceso = pro_ID
                JOIN liqreportes ON rep_ReporteID = " . $pID . " AND  rep_codrubro = liq_codrubro
                JOIN liqrubros ON rub_codrubro = liq_codrubro
                GROUP BY 1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19,20,21,22
           ORDER BY 3,4" ;
    return fSQL($db, $slSql);
}/**
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
*   @par    pCue        C�igo de Cuenta que cierra el comprobante
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
*   @par        $ilEstadoproc		C�ifo de estado
*   @par        $ilNumProces		Numero de Proceso
*   @par        $ilNumProduc		C�igo de Productor
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

    $slSql = "CREATE TEMPORARY TABLE tmp_liquidaciones
                     SELECT
                    pro_ID AS liq_NumProceso,
                    ldi_numliquida as liq_numliquida,
                    ldi_codempresa as liq_codempresa,
                    ldi_codrubro as liq_codrubro,
                    sum(ldi_valtotal * rub_IndDbCr) as tmp_valtotal,
                    com_codreceptor as tmp_codProd
                    FROM liqprocesos
                        INNER JOIN liqliquidacionesdist ON ldi_NumProceso = pro_ID
					 	INNER JOIN liqrubros on rub_codrubro = ldi_codrubro
                        JOIN liqreportes ON rep_reporteID = " . $pTipo . " AND rep_codrubro = ldi_codrubro and length(rep_titlargo) > 0                            
					 	INNER JOIN concomprobantes ON com_tipocomp = 'LQ' AND com_numcomp = ldi_numliquida
						";
    $pQry = str_replace('liq_liqnumproceso', 'ldi_liqnumproceso', $pQry);
    if ($pQry) $slSql .= " WHERE " . str_replace('tac_Embarcador', 'com_codreceptor', $pQry);
    $slSql .=  " GROUP BY 1, 2, 3, 4 ";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (1)', true,false);
    $slSql = "CREATE TEMPORARY TABLE tmp_procesos
                     SELECT DISTINCT liq_NumProceso AS tmp_procID
                     FROM tmp_liquidaciones  ";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2)', true,false);

//
    $slSql = "DROP TABLE IF EXISTS tmp_ch1";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2a)', true,false);
    $slSql = "DROP TABLE IF EXISTS tmp_ch2";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2b)', true,false);
    $slSql = "CREATE TEMPORARY TABLE tmp_ch1
                SELECT e.com_tipocomp, l.com_numcomp, det_numcheque
                    FROM 	conenlace
                    	JOIN concomprobantes l ON enl_tipo ='LQ' AND l.com_tipocomp = enl_tipo
                             AND l.com_numcomp = enl_ID AND enl_opcode = 6
                    	JOIN tmp_procesos ON l.com_numproceso = tmp_procid
                    	JOIN concomprobantes e ON e.com_tipocomp = enl_tipocomp and e.com_numcomp = enl_numcomp
                    	JOIN condetalle ON det_regnumero = e.com_regnumero AND det_codcuenta = '1101020'
                    WHERE l.com_tipocomp ='LQ' ";

    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2c)', true,false);
    $slSql = "CREATE TEMPORARY TABLE tmp_ch2
                SELECT e.com_tipocomp, l.com_numcomp, det_numcheque
                    FROM 	conenlace
                    	JOIN concomprobantes l ON enl_tipo ='LQ' AND l.com_tipocomp = enl_tipo
                             AND l.com_numcomp = enl_ID AND enl_opcode = 8
                    	JOIN tmp_procesos ON l.com_numproceso = tmp_procid 
                    	JOIN concomprobantes e ON e.com_tipocomp = enl_tipocomp and e.com_numcomp = enl_numcomp
                    	JOIN condetalle ON det_regnumero = e.com_regnumero AND det_codcuenta = '1101020'
                    WHERE l.com_tipocomp ='LQ' ";

    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2d)', true,false);

//
/****/
    $slSql = "CREATE TEMPORARY TABLE tmp_liqtarjadetal
                 SELECT tad_liqnumero,
                        tad_codempresa,
                        tac_Embarcador, concat(left(per_Apellidos,22-length(left(per_Nombres,11))), ' ', left(per_Nombres,12)) AS tmp_productor,
                        sum(tad_cantrecibida - tad_cantrechazada  ) AS tmp_cantembarcada
                 FROM ((tmp_procesos JOIN liqtarjadetal  ON tad_LiqProceso = tmp_procID )
                                     JOIN liqtarjacabec  ON tar_NUmTarja = tad_NumTarja)
                                     JOIN conpersonas    ON per_Codauxiliar = tac_Embarcador
                 GROUP by 1, 2, 3";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (3)', true,false);
    //
    // Generacion de tabla de referencias cruzadas, en base al detalle de liquidaciones requeridas
//@TODO:  HAcer columnas a elegir dinamicas, abasadas en parametro de BD (liqprocesos).
    $slSql ="SELECT
            var_tipopago as TP,
            UPPER(tmp_productor) AS 'C1',
            tad_codempresa as 'EM',
            tad_liqnumero AS 'NU',
            tmp_CodProd   AS 'CO',
            tmp_cantembarcada AS 'C2',
            tmp_ch1.det_numcheque AS E1,
            tmp_ch2.det_numcheque AS E2, 
            SUM(CASE WHEN  1  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D1',
            SUM(CASE WHEN  2  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D2',
            SUM(CASE WHEN  3  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D3',
            SUM(CASE WHEN  4  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D4',
            SUM(CASE WHEN  5  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D5',		    
            SUM(CASE WHEN  10  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D10',
            SUM(CASE WHEN RUB_POSORDINAL  in (11,12,13,14,15,16,20,21,22,23,24,25,26,27,28) THEN tmp_valtotal ELSE 0 END)  AS 'D11',
		    SUM(CASE WHEN  var_tipopago = 'TR' AND RUB_POSORDINAL  in (1,2,3,4,5,10) THEN tmp_valtotal ELSE 0 END)  AS 'TR',
		    SUM(CASE WHEN  var_tipopago <> 'TR' THEN tmp_valtotal ELSE 0 END)  AS 'CH',
		    SUM(tmp_valtotal)  AS 'TT'
            FROM (tmp_liquidaciones l
                JOIN tmp_liqtarjadetal t on   liq_numliquida = tad_liqnumero and tad_codempresa = liq_codempresa)
                JOIN liqreportes ON rep_reporteID = " . $pTipo . " AND rep_codrubro = liq_codrubro
                JOIN liqrubros on rub_codrubro = liq_codrubro
                LEFT JOIN tmp_ch1 on tad_liqnumero = tmp_ch1.com_numcomp
                LEFT JOIN tmp_ch2 on tad_liqnumero = tmp_ch2.com_numcomp
                LEFT JOIN v_conVariablesProd on var_codauxiliar = tmp_Codprod
            GROUP BY 1, 2, 3,4,5,6,7,8 ORDER BY 1,2,3";
    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE LIQUIDACION', true,false);
//die("--------------");    
    return $rsLiq;
}

/*
    Inicio ----------------------------------------------------------------------
*/
//$IsValid = checkdate($ValidatingDate[ccsMonth], $ValidatingDate[ccsDay], $ValidatingDate[ccsYear]);
$inicio = microtime();
$txt = "<br> Inicio&nbsp;&nbsp;&nbsp;&nbsp;: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
if(isset($_SESSION["g_user"])) $slUsuario=$_SESSION["g_user"];
$ilNumProces=fGetParam('pPro',false);
$ilNumCompro=fGetParam('pCom',false);
$ilNumCheque=fGetParam('pChq',false);
$ilNumProduc=trim(fGetParam('pPrd',false));
$ilTipoCompr=fGetParam('pTip',false);

$DB2 = fGetParam("pDB2", "");
if (strlen($DB2) > 0) $DB2 .=  "";

$ilUltimoprod=0;
$gProdQry=(strlen($ilNumProduc)>0)? " AND com_codReceptor = " . $ilNumProduc : " ";
$slMensaje  =false;
$slusuario="nn";
$ilPeriodo = false;
//
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->debug=fGetParam('pAdoDbg', 0);
$db->SetFetchMode(ADODB_FETCH_BOTH);
fDesBloquea($db);
/*                                      Segundfa conexion */
/*
$d2 = &ADONewConnection('mysql');
$d2->autoRollback = true;
$d2->PConnect("192.168.0.4", DBUSER, DBPASS, fGetParam("pDB2", "09_costafru")); // #fahMay/07/09
$d2->debug=fGetParam('pAdoDbg', 0);
$d2->SetFetchMode(ADODB_FETCH_BOTH);
fDesBloquea($d2);
                                                       */
$rs = $db->Execute("SELECT * FROM liqprocesos WHERE pro_ID = ". $ilNumProces );
if (!$rs) fErrorPage('',"NO ESTA DEFINIDO EL PROCESO  " . $ilNumProces);
$rs->MoveFirst();
$rec = $rs->FetchNextObject();
//print_r($rec);
$dlFecLiquid=$rec->PRO_FECHALIQUID;
$dlFecCierre=$rec->PRO_FECHACIERRE;
$gdFecInicio='2004/12/31';                                // Fecha Inicial Para todos los procesos
$ilSemana   = $rec->PRO_SEMANA;
$ilAno = $rec->PRO_ANOPROCESO;
$ilEstadoProc = (isset($_GET["pOpc"]))?$_GET["pOpc"]:$rec->PRO_ESTADO;
$ilEstado26   = ($ilEstadoProc == 26)? true : false;        // fah Mzo/17/07
if (!$ilNumProces) $slMensaje = "NO SE HA DEFINIDO EL PROCESO<BR>";
if (!$dlFecLiquid) $slMensaje .= "NO SE HA DEFINIDO FECHA DE LIQUIDACION<BR>";
if (!$dlFecCierre) $slMensaje .= " NO SE HA DEFINIDO FECHA DE CIERRE<BR>";
if (!$ilSemana) $slMensaje .= " SEMANA DE PROCESO INVALIDA<BR>";
//if (!$ilAno) $slMensaje .= " A� DE PROCESO INVALIDO<BR>";
if ($slMensaje) fErrorPage('',$slMensaje, true, false);
unset($rs);
set_time_limit(20*60);
//$ilEstadoProc +=1;
//                                                                      Determinar el periodo contable y su estado
$slSqlper="SELECT per_PerContable, per_estado FROM conperiodos WHERE per_Aplicacion ='LI' AND per_NumPeriodo = " . $ilSemana;
$rs= $db->Execute($slSqlper);
$ilPeriodo=0;
if ($rs->RecordCount() == 1) {
        $ilPeriodo = $rs->fields[0];
        if ($rs->fields[1] == -1) fErrorPage('','LA SEMANA ESTA CERRADA, NO SE PUEDE ALTERAR INFORMACION', true, false);
        }
if (!isset($ilPeriodo)  || NZ($ilPeriodo) == 0) fErrorPage('','NO SE DETERMINO EL PERIODO CONTABLE CORRESPONDIENTE A ESTA SEMANA', true, false);
/*
 Seleccion de datos primarios
 Secuencias de acciones segn el proceso a ejecutar
$oLog = new cBitacoraRec($db,'PR', $ilNumProces,'Ejecuta nivel ' , $ilEstadoProc);
$oLog->fGraba();
*/
//                                                        Eliminacion de Registros generados en procesos anteriores
//if ( $ilEstadoProc < 6 AND  $ilEstadoProc > 11 ) fErrorPage('','LA OPERACION SELECCIONADA NO SE APLICA DESDE ESTA PAGINA', true, false);
if ( $ilEstadoProc < 6 ) fErrorPage('','LA OPERACION SELECCIONADA NO SE APLICA DESDE ESTA PAGINA', true, false);
$agPla=array();
fLimpiezaPrev($db,$ilEstadoProc, $ilNumProces, $ilNumProduc);
$ilInd = -1;
if ($ilEstadoProc == 6) $ilInd = 20;
switch ($ilEstadoProc) {
    case 26:
        $ilEstadoProc = 6;          //                  pARA QUE SE REGISTRE EL ENLACE  DE EMISION DE CHEQUE
        $ilInd = 1;
    case 6:
        echo "<br>PASO $ilEstadoProc.-  PROVISION DE LIQUIDACION  :</font></td></tr><tr><td>";
        echo "<table width='90%' align='center' ><tr><td >NOMBRE</td ><td >COMPROB. NRO.</td ><td >EMPRESA</td ><td >TIPO PAGO</td ><td >VALOR</td ></tr ><tr ><td >";
        $pQry=fGetParam('pQryLiq', 0);
        set_time_limit (0) ;
        $gsTitGeneral="";

        $pQry=" ldi_numProceso = " . $ilNumProces;
        $rs = fDefineQry($db, $pQry );
                
        $agPla['CXP'] = (strlen($rec->PRO_CODCUENTA1) >  1) ? $rec->PRO_CODCUENTA1 : "20000001" ;
        $agPla['CXL'] = (strlen($rec->PRO_CODCUENTA2) >  1) ? $rec->PRO_CODCUENTA1 : "20000099" ;
        $agPla['CXC'] = (strlen($rec->PRO_CODCUENTA3) >  1) ? $rec->PRO_CODCUENTA1 : "10030699" ;
        $agPla['ACI'] = "";
        $agPla['CAP'] = "";
        $agPla['TAP'] = "";
        $agPla['AAP'] = "";
        if(!fContabiliza2($db, $rs, "PROVISION DE LIQUIDACION, SEM. " . $ilSemana)) $ilEstadoProc = -1;
 // Automatizacion de Proceso de liq negativas
        $ilEstadoProc = 11;
        break;
    default:
        fErrorPage('','NO SE PUEDE APLICAR LA OPERACION ' . $ilEstadoProc, true, false);
}
//        echo "<table align='center'>";
//echo "</table>";
if ($ilEstadoProc > 0 && $ilEstadoProc <= 7) {
    $ilEstadoProc = $ilEstadoProc ;
    $qStr = "UPDATE liqprocesos SET pro_Estado = " . $ilEstadoProc  . " WHERE pro_ID = " . $ilNumProces;
    if(!$db->Execute($qStr)) fErrorPage('','NO SE ACTUALIZO EL ESTADO DEL PROCESO', true, false);
}
else   echo "<br><br><center>  -o- </center>";
$final = microtime();
$qStr = $_SERVER['PHP_SELF'] . "?" . 'pFLiq=' .$dlFecLiquid. '&pFCie=' .$dlFecCierre. '&pPro=' .$ilNumProces.
                                    '&pSemana='.$ilSemana.   '&pQry='  .fGetParam("pQry", "");
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




