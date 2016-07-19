<?php
/*
*   CoTrTr_comprob.rpt.php: Impresion de Comprobantes contables
*   @author     Johnny Valencia
*   @param      string		pQryCom  Condici� de bsqueda
*   @output     contenido pdf del reporte.
*   @rev	Jul/01/2013	Correcion para Mysql 5, tablas temporales con decimales
*/
  // DEPURACION DIEGO ROMERO
//ini_set("display_errors",1);
//
error_reporting(E_ALL);
//set_error_handler("repErrorhandler");
include("../LibPhp/ezPdfReport.php");
include("../LibPhp/GenCifras.php");
include("../LibPhp/ConTasas.php");
//Diego Romero convertir numeros a letras
include '../LibPhp/NumbWordter.php';
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
                    com_RegNumero    AS 'REG',
                    det_secuencia    AS 'SEC',
                    com_TipoComp     AS 'TIP',
                    com_NumComp      AS 'COM',
                    com_FecTrans     AS 'FTR',
                    com_FecContab    AS 'FCO',
                    com_FecVencim	 AS 'FVE',
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
                    if(act_IceFlag, 0000000000.00, det_ValTotal )               AS 'NIC',
                    com_RefOperat	 AS 'SEM',
                    com_embarque		 AS 'EMB',
                    com_payment		 AS 'PAY',
                    com_business		 AS 'BUS',
                    com_beneficiary  AS 'BEN',
                    com_kgsneto		 AS 'KGN',
                    com_kgsbruto 	 AS 'KGB',
                    (com_kgsneto * det_CantEquivale)									 AS 'TKN',
                    (com_kgsbruto * det_CantEquivale)									 AS 'TKB',
                    vor_CodPuerto	 AS 'PTO',
                    vor_Destino		 AS 'PTD'
                 FROM concomprobantes JOIN invdetalle on (det_RegNumero = com_RegNumero)
                    LEFT JOIN conactivos  ON (act_CodAuxiliar = det_CodItem)
                    LEFT JOIN genunmedida ON uni_CodUnidad = det_UniMedida
                    LEFT JOIN vop_ordenes ON vor_ID = com_embarque
                    ";
    $alSql[0] .= ($pQry ? " WHERE " . $pQry  : " " ) . " ORDER BY 1, 2";
    $rs= fSQL($db, $alSql);
    if (!$rs) die("NO SE EJECUTO LA CONSULTA: " . $alSql[0]);
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
//    include_once ("RptHeader.inc.php");  // JVL imprime en la cabecera los datos de la compañia a la que se logeo

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
    if (!$firstComp)  $rpt->pdf->NewPage(); // Salto de pagina antes de cada comprobante
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
//    $rst = $db->Execute("SELECT * FROM conpersonas WHERE per_codAuxiliar = " . $group->lastRec['BEN'] );
//    $rsB = $rst->FetchNextObject(); //          datos del beneficiario
    $slTxt = $rsE->PER_CIUDAD . ", " . $group->lastRec['FCO'];
    $rpt->pdf->ezSetDy(-10);

