<?php
/*
*   CoTrTr_comprob.rpt.php: Impresion de Comprobantes contables
*   @author     Fausto Astudillo
*   @param      string		pQryCom  Condici� de bsqueda
*   @output     contenido pdf del reporte.
*   @rev	Jun/12/07	Correcion para Mysql 5, tablas temporales con decimales
*/
error_reporting(E_ALL);
//set_error_handler("repErrorhandler");
include("../LibPhp/ezPdfReport.php");
include("../LibPhp/GenCifras.php");
include("../LibPhp/ConTasas.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db, $pQry=false){
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
//
    $alSql[] = "SELECT
                    com_RegNumero     AS 'REG',
                    det_secuencia    AS 'SEC',
                    com_TipoComp     AS 'TIP',
                    com_NumComp      AS 'COM',
                    com_FecTrans     AS 'FTR',
                    com_FecContab    AS 'FCO',
                    com_Emisor       AS 'CEM',
                    com_CodReceptor  AS 'CRE',
                    com_Receptor     AS 'REC',
                    com_Concepto     AS 'CON',
                    com_TsaImpuestos AS 'IMP',
                    det_CodItem      AS 'CIT',
                    concat(act_descripcion, ' ', ifnull(act_descripcion1,' ')) AS 'ITE',
                    uni_Abreviatura  AS 'UNI',
                    det_CantEquivale AS 'CAN',
                    det_ValUnitario  AS 'PUN',
                    det_ValTotal     AS 'VAL',
                    if(act_IvaFlag, det_ValTotal * act_IvaFlag, 0000000000.00)  AS 'IIV',
                    if(act_IvaFlag, 0000000000.00, det_ValTotal)                AS 'NIV',
                    if(act_IceFlag, det_ValTotal * act_IceFlag, 0000000000.00)  AS 'IIC',
                    if(act_IceFlag, 0000000000.00, det_ValTotal )               AS 'NIC'
                 FROM concomprobantes JOIN invdetalle on (det_RegNumero = com_RegNumero)
                    LEFT JOIN conactivos  ON (act_CodAuxiliar = det_CodItem)
                    LEFT JOIN genunmedida ON uni_CodUnidad = det_UniMedida
                    ";
    $alSql[0] .= ($pQry ? " WHERE " . $pQry  : " " ) . " ORDER BY 1, 2";
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
    global $cla;
    if (!$firstComp)  $rpt->pdf->NewPage(); // Salto de pagna antes de cada comprobante
    $firstComp=false;
    $ilTxtSize=$rpt->fontSize; 
    $ilLeading=0;
    $rpt->pdf->ezSetY ($rpt->pdf->ez['pageHeight']- $rpt->pdf->ez['topMargin']);
//    $rpt->pdf->ezSetDy(-85);
    $ilY= $rpt->pdf->y;
//------------------------
    $rst = $db->Execute("SELECT * FROM genclasetran WHERE cla_aplicacion = 'IN' AND cla_tipocomp = '" . $group->lastRec['TIP'] . "'");
    $rec = $rst->FetchNextObject(); //          datos del tipo de transaccion
    $cla = $rec;
    $rst = $db->Execute("SELECT * FROM conpersonas WHERE per_codAuxiliar = " . $group->lastRec['CEM'] );
    $rsE = $rst->FetchNextObject(); //          datos del Emisor
    $rst = $db->Execute("SELECT * FROM conpersonas WHERE per_codAuxiliar = " . $group->lastRec['CRE'] );
    $rsR = $rst->FetchNextObject(); //          datos del Receptor
    $slTxt = $rsE->PER_CIUDAD . ", " . $group->lastRec['FCO'];
    $rpt->pdf->ezSetDy(-10);
    /**
    $rpt->putTextAndLabel($rpt->leftBorder + 5 , $rpt->pdf->y , $slTxt, 'FECHA: ', 250, 65, 0,0,0,'left' );
    $slTxt = $rsR->PER_APELLIDOS . " " . $rsR->PER_NOMBRES;
    $rpt->pdf->ezSetDy(-20);
    $rpt->putTextAndLabel($rpt->leftBorder + 5 , $rpt->pdf->y , $slTxt, 'CLIENTE: ', 250, 65, 0,0,0,'left' );
    $slTxt = $rsR->PER_RUC;
    $rpt->putTextAndLabel($rpt->leftBorder + 350 , $rpt->pdf->y , $slTxt, 'RUC/CI: ', 80, 45, 0,0,0,'left' );
    $rpt->pdf->ezSetDy(-15);
    $slTxt = $rsR->PER_CIUDAD . ", " . $rsR->PER_DIRECCION;
    $rpt->putTextAndLabel($rpt->leftBorder + 5 , $rpt->pdf->y , $slTxt, 'DIRECCION: ', 250, 65, 0,0,0,'left' );
    $slTxt = $rsR->PER_TELEFONO1;
    $rpt->putTextAndLabel($rpt->leftBorder + 350 , $rpt->pdf->y , $slTxt, 'TELEF.: ', 80, 45, 0,0,0,'left' );
    $rpt->pdf->ezSetDy(-18);
    $ilX = $rpt->leftBorder;
    $ilAlto = $ilY - $rpt->pdf->y;
    $rpt->pdf->ezrRoundRectangle($ilX-5, $rpt->pdf->y, 500, $ilAlto,20);
    $ilY = $rpt->pdf->y;
    $rpt->pdf->ezSetDy(-12);
    **/
//------------------------
    include ("ComHeader2.inc.php");
    $rpt->rptOpt['onlyHeader']=true;
    $rpt->rptOpt['showHeadings']=2;
    $rpt->rptOpt['showLines']=3;
    $alBlankLine=array();
    $alBlankline[]['COM']=' ';
//    $rpt->rptOpt['xPos']=$rpt->pdf->ez['leftMargin'];
    $rpt->pdf->ezTable($alBlankLine,$rpt->colHead,'', $rpt->rptOpt);//    computes de table header height
    $rpt->rptOpt['onlyHeader']=false;
    $rpt->rptOpt['showHeadings']=0;
    $rpt->rptOpt['showLines']=0;
/**/
}

