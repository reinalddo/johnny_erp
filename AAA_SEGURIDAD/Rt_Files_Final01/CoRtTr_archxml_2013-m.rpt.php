<?php

/*
 *   CoRtTr_archxml: Generacion de Archivo XML
 *   @author     Fausto Astudillo
 *   @param      string		pQryLiq  Condición de búsqueda
 *   @output     contenido pdf del reporte.
 *   @rev        fah 26/02/08   procesar los datos de forma diferente, segun sea año 2006 o 2007
 *   @rev        fah 03/09/08   Soporte de autorizaciones multiples (varios tipos de documentos con una misma autorizacion)  
 *   @rev        fah 17/10/2013 Excluir iva (montoiva) del item  ventasestablecimiento
 *   @rev	 fah 12/05/2013 Aplicar formato 2013
 *   @rev        cvl 17/11/15   Adecuar ATS de acuerdo con la ficha técnica de Marzo 2015 emitida por el SRI.
 */
error_reporting(E_ALL);
include("../LibPhp/ezPdfReport.php");
include("../LibPhp/ComExCCS.php");
include_once ("../LibPhp/libxml_display_error.php");
// ini_set("max_execution_time", 300);
set_time_limit(240);
setlocale(LC_TIME, "es_EC");
$inicio = microtime();
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN ">

<html>
<head>
<title>GENERACION XML </title>
</head>
<body>';
$glInfoTxt = "<br><br> Inicio de Proceso: " . date("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");

//include("../LibPhp/GenCifras.php");
/**
 *   Definicion de Query que selecciona los datos a presentar
 *   access  public
 *   @param   object   Refrencia a la conexxion de BD
 *   @param  string    Condicion de busqueda
 *   @return object    Referencia del Recordset.
 */
function &fDefineCompras(&$db, $pQry = false) {
    global $pAnio;
    global $rsiva10, $rsiva20, $rsiva30, $rsiva70, $rsiva100;
    $ilNumProceso = fGetParam('pro_ID', 0);
    $slAutCond = "";

    /* @todo:    Preocesar correctamente la relacion fiscompra - genaut sobre cada tipo de documento. */
    $alSql = "SELECT 
			ID,
			tipoTransac,
			codSustento,
			tid.tab_txtdata2 AS tpIdProv,
			per_Ruc AS idProv,
                        case tid.tab_txtdata when 'P' then per_subcategoria else '' end as tipoProv,
			pro.per_parterelacionada AS parteRel,
                        LPAD(CAST(tipoComprobante AS CHAR(2)),2,'0') as tipoComprobante,
			DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
			establecimiento,
			puntoEmision,
			secuencial,
			DATE_FORMAT(fechaEmision,'%d/%m/%Y') AS fechaEmision,
			autorizacion,
			baseNoGraIva, 
			baseImponible,
			baseImpGrav,
                        baseImpExe,
			montoIce,
			montoIva,
                        case porRetBienes when ".$rsiva10["tab_codigo"]." then valorRetBienes else 0 end as valRetBien10,
                        case porRetServicios when ".$rsiva20["tab_codigo"]." then valorRetServicios else 0 end as valRetServ20,
                        case porRetBienes when ".$rsiva30["tab_codigo"]." then valorRetBienes else 0 end as valorRetBienes,
                        case porRetServicios when ".$rsiva70["tab_codigo"]." then valorRetServicios else 0 end as valorRetServicios,
                        case porRetServicios when ".$rsiva100["tab_codigo"]." then valorRetServicios else 0 end as valRetServ100,
                        0 AS totbasesImpReemb,
			'01' AS pagoLocExt,
			'NA' AS paisEfecPago,
			'NA' AS aplicConvDobTrib,
			'NA' AS pagExtSujRetNorLeg,
            CASE
                WHEN baseNoGraIva+baseImponible+baseImpGrav+baseImpExe+montoIce+montoiva >= 1000 AND tipotransac = 8 THEN '08'
                WHEN baseNoGraIva+baseImponible+baseImpGrav+baseImpExe+montoIce+montoiva >= 1000 AND tipotransac != 8   THEN '02'
                ELSE ''
            END     as formaPag,
			case estabRetencion1 when 0 then null else LPAD(CAST(estabRetencion1 AS CHAR(3)),3,'0') end as estabRetencion1,
			case puntoEmiRetencion1 when 0 then null else LPAD(CAST(puntoEmiRetencion1 AS CHAR(3)),3,'0') end as ptoEmiRetencion1,
			case secRetencion1 when 0 then null else secRetencion1 end as secRetencion1,
                        case autRetencion1 when 0 then null else autRetencion1 end as autRetencion1,
			case estabRetencion1 when 0 then null else DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') end AS fechaEmiRet1, 
			case docModificado when 0 then null else docModificado end as docModificado,
			case estabModificado when 0 then null else LPAD(CAST(estabModificado AS CHAR(3)),3,'0') end as estabModificado,
			case ptoEmiModificado when 0 then null else LPAD(CAST(ptoEmiModificado AS CHAR(3)),3,'0') end as ptoEmiModificado,
                        case secModificado when 0 then null else secModificado end as secModificado,
                        case autModificado when 0 then null else autModificado end as autModificado,
			NULL AS tipoComprobanteReemb,
			NULL AS tpIdProvReemb,
			NULL AS idProvReemb,
			NULL AS establecimientoReemb,
			NULL AS puntoEmisionReemb,
			NULL AS secuencialReemb,
			NULL AS fechaEmisionReemb,
			NULL AS autorizacionReemb,
			NULL AS baseImponibleReemb,
			NULL AS baseImpGravReemb,
			NULL AS baseNoGraIvaReemb,
                        NULL AS baseImpExeReemb,
			NULL AS montoIceReemb,
			NULL AS montoIvaRemb
                        FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genparametros pid  ON pid.par_clave = 'TIPID' AND pid.par_secuencia = per_tipoID
			LEFT JOIN fistablassri tid   ON tid.tab_codtabla = '2-2' AND tid.tab_codigo = tipotransac AND tid.tab_txtData  = pid.par_valor2
			LEFT JOIN fistablassri ice   ON ice.tab_codtabla = 6 AND ice.tab_codigo = porcentajeIce
			LEFT JOIN genvariables var   ON var.iab_objetoId = pro.per_codauxiliar AND var.iab_variableid = 14
			WHERE tipoTransac IN(1,8) " .
            (strlen($pQry) > 0 ? " AND " . $pQry : "") . '
		ORDER BY ID ';

    $rs1 = fSQL($db, $alSql);

    //		GENERACION DE LISTA DE RETENCIONES APLICADAS EN COMPRAS
    $slSql2 = "SELECT ID
			,1 AS secuen
			,codretair AS codigoapp
			,air.tab_txtData  AS codRetAir
			,baseImpAir
			,cast(porcentajeAir as decimal(14,2)) as porcentajeAir
			,valRetAir
                        ,case air.tab_txtData WHEN '338' THEN numCajBan
					WHEN '340' THEN numCajBan
					WHEN '341' THEN numCajBan
					WHEN '342' THEN numCajBan
					WHEN '327' THEN numCajBan
					WHEN '330' THEN numCajBan
					WHEN '504' THEN numCajBan else null end as numCajBan
                        ,case air.tab_txtData WHEN '338' THEN cast(precCajBan as decimal(14,2))
					WHEN '340' THEN cast(precCajBan as decimal(14,2))
					WHEN '341' THEN cast(precCajBan as decimal(14,2))
					WHEN '342' THEN cast(precCajBan as decimal(14,2))
					WHEN '327' THEN cast(precCajBan as decimal(14,2))
					WHEN '330' THEN cast(precCajBan as decimal(14,2))
					WHEN '504' THEN cast(precCajBan as decimal(14,2)) else null end as precCajBan
		FROM fiscompras
			LEFT JOIN fistablassri air   ON air.tab_codtabla = 10 AND air.tab_codigo = codRetAir
		WHERE LENGTH(codretair) > 2 " .
            (strlen($pQry) > 0 ? " AND " . $pQry : "") . "
	UNION
		SELECT id
			,2 AS secuen
			,codretair AS codigoapp
			,air.tab_txtData  AS codRetAir
			,baseimpair2
			,cast(porcentajeAir2 as decimal(14,2)) as porcentajeAir2
			,valretair2
                        ,case air.tab_txtData WHEN '338' THEN numCajBan2
					WHEN '340' THEN numCajBan2
					WHEN '341' THEN numCajBan2
					WHEN '342' THEN numCajBan2
					WHEN '327' THEN numCajBan2
					WHEN '330' THEN numCajBan2
					WHEN '504' THEN numCajBan2 else null end as numCajBan2
                        ,case air.tab_txtData WHEN '338' THEN cast(precCajBan2 as decimal(14,2))
					WHEN '340' THEN cast(precCajBan2 as decimal(14,2))
					WHEN '341' THEN cast(precCajBan2 as decimal(14,2))
					WHEN '342' THEN cast(precCajBan2 as decimal(14,2))
					WHEN '327' THEN cast(precCajBan2 as decimal(14,2))
					WHEN '330' THEN cast(precCajBan2 as decimal(14,2))
					WHEN '504' THEN cast(precCajBan2 as decimal(14,2)) else null end as precCajBan2
		FROM fiscompras
			LEFT JOIN fistablassri air   ON air.tab_codtabla = 10 AND air.tab_codigo = codRetAir2
		
		WHERE LENGTH(codretair2) > 2 " .
            (strlen($pQry) > 0 ? " AND " . $pQry : "") . "
	UNION
		SELECT ID
			,3 AS secuen
			,codretair AS codigoapp
			,air.tab_txtData  AS codretair
			,baseimpair3
			,cast(porcentajeAir3 as decimal(14,2)) as porcentajeAir3
			,valretair3
                        ,case air.tab_txtData WHEN '338' THEN numCajBan3
					WHEN '340' THEN numCajBan3
					WHEN '341' THEN numCajBan3
					WHEN '342' THEN numCajBan3
					WHEN '327' THEN numCajBan3
					WHEN '330' THEN numCajBan3
					WHEN '504' THEN numCajBan3 else null end as numCajBan3
                        ,case air.tab_txtData WHEN '338' THEN cast(precCajBan3 as decimal(14,2))
					WHEN '340' THEN cast(precCajBan3 as decimal(14,2))
					WHEN '341' THEN cast(precCajBan3 as decimal(14,2))
					WHEN '342' THEN cast(precCajBan3 as decimal(14,2))
					WHEN '327' THEN cast(precCajBan3 as decimal(14,2))
					WHEN '330' THEN cast(precCajBan3 as decimal(14,2))
					WHEN '504' THEN cast(precCajBan3 as decimal(14,2)) else null end as precCajBan3
		FROM fiscompras
			LEFT JOIN fistablassri air   ON air.tab_codtabla = 10 AND air.tab_codigo = codRetAir3
		WHERE LENGTH(codretair3) > 2 " .
            (strlen($pQry) > 0 ? " AND " . $pQry : "") . "
	ORDER BY 1,2
		";

    $rs2 = fSQL($db, $slSql2);

    $ar['det'] = $rs1;
    $ar['air'] = $rs2;

    return $ar;
}

