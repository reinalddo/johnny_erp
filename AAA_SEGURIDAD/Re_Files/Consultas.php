<?php
error_reporting(E_ALL);
define("RelativePath", "..");
include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("CMysqlConnection.php");
include_once("Folio.php");
extract($_REQUEST);

$objFolio = new Folio();
echo $objFolio->getRecordByDescription($txtDescripcion);