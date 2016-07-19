<?php
/*
*   InTrTr_contabem2.pro.php: genera un reporte con ANalisis previo a Contab. de Inventario de Movs de Embarque
*                             Si el saldo final no es cero, no se incluye el saldo anterior en el costeo (paRA EVITAR ACUMULACION)
*   @author     Fausto Astudillo
*   @param      string		pQryLiq  Condici� de bsqueda
*   @output     contenido pdf del reporte.
*/
error_reporting(E_ALL);
include("../LibPhp/ezPdfReport.php");
//include("../LibPhp/GenCifras.php");
include("../LibPhp/ConTranLib.php");
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
    $alSql[] ="DROP TABLE IF EXISTS tmp_data";

    $alSql[] ="CREATE TEMPORARY TABLE tmp_productor
               SELECT DISTINCT  tac_embarcador as tmp_codproductor,
                         concat(per_Apellidos, ' ',  per_nombres) as tmp_nombre,
                         tad_liqNUmero, pro_ID, pro_semana, com_regnumero as tmp_regnumero,
                         if (act_subcategoria = 300, '001','002') AS tmp_producto
                    FROM liqprocesos JOIN liqtarjadetal ON tad_liqProceso = pro_ID
                         JOIN liqtarjacabec on tar_numtarja = tad_numTarja
                         JOIN conpersonas on per_codauxiliar = tac_embarcador
                         JOIN concomprobantes ON com_tipocomp = 'LI' AND com_numcomp = tad_liqnumero
                         JOIn conactivos   ON act_codauxiliar = tad_codproducto
                    WHERE " . $pQry . " ORDER BY 2";

    $alSql[] =  "CREATE INDEX tmp_trj1 ON tmp_productor(tmp_codproductor)" ;
    $alSql[] =  "CREATE INDEX tmp_trj2 ON tmp_productor(tmp_regnumero)" ;
    $alSql[] =  "CREATE  TEMPORARY TABLE tmp_data
                SELECT
                    com_CodReceptor AS PRO,
                    pro_semana AS SEM,
                    det_coditem AS ITE,
                    SUM(det_CantEquivale * pro_Signo) AS 'C01',
                    SUM(det_CosTotal * pro_Signo)     AS 'V01',
                    sum(00000000.0000) AS 'C02',
                    sum(00000000.0000) AS 'V02',
                    sum(00000000.0000) AS 'C03',
                    sum(00000000.0000) AS 'V03',
                    sum(00000000.0000) AS 'C04',
                    sum(00000000.0000) AS 'V04',
                    sum(00000000.0000) AS 'C05',
                    sum(00000000.0000) AS 'V05',
                    sum(00000000.0000) AS 'C06',
                    sum(00000000.0000) AS 'V06',
                    sum(00000000.0000) AS 'C07',
                    sum(00000000.0000) AS 'V07',
                    SUM(00000000.0000) AS 'LCO',
                    SUM(det_CantEquivale * pro_Signo) AS 'CXX',
                    SUM(det_CosTotal * pro_Signo)     AS 'VXX'
              FROM invprocesos  join concomprobantes ON com_TipoComp = cla_TipoTransacc
                     JOIN tmp_productor ON tmp_codProductor = com_CodReceptor
                     JOIN invdetalle ON det_RegNumero = com_RegNumero
                     LEFT JOIN invprecios ON  invprecios.pre_CodItem = det_CodItem
              WHERE pro_codProceso = 5   AND com_RefOperat  < pro_Semana AND det_cantEquivale  <> 0
              GROUP BY 1,2,3
              HAVING SUM(det_CantEquivale * pro_Signo) > 0
              order by 1,2,3";
