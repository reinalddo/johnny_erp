<?php
/*
*   CoTrTr_libroban.rpt.php: Reporte de Libro bancos
*   @author     Fausto Astudillo
*   @param      string		pQryCom  Condición de búsqueda
*   @param      string		pFec     Fecha de Corte
*   @param      integer		pAux     Codigo de auxiliar del banco
*   @output     contenido pdf del reporte.
*/
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
    global $con;
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
//
    $alSql[] = "SELECT
                    '' AS 'REG',
                    0  AS 'SEC',
                    '' AS 'TIP',
                    'SALDO ANTERIOR                    ' as 'BEN',
                    '' As 'COM',
                    '' AS 'FTR', '" .
                    $con->con_desde . "' AS 'FCO',
                    det_CodCuenta AS 'CCU',
                    IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'CAU',
                    '' AS 'CUE',
                    0 as 'CHE',
                    0.00  AS 'VDB',
                    0.00  AS 'VCR',
                    sum(det_ValDebito - det_ValCredito+0)  AS 'SAL'
                 FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)
                    LEFT JOIN conpersonas ON (per_CodAuxiliar = com_codreceptor)
                    LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar)
                    LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta)
                 WHERE ". fGetParam('pQryCom', false) . " AND com_feccontab < '" . $con->con_desde . "'
                 GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13
                 HAVING SAL <> 0 OR SAL IS NOT NULL
            UNION
            SELECT
                   com_RegNumero AS 'REG',
                    det_secuencia AS 'SEC',
                    com_TipoComp AS 'TIP',
                    left(concat(IFNULL(com_codreceptor, concat(left(per_Apellidos,20), ' ', left(per_Nombres,12))), ' ', det_glosa),35) as 'BEN',
                    concat(com_TipoComp, '-', com_NumComp) AS 'COM',
                    com_FecTrans AS 'FTR',
                    com_FecContab AS 'FCO',
                    det_CodCuenta AS 'CCU',
                    IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'CAU',
                    cue_Descripcion AS 'CUE',
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
        $alSql[0] .= " ORDER BY 8,9, 11, 7,3, 5";
        }
    else { //                  Reporte Estructurado
//        $alSql[0] .= " ORDER BY det_CodCuenta, det_IDauxiliar, com_TipoComp, com_FecContab, com_NumComp";
        $alSql[0] .= " ORDER BY 8,9, 3,11,7,5";
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
    $rpt->pdf->ezSetDy(-5);
    $rpt->putTextAndLabel($rpt->leftBorder+20, $rpt->pdf->y, $group->lastRec['CCU'] . "   " .$group->lastRec['CUE'] . "     Auxiliar: " .  $group->lastRec['CAU'] , "CUENTA: ", 400, 65);
//    $rpt->pdf->ezSetDy(-10);
//    $rpt->putTextAndLabel($rpt->leftBorder+20, $rpt->pdf->y, $group->lastRec['CAU'] . "   " .$group->lastRec['DES'], "AUXILIAR: ", 400, 65);
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
     echo $errmsg . "<br>(" . $filename . ", linea: " . $linenum . ")";
//   fErrorPage('',$errmsg . "<br>(" . $filename . ", linea: " . $linenum . ")", true, false);
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
$sSql= "select c.con_Idregistro, c.con_codcuenta, c.con_codauxiliar, c.con_feccorte,
               ((select max(p.con_feccorte )
				from conconciliacion p
					where p.con_codcuenta = c.con_codcuenta AND
						p.con_codauxiliar = c.con_codauxiliar AND
						p.con_feccorte < c.con_feccorte) + interval 1 day ) AS 'con_desde'
        from conconciliacion c
        where con_idregistro = " . fGetParam("pID");
$rs = $db->execute($sSql);
if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA CONCILIACION " . $pRNum);
$rs->MoveFirst();
$con = $rs->FetchNextObject(false);
if (is_null($con->con_desde) ||  $con->con_desde == '' || $con->con_desde == 0 ) $con->con_desde = '2004-12-31';
$slQry .= " AND com_feccontab BETWEEN '" . $con->con_desde . "' AND '" . $con->con_feccorte . "' ";
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Times-Roman';
$rep = new ezPdfReport($rs, "letter", "portrait", $slFontName, 8);
$slEmisor = fDBValor($db, "conpersonas", "concat(per_Apellidos, ' ', per_Nombres)", "per_codauxiliar = " .     $slTxt=fGetParam('pAux', "0"));
$rep->title="LIBRO BANCOS '" . strtoupper($slEmisor) . "' CORTADO A " .     $slTxt=fGetParam('pFec', " ");
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>8 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1.5,.2,.5);
$rep->colHead = array(
                        'COM' =>'COMP',
                        'FCO' =>'FECHA',
                        'BEN' =>'BENEFICIARIO',
                        'CHE' =>'CHEQ',
                        'VDB' =>'D E B I T O',
                        'VCR' =>'C R E D I T O',
                        'SAL' =>'S A L D O');
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
$rep->columns['SAL']->type='A';
$rep->columns['SAL']->acumBreaker='CAU'; //             Encera el Acumulador con cada auxiliar

$rep->columns['CCU']->repeat=false;
$rep->columns['CAU']->repeat=false;
$rep->columns['CCU']->format=false;
$rep->columns['CUE']->format=false;
$rep->columns['FCO']->format="yy-mmm-dd";
$rep->columns['CHE']->format="14:0::";
$rep->columns['VDB']->format="14:2:.:,";
$rep->columns['VCR']->format="14:2:.:,";
$rep->columns['SAL']->format="14:2:.:,:-o-";
$rep->columns['SAL']->zeroes=true;

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['CAU']['justification']='right';
$rep->colOpt['VDB']['justification']='right';
$rep->colOpt['VCR']['justification']='right';
$rep->colOpt['CHE']['justification']='right';
$rep->colOpt['SAL']['justification']='right';

$rep->colOpt['COM']['width']=60;
$rep->colOpt['FCO']['width']=55;
$rep->colOpt['BEN']['width']=180;
$rep->colOpt['CHE']['width']=50;
$rep->colOpt['VDB']['width']=70;
$rep->colOpt['VCR']['width']=70;
$rep->colOpt['SAL']['width']=75;

$rep->addGrp('CAU');
  $rep->groups['CAU']->fontSize=10;
    $rep->groups['CAU']->textCol='BEN';
    $rep->addResumeLine('CAU','-', '       SUBTOTAL AUXILIAR :',0);
    $rep->groups['CAU']->linesBefore=1;
    $rep->groups['CAU']->linesAfter=1;
        $rep->setAggregate('CAU',0, 'VDB','S');
        $rep->setAggregate('CAU',0, 'VCR','S');
        $rep->setAggregate('CAU',0, 'SAL','S');
/**/
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
$rep->view($rep->title, $rep->saveFile("CON_LBA_"));
//$rep->preView()
?>
