<?php
/*
*   CoRtTr_archxml: Generacion de Archivo XML
*   @author     Alejandro Valencia
*   @param      string		pQryLiq  Condición de búsqueda
*   @output     contenido pdf del reporte.
*   @rev        fah 26/02/08   procesar los datos de forma diferente, segun sea año 2006 o 2007
*   @rev        fah 03/09/08   Soporte de autorizaciones multiples (varios tipos de documentos con una misma autorizacion)
*   @rev	esl 13/04/10   Exportacion - excluir algunos campos del nodo si es que no usan numero de referendo
*   @rev	esl 13/11/12   Parametrizar tipo de comprobante para ventas (Asisbane-Wacho Neira) Parametros Generales -> Contabilidad -> DIMM -> Tipos de Comprobante clave:DIMCOM sec:1
*   @rev	esl 26/12/12   Parametrizar tipo de comprobante para ventas  en NOTAS DE CREDITO(Asisbane-Wacho Neira) Parametros Generales -> Contabilidad -> DIMM -> Tipos de Comprobante clave:DIMCOM sec:2
*   @rev	jvl 26/06/13   Incluir COM 46 a 50 para segunda Retencion - (Arbeloa Carlos Valencia)
*/
error_reporting(E_ALL);
include("../LibPhp/ezPdfReport.php");
include("../LibPhp/ComExCCS.php");
ini_set("max_execution_time", 120);
//include("../LibPhp/GenCifras.php");
/**
*   Definicion de Query que selecciona los datos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineCompras(&$db, $pQry=false){
	global $pAnio;
    $ilNumProceso= fGetParam('pro_ID', 0);
	$slAutCond = "";
	if ($pAnio == 2006  ){ //	@fah 26/02/08					Validar autorizacion + tipo doc + ruc solo para el 2006 // se aplica tambien al 2008
		$slAutCond = " and aut_tipodocum = tipocomprobante
									AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
								   	      tipocomprobante =3 ) " ;
	}
    /*if ($pAnio == 2007  ){ //	@fah 26/02/08					Validar autorizacion + tipo doc + ruc solo para el 2006 // se aplica tambien al 2008
		$slAutCond = " and aut_tipodocum = tipocomprobante
									AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
								   	      tipocomprobante =3 ) " ;
	}*/
    if ($pAnio >= 2008  ){ //	@fah 26/02/08					Validar autorizacion + tipo doc + ruc solo para el 2006 // se aplica tambien al 2008
		$slAutCond = " and aut_tipodocum = tipocomprobante
									AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
								   	      tipocomprobante =3 ) and aut.aut_IDauxiliar = idprovfact " ;
	}
    
    
    /*@todo:    Preocesar correctamente la relacion fiscompra - genaut sobre cada tipo de documento. */
    $alSql =
	"SELECT ID, tipoTransac, codSustento, devIva, tid.par_Valor1 AS tpIdProv, per_Ruc as idProv,
			tipoComprobante, DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
			DATE_FORMAT(fechaEmision ,'%d/%m/%Y') AS fechaEmision,
			establecimiento, puntoEmision, secuencial, autorizacion,
			if(tipoComprobante = 3, DATE_FORMAT(liq.aut_FecVigencia,'%d/%m/%Y'), 
               DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') ) AS fechaCaducidad,
			0 as baseNoGraIva,
			baseImponible, baseImpGrav,porcentajeIva, montoIva,
			baseImpIce, IFNULL(ice.tab_codsecuencial,0) as porcentajeIce, 
			montoIce, montoIvaBienes, porRetBienes, valorRetBienes,
			montoIvaServicios,
			porRetServicios,
			CASE ris.tab_TxtData	WHEN 100 THEN 0	ELSE valorRetServicios	END AS valorRetServicios,
			CASE ris.tab_TxtData	WHEN 100 THEN valorRetServicios ELSE 0	END AS valRetServ100,
			ai1.tab_txtData as codRetAir, baseImpAir, porcentajeAir, valRetAir,
			ai2.tab_txtData as codRetAir2, baseImpAir2, porcentajeAir2, valRetAir2,
			ai3.tab_txtData as codRetAir3, baseImpAir3, porcentajeAir3, valRetAir3,
			estabRetencion1, puntoEmiRetencion1 AS ptoEmiRetencion1, secRetencion1, autRetencion1,
			DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS fechaEmiRet1, 
			estabRetencion2, puntoEmiRetencion2 AS ptoEmiRetencion2, secRetencion2, autRetencion2,
			DATE_FORMAT(fechaEmiRet2 ,'%d/%m/%Y') AS fechaEmiRet2, 
			docModificado,
			DATE_FORMAT(fechaEmiModificado ,'%d/%m/%Y') AS fechaEmiModificado, estabModificado,
			ptoEmiModificado, secModificado,  autModificado,
			contratoPartidoPolitico, montoTituloOneroso, montoTituloGratuito
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_ID = autorizacion  " . $slAutCond . "  
            LEFT JOIN genautsri   liq    ON liq.aut_ID = autorizacion  AND liq.aut_tipoDocum = 3 AND liq.aut_IDauxiliar = -99
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ice   ON tab_codtabla = 6 and tab_codigo = porcentajeIce
			/* porcentajes de rte. iva: 5 para servicios, 5A para bienes */
			LEFT JOIN fistablassri ris   ON ris.tab_codtabla = '5' AND ris.tab_codigo = porRetServicios
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
			WHERE tipoTransac IN (1,8) " ;
            //WHERE tipoTransac = 1 " ;
// LEFT JOIN genautsri   aut    ON aut.aut_ID = autorizacion  AND aut.aut_tipodocum = tipocomprobante " . $slAutCond . "			
   if (strlen($pQry) > 0 ) $alSql .= " AND  "  . $pQry ;
//    WHERE " . $pQry . " ";
//  echo $alSql;
    $rs= fSQL($db, $alSql);
    return $rs;
}
/**
*   Definicion de Query que selecciona los datos deVentas a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineVentas(&$db, $pQry=false){
/*
*            establecimiento, puntoEmision, secuencial, autorizacion,
*			DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') AS fechaCaducidad,
*/

    $alSql =
	"SELECT tipoTransac,
            tid.tab_codSecuencial AS tpIdCliente,
            per_Ruc as idCliente,
			tipoComprobante,
            DATE_FORMAT(fechaRegistro ,'01/%m/%Y') AS fechaRegistro,
			DATE_FORMAT(fechaEmision ,'01/%m/%Y') AS fechaEmision,
			ivaPresuntivo,
            porcentajeIva,
            porcentajeIce,
            porRetBienes,
            porRetServicios,
            retPresuntiva,
			ai1.tab_txtData as codRetAir,  porcentajeAir, 
			ai2.tab_txtData as codRetAir2, porcentajeAir2, 
			ai3.tab_txtData as codRetAir3, porcentajeAir3, 
			count(*) as numeroComprobantes,
        	SUM(baseImponible) as baseImponible, SUM(baseImpGrav) as baseImpGrav,
        	SUM(baseImpIce) as baseImpIce,  SUM(montoIce) as montoIce,
        	SUM(montoIva) as montoIva, SUM(montoIvaBienes) as montoIvaBienes,
        	SUM(valorRetBienes) valorRetBienes, SUM(montoIvaServicios) montoIvaServicios,
        	SUM(valorRetServicios) valorRetServicios, SUM(baseImpAir) baseImpAir,
        	SUM(valRetAir) valRetAir, SUM(baseImpAir2) baseImpAir2,
        	SUM(valRetAir2) valRetAir2, SUM(baseImpAir3) baseImpAir3, SUM(valRetAir3) valRetAir3
		,0 AS baseNoGraIva,0 AS valorRetIva,0 valorRetRenta /*AGREGADO PARA ESTRUCTURA DESDE 2008*/
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_IDauxiliar = per_codauxiliar AND aut.aut_ID = autorizacion
			LEFT JOIN fistablassri tid   ON tab_codtabla = 1 and tab_codigo = tipotransac and tab_txtData  = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
			WHERE tipoTransac = 2 " ;
			
   if (strlen($pQry) > 0 ) $alSql .= " AND "  . $pQry ;
   $alSql .= " GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18 ";
   
   
   /* NUEVO QUERY PARA VENTAS------------ */
   //esl 13/11/12   Parametrizar tipo de comprobante para ventas (Asisbane-Wacho Neira) Parametros Generales -> Contabilidad -> DIMM -> Tipos de Comprobante clave:DIMCOM sec:1
   //fah 11/11/14	El tipo de identifiacion se asocia con genparametros, ya no con fistablas
   $alSql .=" UNION
		SELECT 	'' AS tipoTransac,
			/*tid.tab_codSecuencial AS tpIdCliente,*/
			tid.par_valor1 AS tpIdCliente
			per_Ruc AS idCliente,
			/* 2 AS tipoComprobante, */
			IFNULL((SELECT par_valor1 FROM genparametros WHERE par_clave = 'DIMCOM' AND par_secuencia = 1), 2) AS tipoComprobante,
			'' AS fechaRegistro,
			'' AS fechaEmision,
			0 AS ivaPresuntivo,
			0 AS porcentajeIva,
			0 AS porcentajeIce,
			0 AS porRetBienes,
			0 AS porRetServicios,
			0 AS retPresuntiva,
			0 AS codRetAir,  0 AS porcentajeAir, 
			0 AS codRetAir2, 0 AS porcentajeAir2, 
			0 AS codRetAir3, 0 AS porcentajeAir3, 
			COUNT(*) AS numeroComprobantes,
			SUM(CASE act_IvaFlag WHEN 0 THEN det_ValTotal ELSE 0 END) AS baseImponible,
			SUM(CASE act_IvaFlag WHEN 0 THEN 0 ELSE det_ValTotal END) AS baseImpGrav,
			0 AS baseImpIce,  0 AS montoIce,
			SUM(CASE act_IvaFlag WHEN 0 THEN 0 ELSE (det_ValTotal*0.12) END) AS montoIva,
			0 AS montoIvaBienes,
			0 valorRetBienes, 0 montoIvaServicios,
			0 valorRetServicios, 0 baseImpAir,
			0 valRetAir, 0 baseImpAir2,
			0 valRetAir2, 0 baseImpAir3, 0 valRetAir3
			/*AGREGADO PARA ESTRUCTURA DESDE 2008*/
			,0 AS baseNoGraIva,
			IFNULL(SUM(CASE WHEN (inv.det_Secuencia = (SELECT MAX(det_Secuencia) FROM invdetalle WHERE det_RegNUmero = inv.det_regnumero)) THEN rete.valorRetIva ELSE 0 END),0) AS valorRetIva,
			IFNULL(SUM(CASE WHEN (inv.det_Secuencia = (SELECT MAX(det_Secuencia) FROM invdetalle WHERE det_RegNUmero = inv.det_regnumero)) THEN rete.valorRetRenta ELSE 0 END),0) AS valorRetRenta
			FROM concomprobantes com
	JOIN invdetalle inv ON inv.det_RegNUmero = com.com_RegNumero
	JOIN conactivos act ON act.act_CodAuxiliar = inv.det_CodItem
	LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = com_CodReceptor
	LEFT JOIN genparametros tid  ON par_clave = 'TIPID' and par_secuencia = per_tipoID
	LEFT JOIN v_retencionVTA rete ON com.com_NumComp = rete.documento AND com.com_CodReceptor = rete.auxiliar
	WHERE com.com_TipoComp = 'FA' AND com.com_Libro IN (SELECT par_Secuencia FROM genparametros WHERE par_Clave = 'CLIBRO' AND par_Valor4 = 'ATS2') ";
	
	
	// LEFT JOIN fistablassri tid   ON tab_codtabla = 1 AND tab_codigo = '2' AND tab_txtData  = per_tipoID
	$condicion = str_replace("fechaRegistro","com.com_FecContab",$pQry);
	if (strlen($pQry) > 0 ) $alSql .= " AND "  . $condicion ;
	$alSql .= " GROUP BY tpIdCliente, idCliente, tipoComprobante ";
	/* FIN DEL NUEVO QUERY PARA VENTAS------------ */
	
	// AGREGAR NC A VENTAS
	//esl 26/12/12   Parametrizar tipo de comprobante para ventas  en NOTAS DE CREDITO(Asisbane-Wacho Neira) Parametros Generales -> Contabilidad -> DIMM -> Tipos de Comprobante clave:DIMCOM sec:2
	$alSql .=" UNION
		SELECT 	'' AS tipoTransac,
			tid.tab_codSecuencial AS tpIdCliente,
			per_Ruc AS idCliente,
			IFNULL(tnc.par_Valor1, 4) AS tipoComprobante,
			'' AS fechaRegistro,
			'' AS fechaEmision,
			0 AS ivaPresuntivo,
			0 AS porcentajeIva,
			0 AS porcentajeIce,
			0 AS porRetBienes,
			0 AS porRetServicios,
			0 AS retPresuntiva,
			0 AS codRetAir,  0 AS porcentajeAir, 
			0 AS codRetAir2, 0 AS porcentajeAir2, 
			0 AS codRetAir3, 0 AS porcentajeAir3, 
			COUNT(*) AS numeroComprobantes,
			SUM(CASE act_IvaFlag WHEN 0 THEN det_ValTotal ELSE 0 END) AS baseImponible,
			SUM(CASE act_IvaFlag WHEN 0 THEN 0 ELSE det_ValTotal END) AS baseImpGrav,
			0 AS baseImpIce,  0 AS montoIce,
			SUM(CASE act_IvaFlag WHEN 0 THEN 0 ELSE (det_ValTotal*0.12) END) AS montoIva,
			0 AS montoIvaBienes,
			0 valorRetBienes, 0 montoIvaServicios,
			0 valorRetServicios, 0 baseImpAir,
			0 valRetAir, 0 baseImpAir2,
			0 valRetAir2, 0 baseImpAir3, 0 valRetAir3
			/*AGREGADO PARA ESTRUCTURA DESDE 2008*/
			,0 AS baseNoGraIva, 0, 0
	FROM concomprobantes com
	JOIN invdetalle inv ON inv.det_RegNUmero = com.com_RegNumero
	JOIN conactivos act ON act.act_CodAuxiliar = inv.det_CodItem
	LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = com_CodReceptor
	LEFT JOIN fistablassri tid   ON tab_codtabla = 1 AND tab_codigo = '2' AND tab_txtData  = per_tipoID
	LEFT JOIN genparametros tnc ON tnc.par_clave = 'DIMCOM' AND tnc.par_secuencia = 2 
	WHERE com.com_TipoComp = tnc.par_valor2 AND com.com_Libro IN (SELECT par_Secuencia FROM genparametros WHERE par_Clave = 'CLIBRO' AND par_Valor4 = 'ATS2')  ";
	
	$condicion = str_replace("fechaRegistro","com.com_FecContab",$pQry);
	if (strlen($pQry) > 0 ) $alSql .= " AND "  . $condicion ;
	$alSql .= " GROUP BY tpIdCliente, idCliente, tipoComprobante ";
	/* FIN DEL NUEVO QUERY PARA VENTAS NC------------ */
  
   
