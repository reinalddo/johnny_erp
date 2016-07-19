<?php
/*
*   InTrTr_karprodsemsum_deufis.rpt.php: Resumen de Deudores de carton / fisico
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
	global $gfFech;
	global $gsCodi;
    $slSql = "SELECT 
                com_codreceptor AS CODRE,
                concat(p.per_Apellidos, ' ', p.per_nombres) as RECEP,
                SUM(det_costotal * cla_claTransaccion * (-1))  AS COSTO,
                SUM(det_valtotal  * cla_claTransaccion * (-1)) AS VALOR
            FROM genclasetran JOIN concomprobantes ON cla_aplicacion = 'IN' AND com_tipoComp = cla_tipoComp
             LEFT JOIN conpersonas b ON b.per_codauxiliar = com_emisor
             LEFT JOIN conpersonas p ON p.per_codauxiliar = com_codreceptor
             LEFT JOIN invdetalle ON det_regnumero = com_regnumero
             LEFT JOIN conactivos ON act_codauxiliar = det_coditem
             LEFT JOIN conperiodos ON per_aplicacion = 'LI' and per_numperiodo = com_numproceso
              JOIN genunmedida ON uni_CodUnidad = act_unimedida
			 WHERE com_tipocomp IN ('EP', 'DV')  AND 
			 	  ((com_feccontab  <= '$gfFech' and com_numproceso <= 0  ) OR
		     	   (com_feccontab  <= '$gfFech' AND per_fecFinal > '$gfFech'))
			GROUP BY 1,2" ;

//    if ($pQry) $slSql .= " AND com_refoperat <= 34 and (com_numproceso > 34 OR com_numproceso < 1"  . $pQry ;
    $slSql .= (strlen($pQry) > 2)?  " AND " . $pQry : "" ;
    $slSql .=  (strlen($gsCodi) > 2)? " AND com_codreceptor = " . $gsCodi : "" ;
    $slSql .= " ORDER BY RECEP " ;
    $rsLiq = $db->Execute($slSql);
    IF (!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE INVENTARIO', true,false);
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
**/
function before_header(&$rpt, &$hdr){
    $ilTxtSize=10;  //
    $ilLeading=0;  //
    include_once ("RptHeader.inc.php");
  }
/** CAbecera de gruop COMPR
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
/** CAbecera de gruop PRODU
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
**/
function before_group_RECEP (&$rpt, &$group) {
    global $db;
    $slText =$group->lastRec['CODRE'] .  " " .$group->lastRec['RECEP'];
    $rpt->pdf->eztext($slText, 10, array('left'=>60, 'justification'=>'LEFT', 'leading'=>15));//        Putting text before group data
    $group->resume[0]['resume_text'] = 'SUBTOT. '  . ": ";
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$slQry   = fGetParam('pQryCom', false);
$gfFech  = fGetParam('pFecI', false);
$gsCodi  = fGetParam('com_CodReceptor', false);
echo "CODIGO: " . $gsCodi;
$db =& fConexion();
set_time_limit (0) ;
$rs =& fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,27.8), "landscape", $slFontName, 8);
$rep->title="RESUMEN DE INVENTARIO NO COBRADO";
$rep->subTitle="CORTE A " . $gfFech;
$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0,1,1,0.7);
$rep->colHead = array(
                        'CODRE'  => "COD.",
                        'RECEP'  => "NOMBRE",
                        'COSTO'  => "COSTO"
//                        'VALOR'  => "VALOR",
                        );
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['CODRE']->type="C";
$rep->columns['RECEP']->type="C";
$rep->columns['COSTO']->type="N";
$rep->columns['COSTO']->format="13:2:.:,";
$rep->columns['VALOR']->format="13:2:.:,";

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['CODRE']['justification']='left';
$rep->colOpt['RECEP']['justification']='left';

$rep->setDefaultColOpt('width', 80);
$rep->colOpt['CODRE']['width']=70;
$rep->colOpt['RECEP']['width']=130;
$rep->colOpt['COSTO']['width']=60;

//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='RECEP';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'SUMA GENERAL', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
        $rep->setAggregate('general',0, 'COSTO','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'VALOR','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

          
$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$rep->run();
$rep->view($rep->title, $rep->saveFile("INV_SLPRR_"));
?>



