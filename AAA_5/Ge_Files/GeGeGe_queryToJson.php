<?php
/*
*   Generar un string JSON con informacion de Base de datos.
*	La sentencia Sql, se asigna via Sesion, o get
*/
ob_start();
if (!isset ($_SESSION)) session_start();
$db->debug = $_SESSION['pAdoDbg'] >= 2;
//print_r($_SESSION);
include('../LibPhp/queryToJson.class.php');
$data = new clsQueryToJson("data", true);
$data->getJson();
ob_end_flush();
?>
