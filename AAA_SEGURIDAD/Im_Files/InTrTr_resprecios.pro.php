<?
if (!isset ($_SESSION)) session_start();
/*
*   Generar un string Xml con informacion de Ordenes  por facturar. la sentencia Sql, se asigna via Sesion, o get
*
*/

require "../LibPhp/queryToXml.php";
if (fGetParam("pSql", false)) $sqltext= fGetParam("pSql", false);
fProcesar()
?>
