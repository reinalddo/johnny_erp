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
    global $gsHeader;
    $slConI = " com_feccontab < '" . $gdDesde . "'"; //         Condicion Inicial
    $slConD = " com_feccontab >= '" . $gdDesde . "'";//         Condicion 'Durante'
    $slConF = " com_feccontab <= '" . $gdHasta . "'";//         Condicion Final2
//      Definicion de query para x-ref
    $slSql = "SELECT DISTINCT com_emisor AS 'BOD',
                left(concat(per_Apellidos, ' ', per_Nombres),15) as 'DES',
                concat('SUM(IF (com_emisor = ', per_codauxiliar, ', det_cantequivale * pro_signo,0)) AS B',
                       per_codauxiliar) AS TXT,
                concat('B', per_codauxiliar) as CLV
            FROM	invprocesos JOIN
            	concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc JOIN
            	conpersonas ON per_codauxiliar = com_emisor JOIN
            	invdetalle on det_regnumero = com_regnumero
            WHERE com_feccontab >= '2004-12-31' AND " . $slConF . "
            ORDER BY 1,3
        ";
    $rs0 = $db->Execute($slSql);
    $slPivot = "";
    $gsHeader = '$' . "rep->colHead = array('ITE' => 'COD.' ,
                        'DES'  => 'I T E M ',
                        'UNI'  => 'U.',";
    while ($rec = $rs0->FetchRow()){
        if (strlen($slPivot)>0) {
            $slPivot  .= ", ";
            $gsHeader .= ", ";
        }
        $slPivot  .= $rec['TXT'];
        $gsHeader .= "'" . $rec['CLV'] . "'=>'". $rec['DES'] ."'";
    }
    $gsHeader .= ", 'SAC'=>'SALDO ACTUAL', 'VAC'=>'COSTO ', 'PUN'=>'COSTO UNITARIO');";
//    echo $slPivot . " <br>";
//    echo $gsHeader. " <br>";
//

   $slSql = "SELECT act_grupo AS 'GRU',
            	det_coditem AS 'ITE',
                concat(act_descripcion, ' ', act_descripcion1) as 'DES' ,
                uni_abreviatura as 'UNI',
                " . $slPivot . ",
            	sum(det_cantequivale * pro_signo)  as SAC,
            	sum(det_cosTotal * pro_signo)      as VAC,
            	sum(det_cosTotal * pro_signo) /	sum(det_cantequivale * pro_signo)  as PUN
            FROM	invprocesos JOIN
            	concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc
            	JOIN invdetalle ON det_regnumero = com_regnumero
            	JOIN conactivos ON act_codauxiliar = det_coditem
            	JOIN genunmedida ON uni_codunidad= act_unimedida
            WHERE com_feccontab >= '2004-12-31' AND " . $slConF .
	    	"  AND (det_cantequivale <> 0 OR det_cosTotal <> 0)
	    GROUP BY 1,2,3,4
            HAVING SAC<>0  OR VAC <>0
            ORDER BY 1,3
        ";
// echo $slSql;
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
function before_group_GRU (&$rpt, &$group) {
    global $db;
    $slGrupo = fDBValor($db, 'genparametros', 'par_descripcion', "par_ClaVE  = 'actgru' and PAR_SECUENCIA  = " . $group->currValue);
    $slText = $group->currValue . " - " . $slGrupo;
    $rpt->pdf->y -=15;
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 400, 12, $slText);
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
$gsHeader="";
$giPerio   = fGetParam('pPerio', false);
if (!$giPerio){
	$gdHasta = date("Y-m-d");
	$gdDesde = fDBValor($db, 'genparametros', 'par_Valor1', "par_clave = 'ININIC' AND par_secuencia=1");
	$giPerio = fDBValor($db, 'conperiodos', 'max(per_NUmperiodo)', "per_aplicacion = 'CO' and '" . $giPerio . "' between per_fecinicial and per_fecfinal ");
	} else   {
		$pe = fDBValor($db, 'conperiodos', 'per_FecInicial, per_FecFinal', "per_aplicacion = 'IN' AND per_numPeriodo = " . $giPerio);
		list ($gdDesde, $gdHasta) = $pe;
	}
$rs = fDefineQry($db);
$rep = new ezPdfReport($rs, array(33.5,21.5), "portrait", $slFontName, 8);
$rep->title="SALDO GENERAL DE INVENTARIO ";
$rep->subTitle="PERIODO DE " . $gdDesde . " A " . $gdHasta;
$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1,0.3,0.5);
eval($gsHeader);

$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "12:2:.:,"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->setDefaultColPro('format', "12:2:.:,:-o-");
$rep->columns['ITE']->type="C";
$rep->columns['DES']->type="C";
$rep->columns['UNI']->type="C";
$rep->columns['ITE']->format="";
$rep->columns['PUN']->format="8:4:.::-o-";
$rep->columns['VAC']->zeroes=true;

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['ITE']['justification']='left';
$rep->colOpt['DES']['justification']='left';
$rep->colOpt['UNI']['justification']='left';

$rep->setDefaultColOpt('width', 52);
$rep->colOpt['ITE'] ['width']=35;
$rep->colOpt['DES'] ['width']=90;
$rep->colOpt['UNI'] ['width']=20;
$rep->colOpt['VAC'] ['width']=57;
$rep->colOpt['PUN'] ['width']=50;

//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='DES';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'SUMA GENERAL *** ', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
        $rep->groups['general']->linesAfter=2;
        $rep->setAggregate('general',0, 'PUN','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'ITE','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'DES','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'UNI','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

$rep->addGrp('GRU');                         // Create a group for column SEM
    $rep->groups['GRU']->fontSize=6;
    $rep->groups['GRU']->textCol='DES';
    $rep->addResumeLine('GRU','S', 'S U B T O T A L **  ',0);
        $rep->groups['GRU']->linesBefore=1;
        $rep->groups['GRU']->linesAfter=2;
        $rep->setAggregate('GRU',0, 'PUN','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('GRU',0, 'ITE','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('GRU',0, 'DES','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('GRU',0, 'UNI','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$rep->run();
$rep->view($rep->title, $rep->saveFile("INV_SGR_"));
?>



