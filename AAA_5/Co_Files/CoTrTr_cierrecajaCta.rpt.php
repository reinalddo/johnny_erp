<?php
/*    Reporte de cierre de caja. Formato HTML
 *    @param   integer  pFecIni     Fecha de Inicio para consulta
 *    @param   integer  pFecFin     Fecha Final de rango
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
        $this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}
include("../LibPhp/excelOut.php");
$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');


$fecIni = fGetParam('pFecIni',Date);
$fecFin = fGetParam('pFecFin',Date);

$ini = strftime('%d / %b / %Y',strtotime($fecIni));
$fin = strftime('%d / %b / %Y',strtotime($fecFin));
$subtitulo = $fecIni <> $fecFin ? "Movimientos Desde: ".$ini." Hasta: ".$fin : "Movimientos al ".$ini;//$fecIni;//fGetParam('pCond','');
$Tpl->assign("subtitulo",$subtitulo);

// Eliminar la tabla si existe.
$sSql="drop table tmp_cierre";
$db->execute($sSql);

//Crear tabla, en este select se traen todos los auxiliares del cierre de caja, tengan o no movimientos:
$sSql="CREATE TABLE tmp_cierre as
         (
                /*SALDOS DEL PERIODO QUE SE ESTÁ CONSULTANDO*/
                SELECT per_CodAuxiliar, CONCAT(per_Apellidos, ' ', per_Nombres) AS aux, cat_Categoria
                        ,det_IDAuxiliar, det_CodCuenta
                        ,pro_codProceso
                        ,par_valor1
                        ,pro_Orden
                        ,000000000000.00 AS 'INI'
                        ,000000000000.00 AS 'NA'
                        ,000000000000.00 AS 'EMI'
                        ,CASE WHEN (pro_Orden= 1 and cat_Categoria != 11) THEN SUM(IF(par_valor1 IS NULL,0,det_ValDebito) - IF(par_valor1 IS NULL,0,det_ValCredito)) ELSE 0 END AS 'DEP'
                        ,CASE WHEN (pro_Orden= 2 and cat_Categoria != 11) THEN SUM(IF(par_valor1 IS NULL,0,det_ValDebito) - IF(par_valor1 IS NULL,0,det_ValCredito)) ELSE 0 END AS 'NC'
                        ,CASE WHEN (pro_Orden= 3 and cat_Categoria != 11) THEN SUM(IF(par_valor1 IS NULL,0,det_ValDebito) - IF(par_valor1 IS NULL,0,det_ValCredito)) ELSE 0 END AS 'CHEQUES'
                        ,CASE WHEN (pro_Orden= 4 and cat_Categoria != 11) THEN SUM(IF(par_valor1 IS NULL,0,det_ValDebito) - IF(par_valor1 IS NULL,0,det_ValCredito)) ELSE 0 END AS 'ND'
                FROM conpersonas 
                JOIN concategorias ON  cat_codauxiliar = per_codauxiliar
                LEFT JOIN condetalle ON det_IDAuxiliar = per_CodAuxiliar
                LEFT JOIN concomprobantes ON com_RegNumero = det_RegNumero AND com_FecContab BETWEEN '".$fecIni."' AND '".$fecFin."'
                LEFT JOIN concuentas ON cue_codcuenta = det_codcuenta
                LEFT JOIN genparametros ON par_Clave = 'CACCJA' AND det_CodCuenta LIKE par_Valor1
                LEFT JOIN invprocesos ON cla_TipoTransacc = com_TipoComp AND pro_codProceso = 22
                WHERE cat_categoria IN (10, 11) 
                GROUP BY per_CodAuxiliar, pro_Orden,par_valor1
        )";
$db->execute($sSql);
        
        
//CHEQUES N/A Y EMITIDOS PARA EL PERIODO ACTUAL
$sSql="INSERT INTO tmp_cierre
      (	SELECT per_CodAuxiliar cod, CONCAT(per_Apellidos, ' ', per_Nombres) AS aux,cat_Categoria
                      ,det_IDAuxiliar
                      ,det_CodCuenta
                      ,NULL
                      ,NULL
                      ,NULL
                      ,0 AS 'INI'
                      ,CASE WHEN operacion = 0 THEN SUM(det_ValDebito - det_ValCredito) ELSE 0 END AS 'NA'
                      ,CASE WHEN operacion = 1 THEN SUM(det_ValDebito - det_ValCredito) ELSE 0 END AS 'EMI'
                      ,0 AS 'DEP', 0 AS 'NC' 
                      /*Para que reste los cheques NA*/
                      ,CASE WHEN operacion = 0 THEN (SUM(det_ValDebito - det_ValCredito)* -1) ELSE 0 END AS 'CHEQUES'
                      ,0 AS 'ND'
              FROM concheques_cab cab 
              INNER JOIN concheques_det det ON cab.idbatch=det.idbatch 
              JOIN concomprobantes c ON c.com_regnumero=det.com_regnum 
              JOIN condetalle d ON c.com_regnumero=d.det_regnumero 
              JOIN conpersonas p ON det_idauxiliar=per_CodAuxiliar 
              JOIN concategorias cat ON cat_CodAuxiliar=per_CodAuxiliar AND cat_Categoria=10
              JOIN conChequeUltMov ult ON ult.com_regnum = det.com_regNum AND ult.ultBatch = det.IdBatch
              WHERE tipo=1 AND operacion IN(0,1) AND det_ValCredito<>0
              AND com_FecContab BETWEEN '".$fecIni."' AND '".$fecFin."'
              GROUP BY  per_CodAuxiliar, operacion
      )";