//    WHERE " . $pQry . " ";
// echo $alSql;
    $rs= fSQL($db, $alSql);
    return $rs;
}

/**
*   Definicion de Query que selecciona los datos de Importaciones a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineImport(&$db, $pQry=false){
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql =
	"SELECT tipoTransac, codSustento, tipImpExp as importacionDe, DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaLiquidacion,
            tipoComprobante, distAduanero, anio, regimen, correlativo, verificador,
            per_Ruc as idFiscalProv, valorCifFob as valorCIF, concat(per_Apellidos, per_Nombres) as razonSocialProv,
            per_subCategoria as tipoSujeto,
            baseImponible, baseImpGrav,porcentajeIva, montoIva,
			baseImpIce, porcentajeIce, montoIce,
			ai1.tab_txtData as codRetAir, baseImpAir, porcentajeAir, valRetAir,
			ai2.tab_txtData as codRetAir2, baseImpAir2, porcentajeAir2, valRetAir2,
			ai3.tab_txtData as codRetAir3, baseImpAir3, porcentajeAir3, valRetAir3
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_IDauxiliar = per_codauxiliar AND aut.aut_ID = autorizacion
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
			WHERE tipoTransac = 3 " ;
   if (strlen($pQry) > 0 ) $alSql .= " AND "  . $pQry ;
//    WHERE " . $pQry . " ";
 // echo $alSql;
    $rs= fSQL($db, $alSql);
    return $rs;
}
/**
*   Definicion de Query que selecciona los datos de Exportaciones a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.  fah04/02/08  per_subcategoia enlugar de par_valor1
*/
function &fDefineExport(&$db, $pQry=false){
    $alSql =	"SELECT 	tipImpExp AS exportacionDe,
				tipoComprobante,
				distAduanero, anio, regimen, correlativo, verificador,
				documEmbarque AS docTransp, DATE_FORMAT(fechaEmbarque,'%d/%m/%Y') AS fechaEmbarque,
				NULL AS fue,
				valorCifFob AS valorFOB,
				valorFOBComprobante,
				establecimiento, puntoEmision, secuencial,
				autorizacion,
				DATE_FORMAT(fechaEmision ,'%d/%m/%Y') AS fechaEmision
				/* ,tipoTransac,
				codSustento,
				DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
				per_Ruc AS idFiscalCliente,
				per_subcategoria AS tipoSujeto,
				CONCAT(per_Apellidos, ' ', per_Nombres) AS razonSocialCliente,
				devIva,
				facturaExportacion
					    */
		    FROM fiscompras
					    LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
					    LEFT JOIN genautsri   aut    ON aut.aut_IDauxiliar = per_codauxiliar AND aut.aut_ID = autorizacion
					    LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
					    LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
					    WHERE tipoTransac = 4 " ;
   if (strlen($pQry) > 0 ) $alSql .= " AND "  . $pQry ;
   
   /* NUEVO QUERY PARA EXPORTACION------------ */
   $alSql .=" UNION
		SELECT  CASE IFNULL(CONCAT(dau_distAduanero,dau_anio,dau_regimen,dau_correlativo,dau_verificador),' ') 
			WHEN ' '  THEN '02'  /*Sin Refrendo*/
			WHEN NULL THEN '02' 
			ELSE '01' /*Con Refrendo*/
			END AS exportacionDe,
			'1' AS tipoComprobante /*Factura*/,
			/* SUBSTR(dau_numreferendo, 1,3) AS distAduanero,
			SUBSTR(dau_numreferendo, 5,4) AS anio,
			SUBSTR(dau_numreferendo, 10,2) AS regimen,
			SUBSTR(dau_numreferendo, 13,6) AS correlativo,
			SUBSTR(dau_numreferendo, 20,1) AS verificador, */
			
			dau_distAduanero AS distAduanero,
			dau_anio AS anio,
			dau_regimen AS regimen,
			dau_correlativo AS correlativo,
			dau_verificador AS verificador,
			
			dau_ordenemb AS docTransp,
			DATE_FORMAT(dau_zarpe,'%d/%m/%Y') AS fechaEmbarque,
			dau_numerodau AS fue,
			dau_valortotalfob AS valorFOB,
			IFNULL(com_Valor,dau_valortotalfob) AS valorFOBComprobante,
			'001' AS establecimiento,
			'001' AS puntoEmision,
			LPAD(dau_fact,7,0) AS secuencial,
			dau_autorizacion AS autorizacion,
			DATE_FORMAT(dau_zarpe,'%d/%m/%Y') AS fechaEmision
		FROM fis_dau
		JOIN concomprobantes ON com_regnumero = dau_RegNumero )
		WHERE dau_estado = 1 ";
		
	//JOIN concomprobantes ON com_TipoComp = 'FA' AND com_NumComp LIKE CONCAT('1001',LPAD(dau_fact,6,0))
	$condicion = str_replace("fechaRegistro","com.com_FecContab",$pQry);
	if (strlen($pQry) > 0 ) $alSql .= " AND "  . $condicion ;
