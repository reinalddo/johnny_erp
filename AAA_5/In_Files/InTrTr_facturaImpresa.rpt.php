<?php
/**
 * Erika Suarez 22/10/2010
 * FACTURA IMPRESA DESDE EL SISTEMA
 **/

define("RelativePath", "..");
include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");

$db = Null;
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
$db->debug=fGetparam("pAdoDbg",false);
require('Smarty.class.php');
class Smarty_AAA extends Smarty {
   function Smarty_AAA()
   {
        $this->Smarty();
        $this->template_dir = '../templates';
        $this->compile_dir = '../templates_c/';
        $this->config_dir = '../configs';
        $this->cache_dir = '../cache/';
        $this->compile_check = true;
        $this->debugging = false;
   }
}

// include("../LibPhp/excelOut.php");

$Tpl = new Smarty_AAA();
$Tpl->debugging =fGetparam("pAppDbg",false);
$numero = fGetParam('regNumero','');
$empresa = $_SESSION['g_empr'];


$db->execute("DROP  TABLE IF EXISTS tmp_datosfactura");

$sSql = "CREATE TABLE tmp_datosfactura 
         AS
            (SELECT com_TipoComp, com_NumComp, com_RegNumero
            /*------------ Cabecera de la Factura -----------------*/
            ,CONCAT(TRIM(per_Apellidos),' ', TRIM(per_Nombres)) AS cliente
            ,com_FecTrans
            ,com_FecContab AS fecha
            ,com_FecVencim
            ,per_Ruc AS RUC
            ,IFNULL(TRIM(per_Direccion),'') AS direccion
            ,IFNULL(TRIM(per_Telefono1),'') AS telefono
            /*------------ Detalle de la Factura ------------------*/
            ,det_CodItem AS item
            ,SUBSTR(TRIM(act_Descripcion),1,60) AS descripcion
            ,ROUND(det_CantEquivale,2) AS cantidad
            ,ROUND(det_valunitario,2) AS valUni
            ,CONCAT(det_Destino,' %')   AS porcdescu
            ,ROUND((det_valunitario*det_CantEquivale)*(det_Destino/100),2) AS descuento
            ,ROUND((det_valunitario*det_CantEquivale)-((det_valunitario*det_CantEquivale)*(det_Destino/100)),2) AS valTotal
            -- select d.* 
            FROM concomprobantes c
            JOIN invdetalle d ON d.det_RegNUmero = c.com_RegNumero
            JOIN conactivos ON act_CodAuxiliar = det_CodItem
            LEFT JOIN conpersonas ON c.com_CodReceptor=per_CodAuxiliar
            WHERE com_TipoComp = 'FA'
            AND com_NumComp = 10011676
            )";
$db->execute($sSql);
/* conversion: 1px equivale a 0.4 mm (0.04 cm) */
/********************************************************
 *CABECERA DEL DOCUMENTO
 *******************************************************/
$sSql = "SELECT * FROM confDocCab c JOIN confDocDet d ON c.conf_id = d.conf_c1 and c.conf_tdoc = 'FA' and d.conf_seccion = 'C' order by conf_orden";
$rs = $db->execute($sSql);

if($rs->EOF){
    fErrorPage('','NO SE HA DEFINIDO PANTILLA PARA EL DOCUMENTO', true,false);
}else{
   
   $i =0;
   $sSql = " ";
   while ($r = $rs->fetchRow()){
    $i ++;
    IF($i > 1 ){
        $sSql.=  " UNION ";
    }
    
    $sSql.=  "select com_NumComp,".$r['conf_logo']." as conf_logo,
                 '".$r['conf_rutalogo']."' as conf_rutalogo,
                 '".$r['conf_longdef']."' as conf_longdef,
                 /*Columna 1*/
                 ".$r['conf_c1']." as conf_c1,
                 '".$r['conf_textoc1']."' as conf_textoc1,
                 ".$r['conf_datoc1']." as datoc1,
                 '".$r['conf_longc1']."' as conf_longc1,
                 /*Columna 2*/
                 ".$r['conf_c2']." as conf_c2,
                 '".$r['conf_textoc2']."' as conf_textoc2,
                 ".$r['conf_datoc2']." as datoc2,
                 '".$r['conf_longc2']."' as conf_longc2,
                 /*Columna 3*/
                 ".$r['conf_c3']." as conf_c3,
                 '".$r['conf_textoc3']."' as conf_textoc3,
                 ".$r['conf_datoc3']." as datoc3,
                 '".$r['conf_longc3']."' as conf_longc3,
                 /*Columna 4*/
                 ".$r['conf_c4']." as conf_c4,
                 '".$r['conf_textoc4']."' as conf_textoc4,
                 ".$r['conf_datoc4']." as datoc4,
                 '".$r['conf_longc4']."' as conf_longc4,
                 /*Columna 5*/
                 ".$r['conf_c5']." as conf_c5,
                 '".$r['conf_textoc5']."' as conf_textoc5,
                 ".$r['conf_datoc5']." as datoc5,
                 '".$r['conf_longc5']."' as conf_longc5,
                 /*Columna 6*/
                 ".$r['conf_c6']." as conf_c6,
                 '".$r['conf_textoc6']."' as conf_textoc6,
                 ".$r['conf_datoc6']." as datoc6,
                 '".$r['conf_longc6']."' as conf_longc6,
                 /*Columna 7*/
                 ".$r['conf_c7']." as conf_c7,
                 '".$r['conf_textoc7']."' as conf_textoc7,
                 ".$r['conf_datoc7']." as datoc7,
                 '".$r['conf_longc7']."' as conf_longc7,
                 /*Columna 8*/
                 ".$r['conf_c8']." as conf_c8,
                 '".$r['conf_textoc8']."' as conf_textoc8,
                 ".$r['conf_datoc8']." as datoc8,
                 '".$r['conf_longc8']."' as conf_longc8,
                 /*Columna 9*/
                 ".$r['conf_c9']." as conf_c9,
                 '".$r['conf_textoc9']."' as conf_textoc9,
                 ".$r['conf_datoc9']." as datoc9,
                 '".$r['conf_longc9']."' as conf_longc9,
                 /*Columna 10*/
                 ".$r['conf_c10']." as conf_c10,
                 '".$r['conf_textoc10']."' as conf_textoc10,
                 ".$r['conf_datoc10']." as datoc10,
                 '".$r['conf_longc10']."' as conf_longc10
                 FROM tmp_datosfactura ";
   }
   
   $rs = $db->execute($sSql);
   if($rs->EOF){
        fErrorPage('','NO SE GENERO INFORMACION DE LA CABECERA', true,false);
   }else{
        $rs->MoveFirst();
        $aDet =& SmartyArray($rs);
        $Tpl->assign("agData", $aDet);
   }
}


