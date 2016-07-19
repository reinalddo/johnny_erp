<?php
/*    Reporte - Cuadro de Cuentas por Pagar
     @rev	esl	07/12/2010	excluir documentos de bitacora que fueron devueltos al cliente
     @rev	esl	11/Abr/2011	Agregar Periodo de ejecucion de reporte - FRUTIBONI
 */

ob_start("ob_gzhandler");
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
// parametro para el query general
$pQry = fGetParam('pQryCom','');

// Parametros individuales para el query
$semana = fGetParam('pro_Semana','');
$com_codReceptor = fGetParam('com_codReceptor',false);
$subtitulo = fGetParam('pCond','');
$subtitulo="CUADRO DE LIQUIDACION";
$Tpl->assign("subtitulo",$subtitulo);
$subtitulo2 = "Semana:".$semana;
$subtitulo2 .= ($com_codReceptor ? " Productor: ". $com_codReceptor  : "  " );
$Tpl->assign("subtitulo2",$subtitulo2);

/*para consultar los detalles*/
$sSql = " /*CUADRO LIQUIDACION*/
/* TIPO: 1-DETALLE
	 5-TOTAL*/
/* TOTALES POR LIQUIDACION */
	SELECT  5 AS tipo, c.com_CodReceptor, CONCAT(p.per_Apellidos,' ',p.per_Nombres) AS productor,
		li.liq_NumProceso AS pro, li.liq_Numliquida AS liq,	
		'' AS marca,'TOTAL LIQUIDACION' AS dmarca,
		SUM(li.liq_Cantidad) AS cajEmb,0 AS vUnit,0 AS dif,
		/*SUMAS CAJAS-PROMEDIO TARJAS*/
		(SELECT SUM((td.tad_cantrecibida - td.tad_cantrechazada)*(tad_ValUnitario-tad_DifUnitario))/SUM((td.tad_cantrecibida - td.tad_cantrechazada))
		FROM liqtarjacabec t JOIN liqtarjadetal td ON td.tad_NumTarja = t.tar_NumTarja
		WHERE t.tac_Semana = pr.pro_Semana AND t.tac_Embarcador = c.com_CodReceptor) AS pCaj,0 as vCaj,
		SUM(li.liq_ValTotal) AS vtotal,
		/*----CAJAS CONTRATADAS---*/		
		IFNULL(v.iab_ValorTex,0) AS cajCont,
		IFNULL(v2.iab_ValorTex,0) AS pcajCont,
		IFNULL(v2.iab_ValorTex,0)* IFNULL(v.iab_ValorTex,0)AS vcajCont,
		/*-------- RUBROS --------*/
		SUM(CASE WHEN li.liq_CodRubro = 1 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)*1 AS R1,
		SUM(CASE WHEN li.liq_CodRubro = 2 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R2,
		SUM(CASE WHEN li.liq_CodRubro = 3 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R3,
		SUM(CASE WHEN li.liq_CodRubro = 4 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R4,
		SUM(CASE WHEN li.liq_CodRubro = 5 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R5,
		SUM(CASE WHEN li.liq_CodRubro = 6 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R6,
		SUM(CASE WHEN li.liq_CodRubro = 7 THEN (IFNULL(li.liq_ValTotal,0)- IFNULL(lid.det_valTotal,0))*ru.rub_IndDbCr ELSE 0 END) AS R7,
		SUM(CASE WHEN li.liq_CodRubro = 7 THEN IFNULL(lid.det_valTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R7_G,
		SUM(CASE WHEN li.liq_CodRubro = 8 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R8,
		SUM(CASE WHEN li.liq_CodRubro = 9 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R9,
		SUM(CASE WHEN li.liq_CodRubro = 10 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R10,
		SUM(CASE WHEN li.liq_CodRubro = 11 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R11,
		SUM(CASE WHEN li.liq_CodRubro = 12 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R12,
		SUM(CASE WHEN li.liq_CodRubro = 13 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R13,
		SUM(CASE WHEN li.liq_CodRubro = 14 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R14,
		SUM(CASE WHEN li.liq_CodRubro = 15 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R15,
		SUM(CASE WHEN li.liq_CodRubro = 16 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R16,
		SUM(CASE WHEN li.liq_CodRubro = 17 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R17,
		SUM(CASE WHEN li.liq_CodRubro = 18 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R18,
		SUM(CASE WHEN li.liq_CodRubro = 19 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R19,
		SUM(CASE WHEN li.liq_CodRubro = 20 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R20,
		SUM(CASE WHEN li.liq_CodRubro = 21 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R21,
		SUM(CASE WHEN li.liq_CodRubro = 22 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END) AS R22,
		/* TOTAL */
		(SUM(CASE WHEN li.liq_CodRubro = 1 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 2 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 3 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 4 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 5 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 6 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 7 THEN (IFNULL(li.liq_ValTotal,0)- IFNULL(lid.det_valTotal,0))*ru.rub_IndDbCr ELSE 0 END) +
		SUM(CASE WHEN li.liq_CodRubro = 7 THEN IFNULL(lid.det_valTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 8 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 9 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 10 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 11 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 12 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 13 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 14 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 15 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 16 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		/* SUM(CASE WHEN li.liq_CodRubro = 17 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+  */
		SUM(CASE WHEN li.liq_CodRubro = 18 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 19 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 20 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 21 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 22 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)/*+
		SUM(CASE WHEN li.liq_CodRubro = 17 THEN IFNULL(li.liq_ValTotal,0) ELSE 0 END)*/)AS RTotal,
		/* TOTAL - DESCONTADO ANTICIPO*/
		(SUM(CASE WHEN li.liq_CodRubro = 1 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 2 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 3 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 4 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 5 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 6 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 7 THEN (IFNULL(li.liq_ValTotal,0)- IFNULL(lid.det_valTotal,0))*ru.rub_IndDbCr ELSE 0 END) +
		SUM(CASE WHEN li.liq_CodRubro = 7 THEN IFNULL(lid.det_valTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 8 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 9 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 10 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 11 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 12 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 13 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 14 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 15 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 16 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 17 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+  
		SUM(CASE WHEN li.liq_CodRubro = 18 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 19 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 20 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 21 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 22 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END))AS RTotalFin,
		/*---- TOTALES DESCUENTOS -----------*/
		(SUM(CASE WHEN li.liq_CodRubro = 6 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 7 THEN (IFNULL(li.liq_ValTotal,0)- IFNULL(lid.det_valTotal,0))*ru.rub_IndDbCr ELSE 0 END) +
		SUM(CASE WHEN li.liq_CodRubro = 7 THEN IFNULL(lid.det_valTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 9 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 14 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 16 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		/* SUM(CASE WHEN li.liq_CodRubro = 17 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+ */
		SUM(CASE WHEN li.liq_CodRubro = 18 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 20 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 21 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END))AS TDesc,
		/* TOTAL */
		(SUM(CASE WHEN li.liq_CodRubro = 1 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 2 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 3 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END)+
		SUM(CASE WHEN li.liq_CodRubro = 4 THEN IFNULL(li.liq_ValTotal,0)*ru.rub_IndDbCr ELSE 0 END))AS TBenf
		/*-----------------------*/
		
	FROM liqprocesos pr
	JOIN liqliquidaciones li ON li.liq_NumProceso = pr.pro_ID
	JOIN concomprobantes c ON c.com_TipoComp = 'LI' AND com_NumComp = li.liq_Numliquida AND c.com_RefOperat = pr.pro_Semana
	LEFT JOIN invdetalle lid ON lid.det_regNumero = c.com_regNumero AND lid.det_codItem = 25684
	JOIN conpersonas p ON p.per_CodAuxiliar = c.com_CodReceptor
	JOIN liqrubros ru ON li.liq_CodRubro = ru.rub_CodRubro
	LEFT JOIN genvarconfig vc ON vcf_nombre = 'CONTRATO' 
	LEFT JOIN genvariables v ON v.iab_ObjetoID = c.com_CodReceptor AND v.iab_VariableID = vc.vcf_ID
	LEFT JOIN genvarconfig vc2 ON vc2.vcf_nombre = 'PPACTADO' 
	LEFT JOIN genvariables v2 ON v2.iab_ObjetoID = c.com_CodReceptor AND v2.iab_VariableID = vc2.vcf_ID		    
	WHERE pro_Semana = ".$semana;
$sSql .= ($com_codReceptor ? " and c.com_CodReceptor = ". $com_codReceptor  : "  " );
$sSql .= " GROUP BY li.liq_Numliquida ";
	

$sSql .= " UNION ALL
      /*DETALLES DE TARJAS -CAJAS*/
	SELECT	1 AS tipo, c.com_CodReceptor, CONCAT(p.per_Apellidos,' ',p.per_Nombres) AS productor, 
		li.liq_NumProceso AS pro, li.liq_Numliquida AS liq,
		td.tad_CodMarca,mr.par_descripcion, 
		(td.tad_cantrecibida - td.tad_cantrechazada) AS cajEmb,tad_ValUnitario AS vUnit, 
		tad_DifUnitario AS dif,
		(tad_ValUnitario-tad_DifUnitario) AS pCaj,
		(td.tad_cantrecibida - td.tad_cantrechazada)*(tad_ValUnitario-tad_DifUnitario) as vCaj,
		0 AS vtotal,
		/*----CAJAS CONTRATADAS---*/		
		0 AS cajCont,
		0 AS pcajCont,
		0 AS vcajCont,
		/*-------- RUBROS --------*/
		0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
		/*Total*/
		0 AS RTotal,0 AS RTotalFin,0 AS TDesc,0 AS TBenf
		/*-----------------------*/
	FROM liqprocesos pr
	JOIN liqliquidaciones li ON li.liq_NumProceso = pr.pro_ID
	JOIN concomprobantes c ON c.com_TipoComp = 'LI' AND com_NumComp = li.liq_Numliquida AND c.com_RefOperat = pr.pro_Semana
	JOIN conpersonas p ON p.per_CodAuxiliar = c.com_CodReceptor
	JOIN liqtarjacabec t ON t.tac_Semana = pr.pro_Semana AND t.tac_Embarcador = c.com_CodReceptor
	JOIN liqtarjadetal td ON td.tad_NumTarja = t.tar_NumTarja 
	LEFT JOIN genparametros mr ON mr.par_Clave = 'IMARCA' AND mr.par_Secuencia = td.tad_CodMarca
	WHERE pro_Semana = ".$semana;
$sSql .= ($com_codReceptor ? " and c.com_CodReceptor = ". $com_codReceptor  : "  " );
$sSql .= " GROUP BY li.liq_Numliquida, t.tac_Embarcador, t.tar_NumTarja, td.tad_Secuencia 
	ORDER BY liq, productor, tipo desc, dmarca";

$rs = $db->execute($sSql . $slFrom);


$Periodo =  ($pcom_FecVencimDesde ? " Desde ". $pcom_FecVencimDesde  : "  " );
$Periodo .= ($pcom_FecVencimHasta ? " Al  ". $pcom_FecVencimHasta  : "  " );
//pr.pro_Semana = 1126 
//style="font-weight:bold;"

if($rs->EOF){
    fErrorPage('','NO SE GENERO INFORMACION PARA PRESENTAR', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    $Tpl->assign("agPeriodo", $Periodo);
    $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
    $Tpl->assign("slPiePag", $slPiePag);
    if (!$Tpl->is_cached('../Li_Files/LiLiRp_CuadroLiquidacion.tpl')) {
            }
            $Tpl->display('../Li_Files/LiLiRp_CuadroLiquidacion.tpl');
}
?>