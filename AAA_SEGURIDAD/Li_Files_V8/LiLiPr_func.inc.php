<?php
/**
*   LiLiPr_func.php:  Funciones publicas del proceso de generacion de liquidaciones.
*   @created    Nov/9/04, fah
*
*/
/**
*   Eliminacion de Comprobantes de corridas previas del mismo proceso
* 	@access public
*   @param  object    $db         Ref. a la conexion
*   @param  string    $pProceso   Numero deproceso
*   @param  string    $pTipos     Tipos de comprobante a eliminar
*   @param  string    $pCond      Condicion extra de procesamiento
* 	@return void
*/
function fDelComprobPrev(&$db, $pProceso, $pTipos='++', $pCond=false){
//                                                  Condicion de eliminacion para comprobantes pertenecientes al proceso en curso
    $slWhere =  " com_TipoComp IN(" .$pTipos . ")  AND ".
                " com_Numproceso = " . $pProceso;
    if ($pCond) $slWhere .= " AND " . $pCond;
//                                                  Eliminaciòn de detalles de inventario generados en corrida previas de este proceso
    $slDelet = "DELETE FROM invdetalle WHERE det_RegNumero IN " .
                "(SELECT com_RegNumero FROM concomprobantes WHERE " . $slWhere .")";
    if (!$db->Execute($slDelet )) fErrorPage('','NO SE PUDO ELIMINAR DETALLES DE INVENTARIO -- PREVIOS  ' .$slDelet, true);
//                                                  Eliminacion de detalles contables generados en corrida previas de este proceso
    $slDelet = "DELETE FROM condetalle WHERE det_RegNumero IN " .
                "(SELECT com_RegNumero FROM concomprobantes WHERE " . $slWhere .")";
    if (!$db->Execute($slDelet )) fErrorPage('','NO SE PUDO ELIMINAR DETALLE CONTABLE DE INVENTARIO -- PREVIOS  ' .$slDelet, true);
//                                                  Eliminacion de comprobantes contables generados en corrida previas de este proceso
    $slDelet = "DELETE FROM concomprobantes WHERE ". $slWhere ;
    if (!$db->Execute($slDelet )) fErrorPage('','NO SE PUDO ELIMINAR CABECERA DE INVENTARIO -- PREVIOS  ' .$slDelet, true);
}


/**
*	Elimina los registros generados por una EJECUCION ANTERIOR, PARA REINICIAR EL PROCESO COMPLETAMENTE
*   @acces      public
*   @param      ref     $db         Conexion ala base de datos
*   @param      string  $pCondComp  Condicion para procesamientode Comprobantes
*   @param      string  $pCondLiq   Condicion para procesamientode Liquidaciones
*   @param      string  $pCondTarj  Condicion para procesamientode Tarjas
*   @param      integer $pNumLiq    Numero de liquidacion
*   @param      int		$pProc      Numero de proceso
*/
function fEliminaLiq(&$db, $pCondComp=false, $pCondLiqu=false, $pCondTarj=false, $pNumLiq = -1 ) {
	global $ilAno;
	global $ilSemana;
	global $ilNumProces;
	$aSql=Array();
	
    $aSql[] = "DROP TABLE IF EXISTS tmp_prev ";
    $aSql[] = "CREATE TABLE  tmp_prev
                 SELECT c.com_regnumero AS tmp_regNumero
                    FROM concomprobantes l
	                   JOIN conenlace   ON  l.com_tipocomp = 'LQ' AND enl_tipo = 'LQ' AND enl_ID = l.com_numcomp
				            AND enl_opCode  BETWEEN 6 AND 12
                       JOIN concomprobantes c  ON c.com_Tipocomp = enl_tipocomp and c.com_numcomp = enl_numcomp
                    WHERE l." . $pCondComp ;
    $aSql[] = "DELETE FROM concomprobantes
                WHERE com_regnumero in (SELECT tmp_regNumero FROM tmp_prev) ";

    if ($pCondComp) {
        $aSql[]= "DELETE FROM condetalle
                    WHERE det_regNumero in
                        (SELECT com_regNumero
                            FROM concomprobantes
                            WHERE  com_tipocomp in ('LI', 'LQ', 'LN') AND " . $pCondComp . ") ";
        $aSql[]="DELETE FROM invdetalle
                    WHERE det_regNUmero in
                        (SELECT com_regNUmero
                            FROM concomprobantes
                            WHERE  com_tipocomp in ('LI', 'LQ', 'LN') AND " . $pCondComp  . ") ";
        $aSql[]="DELETE FROM concomprobantes
                    WHERE  com_tipocomp in ('LI', 'LQ', 'LN') AND " . $pCondComp  . " ";

    }
    if ($pCondLiqu) {
        $aSql[]="DELETE FROM liqliquidaciones  WHERE " . $pCondLiqu;
    }
    if ($pCondTarj) {
        $aSql[]= "DELETE FROM condetalle
                    WHERE det_tipocomp in ('LE', 'LR', 'LC') AND
                        det_numComp in (SELECT tad_numtarja FROM liqtarjadetal WHERE " . $pCondTarj  . ") ";
        $aSql[]="DELETE FROM invdetalle
                    WHERE det_regNUmero in
                        (SELECT com_regnumero
                                FROM concomprobantes
                                WHERE com_tipocomp in ('LE', 'LR', 'LC') and
                                    com_numcomp in (SELECT tad_numtarja FROM liqtarjadetal WHERE " . $pCondTarj  . ")) ";
                                    
        $aSql[]="DELETE FROM concomprobantes
                    WHERE com_tipocomp in ('LE', 'LR', 'LC') and
                                    com_numcomp in (SELECT tad_numtarja FROM liqtarjadetal WHERE " . $pCondTarj  . ") ";

        $aSql[]="DELETE FROM concomprobantes
                    WHERE  com_tipocomp in ('LI', 'LQ', 'LN') AND
                           com_numComp in (SELECT tad_numtarja FROM liqtarjadetal WHERE " . $pCondTarj  . ") ";
    }

    fSQL($db, $aSql);
    fLiberaLiq($db, $pCondTarj);
}
/**
*	Libera los detallles de Tarjas que hayan participado en algun proceso
*   @acces      public
*   @param      ref     $db         Conexion ala base de datos
*   @param      string  $pCondTarj  Condicion para procesamientode Tarjas
*   @param      int		$pProc      Numero de proceso
*/
function fLiberaLiq(&$db, $pCondTarj=false ) {
    $aSql=Array();
    if ($pCondTarj) {
    	$aSql[]="UPDATE liqtarjadetal SET tad_LiqNumero = 0, tad_LiqProceso = 0
    					WHERE " . $pCondTarj;
        fSQL($db, $aSql);    					
    	}

}
?>