/**
 *   Definicion de Query que selecciona los datos deVentas a presentar
 *   access  public
 *   @param   object   Refrencia a la conexxion de BD
 *   @param  string    Condicion de busqueda
 *   @return object    Referencia del Recordset.
 * 	@rev	fah	2015-03-30		Aplicar proceso a los comprobantes cuyo libro contable esté marcado ATS2 (genparametros[CLIBRO].valor4=ATS2). independiemte del tipocomp
 */
function &fDefineVentas(&$db, $pQry = false) {
    /*
     *            establecimiento, puntoEmision, secuencial, autorizacion,
     * 			DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') AS fechaCaducidad,
     */
    global $agEstr;
    $alSql = "SELECT 
		tipoTransac
		,tid.tab_txtdata2               AS tpIdCliente
		,per_Ruc 			AS idCliente
		,if(tipoComprobante < 9 , concat('0', tipoComprobante), tipoComprobante) 
                                                AS tipoComprobante
		,COUNT(DISTINCT id) 		AS numeroComprobantes
		,SUM(baseNoGraIva) 		AS baseNoGraIva
		,SUM(baseImponible)             AS baseImponible
		,SUM(baseImpGrav)               AS baseImpGrav
		,SUM(montoIva) 			AS  montoIva
		,SUM(IFNULL(valorRetBienes,0) + IFNULL(valorRetServicios,0) + IFNULL(valRetServ100,0)) 
                                                AS valorRetIva
		,SUM(valRetAir + valRetAir2 + valretAir3) 		
                                                AS valorRetRenta
        	FROM fiscompras
		LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
		LEFT JOIN genparametros pid  ON pid.par_clave = 'TIPID' AND pid.par_secuencia = per_tipoID
		LEFT JOIN fistablassri tid   ON tid.tab_codtabla = '2-2' AND tid.tab_codigo = tipoTransac AND tid.tab_txtData  = pid.par_valor2
			
		WHERE tipoTransac = 2 " .
            (strlen($pQry) > 0 ? " AND " . $pQry : "") . "
	GROUP BY 1,2,3,4 
	";

    /* NUEVO QUERY PARA VENTAS------------ */
    //esl 13/11/12   Parametrizar tipo de comprobante para ventas (Asisbane-Wacho Neira) Parametros Generales -> Contabilidad -> DIMM -> Tipos de Comprobante clave:DIMCOM sec:1
    //fah 11/11/14	El tipo de identifiacion se asocia con genparametros, ya no con fistablas
    $flTasaIva = .12;
    //$alSql .=" UNION      ------------------- NO ESAR FISCOMPRAS
    $alSql = "	SELECT	tid.tab_txtdata2  AS tpIdCliente,
			per_Ruc AS idCliente,
                        pro.per_parterelacionada AS parteRelVtas,                        
			IFNULL((SELECT par_valor1 FROM genparametros WHERE par_clave = 'DIMCOM' AND par_secuencia = 1), 2) AS tipoComprobante,
			COUNT(DISTINCT com.com_numcomp) 		AS numeroComprobantes,
			ROUND(SUM(CASE act_IvaFlag WHEN 0 THEN det_ValTotal ELSE 0 END),2) 		AS baseNoGraIva,
			ROUND(SUM(CASE act_IvaFlag WHEN 1 THEN  det_ValTotal ELSE 0 END),2) 		AS baseImponible,
			ROUND(SUM(CASE act_IvaFlag IN (2,3) WHEN TRUE THEN det_ValTotal  ELSE 0 END),2) 		AS baseImpGrav,
			ROUND(SUM(IFNULL(det_ValTotal* iva.par_valor2 * $flTasaIva,0) ) , 2) 	AS montoIva,
                        CAST(0 as decimal(14,2)) as montoIce,
			IFNULL(SUM(CASE WHEN (inv.det_Secuencia = (SELECT MAX(det_Secuencia) FROM invdetalle WHERE det_RegNUmero = inv.det_regnumero)) THEN rete.valorRetIva ELSE 0 END),0) AS valorRetIva,
			IFNULL(SUM(CASE WHEN (inv.det_Secuencia = (SELECT MAX(det_Secuencia) FROM invdetalle WHERE det_RegNUmero = inv.det_regnumero)) THEN rete.valorRetRenta ELSE 0 END),0) AS valorRetRenta
	FROM concomprobantes com
		JOIN invdetalle inv ON inv.det_RegNUmero = com.com_RegNumero
		JOIN conactivos act ON act.act_CodAuxiliar = inv.det_CodItem
		JOIN genparametros lib ON lib.par_Clave = 'CLIBRO' AND lib.par_Valor4 = 'ATS2' AND  lib.par_secuencia  = com_libro
		LEFT JOIN genparametros iva ON iva.par_clave = 'CTIVA' AND iva.par_secuencia = act_IvaFlag
		LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = com_CodReceptor
		
		LEFT JOIN genparametros pid  ON pid.par_clave = 'TIPID' AND pid.par_secuencia = per_tipoID
		LEFT JOIN fistablassri tid   ON tid.tab_codtabla = '2-2' AND tid.tab_codigo = 2 AND tid.tab_txtData  = pid.par_valor2
			

		LEFT JOIN v_retencionVTA rete ON com.com_NumComp = rete.documento AND com.com_CodReceptor = rete.auxiliar
	WHERE  " . // #fah2015-03-30  com.com_TipoComp = 'FA'  AND
            //LEFT JOIN fistablassri tid   ON tab_codtabla = 1 AND tab_codigo = '2' AND tab_txtData  = per_tipoID

            $condicion = str_replace("fechaRegistro", "com.com_FecContab", $pQry);
    if (strlen($pQry) > 0)
        $alSql .= " AND " . $condicion;
    $alSql .= " GROUP BY 1,2,3,4 ";
//    $rs1= fSQL($db, $alSql);
    $rs1 = $db->execute($alSql);

////$aDat = $rs1->GetArray();
////echo "<br><br>DATOS DE VENTAS: " ; print_r($aDat);
////echo  "<br>"  . $alSql;

    /*
      "SELECT com_RegNumero AS ID,
      @a:=CONCAT(REPEAT('0', " . $agEstr['LONG']['INI']. "  - LENGTH(com_numcomp)), com_numcomp)  AS a,
      MID(@a, ". $agEstr['ESTAB']['ini']. "," . $agEstr['ESTAB']['fin'] . " ) ESTABLE,
      MID(@a, ". $agEstr['PTOEM']['ini']. "," . $agEstr['PTOEM']['fin'] . " ) ESTABLE,
      MID(@a, ". $agEstr['SECUE']['ini']. "," . $agEstr['SECUE']['fin'] . " ) ESTABLE,

      ";
     */
    $slSql2 = "
	SELECT 	
			MID(LPAD(CAST(com_numcomp AS CHAR(12)),12,'0'), 1, 3)  AS codEstab
			,ROUND(SUM(det_valtotal),2) AS ventasEstab
	FROM concomprobantes com
		JOIN genparametros lib ON lib.par_Clave = 'CLIBRO' AND lib.par_Valor4 = 'ATS2' AND  lib.par_secuencia  = com_libro
		JOIN invdetalle inv ON inv.det_RegNUmero = com.com_RegNumero
		JOIN conactivos act ON act.act_CodAuxiliar = inv.det_CodItem
	WHERE " . #fah2015-03-30   	com.com_TipoComp = 'FA'  AND
            $condicion .
            " GROUP BY 1 ";

    $rs2 = fSQL($db, $slSql2);

    $slSql3 = "
	SELECT 	ROUND(SUM(det_valtotal),2) AS ventasTotal
	FROM concomprobantes com
		JOIN genparametros lib ON lib.par_Clave = 'CLIBRO' AND lib.par_Valor4 = 'ATS2' AND  lib.par_secuencia  = com_libro
		JOIN invdetalle inv ON inv.det_RegNUmero = com.com_RegNumero
		JOIN conactivos act ON act.act_CodAuxiliar = inv.det_CodItem
	WHERE  " . #fah2015-03-30    com.com_TipoComp = 'FA'
            $condicion .
            " ";

    $ar['tot'] = fSQL($db, $slSql3);

    $ar['det'] = $rs1;
    $ar['res'] = $rs2;
//print_r($ar['det']->getArray());	
    return $ar;
}