//------------------------
    //REDIRECCION A LA CARPÉTA SMART. DIEGO ROMERO 
    include ("../../SMART/ExtHeader2.inc.php");
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
//    $rpt->pdf->ezrRoundRectangle($x, 25, 545, 50, 30);   // JVL Rectangulo de Pie de pagina elabora, aprueba, etc.
    $x+=10;
    for ($i=0; $i<3; $i++) {
//        $rpt->pdf->line($x, $y1, $x+$l , $y1);   // JVL Lineas de pie de pagina elabora, aprueba, etc.
        $x+=10+$l;
    }

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
    $flW = 108;
    $flL = 130;
    $ilY = $rpt->pdf->y;
    $rpt->pdf->ezSetDy(-10);
    if ($cla->CLA_IMPFLAG) { //                         Si la Transaccion se aplica  a impuestos
        $slLabel= "VALOR GRABADO CON IVA " . $flTasaIva . "%";
        $y=$ilY;
        // Diego Romero- se quito la linea
       // $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format($group->sums['IIV'], 2, '.', ','),-10), 
       //                         $slLabel, $flW, $flL,0,0,0,'right');
        $rpt->pdf->ezSetDy(-15);
        $flValorIva = ($group->sums['IIV'] * $flTasaIva) / 100;
        $slLabel= "VAT " ;
        $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format($flValorIva, 2, '.', ','), -10), 
                                      $slLabel, $flW, $flL,0,0,0,'right');
        $rpt->pdf->ezSetDy(-15);
        $slLabel= "VALOR GRABADO CON IVA  0%";
         // Diego Romero- se quito la linea
        //$flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format($group->sums['NIV'], 2, '.', ','),-10), 
        //                              $slLabel, $flW, $flL,0,0,0,'right');
        $flValorFin = $group->sums['IIV'] + $group->sums['NIV'] + $flValorIva;
        
        $rpt->pdf->ezSetDy(-15);
        $slLabel= "TOTAL AMOUNT";
        $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format($flValorFin, 2, '.', ','),-10), 
                                      $slLabel, $flW, $flL, 0,0,0,'right' );
    }
    else {
        $slLabel= "SUB TOTAL  " ;
        $y=$ilY;
        $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format(abs($group->sums['IIV']), 2, '.', ','),-10), 
                              $slLabel, $flW, $flL,0,0,0,'right');
        
        $rpt->pdf->ezSetDy(-15);
        $flValorIva = ($group->sums['IIV'] * $flTasaIva) / 100;
        $slLabel= "VAT " ;
        $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format($flValorIva, 2, '.', ','), -10), 
                                      $slLabel, $flW, $flL,0,0,0,'right');

        $rpt->pdf->ezSetDy(-15);
        $flValorFin = $group->sums['IIV'] + $group->sums['NIV'] + $flValorIva;
        $rpt->pdf->ezSetDy(-15);
        $slLabel= "TOTAL AMOUNT";
        $flDy = $rpt->putTextAndLabel($ilX, $rpt->pdf->y, substr(number_format(abs($flValorFin), 2, '.', ','),-10), 
                                      $slLabel, $flW, $flL, 0,0,0,'right' );
    }
    //$slVfe = strtoupper(substr(strstr($group->lastRec['CON'],'#VFE') . " US DOLLARS",5));
       // Diego Romero- se agrega la clase NumbWordter
        $myConverter = new NumbWordter(); //initialize


    
    $valor_entero = floor($flValorFin);

	$centavos = round(($flValorFin - $valor_entero)*100,0);
	$text_centavos='';
	if  ( $centavos!=0){ " with " .$text_centavos=$centavos."/100";}
 
    $valor_word=$myConverter->convert($valor_entero);

    $slVfe = strtoupper($valor_word .' '.$text_centavos."  US DOLLARS");
	// fin modif Diego Romero
    $slCon = strtoupper(strstr($group->lastRec['CON'],'#VFE',TRUE));
    $slLabel= "ARE:";
    $flDy = $rpt->putTextAndLabel($rpt->leftBorder + 43 , $ilY + 45 , $slCon , '.', 180, 30, 0,0,0,'left' );
    $flDy = $rpt->putTextAndLabel($rpt->leftBorder + 5 , $ilY -30 , $slVfe , $slLabel, 180, 30, 0,0,0,'left' );
//    $flDy = $rpt->putTextAndLabel($rpt->leftBorder + 5 , $ilY -25 , num2letras(abs($flValorFin), false, 2, 2, " US Dolares", " ctvs."), $slLabel, 180, 30, 0,0,0,'left' );
//  Cuadros
    $rpt->pdf->ezSetDy(-4);
    $rpt->pdf->Rectangle($rpt->leftBorder,$rpt->pdf->y , 480, $ilY - $rpt->pdf->y);
    $rpt->pdf->line(299, $ilY, 299 , $rpt->pdf->y);
//  Comentario
    $rpt->pdf->ezSetDy(-20);
    if (strlen($group->lastRec['CON']>2)) $flDy = $rpt->putTextAndLabel($rpt->leftBorder + 30 , $rpt->pdf->y , $group->lastRec['CON'], 'NOTA: ', 412, 40, 0,0,0,'left' );
    
    $x = $rpt->pdf->ez['leftMargin'] + 20;
//    $rpt->pdf->addText($x+95,30,8,"Contabilidad",$angle=0,$wordSpaceAdjust=0);
// JVL se subio $iYY de 83 a 100 para que exista distancia entre el cuadro de detalle y datos de pago
    $iYY = 100;
    $rpt->pdf->addText($x , $ilY - 90,10, "BANK DETAILS");
    $rpt->pdf->addText($x,$ilY - $iYY,10,"PLEASE MAKE PAYMENT TO:",$angle=0,$wordSpaceAdjust=0);
//    Bloque de Beneficiario
	// $blSql = Array();
	 $slSql = "SELECT vcf_descripcion AS 'BND', iab_valortex AS 'BNV' 
				FROM `genvariables` LEFT JOIN `genvarconfig` ON vcf_id = iab_VariableID WHERE iab_ObjetoID = " . $group->lastRec['BEN'] . " ";
