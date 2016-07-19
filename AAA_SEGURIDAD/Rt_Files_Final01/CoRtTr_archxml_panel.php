<?
/**
 *  ESL 18/04/2011
 *  PANEL - INGRESO DE DAUS Y ANULADOS PARA EL ANEXO
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
$goPanel->title = $_SESSION["g_empr"] . " - Anexo";
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
$loadJSFile='../CoRtTr_archxml_panel';


$db->debug = fGetParam('pAdoDbg', false);
$gsSesVar = "RtAnx";
//Prefijo de Id se variable de sesion.
$_SESSION[$gsSesVar . '_defs']['start']=0;                              //Valores Operativos minimos para los grids
$_SESSION[$gsSesVar . '_defs']['limit']=25;                             //Valores Operativos minimos para los grids
//print_r($_SESSION);
if(fGetParam('pAdoDbg',false)) $_SESSION['pAdoDbg']= fGetParam('pAdoDbg',false);
$glExpanded = fGetParam('pExpan', 1);
$_SESSION[$gsSesVar. "_aplic"]= "";

$goPanel->render();



$_SESSION["CoRtTr_AnxMeses"] = "select par_Secuencia cod
                                      ,concat(par_Secuencia,' - ',par_Descripcion) txt
                                from genparametros WHERE par_Clave = 'LMESES'
                                and CONCAT(par_Secuencia,' - ',UPPER(par_Descripcion)) LIKE upper('%{query}%')";

$_SESSION["CoRtTr_AnxTipoCmp"] = "SELECT tab_Codigo AS cod , CONCAT(tab_codigo,'-',tab_Descripcion) AS txt FROM fistablassri
                                WHERE tab_CodTabla = 2
                                and tab_Codigo in (1,3,4,7) /*Solicitado por Wacho solo 4 comprobantes*/
                                /* and CONCAT(tab_codigo,'-',UPPER(tab_Descripcion)) LIKE upper('%{query}%') */ ";

                                
;                                
                                
$_SESSION["CoTrTr_anxDaus"] = " SELECT * FROM fis_dau             
                                WHERE dau_estado = 1 
                                AND dau_aniocarga = {panio} AND dau_mescarga = {pmes} ";

$_SESSION["CoTrTr_anxAnulados"] = " SELECT * FROM fisanulados             
                                WHERE estado = 1 
                                AND anio = {panio} AND mes = {pmes} ";
                                                                

                                

//CoTrTr_anxAnulados

?>