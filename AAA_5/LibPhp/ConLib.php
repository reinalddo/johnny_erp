<?
/** Funciones Generales de Apoyo contable
*   @ Rev:   Feb 18/04
	----------------------------------------------------------*/
/*
 *		Retorna el ID de proximo Auxiliar de una categoria dada
 *		@param	pCategAux	Categforia de Auxiliar a generar
 *		@param	pTabla		Nombre de tabla en la que se genera el aux
 *		@param	pCampo	  Nombre de campo a consultar
 **/
function fPxmoAuxiliar($pCategaux, $pTabla, $pCampo)
{
	$clDB = New clsDBdatos;
        $tlSql ="SELECT par_valor2, par_valor3 FROM genparametros WHERE " .
			    "par_clave = 'CAUTI' and par_secuencia = " .
				CCTOSQL($pCategaux, "Integer");

	$clDB->Query($tlSql);
	$rsSecue = $clDB->Next_Record();
        if (!$rsSecue) echo "ERROR";
		$ilMin = $clDB->f(0);
		$ilMax = $clDB->f(1);
//		$ilMax = CCDLOOKUP("MAX(cat_CodAuxiliar)", "conCategorias", " cat_codCategoria BETWEEN " . $clDB->f(0) .
//                        " AND " . $clDB->f(1), $clDB) ;
		if (is_null($ilMax) or empty($ilMax )) $ilMax= 0;
        $ilNvoAux = CCDLOOKUP("MAX(" . $pCampo. ") ", $pTabla, $pCampo . " BETWEEN " . $clDB->f(0) .
                              " AND " . $clDB->f(1), $clDB) ;
//echo "min: $ilMin   Max: $ilMax   Nvo: $ilNvoAux " ;
        if ($ilNvoAux >= ($ilMax-1)) {
           fMensaje("LA SECUENCIA PARA ESTA CATEGORIA DE AUXILIARES SE HA AGOTADO!!! (" . $ilNvoAux  . " >= " . $clDB->f(1)) . ") ";
           	return -1;
           	}
        if (is_null($ilNvoAux) or empty($ilNvoAux )) {
			$ilNvoAux  = $ilMin;
			}
        $ilNvoAux = $ilNvoAux  + 1;
        unset ($clDB);
	unset ($rsSecue);
	return $ilNvoAux;
}
/*
 *			retorna el Siguiente numero de comprobante de un determinado tipo
 *			@param		db			Obj Ref			Referencia a objeto de conexion BD
 *			@param		pTipo		String		  Tipo de comporbante
 **/
function fPxmoComprob(&$db, $pTipo) {
    return NZ(fDBValor($db, 'concomprobantes', 'ifnull(max(com_numcomp),0)+1', "com_Tipocomp = '" . $pTipo . "'"),1);
}
?>
