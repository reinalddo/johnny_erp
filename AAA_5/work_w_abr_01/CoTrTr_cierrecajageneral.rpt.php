<?php
/*    Reporte de cierre de caja. Formato HTML
 *    @param   integer  pFecIni     Fecha de Inicio para consulta
 *    @param   integer  pFecFin     Fecha Final de rango
 *    @rev  esl 30/10/2012 Empresas individuales no ven los reportes consolidados
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


/* ****************************************************************************
   @rev  esl 30/10/2012 Empresas individuales no ven los reportes consolidados
******************************************************************************* */
$sSql= "SELECT e.*,  CASE 
		       WHEN e.emp_Consolidacion LIKE '%C%' THEN 'S'
		       ELSE 'N'
		     END AS consolidado
	 FROM seguridad.segempresas e WHERE e.emp_BaseDatos ='".$_SESSION["g_dbase"]."'";
$rs = $db->execute($sSql);
if($rs->EOF){
    echo('PROBLEMA AL BUSCAR EMPRESA');
}else{
   $sSql= "";// limpiar para nueva consulta
   $rs->MoveFirst();
    while ($r = $rs->fetchRow()){
      $consolidado = $r['consolidado'];
   }
}

if ($consolidado == 'N') {
   fErrorPage('','LA EMPRESA ACTUAL NO APLICA REPORTE CONSOLIDADO', true,false);
}
/* ****************************************************************************
   FIN Empresas individuales no ven los reportes consolidados
******************************************************************************* */


//consulta de empresas
$sSql = "select emp_descripcion empresa,emp_basedatos base from seguridad.segempresas
            where emp_estado=1 and emp_consolidacion like '%C%'
            /*emp_grupo='09' and emp_idempresa like '9%'
            and emp_descripcion not like 'EMPRESA%' and emp_descripcion not like '%INVENTARIO%'
            and emp_descripcion not like 'LATIN%'*/
            order by emp_descripcion";
