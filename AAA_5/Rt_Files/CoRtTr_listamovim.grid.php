<?php
//header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
?>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
<title>RESUMEN DE COMPRAS</title>
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
/*
*   InTrTr_rescomprprov.php: Resumen de Transacciones Compras de Inventario
*   @author     Fausto Astudillo
*   @param      string 		pQryTar  Condici�n de b�squeda
*   @output     contenido pdf del reporte.
*/
include_once("../LibPhp/LibInc.php");
include_once('baaGrid.php');
error_reporting(ALL);
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   $db Refrencia a la conexxion de BD
*   @param  string    $pQry Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){
    $slSql = "SELECT  concat(year(fechaRegistro),'-', left(monthname(fecharegistro),3)) as PERIODO_ ,
                	concat(tra.tab_codigo, '-', tra.tab_Descripcion) AS TRANSACCION,
                	left(sus.tab_Descripcion,30) AS '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SUSTENTO&nbsp;TRIBUTARIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                	tipoComprobante AS TCO,
                	left(tco.tab_Descripcion,20) AS '&nbsp;&nbsp;TIPO&nbsp;COMPROBANTE&nbsp;&nbsp;',
               	    concat(establecimiento,'-', puntoEmision, '-', secuencial) AS '&nbsp;COMPR.&nbsp;NUMERO&nbsp;  ',
                	autorizacion AS AUTORIZAC,
                	concat(pv2.per_Apellidos, ' ', pv2.per_Nombres) AS '&nbsp;NOMBRE&nbsp;DE&nbsp;PROVEEDOR&nbsp;',
                	pv2.per_Ruc AS RUC,
                	fechaRegistro AS FECHA_DE_REG,
                 	baseImponible AS '&nbsp;B.&nbsp;IMP&nbsp;0%&nbsp;',
                 	valorCifFob   AS '&nbsp;VALOR&nbsp;CIF/FOB&nbsp;',
                	baseImpGrav as '&nbsp;B.&nbsp;IMP&nbsp;&nbsp;IVA&nbsp;',
                	porcentajeIva,
                	montoIva,
                	baseImpIce,
                	porcentajeIce,
                	montoIce,
                	montoIvaBienes,
                	porRetBienes,
                	valorRetBienes,
                	montoIvaServicios,
                	porretServicios,
                	valorretServicios,
                	baseImpAir + baseImpAir2 + baseImpAir3 as baseAirTot,
                	valretAir + valretAir2 + valretAir3 as valAirTot,
                	concat(estabRetencion1, '-', puntoEmiretencion1, '-', secretencion1) as compRetencion
                	
                FROM fiscompras fco
                	LEFT JOIN fistablassri sus ON sus.tab_CodTabla = '3'  AND fco.codSustento +0  = sus.tab_Codigo +0
                	LEFT JOIN fistablassri tco ON tco.tab_CodTabla = '2'  AND fco.tipoComprobante +0 = tco.tab_Codigo
                	LEFT JOIN fistablassri tce ON tce.tab_CodTabla = '2'  AND fco.facturaExportacion +0 = tce.tab_Codigo
                	LEFT JOIN fistablassri civ ON civ.tab_CodTabla = '4'  AND fco.porcentajeIva = civ.tab_Codigo
                	LEFT JOIN fistablassri pic ON pic.tab_CodTabla = '6'  AND fco.porcentajeIce = pic.tab_Codigo
                	LEFT JOIN fistablassri prb ON prb.tab_CodTabla = '5a' AND fco.porRetBienes = prb.tab_Codigo
                	LEFT JOIN fistablassri prs ON prs.tab_CodTabla = '5'  AND fco.porRetServicios = prs.tab_Codigo
                	LEFT JOIN fistablassri cra ON cra.tab_CodTabla = '10' AND fco.codRetAir = cra.tab_Codigo
                    LEFT JOIN fistablassri cr2 ON cr2.tab_CodTabla = '10' AND fco.codRetAir2 = cr2.tab_Codigo
                    LEFT JOIN fistablassri cr3 ON cr3.tab_CodTabla = '10' AND fco.codRetAir3 = cr3.tab_Codigo
                	LEFT JOIN fistablassri ccm ON civ.tab_CodTabla = '2'  AND fco.docModificado = ccm.tab_Codigo
                	LEFT JOIN fistablassri tra ON tra.tab_CodTabla = 'A'  AND fco.tipoTransac = tra.tab_Codigo
                	LEFT JOIN conpersonas  pro ON pro.per_CodAuxiliar = fco.codProv
                	LEFT JOIN genparametros par ON par.par_clave= 'TIPID' AND par.par_secuencia = pro.per_tipoID
                	LEFT JOIN conpersonas  pv2 ON pv2.per_CodAuxiliar = fco.idProvFact
                	LEFT JOIN genparametros pm2 ON pm2.par_clave= 'TIPID' AND pm2.par_secuencia = pv2.per_tipoID
                	LEFT JOIN fistablassri tid ON tid.tab_CodTabla = '8'  AND par.par_Valor2 = tid.tab_Codigo
                        LEFT JOIN genautsri  ON aut_ID = autorizacion
        " ;

    if ($pQry) $slSql .= " WHERE "  . $pQry ;
    $slSql .= " ORDER  BY 1,2,3,4,5, fecharegistro";
//  echo "<br> <br> $slSql";
    return $slSql;
}
/** Process the Report Header
*   You can access any property / method from ezPdfReport Object using var $rpt and group data from variable $group received as parameters
*   To put any text, line, rectangle, etc into your report, use the object $rpt->pdf and its "ez functions" (see ezPdf manual),
*   be care of functions that don�t move the insertion point to void text overlapping
*   Note: This function REDEFINES the top margin.
*   @access public
*   @param  object      $rpt        Reference to current report object
*   @param  object      $hdr        Reference to current header report object
*   @return void
*/

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
//session_start();
$db = NewADOConnection("mysql");
//include("AdoDBIni.php");
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> No hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$pQry= fGetParam('pQryCom', 0);
$sltext="";
$pAnio = fGetParam('s_Anio', 0);
$pMes = fGetParam('s_Mes', 0);

