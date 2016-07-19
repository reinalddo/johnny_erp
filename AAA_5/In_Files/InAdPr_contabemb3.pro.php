<?php
/*
*   InAdpr_contabem3.pro.php: Contabilizacion de Inventario de Movs de Embarque, version 3
*   @author     Fausto Astudillo
*   @param      string		pQryLiq  Condici� de bsqueda
*   @output     contenido pdf del reporte.
*/
//error_reporting(E_ALL);
session_start();
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
    global $ilSemana;
    $pProc = "tad_liqproceso = ". $ilNumProceso; //                  Condicion de Proceso
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
// se incluye como saldo anterior costeable solo las transacciones de EP / DV, para evitar que auto-ajustes afecten
    $alSql[] =  "CREATE   TABLE tmp_data
                SELECT
                    com_CodReceptor AS PRO,
                    pro_semana AS SEM,
                    det_coditem AS ITE,
                    SUM(det_CantEquivale * pro_Signo) AS 'C01',
                    SUM(det_CosTotal * pro_Signo)     AS 'V01',
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
                    SUM(0.0000) AS 'LCO',
                    SUM(det_CantEquivale * pro_Signo) AS 'CXX',
                    SUM(det_CosTotal * pro_Signo)     AS 'VXX',
                    000000000.0000 AS 'XPA'
              FROM invprocesos  join concomprobantes ON com_TipoComp = cla_TipoTransacc
                     JOIN tmp_productor ON tmp_codProductor = com_CodReceptor
                     JOIN invdetalle ON det_RegNumero = com_RegNumero
                     LEFT JOIN invprecios ON  invprecios.pre_CodItem = det_CodItem
              WHERE pro_codProceso = 5   AND com_RefOperat  < pro_Semana AND det_cantEquivale  <> 0
              GROUP BY 1,2,3
              order by 1,2,3";
/*
              HAVING SUM(det_CantEquivale * pro_Signo) > 0
              */
