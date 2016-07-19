<?php
/*
*   CoTrTr_deudor.rpt.php: Reporte de Deudores
*   @author     Fausto Astudillo
*   @param      string		pQryCom  Condición de búsqueda
*   @output     contenido pdf del reporte.
*/
error_reporting(E_ALL);
//set_error_handler("repErrorhandler");
//include("GenUti.inc.php");
include("../LibPhp/ezPdfReport.php");
//include("../LibPhp/GenCifras.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){
    $alSql = Array();
    $slCuentas = fDBValor( $db, "liqrubros", "group_concat(distinct   rub_ctaOrigen  separator ', ')",
			    "rub_indDBCR = -1 and rub_ctaOrigen is not null"   );
    $alSql[] = "SELECT
            ifNULL(aso_grupo,20) AS 'GRU',
            det_idauxiliar AS 'COD',
            LEFT(concat(left(per_apellidos, 25 - length(left(per_nombres,12))) , ' ' , per_nombres), 25) as NOM,
            SUM(CASE WHEN  det_codcuenta in ('10030601')  THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'PRE',
            SUM(CASE WHEN  det_codcuenta in ('10030602') THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'ANT',
            SUM(CASE WHEN  det_codcuenta in ('10030604') THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'FER',
            SUM(CASE WHEN  det_codcuenta in ('10030605') THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'AGR',
            SUM(CASE WHEN  det_codcuenta in ('10030606') THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'FUM',
            SUM(CASE WHEN  det_codcuenta in ('10030607') THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'INS',
            SUM(CASE WHEN  det_codcuenta in ('10030610') THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'VPL',
            SUM(CASE WHEN  det_codcuenta in ('10030611') THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'INT',
            SUM(CASE WHEN  det_codcuenta in ('10030612') THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'TCI',
            SUM(CASE WHEN  det_codcuenta in ('10030613') THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'TRA',
	    SUM(CASE WHEN  det_codcuenta in ('10030613') THEN det_valdebito - det_valcredito ELSE 0 END)  AS 'OTR',
            SUM(det_valdebito - det_valcredito) as 'TOT'
            FROM concomprobantes join condetalle ON det_regnumero = com_regnumero
            	join conpersonas on per_codauxiliar = det_idauxiliar
            	left join conasociaciones ON aso_codauxiliar = per_codauxiliar
            WHERE det_codcuenta IN ($slCuentas)
            AND COM_FECCONTAB <= '" . $pQry . "'
            GROUP by 1, 2, 3
            HAVING (ANT <>0 OR PRE <> 0 OR FUM <> 0 OR TOT <> 0 OR INS <> 0 OR VPL <> 0 OR TCI <> 0 OR TRA <> 0 OR OTR <> 0)
            ORDER BY 1, 3
        ";
    $rs= fSQL($db, $alSql);
    if (!$rs) die("NO SE EJECUTo LA CONSULTA: " . $alSql[0]);
    return $rs;
}
/** CAbecera 
*   @access public
*   @param  object      $rpt        Reference to current report object
*   @param  object      $hdr        Reference to current header report object
*   @return void
*/
function before_header(&$rpt, &$hdr){
    $ilTxtSize=10;  //
    $ilLeading=0;  //
    include_once ("RptHeader.inc.php");
  }
/**
*   Texto acbecera 
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_GRU (&$rpt, &$group) {
    global $db;
    global $ilPag;
    global $gfValor;
    global $firstComp;
    if ($group->lastRec['GRU'] == 10)
        $rpt->putText("PRODUCTORES ACTIVOS", 10);
    else
        $rpt->putText("PRODUCTORES INACTIVOS", 10);
/*
    $firstComp=false;
    $ilTxtSize=$rpt->fontSize; 
    $ilLeading=0;
    $rpt->pdf->y= $rpt->pdf->ez['pageHeight']- $rpt->pdf->ez['topMargin'];
//    include ("ComHeader2.inc.php");
    $rpt->rptOpt['onlyHeader']=true;
    $rpt->rptOpt['showHeadings']=2;
    $rpt->rptOpt['showLines']=3;
    $alBlankLine=array();
    $rpt->rptOpt['xPos']=$rpt->pdf->ez['leftMargin'];
    $rpt->pdf->ezTable($alBlankLine,$rpt->colHead,'', $rpt->rptOpt);//    computes de table header height
    $rpt->rptOpt['onlyHeader']=false;
    $rpt->rptOpt['showHeadings']=0;
    $rpt->rptOpt['showLines']=0;
/**/
}

function repErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
    fErrorPage('',$errmsg . "<br>(" . $filename . ", linea: " . $linenum . ")", true, false);
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$ilPag = 1;
$slQry   = fGetParam('pFecha', date("Y-m-d"));
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
$slFontName = 'Courier';
$rep = new ezPdfReport($rs, "letter", "landscape", $slFontName, 9);
$rep->title="LISTADO DE DEUDORES";
$rep->subTitle="CORTE A " . $slQry;
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>8 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.0,1,0,.5);
$rep->colHead = array(
                        'COD' =>'CODIGO',
                        'NOM' =>'NOMBRE',
                        'ANT' =>'ANTI CIPO',
                        'PRE' =>'PREST AMOS',
                        'INT' =>'INTE RESES',
                        'AGR' =>'AGRO QUIM',
                        'FUM' =>'FUMI GACION',
                        'INS' =>'INSU MOS',
                        'FER' =>'FERTI LIZ',
                        'VPL' =>'V. POR LIQ',
                        'TCI' =>'TCI',
			'OTR' =>'OTROS DEBIT',
                        'TOT' =>'TOTAL'
                        );
$xPos=$rep->pdf->ez['leftMargin'];
//$rep->rptOpt = array('fontSize'=>10, 'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
//                     'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->rptOpt = array('fontSize'=>7,  'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
                     'showLines'=>0, 'rowGap'=>0, 'maxWidth'=>990,  'xPos'=>0, 'xOrientation'=>'right');

$rep->printColHead=true;
$rep->setDefaultColPro('type', "N"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->columns['COD']->type='U';
$rep->columns['NOM']->type='U';

$rep->setDefaultColPro('format', "16:2:.:,");
$rep->columns['COD']->format="6:0:.:,";
$rep->columns['NOM']->format="U";
$rep->columns['ANT']->format="16:2:.:,";
$rep->columns['PRE']->format="16:2:.:,";
$rep->columns['INT']->format="16:2:,:.";
$rep->columns['AGR']->format="16:2:.:,";
$rep->columns['FUM']->format="16:2:.:,";
$rep->columns['OTR']->format="16:2:.:,";
$rep->columns['TOT']->format="16:2:.:,";

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['NOM']['justification']='left';
$rep->colOpt['COD']['justification']='left';
$rep->setDefaultColOpt('width', 50);
$rep->colOpt['COD']['width']=40;
$rep->colOpt['NOM']['width']=150;
$rep->colOpt['TOT']['width']=70;

//$rep->addGrp('general');                           // Not required, exist by default
/**/
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='NOM';          // set the column for text at resume line of group
    $rep->addResumeLine('general','S', 'SUMA ', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=2;
        $rep->setAggregate('general',0, 'NOM','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1

$rep->addGrp('GRU');
    $rep->groups['GRU']->fontSize=10;
    $rep->groups['GRU']->textCol='NOM';
    $rep->addResumeLine('GRU','S', '       SUBTOTAL :',0);
    $rep->groups['GRU']->linesBefore=1;
    $rep->groups['GRU']->linesAfter=1;
        $rep->setAggregate('GRU',0, 'COD','S');
        $rep->setAggregate('GRU',0, 'NOM','S');
/*
$rep->addGrp('CAU');
  $rep->groups['CAU']->fontSize=10;
    $rep->groups['CAU']->textCol='GLO';
    $rep->addResumeLine('CAU','-', '       SUBTOTAL AUXILIAR :',0);
    $rep->groups['CAU']->linesBefore=1;
    $rep->groups['CAU']->linesAfter=1;
        $rep->setAggregate('CAU',0, 'VDB','S');
        $rep->setAggregate('CAU',0, 'VCR','S');
        $rep->setAggregate('CAU',0, 'SAL','S');
*/
$gfValor=0;
$firstComp=true;
$rep->run();
$rep->view($rep->title, $rep->saveFile("CON_MOV_"));
//$rep->preView()
?>