/********************************************************
 *DETALLE DEL DOCUMENTO
 *******************************************************/

$sSql = "SELECT * FROM confDocCab c JOIN confDocDet d ON c.conf_id = d.conf_c1 and c.conf_tdoc = 'FA' and d.conf_seccion = 'D' order by conf_orden";
$rs = $db->execute($sSql);
if($rs->EOF){
    fErrorPage('','NO SE HA DEFINIDO PANTILLA PARA EL DOCUMENTO', true,false);
}else{
   $i =0;
   $sSql = " ";
   while ($r = $rs->fetchRow()){
    $i ++;
    IF($i > 1 ){
        $sSql.=  " UNION ";
    }
    
    $sSql.=  "select '".$r['conf_longdef']."' as conf_longdef,
                 /*Columna 1*/
                 ".$r['conf_c1']." as conf_c1,
                 '".$r['conf_textoc1']."' as conf_textoc1,
                 ".$r['conf_datoc1']." as datoc1,
                 '".$r['conf_longc1']."' as conf_longc1,
                 /*Columna 2*/
                 ".$r['conf_c2']." as conf_c2,
                 '".$r['conf_textoc2']."' as conf_textoc2,
                 ".$r['conf_datoc2']." as datoc2,
                 '".$r['conf_longc2']."' as conf_longc2,
                 /*Columna 3*/
                 ".$r['conf_c3']." as conf_c3,
                 '".$r['conf_textoc3']."' as conf_textoc3,
                 ".$r['conf_datoc3']." as datoc3,
                 '".$r['conf_longc3']."' as conf_longc3,
                 /*Columna 4*/
                 ".$r['conf_c4']." as conf_c4,
                 '".$r['conf_textoc4']."' as conf_textoc4,
                 ".$r['conf_datoc4']." as datoc4,
                 '".$r['conf_longc4']."' as conf_longc4,
                 /*Columna 5*/
                 ".$r['conf_c5']." as conf_c5,
                 '".$r['conf_textoc5']."' as conf_textoc5,
                 ".$r['conf_datoc5']." as datoc5,
                 '".$r['conf_longc5']."' as conf_longc5,
                 /*Columna 6*/
                 ".$r['conf_c6']." as conf_c6,
                 '".$r['conf_textoc6']."' as conf_textoc6,
                 ".$r['conf_datoc6']." as datoc6,
                 '".$r['conf_longc6']."' as conf_longc6,
                 /*Columna 7*/
                 ".$r['conf_c7']." as conf_c7,
                 '".$r['conf_textoc7']."' as conf_textoc7,
                 ".$r['conf_datoc7']." as datoc7,
                 '".$r['conf_longc7']."' as conf_longc7,
                 /*Columna 8*/
                 ".$r['conf_c8']." as conf_c8,
                 '".$r['conf_textoc8']."' as conf_textoc8,
                 ".$r['conf_datoc8']." as datoc8,
                 '".$r['conf_longc8']."' as conf_longc8,
                 /*Columna 9*/
                 ".$r['conf_c9']." as conf_c9,
                 '".$r['conf_textoc9']."' as conf_textoc9,
                 ".$r['conf_datoc9']." as datoc9,
                 '".$r['conf_longc9']."' as conf_longc9,
                 /*Columna 10*/
                 ".$r['conf_c10']." as conf_c10,
                 '".$r['conf_textoc10']."' as conf_textoc10,
                 ".$r['conf_datoc10']." as datoc10,
                 '".$r['conf_longc10']."' as conf_longc10
                 FROM tmp_datosfactura ";
   }
   
//   print($sSql);
   
   $rs = $db->execute($sSql);
}


if($rs->EOF){
    fErrorPage('','NO SE GENERO INFORMACION PARA PRESENTAR', true,false);
}else{
    $rs->MoveFirst();
    $aDet =& SmartyArray($rs);
    $Tpl->assign("agDet", $aDet);
    
    if (!$Tpl->is_cached('InTrTr_facturaImpresa.tpl')) {
            }
    
            $Tpl->display('InTrTr_facturaImpresa.tpl');
}
?>
