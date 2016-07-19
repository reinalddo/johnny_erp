<?php
/*
*   InTrTr_karprod.rpt.php: Kardex de productores detallado
*   @author     Fausto Astudillo
*   @param      integer		pQryTar  Condición de búsqueda
*   @output     contenido pdf del reporte.
*   @todo       Generalizar el reporte para todos
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
   $slSql = "SELECT 
                concat(com_tipocomp, '-', com_numcomp) AS 'COMPR',
                com_feccontab AS 'FECHA',
                com_refoperat AS 'SEMAN',
                com_emisor AS 'CODEM',
                com_codreceptor AS 'CODRE',
                concat(per_Apellidos, ' ', per_nombres) as RECEP,
                det_coditem AS CODIT,
                uni_abreviatura AS UNIDA,
                left(concat(act_descripcion, ' ', act_descripcion1),25) as ITEM,
                if (com_tipocomp = 'EP' , det_cantequivale, 0) AS 'EGR',
                if (com_tipocomp = 'DV' , det_cantequivale * pro_signo, 0) AS 'DEV',
                if (com_tipocomp = 'LE' , det_cantequivale * pro_signo, 0) AS 'EMB',
                if (com_tipocomp = 'LI' and det_cantequivale > 0  , det_cantequivale * pro_signo, 0) AS 'COB',
                if (com_tipocomp = 'LI' and det_cantequivale < 0, det_cantequivale * pro_signo, 0) AS 'PAG',
                (det_cantequivale * pro_signo) AS 'SAL',
                (det_cantequivale * pro_efeacumula) AS 'HIS'
                from invprocesos JOIN concomprobantes ON pro_codproceso = 20 AND com_tipocomp = cla_TipoTransacc
                     JOIN invdetalle ON det_regnumero = com_regnumero
                	 JOIN conactivos ON act_codauxiliar = det_coditem
                	 JOIN conpersonas ON per_codauxiliar = com_codreceptor
                     LEFT JOIN genunmedida ON uni_CodUnidad = det_unimedida
                WHERE (det_cantEquivale <>0 OR det_costotal <>0 ) ";
    if ($pQry) $slSql .= " AND   "  . $pQry ;
    $slSql .= " ORDER  BY RECEP, ITEM, SEMAN, pro_Orden, FECHA";

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
function before_group_RECEP (&$rpt, &$group) {
    global $db;
    $slText = 'PRODUCTOR:' . $group->lastRec['CODRE'] . ".     "  . $group->lastRec['RECEP'];
    $rpt->pdf->eztext($slText, 10, array('justification'=>'LEFT', 'leading'=>12));//        Putting text before group data
    }
/** CAbecera de gruop TIPO
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/

function before_group_SEMAN (&$rpt, &$group) {
    global $db;
//    $slText ='SEMANA   ' .$group->lastRec['SEMAN'];
//    $rpt->pdf->eztext($slText, 8, array('justification'=>'LEFT'));//        Putting text before group data
    $group->resume[0]['resume_text'] = '* SEM / ' . $group->currValue . ": ";
    }

/** CAbecera de gruop TIPO
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function after_group_SEMAN (&$rpt, &$group) {
    global $db;
/**    $slText ='SUBTOT. S ' . substr(number_format($group->sums['SAL'], 2, '.', ','), -10);
    $rpt->pdf->y -= 15;
    $rpt->pdf->addText(440,$rpt->pdf->y,9,$slText,$angle=0,$wordSpaceAdjust=0);
//    $rpt->pdf->eztext($slText, 9, array('justification'=>'LEFT'));//        Putting text before group data
    $group->resume[0]['resume_text'] = 'SUMAN SEM. ' . $group->currValue . ": ";
**/
    }
