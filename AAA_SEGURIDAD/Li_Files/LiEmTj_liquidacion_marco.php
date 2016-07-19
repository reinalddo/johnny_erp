<?php
/*
*   Detalle de Tarjas diario
*   Detalle de Tarjas 
*   @date	11/03/09    
*   @author     fah
*   @
*/
ob_start("ob_gzhandler");
include("../LibPhp/ezPdfReport.php");
include("General.inc.php");
include_once("GenUti.inc.php");
include("adoConn.inc.php");
require('Smarty.class.php');
include('tohtml.inc.php'); 
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = './';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = fGetParam("pSmtDbg",false);
   }
}
if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}
include "../LibPhp/excelOut.php";
$Tpl = new Smarty_AAA();
$glFlag= fGetParam('pEmpq', false);
$Tpl->debugging =fGetparam("pAppDbg",false);
/*
 ********************************
*/

/*
 *	Define la instruccion sql a ejecutarse. Genera tambien los arreglos con las cabeceras de la tabla, con
 *	agrupamiento de cabecera
 **/
function &fDefineQry(&$db, $pQry=false){
   global $gsTitGeneral;
    IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_liquidaciones")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
    IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_liqtarjadetal")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
    IF(!$db->Execute( "DROP TABLE IF EXISTS tmp_procesos")) fErrorPage('','NO SE PUDO ELIMINA DATOS TEMPORALES', true,false);
    $pTipo = (isset($_GET['pTipo']))? $_GET['pTipo']:30; // tIPO DE rEPORTE A GENERAR
/**/
    $slSql = " SELECT distinct concat(buq_descripcion, '-', emb_numviaje) AS tmp_Buque
               FROM liqprocesos
                	join liqtarjadetal on tad_liqproceso = pro_ID
                	join liqtarjacabec on tad_numtarja = tar_numtarja
                	join liqembarques  on tac_refoperativa = emb_refoperativa
                	join liqbuques on buq_codbuque = emb_codvapor ";
    if ($pQry) $slSql .= " WHERE " . $pQry;
    $rs= $db->Execute($slSql);
    if(!$rs) fErrorPage('',"NO SE PUDO ACCEDER A VAPORES " . $slSql, true, false);

    while ($rec = $rs->FetchNextObject(true)) {
        $gsTitGeneral .=  (($gsTitGeneral)?', ':'') . $rec->TMP_BUQUE;
    }

    $slSql = "CREATE TEMPORARY TABLE tmp_liquidaciones
                     SELECT pro_ID AS liq_NumProceso, liq_numliquida, liq_codrubro, sum(liq_valtotal * rub_IndDbCr) as tmp_valtotal, com_codreceptor as tmp_codProd
                     FROM (liqprocesos INNER JOIN liqliquidaciones ON liq_NumProceso = pro_ID)
					 		INNER JOIN liqrubros on rub_codrubro = liq_codrubro
                            JOIN liqreportes ON rep_reporteID = " . $pTipo . " AND rep_codrubro = liq_codrubro and length(rep_titlargo) > 0                            
					 		INNER JOIN concomprobantes ON com_tipocomp = 'LQ' AND com_numcomp = liq_numliquida
							 ";
    if ($pQry) $slSql .= " WHERE " . str_replace('tac_Embarcador', 'com_codreceptor', $pQry);
    
    
    $slSql .=  " GROUP BY 1, 2, 3";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (1)', true,false);
    $slSql = "CREATE TEMPORARY TABLE tmp_procesos
                     SELECT DISTINCT liq_NumProceso AS tmp_procID
                     FROM tmp_liquidaciones  ";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2)', true,false);

//
    $slSql = "DROP TABLE IF EXISTS tmp_ch1";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2a)', true,false);
    $slSql = "DROP TABLE IF EXISTS tmp_ch2";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2b)', true,false);
    $slSql = "CREATE TEMPORARY TABLE tmp_ch1
                SELECT e.com_tipocomp, l.com_numcomp, det_numcheque
                    FROM 	conenlace
                    	JOIN concomprobantes l ON enl_tipo ='LQ' AND l.com_tipocomp = enl_tipo
                             AND l.com_numcomp = enl_ID AND enl_opcode = 6
                    	JOIN tmp_procesos ON l.com_numproceso = tmp_procid
                    	JOIN concomprobantes e ON e.com_tipocomp = enl_tipocomp and e.com_numcomp = enl_numcomp
                    	JOIN condetalle ON det_regnumero = e.com_regnumero AND det_codcuenta = '1101020'
                    WHERE l.com_tipocomp ='LQ' ";

    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2c)', true,false);
    $slSql = "CREATE TEMPORARY TABLE tmp_ch2
                SELECT e.com_tipocomp, l.com_numcomp, det_numcheque
                    FROM 	conenlace
                    	JOIN concomprobantes l ON enl_tipo ='LQ' AND l.com_tipocomp = enl_tipo
                             AND l.com_numcomp = enl_ID AND enl_opcode = 8
                    	JOIN tmp_procesos ON l.com_numproceso = tmp_procid 
                    	JOIN concomprobantes e ON e.com_tipocomp = enl_tipocomp and e.com_numcomp = enl_numcomp
                    	JOIN condetalle ON det_regnumero = e.com_regnumero AND det_codcuenta = '1101020'
                    WHERE l.com_tipocomp ='LQ' ";

    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (2d)', true,false);

