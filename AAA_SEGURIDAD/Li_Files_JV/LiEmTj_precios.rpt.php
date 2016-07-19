<?php
/*
*   LiliTj_precios.rpt.php: Resumen de precios al productor
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

    $slSql = "SELECT
                if((tad_valunitario - tad_difunitario = 0),1,2) AS GRU,
                UCASE(LEFT(concat(left(per_apellidos, 30 - length(left(per_nombres,12))) , ' ' , per_nombres), 25))  AS NOM,
                concat(buq_abreviatura, '-', emb_numviaje)  AS VAP,
                caj_abreviatura AS  EMP,
                tad_valunitario - tad_difunitario as PPR,
                tad_valunitario AS POF,
                tad_difunitario * (-1) AS ADE,
			    sum(tad_cantrecibida) AS REC,
                sum(tad_cantrechazada) AS RCH,
                sum(tad_cantcaidas) AS CAI,
                sum((tad_cantrecibida - tad_cantrechazada  )) AS CAJ,
                sum(ROUND((tad_cantrecibida - tad_cantrechazada  )  * (tad_valunitario - tad_difunitario),2)) as VAL,
                sum(ROUND((tad_cantrecibida - tad_cantrechazada  )  * tad_difunitario * (-1), 2)) as VAD,
                sum(ROUND((tad_cantrecibida - tad_cantrechazada  )  * tad_valunitario,2)) as VOF
             FROM (((((liqtarjadetal JOIN liqtarjacabec ON tar_NUmTarja = tad_NumTarja)
                JOIN conactivos ON act_codauxiliar = tad_codproducto)
                JOIN liqembarques ON emb_RefOperativa =  tac_refOperativa)
                JOIN conpersonas on per_codauxiliar = tac_embarcador)
                JOIN liqbuques ON buq_codBuque = emb_codvapor)
                JOIN liqcajas ON caj_codcaja = tad_codcaja,
				genparametros
			WHERE par_clave = 'IMARCA' AND par_secuencia = tad_codmarca "
             ;
/*              tad1_valunitario as PRE,
                if (tad_difunitario >= 0, tad_difunitario, 0) as DIF,
                if (tad_difunitario < 0, tad_difunitario * (-1), 0) as BON,
*/
    if ($pQry) $slSql .= " AND "  . $pQry ;
    $slSql .= " GROUP BY 1,2,3,4,5,6,7  ORDER  BY 1,2,3,4,5";

    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE LIQUIDACION', true,false);
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

