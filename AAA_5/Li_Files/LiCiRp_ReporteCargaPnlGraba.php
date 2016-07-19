<?php
/*
 * Grabar los datos extras de las tarjas
 */


include ("../LibPhp/LibInc.php");   
include_once("GenUti.inc.php");
include_once('../LibPhp/JSON.php');
if (!isset ($_SESSION)) session_start();


$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->SetFetchMode(ADODB_FETCH_ASSOC);
$db->debug=fGetParam('pAdoDbg', 0);

$documento = fGetAllParams();
   
if($documento["tipo"]== EXT){//DATOS EXTRAS DE LAS TARJAS - REPORTE DE CALIDAD 
           if ($documento["ptar_NumTarja"]> 0){ //Que se especifique la tarja
                      $slSql = "update liqtarjacabec set
                                 tac_PromCalibracion = ".$documento["ptac_PromCalibracion"]."
                                 ,tac_PromDedos = ".$documento["ptac_PromDedos"]."
                                 ,tac_PromPeso = ".$documento["ptac_PromPeso"]."
                      WHERE tac_Semana   = ".$documento["ptac_Semana"]."
                      AND   tar_NumTarja = ".$documento["ptar_NumTarja"];
           
           $rsDb=$db->Execute($slSql);
           //header("Content-Type: text/javascript");
           $olResp= NULL;
           $olResp->data = array();
           
           if ($rsDb){
                      $olResp->success = true;
                      $olResp->message="DATOS ACTUALIZADOS";
                      $olResp->records=$db->Affected_Rows();
                      $olResp->lastId =$documento["ptar_NumTarja"];

           } else {
                      $olResp->success = false;
                      $olResp->records=0;
                      $olResp->lastId =0;
                      $olResp->error  = $appStatus->getError('t');
                      $olResp->message = $db->ErrorMsg();
                      $olResp->sql  = $slSql;
           }
           
           $json = new Services_JSON();
           print($json->encode($olResp));
            
            
                      
            }
            
            
}



if($documento["tipo"]== TXT_ACT){//ACTUALIZAR TEXTOS DEL REPORTE
           if (($documento["pltr_semanaDesde"]> 0) && ($documento["pltr_CodCliente"]>= 0)) { //Que se especifique la semana y cliente
                      
                      $slSql = "UPDATE liqTextoReporte 
                                            SET
                                            ltr_visible = ".$documento["pltr_visible"]." , 
                                            ltr_fechaRegistro = sysdate() , 
                                            ltr_usuario = '".$_SESSION["g_user"]."'
                                            
                                            WHERE ltr_tipo = 'CALIDAD'
                                            AND ltr_semanaDesde = ".$documento["pltr_semanaDesde"]."
                                            AND ltr_CodCliente = ".$documento["pltr_CodCliente"];          
           $rsDb=$db->Execute($slSql);
           //header("Content-Type: text/javascript");
           $olResp= NULL;
           $olResp->data = array();
           
           if ($rsDb){
                      
                      $olResp->success = true;
                      $olResp->message="DATOS ACTUALIZADOS";
                      $olResp->records=$db->Affected_Rows();
                      $olResp->lastId =$documento["pltr_semanaDesde"];

           } else {
                      $olResp->success = false;
                      $olResp->records=0;
                      $olResp->lastId =0;
                      //$olResp->error  = $appStatus->getError('t');
                      $olResp->message = $db->ErrorMsg();
                      $olResp->sql  = $slSql;
           }
           
           $json = new Services_JSON();
           print($json->encode($olResp));
            
           }
           else{
                      $olResp= NULL;
                      $olResp->data = array();
           
           
                      $olResp->success = false;
                      $olResp->records=0;
                      $olResp->lastId =0;
                      $olResp->error  = ' ';
                      $olResp->message = 'No fue posible leer clave primaria';
                      $olResp->sql  = ' ';
        
           
           $json = new Services_JSON();
           print($json->encode($olResp));
           }

}

if($documento["tipo"]== TXT_ING){//INGRESAR TEXTOS DEL REPORTE
           if (($documento["pltr_semanaDesde"]> 0) && ($documento["pltr_CodCliente"]>= 0)) { //Que se especifique la semana y cliente
                      
                      $slSql = "INSERT INTO liqTextoReporte 
                                 (          ltr_tipo, 
                                            ltr_semanaDesde, 
                                            ltr_CodCliente, 
                                            ltr_txt1Titulo, 
                                            ltr_txt1Desc, 
                                            ltr_txt2Titulo, 
                                            ltr_txt2Desc, 
                                            ltr_txt3Titulo, 
                                            ltr_txt3Desc, 
                                            ltr_visible, 
                                            ltr_fechaRegistro, 
                                            ltr_usuario
                                 )
                                 VALUES
                                 (          'CALIDAD', 
                                            ".$documento["pltr_semanaDesde"].", 
                                            ".$documento["pltr_CodCliente"].", 
                                            '".$documento["pltr_txt1Titulo"]."',
                                            '".$documento["pltr_txt1Desc"]."',
                                            '".$documento["pltr_txt2Titulo"]."', 
                                            '".$documento["pltr_txt2Desc"]."',
                                            '".$documento["pltr_txt3Titulo"]."',
                                            '".$documento["ltr_txt3Desc"]."',
                                            ".$documento["pltr_visible"].", 
                                            sysdate(), 
                                            '".$_SESSION["g_user"]."'
                                 )";          
           $rsDb=$db->Execute($slSql);
           //header("Content-Type: text/javascript");
           $olResp= NULL;
           $olResp->data = array();
           
           if ($rsDb){
                      
                      $olResp->success = true;
                      $olResp->message="DATOS GUARDADOS";
                      $olResp->records=$db->Affected_Rows();
                      $olResp->lastId =$documento["pltr_semanaDesde"];

           } else {
                      $olResp->success = false;
                      $olResp->records=0;
                      $olResp->lastId =0;
                      //$olResp->error  = $appStatus->getError('t');
                      $olResp->message = $db->ErrorMsg();
                      $olResp->sql  = $slSql;
           }
           
           $json = new Services_JSON();
           print($json->encode($olResp));
            
           }
           else{
                      $olResp= NULL;
                      $olResp->data = array();
           
           
                      $olResp->success = false;
                      $olResp->records=0;
                      $olResp->lastId =0;
                      $olResp->error  = ' ';
                      $olResp->message = 'No fue posible leer clave primaria';
                      $olResp->sql  = ' ';
        
           
           $json = new Services_JSON();
           print($json->encode($olResp));
           }

}

?>