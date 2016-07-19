<?php
/*
 * Enviar documento - bitacora
 * @rev    esl        07/12/2010  Agregar funcion para devolver documentos al cliente.
 */


include ("../LibPhp/LibInc.php");   
include_once("GenUti.inc.php");
if (!isset ($_SESSION)) session_start();


$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->debug=fGetParam('pAdoDbg', 0);

    $documento = fGetAllParams();
    
 if($documento["tipo"]== ENV){//ENVIAR DOCUMENTO   
            /**   Actualizar en bitacoradetalle el registro del usuario que envia **/  
            // estado = 3 Rechazado
             $slSql = "update bitacoradetalle set
                             bit_usuariodestino = '".$documento["bit_usuariodestino"]. "',
                             bit_observacionenvio = '".$documento["bit_observacionenvio"]. "'
                      where  bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                      and    bit_secDoc = '".$documento["bit_secDoc"]."'
                      and    bit_emiDoc = '".$documento["bit_emiDoc"]."'
                      and    bit_numDoc = '".$documento["bit_numDoc"]."'
                      and    bit_idAux = ".$documento["bit_idAux"]."
                      and    bit_secuencia = ".$documento["bit_secuencia"]."
                      and    bit_registro = ".$documento["bit_registro"]
                      ;
                      
                      
            if (!$db->Execute($slSql)){
                print('NO SE ACTUALIZO EL REGISTRO');
            }
            else {
                /**   Insertar registro en bitacoradetalle para nuevo usuario que va a recibir el documento
                 *    Estados en genparametros par_Clave = BITEST
                 *    estado = 1 Pendiente
                **/
                
                //Secuencia:
                $slSql = "select ifnull(max(bit_secuencia),0)+1 as secuencia from bitacoradetalle where bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                                                                                     and    bit_secDoc = '".$documento["bit_secDoc"]."'
                                                                                     and    bit_emiDoc = '".$documento["bit_emiDoc"]."'
                                                                                     and    bit_numDoc = '".$documento["bit_numDoc"]."'
                                                                                     and    bit_idAux = ".$documento["bit_idAux"]."
                                                                                     and    bit_registro = ".$documento["bit_registro"];
                
                $rs = $db->Execute($slSql);
                $arr = $rs->fetchRow(); 
                $sec = $arr["secuencia"];
                
                
                $slSql = "insert into bitacoradetalle (bit_tipoDoc,bit_secDoc,bit_emiDoc,bit_numDoc,bit_idAux,bit_registro,bit_secuencia,bit_fechaenvio, bit_usuario,bit_estado)
                          values ('".$documento["bit_tipoDoc"]."','".$documento["bit_secDoc"]."','".$documento["bit_emiDoc"]."','".$documento["bit_numDoc"]."',".$documento["bit_idAux"].",".$documento["bit_registro"].",".$sec.",
                                  '". date("Y-m-d h:i:s"). "','".$documento["bit_usuariodestino"]. "',1)";
                
                if (!$db->Execute($slSql)){
                    print('NO SE ACTUALIZO EL REGISTRO');
                }
                else
                    print("Actualizacion Exitosa");
            }
}


 if($documento["tipo"]== DEV){//DEVOLVER EL DOCUMENTO
            //  Actualizar en bitacoradetalle
            // estado = 3 Rechazado
            $slSql = "update bitacoradetalle set
                             bit_fecharecibido = '". date("Y-m-d h:i:s"). "' ,
                             bit_estado = 3, 
                             bit_observacion = '".$documento["bit_observacion"]. "',
                             bit_motivoRechazo = ".$documento["bit_motivoRechazo"]. ",
                             bit_usuariodestino = '".$documento["bit_revisor"]. "',
                             bit_observacionenvio = '".$documento["bit_observacion"]. "'
                      where  bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                      and    bit_secDoc = ".$documento["bit_secDoc"]."
                      and    bit_emiDoc = ".$documento["bit_emiDoc"]."
                      and    bit_numDoc = ".$documento["bit_numDoc"]."
                      and    bit_idAux = ".$documento["bit_idAux"]."
                      and    bit_secuencia = ".$documento["bit_secuencia"]."
                      and    bit_registro = ".$documento["bit_registro"];
                      
            if (!$db->Execute($slSql)){
                print('NO SE ACTUALIZO REGISTRO');
            }
            else {
                /**   Insertar registro en bitacoradetalle para nuevo usuario que va a recibir el documento
                 *    Estados en genparametros par_Clave = BITEST
                 *    estado = 1 Pendiente
                **/
                
                //Secuencia:
                $slSql = "select ifnull(max(bit_secuencia),0)+1 as secuencia from bitacoradetalle where bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                                                                                     and    bit_secDoc = '".$documento["bit_secDoc"]."'
                                                                                     and    bit_emiDoc = '".$documento["bit_emiDoc"]."'
                                                                                     and    bit_numDoc = '".$documento["bit_numDoc"]."'
                                                                                     and    bit_idAux = ".$documento["bit_idAux"]."
                                                                                     and    bit_registro = ".$documento["bit_registro"];
                
                $rs = $db->Execute($slSql);
                $arr = $rs->fetchRow(); 
                $sec = $arr["secuencia"];
                
                
                $slSql = "insert into bitacoradetalle (bit_tipoDoc,bit_secDoc,bit_emiDoc,bit_numDoc,bit_idAux,bit_registro,bit_secuencia,bit_fechaenvio, bit_usuario,bit_estado)
                          values ('".$documento["bit_tipoDoc"]."','".$documento["bit_secDoc"]."','".$documento["bit_emiDoc"]."','".$documento["bit_numDoc"]."',".$documento["bit_idAux"].",".$documento["bit_registro"].",".$sec.",
                                  '". date("Y-m-d h:i:s"). "','".$documento["bit_revisor"]. "',1)";
                
                if (!$db->Execute($slSql)){
                    print('NO SE ACTUALIZO EL REGISTRO');
                }
                else
                    print("Actualizacion Exitosa");
            }
}