// echo $alSql;
    $rs= fSQL($db, $alSql);
    return $rs;
}
/**
*   Definicion de Query que selecciona los datos de Recap a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineRecap(&$db, $pQry=false){
    $alSql =
	"SELECT tipoTransac, codSustento, tid.par_Valor1 AS tpIdProv, per_Ruc as idProv,
			tipoComprobante, DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
			establecimiento, puntoEmision, secuencial, autorizacion,
			DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') AS fechaCaducidad,
			baseImponible, baseImpGrav,porcentajeIva, montoIva,
			baseImpIce, porcentajeIce, montoIce, montoIvaBienes, porRetBienes, valorRetBienes,
			montoIvaServicios, porRetServicios, valorRetServicios,
			ai1.tab_txtData as codRetAir, baseImpAir, porcentajeAir, valRetAir,
			ai2.tab_txtData as codRetAir2, baseImpAir2, porcentajeAir2, valRetAir2,
			ai3.tab_txtData as codRetAir3, baseImpAir3, porcentajeAir3, valRetAir3,
			estabRetencion1, puntoEmiRetencion1 AS ptoEmiRetencion1, autRetencion1,
			DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS fechaEmiRet1, 
			estabRetencion2, puntoEmiRetencion2 AS ptoEmiRetencion2, autRetencion2,
			DATE_FORMAT(fechaEmiRet2 ,'%d/%m/%Y') AS fechaEmiRet2, 
			docModificado,
			DATE_FORMAT(fechaEmiModificado ,'%d/%m/%Y') AS fechaEmiModificado, estabModificado,
			ptoEmiModificado, secModificado,  autModificado,
			contratoPartidoPolitico, montoTituloOneroso, montoTituloGratuito
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_IDauxiliar = per_codauxiliar AND aut.aut_ID = autorizacion
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
			WHERE tipoTransac = 5 " ;
   if (strlen($pQry) > 0 ) $alSql .= " AND "  . $pQry ;
//    WHERE " . $pQry . " ";
// echo $alSql;
    $rs= fSQL($db, $alSql);
    return $rs;
}
/**
*   Definicion de Query que selecciona los datos de Fideicomisos a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineFideicom(&$db, $pQry=false){
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql =
	"SELECT tipoTransac, codSustento, tid.par_Valor1 AS tpIdProv, per_Ruc as idProv,
			tipoComprobante, DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
			establecimiento, puntoEmision, secuencial, autorizacion,
			DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') AS fechaCaducidad,
			baseImponible, baseImpGrav,porcentajeIva, montoIva,
			baseImpIce, porcentajeIce, montoIce, montoIvaBienes, porRetBienes, valorRetBienes,
			montoIvaServicios, porRetServicios, valorRetServicios,
			ai1.tab_txtData as codRetAir, codRetAir, baseImpAir, porcentajeAir, valRetAir,
			ai2.tab_txtData as codRetAir2, baseImpAir2, porcentajeAir2, valRetAir2,
			ai3.tab_txtData as codRetAir3, baseImpAir3, porcentajeAir3, valRetAir3,
			estabRetencion1, puntoEmiRetencion1 AS ptoEmiRetencion1, autRetencion1,
			DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS fechaEmiRet1, 
			estabRetencion2, puntoEmiRetencion2 AS ptoEmiRetencion2, autRetencion2,
			DATE_FORMAT(fechaEmiRet2 ,'%d/%m/%Y') AS fechaEmiRet2, 
			docModificado,
			DATE_FORMAT(fechaEmiModificado ,'%d/%m/%Y') AS fechaEmiModificado, estabModificado,
			ptoEmiModificado, secModificado,  autModificado,
			contratoPartidoPolitico, montoTituloOneroso, montoTituloGratuito
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_IDauxiliar = per_codauxiliar AND aut.aut_ID = autorizacion
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
			WHERE tipoTransac = 7 " ;
   if (strlen($pQry) > 0 ) $alSql .= " AND "  . $pQry ;
//    WHERE " . $pQry . " ";
 // echo $alSql;
    $rs= fSQL($db, $alSql);
    return $rs;
}
/**
*   Definicion de Query que selecciona los datos de Anulaciones a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineAnulados(&$db, $pQry=false){
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql =
	"SELECT tipoComprobante, establecimiento, puntoEmision, secuencialInicio, 
			secuencialFin,	autorizacion /*, DATE_FORMAT(fechaAnulacion ,'%d/%m/%Y') AS fechaAnulacion */
	FROM fisanulados
	where estado = 1 " ;
	$condicion = str_replace("YEAR(fechaRegistro)","anio",$pQry);
	$condicion = str_replace("MONTH(fechaRegistro)","mes",$condicion);
	if (strlen($pQry) > 0 ) $alSql .= " AND "  . $condicion ;
	
