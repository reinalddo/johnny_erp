<?php
/*
*   LiLiRp_preliq.php: Pre Liquidacion de Inventario
*   @author     Fausto Astudillo
*   @param      string		pQryLiq  Condici� de bsqueda
*   @output     contenido pdf del reporte.
*   @rev    Ene/17/08       Parametrizacion de semanan inicial de liquidaciones
*   @rev    MAr/12/08       Incluir codigo de item y Agrupamiento por zonas
*   @rev    Abr/19/09       INcluir todos los productores que hayan retirado carton
*   @rev    Sep/15/09       Incluir TODOS LOS ITEMS DE GRUPOS 210,220,230
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
    $ilSemInic = NZ(fDBValor($db, 'genparametros', 'par_valor3', "par_clave='LIINIC'"),0);//               Trae el Parametro de periodo minimo de liquidacion
    
    $alSql = Array();
    
    $alSql[] ="DROP TABLE IF EXISTS tmp_productor";

    $alSql[] ="CREATE TEMPORARY TABLE tmp_productor
               SELECT DISTINCT  tac_embarcador as tmp_codproductor,
                         concat(per_Apellidos, per_nombres) as tmp_nombre,
                         0 AS tad_liqNUmero, 0 AS pro_ID, tac_semana
                    FROM liqtarjadetal
                         JOIN liqtarjacabec on tar_numtarja = tad_numTarja
                         JOIN conpersonas on per_codauxiliar = tac_embarcador
                    WHERE tac_semana = " . $pQry . " ORDER BY 2";
                    
    $alSql[] =  "CREATE TEMPORARY TABLE tmp_data
                SELECT com_CodReceptor AS PRO,
                    det_CodItem AS ITE,
                    SUM(det_CantEquivale * pro_Signo) AS 'C01',
                    SUM(det_ValTotal * pro_Signo)     AS 'V01',
                    00000000.0000 AS 'C02',
                    00000000.0000 AS 'V02',
                    00000000.0000 AS 'C03',
                    00000000.0000 AS 'V03',
                    00000000.0000 AS 'C04',
                    00000000.0000 AS 'V04',
                    00000000.0000 AS 'C05',
                    00000000.0000 AS 'V05',
                    00000000.0000 AS 'C06',
                    00000000.0000 AS 'V06',
                    00000000.0000 AS 'C07',
                    00000000.0000 AS 'V07',
                    SUM(det_CantEquivale * pro_Signo) AS 'CXX',
                    SUM(det_ValTotal * pro_Signo)     AS 'VXX'
              FROM (((((invprocesos  join concomprobantes ON com_TipoComp = cla_TipoTransacc)
                     JOIN tmp_productor ON tmp_codProductor = com_CodReceptor)
                     JOIN invdetalle ON det_RegNumero = com_RegNumero)
                     JOIN conactivos ON conactivos.act_Codauxiliar = det_CodItem)
                     LEFT JOIN invprecios ON  (invprecios.pre_CodItem = det_CodItem and invprecios.pre_LisPrecios = '3'))
              WHERE pro_codProceso = 5   AND com_RefOperat BETWEEN " . $ilSemInic . " AND " . ($pQry  -1) . " AND det_cantEquivale  <> 0
              GROUP BY 1,2 ORDER BY 1,2,3 ";

/* EXPLOSION DE INSUMOS SEGUN   DOSIS POR COMPONENTE  OK.
*/
    $alSql[] ="INSERT INTO tmp_data
                SELECT tac_embarcador AS PRO,
        	            dos_CodItem  AS ITE,
                            SUM(0)  AS 'C01',
                            SUM(0)  AS 'V01',
                            SUM(0)  AS 'C02',
                            SUM(0)  AS 'V02',
                            SUM(0)  AS 'C03',
                            SUM(0)  AS 'V03',
                	    SUM(dos_cantidad *(tad_cantrecibida - tad_cantrechazada  )) AS 'C04',
                            SUM(0)  AS 'V04',
                            SUM(0)  AS 'C05',
                            SUM(0)  AS 'V05',
                            SUM(0)  AS 'C06',
                            SUM(0)  AS 'V06',
                            SUM(0)  AS 'C07',
                            SUM(0)  AS 'V07',
                            SUM(dos_cantidad *(-1) * (tad_cantrecibida - tad_cantrechazada  )) AS 'CXX',
                            SUM(0)       AS 'VXX'
            FROM 	liqdosis
        	JOIN liqcomponent  on cte_codigo = dos_codcomponente
        	JOIN liqtarjadetal on tad_codcompon1 = cte_codigo or
        				  tad_codcompon2 = cte_codigo or
        				  tad_codcompon3 = cte_codigo or
        				  tad_codcompon4 = cte_codigo
        	JOIN liqtarjacabec ON tac_semana = " . $pQry . " AND tar_numtarja = tad_numtarja
        	JOIN conactivos ON dos_coditem = act_codauxiliar
        	JOIN conpersonas ON per_codauxiliar = tac_embarcador
            group by 1,2
            order by 1, cte_referencia, act_grupo ";

