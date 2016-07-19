<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
    <title>LIQUIDACIONES: PROCESO 06</title>
</head>
<body bgcolor="#fffff7" link="#000000" alink="#ff0000" vlink="#000000" text="#000000" style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; FONT-SIZE: 11px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; magin: 0; spacing: 0" bottommargin="0" nowrap leftmargin="0" topmargin="17" rightmargin="0" marginwidth="0" marginheight="0">
<style type="text/css">
</style>
<table width="50%" align="center">
<tr>
    <td align="center">
<?php
/** Genera un comprobante contable con la transaccion COMEX
**/

include ("../LibPhp/LibInc.php");
include("LiLiPr_func.inc.php");
include("../LibPhp/ConTranLib.php");

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
                com_regnumero AS det_regnumero,
                '". $pTip . "' AS det_tipocomp,
                liq_numliquida AS det_numcomp,
                com_feccontab AS det_feccontab,
                liq_secuencia AS det_secuencia,
                1 AS det_clasregistro,
                com_codreceptor AS com_productor,
                com_codreceptor AS det_idauxiliar,
                (rub_Inddbcr * (" . $pSig . ") * liq_valtotal) AS det_valdebito,
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
                tmp_liqtotal,
                cue_reqauxiliar as cue_reqauxiliar,
                cue_tipauxiliar as cue_tipauxiliar
            FROM tmp_liquidado
                 JOIN liqliquidaciones ON liq_NumLiquida = tmp_NUmLiquida
                 JOIN liqprocesos ON pro_ID = " . $pProc . " AND liq_numproceso = pro_ID
                 JOIN concomprobantes ON  com_tipocomp = 'LQ' AND  com_numcomp = liq_numliquida AND com_numproceso =  liq_numproceso
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
    global $ilUltimoProd;
    global $agPla; //                               Plantilla de Cuentas

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
        $rsdat = array_change_key_case($rsdat, CASE_LOWER);
        $lastrec['det_feclibros'] = '2020-12-31'; //        Fecha futura de proceso en el banco
        if($ilUltimoProd != $rsdat['com_productor'] ) { //    Al cambio de productor:
            $lastrec['det_glosa'] = $pGlosa;
            if ($fSuma <> 0) {
                if ($ilChqFlag) {//                                         Para regstro del nro de cheque
                    $lastrec['det_numcheque'] = $ilNumChq;
                    $ilNumChq += 1;
                }
                $lastrec['det_idauxiliar'] = $ilUltimoProd;
                $lastrec['det_secuencia'] = $fNum + 1;
                $lastrec['det_regnumero'] = $ilRegNumero;
                $lastrec['det_numcomp']   = $ilNumComp;
                $lastrec['det_glosa']     = $pGlosa;
                $lastrec['det_codcuenta'] = $agPla['CCI'];
                $lastrec['det_valdebito'] = -$fSuma;
                $lastrec['det_valcredito'] = 0;
                fCierraComprob($db, $fSuma, $lastrec);

                }
            $ilUltimoProd = $rsdat['com_productor'];
            $fSuma = 0;
            $fNum=1;
            // Inserta un nuevo comprobnate
            // fBloquea($db, "concomprobantes");
            $lastrec=$rsdat;
            $slProductor = fDBValor($db, 'conpersonas', "concat(per_Apellidos, ' ' , per_Nombres)", "per_codAuxiliar = " . $lastrec['det_idauxiliar'] );
            if (!$ilComFlag) //                                     Definir si el Nro Comp es autosecuencia o viene como param
                $ilNumComp = NZ(fDBValor($db, 'concomprobantes', 'MAX(com_numcomp)', "com_tipocomp = '" .$lastrec['det_tipocomp']. "'"),0) + 1 ;
            else {
                    $ilNumComp = $ilPxmoCom;
                    $ilPxmoCom += 1;
            }
            $ilAux = AplicaAuxil($lastrec['cue_reqauxiliar'], $lastrec['cue_tipauxiliar'], $lastrec['det_idauxiliar'], $ilUltimoProd);
            $l = strlen($slProductor);
            $p = 60 - $l;
//            echo str_pad(' ', 40*6, '&nbsp;') . $slProductor . str_repeat('&nbsp;',$p) . $lastrec['det_tipocomp'] . " - " . $ilNumComp .  "   Cheque: " . ($ilNumChq ) . "<br>";
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $slProductor . "</td><td>" . $lastrec['det_tipocomp'] . " - " . $ilNumComp . "</td><td> Cheque: " . ($ilNumChq ) . "</td></tr><tr><td>";




            if (!fAgregaComprobante($db, $lastrec['det_tipocomp'], $ilNumComp, 0, $rsdat['det_feccontab'], $rsdat['det_feccontab'],
                $rsdat['det_feccontab'], $ilAux, $rsdat['det_idauxiliar'], $slProductor,
                $pGlosa . " (liq # " . $lastrec['det_numcomp'] . ")",
                0, 1, 9999, 0, $ilSemana, 5, 1, $ilNumProces,
                593, $slUsuario, $ilPeriodo, date("Y-m-d")))
                fErrorPage('',"NO SE PUDO GRABAR EL COMPROBANTE  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp);
            $ilRegNumero = $db->Insert_ID();
            
            
            
            
            fDesBloquea($db, "concomprobantes");
            $slSql = "REPLACE INTO conenlace(enl_tipo, enl_ID, enl_opCode, enl_secuencia, enl_tipocomp, enl_numcomp, enl_usuario)
                            VALUES ('LQ'," . $lastrec['det_numcomp']. "," . $ilEstadoProc . "," . 0 . ",'" .
                                    $lastrec['det_tipocomp'] . "'," . $ilNumComp . ",'". $slUsuario ."')";
            if (!$db->Execute($slSql)) fErrorPage('',"NO SE PUDO GRABAR EL ENLACE  " . $lastrec['det_numcomp'] . "-" . $ilNumComp);
            $fSuma = 0;
//
            if (($rsdat['tmp_liqtotal'] <> 0) && $pPrev) {//                Viene un acumulado previo (ej: caso Cheque Voucher)
                $lastrec=$rsdat;
                $lastrec['det_regnumero'] = $ilRegNumero;
                $fNum=+1;
                $lastrec['det_secuencia'] =$fNum;
                $fNum=+1;
                $lastrec['det_codcuenta'] = $agPla['CAP'];
                $lastrec['det_valdebito'] = $lastrec['tmp_liqtotal'];
                $lastrec['det_valcredito'] = 0;
                $lastrec['det_glosa'] = "Total";
                $lastrec['det_idauxiliar'] = AplicaAuxil(true, $agPla['TAP'], $agPla['AAP'], $ilUltimoProd);
                if (!fInsDetalleCont($db, $lastrec))
                fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;
                $fSuma +=$lastrec['tmp_liqtotal'];
            }
        }
        unset($lastrec);
        $fNum +=1;
        $rsdat['det_secuencia'] = $fNum;
        $rsdat['det_regnumero'] = $ilRegNumero;
        $rsdat['det_numcomp']   = $ilNumComp;
        $rsdat['det_glosa']     = $pGlosa;
        $lastrec=$rsdat;
        if ($rsdat['det_codcuenta'] == ''){
                $rsdat['det_codcuenta']=$agPla['CAP'];
        }
        if (!$rsdat['cue_reqauxiliar']) $rsdat['det_idauxiliar'] = 0;
        $fSuma += $rsdat['det_valdebito'] - $rsdat['det_valcredito'];
        $rsdat['det_idauxiliar'] = AplicaAuxil($rsdat['cue_reqauxiliar'], $rsdat['cue_tipauxiliar'], $rsdat['det_idauxiliar'], $lastrec['det_idauxiliar']);;
        if(!fInsDetalleCont($db, $rsdat))
            fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE:  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;
        $finalrec = $lastrec;
      $rs->MoveNext();
    }
    if ($fSuma <> 0) {
        $lastrec=$finalrec;
        if ($ilChqFlag) {//                                         Para regstro del nro de cheque
                $lastrec['det_numcheque'] = $ilNumChq;
		$lastrec['det_feccheque'] = '2020-12-31';
        }
        $lastrec['det_secuencia'] = $fNum + 1;
        $lastrec['det_regnumero'] = $ilRegNumero;
        $lastrec['det_numcomp']   = $ilNumComp;
        $lastrec['det_glosa']     = $pGlosa;
        $lastrec['det_codcuenta'] = $agPla['CCI'];
        $lastrec['det_valdebito'] = -$fSuma;
        $lastrec['det_valcredito'] = 0;
        fCierraComprob($db, $fSuma, $lastrec);
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
    Inicio ----------------------------------------------------------------------
*/
$inicio = microtime();
$txt = "<br> Inicio&nbsp;&nbsp;&nbsp;&nbsp;: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
if(isset($_SESSION["g_user"])) $slUsuario=$_SESSION["g_user"];
$ilNumProces=fGetParam('pPro',false);
$ilNumCompro=fGetParam('pCom',false);
$ilNumCheque=fGetParam('pChq',false);
$ilNumProduc=trim(fGetParam('pPrd',false));
$ilTipoCompr=fGetParam('pTip',false);
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
//
$rs = $db->Execute("SELECT * FROM liqprocesos WHERE pro_ID = ". $ilNumProces );
if (!$rs) fErrorPage('',"NO ESTA DEFINIDO EL PROCESO  " . $ilNumProces);
$rs->MoveFirst();
$rec = $rs->FetchNextObject();
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
if (!$ilAno) $slMensaje .= " A� DE PROCESO INVALIDO<BR>";
if ($slMensaje) fErrorPage('',$slMensaje, true, false);
unset($rs);
set_time_limit(20*60);
//$ilEstadoProc +=1;
//                                                                      Determinar el periodo contable y su estado
// Fecha del comprobante per_fecinicial y per_fecfinal
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
        echo "<br>PASO $ilEstadoProc.-  CONTABILIZACION EGRESO UNICO  :</font></td></tr><tr><td>";
        fSelRubrosLiq($db, $ilInd);
        fSelTotalesLiq($db, $ilNumProces, $ilInd, 1);
        $rs =& fSelDatosContab($db, $ilNumProces, $ilInd, $rec->PRO_TIPOCOMPROB );
        $agPla['CCI'] = $rec->PRO_CODCUENTA1 ;
        $agPla['TCI'] = $rec->PRO_AUXILIAR1;
        $agPla['ACI'] = $rec->PRO_AUXILIAR1;
        $agPla['CAP'] = "";
        $agPla['TAP'] = "";
        $agPla['AAP'] = "";
        if(!fContabiliza2($db, $rs, "PAGO DE LIQUIDACION, SEM. " . $ilSemana)) $ilEstadoProc = -1;
 // Automatizacion de Proceso de liq negativas
        $ilEstadoProc = 11;
		fLimpiezaPrev($db,$ilEstadoProc, $ilNumProces, $ilNumProduc);
        echo "<br>PASO $ilEstadoProc.-  DEBITO POR LIQUIDACIONES EN NEGATIVO.:</font></td></tr><tr><td>";
        fSelRubrosLiq($db, 20);
        fSelTotalesNeg($db, $ilNumProces,  20);
        $agPla['CCI'] = NZ(fDBValor($db, 'genparametros', 'par_valor1', "par_clave='CLQNEG'"), false);
        $agPla['TCI'] = NZ(fDBValor($db, 'genparametros', 'par_valor2', "par_clave='CLQNEG'"), 0);
        if (!$agPla['CCI']) fErrorPage(' ', "CUENTA DE LIQUIDACIONES NEGATIVAS NO DEFINIDA EN PARAMETROS  ");
        $rs =& fSelDatosContab($db, $ilNumProces, 20, 'DC', 1);
        $agPla['CCN'] = $agPla['CCI'];
        if(!fContabiliza2($db, $rs, "Saldo Neg. o Cero, liq. S/" . $ilSemana)) $ilEstadoProc = -1;
        break;
    case 7:
        echo "<br>PASO $ilEstadoProc.-  INGRESO A CAJA:</font></td></tr><tr><td>";
        fSelRubrosFin($db);
        fSelTotalesLiq($db, $ilNumProces, 20, 1);
        $rs =& fSelDatosContIng($db, $ilNumProces, 30, 'IC', 1);
        $agPla['CCI'] = $rec->PRO_CODCUENTA2;
        $agPla['TCI'] = $rec->PRO_AUXILIAR2;
        $agPla['ACI'] = $rec->PRO_AUXILIAR2;
        $agPla['CAP'] = NZ(fDBValor($db, 'genparametros', 'par_valor1',   "par_clave='CUCAJA'"), false);
        $agPla['TAP'] = NZ(fDBValor($db, 'genparametros', 'par_valor2+0', "par_clave='CUCAJA'"), 0);
        $agPla['AAP'] = $agPla['TAP'];
        $agPla['CCN'] = NZ(fDBValor($db, 'genparametros', 'par_valor1', "par_clave='CLQNEG'"), false); //Cuenta Cierra Negativos   fah/28/07
        $agPla['ACN'] = NZ(fDBValor($db, 'genparametros', 'par_valor2', "par_clave='CLQNEG'"), 0);  //Cuenta Cierra Negativos
        if (!$agPla['AAP']) fErrorPage(' ', "CUENTA CAJA NO DEFINIDA EN PARAMETROS  ");
        if(!fContabiliza2($db, $rs, "Abono a Cuenta, S/" . $ilSemana, true)) $ilEstadoProc = -1;
        break;
    case 8:
        echo "<br>PASO $ilEstadoProc.-  CONTABILIZACION CHEQUE FINAL:</font></td></tr><tr><td>";
        fSelRubrosLiq($db, 30);
        fSelTotalesLiq($db, $ilNumProces, 30, 1);
        $agPla['CCI'] = $rec->PRO_CODCUENTA3;
        $agPla['TCI'] = $rec->PRO_AUXILIAR3;
        $agPla['ACI'] = $rec->PRO_AUXILIAR3;
        $agPla['CAP'] = NZ(fDBValor($db, 'genparametros', 'par_valor1', "par_clave='CUCXPP'"), false);
        $agPla['TAP'] = -1;
        $agPla['AAP'] = false;
        if (!$agPla['CAP']) fErrorPage(' ', "CUENTA POR PAGAR PRODUCTORES NO DEFINIDA EN PARAMETROS  ");
        $rs =& fSelDatosFinal($db, $ilNumProces, 30, $rec->PRO_TIPOCOMPROB);
        if(!fContabiliza2($db, $rs, "Saldo Pendiente, Semana " . $ilSemana)) $ilEstadoProc = -1;
        break;
    case 9:
        echo "<br>PASO $ilEstadoProc.-  DEBITO POR TRANSPORTE:</font></td></tr><tr><td>";
        fSelRubrosLiq($db, 50);
        fSelTotalesLiq($db, $ilNumProces, 50, -1);
        $rs =& fSelDatosConDeb($db, $ilNumProces, 50, 'DC', -1);
        $agPla['CCI'] = NZ(fDBValor($db, 'genparametros', 'par_valor1', "par_clave='CUTRAN'"), false);
        $agPla['TCI'] = 0;
        $agPla['ACI'] = 0;
        $agPla['CAP'] = "1141080"; // TODO: parametrizar
        $agPla['TAP'] = -1;
        $agPla['AAP'] = false;
        if (!$agPla['CCI']) fErrorPage(' ', "CUENTA DE TRANSPORTE NO DEFINIDA EN PARAMETROS  ");
        if(!fContabiliza2($db, $rs, "Debito por Transporte, Semana  " . $ilSemana)) $ilEstadoProc = -1;
        break;
    case 10:
        echo "<br>PASO $ilEstadoProc.-  DEBITO POR ADELANTOS:</font></td></tr><tr><td>";
        fSelRubrosLiq($db, 60);
        fSelTotalesLiq($db, $ilNumProces, 60, -1);
        $agPla['CCI'] = NZ(fDBValor($db, 'genparametros', 'par_valor1', "par_clave='CUADEL'"), false);
        $agPla['TCI'] = NZ(fDBValor($db, 'genparametros', 'par_valor2', "par_clave='CUADEL'"), 0);
        $agPla['ACI'] = $agPla['TCI'];
        $agPla['CAP'] = "1141017";
        $agPla['TAP'] = -1;
        $agPla['AAP'] = false;
        if (!$agPla['CCI']) fErrorPage(' ', "CUENTA DE ADELANTOS NO DEFINIDA EN PARAMETROS  ");
        $rs =& fSelDatosConDeb($db, $ilNumProces, 60, 'EC', -1);
        if(!fContabiliza2($db, $rs, "Adelanto a cuenta de Fruta, Semana  " . $ilSemana)) $ilEstadoProc = -1;
        break;
    case 11:
        echo "<br>PASO $ilEstadoProc.-  DEBITO POR LIQUIDACIONES EN NEGATIVO:</font></td></tr><tr><td>";
        fSelRubrosLiq($db, 1);
        fSelTotalesNeg($db, $ilNumProces, 1);
        $agPla['CCI'] = NZ(fDBValor($db, 'genparametros', 'par_valor1', "par_clave='CLQNEG'"), false);
        $agPla['TCI'] = NZ(fDBValor($db, 'genparametros', 'par_valor2', "par_clave='CLQNEG'"), 0);
        if (!$agPla['CCI']) fErrorPage(' ', "CUENTA DE LIQUIDACIONES NEGATIVAS NO DEFINIDA EN PARAMETROS  ");
        $rs =& fSelDatosContab($db, $ilNumProces, 1, 'DC', 1);
        $agPla['CCN'] = $agPla['CCI'];
        if(!fContabiliza2($db, $rs, "Saldo Neg. o Cero en liq., Sem/" . $ilSemana, false, false)) $ilEstadoProc = -1;
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




