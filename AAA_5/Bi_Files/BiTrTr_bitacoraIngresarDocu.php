<?php
/*
 * Ingresar o modificar un documento ingresado a bitacora
 * esl  20/08/2010
 * @rev     esl         20/12/2010  agregar validacion para ver si un documento ya fue ingresado y no esta anulado.
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


//INGRESAR DOCUMENTO A BITACORA
if($documento["tipo"]== ADD){
            /**   Insertar registro en bitacora en 09_base
             *    Se guarda la cabecera del documento
             **/
            
            
            //@rev     esl         20/12/2010  agregar validacion para ver si un documento ya fue ingresado y no esta anulado.
            /*Validar si el documento que se va a ingresar ya existe y no tiene estado anulado:*/
            $slSql = "select count(*) as ingresado from bitacora where bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                        and   trim(bit_secDoc) = trim('".$documento["bit_secDoc"]."')
                        and   trim(bit_emiDoc) = trim('".$documento["bit_emiDoc"]."')
                        and   trim(bit_numDoc) = trim('".$documento["bit_numDoc"]."')
                        and   bit_idAux = ".$documento["bit_idAux"]."
                        and   bit_anulado = 0";
                
                
            if (!$db->Execute($slSql)){     print('NO SE CONSULTO DOCUMENTO');
            }else {
                    $rs = $db->Execute($slSql);
                    $arr = $rs->fetchRow(); 
                    $ingresado = $arr["ingresado"];
                    
                    if ($ingresado > 0){
                       print("0"); // El documento ya existe y no esta anulado.
                    }
                    else{
                        // Iniciar proceso para insertar documento:
                        $slSql = "insert into bitacora (bit_codEmpresa,bit_empresa, bit_tipoDoc, bit_secDoc,bit_emiDoc,bit_numDoc, bit_idAux,bit_fechaDoc,bit_valor,bit_usuarioActual)
                                                values('".$documento["bit_codempresa"]."','".$documento["bit_empresa"]."','".$documento["bit_tipoDoc"]."',
                                                       '".$documento["bit_secDoc"]."','".$documento["bit_emiDoc"]."','".$documento["bit_numDoc"]."',".$documento["bit_idAux"].",
                                                       '".$documento["bit_fechaDoc"]. "',".$documento["bit_valor"]. ",'".$_SESSION['g_user']."')";
           
                       if (!$db->Execute($slSql)){
                           //print('NO SE INSERTO EL REGISTRO');
                           print("0");
                       }
                       else {
                           $lastRegistro= $db->GetOne("Select LAST_INSERT_ID()");
                          // print("Ultimo Registro:".$lastRegistro."---");
                           
                           /**   Insertar registro en bitacoradetalle
                            *    generando un registro en estado recibido para RECEPCION
                            *    Estados en genparametros par_Clave = BITEST
                            *    estado = 2 Recibido
                           **/
                           
                           
                           //Parametros a insertar:
                           $sec =1; // El ingreso a bitacora es el primer registro del documento
                           $estado = 2; // RECIBIDO
                           $movimiento = 1; // INGRESO A BITACORA
                           
                           //Insertar detalle
                                           
                           $slSql = "insert into bitacoradetalle (bit_tipoDoc, bit_secDoc, bit_emiDoc, bit_numDoc, bit_idAux,bit_registro,bit_secuencia, bit_fechaenvio, bit_usuario,
                                                                  bit_estado, bit_fecharecibido, bit_movimiento,bit_observacion)
                                                           values('".$documento["bit_tipoDoc"]."','".$documento["bit_secDoc"]."','".$documento["bit_emiDoc"]."','".$documento["bit_numDoc"]."'
                                                                  ,".$documento["bit_idAux"].",".$lastRegistro.",".$sec.",'". date("Y-m-d h:i:s")."','".$_SESSION['g_user']."',".$estado."
                                                                  ,'". date("Y-m-d h:i:s")."',".$movimiento.",'".$documento["bit_observacion"]."')";
                           
                           if (!$db->Execute($slSql)){
                               //print('NO SE INSERTO DETALLE');
                               print("0");
                           }
                           else
                              //print("DOCUMENTO INGRESADO EXITOSAMENTE");
                               print("1");
                       }  
                    }
            }
            
            
            
}


//CONSULTAR SI EL DOCUMENTO YA ESTA INGRESADO EN BITACORA
if($documento["tipo"]== CON){
            
                $slSql = "select count(*) as ingresado from bitacora where bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                                                                              and   trim(bit_secDoc) = trim('".$documento["bit_secDoc"]."')
                                                                              and   trim(bit_emiDoc) = trim('".$documento["bit_emiDoc"]."')
                                                                              and   trim(bit_numDoc) = trim('".$documento["bit_numDoc"]."')
                                                                              and   bit_idAux = ".$documento["bit_idAux"]."
                                                                              and   bit_anulado = 0";
                
                $rs = $db->Execute($slSql);
                $arr = $rs->fetchRow(); 
                $ingresado = $arr["ingresado"];
            
                      
                if (!$db->Execute($slSql)){
                    print('NO SE CONSULTO DOCUMENTO');
                }
                else {
                    print($ingresado); 
                }
}

//Actualizar datos de la cabecera del documento
if($documento["tipo"]== UPD){
                /**   Actualizar en bitacora **/  
                $slSql = "update bitacora set
                                 bit_codEmpresa = '".$documento["bit_codempresa"]. "' ,
                                 bit_empresa = '".$documento["bit_empresa"]. "' ,
                                 bit_fechaDoc = '".$documento["bit_fechaDoc"]. "' ,
                                 bit_valor = ".$documento["bit_valor"]. "
                          where  bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                          and    bit_secDoc = '".$documento["bit_secDoc"]."'
                          and    bit_emiDoc = '".$documento["bit_emiDoc"]."'
                          and    bit_numDoc = '".$documento["bit_numDoc"]."'
                          and    bit_idAux = ".$documento["bit_idAux"]."
                          and    bit_registro = ".$documento["bit_registro"];
                          
                if (!$db->Execute($slSql)){
                    print("0");
                }
                else {
                    print("1");
                }
}




//ELIMINAR DOCUMENTO
if($documento["tipo"]== DEL){
            /**  Eliminación lógica en bitacora en 09_base
             *   Sólo cambia de estado. Los documentos con bit_anulado = 1 ya no pueden ser vistos en ninguna pantalla o reporte.
             **/
            
            $slSql = "update bitacora set
                        bit_anulado = 1, 
                        bit_fechaAnulacion = '". date("Y-m-d h:i:s"). "' ,
                        bit_usuarioAnula = '".$_SESSION['g_user']. "' 
                      where bit_tipoDoc = '".$documento["bit_tipoDoc"]."'
                      and bit_secDoc = '".$documento["bit_secDoc"]."'
                      and bit_emiDoc = '".$documento["bit_emiDoc"]."'
                      and bit_numDoc = '".$documento["bit_numDoc"]."'
                      and bit_idAux = ".$documento["bit_idAux"]."
                      and bit_registro = ".$documento["bit_registro"];

            if (!$db->Execute($slSql)){
               print("0");
            }
            else {
               print("1");
            }
}

//print_r($_SESSION);




?>