/**
 *   Definicion de Query que selecciona los datos de Exportaciones a presentar
 *   access  public
 *   @param   object   Refrencia a la conexxion de BD
 *   @param  string    Condicion de busqueda
 *   @return object    Referencia del Recordset.  fah04/02/08  per_subcategoia enlugar de par_valor1
 *   @rev	fah	2015-03-30		Aplicar proceso a los comprobantes cuyo libro contable esté marcado ATS4 (genparametros[CLIBRO].valor4=ATS4)
 */
function &fDefineExport(&$db, $pQry = false) {
    global $agEstr;
    $alSql = "
	SELECT ID,
		concat('0', `tipImpExp`) AS exportacionDe
		,concat('0', tipoComprobante) as tipoComprobante
		,distAduanero
		,anio
		,regimen
		,correlativo
		,verificador
		,documEmbarque AS docTransp
		,DATE_FORMAT(fechaEmbarque ,'%d/%m/%Y') AS fechaEmbarque
		,'' AS fue
		,establecimiento
		,puntoEmision
		,secuencial
		,autorizacion
		,DATE_FORMAT(fechaEmision,'%d/%m/%Y') AS fechaEmision
		,valorciffob AS valorFOB
		,valorFOBComprobante
	FROM fiscompras
		LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
		LEFT JOIN genautsri   aut    ON aut.aut_IDauxiliar = per_codauxiliar AND aut.aut_ID = autorizacion
		LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
		LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
		WHERE tipoTransac = 4 " .
            (strlen($pQry) > 0 ? " AND " . $pQry : "")
    ;

    //ifnull(com_Establecimie, '001')	AS establecimiento,			
    //ifnull(com_ptoEmision, '001') 	AS puntoEmision,			
    //com_numcomp 			AS secuencial,			
    //
		
	/* NUEVO QUERY PARA EXPORTACION------------ */
    $gfTasaIva = .12;            // @TODO     PARAMETRIZAR!!!!!!
    $alSql = "SELECT tid.tab_txtdata2  AS tpIdClienteEx,
                    per_Ruc AS idClienteEx,
                    pro.per_parterelacionada AS parteRelExp,
                    (select y.pai_codSRI from liqembarques x inner join genpaises y
                                            on x.emb_codpais = y.pai_CodPais
                                            where x.emb_RefOperativa = com_embarque) AS paisEfecExp,
                    (select y.pai_fisimpmenor from liqembarques x inner join genpaises y
                                            on x.emb_codpais = y.pai_CodPais
                                            where x.emb_RefOperativa = com_embarque) AS pagoRegFis,
                    com_RegNumero as ID,
			CASE IFNULL(CONCAT(dau_distAduanero,dau_anio,dau_regimen,dau_correlativo,dau_verificador),' ')
				WHEN ' '  THEN '02'  /*Sin Refrendo*/
				WHEN NULL THEN '02'
				ELSE '01' /*Con Refrendo*/
			END                 AS exportacionDe,
			'01'                AS tipoComprobante /*Factura*/,
			dau_distAduanero    AS distAduanero,
			dau_anio            AS anio,
			dau_regimen         AS regimen,	
			dau_correlativo     AS correlativo,
			dau_verificador     AS verificador,
			dau_ordenemb        AS docTransp,
			DATE_FORMAT(IFnull(dau_zarpe, com_feccontab),'%d/%m/%Y') AS fechaEmbarque,
			dau_numerodau       AS fue,
                        ROUND(SUM(CASE act_IvaFlag WHEN 3 THEN ROUND(det_ValTotal / (1+ $gfTasaIva),2) ELSE det_ValTotal END),2)
                                            AS valorFOB,	
			ROUND(SUM(CASE act_IvaFlag WHEN 3 THEN ROUND(det_ValTotal / (1+ $gfTasaIva),2) ELSE det_ValTotal END),2)
                                            AS valorFOBComprobante,	
                        MID(LPAD(CAST(com_numcomp AS CHAR(12)),12,'0'), 1, 3)                   AS establecimiento,
                        MID(LPAD(CAST(com_numcomp AS CHAR(12)),12,'0'), 4, 3)                   AS puntoEmision,
                        CONCAT('000', MID(LPAD(CAST(com_numcomp AS CHAR(12)),12,'0'), 7, 6))    AS secuencial,
			ifnull(aut_id,'00000')                                                  AS autorizacion,
			DATE_FORMAT(com_Feccontab,'%d/%m/%Y')                                   AS fechaEmision			
	FROM concomprobantes  
	JOIN genparametros lib ON lib.par_Clave = 'CLIBRO' AND lib.par_Valor4 = 'ATS4' AND  lib.par_secuencia  = com_libro
	JOIN invdetalle inv ON inv.det_RegNUmero = com_RegNumero
	JOIN conactivos act ON act.act_CodAuxiliar = inv.det_CodItem
	LEFT JOIN fisdaus ON dau_regNumero = com_regnumero
	LEFT JOIN genautsri a ON  a.`aut_IdAuxiliar` = -99 AND a.`aut_TipoDocum` = 18
		AND aut_establecim      = com_establecimie
		AND aut_ptoemision      = com_ptoemision
		AND a.aut_NroInicial    <=com_numcomp
		AND a.aut_NroFinal      >=com_numcomp
        LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = com_CodReceptor
        LEFT JOIN genparametros pid  ON pid.par_clave = 'TIPID' AND pid.par_secuencia = per_tipoID
        LEFT JOIN fistablassri tid   ON tid.tab_codtabla = '2-2' AND tid.tab_codigo = 2 AND tid.tab_txtData  = pid.par_valor2                
	WHERE " . str_replace("fechaRegistro", "com_FecContab", $pQry) . "
        GROUP bY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,19,20,21,22,23
        HAVING ROUND(SUM(CASE act_IvaFlag WHEN 3 THEN det_ValTotal / (1+ $gfTasaIva) ELSE det_ValTotal END),2) <> 0
";


//	

// echo $alSql;
    $rs = fSQL($db, $alSql);
    return $rs;
}