/*
    SUM(if(det_cantequivale > 0, det_CosTotal * pro_Signo,0)) AS 'LCO',
    po1 1 = Saldo Anter Liquidac
    pos 2 = Egresos Bodega
    pos 3 = Devoluciones
    pos 4 = Recepcion en Pto
    pos 5 = Rechazado
    pos 6 = Cjas caidas
    pos 7 = Liquidacin
    pos 8 = No pagado
*/
    $alSql[] ="INSERT INTO tmp_data
                SELECT
                    com_CodReceptor AS PRO,
                    pro_semana AS SEM,
                    invdetalle.det_coditem AS ITE,
                    SUM(CASE WHEN  pro_orden = 1 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C01',
                    SUM(CASE WHEN  pro_orden = 1 THEN (det_CosTotal * pro_Signo)     ELSE 0 END)  AS 'V01',
                    SUM(CASE WHEN  pro_orden = 2 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C02',
                    SUM(CASE WHEN  pro_orden = 2 THEN (det_CosTotal * pro_Signo)     ELSE 0 END)  AS 'V02',
                    SUM(CASE WHEN  pro_orden = 3 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C03',
                    SUM(CASE WHEN  pro_orden = 3 THEN (det_CosTotal * pro_Signo)     ELSE 0 END)  AS 'V03',
                    SUM(CASE WHEN  pro_orden = 4 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C04',
                    SUM(CASE WHEN  pro_orden = 4 THEN (det_CosTotal * pro_Signo)     ELSE 0 END)  AS 'V04',
                    SUM(CASE WHEN  pro_orden = 5 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C05',
                    SUM(CASE WHEN  pro_orden = 5 THEN (det_CosTotal * pro_Signo)     ELSE 0 END)  AS 'V05',
                    SUM(CASE WHEN  pro_orden = 6 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C06',
                    SUM(CASE WHEN  pro_orden = 6 THEN (det_CosTotal * pro_Signo)     ELSE 0 END)  AS 'V06',
                    SUM(CASE WHEN  pro_orden = 7 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C07',
                    SUM(CASE WHEN  pro_orden = 7 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V07',
                    SUM(IF(pro_orden = 7 AND det_cantequivale> 0, det_CosTotal * pro_Signo,0))    AS 'LCO',
                    SUM(det_CantEquivale * pro_Signo)   AS 'CXX',
                    SUM(det_CosTotal * pro_Signo)       AS 'VXX'
              FROM  invprocesos  join concomprobantes ON com_TipoComp = cla_TipoTransacc
                     JOIN tmp_productor ON tmp_codProductor = com_CodReceptor
                     JOIN invdetalle ON det_RegNumero = com_RegNumero
              WHERE pro_codProceso = 5   AND com_RefOperat  = pro_Semana AND det_cantEquivale  <> 0
              GROUP BY 1,2,3
              order by 1,2,3
              ";
/**
                   SUM(if(det_CantEquivale >0, det_CantEquivale * pro_Signo, 0))   AS 'CXX',
                    SUM(if(det_CantEquivale >0, det_CosTotal * pro_Signo,  0))       AS 'VXX'
**/
    $alSql[] =  "CREATE INDEX tmp_dat ON tmp_data(PRO)" ;
    $alSql[] ="SELECT
                    tmp_nombre AS NOM, PRO,
                    tmp_regnumero AS REG,
                    par_valor4 AS GRU,
                    tad_liqnumero AS LIQ,
                    concat(ITE, '  ', left(act_descripcion,18), ' ',left(act_descripcion1,10)) AS DIT,
                    tmp_producto AS PRD,
                    SEM,
                    SUM(C01) AS 'C01',
                    SUM(if (CXX =0,V01, 0)) AS 'V01',
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
                    SUM(LCO) AS 'LCO',
                    SUM(V07) / SUM(C07) AS PUN,
                    SUM(IF(C07 < 0, C07,0)) AS CCO,
                    SUM(IF(C07 > 0, C07,0)) AS CPA,
                    SUM(IF(C07 < 0, V07,0)) AS VCO,
                    SUM(IF(C07 > 0, V07,0)) AS VPA
            FROM tmp_data
                    JOIN conactivos ON conactivos.act_Codauxiliar = ITE
                    JOIN genparametros ON par_clave = 'ACTGRU' AND par_secuencia = act_grupo
                    JOIN tmp_productor ON tmp_codProductor = PRO
            GROUP BY 1,2,3,4,5,6,7,8
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
            ORDER BY 1,2,3,4,5";

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
            $sltxt .= ((strlen($sltxt)>0) ? ', ':'') . $rs->fields['com_tipocomp'] . '-' . $rs->fields['com_numcomp'];
            $rs->MoveNext();
        }
        $rpt->pdf->y -= 8;
        $rpt->pdf->y=$rpt->putTextAndLabel(10, $rpt->pdf->y, $sltxt, "DOCS: " ,  500,30) + 6;
    }
}
function before_group_GRU (&$rpt, &$group) {
    global $igAux ;
    $igAux = $group->lastRec['GRU'];
}
/**
*   Al termino de procesar cada grupo de items carton o material
*/
function after_group_GRU (&$rpt, &$group) {
    global $gfValor;
    global $cla;
    global $db;
    global $igSecue;
    global $igAux;
    $igSecue +=1;
    $lastrec= array();
    $fSuma=0;
    $fCosto=$group->sums['V04'] * (-1);
    $fCpCob=($group->sums['V01'] + $group->sums['V02'] + $group->sums['V03']) * (-1);
    $fIngre=$group->sums['VCO'] * (-1);
    $fEgres=$group->sums['VPA'] * (-1);
    $fDifer=$fCosto + $fCpCob + $fIngre + $fEgres;
    if (is_null($group->lastRec['GRU']) || empty($group->lastRec['GRU'])) {
       fMensaje("UN ITEM NO TIENE DEFINIDO EL 'TIPO DE COSTO' EN " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp'] . "-" . $lastrec['det_secuencia']);
       fErrorPage('',"UN ITEM NO TIENE DEFINIDO EL 'TIPO DE COSTO' EN " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp'] . "-" . $lastrec['det_secuencia']) ;
    }
echo "<br> " . $group->lastRec['GRU'] . "  / " . $igAux . " // " . $group->currValue . " //" . $group->lastValue ;
}
/**
*   Al termino de procesar cada productor
*/
function after_group_PRO (&$rpt, &$group) {
    global $gfValor;
    global $cla;
    global $db;
    global $igSecue;
    $x = $rpt->leftBorder+5;
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
$igSecue = 0;
$igAux = 0;
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$trSql = "SELECT cla_descripcion, cla_tipocomp, cla_contabilizacion, cla_indtransfer,
                    cla_ctaorigen, cla_ctadestino, cla_auxorigen,
                    cla_auxdestino, cla_ctaingresos, cla_ctacosto, cla_ctadiferencia, cla_reqreferencia,
                    cla_reqsemana, cla_clatransaccion, cla_indicosteo, cla_ImpFlag,
                    o.cue_tipauxiliar as cue_oriauxil,
                    d.cue_tipauxiliar as cue_desauxil,
                    i.cue_tipauxiliar as cue_ingauxil,
                    e.cue_tipauxiliar as cue_egrauxil,                    	
                    f.cue_tipauxiliar as cue_difauxil
            FROM genclasetran
                    LEFT JOIN concuentas o on o.cue_codcuenta = cla_ctaorigen
		            LEFT JOIN concuentas d on d.cue_codcuenta = cla_ctadestino
		            LEFT JOIN concuentas i on i.cue_codcuenta = cla_ctaingresos
		            LEFT JOIN concuentas e on e.cue_codcuenta = cla_ctacosto
		            LEFT JOIN concuentas f on f.cue_codcuenta = cla_ctadiferencia
            WHERE cla_tipocomp = 'LI' ";
$rs = $db->execute($trSql);
if (!$rs) fErrorPage('',"NO SE PUDO SELECCIONAR LA TRANSACCION " . $pRNum);
$rs->MoveFirst();
$cla = $rs->FetchNextObject(false);
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$rep = new ezPdfReport($rs, array(35,14),"landscape", "Courier", 8);
$rep->subTitle="CONTABILIZACION DE EMBARQUE ";
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$rep->font, 'fontSize'=>$ilFontSize+2,  'justification'=>'center', 'leading'=>$ilFontSize+4),
                      'S'=>array('font'=>$rep->font, 'fontSize'=>$ilFontSize ,   'justification'=>'center', 'leading'=>$ilFontSize+4),
                      'C'=>array('font'=>$rep->font, 'fontSize'=>$ilFontSize-2 , 'justification'=>'center', 'leading'=>$ilFontSize+1 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.2,0.8,0.1,1);
$rep->colHead = array(
                    'REG'=>'REG',
                    'GRU'=>'  GRUPO ',
                    'DIT'=>'   ITEM ',
                    'C01'=>'SALDO ANTER.',
                    'V01'=>'S:ANT. COSTO',
                    'C02'=>'EGRESO BODEGA',
                    'V02'=>'EGR COSTO',
                    'C03'=>'DEVOLU CIONES',
                    'V03'=>'DEVOL. COSTO',
                    'C04'=>'CANT EMBARC.',
                    'V04'=>'COSTO EMBAR',
//                    'C05'=>'CAN RCH',
//                    'V05'=>'VAL RCH',
//                    'C06'=>'CAN CAI',
//                    'V06'=>'VAL CAI',
//                    'C07'=>'CANT. LI QUIDADA',
                    'LCO'=>'COSTO LIQUIDADO',
                    'CCO'=>'CANT. COBRADA',
                    'CPA'=>'CANT. PAGADA',
                    'PUN'=>'PREC. UNIT',
                    'VCO'=>'VALOR. COBRADO',
                    'VPA'=>'VALOR. PAGADO',
                    'CXX'=>'SALDO CANT',
                    'VXX'=>'SALDO COSTO'
                    );
$rep->rptOpt = array('fontSize'=>9, 'titleFontSize' => 10, 'showHeadings'=>2,'shaded'=>0, 'splitRows'=>0,
                     'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->printColHead=false;
$rep->setDefaultColPro('format', "10:2:.:"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['DIT']->type='C';
$rep->columns['DIT']->repeat=false;
$rep->columns['DIT']->format=false;
$rep->columns['C04']->format="10:3:.:";
$rep->columns['C07']->format="10:3:.:";
$rep->columns['V07']->format="10:2:.:";
$rep->columns['CCO']->format="10:3:.:";
$rep->columns['CPA']->format="10:3:.:";
$rep->columns['VCO']->format="10:2:.:";
$rep->columns['VPA']->format="10:2:.:";
$rep->columns['VXX']->format="10:2:.:";

    $rep->columns['C01']->visible=true;
    $rep->columns['C07']->visible=false;
    $rep->columns['V07']->visible=false;
    $rep->columns['CCO']->visible=true;
    $rep->columns['CPA']->visible=true;
    $rep->columns['VCO']->visible=true;
    $rep->columns['VPA']->visible=true;

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['DIT']['justification']='left';

$rep->setDefaultColOpt('width', 45);
    $rep->columns['C01']->width=45;
$rep->colOpt['DIT']['width']=130;
$rep->colOpt['PUN']['width']=40;
$rep->colOpt['VCO']['width']=45;
$rep->colOpt['VPA']['width']=45;

$rep->addGrp('PRO');
    $rep->groups['PRO']->fontSize=10;
    $rep->groups['PRO']->textCol='DIT';
    $rep->addResumeLine('PRO','-', 'T O T A L: ',0);
    $rep->groups['PRO']->linesBefore=2;
        $rep->setAggregate('PRO',0, 'V01','S');
        $rep->setAggregate('PRO',0, 'V02','S');
        $rep->setAggregate('PRO',0, 'V03','S');
        $rep->setAggregate('PRO',0, 'V04','S');
        $rep->setAggregate('PRO',0, 'V05','S');
        $rep->setAggregate('PRO',0, 'V07','S');
        $rep->setAggregate('PRO',0, 'VPA','S');
        $rep->setAggregate('PRO',0, 'VCO','S');
        $rep->setAggregate('PRO',0, 'VXX','S');
        $rep->setAggregate('PRO',0, 'LCO','S');

$rep->addGrp('GRU');
    $rep->groups['GRU']->fontSize=10;
    $rep->groups['GRU']->textCol='DIT';
    $rep->addResumeLine('GRU','-', 'SUBT. GRU: ',0);
    $rep->groups['GRU']->linesBefore=2;
        $rep->setAggregate('GRU',0, 'V01','S');
        $rep->setAggregate('GRU',0, 'V02','S');
        $rep->setAggregate('GRU',0, 'V03','S');
        $rep->setAggregate('GRU',0, 'V04','S');
        $rep->setAggregate('GRU',0, 'V05','S');
        $rep->setAggregate('GRU',0, 'V07','S');
        $rep->setAggregate('GRU',0, 'VPA','S');
        $rep->setAggregate('GRU',0, 'VCO','S');
        $rep->setAggregate('GRU',0, 'VXX','S');
        $rep->setAggregate('GRU',0, 'LCO','S');

/**
$rep->addGrp('PRD');
    $rep->groups['PRD']->fontSize=10;
    $rep->groups['PRD']->textCol='DIT';
    $rep->addResumeLine('PRD','-', 'SUBT. PROD: ',0);
    $rep->groups['PRD']->linesBefore=2;
        $rep->setAggregate('PRD',0, 'V01','S');
        $rep->setAggregate('PRD',0, 'V02','S');
        $rep->setAggregate('PRD',0, 'V03','S');
        $rep->setAggregate('PRD',0, 'V04','S');
        $rep->setAggregate('PRD',0, 'V05','S');
        $rep->setAggregate('PRD',0, 'V07','S');
        $rep->setAggregate('PRD',0, 'VPA','S');
        $rep->setAggregate('PRD',0, 'VCO','S');
        
$rep->addGrp('MAR');
    $rep->groups['MAR']->fontSize=10;
    $rep->groups['MAR']->textCol='DIT';
    $rep->addResumeLine('MAR','-', 'SUBT: MARC. ',0);
    $rep->groups['MAR']->linesBefore=2;
        $rep->setAggregate('MAR',0, 'V01','S');
        $rep->setAggregate('MAR',0, 'V02','S');
        $rep->setAggregate('MAR',0, 'V03','S');
        $rep->setAggregate('MAR',0, 'V04','S');
        $rep->setAggregate('MAR',0, 'V05','S');
        $rep->setAggregate('MAR',0, 'V07','S');
        $rep->setAggregate('MAR',0, 'VPA','S');
        $rep->setAggregate('MAR',0, 'VCO','S');
**/
$gfValor=0;
$rep->run();
$rep->view($rep->title, $rep->saveFile("COST_LIQ"));
?>
