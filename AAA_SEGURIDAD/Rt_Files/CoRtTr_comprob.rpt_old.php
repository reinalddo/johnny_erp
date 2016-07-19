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
function &fDefineQry(&$db, $pQry=false, $pTipo){
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql = Array();
    $alSql[] = "SELECT 	ID as ID, idProvFact COD, concat(pro.per_Apellidos, ' ', pro.per_Nombres) NOM,
						date_format(fechaRegistro,"%M %d %y") FEC, year(fechaRegistro) PER,
						ifNULL(pro.per_Ruc,'********') as RUC,
						concat(ifNULL(pro.per_Direccion,' '), '  / Telf:', ifNULL(pro.per_Telefono1,' ')) as DIR,
						concat(establecimiento, ' ', puntoEmision, ' ', secuencial) as  FAC,
						UPPER(tco.tab_Descripcion) as TIP,
						baseImpGrav as BIV, civ.tab_porcentaje as PIV, montoIva as MIV,
						montoIvaBienes as BIB, prb.tab_porcentaje as PIB, prb.tab_Descripcion AS DIB, valorRetBienes as MIB,
						montoIvaServicios as BIS, prs.tab_porcentaje as PIS, prs.tab_Descripcion AS DIS, valorRetServicios as MIS,
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

*//**
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
    $rpt->pdf->y-=4.5 * $flConv;
    $rpt->putTextWrap(100, $y, 400, 10, strtoupper($rpt_lastRec['NOM']),  '', 'left', 0, 0);
    $rpt->putTextWrap(550, $y, 150, 10, strtoupper($rpt_lastRec['FEC']),  '', 'left', 0, 0);
    $rpt->putTextWrap(550, $y, 150, 10, strtoupper($rpt_lastRec['PER']),  '', 'left', 0, 0);

    $rpt->pdf->y-=$flPaso;
    $rpt->putTextWrap(100, $y, 400, 10, strtoupper($rpt_lastRec['RUC']),  '', 'left', 0, 0);
    $rpt->putTextWrap(550, $y, 650, 10, strtoupper($rpt_lastRec['DIR']),  '', 'left', 0, 0);

    $rpt->pdf->y-=$flPaso;
    $rpt->putTextWrap(100, $y, 400, 10, strtoupper($rpt_lastRec['FAC']),  '', 'left', 0, 0);

    $rpt->pdf->y-=$flPaso;
    $flC1 = 100;
    $flC2 = 350;
    $flC3 = 600;
    $flC4 = 800;
    $flC5 = 1000;
    if ($rpt_lastRec['MIB']) >0 {
	    $rpt->putTextWrap($flC1, $y, 250, 10, strtoupper($rpt_lastRec['TIP']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC2, $y, 150, 10, strtoupper($rpt_lastRec['BIB']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC3, $y, 200, 10, strtoupper($rpt_lastRec['DIB']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC4, $y, 100, 10, strtoupper($rpt_lastRec['PIB']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC5, $y, 150, 10, strtoupper($rpt_lastRec['MIB']),  '', 'left', 0, 0);
    }
    if ($rpt_lastRec['MIS']) >0 {
	    $rpt->putTextWrap($flC1, $y, 250, 10, strtoupper($rpt_lastRec['TIS']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC2, $y, 150, 10, strtoupper($rpt_lastRec['BIS']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC3, $y, 200, 10, strtoupper($rpt_lastRec['DIS']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC4, $y, 100, 10, strtoupper($rpt_lastRec['PIS']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC5, $y, 150, 10, strtoupper($rpt_lastRec['MIS']),  '', 'left', 0, 0);
    }
    if ($rpt_lastRec['MIR']) >0 {
	    $rpt->putTextWrap($flC1, $y, 250, 10, strtoupper($rpt_lastRec['TIR']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC2, $y, 150, 10, strtoupper($rpt_lastRec['BIR']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC3, $y, 200, 10, strtoupper($rpt_lastRec['DIR']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC4, $y, 100, 10, strtoupper($rpt_lastRec['PIR']),  '', 'left', 0, 0);
	    $rpt->putTextWrap($flC5, $y, 150, 10, strtoupper($rpt_lastRec['MIR']),  '', 'left', 0, 0);
    }

    $x=  $rpt->leftBorder;
    $rs = $db->execute("SELECT per_direccion, per_telefono1, per_telefono2, per_ruc FROM conpersonas WHERE per_codauxiliar = " . $group->currValue);
    if ($rs) {
        while (!$rs->EOF) {
            $sltxt= " " . $group->lastRec['NOM'] ;
            $i = (strlen($sltxt) < 109) ? 110 - strlen($sltxt): 1;
            $rs->MoveNext();
        }
    }

    $rpt->putTextWrap(210, $y, 400, 10, strtoupper($sltxt),  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
    $rpt->putTextWrap(345, $y, 200, 12, $flValor,  $pLead = 0, $pJust = 'right', $pAng = 0, $pTest=0);

    $slCant = strtoupper(num2letras($flValor, false, 2, 2, " US Dolares", " ctvs."));
//    echo $slCant . " " . $gfValor . " " . $flValor ."<br>";
    $y -=  22;
    $rpt->putTextWrap(205, $y, 430, 10, $slCant,  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
    $y -= 30;
    $rpt->putTextWrap(195, $y, 300, 10, "Guayaquil,  " . $slFecha,  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
//    $group->lastRec['NOM'];
    $y = $rpt->pdf->y -30;

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
error_reporting(E_ALL);
$ilPag = 1;
$ilTipProceso= fGetParam('pTipo', 20);//                    Tipo de proceso, si no se viene en GET, el tipo es 20
$slQry   = fGetParam('pQryLiq', false);
$slSem   = trim(fGetParam('pro_Semana', false));
if (!$slQry) fErrorPage('','DEBE DEFINIR UN CRITERIO DE SELECCION (Semana y/o Proceso)', true,  false);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry, $ilTipProceso );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,28), "portrait", $slFontName, 10);
//$rep->title="LIQUIDACIONDE COMPRA DE FRUTA";
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1,1,0.5);
$rep->colHead = array(
                        'RUB' =>'RUBRO',
//                        'VAP' =>'VAPOR',
                        'PRO' =>'PRODUCTO',
                        'EMP' =>'EMPAQUE',
                        'CAJ' =>'CJAS EMBARC',
                        'PPR'=>'PRE. PROD',
                        'VAL'=>'VALOR');
$rep->rptOpt = array('fontSize'=>10, 'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->printColHead=false;
$rep->setDefaultColPro('format', "9:2:,:."); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "C");        //      Default column type
$rep->columns['RUB']->type='C';
//$rep->columns['VAP']->type='C';
$rep->columns['PRO']->type='C';
$rep->columns['EMP']->type='C';
$rep->columns['CAJ']->type='N';
$rep->columns['PPR']->type='N';
$rep->columns['VAL']->type='N';
$rep->columns['VAL']->zeroes=true;

$rep->columns['EMP']->repeat=1;
$rep->columns['CAJ']->repeat=1;
$rep->columns['PPR']->repeat=1;
$rep->columns['VAL']->repeat=1;


$rep->columns['RUB']->repeat=false;
//$rep->columns['VAP']->repeat=false;
$rep->columns['PRO']->repeat=false;
//$rep->columns['EMP']->repeat=false;
//$rep->columns['CAJ']->repeat=false;
//$rep->columns['PPR']->repeat=false;

$rep->columns['RUB']->format=false;
//$rep->columns['VAP']->format=false;
$rep->columns['PRO']->format=false;
$rep->columns['EMP']->format=false;
$rep->columns['CAJ']->format="5:0::";
$rep->columns['PPR']->format="6:4:.:,";
$rep->columns['VAL']->format="9:2:.:,:-o-";

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['CAJ']['justification']='right';
$rep->colOpt['PPR']['justification']='right';
$rep->colOpt['VAL']['justification']='right';

$rep->colOpt['RUB']['width']=160;
//$rep->colOpt['VAP']['width']=90;
$rep->colOpt['PRO']['width']=110;
$rep->colOpt['EMP']['width']=40;
$rep->colOpt['CAJ']['width']=85;
$rep->colOpt['PPR']['width']=35;
$rep->colOpt['VAL']['width']=80;

$rep->addGrp('COD');
    $rep->groups['COD']->fontSize=10;
    $rep->groups['COD']->textCol='PRO';
    $rep->addResumeLine('COD','-', 'VALOR NETO (s.e.u.o.)',0);
    $rep->groups['COD']->linesBefore=2;
        $rep->setAggregate('COD',0, 'VAL','S');
$rep->addGrp('GRU');                         // Create a group for column GRU
    $rep->groups['GRU']->fontSize=10;
    $rep->groups['GRU']->textCol='PRO';
    $rep->addResumeLine('GRU','-', 'SUBTOTAL:',0);
        $rep->setAggregate('GRU',0, 'VAL','S');
$gfValor=0;
$rep->run();
$rep->view($rep->title, $rep->saveFile("LIQ_GEN_"));
?>
