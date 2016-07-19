<?php
/*
*   CoTrTr_estadocue.rpt.php: Reporte de Estado Cuenta
*   @author     Fausto Astudillo
*   @param      string		pQryCom  Condición de búsqueda
*   @param      string		pFec     Fecha de Corte
*   @param      integer		pAux     Codigo de auxiliar del banco
*   @output     contenido pdf del reporte.
*/
error_reporting(E_ALL);
//set_error_handler("repErrorhandler");
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
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
//
    $alSql[] = "SELECT
                   com_RegNumero AS 'REG',
                    det_secuencia AS 'SEC',
                    com_TipoComp AS 'TIP',
                    IF(com_codreceptor > 0, concat(left(per_Apellidos,22), ' ', left(per_Nombres,12)), LEFT(det_glosa,38)) as 'BEN',
                    concat(com_TipoComp, '-', com_NumComp) AS 'COM',
                    com_FecTrans AS 'FTR',
                    com_FecContab AS 'FCO',
                    com_Emisor AS 'CEM',
                    com_CodReceptor AS 'CRE',
                    com_Receptor AS 'REC',
                    left(com_Concepto,25) AS 'CON',
                    concat(det_CodCuenta, '   ') AS 'CCU',
                    IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'CAU',
                    cue_Descripcion AS 'CUE',
                    concat(IF(det_idauxiliar <> 0 ,
                                concat(IFNULL( concat(act_descripcion, ' ', ifnull(act_descripcion1,' ')),
                                   concat(per_Apellidos, ' ', ifnull(per_Nombres, ' ')) )), '') )   AS 'DES',
                    det_NumCheque as 'CHE',
                    det_ValDebito  AS 'VDB',
                    det_ValCredito AS 'VCR',
                    det_ValDebito - det_ValCredito  AS 'SAL'
                 FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)
                    LEFT JOIN conpersonas ON (per_CodAuxiliar = com_codreceptor)
                    LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar)
                    LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta) ";

    $alSql[0] .= ($pQry ? " WHERE " . $pQry  : " " );
    if (fGetParam("pTip", 'F') == 'F'){ //                  Reporte Cronologico
        $alSql[0] .= " ORDER BY det_CodCuenta, det_IDauxiliar, det_numcheque, com_feccontab, det_tipocomp, det_numcomp";
        }
    else { //                  Reporte Estructurado
        $alSql[0] .= " ORDER BY det_CodCuenta, det_IDauxiliar, com_TipoComp, det_numcheque, com_FecContab, com_NumComp";
    }

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

