<?php
/*
*
*	@rev	fah	Nov/12/07		Correccion para manejar los grupos de cuentas
*	@rev	fah	Sep/22/07		Correccion para aumentar numero de letras en descripciones y titulo dePYG
*	@rev	fah	04/13/10		Omitir detalle de cuentas cuyo saldo final sera cero ( sin importar valores DB y CR <>0 del periodo). SOlicitado por nmontero@forza
*/
include("../LibPhp/ezPdfReport.php");


function &fGetData($pSql, $ilPeriodo, $ilNivel, $pEsquema) {
    global $db;
    global $slTipo;
    $aSql=Array();
    $aSql[] ="DROP TABLE IF EXISTS tmp_auxiliares";
    $aSql[] ="drop table  if exists tmp_balcomp";

    $aSql[] ="CREATE TEMPORARY TABLE tmp_auxiliares
                SELECT
                        ucase( rpad(concat( left(per_Apellidos, 18), ' ', left(ifnull(per_Nombres,' '), 15)), " . (($slTipo =='COM')?20:45) . ", '-') )as tmp_descripcion,
                        per_codauxiliar as tmp_codauxiliar
                FROM conpersonas";
    $aSql[] ="INSERT INTO tmp_auxiliares
                SELECT  ucase( rpad(concat( left(act_descripcion, 18), ' ', left(ifnull(act_descripcion1,' '), 15))," . (($slTipo =='COM')?20:45) . ", '-') )as tmp_descripcion,
                        act_codauxiliar as tmp_codauxiliar
                FROM conactivos";

    $aSql[] ="CREATE INDEX i_tmpaux on tmp_auxiliares(tmp_codauxiliar)";
/**
*               Valores de cuentas ascendentes
**/
    $aSql[] ="CREATE  TABLE  tmp_balcomp
	            SELECT
                    red_codcuenta as cuenta ,
                    red_Ascendent,
                    cc.cue_padre as cue_padre ,
                    cc.cue_id as cue_id,
                    red_codcuenta  AS det_codcuenta,
                    000000 as det_idauxiliar,
                    cc.cue_tipmovim as cue_tipmovim,
                    sum(round(det_valdebito,2) - round(det_valcredito,2) -0000000000.00) as salan,
                    0000000000.00 as valdb, 0000000000.00 as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
 	            FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
	            WHERE com_numperiodo < ". $ilPeriodo . " AND
                    com_estProceso = 5 AND
	                det_regnumero = com_regnumero AND
                    concuentas.cue_codcuenta = det_codcuenta AND
	                red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent
                    AND red_codcuenta
                GROUP BY 1,2,3,4, 5,6
                ORDER BY 1,2" ;

	$aSql[] = "CREATE INDEX i_tmpsal on tmp_balcomp(cuenta, red_ascendent)";
/**
*       Cuentas de movimiento
**/
    $aSql[] ="INSERT INTO tmp_balcomp
              SELECT det_codcuenta as cuenta ,
                    9 as red_Ascendent,
                    cue_id as cue_padre,
                    0  as cue_id,
                    det_codcuenta,
                    det_idauxiliar,
                    1 as cue_tipmovim,
                    sum(round(det_valdebito,2) - round(det_valcredito,2)) as salan,
                    0000000000.00 as valdb, 0000000000.00 as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
                FROM concomprobantes JOIN condetalle on det_regnumero = com_regnumero
                    JOIN concuentas ON cue_codcuenta = det_codcuenta
                    LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
                WHERE com_numperiodo < ". $ilPeriodo . "  AND com_estProceso = 5 AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0
                GROUP BY 1,2,3,4, 5,6,7
                HAVING saldo <> 0
                ORDER BY 1,2";
/**
  $aSql[] ="INSERT INTO tmp_balcomp
              SELECT det_codcuenta as cuenta ,
                    9 as red_Ascendent,
                    cue_id as cue_padre,
                    0  as cue_id,
                    det_codcuenta,
                    det_idauxiliar,
                    1 as cue_tipmovim,
                    sum(round(det_valdebito,2) - round(det_valcredito,2)) as salan,
                    0000000000.00 as valdb, 0000000000.00 as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
                FROM concomprobantes JOIN condetalle on det_regnumero = com_regnumero
                    JOIN concuentas ON cue_codcuenta = det_codcuenta
                    LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
                WHERE com_numperiodo < ". $ilPeriodo . " AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0
                GROUP BY 1,2,3,4, 5,6,7
                HAVING saldo <> 0
                ORDER BY 1,2";
 **/
/**
*       Cuentas de movimiento
**/
    $aSql[] ="INSERT INTO tmp_balcomp
	            SELECT
                    red_codcuenta as cuenta ,
                    red_Ascendent,
                    cc.cue_padre as cue_padre ,
                    cc.cue_id as cue_id ,
                  	red_codcuenta  AS det_codcuenta,
                    00000 as det_idauxiliar,
                    cc.cue_tipmovim as cue_tipmovim,
                    sum(0000000000.00) as salan,
                    sum(round(det_valdebito,2))  as valdb ,
                    sum(round(det_valcredito,2)) as valcr,
                    sum(round(det_valdebito,2) - round(det_valcredito,2)) as saldo
                FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
                WHERE com_numperiodo = ". $ilPeriodo . " AND com_estProceso = 5 AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND
                    red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent AND red_codcuenta AND
                    (det_valdebito <> 0 or det_valcredito <> 0)
                GROUP BY 1,2,3,4, 5,6, 7
                ORDER BY 1,2";
/**
	$aSql[] ="INSERT INTO tmp_balcomp
	            SELECT
                    red_codcuenta as cuenta ,
                    red_Ascendent,
                    cc.cue_padre as cue_padre ,
                    cc.cue_id as cue_id ,
                  	red_codcuenta  AS det_codcuenta,
                    00000 as det_idauxiliar,
                    cc.cue_tipmovim as cue_tipmovim,
                    sum(0000000000.00) as salan,
                    sum(round(det_valdebito,2))  as valdb ,
                    sum(round(det_valcredito,2)) as valcr,
                    sum(round(det_valdebito,2) - round(det_valcredito,2)) as saldo
                FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
                WHERE com_numperiodo = ". $ilPeriodo . " AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND
                    red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent AND red_codcuenta AND
                    (det_valdebito <> 0 or det_valcredito <> 0)
                GROUP BY 1,2,3,4, 5,6, 7
                ORDER BY 1,2";
 **/

/**
*       Cuentas de movimiento del periodo
**/

    $aSql[] ="INSERT INTO tmp_balcomp
               SELECT det_codcuenta as cuenta , 9 as red_Ascendent, cue_id as cue_padre,
                    00000  as cue_id,
                    det_codcuenta, det_idauxiliar,
              	    1 as cue_tipmovim,
                    sum(0000000000.00) as salan,
                    sum(round(det_valdebito,2))  as valdb ,
                    sum(round(det_valcredito,2)) as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
                FROM concomprobantes JOIN condetalle on det_regnumero = com_regnumero
                     JOIN concuentas ON cue_codcuenta = det_codcuenta
                     LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
	            WHERE com_numperiodo = ". $ilPeriodo . "  AND com_estProceso = 5 AND
	                  det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0 and
	                  (det_valdebito <> 0 or det_valcredito <> 0)
             GROUP BY 1,2,3,4,5,6
             ORDER BY 1,7";
/**
	$aSql[] ="INSERT INTO tmp_balcomp
               SELECT det_codcuenta as cuenta , 9 as red_Ascendent, cue_id as cue_padre,
                    00000  as cue_id,
                    det_codcuenta, det_idauxiliar,
              	    1 as cue_tipmovim,
                    sum(0000000000.00) as salan,
                    sum(round(det_valdebito,2))  as valdb ,
                    sum(round(det_valcredito,2)) as valcr,
                    sum(round(det_valdebito,2)  - round(det_valcredito,2)) as saldo
                FROM concomprobantes JOIN condetalle on det_regnumero = com_regnumero
                     JOIN concuentas ON cue_codcuenta = det_codcuenta
                     LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
	            WHERE com_numperiodo = ". $ilPeriodo . " AND
	                  det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0 and
	                  (det_valdebito <> 0 or det_valcredito <> 0)
             GROUP BY 1,2,3,4,5,6
             ORDER BY 1,7";
 **/

    //$slSaldoCondicion = (($slTipo == 'COM') ? "  " : "HAVING (sum(salan) <> sum(valdb) - sum(valcr)  ) AND SUM(ROUND(saldo,2) + 00000000000.00) > 0  ") ;
	if (fGetParam("pCero", false) == false){  			//	#fah041310
		$slSaldoCondicion = (($slTipo == 'COM') ? "  " : "HAVING SUM(salan + valdb - valcr) <> 0   AND SUM(ROUND(saldo,2) + 00000000000.00) <> 0 ") ;
	} else { 											//	#fah041310
		$slSaldoCondicion = (($slTipo == 'COM') ? "  " : "HAVING SUM(salan + valdb - valcr) <> 0   ") ;
	}

    $slEsquema = (($pEsquema) ? ' AND det_codcuenta ' . $pEsquema : ' ');
// echo $slEsquema;
    $aSql[] ="SELECT
                    left(cuenta,1) AS GRU,
                    cuenta AS CTA,
                    cue_posicion AS NIV,
                    concat( if(det_idauxiliar=0,cuenta, concat('       .', det_idauxiliar)) )AS CUE,
                    left (concat( repeat(' ', cue_posicion),
                        if (det_idauxiliar = 0 ,
                            cue_descripcion,
                            concat('     ', left(ifnull(per_apellidos,''),20),
                                    left(ifnull(act_descripcion,''),20), ' ',
                                    left(ifnull(per_nombres,''),25) ,
                                    left(ifnull(act_descripcion1, ''),25)
                    ) ) ), ". (($slTipo =='COM')?38:50) . ") AS DES,
                    SUM(round(salan,2)) AS SAN,
                    SUM(round(valcr,2) + 00000000000.00) AS VCR,
                    SUM(round(valdb,2) + 00000000000.00) AS VDB ,
                    SUM(round(valdb-valcr,2) + 00000000000.00) AS SPE,
                    SUM(round(saldo,2) + 00000000000.00) AS SAB,
                    SUM(round(saldo,2) + 00000000000.00) AS SNT
                FROM tmp_balcomp join concuentas on cue_codcuenta = det_codcuenta " .
                         " left join conpersonas on per_codauxiliar = det_idauxiliar
                          left join conactivos on act_codauxiliar = det_idauxiliar
                WHERE cue_posicion + if(det_idauxiliar>0,1,0)  <= ". $ilNivel .
		      $slEsquema ."
                GROUP BY 1,2,3,4,5 "
                . $slSaldoCondicion .
                "ORDER by cuenta, det_idauxiliar,3, 5 ";
    $rs= fSQL($db, $aSql);
    return $rs;
}
/**
*   Procesa la cabecar del peporte
*   @access public
*   @param  object      $rpt        Reference al reporte
*   @param  object      $hdr        referencia al objeto cabecerA
*   @return void
*/
function before_header(&$rpt, &$hdr){
    $ilTxtSize=10;  //
    $ilLeading=0;  //
    include_once ("RptHeader.inc.php");

  }
