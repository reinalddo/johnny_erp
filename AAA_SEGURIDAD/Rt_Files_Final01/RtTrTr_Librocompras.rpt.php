<?php
/*    Reporte Libro Compras. Formato HTML.
 *    @param   string 	pQry	GET/POST, Condicion SQL para generar el reporte
 *    @param	bool	pExcel	GET/POST, 1= Genera en excel, caso contrario HTML
 *    @author		Gina Franco
 *    @package		Contabilidad
 *    @subpackage	ATS
 *    @date		06/04/2009
 *    @access		fiscompras, concomprobantes, condetalle, fistablassri
 *    @rev	18/08/2010	esl	agregar columna para retenci�n del 10%
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

$subtitulo = fGetParam('pCond',"A�o: ".$anio." - Mes: ".$mes);
$Tpl->assign("subtitulo",$subtitulo);


/*para consultar los detalles*/
/* @TODO PARAMETRIZAR LOS TIPOS DE TRANSACCION QUE DEBEN INCLUIRSE*/
$sSql = "SELECT ID,
	concat(per_apellidos, ' ', per_Nombres) as Proveedor,
	per_ruc as RUC,
	tipoComprobante as 'TIPO_DOCU',
	ai4.tab_TxtData3 as 'TIPO_DOC',
	establecimiento, puntoEmision, secuencial,
	com_NUmcomp as 'CC',
	com_tipocomp as 'TC',
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
	if(porcentajeAir = 10, valRetAir, 0) + if(porcentajeAir2 = 10, valRetAir2, 0) + if(porcentajeAir3 = 10, valRetAir3, 0) as 'RET_10',

	 concat(if(porcentajeAir = 1, codRetAir, '') , '', if(porcentajeAir2 = 1, codRetAir2, '') , '', if(porcentajeAir3 = 1, codRetAir3, '')) as 'CRT_1',
	 concat(if(porcentajeAir = 2, codRetAir, '') , '', if(porcentajeAir2 = 2, codRetAir2, '') , '', if(porcentajeAir3 = 2, codRetAir3, '')) as 'CRT_2',
	 concat(if(porcentajeAir = 5, codRetAir, '') , '', if(porcentajeAir2 = 5, codRetAir2, '') , '', if(porcentajeAir3 = 5, codRetAir3, '')) as 'CRT_5',
	 concat(if(porcentajeAir = 8, codRetAir, '') , '', if(porcentajeAir2 = 8, codRetAir2, '') , '', if(porcentajeAir3 = 8, codRetAir3, '')) as 'CRT_8',
	 concat(if(porcentajeAir = 25, codRetAir,'') , '', if(porcentajeAir2 = 25, codRetAir2, '') , '', if(porcentajeAir3 = 25, codRetAir3, '')) as 'CRT_25',
	 concat(if(porcentajeAir = 10, codRetAir,'') , '', if(porcentajeAir2 = 10, codRetAir2, '') , '', if(porcentajeAir3 = 10, codRetAir3, '')) as 'CRT_10',

	concat(com_tipocomp, ' ', com_numcomp) as 'COMPROB'
	,estabRetencion1,puntoEmiRetencion1,secRetencion1
	,autRetencion1 'AUTRET'
	,codSustento as codSustento
	,ai5.tab_Descripcion as sustento
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_ID = autorizacion  and aut_tipodocum = tipocomprobante
									AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
								   	      tipocomprobante =3 ) and aut.aut_IDauxiliar = idprovfact
            LEFT JOIN genautsri   liq    ON liq.aut_ID = autorizacion  AND liq.aut_tipoDocum = 3 AND liq.aut_IDauxiliar = -99
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ice   ON ice.tab_codtabla = '6' and ice.tab_codigo = porcentajeIce
			LEFT JOIN fistablassri ivb   ON ivb.tab_codtabla = '5A' and ivb.tab_codigo = porretbienes
			LEFT JOIN fistablassri ivs   ON ivs.tab_codtabla = '5' and ivs.tab_codigo = porretservicios
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = '10' and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = '10' and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = '10' and ai3.tab_codigo = codRetAir3
			LEFT JOIN fistablassri ai4   ON ai4.tab_codtabla = '2' and ai4.tab_codigo = tipoComprobante
			LEFT JOIN fistablassri ai5 ON ai5.tab_CodTabla = '3' and ai5.tab_CodSecuencial = codSustento
	LEFT JOIN concomprobantes on com_NumRetenc = ID
	WHERE tipoTransac in (1, 3, 8) and YEAR(com_feccontab) = " . $anio . " and MONTH(com_FecContab) = " . $mes. " ORDER BY estabRetencion1,puntoEmiRetencion1,secRetencion1";

//echo $sSql;

$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE GENERARON LOS DETALLES DE MOVIMIENTOS', true,false);
}else{

    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);

    if (!$Tpl->is_cached('RtTrTr_Librocompras.tpl')) {
            }

            $Tpl->display('RtTrTr_Librocompras.tpl');
}
?>