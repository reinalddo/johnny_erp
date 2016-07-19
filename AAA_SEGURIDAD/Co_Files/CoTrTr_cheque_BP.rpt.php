<?php
/*
*   LiLiRp_liquid.php: Liquidacion de Compra de Fruta
*   @author     Fausto Astudillo
*   @param      string		pQryLiq  Condición de búsqueda
*   @output     contenido pdf del reporte.
*   @rev	esl	21/Mzo/2012	Formato de Impresion de Cheque para Codrigna-Banco del Pacifico
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

    $alSql[] = "select com_numcomp AS 'COM', com_feccontab ,
					    case month(com_feccontab)
						when 1 then concat(year(com_feccontab),' /01/ ',day(com_feccontab))
						when 2 then concat(year(com_feccontab),' /02/ ',day(com_feccontab))
						when 3 then concat(year(com_feccontab),' /03/ ',day(com_feccontab))
						when 4 then concat(year(com_feccontab),' /04/ ',day(com_feccontab))
						when 5 then concat(year(com_feccontab),' /05/ ',day(com_feccontab))
						when 6 then concat(year(com_feccontab),' /06/ ',day(com_feccontab))
						when 7 then concat(year(com_feccontab),' /07/ ',day(com_feccontab))
						when 8 then concat(year(com_feccontab),' /08/ ',day(com_feccontab))
						when 9 then concat(year(com_feccontab),' /09/ ',day(com_feccontab))
						when 10 then concat(year(com_feccontab),' /10/ ',day(com_feccontab))
						when 11 then concat(year(com_feccontab),' /11/ ',day(com_feccontab))
						when 12 then concat(year(com_feccontab),' /12/ ',day(com_feccontab))
					    end as 'FEC',
				det_valcredito as 'VAL',
				FORMAT(det_valcredito, 2)as 'VAL2',
				com_receptor as 'BEN'
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

    $rpt->pdf->y-=25;
    $y = $rpt->pdf->y;
    $x=  $rpt->leftBorder;
//            $slFecha=  date("F j, Y", fText2Fecha($rs->fields['fecha']));
    $sltxt=  ($group->lastRec['BEN']);
    $slFecha=  ($group->lastRec['FEC']);
    $flValor=  $group->lastRec['VAL'];
    $flValor2=  $group->lastRec['VAL2'];
    
    
    $rpt->putTextWrap(85, $y, 400, 8, strtoupper($sltxt),  $pLead = 0, $pJust = 'left', $pAng = 0, 0);//10
    $rpt->putTextWrap(205, $y, 200, 12, $flValor2,  $pLead = 0, $pJust = 'right', $pAng = 0, 0); //345

    $slCant = strtoupper(num2letras($flValor, false, 2, 2, " ", " /100"));
//    echo $slCant . " " . $gfValor . " " . $flValor ."<br>";
    $y -=  20; //22
    $rpt->putTextWrap(60, $y, 430, 8, $slCant,  $pLead = 0, $pJust = 'left', $pAng = 0, 0); //10
    $y -= 37;
    $rpt->putTextWrap(18, $y, 300, 9, "Guayaquil,  " . $slFecha,  $pLead = 0, $pJust = 'left', $pAng = 0, 0);//10
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
$rep->view($rep->title, $rep->saveFile("CHEQUE_"));
?>
