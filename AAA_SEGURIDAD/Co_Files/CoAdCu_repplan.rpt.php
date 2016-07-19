<?php
/*
*   CoAdCu_reppla.php: plan de Cuentas
*   @author     Fausto Astudillo
*   @param      integer		pQryPla  Condición de búsqueda
*   @output     contenido pdf del reporte.
*/
//error_reporting(E_ALL);
include("../LibPhp/ezPdfReport.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){
	$slSql ="SELECT  cue_codcuenta AS CUE,
                     left(cue_codcuenta, 1) AS GRU,
                     concat(repeat(' - ',cue_posicion-1), cue_descripcion) AS DES,
		             left(par_descripcion,20) AS AUX,
		             IF(cue_tipmovim=0, 'X',' ') AS MOV,
                     IF(cue_estado=0, 'X',' ') AS EST
    	        FROM concuentas  JOIN genparametros on par_secuencia =  cue_TipAuxiliar
                WHERE cue_ID > 1 and par_categoria = 13 ";

    if ($pQry) $slSql .= " AND  "  . $pQry ;
    $slSql .= " ORDER BY cue_codcuenta " ;

    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE EJECUTO LA CONSULTA A LA BASE DE DATOS', true,false);
    return $rsLiq;
}
/** Process the Report Header
*   You can access any property / method from ezPdfReport Object using var $rpt and group data from variable $group received as parameters
*   To put any text, line, rectangle, etc into your report, use the object $rpt->pdf and its "ez functions" (see ezPdf manual),
*   be care of functions that don´t move the insertion point to void text overlapping
*   Note: This function REDEFINES the top margin.
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
/** CAbecera de gruop SEM
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_GRU (&$rpt, &$group) {
    global $db;
    $rpt->pdf->eztext('-o-', 8, array('justification'=>'center', 'leading'=>15));//        Putting text before group data
    }

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$slQry   = fGetParam('pQryCta', false);

$db = NewADOConnection("mysql");
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,27.8), 'landscape', $slFontName, 8);
$rep->title="PLAN DE CUENTAS";
//$rep->subTitle="-";
$rep->condition=fGetParam('pCond', 0);
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1,3.5,1.5);
$rep->colHead = array(
                        'CUE' =>'COD. CUENTA',
                        'DES' =>'DESCRIPCION',
                        'AUX' =>'TIPO DE AUXILIAR',
                        'MOV' =>'MOVIM.',
                        'EST' =>'ESTADO',
                        );
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['CUE']->type='C';
$rep->columns['DES']->type='C';
$rep->columns['AUX']->type='C';
$rep->columns['MOV']->type='C';
$rep->columns['EST']->type='C';

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['CUE']['justification']='left';
$rep->colOpt['DES']['justification']='left';
$rep->colOpt['AUX']['justification']='center';
$rep->colOpt['MOV']['justification']='center';
$rep->colOpt['EST']['justification']='center';

$rep->setDefaultColOpt('width', 80);
$rep->colOpt['CUE']['width']= 80;
$rep->colOpt['DES']['width']=200;
$rep->colOpt['AUX']['width']=100;
$rep->colOpt['MOV']['width']= 80;
$rep->colOpt['EST']['width']=60;

$rep->addGrp('GRU');
    $rep->groups['GRU']->fontSize=6;
    $rep->groups['GRU']->textCol='DES';
    $rep->groups['GRU']->linesAfter=1;
    $rep->groups['GRU']->linesBefore=0;

$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$rep->run();
$rep->view($rep->title, $rep->saveFile("C_PLCTA"));
?>



