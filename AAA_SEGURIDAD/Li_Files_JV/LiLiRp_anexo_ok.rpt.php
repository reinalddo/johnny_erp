<?php
/*
*   LiLiRp_liquid.php: Liquidacion de Compra de Fruta
*   @author     Fausto Astudillo
*   @param      string		pQryLiq  Condici� de bsqueda
*   @output     contenido pdf del reporte.
*   @rev	Jun/12/07	Correccion para MySQl 5:  Longitud de campos en tablas temporales 
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
    global $ilNumProceso;
    $pProc = "tad_liqproceso = ". $ilNumProceso; //                  Condicion de Proceso
    $ilSemana = fGetParam('pSem', 0);
    
    $alSql = Array();
    
    $alSql[] ="DROP TABLE IF EXISTS tmp_productor";

    $alSql[] ="CREATE TEMPORARY TABLE tmp_productor
               SELECT DISTINCT  tac_embarcador as tmp_codproductor,
                         concat(per_Apellidos, ' ',  per_nombres) as tmp_nombre,
                         tad_liqNUmero, pro_ID, pro_semana
                    FROM liqprocesos JOIN liqtarjadetal ON tad_liqProceso = pro_ID
                         JOIN liqtarjacabec on tar_numtarja = tad_numTarja
                         JOIN conpersonas on per_codauxiliar = tac_embarcador
                    WHERE " . $pQry . " ORDER BY 2";
                    
    $alSql[] =  "CREATE TEMPORARY TABLE tmp_data
                SELECT tmp_nombre AS NOM, com_CodReceptor AS PRO, pro_semana AS SEM,
                    act_grupo AS GRU,
                    concat(det_CodItem, '  ', left(act_descripcion,18), ' ',left(act_descripcion1,10)) AS DIT,
                    SUM(det_CantEquivale * pro_Signo) AS 'C01',
                    SUM(det_ValTotal * pro_Signo)     AS 'V01',
                    000000000.0000 AS 'C02',
                    000000000.0000 AS 'V02',
                    000000000.0000 AS 'C03',
                    000000000.0000 AS 'V03',
                    000000000.0000 AS 'C04',
                    000000000.0000 AS 'V04',
                    000000000.0000 AS 'C05',
                    000000000.0000 AS 'V05',
                    000000000.0000 AS 'C06',
                    000000000.0000 AS 'V06',
                    000000000.0000 AS 'C07',
                    000000000.0000 AS 'V07',
                    SUM(det_CantEquivale * pro_Signo) AS 'CXX',
                    SUM(det_ValTotal * pro_Signo)     AS 'VXX'
              FROM (((((invprocesos  join concomprobantes ON com_TipoComp = cla_TipoTransacc)
                     JOIN tmp_productor ON tmp_codProductor = com_CodReceptor)
                     JOIN invdetalle ON det_RegNumero = com_RegNumero)
                     JOIN conactivos ON conactivos.act_Codauxiliar = det_CodItem)
                     LEFT JOIN invprecios ON  invprecios.pre_CodItem = det_CodItem)
              WHERE pro_codProceso = 5   AND com_RefOperat  < pro_Semana AND det_cantEquivale  <> 0
              GROUP BY 1,2,3,4,5  ORDER BY 1,2,3 ";
                    
                    
                    
    $alSql[] ="INSERT INTO tmp_data
                SELECT tmp_nombre AS NOM, com_CodReceptor AS PRO, pro_semana AS SEM,
                    act_grupo AS GRU,
                    concat(det_CodItem, '  ', left(act_descripcion,18), ' ',left(act_descripcion1,10)) AS DIT,
                    SUM(CASE WHEN  pro_orden = 1 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C01',
                    SUM(CASE WHEN  pro_orden = 1 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V01',
                    SUM(CASE WHEN  pro_orden = 2 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C02',
                    SUM(CASE WHEN  pro_orden = 2 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V02',
                    SUM(CASE WHEN  pro_orden = 3 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C03',
                    SUM(CASE WHEN  pro_orden = 3 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V03',
                    SUM(CASE WHEN  pro_orden = 4 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C04',
                    SUM(CASE WHEN  pro_orden = 4 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V04',
                    SUM(CASE WHEN  pro_orden = 5 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C05',
                    SUM(CASE WHEN  pro_orden = 5 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V05',
                    SUM(CASE WHEN  pro_orden = 6 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C06',
                    SUM(CASE WHEN  pro_orden = 6 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V06',
                    SUM(CASE WHEN  pro_orden = 7 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C07',
                    SUM(CASE WHEN  pro_orden = 7 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V07',
                    SUM(det_CantEquivale * pro_Signo)   AS 'CXX',
                    SUM(det_ValTotal * pro_Signo)       AS 'VXX'
              FROM (((((invprocesos  join concomprobantes ON com_TipoComp = cla_TipoTransacc)
                     JOIN tmp_productor ON tmp_codProductor = com_CodReceptor)
                     JOIN invdetalle ON det_RegNumero = com_RegNumero)
                     JOIN conactivos ON conactivos.act_Codauxiliar = det_CodItem)
                     LEFT JOIN invprecios ON  invprecios.pre_CodItem = det_CodItem)
              WHERE pro_codProceso = 5   AND com_RefOperat  = pro_Semana AND det_cantEquivale  <> 0
              GROUP BY 1,2,3,4,5 ORDER BY 1,2,3 ";

    $alSql[] ="SELECT NOM, PRO, GRU, DIT, SEM,
                    SUM(C01) AS 'C01',
                    SUM(V01) AS 'V01',
                    SUM(C02) AS 'C02',
                    SUM(V02) AS 'V02',
                    SUM(C03) AS 'C03',
                    SUM(V03) AS 'V03',
                    SUM(C04) AS 'C04',
                    SUM(V04) AS 'V04',
                    SUM(C05) AS 'C05',
                    SUM(V05) AS 'V05',
                    SUM(C06) AS 'C06',
                    SUM(V06) AS 'V06',
                    SUM(C07) AS 'C07',
                    SUM(V07) AS 'V07',
                    SUM(CXX) AS 'CXX',
                    SUM(VXX) AS 'VXX',
                    SUM(V07) / SUM(C07) AS PUN
            FROM tmp_data
            GROUP BY 1,2,3,4,5
            HAVING  SUM(C01) <> 0 OR
                    SUM(V01) <> 0 OR
                    SUM(C02) <> 0 OR
                    SUM(V02) <> 0 OR
                    SUM(C03) <> 0 OR
                    SUM(V03) <> 0 OR
                    SUM(C04) <> 0 OR
                    SUM(V04) <> 0 OR
                    SUM(C05) <> 0 OR
                    SUM(V05) <> 0 OR
                    SUM(C06) <> 0 OR
                    SUM(V06) <> 0 OR
                    SUM(C07) <> 0 OR
                    SUM(V07) <> 0 OR
                    SUM(CXX) <> 0 OR
                    SUM(VXX) <> 0
            ORDER BY 1, 2,3,4";

    $rs= fSQL($db, $alSql);
    return $rs;
}
/** CAbecera de la liquidacin
*   @access public
*   @param  object      $rpt        Reference to current report object
*   @param  object      $hdr        Reference to current header report object
*   @return void
*/
function before_header(&$rpt, &$hdr){
    $ilTxtSize=10;  //
    $ilLeading=16;  //
    include_once ("RptHeader.inc.php");
  }
