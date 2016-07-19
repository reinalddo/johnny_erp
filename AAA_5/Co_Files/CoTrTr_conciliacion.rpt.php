<?php
/*
*   CoTrTr_estadocue.rpt.php: Reporte de Conciliacion
*   @author     Fausto Astudillo
*   @param      string		pQryCom  Condici� de bsqueda
*   @param      string		pFec     Fecha de Corte
*   @param      integer		pAux     Codigo de auxiliar del banco
*   @output     contenido pdf del reporte.
*/
//error_reporting(E_ALL);
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
    $sComun = " FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero)
                    LEFT JOIN conpersonas ON (per_CodAuxiliar = com_codreceptor)
                    LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar)
                    LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta)
                WHERE " . $pQry ;
    $slSelect = "   com_RegNumero AS 'REG',
                    det_secuencia AS 'SEC',
                    com_TipoComp AS 'TIP',
                    left(if(com_codreceptor <>0, concat(left(per_Apellidos,20), ' ', left(IFNULL(per_Nombres,''),20)), det_glosa),45) as 'BEN',
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
                    det_NumCheque as 'CHE', ";

//  Incluir el Saldo Anterior
    $alSql[] = "CREATE TEMPORARY TABLE tmp_movs
                SELECT
                    0 AS 'CLA',
                    0 AS 'REG',
                    0 AS 'SEC',
                    '           ' AS 'TIP',
                    'SALDO SEGUN LIBRO BANCOS                 ' AS 'BEN',
                    '           ' AS 'COM',
                    '           '  AS 'FTR',
                    '           ' AS 'FCO',
                    '           ' AS 'CEM',
                    '           ' AS 'CRE',
                    '           ' AS 'REC',
                    'SALDO SEGUN LIBRO BANCOS ' AS 'CON',
                    concat(det_CodCuenta, '   ') AS 'CCU',
                    IF(det_idauxiliar =0, ' ', concat(det_idauxiliar, '     ')) AS 'CAU',
                    cue_Descripcion AS 'CUE',
                    '           'AS 'DES',
                    '           ' as 'CHE',
                    SUM(0000000000.00)+0000000000.00 AS 'VDB',
                    SUM(det_ValDebito - det_ValCredito)+0000000000.00  AS 'VCR',
                    SUM(det_ValDebito - det_ValCredito)+0000000000.00  AS 'SAL' " .
                    $sComun .
                    " AND com_feccontab <= '" . $con->con_feccorte . "'
                    GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17";
// Movs no Reg en libros
    $alSql[] = "INSERT INTO tmp_movs
                SELECT
                    1 AS 'CLA', " . $slSelect . "
                    det_ValDebito  AS 'VDB',
                    det_ValCredito AS 'VCR',
                    det_ValDebito - det_ValCredito  AS 'SAL' " .
                    $sComun .
                    " AND com_feccontab >'" . $con->con_feccorte . "' " .
                    " AND det_feclibros BETWEEN '2003-01-01' AND '" . $con->con_feccorte . "' " .
		    " AND det_feclibros <> '0000-00-00'" ;
