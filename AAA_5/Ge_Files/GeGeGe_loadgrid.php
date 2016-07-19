<?
/*
*   Presentecion de un Grid que se inserta en componentes ya exietntes, no genera codigo html de la pagina,
*   solo el requerido para armar el componente Ext
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Este archivo se incluye para compatibilidad con otros script que renderizan el grid directamente.
*   @see GeGeGe_loadgrid_basico.php
**/
include ("../Ge_Files/GeGeGe_loadgrid_basico.php");
$goGrid->render();
//echo " <!--" . $_SESSION[$gsSesVar] . "-->"; 
//include_once "../LibPhp/NoCache.php";
//echo $gPaginaHtml;
?>