//    WHERE " . $pQry . " ";
 // echo $alSql;
    $rs= fSQL($db, $alSql);
    return $rs;
}

/**
*   Definicion de Query que selecciona los datos de Rendimientos Financieros a presentar
*   access  public
*   @param   object   Refrencia a la conexxion de BD
*   @param  string    Condicion de busqueda
*   @return object    Referencia del Recordset.
*/
function &fDefineRendFin(&$db, $pQry=false){
    $ilNumProceso= fGetParam('pro_ID', 0);
    $alSql =
	"SELECT tipoTransac, codSustento,  tid.par_Valor1 AS tpIdProv, per_Ruc as idProv,
			tipoComprobante, DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
			establecimiento, puntoEmision, secuencial, autorizacion,
			DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') AS fechaCaducidad,
			baseImponible, baseImpGrav,porcentajeIva, montoIva,
			baseImpIce, porcentajeIce, montoIce, montoIvaBienes, porRetBienes, valorRetBienes,
			montoIvaServicios, porRetServicios, valorRetServicios,
			ai1.tab_txtData as codRetAir, baseImpAir, porcentajeAir, valRetAir,
			ai2.tab_txtData as codRetAir2, baseImpAir2, porcentajeAir2, valRetAir2,
			ai3.tab_txtData as codRetAir3, baseImpAir3, porcentajeAir3, valRetAir3,
			estabRetencion1, puntoEmiRetencion1 AS ptoEmiRetencion1, autRetencion1,
			DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS fechaEmiRet1, 
			estabRetencion2, puntoEmiRetencion2 AS ptoEmiRetencion2, autRetencion2,
			DATE_FORMAT(fechaEmiRet2 ,'%d/%m/%Y') AS fechaEmiRet2, 
			docModificado,
			DATE_FORMAT(fechaEmiModificado ,'%d/%m/%Y') AS fechaEmiModificado, estabModificado,
			ptoEmiModificado, secModificado,  autModificado,
			contratoPartidoPolitico, montoTituloOneroso, montoTituloGratuito
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_IDauxiliar = per_codauxiliar AND aut.aut_ID = autorizacion
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
			WHERE tipoTransac = 6 " ;
   if (strlen($pQry) > 0 ) $alSql .= " AND "  . $pQry ;
//    WHERE " . $pQry . " ";
 // echo $alSql;
    $rs= fSQL($db, $alSql);
    return $rs;
}

