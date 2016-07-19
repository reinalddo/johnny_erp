<?php
/**
*   Formato de balance General escalonado, con varias columnas.. Salida HTML
*   @param
*   @rev	fah	25/11/08	Habilitar bandera para generacion HTML 'o excel (pAdoDbg, forza generacion HTML)
*///
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
function fGetData($pSql, $ilPeriodo, $ilNivel, $pEsquema) {
    global $db;
    global $slTipo;
    $aSql=Array();
    $aSql[] ="DROP TABLE IF EXISTS tmp_auxiliares";
    $aSql[] ="drop table  if exists tmp_balcomp";

    $aSql[] ="CREATE TEMPORARY TABLE tmp_auxiliares
                SELECT
                        ucase( rpad(concat( left(per_Apellidos, 18), ' ', left(ifnull(per_Nombres,' '), 15)), " . (($slTipo =='COM')?20:45) . ", '-') )as tmp_descripcion,
                        per_codauxiliar as tmp_codauxiliar
                FROM conpersonas";
    $aSql[] ="INSERT INTO tmp_auxiliares
                SELECT  ucase( rpad(concat( left(act_descripcion, 18), ' ', left(ifnull(act_descripcion1,' '), 15))," . (($slTipo =='COM')?20:45) . ", '-') )as tmp_descripcion,
                        act_codauxiliar as tmp_codauxiliar
                FROM conactivos";
                
    $aSql[] ="CREATE INDEX i_tmpaux on tmp_auxiliares(tmp_codauxiliar)";
/**
*               Valores de cuentas ascendentes
**/
    $aSql[] ="CREATE TABLE  tmp_balcomp
	            SELECT
                    red_codcuenta as cuenta ,
                    red_Ascendent,
                    cc.cue_padre as cue_padre ,
                    cc.cue_id as cue_id,
                    red_codcuenta  AS det_codcuenta,
                    000000 as det_idauxiliar,
                    cc.cue_tipmovim as cue_tipmovim,
                    sum(round(det_valdebito,2) - round(det_valcredito,2) -0000000000.00) as salan,
                    0000000000.00 as valdb, 0000000000.00 as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
 	            FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
	            WHERE com_numperiodo < ". $ilPeriodo . " AND
                    com_estProceso = 5 AND
	                det_regnumero = com_regnumero AND
                    concuentas.cue_codcuenta = det_codcuenta AND
	                red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent
                    AND red_codcuenta
                GROUP BY 1,2,3,4, 5,6
                ORDER BY 1,2" ;
/**
*       Cuentas de movimiento
**/
    $aSql[] ="INSERT INTO tmp_balcomp
              SELECT det_codcuenta as cuenta ,
                    9 as red_Ascendent,
                    cue_id as cue_padre,
                    0  as cue_id,
                    det_codcuenta,
                    det_idauxiliar,
                    1 as cue_tipmovim,
                    sum(round(det_valdebito,2) - round(det_valcredito,2)) as salan,
                    0000000000.00 as valdb, 0000000000.00 as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
                FROM concomprobantes JOIN condetalle on det_regnumero = com_regnumero
                    JOIN concuentas ON cue_codcuenta = det_codcuenta
                    LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
                WHERE com_numperiodo < ". $ilPeriodo . "  AND com_estProceso = 5 AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0
                GROUP BY 1,2,3,4, 5,6,7
                HAVING saldo <> 0
                ORDER BY 1,2";
/**
*       Cuentas de movimiento
**/
    $aSql[] ="INSERT INTO tmp_balcomp
	            SELECT
                    red_codcuenta as cuenta ,
                    red_Ascendent,
                    cc.cue_padre as cue_padre ,
                    cc.cue_id as cue_id ,
                  	red_codcuenta  AS det_codcuenta,
                    00000 as det_idauxiliar,
                    cc.cue_tipmovim as cue_tipmovim,
                    sum(0000000000.00) as salan,
                    sum(round(det_valdebito,2))  as valdb ,
                    sum(round(det_valcredito,2)) as valcr,
                    sum(round(det_valdebito,2) - round(det_valcredito,2)) as saldo
                FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
                WHERE com_numperiodo = ". $ilPeriodo . " AND com_estProceso = 5 AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND
                    red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent AND red_codcuenta AND
                    (det_valdebito <> 0 or det_valcredito <> 0)
                GROUP BY 1,2,3,4, 5,6, 7
                ORDER BY 1,2";
/**
*       Cuentas de movimiento del periodo
**/

    $aSql[] ="INSERT INTO tmp_balcomp
               SELECT det_codcuenta as cuenta , 9 as red_Ascendent, cue_id as cue_padre,
                    00000  as cue_id,
	                det_codcuenta, det_idauxiliar,
              	    1 as cue_tipmovim,
                    sum(0000000000.00) as salan,
	                sum(round(det_valdebito,2))  as valdb ,
                    sum(round(det_valcredito,2)) as valcr,
	                sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
                FROM concomprobantes JOIN condetalle on det_regnumero = com_regnumero
                     JOIN concuentas ON cue_codcuenta = det_codcuenta
                     LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
	            WHERE com_numperiodo = ". $ilPeriodo . "  AND com_estProceso = 5 AND
	                det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0 and
	                (det_valdebito <> 0 or det_valcredito <> 0)
             GROUP BY 1,2,3,4,5,6
             ORDER BY 1,7";

    $aSql[] = "CREATE INDEX i_tmpsal on tmp_balcomp(cuenta, red_ascendent)";
    $slSaldoCondicion = (($slTipo == 'COM') ? "  " : "HAVING (sum(salan) <> sum(valdb) - sum(valcr)  )") ;
//echo $slSaldoCondicion;
    $rs= fSQL($db, $aSql);
    
    $slSql ="SELECT                    
                     concat( if(det_idauxiliar=0,cuenta, concat(repeat('&nbsp;', cue_posicion * 2), det_idauxiliar)) )AS CUE,
                    left (concat( repeat('&nbsp;', cue_posicion *4),
                        if (det_idauxiliar = 0 ,
                            cue_descripcion,
                            concat('     ', left(ifnull(per_apellidos,''),25),
                                    left(ifnull(act_descripcion,''),25), ' ',
                                    left(ifnull(per_nombres,''),25) ,
                                    left(ifnull(act_descripcion1, ''),25)
                    ) ) ), 200) AS DES,
                    SUM(round(salan,2)) AS SAN,
                    SUM(round(valdb,2) + 0.00) AS VDB,
                    SUM(round(valcr,2) + 0.00) AS VCR ,
                    SUM(round(valdb -valcr,2) + 0.00) AS SAB,
                    SUM(round(salan + valdb -valcr,2) + 0.00) AS SNT,
                    left(cuenta,1) AS GRU,
                    cue_posicion AS NIV,
                    cuenta AS CTA

                FROM tmp_balcomp join concuentas on cue_codcuenta = det_codcuenta" .
                         (($pEsquema)? ' AND det_codcuenta ' . $pEsquema : '') .
                         " left join conpersonas on per_codauxiliar = det_idauxiliar
                          left join conactivos on act_codauxiliar = det_idauxiliar
                WHERE cue_posicion + if(det_idauxiliar>0,1,0)  <= ". $ilNivel . "
                GROUP BY GRU, NIV, CTA, CUE,DES "
                . $slSaldoCondicion .
                "ORDER by CTA, per_Apellidos, per_Nombres, act_Descripcion";
    return $slSql;
    
}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento 
//--------------------------------------------------------------------------------------------------------------------
//
define("RelativePath", "..");
include_once("General.inc.php");
include_once("adodb.inc.php");
;include_once("tohtml.inc.php");
include_once("GenUti.inc.php");
include_once("../Common.php");
include_once("../LibPhp/ConLib.php");
//include_once("../LibPhp/LibInc.php");
include_once('baaGrid_ok.php');
error_reporting(ALL);
if (fGetparam("pExcel",true)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   header('Content-Disposition: attachment; filename="archivo.xls"');
}
$slQry   = fGetParam('pQryBal', false);

