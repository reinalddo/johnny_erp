<?php
/*
*   LiLiRp_liquid.php: Liquidacion de Compra de Fruta, Ditribuido por CIA
*   @author     Fausto Astudillo
*   @param      string		pQryLiq  Condici� de bsqueda
*   @output     contenido pdf del reporte.
*   @rev	fah 15/08/08	 Eliminar identificacion de cabecera de comprobante (pedido por E.Castro.
*   @rev	fah 25/09/08	 Agrupar rubros(No legales) en uno solo para liquidacion sujeta a nueva ley
*   @rev	fah 05/10/08    Cambiar titulo de comprobate (BORRADO DE LIQUIDACION) y eliminar Nombre de Cia.
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
    $alSql[] = "drop table if exists tmp_liquidaciones";
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
	$alSql[] = "DROP TABLE IF EXISTS tmp_totliq";
    $alSql[] = "CREATE /*TEMPORARY*/  TABLE tmp_totliq
                SELECT com_codreceptor AS tot_PRODUCTOR,
					ldi_codempresa as tot_codempresa,
                    concat(per_Apellidos, ' ', per_Nombres) AS tot_NOMBRE,
                    com_numcomp AS tot_numliquida,
                    com_feccontab AS tot_fecha,
                    pro_Semana AS tot_semana,
                    sum(ldi_valtotal * rub_IndDbCr) as tot_VALOR
                FROM   liqprocesos
						JOIN liqliquidacionesdist ON ldi_numproceso = pro_ID
	                   JOIN liqrubros on rub_codrubro = ldi_codrubro
                       JOIN concomprobantes ON com_tipocomp = 'LQ' and com_numcomp = ldi_numliquida
                       JOIN conpersonas ON per_codauxiliar = com_codreceptor
                 WHERE " . $pQry . " GROUP BY 1,2,3,4,5,6 ";
    $alSql[] = "DROP TABLE IF EXISTS tmp_liquidaciones";
