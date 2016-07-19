<?php
/*    Reporte de tarjas, detalle General por Puerto, Vapor, Marca. Formato HTML
 *    @param   integer  pSem     Numero de semana a procesar
 *    @param   integer  pEmb     Numero de Embarque
 *    @param   string   PMarca   Marca
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

if (fGetparam("pExcel",false)){
   header("Content-Type:  application/vnd.ms-excel");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}

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
	per_ruc as RUC,
	tipoComprobante as 'TIPO_DOC',
	establecimiento, puntoEmision, secuencial, 
	com_NUmcomp as 'CC',
	DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS 'FECHA_IMP',
	DATE_FORMAT(com_feccontab ,'%d/%m/%Y') AS 'FECHA_D_CONT',
	DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y' ) AS 'FECHA_VALIDEZ',
	autorizacion 'AUT_SRI',
	' '  AS 'N_D_AUT',
	baseImpGrav AS 'BASE_12', baseImponible 'BASE_0', montoIva 'IVA',  baseImpGrav + baseImponible + montoIva 'TOTAL_COMPRA', 
	montoIvaBienes, porRetBienes, valorRetBienes,
	montoIvaServicios, porRetServicios, valorRetServicios,
	if(porcentajeAir = 1, valRetAir, 0) + if(porcentajeAir2 = 1, valRetAir2, 0) + if(porcentajeAir3 = 1, valRetAir3, 0) as 'RET_1',
	if(porcentajeAir = 2, valRetAir, 0) + if(porcentajeAir2 = 2, valRetAir2, 0) + if(porcentajeAir3 = 2, valRetAir3, 0) as 'RET_2',
	if(porcentajeAir = 5, valRetAir, 0) + if(porcentajeAir2 = 5, valRetAir2, 0) + if(porcentajeAir3 = 5, valRetAir3, 0) as 'RET_5',
	if(porcentajeAir = 8, valRetAir, 0) + if(porcentajeAir2 = 8, valRetAir2, 0) + if(porcentajeAir3 = 8, valRetAir3, 0) as 'RET_8',
	if(porcentajeAir = 25, valRetAir, 0) + if(porcentajeAir2 = 25, valRetAir2, 0) + if(porcentajeAir3 = 25, valRetAir3, 0) as 'RET_25',
        concat(com_tipocomp, ' ', com_numcomp) as 'COMPROB'
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_ID = autorizacion  and aut_tipodocum = tipocomprobante
									AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
								   	      tipocomprobante =3 ) and aut.aut_IDauxiliar = idprovfact
            LEFT JOIN genautsri   liq    ON liq.aut_ID = autorizacion  AND liq.aut_tipoDocum = 3 AND liq.aut_IDauxiliar = -99
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ice   ON tab_codtabla = 6 and tab_codigo = porcentajeIce
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
	LEFT JOIN concomprobantes on com_NumRetenc = ID
	WHERE tipoTransac = 1 and YEAR(com_feccontab) = " . $anio . " and MONTH(com_FecContab) = " . $mes;

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