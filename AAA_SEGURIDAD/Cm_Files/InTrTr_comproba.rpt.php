<?php
/*
*   InTrTr_repgen.rpt.php: Resumen de Transacciones de Inventario
*   @author     Fausto Astudillo
*   @param      integer		pQryTar  Condición de búsqueda
*   @output     contenido pdf del reporte.
*/
error_reporting(E_ALL);
include("../LibPhp/ezPdfReport.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){

    $slSql = "SELECT com_tipocomp AS TIPO,
                com_numcomp AS COMPR,
                det_secuencia  AS SECUE,
                com_feccontab AS FECHA,
                com_refoperat AS REFOP,
                com_emisor AS CODEM,
                concat(b.per_Apellidos, ' ', b.per_nombres) as BODEG,
                com_codreceptor AS CODRE,
                concat(p.per_Apellidos, ' ', p.per_nombres) as RECEP,
                det_coditem AS CODIT,
                left(concat(act_descripcion, ' ', act_descripcion1),25) as ITEM,
                det_candespachada AS CANTI,
                det_cantequivale AS CANTE,
                uni_abreviatura AS UNIDA,
                det_costotal AS COSTO,
                det_valtotal AS VALOR
            FROM genclasetran JOIN concomprobantes ON cla_aplicacion = 'IN' AND com_tipoComp = cla_tipoComp
             LEFT JOIN conpersonas b ON b.per_codauxiliar = com_emisor
             LEFT JOIN conpersonas p ON p.per_codauxiliar = com_codreceptor
             LEFT JOIN invdetalle ON det_regnumero = com_regnumero
             LEFT JOIN conactivos ON act_codauxiliar = det_coditem
             LEFT JOIN genunmedida ON uni_CodUnidad = act_unimedida " ;

    if ($pQry) $slSql .= " WHERE "  . $pQry ;
    $slSql .= " ORDER  BY com_emisor, com_numcomp, com_tipocomp";

    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE INVENTARIO', true,false);
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
/*
function before_group_BODEG (&$rpt, &$group) {
    global $db;
    $slText = 'BODEGA:     ' . $group->lastRec['CODEM'] . ".     "  . $group->lastRec['BODEG'];
    $rpt->pdf->eztext($slText, 10, array('justification'=>'LEFT', 'leading'=>12));//        Putting text before group data
    }
*/
/** CAbecera de gruop TIPO
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_COMPR (&$rpt, &$group) {
    global $db;
    global $ilPag;
    global $gfValor;
    global $firstComp;

    if (!$firstComp)  $rpt->pdf->NewPage(); // Salto de pagna antes de cada comprobante
    $firstComp=false;
/*    $ilTxtSize=$rpt->fontSize;
    $ilLeading=0;
    $rpt->pdf->y= $rpt->pdf->ez['pageHeight']- $rpt->pdf->ez['topMargin'];
//    include ("ComHeader2.inc.php");
    $rpt->rptOpt['onlyHeader']=true;
    $rpt->rptOpt['showHeadings']=2;
    $rpt->rptOpt['showLines']=3;
    $alBlankLine=array();
    $alBlankline[]['COM']=' ';
    $rpt->rptOpt['xPos']=$rpt->pdf->ez['leftMargin'];
    $rpt->pdf->ezTable($alBlankLine,$rpt->colHead,'', $rpt->rptOpt);//    computes de table header height
    $rpt->rptOpt['onlyHeader']=false;
    $rpt->rptOpt['showHeadings']=0;
    $rpt->rptOpt['showLines']=0;
 */
    $rpt->pdf->y= $rpt->pdf->ez['pageHeight'] - 20;
    list($slText, $slTxtRec) = fDbvalor($db,  'genclasetran', 'cla_descripcion, cla_txtReceptor', " cla_tipocomp = '" .$group->lastRec['TIPO']. "' " );
    $rpt->pdf->leftMargin = $rpt->leftBorder;
    $slText =$slText . ' # ' .$group->lastRec['TIPO']. " - " . $group->currValue ;
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 400, 10, $slText, 0, 'center');
    $slText = 'FECHA:      ' . $group->lastRec['FECHA'] . "               SEM: " . $group->lastRec['REFOP'];
    $rpt->pdf->y-=12;
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 600, 10, $slText);
    $slText = $slTxtRec . ":   " . $group->lastRec['CODRE'] . ' ' . $group->lastRec['RECEP'] ;
    $rpt->pdf->y-=12;
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 600, 10, $slText);
    $slText = 'BODEGA:     ' . $group->lastRec['CODEM'] . ". "  . $group->lastRec['BODEG'];
    $rpt->pdf->y-=12;
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 400, 10, $slText);
    $rpt->pdf->y -=18;
    $slText= fDbvalor($db,  'concomprobantes', 'com_concepto', " com_tipocomp = '" .$group->lastRec['TIPO']. "' AND com_numcomp = " . $group->lastRec['COMPR'] );
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 400, 10, 'CONCEPTO : ');
    $rpt->putTextWrap($rpt->leftBorder+80, $rpt->pdf->y, 400, 10, $slText);
    $rpt->pdf->y -=3;