/**
*   Al termino de procesar cada factura
*/
function after_group_COM (&$rpt, &$group) {
    global $db;
    global $gfValor;
    global $cla;
    $rpt->pdf->ezSetDy(-30);
    $y=$rpt->pdf->y;
    $rpt->putTextWrap(100, $y, 412, $group->lastRec['CON']);
//    $rpt->pdf->setLineStyle(1,'square');
    $x = $rpt->pdf->ez['leftMargin'];
    $y1 = 40;
    $l=80;
    $rpt->pdf->ezrRoundRectangle($x, 25, 545, 50, 30);
    $x+=10;
    for ($i=0; $i<3; $i++) {
        $rpt->pdf->line($x, $y1, $x+$l , $y1);
        $x+=10+$l;
    }
// Bloque de Totales, Impuestos
/* {
    $slSql = "SELECT tsd_Rubro as 'RUBRO', tsd_Secuencia, tsd_porcentajeBI AS 'TASA'
                FROM gentasacabecera  LEFT JOIN  gentasadetalle  ON tsd_id = tsc_id
                WHERE tsc_id = " . $group->lastRec['IMP'] . " AND tsd_Rubro > 0  ORDER BY tsd_Rubro, tsd_Secuencia";
    $rst = $db->Execute($slSql);
    $flTasaIva=0;
    $flTasaIce=0;
    while (!$rst->EOF) {
            $rec = $rst->FetchNextObject(); //          datos del tipo de transaccion
            switch ($rec->RUBRO) {
                Case 1:
                    $flTasaIva = $rec->TASA;
                    break;
                Case 2:
                    $flTasaIce = $rec->TASA;
                    break;
            }
    }
} */

    $flTasaIva = 0;
    $flTasaIce = 0;
    if ($cla->CLA_IMPFLAG) { //                         Si la Transaccion se aplica  a impuestos
        $alTasas=fTraeTasa($db, $group->lastRec['IMP']);
        if ($alTasas && is_array($alTasas)) {
            $flTasaIva = $alTasas['iva'];
            $flTasaIce = $alTasas['ice'];
        }
    }
    $ilAlto=0;
    $ilX = 300;
    $flW = 80;
    $flL = 175;
    $ilY = $rpt->pdf->y;
    $rpt->pdf->ezSetDy(-10);
    if ($cla->CLA_IMPFLAG) { //                         Si la Transaccion se aplica  a impuestos
        $slLabel= "VALOR GRABADO CON IVA " . $flTasaIva . "%";
        $y=$ilY;
        $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format($group->sums['IIV'], 2, '.', ','),-10), $slLabel, $flW, $flL,0,0,0,'right');
        $rpt->pdf->ezSetDy(-15);
        $flValorIva = ($group->sums['IIV'] * $flTasaIva) / 100;
        $slLabel= "IMPORTE DE IVA " ;
        $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format($flValorIva, 2, '.', ','), -10), $slLabel, $flW, $flL,0,0,0,'right');
        $rpt->pdf->ezSetDy(-15);
        $slLabel= "VALOR GRABADO CON IVA  0%";
        $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format($group->sums['NIV'], 2, '.', ','),-10), $slLabel, $flW, $flL,0,0,0,'right');
        $flValorFin = $group->sums['IIV'] + $group->sums['NIV'] + $flValorIva;
        $rpt->pdf->ezSetDy(-15);
        $slLabel= "VALOR  TOTAL";
        $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format($flValorFin, 2, '.', ','),-10), $slLabel, $flW, $flL, 0,0,0,'right' );
    }
    else {
        $slLabel= "SUB TOTAL  " ;
        $y=$ilY;
        $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format(abs($group->sums['IIV']), 2, '.', ','),-10), $slLabel, $flW, $flL,0,0,0,'right');
        $rpt->pdf->ezSetDy(-15);
        $flValorIva = ($group->sums['IIV'] * $flTasaIva) / 100;
        $slLabel= "IMPORTE DE IVA " ;
        $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format($flValorIva, 2, '.', ','), -10), $slLabel, $flW, $flL,0,0,0,'right');
        $rpt->pdf->ezSetDy(-15);
        $flValorFin = $group->sums['IIV'] + $group->sums['NIV'] + $flValorIva;
        $rpt->pdf->ezSetDy(-15);
        $slLabel= "VALOR  TOTAL";
        $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format(abs($flValorFin), 2, '.', ','),-10), $slLabel, $flW, $flL, 0,0,0,'right' );
    }
    
    $slLabel= "SON:";
    $flDy = $rpt->putTextAndLabel($rpt->leftBorder + 5 , $ilY -25 , num2letras(abs($flValorFin), false, 2, 2, " US Dolares", " ctvs."), $slLabel, 180, 30, 0,0,0,'left' );