/*
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
                    SUM(det_CosTotal * pro_Signo)       AS 'VXX',
                    SUM(IF(pro_orden = 7 AND det_cantequivale < 0, det_CosTotal * pro_Signo,0))    AS 'XPA'
              FROM  invprocesos  join concomprobantes ON com_TipoComp = cla_TipoTransacc
                     JOIN tmp_productor ON tmp_codProductor = com_CodReceptor
                     JOIN invdetalle ON det_RegNumero = com_RegNumero
              WHERE pro_codProceso = 5   AND com_RefOperat  = pro_Semana AND det_cantEquivale  <> 0
              GROUP BY 1,2,3
              order by 1,2,3
              ";
    $alSql[] =  "CREATE INDEX tmp_dat ON tmp_data(PRO)" ;
//  SUM(if (CXX =0,V01, 0)) AS 'V01',                        Para arreglar costeo fah-08-31
//  SUM(IF(CXX <>0.0000 , VXX,0.0000)) AS 'VXX',
    $alSql[] ="DROP TABLE IF EXISTS tmp_data2";
    $alSql[] ="CREATE TABLE tmp_data2
                SELECT
                    tmp_nombre AS NOM, PRO,
                    tmp_regnumero AS REG,
                    par_valor4 AS GRU,
                    tad_liqnumero AS LIQ,
                    concat(ITE, '  ', left(act_descripcion,18), ' ',left(act_descripcion1,10)) AS DIT,
                    tmp_producto AS PRD,
                    SEM,
                    SUM(C01) AS 'C01',
                    SUM(if (C01 <>0.0000, V01,0.0000)) AS 'V01',
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
                    SUM(LCO) AS 'LCO',
                    SUM(CXX) AS 'CXX',
                    SUM(VXX) AS 'VXX',
                    SUM(XPA) AS 'XPA',
                    SUM(IF(C07 < 0, C07,0.0000)) AS CCO,
                    SUM(IF(C07 > 0, C07,0.0000)) AS CPA,
                    SUM(IF(C07 < 0, V07,0.0000)) AS VCO,
                    SUM(IF(C07 > 0, V07,0.0000)) AS VPA
            FROM tmp_data
                    JOIN conactivos ON conactivos.act_Codauxiliar = ITE
                    JOIN genparametros ON par_clave = 'ACTGRU' AND par_secuencia = act_grupo
                    JOIN tmp_productor ON tmp_codProductor = PRO
            GROUP BY 1,2,3,4,5,6,7,8

            ORDER BY 1,2,3,4,5";
/**
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
                    
if (C01 >0.0000 OR (C01<0 AND CXX=0), V01,0.0000) AS 'V01',
IF(C03 < 0  AND round(C07,4) >0 AND C04 = 0 AND (C03 + C07) >= 0, 0, V03) AS 'V03',
**/
    $alSql[] ="SELECT
                    NOM, PRO,
                    REG,
                    GRU,
                    LIQ,
                    DIT,
                    PRD,
                    SEM,
                    C01 AS 'C01',
                    if (round(C01,4) >0.0000 , V01,0.0000) AS 'V01',
                    C02 AS 'C02',
                    V02 AS 'V02',
                    C03 AS 'C03',
                    V03 AS 'V03',
                    C04 AS 'C04',
                    V04 AS 'V04',
                    C05 AS 'C05',
                    V05 AS 'V05',
                    C06 AS 'C06',
                    V06 AS 'V06',
                    C07 AS 'C07',
                    V07 AS 'V07',
                    LCO AS 'LCO',
                    CXX AS 'CXX',
                    IF(round(CXX,4) <>0.0000 , VXX, 0.0000) AS 'VXX',
                    XPA AS 'XPA',
                    V07 / (C07) AS PUN,
                    IF(C07 < 0, C07,0) AS CCO,
                    IF(C07 > 0, C07,0) AS CPA,
                    IF(C07 < 0, V07,0) AS VCO,
                    IF(C07 > 0, V07,0) AS VPA
            FROM tmp_data2
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
function before_group_REG () {
    global $db;
    global $ilPag;
    global $ilSemana;
    global $gfValor;
    global $groups;
    global $gfDevNp ;
    $gfDevNp =0;
    $group = $groups['REG'];
    $db->execute("delete from condetalle where det_regnumero = " . $group->currValue);
    $ilPag +=1;
}
function before_group_GRU () {
    global $igAux ;
    global $groups;
    $group = $groups['GRU'];
    $igAux = $group->currValue;
//echo "<br> before producto " . $group->lastRec['GRU'] . " ///  " . $igAux;
}
/**
*   Al termino de procesar cada grupo de items carton o material
*/
function after_group_GRU () {
    global $gfValor;
    global $cla;
    global $db;
    global $igSecue;
    global $igAux;
    global $groups;
    global $cantcjas;
    $group = $groups['GRU'];
//echo "<br> producto " . $group->lastRec['GRU'];
    $igSecue +=1;
    $lastrec= array();
    $fSuma=0;
//print_r($group);
    $fCosto= $group->sums['V04'] * (-1);
//  $fCpCob=($group->sums['V01'] + $group->sums['V02'] + $group->sums['V03']) * (-1);
//echo $group->sums['VXX'];   ------------ ESTA SECCION NO SE APLICA
//die();
//    $flSalf= 0.00;
//    if($group->sums['VXX']>0) $flSalf= $group->sums['VXX'];
//    $fCpCob=($group->sums['V01'] + $group->sums['V02'] + ($group->sums['V03']) * (-1)) - $flSalf;
//-------------
    $fIngre=$group->sums['VCO'] * (-1);
    $fEgres=$group->sums['VPA'] * (-1);
    $fDifer=$fCosto + $fCpCob + $fIngre + $fEgres;
    $lastrec['det_regnumero'] = $group->lastRec['REG'];
    $lastrec['det_estejecucion'] = 0;
    $lastrec['det_fecejecucion'] = 0;
    $lastrec['det_estlibros']   = 0;
    $lastrec['det_feclibros']   = 0;
    $lastrec['det_refoperativa']  = $group->lastRec['SEM'];
    $lastrec['det_numcheque']   = 0;
    $lastrec['det_tipocomp']   = 'LI';
    $lastrec['det_feccheque']   =0;
    $lastrec['det_codcuenta']   = $cla->cla_ctaorigen . $group->lastRec['PRD'];
    if (is_null($group->lastRec['GRU']) || empty($group->lastRec['GRU'])) {
//       fMensaje("UN ITEM NO TIENE DEFINIDO EL 'TIPO DE COSTO' EN " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp'] . "-" . $lastrec['det_secuencia']);
       fErrorPage('',"UN ITEM NO TIENE DEFINIDO EL 'TIPO DE COSTO' EN " . $lastrec['det_tipocomp'] . "-" . $group->lastRec['LIQ'] . "-" . $lastrec['det_secuencia']) ;
    }
// echo "<br> " . $group->lastRec['GRU'] . "  / " . $igAux . " // " . $group->currValue . " //" . $group->lastValue ;
//    $lastrec['det_idauxiliar']  = $group->lastRec['GRU'];
    $cantcjas = 0;
    $cantcjas=fDBValor($db, 'liqtarjadetal', 'SUM(tad_cantrecibida - tad_cantrechazada  )', 'tad_liqnumero = ' . $group->lastRec['LIQ']);
    $lastrec['det_idauxiliar']  = $igAux;
    $lastrec['det_secuencia'] =$igSecue;
    $lastrec['det_valdebito'] = $fCosto;
    $lastrec['det_valcredito'] = 0;
    $lastrec['det_glosa'] = "S-" . $group->lastRec['SEM']. " " . substr($group->lastRec['NOM'],0, 28) . ", " . $cantcjas . " cjs.";
    $lastrec['det_numcomp'] = $group->lastRec['LIQ'];
    $lastrec['det_clasregistro'] = 21;
    if (!fInsDetalleCont($db, $lastrec))
        fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp'] . "-" . $lastrec['det_secuencia']);
}
/**
*   Al termino de procesar cada productor
*/
function after_group_REG () {
    global $gfValor;
    global $cla;
    global $db;
    global $igSecue;
    global $groups;
    global $gfDevNp; // Acumulado de DEvoluciones no Pagadas
    $group = $groups['REG'];
    $gfValor=$group->sums['VXX'];
echo "<tr> <td>  ".$group->lastRec['PRO'] . " - " .$group->lastRec['NOM'] . " </td>";
//--------------------------
   $lastrec= array();
    $fSuma=0; 
    $fCosto= round($group->sums['V04'] * (-1),2);
    $flSalI= 0.00;
    $flSalf= 0.00;  
    if($group->sums['V01']>0 ) $flSalI= $group->sums['V01']; // El saldo Inicial
    if ($group->sums['VXX']>=0) $flSalf= $group->sums['VXX']; // El saldo final
    $fCpCob=$fCpCob= round(($flSalI + $group->sums['V02'] + $group->sums['V03'] - $flSalf) * (-1),2) ;
/**
if ($group->lastRec['PRO']==15381){
//    print_r($group->sums);
    echo $flSalI . " /--/ " . $group->sums['V02'] . " /--/ " . $group->sums['V03'] . " // " . $gfDevNp . " //" . $flSalf;
//    echo $flSalI . " /--/ " . $group->sums['V02'] . " /--/ " . $gfDevNp . " //" . $flSalf;
}
    $gfDevNp = 0;
**/

    $fIngre= round($group->sums['VCO'] * (-1),2);
    $fEgres= round($group->sums['VPA'] * (-1),2);
    $fDifer= round($fCosto + $fCpCob + $fIngre + $fEgres,2);
echo "<td> " . $fCosto . "  </td>" ;
echo "<td> " . $fCpCob . "<br> ". $flSalI . " / " . $group->sums['V02'] . " / " .  $group->sums['V03'] . " / " . $flSalf . " </td>";
echo "<td>  " . $fIngre. "</td>";
echo "<td> " . $fEgres. "</td>";
echo "<td> " . $fDifer. "</td> </tr>" ;
//die();
    $lastrec['det_regnumero'] = $group->lastRec['REG'];
    $lastrec['det_estejecucion'] = 0;
    $lastrec['det_fecejecucion'] = 0;
    $lastrec['det_estlibros']   = 0;
    $lastrec['det_feclibros']   = 0;
    $lastrec['det_refoperativa']  = $group->lastRec['SEM'];
    $lastrec['det_numcheque']   = 0;
    $lastrec['det_tipocomp']   = 'LI';
    $lastrec['det_feccheque']   =0;
    $lastrec['det_codcuenta'] = '';
    $lastrec['det_numcomp'] = $group->lastRec['LIQ'];
    $lastrec['det_codcuenta']   = $cla->cla_ctadestino;
    $lastrec['det_idauxiliar']  = $group->lastRec['PRO'];
    $lastrec['det_secuencia'] =$igSecue+1;
    $lastrec['det_valdebito'] = $fCpCob;
    $lastrec['det_valcredito'] = 0;
    $lastrec['det_glosa'] = "Valor liquidado Semana " . $group->lastRec['SEM'];
    $lastrec['det_clasregistro'] = 21;
    if (!fInsDetalleCont($db, $lastrec))
        fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['det_tipocomp'] . "-" . $lastrec['det_numcomp'] . "-" . $lastrec['det_secuencia']);

    $lastrec['det_codcuenta']   = $cla->cla_ctaingresos;
    $lastrec['det_idauxiliar']  = 0;
    $lastrec['det_secuencia'] =$igSecue+2;
    $lastrec['det_valdebito'] = $fIngre;
    $lastrec['det_valcredito'] = 0;
    $lastrec['det_glosa'] = "S-" . $group->lastRec['SEM'] . ", " . $group->lastRec['NOM'];
    $lastrec['det_clasregistro'] = 21;
    if (!fInsDetalleCont($db, $lastrec))
        fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['com_tipocomp'] . "-" . $lastrec['com_numcomp'] . "-" . $lastrec['det_secuencia']);

    $lastrec['det_codcuenta']   = $cla->cla_ctacosto;
    $lastrec['det_idauxiliar']  = 0;
    $lastrec['det_secuencia'] =$igSecue+3;
    $lastrec['det_valdebito'] = $fEgres;
    $lastrec['det_valcredito'] = 0;
    $lastrec['det_glosa'] = "S-" . $group->lastRec['SEM'] . " " . $group->lastRec['NOM'];
    $lastrec['det_clasregistro'] = 21;
    if (!fInsDetalleCont($db, $lastrec))
        fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['com_tipocomp'] . "-" . $lastrec['com_numcomp'] . "-" . $lastrec['det_secuencia']);

    $lastrec['det_codcuenta']   = $cla->cla_ctadiferencia;
    $lastrec['det_idauxiliar']  = 0;
    $lastrec['det_secuencia'] =$igSecue+4;
    $lastrec['det_valdebito'] = 0;
    $lastrec['det_valcredito'] = $fDifer;
    $lastrec['det_glosa'] = "S-" . $group->lastRec['SEM'] . " " . $group->lastRec['NOM'];
    $lastrec['det_clasregistro'] = 21;
    if (!fInsDetalleCont($db, $lastrec))
        fErrorPage('',"NO SE PUDO GRABAR EL DETALLE DE COMPROBANTE: " . $lastrec['com_tipocomp'] . "-" . $lastrec['com_numcomp'] . "-" . $lastrec['det_secuencia'] );
    $igSecue = 0;
