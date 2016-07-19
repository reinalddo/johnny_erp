<?
if (!isset ($_SESSION)) session_start();
/*
*   Generar un string Xml con informacion de Ordenes  por facturar. la sentencia Sql, se asigna via Sesion, o get
*
*/
define("RelativePath", "../");
require_once("../Common.php");
//require_once("../LibPhp/SegLib.php");

require_once('adodb/adodb.inc.php');
require_once('adodb/tohtml.inc.php'); 
require_once('../LibPhp/fetchUtil.class.php');
$data_base = 'mysql';
$user = 'exportuser';
$passwd = 'Exp2008Dusal';
$host = 'localhost';
$db_name = '08_jorcorp';
$dsn = "$data_base://$user:$passwd@$host/$db_name?clientflags=65536";
$db = new FetchUtil($dsn);
//if (fGetParam("pSql", false)) $sqltext= fGetParam("pSql", false);
include_once ("GenUti.inc.php");
$pSem = fGetParam("pSem", -1);
$pEmb = fGetParam("pEmb", -1);
$pDbg = fGetParam("pAdoDbg", 0);
$db->DB->debug = $pDbg;
//$gsSqlText = "call spLiqGetMagDat($pSem, $pEmb )";
//fProcesar();
//require "../Ge_Files/GeGeGe_queryToXml.php";

$gsSqlText = "call spLiqGetMagDat($pSem, $pEmb)";
$glHtml= $db->FetchAllHtml($gsSqlText, "BORDER='1' CELLSPACING=0 WIDTH='98%'", array("PRODUCTOR", "NUMERO", "CODPRO", "HDA", "INSCRIP", "APELLIDOS", "NOMBRES", "HACIENDA",
                                            "INSC", "HAS", "CAJAS", "TIPO", "TEC", "SEMI", "PROV", "CANT", "ZONA", "SECT"));
echo $glHtml;

?>