$db->execute($sSql);

//fecRegistro

//SALDO INICIAL DEL PERIODO QUE SE ESTÁ CONSULTANDO
$sSql="INSERT INTO tmp_cierre
         (     SELECT   per_CodAuxiliar, CONCAT(per_Apellidos, ' ', per_Nombres) AS aux, cat_Categoria
                        ,det_IDAuxiliar
                        ,det_CodCuenta
                        ,NULL/* ,pro_codProceso*/
                        ,par_valor1
                        ,NULL/*,pro_Orden */
                        ,SUM(IF(par_valor1 IS NULL,0,det_ValDebito) - IF(par_valor1 IS NULL,0,det_ValCredito)) AS 'INI'
                        ,0 AS 'NA'
                        ,0 AS 'EMI'
                        ,0 AS 'DEP'
                        ,0 AS 'NC'
                        ,0 AS 'CHEQUES'
                        ,0 AS 'ND' 
                FROM concomprobantes JOIN condetalle ON (det_RegNumero = com_RegNumero) 
                LEFT JOIN conpersonas p ON (p.per_CodAuxiliar = det_IdAuxiliar) 
                LEFT JOIN conactivos ON (act_CodAuxiliar = det_IdAuxiliar) 
                JOIN concategorias ON  cat_codauxiliar = per_codauxiliar
                JOIN genparametros ON par_Clave = 'CACCJA' AND det_CodCuenta LIKE par_Valor1                
                LEFT JOIN concuentas ON (cue_codcuenta = det_codcuenta) 
                /*Proceso - VAN TODOS LOS TIPOS DE COMPROBANTES COMO ESTA EN EL ANTERIOR REPORTE*/
                /* JOIN invprocesos ON cla_TipoTransacc = com_TipoComp AND pro_codProceso = 22 */
                WHERE com_FecContab < '".$fecIni."' 
                /* AND det_codcuenta <> '100000'*/
                AND cat_categoria IN (10,11) 
                GROUP BY det_IDAuxiliar
                ORDER BY det_CodCuenta,com_tipoComp, det_IDAuxiliar
        )";
$db->execute($sSql);

//CONSULTAR CHEQUES N/A PARA QUE RESTE DEL SALDO INICIAL DEL PERIODO
$sSql="INSERT INTO tmp_cierre
         (	SELECT per_CodAuxiliar cod, CONCAT(per_Apellidos, ' ', per_Nombres) AS aux,cat_Categoria
                         ,det_IDAuxiliar
                         ,det_CodCuenta
                         ,NULL
                         ,NULL
                         ,NULL
                         ,(SUM(det_ValDebito - det_ValCredito)* -1) AS 'INI'
                         ,0 AS 'NA'
                         ,0 AS 'EMI'
                         ,0 AS 'DEP'
                         ,0 AS 'NC'
                         ,0 AS 'CHEQUES'
                         ,0 AS 'ND'
                 FROM concheques_cab cab 
                 INNER JOIN concheques_det det ON cab.idbatch=det.idbatch 
                 JOIN concomprobantes c ON c.com_regnumero=det.com_regnum 
                 JOIN condetalle d ON c.com_regnumero=d.det_regnumero 
                 JOIN conpersonas p ON det_idauxiliar=per_CodAuxiliar 
                 JOIN concategorias cat ON cat_CodAuxiliar=per_CodAuxiliar AND cat_Categoria=10
                 JOIN conChequeUltMov ult ON ult.com_regnum = det.com_regNum AND ult.ultBatch = det.IdBatch
                 WHERE tipo=1 AND operacion IN(0) AND det_ValCredito<>0
                 AND com_FecContab  < '".$fecIni."' 
                 GROUP BY  per_CodAuxiliar, operacion
         )";
$db->execute($sSql);

$sSql="SELECT per_CodAuxiliar
             ,aux
             ,cat_Categoria
             ,SUM(INI) AS INI
             ,SUM(NA) AS NA
             ,SUM(EMI) AS EMI
             ,SUM(dep) AS DEP
             ,SUM(nc)AS NC
             ,SUM(cheques) AS CHEQUES
             ,SUM(nd) AS ND
             ,sum(INI+dep+nc+cheques+nd) as saldo
      FROM tmp_cierre 
      GROUP BY per_CodAuxiliar
      ORDER BY cat_Categoria DESC, aux
      ";
$rs = $db->execute($sSql);

if($rs->EOF){
    fErrorPage('','NO SE GENERARO INFORMACION PARA EL CIERRE DE CAJA', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
    $Tpl->assign("slPiePag", $slPiePag);
    $filename = basename($_SERVER[ "PHP_SELF"],".php");
    $Tpl->assign("agArchivo", $filename);
   
    if (!$Tpl->is_cached('CoTrTr_cierrecajaCta.tpl')) {
            }
    $Tpl->display('CoTrTr_cierrecajaCta.tpl');
}
?>