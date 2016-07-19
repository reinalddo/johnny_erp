<?php
/*
*   CoTrTr_comprob.rpt.php: Impresion de Comprobantes contables
*   @author     Johnny Valencia
*   @param      string		pQryCom  Condición de búsqueda
*   @output     contenido pdf del reporte.
*   @rev    fah 03/03/09    Tamaño de pagina dinamico: para comprobantes con mas de 5 lineas,
*                           genera formato A4, caso contrario A5
*   @rev    fah 27/07/09    Presentar descripcion de cuentas padre y abuelo en cada linea
*   @rev    fah 29/07/09    Acortar textos de cuentas
*/
//error_reporting(E_ALL);
//set_error_handler("repErrorhandler");
include("../LibPhp/ezPdfReport.php");
include("../LibPhp/GenCifras.php");
require("../LibPhp/ComExCCS.php");

/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
//                                                                          
    $alSql[] = "SELECT
                   com_RegNumero AS 'REG',
                    det_secuencia AS 'SEC',
                    com_TipoComp AS 'TIP',
                    com_NumComp AS 'COM',
                    com_FecTrans AS 'FTR',
                    com_FecContab AS 'FCO',
                    com_FecVencim AS 'FVE',
                    com_Emisor AS 'CEM',
                    com_CodReceptor AS 'CRE',
                    com_Receptor AS 'REC',
                    com_Concepto AS 'CON',
                    concat(det_CodCuenta, '   ') AS 'CCU',
                    '----' AS 'ABC',
                    IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'CAU',
                    concat_ws('/ ',ifnull(left(a.cue_descripcion,20),''), ifnull(left(p.cue_descripcion,20),''), ifnull(left(c.cue_Descripcion,25),'******* No existe???')) AS 'CUE',
                    concat(IF(det_idauxiliar <> 0 ,
                                concat(IFNULL( concat(act_descripcion, ' ', ifnull(act_descripcion1,' ')),
                                   concat(per_Apellidos, ' ', ifnull(per_Nombres, ' ')) ),
                            ' :  ' ), ''), det_Glosa )   AS 'DES',
                    det_NumCheque as 'CHE',
                    det_ValDebito  AS 'VDB',
                    det_ValCredito AS 'VCR'
                 FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)
                    LEFT JOIN conpersonas ON (per_CodAuxiliar = det_IdAuxiliar)
                    LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar)
                    LEFT JOIN concuentas c ON (c.cue_codcuenta = det_codcuenta)
                    LEFT JOIN concuentas p ON (p.cue_id = c.cue_padre)
                    LEFT JOIN concuentas a ON (a.cue_id = p.cue_padre)
                    ";                                  //                          #fah27/07/09
    $alSql[0] .= ($pQry ? " WHERE " . $pQry  : " " ) . " ORDER BY 1, 2";

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
function before_group_COM (&$rpt, &$group) {
    global $db;
    global $ilPag;
    global $gfValor;
    global $firstComp;
    if (!$firstComp)  $rpt->pdf->NewPage(); // Salto de pagna antes de cada comprobante
    $firstComp=false;
    $ilTxtSize=$rpt->fontSize; 
    $ilLeading=0;
    $rpt->pdf->y= $rpt->pdf->ez['pageHeight']- $rpt->pdf->ez['topMargin'];
    include ("ComHeader2.inc.php");
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
/**/
}