//--------------------------

}
function iniGrps($key=false) {
        global $acols;
        global $columns;
        global $groups;
    	if (!$key) $akeys=array_keys($groups);
    	else {
            if (is_array($key)) $akeys=($key);   // si viene un arrehlo
            else $akeys[] = $key;               // si viene un solo nombre
        }
//        print_r($columns);
        reset($columns);
        $i=0;
        foreach($columns as $col){
        	foreach($akeys  as  $grp) {
			    $groups[$grp]->sums[$col] = 0;    //              Initialize sums for every column
                $groups[$grp]->counts = 0;                //              Initialize counter for the group
            }
//            $columns[$i]->previous = NULL;
            $i++;
        }
        unset($acols);
        unset($akeys);
}
function processEvent($pType, $pGrp='') {
    global $groups;
        $slFuncName=$pType . $pGrp;//                                              name of the function to call ('event' After Group)
        if (strlen($pGrp))   $slFuncName();//                             call the function to simulate "Before/After Group Event"
    }

function processBreak($brkLevel)  {                     // process the group break
        global $alBlankLine;
        global $data;
        global $acols;
        global $agrps;
        global $groups;
        global $record;
        reset($groups);

        $aBreaks = array();
        $brkIni = count($agrps);                // nivel de corte inicial
        $j=count($agrps)-1;
        for ($i=0; $i<= $j; $i++){
            if ($groups[$agrps[$i]]->break) {    // si hay un corte en este grupo
                if ($i < $brkIni)  $brkIni = $i;// el nivel correspondiente es menor al minimo presumido, ahora el mminimo es el actual
            }
            if ($i >= $brkIni) $aBreaks[] = $agrps[$i]; // incluir todos los grupos menores al nivel de corte (mayor indice en el arreglo)
        }
        
//      print_r($agrps);
      foreach(array_reverse($aBreaks,TRUE) as $grp){      // para todos los grupos procesables en orden inverso;
                $groups[$grp]->break = false;
                $data=null;
                $data = Array();
                if ($groups[$grp]->afterEvent ) {
                    processEvent('after_group_', $groups[$grp]->name); //                         process a event
                }
                iniGrps($grp);//                                                Initialize cummulatives
      }
      foreach($aBreaks as $grp){      // para todos los grupos procesables en orden ascendent;
                $groups[$grp]->break = false;
                $data=null;
                $data = Array();
                if ($groups[$grp]->afterEvent ) {
                    processEvent('before_group_', $groups[$grp]->name); //                         process a event
                }
                $groups[$grp]->lastRec = $record;
                iniGrps($grp);//                                                Initialize cummulatives
      }
}