//  Cuadros
    $rpt->pdf->ezSetDy(-4);
    $rpt->pdf->Rectangle($rpt->leftBorder,$rpt->pdf->y , 500, $ilY - $rpt->pdf->y);
    $rpt->pdf->line(299, $ilY, 299 , $rpt->pdf->y);
//  Comentario
    $rpt->pdf->ezSetDy(-20);
    if (strlen($group->lastRec['CON']>2)) $flDy = $rpt->putTextAndLabel($rpt->leftBorder + 30 , $rpt->pdf->y , $group->lastRec['CON'], 'NOTA: ', 412, 40, 0,0,0,'left' );
//  Bloque de autorizaciones y firmas
    $x = $rpt->pdf->ez['leftMargin'] + 20;
    $rpt->pdf->addText($x,30,8,"Emitido Por",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->addText($x+95,30,8,"Contabilidad",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->addText($x+190,30,8,"Gerencia",$angle=0,$wordSpaceAdjust=0);
    $rpt->pdf->line($x+380, $y1, $x+475 , $y1);
    $rpt->pdf->addText($x+400,30,8,"  CLIENTE",$angle=0,$wordSpaceAdjust=0);

}
function repErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
    fErrorPage('',$errmsg . "<br>(" . $filename . ", linea: " . $linenum . ")", true, false);
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$ilPag = 1;
$slQry   = fGetParam('pQryCom', false);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
$cla = NULL;
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,14), "portrait", $slFontName, 10);
$rep->title=false;
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>8 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1,1,.5);
$rep->colHead = array(
                        'CIT' =>'CODIGO',
                        'ITE' =>'DESCRIPCION',
                        'UNI' =>'UNIDAD MEDIDA',
                        'CAN' =>'CANTIDAD',
                        'PUN' =>'PRECIO UNITARIO',
                        'VAL' =>'VALOR USD'
                        );
