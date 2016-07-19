<?
/**
*   Funciones de apoyo en proceso de Transacciones contables
*   @author Fausto Astudillo
*   @create Nov/12/03
*   @Rev    Ene/15/05
**/
/**
*   Graba un Registro de Comprobante, según datos enviados como
* 	@access public
* 	@return bool     Resultado de la inserción
*   @param  variant $db         Conexión ya establecida
*   @param  string  $TipoComp
*   @param  int     $NumComp
*   @param  int     $RegNumero
*   @param  date    $FecTrans
*   @param  date    $FecContab
*   @param  date    $FecVencim
*   @param  int     $Emisor
*   @param  int     $CodReceptor
*   @param  str     $Receptor
*   @param  blob    $Concepto
*   @param  float   $Valor
*   @param  float   $TipoCambio
*   @param  int     $Libro
*   @param  int     $NumRetenc
*   @param  int     $RefOperat
*   @param  int     $EstProceso
*   @param  int     $EstOperacion
*   @param  int     $NumProceso
*   @param  int     $CodMoneda
*   @param  str     $Usuario
*   @param  int     $NumPeriodo
*   @param  date    $FecDigita
**/
function fAgregaComprobante(&$db, $TipoComp, $NumComp, $RegNumero, $FecTrans, $FecContab, $FecVencim, $Emisor,
                    $CodReceptor, $Receptor, $Concepto, $Valor, $TipoCambio, $Libro, $NumRetenc, $RefOperat,
                    $EstProceso, $EstOperacion, $NumProceso, $CodMoneda, $Usuario, $NumPeriodo, $FecDigita)
{
    if (strlen(trim($Receptor))<2 && $CodReceptor > 0){
            $Receptor= fDBValor($db, 'conpersonas', "concat(left(per_Apellidos,12), ' ' , per_Nombres)", "per_codAuxiliar = " . $CodReceptor );
    }
    //  fecdigita no se graba, para que Mysql lo estampe.
    if (!($Libro >0)) $Libro = 9999;
   $slSql = "INSERT INTO concomprobantes (
                     com_TipoComp, com_NumComp, com_RegNumero, com_FecTrans,
                     com_FecContab, com_FecVencim, com_Emisor, com_CodReceptor,
                     com_Receptor, com_Concepto,  com_Valor, com_TipoCambio,
                     com_Libro, com_NumRetenc, com_RefOperat, com_EstProceso,
                     com_EstOperacion, com_NumProceso,  com_CodMoneda, com_Usuario,
                     com_NumPeriodo) VALUES (".
                     $db->qstr($TipoComp). " , ".
                              $NumComp. " , ".
                              $RegNumero. " , '".
                              $FecTrans . "' , '".
                     $FecContab. "' , '".
                     $FecVencim. "' , ".
                     $Emisor. " , ".
                     $CodReceptor. " , ".
                     $db->qstr($Receptor). " , ".
                     $db->qstr($Concepto). " , ".
                     $Valor. " , ".
                     $TipoCambio. " , ".
                     $Libro. " , ".
                     $NumRetenc. " , ".
                     $RefOperat. " , ".
                     $EstProceso. " , ".
                     $EstOperacion. " , ".
                     $NumProceso. " , ".
                     $CodMoneda. " , ".
                     $db->qstr($Usuario). " , ".
                     $NumPeriodo. ")";
    if (!$db->Execute($slSql)) fErrorPage('','NO SE PUDO GRABAR CABECERA DE COMPROBANTE  '. $TipoComp. " / " . $NumComp . ' / '  . " <br> " . $slSql  , true, false);
    else return $db->Insert_ID();
}
/**
*   Actuakiza  un Registro de Detalle Contable, según datos enviados en un arreglo
* 	@access public
*   @param  variant $conn         Referencia a una Conexión ya establecida
*   @param  array   $record       Arreglo que contiene los valores a insertar, con indide = nombre del campo
* 	@return bool                  Resultado de la inserción
*/
function fInsDetalleCont(&$conn, &$alRec)
 {
    if($alRec['det_valdebito'] < 0 ) {
        $alRec['det_valcredito'] = $alRec['det_valdebito'] * (-1);
        $alRec['det_valdebito']  = 0;
        }
    if($alRec['det_valcredito'] < 0 ) {
        $alRec['det_valdebito'] = $alRec['det_valcredito'] * (-1);
        $alRec['det_valcredito']  = 0;
        }
    if(($alRec['det_valcredito'] == 0)  && ($alRec['det_valdebito'] ==0) ) return true; // No grabar en cero
    $alRec['det_fecejecucion']  = fAnalizaFechaCero ($alRec['det_fecejecucion']);
    $alRec['det_feclibros']     = fAnalizaFechaCero ($alRec['det_feclibros']);
    $alRec['det_feccheque']     = fAnalizaFechaCero ($alRec['det_feccheque']);
    
    $sql = "INSERT INTO condetalle (
                det_regnumero,
                det_tipocomp,
                det_numcomp,
                det_secuencia,
                det_clasregistro,
                det_idauxiliar,
                det_ValDebito,
                det_valcredito,
                det_glosa,
                det_estejecucion,
                det_fecejecucion,
                det_estLibros,
                det_feclibros,
                det_RefOperativa,
                det_numcheque ,
                det_feccheque,
                det_codcuenta
            ) VALUES (" .
                $alRec['det_regnumero'] .",'" .
                $alRec['det_tipocomp'] ."'," .
                $alRec['det_numcomp'] ."," .
                $alRec['det_secuencia'] ."," .
                $alRec['det_clasregistro'] ."," .
                $alRec['det_idauxiliar'] ."," .
                $alRec['det_valdebito'] ."," .
                $alRec['det_valcredito'] .",\"" .
                $alRec['det_glosa'] ."\"," .
                $alRec['det_estejecucion'] .",'" .
                $alRec['det_fecejecucion'] ."'," .
                $alRec['det_estlibros'] .",'" .
                $alRec['det_feclibros'] ."'," .
                $alRec['det_refoperativa'] ."," .
                $alRec['det_numcheque'] .",'" .
                $alRec['det_feccheque'] ."','" .
                $alRec['det_codcuenta'] ."' " .
                ")";
	if(!$conn->Execute($sql)) {
		fErrorPage('','NO SE PUDO GRABAR DETALLE DE COMPROBANTE '. $alRec['det_tipocomp'] . ' / ' . $alRec['det_numcomp'] . " <br> " . $sql    , true, false);
		return false;
	}
    return true;
//    return ($conn->Execute($sql)); # Insert the record into the database
}
/**
*   Analiza el contenido de una cadena que debe representar una fecha, si es cero, devuelve el valor '0000-00-00'
* 	@access public
*   @param  $pfec       string     Cadena de texto que repesenta una fecha
* 	@return string      Cadena con el valor depurado (no se valida el contenido de la fecha)
*/
function fAnalizaFechaCero($pFec=0)
 {
     if ($pFec == 0 || $pFec == "0"){
        return "0000-00-00";
    } else {
        return $pFec;
    }
}
/**
*   Añade un Registro de Detalle de INventario, según datos enviados en un arreglo
* 	@access public
*   @param  variant $conn         Referencia a una Conexión ya establecida
*   @param  array   $record       Arreglo que contiene los valores a insertar, con indide = nombre del campo
*   @param  bool    $pcero        Indicador de grabacion con cantidades = 0
* 	@return bool                  Resultado de la inserción
*/
function fInsDetalleInv(&$conn, &$alDetalle, $pCero=false)
 {
    if ($alDetalle['det_CantEquivale']== 0 && !$pCero) return true; //  Si la cantidad es cero, no graba
    $sSql = "INSERT INTO invdetalle (".
                    'det_RegNumero,'.
                    'det_Secuencia,'.
                    'det_CodItem,'.
                    'det_CanDespachada, ' .
                    'det_UniMedida,'.
                    'det_CantEquivale,'.
                    'det_CosTotal,'.
                    'det_ValTotal,'.
                    'det_RefOperativa,'.
                    'det_Estado,'.
                    'det_CosUnitario,'.
                    'det_ValUnitario,'.
                    'det_Destino'.       ") VALUES (".
                    $alDetalle['det_RegNumero']     .",".
                    $alDetalle['det_Secuencia']     .",".
                    $alDetalle['det_CodItem']       .",".
                    $alDetalle['det_CanDespachada'] .",".
                    $alDetalle['det_UniMedida']     .",".
                    $alDetalle['det_CantEquivale']  .",".
                    $alDetalle['det_CosTotal']      .",".
                    $alDetalle['det_ValTotal']      .",".
                    $alDetalle['det_RefOperativa']  .",".
                    $alDetalle['det_Estado']        .",".
                    $alDetalle['det_CosUnitario']   .",".
                    $alDetalle['det_ValUnitario']   .",".
                    $alDetalle['det_Destino']       .")";

            if(!$conn->Execute($sSql)) fErrorPage('','NO SE PUDO GRABAR DETALLE DE COMPROBANTE '. $alDetalle['det_RegNumero'] . ' / ' . $alDetalle['det_CodItem']  , true, false);
}

function fBloquea(&$db, $tabla, $modo="WRITE") {
    if (!$db->Execute("LOCK TABLES " . $tabla . " " . $modo)) fErrorPage('',"NO HA SIDO POSIBLE BLOQUEAR TABLAS, INTENTELO MAS TARDE  " . $lastrec['det_numcomp'] . "-" . $ilNumComp);;
}
function fDesBloquea(&$db) {
   if (!$db->Execute("UNLOCK TABLES " )) fErrorPage('',"IMPOSIBLE DESBLOQUEAR TABLAS, INTENTELO MAS TARDE  " . $lastrec['det_numcomp'] . "-" . $ilNumComp);
}
?>
