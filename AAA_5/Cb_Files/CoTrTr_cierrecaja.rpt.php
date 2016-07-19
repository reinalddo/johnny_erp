<?php
/*    Reporte de cierre de caja. Formato HTML
 *    @param   integer  pFecIni     Fecha de Inicio para consulta
 *    @param   integer  pFecFin     Fecha Final de rango
 *
 */
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("adoConn.inc.php");
$db->debug=fGetparam("pAdoDbg",false);
require('Smarty.class.php');
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = 'template';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}

//if (fGetparam("pExcel",false)){
//   header("Content-Type:  application/vnd.ms-excel");
//   header("Expires: 0");
//   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//}
include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');


$fecIni = fGetParam('pFecIni',Date);
$fecFin = fGetParam('pFecFin',Date);

//setlocale(LC_TIME, 'es_ES');
$ini = strftime('%d / %b / %Y',strtotime($fecIni));
$fin = strftime('%d / %b / %Y',strtotime($fecFin));
$subtitulo = $fecIni <> $fecFin ? "Movimientos Desde: ".$ini." Hasta: ".$fin : "Movimientos al ".$ini;//$fecIni;//fGetParam('pCond','');
$Tpl->assign("subtitulo",$subtitulo);


//consulta movimiento de cuenta de caja
//$sSql = "SELECT per_CodAuxiliar cod , 
//        concat(per_Apellidos, ' ', per_Nombres) as txt, sum(det_ValDebito - det_ValCredito) SAL
//        FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero) 
//        LEFT JOIN conpersonas ON (per_CodAuxiliar = det_idauxiliar) 
//        JOIN concategorias ON cat_codauxiliar = per_codauxiliar and cat_Categoria in (11)
//        WHERE det_idauxiliar = 202 AND 
//        com_feccontab BETWEEN '".$fecIni."' AND '".$fecFin."'  and com_TipoComp in ('IC')
//        group by 1,2
//        order by 2,1";
//$rs = $db->execute($sSql);
//$rs->MoveFirst();
//while ($r = $rs->fetchRow()){
//    $agSaldos[$r['cod']]['caja'] += $r['SAL'];
//    $agSaldos[-1]['caja'] += $r['SAL'];
//}
        
//Consulta saldos anteriores de caja
$sSql="SELECT '' AS 'TIP', 'SALDO ANTERIOR ' as 'BEN',
        per_CodAuxiliar cod, concat(per_Apellidos, ' ', per_Nombres) as txt,
       sum(det_ValDebito) AS 'VDB', sum(det_ValCredito) AS 'VCR', sum(det_ValDebito - det_ValCredito+0) AS 'SAL'
       FROM concomprobantes
       JOIN condetalle on (det_RegNumero = com_RegNumero)
       LEFT JOIN conpersonas ON (per_CodAuxiliar = det_idauxiliar)
       JOIN concategorias ON  cat_codauxiliar = per_codauxiliar and cat_categoria in (11)
       LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta)
       WHERE com_feccontab <= '".$fecIni."'  /*and com_TipoComp in ('IC')*/
       GROUP BY 1,2,3,4
       HAVING SAL <> 0 OR SAL IS NOT NULL";
$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r = $rs->fetchRow()){
//    echo "<br/>".$r['cod']." - ".$r['SAL'];
    $agSaldos[$r['cod']]['caja'] += $r['SAL'];//tenia ant
    $agSaldos[-1]['caja'] += $r['SAL'];
}


////Consulta saldos anteriores de bancos
//$sSql = "SELECT det_IDAuxiliar cod,sum(det_ValDebito) AS 'det_ValorDeb', 
//    sum(det_ValCredito) AS 'det_ValorCre', sum(det_ValDebito - det_ValCredito) AS 'SAL' 
//    FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero) 
//    LEFT JOIN conpersonas p ON (p.per_CodAuxiliar = det_IdAuxiliar) 
//    LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar) 
//    LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta) 
//    WHERE com_FecContab < '".$fecIni."' /*AND det_IDAuxiliar=102 */
//    and det_codcuenta <> '100000'
//    ORDER BY det_CodCuenta, det_IDauxiliar";
////echo $sSql;
//$rs = $db->execute($sSql);
//$rs->MoveFirst();
//while ($r = $rs->fetchRow()){
//    echo "<br/>".$r['cod']." - ".$r['SAL'];
//    $agSaldos[$r['cod']]['ant'] += $r['SAL'];
//    $agSaldos[-1]['ant'] += $r['SAL'];
//}

