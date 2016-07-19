<?php
/**
*   Generacion del debito por intereses, aplicado a CXC
*   @author     Fausto Astudillo
*   @create     Nov/11/04
*   @rev        Ene/15/05
*   @param      $pCue       String  Codigo de Cuenta sobre la que se generara
*   @param      $pFec
**/

//error_reporting (E_ALL);
include ("../LibPhp/LibInc.php");   // para produccion
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConLib.php");
include_once("../LibPhp/ConTasas.php");
$db->debug=fGetParam('pAdoDbg', 1);
session_start();
/**
*   Contabilizacion de una transaccionde inventario
*   @param      $db     Object      Byref, Coneccion ala base de datos
*   @param      $pRNum  Integer     Nùmero de registro del comprobante
**/
function fGenInteres(){
    $tasaInt=12;
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    $db = &ADONewConnection('mysql');
    $db->autoRollback = true;
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
    $db->debug=fGetParam('pAdoDbg', 0);
    $ilNumPeriodo = fGetParam('pPer', 0);
    $trSql = "DELEtE FROM concomprobantes
                WHERE com_tipocomp = 'D2' AND com_numProceso = " . $ilNumPeriodo ;
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUEDE DESHACER PROCESOS PREVIOS PARA ESTE PERIODO " . $ilNumPeriodo );
//
    $trSql = "SELECT per_numperiodo, per_fecinicial, per_fecfinal, per_percontable,
					 datediff(per_fecfinal, per_fecinicial) as tmp_numdias
                FROM conperiodos WHERE per_aplicacion = 'CC' AND per_numperiodo = " . $ilNumPeriodo ;
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO OBTENER INFORMACION DEL PERIODO " . $pRNum);
    $rs->MoveFirst();
    $per = $rs->FetchNextObject(false);
//
    $trSql = "SELECT cla_descripcion, cla_tipocomp,
                    cla_ctaorigen, cla_ctadestino, cla_auxorigen,
                    cla_auxdestino, 
                    cla_reqsemana, cla_clatransaccion
            FROM  genclasetran 
            WHERE cla_tipocomp = 'D2'";
    $rs = $db->execute($trSql);
    
    if (!$rs->RecordCount()) fErrorPage('',"NO SE PUDO SELECCIONAR EL TIPO DE TRANSACCION " . $pRNum);
    $rs->MoveFirst();
    $cla = $rs->FetchNextObject(false);
    if (strlen($cla->cla_ctaorigen) <2) fErrorPage('',"NO SE HA DEFINIDO LA CUENTA CONTABLE PARA EL TIPO DE  TRANSACCION " );
    if (strlen($cla->cla_ctadestino) <2) fErrorPage('',"NO SE HA DEFINIDO LA CUENTA CONTABLE DESTINO PARA EL TIPO DE  TRANSACCION ") ;
/*  error_log ("func\r\n", 3, "/tmp/my-errors.log");
    error_log (" SQL: " . $trSql . "\r\n", 3, "/tmp/my-errors.log");
    error_log (print_r($cla) . " contab1: " . $cla->cla_contabilizacion . "\r\n", 3, "/tmp/my-errors.log");
 */
    $trSql =   "DROP TABLE IF EXISTS tmp_trans " ;
/**  Transacciones del periodo
**/
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO CREAR LA TABLA TEMPORAL " );

    $trSql =   "CREATE TEMPORARY TABLE tmp_trans
                SELECT  det_codcuenta, det_idauxiliar, com_feccontab,
	                   datediff(per_fecfinal, com_feccontab ) as tmp_numdias,
	                   sum(det_valdebito - det_valcredito) as tmp_valor
                FROM conperiodos JOIN  concomprobantes ON per_aplicacion = 'CC' AND per_numperiodo = " . $ilNumPeriodo . "
			           AND com_feccontab between per_fecinicial AND per_fecfinal
	                   JOIN condetalle on det_regnumero = com_regnumero
                WHERE det_codcuenta like '1141030'
                GROUP BY 1,2,3,4
                ORDER by det_codcuenta, det_idauxiliar, com_feccontab ";
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO CREAR LA TABLA TEMPORAL " );
/**  Saldos Anteriores
**/
    $trSql = "CREATE INDEX i_tr ON tmp_trans(det_codcuenta, det_idauxiliar)";
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO CREAR LA TABLA TEMPORAL DE SALDOS " );
    $trSql =   "INSERT INTO tmp_trans
                SELECT det_codcuenta, det_idauxiliar, " . $per->per_fecinicial . "  as tmp_fecha, ".
	                   $per->tmp_numdias . "  as tmp_numdias,
	                   sum(det_valdebito - det_valcredito) as tmp_valor
                FROM concomprobantes  JOIN condetalle on det_regnumero = com_regnumero
                WHERE com_feccontab < '" . $per->per_fecinicial . "' AND
					  det_codcuenta like '1141030'
                GROUP BY 1,2,3,4
                HAVING tmp_valor <> 0
                ORDER by det_codcuenta, det_idauxiliar, tmp_fecha ";

    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO AÑADIR TRANSACCIONES " );
    $trSql =   "SELECT  det_codcuenta, det_idauxiliar,
						concat(per_Apellidos, ' ', per_nombres) as tmp_nombre,
						SUM(ROUND(tmp_valor * " . $tasaInt . " * tmp_numdias / 36000, 2)) as det_valdebito
                FROM tmp_trans JOIN conpersonas on per_codauxiliar = det_idauxiliar
                GROUP BY 1,2  ORDER BY tmp_nombre";
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR INFORMACION PARA CALCULOS " . $pRNum  );
    $rs->MoveFirst();
    $lastrec= array();
    $fNum=1;
    $fSuma=0;
    $lastrec= array();
    $lastrec['det_tipocomp'] = 'D2';
    $lastrec['det_estejecucion'] = 0;
    $lastrec['det_fecejecucion'] = 0;
    $lastrec['det_estlibros']   = 0;
    $lastrec['det_feclibros']   = 0;
    $lastrec['det_refoperativa']  = 0;
    $lastrec['det_numcheque']   = 0;
    $lastrec['det_feccheque']   =0;
    $lastrec['det_codcuenta']   = "";
    $lastrec['det_glosa'] = "INTERESES ENTRE " . $per->per_fecinicial . " y " . $per->per_fecfinal ;
    $lastrec['det_clasregistro'] = 0;
    $slUser=$_SESSION['g_user'];
//
	echo "<br><br><br> GENERACION DE INTERESES";
    while (!$rs->EOF) {
        $rsdat = $rs->GetRowAssoc();
        $rsdat = array_change_key_case($rsdat, CASE_LOWER);
        if($rsdat['det_valdebito'] > 0) { //                    no Grabar valores Negativos
            $ilNumComp = NZ(fDBValor($db, 'concomprobantes', 'MAX(com_numcomp)', "com_tipocomp = '" .$lastrec['det_tipocomp']. "'"),0) + 1 ;
            if (!fAgregaComprobante($db, $lastrec['det_tipocomp'], $ilNumComp, 0, $per->per_fecfinal, $per->per_fecfinal,
                    $per->per_fecfinal, 0, $rsdat['det_idauxiliar'], '',
                    $lastrec['det_glosa'],
                    0, 1, 9999, 0, 0, 5, 1,  $ilNumPeriodo,
                    593, $slUser, $per->per_percontable, date("Y-m-d")))
               fErrorPage('',"NO SE PUDO GRABAR EL COMPROBANTE  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp);
            $ilRegNumero = $db->Insert_ID();
            fDesBloquea($db, "concomprobantes");
            $lastrec['det_secuencia'] = 1;
            $lastrec['det_regnumero'] = $ilRegNumero;
            $lastrec['det_numcomp']   = $ilNumComp;
            $lastrec['det_codcuenta'] = $cla->cla_ctaorigen;
            $lastrec['det_idauxiliar'] = $rsdat['det_idauxiliar'];
            $lastrec['det_valdebito'] = $rsdat['det_valdebito'];
            $lastrec['det_valcredito'] = 0;
    /*
            if(!fInsDetalleCont($db, $lastrec))
                fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE:  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;
    */
            if ($rsdat['det_valdebito'] > 0 ) { //                      No grabar valores en contra
                $j= fInsDetalleCont($db, $lastrec);
                $lastrec['det_secuencia'] = 2;
                $lastrec['det_codcuenta'] = $cla->cla_ctadestino;
                $lastrec['det_valdebito'] = -$rsdat['det_valdebito'];
                $lastrec['det_idauxiliar'] = 0;
                if(!fInsDetalleCont($db, $lastrec))
                    fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE:  " . $lastrec['det_tipocomp'] . "-" . $ilNumComp) . "-" . $lastrec['det_secuencia'] ;
            }
            echo "<BR> " . $lastrec['det_tipocomp'] . " - " . $lastrec['det_regnumero']  . " : " . $rsdat['tmp_nombre'] . " $ " . $rsdat['det_valdebito'];
        }
    $rs->MoveNext();
    }
    return true;
}
/**
*       INICIO
**/
$FileName="";
if (!fValidAcceso("","070","")) {
    	fMensaje ("UD NO TIENE ACCESO A ESTA FUNCION", 1);
        die(); }
fGenInteres();
echo "<br><br>PROCESO TERMINADO SATISFACTORIAMENTE"

?>
