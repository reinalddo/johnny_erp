<?php
/**
* Codigo para grabar y confirmar ubicacion, asi como tambien el estado de cheques
* Recibe como parametros :
* 	- tipo de comprobante,
* 	- numero de comprobante,
* 	- auxiliar
* 	- Fecha Contable
* @package      AAA
* @subpackage   Contabilidad
* @Author       Gina Franco
* @Date         27/Junio/09
*
* @rev          Gina Franco 16/Jul/09   Se cambio confirmacion, para que inserte un batch para asi confirmar cheques uno a uno
* @rev          Gina Franco 29/Jul/09   Se aumento nueva funcion para guardar los cheques autorizados y generar archivo de banco
* @rev          Gina Franco 27/Sept/09   Se aumento nueva funcion para generar archivo pichincha
*/
include_once("General.inc.php");
define("RelativePath", "..");
include_once("adodb.inc.php");
//include_once("../LibPhp/ConLib.php");
include_once("GenUti.inc.php");
include_once("../Co_Files/CoTrTr_archivobanco.php");
//include_once("../LibPhp/ConTranLib.php");
//include_once("../LibPhp/ConTasas.php");
$gbTrans	= false;
$db = Null;
$cla=null;
$olEsq=null;
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);

/**
*   Grabar ubicacion de cheques
**/
function fGrabaUbicacion(){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    //echo print_r($agPar);
    //echo print_r($agPar['pDetalles']);
    //echo $agPar['tot'];
    
    //Inserta Cabecera
    $sSql = "insert concheques_cab (tipo,fecha,Observacion,Origen,Destino,usuario) values (";
    $sSql .= "2,CURRENT_TIMESTAMP,'".$agPar['obs']."','".$agPar['origen']."','".$agPar['destino']."','".$_SESSION['g_user']."'";
    $sSql .= ")";
    //echo $sSql;
    
    if (!$db->Execute($sSql)) return array("failure"=>true,"totalRecords"=>1,"message"=>"Ocurrio un error al grabar la cabecera");
    else $ultBatch = $db->Insert_ID();
    
    //Inserta detalle
    $limite = $agPar['tot']."-";
    $ind = 0;
    while ($ind < $limite){
        $sSql = "insert concheques_det (IdBatch, com_regnum, det_secuencia) values (";
        $sSql .= $ultBatch.",".$agPar['regnum'][$ind].",".$agPar['secuencia'][$ind]."";
        $sSql .= ")";
        //echo $sSql;
        if (!$db->Execute($sSql)) return array("failure"=>true,"totalRecords"=>1,"message"=>"Ocurrio un error al grabar el detalle");
        $ind++;
    }
    
   
               
    if(fGetParam("pAppDbg",0)){
            print_r($agPar);
    }
        
    $olResp= array("success"=>true,"totalRecords"=>1,"message"=>"Registros guardados con exito");
    return $olResp;
}

/**
*   Graba confirmacion de recepcion de cheques
**/
function fGrabaConfirmacion(){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    //echo print_r($agPar);
    //echo print_r($agPar['pDetalles']);
    //echo $agPar['tot'];
    
    //Inserta detalle
    $limite = $agPar['tot']."-";
    $ind = 0;
    while ($ind < $limite){
        $sSql = "update concheques_det
                    set Confirmado=1, fecConfir=CURRENT_TIMESTAMP, usuConfir='".$_SESSION['g_user']."'
                    where IdBatch=".$agPar['batch'][$ind]." and com_regnum=".$agPar['regNum'][$ind]."
                    and det_secuencia=".$agPar['secuencia'][$ind];
        
        //echo $sSql;
        if (!$db->Execute($sSql)) return array("failure"=>true,"totalRecords"=>1,"message"=>"Ocurrio un error al grabar el detalle");
        $ind++;
    }
    
    if(fGetParam("pAppDbg",0)){
            print_r($agPar);
    }
  
    $olResp= array("success"=>true,"totalRecords"=>1,"message"=>"Cheques confirmados con exito");
    return $olResp;
}