function before_group_CCU (&$rpt, &$group) {
    global $db;
        $rpt->columns['CAU']->visible = true;
        $rpt->pdf->y -=4;
        $ilX=$ilX=$rpt->leftBorder + 20;
        $rpt->putText("CUENTA: " . $group->lastRec['CCU'] . "  " . $group->lastRec['CUE'] ,10,10, false,false, false,$ilX);
//        $rpt->pdf->y +=10;
}*/
/**
*   Texto de cabecera para cada Auxiliar
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_CAU (&$rpt, &$group) {
    global $db;
    global $slEmisor;
    $rpt->pdf->ezSetDy(-30);
    $rpt->putTextAndLabel($rpt->leftBorder+20, $rpt->pdf->y, $group->lastRec['CCU'] . "   " .$group->lastRec['CUE'], "CUENTA: ", 400, 65);
    $rpt->pdf->ezSetDy(-10);
    $rpt->putTextAndLabel($rpt->leftBorder+20, $rpt->pdf->y, $group->lastRec['CAU'] . "   " .$slEmisor, "AUXILIAR: ", 400, 65);
}
/**
*   Texto de pie para cada Auxiliar
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function after_group_CAU (&$rpt, &$group) {
    global $db;
    $slTxt = number_format($group->sums['SAL'], 2, '.', ',');
    $slTxt = str_pad($slTxt, 15, " ", STR_PAD_LEFT);
//    $slTxt =$group->sums['SAL'];
    $rpt->putTextAndLabel($rpt->leftBorder+458, $rpt->pdf->y, $slTxt , " SALDO" , 400, 65);
}
/**
*   Al termino de procesar cada productor
*/
function after_group_COM (&$rpt, &$group) {
    global $gfValor;
//    $rpt->pdf->setLineStyle(1,'square');
    $x = $rpt->pdf->ez['leftMargin'];
    $y1 = 40;
    $l=80;
    $rpt->pdf->ezrRoundRectangle($x, 25, 545, 50, 30);
    $x+=10;
    for ($i=0; $i<3; $i++) {
        $rpt->pdf->line($x, $y1, $x+$l , $y1);
        $x+=10+$l;
    }
    $x = $rpt->pdf->ez['leftMargin'] + 20;
    $rpt->pdf->addText($x,30,8,"Emitido Por",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->addText($x+95,30,8,"Contabilidad",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->addText($x+190,30,8,"Gerencia",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->line($x+380, $y1, $x+475 , $y1);
    $rpt->pdf->addText($x+400,30,8,"Recibi Conforme",$angle=0,$wordSpaceAdjust=0);

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
/*
determinar la fecha anterior a una conciliacion                 @todo: manejar saldo anterior
(el dia siguienete de la conciliacion inmediatamente anterior)
*/
if (fGetParam("pID", false) == false ) die("NO SE DEFINIO LA CONCILIACION ");
$sSql= "select c.con_Idregistro, c.con_codcuenta as con_codcuenta, c.con_codauxiliar as con_codauxiliar, c.con_feccorte, (select max(p.con_feccorte )
					from conconciliacion p
					where p.con_codcuenta = c.con_codcuenta AND
						p.con_codauxiliar = c.con_codauxiliar AND
						p.con_feccorte < c.con_feccorte) + interval 1 day AS con_desde
        from conconciliacion c
        where con_codcuenta = '" . fGetParam('pCue', 'nn') . "' AND con_codauxiliar = " . fGetParam("pAux", 0). " AND  con_idregistro = " . fGetParam("pID", 0) ;
$rs = $db->execute($sSql);
$rs->MoveFirst();
$con = $rs->FetchNextObject(false);
if (!$con) fErrorPage('',"NO SE PUDO SELECCIONAR LA CONCILIACION ");
//
if (is_null($con->con_desde) ||  $con->con_desde == '' || $con->con_desde == 0 ) $con->con_desde = '2004-12-31';
$slQry .= " AND det_fecLibros BETWEEN '" . $con->con_desde . "' AND '" . $con->con_feccorte . "' ";
// echo $slQry;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, "letter", "portrait", $slFontName, 9);
$slEmisor = fDBValor($db, "conpersonas", "concat(per_Apellidos, ' ', per_Nombres)", "per_codauxiliar = " .     $slTxt=fGetParam('pAux', "0"));
$rep->title="EDO. CUENTA '" . strtoupper($slEmisor) . "' CORTADO A " .     $slTxt=fGetParam('pFec', " ");
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>8 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1,.2,.5);
$rep->colHead = array(
                        'COM' =>'COMP',
                        'FCO' =>'FECHA',
                        'BEN' =>'BENEFICIARIO',
                        'CHE' =>'CHEQ',
                        'VDB' =>'D E B I T O',
                        'VCR' =>'C R E D I T O');
$xPos=$rep->pdf->ez['leftMargin'];
//$rep->rptOpt = array('fontSize'=>10, 'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
//                     'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->rptOpt = array('fontSize'=>8,  'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
                     'showLines'=>0, 'rowGap'=>0, 'maxWidth'=>990,  'xPos'=>$xPos, 'xOrientation'=>'right');
//
$rep->printColHead=true;
$rep->setDefaultColPro('format', false); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->columns['CCU']->type='C';
$rep->columns['CAU']->type='C';
$rep->columns['CUE']->type='C';
$rep->columns['FCO']->type='C';
$rep->columns['VDB']->type='N';
$rep->columns['VCR']->type='N';
$rep->columns['CHE']->type='N';

$rep->columns['CCU']->repeat=false;
$rep->columns['CAU']->repeat=false;
$rep->columns['CCU']->format=false;
$rep->columns['CUE']->format=false;
$rep->columns['FCO']->format="yy-mmm-dd";
$rep->columns['CHE']->format="14:0::";
$rep->columns['VDB']->format="14:2:,:.";
$rep->columns['VCR']->format="14:2:,:.";

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['CAU']['justification']='right';
$rep->colOpt['VDB']['justification']='right';
$rep->colOpt['VCR']['justification']='right';
$rep->colOpt['CHE']['justification']='right';

$rep->colOpt['COM']['width']=65;
$rep->colOpt['FCO']['width']=65;
$rep->colOpt['BEN']['width']=200;
$rep->colOpt['CHE']['width']=60;
$rep->colOpt['VDB']['width']=85;
$rep->colOpt['VCR']['width']=85;

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

/*
$rep->addGrp('CCU');
    $rep->groups['CCU']->fontSize=10;
    $rep->groups['CCU']->textCol='BEN';
    $rep->addResumeLine('CCU','-', '       SUBTOTAL CUENTA :',0);
    $rep->groups['CCU']->linesBefore=1;
    $rep->groups['CCU']->linesAfter=1;
        $rep->setAggregate('CCU',0, 'VDB','S');
        $rep->setAggregate('CCU',0, 'VCR','S');
*/
$rep->addGrp('CAU');
  $rep->groups['CAU']->fontSize=10;
    $rep->groups['CAU']->textCol='BEN';
    $rep->addResumeLine('CAU','-', '       SUBTOTAL AUXILIAR :',0);
    $rep->groups['CAU']->linesBefore=1;
    $rep->groups['CAU']->linesAfter=1;
        $rep->setAggregate('CAU',0, 'VDB','S');
        $rep->setAggregate('CAU',0, 'VCR','S');
        $rep->setAggregate('CAU',0, 'SAL','S');

if (fGetParam("pTip", 'F') == 'T') { // Reporte Estructurado
    $rep->addGrp('TIP');
    $rep->groups['TIP']->fontSize=10;
    $rep->groups['TIP']->textCol='BEN';
    $rep->addResumeLine('TIP','-', '       SUBTOTAL  :',0);
    $rep->groups['TIP']->linesBefore=1;
    $rep->groups['TIP']->linesAfter=1;
        $rep->setAggregate('TIP',0, 'VDB','S');
        $rep->setAggregate('TIP',0, 'VCR','S');
}
$gfValor=0;
$firstComp=true;
$rep->run();
$rep->view($rep->title, $rep->saveFile("CON_EDO_"));
//$rep->preView()
?>
