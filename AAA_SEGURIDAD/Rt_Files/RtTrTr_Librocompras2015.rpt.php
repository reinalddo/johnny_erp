<?php
/*    Reporte Libro Compras. Formato HTML. 
 *    @param   string 	pQry	GET/POST, Condicion SQL para generar el reporte
 *    @param	bool	pExcel	GET/POST, 1= Genera en excel, caso contrario HTML
 *    @author		Gina Franco
 *    @package		Contabilidad
 *    @subpackage	ATS
 *    @date		06/04/2009
 *    @access		fiscompras, concomprobantes, condetalle, fistablassri
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
        $this->template_dir = '.';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}
/*
if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}
*/
include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$glFlag= fGetParam('pEmpq', false);
$pQry = fGetParam('pQryCom','');


$anio = fGetParam('s_Anio',date('Y'));
$mes = fGetParam('s_Mes',date('m'));

if ($anio == '') $anio = date('Y');
if ($mes == '') $mes = date('m');

$subtitulo = fGetParam('pCond',"Año: ".$anio." - Mes: ".$mes);
$Tpl->assign("subtitulo",$subtitulo);

$pQry = "YEAR(fecharegistro) = " . $anio . " and MONTH(fecharegistro) = " . $mes . " ";
$slRetsql = "SELECT ID 		AS det_id
			,1  			AS det_secue   
			,codretair 		AS det_codigo
			,baseImpAir		As det_baseim
			,porcentajeAir	AS det_porcen
			,valRetAir		AS det_valor
		FROM fiscompras
		WHERE LENGTH(codretair) > 2 " . 
				(strlen($pQry) > 0 ?  " AND "  . $pQry : "") . "
	UNION
		SELECT id
			,2 AS secuen
			,codretair2 AS codRetAir
			,baseimpair2
			,porcentajeair2
			,valretair2
		FROM fiscompras
		WHERE LENGTH(codretair2) > 2 " . 
				(strlen($pQry) > 0 ?  " AND "  . $pQry : "") . "
	UNION
		SELECT ID
			,3 AS secuen
			,codretair3 AS codRetAir
			,baseimpair3
			,porcentajeair3
			,valretair3
		FROM fiscompras
		WHERE LENGTH(codretair3) > 2 " . 
				(strlen($pQry) > 0 ?  " AND "  . $pQry : "") . "
	ORDER BY 1,2 ";


/*para consultar los detalles*/
$sSql = "SELECT ID,
	if(det_secue >= 2, '', concat(per_apellidos, ' ', per_Nombres)) as Proveedor,
	if(det_secue >= 2, '', per_ruc) 		as RUC,
	if(det_secue >= 2, '', tipoComprobante) as 'TIPO_DOC',
	if(det_secue >= 2, '', establecimiento)	AS establecimiento,
	if(det_secue >= 2, '', puntoEmision) 	AS puntoEmision,
	if(det_secue >= 2, '', secuencial)		AS secuencial, 
	if(det_secue >= 2, '', com_NUmcomp) 	as 'CC',
	if(det_secue >= 2, '', DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y')) 	AS 'FECHA_IMP',
	if(det_secue >= 2, '', DATE_FORMAT(com_feccontab ,'%d/%m/%Y')) 	AS 'FECHA_D_CONT',
	if(det_secue >= 2, '', DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y' )) AS 'FECHA_VALIDEZ',
	if(det_secue >= 2, '', autorizacion) 'AUT_SRI',
	' '  AS 'N_D_AUT',
	if(det_secue >= 2, 0, baseImpGrav)	 AS 'BASE_12',
	if(det_secue >= 2, 0, baseImponible) AS 'BASE_0',
	if(det_secue >= 2, 0, montoIva) 	 AS 'IVA',
	if(det_secue >= 2, 0, baseImpGrav + baseImponible + montoIva) 'TOTAL_COMPRA', 
	if(det_secue >= 2, 0, montoIvaBienes) 		AS montoIvaBienes,
	if(det_secue >= 2, 0, ivb.tab_porcentaje) 	AS tab_porcentaje,
	if(det_secue >= 2, 0, valorRetBienes)		AS valorRetBienes,
    if(det_secue >= 2, 0, ivb.tab_porcentaje) 	AS PIVAB, 
	if(det_secue >= 2, 0, montoIvaServicios)	AS montoIvaServicios,
	if(det_secue >= 2, 0, ivs.tab_porcentaje)	AS tab_porcentaje,
	if(det_secue >= 2, 0, valorRetServicios)	AS valorRetServicios,
	if(det_secue >= 2, 0, ivs.tab_porcentaje) 	AS PIVAS,
    det.*,
	concat(com_tipocomp, ' ', com_numcomp) as 'COMPROB'
	,estabRetencion1,puntoEmiRetencion1,secRetencion1
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact 
			LEFT JOIN genautsri   aut    ON aut.aut_ID = autorizacion  and aut_tipodocum = tipocomprobante
									AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
								   	      tipocomprobante =3 ) and aut.aut_IDauxiliar = idprovfact
            LEFT JOIN genautsri   liq    ON liq.aut_ID = autorizacion  AND liq.aut_tipoDocum = 3 AND liq.aut_IDauxiliar = -99
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ice   ON ice.tab_codtabla = 6 and ice.tab_codigo = porcentajeIce
			LEFT JOIN fistablassri ivb   ON ivb.tab_codtabla = '5A' and ivb.tab_codigo = porretbienes
			LEFT JOIN fistablassri ivs   ON ivs.tab_codtabla = '5' and ivs.tab_codigo = porretservicios
			INNER JOIN ($slRetsql) det	 ON det.det_id = fiscompras.id
	LEFT JOIN concomprobantes on com_NumRetenc = ID
	WHERE tipoTransac IN (1,8)
	  and YEAR(fecharegistro) = " . $anio . " and MONTH(fecharegistro) = " . $mes.
   " ORDER BY estabRetencion1,puntoEmiRetencion1,secRetencion1, det_secue, ID";

echo $sSql;

$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
					      
    if (!$Tpl->is_cached('RtTrTr_Librocompras2015.tpl')) {
            }
    
            $Tpl->display('RtTrTr_Librocompras2015.tpl');
}
?>