/**
*   Grabar estado de cheques
**/
function fGrabaEstado(){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    //echo print_r($agPar);
    //echo print_r($agPar['pDetalles']);
    //echo $agPar['tot'];
    
    //Inserta Cabecera
    $sSql = "insert concheques_cab (tipo,fecha,Observacion,Origen,Destino,usuario,fecRegistro,operacion) values (
            1,CURRENT_TIMESTAMP,'".$agPar['obs']."','".$agPar['origen']."','".$agPar['destino']."'
            ,'".$_SESSION['g_user']."','".$agPar['fecha']."',".$agPar['estado'].")";
    //echo $sSql;
    
    if (!$db->Execute($sSql)) return array("failure"=>true,"totalRecords"=>1,"message"=>"Ocurrio un error al grabar la cabecera");
    else $ultBatch = $db->Insert_ID();
    
    /*$estOperacion = "1";//100% activo
    if ($agPar['pagado'] == 1)
        $estOperacion = "99";//cerrado
    
    $archivado = "0";
    if ($agPar['archivado'] == 1)
        $archivado = "1";*/
        
    //Inserta detalle
    $limite = $agPar['tot']."-";
    $ind = 0;
    while ($ind < $limite){
        $sSql = "insert concheques_det (IdBatch, com_regnum, det_secuencia";
        
        /*IF ($agPar['estado'] == 2){ //Cuando se esta confirmando un cheque tambien se cambia el estado de recepcion a "confirmado"
            $sSql .= ",confirmado";
        }
        */
        $sSql .= ") values (";
        $sSql .= $ultBatch.",".$agPar['regnum'][$ind].",".$agPar['secuencia'][$ind];
        
        /*IF ($agPar['estado'] == 2){ //Cuando se esta confirmando un cheque tambien se cambia el estado de recepcion a "confirmado"
            $sSql .= ",1";
        }*/
        
        $sSql .= ")";
        //echo $sSql;
        if (!$db->Execute($sSql)) return array("failure"=>true,"totalRecords"=>1,"message"=>"Ocurrio un error al grabar el detalle");
        $ind++;
    }
    
    //echo print_r($_SESSION)."-------";
    //echo $_SESSION["atr"]["CoTrTr"]["ANU"]."****";
    if(fGetParam("pAppDbg",0)){
            print_r($agPar);
    }
    
    $olResp= array("success"=>true,"totalRecords"=>1,"message"=>"Registros guardados con exito");
    return $olResp;
}
/**
*   Grabar cheques autorizados para banco y genera archivos
**/
function fGrabaAutorizacion(){
    global $db, $cla, $olEsq, $agPar, $ogDet, $igNumChq;
    $agPar = fGetAllParams("ALL");
    //echo print_r($agPar);
    //echo print_r($agPar['pDetalles']);
    //echo $agPar['tot'];
    
    //Inserta Cabecera
    $sSql = "insert concheques_cab (tipo,fecha,Observacion,Origen,Destino,usuario,fecRegistro,operacion) values (
            3,CURRENT_TIMESTAMP,'".$agPar['obs']."','',''
            ,'".$_SESSION['g_user']."','".date('Y-m-d')."',null)";
    //echo $sSql;
    
    if (!$db->Execute($sSql)) return array("failure"=>true,"totalRecords"=>1,"message"=>"Ocurrio un error al grabar la cabecera");
    else $ultBatch = $db->Insert_ID();
           
    //Inserta detalle
    $limite = $agPar['tot']."-";
    $ind = 0;
    while ($ind < $limite){
        $sSql = "insert concheques_det (IdBatch, com_regnum, det_secuencia) values (";
        $sSql .= $ultBatch.",".$agPar['regnum'][$ind].",".$agPar['secuencia'][$ind];
        $sSql .= ")";
        //echo $sSql;
        if (!$db->Execute($sSql)) return array("failure"=>true,"totalRecords"=>1,"message"=>"Ocurrio un error al grabar el detalle");
        $ind++;
    }
    
    //echo print_r($_SESSION)."-------";
    //echo $_SESSION["atr"]["CoTrTr"]["ANU"]."****";
    if(fGetParam("pAppDbg",0)){
            print_r($agPar);
    }
     $bolivariano = fArchivoBolivariano($ultBatch);
     
     
     $pichincha = fArchivoPichincha($ultBatch);
     
    $olResp= array("success"=>true,"totalRecords"=>1,"message"=>"Registros guardados con exito","bolivariano"=>$bolivariano,"pichincha"=>$pichincha,"ult"=>$ultBatch);
    //$olResp= array("success"=>true,"totalRecords"=>1,"message"=>"Registros guardados con exito","bolivariano"=>$bolivariano);
    return $olResp;
}

/**
*       Graba texto en un archivolog para depurar este script
*
*/
function fDbgContab($pMsj){
    error_log($pMsj . " \n", 3,"/tmp/dimm_log.err");
    //echo $pMsj . " <br>";
}
/********************************************************************************************************
 *					Inicio de Proceso
 *
 ********************************************************************************************************/
fDbgContab("------------------------------------");
$cla = NULL;
$agPar = NULL;
$olOpc = fGetParam("pOpc",0);
$ogDet=array();
if (1 == $olOpc)
    $ogResp=fGrabaUbicacion();
elseif (2 == $olOpc)
    $ogResp=fGrabaConfirmacion();
elseif (3 == $olOpc)
    $ogResp=fGrabaEstado();
elseif (4 == $olOpc)
    $ogResp=fGrabaAutorizacion();

/*$ogJson = new Services_JSON();
print($ogJson->encode($ogResp));*/
print(json_encode($ogResp));
?>
