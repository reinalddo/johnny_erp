<?php
/*
 *  Dicion de una tarja en dos detalles, para ingresar precios parciales
 *  @rev    fah May/05/09   Correccion de cantidades a hgrabar, Validar la grabacion si la cantidad a dividir es > 0
 **/
include_once("adodb.inc.php");
require_once('adodb-active-record.inc.php');
include_once("General.inc.php");
define("RelativePath", "..");
include_once("GenUti.inc.php");
/*include_once("adodb.inc.php");
include_once("../LibPhp/ConLib.php");
*/
$gbTrans	= false;
$db = Null;
$cla=null;
$olEsq=null;
$ADODB_FETCH_MODE = ADODB_FETCH_BOTH;
$db = &ADONewConnection('mysql');
$db = NewADOConnection('mysql://' . DBUSER . ":" . DBPASS . "@" . DBSRVR . "/" . DBNAME);
//$db->PConnect(DBSRVR, DBUSER, DBPASS, DBNAME);
//$db = NewADOConnection('mysql://root:pwd@localhost/dbname');
$db->autoRollback = true;
$db->debug = fGetParam("pAdoDbg",0);
ADOdb_Active_Record::SetDatabaseAdapter($db);
class detTarjaClass extends ADOdb_Active_Record{}
$goDet = new detTarjaClass("liqtarjadetal");
$alFlds = $goDet->getAttributeNames();
$gPar=fGetAllParams();
$goDet->load("tad_Numtarja=? and tad_secuencia=?", array($gPar["tad_NumTarja"], $gPar["tad_Secuencia"]));
print_r($goDet); //dbg
//$goDet->tad_valunitario;        // precio oficial
//$goDet->tad_difunitario;        // Diferencia de precio
$goDet->tad_cantrecibida =   $goDet->tad_cantrecibida - $gPar["txt_Cant2"];
$goDet->tad_difunitario  =   $goDet->tad_valunitario - $gPar["txt_Prec1"]; 
$ok = $goDet->replace(); // 0=failure, 1=update, 2=insert

if ($gPar["txt_Cant2"] <> 0){     // Si la segunda cantidad es diferente de cero, generar el segundo registro
    $goDet1=new detTarjaClass("liqtarjadetal");
    foreach($alFlds as $k => $v){
        $goDet1->$v = $goDet->$v;
    }
    $goDet1->tad_secuencia      = 0  ;  // genero una nueva secuencia
    $goDet1->tad_cantrecibida   = $gPar["txt_Cant2"];
    $goDet1->tad_cantrechazada  = 0;
    $goDet1->tad_cantcaidas     = 0;
    $goDet1->tad_difunitario    = $goDet->tad_valunitario  - $gPar["txt_Prec2"];
    $ok = $goDet1->replace(); // 0=failure, 1=update, 2=insert
}
// or using bind parameters
echo"{'success':1}"
?>