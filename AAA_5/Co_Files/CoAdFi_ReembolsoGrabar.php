<?
/* Funciones para Agregar, anular registros de Reembolso de Gastos
   para empleados - solicitado por Frutiboni
   @author  Erika Suarez    2011-08-04
*/

 include ("../LibPhp/LibInc.php");   // para produccion
include_once("GenUti.inc.php");
if (!isset ($_SESSION)) session_start();

$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->debug=fGetParam('pAdoDbg', 0);

$parametros = fGetAllParams();


/**
 * FUNCIONES PARA RELACION DE AUXILIAR - CENTRO DE COSTO
*/
if($parametros["pFuncion"]== ING){
    //VERIFICAR QUE EL REGISTRO NO ESTE INGRESADO   
    $sql = "SELECT COUNT(*) as ingresado FROM conCuentaAuxiliar
            WHERE cxa_codPersona = ".$parametros["cxa_codPersona"]." AND cxa_CodCuenta = '".$parametros["cxa_codCuenta"]."'
            and cxa_estado = 1 ";
    if (!$rs = $db->Execute($sql)){
            print('NO SE CONSULTO SI YA EXISTE EL REGISTRO');
    }else {
            $arr = $rs->fetchRow(); 
            $ingresado = $arr["ingresado"];
            
            if ($ingresado > 0){
               print("YA EXISTE LA RELACION DE LA CUENTA ('".$parametros["cxa_codCuenta"]."') Y EL AUXILIAR (".$parametros["cxa_codPersona"].")"); // El documento ya existe y no esta anulado.
            }
            else{
                //VERIFICAR SI REGISTRO EXISTE PERO ANULADO
                $sql = "SELECT COUNT(*) as ingresado FROM conCuentaAuxiliar
                        WHERE cxa_codPersona = ".$parametros["cxa_codPersona"]." AND cxa_CodCuenta = '".$parametros["cxa_codCuenta"]."'
                        and cxa_estado = -1 ";
                if (!$rs = $db->Execute($sql)){ // El registro ya existe, debe ser actualizado con estado = 1 para activarlo nuevamente
                    print('NO SE CONSULTO SI YA EXISTE EL REGISTRO');
                }else {
                    $arr = $rs->fetchRow(); 
                    $ingresado = $arr["ingresado"];
                    
                    if ($ingresado > 0){
                       $sql = " UPDATE conCuentaAuxiliar set cxa_estado = 1,
                                                             cxa_usuario = '".$_SESSION['g_user']."',
                                                             cxa_fechaRegistro = SYSDATE()
                                WHERE cxa_codPersona = ".$parametros["cxa_codPersona"]."
                                AND cxa_codCuenta = '".$parametros["cxa_codCuenta"]."'" ;
                        if (!$db->Execute($sql)){
                            print('NO SE INSERTO EL REGISTRO');
                        }
                        else { print("REGISTRO GUARDADO");
                        }
                    }
                    else{
                        // El registro no existe, se procede a ingresar
                       $sql = "INSERT INTO conCuentaAuxiliar (cxa_CodCuenta, cxa_codPersona, cxa_estado, cxa_usuario, cxa_fechaRegistro)
                               VALUES ('".$parametros["cxa_codCuenta"]."',".$parametros["cxa_codPersona"].", 1,'".$_SESSION['g_user']."',SYSDATE())
                               ";
                        if (!$db->Execute($sql)){
                            print('NO SE INSERTO EL REGISTRO');
                        }
                        else { print("REGISTRO GUARDADO");
                        }
                    }
                    
                }
            }
    }
}