function addResumeLine($pGrp, $pAgg='S', $pTxt="", $pBll=0) {
    global $columns;
    global $groups;
    global $acols;
        $alres= array();
        $alres['resume_text'] = $pTxt;
        $groups[$pGrp]->linesBefore=$pBll;
        foreach($columns as $col) {
           $alres[$col] = $pAgg;
        }
        $groups[$pGrp]->resume[] = $alres;
        unset($alres);
    }
function setAggregate($pGrp, $pRes=0, $pCol=false, $pTipo='S') {
    global $columns;
    global $groups;
    global $acols;
    if ($pCol)   $groups[$pGrp]->resume[$pRes][$pCol] = $pTipo;
}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
$ilPag = 1;
$ilSemI= fGetParam('pSemI', false);
$ilSemF= fGetParam('pSemF', false);
if (!$ilSemI || !$ilSemF) die('NO SE HADEFINO LA SEMANA DE INICIO O LA DE FIN DEL PROCESO');
$ilSemana=0;
$ilProduc= fGetParam('pPrd', false);
$ilFontSize=fGetParam('pFsz', 0);
$ilNumProceso = fGetParam('pPro', 0);
$igSecue = 0;
$igAux = 0;
$cantcjas=0;
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
echo "<br><br>CONTABILIZACION DE EMBARQUES DESDE LA SEMANA " . $ilSemI . " HASTA LA " . $ilSemF;
$slQry   = fGetParam('pQryLiq', false);