$rs = $db->execute($sSql);
$rs->MoveFirst();
while ($r4 = $rs->fetchRow()){
     
        ///Consulta cheques anulados
        $sSql2="select 	per_CodAuxiliar cod
                , concat(per_Apellidos, ' ', per_Nombres) as txt,sum(det_ValDebito - det_ValCredito) SAL
                ,det_ValDebito,det_ValCredito
                FROM ".$r4['base'].".concomprobantes
                JOIN ".$r4['base'].".condetalle on (det_RegNumero = com_RegNumero)
                LEFT JOIN ".$r4['base'].".conpersonas ON (per_CodAuxiliar = det_idauxiliar)
                JOIN ".$r4['base'].".concategorias ON  cat_codauxiliar = per_codauxiliar  and cat_Categoria in (10,11)
                WHERE com_feccontab BETWEEN '".$fecIni."' AND '".$fecFin."'
                and (com_EstOperacion=9 or com_tipocomp='AN')";
        $rs2 = $db->execute($sSql2);
        if (!$rs2->EOF){
            $rs2->MoveFirst();
            while ($r2 = $rs2->fetchRow()){
                $agSaldos[$r4['empresa']][$r2['cod']]['anul'] += $r2['SAL'];
                $agSaldos[$r4['empresa']][-1]['anul'] += $r2['SAL'];
            }
        }

        $acumDep = 0; $acumND = 0;  $acumCheque = 0;    $acumND = 0;
        $agSaldos[-1]['dep'] = $acumDep;
        $agSaldos[-1]['nc'] = $acumND;
        $agSaldos[-1]['cheque'] = $acumCheque;
        $agSaldos[-1]['nd'] = $acumND;
        
        //consulto los auxiliares a mostrar
        $sSql3 = "SELECT case when cat_categoria=11 then 1 else 2 end orden,
                    per_CodAuxiliar cod, concat(per_Apellidos, ' ', per_Nombres) as txt, cat_categoria cat
                                FROM ".$r4['base'].".conpersonas
                                    JOIN ".$r4['base'].".concategorias ON  cat_codauxiliar = per_codauxiliar
                                 WHERE cat_categoria in ( 10, 11)
                                order by per_Apellidos,per_Nombres";

        $rs3 = $db->execute($sSql3);
        if (!$rs3->EOF){
            $rs3->MoveFirst();
            while ($r = $rs3->fetchRow()){
               
               if (11 != $r['cat'] ){
                    //para consultar saldo anterior
                    $sSql2 = "SELECT det_IDAuxiliar cod,sum(det_ValDebito) AS 'det_ValorDeb', 
                            sum(det_ValCredito) AS 'det_ValorCre', sum(det_ValDebito - det_ValCredito) AS 'SAL' 
                            FROM ".$r4['base'].".concomprobantes
                            JOIN ".$r4['base'].".condetalle on (det_RegNumero = com_RegNumero) 
                            LEFT JOIN ".$r4['base'].".conpersonas p ON (p.per_CodAuxiliar = det_IdAuxiliar) 
                            LEFT JOIN ".$r4['base'].".conactivos ON (act_CodAuxiliar = det_IdAuxiliar) 
                            LEFT JOIN ".$r4['base'].".concuentas ON (cue_codcuenta = det_codcuenta) 
                            WHERE com_FecContab < '".$fecIni."' AND det_IDAuxiliar=".$r['cod']." 
                            and det_codcuenta <> '100000'
                            ORDER BY det_CodCuenta, det_IDauxiliar";
                     //echo "<br/><br/>".$sSql2;
                     $agSaldos[$r4['empresa']][$r['cod']]['ant'] = 0;
                     $rs2 = $db->execute($sSql2);
                     $rs2->MoveFirst();
                     while ($r2 = $rs2->fetchRow()){
                         $agSaldos[$r4['empresa']][$r['cod']]['ant'] = $r2['SAL'];
                         //echo "<br/><br/> sal ".$r2['SAL'];
                         //echo print_r($r2);
                     }
                             
                    //echo "<br/><br/>".$r4['empresa']." -  ".$agSaldos[$r4['empresa']][$r['cod']]['ant']." - ".$r['cod'];
                
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
                     FROM ".$r4['base'].".concomprobantes
                     JOIN ".$r4['base'].".condetalle on (det_RegNumero = com_RegNumero)
                     LEFT JOIN ".$r4['base'].".conpersonas ON (per_CodAuxiliar = det_idauxiliar)
                     JOIN ".$r4['base'].".concategorias ON  cat_codauxiliar = per_codauxiliar 
                     LEFT JOIN ".$r4['base'].".conactivos ON (act_CodAuxiliar = det_IdAuxiliar)
                     LEFT JOIN ".$r4['base'].".concuentas ON (cue_codcuenta = det_codcuenta)
                     WHERE det_idauxiliar = ".$r['cod']." AND com_feccontab BETWEEN '".$fecIni."' AND '".$fecFin."'
                     group by 1,2,3
                     order by 3,2,1";
                 //echo "<br/><br/>".$sSql2;       
                     $rs2 = $db->execute($sSql2);
                     $rs2->MoveFirst();
                     while ($r2 = $rs2->fetchRow()){
                         $agSaldos[$r4['empresa']][$r['cod']]['dep'] += $r2['DEP'];
                         $agSaldos[$r4['empresa']][$r['cod']]['nc'] += $r2['NC'];
                         $agSaldos[$r4['empresa']][$r['cod']]['cheque'] += $r2['CHEQUES'];
                         $agSaldos[$r4['empresa']][$r['cod']]['nd'] += $r2['ND'];
                         $agSaldos[$r4['empresa']][$r['cod']]['final'] = $agSaldos[$r4['empresa']][$r['cod']]['ant']+$agSaldos[$r4['empresa']][$r['cod']]['dep']+$agSaldos[$r4['empresa']][$r['cod']]['nc']+$agSaldos[$r4['empresa']][$r['cod']]['cheque']+$agSaldos[$r4['empresa']][$r['cod']]['nd'];
                         $agSaldos[$r4['empresa']][$r['cod']]['totfin'] = $agSaldos[$r4['empresa']][$r['cod']]['final'];
                         //echo "<br/>1  ".$r2['NC'];
                         //echo "<br/>2  ".$agSaldos[$r['cod']]['ant'];
                         
                     }
                     if (0 == $agSaldos[$r4['empresa']][$r['cod']]['final']){
                        $agSaldos[$r4['empresa']][$r['cod']]['final'] = $agSaldos[$r4['empresa']][$r['cod']]['ant'];
                        $agSaldos[$r4['empresa']][$r['cod']]['totfin'] = $agSaldos[$r4['empresa']][$r['cod']]['ant'];
                     }
                     //echo "<br/><br/>".$r4['empresa']." -  ".$agSaldos[$r4['empresa']][$r['cod']]['final']." - ".$r['cod'];
                     $agSaldos[$r4['empresa']][-1]['ant'] += $agSaldos[$r4['empresa']][$r['cod']]['ant'];
                     $agSaldos[$r4['empresa']][-1]['dep'] += $agSaldos[$r4['empresa']][$r['cod']]['dep'];
                     $agSaldos[$r4['empresa']][-1]['nc'] += $agSaldos[$r4['empresa']][$r['cod']]['nc'];
                     $agSaldos[$r4['empresa']][-1]['cheque'] += $agSaldos[$r4['empresa']][$r['cod']]['cheque'];
                     $agSaldos[$r4['empresa']][-1]['nd'] += $agSaldos[$r4['empresa']][$r['cod']]['nd'];
                     $agSaldos[$r4['empresa']][-1]['final'] += $agSaldos[$r4['empresa']][$r['cod']]['final'];
                     $agSaldos[$r4['empresa']][-1]['totfin'] += $agSaldos[$r4['empresa']][$r['cod']]['totfin'];
               }else{
                    $agSaldos[$r4['empresa']][$r['cod']]['final'] = $agSaldos[$r4['empresa']][$r['cod']]['ant'];
                    $agSaldos[$r4['empresa']][$r['cod']]['totfin'] = $agSaldos[$r4['empresa']][$r['cod']]['caja']+$agSaldos[$r['cod']]['ant'];
                    $agSaldos[$r4['empresa']][-1]['ant'] += $agSaldos[$r4['empresa']][$r['cod']]['ant'];
                    $agSaldos[$r4['empresa']][-1]['final'] += $agSaldos[$r4['empresa']][$r['cod']]['final'];
                    $agSaldos[$r4['empresa']][-1]['totfin'] += $agSaldos[$r4['empresa']][$r['cod']]['totfin'];
               }
            }
        }
}
//print_r($agSaldos);

    //consulta de empresas
    $sSql1 = "select emp_descripcion empresa,emp_basedatos base from seguridad.segempresas
                where emp_estado=1 and emp_consolidacion like '%C%'
                order by emp_descripcion";
    $rs1 = $db->execute($sSql1);
    $rs1->MoveFirst();
    while ($r1 = $rs1->fetchRow()){
        //saldos de caja
        $sSql2="SELECT '' AS 'TIP', 'SALDO ANTERIOR ' as 'BEN',
                per_CodAuxiliar cod, concat(per_Apellidos, ' ', per_Nombres) as txt,
               sum(det_ValDebito) AS 'VDB', sum(det_ValCredito) AS 'VCR', sum(det_ValDebito - det_ValCredito+0) AS 'SAL'
               FROM ".$r1['base'].".concomprobantes
               JOIN ".$r1['base'].".condetalle on (det_RegNumero = com_RegNumero)
               LEFT JOIN ".$r1['base'].".conpersonas ON (per_CodAuxiliar = det_idauxiliar)
               JOIN ".$r1['base'].".concategorias ON  cat_codauxiliar = per_codauxiliar and cat_categoria in (11)
               LEFT JOIN ".$r1['base'].".concuentas ON (cue_codcuenta = det_codcuenta)
               WHERE com_feccontab <= '".$fecIni."'  /*and com_TipoComp in ('IC')*/
               GROUP BY 1,2,3,4
               HAVING SAL <> 0 OR SAL IS NOT NULL";
        
        $rs2 = $db->execute($sSql2);
        $col = 1;
        if (!$rs2->EOF){
            $tot = 0;
            while ($r2 = $rs2->fetchRow()){
                $agConsolidado[$r1['empresa']][$r2['cod']] = $r2['SAL'];
                $agConsolidado[-5][$r2['cod']] += $r2['SAL'];
                $tot += $r2['SAL'];
            }
            $agConsolidado[$r1['empresa']][-1] = $tot;
            $agConsolidado[-5][-1] += $tot;
        }
        
        //Fin de Saldos de Caja
        
        
        //Saldos de Bancos
        $sSql2 = "select per_CodAuxiliar cod, concat(per_Apellidos, ' ', per_Nombres) as txt
               from conpersonas 
               JOIN concategorias ON  cat_codauxiliar = per_codauxiliar and cat_categoria in (10)
               order by cat_categoria";
        $rs2 = $db->execute($sSql2);
        if (!$rs2->EOF){
            $rs2->MoveFirst();
            $tot = 0;
            while ($r2 = $rs2->fetchRow()){
                //echo $agSaldos[$r1['empresa']][$r2['cod']]['final'];
                $agConsolidado[$r1['empresa']][$r2['cod']] = $agSaldos[$r1['empresa']][$r2['cod']]['final'];
                $tot += $agSaldos[$r1['empresa']][$r2['cod']]['final'];
                $agConsolidado[-5][$r2['cod']] += $agSaldos[$r1['empresa']][$r2['cod']]['final'];
            }
            $agConsolidado[$r1['empresa']][-2] = $tot;
            $agConsolidado[$r1['empresa']][-3] = $agConsolidado[$r1['empresa']][-1] + $agConsolidado[$r1['empresa']][-2];
            $agConsolidado[-5][-2] += $tot;
            $agConsolidado[-5][-3] += $agConsolidado[$r1['empresa']][-3];
        }
        
        
        //Fin de saldos de bancos
    }