/** CAbecera de gruop PRO
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/

function before_group_PRO (&$rpt, &$group) {
    global $db;
    $slProduc = "";
    if (isset($group->currValue) && !is_null($group->currValue)) {        // si existe un codigo de producto
        $slProduc .= fDBValor($db, 'conactivos', "concat(act_descripcion, ' - ' ,  act_descripcion1) ", 'act_codauxiliar = ' . $group->currValue );
    }
    else $slProduc .= " -- ";
    $rpt->pdf->eztext("\n" . strtoupper($slProduc) , 8, array('justification'=>'center', 'leading'=>12));//        Putting text before group data
    $group->resume[0]['resume_text'] = 'SUBTOT. ' . $slProduc;
    }

/** CAbecera de gruop GRU
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_GRU (&$rpt, &$group) {
    global $db;
    $slProduc = "";
    if (isset($group->currValue) && $group->currValue == 1) $slTexto = "TARJAS SIN PRECIO";
    else $slTexto = "TARJAS VALORIZADAS";
    $rpt->pdf->eztext("\n" . strtoupper($slTexto) , 8, array('justification'=>'center', 'leading'=>12));//        Putting text before group data
    $group->resume[0]['resume_text'] = 'SUBTOT. ' . $slTexto;
    }

function report_footer (&$rpt, &$group) {
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
    $rpt->pdf->addText($x    ,$yt,8,"Elaborado Por",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->addText($x+ 95,$yt,8,"Aprobado",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->addText($x+190,$yt,8,"Gerencia",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->line($x+380, $y1, $x+475 , $y1);
    $rpt->pdf->addText($x+400,$yt,8," ",$angle=0,$wordSpaceAdjust=0);

}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$slQry   = fGetParam('pQryTar', false);
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = NewADOConnection("mysql");
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,27.8), "portrait", $slFontName, 9);
$rep->title="RESUMEN DE PRECIOS POR PRODUCTOR";
//$rep->subTitle="-";
$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1.5,0.3,0.5);
$rep->colHead = array(
                        'NOM' =>'PRODUCTOR',
                        'VAP' =>'VAPOR',
//                        'PRO' =>'PRODUCTO',
//                        'MAR' =>'MARCA',
                        'EMP' =>'EMPA QUE',
//                        'REC' =>'CJAS RECIB',
//                        'RCH' =>'CJAS RECH',
//                        'CAI' =>'CJAS CAID',
                        'CAJ' =>'CJAS EMB.',
                        'POF'=>'PREC OFIC',
                        'ADE'=>'ADEL(-) BONO(+)',
                        'PPR'=>'PREC PROD',
                        'VOF'=>'$ PREC. OFICIAL',
                        'VAD'=>'$ BONO/ ADEL.',
                        'VAL'=>'$ TOTAL');
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "9:2:.:,"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['NOM']->type='U';
$rep->columns['VAP']->type='C';
//$rep->columns['PRO']->type='C';
//$rep->columns['MAR']->type='C';
$rep->columns['EMP']->type='C';

$rep->columns['NOM']->format=false;
$rep->columns['VAP']->format=false;
//$rep->columns['PRO']->format=false;
//$rep->columns['MAR']->format=false;
$rep->columns['EMP']->format=false;

$rep->columns['NOM']->repeat=false;
$rep->columns['VAP']->repeat=false;
//$rep->columns['PRO']->repeat=false;
//$rep->columns['MAR']->repeat=false;
$rep->columns['EMP']->repeat=false;

$rep->columns['REC']->format="7:0:,:.";
$rep->columns['RCH']->format="7:0:,:.";
$rep->columns['CAI']->format="7:0:,:.";
$rep->columns['CAJ']->format="7:0:,:.";
$rep->columns['VAL']->format="14:2:.:,";
$rep->columns['PPR']->format="10:3:.:,";
$rep->columns['POF']->format="10:3:.:,";
$rep->columns['ADE']->format="10:3:.:,";
$rep->columns['VOF']->format="14:2:.:,";
$rep->columns['VAD']->format="14:2:.:,";

$rep->setDefaultColOpt('justification', 'right');
//$rep->colOpt['PRO']['justification']='left';
$rep->colOpt['VAP']['justification']='left';
$rep->colOpt['MAR']['justification']='left';
$rep->colOpt['EMP']['justification']='left';
$rep->colOpt['NOM']['justification']='left';

$rep->setDefaultColOpt('width', 35);
$rep->colOpt['VAP']['width']=55;
$rep->colOpt['EMP']['width']=40;
$rep->colOpt['NOM']['width']=160;
$rep->colOpt['CAJ']['width']=40;
$rep->colOpt['VAL']['width']=50;
$rep->colOpt['POF']['width']=40;
$rep->colOpt['ADE']['width']=45;
$rep->colOpt['PPR']['width']=40;
$rep->colOpt['VOF']['width']=50;
$rep->colOpt['VAD']['width']=50;

$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='NOM';          // set the column for text at resume line of group
    $rep->addResumeLine('general','S', 'SUMA GENERAL', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
//        $rep->setAggregate('general',0, 'PRO','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'VAP','-');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
//        $rep->setAggregate('general',0, 'MAR','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'EMP','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'NOM','-');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'TAR','-');
        $rep->setAggregate('general',0, 'PPR','-');
        $rep->setAggregate('general',0, 'POF','-');
        $rep->setAggregate('general',0, 'ADE','-');
        $rep->setAggregate('general',0, 'BON','-');

$rep->addGrp('GRU');                           // Not required, exist by default
$rep->groups['GRU']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['GRU']->textCol='NOM';          // set the column for text at resume line of group
    $rep->addResumeLine('GRU','S', ' SUMA: ', 1);         // Add a resume line for group 'GRU' using sums in all columns bt default
        $rep->groups['GRU']->linesBefore=0;
//        $rep->setAggregate('GRU',0, 'PRO','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('GRU',0, 'VAP','-');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
//        $rep->setAggregate('GRU',0, 'MAR','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('GRU',0, 'EMP','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('GRU',0, 'TAR','-');
        $rep->setAggregate('GRU',0, 'PPR','-');
        $rep->setAggregate('GRU',0, 'NOM','-');
        $rep->setAggregate('GRU',0, 'POF','-');
        $rep->setAggregate('GRU',0, 'ADE','-');
        $rep->setAggregate('GRU',0, 'BON','-');

$rep->addGrp('NOM');                           // Not required, exist by default
$rep->groups['NOM']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['NOM']->textCol='EMP';          // set the column for text at resume line of group
    $rep->addResumeLine('NOM','S', ' >> ', 1);         // Add a resume line for group 'NOM' using sums in all columns bt default
        $rep->groups['NOM']->linesBefore=0;
//        $rep->setAggregate('NOM',0, 'PRO','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('NOM',0, 'VAP','-');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
//        $rep->setAggregate('NOM',0, 'MAR','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('NOM',0, 'EMP','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('NOM',0, 'NOM','-');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
        $rep->setAggregate('NOM',0, 'TAR','-');
        $rep->setAggregate('NOM',0, 'PPR','-');
        $rep->setAggregate('NOM',0, 'POF','-');
        $rep->setAggregate('NOM',0, 'ADE','-');
        $rep->setAggregate('NOM',0, 'BON','-');


$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$rep->run();
$rep->view($rep->title, $rep->saveFile("TRJ_RES_"));
?>



