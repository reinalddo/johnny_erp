<?php
/*
 * Grabar una transaccion de CXC
 */

//ob_start();
include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
if (!isset ($_SESSION)) session_start();

//ini_set('memory_limit', '64M');
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->debug=fGetParam('pAdoDbg', 0);

    $documento = fGetAllParams();
    // Estados en genparametros par_Clave = BITEST
    
    if($documento["tipo"]== RCB){ //RECIBIR DOCUMENTO
            /**   Actualizar en bitacoradetalle **/  
            // estado = 2 Recibido
            $slSql = "update bitacoradetalle set
                             bit_fecharecibido = '". date("Y-m-d h:i:s"). "' ,
                             bit_estado = 2, 
                             bit_movimiento = ".$documento["bit_movimiento"]. " , 
                             bit_observacion = '".$documento["bit_observacion"]. "'
                      where  bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                      and    bit_secDoc = '".$documento["bit_secDoc"]."'
                      and    bit_emiDoc = '".$documento["bit_emiDoc"]."'
                      and    bit_numDoc = '".$documento["bit_numDoc"]."'
                      and    bit_idAux = ".$documento["bit_idAux"]."
                      and    bit_registro = ".$documento["bit_registro"]."
                      and    bit_secuencia = ".$documento["bit_secuencia"];
                      
                      
            if (!$db->Execute($slSql)){
                print('NO SE ACTUALIZO EL REGISTRO');
            }
            else {
                /**   Actualizar en bitacora **/ 
                $slSql = "update bitacora set
                             bit_usuarioActual = '".$_SESSION['g_user']. "' 
                      where  bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                      and    bit_secDoc = '".$documento["bit_secDoc"]."'
                      and    bit_emiDoc = '".$documento["bit_emiDoc"]."'
                      and    bit_numDoc = '".$documento["bit_numDoc"]."'
                      and    bit_idAux = ".$documento["bit_idAux"]."
                      and    bit_registro = ".$documento["bit_registro"];

                //if (!$db->Execute($slSql)) fErrorPage('','NO SE PUDO GRABAR REGISTRO  '. $slSql  , true, false);
                if (!$db->Execute($slSql)){
                    print('NO SE ACTUALIZO EL REGISTRO');
                }
                else
                   // print("Actualizacion Exitosa Doc (".$documento["bit_tipoDoc"]."-".$documento["bit_numDoc"]."-".$documento["bit_idAux"].")");
                    print("Actualizacion Exitosa");
            }
        
    }
    
    
    

?>