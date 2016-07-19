<?php
/*
*   CoRtTr_archxml: Generacion de Archivo XML
*   @author     Fausto Astudillo
*   @param      string		pQryLiq  Condición de búsqueda
*   @output     contenido pdf del reporte.
*   @rev        fah 26/02/08   procesar los datos de forma diferente, segun sea año 2006 o 2007
*   @rev        fah 03/09/08   Soporte de autorizaciones multiples (varios tipos de documentos con una misma autorizacion)
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
*   @rev	fah 16/02/09	Aplicar condicion de validacion con tipo de documento, para todos loa años.
*/
function &fDefineCompras(&$db, $pQry=false){
	global $pAnio;
    $ilNumProceso= fGetParam('pro_ID', 0);
	$slAutCond = "";
	/*if ($pAnio == 2006  ){ //	@fah 26/02/08					Validar autorizacion + tipo doc + ruc solo para el 2006 // se aplica tambien al 2008
		$slAutCond = " and aut_tipodocum = tipocomprobante
			AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
			      tipocomprobante =3 ) " ;
	}
    if ($pAnio == 2007  ){ //	@fah 26/02/08					Validar autorizacion + tipo doc + ruc solo para el 2006 // se aplica tambien al 2008
		$slAutCond = " and aut_tipodocum = tipocomprobante
			AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
			      tipocomprobante =3 ) " ;
	}
    if ($pAnio == 2008  ){ //	@fah 26/02/08					Validar autorizacion + tipo doc + ruc solo para el 2006 // se aplica tambien al 2008 */
		$slAutCond = " and aut_tipodocum = tipocomprobante
				AND ((tipocomprobante <> 3 and aut_idauxiliar = idprovfact) or
				      tipocomprobante =3 ) and aut.aut_IDauxiliar = idprovfact " ;
	/*}*/
    
    
    /*@todo:    Preocesar correctamente la relacion fiscompra - genaut sobre cada tipo de documento. */
    $alSql =
	"SELECT ID, tipoTransac, codSustento, devIva, tid.par_Valor1 AS tpIdProv, per_Ruc as idProv,
			tipoComprobante, DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
			DATE_FORMAT(fechaEmision ,'%d/%m/%Y') AS fechaEmision,
			establecimiento, puntoEmision, secuencial, autorizacion,
			if(tipoComprobante = 3, DATE_FORMAT(liq.aut_FecVigencia,'%d/%m/%Y'), 
               DATE_FORMAT(aut.aut_FecVigencia,'%d/%m/%Y') ) AS fechaCaducidad,
			baseImponible, baseImpGrav,porcentajeIva, montoIva,
			baseImpIce, IFNULL(ice.tab_codsecuencial,0) as porcentajeIce, 
			montoIce, montoIvaBienes, porRetBienes, valorRetBienes,
			montoIvaServicios, porRetServicios, valorRetServicios,
			ai1.tab_txtData as codRetAir, baseImpAir, porcentajeAir, valRetAir,
			ai2.tab_txtData as codRetAir2, baseImpAir2, porcentajeAir2, valRetAir2,
			ai3.tab_txtData as codRetAir3, baseImpAir3, porcentajeAir3, valRetAir3,
			estabRetencion1, puntoEmiRetencion1 AS ptoEmiRetencion1, secRetencion1, autRetencion1,
			DATE_FORMAT(fechaEmiRet1 ,'%d/%m/%Y') AS fechaEmiRet1, docModificado,
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
			LEFT JOIN fistablassri ai1   ON ai1.tab_codtabla = 10 and ai1.tab_codigo = codRetAir
			LEFT JOIN fistablassri ai2   ON ai2.tab_codtabla = 10 and ai2.tab_codigo = codRetAir2
			LEFT JOIN fistablassri ai3   ON ai3.tab_codtabla = 10 and ai3.tab_codigo = codRetAir3
			WHERE tipoTransac = 1 " ;
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
    $alSql =
	"SELECT tipoTransac, codSustento, tipImpExp as exportacionDe,
			tipoComprobante, DATE_FORMAT(fechaRegistro ,'%d/%m/%Y') AS fechaRegistro,
            distAduanero, anio, regimen, correlativo, verificador,
            documEmbarque as docTransp, DATE_FORMAT(fechaEmbarque,'%d/%m/%Y') AS fechaEmbarque,
            per_Ruc as idFiscalCliente, per_subcategoria as tipoSujeto,
            valorCifFob as valorFOB, concat(per_Apellidos, ' ', per_Nombres) as razonSocialCliente,
            devIva, facturaExportacion, valorFOBComprobante,
			establecimiento, puntoEmision, secuencial, autorizacion,
            DATE_FORMAT(fechaEmision ,'%d/%m/%Y') AS fechaEmision,
            autorizacion
	FROM fiscompras
			LEFT JOIN conpersonas pro    ON pro.per_codauxiliar = idProvFact
			LEFT JOIN genautsri   aut    ON aut.aut_IDauxiliar = per_codauxiliar AND aut.aut_ID = autorizacion
			LEFT JOIN genparametros tid  ON par_clave = 'TIPID' AND par_secuencia = per_tipoID
			LEFT JOIN genautsri   art    ON art.aut_IDauxiliar = idProvFact AND art.aut_ID = autRetencion1 AND art.aut_tipoDocum = 7
			WHERE tipoTransac = 4 " ;
   if (strlen($pQry) > 0 ) $alSql .= " AND "  . $pQry ;
//    WHERE " . $pQry . " ";
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
			secuencialFin,	autorizacion,
	        DATE_FORMAT(fechaAnulacion ,'%d/%m/%Y') AS fechaAnulacion
	FROM fisanulados
			" ;
   if (strlen($pQry) > 0 ) $alSql .= " WHERE "  . $pQry ;
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
               fProcesaCampo($alRec, $olDato);
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
fAgregarElemPar($root, 'direccionMatriz', 'EGDIR');
fAgregarElemPar($root, 'telefono', 'EGTELE');
fAgregarElemPar($root, 'fax', 'EGFAX');
fAgregarElemPar($root, 'email', 'EGMAIL');
fAgregarElemPar($root, 'tpIdRepre', 'EGTID');
fAgregarElemPar($root, 'idRepre', 'EGIDR');
fAgregarElemPar($root, 'rucContador', 'EGRCO');
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
