<?php
/*
*   LiEmTj_detembsec.rpt.php: Listado de Tarjas por Embarque, secuenciales
*   @author     Fausto Astudillo
*   @param      string		pQry  Condici?n de b?squeda
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
                tad_codproducto  AS PRO,
                tad_codmarca  AS MAR,
                concat(left(conpersonas.per_apellidos, 15), ' ', left(conpersonas.per_nombres, 10))  AS NOM,
                caj_abreviatura AS  EMP,
                tad_numtarja AS TAR,
		tad_cantrecibida AS REC,
                tad_cantrechazada AS RCH,
                tad_cantcaidas AS CAI,
                (tad_cantrecibida - tad_cantrechazada  ) AS CAJ,
                tad_valunitario as PRE,
                if (tad_difunitario >= 0, tad_difunitario, 0) as DIF,
                if (tad_difunitario < 0, tad_difunitario * (-1), 0) as BON,
                tad_valunitario - tad_difunitario as PPR,
                (tad_cantrecibida - tad_cantrechazada  )  * (tad_valunitario - tad_difunitario) as VAL
             FROM (((liqtarjadetal JOIN liqtarjacabec ON tar_NUmTarja = tad_NumTarja)
                JOIN liqembarques  ON emb_RefOperativa = tac_RefOperativa)
                JOIN conpersonas on per_codauxiliar = tac_embarcador)
                JOIN liqcajas ON caj_codcaja = tad_codcaja "
             ;
    if ($pQry) $slSql .= " WHERE "  . $pQry ;
    $slSql .= " ORDER  BY 1,2,3,4,5,6 ";

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
/** Define text to present before the group of column VAP.
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_VAP (&$rpt, &$group) {
    global $db;
    global $glVapTxt;
    $slVapor = '';
    if (isset($group->currValue) && !is_null($group->currValue)) {        // si existe un vapor valido
        $slVapor .= fDBValor($db, 'liqembarques JOIN liqbuques on buq_codbuque = emb_codvapor',
                                   "concat(buq_descripcion, '/ ', emb_numviaje) ",
                                   'emb_refoperativa = ' . $group->currValue);
    } else $slVapor .= "--";
    $glVapTxt = $group->currValue . ' ' . $slVapor;
    $group->resume[0]['resume_text'] = substr('- ' . $slVapor,0,30);
  }

/** CAbecera de gruop MAR
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_MAR (&$rpt, &$group) {
    global $db;
    global $glVapTxt;
    global $glProTxt;
    if (isset($group->currValue) && !is_null($group->currValue)) {
        $slMarca = fDBValor($db, 'genparametros',
                                   "par_descripcion",
                                   "par_clave = 'IMARCA' AND par_secuencia = " . $group->currValue);
    } else $slMarca = "--";
    $rpt->pdf->y -= 10;
    $rpt->putText($glProTxt . "** VAPOR: " . $glVapTxt . "      " . strtoupper($slMarca) . " **", 10,25);

    }
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$slQry   = fGetParam('pQryTar', false);
$slTxt   = fGetParam('pCond', false);
$db = NewADOConnection("mysql");
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
$slFontName = fGetParam('pFon', 'Helvetica');   //                          Fonst Name from URL
$ilFontSize = fGetParam('pSiz', 10); //                                      Font Size from url
$slPosition = fGetParam('pPos', "portrait"); //                            Font Size from url
$slPageSize = fGetParam('pPag', "A4"); //                                   Page Size from URL
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$rep = new ezPdfReport($rs, $slPageSize, $slPosition, $slFontName, $ilFontSize);
$rep->title="DETALLE DE TARJAS ";
//$rep->subTitle="-";
$rep->condition=$slTxt;
$rep->titleOpts=array('T'=>array('font'=>'Helvetica', 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>'Helvetica', 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>'Helvetica', 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1,0.8,.8);
$rep->colHead = array(
//                         'SEM' =>'SEMANA',
//                        'PRO' =>'PRODUCTO',
//                        'VAP' =>'VAPOR',
//                        'EMP' =>'EMPAQUE',

                        'NOM' =>'PRODUCTOR',
                        'EMP' =>'EMPA QUE',
                        'TAR' =>'TARJA',
                        'REC' =>'CJAS RECIB',
                        'RCH' =>'CJAS RECH',
                        'CAI' =>'CJAS CAID',
                        'CAJ' =>'CJAS EMBARC',
//                        'PRE'=>'PRE. OFIC',
//                        'DIF'=>'ADEL ANTO',
//                        'BON'=>'BONO',
//                        'PPR'=>'PRE. PROD',
//                        'VAL'=>'VALOR'
						   );
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0, 'rowGap'=>1,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['PRO']->type='C';
$rep->columns['VAP']->type='C';
$rep->columns['MAR']->type='C';
$rep->columns['EMP']->type='U';
$rep->columns['NOM']->type='C';
$rep->columns['TAR']->type='I';
$rep->columns['PRO']->format=false;
$rep->columns['VAP']->format=false;
$rep->columns['MAR']->format=false;
$rep->columns['EMP']->format=false;
$rep->columns['NOM']->format=false;
$rep->columns['TAR']->format="7:0::";
$rep->columns['EMP']->repeat=false;
$rep->columns['NOM']->repeat=false;

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

$rep->setDefaultColOpt('width', 60);
$rep->colOpt['PRO']['width']=50;
$rep->colOpt['VAP']['width']=50;
$rep->colOpt['MAR']['width']=60;
$rep->colOpt['EMP']['width']=35;
$rep->colOpt['NOM']['width']=160;
$rep->colOpt['MAR']['width']=50;
$rep->colOpt['RCH']['width']=40;
$rep->colOpt['CAI']['width']=40;
$rep->colOpt['CAJ']['width']=50;
$rep->colOpt['VAL']['width']=50;


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
/*
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

$rep->addGrp('PRO');
    $rep->groups['PRO']->fontSize=6;
    $rep->groups['PRO']->textCol='NOM';
    $rep->addResumeLine('PRO','S', '',0);
        $rep->setAggregate('PRO',0, 'SEM','-');
        $rep->setAggregate('PRO',0, 'PRO','-');
        $rep->setAggregate('PRO',0, 'VAP','-');
        $rep->setAggregate('PRO',0, 'MAR','-');
        $rep->setAggregate('PRO',0, 'EMP','-');
        $rep->setAggregate('PRO',0, 'NOM','-');
        $rep->setAggregate('PRO',0, 'TAR','-');
*/
$rep->addGrp('VAP');
    $rep->groups['VAP']->fontSize=6;
    $rep->groups['VAP']->textCol='NOM';
    $rep->addResumeLine('VAP','S', ' ',0);
        $rep->setAggregate('VAP',0, 'SEM','-');
        $rep->setAggregate('VAP',0, 'PRO','-');
        $rep->setAggregate('VAP',0, 'VAP','-');
        $rep->setAggregate('VAP',0, 'MAR','-');
        $rep->setAggregate('VAP',0, 'EMP','-');
        $rep->setAggregate('VAP',0, 'NOM','-');
        $rep->setAggregate('VAP',0, 'TAR','-');

$rep->addGrp('MAR');
    $rep->groups['MAR']->fontSize=6;
    $rep->groups['MAR']->textCol='NOM';
    $rep->addResumeLine('MAR','S', ' ',1);
        $rep->groups['MAR']->linesBefore=1;
        $rep->setAggregate('MAR',0, 'SEM','-');
        $rep->setAggregate('MAR',0, 'PRO','-');
        $rep->setAggregate('MAR',0, 'VAP','-');
        $rep->setAggregate('MAR',0, 'MAR','-');
        $rep->setAggregate('MAR',0, 'EMP','-');
        $rep->setAggregate('MAR',0, 'NOM','-');
        $rep->setAggregate('MAR',0, 'TAR','-');

$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$rep->run();
$rep->view($rep->title, $rep->saveFile("TARJAS_"));
?>