for ($ilSemana =$ilSemI; $ilSemF >= $ilSemana; $ilSemana++) {
    echo "<br><br>SEMANA " . $ilSemana . " <br>";
    $slQry_2   = 'tac_semana = ' . $ilSemana . ($slQry ? " AND " . $slQry : "");
    $rs = fDefineQry($db, $slQry_2 );
    $rs->MoveFirst();
    $rep->subTitle="CONTABILIZACION DE EMBARQUE ";
    $data = array();
    $row  = array();
    if ($rs->EOF) die("** LA SEMANA NO HA SIDO COSTEADA AUN**");
    if ($ilSemana == $ilSemI) {   //                  EN la primera semana que se procesa, se define los grupos
        $columns = array();
        $acols = array();
        $groups = array();
        $grpProd = new clsGrp('REG');
        $grpGrup= new clsGrp('GRU');
        $groups['REG'] = $grpProd;
        $groups['GRU'] = $grpGrup;
        $recno=0;
        $agrps = array_keys($groups);
        $idat=0;
        $columns =array_keys($rs->fields);
        $acols=array_keys($columns);
        addResumeLine('REG','-', 'S U B T O T A L **  ',0);
        addResumeLine('GRU','-', 'S U B T O T A L **  ',0);
                setAggregate('REG',0, 'V01','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
                setAggregate('REG',0, 'V02','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
                setAggregate('REG',0, 'V03','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('REG',0, 'V04','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('REG',0, 'V05','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('REG',0, 'V06','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('REG',0, 'V01','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('REG',0, 'VCO','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('REG',0, 'VPA','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('REG',0, 'VXX','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'

                setAggregate('GRU',0, 'V01','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
                setAggregate('GRU',0, 'V02','S');  // Change aggregate to '-' (nothing)  for column 'C2' in resumline 1
                setAggregate('GRU',0, 'V03','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('GRU',0, 'V04','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('GRU',0, 'V05','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('GRU',0, 'V06','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('GRU',0, 'V07','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('GRU',0, 'VCO','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('GRU',0, 'VPA','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
                setAggregate('GRU',0, 'VXX','S');  // Change aggregate to '-' (nothing)  for column 'CO' in resumline 1 of Group 'CO'
    }
    else {
        iniGrps('GRU');
        iniGrps('REG');
    }
    //print_r($groups);
    //-------
    echo "<table border=1 style='font-size:12; '>";
    echo "<tr align='center'><td style='width:250;' > PRODUCTOR </td>";
    echo "<td style='width:100; text-align:right'> COSTO: </td>" ;
    echo "<td style='width:100; text-align:right'> CXC: </td>";
    echo "<td style='width:100; text-align:right'> SUM COB: </td>";
    echo "<td style='width:100; text-align:right'> SUM PAG: </td>";
    echo "<td style='width:100; text-align:right'> DIFEREN: </td> </tr>" ;
    $gfDevNp =0; // Suma de DEvoluciones no Pagadas
    while (!$rs->EOF) {
        $grp=count($groups);
        $ilbreaks=0;
        $record = $rs->FetchRow();// -----------------                    DATA RECORD TO PROCESS
        if ($record['CXX'] == 0) $record['VXX']=0;
        $recno+=1;
        $brkLevel=0;
        $brkLevelMin=0;
    //echo "<br> rec: " .$recno . " " . $record['GRU'];
        if ($recno == 1) { //
            for ($i=count($agrps)-1; $i>=0; $i--) {
                $slGroup = $agrps[$i];
                $groups[$slGroup]->lastRec=$record;
                $groups[$slGroup]->currValue = $record[$slGroup];
                $groups[$slGroup]->lastValue = NULL;
                if ($groups[$slGroup]->beforeEvent ) { processEvent('before_group_', $slGroup);  }
            }
        }
    // echo $record['REG']. " / " . $record['PRO']. " / " . $record['GRU']. " / <br>" ;
        foreach ($record as $col => $val) {//                            FOR Every Field in record
                if(isset($groups[$col]) ) { //                                When  a group for this column exist analize except for first record
                    $groups[$col]->counts +=1;//                              Increment Record counting for group
    //                $groups[$col]->lastRec=$record;
                    if ($recno != 1) { //                                                   If is not the first record
                        if ($groups[$col]->currValue != $record[$col]){ //                                               If the column value has changed or it is the last record
                            $groups[$col]->lastValue = $groups[$col]->currValue;
                            $groups[$col]->break = true;  //                              break Flag for this col group
                            $ilbreaks+=1; //                                                    Col change counter
                            $brkLevel = array_search($col, $agrps);//                           Level of break, for recursive process
                            if ($brkLevel || $brkLevel > $brkLevelMin) $brkLevel = $brkLevelMin ; //   point to lowest level
                            $vlColValue = '';
                        }
                    }
                    $groups[$col]->currValue = $record[$col]; //          Set the current value for the column break
                    
                }
        } //                            Fin Rec procesamiento
        $idat++;
        if ($ilbreaks ) {
            processBreak($brkLevel);
            unset($data);
            $idat=0;
            $ilcount=count($agrps)-1;
    /**        for ($l=$brkLevel; $l<=$ilcount; $l++){
                $slGroup=$agrps[$l];
                if ($groups[$slGroup]->beforeEvent ) {
                    processEvent('before_group_', $slGroup); //   process the group headers for next section
                }

            }
    **/
        }
        reset($agrps);
        reset($record);
        foreach ($record as $col => $val) {//                            FOR Every Field in record acumulate
            foreach ($agrps as $grp) {
                if(isset($groups[$grp]->resume[0][$col]) && $groups[$grp]->resume[0][$col]  == "S" ) {
                    if (!isset($groups[$grp]->sums[$col])) $groups[$grp]->sums[$col] = 0;
                    $groups[$grp]->sums[$col] += $val;
                }
            }
        }
  /**
       if ($record['PRO'] == 15381) {
//                print_r($record);
                echo "<br> " . $record['DIT'] . " /--/ " . $record['V01'] . " /--/ " . $record['V02'] . " // " . $record['V03'] . " //" . $record['CXX'] . " //" . $record['VXX'];
                echo "<br>" . $record['C03']. " == " . $record['CPA'] . "<br>" ;
                echo "<br>" . $record['C03'] . " // " . $record['CPA'] . " // " . $record['V03'] . " // " . $record['XPA']. " // " . $gfDevNp . "<br>";
            }
              **/
    //    if ($rs->EOF) $this->lastGrp=true;//                                 To force the last group after last record
    }
    after_group_GRU();
    after_group_REG();
    echo "</table>";
//--------
}
?>
