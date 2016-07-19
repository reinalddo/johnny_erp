<?php
/*
*   LiEmTj_detemb.rpt.php: Listado de Tarjas por Embarque  ///@TODO NO TERMINADO...
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

    $slSql = "SELECT
                tac_semana as SEM,
                tad_numtarja AS TAR,
                concat(left(conpersonas.per_apellidos, 15), ' ', left(conpersonas.per_nombres, 10))  AS NOM,
                concat(left(buq_abreviatura,8), ' - ', emb_numviaje) AS  VAP,
                left(act_descripcion,15)  AS PRO,
                par_descripcion  AS MAR,
                caj_abreviatura AS  EMP,
			    tad_cantrecibida AS REC,
                tad_cantrechazada AS RCH,
                tad_cantcaidas AS CAI,
                (tad_cantrecibida - tad_cantrechazada  ) AS CAJ
             FROM liqtarjacabec JOIN liqtarjadetal ON tad_NUmTarja = tar_NumTarja
                JOIN liqembarques ON emb_refoperativa = tac_refOperativa
                JOIN liqbuques ON buq_Codbuque = emb_CodVapor
                JOIN conpersonas on per_codauxiliar = tac_embarcador
                JOIN liqcajas ON caj_codcaja = tad_codcaja
				JOIN conactivos ON act_codauxiliar = tad_codproducto
				JOIN genparametros ON par_Clave = 'IMARCA' AND par_secuencia = tad_codmarca
			 "
             ;
/*
               tad_valunitario as PRE,
                if (tad_difunitario >= 0, tad_difunitario, 0) as DIF,
                if (tad_difunitario < 0, tad_difunitario * (-1), 0) as BON,
                tad_valunitario - tad_difunitario as PPR,
                (tad_cantrecibida - tad_cantrechazada  )  * (tad_valunitario - tad_difunitario) as VAL

*/
    if ($pQry) $slSql .= " WHERE  "  . $pQry ;
    $slSql .= " ORDER  BY 1,2 ";

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
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$slQry   = fGetParam('pQryTar', false);

$db = NewADOConnection("mysql");
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$rep = new ezPdfReport($rs, array(21.5,27.8), "landscape", "Courier", 9);
$rep->title="DETALLE SECUENCIAL DE TARJAS ";
//$rep->subTitle="-";
$rep->condition=$slQry;
$rep->titleOpts=array('T'=>array('font'=>'Helvetica', 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>'Helvetica', 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>'Helvetica', 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1,0.8,0.8);
$rep->colHead = array(
//                         'SEM' =>'SEMANA',
                        'TAR' =>'TARJA',
                        'NOM' =>'PRODUCTOR',
                        'VAP' =>'VAPOR',
                        'EMP' =>'EMPA QUE',
                        'PRO' =>'PRODUCTO',
                        'MAR' =>'MARCA',
                        'EMP' =>'EMPAQUE',
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
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['PRO']->type='U';
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
$rep->columns['EMP']->repeat=false;
$rep->columns['PRO']->repeat=false;
$rep->columns['VAP']->repeat=false;
$rep->columns['MAR']->repeat=false;
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
$rep->colOpt['TAR']['justification']='left';
$rep->colOpt['PRO']['justification']='left';
$rep->colOpt['VAP']['justification']='left';
$rep->colOpt['MAR']['justification']='left';
$rep->colOpt['EMP']['justification']='left';
$rep->colOpt['NOM']['justification']='left';

$rep->setDefaultColOpt('width', 40);
$rep->colOpt['PRO']['width']=70;
$rep->colOpt['VAP']['width']=70;
$rep->colOpt['MAR']['width']=70;
$rep->colOpt['EMP']['width']=50;
$rep->colOpt['NOM']['width']=130;
$rep->colOpt['TAR']['width']=40;


//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='NOM';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'SUMA GENERAL', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
        $rep->setAggregate('general',0, 'REC','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'RCH','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'CAI','S');  // Change aggregate to '-' (nothing)  for column 'C1' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'CAJ','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1

$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$rep->run();
$rep->view($rep->title, $rep->saveFile("TARJAS_"));
?>