//
/****/
    $slSql = "CREATE TEMPORARY TABLE tmp_liqtarjadetal
                 SELECT tad_liqnumero, tac_Embarcador, concat(left(per_Apellidos,22-length(left(per_Nombres,11))), ' ', left(per_Nombres,12)) AS tmp_productor,
                        sum(tad_cantrecibida - tad_cantrechazada  ) AS tmp_cantembarcada, tac_CodEmpresa AS tmp_codempresa
			,ROUND(AVG(tad_ValUnitario - tad_DifUnitario),2) as PrecioPagado
                 FROM ((tmp_procesos JOIN liqtarjadetal  ON tad_LiqProceso = tmp_procID )
                                     JOIN liqtarjacabec  ON tar_NUmTarja = tad_NumTarja)
                                     JOIN conpersonas    ON per_Codauxiliar = tac_Embarcador
                 GROUP by 1, 2, 3";
    IF(!$db->Execute($slSql))  fErrorPage('','NO SE PUDO GENERAR DATOS TEMPORALES DE LIQUIDACION (3)', true,false);
    //
    // Generacion de tabla de referencias cruzadas, en base al detalle de liquidaciones requeridas
//@TODO:  HAcer columnas dinamicas.
    $slSql ="SELECT tmp_CodProd   AS 'C0',
                    UPPER(tmp_productor) AS 'C1',
                    tmp_cantembarcada AS 'C2',
                    tmp_ch1.det_numcheque AS E1,
                    tmp_ch2.det_numcheque AS E2, 
                    SUM(CASE WHEN  1  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D1',
                    SUM(CASE WHEN  2  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D2',
                    SUM(CASE WHEN  3  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D3',
                    SUM(CASE WHEN  4  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D4',
                    SUM(CASE WHEN  5  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D5',		    
                    SUM(CASE WHEN  10  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D10',
                    SUM(CASE WHEN  11  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D11',
                    SUM(CASE WHEN  12  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D12',
                    SUM(CASE WHEN  13  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D13',
                    SUM(CASE WHEN  14  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D14',		    
                    SUM(CASE WHEN  15  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D15',
                    SUM(CASE WHEN  16  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D16',
                    SUM(CASE WHEN  20  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D20',
                    SUM(CASE WHEN  21  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D21',
                    SUM(CASE WHEN  22  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D22',		    
                    SUM(CASE WHEN  23  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D23',
                    SUM(CASE WHEN  24  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D24',
                    SUM(CASE WHEN  25  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D25',
                    SUM(CASE WHEN  26  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D26',
                    SUM(CASE WHEN  27  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D27',
		    SUM(CASE WHEN  28  = RUB_POSORDINAL THEN tmp_valtotal ELSE 0 END)  AS 'D28',		    
		    SUM(tmp_valtotal)  AS 'TT',
		    emp_Descripcion AS 'empresa',
		    PrecioPagado
            FROM (tmp_liquidaciones l JOIN tmp_liqtarjadetal t on   liq_numliquida = tad_liqnumero)
                    JOIN liqreportes ON rep_reporteID = " . $pTipo . " AND rep_codrubro = liq_codrubro
                    JOIN liqrubros on rub_codrubro = liq_codrubro
                    LEFT JOIN tmp_ch1 on tad_liqnumero = tmp_ch1.com_numcomp
                    LEFT JOIN tmp_ch2 on tad_liqnumero = tmp_ch2.com_numcomp 
	 join seguridad.segempresas on tmp_codempresa = emp_BaseDatos
            GROUP BY 1, 2, 3,4,5
	    ORDER BY C1 ASC, empresa ";
    $rsLiq = $db->Execute($slSql);
    IF(!$rsLiq) fErrorPage('','NO SE GENERARON LOS DETALLES DE LIQUIDACION', true,false);
    return $rsLiq;
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//

$ilNumProces   = fGetParam('pPro', false);
$db->debug=fGetParam('pAdoDbg', 0);
$pQry=fGetParam('pQryLiq', 0);
$rep->condition=fGetParam('pCond', '');
$rep->subTitle=fGetParam('pCond', '');
set_time_limit (0) ;
$rs = fDefineQry($db, $pQry );
$tplFile = 'LiEmTj_liquidacion_marco.tpl';
$Tpl->assign("gsNumCols", 25);
$Tpl->assign("gsEmpresa", $_SESSION["g_empr"]);
$gsSubt= "DETALLE DE TARJAS<br>" . fGetParam("pCond", "-");
$Tpl->assign("gsSubTitul", $gsSubt );
$Tpl->assign("gsNumCols", 25);
$aDet =& SmartyArray($rs);
$Tpl->assign("agData", $aDet);
$Tpl->display($tplFile);


?>