//$xPos=$rep->pdf->ez['leftMargin'];
$xPos=$rep->leftBorder;
//$rep->rptOpt = array('fontSize'=>10, 'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
//                     'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->rptOpt = array('fontSize'=>8,  'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
                     'showLines'=>2, 'rowGap'=>0, 'maxWidth'=>990,  'xOrientation'=>'center');
//, "xOrientation"=>"centre"
$rep->printColHead=false;
$rep->setDefaultColPro('format', false); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->columns['CIT']->type='C';
$rep->columns['ITE']->type='C';
$rep->columns['UNI']->type='C';
$rep->columns['CAN']->type='N';
$rep->columns['PUN']->type='N';
$rep->columns['VAL']->type='N';

$rep->columns['CIT']->repeat=false;
$rep->columns['ITE']->repeat=false;
$rep->columns['UNI']->repeat=false;
$rep->columns['CAN']->repeat=true;
$rep->columns['PUN']->repeat=true;
$rep->columns['VAL']->repeat=true;

$rep->columns['CIT']->repeat=false;
$rep->columns['ITE']->repeat=false;
$rep->columns['UNI']->repeat=false;
$rep->columns['CAN']->format="8:2:,:.";
$rep->columns['PUN']->format="8:2:,:.";
$rep->columns['VAL']->format="12:2:,:.";

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['UNI']['justification']='center';
$rep->colOpt['CAN']['justification']='right';
$rep->colOpt['PUN']['justification']='right';
$rep->colOpt['VAL']['justification']='right';

$rep->colOpt['CIT']['width']=80;
$rep->colOpt['ITE']['width']=160;
$rep->colOpt['UNI']['width']=50;
$rep->colOpt['CAN']['width']=65;
$rep->colOpt['PUN']['width']=65;
$rep->colOpt['VAL']['width']=80;

//$rep->addGrp('general');                           // Not required, exist by default
$rep->addGrp('COM');
    $rep->groups['COM']->fontSize=10;
    $rep->groups['COM']->textCol='ITE';
    $rep->addResumeLine('COM','-', 'S U B T O T A L  (U.S. Dolares) :',0);
    $rep->groups['COM']->linesBefore=4;
        $rep->setAggregate('COM',0, 'VAL','S');
        $rep->setAggregate('COM',0, 'IIV','S');
        $rep->setAggregate('COM',0, 'NIV','S');
        $rep->setAggregate('COM',0, 'IIC','S');
        $rep->setAggregate('COM',0, 'NIC','S');

$gfValor=0;
$firstComp=true;
$rep->run();
//die();
$rep->view($rep->title, $rep->saveFile("FAC_COM_"));
//$rep->preView()
?>
