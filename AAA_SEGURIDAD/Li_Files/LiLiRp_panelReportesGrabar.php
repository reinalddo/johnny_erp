<?php
/*
 * Crear y actualizar Comisiones de la liquidacion - Aplesa
 * esl      23/Enero/2012
 */

//ob_start();
include ("../LibPhp/LibInc.php");  
include_once("GenUti.inc.php");
if (!isset ($_SESSION)) session_start();


$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->debug=fGetParam('pAdoDbg', 0);

$parametros = fGetAllParams();

//CREAR O ACTUALIZAR CERTIFICADO

if($parametros["tipo"]== ING){
            if ($parametros["lde_id"] > 0 ){ // Actualizar existente
                        $slSql = "  update liquidacionDatoExtra 
                                    set  lde_semana = ".$parametros['lde_semana']."
                                         ,lde_tipoVariable = ".$parametros['lde_tipoVariable']."
                                         ,lde_cajas = ".$parametros['lde_cajas']."
                                         ,lde_precio = ".$parametros['lde_precio']."
                                         ,lde_auxiliar = ".$parametros['lde_auxiliar']."
                                         ,lde_estado =  1 
                                         ,lde_fechaRegistro = date_format(sysdate(),'%Y-%m-%d %h:%i:%s')
                                         ,lde_usuarioRegistro = '".$_SESSION['g_user']."'
                                    where lde_id = ".$parametros['lde_id'];
                                    
                        if (!$db->Execute($slSql)){
                                    $id = "0";
                        }
                        else{   $id = $parametros["lde_id"];          
                        }
                        print($id);  
            }
            else{ // ingresar registro nuevo
                       $slSql = "  insert into liquidacionDatoExtra ( lde_id, lde_semana, lde_tipoVariable, lde_cajas, lde_precio, 
                                                                      lde_auxiliar, lde_estado, lde_fechaRegistro, lde_usuarioRegistro)
                                    values (
                                            ".$parametros['lde_id']."
                                            ,".$parametros['lde_semana']."
                                            ,".$parametros['lde_tipoVariable']."
                                            ,".$parametros['lde_cajas']."
                                            ,".$parametros['lde_precio']."
                                            ,".$parametros['lde_auxiliar']."
                                            ,1
                                            ,date_format(sysdate(),'%Y-%m-%d %h:%i:%s')
                                            ,'".$_SESSION['g_user']."'
                                    )
                                    ";
                        
                        if (!$db->Execute($slSql)){
                                    $id = "0";
                        }
                        else{   $id = mysql_insert_id();
                        }
                        print($id);  
            }
}


if($parametros["tipo"]== DEL){
            if ($parametros["lde_id"] > 0 ){ // Cambiar de estado para anular registro
                        $slSql = "  update liquidacionDatoExtra 
                                    set  lde_estado =  0, 
                                         lde_fechaAnula = date_format(sysdate(),'%Y-%m-%d %h:%i:%s'), 
                                         lde_usuarioAnula = '".$_SESSION['g_user']."'
                                    where lde_id = ".$parametros["lde_id"];
                        
                        if (!$db->Execute($slSql)){
                                    $slMensaje = "No se elimino registro";
                        }
                        else{
                          $slMensaje = "Registro eliminado";          
                        }
            }
            else{
                        $slMensaje = "Indique el registro a eliminar";            
            }
            print($slMensaje);      
}


?>