//                    " AND det_feclibros BETWEEN '" . $con->con_desde . "' AND '" . $con->con_feccorte . "' ";
// Valores pendientes de cobro, cambiados de signo
    $alSql[] = "INSERT INTO tmp_movs
                SELECT 2 AS 'CLA', " . $slSelect . "
                    det_ValCredito  AS 'VDB',
                    det_ValDebito   AS 'VCR',
                    det_ValCredito - det_ValDebito  AS 'SAL' " . $sComun .
                    " AND com_feccontab <= '" . $con->con_feccorte . "'
                      AND (det_feclibros  >'" . $con->con_feccorte . "'  OR
	                      det_feclibros = '000-00-00' OR det_feclibros < '2001-01-01' )";

    $slSql = "SELECT * FROM tmp_movs ";

    if (fGetParam("pTip", 'F') == 'F'){ //                  Reporte Cronologico
        $slSql .= " ORDER BY CUE, CAU, CLA, FCO, COM";
        }
    else { //                  Reporte Estructurado
        $slSql .= " ORDER BY CUE, CAU, CLA, TIP, FCO, COM";
    }
    $alSql[] = $slSql;
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
}
/**
*   Texto de cabecera para cada Auxiliar
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_CAU (&$rpt, &$group) {
    global $db;
    $rpt->pdf->ezSetDy(-30);
    $rpt->putTextAndLabel($rpt->leftBorder+20, $rpt->pdf->y, $group->lastRec['CCU'] . "   " .$group->lastRec['CUE'], "CUENTA: ", 400, 65);
    $rpt->pdf->ezSetDy(-10);
    $rpt->putTextAndLabel($rpt->leftBorder+20, $rpt->pdf->y, $group->lastRec['CAU'] . "   " .$group->lastRec['DES'], "AUXILIAR: ", 400, 65);
}
/**
*   Texto de CABECERA para la clase de movimientos
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_CLA (&$rpt, &$group) {
    global $db;
    switch ($group->lastRec['CLA']) {
        case 1:
        case'1':
            $rpt->pdf->ezSetDy(-10);
            $slTxt ='TRANSACCIONES  NO REGISTRADAS EN LIBRO BANCOS';
            $group->resume[0]['resume_text']= '       SUBTOTAL NO REGISTRADAS:';
            break;
        case 2:
        case'2':
            $rpt->pdf->ezSetDy(-10);
            $slTxt ='TRANSACCIONES  PENDIENTES EN EL BANCO';
            $group->resume[0]['resume_text']= '       SUBTOTAL PENDIENTES:';
            break;
        default:
            $slTxt ='';
    }
    if (strlen($slTxt) > 0 ) {
        $rpt->putTextAndLabel($rpt->leftBorder+20, $rpt->pdf->y, $slTxt,'', 400, 65);
        $rpt->pdf->ezSetDy(-2);
    }
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
$sSql= "select c.con_idregistro, c.con_codcuenta as con_codcuenta, c.con_codauxiliar as con_codauxiliar, c.con_feccorte, (select max(p.con_feccorte )
					from conconciliacion p
					where p.con_codcuenta = c.con_codcuenta AND
						p.con_codauxiliar = c.con_codauxiliar AND
						p.con_feccorte < c.con_feccorte) + interval 1 day AS con_desde
        from conconciliacion c
        where con_codcuenta = '" . fGetParam('pCue', 'nn') . "' AND con_codauxiliar = " . fGetParam("pAux", 0). " AND  con_idregistro = " . fGetParam("pID", -1);
$rs = $db->execute($sSql);
$rs->MoveFirst();
$con = $rs->FetchNextObject(false);
if (!$con) fErrorPage('',"NO SE PUDO SELECCIONAR LA CONCILIACION ");
//
if (is_null($con->con_desde) ||  $con->con_desde == '' || $con->con_desde == 0 ) $con->con_desde = '2004-12-31';
$slQry = " det_codcuenta = '" . $con->con_codcuenta . "' AND det_idauxiliar = " . $con->con_codauxiliar;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, "letter", "portrait", $slFontName, 9);
$slEmisor = fDBValor($db, "conpersonas", "concat(per_Apellidos, ' ', per_Nombres)", "per_codauxiliar = " .     $slTxt=fGetParam('pAux', "0"));
$rep->title="CONCILIACION BANCARIA  '" . strtoupper($slEmisor) . "' CORTADO A " .     $slTxt=fGetParam('pFec', " ");
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>8 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1.5,0.1,1);
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
    $rep->groups['CCU']->linesBefore=0;
    $rep->groups['CCU']->linesAfter=1;
        $rep->setAggregate('CCU',0, 'VDB','S');
        $rep->setAggregate('CCU',0, 'VCR','S');
*/
$rep->addGrp('CAU');
  $rep->groups['CAU']->fontSize=10;
    $rep->groups['CAU']->textCol='BEN';
    $rep->addResumeLine('CAU','-', '       SUBTOTAL AUXILIAR :',0);
    $rep->groups['CAU']->linesBefore=0;
    $rep->groups['CAU']->linesAfter=1;
        $rep->setAggregate('CAU',0, 'VDB','S');
        $rep->setAggregate('CAU',0, 'VCR','S');
        $rep->setAggregate('CAU',0, 'SAL','S');

$rep->addGrp('CLA');
  $rep->groups['CLA']->fontSize=10;
    $rep->groups['CLA']->textCol='BEN';
    $rep->addResumeLine('CLA','-', '       SUBTOTAL  :',0);
    $rep->groups['CLA']->linesBefore=0;
    $rep->groups['CLA']->linesAfter=1;
        $rep->setAggregate('CLA',0, 'VDB','S');
        $rep->setAggregate('CLA',0, 'VCR','S');
        $rep->setAggregate('CLA',0, 'SAL','S');


if (fGetParam("pTip", 'F') == 'T') { // Reporte Estructurado
    $rep->addGrp('TIP');
    $rep->groups['TIP']->fontSize=10;
    $rep->groups['TIP']->textCol='BEN';
    $rep->addResumeLine('TIP','-', '       SUBTOTAL  :',0);
    $rep->groups['TIP']->linesBefore=0;
    $rep->groups['TIP']->linesAfter=0;
        $rep->setAggregate('TIP',0, 'VDB','S');
        $rep->setAggregate('TIP',0, 'VCR','S');
}
$gfValor=0;
$firstComp=true;
$rep->run();
$rep->view($rep->title, $rep->saveFile("CON_MOV_"));
//$rep->preView()
?>
