<?php
/*
*   LiLiRp_liquid.php: Liquidacion de Compra de Fruta
*   @author     Fausto Astudillo
*   @param      string		pQryLiq  Condici� de bsqueda
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
    $alSql[] = "drop table if exists tmp_liquidaciones";
    $alSql[] = "drop table if exists tmp_totales";
    $alSql[] = "drop table if exists tmp_liqtarjadetal";
    $alSql[] = "drop table if exists tmp_liqgeneral";
    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>      DETALLE DE LIQUIDACIONES *:
    codproductor   = Codigo del productor
    liq_numliquida	= Numero de lliquidacion,
    liq_codrubro,	= Codigo de Rubro,
    RUBRO	= Descripcion de rubro
    GRUPO	= Grupo de rubro (1=Ingresos, 2=Egrsos,
    SECUENCIA     = Secuencia dentro del grupo,
    INDCANTIDAD    = Indicador de que el rubro tiene detalle  de cantidades asociado,
    INDTEXTO	= Indicador de que el rubro tiene texto asociado
    TEXTO              = Texto que debe acompa�r al rubro.
    VALOR              = Valor del rubro (positivo o negativo)
    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>      */
    $alSql[] = "CREATE TEMPORARY  TABLE tmp_totliq
                SELECT com_codreceptor AS tot_PRODUCTOR,
                    concat(per_Apellidos, ' ', per_Nombres) AS tot_NOMBRE,
                    com_numcomp AS tot_numliquida,
                    com_feccontab AS tot_fecha,
                    pro_semana  AS tot_SEMANA,
                    sum((liq_valtotal * rub_IndDbCr) +00000000.00) as tot_VALOR
                FROM   liqprocesos
                       JOIN liqliquidaciones ON liq_numproceso = pro_ID
	                   JOIN liqrubros on rub_codrubro = liq_codrubro
	                   JOIN liqreportes ON rep_reporteID = " . $pTipo . " AND rep_codrubro = liq_codrubro
                       JOIN concomprobantes ON com_tipocomp = 'LQ' and com_numcomp = liq_numliquida
                       JOIN conpersonas ON per_codauxiliar = com_codreceptor
                 WHERE " . $pQry . " GROUP BY 1,2,3,4,5  HAVING tot_valor > 0";
    