if($documento["tipo"]== RCH){//RECHAZAR EL DOCUMENTO - Enviarlo al cliente.
           /**   Insertar registro en bitacoradetalle para registrar la devolucion del documento al cliente
             *    Estados en genparametros par_Clave = BITEST
             *    estado = 4 Rechazado a cliente
            **/
            
            //Secuencia:
            $slSql = "select ifnull(max(bit_secuencia),0)+1 as secuencia from bitacoradetalle where bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                                                                                 and    bit_secDoc = '".$documento["bit_secDoc"]."'
                                                                                 and    bit_emiDoc = '".$documento["bit_emiDoc"]."'
                                                                                 and    bit_numDoc = '".$documento["bit_numDoc"]."'
                                                                                 and    bit_idAux = ".$documento["bit_idAux"]."
                                                                                 and    bit_registro = ".$documento["bit_registro"];
            
            $rs = $db->Execute($slSql);
            $arr = $rs->fetchRow(); 
            $sec = $arr["secuencia"];
            
            
            $slSql = "insert into bitacoradetalle (bit_tipoDoc,bit_secDoc,bit_emiDoc,bit_numDoc,bit_idAux,bit_registro,bit_secuencia,bit_fechaenvio, bit_usuario,bit_estado,bit_motivoRechazo, bit_usuariodestino, bit_observacionenvio)
                      values ('".$documento["bit_tipoDoc"]."','".$documento["bit_secDoc"]."','".$documento["bit_emiDoc"]."','".$documento["bit_numDoc"]."',".$documento["bit_idAux"].",".$documento["bit_registro"].",".$sec.",
                              '". date("Y-m-d h:i:s"). "','".$_SESSION['g_user']. "',4,".$documento["bit_motivoRechazo"]. ",'CLIENTE','".$documento["bit_observacion"]. "')";
            
            if (!$db->Execute($slSql)){
                print('NO SE ACTUALIZO EL REGISTRO');
            }
            else
                print("Actualizacion Exitosa");
}

if($documento["tipo"]== CIE){//CERRAR EL PROCESO DE BITACORA PARA UN DOCUMENTO
            // Actualizar en bitacora
            // estado = 3 Rechazado
            $slSql = "update bitacora set
                             bit_cerrado = 1, 
                             bit_fechaCierre = '". date("Y-m-d h:i:s"). "' ,
                             bit_usuarioCierre = '".$_SESSION['g_user']. "'                             
                      where  bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                      and    bit_secDoc = ".$documento["bit_secDoc"]."
                      and    bit_emiDoc = ".$documento["bit_emiDoc"]."
                      and    bit_numDoc = ".$documento["bit_numDoc"]."
                      and    bit_idAux = ".$documento["bit_idAux"]."
                      and    bit_registro = ".$documento["bit_registro"];
                      
            if (!$db->Execute($slSql)){
                print('NO SE ACTUALIZO REGISTRO');
            }
            else {
                print("Actualizacion Exitosa");
            }
}

?>