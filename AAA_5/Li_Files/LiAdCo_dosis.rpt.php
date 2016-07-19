<?php
/*
*   LiEmTj_detemb.rpt.php: Listado de Tarjas por Embarque
*   @author     Fausto Astudillo
*   @param      integer		pQry  Condici?n de b?squeda
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
    $slSql = "SELECT ' '                                    AS 'REF',
                    IF(par_valor4=1,'A C T I V A S', 'I N A C T I V A S')  AS 'ACT',
                    IFNULL(par_valor1,9999)                 AS 'TIP',
                    par_descripcion                         AS 'MAR',
                    concat(liqcajas.caj_CodCaja , '/ ', liqcajas.caj_abreviatura, ' - ', liqcajas.caj_descripcion) AS 'EMP',
                    concat(liqcomponent.cte_Referencia,  ' - ' , left(cte_descripcion,25)) AS 'CTE',
                    concat(dos_codItem , ' - ' , act_Descripcion ) AS 'ITE',
                    uni_abreviatura                                AS 'UNI',
                    liqdosis.dos_Cantidad                   AS 'DOS',
                    conactivos.act_Tipo                     AS 'TII',
                    act_grupo                               AS 'GRU',
                    IFNULL(pre_PreUnitario, 0)              AS 'PUN',
                    IFNULL((pre_preunitario *  dos_cantidad ), 0)  AS 'VAL'
                FROM liqcajas left join genparametros on (par_clave='IMARCA' and par_secuencia = caj_codmarca)
                    left join liqcomponent on  liqcomponent.cte_Codigo IN (caj_componente1, caj_componente2,caj_componente3, caj_componente4)
                    left join liqdosis on liqdosis.dos_CodComponente = cte_Codigo
                    left join conactivos on act_codauxiliar = dos_coditem
                    left join  invprecios on pre_lisprecios = 3 and (invprecios.pre_CodItem = act_codauxiliar)
                    left join  genunmedida on uni_codunidad = act_unimedida
                ";
    if ($pQry) $slSql .= "WHERE " . $pQry;
    $slSql .= " ORDER BY 2, 3, par_descripcion, caj_descripcion, cte_Clase,
                    act_tipo, act_grupo,
                    act_descripcion ";
    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERO LA INFORMACION BASE DEL REPORTE', true,false);
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
function before_group_ACT(&$rpt, &$grp){
    $rpt->pdf->ezSetDy(-15);
    $rpt->putText("***  D O S I S      " .  $grp->lastRec['ACT']);
}
function before_group_TIP(&$rpt, &$grp){
    $rpt->pdf->ezSetDy(-10);
    switch ($grp->lastRec['TIP']){
        case 1:
            $rpt->putText(" **   MARCAS PROPIAS ");
            break;
        default:
            $rpt->putText(" **   MARCAS DE TERCEROS");
            break;
    }
}

function before_group_MAR(&$rpt, &$grp){
    $rpt->pdf->ezSetDy(-5);
  }

function before_group_EMP(&$rpt, &$grp){
    $rpt->pdf->ezSetDy(-20);
    $rpt->putTextAndLabel($rpt->leftBorder, $rpt->pdf->y, $grp->lastRec['MAR'] , '  *    MARCA  :', 200, 70);
    $rpt->pdf->ezSetDy(-12);
    $rpt->putTextAndLabel($rpt->leftBorder, $rpt->pdf->y, $grp->lastRec['EMP'] , '  *    EMPAQUE:', 200, 70);
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$slQry   = fGetParam('pQryDos', false);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$rep = new ezPdfReport($rs, array(21.5,27.8), "portrait", "Times-Roman", 9);
$rep->title="DETALLE GENERL DE DOSIFICACION ";
//$rep->subTitle="-";
$rep->condition=$slQry;
$rep->titleOpts=array('T'=>array('font'=>$rep->font, 'fontSize'=>$rep->fontSize, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$rep->font, 'fontSize'=>$rep->fontSize-2 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$rep->font, 'fontSize'=>$rep->fontSize-2 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1,0.8,0.8);
$rep->colHead = array(
//                        'ACT' =>'ACTIVO',
//                        'TIP' =>'TIPO',
//                        'MAR' =>'MARCA',
//                        'EMP' =>'EMPAQUE',
                          'REF' =>' ',
                          'CTE' =>'COMPONENTE',
                          'ITE' =>'ITEM',
                          'DOS' =>'DOSIS',
                          'UNI' =>'UN.MED.',
//                        'TII' =>'TIPO ITEM',
//                        'GRU' =>'GRUPO ITEM',
                          'PUN' =>'PREC. UNIT.',
                          'VAL' =>'VALOR'
						   );
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', ""); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "U");        //      Default column type
$rep->columns['PUN']->type='N';
$rep->columns['VAL']->type='N';
$rep->columns['DOS']->type='N';
$rep->columns['DOS']->format= '8:4:,:.';
$rep->columns['PUN']->format= '8:4:,:.';
$rep->columns['VAL']->format='10:2:,:.';

$rep->columns['CTE']->repeat=false;
$rep->columns['ITE']->repeat=false;

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['DOS']['justification']='right';
$rep->colOpt['PUN']['justification']='right';
$rep->colOpt['VAL']['justification']='right';

$rep->setDefaultColOpt('width', 80);
$rep->colOpt['REF']['width']=16;
$rep->colOpt['CTE']['width']=185;
$rep->colOpt['ITE']['width']=150;
$rep->colOpt['DOS']['width']=40;
$rep->colOpt['UNI']['width']=35;
$rep->colOpt['PUN']['width']=40;
$rep->colOpt['VAL']['width']=50;

$rep->addGrp('ACT');
$rep->addGrp('TIP');
$rep->addGrp('MAR');
$rep->groups['MAR']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable s
$rep->groups['MAR']->lineBefore=4;
$rep->addGrp('EMP');                           // Not required, exist by default
$rep->groups['EMP']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['EMP']->textCol='CTE';          // set the column for text at resume line of group
    $rep->addResumeLine('EMP','-', '               S U M A ----- ', 1);         // Add a resume line for group 'EMP' using sums in all columns bt default
        $rep->groups['EMP']->linesBefore=1;
        $rep->setAggregate('EMP',0, 'VAL','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1

//$rep->addGrp('general');                           // Not required, exist by default

$rep->run();
$rep->view($rep->title, $rep->saveFile("LIQ_DOSIS_"));
?>