/**
*   Texto acbecera de cada liquidacion
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_PRO (&$rpt, &$group) {
    global $db;
    global $ilPag;
    global $ilSemana;
    global $gfValor;

    if ($ilPag > 1 ) {
            $rpt->pdf->ezNewPage();
    }
    $ilPag +=1;
    
    $x=  $rpt->leftBorder;
    $rpt->pdf->y -= 12;
    $rpt->putTextAndLabel(10,  $rpt->pdf->y, $group->lastRec['PRO'] . " " . $group->lastRec['NOM'], "PRODUCTOR:" , 300, 52);
    $rpt->putTextAndLabel(400, $rpt->pdf->y, $group->lastRec['SEM'], "SEMANA: " ,    40,50);
    $rpt->pdf->y -= 2;
    $sltxt = "SELECT com_tipocomp, com_numcomp
	                FROM concomprobantes
                    WHERE   com_tipocomp in ('EP', 'DV') and com_refOperat = " . $group->lastRec['SEM'] . " and
	                        com_codreceptor =" . $group->lastRec['PRO'] . "
	               ORDER BY 1,2";
    $rs = $db->execute($sltxt);
    if ($rs) {
        $sltxt= '' ;
        while (!$rs->EOF) {
            $sltxt .= ((strlen($sltxt)>0) ? ', ':'') . $rs->fields[0] . '-' . $rs->fields[1];
            $rs->MoveNext();
        }
        $rpt->pdf->y -= 8;
        $rpt->pdf->y=$rpt->putTextAndLabel(10, $rpt->pdf->y, $sltxt, "DOCS: " ,  500,30) + 6;

    }
    

}
/**
*   Al termino de procesar cada productor
*/
function after_group_PRO (&$rpt, &$group) {
    global $gfValor;
    $x = $rpt->leftBorder;
    $y1 = 40;
    $l=80;
    
    $w=$rpt->pdf->ez['pageWidth'] - $rpt->pdf->ez['rightMargin'] - $x -10;
    $rpt->pdf->ezrRoundRectangle($x, 25, $w, 50, 30);
    $x+=10;
    for ($i=0; $i<3; $i++) {
        $rpt->pdf->line($x, $y1, $x+$l , $y1);
        $x+=10+$l;
    }
    $gfValor=$group->sums['VXX'];
}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$ilPag = 1;
$slQry   = fGetParam('pQryLiq', false);
$ilSemana= fGetParam('pSem', false);
$ilProduc= fGetParam('pPrd', false);
$ilFontSize=fGetParam('pFsz', 0);
$ilNumProceso = fGetParam('pPro', 0);