/*
*   Recorre el registro de datos para generar cada campo en formato XML
*   @param  $alRec      referencia al Recordset dedatos
*   @param  $alCam      referencia al Recordset de estructura XML
*/
function fProcesaDatos($alRec, $alCam){
	global $detair, $airFl, $air;
	$detair=false;
	$air = false;
	$airFl = false;
	reset($alCam);
	foreach ($alCam as $olDato) {
	      if (!array_key_exists($olDato["xml_campo"], $alRec)){
	          echo " NO EXISTE EL CAMPO " . $olDato["xml_campo"] . " = " . $olDato["xml_campo"] . " <br>";
	          }
	      else {
               
	       /**
		*  VALIDAR QUE CUANDO NO SE USE NUMERO DE REFERENDO NO AGREGE EN EL NODO LOS ELEMENTOS DEL REFERENDO
		*  Si es que no usa numero de referendo
	       */
	       if($olDato["xml_tipoReg"] == 'EXP' and $alRec["exportacionDe"] == '02') {
			if (($olDato["xml_campo"] == "distAduanero") or ($olDato["xml_campo"] == "anio") or ($olDato["xml_campo"] == "regimen")
			    or ($olDato["xml_campo"] == "correlativo") or ($olDato["xml_campo"] == "verificador")){
				/*No agregar al nodo porque no es requerido si el campo exportacionDe = 02 */
			 }
			 else{
				fProcesaCampo($alRec, $olDato);
			 }
	       }
	       else {
			fProcesaCampo($alRec, $olDato);
	       }
	       /**
		*
		*
	       */
	      }
	}
}
/*
*   Analiza cada campo de datos frente ala estructura asociada y genera el objeto XML
*   @param  $alRec      referencia al Recordset de datos
*   @param  $olDato      referencia al Objeto que define la estructura del campo
*/
function fProcesaCampo($alRec, $olDato){
    global $det, $air, $doc, $airFl, $detco, $detair;
    $slK = $olDato["xml_campo"];
    $slV = $alRec[$slK];
    $slK1 = $slK;
    $slSuf="";
    $idx = strpos($slK, "Air");
	if ($idx >0) {
	    if (strlen($slK) > $idx + 3) {
	    	$slK1 = substr($slK,0, strlen($slK) - 1);
		$slSuf= substr($slK,strlen($slK) - 1);
	    }
	}
// echo "<br>$slK => $slV   ---  " . $olDato['xml_formato'] . " ///";
	
	switch($olDato["xml_formato"]) {
		    case ('N'):
		    case ('n'):
		    case ('D'):
		    case ('d'):
		    case ('E'):
		        $ilEnteros = NZ($olDato["xml_longMax"]) - NZ($olDato["xml_numDecim"]);
				$slV=fNumFormateado($slV, $ilEnteros, $olDato["xml_numDecim"], $olDato["xml_longMin"]);
				break;
			case ('dd/mm'):
			case ('dd/mm/aaaa'):
			case ('mm/aaaa'):
			case ('t/mm/aaaa'):
				$slFmt = str_replace("mm", "m", $olDato["xml_formato"]);
				$slFmt = str_replace("dd", "d", $slFmt);
				$slFmt = str_replace("aaaa", "Y", $slFmt);
				$slFmt = str_replace("aa", "y", $slFmt);
//                if ($slV< 0)
//				    echo "<br> " . $alRec['secuencial'] . " -- $slV --- $slFmt ---";
				if ($slV == "0000/00/00"
                    or $slV == "00/00/0000"
                    or $slV == "31/12/1969"
                    or is_null($slV) or strlen($slV ) < 1
                    or str2date($slV,"dmy") < str2date("2001/01/01","ymd")
                    or !date($slFmt, str2date($slV, "dmy")) ) {
					if ( $olDato["xml_requerido"] == "ob") {
						echo "<br> La Transaccion " . $alRec["ID"] . " Tipo: " . $alRec["tipoComprobante"] .
							 " Comp. Numero: " . $alRec["secuencial"] . " tiene el campo " . $slK .
						 	" un valor invalido " . $slV;
						$slV="00/00/0000";
						}
					else $slV="00/00/0000";
					}
				else 	$slV= date($slFmt, str2date($slV, "dmy"));
				break;
			case ('C'):
			case ('c'):
			    break;
			default:
			    break;
		}
//		    echo " <br> $slK -- $idx  " . ((FALSE !== $idx) ? " T " : "F");
	    if (FALSE !== $idx){
			if(!$airFl ){		//Solo en primer air
				$detco = $doc->createElement('air');
				$detair= $det->appendChild($detco);
				$airFl = true;
			}
    		if (
                ($alRec["codRetAir" . $slSuf] > 0 && $alRec["baseImpAir" . $slSuf] > 0) ) { // Si el porcentaje o valor retenido es >0
//		    if (!$air){ 		// Nuevo detalle air
		    if ($slK == "codRetAir" || $slK == "codRetAir2" || $slK == "codRetAir3" ){ // Nuevo detalle air
			    $detco = $doc->createElement('detalleAir');
			    $air   = $detair->appendChild($detco);
			}
			$slK = str_replace("Air2", "Air", $slK); //Siempre debe terminar en Air
			$slK = str_replace("Air3", "Air", $slK);
    			fAgregarElemTxt($air, $slK, $slV);
    		}
	    }
	    else{
	    	$air = false; // no corresponde a retencion en la fuente air
//		$airFl=FALSE;
// ECHO "$slK --- $slV";
	     	 fAgregarElemTxt($det, $slK, $slV);
	    }
}

