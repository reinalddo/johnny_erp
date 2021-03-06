<?php
/*
*   InTrTr_kardbod.rpt.php: Kardex de Bodega
*   @author     Fausto Astudillo
*   @param      integer		pQryTar  Condici� de bsqueda
*   @output     contenido pdf del reporte.
*   @todo       Generalizar el reporte para todos
*/
error_reporting(E_ALL);
include("General.inc.php");
include("GenUti.inc.php");
include_once("adodb.inc.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @return object    Referencia del Recordset.
*/
function &fDefineQry(&$db){
    global $giPerio;
    global $gdDesde;
    global $gdHasta;
    global $iCtoFlag;
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
/**
*   ITE:    Codigo de Item
*   FEC:    Fecha de Transaccion
*   ORD:    orden en el que se consideran las transacciones
*   REG:    Registro Interno del comprobante (para ordenar las transacc)
*   SEC:    Secuenca de cada cetalle
*   TIP:    Tipo de Comprobante
*   NUM:    Numero de Comprobante
*   IND:    Indicador de costeo: 1= Modifica el Costo promedio, 0=No Afecta al Costo promedio, es afectado por �
*           los movimientos que no estan en pro_codproceso=1, vienen con este indicador = 0
*   ACU:    Indicador de Acumulacion al saldo:  0= No Acumula, !0 = Acumula
*   CAN:    Cantidad que afecta al saldo
*   VAL     Valor que afecta al saldo
*   CTR     Cantidad Neta de la transaccion ( para determinar el costo de la transaccion)
**/

    $alSql[] = "DROP TABLE IF EXISTS tmp_invmovs";
   $alSql[] = "CREATE TEMPORARY TABLE tmp_invmovs
           SELECT
                det_coditem AS 'ITE',
                com_feccontab AS 'FEC',
                ifnull(pro_orden,9999) as 'ORD',
                com_regnumero as 'REG',
                det_secuencia AS 'SEC',
                com_tipocomp as 'TIP',
                com_numcomp as 'NUM',
                if(isnull(pro_orden),0,cla_indicosteo) AS 'IND',
                ifnull(pro_signo,0) as 'SIG',
                cla_lisprecios AS 'LPR',
            	det_cantequivale * ifnull(pro_signo,00000.00)  as CAN,
            	det_cosTotal * ifnull(pro_signo,0000000.0000)      as VAL,
            	det_cantequivale                        as CTR
            FROM	concomprobantes JOIN
                genclasetran ON cla_tipocomp = com_tipocomp LEFT JOIN
            	invprocesos  ON pro_codproceso = 1 AND cla_tipotransacc = com_tipocomp JOIN
            	invdetalle   ON det_regnumero = com_regnumero
            WHERE com_feccontab BETWEEN '" . $gdDesde . "' AND '" . $gdHasta . "'
                  AND (det_cantequivale <> 0 OR det_cosTotal <> 0)
                  AND com_feccontab >= '2007-12-31'
        ";
   $alSql[] = "INSERT INTO tmp_invmovs
           SELECT
                det_coditem AS 'ITE',
                '$gdDesde' AS 'FEC',
                0 as 'ORD',
                0 AS 'REG',
                0 AS 'SEC',
                '@' as 'TIP',
                0 as 'NUM',
                1 AS 'IND',
                1 as 'SIG',
                1 AS 'LPR',
				SUM(det_cantequivale * pro_signo)  as CAN,
            	SUM(det_cosTotal * pro_signo)      as VAL,
            	SUM(det_cantequivale * pro_signo)  as CTR
            FROM	invprocesos JOIN
                genclasetran ON cla_tipocomp = cla_tipotransacc JOIN
            	concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc
            	JOIN invdetalle ON det_regnumero = com_regnumero
            WHERE com_feccontab <'" . $gdDesde . "'
                  AND (det_cantequivale <> 0 OR det_cosTotal <> 0)
                  AND com_feccontab >= '2007-12-31'
            GROUP BY 1,2,3,4,5,6,7,8,9,10
            " ;
   $alSql[] = "create index I_MOVS ON tmp_invmovs(ITE, FEC, ORD, REG, SEC)";

    $alSql[] = "SELECT * FROM tmp_invmovs
                ORDER BY ITE, FEC, ORD, REG, SEC";

    $rs= fSQL($db, $alSql);
    if (!$rs) die("NO SE EJECUTO LA CONSULTA: " . $alSql[0]);
    return $rs;
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
    $db =& fConexion();
    set_time_limit (0) ;
    $slFontName = 'Helvetica';
    $gdDesde=false;
    $gdHasta= false;
    $giPerio   = fGetParam('pPerio', false);    // Un periodo
    $giPerIn   = fGetParam('pPerI', false);    // Periodo Inicial
    $giPerFi   = fGetParam('pPerF', false);    // Periodo Final
    $iCtoFlag  = fGetParam('pCosto', false);
    $iteDbg     = fGetParam('pIteDbg', false);        // Item a Depurar
    $dbg        = (strlen($iteDbg)>0?true:false);        // Depurar el proceso
    $iCtoFlag  = fGetParam('pCosto', false);
    if (!$giPerIn) { //                              Si viene como parametro un periodo
        $pe = fDBValor($db, 'conperiodos', 'per_fecInicial, per_FecFinal', "per_aplicacion = 'IN' AND per_numPeriodo = " . $giPerio);
        list ($gdDesde, $gdHasta) = $pe;
    }
    else{ //                                        Si viene como parametro dos periodos
        $pe = fDBValor($db, 'conperiodos', 'min(per_fecInicial), max(per_FecFinal)', "per_aplicacion = 'IN' AND per_numPeriodo BETWEEN  " . $giPerIn . " AND " . $giPerFi);
        list ($gdDesde, $gdHasta) = $pe;
    }
    $rs = fDefineQry($db);

    $rs->MoveFirst();
    $inicio = microtime();
    $txt =  "<br> <center> COSTEO DE INVENTARIO DESDE $gdDesde HASTA $gdHasta </center> <br> " .
            "<center> Inicia&nbsp;&nbsp;&nbsp;&nbsp;: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g") . " </center> ";


    //print_r($groups);
    //-------
/*
    echo "<table border=1 style='font-size:12; '>";
    echo "<tr align='center'><td style='width:250;' > PRODUCTOR </td>";
    echo "<td style='width:100; text-align:right'> COSTO: </td>" ;
    echo "<td style='width:100; text-align:right'> CXC: </td>";
    echo "<td style='width:100; text-align:right'> SUM COB: </td>";
    echo "<td style='width:100; text-align:right'> SUM PAG: </td>";
    echo "<td style='width:100; text-align:right'> DIFEREN: </td> </tr>" ;
*/
    $recno=0;
    $lastItem=NULL;
    $salCant=0;
    $salValo=0;
    $ultCost=0;
    if ($dbg) echo "<html><head><style>.td {border:1px solid black;}</style></head>
                    <body><table style='border:1px solid black; border-collapse:collapse'>
                    <tr style='border:1px solid black;'>
                        <td>TIPO TR</td><td>NUM</td><td>ORD</td><td>FECHA</td><td>COD. ITEM</td><td>CANTIDAD</td>
                        <td>VALOR</td><td>SAL CAN</td><td>SAL VAL</td><td>ULT COST</td><td>COST TRAN</td>
                        <td>COST UNIT</td><td>COST PROM *</td><tr>";
    if ($rs) {
        while ($rec = $rs->FetchRow()) {
            $recno+=1;
            if ($rec['ITE'] <> $lastItem){
                $salCant=0;
                $salValo=0;
                $lastItem = $rec['ITE'];
                $cosTran=0;
            }
            if ($rec['ITE'] == '@'){
                    $salCant = $rec['CAN'];
                    $salValo = $rec['VAL'];
                }
            else {
                if ($rec['IND']) {  //              Transaccion costeadora
                    $salCant += $rec['CAN'];
                    $salValo += $rec['VAL'];
                    if($rec['CAN'] <> 0 ) $ultCost = $rec['VAL'] / $rec['CAN'];
                }
                else {              //              transaccion costeable, debe regrabarse
                    if ($salCant <> 0 ) {
                        $ultCost = round($salValo / $salCant,6);
                        $cosTran = round(($rec['CTR'] * $salValo) / $salCant,2);
                        }
                    else $cosTran = round($rec['CTR'] * $ultCost,2);
                    $salCant += $rec['CTR'] * $rec['SIG'];      // Saldo afectado por la transacc actual
                    if ($salCant == 0 ) $cosTran = $salValo ;   // Si el saldo en cant es cero, el costo de transacc es = costo saldo
                    $salValo += $cosTran * $rec['SIG'];         // Nuevo saldo en valores
//echo "<br> $rec['ITE']  $salCant  $salValo  TRAN: $cosUnit   $rec['CTR']";
                    if ($rec['CTR'] <> 0 ) $cosUnit = round($cosTran  / $rec['CTR'],4);
                    else $cosUnit=0;
                    $slSql = "UPDATE invdetalle SET det_cosunitario = " . $cosUnit . ",
                                                det_cosTotal = " . $cosTran ;
					if ($rec['LPR'] == 1) { // Si la transaccion tiene definido que el Precio = costo
					    $slSql .= ", det_valunitario = " . $cosUnit . ",
                                                det_valTotal = " . $cosTran;
					}
					$slSql .=" WHERE det_regnumero = " . $rec['REG']. " AND det_secuencia = " . $rec['SEC'] ;
                    $db->execute($slSql);
                }
            }
                if ($dbg && $rec['ITE'] == $iteDbg ) echo "<tr><td>" .
                    $rec['TIP'] . "</td><td>" . $rec['NUM'] . "</td><td>" . $rec['ORD'] . "</td><td>" .
                    $rec['FEC'] . "</td><td>" .
                    $rec['ITE'] . "</td><td>" . $rec['CAN'] * $rec['SIG'] . "</td><td>" .
                    $rec['VAL'] * $rec['SIG'] . "</td><td>" . $salCant . "</td><td>" .
                    $salValo .    "</td><td>" . $ultCost   .  "</td><td>" . $cosTran . "</td><td>" .
                    $cosUnit . "</td><td>" . ($salCant <>0? $salValo / $salCant :'-0-') . "</td><tr>";
        }
        if ($recno > 0) { 
        	$slSql =" UPDATE invprocesos JOIN 
            				  concomprobantes ON pro_codproceso = 1 AND com_tipocomp = cla_tipotransacc
            				  SET com_estproceso = 5
							  WHERE com_feccontab BETWEEN '" . $gdDesde . "' AND '" . $gdHasta . "'
                  					AND com_feccontab >= '2004-12-31' ";
            $db->execute($slSql);
        }
}
if ($dbg ) echo "</table ></body>";
$final = microtime();
$segun = round(microtime_diff($inicio ,$final),2);
$minut = ($segun / 60);
$txt .= "<br> Finaliza: " . date ("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
$txt .= "<br> Registros procesados: $recno   ";
$txt .= "<br> TIEMPO UTILIZADO:      ".
         round($minut,2) . " mins. <br>";
echo "<center> $txt <br> PROCESAMIENTO OK </center>";
$txt.= " ." ;
$db->close();
?>