//print_r($agConsolidado);

//Columnas de caja
$totCabCaja=1;
$sSql2 = "select per_CodAuxiliar cod, concat(per_Apellidos, ' ', per_Nombres) as txt
               from conpersonas 
               JOIN concategorias ON  cat_codauxiliar = per_codauxiliar and cat_categoria in (11)
               order by cat_categoria";
               
$rs2 = $db->execute($sSql2);
$col = 1;
if (!$rs2->EOF){
    $rs2->MoveFirst();
    while ($r2 = $rs2->fetchRow()){
        //echo "<br/><br/>".$r2['txt'];
        $cuenta[$col] = $r2['txt'];
        $codigos[$col] = $r2['cod'];
        $col++;
        $totCabCaja++;
    }
}
$cuenta[$col] = "TOTAL CAJA (1)";
$codigos[$col] = "-1";
$col++;
//Columnas de bancos
$totCabBancos=1;
$sSql2 = "select per_CodAuxiliar cod, concat(per_Apellidos, ' ', per_Nombres) as txt
               from conpersonas 
               JOIN concategorias ON  cat_codauxiliar = per_codauxiliar and cat_categoria in (10)
               order by cat_categoria";
$rs2 = $db->execute($sSql2);
if (!$rs2->EOF){
    //$col = 1;
    $rs2->MoveFirst();
    while ($r2 = $rs2->fetchRow()){
        $cuenta[$col] = $r2['txt'];
        $codigos[$col] = $r2['cod'];
        $col++;
        $totCabBancos++;
    }
}
$cuenta[$col] = "TOTAL BANCOS (2)";
$codigos[$col] = "-2";
$col++;

$cuenta[$col] = "TOTAL CAJA - BANCOS (1+2)";
$codigos[$col] = "-3";

//print_r($cuenta);

$Tpl->assign("agSaldos", $agConsolidado);
$Tpl->assign("agCab", $cuenta);
$Tpl->assign("agCodCab", $codigos);
$Tpl->assign("agTotCabCaja", $totCabCaja);
$Tpl->assign("agTotCabBancos", $totCabBancos);
//$acumula = 0;
//$Tpl->assign("acumula",$acumula);

$filename = basename($_SERVER[ "PHP_SELF"],".php");
$Tpl->assign("agArchivo", $filename);


//$rs = $db->execute($sSql . $slFrom);
if(0 == count($agSaldos)){
//    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
//
    //empresas
    $rs1->MoveFirst();
    $aDet =& SmartyArray($rs1);
    $Tpl->assign("agData", $aDet);
    
   
//    
    if (!$Tpl->is_cached('CoTrTr_cierrecajageneral.tpl')) {
            }
//    
            $Tpl->display('CoTrTr_cierrecajageneral.tpl');
}
?>