//Consulta cheques anulados
$sSql="select 	per_CodAuxiliar cod
        , concat(per_Apellidos, ' ', per_Nombres) as txt,sum(det_ValDebito - det_ValCredito) SAL
        ,det_ValDebito,det_ValCredito
        FROM concomprobantes
        JOIN condetalle on (det_RegNumero = com_RegNumero)
        LEFT JOIN conpersonas ON (per_CodAuxiliar = det_idauxiliar)
        JOIN concategorias ON  cat_codauxiliar = per_codauxiliar  and cat_Categoria in (10,11)
        WHERE com_feccontab BETWEEN '".$fecIni."' AND '".$fecFin."'
        and (com_EstOperacion=9 or com_tipocomp='AN')";
$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r = $rs->fetchRow()){
    $agSaldos[$r['cod']]['anul'] += $r['SAL'];
    $agSaldos[-1]['anul'] += $r['SAL'];
}

//Consulta cheques N/A
$sSql="SELECT per_CodAuxiliar cod
         , CONCAT(per_Apellidos, ' ', per_Nombres) AS txt,SUM(det_ValDebito - det_ValCredito) SAL
         ,SUM(det_ValDebito),SUM(det_ValCredito)
         FROM concheques_cab cab 
         INNER JOIN concheques_det det ON cab.idbatch=det.idbatch 
         JOIN concomprobantes c ON c.com_regnumero=det.com_regnum 
         JOIN condetalle d ON c.com_regnumero=d.det_regnumero 
         JOIN conpersonas p ON det_idauxiliar=per_CodAuxiliar 
         JOIN concategorias cat ON cat_CodAuxiliar=per_CodAuxiliar AND cat_Categoria=10
         JOIN conChequeUltMov ult ON ult.com_regnum = det.com_regNum AND ult.ultBatch = det.IdBatch
         WHERE tipo=1 AND operacion = 0 AND det_ValCredito<>0
         and fecRegistro BETWEEN '".$fecIni."' AND '".$fecFin."'
         /* AND com_FecContab BETWEEN '2010-07-01' AND '2010-07-31' */
         GROUP BY  per_CodAuxiliar
         ";
         
$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r = $rs->fetchRow()){
    $agSaldos[$r['cod']]['NA'] += $r['SAL'];
    $agSaldos[-1]['NA'] += $r['SAL'];
}



//Consulta del saldo anterior de los cheques N/A
$sSql="SELECT per_CodAuxiliar cod
         , CONCAT(per_Apellidos, ' ', per_Nombres) AS txt,SUM(det_ValDebito - det_ValCredito) SAL
         ,SUM(det_ValDebito),SUM(det_ValCredito)
         FROM concheques_cab cab 
         INNER JOIN concheques_det det ON cab.idbatch=det.idbatch 
         JOIN concomprobantes c ON c.com_regnumero=det.com_regnum 
         JOIN condetalle d ON c.com_regnumero=d.det_regnumero 
         JOIN conpersonas p ON det_idauxiliar=per_CodAuxiliar 
         JOIN concategorias cat ON cat_CodAuxiliar=per_CodAuxiliar AND cat_Categoria=10
         JOIN conChequeUltMov ult ON ult.com_regnum = det.com_regNum AND ult.ultBatch = det.IdBatch
         WHERE tipo=1 AND operacion = 0 AND det_ValCredito<>0
         and fecRegistro  < '".$fecIni."'
         GROUP BY  per_CodAuxiliar
         ";
         
$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r = $rs->fetchRow()){
    $agSaldos[$r['cod']]['SNA'] += $r['SAL'];
    $agSaldos[-1]['SNA'] += $r['SAL'];
}



