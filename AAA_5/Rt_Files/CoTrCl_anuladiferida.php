<?php
/**
*   Proceso para la Anulacion de una transaccionde Contable
*   @param      pRec     GET   Numero de Registro del comprobante a anular
*   @param      pFec     GET   Fecha de anulacion, para el comprobante anulador (aaa-mm-dd)
*
*   @rev    Gina Franco 22/May/09   Variacion de archivo CoTrTr_anula.php
*   @rev    Gina Franco 22/May/09   se cambio para que retornara un arreglo con el resultado,
*                                   y en el detalle se cambio que en el valor del debe se actualice con el del haber y viceversa
**/
if (!isset ($_SESSION)) @session_start();
error_reporting (E_ALL);
/*if (!isset($_SESSION["g_user"]) && (isset($_GET["ByPass"]) && $_GET["ByPass"]=='prueba'))
{
    include_once("General2.inc.php");
    define("RelativePath", "..");
    include_once("adodb.inc.php");
    include_once("tohtml.inc.php");
    include_once("../Common.php");
    include_once("../LibPhp/ConLib.php");
    include_once("GenUti.inc.php");
}
else include ("../LibPhp/LibInc.php");*/   // para produccion

include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");

include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
$db->debug=fGetParam('pAdoDbg', 1);

