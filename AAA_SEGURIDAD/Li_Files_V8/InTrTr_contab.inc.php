<?php
/** Contabilizacion de Transacciones de Inventario
*   Genera un el detalle contable de una Tr de Inventario, utilizando la misma cabecera
*   @Crea   fah  Feb 18/04
*   @Rev    fah  JUl/14/04
*   @Rev    fah  Ene/15/05
**/
error_reporting (E_ALL);
// Solo en caso de depuracion
if (!isset($_SESSION["g_user"]) && (isset($_SESSION["ByPass"]) && $_SESSION["ByPass"]=='prueba'))
{
    define("RelativePath", "..");
    include_once("adodb.inc.php");
    include_once("tohtml.inc.php");
    include_once("../Common.php");
    include_once("../LibPhp/ConLib.php");
    include_once("GenUti.inc.php");
}
else include ("../LibPhp/LibInc.php");   // para produccion
include_once("LiLiPr_func.inc.php");
include_once("GenUti.inc.php");
include_once("../Co_Files/ConTranLib.php");
//
function fSelTransacc($db,