$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_BOTH);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$ilPeriodo  =fGetParam('pPer', 1);
$ilNivel    =fGetParam('pNiv', 6);
$slEsquema  =fGetParam('pEsq', '');
$slTipo     =fGetParam('p','COM');
$slSql = fGetData($db, $ilPeriodo, $ilNivel, $slEsquema);

/*********************************/
set_time_limit (0) ;
	$goGrid = new baaGrid ($slSql, DB_MYSQL);
	
	$goGrid->showErrors(); // just in case
	$goGrid->setHeaderClass('CobaltColumnTD');
	$goGrid->setRowClass('CobaltDataTD,CobaltAltDataTD');
//	$goGrid->showColNumbers(true);

    switch ($slTipo) {
        case "GEN":
            $slTit = "BALANCE GENERAL";
            break;
        case "PYG":
            $slTit = "ESTADO DE PERDIDAS Y GANANCIAS" ;
            break;
        default:
            $slTit = "SALDOS" ;
    }
    $goGrid->hideColumn(8);
    $goGrid->hideColumn(9);

    if ($slTipo == 'GEN' || $slTipo == 'PYG') { // Procesar Balance General 0 PIG
        $goGrid->setHeadings('CUENTA,DESCRIPCION,SALDO FINAL');
        }
    else {
        $goGrid->setHeadings('CUENTA,DESCRIPCION,SALDO ANTERIOR,DEBITOS,CREDITOS,SALDO PERIODO, SALDO FINAL');
    }
	$goGrid->setTableAttr('border="1" nowrap align="center" cellspacing="1" title="' .$slTit .'" width="'. $ilTabW . '"');
	$goGrid->setWidth( 0, 90);
	$goGrid->setWidth( 1, 380);
	$goGrid->setWidth( 2, 90);
	$goGrid->setWidth( 3, 90);
	$goGrid->setWidth( 4, 90);
	$goGrid->setWidth( 5,  90);
	$goGrid->setWidth( 6, 90);
    $goGrid->setOnChange(7,1);
    $goGrid->setOnChange(8,1);

	for ($j=2; $j<=7; $j++) 	{
		$goGrid->setWidth($j, 90);
		$goGrid->setDecPlaces($j, 2);
		$goGrid->setTotal($j, 2);
	}
    $goGrid->setSubTotalClass('sub1');