/**
 *   Definicion de Query que selecciona los datos de Recap a presentar
 *   access  public
 *   @param   object   Refrencia a la conexxion de BD
 *   @param  string    Condicion de busqueda
 *   @return object    Referencia del Recordset.
 */
function &fDefineRecap(&$db, $pQry = false) {
    $alSql = "SELECT tipoTransac, codSustento, tid.par_Valor1 AS tpIdProv, per_Ruc as idProv,
			concat('000', tipoComprobante) as tipoComprobante,
			DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
			establecimiento, puntoEmision, secuencial, autorizacion,
			DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') AS fechaCaducidad,
			baseImponible, baseImpGrav,porcentajeIva, montoIva,
			baseImpIce, porcentajeIce, montoIce, montoIvaBienes, porRetBienes, valorRetBienes,
			montoIvaServicios, porRetServicios, valorRetServicios,
			ai1.tab_txtData as codRetAir, baseImpAir, porcentajeAir, valRetAir,
			ai2.tab_txtData as codRetAir2, baseImpAir2, porcentajeAir2, valRetAir2,
			ai3.tab_txtData as codRetAir3, baseImpAir3, porcentajeAir3, valRetAir3,
			estabRetencion1, puntoEmiRetencion1 AS ptoEmiRetencion1, autRetencion1,
			DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS fechaEmiRet1, docModificado,
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
			WHERE tipoTransac = 5 ";
    if (strlen($pQry) > 0)
        $alSql .= " AND " . $pQry;
//    WHERE " . $pQry . " ";
// echo $alSql;
    $rs = fSQL($db, $alSql);
    return $rs;
}

/**
 *   Definicion de Query que selecciona los datos de Fideicomisos a presentar
 *   access  public
 *   @param   object   Refrencia a la conexxion de BD
 *   @param  string    Condicion de busqueda
 *   @return object    Referencia del Recordset.
 */
function &fDefineFideicom(&$db, $pQry = false) {
    $ilNumProceso = fGetParam('pro_ID', 0);
    $alSql = "SELECT tipoTransac, codSustento, tid.par_Valor1 AS tpIdProv, per_Ruc as idProv,
			concat('000', tipoComprobante) as tipoComprobante,
			DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
			establecimiento, puntoEmision, secuencial, autorizacion,
			DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') AS fechaCaducidad,
			baseImponible, baseImpGrav,porcentajeIva, montoIva,
			baseImpIce, porcentajeIce, montoIce, montoIvaBienes, porRetBienes, valorRetBienes,
			montoIvaServicios, porRetServicios, valorRetServicios,
			ai1.tab_txtData as codRetAir, codRetAir, baseImpAir, porcentajeAir, valRetAir,
			ai2.tab_txtData as codRetAir2, baseImpAir2, porcentajeAir2, valRetAir2,
			ai3.tab_txtData as codRetAir3, baseImpAir3, porcentajeAir3, valRetAir3,
			estabRetencion1, puntoEmiRetencion1 AS ptoEmiRetencion1, autRetencion1,
			DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS fechaEmiRet1, docModificado,
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
			WHERE tipoTransac = 7 ";
    if (strlen($pQry) > 0)
        $alSql .= " AND " . $pQry;
//    WHERE " . $pQry . " ";
    // echo $alSql;
    $rs = fSQL($db, $alSql);
    return $rs;
}

/**
 *   Definicion de Query que selecciona los datos de Anulaciones a presentar
 *   access  public
 *   @param   object   Refrencia a la conexxion de BD
 *   @param  string    Condicion de busqueda
 *   @return object    Referencia del Recordset.
 */
function &fDefineAnulados(&$db, $pQry = false) {
    $ilNumProceso = fGetParam('pro_ID', 0);
    $alSql = "SELECT * FROM (
        SELECT right(concat('0', tipoComprobante),2) AS  tipoComprobante,
			establecimiento,
            puntoEmision,
            secuencialInicio,
			secuencialFin,
            autorizacion,
            fechaAnulacion as fechaAnulacion,
            fechaAnulacion AS fechaRegistro
	FROM fisanulados
    ) tmp00
    WHERE true " .
            (strlen($pQry) > 0 ? " AND " . $pQry : "") .
            "
    ORDER BY fechaAnulacion, tipoComprobante, establecimiento, puntoEmision, secuencialInicio
    ";
    // echo $alSql;
    $rs = fSQL($db, $alSql);
    return $rs;
}