/*	$alSql[] = "CREATE TEMPORARY TABLE tmp_liquidaciones
                SELECT tot_productor AS CODPRODUCTOR,
                    tot_fecha AS fecha,
                    tot_numliquida as liq_numliquida,
                    tot_semana AS liq_semana,
					tot_codempresa,
                    liq_codrubro,
                    liq_descripcion AS TEXTO ,
                    rub_desclarga  AS RUBRO,
                    rub_grupo AS GRUPO,
                    rub_posordinal AS SECUENCIA,
                    rub_IndCantidad  AS INDCANTIDAD,
                    rub_indtexto AS INDTEXTO,
                    liq_valtotal * rub_IndDbCr as VALOR
                FROM   tmp_totliq
                       JOIN liqliquidaciones ON liq_numliquida = tot_numliquida
	                   JOIN liqrubros on rub_codrubro = liq_codrubro"; */
	$alSql[] = "CREATE TEMPORARY TABLE tmp_liquidaciones
				SELECT tot_productor AS CODPRODUCTOR,
                    tot_fecha AS fecha,
                    tot_numliquida as liq_numliquida,
                    tot_semana AS liq_semana,
					tot_codempresa,
                    ldi_codrubro AS liq_codrubro,
                    rub_desclarga AS TEXTO ,
                    rub_desclarga  AS RUBRO,
                    rub_grupo AS GRUPO,
                    rub_posordinal AS SECUENCIA,
                    rub_IndCantidad  AS INDCANTIDAD,
                    rub_indtexto AS INDTEXTO,
                    ldi_valtotal * rub_IndDbCr as VALOR
                FROM   tmp_totliq
                       JOIN liqliquidacionesdist ON ldi_numliquida = tot_numliquida and ldi_codempresa = tot_codempresa
	               JOIN liqrubros on rub_codrubro = ldi_codrubro
				   ";
    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>      DETALLE DEL RUBRO   "FRUTA"  PARA LIQUIDACIONES */
    $alSql[] = "DROP TABLE IF EXISTS tmp_liqtarjadetal";
    $alSql[] = "CREATE /*TEMPORARY*/ TABLE tmp_liqtarjadetal
                SELECT tad_liqnumero,
						tad_codempresa,
                     concat(buq_descripcion, ' ', emb_numviaje) AS VAPOR,		
                     concat(act_descripcion, ' ', act_descripcion1) AS PRODUCTO,
                     caj_abreviatura AS  EMPAQUE,						
                     tad_valunitario + 000000.0000 AS PRECIO,					
                     sum(tad_cantrecibida - tad_cantrechazada  ) AS CAJAS,
                     SUM(round((tad_cantrecibida - tad_cantrechazada  ) * TAD_VALUNITARIO,2)) as VALFRU
                FROM tmp_totliq
                    JOIN liqtarjadetal ON tad_liqnumero = tot_numliquida and tad_codempresa= tot_codempresa
                    JOIN liqtarjacabec ON tar_NUmTarja = tad_NumTarja
                    left JOIN liqembarques  ON emb_refoperativa =  tac_refOperativa
                    left JOIN liqbuques ON buq_codbuque = emb_codvapor
                    JOIN conactivos ON act_codauxiliar = tad_codproducto
                    JOIN liqcajas ON caj_codcaja = tad_codcaja
                GROUP BY 1,2,3,4,5 ";
    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>      TABLA CON DATOS FINALES, INCLUYE RUBROS CON DETALLE Y SIN DETALLE */
    /*>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>       se requiere este artificio, porque no funcion�un OUTER  JOIN que estaba planeado */
    $alSql[] = "DROP TABLE IF EXISTS tmp_liqgeneral";
    $alSql[] = "CREATE  /*TEMPORARY*/ TABLE tmp_liqgeneral
                SELECT  a.*, b.*
                FROM (tmp_liquidaciones a
						left join tmp_liqtarjadetal b on b.tad_liqnumero = a.liq_numliquida AND b.tad_codempresa = a.tot_codempresa )
                WHERE a.indcantidad = 1 ";
    $alSql[] = "INSERT INTO tmp_liqgeneral (
                       codproductor, tot_codempresa, liq_numliquida, liq_codrubro,  TEXTO ,
                       RUBRO,  GRUPO, SECUENCIA,  INDCANTIDAD,   INDTEXTO, VALOR)
                SELECT codproductor, tot_codempresa, liq_numliquida, liq_codrubro,  TEXTO ,
                       RUBRO,  GRUPO, SECUENCIA,  INDCANTIDAD, INDTEXTO, VALOR
                FROM tmp_liquidaciones
                WHERE indcantidad = 0 and valor <> 0 ";
    /* >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>      SELECCION DE REGISTROS PARA EL REPORTE:
       Se  obtiene los registros con detalle  (tmp_liquidaciones + tmp_liqtarjadetal ) y  los registros sin detalle (tmp_liquidaciones), agregando el nombre del productor.
       en los registros  de rubros que no requieren detalle (tmp_liquidaciones.inddetal = 0, ejm. cualquiera de los descuentos ), los valores correspondientes al detalle son nulos o cero. En estos casos no imprimir detalle, solo descripcion del rubro y valor.
    Cuando un rubro requiere detalle (tmp_liquidaciones.inddetal = 0, ejm: TOTAL FRUTA), se imprimira la descripcion del rubro, luego el detalle y al final el valor del rubro (o la suma de los detalles)
                        ifnull(texto, rep_TitLargo)           		AS TEX,
    >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>      */
    $alSql[] = "SELECT
					concat(conpersonas.per_apellidos, ' ', conpersonas.per_nombres) AS NOM,
                    codproductor    AS COD,
					tot_codempresa  AS CIA,
                    liq_numliquida  AS LIQ,
                    fecha           AS FEC,
                    liq_semana      AS SEMANA,

                    rep_TitLargo           		AS TEX,
                    if(indcantidad > 0, vapor, ifnull(rep_TitLargo, rubro))           AS RUB,
                    grupo           AS GRU,

                    producto        AS PRO,
                    empaque         AS EMP,
                    precio          AS PPR,
                    concat(cajas, '  cjs a $ ')  AS CAJ,
                    sum(if(indcantidad >0, VALFRU, valor))           AS VAL
                FROM   tmp_liqgeneral
                    JOIN liqreportes on rep_reporteID = " . fGetParam("pTipo", 30) . " AND rep_codrubro = liq_codrubro
                    JOIN conpersonas ON per_codauxiliar = tmp_liqgeneral.codproductor
		    GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13
                ORDER BY NOM, CIA, liq_numliquida, grupo, REP_POSORDINAL ";
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
    //include_once ("RptHeader.inc.php");  #fah15/08/08 #fah05/10/08
  }
