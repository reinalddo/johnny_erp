<?php
/*
*   InTrTr_noliquidado.rpt.php: Resumen de Transacciones de Inventario pendientes
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
	$alSql = Array();
	$alSql[]="DROP TABLE IF EXISTS tmp_liquid";
	$alSql[]="CREATE TEMPORARY TABLE  tmp_liquid
				SELECT com_codreceptor as tmp_productor, MAX(com_numproceso) AS tmp_ultliquidacion,
					MAX(com_refoperat) AS tmp_ultsemliquid
					FROM concomprobantes
					WHERE com_tipocomp ='LI'
					GROUP BY 1 ";

    $slSql="SELECT com_tipocomp AS TIPO,
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
             LEFT JOIN genunmedida ON uni_CodUnidad = act_unimedida
			 JOIN tmp_liquid  ON com_codreceptor = tmp_productor AND com_refoperat > tmp_ultsemliquid " ;

    if ($pQry) $slSql .= " WHERE com_tipocomp IN ('EP', 'DV') AND "  . $pQry ;
    $slSql .= " ORDER  BY com_emisor, com_numcomp, com_tipocomp";
	$alSql[]= $slSql;
    $rsLiq= fSQL($db, $alSql);
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
function before_group_BODEG (&$rpt, &$group) {
    global $db;
    $slText = 'BODEGA:     ' . $group->lastRec['CODEM'] . ".     "  . $group->lastRec['BODEG'];
    $rpt->pdf->eztext($slText, 10, array('justification'=>'LEFT', 'leading'=>12));//        Putting text before group data
    }
/** CAbecera de gruop TIPO
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_COMPR (&$rpt, &$group) {
    global $db;
    $slText ='COMPROB   ' .$group->lastRec['TIPO']. " - " . $group->currValue. '          FECHA: ' . $group->lastRec['FECHA'] .
             '         A: ' . $group->lastRec['RECEP'] . "         S:" . $group->lastRec['REFOP'];
    $rpt->pdf->eztext($slText, 8, array('justification'=>'LEFT', 'leading'=>15));//        Putting text before group data
//    $group->resume[0]['resume_text'] = 'SUBTOT. ' . $group->currValue . ": ";
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
$slFontName = 'Courier';
$rep = new ezPdfReport($rs, array(21.5,27.8), "landscape", $slFontName, 10);
$rep->title="DETALLE DE TRANSACCIONES DE INVENTARIO";
//$rep->subTitle="-";
$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0,1,0.5,1.5);
$rep->colHead = array(
                        'SECUE'  => "S.",
                        'CODIT' => "CODIGO",
                        'ITEM'  => "I T E M ",
//                        'CANTI'  => "CANTI",
                        'UNIDA'  => "UNI",
                        'CANTE'  => "CANTID.",
                        'COSTO'  => "COSTO",
                        'VALOR'  => "VALOR",
                        );
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['ITEM']->type="C";
$rep->columns['SECUE']->format="7:0::";
$rep->columns['CODIT']->format="7:0::";
$rep->columns['CANTI']->format="9:2::.";
$rep->columns['COSTO']->format="13:2:.:,";
$rep->columns['VALOR']->format="13:2:.:,";

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['ITEM']['justification']='left';

$rep->setDefaultColOpt('width', 80);
$rep->colOpt['SECUE']['width']=35;
$rep->colOpt['ITEM'] ['width']=160;
$rep->colOpt['UNIDA']['width']=30;
$rep->colOpt['CANTI']['width']=80;
$rep->colOpt['COSTO']['width']=90;
$rep->colOpt['VALOR']['width']=90;

//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='ITEM';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'SUMA GENERAL', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
        $rep->setAggregate('general',0, 'COSTO','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'VALOR','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

$rep->addGrp('BODEG');                         // Create a group for column SEM
    $rep->groups['BODEG']->fontSize=6;
    $rep->groups['BODEG']->textCol='ITEM';

        
$rep->addGrp('TIPO');                         // Create a group for column SEM
    $rep->groups['TIPO']->fontSize=6;
    $rep->groups['TIPO']->textCol='ITEM';
    $rep->addResumeLine('TIPO','-', ' ',0);
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
$rep->run();
$rep->view($rep->title, $rep->saveFile("INV_DET_"));
?>



