<?php
/*
*   CoRtTr_comprob: Comprobante deretencion enlafuente
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
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
    $alSql[] = "SELECT 	ID as ID, idProvFact COD, concat(pro.per_Apellidos, ' ', pro.per_Nombres) NOM,
						date_format(fechaRegistro,'%M %d %y') FEC, year(fechaRegistro) PER,
						ifNULL(pro.per_Ruc,'********') as RUC,
						concat(ifNULL(pro.per_Direccion,' '), '  / Telf:', ifNULL(pro.per_Telefono1,' ')) as DIR,
						concat(establecimiento, ' ', puntoEmision, ' ', secuencial) as  FAC,
						UPPER(tco.tab_Descripcion) as TIP,
						baseImpGrav as BIV, civ.tab_porcentaje as PIV, montoIva as MIV,
						montoIvaBienes as BIB, porRetBienes TIB, prb.tab_porcentaje as PIB, prb.tab_Descripcion AS DIB, valorRetBienes as MIB,
						montoIvaServicios as BIS, porRetServicios TIS, prs.tab_porcentaje as PIS, prs.tab_Descripcion AS DIS, valorRetServicios as MIS,
						BaseImpAir as BIR, cra.tab_porcentaje as PIR, cra.tab_Descripcion AS DIS, valRetAir as MIR,
						valRetAir + valorRetBienes + valorRetServicios as TOT
				FROM fiscompras fco
						LEFT JOIN fistablassri sus ON sus.tab_CodTabla = '3'  AND fco.codSustento +0  = sus.tab_Codigo +0
						LEFT JOIN fistablassri tco ON tco.tab_CodTabla = '2'  AND fco.tipoComprobante +0 = tco.tab_Codigo
						LEFT JOIN fistablassri civ ON civ.tab_CodTabla = '4'  AND fco.porcentajeIva = civ.tab_Codigo
						LEFT JOIN fistablassri pic ON pic.tab_CodTabla = '6'  AND fco.porcentajeIce = pic.tab_Codigo
						LEFT JOIN fistablassri prb ON prb.tab_CodTabla = '5a' AND fco.porRetBienes = prb.tab_Codigo
						LEFT JOIN fistablassri prs ON prs.tab_CodTabla = '5'  AND fco.porRetServicios = prs.tab_Codigo
						LEFT JOIN fistablassri cra ON cra.tab_CodTabla = '10' AND fco.codRetAir = cra.tab_Codigo
						LEFT JOIN fistablassri ccm ON civ.tab_CodTabla = '2'  AND fco.docModificado = ccm.tab_Codigo
						LEFT JOIN fistablassri tra ON tra.tab_CodTabla = 'A'  AND fco.tipoTransac = tra.tab_Codigo
						LEFT JOIN conpersonas  pro ON pro.per_CodAuxiliar = fco.codProv
						LEFT JOIN genparametros par ON par.par_clave= 'TIPID' AND par.par_secuencia = pro.per_tipoID
						LEFT JOIN conpersonas  pv2 ON pv2.per_CodAuxiliar = fco.idProvFact
						LEFT JOIN genparametros pm2 ON pm2.par_clave= 'TIPID' AND pm2.par_secuencia = pv2.per_tipoID
                 WHERE " . $pQry . " AND valRetAir + valorRetBienes + valorRetServicios  > 0";
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
function before_group_ID (&$rpt, &$group) {
    global $db;
    global $ilPag;
    global $gfValor;
    global $ilTipProceso;
    $flConv = 72/2.54;
    $flPaso = 25;
    $y= $rpt->pdf->y-=4.5 * $flConv;
    $rpt->pdf->y-=4.5 * $flConv;
    $rpt->putTextWrap(100, $y, 400, 10, strtoupper($group->lastRec['NOM']),  '', 'left', 0, 0);
    $rpt->putTextWrap(350, $y, 150, 10, strtoupper($group->lastRec['FEC']),  '', 'left', 0, 0);
    $rpt->putTextWrap(550, $y, 150, 10, strtoupper($group->lastRec['PER']),  '', 'left', 0, 0);

    $y-=$flPaso;
    $rpt->putTextWrap(100, $y, 400, 10, strtoupper($group->lastRec['RUC']),  '', 'left', 0, 0);
    $rpt->putTextWrap(350, $y, 650, 10, strtoupper($group->lastRec['DIR']),  '', 'left', 0, 0);

    $y-=$flPaso;
    $rpt->putTextWrap(100, $y, 400, 10, strtoupper($group->lastRec['FAC']),  '', 'left', 0, 0);

    $y-=$flPaso;
    $flC1 = 10;
    $flC2 = 250;
    $flC3 = 400;
    $flC4 = 550;
    $flC5 = 700;
    $ilFont=8;
    if ($group->lastRec['MIB'] >0 ) {
	    $rpt->putTextWrap($flC1, $y, 250, 8, strtoupper($group->lastRec['TIP']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC2, $y, 150, 8, strtoupper($group->lastRec['BIB']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC3, $y, 200, 8, strtoupper($group->lastRec['DIB']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC4, $y, 100, 8, strtoupper($group->lastRec['PIB']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC5, $y, 150, 8, strtoupper($group->lastRec['MIB']),  '', 'left', 0, 0);
    }
    $y-=$flPaso;
    if ($group->lastRec['MIS'] >0 ){
	    $rpt->putTextWrap($flC1, $y, 250,  8, strtoupper($group->lastRec['TIS']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC2, $y, 150,  8, strtoupper($group->lastRec['BIS']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC3, $y, 200,  8, strtoupper($group->lastRec['DIS']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC4, $y, 100,  8, strtoupper($group->lastRec['PIS']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC5, $y, 150,  8, strtoupper($group->lastRec['MIS']),  '', 'left', 0, 0);
    }
    $y-=$flPaso;
    if ($group->lastRec['MIR'] >0 ){
	    $rpt->putTextWrap($flC1, $y, 250,  8, strtoupper($group->lastRec['TIR']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC2, $y, 150,  8, strtoupper($group->lastRec['BIR']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC3, $y, 200,  8, strtoupper($group->lastRec['DIR']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC4, $y, 100,  8, strtoupper($group->lastRec['PIR']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC5, $y, 150,  8, strtoupper($group->lastRec['MIR']),  '', 'left', 0, 0);
    }
    $y-=$flPaso;

	$slTxt ="TOTAL RETENIDO";
	$flValor=$group->lastRec['TOT'];
    $rpt->putTextWrap(210, $y, 400,  8, strtoupper($slTxt),  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
    $rpt->putTextWrap(345, $y, 200,  8, $group->lastRec['TOT'],  $pLead = 0, $pJust = 'right', $pAng = 0, $pTest=0);

    $slCant = strtoupper(num2letras($flValor, false, 2, 2, " US Dolares", " ctvs."));
//    echo $slCant . " " . $gfValor . " " . $flValor ."<br>";
    $y -=  22;
    $rpt->putTextWrap(205, $y, 430,  8, $slCant,  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
    $y -= 30;
    $rpt->putTextWrap(195, $y, 300,  8, "Guayaquil,  " . $slFecha,  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
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
$slQry   = " ID = " . fGetParam('ID', false);
$slSem   = trim(fGetParam('pro_Semana', false));
if (!$slQry) fErrorPage('','DEBE DEFINIR UN CRITERIO DE SELECCION (Semana y/o Proceso)', true,  false);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,28), "portrait", $slFontName, 8);
//$rep->title="LIQUIDACIONDE COMPRA DE FRUTA";
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1,1,0.5);
/**
$rep->colHead = array(
                        'RUB' =>'RUBRO',
//                        'VAP' =>'VAPOR',
                        'PRO' =>'PRODUCTO',
                        'EMP' =>'EMPAQUE',
                        'CAJ' =>'CJAS EMBARC',
                        'PPR'=>'PRE. PROD',
                        'VAL'=>'VALOR');
**/
$rep->rptOpt = array('fontSize'=>10, 'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->printColHead=false;
$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "C");        //      Default column type
//$rep->columns['VAP']->type='C';
$rep->columns['ID']->type='C';
$rep->columns['ID']->zeroes=true;

$rep->columns['ID']->repeat=false;

$rep->columns['ID']->format=false;

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['ID']['justification']='left';

$rep->colOpt['ID']['width']=160;

$rep->addGrp('ID');
    $rep->groups['ID']->fontSize=10;
    $rep->groups['ID']->textCol='PRO';
    $rep->addResumeLine('ID','-', ' ',0);
    $rep->groups['ID']->linesBefore=2;
        $rep->setAggregate('ID',0, 'TOT','S');
$gfValor=0;
$rep->run();
$rep->view($rep->title, $rep->saveFile("LIQ_GEN_"));
?>