/** CAbecera de gruop GRU
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_GRU (&$rpt, &$group) {
    global $db;
    $rpt->rptOpt['fontSize']= $rpt->fontSize;
    global $gaAcum, $glColSal;
//    $gaAcum[$group->currValue]['SAN'] += $group->sums['SAN'];
//    $gaAcum[$group->currValue]['VDB'] += $group->sums['VDB'];
//    $gaAcum[$group->currValue]['VCR'] += $group->sums['VCR'];
    if (!isset($gaAcum[$group->currValue][$glColSal])) $gaAcum[$group->currValue][$glColSal] = 0;
    $gaAcum[$group->currValue][$glColSal] += $group->lastRec[$glColSal];
}

/** CAbecera de gruop NIV: Cuando cambia el nivel de la cuenta, cambia el tama� de Font
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_NIV (&$rpt, &$group) {
    global $db;
    if ($group->currValue < 4){
        $rpt->rptOpt['fontSize']= $rpt->fontSize - $group->currValue;
    }
}
/** Pie  de gruop GRU: Cuando cambia el Grupo de balance  de la cuenta, cambia el tama� de Font
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function after_group_GRU (&$rpt, &$group) {
    global $slLastGr;
    $slLastGr = $group->lastValue;
}

function after_group_general (&$rpt, &$group) {
    global $db;
    global $gaAcum, $sgCtaPat, $sgCtaRes, $glColSal,$slLastGr;
    $rpt->pdf->y -=15;
    $slTexto = '';
    $flAcum = 0;
    if ($slLastGr <= $sgCtaPat){
            $slTexto = '----------------- TOTAL ACTIVO, PASIVO Y PATRIMONIO: ';
            $flAcum =0;
            for ($i=1; 4 > $i; $i++){
                $flAcum += isset($gaAcum[$i][$glColSal])?$gaAcum[$i][$glColSal]:0;
            }
    }else{
	    $slTexto = '----------------- TOTAL CUENTAS DE RESULTADOS: ';
            $flAcum = 0;
            for ($i=4; 7 >= $i; $i++){
                $flAcum += isset($gaAcum[$i][$glColSal])?$gaAcum[$i][$glColSal]:0;
            }
    }
    /*switch ($group->lastValue){
        case $sgCtaPat:
            $slTexto = '----------------- TOTAL ACTIVO, PASIVO Y PATRIMONIO: ';
            $flAcum =0;
            for ($i=1; 4 > $i; $i++){
                $flAcum += isset($gaAcum[$i][$glColSal])?$gaAcum[$i][$glColSal]:0;
            }
            break;
        case $sgCtaRes:
            $slTexto = '----------------- TOTAL CUENTAS DE RESULTADOS: ';
            $flAcum = 0;
            for ($i=4; 7 >= $i; $i++){
                $flAcum += isset($gaAcum[$i][$glColSal])?$gaAcum[$i][$glColSal]:0;
            }
            break;
    }*/
