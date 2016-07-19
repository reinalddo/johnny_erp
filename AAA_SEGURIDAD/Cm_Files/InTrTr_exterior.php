
<?php
include_once('./AAA/AAA_7_3/LibPhp/clsDirectCrud.php');  // To access files from Direct-Router, must be directed from this path, so need a extra level
/*
 * @class operaciones
 * Copyright(c) 2010-2015
 * @rev	jvl	05/05/2011	Agregar funcion para busqueda de parametro por clave y secuencia
 */

if (!isset ($_SESSION)) session_start();

include("GenUti.inc.php");
$FileName=basename($_SERVER["PHP_SELF"], ".php"); //                          Definicion obligatoria para que funciones Seglib
$HtmlFile = "InTrTr_Exterior" .".html";
$gf = fopen ($HtmlFile, "r");           //    Cargar Plantilla HTML
$gPaginaHtml = fread ($gf, filesize ($HtmlFile));
fclose ($gf);
if (strpos($gPaginaHtml, "{Cabecera}") >= 0){
    include_once "../De_Files/DeGeGe_cabecera.php";
    $olCabecera = new clsCabecera();
    $gPaginaHtml= str_replace("{Cabecera}",  $olCabecera->getHtmlOut(), $gPaginaHtml) ;
    }

echo $gPaginaHtml;
require "../LibPhp/chklang.php";
?>