function fAgregarElemPar(&$pCont, $pNom, $pVal){
	global $doc, $db;
	$slVal = fDBValor($db, 'genparametros', 'par_Descripcion', "par_clave = '$pVal'");
	fAgregarElemTxt($pCont, $pNom, $slVal);
}
function fAgregarElemTxt(&$pCont, $pNom, $pVal){
	global $doc, $db;
//	echo "$pNom -- $pVal <br>";
	$outer = $doc->createElement($pNom);
	$outer = $pCont->appendChild($outer);
	$valor = $doc->createTextNode($pVal);
	$valor = $outer->appendChild($valor);
}

function fNumFormateado($pVal, $pEnt, $pDec, $pLMin=false)
{
	$alFmtoEnt = array();
	$alFmtoDec = array();
	$alLong = 1;
	$pVal = number_format($pVal, $pDec, ".","");
	for ($i= 1;$i<=$pDec; $i++){
	    if (($alLong <= $pLMin)) $alFmtoDec[]="0";
	    else $alFmtoDec[]="#";
	    ++$alLong;
	}
	for ($i= 1;$i<=$pEnt; $i++){
	    if ($alLong <= $pLMin ) $alFmtoEnt[]="0";
	    else $alFmtoEnt[]="#";
	    ++$alLong;
	}
	$slNumF = CCFormatNumber($pVal, Array(True, $pDec, ".", "", False, $alFmtoEnt, $alFmtoDec, 1, True, ""));
// echo "E:$pEnt  ---  D: $pDec    ----  M: $pLMin //  $pVal // $slNumF";
	return $slNumF;
}
//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
ob_start();
$det= NULL;
$air= false;
$doc= NULL;
$detco= NULL;
$detair= NULL;
$airFl= false;
$doc = new DomDocument('1.0', 'UTF-8');
$root = $doc->createElement('iva');
$root = $doc->appendChild($root);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> No hay acceso al servidor de BD");
$db->debug=fGetParam('pAdoDbg', 0);
$pAnio = fGetParam('s_Anio', false);
$pMes = fGetParam('s_Mes', false);
if (!$pAnio || !$pMes) echo "DEBE ELEGIR UN AÑO Y MES PARA PROCESAR";
echo "AÑO DE PROCESO: " . $pAnio . "<br>";
echo "MES DE PROCESO: " . $pMes . "<br>";
fAgregarElemPar($root, 'numeroRuc', 'EGRUC');
fAgregarElemPar($root, 'razonSocial', 'EGNOM');
//fAgregarElemPar($root, 'direccionMatriz', 'EGDIR');
//fAgregarElemPar($root, 'telefono', 'EGTELE');
//fAgregarElemPar($root, 'fax', 'EGFAX');
//fAgregarElemPar($root, 'email', 'EGMAIL');
//fAgregarElemPar($root, 'tpIdRepre', 'EGTID');
//fAgregarElemPar($root, 'idRepre', 'EGIDR');
//fAgregarElemPar($root, 'rucContador', 'EGRCO');
fAgregarElemTxt($root, 'anio', $pAnio);
fAgregarElemTxt($root, 'mes', CCFormatNumber($pMes, Array(True, 0, ".", ",", False, array("0","0"), array(), 1, True, "")));

