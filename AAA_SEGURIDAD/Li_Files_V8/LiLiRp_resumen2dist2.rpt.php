<?php
/*
*   LiliRp_resumen2.php: Emite un resumen general de liquidaciones, en formato pdf VERSION DISTRIBUIDA POR CIA
*   @author     Fausto Astudillo
*   @param      integer		pPro    N?mero de proceso que asocia las liquidaciones
*   @output     contenido pdf del reporte.
*/
error_reporting(E_ALL);
include("../LibPhp/ezPdfReport.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  integer  $ilNUmproces       Numero de proceso de las liquidaciones
*   @return object   Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){
    global $gsTitGeneral;
    IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_liquidaciones")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
    IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_liqtarjadetal")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
    IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_procesos")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
    $pTipo = (isset($_GET['pTipo']))? $_GET['pTipo']:30; // tIPO DE rEPORTE A GENERAR
/**/
    $slSql = " SELECT distinct concat(buq_descripcion, '-', emb_numviaje) AS tmp_Buque
               FROM liqprocesos
                	join liqtarjadetal on tad_liqproceso = pro_ID
                	join liqtarjacabec on tad_numtarja = tar_numtarja
                	join liqembarques  on tac_refoperativa = emb_refoperativa
                	join liqbuques on buq_codbuque = emb_codvapor ";
    if ($pQry) $slSql .= " WHERE " . $pQry;
    $rs= $db->Execute($slSql);
    if(!$rs) fErrorPage('',"NO SE PUDO ACCEDER A VAPORES " . $slSql, true, false);

    while ($rec = $rs->FetchNextObject(true)) {
        $gsTitGeneral .=  (($gsTitGeneral)?', ':'') . $rec->TMP_BUQUE;
    }

    $slSql = "CREATE /*TEMPORARY*/ TABLE tmp_liquidaciones
                     SELECT pro_ID AS liq_NumProceso,
					    ldi_codempresa as liq_codempresa,
						ldi_numliquida as liq_numliquida,
						ldi_codrubro as liq_codrubro,
						sum(ldi_valtotal * rub_IndDbCr) as tmp_valtotal,
						com_codreceptor as tmp_codProd
                     FROM (liqprocesos
							INNER JOIN liqliquidacionesdist ON ldi_NumProceso = pro_ID)
					 		INNER JOIN liqrubros on rub_codrubro = ldi_codrubro
                            JOIN liqreportes ON rep_reporteID = " . $pTipo . " AND rep_codrubro = ldi_codrubro and length(rep_titlargo) > 0                            
					 		INNER JOIN concomprobantes ON com_tipocomp = 'LQ' AND com_numcomp = ldi_numliquida
							 ";
    if ($pQry) $slSql .= " WHERE " . str_replace('tac_Embarcador', 'com_codreceptor', $pQry);
    
    
    $slSql .=  " GROUP BY 1, 2, 3,4";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (1)', true,false);
    $slSql = "CREATE /*TEMPORARY*/ TABLE tmp_procesos
                     SELECT DISTINCT liq_NumProceso AS tmp_procID
                     FROM tmp_liquidaciones  ";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2)', true,false);

//
    $slSql = "DROP TABLE IF EXISTS tmp_ch1";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2a)', true,false);
    $slSql = "DROP TABLE IF EXISTS tmp_ch2";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2b)', true,false);
    $slSql = "CREATE /*TEMPORARY*/ TABLE tmp_ch1
                SELECT e.com_tipocomp, l.com_numcomp, det_numcheque
                    FROM 	conenlace
                    	JOIN concomprobantes l ON enl_tipo ='LQ' AND l.com_tipocomp = enl_tipo
                             AND l.com_numcomp = enl_ID AND enl_opcode = 6
                    	JOIN tmp_procesos ON l.com_numproceso = tmp_procid
                    	JOIN concomprobantes e ON e.com_tipocomp = enl_tipocomp and e.com_numcomp = enl_numcomp
                    	JOIN condetalle ON det_regnumero = e.com_regnumero AND det_codcuenta = '1101020'
                    WHERE l.com_tipocomp ='LQ' ";

    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2c)', true,false);
    $slSql = "CREATE /*TEMPORARY*/ TABLE tmp_ch2
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
    $slSql = "CREATE /*TEMPORARY*/ TABLE tmp_liqtarjadetal
                 SELECT
					tad_codempresa,
					tad_liqnumero,
					tac_Embarcador,
					concat(left(per_Apellidos,22-length(left(per_Nombres,11))), ' ', left(per_Nombres,10)) AS tmp_productor,
                    sum(tad_cantrecibida - tad_cantrechazada  ) AS tmp_cantembarcada
                 FROM ((tmp_procesos JOIN liqtarjadetal  ON tad_LiqProceso = tmp_procID )
                                     JOIN liqtarjacabec  ON tar_NUmTarja = tad_NumTarja)
                                     JOIN conpersonas    ON per_Codauxiliar = tac_Embarcador
                 GROUP by 1, 2, 3, 4";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (3)', true,false);
    //
    // Generacion de tabla de referencias cruzadas, en base al detalle de liquidaciones requeridas
