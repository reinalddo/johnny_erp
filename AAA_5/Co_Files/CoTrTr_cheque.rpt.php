<?php
/*
*   LiLiRp_liquid.php: Liquidacion de Compra de Fruta
*   @author     Fausto Astudillo
*   @param      string		pQryLiq  Condición de búsqueda
*   @output     contenido pdf del reporte.
*/
error_reporting(E_ALL);
include("../LibPhp/ezPdfReport.php");
include("../LibPhp/GenCifras.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){

    $alSql[] = "select com_numcomp AS 'COM', com_feccontab as 'FEC',
				det_valcredito as 'VAL', com_receptor as 'BEN'
				from concomprobantes join condetalle on det_regnumero = com_regnumero
				join conpersonas on per_codauxiliar = det_idauxiliar
                WHERE " . $pQry . " and
	det_idauxiliar =com_emisor
	AND det_valcredito <> 0";

    $rs= fSQL($db, $alSql);
    return $rs;
}
/**
*   Texto acbecera de cada liquidacion
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_COM (&$rpt, &$group) {
    global $db;
    global $ilPag;
    global $gfValor;
    global $ilTipProceso;

    $ilPag +=1;

    $rpt->pdf->y-=20;
    $y = $rpt->pdf->y;
    $x=  $rpt->leftBorder;
//            $slFecha=  date("F j, Y", fText2Fecha($rs->fields['fecha']));
    $sltxt=  ($group->lastRec['BEN']);
    $slFecha=  ($group->lastRec['FEC']);
    $flValor=  $group->lastRec['VAL'];

    $rpt->putTextWrap(210, $y, 400, 10, strtoupper($sltxt),  $pLead = 0, $pJust = 'left', $pAng = 0, 0);
    $rpt->putTextWrap(345, $y, 200, 12, $flValor,  $pLead = 0, $pJust = 'right', $pAng = 0, 0);

    $slCant = strtoupper(num2letras($flValor, false, 2, 2, " US Dolares", " ctvs."));
//    echo $slCant . " " . $gfValor . " " . $flValor ."<br>";
    $y -=  22;
    $rpt->putTextWrap(205, $y, 430, 10, $slCant,  $pLead = 0, $pJust = 'left', $pAng = 0, 0);
    $y -= 30;
    $rpt->putTextWrap(195, $y, 300, 10, "Guayaquil,  " . $slFecha,  $pLead = 0, $pJust = 'left', $pAng = 0, 0);
//    $group->lastRec['NOM'];
    $y = $rpt->pdf->y -30;

}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
error_reporting(E_ALL);
$ilPag = 1;
$ilTipProceso= fGetParam('pTipo', 20);//                    Tipo de proceso, si no se viene en GET, el tipo es 20
$slQry   = fGetParam('pQryCom', false);
//if (strlen($slQry)<1){
if (!$slQry) fErrorPage('','DEBE DEFINIR UN CRITERIO DE SELECCION ', true,  false);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry);
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,8), "portrait", $slFontName, 10);
//$rep->title="LIQUIDACIONDE COMPRA DE FRUTA";
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1,1,0.5);
$rep->colHead = array(
                        'zCOM' =>'RUBRO',
                        'zFEC' =>'PRODUCTO',
                        'zVAL' =>'EMPAQUE',
                        'zBEN' =>'CJAS EMBARC');
$rep->setVisible('COM', false);
$rep->setVisible('FEC', false);
$rep->setVisible('VAL', false);
$rep->setVisible('BEN', false);
$rep->columns['COM']->visible= false;

$rep->rptOpt = array('fontSize'=>10, 'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->printColHead=false;
$rep->printFooter=false;
$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "C");        //      Default column type
$rep->setDefaultColPro('visible', false);        //      Default column type
$rep->addGrp('COM');                           // Not required, exist by default
$rep->groups['COM']->detail=0;
$gfValor=0;
$rep->run();
$rep->view($rep->title, $rep->saveFile("LIQ_GEN_"));
?>