if ($pAnio >0 and $pMes >0 ) {
    $pQry = " YEAR(fechaRegistro) = $pAnio AND month(fechaRegistro) = $pMes";
    $slSql=fDefineQry($db, $pQry );
	$goGrid = new baaGrid ($slSql, DB_MYSQL);
	$goGrid->setTableAttr('border="1" nowrap align="center" cellspacing="1" width="'. $ilTabW . '"');
	$goGrid->showErrors(); // just in case
	$goGrid->setHeaderClass('CobaltColumnTD');
	$goGrid->setRowClass('CobaltDataTD,CobaltAltDataTD');
	$goGrid->externHead=$slHead1;
//	$goGrid->showColNumbers(true);
	$goGrid->setWidth( 0, 450);
	$goGrid->setWidth( 1, 200);
	$goGrid->setWidth( 2, 1000);
	$goGrid->setWidth( 3, 600);
	$goGrid->setWidth( 4, 300);
	$goGrid->setWidth( 5,  300);
	$goGrid->setWidth( 6, 300);
	$goGrid->setWidth( 7, 800);
	$goGrid->setWidth( 8, 200);
	$goGrid->setWidth( 9, 300);
	for ($j=10; $j<=25; $j++) 	{
		$goGrid->setWidth($j, 150);
		$goGrid->setDecPlaces($j, 2);
		$goGrid->setTotal($j, 2);
	}
	$goGrid->setTotal(10, 2);
	$goGrid->setOnChange(0,1,2);
	$goGrid->setOnChange(1,1,2);
/*	$goGrid->setOnChange(2,0,0);
    $goGrid->setOnChange(3,0,0);
	$goGrid->setOnChange(4,0,0);
   */
    $goGrid->setSubTotalClass('sub1');
//	echo $goGrid->externHead;
	$goGrid->display();
	ob_end_flush();
	}
else {
        echo "DEBE ELEGIR UN A�O Y MES ";
}
?>
