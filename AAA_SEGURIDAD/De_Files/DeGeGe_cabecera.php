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
/**
*   Cabecera de Paginas Generadas manualmente, sin uso del Generador de Codigo
**/	
	class clsCabecera { 
    /**
    *   Salida Html
    **/
    var $HtmlOut;
    /**
    *   Inicializador de Clase
    *   @param pArch   string      Nombre de archivo que contiene el codigo HTML de la cabecera
    *   @return void
    **/
    function clsCabecera($pArch = "../De_Files/Cabecera.html")
    {
        global $FileName;
        $f = fopen ($pArch, "r");
        $this->HtmlOut = fread ($f, filesize ($pArch));
        fclose ($f);
        $this->HtmlOut=str_replace("{lbEmpresa}", $_SESSION["g_empr"] ? $_SESSION["g_empr"]: "??????", $this->HtmlOut) ;
        $HtmlOut=str_replace("{lbUsuario}", fTraeUsuario(), $HtmlOut) ;
        $this->HtmlOut=str_replace("{lbFecha}", date("F j, Y, g:i a"), $this->HtmlOut) ;
        if (!isset($FileName))
            $FileName=substr(basename($_SERVER["PHP_SELF"], ".php") ,0,6); //                          Definicion obligatoria para que funciones Seglib
    	if (!fValidAcceso("","","")) {
        	fMensaje ("UD. NO TIENE ACCESO A ESTE MODULO", 1);
            die();
    		}
    	unset($f);
    }
    /*
    *   Devuelve el codigo Html de la cabecera
    *   @return string
    */
    function getHtmlOut(){
        return $this->HtmlOut;
    }
}
if ($_GET["dbg"] == 1) {
    $FileName="SvTrTr";
    $olCabecera = new clsCabecera();
    echo  $olCabecera->getHtmlOut();
    print_r($olCabecera);
}
?>