?>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<link rel="stylesheet" type="text/css" href="http://192.168.0.59/AAA/AAA_SEGURIDAD/Themes/Cobalt/Style.css">
<title> <? echo $slTit ; ?></title>
<style type="text/css">
<!--
.head1 {background: #CC9933; height:22px; color: #FFFFFF; font-size: 9pt; font-weight: 500=medium; text-align: center}
.sub1 {background: #C8B588; height:22px; color: #FFFFFF; font-size: 9pt; font-weight: 500=medium; text-align: center}
.data1 {background: #EEEECC; font-size: 9pt}
.data2 {background: #CCCCCC; font-size: 9pt}
TD { nowrap:true}

/*printer friendly styles*/
.border {border: solid 1px #CCCCCC}
.TLB {border: solid 1px #CCCCCC; border-right: none}
.TB {border-top:  solid 1px #CCCCCC; border-bottom:  solid 1px #CCCCCC}
.TRB {border: solid 1px #CCCCCC; border-left: none}
.T {border-top: solid 1px #CCCCCC}
.L {border-left: solid 1px #CCCCCC}
.B {border-bottom: solid 1px #CCCCCC}
.LR {border-left: solid 1px #CCCCCC; {border-right: solid 1px #CCCCCC}
.LB {border-left: solid 1px #CCCCCC; {border-bottom: solid 1px #CCCCCC}
.LRB {border: solid 1px #CCCCCC; border-top: none}

/* End of style section. Generated by AceHTML at 16/08/03 10:14:34 */
-->
</style>
</head>
<body bgcolor="#fffff7" link="#000000" alink="#ff0000" vlink="#000000" text="#000000" class="CobaltPageBODY" style="PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; PADDING-TOP: 0px; magin: 0; spacing: 0" bottommargin="0" nowrap leftmargin="0" topmargin="17" rightmargin="0" marginwidth="0" marginheight="0">
<?php
//	echo $goGrid->externHead;
	$goGrid->display();
	ob_end_flush();
?>
