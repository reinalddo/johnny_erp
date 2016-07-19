<?php
/**
*   Proceso para la Anulacion de una transaccionde Contable
*   @param      pRec     GET   Numero de Registro del comprobante a anular
*   @param      pFec     GET   Fecha de anulacion, para el comprobante anulador (aaa-mm-dd)
**/
error_reporting (E_ALL);
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
$db->debug=fGetParam('pAdoDbg', 1);
/**
*   Anulacion de una transaccionde Contable
*   @param      $pRNum  Integer     Nùmero de registro del comprobante
**/
function fContabInv($pRNum, $pFecha){
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    $db = &ADONewConnection('mysql');
    $db->autoRollback = true;
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
    $db->debug=fGetParam('pAdoDbg', 0);
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
    if (!$cla->cla_contabilizacion ) return true;
    $trSql = "SELECT
                concomprobantes.*, condetalle.*
             FROM concomprobantes JOIN condetalle ON det_regnumero = com_regnumero
            WHERE com_regnumero = " . $pRNum;
    $rs = $db->execute($trSql);
    if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum  );
    $rs->MoveFirst();
    $lastrec= array();
    $fNum=1;
    $fSuma=0;
    $fImpIva=0;
    $fImpIce=0;
    $lastrec['det_regnumero'] = $pRNum;
    $lastrec['det_estejecucion'] = 0;
    $lastrec['det_fecejecucion'] = 0;
    $lastrec['det_estlibros']   = 0;
    $lastrec['det_feclibros']   = 0;
    $lastrec['det_refoperativa']    = 0;
    $lastrec['det_numcheque']   = 0;
    $lastrec['det_feccheque']   =0;
    $lastrec['det_codcuenta']   = "";
//
    while (!$rs->EOF) {
        $tra = $rs->FetchNextObject(false);
//        $tra = array_change_key_case($tr, CASE_LOWER);
        if ($fNum ==1 )  { //                                En el primer registro de detalle
            $slAnular = $tra->com_TipoComp . " - " .$tra->com_NumComp  ;
            $slSqlper="SELECT per_PerContable, per_estado FROM conperiodos WHERE per_Aplicacion ='CO' AND '" . $pFecha . "' BETWEEN per_fecinicial AND per_fecfinal ";
            $per= $db->Execute($slSqlper);
            $ilPeriodo=0;
            if ($per->RecordCount() == 1) {
                    $ilPeriodo = $per->fields[0];
                    if ($per->fields[1] == -1) fErrorPage('','EL PERIDO CONTABLE ESTA CERRADO, NO SE PUEDE ALTERAR INFORMACION', true, false);
                    }
            if (!isset($ilPeriodo)  || NZ($ilPeriodo) == 0) fErrorPage('','NO SE DETERMINO EL PERIODO CONTABLE CORRESPONDIENTE A ESTA SEMANA', true, false);

            $ilNumComp = NZ(fDBValor($db, 'concomprobantes', 'MAX(com_numcomp)', "com_tipocomp = 'AN'"),0) + 1 ;
            $slUsuario=$_SESSION['g_user'];
            if(!fAgregaComprobante($db, 'AN', $ilNumComp, 0,
                $pFecha, $pFecha, $pFecha, $tra->com_Emisor,
                $tra->com_CodReceptor, $tra->com_Receptor,
                "ANULACION DE  " . $tra->com_TipoComp . " - " . $tra->com_NumComp,
                0, 1, 9999, 0, $tra->com_RefOperat, 5, 1, $tra->com_NumProceso,
                593, $slUsuario, $ilPeriodo, NULL))
                fErrorPage('',"NO SE PUDO GRABAR EL COMPROBANTE  " . $tra->det_TipoComp . "-" . $ilNumComp);
            $ilRegNumero = $db->Insert_ID();
            $db->Execute("delete from condetalle WHERE det_regnumero = " . $ilRegNumero );
        }
        $lastrec['det_regnumero'] = $ilRegNumero;
        $lastrec['det_tipocomp']  = 'AN';
        $lastrec['det_numcomp']   = $ilNumComp;
        $lastrec['det_idauxiliar']= $tra->det_IDAuxiliar;
        $lastrec['det_secuencia'] = $tra->det_Secuencia;
        $lastrec['det_glosa']     = "ANULACION DE  " . $tra->com_TipoComp . " - " . $tra->com_NumComp;
        $lastrec['det_codcuenta'] = $tra->det_CodCuenta;
        $lastrec['det_valdebito'] = $tra->det_ValDebito;
        $lastrec['det_valcredito'] = $tra->det_ValCredito;;
        $lastrec['det_clasregistro'] = 0;
        fInsDetalleCont($db, $lastrec);
        $fNum+=1;
    }
    echo " ANULADO EL COMPROBANTE " . $slAnular . " CON  AN - " . $ilNumComp . " <BR>" ;
    return true;
}
/**
--------------------------------------------------------------------------------------
		INICIO
--------------------------------------------------------------------------------------
**/
//if (fGetparam('pGen', false) && fGetparam('pReg', false)) //  El proceso es llamado directamente
    fContabInv(fGetparam('pReg', false), fGetparam('pFec', false));
?>