/**
 *   Definicion de Query que selecciona los datos de Rendimientos Financieros a presentar
 *   access  public
 *   @param   object   Refrencia a la conexxion de BD
 *   @param  string    Condicion de busqueda
 *   @return object    Referencia del Recordset.
 */
function &fDefineRendFin(&$db, $pQry = false) {
    $ilNumProceso = fGetParam('pro_ID', 0);
    $alSql = "SELECT tipoTransac,
		codSustento,
		tid.par_Valor1 AS tpIdProv,
		per_Ruc as idProv,
			concat('000', tipoComprobante) as tipoComprobante,
			DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
			establecimiento,
			puntoEmision,
			secuencial,
			autorizacion,
																-- DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') AS fechaCaducidad,
			baseImponible,
			baseImpGrav,
																-- porcentajeIva,
			montoIce,
			montoIva,
			valorRetBienes,
			valorRetServicios,
																-- baseImpIce,
																-- porcentajeIce,
																-- 
																-- montoIvaBienes,
																-- porRetBienes,
			
																--	montoIvaServicios,
																-- porRetServicios,
			
			ai1.tab_txtData as codRetAir,
			baseImpAir,
			porcentajeAir,
			valRetAir,
			ai2.tab_txtData as codRetAir2,
			baseImpAir2,
			porcentajeAir2,
			valRetAir2,
			ai3.tab_txtData as codRetAir3,
			baseImpAir3,
			porcentajeAir3,
			valRetAir3,
			estabRetencion1,
			puntoEmiRetencion1 AS ptoEmiRetencion1,
			autRetencion1,
			DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS fechaEmiRet1, docModificado,
			DATE_FORMAT(fechaEmiModificado ,'%d/%m/%Y') AS fechaEmiModificado,
			estabModificado,
			ptoEmiModificado,
			secModificado,
			autModificado,
			contratoPartidoPolitico,
			montoTituloOneroso, 
			ontoTituloGratuito
			
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_IDauxiliar = per_codauxiliar AND aut.aut_ID = autorizacion
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
			WHERE tipoTransac = 6 ";
    if (strlen($pQry) > 0)
        $alSql .= " AND " . $pQry;
//    WHERE " . $pQry . " ";
    // echo $alSql;
    $rs = fSQL($db, $alSql);
    return $rs;
}

/*
 *   Recorre el registro de datos para la primera parte de compras, hasta estabRetencion1
 *   @param  $alRec      referencia al Recordset dedatos
 *   @param  $alCam      referencia al Recordset de estructura XML
 *   @param  $pLimite 	id del elemento al que debe retornar el apuntador
 */

function fProcesaDatosComp1($alRec, $alCam, $pLimite = false, $pTrId = ' -') {
    global $detair, $airFl, $air;
    $detair = false;
    $air = false;
    $airFl = false;
    $olRet = false;
    reset($alCam);
    foreach ($alCam as $olDato) {
        if ($olDato["xml_campo"] == $pLimite)
            return false;
        if (!array_key_exists($olDato["xml_campo"], $alRec)) {
            echo " NO EXISTE EL CAMPO " . $olDato["xml_campo"] . " = " . $olDato["xml_campo"] . " <br>";
        } else {
            $olNodo = fProcesaCampo($alRec, $olDato, true, $pTrId);
        }
    }
    return $olRet;
}

/*
 *   Recorre el registro de datos para generar cada campo en formato XML
 *   @param  $alRec      referencia al Recordset dedatos
 *   @param  $alCam      referencia al Recordset de estructura XML
 *   @param  $pApunta 	id del elemento al que debe retornar el apuntador
 */

function fProcesaDatos($alRec, $alCam, $pApunta = false, $pTrId = ' -') {
    global $detair, $airFl, $air;
    $detair = false;
    $air = false;
    $airFl = false;
    $olRet = false;
    reset($alCam);
    foreach ($alCam as $olDato) {
        //echo "<br>" . $olDato["xml_campo"];  //dbg		
        if (!array_key_exists($olDato["xml_campo"], $alRec)) {
            echo " NO EXISTE EL CAMPO " . $olDato["xml_campo"] . " = " . $olDato["xml_campo"] . " <br>";
        } else {
            $olNodo = fProcesaCampo($alRec, $olDato, true, $pTrId);
        }
        if ($olDato["xml_campo"] == $pApunta)
            $olRet = $olNodo;
    }
    return $olRet;
}

/*
 *   Analiza cada campo de datos frente ala estructura asociada y genera el objeto XML
 *   @param  $alRec      referencia al Recordset de datos
 *   @param  $olDato      referencia al Objeto que define la estructura del campo
 *   @param  $pRetorna 	boolean  debe retornar el apuntador
 *   @param  $pidTr      Id para transaccion
 */

function fProcesaCampo($alRec, $olDato, $pRetorna = false, $pIdTr = ' - ') {
    global $det, $air, $doc, $airFl, $detco, $detair;
    $slK = $olDato["xml_campo"];
    $slV = $alRec[$slK];
    $slK1 = $slK;
    $slSuf = "";
    $idx = strpos($slK, "Air");
    if ($idx > 0) {
        if (strlen($slK) > $idx + 3) {
            $slK1 = substr($slK, 0, strlen($slK) - 1);
            $slSuf = substr($slK, strlen($slK) - 1);
        }
    }
    //echo "<br>$slK => $slV   ---  " . $olDato['xml_formato'] . " ///";
    switch ($olDato["xml_formato"]) {
        case ('N'):
        case ('n'):
        case ('D'):
        case ('d'):
        case ('E'):
            $ilEnteros = NZ($olDato["xml_longMax"]) - NZ($olDato["xml_numDecim"]);
            $slV = is_numeric($slV) ? fNumFormateado($slV, $ilEnteros, $olDato["xml_numDecim"], $olDato["xml_longMin"]) : "";  // #fah29/07/2013
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
                    or is_null($slV) or strlen($slV) < 1
                    or str2date($slV, "dmy") < str2date("2001/01/01", "ymd")
                    or ! date($slFmt, str2date($slV, "dmy"))) {
                if ($olDato["xml_requerido"] == "ob") {
                    echo "<br> La Transaccion " . $alRec["ID"] . " Tipo: " . $alRec["tipoComprobante"] .
                    " Comp. Numero: " . $alRec["secuencial"] . " tiene el campo " . $slK .
                    " un valor invalido " . $slV;
                    $slV = "00/00/0000";
                } else {
                    $slV = "";
                }
            } else {
                $slV = date($slFmt, str2date($slV, "dmy"));
            }
            break;
        case ('C'):
        case ('c'):
            break;
        default:
            break;
    }
//echo " SALE $slV  #";
    if ($olDato['xml_requerido'] == 'OB' ||
            $olDato['xml_longMin'] > strlen($slV) ||
            $olDato['xml_longMax'] < strlen($slV)
    ) {
        echo "<br><span style='font-size:12px'> Error de dato ó longitud de dato '" . $olDato['xml_descripcion'] . "' [" . $slV . "] en transaccion " . $pIdTr . "</span>";        
    }
    if ($olDato['xml_requerido'] != 'OB' && strlen($slV) < 1) {
        return null;
    } // no crear elementos opcionales ó condicionales
    $olNodo = fAgregarElemTxt($det, $slK, $slV);
    if ($pRetorna) {
        return $olNodo;
    }
    return null;
}

/**
 */
