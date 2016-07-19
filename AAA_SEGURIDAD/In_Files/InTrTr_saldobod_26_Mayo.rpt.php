<?php
/*
*   InTrTr_Saldogen.rpt.php: Saldo General de Inventario
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
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db){
    global $giPerio;
    global $gdDesde;
    global $gdHasta;
    $ilBodega=fGetParam('com_Emisor', false);
    $slConI = " com_feccontab < '" . $gdDesde . "'"; //         Condicion Inicial
    $slConD = " com_feccontab >= '" . $gdDesde . "'";//         Condicion 'Durante'
    $slConF = " com_feccontab <= '" . $gdHasta . "'";//         Condicion Final2
    $slSql = "SELECT
                com_emisor as 'BOD',
                act_grupo AS 'GRU',
            	det_coditem AS 'ITE',
                concat(act_descripcion, ' ', act_descripcion1) as 'DES' ,
                uni_abreviatura as 'UNI',
                ' '            AS 'C99',
            	sum(if (" . $slConI . " , det_cantequivale * pro_signo, 0))        as SAN,
            	sum(if (" . $slConI . " , det_cosTotal * pro_signo, 0))            as VAN,
            	sum(if (" . $slConD . " AND pro_signo = 1, det_cantequivale , 0))  as CIN,
            	sum(if (" . $slConD . " AND pro_signo = 1, det_cosTotal , 0))      as VIN,
            	sum(if (" . $slConD . " AND pro_signo = -1 , det_cantequivale , 0)) as CEG,
            	sum(if (" . $slConD . " AND pro_signo = -1 , det_cosTotal , 0))    as VEG,
            	sum(det_cantequivale * pro_signo)  as SAC,
            	sum(det_cosTotal * pro_signo)      as VAC,
            	sum(det_cosTotal * pro_signo) /	sum(det_cantequivale * pro_signo)  as PUN
            FROM	invprocesos JOIN
            	concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc
            	JOIN invdetalle ON det_regnumero = com_regnumero
            	JOIN conactivos ON act_codauxiliar = det_coditem
            	JOIN genunmedida ON uni_codunidad= act_unimedida
            WHERE com_feccontab >= '2004-12-31' AND " . $slConF . "  AND (det_cantequivale <> 0 OR det_cosTotal <> 0) ".
            ($ilBodega? 'AND com_Emisor = ' . $ilBodega : '') .
            " GROUP BY 1,2,3,4,5,6
            HAVING SAN <>0 OR CIN <>0 OR CEG <>0
            ORDER BY 1,2,3
        ";
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
function before_group_BOD (&$rpt, &$group) {
    global $db;
    global $gdDesde, $gdHasta;
    $slGrupo = fDBValor($db, 'conpersonas', "concat(per_Apellidos, ' ' , per_Nombres) as tmp_bodega", "per_codauxiliar  = " . $group->currValue);
    $slText = $group->currValue . " - " . $slGrupo;
    $rpt->pdf->y -=10;
    $rpt->pdf->eztext($slText, 10, array('justification'=>'center', 'leading'=>12));//        Putting text before group data

    $slSql= " Select if(cla_clatransaccion=1, 'INGRESOS', 'EGRESOS') as tipo,
		          MIN(com_numcomp) as minimo, MAX(com_numcomp) as maximo
		          from concomprobantes join genclasetran on cla_tipocomp = com_tipocomp
		          WHERE com_emisor = " . $group->currValue . " AND
			             com_feccontab between '" . $gdDesde . "' and '" . $gdHasta. "' " .
	              " GROUP by 1";
    $rs = $db->Execute($slSql);
    if ($rs) {
        while ($rec = $rs->FetchNextObject()) {
            $slText = $rec->TIPO . ': del ' . $rec->MINIMO . ' al ' . $rec->MAXIMO;
            $rpt->pdf->eztext($slText, 10, array('justification'=>'right', 'leading'=>12));
        }
    }
     $rpt->pdf->y -=2;
/****/
}
function before_group_GRU (&$rpt, &$group) {
    global $db;
    $slGrupo = fDBValor($db, 'genparametros', 'par_descripcion', "par_Clave  = 'actgru' and PAR_SECUENCIA  = " . $group->currValue);
    $slText = $group->currValue . " - " . $slGrupo;
    $rpt->pdf->y -=15;
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 400, 10, $slText);//        Putting text before group data
    $rpt->pdf->y +=8;
}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$db =& fConexion();
set_time_limit (0) ;
$slFontName = 'Helvetica';
$gdDesde=false;
$gdHasta= false;
/**
$giPerio   = fGetParam('pPerio', false);
$pe = fDBValor($db, 'conperiodos', 'per_fecInicial, per_FecFinal', "per_aplicacion = 'IN' AND per_numPeriodo = " . $giPerio);
list ($gdDesde, $gdHasta) = $pe;
**/
$gdDesde = fGetParam('pFecI', false);
$gdHasta = fGetParam('pFecF', false);
$gsTipoR = fGetParam('pTipR', false);  //   Tipo de Reporte: F=Para Toma Fisica
$iCtoFlag  = fGetParam('pCosto', false);
if (!$gdDesde) $gdDesde = fDBValor($db, 'genparametros', 'par_Valor1', "par_clave = 'ININIC' AND par_secuencia=1");
if (!$gdHasta) $gdHasta = date("Y-m-d", time());
$rs = fDefineQry($db);
$rep = new ezPdfReport($rs, array(21.5,27.8), "landscape", $slFontName, 8);
$rep->title="SALDOS DE INVENTARIO POR BODEGA";
$rep->subTitle="PERIODO DE " . $gdDesde . " A " . $gdHasta;
$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.8,1.5,0.3,0.5);
if ($iCtoFlag)
    $rep->colHead = array(
//                        'BOD'  => "BODEGA",
//                        'GRU'  => "GRUPO",
                        'ITE' => "COD.",
                        'DES'  => "I T E M ",
                        'UNI'  => "U.",
                        'SAN'  => "SLDO. PREVIO",
                        'VAN'  => "VALR. PREVIO",
                        'CIN'  => "CANT. INGRESOS",
                        'VIN'  => "VALR. INGRESOS",
                        'CEG' => 'CANT. EGRESOS',
                        'VEG' => 'VALR. EGRESOS',
                        'SAC' => 'SLDO FINAL',
                        'VAC' => 'VALR FINAL'
                        );
