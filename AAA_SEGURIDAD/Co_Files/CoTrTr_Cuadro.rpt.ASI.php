<?php
/*    Reporte - Cuadro de Cuentas por Pagar
     @rev	esl	07/12/2010	excluir documentos de bitacora que fueron devueltos al cliente
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
$pcom_TipoComp = fGetParam('com_TipoComp','');
$pcom_NumComp = fGetParam('com_NumComp','');
$pcom_FecVencimDesde = fGetParam('com_FecVencimDesde','');
$pcom_FecVencimHasta = fGetParam('com_FecHasta','');
$pidProvFact = fGetParam('idProvFact','');
$pestado = fGetParam('estado','');
if ($pestado != ""){
   $p_estado = $pestado;
}
if (strtoupper($pestado) == "'PENDIENTE'"){
   $p_estado = "'VENCIDO', 'POR VENCER'";
}

$subtitulo = fGetParam('pCond','');
$subtitulo="Cuadro de Cuentas por Pagar";
$Tpl->assign("subtitulo",$subtitulo);

/*para consultar los detalles*/
$sSql = "select   con.com_FecContab,
		  con.com_CodEmpresa,
		  concat(con.com_TipoComp,'-',con.com_NumComp) as numComprobante,
		  fis.codProv,
		  fis.idProvFact,
		  concat(per.per_Apellidos,' ',per.per_Nombres) as nombreProveedor,
		  fis.establecimiento,
		  fis.puntoEmision,
		  fis.secuencial,
		  con.com_NumRetenc as ID,
		  fis.estabretencion1,
		  fis.puntoEmiRetencion1,
		  fis.secretencion1,
		  fis.fechaEmision,
		  con.com_FecVencim,
		  case
			   when cpp_saldo>0 then datediff(com_FecVencim,CURDATE())
			   when cpp_saldo<=0 then 0
		  end as diasVencidos,
		  concat(ifnull(con.com_concepto, ''), '  /  ',ifnull(cpp_glosa,'')) as concepto,
		  ifnull(fis.baseImponible,0)+ifnull(fis.baseImpGrav,0) as valFactura,
		  ifnull(fis.montoIva, 0) as iva,
		  ifnull(fis.baseImponible,0)+ifnull(fis.baseImpGrav,0)+ifnull(fis.montoIva,0) as total,
		  case 
			   when cpp_saldo=0 then 'PAGADO'
			   when cpp_saldo<0 then 'PAGADO!'
			   when cpp_saldo>0 and (com_FecVencim > CURDATE()) then 'POR VENCER'
			   when cpp_saldo>0 and (com_FecVencim <= CURDATE()) then 'VENCIDO'
		  END AS estado,
		  cpp_saldo,
		  concat(con.com_usuario, ', ',con.com_FecDigita) as usuario
	  from fiscompras fis
	  inner join concomprobantes con on
		  con.com_NumRetenc = fis.ID
	  inner join conpersonas per on
		  per.per_CodAuxiliar = fis.idProvFact
	  inner join v_consalporpag on
		  cpp_idauxiliar = fis.idProvFact
		  and cpp_numfact = fis.secuencial
	  where con.com_TipoComp = '". $pcom_TipoComp."'";

