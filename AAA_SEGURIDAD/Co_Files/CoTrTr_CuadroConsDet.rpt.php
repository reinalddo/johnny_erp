<?php
/*    Reporte - Cuadro de Cuentas por Pagar
     @rev	esl	07/12/2010	Excluir documentos de bitacora que fueron devueltos al cliente
     @rev	esl	17/01/2011	no mostrar el reporte para las empresas que no sean consolidadas
 */

ob_start("ob_gzhandler");
ini_set("max_execution_time",0);
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
$subtitulo="Cuadro Consolidado de Cuentas por Pagar";
$Tpl->assign("subtitulo",$subtitulo);



/* ****************************************************************************
   esl 17/01/2011 Empresas individuales no ven los reportes consolidados
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
   fErrorPage('','LA EMPRESA SELECCIONADA NO APLICA REPORTE CONSOLIDADO', true,false);
}else{ /* ************************************************************************************** */
         

/* Borrar datos anteriores de la tabla de la consulta:*/
$sSql= "delete from 09_base.tmp_cxp";
$rs = $db->execute($sSql);

/* Cosultar todas las empresas:*/
$sSql= "select * from seguridad.segempresas where emp_Consolidacion like '%C%'";
$rs = $db->execute($sSql);

if($rs->EOF){
    echo('PROBLEMA AL BUSCAR EMPRESAS');
}else{
    $sSql= "";// limpiar para nueva consulta
    $rs->MoveFirst();
    while ($r = $rs->fetchRow()){  
      // Formar query para consulta consolidada
      $sSql .= " UNION ";
      $sSql .= "select '".$r['emp_Descripcion']."' as empresa,
		  '".$r['emp_BaseDatos']."' as base,
		  con.com_FecContab,
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
		  concat(con.com_usuario, ', ',con.com_FecDigita) as usuario,
		  case    when cpp_saldo<=0 then info
		  	  else ''
		  end as detalle
	  from ".$r['emp_BaseDatos'].".fiscompras fis
	  inner join ".$r['emp_BaseDatos'].".concomprobantes con on
		  con.com_NumRetenc = fis.ID
	  inner join ".$r['emp_BaseDatos'].".conpersonas per on
		  per.per_CodAuxiliar = fis.idProvFact
	  inner join ".$r['emp_BaseDatos'].".v_consalporpag on
		  cpp_idauxiliar = fis.idProvFact
		  and cpp_numfact = fis.secuencial
	  left join ".$r['emp_BaseDatos'].".v_condetxpag vcxp on
		  vcxp.det_codcuenta LIKE '2000000%'
		  and vcxp.det_idauxiliar = fis.idProvFact
		  and vcxp.det_numcheque = fis.secuencial
	  where con.com_TipoComp = '". $pcom_TipoComp."'";
	  
	  // Condiciones para la búsqueda
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
		    SELECT '".$r['emp_Descripcion']."' as empresa,
		  '".$r['emp_BaseDatos']."' as base
		         ,NULL AS com_FecContab, NULL AS com_CodEmpresa, NULL AS numComprobante, NULL AS codProv, bita.bit_idAux AS  idProvFact, CONCAT(per.per_Apellidos,' ',per.per_Nombres) AS nombreProveedor
			 ,bita.bit_secDoc AS establecimiento, bita.bit_emiDoc AS puntoEmision, bita.bit_numDoc AS secuencial, NULL AS ID, NULL AS estabRetencion1
			 ,NULL AS puntoEmiRetencion1, NULL AS secRetencion1, bita.bit_fechaDoc AS fechaEmision, NULL AS com_FecVencim, NULL AS diasvencidos
			 ,'INGRESADA EN BITACORA' AS concepto, bit_valor AS valFactura, NULL AS iva, NULL AS total, 'EN TRANSITO' AS estado, NULL AS cpp_saldo, NULL AS usuario
			 ,''
		   FROM bitacora bita
		   JOIN bitacoradetalle deta ON  deta.bit_tipoDoc  = bita.bit_tipoDoc  AND deta.bit_secDoc  = bita.bit_secDoc
		   AND deta.bit_emiDoc = bita.bit_emiDoc  AND deta.bit_numDoc = bita.bit_numDoc
		   AND deta.bit_idAux = bita.bit_idAux AND deta.bit_registro  = bita.bit_registro
		   JOIN seguridad.segempresas seg ON bita.bit_codEmpresa = seg.emp_IDempresa
		   /*las facturas que han sido ingresadas para ese productor*/
		   LEFT JOIN ".$r['emp_BaseDatos'].".fiscompras fis   ON  fis.tipoTransac = 1 /*FACTURAS*/ 
					 AND bita.bit_secDoc = establecimiento 
					 AND bita.bit_emiDoc = puntoemision 
					 AND bita.bit_numDoc = secuencial 
					 AND bita.bit_idAux = idProvFact
		   JOIN ".$r['emp_BaseDatos'].".conpersonas per ON per.per_CodAuxiliar = bita.bit_idAux
		   WHERE seg.emp_BaseDatos = '".$r['emp_BaseDatos']."'
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
	 
    }
    $sSql = substr($sSql, 7); // para quitar el "union" del principio.
}

// para insertar los datos en la tabla
$sSql = "insert into 09_base.tmp_cxp ".$sSql; 
$rs = $db->execute($sSql);

// Consultar el reporte
$sSql = " "; 
$sSql = "select * from 09_base.tmp_cxp".$sSql; 
$sSql .= " order by empresa, nombreProveedor, com_FecContab";

//echo($sSql);

//header("Character-Encoding", "utf8");
//header('Content-Type: text/html; charset=UTF-8');

$rs = $db->execute($sSql . $slFrom);

if($rs->EOF){
    fErrorPage('','NO SE GENERARON CUENTAS POR PAGAR', true,false);
}else{
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agData", $aDet);
    $slPiePag = $_SESSION["g_user"] . ", " . date("%d %M %y");
    $Tpl->assign("slPiePag", $slPiePag);
    if (!$Tpl->is_cached('CoTrTr_CuadroConsDet.tpl')) {
            }
            $Tpl->display('CoTrTr_CuadroConsDet.tpl');
}

}

?>