/** CAbecera de gruop ITEM
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_ITEM (&$rpt, &$group) {
    global $db;
    $slText ='ITEM: ' .$group->lastRec['CODIT'] . '  ' .$group->lastRec['ITEM'];
    $rpt->pdf->eztext($slText, 9, array('justification'=>'LEFT', 'leading'=>15));//        Putting text before group data
    $group->resume[0]['resume_text'] = 'SUBTOT. ' . $group->currValue . ": ";

    }
function after_group_ITEM (&$rpt, &$group) {
//    $slText ='SALDO:   ' . substr(number_format($group->sums['SAL'], 2, '.', ','), -10);
//    $rpt->pdf->y -= 15;
//    $rpt->pdf->addText(440,$rpt->pdf->y,9,$slText,$angle=0,$wordSpaceAdjust=0);
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
$slFontName = 'Courier';
$rep = new ezPdfReport($rs, array(21.5,27.8), "landscape", $slFontName, 8);
$rep->title="KARDEX DE INVENTARIO DE PRODUCTORES ";
//$rep->subTitle="-";
$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1,0.3,0.5);
$rep->colHead = array(
                        'SEMAN'  => "S",
//                        'SECUE'  => "S.",
//                        'CODIT' => "CODIGO",
//                        'ITEM'  => "I T E M ",
//                        'CANTI'  => "CANTI",
                        'COMPR'  => "COMPROB.",
                        'FECHA'  => "FECHA",
                        'UNIDA'  => "UNID",
                        'EGR' => 'EGRESOS',
                        'DEV' => 'DEVOLUC',
                        'EMB' => 'EMBARC.',
                        'COB' => 'COBRADO',
                        'PAG' => 'PAGADO',
//                        'LIQ' => 'LIQUID.'
                        'SAL' => 'SALDO',
                        'HIS' => 'ACUMUL. COBRO-PAGO'
//                        'CANTE'  => "CANTID.",
//                        'COSTO'  => "COSTO",
//                        'VALOR'  => "VALOR",
                        );
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "9:2:.:"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['ITEM']->type="C";
$rep->columns['SAL']->type="A";             // This column is an acumulator
$rep->columns['HIS']->type="A";             // This column is an acumulator
$rep->columns['SAL']->acumBreaker="ITEM";   // That beaks on this group
$rep->columns['HIS']->acumBreaker="ITEM";   // That beaks on this group
$rep->columns['CODIT']->format="10:0::";
$rep->columns['SEMAN']->format="10:0::";
$rep->columns['SAL']->format="10:2:.::-o-";
$rep->columns['HIS']->format="10:2:.::-o-";
$rep->columns['SAL']->zeroes=true;
$rep->columns['HIS']->zeroes=true;
$rep->columns['SEMAN']->repeat=false;
$rep->columns['FECHA']->repeat=false;
$rep->columns['SEMAN']->zeroes=true;

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['ITEM']['justification']='left';
$rep->colOpt['COMPR']['justification']='left';
$rep->colOpt['SEMAN']['justification']='center';

$rep->setDefaultColOpt('width', 55);
$rep->colOpt['FECHA'] ['width']=55;
$rep->colOpt['ITEM'] ['width']=160;
$rep->colOpt['COMPR'] ['width']=70;
$rep->colOpt['UNIDA'] ['width']=30;
$rep->colOpt['SEMAN'] ['width']=20;
//$rep->colOpt['UNIDA']['width']=30;
/**                                                 NO TIENE SENTIDO
//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='COMPR';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'SUMA ', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
        $rep->setAggregate('general',0, 'EGR','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'DEV','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'EMB','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'COB','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'PAG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
**/

$rep->addGrp('RECEP');                         // Create a group for column SEM
    $rep->groups['RECEP']->fontSize=6;
    $rep->groups['RECEP']->textCol='COMPR';
/**
    $rep->addResumeLine('RECEP','-', 'SALDO GENERAL  ',0);
        $rep->setAggregate('RECEP',0, 'EGR','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('RECEP',0, 'DEV','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('RECEP',0, 'EMB','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('RECEP',0, 'COB','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('RECEP',0, 'PAG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
**/
$rep->addGrp('ITEM');                         // Create a group for column SEM
    $rep->groups['ITEM']->fontSize=6;
    $rep->groups['ITEM']->textCol='COMPR';
    $rep->addResumeLine('ITEM','-', 'SUMA GENERAL  ',0);
        $rep->setAggregate('ITEM',0, 'EGR','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('ITEM',0, 'DEV','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('ITEM',0, 'EMB','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('ITEM',0, 'COB','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('ITEM',0, 'PAG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

$rep->addGrp('SEMAN');                         // Create a group for column SEM
    $rep->groups['SEMAN']->fontSize=6;
    $rep->groups['SEMAN']->textCol='COMPR';
    $rep->addResumeLine('SEMAN','-', '  ',0);
        $rep->setAggregate('SEMAN',0, 'EGR','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('SEMAN',0, 'DEV','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('SEMAN',0, 'EMB','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('SEMAN',0, 'COB','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('SEMAN',0, 'PAG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'



$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$rep->run();
$rep->view($rep->title, $rep->saveFile("KDX_PRO_"));
?>