//@TODO:  HAcer columnas dinamicas.
    $slSql ="SELECT tmp_CodProd   AS 'C0',
                    UPPER(tmp_productor) AS 'C1',
                    right(tad_codempresa,length(tad_codempresa) - 3) as 'EM',
					tmp_cantembarcada AS 'C2',
                    tmp_ch1.det_numcheque AS E1,
                    tmp_ch2.det_numcheque AS E2, 
                    SUM(CASE WHEN  1  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D1',
                    SUM(CASE WHEN  2  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D2',
                    SUM(CASE WHEN  3  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D3',
                    SUM(CASE WHEN  4  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D4',
                    SUM(CASE WHEN  5  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D5',		    
                    SUM(CASE WHEN  10  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D10',
                    SUM(CASE WHEN  11  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D11',
                    SUM(CASE WHEN  12  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D12',
                    SUM(CASE WHEN  13  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D13',
                    SUM(CASE WHEN  14  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D14',		    
                    SUM(CASE WHEN  15  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D15',
                    SUM(CASE WHEN  16  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D16',
                    SUM(CASE WHEN  20  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D20',
                    SUM(CASE WHEN  21  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D21',
                    SUM(CASE WHEN  22  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D22',		    
                    SUM(CASE WHEN  23  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D23',
                    SUM(CASE WHEN  24  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D24',
                    SUM(CASE WHEN  25  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D25',
                    SUM(CASE WHEN  26  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D26',
                    SUM(CASE WHEN  27  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D27',
		    SUM(CASE WHEN  28  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D28',		    
		    SUM(tmp_valtotal)  AS 'TT'
            FROM (tmp_liquidaciones l
					JOIN tmp_liqtarjadetal t on   liq_numliquida = tad_liqnumero and liq_codempresa = tad_codempresa )
                    JOIN liqreportes ON rep_reporteID = " . $pTipo . " AND rep_codrubro = liq_codrubro
                    JOIN liqrubros on rub_codrubro = liq_codrubro
                    LEFT JOIN tmp_ch1 on tad_liqnumero = tmp_ch1.com_numcomp
                    LEFT JOIN tmp_ch2 on tad_liqnumero = tmp_ch2.com_numcomp
            GROUP BY 1, 2, 3,4,5,6 ORDER BY 3,2 ";
    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE LIQUIDACION', true,false);
    return $rsLiq;
}
/** Actions to execute BEFORE the Report.
*   You can access any property / method from ezPdfReport Object using var $rpt and group data from variable $group received as parameters
*   To put any text, line, rectangle, etc into your report, use the object $rpt->pdf and its "ez functions" (see ezPdf manual),
*   be care of functions that don´t move the insertion point to void text overlapping
*   Note: This function DOES NOT AFFECT the top margin, so you can put General Objects, water marks, etc.
*   @access public
*   @param  object      $rpt        Reference to current report object
*   @return void
*/
function before_report(&$rpt){

  }
/** Process the Report Header
*   You can access any property / method from ezPdfReport Object using var $rpt and group data from variable $group received as parameters
*   To put any text, line, rectangle, etc into your report, use the object $rpt->pdf and its "ez functions" (see ezPdf manual),
*   be care of functions that don´t move the insertion point to void text overlapping
*   Note: This function REDEFINES the top margin.
*   @access public
*   @param  object      $rpt        Reference to current report object
*   @return void
*/
function before_header(&$rpt, &$hdr){
//  $slSubTitulo
//  $slCondicion
/*    $ilTitSize=10;  // Tamaño del titulo
    $ilSubSize=8;  // Tamaño del Subtitulo
    $ilConSize=6;  // Tamaño del Texto de Condicion

    $slTitulo="RESUMEN GENERAL DE LIQUIDACIONES\n";

*/    $ilTxtSize=10;  //
//    global $gsTitGeneral;
    $ilLeading=0;  //
    include_once ("RptHeader.inc.php");
//    echo $gsTitGeneral;
//    $rpt->pdf->eztext($gsTitGeneral, 10, array('justification'=>'center', 'leading'=>20));
    }
/** Actions to execute BEFORE the group of column C0.
*   You can access any property / method from ezPdfReport Object using var $rpt and group data from variable $group received as parameters
*   To put any text, line, rectangle, etc into your report, use the object $rpt->pdf and its "ez functions" (see ezPdf manual),
*   be care of functions that don´t move the insertion point to void text overlapping
*   @access public
*   @param  object      $rpt        Reference to current report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_EM(&$rpt, &$group){
    $rpt->pdf->eztext('** ' . $group->currValue, 8, array('justification'=>'center', 'leading'=>20));//        Putting text before group data
//  $group->detail=false;       //                              If you want only resume lines for this group
  }
/*				*/
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$ilNumProces   = fGetParam('pPro', false);
$db = NewADOConnection("mysql");
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
$pQry=fGetParam('pQryLiq', 0);
set_time_limit (0) ;
$gsTitGeneral="";
$rs = fDefineQry($db, $pQry );
$slFontName = fGetParam('pFon', 'Helvetica');   //                          Fonst Name from URL
$ilFontSize = fGetParam('pSiz', 9); //                                      Font Size from url
$slPosition = fGetParam('pPos', "portrait"); //                                      Font Size from url
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
$rep = new ezPdfReport($rs, array(34,21), "landscape", "Helvetica", 6);
//$rep = new ezPdfReport($rs, array(35, 80), $slPosition, $slFontName, $ilFontSize);
$rep->title="CUADRO GENERAL DE LIQUIDACIONES";
$rep->condition=fGetParam('pCond', '');
$rep->subTitle=fGetParam('pCond', '');
$rep->condition=$gsTitGeneral; 
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>10 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition

$rep->margins = array(0.5,1,0.8,0.8);
$rep->colHead = array( 
                        'C0' =>'COD.',
                        'C1' =>'NOMBRE',
						//'EM' =>'EMPRESA',
                        'C2' =>'CAJAS',
                        'D1' =>'FRUTA',
                        'D2' =>'BONIFIC',
                        'D3' =>'COMPENSAC',
                        'D4' =>'EMPAQ PAGDO',			
                        'D5' =>'OTROS INGR.',
                        'D10' =>'EMPAQ. COBR',
                        'D11' =>'RET FUENT',			
                        'D12' =>'ADELANTO',
                        'D13' =>'ANTICIPOS',
                        'D14' =>'PRESTAMOS',			
                        'D15' =>'INTER.PREST',
                        'D16' =>'GTOS ADMIN',
                        //'D20' =>'AGRO QUIM',			
                        'D21' =>'FERTILIZANT',
                        'D22' =>'TRANSPORT',
                        //'D23' =>'FUMIGAC',			
                        'D24' =>'INSUMOS',
                        'D25' =>'TRANS FEREN',
                        'D26' =>'VAL P/LIQ',
			'D27' =>'TCI',
			'D28' =>'OTROS DESC',
			'TT' =>'NETO',
                        'E1'=>'CHEQUE 1',
                        'E2'=>'CHEQUE 2'
                        );
$rep->rptOpt = array('fontSize'=>9, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0, 'rowGap'=>1);

$rep->setDefaultColPro('format', "16:2:,:."); // Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        // Default column type
$rep->columns['C1']->type='C';
$rep->columns['C1']->format=false;
$rep->columns['C2']->type='I';
$rep->columns['C2']->format="7:0::";
$rep->columns['E1']->format="7:0::";
$rep->columns['E2']->format="7:0::";
$rep->columns['C0']->format="7:0::";
//$rep->columns['C19']->zeroes=true;
//$rep->columns['C19']->format="12:2:.:,:-0-";

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['C0']['justification']='left';
$rep->colOpt['C1']['justification']='left';
/*
$rep->setDefaultColOpt('width', 40);
$rep->colOpt['C1']['width']=90;
$rep->colOpt['C2']['width']=30;
*/
$rep->setDefaultColOpt('width', 35);
$rep->colOpt['C0']['width']=25;
$rep->colOpt['C1']['width']=75;
$rep->colOpt['C2']['width']=35;
$rep->colOpt['C3']['width']=55;
$rep->colOpt['C19']['width']=60;

//$rep->addGrp('general');                  // Not required, exist by default
$rep->groups['general']->fontSize=6;        // set font size for this group
$rep->groups['general']->textCol='C1';      // set the column for text at resume line of group
$rep->groups['general']->linesBefore=4;
$rep->addResumeLine('general','S', 'SUMA GENERAL');         // Add a resume line for group 'general' using sums in all columns bt default
$rep->setAggregate('general',0, 'C1','-');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
$rep->setAggregate('general',0, 'E1','-');
$rep->setAggregate('general',0, 'CO','-');
$rep->setAggregate('general',0, 'E2','-');

	
$rep->addGrp('EM');                         // Create a group for column C0
$rep->groups['EM']->fontSize=6;
$rep->groups['EM']->textCol='C1';
$rep->groups['EM']->linesAfter=0;
$rep->groups['EM']->linesBefore=0;
$rep->addResumeLine('EM','S', 'SUMA ');      // Add a resume line for group C0 using sums in all columns bt default
$rep->setAggregate('EM',0, 'C0','-'); // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
$rep->setAggregate('EM',0, 'EM','-'); // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
$rep->setAggregate('EM',0, 'C1','-'); // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
$rep->setAggregate('EM',0, 'C2','-'); // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1 of Group 'CO'

$rep->run();
$rep->view($rep->title, $rep->saveFile("LIQ_RESD_"));
?>