//Consulta cheques CONFIRMADOS
$sSql="SELECT per_CodAuxiliar cod
         , CONCAT(per_Apellidos, ' ', per_Nombres) AS txt,SUM(det_ValDebito - det_ValCredito) SAL
         ,SUM(det_ValDebito),SUM(det_ValCredito)
         FROM concheques_cab cab 
         INNER JOIN concheques_det det ON cab.idbatch=det.idbatch 
         JOIN concomprobantes c ON c.com_regnumero=det.com_regnum 
         JOIN condetalle d ON c.com_regnumero=d.det_regnumero 
         JOIN conpersonas p ON det_idauxiliar=per_CodAuxiliar 
         JOIN concategorias cat ON cat_CodAuxiliar=per_CodAuxiliar AND cat_Categoria=10
         JOIN conChequeUltMov ult ON ult.com_regnum = det.com_regNum AND ult.ultBatch = det.IdBatch
         WHERE tipo=1 AND operacion = 2 AND det_ValCredito<>0
         AND fecRegistro BETWEEN '".$fecIni."' AND '".$fecFin."'
         GROUP BY  per_CodAuxiliar
         ";
         
$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r = $rs->fetchRow()){
    $agSaldos[$r['cod']]['CF'] += $r['SAL'];
    $agSaldos[-1]['CF'] += $r['SAL'];
}


//Consulta cheques que estén solo emitidos
$sSql="SELECT per_CodAuxiliar cod
         , CONCAT(per_Apellidos, ' ', per_Nombres) AS txt,SUM(det_ValDebito - det_ValCredito) SAL
         ,SUM(det_ValDebito),SUM(det_ValCredito)
         FROM concheques_cab cab 
         INNER JOIN concheques_det det ON cab.idbatch=det.idbatch 
         JOIN concomprobantes c ON c.com_regnumero=det.com_regnum 
         JOIN condetalle d ON c.com_regnumero=d.det_regnumero 
         JOIN conpersonas p ON det_idauxiliar=per_CodAuxiliar 
         JOIN concategorias cat ON cat_CodAuxiliar=per_CodAuxiliar AND cat_Categoria=10
         JOIN conChequeUltMov ult ON ult.com_regnum = det.com_regNum AND ult.ultBatch = det.IdBatch
         WHERE tipo=1 AND operacion = 1 AND det_ValCredito<>0
         AND fecRegistro BETWEEN '".$fecIni."' AND '".$fecFin."'
         GROUP BY  per_CodAuxiliar
         ";
         
$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r = $rs->fetchRow()){
    $agSaldos[$r['cod']]['emit'] += $r['SAL'];
    $agSaldos[-1]['emit'] += $r['SAL'];
}



//Consulta cheques PAGADOS
$sSql="SELECT per_CodAuxiliar cod
         , CONCAT(per_Apellidos, ' ', per_Nombres) AS txt,SUM(det_ValDebito - det_ValCredito) SAL
         ,SUM(det_ValDebito),SUM(det_ValCredito)
         FROM concheques_cab cab 
         INNER JOIN concheques_det det ON cab.idbatch=det.idbatch 
         JOIN concomprobantes c ON c.com_regnumero=det.com_regnum 
         JOIN condetalle d ON c.com_regnumero=d.det_regnumero 
         JOIN conpersonas p ON det_idauxiliar=per_CodAuxiliar 
         JOIN concategorias cat ON cat_CodAuxiliar=per_CodAuxiliar AND cat_Categoria=10
         JOIN conChequeUltMov ult ON ult.com_regnum = det.com_regNum AND ult.ultBatch = det.IdBatch
         WHERE tipo=1 AND operacion = 3 AND det_ValCredito<>0
         AND fecRegistro BETWEEN '".$fecIni."' AND '".$fecFin."'
         GROUP BY  per_CodAuxiliar
         ";
         
$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r = $rs->fetchRow()){
    $agSaldos[$r['cod']]['PG'] += $r['SAL'];
    $agSaldos[-1]['PG'] += $r['SAL'];
}






$acumDep = 0; $acumND = 0;  $acumCheque = 0;    $acumND = 0;
$agSaldos[-1]['dep'] = $acumDep;
$agSaldos[-1]['nc'] = $acumND;
$agSaldos[-1]['cheque'] = $acumCheque;
$agSaldos[-1]['nd'] = $acumND;