//    print_r($gaAcum);
    $rpt->pdf->y -=20;
    if (strlen($slTexto) > 1) {
        if ($rpt->pdf->y <= $rpt->pdf->ez['bottomMargin']) {
            $rpt->pdf->eztext(' ', 10); // Para avanzar a una nueva pagina
            $rpt->pdf->y -=20;
        }
        $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 300, 10, $slTexto);
        $rpt->putTextWrap($rpt->leftBorder + 495, $rpt->pdf->y, 150, 10, number_format ($flAcum, 2, '.', ','));
        $rpt->pdf->y -=15;
    }
}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$slQry   = fGetParam('pQryBal', false);

$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_BOTH);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (120) ;
$ilPeriodo  =fGetParam('pPer', 1);
$igAcumula  =fGetParam('pAcu', 1); // Indicador de acumulacion de saldos
$ilNivel    =fGetParam('pNiv', 6);
$slEsquema  =fGetParam('pEsq', '');
$slTipo     =fGetParam('p','COM');
$rs = fGetData($db, $ilPeriodo, $ilNivel, $slEsquema);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,27.8), "landscape", $slFontName, 8 );
$sgCtaPat = NZ(fDBValor($db, "genparametros", "par_Valor1", "par_Clave = 'CTAPAT'"), '3');
$sgCtaRes = NZ(fDBValor($db, "genparametros", "par_Valor1", "par_Clave = 'CTARES'"), '7');
$slSubTit = fDBValor($db, "conperiodos", "per_FecFinal", "per_Aplicacion = 'CO' AND per_numperiodo = " . $ilPeriodo);
$slLastGr = "";
if ($slTipo == 'GEN' || $slTipo == 'PYG') { // Procesar Balance General 0 PIG
    if ($igAcumula == 1){
    	$rep->subTitle ="ACUMULADO A " . $slSubTit;
    	$glColSal= "SAB";			//Procesar Saldos Acumulados
    }
    else  {
    	$glColSal= "SPE";			//Procesar Saldos del Periodo
    	$rep->subTitle ="CORRESPONDIENTE AL PERIODO " . $slSubTit;
    }
}
else {
    $glColSal= "SAB";			//Procesar Saldos Acumulados
    $rep->subTitle ="ACUMULADO A " . $slSubTit;
}