$cmp = $doc->createElement('compras');
$root->appendChild($cmp);
$pQry= fGetParam('pQry', false);
/**
*   Procesar registros decompras
**/
echo "<br> PROCESO DE COMPRAS---------------------------------------------------";
$rs = fDefineCompras($db, $pQry) ;
$slSql = "SELECT * from fis_xmlestruct WHERE xml_tiporeg = 'COM'";
$rsEst= $db->execute($slSql);
$alCam = $rsEst->GetArray();
$ilCount = 0;
//print_r($alCam);
if ($rs)
	while ($alRec = $rs->FetchRow()) {
	    $airFl = false;
		$detco = $doc->createElement('detalleCompras');
		$det   = $cmp->appendChild($detco);
		fProcesaDatos($alRec, $alCam);
		$ilCount++;
    }
echo "<br>$ilCount Registros de Compras procesados.<br>";

/**
*   Procesar registros de Ventas
**/
$cmp = $doc->createElement('ventas');
$root->appendChild($cmp);

$rs = fDefineVentas($db, $pQry) ;
$slSql = "SELECT * from fis_xmlestruct WHERE xml_tiporeg = 'VEN'";
$rsEst= $db->execute($slSql);
$alCam = $rsEst->GetArray();
//print_r($alCam);
$ilCount=0;
echo "<br> PROCESO DE VENTAS --------------------------------------------------";
if ($rs)
	while ($alRec = $rs->FetchRow()) {
	    $airFl = false;
		$detco = $doc->createElement('detalleVentas');
		$det   = $cmp->appendChild($detco);
		fProcesaDatos($alRec, $alCam);
		$ilCount++;
    }
echo "<br>$ilCount Registros.<br>";

/**
*   Procesar registros de Importacionesa
**/
/**
 *	EN LA ESTRUCTURA DE 2008 YA NO SE INCLUYEN IMPORTACIONES
 */
/*

$cmp = $doc->createElement('importaciones');
$root->appendChild($cmp);

$rs = fDefineImport($db, $pQry) ;
$slSql = "SELECT * from fis_xmlestruct WHERE xml_tiporeg = 'IMP'";
$rsEst= $db->execute($slSql);
$alCam = $rsEst->GetArray();
//print_r($alCam);
$ilCount=0;
echo "<br> PROCESO DE IMPORTACIONES --------------------------------------------";
if ($rs)
	while ($alRec = $rs->FetchRow()) {
	    $airFl = false;
		$detco = $doc->createElement('detalleImportaciones');
		$det   = $cmp->appendChild($detco);
		fProcesaDatos($alRec, $alCam);
		$ilCount++;
    }
echo "<br> $ilCount Registros <br>";



*/