function fAgregarElemPar(&$pCont, $pNom, $pVal) {
    global $doc, $db;
    $slVal = fDBValor($db, 'genparametros', 'par_Descripcion', "par_clave = '$pVal'");
    fAgregarElemTxt($pCont, $pNom, $slVal);
}

/**
 */
function fAgregarElemTxt(&$pCont, $pNom, $pVal) {
    global $doc, $db;
//	echo "$pNom -- $pVal <br>";
    if (strlen($pVal) >= 1) {
        $outer = $doc->createElement($pNom);
        $outer = $pCont->appendChild($outer);
        $valor = $doc->createTextNode($pVal);
        $valor = $outer->appendChild($valor);
        return $outer;
    }
    return false;
}

/**
 */
function fNumFormateado($pVal, $pEnt, $pDec, $pLMin = false) {
    $alFmtoEnt = array();
    $alFmtoDec = array();
    $alLong = 1;
    $pVal = number_format($pVal, $pDec, ".", "");
    for ($i = 1; $i <= $pDec; $i++) {
        if (($alLong <= $pLMin))
            $alFmtoDec[] = "0";
        else
            $alFmtoDec[] = "#";
        ++$alLong;
    }
    for ($i = 1; $i <= $pEnt; $i++) {
        if ($alLong <= $pLMin)
            $alFmtoEnt[] = "0";
        else
            $alFmtoEnt[] = "#";
        ++$alLong;
    }
    $slNumF = CCFormatNumber($pVal + 0, Array(True, $pDec, ".", "", False, $alFmtoEnt, $alFmtoDec, 1, True, ""));
    // echo "<br>E:$pEnt  ---  D: $pDec    ----  M: $pLMin // VAL: $pVal // NUMFORM: $slNumF";  // dbg
    if ($pLMin > 0 AND strlen($slNumF) < $pLMin) {
        $slNumF = str_repeat('0', $oLMin - $slNumF) . $slNumF;
    }
    return $slNumF;
}

//--------------------------------------------------------------------------------------------------------------------
//                          Procesamiento
//--------------------------------------------------------------------------------------------------------------------
//
ob_start();
$det = NULL;
$air = false;
$doc = NULL;
$detco = NULL;
$detair = NULL;
$airFl = false;
$doc = new DomDocument('1.0', 'UTF-8');
$root = $doc->createElement('iva');
$root = $doc->appendChild($root);
$db = NewADOConnection(DBTYPE);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->Connect(DBSRVR, DBUSER, DBPASS, DBNAME) or die("<BR> <BR> No hay acceso al servidor de BD");
$db->debug = fGetParam('pAdoDbg', 0);
$pAnio = fGetParam('s_Anio', false);
$pMes = fGetParam('s_Mes', false);
if (!$pAnio || !$pMes)
    echo "DEBE ELEGIR UN AÑO Y MES PARA PROCESAR";
