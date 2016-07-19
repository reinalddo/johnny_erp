	<?php
include("../LibPhp/ezPdfReport.php");	

function &fGetData($pSql, $ilPeriodo, $ilNivel) {
    global $db;
    $aSql=Array();
    $aSql[] ="DROP TABLE IF EXISTS tmp_auxiliares";
    $aSql[] ="drop table  if exists tmp_balcomp";

    $aSql[] ="CREATE TEMPORARY TABLE tmp_auxiliares
                SELECT
                        ucase( rpad(concat( left(per_Apellidos, 20), ' ', left(ifnull(per_Nombres,' '), 15)), 28, '-') )as tmp_descripcion,
                        per_codauxiliar as tmp_codauxiliar
                FROM conpersonas";
    $aSql[] ="INSERT INTO tmp_auxiliares
                SELECT  ucase( rpad(concat( left(act_descripcion, 20), ' ', left(ifnull(act_descripcion1,' '), 15)), 28, '-') )as tmp_descripcion,
                        act_codauxiliar as tmp_codauxiliar
                FROM conactivos";
                
    $aSql[] ="CREATE INDEX i_tmpaux on tmp_auxiliares(tmp_codauxiliar)";
/**
*               Valores de cuentas ascendentes
**/
    $aSql[] ="CREATE TEMPORARY TABLE  tmp_balcomp
	            SELECT
                    red_codcuenta as cuenta ,
                    red_Ascendent,
                    cc.cue_padre as cue_padre ,
                    cc.cue_id as cue_id,
                    red_codcuenta  AS det_codcuenta,
                    0 as det_idauxiliar,
                    cc.cue_tipmovim as cue_tipmovim,
                    sum(det_valdebito - det_valcredito -0.00) as salan,
                    0.00 as valdb, 0.00 as valcr,
                    sum(det_valdebito  - det_valcredito) as saldo
 	            FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
	            WHERE com_numperiodo < ". $ilPeriodo . " AND
                    com_estProceso = 5 AND
	                det_regnumero = com_regnumero AND
                    concuentas.cue_codcuenta = det_codcuenta AND
	                red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent
                    AND red_codcuenta
                GROUP BY 1,2,3,4, 5,6
                ORDER BY 1,2" ;
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
                    sum(det_valdebito - det_valcredito) as salan,
                    0 as valdb, 0 as valcr,
                    sum(det_valdebito  - det_valcredito) as saldo
                FROM concomprobantes JOIN condetalle on det_regnumero = com_regnumero
                    JOIN concuentas ON cue_codcuenta = det_codcuenta
                    LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
                WHERE com_numperiodo < ". $ilPeriodo . "  AND com_estProceso = 5 AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0
                GROUP BY 1,2,3,4, 5,6,7
                HAVING saldo <> 0
                ORDER BY 1,2";
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
                    0 as det_idauxiliar,
                    cc.cue_tipmovim as cue_tipmovim,
                    sum(0) as salan,
                    sum(det_valdebito)  as valdb ,
                    sum(det_valcredito) as valcr,
                    sum(det_valdebito - det_valcredito) as saldo
                FROM concomprobantes, condetalle, concuentas, conredcuentas, concuentas cc
                WHERE com_numperiodo = ". $ilPeriodo . " AND com_estProceso = 5 AND
                    det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND
                    red_cueid = concuentas.cue_id AND cc.cue_id = red_ascendent AND red_codcuenta
                GROUP BY 1,2,3,4, 5,6, 7
                ORDER BY 1,2";
/**
*       Cuentas de movimiento del periodo
**/

    $aSql[] ="INSERT INTO tmp_balcomp
               SELECT det_codcuenta as cuenta , 9 as red_Ascendent, cue_id as cue_padre,
                    0  as cue_id,
	                det_codcuenta, det_idauxiliar,
              	    1 as cue_tipmovim,
                    sum(0.00) as salan,
	                sum(det_valdebito)  as valdb ,
                    sum(det_valcredito) as valcr,
	                sum(det_valdebito  - det_valcredito) as saldo
                FROM concomprobantes JOIN condetalle on det_regnumero = com_regnumero
                     JOIN concuentas ON cue_codcuenta = det_codcuenta
                     LEFT JOIN tmp_auxiliares ON tmp_codauxiliar = det_idauxiliar
	            WHERE com_numperiodo = ". $ilPeriodo . "  AND com_estProceso = 5 AND
	                det_regnumero = com_regnumero AND concuentas.cue_codcuenta = det_codcuenta AND det_idauxiliar <> 0
             GROUP BY 1,2,3,4,5,6
             ORDER BY 1,7";

    $aSql[] = "CREATE INDEX i_tmpsal on tmp_balcomp(cuenta, red_ascendent)";
    $aSql[] ="SELECT
                    left(cuenta,1) AS GRU,
                    cuenta AS CTA,
                    cue_posicion AS NIV,
                    concat( if(det_idauxiliar=0,cuenta, concat('       .', det_idauxiliar)) )AS CUE,
                    left (concat( repeat('  ', cue_posicion),
                        if (det_idauxiliar = 0 ,
                            cue_descripcion,
                            concat('     ', left(ifnull(per_apellidos,''),13),
                                    left(ifnull(act_descripcion,''),13), ' ',
                                    left(ifnull(per_nombres,''),11) ,
                                    left(ifnull(act_descripcion1, ''),11)
                    ) ) ), 38) AS DES,
                    SUM(salan) AS SAN,
                    SUM(valcr + 0.00) AS VCR,
                    SUM(valdb + 0.00) AS VDB ,
                    SUM(saldo + 0.00) AS SAB,
                    SUM(saldo + 0.00) AS SNT
                FROM ((tmp_balcomp left join concuentas on cue_codcuenta = det_codcuenta )
                         left join conpersonas on per_codauxiliar = det_idauxiliar)
                         left join conactivos on act_codauxiliar = det_idauxiliar
                WHERE cue_posicion + if(det_idauxiliar>0,1,0)  <= ". $ilNivel . "
                GROUP BY 1,2,3,4,5
                ORDER by cuenta, det_idauxiliar,3, 5 ";
/**
                    SUM(ABS(saldo)) AS SAB,
**/
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
    $rpt->rptOpt['fontSize']= 11;
    }