/*
    $alSql[] = "CREATE TEMPORARY  TABLE tmp_totales
                SELECT com_codreceptor AS CODPRODUCTOR,
                    com_feccontab AS fecha,
                    pro_semana  AS SEMANA,
                    sum(liq_valtotal * rub_IndDbCr) as VALOR
                FROM   liqprocesos join liqliquidaciones ON liq_numproceso = pro_ID
	                   JOIN liqrubros on rub_codrubro = liq_codrubro
	                   JOIN liqreportes ON rep_reporteID = 20 AND rep_codrubro = liq_codrubro
                       JOIN concomprobantes ON com_tipocomp = 'LQ' and com_numcomp = liq_numliquida
                 WHERE " . $pQry . " GROUP BY 1,2 ";
*/
     $alSql[] = "CREATE TEMPORARY  TABLE tmp_liquidaciones
                SELECT tot_productor AS CODPRODUCTOR,
                    tot_fecha AS fecha,
                    tot_numliquida as liq_numliquida,
                    liq_codrubro,
                    liq_descripcion AS TEXTO ,
                    rub_desclarga  AS RUBRO,
                    rub_grupo AS GRUPO,
                    rub_posordinal AS SECUENCIA,
                    rub_IndCantidad  AS INDCANTIDAD,
                    rub_indtexto AS INDTEXTO,
                    (liq_valtotal * rub_IndDbCr) + 000000000.00 as VALOR
                FROM   tmp_totliq
                       JOIN liqliquidaciones ON liq_numliquida = tot_numliquida
	                   JOIN liqrubros on rub_codrubro = liq_codrubro
                       JOIN liqreportes ON rep_reporteID = " . $pTipo . " AND rep_codrubro = liq_codrubro ";
    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>      DETALLE DEL RUBRO   "FRUTA"  PARA LIQUIDACIONES
    *                                       lA COLUMNA Valfru Garantiza que el valor salga segun el calculo
    */
    $alSql[] = "CREATE TEMPORARY TABLE tmp_liqtarjadetal
                SELECT tad_liqnumero,								
                     concat(buq_descripcion, ' ', emb_numviaje) AS VAPOR,		
                     concat(act_descripcion, ' ', act_descripcion1) AS PRODUCTO,
                     caj_abreviatura AS  EMPAQUE,						
                     tad_valunitario + 0000.0000 AS PRECIO,					
                     sum(tad_cantrecibida - tad_cantrechazada  ) AS CAJAS,
                     SUM(round((tad_cantrecibida - tad_cantrechazada  ) * TAD_VALUNITARIO,2) + 0000000000.00) as VALFRU
                FROM tmp_totliq
                    JOIN liqtarjadetal ON tad_liqnumero = tot_numliquida
                    JOIN liqtarjacabec ON tar_NUmTarja = tad_NumTarja
                    JOIN liqembarques  ON emb_refoperativa =  tac_refOperativa
                    JOIN liqbuques ON buq_codbuque = emb_codvapor
                    JOIN conactivos ON act_codauxiliar = tad_codproducto
                    JOIN liqcajas ON caj_codcaja = tad_codcaja
                GROUP BY 1,2,3,4,5 ";
    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>      TABLA CON DATOS FINALES, INCLUYE RUBROS CON DETALLE Y SIN DETALLE */
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>       se requiere este artificio, porque no funcion�un OUTER  JOIN que estaba planeado */
    $alSql[] = "CREATE  TEMPORARY TABLE tmp_liqgeneral
                SELECT  a.*, b.*
                FROM (tmp_liquidaciones a left join tmp_liqtarjadetal b on b.tad_liqnumero = a.liq_numliquida )
                WHERE a.indcantidad = 1 ";
    $alSql[] = "INSERT INTO tmp_liqgeneral (
                       codproductor, liq_numliquida, liq_codrubro,  TEXTO ,
                       RUBRO,  GRUPO, SECUENCIA,  INDCANTIDAD,   INDTEXTO, VALOR)
                SELECT codproductor, liq_numliquida, liq_codrubro,  TEXTO ,
                       RUBRO,  GRUPO, SECUENCIA,  INDCANTIDAD, INDTEXTO, VALOR
                FROM tmp_liquidaciones 
                WHERE indcantidad = 0 and valor <> 0 ";
    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>      SELECCION DE REGISTROS PARA EL REPORTE:
       Se  obtiene los registros con detalle  (tmp_liquidaciones + tmp_liqtarjadetal ) y  los registros sin detalle (tmp_liquidaciones), agregando el nombre del productor.
       en los registros  de rubros que no requieren detalle (tmp_liquidaciones.inddetal = 0, ejm. cualquiera de los descuentos ), los valores correspondientes al detalle son nulos o cero. En estos casos no imprimir detalle, solo descripcion del rubro y valor.
    Cuando un rubro requiere detalle (tmp_liquidaciones.inddetal = 0, ejm: TOTAL FRUTA), se imprimira la descripcion del rubro, luego el detalle y al final el valor del rubro (o la suma de los detalles)
    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>      */
    $alSql[] = "SELECT concat(conpersonas.per_apellidos, ' ', conpersonas.per_nombres) AS NOM,
                    codproductor    AS COD,
                    liq_numliquida  AS LIQ,
                    fecha           AS FEC,
                    liq_codrubro    AS CRU,
                    texto           AS TEX,
                    if(indcantidad > 0, vapor, rubro)           AS RUB,
                    grupo           AS GRU,
                    secuencia       AS SEC,
                    indcantidad     AS ICA,
                    indtexto        AS ITX,
                    if(indcantidad >0, VALFRU, valor)           AS VAL,
                    producto        AS PRO,
                    empaque         AS EMP,
                    precio          AS PPR,
                    concat(cajas, ' cjs a $')  AS CAJ
                FROM   tmp_liqgeneral JOIN liqreportes ON rep_reporteID = " . $pTipo . "  AND rep_codrubro = liq_codrubro
                    JOIN conpersonas ON per_codauxiliar = tmp_liqgeneral.codproductor
                ORDER BY liq_numliquida, grupo, secuencia ";
    $rs= fSQL($db, $alSql);
    return $rs;
}
/** CAbecera de la liquidacin
*   @access public
*   @param  object      $rpt        Reference to current report object
*   @param  object      $hdr        Reference to current header report object
*   @return void

function before_header(&$rpt, &$hdr){
    $ilTxtSize=10;  //
    $ilLeading=0;  //
    include_once ("RptHeader.inc.php");
  }
*//**
*   Texto acbecera de cada liquidacion
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_COD (&$rpt, &$group) {
    global $db;
    global $ilPag;
    global $gfValor;
    global $ilTipProceso;
    if ($ilPag > 1 ) {
//        if ($gfValor <> 0) {
            $rpt->pdf->y -=10;
            $ltxt = num2letras($gfValor, false, 2);
            $rpt->puttext("SON: " . $ltxt,10,10);
            $gfValor=0;
            $rpt->pdf->ezNewPage();
//        }
    }
    $ilPag +=1;

   //$rpt->pdf->y-=20;
    $rpt->pdf->y-=20;
    $y = $rpt->pdf->y;
    $x=  $rpt->leftBorder;
    $rs = $db->execute("SELECT per_direccion, per_telefono1, per_telefono2, per_ruc FROM conpersonas WHERE per_codauxiliar = " . $group->currValue);
    if ($rs) {
        while (!$rs->EOF) {
            $sltxt= " " . $group->lastRec['NOM'] ;
            $i = (strlen($sltxt) < 109) ? 110 - strlen($sltxt): 1;
            $rs->MoveNext();
        }
    }

    $rs = $db->execute("SELECT tot_fecha, tot_valor, tot_semana FROM tmp_totliq WHERE tot_PRODUCTOR = " . $group->lastRec['COD']);
    if (!$rs) fErrorPage('','NO SE PUDO ACCEDER A INFORMACION DE TOTALES', true,  false);
    if ($rs) {
        $rs->MoveFirst();
        while (!$rs->EOF) {
//            $slFecha=  date("F j, Y", fText2Fecha($rs->fields['fecha']));
            $slFecha=  ($rs->fields['tot_fecha']);
            $flValor=  $rs->fields['tot_valor'];
            $ilSemana = $rs->fields['tot_semana'];
            $rs->MoveNext();
    }
    $rpt->putTextWrap(210, $y, 400, 10, strtoupper($sltxt),  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
    $rpt->putTextWrap(350, $y, 200, 12, $flValor,  $pLead = 0, $pJust = 'right', $pAng = 0, $pTest=0);

    $slCant = strtoupper(num2letras($flValor, false, 2, 2, " US Dolares", " ctvs."));
//    echo $slCant . " " . $gfValor . " " . $flValor ."<br>";
    $y -=  22;
    $rpt->putTextWrap(205, $y, 430, 10, $slCant,  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
    $y -= 30;
    $rpt->putTextWrap(195, $y, 300, 10, "Guayaquil,  " . $slFecha,  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
//    $group->lastRec['NOM'];
    $y = $rpt->pdf->y -30;
//echo "TIPO DE PROCESO: " . $ilTipProceso . "<br>";
    $opCode = 6;
    switch ($ilTipProceso) {
        case 1:                     //                      Liquidacion General
        case 20:                    //                      Primer Cheque Voucher (con rubros autorizados)
             $opCode = 6;
             break;
        case 30:                    //                      Segundo Cheque Vouche (los otros rubros)
            $opCode = 8;
            break;
    }
    $slSql = "SELECT e.com_tipocomp, e.com_numcomp, det_numcheque
                    FROM 	conenlace
                    	JOIN concomprobantes l ON enl_tipo ='LQ' AND l.com_tipocomp = enl_tipo AND l.com_numcomp = enl_ID
                    	JOIN concomprobantes e ON e.com_tipocomp = enl_tipocomp and e.com_numcomp = enl_numcomp
                    	JOIN condetalle ON det_regnumero = e.com_regnumero AND det_codcuenta = '1101020'
                    WHERE l.com_tipocomp ='LQ' and l.com_numcomp = " . $group->lastRec['LIQ'] . " AND enl_opcode = " . $opCode ;
    $rs = $db->execute($slSql);
    if (!$rs) fErrorPage('','NO SE PUDO ACCEDER A INFORMACION DE PRODUCTORES', true,  false);
    $rs->MoveFirst();
    $slCheq = $rs->fields['com_tipocomp'] . "-" .$rs->fields['com_numcomp'] . "     CHEQUE #: " . $rs->fields['det_numcheque'];
    $y = $rpt->pdf->y -188;//               Separacion entre cheque y texto
    $rpt->putTextWrap(400, $y, 430, 9, $slCheq,  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
    $rs = $db->execute("SELECT per_direccion, per_telefono1, per_telefono2, per_ruc FROM conpersonas WHERE per_codauxiliar = " . $group->currValue);
    if (!$rs) fErrorPage('','NO SE PUDO ACCEDER A INFORMACION DE PRODUCTORES', true,  false);
    $rs->MoveFirst();
    $y -=30;
    if ($ilTipProceso == 30) $rpt->putTextWrap(220, $y, 430, 12, "** PRE LIQUIDACION DE COMPRA DE FRUTA **",  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
    else $rpt->putTextWrap(220, $y, 430, 12, "LIQUIDACION DE COMPRA DE FRUTA ",  $pLead = 0, $pJust = 'left', $pAng = 0, $pTest=0);
    $y -=12;
    $sltxt= $group->lastRec['COD'] . '  ' . $group->lastRec['NOM'];
    $rpt->putTextAndLabel(60, $y, $sltxt, 'PRODUCTOR:', 300, 80);
    $rpt->putTextAndLabel(60, $y-12, $rs->fields['per_ruc'], 'RUC /C.I.:', 300, 80);
    $rpt->putTextAndLabel(60, $y-24,$group->lastRec['FEC'], 'FECHA: ' , 550,80);
    $rpt->putTextAndLabel(400, $y-24, $ilSemana, 'SEMANA:', 300, 60);

    $rpt->putTextAndLabel(60,$y-36, $rs->fields['per_direccion']. ',  ' . $rs->fields['per_telefono1'] . ' / ' . $rs->fields['per_telefono2']. "\n", 'DIRECCION:',450, 80 );
    $rpt->pdf->y=$y-30;
//
/**/
    }
}
/**
*   Al termino de procesar cada productor
*/
function after_group_COD (&$rpt, &$group) {
    global $gfValor;
    $x = $rpt->leftBorder;
    $y1 = 40;
    $l=80;
    $gfValor=$group->sums['VAL'];

}
/**
*   Cabecera para cada grupo de rubros
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_GRU (&$rpt, &$group) {
    
    if ($group->currValue == 10) {
//      $rpt->pdf->y-=210;
        $rpt->pdf->y-=20;
        $group->detail = true;
        $rpt->putText ("<b>I N G R E S O S </b>", 10,10);
        $rpt->colOpt['PRO']['width']=110;
        $rpt->colOpt['EMP']['width']=40;
        $rpt->colOpt['CAJ']['width']=85;
        $rpt->colOpt['PPR']['width']=35;
        $rpt->colOpt['VAL']['width']=75;

    }
    else {
        $rpt->pdf->y-=30;
//        $group->detail = false;
        $rpt->putText ("<b>D E S C U E N T O S</b> ", 10,10);
        $rpt->colOpt['PRO']['width']=200;
        $rpt->colOpt['EMP']['width']=1;
        $rpt->colOpt['CAJ']['width']=1;
        $rpt->colOpt['PPR']['width']=1;
        $rpt->colOpt['VAL']['width']=75;
    }
    $rpt->pdf->y +=10;
}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
error_reporting(E_ALL);
$ilPag = 1;
$ilTipProceso= fGetParam('pTipo', 20);//                    Tipo de proceso, si no se viene en GET, el tipo es 20
$slQry   = fGetParam('pQryLiq', false);
$slSem   = trim(fGetParam('pro_Semana', false));
$slGru   = trim(fGetParam('pro_GrupoLiquida', false));
$slPrd   = trim(fGetParam('tac_Embarcador', false));
//if (strlen($slQry)<1){
        if (strlen($slSem)>=1)  $slQry= " pro_Semana = " . $slSem ;
        if (strlen($slGru)>=1)  $slQry.=(($slQry)? " AND ": "" ) . " per_Grupo = " . $slGru ;
        if (strlen($slPrd)>=1)  $slQry.=(($slPrd)? " AND ": "" ) . " com_codreceptor = " . $slPrd ;
//}
if (!$slQry) fErrorPage('','DEBE DEFINIR UN CRITERIO DE SELECCION (Semana y/o Proceso)', true,  false);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry, $ilTipProceso );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = fGetParam('pFont','Helvetica');
$slFontSize = fGetParam('pFSize',12);
//$rep = new ezPdfReport($rs, array(21.5,28), "portrait", $slFontName, $slFontSize);
$rep = new ezPdfReport($rs, array(21.5,28), "portrait", $slFontName, $slFontSize);
//$rep->title="LIQUIDACIONDE COMPRA DE FRUTA";
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>12, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,0.5,1,0.5);
//$rep->margins = array(0.5,0.5,1,0.5);
$rep->colHead = array(
                        'RUB' =>'RUBRO',
//                        'VAP' =>'VAPOR',
                        'PRO' =>'PRODUCTO',
                        'EMP' =>'EMPAQUE',
                        'CAJ' =>'CJAS EMBARC',
                        'PPR'=>'PRE. PROD',
                        'VAL'=>'VALOR');
$rep->rptOpt = array('fontSize'=>12, 'titleFontSize' => 10, 'showHeadings'=>0,'shaded'=>1, 'splitRows'=>0,
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
$rep->columns['PPR']->format="8:4:.:,";
$rep->columns['VAL']->format="9:2:.:,:-o-";

$rep->setDefaultColOpt('justification', 'left');
$rep->colOpt['CAJ']['justification']='right';
$rep->colOpt['PPR']['justification']='right';
$rep->colOpt['VAL']['justification']='right';

$rep->colOpt['RUB']['width']=160;
//$rep->colOpt['VAP']['width']=90;
$rep->colOpt['PRO']['width']=120;
$rep->colOpt['EMP']['width']=40;
$rep->colOpt['CAJ']['width']=110;
$rep->colOpt['PPR']['width']=50;
$rep->colOpt['VAL']['width']=90;


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
