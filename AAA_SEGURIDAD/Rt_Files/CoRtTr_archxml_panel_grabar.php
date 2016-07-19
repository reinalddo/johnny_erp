<?php
/*
 * Grabar registros de Daus y Anulados
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

$reg = fGetAllParams();

/**
 *   TIPO DE PROCESO:
 *   1 = ACTUALIZAR O INGRESAR NUEVO REGISTRO DE DAUS
 *   2 = ELIMINAR DAUS
 *   3 = ACTUALIZAR O INGRESAR NUEVO REGISTRO DE ANULADOS
 *   4 = ELIMINAR ANULADO
 **/
    
 if($reg["tipo"]== 1){//GUARDAR REGISTROS DE DAUS
            if($reg["dau_id"] == 0){//INSERTAR NUEVO REGISTRO
                        $slSql = "insert into fis_dau(
                                    dau_aniocarga,
                                    dau_mescarga,
                                    dau_ordenemb,
                                    dau_zarpe,
                                    dau_fact,
                                    dau_valortotalfob,
                                    dau_numerodau,
                                    dau_distAduanero,
                                    dau_anio,
                                    dau_regimen,
                                    dau_correlativo,
                                    dau_verificador,
                                    dau_autorizacion,
                                    dau_fechaingreso,
                                    dau_usuario,
                                    dau_estado
                                    )
                                  values   (
                                    ".$reg['dau_aniocarga'].",
                                    ".$reg['dau_mescarga'].",
                                    '".$reg['dau_ordenemb']."',
                                    DATE_FORMAT('".$reg['dau_zarpe']."','%Y-%m-%d %H:%i:%s'),
                                    '".$reg['dau_fact']."',
                                    ".$reg['dau_valortotalfob'].",
                                    '".$reg['dau_numerodau']."',
                                    '".$reg['dau_distAduanero']."',
                                    '".$reg['dau_anio']."',
                                    '".$reg['dau_regimen']."',
                                    '".$reg['dau_correlativo']."',
                                    '".$reg['dau_verificador']."',
                                    '".$reg['dau_autorizacion']."',
                                    DATE_FORMAT('". date("Y-m-d h:i:s"). "','%Y-%m-%d %H:%i:%s'),
                                    '".$_SESSION['g_user']."',
                                    1)";
                        if (!$db->Execute($slSql)){  print('NO SE ACTUALIZO EL REGISTRO');  }
                        else print("Actualizacion Exitosa");
            }else{//REGISTRO EXISTENTE - ACTUALIZAR
                        $slSql = "update fis_dau set
                                    dau_ordenemb = '".$reg['dau_ordenemb']."',
                                    dau_zarpe = DATE_FORMAT('".$reg['dau_zarpe']."','%Y-%m-%d %H:%i:%s'),
                                    dau_fact = '".$reg['dau_fact']."',
                                    dau_valortotalfob = ".$reg['dau_valortotalfob'].",
                                    dau_numerodau = '".$reg['dau_numerodau']."',
                                    dau_distAduanero = '".$reg['dau_distAduanero']."',
                                    dau_anio = '".$reg['dau_anio']."',
                                    dau_regimen = '".$reg['dau_regimen']."',
                                    dau_correlativo = '".$reg['dau_correlativo']."',
                                    dau_verificador = '".$reg['dau_verificador']."',
                                    dau_autorizacion = '".$reg['dau_autorizacion']."',
                                    dau_fechaModifica = DATE_FORMAT('". date("Y-m-d h:i:s"). "','%Y-%m-%d %H:%i:%s'),
                                    dau_usuarioModifica = '".$_SESSION['g_user']."'
                                  where  dau_id = ".$reg['dau_id']." 
                                  and dau_aniocarga = ".$reg['dau_aniocarga']." AND dau_mescarga = ".$reg['dau_mescarga'];
                        if (!$db->Execute($slSql)){  print('NO SE ACTUALIZO EL REGISTRO');  }
                        else print("Actualizacion Exitosa");
            }                
}


if($reg["tipo"]== 2){//ELIMINAR DAUS
                        $slSql = "update fis_dau set
                                    dau_estado = -1,
                                    dau_fechaModifica = DATE_FORMAT('". date("Y-m-d h:i:s"). "','%Y-%m-%d %H:%i:%s'),
                                    dau_usuarioModifica = '".$_SESSION['g_user']."'
                                  where  dau_id = ".$reg['dau_id']." 
                                  and dau_aniocarga = ".$reg['dau_aniocarga']." AND dau_mescarga = ".$reg['dau_mescarga'];
                        if (!$db->Execute($slSql)){  print('NO SE ACTUALIZO EL REGISTRO');  }
                        else print("Eliminacion Exitosa");
}

if($reg["tipo"]== 3){//GUARDAR REGISTROS DE ANULADOS
            if($reg["id"] == 0){//INSERTAR NUEVO REGISTRO
                        $slSql = "insert into fisanulados(
                                    anio,
                                    mes,
                                    tipoComprobante,
                                    establecimiento,
                                    puntoEmision,
                                    secuencialInicio,
                                    secuencialFin,
                                    autorizacion,
                                    fechaIngreso,
                                    usuario,
                                    estado
                                    )
                                  values   (
                                    ".$reg['anio'].",
                                    ".$reg['mes'].",
                                    '".$reg['tipoComprobante']."',
                                    '".$reg['establecimiento']."',
                                    '".$reg['puntoEmision']."',
                                    '".$reg['secuencialInicio']."',
                                    '".$reg['secuencialFin']."',
                                    '".$reg['autorizacion']."',
                                    DATE_FORMAT('".$reg['fechaIngreso']."','%Y-%m-%d %H:%i:%s'),
                                    '".$_SESSION['g_user']."',
                                    1)";
                        if (!$db->Execute($slSql)){  print('NO SE ACTUALIZO EL REGISTRO');  }
                        else print("Actualizacion Exitosa");
            }else{//REGISTRO EXISTENTE - ACTUALIZAR
                        $slSql = "update fisanulados set
                                    tipoComprobante = '".$reg['tipoComprobante']."',
                                    establecimiento = '".$reg['establecimiento']."',
                                    puntoEmision = '".$reg['puntoEmision']."',
                                    secuencialInicio = '".$reg['secuencialInicio']."',
                                    secuencialFin = '".$reg['secuencialFin']."',
                                    autorizacion = '".$reg['autorizacion']."',
                                    fechaModifica = DATE_FORMAT('". date("Y-m-d h:i:s"). "','%Y-%m-%d %H:%i:%s'),
                                    usuarioModifica = '".$_SESSION['g_user']."'
                                  where  id = ".$reg['id']." 
                                  and anio = ".$reg['anio']." AND mes = ".$reg['mes'];
                        if (!$db->Execute($slSql)){  print('NO SE ACTUALIZO EL REGISTRO');  }
                        else print("Actualizacion Exitosa");
            }                
}


if($reg["tipo"]== 4){//ELIMINAR ANULADOS
                        $slSql = "update fisanulados set
                                    estado = -1,
                                    fechaModifica = DATE_FORMAT('". date("Y-m-d h:i:s"). "','%Y-%m-%d %H:%i:%s'),
                                    usuarioModifica = '".$_SESSION['g_user']."'
                                  where  id = ".$reg['id']." 
                                  and anio = ".$reg['anio']." AND mes = ".$reg['mes'];
                                  
                                  
                        if (!$db->Execute($slSql)){  print('NO SE ACTUALIZO EL REGISTRO');  }
                        else print("Eliminacion Exitosa");
}




?>