//consulto los auxiliares a mostrar
$sSql = "SELECT case when cat_categoria=11 then 1 else 2 end orden,
            per_CodAuxiliar cod, concat(per_Apellidos, ' ', per_Nombres) as txt, cat_categoria cat
                        FROM conpersonas JOIN concategorias ON  cat_codauxiliar = per_codauxiliar
                         WHERE cat_categoria in ( 10, 11)
                        order by per_Apellidos,per_Nombres";

$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r = $rs->fetchRow()){
   
   if (11 != $r['cat'] ){
        //para consultar saldo anterior
        $sSql2 = "SELECT det_IDAuxiliar cod,sum(det_ValDebito) AS 'det_ValorDeb', 
                sum(det_ValCredito) AS 'det_ValorCre', sum(det_ValDebito - det_ValCredito) AS 'SAL' 
                FROM concomprobantes JOIN condetalle on (det_RegNumero = com_RegNumero) 
                LEFT JOIN conpersonas p ON (p.per_CodAuxiliar = det_IdAuxiliar) 
                LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar) 
                LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta) 
                WHERE com_FecContab < '".$fecIni."' AND det_IDAuxiliar=".$r['cod']." 
                and det_codcuenta <> '100000'
                ORDER BY det_CodCuenta, det_IDauxiliar";
     //echo $sSql2;       
         $rs2 = $db->execute($sSql2);
         $rs2->MoveFirst();
         while ($r2 = $rs2->fetchRow()){
             /*$dep += $r2['DEP'];
             $nc += $r2['NC'];
             $cheque += $r2['CHEQUES'];
             $nd += $r2['ND'];
             $tot = $dep+$nc+$cheque+$nd;*/
             //$agSaldos[$r['cod']]['ant'] = 10;
             $agSaldos[$r['cod']]['ant'] = $r2['SAL'];
             //echo "<br/>1  ".$dep." - ".$nc." - ".$cheque." - ".$nd;
             //echo "<br/>2  ".$agSaldos[$r['cod']]['ant'];
             
         }
                 
        //$agSaldos[$r['cod']]['ant'] = $tot - $anu;
        
        
        //******************************** Restar del saldo en libros el saldo de los cheques N/A *******************************************************************
        $agSaldos[$r['cod']]['ant'] = $agSaldos[$r['cod']]['ant'] - $agSaldos[$r['cod']]['SNA'];
        
    
        $agSaldos[$r['cod']]['dep'] = 0;
        $agSaldos[$r['cod']]['nc'] = 0;
        $agSaldos[$r['cod']]['cheque'] = 0;
        $agSaldos[$r['cod']]['nd'] = 0;
        $agSaldos[$r['cod']]['final'] = 0;
        $agSaldos[$r['cod']]['totfin'] = 0;
    
        //para consultar detalle
        $sSql2 = "SELECT com_TipoComp AS 'TIP','' as 'BEN', per_CodAuxiliar cod
         , concat(per_Apellidos, ' ', per_Nombres) as txt,
         CASE when com_TipoComp in (select cla_TipoTransacc from invprocesos
                                        where pro_codproceso=22 and pro_Orden=1)
                 then sum(det_ValDebito - det_ValCredito) else 0 end AS 'DEP',
         CASE when com_TipoComp in (select cla_TipoTransacc from invprocesos
                                        where pro_codproceso=22 and pro_Orden=2)
                 then sum(det_ValDebito - det_ValCredito) else 0 end AS 'NC',
         CASE when com_TipoComp in (select cla_TipoTransacc from invprocesos
                                        where pro_codproceso=22 and pro_Orden=3)
                 then sum(det_ValDebito - det_ValCredito) else 0 end AS 'CHEQUES',
         CASE when com_TipoComp in (select cla_TipoTransacc from invprocesos
                                        where pro_codproceso=22 and pro_Orden=4) 
                 then sum(det_ValDebito - det_ValCredito) else 0 end AS 'ND'
         FROM concomprobantes
         JOIN condetalle on (det_RegNumero = com_RegNumero)
         LEFT JOIN conpersonas ON (per_CodAuxiliar = det_idauxiliar)
         JOIN concategorias ON  cat_codauxiliar = per_codauxiliar 
         LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar)
         LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta)
         WHERE det_idauxiliar = ".$r['cod']." AND com_feccontab BETWEEN '".$fecIni."' AND '".$fecFin."'
         group by 1,2,3
         order by 3,2,1";
     //echo $sSql2;       
         $rs2 = $db->execute($sSql2);
         $rs2->MoveFirst();
         while ($r2 = $rs2->fetchRow()){
             $agSaldos[$r['cod']]['dep'] += $r2['DEP'];
             $agSaldos[$r['cod']]['nc'] += $r2['NC'];
             $agSaldos[$r['cod']]['cheque'] += $r2['CHEQUES'];
             $agSaldos[$r['cod']]['nd'] += $r2['ND'];
             $agSaldos[$r['cod']]['final'] = $agSaldos[$r['cod']]['ant']+$agSaldos[$r['cod']]['dep']+$agSaldos[$r['cod']]['nc']+$agSaldos[$r['cod']]['cheque']+$agSaldos[$r['cod']]['nd'];
             $agSaldos[$r['cod']]['totfin'] = $agSaldos[$r['cod']]['final'];
             //echo "<br/>1  ".$r2['NC'];
             //echo "<br/>2  ".$agSaldos[$r['cod']]['totfin'];
             
         }
         if (0 == $agSaldos[$r['cod']]['final']){
            $agSaldos[$r['cod']]['final'] = $agSaldos[$r['cod']]['ant'];
            $agSaldos[$r['cod']]['totfin'] = $agSaldos[$r['cod']]['final'];
         }


         //************** Restar del saldo de los cheques y de los totales el saldo de los N/A**************************************
         $agSaldos[$r['cod']]['cheque'] = $agSaldos[$r['cod']]['cheque'] - $agSaldos[$r['cod']]['NA'];
         $agSaldos[$r['cod']]['final'] = $agSaldos[$r['cod']]['final'] - $agSaldos[$r['cod']]['NA'];
         $agSaldos[$r['cod']]['totfin'] = $agSaldos[$r['cod']]['totfin'] - $agSaldos[$r['cod']]['NA'];


         //echo "<br/>  ".$r['cod']." - ".print_r($agSaldos[$r['cod']]);
         $agSaldos[-1]['ant'] += $agSaldos[$r['cod']]['ant'];
         $agSaldos[-1]['dep'] += $agSaldos[$r['cod']]['dep'];
         $agSaldos[-1]['nc'] += $agSaldos[$r['cod']]['nc'];
         $agSaldos[-1]['cheque'] += $agSaldos[$r['cod']]['cheque'];
         $agSaldos[-1]['nd'] += $agSaldos[$r['cod']]['nd'];
         $agSaldos[-1]['final'] += $agSaldos[$r['cod']]['final'];
         $agSaldos[-1]['totfin'] += $agSaldos[$r['cod']]['totfin'];
         //echo "<br/>  ".$r['cod']." - ".$agSaldos[-1]['ant']." - ".$agSaldos[$r['cod']]['ant'];
         

         
          
   }else{
        $agSaldos[$r['cod']]['final'] = $agSaldos[$r['cod']]['ant'];
        $agSaldos[$r['cod']]['totfin'] = $agSaldos[$r['cod']]['caja']+$agSaldos[$r['cod']]['ant'];
        $agSaldos[-1]['ant'] += $agSaldos[$r['cod']]['ant'];
        $agSaldos[-1]['final'] += $agSaldos[$r['cod']]['final'];
        $agSaldos[-1]['totfin'] += $agSaldos[$r['cod']]['totfin'];
   }
}
//}
//print_r($agSaldos);



//echo "<br/>".count($agSaldos);
//echo "<br/>".$agSaldos[102]['dep'];

$Tpl->assign("agSaldos", $agSaldos);
//$acumula = 0;
//$Tpl->assign("acumula",$acumula);


//echo basename($_SERVER[ "PHP_SELF"],".php");
$filename = basename($_SERVER[ "PHP_SELF"],".php");
$Tpl->assign("agArchivo", $filename);

//$rs = $db->execute($sSql . $slFrom);
if(0 == count($agSaldos)){
//    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
//    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
//    
    if (!$Tpl->is_cached('CoTrTr_cierrecaja.tpl')) {
            }
//    
            $Tpl->display('CoTrTr_cierrecaja.tpl');
}
?>