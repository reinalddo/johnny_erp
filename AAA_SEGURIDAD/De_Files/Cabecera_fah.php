<?php
/**
*Script para incluir la cabecera en una pagina, no generada por CCS, pero de igual funcionalidad,
*con validacion de privilegios de acceso
*   @Crea   fah  Oct 10 /04
**/

    define("RelativePath", "../");
    include_once("General.inc.php");
    include_once("../Common.php");
    include_once("../LibPhp/SegLib.php");
    $filename = "../De_Files/Cabecera.html";
    $f = fopen ($filename, "r");
    $contents = fread ($f, filesize ($filename));
    fclose ($f);
    $contents=str_replace("{lbEmpresa}", $_SESSION["g_empr"] ? $_SESSION["g_empr"]: "??????", $contents) ;
    $contents=str_replace("{lbUsuario}", fTraeUsuario(), $contents) ;
    $contents=str_replace("{lbFecha}", date("F j, Y, g:i a"), $contents) ;
    echo "<html><head><head><body>$contents</body></html>";
    $FileName=substr(basename($_SERVER["PHP_SELF"], ".php") ,0,6); //                          Definicion obligatoria para que funciones Seglib
	if (!fValidAcceso("","","")) {
    	fMensaje ("UD. NO TIENE ACCESO A ESTE MODULO", 1);
        die();
		}
?>

