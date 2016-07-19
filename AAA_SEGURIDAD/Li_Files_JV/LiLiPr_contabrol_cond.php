<?
/*
    PARAMETROS PARA GENERAR EL ROL DE LIQUIDACION
*/
if (!isset ($_SESSION)) session_start();
ob_start("ob_gzhandler");
include_once('GenUti.inc.php');
include "../LibPhp/extTpl.class.php";
include_once "../LibPhp/NoCache.php";
include"../De_Files/DeGeGe_cabecera.php";


header("Content-Type: text/html;  charset=utf-8");
$_SESSION[$gsSesVar . '_defs']['tra_Usuario'] = $_SESSION['g_user'];
$_SESSION[$gsSesVar . '_defs']['tra_Fecha'] = '@date("Y-m-d H:i:s")';

$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extpanels.tpl");
$goPanel->title = $_SESSION["g_empr"] . " - ROL DE LIQUIDACION";
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray")));
$goPanel->addCssFile("../LibJs/ext/resources/css/". (fGetParam("theme", "xtheme-gray"). "-colors"));
$goPanel->addCssFile("../LibJs/ext3/resources/css/iconos");
$goPanel->addBodyScript("../LibJs/ext/ux/menu/EditableItem");
$goPanel->addBodyScript("../LibJs/ext/ux/tree/ColumnNodeUI");
$goPanel->addBodyScript("../LibJs/extEstrPaneles");//Codigo comun que aplica la estructura Basica de paneles
$goPanel->addBodyScript("../LibJs/extExtensions");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.gen.cmbBox");
$goPanel->addBodyScript("../LibJs/extAutogrid");
$goPanel->addBodyScript("../LibJs/ext/ux/Ext.ux.grid.printergrid_0.0.1");
$goPanel->addBodyScript("../LibJs/ext/ux/grid/GridFilters");
$goPanel->addBodyScript("../LibJs/ext/ux/grid/filter/Filter.js");
$loadJSFile='../LiLiPr_contabrol_cond';


$db->debug = fGetParam('pAdoDbg', false);
$gsSesVar = "LiContabRol";
//Prefijo de Id se variable de sesion.
$_SESSION[$gsSesVar . '_defs']['start']=0;                              //Valores Operativos minimos para los grids
$_SESSION[$gsSesVar . '_defs']['limit']=25;                             //Valores Operativos minimos para los grids

if(fGetParam('pAdoDbg',false)) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);
$glExpanded = fGetParam('pExpan', 1);
$_SESSION[$gsSesVar. "_aplic"]= "";

$goPanel->render();

?>