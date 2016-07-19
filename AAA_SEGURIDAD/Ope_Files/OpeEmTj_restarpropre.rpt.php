<?php
/*
*   LiliTj_restarpropre.php: Resumen de Tarjas valorizado, por Embarque, productor, precio
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
                tac_semana as SEM,
                tac_refoperativa AS VAP,
				emb_NumViaje AS VIA,
                act_descripcion  AS PRO,
                concat(left(conpersonas.per_apellidos, 15), ' ', left(conpersonas.per_nombres, 12))  AS NOM,
                tad_codmarca  AS MAR,
                caj_abreviatura AS  EMP,
                count(tad_numtarja) AS TAR,
			    sum(tad_cantrecibida) AS REC,
                sum(tad_cantrechazada) AS RCH,
                sum(tad_cantcaidas) AS CAI,
                sum((tad_cantrecibida - tad_cantrechazada  )) AS CAJ,
                tad_valunitario - tad_difunitario as PPR,
                sum((tad_cantrecibida - tad_cantrechazada  )  * (tad_valunitario - tad_difunitario)) as VAL
             FROM ((((liqtarjadetal JOIN liqtarjacabec ON tar_NUmTarja = tad_NumTarja)
                JOIN conactivos ON act_codauxiliar = tad_codproducto)
                JOIN conpersonas on per_codauxiliar = tac_embarcador)
                JOIN liqembarques ON emb_RefOperativa =  tac_refOperativa)
                JOIN liqcajas ON caj_codcaja = tad_codcaja "
             ;
/*              tad_valunitario as PRE,
                if (tad_difunitario >= 0, tad_difunitario, 0) as DIF,
                if (tad_difunitario < 0, tad_difunitario * (-1), 0) as BON,
*/
    if ($pQry) $slSql .= " WHERE "  . $pQry ;
    $slSql .= " GROUP BY 1,2,3,4,5,6,7,13  ORDER  BY 1,2,3,4,7,5";

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
/** CAbecera de gruop SEM
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_SEM (&$rpt, &$group) {
    global $db;
    $rpt->putText('SEMANA:   ' . $group->currValue, 8, 15);//        Putting text before group data
    $group->resume[0]['resume_text'] = 'SUBTOT. SEM. ' . $group->currValue . ": ";
    }
/** CAbecera de gruop PRO
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
/*
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
*/
/** Define text to present before the group of column VAP.
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_VAP (&$rpt, &$group) {
    global $db;
    $slVapor = '';
    if (isset($group->currValue) && !is_null($group->currValue)) {        // si existe un vapor valido
        $slVapor .= fDBValor($db, 'liqembarques JOIN liqbuques on buq_codbuque = emb_codvapor',
                                   "concat(buq_descripcion, '/ ', emb_numviaje) ",
                                   'emb_refoperativa = ' . $group->currValue);
    } else $slVapor .= "--";
  $rpt->putText("V A P O R : " . $slVapor ,8, 12);//        Putting text before group data
  $group->resume[0]['resume_text'] = substr($slVapor,0, 20);
  }

/** CAbecera de gruop MAR
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_MAR (&$rpt, &$group) {
    global $db;
    if (isset($group->currValue) && !is_null($group->currValue)) {        
        $slMarca = fDBValor($db, 'genparametros',
                                   "par_descripcion",
                                   "par_clave = 'IMARCA' AND par_secuencia = " . $group->currValue);
    } else $slMarca = "--";
    $rpt->putText("M A R C A : ". $slMarca, 8, 12);//        Putting text before group data
    $group->resume[0]['resume_text'] = "SUBTOT. " .$slMarca;
    }
/** PIE  de gruop MAR
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void

function after_group_MAR (&$rpt, &$group) {
//    $rpt->pdf->eztext("  ", 8, array('justification'=>'left', 'leading'=>15));//        Putting text before group data
}
*/
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
$rep->title="RESUMEN DE TARJAS Y PRECIOS POR EMBARQUE";
//$rep->subTitle="-";
$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(.5,1,1,1);
$rep->colHead = array(
//                         'SEM' =>'SEMANA',

//                        'VAP' =>'VAPOR',
//                        'VIA' =>'PRODUCTOR',
//                        'MAR' =>'MARCA',
                        'PRO' =>'PRODUCTO',
                        'EMP' =>'EMPA QUE',
                        'NOM' =>'PRODUCTOR',
//                        'TAR' =>'NUM.',
                        'REC' =>'CJAS RECIB',
                        'RCH' =>'CJAS RECH',
                        'CAI' =>'CJAS CAID',
                        'CAJ' =>'CJAS EMBARC',
                        'PPR'=>'PRE. PROD',
                        'VAL'=>'VALOR');
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['PRO']->type='C';
$rep->columns['VAP']->type='C';
$rep->columns['MAR']->type='C';
$rep->columns['EMP']->type='C';
$rep->columns['NOM']->type='C';
$rep->columns['TAR']->type='I';
$rep->columns['PRO']->format=false;
$rep->columns['VAP']->format=false;
$rep->columns['MAR']->format=false;
$rep->columns['EMP']->format=false;
$rep->columns['NOM']->format=false;
$rep->columns['TAR']->format="7:0::";
$rep->columns['PRO']->repeat=false;
$rep->columns['EMP']->repeat=false;

