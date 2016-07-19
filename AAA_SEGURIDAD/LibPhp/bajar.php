<?php
$id = $_GET['pOrig']; // Origen
$dest= $_GET['pDest'];// Destino
$enlace = $id;
header ("Expires: Mon, 27 jul 1997 :: GMT");
header ("Last-Modified: " . gmdate("D, d M Y H H:i:s") . " GMT");
header ("Cache-Control: no-store, no-cache, must-revalidate");
header ("Cache-Control: post-check=0, pre-check=0, false");
header ("Pragma: no-cache");
header ("Content-Disposition: attachment; filename=".$dest."\n\n");
header ("Content-Type: application/force-download");
header ("Content-Length: ".filesize($enlace));
header ("Connection: Keep-Alive");
set_time_limit(0);
$fArch = fopen($enlace, "r");
ob_start();
//readfile($enlace);
$slContenido = fread($fArch, filesize($enlace));
echo $slContenido;
ob_end_flush();
?>

