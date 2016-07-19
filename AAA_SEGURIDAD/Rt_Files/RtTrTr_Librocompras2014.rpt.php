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
        $this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}
/*
if (fGetparam("pExcel",false)){
   header("Content-Type:  application/x-msdownload");
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


/*para consultar los detalles*/
$sSql = "SELECT ID,
	concat(per_apellidos, ' ', per_Nombres) as Proveedor,
	CAST(per_ruc AS CHAR) as RUC,
	tipoComprobante as 'TIPO_DOC',
	establecimiento, puntoEmision, secuencial, 
	com_NUmcomp as 'CC',
	DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS 'FECHA_IMP',
	DATE_FORMAT(com_feccontab ,'%d/%m/%Y') AS 'FECHA_D_CONT',
	DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y' ) AS 'FECHA_VALIDEZ',
	autorizacion 'AUT_SRI',
	' '  AS 'N_D_AUT',
	baseImpGrav AS 'BASE_12', baseImponible 'BASE_0', montoIva 'IVA',  baseImpGrav + baseImponible + montoIva 'TOTAL_COMPRA', 
	montoIvaBienes, ivb.tab_porcentaje, valorRetBienes, ivb.tab_porcentaje PIVAB, 
	montoIvaServicios, ivs.tab_porcentaje, valorRetServicios, ivs.tab_porcentaje PIVAS,
	if(porcentajeAir = 1, valRetAir, 0) + if(porcentajeAir2 = 1, valRetAir2, 0) + if(porcentajeAir3 = 1, valRetAir3, 0) as 'RET_1',
	if(porcentajeAir = 2, valRetAir, 0) + if(porcentajeAir2 = 2, valRetAir2, 0) + if(porcentajeAir3 = 2, valRetAir3, 0) as 'RET_2',
	if(porcentajeAir = 5, valRetAir, 0) + if(porcentajeAir2 = 5, valRetAir2, 0) + if(porcentajeAir3 = 5, valRetAir3, 0) as 'RET_5',
	if(porcentajeAir = 8, valRetAir, 0) + if(porcentajeAir2 = 8, valRetAir2, 0) + if(porcentajeAir3 = 8, valRetAir3, 0) as 'RET_8',
	if(porcentajeAir = 25, valRetAir, 0) + if(porcentajeAir2 = 25, valRetAir2, 0) + if(porcentajeAir3 = 25, valRetAir3, 0) as 'RET_25',

	 concat(if(porcentajeAir = 1, codRetAir, '') , '', if(porcentajeAir2 = 1, codRetAir2, '') , '', if(porcentajeAir3 = 1, codRetAir3, '')) as 'CRT_1', 
	 concat(if(porcentajeAir = 2, codRetAir, '') , '', if(porcentajeAir2 = 2, codRetAir2, '') , '', if(porcentajeAir3 = 2, codRetAir3, '')) as 'CRT_2',
	 concat(if(porcentajeAir = 5, codRetAir, '') , '', if(porcentajeAir2 = 5, codRetAir2, '') , '', if(porcentajeAir3 = 5, codRetAir3, '')) as 'CRT_5',
	 concat(if(porcentajeAir = 8, codRetAir, '') , '', if(porcentajeAir2 = 8, codRetAir2, '') , '', if(porcentajeAir3 = 8, codRetAir3, '')) as 'CRT_8', 
	 concat(if(porcentajeAir = 25, codRetAir,'') , '', if(porcentajeAir2 = 25, codRetAir2, '') , '', if(porcentajeAir3 = 25, codRetAir3, '')) as 'CRT_25',

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
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
	LEFT JOIN concomprobantes on com_NumRetenc = ID
	WHERE tipoTransac IN (1,8 /*Transaccion 8 es compra de fruta que tambien se incluye para FRUTIBONI */ ) and YEAR(com_feccontab) = " . $anio . " and MONTH(com_FecContab) = " . $mes. " ORDER BY estabRetencion1,puntoEmiRetencion1,secRetencion1";

//echo $sSql;

$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    
    if (!$Tpl->is_cached('RtTrTr_Librocompras2.tpl')) {
            }
    
            $Tpl->display('RtTrTr_Librocompras2.tpl');
}
?>
