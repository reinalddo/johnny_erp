<?php
/*
 *funcion para exportar datos de una consulta a Excel
 *
 *
 *
 */

if (fGetparam("pExcel",false)){
/*
   header("Content-Type: application/vnd.ms.wmhtml");  // application/vnd.ms-excel   application/x-msdownload
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   header('Content-Disposition: attachment; filename="SmartERP.xls"');
*/

    header('Pragma: public');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
    header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Content-Transfer-Encoding: none');
    header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera
    header('Content-type: application/x-msexcel'); // This should work for the rest
    header('Content-Disposition: attachment; filename="SmartERP.xls"');

}
?>
