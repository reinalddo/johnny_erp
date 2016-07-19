<?php
/**
*   Proceso para la Duplicacion de una transaccionde Contable
*   @param      pRec     GET   Numero de Registro del comprobante a anular
*   @param      pFec     GET   Fecha de anulacion, para el comprobante anulador (aaa-mm-dd)
**/
//error_reporting (E_ALL);
include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
include_once("../LibPhp/ConTranLib.php");
include_once("../LibPhp/ConTasas.php");
$db->debug=fGetParam('pAdoDbg', 1);
/**
*   Duplicaicon  de una transaccionde Contable
*   @param      $pRNum  Integer     Nùmero de registro del comprobante
**/
function fDuplicaTrans($pRNum, $pFecha){
	global $tra;
	global $db;
	global $giCount;
	global $gfSumDB;
	global $gfSumCR;
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
	$pFecha = date("Y-m-d", time());
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
            if (!isset($ilPeriodo)  || NZ($ilPeriodo) == 0) fErrorPage('','NO SE DETERMINO EL PERIODO CONTABLE CORRESPONDIENTE A ESTA FECHA ' . $pFecha, true, false);

            $ilNumComp = NZ(fDBValor($db, 'concomprobantes', 'MAX(com_numcomp)', "com_tipocomp = '" . $tra->com_TipoComp . "'"),0) + 1 ;
            $slUsuario=$_SESSION['g_user'];
			if(is_null($tra->com_Emisor) || strlen($tra->com_Emisor) <1) $tra->com_Emisor = 0;
			if(is_null($tra->com_CodReceptor) || strlen($tra->com_CodReceptor) <1) $tra->com_CodReceptor = 0;
            if(!fAgregaComprobante($db, $tra->com_TipoComp, $ilNumComp, 0,
                $pFecha, $pFecha, $pFecha, $tra->com_Emisor,
                $tra->com_CodReceptor, $tra->com_Receptor,
                "DUPLICACION DE   " . $tra->com_TipoComp . " - " . $tra->com_NumComp,
                0, 1, 9999, 0, $tra->com_RefOperat, 5, 1, $tra->com_NumProceso,
                593, $slUsuario, $ilPeriodo, NULL))
                fErrorPage('',"NO SE PUDO GRABAR EL COMPROBANTE  " . $tra->det_TipoComp . "-" . $ilNumComp);
            $ilRegNumero = $db->Insert_ID();
            $db->Execute("delete from condetalle WHERE det_regnumero = " . $ilRegNumero );
        }
        $lastrec['det_regnumero'] = $ilRegNumero;
        $lastrec['det_tipocomp']  = $tra->com_TipoComp;
        $lastrec['det_numcomp']   = $ilNumComp;
        $lastrec['det_idauxiliar']= $tra->det_IDAuxiliar;
        $lastrec['det_secuencia'] = $tra->det_Secuencia;
        $lastrec['det_glosa']     = $tra->det_Glosa;
        $lastrec['det_codcuenta'] = $tra->det_CodCuenta;
        $lastrec['det_valdebito'] = $tra->det_ValDebito;
        $lastrec['det_valcredito'] = $tra->det_ValCredito;;
        $lastrec['det_clasregistro'] = 0;
        fInsDetalleCont($db, $lastrec);
        $fNum+=1;
        $gfSumDB += $tra->det_ValDebito;
        $gfSumCR += $tra->det_ValCredito;
    }
	$giCount=$fNum;
//    echo " DUPLICACION EXITOSA, <br> COMPROBANTE GENERADO: " . $tra->com_TipoComp . " - " . $ilNumComp . " <BR>" ;
    global $gsMensaje;
    global $gsTipoComp;
    global $giNumReg;
    global $giNumComp;
    $giNumComp = $ilNumComp;
	$gsMensaje  = " DUPLICACION EXITOSA, <br> COMPROBANTE GENERADO: " . $tra->com_TipoComp . " - " . $ilNumComp . " <BR>" ;
	$gsTipoComp = $tra->com_TipoComp ;
	$giNumReg=$ilRegNumero;
    return true;
}
/**
*   Entrada en la bitacora de seguridad, anotando las condiciones de duplicacion
**/
function InTrTr_Bitacora($pTipo='D'){
/*
    global $gfValTotal;
    global $gfCosTotal;
    global $gfCanTotal; */
    global $tra;
    global $db;
   	global $giCount;
	global $gfSumDB;
	global $gfSumCR;
	global $giNumComp;
	global $gsTipoComp;
    $alFecContab = $tra->com_FecContab; // Fecha en formato de arreglo
    $slTxt = "  E: " . $tra->com_Emisor .
             "  R: " . $tra->com_CodReceptor .
             "  F: " . $tra->comFecContab. " / RO:" . $tra->com_RefOperat;
    $slOperac = "DUPLICADO DE " . $gsTipoComp . " - " .  $tra->com_NumComp; ;
    $ilNumComp = $giNumComp;
    fRegistroBitacoraAdo($db, $gsTipoComp, // Tipo Comprobante
                      $giNumComp,// Numero Comprobante
                      $_SESSION['g_user'], // Usuario
                      $pAnot = $slOperac . $slTxt,
                      $giCount,
                      $gfSumDB,
                      $gfSumCR,
                       " " ,
                       0,
                       0);
}
//if (fGetparam('pGen', false) && fGetparam('pReg', false)) //  El proceso es llamado directamente
/******************************************************************************************************************
					INICIO
********************************************************************************************************************/
 	$gsMensaje = "";
 	$gsTipoComp="";
 	$giNumReg=0;
 	$giNumComp = 0;
 	$tra=NULL;
 	$gfSumDB=0;
 	$gfSumCR=0;
 	$giCount=0;
 	$db = NULL;
    $ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
    $db = &ADONewConnection('mysql');
    $db->autoRollback = true;
    $db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
//  $db->PConnect('localhost', 'root', 'xx', 'datos');
//  $db->SetFetchMode(ADODB_FETCH_BOTH);
    $db->debug=fGetParam('pAdoDbg', 0);

    fDuplicaTrans(fGetparam('pReg', false), fGetparam('pFec', false));
    InTrTr_Bitacora($pTipo='D');
?>
<html>
<head>
  <base target="_self">
  <link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
  <link rel="stylesheet" type="text/css" href="../Themes/Menu/Style.css">
  <title>DUPLICACION</title>
<script languaje="JavaScript1.2">
function fCargaComp() {
//	alert(window.opener.location);
	window.opener.location="../In_Files/InTrTr_Cabe.php?com_RegNumero=<?echo $giNumReg?>&pTipoComp=<? echo $gsTipoComp?>";
	window.focus
	self.close();	
}
</script>
  <body bgcolor="#fffff7" link="#000000" alink="#ff0000" vlink="#000000" text="#000000" class="CobaltPageBODY" style="FONT-SIZE: 14px">
	<center>
  <?
  	
      echo $gsMensaje ."<br><br>";
//      com_RegNumero=110870&pTipoComp=DG

   ?>
    	<!-- BEGIN Button Button_Ver -->
        <input class="" name="btVer" type="button" style="FONT-SIZE: 9px; WIDTH: 70px; HEIGHT: 20px"
                        value="VER ... " title="Carga el Comprobante Generado" accesskey="V" 
						onClick="fCargaComp()">
        <!-- END Button Button_Ver -->   
   </center>
   </body>
</html>