echo "AÑO DE PROCESO: " . $pAnio . "<br>";
echo "MES DE PROCESO: " . $pMes . "<br>";
$agEstr = $db->getAssoc("SELECT  par_Clave, par_valor1 ini, par_valor2 fin
	FROM genparametros  JOIN gencatparam  ON cat_Clave = 'DIMCON' AND cat_codigo = par_categoria
	WHERE par_categoria");
//print_r($agEstr);
$pQry = fGetParam('pQry', false);
$ilNumEst = fDBValor($db, "genparametros", "par_valor1", " par_clave='EGNEST' AND par_secuencia = 11 ");
$totalV = fDBValor($db, "fiscompras", "SUM(basenograiva + baseimponible + baseimpgrav /*+ montoiva*/) AS ventasTotal ", " tipoTransac = 2 AND " . $pQry);
$rsv = fDefineVentas($db, $pQry);

$totalV = $rsv['tot']->fetchObject(false)->ventasTotal;

//print_r($totalV);
echo "<br>ESTABLECIMIENTOS: " . fNumFormateado($ilNumEst, 3, 0, 3);
echo "<br>VENTA TOTAL    :  " . fNumFormateado($totalV, 12, 2, 3) . "<br><br>";

fAgregarElemTxt($root, 'TipoIDInformante', 'R'); // 'EGTID');
fAgregarElemPar($root, 'IdInformante', 'EGRUC');
fAgregarElemPar($root, 'razonSocial', 'EGNOM');
fAgregarElemTxt($root, 'Anio', $pAnio);
fAgregarElemTxt($root, 'Mes', CCFormatNumber($pMes, Array(True, 0, ".", ",", False, array("0", "0"), array(), 1, True, "")));
fAgregarElemTxt($root, 'numEstabRuc', fNumFormateado($ilNumEst, 3, 0, 3));  // Numero de establecimientos

fAgregarElemTxt($root, 'totalVentas', $totalV);
fAgregarElemTxt($root, 'codigoOperativo', 'IVA');

$cmp = $doc->createElement('compras');
$root->appendChild($cmp);

/**
 *   Procesar registros decompras
 * */
echo "<br> PROCESO DE COMPRAS			-------------------------------------------------------------------------------------------------------";
$rsiva10 = $db->GetRow("SELECT tab_codigo FROM fistablassri WHERE tab_codtabla='5A' and tab_porcentaje=10");
$rsiva20 = $db->GetRow("SELECT tab_codigo FROM fistablassri WHERE tab_codtabla='5' and tab_porcentaje=20");
$rsiva30 = $db->GetRow("SELECT tab_codigo FROM fistablassri WHERE tab_codtabla='5A' and tab_porcentaje=30");
$rsiva70 = $db->GetRow("SELECT tab_codigo FROM fistablassri WHERE tab_codtabla='5' and tab_porcentaje=70");
$rsiva100= $db->GetRow("SELECT tab_codigo FROM fistablassri WHERE tab_codtabla='5' and tab_porcentaje=100");

$agCompras = fDefineCompras($db, $pQry);
$slSql = "SELECT
		`xml_campo`
		, `xml_descripcion`
		, `xml_longMin`
		, `xml_longMax`
		, `xml_tipoDato`
		, `xml_tablaValida`
		, `xml_formato`
		, `xml_numDecim`
		, `xml_requerido`
		, `xml_default`
	   FROM fis_xmlestruct WHERE xml_version = 2015  AND xml_tiporeg = 'COM'  
	  ORDER BY xml_Secuencia";
$rsEst = $db->execute($slSql);
$alCam = $rsEst->GetArray();
$slEstAir = "SELECT 
		`xml_campo`
		, `xml_descripcion`
		, `xml_longMin`
		, `xml_longMax`
		, `xml_tipoDato`
		, `xml_tablaValida`
		, `xml_formato`
		, `xml_numDecim`
		, `xml_requerido`
		, `xml_default`
        ,xml_secuencia
	   FROM fis_xmlestruct WHERE xml_version = 2015  AND xml_tiporeg = 'COM-AIR'  
	  ORDER BY xml_Secuencia";

$rsEstAir = $db->execute($slEstAir);
$alEstAir = $rsEstAir->GetArray();
//echo "<br>getarray estair  ";  print_r($alEstAir);
//echo "<br>getarray  "; print_r($rsEstAir->GetArray());
//echo "<br>getassoc"; print_r($alEstAir);
$ilCount = 0;
$agCompras['air']->moveFirst();

if ($agCompras['det']) {
    while ($alRec = $agCompras['det']->FetchRow()) {
        $airFl = false;
        $detco = $doc->createElement('detalleCompras');
        $det = $cmp->appendChild($detco);
        $slTrId = " TIPO: " . $alRec['tipoComprobante'] . " NUM: " . $alRec['establecimiento'] . $alRec['puntoEmision'] . $alRec['secuencial'] .
                " RUC " . $alRec['idProv'];
        fProcesaDatosComp1($alRec, $alCam, "estabRetencion1", $slTrId);  // procesar y devolver nodo
        $alAir = $agCompras['air']->fetchObj();
        //print_r($alAir);

        $detLe = $doc->createElement('pagoExterior');
        $locEx = $det->appendChild($detLe);
        fAgregarElemTxt($locEx, 'pagoLocExt', $alRec["pagoLocExt"]);
        fAgregarElemTxt($locEx, 'paisEfecPago', $alRec["paisEfecPago"]);
        fAgregarElemTxt($locEx, 'aplicConvDobTrib', $alRec["aplicConvDobTrib"]);
        fAgregarElemTxt($locEx, 'pagExtSujRetNorLeg', $alRec["pagExtSujRetNorLeg"]);

        if (strlen($alRec["formaPag"]) > 1) {
            $detFp = $doc->createElement('formasDePago');
            //$locFp   = $det->appendChild($detFp);
            $nodFp = $det->appendChild($detFp);
            fAgregarElemTxt($nodFp, 'formaPago', $alRec["formaPag"]);
        }


        if ($alAir->ID) {
            //		if ($alAir){
            //echo "<br>10  : " . $alRec['ID'] . " - " . 	$alAir->ID;			
            $detco = $doc->createElement('air');
            $detair = $det->appendChild($detco);
            //if ($alRec['ID'] > 	$alAir->ID ) echo " Mayor ";
            //print_r($alAir);
            while ($alRec['ID'] > $alAir->ID) {     // Alcanzar Id de retencion al ID de transacc
                //echo "<br>10-1  : " . $alRec['ID'] . " - " . 	$alAir->ID;							
                $agCompras['air']->moveNext();
                $alAir = $agCompras['air']->fetchObj();
            }
            //echo "<br>11";
            //echo "<br>agAir"; print_r($alAir);
            while ($alRec['ID'] == $alAir->ID) {     // Procesar todas las air de una transacc
                //echo "<br>12";
                $airnode = $doc->createElement('detalleAir');
                $air = $detair->appendChild($airnode);
                fAgregarElemTxt($air, 'codRetAir', $alAir->codRetAir);
                fAgregarElemTxt($air, 'baseImpAir', $alAir->baseImpAir);
                fAgregarElemTxt($air, 'porcentajeAir', $alAir->porcentajeAir);
                fAgregarElemTxt($air, 'valRetAir', $alAir->valRetAir);
                fAgregarElemTxt($air, 'numCajBan', $alAir->numCajBan);
                fAgregarElemTxt($air, 'precCajBan', $alAir->precCajBan);
                $agCompras['air']->moveNext();
                $alAir = $agCompras['air']->fetchObj();
            };
            fAgregarElemTxt($det, 'estabRetencion1', $alRec["estabRetencion1"]);
            fAgregarElemTxt($det, 'ptoEmiRetencion1', $alRec["ptoEmiRetencion1"]);
            fAgregarElemTxt($det, 'secRetencion1', $alRec["secRetencion1"]);
            fAgregarElemTxt($det, 'autRetencion1', $alRec["autRetencion1"]);
            fAgregarElemTxt($det, 'fechaEmiRet1', $alRec["fechaEmiRet1"]);
        }
        fAgregarElemTxt($det, 'docModificado', $alRec["docModificado"]);
        fAgregarElemTxt($det, 'estabModificado', $alRec["estabModificado"]);
        fAgregarElemTxt($det, 'ptoEmiModificado', $alRec["ptoEmiModificado"]);
        fAgregarElemTxt($det, 'secModificado', $alRec["secModificado"]);
        fAgregarElemTxt($det, 'autModificado', $alRec["autModificado"]);
        $ilCount++;
    } // eof While agCompras[det]
}
echo "<br>$ilCount Registros de Compras procesados.";


/**
 *   Procesar registros de Ventas
 * */
echo "<br><br> PROCESO DE VENTAS --------------------------------------------------";
$rsv = fDefineVentas($db, $pQry);
//print_r($rs);
$ilCount = 0;

///  if ($rsv['det']->_numOfRows) {

$cmp = $doc->createElement('ventas');
$root->appendChild($cmp);
$slSql = "SELECT
            `xml_campo`
            , `xml_descripcion`
            , `xml_longMin`
            , `xml_longMax`
            , `xml_tipoDato`
            , `xml_tablaValida`
            , `xml_formato`
            , `xml_numDecim`
            , `xml_requerido`
            , `xml_default`
           FROM fis_xmlestruct WHERE xml_version = 2015  AND xml_tiporeg = 'VEN'  
          ORDER BY xml_Secuencia";
$rsEst = $db->execute($slSql);
$alCam = $rsEst->GetArray();
//print_r($alCam);
//print_r($rsv['det']->getArray());
$rsv['det']->moveFirst();

while ($alRec = $rsv['det']->FetchRow()) {
    $airFl = false;
    $detco = $doc->createElement('detalleVentas');
    $det = $cmp->appendChild($detco);
    $slTrId = " TIPO: " . $alRec['tipoComprobante'] . " cliente: " . $alRec['idCliente'];
    fProcesaDatos($alRec, $alCam, false, $slTrId);
    $ilCount++;
}
echo "<br>$ilCount Registros de detalleVentas. ----  ";

// -------------------------------------------- RESUMEN DE VENTAS ventasEstablecimiento
$ilCount = 0;
$slSql = "SELECT
            `xml_campo`
            , `xml_descripcion`
            , `xml_longMin`
            , `xml_longMax`
            , `xml_tipoDato`
            , `xml_tablaValida`
            , `xml_formato`
            , `xml_numDecim`
            , `xml_requerido`
            , `xml_default`
           FROM fis_xmlestruct WHERE xml_version = 2015  AND xml_tiporeg = 'VEN-EST'  
          ORDER BY xml_Secuencia";
$rsEst = $db->execute($slSql);
$alCam = $rsEst->GetArray();
//print_r($rsv['res']);
//print_r($alCam);
if ($rsv['res']->RecordCount()) {
    $vres = $doc->createElement('ventasEstablecimiento');
    $root->appendChild($vres);

    while ($alRec = $rsv['res']->FetchRow()) {
        $detco = $doc->createElement('ventaEst');
        $det = $vres->appendChild($detco);
        //	print_r($alRec);
        $slTrId = " TIPO: Total por Estab " . $alRec['codEstab'];
        fProcesaDatos($alRec, $alCam);
        $ilCount++;
    }
}
echo "<br>$ilCount Registros de ventasEstablecimiento.<br>";
////}
/////////**
////////*   Procesar registros de Importacionesa
////////**/
////////$cmp = $doc->createElement('importaciones');
////////$root->appendChild($cmp);
////////
////////$rs = fDefineImport($db, $pQry) ;
////////$slSql = "SELECT * from fis_xmlestruct WHERE xml_version = 2013 And  xml_tiporeg = 'IMP'";
////////$rsEst= $db->execute($slSql);
////////$alCam = $rsEst->GetArray();
//////////print_r($alCam);
////////$ilCount=0;
////////echo "<br> PROCESO DE IMPORTACIONES --------------------------------------------";
////////if ($rs)
////////	while ($alRec = $rs->FetchRow()) {
////////	    $airFl = false;
////////		$detco = $doc->createElement('detalleImportaciones');
////////		$det   = $cmp->appendChild($detco);
////////		fProcesaDatos($alRec, $alCam);
////////		$ilCount++;
////////    }
////////echo "<br> $ilCount Registros <br>";
////////
/**
 *   Procesar registros de Exportaciones
 * */
echo "<br> PROCESO DE EXPORTACIONES ----------------------------------------------";
$cmp = $doc->createElement('exportaciones');
$root->appendChild($cmp);

$rs = fDefineExport($db, $pQry);
$slSql = "SELECT
		`xml_campo`
		, `xml_descripcion`
		, `xml_longMin`
		, `xml_longMax`
		, `xml_tipoDato`
		, `xml_tablaValida`
		, `xml_formato`
		, `xml_numDecim`
		, `xml_requerido`
		, `xml_default`
	   FROM fis_xmlestruct WHERE xml_version = 2015  AND xml_tiporeg = 'EXP'  
	  ORDER BY xml_Secuencia";

$rsEst = $db->execute($slSql);
$alCam = $rsEst->GetArray();
//print_r($alCam);
$ilCount = 0;

if ($rs)
    while ($alRec = $rs->FetchRow()) {
        $airFl = false;
        $detco = $doc->createElement('detalleExportaciones');
        $det = $cmp->appendChild($detco);
        // echo "<br>"; print_r($alRec);  //dbg
        $slTrId = " TIPO: " . $alRec['tipoComprobante'] .
                " DOCUM: " . $alRec['establecimiento'] . $alRec['puntoEmision'] . $alRec['secuencial'];
        fProcesaDatos($alRec, $alCam, false, $slTrId);
        $ilCount++;
    }
echo "<br><br> $ilCount Registros<br>";

/////////**
////////*   Procesar registros de Recaps
////////**/
////////$cmp = $doc->createElement('recap');
////////$root->appendChild($cmp);
////////
////////$rs = fDefineRecap($db, $pQry) ;
////////$slSql = "SELECT * from fis_xmlestruct WHERE xml_version = 2013 And  xml_tiporeg = 'REC'";
////////$rsEst= $db->execute($slSql);
////////$alCam = $rsEst->GetArray();
//////////print_r($alCam);
////////$ilCount=0;
////////echo "<br> PROCESO DE RECAPS (TARJ. DE CREDITO) --------------------------------";
////////if ($rs)
////////	while ($alRec = $rs->FetchRow()) {
////////	    $airFl = false;
////////		$detco = $doc->createElement('detalleRecap');
////////		$det   = $cmp->appendChild($detco);
////////		fProcesaDatos($alRec, $alCam);
////////		$ilCount++;
////////    }
////////echo "<br> $ilCount Registros <br>";
////////
////////
/////////**
////////*   Procesar registros de Fideicomisos
////////**/
////////$cmp = $doc->createElement('fideicomisos');
////////$root->appendChild($cmp);
////////
////////$rs = fDefineFideicom($db, $pQry) ;
////////$slSql = "SELECT * from fis_xmlestruct WHERE xml_version = 2013 And  xml_tiporeg = 'FID'";
////////$rsEst= $db->execute($slSql);
////////$alCam = $rsEst->GetArray();
//////////print_r($alCam);
////////$ilCount=0;
////////echo "<br> PROCESO DE FIDEICOMISOS ---------------------------------------------";
////////if ($rs)
////////	while ($alRec = $rs->FetchRow()) {
////////	    $airFl = false;
////////		$detco = $doc->createElement('detalleFideicomisos');
////////		$det   = $cmp->appendChild($detco);
////////		fProcesaDatos($alRec, $alCam);
////////		$ilCount++;
////////    }
////////echo "<br> $ilCount Registros<br>";
////////
/////////**
////////*   Procesar registros de Fideicomisos
////////**/
////////$cmp = $doc->createElement('rendFinancieros');
////////$root->appendChild($cmp);
////////
////////$rs = fDefineRendFin($db, $pQry) ;
////////$slSql = "SELECT * from fis_xmlestruct WHERE xml_version = 2013 And  xml_tiporeg = 'REN'";
////////$rsEst= $db->execute($slSql);
////////$alCam = $rsEst->GetArray();
//////////print_r($alCam);
////////$ilCount=0;
////////echo "<br> PROCESO DE RENDIMIENTOS FINANCIEROS ---------------------------------";
////////if ($rs)
////////	while ($alRec = $rs->FetchRow()) {
////////	    $airFl = false;
////////		$detco = $doc->createElement('detalleRendFinancieros');
////////		$det   = $cmp->appendChild($detco);
////////		fProcesaDatos($alRec, $alCam);
////////		$ilCount++;
////////    }
////////echo "<br> $ilCount Registros<br>";


/**
 *   Procesar registros de Anulaciones
 * */
echo "<br> PROCESO DE ANULACIONES ----------------------------------------------";
$cmp = $doc->createElement('anulados');
$root->appendChild($cmp);

$rs = fDefineAnulados($db, $pQry);
$slSql = "SELECT
		`xml_campo`
		, `xml_descripcion`
		, `xml_longMin`
		, `xml_longMax`
		, `xml_tipoDato`
		, `xml_tablaValida`
		, `xml_formato`
		, `xml_numDecim`
		, `xml_requerido`
		, `xml_default`
	   FROM fis_xmlestruct WHERE xml_version = 2013  AND xml_tiporeg = 'ANU'  
	  ORDER BY xml_Secuencia";

$rsAnu = $db->execute($slSql);
$alCam = $rsAnu->GetArray();
//print_r($alCam);
$ilCount = 0;

if ($rsAnu)
    while ($alRec = $rs->FetchRow()) {
        $detco = $doc->createElement('detalleAnulados');
        $det = $cmp->appendChild($detco);
        // echo "<br>"; print_r($alRec);  //dbg
        fProcesaDatos($alRec, $alCam);
        $ilCount++;
    }
echo "<br> $ilCount Registros de Anulaciones<br>";


$xml_string = $doc->saveXML();
$xml_string = str_replace("><", ">\r\n<", $xml_string);
$slArch = DBNAME . "_" . $pAnio . "_" . $pMes;
$slRutaArch = "../pdf_files/" . $slArch;
if ($file = fopen($slRutaArch, "w")) {
    fwrite($file, $xml_string);
    fclose($file);
}
//$doc->dump_file($slRutaArch, false, true);

libxml_use_internal_errors(true);   // Enable user error handling

$feed = new DOMDocument();
$feed->preserveWhitespace = false;
$feed->load($slRutaArch);

if (!$feed->schemaValidate('ats-03-15.xsd')) {
    $slErr = libxml_get_all_errors("<BR>");
    echo "<BR><BR> **********************  ERRORES DE VALIDACION XML (ats): ";
    echo str_replace("\n", "<br>", $slErr);
}


$host = "http://" . $_SERVER['HTTP_HOST'];
$uri = rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '/\\');
$extra = "pdf_files/$slArch";
$slFullPath = $host . $uri . "/" . $extra;

$slRef = $host . $uri . "/LibPhp/bajar.php?pOrig=" . $slRutaArch . "&pDest=$slArch.xml";
echo "<br><br><a href=" . $slRef . "> Descargar el Archivo generado </a>";

$final = microtime();
$glInfoTxt .= "<br> Finalizo: " . date("d M Y, H \h\\r\s: i \m\i\\n: s \s\e\g");
$glInfoTxt .= "<br><br> TIEMPO UTILIZADO:      " . round(microtime_diff($inicio, $final), 2) . " segs. <br>";
echo $glInfoTxt;

ob_end_flush();
?>