/** CAbecera de gruop NIV: Cuando cambia el nivel de la cuenta, cambia el tamaño de Font
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_NIV (&$rpt, &$group) {
    global $db;
    if ($group->currValue < 4)   $rpt->rptOpt['fontSize']= 11 - $group->currValue;
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
set_time_limit (0) ;
$ilPeriodo=fGetParam('pPer', 1);
$ilNivel  =fGetParam('pNiv', 6);
$rs = fGetData($db, $ilPeriodo, $ilNivel );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,27.8), "landscape", $slFontName, 8);
$rep->title="BALANCE DE COMPROBACION";
//$rep->subTitle="-";
$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1,0.0,0.5);
$rep->colHead = array(
                        'CUE' =>'CUENTA',
                        'DES' =>'DESCRIPCION',
                        'SAN' =>'SALDO ANTERIOR',
                        'VDB' =>'DEBITOS',
                        'VCR' =>'CREDITOS',
                        'SAB' =>'SALDO FINAL'
                        );
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990,  'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "15:2:.:,"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['CUE']->type='C';
$rep->columns['DES']->type='C';
$rep->columns['SAB']->zeroes=true;
$rep->columns['SAB']->format="15:2:.:,:-o-";


$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['CUE']['justification']='left';
$rep->colOpt['DES']['justification']='left';

$rep->setDefaultColOpt('width', 85);
$rep->colOpt['CUE']['width']=60;
$rep->colOpt['DES']['width']=140;


//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='NOM';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'RESULTADOS:', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
        $rep->setAggregate('general',0, 'SNT','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
$rep->addGrp('GRU');
$rep->addGrp('NIV');
$rep->run();
$rep->view($rep->title, $rep->saveFile("TRJ_RES_"));
?>