$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1,1,0.5);
if ($slTipo == 'GEN' || $slTipo == 'PYG') { // Procesar Balance General 0 PIG
    if ($slTipo == 'GEN')    $rep->title="BALANCE GENERAL";
    elseif ($slTipo == 'PYG')    $rep->title="ESTADO DE PERDIDAS Y GANANCIAS"; // Correccion de titulo para PYG
        else $rep->title="SALDOS DE CUENTAS";
    $rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>14, 'justification'=>'center', 'leading'=>14),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>12 , 'justification'=>'center', 'leading'=>12),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>10 , 'justification'=>'center', 'leading'=>10 ));
    $rep->fontSize = 14;
/**
    $rep->colHead = array(
                        'CUE' =>'CUENTA',
                        'DES' =>'DESCRIPCION',
                        'SAB' =>'SALDO FINAL'
                        );
**/
    $rep->colHead = array(
                        'CUE' =>'CUENTA',
                        'DES' =>'DESCRIPCION',
                        $glColSal =>'SALDO FINAL'
                        );
    }
else {
    $rep->fontSize = 10;
    $rep->title="BALANCE DE COMPROBACION";
    $rep->colHead = array(
                        'CUE' =>'CUENTA',
                        'DES' =>'DESCRIPCION',
                        'SAN' =>'SALDO ANTERIOR',
                        'VDB' =>'DEBITOS',
                        'VCR' =>'CREDITOS',
                        'SPE' =>'SALDO DEL PERIODO',
                        'SAB' =>'SALDO FINAL'
                        );
}
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990,  'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "15:2:.:,"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['CUE']->type='C';
$rep->columns['DES']->type='C';
$rep->columns[$glColSal]->zeroes=true;
$rep->columns[$glColSal]->format="15:2:.:,:-o-";

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['CUE']['justification']='left';
$rep->colOpt['DES']['justification']='left';

if ($slTipo == 'GEN' || $slTipo == 'PYG'){ // Procesar Balance General 0 PIG
    $rep->setDefaultColOpt('width', 120);
    $rep->colOpt['CUE']['width']=140;
    $rep->colOpt['DES']['width']=280;
    }
else {
    $rep->setDefaultColOpt('width', 68); //85
    $rep->colOpt['CUE']['width']=55;	 //60
    $rep->colOpt['DES']['width']=155;	 // 140
}
//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;
    $rep->groups['general']->textCol='NOM';
    $rep->addResumeLine('general','-', 'RESULTADOS:', 1);
        $rep->groups['general']->linesBefore=1;

$rep->addGrp('GRU');
$rep->addGrp('NIV');

$gaAcum=array();
$rep->run();
$rep->view($rep->title, $rep->saveFile("CON_BAL_"));
?>