/*
                    SUM(CASE WHEN  pro_orden = 4 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C04',
                    SUM(CASE WHEN  pro_orden = 4 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V04',
                    */
    $alSql[] ="INSERT INTO tmp_data
                SELECT com_CodReceptor AS PRO,
                    det_CodItem AS ITE,
                    SUM(CASE WHEN  pro_orden = 1 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C01',
                    SUM(CASE WHEN  pro_orden = 1 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V01',
                    SUM(CASE WHEN  pro_orden = 2 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C02',
                    SUM(CASE WHEN  pro_orden = 2 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V02',
                    SUM(CASE WHEN  pro_orden = 3 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C03',
                    SUM(CASE WHEN  pro_orden = 3 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V03',
                    SUM(0)  AS 'C04',
                    SUM(0)  AS 'V04',
                    SUM(CASE WHEN  pro_orden = 5 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C05',
                    SUM(CASE WHEN  pro_orden = 5 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V05',
                    SUM(CASE WHEN  pro_orden = 6 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C06',
                    SUM(CASE WHEN  pro_orden = 6 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V06',
                    SUM(CASE WHEN  pro_orden = 7 THEN (det_CantEquivale * pro_Signo) ELSE 0 END)  AS 'C07',
                    SUM(CASE WHEN  pro_orden = 7 THEN (det_ValTotal * pro_Signo)     ELSE 0 END)  AS 'V07',
                    SUM(det_CantEquivale * pro_Signo)   AS 'CXX',
                    SUM(det_ValTotal * pro_Signo)       AS 'VXX'
              FROM invprocesos  join concomprobantes ON pro_codProceso = 5   AND com_TipoComp = cla_TipoTransacc
                     /*JOIN tmp_productor ON tmp_codProductor = com_CodReceptor  --      INCLUIR TODOS LOS PROD AUNQUE NO EMBARQUEN */
                     JOIN invdetalle ON det_RegNumero = com_RegNumero
                     JOIN conactivos ON conactivos.act_Codauxiliar = det_CodItem
                     LEFT JOIN invprecios ON (invprecios.pre_CodItem = det_CodItem and invprecios.pre_LisPrecios = '3')
              WHERE com_RefOperat =" . $pQry . "  AND det_cantEquivale  <> 0
              GROUP BY 1,2 ORDER BY 1,2,3 ";
//*                                                 VAlor Historico
    $alSql[] ="INSERT INTO tmp_data
                SELECT com_CodReceptor AS PRO,
                    det_CodItem AS ITE,
                            SUM(0)  AS 'C01',
                            SUM(0)  AS 'V01',
                            SUM(0)  AS 'C02',
                            SUM(0)  AS 'V02',
                            SUM(0)  AS 'C03',
                            SUM(0)  AS 'V03',
                			SUM(0)  AS 'C04',
                            SUM(0)  AS 'V04',
                            SUM(0)  AS 'C05',
                            SUM(0)  AS 'V05',
                            SUM(0)  AS 'C06',
                            SUM(0)  AS 'V06',
                            SUM(det_cantequivale)  AS 'C07',
                            SUM(0)  AS 'V07',
                            SUM(0) AS 'CXX',
                            SUM(0)       AS 'VXX'
              FROM tmp_productor
                     JOIN concomprobantes ON com_TipoComp in ( 'LI' , 'LS') AND com_codreceptor = tmp_codproductor
                     JOIN invdetalle ON det_RegNumero = com_RegNumero
              WHERE com_RefOperat  < " . $pQry . "  AND det_cantEquivale  <> 0
              GROUP BY 1,2 ORDER BY 1,2,3 ";
	
	switch(fGetParam("pTip", 0)){							// #JVL Ago 13 del 2014 $slGrupos debe tomar de genparametros  
		case 0:
			$slGrupos="210,220,230,240,250,260,310";
			break;
		default:
			$slGrupos="210,220,240";
			break;
	}

    $alSql[] ="SELECT
    		    par_Descripcion AS ZON,
		    concat(per_apellidos, ' ' , per_nombres) AS NOM,
                    PRO,
                    ITE,
                    concat(act_descripcion, ' ' , act_descripcion1) as DIT,
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
                    SUM(VXX) AS 'VXX'
            FROM tmp_data JOIN conpersonas ON per_codauxiliar = PRO
                JOIN conactivos  ON act_codauxiliar = ITE
		LEFT JOIN genparametros ON par_claVE ='LSZON' AND par_secuencia = per_subzona
            WHERE act_grupo in (SELECT par_secuencia FROM genparametros WHERE par_clave = 'ACTGRU' AND par_valor4 = 'LQF')
            GROUP BY 1,2,3, 4,5
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
                    SUM(V06) <> 0 
            ORDER BY 1,2, 3, act_grupo, 4 ";

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
    $ilLeading=0;  //
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
   /*
    if ($ilPag > 1 ) {
            $rpt->pdf->ezNewPage();
    }
    $ilPag +=1;
   */
    $x=  $rpt->leftBorder;
    $rpt->pdf->y-=10;
    /*
    $rs = $db->execute("SELECT concat(per_Apellidos, ' ', per_Nombres) as txt_productor FROM conpersonas WHERE per_codauxiliar = " . $group->currValue);
    if ($rs) {
        while (!$rs->EOF) {
            $sltxt= $group->lastRec['PRO'] . '  ' . $rs->fields[0];
            $i = (strlen($sltxt) < 109) ? 110 - strlen($sltxt): 1;
            $sltxt .= str_repeat(' ',$i) .  '   SEMANA: ' . $ilSemana;;
            $rpt->putText($sltxt, 9,9);
            $rs->MoveNext();
        }
    }
    */
    $sltxt=strtoupper($group->lastRec['NOM']) ;
    $i = (strlen($sltxt) < 109) ? 110 - strlen($sltxt): 1;
    $sltxt .= str_repeat(' ',$i) ;
    $rpt->putText($group->lastRec['PRO'] .  ' ' . $sltxt, 9,9);
    $rpt->pdf->y+=10;
}
function before_group_ZON (&$rpt, &$group) {
    global $db;
    global $ilPag;
    global $ilSemana;
    global $gfValor;
    $x=  $rpt->leftBorder;
    $rpt->pdf->y-=25;

    $sltxt=" " ;
    $i = (strlen($sltxt) < 109) ? 110 - strlen($sltxt): 1;
    $sltxt .= str_repeat(' ',$i);
    $rpt->putText($sltxt . strtoupper("-------------------------- ZONA: " . $group->lastRec['ZON']) , 9,9);
    $rpt->pdf->y+=10;
}
/**
*   Al termino de procesar cada productor

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
}*/
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$ilPag = 1;
$slQry   = fGetParam('pQryLiq', false);
$ilSemana= fGetParam('pSem', false);
$ilProduc= fGetParam('pPrd', false);
$ilFontSize=fGetParam('pFsz', 8);
$ilNumProceso = fGetParam('pPro', 0);

$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$slQry=$ilSemana;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$rep = new ezPdfReport($rs, array(21.5,27.9),"portrait", "Helvetica", 9);
$rep->title="PRE LIQUIDACION DE CARTON ";
$rep->subTitle="SEMANA : ". $slQry;
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$rep->font, 'fontSize'=>$ilFontSize+2,  'justification'=>'center', 'leading'=>$ilFontSize+4),
                      'S'=>array('font'=>$rep->font, 'fontSize'=>$ilFontSize +2 ,   'justification'=>'center', 'leading'=>$ilFontSize+6),
                      'C'=>array('font'=>$rep->font, 'fontSize'=>$ilFontSize-2 , 'justification'=>'center', 'leading'=>$ilFontSize+1 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,1,.01,0.5);
$rep->colHead = array(
                    'ITE'=>'ITEM',
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
                    'CXX'=>'DIFERENCIA',
//                    'V04'=>'VAL PTO',
//                    'C05'=>'CAN RCH',
//                    'V05'=>'VAL RCH',
//                     'C06'=>'CAN CAI',
//                    'V06'=>'VAL CAI',
                    'V07'=>'                ',
                    'C07'=>'ACUM. HISTORICO '
//                    'VXX'=>'SAL VAL'
                    );
$rep->rptOpt = array('fontSize'=>9, 'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>0, 'splitRows'=>0,
                     'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);
$rep->printColHead=true;
$rep->setDefaultColPro('format', "12:4:.:"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['DIT']->type='C';
$rep->columns['DIT']->repeat=false;
$rep->columns['DIT']->format=false;
$rep->columns['V07']->format="9:2:,:.";
$rep->columns['VXX']->format="9:2:,:.";
$rep->columns['ITE']->format="9:0::";


$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['DIT']['justification']='left';

$rep->setDefaultColOpt('width', 58);
$rep->colOpt['DIT']['width']=130;


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
$rep->addGrp('ZON');
    $rep->groups['ZON']->fontSize=10;
    $rep->groups['ZON']->textCol='DIT';
    $rep->addResumeLine('ZON','-', 'CONTROl ZONA: ',0);
    $rep->groups['ZON']->linesBefore=2;
        $rep->setAggregate('ZON',0, 'C01','S');
	$rep->setAggregate('ZON',0, 'C02','S');
	$rep->setAggregate('ZON',0, 'C03','S');
	$rep->setAggregate('ZON',0, 'C04','S');
	$rep->setAggregate('ZON',0, 'CXX','S');
	$rep->setAggregate('ZON',0, 'C07','S');

    $rep->addResumeLine('general','-', 'CONTROl ZONA: ',0);
        $rep->setAggregate('general',0, 'C01','S');
	$rep->setAggregate('general',0, 'C02','S');
	$rep->setAggregate('general',0, 'C03','S');
	$rep->setAggregate('general',0, 'C04','S');
	$rep->setAggregate('general',0, 'CXX','S');
	$rep->setAggregate('general',0, 'C07','S');

    
$rep->addGrp('PRO');
    $rep->groups['PRO']->fontSize=10;
    $rep->groups['PRO']->textCol='DIT';
/*
    $rep->addResumeLine('PRO','-', 'T O T A L: ',0);
    $rep->groups['PRO']->linesBefore=0;
        $rep->setAggregate('PRO',0, 'V07','S');
*/
$gfValor=0;
$rep->run();
$rep->view($rep->title, $rep->saveFile("LIQ_PRE_"));
?>