else
    $rep->colHead = array(
//                        'BOD'  => "BODEGA",
//                        'GRU'  => "GRUPO",
                        'ITE' => "COD.",
                        'DES'  => "I T E M ",
                        'UNI'  => "U.",
                        'SAN'  => "SLDO. PREVIO",
//                        'VAN'  => "VALR. PREVIO",
                        'CIN'  => "CANT. INGRESOS",
//                        'VIN'  => "VALR. INGRESOS",
                        'CEG' => 'CANT. EGRESOS',
//                        'VEG' => 'VALR. EGRESOS',
                        'SAC' => 'SLDO FINAL',
//                        'VAC' => 'VALR FINAL'
                        'PUN' => 'PREC.UNIT.'
                        );

if ($gsTipoR =="F") {
    $rep->colHead['C90'] = "TOMA FISICA";
    $rep->title="LISTADO PARA TOMA FISICA DE INVENTARIO";
}
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "12:2:.:,"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['ITE']->type="C";
$rep->columns['DES']->type="C";
$rep->columns['UNI']->type="C";
$rep->columns['ITE']->format="";
$rep->columns['SAC']->format="12:2:.:,:-o-";
$rep->columns['SAC']->zeroes=true;
$rep->columns['VAC']->format="12:2:.:,:-o-";
$rep->columns['VIN']->format="12:2:.:,:-o-";
$rep->columns['VEG']->format="12:2:.:,:-o-";
$rep->columns['VAC']->zeroes=true;
$rep->columns['PUN']->format="12:4:.:,:-o-";

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['ITE']['justification']='left';
$rep->colOpt['DES']['justification']='left';
$rep->colOpt['UNI']['justification']='left';

$rep->setDefaultColOpt('width', ($iCtoFlag)?55:65);
$rep->colOpt['ITE'] ['width']=35;
$rep->colOpt['DES'] ['width']=($iCtoFlag)?90:125;
$rep->colOpt['UNI'] ['width']=20;
$rep->colOpt['VAN'] ['width']=60;
$rep->colOpt['VIN'] ['width']=55;
$rep->colOpt['VEG'] ['width']=55;
$rep->colOpt['VAC'] ['width']=60;

//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='DES';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'SUMA GENERAL *** ', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
        $rep->setAggregate('general',0, 'VAN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'VIN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'VEG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'VAC','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

$rep->addGrp('BOD');                         // Create a group for column SEM
    $rep->groups['BOD']->fontSize=6;
    $rep->groups['BOD']->textCol='DES';
    $rep->addResumeLine('BOD','-', 'T O T A L  **  ',0);
        $rep->groups['BOD']->linesBefore=1;
        $rep->setAggregate('BOD',0, 'VAN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('BOD',0, 'VIN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('BOD',0, 'VEG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('BOD',0, 'VAC','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

$rep->addGrp('GRU');                         // Create a group for column SEM
    $rep->groups['GRU']->fontSize=6;
    $rep->groups['GRU']->textCol='DES';
    $rep->addResumeLine('GRU','-', 'S U B T O T A L **  ',0);
        $rep->groups['GRU']->linesBefore=1;
        $rep->setAggregate('GRU',0, 'VAN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('GRU',0, 'VIN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('GRU',0, 'VEG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('GRU',0, 'VAC','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$rep->run();
$rep->view($rep->title, $rep->saveFile("INV_SAL_"));
?>