/**
*   Procesar registros de Exportaciones
**/
$cmp = $doc->createElement('exportaciones');
$root->appendChild($cmp);

$rs = fDefineExport($db, $pQry) ;
$slSql = "SELECT * from fis_xmlestruct WHERE xml_tiporeg = 'EXP'";
$rsEst= $db->execute($slSql);
$alCam = $rsEst->GetArray();
//print_r($alCam);
$ilCount=0;
$Referendo = '';
echo "<br> PROCESO DE EXPORTACIONES ----------------------------------------------";
if ($rs)
	while ($alRec = $rs->FetchRow()) {
		$airFl = false;
		$detco = $doc->createElement('detalleExportaciones');
		$det   = $cmp->appendChild($detco);
		fProcesaDatos($alRec, $alCam);
		$ilCount++;
    }
echo "<br> $ilCount Registros<br>";

/**
*   Procesar registros de Recaps
**/
$cmp = $doc->createElement('recap');
$root->appendChild($cmp);

$rs = fDefineRecap($db, $pQry) ;
$slSql = "SELECT * from fis_xmlestruct WHERE xml_tiporeg = 'REC'";
$rsEst= $db->execute($slSql);
$alCam = $rsEst->GetArray();
//print_r($alCam);
$ilCount=0;

echo "<br> PROCESO DE RECAPS (TARJ. DE CREDITO) --------------------------------";
if ($rs)
	while ($alRec = $rs->FetchRow()) {	
	    $airFl = false;
		$detco = $doc->createElement('detalleRecap');
		$det   = $cmp->appendChild($detco);
		fProcesaDatos($alRec, $alCam);
		$ilCount++;
    }
echo "<br> $ilCount Registros <br>";    


/**
*   Procesar registros de Fideicomisos
**/
$cmp = $doc->createElement('fideicomisos');
$root->appendChild($cmp);

$rs = fDefineFideicom($db, $pQry) ;
$slSql = "SELECT * from fis_xmlestruct WHERE xml_tiporeg = 'FID'";
$rsEst= $db->execute($slSql);
$alCam = $rsEst->GetArray();
//print_r($alCam);
$ilCount=0;
echo "<br> PROCESO DE FIDEICOMISOS ---------------------------------------------";
if ($rs)
	while ($alRec = $rs->FetchRow()) {
	    $airFl = false;
		$detco = $doc->createElement('detalleFideicomisos');
		$det   = $cmp->appendChild($detco);
		fProcesaDatos($alRec, $alCam);
		$ilCount++;
    }
echo "<br> $ilCount Registros<br>";




/**
*   Procesar registros de Anulados
**/
$cmp = $doc->createElement('anulados');
$root->appendChild($cmp);

$rs = fDefineAnulados($db, $pQry) ;
$slSql = "SELECT * from fis_xmlestruct WHERE xml_tiporeg = 'ANU'";
$rsEst= $db->execute($slSql);
$alCam = $rsEst->GetArray();
//print_r($alCam);
$ilCount=0;
echo "<br> PROCESO DE ANULADOS --------------------------------------------------";
if ($rs)
	while ($alRec = $rs->FetchRow()) {
	    $airFl = false;
		$detco = $doc->createElement('detalleAnulados');
		$det   = $cmp->appendChild($detco);
		fProcesaDatos($alRec, $alCam);
		$ilCount++;
    }
echo "<br>$ilCount Registros.<br>";




/**
*   Procesar registros de Fideicomisos
**/
$cmp = $doc->createElement('rendFinancieros');
$root->appendChild($cmp);

$rs = fDefineRendFin($db, $pQry) ;
$slSql = "SELECT * from fis_xmlestruct WHERE xml_tiporeg = 'REN'";
$rsEst= $db->execute($slSql);
$alCam = $rsEst->GetArray();
//print_r($alCam);
$ilCount=0;
echo "<br> PROCESO DE RENDIMIENTOS FINANCIEROS ---------------------------------";
if ($rs)
	while ($alRec = $rs->FetchRow()) {
	    $airFl = false;
		$detco = $doc->createElement('detalleRendFinancieros');
		$det   = $cmp->appendChild($detco);
		fProcesaDatos($alRec, $alCam);
		$ilCount++;
    }
echo "<br> $ilCount Registros<br>";





$xml_string = $doc->saveXML();
$xml_string = str_replace("><", ">\r\n<", $xml_string);
$slArch=DBNAME . "_" . $pAnio . "_" . $pMes . "";
$slRutaArch="../pdf_files/" . $slArch;
if($file = fopen($slRutaArch,"w")) {
 	fwrite($file, $xml_string);
 	fclose($file);
	}
//$doc->dump_file($slRutaArch, false, true);
$host  = "http://" .$_SERVER['HTTP_HOST'];
$uri  = rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '/\\');
$extra = "pdf_files/$slArch";
$slFullPath=$host . $uri . "/".$extra ;

$slRef= $host . $uri . "/LibPhp/bajar.php?pOrig=" . $slRutaArch . "&pDest=$slArch.xml";
echo  "<a href=" . $slRef . "> Descargar el Archivo generado </a>";
ob_end_flush();
?>
