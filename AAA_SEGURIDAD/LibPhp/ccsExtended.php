<?
/**
*   Devuelve un arreglo con valores de la base de datos, compuesto por un arreglo de campos para cada registro devuelto
*   @$db    object      By Ref, Apuntador al Objeto conexion de BD de CCS
*   $sql    String      Texto con la sentencia Sql a ejecutar
*   $pTip   Integer     Tipo de resultado:  1=Arreglo escalar, 2=Arreglado Indexado por el primer Campo
*/

function fGetListValues(&$db, $sql, $pTip=1 )
{
    $values = false;
    $db->query($sql);
    if ($db->next_record())
    {
        $alMeta=$db->metadata();
        $j=$db->num_fields();
        $ilRow=0;
        do
            {
             for ($i=0; $i<$j; $i++){
                    $slCampo=$alMeta[$i]['name'];
                    if($pTip==2) $key=$db->Record[0];
                    else $key=$i;
                        $values[$key][$slCampo] =  $db->f($slCampo);
                 }
            } while ($db->next_record());
        }
//        print_r($values);
//        die();
    return $values;
}
?>
