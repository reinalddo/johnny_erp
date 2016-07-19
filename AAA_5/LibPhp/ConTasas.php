<?php
/**
*   Obtener la tasa de impuestos
*   @param  pTasa       Int     Id de la tasa de impuestos a  aplicar
*   @descr  retorna un arreglo con la informacion de la tasa registrada en la transaccion, donde el rubro :
*           1= Iva
*           2= Ice
*           Si hay que añadir otros, se indicara en este arreglo.
**/
function fTraeTasa(&$db, $pTasa=1) {
    if ($pTasa == 0) $pTasa=1;
    $slSql = "SELECT tsd_Rubro as 'RUBRO', tsd_Secuencia, tsd_porcentajeBI AS 'TASA'
                FROM gentasacabecera  LEFT JOIN  gentasadetalle  ON tsd_id = tsc_id
                WHERE tsc_id = " . $pTasa . " AND tsd_Rubro > 0  ORDER BY tsd_Rubro, tsd_Secuencia";
    $rst = $db->Execute($slSql);
    $aTasas = array();
    while (!$rst->EOF) {
            $rec = $rst->FetchNextObject(); //          datos del tipo de transaccion
            switch ($rec->RUBRO) {
                Case 1:
                    $aTasas['iva'] = $rec->TASA;
                    break;
                Case 2:
                    $aTasas['ice'] = $rec->TASA;
                    break;
                Case 3:
                    $aTasas['otr'] = $rec->TASA;
                    break;
            }
    }
    if (is_array($aTasas)) {
        return $aTasas;
    }
    else return false;
}
?>