/**
*   Anulacion de una transaccionde Contable
*   @param      $pRNum  Integer     Nùmero de registro del comprobante
**/
function fContabInv($pRNum, $pFecha){
    
    
    //se realizan validacion para saber si el usuario tiene privilegios para ejecutar la opcion
    //y de que los comprobantes a anular solo sean del dia actual
    if (1 != $_SESSION["atr"]["CoTrTr"]["ADF"])
        return array("failure"=>true,"totalRecords"=>1,"message"=>"Usted no tiene privilegios para ejecutar esta opcion");
    /*elseif (date('Y-m-d') != date($agPar["pFecContab"]))
            return array("failure"=>true,"totalRecords"=>1,"message"=>"Solo se pueden anular comprobantes de la fecha actual.");
    */
    
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    $db = &ADONewConnection('mysql');
    $db->autoRollback = true;
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
    $db->debug=fGetParam('pAdoDbg', 0);
    $trSql = "SELECT com_tipocomp, cla_descripcion, cla_tipocomp, cla_Contabilizacion, cla_indtransfer,
                    cla_ctaorigen, cla_ctadestino, cla_auxorigen,
                    cla_auxdestino, cla_ctaingresos, cla_ctacosto, cla_ctadiferencia, cla_reqreferencia,
                    cla_reqsemana,cla_clatransaccion, cla_indicosteo, cla_ImpFlag
            FROM concomprobantes JOIN genclasetran ON cla_tipocomp = com_tipocomp
            WHERE com_regnumero = " . $pRNum;
    $rs = $db->execute($trSql);
    if (!$rs){
        //fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
        return array("failure"=>true,"totalRecords"=>1,"message"=>"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
    }
    $rs->MoveFirst();
    $cla = $rs->FetchNextObject(false);
/*  error_log ("func\r\n", 3, "/tmp/my-errors.log");
    error_log (" SQL: " . $trSql . "\r\n", 3, "/tmp/my-errors.log");
    error_log (print_r($cla) . " contab1: " . $cla->cla_contabilizacion . "\r\n", 3, "/tmp/my-errors.log");
 */
    
    if (!$cla->cla_Contabilizacion ) return true;
    $trSql = "SELECT
                concomprobantes.*, condetalle.*
             FROM concomprobantes JOIN condetalle ON det_regnumero = com_regnumero
            WHERE com_regnumero = " . $pRNum;
    $rs = $db->execute($trSql);
    if (!$rs){
        //fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum  );
        return array("failure"=>true,"totalRecords"=>1,"message"=>"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
    }
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
        //print_r($tra);
//        $tra = array_change_key_case($tr, CASE_LOWER);
        if ($fNum ==1 )  { //                                En el primer registro de detalle
            $slAnular = $tra->com_TipoComp . " - " .$tra->com_NumComp  ;
            $slSqlper="SELECT per_PerContable, per_estado FROM conperiodos WHERE per_Aplicacion ='CO' AND '" . $pFecha . "' BETWEEN per_fecinicial AND per_fecfinal ";
            $per= $db->Execute($slSqlper);
            $ilPeriodo=0;
            if ($per->RecordCount() == 1) {
                    $ilPeriodo = $per->fields[0];
                    if ($per->fields[1] == -1){
                        //fErrorPage('','EL PERIDO CONTABLE ESTA CERRADO, NO SE PUEDE ALTERAR INFORMACION', true, false);
                        return array("failure"=>true,"totalRecords"=>1,"message"=>"EL PERIDO CONTABLE ESTA CERRADO, NO SE PUEDE ALTERAR INFORMACION");
                    }
            }
            if (!isset($ilPeriodo)  || NZ($ilPeriodo) == 0){
                //fErrorPage('','NO SE DETERMINO EL PERIODO CONTABLE CORRESPONDIENTE A ESTA SEMANA', true, false);
                return array("failure"=>true,"totalRecords"=>1,"message"=>"NO SE DETERMINO EL PERIODO CONTABLE CORRESPONDIENTE A ESTA SEMANA");
            }

            $ilNumComp = NZ(fDBValor($db, 'concomprobantes', 'MAX(com_numcomp)', "com_tipocomp = 'AN'"),0) + 1 ;
            $slUsuario=$_SESSION['g_user'];
            if(!fAgregaComprobante($db, 'AN', $ilNumComp, 0,
                $pFecha, $pFecha, $pFecha, $tra->com_emisor,
                $tra->com_CodReceptor, $tra->com_Receptor,
                "ANULACION DE  " . $tra->com_TipoComp . " - " . $tra->com_NumComp,
                0, 1, 9999, 0, $tra->com_RefOperat, 5, 1, $tra->com_NumProceso,
                593, $slUsuario, $ilPeriodo, NULL))
                //fErrorPage('',"NO SE PUDO GRABAR EL COMPROBANTE  " . $tra->det_TipoComp . "-" . $ilNumComp);
                return array("failure"=>true,"totalRecords"=>1,"message"=>"NO SE PUDO GRABAR EL COMPROBANTE  " . $tra->det_TipoComp . "-" . $ilNumComp);
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
        $lastrec['det_valdebito'] = $tra->det_ValCredito;
        $lastrec['det_valcredito'] = $tra->det_ValDebito;
        $lastrec['det_clasregistro'] = 0;
        $lastrec['det_estlibros'] = 1;
        $lastrec['det_feclibros'] = date("Y-m-d");
        fInsDetalleCont($db, $lastrec);
        $fNum+=1;
    }
    //echo " ANULADO EL COMPROBANTE " . $slAnular . " CON  AN - " . $ilNumComp . " <BR>" ;
    $recActualiza = array();
    $recActualiza['det_RegNumero'] = $pRNum;
    $recActualiza['com_Concepto'] = "ANULADO CON COMPROBANTE AN - " . $ilNumComp.". ";
    if (!fActualizaConcepto($db,$recActualiza)){
        return array("failure"=>true,"totalRecords"=>1,"message"=>"NO SE PUDO ACTUALIZAR EL COMPROBANTE  " . $tra->det_TipoComp . "-" . $ilNumComp);
    }
    
    $olResp= array("success"=>true,"totalRecords"=>1,"message"=>" ANULADO EL COMPROBANTE " . $slAnular . " CON  AN - " . $ilNumComp);
    return $olResp;
}

/**
*   Actualiza un registro anulado
* 	@access public
*   @param  variant $conn         Referencia a una Conexión ya establecida
*   @param  array   $record       Arreglo que contiene los valores a insertar, con indide = nombre del campo
* 	@return bool                  Resultado de la inserción
*/
function fActualizaConcepto (&$conn, &$alDetalle)
 {
    $sSql = "update concomprobantes
            set com_Concepto = concat('".$alDetalle['com_Concepto']."',com_Concepto)
            where com_regnumero=".$alDetalle['det_RegNumero']."";
    //echo $sSql;
    if(!$conn->Execute($sSql)){ //fErrorPage('','NO SE PUDO ACTUALIZAR DETALLE DE COMPROBANTE '. $alDetalle['det_RegNumero'] . ' / ' . $alDetalle['det_CodItem']  , true, false);
        return false;
    }
    
    $sSql = "update condetalle
            set det_glosa = concat('".$alDetalle['com_Concepto']."',det_glosa)
                ,det_EstLibros = 1
                ,det_FecLibros = '".date("Y-m-d")."'
            where det_regnumero=".$alDetalle['det_RegNumero']."";

    if(!$conn->Execute($sSql)){ //fErrorPage('','NO SE PUDO ACTUALIZAR DETALLE DE COMPROBANTE '. $alDetalle['det_RegNumero'] . ' / ' . $alDetalle['det_CodItem']  , true, false);
        return false;
    }else return true;
}


/**
--------------------------------------------------------------------------------------
		INICIO
--------------------------------------------------------------------------------------
**/
//if (fGetparam('pGen', false) && fGetparam('pReg', false)) //  El proceso es llamado directamente

$ogResp=fContabInv(fGetparam('pReg', false), fGetparam('pFec', false));

print(json_encode($ogResp));
?>