//$sSql .= ($pcom_TipoComp ? " where con.com_TipoComp = '". $pcom_TipoComp  : "'" );
//$sSql .= ($pQry ? " and ". $pQry  : "  " );
$sSql .= ($pcom_NumComp ? " and con.com_NumComp = ". $pcom_NumComp  : "  " );
$sSql .= ($pcom_FecVencimDesde ? " and con.com_FecVencim >= ". $pcom_FecVencimDesde  : "  " );
$sSql .= ($pcom_FecVencimHasta ? " and con.com_FecVencim <= ". $pcom_FecVencimHasta  : "  " );
$sSql .= ($pidProvFact ? " and fis.idProvFact = ". $pidProvFact  : "  " );
$sSql .= ($p_estado ? " and case 
				 when cpp_saldo=0 then 'PAGADO'
				 when cpp_saldo<0 then 'PAGADO!'
				 when cpp_saldo>0 and (com_FecVencim > CURDATE()) then 'POR VENCER'
				 when cpp_saldo>0 and (com_FecVencim <= CURDATE()) then 'VENCIDO'
		  	END IN (". $p_estado : "  " );
IF ($p_estado != ""){
   $sSql .= ")";
}





/*Facturas ingresadas en bitacora*/
$sSql .= " UNION
	   SELECT NULL AS com_FecContab, NULL AS com_CodEmpresa, NULL AS numComprobante, NULL AS codProv, bita.bit_idAux AS  idProvFact, CONCAT(per.per_Apellidos,' ',per.per_Nombres) AS nombreProveedor
		,bita.bit_secDoc AS establecimiento, bita.bit_emiDoc AS puntoEmision, bita.bit_numDoc AS secuencial, NULL AS ID, NULL AS estabRetencion1
		,NULL AS puntoEmiRetencion1, NULL AS secRetencion1, bita.bit_fechaDoc AS fechaEmision, NULL AS com_FecVencim, NULL AS diasvencidos
		, 'INGRESADA EN BITACORA' AS concepto, bit_valor AS valFactura, NULL AS iva, NULL AS total, 'EN TRANSITO' AS estado, NULL AS cpp_saldo, NULL AS usuario
	  FROM bitacora bita
	  JOIN bitacoradetalle deta ON  deta.bit_tipoDoc  = bita.bit_tipoDoc  AND deta.bit_secDoc  = bita.bit_secDoc
	  AND deta.bit_emiDoc = bita.bit_emiDoc  AND deta.bit_numDoc = bita.bit_numDoc
	  AND deta.bit_idAux = bita.bit_idAux AND deta.bit_registro  = bita.bit_registro
	  JOIN seguridad.segempresas seg ON bita.bit_codEmpresa = seg.emp_IDempresa
	  /*las facturas que han sido ingresadas para ese productor*/
	  LEFT JOIN fiscompras fis   ON  fis.tipoTransac = 1 /*FACTURAS*/ 
				AND bita.bit_secDoc = establecimiento 
				AND bita.bit_emiDoc = puntoemision 
				AND bita.bit_numDoc = secuencial 
				AND bita.bit_idAux = idProvFact
	  JOIN conpersonas per ON per.per_CodAuxiliar = bita.bit_idAux
	  WHERE seg.emp_BaseDatos = '".$_SESSION["g_dbase"] ."'
	  AND bita.bit_tipoDoc = 'FC'
	  AND deta.bit_secuencia = (SELECT MAX(de.bit_secuencia) FROM bitacora bi
			JOIN bitacoradetalle de ON de.bit_tipoDoc  = bi.bit_tipoDoc  AND de.bit_secDoc  = bi.bit_secDoc
				AND de.bit_emiDoc = bi.bit_emiDoc  AND de.bit_numDoc = bi.bit_numDoc
				AND de.bit_idAux = bi.bit_idAux AND de.bit_registro  = bi.bit_registro  
			WHERE bi.bit_tipoDoc = bita.bit_tipoDoc  AND bi.bit_secDoc = bita.bit_secDoc	
				AND bi.bit_emiDoc = bita.bit_emiDoc  AND bi.bit_numDoc = bita.bit_numDoc
				AND bi.bit_idAux = bita.bit_idAux AND bi.bit_registro = bita.bit_registro				)
	  AND deta.bit_estado != 4
	  AND bita.bit_anulado = 0
	  AND id IS NULL
	 ";


$sSql .= ($pcom_FecVencimDesde ? " and bita.bit_fechaDoc >= ". $pcom_FecVencimDesde  : "  " );
$sSql .= ($pcom_FecVencimHasta ? " and bita.bit_fechaDoc <= ". $pcom_FecVencimHasta  : "  " );
$sSql .= ($pidProvFact ? " and bita.bit_idAux = ". $pidProvFact  : "  " );

$sSql .= " ORDER BY 6, 1"; 


$rs = $db->execute($sSql . $slFrom);

if($rs->EOF){
    fErrorPage('','NO SE GENERARON CUENTAS POR PAGAR', true,false);
}else{
    
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
    $Tpl->assign("slPiePag", $slPiePag);
    if (!$Tpl->is_cached('CoTrTr_Cuadro.tpl')) {
            }
            $Tpl->display('CoTrTr_Cuadro.tpl');
}
?>