//    $group->resume[0]['resume_text'] = 'SUBTOT. ' . $group->currValue . ": ";
    $rpt->rptOpt['onlyHeader']=true;
    $rpt->rptOpt['showHeadings']=2;
    $rpt->rptOpt['showLines']=3;
    $alBlankLine=array();
    $alBlankline[]['COM']=' ';
//    $rpt->rptOpt['xPos']=$rpt->pdf->ez['leftMargin'];
    $rpt->pdf->ezTable($alBlankLine,$rpt->colHead,'', $rpt->rptOpt);//    computes de table header height
    $rpt->rptOpt['onlyHeader']=false;
    $rpt->rptOpt['showHeadings']=0;
    $rpt->rptOpt['showLines']=0;
    $rpt->pdf->y +=8;
/**/

    }
/** PIE  de gruop VAP
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void

function after_group_VAP (&$rpt, &$group) {
    $rpt->pdf->eztext("   ", 8, array('justification'=>'left', 'leading'=>20));//        Putting text before group data
}
*/
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$slQry   = fGetParam('pQryCom', false);
$db =& fConexion();
set_time_limit (0) ;
if ($slQry) $rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,14), "landscape", $slFontName, 10);
$rep->title="";
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0,1.5,0.5,1.5);
$rep->colHead = array(
                        'SECUE'  => "S.",
                        'CODIT' => "CODIGO",
                        'ITEM'  => "I T E M ",
                        'UNIDA'  => "UNI",
                        'CANTE'  => "CANTID.",
//                        'COSTO'  => "COSTO",
                        'VALOR'  => "VALOR",
                        );
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->printColHead=false;
$rep->printFooter=false;
$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['ITEM']->type="C";
$rep->columns['SECUE']->format="7:0::";
$rep->columns['CODIT']->format="7:0::";
$rep->columns['CANTI']->format="9:2::.";
$rep->columns['COSTO']->format="7:2:,:.";
$rep->columns['VALOR']->format="14:2:,:.";

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['SECUE']['justification']='center';
$rep->colOpt['CODIT']['justification']='center';
$rep->colOpt['ITEM']['justification']='left';

$rep->setDefaultColOpt('width', 55);
$rep->colOpt['SECUE']['width']=25;
$rep->colOpt['ITEM'] ['width']=160;
$rep->colOpt['UNIDA']['width']=30;
$rep->colOpt['CANTI']['width']=80;
$rep->colOpt['COSTO']['width']=90;
$rep->colOpt['VALOR']['width']=90;
/*
//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='ITEM';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'SUMA GENERAL', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
        $rep->setAggregate('general',0, 'COSTO','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'VALOR','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
*/
$rep->addGrp('BODEG');                         // Create a group for column SEM
    $rep->groups['BODEG']->fontSize=6;
    $rep->groups['BODEG']->textCol='ITEM';

        
$rep->addGrp('TIPO');                         // Create a group for column SEM
    $rep->groups['TIPO']->fontSize=6;
    $rep->groups['TIPO']->textCol='ITEM';
    $rep->addResumeLine('TIPO','-', ' S U M A ',0);
        $rep->setAggregate('TIPO',0, 'COSTO','S');
        $rep->setAggregate('TIPO',0, 'VALOR','S');

$rep->addGrp('COMPR');                         // Create a group for column SEM
    $rep->groups['COMPR']->fontSize=6;
    $rep->groups['COMPR']->textCol='ITEM';
//    $rep->addResumeLine('COMPR','-', ' ',0);
//        $rep->setAggregate('COMPR',0, 'COSTO','S');
//        $rep->setAggregate('COMPR',0, 'VALOR','S');

$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$firstComp = true;
$rep->run();
$rep->view($rep->title, $rep->saveFile("INV_DET_"));
?>



