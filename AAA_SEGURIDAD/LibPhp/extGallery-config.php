<?php
include_once('genFire.php');
$fp = FirePHP::getInstance(true);
function debugOut($pLab, $pDat, $pMod = "log"){
	if(fGetParam('pAppDbg', false)){
		global $fp;
		  if (isset($fp)){
			 $fp->log($pDat, $pLab);
		  }
	   }
	}
$pDir 		= 	fGetParam('pPath', '/images');  // Path referencial, en base a raiz de la aplicacion ó Absoluto
if (substr($pDir,0,1) != '/')
    $dir_base = '../' . $pDir;                  //  Si no es path absoluto, unbica la correcta posicion de
else
    $dir_base =  $pDir;
$dir_to_scan= $dir_base. "/images/";    // DIrectorio a listar, deoende de la relatividad de las rutas
$dir        = $pDir . "/images/";       // Directorio de Imagenes. path relativo a la raiz de la aplicacion ó Absoluto
$dir_thumbs = $pDir . "/thumbs/";       // Directorio de Viñetas . path relativo a la raiz de la aplicacion ó Absoluto

debugOut("scandir  ", $scanDir);
debugOut("dir  ",  $pDir);
debugOut("thums ", $dir_thumbs);
//$serverPath = realpath($_SERVER["DOCUMENT_ROOT"]);
//debugOut("Param dir ", $pDir);
//debugOut("pat path ", $pathpath);
//debugOut("serverpath ", $serverPath);
//$pathPath   = substr($pathpath,strlen($serverPath));
//$baseDir    = $pathpath;

?>