/**
*   Texto de cabecera para cada cuenta
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_CCU (&$rpt, &$group) {
    global $db;
        $rpt->columns['CAU']->visible = true;
        $rpt->pdf->y -=4;
        $rpt->putText($group->lastRec['CCU'],10,10, false,false, false,$rpt->pdf->ez['leftMargin']);
        $ilX=$rpt->pdf->ez['leftMargin'] + $rpt->colOpt['CAU']['width'] ; // + $rpt->colOpt['CAU']['width'];
        $rpt->pdf->y +=12;
        $rpt->putText($group->lastRec['CUE'], 10,10, false,false, false,$ilX);
        $rpt->pdf->y +=8;
}
/**
*   Al termino de procesar cada comprobante
*/
function after_group_COM (&$rpt, &$group) {
    global $gfValor;
//    $rpt->pdf->setLineStyle(1,'square');
    $x = $rpt->pdf->ez['leftMargin'];
    $y= $rpt->pdf->ez['bottomMargin'];
    $y1 = $y+20;
    $l=80;
    $rpt->pdf->ezrRoundRectangle($x, $y+10, 545, $y+35, 30);
    $x+=10;
    for ($i=0; $i<3; $i++) {
        $rpt->pdf->line($x, $y1, $x+$l , $y1);
        $x+=10+$l;
    }
    $yt=$y+10;
    $x = $rpt->pdf->ez['leftMargin'] + 20;
    $rpt->pdf->addText($x    ,$yt,8,"Emitido Por",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->addText($x+ 95,$yt,8,"Contabilidad",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->addText($x+190,$yt,8,"Gerencia",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->line($x+380, $y1, $x+475 , $y1);
    $rpt->pdf->addText($x+400,$yt,8,"Recibi Conforme",$angle=0,$wordSpaceAdjust=0);

}
function repErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
    fErrorPage('',$errmsg . "<br>(" . $filename . ", linea: " . $linenum . ")", true, false);
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$ilPag = 1;
$slQry   = fGetParam('pQryCom', false);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$giNumLin = fDBValor($db, "concomprobantes JOin condetalle on det_regnumero = com_regnumero", "count(*)",  fGetParam("pQryCom", false));
                    /*"com_tipocomp = '" . fGetParam("com_TipoComp",'-').
                    "' AND com_numcomp= " . fGetParam("com_NumComp",-999) );*/
if ($giNumLin > 5)
    $rep = new ezPdfReport($rs, array(21.5,29), "portrait", $slFontName, 9);
else
    $rep = new ezPdfReport($rs, array(21.5,14), "portrait", $slFontName, 9);
$rep->title=false;
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>8 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.8,1,.5,.5);
$rep->colHead = array(
                        'CAU' =>'CTA/AUX.',
                        'DES' =>'DESCRIPCION',
                        'CHE' =>'# DOC.',
                        'VDB' =>'DEBITO',
                        'VCR' =>'CREDITO');
$xPos=0;
//$rep->rptOpt = array('fontSize'=>10, 'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
//                     'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->rptOpt = array('fontSize'=>8,  'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
                     'showLines'=>0, 'rowGap'=>0, 'maxWidth'=>990,  'xPos'=>-20, 'xOrientation'=>'right');

$rep->printColHead=false;
$rep->printFooter=false;
$rep->setDefaultColPro('format', "14:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->columns['CCU']->type='C';
$rep->columns['CAU']->type='C';
$rep->columns['VDB']->type='N';
$rep->columns['VCR']->type='N';
$rep->columns['CHE']->type='N';

$rep->columns['CCU']->repeat=false;
$rep->columns['CAU']->repeat=false;
$rep->columns['CCU']->format=false;
$rep->columns['CUE']->format=false;
$rep->columns['CHE']->format=("10:0::");

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['CAU']['justification']='right';
$rep->colOpt['VDB']['justification']='right';
$rep->colOpt['VCR']['justification']='right';
$rep->colOpt['CHE']['justification']='right';

$rep->colOpt['CAU']['width']=74;
$rep->colOpt['DES']['width']=285;
$rep->colOpt['VDB']['width']=70;
$rep->colOpt['VCR']['width']=70;
$rep->colOpt['ABC']['width']=1;
$rep->colOpt['CHE']['width']=50;

//$rep->addGrp('general');                           // Not required, exist by default
/*
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='NOM';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'SUMA GENERAL', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
        $rep->setAggregate('general',0, 'SEM','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'PRO','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'VAP','-');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'MAR','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'EMP','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'NOM','-');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'TAR','-');
        $rep->setAggregate('general',0, 'PPR','-');
*/

$rep->addGrp('COM');
    $rep->groups['COM']->fontSize=10;
    $rep->groups['COM']->textCol='DES';
    $rep->addResumeLine('COM','S', 'S U M A N   U.S. Dolares :',0);
    $rep->groups['COM']->linesBefore=0;
        $rep->setAggregate('COM',0, 'CAU','-');
        $rep->setAggregate('COM',0, 'CHE','-');
        $rep->setAggregate('COM',0, 'DES','-');
        $rep->setAggregate('COM',0, 'ABC','-');

$rep->addGrp('CCU');
$gfValor=0;
$firstComp=true;
$rep->run();
$rep->view($rep->title, $rep->saveFile("CON_COM_"));
//$rep->preView()
?>