$rep->columns['REC']->type='I';
$rep->columns['REC']->format="7:0:,:.";
$rep->columns['RCH']->type='I';
$rep->columns['RCH']->format="7:0:,:.";
$rep->columns['CAI']->type='I';
$rep->columns['CAI']->format="7:0:,:.";
$rep->columns['CAJ']->type='I';
$rep->columns['CAJ']->format="7:0:,:.";

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['PRO']['justification']='left';
$rep->colOpt['VAP']['justification']='left';
$rep->colOpt['MAR']['justification']='left';
$rep->colOpt['EMP']['justification']='left';
$rep->colOpt['NOM']['justification']='left';

$rep->setDefaultColOpt('width', 40);
$rep->colOpt['PRO']['width']=115;
$rep->colOpt['VAP']['width']=50;
$rep->colOpt['MAR']['width']=60;
$rep->colOpt['EMP']['width']=40;
$rep->colOpt['NOM']['width']=140;
$rep->colOpt['MAR']['width']=50;
$rep->colOpt['CAJ']['width']=50;
$rep->colOpt['VAL']['width']=60;


//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='NOM';          // set the column for text at resume line of group
    $rep->addResumeLine('general','S', 'SUMA GENERAL', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
        $rep->setAggregate('general',0, 'SEM','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'PRO','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'VAP','-');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'MAR','-');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'EMP','-');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'NOM','-');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'TAR','-');
        $rep->setAggregate('general',0, 'PPR','-');

$rep->addGrp('SEM');                         // Create a group for column SEM
    $rep->groups['SEM']->fontSize=6;
    $rep->groups['SEM']->textCol='NOM';
    $rep->addResumeLine('SEM','S', ' ',0);
        $rep->setAggregate('SEM',0, 'SEM','-');
        $rep->setAggregate('SEM',0, 'PRO','-');
        $rep->setAggregate('SEM',0, 'VAP','-');
        $rep->setAggregate('SEM',0, 'MAR','-');
        $rep->setAggregate('SEM',0, 'EMP','-');
        $rep->setAggregate('SEM',0, 'NOM','-');
        $rep->setAggregate('SEM',0, 'TAR','-');
        $rep->setAggregate('SEM',0, 'PPR','-');

$rep->addGrp('VAP');
    $rep->groups['VAP']->fontSize=6;
    $rep->groups['VAP']->linesAfter=2;
    $rep->groups['VAP']->textCol='NOM';
    $rep->addResumeLine('VAP','S', ' ',0);
        $rep->setAggregate('VAP',0, 'SEM','-');
        $rep->setAggregate('VAP',0, 'PRO','-');
        $rep->setAggregate('VAP',0, 'VAP','-');
        $rep->setAggregate('VAP',0, 'MAR','-');
        $rep->setAggregate('VAP',0, 'EMP','-');
        $rep->setAggregate('VAP',0, 'NOM','-');
        $rep->setAggregate('VAP',0, 'TAR','-');
        $rep->setAggregate('VAP',0, 'PPR','-');


$rep->addGrp('PRO');
    $rep->groups['PRO']->fontSize=6;
    $rep->groups['PRO']->textCol='NOM';
    $rep->addResumeLine('PRO','S', ' - - - - ',0);
        $rep->setAggregate('PRO',0, 'SEM','-');
        $rep->setAggregate('PRO',0, 'PRO','-');
        $rep->setAggregate('PRO',0, 'VAP','-');
        $rep->setAggregate('PRO',0, 'MAR','-');
        $rep->setAggregate('PRO',0, 'EMP','-');
        $rep->setAggregate('PRO',0, 'NOM','-');
        $rep->setAggregate('PRO',0, 'TAR','-');
        $rep->setAggregate('PRO',0, 'PPR','-');


/*
$rep->addGrp('MAR');
    $rep->groups['MAR']->fontSize=6;
    $rep->groups['MAR']->textCol='NOM';
    $rep->groups['MAR']->linesAfter=1;
    $rep->addResumeLine('MAR','S', ' ',1);
        $rep->groups['MAR']->linesBefore=1;
        $rep->setAggregate('MAR',0, 'SEM','-');
        $rep->setAggregate('MAR',0, 'PRO','-');
        $rep->setAggregate('MAR',0, 'VAP','-');
        $rep->setAggregate('MAR',0, 'MAR','-');
        $rep->setAggregate('MAR',0, 'EMP','-');
        $rep->setAggregate('MAR',0, 'NOM','-');
        $rep->setAggregate('MAR',0, 'TAR','-');
        $rep->setAggregate('MAR',0, 'PPR','-');
*/
$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$rep->run();
$rep->view($rep->title, $rep->saveFile("TRJ_RES_"));
?>



