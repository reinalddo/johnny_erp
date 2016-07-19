<?php
/*
*   CoTrTr_saldiario.rpt.php: Saldos Diarios de Cuentas
*   @author     Fausto Astudillo
*   @param      string		pQryCom  Condición de búsqueda
*   @output     contenido pdf del reporte.
*/
error_reporting (E_ALL ^ E_NOTICE);
//set_error_handler("repErrorhandler");
define("RelativePath", "..");
$slFile = "CoTrTr";
include("../Common.php");
include("../LibPhp/SegLib.php");
    if (!fValidAcceso($slFile,"","")) {
        	fMensaje (basename($_SERVER["PHP_SELF"]) . ":   ACCESO RESTRINGIDO: UD. NO TIENE ACCESO A ESTE MODULO " . $slFile, 1);
            die();
    }
include("../LibPhp/ezPdfReport.php");
//include("../LibPhp/GenCifras.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pFec=false){
    $alSql = Array();
    $slQry = fGetParam("pQry", "par_secuencia = 1");    //  Si no tiene parametro asume que se trata de libro bancos
//
    $alSql = "SELECT  concat(det_codCuenta,'-',cue_Descripcion) as 'CUE', concat(per_Apellidos,' ', per_nombres) AS 'NOM',
                        sum(det_valdebito) -sum(det_valcredito) AS 'SAL'
                FROM condetalle  
			/* JOIN  genparametros ON par_clave = 'CLIBRO' and  par_valor1 = det_codcuenta */
			JOIN  concomprobantes on com_regnumero = det_regnumero
			JOIN conpersonas on per_codauxiliar = det_idauxiliar
			join concuentas on Cue_CodCuenta = det_codCuenta
                WHERE com_feccontab <= '". $pFec . "'
		and det_codcuenta in (   SELECT Cue_CodCuenta FROM concuentas
					 WHERE Cue_CodCuenta LIKE CONCAT((SELECT par_Valor1
									  FROM genparametros
									  WHERE par_Clave = 'CUCXC'), '%')
				      )" ;
	    

$pCue_CodCuenta = fGetParam('Cue_CodCuenta',false);		
$alSql .= ($pCue_CodCuenta ? " and det_codcuenta = ". $pCue_CodCuenta  : "  " );
$alSql .= " GROUP BY 1,2 ORDER BY 1,2";


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

function repErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
//    fErrorPage('',$errmsg . "<br>(" . $filename . ", linea: " . $linenum . ")", true, false);
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$ilPag = 1;
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$fec = fGetParam('pFec', date('Y-M-d H:i:s'));
$rs = fDefineQry($db, $fec);
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, "A4", "portrait", $slFontName, 12);
//$rep->title="SALDOS AL DIA " . date('Y-M-d H:i:s');
$rep->title="SALDOS AL DIA " . $fec;
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>8 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1,.5,.5);
$rep->colHead = array(  'CUE' =>'CUENTA', 
                        'NOM' =>'AUX',
                        'SAL' =>'SALDO');
$xPos=$rep->pdf->ez['leftMargin'];
//$rep->rptOpt = array('fontSize'=>10, 'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
//                     'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->rptOpt = array('fontSize'=>10,  'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
                     'showLines'=>0, 'rowGap'=>0, 'maxWidth'=>990,  'xPos'=>$xPos, 'xOrientation'=>'right');

$rep->printColHead=true;
$rep->setDefaultColPro('format', false); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->columns['CUE']->type='C';
$rep->columns['NOM']->type='C';
$rep->columns['SAL']->type='N';

$rep->columns['CUE']->repeat=false;
$rep->columns['NOM']->repeat=false;
$rep->columns['SAL']->repeat=false;
$rep->columns['CUE']->format=false;
$rep->columns['NOM']->format=false;
$rep->columns['SAL']->format="14:2:,:.";

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['SAL']['justification']='right';

$rep->colOpt['CUE']['width']=180;
$rep->colOpt['NOM']['width']=250;
$rep->colOpt['SAL']['width']=100;

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
$rep->groups['general']->textCol='NOM';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'TOTAL GENERAL USD $', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=2;
        $rep->setAggregate('general',0, 'SAL','S');  // Chan
$gfValor=0;
$firstComp=true;
$rep->run();
$rep->view($rep->title, $rep->saveFile("CON_SAL_"));
//$rep->preView()
?>
