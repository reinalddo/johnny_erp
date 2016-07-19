<?php
/*
*   Secuecia de instrucciones para evitar cache de la pagina
*   @author     fah
*   @ver         1.0 Jul/08/07
*
*/
header('Pragma: no-cache ');
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");// always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
?>

