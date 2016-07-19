<?
/*
 *   Gris wue se inserta en componentes ya exietntes
 
 
*   Formulario de captura de datos de embarques
*   Utiliza una plantilla Html con  la estructura Basica del un grid ext, en la que se
*   sustituyen los valores requeridos por este script
*   El codigo JS siempre debe colocarse en un archivo con el mismo nombre de este script, pero
*   con extension js.
*   Define inicialmente las instrucciones Sql que debe aplicarse, grabandolas en vars de sesion
**/
//ob_start("ob_gzhandler");
//ob_start();
header("Content-Type: text/html;  charset=ISO-8859-1");
include "../LibPhp/extTpl_2.class.php";
$goPanel = new clsExtTpl("../Ge_Files/GeGeGe_extbody.tpl");
$goPanel->addBodyScript("../LibJava/general");
$goPanel->addBodyScript("Autogrid");
$goPanel->addBodyScript("GridDef");
$goPanel->render();

//include_once "../LibPhp/NoCache.php";
echo $gPaginaHtml;
?>