//    	$iYY +=10;
//$rpt->pdf->addText($x+40,$ilY-$iYY,6,$blSql ,$angle=0,$wordSpaceAdjust=0);

    $rst = $db->Execute ($slSql);

///*
     
    while(!$rst->EOF) {
    	$rec = $rst->FetchNextObject();
    	$benedes = $rec->BND;
    	$beneval = $rec->BNV;
    	$iYY +=10;
	   $rpt->pdf->addText($x+5,$ilY-$iYY,8,$benedes . " : ",$angle=0,$wordSpaceAdjust=0);
	   $rpt->pdf->addText($x+60,$ilY-$iYY,8,$beneval ,$angle=0,$wordSpaceAdjust=0);
    	
    	 }
//*/    
    
    $rpt->pdf->addText($x+120,35,7,"The funds that correspond to this invoice must be on our account by " . $group->lastRec['FVE'],$angle=0,$wordSpaceAdjust=2);
    $rpt->pdf->addText($x+125,30,7,"** Please note that the amount is payable net of all bank charges **",$angle=0,$wordSpaceAdjust=2);
//    $rpt->pdf->addText($x+190,30,8,"Gerencia",$angle=0,$wordSpaceAdjust=0);
//    $rpt->pdf->line($x+380, $y1, $x+475 , $y1);
//    $rpt->pdf->addText($x+400,30,8,"  CLIENTE",$angle=0,$wordSpaceAdjust=0);
 
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
$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);      // JVL se descomento
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21,29.7), "portrait", $slFontName, 8);    // JVL Tamaño y Orientacion de la hoja
$rep->title=false;
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>8 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1,1,.5);
$rep->colHead = array(   
                        'CIT' =>'CODE',
                        'ITE' =>'DESCRIPTION', 
                        'UNI' =>'MEASURE',
                        //AGREGADO PESO NETO Y BRUTO DIEGO ROMERO
                        //'KGB' =>'GROSS WEIGHT',
                        //'KGN' =>'NET WEIGHT',
                        //                    
                        'CAN' =>'QUANTITY',
                        'PUN' =>'PRICE ',
                        'VAL' =>'SUBTOTAL USD'
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
//AGREGADO PESO NETO Y BRUTO DIEGO ROMERO
//$rep->columns['KGB']->type='N';
//$rep->columns['KGN']->type='N';
//
$rep->columns['CAN']->type='N';
$rep->columns['PUN']->type='N';
$rep->columns['VAL']->type='N';

$rep->columns['CIT']->repeat=false;
$rep->columns['ITE']->repeat=false;
$rep->columns['UNI']->repeat=false;
//AGREGADO PESO NETO Y BRUTO DIEGO ROMERO
//$rep->columns['KGB']->repeat=true;
//$rep->columns['KGN']->repeat=true;
//
$rep->columns['CAN']->repeat=true;
$rep->columns['PUN']->repeat=true;
$rep->columns['VAL']->repeat=true;

$rep->columns['CIT']->repeat=false;
$rep->columns['ITE']->repeat=false;
$rep->columns['UNI']->repeat=false;
//AGREGADO PESO NETO Y BRUTO DIEGO ROMERO
//$rep->columns['KGB']->format="8:2:,:.";
//$rep->columns['KGN']->format="8:2:,:.";
//
$rep->columns['CAN']->format="8:2:,:.";
$rep->columns['PUN']->format="8:2:,:.";
$rep->columns['VAL']->format="12:2:,:.";

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['UNI']['justification']='center';
$rep->colOpt['CAN']['justification']='right';
$rep->colOpt['PUN']['justification']='right';
$rep->colOpt['VAL']['justification']='right';
//AGREGADO PESO NETO Y BRUTO DIEGO ROMERO
//$rep->colOpt['KGB']['justification']='right';
//$rep->colOpt['KGN']['justification']='right';
//


$rep->colOpt['CIT']['width']=60;
$rep->colOpt['ITE']['width']=180;
$rep->colOpt['UNI']['width']=50;
//AGREGADO PESO NETO Y BRUTO DIEGO ROMERO
//$rep->colOpt['KGB']['width']=40;
//$rep->colOpt['KGN']['width']=40;
//
$rep->colOpt['CAN']['width']=65;
$rep->colOpt['PUN']['width']=50;
$rep->colOpt['VAL']['width']=70;

//$rep->addGrp('general');                           // Not required, exist by default
$rep->addGrp('COM');
    $rep->groups['COM']->fontSize=8;
    $rep->groups['COM']->textCol='ITE';
    $rep->addResumeLine('COM','-', 'S U B T O T A L  (U.S. Dollars) :',0);
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
$rep->view($rep->title, $rep->saveFile("FAC_EXT_"));
//$rep->preView()
?>