if($parametros["pFuncion"]== SUP){
    //ELIMINAR RELACION DE CENTRO DE COSTO Y AUXILIAR
    $sql = " UPDATE conCuentaAuxiliar set cxa_estado = -1
            WHERE cxa_codPersona = ".$parametros["cxa_codPersona"]."
            AND cxa_codCuenta = '".$parametros["cxa_codCuenta"]."'" ;
    if (!$db->Execute($sql)){
        print('NO SE ELIMINO EL REGISTRO');
    }
    else {
        print("REGISTRO ELIMINADO");
    }
}
/**
 * FUNCIONES PARA REEMBOLSO -CABECERA
*/
if($parametros["pFuncion"]== INREE){
    //SELECCIONAR EL COD AUXILIAR DEL USUARIO
    $sql = "SELECT usu_AuxId as auxUsu FROM seguridad.segusuario WHERE usu_AuxId IS NOT NULL AND usu_login = '".$_SESSION['g_user']."' ";
    if (!$rs = $db->Execute($sql)){
            print('-1');
    }else {
            $arr = $rs->fetchRow(); 
            $usuEmisor = $arr["auxUsu"];
            if ($usuEmisor == " "){
               print("-1"); //No se ha podido seleccionar el auxiliar del usuario emisor
            }
            else{
                    if ($parametros["ree_Id"] > 0){ //Solo actualizar cabecera
                       $sql = " UPDATE conReembolso 
                                SET
                                    ree_Emisor = '".$usuEmisor."' , 
                                    ree_Concepto = '".$parametros["ree_Concepto"]."' , 
                                    ree_RefOperat = ".$parametros["ree_RefOperat"]." , 
                                    ree_Fecha = '".$parametros["ree_Fecha"]."' , 
                                    ree_Valor = ".$parametros["ree_Valor"]." , 
                                    ree_Usuario = '".$_SESSION['g_user']."'
                                WHERE
                                    ree_Id = ".$parametros["ree_Id"];

                        if (!$db->Execute($sql)){ print('-1');
                        }
                        else { print($parametros["ree_Id"]);
                        }
                    }
                    else{
                        // Ingresar Cabecera
                       $sql = "INSERT INTO conReembolso 
                                    (ree_Emisor, 
                                    ree_Concepto, 
                                    ree_RefOperat, 
                                    ree_Fecha, 
                                    ree_Valor, 
                                    ree_Estado, 
                                    ree_Usuario, 
                                    ree_FechaRegistro
                                    )
                                    VALUES ('".$usuEmisor."','".$parametros["ree_Concepto"]."','".$parametros["ree_RefOperat"]."',
                                            '".$parametros["ree_Fecha"]."',".$parametros["ree_Valor"].",1,'".$_SESSION['g_user']."',SYSDATE())";
                        if (!$db->Execute($sql)){
                            print('-1');
                        }
                        else { print($db->Insert_ID());
                        }
                    }

            }
    }
}

if($parametros["pFuncion"]== SUREE){ 
    //ELIMINAR REEMBOLSO
    if ($parametros["ree_Id"] > 0){
        //Eliminar detalles que pudiera tener
        $sql = "Delete from conReembolsoDetal where red_Id = ".$parametros["ree_Id"];
        if (!$rs = $db->Execute($sql)){
                print('-1');
        }else {
            //Eliminar cabecera
            $sql = "Delete from conReembolso where ree_Id = ".$parametros["ree_Id"];
            if (!$rs = $db->Execute($sql)){
                    print('-1');
            }else {
                    print('1');
            }
        }
    }
}
/**
 * FUNCIONES PARA REEMBOLSO -DETALLE
*/
if($parametros["pFuncion"]== INRED){
    // INSERTAR REGISTROS DE DETALLE DE REEMBOLSO
    if ($parametros["red_Id"] > 0){
           $sql = "INSERT INTO conReembolsoDetal 
                    (red_Id, 
                    red_Concepto, 
                    red_MotivoCC, 
                    red_Aux, 
                    red_Valor, 
                    red_Usuario
                    )
                    VALUES (".$parametros["red_Id"].",'".$parametros["red_Concepto"]."','".$parametros["red_MotivoCC"]."',
                            ".$parametros["red_Aux"].",".$parametros["red_Valor"].",'".$_SESSION['g_user']."')";
            if (!$db->Execute($sql)){
                print('-1');
            }
            else { print($db->Insert_ID());
            }
    }
}


if($parametros["pFuncion"]== SURED){ 
    //ELIMINAR DETALLES DE REEMBOLSO
    if ($parametros["red_Id"] > 0){
        $sql = "Delete from conReembolsoDetal where red_Id = ".$parametros["red_Id"];
        if (!$rs = $db->Execute($sql)){
                print('-1');
        }else {
                print('1');
        }    
    }
    
}


?>