$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$rep = new ezPdfReport($rs, array(21.7,14),"portrait", "Courier", 8);
$rep->subTitle="LIQUIDACION DE CARTON Y MATERIAL REEMBOLSABLE ";
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$rep->font, 'fontSize'=>$ilFontSize+2,  'justification'=>'center', 'leading'=>$ilFontSize+4),
                      'S'=>array('font'=>$rep->font, 'fontSize'=>$ilFontSize ,   'justification'=>'center', 'leading'=>$ilFontSize+4),
                      'C'=>array('font'=>$rep->font, 'fontSize'=>$ilFontSize-2 , 'justification'=>'center', 'leading'=>$ilFontSize+1 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.0,0.5,0.1,1);
$rep->colHead = array(
//                    'ITE'=>'ITEM',
                    'DIT'=>'   ITEM ',
//                    'C00'=>'SAL ANT',
//                    'V00'=>'VAL ANT',
                    'C01'=>'SALDO ANTER.',
//                    'V01'=>'VAL ANT',
                    'C02'=>'EGRESO BODEGA',
//                    'V02'=>'EGR VAL',
                    'C03'=>'DEVOLU CIONES',
//                    'V03'=>'DEV VAL',
                    'C04'=>'CANT EMBARC.',
//                    'V04'=>'VAL PTO',
//                    'C05'=>'CAN RCH',
//                    'V05'=>'VAL RCH',
//                    'C06'=>'CAN CAI',
//                    'V06'=>'VAL CAI',
                    'C07'=>'CANT. LI QUIDADA',
                    'V07'=>'VALR. LI QUIDADO',
                    'PUN'=>'PREC. UNIT',
                    'CXX'=>'SALDO FINAL'
//                    'VXX'=>'SAL VAL'
                    );
$rep->rptOpt = array('fontSize'=>9, 'titleFontSize' => 10, 'showHeadings'=>1,'shaded'=>0, 'splitRows'=>0,
                     'showLines'=>3, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->printColHead=false;
$rep->setDefaultColPro('format', "12:4:.:"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['DIT']->type='C';
$rep->columns['DIT']->repeat=false;
$rep->columns['DIT']->format=false;
$rep->columns['V07']->format="9:2:,:.";
$rep->columns['VXX']->format="9:2:,:.";

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['DIT']['justification']='left';

$rep->setDefaultColOpt('width', 58);
$rep->colOpt['DIT']['width']=130;
$rep->colOpt['PUN']['width']=40;


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

$rep->addGrp('PRO');
    $rep->groups['PRO']->fontSize=10;
    $rep->groups['PRO']->textCol='DIT';
    $rep->addResumeLine('PRO','-', 'T O T A L: ',0);
    $rep->groups['PRO']->linesBefore=2;
        $rep->setAggregate('PRO',0, 'V07','S');
$gfValor=0;
$rep->run();
$rep->view($rep->title, $rep->saveFile("LIQ_ANE_"));
?>
