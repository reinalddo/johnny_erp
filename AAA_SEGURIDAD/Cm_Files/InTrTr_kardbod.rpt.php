<?php
/*
*   InTrTr_kardbod.rpt.php: Kardex de Bodega
*   @author     Fausto Astudillo
*   @param      integer		pQryTar  Condici� de bsqueda
*   @output     contenido pdf del reporte.
*   @todo       Generalizar el reporte para todos
*   fah	10/11/09	Agregar columna de Precio Unitario de transaccion y Pu de saldo
*   esl 21/06/10	Ocultar precio cuando el comprobante no es IB, solo para los movimientos no para el cálculo del saldo inicial
*/
error_reporting(E_ALL);
include("../LibPhp/ezPdfReport_2.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @return object    Referencia del Recordset.
*   @rev              fah ene/23/08     Parametrizacion de fecha inicial de inventario
*/
function &fDefineQry(&$db){
    global $giPerio;
    global $gdDesde;
    global $gdHasta;
    global $iCtoFlag;
    global $giGeneral;
    global $giTipPro;
    $dlFecIni = nz(fDBValor($db, 'genparametros', "par_valor1", "par_clave = 'ININIC' "),'2004-12-31'); // @fah230108: fecha inicial de procesamiento de inventario
    $slCondDat = fGetParam("pQryCom", false);
    if (!$slCondDat){
        $slBodega = fGetParam("com_Emisor", false);
        $slTipCom = fGetParam("com_TipoComp", false);
        $slItem = fGetParam("det_CodItem", false);
        $slItem = $slItem ? ' det_coditem = ' .$slItem : '';
        $slCondDat  = (($slBodega)? " com_Emisor = " . $slBodega :"");
        if ($slItem ) $slCondDat .= (($slCondDat)? " AND " : "" ) . $slItem ;
        if ($slTipCom) $slCondDat .= (($slCondDat)? " AND  " : "" ) . " com_Tipocomp = " . $slTipCom ;
    }
    if (strlen($slCondDat) > 1) $slCondDat .= " AND " ;
    $slConI = " com_feccontab < '" . $gdDesde . "'"; //         Condicion Inicial
    $slConD = " com_feccontab >= '" . $gdDesde . "'";//         Condicion 'Durante'
    $slConF = " com_feccontab <= '" . $gdHasta . "'";//         Condicion Final2;
    $ilLong=($iCtoFlag)?18:40;
   $alSql[] = "CREATE TEMPORARY TABLE tmp_invmovs
                SELECT com_emisor as 'BOD',
                act_grupo AS 'GRU',
                com_feccontab as 'FEC',
                pro_orden AS 'ORD',
                concat(com_tipocomp, '-', com_numcomp) as 'COM',
            	det_coditem AS 'ITE',
            	left(ifnull(com_receptor,left(com_concepto," . $ilLong . "))," .$ilLong . " ) as 'REC',
                uni_abreviatura as 'UNI',
            	(det_cosTotal ) / (det_cantequivale )     as SAN,
            	000000000.00        as VAN,
            	if (pro_signo = 1, det_cantequivale , 000000000.00)  as CIN,
            	if (pro_signo = 1, det_cosTotal , 000000000.00)      as VIN,
            	if (pro_signo = -1 , det_cantequivale , 000000000.00) as CEG,
            	if (pro_signo = -1 , det_cosTotal , 000000000.00)    as VEG,
		000000000.00  as SUN,		
            	det_cantequivale * pro_signo  as SAC,
            	det_cosTotal * pro_signo      as VAC,
            	/* (det_cosTotal * pro_signo) /	(det_cantequivale * pro_signo)  as PUN */
		if(com_tipocomp = 'IB',(det_cosTotal * pro_signo) / (det_cantequivale * pro_signo),000000000.00) as PUN
            FROM	invprocesos JOIN
            	concomprobantes ON pro_codproceso = " . $giTipPro . " AND com_tipocomp = cla_tipotransacc
            	JOIN invdetalle ON det_regnumero = com_regnumero
            	JOIN conactivos ON act_codauxiliar = det_coditem
            	JOIN genunmedida ON uni_codunidad= act_unimedida
            WHERE " . $slCondDat . " com_feccontab BETWEEN '" . $gdDesde . "' AND '" . $gdHasta . "'
                  AND (det_cantequivale <> 0 OR det_cosTotal <> 0)
                  AND com_feccontab >= '2004-12-31'
        ";
//            ORDER BY 1,5,2,3
   if (!$giGeneral)   //        Kardex general no quiebra por bodega
        $alSql[] = "create index I_MOVS ON tmp_invmovs(BOD, ITE, GRU, FEC)";
   else
        $alSql[] = "create index I_MOVS ON tmp_invmovs(ITE, GRU, FEC)";
   $alSql[] = "INSERT INTO tmp_invmovs
             SELECT com_emisor as 'BOD',
                act_grupo AS 'GRU', '" .
                $gdDesde . "' AS 'FEC',
                0 AS 'ORD',
                ' ' as 'COM',
            	det_coditem AS 'ITE',
            	'SALDO ANTERIOR ' as 'REC',
                uni_abreviatura as 'UNI',
            	if(SUM(det_cantequivale * pro_signo) <>0, SUM(det_cosTotal * pro_signo) / SUM(det_cantequivale * pro_signo),0)        as SAN,
            	000000000.00            as VAN,
            	000000000.00  as CIN,
            	000000000.00  as VIN,
            	000000000.00  as CEG,
            	000000000.00  as VEG,
		000000000.00  as SUN,
            	SUM(det_cantequivale * pro_signo)  as SAC,
            	SUM(det_cosTotal * pro_signo)      as VAC,
            	SUM(det_cosTotal * pro_signo) /	SUM(det_cantequivale * pro_signo)  as PUN

            FROM	invprocesos JOIN
            	concomprobantes ON pro_codproceso =  " . $giTipPro . "  AND com_tipocomp = cla_tipotransacc
            	JOIN invdetalle ON det_regnumero = com_regnumero
            	JOIN conactivos ON act_codauxiliar = det_coditem
            	JOIN genunmedida ON uni_codunidad= act_unimedida
            WHERE  " . $slCondDat . $slConI . "  AND (det_cantequivale <> 0 OR det_cosTotal <> 0) AND com_feccontab >= '" . $dlFecIni . "'
            GROUP BY 1,2,3,4,5,6,7,8
            " ;
//             ORDER BY 1,5,2,3
    if (!$giGeneral ){   //                      El kardex general no quiebra por bodega
        $alSql[] = "SELECT * FROM tmp_invmovs
                ORDER BY BOD,GRU,ITE,FEC,ORD,COM";
    }
    else{
        $alSql[] = "SELECT * FROM tmp_invmovs
        ORDER BY GRU,ITE,FEC,ORD,COM";
    }

    $rs= fSQL($db, $alSql);
    if (!$rs) die("NO SE EJECUTo LA CONSULTA: " . $alSql[0]);
    return $rs;
}
/** Process the Report Header
*   You can access any property / method from ezPdfReport Object using var $rpt and group data from variable $group received as parameters
*   To put any text, line, rectangle, etc into your report, use the object $rpt->pdf and its "ez functions" (see ezPdf manual),
*   be care of functions that dont move the insertion point to void text overlapping
*   Note: This function REDEFINES the top margin.
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
 function before_group_BOD (&$rpt, &$group) {
    global $db;
    $slGrupo = fDBValor($db, 'conpersonas', "concat(per_Apellidos, ' ' , per_Nombres) as tmp_bodega", "per_codauxiliar  = " . NZ($group->currValue,0));
    $slText = $group->currValue . " - " . $slGrupo;
    $rpt->pdf->y -=10;
    $rpt->pdf->eztext($slText, 10, array('justification'=>'center', 'leading'=>12));//        Putting text before group data
    }
function before_group_GRU (&$rpt, &$group) {
    global $db;
    $slGrupo = fDBValor($db, 'genparametros', 'par_descripcion', "par_ClaVE  = 'actgru' and PAR_SECUENCIA  = " . $group->currValue);
    $slText = $group->currValue . " - " . $slGrupo;
    $rpt->pdf->y -=10;
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 400, 10, $slText);//        Putting text before group data
    $rpt->pdf->y -=10;
    }
/** CAbecera de gruop ITEM
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_ITE (&$rpt, &$group) {
    global $db;
    $rpt->pdf->y -=10;
    $slText ='ITEM: ' .$group->lastRec['ITE'];
    list($slDescr, $slUni) = fDBValor($db, 'conactivos JOIN genunmedida ON uni_codunidad = act_unimedida',
                                            "concat(act_descripcion,'', act_descripcion1), uni_abreviatura",
                                            "act_codauxiliar  =  " . $group->currValue);
    $slText .= "  " . $slDescr. " (" . $slUni . ")" ;
    $rpt->putTextWrap($rpt->leftBorder, $rpt->pdf->y, 400, 9, $slText);//        Putting text before group data
    $group->resume[0]['resume_text'] = 'SUBTOT. ' . $group->currValue . ": ";

    }
function after_group_ITE (&$rpt, &$group) {
//    $slText ='SALDO:   ' . substr(number_format($group->sums['SAL'], 2, '.', ','), -10);
//    $rpt->pdf->y -= 15;
//    $rpt->pdf->addText(440,$rpt->pdf->y,9,$slText,$angle=0,$wordSpaceAdjust=0);
}
/*
*   @access public
*   @param  array      $data	   Collection of current current lines ready to render
*   @param  array      $group      Collection of current record fields.
*   @return void
 */
function before_row(& $data, & $rec){
    global $rep;
    //$rec['SUN'] = $rec['SAC'] <>0? $rec['VAC'] / $rec['SAC'] : 0;
    if ( $rep->groups['ITE']->sums['SAC']!=0  &&  $rep->groups['ITE']->sums['VAC'] != 0)
	$rec['SUN']  = $rep->groups['ITE']->sums['VAC'] / $rep->groups['ITE']->sums['SAC'];
    else         $rec['SUN'] = $rec['SAC'] <>0? $rec['VAC'] / $rec['SAC'] : 0;
    return true;
}
/** PIE  de gruop VAP
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void

function after_group_VAP (&$rpt, &$group) {
    $rpt->pdf->eztext("   ", 8, array('justification'=>'left', 'leading'=>20));//        Putting text before group data
}
*/
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$db =& fConexion();
set_time_limit (0) ;
$slFontName = 'Helvetica';
$gdDesde=false;
$gdHasta= false;
$giGeneral = fGetParam('pGral',  false);
$giPerio   = fGetParam('pPerio', false);    // Un periodo
$giPerIn   = fGetParam('pPerIn', false);    // Periodo Inicial
$giPerFi   = fGetParam('pPerFi', false);    // Periodo Final
$iCtoFlag  = fGetParam('pCosto', false);
$gdDesde   = fGetParam('pFecI', false);
$gdHasta   = fGetParam('pFecF', false);
$giTipPro  = fGetParam("pTipPro", 1);	// Codigo del proceso a ejecutar

if (!$giPerIn && !$gdDesde) { //                              Si viene como parametro un periodo
    $pe = fDBValor($db, 'conperiodos', 'per_fecInicial, per_FecFinal', "per_aplicacion = 'IN' AND per_numPeriodo = " . $giPerio);
    list ($gdDesde, $gdHasta) = $pe;
}
elseif (!$giPerFi && !$gdHasta) { //                                        Si viene como parametro dos periodos
    $pe = fDBValor($db, 'conperiodos', 'min(per_fecInicial), max(per_FecFinal)', "per_aplicacion = 'IN' AND per_numPeriodo BETWEEN  " . $giPerIn . " AND " . $giPerFi);
    list ($gdDesde, $gdHasta) = $pe;
}
if ($giPerFi && !$gdHasta) { //                                        Si viene como parametro dos periodos
    $pe = fDBValor($db, 'conperiodos', 'min(per_fecInicial), max(per_FecFinal)', "per_aplicacion = 'IN' AND per_numPeriodo BETWEEN  " . $giPerIn . " AND " . $giPerFi);
    list ($gdDesde, $gdHasta) = $pe;
}
$rs = fDefineQry($db);
$rep = new ezPdfReport($rs, array(21.5,27.8), "landscape", $slFontName, 8);
$rep->subTitle="PERIODO DE " . $gdDesde . " A " . $gdHasta;
$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(1,1.5,0.3,0.5);
if ($iCtoFlag){
    $ilAncho = 90;
    $rep->colHead = array(
//                        'GRU'  => "GRUPO",
//                        'ITE' => "COD.",
                        'FEC'  => "FECHA",
                        'COM'  => "COMPROB.",
                        'REC'  => "ORIGEN / DESTINO",
//                        'UNI'  => "U.",
//                        'VAN'  => "COSTO. PREVIO",
                        'CIN'  => "CANT. INGRESOS",
                        'PUN'  => "P.UNI. TRN",			
                        'VIN'  => "COSTO. INGRESOS",
                        'CEG' => 'CANT. EGRESOS',
                        'VEG' => 'COSTO. EGRESOS',
                        'SAC' => 'SLDO FINAL',
                        'VAC' => 'COSTO FINAL',
			'SUN' => 'UNITARIO'
                        );
    }
else {
    $ilAncho = 170;
    $rep->colHead = array(
                        'FEC'  => "FECHA",
                        'COM'  => "COMPROB.",
                        'REC'  => "DESTINO",
//                        'SAN'  => "SLDO. PREVIO",
//                        'VAN'  => "COSTO. PREVIO",
                        'CIN'  => "CANT. INGRESOS",
//                        'VIN'  => "COSTO. INGRESOS",
                        'CEG' => 'CANT. EGRESOS',
//                        'VEG' => 'COSTO. EGRESOS',
                        'SAC' => 'SLDO FINAL',
//                        'VAC' => 'COSTO FINAL'
                        );
}
$rep->rptOpt = array('fontSize'=>8, 'titleFontSize' => 2, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
                      'shaded'=>0, 'showLines'=>0, 'maxWidth'=>990, 'innerLineThickness'=>0);

$rep->setDefaultColPro('format', "12:2:.:,"); //     Default Format for columns  total long:decimal places:thousands sep:decimal sep:
$rep->setDefaultColPro('type', "N");        //      Default column type
$rep->columns['ITE']->type="C";
$rep->columns['UNI']->type="C";
$rep->columns['REC']->type="C";
$rep->columns['FEC']->type="C";
$rep->columns['SAC']->type="A";
$rep->columns['VAC']->type="A";
$rep->columns['SAN']->format="12:4:.:,:-o-";
$rep->columns['SAC']->format="12:2:.:,:-o-";
$rep->columns['SAC']->zeroes=true;
$rep->columns['VAC']->format="12:2:.:,:-o-";
$rep->columns['VIN']->format="12:2:.:,:-o-";
$rep->columns['VEG']->format="12:2:.:,:-o-";
$rep->columns['PUN']->format="6:4:.:,:-o-";
$rep->columns['SUN']->format="6:4:.:,:-o-";
$rep->columns['VAC']->zeroes=true;
$rep->columns['FEC']->repeat=false;

$rep->setDefaultColOpt('justification', 'right');
$rep->colOpt['ITE']['justification']='left';
$rep->colOpt['DES']['justification']='left';
$rep->colOpt['UNI']['justification']='left';
$rep->colOpt['FEC']['justification']='left';
$rep->colOpt['COM']['justification']='left';
$rep->colOpt['REC']['justification']='left';

$rep->setDefaultColOpt('width', 50);
$rep->colOpt['FEC'] ['width']=50;
$rep->colOpt['CIN'] ['width']=60;
$rep->colOpt['CEG'] ['width']=60;
$rep->colOpt['COM'] ['width']= 60;
$rep->colOpt['PUN'] ['width']= 30;
$rep->colOpt['SUN'] ['width']= 30;
$rep->colOpt['REC'] ['width']=$ilAncho;
$rep->colOpt['UNI'] ['width']=20;
$rep->colOpt['VAN'] ['width']=50;
$rep->colOpt['VIN'] ['width']=50;
$rep->colOpt['VEG'] ['width']=50;
$rep->colOpt['VAC'] ['width']=55;


$rep->title = fDBValor($db, 'genparametros', "par_descripcion",  "par_clave= 'INPRO' AND par_secuencia =  " . $giTipPro);


//$rep->addGrp('general');                           // Not required, exist by default
$rep->groups['general']->fontSize=8;                 // set font size for this group (has no efect yet, because ezTable sets fontsize for entire table)
    $rep->groups['general']->textCol='REC';          // set the column for text at resume line of group
    $rep->addResumeLine('general','-', 'SUMA GENERAL *** ', 1);         // Add a resume line for group 'general' using sums in all columns bt default
        $rep->groups['general']->linesBefore=1;
//        $rep->setAggregate('general',0, 'VAN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'VIN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('general',0, 'VEG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('general',0, 'VAC','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

if (!$giGeneral){    // Kardex general no quiebra por bodega
    $rep->title .= " POR BODEGA ";
    $rep->addGrp('BOD');                         // Create a group for column SEM
        $rep->groups['BOD']->fontSize=6;
        $rep->groups['BOD']->textCol='REC';
        $rep->groups['general']->linesBefore=1;
        $rep->addResumeLine('BOD','-', 'TOTAL BODEGA **  ',0);
            $rep->groups['BOD']->linesBefore=1;
    //        $rep->setAggregate('BOD',0, 'VAN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
            $rep->setAggregate('BOD',0, 'VIN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
            $rep->setAggregate('BOD',0, 'VEG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
            $rep->setAggregate('BOD',0, 'VAC','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
}
else $rep->title .=" GENERAL ";

$rep->addGrp('GRU');                         //
    $rep->groups['GRU']->fontSize=6;
    $rep->groups['GRU']->textCol='REC';
    $rep->addResumeLine('GRU','-', 'SUBTOTAL GRUPO  ',0);
        $rep->groups['GRU']->linesBefore=1;
//        $rep->setAggregate('GRU',0, 'SAN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('GRU',0, 'CIN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('GRU',0, 'CEG','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
//        $rep->setAggregate('GRU',0, 'VAN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('GRU',0, 'VIN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('GRU',0, 'VEG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('GRU',0, 'VAC','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

$rep->addGrp('ITE');                         // Create a group for column SEM
    $rep->groups['ITE']->fontSize=6;
    $rep->groups['ITE']->textCol='REC';
    $rep->addResumeLine('ITE','-', 'S U B T O T A L **  ',0);
        $rep->groups['ITE']->linesBefore=1;
//        $rep->setAggregate('ITE',0, 'SAN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('ITE',0, 'CIN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('ITE',0, 'CEG','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
//        $rep->setAggregate('ITE',0, 'VAN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('ITE',0, 'VIN','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
        $rep->setAggregate('ITE',0, 'VEG','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
        $rep->setAggregate('ITE',0, 'VAC','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

$glProTxt='';
$glVapTxt='';
$glMarTxt='';
$rep->run();
$rep->view($rep->title, $rep->saveFile("INV_KDX_ITE_"));
?>