/**
*   Al cambio de productor
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_COD (&$rpt, &$group) {
	fCabeceradeLiq($rpt, $group);
}
/**
*   Al cambio de empresa
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function before_group_CIA (&$rpt, &$group) {
	//fCabeceradeLiq($rpt, $group);
}

/**
*   Texto acbecera de cada liquidacion
*   @access public
*   @param  object      $group      Reference to report object
*   @param  object      $group      Reference to current groups data
*   @return void
*/
function fCabeceradeLiq(&$rpt, &$group){
    global $db;
    global $ilPag;
    global $gfValor;
    if ($ilPag > 1 ) {
        if ($gfValor <> 0) {
            $ltxt = num2letras($gfValor, false, 2);
            $rpt->puttext("SON: " . $ltxt,10,10);
            $gfValor=0;
            $rpt->pdf->ezNewPage();
        }
    }
    $ilPag +=1;
    
    $x=  $rpt->leftBorder;
    //define('ADODB_ASSOC_CASE', 0);
    $slZon = $db->getOne("select par_descripcion, par_clave from conpersonas join genparametros on par_clave =  'LSZON'  and par_secuencia = per_SubZona AND per_codauxiliar = " . $group->lastRec['COD']);
    $rs = $db->execute("SELECT per_direccion, per_telefono1, per_telefono2, per_ruc FROM conpersonas WHERE per_codauxiliar = " . $group->lastRec['COD']);
    if ($rs) {
        while (!$rs->EOF) {
	//    echo "REC";
	//    print_r($rs->fields);
			$rpt->putTextWrap(250, $rpt->pdf->y+10 , 200, 10, $group->lastRec['CIA'],  0);
            $sltxt= 'PRODUCTOR:   ' . $group->lastRec['COD'] . '  ' . $group->lastRec['NOM'] . '    RUC / CI: '. $rs->fields['per_Ruc'];
            $i = (strlen($sltxt) < 109) ? 110 - strlen($sltxt): 1;
            $sltxt .= str_repeat(' ',$i);
            $rpt->putText($sltxt, 9,10);
            //$rpt->putText('DIRECCION:      ' . $rs->fields['per_direccion']. ',  ' . $rs->fields['per_telefono1'] . ' / ' . $rs->fields['per_telefono2']."\n" , 8,8);
            $sltxt2= 'DIRECCION:      ' . $rs->fields['per_Direccion']. ',  ' . $rs->fields['per_Telefono1'] . ' / ' . $rs->fields['per_Telefono2'];
            $i = (strlen($sltxt2) < 109) ? 110 - strlen($sltxt2): 1;
            $rpt->putText($sltxt2, 9,10);
	    $sltxt2 = ' SEMANA: ' . fGetParam("pro_Semana",0)  /*. '                 FECHA: ' . $group->lastRec['FEC'] */;
            $rpt->putText($sltxt2, 9,10);
	    $sltxt2 = ' ZONA: ' . $slZon  /*. '                 FECHA: ' . $group->lastRec['FEC'] */;
	    //$rpt->putText($sltxt2, 9, 10);
	    $rpt->putTextWrap(450, $rpt->pdf->y+10 , 200, 10, $sltxt2,  0);
	    $rs->MoveNext();
    }
    //define('ADODB_ASSOC_CASE', 2);
    $y = $rpt->pdf->y -6;
//    $rpt->pdf->setLineStyle(0.1,'square');
    $rpt->pdf->ezrRoundRectangle($x-5, $y, 515, 65,20);
    $y1=$rpt->pdf->y +30;
    //$rpt->pdf->line($x -5, $y1, 550, $y1);
//    $rpt->pdf->ezrRoundRectangle($x-5, $rpt->pdf->y -15, 505, 40,25);
    $rpt->pdf->y -=15;
    }
}
/**
*   Al termino de procesar cada productor
*/
function after_group_COD (&$rpt, &$group) {
    global $gfValor;
//    $rpt->pdf->setLineStyle(1,'square');
    $x = $rpt->leftBorder;
    $y1 = 25;
    $l=80;
    $rpt->pdf->ezrRoundRectangle($x, 5, 515, 70, 30);
    $x+=10;
    $alTxt = array("Elaborado " . $_SESSION["g_user"], "Revisado", "Autorizado", "Recibi Conforme");
    for ($i=0; $i<4; $i++) {
        $rpt->pdf->line($x, $y1, $x+$l , $y1);
	$rpt->putTextWrap($x+15, $y1-6, 200, 5, $alTxt[$i],  0, $pJust = 'left');
        $x+=20+$l;
    }
    $slText = "DEJANDO CONSTANCIA QUE LOS DESCUENTOS ARRIBA INDICADOS HAN SIDO LEGAL y DEBIDAMENTE AUTORIZADOS  POR EL SUSCRITO";
    $rpt->putTextWrap(330, 10, 200, 5, $slText,  0, $pJust = 'left');
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
        $group->detail = true;
        $rpt->putText ("<b>I N G R E S O S </b>", 10,10);
        $rpt->colOpt['PRO']['width']=110;
        $rpt->colOpt['EMP']['width']=40;
        $rpt->colOpt['CAJ']['width']=85;
        $rpt->colOpt['PPR']['width']=30;
        $rpt->colOpt['VAL']['width']=80;

    }
    else {
//        $group->detail = false;
        $rpt->putText ("<b>D E S C U E N T O S</b> ", 10,10);
        $rpt->colOpt['PRO']['width']=200;
        $rpt->colOpt['EMP']['width']=1;
        $rpt->colOpt['CAJ']['width']=1;
        $rpt->colOpt['PPR']['width']=1;
        $rpt->colOpt['VAL']['width']=80;
    }
    $rpt->pdf->y +=10;
}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$ilPag = 1;
$slQry   = fGetParam('pQryLiq', false);
$slSem   = fGetParam('pro_Semana', false);
$slGru   = fGetParam('pro_GrupoLiquida', false);
$slPrd   = fGetParam('tac_Embarcador', false);
if (!$slQry){
        if (strlen($slSem)>=1)  $slQry= " pro_Semana = " . $slSem ;
        if (strlen($slGru)>=1)  $slQry.=(($slQry)? " AND ": "" ) . " per_Grupo = " . $slGru ;
        if (strlen($slPrd)>=1)  $slQry.=(($slQry)? " AND ": "" ) . " com_codreceptor = " . $slPrd ;
}
if (!$slQry) fErrorPage('','DEBE DEFINIR UN CRITERIO DE BUSQUEDA (Semana y/o Productor / Grupo)', true,  false);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> no hay acceso al servidor");
$db->debug=fGetParam('pAdoDbg', 0);
set_time_limit (0) ;
$rs = fDefineQry($db, $slQry );
//$rep = new ezPdfReport($rs, "A4", "portrait", "Times", 8);
//rep = new ezPdfReport($rs, array(29,15.8), "landscape", "Helvetica", 6);
$slFontName = 'Helvetica';
$rep = new ezPdfReport($rs, array(21.5,14), "portrait", $slFontName, 10);
$slTitulo = $db->GetOne("select rep_titlargo from liqreportes where rep_reporteID = " . fGetParam("pTipo", 30).  " and rep_nivel = 1");
$rep->title = $slTitulo;
/*if (fGetParam("pTipo", 30) == 30)
    $rep->title="DESCUENTOS AUTORIZADOS" ; //"PER LIQUIDACION DE COMPRA DE FRUTA";  #fah15/08/08 #fah05/10/08
else
    $rep->title="LIQUIDACION DE COMPRA DE FRUTA";
														      */
//$rep->subTitle="-";
//$rep->condition=fGetParam('pCond', '');
$rep->titleOpts=array('T'=>array('font'=>$slFontName, 'fontSize'=>10, 'justification'=>'center', 'leading'=>12),
                      'S'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10),
                      'C'=>array('font'=>$slFontName, 'fontSize'=>8 , 'justification'=>'center', 'leading'=>10 )); // Options for T=Title, S=Subtitle, C=Condition
$rep->margins = array(0.5,0.0,0,0.5);
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
$rep->columns['PPR']->format="6:3:.:,";
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
$rep->colOpt['VAL']['width']=75;


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
$rep->addGrp('CIA');
    $rep->groups['CIA']->fontSize=10;
    //$rep->groups['CIA']->textCol='PRO';
    //$rep->addResumeLine('CIA','-', 'VALOR NETO (s.e.u.o.)',0);
    //$rep->groups['CIA']->linesBefore=2;
    //$rep->setAggregate('CIA',0, 'VAL','S');

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
